<?php

namespace App\Service;

/**
 * Service para manipular Regras de Usuários da Aplicação
 * @author David Diniz <diniz.david@gmail.com>
 */
class AuthService {

    private $container;

    public function __construct($container) 
    {
        $this->container = $container;
    }

    public function getPermissions() 
    {
        /*
         * Rotas
         */
        foreach ($this->container->router->getRoutes() as $route) {
            $routes[] = $route->getPattern();
        }
        
        /*
         * Perfis
         */
        $roles[] = 'guest';
        $allow = [
            "guest" => $this->rotasPublicas(),
        ];
        
        /*
         * Permissões
         */
        return [
            "resources" => $routes,
            "roles" => $roles,
            "assignments" => [
                "allow" => $allow,
                "deny" => []
            ]
        ];
    }

    private function rotasPublicas() 
    {
        return [
            '/',
            '/4xx',
            '/401',
            '/403',
            '/404',
            '/500',
            '/api/swagger',
            '/api/1.0.0/createUser',
            '/api/1.0.0/login',
            '/api/1.0.0/listarUsuarios',
            '/api/1.0.0/editarDados',
            '/api/1.0.0/uploadBiometria',
            '/api/1.0.0/downloadBiometria',
        ];
    }
}
