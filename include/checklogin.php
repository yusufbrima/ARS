<?php
session_start();
include('dbcon.php');
include('function_library.php');
if((isset($_GET['old_username']) && !empty($_GET['old_username'])) && (isset($_GET['pass']) && !empty($_GET['pass']))){
  $username = strtolower(mysql_fix_string(trim($_GET['old_username'])));//Custome function to prevent SQL and XSS injection attacks
  $password = strtolower(mysql_fix_string(trim($_GET['pass'])));//Custome function to prevent SQL and XSS injection attacks
  $password = $password.$config['salt'];
  $password = hash('ripemd128',$password);
  //Checking if the user has confirmed their account
  $query 		= "SELECT * FROM user WHERE  password='$password' and username='$username'";
  $result =  $con->query($query);
  $num_row 	= mysqli_num_rows($result);
  $row		= $result->fetch_array();//Fetching of user data from the database into the row array
  $user_type = $row['user_type'];
  if ($num_row  <> 0){
      echo "Account confirmed. Update your Username";
  }else{
    echo "<em style='color:red;'>Authentication failed</em>";
  }
}

?>
