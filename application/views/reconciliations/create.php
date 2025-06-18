<div class="card card-block">
    <div id="notify" class="alert alert-success" style="display:none;">
        <a href="#" class="close" data-dismiss="alert">&times;</a>

        <div class="message"></div>
    </div>
    <div class="card-body">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
               <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard') ?></a></li>
               <li class="breadcrumb-item" aria-current="page"><a href="<?= base_url('reconciliations') ?>"><?php echo $this->lang->line('Reconciliations') ?></a></li>
               <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line('Reconciliation') ?></li>
            </ol>
        </nav>
        <h5 class="title"> <?php echo $this->lang->line('Reconciliation'); ?> </h5>
        
        <hr>
        <div class="card card-block">
            <form method="post" id="data_form" class="form-horizontal">
                <div class="form-group row">                
                    
                    <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="name"><?php echo $this->lang->line('Start Date') ?><span class="compulsoryfld">*</span></label>
                        <input type="date" class="form-control margin-bottom  required" name="start_date" id="start_date" value="<?=$datefrom?>" max="<?=date('Y-m-d')?>" required>
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="name"><?php echo $this->lang->line('End Date') ?><span class="compulsoryfld">*</span></label>
                        <input type="date" class="form-control margin-bottom  required"  name="end_date" id="end_date" value="<?=$dateto?>"  max="<?=date('Y-m-d')?>" required>
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="name"><?php echo $this->lang->line('Closing Balance') ?><span class="compulsoryfld">*</span></label>
                        <input type="number" class="form-control margin-bottom  required"  name="closing_balance" id="closing_balance" value="0.00">
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                           <div class="form-group"> 
                              <label for="account" class="col-form-label"><?php echo $this->lang->line('Account') ?><span class="compulsoryfld"> *</span></label>
                              <select name="bank_account" id="bank_account" class="form-control required" required>
                                 
                                 <?php foreach ($bankaccounts as $row) {
                                    $sel = "";
                                    if ($account_code == $row['code']) {
                                          $sel = 'selected';
                                    }
                                    echo '<option value="' . $row['code'] . '" ' . $sel . '>' . $row['code'] . ' - ' . $row['name'] . '</option>';
                                 }
                                 ?>
                              </select>

                           </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12 mt-32px">
                            <button id="transaction_search_btn" type="submit" class="btn  btn-secondary"><?php echo $this->lang->line('Search Transactions') ?></button>
                            <input type="hidden" name="id" value="0">
                        </div>
                    
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label" for="name"><?php echo $this->lang->line('Note') ?><span class="compulsoryfld">*</span></label>
                          <textarea name="note" id="note" class="form-textarea" placeholder="<?php echo $this->lang->line('Note') ?>"></textarea>
                        </div>
                        <div class="col-12 mt-4">
                            <h4><?php echo $this->lang->line('Transactions') ?></h4>
                            <hr>

                            <!-- ================================================= -->
                            <table class="table table-striped table-bordered zero-configuration dataTable" id="tarasactiontbl">
                                <thead>
                                    <tr class="item_header bg-gradient-directional-blue white">
                                    <th width="10%" class="text-center1 pl-1"><?php echo $this->lang->line('Date') ?></th>
                                    <th width="8%" class="text-center1 pl-1"><?php echo $this->lang->line('Transaction').' #' ?></th>
                                    <!-- <th width="15%" class="text-center1 pl-1"><?php echo $this->lang->line('Descriptions') ?></th> -->
                                    <th width="25%" class=""><?php echo $this->lang->line('Contact') ?></th>
                                    <th width="10%" class="text-right"><?php echo $this->lang->line('Deposit') ?></th>
                                    <th width="10%" class="text-right"><?php echo $this->lang->line('Withdrawal') ?></th>
                                    <th><?php echo $this->lang->line('Clear') ?></th>
                                    </tr>
                                </thead>
                                <tbody id="journal-rows">
                                    <?php
                                
                                    if($banktransactions)
                                    {  
                                        $i=0;
                                        foreach($banktransactions as $row)
                                        {
                                            $date = $row['trans_date'];
                                            $trans_ref_number = $row['trans_ref_number'];
                                            $trans_amount = $row['trans_amount'];
                                            $checked = ($row['flag']==1)? "checked":"";
                                            
                                            $contact = ($row['supplier'])?$row['supplier']:$row['customer'];
                                            echo "<tr>";
                                            echo "<td>".$row['trans_date']."<input type='hidden' name='trans_date[]' id='trans_date-$i' value='$date'></td>";
                                            echo "<td>".$row['trans_ref_number']."<input type='hidden' name='trans_ref_number[]' id='trans_ref_number-$i' value='$trans_ref_number'></td>";
                                            // echo "<td></td>";
                                            echo "<td>".$contact."</td>";
                                            if($row['credit']>0)
                                            {
                                                echo "<td></td>";
                                                echo "<td class='text-right'>".$row['trans_amount']."<input type='hidden' name='transtype[]' id='transtype-$i' value='credit'></td>";
                                            }
                                            else{
                                                echo "<td class='text-right'>".$row['trans_amount']."<input type='hidden' name='transtype[]' id='transtype-$i' value='debit'></td>";
                                                echo "<td></td>";
                                            }
                                            echo "<td><input type='hidden' name='trans_amount[]' id='trans_amount-$i' value='$trans_amount'><input type='checkbox'class='clear_check' id='clear_check-$i' $checked><input type='hidden' name='checkedflg[]' id='checkedflg-$i' value='0'></td>";
                                            echo "</tr>";
                                            $i++;
                                        }
                                    }

                                    ?>
                                
                                </tbody>
                            
                            </table>
                            <!-- ================================================= -->
                                    
                            <hr>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12"></div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="row">
                                <div class="col-9 text-right">
                                    <label for="account" class="col-form-label"><?php echo $this->lang->line('Opening Balance') ?></label>
                                </div>
                                <div class="col-3 text-right">
                                   
                                    <?php 
                                        $opening_balance = ($banktransactions[0]['opening_balance']>0) ? $banktransactions[0]['opening_balance']:0.00;
                                    ?>
                                     <label  class="col-form-label" id="opening_balance_text"><?php echo (number_format($opening_balance,2)); ?></label>
                                    <input type="hidden" id="opening_balance" name="opening_balance" value="<?php echo $opening_balance ?>">
                                </div>

                                <div class="col-9 text-right">
                                    <label for="account" class="col-form-label" ><?php echo $this->lang->line('Closing Balance') ?></label>
                                </div>
                                <div class="col-3 text-right">
                                    <label  class="col-form-label" id="closing_balance_text">0.00</label>                                    
                                    <input type="hidden" id="closing_balance_val" name="closing_balance_val" value="0.00">
                                </div>
                                <div class="col-9 text-right">
                                    <label for="account" class="col-form-label"><?php echo $this->lang->line('Cleared Amount') ?></label>
                                </div>
                                <div class="col-3 text-right">
                                    <label  class="col-form-label" id="cleared_amount_text">0.00</label>
                                    <input type="hidden" id="cleared_amount" name="cleared_amount">
                                </div>
                                <div class="col-9 text-right">
                                    <label for="account" class="col-form-label"><?php echo $this->lang->line('Difference') ?></label>
                                </div>
                                <div class="col-3 text-right">
                                    <label  class="col-form-label" id="difference_amount_text">0.00</label>
                                    <input type="hidden" id="difference_amount" name="difference_amount">
                                </div>
                            </div>
                        </div>
                       

                    </div>
                    
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mt-2 text-right">
                        <input type="submit" id="reconcile-btn" class="btn btn-crud btn-primary btn-lg margin-bottom disable-class"
                            value="<?php echo $this->lang->line('Reconcile') ?>" data-loading-text="Adding...">
                    </div>
                </div>
            </form>
        </div>

        
    </div>
</div>

<script type="text/javascript">

    $(document).ready(function () {

        
        // Handle change event for start_date
        $('#start_date').on('change', function () {
            let startDate = $(this).val();
            let endDateInput = $('#end_date');

            // Set min date for end_date
            endDateInput.attr('min', startDate);

            // Clear end_date if its value is less than start_date
            if (endDateInput.val() && endDateInput.val() < startDate) {
                endDateInput.val('');
            }
        });

        // Handle change event for end_date
        $('#end_date').on('change', function () {
            let endDate = $(this).val();
            let startDateInput = $('#start_date');

            // Set max date for start_date
            startDateInput.attr('max', endDate);

            // Clear start_date if its value is greater than end_date
            if (startDateInput.val() && startDateInput.val() > endDate) {
                startDateInput.val('');
            }
        });



        $("#bank_account").select2();
        $("#data_form").validate($.extend(true, {}, globalValidationOptions,{
            ignore: [],
            rules: {               
                start_date : { required: true },
                end_date: { required: true },
                closing_balance: { required: true },
                closing_balance: { required: true }
            },
            messages: {
                start_date: "Enter Start Date",
                end_date: "Enter end date",
                closing_balance: "Select journal basis",
                closing_balance: "Enter Closing Balance",
            }
        }));
        
      
    });

    $('#reconcile-btn').on('click', function(e) {
        e.preventDefault(); // Prevent the default form submission
        $('#reconcile-btn').prop('disabled', true);
        let isValid = true;


        $("select[name='bank_account']").removeClass("is-invalid");

        // Validate each 'account[]' field manually
        $("select[name='account[]']").each(function () {
            if ($(this).val() === "" || $(this).val() === null) {
                $(this).addClass("is-invalid");
                isValid = false;
            }
        });

        // If any account[] field is invalid, show validation error popup and stop form submission
        if (!isValid && $('.clear_check:checked').length > 0) {
            Swal.fire('Validation Error', 'Please ensure all account fields are selected.', 'error');
            return; // Exit function if validation fails
        }
        // Validate the form
        if ($("#data_form").valid()) {                
            var form = $('#data_form')[0];
            var formData = new FormData(form); 
            Swal.fire({
                title: "Are you sure?",
                text: "Do you want to create a new Reconciliation?",
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
                        url: baseurl + 'reconciliations/action',
                        type: 'POST',
                        data: formData,
                        contentType: false, 
                        processData: false,
                        success: function(response) {
                           
                            window.location.href = baseurl + 'reconciliations';
                            
                        },
                        error: function(xhr, status, error) {
                            Swal.fire('Error', 'An error occurred while generating the reconciliation', 'error');
                            console.log(error); // Log any errors
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    // Enable the button again if user cancels
                    $('#reconcile-btn').prop('disabled', false);
                }
            });
        } else {
            // If form validation fails, re-enable the button
            $('#reconcile-btn').prop('disabled', false);
        }
    });


    $('#transaction_search_btn').on('click', function(e) {
        e.preventDefault(); // Prevent default button action
        // Retrieve values
        const startDate = $("#start_date").val();
        const endDate = $("#end_date").val();
        const bankAccount = $("#bank_account").val();

        // Validate inputs
        if (!startDate || !endDate || !bankAccount) {
            Swal.fire({
                title: "Missing Information!",
                text: "Please fill in all required fields: Start Date, End Date, and Bank Account.",
                icon: "warning",
                confirmButtonText: "OK",
                allowOutsideClick: false,
            });
            return; // Stop execution if validation fails
        }

        Swal.fire({
            title: "Are you sure?",
            text: "Do you want to search the transaction list between the selected date range?",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, proceed!',
            cancelButtonText: "No - Cancel",
            reverseButtons: true,  
            focusCancel: true,     // Focus on the Cancel button
            allowOutsideClick: false,  // Disable outside click
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = baseurl + 'reconciliations/create?datefrom=' + $("#start_date").val() + '&dateto=' + $("#end_date").val() + '&bank_account=' + $("#bank_account").val();

            }
        });
    });




    $(document).ready(function() {
        // values_set();
        // Function to calculate and update the Cleared Amount
        function updateClearedAmount() {
            // Retrieve the opening and closing balances and parse them as floats
            var opening_balance = parseFloat($("#opening_balance").val()) || 0;
            var closing_balance = parseFloat($("#closing_balance").val()) || 0;
            var cleared_amount = 0; // Initialize cleared amount
            var DifferenceAmount = closing_balance - opening_balance; // Default difference calculation

            // Check if any checkboxes are checked
            if ($('.clear_check:checked').length > 0) {
                // Iterate over each checked checkbox
                $('.clear_check:checked').each(function () {
                    // Extract the index from the checkbox id
                    var index = $(this).attr('id').split('-')[1];
                    var amount = parseFloat($('#trans_amount-' + index).val()) || 0;
                    var transtype = $('#transtype-' + index).val();
                    // Adjust cleared amount based on the transaction type
                    if (transtype === 'credit') {
                        cleared_amount -= amount;
                        // cleared_amount += opening_balance; 
                    } else if (transtype === 'debit') {
                        cleared_amount += amount;
                        // cleared_amount += opening_balance; 
                    }
                });

                // Update difference amount based on cleared transactions
                // if (cleared_amount > 0) {
                //     DifferenceAmount = closing_balance - cleared_amount;
                // } else {
                //     DifferenceAmount = closing_balance + cleared_amount;
                // }
            }

            // If no cleared amount is calculated, default it to the opening balance
            if (cleared_amount === 0) {
                cleared_amount = opening_balance;
                
            }
            else
            {
                cleared_amount = cleared_amount+opening_balance;
            }
            DifferenceAmount = closing_balance - cleared_amount;
            if(DifferenceAmount==0)
            {
                $('#reconcile-btn').removeClass('disable-class');
            }
            else{
                $('#reconcile-btn').addClass('disable-class');
            }
            // Update the text values
            $("#difference_amount_text").text(DifferenceAmount.toFixed(2));
            $("#cleared_amount_text").text(cleared_amount.toFixed(2));
            $("#difference_amount").val(DifferenceAmount.toFixed(2));
            $("#cleared_amount").val(cleared_amount.toFixed(2));
        }



        $('.clear_check').change(function() {
            updateClearedAmount();
            var index = $(this).attr('id').split('-')[1]; 
            if ($(this).is(':checked')) {
                $("#checkedflg-" + index).val(1); 
            } else {
                $("#checkedflg-" + index).val(0); 
            }
        });


        // Initial calculation on page load
        updateClearedAmount();

        function updateBalances() {
            // Get the value entered in the closing_balance input and parse it as a float
            var closingBalance = parseFloat($('#closing_balance').val()) || 0;

            // Update the text of #closing_balance_text to match the closing balance
            $('#closing_balance_text').text(closingBalance.toFixed(2));
            $('#closing_balance_val').val(closingBalance.toFixed(2));

            // Get the value of #difference_amount and parse it as a float
            // var cleared_amount = parseFloat($('#cleared_amount').val()) || 0;
            var cleared_amount = Math.abs($('#cleared_amount').val()) || 0;
            // Calculate the difference
            var calculatedDifference =  closingBalance - cleared_amount;

            // Update the value of #calculated_difference with the result
            $('#difference_amount_text').text(calculatedDifference.toFixed(2));
            // Check if at least one checkbox is selected and calculatedDifference is zero
            if ($('.clear_check:checked').length > 0 && calculatedDifference === 0) {
                // Remove the disable-class from the submit button
                $('#reconcile-btn').removeClass('disable-class');
            } else {
                // Add the disable-class to the submit button if conditions are not met
                $('#reconcile-btn').addClass('disable-class');
            }
    }

    // Attach the updateBalances function to the input event of #closing_balance
    $('#closing_balance').on('input', function() {
        updateBalances();
    });

    // Initial call to set values on page load
    updateBalances();
});
 




</script>