<?php
/*Adding a newly created user into the database*/
function add_admin($con, $password,$username)
  {
      $query = "INSERT INTO admin(password,username) VALUES('$password','$username')";
      $result = $con->query($query);
      if (!$result) {
        die($con->connect_error);
      }
      else {
        return true;
      }
  }
  	/*Function script to check user availability*/
    function check_admin_user($con,$username){
          $query = "SELECT * FROM admin WHERE  username='$username'";
  				//$result 	= $con->query("SELECT * FROM user WHERE  username='$username'");
  				$num_row 	= mysqli_num_rows($con->query($query));

  				if ($num_row > 0)
  					{
  							return false;
  					}
  					else {
  						return true;
  					}
  	}
 ?>
