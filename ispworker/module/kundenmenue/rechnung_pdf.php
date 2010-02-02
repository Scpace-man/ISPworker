<?@session_start();

/* old, "on-the-fly" version

include("../../include/config.inc.php");
include("../../include/common.inc.php");
include("../biz/inc/config.inc.php");
include("../biz/inc/pdf.inc.php");

$res = $db->query("select kundenid from biz_rechnungen where rechnungid='$_REQUEST[rechnungid]' and kundenid='$_SESSION[user]'");
if($db->num_rows($res)==0) {
	die();
}

$directoutput=true;
pdfinvoice($_REQUEST[rechnungid]);

*/


include("../../include/config.inc.php");
include("../../include/common.inc.php");
include("../biz/inc/config.inc.php");

$res = $db->query("select kundenid from biz_rechnungen where rechnungid='$_REQUEST[rechnungid]' and kundenid='$_SESSION[user]'");
if($db->num_rows($res)==0) die();


$mypath = $biz_temppath;
$myfilename = "r-".$_REQUEST['rechnungid'].".pdf";


header("HTTP/1.1 200 OK");
header("Status: 200 OK");
header("Accept-Ranges: bytes");
header("Content-Transfer-Encoding: Binary");
header("Content-Type: application/force-download");
header("Content-Disposition: inline; filename=\"".$myfilename."\"");

#header("Content-Type: application/octet-stream");
#header("Content-Disposition: inline; filename=\"".$myfilename."\"");

readfile($mypath."/".$myfilename);
?>
