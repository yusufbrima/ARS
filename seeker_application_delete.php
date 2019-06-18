<div id="seeker_application_delete" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span style='Color:red;'>&times;</span></button>
        <h4 class="modal-title">Delete Record</h4>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this record?
        <?php
        if(isset($_GET['job_id']) && isset($_GET['seeker_id'])){
          $id = trim($_GET['job_id']);
          $user_id = trim($_GET['seeker_id']);
          $query = "UPDATE application SET trashed=1 where job_id='{$id}' AND seeker_id='{$user_id}'";
          $result = $con->query($query);
          if($result){
            location('seeker_applications.php');
          }
        }
        ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <a class="btn btn-danger btn-ok">Delete</a>
      </div>
    </div>
  </div>
</div><!--End of modal dialog -->
