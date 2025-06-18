<div class="app-content content container-fluid">
    <div class="card card-block">
        <div id="notify" class="alert alert-success" style="display:none;">
            <a href="#" class="close" data-dismiss="alert">&times;</a>

            <div class="message"></div>
        </div>
        <?php

        if($this->session->flashdata('item')) {
        $message = $this->session->flashdata('item');
        ?>
                <div class="alert alert-info"><?php echo $message['message']; ?>

                </div>
                <?php
        }
        // if ($invoice['csd'] != $this->session->userdata('user_details')[0]->cid) 
        // {
        //     $msg = check_permission();
        //     echo $msg;
        //     return;
        // }
        ?>
        <div class="content-body">
            <section class="card1">
                <div id="invoice-template" class="card-block1">
                    <div class="row wrapper white-bg page-heading">

                        <div class="col-lg-12">
                            <?php
                            $validtoken = hash_hmac('ripemd160', 'q' . $invoice['iid'], $this->config->item('encryption_key'));
                            $link = '../../billing/printquote?id=' . $invoice['iid'] . '&token=' . $validtoken;
                            $linkp = '../../billing/print_rec?id=' . $invoice['iid'] . '&token=' . $validtoken;
                            ?>
                             <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?= base_url('') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                                    <li class="breadcrumb-item"><a href="<?= base_url('quote') ?>"><?php echo $this->lang->line('Received Quotes'); ?></a></li>
                                    <li class="breadcrumb-item active" aria-current="page"><?php echo $invoice['quote_number']; ?></li>
                                </ol>
                            </nav> 
                            <div class="row">
                                <div class="col-lg-8">
                                    <h5><?php echo $invoice['quote_number']; ?></h5>
                                </div>
                                <div class="col-lg-4 text-right">
                                    <div class="title-action"> 
                                        <div class="btn-group ">
                                            <button type="button"
                                                class="btn btn-sm btn-secondary btn-min-width dropdown-toggle"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                                    class="icon-print"></i> <?php echo $this->lang->line('Print Quote') ?>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" target="_blank" href="<?php echo $link ?>"><?php echo $this->lang->line('Print') ?></a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" href="<?php echo $link ?>&d=1"><?php echo $this->lang->line('PDF Download') ?></a>
                                            </div>
                                            <?php
                                            // if($invoice['status']=='pending') {
                                            ?>
                                            <a class="btn btn-sm btn-info round ml-1 d-none"
                                                href="<?php echo base_url('quote/approve') ?>?&id=<?=$invoice['iid'] ?>"
                                                title="Approve"><i class="icon-check"></i> Approve</a>
                                            <a class="btn btn-sm btn-warning round ml-1  d-none"
                                                href="<?php echo base_url('quote/editquote') ?>?&id=<?=$invoice['iid'] ?>"
                                                title="Approve"><i class="icon-book"></i> Update Quote</a>
                                            <?php
                                            // }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>


                            
                        </div>
                    </div>

                    <!-- Invoice Company Details -->
                    <div id="invoice-company-details" class="row mt-2">
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
                            <p class="pb-0 mb-0 text-bold-800"> <?php echo $invoice['quote_number'] . '</p>
                            <p class="pb-0 mb-0">Reference : ' . $invoice['refer'] . '</p>'; ?>
                            <p class="pb-0 mb-0"> <?php echo $this->lang->line('Quote Date'); ?> : <span>  <?php echo dateformat($invoice['invoicedate']); ?></span></p>
                            <p class="pb-0 mb-0"> <?php echo $this->lang->line('Due Date'); ?> : <span>  <?php echo dateformat($invoice['invoiceduedate']); ?></span></p>
                            <p class="pb-0 mb-0"> <?php echo $this->lang->line('Currency'); ?> : <span class="text-bold-600">  <?php echo $this->config->item('currency'); ?></span></p>
                            <p class="pb-0 mb-0"> <?php echo $this->lang->line('Gross Amount'); ?> : <span class="lead text-bold-600">  <?php echo number_format($invoice['total'], 2) ?></span></p>                            
                        </div>
                    </div>
                    <!--/ Invoice Company Details -->

                   
                    <!--/ Invoice Customer Details -->
                    <?php if ($invoice['proposal'] != '') {
                        echo '<div id="invoice-customer-details" class="row pt-2">
                        <div class="col-sm-12 text-xs-center text-md-left">';
                        echo '<h5>' . $this->lang->line('Proposal') . '</h5>';
                        echo '<p>' . $invoice['proposal'] . '</p>';


                        echo '   </div></div>';
                    } ?>
                    <!-- Invoice Items Details -->
                    <div id="invoice-items-details" class="pt-2">
                        <div class="row">
                            <div class="col-sm-12 table-scroll">
                                <table class="table table-striped table-bordered zero-configuration dataTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th><?php echo $this->lang->line('Code') ?></th>
                                            <th><?php echo $this->lang->line('Item Name') ?></th>
                                            <th class="text-xs-right text-md-right">
                                                <?php echo $this->lang->line('Rate') ?></th>
                                            <th class="text-xs-center text-md-center">
                                                <?php echo $this->lang->line('Qty') ?></th>
                                            <!-- <th class="text-xs-left"><?php echo $this->lang->line('Tax') ?></th> -->
                                            <th class="text-xs-right text-md-right">
                                                <?php echo $this->lang->line('Discount') ?></th>
                                            <th class="text-xs-right text-md-right">
                                                <?php echo $this->lang->line('Amount') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $c = 1;
                                    $sub_t=0;
                                    foreach ($products as $row) {
                                        $sub_t += $row['price'] * $row['qty'];
                                        echo '<tr>
                                            <th scope="row">' . $c . '</th>
                                                <td>' . $row['product_code'] . '</td>
                                                <td style="display: block;width:max-content;">' . $row['product_name'] . '</td>                                            
                                                <td class="text-xs-right text-md-right">' . number_format($row['price'], 2) . '</td>
                                                <td class="text-xs-center text-md-center">' . $row['qty'] . '</td>';

                                               // echo '<td>' . number_format($row['totaltax'], 2) . ' (' . amountFormat_s($row['tax']) . '%)</td>';

                                                echo '<td class="text-xs-right text-md-right">' . number_format($row['totaldiscount'], 2).'</td>
                                                <td class="text-xs-right text-md-right">' . number_format($row['subtotal'], 2) . '</td>
                                            </tr>';
                                        $c++;
                                    } ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <p></p>
                        <div class="row">
                            <div class="col-md-8 col-sm-12 text-xs-center text-md-left">


                                <div class="row">
                                    <div class="col-md-8">

                                        <!-- <p class="lead"><?php echo $this->lang->line('Quote Status') ?>: <u><strong
                                                        id="pstatus"><?php echo $this->lang->line(ucwords($invoice['status'])) ?></strong></u>
                                        </p> -->
                                        <p class="lead mt-1"><br><?php echo $this->lang->line('Note') ?>:</p>
                                        <code>
                                            <?php echo $invoice['notes'] ?>
                                        </code>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <p class="lead"><?php echo $this->lang->line('Summary') ?></p>
                                <div class="table-responsive">
                                    <table class="table">
                                        <tbody>
                                            <tr class="tr-no-border">
                                                <td class="no-border"><?php echo $this->lang->line('Sub Total') ?></td>
                                                <td class="text-xs-right no-border"> <?php echo number_format($sub_t, 2) ?></td>
                                            </tr>
                                            <!-- <tr >
                                            <td><?php echo $this->lang->line('TAX') ?></td>
                                            <td class="text-xs-right"><?php echo number_format($invoice['tax'], 2) ?></td>
                                        </tr> -->
                                            <tr class="tr-no-border">
                                                <td class="no-border"><?php echo $this->lang->line('Discount') ?></td>
                                                <td class="text-xs-right no-border">
                                                    <?php echo number_format($invoice['discount'], 2) ?></td>
                                            </tr>
                                            <tr class="tr-no-border">
                                                <td class="no-border"><?php echo $this->lang->line('Shipping') ?></td>
                                                <td class="text-xs-right no-border">
                                                    <?php echo number_format($invoice['shipping'], 2) ?></td>
                                            </tr>


                                            <tr class="bg-lighten-4 tr-no-border">
                                                <td class="text-bold-800 no-border"><?php echo $this->lang->line('Total') ?></td>
                                                <td class="text-bold-800 text-xs-right no-border">
                                                    <?php

                                                echo ' <span id="paydue">' . number_format($invoice['total'], 2) . '</span></strong>'; ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="text-xs-right">
                                    <p><?php 
                                    echo $this->lang->line('Authorized person') ?></p>
                                    <?php echo '<img src="../../userfiles/employee_sign/' . $employee['sign']. '" alt="signature" class="height-100"/>
                                    <h6>(' . $employee['name'] . ')</h6>
                                    <p class="text-muted">' . user_role($employee['roleid']) . '</p>'; ?>
                                    <img src="" alt="">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Invoice Footer -->

                    <div id="invoice-footer" class="d-none">


                        <div class="row">

                            <div class="col-md-7 col-sm-12">

                                <h6><?php echo $this->lang->line('Terms & Condition') ?></h6>
                                <p> <?php

                                    echo '<strong>' . $invoice['termtit'] . '</strong><br>' . $invoice['terms'];
                                    ?></p>
                            </div>

                        </div>

                    </div>
                    <!--/ Invoice Footer -->

                </div>
            </section>
        </div>
    </div>
</div>