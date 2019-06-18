<form class="" action="echo $_SERVER['PHP_SELF'];" method="post">
  <!--Organizations Filter -->
  <div class="panel panel-primary" id="panel-job-0">
    <div class="panel-heading">
    <h3 class="panel-title">Organizations<span class="pull-right" ><a href="#" class="closePane" id="closeWindow-job-0">&times;</a></span></h3>
    </div>
    <div class="panel-body">
      <div class="form-group row">
        <div class="form-group">
          <?php
            $query ="SELECT j.id,r.id AS recruiter_id,r.organization,count(j.recruiter_id) as count FROM job AS j LEFT JOIN recruiter AS r ON j.recruiter_id=r.id GROUP BY recruiter_id LIMIT 10";
            $organization_filter_result = $con->query($query);
            $affected_organization_filter_rows 	= mysqli_num_rows($organization_filter_result);
            $result= "";
            if($affected_organization_filter_rows>0){
              for($i=0;$i<$affected_organization_filter_rows;$i++){
                $row = mysqli_fetch_array($organization_filter_result);
                //print_r($row);
                $result .= "<label><a href='job_filter_output.php?r_id={$row['recruiter_id']}&flag=1'><input type='checkbox'  name='{$row['organization']}' value='{$row['organization']}'/>&nbsp;{$row['organization']} &nbsp;&nbsp;({$row['count']}) </a></label><br />";
              }
              echo $result;
            }
          ?>
        </div>
      </div>
    </div>
  </div>
    <!--Region Filter -->
    <div class="panel panel-primary" id="panel-job-1">
      <div class="panel-heading">
      <h3 class="panel-title">Region<span class="pull-right" ><a href="#" class="closePane"id="closeWindow-job-1">&times;</a></span></h3>
      </div>
      <div class="panel-body">
        <div class="form-group row">
          <?php
            $query ="SELECT j.id AS job_id,r.id AS recruiter_id,p.id AS province_id,p.name,count(j.province) as count FROM job AS j LEFT JOIN province AS p ON j.province=p.id LEFT JOIN recruiter AS r ON j.recruiter_id=r.id GROUP BY j.province";
            $province_filter_result = $con->query($query);
            $affected_province_filter_rows 	= mysqli_num_rows($province_filter_result);
            $result= "";
            if($affected_province_filter_rows>0){
              for($i=0;$i<$affected_province_filter_rows;$i++){
                $row = mysqli_fetch_array($province_filter_result);
                //print_r($row);
                $result .= "<label><a href='job_filter_output.php?p_id={$row['province_id']}&flag=2'><input type='checkbox'  name='{$row['name']}' value='{$row['name']}'/>&nbsp;{$row['name']} &nbsp;&nbsp;({$row['count']}) </a></label><br />";
              }
              echo $result;
            }
          ?>
        </div>
      </div>
    </div>
    <!--City Filter -->
    <div class="panel panel-primary" id="panel-job-6">
      <div class="panel-heading">
      <h3 class="panel-title">City<span class="pull-right" ><a href="#" class="closePane"id="closeWindow-job-6">&times;</a></span></h3>
      </div>
      <div class="panel-body">
        <div class="form-group row">
          <?php
            $query ="SELECT j.id AS job_id,r.id AS recruiter_id,ci.id AS city_id,ci.name,count(j.city) as count FROM job AS j LEFT JOIN city AS ci ON j.city=ci.id LEFT JOIN recruiter AS r ON j.recruiter_id=r.id GROUP BY j.city";
            $city_filter_result = $con->query($query);
            $affected_city_filter_rows 	= mysqli_num_rows($city_filter_result);
            $result= "";
            if($affected_city_filter_rows>0){
              for($i=0;$i<$affected_city_filter_rows;$i++){
                $row = mysqli_fetch_array($city_filter_result);
                //print_r($row);
                $result .= "<label><a href='job_filter_output.php?c_id={$row['city_id']}&flag=3'><input type='checkbox'  name='{$row['name']}' value='{$row['name']}'/>&nbsp;{$row['name']} &nbsp;&nbsp;({$row['count']}) </a></label><br />";
              }
              echo $result;
            }
          ?>
        </div>
      </div>
    </div>
  <!--Fields of Study Filter -->
  <div class="panel panel-primary" id="panel-job-2">
    <div class="panel-heading">
    <h3 class="panel-title">Job Categories<span class="pull-right" ><a href="#" class="closePane" id="closeWindow-job-2">&times;</a></span></h3>
    </div>
    <div class="panel-body">
      <div class="form-group row">
        <?php
          $query ="SELECT j.id AS job_id,r.id AS recruiter_id,c.id AS career_field_id,c.name,count(j.career_field_id) as count FROM job AS j LEFT JOIN job_category AS c ON j.career_field_id=c.id LEFT JOIN recruiter AS r ON j.recruiter_id=r.id GROUP BY j.career_field_id LIMIT 10";
          $job_category_filter_result = $con->query($query);
          $affected_job_category_filter_rows 	= mysqli_num_rows($job_category_filter_result);
          $result= "";
          if($affected_job_category_filter_rows>0){
            for($i=0;$i<$affected_job_category_filter_rows;$i++){
              $row = mysqli_fetch_array($job_category_filter_result);
              //print_r($row);
              $result .= "<label><a href='job_filter_output.php?j_id={$row['career_field_id']}&flag=4'><input type='checkbox'  name='{$row['name']}' value='{$row['name']}'/>&nbsp;{$row['name']} &nbsp;&nbsp;({$row['count']}) </a></label><br />";
            }
            echo $result;
          }
        ?>
      </div>
    </div>
  </div>
  <!--Employment Type Filter -->
  <div class="panel panel-primary" id="panel-job-3">
    <div class="panel-heading">
    <h3 class="panel-title">Employment Type<span class="pull-right" ><a href="#" class="closePane" id="closeWindow-job-3">&times;</a></span></h3>
    </div>
    <div class="panel-body">
      <div class="form-group row">
        <?php
          $query ="SELECT j.id AS job_id,r.id AS recruiter_id,e.id AS employment_id,e.name,count(j.employment_type) as count FROM job AS j LEFT JOIN employment_type AS e ON j.employment_type=e.id LEFT JOIN recruiter AS r ON j.recruiter_id=r.id GROUP BY j.employment_type";
          $employment_type_filter_result = $con->query($query);
          $affected_employment_type_filter_rows 	= mysqli_num_rows($employment_type_filter_result);
          $result= "";
          if($affected_employment_type_filter_rows>0){
            for($i=0;$i<$affected_employment_type_filter_rows;$i++){
              $row = mysqli_fetch_array($employment_type_filter_result);
              //print_r($row);
              $result .= "<label><a href='job_filter_output.php?emp_id={$row['employment_id']}&flag=5'><input type='checkbox'  name='{$row['name']}' value='{$row['name']}'/>&nbsp;{$row['name']} &nbsp;&nbsp;({$row['count']}) </a></label><br />";
            }
            echo $result;
          }
        ?>
      </div>
    </div>
  </div>
  <!--Degree Level Filter -->
  <div class="panel panel-primary" id="panel-job-4">
    <div class="panel-heading">
    <h3 class="panel-title">Degree Level<span class="pull-right" ><a href="#" class="closePane" id="closeWindow-job-4">&times;</a></span></h3>
    </div>
    <div class="panel-body">
      <div class="form-group row">
        <?php
          $query ="SELECT j.id AS job_id,r.id AS recruiter_id,e.id AS level_id,e.level,count(j.education_level) as count FROM job AS j LEFT JOIN education_level AS e ON j.education_level=e.id LEFT JOIN recruiter AS r ON j.recruiter_id=r.id GROUP BY j.education_level";
          $education_level_filter_result = $con->query($query);
          $affected_education_level_filter_rows 	= mysqli_num_rows($education_level_filter_result);
          $result= "";
          if($affected_education_level_filter_rows>0){
            for($i=0;$i<$affected_education_level_filter_rows;$i++){
              $row = mysqli_fetch_array($education_level_filter_result);
              //print_r($row);
              $result .= "<label><a href='job_filter_output.php?q_id={$row['level_id']}&flag=6'><input type='checkbox'  name='{$row['level']}' value='{$row['level']}'/>&nbsp;{$row['level']} &nbsp;&nbsp;({$row['count']}) </a></label><br />";
            }
            echo $result;
          }
        ?>
      </div>
    </div>
  </div>
    <!--Years of Experience Filter -->
    <div class="panel panel-primary" id="panel-job-5">
      <div class="panel-heading">
      <h3 class="panel-title">Years of Experience<span class="pull-right" ><a href="#" class="closePane" id="closeWindow-job-5">&times;</a></span></h3>
      </div>
      <div class="panel-body">
        <div class="form-group row">
          <?php
            $query ="SELECT j.id AS job_id,r.id AS recruiter_id,e.id AS level_id,e.name,count(j.work_experience) as count FROM job AS j LEFT JOIN experience_level AS e ON j.work_experience=e.id LEFT JOIN recruiter AS r ON j.recruiter_id=r.id GROUP BY j.work_experience";
            $experience_level_filter_result = $con->query($query);
            $affected_experience_level_filter_rows 	= mysqli_num_rows($experience_level_filter_result);
            $result= "";
            if($affected_experience_level_filter_rows>0){
              for($i=0;$i<$affected_experience_level_filter_rows;$i++){
                $row = mysqli_fetch_array($experience_level_filter_result);
                //print_r($row);
                $result .= "<label><a href='job_filter_output.php?ex_id={$row['level_id']}&flag=7'><input type='checkbox'  name='{$row['name']}' value='{$row['name']}'/>&nbsp;{$row['name']} &nbsp;&nbsp;({$row['count']}) </a></label><br />";
              }
              echo $result;
            }
          ?>
        </div>
      </div>
    </div>
</form>
