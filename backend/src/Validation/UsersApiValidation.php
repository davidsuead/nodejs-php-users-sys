<?php

namespace App\Validation;

use Exception;
use Slim\Http\Request;
use Respect\Validation\Validator as V;

/**
 * Classe que define regras de validação dos endpoints do modulo Usuarios
 * @author David Diniz <diniz.david@gmail.com>
 * @since 1.0.0
 */
class UsersApiValidation extends ApiValidation
{
    /**
     * {@inheritDoc}
     */
    public function attributeRules() 
    {
        return [
            ['notEmpty', ['Authorization']],
            ['notEmpty', ['nomeUsuario','numrCpf','emailUsuario','senhaUsuario'],['on' => $this->container->constante['SCENARIO']['CREATE_USER']]],
            ['notEmpty', ['emailUsuario','senhaUsuario'],['on' => $this->container->constante['SCENARIO']['LOGIN']]],
            ['notEmpty', ['idUsuario'],['on' => $this->container->constante['SCENARIO']['EDIT_DATA']]],
            ['notEmpty', ['tokenJwt'],['on' => $this->container->constante['SCENARIO']['UPLOAD_BIOMETRIA']]],
            ['intVal', ['idUsuario'],['on' => $this->container->constante['SCENARIO']['EDIT_DATA']]],
            ['length', ['nomeUsuario','enderecoRua','enderecoBairro','enderecoCidade'], ['max' => 100]],
            ['length', ['numrCpf'], ['max' => 11]],
            ['length', ['emailUsuario','enderecoComplemento'], ['max' => 255]],
            ['length', ['senhaUsuario'], ['max' => 20]],
            ['length', ['enderecoUf'], ['max' => 2]],
            ['length', ['telefonePrincipal','telefoneOutro'], ['max' => 15]],
            // ['length', ['tokenJwt'], ['max' => 500]],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getRules()
    {
        $rules = parent::getRules();
        $rules['dataNascimento'] = [
            'rules' => V::when(V::notEmpty(), V::date('d/m/Y'), v::alwaysValid()),
            'messages' => [
                'date' => $this->messages['date']
            ]
        ];
        $rules['offset'] = [
            'rules' => V::when(V::notEmpty(), V::intVal(), v::alwaysValid()),
            'messages' => [
                'intVal' => $this->messages['intVal']
            ]
        ];
        $rules['limit'] = [
            'rules' => V::when(V::notEmpty(), V::intVal(), v::alwaysValid()),
            'messages' => [
                'intVal' => $this->messages['intVal']
            ]
        ];
        return $rules;
    }

    /**
     * Validar parâmetros de entrada no cenário: CREATE_USER
     *
     * @param Request $request
     * @return void
     */
    public function validateOnCreateUser(Request $request)
    {
        try {
            $params = $request->getParams();
            $this->validateAuthorization($request);
            $this->validateNumCpf($params['numrCpf'], null, true);
            $this->validateEmail($params['emailUsuario'], null, true);

        } catch (Exception $ex) {
            $this->container->validator->addError('error', $ex->getMessage());
        }
    }

    /**
     * Valida os parâmetros de entrada no cenário LOGIN
     *
     * @param Request $request
     * @return void
     */
    public function validateOnLogin(Request $request)
    {
        try {
            $params = $request->getParams();
            $this->validateAuthorization($request);
            $this->validateEmail($params['emailUsuario']);
            $this->validatePassword($params['emailUsuario'], $params['senhaUsuario']);

        } catch (Exception $ex) {
            $this->container->validator->addError('error', $ex->getMessage());
        }
    }

    /**
     * Valida os parâmetros de entrada no cenário LIST_USERS
     *
     * @param Request $request
     * @return void
     */
    public function validateOnListarUsuarios(Request $request)
    {
        try {
            $this->validateAuthorization($request);

        } catch (Exception $ex) {
            $this->container->validator->addError('error', $ex->getMessage());
        }
    }

    /**
     * Valida os parâmetros de entrada do cenário EDIT_DATA
     *
     * @param Request $request
     * @return void
     */
    public function validateOnEditarDados(Request $request)
    {
        try {
            $params = $request->getParams();
            $this->validateAuthorization($request);
            $this->validateIdUsuario($params['idUsuario']);
            if (!empty($params['numrCpf'])) {
                $this->validateNumCpf($params['numrCpf'], $params['idUsuario']);
            }
            if (!empty($params['emailUsuario'])) {
                $this->validateEmail($params['emailUsuario'], $params['idUsuario']);
            }

        } catch (Exception $ex) {
            $this->container->validator->addError('error', $ex->getMessage());
        }
    }

    /**
     * Valida os parâmetros de entrada no cenários UPLOAD_BIOMETRIA
     *
     * @param Request $request
     * @return void
     */
    public function validateOnUploadBiometria(Request $request)
    {
        try {
            $params = $request->getParams();
            $this->validateAuthorization($request);

            $decoded = $this->container->jwtService->decode($params['tokenJwt']);
            if (empty($decoded['accessToken'])) {
                throw new Exception('Não foi possível identificar o accessToken do usuário');
            }
            if (empty($decoded['nomeArquivo'])) {
                throw new Exception('Não foi possível identificar o nome do arquivo da biometria');
            }
            if (substr($decoded['nomeArquivo'], -4) != '.wsq') {
                throw new Exception('A extensão do arquivo da biometria está inválida');
            }
            if (empty($decoded['conteudoArquivo'])) {
                throw new Exception('Não foi possível identificar o conteúdo do arquivo da biometria');
            }
            $this->validateAccessToken($decoded['accessToken']);

        } catch (Exception $ex) {
            $this->container->validator->addError('error', $ex->getMessage());
        }
    }

    /**
     * Validação personalizada
     *
     * @param Request $request
     * @return void
     */
    public function customValidate(Request $request)
    {
        if(!empty($this->scenario) && $this->container->validator->isValid()) {
            switch($this->scenario) {
                case $this->container->constante['SCENARIO']['CREATE_USER']:
                    $this->validateOnCreateUser($request);
                    break;
                case $this->container->constante['SCENARIO']['LOGIN']:
                    $this->validateOnLogin($request);
                    break;
                case $this->container->constante['SCENARIO']['LIST_USERS']:
                    $this->validateOnListarUsuarios($request);
                    break;
                case $this->container->constante['SCENARIO']['EDIT_DATA']:
                    $this->validateOnEditarDados($request);
                    break;
                case $this->container->constante['SCENARIO']['UPLOAD_BIOMETRIA']:
                    $this->validateOnUploadBiometria($request);
                    break;
            }
        }
    }
}