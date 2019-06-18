<?php session_start();
/*authorization of recruiters only to post view page*/
if(!isset($_SESSION['seeker_username']) && empty($_SESSION['seeker_username'])){
  if(!isset($_SESSION['user_type_seeker']) && empty($_SESSION['user_type_seeker'])){
    header('location: login.php?warning=Authentication required, you must login first to access this page!');
  }
}else{
  $username=$_SESSION['seeker_username'];
}
?>
<?php
include('include/dbcon.php');
include('include/function_library.php');
$flag = false;
$s_id="";
$job_count=0;
if(!empty($username)){
    $query 		= "SELECT * FROM user WHERE username='$username'";
    $result = $con->query($query);
    $row		= mysqli_fetch_array($result);//Fetching of user data from the database into the row array
    $num_row 	= mysqli_num_rows($result);
    if ($num_row  <> 0){
        $user_id = $row['user_id'];
        $query_seeker = "SELECT id FROM seeker  WHERE user_id='$user_id'";
        $result_seeker = $con->query($query_seeker);
        $dataset = mysqli_fetch_array($result_seeker);//Fetching of user data from the database into the row array
        $num_row_affected 	= mysqli_num_rows($result_seeker);
        if ($num_row_affected  == 1){
            $s_id = $dataset['id'];
        }
      $job_query = "SELECT COUNT(*) AS count FROM application WHERE seeker_id={$s_id} AND trashed=0";
      $job_result = $con->query($job_query);
      $affected_count_rows 	= mysqli_num_rows($job_result);
      if($affected_count_rows>0){
        for($i=0;$i<$affected_count_rows;$i++){
          $dataset = mysqli_fetch_array($job_result);
          $job_count = $dataset['count'];
        }
      }
    }
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Welcome to E-JobFinder: One-stop-shop for employment</title>
    <?php include_once "header.php"; ?>
  </head>
  <body>
      <nav class="navbar navbar-inverse navbar-fixed-top">
       <div class="container-fluid">
         <div class="navbar-header">
           <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
           <a class="navbar-brand" href="index.php"><img src="images/e-jobFinder.png" alt="Logo"/></a>
         </div>
          <div class="collapse navbar-collapse" id="myNavbar">
         <ul class="nav navbar-nav navbar-right">
           <li><a href="seeker_dashboard.php">Welcome <?php echo $username; ?></a></li>
           <li role="presentation"><a href="jobSearch.php"><span class="glyphicon glyphicon-search"></span> Job Search</a></li>
           <li class="dropdown active">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-user"></span> My Account
            <span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li><a href="seeker_profile_view.php"><span class="glyphicon glyphicon-user"></span> My Profile</a></li>
              <li><a href="seeker_applications.php"><span class="glyphicon glyphicon-briefcase"></span> My Applications <span class="badge"><?php echo $job_count; ?></span></a></li>
              <li><a href="seeker_resume_view.php"><span class="glyphicon glyphicon-envelope"></span> My Resume</a></li>
              <li><a href="seeker_settings.php"><span class="glyphicon glyphicon-wrench"></span> Settings</a></li>
              <li><a href="seeker_job_preference_view.php"><span class="glyphicon glyphicon-wrench"></span>Job Prefrences</a></li>
            </ul>
          </li>
           <li><a href="logout.php"><span class="glyphicon glyphicon-off"></span> Logout</a></li>
         </ul>
       </div>
        </div>
     </nav>
     <div class="menu-spacer"></div>
     <!--Clearing of the float rule-->
       <div class="clear-fix">
       </div>
       <!--Div for the Password Reset Code Canvas-->
       <div class="container">
         <?php
            if(isset($_GET['flag']) && !empty($_GET['flag'])){
              $flag =  mysql_fix_string(trim($_GET['flag']));

              switch ($flag) {
                case 1://organization filter
                  if(isset($_GET['r_id']) && !empty($_GET['r_id'])){
                    $r_id=mysql_fix_string(trim($_GET['r_id']));
                    $query ="SELECT j.id AS job_id,j.title,j.description,j.post_date, r.profile_url,r.organization,r.id AS recruiter_id,j.url FROM job as j LEFT JOIN recruiter AS r ON j.recruiter_id=r.id WHERE j.recruiter_id={$r_id} ORDER BY j.post_date";
                    $job_result = $con->query($query);
                    $content ='<div class="row advert">';
                    $content .="<div class='h2'>Search Filter By Organization</div>";
                    $affected_job_rows 	= mysqli_num_rows($job_result);
                    if($affected_job_rows>0){
                      for($i=0;$i<$affected_job_rows;$i++){
                        $row = mysqli_fetch_array($job_result);
                        //print_r($row);
                        $flag = true;
                        $job['id']=$row['job_id'];
                        $recruiter['id']=$row['recruiter_id'];
                        $job['title'] =$row['title'];
                        $recruiter['profile_url']=$row['profile_url'];
                        $recruiter['organization']=$row['organization'];
                        $job_link =$row['url'];
                        $posted = strtotime($row['post_date']);
                        $output = date('l jS \of F Y h:i:s A',$posted);
                        $job['description']=$row['description'];
                        $path = "imageUpload/".$recruiter['profile_url'];
                        $img = "<a class='pull-right' href='job_preview.php?job_id={$job['id']}&recruiter_id={$recruiter['id']}'><img src='{$path}' alt='Profile Picture' class='img-circle search-thumbnail' /></a>";
                        $content .= "<div class='h4'>{$row['title']}</div>";
                        $content .= $img;
                        $content .= "<div class='h6'><a href='{$job_link}' target='_blank'>{$row['organization']}</a></div>";
                        $content .="<div class='content'><p>{$row['description']}</p><p>Posted on {$output}</p></div>";
                        $content .="<a href='job_preview.php?job_id={$job['id']}&recruiter_id={$recruiter['id']}'><input type='button' class='btn btn-default btn-primary' value='Read More...' name='read_more'></a>";
                      }
                      $content .="</div>";
                    }
                    echo $content;
                  }
                  break;
                case 2://province filter
                    if(isset($_GET['p_id']) && !empty($_GET['p_id'])){
                      $p_id=mysql_fix_string(trim($_GET['p_id']));
                      $query ="SELECT j.id AS job_id,j.title,j.description,j.post_date, r.profile_url,r.organization,r.id AS recruiter_id,j.url FROM job as j LEFT JOIN recruiter AS r ON j.recruiter_id=r.id WHERE j.province={$p_id} ORDER BY j.post_date";
                      $job_result = $con->query($query);
                      $content ='<div class="row advert">';
                      $content .="<div class='h2'>Search Filter By Region</div>";
                      $affected_job_rows 	= mysqli_num_rows($job_result);
                      if($affected_job_rows>0){
                        for($i=0;$i<$affected_job_rows;$i++){
                          $row = mysqli_fetch_array($job_result);
                          //print_r($row);
                          $flag = true;
                          $job['id']=$row['job_id'];
                          $recruiter['id']=$row['recruiter_id'];
                          $job['title'] =$row['title'];
                          $recruiter['profile_url']=$row['profile_url'];
                          $recruiter['organization']=$row['organization'];
                          $job_link =$row['url'];
                          $posted = strtotime($row['post_date']);
                          $output = date('l jS \of F Y h:i:s A',$posted);
                          $job['description']=$row['description'];
                          $path = "imageUpload/".$recruiter['profile_url'];
                          $img = "<a class='pull-right' href='job_preview.php?job_id={$job['id']}&recruiter_id={$recruiter['id']}'><img src='{$path}' alt='Profile Picture' class='img-circle search-thumbnail' /></a>";
                          $content .= "<div class='h4'>{$row['title']}</div>";
                          $content .= $img;
                          $content .= "<div class='h6'><a href='{$job_link}' target='_blank'>{$row['organization']}</a></div>";
                          $content .="<div class='content'><p>{$row['description']}</p><p>Posted on {$output}</p></div>";
                          $content .="<a href='job_preview.php?job_id={$job['id']}&recruiter_id={$recruiter['id']}'><input type='button' class='btn btn-default btn-primary' value='Read More...' name='read_more'></a>";
                        }
                        $content .="</div>";
                      }
                      echo $content;
                    }
                  break;
                case 3://city filter
                    if(isset($_GET['c_id']) && !empty($_GET['c_id'])){
                      $c_id=mysql_fix_string(trim($_GET['c_id']));
                      $query ="SELECT j.id AS job_id,j.title,j.description,j.post_date, r.profile_url,r.organization,r.id AS recruiter_id,j.url FROM job as j LEFT JOIN recruiter AS r ON j.recruiter_id=r.id WHERE j.city={$c_id} ORDER BY j.post_date";
                      $job_result = $con->query($query);
                      $content ='<div class="row advert">';
                      $content .="<div class='h2'>Search Filter By City</div>";
                      $affected_job_rows 	= mysqli_num_rows($job_result);
                      if($affected_job_rows>0){
                        for($i=0;$i<$affected_job_rows;$i++){
                          $row = mysqli_fetch_array($job_result);
                          //print_r($row);
                          $flag = true;
                          $job['id']=$row['job_id'];
                          $recruiter['id']=$row['recruiter_id'];
                          $job['title'] =$row['title'];
                          $recruiter['profile_url']=$row['profile_url'];
                          $recruiter['organization']=$row['organization'];
                          $job_link =$row['url'];
                          $posted = strtotime($row['post_date']);
                          $output = date('l jS \of F Y h:i:s A',$posted);
                          $job['description']=$row['description'];
                          $path = "imageUpload/".$recruiter['profile_url'];
                          $img = "<a class='pull-right' href='job_preview.php?job_id={$job['id']}&recruiter_id={$recruiter['id']}'><img src='{$path}' alt='Profile Picture' class='img-circle search-thumbnail' /></a>";
                          $content .= "<div class='h4'>{$row['title']}</div>";
                          $content .= $img;
                          $content .= "<div class='h6'><a href='{$job_link}' target='_blank'>{$row['organization']}</a></div>";
                          $content .="<div class='content'><p>{$row['description']}</p><p>Posted on {$output}</p></div>";
                          $content .="<a href='job_preview.php?job_id={$job['id']}&recruiter_id={$recruiter['id']}'><input type='button' class='btn btn-default btn-primary' value='Read More...' name='read_more'></a>";
                        }
                        $content .="</div>";
                      }
                      echo $content;
                    }
                  break;
                case 4://job Category filter
                    if(isset($_GET['j_id']) && !empty($_GET['j_id'])){
                      $j_id=mysql_fix_string(trim($_GET['j_id']));
                      $query ="SELECT j.id AS job_id,j.title,j.description,j.post_date, r.profile_url,r.organization,r.id AS recruiter_id,j.url FROM job as j LEFT JOIN recruiter AS r ON j.recruiter_id=r.id WHERE j.career_field_id={$j_id} ORDER BY j.post_date";
                      $job_result = $con->query($query);
                      $content ='<div class="row advert">';
                      $content .="<div class='h2'>Search Filter By Job Category</div>";
                      $affected_job_rows 	= mysqli_num_rows($job_result);
                      if($affected_job_rows>0){
                        for($i=0;$i<$affected_job_rows;$i++){
                          $row = mysqli_fetch_array($job_result);
                          //print_r($row);
                          $flag = true;
                          $job['id']=$row['job_id'];
                          $recruiter['id']=$row['recruiter_id'];
                          $job['title'] =$row['title'];
                          $recruiter['profile_url']=$row['profile_url'];
                          $recruiter['organization']=$row['organization'];
                          $job_link =$row['url'];
                          $posted = strtotime($row['post_date']);
                          $output = date('l jS \of F Y h:i:s A',$posted);
                          $job['description']=$row['description'];
                          $path = "imageUpload/".$recruiter['profile_url'];
                          $img = "<a class='pull-right' href='job_preview.php?job_id={$job['id']}&recruiter_id={$recruiter['id']}'><img src='{$path}' alt='Profile Picture' class='img-circle search-thumbnail' /></a>";
                          $content .= "<div class='h4'>{$row['title']}</div>";
                          $content .= $img;
                          $content .= "<div class='h6'><a href='{$job_link}' target='_blank'>{$row['organization']}</a></div>";
                          $content .="<div class='content'><p>{$row['description']}</p><p>Posted on {$output}</p></div>";
                          $content .="<a href='job_preview.php?job_id={$job['id']}&recruiter_id={$recruiter['id']}'><input type='button' class='btn btn-default btn-primary' value='Read More...' name='read_more'></a>";
                        }
                        $content .="</div>";
                      }
                      echo $content;
                    }
                  break;
                case 5://employment type filter
                    if(isset($_GET['emp_id']) && !empty($_GET['emp_id'])){
                      $emp_id=mysql_fix_string(trim($_GET['emp_id']));
                      $query ="SELECT j.id AS job_id,j.title,j.description,j.post_date, r.profile_url,r.organization,r.id AS recruiter_id,j.url FROM job as j LEFT JOIN recruiter AS r ON j.recruiter_id=r.id WHERE j.employment_type={$emp_id} ORDER BY j.post_date";
                      $job_result = $con->query($query);
                      $content ='<div class="row advert">';
                      $content .="<div class='h2'>Search Filter By Employment Type</div>";
                      $affected_job_rows 	= mysqli_num_rows($job_result);
                      if($affected_job_rows>0){
                        for($i=0;$i<$affected_job_rows;$i++){
                          $row = mysqli_fetch_array($job_result);
                          //print_r($row);
                          $flag = true;
                          $job['id']=$row['job_id'];
                          $recruiter['id']=$row['recruiter_id'];
                          $job['title'] =$row['title'];
                          $recruiter['profile_url']=$row['profile_url'];
                          $recruiter['organization']=$row['organization'];
                          $job_link =$row['url'];
                          $posted = strtotime($row['post_date']);
                          $output = date('l jS \of F Y h:i:s A',$posted);
                          $job['description']=$row['description'];
                          $path = "imageUpload/".$recruiter['profile_url'];
                          $img = "<a class='pull-right' href='job_preview.php?job_id={$job['id']}&recruiter_id={$recruiter['id']}'><img src='{$path}' alt='Profile Picture' class='img-circle search-thumbnail' /></a>";
                          $content .= "<div class='h4'>{$row['title']}</div>";
                          $content .= $img;
                          $content .= "<div class='h6'><a href='{$job_link}' target='_blank'>{$row['organization']}</a></div>";
                          $content .="<div class='content'><p>{$row['description']}</p><p>Posted on {$output}</p></div>";
                          $content .="<a href='job_preview.php?job_id={$job['id']}&recruiter_id={$recruiter['id']}'><input type='button' class='btn btn-default btn-primary' value='Read More...' name='read_more'></a>";
                        }
                        $content .="</div>";
                      }
                      echo $content;
                    }
                  break;
                case 6://degree level filter
                    if(isset($_GET['q_id']) && !empty($_GET['q_id'])){
                      $q_id=mysql_fix_string(trim($_GET['q_id']));
                      $query ="SELECT j.id AS job_id,j.title,j.description,j.post_date, r.profile_url,r.organization,r.id AS recruiter_id,j.url FROM job as j LEFT JOIN recruiter AS r ON j.recruiter_id=r.id WHERE j.education_level={$q_id} ORDER BY j.post_date";
                      $job_result = $con->query($query);
                      $content ='<div class="row advert">';
                      $content .="<div class='h2'>Search Filter By Level of Qualification</div>";
                      $affected_job_rows 	= mysqli_num_rows($job_result);
                      if($affected_job_rows>0){
                        for($i=0;$i<$affected_job_rows;$i++){
                          $row = mysqli_fetch_array($job_result);
                          //print_r($row);
                          $flag = true;
                          $job['id']=$row['job_id'];
                          $recruiter['id']=$row['recruiter_id'];
                          $job['title'] =$row['title'];
                          $recruiter['profile_url']=$row['profile_url'];
                          $recruiter['organization']=$row['organization'];
                          $job_link =$row['url'];
                          $posted = strtotime($row['post_date']);
                          $output = date('l jS \of F Y h:i:s A',$posted);
                          $job['description']=$row['description'];
                          $path = "imageUpload/".$recruiter['profile_url'];
                          $img = "<a class='pull-right' href='job_preview.php?job_id={$job['id']}&recruiter_id={$recruiter['id']}'><img src='{$path}' alt='Profile Picture' class='img-circle search-thumbnail' /></a>";
                          $content .= "<div class='h4'>{$row['title']}</div>";
                          $content .= $img;
                          $content .= "<div class='h6'><a href='{$job_link}' target='_blank'>{$row['organization']}</a></div>";
                          $content .="<div class='content'><p>{$row['description']}</p><p>Posted on {$output}</p></div>";
                          $content .="<a href='job_preview.php?job_id={$job['id']}&recruiter_id={$recruiter['id']}'><input type='button' class='btn btn-default btn-primary' value='Read More...' name='read_more'></a>";
                        }
                        $content .="</div>";
                      }
                      echo $content;
                    }
                  break;
                case 7://Years of Experience filter
                  if(isset($_GET['ex_id']) && !empty($_GET['ex_id'])){
                    $ex_id=mysql_fix_string(trim($_GET['ex_id']));
                    $query ="SELECT j.id AS job_id,j.title,j.description,j.post_date, r.profile_url,r.organization,r.id AS recruiter_id,j.url FROM job as j LEFT JOIN recruiter AS r ON j.recruiter_id=r.id WHERE j.work_experience={$ex_id} ORDER BY j.post_date";
                    $job_result = $con->query($query);
                    $content ='<div class="row advert">';
                    $content .="<div class='h2'>Search Filter By Years of Experience</div>";
                    $affected_job_rows 	= mysqli_num_rows($job_result);
                    if($affected_job_rows>0){
                      for($i=0;$i<$affected_job_rows;$i++){
                        $row = mysqli_fetch_array($job_result);
                        //print_r($row);
                        $flag = true;
                        $job['id']=$row['job_id'];
                        $recruiter['id']=$row['recruiter_id'];
                        $job['title'] =$row['title'];
                        $recruiter['profile_url']=$row['profile_url'];
                        $recruiter['organization']=$row['organization'];
                        $job_link =$row['url'];
                        $posted = strtotime($row['post_date']);
                        $output = date('l jS \of F Y h:i:s A',$posted);
                        $job['description']=$row['description'];
                        $path = "imageUpload/".$recruiter['profile_url'];
                        $img = "<a class='pull-right' href='job_preview.php?job_id={$job['id']}&recruiter_id={$recruiter['id']}'><img src='{$path}' alt='Profile Picture' class='img-circle search-thumbnail' /></a>";
                        $content .= "<div class='h4'>{$row['title']}</div>";
                        $content .= $img;
                        $content .= "<div class='h6'><a href='{$job_link}' target='_blank'>{$row['organization']}</a></div>";
                        $content .="<div class='content'><p>{$row['description']}</p><p>Posted on {$output}</p></div>";
                        $content .="<a href='job_preview.php?job_id={$job['id']}&recruiter_id={$recruiter['id']}'><input type='button' class='btn btn-default btn-primary' value='Read More...' name='read_more'></a>";
                      }
                      $content .="</div>";
                    }
                    echo $content;
                  }
                  break;
                default:
                  location('jobSearch.php');
                  break;
              }
            }
        ?>
     </div><!--End of Container Div-->
 <?php include_once "footer.php"; ?><!--Site footer-->
 <?php include "library.php"; ?><!--Script Library files -->
 </body>
 </html>
