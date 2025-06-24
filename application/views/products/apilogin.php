<div class="content-body">

    <div class="card">
        <div class="card-header">
            <h5 class="title"> <?php echo "Login" ?></h5>
            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
            <div class="heading-elements">
                <ul class="list-inline mb-0">                    
                    <li><a class="breaklink" data-action="expand"><i class="ft-maximize"></i></a></li>                    
                </ul>
            </div>
        </div>
        <div class="card-content">
            <div id="notify" class="alert alert-success" style="display:none;">
                <a href="#" class="close" data-dismiss="alert">&times;</a>

                <div class="message"></div>
            </div>
            <div class="card-body">
                 <form method="POST" id="data_form">
                    <div class="row">
                    
                            <div class="col-2">
                                <label class="col-form-label" for="product_catname"><?php echo "Email"; ?><span class="compulsoryfld">*</span></label>
                                <input type="email" name="email" id="email" class="form-control" required placeholder="Email">
                            </div>
                            <div class="col-2">
                                 <label class="col-form-label" for="product_catname"><?php echo "Password"; ?><span class="compulsoryfld">*</span></label>
                                <input type="text" name="password" id="password" class="form-control" required placeholder="Password">
                            </div>
                            <div class="col-2"><input type="submit" value="Submit" class="btn btn-primary  mt-32px" id="btn-add"></div>
                        
                    </div>
                </form>
                    <div class="entered_data mt-2"></div>
                    <textarea name="details" id="details" class="form-control mt-3" rows="15"></textarea>
                  
                </div>
            </div>
        </div>
    </div>
   <script>
$(document).ready(function() {
        $("#data_form").validate($.extend(true, {}, globalValidationOptions,{
            rules: {
                email: {required:true},
                password: {required:true},
            },
            messages: {
                email    : "Enter Email",
                password    : "Enter Password",
            }
        }));    
});
    $("#btn-add").on("click", function(e) {
        e.preventDefault(); 
        var formData = $("#data_form").serialize(); 
        if ($("#data_form").valid()) {
            $.ajax({
                type: 'POST',
                url: baseurl +'Apilogin/applogin',
                data: formData,
                success: function(response) {
                    $("#details").text(response);
                    var email ="Email : "+$("#email").val();
                    var password ="Password : "+$("#password").val();
                    $(".entered_data").text(email + " "+password);
                    $("#email").val("");
                    $("#password").val("");
                    // location.reload();
                },
                error: function(xhr, status, error) {
                    // Handle error
                    console.error(xhr.responseText);
                }
            });
        }
    }); 
   </script>