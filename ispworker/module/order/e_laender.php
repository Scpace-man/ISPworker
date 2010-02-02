<?
$module = basename(dirname(__FILE__));
include("../../header.php");



if($_REQUEST[update]=="true" & $l_REQUEST[aenderid]!="") {
    $db->query("update order_laender set name='$_REQUEST[land]', isocode='$_REQUEST[isocode]' where laenderid='$_REQUEST[laenderid]'");
}

if($_REQUEST[update]=="true" & $_REQUEST[laenderid]=="") {
    $db->query("insert into order_laender (name,isocode) values ('$_REQUEST[land]','$_REQUEST[isocode]')");
}

if($_REQUEST[del]=="true") {
    $db->query("delete from order_laender where laenderid='$_REQUEST[laenderid]'");
}


?>

<span class="htitle">Länder</span><br>
<br>


<table width="540" border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
  <td><b>Ausgabe aller Länder</b></td>
  <td colspan="2"><b>Aktion</b></td>
</tr>
<?
$res = $db->query("select * from order_laender order by name");
while($row=$db->fetch_array($res)) {

?>
<tr class="tr">
  <td><?=$row[name]?></td>
  <td width="16"><a href="module/order/e_laender.php?edit=true&laenderid=<?=$row[laenderid]?>"><img src="img/edit.gif" border="0" alt="Bearbeiten"></a></td>
  <td width="16"><a href="module/order/e_laender.php?del=true&laenderid=<?=$row[laenderid]?>"><img src="img/trash.gif" border="0" alt="Löschen"></a></td>
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
if($_REQUEST[edit] == "true") {
    $res = $db->query("select * from order_laender where laenderid='$_REQUEST[laenderid]'");
    $row = $db->fetch_array($res);
}

?>

<form action="module/order/e_laender.php?laenderid=<?=$row[laenderid]?>&update=true" method="post">
<table width="540" border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>
<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
  <td colspan="2"><b>Neues Land / Land editieren</b></td>
</tr>
<tr class="tr">
  <td>Land:</td>
  <td><input type="text" name="land" value="<?=$row[name]?>"></td>
</tr>
<tr class="tr">
  <td>Ländercode:</td>
  <td><input type="text" name="isocode" value="<?=$row[isocode]?>" size="2" maxlength="3"></td>
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