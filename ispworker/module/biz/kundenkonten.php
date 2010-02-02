<?
$module = basename(dirname(__FILE__));
include("../../header.php");
?>

<i><b>Konten & Buchungen</b></i><br>
<br>


<?
if(isset($kundeloeschen)) {
  echo "<a href=\"module/biz/kunden.php?kundeloeschenja=true&kundenid=$kundenid\"><b>*Kunde jetzt löschen*</b></a>";
}

if(isset($kundeloeschenja)) {
  $db->query("delete from biz_kunden where adminid='$_SESSION[adminid]' and kundenid='$kundenid'");
}


?>


<table border="0" cellspacing="10" cellpadding="5">
<tr>
<td valign="top" width="540">

&raquo; <a href="module/biz/buchung_neu.php">Neue Buchung</a><br>
<br>


<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr bgcolor="#cccccc">
<td>

<table width="540" border="0" cellspacing="1" cellpadding="3">
<tr bgcolor="#e7e7e7" align="left" valign="top">
<td><b>KundenID</b></td>
<td><b>Kunde</b></td>
<td><b>Umsatz</b></td>
<td><b>Kontostand</b></td>
<td><b>Aktion</b></td>
</tr>
<?

$res = $db->query("select kundenid,nachname,vorname from biz_kunden where adminid='$_SESSION[adminid]' order by nachname");
while($row=$db->fetch_array($res)) {

  $res_b = $db->query("select betrag from biz_kundenbuchungen where adminid='$_SESSION[adminid]' and kundenid='$row[kundenid]'");
  while($row_b=$db->fetch_array($res_b)) {
    $umsatz = $umsatz + $row_b[betrag];
    $umsatz = sprintf("%.2f",$umsatz);
  }
  
  $res_r = $db->query("select positionen from biz_rechnungen where adminid='$_SESSION[adminid]' and kundenid='$row[kundenid]'");
  while($row_r=$db->fetch_array($res_r)) {
    $x  = explode("<br>",$row_r[positionen]);
    $n = count($x);

    for($i=0;$i<$n;$i++) {
      $y  = explode("|",$x[$i]);
      $summe = $y[0] * $y[2];
      $summe = sprintf("%.2f",$summe);

      $total = $summe + $total;
      $total = sprintf("%.2f",$total);
    }

    $schuld = $schuld + $total;
    $schuld = sprintf("%.2f",$schuld);
    
  }
  $konto = $umsatz - $schuld;
  $konto = sprintf("%.2f",$konto);
  
?>
<tr bgcolor="#FFFFFF" align="left" valign="top">
<td><?=$row[kundenid]?></td>
<td><?echo "$row[nachname], $row[vorname]";?></td>
<td><?=$umsatz?></td>
<td><?=$konto?></td>
<td>Aktion</td>
</tr>
<?
  $schuld = 0;
  $summe  = 0;
  $total  = 0;
  $umsatz = 0;
  $konto  = 0;
}

?>



</table>

</td>
</tr>
</table>

<br>
<br>
</td>
</tr>
</table>










<?include("../../footer.php");?>
