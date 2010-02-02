<?
$module = basename(dirname(__FILE__));
include("../../header.php");
include("./inc/functions.inc.php");


$res_s = $db->query("select * from ticket_personenprofile where pprofilid='$_SESSION[adminid]'");
$row_s = $db->fetch_array($res_s);


$time = time();

if($_REQUEST[catchticket]==true) $db->query("update ticket_anfragen set userid='$_SESSION[adminid]' where ticketid='$_REQUEST[ticketid]'");



if($_REQUEST[responsesave]==true) {

	$res = $db->query("select * from ticket_anfragen where ticketid='$_REQUEST[ticketid]'");
	$row = $db->fetch_array($res);

	$mess = "Sehr geehrte Kundin, sehr geehrter Kunde, \n$row_s[name] hat auf Ihre Support Anfrage geantwortet:\n\n".$_REQUEST[antwort];
	$headers  = 'Content-type: text/plain; charset=iso-8859-1' . "\r\n";
	$headers .= "From: $row[tomail]". "\r\n";
	
	mail("$row[frommail]", imap_8bit(imap_qprint("[TicketID: ".$_REQUEST[ticketid]."] Re: $row[betreff]")), $mess, "From: $row[tomail]");

	$db->query("insert into ticket_anfrageantworten (ticketid,nachricht,eingegangen,frommail,tomail)
				values ('$_REQUEST[ticketid]','$_REQUEST[antwort]','$time','$row[tomail]','$row[frommail]')");
}

if ($_REQUEST["do"]=="close") $db->query("update ticket_anfragen set status='Erledigt' where ticketid='$_REQUEST[ticketid]'");


if ($_REQUEST["do"]=="hold") $db->query("update ticket_anfragen set status='Halten' where ticketid='$_REQUEST[ticketid]'");

if ($_REQUEST["do"]=="del") 
{
	$resat = $db->query("select attachments from ticket_anfragen where ticketid='$_REQUEST[ticketid]'");
	$rowat = $db->fetch_array($resat);

	if($rowat[attachments]!="") {
	    $arr = explode("\n",$rowat[attachments]);
	    foreach($arr as $line) {
		if($line!="") {
        	    $att = explode(" ",$line);
	    	    unlink("$ticket_temppath/".$att[2]);
		}
	    }
	}

	$db->query("delete from ticket_anfragen where ticketid='$_REQUEST[ticketid]'");
	$db->query("delete from ticket_anfrageantworten where ticketid='$_REQUEST[ticketid]'");
	echo "Ticket ist gelöscht.";
	include("../../footer.php");
	die ();
}




?>




<b>Support Anfrage</b><br>
<br>


<?
$res = $db->query("select * from ticket_anfragen where ticketid='$_REQUEST[ticketid]'");
$row = $db->fetch_array($res);
?>


<table width="630" border="0" cellspacing="0" cellpadding="0">
<tr>
<td bgcolor="#cccccc">

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr>
  <td width="200" bgcolor="#e7e7e7">TicketID</td>
  <td bgcolor="#ffffff"><?=$row[ticketid]?></td>
</tr>
<tr>
  <td bgcolor="#e7e7e7">Eingang</td>
  <td bgcolor="#ffffff"><?$datum = date("d.m.Y H:i:s",$row[eingegangen]); echo $datum;?></td>
</tr>
<tr>
  <td bgcolor="#e7e7e7">Status</td>
  <td bgcolor="#ffffff"><b><?=$row[status]?></b></td>
</tr>
<tr>
  <td bgcolor="#e7e7e7">Abteilung</td>
  <td bgcolor="#ffffff">
  <?$resb = $db->query("select bezeichnung from ticket_abteilungen where mail='$row[tomail]'");
    $rowb = $db->fetch_array($resb);
    echo $rowb[bezeichnung];
  ?>
  </td>
</tr>
<tr>
  <td bgcolor="#e7e7e7">Ticket Eigentümer</td>
  <td bgcolor="#ffffff">
  <?
  $rese = $db->query("select * from ticket_personenprofile where pprofilid='$row[userid]'");
  $rowe = $db->fetch_array($rese);
  echo $rowe[name];
  if($rowe[name]=="") { echo "Niemand"; } echo " - <a href=\"module/ticket/anfrage_detail.php?catchticket=true&ticketid=$_REQUEST[ticketid]\">Übernehme Ticket</a>"; 
  ?>

  </td>
</tr>
<tr>
  <td bgcolor="#e7e7e7">Absender Mailadresse</td>
  <td bgcolor="#ffffff"><?=$row[frommail]?></td>
</tr>
</table>

</td>
</tr>
</table>
<br>

<table width="630" border="0" cellspacing="0" cellpadding="0">
<tr>
<td bgcolor="#cccccc">

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr>
  <td align="center" width="200" bgcolor="#ffffff"><a href="module/ticket/anfrage_detail.php?do=close&ticketid=<?=$_REQUEST["ticketid"]?>">SCHLIESSEN</a></td>
  <td align="center" width="200" bgcolor="#ffffff"><a href="module/ticket/anfrage_detail.php?do=hold&ticketid=<?=$_REQUEST["ticketid"]?>">HALTEN</a></td>
  <td align="center" width="200" bgcolor="#ffffff"><a href="module/ticket/anfrage_detail.php?do=del&ticketid=<?=$_REQUEST["ticketid"]?>">LÖSCHEN</a></td>
</tr>
</table>

</td>
</tr>
</table>
<br>
<form action="module/ticket/anfrage_detail.php?response=true&ticketid=<?=$_REQUEST[ticketid]?>" method="post">
<input type="submit" name="submit" value="Antworten mit Quotes"> <input type="submit" name="submit2" value="Antworten">
</form>
<?
if($_REQUEST[response]==true) {

	$res_a = $db->query("select * from ticket_anfrageantworten where ticketid='$_REQUEST[ticketid]' order by eingegangen DESC");
	$row_a = $db->fetch_array($res_a);
	
	
	if(isset($_REQUEST[submit])) {

	    if($row_a[nachricht]!="") {
		$q = $q."\n"."Am ".date("d.m.Y",$row_a[eingegangen])." um ".date("H:i:s",$row[eingegangen])." schrieben Sie:\n\n----\n".$row_a[nachricht]."----\n\n";
	    }
	    else {
		$q = $q."\n"."Am ".date("d.m.Y",$row[eingegangen])." um ".date("H:i:s",$row[eingegangen])." schrieben Sie:\n\n----\n".$row[nachricht]."----\n";
	    }

	    $qe = explode("\n",$q);
	    $q = "";
	    for($i=0;$i<count($qe);$i++) {
		$q .= "> $qe[$i]\n";
	    }
	}

	$q = $q."\n".$row_s[signatur]."\n\n";
	$q = "\n\n".$q."\n\n";



?>

<form action="module/ticket/anfrage_detail.php?responsesave=true&ticketid=<?=$_REQUEST[ticketid]?>" method="post">
<table width="630" border="0" cellspacing="0" cellpadding="0">
<tr>
<td bgcolor="#cccccc">

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr bgcolor="#e7e7e7">
<td><b>Antwort schreiben</b></td>
</tr>

<tr bgcolor="#FFFFFF">
<td><textarea name="antwort" cols="110" rows="17"><?=$q?></textarea></td>
</tr>
<tr bgcolor="#FFFFFF">
<td><input type="submit" value="Senden"></td>
</tr>
</table>

</td>
</tr>
</table>
</form>







<?
}
?>

<form action="module/ticket/anfrage_detail.php?response=true&ticketid=<?=$_REQUEST[ticketid]?>" method="post">
<table width="630" border="0" cellspacing="0" cellpadding="0">
<tr>
<td bgcolor="#cccccc">

<table width="100%" border="0" cellspacing="1" cellpadding="3">


<?
$resa = $db->query("select * from ticket_anfrageantworten where ticketid='$row[ticketid]' order by eingegangen DESC");
while($rowa = $db->fetch_array($resa)) {
$datum = date("d.m.Y H:i:s",$rowa[eingegangen]);
?>
<tr bgcolor="#e7e7e7">
<td><b>Antwort, <?=$datum?> - Von: <?=$rowa[frommail]?> An: <?=$rowa[tomail]?></b></td>
</tr>
<tr bgcolor="#FFFFFF">
<td><font face="Verdana" size="1"><?$rowa[nachricht] = nl2br($rowa[nachricht]);?><?=$rowa[nachricht]?></font></td>
</tr>
<?
}
?>

<tr bgcolor="#e7e7e7">
<td><b><?=$row[betreff]?></b></td>
</tr>

<tr bgcolor="#FFFFFF">
<td><font face="Verdana" size="1"><?$row[nachricht] = nl2br($row[nachricht]);?><?=$row[nachricht]?></font></td>
</tr>

<?if($row[attachments]!="") {?>
<tr bgcolor="#FFFFFF">
<td><b><font face="Verdana" size="1">Attachments</font></b><br>
<?
$arr = explode("\n",$row[attachments]);
foreach($arr as $line) {
    if($line!="") {
	$att = explode(" ",$line);
	$filename = explode("$row[ticketid]"."_",$att[2]);
	$bytes = $att[1] / 1024;
	$bytes = sprintf("%.2f",$bytes);
	echo "<font face=\"Verdana\" size=\"1\"><a href=\"module/ticket/download.php?filename=$filename[1]&ticketid=$row[ticketid]&type=$att[0]\">$filename[1]</a> $bytes KB</font><br>";
    }
}
?>
</td>
</tr>

<?}?>


</table>

</td>
</tr>
</table>
<br>


<br>
<br>









<?include("../../footer.php");?>