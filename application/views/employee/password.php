<article class="content">
    <div>

        <div class="row">
            <div class="col-md-12">
                <div class="card card-block">
                    <div id="notify" class="alert alert-success" style="display:none;">
                        <a href="#" class="close" data-dismiss="alert">&times;</a>

                        <div class="message"></div>
                    </div>
                    <div id="errors" class="well"></div>
                    <form method="post" id="product_action" class="form-horizontal">
                        <div class="card-body">

                            <h5><?php echo $this->lang->line('Update Your Password') ?> (<?php echo $user['username'] ?>
                                )</h5>
                            <hr>


                            <div class="form-group row">
                                <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                    <label class="col-form-label"  for="address"><?php echo $this->lang->line('New Password') ?><span class="compulsoryfld">*</span></label>
                                    <input type="password" placeholder="New Password" class="form-control margin-bottom  required" name="newpassword"  id="user-pass">
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                    <label class="col-form-label" for="address"><?php echo $this->lang->line('Re New Password') ?><span class="compulsoryfld">*</span></label>
                                    <input type="password" placeholder="Re New Password"  class="form-control margin-bottom  required" name="renewpassword" id="user-pass2">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 submit-section">
                                    <input type="hidden"  name="eid" value="<?php echo $user['id'] ?>">
                                    <input type="submit" id="password_update" class="btn btn-primary btn-lg margin-bottom" value="<?php echo $this->lang->line('Update Password') ?>" data-loading-text="Updating...">
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</article>
<script src="<?php echo assets_url(); ?>assets/myjs/jquery.password-validation.js" type="text/javascript"></script>
<script>
    $(document).ready(function () {
        $("#user-pass").passwordValidation({"confirmField": "#user-pass2"}, function (element, valid, match, failedCases) {

            $("#errors").html("<div class='alert alert-warning mb-2' role='alert'><strong>Password Rules</strong><br>" + failedCases.join("<br>") + "</div>");

            if (valid) $(element).css("border", "2px solid green");
            if (!valid) {
                $(element).css("border", "2px solid red");
                $('#password_update').attr('disabled', 'disabled');
            }
            if (valid && match) {
                $("#user-pass2").css("border", "2px solid green");
                $('#errors').html('');
                $('#password_update').removeAttr('disabled');
            }
            if (!valid || !match) {
                $("#user-pass2").css("border", "2px solid red");
                $('#password_update').attr('disabled', 'disabled');
            }
        });
    });
</script>
<script type="text/javascript">
    $("#password_update").click(function (e) {
        e.preventDefault();
        var actionurl = baseurl + 'employee/updatepassword';
        actionProduct(actionurl);
    });
</script>
