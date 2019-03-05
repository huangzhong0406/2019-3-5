<?php
/* evercookie, by samy kamkar, 09/20/2010
 *  http://samy.pl : code@samy.pl
 *
 * This is the server-side simple caching mechanism.
 *
 * -samy kamkar
 */
# header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
// we get cookie name from current file name so remember about it when rename of this file will be required 


include dirname(__FILE__) . DIRECTORY_SEPARATOR . '_cookie_name.php';
$cookie_name = evercookie_get_cookie_name(__FILE__);


// we don't have a cookie, user probably deleted it, force cache
if (empty($SINGOO_COOKIE[$cookie_name])) {
//    header('HTTP/1.1 304 Not Modified');
    exit;
}

if(!headers_sent()) {
    header('Content-Type: text/html');
    header("Last-Modified:".gmdate("D, d M Y H:i:s")." GMT");
    header("Expires:".gmdate("D, d M Y H:i:s",time()+10*365*24*3600)." GMT");
    header('Cache-Control: private, max-age='.(time()+10*365*24*3600));
}
echo $SINGOO_COOKIE[$cookie_name];
