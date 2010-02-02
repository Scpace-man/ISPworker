<?
$module = basename(dirname(__FILE__));
include("../../header.php");


if(isset($_REQUEST[update])) {
  $db->query("update faq_kategorien set name='$_REQUEST[name]',beschreibung='$_REQUEST[beschreibung]' where id='$_REQUEST[id]'");
  echo "<center><b>&Auml;nderungen wurden gespeichert.</b></center><br><br>\n";
}


$res = $db->query("select id,name,beschreibung from faq_kategorien where id='$_REQUEST[id]'");
$row = $db->fetch_array($res);
?>


<span class="htitle">Kategorie bearbeiten</span><br>
<br>


<form action="module/faq/kat_editieren.php?update=true&id=<?=$_REQUEST[id]?>" method="post">
<table width="540" border="0" cellspacing="0" cellpadding="0">
<tr bgcolor="#cccccc" align="left" valign="top">
<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr bgcolor="#e7e7e7" align="left" valign="top">
  <td colspan="2"><b>Kategorie editieren</b></td>
</tr>
<tr>
  <td bgcolor="#ffffff">Bezeichnung</td>
  <td bgcolor="#ffffff"><input type="text" name="name" value="<?=$row[name]?>"></td>
</tr>
<tr>
  <td bgcolor="#ffffff">Beschreibung</td>
  <td bgcolor="#ffffff"><textarea cols="80" rows="4" name="beschreibung"><?=$row[beschreibung]?></textarea></td>
</tr>
<tr>
  <td bgcolor="#ffffff">&nbsp;</td>
  <td bgcolor="#ffffff"><input type="submit" value="&Auml;nderungen speichern"></td>
</tr>
</table>


</td>
</tr>
</table>
</form>








<?include("../../footer.php");?>