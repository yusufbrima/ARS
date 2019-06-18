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
 if((isset($_GET['recruiter_id']) && !empty($_GET['recruiter_id'])) && (isset($_GET['user_id']) && !empty($_GET['user_id']))){
   $recruiter_id =  mysql_fix_string(trim($_GET['recruiter_id']));
   $user_id =  mysql_fix_string(trim($_GET['user_id']));
   $recruiter_query = "SELECT profile_url FROM recruiter WHERE id='$recruiter_id' AND user_id='$user_id' ";
   $recruiter_result = $con->query($recruiter_query);
   $recruiter['profile_url']="";
   $affected_recruiter_rows 	= mysqli_num_rows($recruiter_result);
   if($affected_recruiter_rows>0){
     for($i=0;$i<$affected_recruiter_rows;$i++){
       $row = mysqli_fetch_array($recruiter_result);
       //print_r($row);
       $recruiter['profile_url'] = $row['profile_url'];
      }
   }
 }else{
   header('location: seeker_resume_view.php');
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
               <li><a href="recruiter_jobs.php"><span class="glyphicon glyphicon-briefcase"></span> My Jobs <span class="badge">5</span> <span class="badge"><?php echo $job_count; ?></span></a></li>
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
                   <div class="form-group">
                       <input type="hidden" name="MAX_FILE_SIZE" value="2097152" />
                     <label for="inputProfilePicture">Organization Logo<em class="required">*</em></label>
                     <input class="form-control" type="file" name="inputProfilePicture" id="inputProfilePicture" />
                       <p class="notice">Choose only jpg|png|gif images and file size must not be greater than 2MB</p>
                   </div>
                   <p id="errorMessage" class="errorMessage">
                       <?php
                       if (isset($_POST['register'])){
                         //echo $recruiter['profile_url'];
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
                             switch($_FILES['inputProfilePicture']['type'])
                             {
                                 case 'image/jpeg': $ext = 'jpg'; break;
                                 case 'image/jpg': $ext = 'jpg'; break;
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
                                 $oldpath = "imageUpload/".$recruiter['profile_url'];
                                 if(delete_file($oldpath)){
                                   if(update_recuiter_profile_url($con,$recruiter_id,$user_id,$newfilename)){
                                     if(move_uploaded_file($_FILES["inputProfilePicture"]["tmp_name"], "imageUpload/" . $newfilename)){
                                           location('recruiter_profile_view.php');

                                     }else{
                                         //$error = $_FILES['img']['error'];
                                         //$message = $upload_errors[$error];
                                         // echo $message;
                                         echo "Image not successfully uploaded! Upload failed";
                                       }
                                   }
                                 }//End of file delete function
                               }
                           }else{
                             echo "No file selected";
                           }
                       }
                       ?>
                   </p>
                   <div class="form-group form-inline">
                     <input type="submit" name="register" value="Update Profile Picture" class="btn btn-default btn-primary" accesskey="C" />
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
