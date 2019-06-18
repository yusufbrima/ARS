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
    //Checking if the user has confirmed their account
    $user_id = "";
    $seeker_id = "";
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
                $seeker_id = $dataset['id'];
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
              <form method="post" action="<?php outputData($_SERVER['PHP_SELF']); ?>" name="frmSeekerReference" id="frmSeekerReference">
                <fieldset>
                  <legend class="legend">Seeker References</legend>
                  <div class="form-group">
                      <label for="inputFirstName">First Name<em class="required">*</em></label>
                      <input class="form-control" type="text" value="<?php if(isset($_POST['inputFirstName'])) {echo outputData($_POST['inputFirstName']);} ?>" name="inputFirstName" id="inputFirstName"
                        placeholder="First Name"  />
                  </div>
                  <div class="form-group">
                      <label for="inputLastName">Last Name<em class="required">*</em></label>
                      <input class="form-control" type="text" value="<?php if(isset($_POST['inputLastName'])){echo outputData($_POST['inputLastName']); }?>" name="inputLastName" id="inputLastName"
                        placeholder="Last Name"  />
                  </div>
                  <div class="form-group">
                      <label for="inputSex">Sex:<em class="required">*</em></label>
                      <label for="SexMale">
                        <input type="radio" name="sex" value="M" id="SexMale" />Male
                      </label>
                      <label for="sexFemale">
                        <input  type="radio" name="sex" value="F" id="sexFemale" />Female
                      </label>
                      <label for="sexOthers">
                        <input  type="radio" name="sex" value="O" id="sexOthers" checked />Others
                      </label>
                  </div>
                  <div class="form-group">
                      <label for="inputOrganization">Organization<em class="required">*</em></label>
                      <input class="form-control" type="text" value="<?php if(isset($_POST['inputOrganization'])){echo outputData($_POST['inputOrganization']); }?>" name="inputOrganization" id="inputOrganization"
                        placeholder="Enter Reference's Organization"  />
                  </div>
                  <div class="form-group">
                        <label for="inputEmail">Email<em class="required">*</em></label>
                        <input class="form-control" type="email" name="inputEmail" id="inputEmail"
                          placeholder="Email" autocomplete="on" />
                    </div>
                    <div class="form-group">
                          <label for="inputWebLink">Web URL</label>
                          <input class="form-control" type="url" name="inputWebLink" id="inputWebLink" value="<?php if(isset($_POST['inputWebLink'])){echo outputData($_POST['inputWebLink']); }?>"
                            placeholder="Enter web link" >
                    </div>
                    <div class="form-group">
                      <label for="inputTelephone">Mobile<em class="required">*</em></label>
                      <input class="form-control" type="tel" value="<?php if(isset($_POST['inputTelephone'])){echo outputData($_POST['inputTelephone']); }?>" name="inputTelephone" id="inputTelephone"
                        placeholder="Phone Number"  />
                    </div>
                  <p id="errorMessage" class="errorMessage">
                    <?php
                          if(isset($_POST['save'])){
                            if(isset($_POST['inputFirstName']) && !empty($_POST['inputFirstName'])){
                              if(isset($_POST['inputLastName']) && !empty($_POST['inputLastName'])){
                                if(isset($_POST['inputOrganization']) && !empty($_POST['inputOrganization'])){
                                  if(isset($_POST['inputEmail']) && !empty($_POST['inputEmail'])){
                                      if (filter_var($_POST['inputEmail'],FILTER_VALIDATE_EMAIL)) {
                                        if (isset($_POST['inputTelephone']) && !empty($_POST['inputTelephone'])){
                                          if (isset($_POST['sex']) && !empty($_POST['sex'])){
                                            $reference_first_name = mysql_fix_string(trim($_POST['inputFirstName']));
                                            $reference_last_name = mysql_fix_string(trim($_POST['inputLastName']));
                                            $reference_organization =mysql_fix_string(trim($_POST['inputOrganization']));
                                            $reference_email =mysql_fix_string(trim($_POST['inputEmail']));
                                            $reference_telephone =mysql_fix_string(trim($_POST['inputTelephone']));
                                            $reference_sex =mysql_fix_string(trim($_POST['sex']));
                                            $reference_url = isset($_POST['inputWebLink'])?mysql_fix_string(trim($_POST['inputWebLink'])):null;
                                              if(add_seeker_reference($con, $reference_first_name,$reference_sex,$reference_last_name,$reference_organization,$reference_email,$reference_telephone,$reference_url,$seeker_id)){
                                                //($con, $job_title, $organization_name,$occupational_field,$field_of_study,$start_date,$end_date=NULL,$seeker_id))
                                                  //echo "Record successfully added <img src='images/success.png' />";
                                                  //echo "<meta content='5,seeker_skills.php' http-equiv='refresh' >";
                                                  location('seeker_resume_view.php');
                                              }else{
                                                  echo "Fatal error occured!";
                                              }
                                          }else{
                                            echo "Please select your salutation status<span class='glyphicon glyphicon-remove'></span>";
                                          }//Email sex field validation
                                        }else{
                                          echo "Please enter reference phone number<span class='glyphicon glyphicon-remove'></span>";
                                        }//Email validity checking
                                      }else{
                                        echo "Please enter a valid email address<span class='glyphicon glyphicon-remove'></span>";
                                      }//Email validity checking
                                  }else{
                                      echo "Please enter an email address<span class='glyphicon glyphicon-remove'></span>";
                                  }//End of organization name validation
                                }else{
                                    echo "Please enter organization name<span class='glyphicon glyphicon-remove'></span>";
                                }//End of organization name validation
                              }else{
                                  echo "Please enter last name<span class='glyphicon glyphicon-remove'></span>";
                              }//End of last name validation
                            }else{
                                echo "Please enter first name<span class='glyphicon glyphicon-remove'></span>";
                            }//End of first name validation

                          }
                      ?>
                  </p>
                  <div class="form-group form-inline">
                    <input type="submit" name="save" value="Save Record" class="btn btn-default btn-primary" />
                    <input type="reset" name="reset" value="Clear Form" class="btn btn-default btn-danger" />
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
</body>
</html>
