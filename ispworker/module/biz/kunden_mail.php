<?
$module = basename(dirname(__FILE__));
include("../../header.php");
?>


<i><b>Kunden</b></i><br>
<br>



<?


if($merken==true) {
  
  if($doit!=true) {    
    if($_SESSION['merkkunde']!="") {
      echo "<font color=\"red\">Fehler: Es wurde bereits ein Kunde gemerkt</font> <a href=\"module/biz/kunden.php?merken=true&doit=true&kundenid=$kundenid\">Ignorieren und Kunde überschreiben</a><br><br>";
    }
    else {
      $_SESSION['merkkunde'] = $kundenid;
      echo "<b>Kunde ist auf dem Merkzettel notiert.</b><br><br>"; 
    }
  }
  else {
    $_SESSION['merkkunde'] = $kundenid;
    $merkkunde = $kundenid;
    echo "<b>Kunde ist notiert.</b><br><br>";
  }
}


if(isset($kundeloeschen)) {
  echo "<a href=\"module/biz/kunden.php?kundeloeschenja=true&kundenid=$kundenid\"><b>*Jawohl, Kunde jetzt löschen*</b></a>";
}



if(isset($kundeloeschenja)) {
  $db->query("delete from biz_kunden where adminid='$_SESSION[adminid]' and kundenid='$kundenid'");
  $db->query("delete from biz_kundenbuchungen where adminid='$_SESSION[adminid]' and kundenid='$kundenid'");
  $db->query("delete from biz_rechnungen where adminid='$_SESSION[adminid]' and kundenid='$kundenid'");
}
?>





<table border="0" cellspacing="10" cellpadding="5" width="650">
<tr>
<td valign="top" width="650">


&raquo; <a href="module/biz/kunde_neu.php">Neuer Kunde</a><br>
<br>
<table>
<tr>
<td valign="top" colspan="2">

<form action="module/biz/kunden_suche.php" method="post">
<strong>Volltextsuche</strong> <input type="text" name="suche" size="30">
<input type="submit" value="Suche starten">
</form>
</td>
</tr>
<tr>
<td width="320">
<form action="module/biz/kunden.php" method="post">
<select name="anzahl">
<option value="30">30 Datensätze pro Seite</option>
<option value="50">50 Datensätze pro Seite</option>
<option value="100">100 Datensätze pro Seite</option>
<option value="200">200 Datensätze pro Seite</option>
</select>
<input type="submit" value="Anzeigen">
</form>
</td>
<td width="250">
<form action="module/biz/kunden.php" method="post">
<select name="ordnung">
<option value="nachname">Nachname</option>
<option value="kundenid">Kundennummer</option>
<option value="firma">Firma</option>
</select>
<input type="submit" value="Ordnen">
</form>
</td>
</tr>
</table>





<?
if(!isset($anzahl)) { $anzahl = 30; }
if(!isset($start))  { $start  = 0; }
if(!isset($ordnung))  { $ordnung  = nachname; }

$res = $db->query("select kundenid from biz_kunden where adminid='$_SESSION[adminid]'");
$ds  = $db->num_rows($res);




$seiten = $ds / $anzahl;
$x=0;

for($i=0;$i<$seiten;$i++) {
  $n = $i + 1;
  echo "<a href=\"module/biz/kunden.php?start=$x&anzahl=$anzahl\">Seite $n</a>&nbsp; &nbsp; &nbsp;";
  $x = $x + $anzahl;
}
?>
<br>
<br>

<table width="900" border="0" cellspacing="0" cellpadding="0">
<tr bgcolor="#cccccc">
<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr bgcolor="#e7e7e7" align="left" valign="top">

<td><b>E-Mail</b></td>


<?

$res = $db->query("select kundenid,vorname,nachname,firma,bemerkung,telefon,mail from biz_kunden where adminid='$_SESSION[adminid]' order by $ordnung");
while($row=$db->fetch_array($res)) {
?>

</tr>
<tr bgcolor="#FFFFFF" align="left" valign="top">

<td><? //echo "<a href='mailto:$row[mail]'>$row[mail]</a>"; ?> <?=row[mail]?></td>


</tr>
<?
}
?>
</table>
</td>
</tr>
</table>

<br>
<br>
<?
/*
if($start > 0) {
  $start = $start - $anzahl;
  echo "<a href=\"rcp_list.php?listid=$listid&start=$start\"><< Zurück</a> |";
}
*/

$seiten = $ds / $anzahl;
$x=0;

for($i=0;$i<$seiten;$i++) {
  $n = $i + 1;
  echo "<a href=\"module/biz/kunden.php?start=$x&anzahl=$anzahl\">Seite $n</a>&nbsp; &nbsp; &nbsp;";
  $x = $x + $anzahl;
}


?>
</td>
</tr>
</table>

<br>
<br>



















<?include("../../footer.php");?>

