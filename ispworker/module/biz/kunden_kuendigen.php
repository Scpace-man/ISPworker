<?
$module = basename(dirname(__FILE__));
include("../../header.php");

if(isset($_REQUEST['submit']))
{
    $eingabe = explode(".", $_REQUEST[kuendigen_zum]);
    if(count($eingabe) != 3 || !checkdate($eingabe[1], $eingabe[0], $eingabe[2])) {$error = "Es wurde kein g&uuml;ltiges Datum eingetragen!"; $label = ' style="color: #cc0000"';}
    else
    {
        $query = "UPDATE biz_rechnungtodo SET `kuendigen_zum`='".$eingabe[2]."-".$eingabe[1]."-".$eingabe[0]."' WHERE `posid` =".$_REQUEST['proid'];
        $test = $db->query($query);
        echo "Das Produkt ist erfolgreich gek&uuml;ndigt!<br>";
    }
}

?>
<span class="htitle">Produkt - K&uuml;ndigung</span><br>
<br><br>
<table width="540" border="0" cellspacing="0" cellpadding="0">
<tr bgcolor="#cccccc">
<td>
    <table width="100%" border="0" cellspacing="1" cellpadding="3">
    <form action="module/biz/kunden_kuendigen.php?posid=<?=$_REQUEST['posid']?>" method="post">
    <tr class="th">
    <td><b>Zu welchem Termin soll das Produkt gek&uuml;ndigt werden?</b></td>
    </tr>
    <tr class="tr">
    <td><br><br>

<?
    if($error) echo $error."<br>";
?>
    <span<?=$label?>>K&uuml;ndigungsdatum</span> <input type="text" name="kuendigen_zum" value=""> Beispiel 21.2.2006
    <br><br>
    <input type="hidden" name="proid" value="<?=$_REQUEST['posid']?>">
    <input type="submit" name="submit" value="K&uuml;ndigen">
    </td>
    </tr>
    </form>
    </table>
</td>
</tr>
</table>


<br>
<br>
<br>


<?include("../../footer.php");?>

