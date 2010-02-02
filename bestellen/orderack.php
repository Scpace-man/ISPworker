<?include("header.php");?>

<h3>Bestellung erfolgreich</h3>
<hr size="1" noshade>
<br>

<?


$x = explode("-",$_REQUEST[id]);

$datum = date("Y-m-d H:i:s",$x[1]); 
$res   = $db->query("select * from biz_bestellungen where bestellid='$x[0]' and datum='$datum'");
$row   = $db->fetch_array($res);  

$respp = $db->query("select mail from biz_profile where profilid='1'");
$rowpp = $db->fetch_array($respp);



if($row[kundenid]!="") {
    @mail($rowpp["mail"],"Bestellung $x[0] durch Kunde bestätigt","Die Bestellung ".CONF_BASEHREF."module/biz/bestellung_show.php?bestellid=$x[0] wurde durch den Kunden bestätigt.","From: ".$rowpp[mail]);
    echo "Vielen Dank für Ihre Bestätigung - Sie erhalten innerhalb der nächsten 24 Stunden Nachricht von uns.";
}
?>
<br>

<br>

<?include("footer.php");?>
