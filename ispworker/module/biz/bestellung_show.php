<?
$module = basename(dirname(__FILE__));
include("../../header.php");
?>

<span class="htitle">Bestellung</span><br>
<br>

<?
if($_REQUEST[updatekommentar]=="true") {
    $db->query("update biz_bestellungen set internerkommentar='$_REQUEST[internerkommentar]' where bestellid='$_REQUEST[bestellid]'");
}

$res = $db->query("select b.internerkommentar,b.bestellid, b.kundenid,b.produkte,b.datum,b.erledigt,b.kommentar, b.domains, 
		   k.vorname,k.nachname,k.mail from biz_bestellungen as b, biz_kunden as k
                   where b.adminid='$_SESSION[adminid]' and b.adminid=k.adminid and b.kundenid=k.kundenid and b.bestellid='$_REQUEST[bestellid]' order by datum");

$row = $db->fetch_array($res);

$domains = $row[domains];

$ointernerkommentar = $row[internerkommentar];

if($_REQUEST[merken]=="true") {
  $_SESSION['merkkunde'] = $row[kundenid];
}


$x = explode(";",$row[produkte]);
for($i=0;$i<count($x);$i++) {
  $produkte[] = $x[$i];
}


if($_REQUEST["savedomains"]==true)
{

    // ID des Standard Registrars ermitteln
    $res2 = $db->query("select dregid from biz_domainregistrare where standard='1'");
    $row2 = $db->fetch_array($res2);
    $dregid = $row2[dregid];

    if($dregid == "") { message("Fehler: Kein Standard Registrar definiert."); include("../../footer.php"); die(); }

    // Erstes Produkt in der Liste ist unser Pasis Paket
    // Ermittle Anzahl Inklusivdomains
    $y = explode(":",$produkte[0]);
    $res2 = $db->query("select anzdomains from order_artikel where artikelid='$y[1]'");
    $row2 = $db->fetch_array($res2);
    // 
    $d = explode(":",$domains);
    for($i=0;$i<count($d);$i++)
    {
	if($i <= ($row2[anzdomains]-1)) $inkl = "Y"; else $inkl = "N";
	$date = date("Y-m-d");
	if($d[$i]!="")
	{
	    $db->query("insert into biz_domains (kundenid,domainname,freigeschaltet,inklusiv,registrar) 
		        values ('$row[kundenid]','$d[$i]','$date','$inkl','$dregid')");
	}
    }
    message("Domains sind gespeichert.");
}


if($_REQUEST["executejob"]==true)
{
    include("../order/inc/functions.inc.php");
    $y = explode(":",$produkte[0]);
    $produktid = $y[1];
    
    $domarr = explode(":",$domains);

    order_execute_jobs("Y",$produktid,$domarr,$_REQUEST["jobid"]);
    message("Job ist ausgeführt.");
}

?>

<table border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>

<table width="600" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
  <td colspan="2"><b>Kundendaten</b></td>
</tr>

<tr class="tr">
  <td>KundenNr</b></td>
  <td><a href="module/biz/kunden_detail.php?kundenid=<?=$row[kundenid]?>"><?=$row[kundenid]?></a></b></td>
</tr>
<tr class="tr">
  <td>Vorname</b></td>
  <td><?=$row[vorname]?></b></td>
</tr>
<tr class="tr">
  <td>Nachname</b></td>
  <td><?=$row[nachname]?></b></td>
</tr>
<tr class="tr">
  <td>Mail</b></td>
  <td><a href="mailto:<?=$row["mail"]?>"><?=$row["mail"]?></a></b></td>
</tr>
</table>

</td>
</tr>
</table>

<br>
<br>

<table border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>

<table width="600" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
<td><b>Anzahl</b></td>
<td><b>ProduktID</b></td>
<td><b>Bezeichnung und Rechnungskommentar</b></td>
<td><b>Abrechnung</b></td>
<td><b>Preis in <?=$biz_settings["waehrung"]?></b></td>
<td><b>Summe in <?=$biz_settings["waehrung"]?></b></td>
</tr>
<?
for($pos=0;$pos<(count($produkte)-1);$pos++) {

  $x = explode(":",$produkte[$pos]);
  $res = $db->query("select bezeichnung,preis,abrechnung from biz_produkte where adminid='$_SESSION[adminid]' and produktid='$x[1]'");
  $row = $db->fetch_array($res);
?>
<tr class="tr">
<td valign="top"><?=$x[0]?></td>
<td valign="top"><?=$x[1]?></td>
<td valign="top"><?=$row[bezeichnung]?><br><font size="1"><?=nl2br($x[2])?></font></td>
<td valign="top" align="right">
<?
$abrechnung=explode(":",$row[abrechnung]);
if($abrechnung[0]=="indiv") echo "alle ".$abrechnung[1]." Monate";
else echo $row[abrechnung];
?>
</td>

<td valign="top" align="right"><?=$row[preis]?></td>
<?
if($_REQUEST[merken]=="true") $_SESSION['merkzettel'][] = "$x[0]:$x[1]:$x[2]";
$summe = $x[0] * $row[preis];
$summe = sprintf("%.2f",$summe);
$total = $total + $summe;
?>
<td valign="top" align="right"><?=$summe?></td>
</tr>
<?
}
$total = sprintf("%.2f",$total);
?>
<tr class="tr">
    <td colspan="5" align="right"><b>Summe Total:<b> </td>
    <td align="right"><?=$total?></td>
</tr>

</table>

</td>
</tr>
</table>

<br>
<form action="module/biz/bestellung_show.php?merken=true&bestellid=<?=$_REQUEST[bestellid]?>" method="post">
<input type="submit" value="Bestelldaten auf den Merkzettel notieren">
</form>
<br>
<?
if($domains!="") 
{
    html_caption("Domains");
    $d = explode(":",$domains);
    for($i=0;$i<count($d);$i++)
	echo "$d[$i]<br>\n";
?>
<form action="module/biz/bestellung_show.php?savedomains=true&bestellid=<?=$_REQUEST[bestellid]?>" method="post">
<input type="submit" value="Domains eintragen">
</form><br>
<?
}

# Kommentar des Kunden

if($row[kommentar]!="")
{
    html_caption("Kommentar des Kunden");
    echo "<PRE>".$row[kommentar]."</PRE>\n";
}
?>


<?

# Jobs

$x = explode(":",$produkte[0]);

$resj = $db->query("select * from order_jobs where jobproductid='".$x[1]."' and jobactivation='Y' ");
if($db->num_rows($resj) > 0) 
{
    html_caption("Jobs");
    while($job = $db->fetch_array($resj)) 
    {
	echo $job["jobname"]." - <a href=\"module/biz/bestellung_show.php?executejob=true&jobid=".$job["jobid"]."&bestellid=".$_REQUEST["bestellid"]."\">ausführen</a><br>\n";
    }
}
?>
<br>
<br>
<?


# Interner Kommentar

html_caption("Interner Kommentar");
?>
<form action="module/biz/bestellung_show.php?bestellid=<?=$_REQUEST[bestellid]?>&updatekommentar=true" method="post">
<textarea name="internerkommentar" style="width:600px; height:100px;"><?=$ointernerkommentar?></textarea><br> 
<input type="submit" value="Speichern">
</form>
<br>
<br>
<br>
<?include("../../footer.php");?>