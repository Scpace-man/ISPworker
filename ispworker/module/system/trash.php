<?$module = basename(dirname(__FILE__));
include("../../header.php");

switch($_REQUEST[action]) {
    case "update":
	if($_REQUEST["do"] == "delete")
	{
	    for($i = 0; $i < count($_REQUEST[entries]); $i++)
		$db->query("delete from system_trash where entryid='".$_REQUEST[entries][$i]."'");
	}

	if($_REQUEST["do"] == "restore")
	{	
	    for($i = 0; $i < count($_REQUEST[entries]); $i++) {
		$res = $db->query("select * from system_trash where entryid='".$_REQUEST[entries][$i]."'");
		while($rs = $db->fetch_array($res)) 
		{
		    $array  = explode("::", $rs[entryvalues]);
		    
		    $values = "'$array[0]'";
		    for($j = 1; $j < count($array); $j++)
		    {
			$values .= ",'$array[$j]'";
		    }
		    $db->query("insert into $rs[entrytable] values ($values)");
    		    $db->query("delete from system_trash where entryid='".$_REQUEST[entries][$i]."'");
		}
	    }
	}
    break;
}


?>





<span class="htitle">Papierkorb</span><br>
<br>
<br>

<form action="module/system/trash.php?action=update" method="post">

<table width="99%" border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>
<?
if($_REQUEST[order]=="") $_REQUEST[order] = "entrydate";
if($_REQUEST["sort"]=="") $_REQUEST["sort"] = "DESC";
?>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
  <td width="16"><img src="img/pixel.gif" border="0" width="1" height="1"></td>
  <td width="120"><a href="module/system/trash.php?order=entrydate&sort=<?if($_REQUEST[sort] == "DESC") echo "ASC"; else echo "DESC";?>" class="tf">Datum</a></td>
  <td width="180"><a href="module/system/trash.php?order=entrytable&sort=<?if($_REQUEST[sort] == "DESC") echo "ASC"; else echo "DESC";?>" class="tf">Tabelle</a></td>
  <td><a href="module/system/trash.php?order=entryvalues&sort=<?if($_REQUEST[sort] == "DESC") echo "ASC"; else echo "DESC";?>" class="tf">Werte</a></td>
</tr>
<?
$res = $db->query("select * from system_trash order by $_REQUEST[order] $_REQUEST[sort]");
while($row = $db->fetch_array($res)) {
?>
<tr class="tr">
  <td width="16" valign="top"><input type="checkbox" name="entries[]" value="<?=$row[entryid]?>"></td>
  <td valign="top"><?=date("d.m.Y H:i:s",$row[entrydate])?></a></td>
  <td valign="top"><?=$row[entrytable]?></td>
  <td valign="top"><?=$row[entryvalues]?></td>
</tr>
<?}?>

<tr class="tr">
<td colspan="4" valign="top">
<!--<a href="">Alle auswählen</a> / <a href="">Auswahl entfernen</a>--> &nbsp; <select name="do"><option value="restore">Wiederherstellen</option><option value="delete">Löschen</option></select> <input type="submit" value="Speichern">
</td>
</tr>

</table>

</td>
</tr>
</table>

</form>

<br>




<br>


<?include("../../footer.php");?>
