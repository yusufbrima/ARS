<div id="seeker_username_management" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span style='Color:red;'>&times;</span></button>
        <h4 class="modal-title">Change Username</h4>
      </div>
      <div class="modal-body">
        <form>
            <div class="form-group">
                  <label class="sr-only" for="inputOldUsername">Old Username<em class="required">*</em></label>
                  <input class="form-control" type="text" name="inputOldUsername" id="inputOldUsername"
                    placeholder="Enter your old username" value="" />
            </div>
            <div class="form-group">
                <label  class="sr-only" for="inputPassword">Password<em class="required">*</em></label>
                <input class="form-control" type="password" name="inputPassword" id="inputPassword" value=""
                  placeholder="Password" />
            </div>
            <div class="form-group">
                  <label class="sr-only" for="inputUsername">New Username<em class="required">*</em></label>
                  <input class="form-control" type="text" name="inputUsername" id="inputUsername"
                    placeholder="Enter your new username" value="" />
            </div>
            <div id="feedback"></div>
            <div id="newUserfeedback"></div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <a class="btn btn-danger btn-ok" id="Update_profile">Update</a>
      </div>
    </div>
  </div>
</div><!--End of modal dialog -->

<div id="seeker_newsletter" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span style='Color:red;'>&times;</span></button>
        <h4 class="modal-title">Configure Job Alert Settings</h4>
      </div>
      <div class="modal-body">
        <form>
            <div class="form-group">
                  <label for="job_alert">
                    <input  type="checkbox" name="job_alert" id="job_alert" value=""/>Alert Me of Recent Job Posts?
                  </label>
                <input type="hidden" name="s_id" id="seeker_id" value="<?php echo $s_id; ?>" />
            </div>
            <div id="newsletter_feedback"></div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <a class="btn btn-danger btn-ok" id="newsletter">Save</a>
      </div>
    </div>
  </div>
</div><!--End of modal dialog -->
