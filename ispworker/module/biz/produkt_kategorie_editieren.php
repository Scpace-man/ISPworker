<?
$module = basename(dirname(__FILE__));
include("../../header.php");


if(isset($_REQUEST[update])) {
    $db->query("update biz_produktkategorien set bezeichnung='".mysql_escape_string($_REQUEST[bezeichnung])."',kommentar='".mysql_escape_string($_REQUEST[kommentar])."',sichtbar='".mysql_escape_string($_REQUEST[sichtbar])."' where adminid='$_SESSION[adminid]' and katid='$_REQUEST[katid]'");
    message("&Auml;nderungen sind gespeichert.");
}


$res = $db->query("select katid,bezeichnung,kommentar,sichtbar from biz_produktkategorien where adminid='$_SESSION[adminid]' and katid='$_REQUEST[katid]'");
$row = $db->fetch_array($res);



?>

<span class="htitle">Produkte</span><br>
<br>


<form action="module/biz/produkt_kategorie_editieren.php?update=true&katid=<?=$_REQUEST[katid]?>" method="post">
<table width="540" border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
  <td colspan="2"><b>Produktkategorie bearbeiten</b></td>
</tr>
<tr class="tr">
  <td>Bezeichnung</td>
  <td><input type="text" name="bezeichnung" value="<?=stripslashes($row[bezeichnung])?>"></td>
</tr>
<tr class="tr">
  <td>Kommentar</td>
  <td><input type="text" name="kommentar" value="<?=stripslashes($row[kommentar])?>"></td>
</tr>
<tr class="tr">
<td>Sichtbar für Kunden?</td>
<td>
<?
  if($row[sichtbar]=="0")         { $s1 = "checked"; }
  if($row[sichtbar]=="1")        { $s2 = "checked"; }

?>
  <input type="radio" name="sichtbar" value="1" <?=$s2?>>JA <input type="radio" name="sichtbar" value="0" <?=$s1?>> NEIN
  </td>
</tr>
<tr class="tr">
  <td>&nbsp;</td>
  <td><input type="submit" value="Speichern"></td>
</tr>
</table>


</td>
</tr>
</table>

</td>
</tr>
</table>

</td>
</tr>
</table>

</form>








<?include("../../footer.php");?>