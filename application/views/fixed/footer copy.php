<p class="version">Version 3.1</p>
</div>
</div>



<!-- =================================History section=========================== -->
<!--     erp2025 add 06-01-2025   Detailed hisory starts-->
<button class="history-expand-button navtab-caption" title="Click here to view the history.">
    <span>History</span>
</button>
<div class="history-container">
    <button class="history-close-button">
    <span>Close</span>
    </button>
    <button class="logclose-btn">
        <span>X</span>
    </button>
    <h2>History</h2>
    <form class="table-scroll history-scroll">
    <table id="log" class="table table-striped table-bordered zero-configuration dataTable">
        <thead>
            <tr>
                <th><?php echo $this->lang->line('Performed By') ?></th>
                <th><?php echo $this->lang->line('Action')?></th>
                <th><?php echo $this->lang->line('Action Performed') ?></th>
                <th><?php echo $this->lang->line('Performed At')?></th>
                <th><?php echo $this->lang->line('IP Address')?></th>
                        
            </tr>
        </thead>
        <tbody>
        <?php $i = 1;
        if($groupedDatas)
        {
       
                foreach ($groupedDatas as $seqence_number => $historylist)
                {
                    $flag=0;
                    ?>              
                    <tr>
                        <td>  
                            <div class="userdata">
                            <img class="rounded-circle1" src='<?php echo base_url('userfiles/employee/thumbnail/'.$historylist[0]['picture'])?>' style="width:50px; height:50px;margin-right:3px;" ?>
                                <?php  echo $historylist[0]['name'];
                                        
                            echo '</div>';
                        echo '</td>'; ?>
                        
                        <td><?php echo $historylist[0]['action']?></td> 
                        <td>    
                            <?php    
                            foreach ($historylist as $historydata) { ?>       
                                <ul class="padd-left-ul"><li>  <?php echo $historydata['old_value'];?> > <b><span class="newdata"><?php echo $historydata['new_value']?></span></b> (<?php if($historydata['field_label']==""){echo $historydata['field_name'];}else{echo $historydata['field_label'];}?>)
                            </li></ul>
                            <?php } ?>
                        
                        </td>               
                        <td><?php echo dateformat_time($historydata['changed_date']); ?></td>
                        <td><?php echo $historydata['ip_address']?></td> 
                        
                    </tr>   
                    <?php 
                        $i++; 
                        
                }
            }
            else{
                    echo "<tr><td colspan='4'>No Data Found</td></tr>";
            }?>
        
        </tbody>
    </table>

    </form>
</div>   

<?php

 if(empty($groupedDatas))
 {
    ?><script>$(".history-expand-button").hide(); var historyflg =1;</script><?php
 }
 else{
    ?>
    <script>
        historyflg=0;
        $(document).ready(function () {
            alert("Asdas");
            console.log("ASdas");
            // Approval levels starts
            function approvals(module_number, function_number, approval_step) {
                Swal.fire({
                    title: 'Approve this request?',
                    input: 'textarea',
                    inputLabel: 'Approval Remarks',
                    inputPlaceholder: 'Enter your message or reason...',
                    inputAttributes: {
                        'aria-label': 'Approval message'
                    },
                    showCancelButton: true,  
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, proceed!',
                    cancelButtonText: "No - Cancel",
                    reverseButtons: true,  
                    focusCancel: true,      
                    allowOutsideClick: false,  // Disable outside click
                    preConfirm: (message) => {
                        // if (!message) {
                        //     Swal.showValidationMessage('Approval message is required');
                        // }
                        return message;
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const message = result.value;

                        // Proceed with AJAX call
                        $.ajax({
                            url: baseurl +'quote/approvel_level_action',
                            type: 'POST',
                            data: {
                                module_number: module_number,
                                function_number: function_number,
                                approval_step: approval_step,
                                approval_comments: message,
                                target_url: "<?= getCurrentUrl() ?>",
                            },
                            success: function(response) {
                                location.reload();
                            },
                            error: function(xhr, status, error) {
                                Swal.fire(
                                    'Error!',
                                    'There was an error submitting the approval.',
                                    'error'
                                );
                                console.error(error);
                            }
                        });
                    }
                });
            }

            var approvedLevels = <?php echo json_encode($approved_levels); ?>;
            var approval_permissions = <?= json_encode($my_approval_permissions[0]); ?>;
            
            // Initialize buttons
            $('.first_level, .second_level')
                .addClass('approval-disabled')
                .attr('title', 'You have no permission');

            // Enable buttons based on permissions
            if (approval_permissions.first_level_approval === 'Yes') {
                $('.first_level')
                    .removeClass('approval-disabled')
                    .attr('title', 'First Level Approval');
            }

            if (approval_permissions.second_level_approval === 'Yes') {
                $('.second_level')
                    .removeClass('approval-disabled')
                    .attr('title', 'Second Level Approval');
            }

            // Highlight approved levels and check for cancel button
            let shouldAddCancel = false;
            let hasFirstLevelApproved = false;

            if (approvedLevels && approvedLevels.length > 0) {
                approvedLevels.forEach(function(level) {
                    if (level.approval_step == 1) {
                        $('.first_level').addClass('highlighted');
                        hasFirstLevelApproved = true;
                        if (approval_permissions.first_level_approval === 'Yes') {
                            shouldAddCancel = true;
                        }
                    }
                    if (level.approval_step == 2) {
                        $('.second_level').addClass('highlighted');
                        if (approval_permissions.second_level_approval === 'Yes') {
                            shouldAddCancel = true;
                        }
                    }
                });
            }

            // Add Cancel button if conditions are met
            if (shouldAddCancel && $('.cancel_level').length === 0) {
                $('.breadcrumb-approvals').append(
                    '<li><a href="#" class="cancel_level breaklink" data-level="4">Cancel</a></li>'
                );
            }

            // Handle button clicks
            $(document).on('click', '.breadcrumb-approvals a', function(e) {
                e.preventDefault();

                
                
                if ($(this).hasClass('approval-disabled')) {
                    return false;
                }
                
                const $button = $(this);
                const level = $button.data('level');
                
                if (level == 4) {
                    $('.approval-cancel-container').addClass('show');
                    return;
                }
                
                // Prevent approval for already highlighted levels
                if ($button.hasClass('highlighted')) {
                    $button.attr('title', 'This level is already approved');
                    return false;
                }
                function_number = $("#function_number").val();
                // Handle approval based on level
                switch(level) {
                    case 1:
                        // First level can always be approved if not highlighted
                        approvals('<?=$module_number?>',function_number, level);
                        break;
                        
                    case 2:
                        // Second level requires first level to be approved
                        if (!hasFirstLevelApproved) {
                        //   $('.second_level').attr('title', 'Second level approval is only possible after the first level is approved');
                        Swal.fire({
                            title: 'First Level is not Approved',
                            text: 'Second level approval is only possible after the first level is approved.',
                            icon: 'warning',
                            confirmButtonText: 'OK'
                        });
                        return false;
                        } else {
                            approvals('<?=$module_number?>',function_number, level);
                        }
                        break;
                }
            });
            // Close button handler
            $(document).on('click', '.approval-close-button', function(e) {
                e.preventDefault();
                $('.approval-cancel-container').removeClass('show');
            });

            $('.cancel-approved-btn').on('click', function(e) {
                e.preventDefault();
                $('.cancel-approved-btn').prop('disabled', true); 
        
                if ($("#approved_cancellation_form").valid()) {
                
                    var latest_level = ($("#latest_level").val() == 2) ?"Second Level" : "First Level";
                    var form = $('#approved_cancellation_form')[0];
                    var formData = new FormData(form);
                    Swal.fire({
                        title: "Are you sure?",
                        text: "Do you want to cancel " + latest_level,
                        icon: "question",
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, proceed!',
                        cancelButtonText: "No - Cancel",
                        reverseButtons: true,
                        focusCancel: true,
                        allowOutsideClick: false, // Disable outside click
                    }).then((result) => {
                        if (result.isConfirmed) {

                            $.ajax({
                                url: baseurl +'quote/approval_cancellation', 
                                type: 'POST',
                                data: formData,
                                contentType: false,
                                processData: false,
                                success: function(response) {
                                    location.reload();

                                },
                                error: function(xhr, status, error) {
                                    Swal.fire('Error',
                                        'An error occurred while cancelation',
                                        'error');
                                    console.log(error); // Log any errors
                                }
                            });
                        } else if (result.dismiss === Swal.DismissReason.cancel) {
                            $('.cancel-approved-btn').prop('disabled', false);
                        }
                    });
                } else {
                    $('.cancel-approved-btn').prop('disabled', false);
                }
            });
            // approval levels ends

            
            $(".history-expand-button").on("click", function() {
                $(".history-container").toggleClass("active");
            });
            $(".history-close-button").on("click", function () {
                $(".history-container").removeClass("active");
                });
            $(".logclose-btn").on("click", function () {
                $(".history-container").removeClass("active");
            });   
            $('#log').DataTable({
                paging: true,      // Enable pagination
                searching: true,   // Enable search bar
                ordering: true,    // Enable column sorting
                info: true,        // Show table information
                lengthChange: true, // Enable changing number of rows displayed
                order: [[3, 'desc']],
                columnDefs: [
                    { targets: 0, width: '20%' },
                    { targets: 1, width: '12%' },
                    { targets: 2, width: '' },
                    { targets: 3, width: '15%' },
                    { targets: 4, width: '10%' },
                    // Add more as needed
                ]
            });
        });
        </script>
    <?php
 }  ?>  


<!--     erp2025 add 06-01-2025   Detailed hisory ends-->
<!-- =========================History End=================== -->       
<!-- erp2024 new customer creation modal 26-03-2025 starts -->
 <div class="modal fade" id="newcustomerModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="newcustomerModalLabel" aria-hidden="true">

  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newcustomerModalLabel">New Customer</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" id="cutomer_popup_form" class="form-horizontal" enctype="multipart/form-data">
            <div class="card1">
                <div class="card-content">
                    <div class="card-body1">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link breaklink customer-tabs active show" id="base-custtab1" data-toggle="tab"
                                    aria-controls="custtab1" href="#custtab1" role="tab"
                                    aria-selected="true"><?php echo $this->lang->line('Address') ?></a>
                            </li>
                            <li class="nav-item d-none">
                                <a class="nav-link breaklink customer-tabs" id="base-custtab2" data-toggle="tab" aria-controls="custtab2"
                                    href="#custtab2" role="tab"
                                    aria-selected="false"><?php echo $this->lang->line('Shipping Address') ?></a>
                            </li>
                                <li class="nav-item d-none">
                                <a class="nav-link breaklink customer-tabs" id="base-custtab4" data-toggle="tab" aria-controls="custtab4"
                                    href="#custtab4" role="tab"
                                    aria-selected="false"><?php echo $this->lang->line('CustomFields') ?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link breaklink customer-tabs" id="base-custtab3" data-toggle="tab" aria-controls="custtab3"
                                    href="#custtab3" role="tab"
                                    aria-selected="false"><?php echo $this->lang->line('Customer Details'); ?></a>
                            </li>

                        </ul>
                        <div class="tab-content px-1 pt-1">
                            <div class="tab-pane active show" id="custtab1" role="tabpanel" aria-labelledby="base-custtab1">
                                <div class="form-row mt-1">
                                    <div class="col-12">
                                        <h5 class="popup-title"><?php echo $this->lang->line('Billing Address') ?></h5>
                                        <hr>
                                    </div>
                                    
                                    <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">                                        
                                        <label class="col-form-label" for="name"><?php echo $this->lang->line('Name') ?><span class="compulsoryfld">*</span></label>
                                        <input type="text" placeholder="Name"  class="form-control margin-bottom b_input required" name="name" id="mcustomer_name" required>
                                    </div>

                                    <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label" for="name"><?php echo $this->lang->line('Company') ?></label>
                                        <input type="text" placeholder="Company" class="form-control margin-bottom b_input" name="company">
                                    </div>

                                    <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label" for="phone"><?php echo $this->lang->line('Phone') ?><span class="compulsoryfld">*</span></label>
                                        <input type="text" placeholder="phone" class="form-control margin-bottom required b_input" name="phone" id="mcustomer_phone" required>
                                    </div>                                        

                                    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label" for="email">Email<span class="compulsoryfld">*</span></label>
                                        <input type="email" placeholder="email" class="form-control margin-bottom required b_input" name="email" id="mcustomer_email" required>
                                    </div> 
                                    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label" for="country"><?php echo $this->lang->line('Country') ?><span class="compulsoryfld">*</span></label>
                                        <?php
                                        $countries = country_list();
                                        ?>
                                        <select name="country" id="mcustomer_country" class="form-control margin-bottom" required>
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
                                    
                                    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label" for="city"><?php echo $this->lang->line('City') ?></label>
                                        <input type="text" placeholder="city" class="form-control margin-bottom b_input" name="city" id="mcustomer_city">
                                    </div>
                                    
                                    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label" for="region"><?php echo $this->lang->line('Region') ?></label>
                                        <input type="text" placeholder="Region" class="form-control margin-bottom b_input" name="region" id="region">
                                    </div>                                    
                                                                       
                                    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label" for="postbox"><?php echo $this->lang->line('PostBox') ?></label>
                                        <input type="text" placeholder="PostBox" class="form-control margin-bottom b_input" name="postbox" id="postbox">
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label" for="address"><?php echo $this->lang->line('Address') ?></label>
                                        <input type="hidden" placeholder="address"  class="form-control margin-bottom b_input" name="address2"  id="mcustomer_address2">
                                        <textarea name="address" id="mcustomer_address1" placeholder="address" class="form-textarea margin-bottom b_input"></textarea>
                                    </div>
                                </div>
                           
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <h5 class="popup-title"><?php echo $this->lang->line('Shipping Address') ?> </h5>
                                        <hr>
                                    </div>
                                    <div class="col-12">
                                        <div class="custom-checkbox">   
                                            <input type="checkbox" class="custom-control-input1" name="customer1"     id="copy_address">   <label class="col-form-label custom-control-label1"  for="copy_address"><?php echo $this->lang->line('Same As Billing') ?></label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="btn-group alert alert-info">
                                            <?php echo $this->lang->line("leave Shipping Address") ?>
                                        </div>
                                        <i>*<?php echo $this->lang->line("Shipping Charge is not Refundable") ?></i>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label" for="shipping_name"><?php echo $this->lang->line('Name') ?></label>
                                        <input type="text" placeholder="Name" class="form-control margin-bottom b_input" name="shipping_name" id="mcustomer_name_s">
                                    </div>

                                    <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label" for="shipping_phone"><?php echo $this->lang->line('Phone') ?></label>
                                        <input type="text" placeholder="phone" class="form-control margin-bottom b_input" name="shipping_phone" id="mcustomer_phone_s">
                                    </div>

                                    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label" for="shipping_email">Email</label>
                                        <input type="email" placeholder="email" class="form-control margin-bottom b_input" name="shipping_email" id="mcustomer_email_s">
                                    </div>

                                    <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label" for="shipping_country"><?php echo $this->lang->line('Country') ?></label>
                                        <!-- <input type="text" placeholder="Country" class="form-control margin-bottom b_input" name="shipping_country" id="mcustomer_country_s"> -->
                                        <select name="shipping_country" id="mcustomer_country_s" class="form-control margin-bottom">
                                            <?php
                                                // echo "<option value=''>Select Country</option>";
                                                foreach ($countries as $row) {
                                                    $cid = $row['id'];
                                                    $title = $row['name'];
                                                    $code = $row['code'];
                                                    echo "<option value='$cid'>$title($code)</option>";
                                                }
                                            ?>
                                        </select>
                                    </div>

                                    <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label" for="shipping_city"><?php echo $this->lang->line('City') ?></label>
                                        <input type="text" placeholder="city" class="form-control margin-bottom b_input" name="shipping_city" id="mcustomer_city_s">
                                    </div>

                                    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label" for="shipping_region"><?php echo $this->lang->line('Region') ?></label>
                                        <input type="text" placeholder="Region" class="form-control margin-bottom b_input" name="shipping_region" id="shipping_region">
                                    </div>

                                    

                                    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label" for="postbox"><?php echo $this->lang->line('PostBox') ?></label>
                                        <input type="text" placeholder="PostBox" class="form-control margin-bottom b_input" name="shipping_postbox" id="shipping_postbox">
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label" for="address"><?php echo $this->lang->line('Address') ?></label>
                                        <input type="hidden" placeholder="address_s1" class="form-control margin-bottom b_input" name="address_s1" id="mcustomer_address1_s1">
                                        <textarea name="shipping_address_1" id="mcustomer_address1_s" placeholder="address" class="form-textarea margin-bottom b_input"></textarea>
                                    </div>

                                    <div class="col-12 text-right">
                                        <button type="button" data-dismiss="modal" aria-label="Close" class="btn btn-crud btn-secondary">
                                            Cancel
                                        </button>
                                        <button id="nextBtn" type="button" class="btn btn-primary btn-crud">Next</button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="tab-pane" id="custtab3" role="tabpanel" aria-labelledby="base-custtab3">
                                <!-- erp2024 newly added 01-06-2024 -->
                                <div class="form-row">
                                    <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                            <label class="col-form-label" for="Registration Number"><?php echo $this->lang->line('Registration Number') ?> </label>
                                            <input type="text" placeholder="Registration Number" class="form-control margin-bottom b_input" name="registration_number" id="registration_number">
                                    </div>
                                    
                                    <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label" for="Expiry Date"><?php echo $this->lang->line('Expiry Date') ?> </label>
                                        <input type="date" placeholder="Expiry Date" class="form-control margin-bottom b_input" name="expiry_date" id="expiry_date">
                                    </div>
                                
                                    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label" for="Computer Card"><?php echo $this->lang->line('Computer Card') ?> </label>
                                        <div class="col-12 row">
                                        <input type="text" placeholder="Computer Card Number" class="form-control margin-bottom b_input col-7" name="computer_card_number" id="computer_card_number">             
                                        <input type="file" placeholder="Computer Card" class="form-control margin-bottom b_input col-4 mr-1" name="computer_card_image" id="computer_card_image">
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label" for="Sponser ID"><?php echo $this->lang->line('Sponser ID') ?> </label>
                                        <div class="col-12 row">
                                            <input type="text" placeholder="Sponser ID" class="form-control margin-bottom b_input col-7" name="sponser_id" id="sponser_id">                                        
                                            <input type="file" placeholder="Sponser image" class="form-control margin-bottom b_input col-4 mr-2" name="sponser_image" id="sponser_image">
                                        </div>
                                    </div>

                                    <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label" for="Credit Limit"><?php echo $this->lang->line('Credit Limit') ?><span class="compulsoryfld">*</span> </label>
                                        <input type="number" placeholder="Credit Limit" class="form-control margin-bottom b_input" name="credit_limit" id="credit_limit" required>
                                    </div>                                       

                                    <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label" for="Credit Period"><?php echo $this->lang->line('Credit Period') ?><span class="compulsoryfld">*</span> </label>
                                        <input type="number" placeholder="No. of days" class="form-control margin-bottom b_input" name="credit_period" id="credit_period" required>
                                    </div>


                                <!-- erp2024 newly added 01-06-2024 -->

                                    <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label" for="Discount"><?php echo $this->lang->line('Discount') ?> </label>
                                        <input type="text" placeholder="Custom Discount" class="form-control margin-bottom b_input" name="discount">
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label" for="tax_id"><?php echo $this->lang->line('TAX') ?> ID</label>
                                        <input type="text" placeholder="TAX ID" class="form-control margin-bottom b_input" name="tax_id">
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label" for="document_id"><?php echo $this->lang->line('Document') ?> ID</label>
                                        <input type="text" placeholder="Document ID" class="form-control margin-bottom b_input" name="document_id">
                                    </div>

                                    <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label" for="c_field"><?php echo $this->lang->line('Extra') ?> </label>
                                        <input type="text" placeholder="Custom Field" class="form-control margin-bottom b_input" name="c_field">
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label" for="customergroup"><?php echo $this->lang->line('Customer group') ?></label>
                                        <select name="customergroup" class="form-control b_input">   <?php   foreach ($customergrouplist as $row) {       $cid = $row['id'];       $title = $row['title'];       echo "<option value='$cid'>$title</option>";   }   ?>
                                        </select>
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label" for="language">Language</label>
                                        <select name="language" id="language" class="form-control b_input" style="width:100%">   <?php   echo $langs;   ?></select>
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label" for="currency"><?php echo $this->lang->line('customer_login') ?></label>
                                        <select name="c_login" class="form-control b_input">   <option value="1"><?php echo $this->lang->line('Yes') ?></option>   <option value="0"><?php echo $this->lang->line('No') ?></option>
                                        </select>
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label" for="password_c"><?php echo $this->lang->line('New Password') ?></label>
                                        <input type="text" placeholder="Leave blank for auto generation"  class="form-control margin-bottom b_input" name="password_c" id="password_c">
                                    </div>
                                    <!-- erp2024 new field 03-06-2024 -->
                                    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label" for="currency"><?php echo $this->lang->line('Profile Picture') ?></label>
                                        <input type="file" placeholder="Profile Picture" class="form-control margin-bottom b_input" name="picture" id="picture">
                                    </div>

                                    <!-- erp2024 newly added sales man -->
                                    <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label" for="currency"><?php echo $this->lang->line('Sales Man') ?></label>
                                        <select name="salesman_id" id="saleman_id" class="form-control form-select">
                                            <option value="">Select Salesman</option>
                                            <?php
                                                if(!empty($salesmanlist))
                                                {
                                                    foreach ($salesmanlist as $key => $value) {
                                                        echo "<option value='".$value['id']."'>".ucwords($value['name'])."</option>";
                                                    }
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12 d-none">
                                        <label class="col-form-label" for="Status"><?php echo $this->lang->line('Status') ?></label>
                                        <select name="status" id="status" class="form-control form-select">
                                            <option value="Enable">Enable</option>
                                            <option value="Disable">Disable</option>
                                        </select>
                                    </div>
                                    <!-- erp2024 newly added sales man -->
                                </div>
                                <br><h5><b>Contact Details</b></h5><hr>
                                <div class="form-row">
                                    <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">                                    
                                        <label class="col-form-label" for="Contact Person"><?php echo $this->lang->line('Contact Person') ?> </label>
                                        <input type="text" placeholder="Contact Person's Name" class="form-control margin-bottom b_input" name="contact_person" id="contact_person">
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label" for="contact_designation"><?php echo $this->lang->line('Designation') ?> </label>
                                        <input type="text" placeholder="Designation" class="form-control margin-bottom b_input" name="contact_designation" id="contact_designation" value="<?php echo $customer['contact_designation'] ?>">
                                    </div>  
                                    <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label" for="Land Line"><?php echo $this->lang->line('Land Line') ?> </label>
                                        <input type="text" placeholder="Land Line" class="form-control margin-bottom b_input" name="land_line" id="land_line">
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label" for="Contact Phone1"><?php echo $this->lang->line('Contact Phone1') ?> </label>
                                        <input type="text" placeholder="Contact Phone1" class="form-control margin-bottom b_input" name="contact_phone1" id="contact_phone1">
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label" for="Contact Phone2"><?php echo $this->lang->line('Contact Phone2') ?> </label>
                                        <input type="text" placeholder="Contact Phone2" class="form-control margin-bottom b_input" name="contact_phone2" id="contact_phone2">
                                    </div>                    
                                    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">             
                                        <label class="col-form-label" for="Contact Email1"><?php echo $this->lang->line('Contact Email1') ?> </label>
                                        <input type="email" placeholder="Contact Email1" class="form-control margin-bottom b_input" name="contact_email1" id="contact_email1">
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label" for="Contact Email2"><?php echo $this->lang->line('Contact Email2') ?> </label>
                                        <input type="email" placeholder="Contact Email2" class="form-control margin-bottom b_input" name="contact_email2" id="contact_email2">
                                    </div>

                                    <!-- erp2024 new field 03-06-2024 -->
                                    <div class="col-12 text-right">
                                            <button type="button" data-dismiss="modal" aria-label="Close" class="btn btn-crud btn-secondary">
                                                Cancel
                                            </button>
                                            <button id="prevBtn" type="button" class="btn btn-secondary btn-crud">Previous</button>
                                            <input type="submit" id="add_customer_btn" class="btn btn-crud btn-primary margin-bottom float-xs-right mr-2" value="<?php echo $this->lang->line('Add customer') ?>"  data-loading-text="Adding...">
                                    </div>
                                </div> 
                            </div>

                            <div class="tab-pane show" id="custtab4" role="tabpanel" aria-labelledby="base-custtab4">

                            <div class="form-group row">
                                <?php                                 
                                foreach ($custom_fields as $row) {
                                    if ($row['f_type'] == 'text') { ?>
                                        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                            <label class="col-form-label" for="document_id"><?= $row['name'] ?></label>
                                            <input type="text" placeholder="<?= $row['placeholder'] ?>" class="form-control margin-bottom b_input <?= $row['other'] ?>"  name="custom[<?= $row['id'] ?>]">   
                                        </div>                                            
                                    <?php }
                                }
                                ?>
                                </div>

                            </div>
                            <!-- <div id="mybutton" class="submit-section text-right mb-2">
                                <button type="button" data-dismiss="modal" aria-label="Close" class="btn btn-crud btn-secondary">
                                    Close
                                </button>
                                
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- erp2024 remove action url -->
            <!-- <input type="hidden" value="customers/addcustomer" id="action-url"> -->
        </form>
      </div>
     
    </div>
  </div>
</div>
<!-- erp2024 new customer creation modal 26-03-2025 ends -->

<!-- erp2024 new Supplier creation modal 26-03-2025 starts -->
<div class="modal fade" id="newsupplierModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="newsupplierModalLabel" aria-hidden="true">
<!-- ======================== -->
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="newsupplierModalLabel">New Supplier</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
            <form method="post" id="supplier_popup_form" class="form-horizontal" enctype="multipart/form-data">
                <div class="card1">
                    <div class="card-content">
                        <div class="card-body1">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link breaklink customer-tabs active show" id="base-supplierTab1" data-toggle="tab"
                                        aria-controls="supplierTab1" href="#supplierTab1" role="tab"
                                        aria-selected="true"><?php echo $this->lang->line('Address') ?></a>
                                </li>
                                <li class="nav-item d-none">
                                    <a class="nav-link breaklink customer-tabs" id="base-supplierTab2" data-toggle="tab" aria-controls="supplierTab2"
                                        href="#supplierTab2" role="tab"
                                        aria-selected="false"><?php echo $this->lang->line('Shipping Address') ?></a>
                                </li>
                                    <li class="nav-item d-none">
                                    <a class="nav-link breaklink customer-tabs" id="base-supplierTab4" data-toggle="tab" aria-controls="supplierTab4"
                                        href="#supplierTab4" role="tab"
                                        aria-selected="false"><?php echo $this->lang->line('CustomFields') ?></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link breaklink customer-tabs" id="base-supplierTab3" data-toggle="tab" aria-controls="supplierTab3"
                                        href="#supplierTab3" role="tab"
                                        aria-selected="false"><?php echo $this->lang->line('Customer Details'); ?></a>
                                </li>

                            </ul>
                            <div class="tab-content px-1 pt-1">
                                <div class="tab-pane active show" id="supplierTab1" role="tabpanel" aria-labelledby="base-supplierTab1">
                                    <div class="form-row mt-1">
                                        <div class="col-12">
                                            <h5 class="popup-title"><?php echo $this->lang->line('Billing Address') ?></h5>
                                            <hr>
                                        </div>
                                        
                                        
                                        <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                                <label class="col-form-label" for="name"><?php echo $this->lang->line('Name') ?><span class="compulsoryfld">*</span></label>
                                                <input type="text" placeholder="Name"
                                                    class="form-control margin-bottom b_input required" name="name"
                                                    id="msupplier_name">
                                            </div>
                                            <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                                <label class="col-form-label"
                                                for="name"><?php echo $this->lang->line('Company') ?></label>
                                                <input type="text" placeholder="Company"
                                                    class="form-control margin-bottom b_input" name="company">
                                            </div>
                                            <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                                <label class="col-form-label"
                                                for="phone"><?php echo $this->lang->line('Phone') ?><span class="compulsoryfld">*</span></label>
                                                    <input type="text" placeholder="phone"
                                                    class="form-control margin-bottom required b_input" name="phone"
                                                    id="msupplier_phone">
                                            </div>
                                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">

                                                <label class="col-form-label" for="email">Email<span class="compulsoryfld">*</span></label>
                                                <input type="email" placeholder="email"
                                                    class="form-control margin-bottom required b_input" name="email"
                                                    id="msupplier_email">
                                            </div>
                                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                                <label class="col-form-label"
                                                for="country"><?php echo $this->lang->line('Country') ?></label>
                                                <select name="country" id="msupplier_country" class="form-control margin-bottom" required>
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
                                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                                <label class="col-form-label"
                                                for="city"><?php echo $this->lang->line('City') ?></label>
                                                <input type="text" placeholder="city"
                                                    class="form-control margin-bottom b_input" name="city"
                                                    id="msupplier_city">
                                            </div>
                                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                                <label class="col-form-label"
                                                for="region"><?php echo $this->lang->line('Region') ?></label>
                                                <input type="text" placeholder="Region"
                                                    class="form-control margin-bottom b_input" name="region"
                                                    id="msupplier_region">
                                            </div>
                                        
                                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                                <label class="col-form-label"
                                                for="postbox"><?php echo $this->lang->line('PostBox') ?></label>
                                                <input type="text" placeholder="PostBox"
                                                    class="form-control margin-bottom b_input" name="postbox"
                                                    id="msupplier_postbox">
                                            </div>
                                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                                <label class="col-form-label"
                                                for="address"><?php echo $this->lang->line('Address') ?></label>
                                                <!-- <input type="text" placeholder="address"
                                                    class="form-control margin-bottom b_input" name="address"
                                                    id="msupplier_address1"> -->
                                                <textarea class="form-textarea margin-bottom b_input" placeholder="Address" name="address"
                                                id="msupplier_address1"></textarea>
                                            </div>

                                    </div>
                                
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <h5 class="popup-title"><?php echo $this->lang->line('Shipping Address') ?> </h5>
                                            <hr>
                                        </div>
                                        <div class="col-12">
                                            <div class="custom-checkbox">   
                                                <input type="checkbox" class="custom-control-input1" name="customer1"     id="copy_supplier_address">   <label class="col-form-label custom-control-label1"  for="copy_supplier_address"><?php echo $this->lang->line('Same As Billing') ?></label>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="btn-group alert alert-info">
                                                <?php echo $this->lang->line("leave Shipping Address") ?>
                                            </div>
                                            <i>*<?php echo $this->lang->line("Shipping Charge is not Refundable") ?></i>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                                <label class="col-form-label"
                                                for="shipping_name"><?php echo $this->lang->line('Name') ?></label>
                                                <input type="text" placeholder="Name"
                                                    class="form-control margin-bottom b_input" name="shipping_name"
                                                    id="msupplier_name_s">
                                            </div>
                                            <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                                <label class="col-form-label"
                                                for="shipping_phone"><?php echo $this->lang->line('Phone') ?></label>
                                                <input type="text" placeholder="phone"
                                                    class="form-control margin-bottom b_input" name="shipping_phone"
                                                    id="msupplier_phone_s">
                                            </div>
                                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                                <label class="col-form-label" for="shipping_email">Email</label>
                                                <input type="email" placeholder="email"
                                                    class="form-control margin-bottom b_input" name="shipping_email"
                                                    id="msupplier_email_s">
                                            </div>
                                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                                <label class="col-form-label"
                                                for="shipping_country"><?php echo $this->lang->line('Country') ?></label>
                                                <select name="shipping_country" id="msupplier_country_s" class="form-control margin-bottom" >
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
                                            
                                            <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                                <label class="col-form-label"
                                                for="shipping_city"><?php echo $this->lang->line('City') ?></label>
                                                <input type="text" placeholder="city"
                                                    class="form-control margin-bottom b_input" name="shipping_city"
                                                    id="msupplier_city_s">
                                            </div>
                                            <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                                <label class="col-form-label"
                                                for="shipping_region"><?php echo $this->lang->line('Region') ?></label>
                                                <input type="text" placeholder="Region"
                                                    class="form-control margin-bottom b_input" name="shipping_region"
                                                    id="msupplier_region_s">
                                            </div>
                                        
                                            <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                                <label class="col-form-label"
                                                for="postbox"><?php echo $this->lang->line('PostBox') ?></label>
                                                <input type="text" placeholder="PostBox"
                                                    class="form-control margin-bottom b_input" name="shipping_postbox"
                                                    id="msupplier_postbox_s">
                                            </div>
                                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                                <label class="col-form-label"for="address"><?php echo $this->lang->line('Address') ?></label>
                                                <!-- <input type="text" placeholder="shipping_address_1"
                                                    class="form-control margin-bottom b_input" name="shipping_address_1"
                                                    id="msupplier_address1_s"> -->
                                                <textarea class="form-textarea margin-bottom b_input" placeholder="Address" name="shipping_address_1" id="msupplier_address1_s"></textarea>
                                            </div>

                                            <div class="col-12 text-right">
                                                <button type="button" data-dismiss="modal" aria-label="Close" class="btn btn-crud btn-secondary">
                                                    Cancel
                                                </button>
                                                <button id="nextSupplierBtn" type="button" class="btn btn-primary">Next</button>
                                            </div>
                                    </div>
                                </div>
                                
                                <div class="tab-pane" id="supplierTab3" role="tabpanel" aria-labelledby="base-supplierTab3">
                                    <!-- erp2024 newly added 01-06-2024 -->
                                    <div class="form-row">
                                        <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
                                        <label class="col-form-label" for="Registration Number"><?php echo $this->lang->line('Registration Number') ?><span class="compulsoryfld">*</span></label>
                                            <input type="text" placeholder="Registration Number" class="form-control margin-bottom b_input" name="registration_number" id="registration_number" required>
                                        </div>
                                        <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
                                            <label class="col-form-label"   for="Expiry Date"><?php echo $this->lang->line('Expiry Date') ?> </label>
                                            <input type="date" placeholder="Expiry Date" class="form-control margin-bottom b_input" name="expiry_date" id="expiry_date">
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                            <label class="col-form-label" for="Computer Card"><?php echo $this->lang->line('Computer Card') ?> </label>
                                            <div class="col-12 row">
                                                <input type="text" placeholder="Computer Card Number" class="form-control margin-bottom b_input col-4" name="computer_card_number" id="computer_card_number">
                                                <div class="col-lg-8 col-md-8 col-sm-12">
                                                    <div class="d-flex">
                                                        <input type="file" name="computer_card_image" id="computer_card_image" class="form-control1 input-file fileclass" accept=" .jpg, .jpeg, .png,.pdf" onchange="readURL(this);">
                                                        <img class="blah" src="" alt="your image" style="margin-left:10px; width:50px; height:50px;">
                                                        <button type="button" class="btn btn-crud btn-secondary btn-sm delete-btn-file-only" style="height:30px; height:30px; margin:3px;"><i class="fa fa-trash" ></i></button>
                                                    </div>
                                                </div>
                                                <!-- <input type="file" placeholder="Computer Card" class="form-control margin-bottom b_input " name="computer_card_image" id="computer_card_image"> -->
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                            <label class="col-form-label" for="Sponser ID"><?php echo $this->lang->line('Sponser ID') ?> </label>
                                            <div class="col-12 row">
                                                <input type="text" placeholder="Sponser ID" class="form-control margin-bottom b_input col-4" name="sponser_id" id="sponser_id">
                                                <div class="col-lg-8 col-md-8 col-sm-12">
                                                    <div class="d-flex">
                                                        <input type="file" name="sponser_image" id="sponser_image" class="form-control1 input-file fileclass" accept=" .jpg, .jpeg, .png,.pdf" onchange="readURL(this);">
                                                        <img class="blah" src="" alt="your image" style="margin-left:10px; width:50px; height:50px;">
                                                        <button type="button" class="btn btn-crud btn-secondary btn-sm delete-btn-file-only" style="height:30px; height:30px; margin:3px;"><i class="fa fa-trash" ></i></button>
                                                    </div>
                                                </div>
                                                <!-- <input type="file" placeholder="Sponser image" class="form-control margin-bottom b_input col-8" name="sponser_image" id="sponser_image"> -->
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
                                            <label class="col-form-label"   for="Credit Period"><?php echo $this->lang->line('Credit Period Allowed') ?> </label>
                                            <input type="number" placeholder="No. of days" class="form-control margin-bottom b_input" name="credit_period" id="credit_period">
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                            <label class="col-form-label" for="Credit Limit"><?php echo $this->lang->line('Credit Limit Allowed to the Company') ?> </label>
                                            <input type="number" placeholder="Credit Limit" class="form-control margin-bottom b_input" name="credit_limit" id="credit_limit">
                                        </div>                                        
                                       
                                    <!-- erp2024 newly added 01-06-2024 -->
                                     
                                        <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
                                            <label class="col-form-label" for="Discount"><?php echo $this->lang->line('Discount') ?> </label>
                                            <input type="text" placeholder="Custom Discount" class="form-control margin-bottom b_input" name="discount">
                                        </div>                                    
                                        <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
                                                <label class="col-form-label" for="tax_id"><?php echo $this->lang->line('TAX') ?> ID</label>
                                                <input type="text" placeholder="TAX ID" class="form-control margin-bottom b_input" name="tax_id">
                                        </div>
                                        <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
                                            <label class="col-form-label" for="document_id"><?php echo $this->lang->line('Document') ?> ID</label>
                                                <input type="text" placeholder="Document ID" class="form-control margin-bottom b_input" name="document_id">
                                        </div>
                                        
                                        <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12 d-none">
                                            <label class="col-form-label" for="c_field"><?php echo $this->lang->line('Extra') ?> </label>
                                            <input type="text" placeholder="Custom Field" class="form-control margin-bottom b_input" name="c_field">
                                        </div>
                                        <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12 d-none">
                                            <label class="col-form-label" for="customergroup"><?php echo $this->lang->line('Customer group') ?></label>
                                            <select name="customergroup" class="form-control b_input">
                                                <?php
                                                foreach ($customergrouplist as $row) {
                                                    $cid = $row['id'];
                                                    $title = $row['title'];
                                                    echo "<option value='$cid'>$title</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>                                    
                                        <div  class="col-lg-2 col-md-3 col-sm-6 col-xs-12 d-none1">
                                            <label class="col-form-label" for="language">Language</label>
                                            <select name="language" id="language1" class="form-control b_input" style="width:100%">
                                                <?php
                                                echo $langs;
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12 d-none1">
                                            <label class="col-form-label" for="currency"><?php echo $this->lang->line('Supplier Login') ?></label>
                                            <select name="c_login" class="form-control b_input">
                                                <option value="1"><?php echo $this->lang->line('Yes') ?></option>
                                                <option value="0"><?php echo $this->lang->line('No') ?></option>
                                            </select>
                                        </div>
                                        <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12 d-none1">
                                            <label class="col-form-label" for="password_c"><?php echo $this->lang->line('New Password') ?></label>
                                            <input type="text" placeholder="Leave blank for auto generation"  class="form-control margin-bottom b_input" name="password_c" id="password_c">
                                        </div>
                                    <!-- erp2024 new field 03-06-2024 -->
                                    <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
                                         <label class="col-form-label" for="currency"><?php echo $this->lang->line('Profile Picture') ?></label>
                                        <div class="d-flex">
                                            <input type="file" name="picture" id="picture" class="form-control1 input-file fileclass" accept=" .jpg, .jpeg, .png" onchange="imgreadURL(this);">
                                            <img class="blah" src="" alt="your image" style="margin-left:10px; width:50px; height:50px;">
                                            <button type="button" class="btn btn-crud btn-secondary btn-sm delete-btn-file-only" style="height:30px; height:30px; margin:3px;"><i class="fa fa-trash" ></i></button>
                                        </div>
                                        </div>
                                    </div>
                                    <br><h5><b>Contact Details</b></h5><hr>
                                    <div class="form-row">     
                                        <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">                                   
                                            <label class="col-form-label mb-1" for="Contact Person"><?php echo $this->lang->line('Contact Person') ?> </label>
                                            <input type="text" placeholder="Contact Person's Name" class="form-control margin-bottom b_input" name="contact_person" id="contact_person">
                                        </div>
                                        
                                        <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
                                            <label class="col-form-label mb-1"   for="contact_designation"><?php echo $this->lang->line('Designation') ?> </label>
                                            <input type="text" placeholder="Designation" class="form-control margin-bottom b_input" name="contact_designation" id="contact_designation" value="<?php echo $customer['contact_designation'] ?>">
                                        </div>  
                                        <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
                                            <label class="col-form-label mb-1"   for="Land Line"><?php echo $this->lang->line('Land Line') ?> </label>
                                            <input type="text" placeholder="Land Line" class="form-control margin-bottom b_input" name="land_line" id="land_line">
                                        </div>
                                        <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
                                            <label class="col-form-label mb-1" for="Contact Phone1"><?php echo $this->lang->line('Contact Phone1') ?> </label>
                                            <input type="text" placeholder="Contact Phone1" class="form-control margin-bottom b_input" name="contact_phone1" id="contact_phone1">
                                        </div>
                                        <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
                                            <label class="col-form-label mb-1"   for="Contact Phone2"><?php echo $this->lang->line('Contact Phone2') ?> </label>
                                            <input type="text" placeholder="Contact Phone2" class="form-control margin-bottom b_input" name="contact_phone2" id="contact_phone2">
                                        </div>
                                        <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">                         
                                            <label class="col-form-label mb-1" for="Contact Email1"><?php echo $this->lang->line('Contact Email1') ?> </label>
                                            <input type="email" placeholder="Contact Email1" class="form-control margin-bottom b_input" name="contact_email1" id="contact_email1">
                                        </div>
                                        <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
                                            <label class="col-form-label mb-1"   for="Contact Email2"><?php echo $this->lang->line('Contact Email2') ?> </label>
                                            <input type="email" placeholder="Contact Email2" class="form-control margin-bottom b_input" name="contact_email2" id="contact_email2">
                                        </div>
                                    </div> 
                                    <br><h5><b>Bank  Details</b></h5><hr> 
                                    <div class="form-row">                      
                                        <div class="col-sm-4 mb-1">                                      
                                            <label class="col-form-label" for="Contact Person"><?php echo $this->lang->line('Account Number') ?> </label>
                                            <input type="text" placeholder="Account Number" class="form-control margin-bottom b_input" name="account_number" id="account_number" value="<?php echo $customer['account_number'] ?>">
                                        </div>
                                        <div class="col-sm-4 mb-1">
                                            <label class="col-form-label"   for="account_holder"><?php echo $this->lang->line('Account Holder') ?> </label>
                                            <input type="text" placeholder="Account Holder" class="form-control margin-bottom b_input" name="account_holder" id="account_holder" value="<?php echo $customer['account_holder'] ?>">
                                        </div>
                                        <div class="col-sm-4 mb-1">
                                            <label class="col-form-label"   for="bank_name"><?php echo $this->lang->line('Bank Name') ?> </label>
                                            <input type="text" placeholder="Bank Name" class="form-control margin-bottom b_input" name="bank_name" id="bank_name" value="<?php echo $customer['bank_name'] ?>">
                                        </div>
                                        <div class="col-sm-4 mb-1">                                       
                                            <label class="col-form-label" for="Bank Country"><?php echo $this->lang->line('Bank Country') ?> </label>
                                            <select name="bank_country" id="bank_country" class="form-control margin-bottom">
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
                                        <div class="col-sm-4 mb-1">                                       
                                            <label class="col-form-label"   for="Bank Location"><?php echo $this->lang->line('Bank Location') ?> </label>
                                            <input type="text" placeholder="Bank Location" class="form-control margin-bottom b_input" name="bank_location" id="bank_location" value="<?php echo $customer['bank_location'] ?>">
                                        </div>
                                    </div> 
                                    <!-- erp2024 new field 03-06-2024 -->

                                        <!-- erp2024 new field 03-06-2024 -->
                                        <div class="col-12 text-right">
                                                <button type="button" data-dismiss="modal" aria-label="Close" class="btn btn-crud btn-secondary">
                                                    Cancel
                                                </button>
                                                <button id="prevSupplierBtn" type="button" class="btn btn-secondary">Previous</button>
                                                <input type="submit" id="add_supplier_btn" class="btn btn-crud btn-primary margin-bottom float-xs-right mr-2" value="<?php echo $this->lang->line('Add Supplier') ?>"  data-loading-text="Adding...">
                                        </div>
                                    </div> 
                                </div>

                                <div class="tab-pane show" id="supplierTab4" role="tabpanel" aria-labelledby="base-supplierTab4">

                                <div class="form-group row">
                                    <?php                                 
                                    foreach ($custom_fields as $row) {
                                        if ($row['f_type'] == 'text') { ?>
                                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                                <label class="col-form-label" for="document_id"><?= $row['name'] ?></label>
                                                <input type="text" placeholder="<?= $row['placeholder'] ?>" class="form-control margin-bottom b_input <?= $row['other'] ?>"  name="custom[<?= $row['id'] ?>]">   
                                            </div>                                            
                                        <?php }
                                    }
                                    ?>
                                    </div>

                                </div>
                                <!-- <div id="mybutton" class="submit-section text-right mb-2">
                                    <button type="button" data-dismiss="modal" aria-label="Close" class="btn btn-crud btn-secondary">
                                        Close
                                    </button>
                                    
                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- erp2024 remove action url -->
                <!-- <input type="hidden" value="customers/addcustomer" id="action-url"> -->
            </form>
            </div>
        
        </div>
    </div>
<!-- ================== -->
</div>
<!-- erp2024 new Supplier creation modal 26-03-2025 ends -->

<?php
    function get_productcode($productname){
        preg_match('/\(([^()]*)\)[^()]*$/', $productname, $matches);
        if (isset($matches[1])) {
            $result = $matches[1];
            return $result;
        } else {
            return false;
        }
    }
?>
</div>
<!-- BEGIN VENDOR JS-->
<script type="text/javascript">
   
    // erp2024 new customer add section starts 26-03-2025 starts
     //erp2024 customer add section 03-06-2024
     $("#cutomer_popup_form").validate({
        ignore: [], // Important: Do not ignore hidden fields
        rules: {
            name: {required:true},
            phone: {required:true, phoneRegex :true},
            shipping_phone: {phoneRegex :true},
            country: {required:true},
            credit_limit: {required:true},
            credit_period: {required:true},
            email: {
                required: true,
                email: true
            },
        },
        messages: {
            name  : "Enter Name",
            phone  : "Enter Valid Phone Number",
            shipping_phone  : "Enter Valid Phone Number",
            country  : "Select Country",
            email  : "Enter Email",
            credit_limit  : "Enter Credit Limit",
            credit_period  : "Enter Credit Period in days",
        },
        errorElement: "em",
        errorPlacement: function ( error, element ) {             
            error.addClass( "help-block" ); 
            if ( element.prop( "type" ) === "checkbox" ) {
                error.insertAfter( element.parent( "label" ) );
            }else {
                error.insertAfter( element );
            }
        },
        highlight: function ( element, errorClass, validClass ) {
            $( element ).parents( ".col-sm-5" ).addClass( "has-error" ).removeClass( "has-success" );
        },
        unhighlight: function (element, errorClass, validClass) {
            $( element ).parents( ".col-sm-5" ).addClass( "has-success" ).removeClass( "has-error" );
        },
    });


    // function for premission deny or allow 16-04-2025
    // const grand_permissions = <?php //echo json_encode($permissions); ?>;

    // $.ajax({
    //     url: baseurl + 'user/check_permission_ajax',
    //     method: 'POST',
    //     data: { permissions: grand_permissions },
    //     success: function (response) {
    //         if (response !== 'true') {
    //             $('.content-body').html(response);
    //         }
    //     },
    //     error: function () {
    //         $('.content-body').html(
    //             '<div class="alert alert-danger">Permission check failed (AJAX error).</div>'
    //         );
    //     }
    // });

    
    $("#add_customer_btn").on("click", function (e) {
        e.preventDefault();
        $('#add_customer_btn').prop('disabled', true);
        if ($("#cutomer_popup_form").valid()) {

            var formData = new FormData($("#cutomer_popup_form")[0]);
        
            Swal.fire({
               title: "Are you sure?",
               text: "Do you want to Create customer?",
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
                        url: baseurl + 'Customers/addcustomer',
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: "json",
                        success: function(response) { 
                            if (typeof response === "string") {
                                response = JSON.parse(response.trim());
                            }
                            
                            if(pageName1=="invoices/customer_leads")
                            {
                                $("#customer-search").val($("#mcustomer_name").val());
                                $("#customer_phone").val($("#mcustomer_phone").val());
                                $("#customer_email").val($("#mcustomer_email").val());
                                $("#customer_id").val(response.cid);
                                $("#customer_address").val($("#mcustomer_address1").val());
                            }
                            else{
                                selectCustomer(response.cid, response.cname,response.cadd2, response.ph, response.email, response.discount,response.credit_period,response.credit_limit,response.avalable_credit_limit);
                            }
                            // $("#console.log("Customer ID (cid):", response.cid);").val();

                            $("#newcustomerModal").modal('hide');
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
               }
               else{
                    $('#add_customer_btn').prop('disabled', false);
               }
            });
        }
        else {
            $('#add_customer_btn').prop('disabled', false);
            $('html, body').animate({
                scrollTop: $("#cutomer_popup_form").offset().top
            }, 2000);
            $("#cutomer_popup_form").focus();            
            $('.alert-dismissible').removeClass('d-none');
        }
    });
    // erp2024 new customer add section starts 26-03-2025 ends

    // erp2024 new Supplier add section starts 09-04-2025 starts

    $("#supplier_popup_form").validate({
        ignore: [],
        rules: {
            name: { required: true },
            phone: {
                required: true,
                phoneRegex :true
            },
            shipping_phone: {
                phoneRegex :true
            },
            email: {
                required: true,
                email: true
            }
        },
        messages: {
            name: "Enter Name",
            phone: "Enter a valid phone number",
            shipping_phone: "Enter a valid phone number",
            email: "Enter a valid Email"
        },
        errorElement: "em",
        errorPlacement: function (error, element) {
            error.addClass("help-block");
            if (element.prop("type") === "checkbox") {
                error.insertAfter(element.parent("label"));
            } else {
                error.insertAfter(element);
            }
        },
        highlight: function (element, errorClass, validClass) {
            $(element).parents(".col-sm-5").addClass("has-error").removeClass("has-success");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).parents(".col-sm-5").addClass("has-success").removeClass("has-error");
        }
    });
    $("#add_supplier_btn").on("click", function (e) {
        e.preventDefault();
        $('#add_supplier_btn').prop('disabled', true);
        if ($("#supplier_popup_form").valid()) {

            var formData = new FormData($("#supplier_popup_form")[0]);
        
            Swal.fire({
               title: "Are you sure?",
               text: "Do you want to Create a Supplier?",
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
                        url: baseurl + 'Supplier/add_new_supplier',
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: "json",
                        success: function(response) { 
                            if (typeof response === "string") {
                                response = JSON.parse(response.trim());
                            }
                            
                            selectSupplier(response.cid, response.name, response.city, response.ph, response.email);                         
                            $("#newsupplierModal").modal('hide');
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
               }
               else{
                    $('#add_supplier_btn').prop('disabled', false);
               }
            });
        }
        else {
            $('#add_supplier_btn').prop('disabled', false);
            $('html, body').animate({
                scrollTop: $("#supplier_popup_form").offset().top
            }, 2000);
            $("#supplier_popup_form").focus();            
            $('.alert-dismissible').removeClass('d-none');
        }
    });
    // erp2024 new Supplier add section starts 09-04-2025 ends



    $('[data-toggle="datepicker"]').datepicker({
        autoHide: true,
        format: '<?php echo $this->config->item('dformat2'); ?>'
    });
    $('[data-toggle="datepicker"]').datepicker('setDate', '<?php echo dateformat(date('Y-m-d')); ?>');

    $('#sdate').datepicker({autoHide: true, format: '<?php echo $this->config->item('dformat2'); ?>'});
    $('#sdate').datepicker('setDate', '<?php echo dateformat(date('Y-m-d', strtotime('-30 days', strtotime(date('Y-m-d'))))); ?>');
    $('.date30').datepicker({autoHide: true, format: '<?php echo $this->config->item('dformat2'); ?>'});
    $('.date30').datepicker('setDate', '<?php echo dateformat(date('Y-m-d', strtotime('-30 days', strtotime(date('Y-m-d'))))); ?>');

    $('.date30_plus').datepicker({autoHide: true, format: '<?php echo $this->config->item('dformat2'); ?>'});
    $('.date30_plus').datepicker('setDate', '<?php echo dateformat(date('Y-m-d', strtotime('+30 days', strtotime(date('Y-m-d'))))); ?>');



</script>
<script src="<?= assets_url() ?>app-assets/vendors/js/extensions/unslider-min.js"></script>
<script src="<?= assets_url() ?>app-assets/vendors/js/timeline/horizontal-timeline.js"></script>
<script src="<?= assets_url() ?>app-assets/js/core/app-menu.js"></script>
<script src="<?= assets_url() ?>app-assets/js/core/app.js"></script>
<script type="text/javascript" src="<?= assets_url() ?>app-assets/js/scripts/ui/breadcrumbs-with-stats.js"></script>
<script src="<?php echo assets_url(); ?>assets/myjs/jquery-ui.js"></script>
<script src="<?php echo assets_url(); ?>app-assets/vendors/js/tables/datatable/datatables.min.js"></script>

<script type="text/javascript">var dtformat = $('#hdata').attr('data-df');
    var currency = $('#hdata').attr('data-curr');
</script>
<script src="<?php echo assets_url('assets/myjs/custom.js') . APPVER; ?>"></script>
<script src="<?php echo assets_url('assets/myjs/basic.js') . APPVER; ?>"></script>
<script src="<?php echo assets_url('assets/myjs/control.js') . APPVER; ?>"></script>

<script type="text/javascript">

    //erp2024 13-03-2025
    var pathname1 = window.location.pathname;
    var partss = pathname1.split("/");
    var secondName1 = partss[2]
    var lastPart1 = partss.length > 0 ? partss[partss.length - 1] : "";
    pageName1 = secondName1+"/"+lastPart1;
    if(((pageName1!="invoices/customer_leads") && (pageName1!="quote/create"))){  
        $(".producthis").hide();
    }
  
    $.ajax({
        url: baseurl + 'manager/pendingtasks',
        dataType: 'json',
        success: function (data) {
            $('.tasklist').html(data.tasks);
            $('.taskcount').html(data.tcount);

        },
        error: function (data) {
            $('#response').html('Error')
        }

    });
 
    $(document).ready(function () {

        //customer popup prev next section starts
        $("#prevBtn").click(function () {
            $('a[href="#custtab1"]').tab("show"); 
            
        });

        $("#nextBtn").click(function () {
            $('a[href="#custtab3"]').tab("show");
        });
        //customer popup prev next section ends

        //Supplier popup prev next section starts
        $("#prevSupplierBtn").click(function () {
            $('a[href="#supplierTab1"]').tab("show"); 
            
        });

        $("#nextSupplierBtn").click(function () {
            $('a[href="#supplierTab3"]').tab("show");
        });
        //Supplier popup prev next section ends


        $('.searchsectionedit').on('click', function()
        {
            $(".customer-search-section").removeClass('d-none').hide().slideDown(500);
            $(".searchsectioncancel").removeClass('d-none');
            $(".searchsectionedit").addClass('d-none');
        });
        $('.searchsectioncancel').on('click', function()
        {  
            $(".customer-search-section").slideUp(500, function() {
                $(this).addClass('d-none'); 
                $(".searchsectionedit").removeClass('d-none');
            });
            
            $(".searchsectioncancel").addClass('d-none');
        });
        var anyUnsavedChanges = false;
        $(".unsavedisable-btns").prop("disabled",true);
        $('input:not(:checkbox), select, textarea').on('input change', function() {
            anyUnsavedChanges = true;
            $(".unsavedisable-btns").prop("disabled",false);
        });
        $(document).on('input change', 'input:not(:checkbox), select, textarea', function() {
            anyUnsavedChanges = true;
            $(".unsavedisable-btns").prop("disabled",false);
        });
        $('.removeProd, #addmore_img .delete-btn').on('click', function() {
            anyUnsavedChanges = true;
            $(".unsavedisable-btns").prop("disabled", false);
        });
        // Track form submission
        $('form').submit(function() {
            anyUnsavedChanges = false;
        });
        
        //disable autocomplete
        $("form").attr("autocomplete", "off");
        $("input, textarea").attr("autocomplete", "off");
        $("form").prop("autocomplete", "off");
        $("input, textarea").prop("autocomplete", "off");


        window.menu_objects = <?php echo json_encode($this->session->userdata('defined_permissions')); ?>;       
        setTimeout(function () {
            // Check if window.menu_objects is defined and is an array
            console.log(window.menu_objects);
            if (Array.isArray(window.menu_objects)) {
                $(".menu_assign_class").each(function () {
                    let this_access = $(this).attr("data-access");
                    

                    if (window.menu_objects.indexOf(this_access) > -1) {
                        // console.log("Present in menu_objects: " + this_access);
                    } else {
                        // console.log("Not present in menu_objects: " + this_access);
                        $(this).remove();
                    }
                });
            } else {
                $(".menu_assign_class").each(function () {
                    let this_access = $(this).attr("data-access");
                    $(this).remove();
                });
            }
        }, 100);



        $('[data-toggle="tooltip"]').tooltip();
        $('#submit-deliverynote').prop('disabled',false);
        $("#submit-deliverynote").on("click", function(e) {
            e.preventDefault();
            $('#submit-deliverynote').prop('disabled',true);
            var selectedProducts1 = [];
            var validationFailed = false;
            var selectedProducts = [];
            var avalable_credit_limit = $("#available_credit").val().replace(/,/g, '');
            var totalval = $("#invoiceyoghtml").val();
            totalval = totalval.replace(/,/g, '');
            var grandamount = parseFloat(totalval);
            $('.checkedproducts:checked').each(function() {
                selectedProducts.push($(this).val());
            });
            $('.product_qty').each(function(index) {
                var currentQty = parseFloat($(this).val());
                var oldQty = parseFloat($(this).closest('td').find('input[name="old_product_qty[]"]').val());

                if (!isNaN(currentQty) && currentQty > 0) {
                    if (currentQty <= oldQty) {
                        selectedProducts1.push(currentQty);
                    } else {
                        validationFailed = true;
                        return false; // Break out of the loop
                    }
                }
            });

            if (validationFailed) {
                Swal.fire({
                    text: "Delivery quantity cannot exceed the old product quantity.",
                    icon: "error"
                });
                $('#submit-deliverynote').prop('disabled',false);
                return;
            }

            if (selectedProducts1.length === 0) {
                Swal.fire({
                    text: "To proceed, please add a delivery quantity for at least one item",
                    icon: "info"
                });
                $('#submit-deliverynote').prop('disabled',false);
                return;
            }
            
            if (grandamount > avalable_credit_limit) {
                Swal.fire({
                    text: "Customer doesn't have enough credit balance. Please contact Credit Manager",
                    icon: "error"
                });       
                $('#submit-deliverynote').prop('disabled',false);
                return;      
            }
           
            // Use SweetAlert for confirmation
            Swal.fire({
                title: "Are you sure?",
                // text: "Are you sure you want to update inventory? Do you want to proceed?",
                "text":"Do you want to complete this delivery note? Once completed, it will proceed to the next level. If you need to make any changes before it moves forward, use the 'Save as Draft' button. Do you also want to update the inventory?",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, proceed!',
                cancelButtonText: "No - Cancel",
                reverseButtons: true,
                focusCancel: true
            }).then((result) => {
                if (result.isConfirmed) {
                    var delivery = $("#delevery_note_id").val();
                    var sales =  $("#salesorder_number").val();
                    var cust =  $("#customer_id").val();
                    var priceFlg =1;
                    var formData = $("#data_form").serialize(); 
                    formData += '&completed_status=1';
                    $.ajax({
                        type: 'POST',
                        url: baseurl +'DeliveryNotes/deliverynoteaction',
                        data: formData,
                        success: function(response) {
                            // deliveryReport();     
                            $('#submit-deliverynote').prop('disabled',false);
                            var responseData = JSON.parse(response);
                            var deliveryNoteData = responseData.data;
                            var types = responseData.type;
                            window.open(baseurl + 'DeliveryNotes/reprintnote?delivery=' + delivery + '&sales=' + sales + '&cust=' + cust + '&priceFlg=' + priceFlg, '_blank');
                            window.open(baseurl + 'DeliveryNotes/create?id='+delivery, '_blank');
                            // window.location.href = baseurl + 'DeliveryNotes/reprintnote?delivery=' + delivery + '&sales=' + sales + '&cust=' + cust + '&priceFlg=' + priceFlg;
                            //if delivery note from sales
                            // if(types=='fromsales')
                            // {
                                
                            //     window.location.href = baseurl + 'SalesOrders/delivery_notes?id=' + deliveryNoteData;
                            // }
                            // else{
                                // window.open(baseurl + 'DeliveryNotes', '_blank');
                                window.location.href =baseurl +'DeliveryNotes/create?id='+delivery;
                                // window.location.href = baseurl + 'DeliveryNotes';
                            // }
                            
                            
                            
                        },
                        error: function(xhr, status, error) {
                            // Handle error
                            console.error(xhr.responseText);
                        }
                    });
                }
                else{
                    $('#submit-deliverynote').prop('disabled',false);
                }
            });
        });

        
        // Getting label for Select Box :10-01-2025
        $('select').each(function () {
            if (!$(this).attr('multiple')) {
                 const selectedLabel = $(this).find(':selected').text();
                $(this).attr('data-original-label',selectedLabel);
            } else {
            // For multi-select, get all selected options text and join them with a comma
            const selectedLabels = $(this)
                .find(':selected')
                .map(function () {
                return $(this).text();
                })
                .get()
                .join(', ');
            $(this).attr('data-original-label', selectedLabels);
            }
        });

        ////////end

    });

    function deliveryReport(){
        var selectedProducts = [];
        var deliveredItems = [];
        var i =0;
        deliverynoteFlg = $("#deliverynoteFlg").val();
        $('.checkedproducts1:checked').each(function() {
            selectedProducts.push($(this).val());
            deliveredItems.push($("#amount-"+i).val());
            i++;
        });
        if (selectedProducts.length === 0) {
            Swal.fire({
                text: "Please select at least one product",
                icon: "info"
            });
            return;
        }
       
        var invocienoId= $('#salesorder_tid').val();
        // var invocienoId= $('#invocienoId').val();
        var customer_id= $('#customer_id').val();
        var invocieduedate= $('#invocieduedate').val();
        var invoicedate= $('#invoicedate').val();
        var refer= $('#refer').val();
        var taxformat= $('#taxformat').val();
        var discountFormat= $('#discountFormat').val();
        var  salenote= $('#salenote').val();
        // Create the form dynamically
        var form = $('<form action="<?php echo site_url('pos_invoices/deliverNoteexportpdf')?>" target="_blank" method="POST"></form>');
        form.append('<input type="hidden" name="deliveredItems" value="' + deliveredItems + '">');
        form.append('<input type="hidden" name="selectedProducts" value="' + selectedProducts + '">');
        form.append('<input type="hidden" name="invocienoId" value="' + invocienoId + '">');
        form.append('<input type="hidden" name="customer_id" value="' + customer_id + '">');
        form.append('<input type="hidden" name="invoicedate" value="' + invoicedate + '">');
        form.append('<input type="hidden" name="invocieduedate" value="' + invocieduedate + '">');
        form.append('<input type="hidden" name="deliverynoteFlg" value="' + deliverynoteFlg + '">');

        form.append('<input type="hidden" name="refer" value="' + refer + '">');
        form.append('<input type="hidden" name="taxformat" value="' + taxformat + '">');
        form.append('<input type="hidden" name="discountFormat" value="' + discountFormat + '">');
        form.append('<input type="hidden" name="salenote" value="' + salenote + '">');
        $('body').append(form);
        form.submit();     
        setInterval(function () {
            window.location.href = baseurl + 'DeliveryNotes'; 
        }, 1000);    
    
}

function loadunreadmsgs() {
    var currentcount = parseInt($("#unreadmsgs").text(), 10);
    if (isNaN(currentcount)) {
        currentcount = 0;
    }

    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseurl + 'messages/unreadmessagecount',
        success: function(response) {
            var unreadmsgs = parseInt(response.unreadmsgs, 10);
            if (isNaN(unreadmsgs)) {
                unreadmsgs = 0;
            }
            // if(reciever == loginuserid){
                var msgcount = unreadmsgs + currentcount;
                var targetuserid = response.targetuser;
                $("#unread"+targetuserid).text(msgcount);
                $("#unreadcount"+targetuserid).text(msgcount);
                $("#unread1"+targetuserid).text(msgcount); 
                $("#medialist"+targetuserid).html(response.msglist); 
            // }
            
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    });
}
function loadassignedtasks() {
   
    $.ajax({
        url: baseurl + 'manager/pendingtasks',
        dataType: 'json',
        success: function (data) {
            $('.tasklist').html(data.tasks);
            $('.taskcount').html(data.tcount);

        },
        error: function (data) {
            $('#response').html('Error')
        }

    });
}


$(document).ready(function() {
    setInterval(function() {
        loadunreadmsgs(); 
        loadassignedtasks(); 
    }, 15000); 
    //set default titles
    addMissingTitles();
    hideEmptyImagePreviews();
    $('#addmore_img').click(function() {
        var fileId = $('.form-control').length;
        var newInput = '<div class="d-flex mt-2">';
        newInput += '<input type="file" name="upfile[]" title="Add Attachments(pdf, jpg, png, csv, excel only)" id="upfile-' + fileId + '" class="form-control1 input-file" accept=".pdf, .jpg, .jpeg, .png" onchange="readURL(this);">';
        newInput += '<img class="blah" src="" alt="your image" style="margin-left:10px; width:50px; height:50px;"/>';
        newInput += '<button type="button" class="btn btn-crud btn-secondary btn-sm delete-btn" style="height:30px; height:30px; margin:3px;"  title="Remove"><i class="fa fa-trash" ></i></button>';
        newInput += '</div>';

        // Append the new input field to the upload section
        $('#uploadsection').append(newInput);

        // Call the function to hide empty image previews
        hideEmptyImagePreviews();
        save_changed_values_for_history();
    });
    $('#addmore_product_img').click(function() {
        var fileId = $('.form-control').length;
        var newInput = '<div class="d-flex mt-2">';
        newInput += '<input type="file" name="upfile[]" id="upfile-' + fileId + '" class="form-control1 input-file" accept=".pdf, .jpg, .jpeg, .png" onchange="imgreadURL(this);">';
        newInput += '<img class="blah" src="" alt="your image" style="margin-left:10px; width:50px; height:50px;"/>';
        newInput += '<button type="button" class="btn btn-crud btn-secondary btn-sm delete-btn" style="height:30px; height:30px; margin:3px;"  title="Remove"><i class="fa fa-trash" ></i></button>';
        newInput += '</div>';

        // Append the new input field to the upload section
        $('#uploadsection').append(newInput);

        // Call the function to hide empty image previews
        hideEmptyImagePreviews();
        save_changed_values_for_history();
    });



    // Event delegation to handle delete button clicks on dynamically added elements
    $('#uploadsection').on('click', '.delete-btn', function() {
      
            swal.fire({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this item!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, proceed!',
                cancelButtonText: "No - Cancel",
                reverseButtons: true,
                focusCancel: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Remove the parent row of the delete button (which contains the input, image, and button)
                    // $(this).closest('.row').remove();
                    $(this).closest('.d-flex').remove();
                }
            });
       
    });
});

$('.delete-btn').on('click',  function() {
    // Show confirmation dialog
    const $fileInput = $(this).siblings('.input-file');
    // Check if .blah exists and has a source value
    if ($fileInput.length > 0 && $fileInput[0].files.length > 0) {
        swal.fire({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover this item!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, proceed!',
            cancelButtonText: "No - Cancel",
            reverseButtons: true,
            focusCancel: true
        }).then((result) => {
            if (result.isConfirmed) {
                $(this).closest('.d-flex').remove();
            }
        });
    }
    else{
        Swal.fire({
            icon: 'info',
            title: 'No file to Delete',
            text: 'There is no file to delete.',
            confirmButtonText: 'OK'
        });
    }
});
$('.delete-btn-file-only').on('click',  function() {
    const $blah = $(this).siblings('.blah');
    const $fileInput = $(this).siblings('.fileclass');
    // Check if .blah exists and has a source value
    if (($blah.length > 0 && $blah.attr('src') !== '') || ($fileInput.length > 0)) {
        swal.fire({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover this item!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, proceed!',
            cancelButtonText: "No - Cancel",
            reverseButtons: true,
            focusCancel: true
        }).then((result) => {
            if (result.isConfirmed) {
                $blah.hide(); // Hide the .blah image
                if ($fileInput.length > 0) {
                    $fileInput.val(''); 
                }
            }
        });
    } else {
        // Optional: Show a message if no .blah is available
        Swal.fire({
            icon: 'info',
            title: 'No file to Delete',
            text: 'There is no file to delete.',
            confirmButtonText: 'OK'
        });
    }
});

// Function to update the image preview dynamically
function readURL(input) {
    if (input.files && input.files[0]) {
        var file = input.files[0];
        var fileType = file.type; // Get the file type

        // Check if the file is an image (jpg, png, jpeg)
        if (fileType === 'image/jpeg' || fileType === 'image/png' || fileType === 'image/jpg') {
            var reader = new FileReader();

            reader.onload = function (e) {
                // Update the corresponding preview image by finding the .blah within the same .d-flex container
                $(input).siblings('.blah').attr('src', e.target.result).show();
            };

            reader.readAsDataURL(file); // Read the file as a data URL
        } else {
            // If the file is not an image, hide the preview image
            $(input).siblings('.blah').hide();
        }
    }
}

function imgreadURL(input) {
    if (input.files && input.files[0]) {
        var file = input.files[0];
        var fileType = file.type; // Get the file type

        // Check if the file is an image (jpg, png, jpeg)
        if (fileType === 'image/jpeg' || fileType === 'image/png' || fileType === 'image/jpg') {
            var reader = new FileReader();

            reader.onload = function (e) {
                $(input).siblings('.blah').attr('src', e.target.result).show();
            };

            reader.readAsDataURL(file); // Read the file as a data URL
        } else {
                // Use SweetAlert for error message
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid File Type',
                    text: 'Please select an image file (jpg, jpeg, png).',
                    confirmButtonText: 'OK',
                    customClass: {
                        confirmButton: 'btn btn-danger',
                    }
                });
                $(input).val(''); // Clear the input field
                $(input).siblings('.blah').hide(); // Hide the preview image
            }
    }
}


// Function to hide empty image previews if no src is set
function hideEmptyImagePreviews() {
    $('.blah').each(function() {
        if (!$(this).attr('src')) {
            $(this).hide(); // Hide the preview if there is no src
        }
    });
}

    

    //erp2024 26-03-2025
     $('.add_customer_btn').on('click',  function(e) {
         e.preventDefault();
         $("#newcustomerModal").find('form')[0].reset();
         $("#newcustomerModal").modal('show');
     });
    $('.add_supplier_btn').on('click',  function(e) {
        e.preventDefault();
        $("#newsupplierModal").find('form')[0].reset();
        $("#newsupplierModal").modal('show');
    });

    $('#printButton').on('click', function() {
        var invoiceId = 4;
        var token = 'a3967412bf0e34c41d6cc15c8d2dfc71f343c542'; 
        $.ajax({
            url: baseurl + 'billing/pre_print_invoice',
            type: 'GET',
            data: {
                id: invoiceId,
                token: token
            },
            success: function(response) {
                // Open a new window for the invoice printout
                var printWindow = window.open('', '', 'height=800,width=600');
                printWindow.document.write(response);
                printWindow.document.close();

                // Add event listener to trigger the print dialog after the content loads
                printWindow.addEventListener('load', function() {
                    setTimeout(function() {
                        printWindow.print();  // Trigger the print dialog after the page content is loaded
                    }, 200);  // Delay to ensure the content has fully loaded
                });
            },
            error: function(xhr, status, error) {
                alert('Failed to load invoice. Please try again.');
            }
        });
    });


    $(document).on('click', '.open-task', function(e) {
        var taskid = $(this).data('task-id');
        $.ajax({
            url: baseurl + 'tools/mark_task_as_read',
            type: 'POST',
            data: { id: taskid },
            success: function(response) {
                console.log("Flag updated");
            },
            error: function(xhr) {
                console.error("Failed to update flag");
            }
        });
    });

    // //permission section
    // if(historyflg!=1)
    // {
    //     $(".history-expand-button").show();
    // }
    // $(".btn-sm").hide();    
    // $(".btn").hide();    
    // $(".history-expand-button").hide();    
    // $(".topsection-caption1").hide();    
    // $(".bottomsection-caption").hide();    
    // $(".bottomsection-caption1").hide();    
    // $(".navtab-caption").hide();    
    // $(".btn-modules").show();    
    // $(".btn-crud").show();    
    // // $(".btncard").show();  
    // $(".expand-btn1").show(); 
    // const permissions = <?php
    //     echo json_encode($permissions);
    // ?>;
    // console.log(permissions);
    
    // if (Array.isArray(permissions) && permissions.length > 0) {
    //     const buttonsToDisplay = permissions.map(permission => permission.function);

    //      $(".btn-sm, .btn, a, input[type='submit']").each(function () {
    //          let buttonText = ""
    //          // Handle different elements properly
    //          if ($(this).is("input[type='submit'], input[type='button']")) {
    //              buttonText = $(this).val().trim(); // Use .val() for input elements
    //          } else {
    //              buttonText = $(this).text().trim(); // Use .text() for <a> and <button>
    //          }

    //          if (buttonsToDisplay.includes(buttonText)) {
    //              $(this).show();
    //          }
            
    //      });
         
    //      $(".topsection-caption1, .bottomsection-caption, .bottomsection-caption1, .navtab-caption").each(function () {
    //          if (buttonsToDisplay.includes($(this).text().trim())) {
    //              $(this).show();
    //          }
    //      });

    //      // const historyExpandButton = $(".history-expand-button");
    //      // if (buttonsToDisplay.includes(historyExpandButton.text().trim())) {
    //      //     historyExpandButton.show();
    //      // }

    //      $(".btn-modules").show();    
    //      $(".btn-crud").show();    
    //      $(".btncard").show();  
    //      $(".expand-btn1").show();  
    //      $(".form-textarea").show();       
    //      $(".summernote").show();
    //      $(".navsearch").show();
    //      $(".customsearchbtn input[type='button']").show();
       
    //  }
    //  else{
    //      $("a.btncard").show();
    //  }

        // let isBackConfirmed = false;

        // // Push fake state to trap first back button
        // history.pushState({ page: 1 }, "", "");

        // window.onpopstate = function (event) {
        //     if (!isBackConfirmed) {
        //         Swal.fire({
        //             title: 'Are you sure?',
        //             text: "Do you really want to go back?",
        //             icon: 'warning',
        //             showCancelButton: true,
        //             confirmButtonText: 'Yes, go back',
        //             cancelButtonText: 'No, stay here'
        //         }).then((result) => {
        //             if (result.isConfirmed) {
        //                 isBackConfirmed = true;
        //                 history.back(); // Allow real back
        //             } else {
        //                 history.pushState({ page: 1 }, "", ""); // Prevent back
        //             }
        //         });
        //     }
        // };



// Browser Back Button Protection with Alert Message
// Shows confirmation alert when user clicks browser back button

(function() {
    'use strict';
    
    // Configuration - set to true to always show alert, false to only show when form has changes
    var ALWAYS_CONFIRM_BACK = false; // Change to true if you want to always show confirmation
    
    // Global variables
    var hasUnsavedChanges = false;
    var originalFormData = {};
    var originalFileData = {};
    var isNavigatingAway = false;
    var backButtonClicked = false;
    
    // Custom messages
    var BACK_BUTTON_MESSAGE = "Are you sure you want to go back? Any unsaved changes will be lost.";
    var GENERIC_BACK_MESSAGE = "Are you sure you want to leave this page?";
    
    // Initialize when DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        initializeBackButtonProtection();
    });
    
    // Main initialization function
    function initializeBackButtonProtection() {
        storeOriginalFormData();
        trackFormChanges();
        setupBackButtonDetection();
        setupSelect2Tracking();
        // handlePageUnload();
        // handleFormSubmissions();
    }
    
    // Store original form data including Select2 and files
    function storeOriginalFormData() {
        var forms = document.querySelectorAll('form');
        forms.forEach(function(form, index) {
            var formId = form.id || 'form_' + index;
            originalFormData[formId] = serializeForm(form);
            originalFileData[formId] = serializeFileInputs(form);
        });
    }
    
    // Enhanced serialize form data with Select2 support
    function serializeForm(form) {
        var formData = [];
        var elements = form.elements;
        
        for (var i = 0; i < elements.length; i++) {
            var element = elements[i];
            var name = element.name;
            
            if (!name || element.disabled) continue;
            
            switch (element.type) {
                case 'radio':
                case 'checkbox':
                    if (element.checked) {
                        formData.push(name + '=' + encodeURIComponent(element.value));
                    }
                    break;
                case 'select-multiple':
                    for (var j = 0; j < element.options.length; j++) {
                        if (element.options[j].selected) {
                            formData.push(name + '=' + encodeURIComponent(element.options[j].value));
                        }
                    }
                    break;
                case 'file':
                    // Handle file inputs separately
                    break;
                default:
                    formData.push(name + '=' + encodeURIComponent(element.value));
                    break;
            }
        }
        
        // Handle Select2 elements
        var select2Elements = form.querySelectorAll('[data-select2-id], .select2-hidden-accessible');
        select2Elements.forEach(function(element) {
            if (element.name && !element.disabled) {
                if (element.multiple) {
                    // Multiple select
                    var selectedValues = $(element).val() || [];
                    selectedValues.forEach(function(value) {
                        formData.push(element.name + '=' + encodeURIComponent(value));
                    });
                } else {
                    // Single select
                    var value = $(element).val();
                    if (value !== null && value !== '') {
                        formData.push(element.name + '=' + encodeURIComponent(value));
                    }
                }
            }
        });
        
        return formData.join('&');
    }
    
    // Serialize file inputs data
    function serializeFileInputs(form) {
        var fileData = {};
        var fileInputs = form.querySelectorAll('input[type="file"]');
        
        fileInputs.forEach(function(input) {
            if (input.name) {
                var files = [];
                for (var i = 0; i < input.files.length; i++) {
                    files.push({
                        name: input.files[i].name,
                        size: input.files[i].size,
                        type: input.files[i].type,
                        lastModified: input.files[i].lastModified
                    });
                }
                fileData[input.name] = files;
            }
        });
        
        return JSON.stringify(fileData);
    }
    
    // Setup Select2 change tracking
    function setupSelect2Tracking() {
        // Wait for Select2 to be initialized, then bind change events
        setTimeout(function() {
            if (typeof $ !== 'undefined' && $.fn.select2) {
                $('[data-select2-id], .select2-hidden-accessible').on('select2:select select2:unselect select2:clear', function() {
                    setTimeout(checkForChanges, 50);
                });
            }
        }, 500);
        
        // Also setup a MutationObserver to catch dynamically added Select2 elements
        if (window.MutationObserver) {
            var observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    mutation.addedNodes.forEach(function(node) {
                        if (node.nodeType === 1) { // Element node
                            var select2Elements = node.querySelectorAll ? 
                                node.querySelectorAll('[data-select2-id], .select2-hidden-accessible') : [];
                            
                            if (select2Elements.length > 0 && typeof $ !== 'undefined' && $.fn.select2) {
                                $(select2Elements).off('select2:select select2:unselect select2:clear').on('select2:select select2:unselect select2:clear', function() {
                                    setTimeout(checkForChanges, 50);
                                });
                            }
                        }
                    });
                });
            });
            
            observer.observe(document.body, {
                childList: true,
                subtree: true
            });
        }
    }
    
    // Track form changes including files
    function trackFormChanges() {
        document.addEventListener('input', handleFieldChange);
        document.addEventListener('change', handleFieldChange);
        document.addEventListener('paste', function() {
            setTimeout(checkForChanges, 100);
        });
        
        // Special handling for file inputs
        document.addEventListener('change', function(event) {
            if (event.target.type === 'file') {
                setTimeout(checkForChanges, 100);
            }
        });
    }
    
    // Handle field changes
    function handleFieldChange(event) {
        if (event.target.closest('form') && !isNavigatingAway) {
            checkForChanges();
        }
    }
    
    // Enhanced check for form changes including Select2 and files
    function checkForChanges() {
        hasUnsavedChanges = false;
        
        var forms = document.querySelectorAll('form');
        forms.forEach(function(form, index) {
            var formId = form.id || 'form_' + index;
            var currentData = serializeForm(form);
            var currentFileData = serializeFileInputs(form);
            
            // Check regular form data
            if (originalFormData[formId] !== currentData) {
                hasUnsavedChanges = true;
                return;
            }
            
            // Check file data
            if (originalFileData[formId] !== currentFileData) {
                hasUnsavedChanges = true;
                return;
            }
        });
        
        updatePageIndicator();
    }
    
    // Update page indicator
    function updatePageIndicator() {
        if (hasUnsavedChanges) {
            document.body.classList.add('has-unsaved-changes');
            if (!document.title.includes('*')) {
                document.title = '* ' + document.title;
            }
        } else {
            document.body.classList.remove('has-unsaved-changes');
            document.title = document.title.replace('* ', '');
        }
    }
    
    // Setup back button detection - this is the main function for back button alert
    function setupBackButtonDetection() {
        // Method 1: Using history manipulation
        var currentState = history.state || {};
        history.replaceState(Object.assign(currentState, {backDetection: true}), document.title, window.location.href);
        
        // Push a new state to catch back button
        history.pushState({backDetection: true, timestamp: Date.now()}, document.title, window.location.href);
        
        // Listen for popstate events (triggered by back/forward buttons)
        window.addEventListener('popstate', function(event) {
            // Check if this is a back button click
            if (event.state && event.state.backDetection) {
                backButtonClicked = true;
                
                // Determine what message to show
                var shouldShowConfirmation = ALWAYS_CONFIRM_BACK || hasUnsavedChanges;
                var message = hasUnsavedChanges ? BACK_BUTTON_MESSAGE : GENERIC_BACK_MESSAGE;
                
                if (shouldShowConfirmation && !isNavigatingAway) {
                    var confirmLeave = confirm(message);
                    
                    if (confirmLeave) {
                        // User confirmed, allow navigation
                        isNavigatingAway = true;
                        backButtonClicked = false;
                        history.back();
                    } else {
                        // User cancelled, stay on current page
                        // Push state again to prevent going back
                        history.pushState({backDetection: true, timestamp: Date.now()}, document.title, window.location.href);
                        backButtonClicked = false;
                    }
                } else {
                    // No confirmation needed, allow normal navigation
                    backButtonClicked = false;
                }
            }
        });
        
        // Method 2: Additional detection using page visibility
        document.addEventListener('visibilitychange', function() {
            if (document.hidden && backButtonClicked) {
                // Page is being hidden after back button click
                console.log('Back navigation detected via visibility change');
            }
        });
        
        // Method 3: Using hashchange as backup (for older browsers)
        if (!window.history.pushState) {
            var originalHash = window.location.hash;
            window.location.hash = '#backdetection';
            
            window.addEventListener('hashchange', function() {
                if (window.location.hash === originalHash) {
                    var shouldShowConfirmation = ALWAYS_CONFIRM_BACK || hasUnsavedChanges;
                    var message = hasUnsavedChanges ? BACK_BUTTON_MESSAGE : GENERIC_BACK_MESSAGE;
                    
                    if (shouldShowConfirmation && !isNavigatingAway) {
                        var confirmLeave = confirm(message);
                        if (!confirmLeave) {
                            window.location.hash = '#backdetection';
                        }
                    }
                }
            });
        }
    }
    
    // Handle page unload (refresh, close, etc.)
    function handlePageUnload() {
        window.addEventListener('beforeunload', function(event) {
            if ((ALWAYS_CONFIRM_BACK || hasUnsavedChanges) && !isNavigatingAway) {
                var message = 'Are you sure you want to leave? Any unsaved changes will be lost.';
                event.returnValue = message;
                return message;
            }
        });
    }
    
    // Handle form submissions
    function handleFormSubmissions() {
        document.addEventListener('submit', function(event) {
            isNavigatingAway = true;
            hasUnsavedChanges = false;
            updatePageIndicator();
        });
        
        document.addEventListener('click', function(event) {
            var target = event.target;
            if (target.matches('.btn-save, .save-button, [data-save="true"]') ||
                target.matches('button[type="submit"], input[type="submit"]')) {
                isNavigatingAway = true;
                hasUnsavedChanges = false;
                updatePageIndicator();
            }
        });
    }
    
    // Public functions
    window.markFormAsSaved = function() {
        hasUnsavedChanges = false;
        isNavigatingAway = false;
        storeOriginalFormData();
        updatePageIndicator();
    };
    
    window.resetUnsavedChanges = function() {
        hasUnsavedChanges = false;
        isNavigatingAway = false;
        storeOriginalFormData();
        updatePageIndicator();
    };
    
    window.hasUnsavedChanges = function() {
        return hasUnsavedChanges;
    };
    
    // Force enable/disable back button confirmation
    window.setAlwaysConfirmBack = function(enable) {
        ALWAYS_CONFIRM_BACK = enable;
    };
    
    // Set custom messages
    window.setBackButtonMessages = function(backMessage, genericMessage) {
        if (backMessage) BACK_BUTTON_MESSAGE = backMessage;
        if (genericMessage) GENERIC_BACK_MESSAGE = genericMessage;
    };
    
    // Navigate with custom confirmation
    window.navigateWithConfirmation = function(url, customMessage) {
        var message = customMessage || (hasUnsavedChanges ? BACK_BUTTON_MESSAGE : GENERIC_BACK_MESSAGE);
        var shouldConfirm = ALWAYS_CONFIRM_BACK || hasUnsavedChanges;
        
        if (shouldConfirm) {
            var confirmLeave = confirm(message);
            if (confirmLeave) {
                isNavigatingAway = true;
                window.location.href = url;
            }
        } else {
            window.location.href = url;
        }
    };
    
    // Manually trigger change detection (useful after programmatic changes)
    window.triggerChangeDetection = function() {
        setTimeout(checkForChanges, 100);
    };
    
    // Advanced: Custom alert dialog (optional, more visually appealing)
    window.showCustomBackAlert = function(message, onConfirm, onCancel) {
        var overlay = document.createElement('div');
        overlay.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 10001;
        `;
        
        var dialog = document.createElement('div');
        dialog.style.cssText = `
            background: white;
            padding: 30px;
            border-radius: 8px;
            max-width: 400px;
            width: 90%;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            font-family: Arial, sans-serif;
        `;
        
        dialog.innerHTML = `
            <div style="font-size: 18px; font-weight: bold; margin-bottom: 15px; color: #333;">
                <span style="color: #e74c3c;">⚠</span> Confirm Navigation
            </div>
            <div style="margin-bottom: 20px; color: #666; line-height: 1.5;">
                ${message}
            </div>
            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <button id="stayBtn" style="padding: 10px 20px; border: 1px solid #ddd; border-radius: 4px; cursor: pointer; font-size: 14px; background-color: #f8f9fa; color: #333;">
                    Stay on Page
                </button>
                <button id="leaveBtn" style="padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; background-color: #e74c3c; color: white;">
                    Leave Page
                </button>
            </div>
        `;
        
        overlay.appendChild(dialog);
        document.body.appendChild(overlay);
        
        dialog.querySelector('#leaveBtn').addEventListener('click', function() {
            document.body.removeChild(overlay);
            if (onConfirm) onConfirm();
        });
        
        dialog.querySelector('#stayBtn').addEventListener('click', function() {
            document.body.removeChild(overlay);
            if (onCancel) onCancel();
        });
        
        // Close on escape key
        document.addEventListener('keydown', function escHandler(event) {
            if (event.key === 'Escape') {
                document.body.removeChild(overlay);
                if (onCancel) onCancel();
                document.removeEventListener('keydown', escHandler);
            }
        });
        
        // Focus on stay button by default
        dialog.querySelector('#stayBtn').focus();
    };
    
})();


</script>


</body>
</html>
