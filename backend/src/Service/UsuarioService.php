<?php

namespace App\Service;

use Exception;
use DateTime;
use Slim\Http\Request;
use App\Entity\Usuario;

/**
 * Classe service que manipula os dados da entidade Usuario
 * @author David Diniz
 * @since 1.0.0
 */
class UsuarioService extends ModelService
{
    /**
     * {@inheritDoc}
     */
    protected static $entityClass = Usuario::class;

    /**
     *
     * @param Request $request
     * @return static
     */
    public function getPagination(Request $request) 
    {
        return json_encode([$this->getRepository()->getBy($this->container, $request->getParams())]);
    }

    /**
     *
     * @param Request $request
     * @return array
     */
    public function listar(Request $request)
    {
        return $this->getRepository()->getBy($this->container, $request->getParams());
    }
    
    /**
     * Salva os dados pessoais do usuário
     *
     * @param array $params
     * @return Usuario
     * @throws Exception
     */
    public function save(array $params)
    {
        try {
            if (!empty($params['idUsuario'])) {
                /** @var Usuario $user */
                $user = $this->buscar($params['idUsuario']);
            }

            if (empty($user)) {
                $user = new Usuario();
                $user->setDataRegistro(new DateTime());
            }

            $fields = [
                'descNome' => 'setDescNome',
                'numrCpf' => 'setNumrCpf',
                'descEmail' => 'setDescEmail',
                'descSenha' => 'setDescSenha',
                'dataNascimento' => 'setDataNascimento',
            ];            
            foreach($fields as $field => $func) {
                if (!empty($params[$field])) {
                    $user->$func($params[$field]);
                }
            }

            return $this->getRepository()->save($user);

        } catch (Exception $ex) {
            throw new Exception('Não foi possível salvar os dados pessoais do usuários. Detalhes: ' . $ex->getMessage());
        }
    }

    /**
     * Verifica se a senha é valida
     *
     * @param string $descEmail
     * @param string $password
     * @return boolean
     */
    public function verifyPassword($descEmail, $password)
    {
        /** @var Usuario $user */
        $user = $this->getRepository()->findOneBy([
            'descEmail' => $descEmail
        ]);

        return !empty($user) && password_verify($password, $user->getDescSenha());
    }
}