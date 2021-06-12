<?php

require '../../vendor/autoload.php';
require '../Cors.php';
require '../Config/PDO.php';
require '../Config/Mysql.php';
require '../Models/UserModel.php';


use Firebase\JWT\JWT;
use src\controllers\Request;


 $queryString = filter_input(INPUT_SERVER, 'QUERY_STRING');

 $User = new UserModel($coon, 'user');

function Erro($msg){
    header('HTTP/1.0 404 Not Found', true, 404);
    echo json_encode(['Error'=>$msg]);
}


Request::Request("POST", function($msg){
        global $User;

        if(isset($msg['email'])){
            $email = filter_var($msg['email'], FILTER_VALIDATE_EMAIL);
            $emailLower = strtolower($msg['email']);
            if(!$email){
                Error("E-mail invalido!");
                exit;
            } 
        }else{
             Erro('Envie um E-mail!');
             exit;
        }
        
     
        if(isset($msg['nome']) && isset($msg['senha'])){
            $senhaJWT = JWT::encode($msg['senha'], $_ENV['TOKEN']);
            echo $User->Insert($msg['nome'],$emailLower,$senhaJWT, $msg['master']);
        return;
        }
        return Erro('Algum campo está faltando');


    
});



Request::Request("GET", function($msg){
    global $queryString;
    global $User;
    parse_str($queryString, $id);
    MiddleWareLogin($id);

    if($id && isset($id['id'])){
            
        echo $User->FindId($id['id']);
        return;
    }
    echo $User->FindAll();
    
});



Request::Request("PUT", function($msg){
    global $queryString;
    global $User;

    parse_str($queryString, $id);
    MiddleWareLogin($id);
    
    echo $User->Update($msg,$id['id']);
    
    
});

Request::Request("DELETE", function($msg){
    global $User;
    global $queryString;
    parse_str($queryString, $id);
    MiddleWareLogin($id);
    
    echo $User->Delete($id['id']);
});


function MiddleWareLogin($id){
    global $User;
    
    if(!isset($_SERVER['HTTP_TOKEN'])){
         Erro('Envie o Token');
         exit;
    }
    
    try{
        $user = JWT::decode($_SERVER['HTTP_TOKEN'], $_ENV['TOKEN'], array('HS256'));
        $userArray = (array) $user;

   
        if(isset($id) && isset($id['id'])){
            $user = $User->FindId($id['id']);
            $useTable = json_decode($user,true);
            
            if(!is_array($useTable)){
                echo $user;
                return;
            }
            
            if($useTable['email'] == $userArray['email'] || $userArray['master']){
                 
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
