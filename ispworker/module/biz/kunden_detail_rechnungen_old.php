<?
$module = basename(dirname(__FILE__));

if($_REQUEST[action] == "mahnen")
{
    $redirect = true;
    $string = $_REQUEST[ausw][0];
    for($i = 1;$i < count($_REQUEST[ausw]); $i++) $string .= ",".$_REQUEST[ausw][$i];
    $redirectlocation = "mahnung_neu.php?add=true&ausw=$string";
}


if($_REQUEST[paydinvoice] == "true" || $_REQUEST[action] == "setzebezahlt")
{
    $redirect = true;
    $string = $_REQUEST[ausw][0];
    for($i = 1;$i < count($_REQUEST[ausw]); $i++) $string .= ",".$_REQUEST[ausw][$i];
    if($_REQUEST[paydinvoice]=="true"){
    	$redirectlocation = "rechnungen_buchen.php?add=true&ausw=".$_REQUEST[rechnungid];
    }else{
    	$redirectlocation = "rechnungen_buchen.php?add=true&ausw=$string";
    }
}


include("./inc/functions.inc.php");
include("../../header.php");
include("./inc/reiter1.layout.php");

$bgcolor[0]   = "#f0f0f0";
$linecolor[0] = "#000000";

$bgcolor[1]   = "#ffffff";
$linecolor[1] = "#ffffff";
include("./inc/reiter1.php");

if(isset($_REQUEST['aktivieren']))
{
    $db->query("UPDATE biz_rechnungtodo SET `kuendigen_zum`='' WHERE `posid` =".$_REQUEST['posid']);
}

if($_REQUEST[delinvoice]=="true") {
  trash("biz_rechnungen","where adminid='$_SESSION[adminid]' and rechnungid='$_REQUEST[rechnungid]'");
}


if($_REQUEST[paydinvoice]=="true") {
  //$db->query("update biz_rechnungen set status='bezahlt' where adminid='$_SESSION[adminid]' and rechnungid='$_REQUEST[rechnungid]'");
}


if($_REQUEST[unpaydinvoice]=="true") {
  $db->query("update biz_rechnungen set status='unbezahlt' where adminid='$_SESSION[adminid]' and rechnungid='$_REQUEST[rechnungid]'");
}

if($_REQUEST[saveeditinvoice]=="true") {
  $db->query("update biz_rechnungen set positionen='$_REQUEST[positionen]' where kundenid='$_REQUEST[kundenid]' and rechnungid='$_REQUEST[rechnungid]'");
}

if($_REQUEST[delinvoicetodopos]=="true") {
    trash("biz_rechnungtodo","where posid='$_REQUEST[posid]'");
}

/*if($_REQUEST[action] == "setzebezahlt") {
    for($i = 0;$i < count($_REQUEST[ausw]); $i++) {
		$db->query("update biz_rechnungen set status='bezahlt' where adminid='$_SESSION[adminid]' and rechnungid='".$_REQUEST[ausw][$i]."' ");
    }
}*/

if($_REQUEST[action] == "setzeunbezahlt") {
    for($i = 0;$i < count($_REQUEST[ausw]); $i++) {
	$db->query("update biz_rechnungen set status='unbezahlt' where adminid='$_SESSION[adminid]' and rechnungid='".$_REQUEST[ausw][$i]."' ");
    }
}




?>

<table width="680" border="0" cellspacing="0" cellpadding="0">
<tr bgcolor="#cccccc">
<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr bgcolor="#e7e7e7">
  <td colspan="8"><b>Zuk&uuml;nftige Rechnungs Positionen</b></td>
</tr>
<tr>
  <td bgcolor="#ffffff"><b>Profil</b></td>
  <td bgcolor="#ffffff"><b>Anz</b></td>
  <td bgcolor="#ffffff"><b>Bezeichnung</b></td>
  <td bgcolor="#ffffff" width="50"><b>E-Preis</b></td>
  <td bgcolor="#ffffff"><b>Abrechnungsart</b></td>
  <td bgcolor="#ffffff"><b>Abrechnungszeitraum</b></td>
  <td width="16" bgcolor="#ffffff" colspan="2"><b>Aktion</b></td>
</tr>

<?
$res = $db->query("SELECT r.posid,r.beginnabrechnung,r.produktanzahl,r.kuendigen_zum,r.produktid,r.produktkommentar,r.profilid,p.bezeichnung,p.preis,p.abrechnung
                   FROM biz_rechnungtodo AS r, biz_produkte AS p
		   WHERE r.produktid=p.produktid AND r.kundenid='".$_REQUEST[kundenid]."'");

while($row = $db->fetch_array($res)) {
$ts = strtotime($row['beginnabrechnung']);

$array = calc_abrechnungszeitraum($ts,$row['abrechnung']);

$resprofil = $db->query("select profil from biz_profile where profilid='".$row['profilid']."'");
$rowprofil = $db->fetch_array($resprofil);

?>
<tr>
  <td valign="top" bgcolor="#ffffff"><?=$rowprofil[profil]?></td>
  <td valign="top" bgcolor="#ffffff"><?=$row[produktanzahl]?></td>
  <td valign="top" bgcolor="#ffffff">
  <?=$row['bezeichnung']?><br>
  <?
  if($row['produktkommentar']!="") { echo "<font size=\"1\">".$row['produktkommentar']."</font><br>"; }

  $resdd = $db->query("select * from biz_domains where rechtodoid='".$row['posid']."'");
  while($dd = $db->fetch_array($resdd)) {
    echo "<font size=\"1\">".$dd['domainname']."</font><br>";
  }
  $kuendigen = explode("-", $row['kuendigen_zum']);
    if(count($kuendigen) == 3)
    {
        $kuendigen = "<br>gek&uuml;ndigt zum: <span style='color: #cc0000;'>".$kuendigen[2].".".$kuendigen[1].".".$kuendigen[0]."</span>";
    }
    else
        $kuendigen = "";
  ?>
  </td>
  <td valign="top" bgcolor="#ffffff"><?=$row[preis]?></td>
  <td valign="top" bgcolor="#ffffff">
  <?
	$intervall=explode(":",$row[abrechnung]);
	if($intervall[0]=="indiv"){
		echo "alle ".$intervall[1]." Monate";
	}else{
		echo $row[abrechnung];
	}

  ?></td>
  <td valign="top" bgcolor="#ffffff"><?=$array[1]?><?=$kuendigen?></td>
  <td valign="top" bgcolor="#ffffff"><a href="module/biz/kunden_detail_rechnungen.php?kundenid=<?=$_REQUEST[kundenid]?>&posid=<?=$row[posid]?>&delinvoicetodopos=true" onclick="return confirm('M&ouml;chten Sie den Datensatz wirklich l&ouml;schen?');"><img src="img/trash.gif" border="0" alt="L&ouml;schen"></a></td>
  <?
  if($kuendigen == "")
  {
  ?>
    <td valign="top" bgcolor="#ffffff"><a href="module/biz/kunden_kuendigen.php?kundenid=<?=$_REQUEST[kundenid]?>&posid=<?=$row['posid']?>&kuendigen=true">kue</a></td>
  <?
  }
  else
  {
  ?>
    <td valign="top" bgcolor="#ffffff"><a href="module/biz/kunden_detail_rechnungen.php?kundenid=<?=$_REQUEST[kundenid]?>&posid=<?=$row['posid']?>&aktivieren=true"onclick="return confirm('M&ouml;chten Sie die K&uuml;ndigung f&uuml;r das Produkt wirklich aufheben?');">akt</a></td>
  <?
  }
  ?>
</tr>
<?}?>
</table>

</td>
</tr>
</table>

<br>
<br>

<?
if($_REQUEST[editinvoice]=="true") {

$res = $db->query("select * from biz_rechnungen where rechnungid='$_REQUEST[rechnungid]'");
$row = $db->fetch_array($res);
?>

<form action="module/biz/kunden_detail_rechnungen.php?kundenid=<?=$_REQUEST[kundenid]?>&saveeditinvoice=true&rechnungid=<?=$_REQUEST[rechnungid]?>" method="post">

<table width="680" border="0" cellspacing="0" cellpadding="0">
<tr bgcolor="#cccccc">
<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr bgcolor="#e7e7e7">
  <td><b>Rechnung bearbeiten</b></td>
</tr>
<tr>
  <td bgcolor="#ffffff" align="center">
  <textarea name="positionen" cols="75" rows="10" wrap=off>
  <?=$row[positionen]?>
  </textarea>
  </td>
</tr>
<tr>
  <td bgcolor="#ffffff"><input type="submit" value="Speichern"></td>
</tr>
</table>

</td>
</tr>
</table>

</form>

<br>
<br>
<?
}
?>


<form action="module/biz/kunden_detail_rechnungen.php?kundenid=<?=$_REQUEST[kundenid]?>" method="post">
<table width="680" border="0" cellspacing="0" cellpadding="0">
<tr bgcolor="#cccccc">
<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr bgcolor="#e7e7e7">
  <td colspan="8"><b>Rechnungen</b></td>
</tr>
<tr bgcolor="#ffffff">
  <td width="20"><img src="img/pixel.gif" width="1" height="1"></td>
  <td width="45"><b>Re-Nr</b></td>
  <td width="70"><b>Datum</b></td>
  <td><b>Positionen</b></td>
  <td width="70"><b>Status</b></td>
  <td colspan="3" width="48"><b>Aktion</b></td>
</tr>


<?
//Für Sortierung aus SQL-Statement order by entfernt:
$res = $db->query("select p.idprefix, p.idsuffix, r.rechnungid,r.datum,r.profilid,r.positionen,r.status from biz_rechnungen as r, biz_profile as p where r.profilid=p.profilid and r.adminid='$_SESSION[adminid]' and r.kundenid='$_REQUEST[kundenid]' order by r.rechnungid DESC");

while($row=$db->fetch_array($res)) {


$pos = explode("<br>",$row[positionen]);
for($i=0;$i<count($pos);$i++) {
  $entry  = explode("|",$pos[$i]);
  if($entry[0]!="") {
    $artikel_anz[] .= $entry[0];
    $artikel_bez[] .= $entry[1]."<br>".$entry[3]."<br>";
    $artikel_pre[] .= $entry[2];
    $entry[0] = sprintf("%.2f",$entry[0]);
    $summe = $summe + ($entry[2] * $entry[0]);
  }
}


?>
<tr>
  <td bgcolor="#ffffff" valign="top"><input type="checkbox" name="ausw[]" value="<?=$row[rechnungid]?>"></td>
  <td bgcolor="#ffffff" valign="top"><?=$row[idprefix]?><?=$row[rechnungid]?><?=$row[idsuffix]?></td>
  <td bgcolor="#ffffff" valign="top"><?=$row[datum]?></td>
  <td bgcolor="#ffffff">

  <table border="0" width="100%">
  <tr>
    <td><b>Anz</b></td>
    <td><b>Bezeichnung</b></td>
    <td><b>Preis</b></td>
  </tr>
  <?for($j=0;$j<count($artikel_anz);$j++) {
  ?>
  <tr>
    <td valign="top"><?=$artikel_anz[$j]?></td>
    <td valign="top"><?=$artikel_bez[$j]?></td>
    <td valign="top"><?=$artikel_pre[$j]?></td>
  </tr>
  <?
  }
  ?>
  </table>
  <?
  $summe = sprintf("%.2f",$summe);
  ?>
  <b>Summe:</b> <?=$summe?>

  </td>
  <td bgcolor="#ffffff" valign="top"><font size="1">
  <?
    $style = "";
    if($row['status'] == "gemahnt")
        $style = " style='color: red'";
    elseif($row['status'] == "bezahlt")
        $style = " style='color: green'";
    elseif($row['status'] == "unbezahlt")
        $style = " style='color: orange'";
    echo "<span".$style.">".$row['status']."</span>";
  ?>
    </font><br>
  <?
  if($row[status]=="unbezahlt") {
  ?>
    <font size="1"><a href="module/biz/kunden_detail_rechnungen.php?kundenid=<?=$_REQUEST[kundenid]?>&paydinvoice=true&rechnungid=<?=$row[rechnungid]?>">Bezahlt</a></font><br>
  <?
  } else {
  ?>
    <font size="1"><a href="module/biz/kunden_detail_rechnungen.php?kundenid=<?=$_REQUEST[kundenid]?>&unpaydinvoice=true&rechnungid=<?=$row[rechnungid]?>">Unbezahlt</a></font><br>
  <?
  }
  ?>
  <font size="1"><a href="module/biz/mahnung_neu.php?add=true&ausw=<?=$row[rechnungid]?>">Mahnen</a></font><br>


</td>
<td width="16" bgcolor="#ffffff" valign="top"><a href="module/biz/rechnung_show.php?rechnungid=<?=$row[rechnungid]?>"><img src="img/pdf.gif" border="0"></a>
<td width="16" bgcolor="#ffffff" valign="top"><a href="module/biz/kunden_detail_rechnungen.php?kundenid=<?=$_REQUEST[kundenid]?>&editinvoice=true&rechnungid=<?=$row[rechnungid]?>"><img src="img/edit.gif" border="0" alt="Bearbeiten"></a>
<td width="16" bgcolor="#ffffff" valign="top"><a href="module/biz/kunden_detail_rechnungen.php?kundenid=<?=$_REQUEST[kundenid]?>&delinvoice=true&rechnungid=<?=$row[rechnungid]?>" onclick="return confirm('Möchten Sie den Datensatz wirklich löschen?');"><img src="img/trash.gif" border="0" alt="Löschen"></a>



</tr>
<?
$artikel_anz = "";
$artikel_bez = "";
$artikel_pre = "";
$summe = "";
}
?>


</table>

</td>
</tr>
</table>
<br>
<select name="action">
    <option value="mahnen">Mahnen</option>
    <option value="setzebezahlt">Bezahlt</option>
    <option value="setzeunbezahlt">Unbezahlt</option>
</select> <input type="submit" value="Senden">
</form>

<br>
<br>
<br>




<?include("../../footer.php");?>
