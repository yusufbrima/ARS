<?php
//example to call :
require("include/class.filetotext.php");
$docObj = new Filetotext("_job_posts/323e001ee461ae5d4d2b9dd2760a2da5.pdf");  //$docObj = new Filetotext("test.pdf");
$return = $docObj->convertToText();
//var_dump( $return ) ;
echo "<h1>Out put of a text file...</h1></br />";

echo $return;
?>
