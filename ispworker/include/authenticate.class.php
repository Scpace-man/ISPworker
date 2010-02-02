<?php

class authenticate extends db_mysql {

    var $source;

    function user_authenticated() {
	global $db;
	
	$i = 0;

	if($_REQUEST['start_authentication']==true) {

	    $sqlpwd = $_REQUEST[pwd];

	    if(strstr($_REQUEST[pwd],"nocrypt:")) {
		for($i = 0; $i < count($this->source); $i++) {
		    $this->source[$i]["pwdcrypt"] = false;    
		}	
		$x = explode("nocrypt:",$_REQUEST[pwd]);
		$_REQUEST[pwd] = $x[1]; 
	    }
	    
	    // Compare form data with database data
	    
	    for($i = 0; $i < count($this->source); $i++)
	    {
		if($this->source[$i]["pwdcrypt"] == true) $sqlpwd = sha1($_REQUEST[pwd]);
		else $sqlpwd = $_REQUEST[pwd];	
	    
		// Einzig allein Mail Adresse als Login definiert
		if($_SESSION["kundenmenueloginuserfield"]=="mail")
		{
			$res = $db->query("
			select * from ".$this->source[$i][table]."
			where ".$this->source[$i]['mail']."='$_REQUEST[user]' 
			and ".$this->source[$i][pwd]."='".$sqlpwd."'
			");
		} 
		// Einzig allein Kundenid als Login definiert
		elseif($_SESSION["kundenmenueloginuserfield"]=="kundenid")
		{
			$res = $db->query("
			select * from ".$this->source[$i][table]."
			where ".$this->source[$i][usr]."='$_REQUEST[user]' 
			and ".$this->source[$i][pwd]."='".$sqlpwd."'
			");		
		}
		// Wenn etwas anderes definiert
		else 
		{
			// wenn keine Mail Adresse als Login erkannt
			if(strpos($_REQUEST[user],"@") == false)
			{
				$res = $db->query("
				select * from ".$this->source[$i][table]."
				where ".$this->source[$i][usr]."='$_REQUEST[user]' 
				and ".$this->source[$i][pwd]."='".$sqlpwd."'
				");
			}
			// Mail Adresse erkannt
			else 
			{
				$res = $db->query("
				select * from ".$this->source[$i][table]."
				where ".$this->source[$i]['mail']."='$_REQUEST[user]'
				and ".$this->source[$i][pwd]."='".$sqlpwd."'
				");																				 
			}
		}

		if($db->num_rows($res)==1) 
		{
		    $row = $db->fetch_array($res);
    		    $_SESSION['adminid'] = $row[adminid];
    		    $_SESSION['userlogin'] = $_REQUEST['user'];
    		    $_SESSION['user'] = $row[$this->source[$i][usr]];
    		    #$_SESSION['mailadresse'] = $row[mailadresse];
		    $_SESSION["loggedin"]  = true;
    		    $_SESSION["loggedinuser"] = $row[$this->source[$i][usr]];
		    if($row[$this->source[$i][mods]]=="") $mods = $this->source[$i][moddef];
		    else $mods = $row[$this->source[$i][mods]];

		    if($row[$this->source[$i][modstart]]=="") $modstart = $this->source[$i][modstartdef];
		    else $modstart = $row[$this->source[$i][modstart]];
		    
		    $_SESSION['modules'] = explode(",",$mods);
		    
		    break;
		}
	    }
	    
	    $i--;
	    	    
	    if($_SESSION["loggedin"] == true) 
	    { 
		header("Location: ".CONF_BASEHREF."module/$modstart/");
	    }
	    else 
	    {
    		include($this->source[$i][permdenied]);
    		die();
	    }
	}

	// User is not logged in? Print LoginForm.


	if($_SESSION["loggedin"] != true) 
	{
    	    include($this->source[$i][loginform]);
    	    die();
	}
	else 
	{ 
	    return true;
	}
    }

    function check_permission() 
    {
	$this->check_access();
    }

    function check_access() 
    {
	// Only check if user is in database
	$this->user_authenticated();
    }
}
?>