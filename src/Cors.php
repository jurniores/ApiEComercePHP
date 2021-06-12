<?php
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
        header('Access-Control-Allow-Headers: token, Content-Type, X-Requested-With');
        header("HTTP/1.1 200 OK");

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            die();
        }

use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load('../../.env');

/*
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Max-Age: 86400'); 
 
header('Access-Control-Allow-Methods: POST, GET'); 
header('Access-Control-Allow-Headers: *');
header('Content-Type: application/json');*/
