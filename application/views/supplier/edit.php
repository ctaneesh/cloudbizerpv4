<div class="content-body">
    <div class="card">
        <div class="card-header">
            <h5><?php echo $this->lang->line('Edit supplier Details') ?></h5>

            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
            <div class="heading-elements">
                <ul class="list-inline mb-0">
                    
                    <li><a class="breaklink" data-action="expand"><i class="ft-maximize"></i></a></li>
                    
                </ul>
            </div>
        </div>
        <div class="card-body">
            <form method="post" id="data_form" class="form-horizontal">
                <input type="hidden" name="id" value="<?php echo $customer['id'] ?>">

                <div class="form-group row">

                    <label class="col-sm-2 col-form-label"
                           for="product_name"><?php echo $this->lang->line('Name') ?></label>

                    <div class="col-sm-6">
                        <input type="text" placeholder="Name"
                               class="form-control margin-bottom  required" name="name"
                               value="<?php echo $customer['name'] ?>">
                    </div>
                </div>
                <div class="form-group row">

                    <label class="col-sm-2 col-form-label"
                           for="product_name"><?php echo $this->lang->line('Company') ?></label>

                    <div class="col-sm-6">
                        <input type="text" placeholder="Company"
                               class="form-control margin-bottom" name="company"
                               value="<?php echo $customer['company'] ?>">
                    </div>
                </div>

                <div class="form-group row">

                    <label class="col-sm-2 col-form-label" for="phone"><?php echo $this->lang->line('Phone') ?></label>

                    <div class="col-sm-6">
                        <input type="text" placeholder="phone"
                               class="form-control margin-bottom required" name="phone"
                               value="<?php echo $customer['phone'] ?>">
                    </div>
                </div>
                <div class="form-group row">

                    <label class="col-sm-2 col-form-label" for="email"><?php echo $this->lang->line('Email') ?></label>

                    <div class="col-sm-6">
                        <input type="text" placeholder="email"
                               class="form-control margin-bottom  required" name="email"
                               value="<?php echo $customer['email'] ?>">
                    </div>
                </div>
                <div class="form-group row">

                    <label class="col-sm-2 col-form-label"
                           for="product_name"><?php echo $this->lang->line('Address') ?></label>

                    <div class="col-sm-6">
                        <input type="text" placeholder="address"
                               class="form-control margin-bottom" name="address"
                               value="<?php echo $customer['address'] ?>">
                    </div>
                </div>
                <div class="form-group row">

                    <label class="col-sm-2 col-form-label" for="city"><?php echo $this->lang->line('City') ?></label>

                    <div class="col-sm-6">
                        <input type="text" placeholder="city"
                               class="form-control margin-bottom" name="city"
                               value="<?php echo $customer['city'] ?>">
                    </div>
                </div>
                <div class="form-group row">

                    <label class="col-sm-2 col-form-label"
                           for="region"><?php echo $this->lang->line('Region') ?></label>

                    <div class="col-sm-6">
                        <input type="text" placeholder="region"
                               class="form-control margin-bottom" name="region"
                               value="<?php echo $customer['region'] ?>">
                    </div>
                </div>
                <div class="form-group row">

                    <label class="col-sm-2 col-form-label"
                           for="country"><?php echo $this->lang->line('Country') ?></label>

                    <div class="col-sm-6">
                        <select name="country" id="country" class="form-control margin-bottom">
                        <?php
                            echo "<option value=''>Select Country</option>";
                            foreach ($countries as $row) {
                                $cid = $row['id'];
                                $title = $row['name'];
                                $code = $row['code'];
                                $sel ="";
                                if($cid == $customer['country']){
                                    $sel = "selected";  
                                }
                                echo "<option value='$cid' $sel>$title($code)</option>";
                            }
                        ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row">

                    <label class="col-sm-2 col-form-label"
                           for="postbox"><?php echo $this->lang->line('PostBox') ?></label>

                    <div class="col-sm-6">
                        <input type="text" placeholder="region"
                               class="form-control margin-bottom" name="postbox"
                               value="<?php echo $customer['postbox'] ?>">
                    </div>
                </div>
                <div class="form-group row">

                    <label class="col-sm-2 col-form-label"
                           for="postbox"><?php echo $this->lang->line('Tax') ?> ID</label>

                    <div class="col-sm-6">
                        <input type="text" placeholder="TAX ID"
                               class="form-control margin-bottom" name="tax_id"
                               value="<?php echo $customer['tax_id'] ?>">
                    </div>
                </div>
                <!-- erp2024  modified potion ends 03-2024 -->
                <br><h5><b>Contact Details</b></h5><hr> 
                <div class="form-group row">  
                    
                    <div class="col-sm-4 mb-1">                                      
                        <label class="col-form-label" for="Contact Person"><?php echo $this->lang->line('Contact Person') ?> </label>
                        <input type="text" placeholder="Contact Person's Name" class="form-control margin-bottom b_input" name="contact_person" id="contact_person" value="<?php echo $customer['contact_person'] ?>">
                    </div>
                    <div class="col-sm-4 mb-1">                                      
                        <label class="col-form-label" for="contact_designation"><?php echo $this->lang->line('Designation') ?> </label>
                        <input type="text" placeholder="Designation" class="form-control margin-bottom b_input" name="contact_designation" id="contact_designation" value="<?php echo $customer['contact_designation'] ?>">
                    </div>
                    <div class="col-sm-4 mb-1">
                        <label class="col-form-label"   for="Land Line"><?php echo $this->lang->line('Land Line') ?> </label>
                        <input type="text" placeholder="Land Line" class="form-control margin-bottom b_input" name="land_line" id="land_line" value="<?php echo $customer['land_line'] ?>">
                    </div>
                    <div class="col-sm-4 mb-1">                                       
                         <label class="col-form-label" for="Contact Phone1"><?php echo $this->lang->line('Contact Phone1') ?> </label>
                        <input type="text" placeholder="Contact Phone1" class="form-control margin-bottom b_input" name="contact_phone1" id="contact_phone1" value="<?php echo $customer['contact_phone1'] ?>">
                    </div>                    
                    <div class="col-sm-4 mb-1">                                       
                        <label class="col-form-label"   for="Contact Phone2"><?php echo $this->lang->line('Contact Phone2') ?> </label>
                        <input type="text" placeholder="Contact Phone2" class="form-control margin-bottom b_input" name="contact_phone2" id="contact_phone2" value="<?php echo $customer['contact_phone2'] ?>">
                    </div>
                    <div class="col-sm-4 mb-1">                                                                            
                        <label class="col-form-label" for="Contact Email1"><?php echo $this->lang->line('Contact Email1') ?> </label>
                        <input type="email" placeholder="Contact Email1" class="form-control margin-bottom b_input" name="contact_email1" id="contact_email1" value="<?php echo $customer['contact_email1'] ?>">
                    </div>
                    <div class="col-sm-4 mb-1">                                       
                        <label class="col-form-label"   for="Contact Email2"><?php echo $this->lang->line('Contact Email2') ?> </label>
                        <input type="email" placeholder="Contact Email2" class="form-control margin-bottom b_input" name="contact_email2" id="contact_email2" value="<?php echo $customer['contact_email2'] ?>">
                    </div>
                    <div class="col-sm-4 mb-1">                                       
                        <label class="col-form-label"   for="Contact Email2"><?php echo $this->lang->line('Company Website') ?> </label>
                        <input type="url" placeholder="website url" class="form-control margin-bottom b_input" name="website_url" id="website_url" value="<?php echo $customer['website_url'] ?>">
                    </div>
                </div> 


                <br><h5><b>Bank  Details</b></h5><hr> 
                <div class="form-group row">                      
                    <div class="col-sm-4 mb-1">                                      
                        <label class="col-form-label" for="Contact Person"><?php echo $this->lang->line('Account Number') ?> </label>
                        <input type="text" placeholder="Account Number" class="form-control margin-bottom b_input" name="account_number" id="account_number" value="<?php echo $customer['account_number'] ?>">
                    </div>
                    <div class="col-sm-4 mb-1">
                        <label class="col-form-label"   for="Land Line"><?php echo $this->lang->line('Account Holder') ?> </label>
                        <input type="text" placeholder="Land Line" class="form-control margin-bottom b_input" name="account_holder" id="account_holder" value="<?php echo $customer['account_holder'] ?>">
                    </div>
                    <div class="col-sm-4 mb-1">                                       
                         <label class="col-form-label" for="Bank Country"><?php echo $this->lang->line('Bank Country') ?> </label>
                        <select name="bank_country" id="bank_country" class="form-control margin-bottom">
                        <?php
                            echo "<option value=''>Select Country</option>";
                            foreach ($countries as $row) {
                                $cid = $row['id'];
                                $title = $row['name'];
                                $code = $row['code'];
                                $sel ="";
                                if($cid == $customer['bank_country']){
                                    $sel = "selected";  
                                }
                                echo "<option value='$cid' $sel>$title($code)</option>";
                            }
                        ?>
                        </select>
                    </div>                    
                    <div class="col-sm-4 mb-1">                                       
                        <label class="col-form-label"   for="Bank Location"><?php echo $this->lang->line('Bank Location') ?> </label>
                        <input type="text" placeholder="Bank Location" class="form-control margin-bottom b_input" name="bank_location" id="bank_location" value="<?php echo $customer['bank_location'] ?>">
                    </div>
                </div> 
                <!-- erp2024 modification ends 03-06-2024 -->
                <div class="form-group row">
                    <div class="col-sm-4">
                        <input type="submit" id="submit-data" class="btn btn-success margin-bottom"   value="<?php echo $this->lang->line('Update') ?>" data-loading-text="Updating...">
                        <input type="hidden" value="supplier/editsupplier" id="action-url">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
     // erp2024 add 03-06-2024
     $("#country").select2();
     $("#bank_country").select2();
    // erp2024 add 03-06-2024  ends
</script>