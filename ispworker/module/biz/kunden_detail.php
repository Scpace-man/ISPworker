<?
/*********************************************************************************/
/*	CHANGES 28.03.2006, sm
/*	Zeile 159-166: Darstellung der Landangaben implementiert
/*********************************************************************************/
$module = basename(dirname(__FILE__));
include("./inc/functions.inc.php");
include("../../header.php");



include("./inc/reiter1.layout.php");
include("./inc/reiter1.php");

$res = $db->query("SELECT * FROM biz_kunden WHERE kundenid='$_REQUEST[kundenid]'");
$row = $db->fetch_array($res);

$res2 = $db->query("SELECT bestandskunden,firma,titel,mobil,fax,url,pkey,geb,zusatz1,zusatz2,zusatz3,zusatz1status,zusatz2status,zusatz3status,nichtvolljaehrig FROM order_settings", $conn) or die ("SQL Abfrage ist ung&uuml;ltig. ".mysql_error());
$ro2 = $db->fetch_array($res2);

if($_REQUEST[sendaccount]=="true")
{
    $resp = $db->query("select mail, kundenmenue from biz_profile where profilid='1'");
    $rowp = $db->fetch_array($resp);

    $rest = $db->query("select * from biz_mailtemplates where templatename='std_zugangsdaten'");
    $rowt = $db->fetch_array($rest);

    $mailbetreff = $rowt['mailbetreff'];
    $mailtext    = $rowt['mailtext'];

    $neuespw=make_password2();

    if($rowsets["kundenmenueloginuserfield"]=="mail") {
        $mailbetreff = str_replace("#benutzername#", "$row[mail]", $mailbetreff);
        $mailtext = str_replace("#benutzername#", "$row[mail]", $mailtext);
    }
    else {
	$mailbetreff = str_replace("#benutzername#", "$_REQUEST[kundenid]", $mailbetreff);
        $mailtext = str_replace("#benutzername#", "$_REQUEST[kundenid]", $mailtext);
    }
    
    $mailbetreff = str_replace("#passwort#", $neuespw, $mailbetreff);

    $mailtext = str_replace("#passwort#", $neuespw, $mailtext);
    $mailtext = str_replace("#profilkundenmenue#", $rowp['kundenmenue'], $mailtext);
?>
    <form action="module/biz/kunden_detail.php?sendaccountnow=true&kundenid=<?=$_REQUEST[kundenid]?>&from=<?=$rowp['mail']?>&to=<?=$row['mail']?>" method="post">
	<input type="hidden" name="pw" value="<?=$neuespw?>">
    <table width="540" border="0" cellspacing="0" cellpadding="0">
    <tr class="tb">
    <td>

    <table width="100%" border="0" cellspacing="1" cellpadding="3">
    <tr class="th">
	<td><b>Zugangsdaten senden</b></td>
    </tr>
    <tr class="tr">
	<td><input type="text" name="mailbetreff" value="<?=$mailbetreff?>" class="input-text"></td>
    </tr>
    <tr class="tr">
	<td><textarea name="mailtext" style="width:530px;height:150px;"><?=$mailtext?></textarea></td>
    </tr>
    <tr class="tr">
	<td><input type="submit" value="Senden"></td>
    </tr>
    </table>

    </td>
    </tr>
    </table>
    </form>

<?
}

if($_REQUEST[sendaccountnow]=="true") {

	$db->query("Update biz_kunden set passwort='".sha1($pw)."' where kundenid='$_REQUEST[kundenid]'") or die("Fehler beim versenden des Passwortes!");

    mail("$to","$mailbetreff",strip_cr($mailtext),"From: $from");
    message("Zugangsdaten sind gesendet.");
}


?>

<table width="540" border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
  <td colspan="2"><b>Kundendaten</b></td>
</tr>
<tr class="tr">
  <td width="150">Funktionen</td>
  <td><a href="module/biz/kunden.php?action=merken&kundenid=<?=$row[kundenid]?>"><img src="img/merken.gif" alt="Merken" border="0"></a> <img src="img/pixel.gif" width="6" height="1"> <a href="module/biz/kunde_editieren.php?kundenid=<?=$row[kundenid]?>"><img src="img/edit.gif" alt="Bearbeiten" border="0"></a> <img src="img/pixel.gif" width="6" height="1"> <a href="module/biz/kunden_detail.php?kundenid=<?=$_REQUEST[kundenid]?>&sendaccount=true"><img src="img/sendmail.gif" border="0" alt="Zugangsdaten senden"></a></td>
</tr>
<tr class="tr">
  <td>KundenNr</td>
  <td><?=$row[kundenid]?></td>
</tr>
<?
// Firma
if($ro2['firma'] != "inaktiv")
{
?>
<tr class="tr">
    <td>Firma</td>
    <td><?=$row[firma]?></td>
</tr>
<?
}
?>
<tr class="tr">
  <td>Anrede</td>
  <td><?=$row[anrede]?></td>
</tr>
<?
// Titel
if($ro2['titel'] != "inaktiv")
{
?>
<tr class="tr">
    <td>Titel</td>
    <td><?=$row[titel]?></td>
</tr>
<?
}
// Ende Titel
?>
<tr class="tr">
  <td>Vorname</td>
  <td><?=$row[vorname]?></td>
</tr>
<tr class="tr">
  <td>Nachname</td>
  <td><?=$row[nachname]?></td>
</tr>
<?
/*********************************************************************************/
/*	CHANGES 28.03.2006, sm
/*	Zeile 162-183: Landangaben implementiert, inklusive Datenbankeintrag
/*********************************************************************************/

// Geburtsdatum
if($ro2['geb'] != "inaktiv")
{
echo'
<tr class="tr">
    <td>Geburtsdatum</td>
    <td>';
		if($row['geb_tag'] != ""){
			echo $row['geb_tag'].".".$row['geb_monat'].".".$row['geb_jahr'];
		}
echo '</td>
</tr>';

}
// Ende Geburtsdatum
?>
<tr class="tr">
  <td>Strasse</td>
  <td><?=$row[strasse]?></td>
</tr>
<tr class="tr">
  <td valign="top">Land / Plz / Ort</td>
  <td><?=$row[isocode]?>-<?=$row[plz]?> <?=$row[ort]?></td>
</tr>
<tr class="tr">
  <td>Telefon</td>
  <td><?=$row[telefon]?></td>
</tr>
<?
// Mobil
if($ro2['mobil'] != "inaktiv")
{
?>
<tr class="tr">
    <td>Handy &nbsp;<span style="font-size:10px;">(mit Vorwahl)</span></td>
    <td><?=$row[handy]?></td>
</tr>
<?
}
// Ende Mobil
// Fax
if($ro2['fax'] != "inaktiv")
{
?>
<tr class="tr">
    <td>Fax &nbsp;<span style="font-size:10px;">(mit Vorwahl)</span></td>
    <td><?=$row[fax]?></td>
</tr>
<?
}
// Ende Fax
// Website
if($ro2['url'] != "inaktiv")
{
?>
<tr class="tr">
    <td>Website</td>
    <td><?=$row[url]?></td>
</tr>
<?
}
?>
<tr class="tr">
  <td>E-Mail</td>
  <td><? echo "<a href=\"mailto:$row[mail]\">$row[mail]</a>"; ?></td>
</tr>
<?
// Zusatz1
if($ro2['zusatz1status'] != "inaktiv")
{
?>
<tr class="tr">
    <td><?=$ro2['zusatz1']?></td>
    <td><?=$row[zusatz1]?></td>
</tr>
<?
}
// Ende Zusatz1
// Zusatz2
if($ro2['zusatz2status'] != "inaktiv")
{
?>
<tr class="tr">
    <td><?=$ro2['zusatz2']?></td>
    <td><?=$row[zusatz2]?></td>
</tr>
<?
}
// Ende Zusatz2
// Zusatz3
if($ro2['zusatz3status'] != "inaktiv")
{
?>
<tr class="tr">
    <td><?=$ro2['zusatz3']?></td>
    <td><?=$row[zusatz3]?></td>
</tr>
<?
}
// Ende Zusatz3
?>
<tr class="th">
  <td colspan="2"><b>Zahlungsbedingungen</b></td>
</tr>
<tr class="tr">
  <td>Bezahlart</td>
  <td>
  	<?
  	if($row['bezahlart']=="rechnung")
        echo "Rechnung";
    elseif($row['bezahlart']=="vorkasse")
        echo "Vorkasse";
    elseif($row['bezahlart']=="paypal")
        echo "Paypal";
    else
        echo "Lastschrift";
  	?>

  </td>
</tr>
<tr class="tr">
  <td>Kontoinhaber</td>
  <td><?=$row[kontoinhaber]?></td>
</tr>
<tr class="tr">
  <td>Kontonummer</td>
  <td><?=$row[kontonummer]?></td>
</tr>
<tr class="tr">
  <td>Bankleitzahl</td>
  <td><?=$row[bankleitzahl]?></td>
</tr>
<tr class="tr">
  <td>Geldinstitut</td>
  <td><?=$row[geldinstitut]?></td>
</tr>

<tr class="th">
  <td colspan="2"><b>Sonstiges</b></td>
  </tr>
<tr class="tr">
  <td>Passwort</td>
  <td><?=$row[passwort]?></td>
</tr>
<tr class="tr">
  <td>Mehrwertsteuersatz</td>
  <td><?=$row[mwst]?>%</td>
</tr>
<tr class="tr">
  <td><b>Rechnungszustellung</b></td>
  <td><b><?if($row[sendmail]=="Y"){ echo "Rechnungen per E-Mail verschicken"; }else{ echo "Rechnungen per Post verschicken"; }?></b></td>
</tr>
<tr class="tr">
  <td>Rechnungstext</td>
  <td><?=$row[rechnungstext]?></td>
</tr>
<?
$resrechnung = $db->query("SELECT * FROM biz_rechnungen WHERE kundenid='$_REQUEST[kundenid]'");
while($rowrechnung = $db->fetch_array($resrechnung))
{
	$_records[] = array("positionen" => $rowrechnung["positionen"], "status" => $rowrechnung['status']);
}
$gemahnt    = 0;
$unbezahlt  = 0;
$bezahlt    = 0;
if(count($_records))
foreach($_records as $_record)
{
		// Positionen trennen
		$_positions = explode("<br>", $_record['positionen']);
		// Positionen durchlaufen
		$ausgabe = array();
		foreach($_positions as $_position)
		{
			// Felder trennen
			$_fields = explode("|", $_position);
			// Betrag steht im 3. Feld
			if(isset($_fields[2]) && $_fields[2]!="")
            {
		       $ausgabe[] = $_fields[2];
            }
		}

		if($_record['status'] == "gemahnt"){
			for($z=0;$z<count($ausgabe);$z++){
				$gemahnt+=$ausgabe[$z];
			}
		}elseif($_record['status'] == "unbezahlt"){
			for($z=0;$z<count($ausgabe);$z++){
				$unbezahlt+=$ausgabe[$z];
				$unbezahlt+=$gemahnt;
			}
		}elseif($_record['status'] == "bezahlt"){
			for($z=0;$z<count($ausgabe);$z++){
				$bezahlt+=$ausgabe[$z];
			}
		}
}


?>
</table>
</td>
</tr>
</table><br>
<table width="540" border="0" cellspacing="0" cellpadding="0">
<tr bgcolor="#cccccc">

<td>
<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
  <td colspan="2"><b>Kunden-Profil</b></td>
  </tr>
<tr class="tr">
  <td>Rechnungen bezahlt:</td>
  <td><?echo sprintf("%.2f",$bezahlt)." ".$biz_settings[waehrung]?></td>
</tr>
<tr class="tr">
  <td>Rechnungen unbezahlt:</td>
  <td><?echo sprintf("%.2f",$unbezahlt)." ".$biz_settings[waehrung]?></td>
</tr>
<tr class="tr">
  <td>Rechnungen gemahnt:</td>
  <td><?echo sprintf("%.2f",$gemahnt)." ".$biz_settings[waehrung]?></td>
</tr>
</table>

</td>
</tr>
</table>

<br>
<br>

<br>
<br>
<br>




<?include("../../footer.php");?>
