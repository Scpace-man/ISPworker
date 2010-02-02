<?
$module = basename(dirname(__FILE__));
$noauth = true;
include("../../header.php");

if(isset($_REQUEST[bestellid])) {


    $resj = $db->query("select * from order_jobs where jobid='$_REQUEST[jobid]'");
    $rowj = $db->fetch_array($resj);

    $res  = $db->query("select domains from biz_bestellungen where bestellid='$_REQUEST[bestellid]'"); 
    $row  = $db->fetch_array($res);

    $rowj[shellcommand] = str_replace("#passwort#",$passwort,$rowj[shellcommand]);
    $rowj[shellcommand] = str_replace("#ip#",$ip,$rowj[shellcommand]);
    $rowj[shellcommand] = str_replace("#domains#",$row[domains],$rowj[shellcommand]);
 

    $o = shell_exec("$rowj[shellcommand]");
}


?>



<?include("../../footer.php");?>