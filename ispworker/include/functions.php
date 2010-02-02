<?php


function trash($table,$cond) {
    global $db;
    
    $res = $db->query("SHOW COLUMNS FROM $table");
    if ($db->num_rows($res) > 0) {
		while ($row = mysql_fetch_assoc($res)) {
		    $fields .= "$row[Field],";
		}
    }

    $fields = rtrim($fields,',');

    $res = $db->query("select * from $table $cond");    
    $row = $db->fetch_array($res);
    
    $values = "$row[0]";
    
    for($i = 1; $i < $db->num_fields($res); $i++)
    {
	$values .= "::$row[$i]";
    }

    $entrydate = time();
    $db->query("insert into system_trash (entrydate,entrytable,entryfields,entryvalues) values ('$entrydate','$table','$fields','$values')");
    $db->query("delete from $table $cond");

}





function message($message,$type="success")
{
    if($type=="success") $color = "green";
    if($type=="error")   $color = "red";
    
    echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"95%\">";
    echo "<tr><td bgcolor=\"#404040\"><img src=\"img/pixel.gif\" width=\"1\" height=\"1\"></td></tr>";    
    echo "<tr><td bgcolor=\"#f7f7f7\" valign=\"middle\" height=\"30\"><img src=\"img/pixel.gif\" width=\"5\" height=\"1\"><font color=\"$color\"><b>$message</b></font></td></tr>";
    echo "<tr><td bgcolor=\"#404040\"><img src=\"img/pixel.gif\" width=\"1\" height=\"1\"></td></tr>";    
    echo "</table><br><br>";

}




	// gibt TRUE beim Netscape-Naviagator zurŸck
	function netscape_navigator() {
	
		$agent = strtolower($_SERVER['HTTP_USER_AGENT']);
		
		if(!strstr($agent, "mozilla") or strstr($agent, "compatible") or strstr($agent, "opera") or strstr($agent, "khtml")) return FALSE;
		return TRUE;
		
	}


	// #################### //
	//      PrüŸfungen       //
	// #################### //

	// einfache PrüŸfung auf LäŠnge
	function fn_input_valid($string, $laenge) {
	
		if (strlen($string) < $laenge) return FALSE;
		return true;
		
	}
	
	// Vergleich einer Eingabe mit einer Werteliste
	function fn_liste_valid ($string, $liste) {
	
		if (empty($string)) return false;
		return in_array($string, $liste);
		
	}
	
	// PrŸfung der GŸltigkeit einer eMail-Adresse
	function fn_email_valid($string) {	
	
		if (empty($string)) return FALSE;		

		$preg = "^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@([a-zA-Z0-9-]+\.)+([a-zA-Z]{2,4})$";
		preg_match("/$preg/", $string, $result);
		
		if ($string != $result[0]) return false;		
		return true;
		
	}
	
	// PrŸfung der GŸltigkeit einer URL
	function fn_url_valid($string) {	
	
		if (empty($string)) return FALSE;		

		$preg = "^(http|https)\:\/\/([a-zA-Z0-9-@~]+\.)+([a-zA-Z]{2,4})$";
		preg_match("/$preg/", $string, $result);
		
		if ($string != $result[0]) return false;		
		return true;
		
	}
	
	// †berprŸfung eines engegebenen Datums im deutschen Format 24.12.1912
	// die Begrenzung auf Daten zwischen 1900 und 2099 ist willkŸrlich
	function fn_date_valid($date) {
		$date = explode(".", $date);
		 if($date[2] < 1900 || $date[2] > 2099) {
            return FALSE;
        }
        if(!checkdate($date[1],$date[0],$date[2])) {
            return FALSE;
        }
        return TRUE;
	}
	
	// ermittelt, ob ein My-SQL-Datum abgelaufen ist
	function fn_expired($MySQL_Date) {
	
		$date = fn_make_Timestamp($MySQL_Date);
		$timestamp = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
		
		if ($timestamp > $date) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	
	// #################### //
	//     Formatierung     //
	// #################### //

	function fn_make_HTML($string, $parse="") {
	
		$string = trim(nl2br(htmlentities($string)));
		if ($parse) {
			$string = str_replace("[/LINK]", "</a>", $string);
			$string = str_replace('[LINK:', '<a href="', $string);	
			$string = str_replace(']', '">', $string);



		}
		
		return $string;
		
	}
	
	// KŸrzt lange Text auf angegebene Anzahl Stellen -2 und hŠngt ... an
	function fn_make_short($string, $length) {
		
		if (strlen($string) > $length AND $length > 1) {
			$string = substr($string, 0, ($length - 2)) . "...";
		}
		$result = fn_make_HTML($string, FALSE);
		return $result;
		
	}
	
	function fn_make_Date($MySQL_Date, $lang="") {
	
		if ($lang == "de" or empty($lang)) {
			return implode(".", array_reverse(explode("-", $MySQL_Date)));
		} elseif ($lang == "en") {
			$date = explode("-", $MySQL_Date);
			$day = (int) $date[2];
			$month = (int) $date[1];
			$year = $date[0];
			$arrMonths = array("", "Jan.", "Feb.", "Mar.", "Apr.", "May", "Jun.", "Jul.", "Aug.", "Sep.", "Okt.", "Nov.", "Dec.");
			$arrDays = array("", "st", "nd", "rd");
			isset($arrDays[$day]) ? $day_ext = $arrDays[$day] : $day_ext = "th";
			
			return ($arrMonths[$month] . " " . $day . $day_ext . ", " . $year);
			
		}
	}
	
	function fn_make_Date_mysql($Date) {
	
		return implode("-", array_reverse(explode(".", $Date)));
			
	}
	
	function fn_make_Time($MySQL_Time, $lang="") {
	
		$time = explode(":", $MySQL_Time);
		$timestamp = mktime($time[0], $time[1] ,$time[2], 1, 1, 1970);
		
		if (empty($lang)) {
			return strftime("%H:%M", $timestamp);
		} elseif ($lang == "de") {
			return strftime("%H:%M Uhr", $timestamp);
		} elseif ($lang == "en") {
			return strftime("%I:%M %p", $timestamp);
		}
	}
	
	function fn_make_Time_mysql($Time) {
	
		$time = explode(":", $Time);
		return $time[0].":".$time[1].":".$time[2];
			
	}
	
	function fn_make_Timestamp($MySQL_Date) {
	
		$date = explode("-", $MySQL_Date);
		return mktime(0, 0, 0, $date[1], $date[2], $date[0]);
		
	}
	
	function fn_get_date($akt_datum) {
	       $datum = getdate();
           return $akt_datum = "$datum[mday].$datum[mon].$datum[year]";
	       
    }
	
	function fn_make_Dezimal($Zahl, $length="0", $null=TRUE) {
	
		if($Zahl == 0 and !$null) return FALSE;
	
		if($length == 0) {
			return str_replace(",", ".", number_format($Zahl));
		} else {
			return number_format($Zahl, $length, ",", ".");
		}
		
	}
	
	function fn_make_Dezimal_mysql($Zahl) {
	
		return doubleval(strtr($Zahl,",", "."));
	
	}
	
	function fn_Create_Thumbnail($imagefile, $thumbsDir, $size) {
		#if (!file_exists($thumbsDir)) {
		#mkdir($thumbsDir, 0644);
		#}
		$tn_file = $thumbsDir."/".basename($imagefile);
		$imageSize = GetImageSize($imagefile);
		if ($imageSize[0]) {
			$width = $imageSize[0];
			$height = $imageSize[1];
			if ($width>$height) {
				$tn_width = $size;
				$tn_height = round($size*($height/$width));
			} else {
				$tn_height = $size;
				$tn_width = round($size*($width/$height));
			}
			$shellCmd = "convert -geometry ".$tn_width."x".$tn_height." ".$imagefile." ".$tn_file;
			exec($shellCmd);
			#chmod($tn_file, 0644);
		}
    }
	
	
	// #################### //
	//   Hilfsfunktionen    //
	// #################### //
	
	// Verhindert das Speichern im Browsercache
	function fn_nocache() {
	
		header("Expires: Sat, 26 May 1964 00:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-cache, must-revalidate");
		header("Pragma: no-cache");
		
	}
	
	// Checksumme md5 erstellen (Hash)
	function fn_hash ($data) {
	
		return md5(serialize($data));
		
	}


function html_caption($title,$width=600)
{
    echo '
    <table width="'.$width.'" border="0" cellpadding="0" cellspacing="0">
    <tr>
	<td><b>'.$title.'</b></td>
    </tr>
    <tr>
	<td height="1"><img src="img/pixel.gif" width="1" height="1"></td>
    </tr>
    <tr>
	<td bgcolor="#8994AA" height="1"><img src="img/pixel.gif" width="1" height="1"></td>
    </tr>
    </table>
    <br>
    ';
    //  #EC9712
    //  #8994AA
}





function make_password2()
{
    $pool = "qwertzupasdfghkyxcvbnm";
    $pool .= "23456789";
    $pool .= "WERTZUPLKJHGFDSAYXCVBNM";

    srand ((double)microtime()*1000000);
    for($index = 0; $index < 6; $index++)
    {
	$pass_word .= substr($pool,(rand()%(strlen ($pool))), 1);

    }
    return $pass_word;
}


function strip_cr($string)
{
    return str_replace("\r","",$string);
}


function enable_magic_quotes($array)
{
    if(get_magic_quotes_gpc() == 0)
    {
	foreach ($array as $key => $value)
	{
	    if(is_array($value))
	    {
		$temparray = $value;
		foreach($temparray as $key2 => $value2)
		{
		    $temparray[$key2] = addslashes($value2);
		}
		
		$array[$key] = $temparray;    
	    }
	    
	    if(!is_array($value) and !is_numeric($value))
	    {
		$array[$key] = addslashes($value);
	    }
	}
    }						
    return $array;
}
    
?>