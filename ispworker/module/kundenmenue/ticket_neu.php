<?
$module = basename(dirname(__FILE__));
include("../../header.php");
?>

<b>Neue Support Anfrage</b><br>
<br>

<?

if($_REQUEST[send]=="true") {

    $res = $db->query("select * from biz_kunden where kundenid='$_SESSION[user]'");
    $row = $db->fetch_array($res);

    mail($_REQUEST[mailto],$_REQUEST[betreff],$_REQUEST[nachricht],"From: $row[mail]");

    echo "<font color=\"green\">Anfrage gesendet.</font><br><br>";

}


?>


<form action="module/kundenmenue/ticket_neu.php?send=true" method="post">
<table width="500" border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
  <td colspan="2"><b>Neues Ticket</b></td>
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
  <td valign="top"><input type="text" name="betreff"></td>
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


<?include("../../footer.php");?>
