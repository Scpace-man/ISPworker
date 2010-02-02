<?
$module = basename(dirname(__FILE__));
include("../../header.php");
?>

<span class="htitle">Firmenprofile</span><br>
<br>



<?

if(isset($_REQUEST[profilloeschen])) {
  if($biz_profileloeschbar=="true" and $_REQUEST[profilid]!=1) {
    echo "<a href=\"module/biz/firmen_profile.php?profilloeschenja=true&profilid=$_REQUEST[profilid]\"><b>*Profil jetzt löschen*</b></a><br><br>";
  }
  else {
    message("Das Profil kann nicht gelöscht werden.","error");
  }
}



if(isset($_REQUEST[profilloeschenja])) {
  $db->query("delete from biz_profile where adminid='$_SESSION[adminid]' and profilid='$_REQUEST[profilid]'");
  $db->query("delete from biz_layout where adminid='$_SESSION[adminid]' and profilid='$_REQUEST[profilid]'");  
  unlink($biz_imgpath."/logo".$_REQUEST[profilid].".jpg");
}
?>






&raquo; <a href="module/biz/firmen_profil_neu.php">Neues Firmenprofil</a><br>
<br>



<table width="80%" border="0" cellspacing="0" cellpadding="0">
<tr class="tb">
<td>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr class="th">
<td><b>Profil</b></td>
<td><b>Firma</b></td>
<td><b>Inhaber</b></td>
<td colspan="2"><b>Aktion</b></td>
<?

$res = $db->query("select profilid,profil,firma,inhaber from biz_profile where adminid='$_SESSION[adminid]' order by profil");
while($row=$db->fetch_array($res)) {
?>

</tr>
<tr class="tr">
<td><?=$row[profil]?></td>
<td><?=$row[firma]?></td>
<td><?=$row[inhaber]?></td>
<td width="16"><a href="module/biz/firmen_profil_editieren.php?profilid=<?=$row[profilid]?>"><img alt="Bearbeiten" src="img/edit.gif" border="0"></a></td>
<td width="16"><a href="module/biz/firmen_profile.php?profilloeschen=true&profilid=<?=$row[profilid]?>"><img alt="Löschen" src="img/trash.gif" border="0"></a></td>
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

