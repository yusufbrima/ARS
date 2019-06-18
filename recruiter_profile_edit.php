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
              <li><a href="recruiter_jobs.php"><span class="glyphicon glyphicon-briefcase"></span> My Jobs <span class="badge">5</span> <span class="badge"><?php echo $job_count;?></span></a></li>
              <li><a href="recruiter_applications.php"><span class="glyphicon glyphicon-envelope"></span> Recieved Applications  <span class="badge">5</span><span class="badge"><?php echo $application_count; ?></span></a></li>
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
       <!--Div for the Password Reset Code Canvas-->
       <div class="container">
          <div class="col-md-6">
            <form method="post" action="<?php outputData($_SERVER['PHP_SELF']); ?>" enctype='multipart/form-data' name="frmRecruiterPersnalInfo" id="frmRecruiterPersnalInfo">
              <fieldset>
                <legend class="legend">Personal Info</legend>
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
                          placeholder="Enter the name of your Organization"  />
                    </div>
                    <div class="form-group">
                      <label for="inputTelephone">Mobile<em class="required">*</em></label>
                      <input class="form-control" type="tel" value="<?php if(isset($_POST['inputTelephone'])){echo outputData($_POST['inputTelephone']); }?>" name="inputTelephone" id="inputTelephone"
                        placeholder="Phone Number"  />
                    </div>
                  <div class="form-group">
                      <input type="hidden" name="MAX_FILE_SIZE" value="2097152" />
                    <label for="inputProfilePicture">Organization Logo<em class="required">*</em></label>
                    <input class="form-control" type="file" name="inputProfilePicture" id="inputProfilePicture" />
                      <p class="notice">Choose only jpg|png|gif images and file size must not be greater than 2MB</p>
                  </div>
                  <div class="form-group">
                        <label for="inputWebLink">Web URL</label>
                        <input class="form-control" type="url" name="inputWebLink" id="inputWebLink" value="<?php if(isset($_POST['inputWebLink'])){echo outputData($_POST['inputWebLink']); }?>"
                          placeholder="Enter web link" >
                  </div>
                  <div class="form-group">
                      <label for="inputStreet">Address<em class="required">*</em></label>
                      <input class="form-control" type="text" value="<?php if(isset($_POST['inputStreet'])){echo outputData($_POST['inputStreet']); }?>" name="inputStreet" id="inputStreet"
                        placeholder="Enter  Street name"  autocomplete="on" />
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
                    <label class="sr-only" for="inputProvince">Province<em class="required">*</em></label>
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
                  <div class="form-group">
                    <label for="inputCountry">Country<em class="required">*</em></label>
                      <?php
                      /*Fetching data for the province control*/
                      $queryStringCountry = "SELECT * FROM country ORDER BY nicename";
                      $outputCountry ='<select class="form-control" name="inputCountry">\n<option value="">--Select Country--</option>';
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
                  </div>
                  <p id="errorMessage" class="errorMessage">
                      <?php
                      if (isset($_POST['register'])){
                          if(isset($_POST['inputFirstName']) && !empty($_POST['inputFirstName'])){
                            if(isset($_POST['inputLastName']) && !empty($_POST['inputLastName'])){
                              if(isset($_POST['sex']) && !empty($_POST['sex'])){
                                if(isset($_POST['inputOrganization']) && !empty($_POST['inputOrganization'])){
                                  if(isset($_POST['inputTelephone']) && !empty($_POST['inputTelephone'])){
                                    if(isset($_POST['inputWebLink']) && !empty($_POST['inputWebLink'])){
                                      if(isset($_POST['inputStreet']) && !empty($_POST['inputStreet'])){
                                        if(isset($_POST['city']) && !empty($_POST['city'])){
                                          if(isset($_POST['inputProvince']) && !empty($_POST['inputProvince'])){
                                            if(isset($_POST['inputCountry']) && !empty($_POST['inputCountry'])){
                                              $first_name =mysql_fix_string(trim($_POST['inputFirstName']));
                                              $last_name =mysql_fix_string(trim($_POST['inputLastName']));
                                              $sex =mysql_fix_string(trim($_POST['sex']));
                                              $organization =mysql_fix_string(trim($_POST['inputOrganization']));
                                              $telephone =mysql_fix_string(trim($_POST['inputTelephone']));
                                              $web_link =mysql_fix_string(trim($_POST['inputWebLink']));
                                              $city =mysql_fix_string(trim($_POST['city']));
                                              $province =mysql_fix_string(trim($_POST['inputProvince']));
                                              $country =mysql_fix_string(trim($_POST['inputCountry']));
                                              $street =mysql_fix_string(trim($_POST['inputStreet']));
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
                                                  $name = $_FILES['inputProfilePicture']['name'];
                                                  $size = $_FILES['inputProfilePicture']['size'];
                                                  switch($_FILES['inputProfilePicture']['type'])
                                                  {
                                                      case 'image/jpeg': $ext = 'jpg'; break;
                                                      case 'image/gif': $ext = 'gif'; break;
                                                      case 'image/png': $ext = 'png'; break;
                                                      case 'image/tiff': $ext = 'tif'; break;
                                                      default: $ext = ''; break;
                                                  }
                                                  if($ext ==""){
                                                      echo "File type not supported";
                                                  }else{
                                                      $temp = explode(".", $_FILES["inputProfilePicture"]["name"]);
                                                      $rndstr = round(microtime(true))."data";
                                                      $newfilename = hash('ripemd128',$rndstr) . '.' . end($temp);
                                                      if(add_recruiter_personal_info($con,$user_id,$first_name,$last_name,$telephone,$sex,$web_link,$organization,$newfilename,$street,$city,$province,$country)){
                                                          //$con,$user_id,$first_name,$last_name,$moible,$marital_status,$dob,$profile_url="",$sex,$street,$city,$province,$country

                                                           if(move_uploaded_file($_FILES["inputProfilePicture"]["tmp_name"], "imageUpload/" . $newfilename)){
                                                                 location('recruiter_profile_view.php');

                                                           }else{
                                                               echo "Image not successfully uploaded! Upload failed";
                                                           }
                                                       }//End of data insertion code
                                                       else{
                                                           echo "Insertion failed" .$con->connect_error;
                                                       }
                                                   }
                                               }//End of File Upload Code
                                            }else{
                                              echo "Please select Country<span class='glyphicon glyphicon-remove'></span>";
                                            }
                                          }else{
                                            echo "Please select your Province<span class='glyphicon glyphicon-remove'></span>";
                                          }
                                        }else{
                                          echo "Please select your city<span class='glyphicon glyphicon-remove'></span>";
                                        }
                                      }else{
                                        echo "Please enter your organization address<span class='glyphicon glyphicon-remove'></span>";
                                      }
                                    }else{
                                      echo "Please enter your company web link<span class='glyphicon glyphicon-remove'></span>";
                                    }
                                  }else{
                                    echo "Please enter your contact number<span class='glyphicon glyphicon-remove'></span>";
                                  }
                                }else{
                                  echo "Please enter the name of your organization<span class='glyphicon glyphicon-remove'></span>";
                                }
                              }else{
                                echo "Please select your sex <span class='glyphicon glyphicon-remove'></span>";
                              }
                            }else{
                                echo "Please enter last name <span class='glyphicon glyphicon-remove'></span>";
                            } //End of last name validation checker
                          }else{
                              echo "Please enter first name <span class='glyphicon glyphicon-remove'></span>";
                          } //End of first name validation checker
                      }
                      ?>
                  </p>
                  <div class="form-group form-inline">
                    <input type="submit" name="register" value="Continue" class="btn btn-default btn-primary" accesskey="C" />
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
