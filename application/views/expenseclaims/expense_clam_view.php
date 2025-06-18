<?php       
  if (($msg = check_permission($permissions)) !== true) {
     echo $msg;
     return;
  }
 ?>
<div class="card card-block">
    <div id="notify" class="alert alert-success" style="display:none;">
        <a href="#" class="close" data-dismiss="alert">&times;</a>

        <div class="message"></div>
    </div>
        <?php

        $caption = $this->lang->line('Expense Claim');
          ?>
        <div class="card-header border-bottom">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('expenseclaims') ?>"><?php echo $this->lang->line('Expense Claims'); ?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo $caption. "#".$expense_details['claim_number']; ?></li>
                </ol>
            </nav>
            <div class="row">
                <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 col-12">
                    <?php
                    $editlink="";
                    
                    if($expense_details['approval_status'] == 'Not Approved' || $expense_details['approval_status']=='Refused' || $expense_details['refused_by'])
                    {
                        $editlink = '&nbsp;<a href="' . base_url('expenseclaims/edit?id=' . $expense_details['claim_number']) . '" class="btn btn-crud btn-sm btn-primary">' . $this->lang->line('Edit') . '</a>';

                    } 
                    ?>
                    <h4>
                        <?php                         
                        echo $caption." #".$expense_details['claim_number'].$editlink; ?>
                    </h4>
                </div>
                <div class="col-lg-3 col-md-7 col-sm-12 col-xs-12 col-12">
                    <div class="alert alert-success" role="alert">
                        <?php
                            $status = ($expense_details['refused_by']) ? "Refused" : $expense_details['approval_status'];                        
                            echo 'Expense Claim Status : '.$status;
                        ?>
                    </div>
                </div>
            </div>
            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
            <div class="heading-elements">
                <ul class="list-inline mb-0">
                    <li><a class="breaklink" data-action="expand"><i class="ft-maximize"></i></a></li>
                </ul>
            </div>


        </div>


        
    <div class="card-body">        
        <div class="card card-block ">
            <div class="row"> 
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="account-left-section">
                        <?php
                            $approverequest = "disabled";
                            $paymentbtn = "disable-class";
                            $markapprovebtn = "";
                            $marktitle = "";     
                            $refusetitle = "";     
                            $requsttitle = "Request approval already send";                      
                            switch ($expense_details['approval_status']) {
                                case 'Not Approved':
                                    $markapprovebtn = "disabled";
                                    $marktitle = "First send approval request";
                                    $refusetitle = "First send approval request";
                                    $approverequest = "";
                                    $requsttitle = "Send Request Approval";
                                    break;
                            
                                case 'Waiting For Approval':
                                    if ($this->session->userdata('id') == $expense_details['approver_id']) {
                                        $markapprovebtn = "";
                                        $marktitle = "Approve now";
                                        $refusetitle = "Refuse now";
                                        
                                    } else {
                                        $markapprovebtn = "disabled";
                                        $marktitle = "You are not authorized to approve or refuse";
                                        $refusetitle = "You are not authorized to approve or refuse";
                                    }
                                    break;
                            
                                case 'Approved':
                                    if ($this->session->userdata('id') != $expense_details['approver_id']) {
                                        $markapprovebtn = "disabled";
                                    }
                                    $paymentbtn = "";
                                    break;
                            
                                default:
                                    break;
                            }
                            
                         
                            $dueamount = $expense_details['claim_total'] - $expense_details['payment_recieved_amount'];
                        ?>
                        <input type="hidden" name="claim_number" id="claim_number" value="<?=$expense_details['claim_number']?>">
                        <h3><?php echo $this->lang->line('Create'); ?></h3>
                        <h2><?php echo '<span>'.$expense_details['approver'].'</span> Created this transaction on <span>'.date('d-m-Y', strtotime($expense_details['claim_date'])).'</span>'; ?> </h2>
                        <hr>
                            
                            <!-- ============== Approve section ================== -->
                            <div class="row toggle-row-approve mt-3" style="cursor: pointer;">
                                <div class="col-lg-11 col-md-10 col-sm-10 col-10">
                                    <h3><?php echo $this->lang->line('Approve'); ?></h3>
                                    <p><?php echo $this->lang->line('Status')." : ".$expense_details['approval_status']; ?></p>
                                </div>
                                <div class="col-lg-1 col-md-2 col-sm-2  col-2  text-right ">
                                    <i class="fa fa-angle-down toggle-icon fontsize-20"></i>
                                </div>
                                
                            </div>

                            <div id="collapse_101" class="collapse">
                                <button class="btn btn-crud btn-secondary" <?=$approverequest?> id="request-apprval-btn" title="<?=$requsttitle?>"><?php echo $this->lang->line('Request Approval'); ?></button>
                                <button class="btn btn-crud btn-secondary"  <?=$markapprovebtn?> id="mark-approved-btn" title="<?=$marktitle?>"><?php echo $this->lang->line('Mark Approved'); ?></button>
                                <button class="btn btn-crud btn-danger bg-danger" id="refuse-btn" <?=$markapprovebtn?> title="<?=$refusetitle?>"><?php echo $this->lang->line('Refuse'); ?></button>
                            </div>
                            <hr>
                            <!-- ============== Approve section ================== -->

                            <!-- =======================Make payment section ================ -->
                            <div class="row toggle-row-payment mt-3" style="cursor: pointer;">
                                <div class="col-lg-11 col-md-10 col-sm-10 col-10">
                                    <h3><?php echo $this->lang->line('Make Payment'); ?></h3>                                    
                                    <p><?php echo $this->lang->line('Amount Due')." : <b>".number_format($dueamount,2)."</b>"; ?></p>
                                </div>
                                <div class="col-lg-1 col-md-2 col-sm-2  col-2  text-right ">
                                    <i class="fa fa-angle-down toggle-icon fontsize-20"></i>
                                </div>
                                
                            </div>

                            <div id="collapse_102" class="collapse">
                                <a href="<?= base_url('expenseclaims/expense_claim_payment?id=' . $expense_details['claim_number'].'&csd='.$expense_details['supplier_id']); ?>" class="btn btn-crud btn-secondary <?=$paymentbtn?>" ><?php echo $this->lang->line('Make Payment'); ?></a>

                                <p class="mt-2 mb-2"><?php echo $this->lang->line('Payment Made');?></p>

                                <?php
                                    $lists ="";
                                    if($payment_transactions)
                                    {
                                        foreach ($payment_transactions as $key => $value) {
                                            $lists .="<p>";
                                             $lists .= date('d-m-Y H:i:s',strtotime($value['trans_date'])). " A payment for <b>".number_format($value['trans_amount'],2)."</b> was made using <b>".$value['trans_payment_method']."</b><br>";
                                           
                                            $lists .="<a href='".base_url('expenseclaims/expense_claim_payment_edit?id=' . $value['trans_ref_number'].'&csd='.$expense_details['supplier_id'])."' class='btn btn-sm btn-secondary'>".$this->lang->line('Edit Payment')."</a>";
                                            // $lists .="<a href='".base_url('expenseclaims/expense_claim_payment?id=' . $expense_details['claim_number'].'&csd='.$expense_details['supplier_id'])."' class='btn btn-sm btn-secondary'>".$this->lang->line('Edit Payment')."</a>&nbsp;&nbsp;<a href='".base_url('expenseclaims/expense_claim_payment?id=' . $expense_details['claim_number'].'&csd='.$expense_details['supplier_id'])."' class='btn btn-sm btn-secondary'>".$this->lang->line('Delete Payment')."</a>";
                                            $lists .="</p>";
                                        }
                                    }
                                    echo  $lists;
                                ?>
                            </div>
                            <hr>
                            <!-- =======================Make payment section ================ -->

                            <!-- ====================== Journel Section ====================== -->
                            <div class="row toggle-row mt-3" style="cursor: pointer;">
                                <div class="col-lg-11 col-md-10 col-sm-10 col-10">
                                    <h3><?php echo $this->lang->line('Journals'); ?></h3>
                                    <p><?php echo $this->lang->line('Journals are'); ?></p>
                                </div>
                                <div class="col-lg-1 col-md-2 col-sm-2  col-2  text-right ">
                                    <i class="fa fa-angle-down toggle-icon fontsize-20"></i>
                                </div>
                            </div>

                            <div id="collapse_100" class="collapse">
                                <table class="table table-striped table-bordered zero-configuration dataTable" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th style="width:70%"><?php echo $this->lang->line('Account'); ?></th>
                                            <th class="text-right"><?php echo $this->lang->line('Debit'); ?></th>
                                            <th class="text-right"><?php echo $this->lang->line('Credit'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php           
                                        if ($journels) {
                                            foreach ($journels as $row) { ?>
                                                <tr>
                                                    <td><?= $row['acid'] . "  - " . $row['holder'] ?></td>
                                                    <td class="text-right"><?= number_format($row['debitamount'], 2) ?></td>
                                                    <td class="text-right"><?= number_format($row['creditamount'], 2) ?></td>
                                                </tr>
                                            <?php }
                                        } ?>
                                    </tbody>
                                </table>
                            </div>
                            <hr>
                            <!-- ====================== Journel Section ====================== -->

                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <!-- ============================================================= -->
                     <div class="transaction-box">
                        
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-12">

                            
                                <table >
                                    <tr>
                                        <th class="fixedpadding1"><?php echo $this->lang->line('Expense Claim From'); ?></th>
                                    </tr>
                                    <tr>                                        
                                        <th class="fixedpadding-bottom1"><?=$expense_details['supplier']?></th>
                                    </tr>
                                    <tr>
                                        <td class="fixedpadding-bottom1">Phone : <?=$expense_details['supplierphone']?></td>
                                    </tr>
                                    <tr>
                                        <td class="fixedpadding-bottom1">Email : <?=$expense_details['supplieremail']?></td>
                                    </tr>
                                    <tr>
                                        <td class="fixedpadding-bottom1">City : <?=$expense_details['suppliercity']?></td>
                                    </tr>
                                    <tr>
                                        <td class="fixedpadding-bottom1">Region : <?=$expense_details['supplierregion']?></td>
                                    </tr>
                                    <tr>
                                        <td class="fixedpadding-bottom1">Address : <?=$expense_details['supplieraddress']?></td>
                                    </tr>
                                    
                                    
                                </table>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12 table-scroll">
                                <table >
                               
                                    <tr>
                                        <th class="fixedwidth fixedpadding-bottom"><?php echo $this->lang->line('Expense Claim Number'); ?></th>
                                        <td class="fixedpadding-bottom">:&nbsp;&nbsp;<?=$expense_details['claim_number']?></td>
                                    </tr>
                                    <tr>
                                        <th class="fixedwidth fixedpadding-bottom"><?php echo $this->lang->line('Expense Claim Date'); ?></th>
                                        <td class="fixedpadding-bottom">:&nbsp;&nbsp;<?=date('d-m-Y', strtotime($expense_details['claim_date']))?></td>
                                    </tr>
                                    <tr>
                                        <th class="fixedwidth fixedpadding-bottom"><?php echo $this->lang->line('Due Date'); ?></th>
                                        <td class="fixedpadding-bottom">:&nbsp;&nbsp;<?=date('d-m-Y', strtotime($expense_details['claim_due_date']))?></td>
                                    </tr>
                                    <tr>
                                        <th class="fixedwidth fixedpadding-bottom"><?php echo $this->lang->line('Employee'); ?></th>
                                        <td class="fixedpadding-bottom">:&nbsp;&nbsp;<?=$expense_details['employee']?></td>
                                    </tr>
                                    <tr>
                                        <th class="fixedwidth fixedpadding-bottom"><?php echo $this->lang->line('Approver'); ?></th>
                                        <td class="fixedpadding-bottom">:&nbsp;&nbsp;<?=$expense_details['approver']?></td>
                                    </tr>
                                    
                                    
                                </table>

                            </div>
                            <div class="col-12 table-scroll">
                                             
                            <table class="table table-striped table-bordered zero-configuration dataTable" style="width:100%"> 
                            <thead>
                                <tr>
                                    <th ><?php echo $this->lang->line('Item No'); ?></th>
                                    <th ><?php echo $this->lang->line('Description'); ?></th>
                                    <th class="text-center"><?php echo $this->lang->line('Quantity'); ?></th>
                                    <th class="text-right"><?php echo $this->lang->line('Price'); ?></th>
                                    <th class="text-right"><?php echo $this->lang->line('Amount'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php           
                                if($expense_items)
                                { 
                                    foreach($expense_items as $row)
                                    {
                                        ?>
                                        <tr>
                                            <td><?=$row['product_code']?></td>
                                            <td><?=$row['product_name']?></td>
                                            <td class="text-center"><?=$row['quantity']?></td>
                                            <td class="text-right"><?=$row['price']?></td>
                                            <td class="text-right"><?=number_format($row['total'],2)?></td>
                                        </tr>
                                        <?php

                                    }
                                }

                                $discounttype = ($expense_details['discount_type']=='Percentage') ? "(".$expense_details['claim_discount']."%)" : ""
                                ?>
                                
                                
                            </tbody>
                        </table>
                            </div>
                        </div>
                        <div class="row mt-3 mb-3">
                            <div class="col-lg-7 col-md-8 col-sm-12"></div>
                            <div class="col-lg-5 col-md-4 col-sm-12">
                                <div class="row">
                                    <div class="col-lg-7 col-md-7 col-sm-7 col-7 mb-1"><?php echo $this->lang->line('Sub Total'); ?></div>
                                    <div class="col-lg-5 col-md-5 col-sm-5 col-5 mb-1"><?php echo ' :<span class="text-right"> <b>'.number_format($expense_details['claim_subtotal'],2).'</b></span>'; ?></div>

                                    <div class="col-lg-7 col-md-7 col-sm-7 col-7 mb-1"><?php echo  $this->lang->line('Discount').$discounttype; ?></div>
                                    <div class="col-lg-5 col-md-5 col-sm-5 col-5 mb-1"><?php echo ' :<span class="text-right"> <b>'.number_format($expense_details['claim_discount_amount'],2).'</b></span>'; ?></div>
                                    
                                    <div class="col-lg-7 col-md-7 col-sm-7 col-7 mb-1"><?php echo  $this->lang->line('Bill Amount'); ?></div>
                                    <div class="col-lg-5 col-md-5 col-sm-5 col-5 mb-1"><?php echo ' :<span class="text-right"> <b>'.number_format($expense_details['claim_total'],2).'</b></span>'; ?></div>
                                    
                                    <div class="col-lg-7 col-md-7 col-sm-7 col-7 mb-1"><?php echo  $this->lang->line('Paid Amount'); ?></div>
                                    <div class="col-lg-5 col-md-5 col-sm-5 col-5 mb-1"><?php echo ' :<span class="text-right"> <b>'.number_format($expense_details['payment_recieved_amount'],2).'</b></span>'; ?></div>
                                    
                                    <div class="col-lg-7 col-md-7 col-sm-7 col-7 mb-1"><?php echo  $this->lang->line('Balance'); ?></div>
                                    <div class="col-lg-5 col-md-5 col-sm-5 col-5 mb-1"><?php echo ' :<span class="text-right"> <b>'.number_format($dueamount,2).'</b></span>'; ?></div>
                                </div>

                                </div>


                             
                            </div>
                        </div>
                     </div>
                    <!-- ============================================================= -->
                </div>
            </div>
            
        </div>

        
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#data_form").validate({
            ignore: [],
            rules: {               
                transcat_id: { required: true },
                typename: { required: true },
                coa_header_id: { required: true }
            },
            messages: {
                transcat_id: "Enter Chart of Account Type ID",
                typename: "Enter Chart of Account Type Name",
                coa_header_id: "Select Chart of Account Header"
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
        //datatables
        $('#catgtable').DataTable({responsive: true});

    });

    $('#banking-cat-btn').on('click', function(e) {
        e.preventDefault(); // Prevent the default form submission
        $('#banking-cat-btn').prop('disabled', true);
        
        // Validate the form
        if ($("#data_form").valid()) {                
            var form = $('#data_form')[0];
            var formData = new FormData(form); 
            Swal.fire({
                title: "Are you sure?",
                text: "Do you want to create/update category?",
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
                        url: baseurl + 'bankingcategory/addeditaction', // Replace with your server endpoint
                        type: 'POST',
                        data: formData,
                        contentType: false, 
                        processData: false,
                        success: function(response) {
                            if (typeof response === "string") {
                                response = JSON.parse(response);
                            }
                            if(response.status=='Error')
                            {
                                $('#account-error').removeClass('d-none');  
                                $('#account-btn').prop('disabled', false);
                            }
                            else{
                                $('#account-error').addClass('d-none');  
                                location.reload();
                            }                    
                        },
                        error: function(xhr, status, error) {
                            Swal.fire('Error', 'An error occurred while generating the lead', 'error');
                            console.log(error); // Log any errors
                        }
                    });

                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    // Enable the button again if user cancels
                    $('#banking-cat-btn').prop('disabled', false);
                }
            });
        } else {
            // If form validation fails, re-enable the button
            $('#banking-cat-btn').prop('disabled', false);
        }
    });

    function update_category(id)
    {
        $.ajax({
            type: 'POST',
            url: baseurl +'bankingcategory/load_category_by_id',
            data: {
                "category_id" : id
            },
            success: function(response) {
                var responseData = JSON.parse(response);
                var resultdata = responseData.data[0];
                $("#holder").focus();
                $("#banking-cat-btn").val("Update");           
                $("#headerlabel").text("Update Banking Category");           
                $("#transcat_id").val(resultdata.transcat_id);
                $("#category_id").val(resultdata.id);
                $("#transcat_name").val(resultdata.transcat_name);
                $("#transtype_id").val(resultdata.transtype_id).trigger('change');
                $("#status").val(resultdata.status).trigger('change');
            },
            error: function(xhr, status, error) {
                // Handle error
                console.error(xhr.responseText);
            }
        });
    }

    $(document).ready(function () {
        // Click event for the toggle-row
        $('.toggle-row').click(function () {
            $('#collapse_100').collapse('toggle');
            var icon = $(this).find('.toggle-icon');
            if (icon.hasClass('fa-angle-down')) {
                icon.removeClass('fa-angle-down').addClass('fa-angle-up');
            } else {
                icon.removeClass('fa-angle-up').addClass('fa-angle-down');
            }
        });
        $('.toggle-row-approve').click(function () {
            $('#collapse_101').collapse('toggle');
            var icon = $(this).find('.toggle-icon');
            if (icon.hasClass('fa-angle-down')) {
                icon.removeClass('fa-angle-down').addClass('fa-angle-up');
            } else {
                icon.removeClass('fa-angle-up').addClass('fa-angle-down');
            }
        });
        $('.toggle-row-payment').click(function () {
            $('#collapse_102').collapse('toggle');
            var icon = $(this).find('.toggle-icon');
            if (icon.hasClass('fa-angle-down')) {
                icon.removeClass('fa-angle-down').addClass('fa-angle-up');
            } else {
                icon.removeClass('fa-angle-up').addClass('fa-angle-down');
            }
        });
    });

    $("#request-apprval-btn").on('click', function() {
        Swal.fire({
            title: "Are you sure?",
            text: "Do you want to send the approval request now?",
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
                    url: baseurl + 'expenseclaims/request_for_approval',
                    data: {
                        claim_number: $("#claim_number").val(),
                    },
                    dataType: 'json', // Corrected from `datatype` to `dataType`
                    success: function(response) {
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        
                        console.error(xhr.responseText);
                    }
                });
            }
        });
    });

    $("#mark-approved-btn").on('click', function() {
        Swal.fire({
            title: "Are you sure?",
            text: "Do you want to send the approve now?",
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
                    url: baseurl + 'expenseclaims/approving_the_request',
                    data: {
                        claim_number: $("#claim_number").val(),
                    },
                    dataType: 'json', // Corrected from `datatype` to `dataType`
                    success: function(response) {
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        
                        console.error(xhr.responseText);
                    }
                });
            }
        });
    });
    $("#mark-approved-btn").on('click', function() {
        Swal.fire({
            title: "Are you sure?",
            text: "Do you want to send the approve now?",
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
                    url: baseurl + 'expenseclaims/approving_the_request',
                    data: {
                        claim_number: $("#claim_number").val(),
                    },
                    dataType: 'json', // Corrected from `datatype` to `dataType`
                    success: function(response) {
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        
                        console.error(xhr.responseText);
                    }
                });
            }
        });
    });


 $("#refuse-btn").on('click', function () {
    Swal.fire({
        title: "Are you sure?",
        html: `
            <p>Do you want to approve this request?</p>
            <textarea id="refuse_reason" class="swal2-textarea" style="width: 100%; max-width: 70%; height: 100px;" placeholder="Enter reason for refuse"></textarea>
        `,
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, proceed!',
        cancelButtonText: "No - Cancel",
        reverseButtons: true,
        focusCancel: true, 
        allowOutsideClick: false, 
        preConfirm: () => {
            const reason = document.getElementById('refuse_reason').value.trim();
            if (!reason) {
                Swal.showValidationMessage('Refuse reason is required!');
                return false;
            }
            return reason;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const reason = result.value;
            $.ajax({
                type: 'POST',
                url: baseurl + 'expenseclaims/refusing_the_request',
                data: {
                    claim_number: $("#claim_number").val(),
                    refuse_reason: reason,
                },
                dataType: 'json', // Corrected from `datatype` to `dataType`
                success: function (response) {
                    location.reload();
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }
    });
});

</script>