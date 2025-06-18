

<div class="content-body">  

    <?php       
    if (($msg = check_permission($permissions)) !== true) {
        echo $msg;
        return;
    }
    ?>

    <div class="row d-none">
        <div class="col-xl-6 col-md-12">
            <div class="card overflow-hidden">
                <div class="card-content">
                    <div class="media align-items-stretch">
                        <div class="bg-primary p-2 media-middle">
                            <i class="fa fa-briefcase font-large-2 white"></i>
                        </div>
                        <div class="media-body p-2">
                            <h4><?php echo $this->lang->line('Balance') ?></h4>
                            <span><?php echo $this->lang->line('Total') ?></span>
                        </div>
                        <div class="media-right p-2 media-middle">
                            <h1 class="success"><span id="dash_0"></span></h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-md-12">
            <div class="card">
                <div class="card-content">
                    <div class="media align-items-stretch">
                        <div class="bg-warning p-2 media-middle">
                            <i class="fa fa-list-alt font-large-2  white"></i>
                        </div>
                        <div class="media-body p-2">
                            <h4><?php echo $this->lang->line('Accounts') ?></h4>
                            <span><?php echo $this->lang->line('Total') ?></span>
                        </div>
                        <div class="media-right p-2 media-middle">
                            <h1 class="cyan" id="dash_1">0</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h5><?php echo $this->lang->line('Accounts') ?></h5>
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

                <!-- ======================================================================== -->
                <div class="card card-block formborder">
                    <form method="post" id="data_form" class="form-horizontal">

                        <h5 id="headerlabel"><?php echo $this->lang->line('Add New Account') ?></h5>
                        <hr>
                        <div class="alert alert-danger d-none" role="alert" id="account-error">Account number already in use</div>
                        <div class="form-group row">
                            
                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label" for="holder"><?php echo $this->lang->line('Name') ?><span class="compulsoryfld">*</span></label>
                                <input type="text" placeholder="Name"
                                    class="form-control margin-bottom required" name="holder" id="holder">
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label"
                                for="accno"><?php echo $this->lang->line('Account No') ?><span class="compulsoryfld">*</span></label>
                                <input type="text" placeholder="Account Number"
                                    class="form-control margin-bottom required" name="accno" id="accno">
                            </div>


                            <!-- ======================================== -->
                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label" for="address"><?php echo $this->lang->line('Account Type') ?><span class="compulsoryfld">*</span></label>
                                    
                                <?php
                                    echo '<select name="account_type_id" id="account_type_id" class="form-control">';
                                    echo '<option value="">Select Type</option>';
                                    foreach ($accountheaders as $parentItem) {
                                        $coaHeaderId = $parentItem['coa_header_id'];
                                        $coaHeader = $parentItem['coa_header'];
                                        if (isset($child[$coaHeaderId])) {
                                            echo '<optgroup label="' . htmlspecialchars($coaHeader) . '">';
                                            foreach ($child[$coaHeaderId] as $childItem) {
                                                $childId = $childItem['coa_type_id'];
                                                $typename = $childItem['typename'];
                                                echo '<option value="' . htmlspecialchars($childId) . '" data-id="' . htmlspecialchars($typename) . '">' . htmlspecialchars($typename) . '</option>';
                                            }
                            
                                            echo '</optgroup>';
                                        }
                                    }
                                    echo '</select>';
                                    ?>


                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label" for="address"><?php echo $this->lang->line('Parent Account') ?></label>
                                <select name="parent_account_id" id="parent_account_id" class="form-control margin-bottom">
                                </select>
                                <input type="hidden" name="account_type" id="account_type">
                                <input type="hidden" name="account_id" id="account_id" value="0">
                                <input type="hidden" name="parent_account_idval" id="parent_account_idval" value="0">
                            </div>
                            <!-- ======================================== -->

                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12 d-none">
                                <label class="col-form-label"
                                for="lid"><?php echo $this->lang->line('Business Locations') ?></label>
                                <select name="lid" class="form-control">
                                    <?php
                                    if (!$this->aauth->get_user()->loc) echo "<option value='0'>" . $this->lang->line('All') . "</option>";
                                    foreach ($locations as $row) {
                                        $cid = $row['id'];
                                        $acn = $row['cname'];
                                        $holder = $row['address'];
                                        echo "<option value='$cid'>$acn - $holder</option>";
                                    }
                                    ?>
                                </select>
                                
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label" for="acode"><?php echo $this->lang->line('Descriptions') ?></label>
                                <textarea name="acode" id="acode" class="form-textarea margin-bottom"></textarea>
                            </div>
                        
                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label"
                                for="intbal"><?php echo $this->lang->line('Intial Balance') ?></label>
                                <input type="text" placeholder="Intial Balance" onkeypress="return isNumber(event)"
                                    class="form-control margin-bottom" name="intbal" id="intbal">
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12 mt-32px submit-section">
                                <input type="submit" id="account-btn" class="btn btn-lg btn-primary margin-bottom"
                                    value="<?php echo $this->lang->line('Add Account') ?>" data-loading-text="Adding...">
                                <input type="hidden" value="accounts/addacc" id="action-url">
                            </div>
                        </div>
                    </form>
                </div>
                <!-- ======================================================================== -->
                <div class="table-container table-scroll">
                    <table id="acctable" class="table table-striped table-bordered zero-configuration dataTable w-100" cellspacing="0" >
                        <thead>
                        <tr>
                            <!-- <th style="width:5%;">#</th> -->
                            <th colspan="2"><?php echo $this->lang->line('Account No') ?></th>
                            <!-- <th><?php echo $this->lang->line('Name') ?></th> -->
                            <th><?php echo $this->lang->line('Type') ?></th>
                            <th class='text-right'><?php echo $this->lang->line('Balance') ?></th>
                            <th><?php echo $this->lang->line('Actions') ?></th>


                        </tr>
                        </thead>
                        <tbody>
                        <?php 
                        $i = 0;
                       
                        foreach ($accountheaders as $parentItem) {
                           
                            $coaHeaderId = $parentItem['coa_header_id'];
                            
                            $total_header_amount = array_sum(array_column($accountlists[$coaHeaderId], 'lastbal'));
                            // echo "<pre>"; print_r($total_amount);
                            $coaHeader = $parentItem['coa_header'];
                            if (isset($accountlists[$coaHeaderId])) {
                                ?>
                                <tr class="customermain">
                                    <td colspan="3">
                                        <button class="btn btn-info btn-sm expand-btn3" type="button" data-toggle="collapse" data-target="#collapse_<?=$coaHeaderId?>">
                                            <i class="fa fa-angle-down"></i>
                                        </button>
                                        <?php echo '<b>'.$coaHeaderId." - ".$coaHeader.'</b>'; ?>
                                    </td>
                                    <td class="text-right"><b><?=number_format(abs($total_header_amount),2)?></b></td>
                                    <td></td>
                                </tr>
                                <?php
                            }
                            
                            foreach ($accountlists[$coaHeaderId] as $row) {
                                
                                $aid = $row['id'];
                                $code = $row['acn'];
                                $parent_account_id = $row['parent_account_id'];
                                $acn = "<a onclick='update_account($aid)'class='breaklink' >".$row['acn']."</a>";
                                // $acn = "<a href='" . base_url("accounts/view?id=$aid") . "' >".$row['acn']."</a>";
                                $acn_transactions = "<a href='" . base_url("transactions/account_transactions?code=$code") . "' ><b>".$row['acn'].' - '.$row['holder']."</b></a>";
                                $holder = $acn_transactions;
                                $balance = ($row['lastbal'] != '0.00') ? '<b>'.number_format(abs($row['lastbal']), 2).'</b>':'0.00';
                                $type = $row['account_type'];
                                $qty = $row['adate'];
                                // $accountparent[$single['coa_header_id']][$single['parent_account_id']][];
                                $parentItem = $accountparent[$coaHeaderId][$row['id']];
                                // echo "<pre>"; print_r($parentItem);
                                
                                if($parentItem)
                                {
                                    $i++;
                                        $parentmaster = $parentItem[0]['parent_account'];
                                        $parentmaster_id = $parentItem[0]['id'];
                                    ?> 
                                    <tr class="customermain1 collapse show" id='collapse_<?=$coaHeaderId?>'>
                                        <!-- <td> <?=$i?></td> -->
                                        <td colspan="3">                                 
                                            <?php echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>'.$holder.'</b>'; ?> <button class="btn btn-info btn-sm expand-btn4" type="button" data-toggle="collapse" data-target="#collapse_<?=$parentmaster?>" >
                                                <i class="fa fa-angle-down"></i>
                                            </button>
                                        
                                        </td>
                                        <td class="text-right"><?php 
                                            $total_amount = array_sum(array_column($parentItem, 'lastbal'));
                                            echo "<b>".number_format(abs($total_amount),2)."</b>";
                                            ?>
                                        </td>
                                        <?php
                                        echo  "<td><button onclick='update_account($aid)' class='btn btn-crud btn-secondary btn-sm' title='Edit'><i class='fa fa-pencil'></i></button></td>";
                                        $fl = 1;
                                        $starttbl ="";
                                        $endtbl ="";
                                        $tabletr = "";
                                        $j=1;
                                        foreach($parentItem as $child)
                                        {
                                            $typeclass = 'collapse_'.$coaHeaderId;
                                            $childacn = $child['acn'];
                                            $child_holder = "<a href='" . base_url("transactions/account_transactions?code=$childacn") . "' ><b>".$child['acn']." - ".$child['holder']."</b></a>";
                                            $parentid = $child['parent_account_id'];
                                            $parent_account = $child['parent_account'];
                                            $accountid= $child['id'];
                                            $childbalance = ($child['lastbal'] != '0.00') ? '<b>'.number_format(abs($child['lastbal']),2).'</b>':'0.00';
                                           
                                            echo  "<tr class='collapse show $typeclass' id='collapse_$parent_account'>";
                                            // echo  "<td class='text-center'>".$j++."</td>";
                                            // echo "<td></td>";
                                            // echo  "<td colspan='2' class='text-center'>&nbsp;&nbsp;&nbsp;&nbsp;".$child['acn']."</td>";
                                            echo  "<td colspan='2'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$child_holder."</td>";
                                            echo  "<td>".$child['account_type']."</td>";
                                            echo  "<td class='text-right'>".$childbalance."</td>";
                                            echo  "<td><button onclick='update_account($accountid)' class='btn btn-crud btn-secondary btn-sm' title='Edit'><i class='fa fa-pencil'></i></button></td></tr>";
                                            
                                        }
                                        ?>
                                    </tr>

                                    <?php
                                
                                }
                                else{
                                    if(empty($parent_account_id))
                                    {
                                        $i++;
                                        echo "<tr class='collapse show' id='collapse_$coaHeaderId'>";
                                        // echo "<td>$i</td>
                                        // echo "<td>$acn</td>";
                                        echo "<td colspan='2'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$holder</td>
                                        <td>$type</td>
                                        <td class='text-right'>".($balance)."</td>
                                        <td><button onclick='update_account($aid)' class='btn btn-crud btn-secondary btn-sm' title='Edit'><i class='fa fa-pencil'></i></button></td></tr>";
                                    }
                                    
                                }
                            }
                        } ?>
                        
                        </tbody>
                       
                    </table>
                </div>
            </div>
        </div>
        <input type="hidden" id="dashurl" value="accounts/account_stats">
    </div>
<script type="text/javascript">
    $(document).on('click', '.expand-btn3', function(e) {
        e.preventDefault();
        var $this = $(this);
        var target = $this.data('target');       // e.g., "#collapse_100"
        
        // Toggle the ID target
        $(target).collapse('toggle');            // => #collapse_100

        // Remove '#' and toggle the class
        var cleanTarget = target.replace('#', '');  // => "collapse_100"
        $('.' + cleanTarget).collapse('toggle');    // => .collapse_100

        // Toggle icon direction
        var $icon = $this.find('i');
        $icon.toggleClass('fa-angle-down fa-angle-up');
    });

    $(document).on('click', '.expand-btn4', function(e) {
        e.preventDefault();
        var $this = $(this);
        var target = $this.data('target');

        $(target).collapse('toggle'); // Toggle the visibility of the customer rows

        // Toggle between angle-down and angle-up icons
        if ($this.find('i').hasClass('fa-angle-down')) {
            $this.find('i').removeClass('fa-angle-down').addClass('fa-angle-up');
        } else {
            $this.find('i').removeClass('fa-angle-up').addClass('fa-angle-down');
        }
    });
    
    $(document).ready(function() {
        $("#account_type_id").select2({
            placeholder: "Type Account", 
            allowClear: true,
            width: '100%'
        });   
        $("#parent_account_id").select2({
            placeholder: "Select Parent Account", 
            allowClear: true,
            width: '100%'
        });   

        $("#data_form").validate($.extend(true, {}, globalValidationOptions, {
            ignore: [],
            rules: {               
                holder: { required: true },
                accno: { required: true },
                account_type_id: { required: true }
            },
            messages: {
                holder: "Enter account holder name",
                accno: "Enter account number",
                account_type_id: "Select account type"
            }
        }));

    });
    $('#account-btn').on('click', function(e) {
        e.preventDefault(); // Prevent the default form submission
        $('#account-btn').prop('disabled', true);
        
        // Validate the form
        if ($("#data_form").valid()) {                
            var form = $('#data_form')[0];
            var formData = new FormData(form); 
            Swal.fire({
                title: "Are you sure?",
                text: "Do you want to create/update account?",
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
                        url: baseurl + 'accounts/addacc', // Replace with your server endpoint
                        type: 'POST',
                        data: formData,
                        contentType: false, 
                        processData: false,
                        success: function(response) {
                            if (typeof response === "string") {
                                response = JSON.parse(response);
                            }
                            if(response.status=='Error')
                            {
                                $('#account-error').removeClass('d-none');  
                                $('#account-btn').prop('disabled', false);
                            }
                            else{
                                $('#account-error').addClass('d-none');  
                                location.reload();
                            }
                            
                        },
                        error: function(xhr, status, error) {
                            Swal.fire('Error', 'An error occurred while generating the lead', 'error');
                            console.log(error); // Log any errors
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    // Enable the button again if user cancels
                    $('#account-btn').prop('disabled', false);
                }
            });
        } else {
            // If form validation fails, re-enable the button
            $('#account-btn').prop('disabled', false);
        }
    });

    $("#account_type_id").on('change',function(){
        $("#account_type").val($("#account_type_id option:selected").data("id"));       
        $.ajax({
            type: 'POST',
            url: baseurl +'accounts/load_accounts_by_typeid',
            data: {
                "account_type_id" : $("#account_type_id").val()
            },
            success: function(response) {
            var responseData = JSON.parse(response);
            $("#parent_account_id").html(responseData.data);
            if($("#parent_account_idval").val() >0)
            {
                    $("#parent_account_id option").each(function() {
                        
                        if ($(this).val() == $("#parent_account_idval").val()) {
                            $(this).prop("selected", true);
                            return false; // Stops the loop once the match is found
                        }
                    });
            }
            },
            error: function(xhr, status, error) {
                // Handle error
                console.error(xhr.responseText);
            }
        });
    });

    function update_account(id)
    {
        $.ajax({
            type: 'POST',
            url: baseurl +'accounts/load_accounts_by_id',
            data: {
                "account_id" : id
            },
            success: function(response) {
                var responseData = JSON.parse(response);
                var resultdata = responseData.data[0];
                $("#holder").focus();
                $("#account-btn").val("Update Account");           
                $("#headerlabel").text("Update Account");           
                $("#parent_account_idval").val(resultdata.parent_account_id);
                $("#account_id").val(resultdata.id);
                $("#accno").val(resultdata.acn);
                $("#holder").val(resultdata.holder);
                $("#intbal").val(resultdata.lastbal);
                $("#account_type_id").val(resultdata.account_type_id).trigger('change');
                $("#parent_account_id").val(resultdata.parent_account_id).trigger('change');
                $("#acode").val(resultdata.code);
            },
            error: function(xhr, status, error) {
                // Handle error
                console.error(xhr.responseText);
            }
        });
    }

</script>