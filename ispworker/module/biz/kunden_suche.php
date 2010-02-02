<?
$module = basename(dirname(__FILE__));
include("../../header.php");
?>


<i><b>Kunden</b></i><br>
<br>

Ihre Suche hat ergeben: <br><br>

<?
$res = $db->query("select * from biz_kunden where nachname='$suche' or mail='$suche' or kundenid='$suche'");
while($row=$db->fetch_array($res)) {

echo "<table>";
echo "<tr><td width=\"150\">Name</td><td>$row[vorname] $row[nachname]</td></tr>";
echo "<tr><td width=\"150\">Email</td><td><a href=\"mailto:$row[mail]\">$row[mail]</a></td></tr>";
echo "<tr><td width=\"150\">Telefon</td><td>$row[telefon]</td></tr>";
echo "<tr><td>Aktion</td><td><a href=\"module/biz/kunden_detail.php?kundenid=$row[kundenid]\">Details</a>
| <a href=\"module/biz/kunde_editieren.php?kundenid=$row[kundenid]\">Editieren</a> 
| <a href=\"module/biz/kunden.php?kundeloeschen=true&kundenid=$row[kundenid]\">Löschen</a>
| <a href=\"module/biz/kunden.php?merken=true&kundenid=$row[kundenid]\">Merken</a></td></tr></table>";
echo "<br><hr><br>";

}
?>















<?include("../../footer.php");?>
