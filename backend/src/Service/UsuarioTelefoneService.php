<?php

namespace App\Service;

use Exception;
use App\Entity\UsuarioTelefone;

/**
 * Classe service que manipula os dados da entidade UsuarioTelefone
 * @author David Diniz
 * @since 1.0.0
 */
class UsuarioTelefoneService extends ModelService
{
    /**
     * {@inheritDoc}
     */
    protected static $entityClass = UsuarioTelefone::class;

    /**
     * Salva o telefone do usuário
     *
     * @param int $idUsuario
     * @param string $indiPrincipal
     * @param string $descTelefone
     * @return UsuarioTelefone
     * @throws Exception
     */
    public function save($idUsuario, $indiPrincipal, $descTelefone)
    {
        try {
            /** @var UsuarioTelefone $usuarioTelefone */
            $usuarioTelefone = $this->getRepository()->findOneBy([
                'usuario' => $idUsuario,
                'indiPrincipal' => $indiPrincipal
            ]);

            if (empty($usuarioTelefone)) {
                $usuarioTelefone = new UsuarioTelefone();
                $usuarioTelefone->setIndiPrincipal($indiPrincipal);
                $usuarioTelefone->setUsuario($this->container->usuarioService->getReference($idUsuario));
            }

            $usuarioTelefone->setDescTelefone($descTelefone);

            return $this->getRepository()->save($usuarioTelefone);
        } catch (Exception $ex) {
            throw new Exception('Não foi possível salvar o telefone do usuário. Detalhes: ' . $ex->getMessage());
        }
    }
}