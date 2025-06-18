<article class="content">
    <?php       
        // if (($msg = check_permission($permissions)) !== true) {
        //     echo $msg;
        //     return;
        // }       
       
    ?>
    <div class="card card-block">
        <div id="notify" class="alert alert-success" style="display:none;">
            <a href="#" class="close" data-dismiss="alert">&times;</a>

            <div class="message"></div>
        </div>
        <div class="card-header border-bottom">            
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard') ?></a></li>
                  
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line('Purchase') . ' - ' .$this->lang->line('sales') . ' ' . $this->lang->line('Report') ?></li>
                </ol>
            </nav>
            <h4 class="card-title"><?php echo $this->lang->line('Purchase') . ' - ' .$this->lang->line('sales') . ' ' . $this->lang->line('Report') ?> </h4>
            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
            <div class="heading-elements">
                <ul class="list-inline mb-0">
                    <li><a class="breaklink" data-action="expand"><i class="ft-maximize"></i></a></li>
                </ul>
            </div>
          
        </div>
        
        <div class="card-body">
           
        <div class="row">
                    <div class="col-12">
                        <div class="card1 sameheight-item">
                            <?php $oneMonthBefore = date('Y-m-d', strtotime('-1 month')); ?>
                            <form action="<?php echo base_url() ?>sales/saleviewstatement" method="post"
                                  role="form">
                                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                                       value="<?php echo $this->security->get_csrf_hash(); ?>">
                                <div class="form-group row">
                                    <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                        <label class="col-form-label" for="pay_cat"><?php echo $this->lang->line('Product Code / ID') ?></label>
                                        <input type="text" class="form-control" name="product_name" placeholder="<?php echo $this->lang->line('Enter Product Code / ID') ?>" id='productcode' value="<?=$filter[4]?>" style="width:100% !important;">
                                        <input type="hidden" value="search" id="billtype">
                                        <input type="hidden" class="pdIn" name="pid" id="pid-0" value="0">                                      
                                        
                                    </div>
                                    <div class="col-lg-3 col-md-8 col-sm-12 col-xs-12">
                                        <label class="col-form-label" for="pay_cat"><?php echo $this->lang->line('Product') ?></label>
                                        <input type="text" class="form-control" name="product_name"  id='productname' placeholder="Search by product description" value="<?=$filter[3]?>" style="width:100% !important;">
                                    </div>

                                    <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label"
                                           for="sdate"><?php echo $this->lang->line('From Date') ?><span class="compulsoryfld">*</span></label>
                                       
                                        <input type="date" name="sdate"  value="<?= $oneMonthBefore ?>" class="form-control required">
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label"
                                           for="edate"><?php echo $this->lang->line('To Date') ?><span class="compulsoryfld">*</span></label>
                                            <input type="date" name="edate"  value="<?= date('Y-m-d') ?>" class="form-control required">
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12 submit-section mt-1">
                                        <input type="submit" class="btn btn-crud btn-primary btn-lg" value="Get">
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>

                </div>
                <hr>
            <input type="hidden" value="search" id="billtype">
            <input type="hidden" class="pdIn" name="pid" id="prodid-0" value="<?php echo $filter[0] ?>">
            <input type="hidden"  name="product_code" id="product_code" value="<?php echo $filter[4] ?>">             
            <input type="hidden" name="startdt" id="startdt" value="<?php echo $filter[1] ?>">
            <input type="hidden" name="enddt" id="enddt" value="<?php echo $filter[2] ?>">           
            <h4><?php echo $this->lang->line('Product') ?> : <?php echo $filter[3] ?></h4>
            <h6><?php echo $this->lang->line('Product Code') ?> : <?php echo $filter[4] ?></h6>
            <h6><?php echo $this->lang->line('Item Description') ?> : <?php echo $filter[5] ?></h6>
            <h6><?php echo $this->lang->line('Stock Quantity') ?> : <?php echo $filter[6] ?></h6>
            <h6>From : <?php echo $filter[1] ?> To <?php echo $filter[2] ?></h6>
           <hr>
        <table id="salesorders" class="table table-striped table-bordered zero-configuration" style="width:100%;">
                    <thead>
                    <tr>
                        <th class="text-center"><?php echo $this->lang->line('No') ?></th>                        
                        <th><?php echo $this->lang->line('Date') ?></th>
                        <th><?php echo $this->lang->line('Transaction Type') ?></th>

                        <th class="text-center"><?php echo $this->lang->line('Quantity') ?></th>
                        <th class="text-right"><?php echo $this->lang->line('Cost') ?></th>
                        <th class="text-center"><?php echo $this->lang->line('OnHand Before') ?></th>
                        <th class="text-center"><?php echo $this->lang->line('OnHand After') ?></th>
                        <th class="no-sort text-center"><?php echo $this->lang->line('Amount') ?></th>
                       
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>

                    <tfoot>
                    <tr>
                        <th class="text-center"><?php echo $this->lang->line('No') ?></th>    

                        <th><?php echo $this->lang->line('Date') ?></th>
                        <th><?php echo $this->lang->line('Transaction Type') ?></th>

                        <th class="text-center"><?php echo $this->lang->line('Quantity') ?></th>
                        <th class="text-center"><?php echo $this->lang->line('Cost') ?></th>
                        
                        <th class="no-sort text-center"><?php echo $this->lang->line('OnHand Before') ?></th>
                        <th class="no-sort text-center"><?php echo $this->lang->line('OnHand After') ?></th>
                        <th class="no-sort text-center"><?php echo $this->lang->line('Amount') ?></th>

                    </tr>
                    </tfoot>
        </table>
        </div>
    </div>
</article>

<script type="text/javascript">
    var columnlist = [
        { 'width': '5%' }, 
        { 'width': '8%' },
        { 'width': '8%' },
        { 'width': '15%' }, 
        { 'width': '10%' }, 
        { 'width': '12%' },
        { 'width': '10%' },
        { 'width': '' }
    ];
    $(document).ready(function () {
    
   

    
 
   
    ////////////////////////Amount Range//////////////////////////
    // Set default start and end dates
        var startDate = moment().startOf('month'); // Start of the current month
        var endDate = moment().endOf('month'); // End of the current month

        $('#daterange').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear',
                format: 'DD-MM-YYYY'
            },
            opens: 'left', // Adjust the opening direction (left, right, etc.)
            alwaysShowCalendars: true,
            showDropdowns: true,
        });

        // Clear the input when the cancel button is clicked
        $('#daterange').on('cancel.daterangepicker', function(ev, picker){
            $(this).val('');
            $("#filter_expiry_date_from").val('');
            $("#filter_expiry_date_to").val('');
        });

        // Set the value of the textbox when the apply button is clicked
        $('#daterange').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
            $("#filter_expiry_date_from").val(picker.startDate.format('DD-MM-YYYY'));
            $("#filter_expiry_date_to").val(picker.endDate.format('DD-MM-YYYY'));
        });

        
        // search by count 15-10-2024 starts
        $("#filter_search_btn").on('click', function(e) {
            e.preventDefault();
            $('#salesorders').DataTable().destroy();
            draw_data("", "");
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: baseurl + 'SalesOrders/get_salesorder_count_filter',
                data :{
                    filter_status: $('#filter_status').val(),
                    // filter_employee: $('#filter_employee').val(),
                    filter_expiry_date_from: $('#filter_expiry_date_from').val(),
                    filter_expiry_date_to: $('#filter_expiry_date_to').val(),
                    filter_customer: $('#filter_customer').val(),
                    // filter_customertype: $('#filter_customertype').val(),
                    filter_price_from: $('#filter_price_from').val(),
                    filter_price_to: $('#filter_price_to').val()
                },
                success: function(response) {
                    data = response.data;
                    // Assuming 'data' is the object containing the response
                    $("#yearly_count").text(data.yearly_count);
                    $("#quarterly_count").text(data.quarterly_count);
                    $("#monthly_count").text(data.monthly_count);
                    $("#weekly_count").text(data.weekly_count);
                    $("#daily_count").text(data.daily_count);

                    $("#yearly_total").text(data.yearly_total);
                    $("#quarterly_total").text(data.quarterly_total);
                    $("#monthly_total").text(data.monthly_total);
                    $("#weekly_total").text(data.weekly_total);
                    $("#daily_total").text(data.daily_total);  

                },
                error: function(xhr, status, error) {
                    // Handle error
                    console.error(xhr.responseText);
                }
            });            
            
        });
        // search by count 15-10-2024 ends

        // erp2024 filter search ends 

        $("#custom-search").hide();
        $(".navsearch").removeClass("navbtn-active");
        // draw_data();
        week();
        // $('#salesorders').parent().css({
        //     'max-width': '100%', // Set the maximum height as per your requirement
        //     'overflow-x': 'scroll'
        // });
        $('#search').click(function () {
            var start_date = $('#start_date').val();
            var end_date = $('#end_date').val();
            if (start_date != '' && end_date != '') {
                $('#salesorders').DataTable().destroy();
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
        $('#salesorders').DataTable().destroy();
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
        $('#salesorders').DataTable().destroy();
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
        $('#salesorders').DataTable().destroy();
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
        $('#salesorders').DataTable().destroy();
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
        $('#salesorders').DataTable().destroy();
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
        $('#salesorders').DataTable().destroy();
        draw_data(start_date, end_date);
    } else {
        alert("Date range is required");
    }
}


// $("#ui-id-1").on('change',function(){
//     alert(9);
// });
$("#status_search").on('change',function(){
 
    
    $("#custom-search").hide();
    $(".navsearch").removeClass("navbtn-active");
    $("#week").addClass("navbtn-active");
    var selectedStatus = $(this).val();
    var date = new Date();
    var year = date.getFullYear();
    var month = ("0" + (date.getMonth() + 1)).slice(-2); // Add leading zero
    var day = ("0" + date.getDate()).slice(-2); // Add leading zero
    var formattedDate = year + '-' + month + '-' + day;
    var end_date = formattedDate; // Today's date

    // Get date 7 days ago
    date.setDate(date.getDate() - 7);
    year = date.getFullYear();
    month = ("0" + (date.getMonth() + 1)).slice(-2); 
    day = ("0" + date.getDate()).slice(-2);
    var start_date = year + '-' + month + '-' + day;
    if (start_date != '' && end_date != '') {
        $('#salesorders').DataTable().destroy();
        draw_data(start_date, end_date);
    } else {
        alert("Date range is required");
    }
    
});
function draw_data(start_date = '', end_date = '') {
    // alert('here');
    var selected_status = $('#status_search').val();
    var prid = $('#prodid-0').val();
    var product_code = $('#product_code').val();
    start_date = $('#startdt').val();
    end_date = $('#enddt').val();
    $('#salesorders').DataTable({
        'processing': true,
        'serverSide': true,
        'stateSave': true,
        // responsive: true,
        <?php datatable_lang();?>
        'order': [],
        'ajax': {
            'url': "<?php echo site_url('Sales/ajax_list')?>",
            'type': 'POST',
            'data': {
                '<?=$this->security->get_csrf_token_name()?>': crsf_hash,
                start_date: start_date,
                end_date: end_date,
                eid: prid,
                product_code: product_code,              
            }
        },
        'columnDefs': [
            {
                'targets': [0,6],
                'orderable': false,  // Only disable ordering for column 0
                'createdCell': function(td, cellData, rowData, row, col) {
                    addClassToColumns(td, col, ['text-center']);
                }
            },
            {
                'targets': [1, 4,3],
                'createdCell': function(td, cellData, rowData, row, col) {
                    addClassToColumns(td, col, ['text-center']);
                }
            },
            {
                'targets': [5],
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
        $('#salesorders_length label').css('margin-top', '5px');
    }
    if(start_date !=''){
        start_date = date_convert(start_date);
       // startsection = "<label>Results from : </label><strong> "+start_date+"</strong>";        
        $('#salesorders_length label').css('margin-top', '10px');
    }
    if(end_date!=''){
        end_date = date_convert(end_date);
       // endsection = " - <strong> "+end_date+"</strong>";
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
<!-- <script type="text/javascript">


    $(document).ready(function () {


        $('#entries').DataTable({
            "processing": true, // Show processing indicator
            "serverSide": true, // Enable server-side processing
            "ajax": {
                "url": baseurl + 'reports/customerstatements',
                "type": "POST",
                "data": function(d) {  // Use function to add dynamic data
                    d.ac = '<?php echo $filter[0]; ?>';
                    d.sd = '<?php echo $filter[2]; ?>';
                    d.ed = '<?php echo $filter[3]; ?>';
                    d.ty = '<?php echo $filter[1]; ?>';
                    d['<?php echo $this->security->get_csrf_token_name(); ?>'] = crsf_hash;
                }
            },
            columnDefs: [
                { targets: [4], orderable: false },
                { searchable: true, targets: [0, 1, 2,3] } // Example for first three columns
            ],
            dom: '<"results-and-buttons-wrapper"lfB>rtip',
            buttons: [
                
                {
                    extend: 'excelHtml5',
                    footer: true,
                    exportOptions: {
                        columns: [0,1, 2, 3, 4]
                    }
                },
                // {
                //     extend: 'pdfHtml5',
                //     footer: true,
                //     exportOptions: {
                //         columns: [0, 1, 2, 3, 4], // Add a comma here
                //         format: {
                //             body: function (data, row, column, node) {
                //                 return data; // Return data as is
                //             }
                //         }
                //     }
                // }

            ],
            "columns": [
                { "data": "date", "width": "20%", "className": "text-center" },
                { "data": "note", "width": "25%" },
                { "data": "debit", "className": "text-right" },
                { "data": "credit", "className": "text-right" },
                { "data": "balance", "className": "text-right" }
            ],
            "error": function(xhr, status, error) {
                $('#response').html('Error: ' + error);
            }
        });
    });
</script> -->
