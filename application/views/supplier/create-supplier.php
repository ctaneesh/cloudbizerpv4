<div class="content-body">
<?php       
  if (($msg = check_permission($permissions)) !== true) {
     echo $msg;
     return;
  }
    $breadcrub_label = $this->lang->line('New Supplier');
    $btn_label = $this->lang->line('Add Supplier');
    $card_with_first = "col-lg-7 col-md-7 col-sm-12 col-xs-12";
    $card_with_second = "col-lg-5 col-md-5 col-sm-12 col-xs-12";
    $mainsection = "col-lg-2 col-md-4 col-sm-12 col-xs-12";
    $inputfile = "col-12";
    if($supplierid)
    {
        $breadcrub_label = $supplier['company'];
        $btn_label = $this->lang->line('Update Supplier');
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
                    <li class="breadcrumb-item"><a href="<?= base_url('supplier') ?>"><?php echo $this->lang->line('Suppliers') ?></a></li>
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
            
            <form method="post" id="supplier_data_form" class="form-horizontal" enctype="multipart/form-data">
                <div class="card1">
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
                                       aria-selected="false"><?php echo $this->lang->line('Supplier Details'); ?></a>
                                </li>

                            </ul>
                            <div class="tab-content pt-1">
                                <div class="tab-pane active show" id="tab1" role="tabpanel" aria-labelledby="base-tab1">
                                    <div class="form-row mt-1">
                                       
                                        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                            <input type="hidden" name="supplierid" id="supplierid" value="<?=$supplierid?>">
                                            <label class="col-form-label"
                                               for="name"><?php echo $this->lang->line('Company') ?><span class="compulsoryfld">*</span></label>
                                            <input type="text" placeholder="Company" class="form-control margin-bottom b_input" name="company" id="companyname" data-original-value="<?php echo $supplier['company']?>" value="<?php echo $supplier['company']?>">
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                            <label class="col-form-label" for="name"><?php echo $this->lang->line('Name') ?><span class="compulsoryfld">*</span></label>
                                            <input type="text" placeholder="Name" class="form-control margin-bottom b_input required" name="name" id="mcustomer_name" data-original-value="<?php echo $supplier['name']?>"  value="<?php echo $supplier['name']?>">
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                            <label class="col-form-label"
                                               for="phone"><?php echo $this->lang->line('Phone') ?><span class="compulsoryfld">*</span></label>
                                                <input type="text" placeholder="phone" class="form-control margin-bottom required b_input" name="phone" id="mcustomer_phone" data-original-value="<?php echo $supplier['phone']?>"  value="<?php echo $supplier['phone']?>">
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">

                                            <label class="col-form-label" for="email">Email<span class="compulsoryfld">*</span></label>
                                            <input type="email" placeholder="email" class="form-control margin-bottom required b_input" name="email" id="mcustomer_email" data-original-value="<?php echo $supplier['email']?>"  value="<?php echo $supplier['email']?>">
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                            <label class="col-form-label"
                                               for="address"><?php echo $this->lang->line('Address') ?></label>
                                            <!-- <input type="text" placeholder="address"
                                                   class="form-control margin-bottom b_input" name="address"
                                                   id="mcustomer_address1"> -->
                                            <textarea class="form-textarea margin-bottom b_input" placeholder="Address" name="address"
                                            id="mcustomer_address1" data-original-value="<?php echo $supplier['address']?>"> <?php echo $supplier['address']?></textarea>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                            <label class="col-form-label"
                                               for="city"><?php echo $this->lang->line('City') ?></label>
                                            <input type="text" placeholder="city"  class="form-control margin-bottom b_input" name="city" id="mcustomer_city" data-original-value="<?php echo $supplier['city']?>"  value="<?php echo $supplier['city']?>">
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                            <label class="col-form-label"
                                               for="region"><?php echo $this->lang->line('Region') ?></label>
                                            <input type="text" placeholder="Region" class="form-control margin-bottom b_input" name="region" id="region" data-original-value="<?php echo $supplier['region']?>"  value="<?php echo $supplier['region']?>">
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                            <label class="col-form-label"
                                               for="country"><?php echo $this->lang->line('Country') ?></label>
                                             <select name="country" id="mcustomer_country" class="form-control margin-bottom" required>
                                                <?php
                                                    echo "<option value=''>Select Country</option>";
                                                    foreach ($countries as $row) {
                                                        $cid = $row['id'];
                                                        $title = $row['name'];
                                                        $code = $row['code'];
                                                        $sel= ($supplier['country'] && $supplier['country']==$cid) ? "selected" : "";
                                                        echo "<option value='$cid' $sel>$title($code)</option>";
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                            <label class="col-form-label"
                                               for="postbox"><?php echo $this->lang->line('PostBox') ?></label>
                                            <input type="text" placeholder="PostBox" class="form-control margin-bottom b_input" name="postbox" id="postbox" data-original-value="<?php echo $supplier['billing_contact_person']?>"  value="<?php echo $supplier['postbox']?>">
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
                                        <div class="form-row">
                                        <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                            <label class="col-form-label" for="billing_name"><?php echo $this->lang->line('Name') ?></label>
                                            <input type="text" placeholder="Name" class="form-control margin-bottom b_input" name="billing_name" id="billing_name" value="<?=$supplier['billing_name']?>" data-original-value="<?php echo $supplier['billing_name'] ?>">
                                        </div>
                                        <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                            <label class="col-form-label" for="billing_contact_person"><?php echo $this->lang->line('Contact Person') ?></label>
                                            <input type="text" placeholder="Name" class="form-control margin-bottom b_input" name="billing_contact_person" id="billing_contact_person"  value="<?=$supplier['billing_contact_person']?>" data-original-value="<?php echo $supplier['billing_contact_person'] ?>">
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                            <label class="col-form-label" for="billing_address_1"><?php echo $this->lang->line('Billing Address 1') ?></label>
                                            <textarea placeholder="Address" class="form-textarea margin-bottom b_input" name="billing_address_1"  id="billing_address_1" data-original-value="<?php echo $supplier['billing_address_1'] ?>"><?=$supplier['billing_address_1']?></textarea>
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                            <label class="col-form-label" for="billing_city"><?php echo $this->lang->line('Billing Address 2') ?></label>
                                            <textarea placeholder="Address" class="form-textarea margin-bottom b_input" name="billing_address_2"  id="billing_address_2" data-original-value="<?php echo $supplier['billing_address_2'] ?>"><?=$supplier['billing_address_2']?></textarea>
                                        </div>
                                        <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                                            <label class="col-form-label" for="billing_city"><?php echo $this->lang->line('City') ?></label>
                                            <input type="text" placeholder="city" class="form-control margin-bottom b_input" name="billing_city" id="billing_city" value="<?=$supplier['billing_city']?>" data-original-value="<?php echo $supplier['billing_city'] ?>">
                                        </div>

                                        <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                                            <label class="col-form-label" for="billing_region"><?php echo $this->lang->line('Region') ?></label>
                                            <input type="text" placeholder="Region" class="form-control margin-bottom b_input" name="billing_region" id="billing_region" value="<?=$supplier['billing_region']?>" data-original-value="<?php echo $supplier['billing_region'] ?>">
                                        </div>

                                        <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                                            <label class="col-form-label" for="billing_postal_code"><?php echo $this->lang->line('PostBox') ?></label>
                                            <input type="text" placeholder="Postal Code" class="form-control margin-bottom b_input" name="billing_postal_code" id="billing_postal_code" value="<?=$supplier['billing_postal_code']?>"  data-original-value="<?php echo $supplier['billing_postal_code'] ?>">
                                        </div>

                                        <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                                            <label class="col-form-label" for="billing_country"><?php echo $this->lang->line('Country') ?></label>
                                            <select name="billing_country" id="billing_country" class="form-control margin-bottom"  data-original-value="<?php echo $supplier['billing_country'] ?>">
                                                <?php
                                                    echo "<option value=''>Select Country</option>";
                                                    foreach ($countries as $row) {
                                                        $cid = $row['id'];
                                                        $title = $row['name'];
                                                        $code = $row['code'];
                                                        $bsel= ($supplier['billing_country'] && $supplier['billing_country']==$cid) ? "selected" : "";
                                                        echo "<option value='$cid' $bsel>$title($code)</option>";
                                                    }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                                            <label class="col-form-label" for="billing_phone"><?php echo $this->lang->line('Phone') ?></label>
                                            <input type="text" placeholder="phone" class="form-control margin-bottom b_input" name="billing_phone" id="billing_phone"  value="<?=$supplier['billing_phone']?>"  data-original-value="<?php echo $supplier['billing_phone'] ?>">
                                        </div>

                                        <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                                            <label class="col-form-label" for="billing_email">Email</label>
                                            <input type="email" placeholder="email" class="form-control margin-bottom b_input" name="billing_email" id="billing_email"  value="<?=$supplier['billing_email']?>"  data-original-value="<?php echo $supplier['billing_email'] ?>">
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
                                    <div class="form-row">
                                        <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                            <label class="col-form-label" for="shipping_name"><?php echo $this->lang->line('Name') ?></label>
                                            <input type="text" placeholder="Name" class="form-control margin-bottom b_input" name="shipping_name" id="shipping_name" value="<?=$supplier['shipping_name']?>"  data-original-value="<?php echo $supplier['shipping_name'] ?>">
                                        </div>
                                        <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                            <label class="col-form-label" for="shipping_contact_person"><?php echo $this->lang->line('Contact Person') ?></label>
                                            <input type="text" placeholder="Name" class="form-control margin-bottom b_input" name="shipping_contact_person" id="shipping_contact_person" value="<?=$supplier['shipping_contact_person']?>" data-original-value="<?php echo $supplier['shipping_contact_person'] ?>">
                                        </div>
                                        <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                            <label class="col-form-label" for="address"><?php echo $this->lang->line('Shipping Address 1') ?></label>
                                            <textarea placeholder="Address" class="form-textarea margin-bottom b_input" name="shipping_address_1"  id="shipping_address_1" data-original-value="<?php echo $supplier['shipping_address_1'] ?>"><?=$supplier['shipping_address_1']?></textarea>
                                        </div>
                                        <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                            <label class="col-form-label" for="address"><?php echo $this->lang->line('Shipping Address 2') ?></label>
                                            <textarea placeholder="Address" class="form-textarea margin-bottom b_input" name="shipping_address_2"  id="shipping_address_2" data-original-value="<?php echo $supplier['shipping_address_2'] ?>"><?=$supplier['shipping_address_2']?></textarea>
                                        </div>
                                        <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                                            <label class="col-form-label" for="shipping_city"><?php echo $this->lang->line('City') ?></label>
                                            <input type="text" placeholder="city" class="form-control margin-bottom b_input" name="shipping_city" id="shipping_city" value="<?=$supplier['shipping_city']?>" data-original-value="<?php echo $supplier['shipping_city'] ?>">
                                        </div>

                                        <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                                            <label class="col-form-label" for="shipping_region"><?php echo $this->lang->line('Region') ?></label>
                                            <input type="text" placeholder="Region" class="form-control margin-bottom b_input" name="shipping_region" id="shipping_region" value="<?=$supplier['shipping_region']?>" data-original-value="<?php echo $supplier['shipping_region'] ?>">
                                        </div>

                                        <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                                            <label class="col-form-label" for="postbox"><?php echo $this->lang->line('PostBox') ?></label>
                                            <input type="text" placeholder="PostBox" class="form-control margin-bottom b_input" name="shipping_postal_code" id="shipping_postal_code" value="<?=$supplier['shipping_postal_code']?>" data-original-value="<?php echo $supplier['shipping_postal_code'] ?>">
                                        </div>

                                        
                                        <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                                            <label class="col-form-label" for="shipping_country"><?php echo $this->lang->line('Country') ?></label>
                                            <!-- <input type="text" placeholder="Country" class="form-control margin-bottom b_input" name="shipping_country" id="mcustomer_country_s"> -->
                                            <select name="shipping_country" id="shipping_country" class="form-control margin-bottom" data-original-value="<?php echo $supplier['shipping_country'] ?>">
                                                <?php
                                                    echo "<option value=''>Select Country</option>";
                                                    foreach ($countries as $row) {
                                                        $cid = $row['id'];
                                                        $title = $row['name'];
                                                        $code = $row['code'];
                                                        $ssel= ($supplier['shipping_country'] && $supplier['shipping_country']==$cid) ? "selected" : "";
                                                        echo "<option value='$cid' $ssel>$title($code)</option>";
                                                    }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                                            <label class="col-form-label" for="shipping_phone"><?php echo $this->lang->line('Phone') ?></label>
                                            <input type="text" placeholder="phone" class="form-control margin-bottom b_input" name="shipping_phone" id="shipping_phone" value="<?=$supplier['shipping_phone']?>" data-original-value="<?php echo $supplier['shipping_phone'] ?>">
                                        </div>

                                        <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                                            <label class="col-form-label" for="shipping_email">Email</label>
                                            <input type="email" placeholder="email" class="form-control margin-bottom b_input" name="shipping_email" id="shipping_email" value="<?=$supplier['shipping_email']?>" data-original-value="<?php echo $supplier['shipping_email'] ?>">
                                        </div>
                                        
                                    </div>


                                </div>
                                <div class="tab-pane" id="tab3" role="tabpanel" aria-labelledby="base-tab3">
                                    <!-- erp2024 newly added 01-06-2024 -->
                                    <div class="form-row">
                                        <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                        <label class="col-form-label" for="Registration Number"><?php echo $this->lang->line('Registration Number') ?><span class="compulsoryfld">*</span></label>
                                            <input type="text" placeholder="Registration Number" class="form-control margin-bottom b_input" name="registration_number" id="registration_number" required data-original-value="<?php echo $supplier['registration_number']?>" value="<?php echo $supplier['registration_number']?>">
                                        </div>
                                        <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                            <label class="col-form-label"   for="Expiry Date"><?php echo $this->lang->line('Expiry Date') ?> </label>
                                            <input type="date" placeholder="Expiry Date" class="form-control margin-bottom b_input" name="expiry_date" id="expiry_date" data-original-value="<?php echo $supplier['expiry_date']?>" value="<?php echo $supplier['expiry_date']?>">
                                        </div>
                                        <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                            <label class="col-form-label" for="Computer Card"><?php echo $this->lang->line('Computer Card') ?> </label>
                                            <div class="col-12 row">
                                                <input type="text" placeholder="Computer Card Number" class="form-control margin-bottom b_input col-lg-4 col-md-4 col-sm-12 mb-1" name="computer_card_number" id="computer_card_number"  data-original-value="<?php echo $supplier['computer_card_number'] ?>" value="<?=$supplier['computer_card_number']?>">
                                                <!-- <div class="col-lg-8 col-md-8 col-sm-12">
                                                    <div class="d-flex">
                                                        <input type="file" name="computer_card_image" id="computer_card_image" class="form-control1 input-file fileclass" accept=" .jpg, .jpeg, .png,.pdf" onchange="readURL(this);">
                                                        <img class="blah" src="" alt="your image" style="margin-left:10px; width:50px; height:50px;">
                                                        <button type="button" class="btn btn-crud btn-secondary btn-sm delete-btn-file-only" style="height:30px; height:30px; margin:3px;"><i class="fa fa-trash" ></i></button>
                                                    </div>
                                                </div> -->
                                                <div class="col-lg-8 col-md-8 col-sm-12">
                                                    <div class="d-flex">
                                                        <input type="file" name="computer_card_image" id="computer_card_image" class="form-control1 input-file fileclass" accept=" .jpg, .jpeg, .png,.pdf" onchange="readURL(this);"  data-original-value="<?php echo $supplier['computer_card_image'] ?>" value="<?php echo $supplier['computer_card_image'] ?>">
                                                        <input type="hidden" name="computer_card_image_text" value="<?php echo $supplier['computer_card_image'] ?>">
                                                        <?php
                                                        if (!empty($supplier['computer_card_image']) && is_string($supplier['computer_card_image']) && strtolower(pathinfo($supplier['computer_card_image'], PATHINFO_EXTENSION)) !== 'pdf') 
                                                        { ?>
                                                        <img class="blah" src="<?php echo base_url().'userfiles/customers/'.$supplier['computer_card_image']; ?>" alt="your image" style="margin-left:10px; width:50px; height:50px;">
                                                        <?php }
                                                        else if (!empty($supplier['computer_card_image']) && is_string($supplier['computer_card_image']) && strtolower(pathinfo($supplier['computer_card_image'], PATHINFO_EXTENSION)) === 'pdf') {
                                                        echo "<i class='fa fa-file-pdf-o' style='font-size:33px; margin:3px;disply:block!important;'></i>";
                                                        }
                                                        else{}
                                                        $supplier_id = $supplierid;
                                                        $computer_card_img_val = $supplier["computer_card_image"];
                                                        if (!empty($supplier['computer_card_image'])) { ?>
                                                        <button type="button" class="btn btn-crud btn-secondary btn-sm delete-btn-file-only" 
                                                            style="height:30px; margin:3px;" 
                                                            onclick="deleteItem('<?=$supplier_id?>','computer_card_image','<?=$computer_card_img_val?>')">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                        <?php } else { ?>
                                                        <button type="button" class="btn btn-crud btn-secondary btn-sm delete-btn-file-only" 
                                                            style="height:30px; margin:3px;">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                        <?php } ?>

                                                    </div>
                                                </div>
                                                <!-- <input type="file" placeholder="Computer Card" class="form-control margin-bottom b_input " name="computer_card_image" id="computer_card_image"> -->
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                            <label class="col-form-label" for="Sponser ID"><?php echo $this->lang->line('Sponser ID') ?> </label>
                                            <div class="col-12 row">
                                                <input type="text" placeholder="Sponser ID" class="form-control margin-bottom b_input b_input col-lg-4 col-md-4 col-sm-12 mb-1" name="sponser_id" id="sponser_id" data-original-value="<?php echo $supplier['sponser_id'] ?>" value="<?=$supplier['sponser_id']?>">
                                                <!-- <div class="col-lg-8 col-md-8 col-sm-12">
                                                    <div class="d-flex">
                                                        <input type="file" name="sponser_image" id="sponser_image" class="form-control1 input-file fileclass" accept=" .jpg, .jpeg, .png,.pdf" onchange="readURL(this);">
                                                        <img class="blah" src="" alt="your image" style="margin-left:10px; width:50px; height:50px;">
                                                        <button type="button" class="btn btn-crud btn-secondary btn-sm delete-btn-file-only" style="height:30px; height:30px; margin:3px;"><i class="fa fa-trash" ></i></button>
                                                    </div>
                                                </div> -->
                                               <div class="col-lg-8 col-md-8 col-sm-12">
                                                    <div class="d-flex">
                                                        <input type="file" name="sponser_image" id="sponser_image" class="form-control1 input-file fileclass" accept=" .jpg, .jpeg, .png,.pdf" onchange="readURL(this);" data-original-value="<?php echo $supplier['sponser_image'] ?>" value="<?php echo $supplier['sponser_image'] ?>">
                                                         <input type="hidden" name="sponser_image_text" value="<?php echo $supplier['sponser_image'] ?>">
                                                        <?php
                                                        if (!empty($supplier['sponser_image']) && is_string($supplier['sponser_image']) && strtolower(pathinfo($supplier['sponser_image'], PATHINFO_EXTENSION)) !== 'pdf') 
                                                        { ?>
                                                        <img class="blah" src="<?php echo base_url().'userfiles/customers/'.$supplier['sponser_image']; ?>" alt="your image" style="margin-left:10px; width:50px; height:50px;">
                                                        <?php }
                                                        else if (!empty($supplier['sponser_image']) && is_string($supplier['sponser_image']) && strtolower(pathinfo($supplier['sponser_image'], PATHINFO_EXTENSION)) === 'pdf') {
                                                        echo "<i class='fa fa-file-pdf-o' style='font-size:33px; margin:3px;disply:block!important;'></i>";
                                                        }
                                                        else{}
                                                        $supplier_id = $supplier["id"];
                                                        $sponser_img_val = $supplier["sponser_image"];
                                                        if (!empty($supplier['sponser_image'])) { ?>
                                                        <button type="button" class="btn btn-crud btn-secondary btn-sm delete-btn-file-only" 
                                                            style="height:30px; margin:3px;" 
                                                            onclick="deleteItem('<?=$supplier_id?>','sponser_image','<?=$sponser_img_val?>')">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                        <?php } else { ?>
                                                        <button type="button" class="btn btn-crud btn-secondary btn-sm delete-btn-file-only" 
                                                            style="height:30px; margin:3px;">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                        <?php } ?>

                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                            <label class="col-form-label" for="Credit Limit"><?php echo $this->lang->line('Credit Limit Allowed to the Company') ?> </label>
                                            <input type="number" placeholder="Credit Limit" class="form-control margin-bottom b_input" name="credit_limit" id="credit_limit" data-original-value="<?php echo $supplier['credit_limit']?>" value="<?php echo $supplier['credit_limit']?>">
                                        </div>                                        
                                        <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                            <label class="col-form-label"   for="Credit Period"><?php echo $this->lang->line('Credit Period Allowed') ?> </label>
                                            <input type="number" placeholder="No. of days" class="form-control margin-bottom b_input" name="credit_period" id="credit_period" data-original-value="<?php echo $supplier['credit_period']?>" value="<?php echo $supplier['credit_period']?>">
                                        </div>
                                    <!-- erp2024 newly added 01-06-2024 -->
                                     
                                        <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                            <label class="col-form-label" for="Discount"><?php echo $this->lang->line('Discount') ?> </label>
                                            <input type="text" placeholder="Custom Discount" class="form-control margin-bottom b_input" name="discount" data-original-value="<?php echo $supplier['discount']?>" value="<?php echo $supplier['discount']?>">
                                        </div>                                    
                                        <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                                <label class="col-form-label" for="tax_id"><?php echo $this->lang->line('TAX') ?> ID</label>
                                                <input type="text" placeholder="TAX ID" class="form-control margin-bottom b_input" name="tax_id" data-original-value="<?php echo $supplier['tax_id']?>" value="<?php echo $supplier['tax_id']?>">
                                        </div>
                                        <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                            <label class="col-form-label" for="document_id"><?php echo $this->lang->line('Document') ?> ID</label>
                                                <input type="text" placeholder="Document ID" class="form-control margin-bottom b_input" name="document_id" data-original-value="<?php echo $supplier['document_id']?>" value="<?php echo $supplier['document_id']?>">
                                        </div>
                                        
                                        <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12 d-none">
                                            <label class="col-form-label" for="c_field"><?php echo $this->lang->line('Extra') ?> </label>
                                            <input type="text" placeholder="Custom Field" class="form-control margin-bottom b_input" name="c_field">
                                        </div>
                                                                    
                                        <div  class="col-lg-3 col-md-4 col-sm-12 col-xs-12 d-none1">
                                            <label class="col-form-label" for="language">Language</label>
                                            <select name="language" id="language1" class="form-control b_input" style="width:100%">
                                                <?php
                                                echo $langs;
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12 d-none">
                                            <label class="col-form-label" for="currency"><?php echo $this->lang->line('Supplier Login') ?></label>
                                            <select name="c_login" class="form-control b_input">
                                                <option value="1"><?php echo $this->lang->line('Yes') ?></option>
                                                <option value="0"><?php echo $this->lang->line('No') ?></option>
                                            </select>
                                        </div>
                                        <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12 d-none">
                                            <label class="col-form-label" for="password_c"><?php echo $this->lang->line('New Password') ?></label>
                                            <input type="text" placeholder="Leave blank for auto generation"  class="form-control margin-bottom b_input" name="password_c" id="password_c">
                                        </div>
                                    <!-- erp2024 new field 03-06-2024 -->
                                     <?php 
                                     if(empty($supplierid))
                                     { ?>
                                    <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                         <label class="col-form-label" for="currency"><?php echo $this->lang->line('Profile Picture') ?></label>
                                        <div class="d-flex">
                                            <input type="file" name="picture" id="picture" class="form-control1 input-file fileclass" accept=" .jpg, .jpeg, .png" onchange="imgreadURL(this);">
                                            <input type="hidden" name="picture_text" value="<?php echo $supplier['picture'] ?>">
                                            <img class="blah" src="" alt="your image" style="margin-left:10px; width:50px; height:50px;">
                                            <button type="button" class="btn btn-crud btn-secondary btn-sm delete-btn-file-only" style="height:30px; height:30px; margin:3px;"><i class="fa fa-trash" ></i></button>
                                        </div>
                                    </div>
                                    <?php }?>
                                    
                                    </div>
                                    <br><h5><b>Contact Details</b></h5><hr>
                                    <div class="form-group row">     
                                        <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">                                   
                                            <label class="col-form-label mb-1" for="Contact Person"><?php echo $this->lang->line('Contact Person') ?> </label>
                                            <input type="text" placeholder="Contact Person's Name" class="form-control margin-bottom b_input" name="contact_person" id="contact_person" data-original-value="<?php echo $supplier['contact_person']?>" value="<?php echo $supplier['contact_person']?>">
                                        </div>
                                        
                                        <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                            <label class="col-form-label mb-1"   for="contact_designation"><?php echo $this->lang->line('Designation') ?> </label>
                                            <input type="text" placeholder="Designation" class="form-control margin-bottom b_input" name="contact_designation" id="contact_designation" value="<?php echo $supplier['contact_designation'] ?>" data-original-value="<?php echo $supplier['contact_designation']?>" value="<?php echo $supplier['contact_designation']?>">
                                        </div>  
                                        <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                            <label class="col-form-label mb-1"   for="Land Line"><?php echo $this->lang->line('Land Line') ?> </label>
                                            <input type="text" placeholder="Land Line" class="form-control margin-bottom b_input" name="land_line" id="land_line" data-original-value="<?php echo $supplier['land_line']?>" value="<?php echo $supplier['land_line']?>">
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                            <label class="col-form-label mb-1" for="Contact Phone1"><?php echo $this->lang->line('Contact Phone1') ?> </label>
                                            <input type="text" placeholder="Contact Phone1" class="form-control margin-bottom b_input" name="contact_phone1" id="contact_phone1" data-original-value="<?php echo $supplier['contact_phone1']?>" value="<?php echo $supplier['contact_phone1']?>">
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                            <label class="col-form-label mb-1"   for="Contact Phone2"><?php echo $this->lang->line('Contact Phone2') ?> </label>
                                            <input type="text" placeholder="Contact Phone2" class="form-control margin-bottom b_input" name="contact_phone2" id="contact_phone2" data-original-value="<?php echo $supplier['contact_phone2']?>" value="<?php echo $supplier['contact_phone2']?>">
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">                         
                                            <label class="col-form-label mb-1" for="Contact Email1"><?php echo $this->lang->line('Contact Email1') ?> </label>
                                            <input type="email" placeholder="Contact Email1" class="form-control margin-bottom b_input" name="contact_email1" id="contact_email1" data-original-value="<?php echo $supplier['contact_email1']?>" value="<?php echo $supplier['contact_email1']?>">
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                            <label class="col-form-label mb-1"   for="Contact Email2"><?php echo $this->lang->line('Contact Email2') ?> </label>
                                            <input type="email" placeholder="Contact Email2" class="form-control margin-bottom b_input" name="contact_email2" id="contact_email2" data-original-value="<?php echo $supplier['contact_email2']?>" value="<?php echo $supplier['contact_email2']?>">
                                        </div>
                                    </div> 
                                    <br><h5><b>Bank  Details</b></h5><hr> 
                                    <div class="form-group row">                      
                                        <div class="col-lg-3 col-md-3 col-sm-12 mb-1">                                      
                                            <label class="col-form-label" for="Contact Person"><?php echo $this->lang->line('Account Number') ?> </label>
                                            <input type="text" placeholder="Account Number" class="form-control margin-bottom b_input" name="account_number" id="account_number" value="<?php echo $supplier['account_number'] ?>" data-original-value="<?php echo $supplier['account_number']?>" value="<?php echo $supplier['account_number']?>">
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-12 mb-1">
                                            <label class="col-form-label"   for="account_holder"><?php echo $this->lang->line('Account Holder') ?> </label>
                                            <input type="text" placeholder="Account Holder" class="form-control margin-bottom b_input" name="account_holder" id="account_holder" value="<?php echo $supplier['account_holder'] ?>" data-original-value="<?php echo $supplier['account_holder']?>" value="<?php echo $supplier['account_holder']?>">
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-12 mb-1">
                                            <label class="col-form-label"   for="bank_name"><?php echo $this->lang->line('Bank Name') ?> </label>
                                            <input type="text" placeholder="Bank Name" class="form-control margin-bottom b_input" name="bank_name" id="bank_name" value="<?php echo $supplier['bank_name'] ?>" data-original-value="<?php echo $supplier['company']?>" value="<?php echo $supplier['bank_name']?>">
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-12 mb-1">                                       
                                            <label class="col-form-label" for="Bank Country"><?php echo $this->lang->line('Bank Country') ?> </label>
                                            <select name="bank_country" id="bank_country" class="form-control margin-bottom">
                                            <?php
                                                echo "<option value=''>Select Country</option>";
                                                foreach ($countries as $row) {
                                                    $cid = $row['id'];
                                                    $title = $row['name'];
                                                    $code = $row['code'];
                                                    $banksel= ($supplier['bank_country'] && $supplier['bank_country']==$cid) ? "selected" : "";
                                                    echo "<option value='$cid' $banksel>$title($code)</option>";
                                                }
                                            ?>
                                            </select>
                                        </div>                    
                                        <div class="col-lg-3 col-md-3 col-sm-12 mb-1">                                       
                                            <label class="col-form-label"   for="Bank Location"><?php echo $this->lang->line('Bank Location') ?> </label>
                                            <input type="text" placeholder="Bank Location" class="form-control margin-bottom b_input" name="bank_location" id="bank_location" data-original-value="<?php echo $supplier['bank_location']?>" value="<?php echo $supplier['bank_location']?>">
                                        </div>
                                    </div> 
                                    <!-- erp2024 new field 03-06-2024 -->
                                </div>

                                <div class="tab-pane show" id="tab4" role="tabpanel" aria-labelledby="base-tab4">

                                    <div class="form-group row">
                                        <?php
                                        foreach ($custom_fields as $row) {
                                            if ($row['f_type'] == 'text') { ?>
                                                <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                                    <label class="col-form-label"
                                                        for="document_id"><?= $row['name'] ?></label>
                                                        <input type="text" placeholder="<?= $row['placeholder'] ?>"
                                                            class="form-control margin-bottom b_input <?= $row['other'] ?>"
                                                            name="custom[<?= $row['id'] ?>]">
                                                    </div>
                                                </div>
                                            <?php }
                                        }
                                        ?>
                                    </div>

                                </div>
                                <div  id="mybutton" class="text-right">
                                    <hr>
                                     <!-- <button class="btn btn-crud btn-lg btn-secondary" type="button" id="prevTab"><i class="fa fa-backward" aria-hidden="true"></i> Previous</button>
                                    <button class="btn btn-crud btn-lg btn-secondary" type="button" id="nextTab">Next <i class="fa fa-forward" aria-hidden="true"></i></button> -->
                                    <input type="submit" id="supplier_add_submit"
                                           class="btn btn-crud1 btn-lg btn-primary margin-bottom float-xs-right mr-2"
                                           value="<?php echo $btn_label; ?>"
                                           data-loading-text="Adding...">
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
    $("#language1").select2();
    $("#mcustomer_country").select2();
    $("#bank_country").select2({
        width: "100%"
    });
    $("#mcustomer_country_s").select2({
        width: "100%" // Sets the width to 100%
    });
    //erp2024 Supplier add section 10-06-2024
    

    jQuery.validator.addMethod("phoneRegex", function (value, element) {
        // return this.optional(element) || /^\s*(\+?\d{1,4}[\s.-]?)?(\(?\d{1,5}\)?[\s.-]?){1,4}\d{1,5}\s*[\+0-9\(\)\.\-\s]{1,}[0-9]{1,}$/.test(value);
        return this.optional(element) || /^\s*(\+?\d{1,4}[\s.-]?)?(\(?\d{1,5}\)?[\s.-]?)?\d{1,5}[\s.-]?\d{1,5}[\s.-]?\d{1,5}\s*$/.test(value);
    }, "Enter a valid phone number");


    // $("#supplier_data_form").validate($.extend(true, {}, globalValidationOptions,{
    //     ignore: [],
    //     rules: {
    //         name: { required: true },
    //         phone: {
    //             required: true,
    //             phoneRegex :true
    //         },
    //         shipping_phone: {
    //             phoneRegex :true
    //         },
    //         email: {
    //             required: true,
    //             email: true
    //         }
    //     },
    //     messages: {
    //         name: "Enter Name",
    //         phone: "Enter a valid phone number",
    //         shipping_phone: "Enter a valid phone number",
    //         email: "Enter a valid Email"
    //     }
    // }));


    //erp2024 customer add section 03-06-2024
    $("#supplier_data_form").validate($.extend(true, {}, globalValidationOptions, {
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
    

    $("#supplier_add_submit").on("click", function (e) {
        e.preventDefault();
        $('#supplier_add_submit').prop('disabled', true);
        var registration_number = $('#registration_number').val();
        if ($("#supplier_data_form").valid() && (registration_number!="")) {
            var textdata = "Do you want to create a new supplier?";
            if($("#supplierid").val !="")
            {
                textdata = "Do you want to update supplier?";
            }
            var formData = new FormData($("#supplier_data_form")[0]);        
            formData.append('changedFields', JSON.stringify(changedFields));
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
               allowOutsideClick: false, 
            }).then((result) => {
               if (result.isConfirmed) {  
                    $.ajax({
                        type: 'POST',
                        url: baseurl + 'Supplier/add_new_supplier',
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: "json",
                        success: function(response) {
                            window.location.href = baseurl + 'supplier';
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
               }
               else{
                    $('#supplier_add_submit').prop('disabled', false);
               }
            });
        }
        else {
            $('#supplier_add_submit').prop('disabled', false);
            $('html, body').animate({
                scrollTop: $("#supplier_data_form").offset().top
            }, 2000);
            $("#supplier_data_form").focus();
            $('.alert-dismissible').removeClass('d-none');
        }
    });
    ////erp2024 Supplier add section ends 10-06-2024

//for tab navigation (next previous button)
$(document).ready(function() {
    var $tabs = $('.nav-link:visible');
    let currentTabIndex = $tabs.index($tabs.filter('.active'));
    $('#prevTab').hide();
    // $('#supplier_add_submit').hide();
    // Function to update the visibility of navigation buttons and submit button
    function updateButtons() {
        // Hide the "Previous" button when we're on the first tab (Billing Address tab)
        if (currentTabIndex === 21) {
            $('#prevTab').hide();  // Hide the Previous button on the first tab
        } else {
            $('#prevTab').show();  // Show the Previous button for all other tabs
        }

        // Disable "Next" button on the last tab
        $('#nextTab').prop('disabled', currentTabIndex >= $tabs.length - 1);

        // Show "Submit" button only on the last tab
        $('#supplier_add_submit').toggle(currentTabIndex === $tabs.length - 1);
    }

    // Function to show the selected tab
    function showTab(index) {
        if (index >= 0 && index < $tabs.length) {
            // Remove active class from the current tab
            $tabs.eq(currentTabIndex).removeClass('active show');
            $($tabs.eq(currentTabIndex).attr('href')).removeClass('active show');

            // Add active class to the new tab
            $tabs.eq(index).addClass('active show');
            $($tabs.eq(index).attr('href')).addClass('active show');

            // Update the current index
            currentTabIndex = index;
            updateButtons();
        }
    }

    // Navigation button click handlers
    $('#prevTab').click(function () {
        showTab(currentTabIndex - 1);
    });

    $('#nextTab').click(function () {
        showTab(currentTabIndex + 1);
    });
});

// Initialize button states on page load

    
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
    function deleteItem(supplier_id,fieldname,fieldval)
    {
         $.ajax({
            type: 'POST',
            url: baseurl + 'Supplier/delete_file',
            data: {
               id:supplier_id,
               fieldname : fieldname,
               fieldval : fieldval
            },
            dataType: "json",
            success: function(response) {
                //  location.reload();
            },
            error: function(xhr, status, error) {
                  console.error(xhr.responseText);
            }
         });
    }
</script>