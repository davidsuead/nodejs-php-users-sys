<?php

namespace App\Service;

use Exception;
use App\Entity\UsuarioToken;
use DateTime;

/**
 * Classe service que manipula os dados da entidade UsuarioToken
 * @author David Diniz
 * @since 1.0.0
 */
class UsuarioTokenService extends ModelService
{
    /**
     * {@inheritDoc}
     */
    protected static $entityClass = UsuarioToken::class;

    /**
     * Cria um token de usuário
     *
     * @param int $idUsuario
     * @return UsuarioToken
     * @throws Exception
     */
    public function create($idUsuario)
    {
        try {
            $time = time();

            $usuarioToken = new UsuarioToken();
            $usuarioToken->setAccessToken($this->container->utilService->getRandomString());
            $usuarioToken->setTempoExpiracao($time + 3600);
            $usuarioToken->setTempoGeracao($time);
            $usuarioToken->setDataRegistro(new DateTime());
            $usuarioToken->setValido('S');
            $usuarioToken->setUsuario($this->container->usuarioService->getReference($idUsuario));

            return $this->getRepository()->save($usuarioToken);

        } catch (Exception $ex) {
            throw new Exception('Não foi possível gerar o token de usuario. Detalhes: ' . $ex->getMessage());
        }
    }

    /**
     * Desativa o token do usuário
     *
     * @param string $accessToken
     * @return UsuarioToken
     * @throws Exception
     */
    public function disableToken($accessToken)
    {
        try {
            /** @var UsuarioToken $usuarioToken */
            $usuarioToken = $this->getRepository()->findOneBy(['accessToken' => $accessToken]);
            if (!empty($usuarioToken)) {
                $usuarioToken->setValido('N');
                return $this->getRepository()->save($usuarioToken);
            } else  {
                throw new Exception('Token de usuário não encontrado');
            }
        } catch (Exception $ex) {
            throw new Exception('Não foi possível desativar o token do usuário. Detalhes: ' . $ex->getMessage());
        }
    }
}