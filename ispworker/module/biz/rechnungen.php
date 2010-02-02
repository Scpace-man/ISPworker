<?
$clean=true;
$module = basename(dirname(__FILE__));
include("../../header.php");
?>

<span class="htitle">Rechnungen</span><br>
<br>

<?
$u = explode("biz-",$_SESSION['user']);

$res = $db->query("select rechnungid from biz_rechnungen where adminid='$_SESSION[adminid]'");
if($db->num_rows($res)==0) { echo "Derzeit sind keine Rechnungen vorhanden.<br><br>"; include("../../footer.php"); die(); }


if($_REQUEST[action]=="delete") trash("biz_rechnungen","where rechnungid='".$_REQUEST[rechnungid]."' ");
if($_REQUEST[action]=="deleteselect") {
    for($i=0;$i<count($_REQUEST[ausw]);$i++) {
	trash("biz_rechnungen","where rechnungid='".$_REQUEST[ausw][$i]."' ");
    }
}
		


if($_REQUEST[anzahl] != "") $_SESSION[anzahlds] = $_REQUEST[anzahl];
if($_SESSION[anzahlds]=="")   $_SESSION[anzahlds]  = 150;

if(!isset($_REQUEST[anzahl]))  { $_REQUEST[anzahl] = 150; }
if(!isset($_REQUEST[start]))   { $_REQUEST[start]  = 0; }
if(!isset($_REQUEST[ordnung])) { $_REQUEST[ordnung]  = "r.rechnungid"; }
if(!isset($_REQUEST['sort']))    { $_REQUEST['sort']     = "DESC"; }


?>

<table border="0" cellpadding="0" cellspacing="0">
<tr>
    <td colspan="2"><span class="small">Suche</span></td>
    <td colspan="2"><span class="small">Datensätze</span></td>
</tr>
<tr>
    <form action="module/biz/rechnungen.php?ordnung=<?=$_REQUEST[ordnung]?>" method="post">
    <td>
	  <input type="text" name="q"><input type="submit" value="Suchen"></td>
    </td>
	</form>
    <td width="15"><img src="img/pixel.gif" width="1" height="1" border="0"></td>
	<form action="module/biz/rechnungen.php?ordnung=<?=$_REQUEST[ordnung]?>&start=<?=$_REQUEST[start]?>" method="post">
    <td>
	  <input type="text" name="anzahl" size="5" value="<?=$_SESSION[anzahlds]?>"> <input type="submit" value="Anzeigen">
    </td>
    </form>
    <td width="15"><img src="img/pixel.gif" width="1" height="1" border="0"></td>
</tr>
</table>


<br>
<br>
<form action="module/biz/rechnungen.php" method="post">
<table border="0" cellpadding="0" cellspacing="0">
<tr>
	<td valign="top">Rechnungen ReNr <input type="text" name="ri" size="3"> bis ReNr <input type="text" name="rj" size="3"> im PDF Format ausgeben.
	<input type="submit" value="Generieren"></td>
</tr>
<tr>
	<td valign="top"><input type="checkbox" id="label_dr" name="druckrechnungen"><label for="label_dr"> nur Druckrechnungen anzeigen </label></td>
</tr>
</table>
</form>

<?
if($_REQUEST[ri]!="") 
{
	// Wenn die Checkbox "druckrechnungen" markiert ist
    if($_REQUEST[druckrechnungen] == "on") $_REQUEST[dr] = "true";
    else $_REQUEST[dr] = "false";
    
    // Link zusammensetzen
    echo "<a href=\"module/biz/rechnung_show.php?multipdf=true&ri=$_REQUEST[ri]&rj=$_REQUEST[rj]&dr=$_REQUEST[dr]\" target=\"_blank\"><b>Sammel-PDF Datei öffnen</b></a><br><br>";
}
?>

<br>
<br>

<form action="module/biz/rechnungen.php" method="post">

<table border="0" cellspacing="0" cellpadding="5">
<tr>
<td valign="top" width="650">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>

<table width="650" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
<td width="16"><img src="img/pixel.gif" width="1" height="1"></td>
<td width="20"><a href="module/biz/rechnungen.php?ordnung=r.rechnungid&q=<?=$_REQUEST[q]?>&start=<?=$_REQUEST[start]?>&sort=<?if($_REQUEST['sort'] == "DESC") echo "ASC"; else echo "DESC";?>" class="tf">ReNr</a></td>
<td><a href="module/biz/rechnungen.php?ordnung=r.datum&q=<?=$_REQUEST[q]?>&start=<?=$_REQUEST[start]?>&sort=<?if($_REQUEST['sort'] == "DESC") echo "ASC"; else echo "DESC";?>" class="tf">Datum</a></td>
<td><a href="module/biz/rechnungen.php?ordnung=k.nachname&q=<?=$_REQUEST[q]?>&start=<?=$_REQUEST[start]?>&sort=<?if($_REQUEST['sort'] == "DESC") echo "ASC"; else echo "DESC";?>" class="tf">Kunde</a></td>
<td><a href="module/biz/rechnungen.php?ordnung=k.firma&q=<?=$_REQUEST[q]?>&start=<?=$_REQUEST[start]?>&sort=<?if($_REQUEST['sort'] == "DESC") echo "ASC"; else echo "DESC";?>" class="tf">Firma</a></td>
<td><a href="module/biz/rechnungen.php?ordnung=r.status&q=<?=$_REQUEST[q]?>&start=<?=$_REQUEST[start]?>&sort=<?if($_REQUEST['sort'] == "DESC") echo "ASC"; else echo "DESC";?>" class="tf">Status</a></td>
<td colspan="2"><b>Aktion</b></td>

<?


if($_REQUEST[q]!="") {
	$search = "and k.firma='$_REQUEST[q]' or k.nachname='$_REQUEST[q]'";
	//or r.positionen like '%$suche%'";
}

$res = $db->query("select *, k.firma as kfirma from biz_rechnungen as r, biz_kunden as k, biz_profile as p where r.adminid='$_SESSION[adminid]' $search and r.kundenid=k.kundenid and r.profilid=p.profilid order by $_REQUEST[ordnung] ".$_REQUEST['sort']);;
$n = $db->num_rows($res);

$res = $db->query("select *, k.firma as kfirma from biz_rechnungen as r, biz_kunden as k, biz_profile as p where r.adminid='$_SESSION[adminid]' $search and r.kundenid=k.kundenid and r.profilid=p.profilid order by $_REQUEST[ordnung] ".$_REQUEST['sort']." limit $_REQUEST[start],$_SESSION[anzahlds]");;


while($row=$db->fetch_array($res)) {

?>
</tr>
<tr class="tr">
<td width="16" ><input type="checkbox" name="ausw[]" value="<?=$row[rechnungid]?>"></td>
<td><?=$row[idprefix]?><?=$row[rechnungid]?><?=$row[idsuffix]?></td>
<?
$t = strtotime($row[datum]);
$datum = date("d.m.Y",$t);
?>

<td><?=$datum?></td>
<td><a href="module/biz/kunden_detail.php?kundenid=<?=$row[kundenid]?>"><?=$row[nachname]?>, <?=$row[vorname]?></a></td>
<td><a href="module/biz/kunden_detail.php?kundenid=<?=$row[kundenid]?>"><?=$row[kfirma]?></a></td>
<td>
<?
    $style = "";
    if($row['status'] == "gemahnt")
        $style = " style='color: red'";
    elseif($row['status'] == "bezahlt")
        $style = " style='color: green'";
    elseif($row['status'] == "unbezahlt")
        $style = " style='color: orange'";
    echo "<span".$style.">".$row['status']."</span>";
  ?></td>
<td width="16"><a href="module/biz/rechnung_show.php?rechnungid=<?=$row[rechnungid]?>" target="_blank"><img alt="Öffnen" src="img/pdf.gif" border="0"></a></td>
<td width="16"><a href="module/biz/rechnungen.php?action=delete&rechnungid=<?=$row[rechnungid]?>" onclick="return confirm('Möchten Sie den Datensatz wirklich löschen?');"><img alt="Löschen" src="img/trash.gif" border="0"></a></td>
</tr>
<?
}
?>
</table>

</td>
</tr>
</table>


<br>
<select name="action"><option value="deleteselect">Löschen</option></select> <input type="submit" value="Abschicken"> 
<br>
<br>
</td>
</tr>
</table>

<br>
<br>

<table width="600">
<tr>
<td>
<center>
<?for($i = 1; $i <= round($n / $_SESSION[anzahlds]); $i++) { $s = (($i-1) * $_SESSION[anzahlds]); ?>
<a href="module/biz/rechnungen.php?q=<?=$_REQUEST[q]?>&ordnung=<?=$_REQUEST[ordnung]?>&start=<?=$s?>"><?=$i?></a> <img src="img/pixel.gif" width="10" height="1">
<?}?>
</center>
</td>
</tr>
</table>

<br>
<br>







<?include("../../footer.php");?>
