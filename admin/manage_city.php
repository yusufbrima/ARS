<?php
session_start();
if(!isset($_SESSION['admin_username']) && empty($_SESSION['admin_username'])){
  if(!isset($_SESSION['admin_active']) && empty($_SESSION['admin_active'])){
    $_SESSION['warning'] = "Access denied, you must login first!";
    header('location: index.php?warning=Authentication required, you must login first to access this page!');
  }
}else{
  $username=$_SESSION['admin_username'];
}
include_once "include/class.inc.php";
include('../include/dbcon.php');
include('../include/function_library.php');
 ?>
 <?php
    $query =  "SELECT * FROM city";
    $result_flag =false;
   $result = $con->query($query);
   $city_table ="<div><table class='table table-striped' border='0'>";
   $city_table .="<caption class='h3'>List of Cities</caption>";
   $city_table .="<tr><th>ID</th><th>City</th></tr>";
   $num_row 	= mysqli_num_rows($result);
   if($num_row>0){
     for($i=0;$i<$num_row;$i++){
       $row = mysqli_fetch_array($result);
       //print_r($row);
       $result_flag=true;
       $id=$row['id'];
       $city_table .= "<tr><td>{$row['id']}</td><td>{$row['name']}</td</tr>";
     }
    $city_table .="</table></div>";
   }
 ?>
<!DOCTYPE html>
<html>
  <head>
    <title>Welcome to E-JobFinder: One-stop-shop for employment</title>
    <?php include_once "header.php"; ?>
  </head>
  <body>
    <nav class="navbar navbar-inverse navbar-fixed-top">
     <div class="container-fluid">
       <div class="navbar-header">
         <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
         <a class="navbar-brand" href="dashboard.php"><img src="../images/e-jobFinder.png" alt="Logo"/></a>
       </div>
        <div class="collapse navbar-collapse" id="myNavbar">
       <ul class="nav navbar-nav navbar-right">
         <li><a href="dashboard.php">Welcome <?php echo $username; ?></a></li>
        <li class="dropdown active">
         <a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-tasks"></span> Manage Task
         <span class="caret"></span></a>
         <ul class="dropdown-menu">
           <li><a href="manage_job_category.php"><span class="glyphicon glyphicon-user"></span> Add Job Category</a></li>
           <li><a href="manage_field_of_study.php"><span class="glyphicon glyphicon-briefcase"></span> <span class="badge"></span> Add Field Of Study</a></li>
           <li><a href="manage_city.php"><span class="glyphicon glyphicon-envelope"></span> Add City</a></li>
           <li><a href="manage_province.php"><span class="glyphicon glyphicon-envelope"></span> Add Province</a></li>
           <li><a href="manage_qualification.php"><span class="glyphicon glyphicon-envelope"></span> Add Qualiification</a></li>
         </ul>
       </li>
       <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-user"></span> Manage Users
        <span class="caret"></span></a>
        <ul class="dropdown-menu">
          <li><a href="block_user.php"><span class="glyphicon glyphicon-user"></span> Block User</a></li>
          <li><a href="delete_user.php"><span class="glyphicon glyphicon-briefcase"></span> <span class="badge"></span> De-activate Account</a></li>
          <li><a href="add_new_admin.php"><span class="glyphicon glyphicon-envelope"></span> Add Admin</a></li>
          <li><a href="view_admins.php"><span class="glyphicon glyphicon-eye-open"></span> View Admins</a></li>
          <li><a href="user_feedback.php"><span class="glyphicon glyphicon-eye-open"></span> User Feedbacks</a></li>
        </ul>
      </li>
         <li><a href="logout.php"><span class="glyphicon glyphicon-off"></span> Logout</a></li>
       </ul>
     </div>
      </div>
   </nav>
     <div class="menu-spacer"></div>
     <!--Clearing of the float rule-->
       <div class="clear-fix"></div>
         <div class="container">
           <div class="row">
              <div class="col-md-6">
                <form method="post" action="<?php outputData($_SERVER['PHP_SELF']); ?>">
                      <input type="text" name="inputCity" id="inputCity" value="" placeholder="Enter City Name" class="form-control"/><br />
                  <div class="form-inline">
                     <input type="submit" name="input_add_city" id="input_add_city" value="Add City"  class="form-control btn btn-primary"/>
                 </div>
                </form>
              <?php
                  if(isset($_POST['input_add_city'])){
                    if(isset($_POST['inputCity'])&&!empty($_POST['inputCity'])){
                      $city = mysql_fix_string(trim($_POST['inputCity']));
                      $table_name="city";
                      if(check_data_insertion($con,$table_name,$city)){
                        $error = "<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
                        $error .= "<strong>System Error<br /></strong>City already exists</div>";
                        echo $error;
                      }else{
                        if(add_data_category($con,$table_name,$city)){
                          $error = "<div class='alert alert-success'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
                          $error .= "<strong>Success<br /></strong>City Added Successfully</div>";
                          echo $error;
                        }
                      }
                    }else{
                      $error = "<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
                      $error .= "<strong>Input Error<br /></strong>Please Enter City Name</div>";
                      echo $error;
                    }
                  }
              ?>
              </div>
            </div>
            <div id="all_names">
              <?php
                if($result_flag){
                  echo $city_table;
                }
              ?>
           </div>
        </div>
 <?php include "footer.php"; ?><!--Site footer node -->
 <?php include "library.php"; ?><!--Script Library files -->
 </body>
 <script type="text/javascript" src="js/script.js"></script>
 </html>
