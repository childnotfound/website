<?php
session_start();
require 'vendor/autoload.php';
require __DIR__ . '/vendor/kevinsperrine/idiorm/src/Orm.php';
require __DIR__ . '/models/missing_children.class.php';
require 'config.php';

$app = new \Slim\Slim();

# index
$app->get('/', function() use($app) {
  echo "Hello World";
});

$app->get('/404', function() use($app) {
  $app->render('404.tpl.php');
});

$app->get('/404.json', function() use($app) {
  $child = Model::factory('MissingChildren')->orderByExpr('RAND()')->findOne(1);
  echo json_encode($child); 
});

$app->run();
