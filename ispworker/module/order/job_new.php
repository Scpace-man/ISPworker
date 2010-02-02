<?
$module = basename(dirname(__FILE__));
include("../../header.php");

if($_REQUEST["save"] == "true") 
{
	if($_REQUEST["jobmethod"]=="confixx3")
	{
		$jobparameter  = $_REQUEST["confixx3serverid"]."\n";
		$jobparameter .= $_REQUEST["confixx3paketid"]."\n";
	}


	if($_REQUEST["jobmethod"]=="ispdns")
	{
		$jobparameter  = $_REQUEST["ispdnsserverid"]."\n";
		$jobparameter .= $_REQUEST["ispdnstplname"]."\n";
	}


	if($_REQUEST["jobmethod"]=="email")
	{
		$jobparameter  = $_REQUEST["emailto"]."\n";
		$jobparameter .= $_REQUEST["emailfrom"]."\n";
		$jobparameter .= $_REQUEST["emailfromname"]."\n";
		$jobparameter .= $_REQUEST["emailsubject"]."\n";
		$jobparameter .= "--message--\n";
		$jobparameter .= $_REQUEST["emailmessage"]."\n";		
	}


	if($_REQUEST["jobmethod"]=="shell")
	{
		$jobparameter  = $_REQUEST["shellcommand"]."\n";
		$jobparameter .= $_REQUEST["shelloutputemail"]."\n";		
	}

	if($_REQUEST["jobactivation"]!="Y") $_REQUEST["jobactivation"] = "N";

	$db->query("insert into order_jobs (jobname,jobmethod,jobproductid,jobexecutionnum,jobactivation,jobparameter) 
			    values ('$_REQUEST[jobname]','$_REQUEST[jobmethod]','$_REQUEST[jobproductid]','$_REQUEST[jobexecutionnum]','$_REQUEST[jobactivation]','$jobparameter')");

	message("Job ist gespeichert.");
	include("../../footer.php");
	die();
}


?>

<span class="htitle">Jobs - automatisierte Prozesse</span><br>
<br>

<?html_caption("Job erstellen - Schritt 2");?>

<form action="module/order/job_new.php?save=true" method="post">
<input type="hidden" name="jobmethod" value="<?=$_REQUEST["jobmethod"]?>">
<input type="hidden" name="jobproductid" value="<?=$_REQUEST["jobproductid"]?>">
<input type="hidden" name="jobname" value="<?=$_REQUEST["jobname"]?>">
<input type="hidden" name="jobexecutionnum" value="<?=$_REQUEST["jobexecutionnum"]?>">
<input type="hidden" name="jobactivation" value="<?=$_REQUEST["jobactivation"]?>">
<table width="600" border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>
<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
  <td colspan="2"><b>Einstellungen</b></td>
</tr>

<?if($_REQUEST["jobmethod"]=="confixx3") {?>
<tr class="tr">
  <td width="200">Confixx3 Server</td>
  <td>
  <select name="confixx3serverid">
  <?
  $res = $db->query("select * from biz_defaultserver where servertyp='confixx3'");
  
  while($row = $db->fetch_array($res)) 
    echo "<option value=\"".$row[serverid]."\">".$row["servername"]." (".$row["benutzername"].") </option>";
  ?>
  </select>	  
  </td>
</tr>
<tr class="tr">
  <td valign="top">Paket</td>
  <td valign="top">
  <select name="confixx3paketid">
  <?
  $res = $db->query("select * from biz_interfaces where software ='Confixx 3'");
  $con = $db->fetch_array($res);

  $lines = explode("\n",$con[konfig]);

  for($i=0;$i<=count($lines);$i++) {
    if(strstr($lines[$i],"[")) {
  	    $temp  = explode("[",$lines[$i]);
        $temp2 = explode("]",$temp[1]);
        $paketname = stripslashes($temp2[0]);
        $pakete[] = $paketname;
    }
  }

  for($i=0;$i<count($pakete);$i++)
    echo "<option value=\"$i\">$pakete[$i]</option>";
  
  ?>
  </select>	  
  </td>
</tr>
<?}?>

<?if($_REQUEST["jobmethod"]=="ispdns") {?>
<tr class="tr">
  <td width="200">ISPdns Server</td>
  <td>
  <select name="ispdnsserverid">
  <?
  $res = $db->query("select * from biz_defaultserver where servertyp='ispdns'");
  
  while($row = $db->fetch_array($res)) 
    echo "<option value=\"".$row[serverid]."\">".$row["servername"]." (".$row["benutzername"].") </option>";
  ?>
  </select>	  
  </td>
</tr>
<tr class="tr">
  <td>ISPdns Template Name</td>
  <td><input type="text" name="ispdnstplname" class="input-text"></td>
</tr>
<?}?>

<?if($_REQUEST["jobmethod"]=="email") {?>
<tr class="tr">
  <td width="200">E-Mail Empfänger Adresse</td>
  <td><input type="text" name="emailto" value="#kundenemail#" class="input-text"></td>
</tr>
<tr class="tr">
  <td>E-Mail Absender Adresse</td>
  <td><input type="text" name="emailfrom" class="input-text"></td>
</tr>
<tr class="tr">
  <td>E-Mail Absender Name</td>
  <td><input type="text" name="emailfromname" class="input-text"></td>
</tr>
<tr class="tr">
  <td>E-Mail Betreff</td>
  <td><input type="text" name="emailsubject" class="input-text"></td>
</tr>
<tr class="tr">
  <td>E-Mail Nachricht</td>
  <td><textarea name="emailmessage" style="width:400px;height:150px;"></textarea></td>
</tr>
<?}?>

<?if($_REQUEST["jobmethod"]=="shell") {?>
<tr class="tr">
  <td width="200">Shell Befehl</td>
  <td><input type="text" name="shellcommand" class="input-text"></td>
</tr>
<tr class="tr">
  <td>Rückgabe per Mail an</td>
  <td><input type="text" name="shelloutputemail" class="input-text"></td>
</tr>
<?}?>

<tr class="tr">
  <td colspan="2"><input type="submit" value="Job Speichern"></td>
</tr>
</table>
</td>
</tr>
</table>
</form>


<br>
<br>
<?if($_REQUEST["jobexecutionnum"]=="x") { echo "Tipp: Verwenden Sie das Feld #domain# um den Domainnamen zu verwenden."; }?>
<br>
<br>


<?include("../../footer.php");?>