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
$user_id = "";
$seeker_id="";
  /*Retrieving seeker data for preview*/
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
              $seeker_id = $dataset['id'];
          }
      }
  }
  $flag = false;
  if(!empty($seeker_id)){
    $s_id = $seeker_id;
    $query ="SELECT a.id,r.organization,s.id,j.id,a.recruiter_id,j.title,a.applied_date FROM application AS a LEFT JOIN recruiter AS r ON a.recruiter_id=r.id LEFT JOIN seeker AS s ON a.seeker_id=s.id LEFT JOIN job AS j ON a.job_id=j.id WHERE a.seeker_id='$s_id' AND trashed=0";
    $result = $con->query($query);
    $seeker_application = "";
    $seeker_application ="<div><table class='table table-striped' border='0'>";
    $seeker_application .="<caption class='h3'>My Applications</caption>";
    $seeker_application .="<tr><th>Job Title</th><th>Oraganization Name</th><th>Sent Date</th></tr>";
    $num_row 	= mysqli_num_rows($result);
    if($num_row>0){
      for($i=0;$i<$num_row;$i++){
        $row = mysqli_fetch_array($result);
        $seeker['title'] = $row['title'];
        $seeker['job_id'] = $row[3];
        $seeker['organization']= $row['organization'];
        $applied = strtotime($row['applied_date']);
        $output = date('l jS \of F Y h:i:s A',$applied);
      //  print_r($row);
      $recruiter_id = $row['recruiter_id'];
        $flag = true;
        $seeker_application .="<tr><td>{$seeker['title']}</td><td>{$seeker['organization']}</td><td>{$output}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href='job_preview.php?job_id={$seeker['job_id']}&recruiter_id={$recruiter_id}'><span class='glyphicon glyphicon-eye-open'></span></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href='#' data-href='seeker_applications.php?job_id={$seeker['job_id']}&seeker_id={$seeker_id}' data-toggle='modal' data-target='#seeker_application_delete'><span class='glyphicon glyphicon-trash'></span></a></td></tr>";
     }
     $seeker_application .="</table></div>";
    }
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
                   <input type="text" name="inputJobTitle" id="inputJobTitle" value="" placeholder="Search by Job Title" class="form-control"/>
                <input type="hidden" name="inputSeekerID" id="inputSeekerID" value="<?php echo $seeker_id; ?>" />
              </div>
              </div>
              <div class="col-md-6">
               <div class="form-group">
                   <input type="text" name="inputOrganization" id="inputOrganization" value="" placeholder="Search by Organization Name" class="form-control"/>
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
           echo $seeker_application;
         }else{
           echo "<div class='alert alert-danger notification'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><strong>Notification<br /></strong>You have no applications sent yet...</div>";
         }
      ?>
      </div>
       <?php include_once('seeker_application_delete.php') ?>
     </div><!--End of Container Div-->
 <?php include_once "footer.php"; ?><!--Site footer-->
 <?php include "library.php"; ?><!--Script Library files -->
 </body>
 <script type="text/javascript">
     $('document').ready(function(){
         //$('#result').load('include/job_title_search.php').show();
         $('#inputJobTitle').keyup(function(){
               //$('#feedback').append('a');
               $.get('include/seeker_application_title_search.php', {inputJobTitle: $('#inputJobTitle').val(),inputSeekerID: $('#inputSeekerID').val()},
                   function(result){
                       $('#result').html(result).show();
                       $('#all_jobs').css('visibility','hidden');
                   });
         });
     }); //End of ready function
     $('document').ready(function(){
         //$('#result').load('include/job_title_search.php').show();
         $('#inputOrganization').keyup(function(){
               //$('#feedback').append('a');
               $.get('include/seeker_application_search_organization.php', {inputOrganization: $('#inputOrganization').val(),inputSeekerID: $('#inputSeekerID').val()},
                   function(result){
                       $('#result').html(result).show();
                       $('#all_jobs').css('visibility','hidden');
                   });
         });
     }); //End of ready function
     $('#seeker_application_delete').on('show.bs.modal', function(e) {
     $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
     });
 </script>
 </html>
