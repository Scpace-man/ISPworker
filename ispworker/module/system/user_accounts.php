<?
$module = basename(dirname(__FILE__));
include("../../header.php");
include("inc/config.inc.php");


if($_SESSION[adminid]!=1) {
	die("Bitte loggen Sie sich als Superuser (id 1) ein.");
	include("../../footer.php");
}

?>

<span class="htitle">User Accounts</span><br>
<br>



&raquo; <a href="module/system/user_account_new.php">Neuer Account</a><br>
<br>
<?


if($_REQUEST[del]=="true") {
	echo "<br><a href=\"module/system/user_accounts.php?delnow=true&tadminid=$_REQUEST[tadminid]\"><b>* Ja, User Account löschen *</b></a><br><br>";
}

if($_REQUEST[delnow]=="true" && $system_userloeschbar!=false) {
	$db->query("delete from adminaccounts where adminid='$_REQUEST[tadminid]'");
}
?>


<table border="0" cellspacing="0" cellpadding="0">
<tr bgcolor="#cccccc">
<td>

<table width="600" border="0" cellspacing="1" cellpadding="3">
<tr bgcolor="#e7e7e7">
<td><b>ID</b></td>
<td><b>Username</b></td>
<td colspan="2"><b>Aktion</b></td>
</tr>

<?
$res = $db->query("select * from adminaccounts order by adminid");
while($row = $db->fetch_array($res)) {
?>
<tr bgcolor="#ffffff">
<td><?=$row[adminid]?></td>
<td><?=$row[userid]?></td>
<td width="16"><a href="module/system/user_account_edit.php?tadminid=<?=$row[adminid]?>"><img alt="Bearbeiten" src="img/edit.gif" border="0" alt="Bearbeiten"></a></td>
<td width="16"><a href="module/system/user_accounts.php?del=true&tadminid=<?=$row[adminid]?>"><img alt="Bearbeiten" src="img/trash.gif" border="0" alt="Löschen"></a></td>
</tr>
<?
}
?>


</table>

</td>
</tr>
</table>

<br>
<br>
<br>

<?include("../../footer.php");?>