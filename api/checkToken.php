<?php
require_once './vendor/autoload.php';
require_once './key.php';
header('Content-Type: application/json');

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$token = $_GET['token'];

if(!empty($token)) //если параметры запроса не пустые
{
    try {
        $decoded = JWT::decode($token, new Key($key, 'HS256'));
        http_response_code(200);
        print_r(json_encode(array(
            'message' => 'the token is valid'
        )));
    }
    catch (Exception $e)
    {
        http_response_code(400);
        print_r(json_encode(array(
            'message' => 'the token has expired'
        )));
    }
}
else {
    http_response_code(400);
    print_r(json_encode(array(
        'message' => 'it cant be empty'
    )));
}