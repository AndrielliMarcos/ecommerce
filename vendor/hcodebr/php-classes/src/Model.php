<?php

namespace Hcode;

class Model{

    //variável que reberá todos os valores do nosso objeto
    private $values = [];

    //para saber se o método foi chamado
    //$name = nome do método que foi chamado. $args=quais forão os parâmetros passados
    public function __call($name, $args)
    {
        /*primeiro temos que detectar se foi um método get ou um set, já que os comportamentos deles são diferentes.
        Se for get, vamos trazer a infomação e retornar, se for set temos que atribuir valor do atributo que foi passado.
        Para detectar qual é o método vamos verificar as 3 primeiras letras do nome, usando o método substr($name, 0, 3).
        E depois vamos descobrir o nome do campo($fieldName) que foi chamado. Ex.:getidusuario. Então vamos descartar as 
        3 primeiras letras, por isso comeamos de 3, e pegar somente o restante. A função strlen foi usada para contar a 
        quantidade de letras da palavra*/
        $method = substr($name, 0, 3);
        $fieldName = substr($name, 3, strlen($name));
        
        switch ($method) 
        {
            case 'get':
                //se existe o nome, retorna ele, se não, retorna null
                return (isset($this->values[$fieldName])) ? $this->values[$fieldName] : NULL;
                break;

            case 'set':
                   $this->values[$fieldName] = $args;
            break;    
          
        }
    }

    public function setData($data = array())
    {
        foreach ($data as $key => $value)
        {
            //o nome do campo virá dinamicamente, já que temos vários campos no banco
            //por exemplo, se fosse o campo idusuário($key), 
            //teriamos que chamar set+idusuario (set+$key)
            //tudo que é criado dinamicamente em php é colocado entre {}. Ex.:{"set" . $key}($value)  {nome do método}(valor)
            $this->{"set" .$key}($value);
        }
    }

    public function getValues()
    {
        return $this->values;
    }
}