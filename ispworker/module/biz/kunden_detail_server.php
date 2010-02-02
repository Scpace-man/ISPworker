<?
$module = basename(dirname(__FILE__));
include("./inc/functions.inc.php");
include("../../header.php");



include("./inc/reiter1.layout.php");
$bgcolor[0]   = "#f0f0f0";
$linecolor[0] = "#000000";

$bgcolor[4]   = "#ffffff";
$linecolor[4] = "#ffffff";
include("./inc/reiter1.php");




if($_REQUEST[newip]=="true") {
  $db->query("insert into biz_ipadressen (ipadresse,domain,zweck,zusatz,kundenid) values('$_REQUEST[ipadresse]','$_REQUEST[domain]','$_REQUEST[zweck]','$_REQUEST[zusatz]','$_REQUEST[kundenid]')");
}


if($_REQUEST[updateipsave]=="true") {
  $db->query("update biz_ipadressen set domain='$_REQUEST[domain]', zweck='$_REQUEST[zweck]', zusatz='$_REQUEST[zusatz]' where ipadresse='$_REQUEST[ipadresse]'");
}


if($_REQUEST[newserveraccount]=="true") {
  $db->query("insert into biz_serveraccounts (serverid,accountname,accountpwd,kundenid) values('$_REQUEST[serverid]','$_REQUEST[accountname]','$_REQUEST[accountpwd]','$_REQUEST[kundenid]')");
}


if($_REQUEST[delip]=="true") {
  trash("biz_ipadressen","where ipadresse='$_REQUEST[ipadresse]' and kundenid='$_REQUEST[kundenid]'");
}

if($_REQUEST[delserveraccount]=="true") {
    echo "<form action=\"module/biz/kunden_detail_server.php?delserveraccountnow=true&accountid=$_REQUEST[accountid]&kundenid=$_REQUEST[kundenid]\" method=\"post\">";
    echo "<input type=\"checkbox\" name=\"delconfixxaccount\" value=\"true\"> Confixx Account ebenfalls löschen. <input type=\"submit\" value=\"Jetzt löschen\">";
    echo "</form>";
    
}

if($_REQUEST[delserveraccountnow]=="true") {

  $res = $db->query("select * from biz_serveraccounts where accountid='$_REQUEST[accountid]' and kundenid='$_REQUEST[kundenid]'");
  $row = $db->fetch_array($res);

  $res = $db->query("select * from biz_defaultserver where serverid='$row[serveradminid]'");
  $server = $db->fetch_array($res);

    
  if($_REQUEST[delconfixxaccount]==true) 
  {
    if($server[servertyp]=="confixx3") 
    {
      if(strstr($row[accountname],"res")) { echo "Reseller Accounts können nur im Confixx Menü gelöscht werden.<br>"; }
      else 
      { 
        $mysid = confixx3_getsid($server[serverip], $server[benutzername], $server[passwort]);
	sleep(1);
        
	confixx3_anbieterloeschen("$server[serverip]","/reseller/$server[benutzername]/kunden_aendern_loeschen2.php","loeschen[".$row[accountname]."]=ja&SID=$mysid"); 
      }
    }
  }
  
  $db->query("delete from biz_serveraccounts where accountid='$_REQUEST[accountid]' and kundenid='$_REQUEST[kundenid]'");

  message("Account ist gelöscht.");

}


?>

<table width="540" border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
  <td colspan="6"><b>IP Adressen</b></td>
</tr>
<tr class="tr">
  <td><b>IP Adresse</b></td>
  <td><b>PTR</b></td>
  <td><b>Zusatz</b></td>
  <td><b>Zweck</b></td>  
  <td width="32" colspan="2"><b>Aktion</b</td>
</tr>

<?
  $resip = $db->query("select * from biz_ipadressen where kundenid='$_REQUEST[kundenid]'");
  while($rowip=$db->fetch_array($resip)) {
?>

<tr class="tr">
  <td><?=$rowip[ipadresse]?></td>
  <td><?=$rowip[domain]?></td>
  <td><?=$rowip[zusatz]?></td>
  <td><?=$rowip[zweck]?></td>
  <td><a href="module/biz/kunden_detail_server.php?updateip=true&ipadresse=<?=$rowip[ipadresse]?>&kundenid=<?=$_REQUEST[kundenid]?>"><img alt="Bearbeiten" src="img/edit.gif" border="0"></a></td>
  <td><a href="module/biz/kunden_detail_server.php?delip=true&ipadresse=<?=$rowip[ipadresse]?>&kundenid=<?=$_REQUEST[kundenid]?>"><img alt="Löschen" src="img/trash.gif" border="0"></a></td>
</tr>
<?
}
?>
</table>

</td>
</tr>
</table>
<br>

<br>
<?if($_REQUEST[updateip]=="true") {

$resip = $db->query("select * from biz_ipadressen where ipadresse='$_REQUEST[ipadresse]'");
$rowip = $db->fetch_array($resip);
?>

<form action="module/biz/kunden_detail_server.php?updateipsave=true" method="post">
<input type="hidden" name="kundenid" value="<?=$_REQUEST[kundenid]?>">
<table width="540" border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
  <td colspan="4"><b>IP Adressdaten ändern</b></td>
</tr>
<tr class="tr">
  <td>IP Adresse</td>
  <td><?=$rowip[ipadresse]?><input type="hidden" name="ipadresse" value="<?=$rowip[ipadresse]?>"></td>
</tr>
<tr class="tr">
  <td>rDNS Domain<br><font size="1">(optional)</font></td>
  <td><input type="text" name="domain" value="<?=$rowip[domain]?>"></td>
</tr>
<tr class="tr">
  <td>Zweck<br><font size="1">(optional)</font></td>
  <td><input type="text" name="zweck" value="<?=$rowip[zweck]?>"></td>
</tr>
<tr class="tr">
  <td>Zusatz<br><font size="1">(optional)</font></td>
  <td><input type="text" name="zusatz" value="<?=$rowip[zusatz]?>"></td>
</tr>

<tr class="tr">
  <td colspan="2"><input type="submit" value="Speichern"></td>
</tr>
</table>

</td>
</tr>
</table>
</form>
<br>

<?} else {?>


<form action="module/biz/kunden_detail_server.php?newip=true" method="post">
<input type="hidden" name="kundenid" value="<?=$_REQUEST[kundenid]?>">
<table width="540" border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
  <td colspan="4"><b>Neue IP</b></td>
</tr>
<tr class="tr">
  <td>IP Adresse</td>
  <td><input type="text" name="ipadresse"></td>
</tr>
<tr class="tr">
  <td>rDNS Domain<br><font size="1">(optional)</font></td>
  <td><input type="text" name="domain"></td>
</tr>
<tr class="tr">
  <td>Zweck<br><font size="1">(optional)</font></td>
  <td><input type="text" name="zweck"></td>
</tr>
<tr class="tr">
  <td>Zusatz<br><font size="1">(optional)</font></td>
  <td><input type="text" name="zusatz"></td>
</tr>

<tr class="tr">
  <td colspan="2"><input type="submit" value="Speichern"></td>
</tr>
</table>

</td>
</tr>
</table>
</form>
<br>

<?}?>


<table width="550" border="0" cellpadding="0" cellspacing="0">
<tr>
    <td bgcolor="#cccccc" height="1"><img src="img/pixel.gif" width="1" height="1"></td>
</tr>
</table>
<br>


<table width="540" border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
  <td colspan="4"><b>Server Accounts</b></td>
</tr>
<tr class="tr">
  <td><b>IP/URL</b></td>
  <td><b>Accountname</b></td>
  <td><b>Accounttyp</b></td>
  <td width="16"><b>Aktion</b</td>
</tr>

<?
  $resip = $db->query("select * from biz_serveraccounts where kundenid='$_REQUEST[kundenid]'");
  while($rowip=$db->fetch_array($resip)) {
?>

<tr class="tr">
  <td>
  <?
  $res = $db->query("select * from biz_defaultserver where serverid='$rowip[serverid]'");
  $rows = $db->fetch_array($res);  
  echo $rows[serverip];?>
  </td>
  <td>
  <?
   if($rows[servertyp]=="confixx3") echo "<a href=\"module/biz/kunden_detail_confixx3edit.php?kundenid=$_REQUEST[kundenid]&accountid=$rowip[accountid]\">$rowip[accountname]</a>";
   else echo "$rowip[accountname]";
  ?>
  </td>
  <td><?=$rows[servertyp]?></td>
  <td><a href="module/biz/kunden_detail_server.php?delserveraccount=true&accountid=<?=$rowip[accountid]?>&kundenid=<?=$_REQUEST[kundenid]?>"><img alt="Löschen" src="img/trash.gif" border="0"></a></td>
</tr>
<?
}
?>

</table>

</td>
</tr>
</table>
<br>

<br>

<form action="module/biz/kunden_detail_server.php?newserveraccount=true" method="post">
<input type="hidden" name="kundenid" value="<?=$_REQUEST[kundenid]?>">
<table width="540" border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
  <td colspan="4"><b>Spezial Account erstellen</b></td>
</tr>
<tr class="tr">
  <td colspan="4"><font size="1"><a href="module/biz/kunden_detail_confixx3.php?newaccount=true&kundenid=<?=$_REQUEST[kundenid]?>">Neuer Confixx 3 Account</a></font></td>
</tr>

<tr class="th">
  <td colspan="4"><b>Neuer allgemeiner Serveraccount</b></td>
</tr>


<tr class="tr">
  <td>IP Adresse / URL</td>
  <td>
  <select name="serverid">
  <?$res = $db->query("select * from biz_defaultserver order by servertyp");
  while($row = $db->fetch_array($res)) {
    echo "<option value=\"$row[serverid]\">$row[serverip] ($row[servertyp])</option>";
  }
  ?>
  </select>
  </td>
</tr>
<tr class="tr">
  <td>Benutzername</td>
  <td><input type="text" name="accountname"></td>
</tr>
<tr class="tr">
  <td>Passwort</td>
  <td><input type="text" name="accountpwd"></td>
</tr>
<tr class="tr">
  <td colspan="2"><input type="submit" value="Speichern"></td>
</tr>
</table>

</td>
</tr>
</table>
</form>
<br>



<br>
<br>




<?include("../../footer.php");?>
