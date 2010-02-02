<?
$module = basename(dirname(__FILE__));
include("../../header.php");
?>


<b>Support Anfragen</b><br>
<br>
Hinweis: Neue Anfragen erscheinen auf dieser Seite in der Regel erst alle 5 Minuten nach Versand.
<br>
<br>

<?

if($_REQUEST[resend]=="true") {

    $res = $db->query("select * from biz_kunden where kundenid='$_SESSION[user]'");
    $row = $db->fetch_array($res);
	
    mail($_REQUEST[mailto],$_REQUEST[betreff],$_REQUEST[nachricht],"From: $row[mail]");
	    
    echo "<font color=\"green\">Antwort gesendet.</font><br><br>";

}




if($_REQUEST[re]=="true") {
?>

<form action="module/kundenmenue/tickets.php?resend=true" method="post">
<table width="500" border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
  <td colspan="2"><b>Antwort</b></td>
</tr>
<tr class="tr">
  <td valign="top">An</td>
<td valign="top">
<select name="mailto">
<?
$res = $db->query("select * from ticket_abteilungen");
while($row = $db->fetch_array($res)) {
?>
<option value="<?=$row[mail]?>"><?=$row[bezeichnung]?></option>
<?}?>
</select>
</td>
</tr>
<tr class="tr">
  <td valign="top">Betreff</td>
  <td valign="top"><input type="hidden" name="betreff" value="[TicketID: <?=$_REQUEST[ticketid]?>]"> [TicketID: <?=$_REQUEST[ticketid]?>]</td>
</tr>
<tr class="tr">
  <td valign="top">Nachricht</td>
  <td valign="top"><textarea name="nachricht" cols="60" rows="14"></textarea></td>
</tr>
</table>
	
</td>
</tr>
</table>
<br>
<input type="submit" value="Senden">
<br>
<br>
</form>
	
<?

}


$resk = $db->query("select * from biz_kunden where kundenid='$_SESSION[user]'");
$rowk = $db->fetch_array($resk);

$res = $db->query("select * from ticket_anfragen where frommail='$rowk[mail]' order by eingegangen DESC");
while($row = $db->fetch_array($res)) {


?>

<table width="630" border="0" cellspacing="0" cellpadding="0">
<tr>
<td class="tb">

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
<td><b><?=$row[betreff]?>, <?$datum = date("d.m.Y H:i:s",$row[eingegangen]); echo $datum;?> (Status: <?=$row[status]?>)</b></td>
</tr>

<tr class="tr">
<td><font face="Verdana" size="1"><?$row[nachricht] = nl2br($row[nachricht]);?><?=$row[nachricht]?></font></td>
</tr>

<?
$resa = $db->query("select * from ticket_anfrageantworten where ticketid='$row[ticketid]' order by eingegangen");
while($rowa = $db->fetch_array($resa)) {
$datum = date("d.m.Y H:i:s",$rowa[eingegangen]);
?>
<tr class="th">
<td><b>Antwort, <?=$datum?> - Von: <?=$rowa[frommail]?> An: <?=$rowa[tomail]?></b></td>
</tr>
<tr class="tr">
<td><font face="Verdana" size="1"><?$rowa[nachricht] = nl2br($rowa[nachricht]);?><?=$rowa[nachricht]?></font></td>
</tr>
<?
}
?>
<tr class="tr">
<td><font face="Verdana" size="1"><a href="module/kundenmenue/tickets.php?re=true&ticketid=<?=$row[ticketid]?>">Antworten</a></font></td>
</tr>
</table>

</td>
</tr>
</table>
<br>
<?}?>

<br>





<br>
<br>
<br>



<?include("../../footer.php");?>
