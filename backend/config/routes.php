<?php

/**
 * Rotas APP
 */
$app->group('/', function () {
    $this->get('', 'app.controller:index')->setName('app.home');
    $this->get('401', 'app.controller:error401')->setName('app.401');
    $this->get('403', 'app.controller:error403')->setName('app.403');
    $this->get('404', 'app.controller:error404')->setName('app.404');
    $this->get('4xx', 'app.controller:error4xx')->setName('app.4xx');
    $this->get('500', 'app.controller:error500')->setName('app.500');
});

/**
 * Rotas API
 */
$app->group('/api', function () {
    $this->get('/swagger', 'api.controller:swagger')->setName('api.swagger');

    // APIs de integração com USUARIOS
    $this->post('/1.0.0/createUser', 'api.usuario.controller:createUser')->setName('api.createUser');
    $this->post('/1.0.0/login', 'api.usuario.controller:login')->setName('api.login');
    $this->get('/1.0.0/listarUsuarios', 'api.usuario.controller:listarUsuarios')->setName('api.listarUsuarios');
    $this->post('/1.0.0/editarDados', 'api.usuario.controller:editarDados')->setName('api.editarDados');
    $this->post('/1.0.0/uploadBiometria', 'api.usuario.controller:uploadBiometria')->setName('api.uploadBiometria');
    $this->get('/1.0.0/downloadBiometria', 'api.usuario.controller:downloadBiometria')->setName('api.downloadBiometria');
});
