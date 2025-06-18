<!-- ////////////////////////////////////////////////////////////////////////////-->
<div class="app-content content container-fluid">
    <div class="card card-block">
        <div class="content-header row">
        </div>
        <div class="content-body">
            <section class="flexbox-container">
                <!-- Main content -->
                <div class="col-md-12 form f-label">
                    <?php if ($this->session->flashdata("messagePr")) { ?>
                        <div class="alert alert-info">
                            <?php echo $this->session->flashdata("messagePr") ?>
                        </div>
                    <?php } ?>
                    <!-- Profile Image -->
                    <div class="card1 card-block1">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?= base_url('') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo "Profile"; ?></li>
                        </ol>
                    </nav> 
                    <hr>
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                           
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="profile-tab" data-toggle="tab" data-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Profile</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="home-tab" data-toggle="tab" data-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Address</button>
                            </li>
                        </ul>
                        <div class="tab-content" >
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <form method="post" id="address_form" class="form-horizontal container mt-2">
                                    <div class=" row">

                                        <h5><?php echo $this->lang->line('Address') ?></h5>
                                        <hr>
                                        <div class="col-md-6">
                                            <h5><?php echo $this->lang->line('Billing Address') ?></h5>


                                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>"
                                                value="<?php echo $this->security->get_csrf_hash();?>">
                                            <div class="form-group row">

                                                <label class="col-sm-2 col-form-label"
                                                    for="product_name"><?php echo $this->lang->line('Name') ?></label>

                                                <div class="col-sm-10">
                                                    <input type="text" placeholder="Name" class="form-control margin-bottom required"
                                                        name="name" value="<?php echo $customer['name'] ?>" id="mcustomer_name">
                                                </div>
                                            </div>
                                            <div class="form-group row">

                                                <label class="col-sm-2 col-form-label"
                                                    for="product_name"><?php echo $this->lang->line('Company') ?></label>

                                                <div class="col-sm-10">
                                                    <input type="text" placeholder="Company" class="form-control margin-bottom" name="company"
                                                        value="<?php echo $customer['company'] ?>">
                                                </div>
                                            </div>

                                            <div class="form-group row">

                                                <label class="col-sm-2 col-form-label"
                                                    for="phone"><?php echo $this->lang->line('Phone') ?></label>

                                                <div class="col-sm-10">
                                                    <input type="text" placeholder="phone" class="form-control margin-bottom  required"
                                                        name="phone" value="<?php echo $customer['phone'] ?>" id="mcustomer_phone">
                                                </div>
                                            </div>
                                            <div class="form-group row">

                                                <label class="col-sm-2 col-form-label" for="email">Email</label>

                                                <div class="col-sm-10">
                                                    <input type="text" placeholder="email" class="form-control margin-bottom required"
                                                        name="email" value="<?php echo $customer['email'] ?>" id="mcustomer_email" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group row">

                                                <label class="col-sm-2 col-form-label"
                                                    for="product_name"><?php echo $this->lang->line('Address') ?></label>

                                                <div class="col-sm-10">
                                                    <input type="text" placeholder="address" class="form-control margin-bottom" name="address"
                                                        value="<?php echo $customer['address'] ?>" id="mcustomer_address1">
                                                </div>
                                            </div>
                                            <div class="form-group row">

                                                <label class="col-sm-2 col-form-label"
                                                    for="city"><?php echo $this->lang->line('City') ?></label>

                                                <div class="col-sm-10">
                                                    <input type="text" placeholder="city" class="form-control margin-bottom" name="city"
                                                        value="<?php echo $customer['city'] ?>" id="mcustomer_city">
                                                </div>
                                            </div>
                                            <div class="form-group row">

                                                <label class="col-sm-2 col-form-label"
                                                    for="region"><?php echo $this->lang->line('Region') ?></label>

                                                <div class="col-sm-10">
                                                    <input type="text" placeholder="region" class="form-control margin-bottom" name="region"
                                                        value="<?php echo $customer['region'] ?>" id="region">
                                                </div>
                                            </div>
                                            <div class="form-group row">

                                                <label class="col-sm-2 col-form-label"
                                                    for="country"><?php echo $this->lang->line('Country') ?></label>

                                                <div class="col-sm-10">
                                                    <input type="hidden" placeholder="Country" class="form-control margin-bottom" name="country"
                                                        value="<?php echo $customer['country'] ?>" id="mcustomer_country">
                                                    <input type="text" placeholder="Country" class="form-control margin-bottom" name="country_name"
                                                        value="<?php echo $customer['country_name'] ?>" id="mcustomer_country">
                                                </div>
                                            </div>
                                            <div class="form-group row">

                                                <label class="col-sm-2 col-form-label"
                                                    for="postbox"><?php echo $this->lang->line('PostBox') ?></label>

                                                <div class="col-sm-10">
                                                    <input type="text" placeholder="region" class="form-control margin-bottom" name="postbox"
                                                        value="<?php echo $customer['postbox'] ?>" id="postbox">
                                                </div>
                                            </div>
                                            <div class="form-group row">

                                                <label class="col-sm-2 col-form-label" for="postbox"><?php echo $this->lang->line('Tax') ?>
                                                    ID</label>

                                                <div class="col-sm-10">
                                                    <input type="text" placeholder="TAX ID" class="form-control margin-bottom" name="tax_id"
                                                        value="<?php echo $customer['tax_id'] ?>">
                                                </div>
                                            </div>
                                            <div class="form-group row">

                                                <label class="col-sm-2 col-form-label" for="postbox"><?php echo $this->lang->line('Document') ?>
                                                    ID</label>

                                                <div class="col-sm-6">
                                                    <input type="text" placeholder="Document ID" class="form-control margin-bottom b_input"
                                                        name="document_id" value="<?php echo $customer['document_id'] ?>">
                                                </div>
                                            </div>


                                            <div class="form-group row">

                                                <label class="col-sm-2 col-form-label" for="customergroup">Language</label>
                                                <div class="col-sm-6">
                                                    <select name="language" class="form-control b_input">

                                                        <?php echo' <option value="'.$customer['lang'].'">'.$customer['lang'].'</option>' ;
                                                    echo $this->common->languages();
                                                    ?>
                                                    </select>
                                                </div>
                                            </div>


                                        </div>

                                        <div class="col-md-6">
                                            <h5><?php echo $this->lang->line('Shipping Address') ?></h5>
                                            <div class="form-group row">



                                                <div class="col-sm-10">
                                                    <?php echo $this->lang->line("leave Shipping Address") ?>
                                                </div>
                                            </div>

                                            <div class="form-group row">

                                                <label class="col-sm-2 col-form-label"
                                                    for="product_name"><?php echo $this->lang->line('Name') ?></label>

                                                <div class="col-sm-10">
                                                    <input type="text" placeholder="Name" class="form-control margin-bottom" name="shipping_name"
                                                        value="<?php echo $customer['shipping_name'] ?>" id="mcustomer_name_s">
                                                </div>
                                            </div>


                                            <div class="form-group row">

                                                <label class="col-sm-2 col-form-label"
                                                    for="phone"><?php echo $this->lang->line('Phone') ?></label>

                                                <div class="col-sm-10">
                                                    <input type="text" placeholder="phone" class="form-control margin-bottom" name="shipping_phone"
                                                        value="<?php echo $customer['shipping_phone'] ?>" id="mcustomer_phone_s">
                                                </div>
                                            </div>
                                            <div class="form-group row">

                                                <label class="col-sm-2 col-form-label" for="email">Email</label>

                                                <div class="col-sm-10">
                                                    <input type="text" placeholder="email" class="form-control margin-bottom" name="shipping_email"
                                                        value="<?php echo $customer['shipping_email'] ?>" id="mcustomer_email_s">
                                                </div>
                                            </div>
                                            <div class="form-group row">

                                                <label class="col-sm-2 col-form-label"
                                                    for="product_name"><?php echo $this->lang->line('Address') ?></label>

                                                <div class="col-sm-10">
                                                    <input type="text" placeholder="address" class="form-control margin-bottom" name="shipping_address_1"
                                                        value="<?php echo $customer['shipping_address_1'] ?>" id="mcustomer_address1_s">
                                                </div>
                                            </div>
                                            <div class="form-group row">

                                                <label class="col-sm-2 col-form-label"
                                                    for="city"><?php echo $this->lang->line('City') ?></label>

                                                <div class="col-sm-10">
                                                    <input type="text" placeholder="city" class="form-control margin-bottom" name="shipping_city"
                                                        value="<?php echo $customer['shipping_city'] ?>" id="mcustomer_city_s">
                                                </div>
                                            </div>
                                            <div class="form-group row">

                                                <label class="col-sm-2 col-form-label"
                                                    for="region"><?php echo $this->lang->line('Region') ?></label>

                                                <div class="col-sm-10">
                                                    <input type="text" placeholder="region" class="form-control margin-bottom" name="shipping_region"
                                                        value="<?php echo $customer['shipping_region'] ?>" id="shipping_region">
                                                </div>
                                            </div>
                                            <div class="form-group row">

                                                <label class="col-sm-2 col-form-label"
                                                    for="country"><?php echo $this->lang->line('Country') ?></label>

                                                <div class="col-sm-10">
                                                    <input type="hidden" placeholder="Country" class="form-control margin-bottom" name="shipping_country"
                                                        value="<?php echo $customer['shipping_country'] ?>" id="mcustomer_country_s">
                                                    <input type="text" placeholder="Country" class="form-control margin-bottom" name="country_name_s"
                                                        value="<?php echo $customer['country_name'] ?>" id="country_name_s">
                                                </div>
                                            </div>
                                            <div class="form-group row">

                                                <label class="col-sm-2 col-form-label"
                                                    for="postbox"><?php echo $this->lang->line('PostBox') ?></label>

                                                <div class="col-sm-10">
                                                    <input type="text" placeholder="region" class="form-control margin-bottom" name="shipping_postbox"
                                                        value="<?php echo $customer['shipping_postbox'] ?>" id="shipping_postbox">
                                                </div>
                                            </div>


                                        </div>

                                    </div>
                                    <div class="form-group row">

                                        <div class="col-sm-4">
                                            <input type="submit" id="address-update-btn" class="btn btn-lg btn-primary margin-bottom" value="Update"
                                                data-loading-text="Updating...">
                                            <!-- <input type="hidden" value="user/update_address" id="action-url"> -->
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <form method="post" enctype="multipart/form-data"
                                    action="<?php echo base_url() . 'user/add_edit' ?>" class="form-label-left mt-2"><input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
                                    <div class="box-body box-profile">
                                        <div class="col-md-4">
                                            <div class="pic_size" id="image-holder">

                                                <img class="height-200 setpropileam"
                                                    src="../../userfiles/customers/<?php $profile_pic = $user_data[0]->picture;
                                                    echo isset($profile_pic) ? $profile_pic : 'user.png'; ?>"
                                                    alt="User profile picture">
                                            </div>
                                            <br>
                                            <div class="fileUpload btn btn-success wdt-bg">
                                                <span>Change Picture</span>
                                                <input id="fileUpload" class="width-100 upload" name="profile_pic" type="file"
                                                    accept="image/*"/><br/>
                                                <input type="hidden" name="fileOld"  value="<?php echo isset($user_data[0]->profile_pic) ? $user_data[0]->profile_pic : ''; ?>"/>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <h4>Personal Information:</h4>


                                            <hr>


                                            <div class="form-group has-feedback clear-both">

                                                <h5><?php echo(isset($user_data[0]->name) ? $user_data[0]->name : ''); ?></h5>

                                            </div>


                                            <div class="form-group has-feedback clear-both">

                                                <h5><?php echo(isset($user_data[0]->email) ? $user_data[0]->email : ''); ?></h5>
                                            </div>


                                            <hr>
                                            <h5>Change Password:</h5>
                                            <div class="form-group has-feedback">
                                                <label for="exampleInputEmail1">Current Password:</label>
                                                <input id="pass11" class="form-control" pattern=".{6,}" type="password"
                                                    placeholder="********" name="currentpassword" title="6-14 characters">
                                                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                                            </div>
                                            <div class="form-group has-feedback">
                                                <label for="exampleInputEmail1">New Password:</label>
                                                <input type="password" class="form-control" placeholder="New Password"
                                                    name="password">
                                                <span class="glyphicon glyphicon-pencil form-control-feedback"></span>
                                            </div>
                                            <div class="form-group has-feedback">
                                                <label for="exampleInputEmail1">Confirm New Password:</label>
                                                <input type="password" class="form-control" placeholder="Confirm New Password"
                                                    name="confirmPassword">
                                                <span class="glyphicon glyphicon-pencil form-control-feedback"></span>
                                            </div>
                                            <br>
                                            <div class="form-group has-feedback sub-btn-wdt">
                                                <input type="hidden" name="users_id"
                                                    value="<?php echo isset($user_data[0]->users_id) ? $user_data[0]->users_id : ''; ?>">
                                                <input type="hidden" name="user_type"
                                                    value="<?php echo isset($user_data[0]->user_type) ? $user_data[0]->user_type : ''; ?>">
                                                <button name="submit1" type="button" id="profileSubmit"
                                                        class="btn btn-lg btn-primary  wdt-bg">Save
                                                </button>
                                                <!-- <div class=" pull-right">
                                                </div> -->
                                            </div>
                                        </div>
                                        <!-- /.box-body -->
                                    </div>
                                </form>
                            <!-- /.box -->
                            </div>
                            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">...</div>
                        </div>
                    




                       
                        
                    </div>
                    <!-- /.content -->
                </div>
        </div>
        <!-- /.content-wrapper -->

    </div>
</div>
</div>
<script>
$(document).ready(function () {
    $('#profile-tab').click();
    $('#address-update-btn').on('click', function (e) {
    e.preventDefault();

    Swal.fire({
        title: 'Are you sure?',
        text: "Do you want to update your address details?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, update it!',
        cancelButtonText: 'Cancel',
        reverseButtons: true,
        focusCancel: true
    }).then((result) => {
        if (result.isConfirmed) {
            var form = $('#address_form')[0];
            var formData = new FormData(form);

            $.ajax({
                url: '<?php echo site_url('user/update_address'); ?>',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    $('#ajax-msg').text('Submitting...');
                },
                success: function (response) {
                    let res = JSON.parse(response);
                    if (res.status.toLowerCase() === 'success') {                       
                        location.reload();
                    } else {
                        Swal.fire('Error', res.message, 'error');
                    }
                },
                error: function () {
                    Swal.fire('Error', 'An error occurred during submission.', 'error');
                }
            });
        }
    });
});


    
});

</script>