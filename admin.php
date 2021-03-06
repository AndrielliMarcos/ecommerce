<?php  //NESTE ARQUIVO FICARÃO AS ROTAS REFERENTES À ADMINISTRAÇÃO

use \Hcode\PageAdmin;
use \Hcode\Model\User;
 
//rota para página do adminstrador q está sendo chamada
$app->get('/admin', function() {
	//verifyLogin():método que valida se a pessoa está logada ou não
	User::verifyLogin();
	
	$page = new PageAdmin();

	$page->setTpl("index");

});

//rota para página do login q está sendo chamada
$app->get('/admin/login', function() {
	
	//o header e o footer não são os mesmos, por isso eles serão desabilitados
	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]); 

	$page->setTpl("login");

});

//rota para página do login q está sendo chamada
//mesma rota que a de cima, mas essa será post, já q o method do login.html é post(linha36)
$app->post('/admin/login', function() {
	
	//validar o login
	//User=classe
	//login=método estático
	User::login($_POST["login"], $_POST["password"]);

	//redirecionar a página
	header("Location: /admin");
	exit;

});

//rota para limpar a sessão
$app->get('/admin/logout', function() {
	 
	User::logout();

	header("Location: /admin/login");
	exit;

});