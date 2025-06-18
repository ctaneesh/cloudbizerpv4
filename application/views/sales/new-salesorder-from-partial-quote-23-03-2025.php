<div class="content-body">
    <div class="card">
        <div class="card-header border-bottom">
            <?php
             $prefixs = get_prefix_72();
             $suffix = $prefixs['suffix'];
             if (!empty($trackingdata['salesorder_number'])) { 
                $sales_number = remove_after_last_dash($trackingdata['salesorder_number']);
             }
             ?>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('quote') ?>"><?php echo $this->lang->line('Quotes');?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('SalesOrders') ?>"><?php echo $this->lang->line('Sales Orders');?></a></li>
                    <li class="breadcrumb-item active" aria-current="page">                        
                        <?php echo ($sales_number .  '-'.$suffix); ?>
                    </li>
                </ol>
            </nav>
            <div class="row">
                <div class="col-xl-5 col-lg-5 col-md-4 col-sm-12 col-xs-12">
                    <h4 class="card-title">
                        <?php echo $this->lang->line('SO Landing')." - ".$sales_number .  '-'.$suffix;?>
                    </h4>
                </div>
                <div class="col-xl-7 col-lg-7 col-md-8 col-sm-12 col-xs-12">
                    <ul id="trackingbar">
                        <?php   
                            if (!empty($trackingdata)) { 
                                if (!empty($trackingdata['lead_id'])) { 
                                      echo '<li><a href="' . base_url('invoices/customer_leads?id=' . $trackingdata['lead_id']) . '">' . $trackingdata['lead_number'] . '</a></li>';
                                } 
                                if (!empty($trackingdata['quote_number'])) { 
                                      echo '<li><a href="' . base_url('quote/create?id=' . $trackingdata['quote_number']) . '" target="_blank">' . $trackingdata['quote_number'] . '</a></li>';
                                }
                                if (!empty($trackingdata['salesorder_number'])) { 
                                   echo '<li class="active">' . $sales_number .  '-'.$suffix.' <span class="badge badge-pill badge-default btn-primary">'.$trackingdata['sales_count'].'</span></li>';
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

                        <div class="col-xl-2 col-lg-4 col-md-6 col-sm-12 col-xs-12 cmp-pnl">
                            <div id="customerpanel" class="inner-cmp-pnl">
                                <div id="customer">
                                    <div class="clientinfo">
                                        <h3 class="title-sub"><?php echo $this->lang->line('Client Details') ?></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12 cmp-pnl">
                            <div id="customerpanel" class="inner-cmp-pnl">
                                <div id="customer">
                                    <div class="clientinfo">
                                <?php echo '<div id="customer_name"><strong>' . $customer['name'] . '</strong></div>
                              </div>
                              <div class="clientinfo">                              
                                 <div id="customer_address1"><strong>' . $customer['address'] . '<br>' . $customer['city'] . ',' . $customer['shipping_country'] . '</strong></div>
                              </div>
                              
                              <div class="clientinfo">
                              
                              <div type="text" id="customer_phone">Phone: <strong>' . $customer['phone'] . '</strong>, Email: <strong>' . $customer['email'] . '</strong></div>
                              </div>'; ?>
                                        <hr>
                                        <div id="customer_pass"></div>

                                    </div>
                                </div>
                            </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12 cmp-pnl">
                        <?php 
                           if($convertedflg=='2')
                           { ?>
                                <button class="btn btn-crud btn-primary" id="create_salesorder" type="button">Create New Sales order</button>
                           <?php } ?>
                        </div>
                        </div>


                        <!-- ================= table section starts ======================== -->
                        <div id="saman-row">
                          <div >
                            <table id="histable" class="table table-striped table-bordered zero-configuration dataTable">
                                <thead>
                                    <tr class="item_header bg-gradient-directional-blue white">
                                        <th width="25%" class="text-center1 pl-1">Item Name</th>
                                        <th width="8%" class="text-center1 pl-1">Item No.</th>
                                        <!-- <th width="2%" class="text-center1 pl-1">History</th> -->
                                        <th width="5%" class="text-center">Curr. Price</th>
                                        <th width="4%" class="text-center">Min. Price</th>
                                        <th width="4%" class="text-center">Max dis(%)</th>
                                        <th width="5%" class="text-center">Lead</th>
                                        <th width="6%" class="text-center">Quote</th>
                                        <th width="7%" class="text-right">Quote Price</th>
                                        <th width="8%" class="text-center">Sales Order</th>
                                        <th width="10%" class="text-right1">Discount</th>
                                        <th width="4%" class="text-right">Unit Price</th>
                                        <th width="7%" class="text-right">Total</th>
                                        <th width="60%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                         <?php 
                           $i=0;
                           foreach ($productdata as $row) {
                              $pid = $row['productid'];
                              $quote_id = $row['quote_id'];
                              if(!empty($row['lead_id'])){
                                 $leadid = $row['lead_id'];
                                 $leadurl =  '<a href="' . base_url("invoices/customer_leads?id=$leadid") . '"   title="Lead" target="_blank">'.($row['leadnumber']).'</a>';
                                 $leadQty = $row['leadqty'];
                                 $leadDate = date('d-m-Y', strtotime($row['leaddate']));
                              }
                              else{
                                 $leadurl ='';
                                 $leadQty = '--';
                                 $leadDate ='';
                              }
                              if(!empty($row['quote_id'])){
                                 $quote_id = $row['quote_id'];
                                 $quoteurl =  '<a href="' . base_url("quote/view?id=$quote_id") . '"   title="Quote" target="_blank">'.($row['quote_number']).'</a>';
                                 $quoteQty = $row['quoteqty'];
                                 $quoteDate = date('d-m-Y', strtotime($row['quotedate']));
                              }
                              else{
                                 $quoteurl ='';
                                 $quoteQty = '--';
                                 $quoteDate ='';
                              }
                               echo '<td><strong id="productlabel' . $i . '">'.$row['product'].'</strong><input type="hidden" class="form-control" name="product_name[]" placeholder="'.$this->lang->line('Enter Product name').'"  value="' . $row['product'] . '"><input type="hidden" name="pid[]" value="'.$row["productid"].'" id="pid-' . $i . '">&nbsp;<button onclick="producthistory('.$i.')" type="button" class="btn btn-crud btn-sm btn-secondary producthis"  title="History"><i class="fa fa-history"></i></button>&nbsp;<button onclick="single_product_details(' . $i . ')" type="button" class="btn btn-crud btn-sm btn-secondary"  title="Details"><i class="fa fa-info"></i> </td>';
                               
                            //    echo '<td class="text-left"><input type="hidden" name="pid[]" value="'.$row["productid"].'" id="pid-' . $i . '"><button onclick="producthistory('.$i.')" type="button" class="btn btn-sm btn-secondary producthis"  title="History"><i class="fa fa-history"></i></button>&nbsp;<button onclick="single_product_details(' . $i . ')" type="button" class="btn btn-sm btn-secondary"  title="Details"><i class="fa fa-info"></i></button></td>';
                                echo '<td class=""><strong >'.($row['code']).'1</strong></td>';
                               echo '<td class="text-right"><strong >'.($row['product_price']).'1</strong></td>';

                               echo '<td class="text-right"><strong >'.($row['product_lowest_price']).'</strong></td>';

                               echo '<td class="text-right"><strong >'.($row['product_max_discount']).'</strong></td>';

                               echo '<td class="text-center"><strong>'.intval($row['leadqty']).'</strong><br>'.$leadurl.'<br>'.$leadDate.'</td>';

                               echo '<td class="text-center"><strong>'.intval($row['quoteqty']).'</strong><br>'.$quoteurl.'<br>'.$quoteDate.'</td>';

                               echo '<td class="text-right"><strong>'.($row['quoterate']).'</strong></td>';

                               
                               echo '<td class="text-center">';
                                 if(!empty($salesorders)){

                                    $salestotal = "";
                                    $salesdiscountdet = "";
                                    $unitprice = "";
                                    $salesorderid="";
                                    $actionbtn="";
                                    // $completed_status =0;
                                    
                                    echo "<table class='table-internal' style='background:none !important;border:none !important;width:100%;'>";
                                    foreach ($salesorders[$pid][$quote_id] as $sdata) {
                                        
                                       if($pid==$sdata['salesprdid'])
                                       {
                                          $salesorderid = $sdata['salesorderid'];
                                          $completed_status = $sdata['completedstatus'];
                                          $salesordernumber = $sdata['salesordernumber'];
                                          $salesurl =  '<a href="' . base_url("SalesOrders/salesorder_new?id=$salesorderid&token=3") . '"   title="Sales order" target="_blank">'.($salesordernumber).'</a>';
                                          if($completed_status!=1){
                                            $icons = '<i class="fa fa-database"></i>';
                                            $convertbtn = "";                
                                            $draftbtn = '<label title="Draft">'.$icons.'&nbsp;Draft</label>&nbsp;';
                                            $salesurl = '<a  class="text-danger" href="' . base_url("SalesOrders/salesorder_new?id=$salesorderid&token=3") . '" title="Draft">&nbsp; ' . ($salesordernumber) . '</a>';
                                          }
                                          if($sdata['convertedstatus']==0 && $completed_status==1){
                                      
                                            // $editbtn = '<a href="' . base_url("SalesOrders/draft_or_edit?id=$salesorderid") . '" class="btn btn-crud btn-secondary btn-sm" title="Edit" target="_blank"><i class="fa fa-edit"></i></a>&nbsp;';
                                            $editbtn = '<a href="' . base_url("SalesOrders/salesorder_new?id=$salesorderid&token=3") . '" class="btn btn-crud btn-secondary btn-sm" title="Edit" target="_blank"><i class="fa fa-edit"></i></a>&nbsp;';
                                          }
                                          else{
                                            $editbtn = "";
                                          }
                                        
                                          echo "<tr style='background:none !important;border: none !important; border-right:none !important;'>";
                                          echo "<td style='text-align:center;border:none; border-right:none !important; border-bottom:1px solid #ccc;'>";
                                          echo '<strong>'.intval($sdata['salesorderqty']).'</strong><br>'.$salesurl." ".$editbtn1.'<br><span><small>'.date('d-m-Y', strtotime($sdata['salesorderdate']));
                                          echo "</small></span></td>";
                                          echo "</tr>";   
                                           
                                        //   $salestotal .= "<tr style='background:none !important;'>";
                                        //   $salestotal .= "<td  style='text-align:right;border:none; border-right:none !important; border-bottom:1px solid #ccc;padding-top:0px !important;'>";
                                        //   $salestotal .= "<div><strong>";
                                        //   $salestotal .= $sdata['subtotal'];
                                        //   $salestotal .= "</strong></div><br></td>";
                                        //   $salestotal .= "</tr>";

                                          $salestotal .= "<tr style='background:none !important;border: none !important; border-right:none !important;'>";
                                          $salestotal .= "<td style='text-align:right;border:none;border-right:none !important;padding-bottom:0px !important;'><div style='border-bottom:1px solid #ccc;'>";
                                          $salestotal .= '<strong ><br>'.$sdata['subtotal'].'</strong><br><br></div></td>';
                                          $salestotal .= "</tr>"; 

                                          $salesdiscountdet .= "<tr style='background:none !important;'>";
                                          $salesdiscountdet .= "<td style='text-align:right; padding-right:0px!important; padding-top:0px!important;border:none; border-right:none !important; border-radius:0px;padding-top:0px !important;'>";
                                          $salesdiscountdet .= "<div class='text-center' style='border-bottom:1px solid #747475;padding-bottom:10px;'>";
                                          $salesdiscountdet .= "<strong>".$sdata['salesdiscounttype']." / <span>".$sdata['salesdiscount']."</span></strong><br><strong>Amount :<span>".$sdata['salestotaldiscount']."</span></strong><br><br>";
                                          $salesdiscountdet .= "</div>";
                                          $salesdiscountdet .= "</td>";
                                          $salesdiscountdet .= "</tr>";

                                        //   $unitcost = ($sdata['subtotal'] > 0) ? round(($sdata['subtotal'] / intval($sdata['salesorderqty'])), 2):"";

                                        //   $unitprice .= "<tr style='background:none !important;'>";
                                        //   $unitprice .= "<td style='padding:10px 0px 10px 0px; text-align:right; border:none; border-right:none !important;border-bottom:1px solid #ccc;border-radius:0px;'><strong>";

                                        //   $unitprice .= $unitcost;
                                        //   $unitprice .= "</strong></td>";
                                        //   $unitprice .= "</tr>";

                                        $unitcost = ($sdata['subtotal'] > 0) ? round(($sdata['subtotal'] / intval($sdata['salesorderqty'])), 2):"";
                                          
                                        // $unitprice .= "<tr style='background:none !important;'>";
                                        // $unitprice .= "<td style='padding:0px; text-align:right; border:none; border-right:none !important;border-bottom:1px solid #ccc;border-radius:0px;'><strong>";
                                        // $unitprice .= $unitcost;
                                        // $unitprice .= "</strong><br><br></td>";
                                        // $unitprice .= "</tr>";


                                        $unitprice .= "<tr style='background:none !important;border: none !important; border-right:none !important;'>";
                                        $unitprice .= "<td style='text-align:right;border:none; border-right:none !important; border-bottom:1px solid #ccc;padding-bottom:0px !important;'>";
                                        $unitprice .= '<strong><br>'.$unitcost.'</strong><br><br></td>';
                                        $unitprice .= "</tr>"; 


                                        // echo "<tr style='background:none !important;border: none !important; border-right:none !important;'>";
                                        // echo "<td style='text-align:center;border:none; border-right:none !important; border-bottom:1px solid #ccc;'>";
                                        // echo '<strong>'.intval($sdata['salesorderqty']).'</strong><br>'.$salesurl." ".$editbtn.'<br><span><small>'.date('d-m-Y', strtotime($sdata['salesorderdate']));
                                        // echo "</small></span></td>";
                                        // echo "</tr>"; 

                                        $actionbtn .= "<tr style='background:none !important;border: none !important; border-right:none !important;'>";
                                        $actionbtn .= "<td style='text-align:left;border:none;border-right:none !important;padding-bottom:0px !important;'><div style='border-bottom:1px solid #ccc;'>";
                                        $actionbtn .= '<strong><br>';
                                        $actionbtn .= '<span><a href="' . base_url("SalesOrders/salesorder_new?id=$salesorderid&token=3") . '"   title="Assign For Delivery" target="_blank" class="btn btn-crud btn-sm btn-secondary">Assign </a>&nbsp;'.$editbtn.'</strong></span><br><br></div></td>';
                                        // $actionbtn .= '<span><a href="' . base_url("quote/salesorders?id=$salesorderid") . '"   title="Assign For Delivery" target="_blank" class="btn btn-crud btn-sm btn-secondary">Assign </a>&nbsp;'.$editbtn.'</strong></span><br><br></div></td>';
                                        $actionbtn .= "</tr>"; 
                                        
                                        
                                       }                                  
                                    }   
                                    if($row['remaining_qty']!=0)  {
                                       echo "<tr style='background:#fcecec !important;'>";
                                       echo "<td style='border:none !important; text-align:center;'>";
                                       echo 'Rem Qty<br><strong>'.intval($row['remaining_qty']).'('.intval($row['quoteqty']).')</strong>';
                                       echo "</td>";
                                       echo "<tr>";    
                                    }                               
                                    echo "</table>";
                                 }                                 
                               echo '</td>';
                             
                              

                              // echo '<td class="text-right"><strong>'.($row['currentrate']).'</strong></td>';
                              

                              //discount section 
                              echo "<td style='padding-top: 0px !important;'><table >";                             
                              echo $salesdiscountdet."</table></td>";

                               //unitcost section 
                               echo "<td class='text-right' style='padding-top: 0px !important;display: inline-table;'><table class='table-internal1' style='background:none !important;border:none !important;'>";                             
                               echo $unitprice."</table></td>";
                              //sub total
                              echo "<td class='text-right' style='padding-top: 0px !important;text-align:right !important;'><table class='text-right' style='background:none !important;border:none !important;'>";                             
                              echo $salestotal."</table></td>";

                              echo "<td class='text-right' style='padding-top: 0px !important;'><table style='background:none !important;border:none !important;'>";                             
                              echo $actionbtn."</table></td>";
                              // var customer_id1 = $("#customer_id").val();
                              if($completed_status==1){
                                $icons = '<i class="fa fa-thumbs-o-up"></i>';
                              }
                              else{
                                $icons = '<i class="fa fa-thumbs-o-down"></i>';
                              }
                            //   if($sdata['convertedstatus']==0){
                            //     $editbtn = '<button onclick="saleorderedit('.$salesorderid.',\''.$salesordernumber.'\')" type="button" class="btn btn-sm btn-secondary" title="Edit"><i class="fa fa-edit"></i></button>&nbsp;';
                            //   }
                            //   else{
                            //     $editbtn = "";
                            //   }
                    
                           

                           echo '</tr>';
                               $i++;
                           } ?>

                                </tbody>
                            </table>

                    </div>
                            <input type="hidden" name="quote_id" id="quote_id" value="<?=$quote_id?>">
                            <!-- ================= table section ends   ======================== -->
            </div>
        </div>
    </div>




    <div class="modal fade" id="new_sales_order_model" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
               <form method="post" id="data_form" class="form-horizontal">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">Sales Order - <?=$newsalesordernumber?></h4>
                        <button type="button" class="close" data-dismiss="modal">
                            <span >&times;</span>
                            <span class="sr-only">Close</span>
                        </button>
                    </div>
                    <!-- Modal Body -->
                    <div class="modal-body">
                            <div class="form-group row">
                                <div class="col-12 cmp-pnl">
                                    <div class="inner-cmp-pnl">
                                        <div class="form-group form-row">
                                            <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                                <label for="invocieno" class="col-form-label">Sales Order Number</label>
                                                <div class="input-group">
                                                    <div class="input-group-addon"><span class="icon-file-text-o"
                                                    ></span></div>
                                                    <input type="text" class="form-control" placeholder="Sales Order #"
                                                        name="salesorder_number1" id="salesorder_number1"
                                                        value="<?php echo $prefix.$newsalesordernumber; ?>" readonly>
                                                    <input type="hidden" class="form-control" placeholder="Sales Order #"
                                                        name="salesorder_number" id="salesorder_number"
                                                        value="<?php echo $newsalesordernumber; ?>" readonly>
                                                    <input type="hidden" class="form-control"
                                                        placeholder="Sales Order #" name="invocieno" id="invocienoId"
                                                        value="<?php echo $salesorder_num+1000; ?>">
                                                    <input type="hidden" class="form-control" name="seq_number"
                                                        id="seq_number" value="<?php echo $salesseqnumber; ?>">
                                                    <input type="hidden" value="<?=$customer['id']?>" id="customer_id"
                                                        name="customer_id">
                                                </div>
                                            </div>
                                            <div class="col-xl-2 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                                <label for="invocieno"
                                                    class="col-form-label"><?php echo $this->lang->line('Our Reference');?></label>
                                                    <input type="text" class="form-control"
                                                        placeholder="<?php echo $this->lang->line('Reference')?>"
                                                        name="refer" id="refer" value="<?php echo $invoice['refer'] ?>">
                                            </div>

                                            
                                            <!--erp2024 newly added 29-09-2024  -->
                                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                                    <div class="frmclasss"><label for="customer_reference_number" class="col-form-label"><?php echo $this->lang->line('Customer Reference Number'); ?></label>
                                                    <input type="text" name="customer_reference_number" id="customer_reference_number" class="form-control" placeholder="Customer Reference Number" value="<?php echo $invoice['customer_reference_number'] ?>" >
                                                    </div>                                    
                                            </div>
                                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                                    <div class="frmclasss"><label for="customer_reference_number" class="col-form-label"><?php echo $this->lang->line('Customer Contact Person'); ?></label>
                                                    <input type="text" name="customer_contact_person" id="customer_contact_person" class="form-control" placeholder="Customer Contact Person" value="<?php echo $invoice['customer_contact_person'] ?>"  >
                                                    </div>                                    
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                                    <div class="frmclasss"><label for="customer_contact_number" class="col-form-label"><?php echo $this->lang->line('Contact Person Number'); ?></label>
                                                    <input type="text" name="customer_contact_number" id="customer_contact_number" class="form-control" placeholder="Contact Person Number" value="<?php echo $invoice['customer_contact_number'] ?>" >
                                                    </div>                                    
                                            </div>
                                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                                    <div class="frmclasss"><label for="customer_contact_email" class="col-form-label"><?php echo $this->lang->line('Customer Contact Email'); ?></label>
                                                    <input type="email" name="customer_contact_email" id="customer_contact_email" class="form-control" placeholder="Customer Contact Email" value="<?php echo $invoice['customer_contact_email'] ?>" >
                                                    </div>                                    
                                            </div>
                                            <!--erp2024 newly added 29-09-2024 ends -->

                                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none">
                                                <label for="invociedate" class="col-form-label">Sales Order Date</label>
                                                <div class="input-group">
                                                    <!-- <input type="hidden" class="form-control"
                                       placeholder="Billing Date" name="invoicedate" id="invoicedate"
                                       autocomplete="false" min="<?php echo date("Y-m-d"); ?>"  value="<?php echo date("Y-m-d"); ?>" > -->
                                        <input type="hidden" name="iid" value="<?php echo $invoice['iid']; ?>">
                                        <input type="hidden" name="quote_id" value="<?php echo $quote_id; ?>">
                                                </div>
                                            </div>
                                            <input type="hidden" class="form-control"
                                                placeholder="Billing Date" name="invoicedate" id="invoicedate"
                                                autocomplete="false" min="<?php echo date("Y-m-d"); ?>"
                                                value="<?php echo date("Y-m-d"); ?>">
                                            <input type="hidden" name="quote_id" value="<?php echo $quote_id; ?>">



                                            <div class="col-xl-2 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                                <label for="invocieno"
                                                    class="col-form-label"><?php echo $this->lang->line('Customer')." ".$this->lang->line('Purchase Order')." No.";?>
                                                    <span class="compulsoryfld"> *</span></label>                                                    
                                                    <input type="text" class="form-control" 
                                                        placeholder="<?php echo $this->lang->line('Customer')." ".$this->lang->line('Purchase Order');?>"
                                                        name="customer_purchase_order" id="customer_purchase_order" required>
                                            </div>
                                            <div class="col-xl-2 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                                <label for="invocieno"
                                                    class="col-form-label"><?php echo $this->lang->line('Customer')." ".$this->lang->line('Purchase Order')." ".$this->lang->line('Date');?>
                                                    <span class="compulsoryfld"> *</span></label>
                                                    <input type="date" class="form-control"
                                                        name="customer_order_date" id="customer_order_date"
                                                        placeholder="Order Date" autocomplete="false" max="<?=date('Y-m-d')?>">
                                            </div>
                                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                                <label for="invocieduedate" class="col-form-label"><?php echo  $this->lang->line('Delivery Deadline'); ?>
                                                    <span class="compulsoryfld">*</span></label>
                                                    <input type="date" class="form-control"
                                                        name="invocieduedate" id="invocieduedate"
                                                        placeholder="Validity Date" autocomplete="false"
                                                        min="<?php echo date("Y-m-d"); ?>">
                                            </div>
                                            <!-- <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                             <label for="invocieduedate" class="col-form-label"><?php echo  $this->lang->line('Customer Sales Order Date'); ?><span class="compulsoryfld">*</span></label>
                                             <div class="input-group">
                                                <div class="input-group-addon"><span class="icon-calendar-o"
                                                   aria-hidden="true"></span></div>
                                                <input type="date" class="form-control"
                                                   name="customer_order_date" id="customer_order_date"
                                                   placeholder="Order Date" autocomplete="false"  >
                                             </div>
                                             </div> -->
                                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none">
                                                <label for="taxformat" class="col-form-label">Tax</label>
                                                <select class="form-control" onchange="changeTaxFormat(this.value)"
                                                    id="taxformat">
                                                    <?php echo $taxlist; ?>
                                                </select>
                                            </div>
                                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none">
                                                <label for="discountFormat" class="col-form-label">Discount</label>
                                                <select class="form-control" onchange="changeDiscountFormat(this.value)"
                                                    id="discountFormat">
                                                    <?php echo '<option value="' . $invoice['format_discount'] . '">' . $this->lang->line('Do not change') . '</option>'; ?>
                                                    <?php echo $this->common->disclist() ?>
                                                </select>
                                            </div>
                                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none">
                                                <label for="s_warehouses"
                                                    class="col-form-label"><?php echo $this->lang->line('Sale Point') ?></label>
                                                <select id="s_warehouses" class="selectpicker form-control">
                                                    <?php //echo $this->common->default_warehouse();
                                                   echo '<option value="0">' . $this->lang->line('Select Warehouse') ?></option><?php foreach ($warehouse as $row) {
                                                   echo '<option value="' . $row['id'] . '">' . $row['title'] . '</option>';
                                                   } ?>
                                                </select>
                                            </div>
                                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                <label for="toAddInfo" class="col-form-label">Sales Order Note</label>
                                                <textarea class="form-textarea" name="notes"
                                                    id="salenote"><?php echo $invoice['notes'] ?></textarea>
                                            </div>
                                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                <label for="toAddInfo"
                                                    class="col-form-label"><?php echo $this->lang->line('Customer Message'); ?>
                                                </label>
                                                <textarea class="form-textarea" name="propos" id="contents"
                                                    rows="2"><?php echo $invoice['proposal'] ?></textarea>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 mt-1">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="2" id="discountchecked"
                                            name="discountchecked">
                                        <label class="form-check-label" for="discountchecked"
                                            style="font-size:14px;color:#404E67;">
                                            <b>
                                                <?php echo $this->lang->line('Do you want to modify the prices or discounts for the items below'); ?></b>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-12 tablesection"></div>

                                <!-- ========================================================== -->
                                <div class="col-12 row mt-3">
                                    <?php  if($configurations['config_tax']!='0'){ ?>
                                    <div class="col-11 text-right">
                                        <strong>Total Tax (<span
                                                class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>)</strong>
                                    </div>
                                    <div class="col-1">
                                        <span id="taxr"
                                            class="lightMode"><?php echo amountExchange_s($invoice['tax'], $invoice['multi'], $this->aauth->get_user()->loc) ?></span>
                                    </div>
                                    <?php } ?>
                                    <div class="col-11 text-right">
                                        <strong class="d-none1">Total Discount (<span
                                                class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>)</strong>
                                    </div>
                                    <div class="col-1 text-right"> <span id="discs" class="lightMode d-none1"></span></div>
                                    <div class="col-12 text-right">
                                        <?php if ($exchange['active'] == 1){
                                          echo $this->lang->line('Payment Currency client') . ' <small>' . $this->lang->line('based on live market') ?></small>
                                        <select name="mcurrency" class="selectpicker form-control">
                                            <?php
                                                echo '<option value="' . $invoice['multi'] . '">Do not change</option><option value="0">None</option>';
                                                foreach ($currency as $row) {
                                                
                                                      echo '<option value="' . $row['id'] . '">' . $row['symbol'] . ' (' . $row['code'] . ')</option>';
                                                } ?>
                                        </select><?php } ?>
                                    </div>
                                    <div class="col-11 text-right">
                                        <strong class="d-none1"><?php echo $this->lang->line('Grand Total') ?>(<span
                                                class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>)</strong>
                                    </div>
                                    <div class="col-1 text-right">
                                        <span id="grandtotaltext"></span>
                                        <input type="hidden" name="total" class="form-control" id="invoiceyoghtml" readonly>
                                    </div>
                                    <div class="col-6 mt-2">
                                            <input type="submit" class="btn btn-crud btn-lg btn-secondary sub-btn" value="<?php echo $this->lang->line("Save As Draft");?>" id="saleorder-draft-btn" data-loading-text="Dtaft...">
                                           
                                    </div>
                                    <div class="col-6 text-right mt-2">
                                            <input type="submit" id="new-saleorder-btn" class="btn btn-crud btn-lg btn-primary margin-bottom"
                                             value="<?php echo $this->lang->line('Create Sales Order') ?>" data-loading-text="Adding...">
                                          <input type="hidden" value="quote/salesorder_sub_action" id="action-url">
                                    </div>
                                </div>

                                <input type="hidden" value="<?php echo $this->config->item('currency'); ?>"
                                    name="currency">
                                <input type="hidden" value="<?= $this->common->taxhandle_edit($invoice['taxstatus']) ?>"
                                    name="taxformat" id="tax_format">
                                <input type="hidden" value="%" name="discountFormat" id="discount_format">
                                <input type="hidden" value="<?= $invoice['taxstatus']; ?>" name="tax_handle"
                                    id="tax_status">                                
                                <input type="hidden" value="yes" name="applyDiscount" id="discount_handle">
                                <input type="hidden"
                                    value="<?php
                                       if($invoice['shipping']==0)  $invoice['shipping']=1;
                                       $tt = 0;
                                       if ($invoice['ship_tax_type'] == 'incl') $tt = @number_format(($invoice['shipping'] - $invoice['ship_tax']) / $invoice['shipping'], 2, '.', '');
                                       echo amountFormat_general(@number_format((($invoice['ship_tax'] / $invoice['shipping']) * 100) + $tt, 3, '.', '')); ?>"
                                    name="shipRate" id="ship_rate">
                                <input type="hidden" value="<?= $invoice['ship_tax_type']; ?>" name="ship_taxtype"
                                    id="ship_taxtype">
                                <input type="hidden" value="<?= amountFormat_general($invoice['ship_tax']); ?>"
                                    name="ship_tax" id="ship_tax">
                               
                            </div>
                            <!-- ========================================================== -->
                    </div>
                </form>
            </div>
            <!-- Modal Footer -->
            <div class="modal-footer"></div>
        </div>
    </div>

</div>



<!-- ====================================================================================================== -->
<div class="modal fade" id="new_sales_order_edit_model" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="col-12 tablesection"></div>
        </div>
        <!-- Modal Footer -->
        <div class="modal-footer"></div>
    </div>
</div>
<!-- ====================================================================================================== -->


<script type="text/javascript">
$("#create_salesorder").on('click', function() {
    var quote_id = $("#quote_id").val();

    //  Swal.fire({
    //      title: 'Do you want to create a new sales order?',
    //      icon: 'warning',
    //      showCancelButton: true,
    //      confirmButtonText: 'Yes, create it!',
    //      cancelButtonText: 'No, cancel'
    //  }).then((result) => {
    //      if (result.isConfirmed) {
    $.ajax({
        url: baseurl + 'quote/generate_new_salesorder',
        type: 'POST',
        data: {
            quote_id: quote_id
        },
        success: function(response) {
            // Assuming 'response' contains the HTML to be appended to the modal
            $('#new_sales_order_model .tablesection').html(response);
            $('#new_sales_order_model').modal('show');
        },
        error: function(xhr, status, error) {
            Swal.fire({
                title: 'Error!',
                text: 'An error occurred while creating the sales order. Please try again.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    });
    //   }
    //  });
});
$('#discountchecked').on('change', function() {
    if ($(this).is(':checked')) {
        $('.discountpotion').removeClass('d-none');
        $('.discountpotionnotedit').addClass('d-none');
        $('.pricelabel').addClass('d-none');
        $('input[name="product_price[]"]').attr('type', 'text');
        $('#discountchecked').val(1);
    } else {
        $('.discountpotion').addClass('d-none');
        $('.pricelabel').removeClass('d-none');
        $('.discountpotionnotedit').removeClass('d-none');
        $('input[name="product_price[]"]').attr('type', 'hidden');
        $('#discountchecked').val(2);
    }
});

function toggleDiscountOptions(checkbox) {
    if (checkbox.checked) {
        $('.discountpotion1').removeClass('d-none');
        $('.discountpotion1notedit').addClass('d-none');
        $('.pricelabel').addClass('d-none');
        $('input[name="product_price[]"]').attr('type', 'text');
        $('#discountcheckededit').val(1);
    } else {
        $('.discountpotion1').addClass('d-none');
        $('.pricelabel').removeClass('d-none');
        $('.discountpotion1notedit').removeClass('d-none');
        $('input[name="product_price[]"]').attr('type', 'hidden');
        $('#discountcheckededit').val(2);
    }
}

// function checkqty(id) {
//     var qty = parseFloat($("#amount-" + id).val()) || 0;
//     var remaining = parseFloat($("#remainingqty-" + id).val()) || 0;
//     if (qty > remaining) {
//         $("#amount-" + id).val(0);
//         Swal.fire({
//             icon: 'error',
//             title: 'Invalid Quantity',
//             text: 'Sales order quantity is greater than the remaining quantity.'
//         });
//     }
// }

function checkqty(id) {
    var qty = parseFloat($("#amount-" + id).val()) || 0;
    var quoteqty = parseFloat($("#remainingqty-" + id).val()) || 0;

    if (quoteqty < qty) {
        // Remove aria-hidden to avoid focus issues
        $("#new_sales_order_edit_model").removeAttr("aria-hidden");

        Swal.fire({
            title: "Do you want to proceed?",
            text: "Sales order quantity is greater than the Quote quantity",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, proceed!',
            cancelButtonText: "No - Cancel",
            reverseButtons: true,  
            focusCancel: true,     
            allowOutsideClick: false  
        }).then((result) => {
            if (!result.isConfirmed) {
                $("#amount-" + id).val(0);
            }
        }).finally(() => {
            // Restore aria-hidden after SweetAlert closes
            $("#new_sales_order_edit_model").attr("aria-hidden", "true");
        });

        // Delay focusing the cancel button to fix Bootstrap modal conflict
        setTimeout(() => {
            $(".swal2-cancel").focus();
        }, 50);
    }
}

function checkqtyedit(id) {
    var qty = parseFloat($("#amount-" + id).val()) || 0;
    var oldqty = parseFloat($("#old_amount-" + id).val()) || 0;
    var trasferedqty = parseFloat($("#trasferedqty-" + id).val()) || 0;
    var orderedqty = parseFloat($("#orderedqty-" + id).val()) || 0;
    if ((qty + trasferedqty) > orderedqty) {
        $("#amount-" + id).val(oldqty);
        Swal.fire({
            icon: 'error',
            title: 'Invalid Quantity',
            text: 'The sum of the previous SO quantity and the current SO quantity is greater than the QT quantity'
        });
    }
}

$(document).ready(function() {
    $('[data-toggle="popover"]').popover(); 
    $(document).on('click', function(e) {
        if (!$(e.target).closest('[data-toggle="popover"], .popover').length) {
            $('[data-toggle="popover"]').popover('hide');
        }
    });
   //  $('#histable').DataTable({responsive: false});
   $.validator.addMethod("greaterThanZero", function(value, element) {
       return this.optional(element) || parseFloat(value) > 0;
   }, "Total must be greater than 0");
   $("#data_form").validate({
      ignore: [], // Important: Do not ignore hidden fields (used by summernote)
      rules: {
         refer: { required: true },
         customer_purchase_order: { required: true },
         customer_order_date: { required: true },
         invocieduedate: { required: true },
         total: { 
            required: true,
            greaterThanZero: true // Use the custom validation method
        },
        customer_contact_number: {
            phoneRegex :true
        },
      },
      messages: {
         // refer: "Enter Our Reference",
         // customer_purchase_order: "Customer Purchase Order",
         // customer_order_date: "Customer purchse order date",
         // invocieduedate: "Delivery Deadline",
         total: "Enter at least one product quantity",
         customer_contact_number: "Enter a Valid Number"
      },
      errorElement: "em",
      errorPlacement: function(error, element) {
         error.addClass("help-block");
         if (element.prop("type") === "checkbox") {
            error.insertAfter(element.parent("label"));
         } else {
            error.insertAfter(element);
         }
      },
      highlight: function(element, errorClass, validClass) {
         $(element).parents(".col-sm-5").addClass("has-error").removeClass("has-success");
      },
      unhighlight: function(element, errorClass, validClass) {
         $(element).parents(".col-sm-5").addClass("has-success").removeClass("has-error");
      },
      invalidHandler: function(event, validator) {
         // Focus on the first invalid element
         if (validator.errorList.length) {
            $(validator.errorList[0].element).focus();
         }
      }
   });
   $('#new-saleorder-btn').prop('disabled',false);
});
    $('#new-saleorder-btn').on('click', function(e) {
        e.preventDefault(); // Prevent the default form submission
        $('#new-saleorder-btn').prop('disabled',true);
        var quoteid = $("#quote_id").val();
        let isValid = false;
        $(".amnt").each(function () {
                if (parseFloat($(this).val()) > 0) {
                    isValid = true;
                    return false;
                }
        });
        if (isValid==false) {
            $('#new-saleorder-btn').prop('disabled', false); 
                Swal.fire({
                    icon: "error",
                    title: "Invalid Quantity",
                    text: "At least one product quantity must be greater than zero.",
                });
                return;
        }
        // Validate the form
        if ($("#data_form").valid()) {
        
            var form = $('#data_form')[0]; // Get the form element
            var formData = new FormData(form); // Create FormData object
            // var formData =  $("#data_form").serialize();
            formData.append('completed_status', 1);
            var action_url= $('#action-url').val();
            Swal.fire({
                    title: "Are you sure?",
                    text: "Do you want to create a Sales order?",
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
                        $.ajax({
                            url: baseurl + 'quote/salesorder_sub_action', 
                            // url: baseurl + 'quote/saleorderaction', 
                            // url: baseurl + action_url, 
                            type: 'POST',
                            data: formData,
                            contentType: false, // Prevent jQuery from setting content type
                            processData: false, // Prevent jQuery from transforming the data
                            success: function(response) {
                                var jsonResponse = typeof response === "object" ? response : JSON.parse(response);

                                var status = jsonResponse.status;
                                if (status === 'Success') {
                                    window.location.href = baseurl + 'SalesOrders';
                                    // window.location.href = baseurl + 'SalesOrders/salesorder_new?id='+quoteid+'&token=1';
                                }                     
                            },
                            error: function(xhr, status, error) {
                                Swal.fire('Error', 'An error occurred while generating the lead', 'error');
                                console.log(error); // Log any errors
                            }
                        });
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        $('#new-saleorder-btn').prop('disabled', false);
                    }
                });
        
        }
        else{
            $('#new-saleorder-btn').prop('disabled',false);
        }
    });
// });
$('#saleorder-draft-btn').on('click', function(e) {
    e.preventDefault(); // Prevent the default form submission
    $('#saleorder-draft-btn').prop('disabled',true);
    var quoteid = $("#quote_id").val();
    // if ($("#data_form").valid()) {
    
        var form = $('#data_form')[0]; // Get the form element
        var formData = new FormData(form); // Create FormData object
        // var formData =  $("#data_form").serialize();
        formData.append('completed_status', 0);
        var action_url= $('#action-url').val();
        // Swal.fire({
        //             title: "Are you sure?",
        //             text: "Do you want to save the data as draft?",
        //             icon: "question",
        //             showCancelButton: true,
        //             confirmButtonColor: '#3085d6',
        //             cancelButtonColor: '#d33',
        //             confirmButtonText: 'Yes, proceed!',
        //             cancelButtonText: "No - Cancel",
        //             reverseButtons: true,  
        //             focusCancel: true,      
        //             allowOutsideClick: false,  // Disable outside click
        //         }).then((result) => {
        //             if (result.isConfirmed) {

                        $.ajax({
                            url: baseurl + 'quote/saleorderdraftaction', 
                            // url: baseurl + 'quote/salesorder_sub_action', 
                            type: 'POST',
                            data: formData,
                            contentType: false, // Prevent jQuery from setting content type
                            processData: false, // Prevent jQuery from transforming the data
                            success: function(response) {
                                var jsonResponse = typeof response === "object" ? response : JSON.parse(response);
                                $('#saleorder-draft-btn').prop('disabled',false);
                                var status = jsonResponse.status;
                                if (status === 'Success') {
                                // Swal.fire('Success', 'Sales order has been created successfully!', 'success').then(() => {
                                //     setTimeout(function() {
                                     window.location.href = baseurl + 'SalesOrders/salesorder_new?id=' + quoteid + '&token=1';
                                    // }, 600); // 2000 milliseconds = 2 seconds
                                // });
                                }                     
                            },
                            error: function(xhr, status, error) {
                                Swal.fire('Error', 'An error occurred while generating the lead', 'error');
                                console.log(error); // Log any errors
                            }
                        });
                //     }
                //     else{
                //         $('#saleorder-draft-btn').prop('disabled',false);
                //     }
                // });

    
    // }
    // else{
    // $('#saleorder-draft-btn').prop('disabled',false);
    // }
});
function updateSalesorder(e) {
    e.preventDefault(); // Prevent the default form submission

    var quoteid = $("#quote_id1").val();
    var form = $('#data_form_edit')[0]; // Get the form element
    var formData = new FormData(form); // Create FormData object
    var action_url = $('#action-url1').val();

    $.ajax({
        url: baseurl + 'salesorders/salesorder_sub_edit_action',
        // url: baseurl + action_url, // Ensure this is the correct URL
        type: 'POST',
        data: formData,
        contentType: false, // Prevent jQuery from setting content type
        processData: false, // Prevent jQuery from processing the data
        success: function(response) {
            var jsonResponse = typeof response === "object" ? response : JSON.parse(response);

            var status = jsonResponse.status;
            if (status === 'Success') {
                window.location.href = baseurl + 'SalesOrders/salesorder_new?id=' + quoteid + '&token=1';
            } else {
                Swal.fire('Error', 'There was an issue updating the sales order.', 'error');
            }
        },
        error: function(xhr, status, error) {
            Swal.fire('Error', 'An error occurred while updating the sales order', 'error');
            console.log("XHR: ", xhr); // Debug: Check the full XHR response
            console.log("Status: ", status); // Debug: Check the status
            console.log("Error: ", error); // Debug: Check the error
        }
    });
}

function saleorderedit(salesorderid, salesordernumber){
    $('#new_sales_order_edit_model').modal('show');
    $.ajax({
        url: baseurl + 'SalesOrders/sales_order_details_by_id',
        type: 'POST',
        data: {'salesorder_id': salesorderid, 'salesordernumber':salesordernumber},
        success: function(response) {
            // Assuming 'response' contains the HTML to be appended to the modal
            $('#new_sales_order_edit_model .tablesection').html(response);
            $('#new_sales_order_edit_model').modal('show');
        },
        error: function(xhr, status, error) {
            Swal.fire({
                title: 'Error!',
                text: 'An error occurred while creating the sales order. Please try again.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    });
}



</script>