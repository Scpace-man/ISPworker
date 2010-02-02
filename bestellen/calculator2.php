<?
include("header.php");
include("./include/calculator.class.php");
include("./include/calculator.inc.php");

if($cancel==true) {
    $res = $db->query("select * from order_artikel where kurztext='$id'");
    if($db->num_rows($res)==1) {
	$db->query("delete from biz_produkte where produktid='$paketid'");
	$db->query("delete from order_artikel where artikelid='$paketid'");
    }
}


?>


<script language="JavaScript">
<!--
function Go(x)
{
    parent.location.href = x;
    document.forms[0].reset();
    document.forms[0].elements[0].blur();
}
//-->
</script>



<h3>Kalkulator</h3>
<hr size="1" noshade>
<br>



<?
if($kategorie=="") {
    $kategorie = 0;
}
?>
<form name="calc" action="calculator_order.php?kategorie=<?=$kategorie?>" method="post">
<SELECT SIZE="1" NAME="Auswahl" CLASS="select" onChange="Go(this.form.Auswahl.options[this.form.Auswahl.options.selectedIndex].value)">

<?

for($i=0;$i<count($kategorien);$i++) {
    $temp = $kategorien[$i];

    if($i==$kategorie) { $c = "selected"; } else { $c = ""; }
    ?>
    <option value="<?=CONF_BASEHREFBESTELLEN?>calculator.php?kategorie=<?=$i?>" <?=$c?>><?=$temp->name?></option>
    <?
}

$k = $kategorien[$kategorie];
?>
</SELECT>

<br>
<br>

<?if($k->article_webspace_min!=false) {?>
<span class="title">Webspace</span>
<table border="0" cellpadding="0" cellspacing="0">
<tr>
  <td><?=$k->article_webspace_min?></td>
  <td><div class="slider" id="slider-webspace" tabIndex="1"><input id="slider-input-webspace" name="webspace"/></div></td>
  <td><?=$k->article_webspace_max?></td>
</tr>
</table>
<table border="0" cellpadding="0" cellspacing="1">
<tr>
  <td width="12"></td>
  <td width="130"><span id="number-webspace"><?=$k->article_webspace_min?></span> Einheiten</td>
  <td width="60" align="right"><span id="price-webspace">0,00</span></td>
  <td width="4"></td>
  <td>Euro</td>
</tr>
</table>
<br>
<?}?>


<?if($k->article_traffic_min!=false) {?>
<span class="title">Traffic</span>
<table border="0" cellpadding="0" cellspacing="0">
<tr>
  <td><?=$k->article_traffic_min?></td>
  <td><div class="slider" id="slider-traffic" tabIndex="1"><input id="slider-input-traffic" name="traffic"/></div></td>
  <td><?=$k->article_traffic_max?></td>
</tr>
</table>
<table border="0" cellpadding="0" cellspacing="1">
<tr>
  <td width="12"></td>
  <td width="130"><span id="number-traffic"><?=$k->article_traffic_min?></span> Einheiten</td>
  <td width="60" align="right"><span id="price-traffic">0,00</span></td>
  <td width="4"></td>
  <td>Euro</td>
</tr>
</table>
<br>
<?}?>


<?if($k->article_subdomain_min==false) { $disabled = "DISABLED"; } else { $disabled = "onClick=\"subdomainonchange()\""; }?>
<span class="title">Subdomains nutzen</span>
<table border="0" cellpadding="0" cellspacing="0">
<tr>
  <td><input type="checkbox" name="subdomainallow" id="subdomainallow" value="true" <?=$disabled?>> inklusive</td>
</tr>
</table>
<table border="0" cellpadding="0" cellspacing="1">
<tr>
  <td width="12"></td>
  <td width="130"></td>
  <td width="60" align="right"></td>
  <td width="4"></td>
  <td></td>
</tr>
</table>
<br>



<?if($k->article_subdomain_min!=false) {?>
<span class="title">Subdomains</span>
<table border="0" cellpadding="0" cellspacing="0">
<tr>
  <td><?=$k->article_subdomain_min?></td>
  <td><div class="slider" id="slider-subdomain" tabIndex="1"><input id="slider-input-subdomain" name="subdomain"/></div></td>
  <td><?=$k->article_subdomain_max?></td>
</tr>
</table>
<table border="0" cellpadding="0" cellspacing="1">
<tr>
  <td width="12"></td>
  <td width="130"><span id="number-subdomain"><?=$k->article_subdomain_min?></span> Einheiten</td>
  <td width="60" align="right"><span id="price-subdomain">0,00</span></td>
  <td width="4"></td>
  <td>Euro</td>
</tr>
</table>
<br>
<?}?>



<?if($k->article_mailaccount_min==false) { $disabled = "DISABLED"; } else { $disabled = "onClick=\"mailonchange()\""; }?>
<span class="title">Mail Kommunikation nutzen</span>
<table border="0" cellpadding="0" cellspacing="0">
<tr>
  <td><input type="checkbox" name="mailallow" id="mailallow" value="true" <?=$disabled?>> inklusive</td>
</tr>
</table>
<table border="0" cellpadding="0" cellspacing="1">
<tr>
  <td width="12"></td>
  <td width="130"></td>
  <td width="60" align="right"></td>
  <td width="4"></td>
  <td></td>
</tr>
</table>
<br>



<?if($k->article_mailaccount_min!=false) {?>
<span class="title">Mail Accounts</span>
<table border="0" cellpadding="0" cellspacing="0">
<tr>
  <td><?=$k->article_mailaccount_min?></td>
  <td><div class="slider" id="slider-mailaccount" tabIndex="1"><input id="slider-input-mailaccount" name="mailaccount"/></div></td>
  <td><?=$k->article_mailaccount_max?></td>
</tr>
</table>
<table border="0" cellpadding="0" cellspacing="1">
<tr>
  <td width="12"></td>
  <td width="130"><span id="number-mailaccount"><?=$k->article_mailaccount_min?></span> Einheiten</td>
  <td width="60" align="right"><span id="price-mailaccount">0,00</span></td>
  <td width="4"></td>
  <td>Euro</td>
</tr>
</table>
<br>
<?}?>

<?if($k->article_mailforwarder_min!=false) {?>
<span class="title">Mail Weiterleitungen</span>
<table border="0" cellpadding="0" cellspacing="0">
<tr>
  <td><?=$k->article_mailforwarder_min?></td>
  <td><div class="slider" id="slider-mailforwarder" tabIndex="1"><input id="slider-input-mailforwarder" name="mailforwarder"/></div></td>
  <td><?=$k->article_mailforwarder_max?></td>
</tr>
</table>
<table border="0" cellpadding="0" cellspacing="1">
<tr>
  <td width="12"></td>
  <td width="130"><span id="number-mailforwarder"><?=$k->article_mailforwarder_min?></span> Einheiten</td>
  <td width="60" align="right"><span id="price-mailforwarder">0,00</span></td>
  <td width="4"></td>
  <td>Euro</td>
</tr>
</table>
<br>
<?}?>

<?if($k->article_autoresponder_min!=false) {?>
<span class="title">Mail Autoresponder</span>
<table border="0" cellpadding="0" cellspacing="0">
<tr>
  <td><?=$k->article_autoresponder_min?></td>
  <td><div class="slider" id="slider-autoresponder" tabIndex="1"><input id="slider-input-autoresponder" name="autoresponder"/></div></td>
  <td><?=$k->article_autoresponder_max?></td>
</tr>
</table>
<table border="0" cellpadding="0" cellspacing="1">
<tr>
  <td width="12"></td>
  <td width="130"><span id="number-autoresponder"><?=$k->article_autoresponder_min?></span> Einheiten</td>
  <td width="60" align="right"><span id="price-autoresponder">0,00</span></td>
  <td width="4"></td>
  <td>Euro</td>
</tr>
</table>
<br>
<?}?>

<?if($k->article_shellaccount_min==false) { $disabled = "DISABLED"; } else { $disabled = "onClick=\"shellaccountonchange()\""; }?>
<span class="title">Shell Account / SSH Zugriff</span>
<table border="0" cellpadding="0" cellspacing="0">
<tr>
  <td><input type="checkbox" name="shellaccount" id="shellaccount" value="true" <?=$disabled?>> inklusive</td>
</tr>
</table>
<table border="0" cellpadding="0" cellspacing="1">
<tr>
  <td width="12"></td>
  <td width="130"></td>
  <td width="60" align="right"><span id="price-shellaccount">0,00</span></td>
  <td width="4"></td>
  <td>Euro</td>
</tr>
</table>
<br>

<?if($k->article_cronjob_min==false) { $disabled = "DISABLED"; } else { $disabled = "onClick=\"cronjobonchange()\""; }?>
<span class="title">Cronjob</span>
<table border="0" cellpadding="0" cellspacing="0">
<tr>
  <td><input type="checkbox" name="cronjob" id="cronjob" value="true" <?=$disabled?>> inklusive</td>
</tr>
</table>
<table border="0" cellpadding="0" cellspacing="1">
<tr>
  <td width="12"></td>
  <td width="130"></td>
  <td width="60" align="right"><span id="price-cronjob">0,00</span></td>
  <td width="4"></td>
  <td>Euro</td>
</tr>
</table>
<br>

<?if($k->article_mysql_min==false) { $disabled = "DISABLED"; } else { $disabled = "onClick=\"mysqlonchange()\""; }?>
<span class="title">MySQL nutzen</span>
<table border="0" cellpadding="0" cellspacing="0">
<tr>
  <td><input type="checkbox" name="mysqlallow" id="mysqlallow" value="true" <?=$disabled?>> inklusive</td>
</tr>
</table>
<table border="0" cellpadding="0" cellspacing="1">
<tr>
  <td width="12"></td>
  <td width="130"></td>
  <td width="60" align="right"></td>
  <td width="4"></td>
  <td></td>
</tr>
</table>
<br>



<?if($k->article_mysql_min!=false) {?>
<span class="title">MySQL Datenbanken</span>
<table border="0" cellpadding="0" cellspacing="0">
<tr>
  <td><?=$k->article_mysql_min?></td>
  <td><div class="slider" id="slider-mysql" tabIndex="1"><input id="slider-input-mysql" name="mysql"/></div></td>
  <td><?=$k->article_mysql_max?></td>
</tr>
</table>
<table border="0" cellpadding="0" cellspacing="1">
<tr>
  <td width="12"></td>
  <td width="130"><span id="number-mysql"><?=$k->article_mysql_min?></span> Einheiten</td>
  <td width="60" align="right"><span id="price-mysql">0,00</span></td>
  <td width="4"></td>
  <td>Euro</td>
</tr>
</table>
<br>
<?}?>


<?if($k->article_ftp_min!=false) {?>
<span class="title">Zusätzliche FTP Accounts</span>
<table border="0" cellpadding="0" cellspacing="0">
<tr>
  <td><?=$k->article_ftp_min?></td>
  <td><div class="slider" id="slider-ftp" tabIndex="1"><input id="slider-input-ftp" name="ftp"/></div></td>
  <td><?=$k->article_ftp_max?></td>
</tr>
</table>
<table border="0" cellpadding="0" cellspacing="1">
<tr>
  <td width="12"></td>
  <td width="130"><span id="number-ftp"><?=$k->article_ftp_min?></span> Einheiten</td>
  <td width="60" align="right"><span id="price-ftp">0,00</span></td>
  <td width="4"></td>
  <td>Euro</td>
</tr>
</table>
<br>
<?}?>


<hr size="1" noshade width="400" align="left"> 
<br>
<table border="0" cellpadding="0" cellspacing="1">
<tr>
  <td width="12"></td>
  <td width="130"><b>Summe:</b></td>
  <td width="60" align="right"><span id="summe">0,00</span></td>
  <td width="4"></td>
  <td>Euro</td>
</tr>
</table>
<br>




<script type="text/javascript">

    var endpreis = parseFloat(0.00);

    <?if($k->article_webspace_min!=false) {?>
    var s1 = new Slider(document.getElementById("slider-webspace"), document.getElementById("slider-input-webspace"));
    <?}?>
    
    <?if($k->article_traffic_min!=false) {?>
    var s2 = new Slider(document.getElementById("slider-traffic"), document.getElementById("slider-input-traffic"));
    <?}?>
    
    <?if($k->article_subdomain_min!=false) {?>
    var s3 = new Slider(document.getElementById("slider-subdomain"), document.getElementById("slider-input-subdomain"));
    <?}?>
    
    <?if($k->article_mailaccount_min!=false) {?>
    var s4 = new Slider(document.getElementById("slider-mailaccount"), document.getElementById("slider-input-mailaccount"));
    <?}?>
    
    <?if($k->article_mailforwarder_min!=false) {?>    
    var s5 = new Slider(document.getElementById("slider-mailforwarder"), document.getElementById("slider-input-mailforwarder"));
    <?}?>
    
    <?if($k->article_autoresponder_min!=false) {?>    
    var s6 = new Slider(document.getElementById("slider-autoresponder"), document.getElementById("slider-input-autoresponder"));
    <?}?>
    
    <?if($k->article_mysql_min!=false) {?>    
    var s7 = new Slider(document.getElementById("slider-mysql"), document.getElementById("slider-input-mysql"));
    <?}?>
    
    <?if($k->article_ftp_min!=false) {?>
    var s8 = new Slider(document.getElementById("slider-ftp"), document.getElementById("slider-input-ftp"));
    <?}?>
    
    function calctotal(s) {
    	endpreis =
	0.00 
	
	<?if($k->article_webspace_min!=false) {?>
	+ parseFloat(document.getElementById("price-webspace").firstChild.data)
	<?}?>
	
	<?if($k->article_traffic_min!=false) {?>
	+ parseFloat(document.getElementById("price-traffic").firstChild.data)
	<?}?>
	
	<?if($k->article_subdomain_min!=false) {?>
	+ parseFloat(document.getElementById("price-subdomain").firstChild.data)
	<?}?>
	
	<?if($k->article_mailaccount_min!=false) {?>
	+ parseFloat(document.getElementById("price-mailaccount").firstChild.data)
	<?}?>
	
	<?if($k->article_mailforwarder_min!=false) {?>
	+ parseFloat(document.getElementById("price-mailforwarder").firstChild.data)
	<?}?>
	
	<?if($k->article_autoresponder_min!=false) {?>
	+ parseFloat(document.getElementById("price-autoresponder").firstChild.data)
	<?}?>
	
	<?if($k->article_shellaccount_min!=false) {?>
	+ parseFloat(document.getElementById("price-shellaccount").firstChild.data)
	<?}?>
	
	<?if($k->article_cronjob_min!=false) {?>
	+ parseFloat(document.getElementById("price-cronjob").firstChild.data)
	<?}?>
	
	<?if($k->article_mysql_min!=false) {?>
	+ parseFloat(document.getElementById("price-mysql").firstChild.data)
	<?}?>
	
	<?if($k->article_ftp_min!=false) {?>
	+ parseFloat(document.getElementById("price-ftp").firstChild.data);
	<?}?>
	+ 0.00;
	
	document.getElementById("summe").firstChild.data = endpreis.toFixed(2);
    };

    <?if($k->article_webspace_min!=false) {?>
    s1.onchange = function () {
	document.getElementById("number-webspace").firstChild.data = s1.getValue();
	var price = <?=$k->getprice($k->article_webspace)?>;
	var total = parseFloat(price) * parseInt(s1.getValue());
	document.getElementById("price-webspace").firstChild.data = total.toFixed(2);
	calctotal(total);
    };
    <?}?>

    <?if($k->article_traffic_min!=false) {?>
    s2.onchange = function () {
	document.getElementById("number-traffic").firstChild.data = s2.getValue();
	var price = <?=$k->getprice($k->article_traffic)?>;
	var total = parseFloat(price) * parseInt(s2.getValue());
	document.getElementById("price-traffic").firstChild.data = total.toFixed(2);
	calctotal(total);
    };
    <?}?>
    
    <?if($k->article_subdomain_min!=false) {?>
    s3.onchange = function () {
	document.getElementById("number-subdomain").firstChild.data = s3.getValue();
	if(document.calc.subdomainallow.checked==true) {
	    var price = <?=$k->getprice($k->article_subdomain)?>;
	    var total = parseFloat(price) * parseInt(s3.getValue());
	    document.getElementById("price-subdomain").firstChild.data = total.toFixed(2);
	    calctotal(total);
	}
    };
    <?}?>
    
    <?if($k->article_mailaccount_min!=false) {?>
    s4.onchange = function () {
	document.getElementById("number-mailaccount").firstChild.data = s4.getValue();
	if(document.calc.mailallow.checked==true) {
	    var price = <?=$k->getprice($k->article_mailaccount)?>;
	    var total = parseFloat(price) * parseInt(s4.getValue());
	    document.getElementById("price-mailaccount").firstChild.data = total.toFixed(2);
	    calctotal(total);
	}	
    };
    <?}?>

    <?if($k->article_mailforwarder_min!=false) {?>
    s5.onchange = function () {
	document.getElementById("number-mailforwarder").firstChild.data = s5.getValue();
	if(document.calc.mailallow.checked==true) {
	    var price = <?=$k->getprice($k->article_mailforwarder)?>;
	    var total = parseFloat(price) * parseInt(s5.getValue());
	    document.getElementById("price-mailforwarder").firstChild.data = total.toFixed(2);
	    calctotal(total);
	}    
	
    };
    <?}?>

    <?if($k->article_autoresponder_min!=false) {?>
    s6.onchange = function () {
	document.getElementById("number-autoresponder").firstChild.data = s6.getValue();
	if(document.calc.mailallow.checked==true) {
	    var price = <?=$k->getprice($k->article_autoresponder)?>;
	    var total = parseFloat(price) * parseInt(s6.getValue());
	    document.getElementById("price-autoresponder").firstChild.data = total.toFixed(2);
	    calctotal(total);
	}
    };
    <?}?>
    
    <?if($k->article_shellaccount_min!=false) {?>
    function shellaccountonchange () {
	if(document.getElementById("shellaccount").checked==true) {
	    var price = <?=$k->getprice($k->article_shellaccount)?>;
	    var total = parseFloat(price);
	}
	else {
	    var total = parseFloat(0.0);
	}
	document.getElementById("price-shellaccount").firstChild.data = total.toFixed(2);
	calctotal(total);
    };
    <?}?>

    <?if($k->article_subdomain_min!=false) {?>
    function subdomainonchange () {
	if(document.getElementById("subdomainallow").checked==true) {	
    	    document.getElementById("number-subdomain").firstChild.data = s3.getValue();
	    var price = <?=$k->getprice($k->article_subdomain)?>;
	    var total = parseFloat(price) * parseInt(s3.getValue());
	}
	else {
	    var total = parseFloat(0.0);
	}
	document.getElementById("price-subdomain").firstChild.data = total.toFixed(2);
	calctotal(total);
    };
    <?}?>

    <?if($k->article_cronjob_min!=false) {?>
    function cronjobonchange () {
	if(document.getElementById("cronjob").checked==true) {
	    var price = <?=$k->getprice($k->article_cronjob)?>;
	    var total = parseFloat(price);
	}
	else {
	    var total = parseFloat(0.0);
	}
	document.getElementById("price-cronjob").firstChild.data = total.toFixed(2);
	calctotal(total);
    };
    <?}?>


    <?if($k->article_mysql_min!=false) {?>
    function mysqlonchange () {
	if(document.getElementById("mysqlallow").checked==true) {
	    document.getElementById("number-mysql").firstChild.data = s7.getValue();
	    var price = <?=$k->getprice($k->article_mysql)?>;
	    var total = parseFloat(price) * parseInt(s7.getValue());
	}
	else {
	    var total = parseFloat(0.0);
	}
	document.getElementById("price-mysql").firstChild.data = total.toFixed(2);
	calctotal(total);
    };
    <?}?>


    <?if($k->article_mailaccount_min!=false) {?>
    function mailonchange () {
	if(document.getElementById("mailallow").checked==true) {
	    document.getElementById("number-mailaccount").firstChild.data = s4.getValue();
	    var price1 = <?=$k->getprice($k->article_mailaccount)?>;
	    var total1 = parseFloat(price1) * parseInt(s4.getValue());

	    document.getElementById("number-mailforwarder").firstChild.data = s5.getValue();
	    var price2 = <?=$k->getprice($k->article_mailforwarder)?>;
	    var total2 = parseFloat(price2) * parseInt(s5.getValue());

	    document.getElementById("number-autoresponder").firstChild.data = s6.getValue();
	    var price3 = <?=$k->getprice($k->article_autoresponder)?>;
	    var total3 = parseFloat(price3) * parseInt(s6.getValue());

	}
	else {
	    var total1 = parseFloat(0.0);
	    var total2 = parseFloat(0.0);
	    var total3 = parseFloat(0.0);
	}
	document.getElementById("price-mailaccount").firstChild.data = total1.toFixed(2);
	document.getElementById("price-mailforwarder").firstChild.data = total2.toFixed(2);
	document.getElementById("price-autoresponder").firstChild.data = total3.toFixed(2);


	calctotal(total1);
    };
    <?}?>




    
    <?if($k->article_mysql_min!=false) {?>
    s7.onchange = function () {
	document.getElementById("number-mysql").firstChild.data = s7.getValue();
	if(document.calc.mysqlallow.checked==true) {
	    var price = <?=$k->getprice($k->article_mysql)?>;
	    var total = parseFloat(price) * parseInt(s7.getValue());
	    document.getElementById("price-mysql").firstChild.data = total.toFixed(2);
	    calctotal(total);
	}
    };
    <?}?>

    <?if($k->article_ftp_min!=false) {?>
    s8.onchange = function () {
	document.getElementById("number-ftp").firstChild.data = s8.getValue();
	var price = <?=$k->getprice($k->article_ftp)?>;
	var total = parseFloat(price) * parseInt(s8.getValue());
	document.getElementById("price-ftp").firstChild.data = total.toFixed(2);
	calctotal(total);
    };
    <?}?>

    <?if($k->article_webspace_min!=false) {?>
    s1.setValue(1);
    s1.setMinimum(<?=$k->article_webspace_min?>);
    s1.setMaximum(<?=$k->article_webspace_max?>);
    <?}?>

    <?if($k->article_traffic_min!=false) {?>
    s2.setValue(1);
    s2.setMinimum(<?=$k->article_traffic_min?>);
    s2.setMaximum(<?=$k->article_traffic_max?>);
    <?}?>

    <?if($k->article_subdomain_min!=false) {?>
    s3.setValue(1);
    s3.setMinimum(<?=$k->article_subdomain_min?>);
    s3.setMaximum(<?=$k->article_subdomain_max?>);
    <?}?>
    
    <?if($k->article_mailaccount_min!=false) {?>    
    s4.setValue(1);
    s4.setMinimum(<?=$k->article_mailaccount_min?>);
    s4.setMaximum(<?=$k->article_mailaccount_max?>);
    <?}?>
    
    <?if($k->article_mailforwarder_min!=false) {?>
    s5.setValue(1);
    s5.setMinimum(<?=$k->article_mailforwarder_min?>);
    s5.setMaximum(<?=$k->article_mailforwarder_max?>);
    <?}?>
    
    <?if($k->article_autoresponder_min!=false) {?>
    s6.setValue(1);
    s6.setMinimum(<?=$k->article_autoresponder_min?>);
    s6.setMaximum(<?=$k->article_autoresponder_max?>);
    <?}?>
    
    <?if($k->article_mysql_min!=false) {?>
    s7.setValue(1);
    s7.setMinimum(<?=$k->article_mysql_min?>);
    s7.setMaximum(<?=$k->article_mysql_max?>);
    <?}?>
    
    <?if($k->article_ftp_min!=false) {?>
    s8.setValue(1);
    s8.setMinimum(<?=$k->article_ftp_min?>);
    s8.setMaximum(<?=$k->article_ftp_max?>);
    <?}?>

    window.onresize = function () {
        s.recalculate();
    };
</script>


<br>
<br>
<input type="submit" name="submit" value="Speichern + Weiter">

</form>

<br>
<br>

<?include("footer.php");?>
