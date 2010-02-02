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

			$_REQUEST[beschreibung] = wordwrap($_REQUEST[beschreibung],58,"\n");

  			$preis = $_REQUEST[preis1].".".$_REQUEST[preis2];
			if($_REQUEST[abrechnung]=="indiv") { $_REQUEST[abrechnung] = "indiv:$_REQUEST[indivmonate]"; }
 			 $db->query("insert into biz_produkte (adminid,bezeichnung,beschreibung,preis,katid,abrechnung,sichtbar)
              values ('$_SESSION[adminid]','".mysql_escape_string($_REQUEST[bezeichnung])."','".mysql_escape_string($_REQUEST[beschreibung])."','$preis','$_REQUEST[katid]','$_REQUEST[abrechnung]','$_REQUEST[sichtbar]')");


		}
	}

	// Formular initialisieren
	function initform()
	{
		$error = false;
		$_REQUEST['bezeichnung']	= "";
		$_REQUEST['preis1']		= "";
		$_REQUEST['preis2']		= "";
        		$_REQUEST['katid']		= "";
		$_REQUEST['sichtbar'] 		= "";
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

	message("Das Produkt wurde gespeichert.");
	echo "<a href=\"module/biz/produkte.php\">Zurück</a><br>";

	}

	elseif($error) { ?>

	<div class="headlineorange">Das Formular ist fehlerhaft ausgef&uuml;llt</div><br>
	Bitte &uuml;berpr&uuml;fen Sie die markierten Felder und speichern Sie das Formular noch einmal. Vielen Dank!<br><br>


<?php } ?>



<?php if(!isset($_REQUEST['submit']) or $error) { ?>

<span class="htitle">Produkte</span><br>
<br>

<form action="module/biz/produkt_neu.php?new=true" method="post">
<table width="540" border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
  <td colspan="2"><b>Neues Produkt</b></td>
</tr>
<tr class="tr">
  <td class="text<?=$err_bezeichnung?>">Bezeichnung</td>
  <td><input type="text" name="bezeichnung" size="52" value="<?php echo $_REQUEST['bezeichnung']; ?>"></td>
</tr>
<tr class="tr">
  <td class="text" valign="top">Beschreibung<br><font size="1">optional</font></td>
  <td><textarea name="beschreibung" rows="3" cols="58" value="<?php echo $_REQUEST['beschreibung']; ?>"></textarea></td>
</tr>
<tr class="tr">
  <td>Preis</td>
  <td><input type="text" name="preis1" size="6" value="<?php echo $_REQUEST['preis1']; ?>"> . <input type="text" name="preis2" size="2" value="<?php echo $_REQUEST['preis2']; ?>"> in <?=$currency['waehrung']?></td>
</tr>
<tr class="tr">
  <td>Kategorie</td>
  <td>
  <select name="katid">
  <?
  $res = $db->query("select katid,bezeichnung from biz_produktkategorien where adminid='$_SESSION[adminid]' order by bezeichnung");
  while($row = $db->fetch_array($res)) {
    echo "<option value=\"$row[katid]\">$row[bezeichnung]</option>";
  }
  ?>
  </select>
  </td>
</tr>
<tr class="tr">
  <td valign="top">Abrechnungszeitraum</td>
  <td>

  <input type="radio" name="abrechnung" value="einmalig" checked> einmalig<br>
  <input type="radio" name="abrechnung" value="monatlich"> monatlich <br>
  <input type="radio" name="abrechnung" value="vierteljaehrlich"> vierteljährlich<br>
  <input type="radio" name="abrechnung" value="halbjaehrlich"> halbjährlich<br>
  <input type="radio" name="abrechnung" value="jaehrlich"> jährlich<br>
  <input type="radio" name="abrechnung" value="indiv"> alle
  <select name="indivmonate">
  <option>1</option>
  <option>2</option>
  <option>3</option>
  <option>4</option>
  <option>5</option>
  <option>6</option>
  <option>7</option>
  <option>8</option>
  <option>9</option>
  <option>10</option>
  <option>11</option>
  <option>12</option>
  <option>13</option>
  <option>14</option>
  <option>15</option>
  <option>16</option>
  <option>17</option>
  <option>18</option>
  <option>19</option>
  <option>20</option>
  <option>21</option>
  <option>22</option>
  <option>23</option>
  <option>24</option>
  <option>25</option>
  <option>26</option>
  <option>27</option>
  <option>28</option>
  <option>29</option>
  <option>30</option>
  <option>31</option>
  <option>32</option>
  <option>33</option>
  <option>34</option>
  <option>35</option>
  <option>36</option>

  </select> Monate
</td>
</tr>

<tr class="tr">
<td>Sichtbar für Kunden?</td>
<td>
  <select name="sichtbar">
  <option value="1">Ja</option>
  <option value="0">Nein</option>
  </select>
</td>
</tr>
<tr class="tr">
    <td>&nbsp;</td>
    <td>Anschliessend können Sie dieses Produkt bearbeiten, um Zusatzprodukte zu definieren.</td>
</tr>
<tr class="tr">
  <td>&nbsp;</td>
  <td><input type="submit" name="submit" value="Speichern"></td>
</tr>
</table>
</form>






</td>
</tr>
</table>


<?php } ?>

<?include("../../footer.php");?>