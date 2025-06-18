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
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line('Delivery Notes') ?></li>
                </ol>
            </nav>
            
         <h4 class="card-title"><?php echo $this->lang->line('Delivery Notes') ?> <button class="btn btn-primary btn-sm rounded" type="button" id="mergedeliverynotebtn">Merge & Convert to Invoice</button> <a href="<?php echo base_url('deliverynotes/create') ?>" class="btn btn-primary btn-sm rounded"><?php echo $this->lang->line('Add new') ?></a></h4>
         <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
         <div class="heading-elements">
            <ul class="list-inline mb-0">
                <li class="filter_search_li"><a class="expand-btn breaklink" data-target=".filter_list_section"><span class="fa fa-filter"></span> <?php echo $this->lang->line('Filter Search') ?> <i class="fa fa-angle-down"></i></a></li>
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
            <!-- ----erp2024 Filter search 1610-2024 starts ------------- -->
            <div class="whole_filter_section filter_list_section mb-1">
                <div class="row mb-2">
                    <div class="col-12 " >
                        <h5><?php echo $this->lang->line('Filter Search') ?></h5>
                    </div>
                
                    <div class="col-xl-2 col-lg-4 col-md-4 col-sm-12 filterall">
                        <label for="filter_category" class='col-form-label'><?php echo $this->lang->line('Delivery Note Validity') ?></label>
                        <input type="text" id="daterange" name="daterange" class="form-control filter_element" autocomplete="off" >
                        <input type="hidden" name="filter_expiry_date_from" id="filter_expiry_date_from" class="form-control filter_element">
                        <input type="hidden" name="filter_expiry_date_to" id="filter_expiry_date_to" class="form-control filter_element">
                    </div>
                     
                    <div class="col-xl-2 col-lg-4 col-md-4 col-sm-12 filterall">
                        <label for="filter_category" class='col-form-label'><?php echo $this->lang->line('Sales Order Number') ?></label>
                        <input type="text" name="salesorder_number" id="salesorder_number" class="form-control filter_element" placeholder="Enter Sales Order No.">
                    </div>
                    
                    <div class="col-xl-3 col-lg-4 col-md-4 col-sm-12 filterall">
                        <label for="filter_customer" class='col-form-label'><?php echo $this->lang->line('Customers') ?></label>
                        <select name="filter_customer" id="filter_customer" class="form-control form-select breaklink filter_select" multiple="multiple">
                            <?php
                                if(!empty($customers))
                                {
                                    foreach ($customers as $key => $value) {
                                        echo "<option value='".$value['id']."'>".ucwords($value['name'])." - #".$value['id']."</option>";
                                    }
                                }
                            ?>
                        </select>
                    </div>    

                    <div class="col-xl-3 col-lg-4 col-md-4 col-sm-12 filterall">
                        <label for="filter_category" class='col-form-label'><?php echo $this->lang->line('Amount Range') ?></label>
                        <div class="price-range-block" style="margin-top:10px;">
                            <div id="slider-range" class="price-filter-range" name="rangeInput"></div>
                            <div>
                                <input type="hidden" min=0  value="0" id="product_price_min" class="price-range-field" />
                                <input type="hidden" min=0  value="0" id="product_price_max" class="price-range-field" />
                                
                                <input type="hidden"  value="0" id="filter_price_from" class="price-range-field filter_element" />
                                <input type="hidden"  value="0" id="filter_price_to" class="price-range-field filter_element" />
                            </div>
                            <div id="searchResults" class="search-results-block col-form-label"></div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-4 col-sm-12 filterall">
                        <label for="filter_status" class='col-form-label'><?php echo $this->lang->line('Status') ?></label>
                        <select name="filter_status" id="filter_status" class="form-control form-select breaklink filter_select" multiple="multiple">                                
                            <option value="Created">Created</option>
                            <option value="Completed">Completed</option>
                            <option value="Invoiced">Invoiced</option>
                            <option value="In Progress">In Progress</option>
                        </select>
                    </div>
                    <div class="col-xl-2 col-lg-4 col-md-4 col-sm-12">                       
                        <button class="btn btn-secondary mt-35 filter_clear_btn" type="submit" names="filter_clear_btn" id="filter_clear_btn">Reset</button>
                        <button class="btn btn-primary mt-35" type="submit" names="filter_search_btn" id="filter_search_btn">Search</button>
                    </div>
                </div>
            </div>  
            <!-- ----erp2024 Filter search 1610-2024 ends ------------- -->
            <!-- ================ erp2024 newly added 28-06-2024 stats ============== -->
            <?php
                $currentDate = new DateTime();
                $today = $currentDate->format('d-m-Y');
                $weekAgo = (clone $currentDate)->modify('-7 days')->format('d-m-y');
                $monthAgo = (clone $currentDate)->modify('-1 month')->format('d-m-y');
                $quarterAgo = (clone $currentDate)->modify('-4 months')->format('d-m-y');
                $yearAgo = (clone $currentDate)->modify('-1 year')->format('d-m-y');
                $monthstart    = $ranges['month'];
                $weekstart     = $ranges['week'];
                $quarterstart  = $ranges['quarter'];
                $yearstart     = $ranges['year'];
            ?>
                <div class="text-right tablet-scroll">
                    <i class="responsive-filter-icon fa fa-filter"></i>&nbsp;&nbsp;
                    <div class="btn-quick-view filter-group">
                        <button type="button" class="btn btn-crud btn-outline-secondary navsearch" id="today" onclick="today()"><div class="filter-day-count  d-flex justify-content-between"><div><?php echo $this->lang->line('Today'); ?></div><div id="daily_count"><?php echo $counts->daily_count; ?></div></div>
                        <div class="filter-day-value">
                            <?php   
                                    echo " <span title='".$this->lang->line('Draft Title')."'>".$this->lang->line('Filter Draft')." : <strong class='filter-danger' id='daily_draft_count'>".$counts->daily_draft_count."</strong></span>";
                                    echo " <span title='".$this->lang->line('Created Title')."'> ".$this->lang->line('Filter Created')." : <strong class='filter-info' id='daily_created_count'>". $counts->daily_created_count."</strong></span>";
                                    echo " <span title='".$this->lang->line('Progress Title')."'>".$this->lang->line('Filter Progress')." : <strong class='filter-progress' id='daily_progress_count'>".$counts->daily_progress_count."</strong></span>";
                                    echo " <span title='".$this->lang->line('Completed Title')."'>".$this->lang->line('Filter Completed')." : <strong class='filter-assigned' id='daily_assigned_count'>".$counts->daily_assigned_count."</strong></span>";
                            ?>
                            </div>
                            <div class="filter-day-value border-top"><?php echo $this->lang->line('Total Value'); ?> : <strong class="text-right" id="daily_total"><?php echo number_format($counts->daily_total,2); ?></strong></div>
                        </button>


                        <button type="button" class="btn btn-crud btn-outline-secondary navsearch" id="week" onclick="week()"><div class="filter-day-count  d-flex justify-content-between"><div><?php echo $this->lang->line('Week'); ?></div><div id="weekly_count"><?php echo $counts->weekly_count; ?></div></div>
                        <div class="filter-day-value">
                            <?php   
                                    echo " <span title='".$this->lang->line('Draft Title')."'>".$this->lang->line('Filter Draft')." : <strong class='filter-danger' id='weekly_draft_count'>".$counts->weekly_draft_count."</strong></span>";
                                    echo " <span title='".$this->lang->line('Created Title')."'> ".$this->lang->line('Filter Created')." : <strong class='filter-info' id='weekly_created_count'>". $counts->weekly_created_count."</strong></span>";
                                    echo " <span title='".$this->lang->line('Progress Title')."'>".$this->lang->line('Filter Progress')." : <strong class='filter-progress' id='weekly_progress_count'>".$counts->weekly_progress_count."</strong></span>";
                                    echo " <span title='".$this->lang->line('Completed Title')."'>".$this->lang->line('Filter Completed')." : <strong class='filter-assigned' id='weekly_assigned_count'>".$counts->weekly_assigned_count."</strong></span>";
                            ?>
                            </div>
                            <div class="filter-day-value border-top"><?php echo $this->lang->line('Total Value'); ?> : <strong class="text-right" id="weekly_total"><?php echo number_format($counts->weekly_total,2); ?></strong></div>
                        </button>

                        <button type="button" class="btn btn-crud btn-outline-secondary navsearch" id="month" onclick="month()"><div class="filter-day-count  d-flex justify-content-between"><div><?php echo $this->lang->line('Month'); ?></div><div id="monthly_count"><?php echo $counts->monthly_count; ?></div></div>

                        <div class="filter-day-value">
                            <?php   
                                    echo " <span title='".$this->lang->line('Draft Title')."'>".$this->lang->line('Filter Draft')." : <strong class='filter-danger' id='monthly_draft_count'>".$counts->monthly_draft_count."</strong></span>";
                                    echo " <span title='".$this->lang->line('Created Title')."'> ".$this->lang->line('Filter Created')." : <strong class='filter-info' id='monthly_created_count'>". $counts->monthly_created_count."</strong></span>";
                                    echo " <span title='".$this->lang->line('Progress Title')."'>".$this->lang->line('Filter Progress')." : <strong class='filter-progress' id='monthly_progress_count'>".$counts->monthly_progress_count."</strong></span>";
                                    echo " <span title='".$this->lang->line('Completed Title')."'>".$this->lang->line('Filter Completed')." : <strong class='filter-assigned' id='monthly_assigned_count'>".$counts->monthly_assigned_count."</strong></span>";
                            ?>
                            </div>
                            <div class="filter-day-value border-top"><?php echo $this->lang->line('Total Value'); ?> : <strong class="text-right" id="monthly_total"><?php echo number_format($counts->monthly_total,2); ?></strong></div>
                        </button>


                        <button type="button" class="btn btn-crud btn-outline-secondary navsearch" id="quarter" onclick="quarter()"><div class="filter-day-count  d-flex justify-content-between"><div><?php echo $this->lang->line('Quarter'); ?></div><div id="quarterly_count"><?php echo $counts->quarterly_count; ?></div></div>

                        <div class="filter-day-value">
                            <?php   
                                    echo " <span title='".$this->lang->line('Draft Title')."'>".$this->lang->line('Filter Draft')." : <strong class='filter-danger' id='quarterly_draft_count'>".$counts->quarterly_draft_count."</strong></span>";
                                    echo " <span title='".$this->lang->line('Created Title')."'> ".$this->lang->line('Filter Created')." : <strong class='filter-info' id='quarterly_created_count'>". $counts->quarterly_created_count."</strong></span>";
                                    echo " <span title='".$this->lang->line('Progress Title')."'>".$this->lang->line('Filter Progress')." : <strong class='filter-progress' id='quarterly_progress_count'>".$counts->quarterly_progress_count."</strong></span>";
                                    echo " <span title='".$this->lang->line('Completed Title')."'>".$this->lang->line('Filter Completed')." : <strong class='filter-assigned' id='quarterly_assigned_count'>".$counts->quarterly_assigned_count."</strong></span>";
                            ?>
                            </div>
                            <div class="filter-day-value border-top"><?php echo $this->lang->line('Total Value'); ?> : <strong class="text-right" id="quarterly_total"><?php echo number_format($counts->quarterly_total,2); ?></strong></div>
                        </button>


                        <button type="button" class="btn btn-crud btn-outline-secondary navsearch" id="year" onclick="year()"><div class="filter-day-count  d-flex justify-content-between"><div><?php echo $this->lang->line('Year'); ?></div><div id="yearly_count"><?php echo $counts->yearly_count; ?></div></div>
                        <div class="filter-day-value">
                            <?php   
                                    echo " <span title='".$this->lang->line('Draft Title')."'>".$this->lang->line('Filter Draft')." : <strong class='filter-danger' id='yearly_draft_count'>".$counts->yearly_draft_count."</strong></span>";
                                    echo " <span title='".$this->lang->line('Created Title')."'> ".$this->lang->line('Filter Created')." : <strong class='filter-info' id='yearly_created_count'>". $counts->yearly_created_count."</strong></span>";
                                    echo " <span title='".$this->lang->line('Progress Title')."'>".$this->lang->line('Filter Progress')." : <strong class='filter-progress' id='yearly_progress_count'>".$counts->yearly_progress_count."</strong></span>";
                                    echo " <span title='".$this->lang->line('Completed Title')."'>".$this->lang->line('Filter Completed')." : <strong class='filter-assigned' id='yearly_assigned_count'>".$counts->yearly_assigned_count."</strong></span>";
                            ?>
                            </div>
                            <div class="filter-day-value border-top"><?php echo $this->lang->line('Total Value'); ?> : <strong class="text-right" id="yearly_total"><?php echo number_format($counts->yearly_total,2); ?></strong></div>
                        </button>

                        <button type="button" class="btn btn-crud btn-outline-secondary navsearch" id="customsection" onclick="customsection()"><?php echo $this->lang->line('CUSTOM'); ?></button>
                    </div>
                </div>
                <div class="col-12" id="custom-search">
                    <div class="form-group row">
                        <!-- <div class="col-12"><?php echo $this->lang->line('Quote Date') ?></div> -->
                        <div class="col-lg-9 col-md-5 col-sm-12 col-xs-12"></div>
                        <div class="col-lg-3 col-md-7 col-sm-12 col-xs-12">
                            <div class="row searchflds" >
                                <div class="col-lg-4 col-md-5 col-sm-5 col-xs-12">
                                    <label for="start date" class='col-form-label'>Start Date</label>
                                    <input type="text" name="start_date" id="start_date" class="date30 form-control form-control-sm"
                                        autocomplete="off" />
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-5 col-xs-12">
                                    <label for="end_date" class='col-form-label'>End Date</label>
                                    <input type="text" name="end_date" id="end_date" class="form-control form-control-sm"
                                        data-toggle="datepicker" autocomplete="off" />
                                </div>

                                <div class="col-lg-1 col-md-3 col-sm-2 col-xs-12 customsearchbtn">
                                    <input type="button" name="search" id="search" value="Search" class="btn btn-crud btn-info btn-sm"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ================ erp2024 newly added 28-06-2024 ends ============== -->
            <!-- <div class="row">
               <div class="col-md-2">Delivery Date</div>
               <div class="col-md-2">
                  <input type="text" name="start_date" id="start_date"
                     class="date30 form-control form-control-sm" autocomplete="off"/>
               </div>
               <div class="col-md-2">
                  <input type="text" name="end_date" id="end_date" class="form-control form-control-sm"
                     data-toggle="datepicker" autocomplete="off"/>
               </div>
               <div class="col-md-2">
                  <input type="button" name="search" id="search" value="Search" class="btn btn-secondary btn-sm"/>
               </div>
            </div> -->

            <hr>
            <div class="table-container">
                <table id="DeliveryNotes" class="table table-striped table-bordered zero-configuration" style="width:100%;">
                <thead>
                    <tr>
                        <th class="text-center">
                            <!-- <input type="checkbox" id="prdcheckbox" name="delnotecheckbox" id="delnotecheckbox"> -->
                            <?php //echo $this->lang->line('SN') ?>
                        </th>
                        <th class="text-center"><?php echo $this->lang->line('Delivery Note') ?></th>
                        <th class="text-center"><?php echo $this->lang->line('Sales Order') ?></th>
                        <th class="text-center"><?php echo $this->lang->line('Due Date') ?></th>
                        <!-- <th style="width:50px;">  #</th> -->
                        <th> <?php echo $this->lang->line('Customer') ?> </th>
                        <th> <?php echo $this->lang->line('Warehouse') ?> </th>
                        <!-- <th> <?php echo $this->lang->line('Salesman') ?> </th> -->
                        <!-- <th style="width:100px;">Time</th> -->
                        <th class="text-right"><?php echo $this->lang->line('Amount') ?><?php //echo $this->config->item('currency'); ?></th>
                        <th class="text-center"><?php echo $this->lang->line('Status') ?></th>
                        <th class="text-center"><?php echo $this->lang->line('Related Status') ?></th>
                        <th><?php echo $this->lang->line('Action') ?></th>
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
            <h4 class="modal-title"><?php echo $this->lang->line('Delete Invoice') ?></h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
               aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body">
            <p><?php echo $this->lang->line('delete this invoice') ?> ?</p>
         </div>
         <div class="modal-footer">
            <input type="hidden" id="object-id" value="">
            <input type="hidden" id="action-url" value="invoices/delete_i">
            <button type="button" data-dismiss="modal" class="btn btn-primary" id="delete-confirm"><?php echo $this->lang->line('Delete') ?></button>
            <button type="button" data-dismiss="modal"
               class="btn"><?php echo $this->lang->line('Cancel') ?></button>
         </div>
      </div>
   </div>
</div>
<?php
// echo date('Y-m-d');
// echo "<br>\n";
// print gmdate("Y-m-d\TH:i:s\Z");
// phpinfo();

// Get the current date and time in New Zealand
// $currentDateTime = date('Y-m-d H:i:s');

// // Display the date and time
// echo "The current date and time in : $currentDateTime". "<br>\n";
// echo "Timezone: " . date_default_timezone_get() . "<br>\n";
// echo "Current Date and Time: " . date('Y-m-d H:i:s'). "<br>\n";
?>
<script type="text/javascript">
     var columnlist = [
        { 'width': '3%' }, 
        { 'width': '8%' }, 
        { 'width': '9%' },
        { 'width': '7%' }, 
        { 'width': '12%' },
        { 'width': '10%' },
        { 'width': '6%' },
        { 'width': '8%' },
        { 'width': '9%' },
        { 'width': '' }
    ];
   $(document).ready(function () {
            // erp2024 filter search
    $("#filter_status").select2({
        placeholder: "Type Status", 
        allowClear: true,
        width: '100%'
    });
    $("#filter_customer").select2({
        placeholder: "Type Customer", 
        allowClear: true,
        width: '100%'
    });



    ////////////////////////Amount Range//////////////////////////
    var minimum_price=0;
    var maximum_price =0;
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseurl + 'invoices/get_min_max_amount',
        data:{
            tablename : 'cberp_delivery_notes',
            tablefield : 'total_amount'
        },
        success: function(response) {
            var minimum_price = parseFloat(response.data['minimum']);
            var maximum_price = parseFloat(response.data['maximum'])+1;
            $("#product_price_min").val(minimum_price);
            $("#product_price_max").val(maximum_price);
            // Initialize the slider with the fetched values
            $("#slider-range").slider({
                range: true,
                orientation: "horizontal",
                min: parseFloat(minimum_price), // Use fetched minimum price
                max: parseFloat(maximum_price), // Use fetched maximum price
                values: [parseFloat(minimum_price), parseFloat(maximum_price)], // Set initial slider values
                step: 1,
                slide: function (event, ui) {
                    if (ui.values[0] == ui.values[1]) {
                        return false; // Prevent the slider from being set to the same value
                    }
                    $("#filter_price_from").val(ui.values[0]);
                    $("#filter_price_to").val(ui.values[1]);
                    $("#searchResults").text("Amount between " + ui.values[0]  +" "+ "and" + " "+ ui.values[1]);
                }
            });
            $("#searchResults").text("Amount between " + minimum_price  +" "+ "and" + " "+ maximum_price );
            // $("#filter_price_from").val($("#slider-range").slider("values", 0));
            // $("#filter_price_to").val(maximum_price);
            
        },
        error: function(xhr, status, error) {
            // Handle error
            console.error(xhr.responseText);
        }
    });
    
    //for showing range picker default
    $("#slider-range").slider({
        values: [0,0]
    });

    $("#slider-range").click(function () {

        var filter_price_from = $('#filter_price_from').val();
        var filter_price_to = $('#filter_price_to').val();

        $("#searchResults").text("Amount between " + filter_price_from  +" "+ "and" + " "+ filter_price_to);
    });
    
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
            $('#DeliveryNotes').DataTable().destroy();
            draw_data("", "");
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: baseurl + 'DeliveryNotes/get_deliverynote_count_filter',
                data :{
                    filter_status: $('#filter_status').val(),
                    // filter_employee: $('#filter_employee').val(),
                    filter_expiry_date_from: $('#filter_expiry_date_from').val(),
                    filter_expiry_date_to: $('#filter_expiry_date_to').val(),
                    filter_customer: $('#filter_customer').val(),
                    salesorder_number: $('#salesorder_number').val(),
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
    // /////////////////////// filter section ends ////////////

    var table="";
        $("#custom-search").hide();
        $(".navsearch").removeClass("navbtn-active"); 
        week();
       $('#search').click(function () {
           var start_date = $('#start_date').val();
           var end_date = $('#end_date').val();
           if (start_date != '' && end_date != '') {
               $('#DeliveryNotes').DataTable().destroy();
               draw_data(start_date, end_date);
           } else {
               alert("Date range is Required");
           }
       });
   });
   
   function reprint(delivery, salesorder, customer) {
       $.ajax({
           type: 'POST',
           url: baseurl + 'DeliveryNotes/reprintnote',
           data: {"delivery": delivery, "salesorder": salesorder, "customer": customer},
           datatype: "JSON",
           success: function (response) {
   
           },
           error: function (xhr, status, error) {
               // Handle error
               console.error(xhr.responseText);
           }
       });
   }

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
        $('#DeliveryNotes').DataTable().destroy();
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
        $('#DeliveryNotes').DataTable().destroy();
        draw_data(start_date, end_date);
    } else {
        alert("Date range is Required");
    }
}

function week() {    
    $("#custom-search").hide();
    $(".navsearch").removeClass("navbtn-active");
    $("#week").addClass("navbtn-active");
    var start_date = "<?php echo $weekstart; ?>";
    var end_date = "<?php echo date('Y-m-d'); ?>";
    if (start_date != '' && end_date != '') {
        $('#DeliveryNotes').DataTable().destroy();
        draw_data(start_date, end_date);
    } else {
        alert("Date range is required");
    }
}
  
function month() {
    $("#custom-search").hide();
    $(".navsearch").removeClass("navbtn-active");
    $("#month").addClass("navbtn-active");

    // Use PHP variables for the date range
    var start_date = "<?php echo $monthstart; ?>";
    var end_date = "<?php echo date('Y-m-d'); ?>";

    if (start_date != '' && end_date != '') {
        $('#DeliveryNotes').DataTable().destroy();
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
    // Today's date
    // var year = date.getFullYear();
    // var month = ("0" + (date.getMonth() + 1)).slice(-2); // Add leading zero
    // var day = ("0" + date.getDate()).slice(-2); // Add leading zero
    // var formattedDate = year + '-' + month + '-' + day;
    // var end_date = formattedDate; // Today's date    

    // // Date 3 month ago
    // var date1MonthAgo = new Date();
    // date1MonthAgo.setMonth(date1MonthAgo.getMonth() - 3);
    // var year1Month = date1MonthAgo.getFullYear();
    // var month1Month = ("0" + (date1MonthAgo.getMonth() + 1)).slice(-2); // Add leading zero
    // var day1Month = ("0" + date1MonthAgo.getDate()).slice(-2); // Add leading zero

    // var start_date = year1Month + '-' + month1Month + '-' + day1Month; // Date 1 month ago

    var start_date = "<?php echo $quarterstart; ?>";
    var end_date = "<?php echo date('Y-m-d'); ?>";
    if (start_date != '' && end_date != '') {
        $('#DeliveryNotes').DataTable().destroy();
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

    var start_date = "<?php echo $yearstart; ?>";
    var end_date = "<?php echo date('Y-m-d'); ?>";
    if (start_date != '' && end_date != '') {
        $('#DeliveryNotes').DataTable().destroy();
        draw_data(start_date, end_date);
    } else {
        alert("Date range is required");
    }
}
function draw_data(start_date = '', end_date = '') {
    table= $('#DeliveryNotes').DataTable({
        'processing': true,
        'serverSide': true,
        'stateSave': true,
        // responsive: true,
        <?php datatable_lang();?>
        'order': [],
        'ajax': {
            'url': "<?php echo site_url('DeliveryNotes/ajax_list')?>",
            'type': 'POST',
            'data': {
                '<?=$this->security->get_csrf_token_name()?>': crsf_hash,
                start_date: start_date,
                end_date: end_date,                
                filter_status: $('#filter_status').val(),
                filter_expiry_date_from: $('#filter_expiry_date_from').val(),
                filter_expiry_date_to: $('#filter_expiry_date_to').val(),
                filter_customer: $('#filter_customer').val(),
                filter_price_from: $('#filter_price_from').val(),
                filter_price_to: $('#filter_price_to').val(),
                salesorder_number: $('#salesorder_number').val(),
            }
        },
        'columnDefs': [
            {
                'targets': [0,3,8],
                'orderable': false,
                'createdCell': function(td, cellData, rowData, row, col) {
                    addClassToColumns(td, col, ['text-center']);
                }
            },
            {
                'targets': [1, 2,7],
                'createdCell': function(td, cellData, rowData, row, col) {
                    addClassToColumns(td, col, ['text-center']);
                }
            },
            {
                'targets': [6],
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
        $('#DeliveryNotes_length label').css('margin-top', '5px');
    }
    if(start_date !=''){
        start_date = date_convert(start_date);
        startsection = "<label>Results from : </label><strong> "+start_date+"</strong>";        
        $('#DeliveryNotes_length label').css('margin-top', '10px');
    }
    if(end_date!=''){
        end_date = date_convert(end_date);
        endsection = " - <strong> "+end_date+"</strong>";
    }
    if(start_date==end_date){
        endsection= "";
    }
    $('.results-from').html(startsection + endsection);
    addMissingTitles();
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

$('#mergedeliverynotebtn').on('click', function() {
    var selectedData = [];
    $('.checkeditems:checked').each(function() {
        selectedData.push($(this).val());            
    });
    // Proceed if selectedData is not empty
    if(selectedData.length > 0) {
        // Uncomment the following lines to enable the AJAX request
       
        $.ajax({
            url: baseurl + 'DeliveryNotes/check_customer_is_same',
            dataType: 'json',
            method: 'POST',
            data: {
                'selecteditems': selectedData
            },
            success: function(response) {
                if(response.status == 1) {
                    window.location.href = baseurl + 'invoices/create?dnids=multiple';
                    //window.location.href = baseurl + 'DeliveryNotes/convert_multiple_deliverynotes_to_invoice';
                }
                else{
                    Swal.fire({
                        text: "Merging multiple delivery notes into a single invoice is allowed only if they belong to the same customer and warehouse.",
                        icon: "error"
                    });
                }
            }
        });
   
    } else {
        Swal.fire({
            text: "Please select at least one item",
            icon: "info"
        });
    }
});

$('#delnotecheckbox').change(function(){
    if($(this).is(':checked')){
        $(".checkeditems").prop('checked', true);
    } 
    else {
        $(".checkeditems").prop('checked', false);
    }
});

$(document).ready(function() {
    
    $('#delnotecheckbox').on('change', function() {
        var rows = table.rows({ 'search': 'applied' }).nodes(); // Get all rows in the current page
        $('.checkeditems', rows).prop('checked', $(this).is(':checked')); // Set checked state based on master checkbox
    });

    // Handle click on individual checkboxes
    $('#DeliveryNotes tbody').on('change', '.checkeditems', function() {
        if (!$(this).is(':checked')) {
            // If any checkbox is unchecked, uncheck the master checkbox
            $('#delnotecheckbox').prop('checked', false);
        } else {
            // If all checkboxes are checked, check the master checkbox
            if ($('.checkeditems:checked', table.rows({ 'search': 'applied' }).nodes()).length === $('.checkeditems', table.rows({ 'search': 'applied' }).nodes()).length) {
                $('#delnotecheckbox').prop('checked', true);
            }
        }
    });
    });
</script>