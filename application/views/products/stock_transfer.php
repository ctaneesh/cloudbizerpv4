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
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line('Stock Transfer') ?></li>
                </ol>
            </nav>
            <h4 class="card-title"><?php echo $this->lang->line('Stock Transfer') ?></h4>
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
            <div class="card-body">
                <form method="post" id="data_form" class="form-horizontal">
                    <input type="hidden" name="act" value="add_product">
                    <div class="form-group row">
                    
                        <div class="col-sm-4">
                            <label class="col-form-label" for="product_cat"><?php echo $this->lang->line('Transfer From') ?></label>
                            <select id="wfrom" name="from_warehouse" class="form-control required">
                                <option value=''>Select Warehouse</option>
                                <?php
                                foreach ($warehouse as $row) {
                                    $cid = $row['id'];
                                    $title = $row['title'];
                                    echo "<option value='$cid'>$title</option>";
                                }
                                ?>
                            </select>
                        </div>
                        
                        <div class="col-sm-4">
                            <label class="col-form-label" for="product_cat"><?php echo $this->lang->line('Transfer To') ?></label>
                            <select name="to_warehouse" class="form-control required" id="wto">
                            <option value=''>Select Warehouse</option>
                                <?php
                                foreach ($warehouse as $row) {
                                    $cid = $row['id'];
                                    $title = $row['title'];
                                    echo "<option value='$cid'>$title</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <style>
                        .fixed-width-select{
                            width: 380px !important;
                        }
                    </style>
                    <!-- ====================================================================  -->
                        <div id="saman-row" class="col-lg-8 col-md-12 table-scroll pl-0">
                            <table class="table table-striped table-bordered zero-configuration dataTable">
                                <thead>
                                <tr class="item_header bg-gradient-directional-blue white">
                                    <th width="30%" class="pl-14"><?php echo $this->lang->line('Product') ?></th>
                                    <th width="15%" class="pl-14"><?php echo $this->lang->line('Unit') ?></th>
                                    <th width="15%" class="pl-14"><?php echo $this->lang->line('On Hand Qty') ?></th>
                                    <th width="15%" class="text-center"><?php echo $this->lang->line('Quantity') ?></th>
                                    <th width="10%" class="text-center"><?php echo $this->lang->line('Action') ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td> 
                                        <input type="text" id="productrequest-0" name="products_l[]" class="form-control  product_requests" placeholder="<?php echo $this->lang->line('Enter Product name') ?>">
                                        <input type="hidden" id="products_id_0" name="products_id[]" class="form-control product_requests" >
                                        <!-- <select id="products-0" name="products_l[]" class="form-control required fixed-width-select productselect" ><option value="">Select Product</option></select>   -->
                                    </td>
                                    <td>
                                        <input name="products_unit[]" id="products_unit_0" class="form-control  products_units responsive-width-elements" type="text" readonly>
                                    </td>
                                    <td>
                                        <input name="onhand[]" id="onhand-0" class="form-control  onhand_qty" type="text" readonly>
                                    </td>
                                    <td><input name="products_qty[]" id="products_qty_0" class="form-control  product_qty" type="number"></td>
                                    <td class="text-center">--</td>
                                </tr>

                                <tr class="last-item-row sub_c tr-border">
                                    <td class="add-row no-border">
                                        <button type="button" class="btn btn-secondary" aria-label="Left Align"
                                                data-toggle="tooltip"
                                                data-placement="top" id="stock-transfer-addrow">
                                            <i class="icon-plus-square"></i> <?php echo $this->lang->line('Add Row') ?>
                                        </button>
                                        <input type="hidden" name="ganak" id="ganak" value="0">
                                    </td>
                                    <td colspan="7" class="no-border"></td>
                                </tr>
                                <tr class="sub_c" style="display: table-row;">
                                    <td colspan="2" class="no-border"></td>
                                    <td align="right" colspan="7" class="no-border">                            
                                        
                                        <input type="hidden" name="billtype" id="billtype" value="productsinwarehouse">            
                                        <input type="submit" id="submit-btn" class="btn btn-crud btn-primary btn-lg margin-bottom" value="<?php echo $this->lang->line('Stock Transfer') ?>"   data-loading-text="Adding...">
                                        <input type="hidden" value="products/stock_transfer" id="action-url">
                                    </td>
                                </tr>


                                </tbody>
                            </table>
                        </div>
                    <!-- ====================================================================  -->

                    <div class="form-group row">

                        <label class="col-sm-2 col-form-label"></label>

                        <div class="col-sm-4">
                            
                        </div>
                    </div>
            </div>

            </form>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            $("#stock-transfer-addrow").hide();
        });
        // $("#products-0").select2();
        $("#wfrom").on('change', function () {
            $(".product_requests").val("");
            $(".products_units").val("");
            $(".onhand_qty").val("");
            $(".product_qty").val("");
            var tips = $('#wfrom').val();
            var selectedValue = $(this).val();
            $(".products_units").val("");
            if(tips!=""){
                $("#stock-transfer-addrow").show();
            }
            else{
                $("#stock-transfer-addrow").hide();
            }
            // Hide the selected option from wto
            if (selectedValue != '') {
                $('#wto option').show();
                $('#wto option[value="' + selectedValue + '"]').hide();
            }
            // If the current selection in wto is the same as wfrom, reset wto to the first available option
            if ($('#wto').val() == selectedValue) {
                $('#wto').val($('#wto option:visible:first').val());
            }
            // Reset and reinitialize Select2 for products-0
            $(".productselect").val(null).trigger('change'); // Reset Select2
            $(".productselect").select2({
                placeholder: "Select a product",  // Add or update placeholder
                tags: [],
                ajax: {
                    url: baseurl + 'products/stock_transfer_products',
                    dataType: 'json',
                    type: 'POST',
                    quietMillis: 50,
                    data: function (product) {
                        return {
                            product: product,
                            wid: tips,
                            '<?=$this->security->get_csrf_token_name()?>': crsf_hash
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                var itemtext = item.product_name + ' (Stock: ' + item.stock_qty + ')';
                                $('#products_unit_0').val(item.unit);
                                return {
                                    text: itemtext,
                                    id: item.pid,
                                    unit: item.unit,
                                    'data-id': item.pid
                                };
                            })
                        };
                    },
                }
            });
        });

        // Event listener for select2:select to update unit field
        // $('#products-0').on('select2:select', function (e) {
        //     var selectedData = e.params.data;
        //     $('#products_unit_0').val(selectedData.unit);
        // });


        $('#stock-transfer-addrow').on('click', function () {
            var fromwarehouse = $('#wfrom').val();
            var cvalue = parseInt($('#ganak').val()) + 1;
            var nxt = parseInt(cvalue);
            $('#ganak').val(nxt);
            var functionNum = "'" + cvalue + "'";
            count = $('#saman-row div').length;
            

            var data = '<tr><td width="30%"><input type="text" id="productrequest-'+cvalue+'" name="products_l[]" class="form-control product_requests" placeholder="Search by Item Desc or Item No."><input type="hidden" id="products_id_'+cvalue+'" name="products_id[]" class="form-control product_requests" ></td><td><input name="products_unit[]" id="products_unit_'+cvalue+'" class="form-control products_units responsive-width-elements" type="text" readonly></td><td><input name="onhand[]" id="onhand-'+cvalue+'" class="form-control required onhand_qty" type="text" readonly></td><td><input name="products_qty[]" id="products_qty_'+cvalue+'" class="form-control" type="number"></td><td class="text-center"><button type="button" data-rowid="' + cvalue + '" class="btn btn-sm btn-default removeProd" title="Remove" > <i class="fa fa-trash"></i> </button> </td></tr>';
            
            $('tr.last-item-row').before(data);
            $('#productrequest-'+cvalue).autocomplete({
                    source: function (request, response) {
                    $('#productrequest-'+cvalue).removeAttr('data-id');
                    var warehouse = $("#wfrom option:selected").val();
                    if($('#productrequest-'+cvalue).val() !="" && warehouse!="")
                    {
                        $.ajax({
                            url: baseurl + 'search_products/' + billtype,
                            dataType: "json",
                            method: 'post',
                            data: 'name_startsWith=' + request.term + '&type=product_list&row_num=1&wid=' + $("#wfrom option:selected").val() + '&' + d_csrf,
                            success: function (data) {  
                                console.log(data);             
                                response($.map(data, function (item) {
                                    var product_d = item[0]+"("+ item[2] +")";
                                    return {
                                        label: product_d,
                                        value: product_d,
                                        data: item,
                                        product_id: item[2]
                                    };
                                }));
                            }
                        });
                    }
                },
                autoFocus: true,
                minLength: 0,
                select: function (event, ui) {
                    var prid = ui.item.data[1];
                    var unit = ui.item.data[3];
                    var onhand = ui.item.data[4];
                    $('#productrequest-'+cvalue).attr('data-id', prid);
                    $('#onhand-'+cvalue).val(onhand);
                    $('#products_unit_'+cvalue).val(unit);
                    $('#products_id_'+cvalue).val(prid);
                }
            });

            row = cvalue;
        });

    $("#data_form").validate($.extend(true, {}, globalValidationOptions,{
        ignore: [],
        rules: {      
            from_warehouse: { required: true },
            to_warehouse: { required: true },
        },
        messages: {
            from_warehouse: "Select Transfer From",  
            to_warehouse: "Select Transfer To",  
        }
    }));

    $("#submit-btn").on("click", function (e) {
        e.preventDefault();
        $('#submit-btn').prop('disabled', true);
        if ($("#data_form").valid()) {
            let hasProductId = false;
            $('input[name="products_l[]"]').each(function () {
                if ($(this).val()) {
                    hasProductId = true;
                    return false; // Exit loop early
                }
            });

            if (!hasProductId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Missing Product',
                    text: 'Please select at least one product before submitting.',
                });
                $('#submit-btn').prop('disabled', false);
                return; // Stop further execution
            }
            var formData = new FormData($("#data_form")[0]);
            Swal.fire({
               title: "Are you sure?",
               text: "Do you want to create a new stock transfer?",
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
                        url: baseurl + 'products/stock_transfer',
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

