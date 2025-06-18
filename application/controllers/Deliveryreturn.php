<?php
/**
 * Cloud Biz Erp  Accounting,  Invoicing  and CRM Software
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
 *   * Tree Code Hub IT (P) Ltd
 * ***********************************************************************
 */

defined('BASEPATH') or exit('No direct script access allowed');

use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\Printer;

class Deliveryreturn extends CI_Controller
{
    private $configurations;
    private $prifix72;
    private $prifix51;
    private $sales_module_group_number;
    private $my_approval_levels;
    private $all_approval_level;
    private $module_number;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('deliveryreturn_model', 'deliveryreturn');
        $this->load->model('deliverynote_model', 'deliverynote');
         $this->load->model('pos_invoices_model', 'invocies');         
         $this->load->model('customers_model', 'customers');
         $this->load->model('quote_model', 'quote');
        $this->load->library("Aauth");        
        $this->load->library('session');
        if (!$this->aauth->is_loggedin()) {
            redirect('/user/', 'refresh');
        }
        // if (!$this->aauth->premission(1)) {
        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        // }

        if ($this->aauth->get_user()->roleid == 2) {
            $this->limited = $this->aauth->get_user()->id;
        } else {
            $this->limited = '';
        }
        $this->load->library("Custom");
        $this->li_a = 'sales';
        $this->configurations = $this->session->userdata('configurations');
        $this->prifix51 =  get_prefix();
        $this->prifix72 =  get_prefix_72();
        $this->sales_module_group_number =  get_module_details_by_name('Sales');
        $this->module_number =  module_number_name('Delivery Return');
    }

    
    //invoices list
    public function index()
    {
        $data['permissions'] = load_permissions('Sales','Sales','Delivery Returns','List');
        $head['title'] = "Delivery Returns";
        $head['usernm'] = $this->aauth->get_user()->username;
        // $this->load->model('invoices_model');
        // $condition = "";
        // $data['counts'] = $this->invoices_model->get_dynamic_count('cberp_delivery_returns','created_date','total_amount',$condition);
        $data['ranges'] = getCommonDateRanges();
        $data['counts'] = $this->deliveryreturn->get_filter_count($data['ranges']);
        $this->load->view('fixed/header', $head);
        $this->load->view('deliveryreturns/deliveryreturns', $data);
        $this->load->view('fixed/footer');
    }

    


    public function ajax_list()
    {
        
        $list = $this->deliveryreturn->get_datatables($this->limited);
        $data = array();
        $no = $this->input->post('start');
        foreach ($list as $deliverynote) {
            $no++;
            $row = array();
            $row[] = $no;
            $target_url = ($deliverynote->status=='Approved') ? '<a href="' . base_url("Deliveryreturn/deliveryreturn?delivery=$deliverynote->delivery_return_number") . '" >'.($deliverynote->delivery_return_number).'</a>' : '<a href="' . base_url("Deliveryreturn/deliveryreturn?delivery=$deliverynote->delivery_return_number") . '" >'.($deliverynote->delivery_return_number).'</a>';
            // $target_url = ($deliverynote->status=='Approved') ? '<a href="' . base_url("Deliveryreturn/deliveryreturn_view?id=$deliverynote->delivery_return_number") . '" >'.($deliverynote->delivery_return_number).'</a>' : '<a href="' . base_url("Deliveryreturn/deliveryreturn_approval?delivery=$deliverynote->delivery_return_number") . '" >'.($deliverynote->delivery_return_number).'</a>';
            $row[] = $target_url;
            $row[] = dateformat($deliverynote->created_date);
            // $row[] = "#".$deliverynote->customer_id; created
            $row[] = "#".$deliverynote->customer_id." ".$deliverynote->name;
            $row[] = $deliverynote->data;
            // $row[] = $deliverynote->created_time;            
            $row[] = $deliverynote->total_amount;
            if($deliverynote->status=="Delivered")//
            {
                $status = "<span class='st-pending'>".$deliverynote->status."</span>";
                // $status = "<span class='st-pending'>".$deliverynote->status."</span>";
            }
            else if($deliverynote->status=="Approved")//
            {
                $status = "<span class='st-accepted'>".$deliverynote->status."</span>";
            }
            else{
                $status = "<span class='st-created'>Created</span>";
                // $status = "<span class='st-Closed'>".$deliverynote->status."</span>";
            }
            $row[] = $status;
            if($deliverynote->delivery_note_status =="Invoiced")
            {
                $row[] = "<span class='st-".$deliverynote->delivery_note_status."'>".$deliverynote->delivery_note_status."</span>";

            }
            else{
                $row[] = "";

            }
            $reprintBtn = '<a href="' . base_url("Deliveryreturn/reprintnote?delivery=$deliverynote->delivery_return_number&sales=$deliverynote->salesorder_number&cust=$deliverynote->customer_id") . '" target="_blank" class="btn btn-sm btn-secondary"><i class="fa fa-print"></i> Print</a>';
            if($deliverynote->convert_to_credit_note_flag=='1'){
                $disablecls = "disable-class";
            }
            else{
                $disablecls="";
            }

            // $deliveryBtn = '<a href="' . base_url("invoices/invoice_creditnote?id=$deliverynote->invoice_id") . '"  class="btn btn-sm btn-secondary '.$disablecls.'"><i class="fa fa-undo"></i> '.$this->lang->line('Convert to Credit Note').'</a>';
           
            $deliveryBtn = '<a href="' . base_url("Deliveryreturn/deliveryreturn_to_creditnote?delivery=$deliverynote->delivery_return_number") . '"  class="btn btn-sm btn-secondary '.$disablecls.'"><i class="fa fa-undo"></i> '.$this->lang->line('Convert to Credit Note').'</a>';

            if($deliverynote->status=="Approved" && $deliverynote->delivery_note_status !="Invoiced")
            {
                $approvalBtn = "";
            }
            else if($deliverynote->status=="Approved" && $deliverynote->delivery_note_status =="Invoiced")
            {
                $approvalBtn = $deliveryBtn;
            }
            else{
                $approvalBtn = '<a href="' . base_url("Deliveryreturn/deliveryreturn?delivery=$deliverynote->delivery_return_number") . '"  class="btn btn-sm btn-secondary"><i class="fa fa-thumbs-up"></i> Approval</a>';
                // $approvalBtn = '<a href="' . base_url("Deliveryreturn/deliveryreturn_approval?delivery=$deliverynote->delivery_return_number") . '"  class="btn btn-sm btn-secondary"><i class="fa fa-thumbs-up"></i> Approval</a>';
            }
           

            $row[] = $reprintBtn.'&nbsp;'.$approvalBtn;
            // $row[] = $reprintBtn.'&nbsp;'.$invoiceBtn.'&nbsp;'.$deliveryBtn;
            $data[] = $row;
        }
        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->deliveryreturn->count_all($this->limited),
            "recordsFiltered" => $this->deliveryreturn->count_filtered($this->limited),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }
    // erp2024 delivery note view page 09-07-2024 starts
    public function deliveryreturn_view()
    {
        $data['prefix'] = $this->prifix72['deliveryreturn_prefix'];
        $data['permissions'] = load_permissions('Sales','Sales','Delivery Returns','View Page');
        $tid = intval($this->input->get('id'));
        $data['id'] = $tid;
        $data['currency'] = $this->quote->currencies();
        $head['title'] = "Delivery Return #" . $tid;
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['notemaster'] = $this->deliveryreturn->deliveryreturnbyid($tid);
        $data['trackingdata'] = tracking_details('delivery_return_number',$tid);
        $data['products'] = $this->deliveryreturn->deliveryreturn_products($tid);
        $data['deliverynotedetails'] = $this->deliveryreturn->deliverynote_byretid($data['notemaster']['deliverynote_id']);
        $data['invoiceid'] = ($data['deliverynotedetails']['status']=='Invoiced') ? $this->deliveryreturn->invoice_details_by_delnoteid($data['notemaster']['deliverynote_id']):"";
        // echo "<pre>"; print_r($data['notemaster']); die();
    
        //erp2024 06-01-2025 detailed history log starts
        $page = $this->module_number;
        $data['detailed_log']= $this->deliveryreturn->get_detailed_log($tid,$page);
        $products = $data['detailed_log'];
        $groupedBySequence = []; // Initialize an empty array for grouping

        foreach ($products as $product) {
            $sequence = $product['seqence_number'];
            $groupedBySequence[$sequence][] = $product; // Group by sequence number
        }
        
        $data['groupedDelreturns'] = $groupedBySequence;
        $data['journals_records'] = $this->deliveryreturn->delivery_return_journal_records($tid);
        $this->load->view('fixed/header', $head);
        $this->load->view('deliveryreturns/view-delivery-return', $data);
        $this->load->view('fixed/footer');
    }

    public function reprintnote()
    {
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
        $data['prefix'] = $this->prifix72['deliveryreturn_prefix'];
        $delevery_note_id = $this->input->get('delivery', true);
        $salesorder_id = $this->input->get('sales', true);
        $customer_id = $this->input->get('cust', true);
        $data["salesorder_id"] = $salesorder_id;
        $data["delevery_note_id"] = $delevery_note_id;

        $this->db->select('cberp_delivery_returns.created_date,cberp_delivery_returns.order_discount');
        $this->db->from('cberp_delivery_returns');
        $this->db->where('cberp_delivery_returns.delivery_return_number', $delevery_note_id);
        $query1 = $this->db->get();
        $res =  $query1->row_array();
        $data["deleveryreturn_createddate"] = $res['created_date'];
        $data["order_discount"] = $res['order_discount'];
        $client = ""; 
        $data['CustDetails'] = $this->invocies->salesorder_cust($customer_id);
        $data['salesman'] = $this->customers->customer_salesman($customer_id);
        if(!empty($data['CustDetails'])){ 
            $client = '' . $data['CustDetails'][0]['name'] . '<br>' . $data['CustDetails'][0]['address'] . ','. $data['CustDetails'][0]['city'] .' <br>' . $data['CustDetails'][0]['phone'] . '<br>' .$data['CustDetails'][0]['email'] ;
        
            $data["custId"]=$data['CustDetails'][0]['customer_id'];
        }
        $data["client"]=$client;
       
        $loc = location($this->aauth->get_user()->loc);
        $data['companyNanme']=$loc['cname'];
        $company = '' . $loc['address'] . '<br>' . $loc['city'] . ', ' . $loc['region'] . '<br>' . $loc['country'] . ' -  ' . $loc['postbox'] . '<br>' . $this->lang->line('Phone') . ': ' . $loc['phone'] . '<br> ' . $this->lang->line('Email') . ': ' . $loc['email'];
        $data['lang']['company'] = $company;

        // ==================================================================salesorder_number
        $this->db->select('cberp_delivery_returns.customer_id,  cberp_delivery_returns.discount, cberp_delivery_returns.subtotal, cberp_delivery_returns.tax, cberp_delivery_returns.total_amount, cberp_delivery_return_items.*,cberp_products.product_code,cberp_product_description.product_name,cberp_products.unit AS productunit');
        $this->db->from('cberp_delivery_returns');
        // $this->db->where('cberp_delivery_returns.salesorder_number', $salesorder_id);
        $this->db->join('cberp_delivery_return_items', 'cberp_delivery_return_items.delivery_return_number = cberp_delivery_returns.delivery_return_number');
        
        $this->db->join('cberp_products', 'cberp_products.product_code = cberp_delivery_return_items.product_code');
        $this->db->join('cberp_product_description', 'cberp_product_description.product_code = cberp_products.product_code');
        $this->db->where('cberp_delivery_returns.delivery_return_number', $delevery_note_id);
        $query = $this->db->get();
        $data['products'] = $query->result_array();
        // echo "<pre>"; print_r($data['products']); die();
        // $this->load->view('deliveryreturns/deliveryreturnreprintpdf-ltr',$data);
        // $this->load->view('deliveryreturns/deliverynotes');
        $html = $this->load->view('deliveryreturns/deliveryreturnreprintpdf-' . LTR, $data, true);         
        ini_set('memory_limit', '64M');
        $this->load->library('pdf');
        $pdf = $this->pdf->load();
        $pdf->WriteHTML($html);       
        $pdf->Output('reprint-note' . $pay_acc . '.pdf', 'I');       
            
    }
    public function deliveryreturn_to_creditnote()
    {
        
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
        $data['prefix'] = $this->prifix72['invoicereturn_prefix'];        
        $data['permissions'] = load_permissions('Sales','Sales','Delivery Returns','List');
        $this->load->model('Stockreturn_model', 'stockreturn');
        $this->load->library("Common"); 
        $this->load->model('plugins_model', 'plugins');
        $data['exchange'] = $this->plugins->universal_api(5);
        $data['currency'] = $this->stockreturn->currencies();
        $data['taxlist'] = $this->common->taxlist($this->config->item('tax'));
        $this->load->model('customers_model', 'customers');
        $data['customergrouplist'] = $this->customers->group_list();
        $data['lastinvoice'] = $this->stockreturn->lastinvoicereturn();
        
        // $this->load->model('invoice_creditnotes_model', 'invocies_creditnote');
        $data['terms'] = $this->stockreturn->billingterms();
        $head['title'] = "Convert to Credit Note";
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['warehouse'] = $this->stockreturn->warehouses();
        $data['taxdetails'] = $this->common->taxdetail();
        $tid = ($this->input->get('delivery'));
        $data['delivery_return_number'] = $tid;
        $data['notemaster'] = $this->deliveryreturn->deliveryreturnbyid($tid);
        $deliverynote_id = $data['notemaster']['delivery_note_number'] ;
        $data['deliverynote_status'] = $this->deliveryreturn->deliverynote_status($deliverynote_id);
        $data['invoice_details'] = ($data['deliverynote_status']=="Invoiced") ? $this->deliveryreturn->invoice_details($deliverynote_id) : "";
         $data['trackingdata'] = tracking_details('delivery_return_number',$tid);
        // $data['salesorderid'] = $this->deliveryreturn->deliveryreturn_maindata($tid);
        $data['products'] = $this->deliveryreturn->deliveryreturn_products($tid);
        //   echo "<pre>"; print_r($data['products']); die();
        $data['customer'] = $this->deliveryreturn->customer_details($tid);
        $this->load->view('fixed/header', $head);
        $this->load->view('deliveryreturns/stockreturncreditnote', $data);
        $this->load->view('fixed/footer');
    }

    public function deliveryreturn()
    {
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
        $tid = $this->input->get('delivery');
        $type = $this->input->get('type');
        $data['id'] = $tid;
        $data['currency'] = $this->quote->currencies();
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['configurations'] = $this->configurations;
        $data['prefix'] = $this->prifix72['deliveryreturn_prefix'];
        
        $head['title'] = "Delivery Return " . $data['prefix'].$tid;
        $data['type'] = $type;
        $data['permissions'] = load_permissions('Sales','Sales','Delivery Returns','View Page');
        if($type)
        {
            $data['notemaster'] = $this->deliverynote->deliverynoteby_number($tid);
            $data['products'] = $this->deliverynote->deliverynote_products_for_return($tid);
            // echo "<pre>"; print_r($data['products']); die();
            $data['delivery_return_number'] = $this->deliverynote->last_delivery_return_number();
            $data['deliverynote_status'] = $this->deliveryreturn->deliverynote_status($tid);
            $data['invoiceid'] = ($data['deliverynote_status']=='Invoiced') ? $this->deliveryreturn->invoice_details_by_delnoteid($tid):"";

            $data['trackingdata'] = tracking_details('deliverynote_number',$tid);
            $data['invoice_details'] = ($data['deliverynote_status']=="Invoiced") ? $this->deliveryreturn->invoice_details($tid) : [];
            $data['created_employee'] =[];
        }
        else{
            $data['notemaster'] = $this->deliveryreturn->deliveryreturnbyid($tid);
            $data['created_employee'] = employee_details_by_id($data['notemaster']['created_by']);               
            $data['assigned_customer']  = get_customer_details_by_id($data['notemaster']['customerid']);
            $delivery_note_number = $data['notemaster']['delivery_note_number'];
            $data['deliverynote_status'] = $this->deliveryreturn->deliverynote_status($delivery_note_number);
            $data['invoice_details'] =  $this->deliveryreturn->invoice_details($delivery_note_number);            
            $data['journals_records'] = $this->deliveryreturn->delivery_return_journal_records($tid);
            $data['trackingdata'] = tracking_details('delivery_return_number',$tid);
            $data['products'] = $this->deliveryreturn->deliveryreturn_products($tid);
            // echo "<pre>"; print_r($data['products']); die();
            $data['delivery_return_number'] = $data['id'];
             if($this->module_number)
            {
                $data['approved_levels'] = function_approved_levels($this->module_number,$data['delivery_return_number']);
                $data['approval_level_users'] =  linked_user_module_approvals_by_module_number($this->sales_module_group_number);   
                $data['my_approval_permissions'] =  linked_user_module_approvals_by_module_number($this->sales_module_group_number,$this->session->userdata('id'));
                $data['module_number'] = $this->module_number;
            }

            
        }
        //erp2024 06-01-2025 detailed history log starts
        $page = $this->module_number;
        $data['detailed_log']= get_detailed_logs($tid,$page);
        $products = $data['detailed_log'];
        $groupedBySequence = []; // Initialize an empty array for grouping

        foreach ($products as $product) {
            $sequence = $product['seqence_number'];
            $groupedBySequence[$sequence][] = $product; // Group by sequence number
        }
        
        $data['groupedDatas'] = $groupedBySequence;
        
    
        $this->load->view('fixed/header', $head);
        // $this->load->view('deliverynotes/delivery-return', $data);
        $this->load->view('deliveryreturns/approved-delivery-return', $data);
        $this->load->view('fixed/footer');
    }

    public function delivery_return_action()
    {

        $flg = 0;
        $grandtotal = 0;
        $grandtax = 0;
        $granddiscount = 0;
        $grandsubtotal = 0;
        $grandtax = 0;
        $return_qty = $this->input->post('return_qty', true);
        foreach($return_qty as $key1 =>$row){
            if($return_qty[$key1]>0){
                $flg = 1;
                break;
            }            
        }
        $return_type = $this->input->post('return_type', true);
        if($flg==1){
            $masterdata =[];
            $masterdata['delivery_note_number'] = $this->input->post('delivery_note_number', true);
            $masterdata['delivery_return_number'] = ($return_type) ? $this->deliverynote->last_delivery_return_number() : $this->input->post('delivery_return_number', true);
            $masterdata['store_id'] = $this->input->post('store_id', true);
            $masterdata['delivery_note_status'] = $this->input->post('status', true);
            $masterdata['customer_id'] = $this->input->post('customer_id', true);
            $masterdata['shipping'] = numberClean($this->input->post('shipping', true));
            $masterdata['created_date'] = date('Y-m-d H:i:s');
            $masterdata['created_by'] =  $this->session->userdata('id');
            $masterdata['order_discount'] = $this->input->post('order_discount', true);
            $masterdata['order_discount_percentage'] = $this->input->post('order_discount_percentage', true);
            $masterdata['total_amount'] = $this->input->post('total', true);

            $this->db->insert('cberp_delivery_returns', $masterdata);
            $product_code = $this->input->post('hsn', true);
            $damaged_qty = $this->input->post('damaged_qty', true);
            // $product_price = $this->input->post('product_price', true); 
            $totaltax = $this->input->post('taxa', true); 
            $totaldiscount = $this->input->post('disca', true);
            $product_subtotal = $this->input->post('product_subtotal', true);
            $unit = $this->input->post('unit', true);
            $product_tax = $this->input->post('product_tax', true);
            $delivered_qty = $this->input->post('delivered_qty', true);
            $product_price = $this->input->post('product_price', true);
            $discount_type = $this->input->post('discount_type', true);
            $product_discount = $this->input->post('product_discount', true);
            $product_amt = $this->input->post('product_amt', true);
            $product_cost = $this->input->post('product_cost', true);
            $income_account_number = $this->input->post('income_account_number', true);
        
      
            $delivery_return_number = $masterdata['delivery_return_number'];
            //erp2024 06-01-2025 detailed history log starts
            
            detailed_log_history($this->module_number,$delivery_return_number,'Created', $changedFields);
            detailed_log_history('Deliverynote',$masterdata['delivery_note_number'],'Delivery Returned', $_POST['changedFields']);
            //erp2024 06-01-2025 detailed history log ends 
            //insert data to trackin table
            insertion_to_tracking_table('delivery_return_number', $delivery_return_number,'deliverynote_number',$masterdata['delivery_note_number']);

            foreach($return_qty as $key=>$row){
                if($return_qty[$key]>0)
                {
                    $items = [];
                    $items['delivery_return_number'] = $delivery_return_number;
                    $items['product_code'] = $product_code[$key];
                    $items['return_quantity'] = $return_qty[$key];
                    $items['damaged_quantity'] = ($damaged_qty[$key])?$damaged_qty[$key]:0;
                    $items['total_tax'] = numberClean($totaltax[$key]);
                    $items['total_discount'] = numberClean($totaldiscount[$key]);
                    $items['subtotal'] = numberClean($product_subtotal[$key]);
                    $items['product_price'] = numberClean($product_price[$key]);
                    if($discount_type[$key]=="Amttype"){
                        $discountamount = numberClean($product_amt[$key]);
                    }
                    else if($discount_type[$key]=="Perctype"){
                        $discountamount = numberClean($product_discount[$key]);
                    }
                    else{
        
                    }
                    $items['product_discount'] = $discountamount;
                    $items['discount_type'] = $discount_type[$key];
                    // Update the grand totals
                    $grandsubtotal = $grandsubtotal + $items['subtotal'];
                    $grandtax = $grandtax + $items['totaltax'];
                    $granddiscount = $granddiscount + $items['totaldiscount'];
                    $items['product_tax'] = numberClean($product_tax[$key]);
                    $items['delivered_quantity'] = numberClean($delivered_qty[$key]);
                    $items['product_cost'] = ($product_cost[$key]);
                    $this->db->insert('cberp_delivery_return_items', $items);

                     //erp2024  update delivered quantity to cberp_delivery_note_items table 09-07-2024
                    //  $this->deliverynote->delivered_qty_update_to_delivery_note_items_table($masterdata['deliverynote_id'], $items['product_id'], $return_qty[$key]);
                    //erp2024  update delivered quantity to cberp_delivery_note_items table 09-07-2024 ends 
                    
                    //erp2024 product stock update 09-07-2024
                    // $currentprdstock = $this->deliverynote->productstockbyid($items['product_id']);
                    // if($currentprdstock>0){            
                    //     $currentprdstock = $currentprdstock + $items['return_qty'];            
                    //     $this->db->where('pid', $items['product_id']);
                    //     $this->db->update('cberp_products', ['qty'=>$currentprdstock]);
                    // }


                    // $noteitems = [];
                    // $existinglist = $this->deliverynote->existingnotelist($masterdata['deliverynote_id'],$items['product_id']);
                    // if($existinglist>0){
                    //     $noteitems['product_qty'] = $existinglist-$items['return_qty'];
                    //     $noteitems['totaltax'] = $items['totaltax'];
                    //     $noteitems['subtotal'] = $items['subtotal'];
                    //     $noteitems['totaldiscount'] = $items['totaldiscount'];
                    //     $this->db->where('delevery_note_id', $masterdata['deliverynote_id']);
                    //     $this->db->where('product_id', $items['product_id']);
                    //     $this->db->update('cberp_delivery_note_items', $noteitems);
                    // }
                    
                }
            }            
            $granddata['subtotal'] = $grandsubtotal;
            $granddata['discount'] = $granddiscount;
            $granddata['tax'] = $grandtax;
            $this->db->where('delivery_return_number', $items['delivery_return_number']);
            $this->db->update('cberp_delivery_returns',$granddata);


            // erp2024 update customer table available credit limit 10-09-2024
            // $this->load->model('transactions_model', 'transactions');
            // $custdata = $this->transactions->check_customer_account_details($masterdata['customer_id']);
            // $custcredit_limit = $custdata['credit_limit'];
            // $cust_avalable_credit_limit = (!empty($custdata['avalable_credit_limit'])) ? $custdata['avalable_credit_limit']: 0;

            // $subamount = $cust_avalable_credit_limit + $grandsubtotal;
            // $this->db->set('avalable_credit_limit', $subamount, FALSE);
            // $this->db->where('id', $masterdata['customer_id']);
            // $this->db->update('cberp_customers');
            // erp2024 update customer table available credit limit 10-09-2024 ends



            $link = '<a  href="' . base_url("DeliveryNotes"). '" class="btn btn-secondary btn-sm" title="Back to delivery notes"><i class="fa fa-arrow-left"></i> Back to delivery notes</a>';
            $returns = '<a  href="' . base_url("Deliveryreturn"). '" class="btn btn-secondary btn-sm" target="_blank" title="Deliveryreturn"><i class="fa fa-arrow-right"></i> Go to delivery returns</a>';
            header('Content-Type: application/json');
            echo json_encode(array('status' => 'Success', 'message' =>$this->lang->line('Delivery Return') . "&nbsp;".$link."&nbsp;".$returns, 'data'=>$delivery_return_number));
            
        }
        else{
           echo json_encode(array('status' => 'Error', 'message' =>'Must enter at least one return quantity'));
        }
       

    }

    public function deliveryreturn_approval()
    {
        $tid = intval($this->input->get('delivery'));
        $data['id'] = $tid;        
        $data['currency'] = $this->quote->currencies();
        $head['title'] = "Delivery Return #" . $tid;
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['notemaster'] = $this->deliveryreturn->deliveryreturnbyid($tid);
        $deliverynote_id = $data['notemaster']['deliverynote_id'];
        $data['deliverynote_status'] = $this->deliveryreturn->deliverynote_status($deliverynote_id);
        $data['invoice_details'] =  $this->deliveryreturn->invoice_details($deliverynote_id);
        
        $data['trackingdata'] = tracking_details('delivery_return_number',$tid);
        $data['products'] = $this->deliveryreturn->deliveryreturn_products($tid);
        $data['configurations'] = $this->configurations;
        // echo "<pre>"; print_r($data['notemaster']); die();
        $this->load->view('fixed/header', $head);
        $this->load->view('deliveryreturns/approved-delivery-return', $data);
        $this->load->view('fixed/footer');
    }

    public function delivery_return_approval_action()
    {
    
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);  
        $flg = 0;
        $grandtotal = 0;
        $grandtax = 0;
        $granddiscount = 0;
        $grandsubtotal = 0;
        $grand_product_cost = 0;
        $grand_damaged_product_cost = 0;
        $damaged_product_cost = 0;
        $return_qty = $this->input->post('return_qty', true);
      
        foreach($return_qty as $key1 =>$row){
            if($return_qty[$key1]>0){
                $flg = 1;
                break;
            }            
        }
        if($flg==1){
            
            $transaction_number = get_latest_trans_number();
            $delivery_return_number = $this->input->post('delivery_return_number', true);
            
            $deliverynote_status = $this->input->post('deliverynote_status', true);

            $masterdata =[];
            // $masterdata['approved_date'] = date('Y-m-d H:i:s');
            // $masterdata['approval_flg'] = "1";
            $masterdata['status'] = "Approved";
            $masterdata['delivery_note_status'] = $deliverynote_status;
            $masterdata['approval_comments'] = $this->input->post('approval_comments', true);
            // $masterdata['approved_by'] =  $this->session->userdata('id');
            $masterdata['transaction_number'] =  $transaction_number;
            $masterdata['order_discount'] = $this->input->post('order_discount', true);
            $masterdata['order_discount_percentage'] = $this->input->post('order_discount_percentage', true);
            $masterdata['total_amount'] = $this->input->post('total', true);
            // $masterdata['delivery_note_number'] = $this->input->post('delivery_note_number', true);    
            // $masterdata['delivery_return_number'] = $this->input->post('delivery_return_number', true);
            $record_found = record_exists_or_not('cberp_delivery_returns','delivery_return_number',$delivery_return_number);
            if($record_found)
            {
                $this->db->update('cberp_delivery_returns',$masterdata,['delivery_return_number'=>$delivery_return_number]);
            }
            else{
                $masterdata['customer_id'] = $this->input->post('customer_id', true);
                $masterdata['store_id'] = $this->input->post('store_id', true);
                $masterdata['salesorder_id'] = $this->input->post('salesorder_id', true);
                $masterdata['delivery_note_status'] = $this->input->post('deliverynote_status', true);
                $masterdata['shipping'] = numberClean($this->input->post('shipping', true));
                $masterdata['created_date'] = date('Y-m-d H:i:s');
                $masterdata['created_by'] =  $this->session->userdata('id');
                $this->db->insert('cberp_delivery_returns', $masterdata);            
                $delivery_return_number = $this->db->insert_id();
                //insert data to trackin table check_customer_account_details
                insertion_to_tracking_table('delivery_return_number', $delivery_return_number,'deliverynote_number',$masterdata['delivery_note_number']);
            }

            $total = $this->input->post('total', true);

            $unit = $this->input->post('unit', true);
            // $product_tax = $this->input->post('product_tax', true);
            $delivered_qty = $this->input->post('delivered_qty', true);
            $discount_type = $this->input->post('discount_type', true);
            $product_discount = $this->input->post('product_discount', true);
            $product_amt = $this->input->post('product_amt', true);

            $product_id = $this->input->post('product_id', true);
            $invoice_number = $this->input->post('invoice_number', true);
            $product_price = $this->input->post('product_price', true);
            $damaged_qty = $this->input->post('damaged_qty', true);
            $totaldiscount = $this->input->post('disca', true);
            $product_subtotal = $this->input->post('product_subtotal', true);
            $store_id = $this->input->post('store_id', true);
            $customer_id = $this->input->post('customer_id', true);
            $income_account_number = $this->input->post('income_account_number', true);
            // $transaction_number = $this->input->post('transaction_number', true);
            $order_discount = $this->input->post('order_discount', true);
            $product_cost = $this->input->post('product_cost', true);
            $order_discount_percentage = $this->input->post('order_discount_percentage', true);
             
            $totaltax = $this->input->post('taxa', true);
            // erp2024 19-12-2024 load default accounts
            $default_cost_of_goods_account = default_chart_of_account('cost_of_goods_solid');
            $default_inventory_account = default_chart_of_account('inventory');
            $default_damage_account = default_chart_of_account('damage_account');
            $product_code = $this->input->post('hsn', true);
            $warehouseprdstock=0;

            $this->db->delete('cberp_delivery_return_items',['delivery_return_number'=>$delivery_return_number]);
            $warehouseprdstock =0;
            $warhouse_prdstock = 0;
            $grandprice = 0;
            $producttransdata1 = [];
            foreach($return_qty as $key=>$row){
               if($return_qty[$key]>0)
                {
                    $items = [];
                    $items['delivery_return_number'] = $delivery_return_number;
                    $items['product_code'] = $product_code[$key];
                    $items['return_quantity'] = $return_qty[$key];
                    $items['damaged_quantity'] = ($damaged_qty[$key])?$damaged_qty[$key]:0;
                    $items['total_tax'] = numberClean($totaltax[$key]);
                    $items['total_discount'] = numberClean($totaldiscount[$key]);
                    $items['subtotal'] = numberClean($product_subtotal[$key]);
                    $items['product_price'] = numberClean($product_price[$key]);
                    $items['approved_return_quantity'] =$return_qty[$key];
                    $items['approved_damaged_quantity'] = $damaged_qty[$key];
                    if($discount_type[$key]=="Amttype"){
                        $discountamount = numberClean($product_amt[$key]);
                    }
                    else if($discount_type[$key]=="Perctype"){
                        $discountamount = numberClean($product_discount[$key]);
                    }
                    else{
        
                    }
                    $items['product_discount'] = $discountamount;
                    $items['discount_type'] = $discount_type[$key];
                    // Update the grand totals
                    $grandsubtotal = $grandsubtotal + $items['subtotal'];
                    $grandtax = $grandtax + $items['totaltax'];
                    $granddiscount = $granddiscount + $items['totaldiscount'];
                    $items['product_tax'] = numberClean($product_tax[$key]);
                    $items['delivered_quantity'] = numberClean($delivered_qty[$key]);
                    $items['product_cost'] = ($product_cost[$key]);
                    $this->db->insert('cberp_delivery_return_items', $items);
                
                    
                    $this->deliverynote->delivered_qty_update_to_delivery_note_items_table($this->input->post('delivery_note_number', true), $items['product_code'], $return_qty[$key]);
                 
                    // Update the grand totals
                    $grandsubtotal = $grandsubtotal + $product_subtotal[$key];
                    // $grandtax = $grandtax + $items['totaltax'];
                    $granddiscount = $granddiscount + $totaldiscount[$key];
                    // $this->db->update('cberp_delivery_return_items',$items,['delivery_return_number'=>$delivery_return_number, 'product_id' => $items['product_id']]);
                    


                     //erp2024  update delivered quantity to cberp_delivery_note_items table 09-07-2024
                     //$this->deliverynote->delivered_qty_update_to_delivery_note_items_table($masterdata['deliverynote_id'], $items['product_id'], $return_qty[$key]);
                    //erp2024  update delivered quantity to cberp_delivery_note_items table 09-07-2024 ends 
                    
                    //erp2024 product stock update 09-07-2024
                    $currentprdstock = $this->deliverynote->productstockbyid($items['product_code']);
                    // if($currentprdstock>0){      
                        $actualupdatestock = $items['approved_return_quantity'] -  $items['approved_damaged_quantity'];                        
                        
                        $currentprdstock = $currentprdstock + $actualupdatestock;  
                            
                        $this->db->where('product_code', $items['product_code']);
                        $this->db->update('cberp_products', ['onhand_quantity'=>$currentprdstock]);
                            
                 
                    // }
                    $warehouseprdstock = $this->deliverynote->warehouseprdstock_byprdid($items['product_code'],$store_id);
                    $warhouse_prdstock = $warhouse_prdstock + $actualupdatestock;            
                    $this->db->where('store_id', $store_id);
                    $this->db->where('product_code', $items['product_code']);
                    $this->db->update('cberp_product_to_store', ['stock_quantity'=>$currentprdstock]);
                    $actulprice1 = $product_price[$key]*$return_qty[$key];
                    $order_discount_perc = convert_order_discount_percentage_to_amount($actulprice1,$order_discount_percentage);
                    $actulprice = $actulprice1;
                    $grandprice +=  $actulprice;

                    //insert data to avarage cost table
                    //parameters are product_id,product_cost,transaction_quantity,transaction_type
                    // insert_data_to_average_cost_table($product_code[$key],$product_cost[$key],$return_qty[$key],get_costing_transation_type("Sales Return"));
                    
                     
                    
                    // $actulprice = $product_subtotal[$key]-$order_discount_perc;

                    //commented on 15-02-2025
                    // $producttransdata =  [
                    //     'acid' => $income_account_number[$key],
                    //     'type' => 'Asset',
                    //     'cat' => 'Delivery Return',
                    //     'debit' => $actulprice,
                    //     'eid' => $this->session->userdata('id'),
                    //     'date' => date('Y-m-d'),
                    //     'transaction_number'=>$transaction_number,
                    //     // 'invoice_number'=>$invoice_number
                    // ];
                    // $this->db->set('lastbal', 'lastbal + ' . $actulprice, FALSE);
                    // $this->db->where('acn', $income_account_number[$key]);
                    // $this->db->update('cberp_accounts'); 
                    // $this->db->insert('cberp_transactions', $producttransdata);
                    // $this->db->update('cberp_transactions', $producttransdata,['transaction_number'=>$transaction_number,'acid'=>$income_account_number[$key]]);

                    $producttransdata1[$income_account_number[$key]][] =  [
                        'acid' => $income_account_number[$key],
                        'credit' => $actulprice
                    ];
                   
                    // cost of goods transaction
                    $total_product_cost = numberClean($product_cost[$key])*numberClean($return_qty[$key]);
                    $grand_product_cost += $total_product_cost;

                    $damaged_product_cost = numberClean($product_cost[$key])*numberClean($damaged_qty[$key]);
                    $grand_damaged_product_cost += $damaged_product_cost;


                    
                    
                }
            }    

            //erp2024 06-01-2025 detailed history log starts
            detailed_log_history($this->module_number,$delivery_return_number,'Return Approved', $_POST['changedFields']);
            //erp2024 06-01-2025 detailed history log ends 
            // erp2024 transactions starts 25-10-2024
            $groupedData = [];
            // if($producttransdata1)
            // {
            //     foreach ($producttransdata1 as $acid => $transactions) {
            //         $totalCredit = 0;
            
            //         foreach ($transactions as $transaction) {
            //             $totalCredit += $transaction['credit'];
            //         }
            
            //         // Store the summed data for each `acid`
            //         $groupedData[] = [
            //             'acid' => $acid,
            //             'type' => 'Asset',
            //             'cat' => 'Delivery Return',
            //             'debit' => $totalCredit,
            //             'eid' => $this->session->userdata('id'),
            //             'date' => date('Y-m-d'),
            //             'transaction_number'=>$transaction_number
            //         ];
            //         $this->db->set('lastbal', 'lastbal + ' . $totalCredit, FALSE);
            //         $this->db->where('acn', $acid);
            //         $this->db->update('cberp_accounts'); 
    
            //     }
            // }
            if($deliverynote_status != 'Invoiced')
            {
                $invoice_receivable_account_details = default_chart_of_account('accounts_receivable');
                $receivable_data = [
                    'acid' => $invoice_receivable_account_details,
                    'type' => 'Asset',
                    'cat' => 'Delivery Return',
                    'credit' => $grandprice,
                    'eid' => $this->session->userdata('id'),
                    'date' => date('Y-m-d'),
                    'transaction_number'=>$transaction_number,
                ];
                $this->db->insert('cberp_transactions',$receivable_data);
        
                $this->db->set('lastbal', 'lastbal - ' .$grandprice, FALSE);
                $this->db->where('acn', $invoice_receivable_account_details);
                $this->db->update('cberp_accounts'); 

                $invoice_return_account = default_chart_of_account('sales_returns');
                $invoice_return_data = [
                'acid' => $invoice_return_account,
                'type' => 'Asset',
                'cat' => 'Delivery Return',
                'debit' => $grandprice,
                'eid' => $this->session->userdata('id'),
                'date' => date('Y-m-d'),
                'transaction_number'=>$transaction_number
                ];
                $this->db->insert('cberp_transactions',$invoice_return_data);
                $this->db->set('lastbal', 'lastbal + ' .$grandprice, FALSE);
                $this->db->where('acn', $invoice_return_account);
                $this->db->update('cberp_accounts');
            } 

            // if (($groupedData)) {
            //     $this->db->insert_batch('cberp_transactions', $groupedData);
            // }
            // erp2024 transactions ends 25-10-2024 order_discount

            //erp2024 totaldiscount transaction 11-11-2024 starts
            // if($granddiscount>0)
            // {
            //     $discount_account_details = default_chart_of_account('sales_discount');
            //     $discount_data = [
            //         'acid' => $discount_account_details,
            //         'type' => 'Asset',
            //         'cat' => 'Delivery Return',
            //         'credit' => $granddiscount,
            //         'eid' => $this->session->userdata('id'),
            //         'date' => date('Y-m-d'),
            //         'transaction_number'=>$transaction_number,
            //         // 'invoice_number'=>$invoice_number
            //     ];
     
            //     $this->db->insert('cberp_transactions',$discount_data);
            //     $this->db->set('lastbal', 'lastbal - ' .$granddiscount, FALSE);
            //     $this->db->where('acn', $discount_account_details);
            //     $this->db->update('cberp_accounts'); 
            // }

            // if($order_discount)
            // {
            //     $order_discount_account_number = default_chart_of_account('order_discount');
            //     $discount_data1 = [
            //         'acid' => $order_discount_account_number,
            //         'type' => 'Asset',
            //         'cat' => 'Delivery Return',
            //         'credit' => $order_discount,
            //         'eid' => $this->session->userdata('id'),
            //         'date' => date('Y-m-d'),
            //         'transaction_number'=>$transaction_number,
            //         // 'invoice_number'=>$invoice_number
            //     ];
            //     $this->db->insert('cberp_transactions',$discount_data1);
            //     $this->db->set('lastbal', 'lastbal - ' .$order_discount, FALSE);
            //     $this->db->where('acn', $order_discount_account_number);
            //     $this->db->update('cberp_accounts'); 
            // }

            // erp2024 update customer table available credit limit 10-09-2024
            if($deliverynote_status != 'Invoiced')
            {
                $this->load->model('transactions_model', 'transactions');
                $custdata = $this->transactions->check_customer_account_details($customer_id);
                $custcredit_limit = $custdata['credit_limit'];
                $cust_avalable_credit_limit = (!empty($custdata['avalable_credit_limit'])) ? $custdata['avalable_credit_limit']: 0;

                $subamount = $cust_avalable_credit_limit + $grandsubtotal;
                $this->db->set('avalable_credit_limit', $subamount, FALSE);
                $this->db->where('customer_id', $customer_id);
                $this->db->update('cberp_customers');
            }


            $cost_of_goods_data =  [
                'acid' => $default_cost_of_goods_account,
                'type' => 'Expense',
                'cat' => 'Delivery Return',
                'credit' => $grand_product_cost,
                'eid' => $this->session->userdata('id'),
                'date' => date('Y-m-d'),
                'transaction_number'=>$transaction_number,
            ];
            $this->db->set('lastbal', 'lastbal - ' . $grand_product_cost, FALSE);
            $this->db->where('acn', $default_cost_of_goods_account);
            $this->db->update('cberp_accounts'); 
            $this->db->insert('cberp_transactions', $cost_of_goods_data);

            // Inventory transaction
            $inventory_data =  [
                'acid' => $default_inventory_account,
                'type' => 'Asset',
                'cat' => 'Delivery Return',
                'debit' => $grand_product_cost,
                'eid' => $this->session->userdata('id'),
                'date' => date('Y-m-d'),
                'transaction_number'=>$transaction_number,
            ];
            $this->db->set('lastbal', 'lastbal + ' . $grand_product_cost, FALSE);
            $this->db->where('acn', $default_inventory_account);
            $this->db->update('cberp_accounts'); 
            $this->db->insert('cberp_transactions', $inventory_data);
            // erp2024 update customer table available credit limit 10-09-2024 ends

            if($grand_damaged_product_cost>0)
            {

                $damage_account_data =  [
                    'acid' => $default_damage_account,
                    'type' => 'Expense',
                    'cat' => 'Delivery Return',
                    'debit' => $grand_damaged_product_cost,
                    'eid' => $this->session->userdata('id'),
                    'date' => date('Y-m-d'),
                    'transaction_number'=>$transaction_number,
                ];
                $this->db->set('lastbal', 'lastbal + ' . $grand_product_cost, FALSE);
                $this->db->where('acn', $default_damage_account);
                $this->db->update('cberp_accounts'); 
                $this->db->insert('cberp_transactions', $damage_account_data);

                $damage_inventory_data =  [
                    'acid' => $default_inventory_account,
                    'type' => 'Expense',
                    'cat' => 'Delivery Return',
                    'credit' => $grand_damaged_product_cost,
                    'eid' => $this->session->userdata('id'),
                    'date' => date('Y-m-d'),
                    'transaction_number'=>$transaction_number,
                ];
                $this->db->set('lastbal', 'lastbal - ' . $damage_inventory_data, FALSE);
                $this->db->where('acn', $default_inventory_account);
                $this->db->update('cberp_accounts'); 
                $this->db->insert('cberp_transactions', $damage_inventory_data);

            }

            header('Content-Type: application/json');
            echo json_encode(array('status' => 'Success','data'=>$delivery_return_number));
            
        }
        else{
           echo json_encode(array('status' => 'Error', 'message' =>'Must enter at least one return quantity'));
        }
       

    }


    public function reprint_converted_credit_note()
    {
      
        $delevery_note_id = $this->input->get('delivery', true);
        $salesorder_id = $this->input->get('sales', true);
        $customer_id = $this->input->get('cust', true);
        $data["salesorder_id"] = $salesorder_id;
        $data["delevery_return_id"] = $delevery_note_id;

        $this->db->select('cberp_delivery_returns.created_date');
        $this->db->from('cberp_delivery_returns');
        $this->db->where('cberp_delivery_returns.delivery_return_number', $delevery_note_id);
        $query1 = $this->db->get();
        $res =  $query1->row_array();
        $data["deleveryreturn_createddate"] = $res['created_date'];
        $client = "";
        $data['CustDetails'] = $this->invocies->salesorder_cust($customer_id);
        $data['salesman'] = $this->customers->customer_salesman($customer_id);
        if(!empty($data['CustDetails'])){ 
            $client = '' . $data['CustDetails'][0]['name'] . '<br>' . $data['CustDetails'][0]['address'] . ','. $data['CustDetails'][0]['city'] .' <br>' . $data['CustDetails'][0]['phone'] . '<br>' .$data['CustDetails'][0]['email'] ;
        
            $data["custId"]=$data['CustDetails'][0]['customer_id'];
        }
        $data["client"]=$client;
       
        $loc = location($this->aauth->get_user()->loc);
        $data['companyNanme']=$loc['cname'];
        $company = '' . $loc['address'] . '<br>' . $loc['city'] . ', ' . $loc['region'] . '<br>' . $loc['country'] . ' -  ' . $loc['postbox'] . '<br>' . $this->lang->line('Phone') . ': ' . $loc['phone'] . '<br> ' . $this->lang->line('Email') . ': ' . $loc['email'];
        $data['lang']['company'] = $company;

        // ==================================================================
        $this->db->select('cberp_delivery_returns.customer_id, cberp_delivery_returns.discount, cberp_delivery_returns.subtotal, cberp_delivery_returns.tax, cberp_delivery_returns.total_amount,cberp_delivery_returns.order_discount,  cberp_delivery_return_items.*,cberp_products.product_code,cberp_product_description.product_name,cberp_products.unit AS productunit');
        $this->db->from('cberp_delivery_returns');
        // $this->db->where('cberp_delivery_returns.salesorder_number', $salesorder_id);
        $this->db->join('cberp_delivery_return_items', 'cberp_delivery_return_items.delivery_return_number = cberp_delivery_returns.delivery_return_number');
        
        $this->db->join('cberp_products', 'cberp_products.product_code = cberp_delivery_return_items.product_code');
        $this->db->join('cberp_product_description', 'cberp_products.product_code = cberp_product_description.product_code');
        $this->db->where('cberp_delivery_returns.delivery_return_number', $delevery_note_id);
        $query = $this->db->get();
        $data['products'] = $query->result_array();
        // echo "<pre>"; print_r($data['products']); die();
        $html = $this->load->view('deliveryreturns/creditnotereprintpdf-' . LTR, $data, true);         
        ini_set('memory_limit', '64M');
        $this->load->library('pdf');
        $pdf = $this->pdf->load();
        $pdf->WriteHTML($html);       
        $pdf->Output('reprint-note' . $pay_acc . '.pdf', 'I');       
            
    }
    

}
