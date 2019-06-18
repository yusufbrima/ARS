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
$flag = false;
$query ="SELECT j.id AS job_id,j.title,j.description,j.post_date, r.profile_url,r.organization,r.id AS recruiter_id,j.url FROM job as j LEFT JOIN recruiter AS r ON j.recruiter_id=r.id ORDER BY j.post_date";
$job_result = $con->query($query);
$content ='<div class="row advert">';
$content .="<div class='h2'>Recently posted jobs</div>";
$affected_job_rows 	= mysqli_num_rows($job_result);
if($affected_job_rows>0){
  for($i=0;$i<$affected_job_rows;$i++){
    $row = mysqli_fetch_array($job_result);
    //print_r($row);
    $flag = true;
    $job['id']=$row['job_id'];
    $recruiter['id']=$row['recruiter_id'];
    $job['title'] =$row['title'];
    $recruiter['profile_url']=$row['profile_url'];
    $recruiter['organization']=$row['organization'];
    $job_link =$row['url'];
    $posted = strtotime($row['post_date']);
    $output = date('l jS \of F Y h:i:s A',$posted);
    $job['description']=$row['description'];
    $path = "imageUpload/".$recruiter['profile_url'];
    $img = "<a class='pull-right' href='job_preview.php?job_id={$job['id']}&recruiter_id={$recruiter['id']}'><img src='{$path}' alt='Profile Picture' class='img-circle search-thumbnail' /></a>";
    $content .= "<div class='h4'>{$row['title']}</div>";
    $content .= $img;
    $content .= "<div class='h6'><a href='{$job_link}' target='_blank'>{$row['organization']}</a></div>";
    $content .="<div class='content'><p>{$row['description']}</p><p>Posted on {$output}</p></div>";
    $content .="<a href='job_preview.php?job_id={$job['id']}&recruiter_id={$recruiter['id']}'><input type='button' class='btn btn-default btn-primary' value='Read More...' name='read_more'></a>";
  }
  $content .="</div>";
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
      <div class="container">
        <section class="col-md-6 left">
            <form  class="form-inline">
              <fieldset>
                  <legend>Search for Job</legend>
                  <div class="form-inline">
                      <?php
                      $queryStringCareerField = "SELECT * FROM job_category ORDER BY name";
                      $display = '<select class="form-control" name="inputCareerField" id="inputCareerField">\n';
                      $display .='<option value="">--Job Category--</option>';
                      /*Fetching city data from the database*/
                      $resultCareerField = $con->query($queryStringCareerField);
                      $rowNum = mysqli_num_rows($resultCareerField);
                      if($resultCareerField){
                          for($i=0;$i<$rowNum;$i++) {
                              $row = mysqli_fetch_array($resultCareerField);
                              $name = $row['name'];
                              $id = $row['id'];
                              $display .= "<option value='{$id}'>{$name}</option>\n";
                          }
                      }
                      $display .='</select>';
                      echo $display;
                      ?>
                      <?php
                      $queryStringEducation = "SELECT * FROM education_level ORDER BY level";
                      $display = '<select class="form-control" name="inputQualification" id="inputQualification"><br />';
                      $display .='<option value="">--Qualification Attained--</option>\n';
                      /*Fetching city data from the database*/
                      $resultEducationLevel = $con->query($queryStringEducation);
                      $rowNum = mysqli_num_rows($resultEducationLevel);
                      if($resultEducationLevel){
                          for($i=0;$i<$rowNum;$i++) {
                              $row = mysqli_fetch_array($resultEducationLevel);
                              $name = $row['level'];
                              $id = $row['id'];
                              $display .= "<option value='{$id}'>{$name}</option>\n";
                          }
                      }
                      $display .='</select>';
                      echo $display;
                      ?>
                    <input type="text" name="inputNaturalLanguageSearch" id="inputNaturalLanguageSearch" value="" placeholder="Enter any keywords" class="form-control"/>
                    <input type="button" name="filter_jobs"  id="filter_jobs" value="Search" class="btn btn-default btn-primary job-search" />
                  </div>
              </fieldset>
            </form>
            <div id='result'></div>
            <div id='all_jobs'>
            <?php
            if($flag){
                echo $content;
            }
            ?>
          </div>
        </section>
        <section class="col-md-6 right">
            <?php include_once "jobFilter.php"; ?><!--Search Filter -->
        </section>
      </div>
      <div class="clear-fix">
      </div>
        <?php include_once "footer.php"; ?><!--Site footer-->
  </div>
  <?php include "library.php"; ?><!--Script Library files -->
      <script type="text/javascript">
        $('#closeWindow-job-0').click(function(){

          $('#panel-job-0').css('visibility','hidden');
        });

        $('#closeWindow-job-1').click(function(){

          $('#panel-job-1').css('visibility','hidden');
        });

        $('#closeWindow-job-2').click(function(){

          $('#panel-job-2').css('visibility','hidden');
        });

        $('#closeWindow-job-3').click(function(){

          $('#panel-job-3').css('visibility','hidden');
        });

        $('#closeWindow-job-4').click(function(){

          $('#panel-job-4').css('visibility','hidden');
        });

        $('#closeWindow-job-5').click(function(){

          $('#panel-job-5').css('visibility','hidden');
        });
        $('#closeWindow-job-6').click(function(){

          $('#panel-job-6').css('visibility','hidden');
        });

          $('document').ready(function(){
            $('#filter_jobs').click(function(){
                  //$('#feedback').append('a');
                  $.get('include/ajax_job_search.php', {inputNaturalLanguageSearch: $('#inputNaturalLanguageSearch').val(),inputQualification: $('#inputQualification').val(),inputCareerField: $('#inputCareerField').val()},
                      function(result){
                          $('#result').html(result).show();
                          $('#all_jobs').css('visibility','hidden');
                      });
            });
              //$('#result').load('include/job_title_search.php').show();
              $('#inputNaturalLanguageSearch').keyup(function(){
                    //$('#feedback').append('a');
                    $.get('include/job_title_search.php', {inputNaturalLanguageSearch: $('#inputNaturalLanguageSearch').val()},
                        function(result){
                            $('#result').html(result).show();
                            $('#all_jobs').css('visibility','hidden');
                        });
              });
          }); //End of ready function
      </script>
  </body>
  </html>
