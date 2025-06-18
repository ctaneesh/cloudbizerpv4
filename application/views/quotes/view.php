<div class="content-body">


                  
    <div class="card">
        <div class="card-header border-bottom">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('quote') ?>"><?php echo $this->lang->line('Quotes') ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line('Quote'). " #".$invoice['tid']; ?></li>
                </ol>
            </nav>
            <div class="row">
                <div class="col-xl-4 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                    <h4 class="card-title">
                        <?php echo $this->lang->line('Quote'). " #".$invoice['tid']; ?>
                        <?php if($invoice['status']=="accepted"){ $disabled ="";}else{ $disabled ="disabled"; }?>
                        
                        <?php
                        $approvalpndingcls ="";
                        $approvedcls ="";
                        $changestatuscls ="";
                        $btncls = 'btn-secondary';
                        $convertbtn ="";
                        $sendbtn = '';
                        if($invoice['status']=='pending' || $invoice['status']=='rejected')
                        {
                            $btncls = 'btn-danger';
                            $approvalpndingcls = "disable-class";
                            $approvedcls = "disable-class";
                        }
                        else if($invoice['status']=='accepted')
                        {
                            $convertbtn = 'disable-class';                            
                        }
                        
                        else{
                            $approvedcls = "";
                            $sendbtn = 'disable-class';
                        }


                        if($invoice['convertflg']!='0')
                        {
                            $approvedcls = "disable-class";
                        }
                        $acceptcls="";
                        if($invoice['approvalflg']!='1'){
                            $acceptcls = 'disable-class';
                        }
                        if(($invoice['approvalflg']!=1))
                        {
                            $approvedcls = "disable-class";
                            $changestatuscls = "disable-class";
                            if($invoice['approvalflg']==2){ $status = "Hold"; }
                            else if($invoice['approvalflg']==3){ $status = "Rejected"; }
                            else{
                                $status = "Pending";
                            }
                        }
                        if($invoice['status']=='accepted'){
                                $status = "Ready to Send";
                        }else{
                            $status = $invoice['status'];
                        }
                        
                        ?>
                        <a  href="#pop_model" data-toggle="modal" data-remote="false" class="btn  btn-sm <?=$btncls?> <?=$changestatuscls?>" title="Change Status" ><span  class="fa fa-retweet"></span> <?php echo $this->lang->line('Change Status') ?> </a>
                        
                    </h4>
                    
                </div>
                
                <div class="col-xl-8 col-lg-9 col-md-8 col-sm-12 col-xs-12">  
                   
                        
                    <ul id="trackingbar">
                    <?php if(!empty($trackingdata) && $invoice['approvalflg']==1)
                        {
                            if(!empty($trackingdata['lead_id']))
                            { ?>
                                <li><a href="<?= base_url('invoices/customer_leads?id=' . $trackingdata['lead_id']) ?>" target="_blank">LD #<?= $trackingdata['lead_number']; ?></a></li>
                            
                                <li class="active"><a href="<?= base_url('quote/view?id=' . $invoice['iid']) ?>">QT #<?php echo $invoice['tid'];?></a></li><?php
                            }
                        } ?>
                        
                    </ul>  
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
            <div id="invoice-template" class="card-body">
                <div class="row wrapper white-bg page-heading">
                    <div class="col-lg-4 col-md-9 col-sm-12">
                    
                        <?php
                        print_r( $this->session->userdata('key'));
                        $validtoken = hash_hmac('ripemd160', 'q' . $invoice['iid'], $this->config->item('encryption_key'));

                        $link = base_url('billing/quoteview?id=' . $invoice['iid'] . '&token=' . $validtoken);
                        ?>
                        <div class="title-action">

                                <a href="<?php echo 'edit?id=' . $invoice['iid']; ?>" class="btn btn-sm btn-secondary mb-1 <?php echo $approvedcls; ?>"><i class="fa fa-pencil"></i> <?php echo $this->lang->line('Edit Quote') ?> </a>
                               
                                <div class="btn-group convertto d-none" id="convertto">
                                    <button type="button" class="btn  btn-sm btn-secondary dropdown-toggle mb-1 <?php echo $approvedcls; ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" <?php echo $disabled; ?> ><i class="fa fa-exchange"></i> <?php echo $this->lang->line('Convert') ?>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a href="#pop_model3" data-toggle="modal" data-remote="false" class="dropdown-item mb-1" title="Convert to Purchase"  <?php echo $approvedcls; ?>>
                                            <?php echo $this->lang->line('Purchase Order') ?>
                                        </a>
                                       
                                        <!-- <div class="dropdown-divider"></div>
                                        <a href="#pop_model2" data-toggle="modal" data-remote="false"
                                            class="dropdown-item mb-1" title="Convert to Invoice">
                                            <?php echo $this->lang->line('Invoice') ?>
                                        </a> -->
                                        <?php if($invoice['convertflg']=='0'){ ?>
                                            <div class="dropdown-divider"></div>
                                            <button class="dropdown-item mb-1" title="Sales Order" onclick="convertToSalesOrder1('<?=$invoice['iid']?>')">  <?php echo $this->lang->line('Sales Order') ?> </button>
                                        <?php } ?>
                                    </div>
                                </div>
                            <!-- <a href="<?php echo base_url('quote/salesorders?id=' . $invoice["iid"]); ?>"
                                class="btn  btn-sm btn-secondary  mb-1" title="Sales Order" target="_blank"><span
                                    class="fa fa-venus-double"></span> <?php echo $this->lang->line('Sales Order'); ?>
                            </a> -->
                        
                           
                          <button class="btn btn-sm btn-secondary converttobtn mt-14px <?php echo $approvedcls; ?> <?=$convertbtn?>" title="Sales Order" onclick="convertToSalesOrder1('<?=$invoice['iid']?>')">  <?php echo $this->lang->line('Convert to Sales Order') ?> </button>
              
                            <div class="btn-group">  
                                <button type="button" class="btn  btn-sm btn-secondary dropdown-toggle mb-1 <?php echo $approvedcls." ".$sendbtn; ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"  ><span class="fa fa-envelope"></span> EMail
                                </button>
                                <div class="dropdown-menu"><a href="#sendEmail" data-toggle="modal" data-remote="false"
                                        class="dropdown-item sendbill"
                                        data-type="quote"><?php echo $this->lang->line('Send Proposal') ?></a>
                                </div>

                            </div>

                            <div class="btn-group">
                                <button type="button" class="btn  btn-sm btn-secondary dropdown-toggle mb-1 <?php echo $approvedcls." ".$sendbtn; ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <span class="fa fa-mobile"></span> SMS
                                </button>
                                <div class="dropdown-menu">
                                    <a href="#sendSMS" data-toggle="modal" data-remote="false"  class="dropdown-item sendsms"  data-type="quote"><?php echo $this->lang->line('Send Proposal') ?></a>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-5 col-sm-12 text-center">
                        <?php                      
                        
                            if(($invoice['approvalflg']!=1))
                            {
                           ?>
                               <div class="btn-group alert alert-danger text-center" role="alert">
                                   <?php echo $this->lang->line("Waiting for authorization approval form")." "."<b class='pl5'>".$approvedby['name']."</b>. Current Status : <b>".$status."</b>"; ?>
                               </div>
                           <?php
                           }
                           else if($invoice['status']=="pending" || $invoice['status']=="rejected")
                           {
                                $approvedcls = "disable-class";
                                ?>
                                <div class="btn-group alert alert-danger text-center" role="alert">
                                <?php //echo "<span>".$this->lang->line("change the status").". "."Current Status : <b>".ucfirst($invoice['status'])."</b></span>"; ?>
                                <?php echo "<span>Please Accept the Quote. Current Status : <b>".ucfirst($invoice['status'])."</b></span>"; ?>
                            
                                </div>
                                <?php
                            }
                           else if($invoice['status']=="accepted")
                           {
                                
                                ?>
                                <div class="btn-group alert alert-danger text-center" role="alert">
                                <?php echo "<span>".$this->lang->line("change the status").". "."Current Status : <b>".ucfirst("Ready to Send")."</b></span>"; ?>
                                </div>
                                <?php
                            }
                           
                           else{
                               $approvedcls = "";
                               ?>
                               <div class="btn-group alert alert-success text-center" role="alert">
                                <?php echo "<span>Current Status : <b>".ucfirst($invoice['status'])."</b></span>"; ?>
                                </div>
                                <?php
                           }
                        ?>
                       
                    </div>
                    <div class="col-lg-2 col-md-3 col-sm-12 text-lg-right text-sm-left">
                        <a href="<?php echo $link; ?>" class="btn  btn-sm btn-secondary mb-1" target="_blank" style=""><i class="fa fa-globe"></i> <?php echo $this->lang->line('Preview') ?>
                        </a>
                        <div class="btn-group ">
                            <button type="button" class="btn  btn-sm btn-secondary btn-min-width dropdown-toggle mb-1 <?php echo $approvedcls; ?>"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                    class="fa fa-print"></i> <?php echo $this->lang->line('Print Quote') ?>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" target="_blank"
                                    href="<?= base_url('billing/printquote?id=' . $invoice['iid'] . '&token=' . $validtoken); ?>"><?php echo $this->lang->line('Print') ?></a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" target="_blank"
                                    href="<?= base_url('billing/printquote?id=' . $invoice['iid'] . '&token=' . $validtoken); ?>&d=1"><?php echo $this->lang->line('PDF Download') ?></a>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if ($invoice['multi'] > 0) {

                    echo '<div class="tag tag-info text-xs-center">' . $this->lang->line('Payment currency is different') . '</div>';
                }
                if($invoice['status']=='accepted' && $invoice['approvalflg']=='1'){
                    $status = "Ready to Send";
                }
                else if($invoice['status']=='accepted' && $invoice['approvalflg']!='1'){
                    $status  = 'Pending';
                }
                else{
                    $status = $invoice['status'];
                }
                ?>

                <!-- Invoice Company Details -->
                <div id="invoice-company-details" class="row">
                    <div class="col-md-6 col-sm-12 text-xs-center text-md-left">
                        <p></p>
                        <img src="<?php $loc = location($invoice['loc']);
                        echo base_url('userfiles/company/' . $loc['logo']) ?>" class="img-responsive p-1 m-b-2"
                            style="max-height: 120px;">
                        <p class="ml-2"><?= $loc['cname'] ?></p>
                    </div>
                    <div class="col-md-6 col-sm-12 text-xs-center text-md-right">
                        <!-- <h2><?php echo $this->lang->line('Quote') ?></h2> -->
                        <p class=""> <?php echo prefix(1) . $invoice['tid'] . '</p>
                            <p class="">' . $this->lang->line('Reference') . ':' . $invoice['refer'] . '</p>'; ?>
                        <p>
                            <?php echo $this->lang->line('Current Status') ?> : <u>
                                <?php 
                                    if($invoice['status']=='accepted' || $invoice['status']=='Customer PO Received')
                                    {
                                        $cls = "";
                                    }
                                    else{
                                        $cls = "text-danger";
                                    }
                                ?>
                                <strong id="pstatus" class="<?=$cls?>"><?php echo ucwords($status); ?></strong></u>
                        </p>
                        <ul class="px-0 list-unstyled">
                            <li><?php echo $this->lang->line('Gross Amount') ?><span class="lead text-bold-600">
                                <?php echo amountExchange($invoice['total'], 0, $this->aauth->get_user()->loc) ?></span></li>
                        </ul>
                    </div>
                </div>
                <!--/ Invoice Company Details -->

                <!-- Invoice Customer Details -->
                <div id="invoice-customer-details" class="row">
                    <div class="col-sm-12 text-xs-center text-md-left">
                        <p class="text-muted"><?php echo $this->lang->line('Bill To') ?></p>
                    </div>
                    <div class="col-md-6 col-sm-12 text-xs-center text-md-left">
                        <ul class="px-0 list-unstyled">


                            <li class="text-bold-600"><a
                                    href="<?php echo base_url('customers/view?id=' . $invoice['cid']) ?>"><strong
                                        class="invoice_a"><?php echo $invoice['name'] . '</strong></a></li><li>' . $invoice['company'] . '</li><li>' . $invoice['address'] . ', ' . $invoice['city'] . ',' . $invoice['region'] . '</li><li>' . $invoice['country'] . ',' . $invoice['postbox'] . '</li><li>' . $this->lang->line('Phone') . ': ' . $invoice['phone'] . '</li><li>' . $this->lang->line('Email') . ': ' . $invoice['email'];
                                        if ($invoice['tax_id']) echo '</li><li>' . $this->lang->line('Tax') . ' ID: ' . $invoice['tax_id']
                                        ?>
                            </li>
                        </ul>

                    </div>
                    <div class="col-md-6 col-sm-12 text-right">
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
                                        <th class="text-xs-left"><?php echo $this->lang->line('Amount') ?> (<?php echo $this->config->item('currency'); ?>)</th>
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
                                        <td>' . amountExchange($row['price'], 0, $this->aauth->get_user()->loc) . '</td>
                                        <td>' . intval($row['qty']) . $row['unit'] . '</td>
                                        <td>' . amountExchange($row['totaldiscount'], 0, $this->aauth->get_user()->loc) . ' (' . amountFormat_s($row['discount']) . $this->lang->line($invoice['format_discount']) . ')</td>
                                        <td>' . amountExchange($gst, 0, $this->aauth->get_user()->loc) . ' (' . amountFormat_s($rate) . '%)</td>
                                        <td>' . amountExchange($gst, 0, $this->aauth->get_user()->loc) . ' (' . amountFormat_s($rate) . '%)</td>                           
                                        <td>' . amountExchange($row['subtotal'], 0, $this->aauth->get_user()->loc) . '</td>
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

                                    <th class="text-xs-left"><?php echo $this->lang->line('Amount') ?>(<?php echo $this->config->item('currency'); ?>)</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <?php $c = 1;
                                    $sub_t = 0;

                                    foreach ($products as $row) {
                                        $sub_t += $row['price'] * $row['qty'];

                                        echo '<tr>
                                            <th scope="row" class="text-center">' . $c . '</th>
                                            <td>' . $row['product'] . '</td> 
                                            <td>' . $row['code'] . '</td>                          
                                            <td>' . amountExchange($row['price'], 0, $this->aauth->get_user()->loc) . '</td>
                                            <td>' . intval($row['qty']) . $row['unit'] . '</td>
                                            <td>' . amountExchange($row['totaldiscount'], 0, $this->aauth->get_user()->loc) . ' (' . amountFormat_s($row['discount']) . $this->lang->line($invoice['format_discount']) . ')</td>
                                            <td>' . amountExchange($row['totaltax'], 0, $this->aauth->get_user()->loc) . ' (' . amountFormat_s($row['tax']) . '%)</td>
                                                            
                                            <td>' . amountExchange($row['subtotal'], 0, $this->aauth->get_user()->loc) . '</td>
                                        </tr>';

                                        echo '<tr><td colspan=8>' . $row['product_des'] . '</td></tr>';
                                        $c++;
                                    } ?>

                                </tbody>
                                <?php
                                } else {
                                    ?>
                                <tr>
                                    <th class="text-center">#</th>
                                    <!-- <th><?php echo $this->lang->line('Code') ?></th> -->
                                    <th><?php echo $this->lang->line('Item Name & No') ?></th>
                                    <th class="text-right"><?php echo $this->lang->line('Rate') ?></th>
                                    <th class="text-center"><?php echo $this->lang->line('Qty') ?></th>
                                    <?php if($configurations['config_tax']!='0'){ ?>
                                        <th class="text-xs-left"><?php echo $this->lang->line('Tax') ?></th>
                                    <?php } ?>
                                    <th class="text-center"><?php echo $this->lang->line('Discount') ?></th>
                                    <th class="text-right"><?php echo $this->lang->line('Amount') ?> (<?php echo $this->config->item('currency'); ?>)</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <?php $c = 1;
                                    $sub_t = 0;

                                    foreach ($products as $row) {
                                        $sub_t += $row['price'] * $row['qty'];
                                        if($row['qty']>0){
                                            echo '<tr>';
                                        }
                                        else{
                                            echo '<tr style="background:#ffb9c2;">';
                                        }
                                        
                                            echo '<th scope="row" class="text-center">' . $c . '</th>                    
                                            <td>' . $row['product'] . '</td>                           
                                            <td class="text-right">' . $row['price'] . '</td>
                                            <td class="text-center">' . intval($row['qty']) ." ". $row['unit'] . '</td>';
                                            if($configurations['config_tax']!='0'){ 
                                                echo '<td  class="text-center">' . $row['tax'].'/'. $row['totaltax'].'</td>';
                                            }
                                            if($row['discount_type']=="Amttype"){
                                                $distype = 'Amt';
                                            }
                                            else{
                                                $distype = '%';
                                            }
                                            echo '<td  class="text-center">' . $row['totaldiscount'] . ' (' . amountFormat_s($row['discount'])." ". $distype.')</td>';
                                            echo '<td class="text-right">' . $row['subtotal'] . '</td>
                                        </tr>';

                                        // echo '<tr><td colspan=8>' . $row['product_des'] . '</td></tr>';
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


                            <!-- <div class="row">
                                <div class="col-md-8">
                                    <p class="lead"><?php echo $this->lang->line('Status') ?>: <u><strong
                                                id="pstatus"><?php echo $this->lang->line(ucwords($invoice['status'])) ?></strong></u>
                                    </p>
                                    <p class="lead mt-1"><br><?php echo $this->lang->line('Note') ?>:</p>
                                    <code>
                                        <?php echo $invoice['notes'] ?>
                                    </code>
                                </div>
                            </div> -->
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <p class="lead"><?php echo $this->lang->line('Total Due') ?> (<?php echo $this->config->item('currency'); ?>)</p>
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td><?php echo $this->lang->line('Sub Total') ?></td>
                                            <td class="text-xs-right">
                                                <b><?php echo $sub_t; ?></b>
                                            </td>
                                        </tr>
                                        <?php if($configurations['config_tax']!='0'){ ?>
                                            <tr>
                                                <td class="no-border"><?php echo $this->lang->line('Tax') ?></td>
                                                <td class="text-xs-right no-border">
                                                    <b><?php echo $invoice['tax']; ?></b>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        <tr>
                                            <td class="no-border"><?php echo $this->lang->line('Discount') ?></td>
                                            <td class="text-xs-right no-border">
                                                <b><?php echo $invoice['discount']; ?></b>
                                            </td>
                                        </tr>
                                        <!-- <tr>
                                            <td class="no-border"><?php echo $this->lang->line('Shipping') ?></td>
                                            <td class="text-xs-right no-border">
                                                <b><?php //echo $invoice['shipping']; ?></b>
                                            </td>
                                        </tr> -->
                                        <tr>
                                            <td class="text-bold-800 "><?php echo $this->lang->line('Total') ?></td>
                                            <td class="text-bold-800 text-xs-right">
                                                <b><?php echo $invoice['total']; ?></b>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-right">
                                <?php if(!empty($employee['sign'])){ ?>
                                <p><?php
                                //  echo $this->lang->line('Authorized person') ?></p>
                                <?php 
                                    // echo '<img src="' . base_url('userfiles/employee_sign/' . $employee['sign']) . '" alt="signature" class="height-100"/>';
                                    //  echo '<h6>' . $employee['name'] . '</h6>';
                                    //<p class="text-muted">' . user_role($employee['roleid']) . '</p>';
                                     }?>

                                     <!-- convert button starts  -->                                    

                                    <div class="btn-group mt-1 convertto d-none" id="convertto">
                                        <button type="button" class="btn  btn-lg btn-primary dropdown-toggle mb-1 <?php echo $approvedcls; ?>"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" <?php echo $disabled; ?> ><i
                                                class="fa fa-exchange"></i> <?php echo $this->lang->line('Convert') ?>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a href="#pop_model3" data-toggle="modal" data-remote="false" class="dropdown-item mb-1" title="Convert to Purchase"  <?php echo $approvedcls; ?>>
                                                <?php echo $this->lang->line('Purchase Order') ?>
                                            </a>
                                            <?php if($invoice['convertflg']=='0'){ ?>
                                                <div class="dropdown-divider"></div>
                                                <button class="dropdown-item mb-1" title="Sales Order" onclick="convertToSalesOrder1('<?=$invoice['iid']?>')">  <?php echo $this->lang->line('Sales Order') ?> </button>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <input type="hidden" name="approvalflg" id="approvalflg" value="<?=$invoice['approvalflg']?>">
                                    
                                    <button type="button"  class="btn btn-lg btn-secondary <?=$acceptcls?>" title="Accept" id="accept_btn" >  <?php echo $this->lang->line('Accept') ?> </button>

                                    <button class="btn btn-lg btn-primary converttobtn <?php echo $approvedcls; ?> <?=$convertbtn?>" title="Sales Order" onclick="convertToSalesOrder1('<?=$invoice['iid']?>')" >  <?php echo $this->lang->line('Convert to Sales Order') ?> </button>
                        
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Invoice Footer -->

                <div id="invoice-footer d-none">


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
                <pre><?php echo $this->lang->line('Public Access URL') ?>: <?php
                    echo $link ?></pre>

                <div class="col-12 row d-none">
                    <table class="table table-striped table-bordered zero-configuration dataTable d-none">
                        <thead>
                            <tr>
                                <th><?php echo $this->lang->line('Files') ?></th>


                            </tr>
                        </thead>
                        <tbody id="activity">
                            <?php foreach ($attach as $row) {

                            echo '<tr><td><a data-url="' . base_url() . 'quote/file_handling?op=delete&name=' . $row['col1'] . '&invoice=' . $invoice['iid'] . '" class="aj_delete"><i class="btn-secondary btn-sm fa fa-trash"></i></a> <a class="n_item" href="' . base_url() . 'userfiles/attach/' . $row['col1'] . '"> ' . $row['col1'] . ' </a></td></tr>';
                        } ?>

                        </tbody>
                    </table>
                </div>
                <div class="card1 d-none">
                    <pre>Allowed: gif, jpeg, png, docx, docs, txt, pdf, xls </pre>
                    <br>
                    <!-- The fileinput-button span is used to style the file input field as button -->
                    <div class="btn btn-outline-light fileinput-button">
                        <i class="glyphicon glyphicon-plus"></i>
                        <span>Select files...</span>
                        <!-- The file input field used as target for the file upload widget -->
                        <input id="fileupload" type="file" name="files[]" multiple>
                    </div>
                </div>
                <!-- The global progress bar -->
                <div id="progress" class="progress d-none">
                    <div class="progress-bar progress-bar-success"></div>
                </div>
                <!-- The container for the uploaded files -->
                <table id="files" class="files"></table>
                <br>

            </div>
        </div>
    </div>
</div>


<!-- Modal HTML -->

<div id="pop_model" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">

                <h4 class="modal-title"><?php echo $this->lang->line('Change Status') ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>

            <div class="modal-body">
                <form id="form_model">


                    <div class="row">
                        <div class="col"><label for="pmethod"><?php echo $this->lang->line('Mark As') ?></label>
                            <select name="status" class="form-control mb-1" id="statusval">
                                <option value="pending" <?php if($invoice['status']=="pending"){ echo "selected"; }?>><?php echo $this->lang->line('Pending') ?></option>
                                <option value="accepted" <?php if($invoice['status']=="accepted"){ echo "selected"; }?>><?php echo $this->lang->line('Ready to Send') ?></option>
                                <option value="Customer PO Received" <?php if($invoice['status']=="Customer PO Received"){ echo "selected"; }?>><?php echo $this->lang->line('Customer PO Received') ?></option>
                                <option value="rejected" <?php if($invoice['status']=="rejected"){ echo "selected"; }?>><?php echo $this->lang->line('Rejected') ?></option>
                            </select>

                        </div>
                    </div>

                    <div class="modal-footer">
                        <input type="hidden" class="form-control required" name="tid" id="invoiceid"
                            value="<?php echo $invoice['iid'] ?>">
                        <button type="button" class="btn  btn-md btn-secondary"
                            data-dismiss="modal"><?php echo $this->lang->line('Close') ?></button>
                        <input type="hidden" id="action-url" value="quote/update_status">
                        <button type="button" class="btn  btn-md btn-primary"
                            id="submit_model123"><?php echo $this->lang->line('Change Status') ?></button>
                    </div>
                    
                </form>
            </div>
        </div>
    </div>
</div>

<div id="pop_model2" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">

                <h4 class="modal-title"><?php echo $this->lang->line('Change Status') ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>

            <div class="modal-body">
                <form id="form_model2">


                    <div class="row">
                        <div class="col"><?php echo $this->lang->line('quote as invoice') ?>


                        </div>
                    </div>

                    <div class="modal-footer">
                        <input type="hidden" class="form-control required" name="tid" id="invoiceid"
                            value="<?php echo $invoice['iid'] ?>">
                        <input type="hidden" class="form-control required" name="type" id="type" value="0">
                        <button type="button" class="btn  btn-md btn-secondary"
                            data-dismiss="modal"><?php echo $this->lang->line('Close') ?></button>
                        <input type="hidden" id="action-url" value="quote/convert">
                        <button type="button" class="btn  btn-md btn-primary"
                            id="submit_model2"><?php echo $this->lang->line('Yes') ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div id="pop_model3" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">

                <h4 class="modal-title"><?php echo $this->lang->line('Convert to Purchase') ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>

            <div class="modal-body">
                <form id="form_model3">


                    <div class="form-group row">
                        <div class="frmSearch col-sm-12"><label for="cst"
                                class="col-form-label"><?php echo $this->lang->line('Search Supplier') ?> </label>
                            <input type="text" class="form-control" name="cst" id="supplier-box"
                                placeholder="Enter Supplier Name or Mobile Number to search" autocomplete="off" />

                            <div id="supplier-box-result"></div>
                        </div>

                    </div>
                    <div id="customer">
                        <div class="clientinfo">
                            <label class="col-form-label"><?php echo $this->lang->line('Supplier Details') ?></label>
                            <hr>
                            <input type="hidden" name="customer_id" id="customer_id" value="0">
                            <div id="customer_name"></div>
                        </div>
                        <div class="clientinfo">

                            <div id="customer_address1"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" class="form-control required" name="tid" id="invoiceid"
                            value="<?php echo $invoice['iid'] ?>">
                        <button type="button" class="btn  btn-md btn-secondary"
                            data-dismiss="modal"><?php echo $this->lang->line('Close') ?></button>
                        <input type="hidden" id="action-url" value="quote/convert_po">
                        <button type="button" class="btn  btn-md btn-primary"
                            id="submit_model3"><?php echo $this->lang->line('Yes') ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal HTML -->
<div id="sendEmail" class="modal fade">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">

                <h4 class="modal-title">Send Proposal</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div id="request">
                <div id="ballsWaveG">
                    <div id="ballsWaveG_1" class="ballsWaveG"></div>
                    <div id="ballsWaveG_2" class="ballsWaveG"></div>
                    <div id="ballsWaveG_3" class="ballsWaveG"></div>
                    <div id="ballsWaveG_4" class="ballsWaveG"></div>
                    <div id="ballsWaveG_5" class="ballsWaveG"></div>
                    <div id="ballsWaveG_6" class="ballsWaveG"></div>
                    <div id="ballsWaveG_7" class="ballsWaveG"></div>
                    <div id="ballsWaveG_8" class="ballsWaveG"></div>
                </div>
            </div>
            <div class="modal-body" id="emailbody" style="display: none;">
                <form id="sendbill">
                    <div class="row">
                        <div class="col">
                            <label for="email" class="col-form-label"><?php echo $this->lang->line('Email') ?></label>
                            <div class="input-group">
                                <div class="input-group-addon"><span class="icon-envelope-o" aria-hidden="true"></span>
                                </div>
                                <input type="text" class="form-control" placeholder="Email" name="mailtoc"
                                    value="<?php echo $invoice['email'] ?>">
                            </div>

                        </div>

                    </div>


                    <div class="row">
                        <div class="col"><label for="shortnote"
                                class="col-form-label"><?php echo $this->lang->line('Customer Name') ?></label>
                            <input type="text" class="form-control" name="customername"
                                value="<?php echo $invoice['name'] ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col"><label for="shortnote"
                                class="col-form-label"><?php echo $this->lang->line('Subject') ?></label>
                            <input type="text" class="form-control" name="subject" id="subject">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col"><label for="shortnote"
                                class="col-form-label"><?php echo $this->lang->line('Message') ?></label>
                            <textarea name="text" class="summernote" id="content" title="Contents"></textarea>
                        </div>
                    </div>

                    <input type="hidden" class="form-control" id="invoiceid" name="tid"
                        value="<?php echo $invoice['iid'] ?>">
                    <input type="hidden" class="form-control" id="emailtype" value="">


                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    data-dismiss="modal"><?php echo $this->lang->line('Close') ?></button>
                <button type="button" class="btn btn-primary"
                    id="sendM"><?php echo $this->lang->line('Send') ?></button>
            </div>
        </div>
    </div>
</div>
<!--sms-->
<!-- Modal HTML -->
<div id="sendSMS" class="modal fade">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">

                <h4 class="modal-title"><?php echo $this->lang->line('Send'); ?> SMS</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div id="request_sms">
                <div id="ballsWaveG1">
                    <div id="ballsWaveG_1" class="ballsWaveG"></div>
                    <div id="ballsWaveG_2" class="ballsWaveG"></div>
                    <div id="ballsWaveG_3" class="ballsWaveG"></div>
                    <div id="ballsWaveG_4" class="ballsWaveG"></div>
                    <div id="ballsWaveG_5" class="ballsWaveG"></div>
                    <div id="ballsWaveG_6" class="ballsWaveG"></div>
                    <div id="ballsWaveG_7" class="ballsWaveG"></div>
                    <div id="ballsWaveG_8" class="ballsWaveG"></div>
                </div>
            </div>
            <div class="modal-body" id="smsbody" style="display: none;">
                <form id="sendsms">
                    <h3>Coming soon...</h3>
                    <!-- <div class="row">
                        <div class="col">
                            <label for="phone" class="col-form-label"><?php echo $this->lang->line('Phone') ?></label>
                            <div class="input-group">
                                <div class="input-group-addon"><span class="icon-envelope-o" aria-hidden="true"></span>
                                </div>
                                <input type="text" class="form-control" placeholder="SMS" name="mobile"
                                    value="<?php echo $invoice['phone'] ?>">
                            </div>

                        </div>

                    </div>


                    <div class="row">
                        <div class="col"><label for="shortnote"
                                class="col-form-label"><?php echo $this->lang->line('Customer Name'); ?></label>
                            <input type="text" class="form-control" value="<?php echo $invoice['name'] ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col"><label for="shortnote"
                                class="col-form-label"><?php echo $this->lang->line('Message'); ?></label>
                            <textarea class="form-control summernote" name="text_message" id="sms_tem" title="Contents"
                                rows="3"></textarea>
                        </div>
                    </div>


                    <input type="hidden" class="form-control" id="smstype" value=""> -->


                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    data-dismiss="modal"><?php echo $this->lang->line('Close'); ?></button>
                <!-- <button type="button" class="btn btn-primary"id="submitSMS"><?php echo $this->lang->line('Send'); ?></button> -->
            </div>
        </div>
    </div>
</div>
<script src="<?php echo assets_url('assets/myjs/jquery.ui.widget.js') ?>"></script>
<script src="<?php echo assets_url('assets/myjs/jquery.fileupload.js') ?>"></script>
<script>
/*jslint unparam: true */
/*global window, $ */
$(function() {
    'use strict';
    // Change this to the location of your server-side upload handler:
    var url = '<?php echo base_url() ?>quote/file_handling?id=<?php echo $invoice['iid'] ?>';
    $('#fileupload').fileupload({
            url: url,
            dataType: 'json',
            formData: {
                '<?=$this->security->get_csrf_token_name()?>': crsf_hash
            },
            done: function(e, data) {
                $.each(data.result.files, function(index, file) {
                    $('#files').append(
                        '<tr><td><a data-url="<?php echo base_url() ?>quote/file_handling?op=delete&name=' +
                        file.name +
                        '&invoice=<?php echo $invoice['iid'] ?>" class="aj_delete red"><i class="btn-sm fa fa-trash"></i></a> ' +
                        file.name + ' </td></tr>');
                });
            },
            progressall: function(e, data) {
                var progress = parseInt(data.loaded / data.total * 100, 10);
                $('#progress .progress-bar').css(
                    'width',
                    progress + '%'
                );
            }
        }).prop('disabled', !$.support.fileInput)
        .parent().addClass($.support.fileInput ? undefined : 'disabled');
});

$(document).on('click', ".aj_delete", function(e) {
    e.preventDefault();
    var aurl = $(this).attr('data-url');
    var obj = $(this);
    jQuery.ajax({
        url: aurl,
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            obj.closest('tr').remove();
            obj.remove();
        }
    });

});
</script>
<script type="text/javascript">
$(function() {
    $('.summernote').summernote({
        height: 100,
        toolbar: [
            // [groupName, [list of button]]
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
            ['fullscreen', ['fullscreen']],
            ['codeview', ['codeview']]
        ]
    });

    $('#sendM').on('click', function(e) {
        e.preventDefault();

        sendBill($('.summernote').summernote('code'));

    });
});
$(document).ready(function() {
    $("#statusval").on("change", function() {
        // if ($(this).val() === "accepted") {
        //     $(".convertto").removeClass("d-none");
        // } else {
        //     $(".convertto").addClass("d-none");
        // }
    });
});

$("#submit_model123").on("click", function() {
    var tid = $("#invoiceid").val();
    var status = $("#statusval").val();
    $.ajax({
        type: 'POST',
        url: 'update_status', 
        data: { tid: tid, status: status },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'Success') {
                // alert(response.message);
                Swal.fire({
                    text: response.message,
                    icon: "success"
                });
                location.reload(); // Refresh the page
            } else {
                Swal.fire({
                    text: "Failed to update status",
                    icon: "danger"
                });
            }
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
            Swal.fire({
                text: "An error occurred while updating the status. Please try again.",
                icon: "danger"
            });
        }
    });
});

$("#accept_btn").on('click', function(){
    quoteid = $("#invoiceid").val(); 
    Swal.fire({
        title: "Are you sure?",
        text: "Do you want to accept this quote?",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, proceed!',
        cancelButtonText: "No - Cancel",
        reverseButtons: true,  
        focusCancel: true,      
        allowOutsideClick: false,  // Disable outside click
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = baseurl + 'quote/edit?id='+quoteid; 
        }
    });
});


</script>