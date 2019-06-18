<?php
include('dbcon.php');
include('function_library.php');
$query ="SELECT a.id,a.recruiter_id,r.organization,s.id AS seeker_id,j.id AS job_id,j.title,a.applied_date FROM application AS a LEFT JOIN recruiter AS r ON a.recruiter_id=r.id LEFT JOIN seeker AS s ON a.seeker_id=s.id LEFT JOIN job AS j ON a.job_id=j.id WHERE trashed=0 AND";
$s_id = isset($_GET['inputSeekerID'])?$_GET['inputSeekerID']:NULL;
$filter;
if(isset($_GET['inputJobTitle']) && !empty($_GET['inputJobTitle'])){
    $title= mysql_fix_string(trim($_GET['inputJobTitle']));
    $query .=" MATCH(j.title) AGAINST('{$title}') AND a.seeker_id='$s_id'";
  }elseif($_GET['inputJobTitle']==""){
    $query .=" a.seeker_id='$s_id'";
  }
if($con->query($query)){
  $result = $con->query($query);
  //echo $query;
  $seeker_application = "";
  $seeker_application ="<div><table class='table table-striped' border='0'>";
  $num_row 	= mysqli_num_rows($result);
  if($num_row>0){
    $seeker_application .="<caption class='h3'>Search Result {$num_row} records found</caption>";
    $seeker_application .="<tr><th>Job Title</th><th>Oraganization Name</th><th>Sent Date</th></tr>";
    for($i=0;$i<$num_row;$i++){
      $row = mysqli_fetch_array($result);
      $seeker['title'] = $row['title'];
      $seeker['job_id'] = $row['job_id'];
      $seeker['organization']= $row['organization'];
      $applied = strtotime($row['applied_date']);
      $recruiter_id = $row['recruiter_id'];
      $output = date('l jS \of F Y h:i:s A',$applied);
      $seeker_application .="<tr><td>{$seeker['title']}</td><td>{$seeker['organization']}</td><td>{$output}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href='job_preview.php?job_id={$seeker['job_id']}&recruiter_id={$recruiter_id}'><span class='glyphicon glyphicon-eye-open'></span></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href='#' data-href='seeker_applications.php?job_id={$seeker['job_id']}&seeker_id={$s_id}' data-toggle='modal' data-target='#seeker_application_delete'><span class='glyphicon glyphicon-trash'></span></td></tr>";
   }
   $seeker_application .="</table></div>";
    echo $seeker_application;
  }else{
    echo "<div class='alert alert-danger notification'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><strong>Notification<br /></strong>No record found please try other search criteria</div>";
  }
}else{
  echo "<div class='alert alert-danger notification'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><strong>Notification<br /></strong>No record found please try other search criteria</div>";
}
?>
