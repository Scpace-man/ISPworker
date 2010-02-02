<?
$module = basename(dirname(__FILE__));
include("../../header.php");
include("./inc/functions.inc.php");
?>

<span class="htitle">Domainzonen</span><br>
<br>


<font color="red">
ACHTUNG: Nehmen Sie Änderungen in diesem Bereich nur vor, wenn Sie genau wissen, was Sie tun:<br>
Falsche Einstellungen führen zur Nichterreichbarkeit Ihrer Domain!
</font>
<br><br>
Haben Sie bitte 24 bis 48 Stunden Geduld, denn unter Umständen dauert es solange bis<br>
Neueintragungen / Änderungen von externen Nameservern (z.B. denen Ihres Einwahlproviders) erkannt werden.<br>

<br>
<?
$res = $db->query("select domainname from biz_domains where kundenid='$_SESSION[user]' and domainname='$_REQUEST[domainname]'");
$row = $db->fetch_array($res);

if($row["domainname"]=="") die();


if($_REQUEST["action"]=="updaterecord") {
    echo $str = dns_request("&domain=$row[domainname]&action=updaterecord&record=$_REQUEST[quelle]&target=$_REQUEST[ziel]&prio=$_REQUEST[prio]&entryid=$_REQUEST[entryid]");
}

if($_REQUEST["action"]=="newrecord") {
    if($_REQUEST[subdomain]=="") $myrecord = $_REQUEST[domainname];
    else $myrecord = $_REQUEST[subdomain].$_REQUEST[domainname];
    echo $str = dns_request("&domain=$row[domainname]&action=newrecord&record=".$myrecord."&target=$_REQUEST[target]&prio=$_REQUEST[prio]&type=$_REQUEST[recordtype]");
}




$str = dns_request("&domain=$row[domainname]&action=listrecords");
$lines = explode("\n",$str);

$t = $html->table(0);
$t->addcol("Typ",50);
$t->addcol("Hostname",230);
$t->addcol("Ziel",180);
$t->addcol("Prio",50);
$t->addcol("<img src=\"img/pixel.gif\" width=\"0\" height=\"0\">",60);
$t->cols();

for($i = 0; $i < count($lines); $i++) {
    $x = explode("|",$lines[$i]);
    if($x[2]=="A") {
	echo "<form action=\"module/dns/zone_edit.php?action=updaterecord&domainname=$_REQUEST[domainname]&entryid=$x[1]\" method=\"post\">";
	$t->addrow($x[2]);
        $t->addrow("<input type=\"hidden\" name=\"quelle\" value=\"$x[3]\"> $x[3]");
	$t->addrow("<input type=\"text\" name=\"ziel\" value=\"$x[4]\">");
	$t->addrow($x[6]);   
	$t->addrow("<input type=\"submit\" value=\"Ändern\">");
	$t->rows();
	echo "</form>";
    }
    if($x[2]=="MX") {
	echo "<form action=\"module/dns/zone_edit.php?action=updaterecord&domainname=$_REQUEST[domainname]&entryid=$x[1]\" method=\"post\">";
	$t->addrow($x[2]);
        $t->addrow("<input type=\"hidden\" name=\"quelle\" value=\"$x[3]\"> $x[3]");
	$t->addrow("<input type=\"text\" name=\"ziel\" value=\"$x[4]\">");
	$t->addrow("<input type=\"text\" name=\"prio\" size=\"3\" value=\"$x[6]\">");   
	$t->addrow("<input type=\"submit\" value=\"Ändern\">");
	$t->rows();
	echo "</form>";
    }
}

$t->close();
?>
<br>
<b>Neuen Record anlegen</b><br>
<br>
<?

$t = $html->table(0);
$t->addcol("Typ",50);
$t->addcol("Hostname",210);
$t->addcol("Ziel",180);
$t->addcol("Prio",50);
$t->addcol("<img src=\"img/pixel.gif\" width=\"0\" height=\"0\">",60);
$t->cols();

echo "<form action=\"module/dns/zone_edit.php?action=newrecord&domainname=$_REQUEST[domainname]\" method=\"post\">";
$t->addrow("<select name=\"recordtype\"><option>A</option><option>MX</option></select>");
$t->addrow("<input type=\"text\" name=\"subdomain\" size=\"14\">.$_REQUEST[domainname]");
$t->addrow("<input type=\"text\" name=\"target\">");
$t->addrow("<input type=\"text\" name=\"prio\" size=\"3\" value=\"5\">");   
$t->addrow("<input type=\"submit\" value=\"Speichern\">");
$t->rows();
echo "</form>";

$t->close();

?>

<br>
<br>

<?include("../../footer.php");?>
