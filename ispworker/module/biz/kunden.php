<?
$module = basename(dirname(__FILE__));
include("../../header.php");
?>


<span class="htitle">Kunden</span><br>
<br>


&raquo; <a href="module/biz/kunde_neu.php">Neuer Kunde</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<br>


<?


switch($_REQUEST[action]) 
{
    case "merken":
	if($_REQUEST[overwrite]!="true") 
	{
	    if($_SESSION['merkkunde']!="") 
		echo message("Fehler: Es wurde bereits ein Kunde gemerkt. <a href=\"module/biz/kunden.php?action=merken&overwrite=true&kundenid=$_REQUEST[kundenid]\">Ignorieren und Kunde überschreiben</a>","error");
	    else 
	    {
    		$_SESSION['merkkunde'] = $_REQUEST[kundenid];
		message("Kunde ist auf dem Merkzettel notiert.");
	    }	
	}
	else 
	{
	    $_SESSION['merkkunde'] = $_REQUEST[kundenid];
	    $merkkunde = $_REQUEST[kundenid];
	    message("Kunde ist notiert.");
	}    
    break;

    case "delete":
	if($biz_kundenloeschbar==false) message("Die L&ouml;schfunktion wurde deaktiviert.","error");
	else
	{
	    if($_REQUEST[singledel] == "true") 
	    {
			trash("biz_kunden","where kundenid='$_REQUEST[kundenid]'");
			trash("biz_kundenbuchungen","where kundenid='$_REQUEST[kundenid]'");
			trash("biz_rechnungen","where kundenid='$_REQUEST[kundenid]'");
			trash("biz_serveraccounts","where kundenid='$_REQUEST[kundenid]'");
	    }else{
			for($i = 0; $i < count($_REQUEST[ausw]); $i++)
			{
			    trash("biz_kunden","where kundenid='".$_REQUEST[ausw][$i]."'");
		    	trash("biz_kundenbuchungen","where kundenid='".$_REQUEST[ausw][$i]."'");
			    trash("biz_rechnungen","where kundenid='".$_REQUEST[ausw][$i]."'");
			    trash("biz_serveraccounts","where kundenid='".$_REQUEST[ausw][$i]."'");
			}
    	}
}    
break;    
}

if($_REQUEST[anzahl] != "") $_SESSION[anzahlds] = $_REQUEST[anzahl];
if($_REQUEST[typ]    != "") $_SESSION[typds]    = $_REQUEST[typ];

if(!isset($_REQUEST[q]) and !isset($_REQUEST[buchstabe])) { $_REQUEST[buchstabe] = "A"; }
if($_SESSION[anzahlds]=="")   $_SESSION[anzahlds]  = 150;
if(!isset($_REQUEST[start]))    $_REQUEST[start]   = 0; 
if(!isset($_REQUEST[ordnung]))  $_REQUEST[ordnung] = "nachname"; 
if(!isset($_REQUEST[wo]))  $_REQUEST[wo] = "alle"; 
if(!isset($_REQUEST['sort']))     $_REQUEST['sort']    = "DESC";

$abcarr=array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");


?>

<br>
<table border="0" cellpadding="0" cellspacing="0">
<tr>
  <td colspan="2"><span class="small">Suche in Feld</span></td>
  <td colspan="2"><span class="small">Datensätze</span></td>
  <td colspan="2"><span class="small">Typ</span></td>
</tr>
<tr>
<td>
  <form action="module/biz/kunden.php?ordnung=<?=$_REQUEST[ordnung]?>&start=<?=$_REQUEST[start]?>&typ=<?=$_REQUEST[typ]?>" method="post">
  <input type="text" name="q"> <select name="wo">
  	<option value="alle"<?if($_REQUEST['wo']=="alle") { echo " selected"; } else{ echo "";}?>>Alle Felder
   	<option value="firma"<?if($_REQUEST['wo']=="firma") { echo " selected"; } else{ echo "";}?>>Firma
  	<option value="nachname"<?if($_REQUEST['wo']=="nachname") { echo " selected"; } else{ echo "";}?>>Nachname
 	<option value="vorname"<?if($_REQUEST['wo']=="vorname") { echo " selected"; } else{ echo "";}?>>Vorname
   	<option value="mail"<?if($_REQUEST['wo']=="mail") { echo " selected"; } else{ echo "";}?>>Email
   	<option value="strasse"<?if($_REQUEST['wo']=="strasse") { echo " selected"; } else{ echo "";}?>>Strasse		
   	<option value="plz"<?if($_REQUEST['wo']=="plz") { echo " selected"; } else{ echo "";}?>>Plz	
   	<option value="ort"<?if($_REQUEST['wo']=="ort") { echo " selected"; } else{ echo "";}?>>Ort		
	<option value="kundenid"<?if($_REQUEST['wo']=="kundenid") { echo " selected"; } else{ echo "";}?>>Kundennummer
  	<option value="bemerkung"<?if($_REQUEST['wo']=="bemerkung") { echo " selected"; } else{ echo "";}?>>Bemerkung		
  </select><input type="submit" value="Suchen">
  </form>
</td>
<td width="15"></td>
<td>
  <form action="module/biz/kunden.php?buchstabe=<?=$_REQUEST[buchstabe]?>&ordnung=<?=$_REQUEST[ordnung]?>&start=<?=$_REQUEST[start]?>&wo=<?=urlencode($_REQUEST[wo])?>&q=<?=urlencode($_REQUEST[q])?>" method="post">
  <input type="text" name="anzahl" value="<?=$_SESSION[anzahlds]?>" size="6">
  <input type="submit" value="Anzeigen">
  </form>
</td>
<td width="15"></td>
<td>
  <form action="module/biz/kunden.php?buchstabe=<?=$_REQUEST[buchstabe]?>&anzahl=<?=$_REQUEST[anzahl]?>&start=<?=$_REQUEST[start]?>&wo=<?=urlencode($_REQUEST[wo])?>&q=<?=urlencode($_REQUEST[q])?>" method="post">
  <select name="typ">
  <option value="gesamt"<?if($_SESSION[typds]=="gesamt") { echo "selected"; } else { echo ""; }?>>Gesamt</option>
  <option value="druck"<?if($_SESSION[typds]=="druck") { echo "selected"; } else { echo ""; }?>>Druck</option>
  <option value="email"<?if($_SESSION[typds]=="email") { echo "selected"; } else { echo ""; }?>>E-Mail</option>
  </select>
  <input type="submit" value="Anzeigen">
  </form>
</td>

</tr>
</table>
<br>
<br>
<table border="0" cellpadding="0" cellspacing="0" width="700">
<tr>
<td width="700">  
<?
$abclinks.='<b><a href ="module/biz/kunden.php?buchstabe=alle&ordnung='.$_REQUEST[ordnung].'&start=0&anzahl='.$_REQUEST[anzahl].'">Alle</a></b> &nbsp;&nbsp;&nbsp;';

for($i=0;$i<count($abcarr);$i++){
	$abcres = $db->query("select nachname from biz_kunden where nachname like '".$abcarr[$i]."%'");
	if(mysql_affected_rows()>=1){
		$abclinks.='<b><a href ="module/biz/kunden.php?buchstabe='.$abcarr[$i].'&ordnung='.$_REQUEST[ordnung].'&start=0&anzahl='.$_REQUEST[anzahl].'">'.$abcarr[$i].'</a></b> &nbsp;&nbsp;&nbsp;';
	}
}
echo $abclinks;
?>
</td>
</tr>
</table>     
<br>
     
<form action="module/biz/kunden.php?q=<?=$_REQUEST[q]?>&buchstabe=<?=$_REQUEST[buchstabe]?>&ordnung=<?=$_REQUEST[ordnung]?>&start=<?=$_REQUEST[start]?>&anzahl=<?=$_REQUEST[anzahl]?>" method="post">
<table width="98%" border="0" cellspacing="0" cellpadding="0">
	<tr class="tb">
		<td>

			<table width="100%" border="0" cellspacing="1" cellpadding="3">
				<tr class="th">
					<td width="16"><img src="img/pixel.gif" width="1" height="1"></td>
					<td><a href="module/biz/kunden.php?buchstabe=<?=$_REQUEST[buchstabe]?>&ordnung=nachname&start=<?=$_REQUEST[start]?>&wo=<?=urlencode($_REQUEST[wo])?>&q=<?=urlencode($_REQUEST[q])?>&sort=<?if($sort == "DESC") echo "ASC"; else echo "DESC";?>" class="tf">Nachname</a></td>
					<td><a href="module/biz/kunden.php?buchstabe=<?=$_REQUEST[buchstabe]?>&ordnung=vorname&start=<?=$_REQUEST[start]?>&wo=<?=urlencode($_REQUEST[wo])?>&q=<?=urlencode($_REQUEST[q])?>&sort=<?if($sort == "DESC") echo "ASC"; else echo "DESC";?>" class="tf">Vorname</a></td>
					<td><a href="module/biz/kunden.php?buchstabe=<?=$_REQUEST[buchstabe]?>&ordnung=firma&start=<?=$_REQUEST[start]?>&wo=<?=urlencode($_REQUEST[wo])?>&q=<?=urlencode($_REQUEST[q])?>&sort=<?if($sort == "DESC") echo "ASC"; else echo "DESC";?>" class="tf">Firma</a></td>
					<td><b>Info</b></td>
					<td><a href="module/biz/kunden.php?buchstabe=<?=$_REQUEST[buchstabe]?>&ordnung=kundenid&start=<?=$_REQUEST[start]?>&wo=<?=urlencode($_REQUEST[wo])?>&q=<?=urlencode($_REQUEST[q])?>&sort=<?if($sort == "DESC") echo "ASC"; else echo "DESC";?>" class="tf">Interne KundenNr</a</td>
					<td colspan="3"><b>Aktion</b></td>

					<?
					
					if($_SESSION[typds]=="email") { $t = "and mail <> ''"; }
					if($_SESSION[typds]=="druck") { $t = "and mail = ''"; }

					if($_REQUEST[buchstabe]=="alle"){
						$_REQUEST[wo]="alle";
						$_REQUEST[buchstabe]=="";
					}
					
					if($_REQUEST[q]=="") 
					{
						if($_REQUEST[buchstabe]=="alle"){
						    $res = $db->query("select * from biz_kunden where anzeigen='Y' and nachname like '%' $t order by $_REQUEST[ordnung]");
						    $n = $db->num_rows($res);
						    $res = $db->query("select * from biz_kunden where anzeigen='Y' and nachname like '%' $t order by $_REQUEST[ordnung] limit $_REQUEST[start],$_SESSION[anzahlds]");
						}else{
						    $res = $db->query("select * from biz_kunden where anzeigen='Y' and nachname like '$_REQUEST[buchstabe]%' $t order by $_REQUEST[ordnung]");
						    $n = $db->num_rows($res);
						    $res = $db->query("select * from biz_kunden where anzeigen='Y' and nachname like '$_REQUEST[buchstabe]%' $t order by $_REQUEST[ordnung] limit $_REQUEST[start],$_SESSION[anzahlds]");
						}
					} else {
					    $_REQUEST[q] = urldecode($_REQUEST[q]);
						
						if($_REQUEST[wo]=="alle"){
							$statement=" (nachname like '%$_REQUEST[q]%' or vorname like '%$_REQUEST[q]%' or firma like '%$_REQUEST[q]%' or strasse like '%$_REQUEST[q]%' or
							plz like '%$_REQUEST[q]%' or ort like '%$_REQUEST[q]%' or mail like '%$_REQUEST[q]%' or bemerkung like '%$_REQUEST[q]%' or kundenid like '%$_REQUEST[q]%') ";
						}else{
							$statement=" $_REQUEST[wo] LIKE '%$_REQUEST[q]%' ";
						}
					
					    $res = $db->query("select * from biz_kunden where anzeigen='Y' and ".$statement."
						 $t order by $_REQUEST[ordnung]");

					    $n = $db->num_rows($res);
					    $res = $db->query("select * from biz_kunden where anzeigen='Y' and 
						".$statement."
						 $t order by $_REQUEST[ordnung] limit $_REQUEST[start],$_SESSION[anzahlds]");
					}
				
					while($row=$db->fetch_array($res)) {
					?>

				</tr>
				<tr class="tr">
					<td width="16"><input type="checkbox" name="ausw[]" value="<?=$row[kundenid]?>"></td>
					<td><? echo "<a href=\"module/biz/kunden_detail.php?kundenid=$row[kundenid]\">$row[nachname]</a>"; ?></td>
					<td><?=$row[vorname]?></td>
					<td><?=$row[firma]?></td>
					<td>
					<? if (!$row[bemerkung]=="") { echo "<a href=\"module/biz/kunden_detail_notizen.php?kundenid=".$row['kundenid']."\">Info</a>"; }  ?>
					</td>
					<td align="right"><?=$row[kundenid]?></td>
					<td width="16"><a href="module/biz/kunde_editieren.php?buchstabe=<?=$_REQUEST[buchstabe]?>&kundenid=<?=$row[kundenid]?>"><img alt="Bearbeiten" src="img/edit.gif" border="0"></a></td>
					<td width="16"><a href="module/biz/kunden.php?action=delete&singledel=true&buchstabe=<?=$_REQUEST[buchstabe]?>&ordnung=<?=$_REQUEST[ordnung]?>&start=<?=$_REQUEST[start]?>&kundenid=<?=$row[kundenid]?>&q=<?=urlencode($_REQUEST[q])?>" onclick="return confirm('Möchten Sie den Datensatz wirklich löschen?');"><img alt="Löschen" src="img/trash.gif" border="0"></a></td>
					<td width="16"><a href="module/biz/kunden.php?action=merken&buchstabe=<?=$_REQUEST[buchstabe]?>&ordnung=<?=$_REQUEST[ordnung]?>&start=<?=$_REQUEST[start]?>&q=<?=urlencode($_REQUEST[q])?>&kundenid=<?=$row[kundenid]?>"><img alt="Merken" src="img/merken.gif" border="0"></a></td>

				</tr>
				<?
					}
				?>
			</table>
		</td>
	</tr>
</table>
<br>
<select name="action">
<option value="delete">L&ouml;schen</option>
</select> <input type="submit" value="Abschicken">

</form>

<br>
<br>
<center>
<?for($i = 1; $i <= round($n / $_SESSION[anzahlds]); $i++) { $s = (($i-1) * $_SESSION[anzahlds]); ?>
<a href="module/biz/kunden.php?buchstabe=<?=$_REQUEST[buchstabe]?>&ordnung=<?=$_REQUEST[ordnung]?>&start=<?=$s?>&wo=<?=$_REQUEST[wo]?>&q=<?=urlencode($_REQUEST[q])?>"><?=$i?></a> <img src="img/pixel.gif" height="1" width="15">
<?}?>
</center>
<br>
<br>
<br>


<?include("../../footer.php");?>

