<?
$module = basename(dirname(__FILE__));
include("../../header.php");

?>

<span class="htitle">Abteilung bearbeiten</span><br>
<br>


<br>
<?


if($_REQUEST["update"]==true) {
	if($_REQUEST["pop3user"]=="" or $_REQUEST["pop3passwort"]=="" or $_REQUEST["pop3server"]=="" or $_REQUEST["mail"]=="" or $_REQUEST["bezeichnung"]=="") { die("Fehler bei der Eingabe."); }

	$db->query("update ticket_abteilungen set mail='$_REQUEST[mail]', pop3user='$_REQUEST[pop3user]', pop3passwort='$_REQUEST[pop3passwort]', pop3server='$_REQUEST[pop3server]',
			    bezeichnung='$_REQUEST[bezeichnung]',copytomail='$_REQUEST[copytomail]' where abteilungid='$_REQUEST[abteilungid]'");

	echo "<br><font color=\"green\"><b>Abteilung gespeichert.</b></font>";
}


$res = $db->query("select * from ticket_abteilungen where abteilungid='$_REQUEST[abteilungid]'");
$row = $db->fetch_array($res);
?>

<form action="module/ticket/abteilung_edit.php?update=true&abteilungid=<?=$_REQUEST[abteilungid]?>" method="post">
<table border="0" cellspacing="0" cellpadding="0">
<tr bgcolor="#cccccc">
<td>

<table width="600" border="0" cellspacing="1" cellpadding="3">
<tr bgcolor="#e7e7e7">
  <td colspan="2"><b>Abteilung editieren</b></td>
</tr>
<tr bgcolor="#ffffff">
  <td width="250">Name</td>
  <td><input type="text" name="bezeichnung" value="<?=$row[bezeichnung]?>"></td>
</tr>
<tr bgcolor="#ffffff">
  <td>Mail Adresse</td>
  <td><input type="text" name="mail" value="<?=$row[mail]?>"></td>
</tr>
<tr bgcolor="#ffffff">
  <td>POP3 User</td>
  <td><input type="text" name="pop3user" value="<?=$row[pop3user]?>"></td>
</tr>

<tr bgcolor="#ffffff">
  <td>POP3 Passwort</td>
  <td><input type="text" name="pop3passwort" value="<?=$row[pop3passwort]?>"></td>
</tr>

<tr bgcolor="#ffffff">
  <td>POP3 Server</td>
  <td><input type="text" name="pop3server" value="<?=$row[pop3server]?>"></td>
</tr>
<tr bgcolor="#ffffff">
  <td>Kopie eingehender Mails an</td>
  <td><input type="text" name="copytomail" value="<?=$row[copytomail]?>"></td>
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