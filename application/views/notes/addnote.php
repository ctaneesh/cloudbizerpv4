<div class="content-body">
    <div class="card">
        <div class="card-header">
            <h5><?php echo $this->lang->line('Add New Note') ?></h5>
            <hr>
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


                <form method="post" id="data_form" class="form-horizontal">


                    <div class="form-group row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label"
                               for="name"><?php echo $this->lang->line('Title') ?><span class="compulsoryfld">*</span></label>
                            <input type="text" placeholder="Note Title"
                                   class="form-control margin-bottom  required" name="title">
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label"
                               for="edate"><?php echo $this->lang->line('Description') ?><span class="compulsoryfld">*</span></label>
                            <textarea class="summernote required"
                                  placeholder=" Note"
                                  autocomplete="false" rows="10" name="content"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">

                        <label class="col-form-label"></label>

                        <div class="col-12 submit-section">
                            <input type="submit" id="submit-data" class="btn btn-primary btn-lg margin-bottom"
                                   value="<?php echo $this->lang->line('Add Note') ?>" data-loading-text="Adding...">
                            <input type="hidden" value="tools/addnote" id="action-url">
                        </div>
                    </div>


                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function () {
        $('.summernote').summernote({
            height: 70,
            toolbar: [
                // [groupName, [list of button]]
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['fullscreen', ['fullscreen']],
                ['codeview', ['codeview']]
            ]
        });
    });
</script>