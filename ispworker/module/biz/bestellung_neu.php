<?
/******************************/
/*CHANGES 28.03.2006, sm
/* Zeile 78-95:
/* 	- Source aufgeräumt
/* 	- Darstellung der Kundendaten optimiert
/******************************/

$module = basename(dirname(__FILE__));
include("../../header.php");
?>


<span class="htitle">Neue Bestellung</span><br>
<br>




<?
if($_REQUEST['new']=="true") {
  $datum = date("Y-m-d H:i:s");
  
  for($pos=0;$pos<count($_SESSION['merkzettel']);$pos++) {
    $produkte .= $_SESSION['merkzettel'][$pos].";";
  }    	
  $db->query("insert into biz_bestellungen (adminid,kundenid,datum,produkte,kommentar,statusid) values('$_SESSION[adminid]','$_REQUEST[kundenid]','$datum','$produkte','$_REQUEST[kommentar]','1')");
  echo "<b>Bestellung gespeichert.</b><br><br>";
}

?>

W&auml;hlen Sie zun&auml;chst unter "Produkte" die bestellten Produkte aus.
<br>
<br>
Geben Sie eine KundenID ein oder suchen Sie unter "Kunden" einen Kunden aus und
klicken Sie dort auf "Merken".<br>
<br>

<form action="module/biz/bestellung_neu.php" method="post">
<table border="0" cellspacing="0" cellpadding="0">
<tr bgcolor="#cccccc">
<td>

	<table width="300" border="0" cellspacing="1" cellpadding="3">
	<tr bgcolor="#e7e7e7" align="left" valign="top">
		<td><b>Kunden ID eingeben</b></td>
	</tr>
	<tr bgcolor="#FFFFFF" align="left" valign="top">
		<td><input type="text" size="8" name="kundenid"> <input type="submit" value="Auswählen"></td>
	</tr>
	</table>

</td>
</tr>
</table>
</form>

<br>
<br>
<br>
<?
if(!isset($_REQUEST[kundenid])) {
  $kundenid = $_SESSION['merkkunde'];
}else{
  $kundenid = $_REQUEST[kundenid];
}

$res = $db->query("select kundenid,vorname,nachname,mail from biz_kunden where adminid='$_SESSION[adminid]' and kundenid='$kundenid'");
$row = $db->fetch_array($res);

$currencySQL = $db->query("select waehrung from biz_settings");
$currency=$db->fetch_array($currencySQL);
?>

<table border="0" cellspacing="0" cellpadding="0">
<tr bgcolor="#cccccc">
<td>

	<table width="500" border="0" cellspacing="1" cellpadding="3">
	<tr align="left" valign="top">
	  <td bgcolor="#e7e7e7" width="100"><b>KundenNr:</b></td>
	  <td bgcolor="#ffffff"><?=$row[kundenid]?></td>
	</tr>
	<tr>
	  <td bgcolor="#e7e7e7" width="100"><b>Vorname:</b></td>
	  <td bgcolor="#ffffff"><?=$row[vorname]?></td>
	</tr>
	<tr>
	  <td bgcolor="#e7e7e7" width="100"><b>Nachname:</b></td>
	  <td bgcolor="#ffffff"><?=$row[nachname]?></td>
	</tr>
	<tr>
	  <td bgcolor="#e7e7e7" width="100"><b>E-Mail:</b></td>
	  <td bgcolor="#ffffff"><?=$row[mail]?></td>
	</tr>
	</table>

</td>
</tr>
</table>

<br>
<br>


<table border="0" cellspacing="0" cellpadding="0">
<tr bgcolor="#cccccc">
<td>

<table width="600" border="0" cellspacing="1" cellpadding="3">
<tr bgcolor="#e7e7e7" align="left" valign="top">
<td><b>Anzahl</b></td>
<td><b>ProduktID</b></td>
<td><b>Bezeichnung</b></td>
<td><b>Preis in <?=$currency['waehrung']?></b></td>
<td><b>Summe in <?=$currency['waehrung']?></b></td>


<?



for($pos=0;$pos<count($_SESSION['merkzettel']);$pos++) {

  $x = explode(":",$_SESSION['merkzettel'][$pos]);
  $res = $db->query("select bezeichnung,preis from biz_produkte where adminid='$_SESSION[adminid]' and produktid='$x[1]'");
  $row = $db->fetch_array($res);

?>
</tr>
<tr bgcolor="#FFFFFF" align="left" valign="top">
<td><?=$x[0]?></td>
<td><?=$x[1]?></td>
<td><?=$row[bezeichnung]?></td>

<td align="right"><?=$row[preis]?></td>
<?
 $summe = $x[0] * $row[preis];
 $summe = sprintf("%.2f",$summe);
 $total = $total + $summe;
?>
<td align="right"><?=$summe?></td>
</tr>
<?
}
?>
</table>

</td>
</tr>
</table>
<?
 $total = sprintf("%.2f",$total);
?>
<br>
<b>Summe Total:</b> <?echo $total." ".$currency['waehrung'];?> <br>
<br>
<form action="module/biz/bestellung_neu.php?new=true&kundenid=<?=$kundenid?>" method="post">
Kommentar:<br>
<textarea name="kommentar"></textarea>
<br>
<input type="submit" value="Bestellung speichern">
</form>
<br>



<br>










<?include("../../footer.php");?>