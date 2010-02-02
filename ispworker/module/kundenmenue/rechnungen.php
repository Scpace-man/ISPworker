<?
$module = basename(dirname(__FILE__));
include("../../header.php");


$res = $db->query("select rechnungid,datum from biz_rechnungen where kundenid='$_SESSION[user]' order by datum DESC");
if($db->num_rows($res)==0) 
{
    echo "Es sind keine Rechnungen für Sie vorhanden.";

    include("../../footer.php");
    die();
}

// ----------------------

$myres = $db->query("select * from biz_settings");
$biz_settings = $db->fetch_array($myres);
?>

<table width="540" border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
<td><b>RechnungNr</b></td>
<td><b>Datum</b></td>
<td><b>Status</b></td>
<td><b>Aktion</b></td>
<td><b>Paypal</b></td>
</tr>

<?
$myres = $db->query("select mail from biz_kunden where kundenid='$_SESSION[user]'");
$kun   = $db->fetch_array($myres);

$res = $db->query("select rechnungid,positionen,datum,status from biz_rechnungen where kundenid='$_SESSION[user]' order by datum DESC");
while($row=$db->fetch_array($res)) {
$_records[] = array("kunde" => explode("|", $row['anschrift']), "positionen" => $row["positionen"]);
$total=getTotalAmount($_records);

?>
<tr class="tr" align="left" valign="top">
<td><?=$row[rechnungid];?> </td>
<?
$t = strtotime($row[datum]);
$datum = date("d.m.Y",$t);
?>
<td><?=$datum?></td>
<td><?
if($row[status]=="storniert") { echo "<font color=\"green\">$row[status]</font>"; }
if($row[status]=="bezahlt")   { echo "<font color=\"green\">$row[status]</font>"; }
if($row[status]=="unbezahlt") { echo "<font color=\"orange\">$row[status]</font>"; }
if($row[status]=="gemahnt")   { echo "<font color=\"red\">$row[status]</font>"; }

?></td>
<td><a href="module/kundenmenue/rechnung_pdf.php?rechnungid=<?=$row[rechnungid]?>" target="new">Rechnung ansehen</a></td>
<?
echo'
<form action="'.CONF_BASEPAYPALURL.'" method="post" target="paypalwin">
<input type="hidden" name="cmd" value="_cart">
<input type="hidden" name="upload" value="1">
<input type="hidden" name="business" value="'.$biz_settings['paypalmailaddress'].'">
<input type="hidden" name="currency_code" value="'.$biz_settings['ppwaehrung'].'">
<input type="hidden" name="return" value="'.CONF_BASEORDERMENU.'order_paypal_exit.php?invoice_success=true&rid='.$row[rechnungid].'&kid='.$_SESSION[user].'">
<input type="hidden" name="cancel_return" value="'.CONF_BASEORDERMENU.'order_paypal_exit.php?invoice_aborting=true&rid='.$row[rechnungid].'&kid='.$_SESSION[user].'">
<input type="hidden" name="rm" value="2">
<input type="hidden" name="item_name_1" value="Rechnung Nr. '.$row[rechnungid].'">
<input type="hidden" name="amount_1" value="'.$total.'">
<input type="hidden" name="custom" value="">
<td align="center">';

if($row[status]=="unbezahlt" || $row[status] =="gemahnt")
	 echo'<input type="image" src=".../../img/paypalbezahlung.gif" alt="Via Paypal bezahlen">';
echo'</td></form>';
?>
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

<?

function getTotalAmount($_records){

	foreach($_records as $_record)
	{
		// Positionen trennen (Array)
		$_positions = explode("<br>", $_record['positionen']);

		// Positionen durchlaufen
		$ausgabe = array();

		foreach($_positions as $_position)
		{
			// Felder trennen
			$_fields = explode("|", $_position);
			
			// Betrag steht im 3. Feld
			if(isset($_fields[2])) 
            {
                $sum += $_fields[2]*$_fields[0];
                $ausgabe[] = $_fields[2]*$_fields[0];
            }
		}
		implode("", $ausgabe);	
	}
	return array_sum($ausgabe);
}


?>
