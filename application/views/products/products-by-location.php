<div class="content-body">
    <div class="card">
        <div class="card-header pb-0">
            <h5><?php echo $this->lang->line('Products By Location') ?></h5>
            <hr>
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
            <div class="form-group row">
                <div class="col-lg-4">
                    <label class="col-form-label">Enter product name or code with a minimum of 4 characters</label>
                    <input type="text" class="form-control" name="productname" placeholder="<?php echo "Enter Product name or code with minimum 4 chars" ?>" id='productname123'>
                </div>
                
                <input type="hidden" value="productsearch" id="billtype">
            </div>
            <div class="resultsection">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link breaklink active show" id="base-tab1" data-toggle="tab"
                            aria-controls="tab1" href="#tab1" role="tab"
                            aria-selected="true"><?php echo $this->lang->line('Details') ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link breaklink" id="base-tab2" data-toggle="tab" aria-controls="tab2"
                            href="#tab2" role="tab"
                            aria-selected="false"><?php echo $this->lang->line('Onhand') ?></a>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link" id="base-tab3" data-toggle="tab" aria-controls="tab3"
                            href="#tab4" role="tab"
                            aria-selected="false"><?php echo $this->lang->line('CustomFields') ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="base-tab3" data-toggle="tab" aria-controls="tab3"
                            href="#tab3" role="tab"
                            aria-selected="false"><?php echo $this->lang->line('Customer Details'); ?></a>
                    </li> -->

                </ul>

                <div class="tab-content px-1 pt-1">
                    <div class="tab-pane active show resresponse" id="tab1" role="tabpanel" aria-labelledby="base-tab1">
                        
                    </div>
                    <div class="tab-pane warehouseres" id="tab2" role="tabpanel" aria-labelledby="base-tab2">
                        
                    </div>
                    
                    <div class="tab-pane" id="tab3" role="tabpanel" aria-labelledby="base-tab3">
                        
                    </div>

                   
                <!-- erp2024 new section ends 10-06-2024 -->


            </div>
        </div>
    </div>
</div>
<script src="<?php echo assets_url('assets/myjs/jquery.ui.widget.js'); ?>"></script>
<script src="<?php echo assets_url('assets/myjs/jquery.fileupload.js') ?>"></script>

<script>
    $(document).ready(function() {
        $(".resultsection").hide();
    });
    $("#product_cat").select2();
    
    // erp2024 newly added functions ends
</script>
