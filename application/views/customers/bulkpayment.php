<div class="content-body">
<div class="card">
   <div class="card-header">
      <h4 class="card-title"><?php echo $this->lang->line('Customer Details') ?>
         : <?php echo $details['name'] ?>
      </h4>
      <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
      <div class="heading-elements">
         <ul class="list-inline mb-0">
            
            <li><a class="breaklink" data-action="expand"><i class="ft-maximize"></i></a></li>
            
         </ul>
      </div>
   </div>
   <hr>
   <div class="card-content">
      <div id="notify" class="alert alert-success" style="display:none;">
         <a href="#" class="close" data-dismiss="alert">&times;</a>
         <div class="message"></div>
      </div>
      <div class="card-body">
         <div class="row">
            <div class="col-md-4 border-right border-right-grey">
               <div class="ibox-content mt-2">
                  <img alt="image" id="dpic" class="card-img-top img-fluid"
                     src="<?php echo base_url('userfiles/customers/') . $details['picture'] ?>">
               </div>
               <hr>
               <div class="row mt-3">
                  <div class="col-md-12">
                     <a href="<?php echo base_url('customers/view?id=' . $details['id']) ?>"
                        class="btn btn-secondary btn-sm mr-1 mb-1  btn-lighten-1"><i
                        class="fa fa-user"></i> <?php echo $this->lang->line('View') ?></a>
                     <a href="<?php echo base_url('customers/invoices?id=' . $details['id']) ?>"
                        class="btn btn-secondary btn-sm mr-1 mb-1  btn-lighten-1"><i
                        class="fa fa-file-text"></i> <?php echo $this->lang->line('View Invoices') ?>
                     </a>
                     <a href="<?php echo base_url('customers/transactions?id=' . $details['id']) ?>"
                        class="btn btn-secondary btn-sm mr-1 mb-1   btn-lighten-1"><i
                        class="fa fa-money"></i> <?php echo $this->lang->line('View Transactions') ?>
                     </a>
                     <a href="<?php echo base_url('customers/statement?id=' . $details['id']) ?>"
                        class="btn btn-secondary  btn-sm mr-1 mb-1 btn-lighten-1"><i
                        class="fa fa-briefcase"></i> <?php echo $this->lang->line('Account Statements') ?>
                     </a>
                     <a href="<?php echo base_url('customers/quotes?id=' . $details['id']) ?>"
                        class="btn btn-secondary btn-sm mr-1 mb-1  btn-lighten-1"><i
                        class="fa fa-quote-left"></i> <?php echo $this->lang->line('Quotes') ?>
                     </a> <a href="<?php echo base_url('customers/projects?id=' . $details['id']) ?>"
                        class="btn btn-secondary btn-sm mr-1 mb-1  btn-lighten-2"><i
                        class="fa fa-bullhorn"></i> <?php echo $this->lang->line('Projects') ?>
                     </a>
                     <a href="<?php echo base_url('customers/invoices?id=' . $details['id']) ?>&t=sub"
                        class="btn btn-secondary btn-sm mr-1 mb-1  btn-lighten-1"><i
                        class="fa fa-calendar-check-o"></i> <?php echo $this->lang->line('Subscriptions') ?>
                     </a>
                     <a href="<?php echo base_url('customers/notes?id=' . $details['id']) ?>"
                        class="btn btn-secondary  btn-sm mr-1 mb-1 btn-lighten-1"><i
                        class="fa fa-book"></i> <?php echo $this->lang->line('Notes') ?>
                     </a>
                     <a href="<?php echo base_url('customers/documents?id=' . $details['id']) ?>"
                        class="btn btn-secondary btn-sm mr-1 mb-1  btn-lighten-1"><i
                        class="icon-folder"></i> <?php echo $this->lang->line('Documents') ?>
                     </a>
                  </div>
               </div>
            </div>
            <div class="col-md-8">
               <div id="mybutton" class="mb-1">
                  <div class="">
                     <a href="<?php echo base_url('customers/balance?id=' . $details['id']) ?>"
                        class="btn btn-secondary btn-sm"><i
                        class="fa fa-briefcase"></i> <?php echo $this->lang->line('Wallet') ?>
                     </a>
                     <a href="#sendMail" data-toggle="modal" data-remote="false"
                        class="btn btn-secondary btn-sm " data-type="reminder"><i
                        class="fa fa-envelope"></i> <?php echo $this->lang->line('Send Message') ?>
                     </a>
                     <a href="<?php echo base_url('customers/create?id=' . $details['id']) ?>"
                        class="btn btn-secondary btn-sm"><i
                        class="fa fa-pencil"></i> <?php echo $this->lang->line('Edit Profile') ?>
                     </a>
                     <a href="<?php echo base_url('customers/changepassword?id=' . $details['id']) ?>"
                        class="btn btn-secondary btn-sm"><i
                        class="fa fa-key"></i> <?php echo $this->lang->line('Change Password') ?>
                     </a>
                  </div>
               </div>
               <hr>
               <h5 class="mb-2"><?= $this->lang->line('Bulk Payment Invoices'); ?></h5>
               <hr>
               <form method="post" id="product_action">
                  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                     value="<?php echo $this->security->get_csrf_hash(); ?>">
                  <input type="hidden" name="customer" value="<?= $id ?>">
                  <div class="form-group row">
                     <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="pay_cat"><?php echo $this->lang->line('Type') ?></label>
                        <select name="trans_type"  id="trans_type" class="form-control">
                           <option value='due'><?php echo $this->lang->line('Due') ?></option>
                           <option value='partial'><?php echo $this->lang->line('Partial') ?></option>
                        </select>
                     </div>
                     <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="sdate"><?php echo $this->lang->line('From Date') ?><span class="compulsoryfld">*</span></label>
                        <input type="text" class="form-control required" placeholder="Start Date" name="sdate" id="sdate"  data-toggle="datepicker" autocomplete="false">
                     </div>
                     <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="edate"><?php echo $this->lang->line('To Date') ?><span class="compulsoryfld">*</span></label>
                        <input type="text" class="form-control required" placeholder="End Date" name="edate" id="t_date" data-toggle="datepicker" autocomplete="false">
                     </div>
                  </div>
                  <div class="form-group row">
                     <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 submit-section">
                        <input type="submit" class="btn btn-primary" id="calculate_due"  value="<?php echo $this->lang->line('Calculate') ?>">
                     </div>
                  </div>
               </form>
               <hr>
               <form method="post" id="product_action_2">
                  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                     value="<?php echo $this->security->get_csrf_hash(); ?>">
                  <input type="hidden" name="customer" value="<?= $id ?>">
                  <div class="form-group row">
                     <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="sdate"><?php echo $this->lang->line('Amount') ?></label>
                        <input type="text" class="form-control" placeholder="Amount" name="amount" id="amount" autocomplete="false" value="0">
                     </div>
                     <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="pay_cat"><?php echo $this->lang->line('Type') ?></label>
                        <select name="trans_type_2" id="trans_type_2" class="form-control">
                           <option value='due'><?php echo $this->lang->line('Due') ?></option>
                           <option value='partial'><?php echo $this->lang->line('Partial') ?></option>
                        </select>
                     </div>
                     <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="pmethod"><?php echo $this->lang->line('Payment Method') ?></label> 
                        <select name="pmethod" class="form-control mb-1">
                           <option value="Cash"><?php echo $this->lang->line('Cash') ?></option>
                           <option value="Card"><?php echo $this->lang->line('Card') ?></option>
                           <option value="Balance"><?php echo $this->lang->line('Client Balance') ?></option>
                           <option value="Bank"><?php echo $this->lang->line('Bank') ?></option>
                        </select>
                     </div>
                     <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="account"><?php echo $this->lang->line('Account') ?></label>
                        <select name="account" class="form-control">
                        <?php foreach ($acclist as $row) {
                           echo '<option value="' . $row['id'] . '">' . $row['holder'] . ' / ' . $row['acn'] . '</option>';
                           }
                           ?>
                        </select>
                     </div>
                     <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="sdate"><?php echo $this->lang->line('From Date') ?><span class="compulsoryfld">*</span></label>
                        <input type="text" class="form-control required date30"  placeholder="Start Date" name="sdate_2" id="sdate_2" data-toggle="datepicker" autocomplete="false">
                     </div>
                     <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="edate"><?php echo $this->lang->line('To Date') ?><span class="compulsoryfld">*</span></label>
                        <input type="text" class="form-control required" placeholder="End Date" name="edate_2" id="edate_2" data-toggle="datepicker" autocomplete="false">
                     </div>
                     <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label"  for="sdate"><?php echo $this->lang->line('Note') ?></label>
                        <input type="text" class="form-control" placeholder="Note" name="note" autocomplete="false" value="<?= $this->lang->line('Bulk Payment Invoices'); ?>">
                     </div>
                  </div>
                  <div class="form-group row">
                     <div class="col-12 submit-section">
                        <input type="submit" class="btn btn-primary" id="calculate_pay"  value="<?php echo $this->lang->line('Make Payment') ?>">
                     </div>
                  </div>
               </form>
            </div>
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
   $("#calculate_due").click(function (e) {
       e.preventDefault();
       var actionurl = baseurl + 'customers/bulk_post';
       t_actionCaculate(actionurl);
       $("#sdate_2").val($("#sdate").val());
       $("#edate_2").val($("#t_date").val());
        $("#trans_type_2").val($("#trans_type").val());
   
   });
   
   $("#calculate_pay").click(function (e) {
       e.preventDefault();
       var actionurl = baseurl + 'customers/bulk_post_payment';
       t_actionCaculate(actionurl, '#product_action_2');
   
   });
   
   
   function t_actionCaculate(actionurl, f_name = '#product_action') {
       var errorNum = farmCheck();
       if (errorNum > 0) {
           $("#notify").removeClass("alert-success").addClass("alert-warning").fadeIn();
           $("#notify .message").html("<strong>Error</strong>: It appears you have forgotten to complete something!");
           $("html, body").animate({scrollTop: $('#notify').offset().top}, 1000);
       } else {
           $(".required").parent().removeClass("has-error");
           $.ajax({
               url: actionurl,
               type: 'POST',
               data: $(f_name).serialize() + '&' + crsf_token + '=' + crsf_hash,
               dataType: 'json',
               success: function (data) {
                   $("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
                   $("#notify").removeClass("alert-warning").addClass("alert-success").fadeIn();
                   $("html, body").animate({scrollTop: $('html, body').offset().top}, 200);
                   //  $("#product_action").remove();
                   $("#param1").html(data.param1);
                   $("#amount").val(data.due);
   
               },
               error: function (data) {
                   $("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
                   $("#notify").removeClass("alert-success").addClass("alert-warning").fadeIn();
                   $("html, body").animate({scrollTop: $('#notify').offset().top}, 1000);
               }
           });
       }
   }
   $(function() {
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
});
</script>