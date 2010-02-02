<?

function denyhtmlmail($to,$from,$subject)
{

    $nachricht = '
    Sehr geehrte Kundin, sehr geehrter Kunde,
    
    Ihre Anfrage mit dem Betreff * '.$subject.' * 
    wurde vom System automatisch abgelehnt, weil Sie uns eine HTML-eMail geschickt haben.
        
    Wir möchten Sie bitten, uns Ihre Anfrage erneut im reinen Text Format zu mailen.
    Die Umstellung auf Text-eMails können Sie in den Optionen Ihres eMail Programmes vornehmen.
    
    Vielen Dank.
    
    ';
    mail($to,"Ihre HTML-eMail wurde abgelehnt.",$nachricht,"From: $from");

}



function decode_string($string)
{
    return utf8_decode(imap_utf8($string));
}


function buildparts ($struct, $pno = "") {
    
    switch ($struct->type):
	case 1:
	    $r = array (); $i = 1;
	    foreach ($struct->parts as $part)
	    $r[] = buildparts ($part, $pno.".".$i++);
		   
	    return implode ("|", $r);
	case 2:
	    return "{".buildparts ($struct->parts[0], $pno)."}";
        default:
	    $p = substr ($pno, 1);

	    if($p!="" and $p>1) {
		return $p;
	    }
	endswitch;
    
}


function saveattachment($box,$i,$part,$ticketid) {

    global $ticket_temppath;
    global $db;
    global $attach;

    $structure = imap_fetchstructure($box, $i);
    
    $message = imap_fetchbody($box,$i,$part);   
    
    $part = $part -1;
    
    $name = $structure->parts[$part]->dparameters[0]->value;
    $bytes = $structure->parts[$part]->bytes;
    $type = $structure->parts[$part]->type;
    
    
    if ($type == 0)
    {
	$type = "text/";
    }
    elseif ($type == 1)
    {
	$type = "multipart/";
    }
    elseif ($type == 2)
    {
	$type = "message/";
    }
    elseif ($type == 3)
    {
	$type = "application/";
    }
    elseif ($type == 4)
    {
	$type = "audio/";
    }
    elseif ($type == 5)
    {
	$type = "image/";
    }
    elseif ($type == 6)
    {
	$type = "video";
    }
    elseif($type == 7)
    {
	$type = "other/";
    }
    
    $type .= $structure->parts[$part]->subtype; 

    $attach .=  "$type $bytes $ticketid"."_"."$name\n";

    $coding = $structure->parts[$part]->encoding;

    if ($coding == 0)
    {
	$message = imap_utf7_decode($message);
    } 
    elseif ($coding == 1)
    {
	$message = imap_8bit($message);
    }
    elseif ($coding == 2)
    {
	$message = imap_binary($message);
    }
    elseif ($coding == 3)
    {
	$message = imap_base64($message);
    } 
    elseif ($coding == 4)
    {
	$message = quoted_printable_decode($message);
    } 
    elseif ($coding == 5)
    {
	$message = $message;
    } 

    $fp = fopen($ticket_temppath."/$ticketid"."_"."$name","w");
    fwrite($fp,$message);
    fclose($fp);

    chmod($ticket_temppath."/$ticketid"."_"."$name", 0777);
    
    $db->query("update ticket_anfragen set attachments='$attach' where ticketid='$ticketid'");
    
}


function zeitumrechnen($zeit_sec) {
    $sec = $zeit_sec % 60;
    $zeit_sec = ($zeit_sec - $sec) / 60;
    $minute = $zeit_sec % 60;
    $zeit_sec = ($zeit_sec - $minute) / 60;
    $hour = $zeit_sec % 24;
    $zeit_sec = ($zeit_sec - $hour) / 24;
    $day = $zeit_sec % 7;
    $week = ($zeit_sec - $day) / 7;
	
    if($week!=0)   { $week   = "$week w";   } else { $week   = ""; }
    if($day!=0)    { $day    = "$day d";    } else { $day    = ""; }
    if($hour!=0)   { $hour   = "$hour h";   } else { $hour   = ""; }
    if($minute!=0) { $minute = "$minute m"; } else { $minute = ""; }

    $sec = "$sec s";
    return "$week $day $hour $minute $sec";
}
														


?>
