<?
$module = basename(dirname(__FILE__));
include("../../header.php");
?>




<span class="htitle">Individuelle Produkte</span><br>
<br>



<a href="module/biz/angebot_neu.php">Neues individuelles Produkt</a>
<br>

<?
if($_REQUEST[del]=="true") {
	echo "<br><br><a href=\"module/biz/angebote.php?delnow=true&produktid=$_REQUEST[produktid]\"><b>* Ja, Individuelles Produkt und Rechnungslauf Eintrag löschen *</b></a>";
	echo "<br><br>";
}


if($_REQUEST[delnow]=="true") {
	$db->query("delete from biz_produkte where produktid='$_REQUEST[produktid]'");
	$db->query("delete from biz_rechnungtodo where produktid='$_REQUEST[produktid]'");
}

	$currencySQL = $db->query("select waehrung from biz_settings");
	$currency=$db->fetch_array($currencySQL);

?>

<br>



<table width="600" border="0" cellspacing="0" cellpadding="0">
<tr>
<td bgcolor="#cccccc">

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr bgcolor="#e7e7e7">
<td><b>Bezeichnung</b></td>
<td><b>Preis in <?=$currency['waehrung']?></b></td>
<td><b>Kunde</b></td>
<td colspan="2"><b>Aktion</b></td>
</tr>
<?

$res = $db->query("select indivkundenid, produktid,bezeichnung,preis from biz_produkte where adminid='$_SESSION[adminid]' and indivkundenid!='NULL' order by produktid");
while($row=$db->fetch_array($res)) {
?>
<tr bgcolor="#FFFFFF" align="left" valign="top">
<td><?=$row[bezeichnung]?></td>
<td><?=$row[preis]?></td>
<?
$resk = $db->query("select vorname,nachname from biz_kunden where kundenid='$row[indivkundenid]'");
$rowk = $db->fetch_array($resk);
?>
<td><a href="module/biz/kunden_detail.php?kundenid=<?=$row[indivkundenid]?>"><?=$rowk[nachname]?>, <?=$rowk[vorname]?></a></td>
<td width="16"><a href="module/biz/produkt_editieren.php?produktid=<?=$row[produktid]?>"><img alt="Bearbeiten" src="img/edit.gif" border="0"></a></td>
<td width="16"><a href="module/biz/angebote.php?del=true&produktid=<?=$row[produktid]?>"><img alt="Löschen" src="img/trash.gif" border="0"></a>
</td>
</tr>
<?
}
?>
</table>

</td>
</tr>
</table>














<?include("../../footer.php");?>