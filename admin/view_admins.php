<?php
session_start();
if(!isset($_SESSION['admin_username']) && empty($_SESSION['admin_username'])){
  if(!isset($_SESSION['admin_active']) && empty($_SESSION['admin_active'])){
    $_SESSION['warning'] = "Access denied, you must login first!";
    header('location: index.php?warning=Authentication required, you must login first to access this page!');
  }
}else{
  $admin_username=$_SESSION['admin_username'];
}
include_once "include/class.inc.php";
include('../include/dbcon.php');
include('../include/function_library.php');
 ?>
 <?php
    $query =  "SELECT user_id,username,join_date FROM admin ORDER BY username";
    $admin_result_flag =false;
   $result = $con->query($query);
   $admin_table ="<div><table class='table table-striped' border='0'>";
   $admin_table .="<caption class='h3'>List of Admins in the System</caption>";
   $admin_table .="<tr><th>username</th><th>Join Date</th></tr>";
   $num_row 	= mysqli_num_rows($result);
   if($num_row>0){
     for($i=0;$i<$num_row;$i++){
       $row = mysqli_fetch_array($result);
       //print_r($row);
       $id = $row['user_id'];
       $username = $row['username'];
       $admin_result_flag=true;
       $join_date = strtotime($row['join_date']);
       $output = date('l jS \of F Y h:i:s A',$join_date);
       $admin_table .= "<tr><td>{$row['username']}</td><td>{$output}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href='#' data-href='view_admins.php?admin_id={$id}' data-toggle='modal' data-target='#delete_admin_account'><span class='glyphicon glyphicon-trash'></span> Delete</a></td></tr>";
     }
    $admin_table .="</table></div>";
   }
   $message="";
  if(isset($_GET['message'])&&!empty($_GET['message'])){
    $message = $_GET['message'];
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
         <li><a href="dashboard.php">Welcome <?php echo $admin_username; ?></a></li>
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
         <span id="message" style="color:width:100%,height:50px;font-size:20px;"><?php echo $message; ?></span>
         <div class="container">
             <div class="row">
               <div class="col-md-6">
                 <form>
                   <input type="search" name="inputUsername" id="inputUsername" value="" placeholder="Search by Username" class="form-control"/>
                 </form>
               </div>
             </div>
             <div class="row">
               <div class="col-md-6">
                 <div id="name_result"></div>
                 <div id="all_names">
                   <?php
                     if($admin_result_flag){
                       echo $admin_table;
                     }
                   ?>
                </div>
              </div>
            </div>
        </div>
      </div>
 <?php include "delete_admin.php"; ?><!--Site footer node -->
 <?php include "footer.php"; ?><!--Site footer node -->
 <?php include "library.php"; ?><!--Script Library files -->
 </body>
  <script type="text/javascript">
  /*Natural Language Search for user names*/
    $('#inputUsername').keyup(function(){
          //$('#feedback').append('a');
          $.get('include/admin_view_search.php', {inputUsername: $('#inputUsername').val()},
              function(result){
                  $('#name_result').html(result).show();
                  $('#all_names').css('visibility','hidden');
              });
    });
    $('#delete_admin_account').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
    });
  </script>
 </html>
