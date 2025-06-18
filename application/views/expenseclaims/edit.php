<?php       
  if (($msg = check_permission($permissions)) !== true) {
     echo $msg;
     return;
  }
 ?>
<div class="content-body">
   <div class="card">
      <div class="card-header border-bottom">
         <?php 

            $claim_number = $mastredata['claim_number'];
            $prefix = $prefix['po_prefix'];
            ?>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard') ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('expenseclaims') ?>"><?php echo $this->lang->line('Expense Claims') ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line('Expense Claim') ?> <?php echo $claim_number ?></li>
                </ol>
            </nav>
           
            
            <div class="row">
               <div class="col-xl-4 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                  <h4 class="card-title"><?php echo $this->lang->line('Expense Claim')." ".$claim_number; ?> </h4>            
                  <!-- <h4 class="card-title"><?php echo $this->lang->line('Purchase Order')." ".$claim_number; ?> </h4>             -->
               </div>
               <div class="col-xl-8 col-lg-9 col-md-6 col-sm-12 col-xs-12">  
                  <?php 
                  $generatebtn = "";
                  $approvebtn="";
                  $acceptbtn="";
                  $preparedbtn="";
                  $assigncls="disable-class";
                  if($mastredata['prepared_flg']==1)
                  {
                     $preparedbtn="d-none";
                     $required = "";
                     $compulsory = '';
                     // $required = "required";
                     // $compulsory = '<span class="compulsoryfld">*</span>';
                     $assigncls="";
                  }
                  if($mastredata['prepared_flg']!=1 || $mastredata['po_status'] =='Sent')
                  {
                     $approvebtn="d-none";
                     $acceptbtn="d-none";
                  }
                  if($mastredata['approvalflg']==1)
                  {
                     $approvebtn="d-none";
                     // $acceptbtn="d-none";
                  }

                  
                 

                  $frmelmentdisable = "";
                  $frmselectdisable = "";
                  $accpetthenhide = "";
                  $frmbtndisable="";
                  // Using switch to handle different conditions
                  switch (true) {
                      case ($mastredata['po_status'] == 'Sent'):
                          $frmelmentdisable = "readonly";
                          $frmselectdisable = "textarea-bg disable-class";
                          $accpetthenhide = "disable-class";
                          $frmbtndisable= "disabled";
                          break;
                  
                      case ($mastredata['approvalflg'] == '1' && $mastredata['prepared_flg'] == '1' && $mastredata['approved_by'] != $this->session->userdata('id')):
                          $frmelmentdisable = "readonly";
                          $frmselectdisable = "textarea-bg disable-class";
                          $frmbtndisable= "disabled";
                          break;
                  
                      default:
                          $frmelmentdisable = "";
                          $frmselectdisable = "";
                          $frmbtndisable="";
                          break;
                  }
                  ?>

                  <!-- ========================================= -->
                  <?php
                     $msgcls = "";
                     $messagetext = "";
                     $enabledisablecls="";
                     $marginbottom = "mb-2";
                     $assignseccls = "";
                     $acceptsendbtncls="";
                     switch (true) {
                        case ($mastredata['prepared_flg'] == 0):
                           $msgcls = "d-none";
                           $enabledisablecls ="d-none";
                           $marginbottom = "";
                           $assignseccls = "d-none";
                           break;

                        case ($mastredata['approvalflg'] != 1 && $mastredata['prepared_flg'] == 1):
                           $messagetext = "Waiting for approval";
                           $enabledisablecls ="";
                           $msgcls = "";
                           $acceptsendbtncls ="d-none";
                           break;

                        case ($mastredata['approved_by']!=$this->session->userdata('id') && $mastredata['po_status'] == "Assigned"):
                           $messagetext = "Please Accept the Purchase Order";
                           $msgcls = "";
                           $enabledisablecls ="disable-class";
                           break;
                        case ($mastredata['approved_by']==$this->session->userdata('id') && $mastredata['po_status'] == "Assigned"):
                           $msgcls = "";
                           $messagetext = $assignedperson['name']." has not Sent this purchase order yet. Assigned Date : ".date('d-m-Y h:i:s A', strtotime($mastredata['approved_dt']));
                           break;

                        case ($mastredata['po_status'] == "Sent"):
                           $messagetext = "Current Status : Already Sent";
                           $msgcls = "";
                           $enabledisablecls ="";
                           break;
                        case ($mastredata['po_status'] == "Reverted"):
                           $messagetext = "Purchase Order Reverted. Now you can Reassign & Update  or Send Purchase Order from here";
                           $msgcls = "";
                           $enabledisablecls ="";
                           break;

                        default:
                           // No action needed for the default case
                           break;
                     }
                     ?>    
                     <div class="btn-group alert alert-danger text-center <?=$msgcls?>" role="alert">
                        <?php echo $messagetext; ?>
                     </div>
                  <!-- ========================================= -->
               </div>
               <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
               <div class="heading-elements">
                     <ul class="list-inline mb-0">
                        <li><a class="breaklink" data-action="expand"><i class="ft-maximize"></i></a></li>
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

          
            <!-- ========================= Buttons start ===================== -->
            <form method="post" id="data_form1" autocomplete="off">
                <input type="hidden" name="iid" value="<?=$mastredata['id']?>">
                <input type="hidden" name="transaction_number" value="<?=$mastredata['transaction_number']?>">
               <div class="row">
                  <div class="col-xl-4 col-lg-3 col-md-12 col-sm-12 col-xs-12 cmp-pnl">
                     <div id="customerpanel" class="inner-cmp-pnl">
                        <div class="form-group row">
                           <div class="col-sm-12">
                              <h3 class="title-sub">
                              <?php echo $this->lang->line('Supplier Details') ?> 
                           </div>
                           <div class="frmSearch col-sm-12">
                              <label for="cst"class="col-form-label"><?php echo $this->lang->line('Search Supplier') ?> </label>
                              <input type="text" class="form-control required" name="cst" id="supplier-box"
                                 placeholder="Enter Supplier Name or Mobile Number to search"
                                 autocomplete="off" required <?=$frmelmentdisable?>/>
                              <div id="supplier-box-result"></div>
                           </div>
                        </div>
                        <div id="customer">
                           <div class="clientinfo">
                              <input type="hidden" name="customer_id" id="customer_id" value="<?=$mastredata['supplierid']?>">
                              <div id="customer_name">
                                        <?php echo '  
                                            <div id="customer_name"><strong>' . $mastredata['supplier'] . '</strong></div>
                                        </div>
                                        <div class="clientinfo">

                                            <div id="customer_address1"><strong>' . $mastredata['supplieraddress'] . '<br>' . $mastredata['suppliercity']  . '</strong></div>
                                        </div>

                                        <div class="clientinfo">

                                            <div type="text" id="customer_phone">Phone : <strong>' . $mastredata['supplierphone'] . '</strong><br>Email <strong>' . $mastredata['supplieremail'] . '</strong></div>
                                        </div>'; ?>
                           </div>
                           <div class="clientinfo">
                              <div id="customer_address1"></div>
                           </div>
                           <div class="clientinfo">
                              <div type="text" id="customer_phone"></div>
                           </div>
                        </div>
                        <div class="form-group row">
                           
                           
                        </div>
                       
                     </div>
                  </div>
                  <div class="col-xl-8 col-lg-9 col-md-12 col-sm-12 col-xs-12 cmp-pnl">
                     <div class="inner-cmp-pnl">
                        <div class="form-group row">
                           <div class="col-sm-12">
                              <h3
                                 class="title-sub"><?php echo $this->lang->line('Claim Details') ?> </h3>
                           </div>

                           <input type="hidden" name="po_id" id="po_id" value="<?php echo $poid; ?>">
                           <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                              <label for="invocieno"
                                 class="col-form-label"><?php echo $this->lang->line('Expense Claim Number') ?> </label>
                              <div class="input-group">
                                 <div class="input-group-addon"><span class="icon-file-text-o"
                                    aria-hidden="true"></span></div>
                                 <!-- <input type="hidden" class="form-control" placeholder="Purchase Order #" name="invocieno" value="<?php echo $invoicetid; ?>" readonly> -->
                                 <input type="text" class="form-control" placeholder="claim number" name="claim_number" value="<?php echo $claim_number; ?>" readonly>
                              </div>
                           </div>
                           <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                              <label for="invocieno"
                                 class="col-form-label"><?php echo $this->lang->line('Due Date') ?> <span class="compulsoryfld"> *</span></label>                              
                                 <input type="date" class="form-control" placeholder="claim_due_date"
                                    name="claim_due_date" value="<?php echo $mastredata['claim_date'] ?>" <?=$frmelmentdisable?>>
                           </div>
                           <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                              <label for="invocieno"
                                 class="col-form-label"><?php echo $this->lang->line('Employee') ?> <span class="compulsoryfld"> *</span></label> 
                                 <select name="employee_id" id="employee_id" class="form-control">
                                    <option value=""><?php echo $this->lang->line('Select an Employee'); ?></option>
                                    <?php
                                    if($employee)
                                    {
                                        foreach ($employee as $key => $value) {
                                            $selt ="";
                                            if($value['id'] == $mastredata['employee_id'])
                                            {
                                                $selt ="selected";  
                                            }
                                            echo "<option value='".$value['id']."' data-id='".$value['expense_claim_approver']."' $selt>".$value['name']."</option>";
                                        }
                                    }
                                    ?>
                                 </select>
                           </div>
                                <input type="hidden" name="" id="id" value="<?=$mastredata['id']?>">
                                <input type="hidden" name="claim_number" id="claim_number" value="<?=$mastredata['claim_number']?>">
                           
                           <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                              <label for="invocieno"
                                 class="col-form-label"><?php echo $this->lang->line('Approver') ?> <span class="compulsoryfld"> *</span></label>     
                                 <select name="approver_id" id="approver_id" class="form-control">
                                 <option value="<?=$mastredata['approver_id']?>"><?=$mastredata['approver']?></option>
                                 </select>
                           </div>

                           <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                              <label for="invocieno"
                                 class="col-form-label"><?php echo $this->lang->line('Category') ?> <span class="compulsoryfld"> *</span></label> 
                                 <select name="claim_category_id" id="claim_category_id" class="form-control">
                                    <?php
                                    if($category)
                                    {
                                        foreach ($category as $key => $value) {
                                            $sel ="";
                                            if($value['transcat_id'] == $mastredata['claim_category_id'])
                                            {
                                                $sel ="selected";  
                                            }
                                            echo "<option value='".$value['transcat_id']."' $sel>".$value['transcat_name']."</option>";
                                        }
                                    }
                                    ?>
                                 </select>
                           </div>

                           <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 col-xs-12">
                              <label for="invocieno"
                                 class="col-form-label"><?php echo $this->lang->line('Note') ?></label> 
                                 <textarea name="note" id="note" class="form-textarea"><?=$mastredata['note']?></textarea>
                           </div>
                           <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                              <label for="toAddInfo" class="col-form-label"></label>
                              <button type="button" class="btn btn-crud btn-sm btn-secondary mt-3" id="attachment-btn"><i class="fa fa-paperclip" aria-hidden="true"></i> Add Attachment</button>
                           </div>
                           
                        </div>
                     </div>
                  </div>
               </div>
               <?php   
                    if($mastredata['po_status']=='Draft')
                    { ?>
                        <!-- <div class="alert alert-warning alert-success fade show" role="alert">
                            <strong>Draft</strong> Saved Successfully.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div> -->
                    <?php } ?>
               <div id="saman-row" class="table-scroll">
                  <table class="table table-striped table-bordered zero-configuration dataTable">
                     <thead>
                        <tr class="item_header bg-gradient-directional-blue white">
                           <th width="10%" class="text-center1 pl-1"><?php echo $this->lang->line('Code') ?></th>
                           <th width="25%" class="text-center1 pl-1"><?php echo $this->lang->line('Item Name') ?></th>
                           <th width="8%" class="text-left"><?php echo $this->lang->line('Quantity') ?></th>
                           <th width="10%" class="text-right"><?php echo $this->lang->line('Rate') ?></th>
                           <!-- <th width="10%" class="text-center"><?php echo $this->lang->line('Tax') ?>(%)</th>
                           <th width="10%" class="text-center"><?php echo $this->lang->line('Tax') ?></th>
                           <th width="7%" class="text-left"><?php echo $this->lang->line('Discount') ?></th>
                           <th width="4%" class="text-center"><?php echo $this->lang->line('Free Of Charge') ?></th> -->
                           <th width="10%" class="text-right">
                              <?php echo $this->lang->line('Amount') ?>
                              (<?php echo $this->config->item('currency'); ?>)
                           </th>
                           <th width="5%" class="text-center"><?php echo $this->lang->line('Action') ?></th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php 
                        $i = 0;
                         $totaldiscount =0;
                         $grandtotal =0;
                        if(!empty($products))
                        {
                           foreach ($products as $row) {
                              echo '<tr>
                              <td><input type="text" placeholder="Search by Item No." class="form-control code" name="code[]" id="expensecode-' . $i . '" value="' . $row['product_code'] . '" '. $frmelmentdisable.'><input type="hidden" class="form-control" name="hsn[]" id="unit-' . $i . '" value="' . $row['product_code'] . '" ><input type="hidden" class="form-control" name="expense_account_number[]" id="expense_account_number-' . $i . '" value="' . $row['claim_account_code'] . '"></td>
                              <td><span class="d-flex"><input type="text" class="form-control" name="product_name[]" placeholder="Enter Product name"  value="' . $row['product_name'] . '" '. $frmelmentdisable.' id="expenseproductname-' . $i . '">&nbsp;';
                              ?>
                              <button type="button" title="change account"
                                       class="btn  btn-crud btn-sm btn-secondary"
                                       id="btnclk-<?= $i ?>"
                                       data-toggle="popover"
                                       onclick="loadPopover(<?= $i ?>)"
                                       data-html="true"
                                       data-content='
                                             <form id="popoverForm-<?= $i ?>">
                                                <div class="form-group">
                                                   <label for="accountList-<?= $i ?>">Select Account</label>
                                                   <select class="form-control" id="accountList-<?= $i ?>">
                                                         <!-- Options will be loaded dynamically -->
                                                   </select>
                                                </div>
                                                <div class="text-right"><button type="button" onclick="cancelPopover(<?= $i ?>)" class="btn  btn-crud btn-secondary btn-sm">Cancel</button>&nbsp;<button type="button" onclick="change_product_account(<?= $i ?>)" class="btn  btn-crud btn-primary btn-sm">Change</button></div>
                                             </form>'
                                    >
                                       <i class="fa fa-bank"></i>
                                    </button></span>
                              <?php
                              echo '</td><td><input type="text" class="form-control req amnt" name="product_qty[]" id="amount-' . $i . '"
                                       onkeypress="return isNumber(event)" onkeyup="rowTotal(' . $i . '), billUpyog(), expenseclaim_discount()"
                                       autocomplete="off" value="' . intval($row['quantity']) . '" '. $frmelmentdisable.'><input type="hidden" name="old_product_qty[]" value="' . intval($row['quantity']) . '"></td>
                              
                                       <td><input type="text" class="form-control req prc text-right responsive-width-elements" name="product_price[]" id="price-' . $i . '"  onkeypress="return isNumber(event)" onkeyup="checkCost(' . $i . '), rowTotal(' . $i . '), billUpyog(), expenseclaim_discount()"  autocomplete="off" value="' . ($row['price']) . '" '. $frmelmentdisable.'></td>';

                              echo '</td>
                              <td class="text-right"><strong><span class="ttlText" id="result-' . $i . '">' . ($row['total']) . '</span></strong>
                               <div class="costvaluation_section" id="costvaluation_section-' . $i . '"><strong class="text-danger" id="cost_warning_val-' . $i . '"></strong></div></td>';

                              echo '<td class="text-center">
                                <button onclick="single_product_details(' . $i . ')" type="button" class="btn  btn-crud btn-sm btn-secondary" title="Product Details">
                                    <i class="fa fa-info"></i>
                                </button>
                                <button type="button" data-rowid="0" class="btn  btn-crud btn-sm btn-secondary removeProd" title="Remove">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>';

                              
                              echo '<input type="hidden" name="taxa[]" id="taxa-' . $i . '" value="' . ($row['totaltax']) . '">
                              <input type="hidden" class="ttInput" name="product_subtotal[]" id="total-' . $i . '" value="' . ($row['total']) . '">
                              <input type="hidden"  name="product_subtotal_old[]"  value="' . ($row['total']) . '">
                              <input type="hidden" class="pdIn" name="pid[]" id="pid-' . $i . '" value="' . $row['pid'] . '">
                              </tr>';
                              $i++;
                           }
                        } 
                        
                        else{
                        ?>
                           <tr class="startRow">
                           <td><input type="text" placeholder="Search by Item No." class="form-control code" name="code[]" id="expensecode-0" value=""><input type="hidden" class="form-control" name="hsn[]" id="hsn-0" value="" readonly><input type="hidden" class="form-control" name="expense_account_number[]" id="expense_account_number-0">
                           </td>
                              <td><span class="d-flex"><input type="text" class="form-control" name="product_name[]"
                                 placeholder="<?php echo $this->lang->line('Enter Product name') ?>"
                                 id='expenseproductname-0'>&nbsp;
                                 <button type="button" title="change account"
                                       class="btn btn-crud btn-sm btn-secondary"
                                       id="btnclk-<?= $i ?>"
                                       data-toggle="popover"
                                       onclick="loadPopover(<?= $i ?>)"
                                       data-html="true"
                                       data-content='
                                             <form id="popoverForm-<?= $i ?>">
                                                <div class="form-group">
                                                   <label for="accountList-<?= $i ?>">Select Account</label>
                                                   <select class="form-control" id="accountList-<?= $i ?>">
                                                         <!-- Options will be loaded dynamically -->
                                                   </select>
                                                </div>
                                                <div class="text-right"><button type="button" onclick="cancelPopover(<?= $i ?>)" class="btn btn-crud btn-secondary btn-sm">Cancel</button>&nbsp;<button type="button" onclick="change_product_account(<?= $i ?>)" class="btn btn-crud btn-primary btn-sm">Change</button></div>
                                             </form>'
                                    >
                                       <i class="fa fa-bank"></i>
                                    </button></span>
                              </td>
                              
                              <td><input type="text" class="form-control req amnt" name="product_qty[]" id="amount-0"
                                 onkeypress="return isNumber(event)" onkeyup="rowTotal('0'), billUpyog(),expenseclaim_discount()"
                                 autocomplete="off" value="0">
                                
                              <td><input type="text" class="form-control req prc text-right responsive-width-elements" name="product_price[]" id="price-0"
                                 onkeypress="return isNumber(event)" onkeyup="checkCost('0'), rowTotal('0'), billUpyog(), expenseclaim_discount()"
                                 autocomplete="off" value="0" ></td>
                              <td class="d-none"><input type="text" class="form-control vat " name="product_tax[]" id="vat-0"
                                 onkeypress="return isNumber(event)" onkeyup="rowTotal('0'), billUpyog()"
                                 autocomplete="off"></td>
                              <td class="text-right">
                                 <strong><span class='ttlText' id="result-0">0</span></strong>
                                 <div class="costvaluation_section" id="costvaluation_section-0"><strong class="text-danger" id="cost_warning_val-0"></strong></div>
                              </td>
                              <td class="text-center"> <button onclick="single_product_details('0')" type="button" class="btn  btn-crud btn-sm btn-secondary" title="Product Details"><i class="fa fa-info"></i></button> <button type="button" data-rowid="0" class="btn  btn-crud btn-sm btn-secondary removeProd" title="Remove"> <i class="fa fa-trash"></i> </button></td>
                              <input type="hidden" name="taxa[]" id="taxa-0" value="0">
                              <input type="hidden" name="disca[]" id="disca-0" value="0">
                              <input type="hidden" class="ttInput" name="product_subtotal[]" id="total-0" value="0">
                              <input type="hidden" class="pdIn" name="pid[]" id="pid-0" value="0">
                              <input type="hidden" name="unit[]" id="unit-0" value="">
                           </tr>
                        <?php 
                        } ?>
                        <tr class="last-item-row tr-border">
                           <td class="add-row no-border">
                              <?php 
                              if(!empty($mastredata['approved_by']) && ($mastredata['approved_by']!=$this->session->userdata('id') && $mastredata['prepared_flg']==1))
                              { ?> <?php }else { ?>
                              <button type="button" class="btn btn-crud btn-secondary  btn-crud <?=$accpetthenhide?>" aria-label="Left Align"
                                 id="addexpenseproduct"><i class="fa fa-plus-square"></i> <?php echo $this->lang->line('Add Row') ?>
                              </button>
                              <?php } ?>
                           </td>
                           <td colspan="5" class="no-border"></td>
                        </tr>
                        <tr class="sub_c d-none" style="display: table-row;">
                           <td colspan="4" align="right" class="no-border"><input type="hidden" value="0" id="subttlform"
                              name="subtotal"><strong><?php echo $this->lang->line('Total Tax') ?></strong>
                           </td>
                           <td align="left" colspan="2" class="no-border"><span
                              class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>
                              <span id="taxr" class="lightMode">0</span>
                           </td>
                        </tr>
                        <tr class="sub_c" style="display: table-row;">
                              <td colspan="3" class="no-border"></td>
                              <td colspan="2" align="right" class="no-border"><strong><?php echo $this->lang->line('Sub Total') ?>
                                       (<span class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>)</strong>
                              </td>

                              <td align="right" colspan="2" class="no-border">
                                 <span id="grandamount"><?=number_format($mastredata['claim_subtotal'],2)?></span>
                              </td>
                           </tr>
                        <tr class="sub_c" style="display: table-row;">
                           <td colspan="5" align="right" class="no-border">
                              <strong><?php echo $this->lang->line('Discount In') ?></strong>
                           </td>
                           <td align="right" colspan="4" class="no-border"><span
                              class="currenty lightMode"></span>
                              <select name="discount_type" id="discount_type" style="width:fit-content;" class="form-control form-select" onchange="expenseclaim_discount()">
                                <option value="Percentage" <?php if($mastredata['discount_type']=='Percentage') { echo "selected"; }?>>Percentage</option>
                                <option value="Amount" <?php if($mastredata['discount_type']=='Amount') { echo "selected"; }?>>Amount</option>
                              </select>
                           </td>
                        </tr>
                        <tr class="sub_c" style="display: table-row;">
                           <td colspan="5" align="right" class="no-border">
                              <strong><?php echo $this->lang->line('Discount') ?></strong>
                           </td>
                           <td align="right" colspan="5" class="no-border"><span
                              class="currenty lightMode"></span>
                              <input type="hidden" class="form-control text-right"   name="old_claim_discount" id="old_order_discount" autocomplete="off"  value="0">
                              <input type="number" class="form-control text-right" onkeypress="return isNumber(event)"  placeholder="0.00"  name="claim_discount" id="order_discount" autocomplete="off" onkeyup="expenseclaim_discount()" value="<?=$mastredata['claim_discount']?>">
                              <input type="hidden" class="form-control text-right" name="claim_discount_amount" id="claim_discount_amount" autocomplete="off" value="<?=$mastredata['claim_discount_amount']?>">
                              <input type="hidden" class="form-control text-right" name="claim_discount_amount_old" id="claim_discount_amount_old" autocomplete="off" value="<?=$mastredata['claim_discount_amount']?>">
                           </td>
                        </tr>
                  
                        <tr class="sub_c" style="display: table-row;">
                           <!-- <td colspan="2" class="no-border">
                              <?php if ($exchange['active'] == 1){
                                 echo $this->lang->line('Payment Currency client') . ' <small>' . $this->lang->line('based on live market') ?></small>
                              <select name="mcurrency"
                                 class="selectpicker form-control">
                                 <option value="0">Default</option>
                                 <?php foreach ($currency as $row) {
                                    if(strtolower($row['symbol']) == 'qar')
                                    {
                                       echo '<option value="' . $row['id'] . '">' . $row['symbol'] . ' (' . $row['code'] . ')</option>';
                                    }
                                    
                                    } ?>
                              </select>
                              <?php } ?>
                           </td> -->
                           <td colspan="5" align="right" class="no-border"><strong><?php echo $this->lang->line('Total') ?>
                              (<span
                                 class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>)</strong>
                           </td>
                           <td align="right" colspan="2" class="no-border">
                              <span id="grandtotaltext"><?=number_format($mastredata['claim_total'],2)?></span>
                              <input type="hidden" name="total" class="form-control" id="invoiceyoghtml" readonly value="<?=$mastredata['claim_total']?>">
                              <input type="hidden" name="total_old" class="form-control"  readonly value="<?=$mastredata['claim_total']?>">
                             
                           </td>
                        </tr>
                        <tr class="sub_c" style="display: table-row;">
                          
                           <td colspan="" class="no-border">
                              <?php 
                              $draftcls ="";
                              if((!empty($mastredata['approved_by'])) && ($mastredata['approved_by']!=$this->session->userdata('id') && $mastredata['prepared_flg']==1) && ($mastredata['approvalflg']=='1'))
                              {
                                 $draftcls ="d-none";
                               ?>
                              <button type="button" class="btn  btn-crud btn-lg btn-secondary revert-btncolor <?=$assign_personcls?> <?=$revertbtncls?> <?=$generatebtn?> <?=$accpetthenhide?>" id="revert-btn"><?php echo $this->lang->line('Revert To') ?></button>&nbsp;
                              <?php }
                              if((!empty($mastredata['approved_by'])) && ($mastredata['approved_by']==$this->session->userdata('id') && $mastredata['prepared_flg']==1) && ($mastredata['approvalflg']=='1'))
                              {
                                 $draftcls ="";
                                 $revertcls = ($mastredata['po_status']=='Reverted') ? "disable-class" :"";
                               ?>
                                 <button type="button" class="btn  btn-crud btn-lg btn-secondary revert-btncolor <?=$assign_personcls?> <?=$revertbtncls?> <?=$generatebtn?> <?=$accpetthenhide?> <?=$revertcls?>" id="revert-by-admin-btn"><?php echo $this->lang->line('Revert To') ?></button>&nbsp;
                              <?php }
                              ?>
                           </td>
                           <td align="right" colspan="5" class="no-border">

                              <!-- <input type="submit" class="btn btn-lg btn-secondary sub-btn"  value="<?php echo $this->lang->line('Cancel') ?>" id="submit-expense-claimbtn-draft" data-loading-text="Creating..."> -->

                              <input type="submit" class="btn  btn-crud btn-lg btn-primary sub-btn <?=$generatebtn?> <?=$accpetthenhide?>"  value="<?php echo $this->lang->line('Update') ?>" id="submit-expense-claimbtn" data-loading-text="Creating...">


                           </td>
                        </tr>
                     </tbody>
                  </table>
               </div>
               <!-- <input type="hidden" value="purchase/action" id="action-url"> -->
               
               <input type="hidden" value="puchase_search" id="billtype">
               <input type="hidden" value="<?=$i-1?>" name="counter" id="ganak">
               <input type="hidden" value="<?php echo $this->config->item('currency'); ?>" name="currency">
               <input type="hidden" value="<?= $taxdetails['handle']; ?>" name="taxformat" id="tax_format">
               <input type="hidden" value="<?= $taxdetails['format']; ?>" name="tax_handle" id="tax_status">
               <input type="hidden" value="yes" name="applyDiscount" id="discount_handle">
               <input type="hidden" value="<?= $this->common->disc_status()['disc_format']; ?>"
                  name="discountFormat" id="discount_format">
               <input type="hidden" value="<?= amountFormat_general($this->common->disc_status()['ship_rate']); ?>"
                  name="shipRate"
                  id="ship_rate">
               <input type="hidden" value="<?= $this->common->disc_status()['ship_tax']; ?>" name="ship_taxtype"
                  id="ship_taxtype">
               <input type="hidden" value="0" name="ship_tax" id="ship_tax">
            </form>
         </div>
      </div>
   </div>
</div>


<!-- ======================Additional Forms sms,email,cancel etc starts ========================== -->
<script>
$(document).ready(function() {
   $("#employee").prop('required', false);
   $("#data_form1").validate($.extend(true, {}, globalValidationOptions,{
        rules: {
            cst: {
               required: function() {
                  return $('#customer_id').val() == 0;
               }
         },
         claim_due_date: {required:true},
         employee_id: {required:true},
         approver_id: {required:true},
         claim_category_id: {required:true},
        },
        messages: {
            cst    : "Search Supplier",
            claim_due_date  : "Enter Due Date",
            employee_id  : "Select an Employee",
            approver_id  : "Select an Approver",
            claim_category_id  : "Select Category"
        }
    }));
    $("#employee_id").select2();
    $("#approver_id").select2();
    $("#claim_category_id").select2();
});

$(".purchase-approve-btn").on("click", function(e) {

    e.preventDefault();
    var selectedProducts1 = [];
    var validationFailed = false;
    $('.purchase-approve-btn').prop('disabled', true);
    var assignto = $('#employee').val();
    if(assignto=="")
    {
      $("#employee").prop('required', true);
    }
    var rateflg=0;
    $('.amnt').each(function() {
         if ($(this).val() > 0) {
            var elementId = $(this).attr('id');
            var lastChar = elementId.slice(-1);
            var priceElement = $("#price-" + lastChar); 
            if (priceElement.val() > 0) {
               priceElement.rules('remove', 'required');
            } else {
               rateflg =1;
               priceElement.val("");
               priceElement.rules('add', {
                  required: true,
                  messages: {
                     required: "This field is required."
                  }
               });
               $(".help-block").css("display", "block");

            }
            selectedProducts1.push({
                  value: $(this).val()
            });
         }
   });
    if ($("#data_form1").valid()) {
      // var selectedProducts1 = [];
      // $('.amnt').each(function() {
      //       if($(this).val()>0)
      //       {
      //          selectedProducts1.push($(this).val());
      //       }
      // });

      if (selectedProducts1.length === 0) {
            Swal.fire({
            text: "To proceed, please enter quantity for at least one item",
            icon: "info"
         });
         $('.purchase-approve-btn').prop('disabled', false);
            return;
      }
      if (rateflg == 1) {
            Swal.fire({
            text: "To proceed, please enter rate for each item",
            icon: "info"
         });
         $('#purchase-approve-btn').prop('disabled', false);
            return;
      }
      Swal.fire({
            title: "Are you sure?",
            // text: "Are you sure you want to update inventory? Do you want to proceed?",
            "text":"Do you want to Approve this Purchase Order?",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, proceed!',
            cancelButtonText: "No - Cancel",
            reverseButtons: true,
            focusCancel: true
      }).then((result) => {
            if (result.isConfirmed) {
               var formData = $("#data_form1").serialize(); 
               formData += '&completed_status=1';
               $.ajax({
                  type: 'POST',
                  url: baseurl +'purchase/approval_action',
                  data: formData,
                  success: function(response) {
                     window.location.href = baseurl + 'purchase'; 
                  },
                  error: function(xhr, status, error) {
                        // Handle error
                        console.error(xhr.responseText);
                  }
               });
            }
            else{
               $('.purchase-approve-btn').prop('disabled', false);
            }
      });
    }
    else{
         $('.purchase-approve-btn').prop('disabled', false);
      }
});
$(".purchase-send-btn").on("click", function(e) {
    e.preventDefault();
    var selectedProducts1 = [];
    var validationFailed = false;
    $('.purchase-send-btn').prop('disabled', true);
    $("#employee").prop('required', false);
    var rateflg=0;
    $('.amnt').each(function() {
         if ($(this).val() > 0) {
            var elementId = $(this).attr('id');
            var lastChar = elementId.slice(-1);
            var priceElement = $("#price-" + lastChar); 
            if (priceElement.val() > 0) {
               priceElement.rules('remove', 'required');
            } else {
               rateflg =1;
               priceElement.val("");
               priceElement.rules('add', {
                  required: true,
                  messages: {
                     required: "This field is required."
                  }
               });
               $(".help-block").css("display", "block");

            }
            selectedProducts1.push({
                  value: $(this).val()
            });
         }
   });
    if ($("#data_form1").valid()) {
      // $('.amnt').each(function() {
      //       if($(this).val()>0)
      //       {
      //          selectedProducts1.push($(this).val());
      //       }
      // });
      if (selectedProducts1.length === 0) {
            Swal.fire({
            text: "To proceed, please enter quantity for at least one item",
            icon: "info"
         });
         $('.purchase-send-btn').prop('disabled', false);
            return;
      }
      if (rateflg == 1) {
            Swal.fire({
            text: "To proceed, please enter rate for each item",
            icon: "info"
         });
         $('#purchase-send-btn').prop('disabled', false);
            return;
      }
      Swal.fire({
            title: "Are you sure?",
            // text: "Are you sure you want to update inventory? Do you want to proceed?",
            "text":"Do you want to Send this Purchase Order Now?",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, proceed!',
            cancelButtonText: "No - Cancel",
            reverseButtons: true,
            focusCancel: true
      }).then((result) => {
            if (result.isConfirmed) {
               var formData = $("#data_form1").serialize(); 
               formData += '&completed_status=1';
               $.ajax({
                  type: 'POST',
                  url: baseurl +'purchase/send_po_action',
                  data: formData,
                  success: function(response) {
                     // window.location.href = baseurl + 'purchase'; 
                     location.reload();
                  },
                  error: function(xhr, status, error) {
                        // Handle error
                        console.error(xhr.responseText);
                  }
               });
            }
            else{
               $('.purchase-send-btn').prop('disabled', false);
            }
      });
    }
    else{
         $('.purchase-send-btn').prop('disabled', false);
      }
});

$("#submit-expense-claimbtn").on("click", function(e) {
    e.preventDefault();
    $('#submit-expense-claimbtn').prop('disabled', true);
    var selectedProducts1 = [];
    var totalSum = 0;
    $('.ttInput').each(function() {
        var value = parseFloat($(this).val()) || 0; // Convert value to a number, default to 0 if empty
        if (value > 0) {
            selectedProducts1.push(value);
            totalSum += value;
        }
    });

    
    if ($("#data_form1").valid()) {
        if (selectedProducts1.length === 0 || totalSum <= 0) {
            Swal.fire({
                text: "To proceed, please add at least one valid item with a valid price.",
                icon: "info"
            });
            $('#submit-expense-claimbtn').prop('disabled', false);
            return;
        }
      Swal.fire({
            title: "Are you sure?",
            // text: "Are you sure you want to update inventory? Do you want to proceed?",
            "text":"Do you want to create expense claim?",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, proceed!',
            cancelButtonText: "No - Cancel",
            reverseButtons: true,
            focusCancel: true
      }).then((result) => {
            if (result.isConfirmed) {
               var formData = $("#data_form1").serialize(); 
               $.ajax({
                  type: 'POST',
                  url: baseurl +'expenseclaims/editaction',
                  data: formData,
                  success: function(response) {
                     window.location.href = baseurl + 'expenseclaims'; 
                  },
                  error: function(xhr, status, error) {
                        // Handle error
                        console.error(xhr.responseText);
                  }
               });
            }
            else{
               $('#submit-expense-claimbtn').prop('disabled', false);
            }
      });
    }
    else{
      $('#submit-expense-claimbtn').prop('disabled', false);
    }
});
$("#submit-expense-claimbtn-draft").on("click", function(e) {
    e.preventDefault();
    var selectedProducts1 = [];
    var validationFailed = false;
    //if ($("#data_form1").valid()) {
      // Swal.fire({
      //       title: "Are you sure?",
      //       // text: "Are you sure you want to update inventory? Do you want to proceed?",
      //       "text":"Do you want to save this Purchase Order as draft?",
      //       icon: "question",
      //       showCancelButton: true,
      //       confirmButtonColor: '#3085d6',
      //       cancelButtonColor: '#d33',
      //       confirmButtonText: 'Yes, proceed!',
      //       cancelButtonText: "No - Cancel",
      //       reverseButtons: true,
      //       focusCancel: true
      // }).then((result) => {
      //       if (result.isConfirmed) {
               var formData = $("#data_form1").serialize(); 
               formData += '&completed_status=0';
               $.ajax({
                  type: 'POST',
                  url: baseurl +'purchase/draftaction',
                  data: formData,
                  success: function(response) {
                     if (typeof response === "string") {
                        response = JSON.parse(response);
                     }
                     
                     window.location.href = baseurl + 'purchase/create?id='+response.data; 
                  },
                  error: function(xhr, status, error) {
                        // Handle error
                        console.error(xhr.responseText);
                  }
               });
            // }
      // });
  // }
});

$("#revert-btn").on('click', function(){
        Swal.fire({
        title: "Are you Sure ?",
        "text":"Do yo want to revert this purchase order?",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Proceed!',
        cancelButtonText: "No, cancel",
        reverseButtons: true,
        focusCancel: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Perform the AJAX request
                $.ajax({
                    type: 'POST',
                    url: baseurl + 'purchase/revertorder_action',
                    data: {
                        po_id: $("#po_id").val()
                    },
                    dataType: 'json',
                    success: function(response) {
                        window.location.href = baseurl + 'purchase';
                    },
                    error: function(xhr, status, error) {
                        // Handle error
                        console.error(xhr.responseText);
                    }
                });
            }
        });
    });

   $("#revert-by-admin-btn").on('click', function(){
        Swal.fire({
        title: "Are you Sure ?",
        "text":"Do yo want to revert this purchase order?",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Proceed!',
        cancelButtonText: "No, cancel",
        reverseButtons: true,
        focusCancel: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Perform the AJAX request
                $.ajax({
                    type: 'POST',
                    url: baseurl + 'purchase/revertorder_by_admin_action',
                    data: {
                        po_id: $("#po_id").val()
                    },
                    dataType: 'json',
                    success: function(response) {
                        window.location.href = baseurl + 'purchase/create?id='+$("#po_id").val();
                    },
                    error: function(xhr, status, error) {
                        // Handle error
                        console.error(xhr.responseText);
                    }
                });
            }
        });
    });
    
    $("#po-accept-btn").on('click', function(){
        Swal.fire({
        title: "Are you Sure ?",
        "text":"Do yo want to Accept this Purchase Order?",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Proceed!',
        cancelButtonText: "No, cancel",
        reverseButtons: true,
        focusCancel: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Perform the AJAX request
                $.ajax({
                    type: 'POST',
                    url: baseurl + 'purchase/po_accept',
                    data: {
                        po_id: $("#po_id").val()
                    },
                    dataType: 'json',
                    success: function(response) {
                        window.location.href = baseurl + 'purchase/create?id='+$("#po_id").val();
                    },
                    error: function(xhr, status, error) {
                        // Handle error
                        console.error(xhr.responseText);
                    }
                });
            }
        });
    });

    $(function () {
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
   
       $('#sendM').on('click', function (e) {
           e.preventDefault();
   
           sendBill($('.summernote').summernote('code'));
   
       });
   });
   
   $(document).on('click', "#cancel-bill_p", function (e) {
       e.preventDefault();
   
       $('#cancel_bill').modal({backdrop: 'static', keyboard: false}).one('click', '#send', function () {
           var acturl = 'transactions/cancelpurchase';
           cancelBill(acturl);
   
       });
   });

   function discountWithTotal(numb) {
      var price = accounting.unformat($("#price-" + numb).val(), accounting.settings.number.decimal);
      var discount = accounting.unformat($("#discount-" + numb).val(), accounting.settings.number.decimal);
      total_items_count = $("#ganak").val();
      
      // Reset discount if it exceeds the price
      if (parseFloat(discount) >= parseFloat(price)) {
         $("#discount-" + numb).val(0.00);
      }

      var grandTotal = 0;
      var granddiscount = 0;
      // Loop through each item and calculate totals
      for (var i = 0; i <= total_items_count; i++) {
         var productqty = accounting.unformat($("#amount-" + i).val(), accounting.settings.number.decimal);
         var price = accounting.unformat($("#price-" + i).val(), accounting.settings.number.decimal);
         var discount = accounting.unformat($("#discount-" + i).val(), accounting.settings.number.decimal);
         
         var single_product_total = parseFloat(productqty) * parseFloat(price);
         var discountedtotal = parseFloat(single_product_total) - parseFloat(discount);
         granddiscount += parseFloat(discount);
         // Update each item's total with discount
         $("#result-" + i).text(accounting.formatNumber(discountedtotal));
         $("#total-" + i).val(accounting.formatNumber(discountedtotal));
         
         grandTotal += discountedtotal; // Accumulate grand total
      }

      // Format and display the grand total
      grandTotal = accounting.formatNumber(grandTotal);
      granddiscount = accounting.formatNumber(granddiscount);
      $("#discs").text(granddiscount);
      $("#grandtotaltext").text(grandTotal);
      $("#invoiceyoghtml").val(grandTotal); 
   }
   $("#attachment-btn").on('click',function(){
        Swal.fire({
        title: "Coming Soon",
        icon: "info",
        });
    });

    


    function loadPopover(index) {
    const popoverButton = $('#btnclk-' + index);    
    // Set up popover content and show it
    popoverButton.popover('show');

    // AJAX request to load options based on the product code
    $.ajax({
        url: baseurl + 'invoices/load_product_accounts',
        method: 'POST',
        dataType: 'json',
        data: {
            'actheader': 'Expenses',
            'accountnumber':$('#expense_account_number-'+index).val()
        },
        success: function(response) {
            if (response.status === 'Success') {
                const accountList = $('#accountList-' + index);
                accountList.empty(); // Clear any existing options
                accountList.html(response.data);
               
            } else {
                alert('Failed to load options');
            }
        },
        error: function() {
            alert('Error loading options');
        }
    });
}

// Function to handle save action within popover form
function change_product_account(index) {
    Swal.fire({
        title: 'Are you sure?',
        text: "Do you want to change the product account?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, change it!',
        reverseButtons: true,
        focusCancel: true
    }).then((result) => {
        if (result.isConfirmed) {
            var account_selected = $("#accountList-" + index).val();
            $("#expense_account_number-" + index).val(account_selected);
            $('#btnclk-' + index).popover('hide');
        }
        else{
            $('#btnclk-' + index).popover('show');
        }
    });
}

function cancelPopover(index) {
    $('#btnclk-' + index).popover('hide');
}

$('#employee_id').on('change', function() {
    var selectedOption = $(this).find(':selected');
    var employeeId = selectedOption.val();
    var employee_claim_approver = selectedOption.data('id');
    $.ajax({
        type: 'POST',
        url: baseurl + 'Expenseclaims/load_claim_appover',
        data: {
            employee_id: employeeId,
            employee_claim_approver: employee_claim_approver
        },
        success: function(response) {
            $('#approver_id').html(response);
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    });
});

</script>