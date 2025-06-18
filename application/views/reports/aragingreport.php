<div class="content-body">
    <div class="card">
        <div class="card-header border-bottom">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                    <li class="breadcrumb-item"><?php echo $this->lang->line('Aged Receivables'); ?></li>
                </ol>
            </nav>
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12">
                    <h4 class="card-title"><?php echo $this->lang->line('Aged Receivables'); ?></h4>
                    <p>As of the Date - <b><?=date('d-M-Y')?></b><br>Amount customers owe the company</p>
                </div>
                <div class="col-xl-9 col-lg-9 col-md-8 col-sm-12">
                <button class="btn btn-secondary btn-sm mt-1">
                    <i class="fa fa-calendar"></i> As of <strong><?=date('d-m-Y')?></strong>
                </button>
                    <button id="expand-all-items-btn" class="btn btn-secondary btn-sm mt-1"><i class="fa fa-angle-down"></i> Expand All</button>
                    <!-- <a href="<?php echo base_url(); ?>reports/aging_report" class="btn btn-secondary btn-sm mt-1" target="_blank">PDF</a> -->
                    <a href="<?php echo base_url(); ?>reports/aging_report_pdf" class="btn btn-secondary btn-sm mt-1 btn-crud" target="_blank">PDF</a>
                    <a href="<?php echo base_url(); ?>reports/export_to_excel" class="btn btn-secondary btn-sm mt-1 btn-crud" target="_blank">Excel</a>
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
                <table class="agingtable table table-striped table-bordered zero-configuration dataTable">
                        <thead>
                            <tr class="item_header bg-gradient-directional-blue white">
                                <th width="22%" class="text-center1 pl-1"><?php echo $this->lang->line('Company') ?></th>
                                <th width="5%" class="text-center"><?php echo $this->lang->line('Invoice Date') ?></th>
                                <th width="7%" class="text-center"><?php echo $this->lang->line('Due Date') ?></th>
                                <th width="4%" class="text-right"><?php echo $this->lang->line('Amount') ?></th>
                                <th width="4%" class="text-right"><?php echo $this->lang->line('Paid') ?></th>
                                <th width="4%" class="text-center"><?php echo $this->lang->line('Currency') ?></th>
                                <th width="7%" class="text-right"><?php echo $this->lang->line('Account') ?></th>
                                <th width="7%" class="text-right"><?php echo $this->lang->line('1-30 Days') ?></th>
                                <th width="7%" class="text-right"><?php echo $this->lang->line('31-60 Days') ?></th>
                                <th width="7%" class="text-right"><?php echo $this->lang->line('61-90 Days') ?></th>
                                <th width="7%" class="text-right"><?php echo $this->lang->line('90 Days') ?></th>
                                <th width="7%" class="text-right"><?php echo $this->lang->line('Total') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Customer List -->
                            <?php
                            $report_total = 0;
                            foreach ($customer_list as $row) {
                                $customerId = $row['id'];
                                ?>
                                <tr class="customermain">
                                    <td colspan="11">
                                        <button class="btn btn-info btn-sm expand-btn1" data-toggle="collapseitems" data-target=".<?=$customerId?>">
                                            <i class="fa fa-angle-down"></i>
                                        </button>
                                        <?php echo $row['company']; ?>
                                    </td>
                                </tr>
                                <div class="collapseitems" id="<?=$customerId?>">
                                    <?php
                                    $days30_total = 0;
                                    $days60_total = 0;
                                    $days90_total = 0;
                                    $days90plus_total = 0;
                                    $colwise_total = 0;
                                    $txtcls1 = "";
                                    foreach ($paymentlist as $key => $list) {
                                        if (isset($paymentlist[$key][$customerId])) {
                                            $grand = 0;
                                            $invoiceids = $list[$customerId]['invoiceid'];
                                            $invoicetid = $list[$customerId]['invoice_number'];
                                            $invoice_date = !empty($list[$customerId]['invoice_date']) ? date('d-m-Y', strtotime($list[$customerId]['invoice_date'])) : $list[$customerId]['invoice_date'];
                                            $invoice_due_date = !empty($list[$customerId]['invoice_due_date']) ? date('d-m-Y', strtotime($list[$customerId]['invoice_due_date'])) : $list[$customerId]['invoice_due_date'];
                                            $subtotal = $list[$customerId]['subtotal'];
                                            $payment_recieved_amount = $list[$customerId]['payment_recieved_amount'];
                                            $days30 = ($list[$customerId]['30days'] > 0) ? $list[$customerId]['30days'] : 0.00;
                                            $days60 = ($list[$customerId]['60days'] > 0) ? $list[$customerId]['60days'] : 0.00;
                                            $days90 = ($list[$customerId]['90days'] > 0) ? $list[$customerId]['90days'] : 0.00;
                                            $days90plus = ($list[$customerId]['90plus'] > 0) ? $list[$customerId]['90plus'] : 0.00;
                                            $status = $list[$customerId]['status'];
                                            if(($status== 'due' || $status==  'partial') && $days30>=1)
                                            {
                                                $txtcls1 = "text-danger";
                                                $days30 =  $days30 * -1;
                                               
                                            }
                                            else{
                                                $txtcls1 = "";
                                            }
                                            if(($status== 'due' || $status==  'partial') && $days60>=1)
                                            {
                                                $txtclsdays60 = "text-danger";
                                                $days60 =  $days60 * -1;
                                            }
                                            else{
                                                $txtclsdays60 = "";
                                            }
                                            if(($status== 'due' || $status==  'partial') && $days90>=1)
                                            {
                                                $txtclsdays90 = "text-danger";
                                                $days90 =  $days90 * -1;
                                            }
                                            else{
                                                $txtclsdays90 = "";
                                            }
                                            if(($status== 'due' || $status==  'partial') && $days90plus>=1)
                                            {
                                                $txtclsdays90plus = "text-danger";
                                                $days90plus =  $days90plus * -1;
                                            }
                                            else{
                                                $txtclsdays90plus = "";
                                            }
                                            $days30_total = $days30_total + $days30;
                                            $days60_total = $days60_total +  $days60;
                                            $days90_total = $days90_total +  $days90;
                                            $days90plus_total = $days90plus_total + $days90plus;
                                            $grand = $days30 + $days60 + $days90 + $days90plus;
                                            $colwise_total = $grand + $colwise_total;
                                            
                                            if($grand<1)
                                            {
                                                $txtclsgrand = "text-danger";
                                            }
                                            else{
                                                $txtclsgrand = "";
                                            }
                                            ?>
                                            <tr class="<?=$customerId?> collapseitems">
                                                <td><a href="<?php echo base_url(); ?>invoices/view?id=<?=$invoiceids?>"><?=$invoicetid?></a></td>
                                                <td class="text-center"><?=$invoice_date?></td>
                                                <td class="text-center"><?=$invoice_due_date?></td>
                                                <td class="text-right <?=$txtcls?>"><?=number_format($subtotal,2)?></td>
                                                <td class="text-right <?=$txtcls?>"><?=number_format($payment_recieved_amount,2)?></td>
                                                <td class="text-center"><?=$config_currency?></td>
                                                <td class="text-center"></td>
                                                <td class="text-right <?=$txtcls1?>"><?=number_format($days30,2)?></td>
                                                <td class="text-right <?=$txtclsdays60?>"><?=number_format($days60,2)?></td>
                                                <td class="text-right <?=$txtclsdays90?>"><?=number_format($days90,2)?></td>
                                                <td class="text-right <?=$txtclsdays90plus?>"><?=number_format($days90plus,2)?></td>
                                                <td class="text-right <?=$txtclsgrand?>"><?=number_format($grand,2)?></td>
                                            </tr>
                                            
                                            <?php
                                        }
                                        
                                    }
                                    if($colwise_total<1)
                                    {
                                        $txtclscolwise = "text-danger";
                                    }
                                    else{
                                        $txtclscolwise = "";
                                    }
                                    if($days30_total<1)
                                    {
                                        $txtclsdays30_total = "text-danger";
                                    }
                                    else{
                                        $txtclsdays30_total = "";
                                    }
                                    if($colwise_total<1)
                                    {
                                        $txtclscolwise = "text-danger";
                                    }
                                    else{
                                        $txtclscolwise = "";
                                    }
                                    if($days60_total<1)
                                    {
                                        $txtclsdays60_total = "text-danger";
                                    }
                                    else{
                                        $txtclsdays60_total = "";
                                    }
                                    if($days90_total<1)
                                    {
                                        $txtclsdays90_total = "text-danger";
                                    }
                                    else{
                                        $txtclsdays90_total = "";
                                    }
                                    if($days90plus_total<1)
                                    {
                                        $txtclsdays90plus_total = "text-danger";
                                    }
                                    else{
                                        $txtclsdays90plus_total = "";
                                    }
                                    ?>
                                        <tr class="<?=$customerId?> collapseitems">
                                            <td><strong>Total</strong></td>
                                            <td class="text-center"></td>
                                            <td class="text-center"></td>
                                            <td class="text-center"></td>
                                            <td class="text-center"></td>
                                            <td class="text-center"></td>
                                            <td class="text-center"></td>
                                            <td class="text-right <?=$txtclsdays30_total?>"><strong><?=number_format($days30_total,2)?></strong></td>
                                            <td class="text-right <?=$txtclsdays60_total?>"><strong><?=number_format($days60_total,2)?></strong></td>
                                            <td class="text-right <?=$txtclsdays90_total?>"><strong><?=number_format($days90_total,2)?></strong></td>
                                            <td class="text-right <?=$txtclsdays90plus_total?>"><strong><?=number_format($days90plus_total,2)?></strong></td>
                                            <td class="text-right <?=$txtclscolwise?>"><strong><?php echo number_format($colwise_total,2); ?></strong></td>
                                        </tr>
                                </div>
                                <?php
                               
                                $report_total = $colwise_total + $report_total;
                               
                            }
                            ?>
                        </tbody>

                    </table>
                    <div class="text-right mt-2">
                        <?php
                             if($report_total<1)
                             {
                                $report_total =  $report_total * -1;
                             }
                        ?>
                       <strong> Grand Total = <?=number_format($report_total,2)?></strong>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>



<script>
$(document).ready(function() {
    $('#expand-all-items-btn').click();
});
$(document).on('click', '.expand-btn1', function(e) {
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
$('#expand-all-items-btn').on('click', function() {
    var $this = $(this);
    var isExpanded = $this.find('i').hasClass('fa-angle-down');

    if (isExpanded) {
        // Expand all
        $('.collapseitems').collapse('show');
        $this.find('i').removeClass('fa-angle-down').addClass('fa-angle-up');
        $this.text(' Collapse All').prepend('<i class="fa fa-angle-up"></i>'); // Update button text and icon
        // Set individual buttons to "Collapse"
        $('.expand-btn1 i').removeClass('fa-angle-down').addClass('fa-angle-up');
    } else {
        // Collapse all
        $('.collapseitems').collapse('hide');
        $this.find('i').removeClass('fa-angle-up').addClass('fa-angle-down');
        $this.text(' Expand All').prepend('<i class="fa fa-angle-down"></i>'); // Update button text and icon
        // Set individual buttons to "Expand"
        $('.expand-btn1 i').removeClass('fa-angle-up').addClass('fa-angle-down');
    }
});


</script>