<?php

//NESTE ARUIVO FICARÃO AS ROTAS REFERENTES À PÁGINA

use \Hcode\Page;

//rota para págia principal q está sendo chamada
$app->get('/', function() {
    
	$page = new Page();

	$page->setTpl("index");

});

