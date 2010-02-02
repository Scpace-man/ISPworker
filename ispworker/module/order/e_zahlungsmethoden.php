<?
$module = basename(dirname(__FILE__));
include("../../header.php");


if($_REQUEST[save]=="true")
{
    for($i=0;$i<count($_REQUEST['rechnunglaender']);$i++)
    {
	   $id = $_REQUEST['rechnunglaender'][$i]; 
	   $zahlung[$id] = ";rechnung";
    }
    
    for($i=0;$i<count($_REQUEST['vorkasselaender']);$i++)
    {
	   $id = $_REQUEST['vorkasselaender'][$i]; 
	   $zahlung[$id] .= ";vorkasse";
    }

    for($i=0;$i<count($_REQUEST['lastschriftlaender']);$i++)
    {
	   $id = $_REQUEST['lastschriftlaender'][$i]; 
	   $zahlung[$id] .= ";lastschrift";
    }
    

    for($i=0;$i<count($_REQUEST['paypallaender']);$i++)
    {
	   $id = $_REQUEST['paypallaender'][$i]; 
	   $zahlung[$id] .= ";paypal";
    }
    

    
    $db->query("update order_laender set zahlungsmethoden=''");

    while (list ($key, $val) = each ($zahlung)) {
	$db->query("update order_laender set zahlungsmethoden='$val' where laenderid = '$key'");
    }    

}
?>



<span class="htitle">Zahlungsmethoden</span><br>
<br>

<form action="module/order/e_zahlungsmethoden.php?save=true" method="post">
<table width="540" border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
  <td colspan="2"><b>Zahlungsmethoden</b></td>
</tr>
<tr class="tr">
  <td valign="top">Rechnung</td>
  <td valign="top">
  <select name="rechnunglaender[]" size="5" multiple>
  <? 
  $res = $db->query("select * from order_laender"); 
  while($row=$db->fetch_array($res)) {
    if(strstr($row["zahlungsmethoden"],"rechnung")) {
	$checked = " selected";
    }			
    else {
	$checked = "";
    }		  

  ?>
  <option value="<?=$row[laenderid]?>" <?=$checked?>><?=$row[name]?></option>
  <?}?>
  </select>
  </td>
</tr>
<tr class="tr">
  <td valign="top">Vorkasse</td>
  <td valign="top">
  <select name="vorkasselaender[]" size="5" multiple>
  <? 
  $res = $db->query("select * from order_laender"); 
  while($row=$db->fetch_array($res)) {
    if(strstr($row["zahlungsmethoden"],"vorkasse")) {
	$checked = " selected";
    }			
    else {
	$checked = "";
    }		  

  ?>
  <option value="<?=$row[laenderid]?>" <?=$checked?>><?=$row[name]?></option>
  <?}?>
  </select>
  </td>
</tr>
<tr class="tr">
  <td valign="top">Lastschrift</td>
  <td valign="top">
  <select name="lastschriftlaender[]" size="5" multiple>
<? 
  $res = $db->query("select * from order_laender"); 
  while($row=$db->fetch_array($res)) {

    if(strstr($row["zahlungsmethoden"],"lastschrift")) {
	$checked = " selected";
    }			
    else {
	$checked = "";
    }		  
  ?>
  <option value="<?=$row[laenderid]?>" <?=$checked?>><?=$row[name]?></option>
  <?}?>
  </select>
  </td>
</tr>
<tr class="tr">
  <td valign="top">Paypal</td>
  <td valign="top">
  <select name="paypallaender[]" size="5" multiple>
  <? 
  $res = $db->query("select * from order_laender"); 
  while($row=$db->fetch_array($res)) {
    if(strstr($row["zahlungsmethoden"],"paypal")) {
	$checked = " selected";
    }			
    else {
	$checked = "";
    }		  
  ?>
  <option value="<?=$row[laenderid]?>" <?=$checked?>><?=$row[name]?></option>
  <?}?>
  </select>
  </td>
</tr>
<tr class="tr">
  <td colspan="2" valign="top"><input type="submit" value="Speichern"></td>
</tr>
</table>

</td>
</tr>
</table>
</form>

<br>

<br>


<?include("../../footer.php");?>