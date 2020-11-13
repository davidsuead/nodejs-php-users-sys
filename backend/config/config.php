<?php

$json = file_get_contents('../database.json');
$database = json_decode($json);

return [
    'twig' => [
        'path' => [
            $app->getRootDir() . '/templates'
        ],
        'options' => [
            'cache' => $app->getCacheDir() . '/twig',
        ]
    ],
    'monolog' => [
        'name' => 'app',
        'path' => $app->getLogDir() . '/' . $app->getEnvironment() . '-' . date("d-m-Y") . '.log',
        'level' => Monolog\Logger::ERROR
    ],
    'doctrine' => [
        'host' => $database->APP_DATABASE_HOST,
        'port' => $database->APP_DATABASE_PORT,
        'user' => $database->APP_DATABASE_USERNAME,
        'password' => $database->APP_DATABASE_PASSWORD,
        'dbname' => $database->APP_DATABASE_DATABASE,
        'driver' => $_SERVER['APP_DATABASE_DRIVER'],
        "driverOptions" => [
            1002 => 'SET NAMES utf8'
        ]
    ]
];
