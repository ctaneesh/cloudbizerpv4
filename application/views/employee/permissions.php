<?php       
  if (($msg = check_permission($permissions)) !== true) {
     echo $msg;
     return;
  }
 ?>
 <div class="content-body">
    <div class="card">
        
        <div class="card-header border-bottom">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard') ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('employee') ?>"><?php echo $this->lang->line('Employee') ?></a></li>
                    <li class="breadcrumb-item"><?php echo $this->lang->line('Employee')." ".$this->lang->line('Permissions') ?></li>
                  
                </ol>
            </nav>
            <h4 class="card-title">
                <?php echo $this->lang->line('Employee')." ".$this->lang->line('Permissions') ?> <a href="<?php echo base_url('employee/add') ?>"
                                                               class="btn btn-primary btn-sm rounded">
                    <?php echo $this->lang->line('Add new') ?>
                </a>
            </h4>
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

               
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link breaklink active show" id="base-tab1" data-toggle="tab"
                            aria-controls="tab1" href="#tab1" role="tab"
                            aria-selected="true"><?php echo $this->lang->line('Module Permissions') ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link breaklink" id="base-tab2" data-toggle="tab" aria-controls="tab2"
                            href="#tab2" role="tab"
                            aria-selected="false"><?php echo $this->lang->line('Employee Reporting to') ?></a>
                    </li>
                </ul>
                <div class="tab-content px-1 pt-1">
                    <div class="tab-pane active show" id="tab1" role="tabpanel" aria-labelledby="base-tab1">
                        <form method="post" id="data_form" class="form-horizontal">
                            <table id="" class="table table-striped table-bordered zero-configuration table-responsive datatable"
                                cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th style="width:50px;">#</th>
                                    <th><?php echo $this->lang->line('Name') ?></th>
                                    <th><?php echo $this->lang->line('Inventory Manager') ?></th>
                                    <th><?php echo $this->lang->line('Sales Person') ?></th>
                                    <th><?php echo $this->lang->line('Sales Manager') ?></th>
                                    <th><?php echo $this->lang->line('Business Manager') ?></th>
                                    <th><?php echo $this->lang->line('Business Owner') ?></th>
                                    <th><?php echo $this->lang->line('Project Manager') ?></th>


                                </tr>
                                </thead>
                                <tbody>
                                <?php $i = 1;

                                foreach ($permission as $row) {
                                    $i = $row['id'];
                                    $module = $row['module'];

                                    echo "<tr>
                                    <td>$i</td>
                                    <td>$module</td>"; ?>
                                    <td class="text-center"><input type="checkbox" name="r_<?= $i ?>_1"
                                                <?php if ($row['r_1']) echo 'checked="checked"' ?>></td>
                                    <td class="text-center"><input type="checkbox" name="r_<?= $i ?>_2"
                                                <?php if ($row['r_2']) echo 'checked="checked"' ?>></td>
                                    <td class="text-center"><input type="checkbox" name="r_<?= $i ?>_3"
                                                <?php if ($row['r_3']) echo 'checked="checked"' ?>></td>
                                    <td class="text-center"><input type="checkbox" name="r_<?= $i ?>_4"
                                                <?php if ($row['r_4']) echo 'checked="checked"' ?>></td>
                                    <td class="text-center"><input type="checkbox" name="r_<?= $i ?>_5"
                                                <?php if ($row['r_5']) echo 'checked="checked"' ?>></td>
                                    <td class="text-center"><input type="checkbox" name="r_<?= $i ?>_6"
                                                <?php if ($row['r_6']) echo 'checked="checked"' ?>></td>
                                    <?php
                                    echo "</tr>";
                                    //  $i++;
                                }
                                ?>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th><?php echo $this->lang->line('Name') ?></th>
                                    <th><?php echo $this->lang->line('Inventory Manager') ?></th>
                                    <th><?php echo $this->lang->line('Sales Person') ?></th>
                                    <th><?php echo $this->lang->line('Sales Manager') ?></th>
                                    <th><?php echo $this->lang->line('Business Manager') ?></th>
                                    <th><?php echo $this->lang->line('Business Owner') ?></th>
                                    <th><?php echo $this->lang->line('Project Manager') ?></th>

                                </tr>
                                </tfoot>
                            </table>
                            <div class="form-group row">
                                <div class="col-12 submit-section">
                                    <input type="submit" id="submit-btn" class="btn btn-primary margin-bottom btn-lg"
                                        value="<?php echo $this->lang->line('Update') ?>"
                                        data-loading-text="Adding...">
                                    <input type="hidden" name="actionurl" value="employee/permissions_update" id="action-url">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane" id="tab2" role="tabpanel" aria-labelledby="base-tab2">
                        <form action="" >
                            <table id="authpermission" class="table table-striped table-bordered zero-configuration" style="width:100%;">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?php echo $this->lang->line('Name') ?></th>
                                    <th><?php echo $this->lang->line('Role') ?></th>
                                    <th><?php echo $this->lang->line('Reporting To') ?></th>
                                    <th><?php echo $this->lang->line('Amount Limit') ?></th>
                                    <th><?php echo $this->lang->line('Actions') ?></th>


                                </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $i = 1;

                                    // Build the initial select dropdown
                                    $selectOptions = "<option value=''>" . $this->lang->line('Reporting To') . "</option>";
                                    foreach ($employee as $row1) {
                                        $selectOptions .= "<option value='" . $row1['id'] . "'>" . $row1['name'] . "</option>";
                                    }

                                    foreach ($employee as $row) {
                                        $aid = $row['id'];
                                        $username = $row['username'];
                                        $name = $row['name'];
                                        $reportingid = $row['reportingto'];
                                        $role = user_role($row['roleid']);

                                        echo "<tr>
                                        <td>$i</td>
                                        <td><a href='" . base_url("employee/view?id=$aid") . "' title='View'>" . $name . "</a></td>
                                        <td>$role</td>
                                        <td><select class='form-control reportingto' data-aid='$aid' id='reporting_to_".$i."'>$selectOptions</select></td>
                                        <td><input type='text' name='amount_limit' id='amount_limit_".$i."' class='form-control margin-bottom b_input' value=".$row['amount_limit']."></td>
                                        <td><input type='hidden' name='empid' id='empid_".$i."' value='".$aid."'><input type='hidden' name='reporting' id='reporting_".$aid."' value='".$reportingid."'><button type='button' class='btn btn-sm btn-secondary' name='savebtn' onclick='setauthotization(".$i.")'><i class='fa fa-database'></i> Save</button></td>
                                        </tr>";
                                        $i++;
                                    }
                                    ?>

                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th><?php echo $this->lang->line('Name') ?></th>
                                    <th><?php echo $this->lang->line('Role') ?></th>
                                    <th><?php echo $this->lang->line('Reporting To') ?></th>
                                    <th><?php echo $this->lang->line('Amount Limit') ?></th>
                                    <th><?php echo $this->lang->line('Actions') ?></th>
                                </tr>
                                </tfoot>
                            </table>
                        </form>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
<script type="text/javascript">
   
    $(document).ready(function () {
    
    // Initialize the first DataTable
    // $('#emptable').DataTable({
    //     responsive: true
    // });
    // Initialize the second DataTable with server-side processing
    
        var columnlist = [
            { 'width': '5%' }, 
            { 'width': '15%' },
            { 'width': '25%' }, 
            { 'width': '10%' },
            { 'width': '5%' },
            { 'width': '' },
        ];
             //datatables
        $('#authpermission').DataTable({
            //Set column definition initialisation properties.
            
            'columns': columnlist,
            

        });
        $("#dataTables_filter").hide();
        $('select.reportingto').each(function(i) {
            var aid = $(this).data('aid');
            var reportingid = $("#reporting_" + aid).val();
            
            $(this).find('option').each(function() {
                if ($(this).val() == aid) {
                    $(this).remove();
                }
                if ($(this).val() == reportingid) {
                    $(this).prop('selected', true);
                }
            });
        });
        $(".reportingto").select2();
    });
    function setauthotization(id){
        var reportingto = $("#reporting_to_" + id).val();
        var amountto = $("#amount_limit_" + id).val();
        var empid = $("#empid_" + id).val();
        // Check if reportingto is empty and amountto is a positive number
        if (reportingto=="" || amountto =="" || amountto == '0') {
            Swal.fire({
                title: 'Warning',
                text: 'Select reporting to and enter amount limit',
                icon: 'warning',
                confirmButtonText: 'Ok'
            });
            return; // Exit the function to prevent the AJAX call
        }

        $.ajax({
            url: baseurl + 'employee/set_authorization',
            dataType: "json",
            method: 'post',                
            data: {'empid': empid, 'reportingto' : reportingto, 'amountto' : amountto},
            success: function (response) {
                // Create table content from data
                // data = response.data;
                Swal.fire({
                    title: 'Success',
                    text: response.message,
                    icon: 'success',
                    confirmButtonText: 'Ok'
                });                
                hasUnsavedChanges = false;

                
            }
        });
    }

    
    $("#data_form").validate({
        ignore: [],
        rules: {      
            actionurl: { required: true },
        },
        messages: {
            actionurl: "Enter Url",
        },
        errorElement: "em",
        errorPlacement: function(error, element) {
            error.addClass("help-block");
            if (element.prop("type") === "checkbox") {
                error.insertAfter(element.parent("label"));
            } else {
                error.insertAfter(element);
            }
        },
        highlight: function(element, errorClass, validClass) {
            $(element).parents(".col-sm-5").addClass("has-error").removeClass("has-success");
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).parents(".col-sm-5").addClass("has-success").removeClass("has-error");
        },
        invalidHandler: function(event, validator) {
            // Focus on the first invalid element
            if (validator.errorList.length) {
                $(validator.errorList[0].element).focus();
            }
        }
    });

    $("#submit-btn").on("click", function (e) {
        e.preventDefault();
        $('#submit-btn').prop('disabled', true);
        if ($("#data_form").valid()) {
            var formData = new FormData($("#data_form")[0]);
            Swal.fire({
               title: "Are you sure?",
               text: "Do you want to update permission?",
               icon: "question",
               showCancelButton: true,
               confirmButtonColor: '#3085d6',
               cancelButtonColor: '#d33',
               confirmButtonText: 'Yes, proceed!',
               cancelButtonText: "No - Cancel",
               reverseButtons: true, 
               focusCancel: true,
               allowOutsideClick: false,
            }).then((result) => {
               if (result.isConfirmed) {  
                    $.ajax({
                        type: 'POST',
                        url: baseurl + 'employee/permissions_update',
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: "json",
                        success: function(response) {
                            location.reload();
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
               }
               else{
                    $('#submit-btn').prop('disabled', false);
               }
            });
        }
        else {
            $('#submit-btn').prop('disabled', false);
        }
    });
</script>



