<div class="content-body">
<div class="card">
   <div class="card-header">
      <h5><?php echo $this->lang->line('Client Group') . '- ' . $group['title'] ?>
      <a href="<?php echo base_url('clientgroup/create') ?>" class="btn btn-primary btn-sm"><?php echo $this->lang->line('Add new') ?></a>
      <a href="#sendMail"
         data-toggle="modal"
         data-remote="false"
         class="btn btn-secondary btn-sm"><i
         class="fa fa-envelope"></i> <?php echo $this->lang->line('Send Group Message') ?> </a>
         </h5>         
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
         <table id="fclientstable" class="table table-striped table-bordered zero-configuration" cellspacing="0" style="width:100%;">
            <thead>
               <tr>
                  <th style="width:5%; text-align:center;">#</th>
                  <th style="width:5%; text-align:center;"><?php echo $this->lang->line('Image') ?></th>
                  <th style="width:15%;"><?php echo $this->lang->line('Name') ?></th>
                  <th style="width:25%;"><?php echo $this->lang->line('Address') ?></th>
                  <th style="width:28%;"><?php echo $this->lang->line('Email') ?></th>
                  <th style="width:11%;"><?php echo $this->lang->line('Mobile') ?></th>
                  <th><?php echo $this->lang->line('Settings') ?></th>
               </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
               <tr>
                  <th style="width:5%; text-align:center;">#</th>
                  <th style="width:5%; text-align:center;"><?php echo $this->lang->line('Image') ?></th>
                  <th><?php echo $this->lang->line('Name') ?></th>
                  <th><?php echo $this->lang->line('Address') ?></th>
                  <th><?php echo $this->lang->line('Email') ?></th>
                  <th><?php echo $this->lang->line('Mobile') ?></th>
                  <th><?php echo $this->lang->line('Settings') ?></th>
               </tr>
            </tfoot>
         </table>
      </div>
   </div>
</div>
<script type="text/javascript">
   $(document).ready(function () {
   
       $('#fclientstable').DataTable({
   
           "processing": true, //Feature control the processing indicator.
           "serverSide": true, //Feature control DataTables' server-side processing mode.
           "order": [], //Initial no order.
           responsive: true,
           <?php datatable_lang();?>
   
           // Load data for the table's content from an Ajax source
           "ajax": {
               "url": "<?php echo site_url('clientgroup/grouplist') . '?id=' . $group['id']; ?>",
               "type": "POST",
               'data': {'<?=$this->security->get_csrf_token_name()?>': crsf_hash}
           },
   
           //Set column definition initialisation properties.
           "columnDefs": [
               {
                   "targets": [0], //first column / numbering column
                   "orderable": false, //set not orderable
               },
           ],
   
       });
       $('#fclientstable').parent().css({
            'max-width': '100%', // Set the maximum height as per your requirement
            'overflow-x': 'scroll'
        });
   
   });
</script>
<div id="delete_model" class="modal fade">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title"><?php echo $this->lang->line('Delete') ?> </h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
               aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body">
            <p><?php echo $this->lang->line('delete this customer') ?> </p>
         </div>
         <div class="modal-footer">
            <input type="hidden" id="object-id" value="">
            <input type="hidden" id="action-url" value="customers/delete_i">
            <button type="button" data-dismiss="modal" class="btn btn-primary"
               id="delete-confirm"><?php echo $this->lang->line('Delete') ?> </button>
            <button type="button" data-dismiss="modal"
               class="btn"><?php echo $this->lang->line('Cancel') ?> </button>
         </div>
      </div>
   </div>
</div>
<div id="sendMail" class="modal fade">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">Send Message</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
         </div>
         <div class="modal-body">
            <form id="sendmail_form">
               <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
               <div class="row">
                  <div class="col">
                     <label for="customername" class="col-form-label"><?php echo $this->lang->line('Email') ?><span class="compulsoryfld">*</span></label>
                     <input type="email" class="form-control required" placeholder="Email" name="mailtoc" value="<?php echo $details['email'] ?>" required>
                  </div>
               </div>
               <div class="row">
                  <div class="col">
                     <label for="customername"  class="col-form-label"><?php echo $this->lang->line('Customer Name') ?><span class="compulsoryfld">*</span></label>
                     <input type="text" class="form-control required"  name="customername" value="<?php echo $details['name'] ?>" required>
                  </div>
               </div>
               <div class="row">
                  <div class="col">
                     <label for="subject"  class="col-form-label"><?php echo $this->lang->line('Subject') ?><span class="compulsoryfld">*</span></label>
                     <input type="text" class="form-control required" name="subject" id="subject" required>
                  </div>
               </div>
               <div class="row">
                  <div class="col">
                     <label for="contents"  class="col-form-label"><?php echo $this->lang->line('Message') ?><span class="compulsoryfld">*</span></label>
                     <textarea name="text" class="summernote form-control required" id="contents" title="Contents" required></textarea>
                  </div>
               </div>
               <input type="hidden" class="form-control" id="cid" name="tid" value="<?php echo $details['id'] ?>">
               <input type="hidden" id="action-url" value="communication/send_general">
            </form>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary"
               data-dismiss="modal"><?php echo $this->lang->line('Close') ?></button>
            <button type="button" class="btn btn-primary" id="sendNow"><?php echo $this->lang->line('Send') ?></button>
         </div>
      </div>
   </div>
</div>
<script type="text/javascript">
   $(function () {
       $('.summernote').summernote({
           height: 100,
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
   
       $('form').on('submit', function (e) {
           e.preventDefault();
           alert($('.summernote').summernote('code'));
           alert($('.summernote').val());
       });
   });
</script>