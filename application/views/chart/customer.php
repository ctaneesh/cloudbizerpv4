<div class="content-body">
    <div class="card">
        <div class="card-header border-bottom">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard') ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line('Customer Graphical Reports') ?></li>
                </ol>
            </nav>
            <h5><?= $this->lang->line('Customer Graphical Reports') ?></h5>
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
                <div class="form-group">
                    <!-- basic buttons -->
                    <button type="button"
                            class="update_chart btn btn-primary btn-min-width btn-sm mr-1 mb-1"
                            data-val="week"><i
                                class="fa fa-clock-o"></i> <?= $this->lang->line('This Week') ?>
                    </button>
                    <button type="button"
                            class="update_chart btn btn-secondary btn-min-width  btn-sm mr-1 mb-1"
                            data-val="month"><i
                                class="fa fa-calendar"></i> <?= $this->lang->line('This Month') ?>
                    </button>
                    <button type="button"
                            class="update_chart btn btn-success btn-min-width  btn-sm mr-1 mb-1"
                            data-val="year"><i
                                class="fa fa-book"></i> <?= $this->lang->line('This Year') ?>
                    </button>
                    <button type="button"
                            class="update_chart btn btn-info btn-min-width  btn-sm mr-1 mb-1"
                            data-val="custom"><i
                                class="fa fa-address-book"></i> <?= $this->lang->line('Custom Range Date') ?>
                    </button>

                </div>
                <form id="chart_custom">
                    <div id="custom_c" style="display: none ">
                        <div class="form-row">
                            <div class="col-xl-2 col-lg-6 col-md-5 mb-1">
                                <fieldset class="form-group1">
                                    <label for="basicInput"><?php echo $this->lang->line('From Date') ?></label>
                                    <?php $oneMonthBefore = date('Y-m-d', strtotime('-1 month')); ?>
                                    <input type="date" name="sdate"  value="<?= $oneMonthBefore ?>" class="form-control required">
                                </fieldset>
                            </div>
                            <div class="col-xl-2 col-lg-6 col-md-5 mb-1">
                                <fieldset class="form-group1">
                                    <label for="helpInputTop"><?php echo $this->lang->line('To Date') ?></label>
                                    <input type="date" name="edate"  value="<?= date('Y-m-d') ?>" class="form-control required">
                                </fieldset>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-md-2 mb-1"><span class="mt-2"><br></span>
                                <fieldset class="form-group1">
                                    <input type="hidden" name="p"
                                           value="custom">
                                    <button type="button" id="custom_update_chart"
                                            class="btn btn-blue-grey">Submit
                                    </button>
                                </fieldset>
                            </div>

                        </div>

                    </div>
                </form>
                <div class="card-body">
                    <div class="card-block">
                        <div id="cat-chart" height="400"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        var cat_data = [
            <?php foreach ($chart as $item) {
            echo '{y: "' . $item['name'] . '", a: ' . $item['total'] . ' },';
        }
            ?>
        ];
        draw_c(cat_data);
    });

    function draw_c(cat_data) {
        $('#cat-chart').empty();
        Morris.Bar({
            element: 'cat-chart',
            data: cat_data,
            xkey: 'y',
            ykeys: ['a'],
            labels: ['Amount'],
            barColors: [
                '#85362b',
            ],
            barFillColors: [
                '#34cea7',
            ],
            barOpacity: 0.6,
        });
    }

    $(document).on('click', ".update_chart", function (e) {
        e.preventDefault();
        var a_type = $(this).attr('data-val');
        if (a_type == 'custom') {
            $('#custom_c').show();
        } else {
            $.ajax({
                url: baseurl + 'chart/customer_update',
                dataType: 'json',
                method: 'POST',
                data: {
                    'p': $(this).attr('data-val'),
                    '<?=$this->security->get_csrf_token_name()?>': '<?=$this->security->get_csrf_hash(); ?>'
                },
                success: function (data) {
                    draw_c(data);
                }
            });
        }
    });


    $(document).on('click', "#custom_update_chart", function (e) {
        e.preventDefault();
        $.ajax({
            url: baseurl + 'chart/customer_update',
            dataType: 'json',
            method: 'POST',
            data: $('#chart_custom').serialize() + '&<?=$this->security->get_csrf_token_name()?>=<?=$this->security->get_csrf_hash(); ?>',
            success: function (data) {
                draw_c(data);
            }
        });

    });


</script>