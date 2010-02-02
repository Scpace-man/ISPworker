<?

function idn_domain($domain,$method="new") {
    
    if($method=="old") {   
    
	$exec = shell_exec("CHARSET='ISO-8859-15' /usr/local/bin/idn --idna-to-ascii '".$domain."' 2>&1");
	$output = explode("\n",$exec);

	foreach ($output as $line) {
    	    if ($line) $domain = $line;
	}
    }
    
    if($method=="new") {
	$domain = idn_to_ascii($domain, "ISO-8859-16");
    }
		
    return $domain;
}


function domain_whois($server,$domain,$idn=false) {
    $whois_request = &new Whois_Request($server);
    return $whois_request->doRequest($domain);
}

function domain_idnconvert($domain) 
{
    $IDN = new Net_IDNA();
    return $IDN->encode(utf8_encode($domain),'utf8');
}				


function http_idn_domain($domain,$serial) {
    $domain = urlencode($domain);

    $fp = fopen("http://entwicklung.ispworker.de/whois/whois.php?serial=$serial&idn=true&domain=$domain","r");
    if(!$fp) { echo "Error: connection to whois server failed."; }
    else {
	while (!feof($fp)) {
	    $contents .= fread($fp, 8192);
	}
    }
    fclose($fp);
    $domain = trim($contents);
    
    return $domain;
}



function http_domain_whois($q,$serial) {
    $q = urlencode($q);

    $fp = fopen("http://entwicklung.ispworker.de/whois/whois.php?serial=$serial&whois=true&q=$q","r");
    if(!$fp) { echo "Error: connection to whois server failed."; }
    else {
	while (!feof($fp)) {
	    $contents .= fread($fp, 8192);
	}
    }
    fclose($fp);
    $o = trim($contents);
    
    return $o;
}


function docid() {

    $vok = array("a","e","o","u");
    $kon = array("b","d","f","g","r","s","t","w","2","3","4","7","9","A","B","V","G","C","X");
	
    mt_srand((double)microtime()*1000000);
    
    for($chars=1;$chars <= 4; $chars++) {
		
        if ($chars % 2 == 0 or $chars == 1)
            $arr = $vok;
        else
            $arr = $kon;
        $new_pwd .= $arr[mt_rand(0,(count($arr)-1))];
        $new_pwd .= sprintf("%c",mt_rand(97,122));
        unset($arr);
    }
												
    return $new_pwd;
}


function makepwd() {

    $vok = array("a","e","o","u");
    $kon = array("b","d","f","g","r","s","t","w","2","3","4","7","9","A","B","V","G","C","X");
	
    mt_srand((double)microtime()*1000000);
    
    for($chars=1;$chars <= 4; $chars++) {
		
        if ($chars % 2 == 0 or $chars == 1)
            $arr = $vok;
        else
            $arr = $kon;
        $new_pwd .= $arr[mt_rand(0,(count($arr)-1))];
        $new_pwd .= sprintf("%c",mt_rand(97,122));
        unset($arr);
    }
												
    return $new_pwd;
}



function email_valid($string) 
{
    if (empty($string)) return false;
    $preg = "^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@([a-zA-Z0-9-]+\.)+([a-zA-Z]{2,4})$";
    preg_match("/$preg/", $string, $result);
    if ($string != $result[0]) return false;
    return true;
}
											

function pcode_valid($number)
{
    if ($number!="" && $number > 100 && $number <= 99998 ) return true;
    else return false;
}


function order_execute_jobs($activationflag="N",$produktid,$domainarray,$jobid="")
{
    global $db;
    
    $da = $domainarray;

    if($jobid != "") $add = "and jobid='".$jobid."' ";
    
    $resj = $db->query("select * from order_jobs where jobproductid='".$produktid."' and jobactivation='".$activationflag."' ".$add);

    while($job = $db->fetch_array($resj))
    {
	$jobpara = explode("\n",$job["jobparameter"]);

        // Einmalig für das Produkt
        if($job["jobexecutionnum"]=="1")
        {
    	    // Shell Kommando ausführen
	    if($job["jobmethod"]=="shell")
	    {
		$output = shell_exec($jobpara[0]);
		$mail_report_to = $jobpara[1];
	    }
	    // E-Mail verschicken
	    if($job["jobmethod"]=="email")
	    {
		$arr = explode("--message--",$job["jobparameter"]);
		$body = $arr[1];
		$jobpara = explode("\n",$arr[0]);
		$mail_report_to = $jobpara[1];	
		$output = mail($jobpara[0],$jobpara[3],$body,"From: ".$jobpara[2]." <".$jobpara[1].">");
	    }
	    // Report an Admin vorbereiten
	    $report = "Einmalige Ausführung von JOB >> ".$job["jobname"]." <<\nMethode: ".$job["jobmethod"]."\nRueckgabe:\n\n".$output;
	}
	// Für jede Domain
	else if($job["jobexecutionnum"]=="x")
	{
	
	    for($i = 0; $i < count($da); $i++)
	    {
		// Wenn Domain nicht leer
		if($da[$i]!="")
		{
		    // Shell Kommando ausführen
		    if($job["jobmethod"]=="shell")
		    {
			$jobpara[0] = str_replace("#domain#",$da[$i],$jobpara[0]);
			$output = shell_exec($jobpara[0]);
			$mail_report_to = $jobpara[1];
		    }
		    // E-Mail verschicken
		    if($job["jobmethod"]=="email")
		    {
			$arr = explode("--message--",$job["jobparameter"]);
			$body = $arr[1];
			$jobpara = explode("\n",$arr[0]);
			$jobpara[3] = str_replace("#domain#",$da[$i],$jobpara[3]);
			$body       = str_replace("#domain#",$da[$i],$body);
			$mail_report_to = $jobpara[1];

                        $output = mail($jobpara[0],$jobpara[3],$body,"From: ".$jobpara[2]." <".$jobpara[1].">");
		    }
	        // Report an Admin vorbereiten
	        $report .= "Ausfuehrung von JOB >> ".$job["jobname"]." << fuer Domain ".$da[$i]."\nMethode: ".$job["jobmethod"]."\nRueckgabe:\n\n".$output."\n\n";
		}
	    }
	}
	// Report per Mail verschicken
	mail($mail_report_to,"ISPWORKER JOB REPORT",$report, "From: ISPworker <".$order_settings[bemail].">");
	$report = "";
    }
}		
?>