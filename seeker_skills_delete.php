<div id="skill_delete" class="modal fade" role="dialog">
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
        if(isset($_GET['skill_id']) && isset($_GET['user_id'])){
          $id = trim($_GET['skill_id']);
          $user_id = trim($_GET['user_id']);
          $query = "delete from seeker_skill where id='{$id}' AND seeker_id='{$user_id}'";
          $result = $con->query($query);
          if($result){
            //echo "Record deleted successfully";
            location('seeker_resume_view.php');
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
