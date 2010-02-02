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

$bgcolor[1]   = "#ffffff";
$linecolor[1] = "#ffffff";

include("./inc/reiter2.php");


if($_REQUEST[update]=="true") 
{
    $software = urldecode($_REQUEST[software]);
    $db->query("update biz_interfaces set konfig='$_REQUEST[konfig]' where software='$_REQUEST[software]'");
    $interfaceedit=true;
}
?>


<table width="600" border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>


<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
  <td colspan="2"><b>Schnittstellen</b></td>
</tr>
<?
$res = $db->query("select * from biz_interfaces order by software");
while($row = $db->fetch_array($res)) {?>
<tr class="tr">
  <td><?=$row[software]?></td>
  <td width="16"><a href="module/biz/einst_schnittstellen.php?interfaceedit=true&software=<?=urlencode($row[software])?>"><img src="img/edit.gif" alt="Bearbeiten" border="0"></a></td>
</tr>
<?}?>
</table>

</td>
</tr>
</table>

<br>

<?if($_REQUEST[interfaceedit]=="true") {?>


<?$res = $db->query("select * from biz_interfaces where software='$_REQUEST[software]'");
$row = $db->fetch_array($res);
$software = urlencode($row[sofware]);
$row[konfig] = stripslashes($row[konfig]);
?>


<form action="module/biz/einst_schnittstellen.php?update=true&software=<?=$row[software]?>" method="post">
<table width="600" border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>


<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
  <td colspan="2"><b>Schnittstellen Konfiguration</b></td>
</tr>
<tr class="tr">
  <td><textarea cols="50" rows="20" name="konfig" wrap="off" style="width:590px;"><?=$row[konfig]?></textarea></td>
</tr>
<tr class="tr">
  <td><input type="submit" value="Speichern"></td>
</tr>
</table>

</td>
</tr>
</table>
</form>


<?}?>
<br>
<br>
<br>
<br>


<?include("../../footer.php");?>
