<?php //NESTE ARQUIVO FICARÃO AS ROTAS REFERENTES AO USUÁRIO DA ADMINISTRAÇÃO

use \Hcode\PageAdmin;
use \Hcode\Model\User;

//rota para lista todos os usuários
$app->get('/admin/users', function() {
	 
	//verificar se usuário tá logado e tem acesso administrativo para visualizar essa tela
	User::verifyLogin();

	$users  = User::listAll();

	$page = new PageAdmin();

	$page->setTpl("users", array(
		"users"=>$users
	));
	

});

//rota para criar um usuário
//via get, a resposta é um html
$app->get('/admin/users/create', function() {
	 
	//verificar se usuário tá logado e tem acesso administrativo para visualizar essa tela
	User::verifyLogin();

	$page = new PageAdmin();

	$page->setTpl("users-create");
	

});

//rota para apagar um usuário
//deverá sempre vir antes da rota do update, já q o inicio do caminho é o msm
$app->get('/admin/users/:iduser/delete', function($iduser) {

	User::verifyLogin();

	$user = new User();

	$user->get((int)$iduser);

	$user->delete();

	header("Location: /admin/users");
	exit;

});

//rota para atualizar um usuário
//:iduser é usado para solicitar os dados de usuário em específico
//o valor que vier em :iduser, será o mesmo passado em $iduser
$app->get('/admin/users/:iduser', function($iduser) {
	 
	//verificar se usuário tá logado e tem acesso administrativo para visualizar essa tela
	User::verifyLogin();

	$user = new User();

	$user->get((int)$iduser);

	$page = new PageAdmin();

	$page->setTpl("users-update", array(
		"user"=>$user->getValues()
	));
	

});

//rota para salvar
//a rota é a mesma de criar, sendo esta um post
//via post, ele fará um insert dos dados. Ou seja, ele espera receber os dados em post, e enviar para o banco para salvar os registros
$app->post('/admin/users/create', function() {

	User::verifyLogin();

	$user = new User();

	//verificar se inadmin foi setado
	$_POST["inadmin"] = (isset($_POST["inadmin"])) ? 1 : 0;

	//setData é o método que busca as variáveis de uma classe
	$user->setData($_POST);

	$user->save();

	header("Location: /admin/users");
	exit;

});

//rota para salvar a edição
$app->post('/admin/users/:iduser', function($iduser) {

	User::verifyLogin();

	$user = new User();

	$_POST["inadmin"] = (isset($_POST["inadmin"])) ? 1 : 0;

	$user->get((int)$iduser);

	$user->setData($_POST);

	$user->update();

	header("Location: /admin/users");
	exit;

});