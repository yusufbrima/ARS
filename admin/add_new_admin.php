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
           <div class="col-md-6">
             <form>
               <div class="form-group">
                     <label class="sr-only" for="inputUsername">Username<em class="required">*</em></label>
                     <input class="form-control" type="text" name="inputAdminUsername" id="inputAdminUsername"
                       placeholder="Username" autofocus/ >
                <div id="feedback"></div>
               </div>
                 <div class="form-group">
                     <label class="sr-only" for="inputPassword">Password<em class="required">*</em></label>
                     <input class="form-control" type="password" name="inputAdminPassword" id="inputAdminPassword"
                       placeholder="Password" />
                 </div>
                 <div class="form-group">
                     <label  class="sr-only" for="inputPasswordConfirm">Confirm Password <em class="required">*</em></label>
                     <input class="form-control" type="password" name="inputAdminPasswordConfirm" id="inputAdminPasswordConfirm"
                       placeholder="Confirm password..." />
                 </div>
                 <div id="errorMessage"></div>
                 <input type="button" name="register" value="Create Account" id="admin_signup" class="btn btn-default btn-primary" />
              </form>
              <div id="result"></div>
           </div>
          <div class="col-md-6">
          </div>
        </div>
      </div>
  <?php include "footer.php"; ?><!--Site footer node -->
 <?php include "library.php"; ?><!--Script Library files -->
 </body>
 <script type="text/javascript">
 $('document').ready(function(){
   $('#admin_signup').click(function(){
         //$('#feedback').append('a');
         if($('#inputAdminUsername').val()!=""){
           if($('#inputAdminPassword').val()!=""){
             if($('#inputAdminPasswordConfirm').val()!=""){
               if($('#inputAdminPasswordConfirm').val()==$('#inputAdminPassword').val()){
                 $.get('include/add_admin.php', {inputAdminUsername: $('#inputAdminUsername').val(),myAdminPassword: $('#inputAdminPasswordConfirm').val()},
                   function(result){
                       $('#result').html(result).show();
                   });
               }else{
                 $('#errorMessage').html('Password fields must match').show();
               }
             }else{
               $('#errorMessage').html('Please confirm your password').show();
             }
           }else{
             $('#errorMessage').html('Please enter password').show();
           }
         }else{
           $('#errorMessage').html('Please enter a valid username').show();
         }
   });
   /*javascript snippet to check user availability*/
   $('#inputAdminUsername').keyup(function(){
     if($('#inputAdminUsername').val().length>=4){
         //$('#feedback').append('a');
         $.get('include/checkUser.php', {username: $('#inputAdminUsername').val()},
             function(result){
                 $('#feedback').html(result).show();
             });
     }
   });
  });
 </script>
 </html>
