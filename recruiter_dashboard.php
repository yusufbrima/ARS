<?php session_start();
/*authorization of recruiters only to post view page*/
if(!isset($_SESSION['recruiter_username']) && empty($_SESSION['recruiter_username'])){
  if(!isset($_SESSION['user_type_recruiter']) && empty($_SESSION['user_type_recruiter'])){
    $_SESSION['warning'] = "Access denied, you must login first!";
    header('location: login.php?warning=Authentication required, you must login first to access this page!');
  }
}else{
  $username=$_SESSION['recruiter_username'];
}
 ?>
 <?php
 include('include/dbcon.php');
 include('include/function_library.php');
 $resume_flag = false;
 $query ="SELECT s.id,s.first_name,s.last_name,s.profile_url,u.join_date,s.sex,s.street,u.user_id FROM seeker AS s LEFT JOIN user AS u ON s.user_id=u.user_id ORDER BY u.join_date";
 $resume_result = $con->query($query);
 $content ='<div class="row advert">';
 $content .="<div class='h2'>Recently Posted Resumes</div>";
 $affected_resume_rows 	= mysqli_num_rows($resume_result);
 if($affected_resume_rows>0){
   for($i=0;$i<$affected_resume_rows;$i++){
     $row = mysqli_fetch_array($resume_result);
     //print_r($row);
     $resume_flag = true;
     $seeker['id']=$row['id'];
     $seeker['first_name'] =$row['first_name'];
     $seeker['last_name'] = $row['last_name'];
     $seeker['profile_url']=$row['profile_url'];
     $seeker['sex']=$row['sex'];
     if($seeker['sex']=="M"){
       $seeker['sex']="Mr.";
     }elseif ($seeker['sex']=="F") {
       $seeker['sex']="Ms.";
     }else{
       $seeker['sex']="N/A";
     }
     $seeker['street'] =$row['street'];
     $seeker['user_id'] = $row['user_id'];
     $posted = strtotime($row['join_date']);
     $output = date('l jS \of F Y h:i:s A',$posted);
     $path = "imageUpload/".$seeker['profile_url'];
     $img = "<a class='pull-right' href='resume_preview.php?seeker_id={$seeker['id']}&user_id={$seeker['user_id']}'><img src='{$path}' alt='Profile Picture' class='img-circle search-thumbnail' /></a>";
     $content .= "<div class='h4'>{$seeker['sex']}&nbsp;&nbsp; {$seeker['first_name']}&nbsp;&nbsp;{$seeker['last_name']}</div>";
     $content .= $img;
     $content .="<div class='content'><p>{$seeker['street']}</p><p>Being a  member since {$output}</p></div>";
     $content .="<a href='resume_preview.php?seeker_id={$seeker['id']}&user_id={$seeker['user_id']}'><input type='button' class='btn btn-default btn-primary' value='View Profile' name='read_more'></a>";
   }
   $content .="</div>";
 }
  ?>
  <?php
  $application_count=0;
  $job_count=0;
  $recruiter_id;
  if(!empty($username)){
      $query 		= "SELECT * FROM user WHERE username='$username'";
      $result = $con->query($query);
      $row		= mysqli_fetch_array($result);//Fetching of user data from the database into the row array
      $num_row 	= mysqli_num_rows($result);
      if ($num_row  <> 0){
          $user_id = $row['user_id'];
      }
  }
  /*Retrieving recruiter id from the database for data display*/
  $recruiter_query = "SELECT id FROM recruiter WHERE user_id='$user_id'";
  $recruiter_result = $con->query($recruiter_query);
  $affected_recruiter_rows 	= mysqli_num_rows($recruiter_result);
  if($affected_recruiter_rows>0){
    for($i=0;$i<$affected_recruiter_rows;$i++){
      $row = mysqli_fetch_array($recruiter_result);
      //print_r($row);
      $recruiter_id = $row['id'];
    }
    $application_query = "SELECT COUNT(*) AS count FROM application WHERE recruiter_id={$recruiter_id}";
    $application_result = $con->query($application_query);
    $affected_count_rows 	= mysqli_num_rows($application_result);
    if($affected_count_rows>0){
      for($i=0;$i<$affected_count_rows;$i++){
        $dataset = mysqli_fetch_array($application_result);
        $application_count = $dataset['count'];
      }
    }
    $job_query = "SELECT COUNT(*) AS count FROM job WHERE recruiter_id={$recruiter_id}";
    $job_result = $con->query($job_query);
    $affected_count_rows 	= mysqli_num_rows($job_result);
    if($affected_count_rows>0){
      for($i=0;$i<$affected_count_rows;$i++){
        $dataset = mysqli_fetch_array($job_result);
        $job_count = $dataset['count'];
      }
    }
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
           <li><a href="recruiter_dashboard.php">Welcome <?php echo $username; ?></a></li>
           <li role="presentation"><a href="resumeSearch.php"><span class="glyphicon glyphicon-search"></span> Resume Search</a></li>
           <li class="dropdown">
            <a class="dropdown-toggle active" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-user"></span> My Account
            <span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li><a href="recruiter_profile_view.php"><span class="glyphicon glyphicon-user"></span> My Profile</a></li>
              <li><a href="recruiter_jobs.php"><span class="glyphicon glyphicon-briefcase"></span> My Jobs <span class="badge"><?php echo $job_count; ?></span></a></li>
              <li><a href="recruiter_applications.php"><span class="glyphicon glyphicon-envelope"></span> Recieved Applications <span class="badge"><?php echo $application_count; ?></span></a></li>
              <li><a href="recruiter_settings.php"><span class="glyphicon glyphicon-wrench"></span> Settings</a></li>
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
       <!--Div for the Password Reset Code Canvas-->
       <div class="container">
         <div class="col-md-6">
         <?php
         if($resume_flag){
             echo $content;
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
