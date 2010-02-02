<?@session_start(); $module = basename(dirname(__FILE__));
include("../../include/config.inc.php");
include("../../include/common.inc.php");
if(!is_numeric($_REQUEST["ticketid"]) or !isset($_REQUEST["filename"])  or !isset($_REQUEST["type"])) die("");
$myfile = basename(realpath($_REQUEST["filename"]));
if(file_exists("./tmp/".(int)$_REQUEST["ticketid"]."_".$myfile))
{
    header("Content-type: ".$_REQUEST["type"]);
    header("Content-Disposition: attachment; filename=\"".$myfile."\"");
    readfile("./tmp/".(int)$_REQUEST["ticketid"]."_".$myfile);
}
?>