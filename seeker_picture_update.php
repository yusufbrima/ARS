<?php session_start();
/*authorization of recruiters only to post view page*/
include('include/dbcon.php');
include('include/function_library.php');
if(!isset($_SESSION['seeker_username']) && empty($_SESSION['seeker_username'])){
  if(!isset($_SESSION['user_type_seeker']) && empty($_SESSION['user_type_seeker'])){
    header('location: login.php?warning=Authentication required, you must login first to access this page!');
  }
}else{
  $username=$_SESSION['seeker_username'];
}
if((isset($_GET['seeker_id']) && !empty($_GET['seeker_id'])) && (isset($_GET['user_id']) && !empty($_GET['user_id']))){
  $seeker_id =  mysql_fix_string(trim($_GET['seeker_id']));
  $user_id =  mysql_fix_string(trim($_GET['user_id']));
  $seeker_query = "SELECT profile_url FROM seeker WHERE id='$seeker_id' AND user_id='$user_id' ";
  $seeker_result = $con->query($seeker_query);
  $seeker['profile_url']="";
  $affected_seeker_rows 	= mysqli_num_rows($seeker_result);
  if($affected_seeker_rows>0){
    for($i=0;$i<$affected_seeker_rows;$i++){
      $row = mysqli_fetch_array($seeker_result);
      //print_r($row);
      $seeker['profile_url'] = $row['profile_url'];
     }
  }
}else{
  header('location: seeker_resume_view.php');
}
?>
<?php
    //Checking if the user has confirmed their account
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
              <form method="post" action="<?php outputData($_SERVER['PHP_SELF']); ?>" enctype='multipart/form-data' name="frmPersonalInfo" id="frmPersonalInfo">
                <div class="form-group">
                    <input type="hidden" name="MAX_FILE_SIZE" value="2097152" />
                  <label for="inputProfilePicture">Update your Profile Picture<em class="required">*</em></label>
                  <input class="form-control" type="file" name="inputProfilePicture" id="inputProfilePicture" />
                    <p class="notice">Choose only jpg|png|gif images and file size must not be greater than 2MB</p>
                </div>
                  <p id="errorMessage" class="errorMessage">
                    <?php
                          if(isset($_POST['save'])){
                            //print_r($_FILES);
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
                                    $oldpath = "imageUpload/".$seeker['profile_url'];
                                    if(delete_file($oldpath)){
                                      if(update_seeker_profile_url($con,$seeker_id,$user_id,$newfilename)){
                                        if(move_uploaded_file($_FILES["inputProfilePicture"]["tmp_name"], "imageUpload/" . $newfilename)){
                                              location('seeker_profile_view.php');

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
                    <input type="submit" name="save" value="Update Picture" class="btn btn-default btn-primary" />
                </div>
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
