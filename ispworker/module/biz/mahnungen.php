<?
$module = basename(dirname(__FILE__));
include("../../header.php");

?>
<span class="htitle">Mahnwesen</span><br>
<br>

<?

include("./inc/reiter4.layout.php");

$bgcolor[0]   = "#f0f0f0";
$linecolor[0] = "#000000";

$bgcolor[0]   = "#ffffff";
$linecolor[0] = "#ffffff";

include("./inc/reiter4.php");

if($_REQUEST[action]=="delete") {
    for($i=0;$i<count($_REQUEST[ausw]);$i++) {
		trash("biz_mahnungen","where mahnid='".$_REQUEST[ausw][$i]."'");
    }
}

if($_REQUEST[action]=="offen") {
    for($i=0;$i<count($_REQUEST[ausw]);$i++) {
	    $db->query("update biz_mahnungen set status='offen' where mahnid='".$_REQUEST[ausw][$i]."'");
    }
}

if($_REQUEST[action]=="erledigt") {
    for($i=0;$i<count($_REQUEST[ausw]);$i++) {
	    $db->query("update biz_mahnungen set status='erledigt' where mahnid='".$_REQUEST[ausw][$i]."'");
    }
}

if($_REQUEST[anzahl] != "") $_SESSION[anzahlds] = $_REQUEST[anzahl];
if(!isset($_REQUEST[q]))    { $_REQUEST[q] = ""; }
if(!isset($_REQUEST['sort'])) { $_REQUEST['sort'] = "DESC"; }
if($_SESSION[anzahlds]=="") $_SESSION[anzahlds]  = 150;

if($_REQUEST[start]=="")     { $_REQUEST[start]     = 0; }
if($_REQUEST[ordnung]=="")   { $_REQUEST[ordnung]   = "m.datum"; }

?>


<br>
<table border="0" cellpadding="0" cellspacing="0">
<tr>
  <td colspan="2"><span class="small">Suche</span></td>
  <td colspan="2"><span class="small">Datensätze</span></td>
</tr>
<tr>
<td>
  <form action="module/biz/mahnungen.php?ordnung=<?=$_REQUEST[ordnung]?>&start=<?=$_REQUEST[start]?>" method="post"> 
  <input type="text" name="q"> <input type="submit" value="Suchen">
  </form>
</td>
<td width="15"></td>
<td>
  <form action="module/biz/mahnungen.php?ordnung=<?=$_REQUEST[ordnung]?>&q=<?=urlencode($_REQUEST[q])?>" method="post">
  <input type="text" name="anzahl" value="<?=$_SESSION[anzahlds]?>" size="10">
  <input type="submit" value="Anzeigen">
  </form>
</td>
<td width="15"></td>  
<td>
  <form action="module/biz/mahnungen.php?ordnung=<?=$_REQUEST[ordnung]?>&q=<?=urlencode($_REQUEST[q])?>" method="post">
	<select name="status">
		<option value="offen"<?if($_REQUEST[status]=="offen") echo " selected";?>>offene Mahnungen</option>
		<option value="erledigt"<?if($_REQUEST[status]=="erledigt") echo " selected";?>>erledigte Mahnungen</option>
	</select>
  <input type="submit" value="Anzeigen">
  </form>
</td>
</tr>
</table>

<br>
<br>

<form action="module/biz/mahnungen.php" method="post">

<?
if(!isset($_REQUEST[status])){
	$_REQUEST[status]="offen";
}
$_REQUEST[q] = urldecode($_REQUEST[q]);
$res = $db->query("select * from biz_mahnungen m, biz_kunden k where m.status='$_REQUEST[status]' AND m.mahnid like '%$_REQUEST[q]%' and m.kundenid=k.kundenid");
$n  = $db->num_rows($res);

$res = $db->query("select * from biz_mahnungen m, biz_kunden k where m.status='$_REQUEST[status]' AND m.mahnid like '%$_REQUEST[q]%' and m.kundenid=k.kundenid order by $_REQUEST[ordnung] $_REQUEST[sort] limit $_REQUEST[start],$_SESSION[anzahlds]");

$t = $html->table(0);

$t->addcol("<img src=\"img/pixel.gif\" border=\"0\" width=\"1\" height=\"1\">",20);

if($_REQUEST['sort'] == "DESC") $nsort = "ASC"; else $nsort = "DESC"; 
$s = "<a href=\"module/biz/mahnungen.php?ordnung=mahnid&start=$_REQUEST[start]&status=$_REQUEST[status]&q=$_REQUEST[q]&sort=$nsort\" class=\"tf\">Mahnung-Nr</a>";
$t->addcol("$s",80);

if($_REQUEST[sort] == "DESC") $nsort = "ASC"; else $nsort = "DESC"; 
$s = "<a href=\"module/biz/mahnungen.php?ordnung=m.datum&start=$_REQUEST[start]&status=$_REQUEST[status]&q=$_REQUEST[q]&sort=$nsort\" class=\"tf\">Datum</a>";
$t->addcol("$s",80);

if($_REQUEST[sort] == "DESC") $nsort = "ASC"; else $nsort = "DESC"; 
$s = "<a href=\"module/biz/mahnungen.php?ordnung=m.templateid&start=$_REQUEST[start]&status=$_REQUEST[status]&q=$_REQUEST[q]&sort=$nsort\" class=\"tf\">Mahntyp</a>";
$t->addcol("$s",130);

if($_REQUEST[sort] == "DESC") $nsort = "ASC"; else $nsort = "DESC"; 
$s = "<a href=\"module/biz/mahnungen.php?ordnung=m.positionen&start=$_REQUEST[start]&status=$_REQUEST[status]&q=$_REQUEST[q]&sort=$nsort\" class=\"tf\">Rechnung(en)</a>";
$t->addcol("$s",300);

if($_REQUEST[sort] == "DESC") $nsort = "ASC"; else $nsort = "DESC"; 
$s = "<a href=\"module/biz/mahnungen.php?ordnung=m.mahnid&start=$_REQUEST[start]&status=$_REQUEST[status]&q=$_REQUEST[q]&sort=$nsort\" class=\"tf\">Kunde</a>";
$t->addcol("$s",200);

$s = "Status";
$t->addcol("$s",50);

$t->addcol("<img src=\"img/pixel.gif\" width=\"1\" height=\"1\">",16);

$t->cols();

$res2 = $db->query("select * from biz_mahntemplates");
while($row2 = $db->fetch_array($res2)) { $arr_tpl["$row2[templateid]"] = $row2[templatename]; }


while($row = $db->fetch_array($res)) 
{
    $t->addrow("<input type=\"checkbox\" name=\"ausw[]\" value=\"$row[mahnid]\">");
    $t->addrow($row[mahnid]);
    $t->addrow($row[datum]);
    $t->addrow($arr_tpl[$row["templateid"]]);

    $re = explode(";",$row[positionen]);
    unset($rechnungen); 
    for($i=0;$i<count($re);$i++)
    {
	$rechnungen .= "<a href=\"module/biz/rechnung_show.php?rechnungid=$re[$i]\" target=\"_blank\">$re[$i]</a>&nbsp;&nbsp;";
    }
    
    $t->addrow($rechnungen);    
    $t->addrow("<a href=\"module/biz/kunde_editieren.php?kundenid=$row[kundenid]\">$row[nachname], $row[vorname]</a>");
	if($row[status]=="offen"){
	   $t->addrow("<img src=\"img/status_red.gif\">");	
	}else{
	   $t->addrow("<img src=\"img/status_green.gif\">");	
	}
     $t->addrow("<a href=\"module/biz/mahnung_show.php?mahnid=$row[mahnid]\" target=\"_blank\"><img src=\"img/pdf.gif\" border=\"0\"></a>");
    
    $t->rows();
}    
$t->close();
?>

<select name="action">
<option value="delete">Löschen</option>
<option value="offen">offen</option>
<option value="erledigt">erledigt</option>
</select>
<input type="submit" value="Abschicken">
</form>



<br>

<br>
<br>
<br>
<center>
<?for($i = 1; $i <= round($n / $_SESSION[anzahlds]); $i++) { $s = (($i-1) * $_SESSION[anzahlds]); ?>
<a href="module/biz/mahnungen.php?ordnung=<?=$_REQUEST[ordnung]?>&start=<?=$s?>&status=<?=$_REQUEST[status]?>&q=<?=urlencode($_REQUEST[q])?>"><?=$i?></a> <img src="img/pixel.gif" width="6" height="1" border="0">
<?if(($i % 10) == 0) echo "<br>"; }?>
</center>
<br>


<br>

<?include("../../footer.php");?>
