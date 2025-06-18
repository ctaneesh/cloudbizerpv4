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
                    <li class="breadcrumb-item"><?php echo $this->lang->line('Sale Purchase Report')?></li>
                </ol>
            </nav>
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                    <h4 class="card-title"><?php echo $this->lang->line('Sale Purchase Report')?></h4>
                </div>
              
            </div> 
            <hr>
            <div class="row">
            <div class="col-12">
                        <div class="card1 sameheight-item">
                        <?php $oneMonthBefore = date('Y-m-d', strtotime('-1 month')); ?>
                        <form action="<?php echo base_url() ?>reports/sale_purchase_report" method="post" role="form">
                                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                                       value="<?php echo $this->security->get_csrf_hash(); ?>">
                                <div class="form-group row">
                                        <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label"
                                           for="sdate"><?php echo $this->lang->line('From Date') ?></label>
                                           <input type="date" name="filter_expiry_date_from" value="<?=$oneMonthBefore?>" class="form-control required">
                                        </div>
                                         <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label"
                                           for="edate"><?php echo $this->lang->line('To Date') ?></label>
                                          <input type="date" name="filter_expiry_date_to" value="<?=date('Y-m-d')?>" class="form-control required">
                                        </div>
                                    <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                    </br>
                                    <button class="btn btn-crud btn-primary mt-16px" type="submit" names="filter_search_btn" id="filter_search_btn">Get</button>
                                    </div>
                                </div>

                        </form>
                        </div>
                    </div>
            </div>
            <div class="row">
                
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                
                    <!-- <button id="expand-all-btn" class="btn btn-secondary btn-sm mt-1"><i class="fa fa-angle-down"></i> Expand All</button> -->                  
                    <a href="<?php echo base_url(); ?>reports/purchase_sales_pdf" class="btn btn-secondary btn-sm mt-1" target="_blank"> PDF</a>
                    <a href="<?php echo base_url(); ?>reports/export_to_excell" class="btn btn-secondary btn-sm mt-1" target="_blank">Excel</a>
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
                <form method="post" id="data_form" class="table-scroll">
                <table class="customersalestable table table-striped table-bordered zero-configuration dataTable" id="customersalestable">
                   
                        <thead>
                            <tr class="item_header bg-gradient-directional-blue white">
                                <th class="text-center1 pl-1"><?php echo $this->lang->line('Sl No') ?></th>
                                <th class="text-center no-sort"><?php echo $this->lang->line('Item Code') ?></th>
                                <th class="text-center no-sort"><?php echo $this->lang->line('Item Description') ?></th>
                                <th class="text-center no-sort"><?php echo $this->lang->line('Quantity') ?> <?php echo $this->lang->line('sold') ?></th>
                                <th class="text-center no-sort"><?php echo $this->lang->line('Cost') ?></th>
                                <th class="text-center no-sort"><?php echo $this->lang->line('Sale Date') ?></th>
                                <th class="text-center no-sort"><?php echo $this->lang->line('Purchase Date') ?></th>
                                <th class="text-center no-sort"><?php echo $this->lang->line('Quantity Purchased') ?></th>
                                <th class="text-right no-sort"><?php echo $this->lang->line('Onhand') ?></th>
                           
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Customer List -->
                            <?php
                            $report_total = 0;
                            $report_item_total = 0;
                            $report_sub_total = 0;
                            $report_cost_total = 0; ?>
                    
                                <div class="collapse" id="<?=$customerId?>">
                                    <?php
                                    $item_total = 0;
                                    $purchitem_total = 0;
                                    $sub_total_cost = 0;

                                     $i=1;

                                    foreach ($lists as $key => $list) {
                                       
                                            $grand =0;
                                            $created_date = !empty($list['created_date']) ? date('d-m-Y', strtotime($list['created_date'])) : $list['created_date'];
                                            $product_name = $list['product_name'];
                                            $product_des = $list['product_des'];
                                            $product_qty = $list['product_qty'];
                                            $purchase_date = $list['purchase_date'];
                                            $pid = $list['pro_id'];
                                            $cost = $list['cost'];
                                            $purchqty = $list['purchqty'];
                                            $total_cost = $list['cost'];
                                            $onhand = $list['onhand'];
                                            $item_total = $item_total +  $product_qty;
                                            $purchitem_total = $purchitem_total +  $purchqty;
                                            $sub_total_cost = $sub_total_cost + $total_cost;
                                            
                                            if($product_qty<1){ $txtclsqty = "text-danger";   } else{ $txtclsqty = ""; }
                                            if($product_price<1){ $txtclsprice = "text-danger";   } else{ $txtclsprice = ""; }
                                            if($profit<1){ $txtclsprofit = "text-danger";   } else{ $txtclsprofit = ""; }
                                            
                                           
                                            ?>

                                            <tr class="" >
                                                <td><?=$i?></td>            
                                                <td class="text-center sorting"><a href="<?= base_url('products/edit?id='.$pid); ?>"><?=$product_name?></a></td>
                                                <td><a href="<?= base_url('products/edit?id='.$pid); ?>"><?=$product_des?></td>  
                                                <td class="text-center <?=$txtclsqty?>"><?=$product_qty?></td>
                                                <td class="text-right <?=$txtclsprice?>"><?=$cost?></td>
                                                <td class="text-center"><?=$created_date?></td>
                                                <td class="text-center"><?=$purchase_date?></td>
                                                <td class="text-center"><?=$purchqty?></td>
                                                <td class="text-center"><?=$onhand?></td> 
                                        
                                            </tr>
                                            
                                            <?php $i++;
                                        }
                                    
                                    ?>
                                   
                                        <tr class="">
                                            <td><strong>Total</strong></td>
                                            <td class="text-center"></td>
                                            <td class="text-center"></td>
                                            <td class="text-center "><strong><?=number_format($item_total)?></strong></td>
                                            <td class="text-right "><strong> <?php echo number_format($sub_total_cost,2); ?></strong></td>
                                            <td class="text-center"></td>
                                            <td class="text-center"></td>
                                            <td class="text-center "><strong> <?php echo number_format($purchitem_total); ?></strong></td>
                                            
                                        </tr>

                                        
                                </div>
                                <?php
                               
                                $report_total = $profit_total + $report_total;
                                $report_item_total = $item_total + $report_item_total;
                                $report_cost_total = $sub_total_cost + $report_cost_total;
                                $report_purchitem_total = $purchitem_total + $report_purchitem_total;
                            
                             if($report_total<1)
                             {
                                $report_total =  $report_total * -1;
                             }
                      
                            ?>
                             <tr>
                                            <td class="text-center"></td>       
                                            <td class="text-center"></td>
                                            <td class="text-center"></td>  
                                            <td class="text-center responsive-width"><strong>Total Sold Items : <?=number_format($report_item_total)?></strong></td>            
                                            <td class="text-right responsive-width w-100"><strong>Sub Total Cost : <?php echo number_format($report_cost_total,2); ?></strong></td>
                                            <td class="text-center"></td>
                                            <td class="text-center"></td>              
                                            <td class="text-center responsive-width"><strong>Total Purchased Items : <?=number_format($report_purchitem_total)?></strong></td>
                                        </tr> 
                        </tbody>

                    </table>
                   
     
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
        { 'width': '8%' }, 
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
$(document).on('click', '.expand-btn', function(e) {
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
        $('.expand-btn i').removeClass('fa-angle-down').addClass('fa-angle-up');
    } else {
        // Collapse all
        $('.collapse').collapse('hide');
        $this.find('i').removeClass('fa-angle-up').addClass('fa-angle-down');
        $this.text(' Expand All').prepend('<i class="fa fa-angle-down"></i>'); // Update button text and icon
        // Set individual buttons to "Expand"
        $('.expand-btn i').removeClass('fa-angle-up').addClass('fa-angle-down');
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
