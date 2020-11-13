<?php

namespace App\Service;

use LogicException;
use Doctrine\ORM\EntityRepository;
use Psr\Container\ContainerInterface;

/**
 * Classe service modelo para demais services
 * @author David Diniz
 */
class ModelService 
{
    /**
     * Recipiente para injeção de dependência
     * 
     * @var ContainerInterface
     */
    protected $container;
    
    /**
     * Nome da classe entidade
     * 
     * @var string
     */
    protected static $entityClass;

    /**
     * Erros gerados na validação dos dados da entity
     *
     * @var array
     */
    protected $_errors = array();

    /**
     * Relação nome da coluna no banco de dados com o parâmetro utilizado na API
     *
     * @var array
     */
    protected $colsToParams = [];

    /**
     * Cria instância da service e define a entidade
     * 
     * @param ContainerInterface $container
     */
    public function __construct($container) 
    {
        $this->container = $container;
        if(empty(static::$entityClass)){
            throw new LogicException(get_class($this) . ' must have a static $entityClass');
        }
    }
    
    /**
     * Obtém o repositório da entidade
     *
     * @return EntityRepository
     */
    public function getRepository()
    {
        return $this->container->em->getRepository(static::$entityClass);
    }
    
    /**
     * Obtém a referência da entidade
     * 
     * @param integer $id - PK da entidade
     * @return object The entity reference
     */
    public function getReference($id) 
    {
        return $this->getRepository()->getReference($id);
    }

    /**
     * Retorna um array de erros gerados na validação dos campos
     * @return array $errors
     */
    public function getErrors()
    {
        return $this->_errors;
    }

    /**
     * Retorna a descrição do parâmetro relacionado ao nome do campo
     *
     * @param string $name - Nome da coluna no banco de dados
     * @return string
     */
    public function getParamName($name)
    {
        return $this->colsToParams[$name] ?? $name;
    }
    
    /**
     * Obtém objeto da entidade definida
     *
     * @param integer $id - PK da entidade
     * @return object
     */
    public function buscar($id) 
    {
        return $this->getRepository()->find($id);
    }
    
    /**
     * Obtém um array de objetos da entidade definida
     *
     * @return object[]
     */
    public function listaTodos() 
    {
        return $this->getRepository()->findAll();
    }
    
    /**
     * Registra atividades realizadas na aplicação
     * 
     * @param array $params - Array com os parâmetros enviados na requisição do serviço
     * @param string $method - Método utilizado na ação
     * @param string $titulo - Título da ação a ser registrada
     * @param string $descricao - Descrição da ação a ser registrada
     */
    public function log($params, $method, $titulo, $descricao)
    {
        $action = $method == 'PUT' ? 'Editou' : 'Criou';
        $paramsAtividadeSistema = [
            'descTituloLog' => "{$action} {$titulo}",
            'infoAcaoUsuario' => "{$descricao}:" . json_encode($params),
            'numrCpfRespAcao' => $this->container->session->get('USUARIO') != null ? $this->container->session->get('USUARIO')['cpf'] : ''
        ];
        $this->container->registroAtividadeSistemaService->save($paramsAtividadeSistema);
    }
    
    /**
     * Realiza o retorno após a gravação dos dados no banco
     * 
     * @param string $method - Método da requisição
     * @param string $type - Tipo de retorno (success, error)
     * @param string $message - Mensagem que deve vir no retorno
     * @param string $url - URL de retorno
     * @param integer $id - Id gerado/editado na operação
     * @return array
     */
    public function afterSave($method, $type, $message, $url, $codeStatus, $id = null)
    {
        $dados = [
            'dados' => [
                'acao' => $method == 'PUT' ? 'edit' : 'create',
                'class' => $type,
                'msg' => $message,
                'url' => $url
            ],
            'codeStatus' => $codeStatus
        ];
        if(!empty($id)){
            $dados['dados']['id'] = $id;
        }
        return $dados;
    }

    /**
     * Retorna o primeiro erro encontrado da validação dos parâmetros de entrada
     *
     * @return string|null
     */
    public function getApiErrors()
    {
        if(!$this->container->validator->isValid()) {
            $errors = $this->container->validator->getErrors();
            foreach($errors as $field => $error) {
                foreach($error as $message) {
                    if($field == 'error') {
                        return $message;
                    } else {
                        return $this->getParamName($field) . ': ' . $message;
                    }
                }
            }
        }
        return null;
    }
}
