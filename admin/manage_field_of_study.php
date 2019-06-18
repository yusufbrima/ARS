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
    $query =  "SELECT * FROM career_field";
    $result_flag =false;
   $result = $con->query($query);
   $career_field ="<div><table class='table table-striped' border='0'>";
   $career_field .="<caption class='h3'>List of Fields of Study</caption>";
   $career_field .="<tr><th>ID</th><th>Field Of Study</th></tr>";
   $num_row 	= mysqli_num_rows($result);
   if($num_row>0){
     for($i=0;$i<$num_row;$i++){
       $row = mysqli_fetch_array($result);
       //print_r($row);
       $result_flag=true;
       $id=$row['id'];
       $career_field .= "<tr><td>{$row['id']}</td><td>{$row['name']}</td</tr>";
     }
    $career_field .="</table></div>";
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
                      <input type="text" name="inputCareerField" id="inputCareerField" value="" placeholder="Enter Field of Study" class="form-control"/><br />
                  <div class="form-inline">
                     <input type="submit" name="input_career_field" id="input_career_field" value="Add Field of Study"  class="form-control btn btn-primary"/>
                 </div>
                </form>
                <?php
                    if(isset($_POST['input_career_field'])){
                      if(isset($_POST['inputCareerField'])&&!empty($_POST['inputCareerField'])){
                        $job_name = mysql_fix_string(trim($_POST['inputCareerField']));
                        $table_name="career_field";
                        if(check_data_insertion($con,$table_name,$job_name)){
                          $error = "<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
                          $error .= "<strong>System Error<br /></strong>Field of Study already exists</div>";
                          echo $error;
                        }else{
                          if(add_data_category($con,$table_name,$job_name)){
                            $error = "<div class='alert alert-success'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
                            $error .= "<strong>Success<br /></strong>Field of Study Added Successfully</div>";
                            echo $error;
                          }
                        }
                      }else{
                        $error = "<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
                        $error .= "<strong>Input Error<br /></strong>Please Enter Field of Study</div>";
                        echo $error;
                      }
                    }
                ?>
              </div>
            </div>
            <div id="all_names">
              <?php
                if($result_flag){
                  echo $career_field;
                }
              ?>
           </div>
        </div>
 <?php include "footer.php"; ?><!--Site footer node -->
 <?php include "library.php"; ?><!--Script Library files -->
 </body>
 <script type="text/javascript" src="js/script.js"></script>
 </html>
