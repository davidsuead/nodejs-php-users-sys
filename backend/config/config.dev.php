<?php

$config = require $app->getConfigurationDir().'/config.php';

$config['twig']['options']['debug']       = true;
$config['twig']['options']['auto_reload'] = true;

$config['monolog']['level'] = Monolog\Logger::DEBUG;

return $config;
