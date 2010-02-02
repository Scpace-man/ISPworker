<?session_start();
include(dirname(__FILE__)."/../../include/config.inc.php");
include(dirname(__FILE__)."/../../include/common.inc.php");
?>
<html>
<head>
    <title><?=CONF_TITLE?></title>
    <base href="<?=CONF_BASEHREF?>">
    <link href="style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#ffffff" leftmargin="0" topmargin="5" marginbottom="5" marginright="5" marginheight="5" marginwidth="5">
<span class="htitle" align="center">PDF-Koordinaten für <?=$_REQUEST[feld]?></span><br>

<?
if(isset($_REQUEST[update])) {

$db->query("UPDATE biz_layout SET ".
$_REQUEST[feld]."xy" ."= '".$_REQUEST[$_REQUEST[feld]."x"].",".$_REQUEST[$_REQUEST[feld]."y"]."'
WHERE adminid='$_SESSION[adminid]' AND profilid ='$_REQUEST[profilid]'");

    echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"95%\">";
    echo "<tr><td bgcolor=\"#404040\"><img src=\"img/pixel.gif\" width=\"1\" height=\"1\"></td></tr>";    
    echo "<tr><td bgcolor=\"#f7f7f7\" valign=\"middle\" height=\"30\"><img src=\"img/pixel.gif\" width=\"5\" height=\"1\"><font color=\"green\"><b>Daten erfolgreich aktualisiert!</b></font></td></tr>";
    echo "<tr><td bgcolor=\"#404040\"><img src=\"img/pixel.gif\" width=\"1\" height=\"1\"></td></tr>";    
    echo "</table>";

}

$res = $db->query("select layoutid,profilid,adminid,$_REQUEST[feld]xy from biz_layout
where adminid='$_SESSION[adminid]' AND profilid='$_REQUEST[profilid]'");
$row = $db->fetch_array($res);

$coords=explode(",",$row[$_REQUEST[feld]."xy"]);

?>

<form action="module/biz/pdf_koordinaten.php?update=true&profilid=<?=$_REQUEST[profilid]?>&feld=<?=$_REQUEST[feld]?>" method="post">
<table width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><td colspan="2">&nbsp;</td></tr>
<tr bgcolor="#cccccc" align="left" valign="top">
	<td width="100">
		X-Koordinate:		
	</td>
	<td width=100">
		<input type="text" name="<?=$_REQUEST[feld]."x"?>" value="<?=$coords[0]?>" size="3">		
	</td>	
</tr>
<tr bgcolor="#cccccc" align="left" valign="top">
	<td width="100">
		Y-Koordinate:		
	</td>
	<td width="100">
		<input type="text" name="<?=$_REQUEST[feld]."y"?>" value="<?=$coords[1]?>" size="3">		
	</td>	
</tr>
<tr><td colspan="2">&nbsp;</td></tr>
<tr>
	<td>
		<input type="submit" name="button" value="Speichern">
	</td>
	<td>
		<input type="button" name="button" value="Schliessen" onclick="javascript:window.close();">
	</td>	
</tr>
</table>
</form>
<br>
<br>
</td>

</body>
</html>
