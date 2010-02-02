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

$bgcolor[2]   = "#ffffff";
$linecolor[2] = "#ffffff";

include("./inc/reiter4.php");

?>
<br>
<?


if($_REQUEST[save]=="true") {
    $db->query("update biz_settings set mahnautosend='$_REQUEST[mahnautosend]', mahntpl1='$_REQUEST[mahntpl1]', mahntpl2='$_REQUEST[mahntpl2]', mahntpl3='$_REQUEST[mahntpl3]',
	        mahntpl1sa='$_REQUEST[mahntpl1sa]', mahntpl2sa='$_REQUEST[mahntpl2sa]', mahntpl3sa='$_REQUEST[mahntpl3sa]'");
}


if($_REQUEST[saveexclude]=="true") {
    $arr1 = explode("\n",$_REQUEST["excludekunden"]);
    $arr2 = explode("\n",$_REQUEST["excluderechnungen"]);
    asort($arr1);
    asort($arr2);
    
    if(!is_writable($biz_temppath))
    	message($biz_temppath." ist nicht beschreibbar not writable.","error");
    else {
	if(!$fp = fopen($biz_temppath."/mahnexclude.txt","w+")) message($biz_temppath."/mahnexclude.txt ist nicht beschreibbar.","error");
	else {
	    fwrite($fp,serialize($arr1)."\n".serialize($arr2));
	    fclose($fp);
	}
	message("Einträge sind gespeichert.");
    }
}

$me = @file_get_contents($biz_temppath."/mahnexclude.txt"); 
if($me!="") {
    $x = explode("\n",$me);
    $arr1 = unserialize($x[0]);
    $arr2 = unserialize($x[1]);
    
    $exclkunden = implode("\n",$arr1);
    $exclrech   = implode("\n",$arr2);
}    

$res = $db->query("select * from biz_settings");
$row = $db->fetch_array($res);



$tplselect1 = "<select name=\"mahntpl1\">";
$tplselect2 = "<select name=\"mahntpl2\">";
$tplselect3 = "<select name=\"mahntpl3\">";

$res2 = $db->query("select * from biz_mahntemplates");
while($row2 = $db->fetch_array($res2)) {
    $tplselect1 .= "<option value=\"$row2[templateid]\""; if($row[mahntpl1]==$row2[templateid]) { $tplselect1 .= " selected"; } $tplselect1 .= ">$row2[templatename]</option>";
    $tplselect2 .= "<option value=\"$row2[templateid]\""; if($row[mahntpl2]==$row2[templateid]) { $tplselect2 .= " selected"; } $tplselect2 .= ">$row2[templatename]</option>";
    $tplselect3 .= "<option value=\"$row2[templateid]\""; if($row[mahntpl3]==$row2[templateid]) { $tplselect3 .= " selected"; } $tplselect3 .= ">$row2[templatename]</option>";
}

$tplselect1 .= "</select>";
$tplselect2 .= "</select>";
$tplselect3 .= "</select>";



$t = $html->table(0);
$t->addcol("Mahn Verhalten",500,2);
$t->cols();

$s1  = "<select name=\"mahnautosend\"><option value=\"0\"";  if($row[mahnautosend]==0) $s1.= " selected";
$s1 .= ">deaktiviert</option> <option value=\"1\"";  if($row[mahnautosend]==1) $s1 .= " selected";
$s1 .= ">aktiviert</option></select>";


echo "<form action=\"module/biz/mahnung_auto.php?save=true\" method=\"post\">";

$t->addrow("Automatisches Versenden von Mahnungen");
$t->addrow("$s1");
$t->rows();

$t->addrow("1. Mahnung $tplselect1 versenden nach");
$t->addrow("<input type=\"text\" name=\"mahntpl1sa\" size=\"3\" value=\"$row[mahntpl1sa]\"> Tagen");
$t->rows();

$t->addrow("2. Mahnung $tplselect2 versenden nach");
$t->addrow("<input type=\"text\" name=\"mahntpl2sa\" size=\"3\" value=\"$row[mahntpl2sa]\"> Tagen");
$t->rows();

$t->addrow("3. Mahnung $tplselect3 versenden nach");
$t->addrow("<input type=\"text\" name=\"mahntpl3sa\" size=\"3\" value=\"$row[mahntpl3sa]\"> Tagen");
$t->rows();

$t->close();

?>

<input type="submit" value="Speichern">

</form>

<br>
<br>
<br>
<font size="1">Richten Sie zudem <a href="module/biz/mahnung_cronwork.php" target="new">mahnung_cronwork.php</a> als minütlichen Cronjob in Ihrer Crontab ein.</font><br>
<br>
<br>

<?

echo "<form action=\"module/biz/mahnung_auto.php?saveexclude=true\" method=\"post\">";


$t = $html->table(0);
$t->addcol("Ausnahmen",500,1);
$t->cols();

$t->addrow("Kundennummern nicht mahnen, zeilenweise");
$t->rows();

$t->addrow("<textarea name=\"excludekunden\" style=\"width:500px; height:75px\">".$exclkunden."</textarea>");
$t->rows();

$t->addrow("Rechnungsnummern nicht mahnen, zeilenweise");
$t->rows();

$t->addrow("<textarea name=\"excluderechnungen\" style=\"width:500px; height:200px\">".$exclrech."</textarea>");
$t->rows();


$t->close();

?>

<input type="submit" value="Speichern">
</form>



<?include("../../footer.php");?>
