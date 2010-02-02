<?
/*********************************************************************************/
/*	CHANGES 28.03.2006, sm
/*	Zeile 194-220: Landangaben implementiert, inklusive Datenbankeintrag
/*********************************************************************************/
$module = basename(dirname(__FILE__));
include("./inc/functions.inc.php");
include("../../header.php");

	$errorclass = "orange";

	if(!isset($_REQUEST['submit']))
	{
		initform();
	}
	else
	{
		if(!validlength($_REQUEST['vorname'], 3))
		{
			$error = true;
			$err_vorname = $errorclass;
		}
        if(!validlength($_REQUEST['nachname'], 3))
		{
			$error = true;
			$err_nachname = $errorclass;
		}
        if(!validlength($_REQUEST['strasse'], 3))
		{
			$error = true;
			$err_strasse = $errorclass;
		}
        if(!validlength($_REQUEST['plz'], 4))
		{
			$error = true;
			$err_plz = $errorclass;
		}
       	if(!validlength($_REQUEST['ort'], 3))
		{
			$error = true;
			$err_ort = $errorclass;
		}
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

		  $anrede = "$_REQUEST[anrede] $_REQUEST[titel]";

		   $db->query("insert into biz_kunden (adminid,anrede,titel,firma,vorname,nachname,strasse,plz,ort,isocode,telefon,handy,fax,mail,kontoinhaber,geldinstitut,bankleitzahl,kontonummer,bezahlart,passwort,mwst,sendmail,rechnungstext, geb_tag,geb_monat,geb_jahr)
            		       values ('$_SESSION[adminid]','$_REQUEST[anrede]','$_REQUEST[titel]','$_REQUEST[firma]','$_REQUEST[vorname]','$_REQUEST[nachname]','$_REQUEST[strasse]','$_REQUEST[plz]','$_REQUEST[ort]','$_REQUEST[isocode]','$_REQUEST[telefon]','$_REQUEST[handy]','$_REQUEST[fax]','$_REQUEST[mail]','$_REQUEST[kontoinhaber]','$_REQUEST[geldinstitut]','$_REQUEST[bankleitzahl]','$_REQUEST[kontonummer]','$_REQUEST[bezahlart]','".sha1($_REQUEST[passwort])."','$_REQUEST[mwst]','$_REQUEST[sendmail]','$_REQUEST[rechnungstext]','$_REQUEST[tag]','$_REQUEST[monat]','$_REQUEST[jahr]')");
		    $kundenid = $db->insert_id();
		}
	}

	// Formular initialisieren
	function initform()
	{
		$error = false;
		$_REQUEST['firma']		= "";
		$_REQUEST['titel']		= "";
		$_REQUEST['vorname']	= "";
        $_REQUEST['nachname']	= "";
        $_REQUEST['gebdatum']	= "";
        $_REQUEST['strasse']	= "";
		$_REQUEST['plz']		= "";
		$_REQUEST['ort']		= "";
		$_REQUEST['telefon']	= "";
		$_REQUEST['fax']		= "";
		$_REQUEST['handy']		= "";
		$_REQUEST['mail']		= "";
		$_REQUEST['kontoinhaber']	= "";
		$_REQUEST['geldinstitut']	= "";
		$_REQUEST['bankleitzahl']	= "";
		$_REQUEST['kontonummer']	= "";
	}

	// Funktionen zur Validierung
	function validemail($string)
	{
		if (empty($string)) return false;
		$preg = "^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@([a-zA-Z0-9-]+\.)+([a-zA-Z]{2,4})$";

		preg_match("/$preg/", $string, $result);
		if ($string != $result[0]) return false;
		return true;
	}

	function validlength($string, $laenge)
	{
		if (strlen($string) < $laenge) return false;
		return true;
	}





if($_REQUEST[sendaccountdata]=="true") 
{
    $resp = $db->query("select mail, kundenmenue from biz_profile where profilid='1'");
    $rowp = $db->fetch_array($resp);

    $rest = $db->query("select * from biz_mailtemplates where templatename='std_zugangsdaten'");
    $rowt = $db->fetch_array($rest);

    $mailbetreff = $rowt[mailbetreff];
    $mailtext    = $rowt[mailtext];

    $mailbetreff = str_replace("#benutzername#", "$kundenid", $mailbetreff);
    $mailbetreff = str_replace("#passwort#", "$_REQUEST[passwort]", $mailbetreff);

    $mailtext = str_replace("#benutzername#", "$kundenid", $mailtext);
    $mailtext = str_replace("#passwort#", "$_REQUEST[passwort]", $mailtext);
    $mailtext = str_replace("#profilkundenmenue#", "$rowp[kundenmenue]", $mailtext);

    if (mail($_REQUEST[mail],$mailbetreff,$mailtext,"From: ".$rowp[mail]))
    	message("Zugangsdaten erfolgreich verschickt");
    else 	
	message("Fehler beim versenden der Zugangsdaten");
}


if(isset($_REQUEST['submit']) and !$error) 
{

    echo "<center><b>Kunde wurde gespeichert.</b><br>";
    echo "<a href=\"module/biz/kunden_detail.php?kundenid=$kundenid\">Kunden Details</a> | <a href=\"module/biz/kunde_neu.php\">Neuen Kunden anlegen</a></center><br><br>\n";
}
elseif($error) { ?>
    <div class="headlineorange">Das Formular ist fehlerhaft ausgef&uuml;llt</div><br>
    Bitte &uuml;berpr&uuml;fen Sie die markierten Felder und senden Sie das Formular noch einmal ab.<br><br>

<? } ?>



<?if(!isset($_REQUEST['submit']) or $error) { ?>

<span class="htitle">Kunden</span><br>
<br>


<form action="module/biz/kunde_neu.php" method="post">
<table width="540" border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
  <td colspan="2"><b>Neuer Kunde</b></td>
</tr>
<tr class="tr">
  <td>Anrede</td>
  <td><select name="anrede"><option value="Herr">Herr</option><option value="Frau">Frau</option><option value="Firma">Firma</option></select></td>
</tr>
<tr class="tr">
  <td >Firma</td>
  <td><input type="text" name="firma" value="<?php echo $_REQUEST['firma']; ?>" class="input-text"></td>
</tr>
<tr class="tr">
  <td>Titel</td>
  <td><input type="text" name="titel" value="<?php echo $_REQUEST['titel']; ?>" class="input-text"></td>
</tr>
<tr class="tr">
  <td class="text<?=$err_vorname?>">Vorname</td>
  <td><input type="text" name="vorname" value="<?php echo $_REQUEST['vorname']; ?>" class="input-text"></td>
</tr>
<tr class="tr">
  <td class="text<?=$err_nachname?>">Nachname</td>
  <td><input type="text" name="nachname" value="<?php echo $_REQUEST['nachname']; ?>" class="input-text"></td>
</tr>
<tr class="tr">
  <td class="text<?=$err_geburtsdatum?>">Geburtsdatum</td>
  <td><input size="2" type="text" name="tag" value="<?php echo $_REQUEST['geb_tag']; ?>">.<input size="2" type="text" name="monat" value="<?php echo $_REQUEST['geb_monat']; ?>">.<input size="4" type="text" name="jahr" value="<?php echo $_REQUEST['geb_jahr']; ?>"> (TT.MM.JJJJ)</td>
</tr>
<tr class="tr">
  <td class="text<?=$err_strasse?>">Strasse</td>
  <td><input type="text" name="strasse" value="<?php echo $_REQUEST['strasse']; ?>" class="input-text"></td>
</tr>
<tr class="tr">
  <td class="text<?=$err_plz?>">Plz</td>
  <td><input type="text" name="plz" size="5" value="<?php echo $_REQUEST['plz']; ?>"></td>
</tr>
<tr class="tr">
  <td class="text<?=$err_ort?>">Ort</td>
  <td><input type="text" name="ort" value="<?php echo $_REQUEST['ort']; ?>" class="input-text"></td>
</tr>
<tr class="tr">
  <td>Land</td>
  <td>
<select name="isocode">
<?
    $respl = $db->query("select * from order_laender");
    while($rowl=$db->fetch_array($respl)) 
    {
        if($rowl[isocode]=="DE")
    	    echo "<option value=".$rowl[isocode]." selected>".$rowl[isocode]." ".$rowl[name]."</option>";
	else
	    echo "<option value=".$rowl[isocode].">".$rowl[isocode]." ".$rowl[name]."</option>";		
    }
?>
</select>
  </td>
</tr>
<tr class="tr">
  <td>Telefon</td>
  <td><input type="text" name="telefon" value="<?php echo $_REQUEST['telefon']; ?>" class="input-text"></td>
</tr>
<tr class="tr">
  <td>Handy</td>
  <td><input type="text" name="handy" value="<?php echo $_REQUEST['handy']; ?>" class="input-text"></td>
</tr>
<tr class="tr">
  <td>Fax</td>
  <td><input type="text" name="fax" value="<?php echo $_REQUEST['fax']; ?>" class="input-text"></td>
</tr>
<tr class="tr">
  <td class="text<?=$err_mail?>">Mail</td>
  <td><input type="text" name="mail" value="<?php echo $_REQUEST['mail']; ?>" class="input-text"></td>
</tr>
<tr class="th">
  <td><b>Zahlungsbedingungen</b></td>
  <td>&nbsp;</td>
</tr>
<tr class="tr">
  <td>Bezahlart</td>
  <td><select name="bezahlart"><option value="lastschrift" selected="selected">Lastschrift</option><option value="rechnung">Rechnung</option><option value="vorkasse">Vorkasse</option><?
	    $ppsql = $db->query("select paypalmailaddress from biz_settings");
	    $pp = $db->fetch_array($ppsql);
		if($pp['paypalmailaddress']!=""){
			  echo'<option value="paypal">Paypal</option>';
		}
    ?></select></td>
</tr>
<tr class="tr">
  <td class="text<?=$err_bank?>">Kontoinhaber</td>
  <td><input type="text" name="kontoinhaber" value="<?php echo $_REQUEST['kontoinhaber']; ?>" class="input-text"></td>
</tr>
<tr class="tr">
  <td class="text<?=$err_bank?>">Konto</td>
  <td><input type="text" name="kontonummer" value="<?php echo $_REQUEST['kontonummer']; ?>" class="input-text"></td>
</tr>
<tr class="tr">
  <td class="text<?=$err_bank?>">BLZ</td>
  <td><input type="text" name="bankleitzahl" value="<?php echo $_REQUEST['bankleitzahl']; ?>" class="input-text"></td>
</tr>
<tr class="tr">
  <td class="text<?=$err_bank?>">Bank</td>
  <td><input type="text" name="geldinstitut" value="<?php echo $_REQUEST['geldinstitut']; ?>" class="input-text"></td>
</tr>
<tr class="tr">
  <td>Passwort</td>
  <td><input type="text" name="passwort" value="<?echo make_password2();?>"></td>
</tr>
<tr class="tr">
  <td>MwSt</td>
  <td>
  <select name="mwst">
  <?
  $biz_mwst = explode(",",$biz_settings[mwstsaetze]);
  for($i=0;$i<count($biz_mwst);$i++) {
  	echo "<option>".$biz_mwst[$i]."</option>";
  }
  ?>
  </select>
  </td>
</tr>
<tr class="tr">
  <td>Rechnungstext</td>
  <td><input type="text" name="rechnungstext" value="Zahlbar sofort rein netto." class="input-text"></td>
</tr>
<tr class="tr">
  <td>Rechnungszustellung</td>
  <td>
  <select name="sendmail">
  	<option value="Y">per E-Mail</option>
  	<option value="N">per Post</option>
  </select>
  </td>
</tr>
<tr class="tr">
  <td>&nbsp;</td>
  <td><input type="checkbox" name="sendaccountdata" value="true"> Zugangsdaten jetzt per Mail versenden</td>
</tr>
<tr class="tr">
  <td>&nbsp;</td>
  <td><input type="submit" name="submit" value="Speichern"></td>
</tr>
</table>

</td>
</tr>
</table>
</form>

<?}?>

<br>
<br>
<?include("../../footer.php");?>
