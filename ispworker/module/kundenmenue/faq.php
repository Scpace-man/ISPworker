<?
$module = basename(dirname(__FILE__));
include("../../header.php");
?>
<h2>FAQ-&Uuml;bersicht - Frequently Asked Questions</h2><br><br>

<table width="540" border="0" cellspacing="0" cellpadding="0">
	<tr class="tb">
		<td>

			<table width="100%" border="0" cellspacing="1" cellpadding="3">
				<tr class="th">
					<td colspan="2"><b>Alle Kategorien</b></td>
				</tr>
<?
				$res = $db->query("select id,name,beschreibung from faq_kategorien order by name");
				while($row=$db->fetch_array($res)) {
?>
				<tr  align="left" valign="top">
					<td><a href="module/kundenmenue/faq.php?seite=seite2&id=<?=$row[id]?>"><?=$row[name]?></a></td>
					<td><?=$row[beschreibung]?></td>
				</tr>
				<?
				}
				?>
			</table>
		</td>
	</tr>
</table>




<? if ($_REQUEST[seite]=="seite2") { 
   
	$res2 = $db->query("select name from faq_kategorien where id='$_REQUEST[id]'");
	$row2 = $db->fetch_array($res2)
?>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
	<tr class="th">
		<td colspan="2"><b><a href="module/kundenmenue/faq.php">&Uuml;bersicht</a> -> <?=$row2[name]?></b></td>
	</tr>
<?
	$zaehler = "1";
	$res = $db->query("select id,ueberschrift,text from faq_daten where kat='$_REQUEST[id]' order by ueberschrift");
	while($row=$db->fetch_array($res)) {
?>
		<tr align="left" valign="top">
			<td colspan="2"><? echo "$zaehler. $row[ueberschrift]"; ?></td>
			<? $zaehler = $zaehler + "1"; ?>
		</tr>
<?
	}
?>
</table>
		</td>
	</tr>
</table>
<br><br>

<?
    $zaehler = "1";
	$res = $db->query("select id,ueberschrift,text from faq_daten where kat='$_REQUEST[id]' order by ueberschrift");
	echo "<table width='540'>";
	while($row=$db->fetch_array($res)) {
		
		echo "<tr><td><b>$zaehler. $row[ueberschrift]</b><br><br>$row[text]<br><br></td></tr>";
		$zaehler = $zaehler + "1";
	}
	echo "</table>";
	}
?>








<?include("../../footer.php");?>
