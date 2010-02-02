<?$module = basename(dirname(__FILE__));
include("../../header.php");?>

<span class="htitle">Pr�fen der Integrit�t</span><br>
<br>

<p>
Dieses Programm bietet Ihnen die M�glichkeit, die Dateien Ihrer ISPworker Installation
auf Integrit�t hin zu �berpr�fen. Es listet den Status CHANGED oder UNCHANGED
f�r jede Datei auf. Sie k�nnen Ihre Installation mit der aktuellsten verf�gbaren
ISPworker Version vergleichen, Sie k�nnen aber auch �ltere Versionen als Vergleichsbasis
verwenden.
</p>
<p>
<u>Beispiel f�r die Verwendung:</u> Sie haben ein eigenes Logo hochgeladen oder
�nderungen an ISPworker PHP Scripts vorgenommen. Sie wollen auf die neueste Version
updaten und m�chten wissen, welche Dateien Sie nach dem Update wieder modifizieren m�ssen,
um Ihre �nderungen einzupflegen.
</p>

<form action="module/system/md5check.php?action=check" method="post">
Installierte Version: <?echo VERSION;?><br>
<br>
<?
echo file_get_contents("http://www.ispware.de/_files/ispworker-md5list.html");
?>
</form>
<hr size="1" noshade>


<?

function create_md5($fulldir) {
    global $str;

    if (is_dir($fulldir)) {
	if ($dh = opendir($fulldir)) {
	    while (($file = readdir($dh)) !== false) {
		
		if(is_dir($fulldir . $file) == FALSE and $file != "." and $file != "..") {
		    
		    $f = str_replace(dirname(__FILE__)."/../../","",$fulldir);
		    
		    if( strstr($str, "$f$file") ) { $a = explode("$f$file",$str); $b = explode("\n",$a[1]);
			echo "<tr>\n";    
			if( trim($b[0]) ==  md5_file($fulldir . $file) ) echo "<td bgcolor=\"#ffffff\">$fulldir$file</td><td bgcolor=\"#ffffff\"><font color=\"green\">UNCHANGED</font></td>\n";
			else echo "<td bgcolor=\"#ffffff\">$fulldir$file</td> <td bgcolor=\"#ffffff\"><font color=\"red\">CHANGED</font></td>\n";
			echo "</tr>\n";
		    } 
		}
		if(is_dir($fulldir . $file) == TRUE and $file != "." and $file != "..") create_md5($fulldir . $file ."/");
	    }
	    closedir($dh);
	}
    }
}


if($_REQUEST["action"]=="check")
{
    $md5path = dirname(__FILE__)."/../../";

    echo "<i>Vergleiche mit $_REQUEST[md5list] ...</i><br><br>\n";
?>
    <table border="0" cellspacing="0" cellpadding="0">
    <tr bgcolor="#cccccc">
    <td>
    <table width="600" border="0" cellspacing="1" cellpadding="3">
    <tr bgcolor="#e7e7e7">
    <td><b>Datei</b></td>
    <td><b>Status</b></td>
    </tr>  
<?
    $str = file_get_contents("http://www.ispware.de/_files/".$_REQUEST["md5list"]);    
    $x = explode("\n",$str);
    create_md5($md5path);
?>
    </table>
    </td>
    </tr>
    </table>
<?
}
?>

<br>
<br>

<?include("../../footer.php");?>

