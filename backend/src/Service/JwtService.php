<?php

namespace App\Service;

use Exception;
use Firebase\JWT\JWT;
use phpseclib\Crypt\RSA;

/**
 * Service para manipular JWTs
 * @author David Diniz <diniz.david@gmail.com>
 */
class JwtService 
{
    private $container;

    public function __construct($container) 
    {
        $this->container = $container;
    }

    public function generateKeys() 
    {
        $rsa = new RSA();
        return $rsa->createKey(2048);
    }

    /**
     * Decodifica um token JWT
     *
     * @param string $token
     * @return array
     */
    public function decode($token)
    {
        try {
            $decoded = JWT::decode($token, $this->container->environment['APP_SECRET_JWT'], array('HS256'));
            return (array) $decoded;
        } catch (Exception $ex) {
            throw new Exception('Não foi possível decodificar o token JWT. Detalhes: ' . $ex->getMessage());
        }
    }

    /**
     * Codifica o payload em um JWT
     *
     * @param array $payload
     * @return object|array
     */
    public function encode(array $payload)
    {
        try {
            return JWT::encode($payload, $this->container->environment['APP_SECRET_JWT']);
        } catch (Exception $ex) {
            throw new Exception('Não foi possível decodificar o token JWT. Detalhes: ' . $ex->getMessage());
        }
    }
}
