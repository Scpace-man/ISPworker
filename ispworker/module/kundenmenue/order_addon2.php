<?
/*****************************************************************************************************/
/*	CHANGES 28.03.2006, sm
/*	Zeile 43-47:
/*		- Darstellung der individuelle Abrechnungszeiträume in der E-Mail überarbeitet
/*****************************************************************************************************/

$module = basename(dirname(__FILE__));
include("../../header.php");
include("../order/inc/functions.inc.php");
?>

<b>Addon bestellen</b><br>
<br>
<?

if(!isset($_REQUEST[produktid]) or $_REQUEST[produktid]=="") {
    echo "Kein Produkt ausgwählt.";
    include("../../footer.php");
    die();
}


$resb = $db->query("select * from order_settings");
$rowb = $db->fetch_array($resb);

$resk = $db->query("select anrede, nachname, mail from biz_kunden where kundenid='".$_SESSION['user']."' ");
$rowk = $db->fetch_array($resk);

$bestaetigungstext = "
Sehr geehrte(r) $rowk[anrede] $rowk[nachname],

vielen Dank für Ihre Bestellung.

Sie bestellen:

";

				    $res = $db->query("select * from biz_produkte where produktid='$_REQUEST[produktid]'");
				    $row = $db->fetch_array($res);
				    $p_produkte .= " $row[bezeichnung]";
					$abrechnung=explode(":",$row[abrechnung]);
					if($abrechnung[0]=="indiv"){
						$p_produkte.= " ($row[preis] EUR alle $abrechnung[1] Monate )";
					}else{
						$p_produkte.= " ($row[preis] EUR $row[abrechnung] )";
					}
				    $b .= "1:$row[produktid];";

				    $p_produkte .= "\n";
				    $link = "";



$bestaetigungstext .= "
$p_produkte

$rowb[btext]
";




$pwd = makepwd();
$date = date("Y-m-d H:i:s");

$db->query("insert into biz_bestellungen (kundenid,produkte,statusid,domains,datum) values ('".$_SESSION[user]."','$b','1','$_REQUEST[fulldomain]','$date')");

$bid = $db->insert_id();

$resj = $db->query("select * from order_jobs where jobproductid='$_REQUEST[paketid]' order by jobid");
while($rowj = $db->fetch_array($resj)) {
    if($rowj[manuell]=="Y") {
	$jobs .= "$rowj[jobbezeichnung]: $_REQUEST[ispworkerurl]"."module/order/jobexec.php?jobid=$rowj[jobid]&bestellid=$bid&passwort=&ip=\n";
    }
    else {
	$o = shell_exec("$rowj[jobbezeichnung]: $_REQUEST[ispworkerurl]"."module/order/jobexec.php?jobid=$rowj[jobid]&bestellid=$bid&passwort=&ip=");
    }
}

if($rowb[bsendmail]=="Y") {

	if(mail($rowk[mail],$rowb[bbetreff],$bestaetigungstext, "From: ".$rowb[babsendermail])){
	    echo "Vielen Dank für Ihre Bestellung, eine Bestätigungsmail wurde an Ihre Mailadresse $rowk[mail] verschickt.";
	}else{
	   echo "Fehler beim Versenden der Mail an $rowk[mail].";
	}

}

mail(CONF_MAILFROM,"Neue Bestellung $bid","$bestaetigungstext"."\n\nManuelle Jobs:\n\n$_REQUEST[jobs]\n", "From: $rowb[babsendermail]");

?>
<br>
<br>
<br>

<br>
<br>

<br>

<?
include("../../footer.php");
?>
