<?
$module = basename(dirname(__FILE__));
include("../../header.php");

if($_REQUEST[update]=="true") {
    while (list($key, $val) = each($_REQUEST[preis])) {
	$db->query("update order_tld set preis='$val' where tld='$key'");
    }

    while (list($key, $val) = each($_REQUEST[aufpreis])) {
	$db->query("update order_tld set aufpreis='$val' where tld='$key'");
    }
}
	    
if($_REQUEST[updatekat]=="true") {
    $db->query("update order_settings set domainkatid='$_REQUEST[katid]'");
}


$ress = $db->query("select * from order_settings");
$rows=$db->fetch_array($ress);

$katid = $rows[domainkatid];

	$currencySQL = $db->query("select waehrung from biz_settings");
	$currency=$db->fetch_array($currencySQL);

?>

<span class="htitle">Domain Preise in <?=$currency['waehrung']?></span><br>
<br>


<form action="module/order/tldpreise.php?updatekat=true" method="post">
<table width="540" border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
  <td><b>Domainpreise befinden sich in der Kategorie...</b></td>
</tr>
<tr class="tr">
<td>
<select name="katid">
<?
$res = $db->query("select domainkatid from order_settings");
$row = $db->fetch_array($res);
$d   = $row[domainkatid];
$res = $db->query("select * from biz_produktkategorien");
while($row=$db->fetch_array($res)) {
  
    if($d==$row[katid]) { $selected = " selected"; }
    else {
	$selected = "";
    }
    ?>
    <option value="<?=$row[katid]?>" <?=$selected?>><?=$row[bezeichnung]?></option>
<?
}
?>
</select>
<input type="submit" value="OK">
</td>
</tr>
</table>
				    
</td>
</tr>
</table>
</form>
		    
<br>
					    


<form action="module/order/tldpreise.php?update=true" method="post">
<table width="540" border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
  <td colspan="2"><b>Preis für Domain in <?=$currency['waehrung']?></b></td>
</tr>
<?
$res = $db->query("select * from order_tld order by tld");
while($row=$db->fetch_array($res)) {
?>
<tr class="tr">
  <td><?=$row[tld]?></td>
  <td>
  <select name="preis[<?=$row[tld]?>]">
  <?
  $resp = $db->query("select * from biz_produkte where katid='$katid'");
  
  while($rowp=$db->fetch_array($resp)) {

	  echo $rowp[produktid]."pid____".$rowp[bezeichnung];
  
    if($row[preis]==$rowp[produktid]) { $selected = " selected"; }
    else { $selected = ""; }
    echo "<option value=\"$rowp[produktid]\" $selected>$rowp[bezeichnung]</option>";
  }
  ?>
  </select>
  </td>
</tr>
<?}?>
<tr class="th">
  <td colspan="2"><b>Aufpreis für Domain in <?=$currency['waehrung']?></b></td>
</tr>
<?
$res = $db->query("select * from order_tld order by tld");
while($row=$db->fetch_array($res)) {

?>
<tr class="tr">
  <td><?=$row[tld]?></td>
  <td>
  <select name="aufpreis[<?=$row[tld]?>]">
  <?
  $resp = $db->query("select * from biz_produkte where katid='$katid'");
  while($rowp=$db->fetch_array($resp)) {
    if($row[aufpreis]==$rowp[produktid]) { $selected = " selected"; }
    else { $selected = ""; }

    echo "<option value=\"$rowp[produktid]\" $selected>$rowp[bezeichnung]</option>";
  }
  ?>
  </select>
  </td>
</tr>
<?}?>
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