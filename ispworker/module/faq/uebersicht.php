<?
$module = basename(dirname(__FILE__));
include("../../header.php");
?>

<span class="htitle">FAQ - Übersicht</span><br>
<br>



<?

if(isset($_REQUEST[eintragloeschen])) {
  echo "<a href=\"module/faq/uebersicht.php?eintragloeschenja=true&id=$_REQUEST[id]\"><b>*Ja, Eintrag endgültig löschen*</b></a><br><br><br>";
}
if(isset($_REQUEST[eintragloeschenja])) {
  $db->query("delete from faq_daten where id='$_REQUEST[id]'");
  echo "<center><b>Eintag wurde gelöscht.</b></center><br><br>\n";
}

if(isset($_REQUEST[katloeschen])) {
  echo "<a href=\"module/faq/uebersicht.php?katloeschenja=true&id=$_REQUEST[id]\"><b>*Ja, Kategorie UND alle zugehörige Einträge endgültig löschen*</b></a><br><br><br>";
}
if(isset($_REQUEST[katloeschenja])) {
  $db->query("delete from faq_daten where kat='$_REQUEST[id]'");
  $db->query("delete from faq_kategorien where id='$_REQUEST[id]'");
  echo "<center><b>Kategorie und Einträge der Kategorie wurden gelöscht.</b></center><br><br>\n";
}
    

?>


<table border="0" cellspacing="0" cellpadding="5">
<tr>
<td valign="top" width="540">

&raquo; <a href="module/faq/eintrag_neu.php">Neuer Eintrag</a><br>
&raquo; <a href="module/faq/kat_neu.php">Neue Kategorie</a><br>

<br>

<i>Kategorien</i><br>
<br>


<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr bgcolor="#cccccc">
<td>

<table width="600" border="0" cellspacing="1" cellpadding="3">
<tr bgcolor="#e7e7e7" align="left" valign="top">
<td><b>Bezeichnung</b></td>
<td><b>Beschreibung</b></td>
<td colspan="2"><b>Aktion</b></td>

<?
$res = $db->query("select id,name,beschreibung from faq_kategorien order by name");
while($row=$db->fetch_array($res)) {
?>
</tr>
<tr bgcolor="#FFFFFF" align="left" valign="top">
<td><a href="module/faq/uebersicht.php?wahl=true&id=<?=$row[id]?>"><?=$row[name]?></a></td>
<td><?=$row[beschreibung]?></td>
<td width="16"><a href="module/faq/kat_editieren.php?id=<?=$row[id]?>"><img src="img/edit.gif" border="0"></a></td>
<td width="16"><a href="module/faq/uebersicht.php?katloeschen=true&id=<?=$row[id]?>"><img src="img/trash.gif" border="0"></a></td>
</tr>
<?
}
?>
</table>

</td>
</tr>
</table>


<br>
<br>

<? if ($_REQUEST[wahl]=="true") { 
   $res = $db->query("select id,ueberschrift,kat,autor from faq_daten where kat='$_REQUEST[id]' order by ueberschrift");
   $res2 = $db->query("select name from faq_kategorien where id='$_REQUEST[id]'");
   $row2 = $db->fetch_array($res2);
   $text_kat = $row2[name];

   } else {
   $res = $db->query("select id,ueberschrift,kat,autor from faq_daten order by ueberschrift");
   $text_kat = "alle";
   }
?>

<i>Beitr&auml;ge der Kategorie:</i> <b><?=$text_kat?></b><br>
<br>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr bgcolor="#cccccc">

<td>

<table width="600" border="0" cellspacing="1" cellpadding="3">
<tr bgcolor="#e7e7e7" align="left" valign="top">
<td><b>&Uuml;berschrift</b></td>
<td colspan="2"><b>Aktion</b></td>

<?



while($row=$db->fetch_array($res)) {
?>
</tr>
<tr bgcolor="#FFFFFF" align="left" valign="top">
<td><?=$row[ueberschrift]?></td>

<td width="16"><a href="module/faq/eintrag_editieren.php?id=<?=$row[id]?>"><img src="img/edit.gif" border="0"></td>
<td width="16"><a href="module/faq/uebersicht.php?eintragloeschen=true&id=<?=$row[id]?>"><img src="img/trash.gif" border="0"></a></td>
</tr>
<?
}
?>
</table>

</td>
</tr>
</table>

<br>
<br>

</td>
</tr>
</table>




<?include("../../footer.php");?>