<?@session_start();
include("../../include/config.inc.php");
include("../../include/common.inc.php");
//include("./inc/class.dta.php");
include("./inc/DTA.php");

$mystring = file_get_contents($biz_temppath."/dtaus.php");

eval($mystring);
?>