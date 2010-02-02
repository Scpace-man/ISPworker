<?include("header.php");

$biz_settings   = $db->queryfetch("select * from biz_settings");
$order_settings = $db->queryfetch("select * from order_settings");

/*

Paypal liefert momentan das Feld custom nicht zurueck.

$customdata = explode("&", $_REQUEST[custom]);
for($i = 0; $i < count($customdata);$i++) 
{
    $x = explode("=",$customdata[$i]);
    $$x[0] = $x[1];
}
*/

/*

$kidarr = explode("=",$customdata[0]);
$bidarr = explode("=",$customdata[1]);

$kid = $kidarr[1];
$bid = $bidarr[1];
*/

/**
Rückgabewerte Paypal:
$_POST[payment_status]
    |- "Completed"
        |- "Pending"
    $_POST[mc_gross]
        |- bezahlter Betrag
    $_POST[custom]
        |- frei definierbarer Wert (festgelegt in order_formuebersicht.php)
**/


$kunde = $db->queryfetch("select * from biz_kunden where kundenid='".$_REQUEST["kid"]."' ");

?>


<h3>Ihre Paypal Zahlung</h3>
<hr size="1" noshade>
<br>

<?
if($_REQUEST["order_aborting"]=="true") {?>
Die Zahlung per Paypal wurde abgebrochen. 
Bitte versuchen Sie es erneut oder kontaktieren Sie unseren Support mit Angabe der Bestellnummer <b><?=$_REQUEST[bid]?></b>.
<?
    $restpl = $db->query("select * from biz_mailtemplates where templatename='std_paypal_abgebrochen'");
    $rowtpl = $db->fetch_array($restpl);

    $mailbetreff = $rowtpl[mailbetreff];
    $mailtext    = $rowtpl[mailtext];

    $mailbetreff = str_replace("#rechnungid#",$_REQUEST[bid],$mailbetreff);
    $mailtext    = str_replace("#rechnungid#",$_REQUEST[bid],$mailtext);
    $mailbetreff = str_replace("#rechnungid#",$_REQUEST[kid],$mailbetreff);
    $mailtext    = str_replace("#rechnungid#",$_REQUEST[kid],$mailtext);

    mail($_SESSION[d_mail],$mailbetreff,$mailtext, "From: ".$order_settings[babsender]." <".$order_settings[babsendermail].">");
}
?>

<?if($_REQUEST["order_success"]=="true") {?>
Wir danken für Ihre Zahlung in Höhe von <?=$_REQUEST["mc_gross"]?> <?=$biz_settings["ppwaehrung"]?> für Ihre Bestellung Nr. <b><?=$_REQUEST[bid]?></b>.
<?
    $restpl = $db->query("select * from biz_mailtemplates where templatename='std_paypal_bestaetigung'");
    $rowtpl = $db->fetch_array($restpl);

    $mailbetreff = $rowtpl[mailbetreff];
    $mailtext    = $rowtpl[mailtext];

    $mailbetreff = str_replace("#rechnungid#",$_REQUEST[bid],$mailbetreff);
    $mailtext    = str_replace("#rechnungid#",$_REQUEST[bid],$mailtext);
    $mailbetreff = str_replace("#rechnungid#",$_REQUEST[kid],$mailbetreff);
    $mailtext    = str_replace("#rechnungid#",$_REQUEST[kid],$mailtext);

    mail($_SESSION[d_mail],$mailbetreff,$mailtext, "From: ".$order_settings[babsender]." <".$order_settings[babsendermail].">");
}
?>


<?
if($_REQUEST["invoice_aborting"]=="true") {?>
Die Zahlung per Paypal wurde abgebrochen. 
Bitte versuchen Sie es erneut oder kontaktieren Sie unseren Support mit Angabe der Rechnungsnummer <b><?=$_REQUEST[rid]?></b>.
<?
    $restpl = $db->query("select * from biz_mailtemplates where templatename='std_rechnungpaypal_abgebrochen'");
    $rowtpl = $db->fetch_array($restpl);

    $mailbetreff = $rowtpl[mailbetreff];
    $mailtext    = $rowtpl[mailtext];

    $mailbetreff = str_replace("#rechnungid#",$_REQUEST[rid],$mailbetreff);
    $mailtext    = str_replace("#rechnungid#",$_REQUEST[rid],$mailtext);

    mail($kunde["mail"],$mailbetreff,$mailtext, "From: ".$order_settings[babsender]." <".$order_settings[babsendermail].">");
}
?>

<?if($_REQUEST["invoice_success"]=="true") {?>
Wir danken für Ihre Zahlung in Höhe von <?=$_REQUEST["mc_gross"]?> <?=$biz_settings["ppwaehrung"]?> für Ihre Rechnung Nr. <b><?=$_REQUEST[rid]?></b>.
<?
    $restpl = $db->query("select * from biz_mailtemplates where templatename='std_rechnungpaypal_bestaetigung'");
    $rowtpl = $db->fetch_array($restpl);

    $mailbetreff = $rowtpl[mailbetreff];
    $mailtext    = $rowtpl[mailtext];

    $mailbetreff = str_replace("#rechnungid#",$_REQUEST[rid],$mailbetreff);
    $mailtext    = str_replace("#rechnungid#",$_REQUEST[rid],$mailtext);

    mail($kunde["mail"],$mailbetreff,$mailtext, "From: ".$order_settings[babsender]." <".$order_settings[babsendermail].">");
}
?>






<?include("footer.php");?>