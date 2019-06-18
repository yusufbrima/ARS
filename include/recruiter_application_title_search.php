<?php
include('dbcon.php');
include('function_library.php');
$query ="SELECT a.id,s.first_name,s.user_id,s.last_name,s.sex,s.id AS seeker_id,j.id AS job_id,r.id AS recruiter_id,j.title,a.applied_date FROM application AS a LEFT JOIN recruiter AS r ON a.recruiter_id=r.id LEFT JOIN seeker AS s ON a.seeker_id=s.id LEFT JOIN job AS j ON a.job_id=j.id WHERE ";
$r_id = isset($_GET['inputRecruiterID'])?$_GET['inputRecruiterID']:NULL;
if(isset($_GET['inputJobTitle']) && !empty($_GET['inputJobTitle'])){
    $title= mysql_fix_string(trim($_GET['inputJobTitle']));
    $query .=" MATCH(j.title) AGAINST('{$title}') AND a.recruiter_id='$r_id'";
  }elseif($_GET['inputJobTitle']==""){
    $query .=" a.recruiter_id='$r_id'";
  }
$result = $con->query($query);
//echo $query;
$recruiter_application = "";
$recruiter_application ="<div><table class='table table-striped' border='0'>";
$num_row 	= mysqli_num_rows($result);
if($num_row>0){
  $recruiter_application .="<caption class='h3'>Search Result {$num_row} records found</caption>";
  $recruiter_application .="<tr><th>Job Title</th><th>Received From</th><th>Received Date</th></tr>";
  for($i=0;$i<$num_row;$i++){
    $row = mysqli_fetch_array($result);
    $recruiter['title'] = $row['title'];
    $recruiter['job_id'] = $row['job_id'];
    $recruiter['seeker_first_name']= $row['first_name'];
    $recruiter['seeker_last_name']= $row['last_name'];
    $applied = strtotime($row['applied_date']);
    $recruiter['seeker_id'] = $row['seeker_id'];
    $recruiter['seeker_user_id'] = $row['user_id'];
    $recruiter['sex'] = $row['sex'];
    $output = date('l jS \of F Y h:i:s A',$applied);
    //print_r($row);
    if($recruiter['sex']=="M"){
      $recruiter['sex']="Mr.";
    }elseif ($recruiter['sex']=="F") {
      $recruiter['sex']="Ms.";
    }else{
      $recruiter['sex']="N/A";
    }
    $full_name = $recruiter['sex']." ".$recruiter['seeker_first_name']." ".$recruiter['seeker_last_name'];
    $flag = true;
    $recruiter_application .="<tr><td>{$recruiter['title']}</td><td>{$full_name}</td><td>{$output}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='resume_preview.php?seeker_id={$recruiter['seeker_id']}&user_id={$recruiter['seeker_user_id']}'><span class='glyphicon glyphicon-eye-open'></span></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href='#' data-href='recruiter_applications.php?job_id={$recruiter['job_id']}&recruiter_id={$r_id}' data-toggle='modal' data-target='#recruiter_application_delete'><span class='glyphicon glyphicon-trash'></span></td></tr>";
 }
  $recruiter_application .="</table></div>";
  echo $recruiter_application;
}else{
  echo "<div class='alert alert-danger notification'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><strong>Notification<br /></strong>No record found please try other search criteria</div>";
}
?>
