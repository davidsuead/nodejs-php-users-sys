<?php

namespace App\Swagger;

/**
 * Classe que define a documentação da tag USUARIOS
 * @author David Diniz <diniz.david@gmail.com>
 * @since 1.0.0
 */
class UsuarioTag extends AbstractTag
{
    /**
     * Define a documentação do endpoint postCreateUser
     *
     * @return void
     */
    private function setCreateUser()
    {
        $pathName = "/createUser";

        $path = new \stdClass();
        $path->post = new \stdClass();
        $path->post->tags = ["{$this->tag['name']}"];
        $path->post->summary = "Criar um novo usuário";
        $path->post->description = "Endpoint que registra um novo usuário";
        $path->post->operationId = "postCreateUser";
        $path->post->produces = ["application/json"];
        $this->swagger->paths[$pathName] = $path;

        $this->setParameters($pathName, [
            [
                'name' => 'nomeUsuario',
                'description' => 'Nome do usuário',
            ],
            [
                'name' => 'numrCpf',
                'description' => 'Número de CPF do usuário',
            ],
            [
                'name' => 'emailUsuario',
                'description' => 'E-mail do usuário',
            ],
            [
                'name' => 'senhaUsuario',
                'description' => 'Senha do usuário',
            ],
            [
                'name' => 'dataNascimento',
                'description' => 'Data de nascimento do usuário. Informe no formato DD/MM/AAAA. Exemplo: 18/04/1984',
                'required' => false
            ],
            [
                'name' => 'enderecoRua',
                'description' => 'Rua/Logradouro do endereço',
                'required' => false
            ],
            [
                'name' => 'enderecoNumero',
                'description' => 'Número do endereço',
                'required' => false
            ],
            [
                'name' => 'enderecoComplemento',
                'description' => 'Complemento do endereço',
                'required' => false
            ],
            [
                'name' => 'enderecoBairro',
                'description' => 'Bairro do endereço',
                'required' => false
            ],
            [
                'name' => 'enderecoCidade',
                'description' => 'Cidade do endereço',
                'required' => false
            ],
            [
                'name' => 'enderecoUf',
                'description' => 'UF do endereço',
                'required' => false
            ],
            [
                'name' => 'telefonePrincipal',
                'description' => 'Telefone Principal',
                'required' => false
            ],
            [
                'name' => 'telefoneOutro',
                'description' => 'Outro telefone',
                'required' => false
            ],
        ], true, 'post');

        // HTTP Response (200) Success
        $this->setDefaultResponse($pathName, 200,'retUser200', [
            'idUsuario' => 'string',
            'nomeUsuario' => 'string',
        ], null, 'post');

        /**
         * HTTP Response (400) Bad Request
         * HTTP Response (401) Unauthorized
         * HTTP Response (500) Internal Server Error
         */
        $this->setManyDefaultResponse($pathName, [400,401,500], [], [], [], 'post');
    }

    /**
     * Define a documentação do endpoint postLogin
     *
     * @return void
     */
    private function setLogin()
    {
        $pathName = "/login";

        $path = new \stdClass();
        $path->post = new \stdClass();
        $path->post->tags = ["{$this->tag['name']}"];
        $path->post->summary = "Autentica o usuário";
        $path->post->description = "Endpoint que autentica o usuário";
        $path->post->operationId = "postLogin";
        $path->post->produces = ["application/json"];
        $this->swagger->paths[$pathName] = $path;

        $this->setParameters($pathName, [
            [
                'name' => 'emailUsuario',
                'description' => 'E-mail do usuário',
            ],
            [
                'name' => 'senhaUsuario',
                'description' => 'Senha do usuário',
            ],
        ], true, 'post');

        // HTTP Response (200) Success
        $this->setDefaultResponse($pathName, 200, 'retLogin200', [
            'accessToken' => 'string',
        ], null, 'post');

        /**
         * HTTP Response (400) Bad Request
         * HTTP Response (401) Unauthorized
         * HTTP Response (500) Internal Server Error
         */
        $this->setManyDefaultResponse($pathName, [400,401,500], [], [], [], 'post');
    }

    /**
     * Define documentação do endpoint getListarUsuarios
     *
     * @return void
     */
    private function setListarUsuarios()
    {
        $pathName = "/listarUsuarios";

        $path = new \stdClass();
        $path->get = new \stdClass();
        $path->get->tags = ["{$this->tag['name']}"];
        $path->get->summary = "Retorna uma lista de usuários cadastrado";
        $path->get->description = "Endpoint que retorna uma lista de usuários cadastrados no sistema";
        $path->get->operationId = "getListarUsuarios";
        $path->get->produces = ["application/json"];
        $this->swagger->paths[$pathName] = $path;

        $this->setParameters($pathName, [
            [
                'name' => 'nomeUsuario',
                'description' => 'Nome do usuário',
                'required' => false
            ],
            [
                'name' => 'numrCpf',
                'description' => 'Número CPF do usuário',
                'required' => false,
            ],
            [
                'name' => 'emailUsuario',
                'description' => 'E-mail do usuário',
                'required' => false,
            ],
            [
                'name' => 'enderecoCidade',
                'description' => 'Cidade do endereço do usuário',
                'required' => false,
            ],
            [
                'name' => 'offset',
                'description' => 'Define a primeira posição da pesquisa',
                'type' => 'integer',
                'required' => false,
            ],
            [
                'name' => 'limit',
                'description' => 'Definie o limite máximo de registros da pesquisa',
                'type' => 'integer',
                'required' => false,
            ],
        ]);

        // HTTP Response (200) Success
        $arrResponse = [
            'description' => $this->getResponseDesc(200),
            'schema' => [
                '$ref' => "#/definitions/retListaUsuarios200"
            ],
        ];
        $definitions = [
            'retListaUsuarios200' => [
                'type' => 'object',
                'properties' => [
                    'qtdeTotal' => ['type' => 'integer'],
                    'offset' => ['type' => 'integer'],
                    'limit' => ['type' => 'integer'],
                    'usuarios' => [
                        'type' => 'array',
                        'items' => [
                            '$ref' => '#/definitions/retListaUsuarios'
                        ]
                    ]
                ]
            ],
            'retListaUsuarios' => [
                'type' => 'object',
                'properties' => [
                    'idUsuario' => ['type' => 'integer'],
                    'nomeUsuario' => ['type' => 'string'],
                    'numrCpf' => ['type' => 'string'],
                    'emailUsuario' => ['type' => 'string'],
                    'dataNascimento' => ['type' => 'string'],
                    'enderecoRua' => ['type' => 'string'],
                    'enderecoNumero' => ['type' => 'string'],
                    'enderecoComplemento' => ['type' => 'string'],
                    'enderecoBairro' => ['type' => 'string'],
                    'enderecoCidade' => ['type' => 'string'],
                    'enderecoUf' => ['type' => 'string'],
                    'telefonePrincipal' => ['type' => 'string'],
                    'telefoneOutro' => ['type' => 'string'],
                ]
            ]
        ];
        $this->setResponse($pathName, 200, $arrResponse, $definitions);

        /**
         * HTTP Response (400) Bad Request
         * HTTP Response (401) Unauthorized
         * HTTP Response (500) Internal Server Error
         */
        $this->setManyDefaultResponse($pathName, [400,401,500]);
    }

    /**
     * Define documentação do endpoint postEditarDados
     *
     * @return void
     */
    private function setEditarDados()
    {
        $pathName = "/editarDados";

        $path = new \stdClass();
        $path->post = new \stdClass();
        $path->post->tags = ["{$this->tag['name']}"];
        $path->post->summary = "Editar dados do usuário";
        $path->post->description = "Endpoint que edita os dados do usuário";
        $path->post->operationId = "postEditarDados";
        $path->post->produces = ["application/json"];
        $this->swagger->paths[$pathName] = $path;

        $this->setParameters($pathName, [
            [
                'name' => 'idUsuario',
                'description' => 'ID do usuário',
                'type' => 'integer'
            ],
            [
                'name' => 'nomeUsuario',
                'description' => 'Nome do usuário',
                'required' => false
            ],
            [
                'name' => 'numrCpf',
                'description' => 'Número de CPF do usuário',
                'required' => false
            ],
            [
                'name' => 'emailUsuario',
                'description' => 'E-mail do usuário',
                'required' => false
            ],
            [
                'name' => 'senhaUsuario',
                'description' => 'Senha do usuário',
                'required' => false
            ],
            [
                'name' => 'dataNascimento',
                'description' => 'Data de nascimento do usuário. Informe no formato DD/MM/AAAA. Exemplo: 18/04/1984',
                'required' => false
            ],
            [
                'name' => 'enderecoRua',
                'description' => 'Rua/Logradouro do endereço',
                'required' => false
            ],
            [
                'name' => 'enderecoNumero',
                'description' => 'Número do endereço',
                'required' => false
            ],
            [
                'name' => 'enderecoComplemento',
                'description' => 'Complemento do endereço',
                'required' => false
            ],
            [
                'name' => 'enderecoBairro',
                'description' => 'Bairro do endereço',
                'required' => false
            ],
            [
                'name' => 'enderecoCidade',
                'description' => 'Cidade do endereço',
                'required' => false
            ],
            [
                'name' => 'enderecoUf',
                'description' => 'UF do endereço',
                'required' => false
            ],
            [
                'name' => 'telefonePrincipal',
                'description' => 'Telefone Principal',
                'required' => false
            ],
            [
                'name' => 'telefoneOutro',
                'description' => 'Outro telefone',
                'required' => false
            ],
        ], true, 'post');

        // HTTP Response (200) Success
        $this->setDefaultResponse($pathName, 200,'retListaUsuarios', [
            'idUsuario' => 'integer',
            'nomeUsuario' => 'string',
            'numrCpf' => 'string',
            'emailUsuario' => 'string',
            'dataNascimento' => 'string',
            'enderecoRua' => 'string',
            'enderecoNumero' => 'string',
            'enderecoComplemento' => 'string',
            'enderecoBairro' => 'string',
            'enderecoCidade' => 'string',
            'enderecoUf' => 'string',
            'telefonePrincipal' => 'string',
            'telefoneOutro' => 'string',
        ], null, 'post');

        /**
         * HTTP Response (400) Bad Request
         * HTTP Response (401) Unauthorized
         * HTTP Response (500) Internal Server Error
         */
        $this->setManyDefaultResponse($pathName, [400,401,500], [], [], [], 'post');
    }

    /**
     * Define a documentação do endpoint postUploadBiometria
     *
     * @return void
     */
    private function setUploadBiometria()
    {
        $pathName = "/uploadBiometria";

        $path = new \stdClass();
        $path->post = new \stdClass();
        $path->post->tags = ["{$this->tag['name']}"];
        $path->post->summary = "Grava a biometria do usuário";
        $path->post->description = "Endpoint que grava a biometria do usuario. O campo tokenJwt precisa ser um payload (array) com accessToken, nomeArquivo e conteudoArquivo codificado em JWT. O conteudoArquivo deve estar em base64";
        $path->post->operationId = "postUploadBiometria";
        $path->post->produces = ["application/json"];
        $this->swagger->paths[$pathName] = $path;

        $this->setParameters($pathName, [
            [
                'name' => 'Parametros',
                'description' => 'Objeto com dados para persistência',
                'type' => false,
                'in' => 'body',
                'schema' => [
                    '$ref' => "#/definitions/paramsUploadBiometria"
                ],
            ]
        ], true, 'post');

        $definitions = [
            'paramsUploadBiometria' => [
                'type' => 'object',
                'properties' => [
                    'tokenJwt' => ['type' => 'string']
                ]
            ],
        ];

        $this->setDefintions($definitions);

        /**
         * HTTP Response (200) Success
         * HTTP Response (400) Bad Request
         * HTTP Response (401) Unauthorized
         * HTTP Response (500) Internal Server Error
         */
        $this->setManyDefaultResponse($pathName, [], [], [], [], 'post');
    }

    /**
     * Define documentação do endpoint getDownloadUsuario
     *
     * @return void
     */
    private function setDownloadUsuario() 
    {
        $pathName = "/downloadBiometria";

        $path = new \stdClass();
        $path->get = new \stdClass();
        $path->get->tags = ["{$this->tag['name']}"];
        $path->get->summary = "Download da biometria";
        $path->get->description = "Endpoint que retorna a biometria do usuário. Retorna um token JWT com payload: conteudoArquivo em base64";
        $path->get->operationId = "getDownloadUsuario";
        $path->get->produces = ["application/json"];
        $this->swagger->paths[$pathName] = $path;

        $this->setParameters($pathName, [
            [
                'name' => 'accessToken',
                'description' => 'Token de acesso e que identifica o usuário logado',
                'type' => 'string'
            ],
        ]);

        // HTTP Response (200) Success
        $this->setDefaultResponse($pathName, 200,'retBiometriaUsuario', [
            'tokenJwt' => 'string',
        ]);

        /**
         * HTTP Response (400) Bad Request
         * HTTP Response (401) Unauthorized
         * HTTP Response (500) Internal Server Error
         */
        $this->setManyDefaultResponse($pathName, [400,401,500]);
    }

    /**
     * Define todas as documentações de endpoint da tag USUARIOS
     *
     * @return void
     */
    public function setRoutes()
    {
        $this->setCreateUser();
        $this->setLogin();
        $this->setListarUsuarios();
        $this->setEditarDados();
        $this->setUploadBiometria();
        // $this->setDownloadUsuario();
    }
}