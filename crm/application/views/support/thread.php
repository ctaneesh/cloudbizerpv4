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
        } else {
            echo ' <div id="notify" class="alert alert-success" style="display:none;">
            <a href="#" class="close" data-dismiss="alert">&times;</a>

            <div class="message"></div>
        </div>';

        } 
        if(empty($thread_info['id']))
        {
            $msg = check_permission();
            echo $msg;
            return;
        }
              
        $ticket_number = "ST/".($thread_info['id']+1000);
        ?>
        <div class="grid_3 grid_4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('tickets') ?>"><?php echo $this->lang->line('Support Tickets'); ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $ticket_number; ?></li>
                </ol>
            </nav> 

            <div class="card card-block shadow">
               
            <div class="row">
                <div class="col-lg-6">
                    <h4><?php echo "Ticket Number : ". $ticket_number ?></h4>
                </div>
                <div class="col-lg-6 text-right">
                    <?php
                        if($thread_info['status']=='Processing')
                        {
                            $class="st-Processing";
                            $text="Processing";
                        }
                        else if($thread_info['status']=='Solved')
                        {
                            $class="st-Solved";
                            $text="Solved";
                        }
                        else
                        {
                            $class="st-Waiting";
                            $text="Waiting";
                        }
                        echo '<span class="'.$class.'">'.$text.'</span>'
                    ?>
                  
                </div>
                
            </div>
            <hr>
            <p class="mb-0"><?php 
                echo '<strong>Subject : </strong> ' . $thread_info['subject'];
                echo '<br><strong>Created on : </strong> ' . dateformat_time($thread_info['created']);
               
                echo '<br><strong>Customer : </strong> ' . $thread_info['name'];
               
                
                ?></span></p>
                
            </div>
            <?php foreach ($thread_list as $row) { ?>


                <div class="form-group1 row">


                    <div class="col-sm-12">
                        <div class="card card-block shadow"><?php
                            if ($row['custo']) echo '<i class="icon-user display-inline-block"></i> Customer <strong>' . $row['custo'] . '</strong> Replied on  <i>'.dateformat_time($row['cdate']).'</i><hr>';

                            if ($row['emp']) echo '<i class="icon-user-tie display-inline-block"></i> Employee <strong>' . $row['emp'] . '</strong> Replied on <i>'.dateformat_time($row['cdate']).'</i><hr>';

                            echo $row['message'] . '';

                            if ($row['attach']) echo '<strong>Attachment: </strong><a href="' . substr_replace(base_url(), '', -4) . 'userfiles/support/' . $row['attach'] . '" target="_blank">' . $row['attach'] . '</a><br>';
                            ?></div>
                    </div>
                </div>
            <?php }

           {
                // echo form_open_multipart('tickets/thread?id=' . $thread_info['id']); 
                //  echo form_open_multipart('', ['id' => 'ajaxSupportForm']); ?>
                <form id="ajax-reply-form" enctype="multipart/form-data">


                <h5><?php echo $this->lang->line('Your Response') ?></h5>
                <hr>

                <div class="form-group row">

                    <label class="col-sm-2 control-label"
                           for="edate">Reply</label>

                    <div class="col-sm-10">
                        <textarea class="summernote"
                                  placeholder=" Message"
                                  autocomplete="false" rows="10" name="content"></textarea>
                    </div>
                </div>

                <div class="form-group row">

                    <label class="col-sm-2 col-form-label" for="name">Attach </label>

                    <div class="col-sm-6">

                        <small>(docx, docs, txt, pdf, xls, png, jpg, gif)</small>  <button class="btn btn-sm btn-blue tr_clone_add"><?php echo $this->lang->line('add_row') ?></button>
                    </div>
                </div>
                <table class="table no-border" id="v_var">
                    <tr> <td class="no-border"> </td>
                        <td class="no-border">  <input type="file" name="userfile[]" size="20"/><br></td>
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
                        <input type="hidden" name="theead_id" id="theead_id" Value="<?=$thread_info['id']?>">
                        <input type="submit" id="updatebtn" class="btn btn-success margin-bottom"
                               value="Update" data-loading-text="Updating...">
                    </div>
                </div>


                </form>
            <?php } ?>
        </div>
    </div>
</article>
<script type="text/javascript">
    $(function () {
        $(document).on('click', ".tr_clone_add", function (e) {
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
        $('#updatebtn').on('click', function (e) {
            e.preventDefault();
            var form = $('#ajax-reply-form')[0]; 
            var formData = new FormData(form);
            $.ajax({
                url: '<?php echo site_url('tickets/submit_reply_ajax'); ?>',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    $('#ajax-msg').text('Submitting...');
                },
                success: function (response) {
                    let res = JSON.parse(response);
                    if (res.status === 'success') {
                        $('#ajax-msg').text(res.message).css('color', 'green');
                        $('#ajax-reply-form')[0].reset();
                        $('.summernote').summernote('reset');
                        location.reload();
                        // Optional: reload thread list via AJAX
                    } else {
                        $('#ajax-msg').text(res.message).css('color', 'red');
                    }
                },
                error: function () {
                    $('#ajax-msg').text('An error occurred').css('color', 'red');
                }
            });
        });

        // Add new file input row
        $('.tr_clone_add').on('click', function () {
            $('#v_var').append('<tr><td><input type="file" name="userfile[]" /></td></tr>');
        });
    });
</script>