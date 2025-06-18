<div class="card card-block">
    <div id="notify" class="alert alert-success" style="display:none;">
        <a href="#" class="close" data-dismiss="alert">&times;</a>

        <div class="message"></div>
    </div>

        <div class="card-header border-bottom">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('paymentgateways/bank_accounts') ?>"><?php echo $this->lang->line('Bank Accounts'); ?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo $bank_accounts['name']; ?></li>
                </ol>
            </nav>
           <div class="row">
                <div class="col-6">
                    <h4><?php 
                    $code = $bank_accounts['code'];
                    echo $bank_accounts['name']; ?>&nbsp;<a href="<?= base_url('paymentgateways/edit_bank_ac?id=' . $bank_accounts['id']); ?>" class="btn btn-sm btn-primary"><?php echo $this->lang->line('Edit'); ?></a> <a href="<?php echo base_url('bankingtransactions/create?type=income&code='.$code) ?>" class="btn btn-secondary btn-sm rounded"> <?php echo $this->lang->line('Add New Income') ?></a> <a href="<?php echo base_url('bankingtransactions/create?type=expense') ?>" class="btn btn-secondary btn-sm rounded"> <?php echo $this->lang->line('Add New Expense') ?></a></h4>
                </div>
                <div class="col-6">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-12 text-center">
                            <div class="bank-summary">
                                <h4><?=number_format($bank_summary['total_income'],2)?><div class="font-13"><?php echo $this->lang->line('Incoming'); ?></div></h4>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 text-center">
                            <div class="bank-summary">
                                <h4><?=number_format($bank_summary['total_expense'],2)?><div class="font-13"><?php echo $this->lang->line('Outgoing'); ?></div></h4>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 text-center">
                            <div class="bank-summary">
                                <h4><?=number_format($bank_summary['current_balance'],2)?><div class="font-13"><?php echo $this->lang->line('Current Balance'); ?></div></h4>
                            </div>
                        </div>
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
            <div class="col-12 row"> 
                <div class="col-lg-3 col-md-3 col-sm-12">
                    <div class="account-left-section">
                        <h2><?php echo $this->lang->line('Account Number').' <br><span>'.$bank_accounts['acn'].'</span>'; ?> </h2>
                        <h2><?php echo $this->lang->line('Account Code').' <br><span>'.$bank_accounts['code'].'</span>'; ?> </h2>
                        <h2><?php echo $this->lang->line('Opening Balance / Deposit').' <br><span>'.$bank_accounts['opening_balance'].'</span>'; ?> </h2>
                    </div>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-12 borderleft">
                    <!-- ============================================================= -->
                    <h4 class="border-bottom">Transactions</h4>
                    <table id="catgtable" class="table table-striped table-bordered zero-configuration" cellspacing="0"
                        width="100%">
                        <thead>
                        <tr>
                            <th><?php echo $this->lang->line('Date') ?></th>
                            <th><?php echo $this->lang->line('Transaction Number') ?></th>
                            <th><?php echo $this->lang->line('Type') ?></th>
                            <th><?php echo $this->lang->line('Contact Person') ?></th>
                            <th><?php echo $this->lang->line('Document') ?></th>
                            <th  class='text-right'><?php echo $this->lang->line('Amount') ?></th>


                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $i = 1;
                        foreach ($transactions as $row) {
                            $cid = $row->id;
                            $purchase_id = $row->purchase_id;
                            $date = ($row->trans_date) ? date('d-m-Y H:i:s', strtotime($row->trans_date)) : "";
                            $document =  ($row->invoicenumber) ? $row->invoicenumber : $row->purchasereceipt;                       
                            $validtoken = hash_hmac('ripemd160', $row->purchasereceipt, $this->config->item('encryption_key'));
                            $link = ($row->invoicenumber)? "<a href='" . base_url("invoices/view?id=$cid") . "' title='View'>".$row->invoicenumber."</a>":"<a href='" . base_url("invoices/costing?id=$purchase_id&token=$validtoken") . "' title='View'>".$document."</a>";
                            echo "<tr>
                                <td>$date</td>
                                <td>$row->trans_number</td>
                                <td>$row->trans_type</td>
                                <td>$row->customername</td>    
                                <td>".$link."</td>             
                                <td class='text-right'>$row->trans_amount</td>                 
                                </tr>";
                                // <td><button onclick='update_category($cid)' class='btn btn-secondary btn-sm' title='Edit'><i class='icon-pencil'></i></button>&nbsp;</td>
                            // $i++;
                        }
                        ?>
                        </tbody>
                    
                    </table>
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
        $('#catgtable').DataTable({
            responsive: true,
            dom: 'Bfrtip', // Add the Buttons controls
            buttons: [
                {
                    extend: 'excelHtml5',
                    text: 'Export Excel',
                    title: 'Category Table Data',
                    className: 'btn btn-success'
                },
                {
                    extend: 'pdfHtml5',
                    text: 'Export PDF',
                    title: 'Category Table Data',
                    className: 'btn btn-danger',
                    orientation: 'landscape',
                    pageSize: 'A4'
                }
            ]
        });


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
</script>