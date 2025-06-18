<div class="card card-block">
    <div id="notify" class="alert alert-success" style="display:none;">
        <a href="#" class="close" data-dismiss="alert">&times;</a>

        <div class="message"></div>
    </div>
    <div class="card-body">
        <h4 class="card-title"> <?php echo $this->lang->line('Role Premissions') ?> </h4>
        <hr>
        <div class="card card-block formborder">

            
            <form method="post" id="data_form" class="form-horizontal">
                <div class="form-group row">
                    <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="name"><?php echo $this->lang->line('Role Name') ?><span
                                class="compulsoryfld">*</span></label>
                        <input type="hidden" id="old_role" value="<?=$role_id?>">
                        <select name="role_id" id="role_id" class="form-control form-select">
                            <?php
                                if($roles)
                                {
                                    
                                    foreach($roles as $row)
                                    {
                                        
                                        $sel ="";
                                        if($role_id == $row['role_id'])
                                        {
                                            $sel ="selected";
                                        }
                                        echo '<option value="'.$row['role_id'].'" '.$sel.'>'.$row['role_name'].'</option>';
                                    }
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <!-- ======================================== -->
                <?php
                    if($linked_modules_by_roleid)
                    {
                        $permission_active = " active show";
                        $module_active="";
                        $permission_active_data=" show active";
                        $module_active_data = "";
                    }
                    else{
                        $module_active = " active show";
                        $permission_active="";
                        $module_active_data = " show active";
                        $permission_active_data="";
                    }
                ?>
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item mr-1" role="presentation">
                        <button class="btn btn-crud nav-link <?=$module_active?>" id="pills-home-tab" data-toggle="pill" data-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true"><?php echo $this->lang->line('Modules') ?></button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="btn btn-crud nav-link <?=$permission_active?>" id="pills-profile-tab" data-toggle="pill" data-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false"><?php echo $this->lang->line('Premissions') ?></button>
                    </li>            
                </ul>
                <!-- ======================================== -->
                <div class="tab-content" id="pills-tabContent">
                    <!-- ======================================================== -->
                    <div class="tab-pane fade <?php echo $module_active_data; ?>" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                        <div class="col-lg-3 row">
                            <div class="module-dashboard">
                                <h4>
                                    <input type="checkbox" id="Modules" name="Modules" >
                                    <label class="col-form-label1" for="Modules"><?php echo $this->lang->line('Modules') ?></label>
                                </h4>
                                <ul>
                                    <?php 
                                    echo "<pre>";
                                    
                                    $mainmenu = array_column($linked_modules_by_roleid, 'main_menu');

                                    if(!empty($modules))
                                    {
                                        
                                        foreach($modules as $module)
                                        {
                                            $module_number = $module['module_number'];
                                            $module_name = $module['module_name'];
                                            $checked = (in_array($module_name, $mainmenu)) ? "checked":"";
                                            echo '<li>';
                                            echo '<input type="checkbox" data-id="'.$module_number.'" id="'.$module_name.'" name="'.$module_name.'" class="moduleclass" value="'.$module_name.'" '.$checked.'>';
                                            echo '<label for="'.$module_name.'">'.$module_name.'</label>';
                                            echo '</li>';
                                        }
                                    }
                                    ?>
                                   
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- ======================================================== -->

                    <!-- ======================================================== -->
                    <div class="tab-pane fade <?=$permission_active_data?>" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                        <?php
                            // echo "<pre>"; print_r($mainarray);
                            echo '<h4 class="col-12">';         
                            echo '<input type="checkbox" id="checkallmodules"  class="check-all-module"> &nbsp;';                               
                            echo '<label class="col-form-label" for="checkallmodules">'.$this->lang->line('Check All Modules').'</label>
                            </h4>'; 
                            // Loop through main menus

                            // Loop through main menus
                            $approval_modules = array_column($linked_approvals, 'module_id');
                            $module_names = array_column($modules, 'module_name');
                            foreach ($mainarray as $main_menu => $submenu1Array) {
                                $mainmenu_class =  str_replace(' ', '_', $main_menu);                                
                                $main_module = $mainmenu_class."-Module";
                                $module_number = 0;
                                if (in_array($main_menu, $module_names)) {
                                    $index = array_search($main_menu, $module_names);   
                                    $module_number = $modules[$index]['module_number'];          
                                }
                                echo '<table class="w-100 mainmodule '.$mainmenu_class.'">';
                                echo '<tr>';
                                echo '<td width="30%"><hr></td>';
                                echo '<td width="10%" class="text-center"><b>' . $main_menu . '</b></td>';
                                echo '<td width="30%"><hr></td>';
                                echo '</tr>';
                                echo '</table>';
                                
                                echo '<h4 class="col-12 mainmodule '.$mainmenu_class.'">';         
                                echo '<input type="checkbox" id="'.$main_module.'" name="'.$mainmenu_class.'"  class="main-module checkallmodules" data-id="'.$main_module.'"> &nbsp;';                               
                                echo '<label class="col-form-label" for="'.$main_module.'">Check All('.$main_menu.')</label>
                                </h4>'; 
                                echo '<div class="row permissions '.$mainmenu_class.'">';
                                $index1 = "zz";
                                if (in_array($module_number, $approval_modules)) {
                                    $index1 = array_search($module_number, $approval_modules); 
                                }

                                if ($index1!='zz') {
                                    // $index1 = array_search($module_number, $approval_modules); 
                                    $first_level_approval_checked = ($linked_approvals[$index1]['first_level_approval']=='Yes') ? 'checked' : '';          
                                    $second_level_approval_checked = ($linked_approvals[$index1]['second_level_approval']=='Yes') ? 'checked' : '';            
                                    $third_level_approval_checked = ($linked_approvals[$index1]['third_level_approval']=='Yes') ? 'checked' : '';          
                                }
                                else{

                                    $first_level_approval_checked="";
                                    $second_level_approval_checked = "";
                                    $third_level_approval_checked = "";
                                }
                                $flg=0;
                                // Loop through submenu1
                                foreach ($submenu1Array as $submenu1 => $submenuData) {
                                    if($flg==0)
                                    {                                          
                                        
                                        // $mainfunction_checked = (in_array($top_menu_id, $linked_menu_ids)) ? "checked" : "";
                                        echo '<div class="col-12 ">';
                                        echo '<label class="col-12 col-form-label w-100">'.$this->lang->line('Approval Levels').'</label>';
                                        echo '<div class="mt-1 col-12">';
                                        
                                        echo '<div class="form-check form-check-inline">';
                                        echo '<input class="form-check-input main-permission-level checkallmodules '.$main_module.'" type="checkbox" name="'.$module_number.'_first_level_approval" id="'.$module_number.'_first_level_approval" value="Yes" '.$first_level_approval_checked.'>';
                                        echo '<label class="form-check-label label-size" for="'.$module_number.'_first_level_approval">'.$this->lang->line('First Level').'</label>';
                                        echo '</div>';
                                        
                                        echo '<div class="form-check form-check-inline">';
                                        echo '<input class="form-check-input main-permission-level checkallmodules '.$main_module.'" type="checkbox" name="'.$module_number.'_second_level_approval" id="'.$module_number.'_second_level_approval" value="Yes" '.$second_level_approval_checked.'>';
                                        echo '<label class="form-check-label label-size" for="'.$module_number.'_second_level_approval">'.$this->lang->line('Second Level').'</label>';
                                        echo '</div>';

                                        

                                        echo '<div class="form-check form-check-inline">';
                                        echo '<input class="form-check-input main-permission-level checkallmodules '.$main_module.'" type="checkbox" name="'.$module_number.'_third_level_approval" id="'.$module_number.'_third_level_approval" value="Yes" '.$third_level_approval_checked.'>';
                                        echo '<label class="form-check-label label-size" for="'.$module_number.'_third_level_approval">'.$this->lang->line('Third Level') .'</label>';
                                        echo '</div>';
                                        echo '</div>';
                                        echo '</div>';
                                        $flg=1;
                                    }
                                    // if (!is_array($submenuData)) continue; // Skip if not an array
                                    $menu_ids = implode(', ', $submenuData['menu_id']);
                                    // echo '<input type="text" name="main_submenu[]"  value="'.$menu_ids.'">';
                        
                                    // Print functions under submenu1
                                    // foreach ($submenuData['functions'] as $function) {
                                    //     echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$function - $menu_ids<br>";
                                    // }
                        
                                    // Check for submenu2 in subarray
                                
                                        if (isset($subarray[$main_menu][$submenu1])) {
                                            
                                            $empty_index="";
                                            foreach ($subarray[$main_menu][$submenu1] as $submenu2 => $submenu2Data) {
                                                if(empty($submenu2Data['functions']))
                                                {
                                                    $empty_index=$submenu2;
                                                }
                                                $submenu2_ids = implode(', ', $submenu2Data['menu_id']);
                                                // $lastMenuId = end($submenu2Data['menu_id']);
                                                echo '<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 mt-2 mb-2">';
                                                echo '<div class="module-dashboard">';
                                                $emptyIndex = array_search("", $submenu2Data['functions']);
                                                $top_menu_id = $submenu2Data['menu_id'][$emptyIndex];
                                                
                                                $linked_menu_ids = array_column($linked_menus, 'menu_link_id');
                                                $mainfunction_checked = (in_array($top_menu_id, $linked_menu_ids)) ? "checked" : ""; 
                                                $mainpageval = str_replace(' ', '_', $submenu2)."_".$top_menu_id;
                                                echo '<h4>';                               
                                                echo '<input type="checkbox" id="'.$top_menu_id.'" name="'.$submenu1.'" value="'.$top_menu_id.'" class="menuClass main-page-label checkallmodules '.$main_module.'" data-id="'.$mainpageval.'" '.$mainfunction_checked.'>';                               
                                                echo '<label class="col-form-label1" for="'.$top_menu_id.'">'.$submenu1.'/'.$submenu2.'</label>
                                                </h4>';
                                                echo '<ul>';
                                                // Print functions under submenu2
                                                foreach ($submenu2Data['functions'] as $index => $subFunction) {
                                                    // echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$subFunction - {$submenu2Data['menu_id'][$index]}<br>";
                                                    if($subFunction)
                                                    {
                                                        $submenu_label = $submenu2Data['menu_id'][$index];
                                                        $linked_menu_ids = array_column($linked_menus, 'menu_link_id');
                                                        $sbufunction_checked = (in_array($submenu_label, $linked_menu_ids)) ? "checked" : "";

                                                        echo '<li>';
                                                        echo '<input type="checkbox" id="'.$submenu_label.'" name="'.$subFunction.'" class="menuClass checkallmodules '.$main_module.' '.$mainpageval.'" value="'.$submenu2Data['menu_id'][$index].'" '.$sbufunction_checked.'>';
                                                        echo '<label for="'.$submenu_label.'" >'.$subFunction.'</label>';
                                                        echo '</li>';
                                                    }
                                                }
                            
                                                // Check for menu_detail in lastarray
                                                if (isset($lastarray[$main_menu][$submenu1][$submenu2])) {
                                                    // echo "<pre>"; print_r($lastarray[$main_menu][$submenu1][$submenu2]);
                                                    foreach ($lastarray[$main_menu][$submenu1][$submenu2] as $menu_detail => $menuDetailData) {
                                                        $menu_detail_ids = implode(', ', $menuDetailData['menu_id']);
                                                        echo '<li>';
                                                        // if(empty($menu_detail))
                                                        // {
                                                            $idss = $menuDetailData['menu_id'][0];
                                                            // $linked_submainmenu_ids = array_column($linked_menus, 'menu_link_id');
                                                            // print_r($linked_menus);
                                                            // die();
                                                            
                                                            $results = get_menu_ids_from_role_page($main_menu,$submenu1,$submenu2,$role_id);
                                                            $linked_or_not =  0;
                                                            $checked_or_not = '';
                                                            if($results)
                                                            {
                                                                $linked_or_not = 1;
                                                                $checked_or_not = 'checked';
                                                            }

                                                            // $sbumainfunction_checked = (in_array($linked_submainmenu_ids, $idss)) ? "checked" : "";
                                                            $subpageval = "Sub_".str_replace(' ', '_', $menu_detail)."_".$top_menu_id;
                                                            echo '<div class="row"><div class="col-9"><input type="checkbox" id="'.$subpageval.'" name="'.$menu_detail.'" class="menuClass checkallmodules '.$main_module.' submenu_label '.$mainpageval.'" data-id="'.$subpageval.'" '.$checked_or_not.'>';
                                                            
                                                            echo '<label for="'.$subpageval.'">'.$menu_detail.'</label></div>';
                                                            echo '<div class="col-3 text-right"><button class="btn btn-sm btn-crud menu-menu-expand-btn" type="button" data-toggle="collapse"  data-target="#collapse_' . $subpageval . '">
                                                            <i class="fa fa-angle-down"></i>
                                                            </button></div></div>';
                                                        // }
                                                        echo '<ul class="items-under-this collapsemenu" id="collapse_' . $subpageval . '">';
                                                    
                                                        // echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>$menu_detail.$top_menu_id</strong> (Menu ID: $id)<br>";
                            
                                                        // Print functions under menu_detail
                                                        foreach ($menuDetailData['functions'] as $index => $detailFunction) {
                                                            $checkbox_label = $menuDetailData['menu_id'][$index];

                                                            $linked_submenu_ids = array_column($linked_menus, 'menu_link_id');
                                                            $sbufunction_checked = (in_array($checkbox_label, $linked_submenu_ids)) ? "checked" : "";

                                                            echo '<li>';
                                                            echo '<input type="checkbox" id="'.$checkbox_label.'" name="'.$detailFunction.'" class="menuClass checkallmodules '.$main_module.' '.$mainpageval.' '.$subpageval.'" value="'.$menuDetailData['menu_id'][$index].'" '.$sbufunction_checked.'>';
                                                            echo '<label for="'.$checkbox_label.'">'.$detailFunction.'</label>';
                                                            // echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$detailFunction - {$menuDetailData['menu_id'][$index]}<br>";
                                                            echo '</li>';
                                                        }
                                                        echo '</ul>';
                                                    }
                                                }
                                                echo '</ul>';
                                                echo '</div>';
                                                echo '</div>';
                                            }
                                        }
                                }
                                echo '</div>';
                            }
                        ?>

                    </div>
                    <!-- ======================================================== -->

                </div>
                <div class="col-12 text-right mt-3">
                    <hr>
                    <button type="submit" id="save-btn"
                        class="btn btn-crud btn-lg btn-primary"><?php echo $this->lang->line('Save') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $("#role_id").select2({
        placeholder: "Type Role",
        allowClear: true,
        width: '100%'
    });

    $("#data_form").validate({
        ignore: [],
        rules: {
            menu_name: {
                required: true
            },
            menu_label: {
                required: true
            },
            module_number: {
                required: true
            }
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
    $('#catgtable').DataTable({
        responsive: true
    });

    show_menus_by_checked_modules();
    verify_main_page_is_checked();
    verify_main_menu_checked();
    

});

$('#save-btn').on('click', function(e) {
    e.preventDefault();
    $('#save-btn').prop('disabled', true);

    // Validate the form console
    if ($("#data_form").valid()) {
        var selectedMenus = [];
        $('.menuClass:checked').each(function() {
            selectedMenus.push({
                menu_id: $(this).val()
            });
        });
        
        var selectedApprovals = [];
            $('.moduleclass:checked').each(function() {
                let module_number = $(this).data('id'); 
                selectedApprovals.push({
                    first_level_approval: $('#' + module_number + '_first_level_approval').is(':checked') ? 'Yes' : 'No',
                    second_level_approval: $('#' + module_number + '_second_level_approval').is(':checked') ? 'Yes' : 'No',
                    third_level_approval: $('#' + module_number + '_third_level_approval').is(':checked') ? 'Yes' : 'No',
                    module_id: module_number,
                });
            });
        var role_id = $("#role_id").val();
        if (selectedMenus.length > 0) {
            Swal.fire({
                title: "Are you sure?",
                text: "Do you wish to proceed with these selected items?",
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
                        url: baseurl + 'roles/role_permission_addedit_action',
                        type: 'POST',
                        data: JSON.stringify({
                            selectedMenus: selectedMenus,
                            selectedApprovals: selectedApprovals,
                            role_id: role_id 
                        }),
                        contentType: 'application/json',
                        success: function(response) {
                            window.location.href = baseurl + 'roles';
                        },
                        error: function(xhr, status, error) {
                            Swal.fire('Error',
                                'An error occurred while processing the request.',
                                'error');
                            console.log("XHR:", xhr);
                            console.log("Status:", status);
                            console.log("Error:", error);
                        },
                        complete: function() {
                            $('#save-btn').prop('disabled', false);
                        }
                    });
                } else {
                    $('#save-btn').prop('disabled', false);
                }
            });
        } else {
            Swal.fire({
                title: "Action Required",
                text: "Please select at least one module and one menu before proceeding.",
                icon: "warning",
                confirmButtonColor: '#3085d6',
                confirmButtonText: "OK",
                allowOutsideClick: false,
            }).then(() => {
                $('#save-btn').prop('disabled', false);
            });
        }
    } else {
        $('#save-btn').prop('disabled', false);
    }
});



$('#role-btn').on('click', function(e) {
    e.preventDefault(); // Prevent the default form submission
    $('#role-btn').prop('disabled', true);

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
            allowOutsideClick: false, // Disable outside click
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
                        Swal.fire('Error', 'An error occurred while generating the lead',
                            'error');
                        console.log(error); // Log any errors
                    }
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                // Enable the button again if user cancels
                $('#role-btn').prop('disabled', false);
            }
        });
    } else {
        // If form validation fails, re-enable the button
        $('#role-btn').prop('disabled', false);
    }
});



$('#role_id').on('change', function(e) {
    e.preventDefault(); 
    var role_id = $('#role_id').val();         
    var old_role = $('#old_role').val();         
    if(role_id === old_role) {
        return;
    }
    Swal.fire({
        title: "Are you sure?",
        text: "Do you want to change the role?",
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
            window.location.href = baseurl + 'roles/set_user_role_permissions?role=' + role_id;
        } else {           
            $("#role_id").val(old_role).trigger('change');
        }
    });
});
// permission section
$('#Modules').on('change', function () {
    if ($(this).is(':checked')) {
        // If #Modules is checked, check all moduleclass checkboxes
        $('.moduleclass').prop('checked', true);
        updateModuleVisibility();
    } else {
        // If #Modules is unchecked, show SweetAlert confirmation
        Swal.fire({
            title: 'Are you sure?',
            text: 'Unchecking this will deselect all modules!',
            icon: 'warning',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            showCancelButton: true,
            confirmButtonText: 'Yes, proceed!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true,
            focusCancel: true,
            allowOutsideClick: false
        }).then((result) => {
            if (result.isConfirmed) {
                // Uncheck all moduleclass checkboxes if confirmed
                $('.moduleclass').prop('checked', false);
                updateModuleVisibility();
            } else {
                // Keep #Modules checked if canceled
                $('#Modules').prop('checked', true);
            }
        });
    }
});

function updateModuleVisibility() {
    var checkedModules = $('.moduleclass:checked').map(function () {
        return $(this).val();
    }).get();

    // Hide all tables and permissions
    $('.mainmodule').addClass('d-none');
    $('.permissions').addClass('d-none');

    // Show tables and permissions corresponding to the checked modules
    checkedModules.forEach(function (moduleValue) {
        var moduleValue1 = moduleValue.replace(/ /g, '_');
        $('.mainmodule.' + moduleValue1).removeClass('d-none');
        $('.permissions.' + moduleValue1).removeClass('d-none');
    });
}



// When any .moduleclass checkbox changes
$('.moduleclass').on('change', function () {
    // If all .moduleclass checkboxes are checked, set #Modules to checked
    if ($('.moduleclass:checked').length === $('.moduleclass').length) {
        $('#Modules').prop('checked', true);
    } else {
        $('#Modules').prop('checked', false);
    }

    var checkedModules = $('.moduleclass:checked').map(function() {
        return $(this).val(); 
    }).get();

    // Hide all tables and permissions
    // $('.mainmodule').addClass('d-none');
    // $('.permissions').addClass('d-none');
    console.log(checkedModules);
    // Show tables and permissions corresponding to the checked modules
    checkedModules.forEach(function(moduleValue) {
        $('.mainmodule.' + moduleValue).removeClass('d-none');
        $('.permissions.' + moduleValue).removeClass('d-none');
    });

    // Detect when a checkbox is being unchecked and ask for confirmation
    if (!$(this).prop('checked')) {
        swal.fire({
            title: 'Are you sure?',
            text: "Do you want to uncheck this module? Previously checked menu values will be reset.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, uncheck it!',
            cancelButtonText: 'No, keep it checked',
            reverseButtons: true,  
            focusCancel: true,      
            allowOutsideClick: false,  // Disable outside click
        }).then((result) => {
            if (result.isConfirmed) {
                // Uncheck the checkbox and reset related module
                $(this).prop('checked', false);

                var moduleValue = $(this).val();
                // Hide the corresponding table and permissions
                $('.mainmodule.' + moduleValue).addClass('d-none');
                $('.permissions.' + moduleValue).addClass('d-none');
                $('.permissions.' + moduleValue + ' input.menuClass').prop('checked', false);
            } else {
                // If not confirmed, revert the checkbox state
                $(this).prop('checked', true);
                
            }
        });
    }
});

function show_menus_by_checked_modules()
{
    var checkedModules = $('.moduleclass:checked').map(function() {
        return $(this).val(); 
    }).get();

    // Hide all tables with class .mainmodule
    $('.mainmodule').addClass('d-none');
    $('.permissions').addClass('d-none');
    checkedModules.forEach(function(moduleValue) {
        $('.mainmodule' + '.'+moduleValue).removeClass('d-none');
        $('.permissions' + '.'+moduleValue).removeClass('d-none');
    });
}
// permission section ends 

$('.check-all-module').on('change', function (e) {
    // Store reference to the checkbox
    var checkbox = $(this);
    var isChecked = checkbox.is(':checked');

    // Show SweetAlert confirmation
    Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to select or deselect all items?',
        icon: 'warning',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        showCancelButton: true,
        confirmButtonText: 'Yes, proceed!',
        cancelButtonText: 'No, cancel!',
        reverseButtons: true,
        focusCancel: true,
        allowOutsideClick: false
    }).then((result) => {
        if (result.isConfirmed) {
            if (result.isConfirmed) {
            $('.checkallmodules').prop('checked', isChecked);
            // Enable or disable checkboxes based on the checked state
            $('.checkallmodules').prop('disabled', !isChecked);
        } else {
            // If canceled, revert the checkbox state
            checkbox.prop('checked', !isChecked);
            // Reset the disabled state if canceled
            $('.checkallmodules').prop('disabled', false); 
        }
        $('.main-page-label').prop('disabled', false); 
        $('.main-module').prop('disabled', false); 
        } else {
            // If canceled, revert the checkbox state
            checkbox.prop('checked', !isChecked);
        }
    });
});


$('.main-module').on('change', function (e) {
    // Store reference to the checkbox
    var checkbox = $(this);
    var isChecked = checkbox.is(':checked');
    var dataId = checkbox.data('id');
    // Show SweetAlert confirmation
    Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to select or deselect all items?',
        icon: 'warning',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        showCancelButton: true,
        confirmButtonText: 'Yes, proceed!',
        cancelButtonText: 'No, cancel!',
        reverseButtons: true,
        focusCancel: true,
        allowOutsideClick: false
    }).then((result) => {
        if (result.isConfirmed) {
            $('.' + dataId).each(function () {
                $(this).prop('checked', isChecked); 
                if (isChecked) {
                    $(this).prop('disabled', false); 
                } else {
                    $(this).prop('disabled', true); 
                }
            });
            
        } else {
            // If canceled, revert the checkbox state
            checkbox.prop('checked', !isChecked);
            // $('.' + dataId).prop('disabled', true);
        }
        
        $('.main-permission-level').prop('disabled', false); 
        $('.main-page-label').prop('disabled', false); 
    });
});


$('.main-page-label').on('change', function (e) {
    // Store reference to the checkbox
    var checkbox = $(this);
    var isChecked = checkbox.is(':checked');
    var dataId = checkbox.data('id');    
    // Show SweetAlert confirmation
    Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to select or deselect all items?',
        icon: 'warning',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        showCancelButton: true,
        confirmButtonText: 'Yes, proceed!',
        cancelButtonText: 'No, cancel!',
        reverseButtons: true,
        focusCancel: true,
        allowOutsideClick: false
    }).then((result) => {
        if (result.isConfirmed) {
        $('.' + dataId).each(function () {
            $(this).prop('checked', isChecked); 
            if (isChecked) {
                $(this).prop('disabled', false); 
            } else {
                $(this).prop('disabled', true);
            }
        });
        } else {
            // If canceled, revert the checkbox state of the main checkbox
            checkbox.prop('checked', !isChecked);
            $('.' + dataId).prop('disabled', false); // Ensure related checkboxes remain enabled
        }
    });
});



$('.submenu_label').on('change', function (e) {
    // Store reference to the checkbox

    var checkbox = $(this);
    var isChecked = checkbox.is(':checked');
    var dataId = checkbox.data('id');  
    // Show SweetAlert confirmation
    Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to select or deselect all items under this?',
        icon: 'warning',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        showCancelButton: true,
        confirmButtonText: 'Yes, proceed!',
        cancelButtonText: 'No, cancel!',
        reverseButtons: true,
        focusCancel: true,
        allowOutsideClick: false
    }).then((result) => {
        if (result.isConfirmed) {
            // If confirmed, check or uncheck related checkboxes
            $('.' + dataId).each(function () {
                $(this).prop('checked', isChecked);
                $('#' + dataId).val(0);    
                verify_main_page_is_checked();
            });
        } else {
            // If canceled, revert the checkbox state
            checkbox.prop('checked', !isChecked);
            $('#' + dataId).val(1);    
            $('.' + dataId).prop('disabled', false);
        }
    });
});
function verify_main_page_is_checked()
{
    $('.submenu_label').each(function () {
        var checkbox = $(this);
        var isChecked = checkbox.is(':checked');
        var dataId = checkbox.data('id');   
        var textFieldValue = $('#' + dataId).val();        
        if (isChecked || textFieldValue == '1') {
            $('.' + dataId).prop('disabled', false);
            $('label[for="' + dataId + '"]').prop('disabled', false);
        } else {
            $('.' + dataId).prop('disabled', true); 
            $('label[for="' + dataId + '"]').prop('disabled', true);
        }
    });
}

function verify_main_menu_checked()
{

    $('.main-page-label').each(function () {
        var checkbox = $(this);
        var isChecked = checkbox.is(':checked');
        var dataId = checkbox.data('id');    
        if (isChecked) {
            $('.' + dataId).prop('disabled', false);
        } else {
            $('.' + dataId).prop('disabled', true); 
        }
    });
}

</script>