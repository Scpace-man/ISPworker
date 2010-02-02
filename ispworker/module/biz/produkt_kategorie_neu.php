<?
$module = basename(dirname(__FILE__));
include("../../header.php");
?>

<?
if(isset($_REQUEST['new'])) {
  $db->query("insert into biz_produktkategorien (adminid,bezeichnung,kommentar,sichtbar)
              values ('$_SESSION[adminid]','".mysql_escape_string($_REQUEST[bezeichnung])."','".mysql_escape_string($_REQUEST[kommentar])."','$_REQUEST[sichtbar]')");

	message("Die Produktkategorie wurde gespeichert.");
	echo "<a href=\"module/biz/produkte.php\">Zurück</a><br><br><br>";

}
?>

<span class="htitle">Produkte</span><br>
<br>


<form action="module/biz/produkt_kategorie_neu.php?new=true" method="post">
<table width="540" border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
  <td colspan="2"><b>Neue Produktkategorie</b></td>
</tr>
<tr class="tr">
  <td>Bezeichnung</td>
  <td><input type="text" name="bezeichnung"></td>
</tr>
<tr class="tr">
  <td>Kommentar</td>
  <td><input type="text" name="kommentar"></td>
</tr>
<tr class="tr">
<td>Sichtbar für Kunden?</td>
<td>
  <select name="sichtbar">
  <option value="1">Ja</option>
  <option value="0">Nein</option>
  </select>
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
</form>








<?include("../../footer.php");?>