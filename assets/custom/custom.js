var billtype = $('#billtype').val();
var d_csrf = crsf_token + '=' + crsf_hash;
$('#addproduct').on('click', function () {
    var cvalue = parseInt($('#ganak').val()) + 1;
    var nxt = parseInt(cvalue);
    $('#ganak').val(nxt);
    var functionNum = "'" + cvalue + "'";
    count = $('#saman-row div').length;
    var data = '<tr><td><input type="text" class="form-control" name="product_name[]" placeholder="Search by Product Name or Item No." id="productname-' + cvalue + '"></td><td><input type="number" class="form-control req amnt" name="product_qty[]" id="amount-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" value="1"  inputmode="numeric"><input type="hidden" id="alert-' + cvalue + '" value=""  name="alert[]"> </td><td><input type="text" class="form-control req prc" name="product_price[]" id="price-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" inputmode="numeric"></td><td> <input type="text" class="form-control vat" name="product_tax[]" id="vat-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" inputmode="numeric"></td> <td id="texttaxa-' + cvalue + '" class="text-center">0</td> <td><input type="text" class="form-control discount" name="product_discount[]" onkeypress="return isNumber(event)" id="discount-' + cvalue + '" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off"></td> <td><span class="currenty">' + currency + '</span> <strong><span class=\'ttlText\' id="result-' + cvalue + '">0</span></strong></td> <td class="text-center"><button type="button" data-rowid="' + cvalue + '" class="btn btn-sm btn-default removeProd" title="Remove" > <i class="fa fa-trash"></i> </button> </td><input type="hidden" name="taxa[]" id="taxa-' + cvalue + '" value="0"><input type="hidden" name="disca[]" id="disca-' + cvalue + '" value="0"><input type="hidden" class="ttInput" name="product_subtotal[]" id="total-' + cvalue + '" value="0"> <input type="hidden" class="pdIn" name="pid[]" id="pid-' + cvalue + '" value="0"> <input type="hidden" name="unit[]" id="unit-' + cvalue + '" value=""> <input type="hidden" name="hsn[]" id="hsn-' + cvalue + '" value=""> <input type="hidden" name="serial[]" id="serial-' + cvalue + '" value=""> </tr><tr><td colspan="9"><textarea class="form-control"  id="dpid-' + cvalue + '" name="product_description[]" placeholder="Enter Product description" autocomplete="off"></textarea></td></tr>';
    //ajax request
    // $('#saman-row').append(data);
    $('tr.last-item-row').before(data);
    row = cvalue;

    $('#productname-' + cvalue).autocomplete({
        
        source: function (request, response) {
            $.ajax({
                url: baseurl + 'search_products/' + billtype,
                dataType: "json",
                method: 'post',
                data: 'name_startsWith=' + request.term + '&type=product_list&row_num=' + row + '&wid=' + $("#s_warehouses option:selected").val() + '&' + d_csrf,
                success: function (data) {
                    response($.map(data, function (item) {
                        // var product_d = item[0];
                        var product_d = item[0]+" ("+item[7]+")";
                        return {
                            label: product_d,
                            value: product_d,
                            data: item
                        };
                    }));
                }
            });
        },
        autoFocus: true,
        minLength: 0,
        select: function (event, ui) {
            id_arr = $(this).attr('id');
            id = id_arr.split("-");
            var t_r = ui.item.data[3];
            if ($("#taxformat option:selected").attr('data-trate')) {

                t_r = $("#taxformat option:selected").attr('data-trate');
            }
            var discount = ui.item.data[4];
            var custom_discount = $('#custom_discount').val();
            if (custom_discount > 0) discount = deciFormat(custom_discount);
            $("#onhandQty-" + id[1]).text(parseInt(ui.item.data[8]));
            $("#prd-" + id[1]).val(ui.item.data[2]);
            $('#amount-' + id[1]).val(1);
            $('#price-' + id[1]).val(ui.item.data[1]);
            $('#pid-' + id[1]).val(ui.item.data[2]);
            $('#vat-' + id[1]).val(t_r);
            $('#discount-' + id[1]).val(discount);
            $('#dpid-' + id[1]).val(ui.item.data[5]);
            $('#unit-' + id[1]).val(ui.item.data[6]);
            $('#hsn-' + id[1]).val(ui.item.data[7]);
            $('#alert-' + id[1]).val(parseInt(ui.item.data[8]));
            $('#serial-' + id[1]).val(ui.item.data[10]);
            rowTotal(cvalue);
            billUpyog();


        },
        create: function (e) {
            $(this).prev('.ui-helper-hidden-accessible').remove();
        }
    });

});

$('#addcosting').on('click', function () {
    var cvalue = parseInt($('#ganak').val()) + 1;
    var nxt = parseInt(cvalue);
    $('#ganak').val(nxt);
    var functionNum = "'" + cvalue + "'";
    count = $('#saman-row div').length;
    rowno = parseInt(cvalue)+1;
    var currencycode = $("#currency_id").val();
    var currencyrate = $("#currency_rate").val();
    var bill_number = $("#bill_number").val();
    var bill_date = $("#bill_date").val();
    var costdata = '<tr><td>'+rowno+'<td><input type="text" class="form-control" name="expense_name[]" placeholder="Expense Name" id="expense_name-'+cvalue+'"></td><td><input type="number" name="expense_id[]" id="expense_id-'+cvalue+'" class="form-control"  style="width:100px;" readonly></td><td><input type="text" name="payable_acc[]" id="payable_acc-'+cvalue+'" class="form-control" style="width:250px;" placeholder="Account Name or Number"></td><td><input type="number" name="payable_acc_no[]" id="payable_acc_no-'+cvalue+'" class="form-control" style="width:150px;"></td><td><input type="text" name="bill_number_cost[]" id="bill_number_cost-'+cvalue+'" value="'+bill_number+'" class="form-control billnumber" style="width:150px;"></td>  <td><input type="date" name="bill_date_cost[]" id="bill_date_cost-'+cvalue+'" value="'+bill_date+'"  class="form-control billdate" style="width:150px;"></td><td><input type="number" name="costing_amount[]" id="costing_amount-'+cvalue+'" class="form-control" style="width:100px;" onkeyup="costingamount('+cvalue+')"></td><td><input type="text" name="currency_cost[]" id="currency_cost-'+cvalue+'" class="form-control" value="'+currencycode+'" style="width:150px;" readonly></td> <td><input type="number" name="currency_rate_cost[]" id="currency_rate_cost-'+cvalue+'" value="'+currencyrate+'"  class="form-control" style="width:150px;" readonly></td><td><input type="number" name="costing_amount_net[]" id="costing_amount_net-'+cvalue+'" class="form-control" style="width:100px;" readonly><td><input type="number" name="costing_amount_qar[]" id="costing_amount_qar-'+cvalue+'" class="form-control" style="width:100px;" readonly></td><td><textarea name="remarks[]" id="remarks-'+cvalue+'" class="form-control" style="width:250px;"></textarea></td></tr>';
    //ajax request
    // $('#saman-row').append(data);
    $('tr.last-item-row').before(costdata);
    row = cvalue;
    $('#payable_acc-'+cvalue).autocomplete({
        source: function (request, response) {
            $.ajax({
                url: baseurl + 'search_products/payableaccount_search',
                dataType: "json",
                method: 'post',
                data: 'name_startsWith=' + request.term + '&' + d_csrf,
                success: function (data) {               
                    response($.map(data, function (item) {
                        var id = item[0];
                        return {
                            label: item[1],
                            value: item[1],
                            data: item
                        };
                    }));
                }
            });
        },
        autoFocus: true,
        minLength: 0,
        select: function (event, ui) {
           $('#payable_acc_no-'+ cvalue).val(ui.item.data[2]);
        }
    });
    $('#expense_name-' + cvalue).autocomplete({        
        source: function (request, response) {
            $.ajax({
                url: baseurl + 'search_products/' + billtype,
                dataType: "json",
                method: 'post',
                data: 'name_startsWith=' + request.term + '&type=product_list&row_num=' + row + '&wid=' + $("#s_warehouses option:selected").val() + '&' + d_csrf,
                success: function (data) {
                    response($.map(data, function (item) {
                        var id = item[0];
                        return {
                            label: item[1],
                            value: item[1],
                            data: item
                        };
                    }));
                }
            });
        },
        autoFocus: true,
        minLength: 0,
        select: function (event, ui) {
            id_arr = $(this).attr('id');
            id = id_arr.split("-");
            $('#expense_id-'+cvalue).val(ui.item.data[0]);
        },
        create: function (e) {
            $(this).prev('.ui-helper-hidden-accessible').remove();
        }
    });

});
function expensedynamicsearch(cvalue) {
    $('#expense_name-' + cvalue).autocomplete({        
        source: function (request, response) {
            $.ajax({
                url: baseurl + 'search_products/' + billtype,
                dataType: "json",
                method: 'post',
                data: {
                    name_startsWith: request.term,
                    csrf: d_csrf
                },
                success: function (data) {
                    response($.map(data, function (item) {
                        var id = item[0];
                        return {
                            label: item[1],
                            value: item[1],
                            data: item
                        };
                    }));
                }
            });
        },
        autoFocus: true,
        minLength: 0,
        select: function (event, ui) {
            id_arr = $(this).attr('id');
            id = id_arr.split("-");
            $('#expense_id-'+cvalue).val(ui.item.data[0]);
        },
        create: function (e) {
            $(this).prev('.ui-helper-hidden-accessible').remove();
        }
    });
}
function accountdynamicsearch(cvalue) {
    var selector = '#payable_acc-' + cvalue;
    var element = $(selector);
    if (element.length) {
        element.autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: baseurl + 'search_products/payableaccount_search',
                    dataType: "json",
                    method: 'post',
                    data: {
                        name_startsWith: request.term,
                        csrf: d_csrf
                    },
                    success: function (data) {
                        console.log('Response:', data);
                        response($.map(data, function (item) {
                            var id = item[0];
                            return {
                                label: item[1],
                                value: item[1],
                                data: item
                            };
                        }));
                    },
                    error: function (xhr, status, error) {
                        console.error('AJAX Error:', status, error);
                    }
                });
            },
            autoFocus: true,
            minLength: 0,
            select: function (event, ui) {
                var id_arr = $(this).attr('id').split("-");
                $('#payable_acc_no-' + cvalue).val(ui.item.data[2]);
            },
            create: function (e) {
                $(this).prev('.ui-helper-hidden-accessible').remove();
            }
        });
    } else {
        console.error('Element not found:', selector);
    }
}
$(document).ready(function() {
    $("#response-alert").addClass("d-none");
    $("#bill_number").on("keyup", function() {
        $(".billnumber").val($("#bill_number").val());
    });
    $("#bill_date").on("change", function() {
        $(".billdate").val($("#bill_date").val());
    });

    $('[id^=expense_name-]').each(function() {
        var cvalue = $(this).data('id');
        expensedynamicsearch(cvalue);
    });

    
    $('[id^=payable_acc-]').each(function() {
        var cvalue = $(this).data('id');
        accountdynamicsearch(cvalue);
    });





    
    ////erp2024 customer add section ends 03-06-2024

    
    //ero2024 customer edit section ends 03-06-2024

});




$('#addproduct_quotecreate').on('click', function () {
    var cvalue = parseInt($('#ganak').val()) + 1;
    var nxt = parseInt(cvalue);
    $('#ganak').val(nxt);
    var functionNum = "'" + cvalue + "'";
    count = $('#saman-row div').length;
    var data = '<tr><td><input type="text" class="form-control" name="product_name[]" placeholder="Search by Product Name or Item No." id="productname-' + cvalue + '"></td><td><input type="number" class="form-control req amnt" name="product_qty[]" id="amount-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" value="1"  inputmode="numeric"><input type="hidden" id="alert-' + cvalue + '" value=""  name="alert[]"> </td><td class="text-center"><strong id="onhandQty-' + cvalue + '"></strong></td><td><input type="text" class="form-control req prc" name="product_price[]" id="price-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" inputmode="numeric"></td><td> <input type="text" class="form-control vat" name="product_tax[]" id="vat-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" inputmode="numeric"></td> <td id="texttaxa-' + cvalue + '" class="text-center">0</td> <td><input type="text" class="form-control discount" name="product_discount[]" onkeypress="return isNumber(event)" id="discount-' + cvalue + '" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off"></td> <td><span class="currenty">' + currency + '</span> <strong><span class=\'ttlText\' id="result-' + cvalue + '">0</span></strong></td> <td class="text-center"><button type="button" data-rowid="' + cvalue + '" class="btn btn-sm btn-default removeProd" title="Remove" > <i class="fa fa-trash"></i> </button> <button onclick="producthistory('+cvalue+')" type="button" class="btn btn-sm btn-secondary producthis"><i class="fa fa-history"></i></button> </td><input type="hidden" name="taxa[]" id="taxa-' + cvalue + '" value="0"><input type="hidden" name="disca[]" id="disca-' + cvalue + '" value="0"><input type="hidden" class="ttInput" name="product_subtotal[]" id="total-' + cvalue + '" value="0"> <input type="hidden" class="pdIn" name="pid[]" id="pid-' + cvalue + '" value="0"> <input type="hidden" name="unit[]" id="unit-' + cvalue + '" value=""> <input type="hidden" name="hsn[]" id="hsn-' + cvalue + '" value=""> <input type="hidden" name="serial[]" id="serial-' + cvalue + '" value=""> </tr><tr><td colspan="9"><textarea class="form-control"  id="dpid-' + cvalue + '" name="product_description[]" placeholder="Enter Product description" autocomplete="off"></textarea></td></tr>';
    //ajax request
    // $('#saman-row').append(data);
    $('tr.last-item-row').before(data);
    row = cvalue;

    $('#productname-' + cvalue).autocomplete({
        
        source: function (request, response) {
            $.ajax({
                url: baseurl + 'search_products/' + billtype,
                dataType: "json",
                method: 'post',
                data: 'name_startsWith=' + request.term + '&type=product_list&row_num=' + row + '&wid=' + $("#s_warehouses option:selected").val() + '&' + d_csrf,
                success: function (data) {
                    response($.map(data, function (item) {
                        // var product_d = item[0];
                        var product_d = item[0]+" ("+item[7]+")";
                        return {
                            label: product_d,
                            value: product_d,
                            data: item
                        };
                    }));
                }
            });
        },
        autoFocus: true,
        minLength: 0,
        select: function (event, ui) {
            id_arr = $(this).attr('id');
            id = id_arr.split("-");
            var t_r = ui.item.data[3];
            if ($("#taxformat option:selected").attr('data-trate')) {

                t_r = $("#taxformat option:selected").attr('data-trate');
            }
            var discount = ui.item.data[4];
            var custom_discount = $('#custom_discount').val();
            if (custom_discount > 0) discount = deciFormat(custom_discount);
            $("#onhandQty-" + id[1]).text(parseInt(ui.item.data[8]));
            $("#prd-" + id[1]).val(ui.item.data[2]);
            $('#amount-' + id[1]).val(1);
            $('#price-' + id[1]).val(ui.item.data[1]);
            $('#pid-' + id[1]).val(ui.item.data[2]);
            $('#vat-' + id[1]).val(t_r);
            $('#discount-' + id[1]).val(discount);
            $('#dpid-' + id[1]).val(ui.item.data[5]);
            $('#unit-' + id[1]).val(ui.item.data[6]);
            $('#hsn-' + id[1]).val(ui.item.data[7]);
            $('#alert-' + id[1]).val(parseInt(ui.item.data[8]));
            $('#serial-' + id[1]).val(ui.item.data[10]);
            rowTotal(cvalue);
            billUpyog();


        },
        create: function (e) {
            $(this).prev('.ui-helper-hidden-accessible').remove();
        }
    });

});



//For quote edit
$('#addproduct_quoteedit').on('click', function () {
    var cvalue = parseInt($('#ganak').val()) + 1;
    var nxt = parseInt(cvalue);
    $('#ganak').val(nxt);
    var functionNum = "'" + cvalue + "'";
    count = $('#saman-row div').length;
    count = $('#saman-row div').length;
var data = '<tr>' +
    '<td><input type="checkbox" class="checkedproducts" name="product_id[]" id="prd-' + cvalue + '"></td>' +
    '<td><input type="text" class="form-control" name="product_name[]" placeholder="Search by Product Name or Item No." id="productname-' + cvalue + '"></td>' +
    '<td><input type="number" class="form-control req amnt" name="product_qty[]" id="amount-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" value="1" inputmode="numeric">' +
    '<input type="hidden" id="alert-' + cvalue + '" name="alert[]" value=""></td>' +
    '<td><strong id="onhandQty-' + cvalue + '"></strong></td>' +
    '<td><input type="text" class="form-control req prc" name="product_price[]" id="price-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" inputmode="numeric"></td>' +
    '<td><input type="text" class="form-control vat" name="product_tax[]" id="vat-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" inputmode="numeric"></td>' +
    '<td id="texttaxa-' + cvalue + '" class="text-center">0</td>' +
    '<td><input type="text" class="form-control discount" name="product_discount[]" id="discount-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off"></td>' +
    '<td><span class="currenty">' + currency + '</span> <strong><span class=\'ttlText\' id="result-' + cvalue + '">0</span></strong></td>' +
    '<td class="text-center"><button type="button" data-rowid="' + cvalue + '" class="btn btn-sm btn-default removeProd" title="Remove"><i class="fa fa-trash"></i></button><button onclick="producthistory(' + cvalue + ')" type="button" class="btn btn-sm btn-secondary producthis"><i class="fa fa-history"></i></button></td>' +
    '<input type="hidden" name="taxa[]" id="taxa-' + cvalue + '" value="0">' +
    '<input type="hidden" name="disca[]" id="disca-' + cvalue + '" value="0">' +
    '<input type="hidden" class="ttInput" name="product_subtotal[]" id="total-' + cvalue + '" value="0">' +
    '<input type="hidden" class="pdIn" name="pid[]" id="pid-' + cvalue + '" value="0">' +
    '<input type="hidden" name="unit[]" id="unit-' + cvalue + '" value="">' +
    '<input type="hidden" name="hsn[]" id="hsn-' + cvalue + '" value="">' +
    '<input type="hidden" name="serial[]" id="serial-' + cvalue + '" value="">' +'</tr>' +
    '<tr><td colspan="10"><textarea class="form-control" id="dpid-' + cvalue + '" name="product_description[]" placeholder="Enter Product description" autocomplete="off"></textarea></td></tr>' +
    '<tr><td colspan="10"></td></tr>';

   
    //ajax request
    // $('#saman-row').append(data);
    $('tr.last-item-row').before(data);
    console.log(data);
    row = cvalue;

    $('#productname-' + cvalue).autocomplete({
        
        source: function (request, response) {
            $.ajax({
                url: baseurl + 'search_products/' + billtype,
                dataType: "json",
                method: 'post',
                data: 'name_startsWith=' + request.term + '&type=product_list&row_num=' + row + '&wid=' + $("#s_warehouses option:selected").val() + '&' + d_csrf,
                success: function (data) {
                    response($.map(data, function (item) {
                        // var product_d = item[0];
                        var product_d = item[0]+" ("+item[7]+")";
                        return {
                            label: product_d,
                            value: product_d,
                            data: item
                        };
                    }));
                }
            });
        },
        autoFocus: true,
        minLength: 0,
        select: function (event, ui) {
            id_arr = $(this).attr('id');
            id = id_arr.split("-");
            var t_r = ui.item.data[3];
            if ($("#taxformat option:selected").attr('data-trate')) {

                t_r = $("#taxformat option:selected").attr('data-trate');
            }
            var discount = ui.item.data[4];
            var custom_discount = $('#custom_discount').val();
            if (custom_discount > 0) discount = deciFormat(custom_discount);
            $("#onhandQty-" + id[1]).text(parseInt(ui.item.data[8]));
            $("#prd-" + id[1]).val(ui.item.data[2]);
            $('#amount-' + id[1]).val(1);
            $('#price-' + id[1]).val(ui.item.data[1]);
            $('#pid-' + id[1]).val(ui.item.data[2]);
            $('#vat-' + id[1]).val(t_r);
            $('#discount-' + id[1]).val(discount);
            $('#dpid-' + id[1]).val(ui.item.data[5]);
            $('#unit-' + id[1]).val(ui.item.data[6]);
            $('#hsn-' + id[1]).val(ui.item.data[7]);
            $('#alert-' + id[1]).val(parseInt(ui.item.data[8]));
            $('#serial-' + id[1]).val(ui.item.data[10]);
            rowTotal(cvalue);
            billUpyog();


        },
        create: function (e) {
            $(this).prev('.ui-helper-hidden-accessible').remove();
        }
    });

});


//For saleorder edit 
// erp2024 newly added 06-06-2024
$('#addproduct_salesedit').on('click', function () {
    var cvalue = parseInt($('#ganak').val()) + 1;
    var nxt = parseInt(cvalue);
    $('#ganak').val(nxt);
    var functionNum = "'" + cvalue + "'";
    count = $('#saman-row div').length;
    var data = '<tr><td><input type="checkbox" class="checkedproducts" name="product_id[]" id="prd-' +cvalue +'" ></td><td><input type="text" class="form-control" name="product_name[]" placeholder="Search by Product Name or Item No." id="productname-' + cvalue + '"></td><td><input type="hidden" class="form-control discount" name="product_discount[]" onkeypress="return isNumber(event)" id="discount-' + cvalue + '" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off"><strong id="productcode-' + cvalue + '"></strong></td><td><input type="number" class="form-control req amnt" name="product_qty[]" id="amount-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" value="1"  inputmode="numeric"><input type="hidden" id="alert-' + cvalue + '" value=""  name="alert[]"> </td><td><strong id="onhandQty-' + cvalue + '"></strong></td><td><input type="text" class="form-control req prc" name="product_price[]" id="price-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" inputmode="numeric"></td><td> <input type="text" class="form-control vat" name="product_tax[]" id="vat-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" inputmode="numeric"></td> <td id="texttaxa-' + cvalue + '" class="text-center">0</td><td><span class="currenty">' + currency + '</span> <strong><span class=\'ttlText\' id="result-' + cvalue + '">0</span></strong></td><td></td> <td class="text-center"><button type="button" data-rowid="' + cvalue + '" class="btn  btn-sm btn-default removeProd" title="Remove" > <i class="fa fa-trash"></i> </button> </td><input type="hidden" name="taxa[]" id="taxa-' + cvalue + '" value="0"><input type="hidden" name="disca[]" id="disca-' + cvalue + '" value="0"><input type="hidden" class="ttInput" name="product_subtotal[]" id="total-' + cvalue + '" value="0"> <input type="hidden" class="pdIn" name="pid[]" id="pid-' + cvalue + '" value="0"> <input type="hidden" name="unit[]" id="unit-' + cvalue + '" value=""> <input type="hidden" name="hsn[]" id="hsn-' + cvalue + '" value=""> <input type="hidden" name="serial[]" id="serial-' + cvalue + '" value=""> </tr><tr><td colspan="11"><textarea class="form-control"  id="dpid-' + cvalue + '" name="product_description[]" placeholder="Enter Product description" autocomplete="off"></textarea></td></tr>';
    //ajax request 
    // $('#saman-row').append(data);
    $('tr.last-item-row').before(data);
    console.log(data);
    row = cvalue;

    $('#productname-' + cvalue).autocomplete({
        
        source: function (request, response) {
            $.ajax({
                url: baseurl + 'search_products/' + billtype,
                dataType: "json",
                method: 'post',
                data: 'name_startsWith=' + request.term + '&type=product_list&row_num=' + row + '&wid=' + $("#s_warehouses option:selected").val() + '&' + d_csrf,
                success: function (data) {
                    response($.map(data, function (item) {
                        // var product_d = item[0];
                        var product_d = item[0]+" ("+item[7]+")";
                        return {
                            label: product_d,
                            value: product_d,
                            data: item
                        };
                    }));
                }
            });
        },
        autoFocus: true,
        minLength: 0,
        select: function (event, ui) {
            id_arr = $(this).attr('id');
            id = id_arr.split("-");
            var t_r = ui.item.data[3];
            if ($("#taxformat option:selected").attr('data-trate')) {

                t_r = $("#taxformat option:selected").attr('data-trate');
            }
            var discount = ui.item.data[4];
            var custom_discount = $('#custom_discount').val();
            if (custom_discount > 0) discount = deciFormat(custom_discount);
            $("#onhandQty-" + id[1]).text(parseInt(ui.item.data[8]));
            // erp2024 newly added 06-06-2024
            $("#productcode-" + id[1]).text(ui.item.data[7]);
            // erp2024 newly added 06-06-2024 ends
            $("#prd-" + id[1]).val(ui.item.data[2]);
            $('#amount-' + id[1]).val(1);
            $('#price-' + id[1]).val(ui.item.data[1]);
            $('#pid-' + id[1]).val(ui.item.data[2]);
            $('#vat-' + id[1]).val(t_r);
            $('#discount-' + id[1]).val(discount);
            $('#dpid-' + id[1]).val(ui.item.data[5]);
            $('#unit-' + id[1]).val(ui.item.data[6]);
            $('#hsn-' + id[1]).val(ui.item.data[7]);
            $('#alert-' + id[1]).val(parseInt(ui.item.data[8]));
            $('#serial-' + id[1]).val(ui.item.data[10]);
            rowTotal(cvalue);
            billUpyog();


        },
        create: function (e) {
            $(this).prev('.ui-helper-hidden-accessible').remove();
        }
    });

});
$('#addquote_salesorder').on('click', function () {
    var cvalue = parseInt($('#ganak').val()) + 1;
    var nxt = parseInt(cvalue);
    $('#ganak').val(nxt);
    var functionNum = "'" + cvalue + "'";
    count = $('#saman-row div').length;
    var data = '<tr><td><input type="text" class="form-control" name="product_name[]" placeholder="Search by Product Name or Item No." id="productname-' + cvalue + '"></td><td><input type="hidden" class="form-control discount" name="product_discount[]" onkeypress="return isNumber(event)" id="discount-' + cvalue + '" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off"><input type="number" class="form-control req amnt" name="product_qty[]" id="amount-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" value="1"  inputmode="numeric"><input type="hidden" id="alert-' + cvalue + '" value=""  name="alert[]"> </td><td><strong id="onhandQty-' + cvalue + '"></strong></td><td><input type="text" class="form-control req prc" name="product_price[]" id="price-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" inputmode="numeric"></td><td> <input type="text" class="form-control vat" name="product_tax[]" id="vat-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" inputmode="numeric"></td> <td id="texttaxa-' + cvalue + '" class="text-center">0</td><td><span class="currenty">' + currency + '</span> <strong><span class=\'ttlText\' id="result-' + cvalue + '">0</span></strong></td><td class="text-center"><button type="button" data-rowid="' + cvalue + '" class="btn  btn-sm btn-default removeProd" title="Remove" > <i class="fa fa-trash"></i> </button> </td><input type="hidden" name="taxa[]" id="taxa-' + cvalue + '" value="0"><input type="hidden" name="disca[]" id="disca-' + cvalue + '" value="0"><input type="hidden" class="ttInput" name="product_subtotal[]" id="total-' + cvalue + '" value="0"> <input type="hidden" class="pdIn" name="pid[]" id="pid-' + cvalue + '" value="0"> <input type="hidden" name="unit[]" id="unit-' + cvalue + '" value=""> <input type="hidden" name="hsn[]" id="hsn-' + cvalue + '" value=""> <input type="hidden" name="serial[]" id="serial-' + cvalue + '" value=""> </tr><tr><td colspan="11"><textarea class="form-control"  id="dpid-' + cvalue + '" name="product_description[]" placeholder="Enter Product description" autocomplete="off"></textarea></td></tr>';
    //ajax request 
    // $('#saman-row').append(data);
    $('tr.last-item-row').before(data);
    console.log(data);
    row = cvalue;

    $('#productname-' + cvalue).autocomplete({
        
        source: function (request, response) {
            $.ajax({
                url: baseurl + 'search_products/' + billtype,
                dataType: "json",
                method: 'post',
                data: 'name_startsWith=' + request.term + '&type=product_list&row_num=' + row + '&wid=' + $("#s_warehouses option:selected").val() + '&' + d_csrf,
                success: function (data) {
                    response($.map(data, function (item) {
                        // var product_d = item[0];
                        var product_d = item[0]+" ("+item[7]+")";
                        return {
                            label: product_d,
                            value: product_d,
                            data: item
                        };
                    }));
                }
            });
        },
        autoFocus: true,
        minLength: 0,
        select: function (event, ui) {
            id_arr = $(this).attr('id');
            id = id_arr.split("-");
            var t_r = ui.item.data[3];
            if ($("#taxformat option:selected").attr('data-trate')) {

                t_r = $("#taxformat option:selected").attr('data-trate');
            }
            var discount = ui.item.data[4];
            var custom_discount = $('#custom_discount').val();
            if (custom_discount > 0) discount = deciFormat(custom_discount);
            $("#onhandQty-" + id[1]).text(parseInt(ui.item.data[8]));
            // erp2024 newly added 06-06-2024
            $("#productcode-" + id[1]).text(ui.item.data[7]);
            // erp2024 newly added 06-06-2024 ends
            $("#prd-" + id[1]).val(ui.item.data[2]);
            $('#amount-' + id[1]).val(1);
            $('#price-' + id[1]).val(ui.item.data[1]);
            $('#pid-' + id[1]).val(ui.item.data[2]);
            $('#vat-' + id[1]).val(t_r);
            $('#discount-' + id[1]).val(discount);
            $('#dpid-' + id[1]).val(ui.item.data[5]);
            $('#unit-' + id[1]).val(ui.item.data[6]);
            $('#hsn-' + id[1]).val(ui.item.data[7]);
            $('#alert-' + id[1]).val(parseInt(ui.item.data[8]));
            $('#serial-' + id[1]).val(ui.item.data[10]);
            rowTotal(cvalue);
            billUpyog();


        },
        create: function (e) {
            $(this).prev('.ui-helper-hidden-accessible').remove();
        }
    });

});
// erp2024 newly added 06-06-2024 ends result-

//for purchase section
$('#addproduct1').on('click', function () {
    var cvalue = parseInt($('#ganak').val()) + 1;
    var nxt = parseInt(cvalue);
    $('#ganak').val(nxt);
    var functionNum = "'" + cvalue + "'";
    count = $('#saman-row div').length;
    var data = '<tr><td><input type="text" class="form-control" name="product_name[]" placeholder="Search by Product Name or Item No." id="productname-' + cvalue + '"></td><td><input type="number" class="form-control req amnt" name="product_qty[]" id="amount-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" value="0"  inputmode="numeric"><input type="hidden" id="alert-' + cvalue + '" value=""  name="alert[]"> </td><td><input type="text" class="form-control req prc" name="product_price[]" id="price-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" inputmode="numeric" readonly></td><td class="d-none"> <input type="text" class="form-control vat" name="product_tax[]" id="vat-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" inputmode="numeric"></td> <td id="texttaxa-' + cvalue + '" class="text-center d-none">0</td> <td><input type="text" class="form-control discount" name="product_discount[]" onkeypress="return isNumber(event)" id="discount-' + cvalue + '" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" readonly></td> <td class="text-right"><strong><span class=\'ttlText\' id="result-' + cvalue + '">0</span></strong></td> <td class="text-center"><button type="button" data-rowid="' + cvalue + '" class="btn btn-sm btn-default removeProd" title="Remove" > <i class="fa fa-trash"></i> </button> </td><input type="hidden" name="taxa[]" id="taxa-' + cvalue + '" value="0"><input type="hidden" name="disca[]" id="disca-' + cvalue + '" value="0"><input type="hidden" class="ttInput" name="product_subtotal[]" id="total-' + cvalue + '" value="0"> <input type="hidden" class="pdIn" name="pid[]" id="pid-' + cvalue + '" value="0"> <input type="hidden" name="unit[]" id="unit-' + cvalue + '" value=""> <input type="hidden" name="hsn[]" id="hsn-' + cvalue + '" value=""> <input type="hidden" name="serial[]" id="serial-' + cvalue + '" value=""></tr>';
    //ajax request
    // $('#saman-row').append(data);
    $('tr.last-item-row').before(data);
    row = cvalue;

    $('#productname-' + cvalue).autocomplete({        
        source: function (request, response) {
            $.ajax({
                url: baseurl + 'search_products/' + billtype,
                dataType: "json",
                method: 'post',
                data: 'name_startsWith=' + request.term + '&type=product_list&row_num=' + row + '&wid=' + $("#s_warehouses option:selected").val() + '&' + d_csrf,
                success: function (data) {
                    response($.map(data, function (item) {
                        // var product_d = item[0];
                        var product_d = item[0]+" ("+item[7]+")";
                        return {
                            label: product_d,
                            value: product_d,
                            data: item
                        };
                    }));
                }
            });
        },
        autoFocus: true,
        minLength: 0,
        select: function (event, ui) {
            id_arr = $(this).attr('id');
            id = id_arr.split("-");
            var t_r = ui.item.data[3];
            if ($("#taxformat option:selected").attr('data-trate')) {

                t_r = $("#taxformat option:selected").attr('data-trate');
            }
            var discount = ui.item.data[4];
            var custom_discount = $('#custom_discount').val();
            if (custom_discount > 0) discount = deciFormat(custom_discount);
            $('#amount-' + id[1]).val(0);
            $('#price-' + id[1]).val(ui.item.data[1]);
            $('#pid-' + id[1]).val(ui.item.data[2]);
            $('#vat-' + id[1]).val(t_r);
            $('#discount-' + id[1]).val(discount);
            $('#dpid-' + id[1]).val(ui.item.data[5]);
            $('#unit-' + id[1]).val(ui.item.data[6]);
            $('#hsn-' + id[1]).val(ui.item.data[7]);
            $('#alert-' + id[1]).val(parseInt(ui.item.data[8]));
            $('#serial-' + id[1]).val(ui.item.data[10]);
            rowTotal(cvalue);
            billUpyog();


        },
        create: function (e) {
            $(this).prev('.ui-helper-hidden-accessible').remove();
        }
    });

});

//erp2024 newly added for purchase product listin 02-10-2024

$('#purchaseproduct-0').autocomplete({
    
    source: function (request, response) {
        $.ajax({
            url: baseurl + 'search_products/' + billtype,
            dataType: "json",
            method: 'post',
            data: 'name_startsWith=' + request.term + '&type=product_list&row_num=1&wid=' + $("#s_warehouses option:selected").val() + '&' + d_csrf,
            success: function (data) {               
                response($.map(data, function (item) {
                    var product_d = item[0]+" ("+item[7]+")";
                    return {
                        label: product_d,
                        value: item[0],
                        data: item
                    };
                }));
            }
        });
    },
    autoFocus: true,
    minLength: 0,
    select: function (event, ui) {
        var t_r = ui.item.data[3];
        
        if ($("#taxformat option:selected").attr('data-trate')) {

            t_r = $("#taxformat option:selected").attr('data-trate');
        }
        var discount = ui.item.data[4];
        var custom_discount = $('#custom_discount').val();
        if (custom_discount > 0) discount = deciFormat(custom_discount);
        $("#onhandQty").text(parseInt(ui.item.data[8]));
        $('#amount-0').val(0);
        // $('#amount-0').val(1);
        $('#price-0').val(0);        
        // $('#price-0').val(ui.item.data[1]);    
        // $('#prdcost-0').val(ui.item.data[1]);    
        $('#pricelabel-0').text(ui.item.data[1]);
        $('#pid-0').val(ui.item.data[2]);
        $('#vat-0').val(t_r);
        $('#discount-0').val(discount);
        $('#dpid-0').val(ui.item.data[5]);
        $('#unit-0').val(ui.item.data[6]);
        $('#hsn-0').val(ui.item.data[7]);
        $('#alert-0').val(parseInt(ui.item.data[8]));
        $('#serial-0').val(ui.item.data[10]);
        $("#lowestprice-0").val(ui.item.data[11]);
        $("#lowestpricelabel-0").text(ui.item.data[11]);
        $("#maxdiscountrate-0").val(ui.item.data[12]);
        $("#maxdiscountratelabel-0").text(ui.item.data[12]); 
        $('#taxlabel-0').text(t_r+" / ");
        rowTotal(0);
        billUpyog();
    }
});
$('#addpurchaseproduct').on('click', function () {
    var cvalue = parseInt($('#ganak').val()) + 1;
    var nxt = parseInt(cvalue);
    $('#ganak').val(nxt); 
    var functionNum = "'" + cvalue + "'";
    count = $('#saman-row div').length; 
    var data = '<tr><td><input type="text" class="form-control" name="product_name[]" placeholder="Search by Product Name or Item No." id="productname-' + cvalue + '"></td><td><input type="text" class="form-control" name="hsn[]" id="hsn-' + cvalue + '" value="" readonly></td><td><input type="number" class="form-control req amnt" name="product_qty[]" id="amount-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" value="0"  inputmode="numeric"><input type="hidden" id="alert-' + cvalue + '" value=""  name="alert[]"> </td><td><input type="text" class="form-control req prc" name="product_price[]" id="price-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="checkCost(' + functionNum + '), rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" inputmode="numeric" ></td><td class="d-none"> <input type="text" class="form-control vat" name="product_tax[]" id="vat-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" inputmode="numeric"></td> <td id="texttaxa-' + cvalue + '" class="text-center d-none">0</td> <td><input type="text" class="form-control discount" name="product_discount[]" onkeypress="return isNumber(event)" id="discount-' + cvalue + '" onkeyup="discountWithTotal(' + functionNum + ')" autocomplete="off" ></td> <td><input type="hidden" class="form-control" name="foc[]" onkeypress="return isNumber(event)" id="foc-' + cvalue + '" ></td> <td class="text-right"><strong><span class=\'ttlText\' id="result-' + cvalue + '">0</span></strong> <div class="costvaluation_section" id="costvaluation_section-' + cvalue + '"><strong class="text-danger" id="cost_warning_val-' + cvalue + '"></strong></div></td> <td class="text-center"><button onclick="single_product_details(' + cvalue + ')" type="button" class="btn btn-sm btn-secondary" title="Product Details"><i class="fa fa-info"></i></button> <button type="button" data-rowid="' + cvalue + '" class="btn btn-sm btn-secondary removeProd" title="Remove" > <i class="fa fa-trash"></i> </button> </td><input type="hidden" name="taxa[]" id="taxa-' + cvalue + '" value="0"><input type="hidden" name="disca[]" id="disca-' + cvalue + '" value="0"><input type="hidden" class="ttInput" name="product_subtotal[]" id="total-' + cvalue + '" value="0"> <input type="hidden" class="pdIn" name="pid[]" id="pid-' + cvalue + '" value="0"> <input type="hidden" name="unit[]" id="unit-' + cvalue + '" value=""> <input type="hidden" name="serial[]" id="serial-' + cvalue + '" value=""></tr>';

   
    //ajax request
    // $('#saman-row').append(data);
    $('tr.last-item-row').before(data);
    row = cvalue;

    $('#productname-' + cvalue).autocomplete({        
        source: function (request, response) {
            $.ajax({
                url: baseurl + 'search_products/' + billtype,
                dataType: "json",
                method: 'post',
                data: 'name_startsWith=' + request.term + '&type=product_list&row_num=' + row + '&wid=' + $("#s_warehouses option:selected").val() + '&' + d_csrf,
                success: function (data) {
                    response($.map(data, function (item) {
                        // var product_d = item[0];
                        var product_d = item[0]+" ("+item[7]+")";
                        return {
                            label: product_d,
                            value: item[0],
                            data: item
                        };
                    }));
                }
            });
        },
        autoFocus: true,
        minLength: 0,
        select: function (event, ui) {
            id_arr = $(this).attr('id');
            id = id_arr.split("-");
            var t_r = ui.item.data[3];
            if ($("#taxformat option:selected").attr('data-trate')) {

                t_r = $("#taxformat option:selected").attr('data-trate');
            }
            var discount = ui.item.data[4];
            var custom_discount = $('#custom_discount').val();
            if (custom_discount > 0) discount = deciFormat(custom_discount);
            $('#amount-' + id[1]).val(0);
            $('#price-' + id[1]).val(0);
            // $('#price-' + id[1]).val(ui.item.data[1]);
            // $('#prdcost-' + id[1]).val(ui.item.data[1]);
            $('#pid-' + id[1]).val(ui.item.data[2]);
            $('#vat-' + id[1]).val(t_r);
            $('#discount-' + id[1]).val(discount);
            $('#dpid-' + id[1]).val(ui.item.data[5]);
            $('#unit-' + id[1]).val(ui.item.data[6]);
            $('#hsn-' + id[1]).val(ui.item.data[7]);
            $('#alert-' + id[1]).val(parseInt(ui.item.data[8]));
            $('#serial-' + id[1]).val(ui.item.data[10]);
            rowTotal(cvalue);
            billUpyog();


        },
        create: function (e) {
            $(this).prev('.ui-helper-hidden-accessible').remove();
        }
    });

});


$("#addenqproduct").on('click', function () {
    var ganakChun = $("#ganak");
    var ganak = ganakChun.val();
    var cvalue = parseInt(ganak) + 1;
    
    var functionNum = "'" + cvalue + "'";
    count = $('#saman-row div').length;
    //product row

    var data = '<tr><td><input type="text" class="form-control" name="product_name[]" placeholder="Search by Product Name or Item No." id="productname1-'+ cvalue +'"></td><td><input type="number" class="form-control req amnt" name="product_qty[]" id="amount-'+ cvalue +'" onkeypress="return isNumber(event)" autocomplete="off" value="1" ><input type="hidden" class="pdIn" name="pid[]" id="pid-'+ cvalue +'" value="0"> </td><td class=""><button type="button" data-rowid="'+ cvalue +'" class="btn btn-sm btn-default removeProd1" title="Remove" onclick="removeTr('+ cvalue +')"> <i class="fa fa-trash"></i> </button> </td> </tr>';

	//ajax request
   // $('#saman-row').append(data);
    $('tr.last-item-row').before(data);
    refreshRows();
    row = cvalue;


    $('#productname1-' + cvalue).autocomplete({
        source: function (request, response) {
            $.ajax({
                url: baseurl + 'enquiry/search',
                dataType: "json",
                method: 'post',
                data: {
                    name_startsWith: request.term,
                    type: 'product_list',
                    row_num: row
                },
                success: function (data) {
                    response($.map(data, function (item) {
                        return {
                            label: item[0],
                            value: item[0],
                            data: item
                        }
                    }));
                }
            });
        },
        autoFocus: true,
        minLength: 0,
        select: function (event, ui) {
            var names = ui.item.data;
            id_arr = $(this).attr('id');
            id = id_arr.split("-");
            $('#pid-' + id[1]).val(names[1]);
        },
        create: function (e) {
            $(this).prev('.ui-helper-hidden-accessible').remove();
        }
    });
    ganakChun.val(cvalue);
    var sideh2=document.getElementById('rough').scrollHeight;
    var opx3=sideh2+50;
    document.getElementById('rough').style.height=opx3+"px";


});
//remove productrow

function removeTr(cvalue){
    var pidd = $("#pid-"+cvalue).val();
    $("#pid-"+cvalue).closest('tr').remove(); 
}

//caculations
var precentCalc = function (total, percentageVal) {
    var pr = (total / 100) * percentageVal;
    return parseFloat(pr);
};
//format
var deciFormat = function (minput) {
    if (!minput) minput = 0;
    return parseFloat(minput).toFixed(2);
};
var formInputGet = function (iname, inumber) {
    var inputId;
    inputId = iname + '-' + inumber;
    var inputValue = $(inputId).val();

    if (inputValue == '') {

        return 0;
    } else {
        return inputValue;
    }
};

//ship calculation
var coupon = function () {
    var cp = 0;
    if ($('#coupon_amount').val()) {
        cp = accounting.unformat($('#coupon_amount').val(), accounting.settings.number.decimal);
    }
    return cp;
};
var shipTot = function () {
    var ship_val = accounting.unformat($('.shipVal').val(), accounting.settings.number.decimal);
    var ship_p = 0;
    if ($("#taxformat option:selected").attr('data-trate')) {
        var ship_rate = $("#taxformat option:selected").attr('data-trate');
    } else {
        var ship_rate = accounting.unformat($('#ship_rate').val(), accounting.settings.number.decimal);
    }
    var tax_status = $("#ship_taxtype").val();
    if (tax_status == 'excl') {
        ship_p = (ship_val * ship_rate) / 100;
        ship_val = ship_val + ship_p;
    } else if (tax_status == 'incl') {
        ship_p = (ship_val * ship_rate) / (100 + ship_rate);
    }
    $('#ship_tax').val(accounting.formatNumber(ship_p));
    $('#ship_final').html(accounting.formatNumber(ship_p));
    return ship_val;
};

//product total
var samanYog = function () {
    var itempriceList = [];
    var idList = [];
    var r = 0;
    $('.ttInput').each(function () {
        var vv = accounting.unformat($(this).val(), accounting.settings.number.decimal);
        var vid = $(this).attr('id');
        vid = vid.split("-");
        itempriceList.push(vv);
        idList.push(vid[1]);
        r++;
    });
    var sum = 0;
    var taxc = 0;
    var discs = 0;
    var total_amount = 0;
    var grand_amount = 0;
    for (var z = 0; z < idList.length; z++) {
        var x = idList[z];
        if (itempriceList[z] > 0) {
            sum += itempriceList[z];
        }
           // Retrieve values for price and quantity
        var product_price = parseFloat($("#price-" + z).val()) || 0;
        var product_qty = parseFloat($("#amount-" + z).val()) || 0;
       
        // Calculate product of price and quantity with 2 decimal places
        total_amount = parseFloat((product_qty * product_price).toFixed(2));
        grand_amount = total_amount + grand_amount;
        var t1 = accounting.unformat($("#taxa-" + x).val(), accounting.settings.number.decimal);
        var d1 = accounting.unformat($("#disca-" + x).val(), accounting.settings.number.decimal);
        if (t1 > 0) {
            taxc += t1;
        }
        if (d1 > 0) {
            discs += d1;
        }
    }

    // price-0 amount-0
    $("#discs").html(accounting.formatNumber(discs));
    $("#grandamount").html(accounting.formatNumber(grand_amount));
    $(".discs").html(accounting.formatNumber(discs));
    $("#taxr").html(accounting.formatNumber(taxc));
    return accounting.unformat(sum, accounting.settings.number.decimal);
};


function orderdiscount() {


    function parseCurrency(value) {
        
        return parseFloat(value.replace(/,/g, '')) || 0;
    }
    var old_order_discount = parseCurrency($("#old_order_discount").val());
    var order_discount = parseCurrency($("#order_discount").val());
   
    if(old_order_discount>0)
    {
        order_discount = old_order_discount - order_discount;
    }
    var invoiceyoghtml = parseCurrency($("#invoiceyoghtml").val());
   
    if (isNaN(invoiceyoghtml) || invoiceyoghtml <= 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Add at least one product',
            text: 'Invoice total must be greater than 0.',
        });
        return; 
    }
    
    if (isNaN(order_discount) || order_discount <= 0) {
        $("#grandtotaltext").text($("#invoiceyoghtml").val());
    }

    if (order_discount > invoiceyoghtml) {
        Swal.fire({
            icon: 'error',
            title: 'Invalid Discount',
            text: 'Order discount is greater than the net total.',
        });
        return;
    }
    var currentnet = invoiceyoghtml - order_discount;
    $("#grandtotaltext").text(currentnet.toFixed(2));
    // $("#invoiceyoghtml").val(currentnet);
}

//actions
var deleteRow = function (num) {
    var totalSelector = $("#subttlform");
    var prodttl = accounting.unformat($("#total-" + num).val(), accounting.settings.number.decimal);
    var subttl = accounting.unformat(totalSelector.val(), accounting.settings.number.decimal);
    var totalSubVal = subttl - prodttl;
    totalSelector.val(totalSubVal);
    $("#subttlid").html(accounting.formatNumber(totalSubVal));
    var totalBillVal = totalSubVal + shipTot - coupon;
    //final total
    var clean = accounting.formatNumber(totalBillVal);
    $("#mahayog").html(clean);
    $("#invoiceyoghtml").val(clean);
    $("#grandtotaltext").text(clean);
    $("#bigtotal").html(clean);
};


var billUpyog = function () {
    var out = 0;
    var disc_val = accounting.unformat($('.discVal').val(), accounting.settings.number.decimal);
    if (disc_val) {
        $("#subttlform").val(accounting.formatNumber(samanYog()));
        var disc_rate = $('#discountFormat').val();

        switch (disc_rate) {
            case '%':
                out = precentCalc(accounting.unformat($('#subttlform').val(), accounting.settings.number.decimal), disc_val);
                break;
            case 'b_p':
                out = precentCalc(accounting.unformat($('#subttlform').val(), accounting.settings.number.decimal), disc_val);
                break;
            case 'flat':
                out = accounting.unformat(disc_val, accounting.settings.number.decimal);
                break;
            case 'bflat':
                out = accounting.unformat(disc_val, accounting.settings.number.decimal);
                break;
        }
        out = parseFloat(out).toFixed(two_fixed);

        $('#disc_final').html(accounting.formatNumber(out));
        $('#after_disc').val(accounting.formatNumber(out));
    } else {
        $('#disc_final').html(0);
        $('#after_disc').val(0);
    }
    var totalBillVal = accounting.formatNumber(samanYog() + shipTot() - coupon() - out);
    
    $("#mahayog").html(totalBillVal);
    $("#subttlform").val(accounting.formatNumber(samanYog()));
    $("#invoiceyoghtml").val(totalBillVal);
    $(".invoiceyoghtml").val(totalBillVal);
    $("#grandtotaltext").text(totalBillVal);
    $("#authorizedamounthtml").val(totalBillVal);
    $("#bigtotal").html(totalBillVal);
     var itotal=0;
            $('.pdIn').each(function () {
            var pi = $(this).attr('id');
            var arr = pi.split('-');
            pi = arr[1];


            itotal = itotal + accounting.unformat($('#amount-' + pi).val(), accounting.settings.number.decimal);
            $("#total_items_count").html(itotal);
        });
};

var o_rowTotal = function (numb) {
    //most res
    var result;
    var totalValue;
    var amountVal = formInputGet("#amount", numb);
    var priceVal = formInputGet("#price", numb);
    var discountVal = formInputGet("#discount", numb);
    if (discountVal == '') {
        $("#discount-" + numb).val(0);
        discountVal = 0;
    }
    var vatVal = formInputGet("#vat", numb);
    if (vatVal == '') {
        $("#vat-" + numb).val(0);
        vatVal = 0;
    }
    var taxo = 0;
    var disco = 0;
    var totalPrice = (parseFloat(amountVal).toFixed(2)) * priceVal;
    var tax_status = $("#taxformat option:selected").val();
    var disFormat = $("#discount_format").val();

    //tax after bill
    if (tax_status == 'yes') {
        if (disFormat == '%' || disFormat == 'flat') {
            //tax
            var Inpercentage = precentCalc(totalPrice, vatVal);
            totalValue = parseFloat(totalPrice) + parseFloat(Inpercentage);
            taxo = deciFormat(Inpercentage);


            if (disFormat == 'flat') {
                disco = deciFormat(discountVal);
                totalValue = parseFloat(totalValue) - parseFloat(discountVal);
            } else if (disFormat == '%') {
                var discount = precentCalc(totalValue, discountVal);
                totalValue = parseFloat(totalValue) - parseFloat(discount);
                disco = deciFormat(discount);
            }

        } else {
//before tax
            if (disFormat == 'bflat') {
                disco = deciFormat(discountVal);
                totalValue = parseFloat(totalPrice) - parseFloat(discountVal);
            } else if (disFormat == 'b_p') {
                var discount = precentCalc(totalPrice, discountVal);
                totalValue = parseFloat(totalPrice) - parseFloat(discount);
                disco = deciFormat(discount);
            }

            //tax
            var Inpercentage = precentCalc(totalValue, vatVal);
            totalValue = parseFloat(totalValue) + parseFloat(Inpercentage);
            taxo = deciFormat(Inpercentage);


        }
    } else if (tax_status == 'inclusive') {
        if (disFormat == '%' || disFormat == 'flat') {
            //tax
            var Inpercentage = (+totalPrice * +vatVal) / (100 + +vatVal);
            totalValue = parseFloat(totalPrice);
            taxo = deciFormat(Inpercentage);


            if (disFormat == 'flat') {
                disco = deciFormat(discountVal);
                totalValue = parseFloat(totalValue) - parseFloat(discountVal);
            } else if (disFormat == '%') {
                var discount = precentCalc(totalValue, discountVal);
                totalValue = parseFloat(totalValue) - parseFloat(discount);
                disco = deciFormat(discount);
            }

        } else {
//before tax
            if (disFormat == 'bflat') {
                disco = deciFormat(discountVal);
                totalValue = parseFloat(totalPrice) - parseFloat(discountVal);
            } else if (disFormat == 'b_p') {
                var discount = precentCalc(totalPrice, discountVal);
                totalValue = parseFloat(totalPrice) - parseFloat(discount);
                disco = deciFormat(discount);
            }

            //tax
            var Inpercentage = (+totalPrice * +vatVal) / (100 + +vatVal);
            totalValue = parseFloat(totalValue);
            taxo = deciFormat(Inpercentage);


        }
    } else {
        taxo = 0;
        if (disFormat == '%' || disFormat == 'flat') {
            //tax

            //  totalValue = deciFormat(totalPrice);


            if (disFormat == 'flat') {
                disco = deciFormat(discountVal);
                totalValue = parseFloat(totalPrice) - parseFloat(discountVal);
            } else if (disFormat == '%') {
                var discount = precentCalc(totalPrice, discountVal);
                totalValue = parseFloat(totalPrice) - parseFloat(discount);
                disco = deciFormat(discount);
            }

        } else {
//before tax
            if (disFormat == 'bflat') {
                disco = deciFormat(discountVal);
                totalValue = parseFloat(totalPrice) - parseFloat(discountVal);
            } else if (disFormat == 'b_p') {
                var discount = precentCalc(totalPrice, discountVal);
                totalValue = parseFloat(totalPrice) - parseFloat(discount);
                disco = deciFormat(discount);
            }
        }
    }
    $("#result-" + numb).html(deciFormat(totalValue));
    $("#taxa-" + numb).val(taxo);
    $("#texttaxa-" + numb).text(taxo);
    $("#disca-" + numb).val(disco); 
    var totalID = "#total-" + numb;
    $(totalID).val(deciFormat(totalValue));
    
}
// function rowtotal 
var rowTotal = function (numb) {
    //most res
    var result;
    var page = '';
    var totalValue = 0;
    var taxo = 0;
    var disco = 0;
    var discount =0;
    var Inpercentage =0;
    var amountVal = accounting.unformat($("#amount-" + numb).val(), accounting.settings.number.decimal);
    var priceVal = accounting.unformat($("#price-" + numb).val(), accounting.settings.number.decimal);
    var discountVal = accounting.unformat($("#discount-" + numb).val(), accounting.settings.number.decimal);
    var discountamtVal = accounting.unformat($("#discountamt-" + numb).val(), accounting.settings.number.decimal);
    var vatVal = accounting.unformat($("#vat-" + numb).val(), accounting.settings.number.decimal);
    var totalPrice = amountVal.toFixed(two_fixed) * priceVal;
    // var tax_status = $("#taxformat option:selected").val();

    var tax_status = {
        "-1": "On",
        "-2": "Inclusive",
        "0": "Off",
        "-3": "CGST + SGST",
        "-4": "IGST"
    };
    
    var disFormat = $("#discount_format").val();
    var discounttype =  $("#discounttype-" + numb + " option:selected").val();
    var configured_tax = $("#config_tax").val();
    if(discounttype==undefined){
        discounttype = $("#discounttype-" + numb).val();
    }

   
    // //Perctype Amttype
    // //erp2024 removed 17-06-2024
    // if ($("#inv_page").val() == 'new_i' && formInputGet("#pid", numb) > 0) {
    //     var alertVal = accounting.unformat($("#alert-" + numb).val(), accounting.settings.number.decimal);
    //     if (alertVal <= +amountVal) {
    //         var aqt = alertVal - amountVal;
    //         alert('Low Stock! ' + accounting.formatNumber(aqt));
    //     }
    // }
    // // erp2024 removed 17-06-2024 ends 

    // //erp2024 removed 26-07-2024 starts 
    // //tax after bill
    // if ((tax_status == 'yes') || (tax_status!="")) {
    //     if(disFormat=="") { disFormat = '%'; }
    //     if (disFormat == '%' || disFormat == 'flat') {
    //         //tax
    //         var Inpercentage = precentCalc(totalPrice, vatVal);
    //         totalValue = totalPrice + Inpercentage;
    //         taxo = accounting.formatNumber(Inpercentage);
    //         if (disFormat == 'flat') {
    //             disco = accounting.formatNumber(amountVal*discountVal);
    //             totalValue = totalValue - discountVal*amountVal;
    //         } else if (disFormat == '%') {
    //             var discount = precentCalc(totalValue, discountVal);
    //             totalValue = totalValue - discount;
    //             disco = accounting.formatNumber(discount);
    //         }
    //     } else {
    //     ////before tax
    //         if (disFormat == 'bflat') {
    //             disco = accounting.formatNumber(discountVal*amountVal);
    //             totalValue = totalPrice - discountVal*amountVal;
    //         } else if (disFormat == 'b_p') {
    //             var discount = precentCalc(totalPrice, discountVal);
    //             totalValue = totalPrice - discount;
    //             disco = accounting.formatNumber(discount);
    //         }

    //         //tax
    //         var Inpercentage = precentCalc(totalValue, vatVal);
    //         totalValue = totalValue + Inpercentage;
    //         taxo = accounting.formatNumber(Inpercentage);
    //     }
    // } 
    // else if (tax_status == 'inclusive') {
    //     if (disFormat == '%' || disFormat == 'flat') {
    //         //tax
    //         var Inpercentage = (totalPrice * vatVal) / (100 + vatVal);
    //         totalValue = totalPrice;
    //         taxo = accounting.formatNumber(Inpercentage);
    //         if (disFormat == 'flat') {
    //             disco = accounting.formatNumber(discountVal*amountVal);
    //             totalValue = totalValue - discountVal*amountVal;
    //         } else if (disFormat == '%') {
    //             var discount = precentCalc(totalValue, discountVal);
    //             totalValue = totalValue - discount;
    //             disco = accounting.formatNumber(discount);
    //         }
    //     } else {
    //     //before tax
    //         if (disFormat == 'bflat') {
    //             disco = accounting.formatNumber(discountVal*amountVal);
    //             totalValue = totalPrice - discountVal;
    //         } else if (disFormat == 'b_p') {
    //             var discount = precentCalc(totalPrice, discountVal);
    //             totalValue = totalPrice - discount;
    //             disco = accounting.formatNumber(discount);
    //         }
    //         //tax
    //         var Inpercentage = (totalPrice * vatVal) / (100 + vatVal);
    //         totalValue = totalValue;
    //         taxo = accounting.formatNumber(Inpercentage);
    //     }
    // } 
    // else {
    //     taxo = 0;
        
    //     if (disFormat == '%' || disFormat == 'flat') {
    //         if (disFormat == 'flat') {
    //             disco = accounting.formatNumber(discountVal*amountVal);
    //             totalValue = totalPrice - discountVal*amountVal;
    //         } else if (disFormat == '%') {
    //             var discount = precentCalc(totalPrice, discountVal);
    //             totalValue = totalPrice - discount;
    //             disco = accounting.formatNumber(discount);
    //         }

    //     } else {
    //         if (disFormat == 'bflat') {
    //             disco = accounting.formatNumber(discountVal*amountVal);
    //             totalValue = totalPrice - discountVal*amountVal;
    //         } else if (disFormat == 'b_p') {
    //             var discount = precentCalc(totalPrice, discountVal);
    //             totalValue = totalPrice - discount;
    //             disco = accounting.formatNumber(discount);
    //         }
    //     }
    // }
    // ////erp2024 removed 26-07-2024 ends

    if(configured_tax !=0){
        if ((tax_status == 'yes') || (tax_status!="") || (configured_tax=='-1')) {
            Inpercentage = precentCalc(totalPrice, vatVal);
            // totalValue = totalPrice + Inpercentage;
            taxo = accounting.formatNumber(Inpercentage);
        }
        if ((tax_status == 'inclusive') || (tax_status!="") || (configured_tax=='-2')) {
            Inpercentage = (totalPrice * vatVal) / (100 + vatVal);
            // totalValue = totalPrice;
            taxo = accounting.formatNumber(Inpercentage);
        }

    }

    
    //// Discount calculation
    if(discounttype == "Perctype"){
        
        discount = precentCalc(totalPrice, discountVal);
        // totalValue = totalValue - discount;
        disco = accounting.formatNumber(discount);
        
    }
    else if(discounttype == "Amttype")
    {        
        discount = (amountVal*discountamtVal);
        disco = accounting.formatNumber(discount);
    }
    else{
        discount = 0;
        disco= 0;
    }
  
    totalValue = ((totalPrice+Inpercentage)-(discount));

    // if ((tax_status == 'yes') || (tax_status!="")) {
    //     if(disFormat=="") { disFormat = '%'; }
    //     if (disFormat == '%' || disFormat == 'flat') {
    //         //tax
    //         var Inpercentage = precentCalc(totalPrice, vatVal);
    //         totalValue = totalPrice + Inpercentage;
    //         taxo = accounting.formatNumber(Inpercentage);
    //         if (disFormat == 'flat') {
    //             disco = accounting.formatNumber(amountVal*discountVal);
    //             totalValue = totalValue - discountVal*amountVal;
    //         } else if (disFormat == '%') {
    //             var discount = precentCalc(totalValue, discountVal);
    //             totalValue = totalValue - discount;
    //             disco = accounting.formatNumber(discount);
    //         }
    //     } else {
    //     //before tax
    //         if (disFormat == 'bflat') {
    //             disco = accounting.formatNumber(discountVal*amountVal);
    //             totalValue = totalPrice - discountVal*amountVal;
    //         } else if (disFormat == 'b_p') {
    //             var discount = precentCalc(totalPrice, discountVal);
    //             totalValue = totalPrice - discount;
    //             disco = accounting.formatNumber(discount);
    //         }

    //         //tax
    //         var Inpercentage = precentCalc(totalValue, vatVal);
    //         totalValue = totalValue + Inpercentage;
    //         taxo = accounting.formatNumber(Inpercentage);
    //     }
    // } 
    // else if (tax_status == 'inclusive') {
    //     if (disFormat == '%' || disFormat == 'flat') {
    //         //tax
    //         var Inpercentage = (totalPrice * vatVal) / (100 + vatVal);
    //         totalValue = totalPrice;
    //         taxo = accounting.formatNumber(Inpercentage);
    //         if (disFormat == 'flat') {
    //             disco = accounting.formatNumber(discountVal*amountVal);
    //             totalValue = totalValue - discountVal*amountVal;
    //         } else if (disFormat == '%') {
    //             var discount = precentCalc(totalValue, discountVal);
    //             totalValue = totalValue - discount;
    //             disco = accounting.formatNumber(discount);
    //         }
    //     } else {
    //     //before tax
    //         if (disFormat == 'bflat') {
    //             disco = accounting.formatNumber(discountVal*amountVal);
    //             totalValue = totalPrice - discountVal;
    //         } else if (disFormat == 'b_p') {
    //             var discount = precentCalc(totalPrice, discountVal);
    //             totalValue = totalPrice - discount;
    //             disco = accounting.formatNumber(discount);
    //         }
    //         //tax
    //         var Inpercentage = (totalPrice * vatVal) / (100 + vatVal);
    //         totalValue = totalValue;
    //         taxo = accounting.formatNumber(Inpercentage);
    //     }
    // } 
    // else {
    //     taxo = 0;
        
    //     if (disFormat == '%' || disFormat == 'flat') {
    //         if (disFormat == 'flat') {
    //             disco = accounting.formatNumber(discountVal*amountVal);
    //             totalValue = totalPrice - discountVal*amountVal;
    //         } else if (disFormat == '%') {
    //             var discount = precentCalc(totalPrice, discountVal);
    //             totalValue = totalPrice - discount;
    //             disco = accounting.formatNumber(discount);
    //         }

    //     } else {
    //         if (disFormat == 'bflat') {
    //             disco = accounting.formatNumber(discountVal*amountVal);
    //             totalValue = totalPrice - discountVal*amountVal;
    //         } else if (disFormat == 'b_p') {
    //             var discount = precentCalc(totalPrice, discountVal);
    //             totalValue = totalPrice - discount;
    //             disco = accounting.formatNumber(discount);
    //         }
    //     }
    // } 
    var unitcost = totalValue / amountVal;
    $("#unitcost-" + numb).html(accounting.formatNumber(unitcost));
    $("#result-" + numb).html(accounting.formatNumber(totalValue));
    $("#taxa-" + numb).val(taxo);
    $("#texttaxa-" + numb).text(taxo);
    
    $("#disca-" + numb).val(disco);
    if(discounttype!="")
    {
        $("#discount-amtlabel-" + numb).text("Amount : "+disco);    
        $(".discount-amtlabel-" + numb).text("Amount : "+disco);  
        
        $("#total-" + numb).val(accounting.formatNumber(totalValue));
    }
    else{
        $("#discount-amtlabel-" + numb).text("Amount : 0.00");   
        $(".discount-amtlabel-" + numb).text("Amount : 0.00");   
    }
    samanYog();
};


//new function for remove amount from discount 12-09-2024 starts 
var rowDiscountTotal = function (numb) {
    //most res
    var result;
    var page = '';
    var totalValue = 0;
    var taxo = 0;
    var disco = 0;
    var discount =0;
    var Inpercentage =0;
    var amountVal = accounting.unformat($("#amount-" + numb).val(), accounting.settings.number.decimal);
    var priceVal = accounting.unformat($("#price-" + numb).val(), accounting.settings.number.decimal);
    var discountVal = accounting.unformat($("#discount-" + numb).val(), accounting.settings.number.decimal);
    var discountamtVal = accounting.unformat($("#discountamt-" + numb).val(), accounting.settings.number.decimal);
    var vatVal = accounting.unformat($("#vat-" + numb).val(), accounting.settings.number.decimal);
    var totalPrice = amountVal.toFixed(two_fixed) * priceVal;
    // var tax_status = $("#taxformat option:selected").val();
    var tax_status = {
        "-1": "On",
        "-2": "Inclusive",
        "0": "Off",
        "-3": "CGST + SGST",
        "-4": "IGST"
    };
    
    var disFormat = $("#discount_format").val();
    var discounttype =  $("#discounttype-" + numb + " option:selected").val();
    var configured_tax = $("#config_tax").val();
    if(discounttype==undefined){
        discounttype = $("#discounttype-" + numb).val();
    }

    // //Perctype Amttype
    // //erp2024 removed 17-06-2024
    // if ($("#inv_page").val() == 'new_i' && formInputGet("#pid", numb) > 0) {
    //     var alertVal = accounting.unformat($("#alert-" + numb).val(), accounting.settings.number.decimal);
    //     if (alertVal <= +amountVal) {
    //         var aqt = alertVal - amountVal;
    //         alert('Low Stock! ' + accounting.formatNumber(aqt));
    //     }
    // }
    // // erp2024 removed 17-06-2024 ends 

    // //erp2024 removed 26-07-2024 starts 
    // //tax after bill
    // if ((tax_status == 'yes') || (tax_status!="")) {
    //     if(disFormat=="") { disFormat = '%'; }
    //     if (disFormat == '%' || disFormat == 'flat') {
    //         //tax
    //         var Inpercentage = precentCalc(totalPrice, vatVal);
    //         totalValue = totalPrice + Inpercentage;
    //         taxo = accounting.formatNumber(Inpercentage);
    //         if (disFormat == 'flat') {
    //             disco = accounting.formatNumber(amountVal*discountVal);
    //             totalValue = totalValue - discountVal*amountVal;
    //         } else if (disFormat == '%') {
    //             var discount = precentCalc(totalValue, discountVal);
    //             totalValue = totalValue - discount;
    //             disco = accounting.formatNumber(discount);
    //         }
    //     } else {
    //     ////before tax
    //         if (disFormat == 'bflat') {
    //             disco = accounting.formatNumber(discountVal*amountVal);
    //             totalValue = totalPrice - discountVal*amountVal;
    //         } else if (disFormat == 'b_p') {
    //             var discount = precentCalc(totalPrice, discountVal);
    //             totalValue = totalPrice - discount;
    //             disco = accounting.formatNumber(discount);
    //         }

    //         //tax
    //         var Inpercentage = precentCalc(totalValue, vatVal);
    //         totalValue = totalValue + Inpercentage;
    //         taxo = accounting.formatNumber(Inpercentage);
    //     }
    // } 
    // else if (tax_status == 'inclusive') {
    //     if (disFormat == '%' || disFormat == 'flat') {
    //         //tax
    //         var Inpercentage = (totalPrice * vatVal) / (100 + vatVal);
    //         totalValue = totalPrice;
    //         taxo = accounting.formatNumber(Inpercentage);
    //         if (disFormat == 'flat') {
    //             disco = accounting.formatNumber(discountVal*amountVal);
    //             totalValue = totalValue - discountVal*amountVal;
    //         } else if (disFormat == '%') {
    //             var discount = precentCalc(totalValue, discountVal);
    //             totalValue = totalValue - discount;
    //             disco = accounting.formatNumber(discount);
    //         }
    //     } else {
    //     //before tax
    //         if (disFormat == 'bflat') {
    //             disco = accounting.formatNumber(discountVal*amountVal);
    //             totalValue = totalPrice - discountVal;
    //         } else if (disFormat == 'b_p') {
    //             var discount = precentCalc(totalPrice, discountVal);
    //             totalValue = totalPrice - discount;
    //             disco = accounting.formatNumber(discount);
    //         }
    //         //tax
    //         var Inpercentage = (totalPrice * vatVal) / (100 + vatVal);
    //         totalValue = totalValue;
    //         taxo = accounting.formatNumber(Inpercentage);
    //     }
    // } 
    // else {
    //     taxo = 0;
        
    //     if (disFormat == '%' || disFormat == 'flat') {
    //         if (disFormat == 'flat') {
    //             disco = accounting.formatNumber(discountVal*amountVal);
    //             totalValue = totalPrice - discountVal*amountVal;
    //         } else if (disFormat == '%') {
    //             var discount = precentCalc(totalPrice, discountVal);
    //             totalValue = totalPrice - discount;
    //             disco = accounting.formatNumber(discount);
    //         }

    //     } else {
    //         if (disFormat == 'bflat') {
    //             disco = accounting.formatNumber(discountVal*amountVal);
    //             totalValue = totalPrice - discountVal*amountVal;
    //         } else if (disFormat == 'b_p') {
    //             var discount = precentCalc(totalPrice, discountVal);
    //             totalValue = totalPrice - discount;
    //             disco = accounting.formatNumber(discount);
    //         }
    //     }
    // }
    // ////erp2024 removed 26-07-2024 ends

    if(configured_tax !=0){
        if ((tax_status == 'yes') || (tax_status!="") || (configured_tax=='-1')) {
            Inpercentage = precentCalc(totalPrice, vatVal);
            // totalValue = totalPrice + Inpercentage;
            taxo = accounting.formatNumber(Inpercentage);
        }
        if ((tax_status == 'inclusive') || (tax_status!="") || (configured_tax=='-2')) {
            Inpercentage = (totalPrice * vatVal) / (100 + vatVal);
            // totalValue = totalPrice;
            taxo = accounting.formatNumber(Inpercentage);
        }

    }

    
    //// Discount calculation
    if(discounttype == "Perctype"){
        
        discount = precentCalc(totalPrice, discountVal);
        // totalValue = totalValue - discount;
        disco = accounting.formatNumber(discount);
    }
    else if(discounttype == "Amttype")
    {        
        discount = (amountVal*discountamtVal);
        disco = accounting.formatNumber(discount);
    }
    else{
        discount = 0;
        disco= 0;
    }
    
    totalValue = ((totalPrice+Inpercentage)-(discount));

    // if ((tax_status == 'yes') || (tax_status!="")) {
    //     if(disFormat=="") { disFormat = '%'; }
    //     if (disFormat == '%' || disFormat == 'flat') {
    //         //tax
    //         var Inpercentage = precentCalc(totalPrice, vatVal);
    //         totalValue = totalPrice + Inpercentage;
    //         taxo = accounting.formatNumber(Inpercentage);
    //         if (disFormat == 'flat') {
    //             disco = accounting.formatNumber(amountVal*discountVal);
    //             totalValue = totalValue - discountVal*amountVal;
    //         } else if (disFormat == '%') {
    //             var discount = precentCalc(totalValue, discountVal);
    //             totalValue = totalValue - discount;
    //             disco = accounting.formatNumber(discount);
    //         }
    //     } else {
    //     //before tax
    //         if (disFormat == 'bflat') {
    //             disco = accounting.formatNumber(discountVal*amountVal);
    //             totalValue = totalPrice - discountVal*amountVal;
    //         } else if (disFormat == 'b_p') {
    //             var discount = precentCalc(totalPrice, discountVal);
    //             totalValue = totalPrice - discount;
    //             disco = accounting.formatNumber(discount);
    //         }

    //         //tax
    //         var Inpercentage = precentCalc(totalValue, vatVal);
    //         totalValue = totalValue + Inpercentage;
    //         taxo = accounting.formatNumber(Inpercentage);
    //     }
    // } 
    // else if (tax_status == 'inclusive') {
    //     if (disFormat == '%' || disFormat == 'flat') {
    //         //tax
    //         var Inpercentage = (totalPrice * vatVal) / (100 + vatVal);
    //         totalValue = totalPrice;
    //         taxo = accounting.formatNumber(Inpercentage);
    //         if (disFormat == 'flat') {
    //             disco = accounting.formatNumber(discountVal*amountVal);
    //             totalValue = totalValue - discountVal*amountVal;
    //         } else if (disFormat == '%') {
    //             var discount = precentCalc(totalValue, discountVal);
    //             totalValue = totalValue - discount;
    //             disco = accounting.formatNumber(discount);
    //         }
    //     } else {
    //     //before tax
    //         if (disFormat == 'bflat') {
    //             disco = accounting.formatNumber(discountVal*amountVal);
    //             totalValue = totalPrice - discountVal;
    //         } else if (disFormat == 'b_p') {
    //             var discount = precentCalc(totalPrice, discountVal);
    //             totalValue = totalPrice - discount;
    //             disco = accounting.formatNumber(discount);
    //         }
    //         //tax
    //         var Inpercentage = (totalPrice * vatVal) / (100 + vatVal);
    //         totalValue = totalValue;
    //         taxo = accounting.formatNumber(Inpercentage);
    //     }
    // } 
    // else {
    //     taxo = 0;
        
    //     if (disFormat == '%' || disFormat == 'flat') {
    //         if (disFormat == 'flat') {
    //             disco = accounting.formatNumber(discountVal*amountVal);
    //             totalValue = totalPrice - discountVal*amountVal;
    //         } else if (disFormat == '%') {
    //             var discount = precentCalc(totalPrice, discountVal);
    //             totalValue = totalPrice - discount;
    //             disco = accounting.formatNumber(discount);
    //         }

    //     } else {
    //         if (disFormat == 'bflat') {
    //             disco = accounting.formatNumber(discountVal*amountVal);
    //             totalValue = totalPrice - discountVal*amountVal;
    //         } else if (disFormat == 'b_p') {
    //             var discount = precentCalc(totalPrice, discountVal);
    //             totalValue = totalPrice - discount;
    //             disco = accounting.formatNumber(discount);
    //         }
    //     }
    // } 
    var unitcost = totalValue / amountVal;
    $("#unitcost-" + numb).html(accounting.formatNumber(unitcost));
    $("#result-" + numb).html(accounting.formatNumber(totalValue));
    $("#taxa-" + numb).val(taxo);
    $("#texttaxa-" + numb).text(taxo);
    $("#disca-" + numb).val(disco);
    if(discounttype!="")
    {
        $("#discount-amtlabel-" + numb).text(disco);    
        $(".discount-amtlabel-" + numb).text(disco);  
        
        $("#total-" + numb).val(accounting.formatNumber(totalValue));
    }
    else{
        $("#discount-amtlabel-" + numb).text("0.00");   
        $(".discount-amtlabel-" + numb).text("0.00");   
    }
    samanYog();
};
//new function for remove amount from discount 12-09-2024 ends

var calculateDeliveryReturn = function (numb) {
    var amountVal = parseInt($("#amount-" + numb).val());
    var deliveredQtyVal = parseInt($("#delivered_qty-" + numb).val());
    var deliveryreturnedqtyVal = parseInt($("#delivery_returned_qty-" + numb).val());



    // If amountVal is NaN (which means it's null or not a number), set it to zero discs
    if (isNaN(amountVal)) {
        amountVal = 0;
    }
    var validationval = amountVal+deliveryreturnedqtyVal;
    if (validationval >= 0 && validationval <= deliveredQtyVal) {
        // proceed with calculations
        var discounttype =  $("#discounttype-" + numb + " option:selected").val();
        var configured_tax = $("#config_tax").val();
        if(discounttype==undefined){
            discounttype = $("#discounttype-" + numb).val();
        }
        
        var totalValue = 0;
        var priceVal = accounting.unformat($("#price-" + numb).val(), accounting.settings.number.decimal);
        var discountVal = accounting.unformat($("#discount-" + numb).val(), accounting.settings.number.decimal);
        var discountamtVal = accounting.unformat($("#discountamt-" + numb).val(), accounting.settings.number.decimal);
        var vatVal = accounting.unformat($("#vat-" + numb).val(), accounting.settings.number.decimal);
        var taxo = 0;
        var disco = 0;
        var totalPrice = amountVal.toFixed(two_fixed) * priceVal;
        var disFormat = $("#discount_format").val();
        var Inpercentage = 0;
        if (vatVal > 0) {
            Inpercentage = precentCalc(totalPrice, vatVal);
        }
        taxo = accounting.formatNumber(Inpercentage);
       
       
        //// Discount calculation
        if(discounttype == "Perctype"){
            discount = precentCalc(totalPrice, discountVal);
            // totalValue = totalValue - discount;
            disco = accounting.formatNumber(discount);
            
        }
        else if(discounttype == "Amttype")
        {        
            discount = (amountVal*discountamtVal);
            disco = accounting.formatNumber(discount);
        }
        else{
            discount = 0;
            disco= 0;
        }
  

        // totalValue = totalValue + Inpercentage;
        totalValue = ((totalPrice+Inpercentage)-(discount));
        $("#result-" + numb).text(accounting.formatNumber(totalValue));
        $("#taxa-" + numb).val(taxo);
        $("#texttaxa-" + numb).text(taxo);
        $("#disca-" + numb).val(disco);
        $("#total-" + numb).val(accounting.formatNumber(totalValue));
        $("#disca-" + numb).val(disco);
        if(discounttype!="")
        {
            $("#discount-amtlabel-" + numb).text(disco);    
            $(".discount-amtlabel-" + numb).text(disco);  
            
            $("#total-" + numb).val(accounting.formatNumber(totalValue));
        }
        else{
            $("#discount-amtlabel-" + numb).text("0.00");   
            $(".discount-amtlabel-" + numb).text("0.00");   
        }
    } 
    else {
        var totaldiscount = parseFloat($("#discs").text());
        var currentdiscount = $(".discount-amtlabel-" + numb).text(); 
        // var currentdiscountamount = currentdiscount.split(': ')[1];
        var currentdiscountamount = currentdiscount;
        currentdiscountamount = parseFloat(currentdiscountamount);
        var reducedDiscount = totaldiscount - currentdiscountamount;
        reducedDiscount = reducedDiscount.toFixed(2);
        
        Swal.fire({
            icon: 'error',
            title: 'Invalid Input',
            text: 'The Sum of Returned Quantity and Return Quantity is Greater than Delivered Quantity or Amount is not valid',
            confirmButtonText: 'OK'
        })
        // .then(function() {
        //     // Reset the value and call calculateDeliveryReturn again
        //     $("#amount-" + numb).val(0);
        //     calculateDeliveryReturn(numb);
        // });
        $("#total-" + numb).val(0);
        $("#result-" + numb).text(0);
        $("#discount-amtlabel-" + numb).text("0.00");    
        $("#amount-" + numb).val(0);
      

        // 
        // samanYog();
        $("#discount_total").html(0);
        
    }
    // samanYog();
    billUpyog();
};


var changeTaxFormat = function (getSelectv) {

    if (getSelectv == 'yes') {
        var tformat = $('#taxformat option:selected').data('tformat');
        var trate = $('#taxformat option:selected').data('trate');
        $("#tax_status").val(tformat);
        $("#tax_format").val('%');
    } else if (getSelectv == 'inclusive') {
        var tformat = $('#taxformat option:selected').data('tformat');
        var trate = $('#taxformat option:selected').data('trate');
        $("#tax_status").val(tformat);
        $("#tax_format").val('incl');

    } else {
        $("#tax_status").val('no');
        $("#tax_format").val('off');

    }
    var discount_handle = $("#discountFormat").val();
    var tax_handle = $("#tax_format").val();
    formatRest(tax_handle, discount_handle, trate);
}

var changeDiscountFormat = function (getSelectv) {
    if (getSelectv != '0') {
        $(".disCol").show();
        $("#discount_handle").val('yes');
        $("#discount_format").val(getSelectv);
    } else {
        $("#discount_format").val(getSelectv);
        $(".disCol").hide();
        $("#discount_handle").val('no');
    }
    var tax_status = $("#tax_format").val();
    formatRest(tax_status, getSelectv);
}

function formatRest(taxFormat, disFormat, trate = '') {
    var amntArray = [];
    var idArray = [];
    $('.amnt').each(function () {
        var v = accounting.unformat($(this).val(), accounting.settings.number.decimal);
        var id_e = $(this).attr('id');
        id_e = id_e.split("-");
        idArray.push(id_e[1]);
        amntArray.push(v);
    });
    var prcArray = [];
    $('.prc').each(function () {
        var v = accounting.unformat($(this).val(), accounting.settings.number.decimal);
        prcArray.push(v);
    });
    var vatArray = [];
    $('.vat').each(function () {
        if (trate) {
            var v = accounting.unformat(trate, accounting.settings.number.decimal);
            $(this).val(v);
        } else {
            var v = accounting.unformat($(this).val(), accounting.settings.number.decimal);
        }
        vatArray.push(v);
    });

    var discountArray = [];
    $('.discount').each(function () {
        var v = accounting.unformat($(this).val(), accounting.settings.number.decimal);
        discountArray.push(v);
    });

    var taxr = 0;
    var discsr = 0;
    for (var i = 0; i < idArray.length; i++) {
        var x = idArray[i];
        amtVal = amntArray[i];
        prcVal = prcArray[i];
        vatVal = vatArray[i];
        discountVal = discountArray[i];
        var result = amtVal * prcVal;
        if (vatVal == '') {
            vatVal = 0;
        }
        if (discountVal == '') {
            discountVal = 0;
        }
        if (taxFormat == '%') {
            if (disFormat == '%' || disFormat == 'flat') {
                var Inpercentage = precentCalc(result, vatVal);
                var result = result + Inpercentage;
                taxr = taxr + Inpercentage;
                $("#texttaxa-" + x).html(accounting.formatNumber(Inpercentage));
                $("#taxa-" + x).val(accounting.formatNumber(Inpercentage));

                if (disFormat == '%') {
                    var Inpercentage = precentCalc(result, discountVal);
                    result = result - Inpercentage;
                    $("#disca-" + x).val(accounting.formatNumber(Inpercentage));
                    discsr = discsr + Inpercentage;
                } else if (disFormat == 'flat') {
                    result = parseFloat(result) - parseFloat(discountVal);
                    $("#disca-" + x).val(accounting.formatNumber(discountVal));
                    discsr += discountVal;
                }
            } else {
                if (disFormat == 'b_p') {
                    var Inpercentage = precentCalc(result, discountVal);
                    result = result - Inpercentage;
                    $("#disca-" + x).val(accounting.formatNumber(Inpercentage));
                    discsr = discsr + Inpercentage;
                } else if (disFormat == 'bflat') {
                    result = result - discountVal;
                    $("#disca-" + x).val(accounting.formatNumber(discountVal));
                    discsr += discountVal;
                }

                var Inpercentage = precentCalc(result, vatVal);
                result = result + Inpercentage;
                taxr = taxr + Inpercentage;
                $("#texttaxa-" + x).html(accounting.formatNumber(Inpercentage));
                $("#taxa-" + x).val(accounting.formatNumber(Inpercentage));

            }
        } else if (taxFormat == 'incl') {

            if (disFormat == '%' || disFormat == 'flat') {


                var Inpercentage = (result * vatVal) / (100 + vatVal);

                taxr = taxr + Inpercentage;
                $("#texttaxa-" + x).html(accounting.formatNumber(Inpercentage));
                $("#taxa-" + x).val(accounting.formatNumber(Inpercentage));

                if (disFormat == '%') {
                    var Inpercentage = precentCalc(result, discountVal);
                    result = result - Inpercentage;
                    $("#disca-" + x).val(accounting.formatNumber(Inpercentage));
                    discsr = discsr + Inpercentage;
                } else if (disFormat == 'flat') {
                    result = result - discountVal;
                    $("#disca-" + x).val(accounting.formatNumber(discountVal));
                    discsr += discountVal;
                }
            } else {
                if (disFormat == 'b_p') {
                    var Inpercentage = precentCalc(result, discountVal);
                    result = result - Inpercentage;
                    $("#disca-" + x).val(accounting.formatNumber(Inpercentage));
                    discsr = discsr + Inpercentage;
                } else if (disFormat == 'bflat') {
                    result = result - discountVal;
                    $("#disca-" + x).val(accounting.formatNumber(discountVal));
                    discsr += discountVal;
                }

                var Inpercentage = (result * vatVal) / (100 + vatVal);
                taxr = taxr + Inpercentage;
                $("#texttaxa-" + x).html(accounting.formatNumber(Inpercentage));
                $("#taxa-" + x).val(accounting.formatNumber(Inpercentage));

            }
        } else {

            if (disFormat == '%' || disFormat == 'flat') {

                var result = accounting.unformat($("#amount-" + x).val(), accounting.settings.number.decimal) * accounting.unformat($("#price-" + x).val(), accounting.settings.number.decimal);
                $("#texttaxa-" + x).html('Off');
                $("#taxa-" + x).val(0);
                taxr += 0;

                if (disFormat == '%') {
                    var Inpercentage = precentCalc(result, discountVal);
                    result = result - Inpercentage;
                    $("#disca-" + x).val(accounting.formatNumber(Inpercentage));
                    discsr = discsr + Inpercentage;
                } else if (disFormat == 'flat') {
                    var result = result - discountVal;
                    $("#disca-" + x).val(accounting.formatNumber(discountVal));
                    discsr += discountVal;
                }
            } else {
                if (disFormat == 'b_p') {
                    var Inpercentage = precentCalc(result, discountVal);
                    result = result - Inpercentage;
                    $("#disca-" + x).val(accounting.formatNumber(Inpercentage));
                    discsr = discsr + Inpercentage;
                } else if (disFormat == 'bflat') {
                    result = result - discountVal;
                    $("#disca-" + x).val(accounting.formatNumber(discountVal));
                    discsr += discountVal;
                }
                $("#texttaxa-" + x).html('Off');
                $("#taxa-" + x).val(0);
                taxr += 0;
            }
        }

        $("#total-" + x).val(accounting.formatNumber(result));
        $("#result-" + x).html(accounting.formatNumber(result));


    }
    var sum = accounting.formatNumber(samanYog());
    $("#subttlid").html(sum);
    $("#taxr").html(accounting.formatNumber(taxr));
    $("#discs").html(accounting.formatNumber(discsr));
    $(".discs").html(accounting.formatNumber(discsr));
    billUpyog();
}

//remove productrow


// $('#saman-row').on('click', '.removeProd', function () {

//     // var pidd = $(this).closest('tr').find('.pdIn').val();
//     // var pqty = $(this).closest('tr').find('.amnt').val();
//     // pqty = pidd + '-' + pqty;
//     // $('<input>').attr({
//     //     type: 'hidden',
//     //     id: 'restock',
//     //     name: 'restock[]',
//     //     value: pqty
//     // }).appendTo('form');
//     // $(this).closest('tr').remove();
//     // $('#d' + $(this).closest('tr').find('.pdIn').attr('id')).closest('tr').remove();
//     // $('.amnt').each(function (index) {
//     //     rowTotal(index);
//     //     billUpyog();
//     // });

//     return false;
// });

$('#saman-row').on('click', '.removeProd', function () {
    swal.fire({
        title: "Are you sure?",
        text: "Once deleted, you will not be able to recover this item!!!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Delete",
        cancelButtonText: "Cancel",
        cancelButtonColor: "#aaa",
        reverseButtons: true,
        customClass: {
            confirmButton: 'btn-confirm',
            cancelButton: 'btn-cancel'
        },
        didOpen: () => {
            // Focus on the Cancel button
            document.querySelector('.swal2-cancel').focus();
        }
    }).then((result) => {
        if (result.isConfirmed) {
            var $row = $(this).closest('tr');
            var pidd = $row.find('.pdIn').val();
            var pqty = $row.find('.amnt').val();
            pqty = pidd + '-' + pqty;
            $('<input>').attr({
                type: 'hidden',
                id: 'restock',
                name: 'restock[]',
                value: pqty
            }).appendTo('form');
            var rowId = $row.find('.pdIn').attr('id');
            $row.remove();
            $('#d' + rowId).closest('tr').remove();
            var totalrows = $('.amnt').length;
            
            $('.amnt').each(function (index) {
                rowTotal(index);
                billUpyog();
               
            });
            if(totalrows == 0){
                $("#invoiceyoghtml").val(0);
                $("#grandtotaltext").text(0);
                $('.noproduct-section').addClass('d-none');
            } else {
                $('.noproduct-section').removeClass('d-none');
            }
        }
    });

    return false;
});

$('.saman-row').on('click', '.removeProd', function () {
    swal.fire({
        title: "Are you sure?",
        text: "Once deleted, you will not be able to recover this item!!!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Delete",
        cancelButtonText: "Cancel",
        cancelButtonColor: "#aaa",
        reverseButtons: true,
        customClass: {
            confirmButton: 'btn-confirm',
            cancelButton: 'btn-cancel'
        },
        didOpen: () => {
            // Focus on the Cancel button
            document.querySelector('.swal2-cancel').focus();
        }
    }).then((result) => {
        if (result.isConfirmed) {
            var $row = $(this).closest('tr');
            var pidd = $row.find('.pdIn').val();
            var pqty = $row.find('.amnt').val();
            pqty = pidd + '-' + pqty;
            $('<input>').attr({
                type: 'hidden',
                id: 'restock',
                name: 'restock[]',
                value: pqty
            }).appendTo('form');
            var rowId = $row.find('.pdIn').attr('id');
            $row.remove();
            $('#d' + rowId).closest('tr').remove();
            var totalrows = $('.amnt').length;
            
            $('.amnt').each(function (index) {
                rowTotal(index);
                billUpyog();
               
            });
            if(totalrows == 0){
                $("#invoiceyoghtml").val(0);
                $("#grandtotaltext").text(0);
                $('.noproduct-section').addClass('d-none');
            } else {
                $('.noproduct-section').removeClass('d-none');
            }
        }
    });

    return false;
});







$('#productname-0').autocomplete({
    
    source: function (request, response) {
        $.ajax({
            url: baseurl + 'search_products/' + billtype,
            dataType: "json",
            method: 'post',
            data: 'name_startsWith=' + request.term + '&type=product_list&row_num=1&wid=' + $("#s_warehouses option:selected").val() + '&' + d_csrf,
            success: function (data) {               
                response($.map(data, function (item) {
                    var product_d = item[0]+" ("+item[7]+")";
                    return {
                        label: product_d,
                        value: item[0],
                        data: item
                    };
                }));
            }
        });
    },
    autoFocus: true,
    minLength: 0,
    select: function (event, ui) {
        var t_r = ui.item.data[3];
        
        if ($("#taxformat option:selected").attr('data-trate')) {

            t_r = $("#taxformat option:selected").attr('data-trate');
        }
        var discount = ui.item.data[4];
        var custom_discount = $('#custom_discount').val();
        if (custom_discount > 0) discount = deciFormat(custom_discount);
        $("#onhandQty").text(parseInt(ui.item.data[8]));
        $('#amount-0').val(1);
        $('#price-0').val(ui.item.data[1]);        
        $('#pricelabel-0').text(ui.item.data[1]);
        $('#pid-0').val(ui.item.data[2]);
        $('#vat-0').val(t_r);
        $('#discount-0').val(discount);
        $('#dpid-0').val(ui.item.data[5]);
        $('#unit-0').val(ui.item.data[6]);
        $('#hsn-0').val(ui.item.data[7]);
        $('#alert-0').val(parseInt(ui.item.data[8]));
        $('#serial-0').val(ui.item.data[10]);
        $("#lowestprice-0").val(ui.item.data[11]);
        $("#lowestpricelabel-0").text(ui.item.data[11]);
        $("#maxdiscountrate-0").val(ui.item.data[12]);
        $("#maxdiscountratelabel-0").text(ui.item.data[12]); 
        $("#code-0").val(ui.item.data[7]); 
        $('#taxlabel-0').text(t_r+" / ");
        rowTotal(0);
        billUpyog();
    }
});


$('#code-0').autocomplete({
    
    source: function (request, response) {
        $.ajax({
            url: baseurl + 'search_products/' + billtype,
            dataType: "json",
            method: 'post',
            data: 'name_startsWith=' + request.term + '&type=product_list&row_num=1&wid=' + $("#s_warehouses option:selected").val() + '&' + d_csrf,
            success: function (data) {               
                response($.map(data, function (item) {
                    var product_d = item[0]+" ("+item[7]+")";
                    return {
                        label: product_d,
                        value: item[7],
                        data: item
                    };
                }));
            }
        });
    },
    autoFocus: true,
    minLength: 0,
    select: function (event, ui) {
        var t_r = ui.item.data[3];
        
        if ($("#taxformat option:selected").attr('data-trate')) {

            t_r = $("#taxformat option:selected").attr('data-trate');
        }
        var discount = ui.item.data[4];
        var custom_discount = $('#custom_discount').val();
        if (custom_discount > 0) discount = deciFormat(custom_discount);
        $("#onhandQty").text(parseInt(ui.item.data[8]));
        $('#amount-0').val(1);
        $('#price-0').val(ui.item.data[1]);        
        $('#pricelabel-0').text(ui.item.data[1]);
        $('#pid-0').val(ui.item.data[2]);
        $('#vat-0').val(t_r);
        $('#discount-0').val(discount);
        $('#dpid-0').val(ui.item.data[5]);
        $('#unit-0').val(ui.item.data[6]);
        $('#hsn-0').val(ui.item.data[7]);
        $('#alert-0').val(parseInt(ui.item.data[8]));
        $('#serial-0').val(ui.item.data[10]);
        $("#lowestprice-0").val(ui.item.data[11]);
        $("#lowestpricelabel-0").text(ui.item.data[11]);
        $("#maxdiscountrate-0").val(ui.item.data[12]);
        $("#maxdiscountratelabel-0").text(ui.item.data[12]); 
        $("#productname-0").val(ui.item.data[0]); 
        $('#taxlabel-0').text(t_r+" / ");
        rowTotal(0);
        billUpyog(); 
    }
});

// erp2024 new function starts 05-06-2024
$('#productname123').autocomplete({    
    source: function (request, response) {
        $(".resultsection").hide();
        if (request.term.length > 3) {
            $.ajax({
                url: baseurl + 'search_products/' + billtype,
                dataType: "json",
                method: 'post',
                data: 'name_startsWith=' + request.term + '&type=product_list&row_num=1&wid=' + $("#s_warehouses option:selected").val() + '&' + d_csrf,
                success: function (data) {            
                    response($.map(data, function (item) {
                        var product_d = item[0]+" ("+item[2]+")";
                        return {
                            label: product_d,
                            value: product_d,
                            data: item
                        };
                    }));
                }
            });
        }
        else {
            // Hide the result when the input length is less than or equal to 4
            response([]);
            $(".resresponse").html('');
            $(".resultsection").hide();
        }
    },
    autoFocus: true,
    minLength: 0,
    select: function (event, ui) {
        var prid = ui.item.data[1];
        // $("#productID").val(prid);
        $(".resultsection").show();
        $.ajax({
            url: baseurl + 'Products/locationwiseproducts',
            dataType: 'json',
            method: 'POST',
            data: {'prdid': prid},
            success: function(data) {
                resultdata = data.stocks;
                let responseHtml = '<table class="table table-bordered"><thead><tr><th>Name</th><th>Code</th><th>Base Unit</th><th>OnHand</th><th>Customer Order</th><th>Purchase Order</th><th>In Transist</th></tr></thead><tbody>';
                responseHtml += '<tr>';
                responseHtml += '<td>'+data.productname+'</td><td>'+data.productcode+'</td><td>'+data.baseunit+'</td></td><td>'+data.onhand+'</td><td>'+data.total_sales_quantity+'</td><td>'+data.total_purchse_quantity+'</td><td>0</td>';
                responseHtml += '</tr></tbody></table>';
                $(".resresponse").html(responseHtml);

                let onhandHtml = '<table class="table table-bordered"><thead><tr><th>Warehouse</th><th>Unit</th><th>Onhand Stock</th><th>Alert</th></tr></thead><tbody>';
                onhandHtml += '<tr>';
                if (resultdata.length > 0) {
                    $.each(resultdata, function(index, row) {
                        onhandHtml += '<tr>';                        
                        onhandHtml += '<td>' + row.title + '</td>';
                        onhandHtml += '<td>' + row.unit + '</td>';
                        onhandHtml += '<td>' + row.stock_qty + '</td>';
                        onhandHtml += '<td>' + row.alert_qty + '</td>';
                        onhandHtml += '</tr>';
                    });
                } else {
                    onhandHtml += '<tr><td colspan="3">No data available</td></tr>';
                }
        
                onhandHtml += '</tbody></table>';
                $(".warehouseres").html(onhandHtml);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error: ' + textStatus, errorThrown);
            }
        });
        
    }
});

$('#productnames-0').autocomplete({
    source: function (request, response) {
        $('#productnames-0').removeAttr('data-id');
        $.ajax({
            url: baseurl + 'search_products/' + billtype,
            dataType: "json",
            method: 'post',
            data: 'name_startsWith=' + request.term + '&type=product_list&row_num=1&wid=' + $("#s_warehouses option:selected").val() + '&' + d_csrf,
            success: function (data) {               
                response($.map(data, function (item) {
                    var product_d = item[0]+" ("+ item[2] +")";
                    return {
                        label: product_d,
                        value: product_d,
                        data: item,
                        product_id: item[2]
                    };
                }));
            }
        });
    },
    autoFocus: true,
    minLength: 0,
    select: function (event, ui) {
        var prid = ui.item.data[1];
        var prid1 = ui.item.data[3];
        $('#productnames-0').attr('data-id', prid);
        $('#onhand-0').val(prid1);
        warehouseList("0");
    }
});

$('#materialrequest-add').on('click', function () {
    var cvalue = parseInt($('#ganak').val()) + 1;
    var nxt = parseInt(cvalue);
    $('#ganak').val(nxt);
    var functionNum = "'" + cvalue + "'";
    count = $('#saman-row div').length;
    
    var data = '<tr><td><input type="text" class="form-control ui-autocomplete-input" name="product_name[]" placeholder="Search by Product Name or Item No." id="productnames-'+cvalue+'" autocomplete="off" ><input type="hidden" class="form-control ui-autocomplete-input" name="productid[]" placeholder="Search by Product Name or Item No." id="productid-'+cvalue+'" autocomplete="off" ></td><td><select name="warehousefrom[]" id="warehousefrom-' + cvalue + '" class="form-control warehousefrom"><option value="">Select Warehouse</option></select></td><td><input type="text" class="form-control req prc" name="transferqty[]" id="transferqty-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" inputmode="numeric"></td><td class="text-center"><button type="button" data-rowid="' + cvalue + '" class="btn btn-sm btn-default removeProd" title="Remove" > <i class="fa fa-trash"></i> </button> </td></tr>';
    
    $('tr.last-item-row').before(data);
    row = cvalue;
    $('#productnames-'+cvalue).autocomplete({
        source: function (request, response) {
            $('#productnames-'+cvalue).removeAttr('data-id');
            $.ajax({
                url: baseurl + 'search_products/' + billtype,
                dataType: "json",
                method: 'post',
                data: 'name_startsWith=' + request.term + '&type=product_list&row_num=1&wid=' + $("#s_warehouses option:selected").val() + '&' + d_csrf,
                success: function (data) {               
                    response($.map(data, function (item) {
                        var product_d = item[0]+"("+ item[2] +")";
                        return {
                            label: product_d,
                            value: product_d,
                            data: item,
                            product_id: item[1]
                        };
                    }));
                }
            });
        },
        autoFocus: true,
        minLength: 0,
        select: function (event, ui) {
            var prid = ui.item.data[1];
            $('#productnames-'+cvalue).attr('data-id', prid);
            warehouseList(cvalue);
        }
    });
    
    selectedProductList();
});

$('#productrequest-add').on('click', function () {
    var cvalue = parseInt($('#ganak').val()) + 1;
    var nxt = parseInt(cvalue);
    $('#ganak').val(nxt);
    var functionNum = "'" + cvalue + "'";
    count = $('#saman-row div').length;
    
    var data = '<tr><td><input type="text" class="form-control ui-autocomplete-input" name="product_name[]" placeholder="Search by Product Name or Item No." id="productnames-'+cvalue+'" autocomplete="off" ><input type="hidden" class="form-control ui-autocomplete-input" name="productid[]" placeholder="Search by Product Name or Item No." id="productid-'+cvalue+'" autocomplete="off" ></td><td><input type="text" class="form-control req prc" name="onhand[]" id="onhand-' + cvalue + '" inputmode="numeric" readonly></td><td><input type="text" class="form-control req prc" name="transferqty[]" id="transferqty-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" inputmode="numeric"></td><td class="text-center"><button type="button" data-rowid="' + cvalue + '" class="btn btn-sm btn-default removeProd" title="Remove" > <i class="fa fa-trash"></i> </button> </td></tr>';
    
    $('tr.last-item-row').before(data);
    row = cvalue;
    $('#productnames-'+cvalue).autocomplete({
        source: function (request, response) {
            $('#productnames-'+cvalue).removeAttr('data-id');
            $.ajax({
                url: baseurl + 'search_products/' + billtype,
                dataType: "json",
                method: 'post',
                data: 'name_startsWith=' + request.term + '&type=product_list&row_num=1&wid=' + $("#s_warehouses option:selected").val() + '&' + d_csrf,
                success: function (data) {               
                    response($.map(data, function (item) {
                        var product_d = item[0]+"("+ item[2] +")";
                        return {
                            label: product_d,
                            value: product_d,
                            data: item,
                            product_id: item[1]
                        };
                    }));
                }
            });
        },
        autoFocus: true,
        minLength: 0,
        select: function (event, ui) {
            var prid = ui.item.data[1];
            $('#productnames-'+cvalue).attr('data-id', prid);
            var onhand = ui.item.data[3];
            $('#onhand-'+cvalue).val(onhand);
        }
    });
    
    selectedProductList();
});

// erp2024 new function starts 05-06-2024 ends

$('#productname-01').autocomplete({
    source: function (request, response) {
        $.ajax({
            url: baseurl + 'search_products/' + billtype,
            dataType: "json",
            method: 'post',
            data: 'name_startsWith=' + request.term + '&type=product_list&row_num=1&wid=' + $("#s_warehouses option:selected").val() + '&' + d_csrf,
            success: function (data) {               
                response($.map(data, function (item) {
                    var product_d = item[0];
                    return {
                        label: product_d,
                        value: product_d,
                        data: item
                    };
                }));
            }
        });
    },
    autoFocus: true,
    minLength: 0,
    select: function (event, ui) {
        var t_r = ui.item.data[3];
        
        if ($("#taxformat option:selected").attr('data-trate')) {

            t_r = $("#taxformat option:selected").attr('data-trate');
        }
        var discount = ui.item.data[4];
        var custom_discount = $('#custom_discount').val();
        if (custom_discount > 0) discount = deciFormat(custom_discount);
        $("#onhandQty").text(parseInt(ui.item.data[8]));
        $('#amount-0').val(1);
        $('#price-0').val(ui.item.data[1]);
        $('#pid-0').val(ui.item.data[2]);
        $('#vat-0').val(t_r);
        $('#discount-0').val(discount);
        $('#dpid-0').val(ui.item.data[5]);
        $('#unit-0').val(ui.item.data[6]);
        $('#hsn-0').val(ui.item.data[7]);
        $('#alert-0').val(parseInt(ui.item.data[8]));
        $('#serial-0').val(ui.item.data[10]);
        rowTotal(0);

        billUpyog();


    }
});

$('#productname1-0').autocomplete({
    source: function (request, response) {
        $.ajax({
            url: baseurl + 'search_products/search',
            dataType: "json",
            method: 'post',
            data: 'name_startsWith=' + request.term + '&type=product_list&row_num=1&wid=' + $("#salepoint_id").val() + '&' + d_csrf,
            success: function (data) {               
                response($.map(data, function (item) {
                    var product_d = item[0];
                    return {
                        label: product_d,
                        value: product_d,
                        data: item
                    };
                }));
            }
        });
    },
    autoFocus: true,
    minLength: 0,
    select: function (event, ui) {
        var t_r = ui.item.data[3];
        
        if ($("#taxformat option:selected").attr('data-trate')) {

            t_r = $("#taxformat option:selected").attr('data-trate');
        }
        var discount = ui.item.data[4];
        var custom_discount = $('#custom_discount').val();
        if (custom_discount > 0) discount = deciFormat(custom_discount);
    }
});




$('#payable_acc-0').autocomplete({
    source: function (request, response) {
        $.ajax({
            url: baseurl + 'search_products/payableaccount_search',
            dataType: "json",
            method: 'post',
            data: 'name_startsWith=' + request.term + '&' + d_csrf,
            success: function (data) {               
                response($.map(data, function (item) {
                    var id = item[0];
                    return {
                        label: item[1],
                        value: item[1],
                        data: item
                    };
                }));
            }
        });
    },
    autoFocus: true,
    minLength: 0,
    select: function (event, ui) {
       $('#payable_acc_no-0').val(ui.item.data[2]);
    }
});

$('#addrow_btn').on('click', function () {
    var cvalue = parseInt($('#ganak').val());
    var nxt = parseInt(cvalue) + 1;
    $('#ganak').val(nxt);

    // Check if the row already exists
    if ($('#expense_name-' + cvalue).length === 0) {
        var newRow = '<tr><td>' + nxt + '</td><td><input type="text" name="expense_name[]" id="expense_name-' + cvalue + '" class="form-control expense_name" style="width:150px;" data-id="' + cvalue + '" ></td></tr>';
        $('tbody').append(newRow);

        // Initialize autocomplete
        $('#expense_name-' + cvalue).autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: baseurl + 'search_products/search',
                    dataType: "json",
                    method: 'post',
                    data: {
                        name_startsWith: request.term,
                        row_num: cvalue, // Pass the row number as data
                    },
                    success: function (data) {
                        response($.map(data, function (item) {
                            var id = item[0];
                            return {
                                label: item[1],
                                value: item[1],
                                data: item
                            };
                        }));
                    }
                });
            },
            autoFocus: true,
            minLength: 0,
            select: function (event, ui) {
                $('#expense_id-' + cvalue).val(ui.item.data[0]);
            }
        });
    }
});


$(document).on('click', ".select_pos_item", function (e) {
    var pid = $(this).attr('data-pid');
    var stock = accounting.unformat($(this).attr('data-stock'), accounting.settings.number.decimal);
    var flag = true;
    var discount = $(this).attr('data-discount');
    var custom_discount = accounting.unformat($('#custom_discount').val(), accounting.settings.number.decimal);
    if (custom_discount > 0) discount = accounting.formatNumber(custom_discount);

    $('.pdIn').each(function () {
        if (pid == $(this).val()) {

            var pi = $(this).attr('id');
            var arr = pi.split('-');
            pi = arr[1];
            $('#discount-' + pi).val(discount);
            var stotal = accounting.unformat($('#amount-' + pi).val(), accounting.settings.number.decimal) + 1;

            if (stotal <= stock) {
                $('#amount-' + pi).val(accounting.formatNumber(stotal));
                $('#search_bar').val('').focus();
            } else {
                $('#stock_alert').modal('toggle');
            }
            rowTotal(pi);
            billUpyog();
            $('#amount-' + pi).focus();
            flag = false;
        }
    });
    var t_r = $(this).attr('data-tax');
    if ($("#taxformat option:selected").attr('data-trate')) {

        var t_r = $("#taxformat option:selected").attr('data-trate');
    }
    if (flag) {
        var ganak = $('#ganak').val();
        var cvalue = parseInt(ganak);
        var functionNum = "'" + cvalue + "'";
        count = $('#saman-row div').length;
        var data = '<tr id="ppid-' + cvalue + '" class="mb-1"><td colspan="7" ><input type="text" class="form-control text-center p-mobile" name="product_name[]" placeholder="Search by Product Name or Item No." id="productname-' + cvalue + '" value="' + $(this).attr('data-name') + '-' + $(this).attr('data-pcode') + '"><input type="hidden" id="alert-' + cvalue + '" value="' + $(this).attr('data-stock') + '"  name="alert[]"></td></tr><tr><td><input type="number" inputmode="numeric" class="form-control p-mobile p-width req amnt" name="product_qty[]" id="amount-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" value="1" ></td><td class="text-center"><strong id="onhandQty-' + cvalue + '"></strong></td> <td><input type="text" class="form-control p-width p-mobile req prc" name="product_price[]"  inputmode="numeric" id="price-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off"  value="' + $(this).attr('data-price') + '"></td><td> <input type="text" class="form-control p-mobile p-width vat" inputmode="numeric" name="product_tax[]" id="vat-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off"  value="' + t_r + '"></td>  <td><input type="text" class="form-control p-width p-mobile discount pos_w" name="product_discount[]" inputmode="numeric" onkeypress="return isNumber(event)" id="discount-' + cvalue + '" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off"  value="' + discount + '" inputmode="numeric"></td> <td><span class="currenty">' + currency + '</span> <strong><span class=\'ttlText\' id="result-' + cvalue + '">0</span></strong></td> <td class="text-center"><button type="button" data-rowid="' + cvalue + '" class="btn  btn-sm btn-default removeItem" title="Remove" > <i class="fa fa-trash"></i> </button> </td><input type="hidden" name="taxa[]" id="taxa-' + cvalue + '" value="0"><input type="hidden" name="disca[]" id="disca-' + cvalue + '" value="0"><input type="hidden" class="ttInput" name="product_subtotal[]" id="total-' + cvalue + '" value="0"> <input type="hidden" class="pdIn" name="pid[]" id="pid-' + cvalue + '" value="' + $(this).attr('data-pid') + '"> <input type="hidden" name="unit[]" id="unit-' + cvalue + '" value="' + $(this).attr('data-unit') + '"> <input type="hidden" name="hsn[]" id="hsn-' + cvalue + '" value="' + $(this).attr('data-pcode') + '"> <input type="hidden" name="serial[]" id="serial-' + cvalue + '" value="' + $(this).attr('data-serial') + '"></tr>';

        //ajax request
        // $('#saman-row').append(data);
        $('#pos_items').append(data);
        rowTotal(cvalue);
        billUpyog();
        $('#ganak').val(cvalue + 1);
        $('#amount-' + cvalue).focus();

    }
});

$('#saman-pos2').on('click', '.removeItem', function () {
    var pidd = $(this).attr('data-rowid');
    var pqty = accounting.unformat($('#amount-' + pidd).val(), accounting.settings.number.decimal);
    var old_amnt = $('#amount_old-' + pidd).val();
    if (old_amnt) {
        pqty = pidd + '-' + pqty;
        $('<input>').attr({
            type: 'hidden',
            name: 'restock[]',
            value: pqty
        }).appendTo('form');
    }
    $('#ppid-' + pidd).remove();
    $('.amnt').each(function (index) {
        rowTotal(index);
    });
    billUpyog();
    return false;
});


$('#saman-row-pos').on('click', '.removeItem', function () {

    var pidd = $(this).closest('tr').find('.pdIn').val();
    var pqty = accounting.unformat($(this).closest('tr').find('.amnt').val(), accounting.settings.number.decimal);
    var old_amnt = accounting.unformat($(this).closest('tr').find('.old_amnt').val(), accounting.settings.number.decimal);
    if (old_amnt) {
        pqty = pidd + '-' + pqty;
        $('<input>').attr({
            type: 'hidden',
            name: 'restock[]',
            value: pqty
        }).appendTo('form');
    }
    $(this).closest('tr').remove();
    $('#d' + $(this).closest('tr').find('.pdIn').attr('id')).closest('tr').remove();
    $('#p' + $(this).closest('tr').find('.pdIn').attr('id')).remove();
    $('.amnt').each(function (index) {
        rowTotal(index);

    });
    billUpyog();

    return false;

});


$(document).on('click', ".quantity-up", function (e) {
    var spinner = $(this);
    var input = spinner.closest('.quantity').find('input[name="product_qty[]"]');
    var oldValue = accounting.unformat(input.val(), accounting.settings.number.decimal);

    var newVal = oldValue + 1;
    spinner.closest('.quantity').find('input[name="product_qty[]"]').val(accounting.formatNumber(newVal));
    spinner.closest('.quantity').find('input[name="product_qty[]"]').trigger("change");
    var id_arr = $(input).attr('id');
    id = id_arr.split("-");
    rowTotal(id[1]);
    billUpyog();
    return false;
});

$(document).on('click', ".quantity-down", function (e) {
    var spinner = $(this);
    var input = spinner.closest('.quantity').find('input[name="product_qty[]"]');
    var oldValue = accounting.unformat(input.val(), accounting.settings.number.decimal);
    var min = 1;
    if (oldValue <= min) {
        var newVal = oldValue;
    } else {
        var newVal = oldValue - 1;
    }
    spinner.closest('.quantity').find('input[name="product_qty[]"]').val(accounting.formatNumber(newVal));
    spinner.closest('.quantity').find('input[name="product_qty[]"]').trigger("change");
    var id_arr = $(input).attr('id');
    id = id_arr.split("-");
    rowTotal(id[1]);
    billUpyog();
    return false;
});

$('#kgQuantityCheck').change(function(){
    if($(this).is(':checked')){
        $("#kg_quantitydiv").show();
        $('#kgQuantityCheck').val("1");
        $('#kg_quantitydiv').removeClass("d-none");
    } else {
        $("#kg_quantitydiv").hide();
        $('#kgQuantityCheck').val("0");
        $('#kg_quantitydiv').addClass("d-none");
    }
});

$('#prdcheckbox').change(function(){
    if($(this).is(':checked')){
        $(".checkedproducts").prop('checked', true);
    } 
    else {
        $(".checkedproducts").prop('checked', false);
    }
});

$(document).ready(function() {
    
    $("div.alert a[href='https://cloudbizerp.com/settings/activate']").parent().remove();
    $('#updateInventoryBtn').click(function() {
        var selectedProducts = [];
        $('.checkedproducts:checked').each(function() {
            selectedProducts.push($(this).val());
        });
        if (selectedProducts.length === 0) {
            alert("Please select at least one product.");
            return;
        }
        if (confirm("Are you sure you want to update inventory?")) {
            
            $.ajax({
                type: 'POST',
                url: baseurl + 'Invoices/updateInventory',
                data: { selectedProducts: selectedProducts },
                dataType: 'json',
                success: function(response) {
                    alert(response.message);
                    // window.location.href = baseurl + 'purchase/create'; 
                    //window.open(baseurl + 'purchase/create', '_blank');
                },
                error: function(xhr, status, error) {
                }
            });
        }

    });
    
    $('#purchaseOrderBtn').click(function() {
        var selectedProducts = [];
        $('.checkedproducts:checked').each(function() {
            selectedProducts.push($(this).val());
        });
        if (selectedProducts.length === 0) {
            Swal.fire({
                text: "Please select at least one product",
                icon: "info"
              });
            return;
        }
        $.ajax({
            type: 'POST',
            url: baseurl + 'purchase/selectedProducts', 
            data: { selectedValues: selectedProducts },
            success: function(response) {
                window.location.href = baseurl + 'purchase/create_indirect'; 
                // window.open(baseurl + 'purchase/create', '_blank');
            },
            error: function(xhr, status, error) {
            }
        });
    });

    $('#convertedInvoiceBtn').click(function() {
        var selectedProducts = [];
        $('.checkedproducts:checked').each(function() {
            selectedProducts.push($(this).val());
        });
        if (selectedProducts.length === 0) {
            alert("Please select at least one product.");
            return;
        }
        $.ajax({
            type: 'POST',
            url: baseurl + 'purchase/selectedProducts', 
            data: { selectedValues: selectedProducts },
            success: function(response) {
                window.open(baseurl + 'Invoices/converttoinvoice', '_blank');
            },
            error: function(xhr, status, error) {
            }
        });
    });

    var currentURL = window.location.href;
    targeturl = baseurl + 'Invoices/converttoinvoice';
    if(currentURL===targeturl){
        addExistingShippedProducts();
    }

    var currentURL = window.location.href;
    targeturl = baseurl + 'purchase/create_indirect';
    if(currentURL===targeturl){
        addExistingProducts();
    }
    
    // erp2024 for list products 17-06-2024
    var currentURL = window.location.href;
    targeturl = baseurl + 'DeliveryNotes/deliverynotetoinvoice';
    if(currentURL===targeturl){
        addExistingDeliveryProducts();
    }
    // erp2024 for list products 17-06-2024
    
});
// erp2024 functions for delivery note to invoice creation 17-06-2024
function invoicing(devliverynoteid){
    $.ajax({
        type: 'POST',
        url: baseurl + 'DeliveryNotes/productsByDeliveryNoteID', 
        data: { devliverynoteid: devliverynoteid },
        success: function(response) {
        //    window.open(baseurl + 'DeliveryNotes/deliverynotetoinvoice', '_blank');
           window.location.href = baseurl + 'DeliveryNotes/deliverynotetoinvoice';
        },
        error: function(xhr, status, error) {
        }
    });
}
function addExistingDeliveryProducts(){
    // var discountval = 0;
    $.ajax({
        type: 'POST',
        url: baseurl + 'DeliveryNotes/products_in_selected_deliverynote',
        dataType: 'json',
        success: function(response) {
            if (response !== null) {
                response.forEach(function(element) {                    
                    add_existing_values_to_invoice(element);

                });
            }
        },
        error: function(xhr, status, error) {
            // Handle error response
            console.error(xhr.responseText);
            alert("An error occurred while converting to invoice. Please try again.");
        }
    });
}

function add_existing_values_to_invoice(pid){
    var cvalue = parseInt($('#ganak').val());
    var functionNum = "'" + cvalue + "'";
    count = $('#saman-row div').length;
    
    var data = '<tr><td><strong id="productname1-' + cvalue + '"></strong><input type="hidden" class="form-control" name="product_name[]" placeholder="Search by Product Name or Item No." id="productname-' + cvalue + '" readonly></td><td><strong id="code-' + cvalue + '"></strong></td><td class="text-center"><strong id="amount1-' + cvalue + '" ></strong><input type="hidden" class="form-control req amnt" name="product_qty[]" id="amount-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" value="1"  inputmode="numeric" readonly><input type="hidden" id="alert-' + cvalue + '" value=""  name="alert[]"> </td><td class="text-right"><strong id="price1-' + cvalue + '" ></strong><input type="hidden" class="form-control req prc" name="product_price[]" id="price-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" inputmode="numeric" readonly></td>';

    // data += '<td> <input type="text" class="form-control vat" name="product_tax[]" id="vat-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" inputmode="numeric" readonly></td> <td id="texttaxa-' + cvalue + '" class="text-center">0</td>';

    data += '<td class="text-right"><strong id="discountamt1-' + cvalue + '" ></strong><input type="hidden" class="form-control discount" name="product_discount[]" onkeypress="return isNumber(event)" id="discount-' + cvalue + '" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" readonly><input type="hidden" class="form-control" name="discount_type[]" id="discounttype-' + cvalue + '"   ><input type="hidden" class="form-control discount" name="product_amt[]" onkeypress="return isNumber(event)" id="discountamt-' + cvalue + '" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" readonly></td>';

    data += '<td class="text-right"><span class="currenty"></span> <strong class="text-right"><span class=\'ttlText\' id="result-' + cvalue + '">0</span></strong></td> <input type="hidden" name="taxa[]" id="taxa-' + cvalue + '" value="0"><input type="hidden" name="disca[]" id="disca-' + cvalue + '" value="0"><input type="hidden" class="ttInput" name="product_subtotal[]" id="total-' + cvalue + '" value="0"> <input type="hidden" class="pdIn" name="pid[]" id="pid-' + cvalue + '" value="0"> <input type="hidden" name="unit[]" id="unit-' + cvalue + '" value=""> <input type="hidden" name="hsn[]" id="hsn-' + cvalue + '" value=""> <input type="hidden" name="serial[]" id="serial-' + cvalue + '" value=""> </tr>';

    $('tr.last-item-row').before(data);
    row = cvalue;
  
    $.ajax({
        url: baseurl + 'DeliveryNotes/productidsearch',
        dataType: "json",
        method: 'post',
        data: {pid:pid},
        success: function (data) {
            if(data!=""){
                $(".startRow").remove();
                // prdfull = data[0][0] + " ("+data[0][7]+")";
                $("#productname-" + cvalue).val(data[0][0]);
                $("#productname1-" + cvalue).text(data[0][0]);
                $('#amount-' + cvalue).val(data[0][9]);                    
                $('#amount1-' + cvalue).text(data[0][9]);                    
                // $('#amount-' + cvalue).val(1);                    
                $("#price-" + cvalue).val(data[0][1]);
                $("#price1-" + cvalue).text(data[0][1]);
                $('#pid-' + cvalue).val(data[0][2]);
                $('#vat-' + cvalue).val(data[0][3]);
                $('#dpid-' + cvalue).val(data[0][5]);
                $('#unit-' + cvalue).val(data[0][6]);

                $('#hsn-' + cvalue).val(data[0][7]);
                $('#code-' + cvalue).text(data[0][7]);
                $('#alert-' + cvalue).val(data[0][8]);
                $('#serial-' + cvalue).val(data[0][10]);
                $('#discounttype-' + cvalue).val(data[0][12]);
                if($('#discounttype-' + cvalue).val()=="Amttype")
                {                    
                    $('#discount-' + cvalue).val(data[0][4]);
                    $('#discount-' + cvalue).addClass('d-none').val(0);
                    $('#discountamt-' + cvalue).removeClass('d-none');
                }
                else{
                    $('#discount-' + cvalue).val(data[0][4]);
                    $('#discount-' + cvalue).removeClass('d-none');
                    $('#discountamt-' + cvalue).addClass('d-none').val(0);
                }
                $('#discountamt1-' + cvalue).text(data[0][13]);
                rowTotal(cvalue);
                billUpyog();
            }

            
        }
    });
    // alert(discountval);
    var nxt = parseInt(cvalue) + 1;
    $('#ganak').val(nxt);
}
// erp2024 functions for delivery note to invoice creation 17-06-2024
function addExistingShippedProducts(){
    $.ajax({
        type: 'POST',
        url: baseurl + 'purchase/selected_session_shipped_products',
        dataType: 'json',
        success: function(response) {
            if (response !== null) {
                response.forEach(function(element) {                    
                    add_existing_values_to_purchaseorder(element);
                });
            }
        },
        error: function(xhr, status, error) {
            // Handle error response
            console.error(xhr.responseText);
            alert("An error occurred while converting to sales order. Please try again.");
        }
    });
}
function addExistingProducts(){
    $.ajax({
        type: 'POST',
        url: baseurl + 'purchase/selected_session_products',
        dataType: 'json',
        success: function(response) {
            if (response !== null) {
                response.forEach(function(element) {
                    add_existing_values_to_purchaseorder(element);
                });
            }
        },
        error: function(xhr, status, error) {
            // Handle error response
            console.error(xhr.responseText);
            alert("An error occurred while converting to sales order. Please try again.");
        }
    });
}


// function convertToSalesOrder(id) {
//     var $statusval = $("#statusval").val();
//     if ($statusval == 'accepted') {
//         // Display a confirm box
//         if (confirm("Are you sure you want to convert this to a sales order?")) {
//             $.ajax({
//                 type: 'POST',
//                 url: baseurl + 'quote/convert_to_salesorder',
//                 data: { id: id },
//                 dataType: 'JSON',
//                 success: function(response) {
//                     flg = response.flg;
//                     if(flg==1)
//                     {
//                         id = response.id;
//                         window.location.href = baseurl + 'quote/quote_to_salesorders?id=' + id;
//                     }
//                     else{
//                         alert("Already Converted");
//                     }
//                 },
//                 error: function(xhr, status, error) {
//                     console.error(xhr.responseText);
//                     alert("An error occurred while converting to sales order. Please try again.");
//                 }
//             });
//         } else {
//             // If user cancels, do nothing
//             console.log("Conversion cancelled.");
//         }
//     } else {
//         alert("Click on Change Status button and change the status to - Accepted");
//     }
// }

function convertToSalesOrder(id) {
    var $statusval = $("#statusval").val();
    var approvalflg = $("#approvalflg").val();
    if(approvalflg!=1)
    {
        Swal.fire(
            'Authorization Approval',
            'Authorization approval is required to proceed.',
            'info'
        );
        return;
    }
    if ($statusval == 'Customer PO Received') {
        // Display a SweetAlert confirm box
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to convert this to a sales order?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, convert it!',
            cancelButtonText: "No - Cancel",
            reverseButtons: true,
            focusCancel: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: baseurl + 'quote/convert_to_salesorder',
                    data: { id: id },
                    dataType: 'JSON',
                    success: function(response) {
                        var flg = response.flg;
                        if (flg == 1) {
                            var id = response.id; 
                            window.location.href = baseurl + 'SalesOrders/salesorder_new?id=' + id + '&token=2';
                            // window.location.href = baseurl + 'quote/quote_to_salesorders?id=' + id;
                        } else {
                            Swal.fire(
                                'Already Converted',
                                'This quote has already been converted to a sales order.',
                                'info'
                            );
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        Swal.fire(
                            'Error',
                            'An error occurred while converting to sales order. Please try again.',
                            'error'
                        );
                    }
                });
            } else {
                // If user cancels, do nothing
                console.log("Conversion cancelled.");
            }
        });
    } else {
        Swal.fire(
            'Invalid Status',
            'Click on Change Status button and change the status to - Customer PO Received for convert this quote to sales order',
            'error'
        );
    }
}

function convertToSalesOrder1(id) {
    var $statusval = $("#statusval").val();
    var approvalflg = $("#approvalflg").val();
    if(approvalflg!=1)
    {
        Swal.fire(
            'Authorization Approval',
            'Authorization approval is required to proceed.',
            'info'
        );
        return;
    }
    // if ($statusval == 'Customer PO Received') {
        // Display a SweetAlert confirm box
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to convert this to a sales order?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, convert it!',
            cancelButtonText: "No - Cancel",
            reverseButtons: true,
            focusCancel: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: baseurl + 'quote/convert_to_salesorder',
                    data: { id: id },
                    dataType: 'JSON',
                    success: function(response) {
                        var flg = response.flg;
                        if (flg == 1) {
                            var id = response.id; 
                            window.location.href = baseurl + 'SalesOrders/salesorder_new?id=' + id + '&token=2';
                        } else {
                            Swal.fire(
                                'Already Converted',
                                'This quote has already been converted to a sales order.',
                                'info'
                            );
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        Swal.fire(
                            'Error',
                            'An error occurred while converting to sales order. Please try again.',
                            'error'
                        );
                    }
                });
            } else {
                // If user cancels, do nothing
                console.log("Conversion cancelled.");
            }
        });
    // } else {
    //     Swal.fire(
    //         'Invalid Status',
    //         'Click on Change Status button and change the status to - Customer PO Received for convert this quote to sales order',
    //         'error'
    //     );
    // }
}



function add_existing_values_to_purchaseorder(pid){
        var cvalue = parseInt($('#ganak').val());
        var functionNum = "'" + cvalue + "'";
        count = $('#saman-row div').length; 
        var data = '<tr><td><input type="text" class="form-control" name="product_name[]" placeholder="Search by Product Name or Item No." id="productname-' + cvalue + '"></td><td><input type="text" class="form-control req amnt" name="product_qty[]" id="amount-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" value="1"  inputmode="numeric"><input type="hidden" id="alert-' + cvalue + '" value=""  name="alert[]"> </td><td><input type="text" class="form-control req prc" name="product_price[]" id="price-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" inputmode="numeric"></td><td> <input type="text" class="form-control vat" name="product_tax[]" id="vat-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" inputmode="numeric"></td> <td class="text-right"> <strong><span class=\'ttlText\' id="result-' + cvalue + '">0</span></strong></td> <td class="text-center"><button type="button" data-rowid="' + cvalue + '" class="btn btn-sm btn-default removeProd" title="Remove" > <i class="fa fa-trash"></i> </button> </td><input type="hidden" name="taxa[]" id="taxa-' + cvalue + '" value="0"><input type="hidden" name="disca[]" id="disca-' + cvalue + '" value="0"><input type="hidden" class="ttInput" name="product_subtotal[]" id="total-' + cvalue + '" value="0"> <input type="hidden" class="pdIn" name="pid[]" id="pid-' + cvalue + '" value="0"> <input type="hidden" name="unit[]" id="unit-' + cvalue + '" value=""> <input type="hidden" name="hsn[]" id="hsn-' + cvalue + '" value=""> <input type="hidden" name="serial[]" id="serial-' + cvalue + '" value=""> <input type="hidden" class="form-control discount" name="product_discount[]" onkeypress="return isNumber(event)" id="discount-' + cvalue + '" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off"></tr>';
        //ajax request
        // $('#saman-row').append(data);
        $('tr.last-item-row').before(data);
        row = cvalue;
        $.ajax({
            url: baseurl + 'search_products/searchByProductID',
            dataType: "json",
            method: 'post',
            data: {pid:pid},
            success: function (data) {
                if(data!=""){
                    $(".startRow").remove();
                    $("#productname-" + cvalue).val(data[0][0]);
                    $('#amount-' + cvalue).val(1);                    
                    $("#price-" + cvalue).val(data[0][1]);
                    $('#pid-' + cvalue).val(data[0][2]);
                    $('#vat-' + cvalue).val(data[0][3]);
                    $('#discount-' + cvalue).val(data[0][4]);
                    $('#dpid-' + cvalue).val(data[0][5]);
                    $('#unit-' + cvalue).val(data[0][6]);
                    $('#hsn-' + cvalue).val(data[0][7]);
                    $('#alert-' + cvalue).val(data[0][8]);
                    $('#serial-' + cvalue).val(data[0][10]);
                    rowTotal(cvalue);
                    billUpyog();
                }
                
            }
        });
    
    var nxt = parseInt(cvalue) + 1;
    $('#ganak').val(nxt);
}




$('#productname1-0').autocomplete({
    source: function (request, response) {
        $.ajax({
            url: baseurl + 'enquiry/search',
            dataType: "json",
            method: 'post',
            data: {
                name_startsWith: request.term,
                type: 'product_list',
                row_num: 1
            },
            success: function (data) {
                response($.map(data, function (item) {
                    return {
                        label: item[0], // product_name
                        value: item[0], // product_name
                        data: item
                    };
                }));
            }
        });
    },
    autoFocus: true,
    minLength: 0,
    select: function (event, ui) {
        var names = ui.item.data;
        $('#pid-0').val(names[1]);
    }
});

function autocompletePrdts(ival){
    $('#productname1-'+ival).autocomplete({
        source: function (request, response) {
            $.ajax({
                url: baseurl + 'enquiry/search',
                dataType: "json",
                method: 'post',
                data: {
                    name_startsWith: request.term,
                    type: 'product_list',
                    row_num: 1
                },
                success: function (data) {
                    response($.map(data, function (item) {
                        return {
                            label: item[0], // product_name
                            value: item[0], // product_name
                            data: item
                        };
                    }));
                }
            });
        },
        autoFocus: true,
        minLength: 0,
        select: function (event, ui) {
            var names = ui.item.data;
            $('#pid-'+ival).val(names[1]);
        }
    });
}


function refreshRows() {

    var discount_handle = $("#discount_handle").val();
    var tax_status = $("#tax_status").val();

    if (tax_status == 'no' && discount_handle != 'no') {

        if ($('#saman-row').find('.col-sm-5').length != 0) {

            $('.extendable').each(function () {
                $('#saman-row, .items-pnl-head').find('.extendable').removeClass('col-sm-5');
                $('#saman-row, .items-pnl-head').find('.extendable').addClass('col-sm-6');
            });
        }

        if ($('#saman-row').find('.col-sm-7').length != 0) {

            $('.extendable').each(function () {
                $('#saman-row, .items-pnl-head').find('.extendable').removeClass('col-sm-7');
                $('#saman-row, .items-pnl-head').find('.extendable').addClass('col-sm-6');
            });
        }

    } else if (tax_status != 'no' && discount_handle == 'no') {

        if ($('#saman-row').find('.col-sm-5').length != 0) {
            $('.extendable').each(function () {
                $('#saman-row, .items-pnl-head').find('.extendable').removeClass('col-sm-5');
                $('#saman-row, .items-pnl-head').find('.extendable').addClass('col-sm-6');
            });
        }

        if ($('#saman-row').find('.col-sm-7').length != 0) {
            $('.extendable').each(function () {
                $('#saman-row, .items-pnl-head').find('.extendable').removeClass('col-sm-7');
                $('#saman-row, .items-pnl-head').find('.extendable').addClass('col-sm-6');
            });
        }

    } else if (tax_status == 'no' && discount_handle == 'no') {

        if ($('#saman-row').find('.col-sm-6').length != 0) {
            $('.extendable').each(function () {
                $('#saman-row, .items-pnl-head').find('.extendable').removeClass('col-sm-6');
                $('#saman-row, .items-pnl-head').find('.extendable').addClass('col-sm-7');
            });
        }

        if ($('#saman-row').find('.col-sm-5').length != 0) {
            $('.extendable').each(function () {
                $('#saman-row, .items-pnl-head').find('.extendable').removeClass('col-sm-5');
                $('#saman-row, .items-pnl-head').find('.extendable').addClass('col-sm-7');
            });
        }

    }
    else {
        $('.extendable').each(function () {
            $('#saman-row, .items-pnl-head').find('.extendable').removeClass('col-sm-6');
            $('#saman-row, .items-pnl-head').find('.extendable').addClass('col-sm-5');
        });
    }

}

$('#productrequest-0').autocomplete({
    source: function (request, response) {
        $('#productrequest-0').removeAttr('data-id');
        var warehouse = $("#wfrom option:selected").val();
        if($('#productrequest-0').val() !="" && warehouse!="")
        {
            $.ajax({
                url: baseurl + 'search_products/' + billtype,
                dataType: "json",
                method: 'post',
                data: 'name_startsWith=' + request.term + '&type=product_list&row_num=1&wid=' + $("#wfrom option:selected").val() + '&' + d_csrf,
                success: function (data) {  
                    console.log(data);             
                    response($.map(data, function (item) {
                        var product_d = item[0]+" ("+ item[2] +")";
                        return {
                            label: product_d,
                            value: product_d,
                            data: item,
                            product_id: item[2]
                        };
                    }));
                }
            });
        }
    },
    autoFocus: true,
    minLength: 0,
    select: function (event, ui) {
        var prid = ui.item.data[1];
        var unit = ui.item.data[3];
        var onhand = ui.item.data[4];
        $('#productrequest-0').attr('data-id', prid);
        $('#products_id_0').val(prid);
        $('#onhand-0').val(onhand);
        $('#products_unit_0').val(unit);
    }
});


//sweet alert box click effect
$(document).ready(function() {
    var currentURL = window.location.href.split('?')[0];
    var predefinedUrls = [
        baseurl + 'quote/create',
        baseurl + 'quote/edit',
        baseurl + 'quote/salesorders',
        baseurl + 'quote/convert_to_quote',
        baseurl + 'invoices/newcustomerenquiry',
        baseurl + 'invoices/customer_leads',
    ];
    
    function isUrlInArray(url, array) {
        return array.indexOf(url) !== -1;
    }

    // if (isUrlInArray(currentURL, predefinedUrls)) {

        var hasUnsavedChanges = false;
        $('input:not(:checkbox), select, textarea').on('input change', function() {
            hasUnsavedChanges = true;
        });
        $(document).on('input change', 'input:not(:checkbox), select, textarea', function() {
            hasUnsavedChanges = true;
        });
        
        // Track form submission
        $('form').submit(function() {
            hasUnsavedChanges = false;
        });

        $('select.breaklink, textarea.breaklink, input[type=text].breaklink').on('change input', function() {
            hasUnsavedChanges = false;
        });
     

        // Handle link clicks
        // Use event delegation for link clicks
        $(document).on('click', 'a', function(event) {
            // Check if the link has a class that allows breaking the unsaved changes confirmation
            if ($(this).hasClass('breaklink')) {
                return; // Allow the link click without confirmation
            }
            if ($(this).hasClass('page-link')) {
                return; // Allow the link click without confirmation
            }

            if (hasUnsavedChanges) {
                event.preventDefault(); // Prevent the default action of the link
                var proceedUrl = $(this).attr('href'); // Get the URL from the link

                Swal.fire({
                    title: 'Confirmation',
                    text: "You have unsaved changes. Do you want to proceed without saving?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No',
                    reverseButtons: true,
                    focusCancel: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        hasUnsavedChanges = false; // Reset the flag
                        window.location.href = proceedUrl; // Redirect to the new URL
                    } else {
                        Swal.fire('Action cancelled.', '', 'info');
                    }
                });
            }
        });

    // }
});


function discounttypeChange(numb) {
    var selectedValue = $("#discounttype-" + numb + " option:selected").val();
    $("#discount-" + numb).addClass("d-none");
    $("#discountamt-" + numb).addClass("d-none");    
    var price = parseFloat($("#price-" + numb).val()) || 0;
    var lowestPrice = parseFloat($("#lowestprice-" + numb).val()) || 0;
    var discount = parseFloat($("#discount-" + numb).val()) || 0;
    var discountAmt = parseFloat($("#discountamt-" + numb).val()) || 0;
    var maxdiscountrate = parseFloat($("#maxdiscountrate-" + numb).val()) || 0;
    $("#discount-error-"+ numb).html("");
    if (selectedValue === "Perctype") {
        $("#discount-" + numb).removeClass("d-none");
        // Calculate percentage amount
        var percentageAmount = price - ((discount / 100) * price);
        if(discount>maxdiscountrate){
            $("#discount-" + numb).val(0);            
            $("#discount-error-"+ numb).html("<span class='text-danger'>Discount is Higher!</span>");
        }
        else if (percentageAmount < lowestPrice) {
            // Swal.fire({
            //     title: 'Warning!',
            //     text: 'The product price after applying the discount should not be greater than the lowest price.',
            //     icon: 'warning',
            //     confirmButtonText: 'OK'
            // });
            // $("#discount-error"+ numb).val("Error");
            $("#discount-" + numb).val(0);            
            $("#discount-error-"+ numb).html("<span class='text-danger'>Discount is Higher!</span>");
        }
        else{
            $("#discount-error-"+ numb).html("");
        }
    } else if (selectedValue === "Amttype") {
        $("#discountamt-" + numb).removeClass("d-none");
        var finalvalue = price - discountAmt;
        // Compare discount amount with lowest price
        if (finalvalue < lowestPrice) {
            // Swal.fire({
            //     title: 'Warning!',
            //     text: 'The product price after applying the discount should not be greater than the lowest price.',
            //     icon: 'warning',
            //     confirmButtonText: 'OK'
            // });  
                    
            $("#discountamt-" + numb).val(0);
            $("#discount-error-"+ numb).html("<span class='text-danger'>Discount is Higher!</span>"); 
        }
        else{
            $("#discount-error-"+ numb).html("");
        }
    } else {
        $("#discount-" + numb).addClass("d-none");
        $("#discountamt-" + numb).addClass("d-none");
        $("#discount-error-"+ numb).html("");
    }
    
    rowTotal(numb);
    billUpyog();
    
}

function producthistory(id){
    var customer_id1 = $("#customer_id").val();
    var product_id1 = $("#pid-" + id).val();
    $.ajax({
        url: baseurl + 'quote/product_history',
        dataType: "json",
        method: 'post',                
        data: {'product_id': product_id1, 'customer_id' : customer_id1},
        success: function (response) {
            // Create table content from data
            data = response.data;
            var fulldata="";
            if(data!="")
            {
                var itemtitle = itemtitle;
                var tableContent = '<table class="table"><thead><tr><th>Date</th><th>Selling Price</th><th>Sold Price</th></tr></thead><tbody>';
                data.forEach(function(item) {
                    tableContent += '<tr><td>' + item.formatted_invoicedate + '</td><td>' + item.price + '</td><td>' + item.selled_price + '</td></tr>';
                    itemtitle = item.product_name+"("+item.product_code+")";
                });
                tableContent += '</tbody></table>';
                fulldata = "<h5>"+itemtitle+"</h5>"+tableContent;
            }
            else{
                fulldata = "<h5>No History Found..</h5>";
            }
            // Style the table content to add scroll overflow
            var styledTableContent = '<div style="max-height: 400px; overflow-y: auto;">' + fulldata + '</div>';

            // Display the table in a Swal.fire modal
            Swal.fire({
                text: itemtitle,
                html: styledTableContent,
                width: '600px',
                focusConfirm: false,
                confirmButtonText: 'Close'
            });
        }
    });    

}

function single_product_details(id){
    var product_id1 = $("#pid-" + id).val();
    $.ajax({
        url: baseurl + 'products/product_details',
        dataType: "json",
        method: 'post',                
        data: {'product_id': product_id1},
        success: function (response) {
            // Create table content from data
            data = response.data;
            console.log(data);
            var fulldata="";
            
            if(data!="")
            {
                var itemtitle = itemtitle
                var tableContent = '<table class="table">';
                // var tableContent = '<table class="table"><thead><tr><th>Date</th></tr></thead><tbody>';
                tableContent += '<tr><td>Arabic Name</td><td>' + data.arabic_name + '</td></tr>';
                tableContent += '<tr><td>Description</td><td>' + data.product_des + '</td></tr>';
                tableContent += '<tr><td>Cost</td><td>' + data.product_cost + '</td></tr>';
                tableContent += '<tr><td>Selling Price</td><td>' + data.product_price + '</td></tr>';
                tableContent += '<tr><td>Web Price</td><td>' + data.web_price + '</td></tr>';
                tableContent += '<tr><td>Wholesale Price</td><td>' + data.wholesale_price + '</td></tr>';
                tableContent += '<tr><td>Lowest Price</td><td>' + data.min_price + '</td></tr>';
                tableContent += '<tr><td>Category</td><td>' + data.category + '</td></tr>';
                tableContent += '<tr><td>Supplier</td><td>' + data.supplier + '</td></tr>';
                tableContent += '<tr><td>Made In</td><td>' + data.madein + '</td></tr>';
                itemtitle = data.product_name+"("+data.product_code+")";
                tableContent += '</tbody></table>';
                fulldata = "<h4><b>"+itemtitle+"</b></h4>"+tableContent;
            }
            else{
                fulldata = "<h5>No History Found..</h5>";
            }
            // Style the table content to add scroll overflow
            var styledTableContent = '<div style="max-height: 500px; overflow-y: auto;">' + fulldata + '</div>';

            // Display the table in a Swal.fire modal
            Swal.fire({
                text: itemtitle,
                html: styledTableContent,
                width: '600px',
                focusConfirm: false,
                confirmButtonText: 'Close'
            });
        }
    });    

}
function single_product_direct_details(id){
    var product_id1 = id;
    $.ajax({
        url: baseurl + 'products/product_details',
        dataType: "json",
        method: 'post',                
        data: {'product_id': product_id1},
        success: function (response) {
            // Create table content from data
            data = response.data;
            console.log(data);
            var fulldata="";
            
            if(data!="")
            {
                var itemtitle = itemtitle
                var tableContent = '<table class="table">';
                // var tableContent = '<table class="table"><thead><tr><th>Date</th></tr></thead><tbody>';
                tableContent += '<tr><td>Arabic Name</td><td>' + data.arabic_name + '</td></tr>';
                tableContent += '<tr><td>Description</td><td>' + data.product_des + '</td></tr>';
                tableContent += '<tr><td>Cost</td><td>' + data.product_cost + '</td></tr>';
                tableContent += '<tr><td>Selling Price</td><td>' + data.product_price + '</td></tr>';
                tableContent += '<tr><td>Web Price</td><td>' + data.web_price + '</td></tr>';
                tableContent += '<tr><td>Wholesale Price</td><td>' + data.wholesale_price + '</td></tr>';
                tableContent += '<tr><td>Lowest Price</td><td>' + data.min_price + '</td></tr>';
                tableContent += '<tr><td>Category</td><td>' + data.category + '</td></tr>';
                tableContent += '<tr><td>Supplier</td><td>' + data.supplier + '</td></tr>';
                tableContent += '<tr><td>Made In</td><td>' + data.madein + '</td></tr>';
                itemtitle = data.product_name+"("+data.product_code+")";
                tableContent += '</tbody></table>';
                fulldata = "<h4><b>"+itemtitle+"</b></h4>"+tableContent;
            }
            else{
                fulldata = "<h5>No History Found..</h5>";
            }
            // Style the table content to add scroll overflow
            var styledTableContent = '<div style="max-height: 500px; overflow-y: auto;">' + fulldata + '</div>';

            // Display the table in a Swal.fire modal
            Swal.fire({
                text: itemtitle,
                html: styledTableContent,
                width: '600px',
                focusConfirm: false,
                confirmButtonText: 'Close'
            });
        }
    });    

}

function checkDiscountRate() {
    var productPrice = parseFloat($('#product_price').val());
    var minPrice = parseFloat($('#min_price').val());
    var maxDisrate = parseFloat($("#maximum_discount_rate").val());
    var defaultrate = parseFloat($("#product_disc").val());  
    $("#defaultdisval").text(productPrice);
    if ((!isNaN(productPrice) && productPrice > 0 ) && (!isNaN(maxDisrate) && maxDisrate >= 0 )){
            var discountValue = productPrice - ((productPrice * defaultrate) / 100);
            discountValue = discountValue.toFixed(2);
            $("#defaultdisval").text(discountValue);
            if(maxDisrate < defaultrate){                
                $("#defaultdisval").text(productPrice);
                $("#product_disc").val(0);
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid',
                    text: 'Default Discount % shall be less than Maximum %.'
                });
            }
            
    } else {            
        $('#product_price').focus();
        $('#product_disc').val(0);
        Swal.fire({
            icon: 'warning',
            title: 'Incorrect values',
            text: 'Please enter a valid cost or Maximum Discount Rate'
        });
    }
}

function checkMaxDiscountRate() {
    
    $("#maxdisrate").text("");
    var productPrice = parseFloat($('#product_price').val());
    var minPrice = parseFloat($('#min_price').val());
    var maxDisrate = parseFloat($('#maximum_discount_rate').val());
    // var allowedmax = productPrice - minPrice;
    // var allowedmaxperc = (allowedmax / productPrice) * 100;
    // allowedmaxperc = allowedmaxperc.toFixed(1);
    // $("#maxdisrate").text(allowedmaxperc + '%');
    // $("#maxdisval").text(productPrice);
    allowedMAxdiscount();
    if (!isNaN(productPrice) && productPrice > 0 ){
        if (!isNaN(maxDisrate)){
            var discountValue = productPrice - ((productPrice * maxDisrate) / 100);
            discountValue = discountValue.toFixed(2);
            checkDiscountRate();
            $("#maxdisval").text(discountValue);
            if (minPrice > discountValue) {
                $('#maximum_discount_rate').val(0);
                $("#maxdisval").text(productPrice);           
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid',
                    text: 'Maximum discounted price shall be higher/equal than Minimum Price.'
                });
            }
        } 
    } else {            
        $('#product_price').focus();
        $('#maximum_discount_rate').val(0);
        Swal.fire({
            icon: 'warning',
            title: 'Invalid Cost',
            text: 'Please enter a valid cost.'
        });
    }
}
function allowedMAxdiscount() {
    $("#maxdisrate").text("");
    $("#defmaxdisrate").text("");
    if($('#maximum_discount_rate').val()==""){
        $('#maximum_discount_rate').val(0);
        $('#product_disc').val(0);
    }
    var productPrice = parseFloat($('#product_price').val());
    var minPrice = parseFloat($('#min_price').val());
    var allowedmax = productPrice - minPrice;
    var allowedmaxperc = (allowedmax / productPrice) * 100;    
    var maxDisrate = allowedmaxperc;
    allowedmaxperc1 = allowedmaxperc.toFixed(1);
    $("#maxdisrate").text(allowedmaxperc1);    
    $("#defmaxdisrate").text(allowedmaxperc1);    
    $("#maxdisval").text(productPrice);
    $("#defaultdisval").text(productPrice);
}   
function allowedMAxdiscountedit() {
    $("#maxdisrate").text("");
    $("#defmaxdisrate").text("");
    // $('#maximum_discount_rate').val(0);
    // $('#product_disc').val(0);
    var productPrice = parseFloat($('#product_price').val());
    var minPrice = parseFloat($('#min_price').val());
    var allowedmax = productPrice - minPrice;
    var allowedmaxperc = (allowedmax / productPrice) * 100;    
    var maxDisrate = allowedmaxperc;
    allowedmaxperc1 = allowedmaxperc.toFixed(1);
    $("#maxdisrate").text(allowedmaxperc1);    
    $("#defmaxdisrate").text(allowedmaxperc1);    
    $("#maxdisval").text(productPrice);
    $("#defaultdisval").text(productPrice);
}   
// for Quote 
$('#addproduct_quotecreate_new').on('click', function () {
    var cvalue = parseInt($('#ganak').val()) + 1;
    var nxt = parseInt(cvalue);
    $('#ganak').val(nxt);
    var functionNum = "'" + cvalue + "'";
    count = $('#saman-row div').length;
    var data = '<tr><td><input type="text" class="form-control" name="product_name[]" placeholder="Search by Product Name or Item No." id="productname-' + cvalue + '"></td><td><input type="text" class="form-control req amnt" name="product_qty[]" id="amount-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" value="1"  inputmode="numeric"><input type="hidden" id="alert-' + cvalue + '" value=""  name="alert[]"> </td><td class="text-center"><strong id="onhandQty-' + cvalue + '"></strong></td><td><input type="text" class="form-control req prc" name="product_price[]" id="price-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" inputmode="numeric"></td><td> <input type="text" class="form-control vat" name="product_tax[]" id="vat-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" inputmode="numeric"></td> <td id="texttaxa-' + cvalue + '" class="text-center">0</td> <td><input type="text" class="form-control discount" name="product_discount[]" onkeypress="return isNumber(event)" id="discount-' + cvalue + '" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off"></td> <td><span class="currenty">' + currency + '</span> <strong><span class=\'ttlText\' id="result-' + cvalue + '">0</span></strong></td> <td class="text-center"><button type="button" data-rowid="' + cvalue + '" class="btn btn-sm btn-default removeProd" title="Remove" > <i class="fa fa-trash"></i> </button> <button onclick="producthistory('+cvalue+')" type="button" class="btn btn-sm btn-secondary producthis"><i class="fa fa-history"></i></button> </td><input type="hidden" name="taxa[]" id="taxa-' + cvalue + '" value="0"><input type="hidden" name="disca[]" id="disca-' + cvalue + '" value="0"><input type="hidden" class="ttInput" name="product_subtotal[]" id="total-' + cvalue + '" value="0"> <input type="hidden" class="pdIn" name="pid[]" id="pid-' + cvalue + '" value="0"> <input type="hidden" name="unit[]" id="unit-' + cvalue + '" value=""> <input type="hidden" name="hsn[]" id="hsn-' + cvalue + '" value=""> <input type="hidden" name="serial[]" id="serial-' + cvalue + '" value=""> </tr><tr><td colspan="9"><textarea class="form-control"  id="dpid-' + cvalue + '" name="product_description[]" placeholder="Enter Product description" autocomplete="off"></textarea></td></tr>';
    //ajax request
    // $('#saman-row').append(data);
    $('tr.last-item-row').before(data);
    row = cvalue;

    $('#productname-' + cvalue).autocomplete({
        
        source: function (request, response) {
            $.ajax({
                url: baseurl + 'search_products/' + billtype,
                dataType: "json",
                method: 'post',
                data: 'name_startsWith=' + request.term + '&type=product_list&row_num=' + row + '&wid=' + $("#s_warehouses option:selected").val() + '&' + d_csrf,
                success: function (data) {
                    response($.map(data, function (item) {
                        // var product_d = item[0];
                        var product_d = item[0]+" ("+item[7]+")";
                        return {
                            label: product_d,
                            value: product_d,
                            data: item
                        };
                    }));
                }
            });
        },
        autoFocus: true,
        minLength: 0,
        select: function (event, ui) {
            id_arr = $(this).attr('id');
            id = id_arr.split("-");
            var t_r = ui.item.data[3];
            if ($("#taxformat option:selected").attr('data-trate')) {

                t_r = $("#taxformat option:selected").attr('data-trate');
            }
            var discount = ui.item.data[4];
            var custom_discount = $('#custom_discount').val();
            if (custom_discount > 0) discount = deciFormat(custom_discount);
            $("#onhandQty-" + id[1]).text(parseInt(ui.item.data[8]));
            $("#prd-" + id[1]).val(ui.item.data[2]);
            $('#amount-' + id[1]).val(1);
            $('#price-' + id[1]).val(ui.item.data[1]);
            $('#pid-' + id[1]).val(ui.item.data[2]);
            $('#vat-' + id[1]).val(t_r);
            $('#discount-' + id[1]).val(discount);
            $('#dpid-' + id[1]).val(ui.item.data[5]);
            $('#unit-' + id[1]).val(ui.item.data[6]);
            $('#hsn-' + id[1]).val(ui.item.data[7]);
            $('#alert-' + id[1]).val(parseInt(ui.item.data[8]));
            $('#serial-' + id[1]).val(ui.item.data[10]);
            rowTotal(cvalue);
            billUpyog();


        },
        create: function (e) {
            $(this).prev('.ui-helper-hidden-accessible').remove();
        }
    });

});
$('#productname-quote-0').autocomplete({
    
    source: function (request, response) {
        $.ajax({
            url: baseurl + 'search_products/' + billtype,
            dataType: "json",
            method: 'post',
            data: 'name_startsWith=' + request.term + '&type=product_list&row_num=1&wid=' + $("#s_warehouses option:selected").val() + '&' + d_csrf,
            success: function (data) {               
                response($.map(data, function (item) {
                    var product_d = item[0]+" ("+item[7]+")";
                    return {
                        label: product_d,
                        value: product_d,
                        data: item
                    };
                }));
            }
        });
    },
    autoFocus: true,
    minLength: 0,
    select: function (event, ui) {
        var t_r = ui.item.data[3];
        
        if ($("#taxformat option:selected").attr('data-trate')) {

            t_r = $("#taxformat option:selected").attr('data-trate');
        }
        var discount = ui.item.data[4];
        var custom_discount = $('#custom_discount').val();
        if (custom_discount > 0) discount = deciFormat(custom_discount);
        $("#onhandQty").text(parseInt(ui.item.data[8]));
        $('#amount-0').val(1);
        $('#price-0').val(ui.item.data[1]);
        $('#pid-0').val(ui.item.data[2]);
        $('#vat-0').val(t_r);
        $('#discount-0').val(discount);
        $('#dpid-0').val(ui.item.data[5]);
        $('#unit-0').val(ui.item.data[6]);
        $('#hsn-0').val(ui.item.data[7]);
        $('#alert-0').val(parseInt(ui.item.data[8]));
        $('#serial-0').val(ui.item.data[10]);
        rowTotal(0);
        billUpyog();
    }
});

// lead product search
$('#leadproductname-0').autocomplete({
    
    source: function (request, response) {
        $.ajax({
            url: baseurl + 'search_products/' + billtype,
            dataType: "json",
            method: 'post',
            data: 'name_startsWith=' + request.term + '&type=product_list&row_num=1&wid=' + $("#s_warehouses option:selected").val() + '&' + d_csrf,
            success: function (data) {               
                response($.map(data, function (item) {
                    // var product_d = item[0]+" ("+item[7]+")";
                    var product_d = item[0];
                    return {
                        label: item[0]+" ("+item[7]+")",
                        value: product_d,
                        data: item
                    };
                }));
            }
        });
    },
    autoFocus: true,
    minLength: 0,
    select: function (event, ui) {
        var t_r = ui.item.data[3];
        if ($("#taxformat option:selected").attr('data-trate')) {

            t_r = $("#taxformat option:selected").attr('data-trate');
        }
        var discount = ui.item.data[4];
        var custom_discount = $('#custom_discount').val();
        if (custom_discount > 0) discount = deciFormat(custom_discount);
        $("#onhandQty").text(parseInt(ui.item.data[8]));        
        $('#amount-0').val(1);
        $('#price-0').val(ui.item.data[1]);
        $('#pricelabel-0').text(ui.item.data[1]);
        $('#pid-0').val(ui.item.data[2]);
        $('#vat-0').val(t_r);
        $('#taxlabel-0').text(t_r+" / ");
        $('#discount-0').val(discount);
        $('#dpid-0').val(ui.item.data[5]);
        $('#unit-0').val(ui.item.data[6]);
        $('#hsn-0').val(ui.item.data[7]);
        $('#alert-0').val(parseInt(ui.item.data[8]));
        $('#serial-0').val(ui.item.data[10]);        
        $("#lowestprice-0").val(ui.item.data[11]);
        $("#lowestpricelabel-0").text(ui.item.data[11]);
        $("#maxdiscountrate-0").val(ui.item.data[12]);
        $("#maxdiscountratelabel-0").text(ui.item.data[12]);
        $('#code-0').val(ui.item.data[7]);
        rowTotal(0);
        billUpyog();
        $(".noproduct-section").removeClass('d-none');
    }
});

$('#lead_create_btn').on('click', function () {
    var cvalue = parseInt($('#ganak').val()) + 1;
    var configured_tax = parseInt($('#configured_tax').val());
    var nxt = parseInt(cvalue);
    $('#ganak').val(nxt);
    var functionNum = "'" + cvalue + "'";
    count = $('#saman-row div').length;
    var data = '<tr><td><input type="text" class="form-control" name="product_name[]" placeholder="Search by Product Name or Item No." id="productname-' + cvalue + '"></td><td><input type="text" class="form-control" name="code[]"  id="code-' + cvalue + '" readonly></td><td><input type="number" class="form-control req amnt" name="product_qty[]" id="amount-' + cvalue + '" maxlength="6" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" value="1"  inputmode="numeric"><input type="hidden" id="alert-' + cvalue + '" value=""  name="alert[]"> </td><td class="text-center"><strong id="onhandQty-' + cvalue + '"></strong></td>';

    data += '<td class="text-right"><strong id="pricelabel-' + cvalue + '"></strong><input type="hidden" class="form-control" name="lowest_price[]" id="lowestprice-' + cvalue + '" onkeypress="return isNumber(event)" autocomplete="off"></td>';

    data += '<td class="text-right"><strong id="lowestpricelabel-' + cvalue + '"></strong><input type="hidden" class="form-control req prc" name="product_price[]" id="price-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" inputmode="numeric"></td>';

    if(configured_tax!=0){
        data += '<td class="text-center"> <input type="hidden" class="form-control vat" name="product_tax[]" id="vat-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" inputmode="numeric"><strong id="taxlabel-' + cvalue + '"></strong>&nbsp;<strong  id="texttaxa-' + cvalue + '"></strong></td> ';
    }
    else{
        var hiddendata = '<input type="text" class="form-control vat" name="product_tax[]" id="vat-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" inputmode="numeric">';
    }
    data+='<td class="text-center"><strong id="maxdiscountratelabel-' + cvalue + '"></strong></td>';
    
    data+='<td class="text-center"><div class="input-group text-center"><select name="discount_type[]" id="discounttype-' + cvalue + '" class="form-control" onchange="discounttypeChange(' + cvalue + ')"><option value="Perctype">%</option><option value="Amttype">Amt</option></select>&nbsp;<input type="number" min="0" class="form-control discount" name="product_discount[]" onkeypress="return isNumber(event)" id="discount-' + cvalue + '"  autocomplete="off" onkeyup="discounttypeChange(' + cvalue + ')"><input type="number" min="0" class="form-control discount d-none" name="product_amt[]" onkeypress="return isNumber(event)" id="discountamt-' + cvalue + '" autocomplete="off" onkeyup="discounttypeChange(' + cvalue + ')"></div><strong class="discount-amtlabel" id="discount-amtlabel-' + cvalue + '"></strong><div><strong id="discount-error-' + cvalue + '"></strong></div></td>';

    data += '<td  class="text-right"><strong><span class=\'ttlText\' id="result-' + cvalue + '">0</span></strong></td> <td class="text-center"> <button onclick="producthistory('+cvalue+')" type="button" class="btn btn-sm btn-secondary producthis"><i class="fa fa-history"></i></button> <button onclick="single_product_details('+cvalue+')" type="button" class="btn btn-sm btn-secondary"><i class="fa fa-info"></i></button> <button type="button" data-rowid="' + cvalue + '" class="btn btn-sm btn-secondary removeProd" title="Remove" > <i class="fa fa-trash"></i> </button></td>';

    data += '<input type="hidden" name="taxa[]" id="taxa-' + cvalue + '" value="0"><input type="hidden" name="disca[]" id="disca-' + cvalue + '" value="0"><input type="hidden" class="ttInput" name="product_subtotal[]" id="total-' + cvalue + '" value="0"> <input type="hidden" class="pdIn" name="pid[]" id="pid-' + cvalue + '" value="0"> <input type="hidden" name="unit[]" id="unit-' + cvalue + '" value=""> <input type="hidden" name="hsn[]" id="hsn-' + cvalue + '" value=""> <input type="hidden" name="serial[]" id="serial-' + cvalue + '" value="">'+hiddendata+'<input type="hidden" name="maxdiscountrate[]" id="maxdiscountrate-' + cvalue + '"></tr>';

    //ajax request
    // $('#saman-row').append(data);
    $('tr.last-item-row').before(data);
    row = cvalue;

    $('#productname-' + cvalue).autocomplete({
        
        source: function (request, response) {
            $.ajax({
                url: baseurl + 'search_products/' + billtype,
                dataType: "json",
                method: 'post',
                data: 'name_startsWith=' + request.term + '&type=product_list&row_num=' + row + '&wid=' + $("#s_warehouses option:selected").val() + '&' + d_csrf,
                success: function (data) {
                    response($.map(data, function (item) {
                        // var product_d = item[0];
                        var product_d = item[0]+" ("+item[7]+")";
                        return {
                            label: product_d,
                            value: item[0],
                            data: item
                        };
                    }));
                }
            });
        },
        autoFocus: true,
        minLength: 0,
        select: function (event, ui) {
            id_arr = $(this).attr('id');
            id = id_arr.split("-");
            var t_r = ui.item.data[3];
            if ($("#taxformat option:selected").attr('data-trate')) {

                t_r = $("#taxformat option:selected").attr('data-trate');
            }
            var discount = ui.item.data[4];
            var custom_discount = $('#custom_discount').val();
            if (custom_discount > 0) discount = deciFormat(custom_discount);
            $("#onhandQty-" + id[1]).text(parseInt(ui.item.data[8]));
            $("#prd-" + id[1]).val(ui.item.data[2]);
            $('#amount-' + id[1]).val(1);
            $('#price-' + id[1]).val(ui.item.data[1]);
            $('#pricelabel-' + id[1]).text(ui.item.data[1]);
            $('#pid-' + id[1]).val(ui.item.data[2]);
            $('#vat-' + id[1]).val(t_r);
            $('#taxlabel-' + id[1]).text(t_r+" / ");
            // $('#taxlabel-' + id[1]).val(t_r);discount-amtlabel
            $('#discount-' + id[1]).val(discount);
            $('#dpid-' + id[1]).val(ui.item.data[5]);
            $('#unit-' + id[1]).val(ui.item.data[6]);
            $('#hsn-' + id[1]).val(ui.item.data[7]);
            $('#alert-' + id[1]).val(parseInt(ui.item.data[8]));
            $('#serial-' + id[1]).val(ui.item.data[10]);     
            $('#lowestprice-' + id[1]).val(ui.item.data[11]);
            $('#lowestpricelabel-' + id[1]).text(ui.item.data[11]);        
            $('#maxdiscountrate-' + id[1]).val(ui.item.data[12]);
            
            $('#code-'+ id[1]).val(ui.item.data[7]);
            productPrice  = $('#amount-' + id[1]).val(1);
            maxdiscount =  $('#maxdiscountrate-' + id[1]).val(ui.item.data[12]);
            var discountValue = productPrice - ((productPrice * maxdiscount) / 100);
            discountValue = discountValue.toFixed(2);
            $('#maxdiscountratelabel-'+ id[1]).text(discountValue);
            // $('#maxdiscountratelabel-'+ id[1]).text(ui.item.data[12]);
            rowTotal(cvalue);
            billUpyog();
            $(".noproduct-section").removeClass('d-none');


        },
        create: function (e) {
            $(this).prev('.ui-helper-hidden-accessible').remove();
        }
    });

    $('#code-' + cvalue).autocomplete({
        
        source: function (request, response) {
            $.ajax({
                url: baseurl + 'search_products/' + billtype,
                dataType: "json",
                method: 'post',
                data: 'name_startsWith=' + request.term + '&type=product_list&row_num=' + row + '&wid=' + $("#s_warehouses option:selected").val() + '&' + d_csrf,
                success: function (data) {
                    response($.map(data, function (item) {
                        // var product_d = item[0];
                        var product_d = item[0]+" ("+item[7]+")";
                        return {
                            label: product_d,
                            value: item[7],
                            data: item
                        };
                    }));
                }
            });
        },
        autoFocus: true,
        minLength: 0,
        select: function (event, ui) {
            id_arr = $(this).attr('id');
            id = id_arr.split("-");
            var t_r = ui.item.data[3];
            if ($("#taxformat option:selected").attr('data-trate')) {

                t_r = $("#taxformat option:selected").attr('data-trate');
            }
            var discount = ui.item.data[4];
            var custom_discount = $('#custom_discount').val();
            if (custom_discount > 0) discount = deciFormat(custom_discount);
            $("#onhandQty-" + id[1]).text(parseInt(ui.item.data[8]));
            $("#prd-" + id[1]).val(ui.item.data[2]);
            $('#amount-' + id[1]).val(1);
            $('#price-' + id[1]).val(ui.item.data[1]);
            $('#pricelabel-' + id[1]).text(ui.item.data[1]);
            $('#pid-' + id[1]).val(ui.item.data[2]);
            $('#vat-' + id[1]).val(t_r);
            $('#taxlabel-' + id[1]).text(t_r+" / ");
            // $('#taxlabel-' + id[1]).val(t_r);discount-amtlabel
            $('#discount-' + id[1]).val(discount);
            $('#dpid-' + id[1]).val(ui.item.data[5]);
            $('#unit-' + id[1]).val(ui.item.data[6]);
            $('#hsn-' + id[1]).val(ui.item.data[7]);
            $('#alert-' + id[1]).val(parseInt(ui.item.data[8]));
            $('#serial-' + id[1]).val(ui.item.data[10]);     
            $('#lowestprice-' + id[1]).val(ui.item.data[11]);
            $('#lowestpricelabel-' + id[1]).text(ui.item.data[11]);        
            $('#maxdiscountrate-' + id[1]).val(ui.item.data[12]);
            productPrice  = $('#amount-' + id[1]).val(1);
            maxdiscount =  $('#maxdiscountrate-' + id[1]).val(ui.item.data[12]);
            var discountValue = productPrice - ((productPrice * maxdiscount) / 100);
            discountValue = discountValue.toFixed(2);
            alert(discountValue)
            $('#maxdiscountratelabel-'+ id[1]).text(discountValue);
            // $('#maxdiscountratelabel-'+ id[1]).text(ui.item.data[12]);
            $('#productname-'+ id[1]).val(ui.item.data[0]);
            rowTotal(cvalue);
            billUpyog();
            $(".noproduct-section").removeClass('d-none');


        },
        create: function (e) {
            $(this).prev('.ui-helper-hidden-accessible').remove();
        }
    });

});


$('#sales_create_btn').on('click', function () {
    var cvalue = parseInt($('#ganak').val()) + 1;
    var configured_tax = parseInt($('#configured_tax').val());
    var nxt = parseInt(cvalue);
    $('#ganak').val(nxt);
    var functionNum = "'" + cvalue + "'";
    count = $('#saman-row div').length;
    var data = '<tr><td><input type="text" class="form-control code" name="code[]" placeholder="Item No."  id="code-' + cvalue + '"></td><td><input type="text" class="form-control productname" name="product_name[]" placeholder="Search by Product Name or Item No." id="productname-' + cvalue + '"></td><td><input type="number" class="form-control req amnt product_qty" name="product_qty[]" id="amount-' + cvalue + '" maxlength="6" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" value="1"  inputmode="numeric"><input type="hidden" id="alert-' + cvalue + '" value=""  name="alert[]"> </td><td class="text-center"><strong id="onhandQty-' + cvalue + '"></strong></td>';

    data += '<td class="text-right"><strong id="pricelabel-' + cvalue + '"></strong><input type="hidden" class="form-control" name="lowest_price[]" id="lowestprice-' + cvalue + '" onkeypress="return isNumber(event)" autocomplete="off"></td>';

    data += '<td class="text-right"><strong id="lowestpricelabel-' + cvalue + '"></strong><input type="hidden" class="form-control req prc" name="product_price[]" id="price-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" inputmode="numeric"></td>';

    if(configured_tax!=0){
        data += '<td class="text-center"> <input type="hidden" class="form-control vat" name="product_tax[]" id="vat-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" inputmode="numeric"><strong id="taxlabel-' + cvalue + '"></strong>&nbsp;<strong  id="texttaxa-' + cvalue + '"></strong></td> ';
    }
    else{
        var hiddendata = '<input type="text" class="form-control vat" name="product_tax[]" id="vat-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" inputmode="numeric">';
    }
    data+='<td class="text-center"><strong id="maxdiscountratelabel-' + cvalue + '"></strong></td>';
    
    data+='<td class="text-center"><div class="input-group text-center"><select name="discount_type[]" id="discounttype-' + cvalue + '" class="form-control" onchange="discounttypeChange(' + cvalue + ')"><option value="Perctype">%</option><option value="Amttype">Amt</option></select>&nbsp;<input type="number" min="0" class="form-control discount" name="product_discount[]" onkeypress="return isNumber(event)" id="discount-' + cvalue + '"  autocomplete="off" onkeyup="discounttypeChange(' + cvalue + ')"><input type="number" min="0" class="form-control discount d-none" name="product_amt[]" onkeypress="return isNumber(event)" id="discountamt-' + cvalue + '" autocomplete="off" onkeyup="discounttypeChange(' + cvalue + ')"></div><strong class="discount-amtlabel" id="discount-amtlabel-' + cvalue + '"></strong><div><strong id="discount-error-' + cvalue + '"></strong></div></td>';

    data += '<td  class="text-right"><strong><span class=\'ttlText\' id="result-' + cvalue + '">0</span></strong></td> <td class="text-center"> <button onclick="producthistory('+cvalue+')" type="button" class="btn btn-sm btn-secondary producthis"><i class="fa fa-history"></i></button> <button onclick="single_product_details('+cvalue+')" type="button" class="btn btn-sm btn-secondary"><i class="fa fa-info"></i></button> <button type="button" data-rowid="' + cvalue + '" class="btn btn-sm btn-secondary removeProd" title="Remove" > <i class="fa fa-trash"></i> </button></td>';

    data += '<input type="hidden" name="taxa[]" id="taxa-' + cvalue + '" value="0"><input type="hidden" name="disca[]" id="disca-' + cvalue + '" value="0"><input type="hidden" class="ttInput" name="product_subtotal[]" id="total-' + cvalue + '" value="0"> <input type="hidden" class="pdIn" name="pid[]" id="pid-' + cvalue + '" value="0"> <input type="hidden" name="unit[]" id="unit-' + cvalue + '" value=""> <input type="hidden" name="hsn[]" id="hsn-' + cvalue + '" value=""> <input type="hidden" name="serial[]" id="serial-' + cvalue + '" value="">'+hiddendata+'<input type="hidden" name="maxdiscountrate[]" id="maxdiscountrate-' + cvalue + '"></tr>';

    //ajax request
    // $('#saman-row').append(data);
    $('tr.last-item-row').before(data);
    row = cvalue;

    $('#productname-' + cvalue).autocomplete({
        
        source: function (request, response) {
            $.ajax({
                url: baseurl + 'search_products/' + billtype,
                dataType: "json",
                method: 'post',
                data: 'name_startsWith=' + request.term + '&type=product_list&row_num=' + row + '&wid=' + $("#s_warehouses option:selected").val() + '&' + d_csrf,
                success: function (data) {
                    response($.map(data, function (item) {
                        // var product_d = item[0];
                        var product_d = item[0]+" ("+item[7]+")";
                        return {
                            label: product_d,
                            value: item[0],
                            data: item
                        };
                    }));
                }
            });
        },
        autoFocus: true,
        minLength: 0,
        select: function (event, ui) {
            id_arr = $(this).attr('id');
            id = id_arr.split("-");
            var t_r = ui.item.data[3];
            if ($("#taxformat option:selected").attr('data-trate')) {

                t_r = $("#taxformat option:selected").attr('data-trate');
            }
            var discount = ui.item.data[4];
            var custom_discount = $('#custom_discount').val();
            if (custom_discount > 0) discount = deciFormat(custom_discount);
            $("#onhandQty-" + id[1]).text(parseInt(ui.item.data[8]));
            $("#prd-" + id[1]).val(ui.item.data[2]);
            $('#amount-' + id[1]).val(1);
            $('#price-' + id[1]).val(ui.item.data[1]);
            $('#pricelabel-' + id[1]).text(ui.item.data[1]);
            $('#pid-' + id[1]).val(ui.item.data[2]);
            $('#vat-' + id[1]).val(t_r);
            $('#taxlabel-' + id[1]).text(t_r+" / ");
            // $('#taxlabel-' + id[1]).val(t_r);discount-amtlabel
            $('#discount-' + id[1]).val(discount);
            $('#dpid-' + id[1]).val(ui.item.data[5]);
            $('#unit-' + id[1]).val(ui.item.data[6]);
            $('#hsn-' + id[1]).val(ui.item.data[7]);
            $('#alert-' + id[1]).val(parseInt(ui.item.data[8]));
            $('#serial-' + id[1]).val(ui.item.data[10]);     
            $('#lowestprice-' + id[1]).val(ui.item.data[11]);
            $('#lowestpricelabel-' + id[1]).text(ui.item.data[11]);        
            $('#maxdiscountrate-' + id[1]).val(ui.item.data[12]);
            $('#maxdiscountratelabel-'+ id[1]).text(ui.item.data[12]);
            $('#code-'+ id[1]).val(ui.item.data[7]);
            rowTotal(cvalue);
            billUpyog();
            $(".noproduct-section").removeClass('d-none');


        },
        create: function (e) {
            $(this).prev('.ui-helper-hidden-accessible').remove();
        }
    });

    $('#code-' + cvalue).autocomplete({
        
        source: function (request, response) {
            $.ajax({
                url: baseurl + 'search_products/' + billtype,
                dataType: "json",
                method: 'post',
                data: 'name_startsWith=' + request.term + '&type=product_list&row_num=' + row + '&wid=' + $("#s_warehouses option:selected").val() + '&' + d_csrf,
                success: function (data) {
                    response($.map(data, function (item) {
                        // var product_d = item[0];
                        var product_d = item[0]+" ("+item[7]+")";
                        return {
                            label: product_d,
                            value: item[7],
                            data: item
                        };
                    }));
                }
            });
        },
        autoFocus: true,
        minLength: 0,
        select: function (event, ui) {
            id_arr = $(this).attr('id');
            id = id_arr.split("-");
            var t_r = ui.item.data[3];
            if ($("#taxformat option:selected").attr('data-trate')) {

                t_r = $("#taxformat option:selected").attr('data-trate');
            }
            var discount = ui.item.data[4];
            var custom_discount = $('#custom_discount').val();
            if (custom_discount > 0) discount = deciFormat(custom_discount);
            $("#onhandQty-" + id[1]).text(parseInt(ui.item.data[8]));
            $("#prd-" + id[1]).val(ui.item.data[2]);
            $('#amount-' + id[1]).val(1);
            $('#price-' + id[1]).val(ui.item.data[1]);
            $('#pricelabel-' + id[1]).text(ui.item.data[1]);
            $('#pid-' + id[1]).val(ui.item.data[2]);
            $('#vat-' + id[1]).val(t_r);
            $('#taxlabel-' + id[1]).text(t_r+" / ");
            // $('#taxlabel-' + id[1]).val(t_r);discount-amtlabel
            $('#discount-' + id[1]).val(discount);
            $('#dpid-' + id[1]).val(ui.item.data[5]);
            $('#unit-' + id[1]).val(ui.item.data[6]);
            $('#hsn-' + id[1]).val(ui.item.data[7]);
            $('#alert-' + id[1]).val(parseInt(ui.item.data[8]));
            $('#serial-' + id[1]).val(ui.item.data[10]);     
            $('#lowestprice-' + id[1]).val(ui.item.data[11]);
            $('#lowestpricelabel-' + id[1]).text(ui.item.data[11]);        
            $('#maxdiscountrate-' + id[1]).val(ui.item.data[12]);
            $('#maxdiscountratelabel-'+ id[1]).text(ui.item.data[12]);
            $('#productname-'+ id[1]).val(ui.item.data[0]);
            rowTotal(cvalue);
            billUpyog();
            $(".noproduct-section").removeClass('d-none');


        },
        create: function (e) {
            $(this).prev('.ui-helper-hidden-accessible').remove();
        }
    });

});
// lead product search
$('#leadproductname-0').autocomplete({
    
    source: function (request, response) {
        $.ajax({
            url: baseurl + 'search_products/' + billtype,
            dataType: "json",
            method: 'post',
            data: 'name_startsWith=' + request.term + '&type=product_list&row_num=1&wid=' + $("#s_warehouses option:selected").val() + '&' + d_csrf,
            success: function (data) {               
                response($.map(data, function (item) {
                    // var product_d = item[0]+" ("+item[7]+")";
                    var product_d = item[0];
                    return {
                        label: item[0]+" ("+item[7]+")",
                        value: product_d,
                        data: item
                    };
                }));
            }
        });
    },
    autoFocus: true,
    minLength: 0,
    select: function (event, ui) {
        var t_r = ui.item.data[3];
        if ($("#taxformat option:selected").attr('data-trate')) {

            t_r = $("#taxformat option:selected").attr('data-trate');
        }
        var discount = ui.item.data[4];
        var custom_discount = $('#custom_discount').val();
        if (custom_discount > 0) discount = deciFormat(custom_discount);
        $("#onhandQty").text(parseInt(ui.item.data[8]));        
        $('#amount-0').val(1);
        $('#price-0').val(ui.item.data[1]);
        $('#pricelabel-0').text(ui.item.data[1]);
        $('#pid-0').val(ui.item.data[2]);
        $('#vat-0').val(t_r);
        $('#taxlabel-0').text(t_r+" / ");
        $('#discount-0').val(discount);
        $('#dpid-0').val(ui.item.data[5]);
        $('#unit-0').val(ui.item.data[6]);
        $('#hsn-0').val(ui.item.data[7]);
        $('#alert-0').val(parseInt(ui.item.data[8]));
        $('#serial-0').val(ui.item.data[10]);        
        $("#lowestprice-0").val(ui.item.data[11]);
        $("#lowestpricelabel-0").text(ui.item.data[11]);
        $("#maxdiscountrate-0").val(ui.item.data[12]);
        $("#maxdiscountratelabel-0").text(ui.item.data[12]);
        $('#code-0').val(ui.item.data[7]);
        rowTotal(0);
        billUpyog();
        $(".noproduct-section").removeClass('d-none');
    }
});

function leadedit_autocomplete(val) {
    var cvalue = val;
    var configured_tax = parseInt($('#configured_tax').val());
    var nxt = parseInt(cvalue);
 
    row = cvalue;
    $('#leadproductname-'+cvalue).autocomplete({
    
        source: function (request, response) {
            $.ajax({
                url: baseurl + 'search_products/' + billtype,
                dataType: "json",
                method: 'post',
                data: 'name_startsWith=' + request.term + '&type=product_list&row_num=1&wid=' + $("#s_warehouses option:selected").val() + '&' + d_csrf,
                success: function (data) {               
                    response($.map(data, function (item) {
                        // var product_d = item[0]+" ("+item[7]+")";
                        var product_d = item[0];
                        return {
                            label: item[0]+" ("+item[7]+")",
                            value: product_d,
                            data: item
                        };
                    }));
                }
            });
        },
        autoFocus: true,
        minLength: 0,
        select: function (event, ui) {
            var t_r = ui.item.data[3];
            if ($("#taxformat option:selected").attr('data-trate')) {
    
                t_r = $("#taxformat option:selected").attr('data-trate');
            }
            var discount = ui.item.data[4];
            var custom_discount = $('#custom_discount').val();
            if (custom_discount > 0) discount = deciFormat(custom_discount);
            $("#onhandQty").text(parseInt(ui.item.data[8]));        
            $('#amount-'+cvalue).val(1);
            $('#price-'+cvalue).val(ui.item.data[1]);
            $('#pricelabel-'+cvalue).text(ui.item.data[1]);
            $('#pid-'+cvalue).val(ui.item.data[2]);
            $('#vat-'+cvalue).val(t_r);
            $('#taxlabel-'+cvalue).text(t_r+" / ");
            $('#discount-'+cvalue).val(discount);
            $('#dpid-'+cvalue).val(ui.item.data[5]);
            $('#unit-'+cvalue).val(ui.item.data[6]);
            $('#hsn-'+cvalue).val(ui.item.data[7]);
            $('#alert-'+cvalue).val(parseInt(ui.item.data[8]));
            $('#serial-'+cvalue).val(ui.item.data[10]);        
            $("#lowestprice-0").val(ui.item.data[11]);
            $("#lowestpricelabel-0").text(ui.item.data[11]);
            $("#maxdiscountrate-0").val(ui.item.data[12]);
            $("#maxdiscountratelabel-0").text(ui.item.data[12]);
            $('#code-'+cvalue).val(ui.item.data[7]);
            rowTotal(0);
            billUpyog();
            $(".noproduct-section").removeClass('d-none');
        }
    });

}

$('#quote_edit_btn').on('click', function () {
    var cvalue = parseInt($('#ganak').val()) + 1;
    var configured_tax = parseInt($('#configured_tax').val());
    var nxt = parseInt(cvalue);
    $('#ganak').val(nxt);
    var functionNum = "'" + cvalue + "'";
    count = $('#saman-row div').length;
    
    var data = '<tr><td></td></td><td><input type="text" class="form-control" name="product_name[]" placeholder="Search by Product Name or Item No." id="productname-' + cvalue + '" ></td><td><input type="number" class="form-control req amnt" name="product_qty[]" id="amount-' + cvalue + '" maxlength="6" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog(), compare_with_old_new_grand_totals()" autocomplete="off" value="0"  inputmode="numeric"><input type="hidden" id="alert-' + cvalue + '" value=""  name="alert[]"> </td><td class="text-center"><strong id="onhandQty-' + cvalue + '"></strong></td>';

    data += '<td class="text-right"><strong id="pricelabel-' + cvalue + '"></strong><input type="hidden" class="form-control" name="lowest_price[]" id="lowestprice-' + cvalue + '" onkeypress="return isNumber(event)" autocomplete="off"></td>';

    data += '<td class="text-right"><strong id="lowestpricelabel-' + cvalue + '"></strong><input type="hidden" class="form-control req prc" name="product_price[]" id="price-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" inputmode="numeric"></td>';

    if(configured_tax!=0){
        data += '<td class="text-center"> <input type="hidden" class="form-control vat" name="product_tax[]" id="vat-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" inputmode="numeric"><strong id="taxlabel-' + cvalue + '"></strong>&nbsp;<strong  id="texttaxa-' + cvalue + '"></strong></td> ';
    }
    else{
        var hiddendata = '<input type="text" class="form-control vat" name="product_tax[]" id="vat-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" inputmode="numeric">';
    }
    data+='<td class="text-center"><strong id="maxdiscountratelabel-' + cvalue + '"></strong></td>';
    data+='<td class="text-center"><div class="input-group text-center"><select name="discount_type[]" id="discounttype-' + cvalue + '" class="form-control" onchange="discounttypeChange(' + cvalue + ')"><option value="Perctype">%</option><option value="Amttype">Amt</option></select>&nbsp;<input type="number" min="0" class="form-control discount" name="product_discount[]" onkeypress="return isNumber(event)" id="discount-' + cvalue + '"  autocomplete="off" onkeyup="discounttypeChange(' + cvalue + ')"><input type="number" min="0" class="form-control discount d-none" name="product_amt[]" onkeypress="return isNumber(event)" id="discountamt-' + cvalue + '" autocomplete="off" onkeyup="discounttypeChange(' + cvalue + ')"></div><strong class="discount-amtlabel" id="discount-amtlabel-' + cvalue + '"></strong><div><strong id="discount-error-' + cvalue + '"></strong></div></td>';

    data += '<td  class="text-right"><strong><span class=\'ttlText\' id="result-' + cvalue + '">0</span></strong></td> <td class="text-center"> <button onclick="producthistory('+cvalue+')" type="button" class="btn btn-sm btn-secondary producthis"><i class="fa fa-history"></i></button> <button onclick="single_product_details('+cvalue+')" type="button" class="btn btn-sm btn-secondary"><i class="fa fa-info"></i></button> <button type="button" data-rowid="' + cvalue + '" class="btn btn-sm btn-secondary removeProd" title="Remove" > <i class="fa fa-trash"></i> </button></td>';

    data += '<input type="hidden" name="taxa[]" id="taxa-' + cvalue + '" value="0"><input type="hidden" name="disca[]" id="disca-' + cvalue + '" value="0"><input type="hidden" class="ttInput" name="product_subtotal[]" id="total-' + cvalue + '" value="0"> <input type="hidden" class="pdIn" name="pid[]" id="pid-' + cvalue + '" value="0"> <input type="hidden" name="unit[]" id="unit-' + cvalue + '" value=""> <input type="hidden" name="hsn[]" id="hsn-' + cvalue + '" value=""> <input type="hidden" name="serial[]" id="serial-' + cvalue + '" value="">'+hiddendata+'<input type="hidden" name="maxdiscountrate[]" id="maxdiscountrate-' + cvalue + '"></tr>';

    //ajax request
    // $('#saman-row').append(data);
    $('tr.last-item-row').before(data);
    row = cvalue;

    $('#productname-' + cvalue).autocomplete({
        
        source: function (request, response) {
            $.ajax({
                url: baseurl + 'search_products/' + billtype,
                dataType: "json",
                method: 'post',
                data: 'name_startsWith=' + request.term + '&type=product_list&row_num=' + row + '&wid=' + $("#s_warehouses option:selected").val() + '&' + d_csrf,
                success: function (data) {
                    response($.map(data, function (item) {
                        // var product_d = item[0];
                        var product_d = item[0]+" ("+item[7]+")";
                        return {
                            label: product_d,
                            value: product_d,
                            data: item
                        };
                    }));
                }
            });
        },
        autoFocus: true,
        minLength: 0,
        select: function (event, ui) {
            id_arr = $(this).attr('id');
            id = id_arr.split("-");
            var t_r = ui.item.data[3];
            if ($("#taxformat option:selected").attr('data-trate')) {

                t_r = $("#taxformat option:selected").attr('data-trate');
            }
            var discount = ui.item.data[4];
            var custom_discount = $('#custom_discount').val();
            if (custom_discount > 0) discount = deciFormat(custom_discount);
            $("#onhandQty-" + id[1]).text(parseInt(ui.item.data[8]));
            $("#prd-" + id[1]).val(ui.item.data[2]);
            $('#amount-' + id[1]).val(0);
            $('#price-' + id[1]).val(ui.item.data[1]);
            $('#pricelabel-' + id[1]).text(ui.item.data[1]);
            $('#pid-' + id[1]).val(ui.item.data[2]);
            $('#vat-' + id[1]).val(t_r);
            $('#taxlabel-' + id[1]).text(t_r+" / ");
            // $('#taxlabel-' + id[1]).val(t_r);
            $('#discount-' + id[1]).val(discount);
            $('#dpid-' + id[1]).val(ui.item.data[5]);
            $('#unit-' + id[1]).val(ui.item.data[6]);
            $('#hsn-' + id[1]).val(ui.item.data[7]);
            $('#alert-' + id[1]).val(parseInt(ui.item.data[8]));
            $('#serial-' + id[1]).val(ui.item.data[10]);     
            $('#lowestprice-' + id[1]).val(ui.item.data[11]);
            $('#lowestpricelabel-' + id[1]).text(ui.item.data[11]);        
            $('#maxdiscountrate-' + id[1]).val(ui.item.data[12]);
            $('#maxdiscountratelabel-'+ id[1]).text(ui.item.data[12]);
            rowTotal(cvalue);
            billUpyog();
            $(".noproduct-section").removeClass('d-none');


        },
        create: function (e) {
            $(this).prev('.ui-helper-hidden-accessible').remove();
        }
    });

});

$('#quote_draft_add_btn').on('click', function () {
    var cvalue = parseInt($('#ganak').val()) + 1;
    var configured_tax = parseInt($('#configured_tax').val());
    var nxt = parseInt(cvalue);
    $('#ganak').val(nxt);
    var functionNum = "'" + cvalue + "'";
    count = $('#saman-row div').length;
    
    var data = '<tr><td><input type="text" class="form-control" name="product_name[]" placeholder="Search by Product Name or Item No." id="productname-' + cvalue + '" ></td><td><input type="text" class="form-control" name="code[]" id="code-' + cvalue + '"  readonly></td><td><input type="number" class="form-control req amnt" name="product_qty[]" id="amount-' + cvalue + '" maxlength="6" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog(), compare_with_old_new_grand_totals()" autocomplete="off" value="0"  inputmode="numeric"><input type="hidden" id="alert-' + cvalue + '" value=""  name="alert[]"> </td><td class="text-center"><strong id="onhandQty-' + cvalue + '"></strong></td>';

    data += '<td class="text-right"><strong id="pricelabel-' + cvalue + '"></strong><input type="hidden" class="form-control" name="lowest_price[]" id="lowestprice-' + cvalue + '" onkeypress="return isNumber(event)" autocomplete="off"></td>';

    data += '<td class="text-right"><strong id="lowestpricelabel-' + cvalue + '"></strong><input type="hidden" class="form-control req prc" name="product_price[]" id="price-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" inputmode="numeric"></td>';

    if(configured_tax!=0){
        data += '<td class="text-center"> <input type="hidden" class="form-control vat" name="product_tax[]" id="vat-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" inputmode="numeric"><strong id="taxlabel-' + cvalue + '"></strong>&nbsp;<strong  id="texttaxa-' + cvalue + '"></strong></td> ';
    }
    else{
        var hiddendata = '<input type="text" class="form-control vat" name="product_tax[]" id="vat-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" inputmode="numeric">';
    }
    data+='<td class="text-center"><strong id="maxdiscountratelabel-' + cvalue + '"></strong></td>';
    data+='<td class="text-center"><div class="input-group text-center"><select name="discount_type[]" id="discounttype-' + cvalue + '" class="form-control" onchange="discounttypeChange(' + cvalue + ')"><option value="Perctype">%</option><option value="Amttype">Amt</option></select>&nbsp;<input type="number" min="0" class="form-control discount" name="product_discount[]" onkeypress="return isNumber(event)" id="discount-' + cvalue + '"  autocomplete="off" onkeyup="discounttypeChange(' + cvalue + ')"><input type="number" min="0" class="form-control discount d-none" name="product_amt[]" onkeypress="return isNumber(event)" id="discountamt-' + cvalue + '" autocomplete="off" onkeyup="discounttypeChange(' + cvalue + ')"></div><strong class="discount-amtlabel" id="discount-amtlabel-' + cvalue + '"></strong><div><strong id="discount-error-' + cvalue + '"></strong></div></td>';

    data += '<td  class="text-right"><strong><span class=\'ttlText\' id="result-' + cvalue + '">0</span></strong></td> <td class="text-center"> <button onclick="producthistory('+cvalue+')" type="button" class="btn btn-sm btn-secondary producthis"><i class="fa fa-history"></i></button> <button onclick="single_product_details('+cvalue+')" type="button" class="btn btn-sm btn-secondary"><i class="fa fa-info"></i></button> <button type="button" data-rowid="' + cvalue + '" class="btn btn-sm btn-secondary removeProd" title="Remove" > <i class="fa fa-trash"></i> </button></td>';

    data += '<input type="hidden" name="taxa[]" id="taxa-' + cvalue + '" value="0"><input type="hidden" name="disca[]" id="disca-' + cvalue + '" value="0"><input type="hidden" class="ttInput" name="product_subtotal[]" id="total-' + cvalue + '" value="0"> <input type="hidden" class="pdIn" name="pid[]" id="pid-' + cvalue + '" value="0"> <input type="hidden" name="unit[]" id="unit-' + cvalue + '" value=""> <input type="hidden" name="hsn[]" id="hsn-' + cvalue + '" value=""> <input type="hidden" name="serial[]" id="serial-' + cvalue + '" value="">'+hiddendata+'<input type="hidden" name="maxdiscountrate[]" id="maxdiscountrate-' + cvalue + '"></tr>';

    //ajax request
    // $('#saman-row').append(data);
    $('tr.last-item-row').before(data);
    row = cvalue;

    $('#productname-' + cvalue).autocomplete({
        
        source: function (request, response) {
            $.ajax({
                url: baseurl + 'search_products/' + billtype,
                dataType: "json",
                method: 'post',
                data: 'name_startsWith=' + request.term + '&type=product_list&row_num=' + row + '&wid=' + $("#s_warehouses option:selected").val() + '&' + d_csrf,
                success: function (data) {
                    response($.map(data, function (item) {
                        // var product_d = item[0];
                        var product_d = item[0]+" ("+item[7]+")";
                        return {
                            label: product_d,
                            value: item[0],
                            data: item
                        };
                    }));
                }
            });
        },
        autoFocus: true,
        minLength: 0,
        select: function (event, ui) {
            id_arr = $(this).attr('id');
            id = id_arr.split("-");
            var t_r = ui.item.data[3];
            if ($("#taxformat option:selected").attr('data-trate')) {

                t_r = $("#taxformat option:selected").attr('data-trate');
            }
            var discount = ui.item.data[4];
            var custom_discount = $('#custom_discount').val();
            if (custom_discount > 0) discount = deciFormat(custom_discount);
            $("#onhandQty-" + id[1]).text(parseInt(ui.item.data[8]));
            $("#prd-" + id[1]).val(ui.item.data[2]);
            $('#amount-' + id[1]).val(0);
            $('#price-' + id[1]).val(ui.item.data[1]);
            $('#pricelabel-' + id[1]).text(ui.item.data[1]);
            $('#pid-' + id[1]).val(ui.item.data[2]);
            $('#vat-' + id[1]).val(t_r);
            $('#taxlabel-' + id[1]).text(t_r+" / ");
            // $('#taxlabel-' + id[1]).val(t_r);
            $('#discount-' + id[1]).val(discount);
            $('#dpid-' + id[1]).val(ui.item.data[5]);
            $('#unit-' + id[1]).val(ui.item.data[6]);
            $('#hsn-' + id[1]).val(ui.item.data[7]);
            $('#alert-' + id[1]).val(parseInt(ui.item.data[8]));
            $('#serial-' + id[1]).val(ui.item.data[10]);     
            $('#lowestprice-' + id[1]).val(ui.item.data[11]);
            $('#lowestpricelabel-' + id[1]).text(ui.item.data[11]);        
            $('#maxdiscountrate-' + id[1]).val(ui.item.data[12]);
            $('#code-' + id[1]).val(ui.item.data[7]);
            $('#maxdiscountratelabel-'+ id[1]).text(ui.item.data[12]);
            rowTotal(cvalue);
            billUpyog();
            $(".noproduct-section").removeClass('d-none');


        },
        create: function (e) {
            $(this).prev('.ui-helper-hidden-accessible').remove();
        }
    });

});

// add class for datatable columns alignment
function addClassToColumns(td, columnIndex, classes) {
    if (classes && classes.length) {
        $(td).addClass(classes.join(' '));
    }
}

function completedstatus(salesorderid) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to complete the sales order?',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'No, cancel',
        confirmButtonText: 'Yes, complete it!',
        reverseButtons: true, // Display cancel button first
        focusCancel: true // Focus the cancel button by default
        
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: baseurl + 'SalesOrders/completed_salesorder',
                type: 'POST',
                data: {'salesorder_id': salesorderid},
                success: function(response) {
                    Swal.fire({
                        title: 'Success!',
                        text: 'The sales order has been completed successfully.',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        location.reload(); // Reload the current page
                    });
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'An error occurred while completing the sales order. Please try again.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }
    });
}


function single_product_stock(id){
    var product_id1 = $("#pid-" + id).val();
    var productname = $("#productname-" + id).val();
    $.ajax({
        url: baseurl + 'products/product_warehousewise_stock',
        dataType: "json",
        method: 'post',                
        data: {'product_id': product_id1},
        success: function (response) {
            // Create table content from data
            data = response.data;
            console.log(data);
            var fulldata="";
            
            if(data!="")
            {
                var tableContent = '<table class="table">';
                tableContent += '<thead><tr><th>Warehouse</th><th>Stock</th></tr></thead><tbody>';
                tableContent += data;
                tableContent += '</tbody></table>';
                fulldata = "<h4><b>"+productname+"</b></h4>"+tableContent;
            }
            else{
                fulldata = "<h5>No History Found..</h5>";
            }
            // Style the table content to add scroll overflow
            var styledTableContent = '<div style="max-height: 500px; overflow-y: auto;">' + fulldata + '</div>';

            // Display the table in a Swal.fire modal
            Swal.fire({
                text: productname,
                html: styledTableContent,
                width: '600px',
                focusConfirm: false,
                confirmButtonText: 'Close'
            });
        }
    });    

}
function single_product_stock_direct(id,name){
    var productname = name;
    $.ajax({
        url: baseurl + 'products/product_warehousewise_stock',
        dataType: "json",
        method: 'post',                
        data: {'product_id': id},
        success: function (response) {
            // Create table content from data
            data = response.data;
            console.log(data);
            var fulldata="";
            
            if(data!="")
            {
                var tableContent = '<table class="table">';
                tableContent += '<thead><tr><th>Warehouse</th><th>Stock</th></tr></thead><tbody>';
                tableContent += data;
                tableContent += '</tbody></table>';
                fulldata = "<h4><b>"+productname+"</b></h4>"+tableContent;
            }
            else{
                fulldata = "<h5>No History Found..</h5>";
            }
            // Style the table content to add scroll overflow
            var styledTableContent = '<div style="max-height: 500px; overflow-y: auto;">' + fulldata + '</div>';

            // Display the table in a Swal.fire modal
            Swal.fire({
                text: productname,
                html: styledTableContent,
                width: '600px',
                focusConfirm: false,
                confirmButtonText: 'Close'
            });
        }
    });    
    // erp2024 

    
}

//erp2024 02-10-2024
function checkCost(numb){
    productid = $("#pid-"+numb).val();
    entered_price = $("#price-"+numb).val();
    $.ajax({
        url: baseurl + 'products/product_cost',
        dataType: "json",
        method: 'post',                
        data: {'product_id': productid},
        success: function (response) {
            // Create table content from data
            getcost = response.data;
            if(parseFloat(entered_price) > parseFloat(getcost))
            {
                $("#costvaluation_section-"+numb).removeClass('d-none');
                $("#cost_warning_val-"+numb).text("Old Cost is less");
            }
            else{
                $("#costvaluation_section-"+numb).addClass('d-none');
            }
            
        }
    });  
}
   

// $('#addstockreturnproduct').on('click', function () {
//     var cvalue = parseInt($('#ganak').val()) + 1;
//     var nxt = parseInt(cvalue);
//     $('#ganak').val(nxt);
//     var functionNum = "'" + cvalue + "'";
//     count = $('#saman-row div').length;
//     var data = '<tr><td><input type="text" class="form-control" name="product_name[]" placeholder="Search by Product Name or Item No." id="productname-' + cvalue + '"></td><td><input type="number" class="form-control req amnt" name="product_qty[]" id="amount-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" value="1"  inputmode="numeric"><input type="hidden" id="alert-' + cvalue + '" value=""  name="alert[]"> </td><td><input type="text" class="form-control req prc" name="product_price[]" id="price-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" inputmode="numeric"></td>  <td class="text-right"><strong><span class=\'ttlText\' id="result-' + cvalue + '">0</span></strong></td> <td class="text-center"><button type="button" data-rowid="' + cvalue + '" class="btn btn-sm btn-default removeProd" title="Remove" > <i class="fa fa-trash"></i> </button> </td><input type="hidden" name="taxa[]" id="taxa-' + cvalue + '" value="0"><input type="hidden" name="disca[]" id="disca-' + cvalue + '" value="0"><input type="hidden" class="ttInput" name="product_subtotal[]" id="total-' + cvalue + '" value="0"> <input type="hidden" class="pdIn" name="pid[]" id="pid-' + cvalue + '" value="0"> <input type="hidden" name="unit[]" id="unit-' + cvalue + '" value=""> <input type="hidden" name="hsn[]" id="hsn-' + cvalue + '" value=""> <input type="hidden" name="serial[]" id="serial-' + cvalue + '" value=""> <input type="hidden" class="form-control vat" name="product_tax[]" id="vat-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" inputmode="numeric"><input type="text" class="form-control discount" name="product_discount[]" onkeypress="return isNumber(event)" id="discount-' + cvalue + '" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off"></tr>';

//     // var data = '<tr><td><input type="text" class="form-control" name="product_name[]" placeholder="Search by Product Name or Item No." id="productname-' + cvalue + '"></td><td><input type="number" class="form-control req amnt" name="product_qty[]" id="amount-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" value="1"  inputmode="numeric"><input type="hidden" id="alert-' + cvalue + '" value=""  name="alert[]"> </td><td><input type="text" class="form-control req prc" name="product_price[]" id="price-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" inputmode="numeric"></td><td> <input type="text" class="form-control vat" name="product_tax[]" id="vat-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" inputmode="numeric"></td> <td id="texttaxa-' + cvalue + '" class="text-center">0</td> <td><input type="text" class="form-control discount" name="product_discount[]" onkeypress="return isNumber(event)" id="discount-' + cvalue + '" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off"></td> <td><span class="currenty">' + currency + '</span> <strong><span class=\'ttlText\' id="result-' + cvalue + '">0</span></strong></td> <td class="text-center"><button type="button" data-rowid="' + cvalue + '" class="btn btn-sm btn-default removeProd" title="Remove" > <i class="fa fa-trash"></i> </button> </td><input type="hidden" name="taxa[]" id="taxa-' + cvalue + '" value="0"><input type="hidden" name="disca[]" id="disca-' + cvalue + '" value="0"><input type="hidden" class="ttInput" name="product_subtotal[]" id="total-' + cvalue + '" value="0"> <input type="hidden" class="pdIn" name="pid[]" id="pid-' + cvalue + '" value="0"> <input type="hidden" name="unit[]" id="unit-' + cvalue + '" value=""> <input type="hidden" name="hsn[]" id="hsn-' + cvalue + '" value=""> <input type="hidden" name="serial[]" id="serial-' + cvalue + '" value=""> </tr><tr><td colspan="9"><textarea class="form-control"  id="dpid-' + cvalue + '" name="product_description[]" placeholder="Enter Product description" autocomplete="off"></textarea></td></tr>';
//     //ajax request
//     // $('#saman-row').append(data);
//     $('tr.last-item-row').before(data);
//     row = cvalue;

//     $('#productname-' + cvalue).autocomplete({
        
//         source: function (request, response) {
//             $.ajax({
//                 url: baseurl + 'search_products/' + billtype,
//                 dataType: "json",
//                 method: 'post',
//                 data: 'name_startsWith=' + request.term + '&type=product_list&row_num=' + row + '&wid=' + $("#s_warehouses option:selected").val() + '&' + d_csrf,
//                 success: function (data) {
//                     response($.map(data, function (item) {
//                         // var product_d = item[0];
//                         var product_d = item[0]+" ("+item[7]+")";
//                         return {
//                             label: product_d,
//                             value: product_d,
//                             data: item
//                         };
//                     }));
//                 }
//             });
//         },
//         autoFocus: true,
//         minLength: 0,
//         select: function (event, ui) {
//             id_arr = $(this).attr('id');
//             id = id_arr.split("-");
//             var t_r = ui.item.data[3];
//             if ($("#taxformat option:selected").attr('data-trate')) {

//                 t_r = $("#taxformat option:selected").attr('data-trate');
//             }
//             var discount = ui.item.data[4];
//             var custom_discount = $('#custom_discount').val();
//             if (custom_discount > 0) discount = deciFormat(custom_discount);
//             $("#onhandQty-" + id[1]).text(parseInt(ui.item.data[8]));
//             $("#prd-" + id[1]).val(ui.item.data[2]);
//             $('#amount-' + id[1]).val(1);
//             $('#price-' + id[1]).val(ui.item.data[1]);
//             $('#pid-' + id[1]).val(ui.item.data[2]);
//             $('#vat-' + id[1]).val(t_r);
//             $('#discount-' + id[1]).val(discount);
//             $('#dpid-' + id[1]).val(ui.item.data[5]);
//             $('#unit-' + id[1]).val(ui.item.data[6]);
//             $('#hsn-' + id[1]).val(ui.item.data[7]);
//             $('#alert-' + id[1]).val(parseInt(ui.item.data[8]));
//             $('#serial-' + id[1]).val(ui.item.data[10]);
//             rowTotal(cvalue);
//             billUpyog();


//         },
//         create: function (e) {
//             $(this).prev('.ui-helper-hidden-accessible').remove();
//         }
//     });

// });


$('#addstockreturnproduct').on('click', function () {
    var cvalue = parseInt($('#ganak').val()) + 1;
    var nxt = parseInt(cvalue);
    $('#ganak').val(nxt);
    var functionNum = "'" + cvalue + "'";
    count = $('#saman-row div').length;
    var data = '<tr><td><input type="text" class="form-control productname" name="product_name[]" placeholder="Search by Product Name or Item No." id="productname-' + cvalue + '"></td><td><input type="text" class="form-control" name="code[]"  id="code-' + cvalue + '"></td><td><input type="number" class="form-control req amnt" name="product_qty[]" id="amount-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" value="1"  inputmode="numeric"><input type="hidden" id="alert-' + cvalue + '" value=""  name="alert[]"> </td><td><input type="text" class="form-control req prc" name="product_price[]" id="price-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" inputmode="numeric"></td>  <td class="text-right"><strong><span class=\'ttlText\' id="result-' + cvalue + '">0</span></strong></td> <td class="text-center"><button type="button" data-rowid="' + cvalue + '" class="btn btn-sm btn-default removeProd" title="Remove" > <i class="fa fa-trash"></i> </button> </td><input type="hidden" name="taxa[]" id="taxa-' + cvalue + '" value="0"><input type="hidden" name="disca[]" id="disca-' + cvalue + '" value="0"><input type="hidden" class="ttInput" name="product_subtotal[]" id="total-' + cvalue + '" value="0"> <input type="hidden" class="pdIn" name="pid[]" id="pid-' + cvalue + '" value="0"> <input type="hidden" name="unit[]" id="unit-' + cvalue + '" value=""> <input type="hidden" name="hsn[]" id="hsn-' + cvalue + '" value=""> <input type="hidden" name="serial[]" id="serial-' + cvalue + '" value=""> <input type="hidden" class="form-control vat" name="product_tax[]" id="vat-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" inputmode="numeric"><input type="text" class="form-control discount" name="product_discount[]" onkeypress="return isNumber(event)" id="discount-' + cvalue + '" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off"></tr>';

    // var data = '<tr><td><input type="text" class="form-control" name="product_name[]" placeholder="Search by Product Name or Item No." id="productname-' + cvalue + '"></td><td><input type="number" class="form-control req amnt" name="product_qty[]" id="amount-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" value="1"  inputmode="numeric"><input type="hidden" id="alert-' + cvalue + '" value=""  name="alert[]"> </td><td><input type="text" class="form-control req prc" name="product_price[]" id="price-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" inputmode="numeric"></td><td> <input type="text" class="form-control vat" name="product_tax[]" id="vat-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" inputmode="numeric"></td> <td id="texttaxa-' + cvalue + '" class="text-center">0</td> <td><input type="text" class="form-control discount" name="product_discount[]" onkeypress="return isNumber(event)" id="discount-' + cvalue + '" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off"></td> <td><span class="currenty">' + currency + '</span> <strong><span class=\'ttlText\' id="result-' + cvalue + '">0</span></strong></td> <td class="text-center"><button type="button" data-rowid="' + cvalue + '" class="btn btn-sm btn-default removeProd" title="Remove" > <i class="fa fa-trash"></i> </button> </td><input type="hidden" name="taxa[]" id="taxa-' + cvalue + '" value="0"><input type="hidden" name="disca[]" id="disca-' + cvalue + '" value="0"><input type="hidden" class="ttInput" name="product_subtotal[]" id="total-' + cvalue + '" value="0"> <input type="hidden" class="pdIn" name="pid[]" id="pid-' + cvalue + '" value="0"> <input type="hidden" name="unit[]" id="unit-' + cvalue + '" value=""> <input type="hidden" name="hsn[]" id="hsn-' + cvalue + '" value=""> <input type="hidden" name="serial[]" id="serial-' + cvalue + '" value=""> </tr><tr><td colspan="9"><textarea class="form-control"  id="dpid-' + cvalue + '" name="product_description[]" placeholder="Enter Product description" autocomplete="off"></textarea></td></tr>';
    //ajax request
    // $('#saman-row').append(data);
    $('tr.last-item-row').before(data);
    row = cvalue;

    $('#productname-' + cvalue).autocomplete({
        
        source: function (request, response) {
            $.ajax({
                url: baseurl + 'search_products/' + billtype,
                dataType: "json",
                method: 'post',
                data: 'name_startsWith=' + request.term + '&type=product_list&row_num=' + row + '&wid=' + $("#s_warehouses option:selected").val() + '&' + d_csrf,
                success: function (data) {
                    response($.map(data, function (item) {
                        // var product_d = item[0];
                        var product_d = item[0]+" ("+item[7]+")";
                        return {
                            label: product_d,
                            value: item[0],
                            data: item
                        };
                    }));
                }
            });
        },
        autoFocus: true,
        minLength: 0,
        select: function (event, ui) {
            id_arr = $(this).attr('id');
            id = id_arr.split("-");
            var t_r = ui.item.data[3];
            if ($("#taxformat option:selected").attr('data-trate')) {

                t_r = $("#taxformat option:selected").attr('data-trate');
            }
            var discount = ui.item.data[4];
            var custom_discount = $('#custom_discount').val();
            if (custom_discount > 0) discount = deciFormat(custom_discount);
            $("#onhandQty-" + id[1]).text(parseInt(ui.item.data[8]));
            $("#prd-" + id[1]).val(ui.item.data[2]);
            $('#amount-' + id[1]).val(1);
            $('#price-' + id[1]).val(ui.item.data[1]);
            $('#pid-' + id[1]).val(ui.item.data[2]);
            $('#vat-' + id[1]).val(t_r);
            $('#discount-' + id[1]).val(discount);
            $('#dpid-' + id[1]).val(ui.item.data[5]);
            $('#unit-' + id[1]).val(ui.item.data[6]);
            $('#hsn-' + id[1]).val(ui.item.data[7]);
            $('#alert-' + id[1]).val(parseInt(ui.item.data[8]));
            $('#serial-' + id[1]).val(ui.item.data[10]);
            $('#code-' + id[1]).val(ui.item.data[7]);
            rowTotal(cvalue);
            billUpyog();

        },
        create: function (e) {
            $(this).prev('.ui-helper-hidden-accessible').remove();
        }
    });

});


$('#stockreturnprdname-0').autocomplete({
    
    source: function (request, response) {
        $.ajax({
            url: baseurl + 'search_products/' + billtype,
            dataType: "json",
            method: 'post',
            data: 'name_startsWith=' + request.term + '&type=product_list&row_num=1&wid=' + $("#s_warehouses option:selected").val() + '&' + d_csrf,
            success: function (data) {               
                response($.map(data, function (item) {
                    var product_d = item[0]+" ("+item[7]+")";
                    return {
                        label: product_d,
                        value: item[0],
                        data: item
                    };
                }));
            }
        });
    },
    autoFocus: true,
    minLength: 0,
    select: function (event, ui) {
        var t_r = ui.item.data[3];
        
        if ($("#taxformat option:selected").attr('data-trate')) {

            t_r = $("#taxformat option:selected").attr('data-trate');
        }
        var discount = ui.item.data[4];
        var custom_discount = $('#custom_discount').val();
        if (custom_discount > 0) discount = deciFormat(custom_discount);
        $("#onhandQty").text(parseInt(ui.item.data[8]));
        $('#amount-0').val(1);
        $('#price-0').val(ui.item.data[1]);        
        $('#pricelabel-0').text(ui.item.data[1]);
        $('#pid-0').val(ui.item.data[2]);
        $('#vat-0').val(t_r);
        $('#discount-0').val(discount);
        $('#dpid-0').val(ui.item.data[5]);
        $('#unit-0').val(ui.item.data[6]);
        $('#hsn-0').val(ui.item.data[7]);
        $('#alert-0').val(parseInt(ui.item.data[8]));
        $('#serial-0').val(ui.item.data[10]);
        $("#lowestprice-0").val(ui.item.data[11]);
        $("#lowestpricelabel-0").text(ui.item.data[11]);
        $("#maxdiscountrate-0").val(ui.item.data[12]);
        $("#maxdiscountratelabel-0").text(ui.item.data[12]); 
        $('#taxlabel-0').text(t_r+" / ");
        $('#code-0').val(ui.item.data[7]);
        rowTotal(0);
        billUpyog();
    }
});

// erp2024 filter search expand 10-10-2024
$(document).on('click', '.expand-btn', function(e) {    
    e.preventDefault();
    var $this = $(this);
    var target = $this.data('target');                
    $(target).slideToggle();
    if ($this.find('i').hasClass('fa-angle-down')) {
        $this.find('i').removeClass('fa-angle-down').addClass('fa-angle-up');
    } else {
        $this.find('i').removeClass('fa-angle-up').addClass('fa-angle-down');
    }            
});

$(".filter_clear_btn").on('click', function(){
    $(".filter_element").val("");
    $('.filter_select option:selected').remove();
    $('.filter_select_normal').val("");
    $("#filter_search_btn").click();
});

$(document).on('click', '.header-expand-btn', function(e) {    
    e.preventDefault();
    var $this = $(this);
    var target = $this.data('target');                
    $(target).slideToggle();
    if ($this.find('i').hasClass('fa-angle-down')) {
        $this.find('i').removeClass('fa-angle-down').addClass('fa-angle-up');
    } else {
        $this.find('i').removeClass('fa-angle-up').addClass('fa-angle-down');
    }            
});
// erp2024 filter search expand 10-10-2024


function product_edit_autocomplete(val) {
    var cvalue = val;
    var configured_tax = parseInt($('#configured_tax').val());
    var nxt = parseInt(cvalue);
 
    row = cvalue;
    $('#productname-'+cvalue).autocomplete({
    
        source: function (request, response) {
            $.ajax({
                url: baseurl + 'search_products/' + billtype,
                dataType: "json",
                method: 'post',
                data: 'name_startsWith=' + request.term + '&type=product_list&row_num=1&wid=' + $("#s_warehouses option:selected").val() + '&' + d_csrf,
                success: function (data) {               
                    response($.map(data, function (item) {
                        // var product_d = item[0]+" ("+item[7]+")";
                        var product_d = item[0];
                        return {
                            label: item[0]+" ("+item[7]+")",
                            value: product_d,
                            data: item
                        };
                    }));
                }
            });
        },
        autoFocus: true,
        minLength: 0,
        select: function (event, ui) {
            var t_r = ui.item.data[3];
            if ($("#taxformat option:selected").attr('data-trate')) {
    
                t_r = $("#taxformat option:selected").attr('data-trate');
            }
            var discount = ui.item.data[4];
            var custom_discount = $('#custom_discount').val();
            if (custom_discount > 0) discount = deciFormat(custom_discount);
            $("#onhandQty").text(parseInt(ui.item.data[8]));        
            $('#amount-'+cvalue).val(1);
            $('#price-'+cvalue).val(ui.item.data[1]);
            $('#pricelabel-'+cvalue).text(ui.item.data[1]);
            $('#pid-'+cvalue).val(ui.item.data[2]);
            $('#vat-'+cvalue).val(t_r);
            $('#taxlabel-'+cvalue).text(t_r+" / ");
            $('#discount-'+cvalue).val(discount);
            $('#dpid-'+cvalue).val(ui.item.data[5]);
            $('#unit-'+cvalue).val(ui.item.data[6]);
            $('#hsn-'+cvalue).val(ui.item.data[7]);
            $('#alert-'+cvalue).val(parseInt(ui.item.data[8]));
            $('#serial-'+cvalue).val(ui.item.data[10]);        
            $("#lowestprice-0").val(ui.item.data[11]);
            $("#lowestpricelabel-0").text(ui.item.data[11]);
            $("#maxdiscountrate-0").val(ui.item.data[12]);
            $("#maxdiscountratelabel-0").text(ui.item.data[12]);
            $('#code-'+cvalue).val(ui.item.data[7]);
            rowTotal(0);
            billUpyog();
            $(".noproduct-section").removeClass('d-none');
        }
    });

    // erp2024 new functoin for both search
    
    $('#salesorder_create_btn').on('click', function () {
        var cvalue = parseInt($('#ganak').val()) + 1;
        var configured_tax = parseInt($('#configured_tax').val());
        var nxt = parseInt(cvalue);
        $('#ganak').val(nxt);
        var functionNum = "'" + cvalue + "'";
        count = $('#saman-row div').length;
        var data = '<tr><td><input type="text" class="form-control" name="product_name[]" placeholder="Search by Product Name or Item No." id="productname-' + cvalue + '"></td><td><input type="text" class="form-control" name="code[]"  id="code-' + cvalue + '" readonly></td><td><input type="number" class="form-control req amnt" name="product_qty[]" id="amount-' + cvalue + '" maxlength="6" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" value="1"  inputmode="numeric"><input type="hidden" id="alert-' + cvalue + '" value=""  name="alert[]"> </td><td class="text-center"><strong id="onhandQty-' + cvalue + '"></strong></td>';

        data += '<td class="text-right"><strong id="pricelabel-' + cvalue + '"></strong><input type="hidden" class="form-control" name="lowest_price[]" id="lowestprice-' + cvalue + '" onkeypress="return isNumber(event)" autocomplete="off"></td>';

        data += '<td class="text-right"><strong id="lowestpricelabel-' + cvalue + '"></strong><input type="hidden" class="form-control req prc" name="product_price[]" id="price-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" inputmode="numeric"></td>';

        if(configured_tax!=0){
            data += '<td class="text-center"> <input type="hidden" class="form-control vat" name="product_tax[]" id="vat-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" inputmode="numeric"><strong id="taxlabel-' + cvalue + '"></strong>&nbsp;<strong  id="texttaxa-' + cvalue + '"></strong></td> ';
        }
        else{
            var hiddendata = '<input type="text" class="form-control vat" name="product_tax[]" id="vat-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" inputmode="numeric">';
        }
        data+='<td class="text-center"><strong id="maxdiscountratelabel-' + cvalue + '"></strong></td>';
        
        data+='<td class="text-center"><div class="input-group text-center"><select name="discount_type[]" id="discounttype-' + cvalue + '" class="form-control" onchange="discounttypeChange(' + cvalue + ')"><option value="Perctype">%</option><option value="Amttype">Amt</option></select>&nbsp;<input type="number" min="0" class="form-control discount" name="product_discount[]" onkeypress="return isNumber(event)" id="discount-' + cvalue + '"  autocomplete="off" onkeyup="discounttypeChange(' + cvalue + ')"><input type="number" min="0" class="form-control discount d-none" name="product_amt[]" onkeypress="return isNumber(event)" id="discountamt-' + cvalue + '" autocomplete="off" onkeyup="discounttypeChange(' + cvalue + ')"></div><strong class="discount-amtlabel" id="discount-amtlabel-' + cvalue + '"></strong><div><strong id="discount-error-' + cvalue + '"></strong></div></td>';

        data += '<td  class="text-right"><strong><span class=\'ttlText\' id="result-' + cvalue + '">0</span></strong></td> <td class="text-center"> <button onclick="producthistory('+cvalue+')" type="button" class="btn btn-sm btn-secondary producthis"><i class="fa fa-history"></i></button> <button onclick="single_product_details('+cvalue+')" type="button" class="btn btn-sm btn-secondary"><i class="fa fa-info"></i></button> <button type="button" data-rowid="' + cvalue + '" class="btn btn-sm btn-secondary removeProd" title="Remove" > <i class="fa fa-trash"></i> </button></td>';

        data += '<input type="hidden" name="taxa[]" id="taxa-' + cvalue + '" value="0"><input type="hidden" name="disca[]" id="disca-' + cvalue + '" value="0"><input type="hidden" class="ttInput" name="product_subtotal[]" id="total-' + cvalue + '" value="0"> <input type="hidden" class="pdIn" name="pid[]" id="pid-' + cvalue + '" value="0"> <input type="hidden" name="unit[]" id="unit-' + cvalue + '" value=""> <input type="hidden" name="hsn[]" id="hsn-' + cvalue + '" value=""> <input type="hidden" name="serial[]" id="serial-' + cvalue + '" value="">'+hiddendata+'<input type="hidden" name="maxdiscountrate[]" id="maxdiscountrate-' + cvalue + '"></tr>';

        //ajax request
        // $('#saman-row').append(data);
        $('tr.last-item-row').before(data);
        row = cvalue;

        $('#productname-' + cvalue).autocomplete({
            
            source: function (request, response) {
                $.ajax({
                    url: baseurl + 'search_products/' + billtype,
                    dataType: "json",
                    method: 'post',
                    data: 'name_startsWith=' + request.term + '&type=product_list&row_num=' + row + '&wid=' + $("#s_warehouses option:selected").val() + '&' + d_csrf,
                    success: function (data) {
                        response($.map(data, function (item) {
                            // var product_d = item[0];
                            var product_d = item[0]+" ("+item[7]+")";
                            return {
                                label: product_d,
                                value: item[0],
                                data: item
                            };
                        }));
                    }
                });
            },
            autoFocus: true,
            minLength: 0,
            select: function (event, ui) {
                id_arr = $(this).attr('id');
                id = id_arr.split("-");
                var t_r = ui.item.data[3];
                if ($("#taxformat option:selected").attr('data-trate')) {

                    t_r = $("#taxformat option:selected").attr('data-trate');
                }
                var discount = ui.item.data[4];
                var custom_discount = $('#custom_discount').val();
                if (custom_discount > 0) discount = deciFormat(custom_discount);
                $("#onhandQty-" + id[1]).text(parseInt(ui.item.data[8]));
                $("#prd-" + id[1]).val(ui.item.data[2]);
                $('#amount-' + id[1]).val(1);
                $('#price-' + id[1]).val(ui.item.data[1]);
                $('#pricelabel-' + id[1]).text(ui.item.data[1]);
                $('#pid-' + id[1]).val(ui.item.data[2]);
                $('#vat-' + id[1]).val(t_r);
                $('#taxlabel-' + id[1]).text(t_r+" / ");
                // $('#taxlabel-' + id[1]).val(t_r);discount-amtlabel
                $('#discount-' + id[1]).val(discount);
                $('#dpid-' + id[1]).val(ui.item.data[5]);
                $('#unit-' + id[1]).val(ui.item.data[6]);
                $('#hsn-' + id[1]).val(ui.item.data[7]);
                $('#alert-' + id[1]).val(parseInt(ui.item.data[8]));
                $('#serial-' + id[1]).val(ui.item.data[10]);     
                $('#lowestprice-' + id[1]).val(ui.item.data[11]);
                $('#lowestpricelabel-' + id[1]).text(ui.item.data[11]);        
                $('#maxdiscountrate-' + id[1]).val(ui.item.data[12]);
                $('#maxdiscountratelabel-'+ id[1]).text(ui.item.data[12]);
                $('#code-'+ id[1]).val(ui.item.data[7]);
                rowTotal(cvalue);
                billUpyog();
                $(".noproduct-section").removeClass('d-none');


            },
            create: function (e) {
                $(this).prev('.ui-helper-hidden-accessible').remove();
            }
        });

        $('#code-' + cvalue).autocomplete({
            
            source: function (request, response) {
                $.ajax({
                    url: baseurl + 'search_products/' + billtype,
                    dataType: "json",
                    method: 'post',
                    data: 'name_startsWith=' + request.term + '&type=product_list&row_num=' + row + '&wid=' + $("#s_warehouses option:selected").val() + '&' + d_csrf,
                    success: function (data) {
                        response($.map(data, function (item) {
                            // var product_d = item[0];
                            var product_d = item[0]+" ("+item[7]+")";
                            return {
                                label: product_d,
                                value: item[7],
                                data: item
                            };
                        }));
                    }
                });
            },
            autoFocus: true,
            minLength: 0,
            select: function (event, ui) {
                id_arr = $(this).attr('id');
                id = id_arr.split("-");
                var t_r = ui.item.data[3];
                if ($("#taxformat option:selected").attr('data-trate')) {

                    t_r = $("#taxformat option:selected").attr('data-trate');
                }
                var discount = ui.item.data[4];
                var custom_discount = $('#custom_discount').val();
                if (custom_discount > 0) discount = deciFormat(custom_discount);
                $("#onhandQty-" + id[1]).text(parseInt(ui.item.data[8]));
                $("#prd-" + id[1]).val(ui.item.data[2]);
                $('#amount-' + id[1]).val(1);
                $('#price-' + id[1]).val(ui.item.data[1]);
                $('#pricelabel-' + id[1]).text(ui.item.data[1]);
                $('#pid-' + id[1]).val(ui.item.data[2]);
                $('#vat-' + id[1]).val(t_r);
                $('#taxlabel-' + id[1]).text(t_r+" / ");
                // $('#taxlabel-' + id[1]).val(t_r);discount-amtlabel
                $('#discount-' + id[1]).val(discount);
                $('#dpid-' + id[1]).val(ui.item.data[5]);
                $('#unit-' + id[1]).val(ui.item.data[6]);
                $('#hsn-' + id[1]).val(ui.item.data[7]);
                $('#alert-' + id[1]).val(parseInt(ui.item.data[8]));
                $('#serial-' + id[1]).val(ui.item.data[10]);     
                $('#lowestprice-' + id[1]).val(ui.item.data[11]);
                $('#lowestpricelabel-' + id[1]).text(ui.item.data[11]);        
                $('#maxdiscountrate-' + id[1]).val(ui.item.data[12]);
                $('#maxdiscountratelabel-'+ id[1]).text(ui.item.data[12]);
                $('#productname-'+ id[1]).val(ui.item.data[0]);
                rowTotal(cvalue);
                billUpyog();
                $(".noproduct-section").removeClass('d-none');


            },
            create: function (e) {
                $(this).prev('.ui-helper-hidden-accessible').remove();
            }
        });

    });



}