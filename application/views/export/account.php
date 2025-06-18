<div class="content-body">
    <div class="card">
        <div class="card-header border-bottom">                      
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard') ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line('Account Statements'); ?></li>
                </ol>
            </nav>
            <h4><?php echo $this->lang->line('Account Statements') ?></h4>
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

                <?php $oneMonthBefore = date('Y-m-d', strtotime('-1 month')); ?>
                <div class="row sameheight-container">
                    <div class="col-md-6 col-sm-12">
                        <div class="card card-block sameheight-item">

                            <form action="<?php echo base_url() ?>export/accounts_o" method="post" role="form">
                                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                                       value="<?php echo $this->security->get_csrf_hash(); ?>">
                                <div class="form-group row">
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label"
                                           for="pay_cat"><?php echo $this->lang->line('Account') ?></label>
                                        <select name="pay_acc" class="form-control">

                                            <?php
                                            foreach ($accounts as $row) {
                                                $cid = $row['id'];
                                                $acn = $row['acn'];
                                                $holder = $row['holder'];
                                                echo "<option value='$cid'>$acn - $holder</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label"
                                           for="pay_cat"><?php echo $this->lang->line('Type') ?></label>
                                        <select name="trans_type" class="form-control">
                                            <option value='All'><?php echo $this->lang->line('All Transactions') ?></option>
                                            <option value='Expense'><?php echo $this->lang->line('Debit') ?></option>
                                            <option value='Income'><?php echo $this->lang->line('Credit') ?></option>
                                        </select>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label"
                                           for="sdate"><?php echo $this->lang->line('From Date') ?></label>
                                        <!-- <input type="text" class="form-control date30 required"
                                               placeholder="Start Date" name="sdate" data-toggle="datepicker"
                                               autocomplete="false"> -->
                                               <input type="date" name="sdate"  value="<?= $oneMonthBefore ?>" class="form-control required">
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label"
                                           for="edate"><?php echo $this->lang->line('To Date') ?></label>
                                        <!-- <input type="text" class="form-control required"
                                               placeholder="End Date" name="edate"
                                               data-toggle="datepicker" autocomplete="false"> -->
                                               <input type="date" name="edate" id="edate" value="<?= date('Y-m-d') ?>" class="form-control required">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-12 submit-section">
                                        <input type="submit" class="btn btn-primary btn-md"
                                               value="<?php echo $this->lang->line('Export') ?>">


                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>                 
                    <div class="col-md-6 col-sm-12">
                        <div class="card card-block sameheight-item">

                            <form action="<?php echo base_url() ?>export/trans_cat" method="post" role="form"><input
                                        type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                                        value="<?php echo $this->security->get_csrf_hash(); ?>">
                                <div class="form-group row">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="col-form-label"
                                           for="pay_cat"><?php echo $this->lang->line('Category') ?></label>
                                          <select name="pay_cat" class="form-control">
                                            <?php
                                            foreach ($cat as $row) {
                                                $cid = $row['id'];
                                                $title = $row['name'];
                                                echo "<option value='$title'>$title</option>";
                                            }
                                            ?>
                                        </select>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label"
                                           for="pay_cat"><?php echo $this->lang->line('Type') ?></label>
                                        <select name="trans_type" class="form-control">
                                            <option value='All'><?php echo $this->lang->line('All Transactions') ?></option>
                                            <option value='Expense'><?php echo $this->lang->line('Debit') ?></option>
                                            <option value='Income'><?php echo $this->lang->line('Credit') ?></option>
                                        </select>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="col-form-label"
                                           for="sdate"><?php echo $this->lang->line('From Date') ?></label>
                                        <!-- <input type="text" class="form-control date30 required"
                                               placeholder="Start Date" name="sdate"
                                               data-toggle="datepicker" autocomplete="false"> -->
                                        <input type="date" name="sdate"  value="<?= $oneMonthBefore ?>" class="form-control required">
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="col-form-label"
                                           for="edate"><?php echo $this->lang->line('To Date') ?></label>
                                        <!-- <input type="text" class="form-control required"
                                               placeholder="End Date" name="edate"
                                               data-toggle="datepicker" autocomplete="false"> -->
                                            <input type="date" name="edate"  value="<?= date('Y-m-d') ?>" class="form-control required">
                                </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-12 submit-section">
                                        <input type="submit" class="btn btn-primary btn-md"
                                               value="<?php echo $this->lang->line('Export') ?>">
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>


                </div>
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="card card-block sameheight-item">

                            <form action="<?php echo base_url() ?>export/customer" method="post" role="form"><input
                                        type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                                        value="<?php echo $this->security->get_csrf_hash(); ?>">
                                <div class="form-group row">
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label"
                                            for="pay_cat"><?php echo $this->lang->line('Customer') ?></label>
                                            <select name="customer" class="form-control required" id="customer_statement" required>

                                            </select>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label"
                                           for="pay_cat"><?php echo $this->lang->line('Type') ?></label>
                                        <select name="trans_type" class="form-control">
                                            <option value='All'><?php echo $this->lang->line('All Transactions') ?></option>
                                            <option value='Expense'><?php echo $this->lang->line('Debit') ?></option>
                                            <option value='Income'><?php echo $this->lang->line('Credit') ?></option>
                                        </select>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label"
                                           for="sdate"><?php echo $this->lang->line('From Date') ?></label>
                                        <!-- <input type="text" class="form-control required"
                                               placeholder="Start Date" name="sdate" id="sdate"
                                               data-toggle="datepicker" autocomplete="false"> -->
                                        <input type="date" name="sdate"  value="<?= $oneMonthBefore ?>" class="form-control required">
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label"
                                           for="edate"><?php echo $this->lang->line('To Date') ?></label>
                                        <!-- <input type="text" class="form-control required"
                                               placeholder="End Date" name="edate"
                                               data-toggle="datepicker" autocomplete="false"> -->
                                               <input type="date" name="edate" id="edate" value="<?= date('Y-m-d') ?>" class="form-control required">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-12 submit-section">
                                        <input type="submit" class="btn btn-primary btn-md"
                                               value="<?php echo $this->lang->line('Export') ?>">

                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="card card-block sameheight-item">

                            <form action="<?php echo base_url() ?>export/supplier" method="post" role="form"><input
                                        type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                                        value="<?php echo $this->security->get_csrf_hash(); ?>">
                                <div class="form-group row">
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label"
                                           for="pay_cat"><?php echo $this->lang->line('Supplier') ?></label>
                                        <select name="supplier" class="form-control required" id="supplier_statement" required>

                                        </select>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label"
                                           for="pay_cat"><?php echo $this->lang->line('Type') ?></label>
                                        <select name="trans_type" class="form-control">
                                            <option value='All'><?php echo $this->lang->line('All Transactions') ?></option>
                                            <option value='Expense'><?php echo $this->lang->line('Debit') ?></option>
                                            <option value='Income'><?php echo $this->lang->line('Credit') ?></option>
                                        </select>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label"
                                           for="sdate"><?php echo $this->lang->line('From Date') ?></label>
                                        <!-- <input type="text" class="form-control date30 required"
                                               placeholder="Start Date" name="sdate"
                                               data-toggle="datepicker" autocomplete="false"> -->
                                               <input type="date" name="sdate"  value="<?= $oneMonthBefore ?>" class="form-control required">
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label"
                                           for="edate"><?php echo $this->lang->line('To Date') ?></label>
                                        <!-- <input type="text" class="form-control required"
                                               placeholder="End Date" name="edate"
                                               data-toggle="datepicker" autocomplete="false"> -->
                                               <input type="date" name="edate" id="edate" value="<?= date('Y-m-d') ?>" class="form-control required">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-12 submit-section">
                                        <input type="submit" class="btn btn-primary btn-md"
                                               value="<?php echo $this->lang->line('Export') ?>">
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>     <div class="col-md-6 col-sm-12">
                        <div class="card card-block sameheight-item">

                            <form action="<?php echo base_url() ?>export/employee" method="post" role="form"><input
                                        type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                                        value="<?php echo $this->security->get_csrf_hash(); ?>">
                                <div class="form-group row">
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label"
                                           for="pay_cat"><?php echo $this->lang->line('Employee') ?></label>
                                        <select name="employee" class="form-control">
                                            <?php
                                            foreach ($emp as $row) {
                                                $cid = $row['id'];
                                                $title = $row['name'];
                                                echo "<option value='$cid'>$title</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label"
                                           for="pay_cat"><?php echo $this->lang->line('Type') ?></label>
                                        <select name="trans_type" class="form-control">
                                            <option value='All'><?php echo $this->lang->line('All Transactions') ?></option>
                                            <option value='Expense'><?php echo $this->lang->line('Debit') ?></option>
                                            <option value='Income'><?php echo $this->lang->line('Credit') ?></option>
                                        </select>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label"
                                           for="sdate"><?php echo $this->lang->line('From Date') ?></label>
                                        <!-- <input type="text" class="form-control date30 required"
                                               placeholder="Start Date" name="sdate"
                                               data-toggle="datepicker" autocomplete="false"> -->
                                               <input type="date" name="sdate"  value="<?= $oneMonthBefore ?>" class="form-control required">
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label"
                                           for="edate"><?php echo $this->lang->line('To Date') ?></label>
                                        <!-- <input type="text" class="form-control required"
                                               placeholder="End Date" name="edate"
                                               data-toggle="datepicker" autocomplete="false"> -->
                                               <input type="date" name="edate" id="edate" value="<?= date('Y-m-d') ?>" class="form-control required">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-12 submit-section">
                                        <input type="submit" class="btn btn-primary btn-md"
                                               value="<?php echo $this->lang->line('Export') ?>">


                                    </div>
                                </div>

                            </form>
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
        $("#supplier_statement").select2({
            // minimumInputLength: 3,
            tags: [],
            ajax: {
                url: baseurl + 'search/supplier_select',
                dataType: 'json',
                type: 'POST',
                quietMillis: 50,
                data: function (supplier) {
                    return {
                        supplier: supplier,
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
        $('#sdate_2').datepicker('setDate', '<?php echo date('Y-m-d', strtotime('-30 days', strtotime(date('Y-m-d')))); ?>');

    </script>
