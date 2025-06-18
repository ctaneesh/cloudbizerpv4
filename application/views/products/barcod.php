<script src="<?php echo base_url(); ?>assets/plugins/barcode/JsBarcode.all.js"></script>
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/print_barcode.css">
<div class="main-content-wrapper">


    <section class="content-header">
        <div class="row justify-content-between">
            <div class="col-6 p-0">
                <h3 class="top-left-header"><?php echo lang('print_barcode'); ?></h3>
            </div>
           
        </div>
    </section>


    <div class="box-wrapper">
        <div class="table-box">
            <!-- general form elements -->
            <div class="box-body">
                <div id="printableArea">
                    <div class="print_div_wrapper">
                        <?php
                        for ($i = 0; $i < 1;  $i++):
                        for ($j = 0;
                                $j < $items['qty'];
                                $j++):
                            ?>
                            <div class="text-center border-1-default p-2">
                                <div>
                                    <img class="op__min_width_139" id="barcode<?= $items['id'] ?><?= $j ?>">
                                </div>
                                  <div class="text-center item_description">
                                    <p>Code: <b><?= $items['id'] ?></b></p>
                                  
                                </div>
                              
                            </div>
                        <?php
                        endfor;
                        ?>
                        <?php for ($j = 0;
                        $j < $items['qty'];
                        $j++):
                        ?>
                            <svg class="barcode"
                            jsbarcode-format="upc"
                            jsbarcode-value="123456789012"
                            jsbarcode-textmargin="0"
                            jsbarcode-fontoptions="bold">
                            </svg>
                            <script>
                                // inline js used for dynamic barcode generate
                                JsBarcode("#barcode<?=$items['id']?><?=$j?>", "<?=$items['id']?>", {
                                    width: 1,
                                    format: "pharmacode",
                                    height: 30,
                                    fontSize: 12,
                                    textMargin: -18,
                                    margin: 0,
                                    marginTop: 0,
                                    marginLeft: 10,
                                    marginRight: 10,
                                    marginBottom: 0,
                                    displayValue: false
                                });
                                // JsBarcode(".barcode").init();
                            </script>
                        <?php
                        endfor;
                        endfor;
                        ?>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <a id="print_trigger" class="btn bg-blue-btn">
                    <iconify-icon icon="solar:printer-2-broken"></iconify-icon>
                    <?php echo lang('Print');?>
                </a>
                <a class="btn bg-blue-btn" href="<?php echo base_url() ?>Item/itemBarcodeGenerator">
                    <iconify-icon icon="solar:undo-left-round-broken"></iconify-icon>
                    <?php echo lang('back'); ?>
                </a>
            </div>

            

        </div>
    </div>
</div>
<script src="<?php echo base_url(); ?>assets/js/print_trigger.js"></script>
