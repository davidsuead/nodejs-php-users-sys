<?php

namespace App\Service;

use Exception;
use App\Entity\UsuarioEndereco;

/**
 * Classe service que manipula os dados da entidade UsuarioEndereco
 * @author David Diniz
 * @since 1.0.0
 */
class UsuarioEnderecoService extends ModelService
{
    /**
     * {@inheritDoc}
     */
    protected static $entityClass = UsuarioEndereco::class;

    /**
     * Salvar o endereço do usuário
     *
     * @param array $params
     * @return UsuarioEndereco
     * @throws Exception
     */
    public function save(array $params)
    {
        try {
            /** @var UsuarioEndereco $usuarioEndereco */
            $usuarioEndereco = $this->getRepository()->findOneBy([
                'usuario' => $params['idUsuario']
            ]);

            if(empty($usuarioEndereco)) {
                $usuarioEndereco = new UsuarioEndereco();
                $usuarioEndereco->setUsuario($this->container->usuarioService->getReference($params['idUsuario']));
            }

            $fields = [
                'descRua' => 'setDescRua',
                'numrEndereco' => 'setNumrEndereco',
                'descComplemento' => 'setDescComplemento',
                'descBairro' => 'setDescBairro',
                'descCidade' => 'setDescCidade',
                'descUf' => 'setDescUf',
            ];            
            foreach($fields as $field => $func) {
                if (!empty($params[$field])) {
                    $usuarioEndereco->$func($params[$field]);
                }
            }

            return $this->getRepository()->save($usuarioEndereco);
        } catch (Exception $ex) {
            throw new Exception('Não foi possível salvar o endereço do usuário. Detalhes: ' . $ex->getMessage());
        }
    }
}