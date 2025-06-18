<article class="content">
    <div class="card card-block">
        <?php if ($response == 1) {
            echo '<div id="notify" class="alert alert-success">
            <a href="#" class="close" data-dismiss="alert">&times;</a>

            <div class="message">' . $responsetext . '</div>
        </div>';

        } else if ($response == 0) {
            echo '<div id="notify" class="alert alert-danger">
            <a href="#" class="close" data-dismiss="alert">&times;</a>

            <div class="message">' . $responsetext . '</div>
        </div>';
        }
        else {
        ?>
        <div class="grid_3 grid_4">

            <form id="ajax-ticket-form" enctype="multipart/form-data">

            <h5>Add New Ticket</h5>
            <hr>

            <div class="form-group row">

                <label class="col-sm-2 col-form-label" for="name"><?php echo $this->lang->line('Subject') ?></label>

                <div class="col-sm-10">
                    <input type="text" placeholder="Ticket Subject" class="form-control margin-bottom  required"
                        name="title">
                </div>
            </div>


            <div class="form-group row">

                <label class="col-sm-2 control-label" for="edate"><?php echo $this->lang->line('Description') ?></label>

                <div class="col-sm-10">
                    <textarea class="summernote" placeholder=" Note" autocomplete="false" rows="10"
                        name="content"></textarea>
                </div>
            </div>
            <div class="form-group row">

                <label class="col-sm-2 col-form-label" for="name">Attach </label>

                <div class="col-sm-6">

                    <small>(docx, docs, txt, pdf, xls, png, jpg, gif)</small> <button class="btn btn-sm btn-secondary tr_clone_add d-none"><?php echo $this->lang->line('add_row') ?></button>
                </div>
            </div>
            <table class="table" id="v_var">
                <tr>
                    <td style="border:none !important;"> </td>
                    <td style="border:none !important;"> <input type="file" name="userfile[]" size="20" /><br></td>

                </tr>
            </table>
            <?php if ($captcha_on) {
                echo '<script src="https://www.google.com/recaptcha/api.js"></script>
									 <div class="form-group row">

                    <label class="col-sm-2 col-form-label"></label>

                    <div class="col-sm-4"><fieldset class="form-group position-relative has-icon-left">
                                      <div class="g-recaptcha" data-sitekey="' . $captcha . '"></div>
                                    </fieldset></div>
                </div>';
            } ?>
            <div class="form-group row">

                <label class="col-sm-2 col-form-label"></label>

                <div class="col-sm-4">
                    <input type="submit" id="submit-ticket-btn" class="btn btn-lg btn-primary margin-bottom" value="Save Ticket"
                        data-loading-text="Adding...">

                </div>
            </div>


            </form>
        </div>
    </div>
</article>
<script type="text/javascript">
$(function() {
    $(document).on('click', ".tr_clone_add", function(e) {
        e.preventDefault();
        var n_row = $('#v_var').find('tbody').find("tr:last").clone();

        $('#v_var').find('tbody').find("tr:last").after(n_row);

    });
    $('.summernote').summernote({
        height: 250,
        toolbar: [
            // [groupName, [list of button]]
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
            ['fullscreen', ['fullscreen']],
            ['codeview', ['codeview']]
        ]
    });
});

$(document).ready(function () {
    $('#submit-ticket-btn').on('click', function (e) {
        e.preventDefault();

        Swal.fire({
            title: 'Submit Ticket?',
            text: "Are you sure you want to submit this ticket?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, submit it!',
            reverseButtons: true,
            focusCancel: true
        }).then((result) => {
            if (result.isConfirmed) {
                var form = $('#ajax-ticket-form')[0];
                var formData = new FormData(form);

                $.ajax({
                    url: '<?php echo site_url('tickets/submit_ticket_ajax'); ?>',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (res) {
                        Swal.close();
                        if (res.status === 'success') {
                            window.location.href = baseurl + 'tickets';
                        } else {
                            Swal.fire('Error!', res.message, 'error');
                        }
                    },
                    error: function () {
                        Swal.close();
                        Swal.fire('Error!', 'An unexpected error occurred.', 'error');
                    }
                });
            }
        });
    });

    $('.tr_clone_add').on('click', function () {
        $('#v_var').append('<tr><td><input type="file" name="userfile[]" /></td></tr>');
    });
});


</script>

<?php } ?>