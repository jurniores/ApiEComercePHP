<?php

require '../../vendor/autoload.php';
require '../Cors.php';
require '../Config/Mysql.php';
require '../Config/PDO.php';


require '../Models/VendasModel.php';
require '../Models/FotosModel.php';
require '../Utils/UploadFotos.php';

use src\controllers\Request;
use Firebase\JWT\JWT;

 $queryString = filter_input(INPUT_SERVER, 'QUERY_STRING');

 $vendas = new VendasModel($coon, 'produtos');
 $Fotos = new FotosModel($coon,'fotos');

 function Erro($msg){
    header('HTTP/1.0 404 Not Found', true, 404);
    echo json_encode(['Error'=>$msg]);
    exit;
}

Request::Request("POST", function($msg){

    
    global $vendas;
    global $Fotos;
    
    $img = Upload::Imagem();
    
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
        
        //if(isset($msg['nome']) && isset($msg['tipo']) && isset($msg['descricao'])&& isset($msg['desconto'])&& isset($msg['valor'])&& isset($msg['destaque']) && isset($msg['categoria'])){
            //$id = $vendas->Insert($msg['nome'],$msg['tipo'],$msg['descricao'],floatval($msg['desconto']),floatval($msg['valor']), $msg['destaque'], intval($userArray['id']) , $msg['categoria']);
            $id = $vendas->Insert('Roupa','Camiseta','Sem Desc',10,120, false, intval($userArray['id']) , 'camisas');
            $Fotos->Insert($img,$id);


            return;
        //}
        return Erro('Algum campo está faltando');

    }catch(Exception $e){
        return Erro('Token inválido ou expirado!');
    }

    
});

Request::Request("GET", function($msg){
    global $queryString;
    global $vendas;
    global $Fotos;
    parse_str($queryString, $id);
    if($id && isset($id['id'])){
        echo json_encode(["vendas"=>$vendas->FindId($id['id']), "fotos"=>$Fotos->FindOne('id_venda',$id['id'])]);
        return;
    }
    echo json_encode(["vendas"=>$vendas->FindAll(), "fotos"=>$Fotos->FindAll()]);
    
});

Request::Request("PUT", function($msg){
    global $vendas;
    global $queryString;
    parse_str($queryString, $id);
    MiddleWareLoginVendas($id);

    echo $vendas->Update($msg,$id['id']);
    
});

Request::Request("DELETE", function($msg){
    global $vendas, $queryString;
    parse_str($queryString, $id);
    
    MiddleWareLoginVendas($id);

    Upload::DeleteFile($id);
    echo $vendas->Delete($id['id']);
    

    
});


function MiddleWareLoginVendas($id){
    global $vendas;
    
    if(!isset($_SERVER['HTTP_TOKEN'])){
        Erro('Envie o Token');
        exit;
    }
    
    try{
        $user = JWT::decode($_SERVER['HTTP_TOKEN'], $_ENV['TOKEN'], array('HS256'));
        $userArray = (array) $user;

    
    
   
        if(isset($id) && isset($id['id'])){
            $user = $vendas->FindId($id['id']);
            $useTable = json_decode($user,true);
            
            if(!is_array($useTable) || isset($useTable['Error'])){
                echo $user;
                exit;
            }
            if($useTable['id_user'] == $userArray['id'] && $userArray['master']){
                 
                 return;
            }

             Erro('Você não pode fazer este tipo de acesso!');
             
       } 
   
    
     Erro('Campo (id) não existe');
     exit;
        
        
    }catch(Exception $e){
         Erro('Token inválido ou expirou, faça login novamente');
         exit;
    }
}




