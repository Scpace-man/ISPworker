<?
$module = basename(dirname(__FILE__));
include("../../header.php");

#if($deleteall==true) {
#  $_SESSION['merkzettel'] = "";
#  $_SESSION['merkkunde']  = "";
#}


if($_REQUEST['delete']=="true" || $_REQUEST['clearall']=="true") {
	if($_REQUEST['pos']=="all"){
		$newarray=array();
		unset($_SESSION['merkkunde']);		
	}else{
	  for($i=0;$i<count($_SESSION['merkzettel']);$i++) {
		  if($_REQUEST['pos']!=$i) {
    		  $newarray[] = $_SESSION['merkzettel'][$i];
		    }
	   }
	 }
  
	  $_SESSION['merkzettel'] = $newarray;
}

if($_REQUEST[update]=="true") {
  for($i=0;$i<count($_REQUEST[positionen]);$i++) {
    if($_REQUEST[positionen][$i]!=0) {
      $x = explode(":",$_SESSION['merkzettel'][$i]);
      $newarray[] = $_REQUEST[positionen][$i].":".$x[1];
    }
  }
  $_SESSION['merkzettel'] = $newarray;
}

?>

<span class="htitle">Merkzettel</span><br>
<br>


<?

$k   = $_SESSION['merkkunde'];
$res = $db->query("select kundenid, vorname, nachname, firma from biz_kunden where adminid='$_SESSION[adminid]' and kundenid='$k'");
$row = $db->fetch_array($res);

if(count($_SESSION['merkkunde'])!=0) { 

	echo 'Aktuell gemerkter Kunde: <a href="module/biz/kunden_detail.php?kundenid='.$row[kundenid].'">'.$row[vorname].' '.$row[nachname].'</a> '.$row[firma].'<br>';
}
?>

<br>
<br>



<?
#if($_SESSION['merkzettel']=="") {
#    echo "<br>Der Merkzettel ist leer.";
#    include("../../footer.php");
#    die();
#}


if(count($_SESSION['merkzettel'])!=0) {?>

<form action="module/biz/merkzettel.php?update=true" method="post">

<table border="0" cellspacing="0" cellpadding="0">
<tr bgcolor="#cccccc">
<td>

<table width="600" border="0" cellspacing="1" cellpadding="3">
<tr bgcolor="#e7e7e7" align="left" valign="top">
<td><b>Anzahl</b></td>
<td><b>ProduktID</b></td>
<td><b>Bezeichnung</b></td>
<td><b>Preis</b></td>
<td><b>Summe</b></td>
<td colspan="2" width="16"><b>Aktion</b></td>

<?

for($pos=0;$pos<count($_SESSION['merkzettel']);$pos++) {

  $x = explode(":",$_SESSION['merkzettel'][$pos]);
  $res = $db->query("select bezeichnung,preis from biz_produkte where adminid='$_SESSION[adminid]' and produktid='$x[1]'");
  $row = $db->fetch_array($res);

  $currencySQL = $db->query("select waehrung from biz_settings");
  $currency = $db->fetch_array($currencySQL);
  
?>
</tr>
<tr bgcolor="#FFFFFF" align="left" valign="top">
<td><input type="text" size="2" name="positionen[<?=$pos?>]" value="<?=$x[0]?>"></td>
<td><?=$x[1]?></td>
<td><?=$row[bezeichnung]?></td>

<td align="right"><?=$row[preis]?> <?=$currency['waehrung']?></td>
<?
 $summe = $x[0] * $row[preis];
 $summe = sprintf("%.2f",$summe);
 $total = $total + $summe;
?>
<td align="right"><?=$summe?> <?=$currency['waehrung']?></td>
<td width="16" align="center"><a href="module/biz/merkzettel.php?delete=true&pos=<?=$pos?>"><img src="img/trash.gif" border="0"></a> </td>
</tr>
<?
$posges+=$pos;
}
?>
</table>

</td>
</tr>
</table>
<?
 $total = sprintf("%.2f",$total);
?>
<br>
<b>Summe Total:</b> <?=$total?> <?=$currency['waehrung']?><br>
<br>
<table>
<tr>
<td>
<input type="submit" value="Aktualisieren"></td>
</form>
</tr>
</table>
<?} else { echo "Es sind keine Produkte auf dem Merkzettel notiert."; }?>
<br>
<br>

<form action="module/biz/merkzettel.php?delete=true&pos=all" method="post">
<input type="hidden" name="clearall" value="true">
<input type="submit" name="submit" value="Merkzettel leeren">
</form>



<br>
<br>





<?include("../../footer.php");?>