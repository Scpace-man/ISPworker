<?
$module = basename(dirname(__FILE__));
include("../../header.php");



if($_REQUEST[update]=="true" && $_REQUEST[statusid]!="") {
    $db->query("update order_statusbestell set statusimg='$_REQUEST[statusimg]', status='$_REQUEST[status]' where statusid='$_REQUEST[statusid]'");
}

if($_REQUEST[update]=="true" && $_REQUEST[statusid]=="") {
    $db->query("insert into order_statusbestell (statusimg,status) values ('$_REQUEST[statusimg]','$_REQUEST[status]')");
}

if($_REQUEST[del]=="true") {
    $db->query("delete from order_statusbestell where statusid='$_REQUEST[statusid]'");
}


?>

<span class="htitle">Status Attribute von Bestellungen</span><br>
<br>



<table width="540" border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
  <td><b>Status IMG</b></td>
  <td><b>Status</b></td>
  <td colspan="2"><b>Aktion</b></td>
</tr>
<?
$res = $db->query("select * from order_statusbestell order by status");
while($row=$db->fetch_array($res)) {

?>
<tr class="tr">
  <td width="70"><?if($row[statusimg]!="") { echo "<img src=\"$row[statusimg]\" border=0>"; }?></td>
  <td><?=$row[status]?></td>
  <td width="16"><a href="module/order/e_statusbestell.php?edit=true&statusid=<?=$row[statusid]?>"><img src="img/edit.gif" border="0" alt="Bearbeiten"></a></td>
  <td width="16"><a href="module/order/e_statusbestell.php?del=true&statusid=<?=$row[statusid]?>"><img src="img/trash.gif" border="0" alt="Löschen"></a></td>
</tr>
<?
}
?>
</table>


</td>
</tr>
</table>

<br>


<?
$row = "";
if($_REQUEST[edit] == "true") {
    $res = $db->query("select * from order_statusbestell where statusid='$_REQUEST[statusid]'");
    $row = $db->fetch_array($res);
}

?>

<form action="module/order/e_statusbestell.php?statusid=<?=$row[statusid]?>&update=true" method="post">
<table width="540" border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>
<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
  <td colspan="2"><b>Neuer Status / Status editieren</b></td>
</tr>
<tr class="tr">
  <td>Status Image (URL):</td>
  <td><input type="text" name="statusimg" value="<?=$row[statusimg]?>"> 
  <? if($row[statusimg]!="") { echo "<img src=\"$row[statusimg]\" border=0>"; }?></td>
</tr>
<tr class="tr">
  <td>Status</td>
  <td><input type="text" name="status" value="<?=$row[status]?>"></td>
</tr>

<tr class="tr">
  <td colspan="2"><input type="submit" value="Speichern"></td>
</tr>

</table>

</td>
</tr>
</table>
</form>

<br>


<?include("../../footer.php");?>