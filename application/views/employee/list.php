<div class="content-body">
    <?php       
        if (($msg = check_permission($permissions)) !== true) {
            echo $msg;
            return;
        }
    ?>
    <div class="card">
        <div class="card-header">
            <h5 class="title">
                <?php echo $this->lang->line('Employee') ?> <a href="<?php echo base_url('employee/add') ?>"
                                                               class="btn btn-primary btn-sm rounded">
                    <?php echo $this->lang->line('Add new') ?>
                </a>
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
                <div class="whole_filter_section filter_list_section">
                    <div class="row mb-2">
                        <div class="col-12 " >
                        <h5>Search Filter</h5>
                        </div>
                    
                        <div class="col-xl-2 col-lg-4 col-md-4 col-sm-12 filterall">
                            <label for="filter_category" class='col-form-label'><?php echo $this->lang->line('Residence Permit Expiry') ?></label>
                            <input type="text" id="daterange" name="daterange" class="form-control filter_element" autocomplete="off" >
                            <input type="hidden" name="filter_expiry_date_from" id="filter_expiry_date_from" class="form-control filter_element">
                            <input type="hidden" name="filter_expiry_date_to" id="filter_expiry_date_to" class="form-control filter_element">
                        </div>
                                    
                        
                        <div class="col-xl-2 col-lg-4 col-md-4 col-sm-12 filterall">
                                <label for="filter_category" class='col-form-label'><?php echo $this->lang->line('Passport Expiry') ?></label>
                                <input type="text" id="passport_daterange" name="passport_daterange" class="form-control filter_element" autocomplete="off">
                                <input type="hidden" name="filter_passport_date_from" id="filter_passport_date_from" class="form-control filter_element" placeholder="Price From">
                                <input type="hidden" name="filter_passport_date_to" id="filter_passport_date_to" class="form-control filter_element" placeholder="Price To">
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-4 col-sm-12 filterall">
                            <label for="filter_employee" class='col-form-label'><?php echo $this->lang->line('Reporting To') ?></label>
                            <select name="filter_employee" id="filter_employee" class="form-control form-select breaklink filter_select" multiple="multiple">
                                <?php
                                    if(!empty($employees))
                                    {
                                        foreach ($employees as $key => $value) {
                                            echo "<option value='".$value['id']."'>".ucwords($value['name'])."</option>";
                                        }
                                    }
                                ?>
                            </select>
                        </div>   
                        <div class="col-xl-3 col-lg-4 col-md-4 col-sm-12 filterall">
                            <label for="filter_warehouse" class='col-form-label'><?php echo $this->lang->line('Working At') ?></label>
                            <select name="filter_warehouse" id="filter_warehouse" class="form-control form-select breaklink filter_select" multiple="multiple">
                                <?php
                                    if(!empty($warehouses))
                                    {
                                        foreach ($warehouses as $key => $value) {
                                            echo "<option value='".$value['id']."'>".ucwords($value['title'])."</option>";
                                        }
                                    }
                                ?>
                            </select>
                        </div>   
                    
                        <div class="col-xl-2 col-lg-4 col-md-4 col-sm-12">                       
                            <button class="btn btn-crud btn-secondary mt-35 filter_clear_btn" type="submit" names="filter_clear_btn" id="filter_clear_btn">Reset</button>
                            <button class="btn btn-crud btn-primary mt-35" type="submit" names="filter_search_btn" id="filter_search_btn">Search</button>
                        </div>
                    </div>
                </div>  
                <hr>

                <div class="table-table-scroll1">
                    <table id="emptable" class="table table-striped table-bordered zero-configuration w-100">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th><?php echo $this->lang->line('Name') ?></th>
                            <th><?php echo $this->lang->line('Role') ?></th>
                            <th><?php echo $this->lang->line('Phone') ?></th>
                            <th><?php echo $this->lang->line('Email') ?></th>
                            <th><?php echo $this->lang->line('Permit Expiry') ?></th>
                            <th><?php echo $this->lang->line('Passport Expiry') ?></th>
                            <th><?php echo $this->lang->line('Working At') ?></th>                            
                            <th><?php echo $this->lang->line('Reporting To') ?></th>                            
                            <th><?php echo $this->lang->line('Status') ?></th>
                            <th><?php echo $this->lang->line('Actions') ?></th>


                        </tr>
                        </thead>
                        <tbody>
                        
                        </tbody>
                        
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#filter_employee").select2({
                placeholder: "Type Reported Person's Name", 
                allowClear: true,
                width: '100%'
            });
            $("#filter_warehouse").select2({
                placeholder: "Type Working At", 
                allowClear: true,
                width: '100%'
            });
            var table="";
            var columnlist = [
            { 'width': '3%' }, 
            { 'width': '18%' },
            { 'width': '10%' }, 
            { 'width': '8%' },
            { 'width': '10%' },
            { 'width': '6%' },
            { 'width': '6%' },
            { 'width': '8%' },
            { 'width': '8%' },
            { 'width': '7%' },
            { 'width': '' }
            ];
            //datatables
            table = $('#emptable').DataTable({
                // responsive: true, 
              
                'processing': true,
                'serverSide': true,
                'stateSave': true,
                // responsive: true,
                <?php datatable_lang();?> 'order': [],
                'ajax': {
                'url': "<?php echo site_url('employee/employeelist_ajax')?>",
                'type': 'POST',
                'data': function(d) {
                    // Add CSRF token
                    d['<?=$this->security->get_csrf_token_name()?>'] = crsf_hash;
                    // Add the filter_credit_rang_from value to the data being sent
                    d.filter_passport_date_from = $("#filter_passport_date_from").val();
                    d.filter_passport_date_to = $("#filter_passport_date_to").val();
                    d.filter_residence_permit_from  = $("#filter_expiry_date_from").val();
                    d.filter_residence_permit_to  = $("#filter_expiry_date_to").val();
                    d.filter_employee = $("#filter_employee").val();
                    d.filter_warehouse = $("#filter_warehouse").val();
                }
                },
                buttons: [
                    {
                        extend: 'excelHtml5',
                        footer: true,
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        }
                    }
                ],
                'columnDefs': [
                    {
                        'targets': [0],
                        'orderable': false,  // Only disable ordering for column 0
                        'createdCell': function(td, cellData, rowData, row, col) {
                            addClassToColumns(td, col, ['text-center']);
                        }
                    },
                    {
                        'targets': [3,5,6,9],
                        'createdCell': function(td, cellData, rowData, row, col) {
                            addClassToColumns(td, col, ['text-center']);
                        }
                    },
                    {
                        'targets': [],
                        'createdCell': function(td, cellData, rowData, row, col) {
                            addClassToColumns(td, col, ['text-right']);
                        }
                    }
                ],
                'columns': columnlist,
            });


            $("#filter_search_btn").on('click', function(e) {
                e.preventDefault();
                hasUnsavedChanges = false;       
                table.ajax.reload();
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

                
                $('#passport_daterange').daterangepicker({
                    autoUpdateInput: false,
                    locale: {
                        cancelLabel: 'Clear',
                        format: 'DD-MM-YYYY'
                    },
                    opens: 'left', 
                    alwaysShowCalendars: true,
                    showDropdowns: true,
                });

                // Clear the input when the cancel button is clicked
                $('#passport_daterange').on('cancel.daterangepicker', function(ev, picker) {
                    $(this).val('');
                    $("#filter_passport_date_from").val('');
                    $("#filter_passport_date_to").val('');
                });

                // Set the value of the textbox when the apply button is clicked
                $('#passport_daterange').on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
                    $("#filter_passport_date_from").val(picker.startDate.format('DD-MM-YYYY'));
                    $("#filter_passport_date_to").val(picker.endDate.format('DD-MM-YYYY'));
                });


        });
        // $(document).ready(function () {
        //     var columnlist = [
        //     { 'width': '3%' }, 
        //     { 'width': '25%' },
        //     { 'width': '20%' }, 
        //     { 'width': '10%' },
        //     { 'width': '10%' },
        //     { 'width': '6%' },
        //     { 'width': '6%' },
        //     { 'width': '12%' },
        //     { 'width': '12%' },
        //     { 'width': '7%' },
        //     { 'width': '' }
        //     ];
        //     //datatables
        //     $('#emptable').DataTable({
        //         // responsive: true, 
        //         <?php datatable_lang();?> dom: 'Blfrtip',
        //         buttons: [
        //             {
        //                 extend: 'excelHtml5',
        //                 footer: true,
        //                 exportOptions: {
        //                     columns: [0, 1, 2, 3]
        //                 }
        //             }
        //         ],
        //         'columnDefs': [
        //             {
        //                 'targets': [0],
        //                 'orderable': false,  // Only disable ordering for column 0
        //                 'createdCell': function(td, cellData, rowData, row, col) {
        //                     addClassToColumns(td, col, ['text-center']);
        //                 }
        //             },
        //             {
        //                 'targets': [3,5,6,9],
        //                 'createdCell': function(td, cellData, rowData, row, col) {
        //                     addClassToColumns(td, col, ['text-center']);
        //                 }
        //             },
        //             {
        //                 'targets': [],
        //                 'createdCell': function(td, cellData, rowData, row, col) {
        //                     addClassToColumns(td, col, ['text-right']);
        //                 }
        //             }
        //         ],
        //         'columns': columnlist,
        //     });



            
        //         //date filter
        //         // Set default start and end dates
        //         var startDate = moment().startOf('month'); // Start of the current month
        //         var endDate = moment().endOf('month'); // End of the current month

        //         $('#daterange').daterangepicker({
        //             autoUpdateInput: false,
        //             locale: {
        //                 cancelLabel: 'Clear',
        //                 format: 'DD-MM-YYYY'
        //             },
        //             opens: 'left', // Adjust the opening direction (left, right, etc.)
        //             alwaysShowCalendars: true,
        //             showDropdowns: true,
        //         });

        //         // Clear the input when the cancel button is clicked
        //         $('#daterange').on('cancel.daterangepicker', function(ev, picker) {
        //             $(this).val('');
        //         });

        //         // Set the value of the textbox when the apply button is clicked
        //         $('#daterange').on('apply.daterangepicker', function(ev, picker) {
        //             $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
        //             $("#filter_expiry_date_from").val(picker.startDate.format('DD-MM-YYYY'));
        //             $("#filter_expiry_date_to").val(picker.endDate.format('DD-MM-YYYY'));
        //         });


        // });

        $('.delemp').click(function (e) {
            e.preventDefault();
            $('#empid').val($(this).attr('data-object-id'));

        });
    </script>


    <div id="delete_model" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">

                    <h4 class="modal-title">Deactive Employee</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to deactivate this account ? <br><strong> It will disable this account
                            access
                            to
                            user.</strong></p>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="object-id" value="">
                    <input type="hidden" id="action-url" value="employee/disable_user">
                    <button type="button" data-dismiss="modal" class="btn btn-primary" id="delete-confirm">Confirm
                    </button>
                    <button type="button" data-dismiss="modal" class="btn">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <div id="pop_model" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">

                    <h4 class="modal-title"><?php echo $this->lang->line('Delete'); ?></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>

                <div class="modal-body">
                    <form id="form_model">


                        <div class="modal-body">
                            <p>Are you sure you want to delete this employee? <br><strong> It may interrupt old
                                    invoices,
                                    disable account is a better option.</strong></p>
                        </div>
                        <div class="modal-footer">


                            <input type="hidden" class="form-control required"
                                   name="empid" id="empid" value="">
                            <button type="button" class="btn btn-default"
                                    data-dismiss="modal"><?php echo $this->lang->line('Close'); ?></button>
                            <input type="hidden" id="action-url" value="employee/delete_user">
                            <button type="button" class="btn btn-primary"
                                    id="submit_model"><?php echo $this->lang->line('Delete'); ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>