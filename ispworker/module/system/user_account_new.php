<?
$module = basename(dirname(__FILE__));
include("../../header.php");
include("inc/config.inc.php");

if($_SESSION[adminid]!=1) {
	die("Bitte loggen Sie sich als Superuser (id 1) ein.");
	include("../../footer.php");
}

?>

<b>Neuer Account</b><br>
<br>
<?

if($_REQUEST['new']=='true') {
    if($_REQUEST[username]=="" or $_REQUEST[passwort]=="" or count($_REQUEST[mods])==0) { die("Fehler bei der Eingabe."); }

    $d = dir(CONF_MODULEPATH);
    while($entry=$d->read()) {
	if($entry!="." and $entry!="..") {
	    $num++;	
	}						
    }

    if($num==count($_REQUEST[mods])) { $modstr = "*"; } else {

	for($i=0;$i<count($_REQUEST[mods]);$i++) {
    	    $modstr .=  $_REQUEST[mods][$i].",";
	}
    }


    $db->query("insert into adminaccounts (userid,passwort,mailadresse,modules,modulestart) values ('$_REQUEST[username]','".sha1($_REQUEST[passwort])."','$_REQUEST[mailad]','$modstr','$_REQUEST[modulestart]')");
    message("Account ist gespeichert.");
}

?>

<form action="module/system/user_account_new.php?new=true" method="post">
<table border="0" cellspacing="0" cellpadding="0">
<tr bgcolor="#cccccc">
<td>

<table width="600" border="0" cellspacing="1" cellpadding="3">
<tr bgcolor="#e7e7e7">
  <td colspan="2"><b>Neuer Account</b></td>
</tr>
<tr bgcolor="#ffffff">
  <td width="200">Username</td>
  <td><input type="text" name="username"></td>
</tr>
<tr bgcolor="#ffffff">
  <td>Passwort</td>
  <td><input type="text" name="passwort"></td>
</tr>
<tr bgcolor="#ffffff">
  <td>Mail Adresse</td>
  <td><input type="text" name="mailad"></td>
</tr>
<tr bgcolor="#ffffff">
  <td valign="top">Module</td>
  <td>
<?
  $d = dir(CONF_MODULEPATH);
  while($entry=$d->read()) {
    if($entry!="." and $entry!="..") {
	echo "<input type=\"checkbox\" name=\"mods[]\" value=\"$entry\"> $entry<br>";	
    }						
  }		      
?>
  </td>
</tr>
<tr bgcolor="#ffffff">
  <td valign="top">Startmodul</td>
  <td><select name="modulestart">
<?
  $d = dir(CONF_MODULEPATH);
  while($entry=$d->read()) {
    if($entry!="." and $entry!="..") {
	echo "<option value=\"$entry\"> $entry</option>";	
    }						
  }		      
?>
    </select>
  </td>
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