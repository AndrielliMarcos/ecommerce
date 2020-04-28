<?php 

require_once("vendor/autoload.php");

//namespace
use \Slim\Slim;
use \Hcode\Page;
use \Hcode\PageAdmin;

$app = new Slim();

$app->config('debug', true);

//rota para págia principal q está sendo chamada
$app->get('/', function() {
    
	$page = new Page();

	$page->setTpl("index");

});

//rota ara página do adminstrador q está sendo chamada
$app->get('/admin', function() {
    
	$page = new PageAdmin();

	$page->setTpl("index");

});
$app->run();

 ?>