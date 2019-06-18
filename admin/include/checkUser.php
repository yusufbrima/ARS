<?php
include('../../include/dbcon.php');
include_once ('class.inc.php');
include('../../include/function_library.php');
    if(isset($_GET['username'])){
        $username = mysql_real_escape_string(strtolower(trim($_GET['username'])));
        if(!check_admin_user($con,$username)){
            echo "<em style='color:red;'>".$_GET['username']. " already taken <span class='glyphicon glyphicon-remove'></span></em>";
        }
        else{
            echo "<em style='color:green;'>".$_GET['username'] ."  is available <span class='glyphicon glyphicon-ok'></span></em>";
        }
    }
