<?php
$due = false;
if ($this->input->get('due')) {
    $due = true;
} 
// print_r($permissions); die();
?>
<div class="content-body">
    <?php       
        if (($msg = check_permission($permissions)) !== true) {
            echo $msg;
            return;
        }
    ?>
    <div class="card">      
        <div class="card-header">
            <h4 class="card-title">
                    <?php echo $this->lang->line('Clients'); ?>
                        <a href="<?php echo base_url('customers/create') ?>"
                        class="btn btn-primary btn-sm rounded addnewclass">
                        <?php echo $this->lang->line('Add new') ?></a>

                   <!-- &nbsp;&nbsp; -->
                    <span class="due-client-wrapper">                        
                        <input class="form-check-input due-client-checkbox" type="checkbox" value="0" id="due-client-checkbox"> 
                        <label for="due-client-checkbox">                      
                           <strong><?php echo $this->lang->line('Payments Due') ?></strong>
                        </label>                        
                    </span>
                   </h4>
                    <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                <div class="heading-elements">
                    <ul class="list-inline mb-0">
                        <li> <a href="#sendMail" data-toggle="modal" data-remote="false" class="btn btn-secondary btn-sm rounded"
                                data-lang="<?php echo $this->lang->line('Email Selected') ?>"> <span
                                    class="fa fa-envelope"></span>
                                <?php echo $this->lang->line('Email Selected') ?></a></li>
                        <li> <a href="#sendSmsS" data-toggle="modal" data-remote="false"
                                class="btn btn-secondary btn-sm rounded"
                                data-lang="<?php echo $this->lang->line('SMS Selected') ?>"> <span
                                    class="fa fa-mobile"></span>
                                <?php echo $this->lang->line('SMS Selected') ?></a></li>
                        <li><a id="delete_selected" href="#" class="btn btn-secondary btn-sm rounded"
                                data-lang="<?php echo $this->lang->line('Delete Selected') ?>"> <span
                                    class="fa fa-trash-o"></span>
                                <?php echo $this->lang->line('Delete Selected') ?></a></li>
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
                <div class="whole_filter_section filter_list_section">
                    <div class="row mb-2">
                    <div class="col-12 " >
                    <h5>Search Filter</h5>
                    </div>
                    
                    <div class="col-xl-2 col-lg-4 col-md-4 col-sm-12 filterall">
                            <label for="filter_category" class='col-form-label'>Registration Expired Date Range</label>
                            <input type="text" id="daterange" name="daterange" class="form-control filter_element" autocomplete="off" >
                            <input type="hidden" name="filter_expiry_date_from" id="filter_expiry_date_from" class="form-control filter_element">
                            <input type="hidden" name="filter_expiry_date_to" id="filter_expiry_date_to" class="form-control filter_element">
                            <!-- <div class="row">                          
                                <div class="col padding-right-0" ><input type="date" name="filter_registration_expired_from" id="filter_registration_expired_from" class="form-control filter_element" placeholder="Price From"></div>
                                <div class="col padding-left-0"><input type="date" name="filter_registration_expired_to" id="filter_registration_expired_to" class="form-control filter_element" placeholder="Price To"></div>
                            </div> -->
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-4 col-sm-12 filterall">
                            <label for="filter_category" class='col-form-label'>Credit Range</label><br>
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
                            <label for="filter_salesman" class='col-form-label'><?php echo $this->lang->line('Select Salesman') ?></label>
                            <select name="filter_salesman" id="filter_salesman" class="form-control form-select breaklink filter_select" multiple="multiple">
                                <option value=""><?php echo $this->lang->line('Select Salesman') ?></option>
                            </select>
                        </div>               
                        <div class="col-xl-2 col-lg-4 col-md-4 col-sm-12 filterall">
                            <label for="filter_status" class='col-form-label'>Status</label>
                            <select name="filterstatus" id="filterstatus" class="form-control from-select filter_select_normal">
                                <option value="">Select Status</option>
                                <option value="Enable">Enable</option>
                                <option value="Disable">Disable</option>
                            </select>
                        </div>
                        
                    
                        <div class="col-xl-2 col-lg-4 col-md-4 col-sm-12">                       
                            <button class="btn btn-secondary mt-28 filter_clear_btn" type="submit" names="filter_clear_btn" id="filter_clear_btn">Reset</button>
                            <button class="btn btn-primary mt-28" type="submit" names="filter_search_btn" id="filter_search_btn">Search</button>
                        </div>
                    </div>
                </div>  
                <hr>
                <div class="table-table-scroll" >
                    <table id="clientstable" class="table table-striped table-bordered zero-configuration w-100" >
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th><?php echo $this->lang->line('Photo') ?></th>
                                <th><?php echo $this->lang->line('Name') ?></th>
                                <?php if ($due) {
                                    echo '  <th>' . $this->lang->line('Due') . '</th>';
                                } ?>
                                <th><?php echo $this->lang->line('Address') ?></th>
                                <th><?php echo $this->lang->line('Email') ?></th>
                                <th><?php echo $this->lang->line('Phone') ?></th>
                                <th><?php echo $this->lang->line('Expiry Date') ?></th>
                                <th><?php echo $this->lang->line('Salesman') ?></th>
                                <th><?php echo "company / Available Credit Limit" ?></th>
                                <th><?php echo $this->lang->line('Status') ?></th>
                                <th><?php echo $this->lang->line('Settings') ?></th>


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

                <h4 class="modal-title">Delete Customer</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <p><?php echo $this->lang->line('are_you_sure_delete_customer') ?></p>
            </div>
            <div class="modal-footer">
                <input type="hidden" class="form-control" id="object-id" name="deleteid" value="0">
                <input type="hidden" id="action-url" value="customers/delete_i">
                <button type="button" data-dismiss="modal" class="btn btn-primary"
                    id="delete-confirm"><?php echo $this->lang->line('Delete') ?></button>
                <button type="button" data-dismiss="modal"
                    class="btn"><?php echo $this->lang->line('Cancel') ?></button>
            </div>
        </div>
    </div>
</div>

<div id="sendMail" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">

                <h4 class="modal-title"><?php echo $this->lang->line('Email Selected') ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>

            <div class="modal-body">
                <form id="sendmail_form"><input type="hidden"
                        name="<?php echo $this->security->get_csrf_token_name(); ?>"
                        value="<?php echo $this->security->get_csrf_hash(); ?>">



                    <div class="row">
                        <div class="col mb-1"><label for="shortnote"><?php echo $this->lang->line('Subject') ?></label>
                            <input type="text" class="form-control" name="subject" id="subject">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-1"><label for="shortnote"><?php echo $this->lang->line('Message') ?></label>
                            <textarea name="text" class="summernote" id="contents" title="Contents"></textarea>
                        </div>
                    </div>




                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                    data-dismiss="modal"><?php echo $this->lang->line('Close') ?></button>
                <button type="button" class="btn btn-primary"
                    id="sendNowSelected"><?php echo $this->lang->line('Send') ?></button>
            </div>
        </div>
    </div>
</div>

<div id="sendSmsS" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">

                <h4 class="modal-title"><?php echo $this->lang->line('SMS Selected') ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>

            <div class="modal-body">
                <form id="sendsms_form"><input type="hidden"
                        name="<?php echo $this->security->get_csrf_token_name(); ?>"
                        value="<?php echo $this->security->get_csrf_hash(); ?>">



                    <div class="row">
                        <div class="col mb-1"><label for="shortnote"><?php echo $this->lang->line('Message') ?></label>
                            <textarea name="message" class="form-control" rows="6" cols="60"></textarea>
                        </div>
                    </div>


                    <input type="hidden" id="action-url" value="communication/send_general">


                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                    data-dismiss="modal"><?php echo $this->lang->line('Close') ?></button>
                <button type="button" class="btn btn-primary"
                    id="sendSmsSelected"><?php echo $this->lang->line('Send') ?></button>
            </div>
        </div>
    </div>

</div>

<script type="text/javascript">

$(document).ready(function() {

   
    // alert(buttonCount);
    // /////////////////due clien section /////////////////
    if (window.location.href.indexOf('due=true') > -1) {
        $('#due-client-checkbox').prop('checked', true);
    }
    $('#due-client-checkbox').change(function() {
        if (this.checked) {
            window.location.href = baseurl + 'customers?due=true';
        } else {
            window.location.href = baseurl + 'customers';
        }
    });
    // /////////////////due clien section /////////////////


    $("#filter_salesman").select2({
        placeholder: "Type Saleman Name", 
        allowClear: true,
        width: '100%'
    });
    load_salesman();
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseurl + 'customers/min_max_creditlimit',
        success: function(response) {
            var minimum_credit = response.data['minimum'];
            var maximum_credit = response.data['maximum'];
            $("#minValue").text(minimum_credit);
            $("#maxValue").text(maximum_credit);
            // $("#filter_credit_rang_from1").attr("min", minimum_credit);
            // $("#filter_credit_rang_from1").attr("max", maximum_credit);
            $("#slider-range").slider({
                range: true,
                orientation: "horizontal",
                min: parseFloat(minimum_credit), // Use fetched minimum price
                max: parseFloat(maximum_credit), // Use fetched maximum price
                values: [parseFloat(minimum_credit), parseFloat(maximum_credit)], // Set initial slider values
                step: 1,
                slide: function (event, ui) {
                    if (ui.values[0] == ui.values[1]) {
                        return false; // Prevent the slider from being set to the same value
                    }
                    $("#filter_price_from").val(ui.values[0]);
                    $("#filter_price_to").val(ui.values[1]);
                    $("#searchResults").text("Credit between " + ui.values[0]  +" "+ "and" + " "+ ui.values[1]);
                }
            });
            $("#searchResults").text("Credit between " + minimum_credit  +" "+ "and" + " "+ maximum_credit );
            // $("#filter_price_from").val($("#slider-range").slider("values", 0));
            // $("#filter_price_to").val(maximum_credit);
                          
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

        $("#searchResults").text("Credit between " + filter_price_from  +" "+ "and" + " "+ filter_price_to);
    });

    //date filter
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


    var table="";
    $('.summernote').summernote({
        height: 100,
        toolbar: [
            // [groupName, [list of button]]
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
            ['fullscreen', ['fullscreen']],
            ['codeview', ['codeview']]
        ]
    });


    var columnDefs = [
    { 'width': '6%' }, 
    { 'width': '5%' },
    { 'width': '15%' }, 
    { 'width': '25%' },
    { 'width': '20%' },
    { 'width': '10%' },
    { 'width': '10%' },
    { 'width': '10%' },
    { 'width': '10%' },
    { 'width': '5%' },
    { 'width': '' }
    ];

    <?php if ($due): ?>
        var columnDefs = [
            { 'width': '4%' }, 
            { 'width': '52px' },
            { 'width': '15%' }, 
            { 'width': '15%' },
            { 'width': '22%' },
            { 'width': '13%' },
            { 'width': '10%' },
            { 'width': '10%' },
            { 'width': '5%' },
            { 'width': '5%' },
            { 'width': '5%' },
            { 'width': '' }
            ]; // Add an extra column when $due is true
            <?php endif; ?>
            table = $('#clientstable').DataTable({
                'processing': true,
                'serverSide': true,
                'stateSave': true,
                // responsive: true,
                <?php datatable_lang();?> 'order': [],
                'ajax': {
                'url': "<?php echo site_url('customers/load_list')?>",
                'type': 'POST',
                'data': function(d) {
                    // Add CSRF token
                    d['<?=$this->security->get_csrf_token_name()?>'] = crsf_hash;

                    // Conditionally add group (for 'due' filter) if needed
                    <?php if ($due): ?>
                        d.due = 'due';
                    <?php endif; ?>

                    // Add the filter_credit_rang_from value to the data being sent
                    d.filter_credit_rang_from = $("#filter_price_from").val();
                    d.filter_credit_rang_to = $("#filter_price_to").val();
                    d.filter_registration_expired_from  = $("#filter_expiry_date_from").val();
                    d.filter_registration_expired_to  = $("#filter_expiry_date_to").val();
                    d.filterstatus = $("#filterstatus").val();
                    d.filter_salesman = $("#filter_salesman").val();
                
                }
        },
        "columnDefs": [
            {
                'targets': [1], // Disable sorting for the first and second columns
                'orderable': false,
                'className': 'text-center' // Add class to center-align
            }
        ],
        'columns': columnDefs,
        dom: 'Blfrtip',
        buttons: [{
            extend: 'excelHtml5',
            footer: true,
            exportOptions: {
                columns: [0, 1, 2, 3, 4]
            }
        }],
        
    });
    


    $("#filter_search_btn").on('click', function(e) {
        e.preventDefault();
        hasUnsavedChanges = false;       
        table.ajax.reload();
    });

    // const permissions = <?php
    //     echo json_encode($permissions);
    // ?>;
    // if (Array.isArray(permissions) && permissions.length > 0) {
    //     const buttonsToDisplay = permissions.map(permission => permission.function);
    //     $(".btn-sm").hide();
    //     $(".btn-sm").each(function () {
    //         if (buttonsToDisplay.includes($(this).text().trim())) {
    //             $(this).show();
    //         }
    //     });
    // }

     // Extract button texts to hide
  

    $(document).on('click', "#delete_selected", function(e) {
        e.preventDefault();
        if ($("input[name='cust[]']:checked").length == 0) {
            // Show SweetAlert for no selection
            Swal.fire({
                icon: 'info',
                text: 'Please select at least one customer to delete.'
            });
            return; // Exit the function to prevent further execution
        }
        // SweetAlert Confirmation
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to delete",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, proceed!',
            cancelButtonText: "No - Cancel",
            reverseButtons: true,  
            focusCancel: true,      
            allowOutsideClick: false,  // Disable outside click
        }).then((result) => {
            if (result.isConfirmed) {
                if ($("#notify").length == 0) {
                    $("#c_body").html(
                        '<div id="notify" class="alert" style="display:none;"><a href="#" class="close" data-dismiss="alert">&times;</a><div class="message"></div></div>'
                    );
                }
                
                jQuery.ajax({
                    url: "<?php echo site_url('customers/delete_i')?>",
                    type: 'POST',
                    data: $("input[name='cust[]']:checked").serialize() +
                        '&<?=$this->security->get_csrf_token_name()?>=' + crsf_hash +
                        '<?php if ($due) echo "&due=true" ?>',
                    dataType: 'json',
                    success: function(data) {
                        $("input[name='cust[]']:checked").closest('tr').remove();
                        $("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
                        $("#notify").removeClass("alert-danger").addClass("alert-success").fadeIn();
                        $("html, body").animate({
                            scrollTop: $('#notify').offset().top
                        }, 1000);
                    }
                });
            }
        });
    });



    //uni sender
    $('#sendMail').on('click', '#sendNowSelected', function(e) {
        e.preventDefault();
        $("#sendMail").modal('hide');
        if ($("#notify").length == 0) {
            $("#c_body").html(
                '<div id="notify" class="alert" style="display:none;"><a href="#" class="close" data-dismiss="alert">&times;</a><div class="message"></div></div>'
            );
        }
        jQuery.ajax({
            url: "<?php echo site_url('customers/sendSelected')?>",
            type: 'POST',
            data: $("input[name='cust[]']:checked").serialize() + '&' + $("#sendmail_form")
                .serialize(),
            dataType: 'json',
            success: function(data) {
                $("#notify .message").html("<strong>" + data.status + "</strong>: " + data
                    .message);
                $("#notify").removeClass("alert-danger").addClass("alert-success").fadeIn();
                $("html, body").animate({
                    scrollTop: $('#notify').offset().top
                }, 1000);
            }
        });
    });

    $('#sendSmsS').on('click', '#sendSmsSelected', function(e) {
        e.preventDefault();
        $("#sendSmsS").modal('hide');
        if ($("#notify").length == 0) {
            $("#c_body").html(
                '<div id="notify" class="alert" style="display:none;"><a href="#" class="close" data-dismiss="alert">&times;</a><div class="message"></div></div>'
            );
        }
        jQuery.ajax({
            url: "<?php echo site_url('customers/sendSmsSelected')?>",
            type: 'POST',
            data: $("input[name='cust[]']:checked").serialize() + '&' + $("#sendsms_form")
                .serialize(),
            dataType: 'json',
            success: function(data) {
                $("#notify .message").html("<strong>" + data.status + "</strong>: " + data
                    .message);
                $("#notify").removeClass("alert-danger").addClass("alert-success").fadeIn();
                $("html, body").animate({
                    scrollTop: $('#notify').offset().top
                }, 1000);
            }
        });
    });
});
function load_salesman(){
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseurl + 'customers/salesman_list',
        success: function(response) {
            if (response.data) {
                $("#filter_salesman").html(response.data);
            } else {
                $("#filter_salesman").html('<option value="">No salesman available</option>');
            }                   
        },
        error: function(xhr, status, error) {
            // Handle error
            console.error(xhr.responseText);
        }
    });
}


</script>
