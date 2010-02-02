<?$module = basename(dirname(__FILE__));

if($_REQUEST[action] == "mahnen")
{
    $redirect = true;
    $string = $_REQUEST[ausw][0];
    for($i = 1;$i < count($_REQUEST[ausw]); $i++) $string .= ",".$_REQUEST[ausw][$i];
    $redirectlocation = "mahnung_neu.php?add=true&ausw=$string";
}

if($_REQUEST[action] == "bezahlt")
{
    $redirect = true;
    $string = $_REQUEST[ausw][0];
    for($i = 1;$i < count($_REQUEST[ausw]); $i++) $string .= ",".$_REQUEST[ausw][$i];
    $redirectlocation = "rechnungen_buchen.php?add=true&ausw=$string";
}

include_once("../../header.php");

if(isset($_REQUEST[profilid])) $pa = "and profilid='".$_REQUEST[profilid]."' ";
$rowp = $db->queryfetch("select * from biz_profile where adminid='$_SESSION[adminid]' $pa");

switch($_REQUEST[action])
{

    case "storniert":

	include_once("./inc/functions.inc.php");
	include_once("./inc/pdf.inc.php");
	
	
	for($i=0;$i<count($_REQUEST[ausw]);$i++) 
	{
	    $db->query("update biz_rechnungen set status='storniert' where rechnungid='".$_REQUEST[ausw][$i]."' ");
	
	    $rek = $db->query("select kundenid from biz_rechnungen where rechnungid='".$_REQUEST[ausw][$i]."' ");
	    $rok = $db->fetch_array($rek);
	    
	    pdfinvoice($_REQUEST[ausw][$i]);
	    send_invoice($_REQUEST[ausw][$i], $rok[kundenid], $template="std_stornierterechnung");	
	}
    break;

    case "delete":
	for($i=0;$i<count($_REQUEST[ausw]);$i++) trash("biz_rechnungen","where rechnungid='".$_REQUEST[ausw][$i]."' ");
    break;

    case "pdf":
        $pdflink = "<a href=\"module/biz/rechnung_show.php?multipdf=true&ri=".$_REQUEST[ausw][count($_REQUEST[ausw])-1]."&rj=".$_REQUEST[ausw][0]."\" target=\"_blank\"><b>Sammel-PDF Datei öffnen</b></a><br><br>";
    break;

    case "dta":
	$dtaus = true;
	$fp = fopen($biz_temppath."/dtaus.php","w");
	fputs($fp,"\$dta = new DTA(DTA_DEBIT);\n");
	fputs($fp,"\$dta->setAccountFileSender(array(
	    \"name\"           => \"".$rowp[bankinhaber]."\",
    	    \"bank_code\"      => ".$rowp[bankblz].",
	    \"account_number\" => ".$rowp[bankkonto]."
	    ));\n");

	if($_REQUEST[dtaintervall]=="true")
	{
	    for($i = $_REQUEST[rb]; $i <= $_REQUEST[re]; $i++) $_REQUEST[ausw][] = $i;
	}
	
    break;
}
if(!isset($_SESSION["opt_show_debit"])) 	$_SESSION["opt_show_debit"] 	  = "Y";
if(!isset($_SESSION["opt_show_invoice"])) 	$_SESSION["opt_show_invoice"]     = "Y";
if(!isset($_SESSION["opt_show_paypal"])) 	$_SESSION["opt_show_paypal"]      = "Y";;
if(!isset($_SESSION["opt_show_prepayment"]))	$_SESSION["opt_show_prepayment"]  = "Y";;
if(!isset($_SESSION["opt_show_accountdata"])) 	$_SESSION["opt_show_accountdata"] = "N";;
if(!isset($_SESSION["opt_show_docemail"])) 	$_SESSION["opt_show_docemail"]    = "Y";;

if($_REQUEST["set_options"] == true)
{
    $_SESSION["opt_show_debit"]   	= $_REQUEST["show_debit"];
    $_SESSION["opt_show_invoice"] 	= $_REQUEST["show_invoice"];
    $_SESSION["opt_show_paypal"] 	= $_REQUEST["show_paypal"];
    $_SESSION["opt_show_prepayment"] 	= $_REQUEST["show_prepayment"];
    $_SESSION["opt_show_accountdata"] 	= $_REQUEST["show_accountdata"];
    $_SESSION["opt_show_docemail"] 	= $_REQUEST["show_docemail"];

    if($_REQUEST["show_debit"]!="Y")  		$_SESSION["opt_show_debit"] 	  = "N";   
    if($_REQUEST["show_invoice"]!="Y") 		$_SESSION["opt_show_invoice"] 	  = "N";   
    if($_REQUEST["show_paypal"]!="Y") 		$_SESSION["opt_show_paypal"] 	  = "N";   
    if($_REQUEST["show_prepayment"]!="Y") 	$_SESSION["opt_show_prepayment"]  = "N";   
    if($_REQUEST["show_accountdata"]!="Y") 	$_SESSION["opt_show_accountdata"] = "N";   
    if($_REQUEST["show_docemail"]!="Y") 	$_SESSION["opt_show_docemail"]    = "N";   



}

?>


<span class="htitle">Offene Rechnungen</span><br>
<br>

<form action="module/biz/rechnungen_offen.php?set_options=true" method="post">

<table width="740" border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
    <td colspan="4"><b>Anzeige Optionen</b></td>
</tr>
<tr class="tr">
    <td><input type="checkbox" name="show_debit" value="Y"   <?if($_SESSION["opt_show_debit"]   == "Y") echo " checked";?>> Zahlung per Lastschrift</td>
    <td><input type="checkbox" name="show_invoice" value="Y" <?if($_SESSION["opt_show_invoice"] == "Y") echo " checked";?>> Zahlung per Rechnung</td>
    <td><input type="checkbox" name="show_paypal" value="Y"   <?if($_SESSION["opt_show_paypal"]   == "Y") echo " checked";?>> Zahlung per Paypal</td>
    <td><input type="checkbox" name="show_prepayment" value="Y"   <?if($_SESSION["opt_show_prepayment"]   == "Y") echo " checked";?>> Zahlung per Vorkasse</td>
</tr>
<tr class="tr">
    <td><input type="checkbox" name="show_accountdata" value="Y" <?if($_SESSION["opt_show_accountdata"] == "Y") echo " checked";?>> Kontodaten ausgeben</td>
    <td><input type="radio" name="show_docemail" value="Y" <?if($_SESSION["opt_show_docemail"] == "Y") echo " checked";?>> Versand per E-Mail <input type="radio" name="show_docemail" value="N" <?if($_SESSION["opt_show_docemail"] == "N") echo " checked";?>> Versand per Post </td>
    <td></td>
    <td align="right"><input type="submit" value="Speichern"></td>
</tr>
</table>


</td>
</tr>
</table>
</form>

<br>

<form action="module/biz/rechnungen_offen.php?action=dta&dtaintervall=true&start=<?=$_REQUEST[start]?>" method="post">
<table width="740" border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
    <td colspan="5"><b>DTA Export</b></td>
</tr>

<tr class="tr">
    <td>Ab Rechnungsnummer</td>
    <td align="center"> <input type="text" name="rb" size="10"> </td>
    <td>bis Rechnungsnummer</td>
    <td align="center"> <input type="text" name="re" size="10"> </td>
    <td align="right"> <input type="submit" value="Exportieren"></td>

</tr>
</table>

</td>
</tr>
</table>
</form>

<?if($_REQUEST[pdflink]!="") {?>&raquo; <?echo $_REQUEST[pdflink];?><?}?>
<?if($dtaus==true) {?>-> <a href="module/biz/dtaus_show.php">DTA Textfile &ouml;ffnen</a><br><?}?>

<br>

<form action="module/biz/rechnungen_offen.php?start=<?=$_REQUEST[start]?>" method="post">
<table width="740" border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
<td width="16"><b><img src="img/pixel.gif" border="0" width="1" height="1"></b></td>
<td><b>ReNr</b></td>
<td><b>Verschickt</b></td>
<td><b>Gemahnt</b></td>
<td><b>Name</b></td>
<td><b>KdNr</b></td>
<td><b>Gesamtbetrag</b></td>
<td><b>Zahlungsart</b></td>
<td><b>Buchungsdatum</b></td>
<td width="16"><img src="img/pixel.gif" border="0" width="1" height="1"></td>
<?
if(!isset($_REQUEST[start]) or $_REQUEST["start"]=="") $_REQUEST["start"]  = 0;

$res = $db->query("select * from biz_rechnungen, biz_profile 
		   where biz_rechnungen.profilid = biz_profile.profilid 
		   and biz_rechnungen.status='unbezahlt'
		   or biz_rechnungen.status like '%gemahnt%'
		   order by biz_rechnungen.rechnungid DESC");

$numrecords = $db->num_rows($res);

$res = $db->query("select * from biz_rechnungen, biz_profile 
		   where biz_rechnungen.profilid = biz_profile.profilid
		   and biz_rechnungen.status='unbezahlt'
		   or biz_rechnungen.status like '%gemahnt%'
		   order by biz_rechnungen.rechnungid DESC limit ".$_REQUEST["start"].",200");

$options = "";
/*
if($_SESSION["opt_show_debit"]=="Y" and $_SESSION["opt_show_invoice"] == "Y") 
    $options = "#lastschrift# #rechnung#";
if($_SESSION["opt_show_debit"]=="Y" and $_SESSION["opt_show_invoice"] != "Y") 
    $options = "#lastschrift#";
if($_SESSION["opt_show_debit"]!="Y" and $_SESSION["opt_show_invoice"] == "Y") 
    $options = "#rechnung#";
*/
if($_SESSION["opt_show_debit"] =="Y") 		$options .= "#lastschrift#";
if($_SESSION["opt_show_invoice"] == "Y") 	$options .= "#rechnung#";
if($_SESSION["opt_show_paypal"] == "Y") 	$options .= "#paypal#";
if($_SESSION["opt_show_prepayment"] == "Y")	$options .= "#vorkasse#";

    
if($_SESSION["opt_show_docemail"] == "Y") $options .= " #docY#";
if($_SESSION["opt_show_docemail"] != "Y") $options .= " #docN#";

while($rechnung = $db->fetch_array($res)) 
{
    $kunde = $db->queryfetch("select * from biz_kunden where kundenid='".$rechnung["kundenid"]."' ");

    $kundenmwst = $kunde[mwst];
    $datum = fn_make_Date($rechnung[datum]);

    if($dtaus == true and $kunde[bezahlart]=="lastschrift")
    {
	$summe = 0;
        $pos = explode("<br>",$rechnung[positionen]);
	for($i=0;$i<count($pos);$i++) 
	{
	    $entry  = explode("|",$pos[$i]);
	    if($entry[0]!="") 
	    {
    		$entry[0] = sprintf("%.2f",$entry[0]);
		$summe = $summe + ($entry[2] * $entry[0]);
	    }
	}
	if($biz_inputnetto == true) $summe = $summe * "1.$kundenmwst";

        $summe = sprintf("%.2f",$summe);
	if(in_array($rechnung[rechnungid],$_REQUEST[ausw])) 
	{
	    fputs($fp,"\$dta->addExchange(
		array(
		    \"name\"           => \"".$kunde[kontoinhaber]."\",    
		    \"bank_code\"      => ".$kunde[bankleitzahl].",           
		    \"account_number\" => ".$kunde[kontonummer].",           
		),
		".$summe.",                                      
		array(                                      
		    \" \",
		    \"Rechnung ".$rechnung[rechnungid]."\"
		)
		);
	    \n");

	}
    }

    if (strstr($options,$kunde["bezahlart"])!=false and strstr($options,"doc".$kunde["sendmail"])!=false) 
    //if(true)
    {
?>
    </tr>
    <tr class="tr">
	<td width="20"><input type="checkbox" name="ausw[]" value="<?=$rechnung[rechnungid]?>"></td>
	<td><a href="module/biz/rechnung_show.php?rechnungid=<?=$rechnung[rechnungid]?>" target="_blank"><?=$rechnung[idprefix]?><?=$rechnung[rechnungid]?><?=$rechnung[idsuffix]?></a></td>
	<td><?=$datum?></td>
	<td><?
	    if($rechnung[status]=="gemahnt" or $rechnung[status]=="gemahnt2"  or $rechnung[status]=="gemahnt3")
		echo substr($rechnung[mahndatum],8,2).".".substr($rechnung[mahndatum],5,2).".".substr($rechnung[mahndatum],0,4);
	    ?>
	</td>
	<td><a href="module/biz/kunden_detail.php?kundenid=<?=$rechnung[kundenid]?>"><?=$kunde[nachname]?>, <?=$kunde[vorname]?></a>
	<?if($kunde[firma] != "") echo "<br>".$kunde[firma]."\n"; ?>
	<?if($_SESSION["opt_show_accountdata"] == "Y") echo "<br><font size=\"1\">".$kunde[kontoinhaber].", Kto ".$kunde[kontonummer].", Blz ".$kunde[bankleitzahl]."</font>\n";?>
	</td>
	<td><?=$rechnung[kundenid]?></td>
	<td align="right">
	<?
	$restotal = $db->query("select * from biz_rechnungen where rechnungid=".$rechnung[rechnungid]);
	$_records = array();

	while($rowtotal = $db->fetch_array($restotal))
	    $_records[] = array("kunde" => explode("|", $rowtotal['anschrift']), "positionen" => $rowtotal["positionen"]);

	$gesamt = 0;

	$i = 0;
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

	    for($z = 0; $z < count($ausgabe); $z++)
		$gesamt += $ausgabe[$z];
	}

	echo sprintf("%.2f",$gesamt)." ".$biz_settings[waehrung]."<br>";
	$offenerbetrag = sprintf("%.2f",$gesamt)-sprintf("%.2f",$rechnung[buchungsbetrag]);
	
	if($rechnung[buchungsdatum] != '0000-00-00')
	    echo '<font size="-3">Offen: '.sprintf("%.2f",$offenerbetrag)." ".$biz_settings[waehrung]."</font>";
	?>
	</td>
	<td><?=$kunde[bezahlart]?></td>
	<?
	if($rechnung[buchungsdatum]!='0000-00-00') {
	?>
	<td><?=fn_make_Date($rechnung[buchungsdatum])?></td>
	<?
	} else {
	?>
	<td><img src="img/pixel.gif" width="1" height="1"></td>
	<?
	}
	?>
	<td><a href="module/biz/rechnung_show.php?rechnungid=<?=$rechnung[rechnungid]?>" target="_blank"><img src="img/pdf.gif" width="16" border="0"></a></td>
    </tr>
    <?
    } // ende if abfrage
    
} // ende while schleife
?>

</table>
</td>
</tr>
</table>
<?
if($_REQUEST[action]=="bezahlt") {
?>
<br>
Bitte markieren Sie die Rechnung und tragen Sie das Datum, sowie den Betrag bei den Rechnungen ein, die als bezahlt markiert werden sollen.

<br>
<?
}
?>
<br>
<select name="action">
	<option value="mahnen">Mahnen</option>
	<option value="bezahlt">Bezahlt</option>
	<option value="storniert">Stornieren</option>
	<option value="dta">DTA Export</option>
	<option value="pdf">PDF Sammlung</option>
	<option value="delete">Löschen</option></select>
	<input type="submit" value="Abschicken">
</form>

<br>
<br>
<?
if($dtaus==true)
{
    fputs($fp,"header(\"Content-Disposition: attachment; filename="."\\"."\"dtaus.txt\\\""."\");\n");
    fputs($fp,"header(\"Content-type: text/plain\");\n");
    fputs($fp,"header(\"Cache-control: public\");\n");
    fputs($fp,"print \$dta->getFileContent();\n?>");
    fclose($fp);
}

for($i = 1; $i <= ceil($numrecords / 200); $i++) 
{ 
    $s = (($i-1) * 200); 
    echo "<a href=\"module/biz/rechnungen_offen.php?start=".$s."\"><b>".$i."</b></a> <img src=\"img/pixel.gif\" height=\"1\" width=\"5\">";
    if(($i % 40) == 0) echo "<br>\n"; 
}
?>
<br>
<br>



<?include("../../footer.php");?>
