<?
$module = basename(dirname(__FILE__));
include("../../header.php");

if($_REQUEST[del]=="true") {
    trash("order_jobs","where jobid='$_REQUEST[jobid]'");
}

$currencySQL  = $db->query("select waehrung from biz_settings");
$biz_settings = $db->fetch_array($currencySQL);

?>

<span class="htitle">Jobs - automatisierte Prozesse</span><br>
<br>

<table border="0" cellspacing="0" cellpadding="0" width="600">
<tr>
<td>
<p>
Jobs sind Programme die nach dem Eingang einer Bestellung ausgeführt werden können,
entweder sofort und vollautomatisch oder nach manueller Freigabe durch einen Mitarbeiter.
</p>
<p>
Anwendungsfelder sind beispielsweise das automatisierte Erstellen von Webspace Accounts, 
Registrierung von Domains und Rechnungserzeugung. 
</p>
</td>
</tr>
</table> 
<br>

<?html_caption("Vorhandene Jobs");?>


<table width="600" border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>
<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
  <td><b>Jobname</b></td>
  <td><b>Produkt</b></td>
  <td><b>Jobmethode</b></td>
  <td><b>Man. Aktiv.</b></td>
  <td><b>Ausführung</b></td>
  <td colspan="2"><b>Aktion</b></td>
</tr>
<?
$res = $db->query("select p.bezeichnung, j.jobname, j.jobid, j.jobmethod, j.jobactivation, j.jobexecutionnum  from order_jobs as j,biz_produkte as p where p.produktid=j.jobproductid order by p.bezeichnung");
while($row=$db->fetch_array($res)) {

?>
<tr class="tr">
  <td><?=$row[jobname]?></td>
  <td><?=$row[bezeichnung]?></td>
  <td><?=$row[jobmethod]?></td>
  <td><?=$row[jobactivation]?></td>
  <td><?=$row[jobexecutionnuum]?><?if($row[jobexecutionnum]==1) echo "Einmalig"; else echo "für jede Domain";?></td>
  <td width="16"><a href="module/order/jobs.php?edit=true&jobid=<?=$row[jobid]?>"><img src="img/edit.gif" border="0" alt="Bearbeiten"></a></td>
  <td width="16"><a href="module/order/jobs.php?del=true&jobid=<?=$row[jobid]?>"  onclick="return confirm('Möchten Sie den Datensatz wirklich löschen?');"><img src="img/trash.gif" border="0" alt="Löschen"></a></td>
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

<?
if($_REQUEST["edit"]==true) 
{
	$res = $db->query("select p.bezeichnung, j.jobname, j.jobid, j.jobmethod, j.jobactivation, j.jobexecutionnum, j.jobparameter from order_jobs as j,biz_produkte as p where p.produktid=j.jobproductid and jobid='$_REQUEST[jobid]' ");
	$row = $db->fetch_array($res);

	html_caption("Job Spezifikation");
	echo "<pre>\n";
	echo "JOBNAME...........: ".$row["jobname"]."\n";
	echo "JOBMETHOD.........: ".$row["jobmethod"]."\n";
	echo "JOBPRODUCT........: ".$row["bezeichnung"]."\n";
	echo "JOBACTIVATION.....: ".$row["jobactivation"]."\n";
	echo "JOBEXECUTIONNUM...: ".$row["jobexecutionnum"]."\n";
	echo "\n";	
	echo "- JOBPARAMETER - \n".$row["jobparameter"]."\n";
	echo "</pre>\n";

}
?>


<?html_caption("Job erstellen - Schritt 1");?>


<form action="module/order/job_new.php" method="post">
<table width="600" border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>
<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
  <td colspan="2"><b>Einstellungen</b></td>
</tr>
<tr class="tr">
  <td width="150">Assoziiertes Produkt</td>
  <td>
  <select name="jobproductid">
  <?
  $res = $db->query("select p.produktid, p.preis, p.bezeichnung as produktbezeichnung, k.bezeichnung as kategoriebezeichnung
                     from biz_produkte p, biz_produktkategorien k, order_artikel o where p.katid=k.katid and o.artikelid=p.produktid order by k.bezeichnung");
			    
  while($row = $db->fetch_array($res)) 
    echo "<option value=\"".$row[produktid]."\">[".$row["kategoriebezeichnung"]."] ".$row["produktbezeichnung"]." - ".$row["preis"]." ".$biz_settings["waehrung"]."</option>";
  ?>
  </select>
				  
  </td>
</tr>
<tr class="tr">
  <td>Bezeichnung des Jobs</td>
  <td><input type="text" name="jobname" class="input-text"></td>
</tr>
<tr class="tr">
  <td>Methode</td>
  <td>
  <select name="jobmethod">
  <option value="shell">Shell-Befehl ausführen</option>

  <option value="email">E-Mail verschicken</option>
<!--
  <option value="confixx3">Confixx3 Account erstellen</option>
  <option value="ispdns">ISPdns Zone erzeugen </option>
-->
  </select>
  </td>
</tr>
<tr class="tr">
  <td>Anzahl Ausführungen</td>
  <td>
  <select name="jobexecutionnum">
  <option value="1">Einmalig für das Produkt ausführen</option>
  <option value="x">Für jede bestellte Domain ausführen</option>
  </select>
  </td>
</tr>
<tr class="tr">
  <td>Manuelle Aktivierung</td>
  <td> <input type="checkbox" name="jobactivation" value="Y"> Job erst nach manueller Freigabe ausführen.
  </select>
  </td>
</tr>
<tr class="tr">
  <td colspan="2"><input type="submit" value="Weiter"></td>
</tr>
</table>
</td>
</tr>
</table>
</form>





<br>


<?include("../../footer.php");?>