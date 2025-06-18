<?php       
  if (($msg = check_permission($permissions)) !== true) {
     echo $msg;
     return;
  }
 ?>
<div class="content-body">
   <div class="card">
      <div class="card-header">
         <h4 class="card-title"><?php echo $this->lang->line('Edit Company Details') ?></h4>
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
               <div class="col-12">
                  <form method="post" id="product_action" class="form-horizontal">
                     <input type="hidden" name="id" value="<?php echo $company['id'] ?>">
                     <div class="form-group row">
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label"
                           for="name"><?php echo $this->lang->line('Company Name') ?></label>
                           <input type="text" placeholder="Name"
                              class="form-control margin-bottom  required" name="name"
                              value="<?php echo $company['cname'] ?>">
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label"
                           for="address"><?php echo $this->lang->line('Address') ?></label>
                           <input type="text" placeholder="address"
                              class="form-control margin-bottom  required" name="address"
                              value="<?php echo $company['address'] ?>">
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label"
                           for="city"><?php echo $this->lang->line('City') ?></label>
                        
                           <input type="text" placeholder="city"
                              class="form-control margin-bottom  required" name="city"
                              value="<?php echo $company['city'] ?>">
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label"
                           for="city"><?php echo $this->lang->line('Region') ?></label>
                        
                           <input type="text" placeholder="city"
                              class="form-control margin-bottom  required" name="region"
                              value="<?php echo $company['region'] ?>">
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label"
                           for="country"><?php echo $this->lang->line('Country') ?></label>
                        
                           <input type="text" placeholder="Country"
                              class="form-control margin-bottom  required" name="country"
                              value="<?php echo $company['country'] ?>">
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label"
                           for="postbox"><?php echo $this->lang->line('PostBox') ?></label>
                        
                           <input type="text" placeholder="PostBox"
                              class="form-control margin-bottom  required" name="postbox"
                              value="<?php echo $company['postbox'] ?>">
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label"
                           for="phone"><?php echo $this->lang->line('Phone') ?></label>
                        
                           <input type="text" placeholder="phone"
                              class="form-control margin-bottom  required" name="phone"
                              value="<?php echo $company['phone'] ?>">
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label"
                           for="email"><?php echo $this->lang->line('Email') ?></label>
                        
                           <input type="text" placeholder="email"
                              class="form-control margin-bottom  required" name="email"
                              value="<?php echo $company['email'] ?>">
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label"
                           for="email"><?php echo $this->lang->line('Tax') ?> ID </label>
                        
                           <input type="text" placeholder="TAX ID"
                              class="form-control margin-bottom" name="tax_id"
                              value="<?php echo $company['tax_id'] ?>">
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                           <label class=" col-form-label"
                              for="data_share">Product Data Sharing with Other
                           Locations</label>
                           <select name="data_share" class="form-control">
                              <?php switch (BDATA) {
                                 case '1' :
                                     echo '<option value="1">** Yes **</option>';
                                     break;
                                 case '0' :
                                     echo '<option value="0">** No **</option>';
                                     break;
                                 
                                 } ?>
                              <option value="1">Yes</option>
                              <option value="0">No</option>
                           </select>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                           <label class="col-form-label"
                              for="data_share"><?php echo $this->lang->line('Foundation Day') ?> </label> 
                           <div class="input-group">
                              <div class="input-group-addon"><span class="icon-calendar4"
                                 aria-hidden="true"></span></div>
                              <input type="text" class="form-control required editdate"
                                 placeholder="Foundation Date" name="foundation"
                                 autocomplete="false" value="<?php echo dateformat($company['foundation']) ?>">
                           </div>
                        </div>
                       
                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12 mt-2">
                           <label class="col-form-label"
                              for="data_share"><?php echo $this->lang->line('Company Logo') ?> </label> 
                           <input type="hidden" name="id" value="<?php echo $company['id'] ?>">
                           <div class="ibox-content no-padding border-left-right">
                              <img alt="image" id="dpic" class="col" style="max-width: 200px"
                                 src="<?php echo base_url('userfiles/company/') . $company['logo'] . '?t=' . rand(5, 99); ?>">
                              <div id="errorImg"></div>
                           </div>
                           <hr>
                           <p>
                              <label for="fileupload"><?php echo $this->lang->line('Change Company Logo') ?></label><input
                                 id="fileupload" type="file"
                                 name="files[]">
                           </p>
                           <pre>Recommended logo size is 500x300px.</pre>
                           <div id="progress" class="progress progress-sm mt-1 mb-0">
                              <div class="progress-bar bg-success" role="progressbar" style="width: 0%"
                                 aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                           </div>
                        </div>

                        <div class="col-12 submit-section mt-2 text-right">
                           <input type="submit" id="company_update" class="btn btn-crud btn-primary btn-lg margin-bottom" value="<?php echo $this->lang->line('Update Company') ?>" data-loading-text="Updating...">
                        </div>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>


<script src="<?php echo assets_url('assets/myjs/jquery.ui.widget.js') ?>"></script>
<script src="<?php echo assets_url('assets/myjs/jquery.fileupload.js') ?>"></script>
<script type="text/javascript">
  
   $(function () {
       'use strict';
       var url = '<?php echo base_url() ?>settings/companylogo?id=<?php echo $company['id'] ?>';
       $('#fileupload').fileupload({
           url: url,
           dataType: 'json',
           formData: {'<?=$this->security->get_csrf_token_name()?>': crsf_hash},
           done: function (e, data) {
   if(data.result.error) {
   	$('#errorImg').html('<span class="alert alert-danger">'+data.result.error+'</span>');
   
   } else if(data.result.url) {
   	$('#errorImg').html('');
   	$("#dpic").attr('src', '<?php echo base_url() ?>userfiles/company/' + data.result.name + '?' + new Date().getTime());
   }
   
           },
           progressall: function (e, data) {
               var progress = parseInt(data.loaded / data.total * 100, 10);
               $('#progress .progress-bar').css(
                   'width',
                   progress + '%'
               );
           }
       }).prop('disabled', !$.support.fileInput)
           .parent().addClass($.support.fileInput ? undefined : 'disabled');
   });
   $('.editdate').datepicker({
      autoHide: true,
      format: '<?php echo $this->config->item('dformat2'); ?>'
   });

    $("#company_update").on("click", function (e) {
        e.preventDefault();
        $('#company_update').prop('disabled', true);
        if ($("#product_action").valid()) {
            var formData = new FormData($("#product_action")[0]);
            Swal.fire({
               title: "Are you sure?",
               text: "Do you want to update this company?",
               icon: "question",
               showCancelButton: true,
               confirmButtonColor: '#3085d6',
               cancelButtonColor: '#d33',
               confirmButtonText: 'Yes, proceed!',
               cancelButtonText: "No - Cancel",
               reverseButtons: true, 
               focusCancel: true,
               allowOutsideClick: false,
            }).then((result) => {
               if (result.isConfirmed) {  
                    $.ajax({
                        type: 'POST',
                        url: baseurl + 'settings/company',
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: "json",
                        success: function(response) {
                            window.location.href = baseurl + 'settings/company';
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
               }
               else{
                    $('#company_update').prop('disabled', false);
               }
            });
        }
        else {
            $('#company_update').prop('disabled', false);
        }
    });
</script>