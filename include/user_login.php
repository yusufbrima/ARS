<?php
session_start();
include('dbcon.php');
include('function_library.php');
if((isset($_GET['inputUsername']) && !empty($_GET['inputUsername']))&&(isset($_GET['myLoginPassword']) && !empty($_GET['myLoginPassword']))){
  $username = strtolower(mysql_fix_string(trim($_GET['inputUsername'])));//Custome function to prevent SQL and XSS injection attacks
  $password = strtolower(mysql_fix_string(trim($_GET['myLoginPassword'])));//Custome function to prevent SQL and XSS injection attacks
  $password = $password.$config['salt'];
  $password = hash('ripemd128',$password);
  //Checking if the user has confirmed their account
    $query 		= "SELECT * FROM user WHERE  password='$password' and username='$username'";
    $result =  $con->query($query);
    $row		= $result->fetch_array();//Fetching of user data from the database into the row array
    $num_row 	= mysqli_num_rows($result);
    $user_type = $row['user_type'];
    if ($num_row  <> 0){
        if(check_user_account_status($con,$username)){
              if(check_blocked_status($con,$username)){
                //$_SESSION['last_active']= time();
                setcookie('username',$username,time()+7*24*60*60);
                if(isset($_GET['inputkeepMeLoggedIn']) && !empty($_GET['inputkeepMeLoggedIn'])){
                  setcookie('pass',$password,time()+7*24*60*60);
                }else{
                  setcookie('pass',$password,time()-7*24*60*60);
                }
                if($user_type=='1'){
                    //echo "<em style='color:green;'>Success <span class='glyphicon glyphicon-ok'></span></em>";
                    $_SESSION['user_type_seeker'] = 1;
                    $_SESSION['seeker_username']=$row['username'];
                    location('seeker_dashboard.php');
                  }elseif($user_type=='0') {
                        echo "<em style='color:green;'>Success <span class='glyphicon glyphicon-ok'></span></em>";
                        $_SESSION['user_type_recruiter'] = 0;
                        $_SESSION['recruiter_username']=$row['username'];
                       location('recruiter_dashboard.php');
                    }
              }else{
                  echo "<span style='color:red'>Sorry, your account has been blocked. Please contact Adminitrator</span>";
              }
           }else {
             echo "Please confirm you account! <a href='confirmAccount.php'>here</a>";
           }
    }else{
     echo "<em style='color:red;'>Login failed</em>";
   }
}
?>
