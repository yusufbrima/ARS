<?php
session_start();
include('dbcon.php');
include('function_library.php');
$query ="SELECT j.id,j.title,c.name,j.occupational_field,j.attachment_URL,j.post_date FROM job AS j LEFT JOIN job_category AS c ON j.career_field_id=c.id";
$filter =" WHERE";
$query_flag = false;
$recruiter_id = $_SESSION['recruiter_id'];
if(isset($_GET['inputCareerField']) && !empty($_GET['inputCareerField'])){
  $career_field_id= mysql_fix_string(trim($_GET['inputCareerField']));
  $filter .=" career_field_id='$career_field_id' AND ";
  $query_flag = true;
}
if(isset($_GET['inputEmloymentType']) && !empty($_GET['inputEmloymentType'])){
  $employment_type= mysql_fix_string(trim($_GET['inputEmloymentType']));
  $filter .=" employment_type='$employment_type' AND ";
  $query_flag = true;
}
if(isset($_GET['inputQualification']) && !empty($_GET['inputQualification'])){
  $qualification= mysql_fix_string(trim($_GET['inputQualification']));
  $filter .=" education_level='$qualification' AND ";
  $query_flag = true;
}
if(!$query_flag){
  $filter .=" j.recruiter_id='$recruiter_id'";
}else{
  $filter .=" j.recruiter_id='$recruiter_id'";
}
$query_builder = $query.$filter;
$result = $con->query($query_builder);
$recruiter_job_search = "";
$recruiter_job_search ="<div><table class='table table-striped' border='0'>";
$recruiter_job_search .="<caption class='h3'>Search Result <br /><a href='recruiter_job_posting.php' class='add_job'><span class='glyphicon glyphicon-plus'></span> Add Job</a></caption>";
$recruiter_job_search .="<tr><th>Job Title</th><th>Job Category</th><th>Occupational Field</th><th>Job Document</th><th>Posted date</th></tr>";
$num_row 	= mysqli_num_rows($result);
if($num_row>0){
  for($i=0;$i<$num_row;$i++){
    $row = mysqli_fetch_array($result);
    $recruiter['title'] = $row['title'];
    $recruiter['job_name']= $row['name'];
    $recruiter['occupational_field']= $row['occupational_field'];
    $recruiter['job_id'] = $row['id'];
    $recruiter['attachment_url']=$row['attachment_URL'];
    $recruiter['posted_date'] =$row['post_date'];
    //print_r($row);
    $search_flag = true;
    $link ="_job_posts/".$recruiter['attachment_url'];
    $recruiter['id']=$recruiter_id;
    $job_document = "<a href='{$link}'><span class='glyphicon glyphicon-file'></span></a>";
    $recruiter_job_search .="<tr><td>{$recruiter['title']}</td><td>{$recruiter['job_name']}</td><td>{$recruiter['occupational_field']}</td><td>{$job_document}</td><td>{$recruiter['posted_date'] } &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='recruiter_job_details.php?job_id={$recruiter['job_id']}&recruiter_id={$recruiter['id']}'><span class='glyphicon glyphicon-eye-open'></span></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href='recruiter_job_update.php?job_id={$recruiter['job_id']}&recruiter_id={$recruiter['id']}'><span class='glyphicon glyphicon-edit'></span></a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href='#' data-toggle='modal' data-href='recruiter_jobs.php?job_id={$recruiter['job_id']}&recruiter_id={$recruiter['id']}&link={$link}' data-target='#recruiter_job_delete'><span class='glyphicon glyphicon-trash'></span></td></tr>";
 }//End of For loop
}else{
  $recruiter_job_search .="<tr colspan='5'><td><span class='h4'>No Record found, please use other filter options</h1></td></tr>";
}//End of record checking condition
$recruiter_job_search .="</table></div>";
echo $recruiter_job_search
?>
