<?
$module = basename(dirname(__FILE__));
include("../../header.php");


if(isset($update)) {
  $db->query("update faq_daten set ueberschrift='$_REQUEST[ueberschrift]',text='$_REQUEST[text]',kat='$_REQUEST[kategorie]' where id='$_REQUEST[id]'");
  echo "<center><b>&Auml;nderungen wurden gespeichert.</b></center><br><br>\n";
}


$res = $db->query("select id,kat,ueberschrift,text from faq_daten where id='$_REQUEST[id]'");
$row = $db->fetch_array($res);



?>



<form action="module/faq/eintrag_editieren.php?update=true&id=<?=$_REQUEST[id]?>" method="post">
<table width="540" border="0" cellspacing="0" cellpadding="0">
<tr bgcolor="#cccccc" align="left" valign="top">
<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr bgcolor="#e7e7e7" align="left" valign="top">
  <td colspan="2"><b>Eintrag editieren</b></td>
</tr>
<tr>
  <td bgcolor="#ffffff">&Uuml;berschrift</td>
  <td bgcolor="#ffffff"><input type="text" name="ueberschrift" value="<?=$row[ueberschrift]?>" size="80"></td>
</tr>
<tr>
  <td bgcolor="#ffffff">Text</td>
  <td bgcolor="#ffffff"><textarea cols="60" rows="10" name="text"><?=$row[text]?></textarea></td>
</tr>
<tr>
  <td bgcolor="#ffffff">Kategorie</td>
  <td bgcolor="#ffffff">
  <select name="kategorie">
  <?
  $resk = $db->query("select id,name,beschreibung from faq_kategorien order by name");
  while($rowk = $db->fetch_array($resk)) {
    if($rowk[id]==$row[kat]) { $selected = "selected"; }
    else                          { $selected = "";         }
    
    echo "<option value=\"$rowk[id]\" $selected>$rowk[name]</option>";
  }
  ?>
  </select>
		 
<tr>
  <td bgcolor="#ffffff">&nbsp;</td>
  <td bgcolor="#ffffff"><input type="submit" value="&Auml;nderungen speichern"></td>
</tr>
</table>


</td>
</tr>
</table>
<br>
<a href="module/faq/uebersicht.php?id=<?=$_REQUEST[id]?>">Zur&uuml;ck</a>


</td>
</tr>
</table>



</td>
</tr>
</table>

</form>





<?include("../../footer.php");?>