<?php

namespace App\Controller;

use Slim\Http\Request;
use Slim\Http\Response;
use Awurth\Slim\Helper\Controller\Controller;
use phpseclib\Crypt\RSA;

class ApiController extends Controller 
{
    /**
     * Documentação Swagger dos serviços
     * @param class $request Classe Request Slim
     * @param class $response Classe Response Slim
     * @return view Página
     */
    public function swagger(Request $request, Response $response) 
    {
        return $response->withJson($this->container->apiService->swaggerDoc(), 200);
    }
}
