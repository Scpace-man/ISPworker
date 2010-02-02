<?
$module = basename(dirname(__FILE__));
include("../../header.php");
include("./inc/pdfmahn.inc.php");

?>
<span class="htitle">Mahnwesen</span><br>
<br>

<?

include("./inc/reiter4.layout.php");

$bgcolor[0]   = "#f0f0f0";
$linecolor[0] = "#000000";

$bgcolor[1]   = "#ffffff";
$linecolor[1] = "#ffffff";

include("./inc/reiter4.php");


if($_REQUEST[saveandsend]=="true") {

    $mahndatum = date("Y-m-d");
    
    for($h=0;$h<count($_SESSION['mahnmerkzettel']);$h++) {
		$positionen .= $_SESSION['mahnmerkzettel'][$h].";";
		$db->query("update biz_rechnungen set mahndatum='$mahndatum', status='gemahnt' where rechnungid='".$_SESSION['mahnmerkzettel'][$h]."' ");
    }
    
    if($h==0) { die(); }
    $datum = date("Y-m-d");
    $mahnSQL = $db->query("select * from biz_mahntemplates where templateid='$_REQUEST[templateid]'");
    $mSET = $db->fetch_array($mahnSQL);
    $mahngebuehr = $mSET[mgebuehr];
    $ruecklastgebuehr = $mSET[rgebuehr];



    $db->query("insert into biz_mahnungen (profilid,templateid,mahngebuehr,ruecklastgebuehr,positionen,kundenid,datum) values ('$_REQUEST[profilid]','$_REQUEST[templateid]','$mahngebuehr','$ruecklastgebuehr','$positionen','$_REQUEST[kundenid]','$datum')");
    $mahnid = $db->insert_id();

    pdfmahnung($mahnid);

    $file_url = "$biz_temppath/m-".$mahnid.".pdf";
    $fp = fopen($file_url,"r");
    $str = fread($fp, filesize($file_url));
    $str = chunk_split(base64_encode($str));
    fclose($fp);

    $resp = $db->query("select * from biz_profile where profilid='$_REQUEST[profilid]'");
    $rowprofil = $db->fetch_array($resp);

    $resk = $db->query("select * from biz_kunden where kundenid='$_REQUEST[kundenid]'");
    $rowk = $db->fetch_array($resk);

    $rest = $db->query("select * from biz_mahntemplates where templateid='$_REQUEST[templateid]'");
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

    $message .= "$anrede,\n\nanbei ein wichtiges Schreiben im PDF Format.\n";
    $message .= "\n\n";
    $message .= "Mit freundlichen Grüßen\n\n";
    $message .= "$rowprofil[firma]\n$rowprofil[strasse] - $rowprofil[plz] $rowprofil[ort]\n\nMail: $rowprofil[mail]\n";
    $message .= "\n";
    

    $message .= "\n";
    $message .= "--MIME_BOUNDRY\n";
    $message .= "Content-Type: application/pdf; name=\"m-$mahnid.pdf\"\n";
    $message .= "Content-disposition: attachment\n";
    $message .= "Content-Transfer-Encoding: base64\n";
    $message .= "\n";
    $message .= "$str\n";
    $message .= "\n";    
    $message .= "--MIME_BOUNDRY--\n";

    $resk = $db->query("select * from biz_kunden where kundenid='$_REQUEST[kundenid]'");
    $rowk = $db->fetch_array($resk);

    $rest = $db->query("select * from biz_mahntemplates where templateid='$_REQUEST[templateid]'");
    $tpl  = $db->fetch_array($rest);
    
    $mailbetreff = $tpl[templatename];
    
    mail($rowk["mail"], $mailbetreff, $message,$headers);
    mail($biz_settings["pdfkopie"], "Mahnung Nr $mahnid / Kundennummer $_REQUEST[kundenid]", $message,$headers);

    unlink($file_url);

    echo "<font color=\"green\">Mahnschreiben ist erstellt und verschickt.</font><br><br>";

    $deleteall = true;

}


if($deleteall==true) {
  unset($_SESSION['mahnmerkzettel']);
}


if($_REQUEST['delete']=="true") {
  for($i=0;$i<count($_SESSION['mahnmerkzettel']);$i++) {
    if($posid!=$i) {
      $newarray[] = $_SESSION['mahnmerkzettel'][$i];
    }
  }
  $_SESSION['mahnmerkzettel'] = $newarray;
}


if($_REQUEST[add]=="true") {

    $ausw = explode(",",$_REQUEST[ausw]);

    for($i = 0; $i < count($ausw); $i++) 
    {

	$r = $_SESSION['mahnmerkzettel'][0];
	$res = $db->query("select kundenid from biz_rechnungen where rechnungid='$r'");
	$row = $db->fetch_array($res);	

	if($row[kundenid]!="") {
	    $resn = $db->query("select kundenid from biz_rechnungen where rechnungid='$ausw[$i]'");
	    $rown = $db->fetch_array($resn);

	    if($row[kundenid]!=$rown[kundenid]) { echo "<font color=\"red\">Fehler: Die Rechnung muss dem gleichen Kunden zugeordnet sein.</font><br><br>"; $error = true; unset($_SESSION['mahnmerkzettel']); die(); }
	}

	if($error!=true) {
	    $_SESSION['mahnmerkzettel'][] = $ausw[$i];
	}
    }
}




?>


<br>


<table border="0" cellspacing="0" cellpadding="0">
<tr bgcolor="#cccccc">
<td>

<table width="600" border="0" cellspacing="1" cellpadding="3">
<tr bgcolor="#e7e7e7" align="left" valign="top">
<td><b>Rechnungsnummer</b></td>
<td><b>Rechnungsdatum</b></td>
<td><b>Offener Betrag</b></td>
<td width="16"><img src="img/pixel.gif" border="0"></td>
</tr>
<?

for($h=0;$h<count($_SESSION['mahnmerkzettel']);$h++) {

    $rid = $_SESSION['mahnmerkzettel'][$h];
    $res = $db->query("select rechnungid, positionen, datum, kundenid from biz_rechnungen where rechnungid='$rid'");
    $row = $db->fetch_array($res);

	
    $summe = 0;
    $pos = explode("<br>",$row[positionen]);
    for($i=0;$i<count($pos);$i++) {
	$entry  = explode("|",$pos[$i]);
	if($entry[0]!="") {
    	    $artikel_anz[] .= $entry[0];
	    $artikel_bez[] .= $entry[1]."<br>".$entry[3]."<br>";
	    $artikel_pre[] .= $entry[2];
	    $entry[0] = sprintf("%.2f",$entry[0]);
	    $summe = $summe + ($entry[2] * $entry[0]);
	    $summe = sprintf("%.2f",$summe);
	}
    }
?>
<tr bgcolor="#FFFFFF">
    <td><?=$row[rechnungid]?></td>
    <td><?
    $ts = strtotime($row[datum]);
    $date = date("d.m.Y",$ts);
    echo $date;
    ?>
    </td>
    <td><?=$summe?> &euro;</td>
    <td><a href="module/biz/mahnung_neu.php?delete=true&posid=<?=$h?>"><img src="img/trash.gif" alt="Löschen" border="0"></a></td>
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

<form action="module/biz/mahnung_neu.php?saveandsend=true&kundenid=<?=$row[kundenid]?>" method="post">
<table border="0" cellspacing="0" cellpadding="0">
<tr bgcolor="#cccccc">
<td>

<table width="600" border="0" cellspacing="1" cellpadding="3">
<tr bgcolor="#e7e7e7">
  <td colspan="2"><b>Mahn Optionen</b></td>
</tr>
<tr bgcolor="#ffffff">
  <td bgcolor="#ffffff">Template</td>
  <td bgcolor="#ffffff">
  <select name="templateid">
  <?
  $res = $db->query("select * from biz_mahntemplates");
  while($row = $db->fetch_array($res)) {
    echo "<option value=\"$row[templateid]\">$row[templatename]</option>";
  }
  ?>
  </select></td>
</tr>
<tr bgcolor="#ffffff">
  <td bgcolor="#ffffff">Firmenprofil</td>
  <td bgcolor="#ffffff"><select name="profilid">
  <?
  $res = $db->query("select * from biz_profile");
  while($row = $db->fetch_array($res)) {
    echo "<option value=\"$row[profilid]\">$row[firma]</option>";
  }
  ?>
  </select></td>
</tr>
<tr bgcolor="#ffffff">
  <td valign="top">angegebene Mahngebühren</td>
  <td>
		<table border="0" cellpadding="1" cellspacing="1">
    <?
	  $res = $db->query("select * from biz_mahntemplates");
	  while($row = $db->fetch_array($res)) {
	  	
    	echo "<tr><td width=\"200\">$row[templatename]</td><td width=\"100\">$row[mgebuehr] / $row[rgebuehr]</td></tr>";
	  }
  ?>
		</table>
  </td>
</tr>
<tr bgcolor="#ffffff">
  <td><input type="submit" value="Mahnung senden"></td>
  <td><font size="1">(Eine Kopie der Mahnung wird an <?=$biz_settings[pdfkopie]?> verschickt.)</font></td>
</tr>
</table>

</td>
</tr>
</table>
</form>

<br>
<br>

<form action="module/biz/mahnung_neu.php?add=true" method="post">
<table border="0" cellspacing="0" cellpadding="0">
<tr bgcolor="#cccccc">
<td>

<table width="400" border="0" cellspacing="1" cellpadding="3">
<tr bgcolor="#e7e7e7">
  <td colspan="2"><b>Rechnungsnummer hinzufügen</b></td>
</tr>
<tr bgcolor="#ffffff">
  <td bgcolor="#ffffff">Rechnungsnummer</td>
  <td bgcolor="#ffffff"><input type="text" name="ausw" size="15"> <input type="submit" value="Hinzufügen"></td>
</table>

</td>
</tr>
</table>
</form>
<br>
<br>
<br>



<?include("../../footer.php");?>