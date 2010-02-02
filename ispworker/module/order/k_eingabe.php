<?
$module = basename(dirname(__FILE__));
include("../../header.php");
 
// Wenn gespeichert wurde --> Update ausführen
if($_REQUEST['save'])
{
    $res = $db->query("SELECT domainkatid FROM order_settings");
    if($row=$db->fetch_array($res))
        $id = $row['domainkatid'];
    $echo = $db->query("update order_settings set bestandskunden='".$_REQUEST['bestandskunden']."',firma='".$_REQUEST['firma']."',titel='".$_REQUEST['titel']."',mobil='".$_REQUEST['mobil']."',fax='".$_REQUEST['fax']."',url='".$_REQUEST['url']."',pkey='".$_REQUEST['pkey']."',geb='".$_REQUEST['geb']."',zusatz1='".$_REQUEST['zusatz1']."',zusatz2='".$_REQUEST['zusatz2']."',zusatz3='".$_REQUEST['zusatz3']."',zusatz1status='".$_REQUEST['zusatz1status']."',zusatz2status='".$_REQUEST['zusatz2status']."',zusatz3status='".$_REQUEST['zusatz3status']."',nichtvolljaehrig='".$_REQUEST['nichtvolljaehrig']."' WHERE domainkatid='".$id."' ");
    if(!$echo)
        echo "<span style='color: #CC1111; font-size:15px;'>Speichern fehlgeschlagen. Versuchen Sie es bitte erneut.</span><br><br>";
}

// Formular-Inhalt aus der Datenbank laden
$res = $db->query("SELECT bemail, bestandskunden, firma, titel, mobil, fax, url, pkey, geb, zusatz1, zusatz2, zusatz3, zusatz1status, zusatz2status, zusatz3status, nichtvolljaehrig FROM order_settings");
if($row=$db->fetch_array($res)) 
{
?>
<span class="htitle">Eingabefelder f&uuml;r das Kundendaten Formular</span><br>
<br>



<form action="module/order/k_eingabe.php?save=true" method="post">
<table width="540" border="0" cellspacing="0" cellpadding="0">
    <tr class="tb">
        <td>
            <table width="100%" border="0" cellspacing="1" cellpadding="3">
                <tr class="th">
                    <td colspan="2"><b>Einstellung</b></td>
                </tr>
                <?
                // Bestandskunden
                $status1 = $status2 = $status3 = "";
                switch($row['bestandskunden'])
                {
                    case('aktiv'):
                                        $status2 = " checked";
                                        break;
                    default: // inaktiv
                                        $status3 = " checked";
                }
                // Ende Bestandskunden
                ?>
                <tr class="tr">
                    <td width="200" valign="top">Bestellung bestehender Kunden<br><span style="font-size:10px">(d.h. aus dem normalen Bestellsystem)</span></td>
                    <td><input type="radio" name="bestandskunden" value="aktiv"<?=$status2?>>&nbsp;&nbsp;aktiv<br>
                    <input type="radio" name="bestandskunden" value="inaktiv"<?=$status3?>>&nbsp;&nbsp;inaktiv</td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<br>
<table width="540" border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
  <td colspan="2"><b>Eingabefelder</b></td>
</tr>
<?
// Firma
$status1 = $status2 = $status3 = "";
switch($row['firma'])
{
    case('aktivpflicht'):
                        $status1 = " checked";
                        break;
    case('aktiv'):
                        $status2 = " checked";
                        break;
    default: // inaktiv
                        $status3 = " checked";
}
?>
<tr class="tr"> 
    <td valign="top">Firma</td>
    <td valign="top"><input type="radio" name="firma" value="aktivpflicht"<?=$status1?>>&nbsp;&nbsp;aktiv und auszuf&uuml;llen<br><input type="radio" name="firma" value="aktiv"<?=$status2?>>&nbsp;&nbsp;aktiv<br><input type="radio" name="firma" value="inaktiv"<?=$status3?>>&nbsp;&nbsp;inaktiv</td>
</tr>
<?
// Titel
$status1 = $status2 = $status3 = "";
switch($row['titel'])
{
    case('aktivpflicht'):
                        $status1 = " checked";
                        break;
    case('aktiv'):
                        $status2 = " checked";
                        break;
    default: // inaktiv
                        $status3 = " checked";
                        
}
?>
<tr class="tr"> 
    <td valign="top">Titel</td>
    <td valign="top"><input type="radio" name="titel" value="aktivpflicht"<?=$status1?>>&nbsp;&nbsp;aktiv und auszuf&uuml;llen<br><input type="radio" name="titel" value="aktiv"<?=$status2?>>&nbsp;&nbsp;aktiv<br><input type="radio" name="titel" value="inaktiv"<?=$status3?>>&nbsp;&nbsp;inaktiv</td>
</tr>
<?
// Mobil
$status1 = $status2 = $status3 = "";
switch($row['mobil'])
{
    case('aktivpflicht'):
                        $status1 = " checked";
                        break;
    case('aktiv'):
                        $status2 = " checked";
                        break;
    default: // inaktiv
                        $status3 = " checked";
                        
}
?>
<tr class="tr"> 
    <td valign="top">Mobil</td>
    <td valign="top"><input type="radio" name="mobil" value="aktivpflicht"<?=$status1?>>&nbsp;&nbsp;aktiv und auszuf&uuml;llen<br><input type="radio" name="mobil" value="aktiv"<?=$status2?>>&nbsp;&nbsp;aktiv<br><input type="radio" name="mobil" value="inaktiv"<?=$status3?>>&nbsp;&nbsp;inaktiv</td>
</tr>
<?
// Fax
$status1 = $status2 = $status3 = "";
switch($row['fax'])
{
    case('aktivpflicht'):
                        $status1 = " checked";
                        break;
    case('aktiv'):
                        $status2 = " checked";
                        break;
    default: // inaktiv
                        $status3 = " checked";
                        
}
?>
<tr class="tr"> 
    <td valign="top">Fax</td>
    <td valign="top"><input type="radio" name="fax" value="aktivpflicht"<?=$status1?>>&nbsp;&nbsp;aktiv und auszuf&uuml;llen<br><input type="radio" name="fax" value="aktiv"<?=$status2?>>&nbsp;&nbsp;aktiv<br><input type="radio" name="fax" value="inaktiv"<?=$status3?>>&nbsp;&nbsp;inaktiv</td>
</tr>
<?
// Website
$status1 = $status2 = $status3 = "";
switch($row['url'])
{
    case('aktivpflicht'):
                        $status1 = " checked";
                        break;
    case('aktiv'):
                        $status2 = " checked";
                        break;
    default: // inaktiv
                        $status3 = " checked";
                        
}
?>
<tr class="tr"> 
    <td valign="top">Website</td>
    <td valign="top"><input type="radio" name="url" value="aktivpflicht"<?=$status1?>>&nbsp;&nbsp;aktiv und auszuf&uuml;llen<br><input type="radio" name="url" value="aktiv"<?=$status2?>>&nbsp;&nbsp;aktiv<br><input type="radio" name="url" value="inaktiv"<?=$status3?>>&nbsp;&nbsp;inaktiv</td>
</tr>
<?
// Produktkey
$status1 = $status2 = $status3 = "";
switch($row['pkey'])
{
    case('aktiv'):
                        $status2 = " checked";
                        break;
    default: // inaktiv
                        $status3 = " checked";
                        
}
?>
<tr class="tr"> 
    <td valign="top">Produktkey</td>
    <td valign="top"><input type="radio" name="pkey" value="aktiv"<?=$status2?>>&nbsp;&nbsp;aktiv<br><input type="radio" name="pkey" value="inaktiv"<?=$status3?>>&nbsp;&nbsp;inaktiv</td>
</tr>
<?
// Geburtsdatum
$status1 = $status2 = $status3 = $status4 = "";
switch($row['geb'])
{
    case('aktivpflicht'):
                        $status1 = " checked";
                        break;
    case('aktiv'):
                        $status2 = " checked";
                        break;
    case('aktivvoll'):
                        $status4 = " checked";
                        break;
    default: // inaktiv
                        $status3 = " checked";
                        
}
?>
<tr class="tr"> 
    <td valign="top">Geburtsdatum</td>
    <td valign="top"><input type="radio" name="geb" value="aktivvoll"<?=$status4?>>&nbsp;&nbsp;aktiv mit Vollj&auml;hrigkeits-Check<br><input type="radio" name="geb" value="aktivpflicht"<?=$status1?>>&nbsp;&nbsp;aktiv und auszuf&uuml;llen<br><input type="radio" name="geb" value="aktiv"<?=$status2?>>&nbsp;&nbsp;aktiv<br><input type="radio" name="geb" value="inaktiv"<?=$status3?>>&nbsp;&nbsp;inaktiv</td>
</tr>
<tr class="tr"> 
    <td valign="top" width="200">Fehlermeldung wenn der Kunde nicht vollj&auml;hrig ist</td>
    <td valign="top"><input type="text" name="nichtvolljaehrig" maxlength="255" size="50" value="<?=$row['nichtvolljaehrig']?>"></td>
</tr>
            </table>
        </td>
    </tr>
</table>
<br>
<table width="540" border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
    <td>
    <table width="100%" border="0" cellspacing="1" cellpadding="3">
    <tr class="th">
	<td colspan="2"><b>zus&auml;tzliche eigene Felder</b>&nbsp;<span style="font-size:10px;">(hier k&ouml;nnen Sie eigene Feld-Namen erstellen)</span></td>
    </tr>
<?
// Zusatzstatus1
$status1 = $status2 = $status3 = "";
switch($row['zusatz1status'])
{
    case('aktivpflicht'):
                        $status1 = " checked";
                        break;
    case('aktiv'):
                        $status2 = " checked";
                        break;
    default: // inaktiv
                        $status3 = " checked";
                        
}
?>
<tr class="tr"> 
    <td valign="top" width="200"><input type="text" name="zusatz1" value="<?=$row['zusatz1']?>"></td>
    <td valign="top"><input type="radio" name="zusatz1status" value="aktivpflicht"<?=$status1?>>&nbsp;&nbsp;aktiv und auszuf&uuml;llen<br><input type="radio" name="zusatz1status" value="aktiv"<?=$status2?>>&nbsp;&nbsp;aktiv<br><input type="radio" name="zusatz1status" value="inaktiv"<?=$status3?>>&nbsp;&nbsp;inaktiv</td>
</tr>
<?
// Zusatzstatus2
$status1 = $status2 = $status3 = "";
switch($row['zusatz2status'])
{
    case('aktivpflicht'):
                        $status1 = " checked";
                        break;
    case('aktiv'):
                        $status2 = " checked";
                        break;
    default: // inaktiv
                        $status3 = " checked";
                        
}
?>
<tr class="tr"> 
    <td valign="top" width="200"><input type="text" name="zusatz2" value="<?=$row['zusatz2']?>"></td>
    <td valign="top"><input type="radio" name="zusatz2status" value="aktivpflicht"<?=$status1?>>&nbsp;&nbsp;aktiv und auszuf&uuml;llen<br><input type="radio" name="zusatz2status" value="aktiv"<?=$status2?>>&nbsp;&nbsp;aktiv<br><input type="radio" name="zusatz2status" value="inaktiv"<?=$status3?>>&nbsp;&nbsp;inaktiv</td>
</tr>
<?
// Zusatzstatus3
$status1 = $status2 = $status3 = "";
switch($row['zusatz3status'])
{
    case('aktivpflicht'):
                        $status1 = " checked";
                        break;
    case('aktiv'):
                        $status2 = " checked";
                        break;
    default: // inaktiv
                        $status3 = " checked";
                        
}
?>
<tr class="tr"> 
    <td valign="top" width="200"><input type="text" name="zusatz3" value="<?=$row['zusatz3']?>"></td>
    <td valign="top"><input type="radio" name="zusatz3status" value="aktivpflicht"<?=$status1?>>&nbsp;&nbsp;aktiv und auszuf&uuml;llen<br><input type="radio" name="zusatz3status" value="aktiv"<?=$status2?>>&nbsp;&nbsp;aktiv<br><input type="radio" name="zusatz3status" value="inaktiv"<?=$status3?>>&nbsp;&nbsp;inaktiv</td>
</tr>

<tr class="tr">
  <td colspan="2" valign="top"><input type="submit" value="Speichern"></td>
</tr>
</table>
<?
}
?>
</td>
</tr>
</table>
</form>

<br>

<br>


<?include("../../footer.php");?>