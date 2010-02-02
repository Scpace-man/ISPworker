<?
$error = false;
if(isset($_REQUEST['submit']))
{
    //echo "<pre>"; print_r($_REQUEST); exit;

    if(!$_REQUEST['agbok'])
    {
        $error = true;
        $err_agb = " class='error'";
    }

    if(!$_REQUEST['wdfok'])
    {
    	$error = true;
	$err_wdf = " class='error'";
    }

    if($_REQUEST['zahlungsart']=="lastschrift" && ($_REQUEST['kontoinhaber']=="" || 
       $_REQUEST['kontonummer']=="" || $_REQUEST['bankleitzahl']=="" || $_REQUEST['geldinstitut']==""))
    {
	$error = true;
        if($_REQUEST['kontoinhaber']=="")
            $err_kntoin = " class='error'";
        if($_REQUEST['kontonummer']=="")
            $err_kntonr = " class='error'";
        if($_REQUEST['bankleitzahl']=="")
            $err_blz = " class='error'";
        if($_REQUEST['geldinstitut']=="")
            $err_geld = " class='error'";
    }
    
    // Wenn kein Fehler im Formular, dann weiterleiten auf die Übersicht
    if(!$error)
    {
        header("Location: order_formuebersicht.php");
    }
}

include_once("header.php");

if(isset($_REQUEST['submit']))
{
    $_SESSION['d_agbok'] = $_REQUEST['agbok'];
    $_SESSION['d_zahlungsart'] = $_REQUEST['zahlungsart'];
    $_SESSION['d_kontoinhaber'] = $_REQUEST['kontoinhaber'];
    $_SESSION['d_kontonummer'] = $_REQUEST['kontonummer'];
    $_SESSION['d_bankleitzahl'] = $_REQUEST['bankleitzahl'];
    $_SESSION['d_geldinstitut'] = $_REQUEST['geldinstitut'];
    
    // Widerrufsbelehrung
    if($_REQUEST['wdfok']=="on")
	$_SESSION['d_wdfok'] = "bestätigt";
    else
	$_SESSION['d_wdfok'] = "nicht bestätigt";
	
    if($_REQUEST['wdfok2']=="on")
        $_SESSION['d_wdfok2'] = "bestätigt";
    else
	$_SESSION['d_wdfok2'] = "nicht bestätigt";
}
?>

<h3>Punkt 5: Zahlungsmethode</h3>
<hr size="1" noshade>

<form action="order_formzahlung.php" method="post">
<input type="hidden" name="paketid" value="<?=$_SESSION[paketid]?>">
<?

$res2 = $db->query("select paypalmailaddress,paypalfaktor from biz_settings");
$row2 = $db->fetch_array($res2);

$res = $db->query("select zahlungsmethoden from order_laender where isocode='".$_SESSION['d_isocode']."' ");
$row = $db->fetch_array($res);

$rechnung    = "";
$lastschrift = "";

switch($_SESSION['d_zahlungsart'])
{
    case("rechnung"):       	$rechnung 	= " checked"; break;
    case("lastschrift"):    	$lastschrift 	= " checked"; break;
    case("paypal"):		$paypal 	= " checked"; break;    
    default:                	$rechnung 	= " checked"; break;
}

if($_SESSION["d_bestandskunde"]==true) 
{
    $myres = $db->query("select bezahlart from biz_kunden
			where firma='".$_SESSION[d_firma]."' AND vorname='".$_SESSION[d_vorname]."' AND nachname='".$_SESSION[d_nachname]."'
	    		AND mail='".$_SESSION[d_mail]."' AND plz='".$_SESSION[d_plz]."' AND ort='".$_SESSION[d_ort]."' AND strasse='".$_SESSION[d_strasse]."' ");
	         
    $row = $db->fetch_array($myres);
    
    //$_SESSION['d_zahlungsart'] = "paypal";
?>
    <br>
    <table width="600" border="0" cellspacing="0" cellpadding="0">
    <tr class="tb">
	<td>
	
	<table width="100%" border="0" cellspacing="1" cellpadding="3">
	<tr class="th">
	    <td><b>Zahlungsart</b></td>
	</tr>
	<tr class="tr">
	    <td>Als Bestandskunde haben Sie Zahlungsart "<?=$row[bezahlart]?>" mit uns vereinbart.</td>
	</tr>
	</table>
	
	</td>
    </tr>
    </table>
<?
}

else {


if(strstr($row['zahlungsmethoden'],"rechnung") || $_SESSION['d_zahlungsart'] == "rechnung") 
{
?>
    <br>
    <table width="600" border="0" cellspacing="0" cellpadding="0">
    <tr class="tb">
	<td>
	
	<table width="100%" border="0" cellspacing="1" cellpadding="3">
	<tr class="th">
	    <td><b><input type="radio" name="zahlungsart" value="rechnung"<?=$rechnung?>> Zahlung per Rechnung</b></td>
	</tr>
	<tr class="tr">
  	    <td>Zahlung binnen 7 Tagen nach Rechnungserhalt.</td>
	</tr>
	</table>
	
	</td>
    </tr>
    </table>
<?
}
if(strstr($row['zahlungsmethoden'],"vorkasse") || $_SESSION['d_zahlungsart'] == "vorkasse") 
{
?>
    <br>
    <table width="600" border="0" cellspacing="0" cellpadding="0">
    <tr class="tb">
	<td>
	
	<table width="100%" border="0" cellspacing="1" cellpadding="3">
	<tr class="th">
	    <td><b><input type="radio" name="zahlungsart" value="vorkasse"<?=$rechnung?>> Zahlung per Vorkasse</b></td>
	</tr>
	</table>
	
	</td>
    </tr>
    </table>
<?
}
if(strstr($row['zahlungsmethoden'],"lastschrift") || $_SESSION['d_zahlungsart'] == "lastschrift") 
{
?>
    <br>
    <table width="600" border="0" cellspacing="0" cellpadding="0">
    <tr class="tb">
	<td>
	
	<table width="100%" border="0" cellspacing="1" cellpadding="3">
	<tr class="th">
	    <td colspan="2"><b><input type="radio" name="zahlungsart" value="lastschrift"<?=$lastschrift?>> Zahlung per Lastschriftverfahren</b></td>
	</tr>
	<tr class="tr">
	    <td<?=$err_kntoin?>>Kontoinhaber</td>
	    <td><input type="text" name="kontoinhaber" value="<?=$_SESSION['d_kontoinhaber']?>"></td>
	</tr>
	<tr class="tr">
	    <td<?=$err_kntonr?>>Kontonummer</td>
	    <td><input type="text" name="kontonummer" value="<?=$_SESSION['d_kontonummer']?>"></td>
	</tr>
	<tr class="tr">
	    <td<?=$err_blz?>>Bankleitzahl</td>
	    <td><input type="text" name="bankleitzahl" value="<?=$_SESSION['d_bankleitzahl']?>"></td>
	</tr>
	<tr class="tr">
	    <td<?=$err_geld?>>Geldinstitut</td>
	    <td><input type="text" name="geldinstitut" value="<?=$_SESSION['d_geldinstitut']?>"></td>
	</tr>
	</table>
	
	</td>
    </tr>
    </table>
<?
}
if((strstr($row['zahlungsmethoden'],"paypal") || $_SESSION['d_zahlungsart'] == "paypal") && $row2['paypalmailaddress']!='') 
{
?>
    <br>
    <table width="600" border="0" cellspacing="0" cellpadding="0">
    <tr class="tb">
	<td>
        
	<table width="100%" border="0" cellspacing="1" cellpadding="3">
	<tr class="th">
	    <td><b><input type="radio" name="zahlungsart" value="paypal"<?=$paypal?>> Zahlung per Paypal</b></td>
	</tr>
	</table>
	
	</td>
    </tr>
    </table>
<? 
}

} // ende else d_bestandskunde

?>
    <br>
    <table width="600" border="0" cellspacing="0" cellpadding="0">
    <tr class="tb">
	<td>
        <?$res = $db->query("select * from order_settings");
          $row = $db->fetch_array($res);
        ?>
	
        <table width="100%" border="0" cellspacing="1" cellpadding="3">
        <tr class="th">
	    <td><b>Widerrufsbelehrung für Verbraucher i.S. §13 BGB</b></td>
	</tr>
	<tr class="tr">
	    <td><textarea name="widerruf" cols="50" rows="10" style="width:590;" readonly><?=$row['widerruf']?></textarea></td>
	</tr>
	<tr class="tr">
	    <td<?=$err_wdf?>><input type="checkbox" name="wdfok">Ich habe die Widerrufsbelehrung verstanden und akzeptiere diese.</td>
	</tr>
	<tr class="tr">
	    <td><input type="checkbox" name="wdfok2">Ich verzichte auf mein Widerrufsrecht und bitte die Bestellung sofort auszuführen.</td>
	</tr>
        <tr class="th">
	    <td><b>Allgemeine Geschäftsbedingungen</b></td>
	</tr>		
	<tr class="tr">
	    <td><textarea name="agbtext" cols="50" rows="10" style="width:590;" readonly><?= $row['agbtext']?></textarea></td>
	<tr class="tr"> 
	    <td<?=$err_agb?>><input type="checkbox" name="agbok"> Ich bin mit den Allgemeinen Geschäftsbedingungen einverstanden</td>
	</tr>
	</table>
	
	</td>
    </tr>
    </table>

    <br>
    <input type="submit" name="submit" value="Weiter zur Bestellübersicht">			                      
    </form>
    <br>
    <br>
    <br>
    <br>

<?include("footer.php");?>