<?php 

$alphabet = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

function generate_string($input, $strength = 16) {
    $input_length = strlen($input);
    $random_string = '';
    for($i = 0; $i < $strength; $i++) {
        $random_character = $input[mt_rand(0, $input_length - 1)];
        $random_string .= $random_character;
    }
    return $random_string;
}

$hash = hash('sha256', generate_string($alphabet, 256));

$fd = fopen("./key.php", 'w') or die("не удалось создать файл");
fwrite($fd, "<?php \$key = '" . $hash . "';");
fclose($fd);