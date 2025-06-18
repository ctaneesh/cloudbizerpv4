
<div class="content-body">
<?php       
  if (($msg = check_permission($permissions)) !== true) {
     echo $msg;
     return;
  }
 ?>
 	
    <div class="row">
        <div class="col-xl-4 col-lg-6 col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <div class="media d-flex">
                            <div class="media-body text-left">
                                <h3 class="success1"><span id="dash_0"></span></h3>
                                <span><?php echo $this->lang->line('In Stock') ?></span>
                            </div>
                            <div class="align-self-center">
                                <i class="icon-rocket success1 font-large-2 float-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-6 col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <div class="media d-flex">
                            <div class="media-body text-left">
                                <h3 class="danger"><span id="dash_1"></span></h3>
                                <span class="danger"><?php echo $this->lang->line('Stock out') ?></span>
                            </div>
                            <div class="align-self-center">
                                <i class="icon-eyeglasses danger font-large-2 float-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-6 col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <div class="media d-flex">
                            <div class="media-body text-left">
                                <h3 class="purple1"><span id="dash_2"></span></h3>
                                <span><?php echo $this->lang->line('Total') ?></span>
                            </div>
                            <div class="align-self-center">
                                <i class="icon-pie-chart purple1 font-large-2 float-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title"><?php echo $this->lang->line('Products') ?> <a
                        href="<?php echo base_url('products/add') ?>"
                        class="btn btn-primary btn-sm">
                    <?php echo $this->lang->line('Add new') ?>
                </a>  </h4>
                <!-- <a href="<?php echo base_url('products/barcodeinvoke') ?>"
                        class="btn btn-primary btn-sm">
                    <?php echo "Barcode Generate" ?>
                </a>   -->
            </h5>
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
                <!-- <div > -->
                    <div class="whole_filter_section filter_list_section">
                        <h5>Search Filter</h5>
                        <div class="row">
                            <div class="col-xl-3 col-lg-4 col-md-4 col-sm-12 filterall">
                                <label for="filter_category" class='col-form-label'><?php echo $this->lang->line('Category') ?></label>
                                <select name="filter_category" id="filter_category" class="form-control form-select breaklink filter_select" multiple="multiple">
                                    <option value=""><?php echo $this->lang->line('Select Category') ?></option>
                                </select>
                            </div>

                            <div class="col-xl-2 col-lg-4 col-md-4 col-sm-12 filterall">
                                <label for="filter_category" class='col-form-label'><?php echo $this->lang->line('Price Range') ?></label>
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
                            <!-- <div class="row">                          
                                    <div class="col padding-right-0" ><input type="number" name="filter_price_from" id="filter_price_from" class="form-control filter_element" placeholder="Price From"></div>
                                    <div class="col padding-left-0"><input type="number" name="filter_price_to" id="filter_price_to" class="form-control filter_element" placeholder="Price To"></div>
                            </div> -->
                            </div>

                            <div class="col-xl-2 col-lg-4 col-md-4 col-sm-12 filterall">
                                <label for="filter_expiry" class='col-form-label'><?php echo $this->lang->line('Expiry Date Range') ?></label>
                                <input type="text" id="daterange" name="daterange" class="form-control filter_element" autocomplete="off" >
                                <input type="hidden" name="filter_expiry_date_from" id="filter_expiry_date_from" class="form-control filter_element">
                                <input type="hidden" name="filter_expiry_date_to" id="filter_expiry_date_to" class="form-control filter_element">
                            <!-- <div class="row">                          
                                    <div class="col padding-right-0" ><input type="date" name="filter_expiry_date_from" id="filter_expiry_date_from" class="form-control filter_element" placeholder="Price From"></div>
                                    <div class="col padding-left-0"><input type="date" name="filter_expiry_date_to" id="filter_expiry_date_to" class="form-control filter_element" placeholder="Price To"></div>
                            </div> -->
                            </div>

                            <div class="col-xl-2 col-lg-4 col-md-4 col-sm-12 filterall">
                                <label for="filter_category" class='col-form-label'><?php echo $this->lang->line('Available Date Range') ?></label>
                                <input type="text" id="avaialble_daterange" name="avaialble_daterange" class="form-control filter_element" autocomplete="off">
                                <input type="hidden" name="filter_available_date_from" id="filter_available_date_from" class="form-control filter_element" placeholder="Price From">
                                <input type="hidden" name="filter_available_date_to" id="filter_available_date_to" class="form-control filter_element" placeholder="Price To">
                            <!-- <div class="row">                          
                                    <div class="col padding-right-0" >
                                        <input type="date" name="filter_available_date_from" id="filter_available_date_from" class="form-control filter_element" placeholder="Price From">
                                    </div>
                                    <div class="col padding-left-0">
                                        <input type="date" name="filter_available_date_to" id="filter_available_date_to" class="form-control filter_element" placeholder="Price To">
                                    </div>
                            </div> -->
                            </div>

                            <div class="col-xl-3 col-lg-4 col-md-4 col-sm-12 filterall">
                                <label for="filter_brand" class='col-form-label'><?php echo $this->lang->line('Brands') ?></label>
                                <select name="filter_brand" id="filter_brand" class="form-control form-select breaklink filter_select" multiple="multiple">
                                    <option value="">Select Brand</option>
                                </select>
                            </div>

                            <div class="col-xl-3 col-lg-4 col-md-4 col-sm-12 filterall">
                                <label for="filter_manufacturer" class='col-form-label'><?php echo $this->lang->line('Manufacturers') ?></label>
                                <select name="filter_manufacturer" id="filter_manufacturer" class="form-control form-select breaklink filter_select" multiple="multiple">
                                    <option value="">Select Manufacturer</option>
                                </select>
                            </div>

                            <div class="col-xl-3 col-lg-4 col-md-4 col-sm-12 filterall">
                                <label for="filter_warehouse" class='col-form-label'><?php echo $this->lang->line('Warehouses') ?></label>
                                <select name="filter_warehouse" id="filter_warehouse" class="form-control form-select breaklink filter_select" multiple="multiple">
                                    <option value="">Select Warehouse</option>
                                </select>
                            </div>
                            
                            <div class="col-xl-2 col-lg-4 col-md-4 col-sm-12 filterall">
                                <div class="form-check mt-3">
                                    <input class="form-check-input" type="checkbox" value="0" id="filter_alerted_qty">
                                    <label class="form-check-label" for="filter_alerted_qty">
                                        <strong><?php echo $this->lang->line('Alerted Quantity') ?></strong>
                                    </label>
                                </div>
                            </div>
                        
                    
                            <div class="col-xl-2 col-lg-4 col-md-4 col-sm-12">
                            <button class="btn btn-secondary btn-crud mt-35 filter_clear_btn" type="submit" names="filter_clear_btn" id="filter_clear_btn">Reset</button>
                            <button class="btn btn-primary btn-crud mt-35" type="submit" names="filter_search_btn" id="filter_search_btn">Search</button>
                            </div>
                        </div>
                    </div>
                <!-- </div>     -->
                <hr>
                <div class="table-scroll">
                    <table id="productstable" class="table table-striped table-bordered zero-configuration dataTable">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th><?php echo $this->lang->line('Photo') ?></th>                     
                            <th><?php echo $this->lang->line('Code') ?></th>
                            <th><?php echo $this->lang->line('Description') ?></th>   
                            <th><?php echo $this->lang->line('Category') ?></th>
                            <th class="text-center"><?php echo $this->lang->line('On-hand Quantity') ?></th>
                            <th class="text-center"><?php echo $this->lang->line('Alert Quantity') ?></th>
                            <th class="text-right"><?php echo $this->lang->line('Price') ?></th>
                            <th class="text-center"><?php echo $this->lang->line('Date Available') ?></th>
                            <th class="text-center"><?php echo $this->lang->line('Status') ?></th>
                            <th><?php echo $this->lang->line('Settings') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>

                        <tfoot>
                        <tr>
                            <th>#</th>
                            <th><?php echo $this->lang->line('Photo') ?></th>                      
                            <th><?php echo $this->lang->line('Code') ?></th>
                            <th><?php echo $this->lang->line('Description') ?></th>  
                            <th><?php echo $this->lang->line('Category') ?></th>
                            <th class="text-center"><?php echo $this->lang->line('On-hand Quantity') ?></th>
                            <th class="text-center"><?php echo $this->lang->line('Alert Quantity') ?></th>
                            <th class="text-right"><?php echo $this->lang->line('Price') ?></th>
                            <th class="text-center"><?php echo $this->lang->line('Date Available') ?></th>
                            <th class="text-center"><?php echo $this->lang->line('Status') ?></th>
                            <th><?php echo $this->lang->line('Settings') ?></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>

            </div>
            <input type="hidden" id="dashurl" value="products/prd_stats">
        </div>
        </div>
        <script type="text/javascript">



            var table;
            $(document).ready(function () {
                $("#filter_category").select2({
                    placeholder: "Type Category Name", 
                    allowClear: true,
                    width: '100%'
                });
                $("#filter_brand").select2({
                    placeholder: "Type Brand Name", 
                    allowClear: true,
                    width: '100%'
                });
                $("#filter_manufacturer").select2({
                    placeholder: "Type Manufacturer Name", 
                    allowClear: true,
                    width: '100%'
                });
                $("#filter_warehouse").select2({
                    placeholder: "Type Warehouse Name", 
                    allowClear: true,
                    width: '100%'
                });
                $('#filter_alerted_qty').change(function() {
                    if ($(this).is(':checked')) {
                        $(this).val(1);  // Set value to 1 if checked
                    } else {
                        $(this).val(0);  // Set value to 0 if unchecked
                    }
                });
                load_category();
                load_brands();
                load_manufacturers();
                load_warehouse()
                var columnlist = [
                    { 'width': '4%', 'orderable': false },   
                    { 'width': '5%', 'orderable': false }, 
                    { 'width': '9%', 'orderable': true }, 
                    { 'width': '18%', 'orderable': true }, 
                    { 'width': '12%', 'orderable': true }, 
                    { 'width': '5%', 'orderable': true },
                    { 'width': '5%', 'orderable': true }, 
                    { 'width': '5%', 'orderable': true }, 
                    { 'width': '5%', 'orderable': true }, 
                    { 'width': '5%', 'orderable': true }, 
                    { 'width': '', 'orderable': false } 
                ];

                var table = $('#productstable').DataTable({
                    "processing": true, //Feature control the processing indicator.
                    "serverSide": true, //Feature control DataTables' server-side processing mode.
                    "order": [], //Initial no order.
                    // responsive: true,
                    <?php datatable_lang();?>
                    "ajax": {
                        "url": "<?php echo site_url('products/product_list')?>",
                        "type": "POST",
                        'data': function(d) {
                            // Add the filter_category value to the data being sent
                            d['<?=$this->security->get_csrf_token_name()?>'] = crsf_hash;
                            d.group = '<?=$this->input->get('group')?>';
                            d.filter_category = $("#filter_category").val(); // Pass the filter category
                            d.filter_brand = $("#filter_brand").val(); // Pass the filter brand
                            d.filter_manufacturer = $("#filter_manufacturer").val(); // Pass the filter manufacturer
                            d.filter_warehouse = $("#filter_warehouse").val(); // Pass the filter warehouse
                            d.filter_price_from = $("#filter_price_from").val(); //Pass Price from
                            d.filter_price_to = $("#filter_price_to").val(); //Pass Price To
                            d.filter_expiry_date_from = $("#filter_expiry_date_from").val(); //pass expiry from date
                            d.filter_expiry_date_to = $("#filter_expiry_date_to").val(); //pass expiry date to 
                            d.filter_available_date_from = $("#filter_available_date_from").val(); //pass avaialbe date from
                            d.filter_available_date_to = $("#filter_available_date_to").val(); // pass available date to
                            d.filter_alerted_qty = $("#filter_alerted_qty").val(); // pass available date to
                        }
                    },
                    'columnDefs': [
                        {
                            'targets': [0],
                            'orderable': false,  // Only disable ordering for column 0
                            'createdCell': function(td, cellData, rowData, row, col) {
                                addClassToColumns(td, col, ['text-center']);
                            }
                        },
                        {
                            'targets': [5,6,8,9],
                            'createdCell': function(td, cellData, rowData, row, col) {
                                addClassToColumns(td, col, ['text-center']);
                            }
                        },
                        {
                            'targets': [7],
                            'createdCell': function(td, cellData, rowData, row, col) {
                                addClassToColumns(td, col, ['text-right']);
                            }
                        }
                    ],
                    'columns': columnlist,
                    dom: 'Blfrtip',
                    buttons: [
                        {
                            extend: 'excelHtml5',
                            footer: true,
                            exportOptions: {
                                columns: [1, 2, 3, 4, 5, 6]
                            }
                        }
                    ],
                });

                $("#filter_search_btn").on('click', function(e) {
                    e.preventDefault();
                    hasUnsavedChanges = false;
                    var errorflg=1;

                    table.ajax.reload();
                });

                
                miniDash();


                $(document).on('click', ".view-object", function (e) {
                    e.preventDefault();
                    $('#view-object-id').val($(this).attr('data-object-id'));

                    $('#view_model').modal({backdrop: 'static', keyboard: false});

                    var actionurl = $('#view-action-url').val();
                    $.ajax({
                        url: baseurl + actionurl,
                        data: 'id=' + $('#view-object-id').val() + '&' + crsf_token + '=' + crsf_hash,
                        type: 'POST',
                        dataType: 'html',
                        success: function (data) {
                            $('#view_object').html(data);

                        }

                    });

                });
                // $('#productstable').parent().css({
                //         'max-width': '100%', // Set the maximum height as per your requirement
                //         'overflow-x': 'scroll'
                // });
            });
          

            function load_category(){
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: baseurl + 'products/category_list',
                    success: function(response) {
                        if (response.data) {
                            $("#filter_category").html(response.data);
                        } else {
                            $("#filter_category").html('<option value="">No categories available</option>');
                        }                   
                    },
                    error: function(xhr, status, error) {
                        // Handle error
                        console.error(xhr.responseText);
                    }
                });
            }
            function load_brands(){
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: baseurl + 'products/brand_list',
                    success: function(response) {
                        if (response.data) {
                            $("#filter_brand").html(response.data);
                        } else {
                            $("#filter_brand").html('<option value="">No Brands available</option>');
                        }                   
                    },
                    error: function(xhr, status, error) {
                        // Handle error
                        console.error(xhr.responseText);
                    }
                });
            }
            function load_manufacturers(){
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: baseurl + 'products/manufacturer_list',
                    success: function(response) {
                        if (response.data) {
                            $("#filter_manufacturer").html(response.data);
                        } else {
                            $("#filter_manufacturer").html('<option value="">No Manufacturers available</option>');
                        }                   
                    },
                    error: function(xhr, status, error) {
                        // Handle error
                        console.error(xhr.responseText);
                    }
                });
            }
            function load_warehouse(){
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: baseurl + 'products/warehouse_list',
                    success: function(response) {
                        if (response.data) {
                            $("#filter_warehouse").html(response.data);
                        } else {
                            $("#filter_warehouse").html('<option value="">No Manufacturers available</option>');
                        }                   
                    },
                    error: function(xhr, status, error) {
                        // Handle error
                        console.error(xhr.responseText);
                    }
                });
            }
            
            


            $(document).ready(function(){
	
                var minimum_price=0;
                var maximum_price =0;
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: baseurl + 'products/min_max_product_price',
                    success: function(response) {
                        var minimum_price = response.data['minimum'];
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
                                $("#searchResults").text("Price between " + ui.values[0]  +" "+ "and" + " "+ ui.values[1]);
                            }
                        });
                        $("#searchResults").text("Price between " + minimum_price  +" "+ "and" + " "+ maximum_price );
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

                    $("#searchResults").text("Price between " + filter_price_from  +" "+ "and" + " "+ filter_price_to);
                });
           
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
                $('#daterange').on('cancel.daterangepicker', function(ev, picker) {
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

                //For availanle daterange
            
                $('#avaialble_daterange').daterangepicker({
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
                $('#avaialble_daterange').on('cancel.daterangepicker', function(ev, picker) {
                    $(this).val('');
                });

                // Set the value of the textbox when the apply button is clicked
                $('#avaialble_daterange').on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
                    $("#filter_available_date_from").val(picker.startDate.format('DD-MM-YYYY'));
                    $("#filter_available_date_to").val(picker.endDate.format('DD-MM-YYYY'));
                });
            });


        </script>
        <div id="delete_model" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">

                        <h4 class="modal-title"><?php echo $this->lang->line('Delete') ?></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <p><?php echo $this->lang->line('delete this product') ?></p>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="object-id" value="">
                        <input type="hidden" id="action-url" value="products/delete_i">
                        <button type="button" data-dismiss="modal" class="btn btn-primary"
                                id="delete-confirm"><?php echo $this->lang->line('Delete') ?></button>
                        <button type="button" data-dismiss="modal"
                                class="btn"><?php echo $this->lang->line('Cancel') ?></button>
                    </div>
                </div>
            </div>
        </div>

        <div id="view_model" class="modal  fade">
            <div class="modal-dialog modal-lg">
                <div class="modal-content ">
                    <div class="modal-header">

                        <h4 class="modal-title"><?php echo $this->lang->line('View') ?></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body" id="view_object">
                        <p></p>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="view-object-id" value="">
                        <input type="hidden" id="view-action-url" value="products/view_over">

                        <button type="button" data-dismiss="modal"
                                class="btn"><?php echo $this->lang->line('Close') ?></button>
                    </div>
                </div>
            </div>
        </div>

