<?php

require '../../vendor/autoload.php';
require '../Cors.php';
require '../Config/PDO.php';
require '../Config/Mysql.php';
require '../Models/EnderecoModel.php';

use src\controllers\Request;
use Firebase\JWT\JWT;

 $queryString = filter_input(INPUT_SERVER, 'QUERY_STRING');
 $Endereco = new EnderecoModel($coon, 'endereco');

 function Erro($msg){
    header('HTTP/1.0 404 Not Found', true, 404);
    echo json_encode(['Error'=>$msg]);
}

Request::Request("POST", function($msg){
    global $Endereco;
    
    if(!isset($_SERVER['HTTP_TOKEN'])){
        Erro('Envie o Token');
        exit;
    }
    
    try{
        $user = JWT::decode($_SERVER['HTTP_TOKEN'], $_ENV['TOKEN'], array('HS256'));
        $userArray = (array) $user;
        
        
        if(isset($msg['rua']) && isset($msg['estado']) && isset($msg['cidade']) && isset($msg['cep']) &&isset($msg['numero']) && isset($msg['bairro'])){
            echo $Endereco->Insert($msg['rua'],$msg['bairro'],$msg['cidade'],$msg['estado'], intval($msg['numero']), intval($msg['cep']), intval($userArray['id']));
            
            return;
        }
        return Erro('Algum campo está faltando');

    }catch(Exception $e){
        return Erro('Token inválido ou expirado!');
    }

    
});

Request::Request("GET", function($msg){
    global $queryString;
    global $Endereco;
    parse_str($queryString, $id);
    if($id && isset($id['id'])){
        echo $Endereco->FindId($id['id']);
        return;
    }
    echo $Endereco->FindAll();
});

Request::Request("PUT", function($msg){
    global $Endereco;
    global $queryString;
    parse_str($queryString, $id);
    MiddleWareLoginEndereco($id);

    echo $Endereco->Update($msg,$id['id']);
    
});

Request::Request("DELETE", function($msg){
    global $Endereco;
    global $queryString;
    parse_str($queryString, $id);
    MiddleWareLoginEndereco($id);

    echo $Endereco->Delete($id['id']);
});


function MiddleWareLoginEndereco($id){
    global $Endereco;
    
    if(!isset($_SERVER['HTTP_TOKEN'])){
        Erro('Envie o Token');
        exit;
    }
    
    try{
        $user = JWT::decode($_SERVER['HTTP_TOKEN'], $_ENV['TOKEN'], array('HS256'));
        $userArray = (array) $user;

    
    
   
        if(isset($id) && isset($id['id'])){
            $user = $Endereco->FindId($id['id']);
            $useTable = json_decode($user,true);
            
            if(!is_array($useTable)){
                echo $user;
                return;
            }
            if($useTable['id_user'] == $userArray['id'] && $userArray['master']){
                 
                 return;
            }

             Erro('Você não pode fazer este tipo de acesso!');
             exit;
       } 
   
    
     Erro('Campo (id) não existe');
     exit;
        
        
    }catch(Exception $e){
         Erro('Token inválido ou expirou, faça login novamente');
         exit;
    }
}




