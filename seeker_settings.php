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
  $job_alert_flag = check_seeker_job_alert_status($con,$user_id);
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
            <div class="h2">Account Magagement</div>
            <ul class="nav nav-pills login">
                <li role="presentation" ><a href='#' data-href='recruiter_jobs.php' data-toggle='modal' data-target='#seeker_username_management'><span class="glyphicon glyphicon-user"></span>Change Username</a></li>
                <li role="presentation" ><a href='#' data-href='recruiter_jobs.php' data-toggle='modal' data-target='#seeker_newsletter'><span class="glyphicon glyphicon-user"></span>Job Post alert</a></li>
             </ul>
          </div>
       <!--End of the Signup Form -->
       <div class="col-md-6">
         <?php include_once('topAgents.php');?>
       </div>
     </div><!--End of Container Div-->
  <?php include_once"setting_dialog.php";?>
 <?php include_once "footer.php"; ?><!--Site footer-->
 <?php include "library.php"; ?><!--Script Library files -->
 </body>
 <script>
$('document').ready(function(){
  $('#Update_profile').css('visibility','hidden');
  $('#inputUsername').css('visibility','hidden');
});

/*Checking user login for Username update*/
  $('#inputPassword').blur(function(){
    $('#feedback').load('include/checklogin.php').show();
    $.get('include/checklogin.php', {old_username: $('#inputOldUsername').val(),pass: $('#inputPassword').val()},
        function(result){
            $('#feedback').html(result).show();
            var feedback = document.getElementById('feedback');
            if(feedback.innerHTML=="Account confirmed. Update your Username"){
              $('#Update_profile').css('visibility','visible');
              $('#inputUsername').css('visibility','visible');
            }
        });
  });

/*Script to check user login credentials
  $('#feedback').load('include/checkUser.php').show();
  $('#inputUsername').keyup(function(){
    if($('#inputUsername').val().length>=8){
        //$('#feedback').append('a');
        $.post('include/checkUser.php', {username: $('#inputUsername').val()},
            function(result){
                $('#feedback').html(result).show();
            });
    }
  });
  */
  /*Script to update username in the database*/
  $('#Update_profile').click(function(){
    $('#feedback').load('include/updateUsername.php').show();
    $.get('include/updateUsername.php', {old_username: $('#inputOldUsername').val(),pass: $('#inputPassword').val(),username: $('#inputUsername').val(),},
        function(result){
            $('#feedback').html(result).show();
        });
  });


  /*Script to check user login credentials*/
    //$('#newsletter_feedback').load('include/seeker_newsletter.php').show();
    $('#newsletter').click(function(){
          //$('#feedback').append('a');
          var sentinel = document.getElementById('job_alert');
          if(sentinel.checked){
            sentinel.value=1;
          }else{
            sentinel.value=0;
          }
          $.get('include/seeker_newsletter.php', {flag: $('#job_alert').val(),seeker_id: $('#seeker_id').val()},
              function(result){
                  $('#newsletter_feedback').html(result).show();
              });
    });
 </script>
 </html>
