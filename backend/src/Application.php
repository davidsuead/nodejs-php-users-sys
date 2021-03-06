<?php

namespace App;

use Slim\App;

class Application extends App 
{
    /**
     * @var string
     */
    protected $environment;

    /**
     * @var string
     */
    protected $rootDir;

    /**
     * Constructor.
     *
     * @param string $environment
     */
    public function __construct($environment) 
    {
        $this->environment = $environment;
        $this->rootDir = $this->getRootDir();

        parent::__construct($this->loadConfiguration());
        $this->configureContainer();
        $this->loadConstantes();
        $this->registerHandlers();
        $this->loadMiddleware();
        $this->registerControllers();
        $this->loadRoutes();
        $this->configureDoctrine();
    }

    public function getCacheDir() 
    {
        return $this->getRootDir() . '/var/cache/' . $this->environment;
    }

    public function getConfigurationDir() 
    {
        return $this->getRootDir() . '/config';
    }

    public function getEnvironment() 
    {
        return $this->environment;
    }

    public function getLogDir() 
    {
        return $this->getRootDir() . '/var/log';
    }

    public function getRootDir() 
    {
        if (null === $this->rootDir) {
            $this->rootDir = dirname(__DIR__);
        }
        $app = $this;
        $container = $this->getContainer();
        $container['rootDir'] = $this->rootDir;
        return $this->rootDir;
    }

    protected function configureContainer() 
    {
        $app = $this;
        $container = $this->getContainer();
        require $this->getConfigurationDir() . '/container.php';
    }

    protected function loadConfiguration() 
    {
        $app = $this;
        $configuration = [
            'settings' => require $this->getConfigurationDir() . '/slim.php'
        ];

        if (file_exists($this->getConfigurationDir() . '/config.' . $this->getEnvironment() . '.php')) {
            $configuration['settings'] += require $this->getConfigurationDir() . '/config.' . $this->getEnvironment() . '.php';
        } else {
            $configuration['settings'] += require $this->getConfigurationDir() . '/config.php';
        }
        return $configuration;
    }

    protected function loadMiddleware() 
    {
        $app = $this;
        $container = $this->getContainer();
        require $this->getConfigurationDir() . '/middleware.php';
    }

    protected function loadRoutes() 
    {
        $app = $this;
        $container = $this->getContainer();
        require $this->getConfigurationDir() . '/routes.php';
    }

    protected function registerControllers() 
    {
        $container = $this->getContainer();
        $app = $this;
        if (file_exists($this->getConfigurationDir() . '/controllers.php')) {
            $controllers = require $this->getConfigurationDir() . '/controllers.php';
            foreach ($controllers as $key => $class) {
                $container[$key] = function ($container) use ($class) {
                    return new $class($container);
                };
            }
        }
    }

    protected function registerHandlers() 
    {
        $container = $this->getContainer();
        require $this->getConfigurationDir() . '/handlers.php';
    }

    protected function configureDoctrine() 
    {
        $app = $this;
        $container = $this->getContainer();
        $container['em'] = function ($container) {
            require $this->getConfigurationDir() . '/doctrine.php';
            return $entityManager;
        };
    }

    protected function loadConstantes() 
    {
        $app = $this;
        $container = $this->getContainer();
        $container['constante'] = require $this->getConfigurationDir() . '/constantes.php';
    }
}
