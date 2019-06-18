<?php
//error_reporting(0);
/*Adding a newly created user into the database*/
function add_user($con, $password, $email,$secret_code,$user_type,$username)
	{
			$query = "INSERT INTO user(password,email,authen_code,user_type,username) VALUES('$password', '$email','$secret_code','$user_type','$username')";
			$result = $con->query($query);
			if (!$result) {
				die($con->connect_error);
			}
			else {
				return true;
			}

	}
/*Inserting seeker personal details into the database*/
	function add_personal_info($con,$user_id,$first_name,$last_name,$moible,$marital_status,$dob,$profile_url="",$sex,$street,$city,$province,$country)
		{
				$query = "INSERT INTO seeker(user_id,first_name,last_name,marital_status,dob,phone_one,profile_url,sex,street,city,province,country) VALUES('$user_id','$first_name','$last_name','$marital_status','$dob','$moible','$profile_url','$sex','$street','$city','$province','$country')";
                $result = $con->query($query);
				if (!$result) {
                    die($con->connect_error);
				}
				else {
					return true;
				}

		}
/*SQL prevention function */
	function mysql_fix_string($string){
				if (get_magic_quotes_gpc()) $string = stripslashes($string);
				return mysql_real_escape_string($string);
				}
	/*Function script to check user availability*/
  function check_user($con,$username){
        $query = "SELECT * FROM user WHERE  username='$username'";
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
/*function to check user availability in the system*/
function check_user_account_status($con,$username){
			$query 		= "SELECT active FROM user WHERE  username='$username'";
            $result 	= $con->query($query);
			$num_row 	= mysqli_num_rows($con->query($query));
			if ($num_row  <> 0){
						$result = mysqli_fetch_array($result);
						$active = $result['active'];
						if($active <> 0){
							return true;
						}
						else {
							return false;
						}
				}
				else {
					return false;
				}
		}
/*Function to check the user account confirmation code*/
function check_user_code($con,$code){
		    $query 		= "SELECT * FROM user WHERE  authen_code='$code'";
          $num_row 	= mysqli_num_rows($con->query($query));

		    if ($num_row > 0)
		    {
		        return true;
		    }
		    else {
		        return false;
		    }
		}

		/*Function to check the user account  confirmation codefor passsword reset*/
		function check_user_code_confirmation($con,$code){
				    //$query 		= "SELECT * FROM user WHERE  authen_code='$code' AND username='$username'";
						$query 		= "SELECT * FROM user WHERE  authen_code='$code'";
						$result = $con->query($query);
						if($result){
							$num_row 	= mysqli_num_rows($result);
							if ($num_row > 0){
									return true;
							}else {
									return false;
							}
						}else{
							return false;
						}

				}

/*Unlock Account*/
function unlock_account($con,$code){
		    $query 		= "UPDATE USER SET active='1' WHERE authen_code='$code';";
              $result 	= $con->query($query);
            if (!$result) {
            		return false;
            	}
            	else {
            		return true;
            	}
		}

/*Updating Password Reset Code*/
function update_confirmation_code($con,$code,$username){
    $query 		= "UPDATE USER SET authen_code='$code' WHERE username='$username'";
    $result 	= $con->query($query);
    if (!$result) {
        return false;
    }
    else {
        return true;
    }
}

/*Function to avoid Cross Site Scripting and HTML injection*/
	function outputData($data){
		return htmlentities(trim($data));
	}
/*Hard redirection*/
function location($url){
    if(!empty($url)){
        $script = "<script type='text/javascript'>window.location='{$url}'</script>";
				//$script = "<meta content='0,{$url}' http-equiv='refresh' >";
        echo $script;
    }
}
/*Function to add seeker education*/
function add_seeker_education($con, $institution, $level,$field,$start_date,$end_date,$seeker_id)
{
    $query = "INSERT INTO seeker_education(institution_name,field_of_study,start_date,education_level,end_date,seeker_id) VALUES('$institution', '$field','$start_date','$level','$end_date','$seeker_id')";
    $result = $con->query($query);
    if (!$result) {
        die($con->connect_error);
    }
    else {
        return true;
    }

}

/*Function to add seeker experience*/
function add_seeker_experience($con, $job_title, $organization_name,$occupational_field,$field_of_study,$start_date,$end_date=NULL,$seeker_id)
{
    $query = "INSERT INTO seeker_work_experience(job_title,occupational_field ,start_date,end_date,organization,career_field_id,seeker_id) VALUES('$job_title', '$occupational_field','$start_date','$end_date','$organization_name','$field_of_study','$seeker_id')";
    $result = $con->query($query);
    if (!$result) {
        die($con->connect_error);
    }
    else {
        return true;
    }

}

/*Function to add seeker experience*/
function add_seeker_skills($con, $languages, $computer_skills,$leadership_skills,$special_interest,$other_skills=NULL,$seeker_id)
{
    $query = "INSERT INTO seeker_skill(language_skill,computer_skill,leadership,interest,other_skill,seeker_id) VALUES('$languages', '$computer_skills','$leadership_skills','$special_interest','$other_skills','$seeker_id')";
    $result = $con->query($query);
    if (!$result) {
        die($con->connect_error);
    }
    else {
        return true;
    }

}

/*Function to add seeker experience*/
function add_seeker_reference($con, $reference_first_name,$reference_sex,$reference_last_name,$reference_organization,$reference_email,$reference_telephone,$reference_url=NULL,$seeker_id)
{
    $query = "INSERT INTO seeker_reference(first_name,last_name,organization,email,web_link,phone,salutation,seeker_id) VALUES('$reference_first_name', '$reference_last_name','$reference_organization','$reference_email','$reference_url','$reference_telephone','$reference_sex','$seeker_id')";
    $result = $con->query($query);
    if (!$result) {
        die($con->connect_error);
    }
    else {
        return true;
    }

}

/*Updating Password field with new password*/
function update_password($con,$code,$password){
    $query 		= "UPDATE USER SET password='$password' WHERE authen_code='$code'";
    $result 	= $con->query($query);
    if (!$result) {
        return false;
    }
    else {
        return true;
    }
}


/*Function to add seeker experience*/
function update_seeker_reference($con, $reference_first_name,$reference_sex,$reference_last_name,$reference_organization,$reference_email,$reference_telephone,$reference_url=NULL,$user_id,$ref_id)
{
    $query = "UPDATE seeker_reference SET first_name='$reference_first_name',last_name='$reference_last_name',organization='$reference_organization',email='$reference_email',web_link='$reference_url',phone='$reference_telephone',salutation='$reference_sex' WHERE seeker_id='$user_id' AND id='$ref_id' ";
    $result = $con->query($query);
    if (!$result) {
        die($con->connect_error);
    }
    else {
        return true;
    }

}

/*Function to update  seeker skills*/
function update_seeker_skills($con, $languages, $computer_skills,$leadership_skills,$special_interest,$other_skills,$skill_id,$user_id)
{
    $query = "UPDATE seeker_skill SET language_skill='$languages',computer_skill='$computer_skills',leadership='$leadership_skills',interest='$special_interest',other_skill='$other_skills' WHERE seeker_id='$user_id' AND id='$skill_id' ";
    $result = $con->query($query);
    if (!$result) {
        die($con->connect_error);
    }
    else {
        return true;
    }
}


/*Function to update  seeker experience*/
function update_seeker_experience($con, $job_title, $organization_name,$occupational_field,$field_of_study,$start_date,$end_date=NULL,$user_id,$exp_id){
    $query = "UPDATE seeker_work_experience SET job_title='$job_title',occupational_field='$occupational_field',start_date='$start_date',end_date='$end_date',organization='$organization_name',career_field_id='$field_of_study' WHERE seeker_id='$user_id' AND id='$exp_id' ";
    $result = $con->query($query);
    if (!$result) {
        die($con->connect_error);
    }
    else {
        return true;
    }

}


/*Function to update  seeker education*/
function update_seeker_education($con, $institution, $qualification,$field_of_study,$start_date,$end_date=NULL,$education_id,$user_id)
{
    $query = "UPDATE seeker_education SET institution_name='$institution',field_of_study='$field_of_study',start_date='$start_date',education_level='$qualification',end_date='$end_date' WHERE seeker_id='$user_id' AND id='$education_id'";
    $result = $con->query($query);
    if (!$result) {
        die($con->connect_error);
    }
    else {
        return true;
    }
}
/*Function to update  seeker personal details*/
function update_personal_info($con,$first_name,$last_name,$telephone,$marital_status,$dob,$sex,$street,$city,$province,$country,$seeker_id,$user_id){
    $query = "UPDATE seeker SET first_name='$first_name',last_name='$last_name',marital_status='$marital_status',dob='$dob',phone_one='$telephone',sex='$sex',street='$street',city='$city',province='$province',country='$country' WHERE user_id='$user_id' AND id='$seeker_id'";
    $result = $con->query($query);
    if (!$result) {
        die($con->connect_error);
    }
    else {
        return true;
    }
}

/*Function to insert data into recruiter table*/
//add_recruiter_personal_info($con,$user_id,$first_name,$last_name,$telephone,$sex,$web_link,$organization,$newfilename,$street,$city,$province,$country)


function add_recruiter_personal_info($con,$user_id,$first_name,$last_name,$telephone,$sex,$web_link,$organization,$newfilename,$street,$city,$province,$country)
	{
			$query = "INSERT INTO recruiter(user_id,first_name,last_name,organization,phone_one,profile_url,web_link,salutation,street,city,province,country) VALUES('$user_id','$first_name','$last_name','$organization','$telephone','$newfilename','$web_link','$sex','$street','$city','$province','$country')";
							$result = $con->query($query);
			if (!$result) {
									die($con->connect_error);
			}
			else {
				return true;
			}
	}
//update_recruiter_personal_info($con,$first_name,$last_name,$telephone,$sex,$web_link,$organization,$street,$city,$province,$country,$recruiter['id'])
function update_recruiter_personal_info($con,$first_name,$last_name,$telephone,$sex,$web_link,$organization,$street,$city,$province,$country,$id)
	{
			$query = "UPDATE recruiter SET first_name='$first_name',last_name='$last_name',organization='$organization',phone_one='$telephone',web_link='$web_link',salutation='',street='$street',city='$city',province='$province',country='$country' WHERE id='$id'";
							$result = $con->query($query);
			if (!$result) {
									die($con->connect_error);
			}
			else {
				return true;
			}
	}
/*Function to delete file from  the system*/
function delete_file($filename){
  if(file_exists($filename)){
    if(unlink($filename)){
      return true;
    }else{
      return false;
    }
  }else{
      return false;
  }
}

/*Function to update  seeker profile picture*/
function update_seeker_profile_url($con,$seeker_id,$user_id,$newfilename){
    $query = "UPDATE seeker SET profile_url='$newfilename' WHERE user_id='$user_id' AND id='$seeker_id'";
    $result = $con->query($query);
    if (!$result) {
        die($con->connect_error);
    }
    else {
        return true;
    }
}
/*Function to update  recruiter profile picture*/
function update_recuiter_profile_url($con,$recruiter_id,$user_id,$newfilename){
    $query = "UPDATE recruiter SET profile_url='$newfilename' WHERE id='$recruiter_id' AND user_id='$user_id'";
    $result = $con->query($query);
    if (!$result) {
        die($con->connect_error);
    }
    else {
        return true;
    }
}

/*funcion to post a job into the database*/
function add_job($con,$recruiter_id,$job_title,$qualification,$Occupational_field,$employment_type,$work_experience,$salaray,$language,$skills,$job_description,$career_field,$job_tag,$url_title,$job_url,$newfilename,$city,$province,$content)
	{
			$query = "INSERT INTO job(title,occupational_field,employment_type,salary,work_experience,language_level,skills,description,job_tag,link_title,url,attachment_URL,recruiter_id,career_field_id,education_level,city,province,attachment_content) VALUES('$job_title','$Occupational_field','$employment_type','$salaray','$work_experience','$language','$skills','$job_description','$job_tag','$url_title','$job_url','$newfilename','$recruiter_id','$career_field','$qualification','$city','$province','$content')";
			$result = $con->query($query);
			if (!$result) {
									die($con->connect_error);
			}
			else {
				return true;
			}
	}
	/*funcion to update  posted job into the database*/
	function update_job($con,$job_title,$qualification,$Occupational_field,$employment_type,$work_experience,$salary,$language,$skills,$job_description,$career_field,$job_tag,$url_title,$job_url,$city,$province,$recruiter_id,$job_id)
		{
				$query = "UPDATE job set title='$job_title',occupational_field='$Occupational_field',employment_type='$employment_type',salary='$salary',work_experience='$work_experience',language_level='$language',skills='$skills',description='$job_description',job_tag='$job_tag',link_title='$url_title',url='$job_url',career_field_id='$career_field',education_level='$qualification',city='$city',province='$province' WHERE id='$job_id' AND recruiter_id='$recruiter_id'";
				$result = $con->query($query);
				if (!$result) {
										die($con->connect_error);
				}
				else {
					return true;
				}
		}


		/*Adding a newly created user into the database*/
		function job_apply($con,$seeker_id,$recruiter_id,$job_id){
					$query = "INSERT INTO application(job_id,recruiter_id,seeker_id) VALUES('$job_id','$recruiter_id','$seeker_id')";
					$result = $con->query($query);
					if (!$result) {
						die($con->connect_error);
					}else {
						return true;
					}
			}
			/*Function script to check user availability*/
			function check_job_application($con,$seeker_id,$job_id,$recruiter_id){
						$query = "SELECT * FROM application WHERE seeker_id='$seeker_id' AND job_id='$job_id' AND recruiter_id='$recruiter_id'";
						//$result 	= $con->query("SELECT * FROM user WHERE  username='$username'");
						$num_row 	= mysqli_num_rows($con->query($query));
						if ($num_row > 0){
									return false;
							}else{
								return true;
							}
			}
function update_username($con,$username,$password,$new_username){
	$query = "UPDATE user SET username='{$new_username}' WHERE username='{$username}' AND password='{$password}'";
	$result = $con->query($query);
	if (!$result){
		die($con->connect_error);
	}else {
		return true;
	}
}


/*Adding a newly created user into the database*/
function add_seeker_preference($con,$employment_type,$work_experience,$salary,$job_category,$city,$province,$seeker_id){
			$query = "INSERT INTO seeker_job_prefrence(employment_type,work_experience,salary_scale,job_category,city,province,seeker_id) VALUES('$employment_type','$work_experience','$salary','$job_category','$city','$province','$seeker_id')";
			$result = $con->query($query);
			if (!$result) {
				die($con->connect_error);
			}
			else {
				return true;
			}
	}
	/*Function script to check prefrence availability*/
	function check_seeker_job_preference($con,$seeker_id){
				$query = "SELECT * FROM seeker_job_prefrence WHERE seeker_id='$seeker_id'";
				//$result 	= $con->query("SELECT * FROM user WHERE  username='$username'");
				$num_row 	= mysqli_num_rows($con->query($query));
				if ($num_row > 0){
							return false;
					}else{
						return true;
					}
	}
	/*Adding a newly created user into the database*/
	function update_seeker_preference($con,$employment_type,$work_experience,$salary,$job_category,$city,$province,$seeker_id){
				$query = "UPDATE seeker_job_prefrence SET employment_type='$employment_type',work_experience='$work_experience',salary_scale='$salary',job_category='$job_category',city='$city',province='$province' WHERE seeker_id='$seeker_id'";
				$result = $con->query($query);
				if (!$result) {
					die($con->connect_error);
				}
				else {
					return true;
				}
		}

		/*Function script to check user availability*/
		function check_seeker_profile_dependeny($con,$user_id){
					$query = "SELECT * FROM seeker WHERE  user_id='$user_id'";
					//$result 	= $con->query("SELECT * FROM user WHERE  username='$username'");
					$num_row 	= mysqli_num_rows($con->query($query));
					if ($num_row == 0){
								return false;
						}else{
							return true;
						}
}
/*Function to check whether user account is active or blocked*/
function check_blocked_status($con,$username){
	$query =  "SELECT blocked FROM user WHERE username='$username'";
	$result =  $con->query($query);
	$block_status = "";
	if($result){
		$num_rows = mysqli_num_rows($con->query($query));
		for($i=0;$i<$num_rows;$i++){
			$row = mysqli_fetch_array($result);
			$block_status = $row['blocked'];
		}
		if($block_status!=1){
			return true;
		}else{
			return false;
		}
	}
}
/*Snippet to block user account*/
function block_user($con,$user_id){
  $query =  "UPDATE user SET blocked=1 WHERE user_id='$user_id'";
  $result =  $con->query($query);
  if (!$result){
    die($con->connect_error);
  }else {
    return true;
  }
}
/*Snippet to block user account*/
function unblock_user($con,$user_id){
  $query =  "UPDATE user SET blocked=0 WHERE user_id='$user_id'";
  $result =  $con->query($query);
  if (!$result){
    die($con->connect_error);
  }else {
    return true;
  }
}
/*Function to check account dependencies*/
function checker_seeker($con,$user_id,$data_table){
  $query = "SELECT * FROM $data_table WHERE user_id='{$user_id}'";
  $result =  $con->query($query);
  if($result){
    $num_row = mysqli_num_rows($result);
    if($num_row>0){
      return true;
    }else{
      return false;
    }
  }else{
    return false;
  }
}
/*Adding a newly created user messages into the database*/
function send_feedback($con, $user_id,$feedback){
			$query = "INSERT INTO userFeedback(user_id,feedback) VALUES('$user_id','$feedback')";
			$result = $con->query($query);
			if (!$result) {
				die($con->connect_error);
			}else {
				return true;
			}
	}
	/*Deleting User Feedback from database using admin priv*/
	function delete_feedback($con, $feed_id){
				$query = "DELETE FROM userFeedback WHERE id='{$feed_id}'";
				$result = $con->query($query);
				if (!$result) {
					die($con->connect_error);
				}else {
					return true;
				}
		}

		/*Deleting admin user from database*/
		function delete_admin_account($con, $id){
					$query = "DELETE FROM admin WHERE user_id='{$id}'";
					$result = $con->query($query);
					if (!$result) {
						die($con->connect_error);
					}else {
						return true;
					}
			}

			/*Function to check if data already exists in the database*/
			function check_data_insertion($con,$table_name,$criteria){
			  $query =  "SELECT * FROM $table_name WHERE name='$criteria'";
			  $result =  $con->query($query);
			  if($result){
			    $num_row = mysqli_num_rows($result);
			    if($num_row>0){
			      return true;
			    }else{
			      return false;
			    }
			  }
			}
			/*Function to add new categorical data into the database*/
			function add_data_category($con,$table_name,$data){
			  $query = "INSERT INTO $table_name (name) VALUES('$data')";
			  $result = $con->query($query);
			  if (!$result) {
			      die($con->connect_error);
			  }else {
			      return true;
			  }
			}
			/*Function to add new categorical data into the database*/
			function add_education_level($con,$table_name,$data){
				$query = "INSERT INTO $table_name (level) VALUES('$data')";
				$result = $con->query($query);
				if (!$result) {
						die($con->connect_error);
				}else {
						return true;
				}
			}

			/*Function to check if data already exists in the database*/
			function check_education_level($con,$table_name,$criteria){
				$query =  "SELECT * FROM $table_name WHERE level='$criteria'";
				$result =  $con->query($query);
				if($result){
					$num_row = mysqli_num_rows($result);
					if($num_row>0){
						return true;
					}else{
						return false;
					}
				}
			}
			/*Functiion snippet to fetch user profile info from the database*/
			function get_profile_url($con,$table_name,$field_name){
				$query =  "SELECT * FROM $table_name WHERE user_id='{$field_name}'";
				$result = $con->query($query);
				$num_rows = mysqli_num_rows($result);
				if($num_rows>0){
					$dataset =  mysqli_fetch_array($result);
					return $dataset['profile_url'];
				}else{
					return NULL;
				}
			}

			/*function snippet to get seeker ID*/
			function get_seeker_id($con,$field_name){
				$query =  "SELECT id FROM seeker WHERE user_id='{$field_name}'";
				$result = $con->query($query);
				$num_rows = mysqli_num_rows($result);
				if($num_rows>0){
					$dataset =  mysqli_fetch_array($result);
					return $dataset['id'];
				}else{
					return NULL;
				}
			}

			/*function snippet to get recruiter ID*/
			function get_recruiter_id($con,$field_name){
				$query =  "SELECT id FROM recruiter WHERE user_id='{$field_name}'";
				$result = $con->query($query);
				$num_rows = mysqli_num_rows($result);
				if($num_rows>0){
					$dataset =  mysqli_fetch_array($result);
					return $dataset['id'];
				}else{
					return NULL;
				}
			}
			/*Function script to delete admin user*/
			function delete_user($con,$user_id){
			      $query = "SELECT * FROM user WHERE user_id='$user_id'";
			      $result = $con->query($query);
			      if ($result){
			          $query =  "DELETE FROM user WHERE user_id='$user_id'";
			          $result =  $con->query($query);
			          if($result){
			            return true;
			          }else{
			            return false;
			          }
			        }else {
			          return false;
			        }
			  }
				/*Function script to delete seeker */
				function delete_seeker($con,$user_id){
							$query = "SELECT * FROM seeker WHERE user_id='$user_id'";
							$result = $con->query($query);
							if ($result){
									$query =  "DELETE FROM seeker WHERE user_id='$user_id'";
									$result =  $con->query($query);
									if($result){
										return true;
									}else{
										return false;
									}
								}else {
									return false;
								}
					}
					/*Function script to delete seeker */
					function delete_recruiter($con,$user_id){
								$query = "SELECT * FROM recruiter WHERE user_id='$user_id'";
								$result = $con->query($query);
								if ($result){
										$query =  "DELETE FROM recruiter WHERE user_id='$user_id'";
										$result =  $con->query($query);
										if($result){
											return true;
										}else{
											return false;
										}
									}else {
										return false;
									}
						}

						/*function to check seeker job alert status*/
						function check_seeker_job_alert_status($con,$user_id){
							$query =  "SELECT job_alert FROM seeker WHERE user_id='{$user_id}'";
							$result = $con->query($query);
							$num_rows = mysqli_num_rows($result);
							if($num_rows>0){
								$dataset =  mysqli_fetch_array($result);
								 if ($dataset['job_alert']==1){
									 return true;
								 }else{

									 return false;
								 }
							}else{
								return false;
							}
						}
function send_newsletter($email){
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
  $mail->Subject = "Job Post Alert";
  $mail->Body    = "A new job has been posted. Please follow the link to apply for this job <a href='http://localhost/ars/jobSearch.php'>here</a>";
  $mail->AltBody = "A new job has been posted. Please follow the link to apply for this job <a href='http://localhost/ars/jobSearch.php'>here</a>";
  //Final Phase
  if($mail->Send()){
      //location('confirmAccount.php');//Redirection of the user to the login.php page
      return true;
  }else{
      //exit;
      return false;
  }
  //End of mailer code
}