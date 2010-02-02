<?
/*****************************************************************************************************/
/*	CHANGES 28.03.2006, sm
/*	Zeile 44-49:
/*		- Darstellung der individuelle Abrechnungszeiträume überarbeitet
/*****************************************************************************************************/
$module = basename(dirname(__FILE__));
include("../../header.php");
include("../order/inc/functions.inc.php");
include("../order/inc/order.class.php");

$order = new order();


$currencySQL = $db->query("select waehrung from biz_settings");
$currency=$db->fetch_array($currencySQL);
?>

<b>Addons bestellen</b><br>
<br>

<form action="module/kundenmenue/order_addon2.php" method="post">
<table width="500" border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
  <tr class="tr">
    <td valign="top"><strong>Auswahl</strong></td>
    <td valign="top"><strong>Artikelbeschreibung</strong></td>
    <td valign="top" align="right"><div align="left"><b>Preis</b></div></td>
    <td valign="top" align="right"><strong>Abrechungszeitraum</strong></td>
    </tr>
<?
$res = $db->query("select * from biz_produktkategorien where sichtbar=1");
while($kat = $db->fetch_array($res)) {
$res2 = $db->query("select * from biz_produkte WHERE `katid`=".$kat['katid']." AND sichtbar=1");
?>
	<tr bgcolor="#FFFFFF">
		<td bgcolor="#e7e7e7" colspan="4">
			<b><? echo $kat[bezeichnung];?></b>
		</td>
	</tr>
<?php
		while ($row = $db->fetch_array($res2)) {
?>
 <tr class="tr">
  <td width="20" valign="top"><input type="radio" name="produktid" value="<?=$row[produktid]?>"></td>
  <td width="242" valign="top">
    <b><?=$row[bezeichnung]?></b>
    <br>
    <br /></td>
  <td width="126" valign="top" align="right"><?=$row[preis]?> <?=$currency[waehrung]?></td>
  <td width="126" valign="top" align="right"><?
	$abrechnung=explode(":",$row[abrechnung]);
	if($abrechnung[0]=="indiv"){
		echo "alle ".$abrechnung[1]." Monate";
	}else{
		echo $row[abrechnung];
	}
  ?></td>
  </tr>
  <?}}?>
</table>

</td>
</tr>
</table>
<br>
Mit dem Klick auf den Bestellen Button geben Sie eine rechtsverbindliche Bestellung ab.<br>
<br>
<input type="submit" value="Bestellen">
<br>
<br>
</form>


<?include("../../footer.php");?>