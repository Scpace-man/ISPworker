<?
$module = basename(dirname(__FILE__));
include("../../header.php");

die("Dieses Script ist deaktiviert.");

$res = $db->query("select * from biz_kunden");

while($row = $db->fetch_array($res)) {
    
    if($row[bezahlart]=="rechnung") { 
	$k = "Bitte überweisen Sie den Rechnungsbetrag auf unser Konto binnen 7 Tagen.";
    }
    
    if($row[bezahlart]=="lastschrift") { 
	$k = "Der Rechnungsbetrag wird von Ihrem Konto $row[kontonummer], Blz $row[bankleitzahl] eingezogen.";
    }

    $db->query("update biz_rechnungtodo set kommentar='$k' where kundenid='$row[kundenid]'");

}


?>

<br>
<br>
<br>

<?include("../../../footer.php");?>
