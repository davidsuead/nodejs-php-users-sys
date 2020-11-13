<?php

use App\Application;
use Symfony\Component\Dotenv\Dotenv;

ini_set('date.timezone', 'America/Sao_paulo');

require __DIR__ . '/../vendor/autoload.php';

if (!isset($_SERVER['APP_ENV'])) {
    if (!class_exists(Dotenv::class)) {
        throw new \RuntimeException('APP_ENV environment variable is not defined. You need to define environment variables for configuration or add "symfony/dotenv" as a Composer dependency to load variables from a .env file.');
    }
    $json = file_get_contents('../database.json');
    $database = json_decode($json);

    if ($database->APP_AMBIENTE == 'desenv') {
        (new Dotenv())->load(__DIR__ . '/../.env_des');
    } elseif ($database->APP_AMBIENTE == 'test') {
        (new Dotenv())->load(__DIR__ . '/../.env_test');
    } elseif ($database->APP_AMBIENTE == 'homolog') {
        (new Dotenv())->load(__DIR__ . '/../.env_hom');
    } else {
        (new Dotenv())->load(__DIR__ . '/../.env');
    }
}
global $app;
$app = new Application($_SERVER['APP_ENV'] ?? 'dev');

$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});

$app->run();
