<?
$module = basename(dirname(__FILE__));
include("../../header.php");
include("./inc/functions.inc.php");
?>

<span class="htitle">Ticket erstellen</span><br>
<br>


<?

if($_REQUEST[empfaenger]!="" and $_REQUEST[betreff]!="" and $_REQUEST[nachricht]!="" and $_REQUEST[action]="send") {
    $time = time();
    
    $res = $db->query("select * from ticket_abteilungen where abteilungid='$_REQUEST[absender]'");
    $row = $db->fetch_array($res);
    $absendermail = $row[mail];
    
    $db->query("insert into ticket_anfragen (betreff,nachricht,frommail,tomail,status,eingegangen)
		values ('$_REQUEST[betreff]','$_REQUEST[nachricht]','$absendermail','$_REQUEST[empfaenger]','Erledigt','$time')");
    $id = $db->insert_id();

    $_REQUEST[betreff] = "[TicketID: $id] ".$_REQUEST[betreff];
    mail($_REQUEST[empfaenger],$_REQUEST[betreff],$_REQUEST[nachricht],"From: $absendermail");
    echo "<font color=\"green\">Ticket ist versendet und gespeichert.</font><br><br>";
}

$res 	= $db->query("select * from ticket_personenprofile where pprofilid='$_SESSION[adminid]'");
$profil = $db->fetch_array($res);

?>

<form action="module/ticket/ticket_neu.php?action=send" method="post">
<table width="550" border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
  <td colspan="2"><b>Neues Ticket</b></td>  
</tr>
<tr class="tr">
  <td>Absender</td>  
  <td><select name="absender">
<?
$res = $db->query("select * from ticket_abteilungen");
while($row = $db->fetch_array($res)) {
    echo "<option value=\"$row[abteilungid]\">$row[mail]</option>";
}
?>
</select></td>
</tr>
<tr class="tr">
  <td>Empfänger</td>  
  <td><input type="text" name="empfaenger" size="50"></td>
</tr>
<tr class="tr">
  <td>Betreff</td>  
  <td><input type="text" name="betreff" size="50"></td>
</tr>
<tr class="tr">
  <td colspan="2"><textarea name="nachricht" cols="100" rows="9"><?="\n\n".$profil[signatur]?></textarea></td>
</tr>
<tr class="tr">
  <td colspan="2"><input type="submit" value="Senden"></td>
</tr>

</table>


</td>
</tr>
</table>
</form>













<?include("../../footer.php");?>