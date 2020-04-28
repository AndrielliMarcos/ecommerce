<?php

namespace Hcode\Model;

//chama o namespace
use \Hcode\DB\Sql;
use \Hcode\Model;

/*entendemos que toda classe no model terá get e set,
então será criado uma classe que saiba fazer get e set
e as classe estenderão dessa classe. Neste caso, esta classe foi chamada de model*/
class User extends Model{

    //constante de sessão
    const SESSION = "User";

    public static function login($login, $password)
    {
        $sql = new Sql();

        $results = $sql->select("SELECT * FROM tb_users WHERE deslogin = :LOGIN", array(
            ":LOGIN"=>$login
        ));

        if(count($results) === 0)
        {
            throw new \Exception("Usuário inexistente ou senha inválida");
            
        }

        $data = $results[0];

        if(password_verify($password, $data["despassword"]) === true)
        {
            $user = new User();

            //setData método que chama todos os dados
            $user->setData($data);

            //cria uma sessão pro usuário
            //getValues:método que busca os dados do objeto do usuário em forma de array
            $_SESSION[User::SESSION] = $user->getValues();

            return $user;
        }else{
            throw new \Exception("Usuário inexistente ou senha inválida");
        }
    }
    
    //método para verificar se usuário está logado ou não
    public static function verifyLogin($inadmin = true)
    {
        /*o que iremos verificar?
            1.se foi definida a session com a constante session ou
            2.se a constante for verdadeira ou
            3.se o id de dentro dessa sessão é maior q 0
            4.se é um usuário da administração, pq se ele estiver logado na lj, não poderá logar na administração
            5.se a sessão está rodando no servidor web, inicindo na página index
        */
        if(
            !isset($_SESSION[User::SESSION])
            ||
            !$_SESSION[User::SESSION]
            ||
            !(int)$_SESSION[User::SESSION]["iduser"] > 0
            ||
            (bool)$_SESSION[User::SESSION]["inadmin"] !== $inadmin
        ){
            header("Location: /admin/login");
            exit;
        }
    }

    //método para limpar a sessão
    public static function logout()
    {
        $_SESSION[User::SESSION] == NULL;
    }

}