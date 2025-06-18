<div class="app-content content container-fluid">
    <div class="content-wrapper">
        <div id="notify" class="alert alert-success" style="display:none;">
            <a href="#" class="close" data-dismiss="alert">&times;</a>

            <div class="message"></div>
        </div>
<?php

if($this->session->flashdata('quoteupdate')) {
$message = $this->session->flashdata('quoteupdate');
?>
<div class="alert alert-info"><?php echo $message['message']; ?>

</div>
<?php
}

?>
        <div class="content-body">
            <form method="post" id="data_form" action="saveQuoteChanges">
                <section class="card">
                    <div id="invoice-template" class="card-block">
                        <div class="row wrapper white-bg page-heading">

                            <div class="col-lg-12">
                                <?php
                                

                                $validtoken = hash_hmac('ripemd160', 'q' . $invoice['iid'], $this->config->item('encryption_key'));


                                $link = '../../billing/printquote?id=' . $invoice['iid'] . '&token=' . $validtoken;
                                $linkp = '../../billing/print_rec?id=' . $invoice['iid'] . '&token=' . $validtoken;
                                ?>
                                <div class="title-action">

                                    <div class="btn-group ">
                                        <button type="button" class="btn btn-success btn-min-width dropdown-toggle mb-1"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                                    class="icon-print"></i> <?php echo $this->lang->line('Print Quote') ?>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item"
                                            href="<?php echo $link ?>"><?php echo $this->lang->line('Print') ?></a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item"
                                            href="<?php echo $link ?>&d=1"><?php echo $this->lang->line('PDF Download') ?></a>

                                        </div>  
                                        <?php
                                        if($invoice['status']=='pending') {
                                        ?>
                                        <a class="btn btn-info round ml-1" href="<?php echo base_url('quote/approve') ?>?&id=<?=$invoice['iid'] ?>" title="Approve"><i
                                                    class="icon-check"></i> Approve</a>
                                        <a class="btn btn-warning round ml-1" href="<?php echo base_url('quote/editquote') ?>?&id=<?=$invoice['iid'] ?>" title="Approve"><i class="icon-book"></i> Update Quote</a>
                                        <button type="submit" class="btn btn-success">Save All Changes</button>
                                        <?php
                                        }
                                        ?>
                                    </div>



                                </div>
                            </div>
                        </div>

                        <!-- Invoice Company Details -->
                        <div id="invoice-company-details" class="row mt-2">
                            <div class="col-md-6 col-sm-12 text-xs-center text-md-left"><p></p>
                                <img src="../../userfiles/company/<?php echo $this->config->item('logo') ?>"
                                    class="img-responsive p-1 m-b-2" style="max-height: 120px;">
                                <p class="text-muted"><?php echo $this->lang->line('From') ?></p>


                                <ul class="px-0 list-unstyled">
                                    <?php echo '<li class="text-bold-800">' . $this->config->item('ctitle') . '</li><li>' .
                                        $this->config->item('address') . '</li><li>' . $this->config->item('city') . '</li><li>Phone: ' . $this->config->item('phone') . '</li><li> Email: ' . $this->config->item('email'); ?>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6 col-sm-12 text-xs-center text-md-right">
                                <h2><?php echo $this->lang->line('QUOTE') ?></h2>
                                <p class="pb-1"> <?php echo $this->config->item('prefix') . ' #' . $invoice['tid'] . '</p>
                                <p class="pb-1">Reference:' . $invoice['refer'] . '</p>'; ?>
                                <ul class="px-0 list-unstyled">
                                    <li><?php echo $this->lang->line('Gross Amount') ?></li>
                                    <li class="lead text-bold-600"><?php echo amountExchange($invoice['total'], $invoice['multi'] ) ?></li>
                                </ul>
                            </div>
                        </div>
                        <!--/ Invoice Company Details -->

                        <!-- Invoice Customer Details -->
                        <div id="invoice-customer-details" class="row pt-2">
                            <div class="col-sm-12 text-xs-center text-md-left">
                                <p class="text-muted"><?php echo $this->lang->line('Bill To') ?></p>
                            </div>
                            <div class="col-md-6 col-sm-12 text-xs-center text-md-left">
                                <ul class="px-0 list-unstyled">


                                    <li class="text-bold-600"><a
                                            ><strong
                                                    class="invoice_a"><?php echo $invoice['name'] . '</strong></a></li><li>' . $invoice['address'] . '</li><li>' . $invoice['city'] . ',' . $invoice['country'] . '</li><li>' . $this->lang->line('Phone') . ': ' . $invoice['phone'] . '</li><li>Email: ' . $invoice['email']; ?>
                                    </li>
                                </ul>

                            </div>
                            <div class="offset-md-3 col-md-3 col-sm-12 text-xs-center text-md-left">
                                <?php echo '<p><span class="text-muted">' . $this->lang->line('Quote Date') . ' :</span> ' . dateformat($invoice['invoicedate']) . '</p> <p><span class="text-muted">' . $this->lang->line('Due Date') . ' :</span> ' . dateformat($invoice['invoiceduedate']) . '</p>  <p><span class="text-muted">' . $this->lang->line('Terms') . ' :</span> ' . $invoice['termtit'] . '</p>';
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
                                    <table class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th><?php echo $this->lang->line('Description') ?></th>
                                            <th class="text-xs-left"><?php echo $this->lang->line('Quantity') ?></th>
                                            <th class="text-xs-left"><?php echo $this->lang->line('Rate') ?></th>
                                            <th class="text-xs-left"><?php echo $this->lang->line('Tax(%)') ?></th>
                                            <th class="text-xs-left"><?php echo $this->lang->line('Discount') ?></th>
                                            <th class="text-xs-left"><?php echo $this->lang->line('Amount') ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $i = 0;
                                        // echo "<pre>";

                                        echo '<input type="hidden" name="targetinvoice" value="'.$id. '" id="targetinvoice" />';
                                        echo '<input type="hidden" name="invoicenumber" value="'.$invoice['tid']. '" id="invoicenumber" />';
                                        echo '<input type="hidden" name="prdcount" value="'.count($products). '" id="prdcount" />';
                                      
                                        foreach ($products as $row) {
                                        $productID = $row['id'];
                                        echo '<tr>';
                                        echo '<td>' . $row['product'] . '</td>';
                                        
                                        //echo ' <td><input type="text" class="form-control req amnt" name="product_qty[]" id="amount-' . $i . '" onkeypress="return isNumber(event)" onkeyup="rowTotal2(\''.$i.'\',\''.this.value.'\') autocomplete="off" value="' . amountFormat_general($row['qty']) . '" ><input type="hidden" name="old_product_qty[]" value="' . amountFormat_general($row['qty']) . '" ></td>';

                                        echo '<td><input type="text" class="form-control req amnt" name="product_qty[]" id="newqty-'.$i.'" onkeypress="return isNumber(event)" onkeyup="rowTotal2(\''.$i.'\',\''.$row['price'].'\',\''.$row['tax'].'\',\''.$row['discount'].'\')" autocomplete="off" value="' . amountFormat_general($row['qty']) . '" /><input type="hidden" name="old_product_qty[]" value="' . amountFormat_general($row['qty']) . '" id="oldqty-'.$i.'" /></td>';

                                        echo '<input type="hidden" name="productNo[]" value="'.$row['id']. '" />';
                                        echo '<input type="hidden" name="tnumber[]" value="'.$row['tid']. '" />';
                                        echo '<input type="hidden" name="eachproducttax[]" id="eachproducttax-'.$i.'" />';
                                        echo '<input type="hidden" name="eachproductdiscount[]" id="eachproductdiscount-'.$i.'" />';
                                        
                                        echo '<td>' . amountExchange_s($row['price'], $invoice['multi']) .'</td>';

                                        echo '<td class="text-center" id="texttaxa-'.$i.'">' . amountExchange_s($row['tax'], $invoice['multi']) . '%</td>';

                                        echo ' <td>'. amountFormat_general($row['discount']) . '%</td>';
                                        echo '<input type="hidden" name="old_subtotal[]" value="' . amountFormat_general($row['subtotal']) . '" id="oldsubtotal-'.$i.'" />';
                                        echo '<input type="hidden" name="tax[]" value="' . amountFormat_general($row['tax']) . '" id="tax-'.$i.'" />';
                                        echo '<input type="hidden" name="discount[]" value="' . amountFormat_general($row['discount']) . '" id="discount-'.$i.'" />';
                                        echo '<input type="hidden" name="price[]" value="' . amountFormat_general($row['price']) . '" id="price-'.$i.'" />';
                                        echo '<input type="hidden" name="new_subtotal[]" value="' . amountFormat_general($row['subtotal']) . '" id="newsubtotal-'.$i.'" />';
                                        echo '<td><span class="currenty">' . $this->config->item('currency') .'
                                        <strong><span class="ttlText" id="result-'.$i.'">' . $row['subtotal'] . '</span></strong></span></td>';
                                    

                                        echo '</tr>';

                                        echo '<tr class="desc_p"><td colspan="8"><textarea id="dpid-'.$i.'" class="form-control" name="product_description[]" placeholder="Enter Product description" autocomplete="off">' . $row['product_des'] . '</textarea><br></td></tr>';
                                        $i++;
                                    } ?>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <p></p>
                            <div class="row">
                                <div class="col-md-7 col-sm-12 text-xs-center text-md-left">


                                    <div class="row">
                                        <div class="col-md-8">

                                            <p class="lead"><?php echo $this->lang->line('Quote Status') ?>: <u><strong
                                                            id="pstatus"><?php echo $this->lang->line(ucwords($invoice['status'])) ?></strong></u>
                                            </p>
                                            <p class="lead mt-1"><br><?php echo $this->lang->line('Note') ?>:</p>
                                            <code>
                                                <?php echo $invoice['notes'] ?>
                                            </code>
                                        </div>
                                    </div>
                                </div>
                                <?php 
                                    echo '<input type="hidden" name="invoicesubtotal" value="'.$invoice['subtotal'].'" id="invoicesubtotal" />';
                                    echo '<input type="hidden" name="invoicetax" value="' .$invoice['tax']. '" id="invoicetax" />';
                                    echo '<input type="hidden" name="invoicediscount" value="' .$invoice['discount']. '" id="invoicediscount" />';
                                    echo '<input type="hidden" name="invoicetotal" value="' .$invoice['total']. '" id="invoicetotal" />';
                                ?>
                                <div class="col-md-5 col-sm-12">
                                    <p class="lead"><?php echo $this->lang->line('Total Due') ?></p>
                                    <div class="table-responsive">
                                        <table class="table">
                                            <tbody>
                                            <tr>
                                                <td><?php echo $this->lang->line('Sub Total') ?></td>
                                                <td class="text-xs-right">                                                    
                                                    <?php echo amountExchange($sub_t, $invoice['multi']) ?></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo $this->lang->line('TAX') ?></td>
                                                <td class="text-xs-right" id="totalTax"><?php echo amountExchange($invoice['tax'], $invoice['multi']) ?></td>
                                            </tr>
                                            <tr>
                                            <td><?php echo $this->lang->line('Discount') ?></td>
                                            <td class="text-xs-right" id="totalDiscountTxt"><?php echo amountExchange($invoice['discount'], $invoice['multi']) ?></td>
                                        </tr>
                                            <tr>
                                                <td><?php echo $this->lang->line('Shipping') ?></td>
                                                <td class="text-xs-right"><?php echo amountExchange($invoice['shipping'], $invoice['multi']) ?></td>
                                            </tr>


                                            <tr class="bg-grey bg-lighten-4">
                                                <td class="text-bold-800"><?php echo $this->lang->line('Total') ?></td>
                                                <td class="text-bold-800 text-xs-right"> <?php

                                                    echo ' <span id="paydue">' . amountExchange($invoice['total'], $invoice['multi']) . '</span></strong>'; ?></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="text-xs-center">
                                        <p><?php echo $this->lang->line('Authorized person') ?></p>
                                        <?php echo '<img src="../../userfiles/employee_sign/' . $employee['sign']. '" alt="signature" class="height-100"/>
                                        <h6>(' . $employee['name'] . ')</h6>
                                        <p class="text-muted">' . user_role($employee['roleid']) . '</p>'; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Invoice Footer -->

                        <div id="invoice-footer">


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
            </form>
        </div>
    </div>
</div>

