<?
session_start();
$module = basename(dirname(__FILE__));

include("../../include/config.inc.php");
include("./inc/functions.inc.php");

$res = $db->query("select * from biz_kunden where adminid='$_SESSION[adminid]' and kundenid='$kundenid'");
$row = $db->fetch_array($res);

?>

<html>
<head>
<title>Kundennotiz</title>
<base href="<?=$basehref?>">
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<a href="javascript:self.close();">Fenster schliessen</a><br><br>



<table width="450" border="0" cellspacing="1" cellpadding="3">
<tr>
  <td bgcolor="#ffffff">Aktuelle Notiz zum Kunden: <b><?=$row[vorname]?> <?=$row[nachname]?></b> </td>
  <td bgcolor="#ffffff"></td>
</tr>
<tr>
  <td bgcolor="#ffffff" colspan="2">&nbsp;</td>
</tr>
<tr>
  <td bgcolor="#ffffff" colspan="2"><b><?=$row[bemerkung]?></b></td>
</tr>

</table>

</body>
</html>





