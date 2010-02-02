<?
include("header.php");
include("./include/calculator.class.php");
include("./include/calculator.inc.php");

$_SESSION[calc]=true;
$k = $kategorien[$kategorie];

$sum_webspace      = $webspace      * $k->getprice($k->article_webspace);
$sum_traffic       = $traffic       * $k->getprice($k->article_traffic);

if($subdomainallow==true) {
    $sum_subdomain     = $subdomain     * $k->getprice($k->article_subdomain);
}

if($mailallow==true) {
    $sum_mailaccount   = $mailaccount   * $k->getprice($k->article_mailaccount);
    $sum_mailforwarder = $mailaccount   * $k->getprice($k->article_mailforwarder);
    $sum_autoresponder = $autoresponder * $k->getprice($k->article_autoresponder);
}

if($mysqlallow==true) {
    $sum_mysql         = $mysql         * $k->getprice($k->article_mysql);
}

$sum_ftp           = $ftp           * $k->getprice($k->article_ftp);

if($shellaccount==true) {
    $sum_shellaccount = $k->getprice($k->article_shellaccount);
}
if($cronjob==true) {
    $sum_cronjob       = $k->getprice($k->article_cronjob);
}

$sum_webspace 	   = sprintf("%.2f",$sum_webspace);
$sum_traffic	   = sprintf("%.2f",$sum_traffic);
$sum_subdomain     = sprintf("%.2f",$sum_subdomain);
$sum_mailaccount   = sprintf("%.2f",$sum_mailaccount);
$sum_mailforwarder = sprintf("%.2f",$sum_mailforwarder);
$sum_autoresponder = sprintf("%.2f",$sum_autoresponder);
$sum_cronjob	   = sprintf("%.2f",$sum_cronjob);
$sum_shellaccount  = sprintf("%.2f",$sum_shellaccount);
$sum_mysql 	   = sprintf("%.2f",$sum_mysql);
$sum_ftp 	   = sprintf("%.2f",$sum_ftp);


$total = $sum_webspace + $sum_traffic + $sum_subdomain + $sum_mailaccount + $sum_mailforwarder + $sum_autoresponder + $sum_cronjob + $sum_mysql + $sum_ftp + $sum_shellaccount;
$total = sprintf("%.2f",$total);

// Individuellen Artikel erstellen

$time = time();
$_SESSION[calc_id] = $time;

$_SESSION[calc_components] .= $webspace." x ".$k->getarticlename($k->article_webspace)."\n";
$_SESSION[calc_components] .= $traffic." x ".$k->getarticlename($k->article_traffic)."\n";
$_SESSION[calc_components] .= $subdomain." x ".$k->getarticlename($k->article_subdomain)."\n";
$_SESSION[calc_components] .= $mailaccount." x ".$k->getarticlename($k->article_mailaccount)."\n";
$_SESSION[calc_components] .= $mailforwarder." x ".$k->getarticlename($k->article_mailforwarder)."\n";
$_SESSION[calc_components] .= $mysql." x ".$k->getarticlename($k->article_mysql)."\n";
$_SESSION[calc_components] .= $ftp." x ".$k->getarticlename($k->article_ftp)."\n";
$_SESSION[calc_components] .= "shellaccount = $shellaccount\n";
$_SESSION[calc_components] .= "cronjob = $cronjob\n";;



$res_vorlage = $db->query("select * from order_artikel where artikelid='$k->orderarticle'");
$row_vorlage = $db->fetch_array($res_vorlage);

$db->query("insert into biz_produkte (katid,bezeichnung,preis,abrechnung,beschreibung) values ('$savetocategoryid','Webspace Angebot #$time','$total','monatlich','$_SESSION[calc_components]')");
$pid = $db->insert_id();

$db->query("insert into order_artikel (artikelid,kurztext,domainaktiv,kkaktiv,anzdomains,minanzdomains,tlds,tldsmitaufpreis) 
            values ('$pid','time: $time','$row_vorlage[domainaktiv]','$row_vorlage[kkaktiv]','$row_vorlage[anzdomains]','$row_vorlage[minanzdomains]','$row_vorlage[tlds]','$row_vorlage[tldsmitaufpreis]')");

?>




<h3>Kalkulator</h3>
<hr size="1" noshade>
<br>


<form name="calc" action="order_whois.php?paketid=<?=$pid?>" method="post">

<br>
<br>

<span class="title">Zusammenfassung</span><br>
<br>

<table border="0" cellpadding="0" cellspacing="0">
<tr>
  <td width="50" align="right"><?=$webspace?> x</td>
  <td width="10"></td>
  <td width="200"><?=$k->getarticlename($k->article_webspace)?></td>
  <td align="right"><?=$sum_webspace?></td>
  <td width="6"></td>
  <td>Euro</td> 
</tr>
<tr>
  <td width="50" align="right"><?=$traffic?> x</td>
  <td width="10"></td>
  <td width="200"><?=$k->getarticlename($k->article_traffic)?></td>
  <td align="right"><?=$sum_traffic?></td>
  <td width="6"></td>
  <td>Euro</td> 
</tr>
<?if($subdomainallow==true) {?>
<tr>
  <td width="50" align="right"><?=$subdomain?> x</td>
  <td width="10"></td>
  <td width="200"><?=$k->getarticlename($k->article_subdomain)?></td>
  <td align="right"><?=$sum_subdomain?></td>
  <td width="6"></td>
  <td>Euro</td> 
</tr>
<?}?>
<?if($mailallow==true) {?>
<tr>
  <td width="50" align="right"><?=$mailaccount?> x</td>
  <td width="10"></td>
  <td width="200"><?=$k->getarticlename($k->article_mailaccount)?></td>
  <td align="right"><?=$sum_mailaccount?></td>
  <td width="6"></td>
  <td>Euro</td> 
</tr>
<tr>
  <td width="50" align="right"><?=$mailforwarder?> x</td>
  <td width="10"></td>
  <td width="200"><?=$k->getarticlename($k->article_mailforwarder)?></td>
  <td align="right"><?=$sum_mailforwarder?></td>
  <td width="6"></td>
  <td>Euro</td> 
</tr>
<tr>
  <td width="50" align="right"><?=$autoresponder?> x</td>
  <td width="10"></td>
  <td width="200"><?=$k->getarticlename($k->article_autoresponder)?></td>
  <td align="right"><?=$sum_autoresponder?></td>
  <td width="6"></td>
  <td>Euro</td> 
</tr>
<?}?>
<?if($mysqlllow==true) {?>
<tr>
  <td width="50" align="right"><?=$mysql?> x</td>
  <td width="10"></td>
  <td width="200"><?=$k->getarticlename($k->article_mysql)?></td>
  <td align="right"><?=$sum_mysql?></td>
  <td width="6"></td>
  <td>Euro</td> 
</tr>
<?}?>
<tr>
  <td width="50" align="right"><?=$ftp?> x</td>
  <td width="10"></td>
  <td width="200"><?=$k->getarticlename($k->article_ftp)?></td>
  <td align="right"><?=$sum_ftp?></td>
  <td width="6"></td>
  <td>Euro</td> 
</tr>



<?if($cronjob==true) {?>
<tr>
  <td width="50" align="right">1 x</td>
  <td width="10"></td>
  <td width="200"><?=$k->getarticlename($k->article_cronjob)?></td>
  <td align="right"><?=$sum_cronjob?></td>
  <td width="6"></td>
  <td>Euro</td> 
</tr>
<?}?>



<?if($shellaccount==true) {?>
<tr>
  <td width="50" align="right">1 x</td>
  <td width="10"></td>
  <td width="200"><?=$k->getarticlename($k->article_shellaccount)?></td>
  <td align="right"><?=$sum_shellaccount?></td>
  <td width="6"></td>
  <td>Euro</td> 
</tr>
<?}?>

<tr>
  <td colspan="6"><hr size="1"noshade></td>
</tr>
<tr>
  <td width="50" align="right"></td>
  <td width="10"></td>
  <td width="200"><b>Summe:</b></td>
  <td align="right"><?=$total?></td>
  <td width="6"></td>
  <td>Euro</td> 
</tr>
</table>
<br>
<br>

<input type="submit" name="submit" value="Weiter"> 
</form>

<form action="calculator.php?cancel=true&paketid=<?=$pid?>&id=<?=$time?>" methdo="post">
<input type="submit" name="submit" value="Abbrechen"> 
</form>
<br>
<br>

<?include("footer.php");?>
