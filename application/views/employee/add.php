    <div class="content-body">
    
        <?php       
            if (($msg = check_permission($permissions)) !== true) {
                echo $msg;
                return;
            }
        ?>
    <div class="card card-block bg-white">
    <div class="card-header border-bottom">          
         
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard') ?></a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('employee') ?>"><?php echo $this->lang->line('Employees') ?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line('New Employee') ?></li>
            </ol>
        </nav>
        <h4><?php echo $this->lang->line('New Employee') ?></h4>    
        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
        <div class="heading-elements">
            <ul class="list-inline mb-0">
            <li><a class="breaklink" data-action="expand"><i class="ft-maximize"></i></a></li>
            </ul>
        </div>
    </div>
    
        <div id="notify" class="alert alert-success" style="display:none;">
            <a href="#" class="close" data-dismiss="alert">&times;</a>

            <div class="message"></div>
        </div>
        <form method="post" id="data_form_employee" class="card-body">

            <div class="form-group row">
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class=" col-form-label" for="name"><?php echo $this->lang->line('UserName') ?><span class="compulsoryfld">*</span>
                        <small class="error">(Use Only a-z0-9)</small>
                    </label>
                    <input type="text" class="form-control margin-bottom required" name="username"  placeholder="username">
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label" for="email">Email<span class="compulsoryfld">*</span></label>
                    <input type="email" placeholder="email"
                           class="form-control margin-bottom required" name="email"
                           placeholder="email">
                </div>            

                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label" for="password"><?php echo $this->lang->line('Password') ?><span class="compulsoryfld">*</span>
                        <small>(min length 6 | max length 20 | a-zA-Z 0-9 @ $)</small>
                    </label>
                    <input type="text" placeholder="Password"  class="form-control margin-bottom required" name="password"  placeholder="password">
                </div>
          
                <?php if ($this->aauth->get_user()->roleid >= 0) { ?>                

                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label" for="name"><?php echo $this->lang->line('UserRole') ?><span class="compulsoryfld">*</span></label>
                            <select id="roleid" name="roleid" class="form-control margin-bottom required">
                                <option value=""><?= $this->lang->line('Select Role') ?></option>
                                <?php
                                    if(!empty($roles))
                                    {
                                        foreach($roles as $role)
                                        {
                                            echo '<option value="'.$role['role_id'].'">'.$role['role_name'].'</option>';
                                        }
                                    }
                                ?>                            
                            </select>
                    </div>


                <?php } ?>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label"  for="name"><?php echo $this->lang->line('Business Location') ?></label>
                    <select name="location" class="form-control margin-bottom">
                        <option value="0"><?php echo $this->lang->line('Default') ?></option>
                        <?php $loc = locations();
                        foreach ($loc as $row) {
                            echo ' <option value="' . $row['id'] . '"> ' . $row['cname'] . '</option>';
                        }

                        ?>
                    </select>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label" for="reportingto"><?php echo $this->lang->line('Reporting To') ?><span class="compulsoryfld">*</span></label><br>
                    <!-- <input type="text" placeholder="Country" class="form-control margin-bottom" name="country"> -->
                    <select name="reportingto" id="reportingto" class="form-control margin-bottom" required>
                    <?php
                        echo "<option value=''>Select Reporting Person</option>";
                        foreach ($emplists as $row) {
                            $cid = $row['id'];
                            $title = $row['name'];
                            echo "<option value='$cid'>$title</option>";
                        }
                    ?>
                    </select>
                </div>      
                <div class="col-xl-2 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label" for="authorized amount"><?php echo $this->lang->line('Amount Limit') ?><span class="compulsoryfld">*</span></label>
                    <input type="number" name="amount_limit" id="amount_limit" class="form-control margin-bottom" required>
                </div>      

                <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="join_date"><?php echo $this->lang->line('Expense Claims Approver') ?></label>
                        <select name="expense_claim_approver" id="expense_claim_approver" class="form-control margin-bottom">
                            <option value=""><?php echo $this->lang->line('Select') . " " . $this->lang->line('Expense Claims Approver') ?> </option>
                            <?php
                            $options = array(
                                "5" => 'Business Owner',
                                "4" => 'Business Manager',
                                "3" => 'Sales Manager',
                                "2" => 'Sales Person',
                                "6" => 'Sales Man',
                                "1" => 'Inventory Manager',
                                "-1" => 'Project Manager'
                            );

                            if ($expense_approver) {
                                foreach ($expense_approver as $row) {
                                    if (isset($row['roleid']) && isset($options[$row['roleid']])) {
                                        if($row['roleid']==$user['expense_claim_approver'])
                                        {
                                            continue;
                                        }
                                        // Append the role name to the employee name
                                        echo "<option value='" . $row['id'] . "'>" . $row['name'] . " (" . $options[$row['roleid']] . ")</option>";
                                    }
                                }
                            }
                            ?>
                        </select>

                    </div> 
                    
            </div>

            <hr>

            <div class="form-group row">
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label" for="name"><?php echo $this->lang->line('Name') ?><span class="compulsoryfld">*</span></label>
                    <input type="text" placeholder="Name"  class="form-control margin-bottom required" name="name" placeholder="Full name">
                </div>                
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label" for="address"><?php echo $this->lang->line('Address') ?></label>
                    <textarea class="form-textarea margin-bottom" name="address" id="address"></textarea>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label" for="country"><?php echo $this->lang->line('Country') ?></label><br>
                    <!-- <input type="text" placeholder="Country" class="form-control margin-bottom" name="country"> -->
                    <select name="country" id="country" class="form-control margin-bottom">
                    <?php
                        echo "<option value=''>Select Country</option>";
                        foreach ($countries as $row) {
                            $cid = $row['id'];
                            $title = $row['name'];
                            $code = $row['code'];
                            echo "<option value='$cid'>$title($code)</option>";
                        }
                    ?>
                    </select>
                </div>                         
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label"  for="city"><?php echo $this->lang->line('City') ?></label>
                    <input type="text" placeholder="City"  class="form-control margin-bottom" name="city">
                </div>                
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label"  for="city"><?php echo $this->lang->line('Region') ?></label>
                    <input type="text" placeholder="Region"  class="form-control margin-bottom" name="region">
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label"  for="postbox"><?php echo $this->lang->line('Postbox') ?></label>
                    <input type="text" placeholder="Postbox" class="form-control margin-bottom" name="postbox">
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label"  for="phone"><?php echo $this->lang->line('Phone') ?></label>
                    <input type="text" placeholder="phone" class="form-control margin-bottom" name="phone">
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label"  for="phonealt"><?php echo $this->lang->line('Phone') ?>(Alt)</label>
                    <input type="text" placeholder="Alternate Phone" class="form-control margin-bottom" name="phonealt">
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label"  for="salary"><?php echo $this->lang->line('Salary') ?></label>
                    <input type="number" placeholder="salary" class="form-control margin-bottom" name="salary" value="0"> 
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label" for="name"><?php echo $this->lang->line('Department') ?></label>                
                    <select name="department" class="form-control margin-bottom">
                        <option value="0"><?php echo $this->lang->line('Default') . ' - ' . $this->lang->line('No') ?></option>
                        <?php

                        foreach ($dept as $row) {
                            echo ' <option value="' . $row['id'] . '"> ' . $row['val1'] . '</option>';
                        }

                        ?>
                    </select>
                </div>
                <!-- erp2024 newly added 14-06-2024 -->
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12"  id="mainsection">
                    <label class="col-form-label" for="city"><?php echo $this->lang->line('Location-Store') ?><span class="compulsoryfld">*</span></label>
                    <select name="emp_work_location" id="emp_work_location" class="form-control">
                        <option value="">Select</option>
                        <?php
                            if($warehouses){
                                foreach($warehouses as $row){
                                    echo "<option value='".$row['store_id']."'>".$row['store_name']."</option>";
                                }
                            }
                        ?>
                    </select>
                </div>
                <!-- erp2024 newly added 14-06-2024 ends -->
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label" for="city"><?php echo $this->lang->line('Commission') ?>  % </label>
                    <input type="number" placeholder="Commission %" value="0"  class="form-control margin-bottom" name="commission">
                    <small class="pt-1"><i>(It will based on each invoice amount - inclusive all taxes,shipping,discounts)</i></small>
                </div>
                
            </div>

            <hr>
            <div class="form-group row">
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label" for="Nationality"><?php echo $this->lang->line('Nationality') ?></label>
                    <!-- <input type="text" placeholder="Nationality"  class="form-control margin-bottom required" name="nationality" id="nationality"> -->
                    <select name="nationality" id="nationality" class="form-control margin-bottom">
                    <?php
                        echo "<option value=''>Select Nationality</option>";
                        foreach ($countries as $row) {
                            $cid = $row['id'];
                            $title = $row['name'];
                            $code = $row['code'];
                            echo "<option value='$cid'>$title($code)</option>";
                        }
                    ?>
                    </select>
                </div>                
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label" for="Residence Permit"><?php echo $this->lang->line('Residence Permit') ?></label>
                    <input type="text" placeholder="Residence Permit"  class="form-control margin-bottom" name="residence_permit">
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label" for="expiry_date"><?php echo $this->lang->line('Residence Permit Expiry') ?></label>
                    <input type="date" placeholder="Residence Permit Expiry Date" class="form-control margin-bottom" name="expiry_date">
                </div> 
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label" for="passport_number"><?php echo $this->lang->line('Passport Number') ?></label>
                    <input type="text" placeholder="Passport Number"  class="form-control margin-bottom" name="passport_number">
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label" for="passport_expiry"><?php echo $this->lang->line('Passport Expiry') ?></label>
                    <input type="date" placeholder="Passport Expiry"  class="form-control margin-bottom" name="passport_expiry">
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="name"><?php echo $this->lang->line('Passport Status') ?></label>
                        <select name="passport_status" class="form-control margin-bottom">
                            <option value="Active"><?= $this->lang->line('Active') ?></option>
                            <option value="Inactive"><?= $this->lang->line('Inactive') ?></option>
                        </select>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label" for="join_date"><?php echo $this->lang->line('Join Date') ?></label>
                    <input type="date" placeholder="Join Date" class="form-control margin-bottom" name="join_date" value="<?=date('Y-m-d')?>">
                </div> 
            </div> 

            <div class="form-group text-right">
                <hr>
                <div class="submit-section">
                    <input type="submit" id="employee_add_btn" class="btn btn-crud btn-primary btn-lg margin-bottom"
                           value="<?php echo $this->lang->line('Create an Employee') ?>"
                           data-loading-text="Adding...">
                    <!-- <input type="hidden" value="employee/submit_user" id="action-url"> -->
                </div>
            </div>


        </form>
    </div>

</div>
    
<script type="text/javascript">

    //erp2024 Supplier add section 23-12-2024
    $("#data_form_employee").validate($.extend(true, {}, globalValidationOptions,{
        rules: {
            username: {required:true},
            password: {required:true},
            roleid: {required:true},
            name: {required:true},
            email: {
                required: true,
                email: true
            }
        },
        messages: {
            username  : "Enter User Name",
            password  : "Enter Password",
            email  : "Enter Email",
            roleid  : "Select User Role",
            name  : "Enter Name",
        }
    }));
  

    $("#employee_add_btn").on("click", function (e) {
        e.preventDefault();
        $('#employee_add_btn').prop('disabled', true);
        if ($("#data_form_employee").valid()) {

            var formData = new FormData($("#data_form_employee")[0]);
        
            Swal.fire({
               title: "Are you sure?",
               text: "Do you want to create a new employee?",
               icon: "question",
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
                    $.ajax({
                        type: 'POST',
                        url: baseurl + 'employee/submit_user',
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: "json",
                        success: function(response) {
                            window.location.href = baseurl + 'employee';
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
               }
               else{
                    $('#employee_add_btn').prop('disabled', false);
               }
            });
        }
        else {
            $('#employee_add_btn').prop('disabled', false);
            $('html, body').animate({
                scrollTop: $("#data_form_employee").offset().top
            }, 2000);
            $("#data_form_employee").focus();
        }
    });
    ////erp2024 Supplier add section ends 23-12-2024

    // erp2024 add 03-06-2024
    $("#country").select2();
    $("#nationality").select2();
    $("#reportingto").select2();
    $("#expense_claim_approver").select2();
    // erp2024 add 03-06-2024  ends
    $("#profile_add").click(function (e) {
        e.preventDefault();
        var actionurl = baseurl + 'user/submit_user';
        actionProduct1(actionurl);
    });
</script>

<script>

    function actionProduct1(actionurl) {

        $.ajax({

            url: actionurl,
            type: 'POST',
            data: $("#product_action").serialize(),
            dataType: 'json',
            success: function (data) {
                $("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
                $("#notify").removeClass("alert-warning").addClass("alert-success").fadeIn();


                $("html, body").animate({scrollTop: $('html, body').offset().top}, 200);
                $("#product_action").remove();
            },
            error: function (data) {
                $("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
                $("#notify").removeClass("alert-success").addClass("alert-warning").fadeIn();
                $("html, body").animate({scrollTop: $('#notify').offset().top}, 1000);

            }

        });


    }

    // erp2024 newly added 14-06-2024 starts
    $("#roleid").on("change", function(){
        if(this.value=="5"){
            $("#emp_work_location").removeClass("required");
            $("#mainsection").removeClass("has-error");
        }
        else{
            $("#emp_work_location").addClass("required");
        }
    });
    // erp2024 newly added 14-06-2024 ends
</script>