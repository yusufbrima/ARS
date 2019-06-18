<?php
	$curl_handle=curl_init();
	$username = "yusufbrima";
	$password = "YusufBrima";
	$sender = "Team 3"; // not more then 11 characters
	$recipient = "23230198359"; // with international code without '+' eg. 23288234345
	$message = "Welcome to E-Connect";
	$url = "http://www.synapsesms.com/pushsms.php?usrid=$username&usrpass=$password&sender=".urlencode($sender)."&to=$recipient&msg=".urlencode($message);
	$urlresp = file_get_contents($url);

?>
