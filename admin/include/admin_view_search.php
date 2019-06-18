<?php
include_once ('class.inc.php');
include('../../include/dbcon.php');
include('../../include/function_library.php');
$query ="SELECT user_id,username,join_date FROM admin WHERE ";
if(isset($_GET['inputUsername']) && !empty($_GET['inputUsername'])){
    $username= mysql_fix_string(trim($_GET['inputUsername']));
    $query .="MATCH(username) AGAINST('{$username}') ORDER BY username";
  }elseif($_GET['inputUsername']==""){
    $query .="1=1 ORDER BY username";
  }
  $result = $con->query($query);
  $num_row 	= mysqli_num_rows($result);
  if($num_row>0){
    $admin_table ="<div><table class='table table-striped' border='0'>";
    $admin_table .="<caption class='h3'>Search Result {$num_row} records found</caption>";
    $admin_table .="<tr><th>username</th><th>Join Date</th></tr>";
    for($i=0;$i<$num_row;$i++){
      $row = mysqli_fetch_array($result);
      //print_r($row);
      $id = $row['user_id'];
      $admin_result_flag=true;
      $join_date = strtotime($row['join_date']);
      $output = date('l jS \of F Y h:i:s A',$join_date);
      $admin_table .= "<tr><td>{$row['username']}</td><td>{$output}&nbsp;&nbsp;&nbsp;<a href='#' data-href='view_admins.php?admin_id={$id}' data-toggle='modal' data-target='#delete_admin'><span class='glyphicon glyphicon-trash'></span> Delete</a></td></tr>";
    }
   $admin_table .="</table></div>";
   echo $admin_table;
}else{
  echo "<div class='alert alert-danger notification'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><strong>Notification<br /></strong>No record found please try other search criteria</div>";
}
?>
