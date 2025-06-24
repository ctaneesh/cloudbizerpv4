var billtype = $('#billtype').val();
var d_csrf = crsf_token + '=' + crsf_hash;
$('#addproduct').on('click', function () {
    var cvalue = parseInt($('#ganak').val()) + 1;
    var nxt = parseInt(cvalue);
    $('#ganak').val(nxt);
    var functionNum = "'" + cvalue + "'";
    count = $('#saman-row div').length;
    var data = '<tr><td><input type="text" class="form-control" name="product_name[]" placeholder="Search by Product Name or Item No." id="productname-' + cvalue + '"></td><td><input type="number" class="form-control req amnt" name="product_qty[]" id="amount-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" value="1"  inputmode="numeric"><input type="hidden" id="alert-' + cvalue + '" value=""  name="alert[]"> </td><td><input type="text" class="form-control req prc" name="product_price[]" id="price-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" inputmode="numeric"></td><td> <input type="text" class="form-control vat" name="product_tax[]" id="vat-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" inputmode="numeric"></td> <td id="texttaxa-' + cvalue + '" class="text-center">0</td> <td><input type="text" class="form-control discount" name="product_discount[]" onkeypress="return isNumber(event)" id="discount-' + cvalue + '" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off"></td> <td><span class="currenty">' + currency + '</span> <strong><span class=\'ttlText\' id="result-' + cvalue + '">0</span></strong></td> <td class="text-center"><button type="button" data-rowid="' + cvalue + '" class="btn btn-sm btn-default removeProd" title="Remove" > <i class="fa fa-trash"></i> </button> </td><input type="hidden" name="taxa[]" id="taxa-' + cvalue + '" value="0"><input type="hidden" name="disca[]" id="disca-' + cvalue + '" value="0"><input type="hidden" class="ttInput" name="product_subtotal[]" id="total-' + cvalue + '" value="0"> <input type="hidden" class="pdIn" name="pid[]" id="pid-' + cvalue + '" value="0"> <input type="hidden" name="unit[]" id="unit-' + cvalue + '" value=""> <input type="hidden" name="hsn[]" id="hsn-' + cvalue + '" value=""> <input type="hidden" name="serial[]" id="serial-' + cvalue + '" value=""> </tr>';
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
    var lastSerialNumber = parseInt($('#saman-row .serial-number:last').text()) || 0;    
    var nextSerialNumber = lastSerialNumber+1;
    //erp2024 hide on 23-03-2024
    // var costdata = '<tr><td>'+rowno+'<td><input type="text" class="form-control" name="expense_name[]"  placeholder="Expense Name" id="expense_name-'+cvalue+'" onkeyup="save_changed_values_for_history()" title="Expense"></td><td><input type="number" name="expense_id[]" id="expense_id-'+cvalue+'" class="form-control"  style="width:100px;" readonly></td><td><input type="text" name="payable_acc[]" id="payable_acc-'+cvalue+'" title="Account" class="form-control" style="width:250px;" placeholder="Account Name or Number"></td><td><input type="number" name="payable_acc_no[]"  id="payable_acc_no-'+cvalue+'" class="form-control" style="width:150px;" readonly onkeyup="save_changed_values_for_history()"></td><td><input type="text" name="bill_number_cost[]" id="bill_number_cost-'+cvalue+'" title="Bill Number" value="'+bill_number+'" class="form-control billnumber" style="width:150px;"></td>  <td><input type="date" name="bill_date_cost[]" id="bill_date_cost-'+cvalue+'" value="'+bill_date+'"  class="form-control billdate" style="width:150px;" onkeyup="save_changed_values_for_history()" title="Bill Date"></td><td><input type="number" name="costing_amount[]" id="costing_amount-'+cvalue+'" class="form-control text-right" style="width:100px;" onkeyup="costingamount('+cvalue+'), save_changed_values_for_history()" title="Expense Amount"></td><td><input type="text" name="currency_cost[]" id="currency_cost-'+cvalue+'" class="form-control" value="'+currencycode+'" style="width:150px;" readonly></td> <td><input type="number" name="currency_rate_cost[]" id="currency_rate_cost-'+cvalue+'" value="'+currencyrate+'"  class="form-control text-right" style="width:150px;" readonly></td><td><input type="number" name="costing_amount_net[]" id="costing_amount_net-'+cvalue+'" class="form-control text-right" style="width:100px;" readonly><td><input type="number" name="costing_amount_qar[]" id="costing_amount_qar-'+cvalue+'" class="form-control text-right" style="width:100px;" readonly></td><td><textarea name="remarks[]" id="remarks-'+cvalue+'" class="form-control" style="width:250px;" onkeyup="save_changed_values_for_history()" title="Remarks"></textarea></td><td><button type="button" data-rowid="' + cvalue + '" class="btn btn-sm btn-default removeProd" title="Remove" > <i class="fa fa-trash"></i> </button></td></tr>';

     var costdata = '<tr><td class="text-center serial-number">'+nextSerialNumber+'<td><input type="text" class="form-control" name="expense_name[]"  placeholder="Expense Name" id="expense_name-'+cvalue+'" onkeyup="save_changed_values_for_history()" title="Expense"></td><td class="d-none"><input type="number" name="expense_id[]" id="expense_id-'+cvalue+'" class="form-control" readonly></td><td><input type="text" name="payable_acc[]" id="payable_acc-'+cvalue+'" title="Account" class="form-control" style="width:230px;" placeholder="Account Name or Number"></td><td><input type="number" name="payable_acc_no[]"  id="payable_acc_no-'+cvalue+'" class="form-control" readonly onkeyup="save_changed_values_for_history()"></td><td><input type="text" name="bill_number_cost[]" id="bill_number_cost-'+cvalue+'" title="Bill Number" value="'+bill_number+'" class="form-control billnumber" style="width:150px;"></td>  <td><input type="date" name="bill_date_cost[]" id="bill_date_cost-'+cvalue+'" value="'+bill_date+'"  class="form-control billdate" style="width:150px;" onkeyup="save_changed_values_for_history()" title="Bill Date"></td><td><input type="number" name="costing_amount[]" id="costing_amount-'+cvalue+'" class="form-control text-right" style="width:100px;" onkeyup="costingamount('+cvalue+'), save_changed_values_for_history()" title="Expense Amount"></td><td class="d-none"><input type="text" name="currency_cost[]" id="currency_cost-'+cvalue+'" class="form-control" value="'+currencycode+'" style="width:150px;" readonly></td> <td class="d-none"><input type="number" name="currency_rate_cost[]" id="currency_rate_cost-'+cvalue+'" value="'+currencyrate+'"  class="form-control text-right" style="width:150px;" readonly></td><td class="d-none"><input type="number" name="costing_amount_net[]" id="costing_amount_net-'+cvalue+'" class="form-control text-right" style="width:100px;" readonly><td class="d-none"><input type="number" name="costing_amount_qar[]" id="costing_amount_qar-'+cvalue+'" class="form-control text-right" style="width:100px;" readonly></td><td><textarea name="remarks[]" id="remarks-'+cvalue+'" class="form-control" style="width:250px;" onkeyup="save_changed_values_for_history()" title="Remarks"></textarea></td><td><button type="button" data-rowid="' + cvalue + '" class="btn btn-sm btn-default removeProd" title="Remove" > <i class="fa fa-trash"></i> </button></td></tr>';
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
        if(cvalue)
        {
            accountdynamicsearch(cvalue);
        }
        
    });




    
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

    //hide product history button
    $(".producthis").hide();

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

    
    //hide product history button
    $(".producthis").hide();

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
        //check duplicate entry starts
        var newProductID = ui.item.data[7]; // New selected product name
        var duplicateFound = false;
        var totalRows = parseInt($('#ganak').val()); // total number of rows
        for (var i = 1; i <= totalRows; i++) {
            if ($('#purchasecode-' + i).length) { // Check if this field exists
                var existingProductName = $('#purchasecode-' + i).val();
                if (existingProductName == newProductID) {
                    duplicateFound = true;                    
                    break;
                }
            }
        }
        if (duplicateFound) {           
            duplicate_message('0',newProductID);                     
            return false;
        }
        //check duplicate entry ends
        if ($("#taxformat option:selected").attr('data-trate')) {

            t_r = $("#taxformat option:selected").attr('data-trate');
        }
        var discount = ui.item.data[4];
        var custom_discount = $('#custom_discount').val();
        if (custom_discount > 0) discount = deciFormat(custom_discount);
        $("#onhandQty-0").text(parseInt(ui.item.data[8]));
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
        $('#code-0').val(ui.item.data[7]);
        $('#alert-0').val(parseInt(ui.item.data[8]));
        // $('#serial-0').val(ui.item.data[10]);
        // $("#lowestprice-0").val(ui.item.data[11]);
        // $("#lowestpricelabel-0").text(ui.item.data[11]);
        // $("#maxdiscountrate-0").val(ui.item.data[12]);
        // $("#maxdiscountratelabel-0").text(ui.item.data[12]); 
        $('#taxlabel-0').text(t_r+" / ");
        $('#income_account_number-0').val(ui.item.data[9]);
        $('#expense_account_number-0').val(ui.item.data[10]);        
        $('#last_purchase_price_label-0').text(ui.item.data[11]);
        var fulltitle = ui.item.data[0]+" ("+ui.item.data[7]+")";
        $('#purchasecode-0').attr('title',  fulltitle+' - (New)');
        $('#purchasecode-0').val(ui.item.data[7]);
        $('#purchaseproduct-0').attr('title',  fulltitle+' - Product Name(New)');
        $('#amount-0').attr('title',  fulltitle+' - Quantity(New)');
        $('#price-0').attr('title',  fulltitle+' - Price(New)');
        $('#discount-0').attr('title',  fulltitle+' - Discount(New)');
        rowTotal(0);
        billUpyog();
        save_changed_values_for_history();
    }
});
$('#purchasecode-0').autocomplete({
    
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

        //check duplicate entry starts
        var newProductID = ui.item.data[7]; // New selected product name
        var duplicateFound = false;
        var totalRows = parseInt($('#ganak').val()); // total number of rows
        for (var i = 1; i <= totalRows; i++) {
            if ($('#purchasecode-' + i).length) { // Check if this field exists
                var existingProductName = $('#purchasecode-' + i).val();
                if (existingProductName == newProductID) {
                    duplicateFound = true;                    
                    break;
                }
            }
        }
        if (duplicateFound) {
            duplicate_message('0',newProductID);
            return false;
        }
        var t_r = ui.item.data[3];
        
        if ($("#taxformat option:selected").attr('data-trate')) {

            t_r = $("#taxformat option:selected").attr('data-trate');
        }
        var discount = ui.item.data[4];
        var custom_discount = $('#custom_discount').val();
        if (custom_discount > 0) discount = deciFormat(custom_discount);
        $("#onhandQty-0").text(parseInt(ui.item.data[8]));
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
        $('#purchaseproduct-0').val(ui.item.data[0]);
        $('#alert-0').val(parseInt(ui.item.data[8]));
        // $('#serial-0').val(ui.item.data[10]);
        // $("#lowestprice-0").val(ui.item.data[11]);
        // $("#lowestpricelabel-0").text(ui.item.data[11]);
        // $("#maxdiscountrate-0").val(ui.item.data[12]);
        // $("#maxdiscountratelabel-0").text(ui.item.data[12]); 
        $('#taxlabel-0').text(t_r+" / ");
        $('#income_account_number-0').val(ui.item.data[9]);
        $('#expense_account_number-0').val(ui.item.data[10]);
        $('#last_purchase_price_label-0').text(ui.item.data[11]);

        var fulltitle = ui.item.data[0]+" ("+ui.item.data[7]+")";
        $('#purchasecode-0').attr('title',  fulltitle+' - (New)');
        $('#purchaseproduct-0').attr('title',  fulltitle+' - Product Name(New)');
        $('#amount-0').attr('title',  fulltitle+' - Quantity(New)');
        $('#price-0').attr('title',  fulltitle+' - Price(New)');
        $('#discount-0').attr('title',  fulltitle+' - Discount(New)');
        rowTotal(0);
        billUpyog();
        save_changed_values_for_history();
    }
});



$('#addpurchaseproduct').on('click', function () {
    var cvalue = parseInt($('#ganak').val()) + 1;
    var nxt = parseInt(cvalue);
    $('#ganak').val(nxt); 
    var functionNum = "'" + cvalue + "'";
    count = $('#saman-row div').length; 
    var lastSerialNumber = parseInt($('#saman-row .serial-number:last').text()) || 0;    
    var nextSerialNumber = lastSerialNumber+1;
    var data = '<tr><td class="text-center serial-number">'+nextSerialNumber+'</td><td><input type="text" placeholder="Search by Item No." class="form-control" name="code[]" id="purchasecode-' + cvalue + '" value=""><input type="hidden" class="form-control" name="expense_account_number[]" id="expense_account_number-' + cvalue + '"  readonly><input type="hidden" class="form-control" name="hsn[]" id="hsn-' + cvalue + '" value="" readonly></td><td><span class="d-flex"><input type="text" class="form-control" id="productname-' + cvalue + '" name="product_name[]" placeholder="Search by Product Name" id="productname-' + cvalue + '">';
    
    data += '&nbsp;<button type="button" title="change account" class="btn btn-sm btn-secondary" id="btnclk-' + cvalue + '" data-toggle="popover" onclick="loadPopover(' + cvalue +')" data-html="true" data-content=\'<form id="popoverForm-' + cvalue + '"><div class="form-group"><label for="accountList-' + cvalue + '">Select Account</label><select class="form-control" id="accountList-' + cvalue + '"></select></div><div class="text-right"><button type="button" onclick="cancelPopover(' + cvalue + ')" class="btn btn-secondary btn-sm">Cancel</button>&nbsp;<button type="button" onclick="change_product_account(' + cvalue + ')" class="btn btn-primary btn-sm">Change</button></div></form>\'><i class="fa fa-bank"></i></span></button></td>';


     data += '</td><td><input type="number" class="form-control req amnt text-right responsive-width-elements" name="product_qty[]" id="amount-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog(), save_changed_values_for_history(),discountWithTotal(' + functionNum + ')" autocomplete="off" value="0"  inputmode="numeric" data-original-value="0"><input type="hidden" id="alert-' + cvalue + '" value=""  name="alert[]"> </td>';
     data += '<td class="text-right"><span id="last_purchase_price_label-' + cvalue + '"></span></td>';
     data += '<td><input type="text" class="form-control  text-right req prc responsive-width-elements" name="product_price[]" id="price-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="checkCost(' + functionNum + '), rowTotal(' + functionNum + '), billUpyog(), save_changed_values_for_history(),discountWithTotal(' + functionNum + ')" data-original-value="0" autocomplete="off" inputmode="numeric" ></td><td class="d-none"> <input type="text" class="form-control vat" name="product_tax[]" id="vat-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" inputmode="numeric"></td> <td id="texttaxa-' + cvalue + '" class="text-center d-none">0</td> <td><input type="text" class="form-control text-right discount" name="product_discount[]" onkeypress="return isNumber(event)" id="discount-' + cvalue + '" onkeyup="discountWithTotal(' + functionNum + '),save_changed_values_for_history()" autocomplete="off" data-original-value="0"></td> <td><input type="hidden" class="form-control" name="foc[]" onkeypress="return isNumber(event)" id="foc-' + cvalue + '" ></td> <td class="text-right"><strong><span class=\'ttlText\' id="result-' + cvalue + '">0</span></strong> <div class="costvaluation_section" id="costvaluation_section-' + cvalue + '"><strong class="text-danger" id="cost_warning_val-' + cvalue + '"></strong></div></td> <td class="text-center"><button onclick="single_product_details(' + cvalue + ')" type="button" class="btn btn-sm btn-secondary" title="Product Informations"><i class="fa fa-info"></i></button> <button type="button" data-rowid="' + cvalue + '" class="btn btn-sm btn-secondary removeProd" title="Remove" > <i class="fa fa-trash"></i> </button> </td><input type="hidden" name="taxa[]" id="taxa-' + cvalue + '" value="0"><input type="hidden" name="disca[]" id="disca-' + cvalue + '" value="0"><input type="hidden" class="ttInput" name="product_subtotal[]" id="total-' + cvalue + '" value="0"> <input type="hidden" class="pdIn" name="pid[]" id="pid-' + cvalue + '" value="0"> <input type="hidden" name="unit[]" id="unit-' + cvalue + '" value=""> <input type="hidden" name="serial[]" id="serial-' + cvalue + '" value=""></tr>';

   
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
            
            //check duplicate entry starts
            var newProductID = ui.item.data[7]; // New selected product name
            var duplicateFound = false;      
            var totalRows = parseInt($('#ganak').val()); // total number of rows
            for (var i = 0; i <= totalRows; i++) {
                if ($('#purchasecode-' + i).length) {
                    var existingProductName = $('#purchasecode-' + i).val();
                    if (existingProductName == newProductID) {
                        duplicateFound = true;
                        break;
                    }
                }
            }
            if (duplicateFound) {                
                duplicate_message(cvalue,newProductID);
                return false;
            }
            //check duplicate entry ends
            
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
            $('#expense_account_number-' + id[1]).val(ui.item.data[10]);
            $('#last_purchase_price_label-' + id[1]).text(ui.item.data[11]);

            var fulltitle = ui.item.data[0]+" ("+ui.item.data[7]+")";
            $('#purchasecode-'+ id[1]).attr('title',  fulltitle+' - (New)');
            $('#productname-'+ id[1]).attr('title',  fulltitle+' - Product Name(New)');
            $('#amount-'+ id[1]).attr('title',  fulltitle+' - Quantity(New)');
            $('#price-'+ id[1]).attr('title',  fulltitle+' - Price(New)');
            $('#discount-'+ id[1]).attr('title',  fulltitle+' - Discount(New)');


            rowTotal(cvalue);
            billUpyog();
            save_changed_values_for_history();

        },
        create: function (e) {
            $(this).prev('.ui-helper-hidden-accessible').remove();
        }
    });

    $('#purchasecode-' + cvalue).autocomplete({        
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


            var newProductID = ui.item.data[7]; // New selected product name
            var duplicateFound = false;
            var totalRows = parseInt($('#ganak').val()); // total number of rows
            for (var i = 0; i <= totalRows; i++) {
                if ($('#purchasecode-' + i).length) { // Check if this field exists
                    var existingProductName = $('#purchasecode-' + i).val();
                    if (existingProductName == newProductID && i != id[1]) {
                        duplicateFound = true;
                        break;
                    }
                }
            }
            if (duplicateFound) {                
                duplicate_message(cvalue,newProductID);
                return false;
            }

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
            $('#productname-' + id[1]).val(ui.item.data[0]);
            $('#hsn-' + id[1]).val(ui.item.data[7]);
            $('#alert-' + id[1]).val(parseInt(ui.item.data[8]));
            // $('#serial-' + id[1]).val(ui.item.data[10]);
            $('#expense_account_number-' + id[1]).val(ui.item.data[10]); 
            $('#last_purchase_price_label-' + id[1]).text(ui.item.data[11]);
            var fulltitle = ui.item.data[0]+" ("+ui.item.data[7]+")";
            $('#purchasecode-'+ id[1]).attr('title',  fulltitle+' - (New)');
            $('#productname-'+ id[1]).attr('title',  fulltitle+' - Product Name(New)');
            $('#amount-'+ id[1]).attr('title',  fulltitle+' - Quantity(New)');
            $('#price-'+ id[1]).attr('title',  fulltitle+' - Price(New)');
            $('#discount-'+ id[1]).attr('title',  fulltitle+' - Discount(New)');
            rowTotal(cvalue);
            billUpyog();
            save_changed_values_for_history();

        },
        create: function (e) {
            $(this).prev('.ui-helper-hidden-accessible').remove();
        }
    });
    // Add functionality to the button popover
    $('#btnclk-' + cvalue).popover();
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
    ganakval = $("#ganak").val();
    $('#saman-row tr').each(function (index) {
        if(ganakval>0)
        {
            // $("#ganak").val($ganakval-1);
        }
        // if(pageName=="quote/create" || pageName=="DeliveryNotes/create" || pageName=="deliverynotes/create")
        // {
        //     $(this).find('.serial-number').text(index-1);
        // }
        // else{
        //     $(this).find('.serial-number').text(index);
        // }
        $(this).find('.serial-number').text(index);
        
    });
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
    var order_discount = accounting.unformat($('#order_discount').val(), accounting.settings.number.decimal);
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
    ship_val = ship_val - order_discount;
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
        if (value === undefined || value === null) {
            return 0;
        }
        return parseFloat(value.toString().replace(/,/g, '')) || 0;
    }

    function formatNumber(number) {
        return number.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    // Getting input values
    var order_discount = parseCurrency($("#order_discount").val());
    // var invoiceyoghtml = parseCurrency($("#invoiceyoghtml").val());
    var invoiceyoghtml;
    if ($("#invoiceyoghtml").length && $("#invoiceyoghtml").val()) {
        invoiceyoghtml = parseCurrency($("#invoiceyoghtml").val());
    } else {
        invoiceyoghtml = parseCurrency($(".invoiceyoghtml").val());
    }
   
    var old_order_discount = parseCurrency($("#old_order_discount").val());
    var grandproductamount = parseCurrency($("#grandamount").text());
    var totalproductdiscount = parseCurrency($("#discs").text());
    var shiipnig = parseCurrency($(".shipVal").val());

    // Calculating grand discount and current net
    var granddiscount = order_discount + totalproductdiscount;
    var currentnet = (grandproductamount + shiipnig) - (totalproductdiscount + order_discount);
    currentnet = formatNumber(currentnet);


    // Adjust order discount based on old discount
    if (isNaN(old_order_discount) || old_order_discount > 0) {
        order_discount = order_discount - old_order_discount;
    }

    // Ensure invoiceyoghtml is valid
    if (isNaN(invoiceyoghtml) || invoiceyoghtml <= 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Add at least one product',
            text: 'At least one product quantity must be greater than zero.',
        });
        return; 
    }

    // If order_discount is invalid or 0, reset the total text
    if (isNaN(order_discount) || order_discount <= 0) {
        $("#grandtotaltext").text($("#invoiceyoghtml").val());
    }

    // Validation: Order discount cannot exceed invoice total
    if (order_discount > invoiceyoghtml) {
        Swal.fire({
            icon: 'error',
            title: 'Invalid Discount',
            text: 'Order discount is greater than the net total.',
        });
        return;
    }

    // Update total and discount text values
    $("#grandtotaltext").text(currentnet);
    $("#invoiceyoghtml").val(currentnet);
    $("#granddiscount").text(granddiscount.toFixed(2));
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
    $(".grandtotaltext").text(totalBillVal);
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
    var counter = $("#ganak").val();
    counter = counter+1;
    var grand_amounts=0;
    for (var z = 0; z < counter; z++) {       
        var product_price = parseFloat($("#price-" + z).val()) || 0;
        var product_qty = parseFloat($("#amount-" + z).val()) || 0;
        total_amount = parseFloat((product_qty * product_price).toFixed(2));
        grand_amounts = total_amount + grand_amounts;
    }
    var formatted_grand_amount = grand_amounts.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    $("#grandamount").html(formatted_grand_amount);
    var pathname = window.location.pathname;
    var parts = pathname.split("/");
    var secondName = parts[2]
    var lastPart = parts.length > 0 ? parts[parts.length - 1] : "";
    pageName = secondName+"/"+lastPart;
    
    if(($("#available_credit").val()) && ((pageName=="SalesOrders/draft_or_edit") || (pageName=="SalesOrders/create") || (pageName=="DeliveryNotes/create") || (pageName=="SalesOrders/salesorder_new") || (pageName=="invoices/create") || (pageName=="deliverynotes/create"))){  
        credit_limit_with_grand_total();
    }
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
    // alert(validationval+ "sdf "+deliveredQtyVal)
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
        var remainig_qty = deliveryreturnedqtyVal-amountVal;
        if(remainig_qty < 0 || validationval > deliveredQtyVal)
        {
            Swal.fire({
                icon: 'error',
                title: 'Invalid Input',
                text: 'The Sum of Returned Quantity and Return Quantity is Greater than Delivered Quantity or Amount is not valid',
                confirmButtonText: 'OK'
            });
            $("#total-" + numb).val(0);
            $("#result-" + numb).text(0);
            $("#discount-amtlabel-" + numb).text("0.00");    
            $("#amount-" + numb).val(0);
            $("#discount_total").html(0);
            rowTotal(numb);
        }

        
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
   
    var dataid =  $(this).data('rowid');
    swal.fire({
        title: "Are you sure?",
        text: "Once deleted, you will not be able to recover this item!",
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
            var item_code = $row.find('.code').val();
            var deletedval = $(".deleted_item").val();
            item_code = (deletedval) ? deletedval + "," + item_code : item_code;          
            $(".deleted_item").val(item_code);
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

            var pathname = window.location.pathname;
            var parts = pathname.split("/");
            var secondName = parts[2]
            var lastPart = parts.length > 0 ? parts[parts.length - 1] : "";
            pageName = secondName+"/"+lastPart;
            $('.amnt').each(function (index) {
                rowTotal(index);
                billUpyog();
                if(pageName=="expenseclaims/create" || pageName=="expenseclaims/edit" || pageName=="purchase/create")
                {
                    discountWithTotal(index);
                }
                
            });
            if(totalrows == 0){
                $("#invoiceyoghtml").val(0);
                $("#grandtotaltext").text(0);
                $("#grandamount").text(0);
                $('.noproduct-section').addClass('d-none');
            } else {
                $('.noproduct-section').removeClass('d-none');
            }
            $ganakval = $("#ganak").val();
            $('#saman-row tr').each(function (index) {
                if($ganakval>0)
                {
                    // $("#ganak").val($ganakval-1);
                }
                if(pageName=="quote/create" || pageName=="DeliveryNotes/create" || pageName=="deliverynotes/create")
                {
                    $(this).find('.serial-number').text(index-1);
                }
                else{
                    $(this).find('.serial-number').text(index);
                }
                
            });
           
            
            if((pageName=="Invoices/costing")){  
                costingamount(dataid);
            }
        }
    });

    return false;
});

$('.saman-row').on('click', '.removeProd', function () {  
    swal.fire({
        title: "Are you sure?",
        text: "Once deleted, you will not be able to recover this item!!",
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
                $("#grandamount").text(0);
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
        //check duplicate entry starts
            var newProductCode = ui.item.data[7]; // New selected product name
            var duplicateFound = false;      
            var totalRows = parseInt($('#ganak').val()); // total number of rows
            for (var i = 0; i <= totalRows; i++) {
                if ($('#code-' + i).length) {
                    var existingProductCode = $('#code-' + i).val();
                    if (existingProductCode == newProductCode) {
                        duplicateFound = true;
                        break;
                    }
                }
            }
            if (duplicateFound) {                
                duplicate_message('0',newProductCode);
                return false;
            }
            //check duplicate entry ends
        //check duplicate entry ends
        var discount = ui.item.data[4];
        var custom_discount = $('#custom_discount').val();
        if (custom_discount > 0) discount = deciFormat(custom_discount);
        $("#onhandQty-0").text(parseInt(ui.item.data[8]));
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
        // $("#maxdiscountratelabel-0").text(ui.item.data[12]); 
        //erp2024 add discount amount 27-03-2025
        var productPrice = parseFloat(ui.item.data[1]);
        maxdiscount =  parseFloat(ui.item.data[12]);
        var discountValue = ((productPrice * maxdiscount) / 100);
        discountValue = discountValue.toFixed(2);
        $('#maxdiscountamount-0').val(discountValue);
        $('#maxdiscountratelabel-0').text(ui.item.data[12]+"% ("+discountValue+")");

        $("#code-0").val(ui.item.data[7]); 
        $('#taxlabel-0').text(t_r+" / ");        
        $('#income_account_number-0').val(ui.item.data[13]);
        $('#expense_account_number-0').val(ui.item.data[14]);
        $('#product_cost-0').val(ui.item.data[15]);
        rowTotal(0);
        billUpyog();
        var pathname = window.location.pathname;
        var parts = pathname.split("/");
        var secondName = parts[2]
        var lastPart = parts.length > 0 ? parts[parts.length - 1] : "";
        pageName = secondName+"/"+lastPart;
    
        if(pageName!="quote/create" && pageName!="subscriptions/create"){  
            orderdiscount();
        }
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
        //check duplicate entry starts
        var newProductCode = ui.item.data[7]; // New selected product name
        var duplicateFound = false;      
        var totalRows = parseInt($('#ganak').val()); // total number of rows
        if(totalRows > 0)
        {
            for (var i = 0; i <= totalRows; i++) {
                if ($('#code-' + i).length) {
                    var existingProductCode = $('#code-' + i).val();
                    if (existingProductCode == newProductCode) {
                        duplicateFound = true;
                        break;
                    }
                }
            }
            if (duplicateFound) {                
                duplicate_message('0',newProductCode);
                return false;
            }
        }
        //check duplicate entry ends
        if ($("#taxformat option:selected").attr('data-trate')) {

            t_r = $("#taxformat option:selected").attr('data-trate');
        }
        var discount = ui.item.data[4];
        var custom_discount = $('#custom_discount').val();
        if (custom_discount > 0) discount = deciFormat(custom_discount);
        $("#onhandQty-0").text(parseInt(ui.item.data[8]));
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
         //erp2024 add discount amount 27-03-2025
         var productPrice = parseFloat(ui.item.data[1]);
         maxdiscount =  parseFloat(ui.item.data[12]);
         var discountValue = ((productPrice * maxdiscount) / 100);
         discountValue = discountValue.toFixed(2);
         $('#maxdiscountamount-0').val(discountValue);
         $('#maxdiscountratelabel-0').text(ui.item.data[12]+"% ("+discountValue+")");

        // $("#maxdiscountratelabel-0").text(ui.item.data[12]); 
        $("#productname-0").val(ui.item.data[0]); 
        $('#taxlabel-0').text(t_r+" / ");
        $('#income_account_number-0').val(ui.item.data[13]);
        $('#expense_account_number-0').val(ui.item.data[14]);
        $('#product_cost-0').val(ui.item.data[15]);   
        
     
        rowTotal(0);
        billUpyog();
        // if(discount)
        // {
        //     $('.discountcoloumn').removeClass('d-none');
        // }
        
        var pathname = window.location.pathname;
        var parts = pathname.split("/");
        var secondName = parts[2]
        var lastPart = parts.length > 0 ? parts[parts.length - 1] : "";
        pageName = secondName+"/"+lastPart;
    
        if((pageName!="quote/create")){  
            orderdiscount();
        }
       
        // $("#row_btn").click();
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
        $("#onhandQty-0").text(parseInt(ui.item.data[8]));
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
    // var approvalflg = $("#approvalflg").val();
    // if(approvalflg!=1)
    // {
    //     Swal.fire(
    //         'Authorization Approval',
    //         'Authorization approval is required to proceed.',
    //         'info'
    //     );
    //     return;
    // }
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


function convertToSalesOrderDirect(id) {
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
jQuery.validator.addMethod("phoneRegex", function (value, element) {
    // return this.optional(element) || /^\s*(\+?\d{1,4}[\s.-]?)?(\(?\d{1,5}\)?[\s.-]?){1,4}\d{1,5}\s*[\+0-9\(\)\.\-\s]{1,}[0-9]{1,}$/.test(value);
    return this.optional(element) || /^\s*(\+?\d{1,4}[\s.-]?)?(\(?\d{1,5}\)?[\s.-]?)?\d{1,5}[\s.-]?\d{1,5}[\s.-]?\d{1,5}\s*$/.test(value);
}, "Enter a valid phone number");


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
    var product_id1 = ($("#code-" + id).val()) ? $("#code-" + id).val() : $("#purchasecode-" + id).val();

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
                tableContent += '<tr><td class="text-left">Arabic Name</td><td class="text-left">' + data.arabic_name + '</td></tr>';
                tableContent += '<tr><td class="text-left">Description</td><td class="text-left">' + data.product_name + '</td></tr>';
                tableContent += '<tr><td class="text-left">Cost</td><td class="text-left">' + data.product_cost + '</td></tr>';
                tableContent += '<tr><td class="text-left">Selling Price</td><td class="text-left">' + data.product_price + '</td></tr>';
                tableContent += '<tr><td class="text-left">Web Price</td><td class="text-left">' + data.web_price + '</td></tr>';
                tableContent += '<tr><td class="text-left">Wholesale Price</td><td class="text-left">' + data.wholesale_price + '</td></tr>';
                tableContent += '<tr><td class="text-left">Lowest Price</td><td class="text-left">' + data.minimum_price + '</td></tr>';
                // tableContent += '<tr><td class="text-left">Category</td><td class="text-left">' + data.category + '</td></tr>';
                tableContent += '<tr><td class="text-left">Supplier</td><td class="text-left">' + data.supplier + '</td></tr>';
                tableContent += '<tr><td class="text-left">Made In</td><td class="text-left">' + data.madein + '</td></tr>';
                itemtitle = data.product_name+"("+product_id1+")";
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
                // tableContent += '<tr><td>Category</td><td>' + data.category + '</td></tr>';
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
//alert(
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
    
    //hide product history button
    $(".producthis").hide();

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
        $("#onhandQty-0").text(parseInt(ui.item.data[8]));
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
        $("#onhandQty-0").text(parseInt(ui.item.data[8]));        
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
    var lastSerialNumber = parseInt($('#saman-row .serial-number:last').text()) || 0;    
    var nextSerialNumber = lastSerialNumber+1;
    var discount_flg = $('.discount_flg').val();
    var dnoneclass = (discount_flg==1) ? "" : "d-none";
    var data = '<tr><td class="text-center serial-number">'+nextSerialNumber+'</td><td><input type="text" class="form-control" name="code[]"  id="code-' + cvalue + '" placeholder="Search by Item No." ></td>';

    data += '<td ><input type="text" class="form-control" name="product_name[]" placeholder="Search by Product Name or Item No." id="productname-' + cvalue + '"></td>';
    
    data += '<td class="position-relative"><input type="number" class="form-control req amnt" name="product_qty[]" id="amount-' + cvalue + '" maxlength="6" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog(), save_changed_values_for_history(),check_on_hand_quantity()" autocomplete="off" value="1"  inputmode="numeric" data-original-value="1"><input type="hidden" id="alert-' + cvalue + '" value=""  name="alert[]"><div class="tooltip1"></div></td><td class="text-center"><strong id="onhandQty-' + cvalue + '"></strong></td>';

    data += '<td class="text-right"><strong id="pricelabel-' + cvalue + '"></strong><input type="hidden" class="form-control" name="lowest_price[]" id="lowestprice-' + cvalue + '" onkeypress="return isNumber(event)" autocomplete="off"></td>';

    data += '<td class="text-right"><strong id="lowestpricelabel-' + cvalue + '"></strong><input type="hidden" class="form-control req prc" name="product_price[]" id="price-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog(), save_changed_values_for_history()" autocomplete="off" inputmode="numeric"></td>';

    if(configured_tax!=0){
        data += '<td class="text-center"> <input type="hidden" class="form-control vat" name="product_tax[]" id="vat-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog(), save_changed_values_for_history()" autocomplete="off" inputmode="numeric"><strong id="taxlabel-' + cvalue + '"></strong>&nbsp;<strong  id="texttaxa-' + cvalue + '"></strong></td> ';
    }
    else{
        var hiddendata = '<input type="text" class="form-control vat" name="product_tax[]" id="vat-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" inputmode="numeric">';
    }
    data+='<td class="text-center"><strong id="maxdiscountratelabel-' + cvalue + '"></strong></td>';
   

    data+='<td class="text-center discountcoloumn '+dnoneclass+'"><div class="input-group text-center"><select  name="discount_type[]" id="discounttype-' + cvalue + '" class="form-control element-height" onchange="discounttypeChange(' + cvalue + '), save_changed_values_for_history()" data-original-value="Perctype"><option value="Perctype">%</option><option value="Amttype">Amt</option></select>&nbsp;<input type="number" min="0" class="form-control discount element-height" name="product_discount[]" onkeypress="return isNumber(event)" id="discount-' + cvalue + '"  autocomplete="off" onkeyup="discounttypeChange(' + cvalue + '), save_changed_values_for_history()" data-original-value="0.00" ><input type="number" min="0" class="form-control discount d-none element-height" name="product_amt[]" onkeypress="return isNumber(event)" id="discountamt-' + cvalue + '" autocomplete="off" onkeyup="discounttypeChange(' + cvalue + '), save_changed_values_for_history()" data-original-value="0.00" ></div><strong class="discount-amtlabel" id="discount-amtlabel-' + cvalue + '"></strong><div><strong id="discount-error-' + cvalue + '"></strong></div></td>';

    data += '<td  class="text-right"><strong><span class=\'ttlText\' id="result-' + cvalue + '">0</span></strong></td> <td class="text-center1"> <button title="Previous Quoted History" onclick="producthistory('+cvalue+')" type="button" class="btn btn-sm btn-secondary producthis"><i class="fa fa-history"></i></button> <button onclick="single_product_details('+cvalue+')" type="button" class="btn btn-sm btn-secondary" title="Product Informations"><i class="fa fa-info"></i></button> <button type="button" data-rowid="' + cvalue + '" class="btn btn-sm btn-secondary removeProd" title="Remove" > <i class="fa fa-trash"></i> </button></td>';

    data += '<input type="hidden" name="taxa[]" id="taxa-' + cvalue + '" value="0"><input type="hidden" name="disca[]" id="disca-' + cvalue + '" value="0"><input type="hidden" class="ttInput" name="product_subtotal[]" id="total-' + cvalue + '" value="0"> <input type="hidden" class="pdIn" name="pid[]" id="pid-' + cvalue + '" value="0"> <input type="hidden" name="unit[]" id="unit-' + cvalue + '" value=""> <input type="hidden" name="hsn[]" id="hsn-' + cvalue + '" value=""> <input type="hidden" name="serial[]" id="serial-' + cvalue + '" value="">'+hiddendata+'<input type="hidden" name="maxdiscountrate[]" id="maxdiscountrate-' + cvalue + '"></tr>';

    //ajax request lowestpricelabel
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

            //check duplicate entry starts
            var newProductID = ui.item.data[7]; // New selected product name
            var duplicateFound = false;      
            var totalRows = parseInt($('#ganak').val()); // total number of rows
            for (var i = 0; i <= totalRows; i++) {
                if ($('#code-' + i).length) {
                    var existingProductName = $('#code-' + i).val();
                    if (existingProductName == newProductID) {
                        duplicateFound = true;
                        break;
                    }
                }
            }
            if (duplicateFound) {                
                duplicate_message(cvalue,newProductID);
                return false;
            }
            //check duplicate entry ends
           
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

             //erp2024 add discount amount 27-03-2025
             var productPrice = parseFloat(ui.item.data[1]);
             maxdiscount =  parseFloat(ui.item.data[12]);
             var discountValue = ((productPrice * maxdiscount) / 100);
             discountValue = discountValue.toFixed(2);
             $('#maxdiscountamount-' + id[1]).val(discountValue);
             $('#maxdiscountratelabel-'+ id[1]).text(ui.item.data[12]+"% ("+discountValue+")");

            $('#code-'+ id[1]).val(ui.item.data[7]);
            var fulltitle = ui.item.data[0]+" ("+ui.item.data[7]+")";
            $('#code-'+ id[1]).attr('title',  fulltitle+' - (New)');
            $('#amount-'+ id[1]).attr('title',  fulltitle+' - Quantity(New)');
            $('#discounttype-'+ id[1]).attr('title',  fulltitle+' - Discount Type(New)');
            $('#discountamt-'+ id[1]).attr('title',  fulltitle+' - Discount Amount(New)');
            $('#discount-'+ id[1]).attr('title',  fulltitle+' - Discount Percentage(New)');
            $('#productname-'+ id[1]).attr('title',  fulltitle+' - Product Name(New)');
            rowTotal(cvalue);
            billUpyog();
            save_changed_values_for_history();
            
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
            
            //check duplicate entry starts
            var newProductID = ui.item.data[7]; // New selected product name
            var duplicateFound = false;      
            var totalRows = parseInt($('#ganak').val()); // total number of rows
            for (var i = 0; i <= totalRows; i++) {
                if ($('#code-' + i).length) {
                    var existingProductName = $('#code-' + i).val();
                    if (existingProductName == newProductID) {
                        duplicateFound = true;
                        break;
                    }
                }
            }
            if (duplicateFound) {                
                duplicate_message(cvalue,newProductID);
                return false;
            }
            //check duplicate entry ends

            var fulltitle = ui.item.data[0]+" ("+ui.item.data[7]+")";
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

            //erp2024 add discount amount 27-03-2025
            var productPrice = parseFloat(ui.item.data[1]);
            maxdiscount =  parseFloat(ui.item.data[12]);
            var discountValue = ((productPrice * maxdiscount) / 100);
            discountValue = discountValue.toFixed(2);
            $('#maxdiscountamount-' + id[1]).val(discountValue);
            $('#maxdiscountratelabel-'+ id[1]).text(ui.item.data[12]+"% ("+discountValue+")");
            // $('#maxdiscountratelabel-'+ id[1]).text(ui.item.data[12]);
            // $('#maxdiscountratelabel-'+ id[1]).text(ui.item.data[12]);
            $('#productname-'+ id[1]).val(ui.item.data[0]);
            
            $('#code-'+ id[1]).attr('title',  fulltitle+' - (New)');
            $('#amount-'+ id[1]).attr('title',  fulltitle+' - Quantity(New)');
            $('#discounttype-'+ id[1]).attr('title',  fulltitle+' - Discount Type(New)');
            $('#discountamt-'+ id[1]).attr('title',  fulltitle+' - Discount Amount(New)');
            $('#discount-'+ id[1]).attr('title',  fulltitle+' - Discount Percentage(New)');
            $('#productname-'+ id[1]).attr('title',  fulltitle+' - Product Name(New)');
            rowTotal(cvalue);
            billUpyog();
            save_changed_values_for_history();
            $(".noproduct-section").removeClass('d-none');


        },
        create: function (e) {
            $(this).prev('.ui-helper-hidden-accessible').remove();
        }
    });

});


$('#salesorder_create_btn').on('click', function () {
    var cvalue = parseInt($('#ganak').val()) + 1;
    var configured_tax = parseInt($('#configured_tax').val());
    var nxt = parseInt(cvalue);
    $('#ganak').val(nxt);
    var functionNum = "'" + cvalue + "'";
    count = $('#saman-row div').length;
    // <input type="checkbox" class="checkedproducts" name="product_id[]"  id="prd-' + cvalue + '" autocomplete="off"></input>
    var data = '<tr><td>--</td><td><input type="text" class="form-control" name="code[]"  id="code-' + cvalue + '" placeholder="Search by Product Name or Item No." ></td><td><input type="text" class="form-control" name="product_name[]" placeholder="Search by Product Name or Item No." id="productname-' + cvalue + '"></td><td><input type="number" class="form-control req amnt" name="product_qty[]" id="amount-' + cvalue + '" maxlength="6" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog(), save_changed_values_for_history()" autocomplete="off" value="1"  inputmode="numeric" data-original-value="1"><input type="hidden" id="alert-' + cvalue + '" value=""  name="alert[]"> </td><td class="text-center"><strong id="onhandQty-' + cvalue + '"></strong></td>';

    data += '<td class="text-right"><strong id="pricelabel-' + cvalue + '"></strong><input type="hidden" class="form-control" name="lowest_price[]" id="lowestprice-' + cvalue + '" onkeypress="return isNumber(event)" autocomplete="off"></td>';

    data += '<td class="text-right"><strong id="lowestpricelabel-' + cvalue + '"></strong><input type="hidden" class="form-control req prc" name="product_price[]" id="price-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog(), save_changed_values_for_history()" autocomplete="off" inputmode="numeric"></td>';

    if(configured_tax!=0){
        data += '<td class="text-center"> <input type="hidden" class="form-control vat" name="product_tax[]" id="vat-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog(), save_changed_values_for_history()" autocomplete="off" inputmode="numeric"><strong id="taxlabel-' + cvalue + '"></strong>&nbsp;<strong  id="texttaxa-' + cvalue + '"></strong></td> ';
    }
    else{
        var hiddendata = '<input type="text" class="form-control vat" name="product_tax[]" id="vat-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" inputmode="numeric">';
    }
    data+='<td class="text-center"><strong id="maxdiscountratelabel-' + cvalue + '"></strong></td>';
   
    data+='<td class="text-center"><div class="input-group text-center"><select  name="discount_type[]" id="discounttype-' + cvalue + '" class="form-control" onchange="discounttypeChange(' + cvalue + '), save_changed_values_for_history()" data-original-value="Perctype"><option value="Perctype">Perc</option><option value="Amttype">Amt</option></select>&nbsp;<input type="number" min="0" class="form-control discount" name="product_discount[]" onkeypress="return isNumber(event)" id="discount-' + cvalue + '"  autocomplete="off" onkeyup="discounttypeChange(' + cvalue + '), save_changed_values_for_history()" data-original-value="0.00" ><input type="number" min="0" class="form-control discount d-none" name="product_amt[]" onkeypress="return isNumber(event)" id="discountamt-' + cvalue + '" autocomplete="off" onkeyup="discounttypeChange(' + cvalue + '), save_changed_values_for_history()" data-original-value="0.00" ></div><strong class="discount-amtlabel" id="discount-amtlabel-' + cvalue + '"></strong><div><strong id="discount-error-' + cvalue + '"></strong></div></td>';

    data += '<td  class="text-right"><strong><span class=\'ttlText\' id="result-' + cvalue + '">0</span></strong></td> <td class="text-center"> <button onclick="producthistory('+cvalue+')" type="button" class="btn btn-sm btn-secondary producthis"><i class="fa fa-history"></i></button> <button onclick="single_product_details('+cvalue+')" type="button" class="btn btn-sm btn-secondary"  title="Product Informations"><i class="fa fa-info"></i></button> <button type="button" data-rowid="' + cvalue + '" class="btn btn-sm btn-secondary removeProd" title="Remove" > <i class="fa fa-trash"></i> </button></td>';

    data += '<input type="hidden" name="taxa[]" id="taxa-' + cvalue + '" value="0"><input type="hidden" name="disca[]" id="disca-' + cvalue + '" value="0"><input type="hidden" class="ttInput" name="product_subtotal[]" id="total-' + cvalue + '" value="0"> <input type="hidden" class="pdIn" name="pid[]" id="pid-' + cvalue + '" value="0"> <input type="hidden" name="unit[]" id="unit-' + cvalue + '" value=""> <input type="hidden" name="hsn[]" id="hsn-' + cvalue + '" value=""> <input type="hidden" name="serial[]" id="serial-' + cvalue + '" value="">'+hiddendata+'<input type="hidden" name="maxdiscountrate[]" id="maxdiscountrate-' + cvalue + '"></tr>';

    //ajax request lowestpricelabel
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
            var fulltitle = ui.item.data[0]+" ("+ui.item.data[7]+")";
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

            $('#code-'+ id[1]).attr('title',  fulltitle+' - (New)');
            $('#amount-'+ id[1]).attr('title',  fulltitle+' - Quantity(New)');
            $('#discounttype-'+ id[1]).attr('title',  fulltitle+' - Discount Type(New)');
            $('#discountamt-'+ id[1]).attr('title',  fulltitle+' - Discount Amount(New)');
            $('#discount-'+ id[1]).attr('title',  fulltitle+' - Discount Percentage(New)');
            $('#productname-'+ id[1]).attr('title',  fulltitle+' - Product Name(New)');
            // $('#prd-' + id[1]).val(ui.item.data[2]);
            rowTotal(cvalue);
            billUpyog();
            save_changed_values_for_history();
            
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
            var fulltitle = ui.item.data[0]+" ("+ui.item.data[7]+")";
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
            
            $('#code-'+ id[1]).attr('title',  fulltitle+' - (New)');
            $('#amount-'+ id[1]).attr('title',  fulltitle+' - Quantity(New)');
            $('#discounttype-'+ id[1]).attr('title',  fulltitle+' - Discount Type(New)');
            $('#discountamt-'+ id[1]).attr('title',  fulltitle+' - Discount Amount(New)');
            $('#discount-'+ id[1]).attr('title',  fulltitle+' - Discount Percentage(New)');
            $('#productname-'+ id[1]).attr('title',  fulltitle+' - Product Name(New)');
            // $('#prd-' + id[1]).val(ui.item.data[2]);
            rowTotal(cvalue);
            billUpyog();
            save_changed_values_for_history();
            $(".noproduct-section").removeClass('d-none');


        },
        create: function (e) {
            $(this).prev('.ui-helper-hidden-accessible').remove();
        }
    });

    
    //hide product history button
    $(".producthis").hide();

});


$('#row_btn').on('click', function () {
    var cvalue = parseInt($('#ganak').val()) + 1;
    var configured_tax = parseInt($('#configured_tax').val());
    var nxt = parseInt(cvalue);
    $('#ganak').val(nxt); 
    var functionNum = "'" + cvalue + "'";
    count = $('#saman-row div').length;
    var lastSerialNumber = parseInt($('#saman-row .serial-number:last').text()) || 0;    
    var nextSerialNumber = lastSerialNumber+1;
    var discount_flg = $('.discount_flg').val();
    var dnoneclass = (discount_flg==1) ? "" : "d-none";

    var data = '<tr><td class="text-center serial-number">'+nextSerialNumber+'</td><td><input type="text" class="form-control code" name="code[]" placeholder="Item No."  id="code-' + cvalue + '"><input type="hidden" class="form-control" name="product_cost[]" placeholder="Item No."  id="product_cost-' + cvalue + '"></td>';
    data += '<td><span class="d-flex"><input type="text" class="form-control productname" name="product_name[]" placeholder="Search by Product Name or Item No." id="productname-' + cvalue + '"> <input type="hidden" class="form-control" name="income_account_number[]" id="income_account_number-' + cvalue + '" ><input type="hidden" class="form-control" name="expense_account_number[]" id="expense_account_number-' + cvalue + '" >';

    // Add the button after product name input discounttypeChange
    data += '&nbsp;<button type="button" title="change account" class="btn btn-sm btn-secondary" id="btnclk-' + cvalue + '" data-toggle="popover" onclick="loadPopover(' + cvalue +')" data-html="true" data-content=\'<form id="popoverForm-' + cvalue + '"><div class="form-group"><label for="accountList-' + cvalue + '">Select Account</label><select class="form-control" id="accountList-' + cvalue + '"></select></div><div class="text-right"><button type="button" onclick="cancelPopover(' + cvalue + ')" class="btn btn-secondary btn-sm">Cancel</button>&nbsp;<button type="button" onclick="change_product_account(' + cvalue + ')" class="btn btn-primary btn-sm">Change</button></div></form>\'><i class="fa fa-bank"></i></span></button></td>';

    data += '<td class="position-relative"><input type="number" class="form-control req amnt product_qty" name="product_qty[]" id="amount-' + cvalue + '" maxlength="6" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog(), orderdiscount(), save_changed_values_for_history()" data-original-value="1" autocomplete="off" value="0"  inputmode="numeric"><input type="hidden" id="alert-' + cvalue + '" value=""  name="alert[]"> <div class="tooltip1">Invalid Quantity</div></td><td class="text-center"><strong id="onhandQty-' + cvalue + '"></strong></td>';
    
    data += '<td class="text-right"><strong id="pricelabel-' + cvalue + '"></strong><input type="hidden" class="form-control" name="lowest_price[]" id="lowestprice-' + cvalue + '" onkeypress="return isNumber(event)" autocomplete="off"></td>';

    data += '<td class="text-right"><strong id="lowestpricelabel-' + cvalue + '"></strong><input type="hidden" class="form-control req prc" name="product_price[]" id="price-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" inputmode="numeric"></td>';

    if(configured_tax != 0){
        data += '<td class="text-center"> <input type="hidden" class="form-control vat" name="product_tax[]" id="vat-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" inputmode="numeric"><strong id="taxlabel-' + cvalue + '"></strong>&nbsp;<strong  id="texttaxa-' + cvalue + '"></strong></td> ';
    } else {
        var hiddendata = '<input type="text" class="form-control vat" name="product_tax[]" id="vat-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" inputmode="numeric">';
    }

    data += '<td class="text-center"><strong id="maxdiscountratelabel-' + cvalue + '"></strong></td>';

    data += '<td class="text-center discountcoloumn '+dnoneclass+'"><div class="input-group text-center"><select name="discount_type[]" id="discounttype-' + cvalue + '" class="form-control" onchange="discounttypeChange(' + cvalue + '), save_changed_values_for_history()" data-original-value="Perctype"><option value="Perctype">%</option><option value="Amttype">Amt</option></select>&nbsp;<input type="number" min="0" class="form-control discount" name="product_discount[]" onkeypress="return isNumber(event)" id="discount-' + cvalue + '"  autocomplete="off" onkeyup="discounttypeChange(' + cvalue + '), save_changed_values_for_history()" data-original-value="0.00"><input type="number" min="0" class="form-control discount d-none" name="product_amt[]" onkeypress="return isNumber(event)" id="discountamt-' + cvalue + '" autocomplete="off" onkeyup="discounttypeChange(' + cvalue + '), save_changed_values_for_history()" data-original-value="0.00"></div><strong class="discount-amtlabel" id="discount-amtlabel-' + cvalue + '"></strong><div><strong id="discount-error-' + cvalue + '"></strong></div></td>';

    data += '<td  class="text-right"><strong><span class="ttlText" id="result-' + cvalue + '">0</span></strong></td> <td class="text-center"> <button onclick="producthistory(' + cvalue + ')" type="button" class="btn btn-sm btn-secondary producthis"><i class="fa fa-history"></i></button> <button onclick="single_product_details(' + cvalue + ')" type="button" class="btn btn-sm btn-secondary"  title="Product Informations"><i class="fa fa-info"></i></button> <button type="button" data-rowid="' + cvalue + '" class="btn btn-sm btn-secondary removeProd" title="Remove" > <i class="fa fa-trash"></i> </button></td>';

    data += '<input type="hidden" name="taxa[]" id="taxa-' + cvalue + '" value="0"><input type="hidden" name="disca[]" id="disca-' + cvalue + '" value="0"><input type="hidden" class="ttInput" name="product_subtotal[]" id="total-' + cvalue + '" value="0"> <input type="hidden" class="pdIn" name="pid[]" id="pid-' + cvalue + '" value="0"> <input type="hidden" name="unit[]" id="unit-' + cvalue + '" value=""> <input type="hidden" name="hsn[]" id="hsn-' + cvalue + '" value=""> <input type="hidden" name="serial[]" id="serial-' + cvalue + '" value="">'+hiddendata+'<input type="hidden" name="maxdiscountrate[]" id="maxdiscountrate-' + cvalue + '"></tr>';

    $('tr.last-item-row').before(data);
    row = cvalue;

    // Set up autocomplete for product name and code
    $('#productname-' + cvalue).autocomplete({
        source: function (request, response) {
            $.ajax({
                url: baseurl + 'search_products/' + billtype,
                dataType: "json",
                method: 'post',
                data: 'name_startsWith=' + request.term + '&type=product_list&row_num=' + row + '&wid=' + $("#s_warehouses option:selected").val() + '&' + d_csrf,
                success: function (data) {
                    response($.map(data, function (item) {
                        var product_d = item[0] + " (" + item[7] + ")";
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
            var fulltitle = ui.item.data[0]+" ("+ui.item.data[7]+")";
            if ($("#taxformat option:selected").attr('data-trate')) {
                t_r = $("#taxformat option:selected").attr('data-trate');
            }

            //check duplicate entry starts
            var newProductID = ui.item.data[7]; // New selected product name
            var duplicateFound = false;      
            var totalRows = parseInt($('#ganak').val()); // total number of rows
            for (var i = 0; i <= totalRows; i++) {
                if ($('#code-' + i).length) {
                    var existingProductName = $('#code-' + i).val();
                    if (existingProductName == newProductID) {
                        duplicateFound = true;
                        break;
                    }
                }
            }
            if (duplicateFound) {                
                duplicate_message(cvalue,newProductID);
                return false;
            }
            //check duplicate entry ends

            var discount = ui.item.data[4];
            var custom_discount = $('#custom_discount').val();
            if (custom_discount > 0) discount = deciFormat(custom_discount);

            $("#onhandQty-" + cvalue).text(parseInt(ui.item.data[8]));
            $("#prd-" + cvalue).val(ui.item.data[2]);
            $('#amount-' + cvalue).val(1);
            $('#price-' + cvalue).val(ui.item.data[1]);
            $('#pricelabel-' + cvalue).text(ui.item.data[1]);
            $('#pid-' + cvalue).val(ui.item.data[2]);
            $('#vat-' + cvalue).val(t_r);
            $('#taxlabel-' + cvalue).text(t_r + " / ");
            $('#discount-' + cvalue).val(discount);
            $('#dpid-' + cvalue).val(ui.item.data[5]);
            $('#hsn-' + cvalue).val(ui.item.data[6]);
            $('#serial-' + cvalue).val(ui.item.data[9]); 
            $('#lowestprice-' + id[1]).val(ui.item.data[11]);
            $('#lowestpricelabel-' + id[1]).text(ui.item.data[11]);        
            $('#maxdiscountrate-' + id[1]).val(ui.item.data[12]);
            // $('#maxdiscountratelabel-'+ id[1]).text(ui.item.data[12]);
            //erp2024 add discount amount 27-03-2025
            var productPrice = parseFloat(ui.item.data[1]);
            maxdiscount =  parseFloat(ui.item.data[12]);
            var discountValue = ((productPrice * maxdiscount) / 100);
            discountValue = discountValue.toFixed(2);
            $('#maxdiscountamount-' + id[1]).val(discountValue);
            $('#maxdiscountratelabel-'+ id[1]).text(ui.item.data[12]+"% ("+discountValue+")");

            $('#productname-'+ id[1]).val(ui.item.data[0]);
            $('#income_account_number-'+ id[1]).val(ui.item.data[13]);
            $('#expense_account_number-'+ id[1]).val(ui.item.data[14]);
            $('#product_cost-'+id[1]).val(ui.item.data[15]); 

            $('#code-'+ id[1]).attr('title',  fulltitle+' - (New)');
            $('#amount-'+ id[1]).attr('title',  fulltitle+' - Quantity(New)');
            $('#discounttype-'+ id[1]).attr('title',  fulltitle+' - Discount Type(New)');
            $('#discountamt-'+ id[1]).attr('title',  fulltitle+' - Discount Amount(New)');
            $('#discount-'+ id[1]).attr('title',  fulltitle+' - Discount Percentage(New)');
            $('#productname-'+ id[1]).attr('title',  fulltitle+' - Product Name(New)');
            rowTotal(cvalue);
            orderdiscount();
            save_changed_values_for_history();
           
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
            // //check duplicate entry starts
            var newProductID = ui.item.data[7]; // New selected product name
            var duplicateFound = false;      
            var totalRows = parseInt($('#ganak').val()); // total number of rows
            for (var i = 0; i <= totalRows; i++) {
                if ($('#code-' + i).length) {
                    var existingProductName = $('#code-' + i).val();
                    if (existingProductName == newProductID) {
                        duplicateFound = true;
                        break;
                    }
                }
            }
            if (duplicateFound) {                
                duplicate_message(cvalue,newProductID);
                return false;
            }
            //check duplicate entry ends
            var fulltitle = ui.item.data[0]+" ("+ui.item.data[7]+")";
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
            // $('#maxdiscountratelabel-'+ id[1]).text(ui.item.data[12]);
            //erp2024 add discount amount 27-03-2025
            var productPrice = parseFloat(ui.item.data[1]);
            maxdiscount =  parseFloat(ui.item.data[12]);
            var discountValue = ((productPrice * maxdiscount) / 100);
            discountValue = discountValue.toFixed(2);
            $('#maxdiscountamount-' + id[1]).val(discountValue);
            $('#maxdiscountratelabel-'+ id[1]).text(ui.item.data[12]+"% ("+discountValue+")");
            
            $('#productname-'+ id[1]).val(ui.item.data[0]);            
            $('#income_account_number-'+ id[1]).val(ui.item.data[13]);
            $('#expense_account_number-'+ id[1]).val(ui.item.data[14]);
            $('#product_cost-'+id[1]).val(ui.item.data[15]);

            $('#code-'+ id[1]).attr('title',  fulltitle+' - (New)');
            $('#amount-'+ id[1]).attr('title',  fulltitle+' - Quantity(New)');
            $('#discounttype-'+ id[1]).attr('title',  fulltitle+' - Discount Type(New)');
            $('#discountamt-'+ id[1]).attr('title',  fulltitle+' - Discount Amount(New)');
            $('#discount-'+ id[1]).attr('title',  fulltitle+' - Discount Percentage(New)');
            $('#productname-'+ id[1]).attr('title',  fulltitle+' - Product Name(New)');
            rowTotal(cvalue);
            billUpyog();
            orderdiscount();
            save_changed_values_for_history();
            $(".noproduct-section").removeClass('d-none');


        },
        create: function (e) {
            $(this).prev('.ui-helper-hidden-accessible').remove();
        }
    });
    // Add functionality to the button popover
    $('#btnclk-' + cvalue).popover();
    
    //hide product history button
    $(".producthis").hide();
});


$('#sales_create_btn').on('click', function () {
    var cvalue = parseInt($('#ganak').val()) + 1;
    var configured_tax = parseInt($('#configured_tax').val());
    var nxt = parseInt(cvalue);
    $('#ganak').val(nxt);
    var functionNum = "'" + cvalue + "'";
    count = $('#saman-row div').length;
    var lastSerialNumber = parseInt($('#saman-row .serial-number:last').text()) || 0;    
    var nextSerialNumber = lastSerialNumber+1;
    var discount_flg = $('.discount_flg').val();
    var dnoneclass = (discount_flg==1) ? "" : "d-none";
    var data = '<tr><td class="text-center serial-number">'+nextSerialNumber+'</td><td><input type="text" class="form-control code" name="code[]" placeholder="Item No."  id="code-' + cvalue + '"><input type="hidden" class="form-control" name="income_account_number[]" id="income_account_number-' + cvalue + '" ><input type="hidden" class="form-control" name="expense_account_number[]" id="expense_account_number-' + cvalue + '" ><input type="hidden" class="form-control" name="product_cost[]" id="product_cost-' + cvalue + '" ></td><td><input type="text" class="form-control productname" name="product_name[]" placeholder="Search by Product Name or Item No." id="productname-' + cvalue + '"></td>';
    data += '<td class="position-relative"><input type="number" class="form-control req amnt product_qty" name="product_qty[]" id="amount-' + cvalue + '" maxlength="6" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog(), orderdiscount()" autocomplete="off" value="0"  inputmode="numeric"><input type="hidden" id="alert-' + cvalue + '" value=""  name="alert[]"> </strong><div class="tooltip1"></div>';
    data += '</td><td class="text-center"><strong id="onhandQty-' + cvalue + '"></td>';

    data += '<td class="text-right"><strong id="pricelabel-' + cvalue + '"></strong><input type="hidden" class="form-control" name="lowest_price[]" id="lowestprice-' + cvalue + '" onkeypress="return isNumber(event)" autocomplete="off"></td>';

    data += '<td class="text-right"><strong id="lowestpricelabel-' + cvalue + '"></strong><input type="hidden" class="form-control req prc" name="product_price[]" id="price-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog(), orderdiscount()" autocomplete="off" inputmode="numeric"></td>';

    if(configured_tax!=0){
        data += '<td class="text-center"> <input type="hidden" class="form-control vat" name="product_tax[]" id="vat-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog(), orderdiscount()" autocomplete="off" inputmode="numeric"><strong id="taxlabel-' + cvalue + '"></strong>&nbsp;<strong  id="texttaxa-' + cvalue + '"></strong></td> ';
    }
    else{
        var hiddendata = '<input type="text" class="form-control vat" name="product_tax[]" id="vat-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog(), orderdiscount()" autocomplete="off" inputmode="numeric">';
    }
    data+='<td class="text-center"><strong id="maxdiscountratelabel-' + cvalue + '"></strong></td>';
    
    data+='<td class="text-center discountcoloumn '+dnoneclass+'"><div class="input-group text-center"><select name="discount_type[]" id="discounttype-' + cvalue + '" class="form-control" onchange="discounttypeChange(' + cvalue + '),orderdiscount()"><option value="Perctype">%</option><option value="Amttype">Amt</option></select>&nbsp;<input type="number" min="0" class="form-control discount" name="product_discount[]" onkeypress="return isNumber(event)" id="discount-' + cvalue + '"  autocomplete="off" onkeyup="discounttypeChange(' + cvalue + '), orderdiscount()"><input type="number" min="0" class="form-control discount d-none" name="product_amt[]" onkeypress="return isNumber(event)" id="discountamt-' + cvalue + '" autocomplete="off" onkeyup="discounttypeChange(' + cvalue + '),orderdiscount()"></div><strong class="discount-amtlabel" id="discount-amtlabel-' + cvalue + '"></strong><div><strong id="discount-error-' + cvalue + '"></strong></div></td>';

    data += '<td  class="text-right"><strong><span class=\'ttlText\' id="result-' + cvalue + '">0</span></strong></td> <td class="text-center1"> <button onclick="producthistory('+cvalue+')" type="button" class="btn btn-sm btn-secondary producthis"><i class="fa fa-history"></i></button> <button onclick="single_product_details('+cvalue+')" type="button" class="btn btn-sm btn-secondary"  title="Product Informations"><i class="fa fa-info"></i></button> <button type="button" data-rowid="' + cvalue + '" class="btn btn-sm btn-secondary removeProd" title="Remove" > <i class="fa fa-trash"></i> </button></td>';

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
            //check duplicate entry starts
            var newProductID = ui.item.data[7]; // New selected product name
            var duplicateFound = false;      
            var totalRows = parseInt($('#ganak').val()); // total number of rows
            for (var i = 0; i <= totalRows; i++) {
                if ($('#code-' + i).length) {
                    var existingProductName = $('#code-' + i).val();
                    if (existingProductName == newProductID) {
                        duplicateFound = true;
                        break;
                    }
                }
            }
            if (duplicateFound) {                
                duplicate_message(cvalue,newProductID);
                return false;
            }
            //check duplicate entry ends
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
            // $('#maxdiscountratelabel-'+ id[1]).text(ui.item.data[12]);
            //erp2024 add discount amount 27-03-2025
            var productPrice = parseFloat(ui.item.data[1]);
            maxdiscount =  parseFloat(ui.item.data[12]);
            var discountValue = ((productPrice * maxdiscount) / 100);
            discountValue = discountValue.toFixed(2);
            $('#maxdiscountamount-' + id[1]).val(discountValue);
            $('#maxdiscountratelabel-'+ id[1]).text(ui.item.data[12]+"% ("+discountValue+")");

            $('#income_account_number-'+ id[1]).val(ui.item.data[13]);
            $('#expense_account_number-'+ id[1]).val(ui.item.data[14]);
            $('#product_cost-'+ id[1]).val(ui.item.data[15]);
            $('#code-'+ id[1]).val(ui.item.data[7]);
            var fulltitle = ui.item.data[0]+" ("+ui.item.data[7]+")";            
            $('#code-'+ id[1]).attr('title',  fulltitle+' - (New)');
            $('#amount-'+ id[1]).attr('title',  fulltitle+' - Quantity(New)');
            $('#discounttype-'+ id[1]).attr('title',  fulltitle+' - Discount Type(New)');
            $('#discountamt-'+ id[1]).attr('title',  fulltitle+' - Discount Amount(New)');
            $('#discount-'+ id[1]).attr('title',  fulltitle+' - Discount Percentage(New)');
            $('#productname-'+ id[1]).attr('title',  fulltitle+' - Product Name(New)');

            rowTotal(cvalue);
            billUpyog();
            orderdiscount();
            $(".noproduct-section").removeClass('d-none');
            save_changed_values_for_history();

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
            //check duplicate entry starts
            var totalRows = parseInt($('#ganak').val()); // total number of rows
            var newProductID = ui.item.data[7];
            var duplicateFound = false;      
            
            for (var i = 0; i <= totalRows; i++) {
                if ($('#code-' + i).length) {
                    var existingProductName = $('#code-' + i).val();
                    if (existingProductName == newProductID) {
                        duplicateFound = true;
                        break;
                    }
                }
            }
            if (duplicateFound) {                
                duplicate_message(cvalue,newProductID);
                return false;
            }
            //check duplicate entry ends
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
            // $('#maxdiscountratelabel-'+ id[1]).text(ui.item.data[12]);
             //erp2024 add discount amount 27-03-2025
             var productPrice = parseFloat(ui.item.data[1]);
             maxdiscount =  parseFloat(ui.item.data[12]);
             var discountValue = ((productPrice * maxdiscount) / 100);
             discountValue = discountValue.toFixed(2);
             $('#maxdiscountamount-' + id[1]).val(discountValue);
             $('#maxdiscountratelabel-'+ id[1]).text(ui.item.data[12]+"% ("+discountValue+")");
             
            $('#productname-'+ id[1]).val(ui.item.data[0]);
            $('#income_account_number-'+ id[1]).val(ui.item.data[13]);
            $('#expense_account_number-'+ id[1]).val(ui.item.data[14]);
            $('#product_cost-'+id[1]).val(ui.item.data[15]);
            var fulltitle = ui.item.data[0]+" ("+ui.item.data[7]+")";            
            $('#code-'+ id[1]).attr('title',  fulltitle+' - (New)');
            $('#amount-'+ id[1]).attr('title',  fulltitle+' - Quantity(New)');
            $('#discounttype-'+ id[1]).attr('title',  fulltitle+' - Discount Type(New)');
            $('#discountamt-'+ id[1]).attr('title',  fulltitle+' - Discount Amount(New)');
            $('#discount-'+ id[1]).attr('title',  fulltitle+' - Discount Percentage(New)');
            $('#productname-'+ id[1]).attr('title',  fulltitle+' - Product Name(New)');
            rowTotal(cvalue);
            billUpyog();
            orderdiscount();
            $(".noproduct-section").removeClass('d-none');
            save_changed_values_for_history();

        },
        create: function (e) {
            $(this).prev('.ui-helper-hidden-accessible').remove();
        }
    });
    
    //hide product history button
    $(".producthis").hide();

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
        $("#onhandQty-0").text(parseInt(ui.item.data[8]));        
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
             //check duplicate entry starts
             var newProductID = ui.item.data[7]; // New selected product name
             var duplicateFound = false;      
             var totalRows = parseInt($('#ganak').val()); // total number of rows
             for (var i = 0; i <= totalRows; i++) {
                 if ($('#code-' + i).length) {
                     var existingProductName = $('#code-' + i).val();
                     if (existingProductName == newProductID) {
                         duplicateFound = true;
                         break;
                     }
                 }
             }
             if (duplicateFound) {                
                 duplicate_message(cvalue,newProductID);
                 return false;
             }
             //check duplicate entry ends
            var discount = ui.item.data[4];
            var custom_discount = $('#custom_discount').val();
            if (custom_discount > 0) discount = deciFormat(custom_discount);
            $("#onhandQty-0").text(parseInt(ui.item.data[8]));        
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
            $("#lowestprice-").val(ui.item.data[11]);
            $("#lowestpricelabel-"+cvalue).text(ui.item.data[11]);
            $("#maxdiscountrate-"+cvalue).val(ui.item.data[12]);
            $("#maxdiscountratelabel-"+cvalue).text(ui.item.data[12]);
            $('#code-'+cvalue).val(ui.item.data[7]);
            
            var fulltitle = ui.item.data[0]+" ("+ui.item.data[7]+")";
            $('#code-'+ cvalue).attr('title',  fulltitle+' - (New)');
            $('#amount-'+ cvalue).attr('title',  fulltitle+' - Quantity(New)');
            $('#discounttype-'+ cvalue).attr('title',  fulltitle+' - Discount Type(New)');
            $('#discountamt-'+ cvalue).attr('title',  fulltitle+' - Discount Amount(New)');
            $('#discount-'+ cvalue).attr('title',  fulltitle+' - Discount Percentage(New)');
            $('#productname-'+ cvalue).attr('title',  fulltitle+' - Product Name(New)');

            rowTotal(cvalue);
            billUpyog();
            $(".noproduct-section").removeClass('d-none');
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
            
            //check duplicate entry starts
            var newProductID = ui.item.data[7]; // New selected product name
            var duplicateFound = false;      
            var totalRows = parseInt($('#ganak').val()); // total number of rows
            for (var i = 0; i <= totalRows; i++) {
                if ($('#code-' + i).length) {
                    var existingProductName = $('#code-' + i).val();
                    if (existingProductName == newProductID) {
                        duplicateFound = true;
                        break;
                    }
                }
            }
            if (duplicateFound) {                
                duplicate_message(cvalue,newProductID);
                return false;
            }
            //check duplicate entry ends

            var fulltitle = ui.item.data[0]+" ("+ui.item.data[7]+")";
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

            //erp2024 add discount amount 27-03-2025
            var productPrice = parseFloat(ui.item.data[1]);
            maxdiscount =  parseFloat(ui.item.data[12]);
            var discountValue = ((productPrice * maxdiscount) / 100);
            discountValue = discountValue.toFixed(2);
            $('#maxdiscountamount-' + id[1]).val(discountValue);
            $('#maxdiscountratelabel-'+ id[1]).text(ui.item.data[12]+"% ("+discountValue+")");
            // $('#maxdiscountratelabel-'+ id[1]).text(ui.item.data[12]);
            // $('#maxdiscountratelabel-'+ id[1]).text(ui.item.data[12]);
            $('#productname-'+ id[1]).val(ui.item.data[0]);
            
            $('#code-'+ id[1]).attr('title',  fulltitle+' - (New)');
            $('#amount-'+ id[1]).attr('title',  fulltitle+' - Quantity(New)');
            $('#discounttype-'+ id[1]).attr('title',  fulltitle+' - Discount Type(New)');
            $('#discountamt-'+ id[1]).attr('title',  fulltitle+' - Discount Amount(New)');
            $('#discount-'+ id[1]).attr('title',  fulltitle+' - Discount Percentage(New)');
            $('#productname-'+ id[1]).attr('title',  fulltitle+' - Product Name(New)');
            rowTotal(cvalue);
            billUpyog();
            save_changed_values_for_history();
            $(".noproduct-section").removeClass('d-none');


        },
        create: function (e) {
            $(this).prev('.ui-helper-hidden-accessible').remove();
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

    data += '<td  class="text-right"><strong><span class=\'ttlText\' id="result-' + cvalue + '">0</span></strong></td> <td class="text-center"> <button onclick="producthistory('+cvalue+')" type="button" class="btn btn-sm btn-secondary producthis"><i class="fa fa-history"></i></button> <button onclick="single_product_details('+cvalue+')" type="button" class="btn btn-sm btn-secondary" title="Product Informations"><i class="fa fa-info"></i></button> <button type="button" data-rowid="' + cvalue + '" class="btn btn-sm btn-secondary removeProd" title="Remove" > <i class="fa fa-trash"></i> </button></td>';

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

    
    //hide product history button
    $(".producthis").hide();

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

    data += '<td  class="text-right"><strong><span class=\'ttlText\' id="result-' + cvalue + '">0</span></strong></td> <td class="text-center"> <button onclick="producthistory('+cvalue+')" type="button" class="btn btn-sm btn-secondary producthis"><i class="fa fa-history"></i></button> <button onclick="single_product_details('+cvalue+')" type="button" class="btn btn-sm btn-secondary"  title="Product Informations"><i class="fa fa-info"></i></button> <button type="button" data-rowid="' + cvalue + '" class="btn btn-sm btn-secondary removeProd" title="Remove" > <i class="fa fa-trash"></i> </button></td>';

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
            var fulltitle = ui.item.data[0]+" ("+ui.item.data[7]+")";
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

    
    //hide product history button
    $(".producthis").hide();

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
            $("#costvaluation_section-"+numb).addClass('d-none');
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
    var lastSerialNumber = parseInt($('#saman-row .serial-number:last').text()) || 0;    
    var nextSerialNumber = lastSerialNumber+1; //_price
    var data = '<tr><td class="serial-number text-center">'+nextSerialNumber+'</td><td><input type="text" class="form-control" name="code[]"  id="code-' + cvalue + '" placeholder="Enter Product Code"></td><td><input type="text" class="form-control productname" name="product_name[]" placeholder="Search by Product Name or Item No." id="productname-' + cvalue + '" ></td><td><input type="number" class="form-control req amnt" name="product_qty[]" id="amount-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog(),save_changed_values_for_history()" autocomplete="off" value="1"  inputmode="numeric"><input type="hidden" id="alert-' + cvalue + '" value=""  name="alert[]"> </td><td><input type="number" class="form-control" name="damage[]" id="damage-' + cvalue + '" onkeypress="return isNumber(event)"  autocomplete="off" value="0"  inputmode="numeric" onkeyup="damageqtycheck(' + cvalue + ')"></td><td><input type="text" class="form-control text-right req prc responsive-width-elements" name="product_price[]" id="price-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog(),save_changed_values_for_history()" autocomplete="off" inputmode="numeric"></td>  <td class="text-right"><strong><span class=\'ttlText\' id="result-' + cvalue + '">0</span></strong></td> <td class="text-center"><button type="button" data-rowid="' + cvalue + '" class="btn btn-sm btn-default removeProd" title="Remove" > <i class="fa fa-trash"></i> </button> </td><input type="hidden" name="taxa[]" id="taxa-' + cvalue + '" value="0"><input type="hidden" name="disca[]" id="disca-' + cvalue + '" value="0"><input type="hidden" class="ttInput" name="product_subtotal[]" id="total-' + cvalue + '" value="0"> <input type="hidden" class="pdIn" name="pid[]" id="pid-' + cvalue + '" value="0"> <input type="hidden" name="unit[]" id="unit-' + cvalue + '" value="" class="responsive-width-elements"> <input type="hidden" name="hsn[]" id="hsn-' + cvalue + '" value=""> <input type="hidden" name="serial[]" id="serial-' + cvalue + '" value=""> <input type="hidden" class="form-control vat" name="product_tax[]" id="vat-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" inputmode="numeric"><input type="text" class="form-control discount" name="product_discount[]" onkeypress="return isNumber(event)" id="discount-' + cvalue + '" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off"></tr>';

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
            
            var fulltitle = ui.item.data[0]+" ("+ui.item.data[7]+")";
            $('#code-'+ id[1]).attr('title',  fulltitle+' - (New)');
            $('#amount-'+ id[1]).attr('title',  fulltitle+' - Quantity(New)');
            $('#price-'+ id[1]).attr('title',  fulltitle+' - Price(New)');
            $('#productname-'+ id[1]).attr('title',  fulltitle+' - Product Name(New)');
            save_changed_values_for_history();
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
            $('#pid-' + id[1]).val(ui.item.data[2]);
            $('#vat-' + id[1]).val(t_r);
            $('#discount-' + id[1]).val(discount);
            $('#dpid-' + id[1]).val(ui.item.data[5]);
            $('#unit-' + id[1]).val(ui.item.data[6]);
            $('#hsn-' + id[1]).val(ui.item.data[7]);
            $('#alert-' + id[1]).val(parseInt(ui.item.data[8]));
            $('#serial-' + id[1]).val(ui.item.data[10]);
            $('#productname-' + id[1]).val(ui.item.data[0]);
            rowTotal(cvalue);
            billUpyog();              
            var fulltitle = ui.item.data[0]+" ("+ui.item.data[7]+")";
            $('#code-'+ id[1]).attr('title',  fulltitle+' - (New)');
            $('#amount-'+ id[1]).attr('title',  fulltitle+' - Quantity(New)');
            $('#price-'+ id[1]).attr('title',  fulltitle+' - Price(New)');
            $('#productname-'+ id[1]).attr('title',  fulltitle+' - Product Name(New)');
            save_changed_values_for_history();
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
        $("#onhandQty-0").text(parseInt(ui.item.data[8]));
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
    var $this = $(this);
    if ($(e.target).closest('a.expand-link').length > 0) {
        return; 
    }

    e.preventDefault(); 
    
    var target = $this.data('target'); 
    $(target).slideToggle();

    var $headerExpandBtn = $this.closest('.header-expand-btn');
    if ($headerExpandBtn.find('i').hasClass('fa-angle-down')) {
        $headerExpandBtn.find('i').removeClass('fa-angle-down').addClass('fa-angle-up');
    } else {
        $headerExpandBtn.find('i').removeClass('fa-angle-up').addClass('fa-angle-down');
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
             //check duplicate entry starts
            var newProductID = ui.item.data[7]; // New selected product name
            var duplicateFound = false;      
            var totalRows = parseInt($('#ganak').val()); // total number of rows
            for (var i = 0; i <= totalRows; i++) {
                if ($('#code-' + i).length) {
                    var existingProductName = $('#code-' + i).val();
                    if (existingProductName == newProductID) {
                        duplicateFound = true;
                        break;
                    }
                }
            }
            if (duplicateFound) {                
                duplicate_message(cvalue,newProductID);
                return false;
            }
            //check duplicate entry ends
            if ($("#taxformat option:selected").attr('data-trate')) {
    
                t_r = $("#taxformat option:selected").attr('data-trate');
            }
            var discount = ui.item.data[4];
            var custom_discount = $('#custom_discount').val();
            if (custom_discount > 0) discount = deciFormat(custom_discount);
            $("#onhandQty-0").text(parseInt(ui.item.data[8]));        
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
            var fulltitle = ui.item.data[0]+" ("+ui.item.data[7]+")";
            $('#code-'+ cvalue).attr('title',  fulltitle+' - (New)');
            $('#amount-'+ cvalue).attr('title',  fulltitle+' - Quantity(New)');
            $('#discounttype-'+ cvalue).attr('title',  fulltitle+' - Discount Type(New)');
            $('#discountamt-'+ cvalue).attr('title',  fulltitle+' - Discount Amount(New)');
            $('#discount-'+ cvalue).attr('title',  fulltitle+' - Discount Percentage(New)');
            $('#productname-'+ cvalue).attr('title',  fulltitle+' - Product Name(New)');
            save_changed_values_for_history();
            rowTotal(0);
            billUpyog();
            $(".noproduct-section").removeClass('d-none');
        }
    });
    $('#code-'+cvalue).autocomplete({
    
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
            var t_r = ui.item.data[3];
             //check duplicate entry starts
            var newProductID = ui.item.data[7]; // New selected product name
            var duplicateFound = false;      
            var totalRows = parseInt($('#ganak').val()); // total number of rows
            for (var i = 0; i <= totalRows; i++) {
                if ($('#code-' + i).length) {
                    var existingProductName = $('#code-' + i).val();
                    if (existingProductName == newProductID) {
                        duplicateFound = true;
                        break;
                    }
                }
            }
            if (duplicateFound) {                
                duplicate_message(cvalue,newProductID);
                return false;
            }
            //check duplicate entry ends
            if ($("#taxformat option:selected").attr('data-trate')) {
    
                t_r = $("#taxformat option:selected").attr('data-trate');
            }
            var discount = ui.item.data[4];
            var custom_discount = $('#custom_discount').val();
            if (custom_discount > 0) discount = deciFormat(custom_discount);
            $("#onhandQty-0").text(parseInt(ui.item.data[8]));        
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
            $('#productname-'+ cvalue).val(ui.item.data[0]);
            var fulltitle = ui.item.data[0]+" ("+ui.item.data[7]+")";
            $('#code-'+ cvalue).attr('title',  fulltitle+' - (New)');
            $('#amount-'+ cvalue).attr('title',  fulltitle+' - Quantity(New)');
            $('#discounttype-'+ cvalue).attr('title',  fulltitle+' - Discount Type(New)');
            $('#discountamt-'+ cvalue).attr('title',  fulltitle+' - Discount Amount(New)');
            $('#discount-'+ cvalue).attr('title',  fulltitle+' - Discount Percentage(New)');
            $('#productname-'+ cvalue).attr('title',  fulltitle+' - Product Name(New)');
            save_changed_values_for_history();
            rowTotal(0);
            billUpyog();
            $(".noproduct-section").removeClass('d-none');
        }
    });
}


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
    
        data += '<td  class="text-right"><strong><span class=\'ttlText\' id="result-' + cvalue + '">0</span></strong></td> <td class="text-center"> <button onclick="producthistory('+cvalue+')" type="button" class="btn btn-sm btn-secondary producthis"><i class="fa fa-history"></i></button> <button onclick="single_product_details('+cvalue+')" type="button" class="btn btn-sm btn-secondary"  title="Product Informations"><i class="fa fa-info"></i></button> <button type="button" data-rowid="' + cvalue + '" class="btn btn-sm btn-secondary removeProd" title="Remove" > <i class="fa fa-trash"></i> </button></td>';
    
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
        
        //hide product history button
        $(".producthis").hide();
    
    });


$('#productname').autocomplete({
    source: function (request, response) {
        $.ajax({
            url: baseurl + 'search_products/searchinproduct',
            dataType: "json",
            method: 'post',
            data: 'name_startsWith=' + request.term + '&type=product_list&row_num=1',
            // data: 'name_startsWith=' + request.term + '&type=product_list&row_num=1&wid=' + $("#s_warehouses option:selected").val() + '&' + d_csrf,
            success: function (data) {               
                response($.map(data, function (item) {
                    var product_d = item[0];
                    // var product_d = item[0]+" ("+item[7]+")";
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
       
        $('#pid-0').val(ui.item.data[2]);
        $('#productcode').val(ui.item.data[7]);
        rowTotal(0);
        billUpyog();
    }
});


$('#productcode').autocomplete({
    source: function (request, response) {
        $.ajax({
            url: baseurl + 'search_products/searchinproduct',
            dataType: "json",
            method: 'post',
            data: 'name_startsWith=' + request.term + '&type=product_list&row_num=1',
            // data: 'name_startsWith=' + request.term + '&type=product_list&row_num=1&wid=' + $("#s_warehouses option:selected").val() + '&' + d_csrf,
            success: function (data) {               
                response($.map(data, function (item) {
                    var product_d = item[7];
                    // var product_d = item[0]+" ("+item[7]+")";
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
       
        $('#pid-0').val(ui.item.data[2]);
        $('#productname').val(ui.item.data[0]);
       
        rowTotal(0);
        billUpyog();
    }
});


//erp2024 expense claim add row starts

$('#addexpenseproduct').on('click', function () {
    var cvalue = parseInt($('#ganak').val()) + 1;
    var nxt = parseInt(cvalue);
    $('#ganak').val(nxt); 
    var functionNum = "'" + cvalue + "'";
    count = $('#saman-row div').length; 
   

    var data = '<tr><td><input type="text" placeholder="Search by Item No." class="form-control" name="code[]" id="expensecode-' + cvalue + '" value=""><input type="hidden" class="form-control" name="expense_account_number[]" id="expense_account_number-' + cvalue + '"  readonly><input type="hidden" class="form-control" name="hsn[]" id="hsn-' + cvalue + '" value="" readonly></td><td><span class="d-flex"><input type="text" class="form-control" name="product_name[]" placeholder="Search by Product Name" id="expenseproductname-' + cvalue + '">';
    
    data += '&nbsp;<button type="button" title="change account" class="btn btn-sm btn-secondary" id="btnclk-' + cvalue + '" data-toggle="popover" onclick="loadPopover(' + cvalue +')" data-html="true" data-content=\'<form id="popoverForm-' + cvalue + '"><div class="form-group"><label for="accountList-' + cvalue + '">Select Account</label><select class="form-control" id="accountList-' + cvalue + '"></select></div><div class="text-right"><button type="button" onclick="cancelPopover(' + cvalue + ')" class="btn btn-secondary btn-sm">Cancel</button>&nbsp;<button type="button" onclick="change_product_account(' + cvalue + ')" class="btn btn-primary btn-sm">Change</button></div></form>\'><i class="fa fa-bank"></i></span></button></td>';


     data += '</td><td><input type="number" class="form-control req amnt" name="product_qty[]" id="amount-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" value="0"  inputmode="numeric"><input type="hidden" id="alert-' + cvalue + '" value=""  name="alert[]"> </td><td><input type="text" class="form-control req prc text-right" name="product_price[]" id="price-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="checkCost(' + functionNum + '), rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" inputmode="numeric" ></td><td class="d-none"> <input type="text" class="form-control vat" name="product_tax[]" id="vat-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" inputmode="numeric"></td> <td id="texttaxa-' + cvalue + '" class="text-center d-none">0</td> <td class="d-none"><input type="text" class="form-control discount" name="product_discount[]" onkeypress="return isNumber(event)" id="discount-' + cvalue + '" onkeyup="discountWithTotal(' + functionNum + ')" autocomplete="off" ></td> <td class="d-none"><input type="hidden" class="form-control" name="foc[]" onkeypress="return isNumber(event)" id="foc-' + cvalue + '" ></td> <td class="text-right"><strong><span class=\'ttlText\' id="result-' + cvalue + '">0</span></strong> <div class="costvaluation_section" id="costvaluation_section-' + cvalue + '"><strong class="text-danger" id="cost_warning_val-' + cvalue + '"></strong></div></td> <td class="text-center1"><button onclick="single_product_details(' + cvalue + ')" type="button" class="btn btn-sm btn-secondary" title="Product Informations"><i class="fa fa-info"></i></button> <button type="button" data-rowid="' + cvalue + '" class="btn btn-sm btn-secondary removeProd" title="Remove" > <i class="fa fa-trash"></i> </button> </td><input type="hidden" name="taxa[]" id="taxa-' + cvalue + '" value="0"><input type="hidden" name="disca[]" id="disca-' + cvalue + '" value="0"><input type="hidden" class="ttInput" name="product_subtotal[]" id="total-' + cvalue + '" value="0"> <input type="hidden" class="pdIn" name="pid[]" id="pid-' + cvalue + '" value="0"> <input type="hidden" name="unit[]" id="unit-' + cvalue + '" value=""> <input type="hidden" name="serial[]" id="serial-' + cvalue + '" value=""></tr>';

   
    //ajax request
    // $('#saman-row').append(data);
    $('tr.last-item-row').before(data);
    row = cvalue;

    $('#expenseproductname-' + cvalue).autocomplete({        
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
            $('#amount-'+ id[1]).val(1);
            $('#price-' + id[1]).val(ui.item.data[1]);
            // $('#prdcost-' + id[1]).val(ui.item.data[1]);
            $('#pid-' + id[1]).val(ui.item.data[2]);
            $('#vat-' + id[1]).val(t_r);
            $('#discount-' + id[1]).val(discount);
            $('#dpid-' + id[1]).val(ui.item.data[5]);
            $('#unit-' + id[1]).val(ui.item.data[6]);
            $('#hsn-' + id[1]).val(ui.item.data[7]);
            $('#expensecode-' + id[1]).val(ui.item.data[7]);
            $('#alert-' + id[1]).val(parseInt(ui.item.data[8]));
            $('#serial-' + id[1]).val(ui.item.data[10]);
            $('#expense_account_number-' + id[1]).val(ui.item.data[10]);
            rowTotal(cvalue);
            billUpyog();
            expenseclaim_discount();

        },
        create: function (e) {
            $(this).prev('.ui-helper-hidden-accessible').remove();
        }
    });

    $('#expensecode-' + cvalue).autocomplete({        
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
            $('#amount-'+ id[1]).val(1);
            $('#price-' + id[1]).val(ui.item.data[1]);
            // $('#price-' + id[1]).val(ui.item.data[1]);
            // $('#prdcost-' + id[1]).val(ui.item.data[1]);
            $('#pid-' + id[1]).val(ui.item.data[2]);
            $('#vat-' + id[1]).val(t_r);
            $('#discount-' + id[1]).val(discount);
            $('#dpid-' + id[1]).val(ui.item.data[5]);
            $('#unit-' + id[1]).val(ui.item.data[6]);
            $('#expenseproductname-' + id[1]).val(ui.item.data[0]);
            $('#hsn-' + id[1]).val(ui.item.data[7]);
            $('#alert-' + id[1]).val(parseInt(ui.item.data[8]));
            // $('#serial-' + id[1]).val(ui.item.data[10]);
            $('#expense_account_number-' + id[1]).val(ui.item.data[10]); 
            rowTotal(cvalue);
            billUpyog();
            expenseclaim_discount();


        },
        create: function (e) {
            $(this).prev('.ui-helper-hidden-accessible').remove();
        }
    });
    // Add functionality to the button popover
    $('#btnclk-' + cvalue).popover();
});



$('#expensecode-0').autocomplete({
  
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
            $('#amount-0').val(1);
            $('#price-0').val(ui.item.data[1]);
            // $('#prdcost-0').val(ui.item.data[1]);
            $('#pid-0').val(ui.item.data[2]);
            $('#vat-0').val(t_r);
            $('#discount-0').val(discount);
            $('#dpid-0').val(ui.item.data[5]);
            $('#unit-0').val(ui.item.data[6]);
            $('#hsn-0').val(ui.item.data[7]);            
            $("#expenseproductname-0").val(ui.item.data[0]); 
            $('#alert-0').val(parseInt(ui.item.data[8]));
            $('#serial-0').val(ui.item.data[10]);
            $('#expense_account_number-0').val(ui.item.data[10]);
            rowTotal(0);
            billUpyog();
    }
});



$('#expenseproductname-0').autocomplete({
    
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
        $('#amount-0').val(1);
        $('#price-0').val(ui.item.data[1]);
        // $('#prdcost-0').val(ui.item.data[1]);
        $('#pid-0').val(ui.item.data[2]);
        $('#vat-0').val(t_r);
        $('#discount-0').val(discount);
        $('#dpid-0').val(ui.item.data[5]);
        $('#unit-0').val(ui.item.data[6]);
        $('#hsn-0').val(ui.item.data[7]);
        $('#expensecode-0').val(ui.item.data[7]);
        $('#alert-0').val(parseInt(ui.item.data[8]));
        $('#serial-0').val(ui.item.data[10]);
        $('#expense_account_number-0').val(ui.item.data[10]);
        rowTotal(0);
        billUpyog();
    }
});

function expenseclaim_discount() {
    function parseCurrency(value) {
        return parseFloat(value.replace(/,/g, '')) || 0;
    }

    var order_discount = parseCurrency($("#order_discount").val());
    var grandproductamount = parseCurrency($("#grandamount").text());
    var discount_type = $('#discount_type').val();
    var discountAmount = 0;
    var currentnet;

    if(order_discount>0)
    {
        if (discount_type === "Percentage") {
            if (order_discount > 100) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Invalid Discount',
                    text: 'Percentage discount cannot exceed 100%.',
                });
                $("#order_discount").val("");
                $("#invoiceyoghtml").val(grandproductamount.toFixed(2));
                $("#grandtotaltext").text(grandproductamount.toFixed(2));
                return;
            }
            // Calculate percentage discount
            discountAmount = (grandproductamount * order_discount) / 100;
            currentnet = grandproductamount - discountAmount;
        } else {
            currentnet = grandproductamount - order_discount;
            discountAmount = order_discount;
        }

        if (isNaN(order_discount) || order_discount > grandproductamount || currentnet==0) {
            $("#order_discount").val("");
            $("#invoiceyoghtml").val(grandproductamount.toFixed(2));
            $("#grandtotaltext").text(grandproductamount.toFixed(2));
            Swal.fire({
                icon: 'warning',
                title: 'Discount is too large',
                text: 'The discount amount cannot exceed the subtotal.',
            });
            return;
        }

    }
    else{
        currentnet = grandproductamount;
    }
    // Update the grand total
    $("#grandtotaltext").text(currentnet.toFixed(2));
    $("#invoiceyoghtml").val(currentnet.toFixed(2));
    $("#claim_discount_amount").val(discountAmount.toFixed(2));
}

function convert_order_discount_percentage_to_amount1() {
    function parseCurrency(value) {
        return parseFloat(value.replace(/,/g, '')) || 0;
    }

    var order_discount = parseCurrency($("#order_discount").val());
    var grandproductamount = parseCurrency($("#grandamount").text());
    var totaldiscount = parseCurrency($("#discs").text());
    var order_discount_percentage = $("#order_discount_percentage").val();

    
}

function convert_order_discount_percentage_to_amount() {
    function parseCurrency(value) {
        return parseFloat(value.replace(/,/g, '')) || 0; // Remove commas and parse to float
    }

    var grandproductamount = parseCurrency($("#grandamount").text());
    var grandproduct_discountamount = parseCurrency($("#discs").text());
    var order_discount_percentage = parseFloat($("#order_discount_percentage").val()) || 0;
    var discount_amount = 0;
    var grandtotalamount = 0;

    if (grandproductamount > 0 && order_discount_percentage > 0) {
        discount_amount = (grandproductamount * order_discount_percentage) / 100; // Calculate discount
    }

    // Adjust discount_amount based on the decimal value
    var discount_whole = Math.floor(discount_amount); // Whole number part
    var discount_decimal = discount_amount - discount_whole; // Decimal part

    // if (discount_decimal >= 0.05) {
    //     discount_amount = Math.ceil(discount_amount); // Round up to next integer
    // } else {
    //     discount_amount = discount_whole; // Keep the same integer
    // }
    discount_amount = discount_whole;
    grandtotalamount = grandproductamount - (grandproduct_discountamount + discount_amount);

    // Update DOM elements
    $("#invoiceyoghtml").val(grandtotalamount.toFixed(2));
    $("#grandtotaltext").text(grandtotalamount.toFixed(2));
    $("#order_discount").val(discount_amount.toFixed(2));
    $("#order_discount_text").text(discount_amount.toFixed(2));
}

function convert_shipping_percentage_to_amount() {
    function parseCurrency(value) {
        return parseFloat(value.replace(/,/g, '')) || 0; // Remove commas and parse to float
    }

    var grandproductamount = parseCurrency($("#grandamount").text());
    var shipping_percentage = parseFloat($("#shipping_percentage").val()) || 0;    
    var grandtotaltext = parseCurrency($("#grandtotaltext").text());
    var discount_amount = 0;
    var grandtotalamount = 0;

    if (grandproductamount > 0 && shipping_percentage > 0) {
        discount_amount = (grandproductamount * shipping_percentage) / 100; // Calculate discount
    }

    // Adjust discount_amount based on the decimal value
    var discount_whole = Math.floor(discount_amount); // Whole number part
    var discount_decimal = discount_amount - discount_whole; // Decimal part

    // if (discount_decimal >= 0.05) {
    //     discount_amount = Math.ceil(discount_amount); // Round up to next integer
    // } else {
    //     discount_amount = discount_whole; // Keep the same integer
    // }
    discount_amount = discount_whole;
    grandtotalamount = grandtotaltext + discount_amount;

    // Update DOM elements
    $("#invoiceyoghtml").val(grandtotalamount.toFixed(2));
    $("#grandtotaltext").text(grandtotalamount.toFixed(2));
    $("#shipping_amount").val(discount_amount.toFixed(2));
    $("#shipping_text_value").text(discount_amount.toFixed(2));
}

function hasAnychanges()
{
    var hasUnsavedChanges = "success";
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
        return hasUnsavedChanges
}




//erp2024 expense claim add row ends

function save_changed_values_for_history()
{

    document.querySelectorAll('input, textarea, select').forEach((input) => {
        input.addEventListener('change', function () {
            
            const fieldId = this.id || this.name; // Use `name` for grouped radio buttons
            const originalValue = this.getAttribute('data-original-value');
            var label = $('label[for="' + fieldId + '"]');
            var field_label = label.text();
            if (!field_label.trim()) {
                field_label = this.getAttribute('title') || 'Unknown Field';
            }
            if (this.type === 'checkbox') {
                // For checkboxes, use the "checked" state
                const newValue = this.checked ? this.value : null;
                const originalChecked = originalValue === this.value;

                if (originalChecked !== this.checked) {
                    changedFields[fieldId] = {
                        oldValue: originalChecked ? this.value : null,
                        newValue: newValue,
                        fieldlabel : field_label
                    }; // Track changes
                } else {
                    delete changedFields[fieldId]; // Remove if no change
                }
            } else if (this.type === 'radio') {
                // For radio buttons, track the selected option
                if (this.checked) {
                    const newValue = this.value;
                    if (originalValue !== newValue) {
                        changedFields[fieldId] = {
                            oldValue: originalValue,
                            newValue: newValue,
                            fieldlabel : field_label
                        };
                    } else {
                        delete changedFields[fieldId]; // Remove if no change
                    }
                }
            } else if (this.type === 'number') {
                // For numeric fields
                const newValue = parseFloat(this.value);
                const originalNumber = parseFloat(originalValue);

                if (!isNaN(originalNumber) && !isNaN(newValue) && originalNumber !== newValue) {
                    changedFields[fieldId] = {
                        oldValue: originalNumber,
                        newValue: newValue,
                        fieldlabel : field_label
                    };
                } else {
                    delete changedFields[fieldId]; // Remove if no change
                }
            } else {
                // For text, textarea, and select fields
                const newValue = this.value;
                if (originalValue !== newValue) {
                    changedFields[fieldId] = {
                        oldValue: originalValue,
                        newValue: newValue,
                        fieldlabel : field_label
                    };
                } else {
                    delete changedFields[fieldId]; // Remove if no change
                }
            }
        });
    }); 
}


$('#purchasereturnproduct-0').autocomplete({
    
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
        //check duplicate entry starts
       var newProductID = ui.item.data[7]; // New selected product name
       var duplicateFound = false;      
       var totalRows = parseInt($('#ganak').val()); // total number of rows
       for (var i = 0; i <= totalRows; i++) {
           if ($('#code-' + i).length) {
               var existingProductName = $('#code-' + i).val();
               if (existingProductName == newProductID) {
                   duplicateFound = true;
                   break;
               }
           }
       }
       if (duplicateFound) {                
           duplicate_message(cvalue=0,newProductID);
           return false;
       }
       //check duplicate entry ends
        var discount = ui.item.data[4];
        var custom_discount = $('#custom_discount').val();
        if (custom_discount > 0) discount = deciFormat(custom_discount);
        $("#onhandQty-0").text(parseInt(ui.item.data[8]));
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
        $("#purchasereturncode-0").val(ui.item.data[7]); 
        $('#taxlabel-0').text(t_r+" / ");        
        $('#income_account_number-0').val(ui.item.data[13]);
        $('#expense_account_number-0').val(ui.item.data[14]);
        $('#product_cost-0').val(ui.item.data[15]);
        // rowTotal(0);
        // billUpyog();
        // orderdiscount();
        $("#grandtotaltext").text(ui.item.data[1]);
        $(".invoiceyoghtml").val(ui.item.data[1]);
        $("#result-0").text(ui.item.data[1]);
        $("#total-0").val(ui.item.data[1]);
    }
});


$('#purchasereturncode-0').autocomplete({
  
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
       //check duplicate entry starts
       var newProductID = ui.item.data[7]; // New selected product name
       var duplicateFound = false;      
       var totalRows = parseInt($('#ganak').val()); // total number of rows
       for (var i = 0; i <= totalRows; i++) {
           if ($('#code-' + i).length) {
               var existingProductName = $('#code-' + i).val();
               if (existingProductName == newProductID) {
                   duplicateFound = true;
                   break;
               }
           }
       }
       if (duplicateFound) {                
           duplicate_message(cvalue=0,newProductID);
           return false;
       }
       //check duplicate entry ends
        if ($("#taxformat option:selected").attr('data-trate')) {

            t_r = $("#taxformat option:selected").attr('data-trate');
        }
        var discount = ui.item.data[4];
        var custom_discount = $('#custom_discount').val();
        if (custom_discount > 0) discount = deciFormat(custom_discount);
        $("#onhandQty-0").text(parseInt(ui.item.data[8]));
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
        $("#purchasereturnproduct-0").val(ui.item.data[0]); 
        $('#taxlabel-0').text(t_r+" / ");
        $('#income_account_number-0').val(ui.item.data[13]);
        $('#expense_account_number-0').val(ui.item.data[14]);
        $('#product_cost-0').val(ui.item.data[15]);
        $("#grandtotaltext").text(ui.item.data[1]);
        $(".invoiceyoghtml").val(ui.item.data[1]);
        
        $("#result-0").text(ui.item.data[1]);
        $("#total-0").val(ui.item.data[1]);
        rowTotal(0);
        billUpyog();
        // orderdiscount();
    }
});

$(document).on('input', '.damaged_qty', function () {
    let numb = $(this).attr('id').split('-')[1]; // Extract number from ID
    let damagedQty = parseInt($(this).val()) || 0;

    // Fetch delivered quantity safely
    let deliveredQty = parseInt($("#delivered_qty-" + numb).val()) || 0; 

    console.log("Damaged:", damagedQty, "Delivered:", deliveredQty); // Debugging

    if (damagedQty > deliveredQty) {
        Swal.fire({
            icon: 'error',
            title: 'Invalid Input',
            text: 'Damaged Quantity is greater than Delivered Quantity',
            confirmButtonText: 'OK'
        });

        $(this).val(0);
    }
});

//creditlimit-check
function credit_limit_with_grand_total() {
    var total = parseFloat($("#invoiceyoghtml").val().replace(/,/g, '').trim());
    var available_credit_limit = parseFloat($("#available_credit").val().replace(/,/g, '').trim());
    var textdata="";
    var counter = parseInt($("#ganak").val()); // Get the total number of rows
    counter = counter + 1; // Ensure it covers all dynamically created elements
   
    for (var z = 0; z < counter; z++) {       
        var entered_qty = parseInt($("#amount-" + z).val()) || 0;
        var onhandQty = parseInt($("#onhandQty-"+ z).text()) || 0; 
        var productname = $("#productname-" + z).val();
        var code = $("#code-" + z).val();

        //for sales order
        // alert(oldproductqty);
    //    if()
    //    {
    //     oldproductqty
    //    }
    // $("#amount-" + z).css("border", "");
        $("#amount-" + z).removeClass("elementborder");
        if (entered_qty > onhandQty) {
            // $("#amount-" + z).val(onhandQty); 
            // rowTotal(z);
            // billUpyog();
            // orderdiscount();
            textdata = '<div class="alert alert-danger">The on-hand quantity of '+productname+'('+code+')'+' is '+onhandQty+' but you entered '+entered_qty+'.</div>';
            
            // Find the closest tooltip inside the same <td>
            var tooltip = $("#amount-" + z).closest("td").find(".tooltip1");

            if (tooltip.length) {
                var message = "The on-hand quantity of " + productname + " (" + code + ") is " + onhandQty + 
                      " but you entered " + entered_qty;
                    tooltip.text(message);
                     tooltip.stop(true, true)
                    .css({ "display": "block", "opacity": "1" }) // Ensure it's visible
                    .fadeIn(200);

                // Auto-hide tooltip after 3 seconds
                // setTimeout(function () {
                //     tooltip.fadeOut(500, function () {
                //         $(this).css({ "display": "none", "opacity": "0" }); // Hide completely
                //     });
                // }, 3000);
            }


            $(".avail_creditlimit").addClass('text-danger');
            $(".creditlimit-btn").addClass('disable-class_credit_check');        
            $(".sub-btn").addClass('disable-class_credit_check');
            $(".creditlimit-check").html(textdata);
            $("#amount-" + z).addClass("elementborder");
            return; // Exit the function immediately
        }
        else{
            var tooltip = $("#amount-" + z).closest("td").find(".tooltip1");
            tooltip.stop(true, true).fadeOut(200, function () {
                $(this).css({ "display": "none", "opacity": "0" });
            });
        }
    }


    // Check if the parsed values are valid numbers
    if (isNaN(total) || isNaN(available_credit_limit)) {
        textdata = '<div class="alert alert-warning">Invalid numbers. Please check the values again.</div>';
    } else if (total > available_credit_limit) {
        // Format total for display with 2 decimal places
        textdata = '<div class="btn-group alert alert-danger mb-0">The Grand Total Amount of ' + total.toFixed(2) + ' exceeds the Available Credit Limit of ' + available_credit_limit.toFixed(2) + '. Please review.</div>';
        $(".avail_creditlimit").addClass('text-danger');
        $(".creditlimit-btn").addClass('disable-class_credit_check');        
        $(".sub-btn").addClass('disable-class_credit_check');
        // $(".creditlimit-btn").addClass('disable-class');        
        // $(".sub-btn").addClass('disable-class');
    } else {
        // Format total for display with 2 decimal places
        // textdata = '<div class="alert alert-success mb-0">The Available Credit Limit of ' + available_credit_limit.toFixed(2) + ' is sufficient for the Grand Total Amount of ' + total.toFixed(2) + '. Please proceed.</div>';
        $(".avail_creditlimit").removeClass('text-danger');
        $(".creditlimit-btn").removeClass('disable-class_credit_check');
        $(".sub-btn").removeClass('disable-class_credit_check');
        // $(".creditlimit-btn").removeClass('disable-class');
        // $(".sub-btn").removeClass('disable-class');
    }

    $(".creditlimit-check").html(textdata);
}


//07-04-2025 chack on hand quantity
//creditlimit-check
function check_on_hand_quantity() {
    

    var textdata="";
    var counter = parseInt($("#ganak").val()); // Get the total number of rows
    counter = counter + 1; // Ensure it covers all dynamically created elements
    for (var z = 0; z < counter; z++) {       
        var entered_qty = parseInt($("#amount-" + z).val()) || 0;
        var onhandQty = parseInt($("#onhandQty-"+ z).text()) || 0; 
        var productname = $("#productname-" + z).val();
        var code = $("#code-" + z).val();
        $("#amount-" + z).removeClass("elementborder");
        if (entered_qty > onhandQty) {
            textdata = '<div class="alert alert-danger">The on-hand quantity of '+productname+'('+code+')'+' is '+onhandQty+' but you entered '+entered_qty+'.</div>';
            var tooltip = $("#amount-" + z).closest("td").find(".tooltip1");

            if (tooltip.length) {
                var message = "The on-hand quantity of " + productname + " (" + code + ") is " + onhandQty + 
                      " but you entered " + entered_qty;
                    tooltip.text(message);
                     tooltip.stop(true, true)
                    .css({ "display": "block", "opacity": "1" }) // Ensure it's visible
                    .fadeIn(200);
            }

            $(".sub-btn").addClass('disable-class_credit_check');
            // $("#creditlimit-check").html(textdata);
            $("#amount-" + z).addClass("elementborder");
            return; 
        }
        else{
            var tooltip = $("#amount-" + z).closest("td").find(".tooltip1");
            tooltip.stop(true, true).fadeOut(200, function () {
                $(this).css({ "display": "none", "opacity": "0" });
            });
            $(".sub-btn").removeClass('disable-class_credit_check');
        }
    }

    $(".creditlimit-check").html(textdata);
}
$('#sales_order_create_btn').on('click', function () {
    
    var cvalue = parseInt($('#ganak').val()) + 1;
    var configured_tax = parseInt($('#configured_tax').val());
    var nxt = parseInt(cvalue);
    $('#ganak').val(nxt);
    var functionNum = "'" + cvalue + "'";
    count = $('#saman-row div').length;
    var lastSerialNumber = parseInt($('#saman-row .serial-number:last').text()) || 0;    
    var nextSerialNumber = lastSerialNumber+1;
    var discount_flg = $('.discount_flg').val();
    var dnoneclass = (discount_flg==1) ? "" : "d-none";
    //product_qty[]
    if($("#action_type").val()=="Edit")
    {
        var data = '<tr><td class="text-center serial-number">'+nextSerialNumber+'</td><td><input type="text" class="form-control code" name="code[]" placeholder="Item No."  id="code-' + cvalue + '"><input type="hidden" class="form-control" name="income_account_number[]" id="income_account_number-' + cvalue + '" ><input type="hidden" class="form-control" name="expense_account_number[]" id="expense_account_number-' + cvalue + '" ><input type="hidden" class="form-control" name="product_cost[]" id="product_cost-' + cvalue + '" ></td>';
    }
    else{
        var data = '<tr><td class="text-center serial-number">'+nextSerialNumber+'</td><td><input type="text" class="form-control code" name="code[]" placeholder="Item No."  id="code-' + cvalue + '"><input type="hidden" class="form-control" name="income_account_number[]" id="income_account_number-' + cvalue + '" ><input type="hidden" class="form-control" name="expense_account_number[]" id="expense_account_number-' + cvalue + '" ><input type="hidden" class="form-control" name="product_cost[]" id="product_cost-' + cvalue + '" ></td>';
    }
   

    data += '<td><input type="text" class="form-control productname" name="product_name[]" placeholder="Search by Product Name or Item No." id="productname-' + cvalue + '"></td>';

    data += '<td class="text-right"><strong id="pricelabel-' + cvalue + '"></strong><input type="hidden" class="form-control" name="lowest_price[]" id="lowestprice-' + cvalue + '" onkeypress="return isNumber(event)" autocomplete="off"></td>';
    
    data += '<td class="text-right"><strong id="lowestpricelabel-' + cvalue + '"></strong><input type="hidden" class="form-control req prc" name="product_price[]" id="price-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog(), orderdiscount()" autocomplete="off" inputmode="numeric"></td>';
    
    data+='<td class="text-center"><strong id="maxdiscountratelabel-' + cvalue + '"></strong></td>';

    data += '<td class="position-relative"><input type="number" class="form-control req amnt product_qty" name="product_qty[]" id="amount-' + cvalue + '" maxlength="6" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog(), orderdiscount(),credit_limit_with_grand_total()" autocomplete="off" value="0"  inputmode="numeric"><input type="hidden" id="alert-' + cvalue + '" value=""  name="alert[]"><div class="tooltip1"></div></td>';

    

    
    if(configured_tax!=0){
        data += '<td class="text-center"> <input type="hidden" class="form-control vat" name="product_tax[]" id="vat-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog(), orderdiscount()" autocomplete="off" inputmode="numeric"><strong id="taxlabel-' + cvalue + '"></strong>&nbsp;<strong  id="texttaxa-' + cvalue + '"></strong></td> ';
    }
    else{
        var hiddendata = '<input type="text" class="form-control vat" name="product_tax[]" id="vat-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog(), orderdiscount()" autocomplete="off" inputmode="numeric">';
    }
    
    
    data+='<td class="text-center discountcoloumn '+dnoneclass+'"><div class="input-group text-center"><select name="discount_type[]" id="discounttype-' + cvalue + '" class="form-control" onchange="discounttypeChange(' + cvalue + '),orderdiscount()"><option value="Perctype">%</option><option value="Amttype">Amt</option></select>&nbsp;<input type="number" min="0" class="form-control discount" name="product_discount[]" onkeypress="return isNumber(event)" id="discount-' + cvalue + '"  autocomplete="off" onkeyup="discounttypeChange(' + cvalue + '), orderdiscount()"><input type="number" min="0" class="form-control discount d-none" name="product_amt[]" onkeypress="return isNumber(event)" id="discountamt-' + cvalue + '" autocomplete="off" onkeyup="discounttypeChange(' + cvalue + '),orderdiscount()"></div><strong class="discount-amtlabel" id="discount-amtlabel-' + cvalue + '"></strong><div><strong id="discount-error-' + cvalue + '"></strong></div></td>';

    data += '<td class="text-center"><strong id="onhandQty-' + cvalue + '"></strong></td>'; 

    data += '<td  class="text-right"><strong><span class=\'ttlText\' id="result-' + cvalue + '">0</span></strong></td>'; 

    data += '<td> <button onclick="producthistory('+cvalue+')" type="button" class="btn btn-sm btn-secondary producthis"><i class="fa fa-history"></i></button> <button onclick="single_product_details('+cvalue+')" type="button" class="btn btn-sm btn-secondary"  title="Product Informations"><i class="fa fa-info"></i></button> <button type="button" data-rowid="' + cvalue + '" class="btn btn-sm btn-secondary removeProd" title="Remove" > <i class="fa fa-trash"></i> </button></td>';

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
            //check duplicate entry starts
            var newProductID = ui.item.data[7]; // New selected product name
            var duplicateFound = false;      
            var totalRows = parseInt($('#ganak').val()); // total number of rows
            for (var i = 0; i <= totalRows; i++) {
                if ($('#code-' + i).length) {
                    var existingProductName = $('#code-' + i).val();
                    if (existingProductName == newProductID) {
                        duplicateFound = true;
                        break;
                    }
                }
            }
            if (duplicateFound) {                
                duplicate_message(cvalue,newProductID);
                return false;
            }
            //check duplicate entry ends
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

             //erp2024 add discount amount 27-03-2025
             var productPrice = parseFloat(ui.item.data[1]);
             maxdiscount =  parseFloat(ui.item.data[12]);
             var discountValue = ((productPrice * maxdiscount) / 100);
             discountValue = discountValue.toFixed(2);
             $('#maxdiscountamount-' + id[1]).val(discountValue);
             $('#maxdiscountratelabel-'+ id[1]).text(ui.item.data[12]+"% ("+discountValue+")");

            // $('#maxdiscountratelabel-'+ id[1]).text(ui.item.data[12]);
            $('#income_account_number-'+ id[1]).val(ui.item.data[13]);
            $('#expense_account_number-'+ id[1]).val(ui.item.data[14]);
            $('#product_cost-'+ id[1]).val(ui.item.data[15]);
            $('#code-'+ id[1]).val(ui.item.data[7]);
            rowTotal(cvalue);
            billUpyog();
            orderdiscount();
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
             //check duplicate entry starts
             var totalRows = parseInt($('#ganak').val()); // total number of rows
             var newProductID = ui.item.data[7];
            //   // New selected product name
             var duplicateFound = false;      
             
             for (var i = 0; i <= totalRows; i++) {
                 if ($('#code-' + i).length) {
                     var existingProductName = $('#code-' + i).val();
                     if (existingProductName == newProductID) {
                         duplicateFound = true;
                         break;
                     }
                 }
             }
             if (duplicateFound) {                
                 duplicate_message(cvalue,newProductID);
                 return false;
             }
             //check duplicate entry ends
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

             //erp2024 add discount amount 27-03-2025
             var productPrice = parseFloat(ui.item.data[1]);
             maxdiscount =  parseFloat(ui.item.data[12]);
             var discountValue = ((productPrice * maxdiscount) / 100);
             discountValue = discountValue.toFixed(2);
             $('#maxdiscountamount-' + id[1]).val(discountValue);
             $('#maxdiscountratelabel-'+ id[1]).text(ui.item.data[12]+"% ("+discountValue+")");
            // $('#maxdiscountratelabel-'+ id[1]).text(ui.item.data[12]);
            $('#productname-'+ id[1]).val(ui.item.data[0]);
            $('#income_account_number-'+ id[1]).val(ui.item.data[13]);
            $('#expense_account_number-'+ id[1]).val(ui.item.data[14]);
            $('#product_cost-'+id[1]).val(ui.item.data[15]);

            var fulltitle = ui.item.data[0]+" ("+ui.item.data[7]+")";
            $('#code-'+ id[1]).attr('title',  fulltitle+' - (New)');
            $('#amount-'+ id[1]).attr('title',  fulltitle+' - Quantity(New)');
            $('#discounttype-'+ id[1]).attr('title',  fulltitle+' - Discount Type(New)');
            $('#discountamt-'+ id[1]).attr('title',  fulltitle+' - Discount Amount(New)');
            $('#discount-'+ id[1]).attr('title',  fulltitle+' - Discount Percentage(New)');
            $('#productname-'+ id[1]).attr('title',  fulltitle+' - Product Name(New)');
            rowTotal(cvalue);
            billUpyog();
            orderdiscount();
            $(".noproduct-section").removeClass('d-none');
            save_changed_values_for_history();


        },
        create: function (e) {
            $(this).prev('.ui-helper-hidden-accessible').remove();
        }
       
    });
    $(".producthis").hide();
});

$(".weighted_average_cost").on('click', function(e)
{
    e.preventDefault();
    Swal.fire({
    title: 'Coming Soon',
    icon: 'success',
    confirmButtonText: 'OK'
    });

    // Swal.fire({
    //     title: "Are you sure?",
    //     text: "Do you want to update weighted average costing?",
    //     icon: "question",
    //     showCancelButton: true,
    //     confirmButtonColor: '#3085d6',
    //     cancelButtonColor: '#d33',
    //     confirmButtonText: 'Yes, proceed!',
    //     cancelButtonText: "No - Cancel",
    //     reverseButtons: true,  
    //     focusCancel: true,      
    //     allowOutsideClick: false,  // Disable outside click
    // }).then((result) => {
    //     if (result.isConfirmed) {
            
    //         $.ajax({
    //             url: baseurl + 'products/weighted_costing_update',
    //             type: 'POST',
    //             data: {
    //                 product_code : $("#product_code").val(),
    //                 weighted_average_cost : $("#weighted_average_cost").val(),

    //             },
    //             // dataType: 'json',
    //             success: function(response) {
    //                 console.log(response);
    //                 if (typeof response === "string") {
    //                     response = JSON.parse(response);
    //                 }       
    //                 if(response.data=="Mismatch")
    //                 {
    //                     Swal.fire({
    //                         title: 'Error!',
    //                         text: 'Weighted Average Costing Mismatch Error',
    //                         icon: 'Error',
    //                         confirmButtonText: 'OK'
    //                     });
    //                 }  
    //                 else{
    //                     // window.location.href = baseurl + 'products';
    //                 }
                                
    //             },
    //             error: function(xhr, status, error) {
    //                 Swal.fire('Error', 'An error occurred', 'error');
    //                 console.log(error); // Log any errors
    //             }
    //         });
    //     } else if (result.dismiss === Swal.DismissReason.cancel) {
    //         // Enable the button again if user cancels
    //         $('#add_cberp_costing_method_btn').prop('disabled', false);
    //     }
    // });
});


function disable_items()
{    
    $("input, textarea, select, button").addClass("disable-class").prop("disabled", true);
    $("#addmore_img").addClass("disable-class").prop("disabled", true);
    $(".delete-btn").addClass("disable-class").prop("disabled", true);
    $("#head-customerbox").removeClass("disable-class").prop("disabled", false);
    $(".history-expand-button, .history-close-button, .logclose-btn").removeClass("disable-class").prop("disabled", false);
}

//erp2024 27-03-2025 hide or show discount section in table
$('.discountshowhide').on('change', function() {
    var $td = $('.td-colspan');
    var currentColspan = parseInt($td.attr('colspan')) || 0; 
    $('.discountshowhide').prop('disabled',false);
    if ($(this).is(':checked')) {
        $('.discountcoloumn').removeClass('d-none');
        $('.discountshowhide').val(1);
        $('.discount_flg').val(1);
        $td.attr('colspan', currentColspan + 1);
    } else {
        $('.discountcoloumn').addClass('d-none'); 
        $('.discountshowhide').val(2);
        $('.discount_flg').val(0);
        $td.attr('colspan', currentColspan - 1);
    }
});

//erp2024 27-03-2025 change colspan based on chec or uncheck .discountshowhide
function showdiscount_potion()
{
    var $td = $('.td-colspan');
    var currentColspan = parseInt($td.attr('colspan')) || 0; 
    $td.attr('colspan', currentColspan + 1);
    $('.discountcoloumn').removeClass('d-none');
    $('.discountshowhide').val(1);
    $('.discountshowhide').prop('checked', true);
    $('.discountshowhide').prop('disabled',true);
    $('.discount_flg').val(1);
}

function deleteFiles(id,img_name,pagefrom,pageid) {
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
                $.ajax({
                    type: 'POST',
                    url: baseurl + 'Quote/deletesubItem',
                    data: { selectedProducts: id, image: img_name },
                    dataType: 'json',
                    success: function(response) {
                        location.reload();
                    },
                    error: function(xhr, status, error) {}
                });
            }
    });
}

function deleteitem(id,img_name) {
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
            $.ajax({
                type: 'POST',
                url: baseurl + 'Quote/deletesubItem',
                data: { selectedProducts: id, image: img_name },
                dataType: 'json',
                success: function(response) {
                    location.reload();
                },
                error: function(xhr, status, error) {}
            });
        }
    });

}


//09-04-2025 for set title
function addMissingTitles() {
    // Inputs
    $('input:not([title])').each(function () {
        var type = $(this).attr('type');
        var placeholder = $(this).attr('placeholder');
        var value = $(this).val();

        // Use placeholder
        if (placeholder) {
            $(this).attr('title', placeholder);
        } 
        // Use value if it's a button or submit
        else if ((type === 'submit' || type === 'button') && value) {
            $(this).attr('title', value);
        } 
        // Use label fallback
        else {
            var id = $(this).attr('id');
            if (id) {
                var label = $('label[for="' + id + '"]').text().trim();
                if (label) {
                    $(this).attr('title', label);
                }
            }
        }
    });

    // Textareas
    $('textarea:not([title])').each(function () {
        var placeholder = $(this).attr('placeholder');
        if (placeholder) {
            $(this).attr('title', placeholder);
        } else {
            var id = $(this).attr('id');
            if (id) {
                var label = $('label[for="' + id + '"]').text().trim();
                if (label) {
                    $(this).attr('title', label);
                }
            }
        }
    });

    // Selects
    $('select:not([title])').each(function () {
        var id = $(this).attr('id');
        if (id) {
            var label = $('label[for="' + id + '"]').text().trim();
            if (label) {
                $(this).attr('title', label);
            }
        }
        if (!$(this).attr('title')) {
            var firstOption = $(this).find('option:first').text().trim();
            if (firstOption) {
                $(this).attr('title', firstOption);
            }
        }
    });

    // Anchors
    $('a:not([title])').each(function () {
        var text = $(this).text().trim();
        if (text) {
            $(this).attr('title', text);
        }
    });

    // Buttons
    $('button:not([title]):not(.navsearch)').each(function () {
        var text = $(this).text().trim();
        if (text) {
            $(this).attr('title', text);
        }
    });
}

$(".suggested_price_btn").on('click', function(e)
{
    e.preventDefault();
    // Swal.fire({
    //     title: "Are you sure?",
    //     text: "Do you want to update weighted average costing?",
    //     icon: "question",
    //     showCancelButton: true,
    //     confirmButtonColor: '#3085d6',
    //     cancelButtonColor: '#d33',
    //     confirmButtonText: 'Yes, proceed!',
    //     cancelButtonText: "No - Cancel",
    //     reverseButtons: true,  
    //     focusCancel: true,      
    //     allowOutsideClick: false,  // Disable outside click
    // }).then((result) => {
    //     if (result.isConfirmed) {
       
            $.ajax({
                url: baseurl + 'products/product_suggested_price',
                type: 'POST',
                data: {
                    product_id : $("#product_cod").val(),
                    product_price : $("#product_price").val(),

                },
                // dataType: 'json',
                success: function(response) {
                    if (typeof response === "string") {
                        response = JSON.parse(response);
                    }       
                    if(response.data)
                    {
                        Swal.fire({
                            title: 'Prediction Price',
                            html: response.data,
                            icon: 'info',
                            confirmButtonText: 'OK'
                        });
                    }  
                    else{
                        // window.location.href = baseurl + 'products';
                    }
                                
                },
                error: function(xhr, status, error) {
                    Swal.fire('Error', 'An error occurred', 'error');
                    console.log(error); // Log any errors
                }
            });
    //     } else if (result.dismiss === Swal.DismissReason.cancel) {
    //         // Enable the button again if user cancels
    //         $('#add_cberp_costing_method_btn').prop('disabled', false);
    //     }
    // });
});


function duplicate_check(totalRows,newProductID)
{

    var duplicateFound = false;
    for (var i = 0; i <= totalRows; i++) {
        if ($('#code-' + i).length) {
            var existingProductName = $('#code-' + i).val();
            if (existingProductName == newProductID) {
                duplicateFound = true;
                break;
            }
        }
    }
    return duplicateFound;
    
}
function duplicate_message(cvalue=0,newProductID)
{
     
        $('#purchaseproduct-'+cvalue).val("");
        $('#leadproductname-'+cvalue).val("");
        $('#purchasereturncode-'+cvalue).val("");
        $('#purchasereturnproduct-'+cvalue).val("");
        $('#productname-'+cvalue).val("");
        $('#code-'+cvalue).val("");
        $('#purchasecode-'+cvalue).val("");
        $('#price-'+cvalue).val(0.00);
        $('#discount-'+cvalue).val(0.00);
        $('#discountamt-'+cvalue).val(0.00);
        $('#amount-'+cvalue).val(0);
        $('#onhandQty-'+cvalue).text(0);
        $('#pricelabel-'+cvalue).text(0);
        $('#lowestpricelabel-'+cvalue).text(0);
        $('#maxdiscountratelabel-'+cvalue).text(0);
        $('#last_purchase_price_label-'+cvalue).text("");
        rowTotal(cvalue);
        billUpyog();
        Swal.fire({
            icon: 'error',
            title: 'Duplicate Item',
            text: 'There is already the same item - '+ newProductID +' in the list.',
            confirmButtonText: 'OK'
        });
    
}

function salesorder_edit_autocomplete(cvalue)
{    
    
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
            //check duplicate entry starts
            var newProductID = ui.item.data[7]; // New selected product name
            var duplicateFound = false;      
            var totalRows = parseInt($('#ganak').val()); // total number of rows
            for (var i = 0; i <= totalRows; i++) {
                if ($('#code-' + i).length) {
                    var existingProductName = $('#code-' + i).val();
                    if (existingProductName == newProductID) {
                        duplicateFound = true;
                        break;
                    }
                }
            }
            if (duplicateFound) {                
                duplicate_message(cvalue,newProductID);
                return false;
            }
            //check duplicate entry ends
            if ($("#taxformat option:selected").attr('data-trate')) {

                t_r = $("#taxformat option:selected").attr('data-trate');
            }
            var discount = ui.item.data[4];
            var custom_discount = $('#custom_discount').val();
            if (custom_discount > 0) discount = deciFormat(custom_discount);
            $("#onhandQty-" + cvalue).text(parseInt(ui.item.data[8]));
            $("#prd-" + cvalue).val(ui.item.data[2]);
            $('#amount-' + cvalue).val(1);
            $('#price-' + cvalue).val(ui.item.data[1]);
            $('#pricelabel-' + cvalue).text(ui.item.data[1]);
            $('#pid-' + cvalue).val(ui.item.data[2]);
            $('#vat-' + cvalue).val(t_r);
            $('#taxlabel-' + cvalue).text(t_r+" / ");
            // $('#taxlabel-' + cvalue).val(t_r);discount-amtlabel
            $('#discount-' + cvalue).val(discount);
            $('#dpid-' + cvalue).val(ui.item.data[5]);
            $('#unit-' + cvalue).val(ui.item.data[6]);
            $('#hsn-' + cvalue).val(ui.item.data[7]);
            $('#alert-' + cvalue).val(parseInt(ui.item.data[8]));
            $('#serial-' + cvalue).val(ui.item.data[10]);     
            $('#lowestprice-' + cvalue).val(ui.item.data[11]);
            $('#lowestpricelabel-' + cvalue).text(ui.item.data[11]);        
            $('#maxdiscountrate-' + cvalue).val(ui.item.data[12]);

             //erp2024 add discount amount 27-03-2025
             var productPrice = parseFloat(ui.item.data[1]);
             maxdiscount =  parseFloat(ui.item.data[12]);
             var discountValue = ((productPrice * maxdiscount) / 100);
             discountValue = discountValue.toFixed(2);
             $('#maxdiscountamount-' + cvalue).val(discountValue);
             $('#maxdiscountratelabel-'+ cvalue).text(ui.item.data[12]+"% ("+discountValue+")");

            // $('#maxdiscountratelabel-'+ cvalue).text(ui.item.data[12]);
            $('#income_account_number-'+ cvalue).val(ui.item.data[13]);
            $('#expense_account_number-'+ cvalue).val(ui.item.data[14]);
            $('#product_cost-'+ cvalue).val(ui.item.data[15]);
            $('#code-'+ cvalue).val(ui.item.data[7]);
            rowTotal(cvalue);
            billUpyog();
            orderdiscount();
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
             //check duplicate entry starts
             var newProductID = ui.item.data[7]; // New selected product name
             var duplicateFound = false;      
             var totalRows = parseInt($('#ganak').val()); // total number of rows
             for (var i = 0; i <= totalRows; i++) {
                 if ($('#code-' + i).length) {
                     var existingProductName = $('#code-' + i).val();
                     if (existingProductName == newProductID) {
                         duplicateFound = true;
                         break;
                     }
                 }
             }
             if (duplicateFound) {                
                 duplicate_message(cvalue,newProductID);
                 return false;
             }
             //check duplicate entry ends
            if ($("#taxformat option:selected").attr('data-trate')) {

                t_r = $("#taxformat option:selected").attr('data-trate');
            }
            var discount = ui.item.data[4];
            var custom_discount = $('#custom_discount').val();
            if (custom_discount > 0) discount = deciFormat(custom_discount);
            $("#onhandQty-" + cvalue).text(parseInt(ui.item.data[8]));
            $("#prd-" + cvalue).val(ui.item.data[2]);
            $('#amount-' + cvalue).val(1);
            $('#price-' + cvalue).val(ui.item.data[1]);
            $('#pricelabel-' + cvalue).text(ui.item.data[1]);
            $('#pid-' + cvalue).val(ui.item.data[2]);
            $('#vat-' + cvalue).val(t_r);
            $('#taxlabel-' + cvalue).text(t_r+" / ");
            // $('#taxlabel-' + cvalue).val(t_r);discount-amtlabel
            $('#discount-' + cvalue).val(discount);
            $('#dpid-' + cvalue).val(ui.item.data[5]);
            $('#unit-' + cvalue).val(ui.item.data[6]);
            $('#hsn-' + cvalue).val(ui.item.data[7]);
            $('#alert-' + cvalue).val(parseInt(ui.item.data[8]));
            $('#serial-' + cvalue).val(ui.item.data[10]);     
            $('#lowestprice-' + cvalue).val(ui.item.data[11]);
            $('#lowestpricelabel-' + cvalue).text(ui.item.data[11]);        
            $('#maxdiscountrate-' + cvalue).val(ui.item.data[12]);

             //erp2024 add discount amount 27-03-2025
             var productPrice = parseFloat(ui.item.data[1]);
             maxdiscount =  parseFloat(ui.item.data[12]);
             var discountValue = ((productPrice * maxdiscount) / 100);
             discountValue = discountValue.toFixed(2);
             $('#maxdiscountamount-' + cvalue).val(discountValue);
             $('#maxdiscountratelabel-'+ cvalue).text(ui.item.data[12]+"% ("+discountValue+")");
            // $('#maxdiscountratelabel-'+ cvalue).text(ui.item.data[12]);
            $('#productname-'+ cvalue).val(ui.item.data[0]);
            $('#income_account_number-'+ cvalue).val(ui.item.data[13]);
            $('#expense_account_number-'+ cvalue).val(ui.item.data[14]);
            $('#product_cost-'+cvalue).val(ui.item.data[15]);

            var fulltitle = ui.item.data[0]+" ("+ui.item.data[7]+")";
            $('#code-'+ cvalue).attr('title',  fulltitle+' - (New)');
            $('#amount-'+ cvalue).attr('title',  fulltitle+' - Quantity(New)');
            $('#discounttype-'+ cvalue).attr('title',  fulltitle+' - Discount Type(New)');
            $('#discountamt-'+ cvalue).attr('title',  fulltitle+' - Discount Amount(New)');
            $('#discount-'+ cvalue).attr('title',  fulltitle+' - Discount Percentage(New)');
            $('#productname-'+ cvalue).attr('title',  fulltitle+' - Product Name(New)');
            rowTotal(cvalue);
            billUpyog();
            orderdiscount();
            $(".noproduct-section").removeClass('d-none');
            save_changed_values_for_history();


        },
        create: function (e) {
            $(this).prev('.ui-helper-hidden-accessible').remove();
        }
       
    });
    $(".producthis").hide();
}


// for lead,quote,salesorder...
const globalValidationExpandLevel = {
  errorElement: "em",
  errorPlacement: function(error, element) {
    error.addClass("help-block");
    if (element.prop("type") === "checkbox") {
      error.insertAfter(element.parent("label"));
    } else {
      error.insertAfter(element);
    }
  },
  highlight: function(element, errorClass, validClass) {
    $(element)
      .addClass("focusclass")
      .parents(".col-sm-5")
      .addClass("has-error")
      .removeClass("has-success");
  },
  unhighlight: function(element, errorClass, validClass) {
    $(element)
      .removeClass("focusclass")
      .parents(".col-sm-5")
      .addClass("has-success")
      .removeClass("has-error");
  },
  invalidHandler: function(event, validator) {
    if (validator.errorList.length) {
      var firstError = $(validator.errorList[0].element);
      var section = firstError.closest('.page-header-data-section');

      if (section.length && section.is(':hidden')) {
        section.slideDown(200, function () {
          $('html, body').animate({
            scrollTop: firstError.offset().top - 200
          }, 500, function () {
            firstError.focus();
          });
        });
      } else {
        $('html, body').animate({
          scrollTop: firstError.offset().top - 200
        }, 500, function () {
          firstError.focus();
        });
      }
    }
  }
};


