<div class="card card-block">
    <?php if ($message) { ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'API key added successfully'
            });
            window.location.href = baseurl + 'restapi';
        </script>
        <?php
        // echo '<div id = "notify" class="alert alert-success"  >
        //     <a href = "#" class="close" data - dismiss = "alert" >&times;</a >

        //     <div class="message" >Api key added successfully!</div >
        // </div >';
    } ?>
    <div class="card-body">
        <h5><?php echo $this->lang->line('Access Key List') ?> 
            <a href="<?php echo base_url('restapi/add') ?>" class="btn btn-primary btn-sm rounded">
                <?php echo $this->lang->line('Add new') ?>
            </a>
           
        </h5>

        <hr>
        <table id="acctable" class="table table-striped table-bordered zero-configuration" cellspacing="0"
               width="100%">
            <thead>
            <tr>
                <th >#</th>
                <th><?php echo $this->lang->line('Key') ?></th>
                <th><?php echo $this->lang->line('Created On') ?></th>
                <th><?php echo $this->lang->line('Action') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php $i = 1;

            foreach ($keys as $row) {
                $id = $row['id'];
                $key = $row['key'];
                $datec = $row['date_created'];

                echo "<tr>
                    <td style='text-align:center'>$i</td>
                    <td>$key</td>
                    <td>".dateformat($datec)."</td>
                 
                    
                    <td><a href='#' data-object-id='" . $id . "' class='btn btn-secondary btn-sm delete-object' title='Delete'><i class='fa fa-trash'></i></a></td></tr>";
                $i++;
            }
            ?>
            </tbody>
            <tfoot>
            <tr>
                <th>#</th>
                <th><?php echo $this->lang->line('Key') ?></th>
                <th><?php echo $this->lang->line('Created On') ?></th>
                <th><?php echo $this->lang->line('Action') ?></th>
            </tr>
            </tfoot>
        </table>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {

        //datatables
        // $('#acctable').DataTable({responsive: true});

    // });

    var columnlist = [
        { 'width': '5%' }, 
        { 'width': '20%' },
        { 'width': '10%' }, 
        { 'width': '' }
        ];
        $('#acctable').DataTable({
            // responsive: true,
            <?php datatable_lang();?>
            
            "columnDefs": [
                {
                    "targets": [0],
                    "orderable": true,
                },
            ], 
            'columns': columnlist,

        });
    });

     $("#submit-btn").on("click", function (e) {
        e.preventDefault();
        $('#submit-btn').prop('disabled', true);
            var formData = new FormData($("#data_form")[0]);
            Swal.fire({
               title: "Are you sure?",
               text: "Do you want to update Email alert?",
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
                        url: baseurl + 'restapi/add',
                        contentType: false,
                        processData: false,
                        dataType: "json",
                        success: function(response) {
                            window.location.href = baseurl + 'restapi';
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
               }
               else{
                    $('#submit-btn').prop('disabled', false);
               }
        });
        
    });
</script>
<div id="delete_model" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h4 class="modal-title"><?php echo $this->lang->line('Delete') ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <p><?php echo $this->lang->line('delete this key') ?></p>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="object-id" value="">
                <input type="hidden" id="action-url" value="restapi/delete_i">
                <button type="button" data-dismiss="modal" class="btn btn-primary"
                        id="delete-confirm"><?php echo $this->lang->line('Delete') ?></button>
                <button type="button" data-dismiss="modal"
                        class="btn"><?php echo $this->lang->line('Cancel') ?></button>
            </div>
        </div>
    </div>
</div>