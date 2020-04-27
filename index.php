<?php 

require_once("vendor/autoload.php");

//namespace
use \Slim\Slim;
use \Hcode\Page;

$app = new Slim();

$app->config('debug', true);

//rota q está sendo chamada
$app->get('/', function() {
    
	$page = new Page();

	$page->setTpl("index");

});

$app->run();

 ?>