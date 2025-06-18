<article class="content-body">
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
         } else {
         echo ' <div id="notify" class="alert alert-success" style="display:none;">
         <a href="#" class="close" data-dismiss="alert">&times;</a>
         
         <div class="message"></div>
         </div>';
         
         } 
         $ticket_number = "ST/".($thread_info['id']+1000);
         ?>


      <div class="card-body">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('tickets') ?>"><?php echo $this->lang->line('Support Tickets'); ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $ticket_number; ?></li>
                </ol>
            </nav> 
           <h5 class="card-title"><?php echo $ticket_number; ?> <a href="#pop_model" data-toggle="modal"
            data-remote="false"
            class="btn btn-sm btn-primary"
            title="Change Status"
            ><span class="icon-tab"></span> <?php echo $this->lang->line('Change Status') ?></a></h5>
        
         <hr>

         <div class="card-bordered shadow p-1 mb-1 col-12">
               
                <div class="row">
                    <div class="col-lg-6 col-md-9 col-sm-9 col-xs-12">
                        <h4 class="card-title"><?php echo "Ticket Number : ". $ticket_number ?></h4>
                    </div>
                    <div class="col-lg-6 col-md-3 col-sm-3 col-xs-12 text-right">
                        <?php
                            if($thread_info['status']=='Processing')
                            {
                                $class="st-Processing";
                                $text="Processing";
                            }
                            else if($thread_info['status']=='Solved')
                            {
                                $class="st-Solved";
                                $text="Solved";
                            }
                            else
                            {
                                $class="st-Waiting";
                                $text="Waiting";
                            }
                            echo '<span class="'.$class.'">'.$text.'</span>'
                        ?>
                    
                    </div>
                    
                </div>
                <hr>
                <p class="mb-0"><?php 
                    echo '<strong>Subject : </strong> ' . $thread_info['subject'];
                    echo '<br><strong>Created on : </strong> ' . dateformat_time($thread_info['created']);
                
                    echo '<br><strong>Customer : </strong> ' . $thread_info['name'];
                
                    
                    ?></span></p>
                
            </div>
         <?php foreach ($thread_list as $row) { ?>
         <div class="form-group row">
            <div class="col">
               <div class="card-bordered shadow p-1"><?php
                  if ($row['custo']) echo '<i class="fa fa-user display-inline-block"></i> Customer <strong>' . $row['custo'] . '</strong> Replied  on  <i>'.dateformat_time($row['cdate']).'</i><hr>';
                  
                  if ($row['emp']) echo '<i class="fa fa-user display-inline-block"></i> Employee <strong>' . $row['emp'] . '</strong> Replied  on  <i>'.dateformat_time($row['cdate']).'</i><hr>';
                  
                  echo $row['message'] . '';
                  
                  if ($row['attach']) echo '<strong>Attachment: </strong><a href="' . base_url('userfiles/support/' . $row['attach']) . '" target="_blank">' . $row['attach'] . '</a><br>';
                  ?></div>
            </div>
         </div>
         <?php } ?>
          <form id="ajax-reply-form" enctype="multipart/form-data">
         <h5 class="mt-2"><?php echo $this->lang->line('Your Response') ?></h5>
         <hr>
         <div class="form-group row">
            <div class="col-lg-8 col-md-6 col-sm-12 col-xs-12">
                <label class="col-form-label" for="edate"><?php echo $this->lang->line('Reply') ?></label>
               <textarea class="summernote" placeholder="Message" autocomplete="false" rows="5" name="content"></textarea>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                <label class="col-form-label" for="name">Attach </label>
               <input type="file" name="userfile" class="form-control" size="20"/><br>
               <small>(docx, docs, txt, pdf, xls, png, jpg, gif)</small>
            </div>
            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 submit-section">
               <input type="hidden" class="form-control required" name="thread_id" id="thread_id" value="<?php echo $thread_info['id'] ?>">
               <input type="submit" id="addticket_btn" class="btn btn-lg btn-primary margin-bottom"
                  value="<?php echo $this->lang->line('Update') ?>" data-loading-text="Updating...">
            </div>
         </div>
         </form>
      </div>
   </div>
</article>
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
<div id="pop_model" class="modal fade">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title"><?php echo $this->lang->line('Change Status'); ?></h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
         </div>
         <div class="modal-body">
            <form id="form_model">
               <div class="">
                  <div class="col-xs-12 mb-1">
                     <label for="pmethod"><?php echo $this->lang->line('Mark As') ?></label>
                     <select name="status" id="status" class="form-control mb-1">
                        <option value="Solved"><?php echo $this->lang->line('Solved'); ?></option>
                        <option value="Processing"><?php echo $this->lang->line('Processing'); ?></option>
                        <option value="Waiting"><?php echo $this->lang->line('Waiting'); ?></option>
                     </select>
                  </div>
               </div>
               <div class="modal-footer">
                  <input type="hidden" class="form-control required" name="tid" id="invoiceid" value="<?php echo $thread_info['id'] ?>">
                  <button type="button" class="btn btn-default"
                     data-dismiss="modal"><?php echo $this->lang->line('Close'); ?></button>
                  <!-- <input type="hidden" id="action-url" value="tickets/update_status"> -->
                  <button type="button" class="btn btn-primary chamge-status-btn"
                     ><?php echo $this->lang->line('Change Status'); ?></button>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>

<script>
$(document).ready(function () {
    $('#addticket_btn').on('click', function (e) {
        e.preventDefault();

      //   Swal.fire({
      //       title: 'Are you sure?',
      //       text: 'Do you want to submit this reply?',
      //       icon: 'question',
      //       showCancelButton: true,
      //       confirmButtonText: 'Yes, submit it!',
      //       cancelButtonText: 'Cancel'
      //   }).then((result) => {
      //       if (result.isConfirmed) {
                var form = $('#ajax-reply-form')[0];
                var formData = new FormData(form);

                $.ajax({
                    url: "<?php echo site_url('tickets/submit_reply_ajax'); ?>",
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (res) {
                        let response = JSON.parse(res);
                        if (response.status === 'success') {
                           //  Swal.fire('Success!', response.message, 'success');
                            $('#ajax-reply-form')[0].reset();
                            $('.summernote').summernote('reset');
                            location.reload();
                        } else {
                            Swal.fire('Error!', response.message, 'error');
                        }
                    },
                    error: function () {
                        Swal.fire('Error!', 'Something went wrong.', 'error');
                    }
                });
      //       }
      //   });
    });
    $('.chamge-status-btn').on('click', function (e) {
        e.preventDefault();

        Swal.fire({
            title: 'Are you sure?',
            text: 'Do you Want to Update Ticket Status?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, submit it!',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            focusCancel: true
        }).then((result) => {
            if (result.isConfirmed) {
                var form = $('#form_model')[0];
                var formData = new FormData(form);
                $.ajax({
                    url: "<?php echo site_url('tickets/update_status'); ?>",
                     method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                     success: function(response) {
                        location.reload();
                     },
                     error: function(xhr, status, error) {
                        // Handle error
                        console.error(xhr.responseText);
                     }
               });

            }
        });
    });
});
</script>

