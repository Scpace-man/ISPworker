<?
$module = basename(dirname(__FILE__));
include("./inc/functions.inc.php");
include("../../header.php");



include("./inc/reiter1.layout.php");

$bgcolor[0]   = "#f0f0f0";
$linecolor[0] = "#000000";

$bgcolor[3]   = "#ffffff";
$linecolor[3] = "#ffffff";

include("./inc/reiter1.php");


if($_REQUEST[deldomain]=="true") {
  trash("biz_domains","where domainname='$_REQUEST[domain]'");
}

if($_REQUEST[delhandle]=="true") {
  trash("biz_handles","where handleid='$_REQUEST[handleid]'");
}

if($_REQUEST[newdomain]=="true") {
  if($_REQUEST[inklusiv]!="Y") { $_REQUEST[inklusiv]="N"; }
  $freigeschaltet = $_REQUEST[f3]."-".$_REQUEST[f2]."-".$_REQUEST[f1];
  $db->query("insert into biz_domains (domainname,freigeschaltet,registrar,kundenid,adminid,inklusiv,rechtodoid) values('$_REQUEST[domainname]','$freigeschaltet','$_REQUEST[registrar]','$_REQUEST[kundenid]','$_SESSION[adminid]','$_REQUEST[inklusiv]','$_REQUEST[rechtodoid]')");
}

?>

<table width="540" border="0" cellspacing="0" cellpadding="0">
<tr bgcolor="#cccccc">
<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr bgcolor="#e7e7e7">
  <td colspan="4"><b>Inklusive Domains</b></td>
</tr>
<tr>
  <td bgcolor="#ffffff"><b>Domainname</b></td>
  <td bgcolor="#ffffff"><b>Datum</b></td>
  <td bgcolor="#ffffff"><b>Registrar</b></td>
  <td bgcolor="#ffffff" width="16"><b>Aktion</b</td>
</tr>

<?
  $res = $db->query("select * from biz_domains d, biz_domainregistrare r where inklusiv='Y' and adminid='$_SESSION[adminid]' and kundenid='$_REQUEST[kundenid]' and d.registrar=r.dregid order by domainname");
  while($row=$db->fetch_array($res)) {
?>

<tr>
  <td bgcolor="#ffffff"><?=$row[domainname]?></td>
  <td bgcolor="#ffffff"><?
  $t = strtotime($row[freigeschaltet]);
  $freigeschaltet = date("d.m.Y",$t);
  echo $freigeschaltet;  
  ?></td>
  <td bgcolor="#ffffff"><?=$row[name]?></td>
  <td bgcolor="#ffffff"><a href="module/biz/kunden_detail_domains.php?deldomain=true&domain=<?=$row[domainname]?>&kundenid=<?=$_REQUEST[kundenid]?>"><img alt="Löschen" src="img/trash.gif" border="0"></a></td>
</tr>
<?
  }
?>
<tr bgcolor="#e7e7e7">
  <td colspan="4"><b>Exklusive Domains</b></td>
</tr>
<tr>
  <td bgcolor="#ffffff"><b>Domainname</b></td>
  <td bgcolor="#ffffff"><b>Datum</b></td>
  <td bgcolor="#ffffff"><b>Registrar</b></td>
  <td bgcolor="#ffffff" width="16"><b>Aktion</b</td>
</tr>

<?
  $res = $db->query("select * from biz_domains d, biz_domainregistrare r where inklusiv='N' and adminid='$_SESSION[adminid]' and kundenid='$_REQUEST[kundenid]' and d.registrar=r.dregid order by domainname");
  while($row=$db->fetch_array($res)) {
?>

<tr>
  <td bgcolor="#ffffff"><?=$row[domainname]?></td>
  <td bgcolor="#ffffff"><?
   $t = strtotime($row[freigeschaltet]);
   $freigeschaltet = date("d.m.Y",$t);
   echo $freigeschaltet;
	 
  
  ?></td>
  <td bgcolor="#ffffff"><?=$row[name]?></td>
  <td bgcolor="#ffffff"><a href="module/biz/kunden_detail_domains.php?deldomain=true&domain=<?=$row[domainname]?>&kundenid=<?=$_REQUEST[kundenid]?>"><img alt="Löschen" src="img/trash.gif" border="0"></a></td>
</tr>
<?
  }
?>


</table>

</td>
</tr>
</table>

<br>

<?
$t = $html->table(0);
$t->addcol("Handles",530,3);
$t->cols();

$t->addrow("<b>Handle ID</b>",0,80);
$t->addrow("<b>Daten</b>");
$t->addrow("<b>Aktion</b>",0,32);
$t->rows();

$res = $db->query("select * from biz_handles where kundenid='$_REQUEST[kundenid]'");
while($row = $db->fetch_array($res))
{
    $t->addrow($row[handleid]);
    $t->addrow("
    <font size=\"1\">
    $row[nachname], $row[vorname];$row[firma];$row[strasse] $row[strassenr];$row[land]-$row[plz];$row[ort];<br>
    $row[telefon]; $row[fax]; $row[email];
    </font>
    ");
    $t->addrow("<a href=\"module/biz/kunden_detail_domains.php?delhandle=true&handleid=$row[handleid]&kundenid=$_REQUEST[kundenid]\" onclick=\"return confirm('Möchten Sie den Datensatz wirklich löschen?');\"><img alt=\"Löschen\" src=\"img/trash.gif\" border=\"0\"></a>");
    $t->rows();
}
$t->close();
?>
<font size="1"><b>&raquo; <a href="module/biz/kunden_detail_handle_neu.php?kundenid=<?=$_REQUEST[kundenid]?>">Neues Handle anlegen</b></a></font><br>
<font size="1"><b>&raquo; <a href="module/biz/kunden_detail_domain_neu.php?kundenid=<?=$_REQUEST[kundenid]?>">Neue Domain anlegen</b></a></font><br>


<br>
<br>
<br>
<br>




<?include("../../footer.php");?>
