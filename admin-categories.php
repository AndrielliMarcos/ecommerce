<?php //NESTE ARQUIVO FICARÃO AS ROTAS REFERENTES À CATEGORIA

use \Hcode\PageAdmin;
use \Hcode\Model\User;
use \Hcode\Model\Category;
use \Hcode\Model\Product;	

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

//rota para acessar a página categories-products
$app->get("/admin/categories/:idcategory/products", function($idcategory){
	
	User::verifyLogin();

	$category = new Category();

	$category->get((int)$idcategory);

	$page = new PageAdmin();

	$page->setTpl("categories-products",[
		'category'=>$category->getValues(),
		'productsRelated'=>$category->getProducts(),
		'productsNotRelated'=>$category->getProducts(false)
	]);
});

//rota para adicionar produtos a lista de categoria
$app->get("/admin/categories/:idcategory/products/:idproduct/add", function($idcategory, $idproduct){
	
	User::verifyLogin();

	$category = new Category();

	$category->get((int)$idcategory);

	$product = new Product();

	$product->get((int)$idproduct);

	$category->addProduct($product);

	header("Location: /admin/categories/".$idcategory."/products");
	exit;
});

//rota para remover produtos a lista de categoria
$app->get("/admin/categories/:idcategory/products/:idproduct/remove", function($idcategory, $idproduct){
	
	User::verifyLogin();

	$category = new Category();

	$category->get((int)$idcategory);

	$product = new Product();

	$product->get((int)$idproduct);

	$category->removeProduct($product);

	header("Location: /admin/categories/".$idcategory."/products");
	exit;
});