<?
$module = basename(dirname(__FILE__));
include("../../header.php");
include("./inc/functions.inc.php");

$time = time();
?>

<b>Ticket Suche</b><br>
<br>

<form action="module/ticket/anfragen.php?typ=suche" method="post">
Suchbegriff <input type="text" name="q"> 
Feld <select name="feld">
<option value="ticketid">TicketID</option>
<option value="betreff">Betreff</option>
<option value="nachricht">Nachricht</option>
<option value="frommail">Absender</option>
<option value="tomail">Empfänger</option>
</select>

<input type="submit" value="Suchen">
</form>



<?include("../../footer.php");?>