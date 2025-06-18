<?php       
  if (($msg = check_permission($permissions)) !== true) {
     echo $msg;
     return;
  }
 ?>
<div class="content-body">
    <div class="row">
        <div class="col-xl-3 col-lg-6 col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <div class="media d-flex">
                            <div class="media-body text-left">
                                <h3 class="danger" id="dash_0"></h3>
                                <span><?php echo $this->lang->line('Due') ?></span>
                            </div>
                            <div class="align-self-center">
                                <i class="fa fa-clock-o danger font-large-2 float-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <div class="media d-flex">
                            <div class="media-body text-left">
                                <h3 class="blue" id="dash_1"></h3>
                                <span><?php echo $this->lang->line('Progress') ?></span>
                            </div>
                            <div class="align-self-center">
                                <i class="fa fa-refresh blue font-large-2 float-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <div class="media d-flex">
                            <div class="media-body text-left">
                                <h3 class="success" id="dash_2"></h3>
                                <span><?php echo $this->lang->line('Done') ?></span>
                            </div>
                            <div class="align-self-center">
                                <i class="fa fa-flag-checkered success font-large-2 float-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <div class="media d-flex">
                            <div class="media-body text-left">
                                <h3 class="purple" id="dash_6"><?php echo $totalt ?></h3>
                                <span><?php echo $this->lang->line('Total') ?></span>
                            </div>
                            <div class="align-self-center">
                                <i class="icon-support purple font-large-2 float-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">

        <div class="card-header">
            <h3><?php echo $this->lang->line('Task') ?> <a href="<?= base_url() ?>tools/addtask" class="btn btn-primary btn-crud btn-sm rounded">
                    Add new </a></h3>

            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
            <div class="heading-elements">
                <ul class="list-inline mb-0">
                    
                    <li><a class="breaklink" data-action="expand"><i class="ft-maximize"></i></a></li>
                    
                </ul>
            </div>
        </div>
        <div class="card-body">
            <div class="table-container">
                <table id="todotable" class="table table-striped table-bordered zero-configuration" style="width:100% !important;">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Date & Time</th>
                        <th><?php echo $this->lang->line('Task') ?></th>
                        <th><?php echo $this->lang->line('Descriptions') ?></th>
                        <th><?php echo $this->lang->line('Due Date') ?></th>
                        <th><?php echo $this->lang->line('Start') ?></th>
                        <th><?php echo $this->lang->line('Assigned by') ?></th>
                        <th><?php echo $this->lang->line('Assigned To') ?></th>
                        <th><?php echo $this->lang->line('Status') ?></th>
                        <th><?php echo $this->lang->line('Actions') ?></th>


                    </tr>
                    </thead>
                    <tbody>

                    </tbody>

                </table>
            </div>
        </div>
    </div>
    <input type="hidden" id="dashurl" value="tools/task_stats">
</div>

<div id="delete_model" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">

                <h4 class="modal-title"><?php echo $this->lang->line('Delete') ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <p><?php echo $this->lang->line('delete this task') ?> </p>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="object-id" value="">
                <input type="hidden" id="action-url" value="tools/delete_i">
                <button type="button" data-dismiss="modal" class="btn btn-primary"
                        id="delete-confirm"><?php echo $this->lang->line('Delete') ?></button>
                <button type="button" data-dismiss="modal"
                        class="btn"><?php echo $this->lang->line('Cancel') ?></button>
            </div>
        </div>
    </div>
</div>
<div id="pop_model" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">

                <h4 class="modal-title"><?php echo $this->lang->line('Change Status'); ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>

            <div class="modal-body">
                <form id="form_model">


                    <div class="row">
                        <div class="col mb-1"><label
                                    for="status" class="col-form-label"><?php echo $this->lang->line('Change Status') ?></label>
                            <select name="stat" id="stat" class="form-control mb-1">
                                <option value="Due">Due</option>
                                <option value="Progress">Progress</option>
                                <option value="Done">Done</option>
                            </select>

                        </div>
                    </div>

                    <div class="modal-footer">
                        <input type="hidden" class="form-control"
                               name="tid" id="taskid" value="">
                        <button type="button" class="btn btn-default"
                                data-dismiss="modal"><?php echo $this->lang->line('Close'); ?></button>
                        <input type="hidden" id="action-url" value="tools/set_task">
                        <button type="button" class="btn btn-primary" id="change_status"><?php echo $this->lang->line('Change Status'); ?></button>
                        <!-- <button type="button" class="btn btn-primary" id="submit_model"><?php echo $this->lang->line('Change Status'); ?></button> -->
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div id="task_model" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">

                <h4 class="modal-title" id="task_title"><?php echo $this->lang->line('Details'); ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>

            <div class="modal-body">
                <form id="form_model">


                    <div class="row">
                        <div class="col mb-1" id="description">

                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col mb-1"><?php echo $this->lang->line('Priority') ?> <strong><span
                                        id="priority"></span></strong>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-1"><?php echo $this->lang->line('Assigned to') ?> <strong><span
                                        id="employee"></span></strong>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-1"><?php echo $this->lang->line('Assigned by') ?> <strong><span
                                        id="assign"></span></strong>

                        </div>
                    </div>

                    <div class="modal-footer">
                        <input type="hidden" class="form-control required"
                               name="tid" id="taskid" value="">
                        <button type="button" class="btn btn-default"
                                data-dismiss="modal"><?php echo $this->lang->line('Close'); ?></button>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">

    $(document).ready(function () {
        var columnlist = [
        { 'width': '5%' }, 
        { 'width': '10%' }, 
        { 'width': '20%' },
        { 'width': '25%' },
        { 'width': '10%' }, 
        { 'width': '10%' }, 
        { 'width': '10%' }, 
        { 'width': '10%' },
        { 'width': '7%' },
        { 'width': '' }
        ];

        $('#todotable').DataTable({
            "processing": true,
            "serverSide": true,
            // responsive: true,
            <?php datatable_lang();?>
            "ajax": {
                "url": "<?php echo site_url('tools/todo_load_list')?>",
                "type": "POST",
                'data': {'<?=$this->security->get_csrf_token_name()?>': crsf_hash}
            },
            "columnDefs": [
                {
                    "targets": [0],
                   'orderable': false,
                },
            ], 
            'columns': columnlist,
            dom: 'Blfrtip',
            buttons: [
                {
                    extend: 'excelHtml5',
                    footer: true,
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5,6]
                    }
                }
            ],

        });

        $(document).on('click', ".set-task", function (e) {
            e.preventDefault();
            $('#taskid').val($(this).attr('data-id'));
            $('#stat').val($(this).attr('data-status'));
            $('#pop_model').modal({backdrop: 'static', keyboard: false});
        });


        $(document).on('click', ".view_task", function (e) {
            e.preventDefault();
            var actionurl = 'tools/view_task';
            var id = $(this).attr('data-id');
            $('#task_model').modal({backdrop: 'static', keyboard: false});
            $.ajax({
                url: baseurl + actionurl,
                type: 'POST',
                data: {'tid': id, '<?=$this->security->get_csrf_token_name()?>': crsf_hash},
                dataType: 'json',
                success: function (data) {
                    var target_url = data.target_url;
                    if(target_url)
                    {
                        description = data.description+ "<br><a href='"+target_url+"'>Click here for more details</a>";
                    }
                    else{
                        description = data.description;
                    }
                    $('#description').html(description);
                    $('#task_title').html(data.name);
                    $('#employee').html(data.employee);
                    $('#assign').html(data.assign);
                    $('#priority').html(data.priority);
                }
            });

        });
        miniDash();


    });

    $("#change_status").on("click", function(e) {
            e.preventDefault();
            var validationFailed = false;
            Swal.fire({
                title: "Are you sure?",
                text: "Do you want to change the status?",
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
                    $.ajax({

                        url: baseurl + "tools/set_task",
                        type: 'POST',
                        data: {'tid': $("#taskid").val(),'stat' : $("#stat").val()},
                        dataType: 'json',
                        success: function (data) {
                            $('#todotable').DataTable().ajax.reload();
                            $("#pop_model").modal('hide');
                        }

                    });
                } else {
                    $('#change_status').prop('disabled', false); // Re-enable button on cancel
                }
            });
        });

</script>