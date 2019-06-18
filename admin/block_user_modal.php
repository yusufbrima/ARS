<div id="block_user_modal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span style='Color:red;'>&times;</span></button>
        <h4 class="modal-title">Block User Account</h4>
      </div>
      <div class="modal-body">

        Are you sure you want to block this account?
        <?php
        if(isset($_GET['block_user_id'])){
          $id = trim($_GET['block_user_id']);
         if(block_user($con,$id)){
           location('block_user.php');
         }
        }
        ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <a class="btn btn-danger btn-ok">Lock Account</a>
      </div>
    </div>
  </div>
</div><!--End of modal dialog -->
