<?
$module = basename(dirname(__FILE__));
include("./inc/functions.inc.php");
include("../../header.php");

include("./inc/reiter1.layout.php");

$bgcolor[0]   = "#f0f0f0";
$linecolor[0] = "#000000";

$bgcolor[6]   = "#ffffff";
$linecolor[6] = "#ffffff";

include("./inc/reiter1.php");


if($_REQUEST["deldoc"]==true)  
{
    $res = $db->query("select * from biz_docs where docid='".$_REQUEST["docid"]."' ");
    $row = $db->fetch_array($res);
    
    if(unlink($biz_docpath."/".$row["docfilename"])==false) message($biz_docpath."/".$row["docfilename"]." konnte nicht gelöscht werden.","error");
    else { $db->query("delete from biz_docs where docid='".$_REQUEST["docid"]."' "); message("Dokument ist gelöscht."); }    
}

if($_REQUEST["savedoc"]==true) 
{
    $uploaddir = "$biz_docpath/";
    $mytime = time();

    if(!is_writable($uploaddir)) { message("$uploaddir ist nicht beschreibbar.","error"); die(); }
    if($_REQUEST["docname"]=="") { message("Sie müssen eine Dokumentenbezeichnung vergeben."); die(); }

    $uploadfile =  $_REQUEST["kundenid"]."-".$mytime."-".$_FILES['docfile']['name'];
    if (move_uploaded_file($_FILES['docfile']['tmp_name'], $uploaddir . $uploadfile)) {
	chmod($uploaddir . $uploadfile, 0777);
	message("Dokument Upload erfolgreich.");
	$db->query("insert into biz_docs (kundenid,docfilename,docname,doccomment,docdate) 
		values ('".$_REQUEST["kundenid"]."','$uploadfile','".$_REQUEST["docname"]."','".$_REQUEST["doccomment"]."','".$mytime."')");

    } else message("Fehler beim Upload.","error");									     
}
?>

<form enctype="multipart/form-data" action="module/biz/kunden_detail_docs.php?kundenid=<?=$_REQUEST["kundenid"]?>&savedoc=true" method="post">
<table width="650" border="0" cellspacing="0" cellpadding="0">
<tr bgcolor="#cccccc">
<td>
    <table width="100%" border="0" cellspacing="1" cellpadding="3">
        <tr class="th">
            <td width="580" colspan="2"><b>Dokument Upload</b></td>
        </tr>
	<tr class="tr">
	    <td>Datei</td>
	    <td><input type="file" style="width: 380px" name="docfile"></td>
	</tr>
	<tr class="tr">
	    <td>Dokument Bezeichnung</td>
	    <td><input type="text" style="width: 380px" name="docname"></td>
	</tr>
	<tr class="tr">
	    <td valign="top">Kommentar <font size="1">(optional)</font></td>
	    <td><textarea style="width: 380px" name="doccomment"></textarea></td>
	</tr>
	<tr class="tr">
	    <td colspan="2"><input type="submit" value="Hochladen"></td>
	</tr>
    </table>
</td>
</tr>
</table>
</form>
<br>    



<table width="650" border="0" cellspacing="0" cellpadding="0">
<tr bgcolor="#cccccc">
<td>
    <table width="100%" border="0" cellspacing="1" cellpadding="3">
        <tr class="th">
            <td width="65"><b>DOCID</b></td>
	    <td width="75"><b>Datum</b></td>
            <td><b>Dokument Daten</b></td>
            <td width="16">&nbsp;</td>
            <td width="16">&nbsp;</td>	    
        </tr>

    <? 
    $res = $db->query("SELECT * FROM biz_docs WHERE kundenid='$_REQUEST[kundenid]' order by docdate DESC");
    while($row = $db->fetch_array($res)) {
    ?>
        <tr class="tr">
            <td valign="top"><?=$row["docid"]?></td>
	    <td valign="top"><?=date("d.m.Y",$row["docdate"])?></td>
            <td valign="top"><b><?=$row["docname"]?></b><br><?=nl2br($row["doccomment"])?></td>
            <td valign="top"><a href="module/biz/doc_show.php?docid=<?=$row["docid"]?>" target="new"><img src="img/pdf.gif" width="16" height="16" border="0"></a></td>
            <td valign="top"><a href="module/biz/kunden_detail_docs.php?docid=<?=$row["docid"]?>&deldoc=true&kundenid=<?=$_REQUEST["kundenid"]?>" onclick="return confirm('M&ouml;chten Sie den Datensatz wirklich l&ouml;schen?');"><img src="img/trash.gif" width="16" height="16" border="0"></a></td>
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
<br>




<?include("../../footer.php");?>
