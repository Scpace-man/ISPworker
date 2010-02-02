<?$module = basename(dirname(__FILE__));

include("../../header.php");

if(isset($_REQUEST[profilid])) $pa = "and profilid='".$_REQUEST[profilid]."' ";
$resp = $db->query("select * from biz_profile where adminid='$_SESSION[adminid]' $pa");
$rowp = $db->fetch_array($resp);


if($_REQUEST['submit']=="Speichern")
{
/*echo "<pre>";
print_r($_REQUEST);
echo "</pre>";
*/

	for($i=0;$i<count($_REQUEST[ausw]);$i++) {

		$res = $db->query("select * from biz_rechnungen WHERE biz_rechnungen.adminid='$_SESSION[adminid]' AND rechnungid='".$_REQUEST[ausw][$i]."' ");
		$_records=array();
		while($rowtotal=$db->fetch_array($res))
		{
			$_records[] = array("kunde" => explode("|", $rowtotal['anschrift']), "positionen" => $rowtotal["positionen"]);
		}

		$gesamt=0;
		foreach($_records as $_record)
		{
			// Positionen trennen
			$_positions = explode("<br>", $_record['positionen']);
				// Positionen durchlaufen
				$ausgabe = array();
				foreach($_positions as $_position)
				{
					// Felder trennen
					$_fields = explode("|", $_position);

					// Betrag steht im 3. Feld
					if(isset($_fields[2]) && $_fields[2]!="")
		            {
		                $sum += $_fields[2]*$_fields[0];
		                $ausgabe[] = $_fields[2]*$_fields[0];
		            }
				}

				for($z=0;$z<count($ausgabe);$z++){
					$gesamt+=$ausgabe[$z];
				}
		}

		$buchungsdatum = explode(".",$_REQUEST[buchungstag][$i]);
		$buchungsdatum = $buchungsdatum[2]."-".$buchungsdatum[1]."-".$buchungsdatum[0];

		if(sprintf("%.2f",$gesamt)==sprintf("%.2f",$_REQUEST[buchungsbetrag][$i])){
			$db->query("update biz_rechnungen set buchungsdatum='".$buchungsdatum."', buchungsbetrag='".sprintf("%.2f",$_REQUEST[buchungsbetrag][$i])."',status='bezahlt' where rechnungid='".$_REQUEST[ausw][$i]."' ");
		}else{
			$db->query("update biz_rechnungen set buchungsdatum='".$buchungsdatum."', buchungsbetrag='".sprintf("%.2f",$_REQUEST[buchungsbetrag][$i])."' where rechnungid='".$_REQUEST[ausw][$i]."' ");
		}
	}

}

/*		for($i=0;$i<count($_REQUEST[ausw]);$i++) {
	    	//$db->query("update biz_rechnungen set status='bezahlt' where rechnungid='".$_REQUEST[ausw][$i]."' ");
		}*/


?>

<span class="htitle">Rechnungen buchen</span><br>
<br>

<br>


<form action="module/biz/rechnungen_buchen.php" method="post">
<table width="740" border="0" cellspacing="0" cellpadding="0">

<tr bgcolor="#cccccc">

<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">

<tr bgcolor="#e7e7e7">

<td width="16"><b><img src="img/pixel.gif" border="0" width="1" height="1"></b></td>
<td><b>ReNr</b></td>
<td><b>Verschickt</b></td>
<td><b>Gemahnt</b></td>
<td><b>Name</b></td>
<td><b>KdNr</b></td>
<td><b>Gesamtbetrag</b></td>
<td><b>Zahlungsart</b></td>
<td><b>Buchungstag</b></td>
<td><b>Buchungsbetrag</b></td>
<td width="16"><img src="img/pixel.gif" border="0" width="1" height="1"></td>


<?
    $ausw = explode(",",$_REQUEST[ausw]);

    for($i = 0; $i < count($ausw); $i++)
    {
    	$re .= " rechnungid='$ausw[$i]' OR";
    }

	$re = " AND (".substr($re,0,(strlen($re)-3)).")";


$res = $db->query("select * from biz_rechnungen, biz_profile where biz_rechnungen.profilid=biz_profile.profilid and biz_rechnungen.adminid='$_SESSION[adminid]' ".$re);

if(!isset($_REQUEST[anzahl])) { $_REQUEST[anzahl] = 3000; }
if(!isset($_REQUEST[start]))  { $_REQUEST[start]  = 0; }

$out = true; // Ausgabe Switch
while($row=$db->fetch_array($res)) {

	if ($out == true) {
$res2 = $db->query("select * from biz_kunden where kundenid='$row[kundenid]' $l");
$row2 = $db->fetch_array($res2);

if($_REQUEST[onlylast]=="true") {
  if ($row2[bezahlart]!="lastschrift") { $out = false; } else { $out = true; }
}

if($_REQUEST[onlyrech]=="true") {
  if ($row2[bezahlart]!="rechnung") { $out = false; } else { $out = true; }
}


if($_REQUEST[onlydruck]=="true") {
  if ($row2[sendmail]=="Y") { $out = false; } else { $out = true; }
}

	?>

	</tr>

	<tr bgcolor="#FFFFFF" align="left" valign="top">

	<td width="20"><input type="checkbox" checked name="ausw[]" value="<?=$row[rechnungid]?>"></td>
	<td><a href="module/biz/rechnung_show.php?rechnungid=<?=$row[rechnungid]?>" target="_blank"><?=$row[idprefix]?><?=$row[rechnungid]?><?=$row[idsuffix]?></a></td>

	<td><?=$datum?></td>
	<td>
		<?if($row[status]=="gemahnt" or $row[status]=="gemahnt2"  or $row[status]=="gemahnt3") {
			echo substr($row[mahndatum],8,2).".".substr($row[mahndatum],5,2).".".substr($row[mahndatum],0,4);
		}?>

	</td>
	<td><a href="module/biz/kunden_detail.php?kundenid=<?=$row[kundenid]?>"><?=$row2[nachname]?>, <?=$row2[vorname]?></a>
		<?if($row2[firma]!="") { echo "<br>$row2[firma]\n"; } ?>
	</td>


	<td><?=$row[kundenid]?></td>
	<td align="right">
	<?
	$currencySQL = $db->query("select waehrung from biz_settings");
	$currency=$db->fetch_array($currencySQL);

	$restotal = $db->query("select * from biz_rechnungen where rechnungid=$row[rechnungid]");
	$_records=array();
	while($rowtotal=$db->fetch_array($restotal))
	{
		$_records[] = array("kunde" => explode("|", $rowtotal['anschrift']), "positionen" => $rowtotal["positionen"]);
	}
	$gesamt=0;
	$i=0;
	foreach($_records as $_record)
	{
		// Positionen trennen
		$_positions = explode("<br>", $_record['positionen']);
			// Positionen durchlaufen
			$ausgabe = array();
			foreach($_positions as $_position)
			{
				// Felder trennen
				$_fields = explode("|", $_position);

				// Betrag steht im 3. Feld
				if(isset($_fields[2]) && $_fields[2]!="")
	            {
	                $sum += $_fields[2]*$_fields[0];
	                $ausgabe[] = $_fields[2]*$_fields[0];
	            }
			}

			for($z=0;$z<count($ausgabe);$z++){
				$gesamt+=$ausgabe[$z];
			}
	}

	if($row[buchungsbetrag]!=0){
		$betrag = sprintf("%.2f",$row[buchungsbetrag]);
	}else{
		$betrag = sprintf("%.2f",$gesamt);
	}
	echo sprintf("%.2f",$gesamt)." ".$currency[waehrung];

	?>
	</td>
	<td><?=$row2[bezahlart]?></td>
	<td><input type="text" size="8" name="buchungstag[]" value="<?=strftime("%d.%m.%Y",time())?>"></td>
	<td><input type="text" size="6" name="buchungsbetrag[]" value="<?=$betrag?>"></td>
	<td><a href="module/biz/rechnung_show.php?rechnungid=<?=$row[rechnungid]?>" target="_blank"><img src="img/pdf.gif" width="16" border="0"></a></td>
	</tr>

	<?

	} // ende if abfrage

} // ende while schleife

?>

</table>

</td>

</tr>

</table>

	<br>
Bitte markieren Sie die Rechnung und tragen Sie das Datum, sowie den Betrag bei den Rechnungen ein, die als bezahlt markiert werden sollen.<br>
Rechnungen die nur einen Teilbetrag eingetragen bekommen, werden nach wie vor in den offenen Rechnungen gelistet <br>
bis der eingetragene Betrag mit dem Rechnungsbetrag übereinstimmt.

	<br>
	<input type="submit" name="submit" value="Speichern">
</form>

<br>
<br>
<br>

<?include("../../footer.php");?>