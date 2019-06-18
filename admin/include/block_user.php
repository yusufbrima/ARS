<?php
include('../../include/dbcon.php');
include('../../include/function_library.php');
if(isset($_GET['user_id']) && !empty($_GET['user_id'])){
  echo "Data is set and id is ". $_GET['user_id'];
}else{
  echo "Data is not set...";
}
?>
