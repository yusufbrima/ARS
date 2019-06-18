<?php session_start();
  header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
  header("Cache-Control: post-check=0, pre-check=0", false);
  header("Pragma: no-cache");
?>
<?php
include('include/dbcon.php');
include('include/function_library.php');
$link = 'phpMailer/PHPMailerAutoload.php';
require("phpMailer/class.PHPMailer.php");;
if(file_exists($link)){
    require_once 'phpMailer/PHPMailerAutoload.php';
}
$terms = "Our vision is to provide a user-centric, personalized, flexible and adaptive experience with services that are suited for Knowledge workers.
With a structured platform, we seek to connect prospective employers with potential employees who have the requisite skills.
Information on job seekers and employers are readily available in a database that is fully searchable and results are presented
in an intuitive User Interface that matches the status quo of UI design patterns";
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
        <div class="h3"><img src="images/candidate_sign_up.png" /></div>
         <span>Signup as a Candidate Instead? Click <a href="signup.php">here</a></span><br />
         <ul class="nav nav-pills login">
             <li role="presentation" class="active"><a href="signup.php"><span class="glyphicon glyphicon-user"></span> Create new account</a></li>
            <li role="presentation"><a href="confirmAccount.php">Confirm Account</a></li>
            <li role="presentation"><a href="login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
            <li role="presentation" ><a href="resetPassword.php">Recover Password</a></li>
          </ul><!--End of User Account Navigation menu-->
          <!--Beginning of the Signup Form-->
          <div class="col-md-6">
          <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="frmSignup">
            <div class="form-group">
                  <label  class="sr-only" for="inputUsername">Username<em class="required">*</em></label>
                  <input class="form-control" type="text" name="inputUsername" id="inputUsername"
                    placeholder="Username" autofocus />
                <div id="feedback"></div>
            </div>
            <div class="form-group">
                  <label  class="sr-only" for="inputEmail">Email<em style="color:red;">*</em></label>
                  <input class="form-control" type="email" name="inputEmail" id="inputEmail"
                    placeholder="Email" />
                  <br />
                  <p><i>A valid Email Address. All emails from the system will be sent to that email address.</i></p>
              </div>
              <div class="form-group">
                  <label  class="sr-only" for="inputPassword">Password<em style="color:red;">*</em></label>
                  <input class="form-control" type="password" name="inputPassword"id="myPassword"
                    placeholder="Password" />
              </div>
              <div class="form-group">
                  <label class="sr-only" for="inputPasswordConfirm">Confirm Password <em style="color:red;">*</em></label>
                  <input class="form-control" type="password" name="inputPasswordConfirm"id="inputPasswordConfirm"
                    placeholder="Confirm password..." />
              </div>
              <div class="form-group">
              <!--Error message to the user-->
              <p id="errorMessage" class="errorMessage">

                <!--php Insert Script-->
                <?php
                    if (isset($_POST['register']))
                      {
                        $username =strtolower(mysql_fix_string(trim($_POST['inputUsername'])));
                        $email = strtolower(mysql_fix_string($_POST['inputEmail']));
                        $password = strtolower(mysql_fix_string($_POST['inputPassword']));
                        $password = $password.$config['salt'];
                        //$_SESSION['temp_user']=$username;
                        $password = hash('ripemd128',$password);//Hashing of salted password with the ripemd128 crypto algorithm
                        $secret_code = $config['secret_code'];//Generated Secret Code for user verification
                        $config['user_type'] = '0';//User type 0= Recruiter Account
                        if (!filter_var($email,FILTER_VALIDATE_EMAIL)) {
                            echo "<span class='errorSymbol glyphicon glyphicon-warning-sign'></span> Invalid Email address";
                        }
                        else{
                          if(check_user($con,$username)){
                            if(add_user($con, $password, $email,$secret_code,$config['user_type'],$username)){
                              $_SESSION['user_type']=$config['user_type'] ;//Construction of  a user_type session variable
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
                                $mail->Subject = "Email Confirmation Code";
                                $mail->Body    = "Your Confirmation code is ". $secret_code. " Confirm your account  <a href='http://localhost/proj/confirmAccount.php'>here</a>";
                                $mail->AltBody = "Your Confirmation code is ". $secret_code. " Confirm your account  <a href='http://localhost/proj/confirmAccount.php'>here</a>";
                                //Final Phase
                                if($mail->Send())
                                {
                                    location('confirmAccount.php');//Redirection of the user to the login.php page
                                }
                                else {
                                    echo "<span class='errorSymbol glyphicon glyphicon-warning-sign'></span> Message could not be sent. <p>";
                                  //  echo "Mailer Error: " . $mail->ErrorInfo;
                                    exit;
                                }
                                //End of mailer code
                            }

                          }
                        else {
                              echo "<span class='errorSymbol glyphicon glyphicon-warning-sign'></span> User account already exists!"; //Error message indicating the availability of user account
                          }
                        }
                      }
                    ?>
              </p>
              <label for="inputTermsAndConditions">
                <input type="checkbox" name="inputTermsAndConditions" id="inputTermsAndConditions" /> I have <a href="#!" id="termsChecker" data-placement="right" data-toggle="popover" title="<?php echo $terms; ?>"> read and agreed</a> to the terms and conditions of this Service
              </label>
            </div>
            <div class="form-group">
                <input type="submit" name="register" id="signup" value="Create Account" class="btn btn-default btn-primary" disabled="true" />
                <input type="reset" name="reset" value="Clear Form" class="btn btn-default btn-danger" />
            </div>
         </form>
       </div>
       <!--End of the Signup Form -->
       <div class="col-md-6 ad">
         <?php include_once "topAgents.php";?>
       </div>
       </div>
       <div class="clear-fix">

       </div>
       <?php include_once "footer.php"; ?><!--Site footer-->
 <?php include "library.php";
  ?><!--Script Library files -->
 </body>
  <script>
      $('document').ready(function(){
          $('#feedback').load('include/checkUser.php').show();
          $('#inputUsername').keyup(function(){
              if($('#inputUsername').val().length>=4){
                  //$('#feedback').append('a');
                  $.post('include/checkUser.php', {username: $('#inputUsername').val()},
                      function(result){
                          $('#feedback').html(result).show();
                      });
              }
          });
          $('#inputTermsAndConditions').click(function(){
              var inputTermsAndConditions = document.getElementById('inputTermsAndConditions');
              if(inputTermsAndConditions.checked){
                $('#signup').attr('disabled',false);
              }else if (!inputTermsAndConditions.checked) {
                $('#signup').attr('disabled',true);
              }
           });
      }); //End of ready function
  </script>
 </html>