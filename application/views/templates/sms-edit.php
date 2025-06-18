<div class="card card-block">
    <div id="notify" class="alert alert-success" style="display:none;">
        <a href="#" class="close" data-dismiss="alert">&times;</a>

        <div class="message"></div>
    </div>
    <form method="post" id="data_form" class="form-horizontal">
        <div class="card-body">

            <h5><?php echo $this->lang->line('Edit') . ' ( ' . $sms['name'] . ') ' . $this->lang->line('Template') ?></h5>
            <hr>


            <input type="hidden" name="id" value="<?php echo $sms['id'] ?>">


            <div class="form-group row">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label" for="body"><?php echo $this->lang->line('Body') ?></label>
                        <textarea class="form-control margin-bottom summernote" name="body"  rows="15"><?php echo $sms['other'] ?></textarea>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <input type="submit" id="submit-data" class="btn btn-primary btn-lg margin-bottom"
                           value="<?php echo $this->lang->line('Update') ?>" data-loading-text="Updating...">
                    <input type="hidden" value="templates/sms_update" id="action-url">
                </div>
            </div>

        </div>
    </form>
    <div class="box mb-2">
        <div class="col-sm-2">Variables are</div>
        <div class="col-sm-8">{BillNumber}, {URL},{DueDate},{Amount}</div>
    </div>

</div>