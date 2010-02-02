<?

include("../../include/config.inc.php");
include("../../include/db_accountdata.class.php");
include("../../include/db_mysql.class.php");
include("../../module/order/inc/order.class.php");
include("../../module/order/inc/functions.inc.php");

$res = $db->query("select doc from order_docs where docid='$_REQUEST[docid]'");
$row = $db->fetch_array($res);

echo $row[doc];
?>