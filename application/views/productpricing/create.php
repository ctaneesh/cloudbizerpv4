<div class="card card-block">
    <div id="notify" class="alert alert-success" style="display:none;">
        <a href="#" class="close" data-dismiss="alert">&times;</a>

        <div class="message"></div>
    </div>
    <div class="card card-block ">


        <form method="post" id="data_form" class="card-body">

            <h5><?php echo $this->lang->line('Add') ?> <?php echo $this->lang->line('Product Pricing') ?></h5>
            <hr>
            <div class="form-group row">
                <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                    <label class="col-form-label" for="name"><?php echo $this->lang->line('Selling Price') ?><span class="compulsoryfld">*</span> (%)</label>
                    <input type="number" placeholder="Selling Price" class="form-control margin-bottom  required" name="selling_price_perc" >
                </div>
                <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                     <label class="col-form-label" for="address"><?php echo $this->lang->line('Wholesale Price') ?><span class="compulsoryfld">*</span> (%)</label>
                    <input type="number" placeholder="Wholesale Price" class="form-control margin-bottom  required" name="whole_price_perc">
                </div>
                <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                    <label class="col-form-label" for="address"><?php echo $this->lang->line('Web Price') ?><span class="compulsoryfld">*</span> (%)</label>
                    <input type="number" placeholder="Web Price" class="form-control margin-bottom  required" name="web_price_perc">
                </div>
                <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                    <label class="col-form-label" for="address"><?php echo $this->lang->line('Minimum Price') ?><span class="compulsoryfld">*</span> (%)</label>
                    <input type="number" placeholder="Minimum Price" class="form-control margin-bottom  required" name="price_perc">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 submit-section">
                    <input type="submit" id="submit-data" class="btn btn-primary btn-lg margin-bottom"  value="<?php echo $this->lang->line('Add') ?>" data-loading-text="Adding...">
                    <input type="hidden" value="productpricing/create" id="action-url">
                </div>
            </div>

            <input type="hidden" name="image" id="image" value="logo.png">
        </form>
    </div>
</div>
