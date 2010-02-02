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


if($_REQUEST[setnewpass]==true) {
    if($_REQUEST[pass1]!=$_REQUEST[pass2]) message("Fehler: Passwörter unterscheiden sich.","error");
    else {
	$res = $db->query("select * from biz_serveraccounts where kundenid='$_REQUEST[kundenid]' and accountid='$_REQUEST[accountid]'");
	$server = $db->fetch_array($res);
	
	$res = $db->query("select * from biz_defaultserver where serverid='$server[serveradminid]'");
	$def = $db->fetch_array($res);
	    
        $mysid = confixx3_getsid($def[serverip], $def[benutzername], $def[passwort]);

	if(strstr($server[accountname],"web")) {
	    $path = "/reseller/$def[benutzername]/kunden_aendern_pw2.php";
	    $data = "kunde=$server[accountname]&neupw1=$_REQUEST[pass1]&neupw2=$_REQUEST[pass2]&SID=$mysid";
	}
	if(strstr($server[accountname],"res")) {
	    $path  = "/admin/anbieter_aendern_pw2.php";
	    $data = "kunde=$server[accountname]&neupw1=$_REQUEST[pass1]&neupw2=$_REQUEST[pass2]&SID=$mysid";
	}
	
	
	$x = explode("/",$def[serverip]);
    	$host = $x[2];

	$fp = open_post($host, $path, $data);
	close_post($fp);
	    
	message("Passwort ist gespeichert.");
	$res = $db->query("update biz_serveraccounts set accountpwd='$_REQUEST[pass1]' where accountid='$_REQUEST[accountid]' and kundenid='$_REQUEST[kundenid]'");

    }

}
?>





Das Passwort muss aus mindestens 6 Zeichen bestehen. Maximal sind 12 Zeichen erlaubt.
<br>
<br>


<form action="module/biz/kunden_detail_confixx3edit.php?setnewpass=true&accountid=<?=$_REQUEST[accountid]?>&kundenid=<?=$_REQUEST[kundenid]?>" method="post">
<table width="440" border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
  <td colspan="2"><b>Passwort ändern</b></td>
</tr>
<tr class="tr">
  <td>Passwort</td>
  <td><input type="password" name="pass1"></td>
</tr>
<tr class="tr">
  <td>Passwort Wiederholung</td>
  <td><input type="password" name="pass2"></td>
</tr>
<tr class="tr">
  <td colspan="2"><input type="submit" value="Speichern"></td>
</td>
</tr>
</table>

</td>
</tr>
</table>
</form>



<br>
<table width="550" border="0" cellpadding="0" cellspacing="0">
<tr class="tb">
    <td height="1"><img src="img/pixel.gif" width="1" height="1"></td>
</tr>
</table>
<br>
<a href="module/biz/kunden_detail_confixx3edit.php?sendaccountdata=true&accountid=<?=$_REQUEST[accountid]?>&kundenid=<?=$_REQUEST[kundenid]?>">Zugangsdaten versenden.</a>
<?
if($_REQUEST[sendaccountdata]==true) { 

    $res = $db->query("select * from biz_serveraccounts where kundenid='$_REQUEST[kundenid]' and accountid='$_REQUEST[accountid]'");
    $ser = $db->fetch_array($res);
    
    $res = $db->query("select * from biz_defaultserver where serverid='$ser[serveradminid]'");
    $def = $db->fetch_array($res);	    

    $res = $db->query("select * from biz_mailtemplates where templatename='std_confixxzugangsdaten'");
    $tpl = $db->fetch_array($res);
    
    $res = $db->query("select mail from biz_kunden where kundenid='$_REQUEST[kundenid]'");
    $rcp = $db->fetch_array($res);
    
    
    $tpl[mailtext] = str_replace("#confixxurl#","$def[serverip]",$tpl[mailtext]);
    $tpl[mailtext] = str_replace("#confixxuser#","$ser[accountname]",$tpl[mailtext]);
    $tpl[mailtext] = str_replace("#confixxpwd#","$ser[accountpwd]",$tpl[mailtext]);
    mail($rcp[mail],$tpl[mailbetreff],$tpl[mailtext],"From: ".$rowsets[kundenmenuemailfrom]);

    echo " - Zugangsdaten sind versendet."; 
    
    
}
?>
<br>
<br>
<table width="550" border="0" cellpadding="0" cellspacing="0">
<tr>
    <td class="tb" height="1"><img src="img/pixel.gif" width="1" height="1"></td>
</tr>
</table>
<br>
    

<br>
<br>

<?include("../../footer.php");?>
