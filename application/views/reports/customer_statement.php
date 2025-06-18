<div class="content-body">
    <div class="card">
        <div class="card-header border-bottom">            
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard') ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line('Customer') . ' ' . $this->lang->line('Account Statement') ?></li>
                </ol>
            </nav>
            <h5><?php echo $this->lang->line('Customer') . ' ' . $this->lang->line('Account Statement') ?></h5>
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

            <span class="text-bold-600"><span class="icon-file-pdf"></span> To Export Data Check <a
                        href="<?php echo base_url() ?>export/account">HERE</a></span>
                <hr>
                <div class="row">
                    <div class="col-12">
                        <div class="card sameheight-item">
                            <?php $oneMonthBefore = date('Y-m-d', strtotime('-1 month')); ?>
                            <form action="<?php echo base_url() ?>reports/customerviewstatement" method="post"
                                  role="form">
                                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                                       value="<?php echo $this->security->get_csrf_hash(); ?>">
                                <div class="form-group row">
                                    <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label" for="pay_cat"><?php echo $this->lang->line('Customer') ?></label>
                                        <select name="customer" class="form-control required" id="customer_statement" required></select>
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label"
                                           for="pay_cat"><?php echo $this->lang->line('Type') ?></label>
                                        <select name="trans_type" class="form-control">
                                            <option value='All'><?php echo $this->lang->line('All Transactions') ?></option>
                                            <option value='Expense'><?php echo $this->lang->line('Debit') ?></option>
                                            <option value='Income'><?php echo $this->lang->line('Credit') ?></option>
                                        </select>
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label"
                                           for="sdate"><?php echo $this->lang->line('From Date') ?><span class="compulsoryfld">*</span></label>
                                        <!-- <input type="text" class="form-control required"
                                               placeholder="Start Date" name="sdate" id="sdate"
                                               data-toggle="datepicker" autocomplete="false"> -->
                                        <input type="date" name="sdate"  value="<?= $oneMonthBefore ?>" class="form-control required">
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label"
                                           for="edate"><?php echo $this->lang->line('To Date') ?><span class="compulsoryfld">*</span></label>
                                            <input type="date" name="edate"  value="<?= date('Y-m-d') ?>" class="form-control required">
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12 submit-section mt-2">
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
<script type="text/javascript">
    $("#customer_statement").select2({
        // minimumInputLength: 4,
        tags: [],
        ajax: {
            url: baseurl + 'search/customer_select',
            dataType: 'json',
            type: 'POST',
            quietMillis: 50,
            data: function (customer) {
                return {
                    customer: customer,
                    '<?=$this->security->get_csrf_token_name()?>': crsf_hash
                };
            },
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.name,
                            id: item.id
                        }
                    })
                };
            },
        }
    });
</script>
