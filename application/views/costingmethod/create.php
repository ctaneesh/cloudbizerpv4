<div class="card card-block">
    <div class="card-header border-bottom">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('costingmethod') ?>"><?php echo $this->lang->line('Costing Method') ?></a></li>
                <li class="breadcrumb-item active"><?php echo $this->lang->line('New Costing Method') ?></li>
            </ol>
        </nav>
        <h4 class="card-title"><?php echo $this->lang->line('New Costing Method') ?></h4>
        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
        <div class="heading-elements">
            <ul class="list-inline mb-0">
                <li><a class="breaklink" data-action="expand"><i class="ft-maximize"></i></a></li>
            </ul>
        </div>
    </div>
    <div id="notify" class="alert alert-success" style="display:none;">
        <a href="#" class="close" data-dismiss="alert">&times;</a>

        <div class="message"></div>
    </div>
    <div class="card card-block ">


        <form method="post" id="data_form" class="card-body">
            <div class="form-group row">
                <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                    <label class="col-form-label" for="name"><?php echo $this->lang->line('Costing Method') ?><span class="compulsoryfld">*</span></label><br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="cberp_costing_method" id="inlineRadio1" value="FIFO" checked>
                        <label class="form-check-label" for="inlineRadio1">FIFO</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="cberp_costing_method" id="inlineRadio2" value="LIFO">
                        <label class="form-check-label" for="inlineRadio2">LIFO</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="cberp_costing_method" id="inlineRadio3" value="AVERAGE">
                        <label class="form-check-label" for="inlineRadio3">AVERAGE</label>
                    </div>
                </div>
                <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12 submit-section">
                    <input type="submit" id="add_cberp_costing_method_btn" class="btn btn-primary btn-lg margin-bottom"  value="<?php echo $this->lang->line('Add') ?>" data-loading-text="Adding...">
                    <input type="hidden" value="productpricing/create" id="action-url">
                </div>
            </div>

            <input type="hidden" name="image" id="image" value="logo.png">
        </form>
    </div>
</div>

<script>
$('#add_cberp_costing_method_btn').on('click', function(e) {
    e.preventDefault(); // Prevent the default form submission
    $('#add_cberp_costing_method_btn').prop('disabled', true); // Disable button to prevent multiple submissions
                 
        var form = $('#data_form')[0]; // Get the form element
        var formData = new FormData(form); // Create FormData object

        Swal.fire({
            title: "Are you sure?",
            text: "Do you want to create a new costing method?",
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
                    url: baseurl + 'costingmethod/action', // Replace with your server endpoint
                    type: 'POST',
                    data: formData,
                    contentType: false, 
                    processData: false,
                    success: function(response) {
                        if (typeof response === "string") {
                            response = JSON.parse(response);
                            window.location.href = baseurl + 'costingmethod';
                        }                        
                    },
                    error: function(xhr, status, error) {
                        Swal.fire('Error', 'An error occurred while generating the lead', 'error');
                        console.log(error); // Log any errors
                    }
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                // Enable the button again if user cancels
                $('#add_cberp_costing_method_btn').prop('disabled', false);
            }
        });
});
</script>