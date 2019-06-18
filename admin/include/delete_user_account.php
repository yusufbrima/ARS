<?php
include('../../include/dbcon.php');
include('../../include/function_library.php');
$profile_path =  "../../imageUpload/";
$user['seeker_id'] = NULL;
$user['recruiter_id'] = NULL;
if(isset($_GET['user_id'])&&!empty($_GET['user_id'])){
  if(isset($_GET['user_type'])&&!empty($_GET['user_type'])){
        $user_type =  mysql_fix_string(trim($_GET['user_type']));
        $user_id =  mysql_fix_string(trim($_GET['user_id']));
      if($user_type=='Recruiter'){
        //Recruiter operations for account de-activation
          if(delete_user($con,$user_id)){
              if(delete_recruiter($con,$user_id)){
                location('../delete_user.php');
              }else{
                location('../delete_user.php');
              }
            }else{
              location('../delete_user.php');
            }
      }elseif ($user_type=='Job Seeker') {
        //Seeker operations for account de-activation
        if(delete_user($con,$user_id)){
          if(delete_seeker($con,$user_id)){
            location('../delete_user.php');
          }else{
            //echo "There was an error deleting account";
            location('../delete_user.php');
          }
        }else{
          //echo "There was an error deleting account";
          location('../delete_user.php');
        }
      }else{
        location('../delete_user.php');
      }
  }else{
    location('../delete_user.php');
  }
}else{
  location('../delete_user.php');
}

?>
