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
include("include/class.filetotext.php");
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
 $recruiter_query = "SELECT id FROM recruiter WHERE user_id='$user_id'";
 $recruiter_result = $con->query($recruiter_query);
 $affected_recruiter_rows 	= mysqli_num_rows($recruiter_result);
 if($affected_recruiter_rows>0){
   for($i=0;$i<$affected_recruiter_rows;$i++){
     $row = mysqli_fetch_array($recruiter_result);
     //print_r($row);
     $recruiter['id'] = $row['id'];
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
 $data_table ="recruiter";
 if(!checker_seeker($con,$user_id,$data_table)){
   header('location:recruiter_profile_view.php?msg=Please add your profile details first!');
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
              <li><a href="recruiter_applications.php"><span class="glyphicon glyphicon-envelope"></span> Recieved Applications <span class="badge"><?php echo $application_count;?></span></a></li>
              <li><a href="seeker_settings.php"><span class="glyphicon glyphicon-wrench"></span> Settings</a></li>
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
            <form method="post" action="<?php outputData($_SERVER['PHP_SELF']); ?>" enctype='multipart/form-data' name="frmJobPosting" id="frmJobPosting">
              <fieldset>
                <legend class="legend"><img src="images/potential_employee.png" alt="Post a job and meet potential employees today!" /></legend>
                    <div class="form-group">
                        <label for="inputJobTitle">Job Title<em class="required">*</em></label>
                        <input class="form-control" type="text" value="<?php if(isset($_POST['inputJobTitle'])) {echo outputData($_POST['inputJobTitle']);} ?>" name="inputJobTitle" id="inputJobTitle"
                          placeholder="Enter Job Title"  />
                    </div>
                    <div class="form-group">
                      <label for="inputOccupationalField" >Occupational Field<em class="required">*</em></label>
                      <input class="form-control" type="text" value="<?php if(isset($_POST['inputOccupationalField'])){echo $_POST['inputOccupationalField']; }?>"name="inputOccupationalField" id="inputOccupationalField"
                        placeholder="Enter Occupational Field"   />
                    </div>
                    <div class="form-group">
                        <label for="inputEmloymentType">Employment Type<em class="required">*</em></label>
                          <?php
                          $queryStringEmployment_type = "SELECT * FROM employment_type ORDER BY name";
                          $displayEmployment_type = '<select class="form-control" name="inputEmloymentType">\n';
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
                        <label for="inputWorkExperience">Work Experience<em class="required">*</em></label>
                          <?php
                          $queryStringExperience_level = "SELECT * FROM experience_level ORDER BY name";
                          $displayExperience_level = '<select class="form-control" name="inputWorkExperience">\n';
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
                      <label for="inputLanguage" >Salary scale per annum<em class="required">*</em></label>
                      <div class="input-group">
                        <span class = "input-group-addon">Le</span>
                      <input class="form-control" type="number" value="<?php if(isset($_POST['inputSalary'])){echo $_POST['inputSalary']; }?>"name="inputSalary" id="inputSalary"
                        placeholder="Enter salary scale"   />
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputLanguage" >Languages Skills<em class="required">*</em></label>
                      <input class="form-control" type="text" value="<?php if(isset($_POST['inputLanguage'])){echo $_POST['inputLanguage']; }?>"name="inputLanguage" id="inputLanguage"
                        placeholder="Enter language criteria"   />
                    </div>
                    <div class="form-group">
                      <label for="inputSkillAndAbilities">Skills and abilities<em class="required">*</em></label>
                      <textarea cols="10" rows="4" name="inputSkillAndAbilities" class="form-control">
                            <?php if(isset($_POST['inputSkillAndAbilities'])){echo $_POST['inputSkillAndAbilities']; }?>
                      </textarea>
                    </div>
                    <div class="form-group">
                      <label for="inputJobDescription">Job Description<em class="required">*</em></label>
                      <textarea cols="10" rows="4" name="inputJobDescription" class="form-control">
                            <?php if(isset($_POST['inputJobDescription'])){echo $_POST['inputJobDescription']; }?>
                      </textarea>
                    </div>
                    <div class="form-group">
                        <label for="inputJobTag">Job Tag</label>
                        <input class="form-control" type="text" value="<?php if(isset($_POST['inputJobTag'])){echo outputData($_POST['inputJobTag']); }?>" name="inputJobTag" id="inputJobTag"
                          placeholder="Enter job tags for easy search"  />
                    </div>
                    <div class="form-group">
                          <label for="inputWebLink">Web Link Title</label>
                          <input class="form-control" type="text" name="inputURLTitle" id="inputURLTitle" value="<?php if(isset($_POST['inputURLTitle'])){echo outputData($_POST['inputURLTitle']); }?>"
                            placeholder="Enter web link title" />
                    </div>
                    <div class="form-group">
                          <label for="inputURLTitle">Web URL</label>
                          <input class="form-control" type="url" name="inputWebLink" id="inputWebLink" value="<?php if(isset($_POST['inputWebLink'])){echo outputData($_POST['inputWebLink']); }?>"
                            placeholder="Enter web link" />
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="MAX_FILE_SIZE" value="2097152" />
                      <label for="inputJobAttachment">Job Attachment<em class="required">*</em></label>
                      <input class="form-control" type="file" name="inputJobAttachment" id="inputJobAttachment" />
                        <p class="notice">Choose only pdf|doc|docx images and file size must not be greater than 2MB</p>
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
                                $display .= "<option value='{$id}'>{$name}</option>\n";
                            }
                        }
                        $display .='</select><br />';
                        echo $display;
                        ?>
                    </div>
                    <div class="form-group">
                        <label for="inputQualification">Qualification<em class="required">*</em></label>
                        <?php
                        $queryStringEducation = "SELECT * FROM education_level ORDER BY level";
                        $display = '<select class="form-control" name="inputQualification">\n';
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
                        $display .='</select><br />';
                        echo $display;
                        ?>
                    </div>
                    <div class="form-group">
                      <label for="inputCity">City<em class="required">*</em></label>
                      <?php
                        $queryStringCity = "SELECT * FROM city ORDER BY name";
                        $bucket = '<select class="form-control" name="city">\n';
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
                    <label for="inputProvince">Province<em class="required">*</em></label>
                      <?php
                      /*Fetching data for the province control*/
                      $queryStringProvince = "SELECT * FROM province ORDER BY name";
                      $cost_bucket ='<select class="form-control" name="inputProvince">\n';
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
                  <p id="errorMessage" class="errorMessage">
                    <?php
                    if (isset($_POST['post'])){
                        if(isset($_POST['inputJobTitle']) && !empty($_POST['inputJobTitle'])){
                          if(isset($_POST['inputOccupationalField']) && !empty($_POST['inputOccupationalField'])){
                            if(isset($_POST['inputEmloymentType']) && !empty($_POST['inputEmloymentType'])){
                              if(isset($_POST['inputWorkExperience']) && !empty($_POST['inputWorkExperience'])){
                               if(isset($_POST['inputSalary']) && !empty($_POST['inputSalary'])){
                                if(isset($_POST['inputLanguage']) && !empty($_POST['inputLanguage'])){
                                  if(isset($_POST['inputSkillAndAbilities']) && !empty($_POST['inputSkillAndAbilities'])){
                                    if(isset($_POST['inputJobDescription']) && !empty($_POST['inputJobDescription'])){
                                        if(isset($_POST['inputCareerField']) && !empty($_POST['inputCareerField'])){
                                          if(isset($_POST['inputQualification']) && !empty($_POST['inputQualification'])){
                                          if(isset($_POST['city']) && !empty($_POST['city'])){
                                            if(isset($_POST['inputProvince']) && !empty($_POST['inputProvince'])){
                                                  $job_title = mysql_fix_string(trim($_POST['inputJobTitle']));
                                                  $qualification = mysql_fix_string(trim($_POST['inputQualification']));
                                                  $Occupational_field =mysql_fix_string(trim($_POST['inputOccupationalField'])) ;
                                                  $employment_type = mysql_fix_string(trim($_POST['inputEmloymentType']));
                                                  $work_experience =mysql_fix_string(trim($_POST['inputWorkExperience'])) ;
                                                  $salary = mysql_fix_string(trim($_POST['inputSalary'])) ;
                                                  $language = mysql_fix_string(trim($_POST['inputLanguage'])) ;
                                                  $skills = mysql_fix_string(trim($_POST['inputSkillAndAbilities'])) ;
                                                  $job_description = mysql_fix_string(trim($_POST['inputJobDescription'])) ;
                                                  $career_field = mysql_fix_string(trim($_POST['inputCareerField'])) ;
                                                  $city = mysql_fix_string(trim($_POST['city']));
                                                  $province = mysql_fix_string(trim($_POST['inputProvince']));
                                                  $job_tag = isset($_POST['inputJobTag'])?mysql_fix_string(trim($_POST['inputJobTag'])):null;
                                                  $url_title = isset($_POST['inputURLTitle'])?mysql_fix_string(trim($_POST['inputURLTitle'])):null;
                                                  $job_url = isset($_POST['inputWebLink'])?mysql_fix_string(trim($_POST['inputWebLink'])):null;
                                                  /*Checking if an image is uploaded*/
                                                  if ($_FILES){
                                                    $upload_errors = array(
                                                        // http://www.php.net/manual/en/features.file-upload.errors.php
                                                        UPLOAD_ERR_OK 				=> "No errors.",
                                                        UPLOAD_ERR_INI_SIZE  	=> "Larger than upload_max_filesize.",
                                                        UPLOAD_ERR_FORM_SIZE 	=> "Larger than form MAX_FILE_SIZE.",
                                                        UPLOAD_ERR_PARTIAL 		=> "Partial upload.",
                                                        UPLOAD_ERR_NO_FILE 		=> "No file.",
                                                        UPLOAD_ERR_NO_TMP_DIR => "No temporary directory.",
                                                        UPLOAD_ERR_CANT_WRITE => "Can't write to disk.",
                                                        UPLOAD_ERR_EXTENSION 	=> "File upload stopped by extension."
                                                    );
                                                    $temp = explode(".", $_FILES["inputJobAttachment"]["name"]);
                                                    $rndstr = round(microtime(true))."resume";
                                                    $newfilename = hash('ripemd128',$rndstr) . '.' . end($temp);
                                                    $temp_content = NULL;
                                                    $content = "";
                                                    if(move_uploaded_file($_FILES["inputJobAttachment"]["tmp_name"], "_job_posts/" . $newfilename)){
                                                      $filename = "_job_posts/" . $newfilename;
                                                      $temp_content = new Filetotext($filename);  //$docObj = new Filetotext("test.pdf");
                                                      $content = $temp_content->convertToText($temp_content);
                                                        if(add_job($con,$recruiter['id'],$job_title,$qualification,$Occupational_field,$employment_type,$work_experience,$salary,$language,$skills,$job_description,$career_field,$job_tag,$url_title,$job_url,$newfilename,$city,$province,$content)){
                                                            location('recruiter_jobs.php');
                                                        }else{
                                                            echo "Insertion failed" .$con->connect_error;
                                                        }
                                                      }else{
                                                          $error = $_FILES['inputJobAttachment']['error'];
                                                          $message = $upload_errors[$error];
                                                          echo $message;
                                                          //echo "Image not successfully uploaded! Upload failed";
                                                      }

                                                }//End of File Upload Code
                                            }else{
                                                echo "Please select required  province <span class='glyphicon glyphicon-remove'></span>";
                                            } //End of province validation checker
                                          }else{
                                              echo "Please select required  city <span class='glyphicon glyphicon-remove'></span>";
                                          } //End of city validation checker
                                        }else{
                                            echo "Please select required Qualification <span class='glyphicon glyphicon-remove'></span>";
                                        } //End of job Qualification validation checker
                                        }else{
                                            echo "Please select a job Category <span class='glyphicon glyphicon-remove'></span>";
                                        } //End of job Category validation checker
                                    }else{
                                        echo "Please enter the required job description <span class='glyphicon glyphicon-remove'></span>";
                                    } //End of skills validation checker
                                  }else{
                                      echo "Please enter the required skills and abilities <span class='glyphicon glyphicon-remove'></span>";
                                  } //End of job description validation checker
                                }else{
                                    echo "Please enter the languages required <span class='glyphicon glyphicon-remove'></span>";
                                } //End of language skills validation checker
                              }else{
                                  echo "Please enter the recuired salaray scale<span class='glyphicon glyphicon-remove'></span>";
                              } //End of language skills validation checker
                              }else{
                                  echo "Please select level of experience <span class='glyphicon glyphicon-remove'></span>";
                              } //End of experience level validation checker
                            }else{
                                echo "please select type of employment <span class='glyphicon glyphicon-remove'></span>";
                            } //End of employment type validation checker
                          }else{
                              echo "Please enter Occupational field <span class='glyphicon glyphicon-remove'></span>";
                          } //End of Occupational field validation checker
                        }else{
                            echo "Please enter job title <span class='glyphicon glyphicon-remove'></span>";
                        } //End of Job title validation checker
                      }
                    ?>
                  </p>
                  <div class="form-group form-inline">
                    <input type="submit" name="post" value="Post Job" class="btn btn-default btn-primary" accesskey="C" />
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
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                