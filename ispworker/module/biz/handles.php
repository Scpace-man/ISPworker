<?
$module = basename(dirname(__FILE__));
include("../../header.php");

?>
<span class="htitle">Handles</span><br>
<br>

<?

include("./inc/reiter3.layout.php");

$bgcolor[0]   = "#f0f0f0";
$linecolor[0] = "#000000";

$bgcolor[0]   = "#ffffff";
$linecolor[0] = "#ffffff";

include("./inc/reiter3.php");



if($anzahl != "") $_SESSION[anzahlds] = $anzahl;
if(!isset($q))    { $q = ""; }
if(!isset($sort)) { $sort = "DESC"; }
if($_SESSION[anzahlds]=="") $_SESSION[anzahlds]  = 150;

if($start=="")     { $start     = 0; }
if($ordnung=="")   { $ordnung   = "k.nachname"; }
if($buchstabe=="") { $buchstabe = ""; }


?>


<br>
<table border="0" cellpadding="0" cellspacing="0">
<tr>
  <td colspan="2"><span class="small">Suche</span></td>
  <td colspan="2"><span class="small">Datensätze</span></td>
</tr>
<tr>
<td>
  <form action="module/biz/handles.php?ordnung=<?=$ordnung?>&start=<?=$start?>" method="post"> 
  <input type="text" name="q"> <input type="submit" value="Suchen">
  </form>
</td>
<td width="15"></td>
<td>
  <form action="module/biz/handles.php?ordnung=<?=$ordnung?>&start=<?=$start?>&q=<?=urlencode($q)?>" method="post">
  <input type="text" name="anzahl" value="<?=$_SESSION[anzahlds]?>" size="10">
  <input type="submit" value="Anzeigen">
  </form>
</td>
</tr>
</table>


<br>
<br>

<?


    $q = urldecode($q);
    $res = $db->query("select * from biz_handles as h where h.vorname like '%$q%' or h.nachname like '%$q%'");
    $n  = $db->num_rows($res);

    $res = $db->query("select * from biz_handles as h where h.vorname like '%$q%' or h.nachname like '%$q%'");

	    


$t = $html->table(0);

if($sort == "DESC") $nsort = "ASC"; else $nsort = "DESC"; 
$s = "<a href=\"module/biz/handles.php?ordnung=h.handleid&start=$start&q=$q&sort=$nsort\" class=\"tf\">HandleID</a>";
$t->addcol("$s",80);

if($sort == "DESC") $nsort = "ASC"; else $nsort = "DESC"; 
$s = "<a href=\"module/biz/handles.php?ordnung=h.exthandleid&start=$start&q=$q&sort=$nsort\" class=\"tf\">Externe HandleID</a>";
$t->addcol("$s",100);


if($sort == "DESC") $nsort = "ASC"; else $nsort = "DESC"; 
$s = "<a href=\"module/biz/handles.php?ordnung=k.nachname&start=$start&q=$q&sort=$nsort\" class=\"tf\">Kunde</a>";
$t->addcol("$s",200);

$t->addcol("Daten",500);
$t->cols();

while($row = $db->fetch_array($res)) {
    $t->addrow($row[handleid]);
    $t->addrow($row[exthandleid]);
    if($row[kundenid]!="") {
	$res2 = $db->query("select nachname, vorname from biz_kunden where kundenid='$row[kundenid]'");
	$row2 = $db->fetch_array($res2);
	$t->addrow("$row2[nachname], $row2[vorname]");
    }
    else 
    {
	$t->addrow("--");
    }
    $t->addrow("
    <font size=\"1\">
    $row[nachname], $row[vorname];$row[firma];$row[strasse] $row[strassenr];$row[land]-$row[plz];$row[ort];<br>
    $row[telefon]; $row[fax]; $row[email];
    </font>
    ");
    $t->rows();
}    
$t->close();
?>


<br>

<br>
<br>
<br>
<center>
<?for($i = 1; $i <= round($n / $_SESSION[anzahlds]); $i++) { $s = (($i-1) * $_SESSION[anzahlds]); ?>
<a href="module/biz/domains.php?buchstabe=<?=$buchstabe?>&ordnung=<?=$ordnung?>&start=<?=$s?>&q=<?=urlencode($q)?>"><?=$i?></a> <img src="img/
<?if(($i % 10) == 0) echo "<br>"; }?>
</center>
<br>


<br>

<?include("../../footer.php");?>
