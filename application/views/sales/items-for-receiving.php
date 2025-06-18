<!-- erp2024 newly added page 07-06-2024 starts -->
<div class="content-body">
    <?php       
    if (($msg = check_permission($permissions)) !== true) {
        echo $msg;
        return;
    }
    ?>
    <div class="card">
        <div class="card-header border-bottom">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('stocktransfer') ?>"><?php echo $this->lang->line('Stock Transfer List'); ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line('Receive Items'); ?></li>
                </ol>
            </nav>
            <h4 class="card-title"><?php echo $this->lang->line('Receive Items') ?></h4>            
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

        </div>
        <div  class="card-body">
           <form  method="post" id="data_form">
             <input type="hidden" name="actionurl" id="action-url" value="Stocktransfer/item_recieve_submit">
                <div class="table-scroll">

                    <!-- ====================================================================  -->
                    <table class="table table-striped table-bordered zero-configuration dataTable">
                        <thead>
                        <tr class="item_header bg-gradient-directional-blue white">
                            <th  class="pl-14"><?php echo $this->lang->line('ProductID') ?></th>
                            <th class="pl-14"><?php echo $this->lang->line('Description') ?></th>
                            <th class="pl-14"><?php echo $this->lang->line('Unit') ?></th>
                            <th class="pl-14"><?php echo $this->lang->line('Transfered From') ?></th>
                            <th class="pl-14"><?php echo $this->lang->line('Transfer To') ?></th>
                            <th class="pl-14"><?php echo $this->lang->line('Requested Qty') ?></th>
                            <th class="pl-14"><?php echo $this->lang->line('Intransit Qty') ?></th>
                            <th class="pl-14"><?php echo $this->lang->line('Received Qty') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php 
                        if(!empty($requestdata))
                        {
                            $i=0;
                            foreach($requestdata as $row){ 
                            echo "<tr>";
                            echo '<td>
                                <input type="text" class="form-control ui-autocomplete-input" name="product_code[]" value="'.$row['product_code'].'" readonly> 
                                <input type="hidden" class="form-control ui-autocomplete-input" name="request_id[]" value="'.$row['id'].'" readonly> 
                                </td>';
                            echo '<td>
                                <input type="text" class="form-control ui-autocomplete-input" name="product_name[]" value="'.$row['product_name'].'" readonly> 
                                <input type="hidden" class="form-control ui-autocomplete-input" name="productid[]"  autocomplete="off" value="'.$row['productid'].'" readonly> 
                                </td>';
                            echo '<td>
                                <input type="text" class="form-control ui-autocomplete-input" name="product_unit[]" value="'.$row['unit'].'" readonly>
                                </td>';
                            echo '<td>
                                <input type="text" class="form-control ui-autocomplete-input" name="warehouse_from_title[]" value="'.$row['warehouse_from_title'].'" readonly>
                                 <input type="hidden" class="form-control ui-autocomplete-input" name="warehouse_from_id[]"  autocomplete="off" value="'.$row['warehouse_from_id'].'" readonly> 
                                </td>';
                            echo '<td>
                                <input type="text" class="form-control ui-autocomplete-input" name="warehouse_to_title[]" value="'.$row['warehouse_to_title'].'" readonly>
                                 <input type="hidden" class="form-control ui-autocomplete-input" name="warehouse_to_id[]"  autocomplete="off" value="'.$row['warehouse_to_id'].'" readonly> 
                                </td>';
                            echo '<td>
                                <input type="text" class="form-control ui-autocomplete-input" name="requested_qty[]" value="'.$row['requested_qty'].'" readonly>
                                </td>';
                            echo '<td>
                                <input type="text" class="form-control ui-autocomplete-input" name="intransit_qty[]" value="'.$row['intransit_qty'].'" readonly>
                                </td>';
                            echo '<td>
                                <input type="text" class="form-control ui-autocomplete-input" name="recieved_qty[]" value="'.$row['intransit_qty'].'" readonly>
                                </td>';
                            echo '</tr>';
                                $i++;
                            }
                        }
                        ?>
                          
                        <tr class="sub_c" style="display: table-row;">
                            <td colspan="2" class="no-border"></td>
                            <td align="right" colspan="7" class="no-border">
                                <input type="submit" class="btn btn-lg btn-crud btn-primary sub-btn" value="<?php echo $this->lang->line('Receive Items') ?>" id="submit-btn" data-loading-text="Creating...">

                            </td>
                        </tr>


                        </tbody>
                    </table>
                    <!-- ====================================================================  -->
                </div>
           </form>
        </div>
    </div>
</div>
<!-- <script src="<?php echo assets_url('assets/myjs/jquery.ui.widget.js') ?>"></script> -->
<script>
$("#warehouse_to").on('change', function(){
    $(".warehousefrom").empty();
    $(".warehousefrom").append('<option value="">Select Warehouse</option>');
});
function warehouseList(id){
    var productVal = $("#productnames-" + id).attr("data-id");
    $("#productid-" + id).val(productVal);
    var mainwarehouse = $("#warehouse_to").val();
    $.ajax({
        url: baseurl + 'Products/warehouse_by_productid',
        dataType: 'json',
        method: 'POST',
        data: {
            'prdid': productVal,
            'mainwarehouse' : mainwarehouse
        },
        success: function(data) {
            var selectElement = $("#warehousefrom-" + id); // Ensure this is the correct selector for your select element
            selectElement.empty(); // Clear existing options
            selectElement.append($('<option>', {
                value: '',
                text: 'Select Warehouse'
            }));
            if (data.length > 0) {
                $.each(data, function(index, item) {
                    selectElement.append($('<option>', {
                        value: item.id,
                        text: item.title+" - Stock("+item.stock_qty+")"
                    }));
                });
            } else {
                selectElement.append($('<option>', {
                    value: '',
                    text: 'No warehouses found'
                }));
            }
        }
    });
}
function selectedProductList(){
    var selectedProducts = $("#selectedProducts").val();
    id = $("#ganak").val();
    $.ajax({
        url: baseurl + 'Products/products_by_id',
        dataType: 'json',
        method: 'POST',
        data: {
            'selectedProducts': selectedProducts
        },
        success: function(data) {
            var selectElement = $("#product-name-" + id);
            selectElement.empty(); 
            selectElement.append($('<option>', {
                value: '',
                text: 'Select Product'
            }));
            if (data.length > 0) {
                $.each(data, function(index, item) {
                    selectElement.append($('<option>', {
                        value: item.id,
                        text: item.title+" - "+item.code
                    }));
                });
            } else {
                selectElement.append($('<option>', {
                    value: '',
                    text: 'No Products found'
                }));
            }
        }
    });
}


 $("#data_form").validate({
        ignore: [],
        rules: {      
            actionurl: { required: true },
        },
        messages: {
            actionurl: "Select Transfer From",   
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
               text: "Do you want to receive items?",
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
                        url: baseurl + 'Stocktransfer/item_recieve_submit',
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: "json",
                        success: function(response) {
                            window.location.href = baseurl + 'stocktransfer';
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
<!-- erp2024 newly added page 07-06-2024 ends -->