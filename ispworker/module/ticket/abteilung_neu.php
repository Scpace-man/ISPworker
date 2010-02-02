<?
$module = basename(dirname(__FILE__));
include("../../header.php");

?>

<span class="htitle">Neue Abteilung</span><br>
<br>



<?

if($_REQUEST["new"]==true) {
	if($_REQUEST["pop3user"]=="" or $_REQUEST["pop3passwort"]=="" or $_REQUEST["pop3server"]=="" or $_REQUEST["bezeichnung"]=="" or $_REQUEST["mail"]=="") { die("Fehler bei der Eingabe."); }

	$db->query("insert into ticket_abteilungen (bezeichnung,mail,pop3user,pop3passwort,pop3server,copytomail)
			    values ('$_REQUEST[bezeichnung]','$_REQUEST[mail]','$_REQUEST[pop3user]','$_REQUEST[pop3passwort]','$_REQUEST[pop3server]','$_REQUEST[copytomail]')");

	echo "<br><font color=\"green\"><b>Account gespeichert.</b></font>";
}

?>

<form action="module/ticket/abteilung_neu.php?new=true" method="post">
<table border="0" cellspacing="0" cellpadding="0">
<tr bgcolor="#cccccc">
<td>

<table width="600" border="0" cellspacing="1" cellpadding="3">
<tr bgcolor="#e7e7e7">
  <td colspan="2"><b>Neue Abteilung</b></td>
</tr>
<tr bgcolor="#ffffff">
  <td width="250">Name der Abteilung</td>
  <td><input type="text" name="bezeichnung"></td>
</tr>
<tr bgcolor="#ffffff">
  <td>Mail Adresse</td>
  <td><input type="text" name="mail"></td>
</tr>
<tr bgcolor="#ffffff">
  <td>POP3 User</td>
  <td><input type="text" name="pop3user"></td>
</tr>
<tr bgcolor="#ffffff">
  <td>POP3 Passwort</td>
  <td><input type="text" name="pop3passwort"></td>
</tr>
<tr bgcolor="#ffffff">
  <td>POP3 Server</td>
  <td><input type="text" name="pop3server"></td>
</tr>
<tr bgcolor="#ffffff">
  <td>Kopie eingehender Mails an</td>
  <td><input type="text" name="copytomail"></td>
</tr>
<tr bgcolor="#ffffff">
  <td>&nbsp;</td>
  <td><input type="submit" value="Speichern"></td>
</tr>
</table>

</td>
</tr>
</table>
</form>

<br>


<?include("../../footer.php");?>