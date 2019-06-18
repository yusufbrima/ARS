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
//Checking if the user has confirmed their account
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
   $recruiter_id = $recruiter['id'];
   $query ="SELECT j.id,j.title,c.name,j.occupational_field,j.attachment_URL,j.post_date FROM job AS j LEFT JOIN job_category AS c ON j.career_field_id=c.id WHERE j.recruiter_id='$recruiter_id'";
   $result = $con->query($query);
   $recruiter_job = "";
   $recruiter_job ="<div><table class='table table-striped' border='0'>";
   $recruiter_job .="<caption class='h3'>Job Catalog<br /><a href='recruiter_job_posting.php' class='add_job'><span class='glyphicon glyphicon-plus'></span> Add Job</a></caption>";
   $recruiter_job .="<tr><th>Job Title</th><th>Job Category</th><th>Occupational Field</th><th>Job Document</th><th>Posted date</th></tr>";
   $num_row 	= mysqli_num_rows($result);
   if($num_row>0){
     for($i=0;$i<$num_row;$i++){
       $row = mysqli_fetch_array($result);
       $recruiter['title'] = $row['title'];
       $recruiter['job_name']= $row['name'];
       $recruiter['occupational_field']= $row['occupational_field'];
       $recruiter['job_id'] = $row['id'];
       $recruiter['attachment_url']=$row['attachment_URL'];
       $posted = strtotime($row['post_date']);
       $output = date('l jS \of F Y h:i:s A',$posted);
       //print_r($row);
       $flag = true;
       $link ="_job_posts/".$recruiter['attachment_url'];
       $job_document = "<a href='{$link}'><span class='glyphicon glyphicon-file'></span></a>";
       $recruiter_job .="<tr><td>{$recruiter['title']}</td><td>{$recruiter['job_name']}</td><td>{$recruiter['occupational_field']}</td><td>{$job_document}</td><td>{$output} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='recruiter_job_details.php?job_id={$recruiter['job_id']}&recruiter_id={$recruiter['id']}'><span class='glyphicon glyphicon-eye-open'></span></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href='recruiter_job_update.php?job_id={$recruiter['job_id']}&recruiter_id={$recruiter['id']}'><span class='glyphicon glyphicon-edit'></span></a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href='#' data-href='recruiter_jobs.php?job_id={$recruiter['job_id']}&recruiter_id={$recruiter['id']}&link={$link}' data-toggle='modal' data-target='#recruiter_job_delete'><span class='glyphicon glyphicon-trash'></span></a></td></tr>";
    }
    $recruiter_job .="</table></div>";
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
    <div class="container">
      <form>
        <fieldset>
            <legend>Filter Jobs</legend>
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
                      $queryStringEmployment_type = "SELECT * FROM employment_type ORDER BY name";
                      $displayEmployment_type = '<select class="form-control" name="inputEmloymentType" id="inputEmloymentType"><br />';
                      $displayEmployment_type .='<option value="">--Employment Type--</option>\n';
                      /*Fetching city data from the database*/
                      $resultEmployment_type = $con->query($queryStringEmployment_type);
                      $rowNum = mysqli_num_rows($resultEmployment_type);
                      if($resultEmployment_type){
                          for($i=0;$i<$rowNum;$i++) {
                              $row = mysqli_fetch_array($resultEmployment_type);
                              $name = $row['name'];
                              $id = $row['id'];
                              $displayEmployment_type .= "<option value='{$id}'>{$name}</option><br />";
                          }
                      }
                      $displayEmployment_type .='</select>';
                      echo $displayEmployment_type;
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
              <input type="button" name="filter_jobs" onclick="ajax_call();" value="Apply" class="btn btn-default btn-primary" accesskey="C" />
            </div>
        </fieldset>
      </form>
      <div id="result"></div>
      <div id="all_jobs">
      <?php
      if($flag){
        //echo "<div class='h3'>Educational Background</div>";
        echo $recruiter_job;

      }else{
        echo "<a href='recruiter_job_posting.php'><span class='glyphicon glyphicon-plus'></span>Post Jobs now!</a>";
      }
      ?>
    </div>
     </div><!--End of Container Div-->
    <?php include_once "footer.php"; ?><!--Site footer-->
  <?php include "library.php"; ?><!--Script Library files -->
  <?php include_once "recruiter_job_delete.php"; ?><!--job delete modal-->
  <script type="text/javascript">
    $('#recruiter_job_delete').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
    });
    function ajax_call(){
      var simpleXmlHttp = new XMLHttpRequest();
      var response =  document.getElementById('result');
      var inputEmloymentType =  document.getElementById('inputEmloymentType').value;
      var inputQualification =  document.getElementById('inputQualification').value;
      var inputCareerField =  document.getElementById('inputCareerField').value;
      simpleXmlHttp.open('GET','include/recruiter_job_search.php?inputEmloymentType='+inputEmloymentType+'&inputQualification='+inputQualification+'&inputCareerField='+inputCareerField,true);
      simpleXmlHttp.send();
      simpleXmlHttp.onreadystatechange =function(){
        if(simpleXmlHttp.readyState==4 && simpleXmlHttp.status==200){
          $('#all_jobs').css('visibility','hidden');
          response.innerHTML = simpleXmlHttp.responseText;
        }
      }
    }
  </script>
 </body>
 </html>
