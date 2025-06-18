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

                                <div id="invoice-items-details" class="pt-1">
                                    <div class="row">
                                        <div class="table-responsive1 col-sm-12">
                                            <div class="row mb-30">
                                                <div class="col-lg-12">
                                                    <h4 style="text-align:left;">
                                                        <?php echo $this->lang->line('Create Enquiry') ?></h4><br>
                                                </div>
                                                <div class="col-lg-4">
                                                    <label
                                                        for="enquiry_requested_date"><?php echo $this->lang->line('Requested Date') ?></label><br>
                                                    <input type="date" name="enquiry_requested_date"
                                                        id="enquiry_requested_date" class="form-control"
                                                        min="<?=date("Y-m-d")?>">
                                                </div>
                                                <!-- <div class="col-lg-6">
                                            <label for="enquiry_requested_date"><?php echo $this->lang->line('Enquiry Note') ?></label><br>
                                            <textarea name="enquiry_note" id="enquiry_note" class="form-control"></textarea></div> -->
                                                <div class="col-lg-8">
                                                    <label
                                                        for="enquiry_requested_date"><?php echo $this->lang->line('Enquiry Note') ?></label><br>
                                                    <textarea name="enquiry_message" id="enquiry_message"
                                                        class="form-control"></textarea>
                                                </div>
                                            </div>

                                            <table  class="mt-1 table table-striped1 table-bordered zero-configuration dataTable" style="width:100%;">
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
                                                    <tr>
                                                        <td>
                                                            <input type="text" class="form-control wid95per" name="product_name[]"   placeholder="<?php echo $this->lang->line('Enter Product name') ?>"
                                                                id='productname-0' required>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control req amnt wid95per"
                                                                name="product_qty[]" id="amount-0" value="1">
                                                        </td>
                                                        <td></td>
                                                    </tr>
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
                                                        <input type="hidden" value="enquiry/action" id="action-url">
                                                        <input type="hidden" value="0" name="counter" id="ganak">
                                                        <input type="hidden" class="pdIn" name="pid[]" id="pid-0"
                                                            value="0">

                                                    </tr>
                                                    <tr>
                                                        <td align="right" colspan="6" class="no-border"><input type="submit"
                                                                class="btn btn-lg btn-primary sub-btn"
                                                                value="<?php echo $this->lang->line('Create Enquiry') ?>"
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