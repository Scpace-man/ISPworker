<?
$module = basename(dirname(__FILE__));
include("../../header.php");
include("../biz/inc/functions.inc.php");



$res = $db->query("select produktid,beginnabrechnung from biz_rechnungtodo where kundenid='$_SESSION[user]' order by beginnabrechnung DESC");
if($db->num_rows($res)==0) {
	echo "Sie haben derzeit keine Pakete gebucht.";

    include("../../footer.php");
    die();
}

$currencySQL = $db->query("select waehrung from biz_settings");
$currency=$db->fetch_array($currencySQL);
?>

<table width="700" border="0" cellspacing="0" cellpadding="0">
<tr bgcolor="#cccccc">
<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr bgcolor="#e7e7e7">
  <td colspan="5"><b>Zuk&uuml;nftige Rechnungs Positionen</b></td>
</tr>
<tr>
  <td bgcolor="#ffffff"><b>Anz</b></td>
  <td bgcolor="#ffffff"><b>Bezeichnung</b></td>
  <td bgcolor="#ffffff" width="80"><b>Preis in <?=$currency[waehrung]?></b></td>
  <td bgcolor="#ffffff"><b>Abrechnungsart</b></td>
  <td bgcolor="#ffffff"><b>Abrechnungszeitraum</b></td>
</tr>

<?
$res = $db->query("SELECT r.posid,r.beginnabrechnung,r.produktanzahl,r.kuendigen_zum,r.produktid,r.produktkommentar,r.profilid,p.bezeichnung,p.preis,p.abrechnung FROM biz_rechnungtodo AS r, biz_produkte AS p WHERE r.produktid=p.produktid AND r.kundenid='".$_SESSION[user]."'");
while($row = $db->fetch_array($res)) {
$ts = strtotime($row['beginnabrechnung']);

$array = calc_abrechnungszeitraum($ts,$row['abrechnung']);

$resprofil = $db->query("select profil from biz_profile where profilid='".$row['profilid']."'");
$rowprofil = $db->fetch_array($resprofil);

?>
<tr>
  <td valign="top" bgcolor="#ffffff"><?=$row[produktanzahl]?></td>
  <td valign="top" bgcolor="#ffffff"><?=stripslashes($row['bezeichnung'])?><br>
  <?
  if($row['produktkommentar']!="") { echo "<font size=\"1\">".$row['produktkommentar']."</font><br>"; }
  
  $resdd = $db->query("select * from biz_domains where rechtodoid='".$row['posid']."'");
  while($dd = $db->fetch_array($resdd)) {
    echo "<font size=\"1\">".$dd['domainname']."</font><br>";
  }
  ?>
  </td>
  <td valign="top" bgcolor="#ffffff"><?=$row[preis]?></td>
  <td valign="top" bgcolor="#ffffff"><?
	$getAbrech=explode(":",$row[abrechnung]);
	if($getAbrech[0]=="indiv"){
		echo $getAbrech[1]." Monate";
	}else{
		echo $row[abrechnung];
	}
  ?> </td>
  <td valign="top" bgcolor="#ffffff"><?=$array[1]?></td>
 <? } ?>
</tr>
</table>

</td>
</tr>
</table>

<br>
<br>

<?include("../../footer.php");?>
