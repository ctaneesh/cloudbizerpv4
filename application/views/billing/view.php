<div class="content-body">
    <div class="card">
        <div class="card-content">
            <div id="notify" class="alert alert-success" style="display:none;">
                <a href="#" class="close" data-dismiss="alert">&times;</a>

                <div class="message"></div>
            </div>


            <div id="invoice-template" class="card-body">
                <div class="row wrapper white-bg page-heading">

                    <div class="col">
                        <?php $rming = $invoice['total'] - $invoice['paid_amount'];
                        if ($invoice['status'] != 'canceled') { ?>
                            <div class="row">


                                <div class="col-md-8 ">
                                    <div class="form-group1">
                                       
                                        <?php 
                                        // echo $this->lang->line('Payment').":";
                                        // if ($online_pay['enable'] == 1) {
                                        //     echo '<a class="btn btn-sm btn-success btn-min-width mr-1" href="#' . base_url('billing/card?id=' . $invoice['iid'] . '&itype=inv&token=' . $token) . '" data-toggle="modal" data-target="#paymentCard"><i class="fa fa-cc"></i> Credit Card</a> ';
                                        // }
                                        // if ($online_pay['bank'] == 1) {
                                        //     echo '<a class="btn btn-sm btn-cyan btn-min-width mr-1"
                                        //             href = "' . base_url('billing/bank') . '" role = "button" ><i
                                        //                 class="fa fa-bank" ></i > ' . $this->lang->line('Bank') . ' / ' . $this->lang->line('Cash') . '</a >';
                                        // }
                                        if($crm)
                                        {
                                            echo '<a class="btn btn-sm btn-secondary  mr-1" href = "' . base_url('crm/invoices/invoices') . '" role = "button" ><i  class="fa fa-backward" ></i > </a >';
                                        }
                                        if ($this->aauth->is_loggedin()) {

                                            echo '<a class="btn btn-sm btn-secondary  mr-1" href = "' . base_url('invoices/create?id=' . $invoice['iid']) . '" role = "button" ><i  class="fa fa-backward" ></i > </a >';
                                        }
                                        ?>

                                    </div>
                                </div>


                                <div class="col-md-4 text-right">
                                    <div class="btn-group1">
                                        <button type="button" class="btn btn-sm btn-success btn-min-width dropdown-toggle"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                                    class="fa fa-print"></i> <?php echo $this->lang->line('Print Invoice') ?>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="<?= base_url('billing/printinvoice?id=' . $invoice['iid'] . '&token=' . $token); ?>" target='_blank'><?php echo $this->lang->line('Print') ?></a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" target='_blank' href="<?= base_url('billing/printinvoice?id=' . $invoice['iid'] . '&token=' . $token); ?>&d=1"><?php echo $this->lang->line('PDF Download') ?></a>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="title-action ">


                            </div><?php } else {
                            echo '<h2 class="btn btn-sm btn-oval btn-danger">' . $this->lang->line('Cancelled') . '</h2>';
                        } ?>
                    </div>
                </div>

                <!-- Invoice Company Details -->
                <div id="invoice-company-details" class="row">
                    <div class="col-md-6 col-sm-12 text-xs-center text-md-left"><p></p>
                        <img src="<?php $loc = location($invoice['loc']);
                        echo base_url('userfiles/company/' . $loc['logo']) ?>"
                             class="img-responsive p-1 m-b-2" style="max-height: 120px;">
                        <p class="text-muted pl-5 pr-5"></p>


                        <ul class="px-0 list-unstyled">
                            <?php

                            echo '<li class="text-bold-800">' . $loc['cname'] . '</li><li>' . $loc['address'] . '</li><li>' . $loc['city'] . ',</li><li>' . $loc['region'] . ',' . $loc['country'] . ' -  ' . $loc['postbox'] . '</li><li>' . $this->lang->line('Phone') . ' : ' . $loc['phone'] . ' ' . $this->lang->line('Email') . ' : ' . $loc['email'] ?>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6 col-sm-12 text-xs-center text-md-right mt-2">
                        <p class="pb-0"> <?php
                            echo '<b>'.$this->lang->line('INVOICE').' : ' . $invoice['invoice_number'] . '</b></p>
                            <p class="pb-0">' . $this->lang->line('Reference') . ' : ' . $invoice['refer'] . '</p>'; ?>
                            <p ><?php echo $this->lang->line('Payment Status') ?>:
                                        <u><strong id="pstatus"><?php echo $this->lang->line(ucwords($invoice['invoicestatus'])) ?></strong></u>
                                    </p>
                        <ul class="px-0 list-unstyled">
                            <li><?php echo $this->lang->line('Gross Amount') ?> : <strong class="lead"><b><?=$invoice['total']?></b></strong></li>
                           
                            <?php //echo  amountExchange($invoice['total'], $invoice['multi'], $invoice['loc']); ?>
                        </ul>
                    </div>

                </div>

                <!--/ Invoice Company Details -->

                <!-- Invoice Customer Details -->
                <div id="invoice-customer-details ">

                    <div class="row pt-2">
                        <div class="col-md-4 col-sm-12 text-xs-center text-md-left">

                            <p class="text-muted mb-0"><?php echo $this->lang->line('Bill To') ?></p>
                            <ul class="px-0 list-unstyled">
                                <li class="text-bold-800">
                                    <?php
                                        $city = (trim($invoice['city'])) ? $invoice['city'] . ', ' : '';
                                        $country = (trim($invoice['country'])) ? $invoice['country'] . ', ' : '';
                                        $postbox = (trim($invoice['postbox'])) ? $invoice['postbox'] : '';
                                        $region = (trim($invoice['region'])) ? $invoice['region'] : '';
                                    ?>
                                    <strong  class="invoice_a"><?php echo $invoice['name'] . '</strong></li><li>' . $invoice['address'] . '</li><li>' . $city . $region . '</li><li>' . $country .  $postbox . '</li><li>' . $this->lang->line('Phone') . ' : ' . $invoice['phone'] . '</li><li>' . $this->lang->line('Email') . ' : ' . $invoice['email'] . ' </li>';
                                    if (isset($c_custom_fields)){
                                    foreach ($c_custom_fields

                                    as $row) {
                                    echo '<li>' . $row['name'] . ': ' . $row['data'] ?></li>

                                <?php }
                                } ?>

                            </ul>


                        </div>
                        <div class="col-md-5 col-sm-12 text-xs-center text-md-left"> <?php if ($invoice['shipping_name']) { ?>
                                <p class="text-muted mb-0"><?php echo $this->lang->line('Shipping Address') ?></p>
                                <ul class="px-0 list-unstyled">

                                    <?php
                                        $shipping_city = (trim($invoice['shipping_city'])) ? $invoice['shipping_city'] . ', ' : '';
                                        $shipping_country = (trim($invoice['shipping_country'])) ? $invoice['shipping_country'] . ', ' : '';
                                        $shipping_postbox = (trim($invoice['shipping_postbox'])) ? $invoice['shipping_postbox'] : '';
                                        $shipping_region = (trim($invoice['shipping_region'])) ? $invoice['shipping_region'] : '';
                                    ?>
                                    <li class="text-bold-800"><strong
                                                class="invoice_a"><?php echo $invoice['shipping_name'] . '</strong></li><li>' . $invoice['shipping_address_1'] . '</li><li>' . $shipping_city . $shipping_region . '</li><li>' . $shipping_country  . $shipping_postbox . '</li><li>' . $this->lang->line('Phone') . ' : ' . $invoice['shipping_phone'] . '</li><li>' . $this->lang->line('Email') . ' : ' . $invoice['shipping_email']; ?>
                                    </li>
                                </ul>
                            <?php } ?>
                        </div>
                        <div class="col-md-3 col-sm-12 text-right">
                            <?php $date_text = $this->lang->line('Due Date');
                            if ($invoice['i_class'] > 1) $date_text = $this->lang->line('Renew Date');
                            echo '<p class="mb-0"><span class="text-muted">' . $this->lang->line('Invoice Date') . ' :</span> ' . dateformat($invoice['invoice_date']) . '</p> <p class="mb-0"><span class="text-muted">' . $date_text . ' :</span> ' . dateformat($invoice['due_date']) . '</p>  <p class="mb-0"><span class="text-muted">' . $this->lang->line('Terms') . ' :</span> ' . $invoice['termtit'] . '</p>';
                            ?>
                        </div>
                    </div>
                </div>
                <!--/ Invoice Customer Details -->

                <!-- Invoice Items Details -->
                <div id="invoice-items-details" class="pt-2">
                    <div class="row">
                        <div class="table-responsive col-sm-12">
                            <table class="table table-striped table-bordered zero-configuration dataTable">
                                <thead>
                                <?php if ($invoice['tax_status'] == 'cgst'){ ?>

                                <tr>
                                    <th>#</th>
                                    <th><?php echo $this->lang->line('Item No') ?></th>
                                    <th><?php echo $this->lang->line('Item Name') ?></th>
                                    <th class="text-xs-left"><?php echo $this->lang->line('HSN') ?></th>
                                    <th class="text-xs-left"><?php echo $this->lang->line('Rate') ?></th>
                                    <th class="text-xs-left"><?php echo $this->lang->line('Qty') ?></th>
                                    <th class="text-xs-left"><?php echo $this->lang->line('Discount') ?></th>
                                    <th class="text-xs-left"><?php echo $this->lang->line('CGST') ?></th>
                                    <th class="text-xs-left"><?php echo $this->lang->line('SGST') ?></th>
                                    <th class="text-xs-left"><?php echo $this->lang->line('Amount') ?></th>
                                    <th class="text-xs-left"><?php echo $this->lang->line('Paid Amount') ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $c = 1;
                                $sub_t = 0;

                                foreach ($products as $row) {
                                    $sub_t += $row['price'] * $row['product_qty'];
                                    $gst = $row['total_tax'] / 2;
                                    $rate = $row['tax'] / 2;
                                    echo '<tr>                                    
                                    <td>' . $row['code'] . '</td>   
                                    <th scope="row">' . $c . '</th>    
                                    <td>' . $row['product'] . '</td>                    
                                    <td>' . amountExchange($row['price'], $invoice['multi'], $invoice['loc']) . '</td>
                                    <td>' . amountFormat_general($row['product_qty']) . $row['unit'] . '</td>
                                    <td>' . amountExchange($row['total_discount'], $invoice['multi'], $invoice['loc']) . ' (' . amountFormat_s($row['discount']) . $this->lang->line($invoice['format_discount']) . ')</td>
                                    <td>' . amountExchange($gst, $invoice['multi'], $invoice['loc']) . ' (' . amountFormat_s($rate) . '%)</td>
                                    <td>' . amountExchange($gst, $invoice['multi'], $invoice['loc']) . ' (' . amountFormat_s($rate) . '%)</td>                           
                                    <td>' . amountExchange($row['subtotal'], $invoice['multi'], $invoice['loc']) . '</td>
                                    <td>' . amountExchange($row['paid_amount'], $invoice['multi'], $invoice['loc']) . '</td>
                                    </tr>';

                                    // echo '<tr><td colspan=7>' . $row['product_des'] . '</td></tr>';
                                    if (CUSTOM) {
                                        $p_custom_fields = $this->custom->view_fields_data($row['product_code'], 4, 1);


                                        $z_custom_fields = '';

                                        foreach ($p_custom_fields as $row) {
                                            $z_custom_fields .= $row['name'] . ': ' . $row['data'] . '<br>';
                                        }

                                        echo '<tr>  
                                        <td colspan="7">' . $z_custom_fields . '&nbsp;</td>
							
                                        </tr>';
                                    }
                                    $c++;
                                } ?>

                                </tbody>
                                <?php

                                } elseif ($invoice['tax_status'] == 'igst') {
                                    ?>
                                    <tr>
                                        <th>#</th>
                                        <th><?php echo $this->lang->line('Code') ?></th>
                                        <th><?php echo $this->lang->line('Description') ?></th>
                                        <th class="text-xs-left"><?php echo $this->lang->line('HSN') ?></th>
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
                                        $sub_t += $row['price'] * $row['product_qty'];

                                        echo '<tr>
                                            <th scope="row">' . $c . '</th> 
                                            <td>' . $row['code'] . '</td>   
                                            <td>' . $row['product'] . '</td>                       
                                            <td>' . amountExchange($row['price'], $invoice['multi'], $invoice['loc']) . '</td>
                                            <td>' . amountFormat_general($row['product_qty']) . $row['unit'] . '</td>
                                            <td>' . amountExchange($row['total_discount'], $invoice['multi'], $invoice['loc']) . ' (' . amountFormat_s($row['discount']) . $this->lang->line($invoice['format_discount']) . ')</td>
                                            <td>' . amountExchange($row['total_tax'], $invoice['multi'], $invoice['loc']) . ' (' . amountFormat_s($row['tax']) . '%)</td>
                                                            
                                            <td>' . amountExchange($row['subtotal'], $invoice['multi'], $invoice['loc']) . '</td>
                                        </tr>';

                                        // echo '<tr><td colspan=7>' . $row['product_des'] . '</td></tr>';
                                        if (CUSTOM) {
                                            $p_custom_fields = $this->custom->view_fields_data($row['product_code'], 4, 1);


                                            $z_custom_fields = '';

                                            foreach ($p_custom_fields as $row) {
                                                $z_custom_fields .= $row['name'] . ': ' . $row['data'] . '<br>';
                                            }

                                            echo '<tr>  
                                            <td colspan="7">' . $z_custom_fields . '&nbsp;</td>
							
                                            </tr>';
                                        }
                                        $c++;
                                    } ?>

                                    </tbody>
                                    <?php
                                } else {
                                    ?>
                                    <tr>
                                        <th>#</th>
                                        <th><?php echo $this->lang->line('Item Name') ?></th>
                                        <th><?php echo $this->lang->line('Item No') ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('Rate') ?></th>
                                        <th class="text-center"><?php echo $this->lang->line('Qty') ?></th>
                                        <!-- <th class="text-xs-left"><?php echo $this->lang->line('Tax') ?></th> -->
                                        <th class="text-right"><?php echo $this->lang->line('Discount') ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('Amount') ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $c = 1;
                                    $sub_t = 0;

                                    foreach ($products as $row) {
                                        $sub_t += $row['price'] * $row['product_qty'];
                                        echo '<tr>
                                            <td scope="row" class="text-center">' . $c . '</td>
                                            
                                            <td>' . $row['product_name'] . '</td>                           
                                            <td>' . $row['product_code'] . '</td>                           
                                            <td class="text-right">' . $row['price'] . '</td>
                                            <td class="text-center">' . intval($row['product_qty']) . $row['unit'] . '</td>';
                                            // echo '<td>' . amountExchange($row['totaltax'], $invoice['multi'], $invoice['loc']) . ' (' . amountFormat_s($row['tax']) . '%)</td>';

                                            echo '<td class="text-right">' . $row['total_discount'].'</td>';

                                            echo '<td class="text-right">' .$row['subtotal'] . '</td>
                                        </tr>';

                                        // echo '<tr><td colspan=7>' . $row['product_des'] . '</td></tr>';
                                        if (CUSTOM) {
                                            $p_custom_fields = $this->custom->view_fields_data($row['product_code'], 4, 1);


                                            $z_custom_fields = '';

                                            foreach ($p_custom_fields as $row) {
                                                $z_custom_fields .= $row['name'] . ': ' . $row['data'] . '<br>';
                                            }

                                            // echo '<tr><td colspan="7">' . $z_custom_fields . '&nbsp;</td></tr>';
                                        }
                                        $c++;
                                    } ?>

                                    </tbody>
                                <?php } ?>
                            </table>
                        </div>
                    </div>
                    <p></p>
                    <div class="row">
                        <div class="col-md-9 col-sm-12 text-xs-center text-md-left">


                            <div class="row">
                                <div class="col-md-8">
                                    <p  class="lead"><?php echo $this->lang->line('Payment Status') ?>:
                                        <u><strong id="pstatus"><?php echo $this->lang->line(ucwords($invoice['invoicestatus'])) ?></strong></u>
                                    </p>
                                    <p class="lead"><?php echo $this->lang->line('Payment Method') ?>: <u><strong
                                                    id="pmethod"><?php echo $this->lang->line($invoice['payment_method']) ?></strong></u>
                                    </p>

                                    <p class="lead mt-1"><br><?php echo $this->lang->line('Note') ?>:</p>
                                    <code>
                                        <?php echo $invoice['notes'] ?>
                                    </code>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-3 col-sm-12">
                            <p class="lead"><?php echo $this->lang->line('Summary') ?></p>
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                    <tr>
                                        <td><?php echo $this->lang->line('Sub Total') ?></td>
                                        <td class="text-right"> <?php echo number_format($sub_t,2); ?></td>
                                    </tr>
                                    <tr class="d-none">
                                        <td><?php echo $this->lang->line('TAX') ?></td>
                                        <td class="text-right"><?php echo number_format($invoice['tax'], 2); ?></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo $this->lang->line('Discount') ?></td>
                                        <td class="text-right"><?php echo number_format($invoice['discount'], 2); ?></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo $this->lang->line('Shipping') ?></td>
                                        <td class="text-right"><?php echo number_format($invoice['shipping'], 2); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="text-bold-800"><?php echo $this->lang->line('Total') ?></td>
                                        <td class="text-bold-800 text-right"> <?php echo number_format($invoice['total'], 2); ?></td>
                                    </tr>
                                    <?php $roundoff = $this->custom->api_config(4);
                                    if ($roundoff['other']) {
                                        $final_amount = round($invoice['total'], $roundoff['active'], constant($roundoff['other']));
                                        ?>

                                        <tr>
                                            <td>
                                                <span class="text-bold-800"><?php echo $this->lang->line('Total') ?></span>
                                                (<?php echo $this->lang->line('Round Off') ?> )
                                            </td>
                                            <td class="text-bold-800 text-right"> <?php echo number_format($final_amount, 2); ?></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    <tr class="d-none1">
                                        <td><?php echo $this->lang->line('Payment Made'); ?></td>
                                        <td class="pink text-right">
                                            (-) <?php echo ' <span id="paymade">' . $invoice['paid_amount']; ?></span></td>
                                    </tr>
                                    <tr class="bg-grey bg-lighten-4">
                                        <td class="text-bold-800"><?php echo $this->lang->line('Balance Due'); ?></td>
                                        <td class="text-bold-800 text-right"> <?php $myp = '';

                                            if ($rming < 0) {
                                                $rming = 0;

                                            }
                                            if ($roundoff['other']) {
                                                $rming = round($rming, $roundoff['active'], constant($roundoff['other']));
                                            }
                                            echo ' <span id="paydue">' . $rming . '</span></strong>'; ?></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-right">
                                <p><?php echo $this->lang->line('Authorized person'); ?></p>
                                <?php echo '<img src="' . base_url('userfiles/employee_sign/' . $employee['sign']) . '" alt="signature" class="height-100"/>
                                    <h6>(' . $employee['name'] . ')</h6>
                                   '; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Invoice Footer -->

                <div id="invoice-footer" class="d-none"><p class="lead"><?php echo $this->lang->line('Credit Transactions'); ?>
                        :</p>
                    <table class="table table-striped table-bordered zero-configuration dataTable">
                        <thead>
                        <tr>
                            <th><?php echo $this->lang->line('Date'); ?></th>
                            <th><?php echo $this->lang->line('Method'); ?></th>
                            <th><?php echo $this->lang->line('Amount'); ?></th>
                            <th><?php echo $this->lang->line('Note'); ?></th>


                        </tr>
                        </thead>
                        <tbody id="activity">
                        <?php foreach ($activity as $row) {
                            if ($row['credit'] > 0) {
                                echo '<tr>
                            <td>' . $row['date'] . '</td>
                            <td>' . $this->lang->line($row['method']) . '</td>
                            <td>' . amountExchange($row['credit'], $invoice['multi'], $invoice['loc']) . '</td>
                            <td>' . $row['note'] . '</td>
                        </tr>';
                            }
                        } ?>

                        </tbody>
                    </table>

                    <div class="row d-none" >

                        <div class="col-md-7 col-sm-12">

                            <h6><?php echo $this->lang->line('Terms & Condition'); ?></h6>
                            <p> <?php

                                echo '<strong>' . $invoice['termtit'] . '</strong><br>' . $invoice['terms'];
                                ?></p>
                        </div>

                    </div>


                    <div class="row">
                        <?php if ($attach) { ?>

                            <table class="table table-striped table-bordered zero-configuration dataTable">
                                <thead>
                                <tr>
                                    <th><?php echo $this->lang->line('Files') ?></th>


                                </tr>
                                </thead>
                                <tbody id="activity">
                                <?php foreach ($attach as $row) {

                                    echo '<tr><td><a href="' . base_url() . 'userfiles/attach/' . $row['col1'] . '"><i class="btn-info btn-lg icon-download"></i> ' . $row['col1'] . ' </a></td></tr>';
                                } ?>

                                </tbody>
                            </table>
                        <?php } ?>

                    </div>
                </div>
                <!--/ Invoice Footer -->

            </div>
            </section>
        </div>
    </div>
</div>
<?php if ($online_pay['enable'] == 1) { ?>
    <div id="paymentCard" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">

                    <h4 class="modal-title"><?php echo $this->lang->line('Make Payment') ?></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">

                    <?php


                    foreach ($gateway as $row) {
                        $cid = $row['id'];
                        $title = $row['name'];
                        if ($row['surcharge'] > 0) {
                            $surcharge_t = true;
                            $fee = '( ' . amountExchange($rming, $invoice['multi'], $invoice['loc']) . '+' . amountFormat_s($row['surcharge']) . ' %)';
                        } else {
                            $fee = '';
                        }

                        echo '<a href="' . base_url('billing/card?id=' . $invoice['iid'] . '&itype=inv&token=' . $token) . '&gid=' . $cid . '" class="btn   btn-sm mb-1 btn-block blue rounded border border-info text-bold-700 border-lighten-5 "><span class=" display-block"><span class="grey">Pay With </span><span class="blue font-medium-2">' . $title . ' ' . $fee . '</span></span>

 <img class="mt-1 bg-white round" style="max-width:20rem;max-height:10rem"
                                             src="' . assets_url('assets/gateway_logo/' . $cid . '.png') . '">
</a><br>';
                    }
                    ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn  btn-sm btn-default " data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
<?php } ?>
