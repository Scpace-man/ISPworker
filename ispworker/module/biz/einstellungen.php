<?
$module = basename(dirname(__FILE__));
include("../../header.php");
include("./inc/functions.inc.php");
?>


<span class="htitle">Einstellungen</span><br>
<br>

<?


include("./inc/reiter2.layout.php");

$bgcolor[0]   = "#f0f0f0";
$linecolor[0] = "#000000";

$bgcolor[0]   = "#ffffff";
$linecolor[0] = "#ffffff";

include("./inc/reiter2.php");

if(isset($_REQUEST[update])) {
    if($biz_einstloeschbar==true) {

		if($_REQUEST[kassenbuch]!="Y") {
  		    $_REQUEST[kassenbuch]="N";
		}

		if((float)$_REQUEST['paypalfaktor']=="0"){
			$_REQUEST['paypalfaktor']="1.00";
		}

		$db->query("update biz_settings set kassenbuch='$_REQUEST[kassenbuch]', mwstnational='$_REQUEST[mwstnational]', mwstsaetze='$_REQUEST[mwstsaetze]',
  				  domainregistrare='$_REQUEST[domainregistrare]',supportnrinfo='$_REQUEST[supportnrinfo]',kmtelefon='$_REQUEST[kmtelefon]',
  				  supportnrtechnik='$_REQUEST[supportnrtechnik]',supportnrbuchhaltung='$_REQUEST[supportnrbuchhaltung]',
				  pdfkopie='$_REQUEST[pdfkopie]', kundenmenueallowchangepwd='$_REQUEST[kundenmenueallowchangepwd]',
				  kundenmenuemailfrom='$_REQUEST[kundenmenuemailfrom]', kundenmenueloginuserfield='$_REQUEST[kundenmenueloginuserfield]',
				  paypalmailaddress='$_REQUEST[paypalmailaddress]', waehrung='$_REQUEST[waehrung]', ppwaehrung='$_REQUEST[ppwaehrung]',
				  paypalfaktor='$_REQUEST[paypalfaktor]',
				  kommentar_rechnung='".strip_cr($_REQUEST[kommentar_rechnung])."',
				  kommentar_lastschrift='".strip_cr($_REQUEST[kommentar_lastschrift])."',
				  kommentar_vorkasse='".strip_cr($_REQUEST[kommentar_vorkasse])."' ");

    }else { echo "DEMO MODUS."; }
}

$res = $db->query("select * from biz_settings");
$row = $db->fetch_array($res);


?>



<form action="module/biz/einstellungen.php?update=true" method="post">
<table width="700" border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>


<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
  <td colspan="2"><b>Allgemeine Einstellungen</b></td>
</tr>
<tr class="tr">
  <td width="350">Kassenbuch</td>
  <td width="350"><?if($row[kassenbuch]=="Y") { $checked = "checked"; } else { $checked = ""; }?>
  <input type="checkbox" name="kassenbuch" value="Y" <?=$checked?>> Kassenbuch anzeigen</td>
</tr>
<tr class="tr">
  <td>Nationaler MwSt Satz</td>
  <td><input type="text" name="mwstnational" size="2" value="<?=$row[mwstnational]?>"> %</td>
</tr>
<tr class="tr">
  <td>Mögliche MwSt Sätze für jeden Kunden</td>
  <td><input type="text" name="mwstsaetze" size="20" value="<?=$row[mwstsaetze]?>" class="input-text"><br>z.B. 16,12,0</td>
</tr>
<tr class="tr">
  <td>Lokale Währung</td>
  <td><input type="text" name="waehrung" size="5" value="<?=$row[waehrung]?>"> <font size="1">[<a href="http://de.wikipedia.org/wiki/ISO_4217" target="_blank">W&auml;hrung ISO-Codes</a>]</font></td>
</tr>

<tr class="th">
  <td colspan="2">Rechnungskommentar Rechnung: Platzhalter wie z.B. &lt;profilbankkonto&gt; beibehalten.</td>
</tr>
<tr class="tr">
  <td colspan="2"><textarea name="kommentar_rechnung" style="width:690px;"><?=$row[kommentar_rechnung]?></textarea></td>
</tr>

<tr class="th">
  <td colspan="2">Rechnungskommentar Lastschrift: Platzhalter wie z.B. &lt;kontonummer&gt; beibehalten.</td>
</tr>
<tr class="tr">
  <td colspan="2"><textarea name="kommentar_lastschrift" style="width:690px;"><?=$row[kommentar_lastschrift]?></textarea></td>
</tr>

<tr class="th">
  <td colspan="2">Rechnungskommentar Vorkasse</td>
</tr>
<tr class="tr">
  <td colspan="2"><textarea name="kommentar_vorkasse" style="width:690px;"><?=$row[kommentar_vorkasse]?></textarea></td>
</tr>


<tr class="tr">
  <td>Kopie jeder PDF Rechnung per Mail an</td>
  <td><input type="text" name="pdfkopie" size="20" value="<?=$row[pdfkopie]?>" class="input-text"></td>
</tr>
<tr class="th">
  <td colspan="2"><b>Kundenmenü Einstellungen</b></td>
</tr>
<tr class="tr">
  <td>Support Nummern</td>
  <td>
  <?if($row[kmtelefon]=="Y") { $checked = "checked"; } else { $checked = ""; }?>
  <input type="checkbox" name="kmtelefon" value="Y" <?=$checked?>> Support Nummern im Kundenmenü anzeigen</td>
</tr>
<tr class="tr">
  <td>Support Nr Info</td>
  <td><input type="text" name="supportnrinfo" value="<?=$row[supportnrinfo]?>" class="input-text"></td>
</tr>
<tr class="tr">
  <td>Support Nr Technik</td>
  <td><input type="text" name="supportnrtechnik" value="<?=$row[supportnrtechnik]?>" class="input-text"></td>
</tr>
<tr class="tr">
  <td>Support Nr Buchhaltung</td>
  <td><input type="text" name="supportnrbuchhaltung" value="<?=$row[supportnrbuchhaltung]?>" class="input-text"></td>
</tr>
<tr class="tr">
  <td>Absender E-Mail Adresse</td>
  <td><input type="text" name="kundenmenuemailfrom" value="<?=$row[kundenmenuemailfrom]?>" class="input-text"></td>
</tr>
<tr class="tr">
  <td>Login mit</td>
  <td><select name="kundenmenueloginuserfield"><option value="beides" <?if($row[kundenmenueloginuserfield]=="beides") echo "selected";?>>Beides</option><option value="kundenid" <?if($row[kundenmenueloginuserfield]=="kundenid") echo "selected";?>>Kundennummer</option><option value="mail" <?if($row[kundenmenueloginuserfield]=="mail") echo "selected";?>>Mail Adresse</option></select></td>
</tr>
<tr class="tr">
  <td>Passwort Änderung durch Kunden </td>
  <td><input type="radio" name="kundenmenueallowchangepwd" value="1" <?if($row[kundenmenueallowchangepwd]=="1") echo "checked";?>> erlaubt <input type="radio" name="kundenmenueallowchangepwd" value="0" <?if($row[kundenmenueallowchangepwd]==0) echo "checked";?>> nicht erlaubt</td>
</tr>
<tr class="th">
  <td colspan="2"><b>Paypal</b> (Leer lassen, wenn Paypal nicht genutzt wird.)</td>
</tr>
<tr class="tr">
  <td>Paypal E-Mail Adresse</td>
  <td><input type="text" name="paypalmailaddress" value="<?=$row[paypalmailaddress]?>" class="input-text"></td>
</tr>
<tr class="tr">
  <td>zu verwendende Währung für Paypal</td>
  <td><select name="ppwaehrung">
  	<option value="AUD" <?if($row['ppwaehrung']=="AUD") echo "selected";?>>Australische Dollar AUD
  	<option value="CAD" <?if($row['ppwaehrung']=="CAD") echo "selected";?>>Kanadische Dollar CAD
  	<option value="EUR" <?if($row['ppwaehrung']=="EUR") echo "selected";?>>Euro EUR
  	<option value="GBP" <?if($row['ppwaehrung']=="GBP") echo "selected";?>>Britische Pfund Sterling GBP
  	<option value="JPY" <?if($row['ppwaehrung']=="JPY") echo "selected";?>>Japanische Yen JPY
  	<option value="USD" <?if($row['ppwaehrung']=="USD") echo "selected";?>>US Dollar USD
  	<option value="CHF" <?if($row['ppwaehrung']=="CHF") echo "selected";?>>Schweizer Franken CHF
</select>
  </td>
</tr>
<tr class="tr">
  <td>Aktueller Umrechnungskurs der lokalen Währung zur Paypalwährung.</td>
  <td><input type="text" name="paypalfaktor" value="<?=$row['paypalfaktor']?>" class="input-text"></td>
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
<br>
<br>


<?include("../../footer.php");?>
