<?php

namespace App\Swagger;

use stdClass;
use Exception;
use Psr\Container\ContainerInterface;

/**
 * Classe para definição da documentação com Swagger
 * @author David Diniz <diniz.david@gmail.com>
 * @since 1.0.0
 */
class SwaggerDoc 
{
    /**
     * Recipiente para injeção de dependência
     *
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Objeto swagger para documentação
     *
     * @var stdClass
     */
    private $swagger;

    /**
     * Na contrução da classe define parâmetro básicos da classe 
     * (container, swagger, host, basePath, schemes)
     *
     * @param ContainerInterface $container
     */
    public function __construct($container) 
    {
        $this->container = $container;

        $this->swagger = new stdClass();
        $this->swagger->swagger = "2.0";
        $this->swagger->host = str_replace('https://', '', str_replace('http://', '', $this->container->environment->get('APP_URL_SISTEMA')));
        $this->swagger->basePath = "/api/1.0.0";

        if($this->container->environment->get('APP_ENV') == 'dev') {
            $this->swagger->schemes = ["http", "https"];
        } else {
            $this->swagger->schemes = ["https", "http"];
        }
    }

    /**
     * Retorna os parâmetros para definição de Tags
     * (sequence, name, description, className)
     *
     * @return array
     */
    private function getTagsOptions() 
    {
        return [
            'atendeGoias' => [
                'sequence' => 0,
                'name' => "USERS",
                'description' => "APIs de Integração com Módulo Usuários",
                'className' => UsuarioTag::class,
            ],
        ];
    }

    /**
     * Define dados informativos da documentação swagger
     * (description, verson, title, license)
     *
     * @return void
     */
    private function setInfo() 
    {
        $this->swagger->info = new \stdClass();
        $this->swagger->info->description = "API de integração da {$this->container->environment->get('APP_NOME_SISTEMA')} com aplicações internas e externas";
        $this->swagger->info->version = "1.0.0";
        $this->swagger->info->title = "{$this->container->environment->get('APP_SISTEMA_API')} - {$this->container->environment->get('APP_NOME_SISTEMA')}";
        $this->swagger->info->license = new \stdClass();
        $this->swagger->info->license->name = "© Copyright " . date('Y') . " Todos os direitos reservados. Desenvolvido por David Diniz";
        $this->swagger->info->license->url = $this->container->environment->get('APP_URL_SISTEMA');
    }

    /**
     * Define a construção de uma Tag
     *
     * @param int $sequence - Sequência da Tag
     * @param string $name - Nome da Tag
     * @param string $description - Um descrição sobre a Tag
     * @return void
     */
    private function setTag($sequence, $name, $description) 
    {
        if (!isset($this->swagger->tags) || !is_array($this->swagger->tags)) {
            $this->swagger->tags = [];
        }
        $this->swagger->tags[$sequence] = new \stdClass();
        $this->swagger->tags[$sequence]->name = $name;
        $this->swagger->tags[$sequence]->description = $description;
    }

    /**
     * Chama as rotas de cada Tag criada.
     * Aqui deve a instânciar a classe de cada Tag
     *
     * @param array $options[sequence,name,description]
     * @return void
     */
    private function setTagRoutes($options) 
    {
        if (!isset($this->swagger->paths)) {
            $this->swagger->paths = [];
        }

        if (!isset($this->swagger->definitions)) {
            $this->swagger->definitions = [];
        }

        $obj = new $options['className']($this->container, $this->swagger, $options);
        $obj->setRoutes();
    }

    /**
     * Iniciliza a documentação swagger
     *
     * @return void
     */
    public function init() 
    {
        $this->setInfo();
        $tagsOptions = $this->getTagsOptions();
        if (!empty($tagsOptions) && count($tagsOptions) > 0) {
            foreach ($tagsOptions as $options) {

                if (!isset($options['sequence'])) {
                    throw new Exception('Informe a sequência da tag');
                }

                if (empty($options['name'])) {
                    throw new Exception('Informe o nome da tag');
                }

                if (empty($options['description'])) {
                    throw new Exception('Informe a descrição da tag');
                }

                $this->setTag($options['sequence'], $options['name'], $options['description']);
                $this->setTagRoutes($options);
            }
        }
    }

    /**
     * Retorna o objeto $swagger (documentação construída)
     *
     * @return stdClass $swagger
     */
    public function getSwaggerDoc() 
    {
        return $this->swagger;
    }

}
