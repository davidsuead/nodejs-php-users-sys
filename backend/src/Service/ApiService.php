<?php

namespace App\Service;

use App\Swagger\SwaggerDoc;

/**
 * Service para manipular Regras de Negócio de APIs
 * @author David Diniz <diniz.david@gmail.com>
 */
class ApiService 
{

    /**
     * Recipiente para injeção de dependência
     *
     * @var ContainerInterface
     */
    private $container;

    /**
     * Na construção da classe define o recipiente de injeção de dependência
     *
     * @param ContainerInterface $container
     */
    public function __construct($container) 
    {
        $this->container = $container;
    }

    public function validaAuthorization($headers) 
    {
        $chave = $headers['HTTP_AUTHORIZATION'][0]; //chave de autorização de consulta
        $chave = explode(" ", $chave);
        $verificaBearer = strpos("Bearer", trim($chave[0]));
        if ($verificaBearer === false) {
            return [
                "msg" => "A autorização foi negada para esta solicitação",
                'codeStatus' => 401
            ];
        }
        
        if ($chave[1] == $this->container->environment->get('APP_TOKEN_API')) {
            return [
                "msg" => "success",
                'codeStatus' => 200
            ];
        } else {
            return [
                "msg" => "A autorização foi negada para esta solicitação",
                'codeStatus' => 401
            ];
        }
    }

    /**
     * Faz a documentação no padrão Swagger 2.0
     * @return array Estrutura de dados para documentação Swagger 2.0
     */
    public function swaggerDoc() 
    {
        $swaggerDoc = new SwaggerDoc($this->container);
        $swaggerDoc->init();
        return $swaggerDoc->getSwaggerDoc();
    }
}
