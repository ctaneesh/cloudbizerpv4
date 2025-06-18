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
        <div class="content-body" >
            <form method="post" id="data_form" action="saveQuoteChanges">
                <section class="card" style="padding:20px;">
                    <div id="invoice-template" class="card-block">
                        <div class="row wrapper white-bg page-heading">

                            <div class="col-lg-12">
                                
                        <div id="invoice-items-details" class="pt-2">
                            <div class="row">
                                <div class="table-responsive col-sm-12">
                                    <div class="row mb-30">
                                      
                                        <div class="col-lg-12"><h4 style="text-align:center;"><?php echo $this->lang->line('Edit Enquiry') ?></h4><br></div>
                                        <div class="col-lg-7">
                                            <h3><b><?php echo $enquirymain['name']; ?></b></h3>
                                            <?php
                                                echo "<label>Phone: ".$enquirymain['phone']."</label> \n<br>";
                                                echo "<label>Phone : ".$enquirymain['email']."</label> \n<br>";
                                                echo "<label>Address : ".$enquirymain['address']."</label> \n<br>";
                                                echo "<label>City : ".$enquirymain['city']."</label> \n<br>";
                                                echo "<label>Region : ".$enquirymain['region']."</label> \n<br>";
                                                echo "<label>Country : ".$enquirymain['country']."</label> \n<br>";
                                                echo "<label>Postbox : ".$enquirymain['postbox']."</label> \n<br>";
                                            ?>
                                        </div>
                                        <div class="col-lg-5" style="float:right;">
                                            <h3><b>Enquiry Details</b></h3>
                                            <?php
                                                echo "<div class='text-left'>";
                                                echo "<label>Requested Date: ".$enquirymain['enquiry_requested_date']."</label> \n<br>";
                                                echo "<label>Enquiry Date : ".$enquirymain['enquiry_date']."</label> \n<br>";
                                                echo "<label>Note : ".$enquirymain['enquiry_note']."</label> \n<br>";
                                                echo "<label>Message  : ".$enquirymain['enquiry_message']."</label> \n<br>";
                                                echo "<label>Status : ".$enquirymain['status']."</label> \n<br>";
                                                echo "</div>";
                                            ?>
                                        </div>
                                        <div class="col-lg-12 text-center">
                                                <?php if($enquirymain['status']!="pending"){  ?>
                                                <input type="button" class="btn btn-danger sub-btn"
                                                                     value="<?php echo $this->lang->line('Already Converted') ?>"
                                                                     >
                                                <?php } ?>
                                        </div>
                                    </div>
                                    <br><br>

                                    <table class="table table-striped table-bordered zero-configuration dataTable">
                                        <thead>
                                        <tr>
                                            <th><?php echo $this->lang->line('Product Name') ?></th>
                                            <th class="text-xs-left"><?php echo $this->lang->line('Quantity') ?></th>
                                            <th class="text-xs-left"><?php echo $this->lang->line('Actions') ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <input type="hidden" name="lead_id" id="lead_id" value="<?=$enquirymain['lead_id']?>">
                                            <input type="hidden" name="prid" id="prid" value="<?=$enquirymain['id']?>">
                                            <?php
                                                $i = 0;
                                                if(!empty($products)){
                                                    foreach ($products as $row) {
                                                        $productname = $row['product_name'];
                                                        $product_qty = $row['product_qty'];
                                                        $product_id = $row['product_id'];
                                                        echo '<tr><td><input type="text" class="form-control" name="product_name[]" placeholder="Enter Product name or Code" id="productname1-'.$i.'" value="'.$productname.'"  onkeypress="autocompletePrdts('.$i.')"></td><td><input type="text" class="form-control req amnt" name="product_qty[]" id="amount-'.$i.'" onkeypress="return isNumber(event)" autocomplete="off"  value='.$product_qty.'><input type="hidden" class="pdIn" name="pid[]" id="pid-'.$i.'" value='.$product_id.'> </td><td class=""><button type="button" data-rowid="'.$i.'" class="btn btn-sm btn-default removeProd1 mt-1" title="Remove" onclick="removeTr('.$i.')"> <i class="fa fa-trash"></i> </button> </td> </tr>';
                                                        $i++;
                                                    }
                                                }
                                                

                                            ?>
                                            <tr class="last-item-row sub_c">
                                                <td class="add-row">
                                                <?php if($enquirymain['status']=="pending"){  ?>
                                                    <button type="button" class="btn btn-success" aria-label="Left Align"
                                                            data-toggle="tooltip"
                                                            data-placement="top" title="Add product row" id="addenqproduct">
                                                        <i class="icon-plus-square"></i> <?php echo $this->lang->line('Add Row') ?>
                                                    </button>
                                                <?php } ?>
                                                </td>
                                                <td colspan="7"></td>
                                                <input type="hidden" value="enquiry/convert_to_quote" id="action-url">
                                                <input type="hidden" value="<?=$i?>" name="counter" id="ganak">                                            
                                                <input type="hidden" class="pdIn" name="pid[]" id="pid-0" value="0">
                                                
                                            </tr>
                                            <tr>
                                            <td align="right" colspan="6">
                                                <?php if($enquirymain['status']=="pending"){ ?>
                                                    <input type="submit" class="btn btn-success sub-btn"
                                                                     value="<?php echo $this->lang->line('Convert to Quote') ?>"
                                                                     id="submit-data" data-loading-text="Creating...">
                                               <?php  } ?>
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

