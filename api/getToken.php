<?php
require_once './DB.php';
require_once './vendor/autoload.php';
require_once './key.php';
header('Content-Type: application/json');

use Firebase\JWT\JWT;

$login = $_GET['login'];
$password = $_GET['password'];

if(!empty($login) && !empty($password)) //если параметры запроса не пустые
{
    $login = strtolower($login);

    $mysqli = getMySqli();
        if ($mysqli !== null) {
            $stmt = $mysqli->prepare('SELECT username, password FROM users WHERE username = ?');
            $stmt->bind_param('s', $login);
            $stmt->execute();
            $stmt->bind_result($usernameFromDB, $passwordFromDB);
            if($stmt->fetch()) {
                if($login === $usernameFromDB && isValidPassword($password, $passwordFromDB)) { //Если имя пользователя и пароль верные, то получаем роль пользователя
                    $mysqli = getMySqli();
                    if ($mysqli !== null) {
                        $stmt = $mysqli->prepare('SELECT primary_group FROM luckperms_players WHERE username = ?'); //получаю роль
                        $stmt->bind_param('s', $login);
                        $stmt->execute();
                        $stmt->bind_result($PrimaryGroup);
                        if($stmt->fetch()) { //если всё ок, формирую и отдаю токен
                            $payload = [
                                'login' => $login,
                                'primaryGroup' => $PrimaryGroup,
                            ];
                            $jwt = JWT::encode($payload, $key, 'HS256');
                            http_response_code(200);
                            print_r(json_encode(array(
                                'message' => 'entry is possible',
                                'token' => $jwt
                            )));
                        }
                    }
                    else {
                        http_response_code(403);
                        print_r(json_encode(array(
                            'message' => 'entry is not possible'
                        )));
                    }
                } 
                else {
                    http_response_code(403);
                    print_r(json_encode(array(
                        'message' => 'entry is not possible'
                    )));
                }
            }
            else {
                http_response_code(403);
                print_r(json_encode(array(
                    'message' => 'entry is not possible'
                )));
            }
        }
}
else {
    http_response_code(400);
    print_r(json_encode(array(
        'message' => 'it cant be empty'
    )));
}

function isValidPassword($password, $hash) {
    // $SHA$salt$hash, where hash := sha256(sha256(password) . salt)
    $parts = explode('$', $hash);
    return count($parts) === 4 && $parts[3] === hash('sha256', hash('sha256', $password) . $parts[2]); //Кастыль какой-то, я без понятия как это работает
}