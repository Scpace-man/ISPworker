<?session_start();
include(dirname(__FILE__)."/include/config.inc.php");
include(dirname(__FILE__)."/include/common.inc.php");
include(dirname(__FILE__)."/include/version.inc.php");


if($redirect == true) { header("Location: $redirectlocation"); die(); }

?>

<html>
<head>
    <title><?=CONF_TITLE?></title>
    <base href="<?=CONF_BASEHREF?>">
    <link href="style.css" rel="stylesheet" type="text/css">
    <script src="include/functions.standard.js" type="text/javascript" language="Javascript"></script>
</head>
<body bgcolor="#ffffff" leftmargin="0" topmargin="0" marginbottom="0" marginright="0" marginheight="0" marginwidth="0">

<?
$key = 0;
$d = dir(CONF_MODULEPATH);
while($entry=$d->read()) {
    if($entry!="." and $entry!="..") {
	if($mwc==false)  $key = array_search("$entry", $_SESSION['modules']);
        if($key!==FALSE) $modlist[] = $entry;
    }
}
$d->close();
?>

<table cellpadding="0" cellspacing="0" border="0" width="100%" height="50">
<tr>
    <td width="151" bgcolor="#FFFFFF" valign="top" align="center" valign="bottom"><img src="img/logo.gif" border="0"></td>
    <td valign="top">

    <table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
    <td bgcolor="#8994aa" valign="bottom" height="50">
    
    <table border="0" cellpadding="0" cellspacing="0">
    	  <tr>
			<td width="50" height="2"><img src="img/pixel.gif" width="50" height="2" border="0"></td><?
			for($i = 0; $i < count($modlist); $i++) 
			{
                // Wenn erster Menupunkt erstellt werden soll
                if($i==0)
                {
                    // Wenn erster Menupunkt aktiv ist
                    if($module == $modlist[$i])
                    {
                        echo '<td colspan="2"><img src="img/reit-akt-top-left.gif" width="2" height="2"></td>';
                        echo '<td><img src="img/reit-akt-top.gif" width="75" height="2"></td>';
                        echo '<td colspan="3"><img src="img/reit-middle.gif" width="3" height="2"></td>';
                    }
                    else
                    {
                        echo '<td colspan="2"><img src="img/reit-deakt-top-left.gif" width="2" height="2"></td>';
                        echo '<td><img src="img/reit-deakt-top.gif" width="75" height="2"></td>';
                        echo '<td colspan="3"><img src="img/reit-middle.gif" width="3" height="2"></td>';
                    }
                }
                // Wenn letzter Menupunkt erstellt werden soll
                elseif($i==count($modlist)-1)
                {
                    // Wenn letzter Menupunkt aktiv ist
                    if($module == $modlist[$i])
                    {
                        echo '<td><img src="img/reit-akt-top.gif" width="75" height="2"></td>';
                        echo '<td colspan="3"><img src="img/reit-akt-top-right.gif" width="2" height="2"></td>';
                    }
                    else
                    {
                        echo '<td><img src="img/reit-deakt-top.gif" width="75" height="2"></td>';
                        echo '<td colspan="3"><img src="img/reit-deakt-top-right.gif" width="2" height="2"></td>';
                    }
                }
                else
                {
                    if($module == $modlist[$i])
                    {
                        echo '<td><img src="img/reit-akt-top.gif" width="75" height="2"></td>';
                        echo '<td colspan="3"><img src="img/reit-middle.gif" width="3" height="2"></td>';
                    }
                    else
                    {
                        echo '<td><img src="img/reit-deakt-top.gif" width="75" height="2"></td>';
                        echo '<td colspan="3"><img src="img/reit-middle.gif" width="3" height="2"></td>';
                    }
                }
			}
			?></td>
			<td><img src="img/pixel.gif" width="1" height="1" border="0"></td>
    	  </tr>
    	  <tr>
			<td width="50" height="20"><img src="img/pixel.gif" width="50" height="20"></td>
			<?
			for($i = 0; $i < count($modlist); $i++) 
			{
	   			if($module == $modlist[$i]) $hbgcolor = "#ffffff";
	    		else $hbgcolor = "#e3d2c4";
	    
	    		echo '
	    		<td bgcolor="#000000" width="1"><img src="img/pixel.gif" width="1" height="1"></td>
	    		<td bgcolor="'.$hbgcolor.'" width="1"><img src="img/pixel.gif" width="1" height="1"></td>
	    		<td bgcolor="'.$hbgcolor.'" width="75" align="center"><a class="navmod" href="module/'.$modlist[$i].'">'.$modulename[$modlist[$i]].'</a></td>
	    		<td bgcolor="'.$hbgcolor.'" width="1"><img src="img/pixel.gif" width="1" height="1"></td>
	    		';
	    		if($i==count($modlist)-1) echo '<td bgcolor="'.$hbgcolor.'" width="1"><img src="img/pixel.gif" width="1" height="1"></td>';
			}
			?>
			<td bgcolor="#000000" width="1"><img src="img/pixel.gif" width="1" height="1"></td>
	    	<td><img src="img/pixel.gif" width="2" height="1"></td>
    	  </tr>
    	  <tr>
			<td bgcolor="#000000" width="50" height="1"><img src="img/pixel.gif" width="50" height="1"></td>
			<?
			for($i = 0; $i < count($modlist); $i++) 
			{
	    		if($module == $modlist[$i]) $hbgcolor = "#ffffff";
	    		else $hbgcolor = "#000000";

	    		echo '
	    		<td bgcolor="#000000" width="1"><img src="img/pixel.gif" width="1" height="1"></td>
	    		<td bgcolor="'.$hbgcolor.'" width="1"><img src="img/pixel.gif" width="1" height="1"></td>
	    		<td bgcolor="'.$hbgcolor.'" width="75"><img src="img/pixel.gif" width="1" height="1"></td>
	    		<td bgcolor="'.$hbgcolor.'" width="1"><img src="img/pixel.gif" width="1" height="1"></td>
	    		';
	    		if($i==count($modlist)-1) echo '<td bgcolor="'.$hbgcolor.'" width="1"><img src="img/pixel.gif" width="1" height="1"></td>';
			}
			?>
			<td bgcolor="#000000" width="1"><img src="img/pixel.gif" width="1" height="1"></td>
	    	<td bgcolor="#000000" width="100%"><img src="img/pixel.gif" width="1" height="1"></td>
    	  </tr>
    	</table>
        
        </td>
    </tr>
    </table>
    
    </td>
</tr>
</table>
<table cellpadding="0" cellspacing="0" border="0" width="100%" height="100%">
<tr>
<td width="150" bgcolor="#E9E9E9" valign="top">
<!-- Innere Nav Tabelle -->
<table border="0" cellspacing="0" cellpadding="0">
<!-- Anfangsabstand -->

<tr>
  <td width="150" height="8"><img src="img/pixel.gif" width="150" height="1"></td>
</tr>
<!-- Anfangsabstand Ende -->
<!-- Nav Elemente -->
<?
  // Nav Links
  $key = 0;
  $d = dir(CONF_MODULEPATH);
  while($entry=$d->read()) {
    if($entry!="." and $entry!="..") {
      if($mwc==false) {
        $key = array_search("$entry", $_SESSION['modules']);
      }
      if($key!==FALSE) {
        @include(CONF_MODULEPATH."/$entry/inc/config.inc.php");
        if($module==$entry) {
          include(CONF_MODULEPATH."/$entry/nav_opened.php");
        }
        else {
          include(CONF_MODULEPATH."/$entry/nav_closed.php");
        }
      }


    }
  }
  $d->close();




?>

<tr>
  <td width="145">&nbsp;<a href="logout.php" class="nav">Logout</a>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  </td>
</tr>
</table>
<!-- Nav Elemente Ende -->
<!-- Innere Nav Tabelle Ende -->
</td>
<td bgcolor="#9A9A9A" width="1"><img src="img/pixel.gif" width="1" height="1"></td>
<td bgcolor="#646464" width="1"><img src="img/pixel.gif" width="1" height="1"></td>

<td valign="top">
<table width="100%" border="0" celladding="0" cellspacing="0">
<tr>
<td width="15"><img src="img/pixel.gif" width="15"></td>
<td>

<img src="img/pixel.gif" width="1" height="1"><br>
<br>



<?

if($mwc==false) {
    $go = false;
    if(in_array($module,$_SESSION['modules'])==false) {
	echo "Zugriff verweigert"; include(dirname(__FILE__)."/footer.php"); die();
    }
}
?>

