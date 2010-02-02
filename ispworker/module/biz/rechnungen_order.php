<?
$module = basename(dirname(__FILE__));
include("../../header.php");
?>

<i><b>Rechnungsauftrge</b></i><br>
<br>

<?
if(isset($_REQUEST[loeschen])) {
  echo "<a href=\"module/biz/rechnungen_order.php?loeschenja=true&rechnungauftragid=$_REQUEST[rechnungauftragid]\"><b>*Jetzt löschen*</b></a>";
}

if(isset($_REQUEST[loeschenja])) {
  $db->query("delete from biz_rechnungauftraege where adminid='$_SESSION[adminid]' and rechnungauftragid='$_REQUEST[rechnungauftragid]'");
}


?>


<table border="0" cellspacing="10" cellpadding="5">
<tr>
<td valign="top" width="540">

&raquo; <a href="module/biz/rechnungen_order_neu.php">Neuer Auftrag</a><br>
<br>
<form action="module/biz/produkte.php" method="post">
<select name="anzahl">
<option value="30">30 Datensätze pro Seite</option>
<option value="50">50 Datensätze pro Seite</option>
<option value="100">100 Datensätze pro Seite</option>
<option value="200">200 Datensätze pro Seite</option>
</select>
<input type="submit" value="Anzeigen">
</form>


<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr bgcolor="#cccccc">
<td>

<table width="540" border="0" cellspacing="1" cellpadding="3">
<tr bgcolor="#e7e7e7" align="left" valign="top">
<td><b>AuftragID</b></td>
<td><b>KundenID</b></td>
<td><b>Auftragsdatum</b></td>
<td><b>Erstellen</b></td>
<td colspan="2"><b>Aktion</b></td>

<?
if(!isset($_REQUEST[anzahl])) { $_REQUEST[anzahl] = 30; }
if(!isset($_REQUEST[start]))  { $_REQUEST[start]  = 0; }

$res = $db->query("select rechnungauftragid from biz_rechnungauftraege where adminid='$_SESSION[adminid]'");
$ds  = $db->num_rows($res);

$res = $db->query("select rechnungauftragid,kundenid,auftragsdatum,erstellen from biz_rechnungauftraege where adminid='$_SESSION[adminid]' order by rechnungauftragid limit $_REQUEST[start],$_REQUEST[anzahl]");
while($row=$db->fetch_array($res)) {
?>
</tr>
<tr bgcolor="#FFFFFF" align="left" valign="top">
<td><?=$row[rechnungauftragid]?></td>
<td><?=$row[kundenid]?></td>
<td><?=$row[auftragsdatum]?></td>
<td align="right"><?=$row[erstellen]?></td>
<td><a href="module/biz/rechnungen_order_editieren.php?rechnungauftragid=<?=$row[rechnungauftragid]?>">Editieren</a> | <a href="module/biz/rechnungen_order.php?loeschen=true&rechnungauftragid=<?=$row[rechnungauftragid]?>">Löschen</a></td>
</tr>
<?
}
?>
</table>

</td>
</tr>
</table>

<br>
<br>
<?
$seiten = round($_REQUEST[ds] / $_REQUEST[anzahl]);
$x=0;
for($i=1;$i<=$seiten;$i++) {
  echo "<a href=\"module/biz/produkte.php?start=$x\">Seite $i</a>&nbsp; &nbsp; &nbsp;";
  $x = $x + $_REQUEST[anzahl];
}
?>
</td>
</tr>
</table>










<?include("../../footer.php");?>