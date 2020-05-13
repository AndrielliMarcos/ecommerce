<?php 

//iniciar o uso de sessões
session_start();

require_once("vendor/autoload.php");

//namespace
use \Slim\Slim;
use \Hcode\Page;
use \Hcode\PageAdmin;
use \Hcode\Model\User;
use \Hcode\Model\Category;

$app = new Slim();

$app->config('debug', true);

//rota para págia principal q está sendo chamada
$app->get('/', function() {
    
	$page = new Page();

	$page->setTpl("index");

});

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

//rota para acessar p template de categorias
$app->get("/admin/categories", function(){

	User::verifyLogin();

	$categories = Category::listAll();

	$page = new PageAdmin();

	$page->SetTpl("categories", [
		'categories'=>$categories
	]);
});

//rota para criar uma categoria
//ao clicar no botão categoria na tela principal, esta rota encaminha o usuario para tela categories-create
$app->get("/admin/categories/create", function(){

	User::verifyLogin();

	$page = new PageAdmin();

	$page->SetTpl("categories-create");
});

//rota do formulário view(categories-create) para criar uma categoria
$app->post("/admin/categories/create", function(){

	User::verifyLogin();

	$category = new Category();

	$category->setData($_POST);

	$category->save(); 

	header('Location: /admin/categories');
	exit;
});

//rota para deletar uma categoria
$app->get("/admin/categories/:idcategory/delete", function($idcategory){

	User::verifyLogin();

	$category = new Category();

	//get é um método da Classe Category, para carregar o objeto q será deletado
	//carrega
	$category->get((int)$idcategory);
	//deleta
	$category->delete();
	//redireciona
	header('Location: /admin/categories');
	exit;
});

//rota para atualizar uma categoria
//ao clicar no botão atualiza, esta rota encaminha o usuario para tela categories-update
$app->get("/admin/categories/:idcategory", function($idcategory){

	User::verifyLogin();

	$category = new Category();
	//carrega
	$category->get((int)$idcategory);
	 
	$page = new PageAdmin();

	$page->SetTpl("categories-update", [
		'category'=>$category->getValues()
	]);
});

//rota do formulário view(categories-update) para atualizar uma categoria
$app->post("/admin/categories/:idcategory", function($idcategory){

	User::verifyLogin();
	
	$category = new Category();
	//carrega
	$category->get((int)$idcategory);
	 
	$category->setData($_POST);

	$category->save();

	header('Location: /admin/categories');
	exit;
});


$app->run();

 ?>