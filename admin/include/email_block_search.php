<?php
include_once ('class.inc.php');
include('../../include/dbcon.php');
include('../../include/function_library.php');
$query ="SELECT user_id,username,email,join_date,user_type FROM user WHERE ";
if(isset($_GET['inputBlockEmail']) && !empty($_GET['inputBlockEmail'])){
    $email= mysql_fix_string(trim($_GET['inputBlockEmail']));
    $query .="MATCH(email) AGAINST('{$email}') ORDER BY username";
  }elseif($_GET['inputBlockEmail']==""){
    $query .="1=1 ORDER BY email";
  }
  $result = $con->query($query);
  $user_category="";
  $num_row 	= mysqli_num_rows($result);
  if($num_row>0){
    $user_table ="<div><table class='table table-striped' border='0'>";
    $user_table .="<caption class='h3'>Search result {$num_row} records found</caption>";
    $user_table .="<tr><th>username</th><th>Email</th><th>Join Date</th><th>Account Type</th></tr>";
    for($i=0;$i<$num_row;$i++){
      $row = mysqli_fetch_array($result);
      //print_r($row);
      $result_flag=true;
      $id=$row['user_id'];
      if($row['user_type']==0){
        $user_category="Recruiter";
      }elseif ($row['user_type']==1) {
        $user_category="Job Seeker";
      }
      $join_date = strtotime($row['join_date']);
      $output = date('l jS \of F Y h:i:s A',$join_date);
      $user_table .= "<tr><td>{$row['username']}</td><td>{$row['email']}</td><td>{$output}</td><td>{$user_category}&nbsp;&nbsp;&nbsp;<a href='#' data-href='block_user.php?block_user_id={$id}' data-toggle='modal' data-target='#block_user_modal'><span class='glyphicon glyphicon-trash'></span> Block</a>&nbsp;&nbsp;&nbsp;<a href='#' data-href='block_user.php?unblock_user_id={$id}' data-toggle='modal' data-target='#unblock_user_modal'><span class='glyphicon glyphicon-trash'></span> Unblock</a></td></tr>";
    }
   $user_table .="</table></div>";
  echo $user_table;
}else{
  echo "<div class='alert alert-danger notification'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><strong>Notification<br /></strong>No record found please try other search criteria</div>";
}
?>
