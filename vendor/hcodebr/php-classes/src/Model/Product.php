<?php

namespace Hcode\Model;

//chama o namespace
use \Hcode\DB\Sql;
use \Hcode\Model;
use \Hcode\Mailer;

/*entendemos que toda classe no model terá get e set,
então será criado uma classe que saiba fazer get e set
e as classe estenderão dessa classe. Neste caso, esta classe foi chamada de model*/
class Product extends Model{

    //método para listar todos os dados da tabela
    public static function listAll()
    {
        $sql = new Sql();

        return $sql->select("SELECT * FROM tb_products ORDER BY desproduct");
    }

    public function save()
    {
        $sql = new Sql();

        $results = $sq->select("CALL sp_products_save(:idproduct, :desproduct, :vlprice, :vlwidth, :vlheight, :vllength, :vlweight, :desurl)", array(
            ":idproduct"=>$this->getidproduct(),
            ":desproduct"=>$this->getdesproduct(),
            ":vlprice"=>$this->getvlprice(),
            ":vlwidth"=>$this->getvlwidth(),
            ":vlheight"=>$this->getvlheight(),
            ":vllength"=>$this->getvllength(),
            ":vlweight"=>$this->getvlweight(),
            ":desurl"=>$this->getdesurl()
        ));

        $this->setData($results[0]);
    }

    public function get($idproduct)
    {
        $sql = new Sql();

        $results = $sql->select("SELECT * FROM tb_products WHERE idproduct = :idproduct", [
            ':idproduct'=>$idproduct
        ]);

        $this->setData($results[0]);
    }

    //neste caso o delete não recebe parâmetro, já que é mais seguro se o objeto a ser deletado já estiver carregado
    public function delete()
    {
        $sql = new Sql();

        $sql->query("DELETE FROM tb_produtos WHERE idproduct = :idproduct", [
            ':idproduct'=>$this->getidproduct()
        ]);
    }

    //método para verificar se exite uma foto, se não existeir irá retornar uma foro padrão
    public function checkPhoto()
    {
        if(file_exists(
            $SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR .
            "res" . DIRECTORY_SEPARATOR .
            "site" . DIRECTORY_SEPARATOR .
            "img" . DIRECTORY_SEPARATOR .
            "products" . DIRECTORY_SEPARATOR .
            $this->getidproduct() . ".jpg"
        )){
            $url =  "/res/site/img/products/" . $this->getidproduct() . ".jpg";
        }else{
            $url =  "/res/site/img/product.jpg";
        }

        return $this->setdesphoto($url);
    }

    public function getValues()
    {
        $this->checkPhoto();

        $values = parent::getValues();

        return $values;
    }

    //método para colocar as fotos dos uploads dos usuários num formato padro. Que será .jpg
    //definir a imagem do produto
    public function setPhoto($file)
    {
        $extension = explode('.', $file['name']);
        $extension = end($extension);

        switch ($extension) {
            case 'jpg':
            case 'jpeg':    
            $image = imagecreatefromjpeg($file['tmp_name']);    
            break;

            case 'gif':    
            $image = imagecreatefromgif($file['tmp_name']);    
            break;

            case 'png':    
            $image = imagecreatefrompng($file['tmp_name']);    
            break;            
            
        }

        $dist =  $SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR .
                 "res" . DIRECTORY_SEPARATOR .
                 "site" . DIRECTORY_SEPARATOR .
                 "img" . DIRECTORY_SEPARATOR .
                 "products" . DIRECTORY_SEPARATOR .
                $this->getidproduct() . ".jpg";

        imagejpeg($image, $dist);
        
        imagedestroy($image);

        $this->checkPhoto();
    }
}