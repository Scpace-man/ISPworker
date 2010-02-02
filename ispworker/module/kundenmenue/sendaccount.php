<?

//$module = basename(dirname(__FILE__));
//include("../../header.php");


$noauth=true;
include("../../include/config.inc.php");
include("../../include/common.inc.php");


if($_REQUEST["send"]=="true") {
  $res = $db->query("select kundenid, mail as mailadresse from biz_kunden where mail='$_REQUEST[mail]'");
  $row = $db->fetch_array($res);

  if($row[mailadresse]=="") {
    echo "<font face=\"Arial\" size=\"2\" color=\"red\"><b>Die angegebene Mailadresse existiert nicht im System!</b></font><br><br>";
	$flag=false;
  } else {

    $rest = $db->query("select * from biz_mailtemplates where templatename='std_zugangsdaten'");
    $rowt = $db->fetch_array($rest);
		
    $mailbetreff = $rowt[mailbetreff];
    $mailtext    = $rowt[mailtext];


	$pw=make_password2();

    $pwupdate = $db->query("update biz_kunden set passwort='".sha1($pw)."' where mail='$_REQUEST[mail]'");

    if($rowsets["kundenmenueloginuserfield"]=="mail") {
        $mailbetreff = str_replace("#benutzername#", "$row[mailadresse]", $mailbetreff);
	$mailtext = str_replace("#benutzername#", "$row[mailadresse]", $mailtext);
    }
    else {
	$mailbetreff = str_replace("#benutzername#", "$row[kundenid]", $mailbetreff);
	$mailtext = str_replace("#benutzername#", "$row[kundenid]", $mailtext);
    }
					      


    $mailbetreff = str_replace("#passwort#", "$pw", $mailbetreff);
    $mailtext = str_replace("#passwort#", "$pw", $mailtext);

    mail("$row[mailadresse]", "$mailbetreff", "$mailtext","From: ".CONF_MAILFROM);

    echo "<div align=\"center\"></div><font face=\"Arial\" size=\"2\">Mail mit den Zugangsdaten gesendet!<br>Sie werden automatisch weitergeleitet.</font></div>";
	$flag=true;
	}
}

if($flag==true){
	echo'<html>
	<head>
		<title>Zugangsdaten zusenden</title>
		<meta http-equiv="refresh" content="2; URL='.CONF_BASEHREF.'">		
	</head>
	';
	
}else{
	echo'<html>
	<head>
		<title>Zugangsdaten zusenden</title>
	</head>
	';
}
?>
<body bgcolor="#FFFFFF" link="#000000" alink="#000000" vlink="#000000">
<br>
<br>
<center>
<form action="sendaccount.php?send=true" method="post">
<table width="400" border="0" cellpadding="1" cellspacing="0">
	<tr>
		<td bgcolor="FFFFFF">
			<table width="100%" border="0" cellpadding="2" cellspacing="1">
				<tr>
					<td colspan="2" bgcolor="#AEBFCE"><font face="Arial" size="2"><b>Zugangsdaten an Ihre E-Mail Adresse senden</b></font></td>
				</tr>
				<tr>
					<td bgcolor="#F9F9F9"><font face="Arial" size="2">E-Mail Adresse</font></td>
  					<td bgcolor="#F9F9F9"><input type="text" name="mail"></td>
				</tr>
				<tr>
					<td bgcolor="#F9F9F9" colspan="2" align="center"><input type="submit" value="Senden"></td>
				</tr>
			</td>
	</table>
	<br>
	<br>
	</td>
</tr>
<tr>
	<td align="center"><font face="Arial" size="2"><a href="<?=CONF_BASEHREF?>"><b>Zurück zum Login</b></a></font></td>
</tr>
</table>
</form>
</center>
</html>

