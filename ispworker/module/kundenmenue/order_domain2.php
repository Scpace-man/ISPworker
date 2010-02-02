<?
$module = basename(dirname(__FILE__));
include("../../header.php");
include("../order/inc/functions.inc.php");


if($_REQUEST[agree]!="true") { echo "Sie müssen den Bedingungen zustimmen. <a href=\"module/kundenmenue/order_domain.php\">Zurück zur Übersicht.</a>"; include("../../footer.php"); die(); }

?>

<h3>Bestellung erfolgreich</h3>
<hr size="1" noshade>
<br>

<?

$resb = $db->query("SELECT * FROM order_settings");
$rowb = $db->fetch_array($resb);

$resk = $db->query("SELECT * FROM biz_kunden WHERE kundenid='".$_SESSION['user']."' ");
$rowk = $db->fetch_array($resk);


$bestaetigungstext = "
Sehr geehrte(r) $rowk[anrede] $rowk[nachname],

vielen Dank für Ihre Bestellung.

Sie bestellen:
";
			$j = 0;
			$da = explode(";",$_SESSION[domainstoorder]);
			if(count($da)>0) {

			    for($i=0;$i<count($da);$i++) {
				if($da[$i]!="") {
    				    $j++;
				    $ta = explode(".",$da[$i]);
				    
				    $tb = explode("{KK}",$ta[1]);
				    $tld = ".".$tb[0];
					
				    $fd = explode("{KK}",$da[$i]);
				    $fulldomain .= "$fd[0]";
				    
				    
				    $p_produkte .= "$da[$i]";

				    if($rowb[bkkvertrag]=="Y") {
				      if(strstr($da[$i],"{KK}")) {
					  $kkform = $rowb[formkk];
					  $ed = explode("{KK}",$da[$i]);
					  $kkform = str_replace("#domain#","$ed[0]",$kkform);
					  $docid = docid();
					  $time  = time();
					  
					  $db->query("INSERT INTO order_docs (docid,doc,time) VALUES ('$docid','$kkform','$time')");
					  $link = "KK Formular: module/kundenmenue/order_showform.php?docid=$docid";
					  $hlinks .= "<a href=\"module/kundenmenue/order_showform.php?docid=$docid"."\">KK Formular für $ed[0]</a><br>";
				      }
				    }

				    $rest = $db->query("SELECT biz_produkte.produktid, biz_produkte.preis, biz_produkte.abrechnung 
							FROM biz_produkte, order_tld
							WHERe biz_produkte.produktid=order_tld.preis and order_tld.tld = '$tld'");
				    $rowt = $db->fetch_array($rest);
				    $p_produkte .= " ($rowt[preis] EUR $rowt[abrechnung])";
					
				    $b .= "1:$rowt[produktid];"; 
				    
				    // Jobs abarbeiten
				    order_execute_jobs("N",$rowt["produktid"],$da);
				    
				    $p_produkte .= "\n";
				    $link = "";
				}
			    }
			}



$bestaetigungstext .= "
$p_produkte

$bestelllink

$rowb[btext]
";




$pwd = makepwd();
$date = date("Y-m-d H:i:s");

$db->query("INSERT INTO biz_bestellungen (kundenid,produkte,statusid,domains,datum) VALUES ('".$_SESSION[user]."','$b','1','$fulldomain','$date')");

$bid = $db->insert_id();

if($rowb["bsendmail"]=="Y") 
{
    mail("$rowk[mail]","$rowb[bbetreff]","$bestaetigungstext", "From: $rowb[babsendermail]");
    echo "Vielen Dank für Ihre Bestellung, eine Bestätigungsmail wurde an Ihre Mailadresse $rowk[mail] verschickt.";
}
    
mail(CONF_MAILFROM,"Neue Bestellung $bid","$bestaetigungstext"."\n\nManuelle Jobs:\n\n$jobs\n", "From: $rowb[babsendermail]");


?>




<br>
<br>
<br>
<?
if($rowb[bvertrag]=="Y") {
    echo $hlinks;
    echo "<br>";
}

$_SESSION[end] = true;


?>
<br>
<br>

<br>

<?include("../../footer.php");?>
