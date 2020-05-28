<?php

//NESTE ARUIVO FICARÃO AS ROTAS REFERENTES À PÁGINA

use \Hcode\Page;
use \Hcode\Model\Product;
use \Hcode\Model\Category;

//rota para págia principal q está sendo chamada
$app->get('/', function() {
	
	$products = Product::listAll();

	$page = new Page();

	$page->setTpl("index", [
		'products'=>Product::checkList($products)
	]);

});

//rota para cada categoria criada
//ao clicar no nome da cateroria na página, o usuario será enviado para a página correspondete
$app->get("/categories/:idcategory", function($idcategory){
	
	$category = new Category();

	$category->get((int)$idcategory);

	$page = new Page();

	$page->setTpl("category",[
		'category'=>$category->getValues(),
		'products'=>Product::checkList($category->getProducts())
	]);
});

 

