<?
/*********************************************************************************/
/*	CHANGES 28.03.2006, sm
/*	Zeile 162-183: Landangaben implementiert, inklusive Datenbankeintrag
/*********************************************************************************/
$module = basename(dirname(__FILE__));
include("../../header.php");



include("./inc/reiter1.layout.php");
include("./inc/reiter1.php");

$res2 = $db->query("SELECT bestandskunden,firma,titel,mobil,fax,url,pkey,geb,zusatz1,zusatz2,zusatz3,zusatz1status,zusatz2status,zusatz3status,nichtvolljaehrig FROM order_settings", $conn) or die ("SQL Abfrage ist ung&uuml;ltig. ".mysql_error());
$ro2 = $db->fetch_array($res2);

if(isset($_REQUEST[update])) {
	$errorclass = "orange";
	$pwsql = $db->query("SELECT kundenid,passwort FROM biz_kunden where kundenid='$_REQUEST[kundenid]'");
	$pw = $db->fetch_array($pwsql);

  if($_REQUEST[anzeigen]!="Y") { $_REQUEST[anzeigen] = "N"; }

  $aenderungen = "";
  if($ro2['firma'] != "inaktiv")
    $aenderungen .= ",firma='$_REQUEST[firma]'";
  if($ro2['titel'] != "inaktiv")
    $aenderungen .= ",titel='$_REQUEST[titel]'";
  if($ro2['mobil'] != "inaktiv")
    $aenderungen .= ",handy='$_REQUEST[handy]'";
  if($ro2['fax'] != "inaktiv")
    $aenderungen .= ",fax='$_REQUEST[fax]'";
  if($ro2['geb'] != "inaktiv")
    $aenderungen .= ",geb_monat='$_REQUEST[monat]',geb_jahr='$_REQUEST[jahr]',geb_tag='$_REQUEST[tag]'";
  if($ro2['url'] != "inaktiv")
    $aenderungen .= ",url='$_REQUEST[url]'";
  if($ro2['zusatz1status'] != "inaktiv")
    $aenderungen .= ",zusatz1='$_REQUEST[zusatz1]'";
  if($ro2['zusatz2status'] != "inaktiv")
    $aenderungen .= ",zusatz2='$_REQUEST[zusatz2]'";
  if($ro2['zusatz3status'] != "inaktiv")
    $aenderungen .= ",zusatz3='$_REQUEST[zusatz3]'";

	$error=false;

	if($_REQUEST['bezahlart']=="lastschrift" && ($_REQUEST['bankleitzahl']=="" || $_REQUEST['kontonummer']==""))
	{
		$error = true;
		$err_bank = $errorclass;
	}

	if($_REQUEST['sendmail']=='Y' && $_REQUEST['mail']==""){
		$error = true;
		$err_mail = $errorclass;
	}

	if(!$error)
	{
		// Wenn Passwort leer, lasse Passwort unverändert, andernfalls setze neues Passwort.
		if($_REQUEST[passwort]==""){

		$db->query("update biz_kunden set anrede='$_REQUEST[anrede]',vorname='$_REQUEST[vorname]',nachname='$_REQUEST[nachname]',strasse='$_REQUEST[strasse]',
		          plz='$_REQUEST[plz]',ort='$_REQUEST[ort]',isocode='$_REQUEST[isocode]',telefon='$_REQUEST[telefon]',mail='$_REQUEST[mail]',kontoinhaber='$_REQUEST[kontoinhaber]',geldinstitut='$_REQUEST[geldinstitut]',bankleitzahl='$_REQUEST[bankleitzahl]',
		         kontonummer='$_REQUEST[kontonummer]',bezahlart='$_REQUEST[bezahlart]',mwst='$_REQUEST[mwst]',anzeigen='$_REQUEST[anzeigen]', sendmail='$_REQUEST[sendmail]', rechnungstext='$_REQUEST[rechnungstext]'".$aenderungen." where kundenid='$_REQUEST[kundenid]'");

		}else{

		$db->query("update biz_kunden set anrede='$_REQUEST[anrede]',vorname='$_REQUEST[vorname]',nachname='$_REQUEST[nachname]',strasse='$_REQUEST[strasse]',
		          plz='$_REQUEST[plz]',ort='$_REQUEST[ort]',isocode='$_REQUEST[isocode]',telefon='$_REQUEST[telefon]',mail='$_REQUEST[mail]',kontoinhaber='$_REQUEST[kontoinhaber]',geldinstitut='$_REQUEST[geldinstitut]',bankleitzahl='$_REQUEST[bankleitzahl]',
		         kontonummer='$_REQUEST[kontonummer]',bezahlart='$_REQUEST[bezahlart]',passwort='".sha1($_REQUEST[passwort])."',mwst='$_REQUEST[mwst]',anzeigen='$_REQUEST[anzeigen]', sendmail='$_REQUEST[sendmail]', rechnungstext='$_REQUEST[rechnungstext]'".$aenderungen." where kundenid='$_REQUEST[kundenid]'");
		}
		message("&Auml;nderungen sind gespeichert.");


	}
	else
	{
		message("&Auml;nderungen an den Kundendaten bitte &uuml;berpr&uuml;fen.","error");;
	}

}

$res = $db->query("select * from biz_kunden where kundenid='$_REQUEST[kundenid]'", $conn)  or die ("SQL Abfrage ist ung&uuml;ltig. ".mysql_error());
$row = $db->fetch_array($res);

?>

<form action="module/biz/kunde_editieren.php?update=true&kundenid=<?=$_REQUEST[kundenid]?>" method="post">
<table width="540" border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>
<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
  <td colspan="2"><b>Kundendaten editieren</b></td>
</tr>
<?
// Firma
if($ro2['firma'] != "inaktiv")
{

}
// Ende Firma
switch($row['anrede'])
{
    case('frau'):
                $mann = "";
                $frau = " selected";
                break;
    case('mann'):
                $mann = " selected";
                $frau = "";
                break;
}
?>
<tr class="tr">
    <td>Anrede</td>
    <td><select name="anrede"><option <?=$mann?>>Herr</option><option <?=$frau?>>Frau</option></select></td>
</tr>

<?
// Firma
if($ro2['firma'] != "inaktiv")
{
?>
<tr class="tr">
    <td>Firma<?=$pflicht?></td>
    <td><input type="text" name="firma" value="<?=$row[firma]?>" class="input-text"></td>
</tr>
<?
}
// Ende Firma

// Titel
if($ro2['titel'] != "inaktiv")
{
?>
<tr class="tr">
    <td>Titel</td>
    <td><input type="text" name="titel" value="<?=$row[titel]?>" class="input-text"></td>
</tr>
<?
}
// Ende Titel
?>
<tr class="tr">
  <td>Vorname</td>
  <td><input type="text" name="vorname" value="<?=$row[vorname]?>" class="input-text"></td>
</tr>
<tr class="tr">
  <td>Nachname</td>
  <td><input type="text" name="nachname" value="<?=$row[nachname]?>" class="input-text"></td>
</tr>
<?
// Geburtsdatum
if($ro2['geb'] != "inaktiv")
{
?>
<tr class="tr">
    <td>Geburtsdatum</td>
    <td><input type="text" name="tag" value="<?=$row[geb_tag]?>" size="2" maxlength="2">.<input type="text" name="monat" value="<?=$row[geb_monat]?>" size="2" maxlength="2">.<input type="text" name="jahr" value="<?=$row[geb_jahr]?>" size="4" maxlength="4"></td>
</tr>
<?
}
// Ende Geburtsdatum
?>
<tr class="tr">
  <td>Strasse</td>
  <td><input type="text" name="strasse" value="<?=$row[strasse]?>" class="input-text"></td>
</tr>
<tr class="tr">
  <td>Plz</td>
  <td><input type="text" name="plz" size="5" value="<?=$row[plz]?>"></td>
</tr>
<tr class="tr">
  <td>Ort</td>
  <td><input type="text" name="ort" value="<?=$row[ort]?>" class="input-text"></td>
</tr>
<tr class="tr">
  <td>Land</td>
  <td>
<select name="isocode">
<?
    $respl = $db->query("select * from order_laender");
	while($rowl=$db->fetch_array($respl)) {
		if($rowl[isocode]==$row[isocode]){
			echo "<option value=".$rowl[isocode]." selected>".$rowl[isocode]." ".$rowl[name]."</option>";
		}else{
			echo "<option value=".$rowl[isocode].">".$rowl[isocode]." ".$rowl[name]."</option>";
		}
	}
?>
</select>
  </td>
</tr>
<tr class="tr">
  <td>Telefon</td>
  <td><input type="text" name="telefon" value="<?=$row[telefon]?>" class="input-text"></td>
</tr>
<?
// Mobil
if($ro2['mobil'] != "inaktiv")
{
?>
<tr class="tr">
    <td>Mobil &nbsp;<span style="font-size:10px;">(mit Vorwahl)</span></td>
    <td><input type="text" name="handy" value="<?=$row[handy]?>" class="input-text"></td>
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
    <td><input type="text" name="fax" value="<?=$row[fax]?>" class="input-text"></td>
</tr>
<?
}
// Ende Fax
?>
<tr class="tr">
  <td class="text<?=$err_mail?>">E-Mail</td>
  <td><input type="text" name="mail" value="<?=$row[mail]?>" class="input-text"></td>
</tr>
<?
// Website
if($ro2['url'] != "inaktiv")
{
?>
<tr class="tr">
    <td>Website</td>
    <td><input type="text" name="url" value="<?=$row[url]?>" class="input-text"></td>
</tr>
<?
}

// Zusatz1
if($ro2['zusatz1status'] != "inaktiv")
{
?>
<tr class="tr">
    <td><?=$ro2['zusatz1']?></td>
    <td><input type="text" name="zusatz1" value="<?=$row[zusatz1]?>" class="input-text"></td>
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
    <td><input type="text" name="zusatz2" value="<?=$row[zusatz2]?>" class="input-text"></td>
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
    <td><input type="text" name="zusatz3" value="<?=$row[zusatz3]?>" class="input-text"></td>
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

  <? if($row[bezahlart]=="lastschrift") { ?>
  <td><select name="bezahlart">
  <option value="lastschrift" selected="selected">Lastschrift</option>
  <option value="rechnung">Rechnung</option>
  <option value="vorkasse">Vorkasse</option>
  <option value="paypal">Paypal</option>
  </select></td>
  <?} elseif($row[bezahlart]=="rechnung") { ?>
  <td><select name="bezahlart">
<option value="lastschrift">Lastschrift</option>
  <option value="rechnung" selected="selected">Rechnung</option>
  <option value="vorkasse">Vorkasse</option>
  <option value="paypal">Paypal</option>
  </select></td>
  <?} elseif($row[bezahlart]=="paypal") {?>
  <td><select name="bezahlart">
  <option value="lastschrift">Lastschrift</option>
  <option value="rechnung">Rechnung</option>
  <option value="vorkasse">Vorkasse</option>
  <option value="paypal" selected="selected">Paypal</option>
  </select></td>
  <?}elseif($row[bezahlart]=="vorkasse")  { ?>
 	 <td><select name="bezahlart">
 	 <option value="lastschrift">Lastschrift</option>
 	 <option value="rechnung">Rechnung</option><br>
 	 <option value="vorkasse" selected="selected">Vorkasse</option>
	 <option value="paypal">Paypal</option>
	 </select></td>
  <?} ?>
</tr>
<tr class="tr">
  <td class="text<?=$err_bank?>">Kontoinhaber</td>
  <td><input type="text" name="kontoinhaber" value="<?=$row[kontoinhaber]?>" class="input-text"></td>
</tr>
<tr class="tr">
  <td class="text<?=$err_bank?>">Kontonummer</td>
  <td><input type="text" name="kontonummer" value="<?=$row[kontonummer]?>" class="input-text"></td>
</tr>
<tr class="tr">
  <td class="text<?=$err_bank?>">Bankleitzahl</td>
  <td><input type="text" name="bankleitzahl" value="<?=$row[bankleitzahl]?>" class="input-text"></td>
</tr>
<tr class="tr">
  <td>Geldinstitut</td>
  <td><input type="text" name="geldinstitut" value="<?=$row[geldinstitut]?>" class="input-text"></td>
</tr>

<tr class="th">
  <td colspan="2"><b>Sonstiges</b></td>
  </tr>
<tr class="tr">
  <td>Neues Passwort setzen</td>
  <td><input type="text" name="passwort" value="" class="input-text"> <font size="1">(optional)</font></td>
</tr>

<tr class="tr">
  <td>MwSt</td>
  <td><input type="text" name="mwst" value="<?=$row[mwst]?>" size="2"></td>
</tr>
<tr class="tr">
  <td>Rechnungszustellung</td>
  <td>
  <select name="sendmail">
  	<option value="Y"<?if($row[sendmail]=="Y") echo " selected";?>>per E-Mail</option>
  	<option value="N"<?if($row[sendmail]=="N") echo " selected";?>>per Post</option>
  </select></td>
</tr>
<tr class="tr">
  <td>Anzeigen</td><?if($row[anzeigen]=="Y") { $checked = " checked"; } else { $checked = ""; }?>
  <td><input type="checkbox" name="anzeigen" value="Y" <?=$checked?>> Kunde in der Kunden-Übersicht anzeigen</td>
</tr>
<tr class="tr">
  <td>Rechnungstext</td>
  <td><input type="text" name="rechnungstext" value="<?=$row[rechnungstext]?>" class="input-text"></td>
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
