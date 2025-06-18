<div class="content-body">
    <div class="card">
        <div class="card-header">
            <h5><?php echo $this->lang->line('Add New Transfer') ?></h5>
            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
            <div class="heading-elements">
                <ul class="list-inline mb-0">
                    
                    <li><a class="breaklink" data-action="expand"><i class="ft-maximize"></i></a></li>
                    
                </ul>
            </div>
        </div>
        <div class="card-content">
            <hr>
            <div class="card-body">
                <form method="post" id="data_form" class="form-horizontal">
                <div class="message"></div>

                    <div class="form-group row">
                        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label"
                               for="pay_cat"><?php echo $this->lang->line('From Account') ?></label>
                            <select name="pay_acc" class="form-control">
                                <?php
                                foreach ($accounts as $row) {
                                    $cid = $row['id'];
                                    $acn = $row['acn'];
                                    $holder = $row['holder'];
                                    echo "<option value='$cid'>$acn - $holder</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label"
                               for="pay_cat"><?php echo $this->lang->line('To Account') ?></label>
                            <select name="pay_acc2" class="form-control">
                                <?php
                                foreach ($accounts as $row) {
                                    $cid = $row['id'];
                                    $acn = $row['acn'];
                                    $holder = $row['holder'];
                                    echo "<option value='$cid'>$acn - $holder</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label"
                               for="amount"><?php echo $this->lang->line('Amount') ?></label>
                            <input type="text" placeholder="Amount"
                                   class="form-control margin-bottom  required" name="amount">
                        </div>
                    </div>


                    <div class="form-group row">
                        <div class="col-12 submit-section">
                            <input type="submit" id="submit-data" class="btn btn-primary btn-lg margin-bottom"
                                   value="<?php echo $this->lang->line('Add transaction') ?>"
                                   data-loading-text="Adding...">
                            <input type="hidden" value="transactions/save_transfer" id="action-url">
                        </div>
                    </div>


                </form>
            </div>
        </div>
    </div>
</div>

