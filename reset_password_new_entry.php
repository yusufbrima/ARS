<?php session_start(); ?>
<?php
include('include/dbcon.php');
include('include/function_library.php');
//if($_SESSION['temp']==""){
//header("Location: resetPassword.php");
//}
$link = 'phpMailer/PHPMailerAutoload.php';
require("phpMailer/class.PHPMailer.php");;
if(file_exists($link)){
    require_once 'phpMailer/PHPMailerAutoload.php';
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
         <li><a href="index.php">Welcome to E-JobFinder</a></li>
          <li class="active"><a href="signup.php"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
         <li><a href="login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
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
        <div class="h2">User Account Password Reset Page</div>
        <div class="col-md-6">
            <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" id="frmPasswordResetFinal">
                    <div class="form-group">
                        <label  for="inputPassword">New Password<em class="required">*</em></label>
                        <input class="form-control" type="password" name="inputPassword" id="myPassword"
                               placeholder="Type New Password" />
                    </div>
                    <div class="form-group">
                        <label  for="inputPasswordConfirm">Confirm Password <em class="required">*</em></label>
                        <input class="form-control" type="password" name="inputPasswordConfirm" id="inputPasswordConfirm"
                               placeholder="Confirm password..." />
                    </div>
                    <p id="errorMessage" class="errorMessage">

                        <!--php Script to send secret code to email for password reset-->
                        <?php
                        if (isset($_POST['register']))
                        {
                        if(isset($_POST['inputPassword']) && !empty($_POST['inputPassword'])){
                            if(isset($_POST['inputPasswordConfirm']) && !empty($_POST['inputPasswordConfirm'])){
                                if(trim($_POST['inputPassword']) == trim($_POST['inputPasswordConfirm'])){
                                    if(strlen(trim($_POST['inputPassword']))>=8 &&  strlen(trim($_POST['inputPasswordConfirm']))>=8){
                                        $password = strtolower(mysql_fix_string(trim($_POST['inputPassword'])));
                                        $password = $password.$config['salt'];
                                        $password = hash('ripemd128',$password);
                                        $secret_code = isset($_SESSION['secret_code'])?$_SESSION['secret_code']:"";
                                       if(update_password($con,$secret_code,$password)){
                                                header('location: login.php');
                                       }else{
                                           echo "Password update failed, please try again<span class='glyphicon glyphicon-remove'></span>";
                                       }
                                    }else{
                                        echo "Password must be at least 8 characters long<span class='glyphicon glyphicon-remove'></span>";
                                    }
                                }else{
                                    echo "Password did not match<span class='glyphicon glyphicon-remove'></span>";
                                }
                            }else{
                                echo "Please enter a valid confirmation password<span class='glyphicon glyphicon-remove'></span>";
                            }
                        }
                            else{
                                echo "Please enter a valid password <span class='glyphicon glyphicon-remove'></span>";
                            }//End of Validation
                        }
                        ?>
                    </p>
                    <div class="form-group">
                        <input type="submit" name="register" value="Reset Password" class="btn btn-default btn-primary" />
                        <input type="reset" name="reset" value="Clear Form" class="btn btn-default btn-danger" />
                    </div>
            </form>
        </div>
        <!--End of the Signup Form -->
        <div class="col-md-6 ad">
            <?php include_once "topAgents.php";?>
        </div>
    </div>
    <?php include_once "footer.php"; ?><!--Site footer-->
</div>
<?php include "library.php"; ?><!--Script Library files -->
</body>
</html>
