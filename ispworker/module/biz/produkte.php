<?
$module = basename(dirname(__FILE__));
include("../../header.php");


switch($_REQUEST[action])
{
    case "produktmerken":
	$_SESSION['merkzettel'][] = "1:$_REQUEST[produktid]";
	message("Produkt ist auf dem Merkzettel notiert.");
    break;

    case "produktloeschen":
	trash("biz_produkte","where adminid='$_SESSION[adminid]' and produktid='$_REQUEST[produktid]'");
	trash("order_artikel","where artikelid='$_REQUEST[produktid]'");
    break;

    case "kategorieloeschen": Kategorie_loeschen($_REQUEST[katid],$_SESSION[adminid],$_REQUEST[entf]);
	break;
}


function Kategorie_loeschen($katid,$adminid,$entf){
	global $db;
	$res = $db->query("select katid, bezeichnung, produktid from biz_produkte where adminid='$_SESSION[adminid]' AND katid='$katid'");
	$row=$db->fetch_array($res);

	if(mysql_affected_rows()>0){
		if($entf=='n'){
			message("Die Produktkategorie ist nicht leer! Möchten Sie diese trotzdem löschen? (Alle Produkte in dieser Kategorie werden gelöscht!)","error");
			echo '<a href="module/biz/produkte.php?action=kategorieloeschen&entf=t&katid='.$katid.'">Ja</a>&nbsp;&nbsp;&nbsp;&nbsp;';
			echo '<a href="module/biz/produkte.php">Nein</a><br><br>';
		}else{
			trash("biz_produkte","where adminid='$_SESSION[adminid]' and katid='$katid'");
			trash("biz_produktkategorien","where adminid='$_SESSION[adminid]' and katid='$_REQUEST[katid]'");
		}
	}else{
		trash("biz_produkte","where adminid='$_SESSION[adminid]' and katid='$_REQUEST[katid]'");
		trash("biz_produktkategorien","where adminid='$_SESSION[adminid]' and katid='$_REQUEST[katid]'");
	}
}


?>



<span class="htitle">Produkte</span><br>
<br>


&raquo; <a href="module/biz/produkt_neu.php">Produkt hinzufügen</a><br>
&raquo; <a href="module/biz/produkt_kategorie_neu.php">Produktkategorie hinzufügen</a><br>

<br>

<?html_caption("Kategorien");?>

<table width="600" border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
<td width="25"><b>KatID</b></td>
<td><b>Bezeichnung</b></td>
<td colspan="3"><b>Aktion</b></td>

<?
$res = $db->query("select katid,bezeichnung,kommentar from biz_produktkategorien where adminid='$_SESSION[adminid]' order by bezeichnung");
while($row=$db->fetch_array($res)) {
?>
</tr>
<tr class="tr">
<td width="50"><?=$row[katid]?></td>
<td><a href="module/biz/produkte.php?katid=<?=$row[katid]?>&categorydesc=<?echo urlencode($row[bezeichnung]);?>"><?=stripslashes($row[bezeichnung])?></a></td>
<td width="16"><a href="module/biz/produkt_kategorie_editieren.php?katid=<?=$row[katid]?>"><img alt="Bearbeiten" src="img/edit.gif" border="0"></a></td>
<td width="16"><a href="module/biz/produkte.php?action=kategorieloeschen&entf=n&katid=<?=$row[katid]?>"><img alt="Löschen" src="img/trash.gif" border="0"></a></td>
</tr>
<?
//onclick="return confirm('Möchten Sie den Datensatz wirklich löschen?');"
}

$currencySQL = $db->query("select waehrung from biz_settings");
$currency=$db->fetch_array($currencySQL);

?>
</table>

</td>
</tr>
</table>

<br>

<?
if($_REQUEST["katid"]!="") {
html_caption("Produkte der Kategorie ".$_REQUEST["categorydesc"]);
?>

<table width="600" border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
<td width="50"><b>ProdID</b></td>
<td><b>Bezeichnung</b></td>
<td><b>Abrechnung</b></td>
<td><b>Preis in <?=$currency['waehrung']?></b></td>
<td colspan="4"><b>Aktion</b></td>

<?
if(!isset($anzahl)) { $anzahl = 90; }
if(!isset($start))  { $start  = 0; }
if(isset($_REQUEST[katid])) {

$res = $db->query("select abrechnung,produktid,bezeichnung,preis from biz_produkte where adminid='$adminid' and katid='$_REQUEST[katid]' order by produktid limit $start,$anzahl");
while($row=$db->fetch_array($res)) {
?>
</tr>
<tr class="tr">
<td><?=$row[produktid]?></td>
<td><?=stripslashes($row[bezeichnung])?></td>
<td><?
$getAbrech=explode(":",$row[abrechnung]);
if($getAbrech[0]=="indiv"){
	echo $getAbrech[1]." Monate";
}else{
	echo $row[abrechnung];
}
?></td>
<td align="right"><?=$row[preis]?></td>
<td width="16"><a href="module/biz/produkte.php?action=produktmerken&produktid=<?=$row[produktid]?>&katid=<?=$_REQUEST[katid]?>"><img alt="Merken" src="img/merken.gif" border="0"></a></td>
<td width="16"><a href="module/biz/produkt_editieren.php?produktid=<?=$row[produktid]?>&katid=<?=$_REQUEST[katid]?>"><img alt="Bearbeiten" src="img/edit.gif" border="0"></a></td>
<td width="16"><a href="module/biz/produkte.php?action=produktloeschen&produktid=<?=$row[produktid]?>&katid=<?=$_REQUEST[katid]?>" onclick="return confirm('Möchten Sie den Datensatz wirklich löschen?');"><img alt="Löschen" src="img/trash.gif" border="0"></a></td>
</tr>
<?
}
}
?>
</table>

</td>
</tr>
</table>

<?}?>

<br>
<br>







<?include("../../footer.php");?>