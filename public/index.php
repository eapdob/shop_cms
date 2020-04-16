<?php

require_once '../config/init.php';
require_once LIBS . '/functions.php';
require_once CONF . '/routes.php';

new \shop\App();

//\shop\App::$app->setProperty('test', 'TEST');
//debug(\shop\App::$app->getProperties());

//throw new Exception('Страница не найдена', 404);

//debug(\shop\Router::getRoutes());