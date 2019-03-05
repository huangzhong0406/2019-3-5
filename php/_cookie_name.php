<?php

/**
 * Gets evercookie's cookie name for PHP's scripts to get value froms
 * 
 * @param string $file_name Usually it's a file name like 'evercookie_blabla.php'
 * @return string evercookie_blabla
 */

header('Access-Control-Allow-Origin: '. $_SERVER['HTTP_ORIGIN']);

header('Access-Control-Allow-Methods:*');

//header("Access-Control-Allow-Credentials: true");

header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Singoo_cookie");


if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS'){
    exit;
}

$header = apache_request_headers();

//取head标签内容
$SINGOO_COOKIE = json_decode($header['Singoo_cookie'],true);

function evercookie_get_cookie_name($file_name) {
    if (!empty($_GET['cookie'])) {
        return $_GET['cookie'];
    }
    return basename($file_name, '.php');
}
