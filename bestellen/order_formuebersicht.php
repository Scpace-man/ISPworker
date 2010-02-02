<?
include("header.php");

// Settings: welches Feld auch angezeigt werden soll.
$data = $db->queryfetch("SELECT firma,titel,mobil,fax,url,pkey,geb,zusatz1,zusatz2,zusatz3,zusatz1status,zusatz2status,zusatz3status,nichtvolljaehrig FROM order_settings");
$biz_settings = $db->queryfetch("select * from biz_settings");

if($biz_settings['paypalmailaddress']!='' && sprintf("%.2f",$biz_settings['paypalfaktor'])!='0.00' || $biz_settings['paypalfaktor']!="")
    $compute = true;

?>

<h3>Punkt 6: Bestellübersicht</h3>
<hr size="1" noshade>
<br>

<?

echo '<form action="order_formsave.php?paketid='.$_SESSION[paketid].'" method="post">';

?>

<table width="600" border="0" cellspacing="0" cellpadding="0">
	<tr class="tb">
	<td>

		<table border="0" cellspacing="1" cellpadding="3">
		<tr class="th">
			<td width="600" colspan="4"><b>Ausgew&auml;hlte Produkte</b></td>
		</tr>
		<tr class="th">
			<td>Bezeichnung</td>
			<td>Preis in <?=$biz_settings['waehrung']?></td>
			<td>Preis in Paypal</td>
			<td>Intervall</td>
		</tr>
			<?
			
			// Code deckt sich groesstenteils mit order_save.php
			// Zukünftig Code ausgliedern, Bedingungen einbauen und das ganze in beiden Scripts inkludieren.
			
			$produkte=explode(";",$_SESSION['paketid']);
			for($n=0;$n<count($produkte);$n++)
			{
			    $res = $db->query("select bezeichnung,preis,abrechnung from biz_produkte where produktid='".$produkte[$n]."' ");
			    $row = $db->fetch_array($res);

			    $resein = $db->query("select biz_produkte.preis from biz_produkte,order_artikel where order_artikel.produkteinid=biz_produkte.produktid and order_artikel.artikelid='".$produkte[$n]."' ");
			    $rowein = $db->fetch_array($resein);

			    $intervall = explode(":",$row['abrechnung']);
			    if(count($intervall) > 1)	$rechnungsintervall = "alle ".$intervall[1]." Monate";
			    else			$rechnungsintervall = $row['abrechnung'];
			
	        	    $rechnungsintervall = str_replace("jaehrlich", "j&auml;hrlich", $rechnungsintervall);

			    $bez   = $row[bezeichnung];
			    $preis = $row[preis];
			    $total = $total + $preis;
			    ?>
			    <tr class="tr">
	  		    <td width="300"><?=stripslashes($bez)?></td>
	  		    <td width="100"><?=sprintf("%.2f",$preis)." ".$biz_settings['waehrung']?></td>
			    <td width="100">
			    <?
	 		    	 if($compute == true)	echo sprintf("%.2f",round($preis * $biz_settings['paypalfaktor'],2))." ".$biz_settings['ppwaehrung'];
			    	 else			echo sprintf("%.2f",$preis)." ".$biz_settings['ppwaehrung'];
				?>
			    </td>
			    <td width="100"><?=$rechnungsintervall?></td>
			    </tr>
			    <?
			    if($rowein[preis]!="") 
			    {
			        $preisein = $rowein[preis];
				$total    = $total + $preisein;
			    ?>
			        <tr class="tr">
	  		    	<td>Einrichtung</td>
	  		    	<td><?=sprintf("%.2f",$preisein)." ".$biz_settings['waehrung']?></td>
			    	<td>
			    <?
	 		    	if($compute == true)	echo sprintf("%.2f",round($preisein * $biz_settings['paypalfaktor'],2))." ".$biz_settings['ppwaehrung'];
			    	else			echo sprintf("%.2f",$preisein)." ".$biz_settings['ppwaehrung']; 
			    ?>
			    	</td>
			    	<td>einmalig</td>
			        </tr>
			    <?
			    }
			}
			
			$res = $db->query("select anzdomains,tldsmitaufpreis from order_artikel where artikelid='".$produkte[0]."' ");
			$row = $db->fetch_array($res);

			$j = 0;
			$da = explode(";",$_SESSION["domainstoorder"]);
			if(count($da)>0) 
			{
			    for($i=0;$i<count($da);$i++) 
			    {
				if($da[$i]!="") 
				{
    				    $j++;
				    $ta = explode(".",$da[$i]);
				    $tb = explode("{KK}",$ta[1]);
				    $tld = ".".$tb[0];

			?>
				    <tr class="tr">
                    			<td><?=$da[$i]?></td>
                    			<td>
			<?
					if($j <= $row["anzdomains"]) 
					{
					    $restld = $db->query("select tldid,aufpreis from order_tld where tld='".$tld."' ");
					    $rowtld = $db->fetch_array($restld);

					    if(strstr($row["tldsmitaufpreis"],"|".$rowtld["tldid"]."|")) {
					    	$resp = $db->query("select preis,abrechnung from biz_produkte where produktid='".$rowtld["aufpreis"]."' ");
						$rowp = $db->fetch_array($resp);
					    	$rechnungsintervall = str_replace("jaehrlich", "j&auml;hrlich", $rowp['abrechnung']);
						
						$preisp = $rowp["preis"];
						$total  = $total + $preisp;
			?>			<?=sprintf("%.2f",$preisp)." ".$biz_settings['waehrung']." Aufpreis "?>
						
					</td>
					<td>
			<?
						if($compute == true) 	echo sprintf("%.2f",round($preisp * $biz_settings['paypalfaktor'],2))." ".$biz_settings['ppwaehrung'];
						else 			echo sprintf("%.2f",$preisp)." ".$biz_settings['ppwaehrung'];
								
			?>
					</td>
					<td>	<?=$rechnungsintervall?></td>
		 	<?
					    }
					
					    else echo '<td>&nbsp;</td><td>inklusive</td>';
					
                	?>
					</td>
                		    </tr>
			<?
					} 
					else 
					{

					    $rest = $db->query("select biz_produkte.preis, biz_produkte.abrechnung from biz_produkte,order_tld
								where biz_produkte.produktid=order_tld.preis and order_tld.tld = '".$tld."' ");
					    $rowt = $db->fetch_array($rest);
					
					    $rechnungsintervall = str_replace("jaehrlich", "j&auml;hrlich", $rowt['abrechnung']);
						
					    $preist = $rowt["preis"];
					    $total  = $total + $preist;
			?>	
					    <?=sprintf("%.2f",$preist)." ".$biz_settings['waehrung']?>
					    </td>
					    <td>
			<?
					    if($compute == true)	echo sprintf("%.2f",round($preist*$biz_settings['paypalfaktor'],2))." ".$biz_settings['ppwaehrung'];
					    else			echo sprintf("%.2f",$preist)." ".$biz_settings['ppwaehrung'];
			?>
					    </td>
					    <td><?=$rechnungsintervall?></td>
                			</tr>
			<?
					}
				    }
				}
			    }

			    if($compute == true) $total = $total * $biz_settings["paypalfaktor"]; 
			    $total = sprintf("%.2f", $total);
			    $_SESSION["d_paypal_total"] = $total;
			    

?>
		</table>

	</td>
	</tr>
	</table>
	<br>
	<?if($_SESSION["d_zahlungsart"]=="paypal") {?>
	Die Bezahlung per Paypal wird in <?=$biz_settings["ppwaehrung"]?> berechnet. Der Preis in <?=$biz_settings["ppwaehrung"]?> entspricht obiger Angabe.
	<?}?>
	<br>
	
	<br>
	<table width="600" border="0" cellspacing="0" cellpadding="0">
	<tr class="tb">
	<td>

		<table border="0" cellspacing="1" cellpadding="3">
		<tr class="th">
			<td colspan="2"><b>Kundendaten</b></td>
		</tr>
		<?
		// Firma
		if($data['firma'] != "inaktiv")
		{
		?>
		<tr class="tr">
  			<td>Firma</td>
  			<td><?=$_SESSION[d_firma]?></td>
		</tr>
		<?
		}
		//Firma
		?>
		<tr class="tr">
  			<td width="300">Anrede</td>
  			<td width="300"><?=$_SESSION[d_anrede]?></td>
		</tr>
		<?
		// Titel
		if($data['titel'] != "inaktiv")
		{
		?>
		<tr class="tr">
  			<td>Titel</td>
  			<td><?=$_SESSION[d_titel]?></td>
		</tr>
		<?
		}
		// Titel
		?>
		<tr class="tr">
  			<td>Vorname / Nachname</td>
  			<td><?=$_SESSION[d_vorname]?> / <?=$_SESSION[d_nachname]?></td>
		</tr>
		<?
		// Geburtsdatum
		if($data['geb'] != "inaktiv")
		{
		?>
		<tr class="tr">
  			<td>Geburtsdatum</td>
  			<td><?=$_SESSION[d_tag].$_SESSION[d_monat].$_SESSION[d_jahr]?></td>
		</tr>
		<?
		}
		//Geburtsdatum
		?>
		<tr class="tr">
  			<td>Strasse</td>
  			<td><?=$_SESSION[d_strasse]?></td>
		</tr>
		<tr class="tr">
  			<td>Land / Plz / Ort</td>
  			<td><?=$_SESSION[d_isocode]?>-<?=$_SESSION[d_plz]?> / <?=$_SESSION[d_ort]?></td>
		</tr>
		<tr class="tr">
  			<td>Telefon</td>
  			<td><?=$_SESSION[d_telefon]?></td>
		</tr>
		<?
		// Mobil
		if($data['mobil'] != "inaktiv")
		{
		?>
		<tr class="tr">
  			<td>Mobil</td>
  			<td><?=$_SESSION[d_mobil]?></td>
		</tr>
		<?
		}
		//Mobil
		// Fax
		if($data['fax'] != "inaktiv")
		{
		?>
		<tr class="tr">
  			<td>Fax</td>
  			<td><?=$_SESSION[d_fax]?></td>
		</tr>
		<?
		}
		//Fax
		?>
		<tr class="tr">
  			<td>E-Mail</td>
  			<td><?=$_SESSION[d_mail]?></td>
		</tr>
		<?
		// Website
		if($data['url'] != "inaktiv")
		{
		?>
		<tr class="tr">
  			<td>Website</td>
  			<td><?=$_SESSION[d_url]?></td>
		</tr>
		<?
		}
		//Website
		// Aktions Key
		if($data['pkey'] != "inaktiv")
		{
		?>
		<tr class="tr">
			<td>Aktions Key</td>
			<td><?=$_SESSION[d_key]?></td>
		</tr>
		<?
		}
		//Aktions Key
		// Zusatz1
		if($data['zusatz1status'] != "inaktiv")
		{
		?>
		<tr class="tr">
			<td><?=$data['zusatz1']?></td>
			<td><?=$_SESSION[d_zusatz1]?></td>
		</tr>
		<?
		}
		// Zusatz1
		// Zusatz2
		if($data['zusatz2status'] != "inaktiv")
		{
		?>
		<tr class="tr">
			<td><?=$data['zusatz2']?></td>
			<td><?=$_SESSION[d_zusatz2]?></td>
		</tr>
		<?
		}
		// Zusatz2
		// Zusatz3
		if($data['zusatz3status'] != "inaktiv")
		{
		?>
		<tr class="tr">
			<td><?=$data['zusatz3']?></td>
			<td><?=$_SESSION[d_zusatz3]?></td>
		</tr>
		<?
		}
		// Zusatz3
		?>
		</table>
    </td>
    </tr>
</table>
<br>
<table width="600" border="0" cellspacing="0" cellpadding="0">
	<tr class="tb">
	<td>
		<table width="100%" border="0" cellspacing="1" cellpadding="3">
		<?
		if($_SESSION['d_zahlungsart']=="rechnung") {
		?>
		<tr class="th">
			<td colspan="2"><b>Zahlungsmethode</b>&nbsp;&nbsp;<span style="font-size:10">per Rechnung<span></td>
		</tr>
    		<?}?>
        <?
		if($_SESSION['d_zahlungsart']=="vorkasse") {
		?>
		<tr class="th">
			<td colspan="2"><b>Zahlungsmethode</b>&nbsp;&nbsp;<span style="font-size:10">per Vorkasse<span></td>
		</tr>
    		<?}?>
        <?
		if($_SESSION['d_zahlungsart']=="paypal") {
		?>
		<tr class="th">
			<td colspan="2"><b>Zahlungsmethode</b>&nbsp;&nbsp;<span style="font-size:10">per Paypal<span></td>
		</tr>
    		<?}?>
		<?
		if($_SESSION['d_zahlungsart']=="lastschrift") {
		?>
		<tr class="th">
			<td colspan="2"><b>Zahlungsmethode</b>&nbsp;&nbsp;<span style="font-size:10">per Lastschrift<span></td>
		</tr>
		<tr class="tr">
			<td>Kontoinhaber</td>
			<td><?=$_SESSION[d_kontoinhaber]?></td>
		</tr>
		<tr class="tr">
			<td>Kontonummer</td>
			<td><?=$_SESSION[d_kontonummer]?></td>
		</tr>
		<tr class="tr">
			<td>Bankleitzahl</td>
			<td><?=$_SESSION[d_bankleitzahl]?></td>
		</tr>
		<tr class="tr">
			<td>Geldinstitut</td>
			<td><?=$_SESSION[d_geldinstitut]?></td>
		</tr>
        <?
        }

       ?>
		</table>
	</td>
	</tr>
	</table>

<br>

<input type="submit" value="Bestellung abschließen">
</form>

<br>
<br>

<?include("footer.php");?>