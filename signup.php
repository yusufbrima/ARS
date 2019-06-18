<?php session_start();
/*Cache Cache-Control mechanishms*/
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
$terms = "Thanks for using our products and services (“Services”). The Services are provided by E-JobFinder Inc. (“E-JobFinder”), located at 12 Rogbaneh Rd, Makeni City,Bombali, Sierra Leone.

By using our Services, you are agreeing to these terms. Please read them carefully.

Our Services are very diverse, so sometimes additional terms or product requirements (including age requirements) may apply. Additional terms will be available with the relevant Services, and those additional terms become part of your agreement with us if you use those Services.";
$data = NULL;
if(file_exists('include/terms.inc')){
  $data =  file_get_contents('include/terms.inc');
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
         <div class="h3"><img src="images/candidate_sign_up.png" /></div>
         <span>Sign up as a Recruiter Instead? Click <a href="recruiter_signup.php">here</a></span><br />
         <ul class="nav nav-pills login">
             <li role="presentation" id="active"><a href="signup.php"><span class="glyphicon glyphicon-user"></span> Create new account</a></li>
             <li role="presentation"><a href="confirmAccount.php">Confirm Account</a></li>
             <li role="presentation"><a href="login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
             <li role="presentation" ><a href="resetPassword.php">Recover Password</a></li>
          </ul><!--End of User Account Navigation menu-->
          <!--Beginning of the Signup Form-->
          <div class="col-md-6">
          <form method="post" action="include/seeker_signup.php" id="frmSignup" name="frmSignup">
            <div class="form-group">
                  <label class="sr-only" for="inputUsername">Username<em class="required">*</em></label>
                  <input class="form-control" type="text" name="inputUsername" id="inputUsername"
                    placeholder="Username" autofocus/ >
             <div id="feedback"></div>
            </div>
            <div class="form-group">
                  <label class="sr-only" for="inputEmail">Email<em class="required">*</em></label>
                  <input class="form-control" type="email" name="inputEmail" id="inputEmail"
                    placeholder="Email" >
                  <br />
                  <p><i>A valid Email Address. All emails from the system will be sent to that email address.</i></p>
              </div>
              <div class="form-group">
                  <label class="sr-only" for="inputPassword">Password<em class="required">*</em></label>
                  <input class="form-control" type="password" name="inputPassword" id="myPassword"
                    placeholder="Password" />
              </div>
              <div class="form-group">
                  <label  class="sr-only" for="inputPasswordConfirm">Confirm Password <em class="required">*</em></label>
                  <input class="form-control" type="password" name="inputPasswordConfirm" id="inputPasswordConfirm"
                    placeholder="Confirm password..." />
              </div>
              <div class="form-group">
              <!--Error message to the user-->
              <p id="errorMessage" class="errorMessage">
              </p>
              <label for="inputTermsAndConditions">
                <input type="checkbox" name="inputTermsAndConditions" id="inputTermsAndConditions" /> I have <a href="#" id="termsChecker" data-placement="right" data-toggle="popover" title="<?php echo $terms; ?>">read and agreed</a> to the terms and conditions of this Service
              </label>
            </div>
            <div class="form-group">
                <input type="submit" name="register" value="Create Account" id="signup" class="btn btn-default btn-primary" disabled="true" />
                <input type="reset" name="reset" value="Clear Form" class="btn btn-default btn-danger" />
            </div>
         </form>
       </div>
       <!--End of the Signup Form -->
       <div class="col-md-6 ad">
         <?php include_once "topAgents.php";?><!--Top agents canvas-->
       </div>
       </div>
       <?php include_once "footer.php"; ?><!--Site footer-->
       <?php include "library.php"; ?><!--Script Library files -->
 <?php include "library.php"; ?><!--Script Library files -->
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
