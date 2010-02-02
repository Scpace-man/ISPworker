<?

// Configdatei des Moduls "ticket" des panda Projekts
// bzw. der Software ISPWorker

$modulename["ticket"] = "Tickets";


// Pfad zum temporären Verzeichnis (chmod 777)

$ticket_temppath    = dirname(__FILE__)."/../tmp";

$ticket_denyhtmlmail = true;

if($_SESSION['language']=="") 
    include(dirname(__FILE__)."/lang.deutsch.php");
else
    include(dirname(__FILE__)."/lang.".$_SESSION['language'].".php"); 

?>