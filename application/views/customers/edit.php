<div class="content-body">
    <div class="card">
        <div class="card-header border-bottom">   
         
         <nav aria-label="breadcrumb">
               <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard') ?></a></li>
                  <li class="breadcrumb-item"><a href="<?= base_url('customers') ?>"><?php echo $this->lang->line('Customers') ?></a></li>
                  <li class="breadcrumb-item active" aria-current="page"><?php echo $customer['name']; ?></li>
               </ol>
         </nav>         
            <h5 class="card-title" id="card-title"><?php echo $this->lang->line('Edit') ?> - <?php echo $customer['name']; ?>  </h5>
            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
            <div class="heading-elements">
                <ul class="list-inline mb-0">
                    <li><a class="breaklink" data-action="expand"><i class="ft-maximize"></i></a></li>
                </ul>
            </div>
        </div>
        <div class="card-body">
            <!-- erp2024 add enctype="multipart/form-data 01-06-2024 -->
            <div class="alert alert-danger alert-dismissible d-none" role="alert" id="response-alert">
                <div id="responsemsg"></div>
            </div>

            <form method="post" id="cust_data_edit_form" class="form-horizontal" enctype="multipart/form-data">
            <!-- erp2024 add enctype="multipart/form-data 01-06-2024 ends -->
                <div class="row">

                    <div class="col-md-6">
                        <h5><?php echo $this->lang->line('Billing Address') ?></h5>
                        <input type="hidden" name="customer_id" id="customer_id" value="<?php echo $customer['customer_id'] ?>">


                        <div class="form-row">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label" for="mcustomer_name"><?php echo $this->lang->line('Name') ?><span class="compulsoryfld">*</span></label>
                                <input type="text" placeholder="Name" class="form-control margin-bottom required" name="name" value="<?php echo $customer['name'] ?>" id="mcustomer_name" data-original-value="<?php echo $customer['name'] ?>">
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label" for="company"><?php echo $this->lang->line('Company') ?></label>
                                <input type="text" placeholder="Company" class="form-control margin-bottom" id="company" name="company" value="<?php echo $customer['company'] ?>" data-original-value="<?php echo $customer['company'] ?>">
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label" for="mcustomer_phone"><?php echo $this->lang->line('Phone') ?><span class="compulsoryfld">*</span></label>
                                <input type="text" placeholder="phone" class="form-control margin-bottom  required" name="phone" value="<?php echo $customer['phone'] ?>" id="mcustomer_phone" data-original-value="<?php echo $customer['phone'] ?>">
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label" for="mcustomer_email">Email<span class="compulsoryfld">*</span></label>
                                <input type="text" placeholder="email" class="form-control margin-bottom required" name="email" value="<?php echo $customer['email'] ?>" id="mcustomer_email" data-original-value="<?php echo $customer['email'] ?>">
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label" for="mcustomer_address1"><?php echo $this->lang->line('Address') ?></label>
                                <textarea placeholder="address" class="form-textarea margin-bottom" name="address" id="mcustomer_address1" data-original-value="<?php echo $customer['address'] ?>"><?php echo $customer['address'] ?></textarea>
                                <!-- <input type="text" placeholder="address" class="form-control margin-bottom" name="address" value="<?php echo $customer['address'] ?>" id="mcustomer_address1"> -->
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label" for="mcustomer_city"><?php echo $this->lang->line('City') ?></label>
                                <input type="text" placeholder="city" class="form-control margin-bottom" name="city" value="<?php echo $customer['city'] ?>" id="mcustomer_city" data-original-value="<?php echo $customer['city'] ?>">
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label" for="region"><?php echo $this->lang->line('Region') ?></label>
                                <input type="text" placeholder="region" class="form-control margin-bottom" name="region" value="<?php echo $customer['region'] ?>" id="region" data-original-value="<?php echo $customer['region'] ?>">
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label" for="mcustomer_country"><?php echo $this->lang->line('Country') ?></label>
                                <!-- <input type="text" placeholder="Country" class="form-control margin-bottom" name="country" value="<?php echo $customer['country'] ?>" id="mcustomer_country" data-original-value="<?php echo $customer['country'] ?>"> -->
                                <select name="country" id="mcustomer_country" class="form-control" data-original-value="<?php echo $customer['country'] ?>">
                                <?php
                                foreach ($countries as $row) {
                                    $cid = $row['id'];
                                    $title = $row['name'];
                                    $code = $row['code'];
                                    $selected ="";
                                    if($customer['country']==$cid){ $selected = "selected"; }
                                    echo "<option value='$cid' $selected>$title($code)</option>";
                                }
                                ?>
                                </select>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label" for="postbox"><?php echo $this->lang->line('PostBox') ?></label>
                                <input type="text" placeholder="region" class="form-control margin-bottom" name="postbox" value="<?php echo $customer['postbox'] ?>" id="postbox" data-original-value="<?php echo $customer['postbox'] ?>">
                            </div>
                        </div>
                    
                    </div>
                    <div class="col-md-6">
                        <h5><?php echo $this->lang->line('Shipping Address') ?></h5>
                        <div class="form-group">

                            <div class="input-group mt-1">
                                <div class="custom-control custom-checkbox">  <input type="checkbox" class="custom-control-input" name="customer1"         id="copy_address">  <label class="custom-control-label"       for="copy_address"><?php echo $this->lang->line('Same As Billing') ?></label>
                                </div>

                            </div>

                            <div class="alert alert-info">
                                <?php echo $this->lang->line("leave Shipping Address") ?>
                            </div>
                            <i>*<?php echo $this->lang->line("Shipping Charge is not Refundable") ?></i>
                        </div>

                        <div class="form-row">

                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label" for="mcustomer_name_for_shipping"><?php echo $this->lang->line('Name') ?></label>
                                <input type="text" placeholder="Name" class="form-control margin-bottom" name="shipping_name" value="<?php echo $customer['shipping_name'] ?>" id="mcustomer_name_for_shipping" data-original-value="<?php echo $customer['shipping_name'] ?>">
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label" for="mcustomer_phone_s"><?php echo $this->lang->line('Phone') ?></label>
                                <input type="text" placeholder="phone" class="form-control margin-bottom" name="shipping_phone" value="<?php echo $customer['shipping_phone'] ?>" id="mcustomer_phone_s" data-original-value="<?php echo $customer['shipping_phone'] ?>">
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label" for="mcustomer_email_s">Email</label>
                                <input type="text" placeholder="email" class="form-control margin-bottom" name="shipping_email" value="<?php echo $customer['shipping_email'] ?>" id="mcustomer_email_s" data-original-value="<?php echo $customer['shipping_email'] ?>">
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label" for="mcustomer_address1_s"><?php echo $this->lang->line('Address') ?></label>
                                <textarea placeholder="address" class="form-textarea margin-bottom" name="shipping_address_1" id="mcustomer_address1_s" data-original-value="<?php echo $customer['shipping_address_1'] ?>"><?php echo $customer['shipping_address_1'] ?></textarea>
                                <!-- <input type="text" placeholder="address" class="form-control margin-bottom" name="shipping_address_1" value="<?php echo $customer['shipping_address_1'] ?>" id="mcustomer_address1_s"> -->
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                	<label class="col-form-label" for="mcustomer_city_s"><?php echo $this->lang->line('City') ?></label>
                                <input type="text" placeholder="city" class="form-control margin-bottom" name="shipping_city" value="<?php echo $customer['shipping_city'] ?>" id="mcustomer_city_s" data-original-value="<?php echo $customer['shipping_city'] ?>">
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label" for="shipping_region"><?php echo $this->lang->line('Region') ?></label>
                                <input type="text" placeholder="region" class="form-control margin-bottom" name="shipping_region" value="<?php echo $customer['shipping_region'] ?>" id="shipping_region" data-original-value="<?php echo $customer['shipping_region'] ?>">
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label" for="mcustomer_country_s"><?php echo $this->lang->line('Country') ?></label>
                                <!-- <input type="text" placeholder="Country" class="form-control margin-bottom" name="shipping_country" value="<?php echo $customer['shipping_country'] ?>" id="mcustomer_country_s" data-original-value="<?php echo $customer['shipping_country'] ?>"> -->
                                <select name="shipping_country" id="mcustomer_country_s" class="form-control" data-original-value="<?php echo $customer['shipping_country'] ?>">
                                <?php
                                foreach ($countries as $row) {
                                    $cid = $row['id'];
                                    $title = $row['name'];
                                    $code = $row['code'];
                                    $selected ="";
                                    if($customer['shipping_country']==$cid){ $selected = "selected"; }
                                    echo "<option value='$cid' $selected>$title($code)</option>";
                                }
                                ?>
                                </select>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label" for="shipping_postbox"><?php echo $this->lang->line('PostBox') ?></label>
                                <input type="text" placeholder="region" class="form-control margin-bottom" name="shipping_postbox" value="<?php echo $customer['shipping_postbox'] ?>" id="shipping_postbox" data-original-value="<?php echo $customer['shipping_postbox'] ?>">
                            </div>
                        </div>
                    </div>

                    <!-- erp2024 div postion changed add new col-md-12 starts-->
                     <!-- erp2024 newly added 01-06-2024 -->
                    <div class="col-md-12">
                        <br><h5><b><?php echo $this->lang->line('Customer Details'); ?></b></h5><hr>                        
                        <div class="form-row">                                   
                            
                            <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">    
                                <label class="col-form-label" for="registration_number"><?php echo $this->lang->line('Registration Number') ?> </label>
                                <input type="text" placeholder="Registration Number" class="form-control margin-bottom b_input" name="registration_number" id="registration_number" value="<?php echo $customer['registration_number'] ?>" data-original-value="<?php echo $customer['registration_number'] ?>">
                            </div>
                            
                            <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label" for="expiry_date"><?php echo $this->lang->line('Expiry Date') ?> </label>
                                <input type="date" placeholder="Expiry Date" class="form-control margin-bottom b_input" name="expiry_date" id="expiry_date" value="<?php echo $customer['expiry_date'] ?>" data-original-value="<?php echo $customer['expiry_date'] ?>">
                            </div>
                            <!-- <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label" for="computer_card_number"><?php echo $this->lang->line('Computer Card') ?> </label>
                                <div class="col-12 row">
                                    <input type="text" placeholder="Computer Card Number" class="form-control margin-bottom b_input col-6" name="computer_card_number" id="computer_card_number"  value="<?php echo $customer['computer_card_number']; ?>" data-original-value="<?php echo $customer['computer_card_number']; ?>">
                            
                                    <input type="file" placeholder="Computer Card" class="form-control margin-bottom b_input col-3" title="computer_card_image" name="computer_card_image" id="computer_card_image" data-original-value="<?php echo $customer['computer_card_image']; ?>">
                                    <img src="<?php echo base_url().'userfiles/customers/'.$customer['computer_card_image']; ?>" style="width:100px; height:50px;" class="col-3">
                                </div>
                            </div> -->

                            <div class="col-lg-5 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label" for="computer_card_number"><?php echo $this->lang->line('Computer Card') ?> </label>
                                <div class="form-row">
                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                        <input type="text" placeholder="Computer Card Number" class="form-control margin-bottom b_input" name="computer_card_number" id="computer_card_number"  value="<?php echo $customer['computer_card_number']; ?>" data-original-value="<?php echo $customer['computer_card_number']; ?>">
                                    </div>
                                    <div class="col-lg-5 col-md-4 col-sm-6 col-xs-6 mt-responsive">
                                        <input type="file" placeholder="Computer Card" class="form-control margin-bottom b_input" title="computer_card_image" name="computer_card_image" id="computer_card_image" data-original-value="<?php echo $customer['computer_card_image']; ?>">
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 mt-responsive">
                                        <img src="<?php echo base_url().'userfiles/customers/'.$customer['computer_card_image']; ?>" style="width:100px; height:50px;">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- <div class="col-lg-5 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label" for="sponser_id"><?php echo $this->lang->line('Sponser ID') ?> </label>
                                <div class="col-12 row">
                                    <input type="text" placeholder="Sponser ID" class="form-control margin-bottom b_input col-6" name="sponser_id" id="sponser_id"  value="<?php echo $customer['sponser_id']; ?>" data-original-value="<?php echo $customer['sponser_id']; ?>">
                                    <input type="file" placeholder="Sponser image" class="form-control margin-bottom b_input col-3"  title="sponser_image" name="sponser_image" id="sponser_image" data-original-value="<?php echo $customer['sponser_image']; ?>">
                                    <img src="<?php echo base_url().'userfiles/customers/'.$customer['sponser_image']; ?>" style="width:100px; height:50px;" class="col-3">
                                </div>
                            </div> -->


                            <div class="col-lg-5 col-md-6 col-sm-12 col-xs-12">
                                <label for="sponser_id" class="col-form-label">
                                    <?php echo $this->lang->line('Sponser ID'); ?>
                                </label>
                                <div class="form-row">
                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                         <input type="text" placeholder="Sponser ID" class="form-control margin-bottom b_input" name="sponser_id" id="sponser_id"  value="<?php echo $customer['sponser_id']; ?>"> 
                                    </div>
                                    <div class="col-lg-5 col-md-4 col-sm-6 col-xs-6 mt-responsive">
                                        <input type="file" placeholder="Sponser image" class="form-control margin-bottom b_input"  title="sponser_image" name="sponser_image" id="sponser_image" data-original-value="<?php echo $customer['sponser_image']; ?>">
                                    </div>
                                    <div class="col-lg-4 col-md-5 col-sm-6 col-xs-6 mt-responsive">
                                        <img src="<?php echo base_url().'userfiles/customers/'.$customer['sponser_image']; ?>" style="width:100px; height:50px;" >
                                    </div>
                                </div>
                            </div>



                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label" for="credit_limit"><?php echo $this->lang->line('Credit Limit') ?> <span><button id="update-credit-limit" type="button" class="btn btn-crud btn-primary btn-sm">Update Credit Limit</button></span></label>
                                <input type="number" placeholder="Credit Limit" class="form-control margin-bottom b_input" name="credit_limit" id="credit_limit" value="<?php echo $customer['credit_limit']; ?>" data-original-value="<?php echo $customer['credit_limit']; ?>" readonly>
                            </div> 
                            <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label" for="credit_period"><?php echo $this->lang->line('Credit Period') ?> </label>
                                <input type="number" placeholder="No. of days" class="form-control margin-bottom b_input" name="credit_period" id="credit_period"  value="<?php echo $customer['credit_period']; ?>" data-original-value="<?php echo $customer['credit_period']; ?>">
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label" for="avalable_credit_limit"><?php echo $this->lang->line('Available')." ".$this->lang->line('Credit Limit'); ?> </label>
                                <?php
                                
                                    if(!empty($customer['avalable_credit_limit']))
                                    {
                                        $avlcreditlimt = $customer['avalable_credit_limit'];
                                    }
                                    else{
                                        $avlcreditlimt = $customer['credit_limit'];
                                    }   
                                ?>
                                <input readonly type="number"  class="form-control margin-bottom b_input" name="avalable_credit_limit" id="avalable_credit_limit"  value="<?php echo $avlcreditlimt; ?>" data-original-value="<?php echo $avlcreditlimt; ?>">
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label" for="tax_id"><?php echo $this->lang->line('Tax') ?> ID</label>
                                <input type="text" placeholder="TAX ID" class="form-control margin-bottom" id="tax_id" name="tax_id" value="<?php echo $customer['tax_id'] ?>" data-original-value="<?php echo $customer['tax_id'] ?>">
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label" for="document_id"><?php echo $this->lang->line('Document') ?> ID</label>
                                <input type="text" placeholder="Document ID" class="form-control margin-bottom b_input" id="document_id" name="document_id" value="<?php echo $customer['document_id'] ?>" data-original-value="<?php echo $customer['document_id'] ?>">
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label"  for="c_field"><?php echo $this->lang->line('Extra') ?> </label>
                                <input type="text" placeholder="Custom Field" class="form-control margin-bottom b_input" id="c_field" name="c_field" value="<?php echo $customer['custom1'] ?>" data-original-value="<?php echo $customer['custom1'] ?>">
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label" for="customergroup"><?php echo $this->lang->line('Customer group') ?></label>
                                <select name="customergroup" id="customergroup" class="form-control" data-original-value="<?php echo $customergroup['title']  ?>">   <?php  echo '<option value="' . $customergroup['id'] . '">' . $customergroup['title'] . ' (S)</option>';  foreach ($customergrouplist as $row) {      $cid = $row['id'];      $title = $row['title'];      echo "<option value='$cid'>$title</option>";  }  ?>
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label" for="language">Language</label>
                                <select name="language" id="language" class="form-control b_input" data-original-value="<?php echo $customer['lang']  ?>">  <?php  echo '<option value="' . $customer['lang'] . '">-' . ucfirst($customer['lang']) . '-</option>';  echo $langs;  ?>
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label"  for="discount"><?php echo $this->lang->line('Discount') ?> </label>
                                <input type="text" placeholder="Custom Discount"  class="form-control margin-bottom b_input" id="discount" name="discount"   value="<?php echo $customer['discount_c'] ?>" data-original-value="<?php echo $customer['discount_c'] ?>">
                            </div>

                            <!-- erp2024 newly added sales man -->
                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label" for="salesman_id"><?php echo $this->lang->line('Sales Man') ?></label>
                                <select name="salesman_id" id="salesman_id" class="form-control form-select" data-original-value="<?php echo $salesmanlist ?>">
                                    <option value="">Select Salesman</option>
                                    <?php
                                        if(!empty($salesmanlist))
                                        {
                                            foreach ($salesmanlist as $key => $value) {
                                                $sel="";
                                                if($customer['salesman_id']==$value['id'])
                                                {
                                                    $sel="selected";
                                                }
                                                echo "<option value='".$value['id']."' $sel>".ucwords($value['name'])."</option>";
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label" for="status"><?php echo $this->lang->line('Status') ?></label>
                                <select name="status" id="status" class="form-control form-select" data-original-value="<?php echo $customer['status'] ?>">
                                    <option value="Enable" <?php if($customer['status']=='Enable'){ echo 'selected'; } ?>>Enable</option>
                                    <option value="Disable" <?php if($customer['status']=='Disable'){ echo 'selected'; } ?>>Disable</option>
                                </select>
                            </div>
                            <!-- erp2024 newly added sales man -->
                            <?php 
                            // foreach ($custom_fields as $row) {
                            //     if ($row['f_type'] == 'text') { ?>
                                <!-- <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12"> 
                                    <label class="col-form-label" for="document_id"><?= $row['name'] ?></label>
                                    <input type="text" placeholder="<?= $row['placeholder'] ?>" class="form-control margin-bottom b_input" name="custom[<?= $row['id'] ?>]"  value="<?= $row['data'] ?>">
                                </div> -->
                                <?php 
                            //     }
                                
                            // }
                            ?>
                        
                        </div>
                        <br><h5><b>Contact Details</b></h5><hr> 
                        <div class="form-row">               
                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">                         
                                <label class="col-form-label" for="contact_person"><?php echo $this->lang->line('Contact Person') ?> </label>
                                <input type="text" placeholder="Contact Person's Name" class="form-control margin-bottom b_input" name="contact_person" id="contact_person" value="<?php echo $customer['contact_person'] ?>" data-original-value="<?php echo $customer['contact_person'] ?>">
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label" for="contact_designation"><?php echo $this->lang->line('Designation') ?> </label>
                                <input type="text" placeholder="Designation" class="form-control margin-bottom b_input" name="contact_designation" id="contact_designation" value="<?php echo $customer['contact_designation'] ?>" data-original-value="<?php echo $customer['contact_designation'] ?>">
                            </div>  
                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label" for="land_line"><?php echo $this->lang->line('Land Line') ?> </label>
                                <input type="text" placeholder="Land Line" class="form-control margin-bottom b_input" name="land_line" id="land_line" value="<?php echo $customer['land_line'] ?>" data-original-value="<?php echo $customer['land_line'] ?>">
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label" for="contact_phone1"><?php echo $this->lang->line('Contact Phone1') ?> </label>
                                <input type="text" placeholder="Contact Phone1" class="form-control margin-bottom b_input" name="contact_phone1" id="contact_phone1" value="<?php echo $customer['contact_phone1'] ?>" data-original-value="<?php echo $customer['contact_phone1'] ?>">
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label" for="contact_phone2"><?php echo $this->lang->line('Contact Phone2') ?> </label>
                                <input type="text" placeholder="Contact Phone2" class="form-control margin-bottom b_input" name="contact_phone2" id="contact_phone2" value="<?php echo $customer['contact_phone2'] ?>" data-original-value="<?php echo $customer['contact_phone2'] ?>">
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label" for="contact_email1"><?php echo $this->lang->line('Contact Email1') ?> </label>
                                <input type="email" placeholder="Contact Email1" class="form-control margin-bottom b_input" name="contact_email1" id="contact_email1" value="<?php echo $customer['contact_email1'] ?>" data-original-value="<?php echo $customer['contact_email1'] ?>">
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label" for="contact_email2"><?php echo $this->lang->line('Contact Email2') ?> </label>
                                <input type="email" placeholder="Contact Email2" class="form-control margin-bottom b_input" name="contact_email2" id="contact_email2" value="<?php echo $customer['contact_email2'] ?>" data-original-value="<?php echo $customer['contact_email2'] ?>">
                            </div>
                        </div> 
                    </div>
                    <!-- erp2024 div postion changed add new col-md-12 ends-->
                    
                </div>
                <div class="form-group text-right"><hr>
                    <div class="submit-section">
                        <input type="submit" id="cust_edit_submit" class="btn btn-crud btn-lg btn-primary margin-bottom"  value="Update customer" data-loading-text="Updating...">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- ============================================== -->
<div id="credit_limit_model" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">

                <h4 class="modal-title"><?php echo "Update Credit Limit" ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <!-- ======================================================= -->
                <form  method="post" id="credit-limit-form" name="credit-limit-form">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-6">
                                    <label class="col-form-label">Current Credit Limit</label><br>
                                    <strong id="creditlimit"></strong>
                                    <input type="hidden" name="creditlimit_val" id="creditlimit_val" >
                                    <input type="hidden" name="available_creditlimit_val" id="available_creditlimit_val">
                                </div>
                                <div class="col-6">
                                    <label class="col-form-label">Available Credit Limit</label><br>
                                    <strong id="available_creditlimit"></strong>
                                </div>
                                <div class="col-6">
                                    <label class="col-form-label">New Credit Limit</label><br>
                                    <input type="number" name="new_credit_limit" id="new_credit_limit" class="form-control" required>
                                </div>
                                <div class="col-6">
                                    <label class="col-form-label">Updated Available Credit Limit</label><br>
                                    <strong id="updated_credit_limit"></strong>
                                    <input type="hidden" name="updated_credit_limit_val" id="updated_credit_limit_val" value="" class="form-control">
                                </div>
                                <div class="col-12 text-right mt-2">
                                    <button type="button" class="btn btn-crud btn-secondary" data-dismiss="modal" aria-label="Close">Cancel</button>
                                    <button type="submit" class="btn btn-crud btn-primary" id="update_limit_btn">Update</button>
                                </div>
                            </div>
                        </div>
                </form>
                <!-- ======================================================= -->
            </div>
            
        </div>
    </div>
</div>
<!-- ============================================== -->

<script>
const changedFields = {};
$(document).ready(function() {
    $("#language").select2();
    $("#mcustomer_country").select2();
    $("#mcustomer_country_s").select2({
        width: "100%" // Sets the width to 100%
    });
   $("#credit-limit-form").validate({
        rules: {
            new_credit_limit: {required:true},
        },
        messages: {
            new_credit_limit  : "Enter Credit Limit",
        },
        errorElement: "em",
        errorPlacement: function ( error, element ) {             
            error.addClass( "help-block" ); 
            if ( element.prop( "type" ) === "checkbox" ) {
                error.insertAfter( element.parent( "label" ) );
            }else {
                error.insertAfter( element );
            }
        },
        highlight: function ( element, errorClass, validClass ) {
            $( element ).parents( ".col-sm-5" ).addClass( "has-error" ).removeClass( "has-success" );
        },
        unhighlight: function (element, errorClass, validClass) {
            $( element ).parents( ".col-sm-5" ).addClass( "has-success" ).removeClass( "has-error" );
        },
    });

    //erp2024 06-01-2025 for history log     
    document.querySelectorAll('input, textarea, select').forEach((input) => {
        input.addEventListener('change', function () {
            const fieldId = this.id || this.name; // Use `name` for grouped radio buttons
            const originalValue = this.getAttribute('data-original-value');
            var label = $('label[for="' + fieldId + '"]');
            var field_label = label.text();                
            if (!field_label.trim()) {
                field_label = this.getAttribute('title') || 'Unknown Field';
            }

            if (this.type === 'checkbox') {
                // For checkboxes, use the "checked" state
                const newValue = this.checked ? this.value : null;
                const originalChecked = originalValue === this.value;

                if (originalChecked !== this.checked) {
                    changedFields[fieldId] = {
                        oldValue: originalChecked ? this.value : null,
                        newValue: newValue,
                        fieldlabel: field_label
                    }; // Track changes
                } else {
                    delete changedFields[fieldId]; // Remove if no change
                }
            } else if (this.type === 'radio') {
                // For radio buttons, track the selected option
                if (this.checked) {
                    const newValue = this.value;
                    if (originalValue !== newValue) {
                        changedFields[fieldId] = {
                            oldValue: originalValue,
                            newValue: newValue,
                            fieldlabel: field_label
                        };
                    } else {
                        delete changedFields[fieldId]; // Remove if no change
                    }
                }
            } else if (this.type === 'number') {
                // For numeric fields
                const newValue = parseFloat(this.value);
                const originalNumber = parseFloat(originalValue);

                if (!isNaN(originalNumber) && !isNaN(newValue) && originalNumber !== newValue) {
                    changedFields[fieldId] = {
                        oldValue: originalNumber,
                        newValue: newValue,
                        fieldlabel: field_label
                    };
                } else {
                    delete changedFields[fieldId];
                }
            } else if (this.tagName === 'SELECT') {
                // For select fields, use the option's label
                const selectedOption = this.options[this.selectedIndex];
                const newValue = selectedOption ? selectedOption.label : '';
                const originalLabel = this.getAttribute('data-original-value');

                if (originalLabel !== newValue) {
                    changedFields[fieldId] = {
                        oldValue: originalLabel,
                        newValue: newValue,
                        fieldlabel: field_label
                    };
                } else {
                    delete changedFields[fieldId];
                }
            } else {
                // For text and textarea fields
                const newValue = this.value;
                if (originalValue !== newValue) {
                    changedFields[fieldId] = {
                        oldValue: originalValue,
                        newValue: newValue,
                        fieldlabel: field_label
                    };
                } else {
                    delete changedFields[fieldId]; // Remove if no change
                }
            }
        });
    });
    // Function to initialize Select2 and track changes
    function initializeSelect2(selector) {
        // Initialize Select2

        // Attach change event listener to track changes
        $(selector).each(function () {
            const element = $(this);
            const fieldId = element.attr('id');
            const originalValue = element.data('original-value'); // Access `data-original-value`
            var label = $('label[for="' + fieldId + '"]');
            var field_label = label.text();
            element.on('change', function () {
                const newValue = element.val();
                if (originalValue != newValue) {
                    changedFields[fieldId] = {
                        oldValue: originalValue,
                        newValue: newValue,
                        fieldlabel: field_label,
                    }; // Store the original and new value
                } else {
                    delete changedFields[fieldId]; // Remove if no change
                }
            });
        });
    }
    initializeSelect2('.multi-select');
    $(document).on('select2:select select2:unselect', '.select2-hidden-accessible', function (e) {
        const fieldId = this.id || this.name;
        const originalValue = $(this).data('original-label'); // Original value (could be string or array)
        const newValueArray = $(this).val(); // Get the current value(s) as an array
        const label = $('label[for="' + fieldId + '"]');
        let field_label = label.text();
        if (!field_label.trim()) {
            field_label = this.getAttribute('title') || 'Unknown Field';
        }

        if (Array.isArray(newValueArray)) {
            // Handle multiple select: Get the selected option labels
            const newValueLabels = newValueArray.map(function (value) {
                const option = $('option[value="' + value + '"]', e.target);
                return option.length ? option.text() : ''; // Get the label (text) of the selected option
            });

            const newValue = newValueLabels.join(','); // Convert array of labels to string
            const originalLabels = Array.isArray(originalValue) ? originalValue.map(function (value) {
                const option = $('option[value="' + value + '"]', e.target);
                return option.length ? option.text() : '';
            }).join(',') : originalValue;

            if (originalLabels !== newValue) {
                changedFields[fieldId] = {
                    oldValue: originalLabels,
                    newValue: newValue,
                    fieldlabel: field_label,
                };
            } else {
                delete changedFields[fieldId]; // No changes
            }
        } else {
            // Handle single select: Get the selected option label
            const newValue = newValueArray ? $('option[value="' + newValueArray + '"]', e.target).text() : '';
            if (originalValue !== newValue) {
                changedFields[fieldId] = {
                    oldValue: originalValue,
                    newValue: newValue,
                    fieldlabel: field_label,
                };
            } else {
                delete changedFields[fieldId]; // No changes
            }
        }
    });
    // erp2024 newly added 14-06-2024 for detailed history log ends 


});
$('#update-credit-limit').on('click', function(e) {
    e.preventDefault(); // Prevent the default form submission
    // $('#update-credit-limit').prop('disabled',true);
    var customer_id = $('#customer_id').val();
    // Validate the form    
    $("#credit_limit_model").modal('show');
    $("#updated_credit_limit").text("");
    $("#new_credit_limit").val("");
    $("#updated_credit_limit_val").val("");
    
    hasUnsavedChanges = false;
    $.ajax({
        url: baseurl + "customers/current_credit_limits",
        type: 'POST',
        data: {'customer_id': customer_id},
        dataType: 'json',
        success: function(response) {
            data = response.data;
            $("#available_creditlimit_val").val(data.avalable_credit_limit);
            $("#available_creditlimit").text(data.avalable_credit_limit);
            $("#creditlimit_val").val(data.credit_limit);
            $("#creditlimit").text(data.credit_limit);
        },
        error: function(xhr, status, error) {
            Swal.fire('Error', 'An error occurred while generating the material request', 'error');
            console.log(error); // Log any errors
        }
    });
});

$("#new_credit_limit").on('keyup', function() {
    var available_creditlimit_val = parseFloat($("#available_creditlimit_val").val());
    var creditlimit_val = parseFloat($("#creditlimit_val").val());
    var new_credit_limit = parseFloat($(this).val());
    var diffeval = 0;
    var updated_available_creditlimit_val = available_creditlimit_val;
    if (!isNaN(new_credit_limit) && !isNaN(available_creditlimit_val) && !isNaN(creditlimit_val)) {
        if (creditlimit_val > new_credit_limit) {
            diffeval = creditlimit_val - new_credit_limit;
            updated_available_creditlimit_val = available_creditlimit_val - diffeval;
        } else {
            diffeval = new_credit_limit - creditlimit_val;
            updated_available_creditlimit_val = available_creditlimit_val + diffeval;
        }

        $("#updated_credit_limit").text(updated_available_creditlimit_val.toFixed(2));
        $("#updated_credit_limit_val").val(updated_available_creditlimit_val.toFixed(2));
    }
    if(isNaN(new_credit_limit)){
        $("#updated_credit_limit").text("");
        $("#updated_credit_limit_val").val("");
    }
});

$('#update_limit_btn').on('click', function(e) {
    e.preventDefault(); // Prevent the default form submission
    hasUnsavedChanges = false;

    if ($("#credit-limit-form").valid()) {
        var customer_id = parseFloat($("#customer_id").val());
        var available_creditlimit_val = parseFloat($("#available_creditlimit_val").val());
        var creditlimit_val = parseFloat($("#creditlimit_val").val());
        var new_credit_limit = parseFloat($("#new_credit_limit").val());
        var updated_credit_limit_val = parseFloat($("#updated_credit_limit_val").val());
        var changedFields = {
            oldValue: parseFloat($("#creditlimit_val").val()),
            newValue: parseFloat($("#new_credit_limit").val()),
            fieldlabel : "New Credit Limit"
        };
        $.ajax({
            url: baseurl + "customers/update_credit_limits",
            type: 'POST',
            data: {
                'customer_id': customer_id,
                'available_creditlimit_val': available_creditlimit_val,
                'creditlimit_val': creditlimit_val,
                'new_credit_limit': new_credit_limit,
                'updated_credit_limit_val': updated_credit_limit_val,
            },
            dataType: 'json',
            success: function(response) {               
                Swal.fire({
                    title: response.status === 'Success' ? 'Success' : 'Error',
                    html: response.message,
                    icon: response.status === 'Success' ? 'success' : 'error',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Reload the page if OK is clicked
                        location.reload();
                    }
                });
            },
            error: function(xhr, status, error) {
                Swal.fire('Error', 'An error occurred while processing the request', 'error');
                console.log(error); // Log any errors
            }
        });
    
    } else {
        $('#update_limit_btn').prop('disabled', false);
    }
});
    //erp2024 customer edit section 03-06-2024
    $("#cust_data_edit_form").validate($.extend(true, {}, globalValidationOptions,{
        ignore: [], // Important: Do not ignore hidden fields
        rules: {
            name: {required:true},
            phone: {required:true},
            email: {
                required: true,
                email: true
            }
        },
        messages: {
            name  : "Enter Name",
            phone  : "Enter Phone Number",
            email  : "Enter Email",
        }
    }));
    $("#cust_edit_submit").on("click", function (e) {
        e.preventDefault();
        $('#cust_edit_submit').prop('disabled', true);
        if ($("#cust_data_edit_form").valid()) {

            var formData = new FormData($("#cust_data_edit_form")[0]);                      
            formData.append('changedFields', JSON.stringify(changedFields));
            Swal.fire({
               title: "Are you sure?",
               text: "Do you want to update the customer?",
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
                        type: 'POST',
                        url: baseurl + 'Customers/editcustomer',
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: "json",
                        success: function(response) {
                            window.location.href = baseurl + 'customers';
                            // $("#response-alert").removeClass("d-none");
                            // $("#responsemsg").html(response.message);                    

                            // if (response.status === "Success") {
                            //     $("#response-alert").removeClass("alert-danger").addClass("alert-success");
                            // } else {
                            //     $("#response-alert").removeClass("alert-success").addClass("alert-danger");
                            // }
                            // document.getElementById("card-title").scrollIntoView();
                            // $("#card-title").focus();
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                }
                else{
                    $('#cust_edit_submit').prop('disabled', false);
                }
            });


        }
        else {
            $('#cust_edit_submit').prop('disabled', false);
            $('html, body').animate({
                scrollTop: $("#cust_data_edit_form").offset().top
            }, 2000);
            $("#cust_data_edit_form").focus();
        }
    });

</script>