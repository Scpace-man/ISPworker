<?
$module = basename(dirname(__FILE__));
include("../../header.php");
include("./inc/functions.inc.php");
?>
<span class="htitle">Einstellungen</span><br>
<br>


<?
include("./inc/reiter2.layout.php");

$bgcolor[0]   = "#f0f0f0";
$linecolor[0] = "#000000";

$bgcolor[2]   = "#ffffff";
$linecolor[2] = "#ffffff";

include("./inc/reiter2.php");


if($_REQUEST["new"] == "true")
    $db->query("insert into biz_defaultserver (servername,benutzername,passwort,serverip,servertyp) values ('$_REQUEST[servername]','$_REQUEST[benutzername]','$_REQUEST[passwort]','$_REQUEST[serverip]','$_REQUEST[servertyp]')");


if($_REQUEST["delete"]=="true") 
{
    $res = $db->query("select count(*) as anz from biz_serveraccounts where serverid='$_REQUEST[serverid]'");
    $row = $db->fetch_array($res);
	
    if($row[anz] > 0) message("Fehler: $row[anz] Server-Accounts verweisen auf den Server Nr $_REQUEST[serverid]","error");
    else $db->query("delete from biz_defaultserver where serverid='$_REQUEST[serverid]'");
}

if($_REQUEST["update"] == "true" && $_REQUEST["delete"] != "true")
    $db->query("update biz_defaultserver set servername='$_REQUEST[servername]', serverip='$_REQUEST[serverip]', servertyp='$_REQUEST[servertyp]', benutzername='$_REQUEST[benutzername]', passwort='$_REQUEST[passwort]' where serverid='$_REQUEST[serverid]'");


?>


Richten Sie an dieser Stelle Standard Server ein, die Sie Ihren Kunden
zuordnen können.<br>
<br>

<?
$res = $db->query("select * from biz_defaultserver order by servertyp");
if($db->num_rows($res) > 0) {
?>

<table width="550" border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>


<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
  <td><b>Server Bezeichnung</b></td>
  <td><b>ServerIP</b></td>
  <td><b>ServerTyp</b></td>
  <td width="32" colspan="2"><b>Aktion</b></td>
</tr>
<?
while($row = $db->fetch_array($res)) {?>
<tr class="tr">
  <td><?=$row[servername]?></td>
  <td><?=$row[serverip]?></td>
  <td><?=$row[servertyp]?></td>
  <td width="16"><a href="module/biz/einst_server.php?edit=true&serverid=<?=$row[serverid]?>"><img src="img/edit.gif" alt="Bearbeiten" border="0"></a></td>
  <td width="16"><a href="module/biz/einst_server.php?delete=true&serverid=<?=$row[serverid]?>" onclick="return confirm('Möchten Sie den Datensatz wirklich löschen?');"><img src="img/trash.gif" alt="Löschen" border="0"></a></td>
</tr>
<?}?>
</table>

</td>
</tr>
</table>
<?}?>
<br>

<?if($_REQUEST[edit]=="true") {?>

<form action="module/biz/einst_server.php?update=true" method="post">
<table width="550" border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>


<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
  <td colspan="2"><b>Server editieren</td>
</tr>
<?
$res = $db->query("select * from biz_defaultserver where serverid='$_REQUEST[serverid]'");
$row = $db->fetch_array($res);
?>
<input type="hidden" name="serverid" value="<?=$row[serverid]?>">
<tr class="tr">
  <td width="170">Server Bezeichnung</font></td>
  <td><input type="text" name="servername" value="<?=$row[servername]?>" class="input-text"></td>
</tr>
<tr class="tr">
  <td>ServerIP</td>
  <td><input type="text" name="serverip" value="<?=$row[serverip]?>" class="input-text"></td>
</tr>
<tr class="tr">
  <td>ServerTyp</td>
  <td><input type="text" name="servertyp" value="<?=$row[servertyp]?>" class="input-text"></td>
</tr>
<tr class="tr">
  <td>Benutzername <font size="1">(optional)</font></td>
  <td><input type="text" name="benutzername" value="<?=$row[benutzername]?>" class="input-text"></td>
</tr>
<tr class="tr">
  <td>Passwort <font size="1">(optional)</font></td>
  <td><input type="text" name="passwort" value="<?=$row[passwort]?>" class="input-text"></td>
</tr>
</table>

</td>
</tr>
</table><br>
<input type="submit" value="Speichern">
</form>

<?}?>
<br>
<br>
<form action="module/biz/einst_server.php?new=true" method="post">
<table width="550" border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>


<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
  <td colspan="2"><b>Neuer Server</td>
</tr>
<tr class="tr">
  <td width="170">Server Bezeichnung</td>
  <td><input type="text" name="servername" class="input-text"></td>
</tr>
<tr class="tr">
  <td>IP Adresse / URL</td>
  <td><input type="text" name="serverip" class="input-text"></td>
</tr>
<tr class="tr">
  <td>Servertyp</td>
  <td><select name="servertyp">
  <option value="mail">Mail Host</option>
  <option value="web">Web Host</option>
  <option value="vserverhost">V-Server Host</option>
  <option value="confixx3">Confixx3</option>
  <option value="ispdns">ISPdns API URL</option>
  </select>
  </td>
</tr>
<tr class="tr">
  <td>Benutzername <font size="1">(optional)</font></td>
  <td><input type="text" name="benutzername" class="input-text"></td>
</tr>
<tr class="tr">
  <td>Passwort <font size="1">(optional)</font></td>
  <td><input type="text" name="passwort" class="input-text"></td>
</tr>
</table>

</td>
</tr>
</table><br>
<input type="submit" value="Speichern">
</form>

<br>
<br>
<br>


<?include("../../footer.php");?>
