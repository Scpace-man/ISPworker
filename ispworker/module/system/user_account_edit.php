<?
$module = basename(dirname(__FILE__));
include("../../header.php");
include("inc/config.inc.php");


if($_SESSION[adminid]!=1) {
	die("Bitte loggen Sie sich als Superuser (id 1) ein.");
	include("../../footer.php");
}

?>

<b>Account editieren</b><br>
<br>
<?


if($_REQUEST[update]=="true" && $system_userloeschbar!=false) 
{
	if($_REQUEST[username]=="" or $_REQUEST[mods]=="") { die("Fehler bei der Eingabe."); }


	$d = dir(CONF_MODULEPATH);
        while($entry=$d->read()) {
	    if($entry!="." and $entry!="..") {
	        $num++;
	    }
	}
	
	if($num==count($_REQUEST[mods])) { $modstr = "*"; } else 
	{
	    for($i=0;$i<count($_REQUEST[mods]);$i++) {
	        $modstr .=  $_REQUEST[mods][$i].",";
	    }
	}

	if($_REQUEST[passwort]=="") {
	    $db->query("update adminaccounts set userid='$_REQUEST[username]',mailadresse='$_REQUEST[mailad]',modules='$modstr', modulestart='$_REQUEST[modulestart]' where adminid='$_REQUEST[tadminid]'");
	}
	else {
	    $db->query("update adminaccounts set userid='$_REQUEST[username]',passwort='".sha1($_REQUEST[passwort])."',mailadresse='$_REQUEST[mailad]',modules='$modstr', modulestart='$_REQUEST[modulestart]' where adminid='$_REQUEST[tadminid]'");	
	}

	message("Account ist gespeichert.");
}


$res = $db->query("select * from adminaccounts where adminid='$_REQUEST[tadminid]'");
$row = $db->fetch_array($res);
?>

<form action="module/system/user_account_edit.php?update=true&tadminid=<?=$_REQUEST[tadminid]?>" method="post">
<table border="0" cellspacing="0" cellpadding="0">
<tr bgcolor="#cccccc">
<td>

<table width="600" border="0" cellspacing="1" cellpadding="3">
<tr bgcolor="#e7e7e7">
  <td colspan="2"><b>Account editieren</b></td>
</tr>
<tr bgcolor="#ffffff">
  <td width="200">Username</td>
  <td><input type="text" name="username" value="<?=$row[userid]?>"></td>
</tr>
<tr bgcolor="#ffffff">
  <td>Neues Passwort</td>
  <td><input type="text" name="passwort" value=""> <font size="1">(optional)</font></td>
</tr>
<tr bgcolor="#ffffff">
  <td>Mail Adresse</td>
  <td><input type="text" name="mailad" value="<?=$row[mailadresse]?>"></td>
</tr>
<tr bgcolor="#ffffff">
  <td valign="top">Module</td>
    <td>
    <?
    $d = dir(CONF_MODULEPATH);
    while($entry=$d->read()) {
	if($entry!="." and $entry!="..") 
	{
	    if(strstr($row[modules],"$entry") or $row[modules]=="*") $c = "checked"; else $c = "";
	    echo "<input type=\"checkbox\" name=\"mods[]\" value=\"$entry\" $c> $entry</input><br>";
	}
    }
    ?>
    </td>
</tr>
<tr bgcolor="#ffffff">
  <td valign="top">Startmodul</td>
    <td>
    <select name="modulestart">
    <?
    $d = dir(CONF_MODULEPATH);
    while($entry=$d->read()) {
	if($entry!="." and $entry!="..") {
	    if($row[modulestart] == $entry) $s = " selected"; else $s = "";
	    echo "<option value=\"$entry\" $s> $entry</option>";
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