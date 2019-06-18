<?php
include_once ('class.inc.php');
include('../../include/dbcon.php');
include('../../include/function_library.php');
$query ="SELECT user_id,username,email,join_date,user_type FROM user WHERE ";
if(isset($_GET['inputDeleteEmail']) && !empty($_GET['inputDeleteEmail'])){
    $email= mysql_fix_string(trim($_GET['inputDeleteEmail']));
    $query .="MATCH(email) AGAINST('{$email}') ORDER BY username";
  }elseif($_GET['inputDeleteEmail']==""){
    $query .="1=1 ORDER BY email";
  }
  $result = $con->query($query);
  $user_category="";
  $num_row 	= mysqli_num_rows($result);
  if($num_row>0){
    $delete_user_table ="<div><table class='table table-striped' border='0'>";
    $delete_user_table .="<caption class='h3'>Search by Email Result {$num_row} records found</caption>";
    $delete_user_table .="<tr><th>username</th><th>Email</th><th>Join Date</th><th>Account Type</th></tr>";
    for($i=0;$i<$num_row;$i++){
      $row = mysqli_fetch_array($result);
      //print_r($row);
      $delete_result_flag=true;
      $id=$row['user_id'];
      if($row['user_type']==0){
        $user_category="Recruiter";
      }elseif ($row['user_type']==1) {
        $user_category="Job Seeker";
      }
      $join_date = strtotime($row['join_date']);
      $output = date('l jS \of F Y h:i:s A',$join_date);
      $flag_data =  hash('ripemd128','my_dommy_data');
      $return_flag =  substr($flag_data,0,17);
      $delete_user_table .= "<tr><td>{$row['username']}</td><td>{$row['email']}</td><td>{$output}</td><td>{$user_category}&nbsp;&nbsp;&nbsp;<a href='#' data-href='include/delete_user_account.php?user_id={$id}&user_type={$user_category}&flag={$return_flag}' data-toggle='modal' data-target='#delete_user_modal'><span class='glyphicon glyphicon-trash'></span> Delete</a></td></tr>";
    }
   $delete_user_table .="</table></div>";
   echo $delete_user_table;
}else{
  echo "<div class='alert alert-danger notification'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><strong>Notification<br /></strong>No record found please try other search criteria</div>";
}
?>
