<?php session_start(); ?>
<?php
  include('include/dbcon.php');
  include('include/function_library.php');
 ?>

<!DOCTYPE html>
<html>
  <head>
    <title>Welcome to Yusuf & Co. Inc</title>
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
             <li role="presentation" class="active"><a href="login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
             <li role="presentation" ><a href="resetPassword.php">Recover Password</a></li>
          </ul>
          <div class="col-md-6">
          <form method="post" action="<?php outputData($_SERVER['PHP_SELF']); ?>" id="frmLogin">
              <div class="form-group">
                    <label class="sr-only" for="inputUsername">Username<em class="required">*</em></label>
                    <input class="form-control" type="text" name="inputUsername" id="inputUsername"
                      placeholder="Username" value="<?php if(isset($_COOKIE['username'])){ echo outputData($_COOKIE['username']);}?>" />
              </div>
              <div class="form-group">
                  <label  class="sr-only" for="inputPassword">Password<em class="required">*</em></label>
                  <input class="form-control" type="password" name="inputPassword" id="myLoginPassword" value="<?php if(isset($_COOKIE['pass'])){ echo outputData($_COOKIE['pass']);}?>"
                    placeholder="Password" />
              </div>
              <div class="form-group">
                <label for="inputkeepMeLoggedIn">
                  <?php
                    $checked = isset($_COOKIE['pass'])?"checked":"";
                  ?>
                  <input type="checkbox" name="inputkeepMeLoggedIn" id="inputkeepMeLoggedIn" value="1" <?php echo $checked; ?> /> Remember Me
                </label>
              </div>
              <!--Error message to the user-->
              <p id="errorMessage" class="errorMessage">
                <?php echo isset($_GET['warning'])?$_GET['warning']:""; ?>
                <?php
                  if (isset($_POST['login']))
                  {
                         $username = strtolower(mysql_fix_string(trim($_POST['inputUsername'])));//Custome function to prevent SQL and XSS injection attacks
                         $password = strtolower(mysql_fix_string(trim($_POST['inputPassword'])));//Custome function to prevent SQL and XSS injection attacks
                         $password = $password.$config['salt'];
                         $password = hash('ripemd128',$password);
                         //Checking if the user has confirmed their account
                           $query 		= "SELECT * FROM user WHERE  password='$password' and username='$username'";
                           $result =  $con->query($query);
                           $row		= $result->fetch_array();//Fetching of user data from the database into the row array
                           $num_row 	= mysqli_num_rows($result);
                           $user_type = $row['user_type'];
                           if ($num_row  <> 0){
                               if(check_user_account_status($con,$username)){
                                 if(check_blocked_status($con,$username)){
                                       //$_SESSION['last_active']= time();
                                       setcookie('username',$_POST['inputUsername'],time()+7*24*60*60);
                                       if(isset($_POST['inputkeepMeLoggedIn']) && !empty($_POST['inputkeepMeLoggedIn'])){
                                         setcookie('pass',$_POST['inputPassword'],time()+7*24*60*60);
                                       }elseif(isset($_COOKIE['pass'])){
                                         setcookie('pass',$_POST['inputPassword'],time()-7*24*60*60);
                                       }
                                       if($user_type=='1'){
                                           header('location:seeker_dashboard.php');
                                           $_SESSION['user_type_seeker'] = 1;
                                           $_SESSION['seeker_username']=$row['username'];
                                           }
                                       else {
                                               header('location:recruiter_dashboard.php');
                                               $_SESSION['user_type_recruiter'] = 0;
                                               $_SESSION['recruiter_username']=$row['username'];
                                           }
                                   }else{
                                       echo "Sorry, your account has been blocked. Please contact Adminitrator <a href='account_recovery.php'>here</a>";
                                   }
                                 }else {
                                    echo "Please confirm you account! <a href='confirmAccount.php'>here</a>";
                                }
                           }
                      else{
                            echo "Login failed";
                          }
                    }
              ?>
              </p>
                <input type="submit" name="login" value="Login" id="signin" class="btn btn-default btn-primary" />
                <a href="resetPassword.php">Forgot Account?</a>
              <div id="result"></div>
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
 <script type="text/javascript">
   $('document').ready(function(){
       $('#result').load('include/user_login.php').show();
       $('#ajax_login').click(function(){
             //$('#feedback').append('a');
             $.get('include/user_login.php', {inputUsername: $('#inputUsername').val(),myLoginPassword: $('#myLoginPassword').val(),inputkeepMeLoggedIn: $('#inputkeepMeLoggedIn').val()},
                 function(result){
                     $('#result').html(result).show();
                     $('#all_jobs').css('visibility','hidden');
                 });
       });
   }); //End of ready function
</script>
 </html>
