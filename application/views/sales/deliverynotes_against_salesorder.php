<div class="content-body">
    <div class="card">
        <div class="card-header border-bottom">
            <nav aria-label="breadcrumb">
                <?php
                $prefixs = get_prefix_72();
                $suffix = $prefixs['suffix'];
                if (!empty($trackingdata['deliverynote_number'])) { 
                    $deliverynote_number = remove_after_last_dash($trackingdata['deliverynote_number']);
                 }
                ?>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('DeliveryNotes') ?>">Delivery Notes</a></li>                   
                    <li class="breadcrumb-item active" aria-current="page">
                        <?php echo ($deliverynote_number .  '-'.$suffix); ?>
                    </li>
                </ol>
            </nav>
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                    <h4 class="card-title">
                        <?php echo ($deliverynote_number .  '-'.$suffix); ?>
                    </h4>
                </div>
                <div class="col-xl-9 col-lg-9 col-md-8 col-sm-12 col-xs-12">
                    <ul id="trackingbar">                           
                     <?php 
                        if (!empty($trackingdata)) { 
                           if (!empty($trackingdata['lead_id'])) { 
                                 echo '<li><a href="' . base_url('invoices/customer_leads?id=' . $trackingdata['lead_id']) . '">' . $trackingdata['lead_number'] . '</a></li>';
                           } 
                           if (!empty($trackingdata['quote_number'])) { 
                                 echo '<li><a href="' . base_url('quote/create?id=' . $trackingdata['quote_number']) . '" >' . $trackingdata['quote_number'] . '</a></li>';
                           }
                           if (!empty($trackingdata['salesorder_number'])) { 
                            echo '<li><a href="' . base_url('SalesOrders/salesorder_new?id=' . $trackingdata['salesorder_number']) . '&token=3" >' . $trackingdata['salesorder_number'] . '</a></li>';
                           }
                           if (!empty($trackingdata['deliverynote_number'])) { 
                            //   echo '<li><a href="' . base_url('DeliveryNotes/create?id=' . $trackingdata['deliverynote_number']).'" target="_blank">' . $trackingdata['deliverynote_number'] . '</a></li>';
                              
                              echo '<li class="active">' . $deliverynote_number .  '-'.$suffix. ' <span class="badge badge-pill badge-default btn-primary">'.$trackingdata['delivery_count'].'</span></li>';
                           }

                          
                         if (!empty($trackingdata['delivery_return_number'])) { 
                            echo '<li><a href="' . base_url('Deliveryreturn/deliveryreturn?delivery=' . $trackingdata['delivery_return_number']).'">' . $trackingdata['delivery_return_number'] . '</a></li>';
                         }
                         if (!empty($trackingdata['invoice_number'])) { 
                            echo '<li><a href="' . base_url('invoices/create?id=' . $trackingdata['invoice_number']).'">' . $trackingdata['invoice_number'] . '</a></li>';
                         }
                         if (!empty($trackingdata['invoice_retutn_number'])) { 
                            echo '<li><a href="' . base_url('invoicecreditnotes/create?iid=' . $trackingdata['invoice_retutn_number']).'">' . $trackingdata['invoice_retutn_number'] . '</a></li>';
                         }
                        }
                     ?>
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
                        <table id="DeliveryNotes" class="table table-striped table-bordered zero-configuration dataTable" >
                        <thead>
                            <tr>
                                <th class="text-center"><?php echo $this->lang->line('SN') ?></th>
                                <th class="text-center" style="width:10%;">Delivery Notes</th>
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
                                $assignflg=0;
                                foreach($deliverynotedata as $deliverynote) {
                                    $reprintBtn = '<a href="' . base_url("DeliveryNotes/reprintnote?delivery=$deliverynote->delevery_note_id&sales=$deliverynote->salesorder_number&cust=$deliverynote->customer_id") . '" target="_blank" class="btn btn-crud btn-sm btn-secondary"><i class="fa fa-print"></i> Reprint</a>';
                                    
                                    
                                    $invoiceBtn = '<a href="'.base_url('invoices/create?dnid='.$deliverynote->delevery_note_id).'"  class="btn btn-crud btn-sm btn-secondary"><i class="fa fa-exchange"></i> Convert to Invoice</a>';

                                    // $invoiceBtn = '<button onclick="invoicing(\'' . $deliverynote->delevery_note_id . '\')" class="btn btn-crud btn-sm btn-secondary"><i class="fa fa-exchange"></i> Convert to Invoice</button>' ;
                                    
                                    $deliveryBtn = '<a href="' . base_url("Deliveryreturn/deliveryreturn?delivery=$deliverynote->delevery_note_id&type=new") . '"  class="btn btn-crud btn-sm btn-secondary"><i class="fa fa-undo"></i> '.$this->lang->line('Delivery Return').'</a>';

                                    $innerbtn = '<a href="' . base_url("DeliveryNotes/deliverynote_view?id=" . $deliverynote->delevery_note_id) . '" class="btn btn-crud btn-sm btn-secondary"><i class="fa fa-print"></i> Print</a>';

                                    // $reprintBtn1 = '<a href="' . base_url("DeliveryNotes/deliverynote_view?id=" . $deliverynote->delevery_note_id) . '" class="btn btn-sm btn-secondary"><i class="fa fa-print"></i> Reprint</a>';

                                    $reprintBtn1 = '<a href="' . base_url("DeliveryNotes/reprintnote?delivery=$deliverynote->delevery_note_id&sales=$deliverynote->salesorder_number&cust=$deliverynote->customer_id") . '" target="_blank" class="btn btn-crud btn-sm btn-secondary"><i class="fa fa-print"></i> Print</a>';

                                    $actionbtn = "";
                                    // if($deliverynote->status=="Delivered")
                                    // {
                                    //     $status = "<span class='st-pending'>".$deliverynote->status."</span>";
                                        
                                    // }
                                    // else if($deliverynote->status=="Draft")
                                    // {
                                    //     $status = "<span class='st-Grey'>".$deliverynote->status."</span>";
                                    //     $actionbtn = "";
                                    // }
                                    // else if($deliverynote->status=="Printed")
                                    // {
                                    //     $status = "<span class='st-active'>".$deliverynote->status."</span>";
                                    //     $actionbtn = $reprintBtn1.'&nbsp;'.$invoiceBtn.'&nbsp;'.$deliveryBtn;
                                    // }
                                    // else if($deliverynote->status=="Created")
                                    // {
                                    //     $status = "<span class='st-rejected'>".$deliverynote->status."</span>";
                                    //     $actionbtn = $reprintBtn1;
                                    // }
                                    // else if($deliverynote->status=="Invoiced")
                                    // {
                                    //     $status = "<span class='st-accepted'>".$deliverynote->status."</span>";
                                    //     $actionbtn = "";
                                    // }
                                    // else if($deliverynote->status=="Assigned")
                                    // {
                                    //     $status = "<span class='st-rejected'>".$deliverynote->status."</span>";
                                    //     $actionbtn = $reprintBtn1;
                                    //     $assignflg=1;
                                    // }
                                    // else{
                                    //     $status = "<span class='st-canceled'>".$deliverynote->status."</span>";
                                    // }
                                    if($deliverynote->status=="Delivered")
                                    {
                                        $status = "<span class='st-pending'>".$deliverynote->status."</span>";
                                        
                                    }
                                    else if($deliverynote->status=="Draft")
                                    {
                                        $status = "<span class='st-Draft'>".$deliverynote->status."</span>";
                                        $actionbtn = "";
                                    }
                                    else if($deliverynote->status=="Printed")
                                    {
                                        $status = "<span class='st-active'>Created</span>";
                                        // $status = "<span class='st-active'>".$deliverynote->status."</span>";
                                        $actionbtn = $reprintBtn1.'&nbsp;'.$invoiceBtn.'&nbsp;'.$deliveryBtn;
                                    }
                                    else if($deliverynote->status=="Assigned")
                                    {
                                        $status = "<span class='st-rejected'>".$deliverynote->status."</span>";
                                        $actionbtn = $innerbtn;
                                    }
                                    else if($deliverynote->status=="Invoiced")
                                    {
                                        $status = "<span class='st-accepted'>".$deliverynote->status."</span>";
                                        $actionbtn = $deliveryBtn;
                                    }
                                    else{
                                        $status = "<span class='st-canceled'>".$deliverynote->status."</span>";
                                    }
                                    
                        
                                    if($checkedres==1 && $deliverynote->status!='Draft')
                                    {
                                        $status = "<span class='st-Closed'>Fully Returned</span>";
                                        $actionbtn = "";
                                    }
                                    

                                   
                                 
                                    echo '<tr>';
                                    echo '<td class="text-center">' . $i . '</td>';
                                    // echo '<td class="text-center"><a href="' . base_url("DeliveryNotes/create?id=" . $deliverynote->delevery_note_id) . '">' . $deliverynote->delnote_number . '</a></td>';
                                    echo '<td class="text-center"><a href="' . base_url("DeliveryNotes/create?id=" . $deliverynote->delevery_note_id) . '">' . $deliverynote->delivery_note_number . '</a></td>';
                                   
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
                        <?php 
                            echo '<script>
                            $(document).ready(function() {
                                showhide("'.$assignflg.'");
                            });
                            </script>';
                        ?>
                        
                        
                    </div>
                    <div class="row text-end text-right">
                            <div class="col-12 mt-2 ">
                                <?php
                                    if($salesorderdetails["converted_status"]==2)
                                    {                                
                                        echo '<a href="' . base_url("SalesOrders/salesorder_new?id=" . $salesorderdetails["id"]) . '&token=3" class="btn btn-crud btn-primary" id="create_new_btn">Create New Delivery Note</a>';
                                        // echo '<a href="' . base_url("quote/salesorders?id=" . $salesorderdetails["id"]) . '" class="btn btn-primary">Create New Delivery Note</a>';
                                    }
                                ?>
                            </div>
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
        function showhide(flg)
        {
            if(flg==1)
            {
                $("#create_new_btn").addClass("d-none");
            }
            else{
                $("#create_new_btn").removeClass("d-none");
            }
        }
      </script>