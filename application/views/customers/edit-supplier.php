<div class="content-body">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title" id="card-title"><?php echo $this->lang->line('Edit Customer Details') ?></h5>

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
                        <input type="hidden" name="id" value="<?php echo $customer['id'] ?>">


                        <div class="form-group row">

                            <label class="col-sm-2 col-form-label"
                                   for="product_name"><?php echo $this->lang->line('Name') ?></label>

                            <div class="col-sm-10">
                                <input type="text" placeholder="Name"
                                       class="form-control margin-bottom required" name="name"
                                       value="<?php echo $customer['name'] ?>" id="mcustomer_name">
                            </div>
                        </div>
                        <div class="form-group row">

                            <label class="col-sm-2 col-form-label"
                                   for="product_name"><?php echo $this->lang->line('Company') ?></label>

                            <div class="col-sm-10">
                                <input type="text" placeholder="Company"
                                       class="form-control margin-bottom" name="company"
                                       value="<?php echo $customer['company'] ?>">
                            </div>
                        </div>

                        <div class="form-group row">

                            <label class="col-sm-2 col-form-label"
                                   for="phone"><?php echo $this->lang->line('Phone') ?></label>

                            <div class="col-sm-10">
                                <input type="text" placeholder="phone"
                                       class="form-control margin-bottom  required" name="phone"
                                       value="<?php echo $customer['phone'] ?>" id="mcustomer_phone">
                            </div>
                        </div>
                        <div class="form-group row">

                            <label class="col-sm-2 col-form-label" for="email">Email</label>

                            <div class="col-sm-10">
                                <input type="text" placeholder="email"
                                       class="form-control margin-bottom required" name="email"
                                       value="<?php echo $customer['email'] ?>" id="mcustomer_email">
                            </div>
                        </div>
                        <div class="form-group row">

                            <label class="col-sm-2 col-form-label"
                                   for="product_name"><?php echo $this->lang->line('Address') ?></label>

                            <div class="col-sm-10">
                                <input type="text" placeholder="address"
                                       class="form-control margin-bottom" name="address"
                                       value="<?php echo $customer['address'] ?>" id="mcustomer_address1">
                            </div>
                        </div>
                        <div class="form-group row">

                            <label class="col-sm-2 col-form-label"
                                   for="city"><?php echo $this->lang->line('City') ?></label>

                            <div class="col-sm-10">
                                <input type="text" placeholder="city"
                                       class="form-control margin-bottom" name="city"
                                       value="<?php echo $customer['city'] ?>" id="mcustomer_city">
                            </div>
                        </div>
                        <div class="form-group row">

                            <label class="col-sm-2 col-form-label"
                                   for="region"><?php echo $this->lang->line('Region') ?></label>

                            <div class="col-sm-10">
                                <input type="text" placeholder="region"
                                       class="form-control margin-bottom" name="region"
                                       value="<?php echo $customer['region'] ?>" id="region">
                            </div>
                        </div>
                        <div class="form-group row">

                            <label class="col-sm-2 col-form-label"
                                   for="country"><?php echo $this->lang->line('Country') ?></label>

                            <div class="col-sm-10">
                                <input type="text" placeholder="Country"
                                       class="form-control margin-bottom" name="country"
                                       value="<?php echo $customer['country'] ?>" id="mcustomer_country">
                            </div>
                        </div>
                        <div class="form-group row">

                            <label class="col-sm-2 col-form-label"
                                   for="postbox"><?php echo $this->lang->line('PostBox') ?></label>

                            <div class="col-sm-10">
                                <input type="text" placeholder="region"
                                       class="form-control margin-bottom" name="postbox"
                                       value="<?php echo $customer['postbox'] ?>" id="postbox">
                            </div>
                        </div>
                    
                    </div>
                    <div class="col-md-6">
                        <h5><?php echo $this->lang->line('Shipping Address') ?></h5>
                        <div class="form-group row">

                            <div class="input-group mt-1">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" name="customer1"
                                           id="copy_address">
                                    <label class="custom-control-label"
                                           for="copy_address"><?php echo $this->lang->line('Same As Billing') ?></label>
                                </div>

                            </div>

                            <div class="col-sm-10">
                                <?php echo $this->lang->line("leave Shipping Address") ?>
                            </div>
                        </div>

                        <div class="form-group row">

                            <label class="col-sm-2 col-form-label"
                                   for="product_name"><?php echo $this->lang->line('Name') ?></label>

                            <div class="col-sm-10">
                                <input type="text" placeholder="Name"
                                       class="form-control margin-bottom" name="shipping_name"
                                       value="<?php echo $customer['shipping_name'] ?>" id="mcustomer_name_s">
                            </div>
                        </div>


                        <div class="form-group row">

                            <label class="col-sm-2 col-form-label"
                                   for="phone"><?php echo $this->lang->line('Phone') ?></label>

                            <div class="col-sm-10">
                                <input type="text" placeholder="phone"
                                       class="form-control margin-bottom" name="shipping_phone"
                                       value="<?php echo $customer['shipping_phone'] ?>" id="mcustomer_phone_s">
                            </div>
                        </div>
                        <div class="form-group row">

                            <label class="col-sm-2 col-form-label" for="email">Email</label>

                            <div class="col-sm-10">
                                <input type="text" placeholder="email"
                                       class="form-control margin-bottom" name="shipping_email"
                                       value="<?php echo $customer['shipping_email'] ?>" id="mcustomer_email_s">
                            </div>
                        </div>
                        <div class="form-group row">

                            <label class="col-sm-2 col-form-label"
                                   for="product_name"><?php echo $this->lang->line('Address') ?></label>

                            <div class="col-sm-10">
                                <input type="text" placeholder="address"
                                       class="form-control margin-bottom" name="shipping_address_1"
                                       value="<?php echo $customer['shipping_address_1'] ?>" id="mcustomer_address1_s">
                            </div>
                        </div>
                        <div class="form-group row">

                            <label class="col-sm-2 col-form-label"
                                   for="city"><?php echo $this->lang->line('City') ?></label>

                            <div class="col-sm-10">
                                <input type="text" placeholder="city"
                                       class="form-control margin-bottom" name="shipping_city"
                                       value="<?php echo $customer['shipping_city'] ?>" id="mcustomer_city_s">
                            </div>
                        </div>
                        <div class="form-group row">

                            <label class="col-sm-2 col-form-label"
                                   for="region"><?php echo $this->lang->line('Region') ?></label>

                            <div class="col-sm-10">
                                <input type="text" placeholder="region"
                                       class="form-control margin-bottom" name="shipping_region"
                                       value="<?php echo $customer['shipping_region'] ?>" id="shipping_region">
                            </div>
                        </div>
                        <div class="form-group row">

                            <label class="col-sm-2 col-form-label"
                                   for="country"><?php echo $this->lang->line('Country') ?></label>

                            <div class="col-sm-10">
                                <input type="text" placeholder="Country"
                                       class="form-control margin-bottom" name="shipping_country"
                                       value="<?php echo $customer['shipping_country'] ?>" id="mcustomer_country_s">
                            </div>
                        </div>
                        <div class="form-group row">

                            <label class="col-sm-2 col-form-label"
                                   for="postbox"><?php echo $this->lang->line('PostBox') ?></label>

                            <div class="col-sm-10">
                                <input type="text" placeholder="region"
                                       class="form-control margin-bottom" name="shipping_postbox"
                                       value="<?php echo $customer['shipping_postbox'] ?>" id="shipping_postbox">
                            </div>
                        </div>


                    </div>

                    <!-- erp2024 div postion changed add new col-md-12 starts-->
                     <!-- erp2024 newly added 01-06-2024 -->
                    <div class="col-md-12">
                        <br><h5><b><?php echo $this->lang->line('Customer Details'); ?></b></h5><hr>                        
                        <div class="form-group row">                                        
                            <label class="col-sm-2 col-form-label" for="Registration Number"><?php echo $this->lang->line('Registration Number') ?> </label>
                            <div class="col-sm-4">
                                <input type="text" placeholder="Registration Number" class="form-control margin-bottom b_input" name="registration_number" id="registration_number" value="<?php echo $customer['registration_number'] ?>">
                            </div>
                            <label class="col-sm-2 col-form-label" for="Expiry Date"><?php echo $this->lang->line('Expiry Date') ?> </label>
                            <div class="col-sm-4">
                                <input type="date" placeholder="Expiry Date" class="form-control margin-bottom b_input" name="expiry_date" id="expiry_date" value="<?php echo $customer['expiry_date'] ?>">
                            </div>
                        </div>   

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label" for="Computer Card"><?php echo $this->lang->line('Computer Card') ?> </label>
                            <div class="col-sm-2">
                                <input type="text" placeholder="Computer Card Number" class="form-control margin-bottom b_input" name="computer_card_number" id="computer_card_number"  value="<?php echo $customer['computer_card_number']; ?>">
                            </div>
                            <div class="col-sm-2">
                                <input type="file" placeholder="Computer Card" class="form-control margin-bottom b_input" name="computer_card_image" id="computer_card_image">
                            </div>
                            <div class="col-sm-2">
                               <img src="<?php echo base_url().'userfiles/customers/'.$customer['computer_card_image']; ?>" style="width:100px; height:50px;">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label" for="Sponser ID"><?php echo $this->lang->line('Sponser ID') ?> </label>
                            <div class="col-sm-2">
                                <input type="text" placeholder="Sponser ID" class="form-control margin-bottom b_input" name="sponser_id" id="sponser_id"  value="<?php echo $customer['sponser_id']; ?>">
                            </div>
                            <div class="col-sm-2">
                                <input type="file" placeholder="Sponser image" class="form-control margin-bottom b_input" name="sponser_image" id="sponser_image">
                            </div>
                            <div class="col-sm-2">
                               <img src="<?php echo base_url().'userfiles/customers/'.$customer['sponser_image']; ?>" style="width:100px; height:50px;">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label" for="Credit Limit"><?php echo $this->lang->line('Credit Limit') ?> </label>
                            <div class="col-sm-4">
                                <input type="number" placeholder="Credit Limit" class="form-control margin-bottom b_input" name="credit_limit" id="credit_limit" value="<?php echo $customer['credit_limit']; ?>">
                            </div>
                            
                            <label class="col-sm-2 col-form-label"   for="Credit Period"><?php echo $this->lang->line('Credit Period') ?> </label>
                            <div class="col-sm-4">
                                <input type="number" placeholder="No. of days" class="form-control margin-bottom b_input" name="credit_period" id="credit_period"  value="<?php echo $customer['credit_period']; ?>">
                            </div>
                        </div>
                        <div class="form-group row">

                            <label class="col-sm-2 col-form-label" for="postbox"><?php echo $this->lang->line('Tax') ?> ID</label>

                            <div class="col-sm-4">
                                <input type="text" placeholder="TAX ID"
                                       class="form-control margin-bottom" name="tax_id"
                                       value="<?php echo $customer['tax_id'] ?>">
                            </div>

                            <label class="col-sm-2 col-form-label"
                                   for="postbox"><?php echo $this->lang->line('Document') ?> ID</label>

                            <div class="col-sm-4">
                                <input type="text" placeholder="Document ID"
                                       class="form-control margin-bottom b_input" name="document_id"
                                       value="<?php echo $customer['document_id'] ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label"  for="postbox"><?php echo $this->lang->line('Extra') ?> </label>
                            <div class="col-sm-4">
                                <input type="text" placeholder="Custom Field" class="form-control margin-bottom b_input" name="c_field" value="<?php echo $customer['custom1'] ?>">
                            </div>

                            <label class="col-sm-2 col-form-label"
                                   for="customergroup"><?php echo $this->lang->line('Customer group') ?></label>

                            <div class="col-sm-4">
                                <select name="customergroup" class="form-control">
                                    <?php
                                    echo '<option value="' . $customergroup['id'] . '">' . $customergroup['title'] . ' (S)</option>';
                                    foreach ($customergrouplist as $row) {
                                        $cid = $row['id'];
                                        $title = $row['title'];
                                        echo "<option value='$cid'>$title</option>";
                                    }
                                    ?>
                                </select>


                            </div>
                        </div>
                        <div class="form-group row">

                            <label class="col-sm-2 col-form-label"
                                   for="customergroup">Language</label>
                            <div class="col-sm-4">
                                <select name="language" class="form-control b_input">
                                    <?php
                                    echo $langs;
                                    ?>
                                </select>
                            </div>
                            <label class="col-sm-2 col-form-label"  for="Discount"><?php echo $this->lang->line('Discount') ?> </label>
                            <div class="col-sm-4">
                                <input type="text" placeholder="Custom Discount"  class="form-control margin-bottom b_input" name="discount"   value="<?php echo $customer['discount_c'] ?>">
                            </div>
                        </div>

                        <?php foreach ($custom_fields as $row) {
                            if ($row['f_type'] == 'text') { ?>
                                <div class="form-group row">

                                    <label class="col-sm-2 col-form-label"
                                           for="document_id"><?= $row['name'] ?></label>

                                    <div class="col-sm-4">
                                        <input type="text" placeholder="<?= $row['placeholder'] ?>"
                                               class="form-control margin-bottom b_input"
                                               name="custom[<?= $row['id'] ?>]"
                                               value="<?= $row['data'] ?>">
                                    </div>
                                </div>


                            <?php }


                        }
                        ?>
                        <br><h5><b>Contact Details</b></h5><hr> 
                        <div class="form-group row">                                        
                            <label class="col-sm-2 col-form-label mb-1" for="Contact Person"><?php echo $this->lang->line('Contact Person') ?> </label>
                            <div class="col-sm-4 mb-1">
                                <input type="text" placeholder="Contact Person's Name" class="form-control margin-bottom b_input" name="contact_person" id="contact_person" value="<?php echo $customer['contact_person'] ?>">
                            </div>

                            <label class="col-sm-2 col-form-label mb-1"   for="contact_designation"><?php echo $this->lang->line('Designation') ?> </label>
                            <div class="col-sm-4 mb-1">
                                <input type="text" placeholder="Designation" class="form-control margin-bottom b_input" name="contact_designation" id="contact_designation" value="<?php echo $customer['contact_designation'] ?>">
                            </div>  

                            <label class="col-sm-2 col-form-label mb-1"   for="Land Line"><?php echo $this->lang->line('Land Line') ?> </label>
                            <div class="col-sm-4 mb-1">
                                <input type="text" placeholder="Land Line" class="form-control margin-bottom b_input" name="land_line" id="land_line" value="<?php echo $customer['land_line'] ?>">
                            </div>

                            <label class="col-sm-2 col-form-label mb-1" for="Contact Phone1"><?php echo $this->lang->line('Contact Phone1') ?> </label>
                            <div class="col-sm-4 mb-1">
                                <input type="text" placeholder="Contact Phone1" class="form-control margin-bottom b_input" name="contact_phone1" id="contact_phone1" value="<?php echo $customer['contact_phone1'] ?>">
                            </div>
                            <label class="col-sm-2 col-form-label mb-1"   for="Contact Phone2"><?php echo $this->lang->line('Contact Phone2') ?> </label>
                            <div class="col-sm-4 mb-1">
                                <input type="text" placeholder="Contact Phone2" class="form-control margin-bottom b_input" name="contact_phone2" id="contact_phone2" value="<?php echo $customer['contact_phone2'] ?>">
                            </div>

                            <label class="col-sm-2 col-form-label mb-1" for="Contact Email1"><?php echo $this->lang->line('Contact Email1') ?> </label>
                            <div class="col-sm-4 mb-1">
                                <input type="email" placeholder="Contact Email1" class="form-control margin-bottom b_input" name="contact_email1" id="contact_email1" value="<?php echo $customer['contact_email1'] ?>">
                            </div>
                            <label class="col-sm-2 col-form-label mb-1"   for="Contact Email2"><?php echo $this->lang->line('Contact Email2') ?> </label>
                            <div class="col-sm-4 mb-1">
                                <input type="email" placeholder="Contact Email2" class="form-control margin-bottom b_input" name="contact_email2" id="contact_email2" value="<?php echo $customer['contact_email2'] ?>">
                            </div>
                        </div> 
                    </div>
                    <!-- erp2024 div postion changed add new col-md-12 ends-->

                </div>
                <div class="form-group row">

                    <label class="col-sm-2 col-form-label"></label>

                    <div class="col-sm-4">
                        <input type="submit" id="cust_edit_submit" class="btn btn-lg btn-primary margin-bottom"  value="Update customer" data-loading-text="Updating...">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

