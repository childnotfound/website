<?php
session_start();
require 'vendor/autoload.php';
require 'config.php';

$app = new \Slim\Slim();

# index
$app->get('/', function () use($app) {
  echo "Hello World";
});

$app->run();
