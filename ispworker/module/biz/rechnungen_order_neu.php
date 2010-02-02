<?
$module = basename(dirname(__FILE__));
include("../../header.php");
include("./inc/config.inc.php");
?>


<span class="htitle">Neue Rechnung</span><br>
<br>




<?
if(isset($_REQUEST['new'])) {

	//Daten des Kunden holen, wichtig für die Bankdaten bei Lastschrift
	$res = $db->query("select * from biz_kunden where kundenid='$_REQUEST[kundenid]'");
	$kun = $db->fetch_array($res);

	//Rechnungskommentare holen
	$res_rechkom = $db->query("select kommentar_rechnung, kommentar_lastschrift, kommentar_vorkasse from biz_settings");
	$row_rechkom = $db->fetch_array($res_rechkom);

	$biz_kommentar_rechnung=$row_rechkom[kommentar_rechnung];
	$biz_kommentar_lastschrift=$row_rechkom[kommentar_lastschrift];
	$biz_kommentar_vorkasse=$row_rechkom[kommentar_vorkasse];

	//Bankdaten des Providers holen
	$res_profil = $db->query("select * from biz_profile");
	$row_profil = $db->fetch_array($res_profil);

	if($_REQUEST[zahlungsart]=="rechnung") {
	    $biz_kommentar_rechnung = str_replace("<profilbankkonto>",$row_profil[bankkonto],$biz_kommentar_rechnung);
	    $biz_kommentar_rechnung = str_replace("<profilbankblz>",$row_profil[bankblz],$biz_kommentar_rechnung);
	    $kommentarvorlage = $biz_kommentar_rechnung;
	}

	if($_REQUEST[zahlungsart]=="lastschrift") {
	    $biz_kommentar_lastschrift = str_replace("<kontonummer>",$kun[kontonummer],$biz_kommentar_lastschrift);
	    $biz_kommentar_lastschrift = str_replace("<bankleitzahl>",$kun[bankleitzahl],$biz_kommentar_lastschrift);
	    $kommentarvorlage = $biz_kommentar_lastschrift;
	}

	if($_REQUEST[zahlungsart]=="vorkasse") {
	    $biz_kommentar_vorkasse = str_replace("<profilbankkonto>",$row_profil[bankkonto],$biz_kommentar_vorkasse);
	    $biz_kommentar_vorkasse = str_replace("<profilbankblz>",$row_profil[bankblz],$biz_kommentar_vorkasse);
	    $kommentarvorlage = $biz_kommentar_vorkasse;
	}

	//Rechnungskommentar, sowie eigene Kommentare zusammenfügen
	$_REQUEST[rechnungskommentar]=$_REQUEST[rechnungskommentar]."\n".$kommentarvorlage;

	for($pos=0;$pos<count($_SESSION['merkzettel']);$pos++) {
		  $beginnabrechnung = $_REQUEST[jahr][$pos]."-".$_REQUEST[monat][$pos]."-".$_REQUEST[tag][$pos];

		  $x = explode(":",$_SESSION['merkzettel'][$pos]);

		  $kommentar[$pos] = wordwrap($_REQUEST['kommentar'][$pos],71,"\n");
		  //$kommentar[$pos] = wordwrap($kommentar[$pos],71,"\n"); // ORG

		  $db->query("insert into biz_rechnungtodo (adminid,kundenid,beginnabrechnung,produktanzahl,produktid,
               produktkommentar,profilid,kommentar) values ('$_SESSION[adminid]','$_REQUEST[kundenid]','$beginnabrechnung',
              '$x[0]','$x[1]','$kommentar[$pos]','$_REQUEST[profilid]','$_REQUEST[rechnungskommentar]')");

		  echo "<center><b>Auftrag ist gespeichert.</b></center><br><br>\n";
	}

	if($_REQUEST[merkzetteldo]=="del") {
		$emptyarr=array();
	    $_SESSION['merkzettel'] = $emptyarr;
    	$_SESSION['merkkunde']  = "";
	}
}





?>

Geben Sie eine Kundennummer ein oder suchen Sie unter "Kunden" einen Kunden aus und
klicken Sie dort auf "Merken".<br>
<br>

<form action="module/biz/rechnungen_order_neu.php" method="post">
<table border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>

<table width="320" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
<td><b>KundenNr eingeben</b></td>
</tr>
<tr class="tr">
<td><input type="text" size="8" name="kundenid"><input type="submit" value="Auswählen"></td>
</tr>
</table>

</td>
</tr>
</table>
</form>

<br>
<?

if(!isset($_REQUEST[kundenid])) {
	$kundenid = $_SESSION['merkkunde'];
	$res = $db->query("select * from biz_kunden where kundenid='$kundenid'");
	$kun = $db->fetch_array($res);
}else{
	$res = $db->query("select * from biz_kunden where kundenid='$_REQUEST[kundenid]'");
	$kun = $db->fetch_array($res);
	$kundenid=$_REQUEST[kundenid];
}

$currencySQL = $db->query("select waehrung from biz_settings");
$currency=$db->fetch_array($currencySQL);

if($kun[kundenid]!="") {
?>

<table border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>
<table width="350" border="0" cellspacing="1" cellpadding="3">
<tr class="tr">
<td width="100" class="th">KundenNr</b></td>
<td><?=$kun[kundenid]?></b></td>
</tr>
<tr class="tr">
<td class="th">Vorname</b></td>
<td><?=$kun[vorname]?></b></td>
</tr>
<tr class="tr">
<td class="th">Nachname</b></td>
<td><?=$kun[nachname]?></b></td>
</tr>
<tr class="tr">
<td class="th">Mail</b></td>
<td><?=$kun[mail]?></b></td>
</tr>
</table>

</td>
</tr>
</table>

<br>

<?}?>

<br>

<form action="module/biz/rechnungen_order_neu.php?new=true&kundenid=<?=$kundenid?>" method="post">
<table border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>

<table width="600" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
<td width="16"><b>Anzahl</b></td>
<td width="16"><b>ProduktID</b></td>
<td><b>Bezeichnung</b></td>
<td><b>Abrechnung</b></td>
<td><b>Preis in <?=$currency['waehrung']?></b></td>
<td><b>Summe in <?=$currency['waehrung']?></b></td>
</tr>
<?

$t_tag   = date("d");
$t_monat = date("m");
$t_jahr  = date("Y");

for($pos=0;$pos<count($_SESSION['merkzettel']);$pos++) {

  $x = explode(":",$_SESSION['merkzettel'][$pos]);
  $res = $db->query("select * from biz_produkte where produktid='$x[1]'");
  $row = $db->fetch_array($res);
?>

<tr class="tr">
<td><?=$x[0]?></td>
<td><?=$x[1]?></td>
<td><?=$row[bezeichnung]?></td>
<td><?=$row[abrechnung]?></td>
<td align="right"><?=$row[preis]?></td>
<?
  $summe = $x[0] * $row[preis];
  $summe = sprintf("%.2f",$summe);
  $total = $total + $summe;
?>
<td align="right"><?=$summe?></td>
</tr>
<tr class="tr">
<td colspan="2">Beginn des Abrechnungszeitraumes</td>
<td colspan="4">Tag <input type="text" name="tag[<?=$pos?>]" value="<?=$t_tag?>" size="3"> Monat  <input type="text" name="monat[<?=$pos?>]" value="<?=$t_monat?>" size="3"> Jahr  <input type="text" name="jahr[<?=$pos?>]" value="<?=$t_jahr?>"size="5"></td>
</tr>
<tr class="tr">
<td colspan="2">Kommentar</td>
<td colspan="4"><textarea name="kommentar[<?=$pos?>]"  style="width: 440px" rows="2"><?=$row[beschreibung]."\n".$x[2]?></textarea></td>
</tr>
<?
}
?>
</table>
</td>
</tr>
</table>


<br>


<table width="600" border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
  <td colspan="2"><b>Neuer Rechnungsauftrag</b></td>
</tr>
<tr class="tr">
  <td>Kommentar</td>
  <td><textarea name="rechnungskommentar" rows="2" style="width: 440px"></textarea></td>
</tr>

<tr class="tr">
  <td>Zahlungsart</td>
  <td>
  	<select name="zahlungsart">
	<?
		if($kun[bezahlart]=="vorkasse") $vselected = "selected";
		if($kun[bezahlart]=="lastschrift") $lselected = "selected";
		if($kun[bezahlart]=="rechnung") $rselected = "selected";
	?>
  		<option value="vorkasse" <?echo $vselected;?>>Vorkasse</option>
  		<?if($kun[kontonummer]!="") {?><option value="lastschrift" <?echo $lselected;?>>Lastschrift</option><?}?>
  		<option value="rechnung" <?echo $rselected;?>>Rechnung</option>

  	</select>
  </td>
</tr>

<tr class="tr">
  <td width="132">Profil</td>
  <td>
  <select name="profilid">
  <?
  $res = $db->query("select profil,profilid from biz_profile where adminid='$_SESSION[adminid]'");
  while($row = $db->fetch_array($res)) {
    echo "<option value=\"$row[profilid]\">$row[profil]</option>\n";
  }
  ?>
  </select>
  </td>
</tr>


<tr class="tr">
  <td>Merkzettel</td>
  <td><input type="radio" name="merkzetteldo" value="del" checked> Leeren <input type="radio" name="merkzetteldo" value="hold"> Beibehalten</td>
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
<br>
<br>
<?include("../../footer.php");?>
