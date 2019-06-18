<!DOCTYPE html>
<html>
  <head>
    <title>Error 404: page no found on server</title>
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
           <li class="active"><a href="index.php">Welcome to E-JobFinder</a></li>
           <li role="presentation"><a href="jobSearch.php"><span class="glyphicon glyphicon-search"></span> Job Search</a></li>
           <li role="presentation"><a href="resumeSearch.php"><span class="glyphicon glyphicon-search"></span> Resume Search</a></li>
           <li><a href="signup.php"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
           <li><a href="login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
         </ul>
       </div>
        </div>
     </nav>
     <div class="menu-spacer"></div>
     <!--Clearing of the float rule-->
       <div class="clear-fix">
       </div>
       <!--Div for the Password Reset Code Canvas-->
       <div class="container">
          <div class="col-md-6">
                <!--404 Error Document -->
                <img src="images/404_error_graphc.png" alt="Document not found on Server" /><br />
                <span class="h3">Click <a href="index.php">here</a> to go back to Homepage!</span>
          </div>
   <!--End of the Signup Form -->
   <div class="col-md-6 ad">
     <?php include_once "topAgents.php";?>
   </div>
 </div><!--End of Container Div-->
   <?php include_once "footer.php"; ?><!--Site footer-->
<?php include "library.php"; ?><!--Script Library files -->
</body>
</html>
