<?
/**************************************************************************/
/***		PAYPAL FORM, INCLUDED BY ORDER_FORMSAVE.PHP		***/
/**************************************************************************/
?>
<br>
<br>
Sie haben sich für die Zahlung per Paypal entschieden,
klicken Sie jetzt auf den unten stehenden Button, um die Zahlung in Höhe von <?=$_SESSION["d_paypal_total"]?> <?=$biz_settings["ppwaehrung"]?> vorzunehmen.
<br>
<form action="<?=CONF_BASEPAYPALURL?>" target="paypalwin" method="post">
<input type="hidden" name="cmd" value="_cart">
<input type="hidden" name="upload" value="1">
<input type="hidden" name="business" value="<?=$biz_settings["paypalmailaddress"]?>">
<input type="hidden" name="currency_code" value="<?=$biz_settings["ppwaehrung"]?>">
<input type="hidden" name="return" value="<?=CONF_BASEHREFBESTELLEN?>order_paypal_exit.php?order_success=true&bid=<?=$bid?>&kid=<?=$kid?>">
<input type="hidden" name="cancel_return" value="<?=CONF_BASEHREFBESTELLEN?>order_paypal_exit.php?order_aborting=true&bid=<?=$bid?>&kid=<?=$kid?>">
<input type="hidden" name="rm" value="2">
<input type="hidden" name="item_name_1" value="Bestellung <?=$bid?>, KdNr <?=$kid?>">
<input type="hidden" name="amount_1" value="<?=$_SESSION["d_paypal_total"]?>">
<input type="hidden" name="custom" value="<?="kid=".$kid."&bid=".$bid?>">
<br>
<input type="submit" value="Betrag per Paypal bezahlen">
</form>

<br>
<br>

<?include("footer.php");?>