<?
$module = basename(dirname(__FILE__));
include("../../header.php");



if($_REQUEST[update]=="true") {
    $db->query("update order_settings set bemail='$_REQUEST[bemail]',bzusammenfassung='Y',bvertrag='".strip_cr($_REQUEST[bvertrag])."', bkkvertrag='".strip_cr($_REQUEST[bkkvertrag])."', bsendmail='".strip_cr($_REQUEST[bsendmail])."', babsender='".strip_cr($_REQUEST[babsender])."',
	        babsendermail='".strip_cr($_REQUEST[babsendermail])."', bbetreff='".strip_cr($_REQUEST[bbetreff])."', btext='".strip_cr($_REQUEST[btext])."', agbtext='".strip_cr($_REQUEST[agbtext])."', widerruf='".strip_cr($_REQUEST[widerruf])."', bthank='".strip_cr($_REQUEST[bthank])."' ");
}

$res = $db->query("select * from order_settings");
$row = $db->fetch_array($res);

?>

<span class="htitle">Bestellablauf</span><br>
<br>

<form action="module/order/bestellablauf.php?update=true" method="post">
<table width="600" border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>
<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
  <td colspan="2"><b>Einstellungen</b></td>
</tr>
<?/*
<tr class="tr">
  <td>Zusammenfassung der Bestellung ausgeben</td><?if($row[bzusammenfassung]=="Y") { $selected = " checked"; } else { $selected=""; } ?>
  <td><input type="checkbox" name="bzusammenfassung" value="Y" <?=$selected?>></td>
</tr>
*/?>
<tr class="tr">
  <td width="200">Schriftliche Verträge erstellen</td><?if($row[bvertrag]=="Y") { $selected = " checked"; } else { $selected=""; } ?>
  <td><input type="checkbox" name="bvertrag" value="Y" <?=$selected?>></td>
</tr>
<tr class="tr">
  <td>KK Formulare erstellen</td><?if($row[bkkvertrag]=="Y") { $selected = " checked"; } else { $selected=""; } ?>
  <td><input type="checkbox" name="bkkvertrag" value="Y" <?=$selected?>></td>
</tr>
<tr class="tr">
  <td>Bestellbestätigung per Mail</td><?if($row[bsendmail]=="Y") { $selected = " checked"; } else { $selected=""; } ?>
  <td><input type="checkbox" name="bsendmail" value="Y" <?=$selected?>></td>
</tr>
<tr class="tr">
    <td valign="top">Benachrichtigung über<br>neue Bestellungen an</td>
    <td><input type="text" name="bemail" value="<?=$row[bemail]?>" class="input-text"></td>
</tr>
<tr class="tr">
  <td>Absender Name</td>
  <td><input type="text" name="babsender" value="<?=$row[babsender]?>" class="input-text"></td>
</tr>
<tr class="tr">
  <td>Absender E-Mail</td>
  <td><input type="text" name="babsendermail" value="<?=$row[babsendermail]?>" class="input-text"></td>
</tr>
<tr class="tr">
  <td>Betreff</td>
  <td><input type="text" name="bbetreff" value="<?=$row[bbetreff]?>" class="input-text"></td>
</tr>
<tr class="tr">
  <td valign="top">Signatur</td>
  <td><textarea name="btext" style="width:390px; height:100px;"><?=$row[btext]?></textarea></td>
</tr>
<tr class="tr">
  <td valign="top">Ihre AGB</td>
  <td><textarea name="agbtext" style="width:390px; height:100px;"><?=$row[agbtext]?></textarea></td>
</tr>
<tr class="tr">
  <td valign="top">Widerrufsbelehrung</td>
  <td><textarea name="widerruf" style="width:390px; height:100px;"><?=$row[widerruf]?></textarea></td>
</tr>
<tr class="tr">
  <td valign="top">Bestellabschlusstext</td>
  <td><textarea name="bthank" style="width:390px; height:100px;"><?=$row[bthank]?></textarea></td>
</tr>
<tr class="tr">
  <td colspan="2"><input type="submit" value="Speichern"></td>
</tr>
</table>

</td>
</tr>
</table>
</form>

<br>


<?include("../../footer.php");?>
