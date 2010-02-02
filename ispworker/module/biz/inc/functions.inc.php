<?

function anrede($an,$row) {

    if($an=="Herr") {
	$anrede = LA_sehrgeehrterherr." $row[nachname],";
    }
	
    elseif($an=="Frau") {
	$anrede = LA_sehrgeehrtefrau." $row[nachname],";
    }

    else {
	$anrede = LA_sehrgeehrtekundinkunde;
    }
    return $anrede;
}


function make_password($anz=8)
{
    $array = array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z",
    "A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","1","2","3",
    "4","5","6","7","8","9","0");
    srand ((double)microtime()*1000000);
    for($x = 0; $x < $anz; $x++)
    {
	$i = rand(1, count($array))-1;
	$erg[] = $array[$i];
	array_splice($array, $i, 1);
	$s .= "$erg[$x]";
    }
    return $s;
}


/*
//Nun in der ispworker/include/functions.php zu finden
function make_password2()
*/


function open_post($host, $path, $data_to_send) {
    $fp = fsockopen($host, 80);
    
    fputs($fp, "POST $path HTTP/1.0\n");
    fputs($fp, "Host: $host\n");
    fputs($fp, "Referer: www.google.de\n");
    fputs($fp, "Content-type: application/x-www-form-urlencoded\n");
    fputs($fp, "Content-length: ". strlen($data_to_send) ."\n");
    fputs($fp, "Connection: close\n\n");
    fputs($fp, "$data_to_send\n");
		
    return $fp;
}
				    
function close_post($fp) {
    fclose($fp);
}



function confixx3_getsid($url, $username, $password) {

    $x = explode("/",$url);
    $host = $x[2];
	
    $data_to_send = "username=$username&password=$password";
	    
    $fp = open_post($host,"/login.php",$data_to_send);
		
    while(!feof($fp)) {
	$res = fgets($fp, 128);
	if(strstr($res,"SID=")) {
	    $x = explode("SID=",$res);
	    $y = explode(";",$x[1]);
	    $mysid = $y[0];
	}
    }
											    
    close_post($fp);
    $x = explode("\"",$mysid);
    $mysid = $x[0];
    									
    return trim($mysid);
}



function confixx3_anbieterneu($url, $path, $data_to_send) {

    $x = explode("/",$url);
    $host = $x[2];
	
    $fp = open_post($host,"$path",$data_to_send);
    $res = "";
    while(!feof($fp)) {
        $res .= fgets($fp, 1024);
    }
	
    close_post($fp);

    return $res;
}



function confixx3_kundenausgeben($url,$path,$data_to_send) {
    $x = explode("/",$url);
    $host = $x[2];
	
    $fp = open_post($host,"$path",$data_to_send);
    
    while(!feof($fp)) {
        $res[] = fgets($fp, 8096);
    }
	
    close_post($fp);
    
    return $res;
}




function confixx3_anbieterloeschen($url, $path, $data_to_send) {

    $x = explode("/",$url);
    $host = $x[2];

    $fp = open_post($host,"$path",$data_to_send);
    $res = "";
    while(!feof($fp)) {
        $res .= fgets($fp, 1024);
    }
	
    close_post($fp);
    
    return $res;
}
					
					
					

function calc_abrechnungszeitraum($begintimestamp,$abrechnung) {

    if(strstr($abrechnung,"indiv:")) {
	$e = explode("indiv:",$abrechnung);
	$m = $e[1];
	
	$return[0] = strtotime("+".$m." month -1 day",$begintimestamp);
        $return[1] = "Abrechnungszeitraum: ".date("d.m.Y",$begintimestamp)." - ".date("d.m.Y",$return[0]);	
    }
    else {	
	switch ($abrechnung) {
	    case "einmalig":
    		$return[0] = 0;
    		$return[1] = "";
	    break;

	    case "monatlich":
    		$return[0] = strtotime("+1 month -1 day",$begintimestamp);
    		$return[1] = "Abrechnungszeitraum: ".date("d.m.Y",$begintimestamp)." - ".date("d.m.Y",$return[0]);
    	    break;

	    case "vierteljaehrlich":
    		$return[0] = strtotime("+3 month -1 day",$begintimestamp);
    	        $return[1] = "Abrechnungszeitraum: ".date("d.m.Y",$begintimestamp)." - ".date("d.m.Y",$return[0]);
	    break;

	    case "halbjaehrlich":
    		$return[0] = strtotime("+6 month -1 day",$begintimestamp);
    		$return[1] = "Abrechnungszeitraum: ".date("d.m.Y",$begintimestamp)." - ".date("d.m.Y",$return[0]);
	    break;

	    case "jaehrlich":
    		$return[0] = strtotime("+12 month -1 day",$begintimestamp);
    		$return[1] = "Abrechnungszeitraum: ".date("d.m.Y",$begintimestamp)." - ".date("d.m.Y",$return[0]);
	    break;
	}
    }
    return $return;
}

function AktuellerWechselkurs($exch,$expr){

	$ret=http_post("www.oanda.com",	80,	"/convert/classic", array("exch" => "$exch", "expr" => "$expr", "value" => "1"));

	$start = strpos($ret, "<!-- conversion result starts  -->");
	$end = strpos ($ret, "<!-- conversion result ends  -->");

	$length=$end-$start;
	
	$currency= substr($ret, $start, $length); 
	$cvalue=explode("=",strip_tags($currency));
	$currency=trim($cvalue[1]);

	$currency1=explode(" ",$currency);
	
	return $currency1[0];
}

function http_post($server, $port, $url, $vars) {
/*
example:
http_post(
	"www.fat.com",
	80, 
	"/weightloss.pl", 
	array("name" => "obese bob", "age" => "20")
	);
*/

	$user_agent = "Mozilla/4.0 (compatible; MSIE 5.5; Windows 98)";

	$urlencoded = "";
	while (list($key,$value) = each($vars))
		$urlencoded.= urlencode($key) . "=" . urlencode($value) . "&";
	$urlencoded = substr($urlencoded,0,-1);	

	$content_length = strlen($urlencoded);

	$headers = "POST $url HTTP/1.1
Accept: */*
Accept-Language: en-au
Content-Type: application/x-www-form-urlencoded
User-Agent: $user_agent
Host: $server
Connection: Keep-Alive
Cache-Control: no-cache
Content-Length: $content_length

";
	
	$fp = fsockopen($server, $port, $errno, $errstr);
	if (!$fp) {
		return false;
	}

	fputs($fp, $headers);
	fputs($fp, $urlencoded);
	
	$ret = "";
	while (!feof($fp))
		$ret.= fgets($fp, 1024);
        
	fclose($fp);
	
	return $ret;

}


function send_invoice($rechnungid, $kundenid, $template="std_neuerechnung") 
{
    global $db;
    global $biz_temppath;
    global $biz_settings;

    // Getting mail template
  
    $restpl = $db->query("select * from biz_mailtemplates where templatename='$template'");
    $rowtpl = $db->fetch_array($restpl);
	  
    $biz_mailbetreff = $rowtpl[mailbetreff];
    $biz_mailtext    = $rowtpl[mailtext];
		  
    // Get profile id

    $res = $db->query("select profilid from biz_rechnungen where rechnungid='$rechnungid'");
    $row = $db->fetch_array($res);
    
    $profilid = $row["profilid"];

    // Getting client data

    $res = $db->query("select * from biz_kunden where kundenid='$kundenid'");
    $row = $db->fetch_array($res);

    $anrede = anrede($row[anrede],$row);			      

    $resprofil = $db->query("select * from biz_profile where profilid='$profilid'");
    $rowprofil = $db->fetch_array($resprofil);
       
    $file_url = "$biz_temppath/r-".$rechnungid.".pdf";
    $fp = fopen($file_url,"r");
    $str = fread($fp, filesize($file_url));
    $str = chunk_split(base64_encode($str));
    
    // Mail format
     
    $headers  = "From: $rowprofil[mail]\n";
    $headers .= "MIME-Version: 1.0\n";
    $headers .= "Content-Type: multipart/mixed; boundary=\"MIME_BOUNDRY\"\n";
    $headers .= "X-Mailer: PHP\n";
    $headers .= "This is a multi-part message in MIME format.\n";
	 
    $message = "--MIME_BOUNDRY\n";
    $message .= "Content-Type: text/plain; charset=\"iso-8859-15\"\n";
    $message .= "Content-Transfer-Encoding: text/plain\n";
    $message .= "\n";
    
    // String replacements
    
    $mailtext    = str_replace("#anrede#",$anrede,$biz_mailtext);
    $mailtext    = str_replace("#profilkundenmenue#",$rowprofil[kundenmenue],$mailtext);
    $mailbetreff = str_replace("#rechnungid#",$rechnungid,$biz_mailbetreff);
    $mailbetreff = str_replace("#kundenid#",$kundenid,$mailbetreff);
    $mailtext    = str_replace("#rechnungid#",$rechnungid,$mailtext);
    $mailtext    = str_replace("#kundenid#",$kundenid,$mailtext);

    // Mail body
 
    $message .= $mailtext;
    $message .= "\n";
    $message .= "--MIME_BOUNDRY\n";
    $message .= "Content-Type: application/pdf; name=\"$rechnungid.pdf\"\n";
    $message .= "Content-disposition: attachment\n";
    $message .= "Content-Transfer-Encoding: base64\n";
    $message .= "\n";
    $message .= "$str\n";
    $message .= "\n";
    $message .= "--MIME_BOUNDRY--\n";

    if($row["mail"]!="" and $row[sendmail]=="Y") mail("$row[mail]", "$mailbetreff", $message,$headers);
    if($biz_settings["pdfkopie"]!="") mail($biz_settings["pdfkopie"], "$mailbetreff", $message,$headers);
}

?>
