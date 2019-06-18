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
$user_id = "";
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
     $recruiter['id'] = $row['id'];
     $_SESSION['recruiter_id']=$recruiter['id'];
   }
 }
  $flag = false;
  if(!empty($recruiter['id'])){
    $r_id = $recruiter['id'];
    $query ="SELECT a.id AS app_id,s.first_name,s.user_id,s.last_name,s.sex,s.id AS seeker_id,j.id AS job_id,r.id AS recruiter_id,j.title,a.applied_date FROM application AS a LEFT JOIN recruiter AS r ON a.recruiter_id=r.id LEFT JOIN seeker AS s ON a.seeker_id=s.id LEFT JOIN job AS j ON a.job_id=j.id WHERE a.recruiter_id='$r_id'";
    $result = $con->query($query);
    $recruiter_application = "";
    $recruiter_application ="<div><table class='table table-striped' border='0'>";
    $recruiter_application .="<caption class='h3'>My Applications</caption>";
    $recruiter_application .="<tr><th>Job Title</th><th>Received From</th><th>Received Date</th></tr>";
    $num_row 	= mysqli_num_rows($result);
    if($num_row>0){
      for($i=0;$i<$num_row;$i++){
        $row = mysqli_fetch_array($result);
        $recruiter['app_id']= $row['app_id'];
        $recruiter['title'] = $row['title'];
        $recruiter['job_id'] = $row['job_id'];
        $recruiter['seeker_first_name']= $row['first_name'];
        $recruiter['seeker_last_name']= $row['last_name'];
        $applied = strtotime($row['applied_date']);
        $recruiter['seeker_id'] = $row['seeker_id'];
        $recruiter['seeker_user_id'] = $row['user_id'];
        $recruiter['sex'] = $row['sex'];
        $output = date('l jS \of F Y h:i:s A',$applied);
        //print_r($row);
        if($recruiter['sex']=="M"){
          $recruiter['sex']="Mr.";
        }elseif ($recruiter['sex']=="F") {
          $recruiter['sex']="Ms.";
        }else{
          $recruiter['sex']="N/A";
        }
        $full_name = $recruiter['sex']." ".$recruiter['seeker_first_name']." ".$recruiter['seeker_last_name'];
        $flag = true;
        $recruiter_application .="<tr><td>{$recruiter['title']}</td><td>{$full_name}</td><td>{$output}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='resume_preview.php?seeker_id={$recruiter['seeker_id']}&user_id={$recruiter['seeker_user_id']}'><span class='glyphicon glyphicon-eye-open'></span></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href='#' data-href='recruiter_applications.php?app_id={$recruiter['app_id']}&recruiter_id={$recruiter['id']}' data-toggle='modal' data-target='#recruiter_application_delete'><span class='glyphicon glyphicon-trash'></span></a></td></tr>";
     }
     $recruiter_application .="</table></div>";
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
         <li class="dropdown">
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
       <div class="clearfix">
       </div>
       <!--Div for the Password Reset Code Canvas-->
       <div class="container">
         <form>
           <fieldset>
               <legend>Search Applications</legend>
               <div class="row">
               <div class="col-md-6">
               <div class="form-group">
                 <div class="input-group">
                   <input type="text" name="inputJobTitle" id="inputJobTitle" value="" placeholder="Enter job title" class="form-control"/>
                   <span class = "input-group-addon"><span class="glyphicon glyphicon-search"></span></span>
               </div>
                   <input type="hidden" name="inputRecruiterID" id="inputRecruiterID" value="<?php echo $recruiter['id']; ?>" />
               </div>
            </div>
          </div>
           </fieldset>
         </form>
         <div id="result">
         </div>
         <div id="all_jobs">
         <?php
         if($flag){
           //echo "<div class='h3'>Educational Background</div>";
           echo $recruiter_application;
         }else{
           echo "<div class='alert alert-danger notification'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><strong>Notification<br /></strong>You have no applications received...</div>";
         }
      ?>
      </div>
       <?php include_once('recruiter_application_delete.php') ?>
     </div><!--End of Container Div-->
 <?php include_once "footer.php"; ?><!--Site footer-->
 <?php include "library.php"; ?><!--Script Library files -->
 </body>
 <script type="text/javascript">
     $('document').ready(function(){
         //$('#result').load('include/job_title_search.php').show();
         $('#inputJobTitle').keyup(function(){
               //$('#feedback').append('a');
               $.get('include/recruiter_application_title_search.php', {inputJobTitle: $('#inputJobTitle').val(),inputRecruiterID: $('#inputRecruiterID').val()},
                   function(result){
                       $('#result').html(result).show();
                       $('#all_jobs').css('visibility','hidden');
                   });
         });
     }); //End of ready function
     $('#recruiter_application_delete').on('show.bs.modal', function(e) {
     $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
     });
 </script>
 </html>
