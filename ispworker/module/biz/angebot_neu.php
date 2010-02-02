<?
$module = basename(dirname(__FILE__));
include("../../header.php");
?>

<span class="htitle">Neues individuelles Produkt</span><br>
<br>


<br>


<?
if($_REQUEST['new']=='true') {

	$preis = $_REQUEST[preis1].".".$_REQUEST[preis2];
	if($_REQUEST[abrechnung]=="indiv") { $_REQUEST[abrechnung] = "indiv:$_REQUEST[indivmonate]"; }
	$db->query("insert into biz_produkte (adminid,katid,bezeichnung,preis,abrechnung,indivkundenid,beschreibung,sichtbar)
	 			values ('$_SESSION[adminid]','$_REQUEST[katid]','$_REQUEST[bezeichnung]','$preis','$_REQUEST[abrechnung]','$_REQUEST[kundenid]','$_REQUEST[produktkommentar]','$_REQUEST[sichtbar]')");


	$pid = $db->insert_id();
	$beginnabrechnung = $_REQUEST[jahr]."-".$_REQUEST[monat]."-".$_REQUEST[tag];
	$db->query("insert into biz_rechnungtodo (adminid,kundenid,beginnabrechnung,produktanzahl,produktid,produktkommentar,profilid)
		 			values ('$_SESSION[adminid]','$_REQUEST[kundenid]','$beginnabrechnung','$_REQUEST[anzahl]','$pid','$_REQUEST[produktkommentar]','$_REQUEST[profilid]')");


  echo "<b><font color=\"green\">Rechnungsauftrag gespeichert.</font></b><br><br>";
}

?>

<br>
Geben Sie eine KundenNr ein oder suchen Sie unter "Kunden" einen Kunden und
klicken Sie dort auf "Merken".<br>
<br>

<form action="module/biz/angebot_neu.php" method="post">
<table border="0" cellspacing="0" cellpadding="0">
<tr bgcolor="#cccccc">
<td>

<table width="300" border="0" cellspacing="1" cellpadding="3">
<tr bgcolor="#e7e7e7">
  <td><b>Kunden Nummer eingeben</b></td>
</tr>
<tr bgcolor="#FFFFFF">
  <td><input type="text" size="8" name="kundenid"> <input type="submit" value="Auswählen"></td>
</tr>
</table>

</td>
</tr>
</table>
</form>

<br>
<br>
<br>
<?
if(!isset($_REQUEST[kundenid])) {
  $kdid = $_SESSION["merkkunde"];
}
else { $kdid = $_REQUEST["kundenid"]; }
$res = $db->query("select kundenid,vorname,nachname,mail from biz_kunden where adminid='$_SESSION[adminid]' and kundenid='$kdid'");
$row = $db->fetch_array($res);
if($row[kundenid]!="") {
?>

<table border="0" cellspacing="0" cellpadding="0">
<tr>
<td bgcolor="#cccccc">

<table width="600" border="0" cellspacing="1" cellpadding="3">
<tr>
  <td width="200" bgcolor="#e7e7e7">KundenNr</b></td>
  <td bgcolor="#ffffff"><?=$row[kundenid]?></b></td>
</tr>
<tr>
  <td bgcolor="#e7e7e7">Vorname</b></td>
  <td bgcolor="#ffffff"><?=$row[vorname]?></b></td>
</tr>
<tr>
  <td bgcolor="#e7e7e7">Nachname</b></td>
  <td bgcolor="#ffffff"><?=$row[nachname]?></b></td>
</tr>
<tr>
  <td bgcolor="#e7e7e7">Mail</b></td>
  <td bgcolor="#ffffff"><?=$row[mail]?></b></td>
</tr>
</table>

</td>
</tr>
</table>

<br>

<form action="module/biz/angebot_neu.php?new=true&kundenid=<?=$kdid?>" method="post">
<table width="600" border="0" cellspacing="0" cellpadding="0">
<tr>
<td bgcolor="#cccccc">

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr>
  <td bgcolor="#e7e7e7" colspan="2"><b>Produkt</b></td>
</tr>
<tr>
  <td width ="200" bgcolor="#ffffff">Anzahl</td>
  <td bgcolor="#ffffff"><input type="text" name="anzahl" size="2" value="1"></td>
</tr>
<tr>
  <td width ="200" bgcolor="#ffffff">Bezeichnung</td>
  <td bgcolor="#ffffff"><input type="text" name="bezeichnung"></td>
</tr>
<?
	$currencySQL = $db->query("select waehrung from biz_settings");
	$currency=$db->fetch_array($currencySQL);
?>
<tr>
  <td bgcolor="#ffffff">Preis Brutto</td>
  <td bgcolor="#ffffff"><input type="text" name="preis1" size="6"> . <input type="text" name="preis2" size="2"> in <?=$currency['waehrung']?></td>
</tr>
<tr>
  <td bgcolor="#ffffff">Kategorie</td>
  <td bgcolor="#ffffff">
  <select name="katid">
  <?
  $res = $db->query("select katid,bezeichnung from biz_produktkategorien where adminid='$_SESSION[adminid]' order by bezeichnung");
  while($row = $db->fetch_array($res)) {
    echo "<option value=\"$row[katid]\">$row[bezeichnung]</option>";
  }
  ?>
  </select> <br>(<font size="1">Tipp: Legen Sie eine Kategorie "Individuelle Produkte" an</font>)
  </td>
</tr>
<tr>
  <td bgcolor="#ffffff" valign="top">Produktkommentar</td>
  <td bgcolor="#ffffff"><textarea name="produktkommentar" cols="50" rows="5"></textarea></td>
</tr>
<tr>
  <td bgcolor="#ffffff" valign="top">Abrechnungszeitraum</td>
  <td bgcolor="#ffffff">

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

    </select>
</td>
</tr>
<tr>
  <td bgcolor="#ffffff">Beginn des Abrechnungszeitraumes</td>
  <td bgcolor="#ffffff">Tag <input type="text" name="tag" value="02" size="3"> Monat  <input type="text" name="monat" value="03" size="3"> Jahr  <input type="text" name="jahr" value="2004"size="5"></td>
</tr>
<tr>
  <td bgcolor="#ffffff">Profil</td>

<td bgcolor="#ffffff">
<select name="profilid">
  <?
  $res = $db->query("select profil,profilid from biz_profile where adminid='$_SESSION[adminid]'");
  while($row = $db->fetch_array($res)) {
    echo "<option value=\"$row[profilid]\">$row[profil]</option>\n";
  }
  ?>
  </select>
</td>
</tr>

<tr>
<td bgcolor="#ffffff">
Sichtbar für Kunden?
</td>
<td bgcolor="#ffffff">
  <select name="sichtbar">
  <option value="1">Ja</option>
  <option value="0">Nein</option>
  </select>
</td>
</tr>

<tr>
  <td bgcolor="#ffffff">&nbsp;</td>
  <td bgcolor="#ffffff"><input type="submit" value="Speichern"></td>
</tr>
</table>


</td>
</tr>
</table>
</form>



<?}?>
<br>


<br>










<?include("../../footer.php");?>