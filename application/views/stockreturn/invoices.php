<div class="content-body">
    <?php       
    if (($msg = check_permission($permissions)) !== true) {
        echo $msg;
        return;
    }
    ?>
    <div class="card">
        <div class="card-header border-bottom">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                    <li class="breadcrumb-item active"><?php echo $this->lang->line('Stock Return') ?></li>
                </ol>
            </nav>
            <h5 class="title"><?php echo $this->lang->line('Stock Return') ?> <a
                        href="<?php echo base_url('stockreturn/create') ?>"
                        class="btn btn-primary btn-sm">
                    <?php echo $this->lang->line('Add new') ?>
                </a></h5>
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

                <!-- ================ erp2024 newly added 28-06-2024 stats ============== -->
                <?php
                    $currentDate = new DateTime();
                    $today = $currentDate->format('d-m-Y');
                    $weekAgo = (clone $currentDate)->modify('-7 days')->format('d-m-y');
                    $monthAgo = (clone $currentDate)->modify('-1 month')->format('d-m-y');
                    $quarterAgo = (clone $currentDate)->modify('-4 months')->format('d-m-y');
                    $yearAgo = (clone $currentDate)->modify('-1 year')->format('d-m-y');
                ?>
                    <div class="text-right">
                        <i class="fa fa-filter"></i>&nbsp;&nbsp;
                        <div class="btn-group btn-group filter-group">
                            <button type="button" class="btn btn-outline-secondary navsearch" id="today" onclick="today()"><div class="filter-day-count  d-flex justify-content-between"><div><?php echo $this->lang->line('Today'); ?></div><div><?php echo $counts->daily_count; ?></div></div>
                            <div class="filter-day-value">
                            <?php   
                                    echo " <span title='".$this->lang->line('Draft Title')."'>".$this->lang->line('Filter Draft')." : <strong class='filter-due' id='daily_created_count'>".$counts->daily_created_count."</strong></span>";
                                    echo " <span title='".$this->lang->line('Created Title')."'> ".$this->lang->line('Filter Created')." : <strong class='filter-partial' id='daily_partial_count'>". $counts->daily_partial_count."</strong></span>";
                                    echo " <span title='".$this->lang->line('Sent Title')."'>".$this->lang->line('Filter Sent')." : <strong class='filter-paid' id='daily_paid_count'>".$counts->daily_paid_count."</strong></span>";
                                    
                            ?>
                            </div>
                            <div class="filter-day-value border-top"><?php echo $this->lang->line('Total Value'); ?> : <strong class="text-right" id="daily_total"><?php echo number_format($counts->daily_total,2); ?></strong></div>
                            </button>

                            <button type="button" class="btn btn-outline-secondary navsearch" id="week" onclick="week()"><div class="filter-day-count  d-flex justify-content-between"><div><?php echo $this->lang->line('Week'); ?></div><div><?php echo $counts->weekly_count; ?></div></div>
                            <div class="filter-day-value">
                            <?php   
                                    echo " <span title='".$this->lang->line('Draft Title')."'>".$this->lang->line('Filter Draft')." : <strong class='filter-due' id='weekly_created_count'>".$counts->weekly_created_count."</strong></span>";
                                    echo " <span title='".$this->lang->line('Created Title')."'> ".$this->lang->line('Filter Created')." : <strong class='filter-partial' id='weekly_partial_count'>". $counts->weekly_partial_count."</strong></span>";
                                    echo " <span title='".$this->lang->line('Sent Title')."'>".$this->lang->line('Filter Sent')." : <strong class='filter-paid' id='weekly_paid_count'>".$counts->weekly_paid_count."</strong></span>";
                                    
                            ?>
                            </div>
                            <div class="filter-day-value border-top"><?php echo $this->lang->line('Total Value'); ?> : <strong class="text-right" id="daily_total"><?php echo number_format($counts->weekly_total,2); ?></strong></div>
                            </button>

                            <button type="button" class="btn btn-outline-secondary navsearch" id="month" onclick="month()"><div class="filter-day-count  d-flex justify-content-between"><div><?php echo $this->lang->line('Month'); ?></div><div><?php echo $counts->monthly_count; ?></div></div>
                            <div class="filter-day-value">
                            <?php   
                                    echo " <span title='".$this->lang->line('Draft Title')."'>".$this->lang->line('Filter Draft')." : <strong class='filter-due' id='monthly_created_count'>".$counts->monthly_created_count."</strong></span>";
                                    echo " <span title='".$this->lang->line('Created Title')."'> ".$this->lang->line('Filter Created')." : <strong class='filter-partial' id='monthly_partial_count'>". $counts->monthly_partial_count."</strong></span>";
                                    echo " <span title='".$this->lang->line('Sent Title')."'>".$this->lang->line('Filter Sent')." : <strong class='filter-paid' id='monthly_paid_count'>".$counts->monthly_paid_count."</strong></span>";
                                    
                            ?>
                            </div>
                            <div class="filter-day-value border-top"><?php echo $this->lang->line('Total Value'); ?> : <strong class="text-right" id="daily_total"><?php echo number_format($counts->monthly_total,2); ?></strong></div>
                        </button>

                            <button type="button" class="btn btn-outline-secondary navsearch" id="quarter" onclick="quarter()"><div class="filter-day-count  d-flex justify-content-between"><div><?php echo $this->lang->line('Quarter'); ?></div><div><?php echo $counts->quarterly_count; ?></div></div>
                            <div class="filter-day-value">
                            <?php   
                                    echo " <span title='".$this->lang->line('Draft Title')."'>".$this->lang->line('Filter Draft')." : <strong class='filter-due' id='quarterly_created_count'>".$counts->quarterly_created_count."</strong></span>";
                                    echo " <span title='".$this->lang->line('Created Title')."'> ".$this->lang->line('Filter Created')." : <strong class='filter-partial' id='quarterly_partial_count'>". $counts->quarterly_partial_count."</strong></span>";
                                    echo " <span title='".$this->lang->line('Sent Title')."'>".$this->lang->line('Filter Sent')." : <strong class='filter-paid' id='quarterly_paid_count'>".$counts->quarterly_paid_count."</strong></span>";
                                    
                            ?>
                            </div>
                            <div class="filter-day-value border-top"><?php echo $this->lang->line('Total Value'); ?> : <strong class="text-right" id="daily_total"><?php echo number_format($counts->quarterly_total,2); ?></strong></div>
                        </button>

                            <button type="button" class="btn btn-outline-secondary navsearch" id="year" onclick="year()"><div class="filter-day-count  d-flex justify-content-between"><div><?php echo $this->lang->line('Year'); ?></div><div><?php echo $counts->yearly_count; ?></div></div>
                            <div class="filter-day-value">
                            <?php   
                                    echo " <span title='".$this->lang->line('Draft Title')."'>".$this->lang->line('Filter Draft')." : <strong class='filter-due' id='yearly_created_count'>".$counts->yearly_created_count."</strong></span>";
                                    echo " <span title='".$this->lang->line('Created Title')."'> ".$this->lang->line('Filter Created')." : <strong class='filter-partial' id='yearly_partial_count'>". $counts->yearly_partial_count."</strong></span>";
                                    echo " <span title='".$this->lang->line('Sent Title')."'>".$this->lang->line('Filter Sent')." : <strong class='filter-paid' id='yearly_paid_count'>".$counts->yearly_paid_count."</strong></span>";
                                    
                            ?>
                            </div>
                            <div class="filter-day-value border-top"><?php echo $this->lang->line('Total Value'); ?> : <strong class="text-right" id="daily_total"><?php echo number_format($counts->yearly_total,2); ?></strong></div>
                        </button>


                            <button type="button" class="btn btn-outline-secondary navsearch" id="customsection" onclick="customsection()"><?php echo $this->lang->line('CUSTOM'); ?></button>
                        </div>
                    </div>
                    <div class="col-12" id="custom-search">
                        <div class="form-group row">
                            <!-- <div class="col-12"><?php echo $this->lang->line('Quote Date') ?></div> -->
                            <div class="col-lg-9 col-md-7 col-sm-12 col-xs-12"></div>
                            <div class="col-lg-3 col-md-5 col-sm-12 col-xs-12">
                                <div class="row searchflds" >
                                    <div class="col-lg-4 col-md-5 col-sm-5 col-xs-12">
                                        <label for="start date" class='col-form-label'>Start Date</label>
                                        <input type="text" name="start_date" id="start_date" class="date30 form-control form-control-sm"
                                            autocomplete="off" />
                                    </div>
                                    <div class="col-lg-4 col-md-5 col-sm-5 col-xs-12">
                                        <label for="end_date" class='col-form-label'>End Date</label>
                                        <input type="text" name="end_date" id="end_date" class="form-control form-control-sm"
                                            data-toggle="datepicker" autocomplete="off" />
                                    </div>

                                    <div class="col-lg-1 col-md-1 col-sm-2 col-xs-12 customsearchbtn">
                                        <input type="button" name="search" id="search" value="Search" class="btn btn-secondary btn-sm"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ================ erp2024 newly added 28-06-2024 ends ============== -->
                <hr>
                <div class="table-container table-table-scroll1">
                    <table id="invoices_st" class="table table-striped table-bordered zero-configuration w-100">
                        <thead>
                        <tr>
                            <th><?php echo $this->lang->line('No') ?></th>
                            <th><?php echo $this->lang->line('Stock Return') ?></th>
                            <th><?php echo $this->lang->line('Supplier') ?></th>
                            <th class="text-center"><?php echo $this->lang->line('Date') ?></th>
                            <th class="text-center"><?php echo $this->lang->line('Purchase Receipt No') ?></th>
                            <th class="text-right"><?php echo $this->lang->line('Amount') ?></th>
                            <th class="text-center"><?php echo $this->lang->line('Status') ?></th>
                            <th class="text-center"><?php echo $this->lang->line('Approval Status') ?></th>
                            <th class="text-center"><?php echo $this->lang->line('Payment Status') ?></th>
                            <th class="no-sort"><?php echo $this->lang->line('Settings') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="delete_model" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php echo $this->lang->line('Delete Order') ?></h4>
            </div>
            <div class="modal-body">
                <p><?php echo $this->lang->line('delete this order') ?></p>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="object-id" value="">
                <input type="hidden" id="action-url" value="stockreturn/delete_i">
                <button type="button" data-dismiss="modal" class="btn btn-primary" id="delete-confirm">Delete</button>
                <button type="button" data-dismiss="modal" class="btn">Cancel</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">    
    var columnlist = [
            { 'width': '3%' }, 
            { 'width': '5%' },
            { 'width': '18%' }, 
            { 'width': '8%' },
            { 'width': '5%' },
            { 'width': '6%' },
            { 'width': '7%' },
            { 'width': '10%' },
            { 'width': '5%' },
            { 'width': '' }
        ];
    $(document).ready(function () {
        $("#custom-search").hide();
        $(".navsearch").removeClass("navbtn-active");
        week();
        
        // $('#invoices_st').parent().css({
        //     'max-width': '100%', 
        //     'overflow-x': 'scroll'
        // });
        $('#search').click(function () {
            var start_date = $('#start_date').val();
            var end_date = $('#end_date').val();
            if (start_date != '' && end_date != '') {
                $('#invoices_st').DataTable().destroy();
                draw_data(start_date, end_date);
            } else {
                alert("Date range is Required");
            }
        });
    });

    
    function customsection(){
        // $("#custom-search").show();
        $("#custom-search").show();
        $(".navsearch").removeClass("navbtn-active");
        $("#customsection").addClass("navbtn-active");
    }
    function today(){   
        $("#custom-search").hide(); 
        $(".navsearch").removeClass("navbtn-active");
        $("#today").addClass("navbtn-active");
        // var date = new Date();
        // var year = date.getFullYear();
        // var month = ("0" + (date.getMonth() + 1)).slice(-2); // Add leading zero
        // var day = ("0" + date.getDate()).slice(-2); // Add leading zero
        // var formattedDate = year + '-' + month + '-' + day;
        // var start_date = formattedDate;
        // var end_date = formattedDate;
        var start_date =  "<?php echo date('Y-m-d'); ?>";
        var end_date =  "<?php echo date('Y-m-d'); ?>";
        if (start_date != '' && end_date != '') {
            $('#invoices_st').DataTable().destroy();
            draw_data(start_date, end_date);
        } else {
            alert("Date range is Required");
        }
    }
    function yesterday(){    
        $("#custom-search").hide();
        $(".navsearch").removeClass("navbtn-active");
        $("#yesterday").addClass("navbtn-active");
        // var date = new Date();
        // date.setDate(date.getDate() - 1); // Subtract one day
        // var year = date.getFullYear();
        // var month = ("0" + (date.getMonth() + 1)).slice(-2); // Add leading zero
        // var day = ("0" + date.getDate()).slice(-2); // Add leading zero
        // var formattedDate = year + '-' + month + '-' + day;
        // var start_date = formattedDate;
        // var end_date = formattedDate;
        var start_date = "<?php echo date('Y-m-d', strtotime('-1 day')); ?>";
        var end_date = "<?php echo date('Y-m-d', strtotime('-1 day')); ?>";
        if (start_date != '' && end_date != '') {
            $('#invoices_st').DataTable().destroy();
            draw_data(start_date, end_date);
        } else {
            alert("Date range is Required");
        }
    }
    function week() {    
        $("#custom-search").hide();
        $(".navsearch").removeClass("navbtn-active");
        $("#week").addClass("navbtn-active");
        var start_date = "<?php echo date('Y-m-d', strtotime('-7 days')); ?>";
        var end_date = "<?php echo date('Y-m-d'); ?>";
        if (start_date != '' && end_date != '') {
            $('#invoices_st').DataTable().destroy();
            draw_data(start_date, end_date);
        } else {
            alert("Date range is required");
        }
    }

    function month() {
        $("#custom-search").hide();
        $(".navsearch").removeClass("navbtn-active");
        $("#month").addClass("navbtn-active");
        var start_date = "<?php echo date('Y-m-d', strtotime('-1 month')); ?>";
        var end_date = "<?php echo date('Y-m-d'); ?>";
        if (start_date != '' && end_date != '') {
            $('#invoices_st').DataTable().destroy();
            draw_data(start_date, end_date);
        } else {
            alert("Date range is required");
        }
    }

    function quarter() {
        $("#custom-search").hide();
        var date = new Date();
        $(".navsearch").removeClass("navbtn-active");
        $("#quarter").addClass("navbtn-active");
        var start_date = "<?php echo date('Y-m-d', strtotime('-3 month')); ?>";
        var end_date = "<?php echo date('Y-m-d'); ?>";
        if (start_date != '' && end_date != '') {
            $('#invoices_st').DataTable().destroy();
            draw_data(start_date, end_date);
        } else {
            alert("Date range is required");
        }
    }

    function year() {
        $("#custom-search").hide();
        var date = new Date();
        $(".navsearch").removeClass("navbtn-active");
        $("#year").addClass("navbtn-active");
        var start_date = "<?php echo date('Y-m-d', strtotime('-1 year')); ?>";
        var end_date = "<?php echo date('Y-m-d'); ?>";
        if (start_date != '' && end_date != '') {
            $('#invoices_st').DataTable().destroy();
            draw_data(start_date, end_date);
        } else {
            alert("Date range is required");
        }
    }
    function draw_data(start_date = '', end_date = '') {
        $('#invoices_st').DataTable({
            'processing': true,
            'serverSide': true,
            'stateSave': true,
            // responsive: true,
            <?php datatable_lang();?>
            'order': [],
            'ajax': {
                'url': "<?php echo site_url('stockreturn/ajax_list?t=0')?>",
                'type': 'POST',
                'data': {
                    '<?=$this->security->get_csrf_token_name()?>': crsf_hash,
                    start_date: start_date,
                    end_date: end_date
                }
            },
            'columnDefs': [
                {
                    'targets': [0],
                    'orderable': false,
                    'createdCell': function(td, cellData, rowData, row, col) {
                        addClassToColumns(td, col, ['text-center']);
                    }
                },
                {
                    'targets': [1, 3, 4, 6,7,8],
                    'createdCell': function(td, cellData, rowData, row, col) {
                        addClassToColumns(td, col, ['text-center']);
                    }
                },
                {
                    'targets': [5,6],
                    'createdCell': function(td, cellData, rowData, row, col) {
                        addClassToColumns(td, col, ['text-right']);
                    }
                }
            ],
            'columns': columnlist,
            dom: '<"results-and-buttons-wrapper"lfB>rtip',
            buttons: [
                {
                    text: 'Results from...',
                    className: 'results-from',
                    action: function(e, dt, node, config) {
                        // Optional: Add custom action here if needed
                    }
                },
                {
                    extend: 'excelHtml5',
                    footer: true,
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5]
                    }
                }
            ]
        });
        startsection = "";
        endsection = "";
        if(start_date ==''){       
            $('#invoices_st_length label').css('margin-top', '5px');
        }
        if(start_date !=''){
            start_date = date_convert(start_date);
            startsection = "<label>Results from : </label><strong> "+start_date+"</strong>";        
            $('#invoices_st_length label').css('margin-top', '10px');
        }
        if(end_date!=''){
            end_date = date_convert(end_date);
            endsection = " - <strong> "+end_date+"</strong>";
        }
        if(start_date==end_date){
            endsection= "";
        }
        $('.results-from').html(startsection + endsection);
    }

    function date_convert(date_var) {
        if (/^\d{2}-\d{2}-\d{4}$/.test(date_var)) {
            return date_var; // Return the date_var as is
        }
        var parts = date_var.split('-');
        
        // Check if the split resulted in the correct number of parts
        if (parts.length !== 3) {
            console.error('Invalid date format:', date_var);
            return null; // Or handle error as needed
        }
        
        var year = parts[0];
        var month = parts[1];
        var day = parts[2];
        var dateObject = new Date(year, month - 1, day); // month - 1 because months are zero-indexed
        
        // Format the date to d-m-Y format
        var formattedDate = ('0' + day).slice(-2) + '-' + ('0' + month).slice(-2) + '-' + year;
        
        return formattedDate;
    }
   
</script>