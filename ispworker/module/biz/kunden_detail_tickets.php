<?
$module = basename(dirname(__FILE__));
include("./inc/functions.inc.php");
include("../../header.php");



include("./inc/reiter1.layout.php");

$bgcolor[0]   = "#f0f0f0";
$linecolor[0] = "#000000";

$bgcolor[5]   = "#ffffff";
$linecolor[5] = "#ffffff";

include("./inc/reiter1.php");



$resk = $db->query("select * from biz_kunden where kundenid='$_REQUEST[kundenid]'");
$rowk = $db->fetch_array($resk);

$res = $db->query("select * from ticket_anfragen where frommail='$rowk[mail]'");
while($row = $db->fetch_array($res)) {


?>


<table width="630" border="0" cellspacing="0" cellpadding="0">
<tr>
<td bgcolor="#cccccc">

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr bgcolor="#e7e7e7">
<td><b><?=$row[betreff]?>, <?$datum = date("d.m.Y H:i:s",$row[eingegangen]); echo $datum;?></b> (<a href="module/ticket/anfrage_detail.php?ticketid=<?=$row[ticketid]?>">Ticket Details</a>)</td>
</tr>

<tr bgcolor="#FFFFFF">
<td><font face="Verdana" size="1"><?$row[nachricht] = nl2br($row[nachricht]);?><?=$row[nachricht]?></font></td>
</tr>

<?
$resa = $db->query("select * from ticket_anfrageantworten where ticketid='$row[ticketid]' order by eingegangen");
while($rowa = $db->fetch_array($resa)) {
$datum = date("d.m.Y H:i:s",$rowa[eingegangen]);
?>
<tr bgcolor="#e7e7e7">
<td><b>Antwort, <?=$datum?> - Von: <?=$rowa[frommail]?> An: <?=$rowa[tomail]?></b></td>
</tr>
<tr bgcolor="#FFFFFF">
<td><font face="Verdana" size="1"><?$rowa[nachricht] = nl2br($rowa[nachricht]);?><?=$rowa[nachricht]?></font></td>
</tr>
<?
}
?>

</table>

</td>
</tr>
</table>
<br>
<?}?>

<br>



<br>
<br>
<br>




<?include("../../footer.php");?>
