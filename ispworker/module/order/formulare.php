<?
$module = basename(dirname(__FILE__));
include("../../header.php");



if($_REQUEST[update]=="true") {
    $db->query("update order_settings set formkk='".strip_cr($_REQUEST[formkk])."',formrechnung='".strip_cr($_REQUEST[formrechnung])."',
    formlastschrift='".strip_cr($_REQUEST[formlastschrift])."',formsonstzahl='".strip_cr($_REQUEST[formsonstzahl])."' ");
}

$res = $db->query("select * from order_settings");
$row = $db->fetch_array($res);

?>

<span class="htitle">Formulare</span><br>
<br>



<form action="module/order/formulare.php?update=true" method="post">
<table width="700" border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>
<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
  <td><b>KK Formular</b></td>
</tr>
<tr class="tr">
  <td><textarea name="formkk" style="width:690px; height:400px;"><?=$row[formkk]?></textarea></td>
</tr>
<tr class="th">
  <td><b>Bestellformular mit Zahlungsmethode Rechnung</b></td>
</tr>
<tr class="tr">
  <td><textarea name="formrechnung" style="width:690px; height:400px;"><?=$row[formrechnung]?></textarea></td>
</tr>
<tr class="th">
  <td><b>Bestellformular mit Zahlungsmethode Lastschrift</b></td>
</tr>
<tr class="tr">
  <td><textarea name="formlastschrift" style="width:690px; height:400px;"><?=$row[formlastschrift]?></textarea></td>
</tr>
<tr class="th">
  <td><b>Bestellformular Sonstige Zahlungsarten</b></td>
</tr>
<tr class="tr">
  <td><textarea name="formsonstzahl" style="width:690px; height:400px;"><?=$row[formsonstzahl]?></textarea></td>
</tr>


<tr class="tr">
  <td><input type="submit" value="Speichern"></td>
</tr>

</table>

</td>
</tr>
</table>
</form>
<br>
<br>


<?include("../../footer.php");?>