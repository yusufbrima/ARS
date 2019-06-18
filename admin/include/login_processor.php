<?php
session_start();
include('../../include/dbcon.php');
include('../../include/function_library.php');
if((isset($_GET['inputUsername']) && !empty($_GET['inputUsername']))&&(isset($_GET['myLoginPassword']) && !empty($_GET['myLoginPassword']))){
  $username = strtolower(mysql_fix_string(trim($_GET['inputUsername'])));//Custome function to prevent SQL and XSS injection attacks
  $password = strtolower(mysql_fix_string(trim($_GET['myLoginPassword'])));//Custome function to prevent SQL and XSS injection attacks
  $password = $password.$config['salt'];
  $password = hash('ripemd128',$password);
  //Checking if the user has confirmed their account
    $query 		= "SELECT * FROM admin WHERE  password='$password' and username='$username'";
    $result =  $con->query($query);
    $row		= $result->fetch_array();//Fetching of user data from the database into the row array
    $num_row 	= mysqli_num_rows($result);
    if ($num_row  <> 0){
      echo "<em style='color:green;'>Success <span class='glyphicon glyphicon-ok'></span></em>";
      $_SESSION['admin_active'] = true;
      $_SESSION['admin_username']=$row['username'];
        location('dashboard.php');
    }else{
     echo "<em style='color:red;'>Login failed</em>";
   }
}
?>
