<?

// Bitte unverändert lassen
define("CONF_MODULEPATH",dirname(__FILE__)."/../module");
define("CONF_PATHFPDF",dirname(__FILE__)."/../module/biz/fpdf");


include(dirname(__FILE__)."/db_accountdata.class.php");
include(dirname(__FILE__)."/db_mysql.class.php");
include(dirname(__FILE__)."/authenticate.class.php");
include(dirname(__FILE__)."/functions.php");
include(dirname(__FILE__)."/html.class.php");

// Provisorium
$_REQUEST 	= enable_magic_quotes($_REQUEST);
$_POST 		= enable_magic_quotes($_POST);
$_GET 		= enable_magic_quotes($_GET);
$_COOKIE 	= enable_magic_quotes($_COOKIE);
$_SESSION 	= enable_magic_quotes($_SESSION);

// Kundenmenü Login Feld
$sets = $db->query("select * from biz_settings");
$rowsets = $db->fetch_array($sets);

$_SESSION["kundenmenueloginuserfield"] = $rowsets["kundenmenueloginuserfield"];

// html Instanz für Tabellen & Co.
$html = new html;

// Authentifizierungs Objekt
$auth = new authenticate;

$auth->source[0]["table"]       = "adminaccounts";
$auth->source[0]["usr"]         = "userid";
$auth->source[0]["pwd"]         = "passwort";
$auth->source[0]["pwdcrypt"]     = true;
$auth->source[0]["mail"]        = "mailadresse";
$auth->source[0]["mods"]        = "modules";
$auth->source[0]["modstart"]    = "modulestart";
$auth->source[0]["loginform"]   = dirname(__FILE__)."/../login.php";
$auth->source[0]["permdenied"]  = dirname(__FILE__)."/../permdenied.php";

$auth->source[1]["table"]       = "biz_kunden";
$auth->source[1]["usr"]         = "kundenid";
$auth->source[1]["pwd"]         = "passwort";
$auth->source[1]["pwdcrypt"]    = true;
$auth->source[1]["mail"]        = "mail";
$auth->source[1]["mods"]        = "";
$auth->source[1]["moddef"]      = "kundenmenue,dns";
$auth->source[1]["modstart"]    = "";
$auth->source[1]["modstartdef"] = "kundenmenue";
$auth->source[1]["loginform"]   = dirname(__FILE__)."/../login.php";
$auth->source[1]["permdenied"]  = dirname(__FILE__)."/../permdenied.php";
  


$adminid = $_SESSION['adminid'];


// Ist eingeloggter User Superadmin ?
$mwc = false;
if($noauth!=true) {
    $auth->check_access();
    if(in_array("*", $_SESSION['modules'])) {
	$mwc = true;
    }
}

// Durchsuche modules/ nach Modulen und inkludiere Modul Konfiguration


if($noauth!=true) {
    $key = 0;
    $d = dir(CONF_MODULEPATH);
    while($entry=$d->read()) {
	if($entry!="." and $entry!="..") {	    
	    if($mwc==false) {
		if(in_array("$entry", $_SESSION['modules'])) {
		    include(CONF_MODULEPATH."/$entry/inc/config.inc.php");
		}
	    }
	    else {
		include(CONF_MODULEPATH."/$entry/inc/config.inc.php");
	    }
	}
    }
    $d->close();

}
else {
    $d = dir(CONF_MODULEPATH);
    while($entry=$d->read()) {
	if($entry!="." and $entry!="..") {
    	    include(CONF_MODULEPATH."/$entry/inc/config.inc.php");
	}
    }
    $d->close();

}


?>