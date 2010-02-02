<?
$module = basename(dirname(__FILE__));
include("../../header.php");

if($_REQUEST[del]=="true") {
  $db->query("delete from biz_kassenbuch where posid='$_REQUEST[posid]'");
}

$currencySQL = $db->query("select waehrung from biz_settings");
$currency=$db->fetch_array($currencySQL);
?>

<i><b>Kassenbuch (alle Werte in <?=$currency['waehrung']?>)</b></i><br>
<br>
<br>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="47%" valign="top">
			<u>Eingang</u><br>
			<br>

			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr bgcolor="#cccccc">
					<td>

						<table width="100%" border="0" cellspacing="1" cellpadding="3">
							<tr bgcolor="#e7e7e7" align="left" valign="top">
								<td><b>Datum</b></td>
								<td><b>Bezeichnung</b></td>
								<td><b>Netto</b></td>
								<td><b>Brutto</b></td>
								<td><b>MwSt</b></td>
								<td><b>Aktion</b></td>
							</tr>
<?

$res = $db->query("select posid,datum,bezeichnung,summe,mwst from biz_kassenbuch where typ='eingang' and adminid='$_SESSION[adminid]' order by datum");

while($row = $db->fetch_array($res)) {

	if($row[mwst]=="Y") 
	{
		$brutto = $row[summe] * 1.16;
		$mwst   = $brutto - $row[summe];
	}
	else {
  		$brutto = "";
  		$mwst   = "";
	}

	$row[summe]  = sprintf("%.2f",$row[summe]);
	$brutto      = sprintf("%.2f",$brutto);
	$mwst        = sprintf("%.2f",$mwst);


?>

<tr bgcolor="#FFFFFF" align="left" valign="top">
	<td valign="top"><?=$row[datum]?></td>
	<td valign="top"><?=$row[bezeichnung]?></td>
	<td valign="top"><?=$row[summe]?></td>
	<td valign="top"><?=$brutto?></td>
	<td valign="top"><?=$mwst?></td>
	<td valign="top"><a href="module/biz/kasse.php?del=true&posid=<?=$row[posid]?>"><img alt="Löschen" src="img/trash.gif" border="0"></a></td>
</tr>
<?
	$sum_netto  = $sum_netto + $row[summe];
	$sum_brutto = $sum_brutto + $brutto;
	$sum_mwst   = $sum_mwst + $mwst;
}
?>
</table>

</td>
</tr>
</table>

<br>
<?
$sum_netto  = sprintf("%.2f",$sum_netto);
$sum_brutto = sprintf("%.2f",$sum_brutto);
$sum_mwst   = sprintf("%.2f",$sum_mwst);
?>

Summe Netto: <?=$sum_netto?><br>
Summe Brutto: <?=$sum_brutto?><br>
Summe MwSt: <?=$sum_mwst?><br>
<br>
<?

$sum_netto  = "";
$sum_brutto = "";
$sum_mwst   = "";

?>
</td>
<td width="6%"></td>
<td width="47%" valign="top">

<u>Ausgang</u><br>
<br>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr bgcolor="#cccccc">
<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr bgcolor="#e7e7e7" align="left" valign="top">
<td><b>Datum</b></td>
<td><b>Bezeichnung</b></td>
<td><b>Netto</b></td>
<td><b>Brutto</b></td>
<td><b>MwSt</b></td>
<td><b>Aktion</b></td>
</tr>
<?

$res = $db->query("select posid,datum,bezeichnung,summe,mwst from biz_kassenbuch where typ='ausgang' and adminid='$_SESSION[adminid]' order by datum");
while($row = $db->fetch_array($res)) {

if($row[mwst]=="Y") 
{
  $brutto = $row[summe] * 1.16;
  $mwst   = $brutto - $row[summe];
}
else {
  $brutto = "";
  $mwst   = "";
}



$row[summe]  = sprintf("%.2f",$row[summe]);
$brutto      = sprintf("%.2f",$brutto);
$mwst        = sprintf("%.2f",$mwst);


?>

<tr bgcolor="#FFFFFF" align="left" valign="top">
<td valign="top"><?=$row[datum]?></td>
<td valign="top"><?=$row[bezeichnung]?></td>
<td valign="top"><?=$row[summe]?></td>
<td valign="top"><?=$brutto?></td>
<td valign="top"><?=$mwst?></td>
<td valign="top"><a href="module/biz/kasse.php?del=true&posid=<?=$row[posid]?>"><img alt="LÃ¶schen" src="img/trash.gif" border="0"></a></td>
</tr>
<?
$sum_netto  = $sum_netto + $row[summe];
$sum_brutto = $sum_brutto + $brutto;
$sum_mwst   = $sum_mwst + $mwst;
}
?>
</table>

</td>
</tr>
</table>


<br>
<?
$sum_netto  = sprintf("%.2f",$sum_netto);
$sum_brutto = sprintf("%.2f",$sum_brutto);
$sum_mwst   = sprintf("%.2f",$sum_mwst);
?>

Summe Netto: <?=$sum_netto?><br>
Summe Brutto: <?=$sum_brutto?><br>
Summe MwSt: <?=$sum_mwst?><br>
<br>



</td>
</tr>
</table>

<br>



<?include("../../footer.php");?>