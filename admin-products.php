<?php

use \Hcode\PageAdmin;
use \Hcode\Model\User;
use \Hcode\Model\Product;

//rota para acessar p template de produtos
$app->get("/admin/products", function(){

    User::verifyLogin();

    $products = Product::listAll();

    $page = new PageAdmin();

    $page->setTpl("products",[
        "products"=>$products
    ]);

});

//rota para criar uma categoria
//ao clicar no botão categoria na tela principal, esta rota encaminha 
$app->get("/admin/products/create", function(){

    User::verifyLogin();

    $page = new PageAdmin();

    $page->setTpl("products-create");

});

//rota do formulário view(products-create) para criar um produto
$app->post("/admin/products/create", function(){

    User::verifyLogin();

    $product = new Product();

    $product->setData($_POST);

    $product->save();

    header("location: /admin/products");
    exit;
});

//rota para atualizar um produto
//ao clicar no botão atualiza, esta rota encaminha o usuario para tela products-update
$app->get("/admin/products/:idproduct", function($idproduct){

    User::verifyLogin();

    $product = new Product();

    $product->get((int)$idproduct);

    $page = new PageAdmin();

    $page->setTpl("products-update",[
        'product'=>$product->getValues()
    ]);
});

//rota do formulário view(products-update) para atualizar um produto
$app->post("/admin/products/:idproduct", function($idproduct){

    User::verifyLogin();

    $product = new Product();

    $product->get((int)$idproduct);

    $product->setData($_POST);

    $product->setPhoto($_FILES['file']);

    header('Location: /admin/products');
    exit;
});

//rota para deletar um produto
$app->get("/admin/products/:idproduct/delete", function($idproduct){

    User::verifyLogin();

    $product = new Product();

    $product->get((int)$idproduct);

    $product->delete();

    header('Location: /admin/products');
    exit;
});
