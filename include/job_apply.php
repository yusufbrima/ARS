<?php
  session_start();
  include('dbcon.php');
  include('function_library.php');
  $username = "";
  if(!isset($_SESSION['seeker_username']) && empty($_SESSION['seeker_username'])){
    if(!isset($_SESSION['user_type_seeker']) && empty($_SESSION['user_type_seeker'])){
      //header('location: login.php?warning=Authentication required, you must login first to access this page!');
      echo "<span class='errorMessage'>Authentication required, you must login first to access this page!</span> <br /><a href='login.php'>Login</a>";
    }
  }else{
    $username=$_SESSION['seeker_username'];
  }
  if((isset($_GET['recruiter_id'])&& !empty($_GET['recruiter_id'])) && (isset($_GET['job_id'])&& !empty($_GET['job_id']))){
      $user_id = "";
      $seeker_id = "";
    if(!empty($username)){
        $query 		= "SELECT user_id FROM user WHERE username='$username'";
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
                $recruiter_id =  mysql_fix_string(trim($_GET['recruiter_id']));
                $job_id =  mysql_fix_string(trim($_GET['job_id']));
                if(!check_job_application($con,$seeker_id,$job_id,$recruiter_id)){
                  $error = "<div class='alert alert-danger noprint'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
                  $error .= "<strong>Application exist<br /></strong>{$username} you have already applied for this job</div>";
                  echo $error;
                }else{
                  if(job_apply($con,$seeker_id,$recruiter_id,$job_id)){
                      $result = "<div class='alert alert-success noprint'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
                      $result .= "<strong>Success!<br /></strong>Application sent successfully</div>";
                      echo $result;
                    }else{
                      $error = "<div class='alert alert-danger noprint'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
                      $error .= "<strong>Application Error</br /></strong>Application sending failed...</div>";
                      echo $error;
                    }
                }

            }else{
              $error = "<div class='alert alert-danger noprint'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
              $error .= "<strong>Application Error</br /></strong>Please Edit your profile before applying for any jobs...</div>";
              echo $error;
            }
        }

    }
  }
?>
