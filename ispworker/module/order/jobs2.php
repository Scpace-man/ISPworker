<?
$module = basename(dirname(__FILE__));
include("../../header.php");




if($_REQUEST[update]=="true" && $_REQUEST[jobid]!="") {
    $db->query("update order_jobs set pos='$_REQUEST[pos]', produktid='$_REQUEST[produktid]',jobbezeichnung='$_REQUEST[jobbezeichnung]',manuell='$_REQUEST[manuell]',shellcommand='$_REQUEST[shellcommand]' where jobid='$_REQUEST[jobid]'");
}

if($_REQUEST[update]=="true" && $_REQUEST[jobid]=="") {
    $db->query("insert into order_jobs (pos,produktid,jobbezeichnung,manuell,shellcommand) values ('$_REQUEST[pos]','$_REQUEST[produktid]','$_REQUEST[jobbezeichnung]','$_REQUEST[manuell]','$_REQUEST[shellcommand]')");
}

if($_REQUEST[del]=="true") {
    trash("order_jobs","where jobid='$_REQUEST[jobid]'");
}

$currencySQL = $db->query("select waehrung from biz_settings");
$currency    = $db->fetch_array($currencySQL);
	 


?>






<span class="htitle">Jobs - automatisierte Prozesse</span><br>
<br>

WORKING HERE...

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
  <td><b>Jobbezeichnung</b></td>
  <td><b>Jobmethode</b></td>
  <td><b>Produkt</b></td>
  <td colspan="2"><b>Aktion</b></td>
</tr>
<?
$res = $db->query("select p.bezeichnung, j.jobbezeichnung, j.jobid  from order_jobs as j,biz_produkte as p where p.produktid=j.produktid order by p.bezeichnung");
while($row=$db->fetch_array($res)) {

?>
<tr class="tr">
  <td><?=$row[jobbezeichnung]?></td>
  <td><?=$row[bezeichnung]?></td>
  <td>methode</td>
  <td width="16"><a href="module/order/jobs.php?edit=true&jobid=<?=$row[jobid]?>"><img src="img/edit.gif" border="0" alt="Bearbeiten"></a></td>
  <td width="16"><a href="module/order/jobs.php?del=true&jobid=<?=$row[jobid]?>"><img src="img/trash.gif" border="0" alt="Löschen"></a></td>
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

<?html_caption("Job erstellen");?>


<form action="module/order/jobs.php?newjob=true" method="post">
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
  <select name="produktid">
  <?
  $res = $db->query("select p.produktid, p.preis, p.bezeichnung as produktbezeichnung, k.bezeichnung as kategoriebezeichnung
                     from biz_produkte p, biz_produktkategorien k, order_artikel o where p.katid=k.katid and o.artikelid=p.produktid order by k.bezeichnung");
			    
  while($row = $db->fetch_array($res)) 
    echo "<option value=\"".$row[produktid]."\">[".$row["kategoriebezeichnung"]."] ".$row["produktbezeichnung"]." - ".$row["preis"]." ".$currency["waehrung"]."</option>";
  ?>
  </select>
				  
  </td>
</tr>
<tr class="tr">
  <td>Bezeichnung des Jobs</td>
  <td><input type="text" name="jobbezeichnung" class="input-text"></td>
</tr>
<tr class="tr">
  <td>Methode</td>
  <td>
  <select name="jobmethode">
  <option value="shell">Shell-Befehl ausführen</option>
  <option value="email">E-Mail verschicken</option>
  <option value="confixx3">Confixx3 Account erstellen</option>
  <option value="ispdns">ISPdns Zone erzeugen </option>
  </select>
  </td>
</tr>
<tr class="tr">
  <td>Ausführungsart</td>
  <td>
  <select name="jobausfuehrungsart">
  <option value="einmal">Einmalig für das Produkt ausführen</option>
  <option value="jedomain">Für jede bestellte Domain ausführen</option>
  </select>
  </td>
</tr>
<tr class="tr">
  <td>Ausführung</td>
  <td> <input type="checkbox" name="ausfuehrungnachfreigabe" value="Y"> Job erst nach manueller Freigabe ausführen.
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