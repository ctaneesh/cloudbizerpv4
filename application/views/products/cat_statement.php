<div class="content-body">
    <div class="card">
        <div class="card-header">
            <h4><?php echo $product['title'] . ' ';
                echo $this->lang->line('Statements') ?></h4>
            <hr>
            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
            <div class="heading-elements">
                <ul class="list-inline mb-0">
                    
                    <li><a class="breaklink" data-action="expand"><i class="ft-maximize"></i></a></li>
                    
                </ul>
            </div>
        </div>
        <div class="card-content">
            <div id="notify" class="alert alert-success" style="display:none;">
                <a href="#" class="close" data-dismiss="alert">&times;</a>

                <div class="message"></div>
            </div>
            <div class="card-body">

                <div class="content-body">
                    <div class="content">
                        <div class="row ">
                            <div class="col">
                                <form action="<?php echo base_url() ?>productcategory/report_product" method="post"
                                      role="form">
                                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                                           value="<?php echo $this->security->get_csrf_hash(); ?>">
                                    <input name="id" type="hidden" value="<?php echo $product['id'] ?>">
                                    <div class="form-group row">
                                        <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                            <label class="col-form-label" for="pay_cat"><?php echo $this->lang->line('Type') ?></label>
                                            <select name="r_type" class="form-control">
                                                <option value='1'><?php echo $this->lang->line('Sales') ?></option>
                                                <option value='2'><?php echo $this->lang->line('Purchase Order') ?></option>
                                                <option value='3'><?php echo $this->lang->line('Stock Transfer') ?></option>
                                            </select>
                                        </div>
                                        <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                        <label class="col-form-label"
                                               for="sdate"><?php echo $this->lang->line('From Date') ?><span class="compulsoryfld">*</span></label>
                                            <input type="text" class="form-control required"
                                                   placeholder="Start Date" name="s_date" id="sdate"
                                                   autocomplete="false">
                                        </div>
                                        <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                            <label class="col-form-label"
                                               for="edate"><?php echo $this->lang->line('To Date') ?><span class="compulsoryfld">*</span></label>
                                            <input type="text" class="form-control required"
                                                   placeholder="End Date" name="e_date"
                                                   data-toggle="datepicker" autocomplete="false">
                                        </div>
                                        <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12 mt-2 submit-section">
                                            <input type="hidden" value="<?= $sub ?>" name="sub">
                                            <input type="submit" class="btn btn-primary btn-lg" value="View">
                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>
</div>