<?php session_start();
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
if((isset($_GET['experience_id']) && !empty($_GET['experience_id'])) && (isset($_GET['user_id']) && !empty($_GET['user_id']))){
  $exp_id =  mysql_fix_string(trim($_GET['experience_id']));
  $user_id =  mysql_fix_string(trim($_GET['user_id']));
  $exp_query = "SELECT * FROM seeker_work_experience WHERE seeker_id='$user_id' AND id='$exp_id'";
  $exp_result = $con->query($exp_query);
  $row ="";
  $exp['start_date'] = "";
  $exp['end_date'] ="";
  $affected_exp_rows 	= mysqli_num_rows($exp_result);
  if($affected_exp_rows>0){
    for($i=0;$i<$affected_exp_rows;$i++){
      $row = mysqli_fetch_array($exp_result);
      //print_r($row);
      $exp['start_date']= $row['start_date'];
      $exp['end_date'] = $row['end_date'];
      $exp['title']=$row['job_title'];
      $exp['organization']=$row['organization'];
      $exp['occupational_field']=$row['occupational_field'];
      $exp['career_field_id'] =$row['career_field_id'];
    }
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
       <div class="clear-fix">
       </div>
       <!--Div for the Password Reset Code Canvas-->
       <div class="container">
          <div class="col-md-6">
              <form method="post" action="<?php outputData($_SERVER['PHP_SELF']); ?>" name="frmSeekerExperience" id="frmSeekerExperience">
                <fieldset>
                  <legend class="legend">Update Record</legend>
                  <div class="form-group">
                    <label for="inputJobTitle" >Job Title<em class="required">*</em></label>
                    <input class="form-control" type="text" value="<?php echo $exp['title']; ?>" name="inputJobTitle" id="inputJobTitle"
                      placeholder="Enter Job Title"   />
                  </div>
                  <div class="form-group">
                    <label for="inputOrganization" >Organization Name<em class="required">*</em></label>
                    <input class="form-control" type="text" value="<?php echo $exp['organization']; ?>" name="inputOrganization" id="inputOrganization"
                      placeholder="Enter Organization name"   />
                  </div>
                  <div class="form-group">
                    <label for="inputOccupationalField" >Occupational Field<em class="required">*</em></label>
                    <input class="form-control" type="text" value="<?php echo $exp['occupational_field']; ?>"name="inputOccupationalField" id="inputOccupationalField"
                      placeholder="Enter Occupational Field"   />
                  </div>
                    <div class="form-group">
                        <label for="inputCareerField">Job Category<em class="required">*</em></label>
                        <?php
                        $queryStringCareerField = "SELECT * FROM job_category ORDER BY name";
                        $display = '<select class="form-control" name="inputCareerField">\n';
                        $display .='<option value="">--Job Category--</option>\n';
                        /*Fetching city data from the database*/
                        $resultCareerField = $con->query($queryStringCareerField);
                        $rowNum = mysqli_num_rows($resultCareerField);
                        if($resultCareerField){
                            for($i=0;$i<$rowNum;$i++) {
                                $row = mysqli_fetch_array($resultCareerField);
                                $name = $row['name'];
                                $id = $row['id'];
                                if($id==$exp['career_field_id']){
                                  $display .= "<option selected value='{$id}'>{$name}</option>\n";
                                }else{
                                  $display .= "<option value='{$id}'>{$name}</option>\n";
                                }
                            }
                        }
                        $display .='</select><br />';
                        echo $display;
                        ?>
                    </div>
                  <div class="form-group">
                    <label for="inputStartDate">Start Date<em class="required">*</em></label>
                    <input class="form-control" type="date" value="<?php echo $exp['start_date']; ?>"name="inputStartDate"id="inputStartDate" />
                  </div>
                  <div class="form-group">
                      <label for="inputNoEndDate">
                          <input type="checkbox" name="inputNoEndDate"value="1" id="inputNoEndDate" / ><span class="h2"></span> Still there</span>
                      </label>
                  </div>
                  <div class="form-group" id="endDate">
                      <label for="inputEndDate">End Date</label>
                      <input class="form-control" type="date" value="<?php echo $exp['end_date']; ?>"name="inputEndDate"id="inputEndDate" />
                  </div>
                  <p id="errorMessage" class="errorMessage">
                    <?php
                          if(isset($_POST['save'])){
                            if(isset($_POST['inputJobTitle']) && !empty($_POST['inputJobTitle'])){
                              if(isset($_POST['inputOrganization']) && !empty($_POST['inputOrganization'])){
                              if(isset($_POST['inputOccupationalField']) && !empty($_POST['inputOccupationalField'])){
                                if(isset($_POST['inputCareerField']) && !empty($_POST['inputCareerField'])){
                                  if(isset($_POST['inputStartDate']) && !empty($_POST['inputStartDate'])){
                                    $job_title = mysql_fix_string(trim($_POST['inputJobTitle']));
                                    $organization_name = mysql_fix_string(trim($_POST['inputOrganization']));
                                    $occupational_field =mysql_fix_string(trim($_POST['inputOccupationalField']));
                                    $field_of_study =mysql_fix_string(trim($_POST['inputCareerField']));
                                    $start_date =mysql_fix_string(trim($_POST['inputStartDate']));
                                    $end_date = isset($_POST['inputEndDate'])?mysql_fix_string(trim($_POST['inputEndDate'])):null;
                                    if(update_seeker_experience($con, $job_title, $organization_name,$occupational_field,$field_of_study,$start_date,$end_date,$user_id,$exp_id)){
                                      //($con, $job_title, $organization_name,$occupational_field,$field_of_study,$start_date,$end_date=NULL,$seeker_id))
                                        //echo "Record successfully added <img src='images/success.png' />";
                                        //echo "<meta content='5,seeker_skills.php' http-equiv='refresh' >";
                                        location('seeker_resume_view.php');
                                    }else{
                                        echo "Fatal error occured!";
                                    }
                                  }else{
                                      echo "Please select start date<span class='glyphicon glyphicon-remove'></span>";
                                  }//End of validation for field of study
                                }else{
                                    echo "Please select field of study<span class='glyphicon glyphicon-remove'></span>";
                                }//End of validation for field of study
                              }else{
                                  echo "Please enter Occupational field<span class='glyphicon glyphicon-remove'></span>";
                              }//End of validation for occupational field
                            }else{
                                echo "Please enter Organization name<span class='glyphicon glyphicon-remove'></span>";
                            }//End of validation for organization name
                         }else{
                            echo "Please enter job title<span class='glyphicon glyphicon-remove'></span>";
                        }//End of validation for job title
                        }
                      ?>
                  </p>
                  <div class="form-group form-inline">
                    <input type="submit" name="save" value="Update Record" class="btn btn-default btn-primary" />
                  </div>
            </fieldset>
          </form>
      </div>
   <!--End of the Signup Form -->
   <div class="col-md-6 ad">
     <?php include_once "topAgents.php";?>
   </div>
 </div><!--End of Container Div-->
   <?php include_once "footer.php"; ?><!--Site footer-->
<?php include "library.php"; ?><!--Script Library files -->
<script type="text/javascript">
	 //function toggleEndDate(){)
    document.getElementById('inputNoEndDate').onclick=function(){
      var toggle = document.getElementById('inputNoEndDate');
      var mydisplay = document.getElementById('endDate');
      if(toggle.checked){
        mydisplay.style.display="none";
      }
      else {
        mydisplay.style.display="block";
      }
    };
</script>
</body>
</html>
