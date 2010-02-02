<?
$module = basename(dirname(__FILE__));
include("../../header.php");



if($_REQUEST[update]=="true") {

    $res = $db->query("select artikelid from order_artikel where artikelid='$_REQUEST[produktid]'");
    if($db->num_rows($res)==0) {
	$db->query("insert into order_artikel (artikelid,katid) values ('$_REQUEST[produktid]','$_REQUEST[katid]')");
    }

    $tlds = $_REQUEST["tlds"];

	if($_REQUEST[tld_bestellbar]==true){
		$tld_bestellbar="Y";
	}else{
		$tld_bestellbar="N";
	}


    $t = "|";
    for($i=0;$i<count($tlds);$i++) {
	$t .= "$tlds[$i]|";
    }

    $tldsmitaufpreis = $_REQUEST["tldsmitaufpreis"];

    $t2 = "|";
    for($i=0;$i<count($tldsmitaufpreis);$i++) {
	$t2 .= "$tldsmitaufpreis[$i]|";
    }

    $db->query("update order_artikel set produkteinid='$_REQUEST[produkteinid]', kurztext='$_REQUEST[kurztext]', langtext='$_REQUEST[langtext]', kkaktiv='$_REQUEST[kkaktiv]',
                anzdomains='$_REQUEST[anzdomains]', minanzdomains='$_REQUEST[minanzdomains]', tlds='$t', tldsmitaufpreis='$t2', tld_bestellbar='$tld_bestellbar' where artikelid='$_REQUEST[produktid]'");

}


if($_REQUEST["new"]=="true") 
{
    $reskat = $db->query("select katid from biz_produkte where produktid='$_REQUEST[produktid]'");
    $rowkat = $db->fetch_array($reskat);
    $db->query("insert into order_artikel (artikelid,katid,produkteinid) values ('$_REQUEST[produktid]','$rowkat[katid]','$_REQUEST[produkteinid]')");
}

if($_REQUEST[del]=="true") {
    trash("order_artikel","where artikelid='$_REQUEST[artikelid]'");
}

// clear calculator trash
if($_REQUEST[clear]=="true") {

    $res = $db->query("select * from biz_produkte,order_artikel where biz_produkte.produktid=order_artikel.artikelid");
    while($row = $db->fetch_array($res)) {

	if(strstr($row[kurztext],"time:")) {
	    $rt = $db->query("select posid from biz_rechnungtodo where produktid='$row[produktid]'");
	    if($db->num_rows($rt)==0) {
	        $db->query("delete from biz_produkte where produktid='$row[produktid]'");
	        $db->query("delete from order_artikel where artikelid='$row[produktid]'");
	    }
	}
    }
}

	$currencySQL = $db->query("select waehrung from biz_settings");
	$currency=$db->fetch_array($currencySQL);
?>

<span class="htitle">Artikel für den Bestellprozess verwalten</span><br>
<br>


<br>

<?html_caption("Neues Paket");?>


Wählen Sie ein Produkt aus, um es für den Bestellprozess freizuschalten.<br>
<br>

<form action="module/order/artikel.php?new=true" method="post">
<table border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>

<table width="600" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
  <td><b>Paket</b></td>
</tr>
<tr class="tr">
  <td>
  <select name="produktid">
  <?
  $res = $db->query("select p.produktid, p.preis, p.bezeichnung as produktbezeichnung, k.bezeichnung as kategoriebezeichnung
		     from biz_produkte p, biz_produktkategorien k where p.katid=k.katid and p.sichtbar='1' order by k.bezeichnung");
  
  while($row = $db->fetch_array($res)) 
    echo "<option value=\"".$row[produktid]."\">[".$row["kategoriebezeichnung"]."] ".$row["produktbezeichnung"]." - ".$row["preis"]." ".$currency["waehrung"]."</option>\n";
  ?>
  </select>
  </td>
</tr>
<tr class="th">
  <td><b>Einrichtungsgebühr (optional)</b></td>
</tr>
<tr class="tr">
  <td>
  <select name="produkteinid">
  <option value="">--</option>
  <?
  $res = $db->query("select p.produktid, p.preis, p.bezeichnung as produktbezeichnung, k.bezeichnung as kategoriebezeichnung
		     from biz_produkte p, biz_produktkategorien k where p.katid=k.katid and p.sichtbar='1' order by k.bezeichnung");
  
  while($row = $db->fetch_array($res)) echo "<option value=\"".$row[produktid]."\">[".$row["kategoriebezeichnung"]."] ".$row["produktbezeichnung"]." - ".$row["preis"]."</option>\n";
  ?>
  </select>
  </td>
</tr>
<tr class="tr">
  <td><input type="submit" value="Auswählen"></td>
</tr>
</table>

</td>
</tr>
</table>
</form>

<br>
<br>

<?html_caption("Vorhandene Pakete");?>

<table border="0" cellspacing="0" cellpadding="0">
<tr>
<td class="tb">

<table width="600" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
  <td><b>Paketname</b></td>
  <td><b>Preis in <?=$currency['waehrung']?></b></td>
  <td colspan="2"><b>Aktion</b></td>
</tr>
<?

$res = $db->query("select * from biz_produkte,order_artikel where biz_produkte.produktid=order_artikel.artikelid");
while($row = $db->fetch_array($res)) {

?>
<tr class="tr">
  <td><?=$row[bezeichnung]?></td>
  <td><?=$row[preis]?></td>
  <td width="16"><a href="module/order/artikel.php?produktid=<?=$row[produktid]?>"><img src="img/edit.gif" border="0" alt="Bearbeiten"></a></td>
  <td width="16"><a href="module/order/artikel.php?del=true&artikelid=<?=$row[produktid]?>"><img src="img/trash.gif" border="0" alt="Löschen"></a></td>
</tr>
<?}?>

</table>

</td>
</tr>
</table>



<br>
<br>
<?



$res = $db->query("select * from biz_produkte, order_artikel where biz_produkte.produktid=order_artikel.artikelid and biz_produkte.produktid='$_REQUEST[produktid]'");
$row = $db->fetch_array($res);

if($row[produktid]!="") {
?>

<form action="module/order/artikel.php?update=true&produktid=<?=$row[produktid]?>" method="post">
<table border="0" cellspacing="0" cellpadding="0">
<tr>
<td class="tb">

<table width="600" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
  <td colspan="2"><b>Allgemein</b></td>
</tr>
<tr class="tr">
  <td width="150">ArtikelNr</td>
  <td><?=$_REQUEST[produktid]?></td>
</tr>
<tr class="tr">
  <td>Bezeichnung</td>
  <td><?=$row[bezeichnung]?></td>
</tr>
<tr class="tr">
  <td>Preis in <?=$currency['waehrung']?></td>
  <td><?=$row[preis]?></td>
</tr>
<tr class="tr">
  <td>Kategorie</td>
  <td>
  <?
  $resk = $db->query("select k.bezeichnung from biz_produktkategorien k, biz_produkte p where p.katid=k.katid and p.produktid='$row[artikelid]'");
  $rowk = $db->fetch_array($resk);
  echo $rowk["bezeichnung"];
  ?>
  </td>
</tr>
<tr class="tr">
  <td>Abrechnung</td>
  <td><?=$row[abrechnung]?></td>
</tr>
<tr class="tr">
  <td>Bearbeiten</td>
  <td><a href="module/biz/produkt_editieren.php?produktid=<?=$row[produktid]?>&katid=<?=$row[katid]?>">Produktdaten bearbeiten</a></td>
</tr>
<?
$rese = $db->query("select * from biz_produkte where produktid='$row[produkteinid]'");
$rowe  = $db->fetch_array($rese);

?>
<tr class="tr">
  <td>Einrichtung ArtNr</td>
  <td><input type="text" name="produkteinid" value="<?=$row[produkteinid]?>" size="2"> leer, wenn keine Einrichtungsgebühr</td>
</tr>

<?
if($db->num_rows($rese)==1) {
?>
<tr class="tr">
  <td>Bezeichnung Einrichtung</td>
  <td><?=$rowe[bezeichnung]?></td>
</tr>
<tr class="tr">
  <td>Preis Einrichtung in <?=$currency['waehrung']?></td>
  <td><?=$rowe[preis]?></td>
</tr>
<tr class="tr">
  <td>Bearbeiten</td>
  <td><a href="module/biz/produkt_editieren.php?produktid=<?=$rowe[produktid]?>&katid=<?=$rowe[katid]?>">Produktdaten bearbeiten</a></td>
</tr>
<?}?>
<tr class="tr">
  <td valign="top">Kurze Beschreibung</td>
  <td><textarea name="kurztext" style="width: 440px; height:100px;"><?=$row[kurztext]?></textarea></td>
</tr>
<tr class="tr">
  <td valign="top">Ausführliche Beschreibung</td>
  <td><textarea name="langtext" style="width: 440px; height:100px;"><?=$row[langtext]?></textarea></td>
</tr>
<tr class="th">
  <td colspan="2"><b>Domains</b></td>
</tr>
<tr class="tr">
  <td>KK möglich</td><?if($row[kkaktiv]=="Y") { $checked = "checked"; } else { $checked=""; } ?>
  <td><input type="checkbox" name="kkaktiv" value="Y" <?=$checked?>></td>
</tr>
<tr class="tr">
  <td>Anzahl Inklusiv-Domains</td>
  <td><input type="text" name="anzdomains" size="2" value="<?=$row[anzdomains]?>"></td>
</tr>
<tr class="tr">
  <td valign="top">Dabei bestellbar</td>
  <td>
   <select name="tlds[]" size="5" multiple>
     <?
     $rest = $db->query("select * from order_tld");
       while($rowt=$db->fetch_array($rest)) {

       if(strstr($row[tlds],"|$rowt[tldid]|")) {
	    $selected = " selected";
       } else {
    	    $selected = "";
       }
     ?>
       <option value="<?=$rowt[tldid]?>" <?=$selected?>><?=$rowt[tld]?></option>
     <?}?>
    </select>
  </td>
</tr>
<tr class="tr">
  <td valign="top">Aufpreise für</td>
  <td>
   <select name="tldsmitaufpreis[]" size="5" multiple>
     <?
     $rest = $db->query("select * from order_tld");
       while($rowt=$db->fetch_array($rest)) {

       if(strstr($row[tldsmitaufpreis],"|$rowt[tldid]|")) {
	    $selected = " selected";
       } else {
    	    $selected = "";
       }
     ?>
       <option value="<?=$rowt[tldid]?>" <?=$selected?>><?=$rowt[tld]?></option>
     <?}?>
    </select>
  </td>
</tr>
<tr class="tr">
  <td>Mindestanzahl Domains</td>
  <td><input type="text" name="minanzdomains" size="2" value="<?=$row[minanzdomains]?>"></td>
</tr>
<tr class="tr">
  <td>Domains bestellbar</td>
  <td><input type="checkbox" name="tld_bestellbar" <? if($row[tld_bestellbar]=='Y') echo 'checked'?> >
  </td>
</tr>
<tr class="tr">
  <td colspan="2"><input type="submit" value="Speichern"></td>
</tr>
</table>

</td>
</tr>
</table>
</form>



<?}?>
<br>
<br>


<?include("../../footer.php");?>