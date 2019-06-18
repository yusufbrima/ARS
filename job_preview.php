<?php
 session_start();
/*authorization of recruiters only to post view page*/
if(!isset($_SESSION['seeker_username']) && empty($_SESSION['seeker_username'])){
  if(!isset($_SESSION['user_type_seeker']) && empty($_SESSION['user_type_seeker'])){
    header('location: login.php?warning=Authentication required, you must login first to access this page!');
  }
}else{
  $username=$_SESSION['seeker_username'];
}
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
     $img = "<img src='{$path}' alt='Profile Picture' class='img-circle pull-right' />";
     $link ="_job_posts/".$recruiter['attachment_URL'];
     $job_document = "<a href='download_job.php?filename={$link}'><i class='fa fa-download fa-2x' aria-hidden='true'></i></a>";
     $job_display = "<div class='content'>{$img}";
     $job_display .="<p><span class='profile_title'>Organization</span> {$recruiter['organization']}</p>";
     $job_display .="<p><span class='profile_title'>Job title </span>{$recruiter['job_title']}</p>";
     $job_display .="<p><span class='profile_title'>Category of job </span>{$recruiter['category']}</p>";
     $job_display .="<p><span class='profile_title'>Occupational Field </span>{$recruiter['occupational_field']}</p>";
     $job_display .="<p class='noprint'><span class='profile_title'>Job Attachment </span>{$job_document}</p>";
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
     $job_display .="<p><span class='profile_title'>Posted on </span> {$output}</p>";
     $job_display .="<p><span class='profile_title'>Job Tag</span> {$recruiter['job_tag']}</p>";
     $job_display .="</div>";

  }
}else{
  header('location: recruiter_jobs.php');
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
    <style>
      @media print{
        .noprint{
          display: none;
        }
        .print{
          visibility: visible;;
          display: block;
        }
      }
    </style>
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
       <div class="clear-fix"></div>
       <!--Div for the Password Reset Code Canvas-->
       <div class="container print">
         <?php
              if($flag){
                echo "<div class='h1 job_preview_title'>Job Details</div><hr />";
                echo $job_display;
              }
        ?>
      <div class="job-apply noprint">
      <form method="post" class="form-inline" action="<?php outputData($_SERVER['PHP_SELF']); ?>">
        <input type='hidden' value="<?php echo $recruiter_id; ?>" id="recruiter_id" />
        <input type='hidden' value="<?php echo $job_id; ?>" id="job_id" />
        <input type="button" name="filter_jobs" value="Apply now!" id="apply_now" class="btn btn-default btn-primary" />
        <button type="button" name="print" value="Print" id="print" class="pull-right btn-primary btn" /><i class="fa fa-print fa-2x" aria-hidden="true"></i></button>
      </form>
    </div>
    <div class="nonprint" id='result' style="color:green;"></div>
     </div><!--End of Container Div-->
     <div class="noprint">
       <?php include_once "footer.php"; ?><!--Site footer-->
     </div>

 </body>
 <?php include "library.php"; ?><!--Script Library files -->
 <script type="text/javascript">
  $('#print').click(function(){
    window.print();
  });
  $('document').ready(function(){
      //$('#result').load('include/job_title_search.php').show();
      $('#apply_now').click(function(){
            $.get('include/job_apply.php', {recruiter_id: $('#recruiter_id').val(),job_id: $('#job_id').val()},
                function(result){
                    $('#result').html(result).show();
                });
      });
  }); //End of ready function
 </script>
 </html>
