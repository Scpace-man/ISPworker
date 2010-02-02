<?
$module = basename(dirname(__FILE__));
include("./inc/functions.inc.php");
include("../../header.php");

?>

<span class="htitle">Rechnungslauf</span><br>
<br>



&nbsp; &raquo; <a href="module/biz/cronwork.php" target="newc">Rechnungslauf abarbeiten / Rechnungen verschicken</a>
<br>
<br>
<?
if($_REQUEST[edit]=="true") {
?>
<form action="module/biz/rechnungslauf.php?editsave=true&posid=<?=$_REQUEST[posid]?>" method="post">
<table width="800" border="0" cellspacing="0" cellpadding="0">
<tr bgcolor="#cccccc" align="left" valign="top">
<td>
  <table width="100%" border="0" cellspacing="1" cellpadding="3">
  <tr bgcolor="#e7e7e7" align="left" valign="top">
    <td colspan="2"><b>Rechnungsposition editieren</b></td>
  </tr>
  <?
  $res = $db->query("select * from biz_rechnungtodo where posid='$_REQUEST[posid]'");
  $row = $db->fetch_array($res);
  ?>
  <tr>
    <td bgcolor="#ffffff">ProduktID</td>
    <td bgcolor="#ffffff"><input type="text" name="produktid" value="<?=$row[produktid]?>"></td>
  </tr>
  <tr>
    <td bgcolor="#ffffff">Produkt Anzahl</td>
    <td bgcolor="#ffffff"><input type="text" name="produktanzahl" size="4" value="<?=$row[produktanzahl]?>"></td>
  </tr>
  <tr>
    <td bgcolor="#ffffff">Beginn Abrechnung</td>
    <td bgcolor="#ffffff"><input type="text" name="beginnabrechnung" value="<?=$row[beginnabrechnung]?>"></td>
  </tr>
  <tr>
    <td bgcolor="#ffffff">Rechnungs Kommentar</td>
    <td bgcolor="#ffffff"><textarea name="kommentar" cols="100" rows="6"><?=$row[kommentar]?></textarea></td>
  </tr>
  <tr>
    <td bgcolor="#ffffff">Produkt Kommentar</td>
    <td bgcolor="#ffffff"><textarea name="produktkommentar" cols="100" rows="6"><?=$row[produktkommentar]?></textarea></td>
  </tr>  
  
  </table>
</td>
</tr>
</table>
<br>
<input type="submit" value="Speichern">
</form>
<br>
<hr size="1" noshade>
<form action="module/biz/rechnungslauf.php?editdelete=true&posid=<?=$_REQUEST[posid]?>" method="post">
<input type="submit" value="Loeschen">
</form>
<?


include("../../footer.php");
die();
}


if($_REQUEST[editsave]=="true") {
  $db->query("update biz_rechnungtodo set produktid='$_REQUEST[produktid]',produktanzahl='$_REQUEST[produktanzahl]',
	      beginnabrechnung='$_REQUEST[beginnabrechnung]', kommentar='$_REQUEST[kommentar]', produktkommentar='$_REQUEST[produktkommentar]' where posid='$_REQUEST[posid]'");
}


if($_REQUEST[editdelete]=="true") {
  trash("biz_rechnungtodo","where posid='$_REQUEST[posid]'");
}

	$currencySQL = $db->query("select waehrung from biz_settings");
	$currency=$db->fetch_array($currencySQL);
?>

<table width="800" border="0" cellspacing="0" cellpadding="0">
<tr bgcolor="#cccccc" align="left" valign="top">
<td>


<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr bgcolor="#e7e7e7">
  <td colspan="7"><b>Zukünftige Rechnungspositionen</b></td>
</tr>
<tr>
  <td bgcolor="#ffffff"><b>Kunde</b></td>
  <td bgcolor="#ffffff"><b>Anz</b></td>
  <td bgcolor="#ffffff"><b>Bezeichnung</b></td>
  <td bgcolor="#ffffff"><b>Betrag<br> in <?=$currency['waehrung']?></b></td>
  <td bgcolor="#ffffff"><b>Abrechnungsart</b></td>
  <td bgcolor="#ffffff"><b>Abrechnungszeitraum</b></td>
  <td bgcolor="#ffffff"><b>Aktion</b></td>

</tr>

	      <?
	      $res = $db->query("select r.posid,k.vorname,k.nachname,r.kundenid,r.posid,r.beginnabrechnung,r.produktanzahl,r.produktid,
	    			 r.profilid,r.kommentar,p.bezeichnung,p.preis,p.abrechnung
	                         from biz_rechnungtodo as r, biz_produkte as p, biz_kunden as k
		                 where r.produktid=p.produktid
				 and r.adminid='$_SESSION[adminid]' and r.kundenid=k.kundenid order by r.beginnabrechnung,r.kundenid");

while($row = $db->fetch_array($res)) {

  $ts = strtotime($row[beginnabrechnung]);

  $array = calc_abrechnungszeitraum($ts,$row[abrechnung]);
?>
<tr>
  <td bgcolor="#ffffff"><a href="module/biz/kunden_detail.php?kundenid=<?=$row[kundenid]?>"><?=$row[nachname]?> <?=$row[vorname]?></a></td>
  <td bgcolor="#ffffff"><?=$row[produktanzahl]?></td>
  <td bgcolor="#ffffff"><?=$row[bezeichnung]?></td>
  <td bgcolor="#ffffff" valign="right"><?=$row[preis]?></td>
  <td bgcolor="#ffffff"><?=$row[abrechnung]?></td>
  <td bgcolor="#ffffff"><?=$array[1]?></td>
  <td width="16" bgcolor="#ffffff"><a href="module/biz/rechnungslauf.php?edit=true&posid=<?=$row[posid]?>"><img alt="Bearbeiten" src="img/edit.gif" border="0"</a></td>
</tr>
<?}?>
</table>

</td>
</tr>
</table>
<br>
<br>

<?include("../../footer.php");?>
