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
if((isset($_GET['skill_id']) && !empty($_GET['skill_id'])) && (isset($_GET['user_id']) && !empty($_GET['user_id']))){
  $skill_id =  mysql_fix_string(trim($_GET['skill_id']));
  $user_id =  mysql_fix_string(trim($_GET['user_id']));
  $seeker_query = "SELECT * FROM seeker_skill WHERE seeker_id='$user_id' AND id='$skill_id'";
  $seeker_result = $con->query($seeker_query);
  $affected_seeker_rows 	= mysqli_num_rows($seeker_result);
  if($affected_seeker_rows>0){
    for($i=0;$i<$affected_seeker_rows;$i++){
      $row = mysqli_fetch_array($seeker_result);
      //print_r($row);
    }
  }

}else{
  header('location: seeker_resume_view.php');
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
              <form method="post" action="<?php outputData($_SERVER['PHP_SELF']); ?>" name="frmSeekerSkills" id="frmSeekerSkills">
                <fieldset>
                  <legend class="legend">Update Skills Record</legend>
                  <div class="form-group">
                    <label for="inputLanguage" >Languages Known fluently<em class="required">*</em></label>
                    <input class="form-control" type="text" value="<?php echo $row['language_skill']; ?>"name="inputLanguage" id="inputLanguage"
                      placeholder="List Languages spoken"  autofocus="true" autocomplete="on" />
                  </div>
                  <div class="form-group">
                    <label for="inputComputerSkill" >Computer Skills<em class="required">*</em></label>
                    <input type="text" name="inputComputerSkill" class="form-control" value="<?php echo $row['computer_skill']; ?>" placeholder="List your Computer Skills"   autocomplete="on"/>
                  </div>
                  <div class="form-group">
                    <label for="inputLeadershipSkill" >Leadership Skills<em class="required">*</em></label>
                    <textarea cols="10" rows="4" name="inputLeadershipSkill" class="form-control">
                      <?php echo $row['leadership']; ?>
                    </textarea>
                  </div>
                  <div class="form-group">
                    <label for="inputInterest" >Special Interests list if any e.g, Reading!<em class="required">*</em></label>
                    <input type="text"  name="inputInterest" class="form-control" value="<?php echo $row['interest']; ?>" placeholder="Enter your special interests"/>
                  </div>
                  <div class="form-group">
                    <label for="inputOtherSkill" >Other Skills</label>
                    <textarea cols="10" rows="4" name="inputOtherSkill" class="form-control">
                      <?php echo $row['other_skill']; ?>
                    </textarea>
                  </div>
                  <p id="errorMessage" class="errorMessage">
                    <?php
                          if(isset($_POST['save'])){
                            if(isset($_POST['inputLanguage']) && !empty($_POST['inputLanguage'])){
                              if(isset($_POST['inputComputerSkill']) && !empty($_POST['inputComputerSkill'])){
                                if(isset($_POST['inputLeadershipSkill']) && !empty($_POST['inputLeadershipSkill'])){
                                  if(isset($_POST['inputInterest']) && !empty($_POST['inputInterest'])){
                                    $languages = mysql_fix_string(trim($_POST['inputLanguage']));
                                    $computer_skills = mysql_fix_string(trim($_POST['inputComputerSkill']));
                                    $leadership_skills =mysql_fix_string(trim($_POST['inputLeadershipSkill']));
                                    $special_interest =mysql_fix_string(trim($_POST['inputInterest']));
                                    $other_skills = isset($_POST['inputOtherSkill'])?mysql_fix_string(trim($_POST['inputOtherSkill'])):null;
                                    /*Inserting data into the database*/
                                    if(update_seeker_skills($con, $languages, $computer_skills,$leadership_skills,$special_interest,$other_skills,$skill_id,$user_id)){
                                      //($con, $job_title, $organization_name,$occupational_field,$field_of_study,$start_date,$end_date=NULL,$seeker_id))
                                        //echo "<span style='color:green'>Record successfully added <img src='images/success.png' /><span>";
                                        //echo "<meta content='5,seeker_skills.php' http-equiv='refresh' >";
                                        location('seeker_resume_view.php');
                                    }else{
                                        echo "Fatal error occured!";
                                    }
                                  }else{
                                      echo "Please enter your special interests<span class='glyphicon glyphicon-remove'></span>";
                                  }//End of Other attained skills validation
                                }else{
                                    echo "Please list your your leadership Skills<span class='glyphicon glyphicon-remove'></span>";
                                }//End of leadership skills validation
                              }else{
                                  echo "Please enter list your Computer Skills<span class='glyphicon glyphicon-remove'></span>";
                              }//End of Computer skills validation
                            }else{
                                echo "Please enter list languages known<span class='glyphicon glyphicon-remove'></span>";
                            }//End of Langauges name validation

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
</body>
</html>
