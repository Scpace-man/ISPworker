<?
$module = basename(dirname(__FILE__));
include("../../header.php");
global $PHP_SELF;
?>

<table width="540" border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
<td colspan="4"><b>Support-Informationen</b></td>
</tr>
<?
$res = $db->query("select * from biz_settings");
while($row=$db->fetch_array($res)) {
if($row[kmtelefon]=='Y'){
?>
<tr>
  <td bgcolor="#ffffff" width="150">Support Nr Info</td>
  <td bgcolor="#ffffff"><?=$row[supportnrinfo]?></td>
</tr>
<tr>
  <td bgcolor="#ffffff" width="150">Support Nr Technik</td>
  <td bgcolor="#ffffff"><?=$row[supportnrtechnik]?></td>
</tr>
<tr>
  <td bgcolor="#ffffff" width="150">Support Nr Buchhaltung</td>
  <td bgcolor="#ffffff"><?=$row[supportnrbuchhaltung]?></td>
</tr>
<?
}else{
?>
<tr>
  <td colspan="2" bgcolor="#ffffff">Zur Zeit sind keine Supportinformationen verfügbar.</td>
</tr>
<?
}
}
?>
</table>


</td>
</tr>
</table>

<br>
<br>

<?include("../../footer.php");?>
