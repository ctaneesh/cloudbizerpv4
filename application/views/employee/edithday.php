<article class="content">
    <div class="card card-block">
        <div id="notify" class="alert alert-success" style="display:none;">
            <a href="#" class="close" data-dismiss="alert">&times;</a>

            <div class="message"></div>
        </div>
        <div class="card card-body">


            <form method="post" id="data_form" class="form-horizontal">

                <h5><?php echo $this->lang->line('Edit') . ' ' . $this->lang->line('Holiday') ?></h5>
                <hr>
                <input type="hidden"
                       name="did"
                       value="<?php echo $hday['id'] ?>">

                <div class="form-group row">
                    <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="from"><?php echo $this->lang->line('From Date') ?><span class="compulsoryfld">*</span></label>
                        <input type="text" class="form-control b_input required"  placeholder="Start Date" name="from" data-toggle="datepicker" autocomplete="false">
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="todate"><?php echo $this->lang->line('To Date') ?><span class="compulsoryfld">*</span></label>
                        <input type="text" class="form-control b_input required" placeholder="End Date" name="todate" data-toggle="datepicker" autocomplete="false">
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="note"><?php echo $this->lang->line('Note') ?><span class="compulsoryfld">*</span></label>
                        <input type="text" placeholder="Note Title" class="form-control margin-bottom b_input required" name="note"  value="<?= $hday['val3'] ?>">
                    </div>
                </div>


                <div class="form-group row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 submit-section">
                        <input type="submit" id="submit-data" class="btn btn-primary btn-lg margin-bottom"
                               value="<?php echo $this->lang->line('Edit') ?>" data-loading-text="Adding...">
                        <input type="hidden" value="employee/editholiday" id="action-url">
                    </div>
                </div>


            </form>
        </div>
    </div>
</article>