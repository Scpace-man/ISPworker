<?
$module = basename(dirname(__FILE__));
include("../../header.php");
?>


<span class="htitle">Kunden</span><br>
<br>


&raquo; <a href="module/biz/kunden.php">zurück</a><br>
<br>


<?
/*
$res = $db->query("select * from biz_kunden where mailing='Y'");
while($row=$db->fetch_array($res)) {
}
*/

switch($_REQUEST[action]) 
{
	default: show_mail_form(); break;
    case "senden":      break;
}



function show_mail_form (){
echo'
<form action="module/biz/kunden_mailing.php" method="post">
<table width="60%" border="0" cellspacing="0" cellpadding="0">
	<tr class="tb">
		<td>

			<table width="100%" border="0" cellspacing="1" cellpadding="3">
				<tr class="th">
					<td colspan="2"><b>Mailing erstellen</b></td>
				</tr>
				<tr>
					<td bgcolor="#ffffff">Art des Mailings:</td>
					<td bgcolor="#ffffff">
						<select name="artdesmailings">
							<option value="newsletter">Newsletter</option>
							<option value="rundschreiben">wichtige Informationen</option>
						</select>
					</td>
				</tr>
				<tr>
					<td bgcolor="#ffffff">Betreff des Mailings:</td>
					<td bgcolor="#ffffff">
						<input type="text" name="subject" value="">
					</td>
				</tr>
				<tr>
					<td bgcolor="#ffffff" valign="top">Text des Mailings:</td>
					<td bgcolor="#ffffff">
						<textarea name="body" cols="80" rows="20"></textarea>
					</td>
				</tr>
				<tr>
					<td bgcolor="#ffffff" valign="top">für zukünftige Mailings speichern:</td>
					<td bgcolor="#ffffff">
						<input type="checkbox" name="savemailing">
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<br>
<input type="submit" value="Abschicken">
</form>


<br>
<br>
<br>';
}
?>

<?include("../../footer.php");?>

