<?
$module = basename(dirname(__FILE__));
include("../../header.php");
?>


<span class="htitle">Domains</span><br>
<br>



<?


if($_REQUEST[action]=="delete") {
    for($i=0; $i < count($_REQUEST[ausw]); $i++) {
	trash("biz_domains","where domainname='".$_REQUEST[ausw][$i]."'");
    }
}


if($_REQUEST[anzahl] != "") $_SESSION[anzahlds] = $_REQUEST[anzahl];
if(!isset($_REQUEST[q]))    { $_REQUEST[q] = ""; }
if(!isset($_REQUEST['sort'])) { $_REQUEST['sort'] = "DESC"; }
if($_SESSION[anzahlds]=="") $_SESSION[anzahlds]  = 150;

if($_REQUEST[start]=="")     { $_REQUEST[start]     = 0; }
if($_REQUEST[ordnung]=="")   { $_REQUEST[ordnung]   = "d.domainname"; }
if($_REQUEST[buchstabe]=="") { $_REQUEST[buchstabe] = ""; }


?>


<br>
<table border="0" cellpadding="0" cellspacing="0">
<tr>
  <td colspan="2"><span class="small">Suche</span></td>
  <td colspan="2"><span class="small">Datensätze</span></td>
</tr>
<tr>
<td>
  <form action="module/biz/domains.php?ordnung=<?=$_REQUEST[ordnung]?>&start=<?=$_REQUEST[start]?>" method="post"> 
  <input type="text" name="q"> <input type="submit" value="Suchen">
  </form>
</td>
<td width="15"></td>
<td>
  <form action="module/biz/domains.php?buchstabe=<?=$_REQUEST[buchstabe]?>&ordnung=<?=$_REQUEST[ordnung]?>&start=<?=$_REQUEST[start]?>&q=<?=urlencode($_REQUEST[q])?>" method="post">
  <input type="text" name="anzahl" value="<?=$_SESSION[anzahlds]?>" size="10">
  <input type="submit" value="Anzeigen">
  </form>
</td>
</tr>
</table>


<br>

<?

$res = $db->query("select domainname from biz_domains where adminid='$_SESSION[adminid]'");
$total  = $db->num_rows($res);


if($_REQUEST[q]=="") {

$res = $db->query("select * from biz_domains as d, biz_kunden as k where k.kundenid=d.kundenid and d.domainname like '$_REQUEST[buchstabe]%' order by $_REQUEST[ordnung] $_REQUEST[sort]");
$n  = $db->num_rows($res);


$res = $db->query("select * from biz_domains as d, biz_kunden as k where k.kundenid=d.kundenid and d.domainname like '$_REQUEST[buchstabe]%' order by $_REQUEST[ordnung] $_REQUEST[sort] limit $_REQUEST[start],$_SESSION[anzahlds]");


}
else {
  $_REQUEST[q] = urldecode($_REQUEST[q]);
  $res = $db->query("select * from biz_domains as d, biz_kunden as k 
  where d.adminid='$_SESSION[adminid]' and k.kundenid = d.kundenid and (d.domainname like '%$_REQUEST[q]%' or k.nachname like '%$_REQUEST[q]%' or k.firma like '%$_REQUEST[q]%' or k.kundenid like '%$_REQUEST[q]%') order by $_REQUEST[ordnung] $_REQUEST[sort]");
  $n  = $db->num_rows($res);


  $res = $db->query("select * from biz_domains as d, biz_kunden as k 
  where d.adminid='$_SESSION[adminid]' and k.kundenid = d.kundenid and (d.domainname like '%$_REQUEST[q]%' or k.nachname like '%$_REQUEST[q]%' or k.firma like '%$_REQUEST[q]%' or k.kundenid like '%$_REQUEST[q]%') order by $_REQUEST[ordnung] $_REQUEST[sort] limit $_REQUEST[start],$_SESSION[anzahlds]");

}

?>


<table border="0" cellpadding="0" cellspacing="0" width="700">
<tr>
  <td width="700">



   <b><a href ="module/biz/domains.php?buchstabe=0&ordnung=<?=$_REQUEST[ordnung]?>&start=0">0</a></b> &nbsp;
   <b><a href ="module/biz/domains.php?buchstabe=1&ordnung=<?=$_REQUEST[ordnung]?>&start=0">1</a></b> &nbsp;
   <b><a href ="module/biz/domains.php?buchstabe=2&ordnung=<?=$_REQUEST[ordnung]?>&start=0">2</a></b> &nbsp;
   <b><a href ="module/biz/domains.php?buchstabe=3&ordnung=<?=$_REQUEST[ordnung]?>&start=0">3</a></b> &nbsp;
   <b><a href ="module/biz/domains.php?buchstabe=4&ordnung=<?=$_REQUEST[ordnung]?>&start=0">4</a></b> &nbsp;
   <b><a href ="module/biz/domains.php?buchstabe=5&ordnung=<?=$_REQUEST[ordnung]?>&start=0">5</a></b> &nbsp;
   <b><a href ="module/biz/domains.php?buchstabe=6&ordnung=<?=$_REQUEST[ordnung]?>&start=0">6</a></b> &nbsp;
   <b><a href ="module/biz/domains.php?buchstabe=7&ordnung=<?=$_REQUEST[ordnung]?>&start=0">7</a></b> &nbsp;
   <b><a href ="module/biz/domains.php?buchstabe=8&ordnung=<?=$_REQUEST[ordnung]?>&start=0">8</a></b> &nbsp;
   <b><a href ="module/biz/domains.php?buchstabe=9&ordnung=<?=$_REQUEST[ordnung]?>&start=0">9</a></b> &nbsp;
  
   <b><a href ="module/biz/domains.php?buchstabe=a&ordnung=<?=$_REQUEST[ordnung]?>&start=0">A</a></b> &nbsp;
   <b><a href ="module/biz/domains.php?buchstabe=b&ordnung=<?=$_REQUEST[ordnung]?>&start=0">B</a></b> &nbsp;
   <b><a href ="module/biz/domains.php?buchstabe=c&ordnung=<?=$_REQUEST[ordnung]?>&start=0">C</a></b> &nbsp;
   <b><a href ="module/biz/domains.php?buchstabe=d&ordnung=<?=$_REQUEST[ordnung]?>&start=0">D</a></b> &nbsp;
   <b><a href ="module/biz/domains.php?buchstabe=e&ordnung=<?=$_REQUEST[ordnung]?>&start=0">E</a></b> &nbsp;
   <b><a href ="module/biz/domains.php?buchstabe=f&ordnung=<?=$_REQUEST[ordnung]?>&start=0">F</a></b> &nbsp;
   <b><a href ="module/biz/domains.php?buchstabe=g&ordnung=<?=$_REQUEST[ordnung]?>&start=0">G</a></b> &nbsp;
   <b><a href ="module/biz/domains.php?buchstabe=h&ordnung=<?=$_REQUEST[ordnung]?>&start=0">H</a></b> &nbsp;
   <b><a href ="module/biz/domains.php?buchstabe=i&ordnung=<?=$_REQUEST[ordnung]?>&start=0">I</a></b> &nbsp;
   <b><a href ="module/biz/domains.php?buchstabe=j&ordnung=<?=$_REQUEST[ordnung]?>&start=0">J</a></b> &nbsp;
   <b><a href ="module/biz/domains.php?buchstabe=k&ordnung=<?=$_REQUEST[ordnung]?>&start=0">K</a></b> &nbsp;
   <b><a href ="module/biz/domains.php?buchstabe=l&ordnung=<?=$_REQUEST[ordnung]?>&start=0">L</a></b> &nbsp;
   <b><a href ="module/biz/domains.php?buchstabe=m&ordnung=<?=$_REQUEST[ordnung]?>&start=0">M</a></b> &nbsp;
   <b><a href ="module/biz/domains.php?buchstabe=n&ordnung=<?=$_REQUEST[ordnung]?>&start=0">N</a></b> &nbsp;
   <b><a href ="module/biz/domains.php?buchstabe=o&ordnung=<?=$_REQUEST[ordnung]?>&start=0">O</a></b> &nbsp;
   <b><a href ="module/biz/domains.php?buchstabe=p&ordnung=<?=$_REQUEST[ordnung]?>&start=0">P</a></b> &nbsp;
   <b><a href ="module/biz/domains.php?buchstabe=q&ordnung=<?=$_REQUEST[ordnung]?>&start=0">Q</a></b> &nbsp;
   <b><a href ="module/biz/domains.php?buchstabe=r&ordnung=<?=$_REQUEST[ordnung]?>&start=0">R</a></b> &nbsp;
   <b><a href ="module/biz/domains.php?buchstabe=s&ordnung=<?=$_REQUEST[ordnung]?>&start=0">S</a></b> &nbsp;
   <b><a href ="module/biz/domains.php?buchstabe=t&ordnung=<?=$_REQUEST[ordnung]?>&start=0">T</a></b> &nbsp;
   <b><a href ="module/biz/domains.php?buchstabe=u&ordnung=<?=$_REQUEST[ordnung]?>&start=0">U</a></b> &nbsp;
   <b><a href ="module/biz/domains.php?buchstabe=v&ordnung=<?=$_REQUEST[ordnung]?>&start=0">V</a></b> &nbsp;
   <b><a href ="module/biz/domains.php?buchstabe=w&ordnung=<?=$_REQUEST[ordnung]?>&start=0">W</a></b> &nbsp;
   <b><a href ="module/biz/domains.php?buchstabe=x&ordnung=<?=$_REQUEST[ordnung]?>&start=0">X</a></b> &nbsp;
   <b><a href ="module/biz/domains.php?buchstabe=y&ordnung=<?=$_REQUEST[ordnung]?>&start=0">Y</a></b> &nbsp;
   <b><a href ="module/biz/domains.php?buchstabe=z&ordnung=<?=$_REQUEST[ordnung]?>&start=0">Z</a></b> 
  
  
  </td>
</tr>
</table>


<br>

<form action="module/biz/domains.php" method="post">
<table width="700" border="0" cellspacing="0" cellpadding="0">
<tr bgcolor="#cccccc">
<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
<td width="16"><img src="img/pixel.gif" width="1" height="1"></td>
<td><a href="module/biz/domains.php?ordnung=d.domainname&start=<?=$_REQUEST[start]?>&q=<?=$_REQUEST[q]?>&buchstabe=<?=$_REQUEST[buchstabe]?>&sort=<?if($_REQUEST[sort] == "DESC") echo "ASC"; else echo "DESC";?>" class="tf">Domainname</a></td>
<td><a href="module/biz/domains.php?ordnung=k.nachname&start=<?=$_REQUEST[start]?>&q=<?=$_REQUEST[q]?>&buchstabe=<?=$_REQUEST[buchstabe]?>&sort=<?if($_REQUEST[sort] == "DESC") echo "ASC"; else echo "DESC";?>" class="tf">Nachname</a></td>
<td><a href="module/biz/domains.php?ordnung=k.firma&start=<?=$_REQUEST[start]?>&q=<?=$_REQUEST[q]?>&buchstabe=<?=$_REQUEST[buchstabe]?>&sort=<?if($_REQUEST[sort] == "DESC") echo "ASC"; else echo "DESC";?>" class="tf">Firma</a></td>
<td width="70"><a href="module/biz/domains.php?ordnung=d.freigeschaltet&start=<?=$_REQUEST[start]?>&q=<?=$_REQUEST[q]?>&buchstabe=<?=$_REQUEST[buchstabe]?>&sort=<?if($_REQUEST[sort] == "DESC") echo "ASC"; else echo "DESC";?>" class="tf">Freigeschaltet</a></td>
<?
while($row=$db->fetch_array($res)) {
?>

</tr>
<tr class="tr">
<td width="16"><input type="checkbox" name="ausw[]" value="<?=$row[domainname]?>"></td>
<td><?=$row[domainname]?></td>
<td><a href="module/biz/kunden_detail.php?kundenid=<?=$row[kundenid]?>"><?=$row[nachname]?>, <?=$row[vorname]?></a></td>
<td><?=$row[firma]?></td>
<td><?=$row[freigeschaltet]?></td>
</tr>
<?
}
?>
</table>
</td>
</tr>
</table>

<br>
<select name="action"><option value="delete">Löschen</option></select> <input type="submit" value="Abschicken">
</form>

<br>
<br>
<br>
<center>
<?for($i = 1; $i <= round($n / $_SESSION[anzahlds]); $i++) { $s = (($i-1) * $_SESSION[anzahlds]); ?>
<a href="module/biz/domains.php?buchstabe=<?=$_REQUEST[buchstabe]?>&ordnung=<?=$_REQUEST[ordnung]?>&start=<?=$s?>&q=<?=urlencode($_REQUEST[q])?>"><?=$i?></a> <img src="img/
<?if(($i % 10) == 0) echo "<br>"; }?>
</center>
<br>


<br>

<?include("../../footer.php");?>
