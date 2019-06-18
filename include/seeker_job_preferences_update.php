<?php
session_start();
include('dbcon.php');
include('function_library.php');
  if(isset($_GET['inputEmloymentType']) && !empty($_GET['inputEmloymentType'])){
    if(isset($_GET['inputWorkExperience']) && !empty($_GET['inputWorkExperience'])){
      if(isset($_GET['inputSalary']) && !empty($_GET['inputSalary'])){
        if(isset($_GET['inputCareerField']) && !empty($_GET['inputCareerField'])){
          if(isset($_GET['city']) && !empty($_GET['city'])){
            if(isset($_GET['inputProvince']) && !empty($_GET['inputProvince'])){
              $employment_type = mysql_fix_string(trim($_GET['inputEmloymentType']));
              $work_experience = mysql_fix_string(trim($_GET['inputWorkExperience']));
              $salary =mysql_fix_string(trim($_GET['inputSalary']));
              $job_category = mysql_fix_string(trim($_GET['inputCareerField']));
              $city =mysql_fix_string(trim($_GET['city']));
              $province = mysql_fix_string(trim($_GET['inputProvince']));
              $seeker_id = isset($_GET['inputSeeker_ID'])?mysql_fix_string(trim($_GET['inputSeeker_ID'])):null;
                if(update_seeker_preference($con,$employment_type,$work_experience,$salary,$job_category,$city,$province,$seeker_id)){
                  echo "<em style='color:green;'>Prefrence successfully saved <span class='glyphicon glyphicon-ok'></span></em>";
                  location('seeker_job_preference_view.php');
                }
            }else{
                echo "Select preferred province for jobs<span class='glyphicon glyphicon-remove'></span>";
            } //End of job pprovince validation checker
          }else{
              echo "Select preferred city for jobs<span class='glyphicon glyphicon-remove'></span>";
          } //End of job city validation checker
        }else{
            echo "Select preferred Job Category <span class='glyphicon glyphicon-remove'></span>";
        } //End of job Category validation checker
      }else{
          echo "Enter desired Salary Scale <span class='glyphicon glyphicon-remove'></span>";
      } //End of salary scale validation checker
    }else{
        echo "Select preferred level of experience <span class='glyphicon glyphicon-remove'></span>";
    } //End of experience level validation checker
  }else{
      echo "Select preferred Employment type <span class='glyphicon glyphicon-remove'></span>";
  } //End of employment type validation checker
?>
