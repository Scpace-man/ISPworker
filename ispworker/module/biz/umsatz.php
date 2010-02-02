<?
$module = basename(dirname(__FILE__));
include("../../header.php");

/*
if($from[0] < 1 || $from[0] > 12) 
            {
                $error = "Monat gibt es nicht!";
            }
*/
?>


<span class="htitle">Umsatz - Statistik</span><br>
<br>

<?php
	if(isset($_REQUEST['submit']))
	{
        // Teste die Werte auf Richtigkeit
        if(!($_REQUEST['from'] == "" && $_REQUEST['to'] == "")) // || ($_REQUEST['from'] > 12 || $_REQUEST['to'] > 12)
        {
            if($_REQUEST['to'] == "") $_REQUEST['to'] = $_REQUEST['from'];

            $from   = explode(".", $_REQUEST['from']);
            $to     = explode(".", $_REQUEST['to']);
            
            // Wenn nur der Monat angegeben wurde, dann wird das aktuelle Jahr genommen
            if(count($from) == 1){

                $from[] = date("Y", time());
			}
            if(count($to) == 1){

                $to[] = date("Y", time());
			}
// 1 = Jahr
// 0 = Monat

			$datum_von = $from[1]."-".$from[0]."-1";
            $datum_bis = $to[1]."-".$to[0]."-".date("t", mktime(0, 0, 0, $to[0]+1, 0, $to[1]));
       }
        else
            $error = true;
	}
	
	if(!isset($_REQUEST['submit']) || $error)
	{
        $datum_von = date("Y-m-01", time());
        $datum_bis = date("Y-m-t", time());
	}

	// Abfrage der Datesätze, Ergebnis im array $_records
	// die Abfrage lautet dann so
	// "SELECT .... WHERE datum BETWEEN '$datum_von' AND '$datum_bis' AND adminid = 1 AND ...";
	
	// Hier simuliere ich das Array, die anderen Datenfelder wie rechnungsid usw. fehlen
	
	$_records = array();

?>
<table width="540" border="0" cellspacing="0" cellpadding="0" style="border: #cccccc 1px solid">
<tr bgcolor="#cccccc">
<td>
    <table width="100%" border="0" cellspacing="1" cellpadding="3" bgcolor="#ffffff">
        <form name="interval" action="module/biz/umsatz.php" method="post">
        <tr>
            <td align="right">Intervall von:</td>
            <td><input type="text" name="from" value=""></td>
            <td width="200" rowspan="3" valign="top">Beispiel:<br> 3.2005 bis 4<br>angezeigt wird vom M&auml;rz bis April des angegebenen Jahres</td>
        </tr>
        <tr>
            <td align="right" valign="top" rowspan="2">bis:</td>
            <td><input type="text" name="to" value=""></td>
        </tr>
        <tr>
            <td><input type="submit" name="submit" value="Anzeigen"></td>
        </tr>
        </form>
    </table>
</td>
</tr>
</table>

<br>
<?

$currencySQL = $db->query("select waehrung from biz_settings");
$currency=$db->fetch_array($currencySQL);

$_datum_von = explode("-", $datum_von);
$_datum_bis = explode("-", $datum_bis);

$res = $db->query("select * from biz_rechnungen where datum BETWEEN '".$datum_von."' AND '".$datum_bis."' AND adminid = 1 and (status='bezahlt' OR status='unbezahlt' OR status='gemahnt') order by rechnungid DESC");
while($row=$db->fetch_array($res))
{
	$_records[] = array("kunde" => explode("|", $row['anschrift']), "positionen" => $row["positionen"]);
}

	// Summe initialisieren
	$sum = 0;
?>
<table width="540" border="0" cellspacing="0" cellpadding="0">
<tr bgcolor="#cccccc">
<td>
<table width="100%" border="0" cellspacing="1" cellpadding="3">
    <tr class="th">
        <td colspan="2">Statistik im Intervall von <?= $_datum_von[2].".".$_datum_von[1].".".$_datum_von[0]?> bis <?=$_datum_bis[2].".".$_datum_bis[1].".".$_datum_bis[0]?>.</td>
    </tr>
    <tr class="tr">
        <td width="450"><b>Kunde</b></td>
        <td width="100" align="right"><b>Posten (in <?=$currency['waehrung']?>)</b></td>
    </tr>
<?	
    $even = true;
	// Datensätze durchlaufen
	foreach($_records as $_record)
	{
		// Positionen trennen (Array)
		$_positions = explode("<br>", $_record['positionen']);
		if($even)
		{
            $even = false;
            $color = " bgcolor=\"#cccccc\"";
        }
        else
        {
            $even = true;
            $color = "";
        }
		?>
		<tr class="tr">
            <td valign="top"><?=$_record['kunde'][0]?></td>
            <td align="right">
		<?
		// Positionen durchlaufen
		$ausgabe = array();
		foreach($_positions as $_position)
		{
			// Felder trennen
			$_fields = explode("|", $_position);
			
			// Betrag steht im 3. Feld
			if(isset($_fields[2]) && $_fields[2]!="")
            {
				
                $sum += $_fields[2]*$_fields[0];
                $ausgabe[] = sprintf("%.2f",$_fields[2]*$_fields[0]);
            }
		}
		echo implode("<br>", $ausgabe);
		?>
            </td>
		</tr>
		<?
	}
?>	
	<tr class="th">
	   <td align="right">Posten gesamt:</td>
	   <td align="right"><?=sprintf("%.2f",$sum)?></td>
	</tr>
	</table>
	</td></tr>
	</table>

<?include("../../footer.php");?>
