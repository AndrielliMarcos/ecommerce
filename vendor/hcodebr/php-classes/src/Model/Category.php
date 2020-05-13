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
            ':idcategory'=>$this->getcategory()
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
}