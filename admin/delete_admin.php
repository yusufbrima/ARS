<div id="delete_admin_account" class="modal fade" role="dialog">
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
        if(isset($_GET['admin_id']) && !empty($_GET['admin_id'])){
          $id = trim($_GET['admin_id']);
            if(delete_admin_account($con, $id)){
              location('index.php');
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
