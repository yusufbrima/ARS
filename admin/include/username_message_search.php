<?php
include_once ('class.inc.php');
include('../../include/dbcon.php');
include('../../include/function_library.php');
$query ="SELECT u.username AS username,f.id AS id,f.feedback AS feedback,f.date_sent AS date_received FROM userfeedback AS f LEFT JOIN user AS u ON f.user_id=u.user_id WHERE ";
if(isset($_GET['inputUsername']) && !empty($_GET['inputUsername'])){
    $username= mysql_fix_string(trim($_GET['inputUsername']));
    $query .="MATCH(username) AGAINST('{$username}') ORDER BY f.id";
  }elseif($_GET['inputUsername']==""){
    $query .="1=1 ORDER BY username";
  }
  $result = $con->query($query);
  //echo $query;
  $user_category="";
  $user_table ="<div><table class='table table-striped' border='0'>";
  $user_table .="<caption class='h3'>List of User Feedbacks</caption>";
  $user_table .="<tr><th>username</th><th>Message</th><th>Received Date</th></tr>";
  $num_row 	= mysqli_num_rows($result);
  if($num_row>0){
    for($i=0;$i<$num_row;$i++){
      $row = mysqli_fetch_array($result);
      //print_r($row);
      $result_flag=true;
      $id=$row['id'];
      $received_date = strtotime($row['date_received']);
      $output = date('l jS \of F Y h:i:s A',$received_date);
      $user_table .= "<tr><td>{$row['username']}</td><td>{$row['feedback']}</td><td>{$output}</td><td><a href='#' data-href='user_feedback.php?feed_id=$id' data-toggle='modal' data-target='#delete_feed_modal'><span class='glyphicon glyphicon-trash'></span> Delete</a></td></tr>";
    }
   $user_table .="</table></div>";
   echo $user_table;
  }else{
  echo "<div class='alert alert-danger notification'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><strong>Notification<br /></strong>No record found please try other search criteria</div>";
}
?>
