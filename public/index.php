<?php

use Phalcon\Db\Adapter\Pdo\Mysql as PdoMysql;
use Phalcon\Di\FactoryDefault;
use Phalcon\Http\Response;
use Phalcon\Loader;
use Phalcon\Mvc\Micro;


$loader = new Loader();
$loader->registerDirs(
    array(
        __DIR__ . '/../app/models/'
    )
)->register();


$di = new FactoryDefault();

$di->set('db', function () {
    return new PdoMysql(
        array(
            'host' => 'mysql',
            'username' => 'dev',
            'password' => '123456',
            'dbname' => 'phalcon_api'
        )
    );
});

$app = new Micro($di);

include_once __DIR__ . '/../app/routes/cars.php';

$app->notFound(function () {
    $response = new Response();
    $response->setStatusCode(404, "Not Found")->sendHeaders();
    $response->setJsonContent(
        array(
            'status' => 'This is crazy, but the page you were looking for does not exist.',
        )
    );
    return $response;
});

$app->handle();

