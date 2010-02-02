<?

$modulename["kundenmenue"] = "Kundenmenü";


$res = $db->query("select * from biz_settings");
$row = $db->fetch_array($res);

define("CONF_ALLOWCHANGEPWD",$row[kundenmenueallowchangepwd]);
define("CONF_MAILFROM",$row[kundenmenuemailfrom]);

define("CONF_BASEORDERMENU", "http://demo.ispware33333.de/ispworker/bestellen/");
define("CONF_BASEPAYPALURL", "https://www.paypal.com/cgi-bin/webscr");

?>