<div class="card card-block">
    <div id="notify" class="alert alert-success" style="display:none;">
        <a href="#" class="close" data-dismiss="alert">&times;</a>

        <div class="message"></div>
    </div>
        <?php

        $caption = $this->lang->line('Manual Journal');
          ?>
        <div class="card-header border-bottom">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('manualjournals') ?>"><?php echo $this->lang->line('Manual Journals'); ?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo $caption. " #".$journal_master['journal_number']; ?></li>
                </ol>
            </nav>
            <div class="row">
                <div class="col-12">
                    <?php
                    $editlink="";
                    $editlink = '&nbsp;<a href="' . base_url('manualjournals/edit?id=' . $journal_master['journal_number']) . '" class="btn btn-sm btn-primary">' . $this->lang->line('Edit') . '</a>';
                    ?>
                    <h4 class="card-title">
                        <?php                         
                        echo $caption." #".$journal_master['journal_number'].$editlink;
                        
                        ?>
                    </h4>
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
                        
                        <input type="hidden" name="journal_number" id="journal_number" value="<?=$journal_master['journal_number']?>">
                        <h3><?php echo $this->lang->line('Create'); ?></h3>
                        <h2><?php echo '<span>'.$journal_master['name'].'</span> Created this transaction on <span>'.date('d-m-Y H:i:s', strtotime($journal_master['created_dt'])).'</span>'; ?> </h2>
                        <hr>
                            
                            

                            <!-- =======================Make payment section ================ -->
                         
                           
                            <!-- =======================Make payment section ================ -->

                            <!-- ====================== Journel Section ====================== -->
                            <div class="row toggle-row mt-3" style="cursor: pointer;">
                                <div class="col-11">
                                    <h3><?php echo $this->lang->line('Transactions'); ?></h3>
                                    <p><?php echo $this->lang->line('Transactions are'); ?></p>
                                </div>
                                <div class="col-1 text-right">
                                    <i class="fa fa-angle-down toggle-icon fontsize-20"></i>
                                </div>
                            </div>

                            <div id="collapse_100" class="collapse table-scroll">
                                <table class="table table-striped table-bordered zero-configuration dataTable">
                                    <thead>
                                        <tr>
                                            <th style="width:70%"><?php echo $this->lang->line('Transaction Number'); ?></th>
                                            <th class="text-right"><?php echo $this->lang->line('Income'); ?></th>
                                            <th class="text-right"><?php echo $this->lang->line('Expense'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><?= $journal_master['transaction_number']?></td>
                                            <td class="text-right">0.00</td>
                                            <td class="text-right"><?= number_format($journal_master['journal_amount'], 2) ?></td>
                                        </tr>
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
                            <div class="col-lg-6">

                            
                                <table >
                                    <tr>
                                        <th class="fixedpadding1"><?php echo $this->lang->line('Manual Journal'); ?></th>
                                    </tr>
                                    <tr>
                                        <td class="fixedpadding-bottom1"><b><?php echo $this->lang->line('Journal Number'); ?></b> : <?=$journal_master['journal_number']?></td>
                                    </tr>
                                    <tr>
                                        <td class="fixedpadding-bottom1"><b><?php echo $this->lang->line('Date'); ?> : <?=date('d-m-Y', strtotime($journal_master['journal_date']))?></td>
                                    </tr>
                                    <tr>
                                        <td class="fixedpadding-bottom1"><b><?php echo $this->lang->line('Reference'); ?> : <?=$journal_master['journal_reference']?></td>
                                    </tr>
                                    <tr>
                                        <td class="fixedpadding-bottom1"><b><?php echo $this->lang->line('Descriptions'); ?></b> : <?=$journal_master['journal_note']?></td>
                                    </tr>
                                    
                                    
                                </table>
                            </div>
        
                            <div class="col-12 table-scroll">
                                             
                            <table class="table table-striped table-bordered zero-configuration dataTable" style="width:100%"> 
                            <thead>
                                <tr>
                                    <th ><?php echo $this->lang->line('Account'); ?></th>
                                    <th ><?php echo $this->lang->line('Descriptions'); ?></th>
                                    <th class="text-right"><?php echo $this->lang->line('Debit'); ?></th>
                                    <th class="text-right"><?php echo $this->lang->line('Credit'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php        
                                if($journal_items)
                                { 
                                    foreach($journal_items as $row)
                                    {
                                        ?>
                                        <tr>
                                            <td><?=$row['acn']." - ".$row['holder']?></td>
                                            <td><?=$row['description']?></td>
                                            <td class="text-right"><?=number_format($row['debit'],2)?></td>
                                            <td class="text-right"><?=number_format($row['credit'],2)?></td>
                                        </tr>
                                        <?php

                                    }
                                }

                                ?>
                                
                                
                            </tbody>
                        </table>
                            </div>
                        </div>
                        <div class="row mt-3 mb-3">
                            <div class="col-lg-7 col-md-8 col-sm-12"></div>
                            <div class="col-lg-5 col-md-4 col-sm-12">
                                <div class="row">
                                    <div class="col-lg-7 col-md-7 sol-sm-12 mb-1 text-right"><?php echo $this->lang->line('Total'); ?></div>
                                    <div class="col-lg-5 col-md-5 col-sm-12 mb-1 text-right"><?php echo '&nbsp;&nbsp;:<span class="text-right"> <b>'.number_format($journal_master['journal_amount'],2).'</b></span>'; ?></div>

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