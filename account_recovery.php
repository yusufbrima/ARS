<?php
session_start();
include('include/dbcon.php');
include('include/function_library.php');
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
           <li><a href="index.php">Welcome to E-JobFinder</a></li>
           <li><a href="signup.php"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
           <li class="active"><a href="login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
         </ul>
       </div>
        </div>
     </nav>
     <div class="menu-spacer"></div>
     <!--Clearing of the float rule-->
       <div class="clear-fix">
       </div>
       <div class="container">
         <!--<div class="h3">You are about to Signup as a Job Seeker.</div>
         <span>Signup as a job Recruiter Instead? Click <a href="recruiter_signup.php">here</a></span><br />
       -->
          <div class="h2"><img src="images/user_account.png" alt="User Account"/></div>
         <ul class="nav nav-pills login">
             <li role="presentation" ><a href="signup.php"><span class="glyphicon glyphicon-user"></span> Create new account</a></li>
            <li role="presentation"><a href="confirmAccount.php">Confirm Account</a></li>
            <li role="presentation" ><a href="login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
            <li role="presentation" ><a href="resetPassword.php">Recover Password</a></li>
          </ul>
          <div class="col-md-6">
            <em class="h1">Contact Admin</em>
          <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" id="frmLogin">
            <div class="form-group">
                  <label class="sr-only" for="inputUsername">Username<em class="required">*</em></label>
                  <input class="form-control" type="text" name="inputUsername" id="inputUsername"
                    placeholder="Username" autofocus/ >
             <div id="feedback"></div>
            </div>
            <div class="form-group">
              <label for="userFeedback">Your Message<em class="required">*</em></label>
              <textarea cols="10" rows="4" name="userFeedback" id="userFeedback" class="form-control">
              </textarea>
            </div>
             <p id="errorMessage" class="errorMessage">
               <?php
                 if(isset($_POST['btn_user_feedback'])){
                      if(isset($_POST['inputUsername'])&& !empty($_POST['inputUsername'])){
                        if(isset($_POST['userFeedback'])&& !empty($_POST['userFeedback'])){
                            $username = mysql_fix_string(trim($_POST['inputUsername']));
                            $feedback = mysql_fix_string(trim($_POST['userFeedback']));
                            $user_id = "";
                             if(!empty($username)){
                                 $query 		= "SELECT * FROM user WHERE username='$username'";
                                 $result = $con->query($query);
                                 $row		= mysqli_fetch_array($result);//Fetching of user data from the database into the row array
                                 $num_row 	= mysqli_num_rows($result);
                                 if ($num_row  <> 0){
                                     $user_id = $row['user_id'];
                                 }
                             }
                            if(!check_user($con,$username)){
                              if(send_feedback($con, $user_id,$feedback)){
                                echo "<em style='color:green;'> Message sent successfuly. One of our Admins will contact you shortly <span class='glyphicon glyphicon-ok'></span></em>";
                                location('index.php');
                              }
                            }else{
                              echo "<em style='color:red;'> Username Unknown <span class='glyphicon glyphicon-remove'></span></em>";
                            }
                        }else{
                          echo "Please enter your feedback";
                        }
                      }else{
                        echo "Please enter your username";
                      }
                 }
                ?>
              </p>
            <div class="form-group">
              <input type="submit" name="btn_user_feedback" id="btn_user_feedback" value="Send Feedback" class="btn btn-default btn-primary" />
            </div>
         </form>
       </div>
       <!--End of the Signup Form -->
       <div class="col-md-6 ad">
         <?php include_once "topAgents.php";?>
       </div>
       </div>
       <?php include_once "footer.php"; ?><!--Site footer-->
 <?php include "library.php"; ?><!--Script Library files -->
 <script>
    $('document').ready(function(){
      $('#feedback').load('include/check_user_recovery.php').show();
      $('#inputUsername').keyup(function(){
        if($('#inputUsername').val().length>=4){
            //$('#feedback').append('a');
            $.post('include/check_user_recovery.php', {username: $('#inputUsername').val()},
                function(result){
                    $('#feedback').html(result).show();
                });
        }
      });
    });
 </script>
 </body>
 </html>
