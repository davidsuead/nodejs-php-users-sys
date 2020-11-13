<?php

use App\Middleware\AclMiddleware;

$container['acl.middleware'] = function ($container) {
    return new AclMiddleware($container);
};

$app->add($container['acl.middleware']);
$app->add(new \Slim\Middleware\Session([
    'name' => 'dummy_session',
    'autorefresh' => true,
    'lifetime' => '1 hour'
]));
//$app->add($container['csrf']);
