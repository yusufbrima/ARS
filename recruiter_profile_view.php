<?php session_start();
/*authorization of recruiters only to post view page*/
if(!isset($_SESSION['recruiter_username']) && empty($_SESSION['recruiter_username'])){
  if(!isset($_SESSION['user_type_recruiter']) && empty($_SESSION['user_type_recruiter'])){
    header('location: login.php?warning=Authentication required, you must login first to access this page!');
  }
}else{
  $username=$_SESSION['recruiter_username'];
}
 ?>
<?php
include('include/dbcon.php');
include('include/function_library.php');
/*Retrieving seeker data for preview*/
if(!empty($username)){
    $query 		= "SELECT * FROM user WHERE username='$username'";
    $result = $con->query($query);
    $row		= mysqli_fetch_array($result);//Fetching of user data from the database into the row array
    $num_row 	= mysqli_num_rows($result);
    if ($num_row  <> 0){
        $user_id = $row['user_id'];
        //echo $user_id;
    }
}
$flag = false;
if(!empty($user_id)){
  $query 		= "SELECT r.id,r.salutation,r.first_name,r.last_name,r.organization,r.phone_one,r.profile_url,r.web_link,r.street,p.name,ci.name,c.nicename,u.join_date,u.email FROM recruiter AS r LEFT JOIN province AS p ON r.province=p.id LEFT JOIN city AS ci ON r.city=ci.id LEFT JOIN country AS c ON r.country=c.id LEFT JOIN user AS u ON r.user_id=u.user_id WHERE r.user_id='$user_id'";
  $result = $con->query($query);
  $recruiter['first_name']="";
  $recruiter['last_name']="";
  $recruiter['phone_number']="";
  $recruiter['email']="";
  $recruiter['profile_url']="";
  $recruiter['city']="";
  $recruiter['province']="";
  $recruiter['country']="";
  $recruiter['organization']="";
  $recruiter['sex']="";
  $recruiter['url']="";
  $recruiter['id']="";
  $recruiter['street']="";
  $recruiter['join_date']="";
  $recruiter_profile ="";
  $num_row 	= mysqli_num_rows($result);
  if($num_row>0){
    for($i=0;$i<$num_row;$i++){
      $row = mysqli_fetch_array($result);
      //print_r($row);
      $flag = true;
      $recruiter['first_name']=$row['first_name'];
      $recruiter['last_name']=$row['last_name'];
      $recruiter['phone_number']=$row['phone_one'];
      $recruiter['email']=$row['email'];
      $recruiter['profile_url']=$row['profile_url'];
      $recruiter['city']=$row['name'];
      $recruiter['province']=$row[9];
      $recruiter['country']=$row['nicename'];
      $recruiter['organization']=$row['organization'];
      $recruiter['sex']=$row['salutation'];
      $recruiter['url']=$row['web_link'];
      $recruiter['id']=$row['id'];
      $recruiter['street']=$row['street'];
      $recruiter['join_date']=$row['join_date'];
    }
    if($recruiter['sex']=="M"){
      $recruiter['sex']="Mr.";
    }elseif ($recruiter['sex']=="F") {
      $recruiter['sex']="Ms.";
    }else{
      $recruiter['sex']="N/A";
    }
    $path = "imageUpload/".$recruiter['profile_url'];
    $img = "<a class='pull-right' title='Update your profile picture' href='recruiter_picture_update.php?recruiter_id={$recruiter['id']}&user_id={$user_id}'><img src='{$path}' alt='Profile Picture' class='img-circle' /></a>";
    $recruiter_profile ="<div class='container'><table border='0'>";
    $recruiter_profile .="<tr><td class='profile_title'>Salutation</td><td>{$recruiter['sex']}</td><td><a title='Edit Profile' href='recruiter_profile_update.php?recruiter_id={$recruiter['id']}&user_id={$user_id}'><span class='glyphicon glyphicon-edit'></span> Edit</a></td></tr>";
    $recruiter_profile .="<tr><td class='profile_title'>First Name</td><td>{$recruiter['first_name']}</td></tr>";
    $recruiter_profile .="<tr><td class='profile_title'>Last Name</td><td> {$recruiter['last_name']}</td></tr>";
    $recruiter_profile .="<tr><td class='profile_title'>Organization</td><td> {$recruiter['organization']}</td></tr>";
    $recruiter_profile .="<tr><td class='profile_title'>Website URL</td><td> <a href='{$recruiter['url']}' target='_blank'>{$recruiter['url']}</a></td></tr>";
    $recruiter_profile .="<tr><td class='profile_title'>Mobile Contact</td><td><span class='glyphicon glyphicon-phone-alt'></span> {$recruiter['phone_number']}</td></tr>";
    $recruiter_profile .="<tr><td class='profile_title'>Email</td><td><span class='glyphicon glyphicon-envelope'></span> {$recruiter['email']}</td></tr>";
    $recruiter_profile .="<tr><td class='profile_title'>Address</td><td> {$recruiter['street']}</td></tr>";
    $recruiter_profile .="<tr><td class='profile_title'>City</td><td> {$recruiter['city']}</td></tr>";
    $recruiter_profile .="<tr><td class='profile_title'>Province</td><td> {$recruiter['province']}</td></tr>";
    $recruiter_profile .="<tr><td class='profile_title'>Country</td><td>{$recruiter['country']}</td></tr>";
    $recruiter_profile .="<tr><td class='profile_title'>Joined Date</td><td>{$recruiter['join_date']}</td></tr>";
    $recruiter_profile .="</table></div>";
  }
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
         <li class="dropdown active">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-user"></span> My Account
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
       <div class="container resume_img">
           <?php
               if(isset($_GET['msg'] )&& !empty($_GET['msg'] )){
                 $msg = trim($_GET['msg'] );
                 $error = "<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
                 $error .= "<strong>Fata Error<br /></strong>{$msg}</div>";
                 echo $error;
               }
           ?>
            <div class="h1">Profile Overview</div>
              <?php if($flag){
                echo $img;
                echo $recruiter_profile;
              } else{
                echo "<a href='recruiter_profile_edit.php'><span class='glyphicon glyphicon-plus'></span> Add your profile details</a>";
              }
            ?>
     </div><!--End of Container Div-->
     
     <br /><br />
     <br /><br />
     <br /><br />
     <br /><br />
       <?php include_once "footer.php"; ?><!--Site footer-->
       <?php include "library.php"; ?><!--Script Library files -->
 </body>
 </html>
