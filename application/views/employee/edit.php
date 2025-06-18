<div>
    <div id="notify" class="alert alert-success" style="display:none;">
        <a href="#" class="close" data-dismiss="alert">&times;</a>
        <div class="message"></div>
    </div>
    <div class="row">
        
        <div class="col-md-4 ">
            <div class="card card-block card-body">
                <h5><?php echo $this->lang->line('Update Profile Picture') ?></h5>
                <hr>
                <div class="ibox-content no-padding border-left-right">
                    <img alt="profile picture" id="dpic" class="img-responsive col"
                         src="<?php echo base_url('userfiles/employee/') . $user['picture'] ?>">
                </div>
                <hr>
                <p><label for="fileupload"><?php echo $this->lang->line('Change Your Picture') ?></label>
                <input id="fileupload" type="file" name="files[]" data-original-value="<?php echo $user['picture']; ?>"></p></div>
            <!-- signature -->

            <div class="card card-block card-body"><h5><?php echo $this->lang->line('Update Your Signature') ?></h5>
                <hr>
                <div class="ibox-content no-padding border-left-right">
                    <img alt="sign_pic" id="sign_pic" class="img-responsive col col"
                         src="<?php echo base_url('userfiles/employee_sign/') . $user['sign'] ?>">
                </div>
                <hr>
                <p>
                    <label for="sign_fileupload"><?php echo $this->lang->line('Change Your Signature') ?></label>
                    <input id="sign_fileupload" type="file" name="files[]" data-original-value="<?php echo $user['sign']; ?>"></p>
                </div>


        </div>
        <div class="col-md-8">
            <div class="card card-block card-body">

                <form method="post" id="data_form_employee" class="form-horizontal">
                    <div class="grid_3 grid_4">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard') ?></a></li>
                                <li class="breadcrumb-item"><a href="<?= base_url('employee') ?>"><?php echo $this->lang->line('Employees') ?></a></li>
                                <li class="breadcrumb-item active" aria-current="page"><?php echo $user['username'] ?></li>
                            </ol>
                        </nav>
                        <h5><?php echo $this->lang->line('Update Your Details') ?> (<?php echo $user['username'] ?>
                            )</h5>
                        <hr>


                        <div class="form-group row">

                            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label" for="name"><?php echo $this->lang->line('Name') ?><span class="compulsoryfld">*</span></label>
                                <input type="text" placeholder="Name"  class="form-control margin-bottom  required" name="name" id="name"  value="<?php echo $user['name'] ?>" data-original-value="<?php echo $user['name']; ?>">
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label" for="address"><?php echo $this->lang->line('Address') ?></label>
                                <textarea name="address" id="address" class="form-textarea margin-bottom" data-original-value="<?php echo $user['address'] ?>"><?php echo $user['address'] ?></textarea>
                            </div>                                                   

                            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label" for="country"><?php echo $this->lang->line('Country') ?></label>
                                <!-- <input type="text" placeholder="Country"  class="form-control margin-bottom" name="country"  value="<?php echo $user['country'] ?>"> -->
                                <!-- erp2024 newly added 03-06-2024 starts-->
                                <select name="country" id="country" class="form-control margin-bottom multi-select" data-original-value="<?=$user['country']?>">
                                    <?php
                                        echo "<option value=''>Select Country</option>";
                                        foreach ($countries as $row) {
                                            $cid = $row['id'];
                                            $title = $row['name'];
                                            $code = $row['code'];
                                            $sel="";
                                            if($cid==$user['country']){
                                                $sel = "selected";
                                            }
                                            echo "<option value='$cid' $sel>$title($code)</option>";
                                        }
                                    ?>
                                </select>
                                <!-- erp2024 newly added 03-06-2024 ends-->
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label"  for="city"><?php echo $this->lang->line('City') ?></label>
                                <input type="text" placeholder="city"  id="city" class="form-control margin-bottom" name="city" value="<?php echo $user['city'] ?>" data-original-value="<?php echo $user['city']; ?>">
                            </div> 
                            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label" for="postbox"><?php echo $this->lang->line('Postbox') ?></label>
                                <input type="text" placeholder="Postbox" class="form-control margin-bottom" id="postbox"  name="postbox" value="<?php echo $user['postbox'] ?>" data-original-value="<?php echo $user['postbox']; ?>">
                            </div>
                            
                            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label"  for="phone"><?php echo $this->lang->line('Phone') ?><span class="compulsoryfld">*</span></label>
                                <input type="text" placeholder="phone" class="form-control margin-bottom" id="phone" name="phone" value="<?php echo $user['phone'] ?>" data-original-value="<?php echo $user['phone']; ?>">
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label" for="phonealt"><?php echo $this->lang->line('Phone') ?> (Alt)</label>
                                <input type="text" placeholder="altphone" class="form-control margin-bottom" id="phonealt" name="phonealt" value="<?php echo $user['phonealt'] ?>" data-original-value="<?php echo $user['phonealt']; ?>">
                            </div>

                            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label" for="email"><?php echo $this->lang->line('Email') ?><span class="compulsoryfld">*</span></label>
                                <input type="text" placeholder="email" class="form-control margin-bottom  required" name="email" id="email"  value="<?php echo $user['email'] ?>" disabled >
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label" for="location"><?php echo $this->lang->line('Business Location') ?></label>
                                <select name="location" id="location" class="form-control margin-bottom" data-original-value="<?php echo $user['loc']; ?>">
                                    <option value="<?php echo $user['loc'] ?>"><?php echo $this->lang->line('Do not change') ?></option>
                                    <option value="0"><?php echo $this->lang->line('Default') ?></option>
                                    <?php $loc = locations();

                                    foreach ($loc as $row) {
                                        echo ' <option value="' . $row['id'] . '"> ' . $row['cname'] . '</option>';
                                    }

                                    ?>
                                </select>
                            </div>
                        <?php if ($this->aauth->get_user()->roleid >= 0) { ?>
                                
                                <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                    <label class="col-form-label" for="roleid"><?php echo $this->lang->line('UserRole') ?><span class="compulsoryfld">*</span></label>
                                    <select name="roleid" id="roleid" data-original-value="<?php echo $user['roleid']; ?>" class="form-control margin-bottom" <? if ($user['roleid'] == 5) echo 'disabled' ?>>
                                        <!-- <option value="<?= $user['roleid'] ?>">--<?= user_role($user['roleid']) ?>--
                                        </option>
                                        <option value="4"><?= $this->lang->line('Business Manager') ?></option>
                                        <option value="3"><?= $this->lang->line('Sales Manager') ?></option>
                                        <option value="5"><?= $this->lang->line('Business Owner') ?></option>
                                        <option value="2"><?= $this->lang->line('Sales Person') ?></option>
                                        <option value="6"><?= $this->lang->line('Sales Man') ?></option>
                                        <option value="1"><?= $this->lang->line('Inventory Manager') ?></option>
                                        <option value="-1"><?= $this->lang->line('Project Manager') ?></option> -->
                                        <?php
                                        if(!empty($roles))
                                        {
                                            foreach($roles as $role)
                                            {
                                                $sel="";
                                                if($role['role_id']==$user['roleid'])
                                                {
                                                    $sel = "selected";
                                                }
                                                echo '<option value="'.$role['role_id'].'" '.$sel.'>'.$role['role_name'].'</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>


                        <?php } ?>
                        
                        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label"
                                   for="department"><?php echo $this->lang->line('Department') ?></label>
                                <select id="department" name="department" class="form-control margin-bottom" data-original-value="<?php echo $user['dept']; ?>">
                                    <option value="<?php echo $user['dept'] ?>"><?php echo $this->lang->line('Do not change') ?></option>
                                    <option value="0"><?php echo $this->lang->line('Default') . ' - ' . $this->lang->line('No') ?></option>
                                    <?php

                                    foreach ($dept as $row) {
                                        echo ' <option value="' . $row['id'] . '"> ' . $row['val1'] . '</option>';
                                    }

                                    ?>
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label"  for="salary"><?php echo $this->lang->line('Salary') ?></label>
                                <input type="text" placeholder="Salary" onkeypress="return isNumber(event)" class="form-control margin-bottom" id="salary" name="salary" value="<?php echo amountFormat_general($user['salary']) ?>" data-original-value="<?php echo amountFormat_general($user['salary']); ?>">
                            </div>
                            <!-- erp2024 new ly added 17-07-2024 -->
                            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label" for="reportingto"><?php echo $this->lang->line('Reporting To') ?><span class="compulsoryfld">*</span></label>
                                <select name="reportingto" id="reportingto" data-original-value="<?php echo $user['reportingto']; ?>" class="form-control" required>
                                    <option value="">Select</option>
                                    <?php
                                       
                                        foreach ($emplists as $row) {
                                            $cid = $row['id'];
                                            $title = $row['name'];
                                            $sel="";
                                                if($user['reportingto']==$row['id']){
                                                    $sel = "selected";
                                                }
                                            echo "<option value='$cid' $sel>$title</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label" for="amount_limit"><?php echo $this->lang->line('Amount Limit') ?><span class="compulsoryfld">*</span></label>
                                <input type="number" name="amount_limit" id="amount_limit" value="<?=$user['amount_limit']?>" data-original-value="<?php echo $user['amount_limit']; ?>" class="form-control margin-bottom" required>
                            </div>
                            <!-- erp2024 newly added 17-07-2024 ends -->
                            <!-- erp2024 newly added 14-06-2024 -->
                            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12"  id="mainsection">
                                <label class="col-form-label" for="emp_work_location"><?php echo $this->lang->line('Location-Store') ?><span class="compulsoryfld">*</span></label>
                                <select name="emp_work_location" id="emp_work_location" class="form-control" data-original-value="<?php echo $user['emp_work_location']; ?>">
                                    <option value="">Select</option>
                                    <?php
                                        if($warehouses){
                                            foreach($warehouses as $row){
                                                $sel="";
                                                if($user['emp_work_location']==$row['id']){
                                                    $sel = "selected";
                                                }
                                                echo "<option value='".$row['id']."' $sel>".$row['title']."</option>";
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                            <!-- erp2024 newly added 14-06-2024 ends -->
                            
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label" for="commission"><?php echo $this->lang->line('Commission') ?> Percentage </label>
                                <input type="number" placeholder="Commission %" class="form-control margin-bottom" name="commission" id="commission" value="<?php echo $user['c_rate'] ?>" data-original-value="<?php echo $user['c_rate']; ?>">
                                <small class="col">(It will based on each invoice amount - inclusive all taxes,shipping,discounts)</small>
                            </div>
                            

                        </div>
                        <input type="hidden" name="eid" value="<?php echo $user['id'] ?>">
                        
                        <!-- erp2024 newly added fields 03-06-2024 starts-->
                        <hr>
                        <div class="form-group row">
                            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label" for="nationality"><?php echo $this->lang->line('Nationality') ?></label>
                                <!-- <input type="text" placeholder="Nationality"  class="form-control margin-bottom required" name="nationality" id="nationality"> -->
                                <select name="nationality" id="nationality"  data-original-value="<?php echo $user['nationality']; ?>" class="form-control margin-bottom">
                                <?php
                                    echo "<option value=''>Select Nationality</option>";
                                    foreach ($countries as $row) {
                                        $cid = $row['id'];
                                        $title = $row['name'];
                                        $code = $row['code'];
                                        $sel="";
                                        if($cid==$user['nationality']){
                                            $sel = "selected";
                                        }
                                        echo "<option value='$cid' $sel>$title($code)</option>";
                                    }
                                ?>
                                </select>
                            </div>                
                            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label" for="residence_permit"><?php echo $this->lang->line('Residence Permit') ?></label>
                                <input type="text" placeholder="Residence Permit"  class="form-control margin-bottom" name="residence_permit" id="residence_permit" value="<?php echo $user['residence_permit'] ?>" data-original-value="<?php echo $user['residence_permit']; ?>">
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label" for="expiry_date"><?php echo $this->lang->line('Residence Permit Expiry') ?></label>
                                <input type="date" placeholder="Residence Permit Expiry Date" class="form-control margin-bottom" name="expiry_date" id="expiry_date" value="<?php echo $user['expiry_date'] ?>" data-original-value="<?php echo $user['expiry_date']; ?>">
                            </div> 
                            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label" for="passport_number"><?php echo $this->lang->line('Passport Number') ?></label>
                                <input type="text" placeholder="Passport Number"  class="form-control margin-bottom" name="passport_number" id="passport_number" value="<?php echo $user['passport_number'] ?>" data-original-value="<?php echo $user['passport_number']; ?>">
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label" for="passport_expiry"><?php echo $this->lang->line('Passport Expiry') ?></label>
                                <input type="date" placeholder="Passport Expiry"  class="form-control margin-bottom" name="passport_expiry" id="passport_expiry" value="<?php echo $user['passport_expiry'] ?>" data-original-value="<?php echo $user['passport_expiry']; ?>">
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                    <label class="col-form-label" for="passport_status"><?php echo $this->lang->line('Passport Status') ?></label>
                                    <select name="passport_status" id="passport_status" class="form-control margin-bottom" data-original-value="<?php echo $user['passport_status']; ?>">
                                        <option value="Active" <?php if($user['passport_status']=="Active"){ echo "selected"; } ?>><?= $this->lang->line('Active') ?></option>
                                        <option value="Inactive" <?php if($user['passport_status']=="Inactive"){ echo "selected"; } ?>><?= $this->lang->line('Inactive') ?></option>
                                    </select>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label" for="join_date"><?php echo $this->lang->line('Join Date') ?></label>
                                <input type="date" placeholder="Join Date" class="form-control margin-bottom" name="join_date" id="join_date" value="<?php echo $user['join_date'] ?>" data-original-value="<?php echo $user['join_date']; ?>">
                            </div> 
                            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label" for="expense_claim_approver"><?php echo $this->lang->line('Expense Claims Approver') ?></label>
                                <select name="expense_claim_approver" data-original-value="<?php echo $user['expense_claim_approver']; ?>" class="form-control margin-bottom" >
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
                                                $sel="";
                                                if ($row['id'] == $user['expense_claim_approver']) {
                                                  $sel ="selected";
                                                }
                                                echo "<option value='" . $row['id'] . "' $sel>" . $row['name'] . " (" . $options[$row['roleid']] . ")</option>";                 
                                            }
                                        }
                                    }
                                    ?>
                                </select>

                            </div> 
                        </div> 
                        <!-- erp2024 newly added fields 03-06-2024 ends -->

                        <div class="form-group text-right">
                                <hr>
                            <div class="submit-section">
                                <input type="submit" id="employee_update_btn" class="btn btn-crud btn-primary btn-lg margin-bottom"
                                       value="<?php echo $this->lang->line('Update') ?>"
                                       data-loading-text="Updating...">
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
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
  

   
    ////erp2024 Supplier add section ends 23-12-2024
    // erp2024 add 03-06-2024
    $("#country").select2();
    $("#nationality").select2();
    $("#roleid").select2();
    $("#expense_claim_approver").select2();
    // erp2024 add 03-06-2024  ends

</script>
<script src="<?php echo base_url('assets/myjs/jquery.ui.widget.js') ?>"></script>
<!-- The basic File Upload plugin -->
<script src="<?php echo base_url('assets/myjs/jquery.fileupload.js') ?>"></script>
<script>
    /*jslint unparam: true */
    /*global window, $ */
    $(function () {
        'use strict';
        // Change this to the location of your server-side upload handler:
        var url = '<?php echo base_url() ?>employee/displaypic?id=<?php echo $user['id'] ?>';
        $('#fileupload').fileupload({
            url: url,
            dataType: 'json',
            formData: {'<?=$this->security->get_csrf_token_name()?>': crsf_hash},
            done: function (e, data) {

                //$('<p/>').text(file.name).appendTo('#files');


                $("#dpic").attr('src', '<?php echo base_url() ?>userfiles/employee/' + data.result + '?' + new Date().getTime());

            },
            progressall: function (e, data) {
                var progress = parseInt(data.loaded / data.total * 100, 10);
                $('#progress .progress-bar').css(
                    'width',
                    progress + '%'
                );
            }
        }).prop('disabled', !$.support.fileInput)
            .parent().addClass($.support.fileInput ? undefined : 'disabled');


        // Sign
        var sign_url = '<?php echo base_url() ?>employee/user_sign?id=<?php echo $user['id'] ?>';
        $('#sign_fileupload').fileupload({
            url: sign_url,
            dataType: 'json',
            formData: {'<?=$this->security->get_csrf_token_name()?>': crsf_hash},
            done: function (e, data) {

                //$('<p/>').text(file.name).appendTo('#files');
                $("#sign_pic").attr('src', '<?php echo base_url() ?>userfiles/employee_sign/' + data.result + '?' + new Date().getTime());
                location.reload();

            },
            progressall: function (e, data) {
                var progress = parseInt(data.loaded / data.total * 100, 10);
                $('#progress .progress-bar').css(
                    'width',
                    progress + '%'
                );
            }
        }).prop('disabled', !$.support.fileInput)
            .parent().addClass($.support.fileInput ? undefined : 'disabled');
    });
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
    // erp2024 newly added 14-06-2024 for detailed history log
    const changedFields = {};
    //erp2024 06-01-2025 for history log
    $(document).ready(function () {       
        // Add event listeners to all input fields
        document.querySelectorAll('input, textarea, select').forEach((input) => {
            input.addEventListener('change', function () {
                const fieldId = this.id || this.name; // Use `name` for grouped radio buttons
                const originalValue = this.getAttribute('data-original-value');
                var label = $('label[for="' + fieldId + '"]');
                var field_label = label.text();
                // console.log();
                if (this.type === 'checkbox') {
                    // For checkboxes, use the "checked" state
                    const newValue = this.checked ? this.value : null;
                    const originalChecked = originalValue === this.value;

                    if (originalChecked !== this.checked) {
                        changedFields[fieldId] = {
                            oldValue: originalChecked ? this.value : null,
                            newValue: newValue,
                            fieldlabel : field_label
                        }; // Track changes
                    } else {
                        delete changedFields[fieldId]; // Remove if no change
                    }
                } else if (this.type === 'radio') {
                    // For radio buttons, track the selected option
                    if (this.checked) {
                        const newValue = this.value;
                        if (originalValue !== newValue) {
                            changedFields[fieldId] = {
                                oldValue: originalValue,
                                newValue: newValue,
                                fieldlabel : field_label
                            };
                        } else {
                            delete changedFields[fieldId]; // Remove if no change
                        }
                    }
                } else if (this.type === 'number') {
                    // For numeric fields
                    const newValue = parseFloat(this.value);
                    const originalNumber = parseFloat(originalValue);

                    if (!isNaN(originalNumber) && !isNaN(newValue) && originalNumber !== newValue) {
                        changedFields[fieldId] = {
                            oldValue: originalNumber,
                            newValue: newValue,
                            fieldlabel : field_label
                        };
                    } else {
                        delete changedFields[fieldId]; // Remove if no change
                    }
                } 
                else if (this.tagName === 'SELECT') {
                // For select fields, use the option's label
                const selectedOption = this.options[this.selectedIndex];
                const newValue = selectedOption ? selectedOption.label : '';
                const originalLabel = this.getAttribute('data-original-label');

                if (originalLabel !== newValue) {
                    changedFields[fieldId] = {
                        oldValue: originalLabel,
                        newValue: newValue,
                        fieldlabel: field_label
                    };
                } else {
                    delete changedFields[fieldId];
                }
            } else {
                    // For text, textarea, and select fields
                    const newValue = this.value;
                    if (originalValue !== newValue) {
                        changedFields[fieldId] = {
                            oldValue: originalValue,
                            newValue: newValue,
                            fieldlabel : field_label
                        };
                    } else {
                        delete changedFields[fieldId]; // Remove if no change
                    }
                }
            });
        });

        // Function to initialize Select2 and track changes
        function initializeSelect2(selector) {
            // Initialize Select2

            // Attach change event listener to track changes
            $(selector).each(function () {
                const element = $(this);
                const fieldId = element.attr('id');
                const originalValue = element.data('original-value'); // Access `data-original-value`
                var label = $('label[for="' + fieldId + '"]');
                var field_label = label.text();
                element.on('change', function () {
                    const newValue = element.val();
                    if (originalValue != newValue) {
                        changedFields[fieldId] = {
                            oldValue: originalValue,
                            newValue: newValue,
                            fieldlabel: field_label,
                        }; // Store the original and new value
                    } else {
                        delete changedFields[fieldId]; // Remove if no change
                    }
                });
            });
        }
        initializeSelect2('.multi-select');
         //Function for select2 type dropdown
        $(document).on('select2:select select2:unselect', '.select2-hidden-accessible', function (e) {
            const fieldId = this.id || this.name;
            const originalValue = $(this).data('original-label'); // Original value (could be string or array)
            const newValueArray = $(this).val(); // Get the current value(s) as an array
            const label = $('label[for="' + fieldId + '"]');
            let field_label = label.text();
            if (!field_label.trim()) {
                field_label = this.getAttribute('title') || 'Unknown Field';
            }

            if (Array.isArray(newValueArray)) {
                // Handle multiple select: Get the selected option labels
                const newValueLabels = newValueArray.map(function (value) {
                    const option = $('option[value="' + value + '"]', e.target);
                    return option.length ? option.text() : ''; // Get the label (text) of the selected option
                });

                const newValue = newValueLabels.join(','); // Convert array of labels to string
                const originalLabels = Array.isArray(originalValue) ? originalValue.map(function (value) {
                    const option = $('option[value="' + value + '"]', e.target);
                    return option.length ? option.text() : '';
                }).join(',') : originalValue;

                if (originalLabels !== newValue) {
                    changedFields[fieldId] = {
                        oldValue: originalLabels,
                        newValue: newValue,
                        fieldlabel: field_label,
                    };
                } else {
                    delete changedFields[fieldId]; // No changes
                }
            } else {
                // Handle single select: Get the selected option label
                const newValue = newValueArray ? $('option[value="' + newValueArray + '"]', e.target).text() : '';
                if (originalValue !== newValue) {
                    changedFields[fieldId] = {
                        oldValue: originalValue,
                        newValue: newValue,
                        fieldlabel: field_label,
                    };
                } else {
                    delete changedFields[fieldId]; // No changes
                }
            }
        });
    });
    // erp2024 newly added 14-06-2024 for detailed history log ends 

    $("#employee_update_btn").on("click", function (e) {
        e.preventDefault();
        $('#employee_update_btn').prop('disabled', true);
        if ($("#data_form_employee").valid()) {

            var formData = new FormData($("#data_form_employee")[0]);
            formData.append('changedFields', JSON.stringify(changedFields));

            Swal.fire({
               title: "Are you sure?",
               text: "Do you want to update this employee?",
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
                        url: baseurl + 'employee/update',
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
                    $('#employee_update_btn').prop('disabled', false);
               }
            });
        }
        else {
            $('#employee_update_btn').prop('disabled', false);
            $('html, body').animate({
                scrollTop: $("#data_form_employee").offset().top
            }, 2000);
            $("#data_form_employee").focus();
        }
    });
</script>
