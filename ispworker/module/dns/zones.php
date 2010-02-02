<?
$module = basename(dirname(__FILE__));
include("../../header.php");
include("./inc/functions.inc.php");
?>

<span class="htitle">Domainzonen</span><br>
<br>


<?

$t = $html->table(0);
$t->addcol("Zone",500);
$t->addcol("",16);
$t->cols();

$res = $db->query("select * from biz_domains where kundenid='$_SESSION[user]'");

while($row=$db->fetch_array($res)) 
{
    $t->addrow($row[domainname]);
    $t->addrow("<a href=\"module/dns/zone_edit.php?domainname=$row[domainname]\"><img src=\"img/edit.gif\" border=\"0\" alt=\"Bearbeiten\"></a>");
    $t->rows();
}
$t->close();
?>

<br>
<br>

<?include("../../footer.php");?>
