<?php session_start();
/*authorization of recruiters only to post view page*/
if(!isset($_SESSION['seeker_username']) && empty($_SESSION['seeker_username'])){
  if(!isset($_SESSION['user_type_seeker']) && empty($_SESSION['user_type_seeker'])){
    header('location: login.php?warning=Authentication required, you must login first to access this page!');
  }
}else{
  $username=$_SESSION['seeker_username'];
}
?>
<?php
include('include/dbcon.php');
include('include/function_library.php');

$s_id="";
$job_count=0;
if(!empty($username)){
    $query 		= "SELECT * FROM user WHERE username='$username'";
    $result = $con->query($query);
    $row		= mysqli_fetch_array($result);//Fetching of user data from the database into the row array
    $num_row 	= mysqli_num_rows($result);
    if ($num_row  <> 0){
        $user_id = $row['user_id'];
        $query_seeker = "SELECT id FROM seeker  WHERE user_id='$user_id'";
        $result_seeker = $con->query($query_seeker);
        $dataset = mysqli_fetch_array($result_seeker);//Fetching of user data from the database into the row array
        $num_row_affected 	= mysqli_num_rows($result_seeker);
        if ($num_row_affected  == 1){
            $s_id = $dataset['id'];
        }
      $job_query = "SELECT COUNT(*) AS count FROM application WHERE seeker_id={$s_id} AND trashed=0";
      $job_result = $con->query($job_query);
      $affected_count_rows 	= mysqli_num_rows($job_result);
      if($affected_count_rows>0){
        for($i=0;$i<$affected_count_rows;$i++){
          $dataset = mysqli_fetch_array($job_result);
          $job_count = $dataset['count'];
        }
      }
    }
}
?>
<?php

$flag = false;
$seeker_job_preference="";
if(!empty($s_id)){
  //echo "Seeker Id" .$s_id;
  $query 	= " SELECT p.id,p.salary_scale,ex.name AS experience,emp.name AS employment_type,ci.name AS city,pro.name AS province,j.name AS job_category FROM seeker_job_prefrence AS p LEFT JOIN experience_level AS ex ON p.work_experience=ex.id LEFT JOIN employment_type AS emp ON p.employment_type=emp.id LEFT JOIN job_category AS j ON p.job_category=j.id LEFT JOIN city as ci ON p.city=ci.id LEFT JOIN province as pro ON p.province=pro.id WHERE p.seeker_id='$s_id'";
  $result = $con->query($query);
  $num_row 	= mysqli_num_rows($result);
  if($num_row>0){
    for($i=0;$i<$num_row;$i++){
      $row = mysqli_fetch_array($result);
      //print_r($row);
      $flag = true;
      $seeker_prefrence['salary_scale']=$row['salary_scale'];
      $seeker_prefrence['experience']=$row['experience'];
      $seeker_prefrence['employment_type']=$row['employment_type'];
      $seeker_prefrence['job_category']=$row['job_category'];
      $seeker_prefrence['city']=$row['city'];
      $seeker_prefrence['province']=$row['province'];
    }
    $seeker_job_preference ="<div class='container'><table border='0'>";
    $seeker_job_preference .="<tr><td class='profile_title'>Salary Scale</td><td>{$seeker_prefrence['salary_scale']}</td></tr>";
    $seeker_job_preference .="<tr><td class='profile_title'>Experience Level</td><td>{$seeker_prefrence['experience']}</td></tr>";
    $seeker_job_preference .="<tr><td class='profile_title'>Type of Employment</td><td> {$seeker_prefrence['employment_type']}</td></tr>";
    $seeker_job_preference .="<tr><td class='profile_title'>Job Category</td><td> {$seeker_prefrence['job_category']}</td></tr>";
    $seeker_job_preference .="<tr><td class='profile_title'>City</td><td> {$seeker_prefrence['city']}</td></tr>";
    $seeker_job_preference .="<tr><td class='profile_title'>Province</td><td> {$seeker_prefrence['province']}</td></tr>";
    $seeker_job_preference .="</table></div>";
  }
    }
    $data_table ="seeker";
    if(!checker_seeker($con,$user_id,$data_table)){
      header('location:seeker_profile_view.php?msg=Please add your profile details first!');
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
           <a class="navbar-brand" href="index.php"><img src="images/e-jobFinder.png" alt="Logo"/></a>
         </div>
          <div class="collapse navbar-collapse" id="myNavbar">
         <ul class="nav navbar-nav navbar-right">
           <li><a href="seeker_dashboard.php">Welcome <?php echo $username; ?></a></li>
           <li role="presentation"><a href="jobSearch.php"><span class="glyphicon glyphicon-search"></span> Job Search</a></li>
           <li class="dropdown active">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-user"></span> My Account
            <span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li><a href="seeker_profile_view.php"><span class="glyphicon glyphicon-user"></span> My Profile</a></li>
              <li><a href="seeker_applications.php"><span class="glyphicon glyphicon-briefcase"></span> My Applications <span class="badge"><?php echo $job_count; ?></span></a></li>
              <li><a href="seeker_resume_view.php"><span class="glyphicon glyphicon-envelope"></span> My Resume</a></li>
              <li><a href="seeker_settings.php"><span class="glyphicon glyphicon-wrench"></span> Settings</a></li>
              <li><a href="seeker_job_preference_view.php"><span class="glyphicon glyphicon-wrench"></span>Job Prefrences</a></li>
            </ul>
          </li>
           <li><a href="logout.php"><span class="glyphicon glyphicon-off"></span> Logout</a></li>
         </ul>
       </div>
        </div>
     </nav>
     <div class="menu-spacer"></div>
     <!--Clearing of the float rule-->
       <div class="clear-fix">
       </div>
       <!--Div for the Password Reset Code Canvas-->
       <div class="container">
         <div class="col-md-6">
           <div class="h2">Job Prefrence Magagement</div>
           <ul class="nav nav-pills login">
               <li role="presentation" class="active" ><a href='seeker_job_preference_view.php'> <span class="glyphicon glyphicon-eye-open"></span>Job Prefrences</a></li>
               <li role="presentation" ><a href='seeker_job_preferences_update.php'><span class="glyphicon glyphicon-pencil"></span>Edit</a></li>
            </ul>
            <?php if($flag){
              echo $seeker_job_preference;
            } else{
              echo "<a href='seeker_job_preference.php'><span class='glyphicon glyphicon-plus'></span> Add your job prefrence</a>";
            }
          ?>
       </div>
       <div class="col-md-6">
         <?php include_once "topAgents.php"; ?>
      </div>
     </div><!--End of Container Div-->
 <?php include_once "footer.php"; ?><!--Site footer-->
 <?php include "library.php"; ?><!--Script Library files -->
 </body>
 </html>
