<div class="content-body">
    <?php       
        // if (($msg = check_permission($permissions)) !== true) {
        //     echo $msg;
        //     return;
        // }       
    ?>
    <div class="card">
        <div class="card-header border-bottom">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                    <li class="breadcrumb-item"><?php echo $this->lang->line('Customer') . ' ' .$this->lang->line('sales') . ' ' . $this->lang->line('Report') ?></li>
                </ol>
            </nav>
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                    <h4 class="card-title"><?php echo $this->lang->line('Customer') . ' ' .$this->lang->line('sales') . ' ' . $this->lang->line('Report') ?></h4>
                </div>
              
            </div>
            <div class="row">
            <div class="col-12">
                        <div class="card sameheight-item">
                       
                        <form action="<?php echo base_url() ?>sales/CustomerSalesReport" method="post"
                        role="form">
                                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                                       value="<?php echo $this->security->get_csrf_hash(); ?>">
                                <div class="form-group row">
                                 
                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                        <select name="customer" class="form-control required" id="customer_statement" >
                                            <option value="<?php echo ($custdata['id']) ? $custdata['id'] : ''; ?>"><?php echo ($custdata['name']) ? $custdata['name'] : 'Select Customer'; ?></option>
                                            <?php 
                                            if($custdata['name']==''){
                                                ?>
                                                <option value="All">All</option>
                                                <?php
                                            }
                                            ?>
                                           
                                        </select>
                                       
                                    </div>
                                    <div class="col-xl-2 col-lg-4 col-md-4 col-sm-12 filterall">
                                        <?php 
                                            $s_check = "checked";
                                            $hide_detail = "";

                                            if($rmethod){
                                                if($rmethod == 'Details'){
                                                    // echo $rmethod;
                                                    $d_check = "checked";
                                                    $s_check = "";
                                                    $hide_detail = "";
                                                }
                                                else{
                                                    $s_check = "checked";
                                                    $d_check = "";
                                                    $hide_detail = "hide_row";

                                                }
                                            }
                                        ?>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="report_method" id="inlineRadio1" value="Summary" <?php echo $s_check; ?>>
                                            <label class="form-check-label" for="inlineRadio1">Summary</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="report_method" id="inlineRadio2" value="Details" <?php echo $d_check; ?>>
                                            <label class="form-check-label" for="inlineRadio2">Details</label>
                                        </div>
                                    </div>


                                    <!-- ($daterange) ? $daterange : ''; -->
                                    <div class="col-xl-2 col-lg-4 col-md-4 col-sm-12 filterall">
                                        <!-- <label for="filter_expiry" class='col-form-label'></label> -->
                                        <input type="text" id="daterange" placeholder="<?php echo $this->lang->line('Date Range') ?>" name="daterange" class="form-control filter_element" autocomplete="off" value="<?php echo ($daterange) ? $daterange : ''; ?>">
                                        <input type="hidden" name="filter_expiry_date_from" id="filter_expiry_date_from" class="form-control filter_element" value="<?php echo ($start_date) ? $start_date : ''; ?>">
                                        <input type="hidden" name="filter_expiry_date_to" id="filter_expiry_date_to" class="form-control filter_element" value="<?php echo ($end_date) ? $end_date : ''; ?>">
                                
                                    </div>
                                    <button class="btn btn-crud btn-primary" type="submit" names="filter_search_btn" id="filter_search_btn">Get</button>

                                </div>

                            </form>
                        </div>
                    </div>
            </div>
            <div class="row">
                
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                
                    <!-- <button id="expand-all-btn" class="btn btn-secondary btn-sm mt-1"><i class="fa fa-angle-down"></i> Expand All</button> -->                  
                    <a href="<?php echo base_url(); ?>sales/customer_sales_pdf" class="btn btn-secondary btn-sm mt-1" target="_blank"><?php echo ($rmethod) ? $rmethod.' as ' : ''; ?> PDF</a>
                    <a href="<?php echo base_url(); ?>sales/export_to_excel" class="btn btn-secondary btn-sm mt-1" target="_blank"><?php echo ($rmethod) ? $rmethod.' as ' : ''; ?>Excel</a>
                </div>
            </div>
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
                <form method="post" id="data_form">
                <table class="customersalestable table table-striped table-bordered zero-configuration dataTable" id="customersalestable">
                   
                        <thead>
                            <tr class="item_header bg-gradient-directional-blue white">
                                <th class="text-center1 pl-1"><?php echo $this->lang->line('Customer') ?></th>
                                <th class="text-center no-sort"><?php echo $this->lang->line('Sale Date') ?></th>

                                <th class="text-center no-sort"><?php echo $this->lang->line('Item ID') ?></th>
                                <th class="no-sort"><?php echo $this->lang->line('Item Description') ?></th>
                                <th class="text-center no-sort"><?php echo $this->lang->line('Quantity') ?> <?php echo $this->lang->line('sold') ?></th>
                                <th class="text-right no-sort"><?php echo $this->lang->line('Price') ?></th>
                                <th class="text-right no-sort"><?php echo $this->lang->line('Cost') ?></th>
                                <th class="text-right no-sort"><?php echo $this->lang->line('Total Price') ?></th>
                                <th class="text-right no-sort"><?php echo $this->lang->line('Total') ?> <?php echo $this->lang->line('Cost') ?></th>
                                <th class="text-right no-sort"><?php echo $this->lang->line('Profit') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Customer List -->
                            <?php
                            $report_total = 0;
                            $report_item_total = 0;
                            $report_sub_total = 0;
                            $report_cost_total = 0;
                            foreach ($customer_list as $row) {
                                $customerId = $row['customer_id'];
                                ?>
                                <tr class="customermain">
                                    <td colspan="11">
                                        <button class="btn btn-info btn-sm sale-expand-btn" data-toggle="collapse" data-target=".<?=$customerId?>">
                                            <i class="fa fa-angle-down"></i>
                                        </button>
                                        <?php echo $row['name']; ?>
                                    </td>
                                </tr>
                                <div class="collapse" id="<?=$customerId?>">
                                    <?php
                                    $item_total = 0;
                                    $sub_total_price = 0;
                                    $sub_total_cost = 0;

                                    $profit_total = 0;
                                  
                                    foreach ($custlist as $key => $list) {
                                        if (isset($custlist[$key][$customerId])) {
                                            $grand =0;

                                            $created_date = !empty($list[$customerId]['created_date']) ? date('d-m-Y', strtotime($list[$customerId]['created_date'])) : $list[$customerId]['created_date'];
                                         
                                            $product_code = $list[$customerId]['product_code'];
                                            $product_des = $list[$customerId]['product_des'];
                                            $product_qty = $list[$customerId]['product_qty'];
                                            $product_price = $list[$customerId]['product_price'];
                                            $cost = $list[$customerId]['cost'];
                                            $total_price = $list[$customerId]['total_price'];
                                            $total_cost = $list[$customerId]['total_cost'];
                                            $profit = $list[$customerId]['profit'];

                                            $profit_total = $profit_total + $profit;
                                            $item_total = $item_total +  $product_qty;
                                            $sub_total_price = $sub_total_price +  $product_price;
                                            $sub_total_cost = $sub_total_cost + $total_cost;
                                            
                                            if($product_qty<1){ $txtclsqty = "text-danger";   } else{ $txtclsqty = ""; }
                                            if($product_price<1){ $txtclsprice = "text-danger";   } else{ $txtclsprice = ""; }
                                            if($profit<1){ $txtclsprofit = "text-danger";   } else{ $txtclsprofit = ""; }
                                            
                                           
                                            ?>

                                            <tr class="<?=$customerId?> collapse <?=$hide_detail?>" >
                                                <td></td>            
                                                <td class="text-center"><?=$created_date?></td>
                                                <td class="text-center"><?=$product_code?></td>
                                                <td><?=$product_des?></td>  
                                                <td class="text-center <?=$txtclsqty?>"><?=$product_qty?></td>
                                                <td class="text-right <?=$txtclsprice?>"><?=$product_price?></td>
                                                <td class="text-right <?=$txtclsprice?>"><?=$cost?></td>
                                                <td class="text-right <?=$txtclsprice?>"><?=$total_price?></td>
                                                <td class="text-right <?=$txtclsprice?>"><?=$total_cost?></td>
                                               
                                                <td class="text-right <?=$txtclsprofit?>"><?=$profit?></td>
                                                
                                               
                                            </tr>
                                            
                                            <?php
                                        }
                                        
                                    }
                                   
                                    
                                    ?>
                                   
                                        <tr class="<?=$customerId?> collapse">
                                            <td><strong>Total</strong></td>
                                            <td class="text-center"></td>
                                            <td class="text-center"></td>
                                            <td class="text-center"></td>
                                           
                                          
                                            <td class="text-center "><strong><?=number_format($item_total)?></strong></td>
                                            <td class="text-center"></td>
                                            <td class="text-center"></td>

                                            <td class="text-right "><strong> <?php echo number_format($sub_total_price,2); ?></strong></td>
                                            <td class="text-right "><strong> <?php echo number_format($sub_total_cost,2); ?></strong></td>
                                            <!-- $sub_total_cost -->
                                            <td class="text-right "><strong><?php echo number_format($profit_total,2); ?></strong></td>
                                        </tr>

                                        
                                </div>
                                <?php
                               
                                $report_total = $profit_total + $report_total;
                                $report_item_total = $item_total + $report_item_total;
                                $report_sub_total = $sub_total_price + $report_sub_total;
                                $report_cost_total = $sub_total_cost + $report_cost_total;
                               
                            }
                            
                             if($report_total<1)
                             {
                                $report_total =  $report_total * -1;
                             }
                      
                            ?>
                             <tr>
                                            <td class="text-center"></td>
                                            
                                            <td class="text-center"></td>
                                            <td class="text-center"></td>
                                            <td class="text-center"></td>
                                           
                                          
                                            <td class="text-center "><strong>Total Items : <?=number_format($report_item_total)?></strong></td>
                                            <td class="text-center"></td>
                                            <td class="text-center"></td>
                                            <td class="text-right "><strong>Sub Total Price : <?php echo number_format($report_sub_total,2); ?></strong></td>
                                            <td class="text-right "><strong>Sub Total Cost : <?php echo number_format($report_cost_total,2); ?></strong></td>
                                            <td class="text-right "><strong>Total Point : <?php echo number_format($report_total,2); ?></strong></td>
                                        </tr>
                        </tbody>

                    </table>
                   
                    <div class="text-right mt-2">
                        <?php
                            //  if($report_total<1)
                            //  {
                            //     $report_total =  $report_total * -1;
                            //  }
                        ?>
                       <!-- <strong> Grand Total = <? //=number_format($report_total,2)?></strong> -->
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>



<script>
     var columnlist = [
        { 'width': '5%' }, 
        { 'width': '12%' },
        { 'width': '8%' },
        { 'width': '15%' }, 
        { 'width': '10%' }, 
        { 'width': '12%' },
        { 'width': '15%' }, 
        { 'width': '10%' }, 
        { 'width': '12%' },
        { 'width': '10%' }
       
    ];
$(document).ready(function() {
    $('#expand-all-btn').click();

    


});
$("#customer_statement").select2({
        // minimumInputLength: 4,
        tags: [],
        ajax: {
            url: baseurl + 'search/customer_select',
            dataType: 'json',
            type: 'POST',
            quietMillis: 50,
            data: function (customer) {
                return {
                    customer: customer,
                    '<?=$this->security->get_csrf_token_name()?>': crsf_hash
                };
            },
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.name,
                            id: item.id
                        }
                    })
                };
            },
        }
    });
$(document).on('click', '.sale-expand-btn', function(e) {
    e.preventDefault();
    var $this = $(this);
    var target = $this.data('target');

    $(target).collapse('toggle'); // Toggle the visibility of the customer rows

    // Toggle between angle-down and angle-up icons
    if ($this.find('i').hasClass('fa-angle-down')) {
        $this.find('i').removeClass('fa-angle-down').addClass('fa-angle-up');
    } else {
        $this.find('i').removeClass('fa-angle-up').addClass('fa-angle-down');
    }
});

// Expand/Collapse all sections
$('#expand-all-btn').on('click', function() {
    var $this = $(this);
    var isExpanded = $this.find('i').hasClass('fa-angle-down');

    if (isExpanded) {
        // Expand all
        $('.collapse').collapse('show');
        $this.find('i').removeClass('fa-angle-down').addClass('fa-angle-up');
        $this.text(' Collapse All').prepend('<i class="fa fa-angle-up"></i>'); // Update button text and icon
        // Set individual buttons to "Collapse"
        $('.sale-expand-btn i').removeClass('fa-angle-down').addClass('fa-angle-up');
    } else {
        // Collapse all
        $('.collapse').collapse('hide');
        $this.find('i').removeClass('fa-angle-up').addClass('fa-angle-down');
        $this.text(' Expand All').prepend('<i class="fa fa-angle-down"></i>'); // Update button text and icon
        // Set individual buttons to "Expand"
        $('.sale-expand-btn i').removeClass('fa-angle-up').addClass('fa-angle-down');
    }
});

 // Set default start and end dates
 var startDate = moment().startOf('month'); // Start of the current month
        var endDate = moment().endOf('month'); // End of the current month

        $('#daterange').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear',
                format: 'DD-MM-YYYY'
            },
            opens: 'left', // Adjust the opening direction (left, right, etc.)
            alwaysShowCalendars: true,
            showDropdowns: true,
        });

        // Clear the input when the cancel button is clicked
        $('#daterange').on('cancel.daterangepicker', function(ev, picker){
            $(this).val('');
            $("#filter_expiry_date_from").val('');
            $("#filter_expiry_date_to").val('');
        });

        // Set the value of the textbox when the apply button is clicked
        $('#daterange').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
            $("#filter_expiry_date_from").val(picker.startDate.format('DD-MM-YYYY'));
            $("#filter_expiry_date_to").val(picker.endDate.format('DD-MM-YYYY'));
        });


</script>