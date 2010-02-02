<?
$module = basename(dirname(__FILE__));
include("../../header.php");


if($_REQUEST[delfast]=="true") {
    $db->query("delete from biz_bestellungen where bestellid='$_REQUEST[bestellid]'");
}

if($_REQUEST['do']=="true") {
    if($_REQUEST['todo']=="delete") {
		for($i=0;$i<count($_REQUEST[ausw]);$i++) {
		    trash("biz_bestellungen","where bestellid='".$_REQUEST[ausw][$i]."'");
		}
    }

    if(strstr($_REQUEST['todo'],"status")) {
	$x = explode(":",$_REQUEST['todo']);

	for($i=0;$i<count($_REQUEST[ausw]);$i++) {
	    $db->query("update biz_bestellungen set statusid='$x[1]' where bestellid='".$_REQUEST[ausw][$i]."'");
	}
	
    }
}


?>

<span class="htitle">Bestellungen</span><br>
<br>



<?
$res = $db->query("select b.bestellid, b.kundenid,b.datum,b.erledigt,b.statusid, k.vorname,k.nachname from biz_bestellungen as b, biz_kunden as k 
                   where b.adminid='$_SESSION[adminid]' and b.adminid=k.adminid and b.kundenid=k.kundenid order by datum DESC");

if($db->num_rows($res)==0) {
    echo "Es liegen keine Bestellungen vor.";
    include("../../footer.php");
    die();
}


?>


<form action="module/biz/bestellungen.php" method="post">
<input type="hidden" name="do" value="true">
<table border="0" cellspacing="0" cellpadding="0">
<tr bgcolor="#cccccc">
<td>

<table width="600" border="0" cellspacing="1" cellpadding="3">
<tr bgcolor="#e7e7e7" align="left" valign="top">
<td width="16"><img src="img/pixel.gif" width="1" height="1"></td>
<td width="19"><b>Status</b></td>
<td><b>BestellNr</b></td>
<td><b>Kunde</b></td>
<td><b>Datum</b></td>
<td><b>Del</b></td>
</tr>
<?

$res = $db->query("select b.bestellid, b.kundenid,b.datum,b.erledigt,b.statusid, k.vorname,k.nachname from biz_bestellungen as b, biz_kunden as k 
                   where b.adminid='$_SESSION[adminid]' and b.adminid=k.adminid and b.kundenid=k.kundenid order by datum DESC");
while($row = $db->fetch_array($res)) {
?>

<tr bgcolor="#FFFFFF" align="left" valign="top">
<td width="16"><input type="checkbox" name="ausw[]" value="<?=$row[bestellid]?>"></td>
<td align="center"><?
$ress = $db->query("select status, statusimg from order_statusbestell where statusid='$row[statusid]'");
$rows = $db->fetch_array($ress);
?><img src="<?=$rows[statusimg]?>" alt="<?=$rows[status]?>" border="0"></td>
<td><a href="module/biz/bestellung_show.php?bestellid=<?=$row[bestellid]?>"><?=$row[bestellid]?></a></td>
<td><a href="module/biz/kunden_detail.php?kundenid=<?=$row[kundenid]?>"><?=$row[nachname]?> <?=$row[vorname]?></a></td>
<td><?

$timestamp = strtotime($row[datum]); // 
$datum = date("d.m.Y H:i:s",$timestamp);
echo $datum;
?></td>
<td width="16"><a href="module/biz/bestellungen.php?delfast=true&bestellid=<?=$row[bestellid]?>"><img src="img/trash.gif" border="0"></a></td>
</tr>
<?
}
?>
</table>

</td>
</tr>
</table>
<br>
<select name="todo">
<option value="delete">Löschen</option>
<?$res = $db->query("select status,statusid from order_statusbestell");
while($row = $db->fetch_array($res)) {
    echo "<option value=\"status:$row[statusid]\">Status auf \"$row[status]\" setzen</option>";
}
?>

</select> <input type="submit" value="Senden">

</form>

<br>










<?include("../../footer.php");?>