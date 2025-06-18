<div class="app-content content container-fluid">
    <div class="content-wrapper">
        <div id="notify" class="alert alert-success" style="display:none;">
            <a href="#" class="close" data-dismiss="alert">&times;</a>

            <div class="message"></div>
        </div>
        <?php

if($this->session->flashdata('quoteupdate')) {
$message = $this->session->flashdata('quoteupdate');
?>
        <div class="alert alert-info"><?php echo $message['message']; ?>

        </div>
        <?php
}

?>
        <div class="content-body">
            <form method="post" id="data_form" action="saveQuoteChanges">
                <section class="card">
                    <div id="invoice-template" class="card-block">
                        <div class="row wrapper white-bg page-heading">

                            <div class="col-lg-12">

                                <div id="invoice-items-details" class="pt-2">
                                    <div class="row">
                                        <div class="table-responsive1 col-sm-12">
                                            <div class="row mb-30">
                                                <div class="col-lg-12">
                                                    <h4 style="text-align:center;">
                                                        <?php echo $this->lang->line('Edit Enquiry') ?></h4><br>
                                                </div>
                                                <div class="col-lg-4">
                                                    <label
                                                        for="enquiry_requested_date"><?php echo $this->lang->line('Requested Date') ?></label><br>
                                                    <input type="date" name="enquiry_requested_date"
                                                        id="enquiry_requested_date" class="form-control"
                                                        min="<?=date("Y-m-d")?>"
                                                        value="<?=$enquirymain['enquiry_requested_date']?>">
                                                </div>
                                                <!-- <div class="col-lg-6">
                                            <label for="Enquiry Note"><?php echo $this->lang->line('Enquiry Note') ?></label><br>
                                            <textarea name="enquiry_note" id="enquiry_note" class="form-control"><?=$enquirymain['enquiry_note']?></textarea></div> -->
                                                <div class="col-lg-8">
                                                    <label
                                                        for="enquiry_requested_date"><?php echo $this->lang->line('Enquiry Note') ?></label><br>
                                                    <textarea name="enquiry_message" id="enquiry_message"
                                                        class="form-control"><?=$enquirymain['enquiry_message']?></textarea>
                                                </div>
                                            </div>
                                            <br><br>

                                            <table  class="table table-striped1 zero-configuration dataTable">
                                                <thead>
                                                    <tr>
                                                        <th><?php echo $this->lang->line('Product Name') ?></th>
                                                        <th class="text-xs-left">
                                                            <?php echo $this->lang->line('Quantity') ?></th>
                                                        <th class="text-xs-left">
                                                            <?php echo $this->lang->line('Actions') ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <input type="hidden" name="lead_id" id="lead_id" value="<?=$enquirymain['lead_id']?>">
                                                    <input type="hidden" name="general_enqid" id="general_enqid" value="<?=$enquirymain['general_enqid']?>">
                                                    <input type="hidden" name="prid" id="prid" value="<?=$enquirymain['id']?>">
                                                    <?php
                                                        $i = 0;
                                                        if(!empty($products)){
                                                            foreach ($products as $row) {
                                                                $productname = $row['product_name'];
                                                                $product_qty = $row['product_qty'];
                                                                $product_id = $row['product_id'];
                                                                echo '<tr><td><input type="text" class="form-control text-center wid95per" name="product_name[]" placeholder="Enter Product name or Code" id="productname-'.$i.'" value="'.$productname.'" onkeypress="autocompletePrdts('.$i.')" ></td><td><input type="text" class="form-control req amnt wid95per" name="product_qty[]" id="amount-'.$i.'" onkeypress="return isNumber(event)" autocomplete="off"  value='.$product_qty.'><input type="hidden" class="pdIn" name="pid[]" id="pid-'.$i.'" value='.$product_id.'> </td><td class="text-center"><button type="button" data-rowid="'.$i.'" class="btn btn-sm btn-default removeProd1" title="Remove" onclick="removeTr('.$i.')"> <i class="icon-trash"></i> </button> </td> </tr>';
                                                                $i++;
                                                            }
                                                        }                                               

                                                    ?>
                                                    <tr class="last-item-row sub_c">
                                                        <td class="add-row">
                                                            <button type="button" class="btn btn-secondary"
                                                                aria-label="Left Align" data-toggle="tooltip"
                                                                data-placement="top" title="Add product row"
                                                                id="addproduct1">
                                                                <i class="icon-plus-square"></i>
                                                                <?php echo $this->lang->line('Add Row') ?>
                                                            </button>
                                                        </td>
                                                        <td colspan="7"></td>
                                                        <input type="hidden" value="enquiry/editaction" id="action-url">
                                                        <input type="hidden" value="<?=$i?>" name="counter" id="ganak">
                                                        <input type="hidden" class="pdIn" name="pid[]" id="pid-0"
                                                            value="0">

                                                    </tr>
                                                    <tr>
                                                        <td align="right" colspan="6"><input type="submit"
                                                                class="btn btn-lg btn-primary sub-btn"
                                                                value="<?php echo $this->lang->line('Update Enquiry') ?>"
                                                                id="submit-data" data-loading-text="Creating...">

                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>


                                </div>
                </section>
            </form>
        </div>
    </div>
</div>