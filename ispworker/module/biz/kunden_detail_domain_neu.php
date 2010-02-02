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




/*if($_REQUEST[newdomain]=="true") {
  if($_REQUEST[inklusiv]!="Y") { $_REQUEST[inklusiv]="N"; }
  $freigeschaltet = $_REQUEST[f3]."-".$_REQUEST[f2]."-".$_REQUEST[f1];
  echo $freigeschaltet;
  $db->query("insert into biz_domains (domainname,freigeschaltet,registrar,kundenid,adminid,inklusiv,rechtodoid) values('$_REQUEST[domainname]','$freigeschaltet','$_REQUEST[registrar]','$_REQUEST[kundenid]','$_SESSION[adminid]','$_REQUEST[inklusiv]','$_REQUEST[rechtodoid]')");
}*/

?>

<form action="module/biz/kunden_detail_domains.php?newdomain=true" method="post">
<input type="hidden" name="kundenid" value="<?=$_REQUEST[kundenid]?>">
<table width="540" border="0" cellspacing="0" cellpadding="0">
<tr bgcolor="#cccccc">
<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr bgcolor="#e7e7e7">
  <td colspan="4"><b>Neue Domain</b></td>
</tr>
<tr>
  <td bgcolor="#ffffff">Domainname</td>
  <td bgcolor="#ffffff"><input type="text" name="domainname"></td>
</tr>
<tr>
  <td bgcolor="#ffffff">In Paket / Paketzuordnung</td>
  <td bgcolor="#ffffff">
  <select name="rechtodoid">
  <option value="">-- Kein Paket</option>
  <?
  $res = $db->query("select r.posid,k.vorname,k.nachname,r.kundenid,r.posid,r.beginnabrechnung,r.produktanzahl,r.produktid,
		     r.profilid,p.bezeichnung,p.preis,p.abrechnung
		     from biz_rechnungtodo as r, biz_produkte as p, biz_kunden as k
		     where r.produktid=p.produktid
                     and r.kundenid=k.kundenid and r.kundenid='$_REQUEST[kundenid]' order by r.beginnabrechnung,r.kundenid");
  
  while($row = $db->fetch_array($res)) {
    echo "<option value=\"$row[posid]\">$row[bezeichnung] ($row[beginnabrechnung])</option>";
  }
  ?>
  
  </select>
  </td>
</tr>
<?
$year = date("Y");
$mon  = date("m");
$day  = date("d");
?>
<tr>
  <td bgcolor="#ffffff">Inklusive</td>
  <td bgcolor="#ffffff"><input type="checkbox" name="inklusiv" value="Y">Domain wird nicht extra berechnet</td>
</tr>
<tr>
  <td bgcolor="#ffffff">Freigeschaltet</td>
  <td bgcolor="#ffffff"><input type="text" name="f1" value="<?=$day?>" size="2"><input type="text" name="f2" value="<?=$mon?>" size="2"><input type="text" name="f3" value="<?=$year?>" size="4"></td>
</tr>
<tr>
  <td bgcolor="#ffffff">Registrar</td>
  <td bgcolor="#ffffff"><select name="registrar">
  <?
  $res = $db->query("select * from biz_domainregistrare");
  while($row=$db->fetch_array($res)) {
  	echo "<option value=\"$row[dregid]\">$row[name]</option>";
  }
  ?>
  </select></td>
</tr>
<tr>
  <td colspan="2" bgcolor="#ffffff"><input type="submit" value="Speichern"></td>
</tr>
</table>

</td>
</tr>
</table>
</form>
<br>

<br>





<br>
<br>
<br>




<?include("../../footer.php");?>
