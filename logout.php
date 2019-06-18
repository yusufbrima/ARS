<?php
/*Destruction of the User Account Session and redirection to the Index Page*/
session_start();
unset($_SESSION['recruiter_username']);
unset($_SESSION['seeker_username']);
unset($_SESSION['user_type_recruiter']);
unset($_SESSION['user_type_seeker']);
session_destroy();
header('location:login.php');
?>
