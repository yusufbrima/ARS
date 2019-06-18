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
            <li role="presentation" class="active"><a href="confirmAccount.php">Confirm Account</a></li>
            <li role="presentation" ><a href="login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
            <li role="presentation" ><a href="resetPassword.php">Recover Password</a></li>
          </ul>
          <div class="col-md-6">
          <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" id="frmConfirmAccount">
            <div class="form-group">
              <label  for="inputSecretCode">Enter confirmation code<em class="required">*</em></label>
                <input class="form-control" type="text" name="inputSecretCode" maxlength="6" id="inputSecretCode" value="" placeholder="Enter confirmation code"  />
              <p><i>Confirm your account before you login to get access</i><br />
                <i>NB: your account must be confirmed the same Browser Window</i>
              </p>
            </div>
             <p id="errorMessage" class="errorMessage">

                <!--php Confirm Account Code Script-->
                <?php
                if (isset($_POST['register'])){
                    $code = mysql_fix_string($_POST['inputSecretCode']);
                    //$temp_username =isset($_SESSION['temp_user'])?$_SESSION['temp_user']:"";
                      if(check_user_code_confirmation($con,$code)){
                         if(unlock_account($con,$code)){
                              header("Location:login.php");
                          }
                      }
                      else{
                          echo "Invalid Confirmation Code";
                      }
                }

                ?>
              </p>
            <div class="form-group">
              <input type="submit" name="register" value="Confirm Account" class="btn btn-default btn-primary" />
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
 </body>
 </html>
