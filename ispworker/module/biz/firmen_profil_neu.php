<?
$module = basename(dirname(__FILE__));
include("../../header.php");
?>


<?
if(isset($_REQUEST['new'])) {

  if($_FILES['logo']['type']!="image/jpeg" && $_FILES['logo']['type']!="image/pjpeg") {
    message("Fehler: Das Logo muss im .JPG Format vorliegen. ","error");
    include("../../footer.php");
    die();
  }



  $db->query("insert into biz_profile (adminid,profil,firma,inhaber,strasse,plz,ort,mail,homepage,telefon,fax,bankinhaber,bankkonto,bankinstitut,bankblz,bankiban,bankbic,umsatzsteuerid,steuerid,kundenmenue,idprefix,idsuffix,datumprefix,logo_h,logo_w)
              values ('$_SESSION[adminid]','$_REQUEST[profil]','$_REQUEST[firma]','$_REQUEST[inhaber]','$_REQUEST[strasse]','$_REQUEST[plz]','$_REQUEST[ort]','$_REQUEST[mail]','$_REQUEST[homepage]','$_REQUEST[telefon]','$_REQUEST[fax]','$_REQUEST[bankinhaber]','$_REQUEST[bankkonto]','$_REQUEST[bankinstitut]','$_REQUEST[bankblz]','$_REQUEST[bankiban]','$_REQUEST[bankbic]','$_REQUEST[umsatzsteuerid]','$_REQUEST[steuerid]','$_REQUEST[kundenmenue]','$_REQUEST[idprefix]','$_REQUEST[idsuffix]','$_REQUEST[datumprefix]','$_REQUEST[logo_h]','$_REQUEST[logo_w]')");
  $fid = $db->insert_id();

 $db->query("INSERT INTO `biz_layout` ( `adminid` , `profilid` , `Feld1` , `Feld2` , `Feld3` , `Feld4` , `Feld5` , `Feld6` , `Feld7` , `Feld8` , `Feld9` , `Feld10` , `Feld11` , `Feld12` , `Feld13` , `Feld14` , `Feld15` , `Feld16` , `Feld17` , `Feld18` , `Feld19`)
VALUES ('$_SESSION[adminid]', '$fid', '#firma#', '#inhaber#', '#strasse#', '#plz#', '#ort#', '#telefon#', '#fax#', '#homepage#', '#mail#', '#firma# - #inhaber# - #strasse# - #plz# #ort#', '#bankinstitut#', '#bankkonto#', '#bankblz#', '#bankiban#', '#bankbic#', 'hrb 14545 ag Dortmund, Amtsgericht Dortmund', '#umsatzsteuerid#', '#steuerid#', '#inhaber#')");

  $uploaddir = $biz_imgpath."/";
  $uploadfile = $uploaddir . "logo".$fid.".jpg";
  copy($_FILES['logo']['tmp_name'], $uploadfile);
  chmod($uploadfile, 0777);

  message("Profil ist gespeichert.");
}
?>



<form enctype="multipart/form-data" action="module/biz/firmen_profil_neu.php?new=true" method="post">

<table width="540" border="0" cellspacing="0" cellpadding="0">
<tr bgcolor="#cccccc" align="left" valign="top">
<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr bgcolor="#e7e7e7" align="left" valign="top">
  <td colspan="2"><b>Neues Profil</b></td>
</tr>
<tr>
  <td bgcolor="#ffffff">Profil</td>
  <td bgcolor="#ffffff"><input type="text" name="profil"></td>
</tr>
<tr>
  <td bgcolor="#ffffff">Firma</td>
  <td bgcolor="#ffffff"><input type="text" name="firma" size="50"></td>
</tr>

<tr>
  <td bgcolor="#ffffff">Inhaber</td>
  <td bgcolor="#ffffff"><input type="text" name="inhaber" size="50"></td>
</tr>
<tr>
  <td bgcolor="#ffffff">Strasse</td>
  <td bgcolor="#ffffff"><input type="text" name="strasse" size="50"></td>
</tr>
<tr>
  <td bgcolor="#ffffff">Plz</td>
  <td bgcolor="#ffffff"><input type="text" name="plz" size="5"></td>
</tr>
<tr>
  <td bgcolor="#ffffff">Ort</td>
  <td bgcolor="#ffffff"><input type="text" name="ort" size="50"></td>
</tr>
<tr>
  <td bgcolor="#ffffff">E-Mail</td>
  <td bgcolor="#ffffff"><input type="text" name="mail" size="50"></td>
</tr>
<tr>
  <td bgcolor="#ffffff">Homepage</td>
  <td bgcolor="#ffffff"><input type="text" name="homepage" size="50"></td>
</tr>
<tr>
  <td bgcolor="#ffffff">Telefon</td>
  <td bgcolor="#ffffff"><input type="text" name="telefon" size="50"></td>
</tr>
<tr>
  <td bgcolor="#ffffff">Fax</td>
  <td bgcolor="#ffffff"><input type="text" name="fax" size="50"></td>
</tr>
<tr>
  <td bgcolor="#ffffff">Kontoinhaber</td>
  <td bgcolor="#ffffff"><input type="text" name="bankinhaber" size="50"></td>
</tr>
<tr>
  <td bgcolor="#ffffff">Bank</td>
  <td bgcolor="#ffffff"><input type="text" name="bankinstitut" size="50"></td>
</tr>
<tr>
  <td bgcolor="#ffffff">BLZ</td>
  <td bgcolor="#ffffff"><input type="text" name="bankblz" size="50"></td>
</tr>
<tr>
  <td bgcolor="#ffffff">Konto</td>
  <td bgcolor="#ffffff"><input type="text" name="bankkonto" size="50"></td>
</tr>
<tr>
  <td bgcolor="#ffffff">IBAN</td>
  <td bgcolor="#ffffff"><input type="text" name="bankiban" size="50"></td>
</tr>

<tr>
  <td bgcolor="#ffffff">BIC</td>
  <td bgcolor="#ffffff"><input type="text" name="bankbic" size="50"></td>
</tr>
<tr>
  <td bgcolor="#ffffff">SteuerID</td>
  <td bgcolor="#ffffff"><input type="text" name="steuerid" size="50"></td>
</tr>
<tr>
  <td bgcolor="#ffffff">UmsatzsteuerID</td>
  <td bgcolor="#ffffff"><input type="text" name="umsatzsteuerid" size="50"></td>
</tr>
<tr>
  <td bgcolor="#ffffff">Logo</td>
  <td bgcolor="#ffffff"><input type="file" name="logo"></td>
</tr>
<tr>
  <td bgcolor="#ffffff">Größe des Logo im Pdf-Dokument</td>
  <td bgcolor="#ffffff">H: <input type="text" name="logo_h" maxlength="2" size="2" value="">&nbsp; B:<input type="text" name="logo_w" maxlength="2" size="2" value=""></td>
</tr>
<tr>
  <td bgcolor="#ffffff">Kundenmenü URL</td>
  <td bgcolor="#ffffff"><input type="text" name="kundenmenue" size="50"></td>
</tr>
<tr>
  <td bgcolor="#ffffff">RechnungNr Prefix*</td>
  <td bgcolor="#ffffff"><input type="text" name="idprefix" size="50"></td>
</tr>
<tr>
  <td bgcolor="#ffffff">RechnungNr Suffix*</td>
  <td bgcolor="#ffffff"><input type="text" name="idsuffix" size="50"></td>
</tr>
<tr>
  <td bgcolor="#ffffff">Datum Prefix**</td>
  <td bgcolor="#ffffff"><input type="text" name="datumprefix" size="50"></td>
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
<font size="1">
* = Unterstützt die Ausgabe individueller Rechnungsnummern, z.B. RE242134 oder RE242134abc<br>
Dies ist rein kosmetischer Natur, intern arbeitet das System mit dem numerischen Wert 242134.<br>
<br>
** = Angabe eines Datumsformats (z.B. "Y" für aktuelles Jahr) als Prefix für die Rechnungsnummer.<br>
Die Ausgabe des Datums-Prefixes erfolgt nur in den PDF Rechnungsdokumenten. Das Format muss valide sein,<br>
siehe PHP4 date() Funktion.
</font>
<br>
<br>








<?include("../../footer.php");?>
