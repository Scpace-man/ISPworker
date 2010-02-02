<?
$module = basename(dirname(__FILE__));
include("../../header.php");

if(isset($_REQUEST['new'])) {

if($_REQUEST[name]=="") { die("Bitte füllen Sie das Feld Name aus.");  }


  $db->query("insert into faq_kategorien (name,beschreibung)
              values ('$_REQUEST[name]','$_REQUEST[beschreibung]')");

  echo "<center><b>Kategorie wurde erstellt.</b></center><br><br>\n";

}

?>


<span class="htitle">Neue Kategorie</span><br>
<br>


<form action="module/faq/kat_neu.php?new=true" method="post">
<table width="540" border="0" cellspacing="0" cellpadding="0">
<tr bgcolor="#cccccc" align="left" valign="top">
<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr bgcolor="#e7e7e7" align="left" valign="top">
  <td colspan="2"><b>Neue Kategorie</b></td>
</tr>
<tr>
  <td bgcolor="#ffffff">Bezeichnung</td>
  <td bgcolor="#ffffff"><input type="text" name="name"></td>
</tr>
<tr>
  <td bgcolor="#ffffff">Beschreibung</td>
  <td bgcolor="#ffffff"><textarea cols="80" rows="4" name="beschreibung"></textarea></td>
</tr>
<tr>
  <td bgcolor="#ffffff">&nbsp;</td>
  <td bgcolor="#ffffff"><input type="submit" value="Kategorie anlegen"></td>
</tr>
</table>

</td>
</tr>
</table>
</form>


<?include("../../footer.php");?>
