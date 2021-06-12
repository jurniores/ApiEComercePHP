<?php

$coon;

if(isset($coon)){
    exit;
}else{
    
    try{
        $options = array(
        
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
        );
        $coon = new PDO('mysql:host=localhost;dbname=ecomerce','root','', $options);
    }catch(PDOException $e){
        echo json_encode(['Error'=>'Não conseguimos encontrar o banco de dados']);
    }
}

