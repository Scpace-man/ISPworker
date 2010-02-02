<?@session_start(); $module = basename(dirname(__FILE__));
if($_REQUEST["readexportfile"]==true) {
    include("../../include/config.inc.php");
    include("../../include/common.inc.php");
    include("./inc/functions.inc.php");
    header("Content-Type: application/octet-stream");
    header("Content-Disposition: attachment; filename=\"kunden.csv\"");
    readfile($biz_temppath."/kunden.csv"); die();
} else include("../../header.php");
?>
<span class="htitle">Einstellungen</span><br>
<br>


<?


include("./inc/reiter2.layout.php");

$bgcolor[0]   = "#f0f0f0";
$linecolor[0] = "#000000";

$bgcolor[5]   = "#ffffff";
$linecolor[5] = "#ffffff";

include("./inc/reiter2.php");

function myclean($s) { return strip_cr(trim($s)); }

if($_REQUEST[exportkunden]=="true" && $biz_einstloeschbar==true) {
    $fp = fopen("./tmp/kunden.csv","w");
    $res = $db->query("select * from biz_kunden");
    
    
    while($row=$db->fetch_array($res)) {

	$row["firma"]   = myclean($row["firma"]);
	$row["vorname"] = myclean($row["vorname"]);
	$row["nachname"]= myclean($row["nachname"]);
	$row["strasse"] = myclean($row["strasse"]);
	$row["ort"]     = myclean($row["ort"]);
	$row["isocode"] = myclean($row["isocode"]);
	$row["plz"]     = myclean($row["plz"]);
	$row["telefon"] = myclean($row["telefon"]);
	$row["handy"]   = myclean($row["handy"]);
	$row["fax"]     = myclean($row["fax"]);
	$row["mail"]    = myclean($row["mail"]);
	$row["kontoinhaber"] = myclean($row["kontoinhaber"]);
	$row["kontonummer"]  = myclean($row["kontonummer"]);
	$row["bankleitzahl"] = myclean($row["bankleitzahl"]);
	$row["geldinstitut"] = myclean($row["geldinstitut"]);
                	
	

	
	$string = "$row[kundenid];$row[anrede];$row[firma];$row[vorname];$row[nachname];$row[strasse];$row[ort];$row[plz];$row[isocode];$row[telefon];$row[fax];$row[mobil];$row[mail];$row[bezahlart];$row[kontoinhaber];$row[kontonummer];$row[bankleitzahl];$row[geldinstitut];\n";
    	fputs($fp, $string, strlen($string));
    }
    fclose($fp);
    chmod("./tmp/kunden.csv", 0777);  
}

if($_REQUEST[delexportfile]=='true' && $biz_einstloeschbar==true) {
    unlink("./tmp/kunden.csv");  

}

if($_REQUEST[importkunden]=="true" && $biz_einstloeschbar==true) {

	$uploaddir = $biz_temppath."/";
	
	if (move_uploaded_file($_FILES['kundenfile']['tmp_name'], $uploaddir . $_FILES['kundenfile']['name'])) {
//	    $fp = fopen($_FILES['kundenfile']['tmp_name'],"r");
		chmod($uploaddir.$_FILES['kundenfile']['name'], 0755);
		$fp = fopen($uploaddir.$_FILES['kundenfile']['name'],"r");
	
	    while (!feof ($fp)) {
		$buffer = fgets($fp, 4096);
		$x      = explode(";",$buffer);

		$kundennr     = (int) $x[$_REQUEST[spalte_kundennr]];
		$anrede       = $x[$_REQUEST[spalte_anrede]];
		$firma        = $x[$_REQUEST[spalte_firma]];
		$vorname      = $x[$_REQUEST[spalte_vorname]];
		$nachname     = $x[$_REQUEST[spalte_nachname]];
		$strasse      = $x[$_REQUEST[spalte_strasse]];
		$ort          = $x[$_REQUEST[spalte_ort]];
		$isocode      = $x[$_REQUEST[spalte_isocode]];
		$plz          = $x[$_REQUEST[spalte_plz]];
		$telefon      = $x[$_REQUEST[spalte_telefon]];
		$fax          = $x[$_REQUEST[spalte_fax]];
		$mobil        = $x[$_REQUEST[spalte_mobil]];
		$mail         = $x[$_REQUEST[spalte_mail]];
		$bezahlart    = $x[$_REQUEST[spalte_bezahlart]];
		$kontoinhaber = $x[$_REQUEST[spalte_kontoinhaber]];
		$kontonummer  = $x[$_REQUEST[spalte_kontonummer]];
		$bankleitzahl = $x[$_REQUEST[spalte_bankleitzahl]];
		$bankinstitut = $x[$_REQUEST[spalte_bankinstitut]];
	
		$pwd = make_password();


			if($vorname!="" && $nachname!="")
			{
			$db->query("insert into biz_kunden (kundenid,adminid,anrede,vorname,nachname,strasse,firma,ort,plz,isocode,telefon,fax,mail,handy,kontoinhaber,kontonummer,bankleitzahl,geldinstitut,passwort)
				    values ('$kundennr','1','$anrede','$vorname','$nachname','$strasse','$firma','$ort','$plz','$isocode','$telefon','$fax','$mail','$mobil','$kontoinhaber','$kontonummer','$bankleitzahl','$bankinstitut','$pwd')");
			}
	    }
	    message("Kundendaten sind importiert.");
	}else{
	   message("Fehler beim Import der Kundendaten.","error");
	}
}
?>


<form enctype="multipart/form-data" action="module/biz/einst_importexport.php?importkunden=true" method="post">
<table width="440" border="0" cellspacing="0" cellpadding="0">
<tr bgcolor="#cccccc">
<td>


<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr bgcolor="#e7e7e7">
  <td colspan="2"><b>Kundendaten importieren</b></td>
</tr>
<tr>
  <td width="200" bgcolor="#ffffff"><b>Feld</b></td>
  <td bgcolor="#ffffff"><b>Spalte</b></td>
</tr>
<tr>
  <td width="200" bgcolor="#ffffff">KundenNr</td>
  <td bgcolor="#ffffff"><input type="text" name="spalte_kundennr" size="2" value="0"></td>
</tr>
<tr>
  <td width="200" bgcolor="#ffffff">Anrede</td>
  <td bgcolor="#ffffff"><input type="text" name="spalte_anrede" size="2" value="1"></td>
</tr>
<tr>
  <td width="200" bgcolor="#ffffff">Firma</td>
  <td bgcolor="#ffffff"><input type="text" name="spalte_firma" size="2" value="2"></td>
</tr>
<tr>
  <td width="200" bgcolor="#ffffff">Vorname</td>
  <td bgcolor="#ffffff"><input type="text" name="spalte_vorname" size="2" value="3"></td>
</tr>
<tr>
  <td width="200" bgcolor="#ffffff">Nachname</td>
  <td bgcolor="#ffffff"><input type="text" name="spalte_nachname" size="2" value="4"></td>
</tr>
<tr>
  <td width="200" bgcolor="#ffffff">Strasse</td>
  <td bgcolor="#ffffff"><input type="text" name="spalte_strasse" size="2" value="5"></td>
</tr>
<tr>
  <td width="200" bgcolor="#ffffff">Ort</td>
  <td bgcolor="#ffffff"><input type="text" name="spalte_ort" size="2" value="6"></td>
</tr>
<tr>
  <td width="200" bgcolor="#ffffff">Plz</td>
  <td bgcolor="#ffffff"><input type="text" name="spalte_plz" size="2" value="7"></td>
</tr>
<tr>
  <td width="200" bgcolor="#ffffff">Land Isocode</td>
  <td bgcolor="#ffffff"><input type="text" name="spalte_isocode" size="2" value="8"></td>
</tr>
<tr>
  <td width="200" bgcolor="#ffffff">Telefon</td>
  <td bgcolor="#ffffff"><input type="text" name="spalte_telefon" size="2" value="9"></td>
</tr>
<tr>
  <td width="200" bgcolor="#ffffff">Fax</td>
  <td bgcolor="#ffffff"><input type="text" name="spalte_fax" size="2" value="10"></td>
</tr>
<tr>
  <td width="200" bgcolor="#ffffff">Mobil</td>
  <td bgcolor="#ffffff"><input type="text" name="spalte_mobil" size="2" value="11"></td>
</tr>
<tr>
  <td width="200" bgcolor="#ffffff">Mail</td>
  <td bgcolor="#ffffff"><input type="text" name="spalte_mail" size="2" value="12"></td>
</tr>
<tr>
  <td width="200" bgcolor="#ffffff">Bezahlart</td>
  <td bgcolor="#ffffff"><input type="text" name="spalte_bezahlart" size="2" value="13"></td>
</tr>
<tr>
  <td width="200" bgcolor="#ffffff">Kontoinhaber</td>
  <td bgcolor="#ffffff"><input type="text" name="spalte_kontoinhaber" size="2" value="14"></td>
</tr>
<tr>
  <td width="200" bgcolor="#ffffff">Kontonummer</td>
  <td bgcolor="#ffffff"><input type="text" name="spalte_kontonummer" size="2" value="15"></td>
</tr>
<tr>
  <td width="200" bgcolor="#ffffff">Bankleitzahl</td>
  <td bgcolor="#ffffff"><input type="text" name="spalte_bankleitzahl" size="2" value="16"></td>
</tr>
<tr>
  <td width="200" bgcolor="#ffffff">Bankinstitut</td>
  <td bgcolor="#ffffff"><input type="text" name="spalte_bankinstitut" size="2" value="17"></td>
</tr>
<tr>
  <td bgcolor="#ffffff" colspan="2"><input name="kundenfile" type="file"></td>
</tr>
<tr>
  <td bgcolor="#ffffff" colspan="2"><input type="submit" value="Importieren"></td>
</tr>
</table>

</td>
</tr>
</table>

</form>
<br>

<a name="export">
<form action="module/biz/einst_importexport.php?exportkunden=true#export" method="post">
<table width="440" border="0" cellspacing="0" cellpadding="0">
<tr bgcolor="#cccccc">
<td>


<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr bgcolor="#e7e7e7">
  <td colspan="2"><b>Kundendaten exportieren</b></td>
</tr>
<tr>
  <td bgcolor="#ffffff"><input type="submit" value="Exportieren">
  <?if($_REQUEST[exportkunden]=='true') {?><br><br>
  <a href="module/biz/einst_importexport.php?readexportfile=true" target="new">Kundendatei öffnen</a><br>
  <a href="module/biz/einst_importexport.php?delexportfile=true#export">Kundendatei löschen</a><br>
  <?}?>
  
  
  </td>
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
