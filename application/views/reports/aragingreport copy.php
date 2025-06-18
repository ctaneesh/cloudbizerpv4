<div class="content-body">
    <div class="card">
        <div class="card-header border-bottom">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                    <li class="breadcrumb-item"><?php echo $this->lang->line('AR Aging Report'); ?></li>
                </ol>
                <!-- invoices/customer_leads?id=1 -->

                
            </nav>
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12">
                    <h4 class="card-title"><?php echo $this->lang->line('AR Aging Report'); ?></h4>
                </div>
                <div class="col-xl-9 col-lg-9 col-md-8 col-sm-12">
                <button class="btn btn-secondary btn-sm mt-1">
                    <i class="fa fa-calendar"></i> As of <strong><?=date('d-m-Y')?></strong>
                </button>
                    <button id="expand-all-btn" class="btn btn-secondary btn-sm mt-1"><i class="fa fa-angle-down"></i> Expand All</button>
                    <a href="<?php echo base_url(); ?>reports/aging_report" class="btn btn-secondary btn-sm mt-1" target="_blank">PDF</a>
                    <a href="" class="btn btn-secondary btn-sm mt-1" target="_blank">Excel</a>
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
                   

                    <!-- <div class="row">
                        <div class="col-6">
                            <label for="toAddInfo"
                                   class="col-form-label"><?php echo $this->lang->line('Proposal Message') ?></label>
                            <textarea class="summernote1 form-textarea" name="propos" id="contents" rows="2"></textarea>
                        </div>
                    </div> -->

                    <div id="saman-row">
                    <table class="agingtable table table-striped table-bordered zero-configuration dataTable">
                        <thead>
                            <tr class="item_header bg-gradient-directional-blue white">
                                <th width="22%" class="text-center1 pl-1"><?php echo $this->lang->line('Customer') ?></th>
                                <th width="5%" class="text-center"><?php echo $this->lang->line('Invoice Date') ?></th>
                                <th width="4%" class="text-center"><?php echo $this->lang->line('Amount') ?></th>
                                <th width="4%" class="text-center"><?php echo $this->lang->line('Currency') ?></th>
                                <th width="7%" class="text-center"><?php echo $this->lang->line('Account') ?></th>
                                <th width="7%" class="text-center"><?php echo $this->lang->line('Due Date') ?></th>
                                <th width="7%" class="text-center"><?php echo $this->lang->line('1-30 Days') ?></th>
                                <th width="7%" class="text-center"><?php echo $this->lang->line('31-60 Days') ?></th>
                                <th width="7%" class="text-center"><?php echo $this->lang->line('61-90 Days') ?></th>
                                <th width="7%" class="text-center"><?php echo $this->lang->line('90 Days') ?></th>
                                <!-- <th width="7%" class="text-center"><?php echo $this->lang->line('Total') ?></th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Customer 1 -->
                            <tr class="customermain">
                                <td colspan="10">
                                    <button class="btn btn-info btn-sm expand-btn" data-toggle="collapse" data-target="#customer-details-1">
                                        <i class="fa fa-angle-down"></i>
                                    </button>
                                    Customer Name 1
                                </td>
                            </tr>
                            <!-- Collapsible section for Customer 1's records -->
                            <tr id="customer-details-1" class="collapse">
                                <td><a href="<?php echo base_url(); ?>invoices/view?id=88">Invoice #1044</a></td>
                                <td class="text-center">2024-08-15</td>
                                <td class="text-center">$500</td>
                                <td class="text-center">$</td>
                                <td class="text-center">89988888</td>
                                <td class="text-center">2024-09-15</td>
                                <td class="text-center">$500</td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <!-- <td class="text-center">$500</td> -->
                            </tr>
                            <tr id="customer-details-1" class="collapse">
                                <td><a href="">Invoice 1-2</a></td>
                                <td class="text-center">2024-08-01</td>
                                <td class="text-center">$300</td>
                                <td class="text-center">$</td>
                                <td class="text-center">89988888</td>
                                <td class="text-center">2024-08-20</td>
                                <td class="text-center"></td>
                                <td class="text-center">$300</td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <!-- <td class="text-center">$300</td> -->
                            </tr>
                            <tr id="customer-details-1" class="collapse">
                                <td><strong>Total</strong></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"><strong>$500</strong></td>
                                <td class="text-center"><strong>$300</strong></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <!-- <td class="text-center">$300</td> -->
                            </tr>
                            <!-- Customer 2 -->
                            <tr class="customermain">
                                <td colspan="10">
                                    <button class="btn btn-info btn-sm expand-btn" data-toggle="collapse" data-target="#customer-details-2">
                                        <i class="fa fa-angle-down"></i>
                                    </button>
                                    Customer Name 2
                                </td>
                            </tr>
                            <!-- Collapsible section for Customer 2's records -->
                            <tr id="customer-details-2" class="collapse">
                                <td><a href="">Invoice 2-1</a></td>
                                <td class="text-center">2024-07-18</td>
                                <td class="text-center">$400</td>
                                <td class="text-center">$</td>
                                <td class="text-center">89988888</td>
                                <td class="text-center">2024-07-18</td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center">$400</td>
                                <td class="text-center"></td>
                                <!-- <td class="text-center">$650</td> -->
                            </tr>
                            <tr id="customer-details-2" class="collapse">
                                <td><a href="">Invoice 2-2</a></td>
                                <td class="text-center">2024-05-01</td>
                                <td class="text-center">$350</td>
                                <td class="text-center">$</td>
                                <td class="text-center">89988888</td>
                                <td class="text-center">2024-05-22</td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center">$350</td>
                                <!-- <td class="text-center">$520</td> -->
                                
                            </tr>
                            <tr id="customer-details-2" class="collapse">
                                <td><strong>Total</strong></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>                                
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"><strong>$400</strong></td>
                                <td class="text-center"><strong>$350</strong></td>
                                <!-- <td class="text-center">$300</td> -->
                            </tr>
                            <!-- Repeat for more customers as needed -->
                        </tbody>
                    </table>

                    </div>

                </form>
            </div>

        </div>
    </div>
</div>



<script>
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


</script>