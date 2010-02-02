<?
if($_REQUEST[tldb]=='N' ){

	header("Location: order_addons.php?paketid=$_REQUEST[paketid]&tldb=$_REQUEST[tldb]");
	exit;
}else{
	include("header.php");
	$_SESSION['end'] = false;
	$_SESSION['paketid'] = $_REQUEST['paketid'];
	if(!isset($_SESSION["selecteddomains"])) { $_SESSION["selecteddomains"] = 0; }
	if(!isset($_SESSION["domainstoorder"]))  { $_SESSION["domainstoorder"]  = ""; }
	if(!isset($_SESSION["lastdomain"])) { $_SESSION["selecteddomains"] = 0; }

if($_REQUEST[orderd]=="true" && strpos($_SESSION['domainstoorder'], $_REQUEST[domain]) === false)
{
    $_SESSION["selecteddomains"]++;
    $_SESSION["domainstoorder"] .= "$_REQUEST[domain];";
    $memo = "Die Domain ".$_REQUEST[domain]." ist auf der Merkliste.<br>";

	//Erhalten der gerade bestellten Domain
    $fqdn=$_REQUEST[domain];

	//Domain splitten nach name und tld für weiterführende checks
   	$domainname =	explode(".",$_REQUEST[domain]);
   	if(strlen($domainname[1])>4){
   		$domainname[1]=str_replace("{KK}","",$domainname[1]);
   	}

	if(count($domainname)==2)
	{
		$_REQUEST[domain] = $domainname[0];
		$res = $db->query("select * from order_tld WHERE tld='.".$domainname[1]."'order by pos ASC");
		while($row=$db->fetch_array($res))
		{
			$_REQUEST[tld] = $row[tldid];
		}

		$_REQUEST[check] = "true";
    }
}

?>


<h3>Punkt 2: Domain Abfrage</h3>
<hr size="1" noshade>
<br>

<?

$res = $db->query("select * from order_artikel where artikelid='".$_REQUEST[paketid]."' ");
$row_artikel = $db->fetch_array($res);

//echo $_SESSION["domainstoorder"];exit;



if($row_artikel["anzdomains"]==0) {
	echo "<b>In diesem Paket sind keine Domains inklusive.<br>\n";
	echo "Sie k&ouml;nnen Domains gem&auml;ß unserer Preisliste hinzukaufen oder diesen Punkt &uuml;berspringen,<br>\n";
	echo "indem Sie auf \"Weiter\" klicken.<br></b>\n";
}
else
{
	echo "In diesem Paket sind ".$row_artikel["anzdomains"]." Domains inklusive.<br>\n";
}

if($row_artikel["minanzdomains"]==0) {
	echo "Geben Sie hier bitte zumindest eine Domain an. Die restlichen Domains<br>\n";
	echo "k&ouml;nnen Sie direkt anschlie&szlig;end oder auch jederzeit sp&auml;ter auswŠhlen. Die von Ihnen eingegebene Domain wird auf Verf&ouml;gbarkeit gepr&uuml;ft, anschlie&szlig;end geht es weiter mit Schritt 3.<br>\n";
}


if($_REQUEST[check]=="true") {
?>
    <br>
<table width="600" border="0" cellspacing="0" cellpadding="0">
    <tr class="tb">
    	<td>
        	<table width="100%" border="0" cellspacing="1" cellpadding="3">
        		<tr class="th">
            		<td colspan="2"><b>Die &Uuml;berpr&uuml;fung auf Verf&uuml;gbarkeit ergab:</b></td>
        		</tr>
        		<tr class="tr">

<?
    $go = true;

    $res = $db->query("select * from order_tld where tldid='".$_REQUEST[tld]."' ");
    $row = $db->fetch_array($res);

    $fulldomain = $_REQUEST[domain].$row['tld'];

    if($row["idnaktiv"]=="Y") { $fulldomain = domain_idnconvert($fulldomain); }

    if(strlen($_REQUEST[domain]) < $row['minlen']) { $go=false; }
    if(strlen($_REQUEST[domain]) > $row['maxlen']) { $go=false; }

    if($go!=false) {

		if($row[tld]==".de")
		    $o = domain_whois($row[whoisserver], "-T st,ace ".$fulldomain);
		else
		    $o = domain_whois($row[whoisserver], $fulldomain);

		if(strstr($o,$row["wortvergeben"])) {
		    $status = "vergeben";

			if(strpos($_SESSION["domainstoorder"], $fulldomain)===false){
				echo "<td width=\"300\">$fulldomain <font color=\"red\">(vergeben)</font>.</td><td>"; if($row["kkaktiv"]=="Y") { echo " <a href=\"order_whois.php?orderd=true&paketid=$_REQUEST[paketid]&domain=$fulldomain{KK}\">Domain transferieren (KK)</a><br>"; }
			}
			elseif(strpos($_SESSION["domainstoorder"], $fqdn)===false)
			{
				echo "<td width=\"300\">$fulldomain <font color=\"red\">(vergeben)</font>.</td><td>"; if($row["kkaktiv"]=="Y") { echo " <a href=\"order_whois.php?orderd=true&paketid=$_REQUEST[paketid]&domain=$fulldomain{KK}\">Domain transferieren (KK)</a><br>"; }
			}
			else
			{
				echo "<td width=\"300\">$fulldomain <font color=\"red\">(vergeben)</font>.</td><td>Auf der Merkliste für KK<br>";
			}

		}
		elseif(ereg($row["ereg"], $_REQUEST[domain])){
	        $status = "invalid";
		    echo "<td width=\"300\" colspan=\"2\">$fulldomain <font color=\"red\"> (ung&uuml;ltig)</font>.</td>";
		}

		elseif(strstr($o,$row["wortinvalid"]))  {
		    $status = "invalid";
		    echo "<td width=\"300\" colspan=\"2\">$fulldomain <font color=\"red\">(ung&uuml;ltig)</font>.</td>";
		}
		elseif(strstr($o,$row["wortfrei"]) and $status != "invalid") {
		    $status = "frei";

			if(strpos($_SESSION["domainstoorder"], $fulldomain)===false){
				echo "<td width=\"300\">$fulldomain <font color=\"green\">(frei)</font>.</td><td><a href=\"order_whois.php?orderd=true&paketid=".$_REQUEST[paketid]."&domain=".$fulldomain."\">Domain hinzuf&uuml;gen</a><br>";
			}
			elseif(strpos($_SESSION["domainstoorder"], $fqdn)===false)
			{
				echo "<td width=\"300\">$fulldomain <font color=\"green\">(frei)</font>.</td><td><a href=\"order_whois.php?orderd=true&paketid=".$_REQUEST[paketid]."&domain=".$fulldomain."\">Domain hinzuf&uuml;gen</a><br>";
			}
			else
			{
				echo "<td width=\"300\">$fulldomain <font color=\"green\">(frei)</font>.</td><td>Auf der Merkliste<br>";
			}

		}


	//echo "<td width=\"300\">$fulldomain <font color=\"green\">(frei)</font>.</td><td><a href=\"order_whois.php?orderd=true&paketid=".$_REQUEST[paketid]."&domain=".$fulldomain."\">Buchen</a><br>";

    }
    else
    {
		echo "<td width=\"300\" colspan=\"2\"><font color=\"red\">Der Domainname ist ung&uuml;ltig.</font></td>";
    }
?>
            		</td>
        		</tr>
        	</table>
    	</td>
	</tr>
</table>

<br>

<table width="600" border="0" cellspacing="0" cellpadding="0">
    <tr class="tb">
    	<td>
        	<table width="100%" border="0" cellspacing="1" cellpadding="3">
        		<tr class="th">
            		<td colspan="2"><b>Weiterf&uuml;hrende Pr&uuml;fung:</b></td>
        		</tr>


<?

$ALTNTLD = array();
$res = $db->query("select * from order_tld order by pos ASC");
while($row = $db->fetch_array($res)) 
    if(strstr($row_artikel["tlds"],"|$row[tldid]|")) 
	if($row["tld"]==".de" or $row["tld"]==".net" or $row["tld"]==".org" or $row["tld"]==".com" or $row["tld"]==".org")															
	    $ALTNTLD[] = $row["tld"];


//$ALTNTLD = array(".de",".net",".org",".com",".biz");


foreach($ALTNTLD as $wertTLD) {

	$go = true;

	$res = $db->query("select * from order_tld where tld='".$wertTLD."' ");
	$row = $db->fetch_array($res);

	if ($_REQUEST[tld] != $row[tldid])
	{
 	    echo "<tr class='tr'>";

	    $fulldomain = $_REQUEST[domain].$row['tld'];

	    if($row["idnaktiv"]=="Y") { $fulldomain = domain_idnconvert($fulldomain); }

	    if(strlen($_REQUEST[domain]) < $row['minlen']) { $go=false; }
	    if(strlen($_REQUEST[domain]) > $row['maxlen']) { $go=false; }

	    if($go!=false) {


			if($row[tld]==".de")
			    $o = domain_whois($row[whoisserver], "-T st,ace ".$fulldomain);
			else
			    $o = domain_whois($row[whoisserver], $fulldomain);

			if(strstr($o,$row["wortvergeben"])) {
			    $status = "vergeben";
		   			if(strpos($_SESSION["domainstoorder"], $fulldomain)===false){
						echo "<td width=\"300\">$fulldomain <font color=\"red\">(vergeben)</font>.</td><td>"; if($row["kkaktiv"]=="Y") { echo " <a href=\"order_whois.php?orderd=true&paketid=$_REQUEST[paketid]&domain=$fulldomain{KK}\">Domain transferieren (KK)</a><br>"; }
					}
					elseif(strpos($_SESSION["domainstoorder"], $fqdn)===false)
					{
						echo "<td width=\"300\">$fulldomain <font color=\"red\">(vergeben)</font>.</td><td>"; if($row["kkaktiv"]=="Y") { echo " <a href=\"order_whois.php?orderd=true&paketid=$_REQUEST[paketid]&domain=$fulldomain{KK}\">Domain transferieren (KK)</a><br>"; }
					}
					else
					{
						echo "<td width=\"300\">$fulldomain <font color=\"red\">(vergeben)</font>.</td><td>Auf der Merkliste für KK<br>";
					}
			}
			elseif(ereg($row["ereg"], $_REQUEST[domain])){
		            $status = "invalid";
			    echo "<td width=\"300\" colspan=\"2\">$fulldomain <font color=\"red\"> (ung&uuml;ltig)</font>.</td>";
			}

			elseif(strstr($o,$row["wortinvalid"]))  {
			    $status = "invalid";
			    echo "<td width=\"300\" colspan=\"2\">$fulldomain <font color=\"red\">(ung&uuml;ltig)</font>.</td>";
			}
			elseif(strstr($o,$row["wortfrei"]) and $status != "invalid") {
			    $status = "frei";

				if(strpos($_SESSION["domainstoorder"], $fulldomain)===false){
					echo "<td width=\"300\">$fulldomain <font color=\"green\">(frei)</font>.</td><td><a href=\"order_whois.php?orderd=true&paketid=".$_REQUEST[paketid]."&domain=".$fulldomain."\">Domain hinzuf&uuml;gen</a><br>";
				}
				elseif(strpos($_SESSION["domainstoorder"], $fqdn)===false)
				{
					echo "<td width=\"300\">$fulldomain <font color=\"green\">(frei)</font>.</td><td><a href=\"order_whois.php?orderd=true&paketid=".$_REQUEST[paketid]."&domain=".$fulldomain."\">Domain hinzuf&uuml;gen</a><br>";
				}
				else
				{
					echo "<td width=\"300\">$fulldomain <font color=\"green\">(frei)</font>.</td><td>Auf der Merkliste<br>";
				}

			}

	//echo "<td width=\"300\">$fulldomain <font color=\"green\">(frei)</font>.</td><td><a href=\"order_whois.php?orderd=true&paketid=".$_REQUEST[paketid]."&domain=".$fulldomain."\">Buchen</a><br>";

	    }
	    else
	    {
			echo "<td width=\"300\" colspan=\"2\"><font color=\"red\">Der Domainname ist ung&uuml;ltig.</font></td>";
	    }

	    echo "</td></tr>";
	}

}
?>


        	</table>
    	</td>
	</tr>
</table>


<?
}

echo $memo;

if($_REQUEST[deleted]=="true") {
    $_SESSION["selecteddomains"]--;
    $_SESSION["domainstoorder"] = str_replace($_REQUEST["domaintodel"].';',"",$_SESSION["domainstoorder"]);
}

?>
<br>
<table width="600" border="0" cellspacing="0" cellpadding="0">
<form action="order_whois.php?check=true&paketid=<?=$_REQUEST[paketid]?>" method="post">
	<tr class="tb">
		<td>

			<table width="100%" border="0" cellspacing="1" cellpadding="3">
				<tr class="th">
					<td colspan="2"><b>Meine Wunschdomain auf Verf&uuml;gbarkeit pr&uuml;fen...</b></td>
				</tr>
				<tr class="tr">
					<td>http://www.<input type="text" name="domain">
						<select name="tld">
							<?
							$res = $db->query("select * from order_tld order by pos ASC");
							while($row=$db->fetch_array($res)) {
								if(strstr($row_artikel["tlds"],"|$row[tldid]|")) {
							?>
								<option value="<?=$row["tldid"]?>"><?=$row["tld"]?></option>
							<?}}?>
						</select>
					</td>
					<td><input type="submit" value="Prüfen"></td>
				</tr>
			</table>
		</td>
	</tr>
</form>
</table>
<br><?
$tmpda = array();
$tmpda = explode(";",$_SESSION["domainstoorder"]);
// leere Eintraege loeschen
foreach($tmpda as $id => $value)
{
    if(!($value == "" || $value == NULL))
    {
        $da[] = $value;

    }
}
// Ende
if(count($da)>0) {
?>
<table width="600" border="0" cellspacing="0" cellpadding="0">
    <tr class="tb">
    	<td>

        	<table width="100%" border="0" cellspacing="1" cellpadding="3">
        		<tr class="th">
            		<td colspan="2"><b>Vorgemerkte Domains</b></td>
        		</tr>
<?
for($i=0;$i<count($da);$i++) {
	if($da[$i]!="") {
?>
        		<tr class="tr">
            		<td width="300"><?=$da[$i]?></td>
            		<td><a href="order_whois.php?deleted=true&domaintodel=<?=$da[$i]?>&paketid=<?=$_REQUEST[paketid]?>">Domain entfernen</a></td>
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
<?
}
?>

<?

 if($row_artikel['minanzdomains'] == 0 || $row_artikel['minanzdomains'] <= $_SESSION['selecteddomains'] || count($da)>= $row_artikel['minanzdomains']) {?>
<form action="order_addons.php" method="get">
<input type="hidden" name="paketid" value="<?=$_REQUEST[paketid]?>">
<input type="submit" value="Weiter">
</form>
<?} else {
    if($row_artikel['minanzdomains'] == 1)
    {
?>

F&uuml;r dieses Paket ist eine Domain erforderlich!
<?
    }
    else
    {
?>
F&uuml;r dieses Paket sind <?=$row_artikel['minanzdomains']?> Domains erforderlich!
<?
    }
}
?>

<?
include("footer.php");
} // Ende if vom anfang
?>
