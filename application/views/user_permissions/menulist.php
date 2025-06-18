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
        <h4 class="card-title"> <?php echo $this->lang->line('Menu Management') ?> </h4>
        <hr>
        <div class="card card-block formborder">
            <form method="post" id="data_form" class="form-horizontal">
                <div class="form-group row">             
                    
                    <div class="col-lg-2 col-md-3 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="address"><?php echo $this->lang->line('Main Menu') ?><span class="compulsoryfld">*</span></label>
                         <select name="main_menu" id="main_menu" class="form-control">
                            <option value=""><?=$this->lang->line('Main Menu')?></option>
                            <?php
                                if(!empty($modules))
                                {
                                    foreach($modules as $row){
                                        echo '<option value="'.$row['module_name'].'">'.$row['module_name'].'</option>';
                                    }
                                }
                            ?>
                        </select>
                    </div>

                    <div class="col-lg-2 col-md-3 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="name"><?php echo $this->lang->line('Sub Menu1') ?></label>                      
                        <select name="submenu1" id="submenu1" class="form-control"></select>
                        <input type="hidden" name="submenu1text" id="submenu1text">
                        <input type="hidden" name="menu_id" id="menu_id" value="0">
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="address"><?php echo $this->lang->line('Sub Menu2') ?></label>
                        <input type="text" placeholder="<?php echo $this->lang->line('Sub Menu2') ?>" class="form-control margin-bottom" name="submenu2" id="submenu2">
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="address"><?php echo $this->lang->line('Menu Details') ?></label>
                        <input type="text" placeholder="<?php echo $this->lang->line('Menu Details') ?>" class="form-control margin-bottom" name="menu_detail" id="menu_detail">
                    </div>
                    <div class="col-lg-2 col-md-3 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="address"><?php echo $this->lang->line('Function Name') ?></label>
                        <input type="text" placeholder="Eg: Create,Edit,Delete" class="form-control margin-bottom" name="function" id="function">
                    </div>
                    <div class="col-lg-1 col-md-3 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="status"><?php echo $this->lang->line('Status') ?></label>
                        <select name="status" id="status" class="form-control margin-bottom">
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>

                    

                    
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 mt-2">
                        <input type="submit" id="menu-btn" class="btn btn-crud btn-primary  margin-bottom"
                            value="<?php echo $this->lang->line('Add') ?>" data-loading-text="Adding...">
                        
                        <input type="submit" id="menu-btn1" class="btn btn-crud btn-primary  margin-bottom d-none"
                            value="<?php echo $this->lang->line('Delete') ?>" data-loading-text="Adding...">
                        
                            <!-- <input type="hidden" value="productpricing/edit" id="action-url"> -->
                        <!-- <input type="hidden" value="<?php echo $id ?>" name="id"> -->
                    </div>
                </div>
            </form>
        </div>

        <br>
        <table id="catgtable" class="table table-striped table-bordered zero-configuration" cellspacing="0"
               width="100%">
            <thead>
            <tr>
                <th>#</th>
                <th><?php echo $this->lang->line('Main Menu') ?></th>
                <th><?php echo $this->lang->line('Sub Menu1') ?></th>
                <th><?php echo $this->lang->line('Sub Menu2') ?></th>
                <th><?php echo $this->lang->line('Menu Details') ?></th>
                <th><?php echo $this->lang->line('Function Name') ?></th>
                <th><?php echo $this->lang->line('Status') ?></th>
                <th><?php echo $this->lang->line('Action') ?></th>


            </tr>
            </thead>
            <tbody>
            <?php
            $i = 1;
           
            foreach ($details as $row) {
                $menu_id = $row->menu_id;
                echo "<tr>
                    <td>$i</td>
                    <td>$row->main_menu</td>
                    <td>$row->submenu1</td>
                    <td>$row->submenu2</td>
                    <td>$row->menu_detail</td>                 
                    <td>$row->function</td>                 
                    <td>$row->status</td>                 
                    <td><button onclick='update_menu($menu_id)' class='btn btn-crud btn-secondary btn-sm' title='Edit'><i class='fa fa-pencil'></i></button></td></tr>";
                $i++;
                // <td><a href='" . base_url("productpricing/edit?id=$cid") . "' class='btn btn-secondary btn-sm' title='Edit'><i class='icon-pencil'></i></a>&nbsp;</td></tr>";
            }
            ?>
            </tbody>
           
        </table>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#main_menu").select2({
            placeholder: "Type Main Menu", 
            allowClear: true,
            width: '100%'
        });   
        $("#submenu1").select2({
            
            placeholder: "Type Submenu1", 
            allowClear: true,
            width: '100%',
            tags: true,
        });   
        $("#data_form").validate($.extend(true, {}, globalValidationOptions,{
            ignore: [],
            rules: {               
                main_menu: { required: true },
            },
            messages: {
                main_menu: "Enter Main Menu Name ",
            }
        }));
        //datatables
        $('#catgtable').DataTable({responsive: false});

    });

    $('#menu-btn').on('click', function(e) {
        e.preventDefault(); // Prevent the default form submission
        $('#menu-btn').prop('disabled', true);
      
        // Validate the form
        if ($("#data_form").valid()) {                
            var form = $('#data_form')[0];
            var formData = new FormData(form); 
            Swal.fire({
                title: "Are you sure?",
                text: "Do you want to create/update menu?",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, proceed!',
                cancelButtonText: "No - Cancel",
                reverseButtons: true,  
                focusCancel: true,      
                allowOutsideClick: false,  // Disable outside click
            }).then((result) => {
                if (result.isConfirmed) {
                    
                    $.ajax({
                        url: baseurl + 'menus/addeditaction', 
                        type: 'POST',
                        data: formData,
                        contentType: false, 
                        processData: false,
                        success: function(response) {
                            if (typeof response === "string") {
                                response = JSON.parse(response);
                            }
                            location.reload();
                            
                        },
                        error: function(xhr, status, error) {
                            Swal.fire('Error', 'An error occurred while generating the lead', 'error');
                            console.log(error); // Log any errors
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    // Enable the button again if user cancels
                    $('#menu-btn').prop('disabled', false);
                }
            });
        } else {
            // If form validation fails, re-enable the button
            $('#menu-btn').prop('disabled', false);
        }
    });

    $('#menu-btn1').on('click', function(e) {
        e.preventDefault(); // Prevent the default form submission
        $('#menu-btn1').prop('disabled', true);
      
        // Validate the form
        if ($("#data_form").valid()) {                
            var form = $('#data_form')[0];
            var formData = new FormData(form); 
            Swal.fire({
                title: "Are you sure?",
                text: "Do You Want to Delete this Menu? This will Delete Access to this Menu for Every User",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, proceed!',
                cancelButtonText: "No - Cancel",
                reverseButtons: true,  
                focusCancel: true,      
                allowOutsideClick: false,  // Disable outside click
            }).then((result) => {
                if (result.isConfirmed) {
                    
                    $.ajax({
                        url: baseurl + 'menus/deleteaction', 
                        type: 'POST',
                        data: formData,
                        contentType: false, 
                        processData: false,
                        success: function(response) {
                            if (typeof response === "string") {
                                response = JSON.parse(response);
                            }
                            location.reload();
                            
                        },
                        error: function(xhr, status, error) {
                            Swal.fire('Error', 'An error occurred while generating the lead', 'error');
                            console.log(error); // Log any errors
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    // Enable the button again if user cancels
                    $('#menu-btn1').prop('disabled', false);
                }
            });
        } else {
            // If form validation fails, re-enable the button
            $('#menu-btn1').prop('disabled', false);
        }
    });

    $("#main_menu").on('change', function () {
        selectedValue="";
        $.ajax({
            type: 'POST',
            url: baseurl + 'menus/load_menus_by_mainmenu',
            data: {
                "main_menu": $("#main_menu").val()
            },
            success: function (response) {
                var responseData = JSON.parse(response);

                // Check if responseData.data exists and is not empty
                if (Array.isArray(responseData.data) && responseData.data.length > 0) {
                    let options = '<option value="">Select an option</option>'; // Default empty option

                    selectedValue = $("#submenu1text").val();

                    // Loop through the array and skip null/invalid values
                    $.each(responseData.data, function (index, item) {
                        if (item && item.submenu1) { // Skip null or undefined items
                            var sel = (item.submenu1 === selectedValue) ? "selected" : ""; // Check if this option should be selected
                            options += '<option value="' + item.submenu1 + '" ' + sel + '>' + item.submenu1 + '</option>';
                        }
                    });

                    // Replace the content of #submenu1
                    $("#submenu1").html(options);
                } else {
                    // If data is empty, show only the default option
                    $("#submenu1").html('<option value="">No options available</option>');
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });



    function update_menu(id) {
        $.ajax({
            type: 'POST',
            url: baseurl + 'menus/load_menu_by_menuid',
            data: {
                "menu_id": id
            },
            success: function(response) {
                var responseData = JSON.parse(response);
                var resultdata = responseData.data;

                $("#menu-btn").val("Update");
                $("#main_menu").focus();
                $("#menu-btn1").removeClass('d-none');
                $("#submenu1text").val(resultdata.submenu1);
                $("#menu_id").val(resultdata.menu_id);
                $("#submenu2").val(resultdata.submenu2);
                $("#menu_detail").val(resultdata.menu_detail);
                $("#function").val(resultdata.function);
                $("#status").val(resultdata.status);
                $("#main_menu").val(resultdata.main_menu).trigger('change');

            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }
    function delete_menu(id) {
        $.ajax({
            type: 'POST',
            url: baseurl + 'menus/load_menu_by_menuid',
            data: {
                "menu_id": id
            },
            success: function(response) {
                var responseData = JSON.parse(response);
                var resultdata = responseData.data;

                $("#menu-btn").val("Update");
                $("#main_menu").focus();
                
                $("#submenu1text").val(resultdata.submenu1);
                $("#menu_id").val(resultdata.menu_id);
                $("#submenu2").val(resultdata.submenu2);
                $("#menu_detail").val(resultdata.menu_detail);
                $("#function").val(resultdata.function);
                $("#status").val(resultdata.status);
                $("#main_menu").val(resultdata.main_menu).trigger('change');

            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }


</script>