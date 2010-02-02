<?
$module = basename(dirname(__FILE__));
include("../../header.php");


if($_REQUEST[update]=="true" & $_REQUEST[tldid]!="") {
    $db->query("update order_tld set tld='$_REQUEST[tld]', pos='$_REQUEST[pos]',idnaktiv='$_REQUEST[idnaktiv]',kkaktiv='$_REQUEST[kkaktiv]', 
		whoisserver='$_REQUEST[whoisserver]' ,wortfrei='$_REQUEST[wortfrei]', wortvergeben='$_REQUEST[wortvergeben]', wortinvalid='$_REQUEST[wortinvalid]', minlen='$_REQUEST[minlen]',
		maxlen='$_REQUEST[maxlen]', minbuch='$_REQUEST[minbuch]', ereg='$_REQUEST[ereg]' where tldid='$_REQUEST[tldid]'");
}
    
if($_REQUEST[update]=="true" & $_REQUEST[tldid]=="") {
    $db->query("insert into order_tld (tld,pos,idnaktiv,kkaktiv,whoisserver,wortfrei,wortvergeben,wortinvalid,minlen,maxlen,minbuch,ereg) 
		values ('$_REQUEST[tld]','$_REQUEST[pos]','$_REQUEST[idnaktiv]','$_REQUEST[kkaktiv]','$_REQUEST[whoisserver]','$_REQUEST[wortfrei]','$_REQUEST[wortvergeben]','$_REQUEST[wortinvalid]','$_REQUEST[minlen]','$_REQUEST[maxlen]','$_REQUEST[minbuch]','$_REQUEST[ereg]')");
}
	
if($_REQUEST[del]=="true") {
    trash("order_tld","where tldid='$_REQUEST[tldid]'");
}
?>

<span class="htitle">Top-Level-Domains</span><br>
<br>

<?html_caption("Vorhandene Top-Level-Domains");?>

<table width="600" border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
  <td width="40"><b>Pos</b></td>
  <td><b>TLD</b></td>
  <td colspan="2"><b>Aktion</b></td>
</tr>
<?
$res = $db->query("select * from order_tld order by pos ASC");
while($row=$db->fetch_array($res)) {

?>
<tr class="tr">
  <td><?=$row["pos"]?></td>
  <td><?=$row["tld"]?></td>
  <td width="16"><a href="module/order/e_tld.php?edit=true&tldid=<?=$row[tldid]?>"><img src="img/edit.gif" border="0" alt="Bearbeiten"></a></td>
  <td width="16"><a href="module/order/e_tld.php?del=true&tldid=<?=$row[tldid]?>"><img src="img/trash.gif" border="0" alt="Löschen"></a></td>
</tr>
<?
}
?>
</table>


</td>
</tr>
</table>

<br>

<?
if($_REQUEST[edit] == "true") {
    $res = $db->query("select * from order_tld where tldid='$_REQUEST[tldid]'");
    $row = $db->fetch_array($res);
}

?>


<?html_caption("Neue Top-Level-Domain / Top-Level-Domain editieren");?>

<form action="module/order/e_tld.php?tldid=<?=$row[tldid]?>&update=true&edit=true" method="post">
<table width="600" border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>
<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
  <td colspan="2"><b>Allgemeine Angaben</b></td>
</tr>
<tr class="tr">
  <td>Domain:</td>
  <td><input type="text" name="tld" value="<?=$row[tld]?>" class="input-text"> z.B. .de</td>
</tr>
<tr class="tr">
  <td>IDN Unterstützung:</td><?if($row[idnaktiv]=="Y") { $checked = "checked"; } else { $checked = ""; }?>
  <td><input type="checkbox" name="idnaktiv" value="Y" <?=$checked?>></td>
</tr>
<tr class="tr">
  <td>KK Anträge möglich:</td><?if($row[kkaktiv]=="Y") { $checked = "checked"; } else { $checked = ""; }?>
  <td><input type="checkbox" name="kkaktiv" value="Y" <?=$checked?>></td>
</tr>
<tr class="tr">
  <td>Position in Liste</td>
  <td><input type="text" name="pos" value="<?=$row["pos"]?>" size="3"></td>
</tr>
<tr class="th">
  <td colspan="2"><b>Whois Abfrage</b></td>
</tr>
<tr class="tr">
  <td>Whois Server</td>
  <td><input type="text" name="whoisserver" value="<?=$row[whoisserver]?>" class="input-text"></td>
</tr>
<tr class="tr">
  <td>Domain frei, wenn Wort</td>
  <td><input type="text" name="wortfrei" value="<?=$row[wortfrei]?>" class="input-text"> gefunden.</td>
</tr>
<tr class="tr">
  <td>Domain vergeben, wenn Wort</td>
  <td><input type="text" name="wortvergeben" value="<?=$row[wortvergeben]?>" class="input-text"> gefunden.</td>
</tr>
<tr class="tr">
  <td>Domain ungültig, wenn Wort</td>
  <td><input type="text" name="wortinvalid" value="<?=$row[wortinvalid]?>" class="input-text"> gefunden.</td>
</tr>
<tr class="th">
  <td colspan="2"><b>Korrektheit</b></td>
</tr>
<tr class="tr">
  <td>Minimale Länge der Domain</td>
  <td><input type="text" name="minlen" size="3"  value="<?=$row[minlen]?>"></td>
</tr>
<tr class="tr">
  <td>Maximale Länge der Domain</td>
  <td><input type="text" name="maxlen" size="3"  value="<?=$row[maxlen]?>"></td>
</tr>
<tr class="tr">
  <td>Mindestanzahl Buchstaben</td>
  <td><input type="text" name="minbuch" size="3"  value="<?=$row[minbuch]?>"></td>
</tr>
<tr class="tr">
  <td>Ereg Ausdruck für Domaincheck</td>
  <td><input type="text" name="ereg"  value="<?=$row[ereg]?>" class="input-text"></td>
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