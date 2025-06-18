<div class="card card-block">
    <div id="notify" class="alert alert-success" style="display:none;">
        <a href="#" class="close" data-dismiss="alert">&times;</a>

        <div class="message"></div>
    </div>
    <div class="card-body">


        <form method="post" id="data_form" class="form-horizontal">

            <h5><?php echo $this->lang->line('Edit') ?></h5>
            <hr>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="name"><?php echo $this->lang->line('Selling Price') ?> (%)</label>
                <div class="col-sm-4">
                    <input type="number" placeholder="Selling Price" class="form-control margin-bottom  required" name="selling_price_perc" value="<?php echo $selling_price_perc ?>">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="address"><?php echo $this->lang->line('Wholesale Price') ?> (%)</label>
                <div class="col-sm-4">
                    <input type="number" placeholder="Wholesale Price" class="form-control margin-bottom  required" name="whole_price_perc" value="<?php echo $whole_price_perc ?>">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="address"><?php echo $this->lang->line('Web Price') ?> (%)</label>
                <div class="col-sm-4">
                    <input type="number" placeholder="Web Price" class="form-control margin-bottom  required" name="web_price_perc" value="<?php echo $web_price_perc ?>">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="address"><?php echo $this->lang->line('Minimum Price') ?> (%)</label>
                <div class="col-sm-4">
                    <input type="number" placeholder="Minimum Price" class="form-control margin-bottom  required" name="price_perc" value="<?php echo $price_perc ?>">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label"></label>
                <div class="col-sm-4">
                    <input type="submit" id="submit-data" class="btn btn-success margin-bottom"
                           value="<?php echo $this->lang->line('Edit') ?>" data-loading-text="Adding...">
                    <input type="hidden" value="productpricing/edit" id="action-url">
                    <input type="hidden" value="<?php echo $id ?>" name="id">
                </div>
            </div>


        </form>
    </div>
</div>
