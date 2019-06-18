<?php
include('dbcon.php');
include('function_library.php');

if(isset($_GET['seeker_id']) && !empty($_GET['seeker_id'])){
$flag  =  mysql_fix_string(trim($_GET['flag']));
$seeker_id  =  mysql_fix_string(trim($_GET['seeker_id']));

if(isset($_GET['flag']) && $_GET['flag']==1){ //Code to Subscribe to newsletter
  if(update_newsletter($con,$flag,$seeker_id)){
    echo "<em style='color:green;'>Your have successfully subscribed for job alert <span class='glyphicon glyphicon-ok'></span></em>";
  }
}else{
  if(update_newsletter($con,$flag,$seeker_id)){
      echo "<em style='color:red;'>Your have successfully unsubscribed from job alert <span class='glyphicon glyphicon-warinig'></span></em>";
  }
}
}

  function update_newsletter($con,$flag,$seeker_id){
    $query = "UPDATE seeker SET job_alert={$flag} WHERE id={$seeker_id}";
    $result = $con->query($query);
    if (!$result) {
      die($con->connect_error);
    }else {
      return true;
    }
  }
?>
