<?php
session_start();
if(!isset($_SESSION['admin_username']) && empty($_SESSION['admin_username'])){
  if(!isset($_SESSION['admin_active']) && empty($_SESSION['admin_active'])){
    $_SESSION['warning'] = "Access denied, you must login first!";
    header('location: index.php?warning=Authentication required, you must login first to access this page!');
  }
}else{
  $username=$_SESSION['admin_username'];
}
include_once "include/class.inc.php";
include('../include/dbcon.php');
include('../include/function_library.php');
 ?>
<?php
  $query = "SELECT t.u_type AS category,COUNT(u.user_type) AS count FROM user AS u LEFT JOIN user_type AS t ON u.user_type=t.u_key GROUP BY u.user_type;";
  $result = $con->query($query);
  $rows = array();
//flag is not needed
$flag = true;
$table = array();
$table['cols'] = array(

  // Labels for your chart, these represent the column titles
  // Note that one column is in "string" format and another one is in "number" format as pie chart only required "numbers" for calculating percentage and string will be used for column title
  array('label' => 'User Category', 'type' => 'string'),
  array('label' => 'Total', 'type' => 'number')

);

$rows = array();
while($r = mysqli_fetch_assoc($result)) {
  $temp = array();
  // the following line will be used to slice the Pie chart

  $temp[] = array('v' => (string) $r['category']);
  // Values of each slice
  $temp[] = array('v' => (int) $r['count']);
  $rows[] = array('c' => $temp);
}

$table['rows'] = $rows;
$jsonTable = json_encode($table);
?>
<?php
  $query = "select r.organization AS organization,count(j.id) AS count FROM  job AS j LEFT JOIN recruiter AS r ON j.recruiter_id=r.id GROUP BY j.recruiter_id;";
  $result = $con->query($query);
  $rows = array();
//flag is not needed
$flag = true;
$table = array();
$table['cols'] = array(

  // Labels for your chart, these represent the column titles
  // Note that one column is in "string" format and another one is in "number" format as pie chart only required "numbers" for calculating percentage and string will be used for column title
  array('label' => 'Job Statistics', 'type' => 'string'),
  array('label' => 'Total', 'type' => 'number')

);
$total_jobs = 0;
$rows = array();
while($r = mysqli_fetch_assoc($result)) {
  $temp = array();
  // the following line will be used to slice the Pie chart
$total_jobs += $r['count'];
  $temp[] = array('v' => (string) $r['organization']);
  // Values of each slice
  $temp[] = array('v' => (int) $r['count']);
  $rows[] = array('c' => $temp);
}

$table['rows'] = $rows;
$jsonJobTable = json_encode($table);
?>
<?php
  $query = "select concat(s.first_name,' ',s.last_name) as name,count(a.seeker_id) as count from application as a left join seeker as s on s.id=a.seeker_id group by a.seeker_id";
  $result = $con->query($query);
  $rows = array();
//flag is not needed
$flag = true;
$table = array();
$table['cols'] = array(

  // Labels for your chart, these represent the column titles
  // Note that one column is in "string" format and another one is in "number" format as pie chart only required "numbers" for calculating percentage and string will be used for column title
  array('label' => 'Received Applications Statistics', 'type' => 'string'),
  array('label' => 'Total', 'type' => 'number')

);
$total_jobs = 0;
$rows = array();
while($r = mysqli_fetch_assoc($result)) {
  $temp = array();
  // the following line will be used to slice the Pie chart
  $temp[] = array('v' => (string) $r['name']);
  // Values of each slice
  $temp[] = array('v' => (int) $r['count']);
  $rows[] = array('c' => $temp);
}

$table['rows'] = $rows;
$jsonReceivedApplicationsTable = json_encode($table);
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
         <a class="navbar-brand" href="../index.php"><img src="../images/e-jobFinder.png" alt="Logo"/></a>
       </div>
        <div class="collapse navbar-collapse" id="myNavbar">
       <ul class="nav navbar-nav navbar-right">
         <li><a href="dashboard.php">Welcome <?php echo $username; ?></a></li>
        <li class="dropdown active">
         <a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-tasks"></span> Manage Task
         <span class="caret"></span></a>
         <ul class="dropdown-menu">
           <li><a href="manage_job_category.php"><span class="glyphicon glyphicon-user"></span> Add Job Category</a></li>
           <li><a href="manage_field_of_study.php"><span class="glyphicon glyphicon-briefcase"></span> <span class="badge"></span> Add Field Of Study</a></li>
           <li><a href="manage_city.php"><span class="glyphicon glyphicon-envelope"></span> Add City</a></li>
           <li><a href="manage_province.php"><span class="glyphicon glyphicon-envelope"></span> Add Province</a></li>
           <li><a href="manage_qualification.php"><span class="glyphicon glyphicon-envelope"></span> Add Qualiification</a></li>
         </ul>
       </li>
       <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-user"></span> Manage Users
        <span class="caret"></span></a>
        <ul class="dropdown-menu">
          <li><a href="block_user.php"><span class="glyphicon glyphicon-user"></span> Block User</a></li>
          <li><a href="delete_user.php"><span class="glyphicon glyphicon-briefcase"></span> <span class="badge"></span> De-activate Account</a></li>
          <li><a href="add_new_admin.php"><span class="glyphicon glyphicon-envelope"></span> Add Admin</a></li>
          <li><a href="view_admins.php"><span class="glyphicon glyphicon-eye-open"></span> View Admins</a></li>
          <li><a href="user_feedback.php"><span class="glyphicon glyphicon-eye-open"></span>User Feedbacks</a></li>
        </ul>
      </li>
         <li><a href="logout.php"><span class="glyphicon glyphicon-off"></span> Logout</a></li>
       </ul>
     </div>
      </div>
   </nav>
     <div class="menu-spacer"></div>
     <!--Clearing of the float rule-->
       <div class="clear-fix"></div>
       <p class="h1 heading">System summary</p>
       <div class="row"><!--Top Services of the Site-->
         <div class="col-md-4">
           <div id="chart_user"></div>
         </div>
         <div class="col-md-4">
           <div id="chart_applications"></div>
         </div>
         <div class="col-md-4">
           <div id="chart_job">
           </div>
         </div>
       </div>
<?php include "footer.php"; ?><!--Site footer node -->
 <?php //include "library.php"; ?><!--Script Library files -->
 <script type="text/javascript" src="../js/jquery-2.1.4.min.js"></script>
 <script type="text/javascript" src="../js/bootstrap.min.js"></script><!--Bootstrap Jquery Library-->
 <script type="text/javascript" src="https://www.google.com/jsapi"></script>
 <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
 <script type="text/javascript" src="../js/jquery.validate.min.js"></script>
 <script type="text/javascript" src="../js/additional-methods.min.js"></script>
 <script type="text/javascript" src="../js/jquery-ui.min.js"></script>
 </body>
 <script type="text/javascript" src="js/script.js"></script>
 <script type="text/javascript">

  // Load the Visualization API and the piechart package.
  google.load('visualization', '1', {'packages':['corechart']});

  // Set a callback to run when the Google Visualization API is loaded.
  google.setOnLoadCallback(drawUserChart);

  function drawUserChart() {

    // Create our data table out of JSON data loaded from server.
    var data = new google.visualization.DataTable(<?=$jsonTable?>);
    var options = {
         title: 'User statistics',
        is3D: 'true',
        width: 500,
        height: 600
      };
    // Instantiate and draw our chart, passing in some options.
    // Do not forget to check your div ID
    var chart = new google.visualization.PieChart(document.getElementById('chart_user'));
    chart.draw(data, options);
  }
  // Set a callback to run when the Google Visualization API is loaded.
  google.setOnLoadCallback(drawJobChart);

  function drawJobChart() {

    // Create our data table out of JSON data loaded from server.
    var data = new google.visualization.DataTable(<?=$jsonJobTable?>);
    var options = {
         title: 'Job statistics',
        is3D: 'true',
        width: 500,
        height: 600
      };
    // Instantiate and draw our chart, passing in some options.
    // Do not forget to check your div ID
    var chart = new google.visualization.PieChart(document.getElementById('chart_job'));
    chart.draw(data, options);
  }
  // Set a callback to run when the Google Visualization API is loaded.
  google.setOnLoadCallback(drawSentApplicationChart);

  function drawSentApplicationChart() {

    // Create our data table out of JSON data loaded from server.
    var data = new google.visualization.DataTable(<?=$jsonReceivedApplicationsTable?>);
    var options = {
         title: 'Received Applications statistics',
        is3D: 'true',
        width: 500,
        height: 600
      };
    // Instantiate and draw our chart, passing in some options.
    // Do not forget to check your div ID
    var chart = new google.visualization.PieChart(document.getElementById('chart_applications'));
    chart.draw(data, options);
  }
  </script>
 </html>
