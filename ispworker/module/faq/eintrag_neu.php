<?
$module = basename(dirname(__FILE__));
include("../../header.php");
?>


<span class="htitle">Neue Frage und Antwort</span><br>
<br>


<?
if(isset($_REQUEST['new'])) {
  $db->query("insert into faq_daten (kat,ueberschrift,text)
              values ('$_REQUEST[kategorie]','$_REQUEST[ueberschrift]','$_REQUEST[text]')");

  echo "<center><b>Eintrag wurde erstellt.</b></center><br><br>\n";
}
?>



<form action="module/faq/eintrag_neu.php?new=true" method="post">
<table width="600" border="0" cellspacing="0" cellpadding="0">
<tr bgcolor="#cccccc" align="left" valign="top">
<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr bgcolor="#e7e7e7" align="left" valign="top">
  <td colspan="2"><b>Neuer Eintrag</b></td>
</tr>
<tr>
  <td bgcolor="#ffffff">Frage</td>
  <td bgcolor="#ffffff"><input type="text" name="ueberschrift" size="80"></td>
</tr>
<tr>
  <td bgcolor="#ffffff">Text</td>
  <td bgcolor="#ffffff"><textarea cols="90" rows="10" name="text"></textarea></td>
</tr>
<tr>
  <td bgcolor="#ffffff">Kategorie</td>
  <td bgcolor="#ffffff">
  <select name="kategorie">
  <?
  $res = $db->query("select id,name from faq_kategorien order by name");
  while($row = $db->fetch_array($res)) {
    echo "<option value=\"$row[id]\">$row[name]</option>";
  } 
  ?>
  </select>  
  </td>
</tr>
<tr>
  <td bgcolor="#ffffff">&nbsp;</td>
  <td bgcolor="#ffffff"><input type="submit" value="Eintrag speichern"></td>
</tr>
</table>


</td>
</tr>
</table>
</form>








<?include("../../footer.php");?>