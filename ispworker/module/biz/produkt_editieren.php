<?
$module = basename(dirname(__FILE__));
include("../../header.php");


if(isset($_REQUEST[update])) 
{
    if($_REQUEST[abrechnung]=="indiv") { $_REQUEST[abrechnung] = "indiv:$_REQUEST[indivmonate]"; }
    $db->query("update biz_produkte set bezeichnung='".mysql_escape_string($_REQUEST[bezeichnung])."',preis='$_REQUEST[preis]',katid='$_REQUEST[katid]',abrechnung='$_REQUEST[abrechnung]', beschreibung='$_REQUEST[beschreibung]', sichtbar='$_REQUEST[sichtbar]' where adminid='$_SESSION[adminid]' and produktid='$_REQUEST[produktid]'");
    message("&Auml;nderungen wurden gespeichert.");
}


if(isset($_REQUEST[addon]))
{
    $db->query("insert into biz_prodaddons (zugeprod,produktid) values ('$_REQUEST[zugeprod]','$_REQUEST[produktid]')");
    message("Produkt zugeordnet");
}

if(isset($_REQUEST[deladdon]))
{
    $db->query("delete from biz_prodaddons where id = '$_REQUEST[id]'");
    message("$_REQUEST[addonid] Zuordnung gel&ouml;scht.");
}

$res = $db->query("select * from biz_produkte where adminid='$_SESSION[adminid]' and produktid='$_REQUEST[produktid]'");
$row = $db->fetch_array($res);


?>


<span class="htitle">Produkte</span><br>
<br>

<form action="module/biz/produkt_editieren.php?update=true&produktid=<?=$_REQUEST[produktid]?>" method="post">
<table width="540" border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
  <td colspan="2"><b>Produkt bearbeiten</b></td>
</tr>
<tr class="tr">
  <td>Bezeichnung</td>
  <td><input type="text" name="bezeichnung" size="52" value="<?=$row[bezeichnung]?>"></td>
</tr>
<tr class="tr">
  <td valign="top">Beschreibung<br><font size="1">optional</font></td>
  <td><textarea name="beschreibung" cols="58" rows="3"><?=$row[beschreibung]?></textarea></td>
</tr>
<tr class="tr">
  <td>Preis</td>
  <td><input type="text" name="preis" size="6" value="<?=$row[preis]?>"></td>
</tr>
<tr class="tr">
  <td>Kategorie</td>
  <td>
  <select name="katid">
  <?
  $resk = $db->query("select katid,bezeichnung from biz_produktkategorien where adminid='$_SESSION[adminid]' order by bezeichnung");
  while($rowk = $db->fetch_array($resk)) {
    if($rowk[katid]==$row[katid]) { $selected = "selected"; }
    else                          { $selected = "";         }

    echo "<option value=\"$rowk[katid]\" $selected>".stripslashes($rowk[bezeichnung])."</option>";
  }
  ?>
  </select>


<tr class="tr">
  <td valign="top">Abrechnungszeitraum</td>
  <td>
  <?

  if($row[abrechnung]=="einmalig")         { $a1 = "checked"; }
  if($row[abrechnung]=="monatlich")        { $a2 = "checked"; }
  if($row[abrechnung]=="vierteljaehrlich") { $a3 = "checked"; }
  if($row[abrechnung]=="halbjaehrlich")    { $a4 = "checked"; }
  if($row[abrechnung]=="jaehrlich")        { $a5 = "checked"; }
  if(strstr($row[abrechnung],"indiv"))     {
  	$a6 = "checked";
  	$a = explode("indiv:",$row[abrechnung]);
  }


  ?>
  <input type="radio" name="abrechnung" value="einmalig" <?=$a1?>> einmalig<br>
  <input type="radio" name="abrechnung" value="monatlich" <?=$a2?>> monatlich <br>
  <input type="radio" name="abrechnung" value="vierteljaehrlich" <?=$a3?>> vierteljährlich <br>
  <input type="radio" name="abrechnung" value="halbjaehrlich" <?=$a4?>> halbjährlich <br>
  <input type="radio" name="abrechnung" value="jaehrlich" <?=$a5?>> jährlich <br>
  <input type="radio" name="abrechnung" value="indiv" <?=$a6?>> alle
  <select name="indivmonate">
  <option <?if($a[1]=="1") echo "selected";?>>1</option>
  <option <?if($a[1]=="2") echo "selected";?>>2</option>
  <option <?if($a[1]=="3") echo "selected";?>>3</option>
  <option <?if($a[1]=="4") echo "selected";?>>4</option>
  <option <?if($a[1]=="5") echo "selected";?>>5</option>
  <option <?if($a[1]=="6") echo "selected";?>>6</option>
  <option <?if($a[1]=="7") echo "selected";?>>7</option>
  <option <?if($a[1]=="8") echo "selected";?>>8</option>
  <option <?if($a[1]=="9") echo "selected";?>>9</option>
  <option <?if($a[1]=="10") echo "selected";?>>10</option>
  <option <?if($a[1]=="11") echo "selected";?>>11</option>
  <option <?if($a[1]=="12") echo "selected";?>>12</option>
  <option <?if($a[1]=="13") echo "selected";?>>13</option>
  <option <?if($a[1]=="14") echo "selected";?>>14</option>
  <option <?if($a[1]=="15") echo "selected";?>>15</option>
  <option <?if($a[1]=="16") echo "selected";?>>16</option>
  <option <?if($a[1]=="17") echo "selected";?>>17</option>
  <option <?if($a[1]=="18") echo "selected";?>>18</option>
  <option <?if($a[1]=="19") echo "selected";?>>19</option>
  <option <?if($a[1]=="20") echo "selected";?>>20</option>
  <option <?if($a[1]=="21") echo "selected";?>>21</option>
  <option <?if($a[1]=="22") echo "selected";?>>22</option>
  <option <?if($a[1]=="23") echo "selected";?>>23</option>
  <option <?if($a[1]=="24") echo "selected";?>>24</option>
  <option <?if($a[1]=="25") echo "selected";?>>25</option>
  <option <?if($a[1]=="26") echo "selected";?>>26</option>
  <option <?if($a[1]=="27") echo "selected";?>>27</option>
  <option <?if($a[1]=="28") echo "selected";?>>28</option>
  <option <?if($a[1]=="29") echo "selected";?>>29</option>
  <option <?if($a[1]=="30") echo "selected";?>>30</option>
  <option <?if($a[1]=="31") echo "selected";?>>31</option>
  <option <?if($a[1]=="32") echo "selected";?>>32</option>
  <option <?if($a[1]=="33") echo "selected";?>>33</option>
  <option <?if($a[1]=="34") echo "selected";?>>34</option>
  <option <?if($a[1]=="35") echo "selected";?>>35</option>
  <option <?if($a[1]=="36") echo "selected";?>>36</option>


  </select> Monate
   <br>
    </td>
</tr>

<tr class="tr">
<td>Sichtbar für Kunden?</td>
<td>
<?
  if($row[sichtbar]=="0")        { $s1 = "checked"; }
  if($row[sichtbar]=="1")        { $s2 = "checked"; }

?>

  <input type="radio" name="sichtbar" value="1" <?=$s2?>> JA <input type="radio" name="sichtbar" value="0" <?=$s1?>> NEIN
</td>
</tr>

<tr class="tr">
  <td>&nbsp;</td>
  <td><input type="submit" value="Speichern"></td>
</tr>
</form>

<form action="module/biz/produkt_editieren.php?addon=true&produktid=<?=$_REQUEST[produktid]?>" method="post">
<tr class="th">
  <td colspan="2"><b>Produkt verknüpfen mit Zusatzprodukten</b></td>
</tr>
<tr class="tr">
  <td valign="top">Derzeit verkn&uuml;pft mit:</td>
  <td>
<?
	$resx = $db->query("select * from biz_prodaddons where produktid='$_REQUEST[produktid]'");
	while($rowx = $db->fetch_array($resx)) 
	{
		$resy = $db->query("select bezeichnung from biz_produkte where produktid = $rowx[zugeprod]");
		$rowy = $db->fetch_array($resy);
		echo "$rowy[bezeichnung] - <a href='module/biz/produkt_editieren.php?deladdon=true&id=$rowx[id]&produktid=$_REQUEST[produktid]'> x </a><br>";
	}
?>
 </td>
</tr>
<tr class="tr">
  <td>Produkte</td>
  <td>
  <select name="zugeprod">
  <option value="">-</option>
  <?
  $resl = $db->query("select * from biz_produkte order by bezeichnung");
  while($rowl = $db->fetch_array($resl)) {
    echo "<option value=\"$rowl[produktid]\">".$rowl[bezeichnung]."</option>";
  }
  ?>
  </select>

   <br>

    </td>
</tr>
<tr class="tr">
  <td colspan="2"><input type="submit" value="Hinzuf&uuml;gen"></td>
</tr>
</form>
</table>


</td>
</tr>
</table>


<?include("../../footer.php");?>