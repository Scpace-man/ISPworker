<?
$module = basename(dirname(__FILE__));
include("../../header.php");
include("../order/inc/functions.inc.php");
include("../order/inc/idna_convert.class.php");
include("../order/inc/request.class.php");
include("../order/inc/whois_request.class.php");
?>


<h3>Domain Abfrage</h3>
<hr size="1" noshade>
<br>

<?

$_SESSION[end] = false;

if(!isset($_SESSION[selecteddomains])) { $_SESSION[selecteddomains] = 0; }
if(!isset($_SESSION[domainstoorder]))  { $_SESSION[domainstoorder]  = ""; }

if($_REQUEST[check]=="true") {

    $go = true;

    $res = $db->query("select * from order_tld where tldid='$_REQUEST[tld]'");
    $row = $db->fetch_array($res);

    $fulldomain = $_REQUEST[domain]."$row[tld]";

    if($row[idnaktiv]=="Y") { $fulldomain = domain_idnconvert($fulldomain); }

    if(strlen($_REQUEST[domain]) < $row[minlen]) { $go=false; }
    if(strlen($_REQUEST[domain]) > $row[maxlen]) { $go=false; }

    if($go!=false) {

	if($row[tld]==".de")
	    $o = domain_whois($row[whoisserver], "-T st,ace ".$fulldomain);
	else
	    $o = domain_whois($row[whoisserver], $fulldomain);


	if(strstr($o,$row[wortvergeben])) {
	    $status = "vergeben";
	    echo "<font color=\"red\">Die Domain $fulldomain ist vergeben.</font>"; if($row[kkaktiv]=="Y") { echo " <br><a href=\"module/kundenmenue/order_domain.php?orderd=true&paketid=$_REQUEST[paketid]&domain=$fulldomain{KK}\">Diese Domain gehört mir und soll per KK Antrag  übernommen werden.</a><br>"; }
	}
	if(ereg($row[ereg], $_REQUEST[domain])){
            $status = "invalid";
	    echo "<font color=\"red\">Die Domain $fulldomain ist ungültig.</font><br>";
	}

	if(strstr($o,$row[wortinvalid]))  {
	    $status = "invalid";
	    echo "<font color=\"red\">Die Domain $fulldomain ist ungültig.</font><br>";
	}
	if(strstr($o,$row[wortfrei]) and $status != "invalid") {
	    $status = "frei";
	    echo "<font color=\"green\">Die Domain $fulldomain ist frei.</font> <a href=\"module/kundenmenue/order_domain.php?orderd=true&paketid=$_REQUEST[paketid]&domain=$fulldomain\">Bestellen</a><br>";
	}
    }
    else {
	echo "<font color=\"red\">Der Domainname ist ungültig.</font><br>";
    }

}


if($_REQUEST[orderd]=="true") {
    $_SESSION[selecteddomains]++;
    $_SESSION[domainstoorder] .= "$_REQUEST[domain];";
    echo "Die Domain $_REQUEST[domain] ist auf der Merkliste.<br>";
}


if($_REQUEST[deleted]=="true") {
    $_SESSION[selecteddomains]--;
    $_SESSION[domainstoorder] = str_replace("$_REQUEST[domaintodel];","",$_SESSION[domainstoorder]);
}

echo "<br>";

?>

<form action="module/kundenmenue/order_domain.php?check=true&paketid=<?=$_REQUEST[paketid]?>" method="post">
<table width="400" border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
<td colspan="2"><b>Domain Check</b></td>
</tr>
<tr class="tr">
<td>http://www.<input type="text" name="domain" value="<?=$_REQUEST[domain]?>">
<select name="tld">
<?
$res = $db->query("select * from order_tld order by pos");
while($row=$db->fetch_array($res)) {?>
<option value="<?=$row[tldid]?>" <?if($tld==$row[tldid]) echo "selected";?>><?=$row[tld]?></option>
<?}?>
</select>
</td>
<td><input type="submit" value="Prüfen"></td>
</tr>
</table>

</td>
</tr>
</table>
</form>

<br>

<?
$da = explode(";",$_SESSION[domainstoorder]);

if($_SESSION[selecteddomains] > 0) {
?>
<table width="400" border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
    <td colspan="2"><b>Gebuchte Domains</b></td>
</tr>
<?
for($i=0;$i<count($da);$i++) {
if($da[$i]!="") {
?>
<tr class="tr">
    <td><?=$da[$i]?></td>
    <td><a href="module/kundenmenue/order_domain.php?deleted=true&domaintodel=<?=$da[$i]?>&paketid=<?=$_REQUEST[paketid]?>">Löschen</a></td>
</tr>
<?
}
}
?>
</table>
</td>
</tr>
</table>




<br>


<form action="module/kundenmenue/order_domain2.php" method="get">

<table width="400" border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
<td colspan="2"><b>Ihre Bestellung</b></td>
</tr>
<tr class="tr">
<td width="18" valign="top">
<input type="hidden" name="paketid" value="<?=$paketid?>">
<input type="checkbox" name="agree" value="true">
</td>
<td valign="top">
 Ja, ich bestelle diese Domain(s) rechtsverbindlich.
Ich bin damit einverstanden, dass ich aufgrund der sofortigen Weiterleitungen
der Aufträge an die nationalen und internationalen Domain-Registrargesellschaften
 auf mein Widerrufsrecht verzichte.
<br>
</td>
</tr>
<tr class="tr">
<td colspan="2">
<input type="submit" value="Bestellung jetzt abschicken">
</td>
</tr>
</table>

</td>
</tr>
</table>

</form>



<?
}
?>



<?include("../../footer.php");?>
