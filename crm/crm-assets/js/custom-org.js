$(document).ready(function() {
	
	$.validate({
	    modules : 'location, date, security, file',
	    onModulesLoaded : function() {
	      $('#country').suggestCountry();
	    }
  	});

    setTimeout(function() {
      $("#successMessage").hide('blind', {}, 500)
    }, 7000);


  	$('.cancel').on('click', function() {
  		window.location.href=$(this).attr('rel');
  	})

  	$('.selAll').on('click', function () {
  		$obj = $(this);
  		if($obj.is(':checked')){
  			$obj.parents('thead').siblings('tbody').find('input[name="selData"]').prop('checked', true);;
  		} else {
  			$obj.parents('thead').siblings('tbody').find('input[name="selData"]').removeAttr('checked');
  		}
  	});

  	$('body').on('click', '.delSelected', function() {
  		$obj = $(this);
  		$tabClass = $obj.attr('rel');
  		$base_url = $obj.attr('data-base-url');
  		$arr = [];
  		$('#cnfrm_delete').find('.modal-body').find('input[name="ids"]').remove();
  		$('table.' + $tabClass).find('tbody').find('input[name="selData"]').each(function() {
  			$inpObj = $(this);
  			if($inpObj.is(':checked')){
  				$arr.push($inpObj.val());
  			}
  		});
  		if($arr.length > 0) {
  			//console.log($arr);
  			$('#cnfrm_delete').find('.yes-btn').attr('href', $base_url+$arr.join('-'));
  			$('#cnfrm_delete').modal('show');
  		}
  	});

  /* Script for profile page start here */

  $("#fileUpload").on('change', function () {
    if (typeof (FileReader) != "undefined") {
      var image_holder = $("#image-holder");
      image_holder.empty();
      var reader = new FileReader();
      reader.onload = function (e) {
        $("<img />", {
          "src": e.target.result,
          "class": "thumb-image setpropileam"
        }).appendTo(image_holder);
      }
      image_holder.show();
      reader.readAsDataURL($(this)[0].files[0]);
    } else {
      alert("This browser does not support FileReader.");
    }
  });


  $('#profileSubmit').on('click', function() {
    $res = 1;
    $('div.form-group').each(function() {
      if($(this).hasClass('has-error')){
        $res = 0;
      }
    });
    if($res == 1) {
      $('form').submit();
    }
  })

  $('#profileEmail').bind('change keyup', function() {
    $obj = $(this);
    $obj.parents('div.form-group')
        .removeClass('has-error')
        .find('span.text-red').remove();
    var email = $obj.val();
    var uId = $('[name="id"]').val();
    $.ajax({
      url:  $('body').attr('data-base-url') + 'user/checEmailExist',
      method:'post',
      data:{
        email :email,
        uId : uId
      }
    }).done(function(data) {
      if(data == 0) {
        $obj
        .after('<span class="text-red">This Email Already Exist...</span>')
        .parents('div.form-group')
        .addClass('has-error');
      }
      console.log(data);
    })
  })

  /* Script for profile page End here */

  /* Script for user page start here */
  $('.InviteUser').on('click', function() {
    $('#nameModal_user').find('.box-title').text('Invite People');
    $('#nameModal_user').find('.modal-body').html('<div class="row">'+
        '<div class="col-md-12 m-t-20 form-horizontal">'+
          '<label for="sEmail" class="">Enter Email Address</label>'+
          '<textarea name="emails" id="" rows="5" class="form-control"></textarea>'+
          '<span class="help-text">Enter Valid Emails Saperated by comma (,)</span>'+
          '<p>'+
            '<button class="btn btn-primary pull-right send-btn">Send</button>'+
          '</p>'+
        '</div>'+
      '</div>');
    $('#nameModal_user').modal('show');
  });

  $('.modal-body').on('click', '.send-btn', function() {
    $obj = $(this);
    $obj.html('<i class="fa fa-cog fa-spin"></i> Send');
    $obj.parents('div.row').find('.msg-div').remove();
    $emails = $obj.parents('.modal-body').find('textarea').val();
    if($emails != ''){
      $.ajax({
        url: $('body').attr('data-base-url') + 'user/InvitePeople',
        method:'post',
        data: {
          emails: $emails
        },
        dataType: 'json'
      }).done(function(data){
        console.log(data);
        if(data) {
          var part = '';
          if(data.noTemplate != 0){
            part = '<p><strong>Info:</strong> '+data.noTemplate+'</p>';
          }
          $obj.parents('div.row').prepend('<div class="col-md-12 m-t-20 msg-div"><div class="alert alert-info" role="alert">'+
                                  '<span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>'+
                                  '<div class="msg-p">'+
                                  '<p><strong>Success:</strong> '+data.seccessCount+' Invitation Sent successfully</p>'+
                                  '<p><strong>Info:</strong> '+data.existCount+' Emails Alredy present in database</p>'+
                                  '<p><strong>Info:</strong> '+data.invalidEmailCount+' Invalid Emails Found</p>'+part+
                                  '</div>'+
                                  '</div>'+
                                '</div>');
          $obj.html('Send');
        }
      });            
    } else {
      alert('Enter Email First');
    }
  });   

  $(".content-wrapper").on("click",".modalButtonUser", function(e) {
    $.ajax({
      url : $('body').attr('data-base-url') + 'user/get_modal',
      method: 'post', 
      data : {
        id: $(this).attr('data-src')
      }
    }).done(function(data) {
      $('#nameModal_user').find('.modal-body').html(data);
      $('#nameModal_user').modal('show'); 
    })
  });

/*  $("#nameModal_user").on("hidden.bs.modal", function(){
    $(this).find("iframe").html("");
    $(this).find("iframe").attr("src", "");
    });*/
  /* Script for user page end here */

  /* Script for Templates Starts here */
    $('.box-body').on('click', '.view_template', function() {
      $obj = $(this);
      $tmp_id = $obj.attr('data-src');
      $.ajax({
        url: $('body').attr('data-base-url') + "templates/preview",
        method:'post',
        data:{
          template_id: $tmp_id
        }
      }).done(function(data) {
        $('#previewModal').find('div.modal-body').html(data);
        $('#previewModal').modal('show');
        $('#previewModal').find('a').attr('href', 'javascript:;');
      });
    });

  $(".content-wrapper").on("click",".templateModalButton", function(e) {  
    $.ajax({
      url : $('body').attr('data-base-url') + "templates/modal_form",
      method: "post", 
      data : {
      id: $(this).attr("data-src")
      }
      }).done(function(data) {
      $("#nameModal_Templates").find(".modal-body").html(data);
      $("#nameModal_Templates").modal("show"); 
    })
  });
  /* Script for Templates End here */
});

function setId(id, module) {
  var url =  $('body').attr('data-base-url');
  $("#cnfrm_delete").find("a.yes-btn").attr("href",url+"/"+ module +"/delete/"+id);
}

function resizeIframe(obj) { 
  obj.style.height = obj.contentWindow.document.body.scrollHeight + "px";
}
function rowTotal2(key,price,totaltax=0,discount=0){

  var  numberchanged = $('#newqty-'+key).val();
  var  oldQty = $('#oldqty-'+key).val();
  if(numberchanged>0){
      var subtotal = numberchanged*price;
      if(totaltax>0){
        taxamount = subtotal*(totaltax/100);
        subtotal = (subtotal+taxamount);
      }
      if(discount>0){
        discountamount = subtotal*(discount/100);
        subtotal = (subtotal-discountamount);
      }
  }
  else{
      var subtotal=0;
  }
  
    $("#newsubtotal-"+key).val(subtotal);
    $("#result-"+key).text(subtotal);
    var invoiceTotal = 0;
    var invoiceTotalTax = 0;
    var invoiceTotalDiscount = 0;
    var length = parseInt($("#prdcount").val());
    flg=0;
    disflg = 0
    for (var i = 0; i < length; i++) {
        var newsubtotal = parseFloat($("#newsubtotal-"+i).val());
        invoiceTotal += newsubtotal; 
        prdprice = parseInt($("#price-"+i).val());      
        prdQty = parseInt($('#newqty-'+i).val());
        if(($("#tax-"+i).val()>0) && $('#newqty-'+i).val()>0){
          flg=1;
          taxperc = parseInt($("#tax-"+i).val());
          prdtotal = prdQty*prdprice;
          taxamount = prdtotal*(taxperc/100);
          $("#eachproducttax-"+i).val(taxamount);
          invoiceTotalTax +=taxamount;
        }
        
        if(($("#discount-"+i).val()>0) && $('#newqty-'+i).val()>0){
          disflg=1;
          discountperc = parseInt($("#discount-"+i).val());
          prdtotal1 = prdQty*prdprice;
          discountValue = prdtotal1*(discountperc/100);
          $("#eachproductdiscount-"+i).val(discountValue);
          invoiceTotalDiscount +=discountValue;
        }
    }
    if(flg==0){
      invoiceTotalTax=0;
    }
    if(disflg==0){
      invoiceTotalDiscount=0;
    }
    $("#invoiceTotal").val(invoiceTotal);
    $("#paydue").text(invoiceTotal);
    $("#totalTax").text(invoiceTotalTax);
    $("#totalDiscountTxt").text(invoiceTotalDiscount);
    $("#invoicesubtotal").val(invoiceTotal);
    $("#invoicetax").val(invoiceTotalTax);
    $("#invoicediscount").val(invoiceTotalDiscount);
    $("#invoicetotal").val(invoiceTotal);
}
var rowTotal1 = function (numb) {
  //most res
  var result;
  var page = '';
  var totalValue = 0;
  var amountVal = accounting.unformat($("#amount-" + numb).val(), accounting.settings.number.decimal);
  var priceVal = accounting.unformat($("#price-" + numb).val(), accounting.settings.number.decimal);
  var discountVal = accounting.unformat($("#discount-" + numb).val(), accounting.settings.number.decimal);
  var vatVal = accounting.unformat($("#vat-" + numb).val(), accounting.settings.number.decimal);
  var taxo = 0;
  var disco = 0;
  var totalPrice = amountVal.toFixed(two_fixed) * priceVal;
  var tax_status = $("#taxformat option:selected").val();
  var disFormat = $("#discount_format").val();
  if ($("#inv_page").val() == 'new_i' && formInputGet("#pid", numb) > 0) {
      var alertVal = accounting.unformat($("#alert-" + numb).val(), accounting.settings.number.decimal);
      if (alertVal <= +amountVal) {
          var aqt = alertVal - amountVal;
          alert('Low Stock! ' + accounting.formatNumber(aqt));
      }
  }
  //tax after bill
  if (tax_status == 'yes') {
      if (disFormat == '%' || disFormat == 'flat') {
          //tax
          var Inpercentage = precentCalc(totalPrice, vatVal);
          totalValue = totalPrice + Inpercentage;
          taxo = accounting.formatNumber(Inpercentage);
          if (disFormat == 'flat') {
              disco = accounting.formatNumber(amountVal*discountVal);
              totalValue = totalValue - discountVal*amountVal;
          } else if (disFormat == '%') {
              var discount = precentCalc(totalValue, discountVal);
              totalValue = totalValue - discount;
              disco = accounting.formatNumber(discount);
          }
      } else {
//before tax
          if (disFormat == 'bflat') {
              disco = accounting.formatNumber(discountVal*amountVal);
              totalValue = totalPrice - discountVal*amountVal;
          } else if (disFormat == 'b_p') {
              var discount = precentCalc(totalPrice, discountVal);
              totalValue = totalPrice - discount;
              disco = accounting.formatNumber(discount);
          }

          //tax
          var Inpercentage = precentCalc(totalValue, vatVal);
          totalValue = totalValue + Inpercentage;
          taxo = accounting.formatNumber(Inpercentage);
      }
  } else if (tax_status == 'inclusive') {
      if (disFormat == '%' || disFormat == 'flat') {
          //tax
          var Inpercentage = (totalPrice * vatVal) / (100 + vatVal);
          totalValue = totalPrice;
          taxo = accounting.formatNumber(Inpercentage);
          if (disFormat == 'flat') {
              disco = accounting.formatNumber(discountVal*amountVal);
              totalValue = totalValue - discountVal*amountVal;
          } else if (disFormat == '%') {
              var discount = precentCalc(totalValue, discountVal);
              totalValue = totalValue - discount;
              disco = accounting.formatNumber(discount);
          }
      } else {
//before tax
          if (disFormat == 'bflat') {
              disco = accounting.formatNumber(discountVal*amountVal);
              totalValue = totalPrice - discountVal;
          } else if (disFormat == 'b_p') {
              var discount = precentCalc(totalPrice, discountVal);
              totalValue = totalPrice - discount;
              disco = accounting.formatNumber(discount);
          }
          //tax
          var Inpercentage = (totalPrice * vatVal) / (100 + vatVal);
          totalValue = totalValue;
          taxo = accounting.formatNumber(Inpercentage);
      }
  } else {
      taxo = 0;
      if (disFormat == '%' || disFormat == 'flat') {
          if (disFormat == 'flat') {
              disco = accounting.formatNumber(discountVal*amountVal);
              totalValue = totalPrice - discountVal*amountVal;
          } else if (disFormat == '%') {
              var discount = precentCalc(totalPrice, discountVal);
              totalValue = totalPrice - discount;
              disco = accounting.formatNumber(discount);
          }

      } else {
//before tax
          if (disFormat == 'bflat') {
              disco = accounting.formatNumber(discountVal*amountVal);
              totalValue = totalPrice - discountVal*amountVal;
          } else if (disFormat == 'b_p') {
              var discount = precentCalc(totalPrice, discountVal);
              totalValue = totalPrice - discount;
              disco = accounting.formatNumber(discount);
          }
      }
  }
  $("#result-" + numb).html(accounting.formatNumber(totalValue));
  $("#taxa-" + numb).val(taxo);
  $("#texttaxa-" + numb).text(taxo);
  $("#disca-" + numb).val(disco);
  $("#total-" + numb).val(accounting.formatNumber(totalValue));
  samanYog();
};