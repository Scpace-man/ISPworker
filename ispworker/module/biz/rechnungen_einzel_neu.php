<?
$module = basename(dirname(__FILE__));
include("../../header.php");
include("inc/pdf.inc.php");
?>

<span class="htitle">Neue Einzelrechnung</span><br>
<br>

<?

if(!isset($_REQUEST[kundenid])) {
    $kundenid = $_SESSION['merkkunde'];
}else{
   $kundenid = $_REQUEST[kundenid];
}

//$_SESSION['merkkunde'] = $_REQUEST[kundenid];

$res = $db->query("select * from biz_kunden where kundenid='$kundenid'");
$row = $db->fetch_array($res);

if($_REQUEST[add]=="true") {
    $_REQUEST[kommentar]   = wordwrap($_REQUEST[kommentar],58,"\n");
    $_SESSION['poslist'][] = "$_REQUEST[anzahl]|$_REQUEST[bezeichnung]|".sprintf("%.2f",$_REQUEST[preis])."|\n$_REQUEST[kommentar]";
}


if($_REQUEST[plus]=="true") {
    $x = explode("|",$_SESSION['poslist'][$i]);
    $anzahl = $x[0] + 1;
    $_SESSION['poslist'][$i] =  "$anzahl|$x[1]|$x[2]|$x[3]";
}


if($_REQUEST[minus]=="true") {
    $x = explode("|",$_SESSION['poslist'][$i]);
    $anzahl = $x[0] - 1;
    $_SESSION['poslist'][$i] =  "$anzahl|$x[1]|$x[2]|$x[3]";
}


if($_REQUEST[movedown]=="true") {
    $temp = $_SESSION['poslist'][$i+1];
    $_SESSION['poslist'][$i+1] = $_SESSION['poslist'][$i];
    $_SESSION['poslist'][$i]   = $temp;
}

if($_REQUEST[moveup]=="true") {
    $temp = $_SESSION['poslist'][$i];
    $_SESSION['poslist'][$i]   = $_SESSION['poslist'][$i-1];
    $_SESSION['poslist'][$i-1] = $temp;
}


if($_REQUEST[del]=="true") {    
    $len = count($_SESSION['poslist']);
    
    while($i<$len-1) {
	$temp = $_SESSION['poslist'][$i+1];
	$_SESSION['poslist'][$i+1] = $_SESSION['poslist'][$i];
	$_SESSION['poslist'][$i]   = $temp;	
	$i++;
    }
    array_pop($_SESSION['poslist']);
}
    


if(isset($_REQUEST['new'])) {

//Daten des Kunden holen, wichtig für die Bankdaten bei Lastschrift
	$res = $db->query("select * from biz_kunden where kundenid='$kundenid'");
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

    $positionen = "";

    for($i=0;$i<count($_SESSION['poslist']);$i++) {
		$positionen .= $_SESSION['poslist'][$i]."<br>";
    }

    $res_p = $db->query("select * from biz_profile where profilid='$_REQUEST[profilid]'");
    $row_profil = $db->fetch_array($res_p);
  
  
    if($row[firma]!="") { $f = "$row[firma], "; } else { $f = ""; }
    $anschrift = $f.$row[vorname]." ".$row[nachname]."|".$row[strasse]."|".$row[isocode]."-".$row[plz]." ".$row[ort]."|";

    $datum = "$_REQUEST[jahr]-$_REQUEST[monat]-$_REQUEST[tag]"; 

    $db->query("insert into biz_rechnungen (adminid,kundenid,anschrift,positionen,profilid,kommentar,datum)
              values ('$_SESSION[adminid]','$kundenid','$anschrift','$positionen','$_REQUEST[profilid]','$_REQUEST[rechnungskommentar]','$datum')");

	$rechid=$db->insert_id();
//  echo "<center><b>Rechnung ist gespeichert.</b></center><br><br>\n";

    pdfinvoice($rechid);
	if($_REQUEST[versenden]=="versenden"){
		sendmailpdfsingleinvoice ($rechid,$kundenid,$_REQUEST[profilid],$biz_temppath,$biz_settings);
		message("Einzelrechnung Nr. $rechid erstellt, gespeichert und per Mail verschickt!");
	}else{
		message("Einzelrechnung Nr. $rechid erstellt und gespeichert!");
	}
}

$currencySQL = $db->query("select waehrung from biz_settings");
$currency=$db->fetch_array($currencySQL);
?>

Geben Sie eine KundenNr ein oder suchen Sie unter "Kunden" einen Kunden aus und
klicken Sie dort auf "Merken".<br>
<br>

<form action="module/biz/rechnungen_einzel_neu.php" method="post">
<table border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>

<table width="300" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
<td><b>KundenNr eingeben</b></td>
</tr>
<tr class="tr">
<td><input type="text" size="8" name="kundenid"> <input type="submit" value="Auswählen"></td>
</tr> 

</table>

</td>
</tr>
</table>
</form>
    
<br>

    
<table border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>
<table width="350" border="0" cellspacing="1" cellpadding="3">
<tr class="tr">
<td class="th" width="100" bgcolor="#e7e7e7">KundenNr</b></td>
<td><?=$row[kundenid]?></b></td>
</tr>
<tr class="tr">
<td class="th">Firma</b></td>
<td><?=$row[firma]?></b></td>
</tr>
<tr class="tr">
<td class="th">Vorname</b></td>
<td><?=$row[vorname]?></b></td>
</tr>
<tr class="tr">
<td class="th">Nachname</b></td>
<td><?=$row[nachname]?></b></td>
</tr>
<tr class="tr">
<td class="th">Mail</b></td>
<td><?=$row[mail]?></b></td>
</tr>
</table>
		    
</td>
</tr>
</table>
<br>
<br>


<table border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>

<table width="622" border="0" cellspacing="1" cellpadding="3">
<tr class="th"">
<td width="60"><b>Anzahl</b></td>
<td><b>Bezeichnung & Kommentar</b></td>
<td width="80"><b>E-Preis in <?=$currency['waehrung']?></b></td>
<td width="85"><b>Summe in <?=$currency['waehrung']?></b></td>
<td width="16"></td>
<td width="16"></td>
<td width="16"></td>
</tr>
<?
for($i=0;$i<count($_SESSION['poslist']);$i++) {

$x = explode("|",$_SESSION['poslist'][$i]);

?>
<tr class="tr">
  <td valign="top"><a href="module/biz/rechnungen_einzel_neu.php?plus=true&i=<?=$i?>"><img src="img/plus.gif" border="0"></a> <?=$x[0]?> <?if($x[0]>1) {?><a href="module/biz/rechnungen_einzel_neu.php?minus=true&i=<?=$i?>"><img src="img/minus.gif" border="0"></a><?}?></td>
  <td valign="top"><?=$x[1]."<br><pre>".$x[3]."</pre>"?></td>
  <td valign="top"><?=$x[2]?></td>
  <td valign="top">
  <?
  $zw = ($x[0] * $x[2]);
  $zw = sprintf("%.2f",$zw);
  $summe = $summe + $zw;
  $summe = sprintf("%.2f",$summe);

  echo $zw;
  ?>
  </td>
  
  <td valign="top" align="center">
  <?if($i!=0) {?>
  <a href="module/biz/rechnungen_einzel_neu.php?moveup=true&i=<?=$i?>"><img src="img/arrow_up.gif" border="0"></a>
  <?}?>
  </td>
  <td valign="top" align="center">
  <?if(count($_SESSION['poslist'])>1 && $i != count($_SESSION['poslist'])-1) {?>
  <a href="module/biz/rechnungen_einzel_neu.php?movedown=true&i=<?=$i?>"><img src="img/arrow_down.gif" border="0"></a>
  <?}?>
  </td>
  <td valign="top"><a href="module/biz/rechnungen_einzel_neu.php?del=true&i=<?=$i?>"><img src="img/trash.gif" border="0"></a></td>

</tr>
<?}?>

<tr class="tr">
  <td colspan="3">Summe:</td>
  <td><?=$summe?></td>
  <td colspan="3"></td>
</tR>


</table>

</td>
</tr>
</table>


<br>

<b>Rechnungsposition hinzufügen</b><br>
<br>
		    
<form action="module/biz/rechnungen_einzel_neu.php?add=true&kundenid=<?=$kundenid?>" method="post">
<table border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>

<table border="0" cellspacing="1" cellpadding="3">
<tr class="th"">
<td width="40"><b>Anzahl</b></td>
<td width="460"><b>Bezeichnung & Kommentar</b></td>
<td width="100"><b>E-Preis in <?=$currency['waehrung']?></b></td>
</tr>

<tr class="tr">
<td valign="top" align="center"><input type="text" name="anzahl" value="1" size="2"></td>
<td valign="top"><input type="text" name="bezeichnung" size="70"><br>
<textarea name="kommentar" style="width: 445px" rows="4"></textarea>
</td>
<td valign="top" align="center"><input type="text" name="preis" size="7" value="0.00"></td>
</tr>

<tr class="tr">
<td colspan="4"><input type="submit" value="Adden"></td>
</tr>

</table>

</td>
</tr>
</table>
</form>

<br>

<form action="module/biz/rechnungen_einzel_neu.php?new=true&kundenid=<?=$kundenid?>" method="post">
<table width="622" border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
  <td colspan="3"><b>Neue Einzel-Rechnung</b></td>
</tr>
<?
$d = date("d");
$m = date("m");
$y = date("Y");
?>
<tr class="tr">
  <td>Datum</td>
  <td colspan="2">
  Tag <input type="text" name="tag" value="<?=$d?>" size="2"> Monat <input type="text" name="monat" value="<?=$m?>" size="2"> Jahr <input type="text" name="jahr" value="<?=$y?>" size="4"> 
  </td>
</tr>

<tr class="tr">
  <td>Zahlungsart</td>
  <td colspan="2">
  	<select name="zahlungsart">
	<?
		if($row[bezahlart]=="vorkasse") $vselected = "selected";
		if($row[bezahlart]=="lastschrift") $lselected = "selected";
		if($row[bezahlart]=="rechnung") $rselected = "selected";				
	?>
  		<option value="vorkasse" <?echo $vselected;?>>Vorkasse</option>
  		 <?if($kun[kontonummer]!="") {?><option value="lastschrift" <?echo $lselected;?>>Lastschrift</option><?}?>
  		<option value="rechnung" <?echo $rselected;?>>Rechnung</option>

  	</select>
  </td>
</tr>

<tr class="tr">
  <td>Profil</td>
  <td colspan="2">
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
<td>Kommentar</td>
<td colspan="2"><textarea name="rechnungskommentar" cols="98" rows="2"></textarea></td>
</tr>


<tr class="tr">
  <td>&nbsp;</td>
  <td><input type="checkbox" name="versenden" value="versenden"> Rechnung per Mail versenden</td>
  <td align="right"><input type="submit" value="Speichern"></td>  
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
include("../../footer.php");



function sendmailpdfsingleinvoice ($rechnungid, $kundenid, $profilid, $biz_temppath, $biz_settings){

	    global $db;	
	    global $biz_temppath;	
		
	    $file_url = "$biz_temppath/r-".$rechnungid.".pdf";
	    
	    $fp = fopen($file_url,"r");
	    $str = fread($fp, filesize($file_url));
	    $str = chunk_split(base64_encode($str));
	    fclose($fp);

	    $resp = $db->query("select * from biz_profile where profilid='$profilid'");
	    $rowprofil = $db->fetch_array($resp);
    
	    $resk = $db->query("select * from biz_kunden where kundenid='$kundenid'");
	    $rowk = $db->fetch_array($resk);

    	    $rest = $db->query("select * from biz_mailtemplates where templatename='std_neuerechnung'");
	    $tpl  = $db->fetch_array($rest);

	    if($rowk[anrede]=="Herr") { $anrede = "Sehr geehrter Herr $rowk[nachname]"; }
	    elseif($rowk[anrede]=="Frau") { $anrede = "Sehr geehrte Frau $rowk[nachname]"; }
	    else { $anrede = "Sehr geehrte Damen und Herren"; }

	    $headers = "From: $rowprofil[mail]\n";
	    $headers .= "MIME-Version: 1.0\n";
	    $headers .= "Content-Type: multipart/mixed; boundary=\"MIME_BOUNDRY\"\n";
    	    $headers .= "X-Mailer: PHP4\n";
    	    $headers .= "This is a multi-part message in MIME format.\n";

    	    $message = "--MIME_BOUNDRY\n";
    	    $message .= "Content-Type: text/plain; charset=\"iso-8859-1\"\n";
    	    $message .= "Content-Transfer-Encoding: quoted-printable\n";
    	    $message .= "\n";

	    // String replacements
     
	    $mailtext    = str_replace("#anrede#",$anrede,$tpl[mailtext]);
            $mailtext    = str_replace("#profilkundenmenue#",$rowprofil[kundenmenue],$mailtext);
	    $mailbetreff = str_replace("#rechnungid#",$rechnungid,$tpl[mailbetreff]);
	    $mailbetreff = str_replace("#kundenid#",$kundenid,$mailbetreff);
	    $mailtext    = str_replace("#rechnungid#",$rechnungid,$mailtext);
	    $mailtext    = str_replace("#kundenid#",$kundenid,$mailtext);
	
	    $message .= $mailtext;
				 
    	    $message .= "\n";
    	    $message .= "--MIME_BOUNDRY\n";
    	    $message .= "Content-Type: application/pdf; name=\"r-$rechnungid.pdf\"\n";
    	    $message .= "Content-disposition: attachment\n";
	    $message .= "Content-Transfer-Encoding: base64\n";
    	    $message .= "\n";
    	    $message .= "$str\n";
    	    $message .= "\n";    
    	    $message .= "--MIME_BOUNDRY--\n";

	    // für den Kunden
	    mail($rowk["mail"], $mailbetreff, $message,$headers);
    	    // für den Hoster
	    mail($biz_settings["pdfkopie"], $mailbetreff, $message,$headers);

	    unlink($file_url);
}
?>
