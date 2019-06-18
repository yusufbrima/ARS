<?php
include_once ('class.inc.php');
include('../../include/dbcon.php');
include('../../include/function_library.php');
$query ="SELECT user_id,username,email,join_date,user_type FROM user WHERE ";
if(isset($_GET['inputBlockUsername']) && !empty($_GET['inputBlockUsername'])){
    $username= mysql_fix_string(trim($_GET['inputBlockUsername']));
    $query .="MATCH(username) AGAINST('{$username}') ORDER BY username";
  }elseif($_GET['inputBlockUsername']==""){
    $query .="1=1 ORDER BY username";
  }
  $result = $con->query($query);
  $user_category="";
  $num_row 	= mysqli_num_rows($result);
  if($num_row>0){
    $user_table ="<div><table class='table table-striped' border='0'>";
    $user_table .="<caption class='h3'>Search Result {$num_row} records found</caption>";
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
      $join_date = strtotim