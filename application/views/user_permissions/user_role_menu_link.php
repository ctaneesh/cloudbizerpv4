<div class="card card-block">
    <div id="notify" class="alert alert-success" style="display:none;">
        <a href="#" class="close" data-dismiss="alert">&times;</a>

        <div class="message"></div>
    </div>
    <div class="card-body">
        <h5 class="title"> <?php echo $this->lang->line('User Role - Menu Link') ?> </h5>
        <hr>
        <div class="card card-block formborder">
        <?php
            //  echo "<pre>"; print_r($menus_with_modules); 
            if(!empty($modules))
            {
                foreach ($modules as $key => $module) {
                    echo '<table class="w-100">';
                    echo '<tr>';
                    echo '<td width="30%"><hr></td>';
                    echo '<td width="10%" class="text-center"><b>' . $module['module_name'] . '</b></td>';
                    echo '<td width="30%"><hr></td>';
                    echo '</tr>';
                    echo '</table>';
                
                    $module_number = $module['module_number'];
                    
                    // Check if there are menus for this module
                    if (isset($menus_with_modules[$module_number])) {
                        echo '<div class="row mt-2">';
                        foreach ($menus_with_modules[$module_number] as $key => $value) {
                            $menunumber = $value['menu_number'];
                            echo '<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 mt-2 mb-2">
                            <div class="module-dashboard">
                                <h4>
                                    <input type="checkbox" id="'.$value['menu_label'].'" name="'.$value['menu_label'].'" >
                                    <label class="col-form-label1" for="'.$value['menu_label'].'">'.$value['menu_label'].'</label>
                                </h4>';
                                echo '<ul>';
                                // [menu_number] => 1001
                                // [menu_label] => New Customer
                                // [function_name] => Create
                                // [parent_menu_id] => 1000
                                foreach ($all_menus[$menunumber] as $key => $item1) {
                                    if($item1['parent_menu_id'])
                                    {
                                    echo '<li>';
                                        echo '<input type="checkbox" id="'.$item1['menu_label'].'" name="'.$item1['menu_label'].'" class="moduleclass1">';
                                        echo '<label for="'.$item1['menu_label'].'">'.$item1['menu_label'].'</label>';
                                    echo '</li>';
                                    }
                                }
                                echo '</ul>';
                            echo '</div>
                        </div>';
                            
                        }
                        echo '</div>';
                    } 
                }
                
            }
            ?>
        </div>        
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#module_number").select2({
            placeholder: "Type Module", 
            allowClear: true,
            width: '100%'
        });   
        $("#parent_menu_id").select2({
            placeholder: "Select Parent Menu", 
            allowClear: true,
            width: '100%'
        });   
        $("#data_form").validate({
            ignore: [],
            rules: {               
                menu_name: { required: true },
                menu_label: { required: true },
                module_number: { required: true }
                // module_number: { required: true }
            },
            messages: {
                menu_name: "Enter Menu Name",
                menu_label: "Enter Menu Label",
                module_number: "Select Module"
            },
            errorElement: "em",
            errorPlacement: function(error, element) {
                error.addClass("help-block");
                if (element.prop("type") === "checkbox") {
                    error.insertAfter(element.parent("label"));
                } else {
                    error.insertAfter(element);
                }
            },
            highlight: function(element, errorClass, validClass) {
                $(element).parents(".col-sm-5").addClass("has-error").removeClass("has-success");
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).parents(".col-sm-5").addClass("has-success").removeClass("has-error");
            },
            invalidHandler: function(event, validator) {
                // Focus on the first invalid element
                if (validator.errorList.length) {
                    $(validator.errorList[0].element).focus();
                }
            }
        });
        //datatables
        $('#catgtable').DataTable({responsive: true});

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
                text: "Do you want to create a new menu?",
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
                            // location.reload();
                            
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


</script>