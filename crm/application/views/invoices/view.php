<div class="app-content content container-fluid">
    <div class="card card-block">
        <div class="content-header row">
        </div>
        <div class="content-body">
            <?php if ($this->session->flashdata("messagePr")) { ?>
                <div class="alert alert-info">
                    <?php echo $this->session->flashdata("messagePr") ?>
                </div>
            <?php } 
            if ($invoice['csd'] != $this->session->userdata('user_details')[0]->cid) 
            {
                $msg = check_permission();
                echo $msg;
                return;
            }
            ?>

            <section class="card1">
                <div id="invoice-template" class="card-block1">
                    <div class="row wrapper white-bg page-heading">
                        
                        <div class="col-lg-12">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?= base_url('') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                                    <li class="breadcrumb-item"><a href="<?= base_url('invoices/invoices') ?>"><?php echo $this->lang->line('Invoices'); ?></a></li>
                                    <li class="breadcrumb-item active" aria-current="page"><?php echo $invoice['invoice_number']; ?></li>
                                </ol>
                            </nav> 

                            <div class="row">
                                <div class="col-lg-8">
                                    <h5><?php echo $invoice['invoice_number']; ?></h5>
                                </div>
                                <div class="col-lg-4 text-right">
                                    <?php
                                    $validtoken = hash_hmac('ripemd160', $invoice['iid'], $this->config->item('encryption_key'));

                                    $link = '../../billing/view?id=' . $invoice['iid'] . '&token=' . $validtoken;
                                    $linkp = '../../billing/printinvoice?id=' . $invoice['iid'] . '&token=' . $validtoken;
                                    if ($invoice['status'] != 'canceled') {
                                        echo ' <div class="title-action">';
                                    // echo '<a href="' . $link . '"  class="btn btn-sm btn-secondary" title="Partial Payment"><span class="fa fa-money"></span> '.$this->lang->line('Make Payment').' </a>';
                                        echo '<a href="' . $linkp . '"  class="btn btn-sm btn-secondary" target="_blank" title="Partial Payment"
                                        ><span class="fa fa-print"></span> '.$this->lang->line('Print').' </a>   </div>';
                                    } else {
                                        echo '<h2 class="btn btn-sm btn-danger">' . $this->lang->line('Cancelled') . '</h2>';
                                    } ?>
                                </div>
                            </div><hr>
                            </div>
                            
                            
                        </div>
                    </div>

                    <!-- Invoice Company Details -->
                    <div id="invoice-company-details" class="row">
                        <div class="col-md-2 col-sm-12 text-xs-center text-md-left">
                            <img src="../../userfiles/company/<?php echo $this->config->item('logo') ?>"
                                class="img-responsive p-1 m-b-2" style="max-height: 120px;">                          
                        </div>
                        <div class="col-md-4 col-sm-12 text-xs-center text-md-left">
                            <p class="pb-0 mb-0 text-muted"><?php echo $this->lang->line('From') ?></p> 
                             <p class="pb-0 mb-0 text-bold-800"> <?php echo $this->config->item('ctitle'); ?></p>
                             <p class="pb-0 mb-0"> 
                              <?php echo $this->config->item('address');
                              if($this->config->item('city')) { echo ", ".$this->config->item('city'); }
                              ?></p>
                             <p class="pb-0 mb-0"> <?php echo $this->config->item('phone'); ?></p>
                             <p class="pb-0 mb-0"> <?php echo $this->config->item('email'); ?></p>                           
                        </div>
                        <div class="col-md-3 col-sm-12 text-xs-center text-md-left">
                            <p class="pb-0 mb-0 text-muted"><?php echo $this->lang->line('Bill To') ?></p> 
                             <p class="pb-0 mb-0 text-bold-800"> <?php echo $invoice['name']; ?></p>
                             <p class="pb-0 mb-0"> 
                              <?php echo $invoice['address'];?></p>
                              <p class="pb-0 mb-0"> <?php 
                              if($invoice['city']) { echo $invoice['city'].", "; }
                              if($invoice['country']) { echo $invoice['country']; } ?></p>
                             <p class="pb-0 mb-0"> <?php echo $this->lang->line('Phone')." : " .$invoice['phone']; ?></p>
                             <p class="pb-0 mb-0"> <?php echo $this->lang->line('Email')." : " .$invoice['email']; ?></p>                           
                        </div>

                        <div class="col-md-3 col-sm-12 text-xs-center text-md-right">
                            <p class="pb-0 mb-0 text-bold-800"> <?php echo $invoice['invoice_number'] . '</p>
                            <p class="pb-0 mb-0">Reference : ' . $invoice['refer'] . '</p>'; ?>
                            <p class="pb-0 mb-0"> <?php echo $this->lang->line('Quote Date'); ?> : <span>  <?php echo dateformat($invoice['invoicedate']); ?></span></p>
                            <p class="pb-0 mb-0"> <?php echo $this->lang->line('Due Date'); ?> : <span>  <?php echo dateformat($invoice['invoiceduedate']); ?></span></p>
                            <p class="pb-0 mb-0"> <?php echo $this->lang->line('Currency'); ?> : <span class="text-bold-600">  <?php echo $this->config->item('currency'); ?></span></p>
                            <p class="pb-0 mb-0"> <?php echo $this->lang->line('Gross Amount'); ?> : <span class="lead text-bold-600">  <?php echo number_format($invoice['total'], 2) ?></span></p>                            
                        </div>
                    </div>
                    <!--/ Invoice Company Details -->

                   

                    <!-- Invoice Items Details -->
                    <div id="invoice-items-details" class="pt-2">
                        <div class="row">
                            <div class="table-responsive1 col-sm-12 table-scroll">
                                <table class="table table-striped table-bordered1 zero-configuration dataTable">
                                                            <thead>
                                    <?php if($invoice['taxstatus']=='cgst'){ ?>

                                             <tr>
                                        <th>#</th>
                                        
                                        <th class="text-xs-left"><?php echo $this->lang->line('Code') ?></th>
                                        <th><?php echo $this->lang->line('Item Name') ?></th>
                                        <th class="text-xs-left"><?php echo $this->lang->line('Rate') ?></th>
                                        <th class="text-xs-left"><?php echo $this->lang->line('Qty') ?></th>
                                         <th class="text-xs-left"><?php echo $this->lang->line('Discount') ?></th>
                                        <th class="text-xs-left"><?php echo $this->lang->line('CGST') ?></th>
                                        <th class="text-xs-left"><?php echo $this->lang->line('SGST') ?></th>
                                        <th class="text-xs-left"><?php echo $this->lang->line('Amount') ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $c = 1;
                                    $sub_t = 0;

                                    foreach ($products as $row) {
                                        $sub_t += $row['price'] * $row['qty'];
                                        $gst = $row['totaltax']/2;
                                        $rate=$row['tax']/2;
                                        echo '<tr>
                                                <td scope="row">' . $c . '</td>
                                                    <td>' . $row['product_code'] . '</td> 
                                                    <td>' . $row['product_name'] . '</td>                          
                                                    <td>' . amountFormat($row['price']) . '</td>
                                                    <td>' . +$row['qty'].$row['unit'] . '</td>
                                                    <td>' . amountFormat($row['totaldiscount']) . ' (' .amountFormat_s($row['discount']).$this->lang->line($invoice['format_discount']).')</td>
                                                    <td>' . amountFormat($gst) . ' (' . amountFormat_s($rate) . '%)</td>
                                                    <td>' . amountFormat($gst) . ' (' . amountFormat_s($rate) . '%)</td>                           
                                                    <td>' . amountFormat($row['subtotal']) . '</td>
                                                </tr>';
                                        $c++;
                                    } ?>

                                    </tbody>
                                    <?php

                                    } elseif($invoice['taxstatus']=='igst'){
                                        ?>
                                    <tr>
                                        <th>#</th>
                                        <th class="text-xs-left"><?php echo $this->lang->line('Code') ?></th>
                                        <th><?php echo $this->lang->line('Description') ?></th>
                                        <th class="text-xs-left"><?php echo $this->lang->line('Rate') ?></th>
                                        <th class="text-xs-left"><?php echo $this->lang->line('Qty') ?></th>
                                         <th class="text-xs-left"><?php echo $this->lang->line('Discount') ?></th>
                                        <th class="text-xs-left"><?php echo $this->lang->line('IGST') ?></th>
                                        <th class="text-xs-left"><?php echo $this->lang->line('Amount') ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $c = 1;
                                    $sub_t = 0;

                                    foreach ($products as $row) {
                                        $sub_t += $row['price'] * $row['qty'];

                                        echo '<tr>
                                            <td scope="row">' . $c . '</td>
                                            <td>' . $row['product'] . '</td> 
                                            <td>' . $row['code'] . '</td>                          
                                            <td>' . amountFormat($row['price']) . '</td>
                                            <td>' . +$row['qty'].$row['unit'] . '</td>
                                            <td>' . amountFormat($row['totaldiscount']) . ' (' .amountFormat_s($row['discount']).$this->lang->line($invoice['format_discount']).')</td>
                                            <td>' . amountFormat($row['totaltax']) . ' (' . amountFormat_s($row['tax']) . '%)</td>
                                                            
                                            <td>' . amountFormat($row['subtotal']) . '</td>
                                        </tr>'; 

                                        $c++;
                                    } ?>

                                    </tbody>
                                        <?php
                                    }
                                    else {
                                    ?>
                                    <tr>
                                        <th>No.</th>
                                        <th><?php echo $this->lang->line('Code') ?></th>
                                        <th><?php echo $this->lang->line('Item Name') ?></th>
                                        <th class="text-xs-right"><?php echo $this->lang->line('Rate') ?></th>
                                        <th class="text-xs-center"><?php echo $this->lang->line('Qty') ?></th>
                                        <th class="text-xs-right"><?php echo $this->lang->line('Discount') ?></th>
                                        <th class="text-xs-right"><?php echo $this->lang->line('Amount') ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $c = 1;
                                    $sub_t = 0;

                                    foreach ($products as $row) {
                                        $sub_t += $row['price'] * $row['qty'];
                                        echo '<tr>
                                            <td scope="row">' . $c . '</td>
                                            <td>' . $row['product_code'].'</td>
                                            <td>' . $row['product_name'] . '</td>                           
                                            <td class="text-xs-right">' . number_format($row['price'],2) . '</td>
                                            <td class="text-xs-center">' . +$row['qty'].$row['unit'] . '</td>
                                            <td class="text-xs-right">' . number_format($row['totaldiscount'],2) . ' (' .amountFormat_s($row['discount']).$this->lang->line($invoice['format_discount']).')</td>
                                            <td class="text-xs-right">' . number_format($row['subtotal'],2) . '</td>
                                        </tr>';

                                        $c++;
                                    } ?>

                                    </tbody>
                                    <?php } ?>
                                </table>
                            </div>
                        </div>
                        <p></p>
                        <div class="row">
                            <div class="col-md-7 col-sm-12 text-xs-center text-md-left">


                                <div class="row">
                                    <div class="col-md-8"><p class="lead"><?php echo $this->lang->line('Payment Status') ?>: <u><strong
                                                        id="pstatus"><?php echo $this->lang->line(ucwords($invoice['invoice_status'])) ?></strong></u>
                                        </p>
                                        <p class="lead"><?php echo $this->lang->line('Payment Method') ?>: <u><strong
                                                        id="pmethod"><?php echo $this->lang->line($invoice['pmethod']) ?></strong></u>
                                        </p>

                                        <p class="lead mt-1"><br><?php echo $this->lang->line('Note') ?>:</p>
                                        <code>
                                            <?php echo $invoice['notes'] ?>
                                        </code>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5 col-sm-12">
                                <p class="lead"><?php echo $this->lang->line('Summary') ?></p>
                                <div class="table-responsive">
                                    <table class="table">
                                        <tbody>
                                        <tr class="tr-no-border">
                                            <td class="no-border"><?php echo $this->lang->line('Sub Total') ?></td>
                                            <td class="text-xs-right no-border"> <?php echo number_format($sub_t,2) ?></td>
                                        </tr>
                                        <tr class="d-none">
                                            <td><?php echo $this->lang->line('Tax') ?></td>
                                            <td class="text-xs-right"><?php echo number_format($invoice['tax'],2) ?></td>
                                        </tr>
                                         <tr class="tr-no-border">
                                            <td class="no-border"><?php echo $this->lang->line('Discount') ?></td>
                                            <td class="text-xs-right no-border"><?php echo number_format($invoice['discount']) ?></td>
                                        </tr>
                                        <tr class="tr-no-border">
                                            <td class="no-border"><?php echo $this->lang->line('Shipping') ?></td>
                                            <td class="no-border text-xs-right"><?php echo number_format($invoice['shipping'],2) ?></td>
                                        </tr>
                                        <tr class="tr-no-border">
                                            <td class="no-border text-bold-800"><?php echo $this->lang->line('Total') ?></td>
                                            <td class="no-border text-bold-800 text-xs-right"> <?php echo number_format($invoice['total'],2) ?></td>
                                        </tr>
                                        <tr class="tr-no-border">
                                            <td class="no-border"><?php echo $this->lang->line('Payment Made') ?></td>
                                            <td class="pink text-xs-right no-border">
                                                (-) <?php echo number_format($invoice['pamnt'],2) ?></span></td>
                                        </tr>
                                        <tr class="bg-lighten-4 tr-no-border">
                                            <td class="no-border text-bold-800"><?php echo $this->lang->line('Balance Due') ?></td>
                                            <td class="no-border text-bold-800 text-xs-right"> <?php $myp = '';
                                                $rming = $invoice['total'] - $invoice['pamnt'];
                                                if ($rming < 0) {
                                                    $rming = 0;

                                                }
                                                echo ' <span id="paydue">' . number_format($rming,2) . '</span></strong>'; ?></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="text-xs-right">
                                    <?php 
                                    if(!empty($employee['sign'])) { ?>
                                    <p><?php echo $this->lang->line('Authorized person') ?></p>
                                    <?php echo '<img src="../../userfiles/employee_sign/' . $employee['sign'] . '" alt="signature" class="height-100"/>
                                    <h6>(' . $employee['name'] . ')</h6>
                                    '; 
                                    }?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Invoice Footer -->

                    <div id="invoice-footer" class="d-none"><p class="lead"><?php echo $this->lang->line('Credit Transactions') ?>:</p>
                        <table class="table table-striped table-bordered zero-configuration dataTable">
                            <thead>
                            <tr>
                                <th><?php echo $this->lang->line('Date') ?></th>
                                <th><?php echo $this->lang->line('Method') ?></th>
                                <th><?php echo $this->lang->line('Amount') ?></th>
                                <th><?php echo $this->lang->line('Note') ?></th>


                            </tr>
                            </thead>
                            <tbody id="activity">
                            <?php foreach ($activity as $row) {

                                echo '<tr>
                            <td>' . $row['date'] . '</td>
                            <td>' . $this->lang->line($row['method']) . '</td>
                            <td>' . amountExchange($row['credit'],$invoice['multi'], $invoice['loc']) . '</td>
                            <td>' . $row['note'] . '</td>
                        </tr>';
                            } ?>

                            </tbody>
                        </table>

                        <div class="row d-none">

                            <div class="col-md-7 col-sm-12">

                                <h6><?php echo $this->lang->line('Terms & Condition') ?></h6>
                                <p> <?php

                                    echo '<strong>' . $invoice['termtit'] . '</strong><br>' . $invoice['terms'];
                                    ?></p>
                            </div>

                        </div>

                    </div>
                    <!--/ Invoice Footer -->
                    <hr>
                </div>
            </section>
        </div>
    </div>
</div>




