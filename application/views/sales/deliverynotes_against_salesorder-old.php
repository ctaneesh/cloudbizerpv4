<div class="content-body">
    <div class="card">
        <div class="card-header border-bottom">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('DeliveryNotes') ?>">Delivery Notes</a></li>                   
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line('Delivery Note Landing'); ?></li>
                </ol>
            </nav>
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                    <h4 class="card-title">
                        <?php echo $this->lang->line('Delivery Note Landing'); ?>                        
                    </h4>
                </div>
                <div class="col-xl-9 col-lg-9 col-md-8 col-sm-12 col-xs-12">
                    <ul id="trackingbar">
                    <?php 
                    if(!empty($trackingdata))
                        {
                            if(!empty($trackingdata['lead_id']))
                            { ?> 
                                <li><a href="<?= base_url('invoices/customer_leads?id=' . $trackingdata['lead_id']) ?>" target="_blank">LD #<?= $trackingdata['lead_number']; ?></a></li>
                                <?php } 
                            if(!empty($trackingdata['quote_number'])) { ?><li><a href="<?= base_url('quote/view?id=' . $trackingdata['quote_number']) ?>" target="_blank">QT #<?= $trackingdata['quote_number']; ?></a></li>
                            <?php } 
                            if(!empty($salesorderdetails['salesorder_number'])) { ?><li><a href="<?= base_url('quote/salesorders?id=' . $salesorderdetails['id']) ?>" target="_blank">SO #<?= $salesorderdetails['salesorder_number']; ?></a></li>
                            <?php } 

                            $deliverynote_number_array = explode(',', $trackingdata['deliverynote_number']);
                            $deliverynote_id_array = explode(',', $trackingdata['deliverynote_number']);
                          
                           if(!empty($deliverynote_id_array))
                           {
                         ?>
                           <li class="active">
                           <?php 
                            $flg = 1;
                            $sales_orders_list = '<ul>';  // Start unordered list
                            foreach ($deliverynote_number_array as $key => $item) {
                                
                                // For the first sales order, display a clickable link
                                if ($flg == 1) {
                                echo '<a href="' . base_url('DeliveryNotes/create?id=' . $deliverynote_id_array[$key]) . '">DN #' . $item . ' </a>';
                                }
                                
                                // Build the sales orders list to include in the popover
                                $sales_orders_list .= '<li><a href="' . base_url('DeliveryNotes/create?id=' . $deliverynote_id_array[$key]) . '">DN #' . $item . '</a></li>';
                                
                                $flg++;
                            }
                            $sales_orders_list .= '</ul>';  // Close unordered list
                            echo '<a class="badge badge-pill badge-default btn-primary" style="color:#ffffff;" href="#" data-toggle="popover" title="Delivery Note Lists" data-html="true" data-content="' . htmlspecialchars($sales_orders_list) . '">' . count($deliverynote_number_array) . '</a><br>';                                
                            ?>
                           
                        </li>
                        <?php
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
            <div class="card-body">
                <div class="row">

                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-xs-12 cmp-pnl">
                            <div id="customerpanel" class="inner-cmp-pnl">
                                <div id="customer">
                                    <div class="clientinfo">
                                        <h3 class="title-sub"><?php echo $this->lang->line('Client Details') ?></h3>
                                        <hr>
                                        <?php echo '<div id="customer_name"><strong>' . $customer['name'] . '</strong></div>
                                </div>
                                <div class="clientinfo">                              
                                    <div id="customer_address1"><strong>' . $customer['address'] . '<br>' . $customer['city'] . ',' . $customer['shipping_country'] . '</strong></div>
                                </div>
                                
                                <div class="clientinfo">
                                
                                <div type="text" id="customer_phone">Phone: <strong>' . $customer['phone'] . '</strong><br>Email: <strong>' . $customer['email'] . '</strong></div>
                                </div>'; ?>
                                        <hr>

                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-xs-12 cmp-pnl">
                            <div id="customerpanel" class="inner-cmp-pnl">
                                <div id="customer">
                                    <div class="clientinfo">
                                        <h3 class="title-sub"><?php echo $this->lang->line('Sales Order Properties') ?></h3>
                                        <hr>
                                        <?php echo '<div class="row"><div id="customer_name" class="col-5">Sales Order Number </div><div class="col-5">: <strong>' . $salesorderdetails['salesorder_number'] . '</strong></div>                              
                                            <div class="col-5" id="customer_address1">Order Date </div><div class="col-5">: <strong>' . dateformat($salesorderdetails['invoicedate']) . '</strong></div><div class="col-5">Delivery Deadline </div><div class="col-5">: <strong>' . dateformat($salesorderdetails['invoiceduedate']) . '</strong></div><div class="col-5">Customer Purchase Order </div><div class="col-5">: <strong>' . $salesorderdetails['customer_purchase_order'] . '</strong></div><div class="col-5">Customer Order Date </div><div class="col-5">: <strong>' . dateformat($salesorderdetails['customer_order_date']) . '</strong></div></div>
                                        </div>
                                        </div></div>'; ?>
                                        <hr>

                                </div>
                            </div>
                    
                    <!-- ========================================================================== -->
                    <div id="saman-row" >
                        <div class="col-12">
                            <div class="row">
                                <?php
                                if($salesorderdetails["converted_status"]==2 && $prdstatus!=1)
                                {
                                    echo '<a href="' . base_url("quote/salesorders?id=" . $salesorderdetails["id"]) . '" class="btn btn-primary">Create New Delivery Note</a>';
                                }
                                

                                ?>
                            </div>
                        </div>
                        <table id="DeliveryNotes" class="table table-striped table-bordered zero-configuration dataTable" style="width:100%;">
                        <thead>
                            <tr>
                                <th class="text-center"><?php echo $this->lang->line('No') ?></th>
                                <th class="text-center" style="width:10%;">Delivery Notes Number</th>
                                <th class="text-center"><?php echo $this->lang->line('Date') ?></th>
                                <!-- <th style="width:50px;">  #</th> -->
                                <th> Customer </th>
                                <th> Salesman </th>
                                <!-- <th style="width:100px;">Time</th> -->
                                <th class="text-right"><?php echo $this->lang->line('Amount') ?></th>
                                <th class="text-center">Staus</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $i = 1;
                                foreach($deliverynotedata as $deliverynote) {
                                    $reprintBtn = '<a href="' . base_url("DeliveryNotes/reprintnote?delivery=$deliverynote->delevery_note_id&sales=$deliverynote->salesorder_number&cust=$deliverynote->customer_id") . '" target="_blank" class="btn btn-sm btn-secondary"><i class="fa fa-print"></i> Reprint</a>';
                                                
                                    $invoiceBtn = '<button onclick="invoicing(\'' . $deliverynote->delevery_note_id . '\')" class="btn btn-sm btn-secondary"><i class="fa fa-exchange"></i> Convert to Invoice</button>' ;

                                    $deliveryBtn = '<a href="' . base_url("Deliveryreturn/deliveryreturn?delivery=$deliverynote->delevery_note_id") . '"  class="btn btn-sm btn-secondary"><i class="fa fa-undo"></i> '.$this->lang->line('Delivery Return').'</a>';

                                    $innerbtn = '<a href="' . base_url("DeliveryNotes/deliverynote_view?id=" . $deliverynote->delevery_note_id) . '" class="btn btn-sm btn-secondary"><i class="fa fa-print"></i> Print</a>';

                                    // $reprintBtn1 = '<a href="' . base_url("DeliveryNotes/deliverynote_view?id=" . $deliverynote->delevery_note_id) . '" class="btn btn-sm btn-secondary"><i class="fa fa-print"></i> Reprint</a>';

                                    $reprintBtn1 = '<a href="' . base_url("DeliveryNotes/reprintnote?delivery=$deliverynote->delevery_note_id&sales=$deliverynote->salesorder_number&cust=$deliverynote->customer_id") . '" target="_blank" class="btn btn-sm btn-secondary"><i class="fa fa-print"></i> Print</a>';

                                    $actionbtn = "";
                                    if($deliverynote->status=="Delivered")
                                    {
                                        $status = "<span class='st-pending'>".$deliverynote->status."</span>";
                                        
                                    }
                                    else if($deliverynote->status=="Draft")
                                    {
                                        $status = "<span class='st-Grey'>".$deliverynote->status."</span>";
                                        $actionbtn = "";
                                    }
                                    else if($deliverynote->status=="Printed")
                                    {
                                        $status = "<span class='st-active'>".$deliverynote->status."</span>";
                                        $actionbtn = $reprintBtn1.'&nbsp;'.$invoiceBtn.'&nbsp;'.$deliveryBtn;
                                    }
                                    else if($deliverynote->status=="Created")
                                    {
                                        $status = "<span class='st-rejected'>".$deliverynote->status."</span>";
                                        $actionbtn = $innerbtn;
                                    }
                                    else if($deliverynote->status=="Invoiced")
                                    {
                                        $status = "<span class='st-accepted'>".$deliverynote->status."</span>";
                                        $actionbtn = "";
                                    }
                                    else if($deliverynote->status=="Assigned")
                                    {
                                        $status = "<span class='st-rejected'>".$deliverynote->status."</span>";
                                        $actionbtn = $innerbtn;
                                    }
                                    else{
                                        $status = "<span class='st-canceled'>".$deliverynote->status."</span>";
                                    }
                                    

                                   
                                 
                                    echo '<tr>';
                                    echo '<td class="text-center">' . $i . '</td>';
                                    echo '<td class="text-center"><a href="' . base_url("DeliveryNotes/create?id=" . $deliverynote->delevery_note_id) . '">' . $deliverynote->delnote_number . '</a></td>';
                                    // echo '<td class="text-center"><a href="' . base_url("DeliveryNotes/deliverynote_edit?id=" . $deliverynote->delevery_note_id) . '">' . $deliverynote->delnote_number . '</a></td>';
                                    echo '<td class="text-center">'.dateformat($deliverynote->created_date).'</td>';
                                    echo '<td>#'.$deliverynote->customer_id." ".$deliverynote->name.'</td>';
                                    echo '<td>'.$deliverynote->data.'</td>';
                                    echo '<td class="text-right">'.$deliverynote->total_amount.'</td>';
                                    echo '<td class="text-center">'.$status.'</td>';
                                    echo '<td>'.$actionbtn.'</td>';
                                    // echo '<td>'.$reprintBtn.'&nbsp;'.$invoiceBtn.'&nbsp;'.$deliveryBtn.'</td>';
                                    echo '</tr>';
                                    $i++;
                                }
                            ?>
                        </tbody>

                        </table>
                    </div>
                    <!-- ========================================================================== -->



                </div>
            </div>

    <script type="text/javascript">
        $(document).ready(function() {
            $('[data-toggle="popover"]').popover(); 
            $(document).on('click', function(e) {
                if (!$(e.target).closest('[data-toggle="popover"], .popover').length) {
                    $('[data-toggle="popover"]').popover('hide');
                }
            });
        });
      </script>