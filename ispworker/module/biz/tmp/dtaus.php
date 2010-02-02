$dta = new DTA("L","","","");
header("Content-Disposition: attachment; filename=\"dtaus.txt\"");
header("Content-type: text/plain");
header("Cache-control: public");
print $dta->show();
?>