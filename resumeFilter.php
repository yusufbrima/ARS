<form class="" action="echo $_SERVER['PHP_SELF'];" method="post">
 <!--Fields of Study Filter -->
 <div class="panel panel-primary" id="panel-resume-0">
    <div class="panel-heading">
    <h3 class="panel-title">Desired Job Category<span class="pull-right" ><a href="#" class="closePane" id="closeWindow-resume-0">&times;</a></span></h3>
    </div>
    <div class="panel-body">
        <div class="form-group row">
          <?php
            $query ="SELECT j.id,j.name,COUNT(s.job_category) AS total FROM seeker_job_prefrence AS s LEFT JOIN  job_category AS j ON s.job_category=j.id  GROUP BY j.id LIMIT 10";
            $job_filter_result = $con->query($query);
            $affected_job_filter_rows 	= mysqli_num_rows($job_filter_result);
            $result= "";
            if($affected_job_filter_rows>0){
              for($i=0;$i<$affected_job_filter_rows;$i++){
                $row = mysqli_fetch_array($job_filter_result);
                //print_r($row);
                $result .= "<label><a href='resume_filter_output.php?j_id={$row['id']}&flag=1'><input type='checkbox'  name='{$row['name']}' value='{$row['name']}'/>&nbsp;{$row['name']} &nbsp;&nbsp;({$row['total']}) </a></label><br />";
              }
              echo $result;
            }
          ?>
        </div>
    </div>
  </div>
  <!--Employment Type Filter -->
  <div class="panel panel-primary" id="panel-resume-1">
    <div class="panel-heading">
    <h3 class="panel-title">Desired Employment Type<span class="pull-right" ><a href="#" class="closePane" id="closeWindow-resume-1">&times;</a></span></h3>
    </div>
    <div class="panel-body">
        <div class="form-group row">
          <?php
            $query ="SELECT e.id,e.name,count(s.employment_type) AS total FROM seeker_job_prefrence AS s LEFT JOIN employment_type AS e ON s.employment_type=e.id GROUP BY e.id LIMIT 10;";
            $emp_filter_result = $con->query($query);
            $affected_emp_filter_rows 	= mysqli_num_rows($emp_filter_result);
            $result= "";
            if($affected_emp_filter_rows>0){
              for($i=0;$i<$affected_emp_filter_rows;$i++){
                $row = mysqli_fetch_array($emp_filter_result);
                //print_r($row);
                $result .= "<label><a href='resume_filter_output.php?emp_id={$row['id']}&flag=2'><input type='checkbox'  name='{$row['name']}' value='{$row['name']}'/>&nbsp;{$row['name']} &nbsp;&nbsp;({$row['total']}) </a></label><br />";
              }
              echo $result;
            }
          ?>
        </div>
    </div>
  </div>
    <!--Years of Experience Filter -->
    <div class="panel panel-primary" id="panel-resume-3">
      <div class="panel-heading">
      <h3 class="panel-title">Desired Years of Experience<span class="pull-right" ><a href="#" class="closePane" id="closeWindow-resume-3">&times;</a></span></h3>
      </div>
      <div class="panel-body">
        <div class="form-group row">
          <?php
            $query ="SELECT e.id,e.name AS name,COUNT(s.work_experience) AS total FROM seeker_job_prefrence AS s LEFT JOIN  experience_level AS e ON s.work_experience=e.id  GROUP BY e.id LIMIT 10";
            $experience_filter_result = $con->query($query);
            $affected_experience_filter_rows 	= mysqli_num_rows($experience_filter_result);
            $result= "";
            if($affected_experience_filter_rows>0){
              for($i=0;$i<$affected_experience_filter_rows;$i++){
                $row = mysqli_fetch_array($experience_filter_result);
                //print_r($row);
                $result .= "<label><a href='resume_filter_output.php?ex_id={$row['id']}&flag=3'><input type='checkbox'  name='{$row['name']}' value='{$row['name']}'/>&nbsp;{$row['name']} &nbsp;&nbsp;({$row['total']}) </a></label><br />";
              }
              echo $result;
            }
          ?>
        </div>
      </div>
    </div>
    <!--User Location filter -->
    <div class="panel panel-primary" id="panel-resume-4">
      <div class="panel-heading">
      <h3 class="panel-title">Desired Work City<span class="pull-right" ><a href="#" class="closePane" id="closeWindow-resume-4">&times;</a></span></h3>
      </div>
      <div class="panel-body">
        <div class="form-group row">
          <?php
            $query ="SELECT c.id,c.name,count(s.city) AS total FROM seeker_job_prefrence AS s LEFT JOIN city AS c ON s.city=c.id GROUP BY c.id LIMIT 10;";
            $city_filter_result = $con->query($query);
            $affected_city_filter_rows 	= mysqli_num_rows($city_filter_result);
            $result= "";
            if($affected_city_filter_rows>0){
              for($i=0;$i<$affected_city_filter_rows;$i++){
                $row = mysqli_fetch_array($city_filter_result);
                //print_r($row);
                $result .= "<label><a href='resume_filter_output.php?c_id={$row['id']}&flag=4'><input type='checkbox'  name='{$row['name']}' value='{$row['name']}'/>&nbsp;{$row['name']} &nbsp;&nbsp;({$row['total']}) </a></label><br />";
              }
              echo $result;
            }
          ?>
        </div>
      </div>
    </div>

    <div class="panel panel-primary" id="panel-resume-4">
      <div class="panel-heading">
      <h3 class="panel-title">Desired Work Province<span class="pull-right" ><a href="#" class="closePane" id="closeWindow-resume-5">&times;</a></span></h3>
      </div>
      <div class="panel-body">
        <div class="form-group row">
          <?php
            $query ="SELECT p.id,p.name,count(s.province) AS total FROM seeker_job_prefrence AS s LEFT JOIN province AS p ON s.province=p.id GROUP BY p.id LIMIT 10";
            $city_filter_result = $con->query($query);
            $affected_city_filter_rows 	= mysqli_num_rows($city_filter_result);
            $result= "";
            if($affected_city_filter_rows>0){
              for($i=0;$i<$affected_city_filter_rows;$i++){
                $row = mysqli_fetch_array($city_filter_result);
                //print_r($row);
                $result .= "<label><a href='resume_filter_output.php?p_id={$row['id']}&flag=5'><input type='checkbox'  name='{$row['name']}' value='{$row['name']}'/>&nbsp;{$row['name']} &nbsp;&nbsp;({$row['total']}) </a></label><br />";
              }
              echo $result;
            }
          ?>
        </div>
      </div>
    </div>
</form>
