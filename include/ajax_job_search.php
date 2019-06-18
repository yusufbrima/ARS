<?php
session_start();
include('dbcon.php');
include('function_library.php');
$query ="SELECT j.id,j.title,j.post_date,j.description, r.profile_url,r.organization,r.id,j.url FROM job as j LEFT JOIN recruiter AS r ON j.recruiter_id=r.id";
$filter =" WHERE";
$query_flag = false;
  if(isset($_GET['inputCareerField']) && !empty($_GET['inputCareerField'])){
    $career_field_id= mysql_fix_string(trim($_GET['inputCareerField']));
    $filter .=" career_field_id='$career_field_id' AND ";
    $query_flag = true;
  }
  if(isset($_GET['inputQualification']) && !empty($_GET['inputQualification'])){
    $qualification= mysql_fix_string(trim($_GET['inputQualification']));
    $filter .=" education_level='$qualification' AND ";
    $query_flag = true;
  }
  if(isset($_GET['inputNaturalLanguageSearch']) && !empty($_GET['inputNaturalLanguageSearch'])){
    $title= mysql_fix_string($_GET['inputNaturalLanguageSearch']);
    $filter .=" MATCH(j.title) AGAINST('{$title}') AND ";
    $query_flag = true;
  }
  if(!$query_flag){
    $filter .=" 1=1 ORDER BY j.post_date";
  }else{
    $filter .=" 1=1 ORDER BY j.post_date";
  }
$query_builder = $query.$filter;
//echo $query_builder;
$job_search_result = $con->query($query_builder);
$search_content ='<div class="row advert">';
$affected_job_search_rows 	= mysqli_num_rows($job_search_result);
if($affected_job_search_rows>0){
  $search_content .="<div class='h2'>Search Result: {$affected_job_search_rows} record(s) found</div>";
  for($i=0;$i<$affected_job_search_rows;$i++){
    $row = mysqli_fetch_array($job_search_result);
    //print_r($row);
    $success_flag = true;
    $job['id']=$row[0];
    $recruiter['id']=$row[6];
    $job['title'] =$row['title'];
    $recruiter['profile_url']=$row['profile_url'];
    $recruiter['organization']=$row['organization'];
    $job_link =$row['url'];
    $posted = strtotime($row['post_date']);
    $output = date('l jS \of F Y h:i:s A',$posted);
    $job['description']=$row['description'];
    $path = "imageUpload/".$recruiter['profile_url'];
    $img = "<a class='pull-right' href='job_preview.php?job_id={$job['id']}&recruiter_id={$recruiter['id']}'><img src='{$path}' alt='Profile Picture' class='img-circle search-thumbnail' /></a>";
    $search_content .= "<div class='h4'>{$row['title']}</div>";
    $search_content .= $img;
    $search_content .= "<div class='h6'><a href='{$job_link}' target='_blank'>{$row['organization']}</a></div>";
    $search_content .="<div class='content'><p>{$row['description']}</p><p>Posted on {$output} </p></div>";
    $search_content .="<a href='job_preview.php?job_id={$job['id']}&recruiter_id={$recruiter['id']}'><input type='button' class='btn btn-default btn-primary' value='Read More...' name='read_more'></a>";
  }

}else {
  $search_content .= "<div class='alert alert-danger notification'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><strong>Notification<br /></strong>No record found</div>";
}
  $search_content .="</div>";
  echo $search_content;
?>
