<article class="content-body">
<?php       
  if (($msg = check_permission($permissions)) !== true) {
     echo $msg;
     return;
  }
 ?>
    <div class="card card-block">
            
           
        <div id="notify" class="alert alert-success" style="display:none;">
            <a href="#" class="close" data-dismiss="alert">&times;</a>

            <div class="message"></div>
        </div>
        <div class="card-body">

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard') ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('manufacturers') ?>"><?php echo $this->lang->line('Manufacturers') ?></a></li>
                </ol>
            </nav>
            <form method="post" id="data_form" class="form-horizontal">              
                <h5><?php echo $this->lang->line('Manufacturer') ?></h5>
                <hr>
                <input type="hidden"  name="manufacturer_id" autocomplete="off" value="<?=$details['manufacturer_id']?>">
                <div class="form-group row">
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="manufacturer_name"><?php echo $this->lang->line('Manufacturer Name') ?>
                            <span class="compulsoryfld">*</span></label>
                            <input type="text" placeholder="Manufacturer Name" class="form-control margin-bottom" name="manufacturer_name" autocomplete="off" value="<?=$details['manufacturer_name']?>">
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="mfg_code"><?php echo $this->lang->line('Manufacturer Code') ?>
                            <span class="compulsoryfld">*</span></label>
                            <input type="text" placeholder="Manufacturer Code" class="form-control margin-bottom" name="mfg_code" autocomplete="off" value="<?=$details['mfg_code']?>">
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="mfg_email"><?php echo $this->lang->line('Email') ?>
                            <span class="compulsoryfld">*</span></label>
                            <input type="text" placeholder="Manufacturer Email" class="form-control margin-bottom" name="mfg_email" autocomplete="off" value="<?=$details['mfg_email']?>">
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="mfg_email2"><?php echo $this->lang->line('Additional Email') ?>
                            </label>
                            <input type="text" placeholder="Manufacturer Email" class="form-control margin-bottom" name="mfg_email2" autocomplete="off" value="<?=$details['mfg_email2']?>">
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="mfg_phone1"><?php echo $this->lang->line('Phone') ?><span class="compulsoryfld">*</span>
                            </label>
                            <input type="text" placeholder="Manufacturer Phone" class="form-control margin-bottom" name="mfg_phone1" autocomplete="off" value="<?=$details['mfg_phone1']?>">
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="mfg_phone2"><?php echo $this->lang->line('Additional Phone') ?>
                            </label>
                            <input type="text" placeholder="Manufacturer Phone" class="form-control margin-bottom" name="mfg_phone2" autocomplete="off" value="<?=$details['mfg_phone2']?>">
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="mfg_country"><?php echo $this->lang->line('Country') ?>
                            </label>
                            <input type="text" placeholder="Country" class="form-control margin-bottom" name="mfg_country" autocomplete="off" value="<?=$details['mfg_country']?>">
                    </div>

               
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="mfg_region"><?php echo $this->lang->line('Region') ?>
                            </label>
                            <input type="text" placeholder="Region" class="form-control margin-bottom" name="mfg_region" autocomplete="off" value="<?=$details['mfg_region']?>">
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="mfg_region"><?php echo $this->lang->line('City') ?>
                            </label>
                            <input type="text" placeholder="City" class="form-control margin-bottom" name="mfg_city" autocomplete="off" value="<?=$details['mfg_city']?>">
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="mfg_postbox"><?php echo $this->lang->line('Postbox') ?>
                            </label>
                            <input type="text" placeholder="Postbox" class="form-control margin-bottom" name="mfg_postbox" autocomplete="off" value="<?=$details['mfg_postbox']?>">
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="mfg_address"><?php echo $this->lang->line('Address') ?>
                            </label>
                            <textarea name="mfg_address" id="mfg_address" class="form-textarea" placeholder="Address"><?=$details['mfg_address']?></textarea>
                    </div>
                    <!-- <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                        <label class="col-form-label"
                           for="product_catname"><?php echo $this->lang->line('Status') ?></label>
                        <select name="status" id="status" class="form-control">
                            <option value="Enable">Enable</option>
                            <option value="Disable">Disable</option>
                        </select>
                    </div> -->
                    <div class="col-12 text-right mt-2"><hr>
                        <input type="submit" id="add_manf_btn" class="btn btn-primary btn-lg margin-bottom btn-crud"
                               value="<?php echo $this->lang->line('Save'); ?>" data-loading-text="Adding...">
                    </div>
                </div>


            </form>
        </div>
    </div>
</article>

<script>
$(document).ready(function() {
       $("#data_form").validate($.extend(true, {}, globalValidationOptions,{
            rules: {
                manufacturer_name: {required:true},
                mfg_code: {required:true},
                mfg_email: {required:true},
                mfg_phone1: {
                        required:true,
                        phoneRegex :true
                    },
            },
            messages: {
                manufacturer_name    : "Enter Manufacturer Name",
                mfg_code             : "Enter Manufacturer Code",
                mfg_email            : "Enter Email",
                mfg_phone1           : "Enter Valid Number",
            }
        }));
});
$("#add_manf_btn").on("click", function(e) {
    e.preventDefault();
    $('#add_manf_btn').prop('disabled', true);

    if ($("#data_form").valid()) {
      Swal.fire({
            title: "Are you sure?",
            // text: "Are you sure you want to update inventory? Do you want to proceed?",
            "text":"Do you want to create new Manufacturer?",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, proceed!',
            cancelButtonText: "No - Cancel",
            reverseButtons: true,
            focusCancel: true
      }).then((result) => {
            if (result.isConfirmed) {
               var formData = $("#data_form").serialize(); 
               $.ajax({
                  type: 'POST',
                  url: baseurl +'manufacturers/action',
                  data: formData,
                  success: function(response) {
                     window.location.href = baseurl + 'manufacturers'; 
                  },
                  error: function(xhr, status, error) {
                        // Handle error
                        console.error(xhr.responseText);
                  }
               });
            }
            else{
               $('#add_manf_btn').prop('disabled', false);
            }
      });
    }
    else{
      $('#add_manf_btn').prop('disabled', false);
    }
});
</script>