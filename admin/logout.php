<?php
/*Destruction of the User Account Session and redirection to the Index Page*/
session_start();
unset($_SESSION['admin_active']);
session_destroy();
header('location:../index.php');
?>
