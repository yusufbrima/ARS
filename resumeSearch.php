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
$flag = false;
$query ="SELECT s.id,s.first_name,s.last_name,s.profile_url,u.join_date,s.sex,s.street,u.user_id FROM seeker AS s LEFT JOIN user AS u ON s.user_id=u.user_id ORDER BY u.join_date";
$resume_result = $con->query($query);
$content ='<div class="row advert">';
$content .="<div class='h2'>Recently Posted Resumes</div>";
$affected_resume_rows 	= mysqli_num_rows($resume_result);
if($affected_resume_rows>0){
  for($i=0;$i<$affected_resume_rows;$i++){
    $row = mysqli_fetch_array($resume_result);
    //print_r($row);
    $flag = true;
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
       <div class="clear-fix">
       </div>
      <div class="container">
        <section class="col-md-6 left">
          <form  class="form-inline">
            <fieldset>
                <legend>Search for Resume</legend>
                <div class="form-inline">
                    <?php
                      $queryStringCity = "SELECT * FROM city";
                      $bucket = '<select class="form-control" name="city" id="city">';
                      $bucket .='<option value="">--Select City--</option>';
                      /*Fetching city data from the database*/
                       $resultCity = $con->query($queryStringCity);
                      $rowNum = mysqli_num_rows($resultCity);
                      if($resultCity){
                        for($i=0;$i<$rowNum;$i++) {
                        $row = mysqli_fetch_array($resultCity);
                        $name = $row['name'];
                        $id = $row['id'];
                        $bucket .= "<option value='{$id}'>{$name}</option>\n";
                        }
                      }
                    $bucket .='</select>';
                    echo $bucket;
                  ?>
                  <?php
                  /*Fetching data for the province control*/
                  $queryStringCountry = "SELECT * FROM country ORDER BY nicename";
                  $outputCountry ='<select class="form-control" name="inputCountry" id="inputCountry">\n<option value="">--Select Country--</option>';
                  $resultCountry = $con->query($queryStringCountry);
                  $rowNumCountry = mysqli_num_rows($resultCountry);
                  if($resultCountry){
                    for($i=0;$i<$rowNumCountry;$i++) {
                     $row = mysqli_fetch_array($resultCountry);
                     $name = $row['nicename'];
                     $id = $row['id'];
                    $outputCountry .="<option value='{$id}'>{$name}</option>\n";
                   }
                 }
                  $outputCountry .= '</select>';
                  echo $outputCountry;
                  ?>
                  <input class="form-control" type="text" value="" name="inputFirstName" id="inputFirstName" placeholder="Enter Search Key word"  />
                  <input type="button" name="filter_jobs" value="Search" class="btn btn-default btn-primary job-search" onclick="retrieve_resume();" />
                </div>
            </fieldset>
          </form>
          <div id='result'>
          </div>
            <div id='all_jobs'>
            <?php
            if($flag){
                echo $content;
            }
            ?>
          </div>
        </section>
        <section class="col-md-6 right">
            <?php include_once "resumeFilter.php"; ?><!--Search Filter -->

        </section>
      </div>
      <div class="clear-fix">
      </div>
        <?php include_once "footer.php"; ?><!--Site footer-->
  <?php include "library.php"; ?><!--Script Library files -->
  <script>
      $('#closeWindow-resume-0').click(function(){

        $('#panel-resume-0').css('visibility','hidden');
      });

      $('#closeWindow-resume-1').click(function(){

        $('#panel-resume-1').css('visibility','hidden');
      });

      $('#closeWindow-resume-3').click(function(){

        $('#panel-resume-3').css('visibility','hidden');
      });
      $('#closeWindow-resume-4').click(function(){

        $('#panel-resume-4').css('visibility','hidden');
      });
      $('#closeWindow-resume-5').click(function(){

        $('#panel-resume-5').css('visibility','hidden');
      });
      /*Ajax job search by parameter*/
      function retrieve_resume(){
        var simpleXmlHttp = new XMLHttpRequest();
        var response =  document.getElementById('result');
        var inputFirstName =  document.getElementById('inputFirstName').value;
        var city =  document.getElementById('city').value;
        var inputCountry =  document.getElementById('inputCountry').value;
        simpleXmlHttp.open('GET','include/ajax_resume_search.php?inputFirstName='+inputFirstName+'&city='+city+'&inputCountry='+inputCountry,true);
        simpleXmlHttp.send();
        simpleXmlHttp.onreadystatechange =function(){
          if(simpleXmlHttp.readyState==4 && simpleXmlHttp.status==200){
            $('#all_jobs').css('visibility','hidden');
            response.innerHTML = simpleXmlHttp.responseText;
          }
        }
      }
      /*Ajax call for resume search by name*/
      $('document').ready(function(){
          //$('#result').load('include/job_title_search.php').show();
          $('#inputFirstName').keyup(function(){
                //$('#feedback').append('a');
                $.get('include/resume_name_search.php', {inputFirstName: $('#inputFirstName').val()},
                    function(result){
                        $('#result').html(result).show();
                        $('#all_jobs').css('visibility','hidden');
                    });
          });
      }); //End of ready function
  </script>
  </body>
  </html>
