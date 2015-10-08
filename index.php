<?php

define('ROOT_DIR', __DIR__);

require_once 'vendor/autoload.php';
require_once 'autoload.php';

$app = new \Slim\Slim();

$app->get('/', function () {

    /** @var \Latte\Engine $latte */
    $latte = \Mossbauer\Core\Container::get('latte');

    $latte->render('templates/home/home.latte');
});

$app->run();