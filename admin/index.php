<?php
session_start();
include('../include/dbcon.php');
include('../include/function_library.php');
?>
<DOCTYPE html>
<html>
  <head>
    <title>Welcome to E-JobFinder: One-stop-shop for employment</title>
    <?php include_once "header.php"; ?>
    </link rel=""
  </head>
  <body class="login-body">
      <div class="container">
          <div class="content">
            <div class="row">
              <div class="login-panel">
              <p class="h1 login-header">Admin Panel</p>
              <div class="login-form">
                <form method="post" action="<?php outputData($_SERVER['PHP_SELF']); ?>" id="frmLogin">
                    <div class="form-group">
                          <label class="sr-only" for="inputUsername">Username<em class="required">*</em></label>
                          <input class="form-control" type="text" name="inputUsername" id="inputUsername"
                            placeholder="Username" value="<?php if(isset($_COOKIE['username'])){ echo outputData($_COOKIE['username']);}?>" autofocus="true" />
                    </div>
                    <div class="form-group">
                        <label  class="sr-only" for="inputPassword">Password<em class="required">*</em></label>
                        <input class="form-control" type="password" name="inputPassword" id="myLoginPassword" value="<?php if(isset($_COOKIE['pass'])){ echo outputData($_COOKIE['pass']);}?>"
                          placeholder="Password" />
                    </div>
                    <!--Error message to the user-->
                    <p id="errorMessage" class="errorMessage">
                      <?php echo isset($_GET['warning'])?$_GET['warning']:""; ?>
                      <?php
                        if(isset($_POST['btn-login'])){
                          if((isset($_POST['inputUsername']) && !empty($_POST['inputUsername']))&&(isset($_POST['inputPassword']) && !empty($_POST['inputPassword']))){
                            $username = strtolower(mysql_fix_string(trim($_POST['inputUsername'])));//Custome function to prevent SQL and XSS injection attacks
                            $password = strtolower(mysql_fix_string(trim($_POST['inputPassword'])));//Custome function to prevent SQL and XSS injection attacks
                            $password = $password.$config['salt'];
                            $password = hash('ripemd128',$password);
                            //Checking if the user has confirmed their account
                              $query 		= "SELECT * FROM admin WHERE  password='$password' and username='$username'";
                              $result =  $con->query($query);
                              $row		= $result->fetch_array();//Fetching of user data from the database into the row array
                              $num_row 	= mysqli_num_rows($result);
                              if ($num_row  <> 0){
                                //echo "<em style='color:green;'>Success <span class='glyphicon glyphicon-ok'></span></em>";
                                $_SESSION['admin_active'] = true;
                                $_SESSION['admin_username']=$row['username'];
                                  location('dashboard.php');
                              }else{
                               echo "<em style='color:red;'>Login failed</em>";
                             }
                          }
                        }
                      ?>
                    </p>
                      <input type="submit" value="Login" name="btn-login" class="btn btn-default btn-primary" />
                      <!--<a href="resetPassword.php">Forgot Account?</a>-->
               </form>
              </div>
            </div>
            </div>
          </div>
     </div><!--End of Container Div-->
 <?php //include "library.php"; ?><!--Script Library files -->
 <script type="text/javascript" src="../js/jquery-2.1.4.min.js"></script>
 <script type="text/javascript" src="../js/bootstrap.min.js"></script><!--Bootstrap Jquery Library-->
 <script type="text/javascript" src="https://www.google.com/jsapi"></script>
 <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
 <script type="text/javascript" src="../js/jquery.validate.min.js"></script>
 <script type="text/javascript" src="../js/additional-methods.min.js"></script>
 <script type="text/javascript" src="../js/jquery-ui.min.js"></script>
 <script type="text/javascript" src="../js/script.js"></script>
 </body>
 <script type="text/javascript">
   $('document').ready(function(){
       $('#result').load('include/login_processor.php').show();
       $('#ajax_login').click(function(){
             //$('#feedback').append('a');
             if($('#inputUsername').val()!=""){
               if($('#myLoginPassword').val()!=""){
                 $.get('include/login_processor.php', {inputUsername: $('#inputUsername').val(),myLoginPassword: $('#myLoginPassword').val()},
                function(result){
                    $('#result').html(result).show();
                    $('#all_jobs').css('visibility','hidden');
                });
               }else{
                 $('#result').html("Please enter Password").show();
               }
             }else{
               $('#result').html("Please enter Username").show();
             }
       });
     });

  </script>
 </html>
