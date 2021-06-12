<?php

namespace src\controllers;


class Request {

    public static function Request($request, $cb){
        $dados = file_get_contents('php://input');
    
        $method = $_SERVER['REQUEST_METHOD'];
    
        $arrayRc = json_decode($dados, true);

            if($method===$request){
                $cb($arrayRc);
            }
        
    }
}