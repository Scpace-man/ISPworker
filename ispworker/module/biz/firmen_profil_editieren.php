<?
$module = basename(dirname(__FILE__));
include("../../header.php");

echo'<span class="htitle">Firmenprofil editieren</span><br>
<br>';

include("./inc/reiter5.layout.php");

$bgcolor[2]   = "#f0f0f0";
$linecolor[2] = "#000000";

$bgcolor[1]   = "#f0f0f0";
$linecolor[1] = "#000000";

$bgcolor[0]   = "#ffffff";
$linecolor[0] = "#ffffff";

include("./inc/reiter5.php");

?>

<?
if(isset($_REQUEST[update])) {

  $success=false;
  
  $db->query("update biz_profile set idprefix='$_REQUEST[idprefix]',idsuffix='$_REQUEST[idsuffix]',firma='$_REQUEST[firma]',inhaber='$_REQUEST[inhaber]',strasse='$_REQUEST[strasse]',
              plz='$_REQUEST[plz]',ort='$_REQUEST[ort]',telefon='$_REQUEST[telefon]',fax='$_REQUEST[fax]',mail='$_REQUEST[mail]',homepage='$_REQUEST[homepage]',bankinhaber='$_REQUEST[bankinhaber]',
	      bankinstitut='$_REQUEST[bankinstitut]',bankblz='$_REQUEST[bankblz]',bankkonto='$_REQUEST[bankkonto]',bankiban='$_REQUEST[bankiban]',bankbic='$_REQUEST[bankbic]',
	      umsatzsteuerid='$_REQUEST[umsatzsteuerid]',steuerid='$_REQUEST[steuerid]',kundenmenue='$_REQUEST[kundenmenue]',datumprefix='$_REQUEST[datumprefix]',logo_h='$_REQUEST[logo_h]',logo_w='$_REQUEST[logo_w]'
	      where adminid='$_SESSION[adminid]' and profilid='$_REQUEST[profilid]'");

	$aenderungstext="Änderungen erfolgreich übernommen";
	
  if($_FILES['logo']['tmp_name']!="") {
    if($_FILES['logo']['type']!="image/jpeg" and $_FILES['logo']['type']!="image/pjpeg") {
      message("Fehler: Das Logo muss im .JPG Format vorliegen. ","error");
      include("../../footer.php");
      die();
    }
	
    $fid = $_REQUEST[profilid];
    $uploaddir = "$biz_imgpath/";
    $uploadfile =  "logo".$fid.".jpg"; 
	if (move_uploaded_file($_FILES['logo']['tmp_name'], $uploaddir . $uploadfile)) {
	 	@chmod($uploadfile, 0777);
		message($aenderungstext." und Logo erfolgreich hochgeladen!");
		$success=true;
	} else {
		message($aenderungstext.", jedoch gab es einen Fehler beim Upload des Logos!","error");
		$success=false;
	}
  }else{
		message($aenderungstext);
  }
}


$res = $db->query("select profilid,idprefix,idsuffix,profil,firma,inhaber,strasse,plz,ort,telefon,fax,mail,homepage,bankinhaber,bankinstitut,
                   bankblz,bankkonto,bankiban,bankbic,steuerid,umsatzsteuerid,kundenmenue,datumprefix,logo_h,logo_w from biz_profile where adminid='$_SESSION[adminid]' and profilid='$_REQUEST[profilid]'");
$row = $db->fetch_array($res);



?>



<form enctype="multipart/form-data" action="module/biz/firmen_profil_editieren.php?update=true&profilid=<?=$_REQUEST[profilid]?>" method="post">
<table width="540" border="0" cellspacing="0" cellpadding="0">
<tr bgcolor="#cccccc" align="left" valign="top">
<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr bgcolor="#e7e7e7" align="left" valign="top">
  <td colspan="2"><b>Profil editieren</b></td>
</tr>
<tr>
  <td bgcolor="#ffffff">Profil</td>
  <td bgcolor="#ffffff"><?=$row[profil]?></td>
</tr>
<tr>
  <td bgcolor="#ffffff">Firma</td>
  <td bgcolor="#ffffff"><input type="text" name="firma" value="<?=$row[firma]?>" size="50"></td>
</tr>
<tr>
  <td bgcolor="#ffffff">Inhaber</td>
  <td bgcolor="#ffffff"><input type="text" name="inhaber" value="<?=$row[inhaber]?>" size="50"></td>
</tr>
<tr>
  <td bgcolor="#ffffff">Strasse</td>
  <td bgcolor="#ffffff"><input type="text" name="strasse" value="<?=$row[strasse]?>" size="50"></td>
</tr>
<tr>
  <td bgcolor="#ffffff">Plz</td>
  <td bgcolor="#ffffff"><input type="text" name="plz" size="5" value="<?=$row[plz]?>" size="50"></td>
</tr>
<tr>
  <td bgcolor="#ffffff">Ort</td>
  <td bgcolor="#ffffff"><input type="text" name="ort" value="<?=$row[ort]?>" size="50"></td>
</tr>
<tr>
  <td bgcolor="#ffffff">E-Mail</td>
  <td bgcolor="#ffffff"><input type="text" name="mail" value="<?=$row[mail]?>" size="50"></td>
</tr>
<tr>
  <td bgcolor="#ffffff">Homepage</td>
  <td bgcolor="#ffffff"><input type="text" name="homepage" value="<?=$row[homepage]?>" size="50"></td>
</tr>
<tr>
  <td bgcolor="#ffffff">Telefon</td>
  <td bgcolor="#ffffff"><input type="text" name="telefon" value="<?=$row[telefon]?>" size="50"></td>
</tr>
<tr>
  <td bgcolor="#ffffff">Fax</td>
  <td bgcolor="#ffffff"><input type="text" name="fax" value="<?=$row[fax]?>" size="50"></td>
</tr>
<tr>
  <td bgcolor="#ffffff">Kontoinhaber</td>
  <td bgcolor="#ffffff"><input type="text" name="bankinhaber" value="<?=$row[bankinhaber]?>" size="50"></td>
</tr>
<tr>
  <td bgcolor="#ffffff">Geldinstitut</td>
  <td bgcolor="#ffffff"><input type="text" name="bankinstitut" value="<?=$row[bankinstitut]?>" size="50"></td>
</tr>
<tr>
  <td bgcolor="#ffffff">Bankleitzahl</td>
  <td bgcolor="#ffffff"><input type="text" name="bankblz" value="<?=$row[bankblz]?>" size="50"></td>
</tr>
<tr>
  <td bgcolor="#ffffff">Kontonummer</td>
  <td bgcolor="#ffffff"><input type="text" name="bankkonto" value="<?=$row[bankkonto]?>" size="50"></td>
</tr>
<tr>
  <td bgcolor="#ffffff">IBAN</td>
  <td bgcolor="#ffffff"><input type="text" name="bankiban" value="<?=$row[bankiban]?>" size="50"></td>
</tr>
<tr>
  <td bgcolor="#ffffff">BIC</td>
  <td bgcolor="#ffffff"><input type="text" name="bankbic" value="<?=$row[bankbic]?>" size="50"></td>
</tr>
<tr>
  <td bgcolor="#ffffff">SteuerID</td>
  <td bgcolor="#ffffff"><input type="text" name="steuerid" value="<?=$row[steuerid]?>" size="50"></td>
</tr>
<tr>
  <td bgcolor="#ffffff">UmsatzSteuerID</td>
  <td bgcolor="#ffffff"><input type="text" name="umsatzsteuerid" value="<?=$row[umsatzsteuerid]?>" size="50"></td>
</tr>
<tr>
  <td bgcolor="#ffffff">Logo</td>
  <td bgcolor="#ffffff"><input type="file" name="logo"></td>
</tr>
<tr>
  <td bgcolor="#ffffff">Größe des Logo im Pdf-Dokument</td>
  <td bgcolor="#ffffff">H: <input type="text" name="logo_h" maxlength="2" size="2" value="<?=$row[logo_h]?>">&nbsp; B:<input type="text" name="logo_w" maxlength="2" size="2" value="<?=$row[logo_w]?>"></td>
</tr>
<tr>
  <td bgcolor="#ffffff" valign="top">Logodarstellung</td>
  <td bgcolor="#ffffff"><img src="module/biz/img/logo<?=$row[profilid]?>.jpg" width="<?=$row[logo_w]*3?>" height="<?=$row[logo_h]*3?>" border="0" alt="Logo"></td>
</tr>
<tr>
  <td bgcolor="#ffffff">Kundenmenü URL</td>
  <td bgcolor="#ffffff"><input type="text" name="kundenmenue" value="<?=$row[kundenmenue]?>" size="50"></td>
</tr>
<tr>
  <td bgcolor="#ffffff">RechnungNr Prefix</td>
  <td bgcolor="#ffffff"><input type="text" name="idprefix" value="<?=$row[idprefix]?>" size="50"></td>
</tr>
<tr>
  <td bgcolor="#ffffff">RechnungNr Suffix</td>
  <td bgcolor="#ffffff"><input type="text" name="idsuffix" value="<?=$row[idsuffix]?>" size="50"></td>
</tr>
<tr>
  <td bgcolor="#ffffff">Datum Prefix</td>
  <td bgcolor="#ffffff"><input type="text" name="datumprefix" value="<?=$row[datumprefix]?>" size="50"></td>
</tr>
<tr>
  <td bgcolor="#ffffff">&nbsp;</td>
  <td bgcolor="#ffffff"><input type="submit" value="Speichern"></td>
</tr>
</table>


</td>
</tr>
</table>

</form>


<br>
<br>



<?include("../../footer.php");?>
