<div class="content-body">
    <div class="card">
        <div class="card-header">
            <h5><?php echo $this->lang->line('Edit Account') ?></h5>
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
                <?php
                $attributes = array('class' => 'form-horizontal', 'id' => 'data_form');
                echo form_open('', $attributes);
                ?>


                <input type="hidden" name="acid" value="<?php echo $account['id'] ?>">


                <div class="form-group row">
                    <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label"
                           for="accno"><?php echo $this->lang->line('Account No') ?><span class="compulsoryfld">*</span></label>
                        <input type="text"  class="form-control margin-bottom required" name="accno" value="<?php echo $account['acn'] ?>">
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="holder"><?php echo $this->lang->line('Name') ?><span class="compulsoryfld">*</span></label>

                        <input type="text" name="holder" class="form-control required"
                               aria-describedby="sizing-addon1" value="<?php echo $account['holder'] ?>">

                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="acode"><?php echo $this->lang->line('Note') ?></label>
                        <input type="text" name="acode" class="form-control"
                               aria-describedby="sizing-addon1" value="<?php echo $account['code'] ?>">

                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label"
                           for="lid"><?php echo $this->lang->line('Business Locations') ?></label>
                        <select name="lid" class="form-control">
                            <option value='<?php echo $account['loc'] ?>'><?php echo $this->lang->line('Do not change') ?></option>

                            <?php
                            if (!$this->aauth->get_user()->loc) echo "<option value='0'>" . $this->lang->line('All') . "</option>";
                            foreach ($locations as $row) {
                                $cid = $row['id'];
                                $acn = $row['cname'];
                                $holder = $row['address'];
                                echo "<option value='$cid'>$acn - $holder</option>";
                            }
                            ?>
                        </select>
                    </div>
                <?php if ($account['account_type'] == 'Equity') {
                    ?>

                    
                        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label"
                               for="balance"><?php echo $this->lang->line('AccountBalance') ?></label>
                            <input type="text" name="balance" class="form-control"
                                   value="<?php echo amountFormat_general($account['lastbal']) ?>"
                                   onkeypress="return isNumber(event)">
                        </div>
                <?php } ?>
                </div>
                <div class="form-group row">
                    <div class="col-12 submit-section">
                        <input type="submit" id="submit-data" class="btn btn-lg btn-primary margin-bottom"
                               value="<?php echo $this->lang->line('Update') ?>" data-loading-text="Updating...">
                        <input type="hidden" value="accounts/editacc" id="action-url">
                    </div>
                </div>

            </div>
            </form>
        </div>

    </div>
</div>

