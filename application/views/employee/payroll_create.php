<?php       
  if (($msg = check_permission($permissions)) !== true) {
     echo $msg;
     return;
  }
 ?>
<div class="content-body">
    <div class="card">
        <div class="card-header">
            <h5><?php echo $this->lang->line('Add') . ' ' . $this->lang->line('Payroll') . ' ' . $this->lang->line('Transactions') ?></h5>
            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
            <div class="heading-elements">
                <ul class="list-inline mb-0">
                    
                    <li><a class="breaklink" data-action="expand"><i class="ft-maximize"></i></a></li>
                    
                </ul>
            </div>
        </div>
        <div class="card-content1">
            <div id="notify" class="alert alert-success" style="display:none;">
                <a href="#" class="close" data-dismiss="alert">&times;</a>

                <div class="message"></div>
            </div>
            <div class="card card-body">


                <form method="post" id="data_form" class="form-horizontal">
                    <input type="hidden" name="ty_p" value="4">
                    <div class="form-group row">
                        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <label for="cst" class="caption col-form-label"><?php echo $this->lang->line('Search Payer') ?></label>
                            <input type="text" class="form-control" name="cst" id="trans-box" placeholder="Enter Employee Name or Mobile Number to search" autocomplete="off"/>
                            <div id="trans-box-result" class="sbox-result"></div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12" id="customerpanel">
                            <label for="toBizName"  class="caption col-form-label"><?php echo $this->lang->line('C/o') ?> <span>*</span></label>
                            <input type="hidden" name="payer_id" id="customer_id" value="0">
                            <input type="text" class="form-control required" name="payer_name" id="customer_name">
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label"  for="pay_cat"><?php echo $this->lang->line('Account') ?></label>
                            <select name="pay_acc" class="form-control">
                                <?php
                                foreach ($accounts as $row) {
                                    $cid = $row['id'];
                                    $acn = $row['acn'];
                                    $holder = $row['holder'];
                                    echo "<option value='$cid'>$acn - $holder</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <input type="hidden" name="act" value="add_product">
                        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label"  for="date"><?php echo $this->lang->line('Date') ?><span class="compulsoryfld">*</span></label>
                            <input type="text" class="form-control required"
                                   name="date" data-toggle="datepicker"
                                   autocomplete="false">
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label"  for="amount"><?php echo $this->lang->line('Amount') ?><span class="compulsoryfld">*</span></label>
                            <input type="text" placeholder="Amount" onkeypress="return isNumber(event)"
                                   class="form-control margin-bottom  required" name="amount" id="eamt">
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label"  for="product_price"><?php echo $this->lang->line('Type') ?></label>
                            <div class="input-group">
                                <select name="pay_type" class="form-control">
                                    <option value="Expense" selected><?php echo $this->lang->line('Expense') ?></option>
                                    <option value="Income"><?php echo $this->lang->line('Income') ?></option>


                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label"
                               for="pay_cat"><?php echo $this->lang->line('Category') ?></label>
                            <select name="pay_cat" class="form-control">
                                <option value='EmployeePayment'>Employee Payment</option>
                                <?php
                                foreach ($cat as $row) {
                                    $cid = $row['id'];
                                    $title = $row['name'];
                                    echo "<option value='$title'>$title</option>";
                                }
                                ?>
                            </select>


                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label"
                               for="product_price"><?php echo $this->lang->line('Method') ?> </label>
                            <div class="input-group">
                                <select name="paymethod" class="form-control">
                                    <option value="Cash" selected><?php echo $this->lang->line('Cash') ?></option>
                                    <option value="Card"><?php echo $this->lang->line('Card') ?></option>
                                    <option value="Cheque"><?php echo $this->lang->line('Cheque') ?></option>
                                </select>

                            </div>
                        </div>                        
                        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label"><?php echo $this->lang->line('Note') ?></label>
                            <input type="text" placeholder="Note"
                                   class="form-control" name="note">
                        </div>
                    </div>
                    <!---- Dual -->
                    <?php if ($dual['key1']) { ?>
                        <hr><h4 class="purple"><?php echo $this->lang->line('Dual Entry') ?></h4>
                        <div id="customerpanel" class="form-group row bg-purple bg-lighten-4 pb-1">


                            <div class="col-sm-4"><label class=" col-form-label"
                                                         for="f_pay_cat"><?php echo $this->lang->line('From') . ' ' . $this->lang->line('Account') ?></label>
                                <select name="f_pay_acc" class="form-control">
                                    <?php
                                    foreach ($accounts as $row) {
                                        $cid = $row['id'];
                                        $acn = $row['acn'];
                                        $holder = $row['holder'];
                                        echo "<option value='$cid'>$acn - $holder</option>";
                                    }
                                    ?>
                                </select>


                            </div>


                            <div class="col-sm-4"><label class="col-form-label"
                                                         for="f_pay_cat"><?php echo $this->lang->line('From') . ' ' . $this->lang->line('Category') ?></label>
                                <select name="f_pay_cat" class="form-control">
                                    <?php
                                    foreach ($cat as $row) {
                                        $cid = $row['id'];
                                        $title = $row['name'];
                                        echo "<option value='$title'>$title</option>";
                                    }
                                    ?>
                                </select>


                            </div>


                            <div class="col-sm-4"><label class="col-form-label"
                                                         for="f_paymethod"><?php echo $this->lang->line('From') . ' ' . $this->lang->line('Method') ?> </label>

                                <select name="f_paymethod" class="form-control">
                                    <option value="Cash" selected><?php echo $this->lang->line('Cash') ?></option>
                                    <option value="Card"><?php echo $this->lang->line('Card') ?></option>
                                    <option value="Cheque"><?php echo $this->lang->line('Cheque') ?></option>
                                    <option value="Bank"><?php echo $this->lang->line('Bank') ?></option>
                                    <option value="Other"><?php echo $this->lang->line('Other') ?></option>
                                </select>


                            </div>


                        </div>
                        <div class="form-group row  bg-lighten-4 pb-1">

                            <div class="col-sm-8"><label
                                        class="col-form-label"><?php echo $this->lang->line('From') . ' ' . $this->lang->line('Note') ?></label>
                                <input type="text" placeholder="Note"
                                       class="form-control" name="f_note">
                            </div>
                        </div>
                    <?php } ?>

                    <div class="form-group row">

                        <label class="col-form-label"></label>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 submit-section text-right">
                            <input type="submit" id="submit-btn" class="btn btn-crud btn-primary btn-lg margin-bottom"
                                   value="<?php echo $this->lang->line('Add transaction') ?>"
                                   data-loading-text="Adding...">
                            <input type="hidden" value="transactions/save_trans" id="action-url">
                        </div>
                    </div>


                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $("#trans-box").keyup(function () {
        $.ajax({
            type: "GET",
            url: baseurl + 'employee/emp_search',
            data: 'keyword=' + $(this).val() + '&ty=' + $('input[name=ty_p]:checked').val(),
            beforeSend: function () {
                $("#trans-box").css("background", "#FFF url(" + baseurl + "assets/custom/load-ring.gif) no-repeat 165px");
            },
            success: function (data) {
                $("#trans-box-result").show();
                $("#trans-box-result").html(data);
                $("#trans-box").css("background", "none");

            }
        });
    });

    function selectPay(cid, cname, salary) {
        $('#customer_id').val(cid);
        $('#customer_name').html('<strong>' + cname + '</strong>');
        $('#customer_name').val(cname);
        $('#eamt').val(salary);
        $("#customer-box").val();
        $("#customer-box-result").hide();
        $(".sbox-result").hide();
        $("#customer").show();
    }

    $("#data_form").validate({
        ignore: [],
        rules: {      
            cst: { required: true },
            payer_name: { required: true },
            amount: { required: true },
        },
        messages: {
            cst: "Enter Payer",
            payer_name: "Enter Payer Name",
            amount: "Enter Amount",
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
               text: "Do you want to add a new payroll?",
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
                        url: baseurl + 'transactions/save_trans',
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: "json",
                        success: function(response) {
                            window.location.href = baseurl + 'employee/payroll';
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
