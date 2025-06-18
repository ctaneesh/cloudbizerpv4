<div class="content-body">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title" id="card-title"><?php echo $this->lang->line('Generate Barcode') ?></h4>
            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
            <div class="heading-elements">
                <ul class="list-inline mb-0">
                    
                    <li><a class="breaklink" data-action="expand"><i class="ft-maximize"></i></a></li>
                    
                </ul>
            </div>
        </div>
        <div class="card-body">
            <!-- erp2024 add enctype="multipart/form-data 01-06-2024 -->
            <div class="alert alert-warning alert-dismissible d-none" role="alert" id="response-alert">
                <div id="responsemsg"></div>
            </div>
            
            <form id="post_form" method="post" action="<?= base_url()?>Products/barcodeprint" enctype="multipart/form-data" autocomplete="off">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body1">
                           
                            <div class="tab-content px-1 pt-1">
                                <div class="tab-pane active show" id="tab1" role="tabpanel" aria-labelledby="base-tab1">
                                    <div class="form-group row mt-1">

                                        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">                                        
                                            <label class="col-form-label" for="name"><?php echo $this->lang->line('Product Code') ?><span class="compulsoryfld">*</span></label>
                                            <input type="text" placeholder="Code"  class="form-control margin-bottom b_input required" name="code" id="" required="required">
                                        </div>

                                        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                            <label class="col-form-label" for="name"><?php echo $this->lang->line('How Many') ?><span class="compulsoryfld">*</span></label>
                                            <input type="text" placeholder="Quantity" class="form-control margin-bottom b_input" name="qty" required="required">
                                        </div>

                                        

                                <div class="tab-pane show" id="tab4" role="tabpanel" aria-labelledby="base-tab4">

                                </br>
                                <div id="mybutton" class="submit-section text-right">
                                    <input type="submit"  class="btn btn-lg btn-primary margin-bottom float-xs-right mr-2" value="<?php echo $this->lang->line('Generate') ?>"  data-loading-text="Adding...">
                                </div>
                            </form>
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

<script>
    
    $("#cust_add_submit").on("click", function (e) {
        e.preventDefault();
        $('#cust_add_submit').prop('disabled', true);
        if ($("#cust_data_form").valid()) {

            var formData = new FormData($("#cust_data_form")[0]);
        
            Swal.fire({
               title: "Are you sure?",
               text: "Do you want to update the customer?",
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
                        url: baseurl + 'Products/barcodeprint',
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: "json",
                        success: function(response) {
                            // $("#response-alert").removeClass("d-none");
                            // $("#responsemsg").html(response.message);
                            window.location.href = baseurl + 'barcodeprint';

                            // if (response.status === "Success") {
                            //     $("#response-alert").removeClass("alert-danger").addClass("alert-success");
                            // } else {
                            //     $("#response-alert").removeClass("alert-success").addClass("alert-danger");
                            // }
                            // document.getElementById("card-title").scrollIntoView();
                            // $("#card-title").focus();
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
               }
               else{
                    $('#cust_add_submit').prop('disabled', false);
               }
            });
        }
        else {
            $('#cust_add_submit').prop('disabled', false);
            $('html, body').animate({
                scrollTop: $("#cust_data_form").offset().top
            }, 2000);
            $("#cust_data_form").focus();
        }
    });
</script>