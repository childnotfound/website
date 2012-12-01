<?php
session_start();
require 'vendor/autoload.php';
require __DIR__ . '/vendor/kevinsperrine/idiorm/src/Orm.php';
require __DIR__ . '/models/missing_children.class.php';
require 'config.php';

ORM::configure('mysql:host=localhost;dbname='.$config['db']['database']);
ORM::configure('username', $config['db']['username']);
ORM::configure('password', $config['db']['password']); 
ORM::configure('driver_options', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));

$app = new \Slim\Slim();

# index
$app->get('/', function() use($app) {
  echo "Hello World";
  $child = Model::factory('MissingChildren')->orderByExpr('RAND()')->findOne();
  var_dump($child);
});

$app->get('/404', function() use($app) {
  $app->render('404.tpl.php');
});

$app->get('/404.json', function() use($app) {
  $child = Model::factory('MissingChildren')->orderByExpr('RAND()')->findOne();
  echo $child->to_json();
});

$app->run();
