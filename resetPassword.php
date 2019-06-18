<?php session_start(); ?>
<?php
include('include/dbcon.php');
include('include/function_library.php');
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
         <li><a href="signup.php"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
         <li class="active"><a href="login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
       </ul>
     </div>
      </div>
   </nav>
    <div class="menu-spacer"></div>
     </div><!--End of navbar-->
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
             <li role="presentation" class="active"><a href="resetPassword.php">Recover Password</a></li>
          </ul>
          <div class="col-md-6">
          <form method="post" action="<?php $_SERVER['PHP_SELF'];?>" id="frmPasswordReset">
            <div class="form-group">
              <label class="sr-only" for="inputUsername">Username<em class="required">*</em></label>
              <input class="form-control" type="text" name="inputUsername" id="inputUsername"
                placeholder="Username" value="<?php if(isset($_COOKIE['username'])){ echo $_COOKIE['username'];}?>"  />
                      <p><i>Find your username.A 6 digit confirmation code from the system will be sent to your email address.</i></p>
              </div>
              <p id="errorMessage" class="errorMessage">

                <!--php Script to send secret code to email for password reset-->
                <?php
                    if (isset($_POST['register'])){

                        $secret_code = $config['secret_code'];//Generated Secret Code for user verification
                        $username = strtolower(mysql_fix_string(trim($_POST['inputUsername'])));
                            if(!check_user($con,$username)){
                              $query 		= "SELECT email FROM user WHERE username='$username'";
                              $result =  $con->query($query);
                            if($result){
                                 $row = mysqli_fetch_array($result);//Fetching of user data from the database into the row array
                                $num_row 	= mysqli_num_rows($result);
                                if ($num_row  == 1){
                                    $email  = $row['email'];
                                   if(update_confirmation_code($con,$secret_code,$username)){
                                       //Code to Send the mail
                                       $_SESSION['temp_username']=$username;
                                       $mail = new PHPMailer();
                                       //Second Phase
                                       $mail->IsSMTP();            // set mailer to use SMTP
                                       $mail->SMTPDebug =SMTPDebug;
                                       $mail->From = from;
                                       $mail->FromName = fromName;
                                       $mail->Mailer = mailer;
                                       $mail->Host = host;  // specify main and backup server
                                       $mail->SMPTSecure =SMTPSecure;
                                       $mail->Port = port;
                                       $mail->SMTPAuth = true;     // turn on SMTP authentication
                                       $mail->Username = username;  // SMTP username
                                       $mail->Password = password; // SMTP password
                                       //Third Phase
                                       $mail->AddAddress($email);
                                       $mail->AddReplyTo("yusufbrima@gmail.com", "Webmaster");
                                       $mail->WordWrap = 50;
                                       $mail->IsHTML(true);                                  // set email format to HTML
                                       //Fifth Phase
                                       $mail->Subject = "Password reset code for our site";
                                       $mail->Body    = "Your Password reset code is ". $secret_code. " Reset your password  <a href='http://localhost/proj/resetpasswordContinue.php'>here</a>";
                                       $mail->AltBody = "Your Password reset code is". $secret_code. " Reset your password  <a href='http://localhost/proj/resetpasswordContinue.php'>here</a>";
                                       //Final Phase
                                       if($mail->Send()){

                                           header('location: resetpasswordContinue.php');//Redirection of the user to the login.php page

                                       }
                                       else {
                                           echo "Message could not be sent. <p>";
                                           // echo "Mailer Error: " . $mail->ErrorInfo;
                                           exit;
                                       }
                                       //End of mailer code
                                   }//End of user update Code Query
                                }
                            }   //Query to execute if result is fetched from DB

                          }else{
                                echo "Unknown Account!";
                            }
                      }
                ?>
                </p>
               <div class="form-group">
                <input type="submit" name="register" value="Send Code" class="btn btn-default btn-primary" />
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
