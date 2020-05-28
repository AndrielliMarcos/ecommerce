<?php

//NESTE ARUIVO FICARÃO AS ROTAS REFERENTES À PÁGINA

use \Hcode\Page;
use \Hcode\Model\Product;

//rota para págia principal q está sendo chamada
$app->get('/', function() {
	
	$products = Product::listAll();

	$page = new Page();

	$page->setTpl("index", [
		'products'=>Product::checkList($products)
	]);

});

 

