<?php

namespace App\Service;

use Exception;
use App\Entity\UsuarioBiometria;
use DateTime;

/**
 * Classe service que manipula os dados da entidade UsuarioBiometria
 * @author David Diniz
 * @since 1.0.0
 */
class UsuarioBiometriaService extends ModelService
{
    /**
     * {@inheritDoc}
     */
    protected static $entityClass = UsuarioBiometria::class;

    /**
     * Obtém o caminho completo do arquivo no servidor
     *
     * @param string $nomeArquivo
     * @return string
     */
    private function getPath($nomeArquivo)
    {
        $path = str_replace(['\src\Service', '/src/Service'], '', __DIR__);
        return $path . DIRECTORY_SEPARATOR . "biometrias" . DIRECTORY_SEPARATOR . $nomeArquivo;
    }

    /**
     * Registra os dados da biometria do usuário no banco de dados
     *
     * @param int $idUsuario
     * @param string $nomeOriginalArquivo
     * @return UsuarioBiometria
     */
    public function create($idUsuario, $nomeOriginalArquivo)
    {
        try {
            $nomeArquivo = time() . '-' . substr($nomeOriginalArquivo,0,-4) . '-biometria-' . $idUsuario . '.wsq';

            $usuarioBiometria = new UsuarioBiometria();
            $usuarioBiometria->setDescNomeOriginal($nomeOriginalArquivo);
            $usuarioBiometria->setDescNome($nomeArquivo);
            $usuarioBiometria->setDescCaminhoArquivo($this->getPath($nomeArquivo));
            $usuarioBiometria->setDataUpload(new DateTime());
            $usuarioBiometria->setUsuario($this->container->usuarioService->getReference($idUsuario));
            $usuarioBiometria->setNumrIpUpload($this->container->utilService->getIp());

            return $this->getRepository()->save($usuarioBiometria);

        } catch (Exception $ex) {
            throw new Exception('Não foi possível salvar os dados da biometria no banco de dados. Detalhes: ' . $ex->getMessage());
        }
    }
}