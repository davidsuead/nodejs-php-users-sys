<?php

namespace App\Validation;

use Exception;
use LogicException;
use Respect\Validation\Validator;
use Psr\Container\ContainerInterface;

/**
 * Classe padrão para validação de formulários
 * @author David Diniz <diniz.david@gmail.com>
 * @since 1.0.0
 */
class Validation 
{
    /**
     * Array com mensagens padrões
     * @var array
     */
    public $messages = [
        'notEmpty' => 'Campo Obrigatório',
        'length' => [
            'both' => 'O campo deve conter entre {{minValue}} e {{maxValue}} caracteres',
            'min'  => 'O campo deve conter no mínimo {{minValue}} caracteres',
            'max'  => 'O campo deve conter no máximo {{maxValue}} caracteres',
        ],
        'date' => 'Data inválida',
        'email' => 'E-mail inválido',
        'phone' => 'Telefone inválido',
        'intVal' => 'O campo precisa ser um número inteiro',
        'alnum' => 'O campo deve conter apenas letras e números',
        'noWhitespace' => 'O campo não pode conter espaços em branco',
        'url' => 'URL inválida',
        'in' => 'O campo precisa conter o conjunto de valores: {{haystack}}',
        'digit' => 'O campo deve conter somente dígitos',
        'json' => 'O campo precisa ser um json válido',
    ];
    
    /**
     * Array com regras de validação para cada campo do formulário
     * @var array
     */
    protected $rules = array();

    /**
     * Recipiente para injeção de dependência
     *
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Define o cenário que as regras de validação devem ser empregadas
     *
     * @var string
     */
    public $scenario;

    /**
     * The HTTP status code
     *
     * @var int|null
     */
    protected $code = null;

    /**
     * Construção da classe FormValidation
     *
     * @param ContainerInterface $container
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * Retorna The HTTP status code das validações feitas
     *
     * @return int
     */
    public function getCode()
    {
        if($this->container->validator->isValid()) {
            $this->code = $this->code ?? $this->container->constante['HTTP_STATUS_CODE']['VALID'];
        } else {
            $this->code = $this->code ?? $this->container->constante['HTTP_STATUS_CODE']['INVALID'];
        }
        
        return $this->code;
    }
    
    /**
     * Retorna um array que define as regras de validação que devem ser usadas em cada atributo
     * @return array<[rule,attributes[],params[]]>
     */
    protected function attributeRules()
    {
        return [];
    }

    /**
     * Verifica se regra corrente pode ser criada
     *
     * @param array $params
     * @return boolean
     */
    private function canCreate($params)
    {
        if(empty($params['on'])) {
            return true;
        } else if(!empty($this->scenario)) {
            if(is_array($params['on']) && in_array($this->scenario, $params['on'])) {
                return true;
            } else if(!is_array($params['on']) && $params['on'] == $this->scenario) {
                return true;
            }
        }

        return false;
    }
    
    /**
     * Retorna um array de regras de validação dos atributos informados em static::attributeRules()
     * @return array
     */
    public function getRules()
    {
        try {
            $attributeRules = $this->attributeRules();
            if(empty($attributeRules) || !is_array($attributeRules) || count($attributeRules) == 0){
                throw new LogicException('Não foi definido o conjunto de regras dos atributos');
            }
            foreach($attributeRules as $row){
                foreach($row[1] as $attribute){
                    if($this->canCreate($row[2] ?? [])) {
                        if(!isset($this->rules[$attribute]['rules'])) {
                            $this->rules[$attribute]['rules'] = new Validator();
                        }
                        if(isset($row[2]['on'])) {
                            unset($row[2]['on']);
                        }
                        $this->create($row[0], $attribute, $row[2] ?? array());
                    }
                }
            }
            return $this->rules;
        } catch (LogicException $ex) {
            throw new Exception(
                'Erro de lógica: ' . $ex->getMessage()
                . ' na linha "' . $ex->getLine() . '" '
                . ' no arquivo "' . $ex->getFile() . '".'
            );
        }
    }
    
    /**
     * Cria a regra de validação usando \Respect\Validation\Validator
     * @param string $rule - Nome da regra de validação
     * @param string $attribute - Nome do campo que deve ser aplicado a validação
     * @param array $params - Parâmetros que podem existir na chamada da função de validação
     */
    private function create($rule, $attribute, $params = array())
    {
        $message = null;
        switch($rule){
            case 'length':
                $message = $this->validateLength($rule, $attribute, $params);
                break;
            case 'date':
                $this->rules[$attribute]['rules']->$rule($params['format']);
                break;
            case 'alnum':
            case 'digit':
                $this->validateWithAdditionalChars($rule, $attribute, $params);
                break;
            case 'in':
                $this->rules[$attribute]['rules']->$rule($params['range']);
                break;
            default:
                if(!empty($params) || !is_callable($this->rules[$attribute]['rules'], $rule)) {
                    throw new LogicException('Chamada inválida da função "' . $rule . '" em ' . get_class($this));
                }
                $this->rules[$attribute]['rules']->$rule();
        }
        $this->setMessage($rule, $attribute, $message);
    }
    
    /**
     * Realiza a validação da quantidade caracteres de um campo
     * @param string $rule - Nome da função de validação (length)
     * @param string $attribute - Nome do campo que deve ser aplicado a função de validação
     * @param array $params - Parâmetros que devem ser informados na chamada da função de validação
     * @return string - Retorna mensagem de erro no caso do campo não ser validado
     * @throws LogicException - Lança uma exceção lógica quando não informa o mínimo/máximo de caracteres
     * que o campo deve conter
     */
    private function validateLength($rule, $attribute, $params)
    {
        if(!isset($params['min']) && !isset($params['max'])){
            throw new LogicException('Precisa definir pelo menos um parâmetro: mínimo ou máximo.');
        }
        $this->rules[$attribute]['rules']->$rule($params['min'] ?? null, $params['max'] ?? null);
        $message = $this->messages[$rule]['both'];
        if(!isset($params['min'])) {
            $message = $this->messages[$rule]['max'];
        } else if(!isset($params['max'])) {
            $message = $this->messages[$rule]['min'];
        }
        return $message;
    }

    /**
     * Valida o input conforme regra informada, porém aceita alguns caracteres adicionais como parâmetros
     *
     * @param string $rule
     * @param string $attribute
     * @param array $params
     * @return void
     */
    private function validateWithAdditionalChars($rule, $attribute, $params)
    {
        if(!empty($params['additionalChars']) && !is_array($params['additionalChars'])) {
            $this->rules[$attribute]['rules']->$rule($params['additionalChars']);
        } else {
            $this->rules[$attribute]['rules']->$rule();
        }
    }

    /**
     * Define uma mensagem de erro na validação
     *
     * @param string $rule - Nome da função de validação
     * @param string $attribute - Nome do campo que deve ser aplicado a função de validação
     * @param string $message - Mensagem que deve ser usada na validação
     * @return void
     */
    private function setMessage($rule, $attribute, $message = null)
    {
        if(!empty($message) || !empty($this->messages[$rule])) {
            $this->rules[$attribute]['messages'][$rule] = $message ?? $this->messages[$rule];
        }
    }
}
