<?@session_start();

include("../../include/config.inc.php");
include("../../include/common.inc.php");

$res = $db->query("select docfilename from biz_docs where docid='".$_REQUEST["docid"]."' ");
$row = $db->fetch_array($res);

header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"".$row["docfilename"]."\"");
readfile($biz_docpath."/".$row["docfilename"]);
?>