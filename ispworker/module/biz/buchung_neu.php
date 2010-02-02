<?
$module = basename(dirname(__FILE__));
include("../../header.php");
?>

<?
if(isset($new)) {



  $db->query("insert into biz_kundenbuchungen (adminid,kundenid,datum,buchung,betrag)
              values ('$adminid','$kundenid','$datum','$buchung','$betrag')");

  echo "<center><b>Buchung ist gespeichert.</b></center><br><br>\n";
}
?>



<form action="module/biz/buchung_neu.php?new=true" method="post">
<table width="540" border="0" cellspacing="0" cellpadding="0">
<tr bgcolor="#cccccc" align="left" valign="top">
<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr bgcolor="#e7e7e7" align="left" valign="top">
  <td colspan="2"><b>Neue Buchung</b></td>
</tr>
<tr>
  <td bgcolor="#ffffff">Kunde</td>
  <td bgcolor="#ffffff">
  <select name="kundenid" size="10">
  <?
  $res = $db->query("select kundenid, nachname, vorname from biz_kunden where adminid='$adminid' order by nachname");
  while($row = $db->fetch_array($res)) {
  ?>
    <option value="<?=$row[kundenid]?>"><?=$row[nachname]?>,<?=$row[vorname]?> (<?=$row[kundenid]?>)</option>
  <?
  }
  ?>
  </select>
  </td>
</tr>
<tr>
  <td bgcolor="#ffffff">Buchungstext</td>
  <td bgcolor="#ffffff">
  <textarea name="buchung" rows="6" cols="40"></textarea>
  </td>
</tr>
<tr>
  <td bgcolor="#ffffff">Datum</td>
  <td bgcolor="#ffffff">  y-m-d<br>
  <input type="text" name="datum">
  </td>
</tr>
<tr>
  <td bgcolor="#ffffff">Betrag</td>
  <td bgcolor="#ffffff">
  <input type="text" name="betrag" size="8">
  </td>
</tr>


<tr>
  <td bgcolor="#ffffff">&nbsp;</td>
  <td bgcolor="#ffffff"><br><br><input type="submit" value="Speichern"></td>
</tr>
</table>


</td>
</tr>
</table>
</form>








<?include("../../footer.php");?>
