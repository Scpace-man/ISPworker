<?

/* 

  == Preis Kalkulator Konfiguration ==

  Jede Kategorie wird durch ein Objekt repräsentiert.
  Jedes Objekt ist eine Instanz der Klasse "category".

  Das Objekt stellt mehrere Methoden zur Verfügung,
  um die Auswahlmöglichkeiten für die Kategorie festzulegen. 

*/ 


// Wir erstellen ein neue Kategorie

$k1  = new calculator("Einsteiger und Kleinbenutzer1");

// Wir müssen definieren, welcher Artikel für z.B. eine Einheit verwendet werden soll.
// (z.B. 1 MB Webspace)

// Also übergeben wir die ArtikelNr als Parameter.

$k1->set_article_webspace(10);
$k1->set_article_traffic(11);
$k1->set_article_subdomain(15);
$k1->set_article_mailaccount(12);
$k1->set_article_mailforwarder(13);
$k1->set_article_autoresponder(14);
$k1->set_article_shellaccount(16);
$k1->set_article_cronjob(17);
$k1->set_article_mysql(18);
$k1->set_article_ftp(19);

// Jetzt legen wir die Minima und Maxima für Webspace etc. fest.

$k1->set_webspace(1,5000);
$k1->set_traffic(1,20);
$k1->set_subdomain(1,500);
$k1->set_mailaccount(1,500);
$k1->set_mailforwarder(1,500);
$k1->set_autoresponder(1,500);
$k1->set_shellaccount(true); // Checkbox aktiviert
$k1->set_cronjob(1,10);
$k1->set_mysql(1,50);
$k1->set_ftp(1,100);

// Hier wird die Artikelnummer des Artikels angegeben,
// der als Bestell Vorlage dienen soll.
// Dieser Artikel ist mit dem Order-Modul verknüpft,
// dort sind u.a. die bestellbaren Top-Level-Domain definiert.

$k1->set_orderarticle("20");


// Kategorie 2

$k2  = new calculator("Einsteiger und Kleinbenutzer");

$k2->set_article_webspace(10);
$k2->set_article_traffic(11);
$k2->set_article_subdomain(15);
$k2->set_article_mailaccount(12);
$k2->set_article_mailforwarder(13);
$k2->set_article_autoresponder(14);
$k2->set_article_shellaccount(16);
$k2->set_article_cronjob(17);
$k2->set_article_mysql(18);
$k2->set_article_ftp(19);
$k2->set_webspace(1,5001);
$k2->set_traffic(1,21);
$k2->set_subdomain(1,501);
$k2->set_mailaccount(1,501);
$k2->set_mailforwarder(1,501);
$k2->set_autoresponder(1,501);
$k2->set_shellaccount(false); // Checkbox deaktiviert
$k2->set_cronjob(1,11);
$k2->set_mysql(1,51);
$k2->set_ftp(1,101);

$k2->set_orderarticle(21);


// Kategorie 3

$k3  = new calculator("Profis und Poweruser");

$k3->set_article_webspace(10);
$k3->set_article_traffic(11);
$k3->set_article_subdomain(15);
$k3->set_article_mailaccount(12);
$k3->set_article_mailforwarder(13);
$k3->set_article_autoresponder(14);
$k3->set_article_shellaccount(16);
$k3->set_article_cronjob(17);
$k3->set_article_mysql(18);
$k3->set_article_ftp(19);
$k3->set_webspace(1,5002);
$k3->set_traffic(1,22);
$k3->set_subdomain(1,502);
$k3->set_mailaccount(1,502);
$k3->set_mailforwarder(1,502);
$k3->set_autoresponder(1,502);
$k3->set_shellaccount(true); // Checkbox aktiviert
$k3->set_cronjob(1,12);
$k3->set_mysql(1,52);
$k3->set_ftp(1,102);

$k3->set_orderarticle(22);


// Wir speichern alle drei Kategorien in einem Array

$kategorien = array($k1,$k2,$k3);


// Hier prüfen wir, ob die Kategorie "Kalkulator Pakete" existiert

$savetocategory = "Kalkulator Pakete";

$res = $db->query("select * from biz_produktkategorien where bezeichnung='Kalkulator Pakete'");
$row = $db->fetch_array($res);

$savetocategoryid = $row[katid];

if($db->num_rows($res)==0) { die("<b>Fehler: Bitte legen Sie die Produktkategorie >>Kalkulator Pakete<< an.</b>"); }

?>





