<?php

namespace Hcode\Model;

//chama o namespace
use \Hcode\DB\Sql;
use \Hcode\Model;
use \Hcode\Mailer;

/*entendemos que toda classe no model terá get e set,
então será criado uma classe que saiba fazer get e set
e as classe estenderão dessa classe. Neste caso, esta classe foi chamada de model*/
class Category extends Model{

    //método para listar todos os dados da tabela
    public static function listAll()
    {
        $sql = new Sql();

        return $sql->select("SELECT * FROM tb_categories ORDER BY descategory");
    }

    public function save()
    {
        $sql = new Sql();

        $results = $sq->select("CALL sp_categories_save(:idcategory, :descategory)", array(
            ":idcategory"=>$this->getidcategory(),
            ":descategory"=>$this->getdescategory()
        ));

        $this->setData($results[0]);

        //atualizar o arquivo categories-menu.html
        Category::updateFile(); 
    }

    public function get($idcategory)
    {
        $sql = new Sql();

        $results = $sql->select("SELECT * FROM tb_categories WHERE idcategory = :idcategory", [
            ':idcategory'=>$idcategory
        ]);

        $this->setData($results[0]);
    }

    //neste caso o delete não recebe parâmetro, já que é mais seguro se o objeto a ser deletado já estiver carregado
    public function delete()
    {
        $sql = new Sql();

        $sql->query("DELETE FROM tb_categories WHERE idcategory = :idcategory", [
            ':idcategory'=>$this->getidcategory()
        ]);

        //atualizar o arquivo categories-menu.html
        Category::updateFile(); 
    }

    //método para atualiza a página categories-menu
    public static function updateFile()
    {
        //trazer todas as categorias do banco
       $categories = Category::listAll();

       $html = [];

       foreach ($categories as $row)
       {
            array_push($html, '<li><a href="/categories/'.$row['idcategory'].'">'.$row['descategory'].'</a></li>');
       }

       //função para salvar o arquivo categories-menu
       file_put_contents($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "categories-menu.html", implode('', $html));

    }

    //método que retorna todos os PRODUTOS
    //o parâmetro nos dirá se o produto está ou não relacionado a esta categoria
    public function getProducts($related = true)
    {
        $sql = new Sql();

        if($related === true)
        {
           return $sql->select("
              SELECT * FROM tb_products WHERE idproduct IN(
                SELECT a.idproduct
                FROM tb_products a 
                INNER JOIN tb_productscategories b ON a.idproduct = b.idproduct
                WHERE b.idcategory = :idcategory
              );

            ", [
                ':idcategory'=>$this->getidcategory()
            ]);
        }
        else
        {
            return $sql->select("
              SELECT * FROM tb_products WHERE idproduct NOT IN(
                SELECT a.idproduct
                FROM tb_products a 
                INNER JOIN tb_productscategories b ON a.idproduct = b.idproduct
                WHERE b.idcategory = :idcategory
              );

            ", [
                ':idcategory'=>$this->getidcategory()
            ]);

        }
    }

    //método para adicionar um produto na lista de categoria
    public function addProduct(Product $product)
    {
        $sql = new Sql();

        $sql->query("INSERT INTO tb_productscategories (idcategory, idproduct) VALUES (:idcategory, :idproduct)",[
            ':idcategory'=>$this->getcategory(),
            ':idproduct'=>$product->getidproduct()
        ]);
    }

    //método para remover um produto na lista de categoria
    public function removeProduct(Product $product)
    {
        $sql = new Sql();

        $sql->query("DELETE FROM tb_productscategories WHERE idcategory = :idcategory AND idproduct = :idproduct)",[
            ':idcategory'=>$this->getcategory(),
            ':idproduct'=>$product->getidproduct()
        ]);
    }

}