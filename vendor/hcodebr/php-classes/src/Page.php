<?php
//namespace especifica onde a classe está
namespace Hcode;

//vamos usar classe q está em outro namespace
//quando chamarmos um new tpl, o programa saberá que é do namespace rain
use Rain\Tpl;

class Page
{
    private $tpl;
    private $options = [];
    private $defaults = [
        "data"=>[]
    ];

    public function __construct($opts = array()){

        $this->options = array_merge($this->defaults, $opts);

        //configurar o template
        $config = array( 
            "tpl_dir"	=> $_SERVER["DOCUMENT_ROOT"] ."/views/",
            "cache_dir"	=> $_SERVER["DOCUMENT_ROOT"] ."/views-cache/",
            "debug"     => false
        );
        Tpl::configure( $config );

        $this->tpl = new Tpl;

        $this->setData($this->options["data"]);

        //desenhar o template na tela
        //essas informações aparecerá m tods as páginas
        $this->tpl->draw("header");

    }

    //método para chamar os dados do template
    private function setData($data = array())
    {
        foreach ($data as $key => $value) {
            $this->tpl->assign($key, $value);
          }
    }

    //método do corpo das páginas
    public function setTpl($name, $data = array(), $returnHTML = false)
    {
        $this->setData($data);
        return $this->tpl->draw($name, $returnHTML);
    }

     public function __destruct()
    {
        //desenhar o template na tela
        //essas informações aparecerá m tods as páginas
        $this->tpl->draw("footer");
    }
}




