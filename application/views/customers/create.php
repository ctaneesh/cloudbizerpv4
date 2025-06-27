<div class="content-body"> 
        <?php       
            if (($msg = check_permission($permissions)) !== true) {
                echo $msg;
                return;
            }
            $breadcrub_label = $this->lang->line('Add New Customer');
            $btn_label = $this->lang->line('Add Customer');
            $card_with_first = "col-lg-7 col-md-7 col-sm-12 col-xs-12";
            $card_with_second = "col-lg-5 col-md-5 col-sm-12 col-xs-12";
            $mainsection = "col-lg-2 col-md-4 col-sm-12 col-xs-12";
            $inputfile = "col-12";
            if($customerid)
            {
                $breadcrub_label = $customer['company'];
                $btn_label = $this->lang->line('Update Customer');
                $card_with_first = "col-lg-4 col-md-4 col-sm-12 col-xs-12";
                $card_with_second = "col-lg-4 col-md-4 col-sm-12 col-xs-12";
                $mainsection = "col-lg-4 col-md-5 col-sm-12 col-xs-12";
                $inputfile = "col-lg-8 col-md-8 col-sm-12 col-xs-12";
            }
        ?>

    <div class="card">
       
        <div class="card-header border-bottom">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('customers') ?>"><?php echo $this->lang->line('Customers') ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $breadcrub_label; ?></li>
                </ol>
            </nav>
            <h4 class="card-title"><?php echo $breadcrub_label;  ?></h4>
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
                <strong><?php echo $this->lang->line('Please fill out all mandatory fields') ?></strong>
            </div>
            
            <form method="post" id="cust_data_form" class="form-horizontal" enctype="multipart/form-data">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body1">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link breaklink active show" id="base-tab1" data-toggle="tab"
                                       aria-controls="tab1" href="#tab1" role="tab"
                                       aria-selected="true"><?php echo $this->lang->line('Address') ?></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link breaklink" id="base-tab2" data-toggle="tab" aria-controls="tab2"
                                       href="#tab2" role="tab"
                                       aria-selected="false"><?php echo $this->lang->line('Shipping/Billing Address') ?></a>
                                </li>
                                  <li class="nav-item d-none">
                                    <a class="nav-link breaklink" id="base-tab3" data-toggle="tab" aria-controls="tab3"
                                       href="#tab4" role="tab"
                                       aria-selected="false"><?php echo $this->lang->line('CustomFields') ?></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link breaklink" id="base-tab3" data-toggle="tab" aria-controls="tab3"
                                       href="#tab3" role="tab"
                                       aria-selected="false"><?php echo $this->lang->line('Customer Details'); ?></a>
                                </li>

                            </ul>
                            <div class="tab-content px-1 pt-1">

                                <div class="tab-pane active show" id="tab1" role="tabpanel" aria-labelledby="base-tab1">
								<div class="row g-2 mt-1">
									<div class="col-12">
										<h5 class="popup-title"><?php echo $this->lang->line('Company Address') ?></h5>
										<hr>
									</div>

									<input type="hidden" id="customerid" name="customerid" value="<?=$customerid?>">

									<div class="col-lg-2 col-md-4 col-sm-12">
										<label class="col-form-label" for="companyname"><?php echo $this->lang->line('Company') ?><span class="compulsoryfld">*</span></label>
										<input type="text" placeholder="Company" class="form-control margin-bottom b_input" name="company" id="companyname" value="<?=$customer['company']?>" data-original-value="<?php echo $customer['company'] ?>">
									</div>

									<div class="col-lg-2 col-md-4 col-sm-12">
										<label class="col-form-label" for="mcustomer_name"><?php echo $this->lang->line('Name') ?><span class="compulsoryfld">*</span></label>
										<input type="text" placeholder="Name" class="form-control margin-bottom b_input required" name="name" id="mcustomer_name" required value="<?=$customer['name']?>" data-original-value="<?php echo $customer['name'] ?>">
									</div>

									<div class="col-lg-4 col-md-4 col-sm-12">
										<label class="col-form-label" for="mcustomer_address1"><?php echo $this->lang->line('Address') ?></label>
										<textarea placeholder="Address" class="form-textarea margin-bottom b_input" name="address" id="mcustomer_address1" data-original-value="<?php echo $customer['address'] ?>"><?=$customer['address']?></textarea>
									</div>

									<div class="col-lg-2 col-md-4 col-sm-12">
										<label class="col-form-label" for="mcustomer_city"><?php echo $this->lang->line('City') ?></label>
										<input type="text" placeholder="City" class="form-control margin-bottom b_input" name="city" id="mcustomer_city" value="<?=$customer['city']?>" data-original-value="<?php echo $customer['city'] ?>">
									</div>

									<div class="col-lg-2 col-md-4 col-sm-12">
										<label class="col-form-label" for="region"><?php echo $this->lang->line('Region') ?></label>
										<input type="text" placeholder="Region" class="form-control margin-bottom b_input" name="region" id="region" value="<?=$customer['region']?>" data-original-value="<?php echo $customer['region'] ?>">
									</div>

									<div class="col-lg-2 col-md-4 col-sm-12">
										<label class="col-form-label" for="postbox"><?php echo $this->lang->line('PostBox') ?></label>
										<input type="text" placeholder="PostBox" class="form-control margin-bottom b_input" name="postbox" id="postbox" value="<?=$customer['postbox']?>" data-original-value="<?php echo $customer['postbox'] ?>">
									</div>

									<div class="col-lg-2 col-md-4 col-sm-12">
										<label class="col-form-label" for="mcustomer_phone"><?php echo $this->lang->line('Phone') ?><span class="compulsoryfld">*</span></label>
										<input type="text" placeholder="Phone" class="form-control margin-bottom required b_input" name="phone" id="mcustomer_phone" required value="<?=$customer['phone']?>" data-original-value="<?php echo $customer['phone'] ?>">
									</div>

									<div class="col-lg-2 col-md-4 col-sm-12">
										<label class="col-form-label" for="mcustomer_email">Email<span class="compulsoryfld">*</span></label>
										<input type="email" placeholder="Email" class="form-control margin-bottom required b_input" name="email" id="mcustomer_email" required value="<?=$customer['email']?>" data-original-value="<?php echo $customer['email'] ?>">
									</div>

									<div class="col-lg-2 col-md-4 col-sm-12">
										<label class="col-form-label" for="mcustomer_country"><?php echo $this->lang->line('Country') ?><span class="compulsoryfld">*</span></label>
										<select name="country" id="mcustomer_country" class="form-control margin-bottom" required data-original-value="<?php echo $customer['country'] ?>">
											<option value=''>Select Country</option>
											<?php
												foreach ($countries as $row) {
													$cid = $row['id'];
													$title = $row['name'];
													$code = $row['code'];
													$sel = ($customer['country'] && $customer['country'] == $cid) ? "selected" : "";
													echo "<option value='$cid' $sel>$title($code)</option>";
												}
											?>
										</select>
									</div>
								</div>
							</div>


                                <div class="tab-pane" id="tab2" role="tabpanel" aria-labelledby="base-tab2">
                                      
                                    <div class="form-group">
                                      
                                        <div class="input-group mt-1">
                                            <div class="custom-control custom-checkbox">   
                                                <input type="checkbox" class="custom-control-input" name="customer1" id="copy_address">   
                                                <label class="custom-control-label"  for="copy_address"><?php echo $this->lang->line('Same As Company') ?></label>
                                            </div>

                                        </div>
                                        <div class="col-12 mt-1 row">
                                            <h5 class="popup-title"><?php echo $this->lang->line('Billling Address') ?></h5>
                                           
                                        </div>
                                        <hr>
                                        <div class="row g-2">
                                        <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                            <label class="col-form-label" for="billing_name"><?php echo $this->lang->line('Name') ?></label>
                                            <input type="text" placeholder="Name" class="form-control margin-bottom b_input" name="billing_name" id="billing_name" value="<?=$customer['billing_name']?>" data-original-value="<?php echo $customer['billing_name'] ?>">
                                        </div>
                                        <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                            <label class="col-form-label" for="billing_contact_person"><?php echo $this->lang->line('Contact Person') ?></label>
                                            <input type="text" placeholder="Name" class="form-control margin-bottom b_input" name="billing_contact_person" id="billing_contact_person"  value="<?=$customer['billing_contact_person']?>" data-original-value="<?php echo $customer['billing_contact_person'] ?>">
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                            <label class="col-form-label" for="billing_address_1"><?php echo $this->lang->line('Billing Address 1') ?></label>
                                            <textarea placeholder="Address" class="form-textarea margin-bottom b_input" name="billing_address_1"  id="billing_address_1" data-original-value="<?php echo $customer['billing_address_1'] ?>"><?=$customer['billing_address_1']?></textarea>
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                            <label class="col-form-label" for="billing_city"><?php echo $this->lang->line('Billing Address 2') ?></label>
                                            <textarea placeholder="Address" class="form-textarea margin-bottom b_input" name="billing_address_2"  id="billing_address_2" data-original-value="<?php echo $customer['billing_address_2'] ?>"><?=$customer['billing_address_2']?></textarea>
                                        </div>
                                        <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                                            <label class="col-form-label" for="billing_city"><?php echo $this->lang->line('City') ?></label>
                                            <input type="text" placeholder="city" class="form-control margin-bottom b_input" name="billing_city" id="billing_city" value="<?=$customer['billing_city']?>" data-original-value="<?php echo $customer['billing_city'] ?>">
                                        </div>

                                        <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                                            <label class="col-form-label" for="billing_region"><?php echo $this->lang->line('Region') ?></label>
                                            <input type="text" placeholder="Region" class="form-control margin-bottom b_input" name="billing_region" id="billing_region" value="<?=$customer['billing_region']?>" data-original-value="<?php echo $customer['billing_region'] ?>">
                                        </div>

                                        <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                                            <label class="col-form-label" for="billing_postal_code"><?php echo $this->lang->line('PostBox') ?></label>
                                            <input type="text" placeholder="Postal Code" class="form-control margin-bottom b_input" name="billing_postal_code" id="billing_postal_code" value="<?=$customer['billing_postal_code']?>"  data-original-value="<?php echo $customer['billing_postal_code'] ?>">
                                        </div>

                                        <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                                            <label class="col-form-label" for="billing_country"><?php echo $this->lang->line('Country') ?></label>
                                            <select name="billing_country" id="billing_country" class="form-control margin-bottom"  data-original-value="<?php echo $customer['billing_country'] ?>">
                                                <?php
                                                    echo "<option value=''>Select Country</option>";
                                                    foreach ($countries as $row) {
                                                        $cid = $row['id'];
                                                        $title = $row['name'];
                                                        $code = $row['code'];
                                                        $bsel= ($customer['billing_country'] && $customer['billing_country']==$cid) ? "selected" : "";
                                                        echo "<option value='$cid' $bsel>$title($code)</option>";
                                                    }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                                            <label class="col-form-label" for="billing_phone"><?php echo $this->lang->line('Phone') ?></label>
                                            <input type="text" placeholder="phone" class="form-control margin-bottom b_input" name="billing_phone" id="billing_phone"  value="<?=$customer['billing_phone']?>"  data-original-value="<?php echo $customer['billing_phone'] ?>">
                                        </div>

                                        <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                                            <label class="col-form-label" for="billing_email">Email</label>
                                            <input type="email" placeholder="email" class="form-control margin-bottom b_input" name="billing_email" id="billing_email"  value="<?=$customer['billing_email']?>"  data-original-value="<?php echo $customer['billing_email'] ?>">
                                        </div>
                                    </div>
                                        <hr>

                                    <div class="input-group mt-1">
                                         <div class="col-12 mt-1 row">
                                            <h5 class="popup-title"><?php echo $this->lang->line('Shipping Address') ?></h5>
                                           
                                        </div>
                                            <div class="custom-control custom-checkbox">   
                                                <input type="checkbox" class="custom-control-input" name="customer1"  id="copy_billing_address">   <label class="custom-control-label"  for="copy_billing_address"><?php echo $this->lang->line('Same As Billing') ?></label>
                                            </div>

                                        </div>

                                        <div class="alert alert-info">
                                            <?php echo $this->lang->line("leave Shipping Address") ?>
                                        </div>
                                        <i>*<?php echo $this->lang->line("Shipping Charge is not Refundable") ?></i>
                                        
                                        <hr>
                                    </div>
                                    <div class="row g-2">
                                        <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                            <label class="col-form-label" for="shipping_name"><?php echo $this->lang->line('Name') ?></label>
                                            <input type="text" placeholder="Name" class="form-control margin-bottom b_input" name="shipping_name" id="shipping_name" value="<?=$customer['shipping_name']?>"  data-original-value="<?php echo $customer['shipping_name'] ?>">
                                        </div>
                                        <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                            <label class="col-form-label" for="shipping_contact_person"><?php echo $this->lang->line('Contact Person') ?></label>
                                            <input type="text" placeholder="Name" class="form-control margin-bottom b_input" name="shipping_contact_person" id="shipping_contact_person" value="<?=$customer['shipping_contact_person']?>" data-original-value="<?php echo $customer['shipping_contact_person'] ?>">
                                        </div>
                                        <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                            <label class="col-form-label" for="address"><?php echo $this->lang->line('Shipping Address 1') ?></label>
                                            <textarea placeholder="Address" class="form-textarea margin-bottom b_input" name="shipping_address_1"  id="shipping_address_1" data-original-value="<?php echo $customer['shipping_address_1'] ?>"><?=$customer['shipping_address_1']?></textarea>
                                        </div>
                                        <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                            <label class="col-form-label" for="address"><?php echo $this->lang->line('Shipping Address 2') ?></label>
                                            <textarea placeholder="Address" class="form-textarea margin-bottom b_input" name="shipping_address_2"  id="shipping_address_2" data-original-value="<?php echo $customer['shipping_address_2'] ?>"><?=$customer['shipping_address_2']?></textarea>
                                        </div>
                                        <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                                            <label class="col-form-label" for="shipping_city"><?php echo $this->lang->line('City') ?></label>
                                            <input type="text" placeholder="city" class="form-control margin-bottom b_input" name="shipping_city" id="shipping_city" value="<?=$customer['shipping_city']?>" data-original-value="<?php echo $customer['shipping_city'] ?>">
                                        </div>

                                        <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                                            <label class="col-form-label" for="shipping_region"><?php echo $this->lang->line('Region') ?></label>
                                            <input type="text" placeholder="Region" class="form-control margin-bottom b_input" name="shipping_region" id="shipping_region" value="<?=$customer['shipping_region']?>" data-original-value="<?php echo $customer['shipping_region'] ?>">
                                        </div>

                                        <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                                            <label class="col-form-label" for="postbox"><?php echo $this->lang->line('PostBox') ?></label>
                                            <input type="text" placeholder="PostBox" class="form-control margin-bottom b_input" name="shipping_postal_code" id="shipping_postal_code" value="<?=$customer['shipping_postal_code']?>" data-original-value="<?php echo $customer['shipping_postal_code'] ?>">
                                        </div>

                                        
                                        <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                                            <label class="col-form-label" for="shipping_country"><?php echo $this->lang->line('Country') ?></label>
                                            <!-- <input type="text" placeholder="Country" class="form-control margin-bottom b_input" name="shipping_country" id="mcustomer_country_s"> -->
                                            <select name="shipping_country" id="shipping_country" class="form-control margin-bottom" data-original-value="<?php echo $customer['shipping_country'] ?>">
                                                <?php
                                                    echo "<option value=''>Select Country</option>";
                                                    foreach ($countries as $row) {
                                                        $cid = $row['id'];
                                                        $title = $row['name'];
                                                        $code = $row['code'];
                                                        $ssel= ($customer['shipping_country'] && $customer['shipping_country']==$cid) ? "selected" : "";
                                                        echo "<option value='$cid' $ssel>$title($code)</option>";
                                                    }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                                            <label class="col-form-label" for="shipping_phone"><?php echo $this->lang->line('Phone') ?></label>
                                            <input type="text" placeholder="phone" class="form-control margin-bottom b_input" name="shipping_phone" id="shipping_phone" value="<?=$customer['shipping_phone']?>" data-original-value="<?php echo $customer['shipping_phone'] ?>">
                                        </div>

                                        <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                                            <label class="col-form-label" for="shipping_email">Email</label>
                                            <input type="email" placeholder="email" class="form-control margin-bottom b_input" name="shipping_email" id="shipping_email" value="<?=$customer['shipping_email']?>" data-original-value="<?php echo $customer['shipping_email'] ?>">
                                        </div>
                                        
                                    </div>


                                </div>
                                
                                <div class="tab-pane" id="tab3" role="tabpanel" aria-labelledby="base-tab3">
                                    <!-- erp2024 newly added 01-06-2024 -->
                                    <div class="row g-2">
                                        <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                                                <label class="col-form-label" for="Registration Number"><?php echo $this->lang->line('Registration Number') ?> </label>
                                                <input type="text" placeholder="Registration Number" class="form-control margin-bottom b_input" name="registration_number" id="registration_number" value="<?=$customer['registration_number']?>" data-original-value="<?php echo $customer['registration_number'] ?>">
                                        </div>
                                        
                                        <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                                            <label class="col-form-label" for="Expiry Date"><?php echo $this->lang->line('Expiry Date') ?> </label>
                                            <input type="date" placeholder="Expiry Date" class="form-control margin-bottom b_input" name="expiry_date" id="expiry_date" value="<?=$customer['expiry_date']?>" data-original-value="<?php echo $customer['expiry_date'] ?>">
                                        </div>
                                  
                                        <div class="col-lg-4 col-md-5 col-sm-12">
                                            <label for="computer_card_number" class="col-form-label">
                                                <?php echo $this->lang->line('Computer Card'); ?>
                                            </label>
                                            <div class="row g-2">
                                                <div class="<?=$card_with_first?>">
                                                    <input type="text" placeholder="Computer Card Number" class="form-control b_input" name="computer_card_number" id="computer_card_number" value="<?=$customer['computer_card_number']?>" data-original-value="<?php echo $customer['computer_card_number'] ?>">
                                                </div>
                                                <div class="<?=$card_with_second?> mt-responsive">
                                                    <input type="file" class="form-control b_input" name="computer_card_image" id="computer_card_image" data-original-value="<?php echo $customer['computer_card_image'] ?>">
                                                </div>
                                                <?php if($customer['computer_card_image']){ ?>
                                                <div class="<?=$card_with_first?> mt-responsive">
                                                    <input type="hidden" name="computer_card_image_text" value="<?=$customer['computer_card_image']?>">
                                                    <img src="<?php echo base_url().'userfiles/customers/'.$customer['computer_card_image']; ?>" style="width:100px; height:50px;">
                                                </div>
                                                <?php } ?>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 col-md-5 col-sm-12">
                                            <label for="computer_card_number" class="col-form-label">
                                                <?php echo $this->lang->line('Sponser ID'); ?>
                                            </label>
                                            <div class="row g-2">
                                                <div class="<?=$card_with_first?>">
                                                    <input type="text" placeholder="Sponser ID" class="form-control b_input" name="sponser_id" id="sponser_id" value="<?=$customer['sponser_id']?>" data-original-value="<?php echo $customer['sponser_id'] ?>">
                                                </div>
                                                <div class="<?=$card_with_second?> mt-responsive">
                                                   <input type="file" class="form-control b_input" name="sponser_image" id="sponser_image" data-original-value="<?php echo $customer['sponser_image'] ?>">
                                                </div>
                                                <?php if($customer['sponser_image']){ ?>
                                                <div class="<?=$card_with_first?> mt-responsive">
                                                    <input type="hidden" name="sponser_image_text" value="<?=$customer['sponser_image']?>">
                                                   <img src="<?php echo base_url().'userfiles/customers/'.$customer['sponser_image']; ?>" style="width:100px; height:50px;" >
                                                </div>
                                                <?php } ?>
                                            </div>
                                        </div>


                                        <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                                            <label class="col-form-label" for="Credit Limit"><?php echo $this->lang->line('Credit Limit') ?><span class="compulsoryfld">*</span> </label>
                                            <input type="number" placeholder="Credit Limit" class="form-control margin-bottom b_input" name="credit_limit" id="credit_limit" required value="<?=$customer['credit_limit']?>" data-original-value="<?php echo $customer['credit_limit'] ?>">
                                        </div>                                       

                                        <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                                            <label class="col-form-label" for="Credit Period"><?php echo $this->lang->line('Credit Period') ?><span class="compulsoryfld">*</span> </label>
                                            <input type="number" placeholder="No. of days" class="form-control margin-bottom b_input" name="credit_period" id="credit_period" required value="<?=$customer['credit_period']?>" data-original-value="<?php echo $customer['credit_period'] ?>">
                                        </div>


                                        <!-- erp2024 newly added 01-06-2024 -->

                                        <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                                            <label class="col-form-label" for="Discount"><?php echo $this->lang->line('Discount') ?> </label>
                                            <input type="text" placeholder="Custom Discount" class="form-control margin-bottom b_input" name="discount" value="<?=$customer['discount']?>"  data-original-value="<?php echo $customer['discount'] ?>">
                                        </div>
                                        <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                                            <label class="col-form-label" for="tax_id"><?php echo $this->lang->line('TAX') ?> ID</label>
                                            <input type="text" placeholder="TAX ID" class="form-control margin-bottom b_input" name="tax_id" value="<?=$customer['tax_id']?>" data-original-value="<?php echo $customer['tax_id'] ?>">
                                        </div>
                                        <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                                            <label class="col-form-label" for="document_id"><?php echo $this->lang->line('Document') ?> ID</label>
                                            <input type="text" placeholder="Document ID" class="form-control margin-bottom b_input" name="document_id" value="<?=$customer['document_id']?>" data-original-value="<?php echo $customer['document_id'] ?>">
                                        </div>

                                        <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12 d-none">
                                            <label class="col-form-label" for="c_field"><?php echo $this->lang->line('Extra') ?> </label>
                                            <input type="text" placeholder="Custom Field" class="form-control margin-bottom b_input" name="c_field" >
                                        </div>
                                        <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                                            <label class="col-form-label" for="customergroup"><?php echo $this->lang->line('Customer group') ?></label>
                                            <select name="customer_group_id" class="form-control b_input" data-original-value="<?php echo $customer['customer_group_id'] ?>">   
                                                <?php   foreach ($customergrouplist as $row) 
                                                {       
                                                    $cid = $row['id'];       
                                                    $title = $row['title'];       
                                                    $gsel= ($customer['customer_group_id'] && $customer['customer_group_id']==$cid) ? "selected" : "";
                                                    echo "<option value='$cid' $gsel>$title</option>";   
                                                }   ?>
                                            </select>
                                        </div>
                                        <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                                            <label class="col-form-label" for="language">Language</label>
                                            <select name="language" id="language" class="form-control b_input" style="width:100%" data-original-value="<?php echo $customer['customer_group_id'] ?>">   <?php   echo $langs;   ?></select>
                                        </div>

                                        <?php 
                                        if(empty($customerid))
                                        { ?>
                                        <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                                            <label class="col-form-label" for="currency"><?php echo $this->lang->line('customer_login') ?></label>
                                            <select name="c_login" class="form-control b_input">   <option value="1"><?php echo $this->lang->line('Yes') ?></option>   <option value="0"><?php echo $this->lang->line('No') ?></option>
                                            </select>
                                        </div>
                                        <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                                            <label class="col-form-label" for="password_c"><?php echo $this->lang->line('New Password') ?></label>
                                            <input type="text" placeholder="Leave blank for auto generation"  class="form-control margin-bottom b_input" name="password_c" id="password_c">
                                        </div>
                                        <?php } ?>
                                        <!-- erp2024 new field 03-06-2024 -->                                         
                                         <div class="<?=$mainsection?>">
                                            <label class="col-form-label" for="currency"><?php echo $this->lang->line('Profile Picture') ?></label>
                                            <div class="row g-2">
                                                <div class="<?=$inputfile?>">                                                   
                                                     <input type="file" placeholder="Profile Picture" class="form-control margin-bottom b_input" name="picture" id="picture" data-original-value="<?php echo $customer['picture'] ?>">
                                                </div>
                                                
                                                <?php if($customer['picture']){ ?>
                                                <div class="<?=$card_with_first?> mt-responsive">
                                                    <input type="hidden" name="picture_text" value="<?=$customer['picture']?>">
                                                   <img src="<?php echo base_url().'userfiles/customers/'.$customer['picture']; ?>" style="width:100px; height:50px;" >
                                                </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <!-- <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                                            <label class="col-form-label" for="currency"><?php echo $this->lang->line('Profile Picture') ?></label>
                                            <input type="file" placeholder="Profile Picture" class="form-control margin-bottom b_input" name="picture" id="picture">
                                        </div> -->

                                        <!-- erp2024 newly added sales man -->
                                        <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                                            <label class="col-form-label" for="currency"><?php echo $this->lang->line('Sales Man') ?></label>
                                            <select name="salesman_id" id="salesman_id" class="form-control form-select"  data-original-value="<?php echo $customer['salesman_id'] ?>">
                                                <option value="">Select Salesman</option>
                                                <?php
                                                    if(!empty($salesmanlist))
                                                    {
                                                        foreach ($salesmanlist as $key => $value) {
                                                            $gsel1= ($customer['salesman_id'] && $customer['salesman_id']==$value['id']) ? "selected" : "";
                                                            echo "<option value='".$value['id']."' $gsel1>".ucwords($value['name'])."</option>";
                                                        }
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                                            <label class="col-form-label" for="Status"><?php echo $this->lang->line('Status') ?></label>
                                            <select name="status" id="status" class="form-control form-select" data-original-value="<?php echo $customer['status'] ?>">
                                                <option value="Enable" <?php if($customer['status']=="Enable") { echo "selected"; } ?>>Enable</option>
                                                <option value="Disable" <?php if($customer['status']=="Disable") { echo "selected"; } ?>>Disable</option>
                                            </select>
                                        </div>
                                        <!-- erp2024 newly added sales man -->
                                    </div>
                                    <br><h5><b>Contact Details</b></h5><hr>
                                    <div class="form-group row g-2">
                                        <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">                                    
                                            <label class="col-form-label" for="Contact Person"><?php echo $this->lang->line('Contact Person') ?> </label>
                                            <input type="text" placeholder="Contact Person's Name" class="form-control margin-bottom b_input" name="contact_person" id="contact_person" value="<?=$customer['contact_person']?>" data-original-value="<?php echo $customer['contact_person'] ?>">
                                        </div>
                                        <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                                            <label class="col-form-label" for="contact_designation"><?php echo $this->lang->line('Designation') ?> </label>
                                            <input type="text" placeholder="Designation" class="form-control margin-bottom b_input" name="contact_designation" id="contact_designation" value="<?php echo $customer['contact_designation'] ?>" data-original-value="<?php echo $customer['contact_designation'] ?>">
                                        </div>  
                                        <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                                            <label class="col-form-label" for="Land Line"><?php echo $this->lang->line('Land Line') ?> </label>
                                            <input type="text" placeholder="Land Line" class="form-control margin-bottom b_input" name="land_line" id="land_line" value="<?=$customer['land_line']?>" data-original-value="<?php echo $customer['land_line'] ?>">
                                        </div>
                                        <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                                            <label class="col-form-label" for="Contact Phone1"><?php echo $this->lang->line('Contact Phone1') ?> </label>
                                            <input type="text" placeholder="Contact Phone1" class="form-control margin-bottom b_input" name="contact_phone1" id="contact_phone1" value="<?=$customer['contact_phone1']?>" data-original-value="<?php echo $customer['contact_phone1'] ?>">
                                        </div>
                                        <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                                            <label class="col-form-label" for="Contact Phone2"><?php echo $this->lang->line('Contact Phone2') ?> </label>
                                            <input type="text" placeholder="Contact Phone2" class="form-control margin-bottom b_input" name="contact_phone2" id="contact_phone2" value="<?=$customer['contact_phone2']?>" data-original-value="<?php echo $customer['contact_phone2'] ?>">
                                        </div>                    
                                        <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">             
                                            <label class="col-form-label" for="Contact Email1"><?php echo $this->lang->line('Contact Email1') ?> </label>
                                            <input type="email" placeholder="Contact Email1" class="form-control margin-bottom b_input" name="contact_email1" id="contact_email1" value="<?=$customer['contact_email1']?>"  data-original-value="<?php echo $customer['contact_email1'] ?>">
                                        </div>
                                        <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                                            <label class="col-form-label" for="Contact Email2"><?php echo $this->lang->line('Contact Email2') ?> </label>
                                            <input type="email" placeholder="Contact Email2" class="form-control margin-bottom b_input" name="contact_email2" id="contact_email2" value="<?=$customer['contact_email2']?>" data-original-value="<?php echo $customer['contact_email2'] ?>">
                                        </div>
                                    </div> 

                                    <!-- erp2024 new field 03-06-2024 -->
                                </div>

                                <div class="tab-pane show" id="tab4" role="tabpanel" aria-labelledby="base-tab4">

                                <div class="form-group row">
                                 <?php                                 
                                    foreach ($custom_fields as $row) {
                                        if ($row['f_type'] == 'text') { ?>
                                            <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                                                <label class="col-form-label" for="document_id"><?= $row['name'] ?></label>
                                                <input type="text" placeholder="<?= $row['placeholder'] ?>" class="form-control margin-bottom b_input <?= $row['other'] ?>"  name="custom[<?= $row['id'] ?>]">   
                                           </div>                                            
                                        <?php }
                                    }
                                    ?>
                                    </div>

                                </div>
                                <hr>
                                <div id="mybutton" class="submit-section text-end">
                                    <input type="submit" id="cust_add_submit" class="btn btn-crud btn-lg btn-primary margin-bottom float-xs-end mr-2" value="<?php echo $btn_label; ?>"  data-loading-text="Adding...">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- erp2024 remove action url -->
                <!-- <input type="hidden" value="customers/addcustomer" id="action-url"> -->
            </form>
        </div>
    </div>
</div>

<script>
const changedFields = {};
$("#language").select2();
$("#mcustomer_country").select2();
$("#billing_country").select2({
    width: "100%" // Sets the width to 100%
});
$("#shipping_country").select2({
    width: "100%" // Sets the width to 100%
});
    //erp2024 customer add section 03-06-2024
  $("#cust_data_form").validate($.extend(true, {}, globalValidationOptions, {
    ignore: [],
    rules: {
        company: { required: true },
        name: { required: true },
        phone: { required: true,phoneRegex :true },
        country: { required: true },
        credit_limit: { required: true },
        credit_period: { required: true },
        email: {
            required: true,
            email: true
        },
        shipping_phone:{ phoneRegex :true },
        contact_phone1:{ phoneRegex :true },
        contact_phone2:{ phoneRegex :true },
    },
    messages: {
        name: "Enter Company Name",
        name: "Enter Name",
        phone: "Enter Valid Phone Number",
        shipping_phone: "Enter Valid Phone Number",
        contact_phone1: "Enter Valid Phone Number",
        contact_phone2: "Enter Valid Phone Number",
        country: "Select Country",
        email: "Enter Email",
        credit_limit: "Enter Credit Limit",
        credit_period: "Enter Credit Period in days",
    }
}));


    $("#cust_add_submit").on("click", function (e) {
        e.preventDefault();
        $('#cust_add_submit').prop('disabled', true);
        if ($("#cust_data_form").valid()) {

            var formData = new FormData($("#cust_data_form")[0]);                   
            formData.append('changedFields', JSON.stringify(changedFields));
            var textdata = ($("#customerid").val()) ? "Do you want to update the customer?" : "Do you want to create the customer?" ;
            Swal.fire({
               title: "Are you sure?",
               text: textdata,
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
                        url: baseurl + 'Customers/addcustomer',
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: "json",
                        success: function(response) {
                            window.location.href = baseurl + 'customers';
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
               }
               else{
                    $('#cust_add_submit').prop('disabled', false);
               }
            });
        }
        else {
            $('#cust_add_submit').prop('disabled', false);
            $('html, body').animate({
                scrollTop: $("#cust_data_form").offset().top
            }, 2000);
            $("#cust_data_form").focus();            
            $('.alert-dismissible').removeClass('d-none');
        }
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
</script>
