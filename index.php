<?php
session_start();
require 'vendor/autoload.php';
require __DIR__ . '/vendor/kevinsperrine/idiorm/src/Orm.php';
require __DIR__ . '/models/missing_children.class.php';
require 'config.php';

switch ($config['db']['type']) {
  case 'mysql':
    ORM::configure('mysql:host=localhost;dbname='.$config['db']['database']);
    ORM::configure('username', $config['db']['username']);
    ORM::configure('password', $config['db']['password']);
    ORM::configure('driver_options', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    break;

  case 'sqlite':
    ORM::configure('sqlite:'.$config['db']['filename']);
    break;

  default:
    echo "Error: database type unknown.";
    break;
}

# Check register information and referrer to see if we allow embedding here
# XXX: there should be a better place for this function
function allowEmbedding() {
  $referrer_host = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
  return !($_SERVER['HTTP_REFERER'] &&
      $referrer_host !== $_SERVER['SERVER_NAME']/* &&
      is_host_registered($referrer_host) */);
}

$app = new \Slim\Slim();

# index
$app->get('/', function() use($app) {
  echo "Hello World";
});

# embedd page for other websites
$app->get('/embedded', function() use($app) {
  if (!allowEmbedding()) {
    $app->render('register_first_please.tpl.php');

    return;
  }

  $app->render('embedded.tpl.php');
});

$app->get('/404', function() use($app) {
  $app->render('404.tpl.php');
});

$app->get('/404.json', function() use($app) {
  $child = Model::factory('MissingChildren')
    ->orderByExpr(($config['db']['type'] == 'mysql') ? 'RAND()' : 'RANDOM()')
    ->findOne();

  header('Content-type: text/javascript');
  if ($child) {
    print $child->to_json();
  } else {
    print '{}';
  }
});

$app->run();
