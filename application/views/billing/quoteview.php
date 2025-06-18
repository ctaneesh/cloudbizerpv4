<div class="container-fluid">
    <div class="content-wrapper">
        <div id="notify" class="alert alert-success" style="display:none;">
            <a href="#" class="close" data-dismiss="alert">&times;</a>

            <div class="message"></div>
        </div>

        <div class="content-body">
            <section class="card">
                <div id="invoice-template" class="card-body">

                    <div class="row wrapper white-bg page-heading">

                        <div class="col">

                            <div class="row">


                                <div class="col-md-8 ">
                                    <div class="form-group1">

                                        <?php 
                                      
                                        if($crm)
                                        {
                                            echo '<a class="btn btn-sm btn-secondary  mr-1" href = "' . base_url('crm/quote') . '" role = "button" ><i  class="fa fa-backward" ></i > </a >';
                                        }
                                        if ($this->aauth->is_loggedin()) {

                                            echo '<a class="btn btn-sm btn-secondary  mr-1" href = "' . base_url('quote/create?id=' . $invoice['iid']) . '" role = "button" ><i  class="fa fa-backward" ></i > </a >';
                                        }
                                        ?>

                                    </div>
                                </div>
                                
                               

                                <div class="col-md-4 text-right">
                                    <div class="btn-group1">
                                        <button type="button"
                                            class="btn btn-sm btn-success btn-min-width dropdown-toggle"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                                class="fa fa-print"></i>
                                            <?php echo $this->lang->line('Print') ?>
                                        </button>

                                       
                                        <div class="dropdown-menu">
                                           <a class="dropdown-item" target="_blank" href="<?= base_url('billing/printquote?id=' . $invoice['iid'] . '&token=' . $token); ?>"><?php echo $this->lang->line('Print') ?></a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" target='_blank'  href="<?= base_url('billing/printquote?id=' . $invoice['iid'] . '&token=' . $token); ?>&d=1"><?php echo $this->lang->line('PDF Download') ?></a>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                        </div>
                    </div>


                    <div class="row wrapper white-bg page-heading">

                        <div class="col-lg-12">
                            <?php $rming = $invoice['total'] - $invoice['pamnt']; ?>
                            <div class="row">
                                <?php
                                if($invoice['status']=="accepted")
                                {
                                    $status = "Ready to Send";
                                }
                                else{
                                    $status = ucwords($invoice['quotestatus']);
                                }
                            ?>

                                <div class="col-md-12 text-xs-right d-none">
                                    <div class="btn-group mt-2">
                                        <button type="button" class="btn btn-secondary btn-sm dropdown-toggle"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                                class="icon-print"></i> <?php echo $this->lang->line('Print') ?>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" target="_blank"
                                                href="<?php echo 'printquote?id=' . $invoice['iid'] . '&token=' . $token; ?>"><?php echo $this->lang->line('Print') ?></a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" target="_blank"
                                                href="<?php echo 'printquote?id=' . $invoice['iid'] . '&token=' . $token; ?>&d=1"><?php echo $this->lang->line('PDF Download') ?></a>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="title-action ">


                            </div>
                        </div>
                    </div>

                    <!-- Invoice Company Details -->
                    <div id="invoice-company-details" class="row">
                        <div class="col-md-6 col-sm-12 text-xs-center text-md-left">
                            <p></p>
                            <img src="<?php $loc = location($invoice['loc']);
                            echo base_url('userfiles/company/' . $loc['logo']) ?>" class="img-responsive p-1 m-b-2"
                                style="max-height: 120px;">
                            <p class="text-muted ml-3 mr-3"></p>


                            <ul class="px-0 list-unstyled">
                                <?php
                                $city = (trim($loc['city'])) ? $loc['city'] . ', ' : '';
                                $country = (trim($loc['country'])) ? $loc['country'] . ' -  '  : '';
                                $postbox = (trim($loc['postbox'])) ? $loc['postbox'] : '';
                                $region = (trim($loc['region'])) ? $loc['region'] : '';

                                echo '<li class="text-bold-800">' . $loc['cname'] . '</li><li>' . $loc['address'] . '</li><li>' . $city .'</li><li>' . $region . $country . $postbox . '</li><li>' . $this->lang->line('Phone') . ' : ' . $loc['phone'] . '</li><li> ' . $this->lang->line('Email') . ' : ' . $loc['email'] ?>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6 col-sm-12 text-xs-center text-md-right">
                            <?php echo '<p class=""><b>' . $this->lang->line('Quote')." : "; ?> <?php echo $invoice['quote_number'] . '</b></p>'; ?>
                            <?php echo '<p class="">' . $this->lang->line('Reference') . ' : ' . $invoice['refer'] . '</p>'; ?>

                            <p><?php echo $this->lang->line('Status') ?>: <u>
                                    <strong id="pstatus"><?php echo $status; ?></strong></u>
                            </p>
                            <p><?php echo $this->lang->line('Gross Amount') ?>: <u>
                                    <strong id=""><?php echo amountExchange($invoice['total'], $invoice['multi'], $invoice['loc']); ?></strong></u>
                            </p>
                            
                        </div>

                    </div>

                    <!--/ Invoice Company Details -->

                    <!-- Invoice Customer Details -->
                    <div id="invoice-customer-details" class="row pt-2">
                        <div class="col-sm-12 text-xs-center text-md-left">
                            <p class="text-muted"><?php echo $this->lang->line('Bill To') ?></p>
                        </div>
                        <div class="col-md-8 col-sm-12 text-xs-center text-md-left">
                            <ul class="px-0 list-unstyled">

                                <?php
                                    $city = (trim($invoice['city'])) ? $invoice['city'] . ', ' : '';
                                    $country = (trim($invoice['country'])) ? $invoice['country'] . ', ' : '';
                                    $postbox = (trim($invoice['postbox'])) ? $invoice['postbox'] : '';
                                    $region = (trim($invoice['region'])) ? $invoice['region'] : '';
                                ?>
                                <li class="text-bold-800"><strong
                                        class="invoice_a"><?php echo $invoice['name'] . '</strong></li><li>' . $invoice['address'] . '</li><li>' . $city . $region . '</li><li>' . $country . $postbox . '</li><li>' . $this->lang->line('Phone') . ' : ' . $invoice['phone'] . '</li><li>' . $this->lang->line('Email') . ' : ' . $invoice['email'].'</li>'; ?>
                                </li>
                            </ul>
                            <?php echo '<p>' . $this->lang->line('Terms') . ' :' . $invoice['termtit'] . '</p>'; ?>
                        </div>
                        <div class="col-md-4 col-sm-12 text-xs-left text-md-right">
                            <?php echo '<p><span class="text-muted">' . $this->lang->line('Quote Date') . ' :</span> ' . dateformat($invoice['invoicedate']) . '</p> <p><span class="text-muted">' . $this->lang->line('Valid till') . ' :</span> ' . dateformat($invoice['invoiceduedate']) . '</p>';
                            ?>
                        </div>
                    </div>
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
                            <div class="table-responsive col-sm-12">
                                <table class="table table-striped table-bordered zero-configuration dataTable">
                                    <thead>
                                        <?php if ($invoice['taxstatus'] == 'cgst'){ ?>

                                        <tr>
                                            <th>#</th>
                                            <th><?php echo $this->lang->line('Code') ?></th>
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
                                        echo '<tr>
                                            <th scope="row">' . $c . '</th>
                                            <td>' . $row['product'] . '</td> 
                                            <td>' . $row['code'] . '</td>                          
                                            <td>' . amountExchange($row['price'], $invoice['multi'], $invoice['loc']) . '</td>
                                            <td>' . amountFormat_general($row['qty']) . $row['unit'] . '</td>
                                            <td>' . $row['totaldiscount'] . ' (' . amountFormat_s($row['discount']) . $this->lang->line($invoice['format_discount']) . ')</td>
                                            <td>' . amountExchange($gst, $invoice['multi'], $invoice['loc']) . ' (' . amountFormat_s($rate) . '%)</td>
                                            <td>' . amountExchange($gst, $invoice['multi'], $invoice['loc']) . ' (' . amountFormat_s($rate) . '%)</td>                           
                                            <td>' . amountExchange($row['subtotal'], $invoice['multi'], $invoice['loc']) . '</td>
                                        </tr>';

                                        echo '<tr><td colspan=5>' . $row['product_des'] . '</td></tr>';
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

                                        <th class="text-xs-left"><?php echo $this->lang->line('Amount') ?>
                                            (<?php echo $this->config->item('currency'); ?>)</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php $c = 1;
                                        $sub_t = 0;

                                        foreach ($products as $row) {
                                            $sub_t += $row['price'] * $row['qty'];

                                            echo '<tr>
                                                <th scope="row">' . $c . '</th>
                                                <td>' . $row['product'] . '</td> 
                                                <td>' . $row['code'] . '</td>                          
                                                <td>' . amountExchange($row['price'], $invoice['multi'], $invoice['loc']) . '</td>
                                                <td>' . amountFormat_general($row['qty']) . $row['unit'] . '</td>
                                                <td>' . amountExchange($row['totaldiscount'], $invoice['multi'], $invoice['loc']) . ' (' . amountFormat_s($row['discount']) . $this->lang->line($invoice['format_discount']) . ')</td>
                                                <td>' . amountExchange($row['totaltax'], $invoice['multi'], $invoice['loc']) . ' (' . amountFormat_s($row['tax']) . '%)</td>
                                                                
                                                <td>' . amountExchange($row['subtotal'], $invoice['multi'], $invoice['loc']) . '</td>
                                            </tr>';

                                            echo '<tr><td colspan=5>' . $row['product_des'] . '</td></tr>';
                                            $c++;
                                        } ?>

                                    </tbody>
                                    <?php
                                    } else {
                                        ?>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <!-- <th><?php echo $this->lang->line('Code') ?></th> -->
                                        <th><?php echo $this->lang->line('Item Name') ?></th>
                                        <th><?php echo $this->lang->line('Item No') ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('Rate') ?></th>
                                        <th class="text-center"><?php echo $this->lang->line('Qty') ?></th>
                                        <?php if($configurations['config_tax']!='0'){ ?>
                                        <th class="text-center"><?php echo $this->lang->line('Tax') ?></th>
                                        <?php } ?>
                                        <th class="text-center"><?php echo $this->lang->line('Discount') ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('Amount') ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php $c = 1;
                                        $sub_t = 0;

                                        foreach ($products as $row) {
                                            $sub_t += $row['price'] * $row['qty'];
                                            echo '<tr>
                                                <th scope="row" class="text-center">' . $c . '</th>';
                                                // <td>' . $row['code'] . '</td>                           
                                               echo ' <td>' . $row['product'] . '</td>   
                                                 <td>' . $row['code'] . '</td>                            
                                                <td class="text-right">' . $row['price'] . '</td>
                                                <td class="text-center">' . intval($row['qty']) ." ". $row['unit'] . '</td>';
                                                if($configurations['config_tax']!='0'){
                                                    echo '<td class="text-center">' . $row['totaltax'] . ' (' . amountFormat_s($row['tax']) . '%)</td>';
                                                }
                                                echo '<td class="text-center">' . $row['totaldiscount'] . ' (' . amountFormat_s($row['discount']) . $this->lang->line($invoice['format_discount']) . ')</td>
                                                <td class="text-right">' . $row['subtotal'] . '</td>
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
                                    <div class="col-md-8">
                                        <p class="lead"><?php echo $this->lang->line('Status') ?>: <u>
                                                <strong id="pstatus"><?php echo $status; ?></strong></u>
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
                                                <?php $sub_total = $sub_t - $invoice['discount']; ?>
                                                <td class="text-right"> <?php echo number_format($sub_total, 2); ?></td>
                                            </tr>
                                            <?php if($configurations['config_tax']!='0'){ ?>
                                            <tr>
                                                <td><?php echo $this->lang->line('TAX') ?></td>
                                                <td class="text-right"><?php echo $invoice['tax'] ?></td>
                                            </tr>
                                            <?php } ?>
                                            <tr>


                                                <td><?php echo $this->lang->line('Total Discount') ?>:</td>

                                                <td class="text-right">
                                                    <?php echo number_format($invoice['discount'], 2); ?></td>
                                            </tr>
                                            <!-- <tr>
                                            <td><?php echo $this->lang->line('Shipping') ?></td>
                                            <td class="text-xs-right"><?php echo number_format($invoice['shipping'], 2); ?></td>
                                        </tr> -->


                                            <tr class="bg-grey bg-lighten-4">
                                                <td class="text-bold-800"><?php echo $this->lang->line('Total') ?></td>
                                                <td class="text-bold-800 text-right">
                                                    <?php
                                                echo ' <span id="paydue">' . number_format($invoice['total'], 2). '</span></strong>'; ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="text-right">
                                    <p><?php echo $this->lang->line('Authorized person') ?></p>
                                    <?php echo '<img src="' . base_url('userfiles/employee_sign/' . $employee['sign']) . '" alt="signature" class="height-100 d-none"/>
                                    <h6>(' . $employee['name'] . ')</h6>'; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Invoice Footer -->

                    <div id="invoice-footer">

                        <div class="row d-none">

                            <div class="col-md-7 col-sm-12">

                                <h6><?php echo $this->lang->line('Terms & Condition') ?></h6>
                                <p> <?php

                                    echo '<strong>' . $invoice['termtit'] . '</strong><br>' . $invoice['terms'];
                                    ?></p>
                            </div>

                        </div>

                    </div>
                    <div class="row  d-none">
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
                    <!--/ Invoice Footer -->

                </div>
            </section>
        </div>
    </div>
</div>