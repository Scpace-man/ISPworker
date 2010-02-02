<?
$module = basename(dirname(__FILE__));
include("../../header.php");
?>
<span class="htitle">Einstellungen</span><br>
<br>


<?
include("./inc/reiter2.layout.php");

$bgcolor[0]   = "#f0f0f0";
$linecolor[0] = "#000000";

$bgcolor[3]   = "#ffffff";
$linecolor[3] = "#ffffff";

include("./inc/reiter2.php");
?>


<b>Mail Templates</b><br>
<br>
&raquo; <a href="module/biz/einst_mailtemplates.php?new=true">Neues Mail Template</a><br>
<br>
<?

if($_REQUEST[add]=="true") {
    if($_REQUEST[templatename]=="" or $_REQUEST[mailbetreff]=="" or $_REQUEST[mailtext]=="") { 
	message("Fehler bei der Eingabe.","error"); 
	include("../../footer.php"); die();
    }
    $db->query("insert into biz_mailtemplates (templatename,mailbetreff,mailtext) values ('$_REQUEST[templatename]','$_REQUEST[mailbetreff]','$_REQUEST[mailtext]')");

    message("Template ist gespeichert.");
}

if($_REQUEST[saveedit]=="true") {
    if($_REQUEST[templatename]=="" or $_REQUEST[mailbetreff]=="" or $_REQUEST[mailtext]=="") { 
	message("Fehler bei der Eingabe.","error"); 
	include("../../footer.php"); die();
    }


    $db->query("update biz_mailtemplates set templatename='$_REQUEST[templatename]',mailbetreff='$_REQUEST[mailbetreff]',mailtext='".strip_cr($_REQUEST[mailtext])."' where templateid='$_REQUEST[templateid]'");
    message("Template ist gespeichert");
}

if($_REQUEST[del]=="true") {
    if(strstr($_REQUEST[tname],"std_")) {
	message("Fehler: Standard Templates dürfen nicht gelöscht werden.","error"); 
 
	include("../../footer.php"); die();
    }
    $db->query("delete from biz_mailtemplates where templateid='$_REQUEST[templateid]'");
    message("Template ist gelöscht");
}


?>


<table border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>

<table width="700" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
<td><b>Template Name</b></td>
<td colspan="2"><b>Aktion</b></td>
</tr>

<?
$res = $db->query("select * from biz_mailtemplates");
while($row = $db->fetch_array($res)) {
?>
<tr class="tr">
<td><?=$row[templatename]?></td>
<td width="16"><a href="module/biz/einst_mailtemplates.php?edit=true&templateid=<?=$row[templateid]?>"><img alt="Bearbeiten" src="img/edit.gif" border="0" alt="Bearbeiten"></a></td>
<td width="16"><a href="module/biz/einst_mailtemplates.php?del=true&templateid=<?=$row[templateid]?>&tname=<?=$row[templatename]?>"><img alt="Bearbeiten" src="img/trash.gif" border="0" alt="Löschen"></a></td>
</tr>
<?
}
?>


</table>

</td>
</tr>
</table>
<br>

<?if($_REQUEST["new"]=="true") {?>
<form action="module/biz/einst_mailtemplates.php?add=true" method="post">

<table border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>

<table width="700" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
<td colspan="2"><b>Neues Mail Template</b></td>
</tr>
<tr class="tr">
  <td>Template Name</td>
  <td><input type="text" name="templatename" class="input-text"></td>
</tr>
<tr class="tr">
  <td>Mail Betreff</td> 
  <td><input type="text" name="mailbetreff" style="width:400px;"></td>
</tr>
<tr class="tr">
  <td>Mail Text</td>
  <td><textarea name="mailtext" wrap=off style="width:600px; height: 300px;"></textarea></td>
</tr>
<tr class="tr">
  <td></td> 
  <td><input type="submit" value="Speichern"></td>
</tr>
</table>

</td>
</tr>
</table>

</form>
<?}?> 



<?if($_REQUEST[edit]=='true') {
$res = $db->query("select * from biz_mailtemplates where templateid='$_REQUEST[templateid]'");
$row = $db->fetch_array($res);

?>
<form action="module/biz/einst_mailtemplates.php?saveedit=true&templateid=<?=$row[templateid]?>" method="post">

<table border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>

<table width="700" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
<td colspan="2"><b>Mail Template bearbeiten</b></td>
</tr>
<tr class="tr">
  <td>Template Name</td>
  <td><input type="text" name="templatename" value="<?=$row[templatename]?>" class="input-text"></td>
</tr>
<tr class="tr">
  <td>Mail Betreff</td> 
  <td><input type="text" name="mailbetreff" value="<?=$row[mailbetreff]?>" style="width:400px;"></td>
</tr>
<tr class="tr">
  <td valign="top">Mail Text</td>
  <td><textarea name="mailtext" wrap=off style="width:600px; height: 300px;"><?=$row[mailtext]?></textarea></td>
</tr>
<tr class="tr">
  <td></td> 
  <td><input type="submit" value="Speichern"></td>
</tr>
</table>

</td>
</tr>
</table>

</form>
<?}?> 




<br>
<br>
Diese Templates müssen vorhanden sein:<br>
<br>
std_neuerechnung<br>
std_zugangsdaten<br>
std_bestellbestaetigung<br>
std_bestellbenachrichtigung<br>
<br>
<br>

Template std_neuerechnung<br>
<br>
#rechnungid#<br>
#kundenid#<br>
#anrede# (kompl. Anr. Sehr geehrte Frau Nachn. bzw. Sehr geehrter Herr Nachn.<br>
#profilkundenmenue# (URL zum Kundenmenü, im Firmenprofil einstellbar)<br>
<br>
Template std_zugangsdaten<br>
#benutzername#<br>
#passwort#<br>
#profilkundenmenue#<br>
<br>
Template std_bestellbestaetigung<br>
<br>
#anrede# ( Herr, Frau etc.)<br>
#vorname#<br>
#nachname#<br>
#kundendaten# (komplette Kundendaten)<br>
#zahlungsart#<br>
#produkte# (Zusammenfassung der bestellten Produkte)<br>
#kontodaten#<br>
#bestaetigungslink# (Provider erhält eine Bestätiguns-Mail)<br>
#bestelllink# (Link zum HTML Bestellformular, optional, im order Modul einstellbar)<br>
#signatur# (WebHoster Signatur, siehe order Modul)<br>
<br>
Template std_bestellbenachrichtigung<br>
<br>
#kundendaten#<br>
#zahlungsart#<br>
#produkte#<br>
#kontodaten#<br>
#bestelllink#<br>
#signatur#<br>
#jobs# (Links zu automatisierten , automatische DNS Reservierung etc.)<br>
#verbindungsdaten# (Verbindungsdaten des Kunden)<br>
<br>
Template std_confixxzugangsdaten<br>
<br>
#confixxurl#<br>
#confixxuser#<br>
#confixxpwd#<br>
<br>

<br>
<br>

<?include("../../footer.php");?>