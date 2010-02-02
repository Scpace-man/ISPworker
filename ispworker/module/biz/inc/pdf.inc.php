<?/*

    12.02.2006, ar, #3
    Pay Buttons eingefügt.

    30.12.2006, ar, #2
    Umstellung der Mehrwertsteuer-Berechnung.
    Jede Rechnung speichert zusätzlich den Mehrwertsteuersatz.
    Das neue Feld "mwst" muss der Tabelle biz_rechnungen hinzugefügt werden.
    Ist das Feld "mwst" nicht belegt, wird der Satz aus den Kundendaten ausgelesen.


    22.03.2006, ar, #1
    Permanente Datumsprefix-Anzeige korrigiert.

*/

// Dirty Kundenmenü Hack

if(CONF_PATHFPDF=="") {
	define('FPDF_FONTPATH','./fpdf/font/');
	require('./fpdf/fpdf.php');
}
else {
	define('FPDF_FONTPATH',CONF_PATHFPDF."/font/");
	require(CONF_PATHFPDF."/fpdf.php");
}


function pdfinvoice_addtax($value, $tax, $format="N") 
{ 
    if(strstr($tax,'.')) {
	$x = explode('.',$tax);
	if(strlen($x[0]) == 2) $tax = "1.".$x[0].$x[1];
	if(strlen($x[0]) == 1) $tax = "1.0".$x[0].$x[1];
    }
    else $tax = "1.".$tax;

    if($format == "N") 	return ($value * $tax);
    else		return sprintf("%.2f",($value * $tax));
}


function pdfinvoice_subtax($value, $tax, $format="N")
{
    if(strstr($tax,'.')) {
	$x = explode('.',$tax);
	if(strlen($x[0]) == 2) $tax = "1.".$x[0].$x[1];
	if(strlen($x[0]) == 1) $tax = "1.0".$x[0].$x[1];
    }
    else $tax = "1.".$tax;

    if($format == "N") 	return ($value / $tax);
    else		return sprintf("%.2f",($value / $tax));
}




function pdfinvoiceheader($row,$co,$layout) {

	global $pdf;
	global $biz_imgpath;
	global $biz_temppath;
	global $biz_printnetto;
	global $biz_inputnetto;
	global $seite;
	global $invoicesubject;

	$pdf->AddPage();
	$pdf->SetAutoPageBreak(true); 

	$coords=explode(",",$layout[logoxy]);
	//Größe des Logos wird mit $co[logo_w] und $co[logo_h] festgelegt.
	$pdf->Image($biz_imgpath."/logo".$co[profilid].".jpg",$coords[0],$coords[1],$co[logo_w],$co[logo_h]);
	//$co[logo_w],$co[logo_h]);

	$pdf->SetTextColor(0);

	// Anschrift und Kontaktdaten auslesen und die Platzhalter aus dem PDF-Layout
	// mit den Werten aus Firmenprofile ersetzen, sofern Platzhalter vorhanden sind
	$coords=explode(",",$layout[feld1xy]);
	$pdf->SetFont('Arial','B',9);
	$pdf->Text($coords[0],$coords[1],ersetze($co,$layout[feld1])); //155,15
	$pdf->SetFont('Arial','',9);
	$coords=explode(",",$layout[feld2xy]);	
	$pdf->Text($coords[0],$coords[1],ersetze($co,$layout[feld2]));	//155,19
	$coords=explode(",",$layout[feld3xy]);	
	$pdf->Text($coords[0],$coords[1],ersetze($co,$layout[feld3]));	//155,23
	$coords=explode(",",$layout[feld4xy]);
	$pdf->Text($coords[0],$coords[1],ersetze($co,$layout[feld4])." ".ersetze($co,$layout[feld5]));	//155,31
	$coords=explode(",",$layout[feld5xy]);	
	$pdf->Text($coords[0],$coords[1],ersetze($co,$layout[feld6]));	//155,39
	$coords=explode(",",$layout[feld6xy]);	
	$pdf->Text($coords[0],$coords[1],ersetze($co,$layout[feld7]));	//155,43
	$coords=explode(",",$layout[feld7xy]);	
	$pdf->Text($coords[0],$coords[1],ersetze($co,$layout[feld8]));	//155,50
	$coords=explode(",",$layout[feld8xy]);	
	$pdf->Text($coords[0],$coords[1],ersetze($co,$layout[feld9]));	//155,54
	$pdf->SetFont('Arial','',8);
	$coords=explode(",",$layout[feld9xy]);
	$pdf->Text($coords[0],$coords[1],ersetze($co,$layout[feld10]));	//20,52

	// Falzmarken
	$pdf->SetDrawColor(0);

	$pdf->Line(2, 105, 6, 105);
	$pdf->Line(2, 210, 6, 210);


	// Datum

	$timestamp = strtotime($row[datum]);
	$datum     = date("d.m.Y",$timestamp);
	$pdf->SetFont('Arial','',9);
	$pdf->Text(155,69,"Datum: $datum");

	// Seite
	$seite++;
	$pdf->Text(155,97,"Seite $seite");

	// Anschrift des Kunden

	$an = explode("|",$row[anschrift]);
	$pdf->SetFont('Arial','',10);
	if(strstr($an[0],",")) {
	    $fn = explode(",",$an[0]);
	    $pdf->Text(20,59,trim($fn[0]));
	    $pdf->Text(20,63,trim($fn[1]));
	    $pdf->Text(20,67,$an[1]); 	
	    $pdf->Text(20,75,$an[2]);
	}
	else {
	    $pdf->Text(20,59,$an[0]); 	
	    $pdf->Text(20,63,$an[1]); 	
	    $pdf->Text(20,71,$an[2]);	
	    //$pdf->Text(20,73,$an[3]); Landesangabe alt, Ländercode wird in Plz verwendet
	}
    
	// Betreff und Rechnungsnummer + Kundennummer

	$pdf->SetFont('Arial','B',14);
	if($row[status]=="storniert") 
	    $pdf->Text(20,90,'Rechnung (storniert)');
	else
	    $pdf->Text(20,90,'Rechnung');

	$pdf->SetFont('Arial','',9);
	
	// #1 BEGIN
	if($co[datumprefix]!="")
	{	 
	    if(substr($row[datum],0,4)==date("$co[datumprefix]")) $dp = date("$co[datumprefix]")."-";
	    else $dp = substr($row[datum],0,4)."-";
	}
	// #1 END
	
	$invoicesubject = "Rechnung-Nr.: $dp$co[idprefix]$row[rechnungid]$co[idsuffix]";
	$pdf->Text(20,97,$invoicesubject." / Kunden-Nr.: $row[kundenid]");
	

	// Kopf der Artikel-Tabelle
	$pdf->Text(20,110,'Pos.');				//POSITIONEN dementsprechend verschieben sich die Spalten!
	$pdf->Text(30,110,'Menge');
	$pdf->Text(46,110,'Beschreibung');
	if($biz_printnetto==false && $biz_inputnetto==false) 
	    $pdf->Text(153,110,'Preis brutto');
	else 
	    $pdf->Text(153,110,'Preis netto');
	
	$pdf->Text(178,110,'Summe');
	$pdf->Line(20, 111, 190, 111);

}


function pdfinvoicefooter_sum($row,$co,$total) {

	global $db;
	global $pdf;
	global $biz_mwst;
	global $biz_mwstglobal;
	global $biz_printnetto;
	global $biz_inputnetto;
	global $biz_include_paybuttons;
	global $invoicesubject;

	$pdf->Line(130, 249, 190, 249);
	$pdf->Line(130, 250, 190, 250);

	$pdf->SetTextColor(0);
	$pdf->SetFont('Arial','B',9);
	
	$pdf->Text(130,235,"Summe netto:");

	if($biz_inputnetto==false) 
	{
	    $netto = pdfinvoice_subtax($total,$biz_mwst,"Y");
	}
	else 
	{
	    $netto = $total;
	    $total = pdfinvoice_addtax($total,$biz_mwst);    
	}    
	
	if(strlen($netto)==4) { $xco = 175; }
	if(strlen($netto)==5) { $xco = 174; }
	if(strlen($netto)==6) { $xco = 173; }
	if(strlen($netto)==7) { $xco = 172; }
	if(strlen($netto)==8) { $xco = 172; }
	
	$currencySQL = $db->query("select waehrung from biz_settings");
	$currency = $db->fetch_array($currencySQL);

	$pdf->Text($xco,235,$netto);
		
	$pdf->Text(183,235,$currency['waehrung']);

	if($biz_printnetto==false)
	    $pdf->Text(130,240,"zzgl. $biz_mwst% MwSt.:");
	else 
	    $pdf->Text(130,240,"zzgl. $biz_mwstglobal% MwSt.:");
	

	$mwst = $total - $netto;
	$mwst = sprintf("%.2f",$mwst);

	if(strlen($mwst)==4) { $xco = 175; }
	if(strlen($mwst)==5) { $xco = 174; }
	if(strlen($mwst)==6) { $xco = 173; }
	if(strlen($mwst)==7) { $xco = 172; }
	if(strlen($mwst)==8) { $xco = 172; }

	
	$pdf->Text($xco,240,"$mwst");
	$pdf->Text(183,240,$currency['waehrung']);


	$pdf->Line(130, 243, 190, 243);

	$pdf->Text(130,247,"Summe brutto:");

	if(strlen($total)==4) { $xco = 175; }
	if(strlen($total)==5) { $xco = 174; }
	if(strlen($total)==6) { $xco = 173; }
	if(strlen($total)==7) { $xco = 172; }
	if(strlen($total)==8) { $xco = 172; }
	
	
	$pdf->Text($xco,247,$total);
	$pdf->Text(183,247,$currency['waehrung']);

	// #3 BEGIN
	include_once(dirname(__FILE__)."/pdfpaybuttons.inc.php");
	pdfinvoicepaybuttons($invoicesubject,$total,$currency['waehrung']);
	// #3 END

	$pdf->SetFont('Arial','B',9);

//Zeilenumbrüche für Rechnungskommentar und Kommentare zur Rechnung
	$kommentar=explode("\n",$row[kommentar]);
	$ky=255;
	for($i=0;$i<count($kommentar);$i++){
		if($i==0){
			$pdf->Text(20,$ky,$kommentar[$i]);
		}else{
			$pdf->Text(20,$ky,$kommentar[$i]);	
		}
		$ky+=5;		
	}

	$pdf->Text(20,$ky,$row[rechnungstext]);
}


function pdfinvoicefooter($row,$co,$layout) {

	global $pdf;

	$pdf->Line(20, 230, 190, 230);

	$pdf->SetDrawColor(100);
	$pdf->Line(5, 280, 205, 280);

	$pdf->SetFont('Arial','',8);
	$pdf->SetTextColor(100);
	$coords=explode(",",$layout[feld10xy]);	
	$pdf->Text($coords[0],$coords[1],ersetze($co,$layout[feld11]));			//"Inhaber: $co[bankinhaber]");	5,285
	$coords=explode(",",$layout[feld11xy]);	
	$pdf->Text($coords[0],$coords[1],ersetze($co,$layout[feld12]));			//"$co[bankinstitut], Blz $co[bankblz]"); 5,289
	$coords=explode(",",$layout[feld12xy]);	
	$pdf->Text($coords[0],$coords[1],ersetze($co,$layout[feld13]));			//"Konto $co[bankkonto]"); 5,293

//	$pdf->Line(60,280,60,295);
	$coords=explode(",",$layout[feld13xy]);
	$pdf->Text($coords[0],$coords[1],ersetze($co,$layout[feld14]));			//$co[mail]); 75,285
	$coords=explode(",",$layout[feld14xy]);	
	$pdf->Text($coords[0],$coords[1],ersetze($co,$layout[feld15]));			//$co[homepage]);75,289
//	if($co[steuerid]!="") {
	$coords=explode(",",$layout[feld15xy]);
	$pdf->Text($coords[0],$coords[1],ersetze($co,$layout[feld16]));			//"SteuerID: ".$co[steuerid]);	75,293
//	}
	
//	if($co[umsatzsteuerid]!="") {
	$coords=explode(",",$layout[feld16xy]);
	$pdf->Text($coords[0],$coords[1],ersetze($co,$layout[feld17]));	//"UmsatzsteuerID: ".$co[umsatzsteuerid]); 145,285
		
//	}

//	if($co[bankiban]!="") {
	$coords=explode(",",$layout[feld17xy]);
	$pdf->Text($coords[0],$coords[1],ersetze($co,$layout[feld18]));	//"IBAN: ".$co[bankiban]);	145,289
//	}

//	if($co[bankbic]!="") {
	$coords=explode(",",$layout[feld18xy]);
	$pdf->Text($coords[0],$coords[1],ersetze($co,$layout[feld19]));	//"BIC: ".$co[bankbic]);	145,293
//	}

	$pdf->SetTextColor(0);

}








function pdfinvoice($rechnungid) 
{

    global $biz_mwstglobal;
    global $biz_mwst;
    global $biz_inputnetto;
    global $biz_printnetto;
    global $pdf;
    global $biz_temppath;	
    global $db;
    global $directoutput;
    global $seite;

    $seite = 0;

    // Mehrere Rechnungen vereint in einem Dokument
    global $multipdf;
    global $closemultipdf;
    global $openedmultipdf;


    $res = $db->query("select * from biz_rechnungen where rechnungid='$rechnungid'");
    $row = $db->fetch_array($res);

    $biz_mwst = $row["mwst"];

    $resk = $db->query("select * from biz_kunden where kundenid='$row[kundenid]'");
    $rowk = $db->fetch_array($resk);

    $row[bezahlart] = $rowk[bezahlart];
    $row[rechnungstext] = $rowk[rechnungstext];

    if($biz_mwst == "" or $biz_mwst == "-1") $biz_mwst = $rowk[mwst];

    $resp = $db->query("select profilid,idprefix,idsuffix,datumprefix,firma,telefon,fax,inhaber,strasse,plz,ort,mail,homepage,bankinhaber,bankkonto,
						bankinstitut,bankblz,bankiban,bankbic,umsatzsteuerid,steuerid,logo_h,logo_w from biz_profile where profilid='$row[profilid]'");
    $co = $db->fetch_array($resp);

    $layoutsql = $db->query("select feld1,feld2,feld3,feld4,feld5,feld6,feld7,feld8,feld9,
    feld10,feld11,feld12,feld13,feld14,feld15,feld16,feld17,feld18,feld19,profilid,adminid,
    feld1xy,feld2xy,feld3xy,feld4xy,feld5xy,feld6xy,feld7xy,feld8xy,feld9xy,feld10xy,feld11xy,
    feld12xy,feld13xy,feld14xy,feld15xy,feld16xy,feld17xy,feld18xy,logoxy
    from biz_layout where profilid='$row[profilid]'");
    $layout = $db->fetch_array($layoutsql);

    if($row[anschrift]=="") {
    	die();
    }

    // Prüfen, ob multipdf aktiv
    $initdoc  = true;  
    $printdoc = true;
    
    if($multipdf==true)
        { $printdoc = false; }

    if($openedmultipdf==true) 
        { $initdoc = false; }

    if($closemultipdf==true) 
        { $printdoc = true; }

    if($initdoc==true) {
        $pdf=new FPDF();
        $pdf->Open();
    }


    pdfinvoiceheader($row,$co,$layout);
    pdfinvoicefooter($row,$co,$layout);


    $pos    = explode("<br>",$row[positionen]);
    $posanz = count($pos);

    $y_pos = 116;
    
    // Zählt Positionen je Seite, wenn 7. Position erreicht dann neue Seite
    $poscounter = 1;
	
    for($i=0;$i<$posanz;$i++) 
    {
    
	// $feld[0] = Anzahl					
	// $feld[1] = Produktbezeichnung		
	// $feld[2] = Preis					
	// $feld[3] = Kommentar				

	$feld  = explode("|",$pos[$i]);
	
	$ppp = $feld[2];
	
	if($feld[2]!="") 
	{
	    //Positionen ausgeben

	    if($y > 225 or $poscounter == 8) 
	    { 
	        $poscounter = 1;
	        $y_pos = 116;
	        pdfinvoiceheader($row,$co,$layout);
	        pdfinvoicefooter($row,$co,$layout);
	    }


	    $pdf->Text(23,$y_pos,($i+1));
	    $pdf->Text(33,$y_pos,"$feld[0]");

    	    if(strlen($feld[2])==4) { $xco = 160; }
    	    if(strlen($feld[2])==5) { $xco = 159; }
    	    if(strlen($feld[2])==6) { $xco = 158; }
	    if(strlen($feld[2])==7) { $xco = 157; }
    	    if(strlen($feld[2])==8) { $xco = 156; }

    	    // Artikel Preis, Brutto Berechnung

	    if($biz_inputnetto == false) {
		$feld[2] = pdfinvoice_subtax($feld[2],$biz_mwstglobal);
    		$feld[2] = pdfinvoice_addtax($feld[2],$biz_mwst, "Y");
    	    }
	    
	    
    	    // Wenn Rabatt angegeben
    	    if(strstr($feld[3],"Rabatt:")) {
		$tempx = explode("Rabatt: ",$feld[3]);
		$tempy = explode("%",$tempx[1]);
		$rabattp = $tempy[0];	    
	    
		$rabatt = ($feld[2] * $rabattp) / 100;
		$rabatt = sprintf("%.2f",$rabatt); 

		if($biz_printnetto==false)
		    $feld[3] = str_replace("Rabatt: $rabattp"."%", "Rabatt: $rabattp"."%, Originalpreis: $feld[2]", $feld[3]);
		else 
		{
	    	    $feld2netto = pdfinvoice_subtax($feld[2],$biz_mwstglobal);
	    	    
		    //$feld2netto = sprintf("%.2f",$feld2netto);	
	    	    		    
		    $feld[3] = str_replace("Rabatt: $rabattp"."%", "Rabatt: $rabattp"."%, Originalpreis: $feld2netto", $feld[3]);
		}
	    
		$feld[2] = $feld[2] - $rabatt;
		$feld[2] = sprintf("%.2f",$feld[2]);
	    }
	    
	    $currencySQL = $db->query("select waehrung from biz_settings");
	    $currency = $db->fetch_array($currencySQL);
	
	    if($biz_printnetto==false)
		$pdf->Text(($xco-3),$y_pos,"$feld[2] ".$currency['waehrung']);
	    else
	    {
		$feld2netto = pdfinvoice_subtax($feld[2],$biz_mwstglobal,"Y");
		$pdf->Text(($xco-3),$y_pos,$feld2netto." ".$currency['waehrung']);
	    }
	    
	    $summe = $feld[0] * $feld[2];
	    $summe = sprintf("%.2f",$summe);

	    $total = $summe + $total;
	    $total = sprintf("%.2f",$total);

	    if(strlen($summe)==4) { $xco = 180; }
	    if(strlen($summe)==5) { $xco = 179; }
	    if(strlen($summe)==6) { $xco = 178; }
	    if(strlen($summe)==7) { $xco = 177; }
	    if(strlen($summe)==8) { $xco = 176; }

	    if($biz_printnetto==false)
		$pdf->Text(($xco-3),$y_pos,$summe." ".$currency['waehrung']);
	    else
	    {
		$summenetto = pdfinvoice_subtax($summe,$biz_mwstglobal,"Y");
		$pdf->Text(($xco-3),$y_pos,$summenetto." ".$currency['waehrung']);
	    }
    
	    $feld[1]  = "$feld[1]$feld[3]";
	    $zeile    = explode("\n",$feld[1]);
	    $zeileanz = count($zeile);
	    $y        = $y_pos;


	    for($j=0;$j<$zeileanz;$j++) 
	    {
    		if($y > 225) 
		{
		    $poscounter = 1;
		    $y_pos = 116;
		    $y = 116;
	
		    pdfinvoiceheader($row,$co,$layout);
		    pdfinvoicefooter($row,$co,$layout);
		}

		$pdf->Text(46,$y,$zeile[$j]);
		$y = $y + 4;    
	    }


/*


	    for($j=0;$j<$zeileanz;$j++) 
	    {
    		if($y >= 224) 
		{
		    $y_pos = 116;
		    $y = 114;
	
		    pdfinvoiceheader($row,$co,$layout);
		    pdfinvoicefooter($row,$co,$layout);
		    $i--;
		    break; // hier kommt normalerweise gleich die nächste postion
		}

		$pdf->Text(46,$y,$zeile[$j]);
		$y = $y + 4;    
	    }



*/







    	    $y_pos = $y + 3;
	    $poscounter++;
	} // if 
    } // for
    

    pdfinvoicefooter_sum($row,$co,$total);

    if($printdoc == true) 
    {
	$pdf->SetDisplayMode("real","default");
	if($directoutput==true)
	    $pdf->Output();
	else 
	{
	    $pdf->Output("$biz_temppath/r-".$rechnungid.".pdf");
	    chmod ("$biz_temppath/r-".$rechnungid.".pdf", 0777);
	}
    }
}

function ersetze($co,$feldname){

	$feldname=str_replace("#firma#",$co[firma],$feldname);
	$feldname=str_replace("#inhaber#",$co[inhaber],$feldname);
	$feldname=str_replace("#strasse#",$co[strasse],$feldname);
	$feldname=str_replace("#plz#",$co[plz],$feldname);	
	$feldname=str_replace("#ort#",$co[ort],$feldname);
	$feldname=str_replace("#mail#",$co[mail],$feldname);
	$feldname=str_replace("#telefon#",$co[telefon],$feldname);
	$feldname=str_replace("#fax#",$co[fax],$feldname);
	$feldname=str_replace("#homepage#",$co[homepage],$feldname);
	$feldname=str_replace("#bankinhaber#",$co[bankinhaber],$feldname);
	$feldname=str_replace("#bankinstitut#",$co[bankinstitut],$feldname);
	$feldname=str_replace("#bankkonto#",$co[bankkonto],$feldname);	
	$feldname=str_replace("#bankblz#",$co[bankblz],$feldname);
	$feldname=str_replace("#bankiban#",$co[bankiban],$feldname);
	$feldname=str_replace("#bankbic#",$co[bankbic],$feldname);
	$feldname=str_replace("#umsatzsteuerid#",$co[umsatzsteuerid],$feldname);
	$feldname=str_replace("#steuerid#",$co[steuerid],$feldname);
	
	return $feldname;
}


?>
