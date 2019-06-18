<div id="delete_feed_modal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span style='Color:red;'>&times;</span></button>
        <h4 class="modal-title">Delete User Feedback</h4>
      </div>
      <div class="modal-body">

        Are you sure you want to delete this record?
        <?php
        if(isset($_GET['feed_id'])){
          $feed_id = trim($_GET['feed_id']);
          if(delete_feedback($con, $feed_id)){
            location('user_feedback.php');
          }
        }
        ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <a class="btn btn-danger btn-ok">Block</a>
      </div>
    </div>
  </div>
</div><!--End of modal dialog -->
