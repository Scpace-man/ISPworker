<?

// Konfigurationsdatei des Moduls "biz"

$modulename["biz"] = "Fakturierung";

// Einstellungen aus biz_settings beziehen

$myres = $db->query("select * from biz_settings");
$biz_settings = $db->fetch_array($myres);
$sett_biz = $biz_settings;

// Pfad zum tempor�ren Verzeichnis (chmod 777)

$biz_temppath    = dirname(__FILE__)."/../tmp";

// Pfad zum Verzeichnis f�r Dokumente

$biz_docpath     = dirname(__FILE__)."/../docs";

// Pfad zum Verzeichnis f�r Bilddateien

$biz_imgpath     = dirname(__FILE__)."/../img";

// Globaler MwSt Satz
$biz_mwstglobal = $biz_settings[mwstnational];

// Sollen Netto Preise ausgegeben werden ?
$biz_printnetto = false;


// Sind die Preise als Netto Preise eingegeben?
// Wenn ja, soll die Ausgabe angepasst werden.
$biz_inputnetto = false;

/*
// Vorlagen f�r Kommentare auf der Rechnung

$biz_kommentar_rechnung    = "Bitte ueberweisen Sie den Rechnungsbetrag auf das Konto <profilbankkonto>, BLZ <profilbankblz>.";
$biz_kommentar_lastschrift = "Der Rechnungsbetrag wird von Ihrem Konto <kontonummer>, BLZ <bankleitzahl> eingezogen.";
$biz_kommentar_vorkasse    = "Rechnungsbetrag dankend erhalten.";
*/

// Signierung von Rechnungen, optional
$biz_signproclib = dirname(__FILE__)."/logagency.inc.php";

// ** Folgende Einstellungen betreffen haupts�chlich den Demo Modus. **

// D�rfen Profile gel�scht werden?
// Achtung: Rechnungen werden nicht mitgel�scht und sind dann nicht mehr lesbar,
// bis ein Profil mit der gleichen profilid erstellt wird

$biz_profileloeschbar = true;

// D�rfen Kunden gel�scht werden ?

$biz_kundenloeschbar  = true;

// D�rfen die Allgemeinen Einstellungen ge�ndert werden ?

$biz_einstloeschbar   = true;


// Sprachdatei

if($_SESSION['language']=="")
    include(dirname(__FILE__)."/lang.deutsch.php");
else
    include(dirname(__FILE__)."/lang.".$_SESSION['language'].".php");
	
?>