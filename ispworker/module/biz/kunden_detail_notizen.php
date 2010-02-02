<?
$module = basename(dirname(__FILE__));
include("./inc/functions.inc.php");
include("../../header.php");
include("./inc/reiter1.layout.php");

$bgcolor[0]   = "#f0f0f0";
$linecolor[0] = "#000000";

$bgcolor[7]   = "#ffffff";
$linecolor[7] = "#ffffff";

include("./inc/reiter1.php");

if($_REQUEST[update]=="true") {
    $db->query("update biz_kunden set bemerkung='$_REQUEST[bemerkung]' where kundenid='$_REQUEST[kundenid]'");
	message("Notizen erfolgreich gespeichert!");
}


$res = $db->query("select bemerkung from biz_kunden where kundenid='$_REQUEST[kundenid]'");
$row = $db->fetch_array($res);

?>
<form action="module/biz/kunden_detail_notizen.php?kundenid=<?=$_REQUEST[kundenid]?>&update=true" method="post">
<table width="540" border="0" cellspacing="0" cellpadding="0">
<tr bgcolor="#cccccc">
<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr bgcolor="#e7e7e7">
  <td><b>Notiz</b></td>
</tr>
<tr>
  <td bgcolor="#ffffff"><textarea name="bemerkung" cols="97" rows="10"><?=$row[bemerkung]?></textarea></td>
</tr>
<tr>
  <td bgcolor="#ffffff"><input type="submit" value="Speichern"></td>
</tr>
</table>

</td>
</tr>
</table>
</form>
<br>

<br>

<br>
<br>
<br>

<?include("../../footer.php");?>
