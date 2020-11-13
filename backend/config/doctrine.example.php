<?php

$paths = [
    __DIR__ . '/../src/Entity'
];

$cache = new \Doctrine\Common\Cache\ArrayCache();

$config = new \Doctrine\ORM\Configuration();
$config->setMetadataCacheImpl($cache);
$driverImpl = $config->newDefaultAnnotationDriver($paths);
$config->setMetadataDriverImpl($driverImpl);
$config->setQueryCacheImpl($cache);
$config->setResultCacheImpl($cache);
$config->setProxyDir(__DIR__ . '/../proxy');
$config->setProxyNamespace('App\Proxies');
if (isset($container)) {
    $config->setAutoGenerateProxyClasses($container->environment['APP_ENV'] == 'prod' ? false : true);
    $entityManager = \Doctrine\ORM\EntityManager::create($container->settings['doctrine'], $config);
    $conn = $entityManager->getConnection();
    $conn->getDatabasePlatform()->registerDoctrineTypeMapping('tinyint', 'boolean');
    $conn->getDatabasePlatform()->registerDoctrineTypeMapping('bit', 'boolean');
    $conn->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
} else {
    /**
     * Usado para o client doctrine
     * 
     */
    $dbParams = [
        'driver' => 'pdo_mysql',
        'host' => 'localhost',
        'user' => 'username',
        'password' => '123456',
        "port" => "3306",
        'dbname' => 'database_name'
    ];
    $entityManager = \Doctrine\ORM\EntityManager::create($dbParams, $config);
    $conn = $entityManager->getConnection();
    $conn->getDatabasePlatform()->registerDoctrineTypeMapping('tinyint', 'boolean');
    $conn->getDatabasePlatform()->registerDoctrineTypeMapping('bit', 'boolean');
    $conn->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
}