<div class="content-body">
    <div class="card">
        <div class="card-header border-bottom">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard') ?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo  $this->lang->line('Commission') . ' ' . $this->lang->line('Data'); ?></li>
            </ol>
        </nav>
            <h4 class="card-title"><?php echo $this->lang->line('Commission') . ' ' . $this->lang->line('Data') ?></h4>
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


        </div>
        <div class="font-large-x1 purple p-1" id="param1"></div>
        <div class="card-body">
            <div class="card card-block">
                <form method="post" id="product_action">
                    <div>
                        <h6><?php echo $this->lang->line('Custom Range') ?></h6>
                        <hr>


                        <div class="form-group row">
                            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label"
                                   for="pay_cat"><?php echo $this->lang->line('Employee') ?></label>
                                <select name="pay_acc" class="form-control">

                                    <?php
                                    foreach ($employee as $row) {
                                        $cid = $row['id'];
                                        $name = $row['name'];

                                        echo "<option value='$cid'>$name</option>";
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
                                <input type="submit" id="calculate_profit" class="btn btn-primary btn-lg margin-bottom"
                                       value="<?php echo $this->lang->line('Calculate') ?>"
                                       data-loading-text="Calculating...">
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $("#calculate_profit").click(function (e) {
            e.preventDefault();
            var actionurl = baseurl + 'reports/commission';
            actionCaculate(actionurl);
        });
    </script>