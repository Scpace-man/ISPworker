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
<td colspan="4"><b>News</b></td>
</tr>
<?
$res = $db->query("select * from biz_news WHERE newsid='".$_REQUEST[id]."'");
while($row=$db->fetch_array($res)) {
?>
<tr class="tr" align="left" valign="top">
<td width="60">Datum</td>
<td><?=fn_make_Date($row[datum])?></td>
</tr>
<tr class="tr">
<td width="60">Titel</td>
<td><?=$row[titel]?></td>
</tr>
<tr class="tr">
<td width="60" valign="top">Newstext</td>
<td><?=$row[text]?></td>
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

<?include("../../footer.php");?>
