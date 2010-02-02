<?session_start();
$noauth = true;

include("../../include/config.inc.php");
include("../../include/common.inc.php");
include("./inc/config.inc.php");
include("./inc/functions.inc.php");
include("./inc/pdfmahn.inc.php");


$profilid = "1";

$mahndatum = date("Y-m-d");
$nowdatum  = time();

//echo $biz_settings[mahnautosend];

if($biz_settings[mahnautosend]==0) die("mahnautosend == 0, aborting.");

for($n = 1; $n < 4; $n++) {
    
    $tpl = "mahntpl".$n;
    $datumspanne = 86400 * $biz_settings[$tpl."sa"];

    $restpl = $db->query("select * from biz_mahntemplates where templateid='$biz_settings[$tpl]'");
    $rowtpl = $db->fetch_array($restpl);
    
    if($n == 1) { $p = "status = 'unbezahlt'"; $ns = "gemahnt";  $mgebuehr=$rowtpl[mgebuehr]; $rgebuehr=$rowtpl[rgebuehr]; }
    if($n == 2) { $p = "status = 'gemahnt'";   $ns = "gemahnt2"; $mgebuehr=$rowtpl[mgebuehr]; $rgebuehr=$rowtpl[rgebuehr]; }
    if($n == 3) { $p = "status = 'gemahnt2'";  $ns = "gemahnt3"; $mgebuehr=$rowtpl[mgebuehr]; $rgebuehr=$rowtpl[rgebuehr]; }

    
    $arr1 = array();
    $arr2 = array();
    $me = @file_get_contents($biz_temppath."/mahnexclude.txt");
    if($me!="") {
	$x = explode("\n",$me);
	$arr1 = unserialize($x[0]);
	$arr2 = unserialize($x[1]);
    }
    
    $resr = $db->query("select * from biz_rechnungen where $p");
    while($rowr = $db->fetch_array($resr)) {

	$rechdatum = strtotime($rowr[datum]);
	
	if( (($nowdatum - $rechdatum) > $datumspanne) and (!in_array($rowr[kundenid],$arr1) and !in_array($rowr[rechnungid],$arr2))) {

	
	    $db->query("update biz_rechnungen set mahndatum='$mahndatum', status='$ns' where rechnungid='$rowr[rechnungid]'");
    	    $db->query("insert into biz_mahnungen (profilid,templateid,mahngebuehr,ruecklastgebuehr,positionen,kundenid,datum) values ('$profilid','$biz_settings[$tpl]','$mgebuehr','$rgebuehr','$rowr[rechnungid]','$rowr[kundenid]','$mahndatum')");
	    $mahnid = $db->insert_id();
    
   	    pdfmahnung($mahnid);


	    $file_url = "$biz_temppath/m-".$mahnid.".pdf";
	    $fp = fopen($file_url,"r");
	    $str = fread($fp, filesize($file_url));
	    $str = chunk_split(base64_encode($str));
	    fclose($fp);

	    $resp = $db->query("select * from biz_profile where profilid='$profilid'");
	    $rowprofil = $db->fetch_array($resp);
    
	    $resk = $db->query("select * from biz_kunden where kundenid='$rowr[kundenid]'");
	    $rowk = $db->fetch_array($resk);

	    if($rowk[anrede]=="Herr") { $anrede = "Sehr geehrter Herr $rowk[nachname]"; }
	    elseif($rowk[anrede]=="Frau") { $anrede = "Sehr geehrte Frau $rowk[nachname]"; }
	    else { $anrede = "Sehr geehrte Damen und Herren"; }

	    $headers = "From: $rowprofil[mail]\n";
    	    $headers .= "MIME-Version: 1.0\n";
	    $headers .= "Content-Type: multipart/mixed; boundary=\"MIME_BOUNDRY\"\n";
    	    $headers .= "X-Mailer: PHP4\n";
    	    $headers .= "This is a multi-part message in MIME format.\n";

    	    $message = "--MIME_BOUNDRY\n";
    	    $message .= "Content-Type: text/plain; charset=\"iso-8859-1\"\n";
    	    $message .= "Content-Transfer-Encoding: quoted-printable\n";
    	    $message .= "\n";

    	    $message .= "$anrede,\n\nanbei ein wichtiges Schreiben im PDF Format.\n";
    	    $message .= "\n\n";
    	    $message .= "Mit freundlichen Grüßen\n\n";
    	    $message .= "$rowprofil[firma]\n$rowprofil[strasse] - $rowprofil[plz] $rowprofil[ort]\n\nMail: $rowprofil[mail]\n";
    	    $message .= "\n";
    
    	    $message .= "\n";
    	    $message .= "--MIME_BOUNDRY\n";
    	    $message .= "Content-Type: application/pdf; name=\"m-$mahnid.pdf\"\n";
    	    $message .= "Content-disposition: attachment\n";
	    $message .= "Content-Transfer-Encoding: base64\n";
    	    $message .= "\n";
    	    $message .= "$str\n";
    	    $message .= "\n";    
    	    $message .= "--MIME_BOUNDRY--\n";

    	    $mailbetreff = $rowtpl[templatename];
    
		    mail($rowk["mail"], $mailbetreff, $message,$headers);
    	    mail($biz_settings["pdfkopie"], "Mahnung Nr $mahnid / Kundennummer $rowr[kundenid]", $message,$headers);

            unlink($file_url);
	
	    echo "Mahnschreiben $mahnid ist erstellt und verschickt.<br>";
	}
    }	

}	
    
?>


<?include("../../footer.php");?>