<?
include("header.php");
?>
<h3>Bestellung erfolgreich</h3>
<hr size="1" noshade>
<br>
<?
if($_SESSION["end"]==true) 
{
    include("footer.php");
    die();
}

$resl = $db->query("select name, isocode from order_laender where isocode='$_SESSION[d_isocode]' ");

$land = $rowl['name'];

$resorder = $db->query("select * from order_settings");
$order_settings = $db->fetch_array($resorder);

$p_kundendaten = "\n";
if($order_settings['firma'] != "inaktiv")
{
    $p_kundendaten .= "Firma.....: ".$_SESSION['d_firma']."\n";
}
    $p_kundendaten .= "Anrede....: ".$_SESSION['d_anrede']."\n";
if($order_settings['titel'] != "inaktiv")
{
    $p_kundendaten .= "Titel.....: ".$_SESSION['d_titel']."\n";
}
    $p_kundendaten .= "Vorname...: ".$_SESSION['d_vorname']."\nNachname..: ".$_SESSION['d_nachname']."\n";
if($order_settings['geb'] != "inaktiv")
{
    $p_kundendaten .= "Geburtstag: ".$_SESSION['d_tag']." ".$_SESSION['d_monat']." ".$_SESSION['d_jahr']."\n";
}
    $p_kundendaten .= "Strasse...: ".$_SESSION['d_strasse']."\nPLZ/Ort...: ".$_SESSION['d_isocode']."-".$_SESSION['d_plz']." / ".$_SESSION['d_ort']."\nLand......: ".$_SESSION['d_land']."\nTelefon...: ".$_SESSION['d_telefon']."\n";
if($order_settings['mobil'] != "inaktiv")
{
    $p_kundendaten .= "Mobil.....: ".$_SESSION['d_mobil']."\n";
}
if($order_settings['fax'] != "inaktiv")
{
    $p_kundendaten .= "Fax.......: ".$_SESSION['d_fax']."\n";
}
    $p_kundendaten .= "E-Mail....: ".$_SESSION['d_mail']."\n";
if($order_settings['url'] != "inaktiv")
{
    $p_kundendaten .= "Website...: ".$_SESSION['d_url']."\n";
}
if($order_settings['pkey'] != "inaktiv")
{
    $p_kundendaten .= "Produktkey: ".$_SESSION['d_key']."\n";
}
if($order_settings['zusatz1status'] != "inaktiv")
{
    $p_kundendaten .= $order_settings['zusatz1']."...: ".$_SESSION['d_zusatz1']."\n";
}
if($order_settings['zusatz2status'] != "inaktiv")
{
    $p_kundendaten .= $order_settings['zusatz2']."...: ".$_SESSION['d_zusatz2']."\n";
}
if($order_settings['zusatz3status'] != "inaktiv")
{
    $p_kundendaten .= $order_settings['zusatz3']."...: ".$_SESSION['d_zusatz3']."\n";
}

$p_kundendaten .="
Widerrufsbelehrung zugestimmt..: $_SESSION[d_wdfok]
Sofort ausführen?..............: $_SESSION[d_wdfok2]";


$p_zahlungsart = $_SESSION['d_zahlungsart'];

$p_kontodaten = "
Kontoinhaber...: $_SESSION[d_kontoinhaber]
Kontonummer....: $_SESSION[d_kontonummer]
Bankleitzahl...: $_SESSION[d_bankleitzahl]
Geldinstitut...: $_SESSION[d_geldinstitut]
";


// ----------------------

$myres = $db->query("select * from biz_settings");
$biz_settings = $db->fetch_array($myres);

// Produkte aufbereiten
$produkte = explode(";",$_SESSION['paketid']);
for($n=0;$n<count($produkte);$n++)
{
    if($n==0) $inkldom = $produkte[$n];

    $res = $db->query("select bezeichnung,preis,abrechnung from biz_produkte where produktid='".$produkte[$n]."' ");
    $row = $db->fetch_array($res);

    $intervall = explode(":",$row['abrechnung']);
    if(count($intervall)>1)
    	$rechnungsintervall = "alle ".$intervall[1]." Monate";
    else
    	$rechnungsintervall = $row['abrechnung'];

    $resein = $db->query("select order_artikel.produkteinid, biz_produkte.preis from biz_produkte,order_artikel where order_artikel.produkteinid=biz_produkte.produktid and order_artikel.artikelid='".$produkte[$n]."' ");
    $rowein = $db->fetch_array($resein);

    if($_SESSION[calc]==true)
	$p_produkte .= stripslashes($row[bezeichnung])."\n$_SESSION[calc_components]\n($row[preis] $biz_settings[waehrung] $rechnungsintervall)\n";
    else
	$p_produkte .= stripslashes($row[bezeichnung])."\n($row[preis] $biz_settings[waehrung] $rechnungsintervall)\n";
    
    $b.= "1:".$produkte[$n].";";
    if($rowein[preis]!="") 
    {
	$p_produkte .= "Einrichtung ($rowein[preis] $biz_settings[waehrung] einmalig)";
	$b .= "1:".$rowein[produkteinid].";";
    }
}

// Domains aufbereiten
$res = $db->query("select anzdomains,tldsmitaufpreis from order_artikel where artikelid='".$inkldom."' ");
$row = $db->fetch_array($res);

$j = 0;
$da = explode(";",$_SESSION[domainstoorder]);
if(count($da)>0) 
{
    // Domains durchlaufen
    for($i=0;$i<count($da);$i++) 
    {
	// Wenn Domain nicht leer
	if($da[$i]!="") 
	{
    	    $j++;
	    $ta = explode(".",$da[$i]);

	    $tb = explode("{KK}",$ta[1]);
	    $tld = ".".$tb[0];

	    $fd = explode("{KK}",$da[$i]);
	    
	    // Domain an interne Strings anhängen
	    $fulldomain  .= "$fd[0]:";    
	    $p_produkte .= "$da[$i]";

	    // KK Formulare sollen erstellt werden
	    if($order_settings[bkkvertrag]=="Y") 
	    {
		if(strstr($da[$i],"{KK}")) 
		{
		    $kkform = $order_settings[formkk];
		    $ed     = explode("{KK}",$da[$i]);
		    $kkform = str_replace("#domain#","$ed[0]",$kkform);
		    $docid  = docid();
		    $time   = time();

		    $db->query("insert into order_docs (docid,doc,time) values ('$docid','$kkform','$time')");
		    $link = "KK Formular: ".CONF_BASEHREFBESTELLEN."showform.php?docid=$docid";
		    $hlinks .= "<a href=\"".CONF_BASEHREFBESTELLEN."showform.php?docid=$docid"."\" target=\"newwin\">KK Formular für $ed[0]</a><br>";
		}
	    }

	    // Betrachte Inklusiv Domains
	    if($j <= $row[anzdomains]) 
	    {
		$restld = $db->query("select tldid,aufpreis from order_tld where tld='$tld'");
		$rowtld = $db->fetch_array($restld);
	
		// Wenn Aufpreis für Domain definiert ist
		if(strstr($row[tldsmitaufpreis],"|$rowtld[tldid]|")) 
		{
		    $resp = $db->query("select produktid,preis,abrechnung from biz_produkte where produktid='$rowtld[aufpreis]'");
		    $rowp = $db->fetch_array($resp);
    
		    $pintervall = explode(":",$rowp['abrechnung']);
		
		    if(count($pintervall)>1) 
			$prechnungsintervall = "alle ".$pintervall[1]." Monate";
		    else
			$prechnungsintervall = $rowp['abrechnung'];
		
		    $p_produkte .= " ($rowp[preis] $biz_settings[waehrung] Aufpreis $prechnungsintervall) $link";
		    $b .= "1:$rowp[produktid]:$da[$i];";
		}
		// Kein Aufpreis definiert
		else 
		{
		    // Domainliste für Produkt Nr. 1, später wird sie an den Kommentar der Rechnungsposition angehängt
		    $fulldomain2 .= "$fd[0]\n";
		    $p_produkte .= " (inklusive) $link";
		}
	    }
	    // Ab hier betrachte Domains, die nicht im Paket enthalten sind
	    else 
	    {
		$rest = $db->query("select biz_produkte.produktid, biz_produkte.preis, biz_produkte.abrechnung from biz_produkte,order_tld
				    where biz_produkte.produktid=order_tld.preis and order_tld.tld = '$tld'");
		$rowt = $db->fetch_array($rest);
		
		$tintervall = explode(":",$rowt['abrechnung']);
		if(count($tintervall)>1)
		    $trechnungsintervall = "alle ".$tintervall[1]." Monate";
		else
		    $trechnungsintervall = $rowt['abrechnung'];
		
		$p_produkte .= " ($rowt[preis] $biz_settings[waehrung] $trechnungsintervall)";
		$b .= "1:$rowt[produktid]:$da[$i];";
	    }
	    
	    $p_produkte .= "\n";
	    $link = "";
	}
    }
}


// Wir hängen die Domainliste an Produkt Nr. 1 ran.
$temp = explode(";",$b);
$temp[0] = $temp[0].":".$fulldomain2;
$b = implode(";",$temp);


// Wir bereiten die Formulare vor
if($order_settings[bvertrag]=="Y") 
{
    if	    ($p_zahlungsart=="rechnung")	$bestellform = $order_settings['formrechnung'];
    else if ($p_zahlungsart=="lastschrift")	$bestellform = $order_settings['formlastschrift'];
    else				    	$bestellform = $order_settings['formsonstzahl'];

    // Generiere eindeutige DOCUMENT ID
    $docid = docid();
    $time  = time();

    $bestellform = str_replace("#kundendaten#",nl2br("$p_kundendaten"),$bestellform);
    $bestellform = str_replace("#produkte#",nl2br("$p_produkte"),$bestellform);
    $bestellform = str_replace("#zahlungsart#",nl2br(UCfirst("$p_zahlungsart")),$bestellform);
    $bestellform = str_replace("#kontodaten#",nl2br("$p_kontodaten"),$bestellform);

    $db->query("insert into order_docs (docid,doc,time) values ('$docid','<pre>".mysql_escape_string($bestellform)."</pre>','$time')");

    // Link für E-Mail und HTML Ausgabe
    $bestelllink   = "Bestell-Formular: ".CONF_BASEHREFBESTELLEN."showform.php?docid=$docid";
    $hbestelllink  = "<a href=\"".CONF_BASEHREFBESTELLEN."showform.php?docid=$docid"."\" target=\"newwin\">Bestell-Formular</a>";
}

//Bestandskunden abfangen
$getMwstSQL = $db->query("select mwstnational from biz_settings");
$getMwst    = $db->fetch_array($getMwstSQL);

$kundenSQL = $db->query("select kundenid, nachname, vorname, mail from biz_kunden 
		         where nachname='".$_SESSION[d_nachname]."' AND vorname='".$_SESSION[d_vorname]."' AND mail='".$_SESSION[d_mail]."' ");
$kundenrow = $db->fetch_array($kundenSQL);
$kid  	   = $kundenrow["kundenid"];

if($db->num_rows($kundenSQL) == 0)
{
    $pwd = makepwd();
    $time = time();

    $db->query("insert into biz_kunden (firma,anrede,titel,vorname,nachname,geb_tag,geb_monat,geb_jahr,strasse,ort,isocode,plz,telefon,handy,fax,mail,url,bezahlart,kontoinhaber,
	        kontonummer,bankleitzahl,geldinstitut,regdatum,passwort,mwst)
	        values('$_SESSION[d_firma]','$_SESSION[d_anrede]','$_SESSION[d_titel]','$_SESSION[d_vorname]','$_SESSION[d_nachname]','$_SESSION[d_tag]','$_SESSION[d_monat]','$_SESSION[d_jahr]','$_SESSION[d_strasse]','$_SESSION[d_ort]',
	        '$_SESSION[d_isocode]','$_SESSION[d_plz]','$_SESSION[d_telefon]','$_SESSION[d_mobil]','$_SESSION[d_fax]','$_SESSION[d_mail]','$_SESSION[d_url]','$_SESSION[d_zahlungsart]',
	        '$_SESSION[d_kontoinhaber]','$_SESSION[d_kontonummer]','$_SESSION[d_bankleitzahl]','$_SESSION[d_geldinstitut]','$time','".sha1($pwd)."','$getMwst[mwstnational]')
	        ");
    $kid = $db->insert_id();

    $resp = $db->query("select mail, kundenmenue from biz_profile where profilid='1'");
    $rowp = $db->fetch_array($resp);

    $rest = $db->query("select * from biz_mailtemplates where templatename='std_zugangsdaten'");
    $rowt = $db->fetch_array($rest);

    $mailbetreff = $rowt[mailbetreff];
    $mailtext    = $rowt[mailtext];

    $mailbetreff = str_replace("#benutzername#", "$kid", $mailbetreff);
    $mailbetreff = str_replace("#passwort#", "$pwd", $mailbetreff);

    $mailtext = str_replace("#benutzername#", "$kid", $mailtext);
    $mailtext = str_replace("#passwort#", "$pwd", $mailtext);
    $mailtext = str_replace("#profilkundenmenue#", "$rowp[kundenmenue]", $mailtext);

    if (!mail($_SESSION[d_mail],$mailbetreff,$mailtext,"From: ".$rowp["mail"])) echo "Fehler beim Versenden der Zugangsdaten";

} else $kid = $kundenrow[kundenid];

$datum = date("Y-m-d H:i:s");

// StatusID aus Tabelle order_statusbestell holen
$myres  = $db->query("select statusid from order_statusbestell where status ='OFFEN' or status='UNERLEDIGT'");
$status = $db->fetch_array($myres);

// Bestellung speichern
$db->query("insert into biz_bestellungen (datum,kundenid,produkte,statusid,domains) values ('$datum','$kid','$b','$status[statusid]','$fulldomain')");
$bid = $db->insert_id();

// E-Mails vorbereiten

$restpl   = $db->query("select * from biz_mailtemplates where templatename='std_bestellbestaetigung'");
$rowtpl   = $db->fetch_array($restpl);
$mailtext = $rowtpl[mailtext];

$restpl    = $db->query("select * from biz_mailtemplates where templatename='std_bestellbenachrichtigung'");
$rowtpl    = $db->fetch_array($restpl);
$mailtext2 = $rowtpl[mailtext];

$d = strtotime($datum);

$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
$verbindungsdaten  = "Verfolgungsdaten:\n";
$verbindungsdaten .= "Hostname......: $hostname\n";
$verbindungsdaten .= "IP Adresse....: ".$_SERVER[REMOTE_ADDR]."\n";
$verbindungsdaten .= "Computerdaten.: ".$_SERVER[HTTP_USER_AGENT]."\n";

$mailtext = str_replace("#kundendaten#",$p_kundendaten,$mailtext);
$mailtext = str_replace("#kontodaten#",$p_kontodaten,$mailtext);
$mailtext = str_replace("#zahlungsart#",$p_zahlungsart,$mailtext);
$mailtext = str_replace("#produkte#",$p_produkte,$mailtext);
$mailtext = str_replace("#bestelllink#",$bestelllink,$mailtext);
$mailtext = str_replace("#signatur#",$order_settings[btext],$mailtext);
$mailtext = str_replace("#vorname#",$_SESSION[d_vorname],$mailtext);
$mailtext = str_replace("#nachname#",$_SESSION[d_nachname],$mailtext);
$mailtext = str_replace("#anrede#",$_SESSION[d_anrede],$mailtext);
$mailtext = str_replace("#bestaetigungslink#",CONF_BASEHREFBESTELLEN."orderack.php?id=$bid-$d",$mailtext);

$mailtext2 = str_replace("#kundendaten#",$p_kundendaten,$mailtext2);
$mailtext2 = str_replace("#kontodaten#",$p_kontodaten,$mailtext2);
$mailtext2 = str_replace("#zahlungsart#",$p_zahlungsart,$mailtext2);
$mailtext2 = str_replace("#produkte#",$p_produkte,$mailtext2);
$mailtext2 = str_replace("#bestelllink#",$bestelllink,$mailtext2);
$mailtext2 = str_replace("#signatur#",$order_settings[btext],$mailtext2);
$mailtext2 = str_replace("#vorname#",$_SESSION[d_vorname],$mailtext2);
$mailtext2 = str_replace("#nachname#",$_SESSION[d_nachname],$mailtext2);
$mailtext2 = str_replace("#anrede#",$_SESSION[d_anrede],$mailtext2);
$mailtext2 = str_replace("#jobs#",$jobs,$mailtext2);
$mailtext2 = str_replace("#verbindungsdaten#",$verbindungsdaten,$mailtext2);

$myres = $db->query("select mail from biz_profile where profilid='1'");
$biz_profil = $db->fetch_array($myres);

// E-Mails versenden

if($order_settings[bsendmail]=="Y") 
{
    // Mail an Kunde
    mail($_SESSION[d_mail],$order_settings[bbetreff],$mailtext, "From: ".$order_settings[babsender]." <".$order_settings[babsendermail].">");
    echo "Eine Best&auml;tigungsmail wurde an Ihre Mailadresse $_SESSION[d_mail] verschickt.";
}

// Mail an Provider
mail($order_settings["bemail"],"Neue Bestellung $bid",$mailtext2, "From: ISPworker <".$order_settings[bemail].">");
?>
<br>
<br>
<?=$order_settings[bthank]?><br>
<br>
Ihre Bestellnummer lautet <b><?=$bid?></b>, Ihre Kundennummer ist <b><?=$kid?></b>.<br>
<br>
<br>
<?
if($order_settings[bvertrag]=="Y") 
{
    echo $hlinks;
    echo "<br>";
    echo $hbestelllink;
}

if($_SESSION[calc]==true) $db->query("delete from order_artikel where artikelid='".$produkte[0]."' and kurztext='time: $_SESSION[calc_id]'");

// Jobs abarbeiten
order_execute_jobs("N",$produkte[0],$da);
 
if($_SESSION["d_zahlungsart"]=="paypal") include("order_paypal.inc.php");



// Wiederaufruf der Seite verhindern.
$_SESSION["end"] = true;
?>
<br>
<br>
<br>

<?include("footer.php");?>
