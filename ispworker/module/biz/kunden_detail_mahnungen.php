<?
$module = basename(dirname(__FILE__));
include("./inc/functions.inc.php");
include("../../header.php");



include("./inc/reiter1.layout.php");

$bgcolor[0]   = "#f0f0f0";
$linecolor[0] = "#000000";

$bgcolor[2]   = "#ffffff";
$linecolor[2] = "#ffffff";

include("./inc/reiter1.php");

$res = $db->query("SELECT bm.mahnid ,bm.datum,bm.positionen,bmt.templatename FROM biz_mahnungen bm
LEFT JOIN biz_mahntemplates bmt ON bm.templateid=bmt.templateid
WHERE kundenid='$_REQUEST[kundenid]'");

$_records = array();
echo "<pre>";
while($row = $db->fetch_array($res))
{
    // rechnungspositionen auslesen
    $_rechnungen = explode(";", $row['positionen']);
    $rechnungen = array();
    foreach($_rechnungen as $id => $val)
    {
        if($val != "") $rechnungen[] = '<a href="module/biz/rechnung_show.php?rechnungid='.$val.'">'.$val.'</a>';
    }

    // Datum formatieren
    $datum = explode("-", $row['datum']);

    $records[] = array('mid' => $row['mahnid'], 'status' => $row['templatename'], 'mdatum' => $datum[2].".".$datum[1].".".$datum[0], 'positionen' => implode(" ", $rechnungen));
}
?>
<table width="540" border="0" cellspacing="0" cellpadding="0">
<tr bgcolor="#cccccc">
<td>
    <table width="100%" border="0" cellspacing="1" cellpadding="3">
        <tr class="th">
            <td width="80">Mahn - ID</td>
            <td width="80">Mahn - Status</td>
            <td width="100">Mahn - Datum</td>
            <td>Rechnungspositionen</td>
            <td width="20">&nbsp;</td>
        </tr>
        <?
        if(count($records) > 0)
        foreach($records as $record)
        {
        ?>
        <tr class="tr">
            <td><?=$record['mid']?></td>
            <td><?=$record['status']?></td>
            <td><?=$record['mdatum']?></td>
            <td><?=$record['positionen']?></td>
            <td><a href="module/biz/mahnung_show.php?mahnid=<?=$record['mid']?>"><img src="img/pdf.gif" width="16" height="16" border="0"></a></td>
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
