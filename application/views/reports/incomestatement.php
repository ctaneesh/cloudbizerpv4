<div class="content-body">
    <div class="card">
        <div class="card-header border-bottom">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard') ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line('Income Statement'); ?></li>
                </ol>
            </nav>
            <h4 class="card-title"><?php echo $this->lang->line('Income Statement') ?></h4>
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
                <div class="row">
                    <div class="col-md-6">
                        <p><?php echo $this->lang->line('Total Income') ?> <?php echo amountExchange($income['lastbal'], 0, $this->aauth->get_user()->loc) ?></p>
                        <p><?php echo $this->lang->line('This Month Income') ?> <?php echo amountExchange($income['monthinc'], 0, $this->aauth->get_user()->loc) ?></p>
                        <p id="param1"></p>
                        <p id="param2"></p>
                    </div>

                </div>

            </div>

        </div>

        <div class="card-body">
            <form method="post" id="product_action" class="form-horizontal">
                <div class="grid_3 grid_4">
                    <h6><?php echo $this->lang->line('Custom Range') ?></h6>
                    <hr>


                    <div class="form-group row">
                        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label"
                               for="pay_cat"><?php echo $this->lang->line('Account') ?></label>
                            <select name="pay_acc" class="form-control">
                                <option value='0'><?php echo $this->lang->line('All Accounts') ?></option>
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
                        <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label"
                               for="sdate"><?php echo $this->lang->line('From Date') ?></label>
                                <?php $oneMonthBefore = date('Y-m-d', strtotime('-1 month')); ?>
                                <input type="date" name="sdate"  value="<?= $oneMonthBefore ?>" class="form-control required">
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label"
                               for="edate"><?php echo $this->lang->line('To Date') ?></label>
                               <input type="date" name="edate"  value="<?= date('Y-m-d') ?>" class="form-control required">
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12 submit-section mt-32px">
                            <input type="hidden" name="check" value="ok">
                            <input type="submit" id="calculate_income" class="btn btn-primary btn-lg margin-bottom"
                                   value="<?php echo $this->lang->line('Calculate') ?>"
                                   data-loading-text="Calculating...">
                        </div>
                    </div>
                </div>

            </form>
        </div>

    </div>
