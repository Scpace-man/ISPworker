<?
$module = basename(dirname(__FILE__));
include("../../header.php");

?>

<h3>Modul Business</h3>
<br>

<table width="540" border="0" cellspacing="0" cellpadding="0" align="center">
<tr bgcolor="#cccccc" align="left" valign="top">
<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr bgcolor="#e7e7e7" align="left" valign="top">
  <td colspan="2"><b>Modul Informationen</b></td>
</tr>
<tr>
  <td width="200" bgcolor="#ffffff">Modul Name</td>
  <td bgcolor="#ffffff">Fakturierung</td>
</tr>
<tr>
  <td bgcolor="#ffffff">Version</td>
  <td bgcolor="#ffffff"><?=VERSION?></td>
</tr>
<tr>
  <td bgcolor="#ffffff">Datum</td>
  <td bgcolor="#ffffff"><?=DATUM?></td>
</tr>
<tr>
  <td bgcolor="#ffffff">Autor</td>
  <td bgcolor="#ffffff"><?=COMPANY?></td>
</tr>
<tr>
  <td bgcolor="#ffffff">Autor Mail</td>
  <td bgcolor="#ffffff"><a href="mailto:support@ispware.de">support@ispware.de</a></td>
</tr>
<tr>
  <td bgcolor="#ffffff">Autor Web</td>
  <td bgcolor="#ffffff"><a href="http://www.ispware.de">http://www.ispware.de</a></td>
</tr>
<tr>
  <td bgcolor="#ffffff">Lizenz</td>
  <td bgcolor="#ffffff">ISPWorker Lizenz</td>
</tr>
<tr>
  <td bgcolor="#ffffff">Lizenz Key</td>
  <td bgcolor="#ffffff">
  
  <?
  $res = $db->query("select status from _licence where module='biz'");
  $row = $db->fetch_array($res);
  if($row[status]=="valid") { echo "G�ltig"; } else { echo "Ung�ltig"; }
  ?>
  - <a href="module/system/lizenzkeys.php?p=biz">Lizenzkey �ndern</a>
  </td>
</tr>
<tr>
  <td bgcolor="#ffffff" valign="top">Hinweise</td>
  <td bgcolor="#ffffff">
  + ionCube Loader muss installiert sein. <br>
  + module/biz/cronwork.php als Cronjob einrichten.
  </td>
</tr>
</table>


</td>
</tr>
</table>
</form>






<br>


<?include("../../footer.php");?>
