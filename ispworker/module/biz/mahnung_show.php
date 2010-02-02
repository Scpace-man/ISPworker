<?@session_start();

include("../../include/config.inc.php");
include("../../include/common.inc.php");

include("./inc/pdfmahn.inc.php");

$directoutput=true;

pdfmahnung($_REQUEST[mahnid]);
Header('Content-Type: application/pdf');



?>