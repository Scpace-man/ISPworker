<?
include("include/version.inc.php");
?>
<html>
<head>
<title>Login - ISPworker</title>
<link href="style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#FFFFFF" link="#000000" alink="#000000" vlink="#000000">
<br>
<br>
<center>

<form action="<?=$PHP_SELF?>" method="post">
<input type="hidden" name="start_authentication" value="true">

<table width="400" border="0" cellpadding="1" cellspacing="0" class="text">
<tr>
<td bgcolor="FFFFFF">

<table width="100%" border="0" cellpadding="2" cellspacing="1">

<tr>
  <td colspan="2" bgcolor="#FFFFFF" align="center"><img src="../../img/logo_login.gif" alt="" border=0></td>
</tr><tr>
  <td colspan="2" bgcolor="#AEBFCE"><font face="Arial" size="2"><b>Login</b></font></td>
</tr>
<tr>
  <td bgcolor="#F9F9F9"><font face="Arial" size="2">Benutzername</font></td>
  <td bgcolor="#F9F9F9"><input type="text" name="user"></td>
</tr>
<tr>
  <td bgcolor="#F9F9F9"><font face="Arial" size="2">Passwort</font></td>
  <td bgcolor="#F9F9F9"><input type="password" name="pwd"></td>
</tr>
<tr>
  <td bgcolor="#F9F9F9" colspan="2" align="center"><input type="submit" value="Login"></td>
</tr>
<tr>
  <td bgcolor="#FFFFFF" colspan="2" align="center">&nbsp;</td>
</tr>
<tr>
  <td bgcolor="#FFFFFF" colspan="2" align="center"><font face="Arial" size="2"><a href="../kundenmenue/sendaccount.php"><b>Zugangsdaten vergessen?</b></a></font></td>
</tr>
</td>
</table>

</td>
</tr>
</table>
</form>
<br>
<font size="1" color="#CCCCCC" face="Arial"><a href="http://www.ispware.de" target="_blank">ISPworker Version <?echo VERSION;?></a> <br>crossconcept GmbH</font>
<br>
<br>

</center>
</html>
