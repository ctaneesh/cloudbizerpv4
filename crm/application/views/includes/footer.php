<!-- BEGIN VENDOR JS-->

<script src="<?php echo assets_url(); ?>crm-assets/myjs/jquery-ui.js"></script>
<script src="<?php echo assets_url(); ?>crm-assets/vendors/js/ui/perfect-scrollbar.jquery.min.js"
        type="text/javascript"></script>
<script src="<?php echo assets_url(); ?>crm-assets/vendors/js/ui/unison.min.js" type="text/javascript"></script>
<script src="<?php echo assets_url(); ?>crm-assets/vendors/js/ui/blockUI.min.js" type="text/javascript"></script>
<script src="<?php echo assets_url(); ?>crm-assets/vendors/js/ui/jquery.matchHeight-min.js" type="text/javascript"></script>
<script src="<?php echo assets_url(); ?>crm-assets/vendors/js/ui/screenfull.min.js" type="text/javascript"></script>
<script src="<?php echo assets_url(); ?>crm-assets/vendors/js/extensions/pace.min.js" type="text/javascript"></script>
<script src="<?php echo assets_url(); ?>crm-assets/myjs/jquery.dataTables.min.js"></script>


<script type="text/javascript">var dtformat = $('#hdata').attr('data-df');
    var currency = $('#hdata').attr('data-curr');
    ;</script>
<script src="<?php echo assets_url(); ?>crm-assets/myjs/custom.js"></script>
<script src="<?php echo assets_url(); ?>crm-assets/myjs/basic.js"></script>
<script src="<?php echo assets_url(); ?>crm-assets/myjs/control.js"></script>
<script src="<?php echo assets_url(); ?>crm-assets/myjs/sweetalert.js"></script>

<script src="<?php echo assets_url(); ?>crm-assets/js/core/app.js" type="text/javascript"></script>
<script src="<?php echo assets_url(); ?>crm-assets/js/core/app-menu.js" type="text/javascript"></script>
<script>

$(document).ready(function() {
     hideEmptyImagePreviews();
    $('#addmore_img').click(function() {
        var fileId = $('.form-control').length;
        var newInput = '<div class="d-flex mt-2">';
        newInput += '<input type="file" name="upfile[]" title="Add Attachments(pdf, jpg, png, csv, excel only)" id="upfile-' + fileId + '" class="form-control1 input-file" accept=".pdf, .jpg, .jpeg, .png" onchange="readURL(this);">';
        newInput += '<img class="blah" src="" alt="your image" style="margin-left:10px; width:50px; height:50px;"/>';
        newInput += '<button type="button" class="btn btn-crud btn-secondary btn-sm delete-btn" style="height:30px; height:30px; margin:3px;"  title="Remove"><i class="icon-trash" ></i></button>';
        newInput += '</div>';

        // Append the new input field to the upload section
        $('#uploadsection').append(newInput);

        // Call the function to hide empty image previews
        hideEmptyImagePreviews();
        save_changed_values_for_history();
    });
    $('#addmore_product_img').click(function() {
        var fileId = $('.form-control').length;
        var newInput = '<div class="d-flex mt-2">';
        newInput += '<input type="file" name="upfile[]" id="upfile-' + fileId + '" class="form-control1 input-file" accept=".pdf, .jpg, .jpeg, .png" onchange="imgreadURL(this);">';
        newInput += '<img class="blah" src="" alt="your image" style="margin-left:10px; width:50px; height:50px;"/>';
        newInput += '<button type="button" class="btn btn-crud btn-secondary btn-sm delete-btn" style="height:30px; height:30px; margin:3px;"  title="Remove"><i class="icon-trash" ></i></button>';
        newInput += '</div>';

        // Append the new input field to the upload section
        $('#uploadsection').append(newInput);

        // Call the function to hide empty image previews
        hideEmptyImagePreviews();
        save_changed_values_for_history();
    });



    // Event delegation to handle delete button clicks on dynamically added elements
    $('#uploadsection').on('click', '.delete-btn', function() {
      
            swal.fire({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this item!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, proceed!',
                cancelButtonText: "No - Cancel",
                reverseButtons: true,
                focusCancel: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Remove the parent row of the delete button (which contains the input, image, and button)
                    // $(this).closest('.row').remove();
                    $(this).closest('.d-flex').remove();
                }
            });
       
    });
});

$('.delete-btn').on('click',  function() {
    // Show confirmation dialog
    const $fileInput = $(this).siblings('.input-file');
    // Check if .blah exists and has a source value
    if ($fileInput.length > 0 && $fileInput[0].files.length > 0) {
        swal.fire({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover this item!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, proceed!',
            cancelButtonText: "No - Cancel",
            reverseButtons: true,
            focusCancel: true
        }).then((result) => {
            if (result.isConfirmed) {
                $(this).closest('.d-flex').remove();
            }
        });
    }
    else{
        Swal.fire({
            icon: 'info',
            title: 'No file to Delete',
            text: 'There is no file to delete.',
            confirmButtonText: 'OK'
        });
    }
});
$('.delete-btn-file-only').on('click',  function() {
    const $blah = $(this).siblings('.blah');
    const $fileInput = $(this).siblings('.fileclass');
    // Check if .blah exists and has a source value
    if (($blah.length > 0 && $blah.attr('src') !== '') || ($fileInput.length > 0)) {
        swal.fire({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover this item!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, proceed!',
            cancelButtonText: "No - Cancel",
            reverseButtons: true,
            focusCancel: true
        }).then((result) => {
            if (result.isConfirmed) {
                $blah.hide(); // Hide the .blah image
                if ($fileInput.length > 0) {
                    $fileInput.val(''); 
                }
            }
        });
    } else {
        // Optional: Show a message if no .blah is available
        Swal.fire({
            icon: 'info',
            title: 'No file to Delete',
            text: 'There is no file to delete.',
            confirmButtonText: 'OK'
        });
    }
});

// Function to update the image preview dynamically
function readURL(input) {
    if (input.files && input.files[0]) {
        var file = input.files[0];
        var fileType = file.type; // Get the file type

        // Check if the file is an image (jpg, png, jpeg)
        if (fileType === 'image/jpeg' || fileType === 'image/png' || fileType === 'image/jpg') {
            var reader = new FileReader();

            reader.onload = function (e) {
                // Update the corresponding preview image by finding the .blah within the same .d-flex container
                $(input).siblings('.blah').attr('src', e.target.result).show();
            };

            reader.readAsDataURL(file); // Read the file as a data URL
        } else {
            // If the file is not an image, hide the preview image
            $(input).siblings('.blah').hide();
        }
    }
}

function imgreadURL(input) {
    if (input.files && input.files[0]) {
        var file = input.files[0];
        var fileType = file.type; // Get the file type

        // Check if the file is an image (jpg, png, jpeg)
        if (fileType === 'image/jpeg' || fileType === 'image/png' || fileType === 'image/jpg') {
            var reader = new FileReader();

            reader.onload = function (e) {
                $(input).siblings('.blah').attr('src', e.target.result).show();
            };

            reader.readAsDataURL(file); // Read the file as a data URL
        } else {
                // Use SweetAlert for error message
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid File Type',
                    text: 'Please select an image file (jpg, jpeg, png).',
                    confirmButtonText: 'OK',
                    customClass: {
                        confirmButton: 'btn btn-danger',
                    }
                });
                $(input).val(''); // Clear the input field
                $(input).siblings('.blah').hide(); // Hide the preview image
            }
    }
}


// Function to hide empty image previews if no src is set
function hideEmptyImagePreviews() {
    $('.blah').each(function() {
        if (!$(this).attr('src')) {
            $(this).hide(); // Hide the preview if there is no src
        }
    });
}



</script>
</body>
</html>
