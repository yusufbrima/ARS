<?php
include('dbcon.php');
include('function_library.php');
    if(isset($_POST['username'])){
        $username = mysql_real_escape_string(strtolower(trim($_POST['username'])));
        if(!check_user($con,$username)){
            echo "<em style='color:red;'>".$_POST['username']. " already taken <span class='glyphicon glyphicon-remove'></span></em>";
        }
        else{
            echo "<em style='color:green;'>".$_POST['username'] ."  is available <span class='glyphicon glyphicon-ok'></span></em>";
        }
    }