<?php session_start();
/*authorization of recruiters only to post view page*/
if(!isset($_SESSION['recruiter_username']) && empty($_SESSION['recruiter_username'])){
  if(!isset($_SESSION['user_type_recruiter']) && empty($_SESSION['user_type_recruiter'])){
    header('location: login.php?warning=Authentication required, you must login first to access this page!');
  }
}else{
  $username=$_SESSION['recruiter_username'];
}

include('include/dbcon.php');
include('include/function_library.php');
/*Retrieving seeker data for preview*/
if((isset($_GET['seeker_id']) && !empty($_GET['seeker_id'])) && (isset($_GET['user_id']) && !empty($_GET['user_id']))){
  $seeker_id =  mysql_fix_string(trim($_GET['seeker_id']));
  $user_id =  mysql_fix_string(trim($_GET['user_id']));
  $flag['personal'] = false;
  $flag['education']=false;
  $flag['experience'] = false;
  $flag['skills'] = false;
  $flag['reference'] = false;
  if(!empty($user_id)){
    $query 		= "SELECT s.id,s.first_name,s.last_name,s.marital_status,s.dob,s.phone_one,s.profile_url,s.street,s.sex,c.nicename,p.name,ci.name,u.join_date,u.email FROM seeker AS s LEFT JOIN country AS c ON s.country=c.id LEFT JOIN province AS p ON s.province=p.id LEFT JOIN city AS ci ON s.city=ci.id LEFT JOIN user AS u on s.user_id=u.user_id  WHERE s.user_id='$user_id'";
    $result = $con->query($query);
    $seeker['first_name']="";
    $seeker['last_name']="";
    $seeker['phone_number']="";
    $seeker['email']="";
    $seeker['profile_url']="";
    $seeker['marital_status']="";
    $seeker['city']="";
    $seeker['province']="";
    $seeker['country']="";
    $seeker['dob']="";
    $seeker['sex']="";
    $seeker['id']="";
    $seeker['street']="";
    $seeker_profile ="";
    $num_row 	= mysqli_num_rows($result);
    if($num_row>0){
      for($i=0;$i<$num_row;$i++){
        $row = mysqli_fetch_array($result);
        //print_r($row);
        $flag['personal'] = true;
        $seeker['id']=$row['id'];
        $seeker['first_name']=$row['first_name'];
        $seeker['last_name']=$row['last_name'];
        $seeker['phone_number']=$row['phone_one'];
        $seeker['profile_url']=$row['profile_url'];
        $seeker['marital_status']=$row['marital_status'];
        $seeker['city']=$row['name'];
        $seeker['email']=$row['email'];
        $seeker['province']=$row[10];
        $seeker['country']=$row['nicename'];
        $seeker['dob']=$row['dob'];
        $seeker['sex']=$row['sex'];
        $seeker['street']=$row['street'];
        $posted = strtotime($row['join_date']);
        $output = date('l jS \of F Y h:i:s A',$posted);
      }
    }
        if($seeker['sex']=="M"){
          $seeker['sex']="Mr.";
        }elseif ($seeker['sex']=="F") {
          $seeker['sex']="Ms.";
        }else{
          $seeker['sex']="N/A";
        }
        $path = "imageUpload/".$seeker['profile_url'];
        $img = "<img src='{$path}' alt='Profile Picture' class='img-circle' />";
        $seeker_profile ="<div class='container'><table border='0'>";
        $seeker_profile .="<tr><td class='profile_title'>Salutation</td><td>{$seeker['sex']}</td></tr>";
        $seeker_profile .="<tr><td class='profile_title'>First Name</td><td>{$seeker['first_name']}</td></tr>";
        $seeker_profile .="<tr><td class='profile_title'>Last Name</td><td> {$seeker['last_name']}</td></tr>";
        $seeker_profile .="<tr><td class='profile_title'>Birthday</td><td> {$seeker['dob']}</td></tr>";
        $seeker_profile .="<tr><td class='profile_title'>Mobile Contact</td><td><span class='glyphicon glyphicon-phone-alt'></span> {$seeker['phone_number']}</td></tr>";
        $seeker_profile .="<tr><td class='profile_title'>Marital Status</td><td> {$seeker['marital_status']}</td></tr>";
        $seeker_profile .="<tr><td class='profile_title'>Email</td><td><span class='glyphicon glyphicon-envelope'></span> {$seeker['email']}</td></tr>";
        $seeker_profile .="<tr><td class='profile_title'>Address</td><td> {$seeker['street']}</td></tr>";
        $seeker_profile .="<tr><td class='profile_title'>City</td><td> {$seeker['city']}</td></tr>";
        $seeker_profile .="<tr><td class='profile_title'>Province</td><td> {$seeker['province']}</td></tr>";
        $seeker_profile .="<tr><td class='profile_title'>Country</td><td>{$seeker['country']}</td></tr>";
        $seeker_profile .="<tr><td class='profile_title'>Been a member since </td><td>{$output}</td></tr>";
        $seeker_profile .="</table></div>";

        //End of seeker personal info retrieval
        $seeker_id = $seeker['id'];
        $education_query = "SELECT se.id,se.institution_name,se.start_date,se.end_date,e.level,c.name FROM seeker_education as se LEFT JOIN education_level as e on se.education_level=e.id LEFT JOIN career_field as c on se.field_of_study=c.id WHERE se.seeker_id='$seeker_id'";
        $education_result = $con->query($education_query);
        $seeker_education ="<div><table class='table table-striped' border='0'>";
        $seeker_education .="<caption class='h3'>Educational Background</caption>";
        $seeker_education .="<tr><th>Institution Name</th><th>Qualification attained</th><th>Field of Study</th><th>Start Date</th><th>End Date</th></tr>";
        $affected_rows 	= mysqli_num_rows($education_result);
         if($affected_rows>0){
          $flag['education'] =true;
          for($i=0;$i<$affected_rows;$i++){
            $row = mysqli_fetch_array($education_result);
            $seeker_education .="<tr><td>{$row['institution_name']}</td>";
            $seeker_education .="<td>{$row['level']}</td>";
            $seeker_education .="<td>{$row['name']}</td>";
            $seeker_education .="<td>{$row['start_date']}</td>";
            $seeker_education .="<td>{$row['end_date']}<td></tr>";
          }
          $seeker_education .="</table></div>";
        }
        //End of seeker experience info retrieval
        $experience_query = "SELECT ex.id,ex.job_title,ex.occupational_field,ex.start_date,ex.end_date,job.name FROM seeker_work_experience as ex LEFT JOIN job_category AS job ON ex.career_field_id=job.id WHERE ex.seeker_id='$seeker_id'";
        $experience_result = $con->query($experience_query);
        $seeker_experience ="<div><table class='table table-striped' border='0'>";
        $seeker_experience .="<caption class='h3'>Work Experience</caption>";
        $seeker_experience .="<tr><th>Job Title</th><th>Occupational Field</th><th>Job Category</th><th>Start Date</th><th>End Date</th></tr>";
        $affected_ex_rows 	= mysqli_num_rows($experience_result);
        if($affected_ex_rows>0){
         $flag['experience'] =true;
         for($i=0;$i<$affected_ex_rows;$i++){
           $row = mysqli_fetch_array($experience_result);
           //print_r($row);
           $exp_id = $row['id'];
           $seeker_experience .="<tr><td>{$row['job_title']}</td>";
           $seeker_experience .="<td>{$row['occupational_field']}</td>";
           $seeker_experience .="<td>{$row['name']}</td>";
           $seeker_experience .="<td>{$row['start_date']}</td>";
           $seeker_experience .="<td>{$row['end_date']}</td></tr>";
         }
         $seeker_experience .="</table></div>";
       }
         //End of seeker skills info retrieval
         $skills_query = "SELECT * FROM seeker_skill WHERE seeker_id='$seeker_id'";
         $skills_result = $con->query($skills_query);
         $seeker_skills ="<div><table class='table table-striped' border='0'>";
         $seeker_skills .="<caption class='h3'>Acquired  Skills</caption>";
         $seeker_skills .="<tr><th>Languages spoken</th><th>Computer Skills</th><th>Leadership Skills</th><th>Special Interests</th><th>Other Skills</th></tr>";
         $affected_skills_rows 	= mysqli_num_rows($skills_result);
         if($affected_skills_rows>0){
          $flag['skills'] =true;
          for($i=0;$i<$affected_skills_rows;$i++){
            $row = mysqli_fetch_array($skills_result);
            //print_r($row);
            $skill_id = $row['id'];
            $seeker_skills .="<tr><td>{$row['language_skill']}</td>";
            $seeker_skills .="<td>{$row['computer_skill']}</td>";
            $seeker_skills .="<td>{$row['leadership']}</td>";
            $seeker_skills .="<td>{$row['interest']}</td>";
            $seeker_skills .="<td>{$row['other_skill']}</td></tr>";
          }
          $seeker_skills .="</table></div>";
        }
        //End of seeker skills data operations
        $reference_query = "SELECT * FROM seeker_reference WHERE seeker_id='$seeker_id'";
        $reference_result = $con->query($reference_query);
        $seeker_reference ="<div><table class='table table-striped' border='0'>";
        $seeker_reference .="<caption class='h3'>My References</caption>";
        $seeker_reference .="<tr><th>First Name</th><th>Last Name</th><th>Organization</th><th>Email</th><th>Website URL</th><th>Contact</th></tr>";
        $affected_ref_rows 	= mysqli_num_rows($reference_result);
        if($affected_ref_rows>0){
         $flag['reference'] =true;
         for($i=0;$i<$affected_ref_rows;$i++){
           $row = mysqli_fetch_array($reference_result);
           //print_r($row);
           if($row['salutation']=="M"){
             $sex['sex']="Mr.";
           }elseif ($row['salutation']=="F") {
             $sex['sex']="Ms.";
           }else{
             $sex['sex']="N/A";
           }
           $url = $row['web_link'];
           $ref_id  = $row['id'];
           $seeker_reference .="<td>{$sex['sex']}&nbsp;&nbsp;&nbsp;{$row['first_name']}</td>";
           $seeker_reference .="<td>{$row['last_name']}</td>";
           $seeker_reference .="<td>{$row['organization']}</td>";
           $seeker_reference .="<td>{$row['email']}</td>";
           $seeker_reference .="<td><a href='{$url}' target='_blank'>{$url}</a></td>";
           $seeker_reference .="<td><span class='glyphicon glyphicon-phone-alt'></span> {$row['phone']}</td></tr>";
         }
         $seeker_reference .="</table></div>";
       }
      }

}else{
  header('location: resumeSearch.php');
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
     <!--Clearing of the float rule-->
     <div class="menu-spacer"></div>
     <div class="clear-fix clear-top"></div>
       <!--Div for the Password Reset Code Canvas-->
       <div class="container resume_img">
            <em class="h1 job_preview_title">Resume overview</em><hr />
              <?php if($flag['personal']){
                echo $img;
                echo $seeker_profile;
              }
              ?>
                <hr />

              <?php if($flag['education']){
                //echo "<div class='h3'>Educational Background</div>";
                echo $seeker_education;
              }
              ?>
              <hr />

            <?php if($flag['experience']){
              echo $seeker_experience;
            }
            ?>
            <hr />

          <?php if($flag['skills']){
            echo $seeker_skills;
          }
          ?>
          <hr />

        <?php if($flag['reference']){
          echo $seeker_reference;
        }
        ?>
     </div><!--End of Container Div-->
       <?php include_once "footer.php"; ?><!--Site footer-->
 <?php include "library.php"; ?><!--Script Library files -->
 </body>
 </html>
