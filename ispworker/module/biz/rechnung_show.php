<?@session_start();

include("../../include/config.inc.php");
include("../../include/common.inc.php");
include("./inc/pdf.inc.php");

$directoutput=true;


if($_REQUEST[multipdf]!="true") 
{
	$multipdf=false;
    pdfinvoice($_REQUEST[rechnungid]);
	Header('Content-Type: application/pdf');
}
else 
{
	$multipdf=true;
    $request = "";

    // Wenn nur Druckrechnungen ausgegeben werden sollen
    if($_REQUEST[dr] == "true") $request.= " AND biz_kunden.sendmail='N'";
    
    $query = "SELECT biz_rechnungen.rechnungid, biz_kunden.sendmail";
    $query.= " FROM biz_rechnungen";
	$query.= " LEFT JOIN biz_kunden ON biz_kunden.kundenid=biz_rechnungen.kundenid";
	$query.= " WHERE rechnungid BETWEEN ".$_REQUEST[ri]." AND ".$_REQUEST[rj]." AND !isnull(biz_kunden.bezahlart) AND !isnull(biz_kunden.mwst)".$request;
	$query.= " ORDER BY biz_rechnungen.rechnungid";
	
    $res = $db->query($query);

    // Anzahl der Rechungen speichern
    $count = $db->num_rows($res);

    // Pr¸fen ob Rechungen gefunden wurden
    if($count > 0)
	{ 
		// Erste Rechnung hinzuf¸gen
		if($row=$db->fetch_array($res)) 
		{
			// Wenn es nur eine Rechnung gibt
			if($count == 1){
				 $multipdf = false;
			}
			
			pdfinvoice($row['rechnungid']);
		}
		$i = 1;
		
		// Wenn weitere Rechnungen vorhanden sind
		if($count > 1)
		{
			// Multipdf ˆffnen
			$openedmultipdf = true;
			
			// Weitere Rechnungen hinzuf¸gen
			while($row=$db->fetch_array($res)) 
			{
				pdfinvoice($row['rechnungid']);
				$i++;
				
				// Die vorletzte Rechnung finden
				if($count == $i+1) 
				{
					// Multipdf schlieﬂen
					$closemultipdf = true;
				}
			} // while
		} // if
	Header('Content-Type: application/pdf');		
	}
	else
	{
	  ?>
		<span style="font-family:Arial; font-size:12px">&nbsp;&nbsp;&nbsp;
		  Keine Rechungen von ReNr <?=$_REQUEST[ri]?> bis ReNr <?=$_REQUEST[rj]?> gefunden. 
		  <a href="" onClick="self.close();">Fenster schlieﬂen</a>
		</span>
	  <?
	}
    
}
?>