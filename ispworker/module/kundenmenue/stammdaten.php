<?
$module = basename(dirname(__FILE__));
include("../../header.php");


if(isset($_REQUEST[update])) {

  $db->query("update biz_kunden set mail='$_REQUEST[mail]',telefon='$_REQUEST[telefon]',fax='$_REQUEST[fax]',handy='$_REQUEST[handy]',sendmail='".$_REQUEST["sendmail"]."' where kundenid='$_SESSION[user]'");
  message("&Auml;nderungen wurden gespeichert.");
}


$res = $db->query("select * from biz_kunden where kundenid='$_SESSION[user]'");
$row = $db->fetch_array($res);

?>



<form action="module/kundenmenue/stammdaten.php?update=true" method="post">
<table width="540" border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
  <td colspan="2"><b>Stammdaten</b></td>
</tr>
<tr class="tr">
  <td>Firma</td>
  <td><?=$row[firma]?></td>
</tr>
<tr class="tr">
  <td>Anrede</td>
  <td><?=$row[anrede]?></td>
</tr>
<tr class="tr">
  <td>Vorname</td>
  <td><?=$row[vorname]?></td>
</tr>
<tr class="tr">
  <td>Nachname</td>
  <td><?=$row[nachname]?></td>
</tr>
<tr class="tr">
  <td>Strasse</td>
  <td><?=$row[strasse]?></td>
</tr>
<tr class="tr">
  <td>Land, PLZ</td>
  <td><?=$row[isocode]?>-<?=$row[plz]?></td>
</tr>
<tr class="tr">
  <td>Ort</td>
  <td><?=$row[ort]?></td>
</tr>
<tr class="tr">
  <td>Telefon</td>
  <td><input type="text" name="telefon" size= "30" value="<?=$row[telefon]?>"></td>
</tr>
<tr class="tr">
  <td>Telefax</td>
  <td><input type="text" name="fax" size= "30" value="<?=$row[fax]?>"></td>
</tr>
<tr class="tr">
  <td>Mobil</td>
  <td><input type="text" name="handy" size= "30" value="<?=$row[handy]?>"></td>
</tr>
<tr class="tr">
  <td>E-Mail</td>
  <td><input type="text" name="mail" size= "30" value="<?=$row[mail]?>"></td>
</tr>
<tr class="tr">
  <td>Rechnungszustellung:</td>
  <td>
  <select name="sendmail">
  	<option value="Y"<?if($row[sendmail]=="Y") echo " selected";?>>per E-Mail</option>
  	<option value="N"<?if($row[sendmail]=="N") echo " selected";?>>per Post</option>	
  </select>

  </td>
</tr>
<tr class="tr">
  <td>Zahlungsart</td>
  <td>
  	<? if ($row[bezahlart]=="rechnung") { 
  	   echo "Rechnung";
  	   } else {
  	   echo "Lastschrift";
  	   }
  	?>
  </td>
</tr>
<? if ($row[bezahlart]=="lastschrift") { ?>
<tr class="tr">
  <td colspan="2"><b>Ihre Bankverbindung</b></td>
</tr>
<tr class="tr">
  <td>Kontoinhaber</td>
  <td><?=$row[kontoinhaber]?></td>
</tr>
<tr class="tr">
  <td>Name der Bank</td>
  <td><?=$row[geldinstitut]?></td>
</tr>
<tr class="tr">
  <td>Kontonummer</td>
  <td><?=$row[kontonummer]?></td>
</tr>
<tr class="tr">
  <td>Bankleitzahl</td>
  <td><?=$row[bankleitzahl]?></td>
</tr>
<? } ?>
<tr class="tr">
  <td>&nbsp;</td>
  <td><input type="submit" value="&Auml;nderungen speichern"></td>
</tr>
</table>


</td>
</tr>
</table>

</form>








<?include("../../footer.php");?>