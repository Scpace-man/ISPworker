<?
include(dirname(__FILE__)."/include/config.inc.php");
include(dirname(__FILE__)."/include/common.inc.php");

$res = $db->query("select doc from order_docs where docid='$_REQUEST[docid]'");
$row = $db->fetch_array($res);

echo stripslashes($row[doc]);
?>