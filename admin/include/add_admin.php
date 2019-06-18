<?php
include_once ('class.inc.php');
include('../../include/dbcon.php');
include('../../include/function_library.php');
if(isset($_GET['inputAdminUsername']) && !empty($_GET['inputAdminUsername'])){
  if(isset($_GET['myAdminPassword']) && !empty($_GET['myAdminPassword'])){
    $username =strtolower(mysql_fix_string(trim($_GET['inputAdminUsername'])));
    $password = strtolower(mysql_fix_string(trim($_GET['myAdminPassword'])));
    $password = $password.$config['salt'];//Salting the user password before hashing
    $password = hash('ripemd128',$password);
    if(check_admin_user($con,$username)){
        if (add_admin($con, $password,$username)){
            echo "<em id='success'>Account Successfully $username created <span class='glyphicon glyphicon-ok'></span></em>";
            location('dashboard.php');
        }else{
            echo "<em id='errorMessage'>Error in creating account</em>";
        }
    }else{
      echo "<span id='errorMessage'>Username already exists!</span>";
    }
  }else{
    echo "<span id='errorMessage'>Please enter a valid password</span>";
  }
}else{
  echo "<span id='errorMessage'>Please enter a valid username</span>";
}
?>
