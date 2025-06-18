<div class="app-content content container-fluid">
    <div class="content-wrapper">
        <div id="notify" class="alert alert-success" style="display:none;">
            <a href="#" class="close" data-dismiss="alert">&times;</a>

            <div class="message"></div>
        </div>

        <div class="content-body">
            <section class="card">
                <div id="invoice-template" class="card-body">
                    <div class="row wrapper white-bg page-heading">

                        <div class="col-lg-12">
                            <?php
                            $rming = $invoice['total'] - $invoice['pamnt'];
                            if ($invoice['status'] != 'canceled') { ?>
                                <div class="row">


                                    <div class="col-md-12 text-right d-none">
                                        <div class="btn-group mt-2">
                                            <button type="button" class="btn btn-primary btn-min-width dropdown-toggle"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                                        class="icon-print"></i> <?php echo $this->lang->line('Print Order') ?>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item"
                                                   href="<?php echo 'printorder?id=' . $invoice['iid'] . '&token=' . $token; ?>"><?php echo $this->lang->line('Print') ?></a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item"
                                                   href="<?php echo 'printorder?id=' . $invoice['iid'] . '&token=' . $token; ?>&d=1"><?php echo $this->lang->line('PDF Download') ?></a>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="title-action ">


                                </div><?php } else {
                                echo '<h2 class="btn btn-oval btn-danger">' . $this->lang->line('Cancelled') . '</h2>';
                            } ?>
                        </div>
                    </div>

                    <!-- Invoice Company Details -->
                    <div id="invoice-company-details" class="row mt-2">
                        <div class="col-md-6 col-sm-12 text-xs-center text-md-left"><p></p>
                            <img src="<?php $loc = location($invoice['loc']);
                            echo base_url('userfiles/company/' . $loc['logo']) ?>"
                                 class="img-responsive p-1 m-b-2" style="max-height: 120px;">
                            <p class="text-muted ml-3 mr-3"><?php echo $this->lang->line('From') ?></p>


                            <ul class="px-0 list-unstyled">
                                <?php

                                echo '<li class="text-bold-800">' . $loc['cname'] . '</li><li>' . $loc['address'] . '</li><li>' . $loc['city'] . ',</li><li>' . $loc['region'] . ',' . $loc['country'] . ' -  ' . $loc['postbox'] . '</li><li>' . $this->lang->line('Phone') . ' : ' . $loc['phone'] . '</li><li> ' . $this->lang->line('Email') . ' : ' . $loc['email'] ?>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6 col-sm-12 text-xs-center text-md-right mt-2">
                            <h3><?php echo $this->lang->line('Purchase Receipt')." #". $invoice['srv'] ?><br>Purchase Order : #<?= $purchaseorderdata['tid']?><br></h3>
                            
                            
                            <ul class="px-0 list-unstyled">
                                <li><?php echo $this->lang->line('Gross Amount') ?> <strong id="grossamount"></strong></li>
                            </ul>
                            
                        </div>

                    </div>

                    <!--/ Invoice Company Details -->

                    <!-- Invoice Customer Details -->
                    <div id="invoice-customer-details" class="row pt-2">
                        <div class="col-sm-12 text-xs-center text-md-left">
                            <p class="text-muted">
                                <?php //echo $this->lang->line('Bill From'); 
                                echo $this->lang->line('Supplier Details') ?>
                            </p>
                        </div>
                        <div class="col-md-8 col-sm-12 text-xs-center text-md-left">
                            <ul class="px-0 list-unstyled">


                                <li class="text-bold-800"><strong
                                            class="invoice_a"><?php echo $supplier['name'] . '</strong></li><li>' . $supplier['address'] . '</li><li>' . $supplier['city'] . ', ' . $supplier['region'] . '</li><li>' . $supplier['country'] . ', ' . $supplier['postbox'] . '</li><li>' . $this->lang->line('Phone') . ' : ' . $supplier['phone'] . '</li><li>' . $this->lang->line('Email') . ' : ' . $supplier['email']; ?>
                                </li>
                            </ul>
                            <?php //echo '<p><span class="text-muted">' . $this->lang->line('Supplier Reference Number') . ' :</span> ' . $invoice['customer_reference_number'] . '</p>'; ?>
                            <?php //echo '<p><span class="text-muted">' . $this->lang->line('Terms') . ' :</span> ' . $invoice['termtit'] . '</p>'; ?>
                        </div>
                        <div class="col-md-4 col-sm-12 text-xs-center text-md-right">
                            <?php echo '<p><span class="text-muted">' . $this->lang->line('Purchase Receipt Date') . ' :</span> ' . dateformat($invoice['created_date']) . '</p>';
                            ?>
                        </div>
                    </div>
                    <!--/ Invoice Customer Details -->

                    <!-- Invoice Items Details -->
                    <div id="invoice-items-details" class="pt-2">
                        <div class="row">
                            <div class="table-responsive col-sm-12">
                                <table class="table table-striped table-bordered zero-configuration dataTable">
                                    <thead>
                                    <?php if ($invoice['taxstatus'] == 'cgst'){ ?>

                                    <tr>
                                        <th>#</th>
                                        <th><?php echo $this->lang->line('Description') ?></th>
                                        <th class="text-xs-left"><?php echo $this->lang->line('HSN') ?></th>
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
                                        $gst = $row['totaltax'] / 2;
                                        $rate = $row['tax'] / 2;
                                        // echo '<tr>
                                        //     <th scope="row">' . $c . '</th>
                                        //     <td>' . $row['product'] . '</td> 
                                        //     <td>' . $row['code'] . '</td>                          
                                        //     // <td>' . amountExchange($row['price'], $invoice['multi'], $invoice['loc']) . '</td>
                                        //     // <td>' . amountFormat_general($row['qty']) . $row['unit'] . '</td>
                                        //     // <td>' . amountExchange($row['totaldiscount'], $invoice['multi'], $invoice['loc']) . ' (' . amountFormat_s($row['discount']) . $this->lang->line($invoice['format_discount']) . ')</td>
                                        //     <td>' . amountExchange($gst, $invoice['multi'], $invoice['loc']) . ' (' . amountFormat_s($rate) . '%)</td>
                                        //     <td>' . amountExchange($gst, $invoice['multi'], $invoice['loc']) . ' (' . amountFormat_s($rate) . '%)</td>                           
                                        //     <td>' . amountExchange($row['subtotal'], $invoice['multi'], $invoice['loc']) . '</td>
                                        // </tr>';

                                        // echo '<tr><td colspan=5>' . $row['product_des'] . '</td></tr>';
                                        echo '<tr>
                                            <th scope="row">' . $c . '</th>
                                            <td>' . $row['product'] . '</td>                           
                                            <td>' . $row['price'] . '</td>
                                            <td>' . intval($row['qty']) . $row['unit'] . '</td>';
                                            // echo '<td>' . $row['totaltax'] . ' (' . amountFormat_s($row['tax']) . '%)</td>';
                                            echo '<td>' . $row['totaldiscount'] . ' (' . amountFormat_s($row['discount']) . $this->lang->line($invoice['format_discount']) . ')</td>
                                            <td>' . $row['subtotal'] . '</td>
                                           </tr>';
                                        $c++;
                                    } ?>

                                    </tbody>
                                    <?php

                                    } elseif ($invoice['taxstatus'] == 'igst') {
                                        ?>
                                        <tr>
                                            <th>#</th>
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
                                            $sub_t += $row['price'] * $row['qty'];

                                            // echo '<tr>
                                            //         <th scope="row">' . $c . '</th>
                                            //         <td>' . $row['product'] . '</td> 
                                            //         <td>' . $row['code'] . '</td>                          
                                            //         <td>' . amountExchange($row['price'], $invoice['multi'], $invoice['loc']) . '</td>
                                            //         <td>' . amountFormat_general($row['qty']) . $row['unit'] . '</td>
                                            //         <td>' . amountExchange($row['totaldiscount'], $invoice['multi'], $invoice['loc']) . ' (' . amountFormat_s($row['discount']) . $this->lang->line($invoice['format_discount']) . ')</td>
                                            //         <td>' . amountExchange($row['totaltax'], $invoice['multi'], $invoice['loc']) . ' (' . amountFormat_s($row['tax']) . '%)</td>
                                                                    
                                            //         <td>' . amountExchange($row['subtotal'], $invoice['multi'], $invoice['loc']) . '</td>
                                            //     </tr>';

                                            // echo '<tr><td colspan=5>' . $row['product_des'] . '</td></tr>';
                                            echo '<tr>
                                            <th scope="row">' . $c . '</th>
                                            <td>' . $row['product'] . '</td>                           
                                            <td>' . $row['price'] . '</td>
                                            <td>' . intval($row['qty']) . $row['unit'] . '</td>';
                                            // echo '<td>' . $row['totaltax'] . ' (' . amountFormat_s($row['tax']) . '%)</td>';
                                            echo '<td>' . $row['totaldiscount'] . ' (' . amountFormat_s($row['discount']) . $this->lang->line($invoice['format_discount']) . ')</td>
                                            <td>' . $row['subtotal'] . '</td>
                                           </tr>';
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
                                            <th class="text-center"><?php echo $this->lang->line('Ordered Qty') ?></th>
                                            <th class="text-center"><?php echo $this->lang->line('Received Qty') ?></th>
                                            <th class="text-center"><?php echo $this->lang->line('Damaged Qty') ?></th>
                                            <!-- <th class="text-xs-left"><?php echo $this->lang->line('Tax') ?></th> -->
                                            <th class="text-right"><?php echo $this->lang->line('Discount') ?></th>
                                            <th class="text-right"><?php echo $this->lang->line('Amount') ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $c = 1;
                                        $sub_t = 0;
                                        $subtotalfull =0;
                                        $totaldiscount = 0;
                                        foreach ($products as $row) {
                                            $currentqty = ($row['damage']>0) ? $row['product_qty_recieved'] - $row['damage'] : $row['product_qty_recieved'];
                                            $discount = ($row['discountamount']>0)?$row['discountamount']:0;
                                            $netamount = ($currentqty * $row['price'])-$discount;
                                            $subtotalfull += ($currentqty * $row['price']);
                                            $totaldiscount += $discount;
                                            $sub_t += $row['price'] * $row['qty'];
                                            echo '<tr>
                                            <th scope="row">' . $c . '</th>
                                            <td>' . $row['product_name'] . '</td>                           
                                            <td>' . $row['product_code'] . '</td>                           
                                            <td class="text-right">' . number_format($row['price'], 2) . '</td>
                                            <td class="text-center">' . intval($row['product_qty']). '</td>
                                            <td class="text-center">' . intval($row['product_qty_recieved']). '</td>
                                            <td class="text-center">' . intval($row['damage']). '</td>';
                                            // echo '<td>' . $row['totaltax'] . ' (' . amountFormat_s($row['tax']) . '%)</td>';
                                            echo '<td class="text-right">' . number_format($row['discountamount'],2).'</td>
                                            <td class="text-right">' . number_format($netamount,2) . '</td>
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
                            <div class="col-md-9 col-sm-12 text-xs-center text-md-left">


                                <div class="row">
                                    <div class="col-md-8"><p
                                                class="lead d-none"><?php echo $this->lang->line('Payment Status') ?>:
                                            <u><strong
                                                        id="pstatus"><?php echo $this->lang->line(ucwords($invoice['status'])) ?></strong></u>
                                        </p>
                                        <p class="lead d-none"><?php echo $this->lang->line('Payment Method') ?>: <u><strong
                                                        id="pmethod"><?php echo $this->lang->line($invoice['pmethod']) ?></strong></u>
                                        </p>

                                        <p class="lead mt-1 d-none"><br><?php echo $this->lang->line('Note') ?>:</p>
                                        <code>
                                            <?php echo $invoice['notes'] ?>
                                        </code>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-3 col-sm-12">
                                <p class="lead"><?php echo $this->lang->line('Total Due') ?></p>
                                <div class="table-responsive">
                                    <table class="table">
                                        <tbody>
                                        <tr>
                                            <?php
                                                $sub_total = $sub_t - $invoice['discount'];
                                                $grandtotal = $subtotalfull - $totaldiscount
                                            ?>
                                            <td><?php 
                                            // amountExchange(sub_t, $invoice['multi'], $invoice['loc'])
                                            echo $this->lang->line('Sub Total') ?></td>
                                            <td class="text-right"> <?php echo number_format($subtotalfull,2) ?></td>
                                        </tr>
                                        <tr class="d-none">
                                            <td><?php echo $this->lang->line('TAX') ?></td>
                                            <td class="text-right">
                                                <?php echo amountExchange($invoice['tax'], $invoice['multi'], $invoice['loc']) ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><?php echo $this->lang->line('Discount') ?></td>
                                            <td class="text-right">
                                                <?php 
                                                // echo amountExchange($invoice['discount'], $invoice['multi'], $invoice['loc'])
                                                echo number_format($totaldiscount,2);
                                                 ?>
                                            </td>
                                        </tr>
                                        <tr class="d-none">
                                            <td><?php echo $this->lang->line('Shipping') ?></td>
                                            <td class="text-right"><?php echo amountExchange($invoice['shipping'], $invoice['multi'], $invoice['loc']) ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-bold-800"><?php echo $this->lang->line('Total') ?></td>
                                            <td class="text-bold-800 text-right"> 
                                                <?php 
                                                // echo amountExchange($invoice['total'], $invoice['multi'], $invoice['loc'])
                                                echo number_format($grandtotal,2);
                                                 ?>
                                                 <input type="hidden" id="wholetotal" value="<?=number_format($grandtotal,2)?>">
                                            </td>
                                        </tr>
                                        <tr class="d-none">
                                            <td><?php echo $this->lang->line('Payment Made') ?></td>
                                            <td class="pink text-right">
                                                (-) <?php echo ' <span id="paymade">' . amountExchange($invoice['pamnt'], $invoice['multi'], $invoice['loc']) ?></span></td>
                                        </tr>
                                        <tr class="bg-grey bg-lighten-4 d-none">
                                            <td class="text-bold-800"><?php echo $this->lang->line('Balance Due') ?></td>
                                            <td class="text-bold-800 text-right"> <?php $myp = '';

                                                if ($rming < 0) {
                                                    $rming = 0;

                                                }
                                                // echo ' <span id="paydue">' . amountExchange($rming, $invoice['multi'], $invoice['loc']) . '</span></strong>';
                                                echo ' <span id="paydue">' . number_format($rming,2) . '</span></strong>';
                                                
                                                ?></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="text-right">
                                    <p><?php echo $this->lang->line('Authorized person') ?></p>
                                    <?php echo '<img src="' . base_url('userfiles/employee_sign/' . $preparedperson['sign']) . '" alt="signature" class="height-100"/>
                                    <h6>(' . $preparedperson['name'] . ')</h6>'; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Invoice Footer -->

                    <div id="invoice-footer" class="d-none"><p class="lead"><?php echo $this->lang->line('Debit Transactions') ?>:</p>
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
                                if ($row['debit'] > 0) {
                                    echo '<tr>
                            <td>' . $row['date'] . '</td>
                            <td>' . $this->lang->line($row['method']) . '</td>
                            <td>' . amountExchange($row['debit'], $invoice['multi'], $invoice['loc']) . '</td>
                            <td>' . $row['note'] . '</td>
                        </tr>';
                                }
                            } ?>

                            </tbody>
                        </table>

                        <div class="row">

                            <div class="col-md-7 col-sm-12">

                                <h6><?php echo $this->lang->line('Terms & Condition') ?></h6>
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
<script>
    $(document).ready(function() {
        $("#grossamount").text($('#wholetotal').val());
    });
</script>


