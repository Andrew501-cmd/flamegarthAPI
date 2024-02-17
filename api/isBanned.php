<?php
require_once './DB.php';
require_once './vendor/autoload.php';
header('Content-Type: application/json');

$username = $_GET['username'];

if(!empty($username)) //если параметры запроса не пустые
{
    $username = strtolower($username);
    $mysqli = getMySqli();
    if ($mysqli !== null) {
        $stmt = $mysqli->prepare("SELECT CASE WHEN COUNT(*) > 0 THEN 'True' ELSE 'False' END AS isBanned FROM Punishments WHERE uuid = ? OR uuid = (SELECT ip FROM users WHERE username = ?)");
        $stmt->bind_param('ss', $username, $username);
        $stmt->execute();
        $stmt->bind_result($isBanned);
        if($stmt->fetch()) {
            print_r(json_encode(array(
                'isBanned' => $isBanned
            )));
        }
        else {
            http_response_code(400);
            print_r(json_encode(array(
                'message' => 'error'
            )));
        }
    }
}
else
{
    http_response_code(400);
    print_r(json_encode(array(
        'message' => 'it cant be empty'
    )));
}