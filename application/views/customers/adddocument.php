<article class="content">
    <div class="card card-block">
        <?php if ($response == 1) {
            echo '<div id="notify" class="alert alert-success">
            <a href="#" class="close" data-dismiss="alert">&times;</a>

            <div class="message">' . $responsetext . '</div>
        </div>';
        } else if ($response == 0) {
            echo '<div id="notify" class="alert alert-danger">
            <a href="#" class="close" data-dismiss="alert">&times;</a>

            <div class="message">' . $responsetext . '</div>
        </div>';
        } ?>
        <div class="grid_3 grid_4 mt-2 ml-2">


            <?php echo form_open_multipart('customers/adddocument'); ?>
            <input type="hidden" value="<?= $id ?>" name="id">
            <h5><?php echo $this->lang->line('Upload New Document') ?> </h5>
            <hr>

            <div class="form-group row">
                <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label" for="name"><?php echo $this->lang->line('Title') ?><span class="compulsoryfld">*</span></label>
                    <input type="text" placeholder="Document Title" class="form-control margin-bottom  required" name="title">
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label" for="name"><?php echo $this->lang->line('Document') ?>
                    (docx,docs,txt,pdf,xls)<span class="compulsoryfld">*</span></label>
                    <input type="file" name="userfile" size="20"/>
                </div>
            </div>


            <div class="form-group row">
                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 submit-section">
                    <input type="submit" id="document_add" class="btn btn-primary btn-lg margin-bottom"
                           value="<?php echo $this->lang->line('Upload Document') ?>" data-loading-text="Adding...">
                </div>
            </div>


            </form>
        </div>
    </div>
</article>

