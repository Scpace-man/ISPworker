<?

// Dirty Kundenmenü Hack

if(CONF_PATHFPDF=="") {
	define('FPDF_FONTPATH','./fpdf/font/');
	require('./fpdf/fpdf.php');
}
else {
	define('FPDF_FONTPATH',CONF_PATHFPDF."/font/");
	require(CONF_PATHFPDF."/fpdf.php");
}


function pdfmahnheader($row,$rowk,$co,$layout) {

	global $pdf;
	global $biz_imgpath;
	global $biz_temppath;
	global $seite;
	global $db;

	$kunres = $db->query("select bezahlart from biz_kunden where kundenid='$row[kundenid]'");
        $kunrow = $db->fetch_array($kunres);
	     
	if($kunrow["bezahlart"]!="lastschrift") $row["ruecklastgebuehr"] = "0.00";
			 
	$currencySQL = $db->query("select waehrung from biz_settings");
	$currency = $db->fetch_array($currencySQL);

	$res = $db->query("select * from biz_mahntemplates where templateid='$row[templateid]'");
	$tpl = $db->fetch_array($res);

	$pdf->AddPage();
	$coords=explode(",",$layout[logoxy]);
	$pdf->Image($biz_imgpath."/logo".$co[profilid].".jpg",$coords[0],$coords[1],$co[logo_w],$co[logo_h]);

	$pdf->SetTextColor(0);

	// Anschrift und Kontaktdaten

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


	// Datum

	$timestamp = strtotime($row[datum]);
	$datum     = date("d.m.Y",$timestamp);
	$pdf->SetFont('Arial','',9);
	$pdf->Text(155,69,"Datum: $datum");

	// Anschrift des Kunden

	$pdf->SetFont('Arial','',10);
	$pdf->Text(20,57,$rowk[firma]);
	$pdf->Text(20,61,$rowk[anrede]." ".$rowk[vorname]." ".$rowk[nachname]);
	$pdf->Text(20,65,$rowk[strasse]);
	$pdf->Text(20,73,$rowk[plz]." ".$rowk[ort]);

	if($rowk[anrede]=="Herr") { $anrede = "Sehr geehrter Herr $rowk[nachname]"; }
	elseif($rowk[anrede]=="Frau") { $anrede = "Sehr geehrte Frau $rowk[nachname]"; }
	else { $anrede = "Sehr geehrte Damen und Herren"; }

	$tpl[pretext] = str_replace("#volleanrede#",$anrede,$tpl[pretext]);

	$pdf->SetFont('Arial','B',14);
	$pdf->Text(20,85,$tpl[templatename]);
	$pdf->SetFont('Arial','',9);
	
	$pdf->Text(20,92,"Kunden-Nr.: $row[kundenid]");


	$pdf->SetFont('Arial','',10);

	$y = 105;
	$pl = explode("<br>",$tpl[pretext]);
	
	for($i=0;$i<count($pl);$i++) {
	    $pdf->text(20,$y,$pl[$i]);
	    $y = $y + 4;
	}

	$pdf->SetFont('Arial','B',9);
	$pdf->Text(20,$y + 7,'Rechnungsnummer');
	$pdf->Text(90,$y + 7,'Rechnungsdatum');
	$pdf->Text(160,$y + 7,'Betrag in '.$currency['waehrung']);

	$pdf->SetDrawColor(100);
	$pdf->Line(20, $y + 9, 190, $y + 9);

	$y = $y + 15;
	
	$pdf->SetFont('Arial','',9);
	
	$p = explode(";",$row[positionen]);
		
	for($i=0;$i<count($p);$i++) {
	    if($p[$i]!="")
	    {
		$resr = $db->query("select * from biz_rechnungen where rechnungid='$p[$i]'");
		$rowr = $db->fetch_array($resr);
	    
		$rt = strtotime($rowr[datum]);
		$rd = date("d.m.Y",$rt);
	    
		$pdf->Text(21,$y,$p[$i]);
		$pdf->Text(91,$y,$rd);
	    
		$summe = 0;
    		$pos = explode("<br>",$rowr[positionen]);
        	for($j=0;$j<count($pos);$j++) {
		    $entry  = explode("|",$pos[$j]);
		    if($entry[0]!="") {
			$entry[0] = sprintf("%.2f",$entry[0]);
			$summe = $summe + ($entry[2] * $entry[0]);
			$summe = sprintf("%.2f",$summe);
		    }
		}
		$total = $total + $summe;		

		$pdf->Text(161,$y,$summe);
		$y = $y + 5;
	    }
	}
		
	$pdf->Line(20, 220, 190, 220);

	$pdf->SetFont('Arial','B',10);

	// Mahngebuehr
	if($row[mahngebuehr]!="" and $row[mahngebuehr]!="0.00") 
	{
	    $row[mahngebuehr] = sprintf("%.2f",$row[mahngebuehr]);

	    $n = strlen($row[mahngebuehr]);
    	    if($n==8) $x = 153;	
    	    if($n==7) $x = 155;
    	    if($n==6) $x = 157;
	    if($n==5) $x = 159;
	    if($n==4) $x = 161;
	    
	    $pdf->Text(20,227,'Mahngebühren in '.$currency['waehrung']);
    	    $pdf->Text($x,227,$row[mahngebuehr]);

	    $total = $total + $row[mahngebuehr];
	}

        $total = sprintf("%.2f",$total);

	// Ruecklastschriftgebuehr
	if($row[ruecklastgebuehr]!="" and $row[ruecklastgebuehr]!="0.00") 
	{
	    $row[ruecklastgebuehr] = sprintf("%.2f",$row[ruecklastgebuehr]);

	    $n = strlen($row[ruecklastgebuehr]);
    	    if($n==8) $x = 153;	
    	    if($n==7) $x = 155;
    	    if($n==6) $x = 157;
	    if($n==5) $x = 159;
	    if($n==4) $x = 161;
	    
	    $pdf->Text(20,235,'Rücklastschrift Gebühr in '.$currency['waehrung']);
    	    $pdf->Text($x,235,$row[ruecklastgebuehr]);

	    $total = $total + $row[ruecklastgebuehr];
	}

        $total = sprintf("%.2f",$total);

	$n = strlen($total);
        if($n==8) $x = 153;	
        if($n==7) $x = 155;
        if($n==6) $x = 157;
	if($n==5) $x = 159;
	if($n==4) $x = 161;
	
	$pdf->Text(20,243,'Gesamtbetrag in '.$currency['waehrung']);	    	
	$pdf->Text($x,243,$total);


	$tpl[posttext] = str_replace("#offengesamt#",sprintf("%.2f",$total)." ".$currency['waehrung'],$tpl[posttext]);

	$pdf->SetFont('Arial','',10);

	$u = explode("#frist-",$tpl[posttext]);
	$v = explode("#",$u[1]);
	
	$ft = strtotime("+".$v[0]." day",$timestamp);
	$frist = date("d.m.Y",$ft);
			
	$tpl[posttext] = $u[0].$frist.$v[1];
	
	$y = 253;
	$pl = explode("<br>",$tpl[posttext]);
	
	for($i=0;$i<count($pl);$i++) {
	    $pdf->text(20,$y,$pl[$i]);
	    $y = $y + 4;
	}
}



function pdfmahnfooter($row,$co,$layout) {

	global $pdf;

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








function pdfmahnung($mahnid) {

	global $biz_mwstglobal;
	global $biz_mwstkunde;
	global $pdf;
	global $biz_temppath;
	global $db;
	global $directoutput;
	global $seite;

	// Mehrere Rechnungen vereint in einem Dokument
	global $multipdf;
	global $closemultipdf;
	global $openedmultipdf;

	$res = $db->query("select * from biz_mahnungen where mahnid='$mahnid'");
	$row = $db->fetch_array($res);
	
	$resk = $db->query("select * from biz_kunden where kundenid='$row[kundenid]'");
	$rowk = $db->fetch_array($resk);

	$biz_mwstkunde = $rowk[mwst];

	$resp = $db->query("select profilid,idprefix,idsuffix,datumprefix,firma,telefon,fax,inhaber,strasse,plz,ort,mail,homepage,bankinhaber,bankkonto,
						bankinstitut,bankblz,bankiban,bankbic,umsatzsteuerid,steuerid,logo_h,logo_w from biz_profile where profilid='$row[profilid]'");
	$co = $db->fetch_array($resp);

	$layoutsql = $db->query("select feld1,feld2,feld3,feld4,feld5,feld6,feld7,feld8,feld9,
	feld10,feld11,feld12,feld13,feld14,feld15,feld16,feld17,feld18,feld19,profilid,adminid,
	feld1xy,feld2xy,feld3xy,feld4xy,feld5xy,feld6xy,feld7xy,feld8xy,feld9xy,feld10xy,feld11xy,
	feld12xy,feld13xy,feld14xy,feld15xy,feld16xy,feld17xy,feld18xy,logoxy
	from biz_layout where profilid='$row[profilid]'");
	$layout = $db->fetch_array($layoutsql);

	
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


	pdfmahnheader($row,$rowk,$co,$layout);
	pdfmahnfooter($row,$co,$layout);

/*
	$pos    = explode("<br>",$row[positionen]);
	$posanz = count($pos);


	$y_pos = 111;

	for($i=0;$i<$posanz;$i++) {

	  // $feld[0] = Anzahl
	  // $feld[1] = Produktbezeichnung
	  // $feld[2] = Preis
	  // $feld[3] = Kommentar

	  $feld  = explode("|",$pos[$i]);



	  if($feld[2]!="") {

	    $pdf->Text(23,$y_pos,"$feld[0]");

	    if(strlen($feld[2])==4) { $xco = 160; }
	    if(strlen($feld[2])==5) { $xco = 159; }
	    if(strlen($feld[2])==6) { $xco = 158; }
	    if(strlen($feld[2])==7) { $xco = 157; }
	    if(strlen($feld[2])==8) { $xco = 156; }


	    // Artikel Preis, Brutto Berechnung

	    
	    // Wenn Rabatt angegeben
	    if(strstr($feld[3],"Rabatt:")) {
		$tempx = explode("Rabatt: ",$feld[3]);
		$tempy = explode("%",$tempx[1]);
		$rabattp = $tempy[0];	    
	    
		$rabatt = ($feld[2] * $rabattp) / 100;
		$rabatt = sprintf("%.2f",$rabatt); 
		
		$feld[3] = str_replace("Rabatt: $rabattp"."%", "Rabatt: $rabattp"."%, Originalpreis: $feld[2]", $feld[3]);
		$feld[2] = $feld[2] - $rabatt;
		$feld[2] = sprintf("%.2f",$feld[2]);
	    }
	    
	    
	    
	    $feld[2] = $feld[2] / "1.$biz_mwstglobal"; // Durch Strings teilen, ich liebe PHP :)
	    $feld[2] = $feld[2] * "1.$biz_mwstkunde";
	    $feld[2] = sprintf("%.2f",$feld[2]);


	    $pdf->Text($xco,$y_pos,"$feld[2] €");

	    $summe = $feld[0] * $feld[2];
	    $summe = sprintf("%.2f",$summe);

	    $total = $summe + $total;
	    $total = sprintf("%.2f",$total);

	    if(strlen($summe)==4) { $xco = 180; }
		if(strlen($summe)==5) { $xco = 179; }
		if(strlen($summe)==6) { $xco = 178; }
		if(strlen($summe)==7) { $xco = 177; }
		if(strlen($summe)==8) { $xco = 176; }

	    $pdf->Text($xco,$y_pos,"$summe €");

	    $feld[1]  = "$feld[1]\n$feld[3]";
	    $zeile    = explode("\n",$feld[1]);
	    $zeileanz = count($zeile);
	    $y        = $y_pos;

	    for($j=0;$j<$zeileanz;$j++) {

			if($y >= 224) {
				$y_pos=100;
				$y = 0;
				pdfinvoiceheader($row,$co);
				pdfinvoicefooter($row,$co);
				break;
			}

	    	$pdf->Text(36,$y,$zeile[$j]);
	    	$y = $y + 4;
	    }
	    $y_pos = $y + 3;
  	}
    

    }

*/

    if($printdoc == true) {

	$pdf->SetDisplayMode("real","default");
	if($directoutput==true) {
	    $pdf->Output();
	}
	else {
	    $pdf->Output("$biz_temppath/m-".$mahnid.".pdf");
	    chmod ("$biz_temppath/m-".$mahnid.".pdf", 0777);
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
	$feldname=str_replace("#bankkonto#",$co[bankkonto],$feldname);	
	$feldname=str_replace("#bankinstitut#",$co[bankinstitut],$feldname);
	$feldname=str_replace("#bankblz#",$co[bankblz],$feldname);
	$feldname=str_replace("#bankiban#",$co[bankiban],$feldname);
	$feldname=str_replace("#bankbic#",$co[bankbic],$feldname);
	$feldname=str_replace("#umsatzsteuerid#",$co[umsatzsteuerid],$feldname);
	$feldname=str_replace("#steuerid#",$co[steuerid],$feldname);
	
	return $feldname;
}

?>
