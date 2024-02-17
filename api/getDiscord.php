<?php
require_once './DB.php';
require_once './vendor/autoload.php';
header('Content-Type: application/json');

use DiDom\Document;

$username = $_GET['username'];

if(!empty($username)) //если параметры запроса не пустые
{
    $username = strtolower($username);
    $mysqli = getMySqli();
    if ($mysqli !== null) 
    {
        $stmt = $mysqli->prepare('SELECT discord FROM discordsrv_accounts WHERE uuid = (SELECT uuid FROM luckperms_players WHERE username = ?)');
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->bind_result($DiscordID);
        if($stmt->fetch()) {
            $document = new Document('https://lookup.guru/' . $DiscordID, true);
            $img = $document->xpath('//*[@id="__layout"]/div/div[1]/div[2]/div[1]/a/img')[0] -> src;
            if(!empty($img))
            {
                http_response_code(200);
                print_r(json_encode(array(
                    'id' => $DiscordID,
                    'avatar' => $img,
                )));
            }
            else 
            {
                http_response_code(200);
                print_r(json_encode(array(
                    'id' => $DiscordID,
                    'avatar' => 'null',
                )));
            }
        }
        else 
        {
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