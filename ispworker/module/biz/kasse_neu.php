<?
$module = basename(dirname(__FILE__));
include("./inc/functions.inc.php");
include("../../header.php");


$errorclass = "orange";
	
	if(!isset($_REQUEST['submit']))
	{
		initform();
	}
	else
	{
		if(!validlength($_REQUEST['bezeichnung'], 3))
		{
			$error = true;
			$err_bezeichnung = $errorclass;
		}


		if(!$error)
		{
			$summe = $_REQUEST[summe1].".".$_REQUEST[summe2];
			$datum = $_REQUEST[datum1]."-".$_REQUEST[datum2]."-".$_REQUEST[datum3];
			$db->query("insert into biz_kassenbuch (adminid,typ,bezeichnung,summe,mwst,datum)
              values ('$_SESSION[adminid]','$_REQUEST[typ]','$_REQUEST[bezeichnung]','$summe','$_REQUEST[mwst]','$datum')");
		}
	}

	// Formular initialisieren
	function initform()
	{
		$error = false;
		$_REQUEST['bezeichnung']		= "";
	}

	// Funktionen zur Validierung
	function validemail($string)
	{
		if (empty($string)) return false;
		$preg = "^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@([a-zA-Z0-9-]+\.)+([a-zA-Z]{2,4})$";

		preg_match("/$preg/", $string, $result);
		if ($string != $result[0]) return false;
		return true;
	}

	function validlength($string, $laenge)
	{
		if (strlen($string) < $laenge) return false;
		return true;
	}

$currencySQL = $db->query("select waehrung from biz_settings");
$currency=$db->fetch_array($currencySQL);
?>

<?php if(isset($_REQUEST['submit']) and !$error) { 

	echo "<center><b>Das Eintrag wurde gespeichert.</b></center><br><br>\n";
	
	} 

	elseif($error) { ?>
								
	<div class="headlineorange">Das Formular ist fehlerhaft ausgef&uuml;llt</div><br>
	Bitte &uuml;berpr&uuml;fen Sie die markierten Felder und speichern Sie das Formular noch einmal. Vielen Dank!<br><br>

				
<?php } ?>



<?php if(!isset($_REQUEST['submit']) or $error) { ?>


<b><i>Neuer Kassenbuch Eintrag</i></b>


<form action="module/biz/kasse_neu.php?new=true" method="post">
<table width="540" border="0" cellspacing="0" cellpadding="0">
<tr bgcolor="#cccccc" align="left" valign="top">
<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr bgcolor="#e7e7e7" align="left" valign="top">
  <td colspan="2"><b>Neuer Eintrag</b></td>
</tr>
<tr>
  <td bgcolor="#ffffff">Typ</td>
  <td bgcolor="#ffffff">Eingang <input type="radio" name="typ" value="eingang" checked> Ausgang <input type="radio" name="typ" value="ausgang"></td>
</tr>
<tr>
  <td bgcolor="#ffffff" class="text<?=$err_bezeichnung?>">Bezeichnung</td>
  <td bgcolor="#ffffff"><input type="text" value="<?php echo $_REQUEST['bezeichnung']; ?>" name="bezeichnung" size="40"></td>
</tr>
<tr>
  <td bgcolor="#ffffff">Datum</td>
  <td bgcolor="#ffffff">DD.MM.YYYY<br>
  <input type="text" name="datum3" size="2" value="<?=date("d");?>">.<input type="text" name="datum2" size="2" value="<?=date("m");?>">.<input type="text" name="datum1" size="4" value="<?=date("Y");?>"></td>
</tr>
<tr>
  <td bgcolor="#ffffff">Summe in <?=$currency['waehrung']?></td>
  <td bgcolor="#ffffff"><input type="text" name="summe1" size="7"> <input type="text" name="summe2" size="2"></td>
</tr>
<tr>
  <td bgcolor="#ffffff">MwSt addieren</td>
  <td bgcolor="#ffffff">Ja <input type="radio" name="mwst" value="Y" checked> Nein <input type="radio" name="mwst" value="N"></td>
</tr>
<tr>
  <td bgcolor="#ffffff">&nbsp;</td>
  <td bgcolor="#ffffff"><input type="submit" name="submit" value="Speichern"></td>
</tr>
</table>

</td>
</tr>
</table>
</form>

<?php } ?>




<?include("../../footer.php");?>
