<?
$module = basename(dirname(__FILE__));
include("../../header.php");
global $PHP_SELF;
?>


<span class="htitle">News im Kundenbereich</span><br>
<br>


<?

if($_REQUEST[edit]=='true' and $_REQUEST[update]=='true') {
  $datum_mysql = fn_make_Date_mysql("$_REQUEST[datum]");
  $db->query("update biz_news set datum='$datum_mysql', titel='$_REQUEST[titel]', einleitung='$_REQUEST[einleitung]', text='$_REQUEST[textfeld]', location='$_REQUEST[location]', userid='$_REQUEST[session_userid]' where newsid='$_REQUEST[newsid]'");
  
  echo "<div class='text'>News-Meldung editiert<br><br>";
}

if($_REQUEST[add]=='true' and $_REQUEST[insert]=='true') {
  $datum_mysql = fn_make_Date_mysql("$_REQUEST[datum]");
  $url = "<br><br>";
  if($_REQUEST[quelle1bez]!="") {
    $url .= "<a href=\"$_REQUEST[quelle1url]\">$_REQUEST[quelle1bez]</a><br>";
  }
  $textfeld = $_REQUEST[textfeld].$_REQUEST[url];
  $db->query("insert into biz_news (datum,titel,einleitung,text,userid) values ('$datum_mysql','$_REQUEST[titel]','$_REQUEST[einleitung]','$_REQUEST[textfeld]','$_REQUEST[session_userid]')");
  echo "<div class='text'>News-Meldung erstellt.<br><br>";
}

if($_REQUEST[add]=='true' and $_REQUEST[insert]=='false') {
	$aktdatum = fn_get_date("$_REQUEST[aktdatum]");
?>
<form name="formular" action="module/biz/news.php?add=true&insert=true" enctype="multipart/form-data" method="post">


<table width="600" border="0" cellspacing="0" cellpadding="0">
<tr bgcolor="#cccccc">
<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr bgcolor="#e7e7e7">
  <td colspan="2"><b>News erstellen</b></td>
</tr>
<tr bgcolor="#ffffff">
		<td class="fieldname">Datum</td>
		<td><input type="text" name="datum" size="35" class="textfield" value="<?=$aktdatum?>"></td>
	</tr>
<tr bgcolor="#ffffff">
		<td class="fieldname">Titel</td>
		<td><input type="text" name="titel" size="35" class="textfield"></td>
	</tr>

<tr bgcolor="#ffffff">
		<td class="fieldname">Einleitung f&uuml;r Hauptseite</td>
		<td><textarea name="einleitung" cols="80" rows="5" class="textarea"></textarea></td>
	</tr>
<tr bgcolor="#ffffff">
		<td class="fieldname">Funktionen</td>
		<td><a href="javascript:inserttag('<br>');">Umbruch</a> :: <a href="javascript:inserttag('<p align=justify></p>');">Absatz</a> :: <a href="javascript:inserttag('<a href=http:\\></a>');">URL</a></td>
	</tr>
<tr bgcolor="#ffffff">
		<td class="fieldname">Text</td>
		<td><textarea name="textfeld" cols="80" rows="15" class="textarea"></textarea></td>
	</tr>
<tr bgcolor="#ffffff">
		<td class="fieldname">Link</td>
		<td><input type="text" name="quelle1bez" size="35" class="textfield"> <input type="text" name="quelle1url" size="35" class="textfield"></td>
	</tr>
<tr bgcolor="#ffffff">
		<td class="fieldname"></td>
		<td><input type="submit" value="Eintragen" class="button_submit"></td>
	</tr>
</table>

</td>
</tr>
</table>
</form>
<?
}



if($_REQUEST[edit]=='true' and $_REQUEST['delete']=='true') { 
  echo "<div class='text'><b>Achtung: News jetzt wirklich löschen?</b><br><br><a href=\"module/biz/news.php?newsid=$_REQUEST[newsid]&action=loeschen\"><b>ja</b></a>&nbsp;&nbsp;<a href=\"module/biz/news.php?edit=true&editform=false\"><b>nein</b></a><br><br>";
}

if ($_REQUEST[action] == "loeschen") {
  $db->query("delete from biz_news where newsid='$_REQUEST[newsid]'");
  echo "<div class='text'>Die News wurde gelöscht.<br><br>\n";
}



if($_REQUEST[edit]=='true' and $_REQUEST[editform]=='false') {
?>
&raquo; <a href="module/biz/news.php?add=true&insert=false">Neue News erstellen</a><br>
			<br>



<table width="600" border="0" cellspacing="0" cellpadding="0">
<tr bgcolor="#cccccc">
<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr bgcolor="#e7e7e7">
  <td><b>Titel</b></td>
  <td><b>Datum</b></td>
  <td colspan="2" width="32"><b>Aktion</b></td>
  </tr>
  
<?
	$res = $db->query("select * from biz_news order by datum desc limit 0,50");
	while($row=$db->fetch_array($res)) {
    $row[datum]= fn_make_Date($row[datum]);
		echo "<tr bgcolor=\"#ffffff\">\n";
		echo "<td>$row[titel]</td>\n";
		echo "<td>$row[datum]</td>\n";
		echo "<td><a href=\"module/biz/news.php?edit=true&editform=true&newsid=$row[newsid]\"><img alt='Bearbeiten' src='img/edit.gif' border='0'></a></td><td><a href=\"module/biz/news.php?edit=true&delete=true&newsid=$row[newsid]\"><img alt='Löschen' src='img/trash.gif' border='0'></a></td>\n";
		echo "</tr>\n";
	}
?>
</table>
</td>
</tr>
</table>
<?
}
?>



<?
if($_REQUEST[edit]=='true' and $_REQUEST[editform]=='true') {

$res = $db->query("select * from biz_news where newsid='$_REQUEST[newsid]'");
$row = $db->fetch_array($res);
$row[datum]= fn_make_Date($row[datum]);
?>


<form name="formular" action="module/biz/news.php?edit=true&update=true&newsid=<?=$_REQUEST[newsid]?>" enctype="multipart/form-data" method="post">
<table width="600" border="0" cellspacing="0" cellpadding="0">
<tr bgcolor="#cccccc">
<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr bgcolor="#e7e7e7">
  <td colspan="2"><b>News editieren</b></td>
</tr>
    <tr bgcolor="#ffffff">
		<td class="fieldname">Datum</td>
		<td><input type="text" name="datum" size="35" value="<?=$row[datum]?>" class="textfield"></td>
	</tr>
    <tr bgcolor="#ffffff">
		<td class="fieldname">Titel</td>
		<td><input type="text" name="titel" size="35" value="<?=$row[titel]?>" class="textfield"></td>
    </tr>
    <tr bgcolor="#ffffff">
		<td class="fieldname">Einleitung f&uuml;r Hauptseite</td>
		<td><textarea name="einleitung" cols="80" rows="5" class="textarea"><?=$row[einleitung]?></textarea></td>
	</tr>
    <tr bgcolor="#ffffff">
		<td class="fieldname">Funktionen</td>
		<td><a href="javascript:inserttag('<br>')">Umbruch</a> :: <a href="javascript:inserttag('<p align=justify></p>')">Absatz</a> :: <a href="javascript:inserttag('<a href=></a>')">URL</a></td>
	</tr>
    <tr bgcolor="#ffffff">
		<td class="fieldname">Text</td>
		<td><textarea name="textfeld" cols="80" rows="15" class="textarea"><?=$row[text]?></textarea></td>
	</tr>
    <tr bgcolor="#ffffff">
		<td class="fieldname"></td>
		<td><input type="submit" value="News &auml;ndern" class="button_submit"></td>
	</tr>
</table>
</td>
</tr>
</table>
</form>
<?
}
?>


<br>
<br>


<?include("../../footer.php");?>

