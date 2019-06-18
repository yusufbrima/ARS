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

$s_id="";
$user_id="";
$job_count=0;
if(!empty($username)){
    $query 		= "SELECT * FROM user WHERE username='$username'";
    $result = $con->query($query);
    $row		= mysqli_fetch_array($result);//Fetching of user data from the database into the row array
    $num_row 	= mysqli_num_rows($result);
    if ($num_row  == 1){
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
      if($affected_count_rows==1){
        for($i=0;$i<$affected_count_rows;$i++){
          $dataset = mysqli_fetch_array($job_result);
          $job_count = $dataset['count'];
        }
      }
    }
}
$data_table ="seeker";
if(!checker_seeker($con,$user_id,$data_table)){
  header('location:seeker_profile_view.php?msg=Please add your profile details first!');
  //$_SESSION['error_message'] = "Please add your profile details first!";
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
              <li><a href="seeker_job_prefrence_view.php"><span class="glyphicon glyphicon-wrench"></span>Job Prefrences</a></li>
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
         <form method="post" name="frmJobPosting" id="frmJobPosting">
           <fieldset>
             <legend class="legend">Set up your job prefrences</legend>
                 <div class="form-group">
                     <label for="inputEmloymentType">Preferred Employment Type<em class="required">*</em></label>
                       <?php
                       $queryStringEmployment_type = "SELECT * FROM employment_type ORDER BY name";
                       $displayEmployment_type = '<select class="form-control" name="inputEmloymentType" id="inputEmloymentType">\n';
                       $displayEmployment_type .='<option value="">--Employment Type--</option>\n';
                       /*Fetching city data from the database*/
                       $resultEmployment_type = $con->query($queryStringEmployment_type);
                       $rowNum = mysqli_num_rows($resultEmployment_type);
                       if($resultEmployment_type){
                           for($i=0;$i<$rowNum;$i++) {
                               $row = mysqli_fetch_array($resultEmployment_type);
                               $name = $row['name'];
                               $id = $row['id'];
                               $displayEmployment_type .= "<option value='{$id}'>{$name}</option>\n";
                           }
                       }
                       $displayEmployment_type .='</select><br />';
                       echo $displayEmployment_type;
                       ?>
                 </div>
                 <div class="form-group">
                     <label for="inputWorkExperience">Preferred Work Experience<em class="required">*</em></label>
                       <?php
                       $queryStringExperience_level = "SELECT * FROM experience_level ORDER BY name";
                       $displayExperience_level = '<select class="form-control" name="inputWorkExperience" id="inputWorkExperience">\n';
                       $displayExperience_level .='<option value="">--Experience Level--</option>\n';
                       /*Fetching city data from the database*/
                       $resultExperience_level = $con->query($queryStringExperience_level);
                       $rowNum = mysqli_num_rows($resultExperience_level);
                       if($resultExperience_level){
                           for($i=0;$i<$rowNum;$i++) {
                               $row = mysqli_fetch_array($resultExperience_level);
                               $name = $row['name'];
                               $id = $row['id'];
                               $displayExperience_level .= "<option value='{$id}'>{$name}</option>\n";
                           }
                       }
                       $displayExperience_level .='</select><br />';
                       echo $displayExperience_level;
                       ?>
                 </div>
                 <div class="form-group ">
                   <label for="inputLanguage" >Preferred Salary scale per annum<em class="required">*</em></label>
                   <div class="input-group">
                     <span class = "input-group-addon">Le</span>
                   <input class="form-control" type="number" value="" name="inputSalary" id="inputSalary"
                     placeholder="Enter salary Scale"   />
                   </div>
                 </div>
                 <div class="form-group">
                     <label for="inputCareerField">Preferred Job Category<em class="required">*</em></label>
                     <?php
                     $queryStringCareerField = "SELECT * FROM job_category ORDER BY name";
                     $display = '<select class="form-control" name="inputCareerField" id="inputCareerField">\n';
                     $display .='<option value="">--Job Category--</option>\n';
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
                     $display .='</select><br />';
                     echo $display;
                     ?>
                 </div>
                 <div class="form-group">
                   <label for="inputCity">Preferred Job City<em class="required">*</em></label>
                   <?php
                     $queryStringCity = "SELECT * FROM city ORDER BY name";
                     $bucket = '<select class="form-control" name="city" id="city">\n';
                     $bucket .='<option value="">--Select City--</option>\n';
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
                   $bucket .='</select><br />';
                   echo $bucket;
                 ?>
                 </div>
               <div class="form-group">
                 <label for="inputProvince">Preferred Job Province<em class="required">*</em></label>
                   <?php
                   /*Fetching data for the province control*/
                   $queryStringProvince = "SELECT * FROM province ORDER BY name";
                   $cost_bucket ='<select class="form-control" name="inputProvince" id="inputProvince">\n';
                   $cost_bucket .='<option value="">--Select Province--</option>\n';
                   $resultProvince = $con->query($queryStringProvince);
                   $rowNumProvince = mysqli_num_rows($resultProvince);
                   if($resultProvince){
                     for($i=0;$i<$rowNumProvince;$i++) {
                      $row = mysqli_fetch_array($resultProvince);
                      $name = $row['name'];
                      $id = $row['id'];
                     $cost_bucket .="<option value='{$id}'>{$name}</option>\n";
                    }
                  }
                   $cost_bucket .= '</select>';
                   echo $cost_bucket;
                   ?>
               </div>
               <input type="hidden" name="seeker_id" id="inputSeeker_ID" value="<?php echo $s_id; ?>" />
               <input type="hidden" name="user_id" id="inputUser_ID" value="<?php echo $user_id; ?>" />
                 <div id="prefrence_result"></div>
               <div class="form-group form-inline">
                 <input type="button" name="post" value="Save Prefrences"id="save_prefrences" class="btn btn-default btn-primary" accesskey="C" />
               </div>
           </fieldset>
         </form>
       </div>
       <div class="col-md-6">
         <?php include_once "topAgents.php"; ?>
      </div>
     </div><!--End of Container Div-->
 <?php include_once "footer.php"; ?><!--Site footer-->
 <?php include "library.php"; ?><!--Script Library files -->
 </body>
 <script type="text/javascript">
       $('document').ready(function(){
           $('#result').load('include/seeker_job_preferences.php').show();
           $('#save_prefrences').click(function(){
                 //$('#feedback').append('a');
                 $.get('include/seeker_job_preferences.php', {inputEmloymentType: $('#inputEmloymentType').val(),inputWorkExperience: $('#inputWorkExperience').val(),inputSalary: $('#inputSalary').val(),inputCareerField: $('#inputCareerField').val(),city: $('#city').val(),inputProvince: $('#inputProvince').val(),inputSeeker_ID: $('#inputSeeker_ID').val(),inputUser_ID: $('#inputUser_ID').val()},
                     function(result){
                         $('#prefrence_result').html(result).show();
                     });
           });
       }); //End of ready function
 </script>
 </html>
