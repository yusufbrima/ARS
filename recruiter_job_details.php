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
if((isset($_GET['job_id']) && !empty($_GET['job_id'])) && (isset($_GET['recruiter_id']) && !empty($_GET['recruiter_id']))){
  $job_id =  mysql_fix_string(trim($_GET['job_id']));
  $recruiter_id =  mysql_fix_string(trim($_GET['recruiter_id']));
  $flag = false;
  $query ="SELECT j.id,j.title,c.name,j.occupational_field,j.attachment_URL,j.post_date,j.salary,j.skills,j.description,l.name,j.language_level,j.job_tag,j.link_title,j.url,e.name,ed.level,ci.name,p.name,r.profile_url,r.organization FROM job AS j LEFT JOIN job_category AS c ON j.career_field_id=c.id LEFT JOIN recruiter  AS r ON j.recruiter_id=r.id LEFT JOIN employment_type AS e ON j.employment_type=e.id LEFT JOIN education_level AS ed ON j.education_level=ed.id LEFT JOIN city AS ci ON j.city=ci.id LEFT JOIN province AS p ON j.province=p.id LEFT JOIN experience_level AS l ON j.work_experience=l.id WHERE j.id='$job_id' AND j.recruiter_id='$recruiter_id'";
  $recruiter_result = $con->query($query);
  $affected_recruiter_rows 	= mysqli_num_rows($recruiter_result);
  if($affected_recruiter_rows>0){
    for($i=0;$i<$affected_recruiter_rows;$i++){
      $row = mysqli_fetch_array($recruiter_result);
      //print_r($row);
      $flag = true;
      $recruiter['id']=$row['id'];
      $recruiter['job_title']=$row['title'];
      $recruiter['category']=$row[2];
      $recruiter['province']=$row['name'];
      $recruiter['occupational_field']=$row['occupational_field'];
      $recruiter['attachment_URL']=$row['attachment_URL'];
      $recruiter['salary']=$row['salary'];
      $recruiter['skills']=$row['skills'];
      $recruiter['description']=$row['description'];
      $recruiter['experience_level']=$row[9];
      $recruiter['language_level']=$row['language_level'];
      $recruiter['job_tag']=$row['job_tag'];
      $recruiter['link_title']=$row['link_title'];
      $recruiter['url']=$row['url'];
      $recruiter['profile_url']=$row['profile_url'];
      $recruiter['organization']=$row['organization'];
      $recruiter['employment_type']=$row[14];
      $recruiter['qualification']=$row['level'];
      $recruiter['city']=$row[16];
      $posted = strtotime($row['post_date']);
      $output = date('l jS \of F Y h:i:s A',$posted);
     }
     $path = "imageUpload/".$recruiter['profile_url'];
     $img = "<a class='pull-right' href='#'><img src='{$path}' alt='Profile Picture' class='img-circle' /></a>";
     $link ="_job_posts/".$recruiter['attachment_URL'];
     $job_document = "<a href='{$link}'><span class='glyphicon glyphicon-file'></span></a>";
     $job_display = "<div class='content'>{$img}";
     $job_display .="<p><span class='profile_title'>Organization</span> {$recruiter['organization']}</p>";
     $job_display .="<p><span class='profile_title'>Job title </span>{$recruiter['job_title']}</p>";
     $job_display .="<p><span class='profile_title'>Category of job </span>{$recruiter['category']}</p>";
     $job_display .="<p><span class='profile_title'>Occupational Field </span>{$recruiter['occupational_field']}</p>";
     $job_display .="<p><span class='profile_title'>Job Attachment </span>{$job_document}</p>";
     $job_display .="<p><span class='profile_title'>Salary Range(Le)</span> {$recruiter['salary']}</p>";
     $job_display .="<p><span class='profile_title'>Reqired Skills </span>{$recruiter['skills']}</p>";
     $job_display .="<p><span class='profile_title'>Description </span>{$recruiter['description']}</p>";
     $job_display .="<p><span class='profile_title'>Level of experience </span>{$recruiter['experience_level']}</p>";
     $job_display .="<p><span class='profile_title'>Employment Type </span>{$recruiter['employment_type']}</p>";
     $job_display .="<p><span class='profile_title'>Required qualification </span> {$recruiter['qualification']}</p>";
     $job_display .="<p><span class='profile_title'>Required language skills</span> {$recruiter['language_level']}</p>";
     $job_display .="<p><span class='profile_title'>Job Link </span> <a href='{$recruiter['url']}' target='_blank'>{$recruiter['link_title']} </a></p>";
     $job_display .="<p><span class='profile_title'>City</span> {$recruiter['city']}</p>";
     $job_display .="<p><span class='profile_title'>Province </span> {$recruiter['province']}</p>";
     $job_display .="<p><span class='profile_title'>Date posted</span> {$output}</p>";
     $job_display .="<p><span class='profile_title'>Job Tag</span> {$recruiter['job_tag']}</p>";
     $job_display .="</div>";

  }
}else{
  header('location: recruiter_jobs.php');
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
         <?php
              if($flag){
                echo "<div class='h1'>Job Details</div><hr />";
                echo $job_display;
              }
        ?>
     </div><!--End of Container Div-->
       <?php include_once "footer.php"; ?><!--Site footer-->

 </body>
 <?php include "library.php"; ?><!--Script Library files -->
 </html>
