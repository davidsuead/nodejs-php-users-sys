<?php

namespace App\Controller;

use Slim\Http\Request;
use Slim\Http\Response;
use Awurth\Slim\Helper\Controller\Controller;

/**
 * Controller de APIs de Integração com Módulo USUARIOS
 * @author David Diniz <diniz.david@gmail.com>
 * @since 1.0.0
 */
class ApiUsuarioController extends Controller
{
    /**
     * Cria um novo usuário
     *
     * @param Request $request
     * @param Response $response
     * @return static Retorna um json
     */
    public function createUser(Request $request, Response $response)
    {
        $result = $this->container->usersApiService->createUser($request);
        return $response->withJson($result['data'], $result['statusCode']);
    }

    /**
     * Autentica usuário
     *
     * @param Request $request
     * @param Response $response
     * @return static Retorna um json
     */
    public function login(Request $request, Response $response)
    {
        $result = $this->container->usersApiService->login($request);
        return $response->withJson($result['data'], $result['statusCode']);
    }

    /**
     * Retorna uma lista de usuários
     *
     * @param Request $request
     * @param Response $response
     * @return void
     */
    public function listarUsuarios(Request $request, Response $response)
    {
        $result = $this->container->usersApiService->listarUsuarios($request);
        return $response->withJson($result['data'], $result['statusCode']);
    }

    /**
     * Edita os dados de um usuário
     *
     * @param Request $request
     * @param Response $response
     * @return void
     */
    public function editarDados(Request $request, Response $response)
    {
        $result = $this->container->usersApiService->editarDados($request);
        return $response->withJson($result['data'], $result['statusCode']);
    }

    /**
     * Realiza o upload da biometria do usuário
     *
     * @param Request $request
     * @param Response $response
     * @return void
     */
    public function uploadBiometria(Request $request, Response $response)
    {
        $result = $this->container->usersApiService->uploadBiometria($request);
        return $response->withJson($result['data'], $result['statusCode']);
    }

    /**
     * Realiza o Download da Biometria
     *
     * @param Request $request
     * @param Response $response
     * @return void
     */
    public function downloadBiometria(Request $request, Response $response)
    {
        // $result = $this->container->usersApiService->downloadBiometria($request);
        $result = ['data' => 'ok', 'statusCode'];
        return $response->withJson($result['data'], $result['statusCode']);
    }
}