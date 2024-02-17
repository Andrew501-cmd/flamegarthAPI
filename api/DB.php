<?php

function getMySqli() {
    // CHANGE YOUR DATABASE DETAILS HERE BELOW: host, user, password, database name
    $mysqli = new mysqli('localhost', 'root', 'root', 'flamegarth');
    if (mysqli_connect_error()) {
        http_response_code(500);
        printf('Could not connect to database. Errno: %d, error: "%s"',
            mysqli_connect_errno(), mysqli_connect_error());
        return null;
    }
    return $mysqli;
}