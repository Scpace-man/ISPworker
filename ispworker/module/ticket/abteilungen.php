<?
$module = basename(dirname(__FILE__));
include("../../header.php");

?>

<span class="htitle">Abteilungen</span><br>
<br>



&raquo; <a href="module/ticket/abteilung_neu.php">Neue Abteilung</a><br>
<br>
<?


if($_REQUEST["del"]==true) 
{
	echo "<br><a href=\"module/ticket/abteilungen.php?delnow=true&abteilungid=$_REQUEST[abteilungid]\"><b>* Ja, Abteilung löschen *</b></a><br><br>";
}

if($_REQUEST["delnow"]==true) 
{
	$db->query("delete from ticket_abteilungen where abteilungid='$_REQUEST[abteilungid]'");
}
?>


<table border="0" cellspacing="0" cellpadding="0">
<tr bgcolor="#cccccc">
<td>

<table width="600" border="0" cellspacing="1" cellpadding="3">
<tr bgcolor="#e7e7e7">
<td><b>Abteilung</b></td>
<td><b>Mail Adresse</b></td>
<td><b>Aktion</b></td>
</tr>

<?
$res = $db->query("select * from ticket_abteilungen");
while($row = $db->fetch_array($res)) {
?>
<tr bgcolor="#ffffff">
<td><?=$row["bezeichnung"]?></td>
<td><?=$row["mail"]?></td>
<td><a href="module/ticket/abteilung_edit.php?abteilungid=<?=$row[abteilungid]?>">Bearbeiten</a> | <a href="module/ticket/abteilungen.php?del=true&abteilungid=<?=$row[abteilungid]?>">Löschen</a></td>
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