<?php
/**
 * Cloud Biz Erp -  Accounting,  Invoicing  and CRM Software
 * Copyright (c) Cloud Biz Erp. All Rights Reserved
 * ***********************************************************************
 *
 *  Email: support@cloudbizerp.com
 *  Website: https://www.cloudbizerp.com
 *
 *  ************************************************************************
 *  * This software is furnished under a license and may be used and copied
 *  * only  in  accordance  with  the  terms  of such  license and with the
 *  * inclusion of the above copyright notice.
 *  * Tree Code Hub IT (P) Ltd
 * ***********************************************************************
 */

defined('BASEPATH') or exit('No direct script access allowed');


class SalesOrders extends CI_Controller
{
    private $configurations;
    private $prifix72;
    private $sales_module_group_number;
    private $my_approval_levels;
    private $all_approval_level;
    private $module_number;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('SalesOrder_model', 'salesorder');
        $this->load->model('plugins_model', 'plugins');
        $this->load->model('products_model', 'products');
        $this->load->model('quote_model', 'quote');
        $this->load->library("Aauth");
        $this->load->library('session');
        if (!$this->aauth->is_loggedin()) {
            redirect('/user/', 'refresh');
        }
        // if (!$this->aauth->premission(1)) {
        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        // }
        $this->load->library("Custom");
        $this->li_a = 'sales';
        $this->session->unset_userdata('orderid');
        $this->configurations = $this->session->userdata('configurations');
        $this->prifix72 =  get_prefix_72();
        $this->sales_module_group_number =  get_module_details_by_name('Sales');
        $this->module_number =  module_number_name('Sales');
    }

    
    public function index()
    {
        $data['permissions'] = load_permissions('Sales','Sales','Sales Orders','List');
        $head['title'] = "Manage Sales Orders";
        $data['eid'] = intval($this->input->get('eid'));
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['employees']  = employee_list();
        $data['customers']  = customer_list();
        // $this->load->model('invoices_model', 'invocies');
        // $condition = "WHERE cberp_sales_orders.salesorder_number IS NOT NULL";
        // $data['counts'] = $this->invocies->get_dynamic_count('cberp_sales_orders','invoicedate','total',$condition);
        $data['ranges'] = getCommonDateRanges();
        $data['counts'] = $this->salesorder->get_filter_count($data['ranges']);
        $this->load->view('fixed/header', $head);
        $this->load->view('sales/salesorderlist', $data);
        $this->load->view('fixed/footer');
    }

    

    public function ajax_list()
    {
       
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
        $eid = 0;
        $list = $this->salesorder->get_datatables($eid);
        $data = array();
        $no = $this->input->post('start');
        foreach ($list as $invoices) {
            $disableclass="";
            // $main_url = (($invoices->converted_status == 0) || ($invoices->converted_status == 4) || ($invoices->converted_status == 5)) ? '<a href="' . base_url("SalesOrders/draft_or_edit?id=$invoices->id") . '">' . $this->prifix72['salesorder_prefix'].$invoices->salesorder_number . '</a>' :'<a href="' . base_url("quote/salesorders?id=$invoices->id") . '">' . $this->prifix72['salesorder_prefix'].$invoices->salesorder_number . '</a>';
            $token = ($invoices->quote_number) ? 2 : 3;
            $main_url = '<a href="' . base_url("SalesOrders/salesorder_new?id=$invoices->salesorder_number&token=$token") . '">' . $invoices->salesorder_number . '</a>';

            $quote_url = ($invoices->quote_number) ? '<a href="' . base_url("quote/create?id=$invoices->quote_number") . '">' . $invoices->quote_number . '</a>':'' ;

            // $main_url = '<a href="' . base_url("quote/salesorders?id=$invoices->id") . '">&nbsp; ' . $invoices->salesorder_number . '</a>';
            $prdstatus = $this->salesorder->get_prdstatus_salesorderid($invoices->salesorder_number);
            if($prdstatus==1 && $invoices->converted_status==1)
            {
                $disableclass="disable-class";
            }

            $convertbtn ="";
            if(($invoices->completed_status==1) && ($invoices->converted_status==0) && ($invoices->status!='deleted') ){
                $draftbtn="";             
                $convertbtn = '<a href="' . base_url("SalesOrders/salesorder_new?id=$invoices->salesorder_number&token=3") . '" class="btn btn-secondary btn-sm '.$disabled.' '.$disableclass.'" title="Convert" ><i class="fa fa-exchange"></i> Convert</a>&nbsp;';
                // $convertbtn = '<a href="' . base_url("SalesOrders/draft_or_edit?id=$invoices->id") . '" class="btn btn-secondary btn-sm '.$disabled.' '.$disableclass.'" title="Convert" ><i class="fa fa-exchange"></i> Convert</a>&nbsp;';
            }
            else if(($invoices->completed_status==1) && ($invoices->converted_status!=0) && ($invoices->status!='deleted')){
                $draftbtn="";
                $convertbtn = '<a href="' . base_url("SalesOrders/delivery_notes?id=$invoices->salesorder_number") . '" class="btn btn-secondary btn-sm " title="Delivery Note(s)" >Delivery Note(s)</a>&nbsp;';
            }
            else{}



            $editbtn = "";
         

            if(($invoices->status=='deleted')){
                $status = '<span class="st-due">' . $this->lang->line('Deleted') . '</span>';
            }
            else if(($invoices->status=='draft')){
                $status = '<span class="st-draft">' . $this->lang->line('Draft') . '</span>';
                $editbtn = '<a href="' . base_url("SalesOrders/salesorder_new?id=$invoices->salesorder_number&token=3") . '" class="btn btn-secondary btn-sm" title="Edit"><i class="fa fa-pencil"></i></a>&nbsp;';
                // $editbtn = '<a href="' . base_url("SalesOrders/draft_or_edit?id=$invoices->id") . '" class="btn btn-secondary btn-sm" title="Edit"><i class="fa fa-pencil"></i></a>&nbsp;';
                $convertbtn ="";
            }
            
            else if(($invoices->converted_status=='0') && ($prdstatus==1) && ($invoices->status!='deleted')){
                $status = '<span class="st-due">' . $this->lang->line('Completed') . '</span>';
            }
            else if(($invoices->converted_status=='0') &&  ($invoices->status=='pending')){
                $status = '<span class="st-Open">' . $this->lang->line('Created') . '</span>';
            }

            else if(($invoices->converted_status=='1') && ($prdstatus==1) && ($invoices->status!='deleted')){
                $status = '<span class="st-paid">' . $this->lang->line('Converted') . '</span>';
            }
            else if(($invoices->converted_status=='2') && ($prdstatus!=1) && ($invoices->status!='deleted')){
                $status = '<span class="st-partial">' . $this->lang->line('Partially Converted') . '</span>';
            }
            else if(($invoices->converted_status=='0') && ($prdstatus!=1) && ($invoices->status!='deleted')){
                $status = '<span class="st-rejected">' . $this->lang->line('Not Converted') . '</span>';
                
                $editbtn = '<a href="' . base_url("SalesOrders/salesorder_new?id=$invoices->salesorder_number&token=3") . '" class="btn btn-secondary btn-sm" title="Edit"><i class="fa fa-pencil"></i></a>&nbsp;';
            }
            // else if(($invoices->converted_status=='4') && ($invoices->status!='deleted')){
            //     $status = '<span class="st-draft">' . $this->lang->line('Draft') . '</span>';
            //     $editbtn = '<a href="' . base_url("SalesOrders/salesorder_new?id=$invoices->id&token=3") . '" class="btn btn-secondary btn-sm" title="Edit"><i class="fa fa-pencil"></i></a>&nbsp;';
            //     // $editbtn = '<a href="' . base_url("SalesOrders/draft_or_edit?id=$invoices->id") . '" class="btn btn-secondary btn-sm" title="Edit"><i class="fa fa-pencil"></i></a>&nbsp;';
            //     $convertbtn ="";
            // }
            else if(($invoices->converted_status=='3') && ($invoices->status!='deleted')){
                $status = '<span class="st-rejected">' . $this->lang->line('Assign for Delivery') . '</span>';
                $editbtn = "";
            }
            else if(($invoices->converted_status=='5') && ($invoices->status=='invoiced')){
                $status = '<span class="st-paid">' . $this->lang->line('Invoiced') . '</span>';
                $editbtn = "";
                $convertbtn ="";
            }

            else if(($invoices->status=='pending')){
                $status = '<span class="st-Open">' . $this->lang->line('Created') . '</span>';
            }
            else{
                $status = '<span class="st-paid">' . $this->lang->line('Converted') . '</span>';
                $convertbtn = '<a href="' . base_url("SalesOrders/delivery_notes?id=$invoices->salesorder_number") . '" class="btn btn-secondary btn-sm " title="Delivery Note(s)" >Delivery Note(s)</a>&nbsp;';
                // $convertbtn = '<a href="' . base_url("SalesOrders/delivery_notes?id=$invoices->salesorder_number") . '" class="btn btn-secondary btn-sm " title="Delivery Note(s)" >Delivery Note(s)</a>&nbsp;';
            }

         
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $main_url;
            $row[] = $quote_url;
            $row[] = $invoices->customername;
            $row[] = $invoices->customerid;
            // $row[] = $invoices->items;
            
            $colorcode = get_color_code($invoices->due_date);
            $dudate = (!empty($invoices->due_date))?dateformat($invoices->due_date):"";
            $row[] = '<b style="color:'.$colorcode.'">'.$dudate.'</b>';

            // $row[] = dateformat($invoices->invoiceduedate);
            $row[] = number_format($invoices->total,2);
            $row[] = $status;
            // $editbtn = '<a href="' . base_url("SalesOrders/edit?id=$invoices->id") . '" class="btn btn-secondary btn-sm " title="Delivery Note(s)" ><i class="fa fa-pencil"></i> Edit</a>&nbsp;';
            //  $row[] = '<a href="' . base_url("salesorders/view?id=$invoices->id") . '" class="btn btn-blue btn-sm"><i class="fa fa-eye"></i></a> &nbsp; <a href="' . base_url("quote/printquote?id=$invoices->id") . '&d=1" class="btn btn-info btn-sm"  title="Download"><span class="fa fa-download"></span></a>';
           
            $row[] = $editbtn.$draftbtn.$convertbtn;
            // $row[] = '<button onclick="completedstatus('.$invoices->id.')" type="button" class="btn btn-sm btn-secondary" title="Complete Status">'.$icons.'</button>&nbsp;'.$convertbtn;
            $data[] = $row;
        }
        // print_r($data); die();
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->salesorder->count_all($eid),
            "recordsFiltered" => $this->salesorder->count_filtered($eid),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);

    }
    public function view()
    {
        $tid = intval($this->input->get('id'));
        $data['id'] = $tid;
        $data['invoice'] = $this->salesorder->salesorder_details($tid);
        $data['products'] = $this->salesorder->salesorder_products($tid);
        $data['attach'] = $this->salesorder->attach($tid);
        $data['employee'] = $this->salesorder->employee($data['invoice']['eid']);
        $head['title'] = "Sales Order #" . $data['invoice']['tid'];
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->view('fixed/header', $head);
        if ($data['invoice']) $this->load->view('sales/view', $data);
        $this->load->view('fixed/footer');
    }

    function materialrequest()
    {
        
        
        // if (!$this->aauth->premission(10)) {

        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');

        // }

        $salesPro=$this->input->post('selectedProducts');
        $data['selectedProducts'] = $salesPro;
        $tid = $this->session->userdata("orderid");
        $productIds = explode(",",$salesPro);
        $data['warehouses'] = $this->products->warehouse_list();
        $data['products'] = $this->products->products_list_by_id($productIds);
        $this->load->view('fixed/header', $head);
        $this->load->view('sales/matrial-request', $data);
        $this->load->view('fixed/footer');            
    }

    

    //erp2024 modified on 21-08-2024
    public function materialrequestaction()  {
      
        $products = $this->input->post('product-name');
        $warehousefrom = $this->input->post('warehousefrom');
        $transferqty = $this->input->post('transferqty');
        $product_qty = $this->input->post('product_qty');
        $totalPrdts = count($products);
        $warehouse_to = $this->input->post('warehouse_to');
        
        if ($totalPrdts > 0) {
            $requestMade = false;
        
            for ($i = 0; $i < $totalPrdts; $i++) {
                if (!empty($products[$i]) && !empty($warehousefrom[$i]) && !empty($transferqty[$i])) {                
                    $requestlist = array(
                        'product_id'     => $products[$i],
                        'warehouse_from' => $warehousefrom[$i],
                        'requested_qty'  => $transferqty[$i],   
                        'warehouse_to'   => $warehouse_to,
                        'requested_date'   => date('Y-m-d H:i:s'),
                        'requested_by'   => $this->session->userdata('id')
                    );
        
                    if (!empty($requestlist)) {
                        $this->db->insert('material_request', $requestlist);
                        // echo $this->db->last_query();
                        $requestMade = true;
                    }
                }
            }
        
            if ($requestMade) {
                echo json_encode(array(
                    'status' => 'Success',
                    'message' => 'Material request has been sent'
                ));
            } else {
                echo json_encode(array('status' => 'Error', 'message' => "Please choose all fields."));
            }
        } else {
            echo json_encode(array('status' => 'Error', 'message' => "Please choose product."));
        }
        
    }
    // public function materialrequestaction()  {
      
    //     $products = $this->input->post('product-name');
    //     $warehousefrom = $this->input->post('warehousefrom');
    //     $transferqty = $this->input->post('transferqty');
    //     $product_qty = $this->input->post('product_qty');
    //     $totalPrdts = count($products);
    //     $warehouse_to = $this->input->post('warehouse_to');
        
    //     if ($totalPrdts > 0) {
    //         $requestMade = false;
        
    //         for ($i = 0; $i < $totalPrdts; $i++) {
    //             if (!empty($products[$i]) && !empty($warehousefrom[$i]) && !empty($transferqty[$i])) {                
    //                 $requestlist = array(
    //                     'product_id'     => $products[$i],
    //                     'warehouse_from' => $warehousefrom[$i],
    //                     'requested_qty'  => $transferqty[$i],   
    //                     'warehouse_to'   => $warehouse_to,
    //                     'requested_date'   => date('Y-m-d H:i:s'),
    //                     'requested_by'   => $this->session->userdata('id')
    //                 );
        
    //                 if (!empty($requestlist)) {
    //                     $this->db->insert('material_request', $requestlist);
    //                     // echo $this->db->last_query();
    //                     $requestMade = true;
    //                 }
    //             }
    //         }
        
    //         if ($requestMade) {
    //             $target = base_url() . "SalesOrders/";
    //             echo json_encode(array(
    //                 'status' => 'Success',
    //                 'message' => $this->lang->line('Material request has been sent') . $this->lang->line('Back to sales order') . " <a href='$target' class='btn btn-info btn-sm'><span class='fa fa-eye' aria-hidden='true'></span> Back To Sales Order </a> &nbsp;"
    //             ));
    //         } else {
    //             echo json_encode(array('status' => 'Error', 'message' => "Please choose all fields."));
    //         }
    //     } else {
    //         echo json_encode(array('status' => 'Error', 'message' => "Please choose product."));
    //     }
        
    // }

    public function sales_order_details_by_id(){
        // if (!$this->aauth->premission(10)) {
        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        // } 
        $saleorder_id = $this->input->post('salesorder_id');
        $salesordernumber = $this->input->post('salesordernumber');
        $saleorder_master = $this->salesorder->salesorder_details($saleorder_id);
        $saleorder_items = $this->salesorder->salesorder_item_details_by_id($saleorder_id,$salesordernumber);

        if(!empty($saleorder_items)){
        $i = 0;        
        $table = '<table class="table table-bordered dataTable">';
        $table .= '<thead>';
        $table .= '<tr>';
        $table .= '<th width="22%">Item Name</th>';
        $table .= '<th width="7%">Item No.</th>';
        $table .= '<th width="1%">LD Qty</th>';
        $table .= '<th width="1%">QT Qty</th>';
        $table .= '<th  width="1%">Received SO Qty</th>';
        $table .= '<th width="1%">Remaining SO</th>';
        $table .= '<th width="10%" class="text-center">New SO Qty</th>';
        $table .= '<th width="14%" class="text-center">Discount</th>';
        $table .= '<th width="10%" class="text-right">QT Price</th>';
        $table .= '<th width="7%" class="text-right">Unit Price</th>';
        $table .= '<th class="text-right">Total</th>';
        $table .= '</tr>';
        $table .= '</thead>';
        $table .= '<tbody>';
        foreach ($saleorder_items as $item) {
            $unitcost = ($item['price']>0) ? round($item['subtotal'] / intval($item['enteredqty']), 2) : 0;
            $totaldiscounts = $totaldiscounts + $item['totaldiscount'];
            if($item['discount_type']=='Perctype'){
                $percsel = "selected";
                $amtsel = "";
                $perccls = '';
                $amtcls = 'd-none';
                $disperc = amountFormat_general($item['discount']);
                $disamt = 0;
                $distype = "%";
            }
            else{
                $amtsel = "selected";
                $percsel = "";
                $perccls = 'd-none';
                $amtcls = '';
                $disamt = amountFormat_general($item['discount']);
                $disperc = 0;
                $distype = "Amt";
            }
            $table .= '<tr>';
            $table .= '<td>' . htmlspecialchars($item['product']) . '<input type="hidden" class="form-control" name="product_name[]" id="product_name-' . $i . '"  value="' . $item['product'] . '"><input type="hidden" name="hsn[]" id="unit-' . $i . '" value="' . $item['product_code'] . '"></td>';
            $table .= '<td class="text-center">' . $item['product_code'] . '</td>';
            $table .= '<td class="text-center">' . htmlspecialchars(intval($item['leadqty'])) . '</td>';
            $table .= '<td class="text-center">' . htmlspecialchars(intval($item['ordered_qty'])) . '<input type="hidden" class="form-control req" name="trasferedqty[]" id="trasferedqty-' . $i . '" value="' .intval($item['trasferedqty']) . '"><input type="hidden" class="form-control req" name="orderedqty[]" id="orderedqty-' . $i . '" value="' .intval($item['ordered_qty']) . '"></td>';

            $table .= '<td class="text-center">' . htmlspecialchars(intval($item['trasferedqty'])) . '<input type="hidden" class="form-control" name="deliveredqty[]" id="deliveredqty-' . $i . '" value="' .intval($row['deliveredqty']) . '"></td>';

            $table .= '<td class="text-center">' . htmlspecialchars(intval($item['remainingqty'])) . '<input type="hidden" class="form-control req" name="remainingqty[]" id="remainingqty-' . $i . '" value="' .intval($item['remainingqty']) . '"></td>';
            // $table .= '<td><input type="text" class="form-control" name="so_qty[]" value="' . htmlspecialchars($item['so_qty']) . '"></td>';
            $table .= '<td><input type="number" class="form-control req amnt" name="product_qty[]" id="amount-'.$i.'" onkeypress="return isNumber(event)" onkeyup="checkqtyedit('.$i.'), rowTotal('.$i.'), billUpyog()" autocomplete="off" value="'.intval($item['qty']).'"><input type="hidden" class="form-control req" name="old_product_qty[]" id="old_amount-'.$i.'" onkeypress="return isNumber(event)"autocomplete="off" value="'.intval($item['qty']).'"></td>';
            $table .= '<td class="text-center discountpotion1 d-none">
                    <div class="input-group text-center">
                        <select name="discount_type[]" id="discounttype-' . $i . '" class="form-control" onchange="discounttypeChange(' . $i . ')">
                                <option value="Perctype" '.$percsel.'>%</option>
                                <option value="Amttype" '.$amtsel.'>Amt</option>
                        </select>&nbsp;
                        <input type="number" min="0" class="form-control discount '.$perccls.'" name="product_discount[]" onkeypress="return isNumber(event)" id="discount-' . $i . '"  autocomplete="off" onkeyup="discounttypeChange(' . $i . ')" value="' .$disperc. '">
                        <input type="number" min="0" class="form-control discount '.$amtcls.'" name="product_amt[]" onkeypress="return isNumber(event)" id="discountamt-' . $i . '" autocomplete="off" onkeyup="discounttypeChange(' . $i . ')" value="' .$disamt. '">
                    </div>                                    
                    <strong id="discount-amtlabel-' . $i . '" class="discount-amtlabel discount-amtlabel-' . $i . '">Amount: '.$item['totaldiscount'].'</strong>
                    <div><strong id="discount-error-' . $i . '"></strong></div>                                    
                    </td>';

            $table .= '<td class="text-center discountpotion1notedit">
                <div class="text-center"> ';
                $table .= '<strong id="discount_type_label-' . $i . '" >' .$distype . '</strong> / '; 
                
                if($percsel!=""){
                                                    
                    $table .= '<strong id="discount_typeval_label-' . $i . '" >' .$disperc . '</strong>';
                }
                else{
                    $table .= '<strong id="discount_typeval_label-' . $i . '" >' .$disamt . '</strong>';
                }
            $table .= '</div>                                    
                <strong id="discount-amtlabel-' . $i . '" class="discount-amtlabel discount-amtlabel-' . $i . '">Amount: '.$item['totaldiscount'].'</strong>
                <div><strong id="discount-error-' . $i . '"></strong><input type="hidden" name="disca[]" id="disca-' . $i . '" value="'.$item['totaldiscount'].'"></div>                                    
            </td>';

            $table .= '<td class="text-right"><strong id="pricelabel' . $i . '" class="pricelabel">'.($item['price']).'</strong><input type="hidden" class="form-control req prc " name="product_price[]" id="price-' . $i . '"  onkeypress="return isNumber(event)" onkeyup="rowTotal(' . $i . '), billUpyog()" autocomplete="off" value="' .$item['price'] . '" ><input type="hidden" name="unit[]" id="unit-' . $i . '" value="' . $item['unit'] . '"><input type="hidden" class="ttInput" name="product_subtotal[]" id="total-' . $i . '" value="'.$item['subtotal'].'">';
            $table .= '<td class="text-right"><strong><span >'.$unitcost.'</span></strong>';
            $table .= '<td class="text-right"><strong><span class="ttlText" id="result-' . $i . '">'.$item['subtotal'].'</span></strong>';
            $table .= '<input type="hidden" name="taxa[]" id="taxa-' . $i . '" value="0">
                <input type="hidden" class="pdIn" name="pid[]" id="pid-' . $i . '" value="' . $item['pid'] . '">
                <input type="hidden" class="form-control" name="lowest_price[]" id="lowestprice-' . $i . '" onkeypress="return isNumber(event)" autocomplete="off" value="' . $item['lowest_price'] . '">
                <input type="hidden" class="form-control" name="maxdiscountrate[]" id="maxdiscountrate-' . $i . '" onkeypress="return isNumber(event)" autocomplete="off" value="' . $item['max_disrate'] . '"><input type="hidden" name="hsn[]" id="unit-' . $i . '" value="' . $item['code'] . '"></td>';
            $table .= '</tr>';
            $i++;

            
        }
    
        $table .= '</tbody>';
        $table .= '</table>';
        }

        $formheader = '<form method="post" id="data_form_edit" class="form-horizontal">';
        $formheader .= '<div class="modal-header">';
        $formheader .= '<h4 class="modal-title" id="myModalLabel">Sales Order - #'.$saleorder_master['salesorder_number'].'</h4>';
        $formheader .= '<button type="button" class="close" data-dismiss="modal">';
        $formheader .= '<span aria-hidden="true">&times;</span>';
        $formheader .= '<span class="sr-only">Close</span>';
        $formheader .= '</button>';
        $formheader .= '</div>';
        $formheader .= '<div class="modal-body">';
        $formheader .= '<div class="form-group row">';
        $formheader .= '<div class="col-12 cmp-pnl">';
        $formheader .= '<div class="inner-cmp-pnl">';
        $formheader .= '<div class="form-group form-row">';

        $formheader .= '<div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-xs-12 row">';
        $formheader .= '<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 cmp-pnl">';
        $formheader .= '<div id="customerpanel" class="inner-cmp-pnl">';
        $formheader .= '<div id="customer">';
        $formheader .= '<div class="clientinfo">';
        $formheader .= '<h4 class="title-sub">'.$this->lang->line('Client Details').'</h4>';
        $formheader .= '<hr>';
        $formheader .= '<div id="customer_name"><strong>' . $saleorder_master['name'] . '</strong></div>';
        $formheader .= '</div>';
        $formheader .= '<div class="clientinfo"> ';
        $formheader .= '<div id="customer_address1"><strong>' . $saleorder_master['address'] . '<br>' . $saleorder_master['city'] . ',' . $saleorder_master['shipping_country'] . '</strong></div>';
        $formheader .= '<div type="text" id="customer_phone">Phone: <strong>' . $saleorder_master['phone'] . '</strong><br>Email: <strong>' . $saleorder_master['email'] . '</strong></div>';
        $formheader .= '</div>';
        $formheader .= '</div>';
        $formheader .= '</div>';
        $formheader .= '</div>';
        $formheader .= '</div>';


        $formheader .= '<div class="col-xl-9 col-lg-9 col-md-9 col-sm-12 col-xs-12 row">';
        $formheader .= '<div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-xs-12">';
        $formheader .= '<label for="invocieno" class="col-form-label">Sales Order Number</label>';
        $formheader .= '<div class="input-group">';
        $formheader .= '<div class="input-group-addon"><span class="icon-file-text-o" aria-hidden="true"></span></div>';
        $formheader .= '<input type="text" class="form-control" placeholder="Sales Order #" name="salesorder_number" id="salesorder_number" value="'.$saleorder_master['salesorder_number'].'" readonly>';
        $formheader .= '<input type="hidden" class="form-control" placeholder="Sales Order #" name="invocieno" id="invocienoId" value="'.$saleorder_master['iid'].'">';
        $formheader .= '<input type="hidden" class="form-control" placeholder="Sales Order #" name="salesorderid" id="salesorderid" value="'.$saleorder_master['iid'].'">';
        $formheader .= '<input type="hidden" class="form-control" name="seq_number" id="seq_number" value="'.$saleorder_master['seq_number'].'">';
        $formheader .= '<input type="hidden" value="'.$saleorder_master['csd'].'" id="customer_id" name="customer_id"> </div>';
        $formheader .= '</div>';
        $formheader .= '<div class="col-xl-2 col-lg-4 col-md-6 col-sm-12 col-xs-12">';
        $formheader .= '<label for="invocieno" class="col-form-label">'.$this->lang->line("Our Reference").'</label>';
        $formheader .= '<div class="input-group">';
        $formheader .= '<div class="input-group-addon"><span class="icon-bookmark-o" aria-hidden="true"></span></div>';
        $formheader .= '<input type="text" class="form-control required" placeholder="'.$this->lang->line("Reference").'" name="refer" id="refer" value="'.$saleorder_master['refer'].'">';
        $formheader .= '</div>';
        $formheader .= '</div>';
        $formheader .= '<div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none">';
        $formheader .= '<label for="invociedate" class="col-form-label">Sales Order Date</label>';
        $formheader .= '<div class="input-group">';
        $formheader .= '<div class="input-group-addon"><span class="icon-calendar4" aria-hidden="true"></span></div>';
        $formheader .= ' </div>';
        $formheader .= '</div>';
        $formheader .= '<input type="hidden" class="form-control required" placeholder="Billing Date" name="invoicedate" id="invoicedate" autocomplete="false" min="date("Y-m-d")" value="'.$saleorder_master['invoicedate'].'">';
        $formheader .= '<input type="hidden" name="quote_id" id="quote_id1"  value="'.$saleorder_master['quote_id'].'">';
        $formheader .= '<div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">';
        $formheader .= '<label for="invocieno" class="col-form-label">'.$this->lang->line("Customer")." ".$this->lang->line("Purchase Order").' No.<span class="compulsoryfld"> *</span></label>';
        $formheader .= '<input type="text" class="form-control required"   placeholder="'.$this->lang->line("Customer")." ".$this->lang->line("Purchase Order").'" name="customer_purchase_order" id="customer_purchase_order" value="'.$saleorder_master['customer_purchase_order'].'" required>';
        $formheader .= '</div>';
        $formheader .= '<div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">';
        $formheader .= '<label for="invocieno" class="col-form-label">'.$this->lang->line("Customer")." ".$this->lang->line("Purchase Order")." ".$this->lang->line("Date");
        $formheader .= '<span class="compulsoryfld"> *</span></label>';
        $formheader .= '<input type="date" class="form-control required" name="customer_order_date" id="customer_order_date" placeholder="Order Date" autocomplete="false" value="'.$saleorder_master['customer_order_date'].'">';
        $formheader .= '</div>';
        $formheader .= '<div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-xs-12">';
        $formheader .= '<label for="invocieduedate" class="col-form-label">'.$this->lang->line("Delivery Deadline");
        $formheader .= '<span class="compulsoryfld">*</span></label>';
        $formheader .= '<input type="date" class="form-control required" name="invocieduedate" id="invocieduedate" placeholder="Validity Date" autocomplete="false" min="date("Y-m-d")" value="'.$saleorder_master['invoiceduedate'].'">';
        $formheader .= '</div>';


        $formheader .= '<div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none">';
        $formheader .= '<label for="taxformat" class="col-form-label">Tax</label>';
        $formheader .= '<select class="form-control" onchange="changeTaxFormat(this.value)" id="taxformat">$taxlist;</select></div>';
        $formheader .= '<div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none">';
        $formheader .= '<label for="discountFormat" class="col-form-label">Discount</label><select class="form-control" onchange="changeDiscountFormat(this.value)" id="discountFormat"><option value="' . $invoice['format_discount'] . '">' . $this->lang->line('Do not change') . '</option> $this->common->disclist()</select></div>';

        $formheader .= '<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">';
        $formheader .= '<label for="toAddInfo" class="col-form-label">Sales Order Note</label>';
        $formheader .= '<textarea class="form-textarea" name="notes" id="salenote">'.$saleorder_master['notes'].'</textarea>';
        $formheader .= '</div>';
        $formheader .= '<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">';
        $formheader .= '<label for="toAddInfo" class="col-form-label">'.$this->lang->line("Customer Message").'</label>';
        $formheader .= '<textarea class="form-textarea" name="propos" id="contents" rows="2">'.$saleorder_master['proposal'].'</textarea>';
        $formheader .= '</div>';
        $formheader .= '</div>';
        $formheader .= '</div>';
        $formheader .= '</div>';
        $formheader .= '<div class="col-12 row mt-1">';
        $formheader .= '<div class="form-check">';
        $formheader .= '<input class="form-check-input" type="checkbox" value="2" id="discountcheckededit" name="discountchecked" onchange="toggleDiscountOptions(this)">';
        $formheader .= '<label class="form-check-label" for="discountcheckededit" style="font-size:14px;color:#404E67;"><b>'.$this->lang->line("Do you want to modify the prices or discounts for the items below").'</b></label>';
        $formheader .= '</div>';
        $formheader .= '</div>';
        $formheader .= $table;
        $formheader .= '</div>';
        $formheader .= '';
        $formheader .= '<div class="col-12 row mt-3">';
        if($this->configurations["config_tax"]!="0"){ 
            $formheader .= '<div class="col-11 text-right">';
            $formheader .= '<strong>Total Tax (<span class="currenty lightMode">'.$this->config->item('currency').'</span>)</strong>';
            $formheader .= '</div>';
            $formheader .= '<div class="col-1">';
            $formheader .= '<span id="taxr" class="lightMode">'.amountExchange_s($invoice['tax'], $invoice['multi'], $this->aauth->get_user()->loc).'</span>';
            $formheader .= '</div>';
        }

        $formheader .= '<div class="col-10 text-right">';
        $formheader .= '<strong class="d-none1">Total Discount</strong>';
        $formheader .= '</div>';
        $formheader .= '<div class="col-2 text-right"> <span id="discs" class="lightMode d-none1 discs">'.number_format($totaldiscounts,2).'</span></div>';
        $formheader .= '<div class="col-12 text-right">';
        if ($exchange['active'] == 1){
        $formheader .= $this->lang->line("Payment Currency client") . ' <small>' . $this->lang->line("based on live market").'</small>';
        $formheader .= '<select name="mcurrency" class="selectpicker form-control">';
            $formheader .= '<option value="' . $invoice['multi'] . '">Do not change</option><option value="0">None</option>';
            foreach ($currency as $row) {
                $formheader .= '<option value="' . $row['id'] . '">' . $row['symbol'] . ' (' . $row['code'] . ')</option>';
            } 
        $formheader .= '</select>';
        }
        $formheader .= '</div>';
        $formheader .= '<div class="col-10 text-right">';
        $formheader .= '<strong class="d-none1">'.$this->lang->line("Grand Total").'</strong>';
        $formheader .= '</div>';
        $formheader .= '<div class="col-2 text-right">';
        $formheader .= '<span class="grandtotaltext d-none">'.number_format($saleorder_master['total'],2).'</span>';
        $formheader .= '<input type="text" name="total" class="form-control required invoiceyoghtml" id="invoiceyoghtml" readonly value="'.$saleorder_master['total'].'"></div>';
        $formheader .= '<div class="col-12 text-right mt-2">';
        $formheader .= '<input type="submit" id="new-saleorder-edit-btn" class="btn btn-lg btn-primary margin-bottom" value="'.$this->lang->line("Save").'" data-loading-text="Updating..." onclick="updateSalesorder(event)">'; 
        $formheader .= '<input type="hidden" value="salesorders/salesorder_sub_edit_action" id="action-url1">';
        $formheader .= '</div>';
        $formheader .= '</div>';
        $formheader .= '</form>';
        echo $formheader;
    }

    public function salesorder_sub_edit_action()
    {
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL); 
        $this->load->model('quote_model', 'quote');
    //   echo "<pre>"; print_r($this->input->post('pid')); die();
        $discountchecked = $this->input->post('discountchecked');

        if (isset($discountchecked)) {
            $discountchecked = $discountchecked;
        } else {
            $discountchecked = 0;
        }
        $customer_id = $this->input->post('customer_id');
       
        $salesorderid = $this->input->post('salesorderid');
        $salesorder_id =  $this->input->post('invocieno');        
        $salesordertid = ($salesorder_id+1000);
        $quote_id = $this->input->post('quote_id');
        //insert to tracking quote
        // $this->db->where('quote_id', $quote_id);
        // $this->db->update('cberp_transaction_tracking',['sales_id'=>$salesorderid,'sales_number'=>$salesordertid]);

        $invoicedate = datefordatabase($this->input->post('invoicedate'));
        $invocieduedate = datefordatabase($this->input->post('invocieduedate'));
        $notes = $this->input->post('notes', true);
        $tax = $this->input->post('tax_handle');
        $total_tax = 0;
        $total_discount = 0;
        $discountFormat = $this->input->post('discountFormat');
        // erp2024 remove pterms 06-06-2024
        // $pterms = $this->input->post('pterms');
        $pterms="";
        $propos = $this->input->post('propos');
        $currency = $this->input->post('mcurrency');
        $ship_taxtype = $this->input->post('ship_taxtype');
        $subtotal = rev_amountExchange_s($this->input->post('product_subtotal'), $currency, $this->aauth->get_user()->loc);
        if ($ship_taxtype == 'incl') $shipping = $shipping - $shipping_tax;
        $refer = $this->input->post('refer', true);
        $total = rev_amountExchange_s($this->input->post('total'), $currency, $this->aauth->get_user()->loc);
        
        // 08-08-2024 erp2024 newlyadded fields
        $customer_purchase_order = $this->input->post('customer_purchase_order', true);
        $customer_order_date = $this->input->post('customer_order_date');
        $salesorder_number = $this->input->post('salesorder_number');
        $seq_number = $this->input->post('seq_number');

        $i = 1;
        if ($discountFormat == '0') {
            $discstatus = 0;
        } else {
            $discstatus = 1;
        }

        if ($customer_id == 0) {
            echo json_encode(array('status' => 'Error', 'message' =>
                $this->lang->line('Please add a new client')));
            exit;
        }
        if ($customer_id == 0) {
            echo json_encode(array('status' => 'Error', 'message' =>
                $this->lang->line('Please add a new client')));
            exit;
        }
        
            //Product Data delete
            $pid = $this->input->post('pid');
            $productlist = array();
            $prodindex = 0;
            $product_id = $this->input->post('pid');
            $product_name1 = $this->input->post('product_name', true);
            $product_qty = $this->input->post('product_qty');
            // old product qty
            $old_product_qty = $this->input->post('old_product_qty');
            $product_price = $this->input->post('product_price');
            $product_tax = $this->input->post('product_tax');
            $product_subtotal = $this->input->post('product_subtotal');
            $ptotal_tax = $this->input->post('taxa');
            $ptotal_disc = $this->input->post('disca');
            
            $product_des = $this->input->post('product_description', true);
            $product_hsn = $this->input->post('hsn');
            $product_unit = $this->input->post('unit');
            
            $discount_type = $this->input->post('discount_type');
            $product_amt = $this->input->post('product_amt');
            $product_discount = $this->input->post('product_discount');                
            
            $ordered_qty = $this->input->post('orderedqty');
            $transfered_qty = $this->input->post('trasferedqty');
            $delivered_qty = $this->input->post('deliveredqty');
            $remaining_qty = $this->input->post('remainingqty');

            if(!empty($pid))
            {
          
                $this->db->delete('cberp_sales_orders_items', array('tid' => $salesorder_id));
                // die($this->db->last_query());
                foreach ($pid as $key => $value) {
                    if(numberClean($product_qty[$key]) > 0)
                    {
                        $status ="";
                        $total_discount += numberClean(@$ptotal_disc[$key]);
                        $total_tax += numberClean($ptotal_tax[$key]);
                        $discount_type_val="";             
                        
                        // if($discountchecked==1){
                            if($discount_type[$key]=="Amttype"){
                                $discountamount = numberClean($product_amt[$key]);
                            }
                            else if($discount_type[$key]=="Perctype"){
                                $discountamount = numberClean($product_discount[$key]);
                            }
                            else{
                
                            }
                            $discount_type_val = $discount_type[$key];
                            $totaldiscount = rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc);
                            $subtotal = rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc);
                        // }
                        // else{
                            // $discountamount=0;
                            // $discount_type_val = "";
                            // $totaldiscount = 0;
                            // $prdprice = rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc);
                            // $subtotal = ($prdprice * numberClean($product_qty[$key]));
                        // }
                        $newremaining_qty =0;
                        $newtransfered_qty =0;
                        $newdelivered_qty =0;
                        $currentqty = numberClean($product_qty[$key])-numberClean($old_product_qty[$key]);
                        if(numberClean($product_qty[$key]) != numberClean($old_product_qty[$key])){
                            $newremaining_qty  = numberClean($remaining_qty[$key]) - ($currentqty);
                            $newtransfered_qty =  numberClean($transfered_qty[$key]) + ($currentqty);
                            $newdelivered_qty  =  numberClean($transfered_qty[$key]) + ($currentqty);
                        }
                        
                        $data = array(
                            'tid' => $salesorder_id,
                            'pid' => $product_id[$key],
                            'product' => $product_name1[$key],
                            'code' => $product_hsn[$key],
                            'qty' => numberClean($product_qty[$key]),
                            'price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
                            'discount' => $discountamount,
                            'subtotal' => $subtotal,
                            'totaldiscount' => $totaldiscount,
                            'unit' => $product_unit[$key],
                            'status' => $status,                
                            'discount_type' => $discount_type_val,
                            'ordered_qty'    => $ordered_qty[$key],
                            // 'transfered_qty' => $newtransfered_qty,
                            // 'delivered_qty'  => $newdelivered_qty,
                            // 'delivered_qty'  => numberClean($delivered_qty[$key]) + numberClean($product_qty[$key]),
                            // 'remaining_qty'  => $newremaining_qty,
                            'salesorder_number'  => $salesorder_number,
                        );

                        if(intval($product_qty[$key]) != intval($old_product_qty[$key])){
                            $data['transfered_qty'] = $newtransfered_qty;
                            $data['delivered_qty']  = $newdelivered_qty;
                            $data['remaining_qty']  = $newremaining_qty;
                        }
                                        

                        // erp2024 quote items update 06-08-2024
                        $quoteitems = $this->quote->quote_details_for_multiplesalesorders($quote_id,$product_id[$key]);
                        $remainigqty = $quoteitems['remaining_qty'];
                        $deliveredqty = $quoteitems['delivered_qty'];
                        $transferedqty = $quoteitems['transfered_qty'];
                        $orderedqty = $quoteitems['ordered_qty'];
                        $actualqty = $quoteitems['qty'];

                        //set quote quantities
                        if(numberClean($product_qty[$key]) > ($old_product_qty[$key])){
                            $quotedelveredqty = (numberClean($product_qty[$key]) + numberClean($quoteitems['transfered_qty']))-numberClean($quoteitems['transfered_qty']);
                            $quotetransfered_qty = (numberClean($product_qty[$key]) + numberClean($quoteitems['transfered_qty'])) - numberClean($quoteitems['transfered_qty']);
                            $quoteremaining_qty  = (numberClean($orderedqty) - $quotetransfered_qty);
                        }
                        else if(numberClean($product_qty[$key]) < ($old_product_qty[$key])){
                            $quotedelveredqty = (numberClean($product_qty[$key]) - numberClean($quoteitems['transfered_qty']))-numberClean($quoteitems['transfered_qty']);
                            $quotetransfered_qty = (numberClean($product_qty[$key]) - numberClean($quoteitems['transfered_qty']))-numberClean($quoteitems['transfered_qty']);
                            $quoteremaining_qty  = (numberClean($orderedqty) - $quotetransfered_qty);
                        }      
                        else{}
                        if($orderedqty <= $quotetransfered_qty){
                            $prdstatus = 1;
                        }
                        else{
                            $prdstatus = 0;  
                        }
                        if(numberClean($product_qty[$key]) != ($old_product_qty[$key]))
                        {
                            
                                $quotedelveredqty = numberClean($transferedqty) + ($currentqty);
                                $quotetransfered_qty = numberClean($transferedqty) + ($currentqty);
                                $quoteremaining_qty = numberClean($remainigqty) - ($currentqty);;
                                $quotedata = array(
                                'remaining_qty' => $quoteremaining_qty,
                                'delivered_qty' => $quotedelveredqty,
                                'transfered_qty' =>  $quotetransfered_qty,
                                'prdstatus' => $prdstatus
                            );
                            // echo "<pre>"; print_r($quotedata); die();
                            // $this->quote->quote_items($quote_id,$product_id[$key],$quotedata);
                        }
                        $this->db->insert('cberp_sales_orders_items', $data);
                        // echo $this->db->last_query();
                        $flag = true;
                        $productlist[$prodindex] = $data;
                        $i += numberClean($product_qty[$key]);;
                        $prodindex++;
                    }
                }


                if(!empty($productlist))
                {
                    
                    // $this->quote->update_quote_status($quote_id, $salesorder_number, $salesorderid);
                    $total_discount = rev_amountExchange_s(amountFormat_general($total_discount), $currency, $this->aauth->get_user()->loc);
                    $total_tax = rev_amountExchange_s(amountFormat_general($total_tax), $currency, $this->aauth->get_user()->loc);
                    $data1 = array('invoicedate' => $invoicedate, 'invoiceduedate' => $invocieduedate, 'subtotal' => $subtotal,'total' => $total, 'notes' => $notes,  'discstatus' => $discstatus, 'format_discount' => $discountFormat, 'refer' => $refer, 'term' => $pterms, 'proposal' => $propos, 'customer_order_date'=>$customer_order_date, 'customer_purchase_order'=>$customer_purchase_order, 'items' => $i,'discount' => $total_discount,'tax' => $total_tax);
                    $this->db->update('cberp_sales_orders', $data1, ['id'=> $salesorderid]);
                    history_table_log('cberp_sales_orders_log','sales_order_id',$salesorderid,'Update');
                    echo json_encode(array('status' => 'Success'));
                }
            }

        // if ($transok) {
        //     $this->db->trans_complete();
        // } else {
        //     $this->db->trans_rollback();
        // }
    }

    public function completed_salesorder(){
        $saleorder_id = $this->input->post('salesorder_id');
        $this->db->update('cberp_sales_orders', ['completed_status'=> '1'], ['id'=> $saleorder_id]);
        echo json_encode(array('status' => 'Success'));
    }
    public function update_order_warehouse()
    {
        $invocieno_id = $this->input->post('invocieno_id');
        $store_id = $this->input->post('store_id') ? $this->input->post('store_id') : null;

        if ($invocieno_id) {
            $this->db->update('cberp_sales_orders',['store_id' => $store_id],['id' => $invocieno_id]);
            echo json_encode(array('status' => 'success'));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Invalid ID'));
        }
    }

   public function insert_delivery_note_from_sales_order()
   {
    // ini_set('display_errors', 1);
    // ini_set('display_startup_errors', 1);
    // error_reporting(E_ALL);
     $prefix = $this->plugins->universal_api(72);
     $result = $this->salesorder->insert_delivery_note_from_sales_order($this->input->post('salesorder_id'),$prefix['name']);
     $res= $this->input->post('salesorder_id');
     if($result){
        echo json_encode(array('status' => '1'));
     }
   }

   //erp2024 21-08-2024
   function selected_items_for_material_request()
    {
        
        $salesPro=$this->input->post('selectedProducts');       
        $data['selectedProducts'] = $salesPro;
        $productIds = explode(",",$salesPro);
        $products = $this->products->products_list_by_id($productIds);
        $html = '';
        $i = 0;
        foreach ($products as $product) {
            $prdid = $product['pid'];
            $html .= '<tr class="appendeditems">';
            $html .= '<td>';
            $html .= '<select name="product-name[]" id="product-name-' . $i . '" class="form-control" onchange="warehouseList(\'' . $i . '\')">';
            $html .= '<option value="">' . $this->lang->line('Select Product') . '</option>';
            foreach ($products as $row) {
                $html .= '<option value="' . $row['pid'] . '">' . $row['product_name'] . ' - ' . $row['product_code'] . '</option>';
            }
            $html .= '</select>';
            $html .= '</td>';
            $html .= '<td>';
            $html .= '<select name="warehousefrom[]" id="warehousefrom-' . $i . '" class="form-control warehousefrom">';
            $html .= '<option value="">' . $this->lang->line('Select Warehouse') . '</option>';
            $html .= '</select>';
            $html .= '</td>';
            $html .= '<td><input type="text" class="form-control req prc" name="transferqty[]" id="transferqty-' . $i . '" onkeypress="return isNumber(event)" onkeyup="rowTotal(\'0\'), billUpyog()" autocomplete="off"></td>';
            $html .= '<td class="text-center">--</td>';
            $html .= '</tr>';

            $i++;
        }

        // Output the HTML
        echo $html;
    }

    public function delivery_notes(){
        $salesorder_number = $this->input->get('id');
        $this->load->model('deliverynote_model', 'deliverynote');
        $data['customer'] = $this->salesorder->get_customer_by_salesorder_number($salesorder_number);
        $data['salesorderdetails'] = $this->salesorder->salesorder_details_by_salesorder_number($salesorder_number);
        
        $data['deliverynotedata'] = $this->salesorder->get_delivery_note_data($salesorder_number);
        // $checkedres = $this->deliverynote->check_delivered_and_return_qty_equal($data['deliverynotedata'][0]->delevery_note_id);
        $data['prdstatus'] = $this->salesorder->get_prdstatus_salesorderid($data['salesorderdetails']['id']);        
        $data['trackingdata'] = tracking_details('sales_number',$salesorder_number);
        // echo "<pre>"; print_r($checkedres); die();
        $head['title'] = "Deliverty Note Landing";
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->view('fixed/header', $head);
        $this->load->view('sales/deliverynotes_against_salesorder', $data);
        $this->load->view('fixed/footer');
    }
    public function write_off() {      
        $salesorder_id = $this->input->post('salesorder_id');
        $product_ids = $this->input->post('selectedProducts');  // This is an array
    
        // Ensure that the product_ids array is not empty
        if (!empty($salesorder_id)) {
            $this->db->select('*');
            $this->db->from('cberp_sales_orders_items');
            $this->db->where('tid', $salesorder_id);
            // $this->db->where_in('pid', $product_ids);
    
            $query = $this->db->get();
            
            // Check if any results were found
            if ($query->num_rows() > 0) {
                $result = $query->result_array();
                $table = $this->generate_product_table($result, $salesorder_id);
                echo json_encode(['status' => 'success', 'table' => $table]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No matching records found.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No products selected.']);
        }
    }

    private function generate_product_table($products, $salesorder_id) {
        $i = 5000;        
        $table = '<table class="table table-striped table-bordered zero-configuration dataTable">';
        $table .= '<thead>';
        $table .= '<tr>';
        $table .= '<th width="25%">Item Name & No.</th>';
        $table .= '<th class="text-center" width="5%">Ordered Qty</th>';
        $table .= '<th class="text-center" width="5%">Delivered Qty</th>';
        $table .= '<th class="text-center" width="5%">Rem. Qty</th>';
        $table .= '<th class="text-center" width="5%">Write Off Qty</th>';
        $table .= '</tr>';
        $table .= '</thead>';
        $table .= '<tbody>';
    
        foreach ($products as $item) { //0
            $del_remqty = ($item['del_remaining_qty']>0) ? intval($item['del_remaining_qty']): intval($item['qty']);
            $del_dlvrdqty = ($item['del_transfered_qty']>0) ? intval($item['del_transfered_qty']): 0;
            $prodcutnamewithcode = $item['product']."(".$item['code'].")";
            $prodcutnamewithcode = htmlspecialchars($prodcutnamewithcode);
            $table .= '<tr>';
            $table .= '<td>' . $prodcutnamewithcode . '<input type="hidden" class="form-control" name="product_id[]" id="product_id-' . $i .  '" value="' . $item['pid'] . '"><input type="hidden" class="form-control" name="price[]" id="price-' . $i .  '" value="' . $item['price'] . '"></td>';
            $table .= '<td class="text-center">'.intval($item['qty']).'</td>';           
            $table .= '<td class="text-center">'.$del_dlvrdqty.'</td>';
            $table .= '<td class="text-center"><input type="hidden" name="del_rem_qty[]" id="del_rem_qty' . $i .  '" class="form-control" value="'.$del_remqty.'">'.$del_remqty.'</td>';
            $table .= '<td class="text-center"><input type="number" name="write_off_quantity[]" class="form-control" onkeyup="washoutqty_validate('.$i.'), rowTotal('.$i.'), billUpyog()" id="write_off_quantity' . $i .  '" title="'.$prodcutnamewithcode.'-Quantity">';
            $table .= '<input type="hidden" name="actualrem[]" id="amount-' . $i .  '" class="form-control">';
            $table .= '<input type="hidden" class="form-control" name="discounttype[]" id="discounttype-' . $i .  '" value="'.$item['discount_type'].'">';
          
            if($item['discount_type']=="Amttype"){
                $table .= ' <input type="hidden" min="0" class="form-control discount" name="product_discount[]"  id="discount-'.$i.'" value="0"><input type="hidden" class="form-control discount" name="product_amt[]" id="discountamt-'.$i.'" value="'.$item['discount'].'">';
            }
            else{
                $table .= ' <input type="hidden" min="0" class="form-control discount" name="product_discount[]"  id="discount-'.$i.'" value="'.$item['discount'].'"><input type="hidden" class="form-control discount" name="product_amt[]" id="discountamt-'.$i.'" value="0">';
            }
            $table .= '<input type="hidden" name="disca[]" id="disca-'.$i.'" value="'.$item['totaldiscount'].'"><input type="hidden" class="ttInput" name="product_subtotal[]" id="total-'.$i.'" value="'.$item['subtotal'].'">';
            $table .= '</td>';
            $table .= '</tr>';
            $i++;
        }
    
        $table .= '</tbody>';
        $table .= '</table>';

        
        
        $table .='<div class="container-fluid text-right" id="button-potion">';
        $table .='<input type="hidden" class="form-control" name="salesorder_id_writeoff" id="salesorder_id_writeoff"  value="'.$salesorder_id.'">';
        $table .='<input type="hidden" class="form-control" name="config_tax" id="config_tax"  value="'.$this->configurations["config_tax"].'">';
        $table .= '<button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">Cancel</button>&nbsp;&nbsp;';
        $table .= '<button type="button" class="btn btn-primary" id="write_off_submit_btn" onclick="write_off_btn_click()">Washout Now</button>&nbsp;&nbsp;';
        $table .= '</div>';
        return $table;
    }


    function write_off_action()
    {
 
        $product_idlist=$this->input->post('product_id');   
        $del_rem_qty=$this->input->post('del_rem_qty');   
        $write_off_quantity=$this->input->post('write_off_quantity');   
        $salesorder_id=$this->input->post('salesorder_id_writeoff');   
        $product_subtotal = $this->input->post('product_subtotal');
        $ptotal_disc = $this->input->post('disca');     
        $discount_type = $this->input->post('discount_type');
        $product_amt = $this->input->post('product_amt');
        $product_discount = $this->input->post('product_discount'); 
        $discountamount = 0;
        $totaldiscounts = 0;
        $totalamount = 0;
        if(!empty($product_idlist))
        {
            
            foreach($product_idlist as $key=>$item)
            {

                if($write_off_quantity[$key]>0)
                {
                    // $totaldiscounts = $totaldiscounts + $ptotal_disc[$key];
                    // $totalamount = $totalamount + $product_subtotal[$key];

                    if($discount_type[$key]=="Amttype"){
                        $discountamount = numberClean($product_amt[$key]);
                    }
                    else if($discount_type[$key]=="Perctype"){
                        $discountamount = numberClean($product_discount[$key]);
                    }
                    $product_id = $product_idlist[$key];
                    $items = [];
                    $itemdatadata = [];
                    $prdstatus = 0;
                    $del_remaining_qty = 0;
                    if($write_off_quantity[$key]==$del_rem_qty[$key]){
                        // $items['del_remaining_qty'] = '0';
                        // $items['prdstatus'] = '1';
                        $del_remaining_qty = 0;
                        $prdstatus = '1';
                    }
                    else{
                        // $items['del_remaining_qty'] = intval($del_rem_qty[$key]) - intval($write_off_quantity[$key]);
                        $del_remaining_qty = intval($del_rem_qty[$key]) - intval($write_off_quantity[$key]);
                    }
                    if (strpos($product_subtotal[$key], ',') !== false) {
                        $product_subtotal[$key] = str_replace(',', '', $product_subtotal[$key]);
                    }
                    if (strpos($ptotal_disc[$key], ',') !== false) {
                        $ptotal_disc[$key] = str_replace(',', '', $ptotal_disc[$key]);
                    }
                    $existing_writeoff_qty = $this->salesorder->get_write_off_quantity($product_id,$salesorder_id);
                    $totwrite_off = $existing_writeoff_qty + $write_off_quantity[$key];
                   
                    //[subtotal] => 0.00
                    // [totaldiscount] => 0.00
                    // [del_remaining_qty] => 2
                    $existing_products = $this->salesorder->existing_salesorder_products($product_id,$salesorder_id);
                    $existing_products_subtotal = $existing_products['subtotal'];
                    $existing_products_totaldiscount = $existing_products['totaldiscount'];
                    // $existing_products = $existing_products[];
                    // $subtotal = $existing_products_subtotal - $product_subtotal[$key];
                    // $totaldiscount = $existing_products_totaldiscount - $ptotal_disc[$key];
                    $subtotal = ($prdstatus==1) ? "0.00" : $product_subtotal[$key]; 
                    $totaldiscount = ($prdstatus==1) ? "0.00" : $ptotal_disc[$key];
                    $totaldiscounts = $totaldiscounts + $totaldiscount;
                    $totalamount = $totalamount + $subtotal;
                    // if($product_subtotal[$key]>0)
                    // {
                        $items = [
                            'write_off_quantity'   => intval($totwrite_off),
                            'subtotal'        => $subtotal,
                            'totaldiscount'   => $totaldiscount,
                            // 'subtotal'        => "subtotal - {$product_subtotal[$key]}",
                            // 'totaldiscount'   => "totaldiscount - {$ptotal_disc[$key]}",
                            'write_off_by'    => $this->session->userdata('id'),
                            'write_off_date'  => date("Y-m-d"),
                            'write_off_time'  => date("H:i:s"),
                            'prdstatus'       => $prdstatus,
                            'del_remaining_qty' => $del_remaining_qty,
                        ];   
                        // print_r($existing_products);
                        // echo $product_subtotal[$key]."\n<br>";
                        $this->db->update('cberp_sales_orders_items',$items, ['tid'=>$salesorder_id,'pid'=>$product_id]);
                        // echo "\n<br><pre>";  print_r( $this->db->last_query());
                        
                //    }
                  
                }
            }
            
            $masterdata = [
                'discount' => $totaldiscounts,
                'subtotal' => $totalamount,
                'total'    => $totalamount
            ];
            
            $this->db->update('cberp_sales_orders',$masterdata, ['id'=>$salesorder_id]);
            
            detailed_log_history($this->module_number,$salesorder_id,'Write Off', $_POST['changedFields']);
            
            echo json_encode(['status' => 'Success', 'message' => 'Write-off operation successfully completed']);
        } else {
            // If no products are selected, return error
            echo json_encode(['status' => 'Error', 'message' => 'No products selected for write-off']);
        }
       
    }

    public function get_salesorder_count_filter()
    {
        $filter_status = $this->input->post('filter_status');
        // $filter_employee = $this->input->post('filter_employee');
        
        $filter_expiry_date_from = !empty($this->input->post('filter_expiry_date_from')) ? date('Y-m-d',strtotime($this->input->post('filter_expiry_date_from'))) : ""; 

        $filter_expiry_date_to = !empty($this->input->post('filter_expiry_date_to')) ? date('Y-m-d',strtotime($this->input->post('filter_expiry_date_to'))) : "";

        $filter_price_from = !empty($this->input->post('filter_price_from')) ? $this->input->post('filter_price_from') : 0;
        $filter_price_to = !empty($this->input->post('filter_price_to')) ? $this->input->post('filter_price_to'): 0;

        $filter_customer = !empty($this->input->post('filter_customer')) ?$this->input->post('filter_customer') : "";
        // $filter_customertype = !empty($this->input->post('filter_customertype')) ?$this->input->post('filter_customertype') : "";
 
        $results = $this->salesorder->get_salesorder_count_filter('invoicedate','total',$filter_status,$filter_expiry_date_from,$filter_expiry_date_to,$filter_price_from,$filter_price_to,$filter_customer);        
        foreach ($results as $key => $value) {
            if (empty($value)) {
                $results[$key] = 0;
            }
        }
        
        echo json_encode(array('status' => 'success','data'=>$results));
    }

    //erp2024 21-10-2024 CREATE SALESORDER starts
    public function create()
    {
      //  $data['permissions'] = load_permissions('Sales','Sales','Sales Orders','Create Page');
        $this->load->model('plugins_model', 'plugins');
        $data['emp'] = $this->plugins->universal_api(69);
        if ($data['emp']['key1']) {
            $this->load->model('employee_model', 'employee');
            $data['employee'] = $this->employee->list_employee();
        }
        $this->load->library("Common");
        // $data['taxlist'] = $this->common->taxlist($this->config->item('tax'));
        // $this->load->model('customers_model', 'customers');
        // $this->load->model('plugins_model', 'plugins');
        // $data['exchange'] = $this->plugins->universal_api(5);
        // $data['currency'] = $this->quote->currencies();
        // $data['customergrouplist'] = $this->customers->group_list();
        
        $salesorder_id = $this->input->get('id');
        $data['salesorder_id'] = $salesorder_id;
        

        // echo "<pre>"; print_r($data['masterdetails']); die();
        if(($salesorder_id))
        {
            $data['salesorder_id'] = $salesorder_id;
            $data['masterdetails'] = $this->salesorder->salesorder_details($salesorder_id);
            $data['products'] = $this->salesorder->salesorder_products($salesorder_id);
            $data['id'] =  $salesorder_id;
        }
        else{
            $data['masterdetails'] = [];
            $data['products'] = [];
            $data['id'] =  $this->quote->salesorder_number() ;
        }

        $data['currency'] = $this->quote->currencies();
        // $head['title'] = "Quote To Sales Order #" . $data['invoice']['tid'];
        $head['title'] = "Create new Sales Order";
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['warehouse'] = $this->quote->warehouses();
        $this->load->model('plugins_model', 'plugins');
        $data['exchange'] = $this->plugins->universal_api(5);
        $this->load->library("Common");
        $data['taxlist'] = $this->common->taxlist_edit($data['invoice']['taxstatus']);                
        $data['configurations'] = $this->configurations;
        $this->load->view('fixed/header', $head);
        $this->load->view('sales/create-new-salesorder', $data);
        
        // $this->load->view('sales/salesorder_draft_or_edit', $data);
        $this->load->view('fixed/footer');
    }


    public function saleorderaction()
    {
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);  delete
        $masterdata = [];
        $discountchecked = $this->input->post('discountchecked');
    
        if (isset($discountchecked)) {
            $discountchecked = $discountchecked;
        } else {
            $discountchecked = 0;
        }
        $customer_id = $this->input->post('customer_id');
        $invocieno_n = $this->input->post('invocieno');
        $invocieno = $this->input->post('iid');
        // $quote_id = $this->input->post('quote_id');
        $completed_status =  $this->input->post('completed_status');
        $salesorderids = $invocieno_n - 1000;
        
       
    
        $invoicedate = $this->input->post('invoicedate');
        $invocieduedate = date('Y-m-d', strtotime($this->input->post('invocieduedate')));
        $notes = $this->input->post('notes', true);
        $tax = $this->input->post('tax_handle');
        $total_tax = 0;
        $total_discount = 0;
        $discountFormat = $this->input->post('discountFormat');
        // erp2024 remove pterms 06-06-2024 status
        // $pterms = $this->input->post('pterms');
        $customer_reference_number = $this->input->post('customer_reference_number');
        $customer_contact_person = $this->input->post('customer_contact_person');
        $customer_contact_number = $this->input->post('customer_contact_number');
        $customer_contact_email = $this->input->post('customer_contact_email');
        $proposal = $this->input->post('propos');
    
        $currency = $this->input->post('mcurrency');
        $ship_taxtype = $this->input->post('ship_taxtype');
        $subtotal = rev_amountExchange_s($this->input->post('subtotal'), $currency, $this->aauth->get_user()->loc);
        $shipping = rev_amountExchange_s($this->input->post('shipping'), $currency, $this->aauth->get_user()->loc);
        $shipping_tax = rev_amountExchange_s($this->input->post('ship_tax'), $currency, $this->aauth->get_user()->loc);
        if ($ship_taxtype == 'incl') $shipping = $shipping - $shipping_tax;
        $refer = $this->input->post('refer', true);
        $total = rev_amountExchange_s($this->input->post('total'), $currency, $this->aauth->get_user()->loc);
        // 08-08-2024 erp2024 newlyadded fields
        $customer_purchase_order = $this->input->post('customer_purchase_order', true);
        $customer_order_date = date('Y-m-d', strtotime($this->input->post('customer_order_date')));    
        $order_discount = rev_amountExchange_s($this->input->post('order_discount'), $currency, $this->aauth->get_user()->loc);
        if ($customer_id == 0) {
            echo json_encode(array('status' => 'Error', 'message' =>
                $this->lang->line('Please add a new client')));
            exit;
        }
    
        $masterdata = [
            'tid' => $invocieno_n,
            'seq_number' => 1,
            'salesorder_number' => $invocieno_n,
            'invoicedate' => date('Y-m-d', strtotime($invoicedate)),
            'invoiceduedate' => date('Y-m-d', strtotime($invocieduedate)),
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'ship_tax' => $ship_tax,
            'ship_tax_type' => $ship_tax_type,
            'tax' => $tax,
            'total' => $total,
            'pmethod' => $pmethod,
            'notes' => $notes,
            'status' => 'pending',
            'csd' => $customer_id,
            'eid' => $this->session->userdata('id'),
            // 'pamnt' => $pamnt,
            // 'items' => $items,
            'format_discount' => $discountFormat,
            'refer' => $refer,
            // 'term' => $term,
            'proposal' => $proposal,
            'customer_order_date' => $customer_order_date,
            'customer_purchase_order' => $customer_purchase_order,
            'completed_status' => $completed_status,
            'customer_reference_number' => $customer_reference_number,
            'customer_contact_person' => $customer_contact_person,
            'customer_contact_number' => $customer_contact_number,
            'customer_contact_email' => $customer_contact_email,
            'order_discount' => $order_discount
        ];
        $this->db->insert('cberp_sales_orders',$masterdata);
        $insert_id = $this->db->insert_id();
            // file upload section starts 22-01-2025
            if($_FILES['upfile'])
            {
                upload_files($_FILES['upfile'], 'Salesorder',$insert_id);
            }
            // file upload section ends 22-01-2025
        history_table_log('cberp_sales_orders_log','sales_order_id',$insert_id,'Create');
         //erp2024 06-01-2025 detailed history log starts
         detailed_log_history($this->module_number,$insert_id,'Created', $_POST['changedFields']);
        //erp2024 06-01-2025 detailed history log ends 
         //insert to tracking table
        $this->db->insert('cberp_transaction_tracking',['sales_id'=>$insert_id,'sales_number'=>$invocieno_n]);
    
    
        //sales order items starts
        $productlist = array();
        $prodindex = 0;
        $itc = 0;
        $flag = false;
        $product_id = $this->input->post('pid');
        $product_name1 = $this->input->post('product_name', true);
        $code = $this->input->post('code', true);
        $product_qty = $this->input->post('product_qty');
        $product_price = $this->input->post('product_price');
        $product_tax = $this->input->post('product_tax');
        $product_discount = $this->input->post('product_discount');
        $product_subtotal = $this->input->post('product_subtotal');
        $ptotal_tax = $this->input->post('taxa');
        $ptotal_disc = $this->input->post('disca');
        // $product_des = $this->input->post('product_description', true);
        $product_hsn = $this->input->post('hsn');
        $product_unit = $this->input->post('unit');
        $discount_type = $this->input->post('discount_type');
        $lowest_price = $this->input->post('lowest_price');
        $max_disrate = $this->input->post('maxdiscountrate');
        $product_amt = $this->input->post('product_amt');
        $product_tax =0;
        $grandtotal=0;
       
        foreach ($product_id as $key => $value) {
            if(!empty($product_id[$key]) && !empty($product_name1[$key]) && $product_qty[$key]>0)
            {
                $total_discount += numberClean(@$ptotal_disc[$key]);
                $total_tax += numberClean($ptotal_tax[$key]);
                
                if($discount_type[$key]=="Amttype"){
                    $discountamount = numberClean($product_amt[$key]);
                }
                else{
                    $discountamount = numberClean($product_discount[$key]);
                }
                if($this->configurations["config_tax"]!="0"){ 
                    $product_tax = numberClean($product_tax[$key]);
                }
                
                $data = array(
                    'tid' => $insert_id,
                    'pid' => $product_id[$key],
                    'product' => $product_name1[$key],
                    'code' => $code[$key],
                    'qty' => numberClean($product_qty[$key]),
                    'remaining_qty' => numberClean($product_qty[$key]),
                    'ordered_qty' => numberClean($product_qty[$key]),
                    'transfered_qty' => 0,
                    'delivered_qty' => 0,
                    'price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
                    'tax' => $product_tax,
                    'discount' => $discountamount,
                    'total_amount' => rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc),
                    'totaltax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                    'totaldiscount' => rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc),
                    'unit' => $product_unit[$key],
                    'discount_type' => $discount_type[$key],
                    'lowest_price' => $lowest_price[$key],
                    'max_disrate' => $max_disrate[$key],
                    
    
                );
    
                $flag = true;
                $productlist[$prodindex] = $data;
            }   
            $i++;
            $prodindex++;
            $amt = numberClean($product_qty[$key]);
            $itc += $amt;
            $grandtotal += rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc); 
        }
        if ($prodindex > 0) {
            $grandtotal = $grandtotal - $order_discount;
            $this->db->insert_batch('cberp_sales_orders_items', $productlist);
            $this->db->set(array('discount' => rev_amountExchange_s(amountFormat_general($total_discount), $currency, $this->aauth->get_user()->loc), 'tax' => rev_amountExchange_s(amountFormat_general($total_tax), $currency, $this->aauth->get_user()->loc), 'items' => $itc, 'subtotal'=>$grandtotal, 'total'=>$grandtotal));
            $this->db->where('id', $insert_id);
            $this->db->update('cberp_sales_orders');
        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
                "Please choose product from product list. Go to Item manager section if you have not added the products."));
            $transok = false;
        } 
        //sales order items ends
    
    
        // $this->db->trans_start();
        $flag = false;
        $transok = true;
    
    
        echo json_encode(array('status' => 'Success'));
            $transok = false;
        if ($transok) {
            $this->db->trans_complete();
        } else {
            $this->db->trans_rollback();
        }
    }

    //erp2024 21-10-2024 ENDS 

    //erp2024 01-02-2025 starts
    public function draft_or_edit()
    {
        $data['permissions'] = load_permissions('Sales','Sales','Sales Orders','Create Page');
        $this->load->model('plugins_model', 'plugins');
        $data['emp'] = $this->plugins->universal_api(69);
        if ($data['emp']['key1']) {
            $this->load->model('employee_model', 'employee');
            $data['employee'] = $this->employee->list_employee();
        }
        $this->load->library("Common"); 
        $salesorder_id = $this->input->get('id');
        $data['id'] = $salesorder_id;
        $data['salesorder_id'] = $salesorder_id;
        $data['masterdetails'] = $this->salesorder->salesorder_details($salesorder_id);
        $data['products'] = $this->salesorder->salesorder_products($salesorder_id);

        // echo "<pre>";  print_r($data['masterdetails']); die();
        $data['currency'] = $this->quote->currencies();
        $head['title'] = "Sales Order #".$data['masterdetails']['tid'];
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['warehouse'] = $this->quote->warehouses();
        $this->load->model('plugins_model', 'plugins');
        $data['exchange'] = $this->plugins->universal_api(5);
        $this->load->library("Common");
        $data['taxlist'] = $this->common->taxlist_edit($data['invoice']['taxstatus']);                
        $data['configurations'] = $this->configurations;
        $data['images'] = get_uploaded_images('Salesorder',$salesorder_id);

         //erp2024 06-01-2025 detailed history log starts
         $page = $this->module_number;
         $data['detailed_log']= get_detailed_logs($salesorder_id,$page);
         $products = $data['detailed_log'];
         $groupedBySequence = []; 
         foreach ($products as $product) {
             $sequence = $product['seqence_number'];
             $groupedBySequence[$sequence][] = $product;
         }
         
         $data['groupedDatas'] = $groupedBySequence;
         //erp2024 06-01-2025 detailed history log starts

        $this->load->view('fixed/header', $head);
        $this->load->view('sales/salesorder_draft_or_edit', $data);
        $this->load->view('fixed/footer');
    }
    //erp2024 01-02-2025 ends

    // erp2024 03-02-2025
    public function saleordereditaction()
    {
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);  converted_status 
        $masterdata = [];
        $customer_id = $this->input->post('customer_id');
        $invocieno_n = $this->input->post('invocieno');
        $invocieno = $this->input->post('iid');
        // $quote_id = $this->input->post('quote_id');
        $completed_status =  $this->input->post('completed_status');
        $salesorderids = $this->input->post('salesorder_id');
        
       
    
        $invoicedate = $this->input->post('invoicedate');
        $invocieduedate = date('Y-m-d', strtotime($this->input->post('invocieduedate')));
        $notes = $this->input->post('notes', true);
        $tax = $this->input->post('tax_handle');
        $total_tax = 0;
        $total_discount = 0;
        $discountFormat = $this->input->post('discountFormat');
        // erp2024 remove pterms 06-06-2024
        // $pterms = $this->input->post('pterms');
        $customer_reference_number = $this->input->post('customer_reference_number');
        $customer_contact_person = $this->input->post('customer_contact_person');
        $customer_contact_number = $this->input->post('customer_contact_number');
        $customer_contact_email = $this->input->post('customer_contact_email');
        $proposal = $this->input->post('propos');
        $store_id = $this->input->post('store_id');
    
        $currency = $this->input->post('mcurrency');
        $ship_taxtype = $this->input->post('ship_taxtype');
        $subtotal = rev_amountExchange_s($this->input->post('subtotal'), $currency, $this->aauth->get_user()->loc);
        $shipping = rev_amountExchange_s($this->input->post('shipping'), $currency, $this->aauth->get_user()->loc);
        $shipping_tax = rev_amountExchange_s($this->input->post('ship_tax'), $currency, $this->aauth->get_user()->loc);
        if ($ship_taxtype == 'incl') $shipping = $shipping - $shipping_tax;
        $refer = $this->input->post('refer', true);
        $total = rev_amountExchange_s($this->input->post('total'), $currency, $this->aauth->get_user()->loc);
        // 08-08-2024 erp2024 newlyadded fields 
        $customer_purchase_order = $this->input->post('customer_purchase_order', true);
        $customer_order_date = date('Y-m-d', strtotime($this->input->post('customer_order_date')));    
        $order_discount = rev_amountExchange_s($this->input->post('order_discount'), $currency, $this->aauth->get_user()->loc);
        if ($customer_id == 0) {
            echo json_encode(array('status' => 'Error', 'message' =>
                $this->lang->line('Please add a new client')));
            exit;
        }
    
        $masterdata = [
            'tid' => $invocieno_n,
            'seq_number' => 1,
            'salesorder_number' => $invocieno_n,
            'invoicedate' => date('Y-m-d', strtotime($invoicedate)),
            'invoiceduedate' => date('Y-m-d', strtotime($invocieduedate)),
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'ship_tax' => $ship_tax,
            'ship_tax_type' => $ship_tax_type,
            'tax' => $tax,
            'total' => $total,
            'pmethod' => $pmethod,
            'notes' => $notes,
            // 'status' => $status,
            'csd' => $customer_id,
            'eid' => $this->session->userdata('id'),
            // 'pamnt' => $pamnt,
            // 'items' => $items,
            'format_discount' => $discountFormat,
            'refer' => $refer,
            // 'term' => $term,
            'proposal' => $proposal,
            'customer_order_date' => $customer_order_date,
            'customer_purchase_order' => $customer_purchase_order,
            'completed_status' => $completed_status,
            'customer_reference_number' => $customer_reference_number,
            'customer_contact_person' => $customer_contact_person,
            'customer_contact_number' => $customer_contact_number,
            'customer_contact_email' => $customer_contact_email,
            'order_discount' => $order_discount,
            'converted_status' => 0,
            'store_id' => $store_id,
        ];
        $this->db->update('cberp_sales_orders',$masterdata,['id'=>$salesorderids]);
       
        $this->db->delete('cberp_sales_orders_items', array('tid' => $salesorderids));
        // file upload section starts 22-01-2025
        if($_FILES['upfile'])
        {
            upload_files($_FILES['upfile'], 'Salesorder',$salesorderids);
        }
         // file upload section ends 22-01-2025
        //  history_table_log('cberp_sales_orders_log','sales_order_id',$salesorderids,'Update');
         //erp2024 06-01-2025 detailed history log starts
         detailed_log_history($this->module_number,$salesorderids,'Updated', $_POST['changedFields']);
        //erp2024 06-01-2025 detailed history log ends 
       
    
    
        //sales order items starts
        $productlist = array();
        $prodindex = 0;
        $itc = 0;
        $flag = false;
        $product_id = $this->input->post('pid');
        $product_name1 = $this->input->post('product_name', true);
        $code = $this->input->post('code', true);
        $product_qty = $this->input->post('product_qty');
        $product_price = $this->input->post('product_price');
        $product_tax = $this->input->post('product_tax');
        $product_discount = $this->input->post('product_discount');
        $product_subtotal = $this->input->post('product_subtotal');
        $ptotal_tax = $this->input->post('taxa');
        $ptotal_disc = $this->input->post('disca');
        // $product_des = $this->input->post('product_description', true);
        $product_hsn = $this->input->post('hsn');
        $product_unit = $this->input->post('unit');
        $discount_type = $this->input->post('discount_type');
        $lowest_price = $this->input->post('lowest_price');
        $max_disrate = $this->input->post('maxdiscountrate');
        $product_amt = $this->input->post('product_amt');
        $product_tax =0;
        $grandtotal=0;
       
        foreach ($product_id as $key => $value) {
            if(!empty($product_id[$key]) && !empty($product_name1[$key]) && $product_qty[$key]>0)
            {
                $total_discount += numberClean(@$ptotal_disc[$key]);
                $total_tax += numberClean($ptotal_tax[$key]);
                
                if($discount_type[$key]=="Amttype"){
                    $discountamount = numberClean($product_amt[$key]);
                }
                else{
                    $discountamount = numberClean($product_discount[$key]);
                }
                if($this->configurations["config_tax"]!="0"){ 
                    $product_tax = numberClean($product_tax[$key]);
                }
                
                $data = array(
                    'tid' => $salesorderids,
                    'pid' => $product_id[$key],
                    'product' => $product_name1[$key],
                    'code' => $code[$key],
                    'qty' => numberClean($product_qty[$key]),
                    'remaining_qty' => numberClean($product_qty[$key]),
                    'ordered_qty' => numberClean($product_qty[$key]),
                    'transfered_qty' => 0,
                    'delivered_qty' => 0,
                    'price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
                    'tax' => $product_tax,
                    'discount' => $discountamount,
                    'total_amount' => rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc),
                    'totaltax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                    'totaldiscount' => rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc),
                    'unit' => $product_unit[$key],
                    'discount_type' => $discount_type[$key],
                    'lowest_price' => $lowest_price[$key],
                    'max_disrate' => $max_disrate[$key],
                    
    
                );
    
                $flag = true;
                $productlist[$prodindex] = $data;
            }   
            $i++;
            $prodindex++;
            $amt = numberClean($product_qty[$key]);
            $itc += $amt;
            $grandtotal += rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc); 
        }
        if ($prodindex > 0) {
            $grandtotal = $grandtotal - $order_discount;
            $this->db->insert_batch('cberp_sales_orders_items', $productlist);
            $this->db->set(array('discount' => rev_amountExchange_s(amountFormat_general($total_discount), $currency, $this->aauth->get_user()->loc), 'tax' => rev_amountExchange_s(amountFormat_general($total_tax), $currency, $this->aauth->get_user()->loc), 'items' => $itc, 'subtotal'=>$grandtotal, 'total'=>$grandtotal));
            $this->db->where('id', $salesorderids);
            $this->db->update('cberp_sales_orders');
        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
                "Please choose product from product list. Go to Item manager section if you have not added the products."));
            $transok = false;
        } 
        //sales order items ends
    
    
        // $this->db->trans_start();
        $flag = false;
        $transok = true;
    
    
        echo json_encode(array('status' => 'Success'));
            $transok = false;
        if ($transok) {
            $this->db->trans_complete();
        } else {
            $this->db->trans_rollback();
        }
    }
    public function convert_salesorder_to_deliverynote()
    {
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);   
        
        $masterdata = [];
        $customer_id = $this->input->post('customer_id');
        $invocieno_n = $this->input->post('invocieno');
        $invocieno = $this->input->post('iid');
        $completed_status =  $this->input->post('completed_status');
        $salesorderids = $this->input->post('salesorder_id');
        $salesorder_number = $this->input->post('so_prefix_number');
        
        // $records = record_exists_or_not('cberp_sales_orders','id',$salesorderids);
        
    
        $invoicedate = $this->input->post('invoicedate');
        $invocieduedate = date('Y-m-d', strtotime($this->input->post('invocieduedate')));
        $notes = $this->input->post('notes', true);
        $tax = $this->input->post('tax_handle');
        $total_tax = 0;
        $total_discount = 0;
        $discountFormat = $this->input->post('discountFormat');
        // erp2024 remove pterms 06-06-2024 
        // $pterms = $this->input->post('pterms');
        $customer_reference_number = $this->input->post('customer_reference_number');
        $customer_contact_person = $this->input->post('customer_contact_person');
        $customer_contact_number = $this->input->post('customer_contact_number');
        $customer_contact_email = $this->input->post('customer_contact_email');
        $proposal = $this->input->post('propos');
        
    
        $currency = $this->input->post('mcurrency');
        $ship_taxtype = $this->input->post('ship_taxtype');
        $subtotal = rev_amountExchange_s($this->input->post('subtotal'), $currency, $this->aauth->get_user()->loc);
        $shipping = rev_amountExchange_s($this->input->post('shipping'), $currency, $this->aauth->get_user()->loc);
        $shipping_tax = rev_amountExchange_s($this->input->post('ship_tax'), $currency, $this->aauth->get_user()->loc);
        if ($ship_taxtype == 'incl') $shipping = $shipping - $shipping_tax;
        $refer = $this->input->post('refer', true);
        $total = rev_amountExchange_s($this->input->post('total'), $currency, $this->aauth->get_user()->loc);
        // 08-08-2024 erp2024 newlyadded fields 
        $customer_purchase_order = $this->input->post('customer_purchase_order', true);
        $customer_order_date = date('Y-m-d', strtotime($this->input->post('customer_order_date')));    
        $order_discount = rev_amountExchange_s($this->input->post('order_discount'), $currency, $this->aauth->get_user()->loc);
        if ($customer_id == 0) {
            echo json_encode(array('status' => 'Error', 'message' =>
                $this->lang->line('Please add a new client')));
            exit;
        }

        $store_id = $this->input->post('store_id');
        $masterdata = [
            'salesorder_number' => $salesorder_number,
            // 'due_date' => date('Y-m-d', strtotime($invocieduedate)),
            'customer_id' => $customer_id,
            'employee_id' => $this->session->userdata('id'),
            'reference' => $refer,
            'customer_message' => $proposal,
            'shipping' => $shipping,
            'shipping_tax' => $ship_tax,
            'shipping_tax_type' => $ship_tax_type,
            'tax' => $tax,
            'total' => $total,
            'notes' => $notes,
            'format_discount' => $discountFormat,
            'store_id' => $store_id,
            'status' => 'pending',
            'customer_order_date' => $customer_order_date,
            'customer_purchase_order' => $customer_purchase_order,
            'completed_status' => $completed_status,
            'customer_reference_number' => $customer_reference_number,
            'customer_contact_person' => $customer_contact_person,
            'customer_contact_number' => $customer_contact_number,
            'customer_contact_email' => $customer_contact_email,
            'order_discount' => $order_discount,
            
        ];
        
        
        $quote_number = $this->input->post('quote_number');
        if($quote_number)
        {
            $masterdata['quote_number']=$quote_number;
        }
       if($this->input->post('action_type'))
        {            
            $this->db->update('cberp_sales_orders',$masterdata,['salesorder_number'=>$salesorder_number]);
        }
        else{
            
            // $masterdata['converted_status'] = '1';
            $masterdata['created_by'] = $this->session->userdata('id');
            $masterdata['created_date'] = date('Y-m-d H:i:s');
            $masterdata['salesorder_date'] = date('Y-m-d H:i:s');
            $masterdata['salesorder_number'] = $this->salesorder->lastsalesorder();
            $salesorder_number = $masterdata['salesorder_number'];
            $this->db->insert('cberp_sales_orders',$masterdata);

        }

       
        // $this->db->delete('cberp_sales_orders_items', array('salesorder_number' => $salesorder_number));
        
        // file upload section starts 22-01-2025
        if($_FILES['upfile'])
        {
            upload_files($_FILES['upfile'], 'Salesorder',$salesorder_number);
        }
         // file upload section ends 22-01-2025
        //  history_table_log('cberp_sales_orders_log','sales_order_id',$salesorderids,'Update');
         //erp2024 06-01-2025 detailed history log starts
         detailed_log_history($this->module_number,$salesorder_number,'Updated', $_POST['changedFields']);
        //erp2024 06-01-2025 detailed history log ends 
       
    
    
        //sales order items starts
        $productlist = array();
        $prodindex = 0;
        $itc = 0;
        $flag = false;
        $product_name1 = $this->input->post('product_name', true);
        $code = $this->input->post('code', true);
        $product_qty = $this->input->post('product_qty');
        $product_price = $this->input->post('product_price');
        $product_tax = $this->input->post('product_tax');
        $product_discount = $this->input->post('product_discount');
        $product_subtotal = $this->input->post('product_subtotal');
        $ptotal_tax = $this->input->post('taxa');
        $ptotal_disc = $this->input->post('disca');
        // $product_des = $this->input->post('product_description', true);
        $product_hsn = $this->input->post('hsn');
        $product_unit = $this->input->post('unit');
        $discount_type = $this->input->post('discount_type');
        $lowest_price = $this->input->post('lowest_price');
        $maximum_discount_rate = $this->input->post('maxdiscountrate');
        $product_amt = $this->input->post('product_amt');
        $product_tax =0;
        $grandtotal=0;
        $deleted_items = $this->input->post('deleted_item');        
        $deleted_items_array = explode(",", $deleted_items);
        if($deleted_items_array)
        {
            $this->db->where('salesorder_number', $salesorder_number);
            $this->db->where_in('product_code', $deleted_items_array);
            $this->db->delete('cberp_sales_orders_items'); 
        }
       
        foreach ($product_hsn as $key => $value) {
            if(!empty($product_hsn[$key]) && !empty($product_name1[$key]) && $product_qty[$key]>0)
            {
                $total_discount += numberClean(@$ptotal_disc[$key]);
                $total_tax += numberClean($ptotal_tax[$key]);
                
                if($discount_type[$key]=="Amttype"){
                    $discountamount = numberClean($product_amt[$key]);
                }
                else{
                    $discountamount = numberClean($product_discount[$key]);
                }
                if($this->configurations["config_tax"]!="0"){ 
                    $product_tax = numberClean($product_tax[$key]);
                }
                

                 $data = array(
                    'salesorder_number' => $salesorder_number,
                    'product_code' => $product_hsn[$key],
                    'quantity' => numberClean($product_qty[$key]),
                    'price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
                    'tax' => $product_tax,
                    'discount' => $discountamount,
                    'total_amount' => rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc),
                    'total_tax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                    'discount_type' => $discount_type[$key],
                    'lowest_price' => $lowest_price[$key],
                    'total_discount' => rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc),
                    'maximum_discount_rate' => $maximum_discount_rate[$key],
                    'remaining_quantity' => numberClean($product_qty[$key]),
                    'ordered_quantity' => numberClean($product_qty[$key]),
                    'transfered_quantity' => 0,
                    'delivered_quantity' => 0, 
                );

                $code = trim($code[$key]);     
                $isChanged = !empty($changedSet) && isset($changedSet[$code]);
                $isInWhole = !empty($wholeSet) && isset($wholeSet[$code]);   

                if($isChanged && in_array($code, $product_hsn)) {
                    $this->db->update('cberp_sales_orders_items', $data, ['salesorder_number'=>$salesorder_number, 'product_code'=>$code]);
                }
                elseif (!$isInWhole && in_array($code, $product_hsn)) 
                {
                    $this->db->insert('cberp_sales_orders_items', $data);
                }
                $existornot = $this->salesorder->check_product_existornot($salesorder_number,$product_hsn[$key]);
                if($existornot==1)
                {
                    $this->db->update('cberp_sales_orders_items', $data, ['salesorder_number'=>$salesorder_number, 'product_code'=>$product_hsn[$key]]);
                }
                else{
                    $this->db->insert('cberp_sales_orders_items', $data);
                }                
                $flag = true;
                $productlist[$prodindex] = $data;
            }   
            $i++;
            $prodindex++;
            $amt = numberClean($product_qty[$key]);
            $itc += $amt;
            $grandtotal += rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc); 
        }
        if ($prodindex > 0) {
            $grandtotal = $grandtotal - $order_discount;
            // $this->db->insert_batch('cberp_sales_orders_items', $productlist);
            $this->db->set(array('discount' => rev_amountExchange_s(amountFormat_general($total_discount), $currency, $this->aauth->get_user()->loc), 'tax' => rev_amountExchange_s(amountFormat_general($total_tax), $currency, $this->aauth->get_user()->loc), 'subtotal'=>$grandtotal, 'total'=>$grandtotal));
            $this->db->where('salesorder_number', $salesorder_number);
            $this->db->update('cberp_sales_orders');
            $this->load->model('deliverynote_model', 'deliverynote');
            $result = $this->salesorder->insert_delivery_note_from_sales_order($salesorder_number,$this->deliverynote->deliverynote_number(),$this->module_number);           
            // if($result){
            //     echo json_encode(array('status' => '1'));
            // }

        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
                "Please choose product from product list. Go to Item manager section if you have not added the products."));
            $transok = false;
        } 
        //sales order items ends
    
    
        // $this->db->trans_start();
        $flag = false;
        $transok = true;
    
    
        echo json_encode(array('status' => 'Success'));
            $transok = false;
        if ($transok) {
            $this->db->trans_complete();
        } else {
            $this->db->trans_rollback();
        }
    }

   

    public function saleorderdeleteaction()  
    {
      
        $salesorder_id = $this->input->post('salesorder_id');
        $salesorder_number = $this->input->post('so_prefix_number');       
        if ($salesorder_id) {        
            $salesorder_number_new = $salesorder_number."-DELETED";
            $this->db->update('cberp_sales_orders',['salesorder_number'=>$salesorder_number_new,'status'=>'deleted'],['id'=>$salesorder_id]);
            $changedFields = json_encode([
                [
                    'fieldlabel' => "Sales Order(Deleted)",
                    'field_name' => "Sales Order(Deleted)", 
                    'oldValue' => $salesorder_number,
                    'newValue' => $salesorder_number_new
                ]
            ]);            
            detailed_log_history($this->module_number,$salesorder_id,'Sales Order Deleted', $changedFields);
        }
        
        echo json_encode(array(
            'status' => 'Success'
        ));
    }

    //erp2024 sales order creation 27-02-2025
    public function salesorder_new()
    {
       

        $validity = default_validity();
        $data['validity'] = default_validity();
        $data['permissions'] = load_permissions('Sales','Sales','Sales Orders','Create Page');
        $token = intval($this->input->get('token'));
        $data['prefix'] = $this->prifix72['salesorder_prefix'];
        $data['token'] = $token;     
        //echo "<pre>"; print_r($data['permissions']); die(); 
        $data['warehouse'] = warehouse_list();
        $data['related_salesorders']="";
        // if($token==1)
        // {
         
        //     $quote_id = intval($this->input->get('id'));
         
        //     $data['salesorder_num'] = $this->quote->salesorder_number();
        //     $data['id'] =  $data['salesorder_num'];
        //     $head['title'] = "Sales Order";
        //     // $head['title'] = "Sales Order Landing #" . $data['salesorder_num']+1000;
        //     $head['usernm'] = $this->aauth->get_user()->username;
        //     $data['quoteid'] = $quote_id;
        //     $data['terms'] = $this->quote->billingterms();
        //     $data['customer'] = $this->quote->get_customer_by_quoteid($quote_id);
        //     $data['currency'] = $this->quote->currencies();
        //     // $head['usernm'] = $this->aauth->get_user()->username;
        //     $this->load->model('plugins_model', 'plugins');
        //     $data['exchange'] = $this->plugins->universal_api(5);
        //     $this->load->library("Common");
                            
        //     $data['configurations'] = $this->configurations;
        //     $masterdata = $this->quote->get_quote_details($quote_id);
        //     $data['action_type'] = "";
        //     // echo "<pre>";  print_r($masterdata[0]); die();
        //     $sequentialdata = $this->quote->get_sales_seqnumber_tid($quote_id);
        //     $data['newsalesordernumber'] = $sequentialdata['newsalesordernumber'];
        //     $data['masterdata'] = $masterdata[0];
        //     $data['salesseqnumber'] = $sequentialdata['salesseqnumber'];
        //     $data['invoice'] = $this->quote->quote_details_byquoteid($quote_id);

          

        //     $data['taxlist'] = $this->common->taxlist_edit($data['invoice']['taxstatus']);
        //     $data['convertedflg'] = $this->quote->check_quote_converted_stage($quote_id);
        //     $productdata = [];
        //     $salesorders = [];
        //     if(!empty($masterdata)){
        //         foreach ($masterdata as $row) {
        //             $pid = $row['pid'];
        //             $quote_id = $row['quote_id'];
        //             $productdata[$pid] = array(
        //                 'lead_id' => $row['lead_id'],
        //                 'leadqty' => $row['leadqty'],
        //                 'remaining_qty' => $row['remaining_qty'],
        //                 'leaddate' => $row['leaddate'],
        //                 'productid' => $pid,
        //                 'quote_id' => $quote_id,
        //                 'product' => $row['product'],
        //                 'code'     => $row['code'],
        //                 'quoteqty' => $row['quoteqty'],
        //                 'quoterate' => $row['quoterate'],
        //                 'currentrate' => $row['currentrate'],
        //                 'quotedate' => $row['quotedate'],
        //                 'product_price'        => $row['product_price'],
        //                 'product_lowest_price' => $row['product_lowest_price'],
        //                 'product_max_discount' => $row['product_max_discount'],
        //                 'salestid'             => $row['salestid'],
        //                 'salesseqnumber'       => $row['salesseqnumber'],
        //                 'leadnumber'           => $row['leadnumber'],
        //                 'quote_number'         => $row['quote_number'],
        //                 'quotedamount'         => $row['quotedamount'],
        //             );                
                    
        //         }
        //     }
        //     $saleorderdata = $this->quote->get_salesorder_against_quote($quote_id, $pid);
        //     if(!empty($saleorderdata)){
        //         foreach ($saleorderdata as $sdata) {
        //             // if($row['pid'] == $sdata['salesprdid'])
        //             // {
        //                 $prd_id = $sdata['salesprdid'];
        //                 $salesorders[$prd_id][$quote_id][] = array(
        //                     'salesprdid' => $sdata['salesprdid'],
        //                     'salesorderqty' => $sdata['salesorderqty'],
        //                     'salesorderid' => $sdata['salesorderid'],
        //                     'salesorderdate' => $sdata['salesorderdate'],
        //                     'salesordernumber' => $sdata['salesordernumber'],
        //                     'subtotal'         => $sdata['subtotal'],
        //                     'salestotaldiscount'  => $sdata['salestotaldiscount'],
        //                     'salesdiscount'       => $sdata['salesdiscount'],
        //                     'completedstatus'       => $sdata['completedstatus'],
        //                     'convertedstatus'       => $sdata['convertedstatus'],
        //                     'salesdiscounttype'   => ($sdata['salesdiscounttype']=="Amttype")?'Amt':'%'
        //                 );
        //             // }
        //         }
        //     }

           
        //     $data['trackingdata'] = tracking_details('quote_id',intval($this->input->get('id')));
        
        //     $data['salesorders'] = $salesorders;
        //     $data['productdata'] = $productdata;
        //     $this->load->view('fixed/header', $head);
        //     $this->load->view('sales/new-salesorder-from-partial-quote', $data);
        //     $this->load->view('fixed/footer');
        // }
        if($token==2){ 
            //Quote to sales order convertion
            // $data['permissions'] = load_permissions('Sales','Sales','Sales Orders','Create Page');
            //   ini_set('display_errors', 1);
            // ini_set('display_startup_errors', 1);
            // error_reporting(E_ALL);  
            $this->load->model('customers_model', 'customers');
            $data['customergrouplist'] = $this->customers->group_list();
            $tid = $this->input->get('id');
            $salesorder_number = $tid;
            // $soid = intval($this->input->get('soid'));
            
            // $data['quote_id'] = $tid;
            $data['quote_number'] = $this->quote->get_quote_number_from_salesorder($tid);
            $quote_number = $data['quote_number'];
            if($data['quote_number'])
            {
                $data['related_salesorders'] = $this->salesorder->quote_related_salesorders($quote_number,$salesorder_number);
                // print_r($data['related_salesorders']); die();
            }
            
            $data['trackingdata'] = tracking_details('quote_number',$quote_number);
            
            $data['action_type'] = "";
            $this->session->set_userdata('orderid', $tid);
            $data['terms'] = $this->quote->billingterms();
            $data['invoice'] = $this->quote->quote_details_byid($tid);  
            // echo "<pre>"; print_r($data['invoice']); die();
            $data['created_employee'] = employee_details_by_id($data['invoice']['created_by']);
            $data['id'] = $data['invoice']['iid'];
            $data['products'] = $this->quote->salesorder_products($tid);            
            // $data['customer'] = $this->quote->get_customer_by_quoteid($quote_number);            
            $data['customer'] = $this->salesorder->get_customer_by_salesorder_id($salesorder_number);
            $data['assigned_customer']  = get_customer_details_by_id($data['invoice']['customer_id']); 
            $data['action_type'] = "Edit";          
            $data['currency'] = $this->quote->currencies();
            // $head['title'] = "Quote To Sales Order #" . $data['invoice']['tid'];
            $head['title'] = "Sales Order";
            $head['usernm'] = $this->aauth->get_user()->username;
            $this->load->model('plugins_model', 'plugins');
            $data['exchange'] = $this->plugins->universal_api(5);
            $this->load->library("Common");
            $data['taxlist'] = $this->common->taxlist_edit($data['invoice']['taxstatus']);                
            $data['configurations'] = $this->configurations;
            if($this->module_number)
            {
                $data['approved_levels'] = function_approved_levels($this->module_number,$salesorder_number);
                $data['approval_level_users'] =  linked_user_module_approvals_by_module_number($this->sales_module_group_number);   
                $data['my_approval_permissions'] =  linked_user_module_approvals_by_module_number($this->sales_module_group_number,$this->session->userdata('id'));
                $data['module_number'] = $this->module_number;
            }  
        
            $data['detailed_log']= get_detailed_logs($salesorder_number,$this->module_number);
                $products = $data['detailed_log'];
                $groupedBySequence = []; 
                foreach ($products as $product) {
                    $sequence = $product['seqence_number'];
                    $groupedBySequence[$sequence][] = $product;
                }
            $data['groupedDatas'] = $groupedBySequence;
            $this->load->view('fixed/header', $head);
            $this->load->view('sales/quotetosalesorder', $data);
            $this->load->view('fixed/footer');
        }

        else if($token==3)
        {
            // ini_set('display_errors', 1);
            // ini_set('display_startup_errors', 1);
            // error_reporting(E_ALL);
          
            //create new sales order salesorder_number
            // $data['permissions'] = load_permissions('Sales','Sales','Sales Orders','Create Page');
            $this->load->model('plugins_model', 'plugins');
            $data['emp'] = $this->plugins->universal_api(69);
            if ($data['emp']['key1']) {
                $this->load->model('employee_model', 'employee');
                $data['employee'] = $this->employee->list_employee();
            }
            $this->load->library("Common");
            $salesorder_number = $this->input->get('id');
            // $data['trackingdata'] = tracking_details('sales_id',intval($this->input->get('id')));
            //erp2024 14-03-2025 detailed history log starts
            $page = $this->module_number;
            // echo "<pre>"; print_r($data['groupedSalesorders']); die();
            //erp2024 14-03-2025 detailed history log starts
            if(($salesorder_number))
            {
               

                $data['detailed_log']= get_detailed_logs($salesorder_number,$page);
                $products = $data['detailed_log'];
                $groupedBySequence = []; 
                foreach ($products as $product) {
                    $sequence = $product['seqence_number'];
                    $groupedBySequence[$sequence][] = $product;
                }
                
                $data['groupedDatas'] = $groupedBySequence;
                $data['salesorder_id'] = $salesorder_number;
                $data['invoice'] = $this->salesorder->salesorder_details($salesorder_number);  
                $data['created_employee'] = employee_details_by_id($data['invoice']['created_by']);
                $data['products'] = $this->salesorder->salesorder_products($salesorder_number);
                $data['assigned_customer']  = get_customer_details_by_id($data['invoice']['customer_id']);  
                $data['colorcode'] = get_color_code($data['invoice']['due_date']);                         
                $data['id'] =  $salesorder_number;
                $data['customer'] = $this->salesorder->get_customer_by_salesorder_id($salesorder_number);
                $data['salesorder_num'] = $salesorder_number;
                $data['action_type'] = "Edit"; 
                if($this->module_number)
                {
                    $data['approved_levels'] = function_approved_levels($this->module_number,$salesorder_number);
                    $data['approval_level_users'] =  linked_user_module_approvals_by_module_number($this->sales_module_group_number);   
                    $data['my_approval_permissions'] =  linked_user_module_approvals_by_module_number($this->sales_module_group_number,$this->session->userdata('id'));
                    $data['module_number'] = $this->module_number;
                }               
                $data['images'] = get_uploaded_images('Salesorder',$salesorder_number);
                if($data['invoice']['salesorder_date'])
                {
                    $data['trackingdata'] = tracking_details('salesorder_number',$salesorder_number);
                }
                else{
                    //converting from quote 
                    $data['trackingdata'] = tracking_details('quote_number',$data['invoice']['quote_number']);
                }
                
            }
            else{
                $data['invoice'] = [];
                $data['products'] = [];
                $data['salesorder_number'] =  $this->salesorder->lastsalesorder();
                // $data['salesorder_num'] = $this->quote->salesorder_number();
                $data['action_type'] = "";
            }
            // echo "<pre>"; print_r($data['products']); die();
            $data['currency'] = $this->quote->currencies();
            // $head['title'] = "Quote To Sales Order #" . $data['invoice']['tid'];
            $head['title'] = "Sales Order";
            $head['usernm'] = $this->aauth->get_user()->username;
            $this->load->model('plugins_model', 'plugins');
            $data['exchange'] = $this->plugins->universal_api(5);
            $this->load->library("Common");
            $data['taxlist'] = $this->common->taxlist_edit($data['invoice']['tax_status']);                
            $data['configurations'] = $this->configurations;
            $this->load->view('fixed/header', $head);
            $this->load->view('sales/quotetosalesorder', $data);
            $this->load->view('fixed/footer');
            
        }

        // else {
        //     $this->load->model('customers_model', 'customers');
        //     $data['customergrouplist'] = $this->customers->group_list();
        //     $tid = intval($this->input->get('id'));
        //     $data['id'] = $tid;
        //     $data['quote_id'] = $this->quote->get_quote_number_from_salesorder($tid);
        //     $quote_id = $data['quote_id'];
            
        //     $data['trackingdata'] = tracking_details('quote_id',$quote_id);

        //     $this->session->set_userdata('orderid', $tid);
        //     $data['terms'] = $this->quote->billingterms();
        //     $data['invoice'] = $this->quote->quote_details_by_saleid_quoteid($quote_id,$tid);
        //     $data['products'] = $this->quote->salesorder_products($tid);
        //     // echo "<pre>"; print_r($data['products']); die();
        //     $data['customer'] = $this->quote->get_customer_by_quoteid($quote_id);
        //     $data['salesorder_num'] = $this->quote->salesorder_number();
        //     $data['currency'] = $this->quote->currencies();
        //     // $head['title'] = "Quote To Sales Order #" . $data['invoice']['tid'];
        //     $head['title'] = "Sales Order Landing";
        //     $head['usernm'] = $this->aauth->get_user()->username;
        //     $this->load->model('plugins_model', 'plugins');
        //     $data['exchange'] = $this->plugins->universal_api(5);
        //     $this->load->library("Common");
        //     $data['taxlist'] = $this->common->taxlist_edit($data['invoice']['taxstatus']);                
        //     $data['configurations'] = $this->configurations;
        //     $this->load->view('fixed/header', $head);
        //     $this->load->view('sales/quotetosalesorder-draft', $data);
        //     $this->load->view('fixed/footer');
        // }
       
        
    }

    public function action()
    {
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);  cberp_transaction_tracking 
        $tokenid = $this->input->post('tokenid');
        $salesorder_prefix = $this->prifix72['salesorder_prefix'];
        if($tokenid==3)
        {
            $this->action_direct();
            die();
        }
        $discountchecked = $this->input->post('discountchecked');

        if (isset($discountchecked)) {
            $discountchecked = $discountchecked;
        } else {
            $discountchecked = 0;
        }
        $customer_id = $this->input->post('customer_id');
        $invocieno_n = $this->input->post('invocieno');
        $invocieno = $this->input->post('iid');
        $quote_id = $this->input->post('quote_id');
        $completed_status =  $this->input->post('completed_status');
        $salesorderids = $invocieno_n - 1000;
        
     
        $invoicedate = $this->input->post('invoicedate');
        $invocieduedate = $this->input->post('invocieduedate');
        $notes = $this->input->post('notes', true);
        $tax = $this->input->post('tax_handle');
        $total_tax = 0;
        $total_discount = 0;
        $discountFormat = $this->input->post('discountFormat');
        // erp2024 remove pterms 06-06-2024
        // $pterms = $this->input->post('pterms');
        $propos = $this->input->post('propos');
        $currency = $this->input->post('mcurrency');
        $ship_taxtype = $this->input->post('ship_taxtype');
        $subtotal = rev_amountExchange_s($this->input->post('subtotal'), $currency, $this->aauth->get_user()->loc);
        $shipping = rev_amountExchange_s($this->input->post('shipping'), $currency, $this->aauth->get_user()->loc);
        $shipping_tax = rev_amountExchange_s($this->input->post('ship_tax'), $currency, $this->aauth->get_user()->loc);
        if ($ship_taxtype == 'incl') $shipping = $shipping - $shipping_tax;
        $refer = $this->input->post('refer', true);
        $total = rev_amountExchange_s($this->input->post('total'), $currency, $this->aauth->get_user()->loc);
        // 08-08-2024 erp2024 newlyadded fields
        $customer_purchase_order = $this->input->post('customer_purchase_order', true);
        $customer_order_date = $this->input->post('customer_order_date');
        $order_discount = rev_amountExchange_s($this->input->post('order_discount'), $currency, $this->aauth->get_user()->loc);
        
        $ordered_quantity = $this->input->post('ordered_quantity');
        $transfered_quantity = $this->input->post('transfered_quantity');
        $delivered_quantity = $this->input->post('delivered_quantity');
        $remaining_quantity = $this->input->post('remaining_quantity');
        $store_id = $this->input->post('store_id');

        $i = 0;
        if ($discountFormat == '0') {
            $discount_status = 0;
        } else {
            $discount_status = 1;
        }

        if ($customer_id == 0) {
            echo json_encode(array('status' => 'Error', 'message' =>
                $this->lang->line('Please add a new client')));
            exit;
        }

        // $this->db->trans_start();
        $flag = false;
        $transok = true;


        //Product Data
        $pid = $this->input->post('pid');
        $productlist = array();

        $prodindex = 0;

        $this->db->select('status,tid,pid');
        $this->db->from('cberp_sales_orders_items');
        $this->db->where('tid', $salesorderids);
        $oldstatus = $this->db->get();

      
        $statusList = $oldstatus->result_array();        
        $this->db->delete('cberp_sales_orders_items', array('tid' => $salesorderids));
        $product_id = $this->input->post('pid');

        // print_r($product_id);die();
        $product_name1 = $this->input->post('product_name', true);
        $code = $this->input->post('code', true);
        $product_qty = $this->input->post('product_qty');
        $product_price = $this->input->post('product_price');
        $product_tax = $this->input->post('product_tax');
        $product_subtotal = $this->input->post('product_subtotal');
        $ptotal_tax = $this->input->post('taxa');
        $ptotal_disc = $this->input->post('disca');
        
        $product_des = $this->input->post('product_description', true);
        $product_hsn = $this->input->post('hsn');
        $product_unit = $this->input->post('unit');

        
        $discount_type = $this->input->post('discount_type');
        $product_amt = $this->input->post('product_amt');
        $product_discount = $this->input->post('product_discount');
        $lowest_price = $this->input->post('lowest_price');
        $maximum_discount_rate = $this->input->post('maximum_discount_rate');
        
        
        // print_r($product_discount); die();
        foreach ($pid as $key => $value) {
            if(numberClean($product_qty[$key]) > 0)
            {
                $status ="";
                $total_discount += numberClean(@$ptotal_disc[$key]);
                $total_tax += numberClean($ptotal_tax[$key]);
                foreach ($statusList as $item) {            
                    if($item['pid']==$product_id[$key]){
                        $status = $item['status'];
                    }            
                }
                $discount_type_val="";                
                
                // if($discountchecked==1){
                    if($discount_type[$key]=="Amttype"){
                        $discountamount = numberClean($product_amt[$key]);
                    }
                    else if($discount_type[$key]=="Perctype"){
                        $discountamount = numberClean($product_discount[$key]);
                    }
                    else{
        
                    }
                    $discount_type_val = $discount_type[$key];
                    $total_discount = rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc);
                    $subtotal = rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc);
                // }
                // else{
                    // $discountamount=0;
                    // $discount_type_val = "";
                    // $total_discount = 0;
                    // $prdprice = rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc);
                    // $subtotal = ($prdprice * numberClean($product_qty[$key]));
                // }
                $data = array(
                    'tid' => $salesorderids,
                    // 'tid' => $invocieno,
                    'pid' => $product_id[$key],
                    'product' => $product_name1[$key],
                    'product_code' => $code[$key],
                    'quantity' => numberClean($product_qty[$key]),
                    'price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
                    'tax' => numberClean($product_tax[$key]),
                    'discount' => $discountamount,
                    'subtotal' => $subtotal,
                    'total_tax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                    'total_discount' => $total_discount,
                    'product_des' => $product_des[$key],
                    'unit' => $product_unit[$key],
                    'status' => $status,
                    'discount_type'  => $discount_type_val,
                    'ordered_quantity'    => $ordered_quantity[$key],
                    'transfered_quantity' => (numberClean($product_qty[$key]) + numberClean($transfered_quantity[$key])),
                    'delivered_quantity'  => numberClean($delivered_quantity[$key]) + numberClean($product_qty[$key]),
                    'remaining_quantity'  => numberClean($remaining_quantity[$key]) - numberClean($product_qty[$key]),
                    'lowest_price' => $lowest_price[$key],
                    'maximum_discount_rate' => $maximum_discount_rate[$key],
                );
                // erp2024 quote items update 06-08-2024
                $quoteitems = $this->quote->quote_details_for_multiplesalesorders($quote_id,$product_id[$key]);
                $remainigqty = $quoteitems['remaining_quantity'];
                $deliveredqty = $quoteitems['delivered_quantity'];
                $transferedqty = $quoteitems['transfered_quantity'];
                $orderedqty = $quoteitems['ordered_quantity'];
                $actualqty = $quoteitems['qty'];
                $delveredqty = numberClean($quoteitems['deliveredqty']) + numberClean($product_qty[$key]);
                $transferqty = (numberClean($product_qty[$key]) + numberClean($quoteitems['transfered_quantity']));  
                $prdstatus=0;        
                if($orderedqty <= $transferqty){
                    $prdstatus = 1;
                }
                else{
                    $prdstatus = 0;  
                }
                $remainigqty_new = numberClean($quoteitems['remaining_quantity']) - numberClean($product_qty[$key]);
                $remainigqty_new = ($remainigqty_new >0) ? $remainigqty_new : 0;
                $quotedata = array(
                    'remaining_quantity' => $remainigqty_new,
                    'delivered_quantity' => $delveredqty,
                    'transfered_quantity' =>  $transferqty,
                    // 'quantity' =>  (numberClean($actualqty) - numberClean($product_qty[$key])),
                    // 'subtotal' => $quoteitems['subtotal'] - rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc),
                    // 'total_tax' => $quoteitems['total_tax'] - rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                    // 'total_discount' => $quoteitems['total_discount'] - rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc),
                    'prdstatus' => $prdstatus
                );
               
                $this->quote->quote_items($quote_id,$product_id[$key],$quotedata);
                
                $flag = true;
                $productlist[$prodindex] = $data;
                $i += numberClean($product_qty[$key]);;
                $prodindex++;

            }
        }
        
        $bill_date = datefordatabase($invoicedate);
        $bill_due_date = datefordatabase($invocieduedate);

        $total_discount = rev_amountExchange_s(amountFormat_general($total_discount), $currency, $this->aauth->get_user()->loc);
        $total_tax = rev_amountExchange_s(amountFormat_general($total_tax), $currency, $this->aauth->get_user()->loc);

       
        $data1 = array('invoicedate' => $bill_date, 'due_date' => $bill_due_date, 'subtotal' => $subtotal, 'shipping' => $shipping, 'ship_tax' => $shipping_tax, 'ship_tax_type' => $ship_taxtype, 'discount' => $total_discount, 'tax' => $total_tax, 'total' => $total, 'notes' => $notes, 'customer_id' => $customer_id, 'items' => $i, 'tax_status' => $tax, 'discount_status' => $discount_status, 'format_discount' => $discountFormat, 'refer' => $refer, 'term' => $pterms, 'customer_message' => $propos, 'multi' => $currency, 'customer_order_date'=>$customer_order_date, 'customer_purchase_order'=>$customer_purchase_order,'completed_status'=>$completed_status,'customer_reference_number' => $this->input->post('customer_reference_number'),'customer_contact_person' => $this->input->post('customer_contact_person'),'customer_contact_number' => $this->input->post('customer_contact_number'),'customer_contact_email' => $this->input->post('customer_contact_email'),'order_discount'=>$order_discount,'store_id'=>$store_id);
        $data1['status'] = 'pending';
        $data1['converted_status'] = '0';
        $this->db->set($data1);
        $this->db->where('id', $salesorderids);
        // $this->db->where('id', $invocieno); 
      
        if ($flag) {
            
            if ($this->db->update('cberp_sales_orders', $data1)) {
                //insert to tracking table  salesorder_number
                insertion_to_tracking_table('sales_number', $invocieno_n, 'quote_id', $quote_id);

                history_table_log('cberp_sales_orders_log','sales_order_id',$invocieno,'Created');     
                 //erp2024 06-01-2025 detailed history log starts
                detailed_log_history($this->module_number,$invocieno,'Created', $_POST['changedFields']);
                detailed_log_history('Quote',$quote_id,'Converted to sales order', $_POST['changedFields']);
                //erp2024 06-01-2025 detailed history log ends 
		        // file upload section starts 22-01-2025
                if($_FILES['upfile'])
                {
                    upload_files($_FILES['upfile'], 'Salesorder',$invocieno);
                }
                    // file upload section ends 22-01-2025
                $this->db->insert_batch('cberp_sales_orders_items', $productlist);
                //update quote status
                $salestid = $invocieno_n;
                // $salestid = $invocieno+1000;

                
                $this->quote->update_quote_status($quote_id, $salestid, $salesorderids);

                echo json_encode(array(
                    'status' => 'Success',
                    'message' => $this->lang->line('Order has been updated') . 
                                " <a href='salesorder_new?id=$quote_id&token=1' class='btn btn-info btn-sm'>" . 
                                "<span class='icon-file-text2' aria-hidden='true'></span> View </a>"
                ));
                
            } else {
                echo json_encode(array('status' => 'Error', 'message' =>
                    $this->lang->line('ERROR')));
                $transok = false;
            }


        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
                "Please add atleast one product in invoice $salesorderids"));
            $transok = false;
        }


        if ($transok) {
            $this->db->trans_complete();
        } else {
            $this->db->trans_rollback();
        }
    }

    public function action_direct()
    {
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);  delete
        
        $masterdata = [];
        $discountchecked = $this->input->post('discountchecked');
    
        if (isset($discountchecked)) {
            $discountchecked = $discountchecked;
        } else {
            $discountchecked = 0;
        }
        $customer_id = $this->input->post('customer_id');
        
        $salesorder_number = $this->input->post('so_prefix_number');
        $invocieno = $this->input->post('iid');
        $completed_status =  $this->input->post('completed_status');
        $salesorder_id = $this->input->post('salesorder_id');
       
    
        $invoicedate = $this->input->post('invoicedate');
        $invocieduedate = date('Y-m-d', strtotime($this->input->post('invocieduedate')));
        $notes = $this->input->post('notes', true);
        $tax = $this->input->post('tax_handle');
        $total_tax = 0;
        $total_discount = 0;
        $discountFormat = $this->input->post('discountFormat');
        // erp2024 remove pterms 06-06-2024 status
        // $pterms = $this->input->post('pterms');
        $customer_reference_number = $this->input->post('customer_reference_number');
        $customer_contact_person = $this->input->post('customer_contact_person');
        $customer_contact_number = $this->input->post('customer_contact_number');
        $customer_contact_email = $this->input->post('customer_contact_email');
        $proposal = $this->input->post('propos');
        $store_id = $this->input->post('store_id');
        $currency = $this->input->post('mcurrency');
        $ship_taxtype = $this->input->post('ship_taxtype');
        $subtotal = rev_amountExchange_s($this->input->post('subtotal'), $currency, $this->aauth->get_user()->loc);
        $shipping = rev_amountExchange_s($this->input->post('shipping'), $currency, $this->aauth->get_user()->loc);
        $shipping_tax = rev_amountExchange_s($this->input->post('ship_tax'), $currency, $this->aauth->get_user()->loc);
        if ($ship_taxtype == 'incl') $shipping = $shipping - $shipping_tax;
        $refer = $this->input->post('refer', true);
        
        $total = rev_amountExchange_s($this->input->post('total'), $currency, $this->aauth->get_user()->loc);
        // 08-08-2024 erp2024 newlyadded fields
        $customer_purchase_order = $this->input->post('customer_purchase_order', true);
        $customer_order_date = date('Y-m-d', strtotime($this->input->post('customer_order_date')));    
        $order_discount = rev_amountExchange_s($this->input->post('order_discount'), $currency, $this->aauth->get_user()->loc);
        if ($customer_id == 0) {
            echo json_encode(array('status' => 'Error', 'message' =>
                $this->lang->line('Please add a new client')));
            exit;
        }
        //propos
        $masterdata = [
            'salesorder_number' => $salesorder_number,
            'due_date' => date('Y-m-d', strtotime($invocieduedate)),
            'customer_id' => $customer_id,
            'employee_id' => $this->session->userdata('id'),
            'reference' => $refer,
            'customer_message' => $proposal,
            'shipping' => $shipping,
            'shipping_tax' => $ship_tax,
            'shipping_tax_type' => $ship_tax_type,
            'tax' => $tax,
            'total' => $total,
            'notes' => $notes,
            'format_discount' => $discountFormat,
            // 'payment_term' => $term, // coming from payment section
            // 'pmethod' => $pmethod,
            'store_id' => $store_id,
            'status' => 'pending',
            // 'pamnt' => $pamnt,
            // 'items' => $items,
            // 'term' => $term,
            'customer_order_date' => $customer_order_date,
            'customer_purchase_order' => $customer_purchase_order,
            'completed_status' => $completed_status,
            'customer_reference_number' => $customer_reference_number,
            'customer_contact_person' => $customer_contact_person,
            'customer_contact_number' => $customer_contact_number,
            'customer_contact_email' => $customer_contact_email,
            'order_discount' => $order_discount,
            
        ];
        
        // $record_exists = record_exists_or_not('cberp_sales_orders','salesorder_number',$salesorder_number);
        if($this->input->post('action_type'))
        {
            if(empty($this->input->post('salesorder_date')))
            {
                 $masterdata['salesorder_date'] = date('Y-m-d H:i:s');
                 $masterdata['created_by'] = $this->session->userdata('id');
                 $masterdata['created_date'] = date('Y-m-d H:i:s');
                 insertion_to_tracking_table('salesorder_number',$salesorder_number,'quote_number', $this->input->post('quote_number'));
            }
             $masterdata['converted_status'] = '0';
             $masterdata['updated_by'] = $this->session->userdata('id');
             $masterdata['updated_date'] = date('Y-m-d H:i:s');
             $this->db->update('cberp_sales_orders',$masterdata,['salesorder_number'=>$salesorder_number]);
             detailed_log_history($this->module_number,$salesorder_number,'Updated', $_POST['changedFields']);  
             $changedProducts = [];
             $wholeProducts = [];            
            if (!empty($this->input->post('changedProducts'))) {
                $changedProducts = json_decode($this->input->post('changedProducts'), true);
            }            
            if (!empty($this->input->post('wholeProducts'))) {
                $wholeProducts = json_decode($this->input->post('wholeProducts'), true);
            }            
            $changedSet = !empty($changedProducts) ? array_flip($changedProducts) : [];
            $wholeSet = !empty($wholeProducts) ? array_flip($wholeProducts) : [];

        }
        else{
            
             $masterdata['converted_status'] = '0';
             $masterdata['created_by'] = $this->session->userdata('id');
             $masterdata['created_date'] = date('Y-m-d H:i:s');
            $masterdata['salesorder_date'] = date('Y-m-d H:i:s');
            $masterdata['salesorder_number'] = $this->salesorder->lastsalesorder();
            $salesorder_number = $masterdata['salesorder_number'];
            $this->db->insert('cberp_sales_orders',$masterdata);
            detailed_log_history($this->module_number,$salesorder_number,'Created', "");
            $this->db->insert('cberp_transaction_tracking',['salesorder_number'=>$salesorder_number]);
        }
      
        // file upload section starts 22-01-2025
        if($_FILES['upfile'])
        {
            upload_files($_FILES['upfile'], 'Salesorder',$salesorder_number);
        }
      
        //sales order items starts
        $productlist = array();
        $prodindex = 0;
        $itc = 0;
        $flag = false;
        $product_id = $this->input->post('pid');
        $product_name1 = $this->input->post('product_name', true);
        $code = $this->input->post('code', true);
        $product_qty = $this->input->post('product_qty');
        $product_price = $this->input->post('product_price');
        $product_tax = $this->input->post('product_tax');
        $product_discount = $this->input->post('product_discount');
        $product_subtotal = $this->input->post('product_subtotal');
        $ptotal_tax = $this->input->post('taxa');
        $ptotal_disc = $this->input->post('disca');
        // $product_des = $this->input->post('product_description', true);
        $product_hsn = $this->input->post('hsn');
        $product_unit = $this->input->post('unit');
        $discount_type = $this->input->post('discount_type');
        $lowest_price = $this->input->post('lowest_price');
        $maximum_discount_rate = $this->input->post('maxdiscountrate');
        $product_amt = $this->input->post('product_amt');
        $product_tax =0;
        $grandtotal=0;

        $deleted_items = $this->input->post('deleted_item');
        $deleted_items_array = explode(",", $deleted_items);
        if($deleted_items_array)
        {
            $this->db->where('salesorder_number', $salesorder_number);
            $this->db->where_in('product_code', $deleted_items_array);
            $this->db->delete('cberp_sales_orders_items'); 
        }
       
        foreach ($code as $key => $value) {
            if(!empty($code[$key]) && !empty($product_name1[$key]) && $product_qty[$key]>0)
            {
                $total_discount += numberClean(@$ptotal_disc[$key]);
                $total_tax += numberClean($ptotal_tax[$key]);
                
                if($discount_type[$key]=="Amttype"){
                    $discountamount = numberClean($product_amt[$key]);
                }
                else{
                    $discountamount = numberClean($product_discount[$key]);
                }
                if($this->configurations["config_tax"]!="0")
                { 
                    $product_tax = numberClean($product_tax[$key]);
                }
                
                $data = array(
                    'salesorder_number' => $salesorder_number,
                    'product_code' => $product_hsn[$key],
                    'quantity' => numberClean($product_qty[$key]),
                    'price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
                    'tax' => $product_tax,
                    'discount' => $discountamount,
                    'total_amount' => rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc),
                    'total_tax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                    'discount_type' => $discount_type[$key],
                    'lowest_price' => $lowest_price[$key],
                    'total_discount' => rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc),
                    'maximum_discount_rate' => $maximum_discount_rate[$key],
                    'remaining_quantity' => numberClean($product_qty[$key]),
                    'ordered_quantity' => numberClean($product_qty[$key]),
                    'transfered_quantity' => 0,
                    'delivered_quantity' => 0, 
                );
                $code = trim($code[$key]);     
                $isChanged = !empty($changedSet) && isset($changedSet[$code]);
                $isInWhole = !empty($wholeSet) && isset($wholeSet[$code]);   

                if($isChanged && in_array($code, $product_hsn)) {
                    $this->db->update('cberp_sales_orders_items', $data, ['salesorder_number'=>$salesorder_number, 'product_code'=>$code]);
                }
                elseif (!$isInWhole && in_array($code, $product_hsn)) 
                {
                    $this->db->insert('cberp_sales_orders_items', $data);
                }
                $existornot = $this->salesorder->check_product_existornot($salesorder_number,$product_hsn[$key]);
                if($existornot==1)
                {
                    $this->db->update('cberp_sales_orders_items', $data, ['salesorder_number'=>$salesorder_number, 'product_code'=>$product_hsn[$key]]);
                }
                else{
                    $this->db->insert('cberp_sales_orders_items', $data);
                }
                $flag = true;
                $productlist[$prodindex] = $data;
            }   
            $i++;
            $prodindex++;
            $amt = numberClean($product_qty[$key]);
            $itc += $amt;
            $grandtotal += rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc); 
        }
        if ($prodindex > 0) {
            $grandtotal = $grandtotal - $order_discount;
            // $this->db->insert_batch('cberp_sales_orders_items', $productlist);
            $this->db->set(array('discount' => rev_amountExchange_s(amountFormat_general($total_discount), $currency, $this->aauth->get_user()->loc), 'tax' => rev_amountExchange_s(amountFormat_general($total_tax), $currency, $this->aauth->get_user()->loc), 'total'=>$grandtotal));
            $this->db->where('salesorder_number', $salesorder_number);
            $this->db->update('cberp_sales_orders');
        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
                "Please choose product from product list. Go to Item manager section if you have not added the products."));
            $transok = false;
        } 
        //sales order items ends
    
    
        // $this->db->trans_start();
        $flag = false;
        $transok = true;
    
    
        echo json_encode(array('status' => 'Success'));
            $transok = false;
        if ($transok) {
            $this->db->trans_complete();
        } else {
            $this->db->trans_rollback();
        }
    }

    public function saleorderdraftaction()
    {
        
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);  

        $discountchecked = $this->input->post('discountchecked');

        if (isset($discountchecked)) {
            $discountchecked = $discountchecked;
        } else {
            $discountchecked = 0;
        } 
        $customer_id = $this->input->post('customer_id');
        $so_prefix_number = $this->input->post('so_prefix_number');
        $salesorder_number = $this->input->post('so_prefix_number');
        $quote_id = ($this->input->post('quote_id')) ? $this->input->post('quote_id') : "";
        $invocieno_n = $this->input->post('invocieno');
        $store_id = $this->input->post('store_id');
        // $quote_id = $this->input->post('quote_id');
        $completed_status =  $this->input->post('completed_status');
        //insert to tracking table
        // $this->db->where('quote_id', $quote_id);
        // $this->db->update('cberp_transaction_tracking',['sales_id'=>$invocieno,'sales_number'=>$invocieno_n]);
        
        $invoicedate = $this->input->post('invoicedate');
        $invocieduedate = $this->input->post('invocieduedate');
        $notes = $this->input->post('notes', true);
        $tax = $this->input->post('tax_handle');
        $total_tax = 0;
        $total_discount = 0;
        $discountFormat = $this->input->post('discountFormat');
        // erp2024 remove pterms 06-06-2024
        // $pterms = $this->input->post('pterms'); propos
        $pterms ="";
        $propos = $this->input->post('propos');
        $currency = $this->input->post('mcurrency');
        $ship_taxtype = $this->input->post('ship_taxtype');
        $subtotal = ($this->input->post('subtotal')) ? rev_amountExchange_s($this->input->post('subtotal'), $currency, $this->aauth->get_user()->loc) : 0;
        $shipping = ($this->input->post('shipping')) ? rev_amountExchange_s($this->input->post('shipping'), $currency, $this->aauth->get_user()->loc) : 0;
        $shipping_tax = ($this->input->post('ship_tax')) ? rev_amountExchange_s($this->input->post('ship_tax'), $currency, $this->aauth->get_user()->loc) : 0;
        if ($ship_taxtype == 'incl') $shipping = $shipping - $shipping_tax;
        $refer = $this->input->post('refer', true);
        $total = ($this->input->post('total')) ? rev_amountExchange_s($this->input->post('total'), $currency, $this->aauth->get_user()->loc) : 0;
        // 08-08-2024 erp2024 newlyadded fields
        $customer_purchase_order = $this->input->post('customer_purchase_order', true);
        $customer_order_date = $this->input->post('customer_order_date');

        
        $ordered_quantity = $this->input->post('ordered_quantity');
        $transfered_quantity = $this->input->post('transfered_quantity');
        $delivered_quantity = $this->input->post('delivered_quantity');
        $remaining_quantity = $this->input->post('remaining_quantity');
        $order_discount = $this->input->post('order_discount');
        $action_type = $this->input->post('action_type');
        $maximum_discount_rate = $this->input->post('maxdiscountrate');
        $i = 0;
        if ($discountFormat == '0') {
            $discount_status = 0;
        } else {
            $discount_status = 1;
        }

        if ($customer_id == 0) {
            echo json_encode(array('status' => 'Error', 'message' =>
                $this->lang->line('Please add a new client')));
            exit;


        }
      
        

        // $this->db->trans_start(); converted_status
        $flag = false;
        $transok = true;


        //Product Data
        $pid = $this->input->post('pid');
        $productlist = array();

        $prodindex = 0;

        //new record
        if(empty($action_type))
        {
            $salesorder_number =  $this->salesorder->lastsalesorder();
            $data2 = array('salesorder_number'=>$salesorder_number,'customer_reference_number' => $this->input->post('customer_reference_number'),'customer_contact_person' => $this->input->post('customer_contact_person'),'customer_contact_number' => $this->input->post('customer_contact_number'),'customer_contact_email' => $this->input->post('customer_contact_email'),'quote_number'=>$quote_id,'created_by'=>$this->session->userdata('id'),'created_date'=>date('Y-m-d H:i:s'),'salesorder_date'=>date('Y-m-d H:i:s'));
            $this->db->insert('cberp_sales_orders', $data2); 
        }
        else
        {
            
            $changedProducts = [];
            $wholeProducts = [];            
            if (!empty($this->input->post('changedProducts'))) {
                $changedProducts = json_decode($this->input->post('changedProducts'), true);
            }            
            if (!empty($this->input->post('wholeProducts'))) {
                $wholeProducts = json_decode($this->input->post('wholeProducts'), true);
            }            
            $changedSet = !empty($changedProducts) ? array_flip($changedProducts) : [];
            $wholeSet = !empty($wholeProducts) ? array_flip($wholeProducts) : [];
        }


      
        // $this->db->delete('cberp_sales_orders_items', array('salesorder_number' => $salesorder_number));
        $product_id = $this->input->post('pid');

        // print_r($product_id);die();
        $product_name1 = $this->input->post('product_name', true);
        $product_qty = $this->input->post('product_qty');
        $product_price = $this->input->post('product_price');
        $product_tax = $this->input->post('product_tax');
        $product_subtotal = $this->input->post('product_subtotal');
        $ptotal_tax = $this->input->post('taxa');
        $ptotal_disc = $this->input->post('disca');
        
        $product_des = $this->input->post('product_description', true);
        $product_hsn = $this->input->post('hsn');
        $product_unit = $this->input->post('unit');

        
        $discount_type = $this->input->post('discount_type');
        $product_amt = $this->input->post('product_amt');
        $product_discount = $this->input->post('product_discount');
        $lowest_price = $this->input->post('lowest_price');
        $maxdiscountrate = $this->input->post('maxdiscountrate');

        $deleted_items = $this->input->post('deleted_item');
        $deleted_items_array = explode(",", $deleted_items);
        if($deleted_items_array)
        {
            $this->db->where('salesorder_number', $salesorder_number);
            $this->db->where_in('product_code', $deleted_items_array);
            $this->db->delete('cberp_sales_orders_items'); 
        }
       
        // print_r($product_discount); die(); 
        if($product_hsn)
        {
            foreach ($product_hsn as $key => $value) {
                // $status ="";
                $total_discount += numberClean(@$ptotal_disc[$key]);
                $total_tax += numberClean($ptotal_tax[$key]);
                // foreach ($statusList as $item) {            
                //     if($item['pid']==$product_id[$key]){
                //         $status = $item['status'];
                //     }            
                // }
                $discount_type_val="";
                
                
                // if($discountchecked==1){
                    if($discount_type[$key]=="Amttype"){
                        $discountamount = numberClean($product_amt[$key]);
                    }
                    else if($discount_type[$key]=="Perctype"){
                        $discountamount = numberClean($product_discount[$key]);
                    }
                    else{
        
                    }
                    $discount_type_val = $discount_type[$key];
                    $total_discount = ($ptotal_disc[$key]) ? rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc):0;
                    $subtotal = ($product_subtotal[$key]) ? rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc):0;
                // }
                // else{
                    // $discountamount=0;
                    // $discount_type_val = "";
                    // $total_discount = 0;
                    // $prdprice = rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc);
                    // $subtotal = ($prdprice * numberClean($product_qty[$key]));
                // }
                $data = array(
                    'salesorder_number' => $salesorder_number,
                    'product_code' => $product_hsn[$key],
                    'quantity' => numberClean($product_qty[$key]),
                    'price' => ($product_price[$key]) ? rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc): 0,
                    'tax' => 0,
                    // 'tax' => ($product_tax[$key]) ? numberClean($product_tax[$key]):0,
                    'discount' => $discountamount,
                    'total_amount' => $subtotal,
                    'total_tax' => ($ptotal_tax[$key]) ? rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc): 0,
                    'total_discount' => $total_discount,
                    'lowest_price' => $lowest_price[$key],
                    'maximum_discount_rate' => $maxdiscountrate[$key],
                    'status' => $status,                
                    'discount_type'  => $discount_type_val,
                
                );
                if ($quote_id) {
                    $data['ordered_quantity'] = ($ordered_quantity[$key]) ? ($ordered_quantity[$key]) : 0;
                    $data['transfered_quantity'] = ($transfered_quantity[$key]) ? ($transfered_quantity[$key]) : 0;
                    $data['delivered_quantity'] = ($delivered_quantity[$key]) ? ($delivered_quantity[$key]) : 0;
                    $data['remaining_quantity'] = ($remaining_quantity[$key]) ? ($remaining_quantity[$key]) : 0;
                }

                // $this->db->insert('cberp_sales_orders_items', $data);
                $code = trim($product_hsn[$key]);     
                $isChanged = !empty($changedSet) && isset($changedSet[$code]);
                $isInWhole = !empty($wholeSet) && isset($wholeSet[$code]);   

                if($isChanged && in_array($code, $product_hsn)) {
                    $this->db->update('cberp_sales_orders_items', $data, ['salesorder_number'=>$salesorder_number, 'product_code'=>$code]);
                }
                elseif (!$isInWhole && in_array($code, $product_hsn)) 
                {
                    $this->db->insert('cberp_sales_orders_items', $data);
                }
                $existornot = $this->salesorder->check_product_existornot($salesorder_number,$product_hsn[$key]);
                if($existornot==1)
                {
                    $this->db->update('cberp_sales_orders_items', $data, ['salesorder_number'=>$salesorder_number, 'product_code'=>$product_hsn[$key]]);
                }
                else{
                    $this->db->insert('cberp_sales_orders_items', $data);
                }
                
                $flag = true;
                $productlist[$prodindex] = $data;
                $i += numberClean($product_qty[$key]);;
                $prodindex++;
            }
        }
        // echo "<pre>";
        // print_r($productlist);
        // die();
        $bill_date = datefordatabase($invoicedate);
        $bill_due_date = datefordatabase($invocieduedate);

        $total_discount = ($total_discount) ? rev_amountExchange_s(amountFormat_general($total_discount), $currency, $this->aauth->get_user()->loc) : 0;
        $total_tax = ($total_tax) ? rev_amountExchange_s(amountFormat_general($total_tax), $currency, $this->aauth->get_user()->loc) : 0;

       
        $data1 = array('salesorder_number'=>$salesorder_number,'salesorder_date' => $bill_date, 'due_date' => $bill_due_date, 'shipping' => $shipping, 'shipping_tax' => $shipping_tax, 'shipping_tax_type' => $ship_taxtype, 'discount' => $total_discount, 'tax' => $total_tax, 'total' => $total, 'notes' => $notes, 'customer_id' => $customer_id, 'tax_status' => $tax, 'discount_status' => $discount_status, 'format_discount' => $discountFormat, 'reference' => $refer, 'payment_term' => $pterms, 'customer_message' => $propos, 'multi' => $currency, 'customer_order_date'=>$customer_order_date, 'customer_purchase_order'=>$customer_purchase_order,'completed_status'=>$completed_status,'customer_reference_number' => $this->input->post('customer_reference_number'),'customer_contact_person' => $this->input->post('customer_contact_person'),'customer_contact_number' => $this->input->post('customer_contact_number'),'customer_contact_email' => $this->input->post('customer_contact_email'),'converted_status' => 4,'status'=>'draft','order_discount'=>$order_discount,'store_id'=>$store_id);
        $this->db->set($data1);
        $this->db->where('salesorder_number', $salesorder_number);
        $this->db->update('cberp_sales_orders', $data1);
        
        // file upload section starts 22-01-2025
        if($_FILES['upfile'])
        {
            upload_files($_FILES['upfile'], 'Salesorder',$salesorder_number);
        }
        // file upload section ends 22-01-2025           
        detailed_log_history($this->module_number,$salesorder_number,'Data Saved As Draft', $_POST['changedFields']);
        //erp2024 06-01-2025 detailed history log ends 
        if ($flag) {
            
            echo json_encode(array(
                'status' => 'Success','data'=>$salesorder_number
            ));

        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
                "Please add atleast one product in invoice $salesorder_number"));
            $transok = false;
        }

    }

    public function convert_salesorder_to_invoice()
    {
        $masterdata = [];
        $customer_id = $this->input->post('customer_id');
        $invocieno_n = $this->input->post('invocieno');
        $invocieno = $this->input->post('iid');
        // $quote_id = $this->input->post('quote_id');
        $completed_status =  $this->input->post('completed_status');
        $salesorder_number = $this->input->post('salesorder_id');
        $so_prefix_number = $this->input->post('so_prefix_number');
        
        // $records = record_exists_or_not('cberp_sales_orders','id',$salesorder_number);
    
        $invoicedate = $this->input->post('invoicedate');
        $invocieduedate = date('Y-m-d', strtotime($this->input->post('invocieduedate')));
        $notes = $this->input->post('notes', true);
        $tax = $this->input->post('tax_handle');
        $total_tax = 0;
        $total_discount = 0;
        $discountFormat = $this->input->post('discountFormat');
        // erp2024 remove pterms 06-06-2024
        // $pterms = $this->input->post('pterms');
        $customer_reference_number = $this->input->post('customer_reference_number');
        $customer_contact_person = $this->input->post('customer_contact_person');
        $customer_contact_number = $this->input->post('customer_contact_number');
        $customer_contact_email = $this->input->post('customer_contact_email');
        $proposal = $this->input->post('propos');
        $store_id = $this->input->post('store_id');

        $currency = $this->input->post('mcurrency');
        $ship_taxtype = $this->input->post('ship_taxtype');
        $subtotal = rev_amountExchange_s($this->input->post('subtotal'), $currency, $this->aauth->get_user()->loc);
        $shipping = rev_amountExchange_s($this->input->post('shipping'), $currency, $this->aauth->get_user()->loc);
        $shipping_tax = rev_amountExchange_s($this->input->post('ship_tax'), $currency, $this->aauth->get_user()->loc);
        if ($ship_taxtype == 'incl') $shipping = $shipping - $shipping_tax;
        $refer = $this->input->post('refer', true);
        $total = rev_amountExchange_s($this->input->post('total'), $currency, $this->aauth->get_user()->loc);
        // 08-08-2024 erp2024 newlyadded fields 
        $customer_purchase_order = $this->input->post('customer_purchase_order', true);
        $customer_order_date = date('Y-m-d', strtotime($this->input->post('customer_order_date')));    
        $order_discount = rev_amountExchange_s($this->input->post('order_discount'), $currency, $this->aauth->get_user()->loc);        
        $maximum_discount_rate = $this->input->post('maxdiscountrate');
        if ($customer_id == 0) {
            echo json_encode(array('status' => 'Error', 'message' =>
                $this->lang->line('Please add a new client')));
            exit;
        }
      $masterdata = [
            'salesorder_number' => $salesorder_number,
            'customer_id' => $customer_id,
            'employee_id' => $this->session->userdata('id'),
            'reference' => $refer,
            'customer_message' => $proposal,
            'shipping' => $shipping,
            'shipping_tax' => $ship_tax,
            'tax' => $tax,
            'total' => $total,
            'notes' => $notes,
            'format_discount' => $discountFormat,
            // 'payment_term' => $term, // coming from payment section
            // 'pmethod' => $pmethod,
            'store_id' => $store_id,
            'status' => 'pending',
            // 'pamnt' => $pamnt,
            // 'items' => $items,
            // 'term' => $term,
            'customer_order_date' => $customer_order_date,
            'customer_purchase_order' => $customer_purchase_order,
            'completed_status' => $completed_status,
            'customer_reference_number' => $customer_reference_number,
            'customer_contact_person' => $customer_contact_person,
            'customer_contact_number' => $customer_contact_number,
            'customer_contact_email' => $customer_contact_email,
            'order_discount' => $order_discount,
            
        ];

        if($this->input->post('action_type'))
        {
            $this->db->update('cberp_sales_orders',$masterdata,['salesorder_number'=>$salesorder_number]);
        }
       
       
        // file upload section starts 22-01-2025
        if($_FILES['upfile'])
        {
            upload_files($_FILES['upfile'], 'Salesorder',$salesorder_number);
        }
         // file upload section ends 22-01-2025
        //  erp2024 06-01-2025 detailed history log starts
         detailed_log_history($this->module_number,$salesorder_number,'Converted to Invoice', $_POST['changedFields']);
         detailed_log_history($this->module_number,$salesorder_number,'Updated', $_POST['changedFields']);
        // erp2024 06-01-2025 detailed history log ends 
       
    
    
        //sales order items starts
        $productlist = array();
        $prodindex = 0;
        $itc = 0;
        $flag = false;
        $product_id = $this->input->post('pid');
        $product_name1 = $this->input->post('product_name', true);
        $code = $this->input->post('code', true);
        $product_qty = $this->input->post('product_qty');
        $product_price = $this->input->post('product_price');
        $product_tax = $this->input->post('product_tax');
        $product_discount = $this->input->post('product_discount');
        $product_subtotal = $this->input->post('product_subtotal');
        $ptotal_tax = $this->input->post('taxa');
        $ptotal_disc = $this->input->post('disca');
        // $product_des = $this->input->post('product_description', true);
        $product_hsn = $this->input->post('hsn');
        $product_unit = $this->input->post('unit');
        $discount_type = $this->input->post('discount_type');
        $lowest_price = $this->input->post('lowest_price');
        $max_disrate = $this->input->post('maxdiscountrate');
        $product_amt = $this->input->post('product_amt');
        $product_tax =0;
        $grandtotal=0;

        $deleted_items = $this->input->post('deleted_item');
        $deleted_items_array = explode(",", $deleted_items);
        if($deleted_items_array)
        {
            $this->db->where('salesorder_number', $salesorder_number);
            $this->db->where_in('product_code', $deleted_items_array);
            $this->db->delete('cberp_sales_orders_items'); 
        }
        $i=0;
        foreach ($code as $key => $value) {
            if(!empty($code[$key]) && !empty($product_name1[$key]) && $product_qty[$key]>0)
            {
                $total_discount += numberClean(@$ptotal_disc[$key]);
                $total_tax += numberClean($ptotal_tax[$key]);
                
                if($discount_type[$key]=="Amttype"){
                    $discountamount = numberClean($product_amt[$key]);
                }
                else{
                    $discountamount = numberClean($product_discount[$key]);
                }
                if($this->configurations["config_tax"]!="0")
                { 
                    $product_tax = numberClean($product_tax[$key]);
                }
                
                $data = array(
                    'salesorder_number' => $salesorder_number,
                    'product_code' => $product_hsn[$key],
                    'quantity' => numberClean($product_qty[$key]),
                    'price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
                    'tax' => $product_tax,
                    'discount' => $discountamount,
                    'total_amount' => rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc),
                    'total_tax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                    'discount_type' => $discount_type[$key],
                    'lowest_price' => $lowest_price[$key],
                    'total_discount' => rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc),
                    'maximum_discount_rate' => $maximum_discount_rate[$key],
                    'remaining_quantity' => numberClean($product_qty[$key]),
                    'ordered_quantity' => numberClean($product_qty[$key]),
                    'transfered_quantity' => 0,
                    'delivered_quantity' => 0, 
                );
                $code = trim($code[$key]);     
                $isChanged = !empty($changedSet) && isset($changedSet[$code]);
                $isInWhole = !empty($wholeSet) && isset($wholeSet[$code]);   

                if($isChanged && in_array($code, $product_hsn)) {
                    $this->db->update('cberp_sales_orders_items', $data, ['salesorder_number'=>$salesorder_number, 'product_code'=>$code]);
                }
                elseif (!$isInWhole && in_array($code, $product_hsn)) 
                {
                    $this->db->insert('cberp_sales_orders_items', $data);
                }
                $existornot = $this->salesorder->check_product_existornot($salesorder_number,$product_hsn[$key]);
                if($existornot==1)
                {
                    $this->db->update('cberp_sales_orders_items', $data, ['salesorder_number'=>$salesorder_number, 'product_code'=>$product_hsn[$key]]);
                }
                else{
                    $this->db->insert('cberp_sales_orders_items', $data);
                }
                $flag = true;
                $productlist[$prodindex] = $data;
            }   
            $i++;
            $prodindex++;
            $amt = numberClean($product_qty[$key]);
            $itc += $amt;
            $grandtotal += rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc); 
        }

        if ($prodindex > 0) {
            $grandtotal = $grandtotal - $order_discount;
            $this->db->insert_batch('cberp_sales_orders_items', $productlist);
            $this->db->set(array('discount' => rev_amountExchange_s(amountFormat_general($total_discount), $currency, $this->aauth->get_user()->loc), 'tax' => rev_amountExchange_s(amountFormat_general($total_tax), $currency, $this->aauth->get_user()->loc), 'subtotal'=>$grandtotal, 'total'=>$grandtotal));
            $this->db->where('salesorder_number', $salesorder_number);
            $this->db->update('cberp_sales_orders');
           

        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
                "Please choose product from product list. Go to Item manager section if you have not added the products."));
            $transok = false;
        } 
        //sales order items ends
    
    
        // $this->db->trans_start();
        $flag = false;
        $transok = true;
    
    
        echo json_encode(array('status' => 'Success'));
        if ($transok) {
            $this->db->trans_complete();
        } else {
            $this->db->trans_rollback();
        }
    }


}

