<?php

namespace App\Service;

use DateTime;
use Exception;
use Slim\Http\Request;
use App\Entity\Usuario;
use App\Entity\UsuarioToken;
use App\Entity\UsuarioBiometria;
use App\Validation\UsersApiValidation;

/**
 * Classe service para os endpoints do modulo USUARIOS
 * @author David Diniz
 * @since 1.0.0
 */
class UsersApiService extends ModelService
{
    /**
     * {@inheritDoc}
     */
    protected static $entityClass = Usuario::class;

    /**
     * Prepara os parâmetros de entrada para serem manipulados
     *
     * @param array $params
     * @return array $newParams
     */
    public function prepareParams(array $params)
    {
        $fields = [
            'idUsuario' => 'idUsuario',
            'nomeUsuario' => 'descNome',
            'numrCpf' => 'numrCpf',
            'emailUsuario' => 'descEmail',
            'senhaUsuario' => 'descSenha',
            'dataNascimento' => 'dataNascimento',
            'enderecoRua' => 'descRua',
            'enderecoNumero' => 'numrEndereco',
            'enderecoComplemento' => 'descComplemento',
            'enderecoBairro' => 'descBairro',
            'enderecoCidade' => 'descCidade',
            'enderecoUf' => 'descUf',
        ];
        $newParams = [];
        foreach($fields as $from => $to) {
            if (!empty($params[$from])) {
                $newParams[$to] = $params[$from];
            }
        }
        if (!empty($newParams['dataNascimento'])) {
            $newParams['dataNascimento'] = $this->container->utilService->getConverteDataBanco($newParams['dataNascimento']);
            $newParams['dataNascimento'] = new DateTime($newParams['dataNascimento']);
        }
        return $newParams;
    }

    /**
     * Valida parâmetros de entrada conforme regras definidas em UsersApiValidation
     *
     * @param Request $request
     * @param string $scenario
     * @return int $statusCode
     */
    public function validate(Request $request, $scenario)
    {
        $params = $request->getParams();
        if(isset($params['Authorization'])) {
            unset($params['Authorization']);
        }
        $params['Authorization'] = $request->getHeader('HTTP_AUTHORIZATION')[0] ?? null;
        
        $validation = new UsersApiValidation($this->container);
        $validation->scenario = $scenario;
        $this->container->validator->array($params, $validation->getRules());
        $validation->customValidate($request);

        return $validation->getCode();
    }

    /**
     * Salva os telefones dos usuários
     *
     * @param Usuario $user
     * @param array $params
     * @return void
     */
    public function saveTelefones(Usuario $user, array $params) 
    {
        $fieldsFrom = ['telefonePrincipal' => 'S', 'telefoneOutro' => 'N'];
        foreach($fieldsFrom as $field => $indiPrincipal) {
            if (!empty($params[$field])) {
                $this->container->usuarioTelefoneService->save($user->getIdUsuario(), $indiPrincipal, $params[$field]);
            }
        }
    }

    /**
     * Salva os dados de endereço do usuário
     *
     * @param Usuario $user
     * @param array $params
     * @return void
     */
    public function saveEndereco(Usuario $user, array $params)
    {
        $requiredFields = ['descRua','descBairro','descCidade','descUf'];
        $toSave = false;
        $hasEmpty = false;
        foreach ($requiredFields as $field) {
            if (!empty($params[$field])) {
                $toSave = true;
            } else {
                $hasEmpty = true;
            }
        }

        if ($toSave && !$hasEmpty) {
            $params['idUsuario'] = $user->getIdUsuario();
            $this->container->usuarioEnderecoService->save($params);
        } 
        else if($toSave && $hasEmpty) {
            $msg  = 'Caso deseja cadastrar um endereço, os seguintes campos são necessários: ';
            $msg .= 'Rua, Bairro, Cidade, UF';
            throw new Exception($msg);
        }
    }

    /**
     * Registra um novo usuário
     *
     * @param Request $request
     * @return array[data,statusCode]
     */
    public function createUser(Request $request)
    {
        $this->container->em->beginTransaction();
        try {
            $statusCode = $this->validate($request, $this->container->constante['SCENARIO']['CREATE_USER']);
            if ($this->container->validator->isValid()) {
                $params = $request->getParams();                
                $newParams = $this->prepareParams($params);
                

                /** @var Usuario $user */
                $user = $this->container->usuarioService->save($newParams);
                $this->saveEndereco($user, $newParams);
                $this->saveTelefones($user, $params);

                $data = [
                    'idUsuario' => $user->getIdUsuario(),
                    'nomeUsuario' => $user->getDescNome(),
                ];

                $this->container->em->commit();
            } else {
                $data['message'] = $this->getApiErrors();
            }
        } catch (Exception $ex) {
            $this->container->em->rollback();
            $this->container->monolog->error('Erro durante a requisição a rota "createUSer" ' . get_class($ex), [
                'exception' => $ex
            ]);
            $statusCode = $this->container->constante['HTTP_STATUS_CODE']['INTERNAL_ERROR'];
            $data['message'] = $ex->getMessage();
        }

        return ['data' => $data, 'statusCode' => $statusCode];
    }

    /**
     * Autentica usuário (email + senha)
     *
     * @param Request $request
     * @return array[data,statusCode]
     */
    public function login(Request $request)
    {
        $this->container->em->beginTransaction();
        try {
            $statusCode = $this->validate($request, $this->container->constante['SCENARIO']['LOGIN']);
            if ($this->container->validator->isValid()) {
                $params = $request->getParams();                

                /** @var Usuario $user */
                $user = $this->container->usuarioService->getRepository()->findOneBy([
                    'descEmail' => $params['emailUsuario']
                ]);
                /** @var UsuarioToken $token */
                $token = $this->container->usuarioTokenService->create($user->getIdUsuario());
                $data = [
                    'accessToken' => $token->getAccessToken()
                ];

                $this->container->em->commit();
            } else {
                $data['message'] = $this->getApiErrors();
            }
        } catch (Exception $ex) {
            $this->container->em->rollback();
            $this->container->monolog->error('Erro durante a requisição a rota "login" ' . get_class($ex), [
                'exception' => $ex
            ]);
            $statusCode = $this->container->constante['HTTP_STATUS_CODE']['INTERNAL_ERROR'];
            $data['message'] = $ex->getMessage();
        }

        return ['data' => $data, 'statusCode' => $statusCode];
    }

    /**
     * Retorna uma lista de usuários
     *
     * @param Request $request
     * @return array[data,statusCode]
     */
    public function listarUsuarios(Request $request)
    {
        try {
            $statusCode = $this->validate($request, $this->container->constante['SCENARIO']['LIST_USERS']);
            if ($this->container->validator->isValid()) {
                $params = $request->getParams();
                $result = $this->container->usuarioService->listar($request);
                $data = [
                    'qtdeTotal' => $result['totalRows'],
                    'offset' => $params['offset'] ?? null,
                    'limit' => $params['limit'] ?? null,
                    'usuarios' => $result['rows'],
                ];
            } else {
                $data['message'] = $this->getApiErrors();
            }
        } catch (Exception $ex) {
            $this->container->monolog->error('Erro durante a requisição a rota "listarUsuarios" ' . get_class($ex), [
                'exception' => $ex
            ]);
            $statusCode = $this->container->constante['HTTP_STATUS_CODE']['INTERNAL_ERROR'];
            $data['message'] = $ex->getMessage();
        }

        return ['data' => $data, 'statusCode' => $statusCode];
    }

    /**
     * Edita os dados do usuário
     *
     * @param Request $request
     * @return void
     */
    public function editarDados(Request $request)
    {
        $this->container->em->beginTransaction();
        try {
            $statusCode = $this->validate($request, $this->container->constante['SCENARIO']['EDIT_DATA']);
            if ($this->container->validator->isValid()) {
                $params = $request->getParams();                
                $newParams = $this->prepareParams($params);

                /** @var Usuario $user */
                $user = $this->container->usuarioService->save($newParams);
                $this->saveEndereco($user, $newParams);
                $this->saveTelefones($user, $params);

                $result = $this->container->usuarioService->listar($request);
                $data = $result['rows'];

                $this->container->em->commit();
            } else {
                $data['message'] = $this->getApiErrors();
            }
        } catch (Exception $ex) {
            $this->container->em->rollback();
            $this->container->monolog->error('Erro durante a requisição a rota "editarDados" ' . get_class($ex), [
                'exception' => $ex
            ]);
            $statusCode = $this->container->constante['HTTP_STATUS_CODE']['INTERNAL_ERROR'];
            $data['message'] = $ex->getMessage();
        }

        return ['data' => $data, 'statusCode' => $statusCode];
    }

    /**
     * Realiza o upload da biometria
     *
     * @param Request $request
     * @return array[data,statusCode]
     */
    public function uploadBiometria(Request $request)
    {
        $this->container->em->beginTransaction();
        try {
            $statusCode = $this->validate($request, $this->container->constante['SCENARIO']['UPLOAD_BIOMETRIA']);
            if ($this->container->validator->isValid()) {
                $params = $request->getParams();                
                $newParams = $this->container->jwtService->decode($params['tokenJwt']);

                /** @var UsuarioToken $token */
                $token = $this->container->usuarioTokenService->getRepository()->findOneBy([
                    'accessToken' => $newParams['accessToken']
                ]);
                
                /** @var Usuario $user */
                $user = $token->getUsuario();
                
                /** @var UsuarioBiometria $biometria */
                $biometria = $this->container->usuarioBiometriaService->create(
                    $user->getIdUsuario(), $newParams['nomeArquivo']
                );
                $bytes = file_put_contents($biometria->getDescCaminhoArquivo(), base64_decode($newParams['conteudoArquivo']));

                if ($bytes === false) {
                    throw new Exception('Não foi possível salvar a biometria no servidor');
                }

                $data = ['message' => 'Upload feito com sucesso!'];
                $this->container->em->commit();
            } else {
                $data['message'] = $this->getApiErrors();
            }
        } catch (Exception $ex) {
            $this->container->em->rollback();
            $this->container->monolog->error('Erro durante a requisição a rota "uploadBiometria" ' . get_class($ex), [
                'exception' => $ex
            ]);
            $statusCode = $this->container->constante['HTTP_STATUS_CODE']['INTERNAL_ERROR'];
            $data['message'] = $ex->getMessage();
        }

        return ['data' => $data, 'statusCode' => $statusCode];
    }
    
}