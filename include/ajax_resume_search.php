<?php
session_start();
include('dbcon.php');
include('function_library.php');
$query ="SELECT s.id,s.first_name,s.last_name,s.profile_url,u.join_date,s.sex,s.street,u.user_id FROM seeker AS s LEFT JOIN user AS u ON s.user_id=u.user_id";
$filter =" WHERE";
$query_flag = false;
  if(isset($_GET['city']) && !empty($_GET['city'])){
    $city= mysql_fix_string(trim($_GET['city']));
    $filter .=" s.city='$city' AND ";
    $query_flag = true;
  }
  if(isset($_GET['inputCountry']) && !empty($_GET['inputCountry'])){
    $country= mysql_fix_string(trim($_GET['inputCountry']));
    $filter .=" s.country='$country' AND ";
    $query_flag = true;
  }
  if(isset($_GET['inputFirstName']) && !empty($_GET['inputFirstName'])){
    $first_name= mysql_fix_string(trim($_GET['inputFirstName']));
    $filter .=" MATCH(s.first_name) AGAINST('{$first_name}')";
  }
    if(!$query_flag){
      $filter .=" 1=1 ORDER BY u.join_date";
    }else{
      $filter .=" 1=1 ORDER BY u.join_date";
    }
  $query_builder = $query.$filter;
//  echo $query_builder;
  $resume_result = $con->query($query_builder);
  $content ='<div class="row advert">';
  $affected_resume_rows 	= mysqli_num_rows($resume_result);
  if($affected_resume_rows>0){
   $content .="<div class='h2'>Search Result: {$affected_resume_rows} record(s) found</div>";
    for($i=0;$i<$affected_resume_rows;$i++){
      $row = mysqli_fetch_array($resume_result);
      //print_r($row);
      $flag = true;
      $seeker['id']=$row['id'];
      $seeker['first_name'] =$row['first_name'];
      $seeker['last_name'] = $row['last_name'];
      $seeker['profile_url']=$row['profile_url'];
      $seeker['sex']=$row['sex'];
      if($seeker['sex']=="M"){
        $seeker['sex']="Mr.";
      }elseif ($seeker['sex']=="F") {
        $seeker['sex']="Ms.";
      }else{
        $seeker['sex']="N/A";
      }
      $seeker['street'] =$row['street'];
      $seeker['user_id'] = $row['user_id'];
      $posted = strtotime($row['join_date']);
      $output = date('l jS \of F Y h:i:s A',$posted);
      $path = "imageUpload/".$seeker['profile_url'];
      $img = "<a class='pull-right' href='resume_preview.php?seeker_id={$seeker['id']}&user_id={$seeker['user_id']}'><img src='{$path}' alt='Profile Picture' class='img-circle search-thumbnail' /></a>";
      $content .= "<div class='h4'>{$seeker['sex']}&nbsp;&nbsp; {$seeker['first_name']}&nbsp;&nbsp;{$seeker['last_name']}</div>";
      $content .= $img;
      $content .="<div class='content'><p>{$seeker['street']}</p><p>Being a  member since {$output}</p></div>";
      $content .="<a href='resume_preview.php?seeker_id={$seeker['id']}&user_id={$seeker['user_id']}'><input type='button' class='btn btn-default btn-primary' value='View Profile' name='read_more'></a>";
    }
  }else{
    $content .="<div class='alert alert-danger notification'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><strong>Notification<br /></strong>Search Result: no record(s) found</div>";
  }
  $content .="</div>";
  echo $content;
?>
