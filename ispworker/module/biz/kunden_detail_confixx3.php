<?
$module = basename(dirname(__FILE__));
include("./inc/functions.inc.php");
include("../../header.php");


include("./inc/reiter1.layout.php");
$bgcolor[0]   = "#f0f0f0";
$linecolor[0] = "#000000";

$bgcolor[4]   = "#ffffff";
$linecolor[4] = "#ffffff";
include("./inc/reiter1.php");


$software = "Confixx 3";

$res = $db->query("select * from biz_interfaces where software ='$software'");
$con = $db->fetch_array($res); 



?>
<br>
<form action="module/biz/kunden_detail_confixx3.php?createaccount=true&kundenid=<?=$_REQUEST[kundenid]?>" method="post">
<table width="540" border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
  <td colspan="2"><b>Neuer <?=$software?> Kunden Account</b></td>
</tr>
<tr class="tr">
  <td>Server</td>
  <td><select name="serverid">
  <?
  $res = $db->query("select * from biz_defaultserver where servertyp ='confixx3'");
  while($row = $db->fetch_array($res)) {
    echo "<option value=\"$row[serverid]\">$row[servername] ($row[serverip])</option>";
  }
  ?>
  </select><br><font size="1">Reseller bzw. Adminmenü URL</font></td>
  
</tr>
<tr class="tr">
  <td>Paket Leistungen</td>
  <td>
  <select name="paketid">
  <?
  
  $lines = explode("\n",$con[konfig]);
 
  for($i=0;$i<=count($lines);$i++) {
    if(strstr($lines[$i],"[")) {
	$temp  = explode("[",$lines[$i]);
	$temp2 = explode("]",$temp[1]);
	$paketname = stripslashes($temp2[0]);
	$pakete[] = $paketname;
    }      
  }
  
  for($i=0;$i<count($pakete);$i++) {
    echo "<option value=\"$i\">$pakete[$i]</option>";
  }
  ?>
  </select><br><font size="1">Wählen Sie das richtige Paket für einen Reseller bzw. User Account</font></td>
  
</tr>
<tr class="tr">
  <td>Hauptdomain</td>
  <td>
  <select name="domain">
  <?
  $res = $db->query("select * from biz_domains where kundenid='$_REQUEST[kundenid]'");
  while($row = $db->fetch_array($res)) {
    echo "<option value=\"$row[domainname]\">$row[domainname]</option>";
  }
  ?>
  </select><br>
 </td>
</tr>
<tr class="tr">
  <td>Typ</td>
  <td><input type="checkbox" name="reseller" value="true">Reseller Account anlegen</td>
</tr>
<tr class="tr">
  <td colspan="2"><input type="submit" value="Anlegen"></td>
</tr>
</table>

</td>
</tr>
</table>
</form>

<br>
<br>


<?if($_REQUEST[createaccount]=="true") {


$res = $db->query("select * from biz_kunden where kundenid='$_REQUEST[kundenid]'");
$kunden = $db->fetch_array($res);


$firma 		= urlencode($kunden[firma]); 
$name  		= urlencode($kunden[nachname]);
$firstname 	= urlencode($kunden[vorname]);
$anschrift 	= urlencode($kunden[strasse]);
$ort 		= urlencode($kunden[ort]);
$plz 		= urlencode($kunden[plz]);
$land 		= urlencode($kunden[land]);
$telefon 	= urlencode($kunden[telefon]);
$kundennummer   = $_REQUEST[kundenid];
$emailadresse   = urlencode($kunden[mail]);


  $lines = explode("\n",$con[konfig]);
 
  $entry = 0;
  for($i=0;$i<=count($lines);$i++) {
    if(strstr($lines[$i],"[")) {
	if($_REQUEST[paketid]==$entry) {
	    $parse = true;
	}
	else { $parse = false; }
	$entry++;
    }      
    if($parse==true) {
	if(strstr($lines[$i],"=")) {
	    $t    = explode("=",$lines[$i]);
	    $var  = trim($t[0]);
	    $val  = trim($t[1]);
	    $$var = $val; 
	}
    }
  }

  
$res = $db->query("select * from biz_defaultserver where serverid='$_REQUEST[serverid]'");  
$server = $db->fetch_array($res);

if($_REQUEST[reseller]=="true") { $script = "anbieter_anlegen4.php"; $s = "Anbieter:"; }
else 		      		{ $script = "kunden_neu5.php"; $s = "Kunde:"; }


$mysid = confixx3_getsid($server[serverip], $server[benutzername], $server[passwort]);
sleep(1);
if($_REQUEST[reseller]=="true") { $sp = "admin"; }
else 		 		{ $sp = "reseller/$server[benutzername]"; }


// Confixx3 Parameter

if($bool_spamfilter=="") $bool_spamfilter = 1;
if($bool_modpython=="")  $bool_modpython  = 0;

$urlparams  = "maxkb=$webspace&kbtyp=MB&popmaxkb=$mailspace&popkbtyp=MB&maxpop=$pop3&maxemail=$mailaliase&maxautoresponder=$autoresponder";
$urlparams .= "&maxftp=$ftp&maxtransfer=$traffic&maxsubdomains=$subdomains&maxatdomains=$atdomains&maxmysql=$mysqldb&maxcronjobs=$cronjobs";
$urlparams .= "&wildcard=$wildcards&ftp=$bool_ftp&php=$bool_php&perl=$bool_perl&ssi=$bool_ssi&dirlist=$bool_dirlisting";     
$urlparams .= "&statistik=$bool_webalizer&pwschutz=$bool_dirpwd&fehlerseiten=$bool_errorpages&webftp=$bool_webftp";
$urlparams .= "&webmail=$bool_webmail&phpupload=$bool_phpupload&shell=$bool_shell&wap=$bool_wap&stdcgi=$bool_cgi&modpython=$bool_modpython";
$urlparams .= "&spamfilter=$bool_spamfilter&ip=standard&domain[1]=$_REQUEST[domain]&name=$name&firstname=$firstname&anschrift=$anschrift";
$urlparams .= "&ort=$ort&plz=$plz&land=$land&telefon=$telefon&kundennummer=$kundennummer&emailadresse=$emailadresse";
$urlparams .= "&maxidn=-1&maxmaillist=0&SID=$mysid";

$html = confixx3_anbieterneu($server[serverip], "/$sp/$script", $urlparams);
echo "<br>";

// Webserver Ausgabe
// echo $html;

$x = explode("$s",$html);
$y = explode("<b>",$x[1]);
$z = explode("</b>",$y[1]);

//$user = str_replace("<br>","",$y[0]);
//$user = str_replace("<b>","",$user);
//$user = str_replace("</b>","",$user);
$user = trim($z[0]);

$x = explode("Passwort:",$html);
$y = explode("<b>",$x[1]);
$z = explode("</b>",$y[1]);

//$pwd = str_replace("<br>","",$z[1]);
//$pwd = str_replace("<b>","",$pwd);
//$pwd = str_replace("</b>","",$pwd);
$pwd = trim($z[0]);


if($user!="") 
{
    $db->query("insert into biz_serveraccounts (serverid,accountname,accountpwd,serveradminid,kundenid) values ('$_REQUEST[serverid]','$user','$pwd','$_REQUEST[serverid]','$_REQUEST[kundenid]')");
    message("Account ist angelegt.");
} else 
    message("Der Account konnte nicht angelegt werden.","error");

}?>
<br>
<br>


<table width="550" border="0" cellpadding="0" cellspacing="0">
<tr>
    <td bgcolor="#cccccc" height="1"><img src="img/pixel.gif" width="1" height="1"></td>
</tr>
</table>
<br>


<form action="module/biz/kunden_detail_confixx3.php?confixxsync=true&kundenid=<?=$_REQUEST[kundenid]?>" method="post">
<table width="540" border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
  <td colspan="2"><b>Confixx Synchronisation</b></td>
</tr>
<tr class="tr">
  <td>Server</td>
  <td><select name="serverid">
  <?
  $res = $db->query("select * from biz_defaultserver where servertyp ='confixx3'");
  while($row = $db->fetch_array($res)) {
    echo "<option value=\"$row[serverid]\">$row[servername] ($row[serverip])</option>";
  }
  ?>
  </select> <input type="submit" value="Senden"></td>
  
</tr>
</table>

</td>
</tr>
</table>
</form>
    

<br>
<br>


<?
if($_REQUEST[confixxlinkaccount]=="true") {

    $mywebs = $_REQUEST["webs"];
    
    foreach($mywebs as $elem) {
	$db->query("insert into biz_serveraccounts (serverid,accountname,accountpwd,serveradminid,kundenid) values ('$_REQUEST[serverclientid]','$elem','','$_REQUEST[serverid]','$_REQUEST[kundenid]')");    
    }
    
    message("Account ist zugeordnet.");

}
?>




<?
if($_REQUEST[confixxsync]=="true") {
?>

<form action="module/biz/kunden_detail_confixx3.php?confixxlinkaccount=true&serverid=<?=$_REQUEST[serverid]?>&kundenid=<?=$_REQUEST[kundenid]?>" method="post">
<table width="540" border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
  <td></td>
  <td><b>User</b></td>
  <td><b>Name</b></td>
  <td><b>Domains</b></td>
</tr>
<?

    $res = $db->query("select * from biz_kunden where kundenid='$_REQUEST[kundenid]'");
    $kun = $db->fetch_array($res);

    $res = $db->query("select * from biz_defaultserver where serverid='$_REQUEST[serverid]'");
    $server = $db->fetch_array($res);
    
    
	$mysid = confixx3_getsid($server[serverip], $server[benutzername], $server[passwort]);
	sleep(1);

	if(strstr($server[benutzername],"res")) { $path = "/reseller/$server[benutzername]/kunden_aendern_kundenliste.php"; }
	else { die(); }
	$lines = confixx3_kundenausgeben($server[serverip],$path,"SID=$mysid");

	foreach ($lines as $line_num => $line) {
	    if(strstr($line,"<tr")) { $user = ""; $name = ""; $doms = ""; $parse = true; $counter = 0; }
	
	    //echo $line;
	    
	    if($parse==true) {
		if($counter==3) {
		    $a = explode("&kunde=",$line);
		    $b = explode("\"",$a[1]);
		    
		    $user = trim($b[0]);
		    $parse = false;
		}
		
		if($counter==1) { 		
		    //echo "bin hier";
		    //echo "line: ".$line;    
		    $a = explode("<td >",$line);
		    $b = explode("</td>",$a[1]);
		    $name = trim($b[0]);
    
		    if(strstr($name,$kun[nachname])) { $name = "<font color=\"green\"><b>$name</b></font>"; }
		}
	    
		if($counter==2) { 
		    $a = explode("<td >",$line);
		    $b = explode("</td>",$a[1]);
		    
		    $doms = trim($b[0]);
		}

		if($user != "")
		{
		?>
		    <tr class="tr">
			<td valign="top"><input type="checkbox" name="webs[]" value="<?=$user?>"></td>
			<td valign="top"><?=$user?></td>
			<td valign="top"><?=$name?></td>
			<td valign="top"><?=$doms?></td>
		    </tr>
		<?
		}	
		$counter++;
	    }
	}

    


    $res = $db->query("select * from biz_defaultserver where serverid='$_REQUEST[serverid]' and servertyp ='confixx3'");
    $row = $db->fetch_array($res);
  ?>
    <input type="hidden" name="serverclientid" value="<?=$row[serverid]?>">
  
<tr class="tr">
  <td colspan="4"><input type="submit" value="Ausgewählte Accounts dem Kunden zuordnen"></td>
</tr>
</table>

</td>
</tr>
</table>
</form>
    
<?
}
?>

<br>
<br>

<?include("../../footer.php");?>
