<?
$module = basename(dirname(__FILE__));
include("../../header.php");


if(isset($_REQUEST[update])) {

        if(CONF_ALLOWCHANGEPWD==false) { die("Passwort kann nicht geändert werden, bitte informieren Sie den Administrator."); }

	$anzahlzeichen = strlen($_REQUEST[neuespw1]); 
  	
	$res = $db->query("select passwort from biz_kunden where kundenid='$_SESSION[user]'");
	$row = $db->fetch_array($res);
	
	if ($_REQUEST[neuespw1] != $_REQUEST[neuespw2]) {
  		$fehler= true;
		$fehlercode = "<b>Fehler: Neueingegebene Passw&ouml;rter stimmen nicht &uuml;berein!</b>";
  	}
    if ($row[passwort] != sha1($_REQUEST[passwort])) {
  		$fehler= true;
		$fehlercode = "<b>Fehler: Eingabe des alten Passwortes nicht korrekt!</b>";
  	}
	if ($anzahlzeichen < "6" ) {
  		$fehler= true;
		$fehlercode = "<b>Fehler: Das Passwort muss mindestens aus 6 Zeichen bestehen!</b>";
  	}	
	
	if (!$fehler==true) {	
	$db->query("update biz_kunden set passwort='".sha1($_REQUEST[neuespw1])."' where kundenid='$_SESSION[user]'");
	echo "<b>&Auml;nderungen wurden gespeichert.</b><br><br>";
  	} 	else {
	echo "<b>$fehlercode</b><br><br>";
	}
}
  	?>




<form action="module/kundenmenue/passwort.php?update=true" method="post">
<table width="540" border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
  <td colspan="2"><b>Aktuelles Passwort &auml;ndern</b></td>
</tr>
<tr class="tr">
  <td>Aktuelles Passwort</td>
  <td><input type="password" name="passwort" size= "30"></td>
</tr>
<tr class="tr">
  <td>Neues Passwort</td>
  <td><input type="password" name="neuespw1" size= "30"></td>
</tr>
<tr class="tr">
  <td>Neues Passwort (Wiederholung)</td>
  <td><input type="password" name="neuespw2" size= "30"></td>
</tr>
<tr class="tr">
  <td>&nbsp;</td>
  <td><input type="submit" value="Passwort &auml;ndern"></td>
</tr>
</table>


</td>
</tr>
</table>

</form>








<?include("../../footer.php");?>
