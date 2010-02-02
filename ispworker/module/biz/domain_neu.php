<?
$module = basename(dirname(__FILE__));
include("../../header.php");
?>



<?

if(isset($_REQUEST['new'])) {

  $db->query("insert into biz_domains (adminid,domainname,kunde,freigeschaltet,kosten)

              values ('$_SESSION[adminid]','$_REQUEST[domainname]','$_REQUEST[kunde]','$_REQUEST[freigeschaltet]','$_REQUEST[kosten]')");

  echo "<center><b>Domain ist gespeichert.</b></center><br><br>\n";

}

?>



Noch nicht fertig!<br>

<form action="module/biz/domain_neu.php?new=true" method="post">
<table width="540" border="0" cellspacing="0" cellpadding="0">
<tr bgcolor="#cccccc" align="left" valign="top">
<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr bgcolor="#e7e7e7" align="left" valign="top">
  <td colspan="2"><b>Neue Domain</b></td>
</tr>
<tr>
  <td bgcolor="#ffffff">Domainname</td>
  <td bgcolor="#ffffff"><input type="text" name="domainname"></td>
</tr>
<tr>
  <td bgcolor="#ffffff">Kunde</td>
  <td bgcolor="#ffffff"><input type="text" name="kunde"></td>
</tr>
<tr>
  <td bgcolor="#ffffff">Freigeschaltet</td>
  <td bgcolor="#ffffff"><input type="text" name="freigeschaltet"></td>
</tr>
<tr>
  <td bgcolor="#ffffff">Kosten</td>
  <td bgcolor="#ffffff"><input type="text" name="strasse"></td>
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






<?include("../../footer.php");?>
