<?php
session_start();
if(!isset($_SESSION['admin_username']) && empty($_SESSION['admin_username'])){
  if(!isset($_SESSION['admin_active']) && empty($_SESSION['admin_active'])){
    $_SESSION['warning'] = "Access denied, you must login first!";
    header('location: index.php?warning=Authentication required, you must login first to access this page!');
  }
}else{
  $username=$_SESSION['admin_username'];
}
include_once "include/class.inc.php";
include('../include/dbcon.php');
include('../include/function_library.php');
 ?>
 <?php
    $query =  "SELECT user_id,user_type,username,email,join_date,user_type FROM user ORDER BY username";
    $delete_result_flag =false;
   $result = $con->query($query);
   $user_category="";
   $delete_user_table ="<div><table class='table table-striped' border='0'>";
   $delete_user_table .="<caption class='h3'>List of Users available</caption>";
   $delete_user_table .="<tr><th>username</th><th>Email</th><th>Join Date</th><th>Account Type</th></tr>";
   $num_row 	= mysqli_num_rows($result);
   if($num_row>0){
     for($i=0;$i<$num_row;$i++){
       $row = mysqli_fetch_array($result);
       //print_r($row);
       $delete_result_flag=true;
       $id=$row['user_id'];
       $user_type = $row['user_type'];
       if($row['user_type']==0){
         $user_category="Recruiter";
       }elseif ($row['user_type']==1) {
         $user_category="Job Seeker";
       }
       $flag_data =  hash('ripemd128','my_dommy_data');
       $return_flag =  substr($flag_data,0,17);
       $join_date = strtotime($row['join_date']);
       $output = date('l jS \of F Y h:i:s A',$join_date);
       $delete_user_table .= "<tr><td>{$row['username']}</td><td>{$row['email']}</td><td>{$output}</td><td>{$user_category}&nbsp;&nbsp;&nbsp;<a href='#' data-href='include/delete_user_account.php?user_id={$id}&user_type={$user_category}&flag={$return_flag}' data-toggle='modal' data-target='#delete_user_modal'><span class='glyphicon glyphicon-trash'></span> Delete</a></td></tr>";
     }
    $delete_user_table .="</table></div>";
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
         <a class="navbar-brand" href="dashboard.php"><img src="../images/e-jobFinder.png" alt="Logo"/></a>
       </div>
        <div class="collapse navbar-collapse" id="myNavbar">
       <ul class="nav navbar-nav navbar-right">
         <li><a href="dashboard.php">Welcome <?php echo $username; ?></a></li>
        <li class="dropdown active">
         <a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-tasks"></span> Manage Task
         <span class="caret"></span></a>
         <ul class="dropdown-menu">
           <li><a href="manage_job_category.php"><span class="glyphicon glyphicon-user"></span> Add Job Category</a></li>
           <li><a href="manage_field_of_study.php"><span class="glyphicon glyphicon-briefcase"></span> <span class="badge"></span> Add Field Of Study</a></li>
           <li><a href="manage_city.php"><span class="glyphicon glyphicon-envelope"></span> Add City</a></li>
           <li><a href="manage_province.php"><span class="glyphicon glyphicon-envelope"></span> Add Province</a></li>
           <li><a href="manage_qualification.php"><span class="glyphicon glyphicon-envelope"></span> Add Qualiification</a></li>
         </ul>
       </li>
       <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-user"></span> Manage Users
        <span class="caret"></span></a>
        <ul class="dropdown-menu">
          <li><a href="block_user.php"><span class="glyphicon glyphicon-user"></span> Block User</a></li>
          <li><a href="delete_user.php"><span class="glyphicon glyphicon-briefcase"></span> <span class="badge"></span> De-activate Account</a></li>
          <li><a href="add_new_admin.php"><span class="glyphicon glyphicon-envelope"></span> Add Admin</a></li>
          <li><a href="view_admins.php"><span class="glyphicon glyphicon-eye-open"></span> View Admins</a></li>
          <li><a href="user_feedback.php"><span class="glyphicon glyphicon-eye-open"></span> User Feedbacks</a></li>
        </ul>
      </li>
         <li><a href="logout.php"><span class="glyphicon glyphicon-off"></span> Logout</a></li>
       </ul>
     </div>
      </div>
   </nav>
     <div class="menu-spacer"></div>
     <!--Clearing of the float rule-->
       <div class="clear-fix"></div>
       <div class="row"><!--Top Services of the Site-->
         <div class="container">
             <div class="row">
               <div class="row">
                 <div class="col-md-6">
                   <form>
                     <input type="search" name="inputDeleteUsername" id="inputDeleteUsername" value="" placeholder="Search by Username" class="form-control"/>
                   </form>
                 </div>
                 <div class="col-md-6">
                   <form>
                     <input type="search" name="inputDeleteEmail" id="inputDeleteEmail" value="" placeholder="Search by Email ID" class="form-control"/>
                   </form>
                 </div>
               </div>
               <div id="name_result"></div>
               <div id="all_names">
                 <?php
                   if($delete_result_flag){
                     echo $delete_user_table;
                   }
                 ?>
              </div>
        </div>
      </div>
      <?php include'delete_user_modal.php'; ?><!--Script to block user in the system-->
      <?php include "footer.php"; ?><!--Site footer node -->
     <?php include "library.php"; ?><!--Script Library files -->
     </body>
     <script type="text/javascript">
     /*Natural Language Search for user names*/
       $('#inputDeleteUsername').keyup(function(){
             //$('#feedback').append('a');
             $.get('include/username_delete_search.php', {inputDeleteUsername: $('#inputDeleteUsername').val()},
                 function(result){
                     $('#name_result').html(result).show();
                     $('#all_names').css('visibility','hidden');
                 });
       });
       /*Natural Language Search for email id*/
         $('#inputDeleteEmail').keyup(function(){
               //$('#feedback').append('a');
               $.get('include/email_delete_search.php', {inputDeleteEmail: $('#inputDeleteEmail').val()},
                   function(result){
                       $('#name_result').html(result).show();
                       $('#all_names').css('visibility','hidden');
                   });
         });
       $('#delete_user_modal').on('show.bs.modal', function(e) {
       $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
       });
     </script>
 </html>
