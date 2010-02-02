<?
/****************************************************/
/* CHANGES 28.03.2006, sm
/*	Zeile 68-69, 202-203:
/*		- Neue Ländergegebenheit implementiert
/****************************************************/
session_start();
include_once("include/config.inc.php");
include_once("include/common.inc.php");


if($_REQUEST[tldb]=='N')
{
    if($_SESSION['paketid'] =='') $_SESSION['paketid']=$_REQUEST['paketid'];
    
    $_SESSION["selecteddomains"] = 0;
    $_SESSION["domainstoorder"]  = "";
    $_SESSION["selecteddomains"] = 0;
}
else
{
    if($_SESSION['paketid'] =='') $_SESSION['paketid']=$_REQUEST['paketid'];
}


$error = false;

$err_vorname  = '';
$err_nachname = '';
$err_strasse  = '';
$err_plz      = '';
$err_ort      = '';
$err_email    = '';
$err_geb      = '';


// Domains von der Merkliste löschen
if($_REQUEST[deleted]=="true") 
{
    $_SESSION["selecteddomains"]--;
    $_SESSION["domainstoorder"] = str_replace("$domaintodel;","",$_SESSION["domainstoorder"]);
}


// Addons von der Merkliste löschen
if($_REQUEST[deleteaddon]=="true")
{
    $produkte     = explode(";",$_SESSION['paketid']);
    $anzprod      = count($produkte);
    $anzprodlimit = $anzprod - 1;
    
    for($n=0;$n<count($produkte);$n++)
    {
	if ($produkte[$n]== $addonid and $n < $anzprodlimit )  $_SESSION["paketid"] = str_replace("$addonid;","",$_SESSION["paketid"]);
        if ($produkte[$n]== $addonid and $n == $anzprodlimit ) $_SESSION["paketid"] = str_replace(";$addonid","",$_SESSION["paketid"]);
    }
}


// Wenn neu aufgerufen wurde: Neukunde default
$neukunde = " checked";
$altkunde = "";

/*$conn = mysql_connect(constant("CONF_DB_HOST"),constant("CONF_DB_USER"),constant("CONF_DB_PWD")) or die ("Datenbankverbindung nicht m&ouml;glich!");
@mysql_select_db(constant("CONF_DB_DATABASE")) or die ("Datenbank $datenbank konnte nicht ausgew&auml;hlt werden!");
*/

// Settings aus der Datenbank holen
$res  = $db->query("SELECT bestandskunden,firma,titel,mobil,fax,url,pkey,geb,zusatz1,zusatz2,zusatz3,zusatz1status,zusatz2status,
		    zusatz3status,nichtvolljaehrig FROM order_settings");

$data = $db->fetch_array($res);

$myres = $db->query("select * from biz_settings");
$biz_settings = $db->fetch_array($myres);

// - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// VALIDPRÜFUNGEN

if(isset($_REQUEST['submit']) && ($_REQUEST['typ']=="neu" || !isset($_REQUEST['typ'])))
{
    $res = $db->query("SELECT isocode,name FROM order_laender WHERE laenderid=".$_REQUEST['laenderid']);
    $dataneu = $db->fetch_array($res);


    $neukunde = " checked";
    $altkunde = "";

    $_SESSION['d_firma'] 	= $_REQUEST['firma'];
    $_SESSION['d_anrede']       = $_REQUEST['anrede'];
    $_SESSION['d_titel'] 	= $_REQUEST['titel'];
    $_SESSION['d_vorname']      = $_REQUEST['vorname'];
    $_SESSION['d_nachname']     = $_REQUEST['nachname'];
    $_SESSION['d_tag'] 	        = $_REQUEST['tag'];
    $_SESSION['d_monat'] 	= $_REQUEST['monat'];
    $_SESSION['d_jahr'] 	= $_REQUEST['jahr'];
    $_SESSION['d_strasse']      = $_REQUEST['strasse'];
    $_SESSION['d_plz']	        = $_REQUEST['plz'];
    $_SESSION['d_ort']	        = $_REQUEST['ort'];
    $_SESSION['d_telefon']      = $_REQUEST['telefon'];
    $_SESSION['d_mobil']        = $_REQUEST['mobil'];
    $_SESSION['d_fax'] 	        = $_REQUEST['fax'];
    $_SESSION['d_mail']	        = $_REQUEST['mail'];
    $_SESSION['d_url']	        = $_REQUEST['url'];
    $_SESSION['d_key'] 	        = $_REQUEST['pkey'];
    $_SESSION['d_isocode']      = $dataneu['isocode'];
    $_SESSION['d_zusatz1'] 	= $_REQUEST['zusatz1'];
    $_SESSION['d_zusatz2'] 	= $_REQUEST['zusatz2'];
    $_SESSION['d_zusatz3'] 	= $_REQUEST['zusatz3'];

    if($data['firma'] == "aktivpflicht" && !validlength($_REQUEST['firma'], 2))
    {
        $error = true;
    	$err_firma = ' class="error"';
    }
    if($data['titel'] == "aktivpflicht" && !validlength($_REQUEST['firma'], 2))
    {
        $error = true;
        $err_titel = ' class="error"';
    }
    if($data['geb'] != "inaktiv")
    {
        $nichtvolljaehrig = false;
        if($data['geb'] == "aktivpflicht" && !checkdate($_REQUEST['monat'],$_REQUEST['tag'],$_REQUEST['jahr']))
        {
    	   $error = true;
    	   $err_datum = ' class="error"';
        }
        elseif($data['geb'] == "aktiv" && ($_SESSION['d_jahr'] == "" || $_SESSION['d_monat'] == "" || $_SESSION['d_tag'] == "") && !($_SESSION['d_jahr'] == "" && $_SESSION['d_monat'] == "" && $_SESSION['d_tag'] == ""))
        {
            $error = true;
    	    $err_datum = ' class="error"';
        }
        elseif($data['geb'] == "aktivvoll")
        {
	    $today = mktime(0,0,0,date("n",time()),date("j",time()),date("Y",time())-18);
	    $birth = mktime(0,0,0,$_REQUEST['monat'],$_REQUEST['tag'],$_REQUEST['jahr']);
	    if($today<$birth || $birth==-1)
	    {
	        $error = true;
    	        $err_datum = ' class="error"';
    	        $nichtvolljaehrig = true;
	    }
        }
    }
    if(!validlength($_REQUEST['vorname'], 2))
    {
    	$error = true;
    	$err_vorname = ' class="error"';
    }
    if(!validlength($_REQUEST['nachname'], 2))
    {
    	$error = true;
    	$err_nachname = ' class="error"';
    }
    if(!validlength($_REQUEST['strasse'], 2))
    {
    	$error = true;
    	$err_strasse = ' class="error"';
    }
    if(!validlength($_REQUEST['telefon'], 5))
    {
    	$error = true;
    	$err_telefon = ' class="error"';
    }
    if($data['mobil'] == "aktivpflicht" && !validlength($_REQUEST['mobil'], 5))
    {
    	$error = true;
    	$err_mobil = ' class="error"';
    }
    if($data['fax'] == "aktivpflicht" && !validlength($_REQUEST['fax'], 5))
    {
    	$error = true;
    	$err_fax = ' class="error"';
    }
    if(!validplz($_REQUEST['plz']))
    {
    	$error = true;
    	$err_plz = ' class="error"';
    }
    if(!validlength($_REQUEST['ort'], 2))
    {
    	$error = true;
    	$err_ort = ' class="error"';
    }
    if($data['url'] == "aktivpflicht" && !validlength($_REQUEST['url'], 2))
    {
    	$error = true;
    	$err_url = ' class="error"';
    }
    if($data['zusatz1status'] == "aktivpflicht" && !validlength($_REQUEST['zusatz1'], 2))
    {
    	$error = true;
    	$err_zusatz1 = ' class="error"';
    }
    if($data['zusatz2status'] == "aktivpflicht" && !validlength($_REQUEST['zusatz2'], 2))
    {
    	$error = true;
    	$err_zusatz2 = ' class="error"';
    }
    if($data['zusatz3status'] == "aktivpflicht" && !validlength($_REQUEST['zusatz3'], 2))
    {
    	$error = true;
    	$err_zusatz3 = ' class="error"';
    }
    if(!validemail($_REQUEST['mail']))
    {
    	$error = true;
    	$err_email = ' class="error"';
    }
}
elseif((isset($_REQUEST['submit']) && $_REQUEST['typ']=="alt") || $_SESSION["d_bestandskunde"] == true)
{
    $neukunde="";
    $altkunde=" checked";

    $_SESSION['d_bestandskunde']  = true;
    $_SESSION['d_email']          = $_REQUEST['email'];

    $res = $db->query("SELECT firma,anrede,titel,vorname,nachname,geb_tag,geb_monat,geb_jahr,strasse,plz,ort,isocode,telefon,handy,fax,mail,url,
		       zusatz1,zusatz2,zusatz3,bezahlart,kontoinhaber,kontonummer,bankleitzahl,geldinstitut 
		       FROM biz_kunden 
		       WHERE mail='".$_REQUEST['email']."' 
		       AND passwort='".sha1($_REQUEST['passwort'])."' ");
		       
    if($databestandskunde = $db->fetch_array($res))
    {
        $_SESSION['d_alt'] 	        = true;
        $_SESSION['d_firma'] 	        = $databestandskunde['firma'];
        $_SESSION['d_anrede']           = $databestandskunde['anrede'];
        $_SESSION['d_titel'] 	        = $databestandskunde['titel'];
        $_SESSION['d_vorname']          = $databestandskunde['vorname'];
        $_SESSION['d_nachname']         = $databestandskunde['nachname'];
        $_SESSION['d_tag'] 	        = $databestandskunde['geb_tag'];
        $_SESSION['d_monat'] 	        = $databestandskunde['geb_monat'];
        $_SESSION['d_jahr'] 	        = $databestandskunde['geb_jahr'];
        $_SESSION['d_strasse']          = $databestandskunde['strasse'];
        $_SESSION['d_plz']	        = $databestandskunde['plz'];
        $_SESSION['d_ort']	        = $databestandskunde['ort'];
        $_SESSION['d_telefon']          = $databestandskunde['telefon'];
        $_SESSION['d_mobil']            = $databestandskunde['mobil'];
        $_SESSION['d_fax'] 		= $databestandskunde['fax'];
        $_SESSION['d_mail']	        = $databestandskunde['mail'];
        $_SESSION['d_url']              = $databestandskunde['url'];
        $_SESSION['d_isocode']         	= $databestandskunde['isocode'];
        $_SESSION['d_zusatz1'] 	        = $databestandskunde['zusatz1'];
        $_SESSION['d_zusatz2'] 	        = $databestandskunde['zusatz2'];
        $_SESSION['d_zusatz3'] 	        = $databestandskunde['zusatz3'];
        $_SESSION['d_bezahlart']        = $databestandskunde['bezahlart'];
        $_SESSION['d_kontoinhaber'] 	= $databestandskunde['kontoinhaber'];
        $_SESSION['d_kontonummer']	= $databestandskunde['kontonummer'];
        $_SESSION['d_bankleitzahl']	= $databestandskunde['bankleitzahl'];
        $_SESSION['d_geldinstitut'] 	= $databestandskunde['geldinstitut'];
    }
    else
    {
        $error = true;
        $err_mail = ' class="error"';
    }
}
// - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// VALIDPRÜFUNGEN - ENDE


// - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// VALIDFUNKTIONEN
function validemail($string)
{
    if (empty($string)) return false;
    $preg = "^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@([a-zA-Z0-9-]+\.)+([a-zA-Z]{2,4})$";

    preg_match("/$preg/", $string, $result);
    if ($string != $result[0]) return false;
    return true;
}


function validplz($number)
{
    if ($number!="" && $number > 100 && $number <= 99998 ) return true;
    else return false;
}


function validlength($string, $laenge)
{
    if (strlen($string) < $laenge) return false;
    return true;
}
// - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// VALIDFUNKTIONEN - ENDE

if(!$error && isset($_REQUEST['submit'])) header("Location: order_formzahlung.php");
else include("header.php");
?>

<h3>Punkt 4: Kundendaten <? if($error) echo '<span class="error">(Bitte f&uuml;llen Sie die Pflichtfelder ordnungsgem&auml;&szlig; aus!)</span>'; ?></h3>
<hr size="1" noshade>
<br>
<?
// Wenn die Person nicht Volljährig ist
if($nichtvolljaehrig) echo '<h3><span class="error">'.$data['nichtvolljaehrig'].'</span></h3>';
?>


<!--form action="order_formzahlung.php" method="get"-->

<form action="order_form.php" method="post">
<input type="hidden" name="paketid" value="<?=$_SESSION[paketid]?>">

<table width="600" border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
    <td>
    <table width="100%" border="0" cellspacing="1" cellpadding="3">
    <tr class="th">
	<td colspan="3"><b>Ausgew&auml;hlte Produkte</b></td>
    </tr>
    <?
    $produkte = explode(";",$_SESSION['paketid']);
    for($n=0;$n<count($produkte);$n++)
    {
	if($n==0)
	{
	    $inkldom=$produkte[$n];
	    $res = $db->query("select * from order_artikel where artikelid='".$produkte[$n]."' ");
	    $row_artikel = $db->fetch_array($res);
	    $mindestdom = $row_artikel[mindestanzdomains];
	}

	$res = $db->query("select bezeichnung,preis,abrechnung from biz_produkte where produktid='".$produkte[$n]."' ");
	$row = $db->fetch_array($res);

	$intervall = explode(":",$row['abrechnung']);
	if(count($intervall)>1) $rechnungsintervall = "alle ".$intervall[1]." Monate";
	else			$rechnungsintervall = $row['abrechnung'];
	
	$resein = $db->query("select biz_produkte.preis 
			      from biz_produkte,order_artikel 
			      where order_artikel.produkteinid=biz_produkte.produktid and order_artikel.artikelid='".$produkte[$n]."' ");
	
	$rowein = $db->fetch_array($resein);
	$rechnungsintervall = str_replace("jaehrlich", "j&auml;hrlich", $rechnungsintervall);
    ?>
    <tr class="tr">
	<td><?=stripslashes($row[bezeichnung])?></td>
	<td><?=$row[preis]." ".$biz_settings["waehrung"]." ".$rechnungsintervall?></td>
	<td width="16">
	<?
	if ($n=='0') 	echo "<img src='img/delinactive.gif'>";
	else 		echo "<a href='order_form.php?deleteaddon=true&addonid=$produkte[$n]'><img src='img/delactive.gif' border='0'></a>";
	?>
	</td>
    </tr>
	<?
	if($rowein['preis']!="") 
	{
	?>
	    <tr class="tr">
		<td>Einrichtung</td>
		<td colspan="2"><?=$rowein[preis]." ".$biz_settings["waehrung"]." einmalig"?></td>
	    </tr>
	<?
	} // if ende
    } // for ende

    $res = $db->query("select anzdomains,tldsmitaufpreis from order_artikel where artikelid='".$inkldom."' ");
    $row = $db->fetch_array($res);

    $j = 0;

    $da = explode(";",$_SESSION['domainstoorder']);
    $num = count($da);
    if($num > 0) 
    {
        for($i=0; $i < $num; $i++) 
	{
	    if($da[$i]!="") 
	    {
		$j++;
	        $ta  = explode(".",$da[$i]);
	        $tb  = explode("{KK}",$ta[1]);
	        $tld = ".".$tb[0];
    		
		
		
		if($j <= $row['anzdomains']) 
		{ 
		    $restld = $db->query("select tldid,aufpreis from order_tld where tld='$tld'");
		    $rowtld = $db->fetch_array($restld);

		    if(strstr($row['tldsmitaufpreis'],"|$rowtld[tldid]|")) 
		    {
		        $resp = $db->query("select preis,abrechnung from biz_produkte where produktid='$rowtld[aufpreis]'");
		        $rowp = $db->fetch_array($resp);
		        $rechnungsintervall = str_replace("jaehrlich", "j&auml;hrlich", $rowp['abrechnung']);
		    
			$str1 = $rowp["preis"]." ".$biz_settings["waehrung"]." Aufpreis ".$rechnungsintervall;
		    } else 
			$str1 = "inklusive";
		
		    if ($mindestdom >= count($da))
			$str2 = "<a href=\"order_form.php?deleted=true&domaintodel=".$da[$i]."&paketid=".$produkte[$n]."\"><img src=\"img/delactive.gif\" border=\"0\"></a>";
		    else
			$str2 = "<img src=\"img/delinactive.gif\">";
		}
		else 
		{
		    $rest = $db->query("select biz_produkte.preis, biz_produkte.abrechnung from biz_produkte,order_tld
			                where biz_produkte.produktid=order_tld.preis and order_tld.tld = '$tld'");
		    
		    $rowt = $db->fetch_array($rest);
		    $rechnungsintervall = str_replace("jaehrlich", "j&auml;hrlich", $rowt['abrechnung']);
		    $str1 = $rowt["preis"]." ".$biz_settings["waehrung"]." ".$rechnungsintervall;
		    $str2 = "<a href=\"order_form.php?deleted=true&domaintodel=".$da[$i]."&paketid=".$produkte[$n]."\"><img src=\"img/delactive.gif\" border=\"0\"></a>";
	        }
	    
		?>
		<tr class="tr">
    		    <td><?=$da[$i]?></td>
    		    <td><?=$str1?></td>
    		    <td width="16"><?=$str2?></td>
		</tr>
		<?
	    }
	}
    }
    ?>
    </table>    
</td>
</tr>
</table>
<?
if($data['bestandskunden'] == "aktiv") {
?>
<br>
<table width="600" border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
    <td>

    <table width="100%" border="0" cellspacing="1" cellpadding="3">
    <tr class="th">
	<td colspan="2"><input type="radio" name="typ" value="alt"<?=$altkunde?>><b>Sie sind bereits Kunde?</b></td>
    </tr>
    <tr class="tr">
	<td<?=$err_mail?>>Email *</td>
        <td><input type="text" name="email" value="<?=$_SESSION[d_email]?>" size="26"></td>
    </tr>
    <tr class="tr">
        <td<?=$err_mail?>>Passwort *</td>
        <td><input type="password" name="passwort" value="" size="26"></td>
    </tr>
    </table>

    </td>
</tr>
</table>
<?
}


if($_SESSION["d_bestandskunde"] == true)
{ 
    echo "<br><input type=\"submit\" name=\"submit\" value=\"Weiter zur Zahlungsmethode\"></form>"; 
    include("footer.php"); 
    die();
}
?>
<br>
<table width="600" border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
    <td>
    
    <table width="100%" border="0" cellspacing="1" cellpadding="3">
    <tr class="th">
    <?
    if($data['bestandskunden'] == "aktiv") {
    ?>
	<td colspan="2"><input type="radio" name="typ" value="neu"<?=$neukunde?>><b>Sie sind Neukunde?</b></td>
    <?
    }
    else 
    {
    ?>
        <td colspan="2"><b>Kundendaten</b></td>
    <?
    }
    ?>
    </tr>
    <?
    // Firma
    if($data['firma'] != "inaktiv")
    {
	$pflicht = "";
	if($data['firma'] == "aktivpflicht")
	    $pflicht = " *";
	?>
	<tr class="tr">
	    <td<?=$err_firma?>>Firma<?=$pflicht?></td>
  	    <td><input type="text" name="firma" value="<?=$_SESSION[d_firma]?>" size="26"></td>
	</tr>
	<?
    }
    
    // Ende Firma
    switch($_SESSION['d_anrede'])
    {
	case('frau'):
    	    $mann = "";
            $frau = " selected";
	break;
        
	case('mann'):
            $mann = " selected";
            $frau = "";
        break;
    }
    ?>
    <tr class="tr">
	<td>Anrede</td>
  	<td><select name="anrede"><option <?=$mann?>>Herr</option><option <?=$frau?>>Frau</option></select></td>
    </tr>
    <?
    // Titel
    if($data['titel'] != "inaktiv")
    {
	$pflicht = "";
	if($data['titel'] == "aktivpflicht")
	    $pflicht = " *";
    ?>
	<tr class="tr">
  	    <td<?=$err_titel?>>Titel<?=$pflicht?></td>
  	    <td><input type="text" name="titel" value="<?=$_SESSION[d_titel]?>" size="26"></td>
	</tr>
    <?
    }
    // Ende Titel
    ?>
    <tr class="tr">
  	<td><span<?=$err_vorname?>>Vorname *</span> / <span<?=$err_nachname?>>Nachname *</span></td>
  	<td><input type="text" name="vorname" value="<?=$_SESSION[d_vorname]?>" size="12"><input type="text" name="nachname" value="<?=$_SESSION[d_nachname]?>" size="12"></td>
    </tr>
    <?
    // Geburtsdatum
    if($data['geb'] != "inaktiv")
    {
	$pflicht = "";
	if($data['geb'] == "aktivpflicht" || $data['geb'] == "aktivvoll")
	    $pflicht = " *";
    ?>
	<tr class="tr">
  	    <td<?=$err_datum?>>Geburtsdatum<?=$pflicht?></td>
  	    <td><input type="text" name="tag" value="<?=$_SESSION[d_tag]?>" size="2" maxlength="2">.<input type="text" name="monat" value="<?=$_SESSION[d_monat]?>" size="2" maxlength="2">.<input type="text" name="jahr" value="<?=$_SESSION[d_jahr]?>" size="4" maxlength="4"></td>
	</tr>
    <?
    }
    // Ende Geburtsdatum
    ?>
    <tr class="tr">
    	<td<?=$err_strasse?>>Strasse *</td>
  	<td><input type="text" name="strasse" value="<?=$_SESSION[d_strasse]?>" size="26"></td>
    </tr>
    <tr class="tr">
  	<td><span<?=$err_plz?>>Plz *</span> / <span<?=$err_ort?>>Ort *</span></td>
  	<td><input type="text" name="plz" size="6" maxlength="5" value="<?=$_SESSION[d_plz]?>"><input type="text" name="ort" value="<?=$_SESSION[d_ort]?>" size="18"></td>
    </tr>
    <tr class="tr">
	<td>Land</td>
  	<td><select name="laenderid">
	<?
	$res = $db->query("select * from order_laender order by name");
	while($row = $db->fetch_array($res)) {
	    echo "<option value=\"$row[laenderid]\">$row[name]</option>\n";
	}
	?>
	</select></td>
    </tr>
    <tr class="tr">
	<td<?=$err_telefon?>>Telefon * &nbsp;<span style="font-size:10px;">(mit Vorwahl)</span></td>
  	<td><input type="text" name="telefon" value="<?=$_SESSION[d_telefon]?>" size="26"></td>
    </tr>
    <?
    // Mobil
    if($data['mobil'] != "inaktiv")
    {
	$pflicht = "";
	if($data['mobil'] == "aktivpflicht")
	    $pflicht = " *";
    ?>
	<tr class="tr">
	    <td<?=$err_mobil?>>Mobil<?=$pflicht?> &nbsp;<span style="font-size:10px;">(mit Vorwahl)</span></td>
  	    <td><input type="text" name="mobil" value="<?=$_SESSION[d_mobil]?>" size="26"></td>
	</tr>
    <?
    }
    // Ende Mobil
    // Fax
    if($data['fax'] != "inaktiv")
    {
	$pflicht = "";
	if($data['fax'] == "aktivpflicht")
	    $pflicht = " *";
    ?>
	<tr class="tr">
  	    <td<?=$err_fax?>>Fax<?=$pflicht?> &nbsp;<span style="font-size:10px;">(mit Vorwahl)</span></td>
  	    <td><input type="text" name="fax" value="<?=$_SESSION[d_fax]?>" size="26"></td>
	</tr>
    <?
    }
    // Ende Fax
    ?>
    <tr class="tr">
	<td<?=$err_email?>>E-Mail *</td>
  	<td><input type="text" name="mail" value="<?=$_SESSION[d_mail]?>" size="26"></td>
    </tr>
    <?
    // Website
    if($data['url'] != "inaktiv")
    {
	$pflicht = "";
	if($data['url'] == "aktivpflicht")
	    $pflicht = " *";
    ?>
	<tr class="tr">
  	    <td<?=$err_url?>>Website<?=$pflicht?></td>
  	    <td><input type="text" name="url" value="<?=$_SESSION[d_url]?>" size="26"></td>
	</tr>
    <?
    }
    // Ende Website
    // Produkt Key
    if($data['pkey'] != "inaktiv")
    {
	$pflicht = "";
	if($data['pkey'] == "aktivpflicht")
	    $pflicht = " *";
    ?>
	<tr class="tr">
	    <td<?=$err_pkey?>>Produkt Key<?=$pflicht?></td>
	    <td><input type="text" name="pkey" value="<?=$_SESSION[d_key]?>" size="26"><br><span style="font-size: 10px">(Nur bei Spezialangeboten)</span></td>
	</tr>
    <?
    }
    // Ende Produkt Key
    // Zusatz1
    if($data['zusatz1status'] != "inaktiv")
    {
	$pflicht = "";
	if($data['zusatz1status'] == "aktivpflicht")
	    $pflicht = " *";
    ?>
	<tr class="tr">
  	    <td<?=$err_zusatz1?>><?=$data['zusatz1']?><?=$pflicht?></td>
  	    <td><input type="text" name="zusatz1" value="<?=$_SESSION[d_zusatz1]?>" size="26"></td>
	</tr>
    <?
    }
    // Ende Zusatz1
    // Zusatz2
    if($data['zusatz2status'] != "inaktiv")
    {
	$pflicht = "";
	if($data['zusatz2status'] == "aktivpflicht")
	    $pflicht = " *";
    ?>
    	<tr class="tr">
	    <td<?=$err_zusatz2?>><?=$data['zusatz2']?><?=$pflicht?></td>
  	    <td><input type="text" name="zusatz2" value="<?=$_SESSION[d_zusatz2]?>" size="26"></td>
	</tr>
    <?
    }
    // Ende Zusatz2
    // Zusatz3
    if($data['zusatz3status'] != "inaktiv")
    {
	$pflicht = "";
	if($data['zusatz3status'] == "aktivpflicht")
	    $pflicht = " *";
    ?>
	<tr class="tr">
  	    <td<?=$err_zusatz3?>><?=$data['zusatz3']?><?=$pflicht?></td>
  	    <td><input type="text" name="zusatz3" value="<?=$_SESSION[d_zusatz3]?>" size="26"></td>
	</tr>
    <?
    }
    // Ende Zusatz3
    ?>
    <tr class="tr">
	<td colspan="2"><input type="submit" name="submit" value="Weiter zur Zahlungsmethode"></td>
    </tr>
    </table>

</td>
</tr>
</table>
</form>
<br>
* = Felder müssen ausgefüllt werden.
<br>

<?include("footer.php");?>
