<?php
ini_set('max_execution_time', 300); //300 seconds = 5 minutes
include('dbcon.php');
include('function_library.php');
$link = '../phpMailer/PHPMailerAutoload.php';
require("../phpMailer/class.PHPMailer.php");;
if(file_exists($link)){
    require_once '../phpMailer/PHPMailerAutoload.php';
}
 ?>
<!--php Insert Script-->
<?php
    if (isset($_POST['register']))
      {
        $username =strtolower(mysql_fix_string(trim($_POST['inputUsername'])));
        $email = strtolower(mysql_fix_string(trim($_POST['inputEmail'])));
        //$username = strtolower(mysqli_real_escape_string($con, $_POST['username']));
        $password = strtolower(mysql_fix_string(trim($_POST['inputPassword'])));
        $password = $password.$config['salt'];//Salting the user password before hashing
        $secret_code = $config['secret_code'];//Generated Secret Code for user verification
        $config['user_type'] = '1';//User type 1= Job Seeker
        //$_SESSION['temp_user']=$username;//Creation of a email id variable
        $password = hash('ripemd128',$password);
          if (filter_var($email,FILTER_VALIDATE_EMAIL)) {
              if(check_user($con,$username)){ //Custom function to check of availability of user account
                  if(add_user($con, $password, $email,$secret_code,$config['user_type'],$username)){ //Calling of the add_user function to create user record in the db
                      $_SESSION['user_type']=$config['user_type'] ;//Construction of  a user_type session variable
                      //Code to Send the mail
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
                          location('../confirmAccount.php');//Redirection of the user to the login.php page
                      }
                      else {
                          echo "Message could not be sent. <p>";
                        //  echo "Mailer Error: " . $mail->ErrorInfo;
                          exit;
                      }
                      //End of mailer code
                  }
              }
              else {
                  echo 'User account already exists!'; //Error message indicating the availability of user account
              }
          }
          else{
              echo "<span class='errorSymbol glyphicon glyphicon-warning-sign'></span> Invalid Email address";
          } //End of email validation logic

      }
    ?>
