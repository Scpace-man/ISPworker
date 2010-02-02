<?
		
include(CONF_PATH."include/config.inc.php");
include(CONF_PATH."include/db_accountdata.class.php");
include(CONF_PATH."include/db_mysql.class.php");
include(CONF_PATH."module/order/inc/order.class.php");
include(CONF_PATH."module/order/inc/functions.inc.php");
include(CONF_PATH."module/order/inc/idna_convert.class.php");
include(CONF_PATH."module/order/inc/request.class.php");
include(CONF_PATH."module/order/inc/whois_request.class.php");

$order = new order;

session_cache_limiter('private');
session_cache_limiter();

function http_post($server, $port, $url, $vars) 
{
    $user_agent = "Mozilla/4.0 (compatible; MSIE 5.5; Windows 98)";

    $urlencoded = "";
    while (list($key,$value) = each($vars))
    	$urlencoded.= urlencode($key) . "=" . urlencode($value) . "&";
    
    $urlencoded = substr($urlencoded,0,-1);	
    $content_length = strlen($urlencoded);
    $headers = "POST $url HTTP/1.1
Accept: */*
Accept-Language: en-au
Content-Type: application/x-www-form-urlencoded
User-Agent: $user_agent
Host: $server
Connection: Keep-Alive
Cache-Control: no-cache
Content-Length: $content_length

";
	
    $fp = fsockopen($server, $port, $errno, $errstr);
    if (!$fp) return false;

    fputs($fp, $headers);
    fputs($fp, $urlencoded);
	
    $ret = "";
    while (!feof($fp))
    	$ret.= fgets($fp, 1024);
    
    fclose($fp);
	
    return $ret;
}

function AktuellerWechselkurs($exch,$expr)
{
    $ret = http_post("www.oanda.com",	80,	"/convert/classic", array("exch" => "$exch", "expr" => "$expr", "value" => "1"));
    $start = strpos($ret, "<!-- conversion result starts  -->");
    $end = strpos ($ret, "<!-- conversion result ends  -->");
    $length=$end-$start;
	
    $currency= substr($ret, $start, $length); 
    $cvalue=explode("=",strip_tags($currency));
    $currency=trim($cvalue[1]);

    $currency1=explode(" ",$currency);
    return $currency1[0];
}

?>
