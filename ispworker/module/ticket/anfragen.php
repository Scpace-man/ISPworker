<?
$module = basename(dirname(__FILE__));
include("../../header.php");
include("./inc/functions.inc.php");

$time = time();

if($_REQUEST[aktion]=="del") {
	

	for($i=0;$i<count($_REQUEST[ticketids]);$i++) {
		$db->query("delete from ticket_anfragen where ticketid='".$_REQUEST[ticketids][$i]."'");
		$db->query("delete from ticket_anfragen where ticketid='".$_REQUEST[ticketids][$i]."'");
	}
}

switch($_REQUEST["typ"]) {

    case "offen":
	$title = "Offene Anfragen"; 
	$e = "where (status='Offen' or status='Halten') and userid=''"; 
    break;
    
    case "gespeichert":
	$title = "Gespeicherte Anfragen";
	$e = "where (status='Offen' or status='Halten') and userid='$_SESSION[adminid]'";	
    break;

    case "erledigt": 
	$title = "Erledigte Anfragen"; 
	$e = "where status='Erledigt'";
    break;

    case "suche": 
	$title = "Suchergebnisse"; 
	$e = "where ".$_REQUEST[feld]." like '%".$_REQUEST[q]."%'";
    break;

    default:
	$title = "Offene Anfragen"; 
	$e = "where (status='Offen' or status='Halten') and userid=''";     
    break;
}
?>

<span class="htitle"><?=$title?></span><br>
<br>

<form action="module/ticket/anfragen.php?ticketid=<?=$_REQUEST[ticketid]?>" method="post">
<table width="95%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td bgcolor="#cccccc">

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr bgcolor="#e7e7e7">
  <td width="1%"><b></b></td>
  <td width="5%"><b>ID</b></td>
  <td><b>Betreff</b></td>
  <td><b>Absender</b></td>
  <td><b>Abteilung</b></td>
  <td><b>Status</b></td>
  <td><b>Offen seit</b></td>
  <td><b>Datum</b></td>  
</tr>
<?


$res = $db->query("select * from ticket_anfrageantworten order by eingegangen DESC");
$row = $db->fetch_array($res);

$lastticket = $row[ticketid];


$res = $db->query("select * from ticket_anfragen $e order by eingegangen");
while($row=$db->fetch_array($res)) {

    
    $rest = $db->query("select eingegangen from ticket_anfrageantworten where ticketid='$row[ticketid]' order by eingegangen DESC");
    $rowt = $db->fetch_array($rest);
    
    if($rowt[eingegangen]=="") $ein = $row[eingegangen]; else $ein = $rowt[eingegangen];
    $tdiff = $time - $ein;


    $string = zeitumrechnen($tdiff);
    if($row[betreff]=="") { $row[betreff] = "- Kein Betreff -"; }
    if($row[ticketid]==$lastticket) $row[betreff] = "<b>$row[betreff]</b>";
?>
<tr bgcolor="#FFFFFF" align="left" valign="middle">
<td width="25" valign="middle"><input type="checkbox" name="ticketids[]" value="<?=$row[ticketid]?>"></td>
<td width="60" valign="middle"><?=$row[ticketid]?></td>
<?$betreff = substr($row[betreff],0,30)."...";?>
<td valign="middle"><a href="module/ticket/anfrage_detail.php?ticketid=<?=$row[ticketid]?>"><?=$betreff?></a></td>

<?
$resm = $db->query("select * from biz_kunden where mail='$row[frommail]'");
$rowm = $db->fetch_array($resm);
if($rowm[nachname]!="") { $absender = "<a href=\"module/biz/kunden_detail.php?kundenid=$rowm[kundenid]\">$rowm[nachname], $rowm[vorname]</a>"; }
else { $absender = "$row[frommail]"; }

?>


<td valign="middle"><?=$absender?></td>



<?
$resa = $db->query("select * from ticket_abteilungen where mail='$row[tomail]'");
$rowa = $db->fetch_array($resa);
?>
<td valign="middle"><?=$rowa[bezeichnung]?></td>
<td valign="middle"><?=$row[status]?></td>
<td valign="middle"><?=$string?></td>
<td valign="middle"><?=date("d.m.Y",$row[eingegangen]);?></td>
</td>
</tr>
<?
}
?>
</table>

</td>
</tr>
</table>
<br>
<select name="aktion"><option value="del">Anfragen löschen</option></select> <input type="submit" value="Speichern">

</form>













<?include("../../footer.php");?>