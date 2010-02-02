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

$bgcolor[3]   = "#ffffff";
$linecolor[3] = "#ffffff";

include("./inc/reiter4.php");

$res = $db->query("select waehrung from biz_settings");
$row = $db->fetch_array($res);
$waehrung=$row[waehrung];

?>
<br>
<?


if($_REQUEST[editsave]=="true") {
    $db->query("update biz_mahntemplates set templatename='$_REQUEST[templatename]', pretext='".strip_cr($_REQUEST[pretext])."', posttext='".strip_cr($_REQUEST[posttext])."', mgebuehr='".$_REQUEST[mgebuehr]."', rgebuehr='".$_REQUEST[rgebuehr]."' where templateid='$_REQUEST[tplid]'");
}

if($_REQUEST[newsave]=="true") {
    $db->query("insert into biz_mahntemplates (templatename,pretext,posttext,mgebuehr,rgebuehr) values ('$_REQUEST[templatename]','".strip_cr($_REQUEST[pretext])."','".strip_cr($_REQUEST[posttext])."','$_REQUEST[mgebuehr]','$_REQUEST[rgebuehr]')");
}

if($_REQUEST['delete']=="true") {
    $res = $db->query("select count(*) as anz from biz_mahnungen where templateid='$_REQUEST[tplid]'");
    $row = $db->fetch_array($res);
    if($row[anz] > 0) message("Fehler: $row[anz] Mahnungen verweisen auf das zu löschende Template.","error");
    else {
	$db->query("delete from biz_mahntemplates where templateid='$_REQUEST[tplid]'");
	message("Template  ist gelöscht.");
    }
}



$t = $html->table(0);
$t->addcol("Template",200);
$t->addcol("Geb&uuml;hr in $waehrung",100);
$t->addcol("Rücklastschrift-Geb&uuml;hr in $waehrung",200);
$t->addcol("<img src=\"img/pixel.gif\" width=\"1\" height=\"1\">",32,2);
$t->cols();


$res = $db->query("select * from biz_mahntemplates");
while($row = $db->fetch_array($res)) 
{
    $t->addrow($row[templatename]);    
    $t->addrow($row[mgebuehr]);    
    $t->addrow($row[rgebuehr]);    
    $t->addrow("<a href=\"module/biz/mahnung_tpl.php?edit=true&tplid=$row[templateid]\"><img src=\"img/edit.gif\" border=\"0\"></a>");
    $t->addrow("<a href=\"module/biz/mahnung_tpl.php?delete=true&tplid=$row[templateid]\" onclick=\"return confirm('Möchten Sie den Datensatz wirklich löschen?');\"><img src=\"img/trash.gif\" border=\"0\"></a>");
    $t->rows();
}    
$t->close();
?>
<font size="1">&raquo; <a href="module/biz/mahnung_tpl.php?new=true">Neues Template</a></font>

<br>
<?if($_REQUEST[edit]=="true") {

$res = $db->query("select * from biz_mahntemplates where templateid='$_REQUEST[tplid]'");
$row = $db->fetch_array($res);
?>
<br>
<br>
<form action="module/biz/mahnung_tpl.php?edit=true&editsave=true&tplid=<?=$_REQUEST[tplid]?>" method="post">
<?
$t = $html->table(0);
$t->addcol("Template bearbeiten",600,2);
$t->cols();

$t->addrow("Templatename");    
$t->addrow("<input type=\"text\" name=\"templatename\" value=\"$row[templatename]\" size=\"45\">");
$t->rows();

$t->addrow("Text vor Ausgabe der Positionen");    
$t->addrow("<textarea name=\"pretext\" cols=\"80\" rows=\"8\">$row[pretext]</textarea>");
$t->rows();

$t->addrow("Text nach Ausgabe der Positionen");    
$t->addrow("<textarea name=\"posttext\" cols=\"80\" rows=\"8\">$row[posttext]</textarea>");
$t->rows();

$t->addrow("Mahngeb&uuml;hr");
$t->addrow("<input type=\"text\" name=\"mgebuehr\" value=\"$row[mgebuehr]\" size=\"15\">");
$t->rows();

$t->addrow("Rücklastschriftgeb&uuml;hr");
$t->addrow("<input type=\"text\" name=\"rgebuehr\" value=\"$row[rgebuehr]\" size=\"15\">");
$t->rows();

$t->addrow("<img src=\"img/pixel.gif\">");    
$t->addrow("<input type=\"submit\" value=\"Speichern\">");
$t->rows();

$t->close();

?>
</form>
<?}?>

<?if($_REQUEST['new']=="true") {?>
<br>
<br>


<form action="module/biz/mahnung_tpl.php?newsave=true" method="post">
<?
$t = $html->table(0);
$t->addcol("Neues Template",600,2);
$t->cols();

$t->addrow("Templatename");    
$t->addrow("<input type=\"text\" name=\"templatename\" size=\"45\">");
$t->rows();

$t->addrow("Text vor Ausgabe der Positionen");    
$t->addrow("<textarea name=\"pretext\" cols=\"80\" rows=\"8\"></textarea>");
$t->rows();

$t->addrow("Text nach Ausgabe der Positionen");    
$t->addrow("<textarea name=\"posttext\" cols=\"80\" rows=\"8\"></textarea>");
$t->rows();

$t->addrow("Mahngeb&uuml;hr");
$t->addrow("<input type=\"text\" name=\"mgebuehr\" value=\"0.00\" size=\"15\">");
$t->rows();

$t->addrow("Rücklastschriftgeb&uuml;hr");
$t->addrow("<input type=\"text\" name=\"rgebuehr\" value=\"0.00\" size=\"15\">");
$t->rows();


$t->addrow("<img src=\"img/pixel.gif\">");    
$t->addrow("<input type=\"submit\" value=\"Speichern\">");
$t->rows();

$t->close();

?>
</form>







<?}?>
<br>
<br>






<center>
<?for($i = 1; $i <= round($n / $_SESSION[anzahlds]); $i++) { $s = (($i-1) * $_SESSION[anzahlds]); ?>
<a href="module/biz/mahnungen.php?ordnung=<?=$ordnung?>&start=<?=$s?>&q=<?=urlencode($q)?>"><?=$i?></a> <img src="img/pixel.gif" width="6" height="1" border="0">
<?if(($i % 10) == 0) echo "<br>"; }?>
</center>
<br>


<br>

<?include("../../footer.php");?>
