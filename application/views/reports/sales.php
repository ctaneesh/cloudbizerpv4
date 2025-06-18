<div class="content-body">
    <div class="card">
        <div class="card-header border-bottom">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard') ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo  $this->lang->line('Sales') . ' ' . $this->lang->line('Data & Reports'); ?></li>
                </ol>
            </nav>
            <h4 class="card-title"><?php echo $this->lang->line('Sales') . ' ' . $this->lang->line('Data & Reports') ?></h4>
            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
            <div class="heading-elements">
                <ul class="list-inline mb-0">
                    <li><a class="breaklink" data-action="expand"><i class="ft-maximize"></i></a></li>
                </ul>
            </div>
        </div>


        <div class="card-body">
            <div class="col-md-6">
                <ul class="list-unstyled">
                    <li class="font-large-x1 blue "><?php echo $this->lang->line('Total') . ' ' . $this->lang->line('Sales') ?>
                        <span id="p1" class="font-large-x1 red float-xs-right"><i
                                    class=" icon-refresh spinner"></i></span>
                    </li>
                    <hr>
                    <li class="font-large-x1 green"><?php echo $this->lang->line('Total') . ' ' . $this->lang->line('Month') . ' ' . $this->lang->line('Sales') ?>
                        <span id="p2" class="font-large-x1 blue float-xs-right"><i
                                    class=" icon-refresh spinner"></i></span></li>


                    <li class="font-large-x1 orange font-weight-bold" id="param1"></li>

                </ul>

            </div>

        </div>

    </div>

</div>
<div class="card card-block">
    <div class="card-body">
        <form method="post" id="product_action" class="form-horizontal">
            <div class="grid_3 grid_4">
                <h4><?php echo $this->lang->line('Custom Range') . ' ' . $this->lang->line('Sales') ?></h4>
                <hr>
                <div class="form-group row">
                    <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label"
                           for="pay_cat"><?php echo $this->lang->line('Business Locations') ?></label>
                        <select name="pay_acc" class="form-control">
                            <option value='0'><?php echo $this->lang->line('All') ?></option>
                            <?php
                            foreach ($locations as $row) {
                                $cid = $row['id'];
                                $acn = $row['cname'];
                                $holder = $row['address'];
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
                    <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12 mt-32px submit-section">
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
        var actionurl = baseurl + 'reports/customsales';
        actionCaculate(actionurl);
    });
    setTimeout(function () {
        $.ajax({
            url: baseurl + 'reports/fetch_data?p=sales',
            dataType: 'json',
            success: function (data) {
                $('#p1').html(data.p1);
                $('#p2').html(data.p2);

            },
            error: function (data) {
                $('#response').html('Error')
            }

        });
    }, 2000);
</script>