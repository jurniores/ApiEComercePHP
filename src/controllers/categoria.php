<?php

require '../../vendor/autoload.php';
require '../Cors.php';
require '../Config/PDO.php';
require '../Config/Mysql.php';

require '../Models/CategoriaModel.php';


use Firebase\JWT\JWT;
use src\controllers\Request;


 $queryString = filter_input(INPUT_SERVER, 'QUERY_STRING');

 $Categoria = new CategoriaModel($coon, 'categoria');

function Erro($msg){
    header('HTTP/1.0 404 Not Found', true, 404);
    echo json_encode(['Error'=>$msg]);
}


Request::Request("POST", function($msg){
    global $Categoria;
    if(!isset($_SERVER['HTTP_TOKEN'])){
        Erro('Envie o Token');
        exit;
    }
    
    try{
        $user = JWT::decode($_SERVER['HTTP_TOKEN'], $_ENV['TOKEN'], array('HS256'));
        $userArray = (array) $user;
        
        if(!$userArray['master']){
            Erro('Você não é um administrador, não pode fazer isso!');
            exit;
        } 
        
        if(isset($msg['nome'])){
            echo $Categoria->Insert($msg['nome']);
            return;
        }
        return Erro('Algum campo está faltando');

    }catch(Exception $e){
        return Erro('Token inválido ou expirado!');
    }

   
    
});



Request::Request("GET", function($msg){
    global $queryString;
    global $Categoria;
    parse_str($queryString, $id);
    

    if($id && isset($id['id'])){
            
        echo $Categoria->FindId($id['id']);
        return;
    }
    echo $Categoria->FindAll();
    
});



Request::Request("PUT", function($msg){
    global $queryString;
    global $Categoria;

    parse_str($queryString, $id);
    MiddleWareLoginCategoria();
    
    echo $Categoria->Update($msg,$id['id']);
    
    
});

Request::Request("DELETE", function($msg){
    global $Categoria;
    global $queryString;
    parse_str($queryString, $id);
    MiddleWareLoginCategoria();
    
    echo $Categoria->Delete($id['id']);
});


function MiddleWareLoginCategoria(){
    
    
    if(!isset($_SERVER['HTTP_TOKEN'])){
        return Erro('Envie o Token');
    }
    
    try{
        $user = JWT::decode($_SERVER['HTTP_TOKEN'], $_ENV['TOKEN'], array('HS256'));
        $userArray = (array) $user;

        if(!$userArray['master']){
            Erro('Você não pode fazer isso');
            exit;
        }
        
         
        
    }catch(Exception $e){
         Erro('Token inválido ou expirou, faça login novamente');
         exit;
    }
}
