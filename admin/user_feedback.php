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
    $query =  "SELECT u.username AS username,f.id AS id,f.feedback AS feedback,f.date_sent AS date_received FROM userfeedback AS f LEFT JOIN user AS u ON f.user_id=u.user_id ORDER BY f.id";
    $result_flag =false;
   $result = $con->query($query);
   $user_category="";
   $user_table ="<div><table class='table table-striped' border='0'>";
   $user_table .="<caption class='h3'>List of User Feedbacks</caption>";
   $user_table .="<tr><th>username</th><th>Message</th><th>Received Date</th></tr>";
   $num_row 	= mysqli_num_rows($result);
   if($num_row>0){
     for($i=0;$i<$num_row;$i++){
       $row = mysqli_fetch_array($result);
       //print_r($row);
       $result_flag=true;
       $id=$row['id'];
       $received_date = strtotime($row['date_received']);
       $output = date('l jS \of F Y h:i:s A',$received_date);
       $user_table .= "<tr><td>{$row['username']}</td><td>{$row['feedback']}</td><td>{$output}</td><td><a href='#' data-href='user_feedback.php?feed_id={$id}' data-toggle='modal' data-target='#delete_feed_modal'><span class='glyphicon glyphicon-trash'></span> Delete</a></td></tr>";
     }
    $user_table .="</table></div>";
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
                 <div class="col-md-6">
                       <form>
                       <input type="search" name="inputUsername" id="inputUsername" value="" placeholder="Search by Username" class="form-control"/>
                     </form>
                 </div>
               </div>
           <div id="name_result"></div>
           <div id="all_names">
           <?php
             if($result_flag){
               echo $user_table;
             }
           ?>
        </div>
      </div>
  <?php include'delete_feed_modal.php'; ?><!--Script to block user in the system-->
  <?php include "footer.php"; ?><!--Site footer node -->
 <?php include "library.php"; ?><!--Script Library files -->
 </body>
 <script type="text/javascript">
 $('#delete_feed_modal').on('show.bs.modal', function(e) {
 $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
 });
 /*Natural Language Search for user names*/
   $('#inputUsername').keyup(function(){
         //$('#feedback').append('a');
         $.get('include/username_message_search.php', {inputUsername: $('#inputUsername').val()},
             function(result){
                 $('#name_result').html(result).show();
                 $('#all_names').css('visibility','hidden');
             });
   });
 </script>
 </html>
