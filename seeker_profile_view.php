<?php session_start();
/*authorization of recruiters only to post view page*/
if(!isset($_SESSION['seeker_username']) && empty($_SESSION['seeker_username'])){
  if(!isset($_SESSION['user_type_seeker']) && empty($_SESSION['user_type_seeker'])){
    header('location: login.php?warning=Authentication required, you must login first to access this page!');
  }
}else{
  $username=$_SESSION['seeker_username'];
  //echo $username;
}
$user_id = "";
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
  $query 		= "SELECT s.id,s.first_name,s.last_name,s.marital_status,s.dob,s.phone_one,s.profile_url,s.street,s.sex,c.nicename,p.name,ci.name,u.join_date,u.email FROM seeker AS s LEFT JOIN country AS c ON s.country=c.id LEFT JOIN province AS p ON s.province=p.id LEFT JOIN city AS ci ON s.city=ci.id LEFT JOIN user AS u on s.user_id=u.user_id  WHERE s.user_id='$user_id'";
  $result = $con->query($query);
  $seeker['first_name']="";
  $seeker['last_name']="";
  $seeker['phone_number']="";
  $seeker['email']="";
  $seeker['profile_url']="";
  $seeker['marital_status']="";
  $seeker['city']="";
  $seeker['province']="";
  $seeker['country']="";
  $seeker['dob']="";
  $seeker['sex']="";
  $seeker['id']="";
  $seeker['street']="";
  $seeker['join_date']="";
  $seeker_profile ="";
  $num_row 	= mysqli_num_rows($result);
  if($num_row>0){
    for($i=0;$i<$num_row;$i++){
      $row = mysqli_fetch_array($result);
      //print_r($row);
      $flag = true;
      $seeker['id']=$row['id'];
      $seeker['first_name']=$row['first_name'];
      $seeker['last_name']=$row['last_name'];
      $seeker['phone_number']=$row['phone_one'];
      $seeker['profile_url']=$row['profile_url'];
      $seeker['marital_status']=$row['marital_status'];
      $seeker['city']=$row['name'];
      $seeker['email']=$row['email'];
      $seeker['province']=$row[10];
      $seeker['country']=$row['nicename'];
      $seeker['dob']=$row['dob'];
      $seeker['sex']=$row['sex'];
      $seeker['street']=$row['street'];
      $posted = strtotime($row['join_date']);
      $output = date('l jS \of F Y h:i:s A',$posted);
    }
  }
  if($seeker['sex']=="M"){
    $seeker['sex']="Mr.";
  }elseif ($seeker['sex']=="F") {
    $seeker['sex']="Ms.";
  }else{
    $seeker['sex']="N/A";
  }
      $path = "imageUpload/".$seeker['profile_url'];
      $img = "<a title='Update your profile picture' href='seeker_picture_update.php?seeker_id={$seeker['id']}&user_id={$user_id}'><img src='{$path}' alt='Profile Picture' class='img-circle pull-right' /></a>";
      $seeker_profile ="<div class='container'><table border='0'>";
      $seeker_profile .="<tr><td class='profile_title'>Salutation</td><td>{$seeker['sex']}</td><td><a title='Edit Profile' href='seeker_profile_update.php?seeker_id={$seeker['id']}&user_id={$user_id}'><span class='glyphicon glyphicon-edit'></span> Edit</a></td></tr>";
      $seeker_profile .="<tr><td class='profile_title'>First Name</td><td>{$seeker['first_name']}</td></tr>";
      $seeker_profile .="<tr><td class='profile_title'>Last Name</td><td> {$seeker['last_name']}</td></tr>";
      $seeker_profile .="<tr><td class='profile_title'>Birthday</td><td> {$seeker['dob']}</td></tr>";
      $seeker_profile .="<tr><td class='profile_title'>Mobile Contact</td><td><span class='glyphicon glyphicon-phone-alt'></span> {$seeker['phone_number']}</td></tr>";
      $seeker_profile .="<tr><td class='profile_title'>Marital Status</td><td> {$seeker['marital_status']}</td></tr>";
      $seeker_profile .="<tr><td class='profile_title'>Email</td><td><span class='glyphicon glyphicon-envelope'></span> {$seeker['email']}</td></tr>";
      $seeker_profile .="<tr><td class='profile_title'>Address</td><td> {$seeker['street']}</td></tr>";
      $seeker_profile .="<tr><td class='profile_title'>City</td><td> {$seeker['city']}</td></tr>";
      $seeker_profile .="<tr><td class='profile_title'>Province</td><td> {$seeker['province']}</td></tr>";
      $seeker_profile .="<tr><td class='profile_title'>Country</td><td>{$seeker['country']}</td></tr>";
      $seeker_profile .="<tr><td class='profile_title'>Been a member since </td><td>{$output}</td></tr>";
      $seeker_profile .="</table></div>";
    }

?>
<?php
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
     <!--Clearing of the float rule-->
     <div class="menu-spacer"></div>
     <div class="clear-fix clear-top"></div>
       <!--Div for the Password Reset Code Canvas-->
       <div class="container">
            <?php
                if(isset($_GET['msg'] )&& !empty($_GET['msg'] )){
                  $msg = trim($_GET['msg'] );
                  $error = "<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
                  $error .= "<strong>Fata Error<br /></strong>{$msg}</div>";
                  echo $error;
                }
            ?>
            <em class="h1">Profile Overview</em><hr /><br />
              <?php if($flag){
                echo $img;
                echo $seeker_profile;
              } else{
                echo "<a href='seeker_profile_edit.php'><span class='glyphicon glyphicon-plus'></span> Add your proile details</a>";
              }

              ?>
     </div><!--End of Container Div-->
       <?php include_once "footer.php"; ?><!--Site footer-->
 <?php include "library.php"; ?><!--Script Library files -->
 </body>
 </html>
