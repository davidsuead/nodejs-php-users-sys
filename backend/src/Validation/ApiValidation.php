<?php

namespace App\Validation;

use Exception;
use Slim\Http\Request;
use App\Entity\Usuario;
use App\Entity\UsuarioToken;

/**
 * Classe de validação para parâmetros de requisição em API Rest
 * @author David Diniz <diniz.david@gmail.com>
 * @since 1.0.0
 */
class ApiValidation extends Validation
{
    /**
     * Verifica se o token acesso é válido
     *
     * @param Request $request
     * @return void
     * @throws Exception
     */
    protected function validateAuthorization(Request $request)
    {
        $validaChavePortal = $this->container->apiService->validaAuthorization($request->getHeaders());
        
        if ($validaChavePortal['codeStatus'] != 200) {
            $this->code =  $validaChavePortal['codeStatus'];
            throw new Exception($validaChavePortal['msg']);                
        }
    }

    /**
     * Valida o parâmetro numrCpf
     *
     * @param string $numrCpf
     * @param int|null $idUsuario
     * @param boolean $isNewUser
     * @return Usuario|null
     * @throws Exception
     */
    protected function validateNumCpf($numrCpf, $idUsuario = null, $isNewUser = false)
    {
        if (!$this->container->validaService->isCpf($numrCpf)) {
            throw new Exception('CPF inválido');
        }

        /** @var Usuario $usuario */
        $usuario = $this->container->usuarioService->getRepository()->findOneBy([
            'numrCpf' => $this->container->utilService->limpaCpfCpnj($numrCpf)
        ]);

        if ($isNewUser && !empty($usuario)) {
            throw new Exception('CPF já existe no banco de dados');
        }

        if (!$isNewUser) {
            if (empty($usuario)) {
                throw new Exception('CPF não foi encontrado no banco de dados');
            }
    
            if (!empty($idUsuario) && $usuario->getIdUsuario() != $idUsuario) {
                throw new Exception('CPF já pertence a outro usuário');
            }
        }

        return $usuario;
    }

    /**
     * Valida o parâmetro emailUsuario
     *
     * @param string $emailUsuario
     * @param int|null $idUsuario
     * @param boolean $isNewUser
     * @return Usuario|null
     * @throws Exception
     */
    protected function validateEmail($emailUsuario, $idUsuario = null, $isNewUser = false)
    {
        /** @var Usuario $usuario */
        $usuario = $this->container->usuarioService->getRepository()->findOneBy([
            'descEmail' => $emailUsuario
        ]);

        if ($isNewUser && !empty($usuario)) {
            throw new Exception('E-mail já existe no banco de dados');
        }

        if (!$isNewUser) {
            if (empty($usuario)) {
                throw new Exception('E-mail não foi encontrado no banco de dados');
            }
    
            if (!empty($idUsuario) && $usuario->getIdUsuario() != $idUsuario) {
                throw new Exception('E-mail já pertence a outro usuário');
            }
        }

        return $usuario;
    }

    /**
     * Valida o parâmetro accessToken
     *
     * @param string $accessToken
     * @return UsuarioToken
     * @throws Exception
     */
    protected function validateAccessToken($accessToken)
    {
        /** @var UsuarioToken $token */
        $token = $this->container->usuarioTokenService->getRepository()->findOneBy([
            'accessToken' => $accessToken
        ]);
        
        if(empty($token)) {
            throw new Exception('O Token do usuário não foi encontrado no banco de dados');
        }

        // if (time() > $token->getTempoExpiracao()) {
        //     throw new Exception('ACCESSTOKEN Expirado');
        // }

        return $token;
    }

    /**
     * Valida o parâmetro idUsuario
     *
     * @param int $idUsuario
     * @return Usuario
     * @throws Exception
     */
    protected function validateIdUsuario($idUsuario)
    {
        /** @var Usuario $usuario */
        $usuario = $this->container->usuarioService->buscar($idUsuario);
        
        if(empty($usuario)) {
            throw new Exception('ID do usuário não foi encontrado no banco de dados');
        }

        return $usuario;
    }

    /**
     * Valida a senha do usuário
     *
     * @param string $emailUsuario
     * @param string $password
     * @return void
     * @throws Exception
     */
    protected function validatePassword($emailUsuario, $password)
    {
        if (!$this->container->usuarioService->verifyPassword($emailUsuario, $password)) {
            throw new Exception('Senha inválida.');
        }
    }
}