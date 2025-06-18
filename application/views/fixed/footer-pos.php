<!-- BEGIN VENDOR JS-->
<script type="text/javascript">
    $('[data-toggle="datepicker"]').datepicker({
        autoHide: true,
        format: '<?php echo $this->config->item('dformat2'); ?>'
    });
    $('[data-toggle="datepicker"]').datepicker('setDate', '<?php echo dateformat(date('Y-m-d')); ?>');
    $('#sdate').datepicker({autoHide: true, format: '<?php echo $this->config->item('dformat2'); ?>'});
    $('#sdate').datepicker('setDate', '<?php echo dateformat(date('Y-m-d', strtotime('-30 days', strtotime(date('Y-m-d'))))); ?>');
    $('.date30').datepicker({autoHide: true, format: '<?php echo $this->config->item('dformat2'); ?>'});
    $('.date30').datepicker('setDate', '<?php echo dateformat(date('Y-m-d', strtotime('-30 days', strtotime(date('Y-m-d'))))); ?>');
</script>
<script src="<?= assets_url() ?>app-assets/vendors/js/extensions/unslider-min.js"></script>
<script src="<?= assets_url() ?>app-assets/vendors/js/timeline/horizontal-timeline.js"></script>
<script src="<?= assets_url() ?>app-assets/js/core/app-menu.js"></script>
<script src="<?= assets_url() ?>app-assets/js/core/app.js"></script>
<script type="text/javascript" src="<?= assets_url() ?>app-assets/js/scripts/ui/breadcrumbs-with-stats.js"></script>
<script src="<?php echo assets_url(); ?>assets/myjs/jquery-ui.js"></script>
<script src="<?php echo assets_url(); ?>assets/myjs/jquery.dataTables.min.js"></script>
<script type="text/javascript">var dtformat = $('#hdata').attr('data-df');
    var currency = $('#hdata').attr('data-curr');
    ;</script>
<script src="<?php echo assets_url('assets/myjs/custom.js') . APPVER; ?>"></script>
<script src="<?php echo assets_url('assets/myjs/basic.js') . APPVER; ?>"></script>
<script src="<?php echo assets_url('assets/myjs/control.js') . APPVER; ?>"></script>
<script src="<?= assets_url() ?>app-assets/js/scripts/pages/chat-application.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
     addMissingTitles();
    });
    $.ajax({
        url: baseurl + 'manager/pendingtasks',
        dataType: 'json',
        success: function (data) {
            $('#tasklist').html(data.tasks);
            $('#taskcount').html(data.tcount);
            $('#messagecount').prop("title",data.tcount);
        },
        error: function (data) {
            $('#response').html('Error')
        }
    });
    if (localStorage.show == 'no') {
        $("#pos0").fadeOut();
        $("body").css('padding-top', '0rem');
        $(".content-wrapper").css('padding-top', '0rem');
        $('#hide_header').attr('id', 'show_header');
    }
    $(document).on('click', "#show_header", function (e) {
        $("#pos0").fadeIn();
        $("body").css('padding-top', '4rem');
        $(".content-wrapper").css('padding-top', '1rem');
        localStorage.setItem("show", "yes");
        $(this).attr('id', 'hide_header');
    });
    $(document).on('click', "#hide_header", function (e) {
        $("#pos0").fadeOut();
        $("body").css('padding-top', '0rem');
        $(".content-wrapper").css('padding-top', '0rem');
        $(this).attr('id', 'show_header');
        localStorage.setItem("show", "no");
    });


    
    // $(".btn-sm").hide();    
    // $(".btn").hide();    
    // $(".history-expand-button").hide();    
    // $(".topsection-caption1").hide();    
    // $(".bottomsection-caption").hide();    
    // $(".bottomsection-caption1").hide();    
    // $(".btn-modules").show();    
    // $(".btn-crud").show();    
    // $(".btncard").show();    
    // if (Array.isArray(permissions) && permissions.length > 0) 
    // {
    //     const buttonsToDisplay = permissions.map(permission => permission.function);
    //     $(".btn-sm").each(function () {
    //         if (buttonsToDisplay.includes($(this).text().trim())) {
    //             $(this).show();
    //         }
    //     });
    //     $(".btn").each(function () {
    //         if (buttonsToDisplay.includes($(this).text().trim())) {
    //             $(this).show();
    //         }
    //     });
    //     $(".topsection-caption1").each(function () {
    //         if (buttonsToDisplay.includes($(this).text().trim())) {
    //             $(this).show();
    //         }
    //     });
    //     $(".bottomsection-caption").each(function () {
    //         if (buttonsToDisplay.includes($(this).text().trim())) {
    //             $(this).show();
    //         }
    //     });
    //     $(".bottomsection-caption1").each(function () {
    //         if (buttonsToDisplay.includes($(this).text().trim())) {
    //             $(this).show();
    //         }
    //     });

    //     const historyExpandButton = $(".history-expand-button");
    //     if (buttonsToDisplay.includes(historyExpandButton.text().trim())) {
    //         historyExpandButton.show();
    //     }
    // }


</script>

</body>
</html>

