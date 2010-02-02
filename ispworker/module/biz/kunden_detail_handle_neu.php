<?
$module = basename(dirname(__FILE__));
include("./inc/functions.inc.php");
include("../../header.php");



include("./inc/reiter1.layout.php");

$bgcolor[0]   = "#f0f0f0";
$linecolor[0] = "#000000";

$bgcolor[3]   = "#ffffff";
$linecolor[3] = "#ffffff";

include("./inc/reiter1.php");

if($_REQUEST[action]=="new") {

    $save = "";

    if(!preg_match("/^([a-zA-Z0-9\-\s\.,#\/]|(\xC3\x84)|(\xC3\x96)|(\xC3\x9c)|(\xC3\x9F)|(\xC3\xA4)|(\xC3\xBC)|(\xC3\xB6))+$/",$_REQUEST[vorname]))
	$save  = "#vorname";

    if(!preg_match("/^([a-zA-Z0-9\-\s\.,#\/]|(\xC3\x84)|(\xC3\x96)|(\xC3\x9c)|(\xC3\x9F)|(\xC3\xA4)|(\xC3\xBC)|(\xC3\xB6))+$/",$_REQUEST[nachname]))
	$save .= "#nachname";

    if(trim($_REQUEST[firma])!="" && !preg_match("/^([a-zA-Z0-9\&amp;\-\s\.,#\/]|(\xC3\x84)|(\xC3\x96)|(\xC3\x9c)|(\xC3\x9F)|(\xC3\xA4)|(\xC3\xBC)|(\xC3\xB6))+$/",$_REQUEST[firma]))
	$save .= "#firma";

    if(!preg_match("/^[0-9a-zA-Z\-_\.]{1,64}\@[0-9a-zA-Z\-_\.]{3,64}$/",$_REQUEST[email]))
	$save .= "#email";

    if(!preg_match("/^\+\d+\.\d+$/",$_REQUEST[fax]))
	$save .= "#fax";

    if(!preg_match("/^\+\d+\.\d+$/",$_REQUEST[telefon]))
	$save .= "#telefon";

	

    if($save == "")
    {

	$res = $db->query("select * from biz_handles where vorname='$_REQUEST[vorname]' and nachname='$_REQUEST[nachname]' and strasse='$_REQUEST[strasse]' 
			   and strassenr='$_REQUEST[strassenr]' and plz='$_REQUEST[plz]' and ort='$_REQUEST[ort]'");

	$row = $db->fetch_array($res);
	
	if($db->num_rows($res) > 0) message("Ein Handle ($_REQUEST[vorname],$_REQUEST[nachname],$_REQUEST[strasse] $_REQUEST[strassenr],$_REQUEST[plz],$_REQUEST[ort]) mit der externen HandleID $row[exthandleid] existiert bereits.",error);
	else
	{
	    $db->query("insert into biz_handles (kundenid,vorname,nachname,kontakttyp,firma,strasse,strassenr,plz,ort,land,telefon,fax,email,geschlecht)
	    	    values ('$_REQUEST[kundenid]','$_REQUEST[vorname]','$_REQUEST[nachname]','$_REQUEST[kontakttyp]','$_REQUEST[firma]','$_REQUEST[strasse]','$_REQUEST[strassenr]','$_REQUEST[plz]','$_REQUEST[ort]','$_REQUEST[land]','$_REQUEST[telefon]','$_REQUEST[fax]','$_REQUEST[email]','$_REQUEST[sex]')
		    ");

	    message("Handle ist gespeichert.");
	}
    }
    else
    {
	message("Eingabefehler, prüfen Sie die Daten. ($save)","error");
    }

}


?>

<form action="module/biz/kunden_detail_handle_neu.php?action=new&kundenid=<?=$_REQUEST[kundenid]?>" method="post">
<?
$t = $html->table(0);
$t->addcol("Neues Handle",530,3);
$t->cols();

$t->addrow("Typ");
$t->addrow("<select name=\"kontakttyp\"><option>PERS</option><option>ORG</option></select>");
$t->rows();

$t->addrow("Anrede");
$t->addrow("<select name=\"sex\"><option value=\"MALE\">Herr</option><option value=\"FEMALE\">Frau</option></select>");
$t->rows();

$t->addrow("Vorname");
$t->addrow("<input type=\"text\" name=\"vorname\" maxlength=\"255\" value=\"$_REQUEST[vorname]\">");
$t->rows();

$t->addrow("Nachname");
$t->addrow("<input type=\"text\" name=\"nachname\" maxlength=\"255\" value=\"$_REQUEST[nachname]\">");
$t->rows();

$t->addrow("Firma");
$t->addrow("<input type=\"text\" name=\"firma\" maxlength=\"255\" value=\"$_REQUEST[firma]\"> <font size=\"1\">optional</font>");
$t->rows();

$t->addrow("Strasse und Nr");
$t->addrow("<input type=\"text\" name=\"strasse\" maxlength=\"255\" value=\"$_REQUEST[strasse]\"> <input type=\"text\" name=\"strassenr\" value=\"$_REQUEST[strassenr]\" size=\"3\" maxlength=\"64\">");
$t->rows();

$t->addrow("Plz");
$t->addrow("<input type=\"text\" name=\"plz\" size=\"6\" maxlength=\"32\" value=\"$_REQUEST[plz]\">");
$t->rows();

$t->addrow("Ort");
$t->addrow("<input type=\"text\" name=\"ort\" maxlength=\"255\" value=\"$_REQUEST[ort]\">");
$t->rows();

$t->addrow("Land");
$t->addrow("<input type=\"text\" name=\"land\" size=\"2\" maxlength=\"2\" value=\"DE\">");
$t->rows();

$t->addrow("E-Mail");
$t->addrow("<input type=\"text\" name=\"email\" maxlength=\"255\" value=\"$_REQUEST[email]\">");
$t->rows();

$t->addrow("Telefon");
$t->addrow("<input type=\"text\" name=\"telefon\" maxlength=\"255\" value=\"$_REQUEST[telefon]\">");
$t->rows();

$t->addrow("Fax");
$t->addrow("<input type=\"text\" name=\"fax\" maxlength=\"255\" value=\"$_REQUEST[fax]\">");
$t->rows();


$t->close();
?>

<input type="submit" value="Speichern">
<br>
<br>
<br>
<br>


<?include("../../footer.php");?>
