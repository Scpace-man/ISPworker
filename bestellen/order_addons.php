<?
session_start();
require_once("include/config.inc.php");
include("header.php");

if($_REQUEST[addon]=="true")
{
	$_SESSION['paketid'] .= ";".$_REQUEST['paketid'];
}
?>

<h3>Punkt 3: Zusatz-Produkte</h3>
<hr size="1" noshade>
<br>
Sie haben die M&ouml;glichkeit Zusatzprodukte zu definieren:<br>
<br>

<table width="600" border="0" cellspacing="0" cellpadding="0">
<tr bgcolor="#cccccc">
<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr bgcolor="#e7e7e7" align="left" valign="top">
<td colspan="2"><b>Zusatzprodukte</b></td>
</tr>
<?

$res1 = $db->query("select * from biz_prodaddons where produktid='$_SESSION[paketid]'");
while($row1 = $db->fetch_array($res1)) {
//	$res = $db->query("select artikelid, tld_bestellbar, bpk.bezeichnung as bpkkat from order_artikel oa
//	left join biz_produkte bp ON oa.artikelid=bp.produktid
//	left join biz_produktkategorien bpk ON bp.katid=bpk.katid");
	$res2 = $db->query("select * from biz_produkte where produktid='$row1[zugeprod]'");
	$row2 = $db->fetch_array($res2);


?>

<tr bgcolor="#FFFFFF">
<td>
<b><?
echo "$row2[bezeichnung]";?></b><br><br>
<? echo "$row2[beschreibung]"; ?><br><br>
<? echo "<b>$row2[preis] EUR</b> $row2[abrechnung] "; ?>
</td>
<?
	if(strpos($_SESSION[paketid], ";".$row2[produktid])==false){
		echo '<td><a href="order_addons.php?addon=true&paketid='.$row2[produktid].'">Hinzuf&uuml;gen</a></td>';
	}
	else
	{
		echo '<td>Artikel vorgemerkt</td>';
	}

?>
</tr>

<?}?>
</table>

</td>
</tr>
</table>
<br>
<form action="order_form.php" method="get">
<input type="hidden" name="paketid" value="<?=$_REQUEST[paketid]?>">
<input type="submit" value="Weiter">



<?include("footer.php");?>
