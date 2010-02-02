<?include("header.php");
session_destroy();
?>

<h3>Punkt 1: Paketauswahl</h3>
<hr size="1" noshade>
<br>
Bitte wählen Sie ein Paket aus:<br>
<br>

<table width="600" border="0" cellspacing="0" cellpadding="0">
<tr bgcolor="#cccccc">
<td>

	<table width="100%" border="0" cellspacing="1" cellpadding="3">
	<tr bgcolor="#e7e7e7" align="left" valign="top">
	<td colspan="3"><b>Vorhandene Produkte</b></td>
	</tr>
<?
$res = $db->query("select * from biz_produktkategorien where sichtbar=1");
while($kat = $db->fetch_array($res)) {
$res2 = $db->query("select * from order_artikel WHERE `katid`=".$kat['katid']."");

//	$res = $db->query("select artikelid, tld_bestellbar, bpk.bezeichnung as bpkkat from order_artikel oa
//	left join biz_produkte bp ON oa.artikelid=bp.produktid
//	left join biz_produktkategorien bpk ON bp.katid=bpk.katid");
?>
	<tr bgcolor="#FFFFFF">
		<td bgcolor="#e7e7e7" colspan="3">
			<b><? echo $kat[bezeichnung];?></b>
		</td>
	</tr>
<?php
		while ($row = $db->fetch_array($res2)) {
?>
		<tr bgcolor="#FFFFFF">
			<td>
				<b><?=$row[bpkkat]?></b>
			</td>
			<td>
				<b><?$order->print_paketinfo("bezeichnung",$row[artikelid]);?></b><br>
				<?$order->print_paketinfo("kurztext",$row[artikelid]);?>
			</td>
			<td>
				<a href="order_whois.php?paketid=<?=$row[artikelid]?>&tldb=<?=$row[tld_bestellbar]?>">Bestellen
			</td>
		</tr>

		<?
	}
	}
	?>
	</table>

</td>
</tr>
</table>

<?include("footer.php");?>