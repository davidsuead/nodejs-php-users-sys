<?php

use Awurth\Slim\Helper\Twig\AssetExtension;
use Awurth\SlimValidation\Validator;
use Awurth\SlimValidation\ValidatorExtension;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Slim\Flash\Messages;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;
use Twig\Extension\DebugExtension;
use \SlimSession\Helper;
use App\Service\ApiService;
use App\Service\JwtService;
use App\Service\AuthService;
use App\Service\UsersApiService;
use App\Service\UsuarioBiometriaService;
use App\Service\UsuarioEnderecoService;
use App\Service\UsuarioService;
use App\Service\UsuarioTelefoneService;
use App\Service\UsuarioTokenService;
use App\Service\UtilService;
use App\Service\ValidaService;

/**
 * Injeção de dependência config Slim
 * @tutorial https://www.slimframework.com/docs/
 */
$container['app'] = function($app) {
    return $app;
};

/**
 * Injeção de dependência de Sessões
 * @tutorial https://github.com/bryanjhv/slim-session
 */
$container['session'] = function ($c) {
    return new Helper();
};

$container['flash'] = function () {
    return new Messages();
};

/**
 * Injeção de dependência do Validation
 * @tutorial http://respect.github.io/Validation/docs/validators.html
 */
$container['validator'] = function () {
    return new Validator();
};

/**
 * Injeção de dependência do Twig
 * @tutorial https://twig.symfony.com/doc/2.x/
 */
$container['twig'] = function ($container) {
    $config = $container['settings']['twig'];

    $twig = new Twig($config['path'], $config['options']);

    $twigExt = new TwigExtension($container->router, $container->request->getUri());
    $twigExt->setBaseUrl($container->environment['APP_URL_SISTEMA']);
    $twig->addExtension($twigExt);
    $twig->addExtension(new DebugExtension());
    $twig->addExtension(new AssetExtension($container->request, null, 'asset', $container->environment['APP_URL_SISTEMA']));
    $twig->addExtension(new ValidatorExtension($container->validator));
    $twig->getEnvironment()->addGlobal('flash', $container->flash);
    $twig->getEnvironment()->addGlobal('pagina', $container->request->getUri()->getPath());
    $twig->getEnvironment()->addGlobal('versao', $container->environment['APP_VERSAO']);
    $twig->getEnvironment()->addGlobal('NOME_SISTEMA', $container->environment['APP_NOME_SISTEMA']);
    $twig->getEnvironment()->addGlobal('SIGLA_SISTEMA', $container->environment['APP_SISTEMA_API']);
    $twig->getEnvironment()->addGlobal('APP_URL_SISTEMA', $container->environment['APP_URL_SISTEMA']);

    return $twig;
};

/**
 * Injeção de dependência para gerador de log da aplicação
 * @tutorial https://github.com/Seldaek/monolog/blob/master/doc/01-usage.md
 */
$container['monolog'] = function ($container) {
    $config = $container['settings']['monolog'];

    $logger = new Logger($config['name']);
    $logger->pushProcessor(new UidProcessor());
    $logger->pushHandler(new StreamHandler($config['path'], $config['level']));

    return $logger;
};

/**
 * Injeção de Dependência das Services
 */
$container['apiService'] = function ($container) {
    return new ApiService($container);
};

$container['authService'] = function ($container) {
    return new AuthService($container);
};

$container['utilService'] = function ($container) {
    return new UtilService($container);
};

$container['validaService'] = function ($container) {
    return new ValidaService();
};

$container['jwtService'] = function ($container) {
    return new JwtService($container);
};

$container['usuarioBiometriaService'] = function ($container) {
    return new UsuarioBiometriaService($container);
};

$container['usuarioEnderecoService'] = function ($container) {
    return new UsuarioEnderecoService($container);
};

$container['usuarioService'] = function ($container) {
    return new UsuarioService($container);
};

$container['usuarioTelefoneService'] = function ($container) {
    return new UsuarioTelefoneService($container);
};

$container['usuarioTokenService'] = function ($container) {
    return new UsuarioTokenService($container);
};

$container['usersApiService'] = function ($container) {
    return new UsersApiService($container);
};
