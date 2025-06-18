
<div class="content-body">
   <div class="card">
      <div class="card-header border-bottom">
        
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard') ?></a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('products') ?>"><?php echo $this->lang->line('Products') ?></a></li>
            </ol>
        </nav>
         <h5><?php 
              
                echo  ($productcode) ? $product['product_name']." (".$productcode.")" : $this->lang->line('Add New Product');
                $required_class = "required";
                $btn_label = "Add Product";
                $disabled = "disabled";
                if($productcode) {
                    $required_class = "";
                    $disabled = "";
                    $btn_label = "Update Product";
                }

         ?></h5>
         <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
         <div class="heading-elements">
            <ul class="list-inline mb-0">
               <li><a class="breaklink" data-action="expand"><i class="ft-maximize"></i></a></li>
            </ul>
         </div>
      </div>
      <div id="notify" class="alert alert-success" style="display:none;">
         <a href="#" class="close" data-dismiss="alert">&times;</a>
         <div class="message"></div>
      </div>
    
      <!-- erp2024 modified design 04-06-2024 -->
      <div class="card-body">
        <div class="col-12 alert alert-danger alert-dismissible d-none" role="alert">
            <strong><?php echo $this->lang->line('Please fill out all mandatory fields') ?></strong>
        </div>
         <form method="post" id="data_form" enctype="multipart/form-data">
            <input type="hidden" name="productcode" id="productcode" value="<?=$productcode?>">
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
                  <a class="nav-link breaklink" id="base-tab6" data-toggle="tab" aria-controls="tab6"
                     href="#tab6" role="tab"
                     aria-selected="false"><?php echo $this->lang->line('Accounting') ?></a>
               </li>
               <li class="nav-item">
                  <a class="nav-link breaklink" id="base-tab4" data-toggle="tab" aria-controls="tab4"
                     href="#tab4" role="tab"
                     aria-selected="false"><?php echo $this->lang->line('Location') ?></a>
               </li>
               <li class="nav-item">
                  <a class="nav-link breaklink" id="base-tab5" data-toggle="tab" aria-controls="tab5"
                     href="#tab5" role="tab"
                     aria-selected="false"><?php echo $this->lang->line('Additional Details') ?></a>
               </li>
               <li class="nav-item">
                  <a class="nav-link breaklink" id="base-tab3" data-toggle="tab" aria-controls="tab3"
                     href="#tab3" role="tab"
                     aria-selected="false"><?php echo $this->lang->line('Inventory Management') ?></a>
               </li>
               <?php 
               if($productcode)
               { ?>
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
              <?php } ?>
            </ul>
            
            <input type="hidden" name="act" value="add_product">
            <div class="tab-content px-1 pt-1">
               <div class="tab-pane active show" id="tab1" role="tabpanel" aria-labelledby="base-tab1">
                  <div class="form-row">
                     <div class="col-lg-3 mb-2">
                        <h6><b>Total Stock Quantity : <span class="productStockQty"><?=$product['onhand_quantity']?></span></b></h6>
                     </div>
                     <div class="col-lg-9">
                        <h6><b><?php echo $this->lang->line('Alert Quantity(Default)') ?> : <span class="productAlertQty"><?=$product['alert_quantity']?></span></b></h6>
                     </div>
                     <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12"><label class="col-form-label"  for="product_code"><?php echo $this->lang->line('Item Code') ?><span class="compulsoryfld">*</span></label>
                        <input type="text" placeholder="Product Code" class="form-control required" name="product_code" data-original-value="<?=$productcode?>" value="<?=$productcode?>">
                     </div>
                     <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="product_name"><?php echo $this->lang->line('Product Model') ?><span class="compulsoryfld">*</span></label>
                        <input type="text" placeholder="Product Model" class="form-control margin-bottom required" name="model" data-original-value="<?=$product['model']?>" value="<?=$product['model']?>">
                     </div>
                     <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="product_name"><?php echo $this->lang->line('Product Name') ?><span class="compulsoryfld">*</span></label>
                        <input type="text" placeholder="Product Name" class="form-control margin-bottom required" name="product_name"  data-original-value="<?=$product['product_name']?>"  value="<?=$product['product_name']?>">
                     </div>
                     
                     <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label"  for="Arabic Name"><?php echo $this->lang->line('Arabic Name') ?><span class="compulsoryfld">*</span></label>
                        <input type="text" placeholder="Arabic Name"   class="form-control required" name="arabic_name" data-original-value="<?=$product['arabic_name']?>" value="<?=$product['arabic_name']?>">
                     </div>
                     <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">

                        <label class="col-form-label"><?php echo $this->lang->line('Item Description') ?></label>
                        <textarea placeholder="Description"  class="form-textarea margin-bottom" name="product_description" data-original-value="<?=$product['product_description']?>"><?=$product['product_description']?></textarea>
                     </div>
                     <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="product_cat"><?php echo $this->lang->line('Product Category') ?><span class="compulsoryfld">*</span></label>
                        <select name="product_cat[]" id="product_cat" class="form-control" multiple="multiple" data-original-value="<?php echo $category_linked;  ?>">
                           <option value="">Select Category</option>
                           <?php
                              foreach ($cat as $row) {
                                  $cid = $row['category_id'];
                                  $title = $row['full_path']; 
                                  $selected="";
                                  if($category_linked)
                                  {
                                    $cat_selected = in_array($cid, $category_linked) ? 'selected' : '';
                                  }
                                  
                                  echo "<option value='$cid' $cat_selected>$title</option>";
                              }
                              ?>
                        </select>
                     </div>
                     <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none">
                        <label class="col-form-label" for="sub_cat"><?php echo $this->lang->line('Sub') ?><?php echo $this->lang->line('Category') ?></label>
                        <select id="sub_cat" name="sub_cat[]" class="form-control select-box" multiple="multiple">
                           <!-- <option value="">Select Subcategory</option> -->
                        </select>
                     </div>
                     <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="made_in"><?php echo $this->lang->line('Made IN');?></label>
                        <select name="made_in" id="made_in" class="form-control" data-original-value="<?=$product['made_in']?>">
                        <?php
                           echo "<option value=''>Select Country</option>";
                           foreach ($madein as $row) {
                               $cid = $row['id'];
                               $title = $row['name'];
                               $code = $row['code'];
                               $selted = ($product['made_in'] && $product['made_in'] == $cid) ? "selected" : "";
                               echo "<option value='$cid' $selted>$title($code)</option>";
                           }
                           ?>
                        </select>
                     </div>
                     <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="manufacturer_id"><?php echo $this->lang->line('Manufacturer');?><span class="compulsoryfld">*</span></label>
                        <select name="manufacturer_id" id="manufacturer_id" class="form-control" data-original-value="<?=$product['manufacturer_id']?>">
                           <option value=''>Select Manufacturer</option>
                           <?php
                              foreach ($manufacturers as $row) {
                                  $cid = $row['manufacturer_id'];
                                  $title = $row['manufacturer_name'];
                                  $man_selted = ($product['manufacturer_id'] && $product['manufacturer_id'] == $cid) ? "selected" : "";
                                  echo "<option value='$cid' $man_selted>$title</option>";
                              }
                              ?>
                        </select>
                     </div>
                     <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="manufacturer_part_number"><?php echo $this->lang->line('Manufacturer Partno');  ?><span class="compulsoryfld">*</span></label>
                        <input type="text" placeholder="Manufacturer partno"   class="form-control" name="manufacturer_part_number" value="<?=$product['manufacturer_part_number']?>">
                     </div>

                     <!-- //erp2024 08-10-2024 -->
                     <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none">
                        <label class="col-form-label" for="manufacturer_id"><?php echo $this->lang->line('Brand');?></label>
                        <select name="brand_id[]" id="brand_id" class="form-control" multiple="multiple">
                           <option value=''><?php echo $this->lang->line('Select Brand');  ?></option>
                           <?php
                                if(!empty($brands))
                                {
                                    foreach($brands as $brand)
                                    {
                                        echo '<option value="'.$brand['id'].'">'.ucfirst($brand['brand_name']).'</option>';
                                    }
                                }
                           ?>
                        </select>
                     </div>


                     <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="prefered_vendor"><?php echo $this->lang->line('Prefered Vendor');?><?=$product['prefered_vendor']?></label>
                        <select name="prefered_vendor" id="prefered_vendor" class="form-control" data-original-value="<?=$product['prefered_vendor']?>">
                           <option value=''>Select Vendor</option>
                           <?php
                              foreach ($suppliers as $row) {
                                  $cid = $row['supplier_id'];
                                  $title = $row['name'];
                                  $vender_selted = ($product['prefered_vendor'] && $product['prefered_vendor'] == $cid) ? "selected" : "";
                                  echo "<option value='$cid' $vender_selted>$title</option>";
                              }
                              ?>
                        </select>
                     </div>
                 
                     <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="Default TAX Rate"><?php echo $this->lang->line('Default TAX Rate');?></label>
                        <div class="input-group">
                           <input type="text" name="tax_rate" class="form-control" placeholder="<?php echo $this->lang->line('Default TAX Rate') ?>" aria-describedby="sizing-addon1" onkeypress="return isNumber(event)" value="<?=$product['tax_rate']?>" data-original-value="<?=$product['tax_rate']?>"><span class="input-group-addon">%</span>
                        </div>                        
                        <small><?php echo $this->lang->line('Tax rate during') ?></small>
                     </div>

                     <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                           <?php 
                                $date_available = (!empty($product['date_avaialble'])) ? $product['date_avaialble'] : date('Y-m-d');
                                $today = date('Y-m-d');
                                $min_date = ($date_available < $today) ? $date_available : $today;
                            ?>
                            <label class="col-form-label" for="Date Available"><?php echo $this->lang->line('Date Available') ?></label>
                            <input type="date" placeholder="Date Available" class="form-control margin-bottom" data-original-value="<?=$date_available?>"  value="<?=$date_available?>" name="date_avaialble" min="<?=$min_date?>">
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label" for="edate"><?php echo $this->lang->line('Expiry Date'); ?></label>
                            <input type="date" class="form-control"  placeholder="Expiry Date" name="expiry_date"  value="<?=$product['expiry_date']?>" data-original-value="<?=$product['expiry_date']?>">                            
                            <small>Do not change if not applicable</small>
                        </div>
                     
                  </div>
                  <!-- <div class="form-group row">
                    <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <input type="text" placeholder="<?php echo $this->lang->line('On Hand') ?>*"
                           class="form-control margin-bottom" name="onhand_quantity"  id="onhand_quantity">
                     </div>
                     <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <input type="hidden" placeholder="<?php echo $this->lang->line('Alert Quantity') ?>"
                           class="form-control margin-bottom" name="alert_quantity" id="alert_quantity">
                     </div>
                  </div> -->
                  <hr>
                  <div class="form-group row">                    
                        <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="code_type"><?php echo $this->lang->line('BarCode') ?></label>
                            <select class="form-control" name="code_type" id="code_type">
                            <option value="EAN13">EAN13 - Default</option>
                            <option value="UPCA">UPC-A</option>
                            <!-- <option value="EAN8">EAN8</option>
                            <option value="ISSN">ISSN</option>
                            <option value="ISBN">ISBN</option>
                            <option value="C128A">C128A</option>
                            <option value="C39">C39</option> -->
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label" for="code_type"><?php echo $this->lang->line('BarCode Value') ?></label>
                            <input type="text" placeholder="BarCode" class="form-control margin-bottom" name="barcode" id="numeric_barcode" onkeypress="return isNumber(event)" maxlength="12" value="<?=$product['barcode']?>" data-original-value="<?=$product['barcode']?>">
                            <small id="barcode-terms">Leave blank if you want auto generated in EAN13.</small>
                        </div>

                        <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                            <label class="col-form-label" for="code_type2"><?php echo $this->lang->line('Second BarCode') ?></label>
                                <select class="form-control" id="code_type2" name="code_type2" >
                                
                                    <option value="UPCA">UPC-A</option>
                                    <option value="EAN13">EAN13 - Default</option>
                                    <!-- <option value="EAN8">EAN8</option>
                                    <option value="ISSN">ISSN</option>
                                    <option value="ISBN">ISBN</option>
                                    <option value="C128A">C128A</option>
                                    <option value="C39">C39</option> -->
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label" for="code_type"><?php echo $this->lang->line('Second BarCode Value') ?></label>
                                <input type="text" placeholder="BarCode" class="form-control" title="Barcode" id="numeric_barcode2"  name="barcode2"   onkeypress="return isNumber(event)" maxlength="11" value="<?=$product['barcode2']?>" data-original-value="<?=$product['barcode2']?>">
                                <small id="barcode-terms2"></small>

                            </div>
                            <?php 
                            if($product['status'])
                            {?>
                                <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                    <label class="col-form-label" for="Status"><?php echo $this->lang->line('Status') ?></label>
                                    <select name="status" id="status" class="form-control"  data-original-value="<?=$product['status']?>">
                                        <option value="Enable" <?php if($product['status']=='Enable'){ echo "selected"; } ?>>Enable</option>
                                        <option value="Disable" <?php if($product['status']=='Disable'){ echo "selected"; } ?>>Disable</option>                    
                                    </select>
                                </div>
                            <?php } ?>
                    </div>

                  <div class="form-group row">
                 
                    <div class="col-lg-5 col-md-6 col-sm-12 col-xs-12">
                        <!-- Image upload sections starts-->
                            <label for="cst" class="col-form-label"><?php echo $this->lang->line('Add Attachments'); ?></label>
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
                                    <!-- Image upload sections ends -->
                     <!-- <label class="col-form-label"><?php echo $this->lang->line('Image') ?></label> -->
                        <!-- <div id="progress" class="progress">
                           <div class="progress-bar progress-bar-success"></div>
                        </div> -->
                        <!-- The container for the uploaded files -->
                        <!-- <table id="files" class="files"></table>
                        <span class="btn btn-crud btn-outline-light fileinput-button">
                           <i class="glyphicon glyphicon-plus"></i>
                           <span>Select files...</span>
                           <input id="fileupload" type="file" name="files[]">
                        </span><br>
                        <small>Allowed: gif, jpeg, png (Use light small weight images for fast loading - 200x200)</small> -->
                        <!-- The global progress bar -->
                     </div>
                  </div>
                  <!-- <button class="btn btn-pink add_serial btn-sm m-1">   <?php echo $this->lang->line('add_serial') ?></button><div id="added_product"></div>
                     <div id="accordionWrapa1" role="tablist" aria-multiselectable="true">
                     
                         <div id="coupon4" class="card-header">
                             <a data-toggle="collapse" data-parent="#accordionWrapa1" href="#accordion41"
                             aria-expanded="true" aria-controls="accordion41"
                             class="card-title lead collapsed"><i class="fa fa-plus-circle"></i>
                                 <?php echo $this->lang->line('Products') . ' ' . $this->lang->line('Variations') ?></a>
                         </div>
                         <div id="accordion41" role="tabpanel" aria-labelledby="coupon4"
                             class="card-collapse collapse" aria-expanded="false" style="height: 0px;">
                             <div class="row p-1">
                                 <div class="col">
                                     <button class="btn btn-blue tr_clone_add"><?php echo $this->lang->line('add_row') ?></button>
                     
                                     <hr>
                                     <table class="table" id="v_var">
                                         <tr>
                                             <td><select name="v_type[]" class="form-control">
                                                     <?php
                                                        // foreach ($variables as $row) {
                                                        //     $cid = $row['id'];
                                                        //     $title = $row['name'];
                                                        //     $title = $row['name'];
                                                        //     $variation = $row['variation'];
                                                        //     echo "<option value='$cid'>$variation - $title </option>";
                                                        // }
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
                                                        // foreach ($warehouse as $row) {
                                                        //     $cid = $row['id'];
                                                        //     $title = $row['title'];
                                                        //     echo "<option value='$cid'>$title</option>";
                                                        // }
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
                                     </table>
                                 </div>
                             </div>
                         </div>
                     
                     </div> -->
                  <input type="hidden" name="image" id="image" value="default.png">
                   <!-- Image upload sections ends -->
                    <div class="col-12 mb-1">
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
                                        $file_extension = strtolower(pathinfo($image['image'], PATHINFO_EXTENSION));
                                        $is_image = in_array($file_extension, ['jpg', 'jpeg', 'png']);
                                        $file_url = base_url("userfiles/product/extraimages/{$image['image']}");
                                        $img_tag = $is_image ? "<img src='{$file_url}' class='img-thumbnail' alt='{$image['image']}' style='width:70px; height:70px;'>" : '<i class="fa fa-file-code-o fsize-70"></i>';
                                        $download_attr = $is_image ? 'download' : '';
                                        $icon = "Click to download <i class='fa fa-download'></i><br>";
                                        $imgname = $image['image'];
                                
                                        if ($imgcontains % 5 == 1) {
                                            echo '<tr>';
                                        }
                                
                                        echo "<td class='text-center file-td-section'>";
                                        echo "<div class='file-section'>";
                                        echo $img_tag ? "{$img_tag}" : '';
                                        // echo '<p>'.$imgname.'</p>';
                                        echo "<br><a href='{$file_url}' target='_blank' {$download_attr} class='btn btn-crud btn-sm btn-secondary'>{$icon}</a>&nbsp;";
                                        echo "<button class='btn btn-crud btn-sm btn-secondary' onclick=\"delete_image('{$image['product_image_id']}','{$image['image']}')\" type='button'><i class='fa fa-trash'></i></button>";
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
                    </div>
                    <!-- ===== Image sections ends ============== -->
                
                  
               </div>
               <div class="tab-pane" id="tab2" role="tabpanel" aria-labelledby="base-tab2">
                  <!-- erp2024 newly added section  -->
                  
                  <div class="form-row">
                    <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                    <label class="col-form-label" for="product_cost"><?php echo  $this->lang->line('Cost') ?> (<i><?php echo  $this->lang->line('Based on Latest Purchase Price') ?></i>)<span class="compulsoryfld">*</span></label>
                           <input type="number" name="product_cost" id="product_cost" class="form-control"  placeholder="0.00" aria-describedby="sizing-addon1"  onkeypress="return isNumber(event)" value="<?=$product['product_cost']?>" data-original-value="<?=$product['product_cost']?>">
                     </div>
                     
                  
                  
                    <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="weighted_average_cost"><?php echo  $this->lang->line('Weighted Average Cost') ?></i></label>
                        <div class="row">
                            <div class="col-4">
                                <input type="text" name="weighted_average_cost" id="weighted_average_cost" class="form-control"  readonly  value="<?=$product['weighted_average_cost']?>" data-original-value="<?=$product['weighted_average_cost']?>">
                            </div>
                            <div class="col-8 d-none1">
                                <button class="btn btn-primary weighted_average_cost"><?php echo  $this->lang->line('Update FIFO Weighted Average Cost') ?></button>
                            </div>
                            
                        </div>
                    </div>
                    </div>
                  <div class="form-group row">
                     <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label"  for="wholesale_price"><?php echo $this->lang->line('Wholesale Price') ." (".$whole_price_perc ?>)% of Cost</label>
                           <input type="text" name="wholesale_price" id="wholesale_price" class="form-control"   placeholder="0.00" aria-describedby="sizing-addon"  onkeypress="return isNumber(event)" value="<?=$product['wholesale_price']?>" data-original-value="<?=$product['wholesale_price']?>">
                     </div>
                     <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label"
                        for="product_price"><?php echo $this->lang->line('Selling Price')." (".$selling_price_perc ?>)% of Cost</label>
                           <input type="number" name="product_price" id="product_price" class="form-control"   placeholder="0.00" aria-describedby="sizing-addon"  onkeypress="return isNumber(event)" value="<?=$product['product_price']?>"  data-original-value="<?=$product['product_price']?>">
                     </div>
                     <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="web_price"><?php echo 'Web Price ('.$web_price_perc.')% of Cost' ?></label>
                        <div class="input-group">
                           <!-- <span class="input-group-addon"><?php //echo $this->config->item('currency') ?></span> -->
                           <input type="text" name="web_price" id="web_price" class="form-control"  placeholder="0.00" aria-describedby="sizing-addon"  onkeypress="return isNumber(event)" value="<?=$product['web_price']?>" data-original-value="<?=$product['web_price']?>">
                        </div>
                     </div>
                     <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="min_price"><?php echo 'Minimum Price('.$min_price_prec.'% of Cost)'; ?></label>
                        <div class="input-group">
                           <!-- <span class="input-group-addon"><?php //echo $this->config->item('currency') ?></span> -->
                           <input type="text" name="minimum_price" id="min_price" class="form-control"  placeholder="0.00" aria-describedby="sizing-addon"  onkeypress="return isNumber(event)" value="<?=$product['minimum_price']?>" data-original-value="<?=$product['minimum_price']?>">
                        </div>
                        <input type="hidden" name="whole_price_perc" id="whole_price_perc" value="<?=$whole_price_perc?>">
                        <input type="hidden" name="selling_price_perc" id="selling_price_perc" value="<?=$selling_price_perc?>">
                        <input type="hidden" name="web_price_perc" id="web_price_perc" value="<?=$web_price_perc?>">
                        <input type="hidden" name="min_price_prec" id="min_price_prec" value="<?=$min_price_prec?>">
                     </div>

                     <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="Maximum Discount Rate"><?php echo $this->lang->line('Maximum Discount Rate');?><span class="compulsoryfld">*</span></label>
                        <div class="row">
                            <div class="col-3">
                                <div class="input-group">
                                    <input type="number" name="maximum_discount_rate" id="maximum_discount_rate"  min ="0"  class="form-control required" placeholder="<?php echo '%';?>"  onkeypress="return isNumber(event)" onkeyup="checkMaxDiscountRate()"  value="<?=$product['maximum_discount_rate']?>"  data-original-value="<?=$product['maximum_discount_rate']?>"><span
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
                     <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="Default Discount Rate"><?php echo $this->lang->line('Default Discount Rate');?></label>
                        <div class="row">
                            <div class="col-3">
                                <div class="input-group">
                                    <input type="number" name="discount_rate" min="0" id="product_disc" class="form-control"
                                    placeholder="<?php echo '%'; ?>"
                                    aria-describedby="sizing-addon1" onkeypress="return isNumber(event)" onkeyup="checkDiscountRate()" value="<?=$product['discount_rate']?>" data-original-value="<?=$product['discount_rate']?>"><span class="input-group-addon">%</span>
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

               <div class="tab-pane" id="tab3" role="tabpanel" aria-labelledby="base-tab3">
                  <div class="form-row">
                     <!-- <div class="col-lg-3 mb-2">
                        <h6><b>Total Stock Quantity : <span class="productStockQty">0</span></b></h6>
                        <input type="text" placeholder="<?php echo $this->lang->line('On Hand') ?>*"
                        class="form-control margin-bottom" name="onhand_quantity"  id="onhand_quantity">
                     </div>
                     <div class="col-lg-3">
                        <h6><b>Alert Quantity(Default) : <span class="productAlertQty">0</span></b></h6>
                        <input type="text" placeholder="<?php echo $this->lang->line('Alert Quantity') ?>"
                        class="form-control margin-bottom" name="alert_quantity" id="alert_quantity">
                     </div> -->

                     <!-- @erp2024 newly added for price calculations 18-10-2024 -->
                     <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="product_name"><?php echo $this->lang->line('Total Stock Quantity') ?><span class="compulsoryfld">*</span></label>
                        <input type="text" placeholder="<?php echo $this->lang->line('Total Stock Quantity') ?>*"  class="form-control margin-bottom" name="onhand_quantity"  id="onhand_quantity" readonly value="<?=$product['onhand_quantity']?>" data-original-value="<?=$product['onhand_quantity']?>">
                     </div>
                     <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="product_name"><?php echo $this->lang->line('Alert Quantity(Default)') ?><span class="compulsoryfld">*</span></label>
                        <input type="text" placeholder="<?php echo $this->lang->line('Alert Quantity') ?>"
                        class="form-control margin-bottom" name="alert_quantity" id="alert_quantity" value="<?=$product['alert_quantity']?>"  data-original-value="<?=$product['alert_quantity']?>">
                     </div>

                    <div class="col-lg-4">
                       <div class="border-with-padding">
                            <label class="col-form-label" for="product quantity"><b><?php echo $this->lang->line('Update Product Quantity') ?></b></label>
                            <br>
                          
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="plus" checked>
                                <label class="form-check-label" for="inlineRadio1">Plus</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="minus" <?=$disabled?>>
                                <label class="form-check-label" for="inlineRadio2" >Minus</label>
                            </div>
                            <span class="form-check form-check-inline">
                                
                                <input class="form-control <?=$required_class?>" type="number" name="quantity_to_update" id="quantity_to_update" placeholder="Quantity" >
                                
                            </span>
                            <span class="form-check form-check-inline">
                                <button class="btn btn-crud btn-primary" id="product_quantity_update_btn">Update</button>                            
                            </span>
                       </div>
                     </div>
                     
                    <!-- @erp2024 newly added for price calculations 18-10-2024 -->
                    <!-- erp2024 update product quantity 18-10-2024 starts -->
                        
                     <!-- <div class="col-lg-4 mb-2">
                        
                     </div> -->
                     <!-- erp2024 Measurement unit -->
                    <!-- erp2024 update product quantity 18-10-2024 ends -->

                     <!-- erp2024 Measurement unit -->
                     <div class="col-lg-3 d-none">
                            <label class="col-form-label" for="unit"><b><?php echo $this->lang->line('Base Unit') ?><span class="compulsoryfld">*</span></b></label><br>                       
                            <select name="unit" id="unit" class="form-control">
                            <option value=''>Select Unit</option>
                            <?php
                                foreach ($units as $row) {
                                    $cid = $row['code'];
                                    $title = $row['name'];
                                    //erp2024 removed 10-06-2024
                                    // echo "<option value='$cid'>$title</option>";
                                    //erp2024 new 10-06-2024
                                    echo "<option value='$title'>$title</option>";
                                }
                                ?>
                            </select>
                     </div>
                     <div class="col-lg-10 mb-2"></div>
                     <!-- erp2024 Measurement unit -->
                     <?php
                        $totalwarehouse = count($warehouse);
                        $j = 1;
                        echo '<input type="hidden" class="form-control mb-2" name="warehousecount" id="warehousecount" value="'.$totalwarehouse.'">';
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
                            $warehouse_type = ($row['warehouse_type']=='Main')?'(Default)':"";        
                            $stocksQty = "";
                            $alertsQty = "";
                            $flg = 0;
                            foreach ($productwise_warehouse as $whproduct) {
                                if ($cid == $whproduct['store_id']) {
                                    $stocksQty = $whproduct['stock_quantity'];
                                    $alertsQty = $whproduct['alert_quantity'];
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


               <!-- =================== location tab ======================= -->
               <div class="tab-pane" id="tab4" role="tabpanel" aria-labelledby="base-tab4">
                  <div class="form-row ">
                        <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label" for="aisel"><?php echo $this->lang->line('Aisel') ?></label>
                            <input type="text" placeholder="Aisel" class="form-control margin-bottom" name="aisel" value="<?=$product['aisel']?>"  data-original-value="<?=$product['aisel']?>">
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label" for="rack_number"><?php echo $this->lang->line('Rack No.') ?></label>
                            <input type="text" placeholder="Rack No." class="form-control margin-bottom" name="rack_number" value="<?=$product['rack_number']?>"  data-original-value="<?=$product['rack_number']?>">
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label" for="shelf_number"><?php echo $this->lang->line('Shelf') ?></label>
                            <input type="text" placeholder="shelf_number" class="form-control margin-bottom " name="shelf_number" data-original-value="<?=$product['shelf_number']?>" value="<?=$product['shelf_number']?>">
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label" for="bin_number"><?php echo $this->lang->line('Bin') ?></label>
                            <input type="text" placeholder="bin_number" class="form-control margin-bottom" name="bin_number" value="<?=$product['bin_number']?>" data-original-value="<?=$product['bin_number']?>">
                        </div>                     
                  </div>
               </div>
               <!-- =================== location tab ======================= -->

                <!-- =================== Accounting tab ======================= -->
                <div class="tab-pane" id="tab6" role="tabpanel" aria-labelledby="base-tab6">
                  <div class="form-row ">
                        <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label" for="income_account_number"><?php echo $this->lang->line('Income Account') ?>
                            <span class="compulsoryfld">*</span></label>
                            <select name="income_account_number" id="income_account_number" class="form-control" required data-original-value="<?=$product['income_account_number']?>">
                            <?php
                          
                                if ($incomeaccounts)
                                {
                                    echo "<option value=''>" . $this->lang->line('Select Account') . "</option>";
                                    foreach ($incomeaccounts as $row2) {
                                        $transcat_id = $row2['acn'];
                                        $selected = ($defaultaccounts['product_income'] == $transcat_id) ? 'selected="selected"' : '';
                                        if($product['income_account_number'] && $product['income_account_number']==$transcat_id)
                                        {   
                                             $selected = ($product['income_account_number'] == $transcat_id) ? 'selected="selected"' : '';
                                        }                                     
                                        echo "<option value='$transcat_id' $selected>".$row2['acn']." - ".$row2['holder']."</option>";
                                    }
                                }                            
                            ?>
                            </select>                            
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label" for="expense_account_number"><?php echo $this->lang->line('Expense Account') ?>
                            <span class="compulsoryfld">*</span></label>
                            <select name="expense_account_number" id="expense_account_number" class="form-control" required data-original-value="<?=$product['expense_account_number']?>">
                            <?php
                                if ($expenseaccounts)
                                {
                                    echo "<option value=''>" . $this->lang->line('Select Account') . "</option>";
                                    foreach ($expenseaccounts as $row3) {
                                        $transcat_id = $row3['acn'];
                                        $selected = ($defaultaccounts['product_expense'] == $transcat_id) ? 'selected="selected"' : '';
                                       if($product['expense_account_number'] && $product['expense_account_number']==$transcat_id)
                                        {   
                                             $selected = ($product['expense_account_number'] == $transcat_id) ? 'selected="selected"' : '';
                                        }
                                     
                                        echo "<option value='$transcat_id' $selected>".$row3['acn']." - ".$row3['holder']."</option>";
                                    }
                                }                            
                            ?>
                            </select>                            
                        </div>
                        
                  </div>
               </div>


               <!-- =================== Additional Details tab ======================= -->
               <div class="tab-pane" id="tab5" role="tabpanel" aria-labelledby="base-tab5">
               
                    <div class="form-row">
                        <div class="col-sm-3">
                            <label class="col-form-label" for="unit"><b><?php echo $this->lang->line('Base Unit') ?><span class="compulsoryfld">*</span></b></label>
                            <select name="price_unit" id="price_unit" class="form-control" data-original-value="<?=$product['unit']?>">
                            <option value=''>Select Unit</option>
                            <?php
                                foreach ($units as $row) {
                                    $cid = $row['code'];
                                    $title = $row['name'];
                                    $unit_sel = ($product['unit'] && $product['unit'] == $title) ? "selected" : "";
                                    echo "<option value='$title' $unit_sel>$title</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <!-- ============================================ -->
                        <div class="col-sm-2 pricecheckdiv">
                            <label class="col-form-label" ></label>                                
                            <div class="form-check">
                            <input class="form-check-input col-form-label" type="checkbox" value="0" id="kgQuantityCheck" name="kgQuantityCheck" style="margin-top:14px;" >
                            <label class="form-check-label col-form-label" for="kgQuantityCheck">
                            Pieces/Kg ?
                            </label>
                            </div>
                        </div>
                        <div class="col-sm-2 d-none" id="kg_quantitydiv"><label class="col-form-label" for="kg_quantity"><?php echo $this->lang->line('Pieces Per Kg'); ?></label>                                
                            <input type="number" placeholder="kg quantity"   class="form-control" name="pieces_per_kg" value="<?=$product['pieces_per_kg']?>"  data-original-value="<?=$product['pieces_per_kg']?>">
                        </div>
                        <div class="col-sm-2">
                            <label class="col-form-label" for="unit_weight"><?php echo $this->lang->line('Unit Weight');  ?></label>                                
                            <input type="number" placeholder="Unit weight"   class="form-control" name="unit_weight" value="<?=$product['unit_weight']?>" data-original-value="<?=$product['unit_weight']?>">
                        </div>
                        <div class="col-sm-2">
                            <label class="col-form-label" for="unit_weight"><?php echo $this->lang->line('Standard Pack');  ?></label>         
                            <?php $standard_pack = ($product['standard_pack'] && $product['standard_pack'] >0) ? : 1?>                       
                            <input type="number" placeholder="Standard Pack"   class="form-control required" name="standard_pack"  value="<?=$standard_pack?>"  data-original-value="<?=$product['standard_pack']?>">
                        </div>
                        <!-- ============================================ -->
                    </div>
                    <div class="form-row ">
                         <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                            <label class="col-form-label" for="sku"><?php echo $this->lang->line('SKU'); ?></label>
                            <input type="text" class="form-control"  placeholder="SKU" name="sku" value="<?=$product['sku']?>" data-original-value="<?=$product['sku']?>"> 
                        </div>
                         <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                            <label class="col-form-label" for="upc"><?php echo $this->lang->line('UPC'); ?></label>
                            <input type="text" class="form-control"  placeholder="UPC" name="upc" value="<?=$product['upc']?>" data-original-value="<?=$product['upc']?>"> 
                        </div>
                         <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                            <label class="col-form-label" for="ean"><?php echo $this->lang->line('EAN'); ?></label>
                            <input type="text" class="form-control"  placeholder="EAN" name="ean" value="<?=$product['ean']?>" data-original-value="<?=$product['ean']?>"> 
                        </div>
                         <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                            <label class="col-form-label" for="jan"><?php echo $this->lang->line('JAN'); ?></label>
                            <input type="text" class="form-control"  placeholder="JAN" name="jan" value="<?=$product['jan']?>" data-original-value="<?=$product['jan']?>"> 
                        </div>
                         <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                            <label class="col-form-label" for="isbn"><?php echo $this->lang->line('ISBN'); ?></label>
                            <input type="text" class="form-control"  placeholder="ISBN" name="isbn" value="<?=$product['isbn']?>" data-original-value="<?=$product['isbn']?>"> 
                        </div>
                        
                        
                        <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label" for="aisel"><?php echo $this->lang->line('Dimensions (L x W x H)') ?></label>
                            <div class="row">
                                <div class="col-4">
                                    <input type="text" placeholder="Length" class="form-control margin-bottom" name="product_length" value="<?=$product['product_length']?>"  data-original-value="<?=$product['product_length']?>">
                                </div>
                                <div class="col-4 row">
                                    <input type="text" placeholder="Width" class="form-control margin-bottom" name="product_width" value="<?=$product['product_width']?>"  data-original-value="<?=$product['product_width']?>">
                                </div>
                                <div class="col-4">
                                    <input type="text" placeholder="Height" class="form-control margin-bottom" name="product_height" value="<?=$product['product_height']?>"  data-original-value="<?=$product['product_height']?>">
                                </div>
                            </div>
                            
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label" for="rack_number"><?php echo $this->lang->line('Length Class') ?></label><br>
                            <select name="length_class" id="length_class" class="form-control wid100per" data-original-value="<?=$product['length_class']?>">
                                <option value=""><?php echo $this->lang->line('Select Class') ?></option>
                                <option value="Centimeter" <?php if($product['length_class'] && $product['length_class']=='Centimeter' ){ echo "selected"; } ?>>Centimeter</option>
                                <option value="Millimeter" <?php if($product['length_class'] && $product['length_class']=='Millimeter' ){ echo "selected"; } ?>>Millimeter</option>
                                <option value="Inch" <?php if($product['length_class'] && $product['length_class']== 'Inch'){ echo "selected"; } ?>>Inch</option>                               
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
               <!-- =================== location tab ======================= -->
               <hr>
               <div class="form-group row">
                  <div class="col-sm-12 text-right">
                     <input type="submit" id="add_product_btn" class="btn btn-crud btn-lg btn-primary margin-bottom"
                        value="<?php echo $btn_label; ?>" data-loading-text="Adding...">
                     <input type="hidden" value="products/addproduct" id="action-url">
                  </div>
               </div>
            </div>
            <!-- erp2024 modified design 04-06-2024  Ends-->
         </form>
      </div>
   </div>
</div>
<script src="<?php echo assets_url('assets/myjs/jquery.ui.widget.js'); ?>"></script>
<script src="<?php echo assets_url('assets/myjs/jquery.fileupload.js') ?>"></script>
<script>
    const changedFields = {};
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
//    $("#product_cat").select2();
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
//    $("#unit").select2();
   $("#prefered_vendor").select2();
   $("#length_class").select2();
   /*jslint unparam: true */
   /*global window, $ */
   $(function () {
       'use strict';
       // Change this to the location of your server-side upload handler:
       var url = '<?php echo base_url() ?>products/file_handling';
       $('#fileupload').fileupload({
           url: url,
           dataType: 'json',
           formData: {'<?=$this->security->get_csrf_token_name()?>': crsf_hash},
           done: function (e, data) {
               var img = 'default.png';
               $.each(data.result.files, function (index, file) {
   
               	if(file.error) {
   		$('#files').html('<tr><td><span class="alert alert-danger">'+file.error+'</span></td></tr>');
   		img = file.name;
   	} else {
   		$('#files').html('<tr><td><a data-url="<?php echo base_url() ?>products/file_handling?op=delete&name=' + file.name + '" class="aj_delete"><i class="btn-danger btn-sm icon-trash-a"></i> ' + file.name + ' </a><img style="max-height:200px;" src="<?php echo base_url() ?>userfiles/product/' + file.name + '"></td></tr>');
   		img = file.name;
   	}
   
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
   
   
   // Initialize select2 without ajax
   $("#sub_cat").select2();
   
   // Check if product_cat is not empty
   if ($("#product_cat").val() != "") {
        // If product_cat is not empty, update select2 settings with ajax
        $("#sub_cat").select2({
            placeholder: "Sub Category", 
            allowClear: true,
            ajax: {
                url: baseurl + 'products/sub_cat?id=<?= @$cat[0]['id'] ?>',
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
                            }
                        })
                    };
                }
            }
        });
   }
   
   $("#product_cat").on('change', function () {
       $("#sub_cat").val('').trigger('change');
       var tips = $('#product_cat').val();
       $("#sub_cat").select2({
           allowClear: true,
           tags: [],
           ajax: {
               url: baseurl + 'products/sub_cat?id=' + tips,
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
                           if(item.id) {
                               return {
                                   text: item.title,
                                   id: item.id
                               }
                           }
                       })
                   };
               },
           }
       });
   });
   $(document).on('click', ".v_delete_serial", function (e) {
           e.preventDefault();
           $(this).closest('div .serial').remove();
   });
   $(document).on('click', ".add_serial", function (e) {
       e.preventDefault();
   
       $('#added_product').append('<div class="form-group serial"><label for="field_s" class="col-lg-2 col-form-label"><?= $this->lang->line('serial') ?></label><div class="col-lg-10"><input class="form-control box-size" placeholder="<?= $this->lang->line('serial') ?>" name="product_serial[]" type="text"  value=""></div><button class="btn-sm btn-purple v_delete_serial m-1 align-content-end"><i class="fa fa-trash"></i> </button></div>');
   
   });
   
   // erp2024 newly added functions
//    function prdinventorydel(id){
//        if (confirm("Are you sure you want to delete this product inventory record?")) {
//            $(".stock_qty_" + id).val("");
//            $(".alert_qty_" + id).val("");
//            stockalerttowarehouse(id);
//            stockstowarehouse(id);
//        }
//    }

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
                let onhand_quantity = $("#onhand_quantity").val();            
                let alert_quantity = $("#alert_quantity").val();  

                onhand_quantity = onhand_quantity - stock_qty;
                alert_quantity = alert_quantity - alert_qty;

                let product_code = $("#productcode").val();
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
                            'product_code': product_code,
                            'warehouseID': id,
                            'stock_qty': stock_qty,
                            'alert_qty': alert_qty,
                            'onhand_quantity': onhand_quantity,
                            'alert_quantity': alert_quantity
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
    //    $("#onhand_quantity").val(stockQty);
    //    $(".productStockQty").text(stockQty);
   
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
       $("#alert_quantity").val(alertQty);
    //    $(".productAlertQty").text(alertQty);
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
        $("#weighted_average_cost").val(cost);
        
        whole_price_perc = parseFloat($("#whole_price_perc").val());
        selling_price_perc = parseFloat($("#selling_price_perc").val());
        web_price_perc = parseFloat($("#web_price_perc").val());
        min_price_prec = parseFloat($("#min_price_prec").val());
        min_price1 = (((cost*min_price_prec)/100) + cost).toFixed(2);
        selling_price1 = (((cost*selling_price_perc)/100) + cost).toFixed(2);
        web_price1 = (((cost*web_price_perc)/100) + cost).toFixed(2);
        whole_price1 = (((cost*whole_price_perc)/100) + cost).toFixed(2);
       
        var maximum_discount_rate = $('#maximum_discount_rate').val();
        var product_disc = $('#product_disc').val();
        if (isNaN(cost)  && cost !== '') {
            $("#weighted_average_cost").val("");
            $("#wholesale_price").val("")
            $("#product_price").val("")
            $("#web_price").val("");
            $("#min_price").val("");
        }
        else{
            $("#weighted_average_cost").val(cost);
            $("#wholesale_price").val(whole_price1)
            $("#product_price").val(selling_price1)
            $("#web_price").val(web_price1);
            $("#min_price").val(min_price1);
        }
        allowedMAxdiscount();
        if (!isNaN(maximum_discount_rate) && maximum_discount_rate !== '') {
            checkMaxDiscountRate();
        }
        if (!isNaN(product_disc) && product_disc !== '') {
            checkDiscountRate();
        }
   });
   $(document).ready(function() {
        var max_disrate = $('#maximum_discount_rate').val();
        var product_disc = $('#product_disc').val();
        // allowedMAxdiscountedit();
        if (!isNaN(max_disrate) && max_disrate !== '') {
            checkMaxDiscountRate();
        }
        if (!isNaN(product_disc) && product_disc !== '') {
            checkDiscountRate();
        }
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
       $(".pricecheckdiv").hide();
       $("#kg_quantitydiv").hide();

        var priceunit = $("#price_unit").val();
        if(priceunit=='Each'){
            $('#kgQuantityCheck').val("1");
            $('#kg_quantitydiv').removeClass("d-none");
            $("#kg_quantitydiv").show();
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

    $("#data_form").validate($.extend(true, {}, globalValidationOptions, {
        ignore: [], // Important: Do not ignore hidden fields
        rules: {
            model: { required: true },
            product_name: { required: true },
            product_code: { required: true },
            arabic_name: { required: true },
            'product_cat[]': { required: true },
            manufacturer_id: { required: true },
            manufacturer_part_number: { required: true },
            price_unit: { required: true },
            product_cost: { required: true },
            maximum_discount_rate: { required: true },
            alert_quantity: { required: true }
        },
        messages: {
            model: "Enter Product Model",
            product_name: "Enter Product Name",
            product_code: "Enter Item Code",
            arabic_name: "Enter Arabic Name",
            'product_cat[]': "Select at least one Category",
            manufacturer_id : "Select at least one Manufacturer",
            manufacturer_part_number:"Enter Manufacturer Part No.",
            price_unit:"Select Base unit",
            product_cost:"Enter cost amount",
            maximum_discount_rate:"Maximum discount rate",
            quantity_to_update:"Enter the quantity for update",
            alert_quantity:"Enter alert Quantity"
        }
    }));

    function updateSelectOptions() {
        var selectedCode1 = $("#code_type").val();
        var selectedCode2 = $("#code_type2").val();

        // Handle #code_type options
        $("#code_type2 option").each(function() {
            var optionValue = $(this).val();
            if (optionValue === selectedCode1) {
                $(this).hide(); // Hide the selected option in code_type2
            } else {
                $(this).show(); // Show all other options
            }
        });

        // Handle #code_type2 options
        $("#code_type option").each(function() {
            var optionValue = $(this).val();
            if (optionValue === selectedCode2) {
                $(this).hide(); // Hide the selected option in code_type
            } else {
                $(this).show(); // Show all other options
            }
        });
    }

    // On page load, apply the hiding logic based on default selected values
    updateSelectOptions();
    // if($("#productcode").val() !="")
    // {
    //     $.ajax({
    //         url: baseurl + 'Products/locationwiseproducts',
    //         dataType: 'json',
    //         method: 'POST',
    //         data: {'product_code': $("#productcode").val()},
    //         success: function(data) {
    //             resultdata = data.stocks;
    //             let responseHtml = '<table class="table table-bordered dataTable"><thead><tr><th>Name</th><th>Code</th><th>OnHand</th><th>Customer Order</th><th>Purchase Order</th><th>In Transist</th></tr></thead><tbody>';
    //             responseHtml += '<tr>';
    //             responseHtml += '<td>'+data.productname+'</td><td>'+data.productcode+'</td></td><td>'+data.onhand+'</td><td>'+data.total_sales_quantity+'</td><td>'+data.total_purchse_quantity+'</td><td>0</td>';
    //             responseHtml += '</tr></tbody></table>';
    //             $(".resresponse").html(responseHtml);

    //             let onhandHtml = '<table class="table table-bordered dataTable"><thead><tr><th>Warehouse</th><th>Onhand Stock</th><th>Alert</th></tr></thead><tbody>';
    //             onhandHtml += '<tr>';
    //             if (resultdata.length > 0) {
    //                 $.each(resultdata, function(index, row) {
    //                     onhandHtml += '<tr>';                        
    //                     onhandHtml += '<td>' + row.title + '</td>';
    //                     // onhandHtml += '<td>' + row.unit + '</td>';
    //                     onhandHtml += '<td>' + row.stock_qty + '</td>';
    //                     onhandHtml += '<td>' + row.alert_qty + '</td>';
    //                     onhandHtml += '</tr>';
    //                 });
    //             } else {
    //                 onhandHtml += '<tr><td colspan="3">No data available</td></tr>';
    //             }
        
    //             onhandHtml += '</tbody></table>';
    //             $(".warehouseres").html(onhandHtml);
    //         },
    //         error: function(jqXHR, textStatus, errorThrown) {
    //             console.error('Error: ' + textStatus, errorThrown);
    //         }
    //     });

    //     table= $('#avgcosting').DataTable({
    //         'processing': true,
    //         'serverSide': true,
    //         'stateSave': true,
    //         // responsive: true,
    //         <?php //datatable_lang();?>
    //         'order': [],
    //         'ajax': {
    //             'url': "<?php //echo site_url('Reports/ajax_averagecost_list')?>",
    //             'type': 'POST',
    //             'data': {
    //                 '<?=$this->security->get_csrf_token_name()?>': crsf_hash,
    //                 product_code:  $("#productcode").val(),
    //             }
    //         },
    //         'columnDefs': [
    //             {
    //                 'targets': [0],
    //                 'orderable': false,
    //                 'createdCell': function(td, cellData, rowData, row, col) {
    //                     addClassToColumns(td, col, ['text-center']);
    //                 }
    //             },
    //             {
    //                 'targets': [4,5],
    //                 'createdCell': function(td, cellData, rowData, row, col) {
    //                     addClassToColumns(td, col, ['text-center']);
    //                 }
    //             },
    //             {
    //                 'targets': [6,7,8],
    //                 'createdCell': function(td, cellData, rowData, row, col) {
    //                     addClassToColumns(td, col, ['text-right']);
    //                 }
    //             }
    //         ],
            
    //     });
    // }

});  
$("#add_product_btn").on("click", function(e) {
    e.preventDefault();
    $('#add_product_btn').prop('disabled', true);
    var selectedQtys = [];
    var totalqty_warhouses =0;
    var currentStockQty=0;
    var onhand_quantity = parseInt($("#onhand_quantity").val());
    $('.stock_qty').each(function(index) {
        currentStockQty = parseInt($(this).val());
        if (!isNaN(currentStockQty) && currentStockQty > 0) {
            totalqty_warhouses = totalqty_warhouses + currentStockQty;
        } 
    });

    if ($("#data_form").valid()) {
        if(onhand_quantity != totalqty_warhouses )
        {
            Swal.fire({
                icon: 'warning',
                title: 'Stock Mismatch',
                text: 'The total stock is not equal to the sum of the warehouse stocks',
                confirmButtonText: 'OK',
                iconColor: '#f39c12',
            });
            $('#add_product_btn').prop('disabled', false);
            return;
        }  
      var text = "Do you want to create new product?";
      if($("#productcode").val())
      {
        text = "Do you want to update product?";
      }
      Swal.fire({
            title: "Are you sure?",
            // text: "Are you sure you want to update inventory? Do you want to proceed?",
            "text": text,
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
                  url: baseurl +'products/addproduct',
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
               $('#add_product_btn').prop('disabled', false);
            }
      });
    }
    else{
        $('.alert-dismissible').removeClass('d-none');
        $('#add_product_btn').prop('disabled', false);
    }
});

$("#product_quantity_update_btn").on('click',function(e){
    // e.preventDefault();


    e.preventDefault();
    
    var totalqty = 0;
    var checkedValue = $('input[name="inlineRadioOptions"]:checked').val();
    var quantity_to_update = parseInt($("#quantity_to_update").val());
    var onhand_stock = parseInt($("#onhand_quantity").val()) || 0;  
    changedFields[checkedValue] = {
        oldValue: onhand_stock,
        newValue: totalqty,
        fieldlabel : "Product Quantity - "+checkedValue
    };
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
    Swal.fire({
    title: "Are you sure?",
    text: "Do you want to update product quantity?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Yes, proceed",
    cancelButtonText: "Cancel",
    reverseButtons: true,
    }).then((result) => {
        if (result.isConfirmed) {
        //    var quantity_to_update = $("#quantity_to_update").val();
        //    $("#onhand_quantity").val(quantity_to_update);
        //    $("#stock_qty_1").val(quantity_to_update);
        //    $(".productStockQty").text(quantity_to_update);
            if(checkedValue=='plus')
            {
                totalqty = onhand_stock + quantity_to_update;
                $("#onhand_quantity").val(totalqty);
                $("#stock_qty_1").val(quantity_to_update);
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
                else{
                    $("#onhand_quantity").val(totalqty);
                    $("#stock_qty_1").val(quantity_to_update);
                }     
                
            }
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            
        }
    });
    


    

});
$("#code_type").on("change", function() {
    var selectedCode = $(this).val();
    
    $("#numeric_barcode").val("");
    if(selectedCode == "EAN13") {
        $("#barcode-terms").text("Maximum Allowed Digits - 12");
        $("#numeric_barcode").prop('maxLength', 12);
    } else {
        $("#barcode-terms").text("Maximum Allowed Digits - 11");
        $("#numeric_barcode").prop('maxLength', 11);
    }

    $("#code_type2 option").each(function() {
        var optionValue = $(this).val();
        if (optionValue === selectedCode) {
            $(this).hide();
        } else {
            $(this).show();
        }
    });
});

$("#code_type2").on("change", function() {
    var selectedCode = $(this).val();

    $("#numeric_barcode2").val("");
    if(selectedCode == "EAN13") {
        $("#barcode-terms2").text("Maximum Allowed Digits - 12");
        $("#numeric_barcode2").prop('maxLength', 12);
    } else {
        $("#barcode-terms2").text("Maximum Allowed Digits - 11");
        $("#numeric_barcode2").prop('maxLength', 11);
    }

    $("#code_type option").each(function() {
        var optionValue = $(this).val();
        if (optionValue === selectedCode) {
            $(this).hide();
        } else {
            $(this).show();
        }
    });
});

  function delete_image(id,img_name) {
    swal.fire({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover this item!213",
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
                    data: { image_id : id, image: img_name },
                    dataType: 'json',
                    success: function(response) {
                        location.reload();
                    },
                    error: function(xhr, status, error) {}
                });
            }
        });
    }
   // erp2024 newly added functions ends
</script>