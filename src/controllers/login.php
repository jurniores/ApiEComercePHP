<?php

require '../../vendor/autoload.php';
require '../Cors.php';
require '../Config/PDO.php';
require '../Config/Mysql.php';
require '../Models/UserModel.php';


use Firebase\JWT\JWT;
use src\controllers\Request;

function Erro($msg){
    header('HTTP/1.0 404 Not Found', true, 404);
    echo json_encode(['Error'=>$msg]);
}
 //senha do site hostinger LeoMidia@123

 $User = new UserModel($coon, 'user');

 Request::Request('POST',function($msg){
    global $User;

    if(!isset($msg['email']) || !isset($msg['senha'])){
        
        return Erro("Se atente aos campos email e senha");
    }
    $email = filter_var($msg['email'], FILTER_VALIDATE_EMAIL);

            if(!$email){
                Erro("E-mail invalido!");
                exit;
            } 
        

    $user = $User->FindOne('email',$msg['email']);
    $userDecode = json_decode($user,true);

    if(count($userDecode)>1){

       $senha = JWT::decode($userDecode['senha'], $_ENV['TOKEN'], array('HS256'));
       if($senha == $msg['senha']){
           
           $tokenCliente = JWT::encode([

               'email'=>$userDecode['email'],
               'nome'=>$userDecode['nome'],
               'id'=>$userDecode['id'],
               'master'=>$userDecode['master']

            ],$_ENV['TOKEN']);


           echo json_encode(['success' =>$tokenCliente]);
           return;
       }
       return Erro('senha incorreta');
       
    }
    return Erro('Este usuário não existe');


 });