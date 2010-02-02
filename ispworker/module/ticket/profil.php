<?
$module = basename(dirname(__FILE__));
include("../../header.php");

?>

<span class="htitle">Mein Profil</span><br>
<br>


<?


if($_REQUEST[save]==true) {

	$res = $db->query("select * from ticket_personenprofile where pprofilid='$_SESSION[adminid]'");
	if($db->num_rows($res)==0) {
		$db->query("insert into ticket_personenprofile (pprofilid,name,email,notify,signatur)
					values ('$_SESSION[adminid]','$_REQUEST[name]','$_REQUEST[email]','$_REQUEST[notify]','$_REQUEST[signatur]')");
	}
	else {
		$db->query("update ticket_personenprofile set name='$_REQUEST[name]',email='$_REQUEST[email]',notify='$_REQUEST[notify]',signatur='$_REQUEST[signatur]' where pprofilid='$_SESSION[adminid]'");
	}


}


$res = $db->query("select * from ticket_personenprofile where pprofilid='$_SESSION[adminid]'");
$row = $db->fetch_array($res);

?>

<form action="module/ticket/profil.php?save=true" method="post">
<table border="0" cellspacing="0" cellpadding="0">
<tr bgcolor="#cccccc">
<td>

<table width="600" border="0" cellspacing="1" cellpadding="3">
<tr bgcolor="#e7e7e7">
  <td colspan="2"><b>Profildaten</b></td>
</tr>
<tr bgcolor="#ffffff">
  <td width="250">Name</td>
  <td><input type="text" name="name" value="<?=$row[name]?>"></td>
</tr>
<tr bgcolor="#ffffff">
  <td>Mail Adresse</td>
  <td><input type="text" name="email" value="<?=$row[email]?>"></td>
</tr>
<tr bgcolor="#ffffff">
  <td>Benachrichtigung</td>
  <td><?if($row[notify]=="Y") { $checked = " checked"; } else { $checked = ""; }?>
  <input type="checkbox" name="notify" value="Y" <?=$checked?>> Bei neuen Anfragen per Mail benachrichitgen</td>
</tr>
<tr bgcolor="#ffffff">
  <td>Signatur</td>
  <td><textarea name="signatur" cols="50" rows="5"><?=$row[signatur]?></textarea></td>
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