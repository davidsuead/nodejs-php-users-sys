<?php

namespace App\Controller;

use Slim\Http\Request;
use Slim\Http\Response;
use Awurth\Slim\Helper\Controller\Controller;

class AppController extends Controller 
{
    /**
     * Página Principal do Portal
     * @param class $request Classe Request Slim
     * @param class $response Classe Response Slim
     * @return view Página
     */
    public function index(Request $request, Response $response) 
    {
        return $this->render($response, 'app/index.twig');
    }

    /**
     * Página Erro 401
     * @param class $request Classe Request Slim
     * @param class $response Classe Response Slim
     * @return view Página
     */
    public function error401(Request $request, Response $response) 
    {
        return $this->render($response, 'app/error/401.twig');
    }

    /**
     * Página Erro 403
     * @param class $request Classe Request Slim
     * @param class $response Classe Response Slim
     * @return view Página
     */
    public function error403(Request $request, Response $response) 
    {
        return $this->render($response, 'app/error/403.twig');
    }

    /**
     * Página Erro 404
     * @param class $request Classe Request Slim
     * @param class $response Classe Response Slim
     * @return view Página
     */
    public function error404(Request $request, Response $response) 
    {
        return $this->render($response, 'app/error/404.twig');
    }

    /**
     * Página Erro 4xx
     * @param class $request Classe Request Slim
     * @param class $response Classe Response Slim
     * @return view Página
     */
    public function error4xx(Request $request, Response $response) 
    {
        return $this->render($response, 'app/error/4xx.twig');
    }

    /**
     * Página Erro 500
     * @param class $request Classe Request Slim
     * @param class $response Classe Response Slim
     * @return view Página
     */
    public function error500(Request $request, Response $response) 
    {
        return $this->render($response, 'app/error/500.twig');
    }
}
