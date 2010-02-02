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

$bgcolor[4]   = "#ffffff";
$linecolor[4] = "#ffffff";

include("./inc/reiter2.php");



if($_REQUEST['new']=="true") {
	if($_REQUEST[registrarpasswd1]!=$_REQUEST[registrarpasswd2]) { die("Passwörter stimmen nicht überein."); }
	if($_REQUEST[registrarname]=="")					 { die("Registrarname fehlt."); }
	$db->query("insert into biz_domainregistrare (name,url,benutzer,passwort,klasse) values('$_REQUEST[registrarname]','$_REQUEST[registrarurl]','$_REQUEST[registraruser]','$_REQUEST[registrarpasswd1]','$_REQUEST[klasse]')");

	$rid = $db->insert_id();
	
	if($_REQUEST[standard]=="1") {
	    $db->query("update biz_domainregistrare set standard='0'");
    	    $db->query("update biz_domainregistrare set standard='1' where dregid='$rid'");
	}
    
	message("Registrardaten sind gespeichert.");

}


if($_REQUEST[update]=="true") {
	if($_REQUEST[registrarpasswd1]!=$_REQUEST[registrarpasswd2]) { die("Passwörter stimmen nicht überein."); }
	if($_REQUEST[registrarname]=="") { die("Registrarname fehlt."); }
	$db->query("update biz_domainregistrare set name='$_REQUEST[registrarname]', url='$_REQUEST[registrarurl]', benutzer='$_REQUEST[registraruser]', passwort='$_REQUEST[registrarpwd1]', klasse='$_REQUEST[klasse]' where dregid='$_REQUEST[dregid]'");

	if($_REQUEST[standard]=="1") {
	    $db->query("update biz_domainregistrare set standard='0'");
	    $db->query("update biz_domainregistrare set standard='1' where dregid='$_REQUEST[dregid]'");
	}else{
	    $db->query("update biz_domainregistrare set standard='0'");
	}

	message("Ihre Eingaben sind gespeichert.");

}

if($_REQUEST['delete']=="true") {
    $res = $db->query("select count(*) as anz from biz_domains where registrar='$_REQUEST[dregid]'");
    $row = $db->fetch_array($res);
    
    if($row[anz] > 0) die("<font color=\"red\">Fehler: $row[anz] Domains verweisen noch auf den zu löschenden Registar.</font><br><br>");
    else {
	$db->query("delete from biz_domainregistrare where dregid='$_REQUEST[dregid]'");
    }

}




?>


<table width="640" border="0" cellspacing="0" cellpadding="0">
<tr bgcolor="#cccccc">
<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr bgcolor="#e7e7e7">
  <td colspan="2"><b>Domainregistrare</b></td>
</tr>
<?
$res = $db->query("select * from biz_domainregistrare");
while($row = $db->fetch_array($res)) {
?>
<tr>
  <td bgcolor="#ffffff"><a href="module/biz/einstellungen_domainregistrare.php?edit=true&dregid=<?=$row[dregid]?>"><?=$row[name]?></a> <?if($row[standard]=="1") echo "(Standard)";?></td>
  <td width="16" bgcolor="#ffffff"><a href="module/biz/einstellungen_domainregistrare.php?delete=true&dregid=<?=$row[dregid]?>" onclick="return confirm('Möchten Sie den Datensatz wirklich löschen?');"><img src="img/trash.gif" border="0"></a></td>

</tr>
<?
}
?>
</table>


</td>
</tr>
</table>


<br>

<font size="1"><b>&raquo; <a href="module/biz/einstellungen_domainregistrare.php?addreg=true">Registrar hinzufügen</b></a></font>
<br>


<?if($_REQUEST[edit]=="true") {

$res = $db->query("select * from biz_domainregistrare where dregid='$_REQUEST[dregid]'");
$row = $db->fetch_array($res);


?>

<br>

<form action="module/biz/einstellungen_domainregistrare.php?update=true&dregid=<?=$row[dregid]?>" method="post">
<table width="640" border="0" cellspacing="0" cellpadding="0">
<tr bgcolor="#cccccc">
<td>


<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr bgcolor="#e7e7e7">
  <td colspan="2"><b>Registrar bearbeiten</b></td>
</tr>
<tr>
  <td width="200" bgcolor="#ffffff">Name / Firma</td>
  <td bgcolor="#ffffff"><input type="text" name="registrarname" value="<?=$row[name]?>"></td>
</tr>
<tr>
  <td bgcolor="#ffffff">Kundenmenü URL</td>
  <td bgcolor="#ffffff"><input type="text" name="registrarurl" value="<?=$row[url]?>"></td>
</tr>
<tr>
  <td bgcolor="#ffffff">Benutzername</td>
  <td bgcolor="#ffffff"><input type="text" name="registraruser" value="<?=$row[benutzer]?>"></td>
</tr>
<tr>
  <td bgcolor="#ffffff">Passwort</td>
  <td bgcolor="#ffffff"><input type="password" name="registrarpasswd1"></td>
</tr>
<tr>
  <td bgcolor="#ffffff">Passwort Wiederholung</td>
  <td bgcolor="#ffffff"><input type="password" name="registrarpasswd2"></td>
</tr>
<tr>
  <td bgcolor="#ffffff">Standard</td>
  <td bgcolor="#ffffff"><input type="radio" name="standard" value="1" <?if($row[standard]=="1") echo "checked";?>> Ja <input type="radio" name="standard" value="0" <?if($row[standard]=="0") echo "checked";?>> Nein</td>
</tr>
<tr>
  <td bgcolor="#ffffff">Schnittstellen-Klasse</td>
  <td bgcolor="#ffffff">
  <select name="klasse">
  <option value="">---Keine Klasse---</option>
  <?
  $d = dir(dirname(__FILE__)."/inc/");
  while($entry=$d->read()) {
    if($entry!="." and $entry!="..") {
	if(strstr($entry,"class.domreg.")) {
	    $x = explode("class.domreg.",$entry);
	    $y = explode(".php",$x[1]);
	    $class = $y[0];
	    echo "<option value=\"$class\""; 
	    if($row[klasse]==$class) echo " selected";
	    echo ">$class</option>";
	}
    }
  } 
  $d->close();   	
?>
  </select>
  </td>
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


<?}?>


<br>

<?if($_REQUEST[addreg]=="true") {?>

<form action="module/biz/einstellungen_domainregistrare.php?new=true" method="post">
<table width="640" border="0" cellspacing="0" cellpadding="0">
<tr bgcolor="#cccccc" align="left" valign="top">
<td>


<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr bgcolor="#e7e7e7" align="left" valign="top">
  <td colspan="2"><b>Neuer Registrar</b></td>
</tr>
<tr>
  <td width="200" bgcolor="#ffffff">Name / Firma</td>
  <td bgcolor="#ffffff"><input type="text" name="registrarname"></td>
</tr>
<tr>
  <td bgcolor="#ffffff">Kundenmenü URL</td>
  <td bgcolor="#ffffff"><input type="text" name="registrarurl" value="http://"></td>
</tr>
<tr>
  <td bgcolor="#ffffff">Benutzername</td>
  <td bgcolor="#ffffff"><input type="text" name="registraruser"></td>
</tr>
<tr>
  <td bgcolor="#ffffff">Passwort</td>
  <td bgcolor="#ffffff"><input type="password" name="registrarpasswd1"></td>
</tr>
<tr>
  <td bgcolor="#ffffff">Passwort Wiederholung</td>
  <td bgcolor="#ffffff"><input type="password" name="registrarpasswd2"></td>
</tr>
<tr>
  <td bgcolor="#ffffff">Standard</td>
  <td bgcolor="#ffffff"><input type="radio" name="standard" value="1"> Ja <input type="radio" name="standard" value="0" checked> Nein</td>
</tr>
<tr>
  <td bgcolor="#ffffff">Schnittstellen-Klasse</td>
  <td bgcolor="#ffffff">
  <select name="klasse">
  <option value="">---Keine Klasse---</option>
  <?
  $d = dir(dirname(__FILE__)."/inc/");
  while($entry=$d->read()) {
    if($entry!="." and $entry!="..") {
	if(strstr($entry,"class.domreg.")) {
	    $x = explode("class.domreg.",$entry);
	    $y = explode(".php",$x[1]);
	    $class = $y[0];
	    echo "<option value=\"$class\">$class</option>";
	}
    }
  }    	
  $d->close();
?>
  </select>
  </td>
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

<?}?>

<br>
<br>



<?include("../../footer.php");?>
