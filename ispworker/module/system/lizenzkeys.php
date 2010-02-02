<?$module = basename(dirname(__FILE__));
include("../../header.php");?>

<h3>Lizenz Keys</h3>
<br>

<?if($_REQUEST[update]=="true") {
    $db->query("update _licence set serial='$_REQUEST[serial]',licencekey='$_REQUEST[licencekey]' where module='$_REQUEST[p]'");


    switch($_REQUEST[p]) {
	case "biz":
	    file(CONF_BASEHREF."module/biz/cronwork.php");
	break;

	case "ticket":
	    file(CONF_BASEHREF."module/ticket/cronwork.php");
	break;

	case "game":
	    file(CONF_BASEHREF."module/gameadmin/check.php");
	break;
    }

}
?>


<table width="540" border="0" cellspacing="0" cellpadding="0" align="center">
<tr bgcolor="#cccccc">
<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr bgcolor="#e7e7e7">
  <td><b>Modul</b></td>
  <td><b>Seriennummer</b></td>
  <td><b>Status</b></td>
</tr>
<?
$res = $db->query("select * from _licence");
while($row = $db->fetch_array($res)) {
?>
<tr>
  <td bgcolor="#ffffff"><a href="module/system/lizenzkeys.php?p=<?=$row[module]?>"><?=$row[module]?></a></td>
  <td bgcolor="#ffffff"><?=$row[serial]?></td>
  <td bgcolor="#ffffff"><?=$row[status]?></td>
</tr>
<?}?>

</table>

</td>
</tr>
</table>


<br>


<?

if($_REQUEST[p]=="") { echo "<br><br>"; include("../../footer.php"); die(); }

$res = $db->query("select * from _licence where module='$_REQUEST[p]'");
$row = $db->fetch_array($res);
?>

<form action="module/system/lizenzkeys.php?p=<?=$_REQUEST[p]?>&update=true" method="post">
<table width="540" border="0" cellspacing="0" cellpadding="0" align="center">
<tr bgcolor="#cccccc">
<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr bgcolor="#e7e7e7">
  <td colspan="2"><b>Lizenz Key Informationen</b></td>
</tr>
<tr>
  <td width="200" bgcolor="#ffffff">Modul</td>
  <td bgcolor="#ffffff"><?=$row[module]?></td>
</tr>
<tr>
  <td width="200" bgcolor="#ffffff">Seriennummer</td>
  <td bgcolor="#ffffff"><input type="text" name="serial" size="60" value="<?=$row[serial]?>"></td>
</tr>
<tr>
  <td valign="top" width="200" bgcolor="#ffffff">Lizenz Key</td>
  <td bgcolor="#ffffff"><textarea name="licencekey" rows="5" cols="50"><?=$row[licencekey]?></textarea></td>
</tr>
<tr>
  <td valign="top" width="200" bgcolor="#ffffff"><img src="img/pixel.gif"></td>
  <td bgcolor="#ffffff"><input type="submit" value="Speichern"></td>
</tr>
</table>
</td>
</tr>
</table>
</form>






<br>


<?include("../../footer.php");?>
