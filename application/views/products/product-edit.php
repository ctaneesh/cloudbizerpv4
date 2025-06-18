<div class="content-body">
    <div class="card">
        <div class="card-header border-bottom">
            
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard') ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('products') ?>"><?php echo $this->lang->line('Products') ?></a></li>
                </ol>
            </nav>
            <h5><?php echo $this->lang->line('Edit Product') ?></h5>
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
                <div class="col-12 alert alert-danger alert-dismissible d-none" role="alert">
                    <strong><?php echo $this->lang->line('Please fill out all mandatory fields') ?></strong>
                </div>
                <form method="post" id="data_form" class="form-horizontal" enctype="multipart/form-data">
                    <input type="hidden" id="pid" name="pid" value="<?php echo $product['pid'] ?>">
                    <!-- erp2024  new updation 05-06-2024  -->
                    <ul class="nav nav-tabs mb-2" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link breaklink active show" id="base-tab1" data-toggle="tab"
                                aria-controls="tab1" href="#tab1" role="tab"
                                aria-selected="true"><?php echo $this->lang->line('Product Details') ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link breaklink" id="base-tab2" data-toggle="tab" aria-controls="tab2"
                                href="#tab2" role="tab"
                                aria-selected="false"><?php echo $this->lang->line('Product Pricing') ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link breaklink" id="base-tab3" data-toggle="tab" aria-controls="tab3"
                                href="#tab3" role="tab"
                                aria-selected="false"><?php echo $this->lang->line('Inventory Management') ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link breaklink" id="base-tab8" data-toggle="tab" aria-controls="tab8"
                                href="#tab8" role="tab"
                                aria-selected="false"><?php echo $this->lang->line('Accounting') ?></a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link breaklink" id="base-tab6" data-toggle="tab" aria-controls="tab6"
                                href="#tab6" role="tab"
                                aria-selected="false"><?php echo $this->lang->line('Location') ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link breaklink" id="base-tab7" data-toggle="tab" aria-controls="tab7"
                                href="#tab7" role="tab"
                                aria-selected="false"><?php echo $this->lang->line('Additional Details') ?></a>
                        </li>
                        <!-- <li class="nav-item ml-auto">
                            <input type="submit" class="btn  btn-blue margin-bottom submit-data"   value="<?php echo $this->lang->line('Update') ?>" data-loading-text="Adding...">
                        </li> -->
                        <li class="nav-item">
                            <a class="nav-link breaklink" id="base-tab11" data-toggle="tab"
                            aria-controls="tab11" href="#tab11" role="tab"
                            aria-selected="true"><?php echo $this->lang->line('Details') ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link breaklink" id="base-tab22" data-toggle="tab" aria-controls="tab22"
                                href="#tab22" role="tab"
                                aria-selected="false"><?php echo $this->lang->line('Onhand') ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link breaklink" id="base-tab23" data-toggle="tab" aria-controls="tab23"
                                href="#tab23" role="tab"
                                aria-selected="false"><?php echo $this->lang->line('Weighted Average Costing Data') ?></a>
                        </li>
                    </ul>
                    <div class="tab-content px-1 pt-1">
                        <div class="tab-pane active show" id="tab1" role="tabpanel" aria-labelledby="base-tab1">
                            <div class="form-group row">
                                <!-- erp2024 new fields 05-06-2024 -->
                                <div class="col-lg-3 mb-2">
                                    <h6><b>Stock(On Hand) : <span class="productStockQty"><?php echo  $product['qty']; ?></span></b></h6>
                                </div>
                                <div class="col-lg-2">
                                    <h6><b><?php echo $this->lang->line('Alert Quantity') ?> : <span class="productAlertQty"><?php echo $product['alert']; ?></span></b></h6>
                                </div>

                                <div class="col-lg-3">
                                    <h6><b>Customer Order : <span class="productAlertQty"><?php echo $sales_qty; ?></span></b></h6>
                                </div>
                                <div class="col-lg-2">
                                    <h6><b>Purchase Order : <span class="productAlertQty"><?php echo $purchase_qty; ?></span></b></h6>
                                </div>
                                <div class="col-lg-2">
                                    <h6><b>IN Transist : <span class="productTransist"></span></b></h6>
                                </div>
                                <!-- erp2024 new fields 05-06-2024 ends-->
                                <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                    <label class="col-form-label" for="product_nam"><?php echo $this->lang->line('Product Name') ?><span class="compulsoryfld">*</span></label>
                                    <input type="text" placeholder="Product Name"   class="form-control margin-bottom  required" id="product_nam" name="product_name" value="<?php echo $product['product_name'] ?>" data-original-value="<?php echo $product['product_name']  ?>">
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                    <label class="col-form-label" for="product_cod"><?php echo $this->lang->line('Item Code') ?><span class="compulsoryfld">*</span></label>
                                    <input type="text" placeholder="Product Code" class="form-control required" id="product_cod" name="product_code"  value="<?php echo $product['product_code'] ?>" data-original-value="<?php echo $product['product_code']  ?>">
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                
                                    <label class="col-form-label" for="product_code"><b>Last Updated Details</b></label>
                                    <?php if(!empty($userdetails)){ ?> 
                                    <p> Updated By : <?php echo $userdetails['username'];?><br>        
                                        Email : <?php echo $userdetails['email'];?> <br>
                                        Updated By : <?php echo date("d-m-Y H:i:s", strtotime(($userdetails['updated_dt'])));?></p>
                                    <?php } ?>
                                </div>

                                <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                    <label class="col-form-label"  for="arabic_name"><?php echo $this->lang->line('Arabic Name') ?><span class="compulsoryfld">*</span></label>
                                    <input type="text" placeholder="Arabic Name"   class="form-control required" name="arabic_name" id="arabic_name" value="<?php echo $productai['arabic_name'] ?>" data-original-value="<?php echo $productai['arabic_name']  ?>">
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                    <label class="col-form-label"  for="product_desc"><?php echo $this->lang->line('Item Description') ?><span class="compulsoryfld">*</span></label>
                                    <textarea placeholder="Description" class="form-textarea margin-bottom" id="product_desc" name="product_desc" data-original-value="<?php echo $product['product_des']  ?>"><?php echo $product['product_des'] ?></textarea>
                                </div>
                                                 
                             <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                    <label class="col-form-label" for="product_cat"><?php echo $this->lang->line('Product Category') ?><span class="compulsoryfld">*</span></label>
                                    <select name="product_cat[]" class="form-control" id="product_cat"  multiple="multiple" data-original-value="<?php echo $cat;  ?>" required>
                                        <?php
                                        // echo '<option value="' . $cat_ware['cid'] . '">' . $cat_ware['catt'] . '</option>';
                                        foreach ($cat as $row) {
                                            $cid = $row['id'];
                                            $title = $row['title'];
                                            $selected = in_array($cid, $category_linked) ? 'selected' : '';
                                            echo "<option value='$cid' $selected>$title</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                    <label class="col-form-label" for="sub_cat"><?php echo $this->lang->line('Sub') ?><?php echo $this->lang->line('Category') ?></label>
                                    <select id="sub_cat" name="sub_cat[]" class="form-control select-box" data-original-value="<?php echo $cat_sub_list;  ?>" multiple="multiple"> 
                                        <?php
                                            foreach ($cat_sub_list as $row) {
                                                $cid = $row['id'];
                                                $title = $row['title'];
                                                $selected1 = in_array($cid, $subcategory_linked) ? 'selected' : '';
                                                echo "<option value='$cid' $selected1>$title</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                    <label class="col-form-label" for="made_in"><?php echo $this->lang->line('Made IN');?>*</label>
                                    <select name="made_in" id="made_in" class="form-control required" data-original-value="<?php echo $madein;  ?>" required>
                                    <?php
                                
                                    foreach ($madein as $row) {
                                        $cid = $row['id'];
                                        $title = $row['name'];
                                        $code = $row['code'];
                                        $sel="";
                                        if($cid == $productai['made_in']){
                                            $sel = "selected";
                                        }
                                        echo "<option value='$cid' $sel>$title($code)</option>";
                                    }
                                    ?>
                                    </select>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label" for="manufacturer_id"><?php echo $this->lang->line('Manufacturers')?><span class="compulsoryfld">*</span></label>
                                <select name="manufacturer_id" id="manufacturer_id" class="form-control" data-original-value="<?php echo $manufacturers; ?>">
                                    <?php                                    
                                    foreach ($manufacturers as $row) {
                                        $cid = $row['manufacturer_id'];
                                        $title = $row['manufacturer_name'];
                                        $manufatureid = $productai['manufacturer_id'];
                                        $sel ="";
                                        if($manufatureid == $cid){
                                            $sel = "selected";
                                        }
                                        echo "<option value='$cid' $sel>$title</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label" for="manufacturer_partno"><?php echo $this->lang->line('Manufacturer Partno');  ?></label>
                                <input type="text" placeholder="Manufacturer partno"   class="form-control required" id="manufacturer_partno" name="manufacturer_partno" value="<?php echo $productai['manufacturer_partno']; ?>" data-original-value="<?php echo $productai['manufacturer_partno']; ?>">
                            </div>
                            
                            <!-- //erp2024 08-10-2024 -->
                            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label" for="brand_id"><?php echo $this->lang->line('Brand');?></label>
                                <select name="brand_id[]" id="brand_id" class="form-control" data-original-value="<?php echo $brand_linked; ?>" multiple="multiple">
                                <option value=''><?php echo $this->lang->line('Select Brand');  ?></option>
                                <?php
                                        if(!empty($brands))
                                        {
                                            foreach($brands as $brand)
                                            {
                                                $selected2 = in_array($brand['id'], $brand_linked) ? 'selected' : '';
                                                echo '<option value="'.$brand['id'].'" '.$selected2.'>'.ucfirst($brand['brand_name']).'</option>';
                                            }
                                        }
                                ?>
                                </select>
                            </div>
                              <!-- //erp2024 08-10-2024 -->
                            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label" for="prefered_vendor"><?php echo $this->lang->line('Prefered Vendor');?></label>
                                
                                <select name="prefered_vendor" id="prefered_vendor" class="form-control" data-original-value="<?php echo $suppliers; ?>">
                                    <?php
                                    foreach ($suppliers as $row) {
                                        $cid = $row['id'];
                                        $title = $row['name'];
                                        $preferid = $productai['prefered_vendor'];
                                        $sel="";
                                        if($preferid == $cid){
                                            $sel = "selected";
                                        }
                                        echo "<option value='$cid' $sel>$title</option>";
                                    }
                                    ?>
                                </select>
                             </div>     
                                <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                    <label class="col-form-label" for="product_tax"><?php echo $this->lang->line('Default TAX Rate') ?></label>
                                    <div class="input-group">
                                        <input type="text" name="product_tax" id="product_tax" class="form-control"  placeholder="0.00" aria-describedby="sizing-addon1"  onkeypress="return isNumber(event)" value="<?php echo amountFormat_general($product['taxrate']) ?>" data-original-value="<?php echo amountFormat_general($product['taxrate']); ?>"><span class="input-group-addon">%</span>
                                    </div>
                                    <small><?php echo $this->lang->line('Tax rate during') ?></small>
                                </div>                               
                                
                            
                                <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                    <label class="col-form-label" for="status"><?php echo $this->lang->line('Status') ?></label>
                                    <select name="status" id="status" class="form-control" data-original-value="<?php echo $product['status']; ?>">
                                        <option value="Enable" <?php if($product['status']=='Enable'){ echo 'selected'; } ?>>Enable</option>
                                        <option value="Disable" <?php if($product['status']=='Disable'){ echo 'selected'; } ?>>Disable</option>                    
                                    </select>
                                </div>
                            </div>
                            <div class="form-group1 row">
                                <!-- <label class="col-sm-2 col-form-label"><?php echo $this->lang->line('Stock Units') ?>*</label> -->

                                <!-- <div class="col-sm-4">
                                    <input type="hidden" placeholder="Total Items in stock" class="form-control margin-bottom required" name="product_qty" onkeypress="return isNumber(event)" value="<?php echo $product['qty']; ?>" id="product_qty">
                                </div> -->
                            </div>
                            
                            <div class="form-group1 row">
                                <!-- <label class="col-sm-2 col-form-label"><?php echo $this->lang->line('Alert Quantity') ?></label> -->

                                <!-- <div class="col-sm-4">
                                    <input type="hidden" placeholder="Low Stock Alert Quantity" class="form-control margin-bottom" name="product_qty_alert" id="product_qty_alert" value="<?php echo $product['alert'] ?>" onkeypress="return isNumber(event)">
                                </div> -->
                            </div>
                            <hr>
                            <div class="form-group row">                                
                                
                                <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                                <label class="col-form-label" for="code_type"><?php echo $this->lang->line('BarCode') ?></label>
                                    <select class="form-control" id="code_type" name="code_type" data-original-value="<?php echo $product['barcode']; ?>">
                                        <?php echo $product['barcode'] ?>
                                        
                                        <option value="EAN13" <?php if($product['code_type']=='EAN13'){ echo "selected"; ?> data-original-value="<?php echo $product['barcode']; ?>" <?php } ?>>EAN13 - Default</option>
                                        <option value="UPCA" <?php if($product['code_type']=='UPCA'){ echo "selected";?> data-original-value="<?php echo $product['barcode']; ?>" <?php } ?>>UPC-A</option>
                                        <!-- <option value="EAN8">EAN8</option>
                                        <option value="ISSN">ISSN</option>
                                        <option value="ISBN">ISBN</option>
                                        <option value="C128A">C128A</option>
                                        <option value="C39">C39</option> -->
                                    </select>
                                </div>
                                <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                <?php
                                        $maxlength = ($product['code_type']=='UPCA') ? 11 : 12;
                                    ?>
                                   <label class="col-form-label" for="code_type"><?php echo $this->lang->line('BarCode Value') ?></label>
                                    <input type="text" placeholder="BarCode" class="form-control" title="Barcode Numeric " id="numeric_barcode"  name="barcode"  value="<?php echo $product['barcode'] ?>" data-original-value="<?php echo $product['barcode'] ?>" onkeypress="return isNumber(event)" maxlength="<?=$maxlength?>">
                                    <small id="barcode-terms"></small>

                                </div>


                                <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                                <label class="col-form-label" for="code_type2"><?php echo $this->lang->line('Second BarCode') ?></label>
                                    <select class="form-control" id="code_type2" name="code_type2" data-original-value="<?php echo $product['barcode2']; ?>">
                                        <?php //echo $product['barcode2'] ?>
                                        <option value="UPCA" <?php if($product['code_type2']=='UPCA'){ echo "selected"; ?> data-original-value="<?php echo $product['barcode2']; ?>" <?php } ?>>UPC-A</option>
                                        <option value="EAN13" <?php if($product['code_type2']=='EAN13'){ echo "selected"; ?> data-original-value="<?php echo $product['barcode2']; ?>" <?php } ?>>EAN13 - Default</option>
                                        <!-- <option value="EAN8">EAN8</option>
                                        <option value="ISSN">ISSN</option>
                                        <option value="ISBN">ISBN</option>
                                        <option value="C128A">C128A</option>
                                        <option value="C39">C39</option> -->
                                    </select>
                                </div>
                                <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                    <?php
                                        $maxlength2 = ($product['code_type2']=='UPCA') ? 11 : 12;
                                    ?>
                                   <label class="col-form-label" for="numeric_barcode2"><?php echo $this->lang->line('Second BarCode Value') ?></label>
                                    <input type="text" placeholder="BarCode" class="form-control" title="Barcode Numeric " id="numeric_barcode2"  name="barcode2"  value="<?php echo $product['barcode2'] ?>" data-original-value="<?php echo $product['barcode2'] ?>" onkeypress="return isNumber(event)" maxlength="<?=$maxlength2?>">
                                    <small id="barcode-terms2"></small>

                                </div>

                               
                        </div>
                        <!-- erp2024 old code 24-06-2024 -->
                            <?php //foreach ($custom_fields as $row) {
                                //if ($row['f_type'] == 'text') { ?>
                                    <!-- <div class="form-group row">
                                        <label class="col-sm-2 col-form-label" for="document_id"><?= $row['name'] ?></label>

                                        <div class="col-sm-8">
                                            <input type="text" placeholder="<?= $row['placeholder'] ?>" class="form-control margin-bottom b_input"  name="custom[<?= $row['id'] ?>]"  value="<?= $row['data'] ?>">
                                        </div>
                                    </div> -->


                                <?php //} } ?>
                        <!-- erp2024 old code 24-06-2024 -->
                            
                            <!-- <div class="form-group row">
                                <label class="col-sm-2 control-label" for="edate"><?php echo $this->lang->line('Valid') . ' (' . $this->lang->line('To Date') ?>)</label>

                                <div class="col-sm-2">
                                    <input type="text" class="form-control required editdate2" placeholder="Expiry Date" name="wdate" autocomplete="false" value="<?= dateformat($product['expiry']) ?>">
                                </div>
                                <small>Do not change if not applicable</small>
                            </div>  -->
                            
                            <hr>
                            <div class="form-group row">
                                
                                <div class="col-lg-5 col-md-6 col-sm-12 col-xs-12">
                                    <label class="col-form-label" for="upfile-0"><?php echo $this->lang->line('Add Attachments') ?></label>
                                    <div class="row">                            
                                        <div class="col-8">
                                            <div class="d-flex">
                                                <input type="file" name="upfile[]" id="upfile-0" class="form-control1 input-file" accept=".pdf, .jpg, .jpeg, .png" onchange="readURL(this);">
                                                <img class="blah" src="" alt="your image" style="margin-left:10px; width:50px; height:50px;">
                                                <button type="button" class="btn btn-crud btn-secondary btn-sm delete-btn" style="height:30px; height:30px; margin:3px;"  title="Remove"><i class="fa fa-trash" ></i></button>
                                            </div>
                                            <div id="uploadsection"></div>                                             
                                        </div>                      
                                        <div class="col-4">
                                                <button class="btn btn-crud btn-secondary btn-sm mt-1" id="addmore_img"  title="Add More Files" type="button"><i class="fa fa-plus-circle"></i> Add More</button>
                                            
                                        </div>  
                                    </div>

                                </div>

                                 <!-- Image upload sections ends -->
                                 <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12 mb-1">
                                     <!-- ===== Image sections starts ============== -->
                                    <div class="container-fluid">
                                        <div class="mt-2">
                                            
                                            <!-- <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12"> -->
                                                <?php 
                                            $imgcontains = 0;
                                            if (!empty($images)) {
                                                echo '<table class="table table-striped table-bordered table-responsive">';
                                                $imgcontains = 1;
                                            
                                                foreach ($images as $image) {
                                                    $file_extension = strtolower(pathinfo($image['file_name'], PATHINFO_EXTENSION));
                                                    $is_image = in_array($file_extension, ['jpg', 'jpeg', 'png']);
                                                    $file_url = base_url("uploads/{$image['file_name']}");
                                                    $img_tag = $is_image ? "<img src='{$file_url}' class='img-thumbnail' alt='{$image['actual_name']}' style='width:70px; height:70px;'>" : '<i class="fa fa-file-code-o fsize-70"></i>';
                                                    $download_attr = $is_image ? 'download' : '';
                                                    $icon = "Click to download <i class='fa fa-download'></i><br>";
                                                    $imgname = $image['actual_name'];
                                            
                                                    if ($imgcontains % 5 == 1) {
                                                        echo '<tr>';
                                                    }
                                            
                                                    echo "<td class='text-center file-td-section'>";
                                                    echo "<div class='file-section'>";
                                                    echo $img_tag ? "{$img_tag}" : '';
                                                    echo '<p>'.$imgname.'</p>';
                                                    echo "<a href='{$file_url}' target='_blank' {$download_attr} class='btn btn-crud btn-sm btn-secondary'>{$icon}</a>&nbsp;";
                                                    echo "<button class='btn btn-crud btn-sm btn-secondary' onclick=\"deleteitem('{$image['id']}','{$image['file_name']}')\" type='button'><i class='fa fa-trash'></i></button>";
                                                    echo "</div>";
                                                    echo "";
                                                    echo "</td>";
                                            
                                                    if ($imgcontains % 5 == 0) {
                                                        echo '</tr>';
                                                    }
                                            
                                                    $imgcontains++;
                                                }
                                            
                                                // Close the last row if it wasn't closed
                                                if (($imgcontains - 1) % 5 != 0) {
                                                    echo '</tr>';
                                                }
                                            
                                                echo '</table>';
                                            }
                                            ?>

                                        </div>
                                    </div>
                                    <!-- ===== Image sections ends ============== -->
                                </div>
                            </div>
                            
                            <!-- <button class="btn btn-pink add_serial btn-sm m-1"><?php echo $this->lang->line('add_serial') ?></button><div id="added_product"></div> -->

                            <?php
                            if (is_array(@$serial_list[0])) {
                                foreach ($serial_list as $item) { ?>
                                    <div class="form-group serial">
                                        <label for="field_s" class="col-lg-2 control-label"><?php echo $this->lang->line('serial') ?></label>
                                        <div class="col-lg-10">
                                            <input class="form-control box-size" placeholder="<?php echo $this->lang->line('serial') ?>" type="text"   value="<?= $item['serial'] ?>" <?= ($item['status'] ? 'readonly=""' : 'name="product_serial_e['.$item['id'].']"'); ?>>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }


                            if ($product['merge'] == 0) { ?>
                                <!-- <div id="accordionWrapa1" role="tablist" aria-multiselectable="true">

                                    <div id="coupon4" class="card-header">
                                        <a data-toggle="collapse" data-parent="#accordionWrapa1" href="#accordion41"
                                        aria-expanded="true" aria-controls="accordion41"
                                        class="card-title lead collapsed"><i class="fa fa-plus-circle"></i>
                                            <?php echo $this->lang->line('Products') . ' ' . $this->lang->line('Variations') ?>
                                        </a>
                                    </div>
                                    <div id="accordion41" role="tabpanel" aria-labelledby="coupon4"
                                        class="card-collapse collapse" aria-expanded="false" style="height: 0px;">
                                        <div class="row p-1">
                                            <div class="col">
                                                <button class="btn btn-blue tr_clone_add">Add Row</button>
                                                <hr>
                                                <table class="table" id="v_var">
                                                    <tr>
                                                        <td><select name="v_type[]" class="form-control">
                                                                <?php
                                                                foreach ($variables as $row) {
                                                                    $cid = $row['id'];
                                                                    $title = $row['name'];
                                                                    $title = $row['name'];
                                                                    $variation = $row['variation'];
                                                                    echo "<option value='$cid'>$variation - $title </option>";
                                                                }
                                                                ?>
                                                            </select></td>
                                                        <td><input value="" class="form-control" name="v_stock[]"
                                                                placeholder="<?php echo $this->lang->line('Stock Units') ?>*">
                                                        </td>
                                                        <td><input value="" class="form-control" name="v_alert[]"
                                                                placeholder="<?php echo $this->lang->line('Alert Quantity') ?>*">
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-default tr_delete"><i class="fa fa-trash"></i></button>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                    if (isset($product_var)) {
                                                        foreach ($product_var as $p_var) {
                                                            echo '<tr> <td>' . $p_var['product_name'] . '</td> <td>' . $p_var['qty'] . '<td><td><a href="' . base_url() . 'products/edit?id=' . $p_var['pid'] . '"  class="btn btn-purple btn-sm"><span class="fa fa-edit"></span>' . $this->lang->line('Edit') . '</a><td></tr>';
                                                        }
                                                    }
                                                    ?>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div id="accordionWrapa2" role="tablist" aria-multiselectable="true">

                                    <div id="coupon5" class="card-header">
                                        <a data-toggle="collapse" data-parent="#accordionWrapa2" href="#accordion42"
                                        aria-expanded="true" aria-controls="accordion41"
                                        class="card-title lead collapsed"><i class="fa fa-plus-circle"></i>
                                            <?php echo $this->lang->line('Add') . ' ' . $this->lang->line('Products') . ' ' . $this->lang->line('Warehouse') ?>
                                        </a>
                                    </div>
                                    <div id="accordion42" role="tabpanel" aria-labelledby="coupon5"
                                        class="card-collapse collapse" aria-expanded="false" style="height: 0px;">
                                        <div class="row p-1">
                                            <div class="col">
                                                <button class="btn btn-blue tr_clone_add_w">Add Row</button>
                                                <hr>
                                                <table class="table" id="w_var">
                                                    <tr>
                                                        <td>
                                                            <select name="w_type[]" class="form-control">
                                                                <?php
                                                                foreach ($warehouse as $row) {
                                                                    $cid = $row['id'];
                                                                    $title = $row['title'];
                                                                    echo "<option value='$cid'>$title</option>";
                                                                }
                                                                ?>
                                                            </select></td>
                                                        <td><input value="" class="form-control" name="w_stock[]"
                                                                placeholder="<?php echo $this->lang->line('Stock Units') ?>*">
                                                        </td>
                                                        <td><input value="" class="form-control" name="w_alert[]"
                                                                placeholder="<?php echo $this->lang->line('Alert Quantity') ?>*">
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-default tr_delete"><i class="fa fa-trash"></i></button>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                    if (isset($product_ware)) {
                                                        foreach ($product_ware as $p_var) {
                                                            echo '<tr> <td>' . $p_var['product_name'] . '</td> <td>' . $p_var['qty'] . '<td><td><a href="' . base_url() . 'products/edit?id=' . $p_var['pid'] . '"  class="btn btn-purple btn-sm"><span class="fa fa-edit"></span>' . $this->lang->line('Edit') . '</a><td></tr>';
                                                        }
                                                    }
                                                    ?>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                </div> -->
                                <?php
                            }
                            ?>                        
                        </div>

                        <div class="tab-pane" id="tab2" role="tabpanel" aria-labelledby="base-tab2">
                             <div class="form-group row">

                                <div class="col-sm-3 mb-2">                                
                                    <label class="col-form-label" for="price_unit"><b><?php echo $this->lang->line('Base Unit') ?><span class="compulsoryfld">*</span></b></label><br>
                                    <select name="price_unit" id="price_unit" class="form-control  data-original-value="<?php echo $productai['price_unit'] ?>" required">
                                        <option value=''>Select Unit</option>
                                        <?php
                                        foreach ($units as $row) {
                                            $cid = $row['code'];
                                            $title = $row['name'];
                                            $ticked= "";
                                            if($productai['price_unit']==$title){
                                                $ticked= "selected";
                                            }
                                            echo "<option value='$title' $ticked>$title</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <!-- ============================================ -->
                                <div class="col-sm-2 pricecheckdiv">
                                    <label class="col-form-label mb-2" ></label>                                
                                    <div class="form-check">
                                        <input class="form-check-input col-form-label" type="checkbox" value="0" id="kgQuantityCheck" name="kgQuantityCheck" style="margin-top:14px;">
                                        <label class="form-check-label col-form-label" for="kgQuantityCheck">
                                            Pieces/Kg ?
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-2 d-none" id="kg_quantitydiv"><label class="col-form-label" for="pieces_per_kg"><?php echo $this->lang->line('Pieces Per Kg'); ?></label>                                
                                    <input type="number" placeholder="kg quantity"   class="form-control" id="pieces_per_kg" name="pieces_per_kg"  value="<?php echo $productai['pieces_per_kg']; ?>" data-original-value="<?php echo $productai['pieces_per_kg']; ?>">
                                </div>
                                <div class="col-sm-2">
                                    <label class="col-form-label" for="unit_weight"><?php echo $this->lang->line('Unit Weight');  ?></label>                                
                                    <input type="number" placeholder="Unit weight"   class="form-control" id="unit_weight" name="unit_weight" value="<?php echo $productai['unit_weight']; ?>" data-original-value="<?php echo $productai['unit_weight']; ?>">
                                </div>
                                <div class="col-sm-2">
                                    <label class="col-form-label" for="standard_pack"><?php echo $this->lang->line('Standard Pack');  ?></label>                                
                                    <input type="number" placeholder="Standard Pack"   class="form-control required" id="standard_pack" name="standard_pack" value="<?php echo $productai['standard_pack']; ?>" data-original-value="<?php echo $productai['standard_pack']; ?>">
                                </div>
                                <!-- ============================================ -->
                            </div>

                            <div class="form-group row">
                                <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                    <label class="col-form-label" for="product_cost"><?php echo  $this->lang->line('Cost') ?> (<i><?php echo  $this->lang->line('Based on Latest Purchase Price') ?></i>)<span class="compulsoryfld">*</span></label>
                                    <div class="input-group">
                                    
                                        <input type="number" name="product_cost" id="product_cost" class="form-control required"  placeholder="0.00" aria-describedby="sizing-addon1"  onkeypress="return isNumber(event)" value="<?php echo numberClean($productai['item_cost']) ?>" data-original-value="<?php echo edit_amountExchange_s($productai['item_cost'], 0, $this->aauth->get_user()->loc) ?>">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                    <label class="col-form-label" for="weighted_average_cost"><?php echo  $this->lang->line('Weighted Average Cost') ?></i></label>
                                    <div class="row">
                                        <div class="col-4">
                                            <input type="number" name="weighted_average_cost" id="weighted_average_cost" class="form-control"  readonly value="<?php echo $productai['weighted_average_cost']; ?>">
                                        </div>
                                        <div class="col-8">
                                            <button class="btn btn-primary weighted_average_cost btn-crud"><?php echo  $this->lang->line('Update FIFO Weighted Average Cost') ?></button>
                                        </div>
                                        
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label" for="weighted_average_cost"><?php echo  $this->lang->line('Weighted Average Cost1') ?></i></label>
                                    <div class="row mt-13">
                                        <div class="col-8">
                                            <button class="btn btn-primary btn-crud suggested_price_btn"><?php echo  $this->lang->line('Suggested Price') ?></button>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                    <label class="col-form-label" for="wholesale_price"><?php echo $this->lang->line('Wholesale Price') ." (".$whole_price_perc ?>)% of Cost</label>
                                    <div class="input-group">
                                        <input type="text" name="wholesale_price" id="wholesale_price" class="form-control required"   placeholder="0.00" aria-describedby="sizing-addon"  onkeypress="return isNumber(event)" value="<?php echo $productai['wholesale_price']; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label" for="product_price"><?php echo $this->lang->line('Selling Price')." (".$selling_price_perc ?>)% of Cost</label>
                                    <div class="input-group">
                                        <input type="text" name="product_price" id="product_price" class="form-control required"   placeholder="0.00" aria-describedby="sizing-addon"  onkeypress="return isNumber(event)" value="<?php echo $product['product_price'] ?>" >
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                    <label class="col-form-label" for="web_price"><?php echo 'Product Web Price'." (".$web_price_perc; ?>)% of Cost</label>
                                    <div class="input-group">
                                        <!-- <span class="input-group-addon"><?php //echo $this->config->item('currency') ?></span> -->
                                        <input type="text" name="web_price" id="web_price" class="form-control required"  placeholder="0.00" aria-describedby="sizing-addon"  onkeypress="return isNumber(event)" value="<?php echo $productai['web_price']; ?>" >
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                    <label class="col-form-label" for="min_price"><?php echo 'Minimum Price('.$min_price_prec.'% of Cost)'; ?></label>
                                    <div class="input-group">
                                        <!-- <span class="input-group-addon"><?php //echo $this->config->item('currency') ?></span> -->
                                        <input type="text" name="min_price" id="min_price" class="form-control"  placeholder="0.00" aria-describedby="sizing-addon"  onkeypress="return isNumber(event)" value="<?php echo $productai['min_price']; ?>">
                                    </div>
                                    <input type="hidden" name="whole_price_perc" id="whole_price_perc" value="<?=$whole_price_perc?>">
                                    <input type="hidden" name="selling_price_perc" id="selling_price_perc" value="<?=$selling_price_perc?>">
                                    <input type="hidden" name="web_price_perc" id="web_price_perc" value="<?=$web_price_perc?>">
                                    <input type="hidden" name="min_price_prec" id="min_price_prec" value="<?=$min_price_prec?>">
                                </div>
                                
                                <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                    <label class="col-form-label" for="max_disrate"><?php echo $this->lang->line('Maximum Discount Rate');?><span class="compulsoryfld">*</span></label>
                                    <div class="input-group">                                    
                                        <div class="row">
                                            <div class="col-3">
                                                <div class="input-group">
                                                    <input type="number" name="max_disrate" id="max_disrate" min ="0"  class="form-control required" placeholder="<?php echo '%' ?>" value="<?php echo amountFormat_general($productai['max_disrate']) ?>" data-original-value="<?php echo amountFormat_general($productai['max_disrate']) ?>" onkeypress="return isNumber(event)" onkeyup="checkMaxDiscountRate()"><span
                                                    class="input-group-addon">%</span>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <span> Max Rate : <strong id="maxdisrate"></strong></span>
                                            </div>
                                            <div class="col-5">
                                                <span> Price : <strong id="maxdisval"></strong></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                           
                                <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                    <label class="col-form-label" for="product_disc"><?php echo $this->lang->line('Default Discount Rate') ?></label>
                                    <div class="input-group"> 
                                        <div class="row">
                                            <div class="col-3">
                                                <div class="input-group">
                                                    <input type="number" name="product_disc" id="product_disc" class="form-control"  min ="0"
                                                    placeholder="<?php echo '%'; ?>"
                                                    aria-describedby="sizing-addon1" value="<?php echo amountFormat_general($product['disrate']) ?>" data-original-value="<?php echo amountFormat_general($product['disrate']) ?>" onkeypress="return isNumber(event)" onkeyup="checkDiscountRate()"><span class="input-group-addon">%</span>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <span> Def. Max : <strong id="defmaxdisrate"></strong></span>
                                            </div>
                                            <div class="col-5"><span> Price : <strong id="defaultdisval"></strong></span></div>
                                        </div>
                                        <!-- <small><?php //echo $this->lang->line('Discount rate during') ?></small> -->
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        
                        <div class="tab-pane" id="tab3" role="tabpanel" aria-labelledby="base-tab3">
                            <div class="form-group row ">

                                <div class="col-lg-3 mb-2">
                                    <h6><b>Stock(On Hand) : <span class="productStockQty"><?php echo  $product['qty']; ?></span></b></h6>
                                </div>
                                <div class="col-lg-2">
                                    <h6><b><?php echo $this->lang->line('Alert Quantity') ?> : <span class="productAlertQty"><?php echo $product['alert']; ?></span></b></h6>
                                </div>

                                <div class="col-lg-3">
                                    <h6><b>Customer Order : <span class="productAlertQty"><?php echo $sales_qty; ?></span></b></h6>
                                </div>
                                <div class="col-lg-2">
                                    <h6><b>Purchase Order : <span class="productAlertQty"><?php echo $purchase_qty; ?></span></b></h6>
                                </div>
                                <div class="col-lg-2">
                                    <h6><b>IN Transist : <span class="productTransist"></span></b></h6>
                                </div>

                                <!-- @erp2024 newly added for price calculations 18-10-2024 -->
                                <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                    <label class="col-form-label" for="product_name"><?php echo $this->lang->line('Total Stock Quantity') ?><span class="compulsoryfld">*</span></label>
                                    <input type="text" placeholder="<?php echo $this->lang->line('Total Stock Quantity') ?>*"
                                    class="form-control margin-bottom" name="product_qty"  id="product_qty" readonly value="<?php echo $product['qty'] ?>">
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                    <label class="col-form-label" for="product_qty_alert"><?php echo $this->lang->line('Alert Quantity(Default)') ?><span class="compulsoryfld">*</span></label>
                                    <input type="text" placeholder="<?php echo $this->lang->line('Alert Quantity') ?>"
                                    class="form-control margin-bottom" name="product_qty_alert" id="product_qty_alert" value="<?php echo $product['alert'] ?>" data-original-value="<?php echo $product['alert'] ?>">
                                </div>

                                <div class="col-lg-4">
                                    <div class="border-with-padding">
                                        <label class="col-form-label" for="product quantity"><b><?php echo $this->lang->line('Update Product Quantity') ?></b></label>
                                        <br>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" title="Product Quantity Increased" name="inlineRadioOptions" id="inlineRadio1" value="plus"  data-original-value="plus" checked>
                                            <label class="form-check-label" for="inlineRadio1">Plus</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" title="Product Quantity Decreased" name="inlineRadioOptions" id="inlineRadio2" value="minus"  data-original-value="minus">
                                            <label class="form-check-label" for="inlineRadio2" >Minus</label>
                                        </div>
                                        <span class="form-check form-check-inline">
                                            <input class="form-control" type="number" name="quantity_to_update" id="quantity_to_update" placeholder="Quantity">
                                            
                                        </span>
                                        <span class="form-check form-check-inline">
                                            <button class="btn btn-primary" id="product_quantity_update_btn">Update</button>
                                            
                                        </span>
                                    </div>
                                </div>
                                <!-- @erp2024 newly added for price calculations 18-10-2024 -->
                                
                                <!-- <label class="col-sm-2 col-form-label" for="unit"><b><?php echo $this->lang->line('Base Unit') ?>*</b></label> -->

                                <!-- <div class="col-sm-4 mb-2 d-none">
                                    <select name="unit" id="unit" class="form-control">
                                        <?php     
                                        $unit = $product['unit'];                          
                                        // foreach ($units as $row) {
                                        //     $cid = $row['code'];
                                        //     $title = $row['name'];
                                        //     $sel ="";
                                        //     if($cid == $unit){
                                        //         $sel = "selected";
                                        //     }
                                        //     echo "<option value='$title' $sel>$title</option>";
                                        // }
                                        ?>
                                    </select>
                                </div> -->
                                <div class="col-lg-10 mb-2"></div>
                                <?php
                                
                                $totalwarehouse = count($warehouse);
                                $j = 1;
                                echo '<input type="hidden" class="form-control mb-2" name="warehousecount" id="warehousecount" value="'.$totalwarehouse.'">';
                                echo '<div class="col-md-3">';
                                echo '<b>Shop/Warehouse</b>';
                                echo '</div>';
                                echo '<div class="col-md-2">';
                                echo '<b>Unit</b>';
                                echo '</div>';
                                echo '<div class="col-md-2">';
                                echo '<b>Stock Quantity</b>';
                                echo '</div>';
                                echo '<div class="col-md-2">';
                                echo '<b>Alert Quantity</b>';
                                echo '</div>';
                                echo '<div class="col-md-3">';
                                echo '<b>Action</b>';
                                echo '</div>';
                                echo '<div class="col-md-12" style="height:1px;  margin-bottom:20px;"></div>';
                                foreach ($warehouse as $row) {
                                    $cid = $row['id'];
                                    $title = $row['title']; 
                                    $stocksQty = "";
                                    $alertsQty = "";
                                    $flg = 0;
                                    foreach ($productwise_warehouse as $whproduct) {
                                        if ($cid == $whproduct['store_id']) {
                                            $stocksQty = $whproduct['stock_qty'];
                                            $alertsQty = $whproduct['alert_qty'];
                                            $flg=1;
                                        }
                                    }            

                                    echo '<input type="hidden" class="form-control mb-2" name="'.$cid.'" value="'.$cid.'">';
                                    echo '<div class="col-md-3">';
                                    echo '<input type="text" class="form-control mb-2" name="warehouse_name_'.$cid.'" value="'.$title.'" readonly>';
                                    echo '</div>';
                                    echo '<div class="col-md-2">';
                                    echo '<input type="text" class="form-control selectedunit"  placeholder="'. $this->lang->line('Select base unit from the dropdown').'*" readonly value="'.$product['unit'].'">';
                                    echo '</div>';
                                    echo '<div class="col-md-2">';
                                    echo '<input type="number" class="form-control stock_qty_'.$cid.' stock_qty" name="stock_qty_'.$cid.'" id="stock_qty_'.$j.'"  placeholder="'. $this->lang->line('Stock Quantity').'*" onkeyup="stockstowarehouse('.$cid.')" title="'.$title.'- Stock Quantity" value="'.$stocksQty.'" data-original-value="'.$stocksQty.'" data-id="'.$flg.'">';
                                    echo '</div>';
                                    echo '<div class="col-md-2">';
                                    echo '<input type="number" class="form-control alert_qty_'.$cid.' alert_qty" name="alert_qty_'.$cid.'" id="alert_qty_'.$j.'" placeholder="'. $this->lang->line('Alert Quantity').'*" onkeyup="stockalerttowarehouse('.$cid.')" title="'.$title.'- Alert Quantity" value="'.$alertsQty.'" data-original-value="'.$alertsQty.'">';
                                    echo '</div>';
                                    echo '<div class="col-md-3">';
                                    echo '<button class="btn btn-default tr_delete" onclick="prdinventorydel('.$cid.')"><i class="fa fa-trash"></i></button>';
                                    echo '</div>';
                                    $j++;
                                }
                                ?>
                            </div>
                        </div>

                        <!-- ============================Accounting============================ -->
                         
                <!-- =================== Accounting tab ======================= -->
                <div class="tab-pane" id="tab8" role="tabpanel" aria-labelledby="base-tab8">
                    <div class="form-group row ">
                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label" for="income_account_number"><?php echo $this->lang->line('Income Account') ?>
                                <span class="compulsoryfld">*</span></label>
                                <select name="income_account_number" id="income_account_number" class="form-control"  data-original-value="<?php echo $productai['income_account_number']; ?>" required >
                                <?php
                                    if ($incomeaccounts)
                                    {
                                        echo "<option value=''>" . $this->lang->line('Select Account') . "</option>";
                                        foreach ($incomeaccounts as $row2) {
                                            $transcat_id1 = $row2['acn'];
                                            $sel = ($transcat_id1 == $productai['income_account_number']) ? "selected" : "";
                                            echo "<option value='$transcat_id1' $sel>".$row2['acn']." - ".$row2['holder']."</option>";
                                        }
                                    }                            
                                ?>
                                </select>                            
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label" for="expense_account_number"><?php echo $this->lang->line('Expense Account'); ?>
                                <span class="compulsoryfld">*</span></label>
                                <select name="expense_account_number" id="expense_account_number" class="form-control required"  data-original-value="<?php echo $productai['expense_account_number']; ?>" required>
                                <?php
                                    if ($expenseaccounts)
                                    {
                                        echo "<option value=''>" . $this->lang->line('Select Account') . "</option>";
                                        foreach ($expenseaccounts as $row3) {
                                            $transcat_id = $row3['acn'];
                                           
                                            $sel1 = ($transcat_id == $productai['expense_account_number']) ? "selected" : "";
                                            
                                            echo "<option value='$transcat_id' $sel1>".$row3['acn']." - ".$row3['holder']."</option>";
                                        }
                                    }                            
                                ?>
                                </select>                            
                            </div>
                            
                    </div>
                </div>
                        <!-- =================== location tab ======================= -->
                        <div class="tab-pane" id="tab6" role="tabpanel" aria-labelledby="base-tab6">
                            <div class="form-group row ">
                                    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label" for="aisels"><?php echo $this->lang->line('Aisel') ?></label>
                                        <input type="text" placeholder="Aisel" class="form-control margin-bottom" name="aisel" id="aisels" value="<?php echo $productai['aisel']; ?>" data-original-value="<?php echo $productai['aisel']; ?>">
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label" for="rack_no"><?php echo $this->lang->line('Rack No.') ?></label>
                                        <input type="text" placeholder="Rack No." class="form-control margin-bottom" name="rack_no" id="rack_no" value="<?php echo $productai['rack_no']; ?>" data-original-value="<?php echo $productai['rack_no']; ?>">
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label" for="shelf"><?php echo $this->lang->line('Shelf') ?></label>
                                        <input type="text" placeholder="Shelf" class="form-control margin-bottom " name="shelf" id="shelf" value="<?php echo $productai['shelf']; ?>" data-original-value="<?php echo $productai['shelf']; ?>">
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label" for="bin"><?php echo $this->lang->line('Bin') ?></label>
                                        <input type="text" placeholder="Bin" class="form-control margin-bottom" name="bin" id="bin" value="<?php echo $productai['bin']; ?>" data-original-value="<?php echo $productai['bin']; ?>">
                                    </div>                     
                            </div>
                        </div>
                        <!-- =================== location tab ======================= -->
                        <!-- =================== Additional Details tab ======================= -->
                        <div class="tab-pane" id="tab7" role="tabpanel" aria-labelledby="base-tab">
                            <div class="form-group row ">
                                    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label" for="date_avaialble"><?php echo $this->lang->line('Date Available') ?></label>
                                        <input type="date" placeholder="Date Available" class="form-control margin-bottom"  value="<?php echo $productai['date_avaialble']; ?>" data-original-value="<?php echo $productai['date_avaialble']; ?>" name="date_avaialble" id="date_avaialble">
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label" for="wdate"><?php echo $this->lang->line('Expiry Date'); ?></label>
                                        <input type="date" class="form-control" placeholder="Expiry Date" id="wdate" name="wdate" autocomplete="false" value="<?= $product['expiry'] ?>" data-original-value="<?= $product['expiry'] ?>">                       
                                        <small>Do not change if not applicable</small>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label" for="aisel"><?php echo $this->lang->line('Dimensions (L x W x H)') ?></label>
                                        <div class="row">
                                            <div class="col-4">
                                                <input type="text" placeholder="Length" class="form-control margin-bottom" name="prd_length" title="prd_length" id="prd_length" value="<?php echo $productai['prd_length']; ?>" data-original-value="<?php echo $productai['prd_length']; ?>">
                                            </div>
                                            <div class="col-4 row">
                                                <input type="text" placeholder="Width" class="form-control margin-bottom" name="prd_width" title="prd_width" id="prd_width" value="<?php echo $productai['prd_width']; ?>" data-original-value="<?php echo $productai['prd_width']; ?>">
                                            </div>
                                            <div class="col-4">
                                                <input type="text" placeholder="Height" class="form-control margin-bottom" name="prd_height" title="prd_height" id="prd_height" value="<?php echo $productai['prd_height']; ?>" data-original-value="<?php echo $productai['prd_height']; ?>">
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label" for="length_class"><?php echo $this->lang->line('Length Class') ?></label><br>
                                        <select name="length_class" id="length_class" class="form-control wid100per" data-original-value="<?php echo $productai['length_class'] ?>">
                                            <option value=""><?php echo $this->lang->line('Select Class') ?></option>
                                            <option value="Centimeter" <?php if($productai['length_class']=='Centimeter'){ echo 'selected';} ?>>Centimeter</option>
                                            <option value="Millimeter" <?php if($productai['length_class']=='Millimeter'){ echo 'selected';} ?>>Millimeter</option>
                                            <option value="Inch" <?php if($productai['length_class']=='Inch'){ echo 'selected';} ?>>Inch</option>                               
                                        </select>
                                    </div>
                                
                            </div>
                        </div>
                        <!-- =================== location tab ======================= -->

                        <div class="tab-pane resresponse" id="tab11" role="tabpanel" aria-labelledby="base-tab11"></div>
                        <div class="tab-pane warehouseres" id="tab22" role="tabpanel" aria-labelledby="base-tab22"></div>
                        <div class="tab-pane" id="tab23" role="tabpanel" aria-labelledby="base-tab23">
                            <table id="avgcosting" class="table table-striped table-bordered zero-configuration dataTable w-100">
                                <thead>
                                <tr>
                                    <th class="text-center"><?php echo $this->lang->line('No') ?></th>                        
                                    <th><?php echo $this->lang->line('Date') ?></th>
                                    <th><?php echo $this->lang->line('Product') ?></th>
                                    <th><?php echo $this->lang->line('Type') ?></th>
                                    <th class="text-center"><?php echo $this->lang->line('Quantity') ?></th>
                                    <th class="text-center"><?php echo $this->lang->line('Onhand Quantity') ?></th>
                                    <th class="text-right"><?php echo $this->lang->line('Cost') ?></th>
                                    <th class="text-right"><?php echo $this->lang->line('Average Cost') ?></th>
                                    <th class="text-right"><?php echo $this->lang->line('Inventory Value') ?></th>
                                    <th><?php echo $this->lang->line('Added By') ?></th>
                                </tr>
                                </thead>
                                <tbody>                                
                                </tbody>
                            </table>
                        </div>
                        <hr>
                                
                        <div class="form-group row">
                            <input type="hidden" name="image" id="image" value="<?php echo $product['image'] ?>">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 submit-section text-right">
                                <input type="submit" id="edit_product_btn" class="btn btn-crud btn-primary btn-lg margin-bottom"
                                    value="<?php echo $this->lang->line('Update') ?>"
                                    data-loading-text="Updating...">
                                <input type="hidden" value="products/editproduct" id="action-url">
                            </div>
                        </div>
                        
                    </div>

                </form>
            </div>
        </div>
    </div>
  <!-- =================================History section=========================== -->
    <!-- <button class="history-expand-button">
    <span>History</span>
    </button>

    <div class="history-container">
        <button class="history-close-button">
            <span>Close</span>
        </button>
        <h2>History</h2>
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#home">Product Log</a></li>
            <li><a data-toggle="tab" href="#menu1">Inventory Log</a></li>
        
        </ul>

    <div class="tab-content">
        <div id="home" class="tab-pane fade in active">
        <h3>Product Log</h3>
        <table id="logtable" class="table table-striped table-bordered zero-configuration" style="width:65%;">
                            <thead>
                            <tr>       
                                <th><?php echo "#" ?></th>
                                <th><?php echo $this->lang->line('Action_performed') ?></th>  
                                <th><?php echo $this->lang->line('IP address')?></th>
                                <th><?php echo $this->lang->line('Performed By') ?></th>
                                <th><?php echo $this->lang->line('Performed At')?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i = 1;
                            foreach ($log as $row) { ?>
                            <tr>    
                                <td><?php echo $i?></td>
                                <td><?php echo $row['action_performed']?></td>
                                <td><?php echo $row['ip_address']?></td>
                                <td><?php echo $row['name']?></td>
                                <td><?php echo date('d-m-Y H:i:s', strtotime($row['performed_dt'])); ?></td>
                            </tr>
                    
                    <?php $i++; } ?>
                            </tbody>
                            </table>>
        </div>
        <div id="menu1" class="tab-pane fade">
        <h3>Inventory Log</h3>
        <table id="logtable1" class="table table-striped table-bordered zero-configuration" style="width:75%;">
                            <thead>
                            <tr>       
                                <th><?php echo "#" ?></th>
                                <th><?php echo $this->lang->line('Performed By') ?></th>
                                <th><?php echo $this->lang->line('Old quantity')?></th>
                                <th><?php echo $this->lang->line('New quantity')?></th>
                                <th><?php echo $this->lang->line('Note') ?></th>
                                <th><?php echo $this->lang->line('Performed At')?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i = 1;
                            foreach ($inv_log as $row1) { ?>
                            <tr>    
                                <td><?php echo $i?></td>
                                <td><?php echo $row1['name']?></td>
                                <td><?php echo $row1['old_qty']?></td>
                                <td><?php echo $row1['new_qty']?></td>
                                <td><?php echo $row1['note']?></td>
                                <td><?php echo date('d-m-Y H:i:s', strtotime($row['performed_dt'])); ?></td>
                            </tr>
                    
                    <?php $i++; } ?>
                            </tbody>
                            </table>
        </div>
    
    </div>
                                
  </div> -->
    

<script src="<?php echo assets_url('assets/myjs/jquery.ui.widget.js'); $invoice['tid'] = 0; ?>"></script>
<script src="<?php echo assets_url('assets/myjs/jquery.fileupload.js') ?>"></script>
<script>
    const changedFields = {};
    // $("#product_cat").select2();
    $("#product_cat").select2({
        placeholder: "Type Category Name", 
        allowClear: true
    });
    $("#brand_id").select2({
        placeholder: "Selcet Brand", 
        allowClear: true
    });
    $("#manufacturer_id").select2();
    $("#made_in").select2();
    $("#product_warehouse").select2();
    $("#unit").select2();
    $("#prefered_vendor").select2();
    /*jslint unparam: true */
    /*global window, $ */
    $(function () {
        'use strict';
        // Change this to the location of your server-side upload handler:
        var url = '<?php echo base_url() ?>products/file_handling?id=<?php echo $invoice['tid'] ?>';
        $('#fileupload').fileupload({
            url: url,
            dataType: 'json',
            formData: {'<?=$this->security->get_csrf_token_name()?>': crsf_hash},
            done: function (e, data) {
                var img = 'default.png';
                $.each(data.result.files, function (index, file) {
                    $('#files').html('<tr><td><a data-url="<?php echo base_url() ?>products/file_handling?op=delete&name=' + file.name + '&invoice=<?php echo $invoice['tid'] ?>" class="aj_delete"><i class="btn-danger btn-sm icon-trash-a"></i> ' + file.name + ' </a><img style="max-height:200px;" src="<?php echo base_url() ?>userfiles/product/' + file.name + '"></td></tr>');
                    img = file.name;
                });

                $('#image').val(img);
            },
            progressall: function (e, data) {
                var progress = parseInt(data.loaded / data.total * 100, 10);
                $('#progress .progress-bar').css(
                    'width',
                    progress + '%'
                );
            }
        }).prop('disabled', !$.support.fileInput)
            .parent().addClass($.support.fileInput ? undefined : 'disabled');
    });

    $(document).on('click', ".aj_delete", function (e) {
        e.preventDefault();

        var aurl = $(this).attr('data-url');
        var obj = $(this);

        jQuery.ajax({

            url: aurl,
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                obj.closest('tr').remove();
                obj.remove();
            }
        });

    });

    $("#sub_cat").select2();
    

    $("#product_cat").on('change', function () {
        // Get the currently selected categories
        var selectedCategories = $('#product_cat').val();

        // Reinitialize the subcategory dropdown with select2
        $("#sub_cat").select2({
            allowClear: true,
            ajax: {
                url: baseurl + 'products/sub_cat?id=' + selectedCategories.join(','),
                dataType: 'json',
                type: 'POST',
                quietMillis: 50,
                data: function (product) {
                    return {
                        product: product,
                        '<?=$this->security->get_csrf_token_name()?>': crsf_hash
                    };
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.title,
                                id: item.id
                            };
                        })
                    };
                },
            }
        });

        // Get the currently selected subcategories
        var subCatSelected = $("#sub_cat").val() || [];

        // If no categories are selected, clear subcategories
        if (selectedCategories.length === 0) {
            $("#sub_cat").val([]).trigger('change');
            return;
        }

        // Fetch the valid subcategories based on the selected categories
        $.ajax({
            url: baseurl + 'products/sub_cat?id=' + selectedCategories.join(','),
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                var validSubCategories = [];

                // Collect all valid subcategory IDs from the response
                $.each(response, function (index, subCat) {
                    validSubCategories.push(subCat.id);
                });

                // Filter the currently selected subcategories
                var updatedSubCat = subCatSelected.filter(function (subCatId) {
                    // Keep subcategories that are valid for the remaining selected categories
                    return validSubCategories.includes(subCatId);
                });

                // Update the subcategory select with the valid ones
                $("#sub_cat").val(updatedSubCat).trigger('change');
            }
        });
    });



    $(document).on('click', ".tr_clone_add", function (e) {
        e.preventDefault();
        var n_row = $('#v_var').find('tbody').find("tr:last").clone();

        $('#v_var').find('tbody').find("tr:last").after(n_row);

    });
    $(document).on('click', ".tr_clone_add_w", function (e) {
        e.preventDefault();
        var n_row = $('#w_var').find('tbody').find("tr:last").clone();

        $('#w_var').find('tbody').find("tr:last").after(n_row);

    });
    $(document).on('click', ".tr_delete", function (e) {
        e.preventDefault();
        $(this).closest('tr').remove();
    });
    $(document).on('click', ".v_delete_serial", function (e) {
        e.preventDefault();
        $(this).closest('div .serial').remove();
    });

    $(document).on('click', ".add_serial", function (e) {
        e.preventDefault();

        $('#added_product').append('<div class="form-group serial"><label for="field_s" class="col-lg-2 control-label"><?= $this->lang->line('serial') ?></label><div class="col-lg-10"><input class="form-control box-size" placeholder="<?= $this->lang->line('serial') ?>" name="product_serial[]" type="text"  value=""></div><button class="btn-sm btn-purple v_delete_serial m-1 align-content-end"><i class="fa fa-trash"></i> </button></div>');

    });

    $('.editdate2').datepicker({
        autoHide: true,
        format: '<?php echo $this->config->item('dformat2'); ?>'
    });

    // erp2024 newly added functions
    function prdinventorydel(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to delete this product inventory record?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                let stock_qty = $(".stock_qty_" + id).val();
                let flg = $(".stock_qty_" + id).data("id");
                let alert_qty = $(".alert_qty_" + id).val();            
                let product_qty = $("#product_qty").val();            
                let product_qty_alert = $("#product_qty_alert").val();  

                product_qty = product_qty - stock_qty;
                product_qty_alert = product_qty_alert - alert_qty;

                let productID = $("#pid").val();
                $(".stock_qty_" + id).val("");
                $(".alert_qty_" + id).val("");           

                stockalerttowarehouse(id);
                stockstowarehouse(id);

                if (flg == 1) {
                    $.ajax({
                        url: baseurl + 'Products/delstockfromwarehouse',
                        dataType: 'json',
                        type: 'POST',
                        data: {
                            'productID': productID,
                            'warehouseID': id,
                            'stock_qty': stock_qty,
                            'alert_qty': alert_qty,
                            'product_qty': product_qty,
                            'product_qty_alert': product_qty_alert
                        },
                        success: function (data) {
                            Swal.fire(
                                'Deleted!',
                                'Product inventory record has been deleted.',
                                'success'
                            );
                        },
                        error: function () {
                            Swal.fire(
                                'Error!',
                                'There was a problem deleting the record.',
                                'error'
                            );
                        }
                    });
                }
            }
        });
    }

    function stockstowarehouse(id) {
        counts = $("#warehousecount").val();
        var stockQty = 0.0; // Initialize with a decimal value for parseFloat()
        for (var i = 1; i <= counts; i++) {
            var singleStock = $("#stock_qty_" + i).val();
            if (singleStock !== undefined && !isNaN(parseFloat(singleStock))) {
            stockQty += parseFloat(singleStock);
            }
        }
        $("#product_qty").val(stockQty);
        $(".productStockQty").text(stockQty);

    }
    function stockalerttowarehouse(id){
        countalert = $("#warehousecount").val();
        var alertQty = 0.0; // Initialize with a decimal value for parseFloat()
        for (var j = 1; j <= countalert; j++) {
            var alertStock = $("#alert_qty_" + j).val();
            if (alertStock !== undefined && !isNaN(parseFloat(alertStock))) {
                alertQty = parseFloat(alertQty) + parseFloat(alertStock);
            }
        }
        $("#product_qty_alert").val(alertQty);
        $(".productAlertQty").text(alertQty);
    }
    $("#unit").on('change', function(){
        // Get the selected value from the unit select box
        var unitValue = $(this).val();    
        // Check if the value is null or empty
        $(".stock_qty").val('');
        $(".alert_qty").val('');
        if (!unitValue) {
            // Add readonly attribute to stock_qty and alert_qty fields
            $(".stock_qty").attr('readonly', true);
            $(".alert_qty").attr('readonly', true);
            $(".selectedunit").val(unitValue);
        } else {
            // Remove readonly attribute from stock_qty and alert_qty fields
            $(".stock_qty").removeAttr('readonly');
            $(".alert_qty").removeAttr('readonly');
            $(".selectedunit").val(unitValue);
        }
    });
    $("#price_unit").on('change', function(){
        // Get the selected value from the unit select box
        var priceValue = $(this).val();    
        $('#unit').val(priceValue);
        $(".selectedunit").val(priceValue);
        // Check if the value is null or empty
        if (priceValue=="Each" || priceValue=="each") {
            $(".pricecheckdiv").show();
        }
        else{            
            $(".pricecheckdiv").hide();
            $("#kg_quantitydiv").hide();
            $('#kgQuantityCheck').val("0");
            $('#kgQuantityCheck').prop('checked', false);
        }
    });
    $("#product_cost").on("keyup",function(){
       var cost = parseFloat($("#product_cost").val());
        whole_price_perc = parseFloat($("#whole_price_perc").val());
        selling_price_perc = parseFloat($("#selling_price_perc").val());
        web_price_perc = parseFloat($("#web_price_perc").val());
        min_price_prec = parseFloat($("#min_price_prec").val());
        min_price1 = (((cost*min_price_prec)/100) + cost).toFixed(2);
        selling_price1 = (((cost*selling_price_perc)/100) + cost).toFixed(2);
        web_price1 = (((cost*web_price_perc)/100) + cost).toFixed(2);
        whole_price1 = (((cost*whole_price_perc)/100) + cost).toFixed(2);
        $("#wholesale_price").val(whole_price1)
        $("#product_price").val(selling_price1)
        $("#web_price").val(web_price1);
        $("#min_price").val(min_price1);
        var max_disrate = $('#max_disrate').val();
        var product_disc = $('#product_disc').val();
        allowedMAxdiscount();
        if (!isNaN(max_disrate) && max_disrate !== '') {
            checkMaxDiscountRate();
        }
        if (!isNaN(product_disc) && product_disc !== '') {
            checkDiscountRate();
        }
   });
    $(document).ready(function() {
        $("#income_account_number").select2({
            placeholder: "Type Account", 
            allowClear: true,
            width: '100%'
        });   
        $("#expense_account_number").select2({
            placeholder: "Type Account", 
            allowClear: true,
            width: '100%'
        });  

        $('select').each(function () {
            if (!$(this).attr('multiple')) {
                 const selectedLabel = $(this).find(':selected').text();
                $(this).attr('data-original-label',selectedLabel);
            } else {
            // For multi-select, get all selected options text and join them with a comma
            const selectedLabels = $(this)
                .find(':selected')
                .map(function () {
                return $(this).text();
                })
                .get()
                .join(', ');
            $(this).attr('data-original-label', selectedLabels);
            }
        });
        
        var max_disrate = $('#max_disrate').val();
        var product_disc = $('#product_disc').val();
        allowedMAxdiscountedit();
        if (!isNaN(max_disrate) && max_disrate !== '') {
            checkMaxDiscountRate();
        }
        if (!isNaN(product_disc) && product_disc !== '') {
            checkDiscountRate();
        }
        
        var priceunit = $("#price_unit").val();
        if(priceunit=='Each'){
            $('#kgQuantityCheck').val("1");
            $('#kg_quantitydiv').removeClass("d-none");
            $(".pricecheckdiv").show();
            $('#kgQuantityCheck').prop('checked', true);
        }
        else{
            $("#kg_quantitydiv").hide();
            $(".pricecheckdiv").hide();
            $('#kgQuantityCheck').val("0");
            $('#kg_quantitydiv').addClass("d-none");
            $('#kgQuantityCheck').prop('checked', false);
        }
        prid = $("#pid").val();
        $.ajax({
            url: baseurl + 'Products/locationwiseproducts',
            dataType: 'json',
            method: 'POST',
            data: {'prdid': prid},
            success: function(data) {
                resultdata = data.stocks;
                let responseHtml = '<table class="table table-bordered dataTable"><thead><tr><th>Name</th><th>Code</th><th>Base Unit</th><th>OnHand</th><th>Customer Order</th><th>Purchase Order</th><th>In Transist</th></tr></thead><tbody>';
                responseHtml += '<tr>';
                responseHtml += '<td>'+data.productname+'</td><td>'+data.productcode+'</td><td>'+data.baseunit+'</td></td><td>'+data.onhand+'</td><td>'+data.total_sales_quantity+'</td><td>'+data.total_purchse_quantity+'</td><td>0</td>';
                responseHtml += '</tr></tbody></table>';
                $(".resresponse").html(responseHtml);

                let onhandHtml = '<table class="table table-bordered dataTable"><thead><tr><th>Warehouse</th><th>Unit</th><th>Onhand Stock</th><th>Alert</th></tr></thead><tbody>';
                onhandHtml += '<tr>';
                if (resultdata.length > 0) {
                    $.each(resultdata, function(index, row) {
                        onhandHtml += '<tr>';                        
                        onhandHtml += '<td>' + row.title + '</td>';
                        onhandHtml += '<td>' + row.unit + '</td>';
                        onhandHtml += '<td>' + row.stock_qty + '</td>';
                        onhandHtml += '<td>' + row.alert_qty + '</td>';
                        onhandHtml += '</tr>';
                    });
                } else {
                    onhandHtml += '<tr><td colspan="3">No data available</td></tr>';
                }
        
                onhandHtml += '</tbody></table>';
                $(".warehouseres").html(onhandHtml);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error: ' + textStatus, errorThrown);
            }
        });
    });
    // erp2024 newly added functions ends
    $( document ).ready(function() {
    draw_data();
    $("#data_form").validate($.extend(true, {}, globalValidationOptions,{
        ignore: [], // Important: Do not ignore hidden fields (used by summernote)
        rules: {
            product_name: { required: true },
            product_code: { required: true },
            arabic_name: { required: true },
            'product_cat[]': { required: true },
            manufacturer_id: { required: true },
            manufacturer_partno: { required: true },
            price_unit: { required: true },
            product_desc: { required: true },
            product_cost: { required: true },
            max_disrate: { required: true },
            product_qty_alert: { required: true },
        },
        messages: {
            product_name: "Enter Product Name",
            product_code: "Enter Item Code",
            arabic_name: "Enter Arabic Name",
            'product_cat[]': "Select at least one Category",
            manufacturer_id : "Select at least one Manufacturer",
            manufacturer_partno:"Enter Manufacturer Part No.",
            price_unit:"Select Base unit",
            product_cost:"Enter cost amount",
            max_disrate:"Maximum discount rate",
            product_qty_alert:"Enter alert Quantity",
            product_desc:"Enter Product Description",
        }
    }));

});  
$("#edit_product_btn").on("click", function(e) {
    e.preventDefault();
    $('#edit_product_btn').prop('disabled', true);
    var product_qty = parseInt($("#product_qty").val());
    var product_qty_alert = parseInt($("#product_qty_alert").val());
    var totalqty_warhouses = 0;
    $('.stock_qty').each(function(index) {
        currentStockQty = parseInt($(this).val());
        if (!isNaN(currentStockQty) && currentStockQty > 0) {
            totalqty_warhouses = totalqty_warhouses + currentStockQty;
        } 
    });
    if ($("#data_form").valid()) {
        if(product_qty != totalqty_warhouses )
        {
            Swal.fire({
                icon: 'warning',
                title: 'Stock Mismatch',
                text: 'The total stock is not equal to the sum of all the warehouses stocks, Please cross check the products in the warehouse',
                confirmButtonText: 'OK',
                iconColor: '#f39c12',
            });
            $('#edit_product_btn').prop('disabled', false);
            return;
        }  
        //if(product_qty_alert > product_qty )
        //{
        //    Swal.fire({
        //        icon: 'warning',
        //        title: 'Check Alert Quantity',
        //        text: 'The Alert Quantity is greater than Total Stock Quantity',
        //        confirmButtonText: 'OK',
        //        iconColor: '#f39c12',
        //    });
        //    $('#edit_product_btn').prop('disabled', false);
        //    return;
        //}  
      Swal.fire({
            title: "Are you sure?",
            // text: "Are you sure you want to update inventory? Do you want to proceed?",
            "text":"Do you want to update product?",
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
               var form = $('#data_form')[0]; // Get the form element
               var formData = new FormData(form); // Create FormData object
               formData.append('changedFields', JSON.stringify(changedFields));
               $.ajax({
                  type: 'POST',
                  url: baseurl +'products/editproduct',
                  data: formData,
                    contentType: false, 
                    processData: false,
                  success: function(response) {
                     window.location.href = baseurl + 'products'; 
                  },
                  error: function(xhr, status, error) {
                        // Handle error
                        console.error(xhr.responseText);
                  }
               });
            }
            else{
               $('#edit_product_btn').prop('disabled', false);
            }
      });
    }
    else{
        $('.alert-dismissible').removeClass('d-none');
      $('#edit_product_btn').prop('disabled', false);
    }
});

$("#product_quantity_update_btn").on('click',function(e){
    e.preventDefault();
    var totalqty = 0;
    var checkedValue = $('input[name="inlineRadioOptions"]:checked').val();
    var quantity_to_update = parseInt($("#quantity_to_update").val());
    var onhand_stock = parseInt($("#product_qty").val());   
    if (quantity_to_update === null || isNaN(quantity_to_update) || quantity_to_update < 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Invalid Quantity',
            text: 'Please enter a valid value to update the product quantity.',
            confirmButtonText: 'OK',
            iconColor: '#f39c12',
        });
        return;
    }

    if(checkedValue=='plus')
    {
        totalqty = onhand_stock + quantity_to_update;
    }
    else{
        totalqty = onhand_stock - quantity_to_update;
        if(totalqty < 0) 
        {
            Swal.fire({
                icon: 'warning',
                title: 'Invalid Quantity',
                text: 'Please double-check the entered value. The total stock quantity should never be less than zero..',
                confirmButtonText: 'OK',
                iconColor: '#f39c12',
            });
            return;
        }      
        
    }
    // Swal.fire({
    // title: "Are you sure?",
    // text: "Do you want to update product quantity?",
    // icon: "warning",
    // showCancelButton: true,
    // confirmButtonText: "Yes, proceed",
    // cancelButtonText: "Cancel",
    // reverseButtons: true,
    // }).then((result) => {
    //     if (result.isConfirmed) {
            
    //         $("#product_qty").val(totalqty);    
    //     } else if (result.dismiss === Swal.DismissReason.cancel) {
            
    //     }
    // });
 
    changedFields[checkedValue] = {
        oldValue: onhand_stock,
        newValue: totalqty,
        fieldlabel : "Product Quantity - "+checkedValue
    };
    Swal.fire({
        title: "Are you sure?",
        text: "Do you want to update product quantity?",
        icon: "question",
        input: 'textarea',
        inputPlaceholder: 'Enter additional details here...if any',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, proceed!',
        cancelButtonText: "No - Cancel",
        reverseButtons: true,
        focusCancel: true
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: 'POST',
                url: baseurl + 'products/update_inventory_log',
                data: {
                    product_id: $("#pid").val(),
                    note: result.value,
                    new_qty: totalqty,
                    old_qty: onhand_stock,
                    'changedFields': JSON.stringify(changedFields)
                },
                dataType: 'json',
                success: function(response) {
                    $("#product_qty").val(totalqty);  
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }
        
    });

            

});


    $("#code_type").on("change", function()
    {
        $("#numeric_barcode").val("");
        const selectedOption = $(this).find(':selected');
        const originalValue = selectedOption.data('original-value');
        if($("#code_type").val()=="EAN13")
        {
            $("#barcode-terms").text("Maximum Allowed Digits - 12");
            $("#numeric_barcode").prop('maxLength', 12);
            $("#numeric_barcode").val(originalValue);
        }
        else{
            $("#barcode-terms").text("Maximum Allowed Digits - 11");
            $("#numeric_barcode").prop('maxLength', 11);
            $("#numeric_barcode").val(originalValue);
        }
    });

    $("#code_type2").on("change", function()
    {
        $("#numeric_barcode2").val("");
        const selectedOption = $(this).find(':selected');
        const originalValue = selectedOption.data('original-value');
        if($("#code_type2").val()=="EAN13")
        {
            $("#barcode-terms2").text("Maximum Allowed Digits - 12");
            $("#numeric_barcode2").prop('maxLength', 12);
            $("#numeric_barcode2").val(originalValue);
        }
        else{
            $("#barcode-terms2").text("Maximum Allowed Digits - 11");
            $("#numeric_barcode2").prop('maxLength', 11);
            $("#numeric_barcode2").val(originalValue);
        }
    });



    //Function for standard form fields.
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
                const originalLabel = this.getAttribute('data-original-label');

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


    //Function for select2 type dropdown
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



 
        var columnlist1 = [
        { 'width': '5%' }, 
        { 'width': '10%' },
        { 'width': '10%' }, 
        { 'width': '10%' },
        { 'width': '10%' },
        { 'width': '10%' }
        ];

            //datatables
            // $('#logtable1').DataTable({
            //     //responsive: true,
            //     <?php //datatable_lang();?> 
            //     "columnDefs": [
            //             {
            //                 "targets": [0], 
            //                 "orderable": false, 
            //             },
            //         ],
            //     'columns': columnlist1,
            //     dom: 'Blfrtip',
            //     buttons: [
            //         {
            //             extend: 'excelHtml5',
            //             footer: true,
            //             exportOptions: {
            //                 columns: [0, 1, 2, 3, 4, 5]
            //             }
            //         }
            //     ],

            // });

            $('#catgtable').parent().css({
            'max-width': '100%',
            'overflow-x': 'scroll'
            });
      //  });

      function deleteitem(id,img_name) {
        swal.fire({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this item!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, proceed!',
                cancelButtonText: "No - Cancel",
                reverseButtons: true,
                focusCancel: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: baseurl + 'products/delete_product_image',
                        data: { selectedProducts: id, image: img_name },
                        dataType: 'json',
                        success: function(response) {
                            location.reload();
                        },
                        error: function(xhr, status, error) {}
                    });
                }
            });
        }

        function draw_data() {
        table= $('#avgcosting').DataTable({
            'processing': true,
            'serverSide': true,
            'stateSave': true,
            // responsive: true,
            <?php datatable_lang();?>
            'order': [],
            'ajax': {
                'url': "<?php echo site_url('Reports/ajax_averagecost_list')?>",
                'type': 'POST',
                'data': {
                    '<?=$this->security->get_csrf_token_name()?>': crsf_hash,
                    product_id: $("#pid").val(),
                }
            },
            'columnDefs': [
                {
                    'targets': [0],
                    'orderable': false,
                    'createdCell': function(td, cellData, rowData, row, col) {
                        addClassToColumns(td, col, ['text-center']);
                    }
                },
                {
                    'targets': [4,5],
                    'createdCell': function(td, cellData, rowData, row, col) {
                        addClassToColumns(td, col, ['text-center']);
                    }
                },
                {
                    'targets': [6,7,8],
                    'createdCell': function(td, cellData, rowData, row, col) {
                        addClassToColumns(td, col, ['text-right']);
                    }
                }
            ],
            
        });
    }
    </script>

