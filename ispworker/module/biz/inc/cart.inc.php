<?
if(isset($_SESSION["merkkunde"]) or count($_SESSION["merkzettel"]) != 0) 
{

    if($_SESSION["merkkunde"]!="")
    {
	$res = $db->query("select nachname, vorname from biz_kunden where kundenid='".$_SESSION["merkkunde"]."' ");
	$row = $db->fetch_array($res);

	echo '
	    <div style="position:absolute;
            right: 8px; 
	    top:60px; 
	    border:1px solid #AFAFAF; 
	    padding: 3px; 
	    width:180px; 
	    height: 40px; 
	    background-color:#F0F0F0;
	    font-family:verdana, Arial;
	    font-size:11px;
	    color:#000000;		     
	    "><b>Merkzettel</b> (<a href="module/biz/merkzettel.php">anzeigen</a>)<br>Kunde: '.$row["nachname"].", ".$row["vorname"].' 
	    <br><a href="module/biz/kunden_detail.php?kundenid='.$_SESSION["merkkunde"].'">Allg</a> | <a href="module/biz/kunden_detail_rechnungen.php?kundenid='.$_SESSION["merkkunde"].'">Rech</a> | <a href="module/biz/kunden_detail_mahnungen.php?kundenid='.$_SESSION["merkkunde"].'">Mahn</a> | <a href="module/biz/kunden_detail_domains.php?kundenid='.$_SESSION["merkkunde"].'">Dom</a> | <a href="module/biz/kunden_detail_docs.php?kundenid='.$_SESSION["merkkunde"].'">Doc</a> </div>';
    } 
    else 
    {
	echo '
	    <div style="position:absolute;
            right: 8px; 
	    top:60px; 
	    border:1px solid #AFAFAF; 
	    padding: 3px; 
	    width:180px; 
	    height: 40px; 
	    background-color:#F0F0F0;
	    font-family:verdana, Arial;
	    font-size:11px;
	    color:#000000;		     
	    "><b>Merkzettel</b> (<a href="module/biz/merkzettel.php">anzeigen</a>)<br><font color=red>Bitte einen Kunden auswählen</font></div>';
    }
}
?>