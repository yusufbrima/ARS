<?php
/*PHPMailer configurations*/
DEFINE('SMTPDebug', 0);
DEFINE('from','yusufbrima@gmail.com');
DEFINE('fromName', 'Econnect');
DEFINE('mailer','smtp');
DEFINE('host','smtp.gmail.com');
DEFINE('SMTPSecure',"ssl");
DEFINE('port',587);
DEFINE('username','yourmail@gmail.com');
DEFINE('password','yourpassword');

$config['timeout']=5*60;//Seconds to destory the session data
$config['salt'] ='&@~p\[?kj';
$config['secret_code']= mt_rand(100000,999999);
$config['user_type']='';
$db['server']='localhost';
$db['user']='root';
$db['pass']='';
$db['db']='emp_depot';
$con = mysqli_connect($db['server'],$db['user'],$db['pass'],$db['db']);
if($con->connect_error){
    die("Connection failed: " . $con->connect_error);
}
?>
