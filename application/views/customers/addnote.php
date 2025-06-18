<article class="content">
    <div class="card card-block">
        <div id="notify" class="alert alert-success" style="display:none;">
            <a href="#" class="close" data-dismiss="alert">&times;</a>

            <div class="message"></div>
        </div>
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
        <div class="grid_3 grid_4">


            <form method="post" id="data_form" class="form-horizontal">

               <div class="mt-2 ml-2">
                    <input type="hidden" name="id" value="<?php echo $id ?>">
                    <div class="form-group row">
                        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label" for="name"><?php echo $this->lang->line('Title') ?><span class="compulsoryfld">*</span></label>
                            <input type="text" placeholder="Note Title" class="form-control margin-bottom  required" name="title">
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label" for="edate"><?php echo $this->lang->line('Description') ?><span class="compulsoryfld">*</span></label>
                            <textarea class="summernote1 form-control required" placeholder=" Note"  name="content"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-12 submit-section">
                                <input type="submit" id="submit-data" class="btn btn-lg btn-primary margin-bottom"
                                value="<?php echo $this->lang->line('Add Note') ?>" data-loading-text="Adding...">
                                <input type="hidden" value="customers/addnote" id="action-url">
                        </div>
                    </div>
               </div>


            </form>
        </div>
    </div>
</article>
<script type="text/javascript">
    $(function () {
        $('.summernote').summernote({
            height: 50,
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