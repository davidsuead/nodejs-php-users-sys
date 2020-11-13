<?php

namespace App\Swagger;

use stdClass;
use Exception;
use Psr\Container\ContainerInterface;

/**
 * Define documentação swagger de uma tag
 * @author David Diniz <diniz.david@gmail.com>
 * @since 1.0.0
 */
abstract class AbstractTag
{
    /**
     * Recipiente para injeção de dependência
     *
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Objeto swagger para documentação
     *
     * @var stdClass
     */
    protected $swagger;

    /**
     * Array com parâmetros para definição da tag
     *
     * @var array
     */
    protected $tag;

    /**
     * Lista de statusCode padrão
     *
     * @var array
     */
    private $arrStatusCode = [200, 400, 401, 500];

    /**
     * Lista de descrição de statusCode padrão
     *
     * @var array
     */
    private $descriptions = [
        200 => 'Retorno de um processamento realizado com sucesso',
        400 => 'Retorno com mensagem de exceções',
        401 => 'Código authorization não válido',
        500 => 'Servidor indisponível ou erro interno',
    ];

    /**
     * Lista de propriedades obrigatórios para definição 
     * de um parâmetro de requisição na documentação
     *
     * @var array
     */
    private $paramRequiredProperties = ['name', 'description'];

    /**
     * Lista de valores padrão para propriedades essencias a um parâmetro
     * de requisição na documentação
     *
     * @var array
     */
    private $paramDefaultProperties = [
        'in' => 'query',
        'type' => 'string',
        'required' => true,
    ];

    /**
     * Lista de propriedades para definição do parâmetro de autorização no header
     *
     * @var array
     */
    private $paramAuth = [
        'name' => 'Authorization',
        'description' => 'Token para acesso: Bearer {token} . Ex.: Bearer q223i4wkkwem23',
        'in' => 'header'
    ];

    /**
     * Na construção da classe define o container, o objeto swagger já instanciado e deve receber um array
     * com os parâmetros de definição da tag
     *
     * @param ContainerInterface $container
     * @param stdClass $swagger
     * @param array $tag
     */
    public function __construct($container, $swagger, $tag)
    {
        $this->container = $container;
        $this->swagger = $swagger;
        
        if(!isset($tag['name'])) {
            throw new Exception('Não foi definido o name da tag para classe "' . get_class($this) . '"');
        }
        $this->tag = $tag;
    }

    /**
     * Define todas as documentações de endpoint da tag.
     * Aqui deve ser definido os métodos que descreve a documentação de cada endpoint da tag
     *
     * @return void
     */
    abstract public function setRoutes();

    /**
     * Define um parâmetro de um $pathName
     *
     * @param string $pathName
     * @param array $param
     * @return void
     */
    protected function setParameter($pathName, array $param, $method = 'get')
    {
        if(empty($pathName)) {
            throw new Exception('O nome do "path" não está definido');
        }

        if(!isset($this->swagger->paths[$pathName]->$method->parameters)) {
            $this->swagger->paths[$pathName]->$method->parameters = [];
        }

        if(count($param) == 0) {
            throw new Exception('Lista de propriedades do parâmetro está vazia');
        }

        $keys = array_keys($param);

        foreach($this->paramRequiredProperties as $propertyName) {
            if(!in_array($propertyName, $keys)) {
                throw new Exception('Não foi definido a propriedade "' . $propertyName . '"');
            }
        }

        $parameter = new \stdClass();
        foreach($param as $propertyName => $desc) {
            if(($propertyName == 'type' && $desc != false) || ($propertyName != 'type')) {
                $parameter->$propertyName = $desc;
            }
        }
        
        foreach($this->paramDefaultProperties as $propName => $propValue) {
            if(!in_array($propName, $keys)) {
                $parameter->$propName = $propValue;
            }
        }

        $this->swagger->paths[$pathName]->$method->parameters[] = $parameter;
    }

    /**
     * Define diversos parâmetros de um $pathName
     *
     * @param string $pathName
     * @param array $params
     * @param boolean $authDefault
     * @return void
     */
    protected function setParameters($pathName, array $params, $authDefault = true, $method = 'get')
    {
        if(empty($pathName)) {
            throw new Exception('O nome do "path" não está definido');
        }

        if(count($params) == 0){
            throw new Exception('A lista de parâmetros está vazia');
        }

        foreach($params as $param) {
            $this->setParameter($pathName, $param, $method);
        }

        if($authDefault) {
            $this->setParameter($pathName, $this->paramAuth, $method);
        }
    }

    /**
     * Define as propriedades de uma definition
     *
     * @param string $definitionName - Nome da definition
     * @param \stdClass $definition - Objeto definition
     * @param array $properties - Lista de propriedades
     * @return void
     */
    private function setDefinitionProperties($definitionName, &$definition, array $properties)
    {
        if(empty($properties) || count($properties) == 0) {
            throw new Exception('Não foi definido as propriedades da definition "' . $definitionName . '"');
        }
        $definition->properties = [];
        foreach($properties as $propertyName => $params) {
            $property = new \stdClass();
            foreach($params as $paramName => $paramValue) {
                if(is_array($paramValue)) {
                    $property->$paramName = [];
                    foreach($paramValue as $name => $value) {
                        $property->$paramName[$name] = $value;
                    }
                } else {
                    $property->$paramName = $paramValue;
                }
            }
            $definition->properties[$propertyName] = $property;
        }
    }

    /**
     * Define as definitions e suas propriedades
     *
     * @param array $definitions
     * @return void
     */
    protected function setDefintions(array $definitions)
    {
        if(empty($definitions) || count($definitions) == 0) {
            throw new Exception('Não foi definido os parâmetros de definitions');
        }
        if(!isset($this->swagger->definitions)) {
            $this->swagger->definitions = [];
        }
        foreach($definitions as $definitionName => $params) {
            if(!isset($this->swagger->definitions[$definitionName])) {
                $definition = new \stdClass();
                if(empty($params) || !is_array($params) || count($params) == 0) {
                    throw new Exception('Os parâmetro da definition "' . $definitionName . '" estão inválidos');
                }
                foreach($params as $paramName => $paramValue) {
                    if($paramName == 'properties') {
                        $this->setDefinitionProperties($definitionName, $definition, $paramValue);
                    } else {
                        $definition->$paramName = $paramValue;
                    }
                }
                $this->swagger->definitions[$definitionName] = $definition;
            }
        }
    }

    /**
     * Define a documentação de uma response
     *
     * @param string $pathName - Nome do path
     * @param int $statusCode - HTTP status code
     * @param array $arrResponse - Descrição da response
     * @param array $definitions - Lista de definitions e suas respectivas propriedades
     * @param string $method - HTTP method (get, post)
     * @return void
     */
    protected function setResponse($pathName, $statusCode, array $arrResponse, array $definitions, $method = 'get')
    {
        if(empty($pathName)) {
            throw new Exception('O nome do "path" não está definido');
        }
        if(empty($statusCode)) {
            throw new Exception('Não foi definido o statusCode');
        }
        if(empty($arrResponse) || count($arrResponse) == 0) {
            throw new Exception('Não foi definido os parâmetros do response');
        }
        if(!isset($this->swagger->paths[$pathName]->$method->responses)) {
            $this->swagger->paths[$pathName]->$method->responses = [];
        }
        
        $response = new \stdClass();
        foreach ($arrResponse as $paramName => $paramValue) {
            if(is_array($paramValue)) {
                $response->$paramName = new \stdClass();
                $response->$paramName = [];
                foreach($paramValue as $name => $value) {
                    $response->$paramName[$name] = $value;
                }
            } else {
                $response->$paramName = $paramValue;
            }
        }
        $this->swagger->paths[$pathName]->$method->responses[$statusCode] = $response;
        $this->setDefintions($definitions);
    }

    /**
     * Define documentação padrão de uma response
     *
     * @param string $pathName - Nome do path
     * @param int $statusCode - HTTP status code
     * @param string $definitionName - Nome da definition
     * @param array $properties - Lista de propriedades da definition
     * @param string $responseDesc - Descrição de uma response
     * @param string $method - HTTP method (get, post)
     * @return void
     */
    protected function setDefaultResponse($pathName, $statusCode, $definitionName, array $properties, $responseDesc = null, $method = 'get')
    {
        if(empty($pathName)) {
            throw new Exception('O nome do "path" não está definido');
        }

        if(count($properties) == 0){
            throw new Exception('Lista de propriedades para definição da resposta está vazia.');
        }

        if(!isset($this->swagger->definitions)) {
            $this->swagger->definitions = [];
        }

        if(!isset($this->swagger->paths[$pathName]->$method->responses)) {
            $this->swagger->paths[$pathName]->$method->responses = [];
        }

        $arrResponse = [
            'description' => $responseDesc ?? ($this->descriptions[$statusCode] ?? 'Sem descrição'),
            'schema' => [
                '$ref' => "#/definitions/" . $definitionName
            ],
        ];

        $newProp = [];
        foreach($properties as $propertyName => $propertyType) {
            $newProp[$propertyName] = ['type' => $propertyType];
        }
        $definitions = [
            $definitionName => [
                'type' => 'object',
                'properties' => $newProp
            ],
        ];

        $this->setResponse($pathName, $statusCode, $arrResponse, $definitions, $method);
    }

    /**
     * Define documentação de diversas respostas para diversos statusCode
     *
     * @param string $pathName
     * @param array $arrStatus[200,400,401...]
     * @param array $properties
     * @param array $defintions
     * @param array $descriptions
     * @return void
     */
    protected function setManyDefaultResponse($pathName, $arrStatus = [], $properties = [], $defintions = [], $descriptions = [], $method = 'get')
    {
        if(empty($pathName)) {
            throw new Exception('O nome do "path" não está definido');
        }

        if(empty($arrStatus) || count($arrStatus) == 0) {
            $arrStatus = $this->arrStatusCode;
        }
        
        if(empty($properties) || count($properties) == 0) {
            $properties = ['message' => 'string'];
        }

        foreach($arrStatus as $statusCode) {
            $definitionName = !empty($defintions[$statusCode]) ? $defintions[$statusCode] : 'returnDefaultResponse' . $statusCode;
            $this->setDefaultResponse($pathName, $statusCode, $definitionName, $properties, $descriptions[$statusCode] ?? null, $method);
        }
    }

    /**
     * Retorna a descrição de uma response pelo HTTP status code
     *
     * @param int $statusCode
     * @return string
     */
    public function getResponseDesc($statusCode)
    {
        return $this->descriptions[$statusCode];
    }
}