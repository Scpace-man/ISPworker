<?
$module = basename(dirname(__FILE__));
include("../../header.php");

echo'<span class="htitle">PDF-Layout für Rechnungen und Mahnungen (Stilisierte Darstellung)</span><br>
<br>';

include("./inc/reiter5.layout.php");

$bgcolor[2]   = "#f0f0f0";
$linecolor[2] = "#000000";

$bgcolor[0]   = "#f0f0f0";
$linecolor[0] = "#000000";

$bgcolor[1]   = "#ffffff";
$linecolor[1] = "#ffffff";

include("./inc/reiter5.php");

?>

<?
if(isset($_REQUEST[update])) {

$db->query("UPDATE biz_layout SET 
feld1 = '$_REQUEST[feld1]',
feld2 = '$_REQUEST[feld2]',
feld3 = '$_REQUEST[feld3]',
feld4 = '$_REQUEST[feld4]',
feld5 = '$_REQUEST[feld5]',
feld6 = '$_REQUEST[feld6]',
feld7 = '$_REQUEST[feld7]',
feld8 = '$_REQUEST[feld8]',
feld9 = '$_REQUEST[feld9]',
feld10 = '$_REQUEST[feld10]',
feld11 = '$_REQUEST[feld11]',
feld12 = '$_REQUEST[feld12]',
feld13 = '$_REQUEST[feld13]',
feld14 = '$_REQUEST[feld14]',
feld15 = '$_REQUEST[feld15]',
feld16 = '$_REQUEST[feld16]',
feld17 = '$_REQUEST[feld17]',
feld18 = '$_REQUEST[feld18]',
feld19 = '$_REQUEST[feld19]'
WHERE adminid='$_SESSION[adminid]' AND profilid ='$_REQUEST[profilid]'");

message("Daten erfolgreich aktualisiert");

}

$logosql = $db->query("select logo_h, logo_w from biz_profile where adminid='$_SESSION[adminid]' and profilid='$_REQUEST[profilid]'");
$logorow = $db->fetch_array($logosql);

$res = $db->query("select layoutid,profilid,adminid,feld1,feld2,feld3,feld4,feld5,feld6,feld7,feld8,feld9,feld10,feld11,feld12,feld13,feld14,
feld15,feld16,feld17,feld18,feld19 from biz_layout
where adminid='$_SESSION[adminid]' AND profilid='$_REQUEST[profilid]'");
$row = $db->fetch_array($res);


?>
Platzhalter wie z.b. #firma# entsprechen den Feldnamen der Tabelle biz_profile und werden durch dessen Inhalte ersetzt.<br>
<b>Wichtig:</b> Die Platzhalter müssen allesamt klein geschrieben werden und betreffen <b>nur</b> die Tabelle <b>biz_profile</b>.<br>
<br><br>

<form action="module/biz/pdf_layout.php?update=true&profilid=<?=$_REQUEST[profilid]?>" method="post">
<table width="650" border="0" cellspacing="0" cellpadding="0">
<tr bgcolor="#cccccc" align="left" valign="top">
<td>
		<table cellspacing="0" cellpadding="0" border="0" width="100%" bgcolor="#e7e7e7">
		<tr bgcolor="#e7e7e7" align="left" valign="top">
		    <td width="55%">
				<table border="0" cellpadding="1" cellspacing="1">
					<tr>
						<td><img src="module/biz/img/logo<?=$_REQUEST[profilid]?>.jpg" width="<?=$logorow[logo_w]*3?>" height="<?=$logorow[logo_h]*3?>" border="0" alt="Logo"></td>
						<td valign="bottom"><a href="javascript:void();" onclick="window.open('<?=CONF_BASEHREF?>module/biz/pdf_koordinaten.php?feld=logo&profilid=<?=$_REQUEST[profilid]?>', 'LogoKoord', 'width=250,height=200,scrollbars=no,location=no,status=no,resizable=yes,toobar=no,top=200,left=200');"><img src="img/koord.gif" alt="" border="0"></a></td>
					</tr>
				</table>
					
			</td>
		    <td align="left" width="45%">
				<table border="0" cellpadding="1" cellspacing="1">
					<tr>
						<td><input type="text" tabindex="1" name="feld1" size="30" value="<?=$row[feld1]?>"></td>
						<td valign="bottom"><a href="javascript:void();" onclick="window.open('<?=CONF_BASEHREF?>module/biz/pdf_koordinaten.php?feld=feld1&profilid=<?=$_REQUEST[profilid]?>', 'Feld1', 'width=250,height=200,scrollbars=no,location=no,status=no,resizable=yes,toobar=no,top=200,left=200');"><img src="img/koord.gif" alt="" border="0"></a></td>
					</tr>
					<tr>
						<td><input type="text" tabindex="2" name="feld2" size="30" value="<?=$row[feld2]?>"></td>
						<td valign="bottom"><a href="javascript:void();" onclick="window.open('<?=CONF_BASEHREF?>module/biz/pdf_koordinaten.php?feld=feld2&profilid=<?=$_REQUEST[profilid]?>', 'Feld2', 'width=250,height=200,scrollbars=no,location=no,status=no,resizable=yes,toobar=no,top=200,left=200');"><img src="img/koord.gif" alt="" border="0"></a></td>
					</tr>							
					<tr>
						<td><input type="text" tabindex="3" name="feld3" size="30" value="<?=$row[feld3]?>"></td>
						<td valign="bottom"><a href="javascript:void();" onclick="window.open('<?=CONF_BASEHREF?>module/biz/pdf_koordinaten.php?feld=feld3&profilid=<?=$_REQUEST[profilid]?>', 'Feld3', 'width=250,height=200,scrollbars=no,location=no,status=no,resizable=yes,toobar=no,top=200,left=200');"><img src="img/koord.gif" alt="" border="0"></a></td>
					</tr>						
					<tr>
						<td><input type="text" tabindex="4" name="feld4" size="5" value="<?=$row[feld4]?>"> <input tabindex="5" type="text" name="feld5" value="<?=$row[feld5]?>"></td><td><a href="javascript:void();" onclick="window.open('<?=CONF_BASEHREF?>module/biz/pdf_koordinaten.php?feld=feld4&profilid=<?=$_REQUEST[profilid]?>', 'Feld4', 'width=250,height=200,scrollbars=no,location=no,status=no,resizable=yes,toobar=no,top=200,left=200');"><img src="img/koord.gif" alt="" border="0"></a></td>
					</tr>						
					<tr>						
						<td><input type="text" tabindex="6" name="feld6" size="30" value="<?=$row[feld6]?>"></td>
						<td valign="bottom"><a href="javascript:void();" onclick="window.open('<?=CONF_BASEHREF?>module/biz/pdf_koordinaten.php?feld=feld5&profilid=<?=$_REQUEST[profilid]?>', 'Feld5', 'width=250,height=200,scrollbars=no,location=no,status=no,resizable=yes,toobar=no,top=200,left=200');"><img src="img/koord.gif" alt="" border="0"></a></td>
					</tr>						
					<tr>					
						<td><input type="text" tabindex="7" name="feld7" size="30" value="<?=$row[feld7]?>"></td>
						<td valign="bottom"><a href="javascript:void();" onclick="window.open('<?=CONF_BASEHREF?>module/biz/pdf_koordinaten.php?feld=feld6&profilid=<?=$_REQUEST[profilid]?>', 'Feld6', 'width=250,height=200,scrollbars=no,location=no,status=no,resizable=yes,toobar=no,top=200,left=200');"><img src="img/koord.gif" alt="" border="0"></a></td>
					</tr>					
				</table>					
			</td>
		</tr>
		<tr>
			<td width="450">
				<table border="0" cellpadding="1" cellspacing="1">
					<tr>
						<td><input tabindex="10" type="text" size="40" name="feld10" value="<?=$row[feld10]?>"></td>
						<td valign="bottom"><a href="javascript:void();" onclick="window.open('<?=CONF_BASEHREF?>module/biz/pdf_koordinaten.php?feld=feld9&profilid=<?=$_REQUEST[profilid]?>', 'Feld9', 'width=250,height=200,scrollbars=no,location=no,status=no,resizable=yes,toobar=no,top=200,left=200');"><img src="img/koord.gif" alt="" border="0"></a></td>
					</tr>
				</table>
			</td>
			<td>
				<table border="0" cellpadding="1" cellspacing="1">
					<tr>
						<td><input type="text" tabindex="8" name="feld8" value="<?=$row[feld8]?>"></td>
						<td valign="bottom"><a href="javascript:void();" onclick="window.open('<?=CONF_BASEHREF?>module/biz/pdf_koordinaten.php?feld=feld7&profilid=<?=$_REQUEST[profilid]?>', 'Feld7', 'width=250,height=200,scrollbars=no,location=no,status=no,resizable=yes,toobar=no,top=200,left=200');"><img src="img/koord.gif" alt="" border="0"></a></td>
					</tr>
					<tr>
						<td><input type="text" tabindex="9" name="feld9" value="<?=$row[feld9]?>"></td>
						<td valign="bottom"><a href="javascript:void();" onclick="window.open('<?=CONF_BASEHREF?>module/biz/pdf_koordinaten.php?feld=feld8&profilid=<?=$_REQUEST[profilid]?>', 'Feld8', 'width=250,height=200,scrollbars=no,location=no,status=no,resizable=yes,toobar=no,top=200,left=200');"><img src="img/koord.gif" alt="" border="0"></a></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
		    <td width="450">
				&nbsp;Firmenname, Kundenvorname Kundennachname
			</td>
		    <td valign="bottom" rowspan="3">Datum: <?=date("d.m.Y")?></td>
		</tr>
		<tr>
		    <td width="450">
				&nbsp;Kundenstrasse
			</td>
		</tr>
		<tr>
		    <td width="450">
			 	&nbsp;Kundenplz Kundenort
			</td>
		</tr>
		<tr>
		    <td colspan="2">&nbsp;</td>
		</tr>						
		<tr>
		    <td  width="450">
				&nbsp;<font size="+2"><b>Rechnung</b></font><br>
				Rechnung-Nr.: 2006-1234 / Kunden-Nr.: 12345
			</td>
		    <td valign="bottom">Seite 1</td>	
		</tr>
		<tr>
		    <td colspan="2">&nbsp;</td>
		</tr>
		<tr>
		    <td colspan="2">
				<table align="center" width="100%" border="0">
					<tr>
						<td valign="bottom" width="5%">Pos.</td>
						<td valign="bottom" width="5%">Menge</td>				
						<td valign="bottom" width="70%">Beschreibung</td>				
						<td valign="bottom" width="10%">Preis brutto</td>				
						<td valign="bottom" width="10%">Summe</td>								
					</tr>
				</table>		
			</td>
		</tr>
		<tr>
		    <td colspan="2">&nbsp;<hr noshade width="95%">&nbsp;</td>
		</tr>
		<tr>
		    <td colspan="2" valign="top">
				<table align="center" height="300" width="100%" border="0">
					<tr valign="top">
						<td width="5%">1</td>
						<td width="5%">2</td>				
						<td width="70%">Beispielprodukt</td>				
						<td width="10%">5.00 EUR</td>				
						<td width="10%">10.00 EUR</td>								
					</tr>
				</table>		
			</td>
		</tr>
		<tr>
		    <td colspan="2"><hr noshade width="100%"></td>
		</tr>
		<tr>
		    <td  width="450">&nbsp;</td>
		    <td valign="bottom">
				<table width="100%">
						<tr>
								<td><b>Summe netto:</b></td>
								<td align="right"><b>8.62 EUR</b></td>						
								<td>&nbsp;</td>
						</tr>
						<tr>
								<td><b>zzgl. 16% MwSt:</b></td>
								<td align="right"><b>1.38 EUR</b></td>						
								<td>&nbsp;</td>								
						</tr>
						<tr>
								<td><b>Summe brutto:</b></td>
								<td align="right"><b>10.00 EUR</b></td>						
								<td>&nbsp;</td>								
						</tr>								
				</table>
			</td>	
		</tr>
		<tr>
		    <td colspan="2" align="center">
				<table width="100%">
						<tr>
							<td>&nbsp;</td>
							<td><b>Der Betrag wird von Ihrem Bankkonto eingezogen.<br>Zahlbar sofort rein netto.</b></td>
							<td>&nbsp;</td>
						</tr>
				</table>
			</td>
		</tr>		
		<tr>
		    <td colspan="2"><hr noshade width="100%"></td>
		</tr>		
		<tr>
		    <td colspan="2">
				<table width="100%" border="0">
					<tr>
							<td><input tabindex="11" size="30" type="text" name="feld11" value="<?=$row[feld11]?>"></td>
							<td valign="bottom"><a href="javascript:void();" onclick="window.open('<?=CONF_BASEHREF?>module/biz/pdf_koordinaten.php?feld=feld10&profilid=<?=$_REQUEST[profilid]?>', 'Feld10', 'width=250,height=200,scrollbars=no,location=no,status=no,resizable=yes,toobar=no,top=200,left=200');"><img src="img/koord.gif" alt="" border="0"></a></td>
							<td><input tabindex="14" size="30" type="text" name="feld14" value="<?=$row[feld14]?>"></td>
							<td valign="bottom"><a href="javascript:void();" onclick="window.open('<?=CONF_BASEHREF?>module/biz/pdf_koordinaten.php?feld=feld13&profilid=<?=$_REQUEST[profilid]?>', 'Feld13', 'width=250,height=200,scrollbars=no,location=no,status=no,resizable=yes,toobar=no,top=200,left=200');"><img src="img/koord.gif" alt="" border="0"></a></td>
							<td><input tabindex="17" size="30" type="text" name="feld17" value="<?=$row[feld17]?>"></td>
							<td valign="bottom"><a href="javascript:void();" onclick="window.open('<?=CONF_BASEHREF?>module/biz/pdf_koordinaten.php?feld=feld16&profilid=<?=$_REQUEST[profilid]?>', 'Feld16', 'width=250,height=200,scrollbars=no,location=no,status=no,resizable=yes,toobar=no,top=200,left=200');"><img src="img/koord.gif" alt="" border="0"></a></td>														
					</tr>		
					<tr>
							<td><input tabindex="12" size="30" type="text" name="feld12" value="<?=$row[feld12]?>"></td>
							<td valign="bottom"><a href="javascript:void();" onclick="window.open('<?=CONF_BASEHREF?>module/biz/pdf_koordinaten.php?feld=feld11&profilid=<?=$_REQUEST[profilid]?>', 'Feld11', 'width=250,height=200,scrollbars=no,location=no,status=no,resizable=yes,toobar=no,top=200,left=200');"><img src="img/koord.gif" alt="" border="0"></a></td>					
							<td><input tabindex="15" size="30" type="text" name="feld15" value="<?=$row[feld15]?>"></td>
							<td valign="bottom"><a href="javascript:void();" onclick="window.open('<?=CONF_BASEHREF?>module/biz/pdf_koordinaten.php?feld=feld14&profilid=<?=$_REQUEST[profilid]?>', 'Feld14', 'width=250,height=200,scrollbars=no,location=no,status=no,resizable=yes,toobar=no,top=200,left=200');"><img src="img/koord.gif" alt="" border="0"></a></td>
							<td><input tabindex="18" size="30" type="text" name="feld18" value="<?=$row[feld18]?>"></td>
							<td valign="bottom"><a href="javascript:void();" onclick="window.open('<?=CONF_BASEHREF?>module/biz/pdf_koordinaten.php?feld=feld17&profilid=<?=$_REQUEST[profilid]?>', 'Feld17', 'width=250,height=200,scrollbars=no,location=no,status=no,resizable=yes,toobar=no,top=200,left=200');"><img src="img/koord.gif" alt="" border="0"></a></td>														
					</tr>		
					<tr>
							<td><input tabindex="13" size="30" type="text" name="feld13" value="<?=$row[feld13]?>"></td>
							<td valign="bottom"><a href="javascript:void();" onclick="window.open('<?=CONF_BASEHREF?>module/biz/pdf_koordinaten.php?feld=feld12&profilid=<?=$_REQUEST[profilid]?>', 'Feld12', 'width=250,height=200,scrollbars=no,location=no,status=no,resizable=yes,toobar=no,top=200,left=200');"><img src="img/koord.gif" alt="" border="0"></a></td>					
							<td><input tabindex="16" size="30" type="text" name="feld16" value="<?=$row[feld16]?>"></td>
							<td valign="bottom"><a href="javascript:void();" onclick="window.open('<?=CONF_BASEHREF?>module/biz/pdf_koordinaten.php?feld=feld15&profilid=<?=$_REQUEST[profilid]?>', 'Feld15', 'width=250,height=200,scrollbars=no,location=no,status=no,resizable=yes,toobar=no,top=200,left=200');"><img src="img/koord.gif" alt="" border="0"></a></td>							
							<td><input tabindex="19" size="30" type="text" name="feld19" value="<?=$row[feld19]?>"></td>
							<td valign="bottom"><a href="javascript:void();" onclick="window.open('<?=CONF_BASEHREF?>module/biz/pdf_koordinaten.php?feld=feld18&profilid=<?=$_REQUEST[profilid]?>', 'Feld18', 'width=250,height=200,scrollbars=no,location=no,status=no,resizable=yes,toobar=no,top=200,left=200');"><img src="img/koord.gif" alt="" border="0"></a></td>							
					</tr>				
				</table>
			</td>
		</tr>
		</table>
</td>
</tr>
<tr>
	<td align="right">
		<input type="submit" name="button" value="Speichern">
	</td>
</tr>
</table>
</form>
<br>
<br>
<?include("../../footer.php");?>
