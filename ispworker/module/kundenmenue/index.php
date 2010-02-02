<?
$module = basename(dirname(__FILE__));
include("../../header.php");
global $PHP_SELF;
?>

<h3>Kundenmenü</h3>
<br>

<a href="module/kundenmenue/support.php">Support Informationen</a><br>
<br>


<table width="540" border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
<td colspan="4"><b>News</b></td>
</tr>
<?
	$res = $db->query("select * from biz_news order by datum desc limit 0,50");
	while($row=$db->fetch_array($res)) {
?>
<tr class="tr" align="left" valign="top">
<td width="60"><?=fn_make_Date($row[datum])?></td>
<td width="100"><?=$row[titel]?></td>
<td width="250"><?=$row[einleitung]?></td>
<td><a href="module/kundenmenue/newsdetails.php?id=<?=$row[newsid]?>">mehr Infos</a></td>
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
