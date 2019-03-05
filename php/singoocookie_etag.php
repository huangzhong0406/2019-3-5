<?php
/* evercookie, by samy kamkar, 09/20/2010
 *  http://samy.pl : code@samy.pl
 *
 * This is the server-side ETag software which tags a user by
 * using the Etag HTTP header, as well as If-None-Match to check
 * if the user has been tagged before.
 *
 * -samy kamkar
 */

// we get cookie name from current file name so remember about it when rename of this file will be required
include dirname(__FILE__) . DIRECTORY_SEPARATOR . '_cookie_name.php';
$cookie_name = evercookie_get_cookie_name(__FILE__);

//if(empty($SINGOO_COOKIE[$cookie_name]) && empty($_COOKIE[$cookie_name])){
////    header('HTTP/1.1 304 Not Modified');
//    exit;
//}else{
//
//    if( !empty($_COOKIE[$cookie_name]) ){
//        echo 'cookie';
//        echo $_COOKIE[$cookie_name];
//        exit;
//    }
//    setcookie($cookie_name,$SINGOO_COOKIE[$cookie_name],time()+3600,'/',$_SERVER['HTTP_ORIGIN']);
//    print_r($_COOKIE);
//    print_r($_SESSION);
//    echo 'cookie22';
//    echo $SINGOO_COOKIE[$cookie_name];
//    exit;
//}




// we don't have a cookie, so we're not setting it
if(empty($SINGOO_COOKIE[$cookie_name])){
    // read our etag and pass back
    if (!function_exists('apache_request_headers')) {
        function apache_request_headers() {
            // Source: http://www.php.net/manual/en/function.apache-request-headers.php#70810
            $arh = array();
            $rx_http = '/\AHTTP_/';
            foreach ($_SERVER as $key => $val) {
                if (preg_match($rx_http, $key)) {
                    $arh_key = preg_replace($rx_http, '', $key);
                    $rx_matches = array();
                    // do some nasty string manipulations to restore the original letter case
                    // this should work in most cases
                    $rx_matches = explode('_', $arh_key);
                    if (count($rx_matches) > 0 and strlen($arh_key) > 2) {
                        foreach ($rx_matches as $ak_key => $ak_val) {
                            $rx_matches[$ak_key] = ucfirst(strtolower($ak_val));
                        }
                        $arh_key = implode('-', $rx_matches);
                    }
                    $arh[$arh_key] = $val;
                }
            }
            return ($arh);
        }
    }
    
    // Headers might have different letter case depending on the web server.
    // So, change all headers to uppercase and compare it.
    $headers = array_change_key_case(apache_request_headers(), CASE_UPPER);
    if(isset($headers['IF-NONE-MATCH'])) {
        // extracting value from ETag presented format (which may be prepended by Weak validator modifier)
        $etag_value = preg_replace('|^(W/)?"(.+)"$|', '$2', $headers['IF-NONE-MATCH']);
        header('HTTP/1.1 304 Not Modified');
        header('ETag: "' . $etag_value . '"');
        echo $etag_value;
    }
    exit;
}

// set our etag
header('ETag: "' . $SINGOO_COOKIE[$cookie_name] . '"');
header('Content-Type: text/html');
header("Last-Modified:".gmdate("D, d M Y H:i:s")." GMT");
header("Expires:".gmdate("D, d M Y H:i:s",time()+10*365*24*3600)." GMT");
header('Cache-Control: private, max-age='.(time()+10*365*24*3600));
echo $SINGOO_COOKIE[$cookie_name];
