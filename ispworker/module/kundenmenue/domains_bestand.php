<?

/* Erweiterung von Dominique Schramm */
/* Basierend auf rechnungen.php */
/* Date: 07.Jannuar.2006 */
/* &Uuml;berarbeitet von Stephan Muelhaus, crossconcept GmbH */
/* Date: 16.Jannuar.2006 */


$module = basename(dirname(__FILE__));
include("../../header.php");


$res = $db->query("select domainname,freigeschaltet,inklusiv from biz_domains where kundenid='$_SESSION[user]'");
if($db->num_rows($res)==0) {
    echo "Sie haben derzeit keine Domains bei uns im Bestand";

    include("../../footer.php");
    die();
}


?>

<table width="540" border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>
<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
<td><b>Domainname</b></td>
<td><b>freigeschaltet</b></td>
<td><b>Status</b></td>
</tr>

<?


$res = $db->query("select domainname,freigeschaltet,inklusiv from biz_domains where kundenid='$_SESSION[user]'");
while($row=$db->fetch_array($res)) {
?>
<tr class="tr" align="left" valign="top">
<td><?=$row[domainname]?></td>
<?
$t = strtotime($row[freigeschaltet]);
$datum = date("d.m.Y",$t);
?>
<td><?=$datum?></td>
<td><?

if($row[inklusiv]=='Y'){
	 echo "<font color=\"green\">Inkusivdomain</font>"; 
}else{
	echo "<font color=\"orange\">Exclusivdomain</font>";
}
//if($row[status]=='N') { echo "<font color=\"orange\">Exclusivdomain</font>"; }
//if($row[status]=="gemahnt")   { echo "<font color=\"red\">$row[status]</font>"; }

?></td>
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











<?include("../../footer.php");?>
