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
 *   * Tree Code Hub IT (P) Ltd
 * ***********************************************************************
 */

defined('BASEPATH') or exit('No direct script access allowed');

class Quote extends CI_Controller
{
    private $configurations;
    private $prifix51;
    private $prifix72;
    private $sales_module_group_number;
    private $my_approval_levels;
    private $all_approval_level;
    private $module_number;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('quote_model', 'quote');
        $this->load->model('SalesOrder_model', 'salesorder');
        $this->load->model('authorizationapproval_model', 'authorization_approval');
        $this->load->library("Aauth");
        $this->load->library('session');
        if (!$this->aauth->is_loggedin()) {
            redirect('/user/', 'refresh');
        }
        // if (!$this->aauth->premission(1)) {
        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        // }
        $this->li_a = 'sales';
        $this->session->unset_userdata('orderid');   
        $this->configurations = $this->session->userdata('configurations');
        $this->prifix51 =  get_prefix();
        $this->prifix72 =  get_prefix_72();
        $this->sales_module_group_number =  get_module_details_by_name('Sales');
        $this->module_number =  module_number_name('Quotes');
    }

    //erp2024 26-02-2025 change create() to create_old () & is not used

    public function create()
    {
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);created_employee

        $data['validity'] = default_validity();
        $data['prefix'] = $this->prifix51['quote_prefix'];
        $data['permissions'] = load_permissions('Sales','Quotes','Manage Quotes','View Page');
       
        $this->load->library("Common");
        $data['taxlist'] = $this->common->taxlist($this->config->item('tax'));
        $this->load->model('customers_model', 'customers');
        $this->load->model('plugins_model', 'plugins');
        $data['exchange'] = $this->plugins->universal_api(5);
        $data['currency'] = $this->quote->currencies();
        $data['customergrouplist'] = $this->customers->group_list();        
        $data['terms'] = $this->quote->billingterms();
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['warehouse'] = $this->quote->warehouses();
        $data['taxdetails'] = $this->common->taxdetail();        
        $data['configurations'] = $this->configurations;
        $data['emp'] = $this->plugins->universal_api(69);
        if ($data['emp']['key1']) {
            $this->load->model('employee_model', 'employee');
            $data['employee'] = $this->employee->list_employee();
        }
        
        $quote_number = $this->input->get('id');
        if($quote_number)
        {
            $data['related_salesorders'] = $this->quote->quote_related_salesorders($quote_number);
            if($this->module_number)
            {
                $data['approved_levels'] = function_approved_levels($this->module_number,$quote_number);
                $data['approval_level_users'] =  linked_user_module_approvals_by_module_number($this->sales_module_group_number);   
                $data['my_approval_permissions'] =  linked_user_module_approvals_by_module_number($this->sales_module_group_number,$this->session->userdata('id'));
                $data['module_number'] = $this->module_number;
            }
            
            $data['quote'] = $this->quote->quote_details($quote_number);
            if($data['quote']['created_by'])
            {
                $data['created_employee'] = employee_details_by_id($data['quote']['created_by']);          
            }
            if($data['quote']['sent_by'])
            {
                $data['sent_employee'] = employee_details_by_id($data['quote']['sent_by']);          
            }
            
            $data['assigned_customer']  = get_customer_details_by_id($data['quote']['customer_id']);
            $data['products'] = $this->quote->quote_products($quote_number);
            $data['quote_id'] = $quote_number;
            $data['approvedby'] = $this->quote->approved_person($tid,"Quote");
            $data['assignedperson'] = $this->quote->employee($data['quote']['employee_id']);         
            $data['trackingdata'] = tracking_details('quote_number',$quote_number);
            $head['title'] = "Quote ".$quote_number;
            // $data['log'] = $this->quote->gethistory($quoteid);
            $data['images'] = get_uploaded_images('Quote',$quote_number);
            //erp2024 06-01-2025 detailed history log starts
            $page = $this->module_number;
            $data['detailed_log']= get_detailed_logs($quote_number,$page);
            
            $products = $data['detailed_log'];
            $groupedBySequence = []; // Initialize an empty array for grouping
            $data['colorcode'] = get_color_code($data['quote']['due_date']);
            foreach ($products as $product) {
                $sequence = $product['seqence_number'];
                $groupedBySequence[$sequence][] = $product; // Group by sequence number
            }
            
            $data['groupedDatas'] = $groupedBySequence;
            /////////////////
        }
        else{
            $data['quote'] = [];
            $data['products'] = [];           
            $data['latest_quote_number'] = $this->quote->lastquote();
            $head['title'] = "New Quote";
            $data['colorcode'] ="";
        }
       
        $this->load->view('fixed/header', $head);
        $this->load->view('quotes/create_quote', $data);
        $this->load->view('fixed/footer');
    }
    public function convert_to_quote()
    {
        
        $data['permissions'] = load_permissions('CRM','Leads','Manage Lead','View Page');
        $this->load->model('customer_enquiry_model', 'customer_enquiry');
        $tid = intval($this->input->get('id'));
        $data['leadid'] = $tid;
        $data['enquirymain'] = $this->customer_enquiry->enquiry_details($tid);
        $data['products'] = $this->quote->get_enquiry_items($tid);
        $this->load->model('employee_model', 'employee');
        $data['employee'] = $this->employee->list_employee();
        $this->load->library("Common");
        $data['taxlist'] = $this->common->taxlist($this->config->item('tax'));
        $this->load->model('customers_model', 'customers');
        $this->load->model('plugins_model', 'plugins');
        $data['exchange'] = $this->plugins->universal_api(5);
        $data['currency'] = $this->quote->currencies();
        $data['customergrouplist'] = $this->customers->group_list();
        $data['lastinvoice'] = $this->quote->lastquote();
        $data['terms'] = $this->quote->billingterms();
        $head['title'] = "Convert to Quote";
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['warehouse'] = $this->quote->warehouses();
        $data['taxdetails'] = $this->common->taxdetail();
        $data['configurations'] = $this->configurations;
        $this->load->view('fixed/header', $head);
        $this->load->view('quotes/enquirytoquote', $data);
        $this->load->view('fixed/footer');
    }

    //edit invoice
    public function edit()
    {
        $this->load->model('customers_model', 'customers');
        $data['customergrouplist'] = $this->customers->group_list();
        $this->load->model('employee_model', 'employee');
        $data['employee'] = $this->employee->list_employee();
        $tid = intval($this->input->get('id'));
        $data['id'] = $tid;
        $data['terms'] = $this->quote->billingterms();
        $data['invoice'] = $this->quote->quote_details($tid);
        $data['products'] = $this->quote->quote_products($tid);
        $data['currency'] = $this->quote->currencies();
        $head['title'] = "Edit Quote #" . $data['invoice']['tid'];
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['warehouse'] = $this->quote->warehouses();
        $this->load->model('plugins_model', 'plugins');
        $data['exchange'] = $this->plugins->universal_api(5);
        $this->load->library("Common");
        $data['trackingdata'] = tracking_details('quote_id',$tid);
        $data['taxlist'] = $this->common->taxlist_edit($data['invoice']['tax_status']);        
        $data['configurations'] = $this->configurations;
        $this->load->view('fixed/header', $head);
        $this->load->view('quotes/edit', $data);
        $this->load->view('fixed/footer');
    }

    //invoices list
    public function index()
    {

        $data['permissions'] = load_permissions('Sales','Quotes','Manage Quotes','List');
        $head['title'] = "Manage Quote";
        $data['employee_id'] = intval($this->input->get('employee_id'));
        $head['usernm'] = $this->aauth->get_user()->username;
        // $this->load->model('invoices_model');       
        // $condition = "";
        // $data['counts'] = $this->invoices_model->get_dynamic_count('cberp_quotes','invoicedate','total',$condition);
         $data['ranges'] = getCommonDateRanges();
        $data['counts'] = $this->quote->get_filter_count($data['ranges']);
        $data['employees']  = employee_list();
        $data['customers']  = customer_list();
        $this->load->view('fixed/header', $head);
        $this->load->view('quotes/quotes', $data);
        $this->load->view('fixed/footer');
    }
    public function ajax_list()
    {
        $permissions = load_permissions('Sales','Quotes','Manage Quotes');
        $functions = array_column($permissions, 'function');
        $sales_order_btn = !in_array('Sales Orders', $functions) ? 'd-none' : '';
        $convert_to_so_btn = !in_array('Convert To Sales Order', $functions) ? 'd-none' : '';
        // echo "<pre>"; print_r($functions); die();
        $employee_id = 0;
        // if ($this->aauth->premission(9)) {
        //     $employee_id = $this->input->post('employee_id');
        // } 
        $list = $this->quote->get_datatables($employee_id);
        // print_r($list); die();
        $data = array();
        $no = $this->input->post('start');
        foreach ($list as $invoices) {
            // if($invoices->convert_flag == '1'){
            //     $salesorderstatus = '<span class="st-Closed">' . $this->lang->line(ucwords("Received")) . '</span>';
            //     $salebtn = '<a href="' . base_url("SalesOrders/salesorder_new?id=$invoices->quote_number") . '&token=1" class="btn btn-crud btn-secondary btn-sm '.$sales_order_btn.'"  title="sales order">Sales Order(s)</span></a>';
            // }
            // else if($invoices->convert_flag == '2'){
            //     $salesorderstatus = '<span class="st-partial">' . $this->lang->line(ucwords("Partially Received")) . '</span>';
            //     $salebtn = '<a href="' . base_url("SalesOrders/salesorder_new?id=$invoices->quote_number") . '&token=1" class="btn btn-crud btn-secondary btn-sm '.$sales_order_btn.'"  title="sales order">Sales Order(s)</span></a>';
            // }
            // else{
               
            // }
            $salebtn = '';
            $salesorderstatus ='';
            $convert_to_quote_btn="";
            $approvstatus = '';
            switch ($invoices->status) {
                case 'pending':
                    $status = '<span class="st-pending">Created</span>';  
                    // $status = '<span class="st-pending">' . $this->lang->line(ucwords($invoices->status)) . '</span>';  
                    break;
                case 'rejected':
                    $status = '<span class="st-Closed">' . $this->lang->line(ucwords($invoices->status)) . '</span>';
                    break;
            
                case 'accepted': 
                    $status = '<span class="st-accepted">' . $this->lang->line(ucwords($invoices->status)) . '</span>';
                break;
                case 'customer_approved': 
                    $status = '<span class="st-accepted">' . $this->lang->line(ucwords($invoices->status)) . '</span>';
                break;
                case 'Reverted':
                    $status = '<span class="st-Reverted">' . $invoices->status . '</span>';
                break;
                case 'Sent':
                    $status = '<span class="st-sent">' . $this->lang->line(ucwords($invoices->status)) . '</span>';
                    // if($invoices->convert_flag==0)
                    // {
                        $convert_to_quote_btn = '<a href="' . base_url("quote/create?id=$invoices->quote_number") . '" class="btn btn-secondary btn-sm '.$convert_to_so_btn.'"  title="Convert To Sales Order">Convert To Sales Order</a>';
                        // $convert_to_quote_btn = '<button type="button" class="btn btn-secondary btn-sm '.$convert_to_so_btn.'"  title="Convert To Sales Order" onclick="convertToSalesOrderDirect('.$invoices->quote_number.')">Convert To Sales Order</button>';
                        // $convert_to_quote_btn = '<button type="button" class="btn btn-secondary btn-sm '.$convert_to_so_btn.'"  title="Convert To Sales Order" onclick="convertToSalesOrderDirect('.$invoices->quote_number.')">Convert To Sales Order</button>';
                    // }
                    
                break;
                case 'Assigned':
                    $status = '<span class="st-Reverted">' . $this->lang->line(ucwords($invoices->status)) . '</span>';
                break;
                case 'draft':   
                    $status = '<span class="st-draft">' . $this->lang->line(ucwords($invoices->status)) . '</span>';
                break;
                default:
                    $status = '<span class="st-pending">Created</span>';  
                    // $status = '<span class="st-accepted">' . $this->lang->line(ucwords($invoices->status)) . '</span>';
                    break;
            }

            
            // if($invoices->approval_flag=='1'){
            //     $approvstatus = '<span class="st-accepted">' . $this->lang->line('Approved') . '</span>';
            // }
            // else if($invoices->approval_flag=='2'){
            //     $approvstatus = '<span class="st-pending">' . $this->lang->line('Hold') . '</span>';
            // }
            // else if($invoices->approval_flag=='3'){
            //     $approvstatus = '<span class="st-Closed">' . $this->lang->line(ucwords('Reject')) . '</span>';
            // }
            // else if($invoices->prepared_flag=='0' && $invoices->approval_flag=='0'){
            //     $approvstatus = '';
            // }
            // else{
            //     // $approvstatus = '<span class="st-Closed">' . $this->lang->line('cREATED') . '</span>';
            // }
            
            $targeturl = '<a href="' . base_url("quote/create?id=$invoices->quote_number") . '">' . $invoices->quote_number . '</a>';
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $targeturl;
            $row[] = $invoices->customerid." - ".$invoices->name;
            $row[] = (!empty($invoices->quote_date)) ? dateformat($invoices->quote_date) :"";

            $colorcode = ($invoices->due_date) ? get_color_code($invoices->due_date) :"";
            $dudate = (!empty($invoices->due_date))?dateformat($invoices->due_date):"";
            $row[] = '<b style="color:'.$colorcode.'">'.$dudate.'</b>';

            $row[] = $invoices->total;
            // $row[] = $invoices->refer;
            // $row[] = $invoices->customer_reference_number;
            $row[] = "";
            // $row[] = ($invoices->approved_date) ? $approvstatus."<br>".$invoices->employeename."<br>".$approveddt : "";
            // $row[] = $approveddt;
            // $row[] = $approvstatus;
            $row[] = $status;
            $row[] = $salesorderstatus;
            $btns = '<a href="' . base_url("billing/printquote?id=$invoices->quote_number") . '&token=1" class="btn btn-secondary btn-sm"  title="Print" target="_blank"><span class="fa fa-print"></span></a> '.$salebtn;
            $row[] = $btns." ". $convert_to_quote_btn;
            // $row[] = '<a href="' . base_url("quote/view?id=$invoices->id") . '" class="btn btn-secondary btn-sm" target="_blank" title="View"><i class="fa fa-eye"></i></a> <a href="' . base_url("billing/printquote?id=$invoices->id") . '&token=1" class="btn btn-secondary btn-sm"  title="Print" target="_blank"><span class="fa fa-print"></span></a> <a href="#" data-object-id="' . $invoices->id . '" class="btn btn-secondary btn-sm delete-object" title="Delete"><span class="fa fa-trash"></span></a>';
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->quote->count_all($employee_id),
            "recordsFiltered" => $this->quote->count_filtered($employee_id),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);

    }
    //action leadid  s_warehouses employee_id
    public function action()
    {
      
        $currency = $this->input->post('mcurrency');
        $customer_id = $this->input->post('customer_id');
        $invocieno = $this->input->post('invocieno');
        $quote_number = $this->input->post('quote_number');
        $tid = $this->input->post('invocieno');
        $invoicedate = $this->input->post('invoicedate');
        $invocieduedate = $this->input->post('invocieduedate');
        $notes = $this->input->post('notes', true);
        $tax = $this->input->post('tax_handle');
        $subtotal = rev_amountExchange_s($this->input->post('total'), $currency, $this->aauth->get_user()->loc);
        $shipping = rev_amountExchange_s($this->input->post('shipping'), $currency, $this->aauth->get_user()->loc);
        $shipping_tax = rev_amountExchange_s($this->input->post('ship_tax'), $currency, $this->aauth->get_user()->loc);
        $ship_taxtype = $this->input->post('ship_taxtype');        

        if ($ship_taxtype == 'incl') $shipping = $shipping - $shipping_tax;
        $refer = $this->input->post('reference');
        $total = rev_amountExchange_s($this->input->post('total'), $currency, $this->aauth->get_user()->loc);
        
        $proposal = $this->input->post('propos');
        $total_tax = 0;
        $total_discount = 0;
        // $discountFormat = $this->input->post('discountFormat');
        $discountFormat = 0;
        $pterms = $this->input->post('pterms');
        $quote_id="";
        // $this->load->model('plugins_model', 'plugins'); employee
        // $empl_e = $this->plugins->universal_api(69);
        // if ($empl_e['key1']) {
        $emp = 0;
        // } else {
        //     $emp = $this->aauth->get_user()->id;
        // }

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
        if (empty($this->input->post('pid'))) {
            echo json_encode(array('status' => 'Error', 'message' =>
                $this->lang->line('Please add atleast one product')));
            exit;
        }
        $this->db->trans_start();
        //products product_description
        $transok = true;
        //Invoice Data employee status
        $bill_date = datefordatabase($invoicedate);
        $bill_due_date = datefordatabase($invocieduedate);

        $data = array('quote_number' => $quote_number, 'quote_date' => $bill_date, 'due_date' => $bill_due_date, 'subtotal' => $subtotal, 'shipping' => $shipping, 'shipping_tax' => $shipping_tax, 'shipping_tax_type' => $ship_taxtype, 'discount' => $total_discount, 'tax' => $total_tax, 'total' => $total, 'notes' => $notes, 'customer_id' => $customer_id, 'employee_id' => $emp, 'tax_status' => $tax, 'discount_status' => $discount_status, 'format_discount' => $discountFormat, 'reference' => $refer, 'payment_term' => $pterms, 'customer_message' => $proposal, 'multi' => $currency, 'loc' => $this->aauth->get_user()->loc,'customer_reference_number' => $this->input->post('customer_reference_number'),'customer_contact_person' => $this->input->post('customer_contact_person'),'customer_contact_number' => $this->input->post('customer_contact_number'),'customer_contact_email' => $this->input->post('customer_contact_email'));
        $data['status'] = "pending";

        if($this->input->post('employee')) 
        {
            $data['employee_id'] = $this->input->post('employee');
            $data['status'] = 'Assigned';
            // $data['approved_by'] = $this->session->userdata('id');
            // $data['approved_date'] = date('Y-m-d H:i:s');
            // $data["approval_flag"] = 1;  
        }
        else{
            $data['employee_id'] = NULL;
            $data['status'] = 'pending';
            // $data['approved_by'] = NULL;
            // $data['approved_date'] = NULL;
            // $data["approval_flag"] = 0;  
        }

        // echo "<pre>"; print_r($data); die();
        //erp2024 find amout limit 17-07-2024 ///////////////////////////////////////
        $amount_limit = $this->quote->amount_limit($this->session->userdata('id'));
        $history =[];
        // if($amount_limit>=$total){
        //     $data["approvalflg"] = 1;
        //     $history['authorized_by'] = $this->session->userdata('id');
        //     $history['authorized_date'] = date("Y-m-d");
        //     $history['authorized_amount'] = $total;
        //     $history['authorized_type'] = "Own";
        //     $history['status'] = "Approve";
        // }
        //////////////////////////////////////////////////////////////
        
    
        // if(empty($this->input->post('leadid'))){
        //     $data['status'] = "pending";
        //     $data["approvalflg"] = 0;
        // }




        $data['lead_number'] = ($this->input->post('lead_number')) ? $this->input->post('lead_number') : "";
        if($data['lead_id']){
          
            // master_table_log('customer_general_enquiry_log',$data['lead_id'],'Lead Converted');
            //erp2024 06-01-2025 detailed history log starts
            detailed_log_history('Lead',$data['lead_id'],'Lead Converted to Quote', $changedFields);
            //erp2024 06-01-2025 detailed history log ends 
           
        }
        $qtid = $this->quote->check_quote_existornot($quote_number);
        $authid = $this->quote->check_approval_existornot($quote_number);
      
        if($qtid != 0)
        {       
                //employee_id
                $data['updated_by'] = $this->session->userdata('id');
                $data['updated_date'] = date('Y-m-d H:i:s');
                $quote_id = $this->input->post('quote_id');
                $data['prepared_by'] = $this->session->userdata('id');
                $data['prepared_date'] = date('Y-m-d H:i:s');
                $data['prepared_flag'] = '1';
                // log_table_data('cberp_quotes','cberp_quotes_log', 'id' ,'quote_id','Update',$quote_id);
                //erp2024 06-01-2025 detailed history log starts
                detailed_log_history('Quote',$quote_id,'Quote Updated', $_POST['changedFields']);
                //erp2024 06-01-2025 detailed history log ends 
                $this->db->update('cberp_quotes', $data,['quote_number'=>$quote_number]);
                
                $this->db->delete('cberp_quotes_items', ['quote_number'=>$quote_number]);
                // file upload section starts 22-01-2025
                if($_FILES['upfile'])
                {
                    upload_files($_FILES['upfile'], 'Quote',$quote_number);
                }
                // file upload section ends 22-01-2025
                // die($this->db->last_query());
                $pid = $this->input->post('pid');
                // $invocieno = $this->db->insert_id();
    
                //erp2024 insert to authorization history table /////////////////////////////////////
               
                $history['function_type'] = 'Quote';
                $history['function_id'] = $quote_id;
                $history['requested_by'] = $this->session->userdata('id');
                $history['requested_date'] = date("Y-m-d");
                $history['requested_amount'] = $total;
                
                if($authid>0){
                    $this->db->update('authorization_history',$history,['function_id'=>$quote_id]);
                }
                else{
                    $this->db->insert('authorization_history',$history);
                }
                ////////////////////////////////////////////////////////////////////////////////////////
                
                //insert to tracking table
                if($this->input->post('leadid'))
                {
                                  
                    $comments = "Lead converted to quote. Quote : #".($invocieno);
                    $this->db->where('lead_id', $this->input->post('leadid'));
                    $this->db->update('cberp_customer_leads',['enquiry_status'=>'Closed','comments'=>$comments]);  
                    insertion_to_tracking_table_sales_to_invoice('quote_number',$quote_number, 'lead_id', $this->input->post('leadid'));
                }
                else{
                    // $this->db->insert('cberp_transaction_tracking',['quote_id'=>$quote_id,'quote_number'=>$quote_number]);
                    insertion_to_tracking_table_sales_to_invoice('quote_number',$quote_number);    
                }
                
    
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
                foreach ($product_name1 as $key => $value) {
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
                            'quote_number' => $quote_number,
                            'product_code' => $code[$key],
                            'quantity' => numberClean($product_qty[$key]),
                            'price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
                            'tax' => $product_tax,
                            'discount' => $discountamount,
                            'total_amount' => rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc),
                            'total_tax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                            'total_discount' => rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc),
                            'discount_type' => $discount_type[$key]
                        );
                        
                        $flag = true;
                        $productlist[$prodindex] = $data;
                    }   
                    $i++;
                    $prodindex++;
                    $amt = numberClean($product_qty[$key]);
                    $itc += $amt;
                }
                if ($prodindex > 0) {
                    $this->db->insert_batch('cberp_quotes_items', $productlist);
                    $this->db->set(array('discount' => rev_amountExchange_s(amountFormat_general($total_discount), $currency, $this->aauth->get_user()->loc), 'tax' => rev_amountExchange_s(amountFormat_general($total_tax), $currency, $this->aauth->get_user()->loc)));
                    $this->db->where('quote_number', $quote_number);
                    $this->db->update('cberp_quotes');
                } else {
                    echo json_encode(array('status' => 'Error', 'message' =>
                        "Please choose product from product list. Go to Item manager section if you have not added the products."));
                    $transok = false;
                }
    
                echo json_encode(array('status' => 'Success', 'message' =>
                    $this->lang->line('Quote has  been created'), 'quote'=>$invocieno));
              
        }
        else{
            $data['created_by']   = $this->session->userdata('id');
            $data['created_date']   = date('Y-m-d H:i:s');
            $data['prepared_by']  = $this->session->userdata('id');
            $data['prepared_date']  = date('Y-m-d H:i:s');
            $data['prepared_flag'] = '1';
            $data['employee_id'] = $this->session->userdata('id');
            // $this->db->insert('cberp_quotes', $data); die($this->db->last_query());
            if ($this->db->insert('cberp_quotes', $data)) {
                $quote_id = $this->db->insert_id();
                //erp2024 06-01-2025 detailed history log starts
                detailed_log_history('Quote',$quote_number,'Created', '');
                //erp2024 06-01-2025 detailed history log ends 

                    // file upload section starts 22-01-2025
                    if($_FILES['upfile'])
                    {
                        upload_files($_FILES['upfile'], 'Quote',$quote_number);
                    }
                    // file upload section ends 22-01-2025
    
                //erp2024 insert to authorization history table /////////////////////////////////////
                $history['function_type'] = 'Quote';
                $history['function_id'] = $quote_number;
                $history['requested_by'] = $this->session->userdata('id');
                $history['requested_date'] = date("Y-m-d");
                $history['requested_amount'] = $total;
                if($authid>0){
                    $this->db->update('authorization_history',$history,['function_id'=>$quote_number]);
                }
                else{
                    $this->db->insert('authorization_history',$history);
                }
                ////////////////////////////////////////////////////////////////////////////////////////
                
                //insert to tracking table
                if($this->input->post('leadid'))
                {
                    $comments = "Lead converted to quote. Quote : #".($invocieno);
                    $this->db->where('lead_id', $this->input->post('leadid'));
                    $this->db->update('cberp_customer_leads',['enquiry_status'=>'Closed','comments'=>$comments]);
                    
                    // $this->db->where('lead_id', $this->input->post('leadid'));
                    // $this->db->update('cberp_transaction_tracking',['quote_id'=>$quote_id,'quote_number'=>$quote_number]);
                    insertion_to_tracking_table_sales_to_invoice('quote_number',$quote_number, 'lead_id', $this->input->post('leadid'));
                }
                else{
                    $this->db->insert('cberp_transaction_tracking',['quote_number'=>$quote_number]);
                }
                
    
                $productlist = array();
                $prodindex = 0;
                $itc = 0;
                $flag = false;
                $product_id = $this->input->post('pid');
                $pid = $this->input->post('pid');
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
                foreach ($pid as $key => $value) {
                    if(!empty($product_name1[$key]) && !empty($product_name1[$key]) && $product_qty[$key]>0)
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
                            'quote_number' => $quote_number,
                            'product_code' => $code[$key],
                            'quantity' => numberClean($product_qty[$key]),
                            'price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
                            'tax' => $product_tax,
                            'discount' => $discountamount,
                            'total_amount' => rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc),
                            'total_tax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                            'total_discount' => rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc),                           
                            'discount_type' => $discount_type[$key],
                        );
                        
                        $flag = true;
                        $productlist[$prodindex] = $data;
                    }   
                    $i++;
                    $prodindex++;
                    $amt = numberClean($product_qty[$key]);
                    $itc += $amt;
                }
                if ($prodindex > 0) {
                    $this->db->insert_batch('cberp_quotes_items', $productlist);
                    // log_table_items_data('cberp_quotes_items','cberp_quotes_items_log', 'id' ,'quote_item_id','Create','tid',$invocieno);
                    $this->db->set(array('discount' => rev_amountExchange_s(amountFormat_general($total_discount), $currency, $this->aauth->get_user()->loc), 'tax' => rev_amountExchange_s(amountFormat_general($total_tax), $currency, $this->aauth->get_user()->loc)));
                    $this->db->where('quote_number', $quote_number);
                    $this->db->update('cberp_quotes');
                } else {
                    echo json_encode(array('status' => 'Error', 'message' =>
                        "Please choose product from product list. Go to Item manager section if you have not added the products."));
                    $transok = false;
                }
    
                echo json_encode(array('status' => 'Success', 'message' =>
                    $this->lang->line('Quote has  been created'), 'quote'=>$invocieno));
                // echo json_encode(array('status' => 'Success', 'message' =>
                //     $this->lang->line('Quote has  been created') . " <a href='view?id=$invocieno' class='btn btn-info btn-sm'><span class='fa fa-eye' aria-hidden='true'></span> View </a> &nbsp; &nbsp;<a href='create' class='btn btn-secondary btn-sm'><span class='fa fa-plus-circle' aria-hidden='true'></span> " . $this->lang->line('Create') . "  </a>"));
            } else {
                echo json_encode(array('status' => 'Error', 'message' =>
                    $this->lang->line('ERROR')));
                $transok = false;
            }
        }

        if ($transok) {
            $this->db->trans_complete();
        } else {
            $this->db->trans_rollback();
        }
    }
    public function draftaction()
    {
   

        $currency = $this->input->post('mcurrency');
        $customer_id = $this->input->post('customer_id');
        $invocieno = $this->input->post('invocieno');
        $quote_number = $this->input->post('quote_number');
        $tid = $this->input->post('invocieno');
        
        $invoicedate = $this->input->post('invoicedate');
        $invocieduedate = $this->input->post('invocieduedate');
        $notes = $this->input->post('notes', true);
        $tax = $this->input->post('tax_handle');
        $subtotal = rev_amountExchange_s($this->input->post('subtotal'), $currency, $this->aauth->get_user()->loc);
        $shipping = rev_amountExchange_s($this->input->post('shipping'), $currency, $this->aauth->get_user()->loc);
        $shipping_tax = rev_amountExchange_s($this->input->post('ship_tax'), $currency, $this->aauth->get_user()->loc);
        $ship_taxtype = $this->input->post('ship_taxtype');        

        if ($ship_taxtype == 'incl') $shipping = $shipping - $shipping_tax;
        $refer = $this->input->post('reference');
        $total = rev_amountExchange_s($this->input->post('total'), $currency, $this->aauth->get_user()->loc);
        
        $proposal = $this->input->post('propos');
        $total_tax = 0;
        $total_discount = 0;
        // $discountFormat = $this->input->post('discountFormat');
        $discountFormat = 0;
        $pterms = $this->input->post('pterms');

        // $this->load->model('plugins_model', 'plugins');
        // $empl_e = $this->plugins->universal_api(69);
        // if ($empl_e['key1']) {
        $emp = $this->input->post('employee');
        // $emp = (!empty($this->input->post('employee'))) ? $this->input->post('employee'): $this->aauth->get_user()->id;
        // } else {
        //     $emp = $this->aauth->get_user()->id;
        // }

        $i = 0;
        if ($discountFormat == '0') {
            $discount_status = 0;
        } else {
            $discount_status = 1;
        }

        $this->db->trans_start();
        //products product_description
        $transok = true;
        //Invoice Data employee
        $bill_date = datefordatabase($invoicedate);
        $bill_due_date = datefordatabase($invocieduedate);
        $data = array('quote_number' => $quote_number, 'quote_date' => $bill_date, 'due_date' => $bill_due_date, 'subtotal' => $subtotal, 'shipping' => $shipping, 'shipping_tax' => $shipping_tax, 'shipping_tax_type' => $ship_taxtype, 'discount' => $total_discount, 'tax' => $total_tax, 'total' => $total, 'notes' => $notes, 'customer_id' => $customer_id, 'employee_id' => $emp, 'tax_status' => $tax, 'discount_status' => $discount_status, 'format_discount' => $discountFormat, 'reference' => $refer, 'payment_term' => $pterms, 'customer_message' => $proposal, 'multi' => $currency, 'loc' => $this->aauth->get_user()->loc,'status'=>'draft','customer_reference_number' => $this->input->post('customer_reference_number'),'customer_contact_person' => $this->input->post('customer_contact_person'),'customer_contact_number' => $this->input->post('customer_contact_number'),'customer_contact_email' => $this->input->post('customer_contact_email'));

        //erp2024 find amout limit 17-07-2024 ///////////////////////////////////////
        $amount_limit = $this->quote->amount_limit($this->session->userdata('id'));
        $history =[];
        // if($amount_limit>=$total){
        //     $data["approvalflg"] = 1;
        //     $history['authorized_by'] = $this->session->userdata('id');
        //     $history['authorized_date'] = date("Y-m-d");
        //     $history['authorized_amount'] = $total;
        //     $history['authorized_type'] = "Own";
        //     $history['status'] = "Approve";
        // }
        //////////////////////////////////////////////////////////////

        $data['lead_number'] = (!empty($this->input->post('lead_number'))) ? $this->input->post('lead_number') : "";
        $qtid = $this->quote->check_quote_existornot($quote_number);
        if($qtid != 0)
        {
                $data['updated_by']   = $this->session->userdata('id');
                $data['updated_date']   = date('Y-m-d H:i:s');
                $this->db->update('cberp_quotes', $data,['quote_number'=>$quote_number]);
                // file upload section starts 22-01-2025
                if($_FILES['upfile'])
                {
                    upload_files($_FILES['upfile'], 'Quote',$quote_number);
                }
                // file upload section ends 22-01-2025
                $pid = $this->input->post('pid');
                $invocieno = $qtid;
                // $invocieno = $this->session->userdata('draftquote_id');
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
                $this->db->delete('cberp_quotes_items', array('quote_number' => $quote_number));
                
                    
                foreach ($pid as $key => $value) {
                    if(!empty($product_id[$key]) && !empty($product_name1[$key]))
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
                            'quote_number' => $quote_number,
                            'product_code' => $code[$key],
                            'quantity' => numberClean($product_qty[$key]),
                            'price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
                            'tax' => $product_tax,
                            'discount' => $discountamount,
                            'total_amount' => rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc),
                            'total_tax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                            'total_discount' => rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc),
                            'discount_type' => $discount_type[$key]
                        );
                        
                        $flag = true;
                        $productlist[$prodindex] = $data;
                        $existornot = $this->quote->check_product_existornot($quote_number,$code[$key]);
                        if($existornot==1)
                        {
                            $this->db->update('cberp_quotes_items', $data, ['quote_number'=>$quote_number, 'product_code'=>$code[$key]]);
                        }
                        else{
                            $this->db->insert('cberp_quotes_items', $data);
                        }
                    
                    }   
                    $i++;
                    $prodindex++;
                    $amt = numberClean($product_qty[$key]);
                    $itc += $amt;
                }
              

            
        }
        else{
            $data['created_by']  = $this->session->userdata('id');
            $data['created_date']   = date('Y-m-d H:i:s');
            if ($this->db->insert('cberp_quotes', $data)) {
                $pid = $this->input->post('pid');
                $invocieno = $this->db->insert_id();
                  // file upload section starts 22-01-2025
                  if($_FILES['upfile'])
                  {
                      upload_files($_FILES['upfile'], 'Quote',$quote_number);
                  }
                  // file upload section ends 22-01-2025
                $this->session->set_userdata('draftquote_id', $invocieno);
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

                foreach ($product_name1 as $key => $value) {
                    if(!empty($product_id[$key]) && !empty($product_name1[$key]))
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
                            'quote_number' => $quote_number,
                            'product_code' => $code[$key],
                            'quantity' => numberClean($product_qty[$key]),
                            'price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
                            'tax' => $product_tax,
                            'discount' => $discountamount,
                            'total_amount' => rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc),
                            'total_tax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                            'total_discount' => rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc),
                            'discount_type' => $discount_type[$key]
                        );
                        
                        $flag = true;
                        $productlist[$prodindex] = $data;
                    }   
                    $i++;
                    $prodindex++;
                    $amt = numberClean($product_qty[$key]);
                    $itc += $amt;
                }
                if ($prodindex > 0) {
                    $this->db->insert_batch('cberp_quotes_items', $productlist);
                    $this->db->set(array('discount' => rev_amountExchange_s(amountFormat_general($total_discount), $currency, $this->aauth->get_user()->loc), 'tax' => rev_amountExchange_s(amountFormat_general($total_tax), $currency, $this->aauth->get_user()->loc)));
                    $this->db->where('quote_number', $quote_number);
                    $this->db->update('cberp_quotes');
                } 
               

               
            } 
            
        }       
        header('Content-Type: application/json');
        $response = array(
            'status' => 'Success',
            'quote' => $quote_number
        );
        echo json_encode($response); 
        //erp2024 06-01-2025 detailed history log starts
        detailed_log_history('Quote',$quote_number,'Data Saved As Draft', $changedFields);
        //erp2024 06-01-2025 detailed history log ends  insertion_to_tracking_table
        if ($transok) {
            $this->db->trans_complete();
        } else {
            $this->db->trans_rollback();
        }
    }

    
    public function quote_approval_action()
    {
        $currency = $this->input->post('mcurrency');
        $customer_id = $this->input->post('customer_id');
        $invocieno = $this->input->post('invocieno');
        $invocieno_n = $this->input->post('invocieno');
        $tid = $this->input->post('invocieno');
        $invoicedate = $this->input->post('invoicedate');
        $invocieduedate = $this->input->post('invocieduedate');
        $notes = $this->input->post('notes', true);
        $tax = $this->input->post('tax_handle');
        $subtotal = rev_amountExchange_s($this->input->post('total'), $currency, $this->aauth->get_user()->loc);
        $shipping = rev_amountExchange_s($this->input->post('shipping'), $currency, $this->aauth->get_user()->loc);
        $shipping_tax = rev_amountExchange_s($this->input->post('ship_tax'), $currency, $this->aauth->get_user()->loc);
        $ship_taxtype = $this->input->post('ship_taxtype');        

        if ($ship_taxtype == 'incl') $shipping = $shipping - $shipping_tax;
        $refer = $this->input->post('reference');
        $total = rev_amountExchange_s($this->input->post('total'), $currency, $this->aauth->get_user()->loc);
        
        $proposal = $this->input->post('propos');
        $total_tax = 0;
        $total_discount = 0;
        // $discountFormat = $this->input->post('discountFormat');ship_taxtype
        $discountFormat = 0;
        $pterms = $this->input->post('pterms');
        $emp = $this->input->post('employee');
        $i = 0;
        if ($discountFormat == '0') {
            $discount_status = 0;
        } else {
            $discount_status = 1;
        }

        if ($customer_id == 0) {
            echo json_encode(array('status' => 'Error', 'message' =>
                $this->lang->line('Please add a  client')));
            exit;
        }
        if (empty($this->input->post('pid'))) {
            echo json_encode(array('status' => 'Error', 'message' =>
                $this->lang->line('Please add atleast one product')));
            exit;
        }
        $this->db->trans_start();
        //products product_description
        $transok = true;
        //Invoice Data employee
        $bill_date = datefordatabase($invoicedate);
        $bill_due_date = datefordatabase($invocieduedate);
        $data = array( 'invoicedate' => $bill_date, 'due_date' => $bill_due_date, 'subtotal' => $subtotal, 'shipping' => $shipping, 'ship_tax' => $shipping_tax, 'ship_tax_type' => $ship_taxtype, 'discount' => $total_discount, 'tax' => $total_tax, 'total' => $total, 'notes' => $notes, 'customer_id' => $customer_id, 'employee_id' => $emp, 'tax_status' => $tax, 'discount_status' => $discount_status, 'format_discount' => $discountFormat, 'refer' => $refer, 'term' => $pterms, 'customer_message' => $proposal, 'multi' => $currency, 'loc' => $this->aauth->get_user()->loc,'approved_by'=>$this->session->userdata('id'),'approved_date'=>date('Y-m-d H:i:s'),'customer_reference_number' => $this->input->post('customer_reference_number'),'customer_contact_person' => $this->input->post('customer_contact_person'),'customer_contact_number' => $this->input->post('customer_contact_number'),'customer_contact_email' => $this->input->post('customer_contact_email'));

        //erp2024 find amout limit 17-07-2024 ///////////////////////////////////////
        $amount_limit = $this->quote->amount_limit($this->session->userdata('id'));
        
        //////////////////////////////////////////////////////////////
        $data['status'] = "Assigned";
        $data["approvalflg"] = '1';     
        $data['updated_by']   = $this->session->userdata('id');
        $data['updated_date']   = date('Y-m-d H:i:s');
        $quote_id = $this->input->post('quote_id');
        $this->db->update('cberp_quotes', $data,['quote_number'=>$quote_number]);
        $pid = $this->input->post('pid');
        // file upload section starts 22-01-2025
        if($_FILES['upfile'])
        {
            upload_files($_FILES['upfile'], 'Quote',$quote_number);
        }
        // file upload section ends 22-01-2025
        
        $authdata = [
            'authorized_amount' => $subtotal,
            'status' => 'Approve',
            // 'comments' => $this->input->post('comments'),
            'authorized_date' => date("Y-m-d H:i:s"),
            'authorized_by' => $this->session->userdata('id'),
            'authorized_type' => 'Reported Person',
        ];
    
        $this->db->where('function_id',$this->input->post('iid'));
        $this->db->where('function_type','Quote');
        $this->db->update('authorization_history', $authdata);

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
        $product_hsn = $this->input->post('hsn');
        $product_unit = $this->input->post('unit');
        $discount_type = $this->input->post('discount_type');
        $lowest_price = $this->input->post('lowest_price');
        $maximum_discount_rate = $this->input->post('maxdiscountrate');
        $product_amt = $this->input->post('product_amt');
        $product_tax =0;
        foreach ($pid as $key => $value) {
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
                    'tid' => $quote_id,
                    'pid' => $product_id[$key],
                    'product' => $product_name1[$key],
                    'product_code' => $code[$key],
                    'quantity' => numberClean($product_qty[$key]),
                    'price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
                    'tax' => $product_tax,
                    'discount' => $discountamount,
                    'subtotal' => rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc),
                    'total_tax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                    'total_discount' => rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc),
                    'discount_type' => $discount_type[$key],
                    'lowest_price' => $lowest_price[$key],
                    'maximum_discount_rate' => $maximum_discount_rate[$key],
                );
                
                $flag = true;
                $productlist[$prodindex] = $data;
            }   
            $i++;
            $prodindex++;
            $amt = numberClean($product_qty[$key]);
            $itc += $amt;
            $existornot = $this->quote->check_product_existornot($quote_id,$product_id[$key]);
            if($existornot==1)
            {
                $this->db->update('cberp_quotes_items', $data, ['tid'=>$quote_id, 'pid'=>$product_id[$key]]);
            }
            else{
                $this->db->insert('cberp_quotes_items', $data);
            }
        }
        if ($prodindex > 0) {
            //erp2024 06-01-2025 detailed history log starts
            detailed_log_history('Quote',$quote_id,'Quotaion Approved', $_POST['changedFields']);
            //erp2024 06-01-2025 detailed history log ends 
            $this->db->set(array('discount' => rev_amountExchange_s(amountFormat_general($total_discount), $currency, $this->aauth->get_user()->loc), 'tax' => rev_amountExchange_s(amountFormat_general($total_tax), $currency, $this->aauth->get_user()->loc), 'items' => $itc));
            $this->db->where('id', $quote_id);
            $this->db->update('cberp_quotes');
        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
                "Please choose product from product list. Go to Item manager section if you have not added the products."));
            $transok = false;
        }

        echo json_encode(array('status' => 'Success', 'message' => $this->lang->line('Quote has  been created'), 'quote'=>$invocieno));
 

        if ($transok) {
            $this->db->trans_complete();
        } else {
            $this->db->trans_rollback();
        }
    }


    // erp2024 15-10-2024 quote direct send starts
    
    public function quote_send_by_admin_action()
    {
        //cberp_transaction_tracking customer_leads
        $currency = $this->input->post('mcurrency');
        $customer_id = $this->input->post('customer_id');
        $invocieno = $this->input->post('invocieno');
        $invocieno_n = $this->input->post('invocieno');
        $tid = $this->input->post('invocieno');
        $quote_id = $this->input->post('iid');
        $invoicedate = $this->input->post('invoicedate');
        $invocieduedate = $this->input->post('invocieduedate');
        $notes = $this->input->post('notes', true);
        $tax = $this->input->post('tax_handle');
        $subtotal = rev_amountExchange_s($this->input->post('total'), $currency, $this->aauth->get_user()->loc);
        $shipping = rev_amountExchange_s($this->input->post('shipping'), $currency, $this->aauth->get_user()->loc);
        $shipping_tax = rev_amountExchange_s($this->input->post('ship_tax'), $currency, $this->aauth->get_user()->loc);
        $ship_taxtype = $this->input->post('ship_taxtype');           
        $quote_number = $this->input->post('quote_number');     

        if ($ship_taxtype == 'incl') $shipping = $shipping - $shipping_tax;
        $refer = $this->input->post('reference');
        $total = rev_amountExchange_s($this->input->post('total'), $currency, $this->aauth->get_user()->loc);
        
        $proposal = $this->input->post('propos');
        $total_tax = 0;
        $total_discount = 0;
        // $discountFormat = $this->input->post('discountFormat');ship_taxtype
        $discountFormat = 0;
        $pterms = $this->input->post('pterms');
        $emp = $this->input->post('employee');
        $i = 0;
        if ($discountFormat == '0') {
            $discount_status = 0;
        } else {
            $discount_status = 1;
        }

        if ($customer_id == 0) {
            echo json_encode(array('status' => 'Error', 'message' =>
                $this->lang->line('Please add a  client')));
            exit;
        }
        if (empty($this->input->post('pid'))) {
            echo json_encode(array('status' => 'Error', 'message' =>
                $this->lang->line('Please add atleast one product')));
            exit;
        }
        $this->db->trans_start();
        //products product_description
        $transok = true;
        //Invoice Data employee
        $bill_date = datefordatabase($invoicedate);
        $bill_due_date = datefordatabase($invocieduedate);
        
        $data = array('quote_number' => $quote_number, 'quote_date' => $bill_date, 'due_date' => $bill_due_date, 'subtotal' => $subtotal, 'shipping' => $shipping, 'shipping_tax' => $shipping_tax, 'shipping_tax_type' => $ship_taxtype, 'discount' => $total_discount, 'tax' => $total_tax, 'total' => $total, 'notes' => $notes, 'customer_id' => $customer_id, 'employee_id' => $this->session->userdata('id'), 'tax_status' => $tax, 'discount_status' => $discount_status, 'format_discount' => $discountFormat, 'reference' => $refer, 'payment_term' => $pterms, 'customer_message' => $proposal, 'multi' => $currency, 'loc' => $this->aauth->get_user()->loc,'customer_reference_number' => $this->input->post('customer_reference_number'),'customer_contact_person' => $this->input->post('customer_contact_person'),'customer_contact_number' => $this->input->post('customer_contact_number'),'customer_contact_email' => $this->input->post('customer_contact_email'));

        //erp2024 find amout limit 17-07-2024 ///////////////////////////////////////
        $amount_limit = $this->quote->amount_limit($this->session->userdata('id'));
        
        //////////////////////////////////////////////////////////////
        $data['status'] = "Sent";
        // $data["approval_flag"] = '1';    
        $data['updated_by']   = $this->session->userdata('id');
        $data['updated_date']   = date('Y-m-d H:i:s');
        $data['sent_by']   = $this->session->userdata('id');
        $data['sent_date']   = date('Y-m-d H:i:s');
        $quote_id = $this->input->post('quote_id'); 
        $qtid = $this->quote->check_quote_existornot_by_id($quote_number);
       
        if($qtid != 0)
        {
            $this->db->update('cberp_quotes', $data,['quote_number'=>$quote_number]);
           
        }
        else{
            $data['prepared_by']  = $this->session->userdata('id');
            $data['prepared_date']  = date('Y-m-d H:i:s');
            $data['prepared_flag'] = '1';
            $data['created_by'] = $this->session->userdata('id');
            $data['created_date'] = date('Y-m-d H:i:s');
            $data['quote_number'] = $quote_number;
            $this->db->insert('cberp_quotes', $data);
            insertion_to_tracking_table('quote_number', $quote_number);

        }


        $pid = $this->input->post('pid');
        // file upload section starts 22-01-2025
        if($_FILES['upfile'])
        {
                upload_files($_FILES['upfile'], 'Quote',$quote_number);
        }
        // file upload section ends 22-01-2025
        $authdata = [
            'authorized_amount' => $subtotal,
            'status' => 'Approve',
            // 'comments' => $this->input->post('comments'),
            'authorized_date' => date("Y-m-d H:i:s"),
            'authorized_by' => $this->session->userdata('id'),
            'authorized_type' => 'Reported Person',
        ];
    
        $this->db->where('function_id',$quote_number);
        $this->db->where('function_type','Quote');
        $this->db->update('authorization_history', $authdata);

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
        $product_hsn = $this->input->post('hsn');
        $product_unit = $this->input->post('unit');
        $discount_type = $this->input->post('discount_type');
        $lowest_price = $this->input->post('lowest_price');
        $maximum_discount_rate = $this->input->post('maxdiscountrate');
        $product_amt = $this->input->post('product_amt');
        $product_tax =0;
        foreach ($pid as $key => $value) {
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
                    'quote_number' => $quote_number,
                    'product_code' => $code[$key],
                    'quantity' => numberClean($product_qty[$key]),
                    'price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
                    'tax' => $product_tax,
                    'discount' => $discountamount,
                    'total_amount' => rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc),
                    'total_tax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                    'total_discount' => rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc),
                    'discount_type' => $discount_type[$key],
                );
                
                $flag = true;
                $productlist[$prodindex] = $data;
            }   
            $i++;
            $prodindex++;
            $amt = numberClean($product_qty[$key]);
            $itc += $amt;
            $existornot = $this->quote->check_product_existornot($quote_number,$code[$key]);
            if($existornot==1)
            {
                $this->db->update('cberp_quotes_items', $data, ['quote_number'=>$quote_number, 'product_code'=>$code[$key]]);
            }
            else{
                $this->db->insert('cberp_quotes_items', $data);
            }
        }
        if ($prodindex > 0) {
            //erp2024 06-01-2025 detailed history log starts
            detailed_log_history('Quote',$quote_number,'Quote Sent', $_POST['changedFields']);
            //erp2024 06-01-2025 detailed history log ends 
            $this->db->set(array('discount' => rev_amountExchange_s(amountFormat_general($total_discount), $currency, $this->aauth->get_user()->loc), 'tax' => rev_amountExchange_s(amountFormat_general($total_tax), $currency, $this->aauth->get_user()->loc)));
            $this->db->where('quote_number', $quote_number);
            $this->db->update('cberp_quotes');
        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
                "Please choose product from product list. Go to Item manager section if you have not added the products."));
            $transok = false;
        }

        echo json_encode(array('status' => 'Success', 'message' => $this->lang->line('Quote has  been created'), 'quote'=>$invocieno));
 

        if ($transok) {
            $this->db->trans_complete();
        } else {
            $this->db->trans_rollback();
        }
    }
    // erp2024 15-10-2024 quote direct send ends
  

    public function view()
    {
        $this->load->model('accounts_model');
        $data['acclist'] = $this->accounts_model->accountslist();
        $tid = intval($this->input->get('id'));
        $data['id'] = $tid;
        $data['invoice'] = $this->quote->quote_details($tid);
        $data['products'] = $this->quote->quote_products($tid);
        $data['approvedby'] = $this->quote->approved_person($tid,"Quote");
        $data['trackingdata'] = tracking_details('quote_id',$tid);
        $data['attach'] = $this->quote->attach($tid);
        $data['employee'] = $this->quote->employee($data['invoice']['employee_id']);
        $head['title'] = "Quote #" . $data['invoice']['tid'];
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['configurations'] = $this->configurations;
        $this->load->view('fixed/header', $head);
        if ($data['invoice']) $this->load->view('quotes/view', $data);
        $this->load->view('fixed/footer');
    }


    public function printquote()
    {

        $tid = intval($this->input->get('id'));

        $data['id'] = $tid;
        $data['title'] = "Quote $tid";
        $data['invoice'] = $this->quote->quote_details($tid);
        $data['products'] = $this->quote->quote_products($tid);
        $data['employee'] = $this->quote->employee($data['invoice']['employee_id']);
        $data['general'] = array('title' => $this->lang->line('Quote'), 'person' => $this->lang->line('Customer'), 'prefix' => prefix(1), 't_type' => 0);


        ini_set('memory_limit', '64M');
        if ($data['invoice']['tax_status'] == 'cgst' || $data['invoice']['tax_status'] == 'igst') {
            $html = $this->load->view('print_files/invoice-a4-gst_v' . INVV, $data, true);
        } else {
            $html = $this->load->view('print_files/invoice-a4_v' . INVV, $data, true);
        }
        //PDF Rendering
        $this->load->library('pdf');
        // if (INVV == 1) {
        //     $header = $this->load->view('print_files/invoice-header_v' . INVV, $data, true);
        //     $pdf = $this->pdf->load_split(array('margin_top' => 40));
        //     $pdf->SetHTMLHeader($header);
        // }
        // if (INVV == 2) {
        //     $pdf = $this->pdf->load_split(array('margin_top' => 5));
        // }
        // $pdf->SetHTMLFooter('<div style="text-align: right;font-family: serif; font-size: 8pt; color: #5C5C5C; font-style: italic;margin-top:-6pt;">{PAGENO}/{nbpg} #' . $data['invoice']['tid'] . '</div>');

        // $pdf->WriteHTML($html);

        $file_name = preg_replace('/[^A-Za-z0-9]+/', '-', 'Quote__' . $data['invoice']['name'] . '_' . $data['invoice']['tid']);
        if ($this->input->get('d')) {
            $pdf->Output($file_name . '.pdf', 'D');
        } else {
            $pdf->Output($file_name . '.pdf', 'I');
        }
    }

    public function delete_i()
    {
        $id = $this->input->post('deleteid');
        if ($this->quote->quote_delete($id)) {
            echo json_encode(array('status' => 'Success', 'message' =>
                $this->lang->line('DELETED')));
        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
                $this->lang->line('ERROR')));
        }
    }

    public function editaction()
    {
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL); $emp = $this->input->post('employee');
        $customer_id = $this->input->post('customer_id');
        $invocieno_n = $this->input->post('invocieno');
        $invocieno = $this->input->post('iid');
        $invoicedate = $this->input->post('invoicedate');
        $invocieduedate = $this->input->post('invocieduedate');
        $convertflg = $this->input->post('convertflg');
        $notes = $this->input->post('notes', true);
        $tax = $this->input->post('tax_handle');
        $total_tax = 0;
        $total_discount = 0;
        $discountFormat = $this->input->post('discountFormat');
        $pterms = $this->input->post('pterms');
        $propos = $this->input->post('propos');
        $currency = $this->input->post('mcurrency');
        $ship_taxtype = $this->input->post('ship_taxtype');
        $employee = $this->input->post('employee');
        $oldemployee = $this->input->post('oldemployee');
        $approvalflg = $this->input->post('approvalflg');
        $subtotal = rev_amountExchange_s($this->input->post('subtotal'), $currency, $this->aauth->get_user()->loc);
        $shipping = rev_amountExchange_s($this->input->post('shipping'), $currency, $this->aauth->get_user()->loc);
        $shipping_tax = rev_amountExchange_s($this->input->post('ship_tax'), $currency, $this->aauth->get_user()->loc);
        if ($ship_taxtype == 'incl') $shipping = $shipping - $shipping_tax;
        $refer = $this->input->post('refer', true);
        $total = rev_amountExchange_s($this->input->post('total'), $currency, $this->aauth->get_user()->loc);

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
        if (empty($this->input->post('pid'))) {
            echo json_encode(array('status' => 'Error', 'message' =>
                $this->lang->line('Please add atleast one product')));
            exit;
        }
        //erp2024 find amout limit 17-07-2024 ///////////////////////////////////////
        $oldtotal = rev_amountExchange_s($this->input->post('oldtotal'), $currency, $this->aauth->get_user()->loc);        
        $history =[];    
            if($oldtotal<$total){
                $history['requested_date'] = date("Y-m-d");
                $history['requested_amount'] = $total;
                $history['function_type'] = 'Quote';
                $history['function_id'] = $invocieno;
                $approvalflg = 0;
                $history['requested_by'] = $employee;
                $this->db->delete('authorization_history', array('function_id' => $invocieno, 'function_type'=>'Quote')); 
                $this->db->insert('authorization_history',$history);            
            }
            // $amount_limit = $this->quote->amount_limit($employee); 
            // if(($oldtotal!=$total && $convertflg!=1) || ($employee != $oldemployee))
            // {
            //     $amount_limit = $this->quote->amount_limit($employee);   
            //     $history['requested_date'] = date("Y-m-d");
            //     $history['requested_amount'] = $total;
            //     $history['function_type'] = 'Quote';
            //     $history['function_id'] = $invocieno;
            //     if($amount_limit>=$total){
            //         $approvalflg = 1;
            //         $history['authorized_by'] = $employee;
            //         $history['authorized_date'] = date("Y-m-d");
            //         $history['authorized_amount'] = $total;
            //         $history['authorized_type'] = "Own";
            //         $history['status'] = "Approve";
            //     }
            //     else{
            //         $approvalflg = 0;
            //         $history['requested_by'] = $employee;
            //     }
            //     $this->db->delete('authorization_history', array('function_id' => $invocieno, 'function_type'=>'Quote')); 
            //     $this->db->insert('authorization_history',$history);
            // }
      
        //////////////////////////////////////////////////////////////////////////////////////////////////////////

        $this->db->trans_start();
        $flag = false;
        $transok = true;


        //Product Data
        $pid = $this->input->post('pid');
        $productlist = array();

        $prodindex = 0;

        
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
        // $product_des = $this->input->post('product_description', true); refer
        $product_hsn = $this->input->post('hsn');
        $product_unit = $this->input->post('unit');
        
        $discount_type = $this->input->post('discount_type');
        $lowest_price = $this->input->post('lowest_price');
        $maximum_discount_rate = $this->input->post('maxdiscountrate');        
        $product_amt = $this->input->post('product_amt');
        $product_tax =0;
        if(!empty($pid)){
            $this->db->delete('cberp_quotes_items', array('tid' => $invocieno));
            foreach ($pid as $key => $value) {
                if($this->configurations["config_tax"]!="0"){ 
                    $product_tax = numberClean($product_tax[$key]);
                }
                $total_discount += numberClean(@$ptotal_disc[$key]);
                $total_tax += numberClean($ptotal_tax[$key]);
                if($discount_type[$key]=="Amttype"){
                    $discountamount = numberClean($product_amt[$key]);
                }
                else{
                    $discountamount = numberClean($product_discount[$key]);
                }
                $data1 = array( 
                    'tid' => $invocieno,
                    'pid' => $product_id[$key],
                    'product' => $product_name1[$key],
                    'product_code' => $code[$key],
                    'quantity' => numberClean($product_qty[$key]),
                    'remaining_quantity' => numberClean($product_qty[$key]),
                    'ordered_quantity' => numberClean($product_qty[$key]),
                    'transfered_quantity' => 0,
                    'delivered_quantity' => 0,
                    'price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
                    'tax' => $product_tax,
                    'discount' => $discountamount,
                    'subtotal' => rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc),
                    'total_tax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                    'total_discount' => rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc),
                    // 'product_des' => $product_des[$key],
                    'unit' => $product_unit[$key],
                    'discount_type' => $discount_type[$key],
                    'lowest_price' => $lowest_price[$key],
                    'maximum_discount_rate' => $maximum_discount_rate[$key]
                );

                $flag = true;
                $productlist[$prodindex] = $data1;
                $i += numberClean($product_qty[$key]);;
                $prodindex++;
            }
        }
        $bill_date = datefordatabase($invoicedate);
        $bill_due_date = datefordatabase($invocieduedate);

        $total_discount = rev_amountExchange_s(amountFormat_general($total_discount), $currency, $this->aauth->get_user()->loc);
        $total_tax = rev_amountExchange_s(amountFormat_general($total_tax), $currency, $this->aauth->get_user()->loc);

        
      
       $data = array('invoicedate' => $bill_date, 'due_date' => $bill_due_date, 'subtotal' => $subtotal, 'shipping' => $shipping, 'ship_tax' => $shipping_tax, 'ship_tax_type' => $ship_taxtype, 'discount' => $total_discount, 'tax' => $total_tax, 'total' => $total, 'notes' => $notes, 'customer_id' => $customer_id, 'items' => $i, 'tax_status' => $tax, 'discount_status' => $discount_status, 'format_discount' => $discountFormat, 'refer' => $refer, 'term' => $pterms, 'customer_message' => $propos, 'multi' => $currency, 'approvalflg' => $approvalflg, 'employee_id'=>$employee);
       if($approvalflg==0){
            $data['status'] ="pending";
       }
       else{
            $data['status'] ="accepted";
       }
       $this->db->set($data);
        $this->db->where('id', $invocieno);
        //////////////////////////////////////////////////////////////
        if ($flag) {
            if ($this->db->update('cberp_quotes', $data)) {
               
                $this->db->insert_batch('cberp_quotes_items', $productlist);
                echo json_encode(array('status' => 'Success', 'message' =>
                    $this->lang->line('Quote has  been updated') . " <a href='view?id=$invocieno' class='btn btn-info btn-sm'><span class='icon-file-text2' aria-hidden='true'></span> View </a> "));
            } else {
                echo json_encode(array('status' => 'Error', 'message' =>
                    $this->lang->line('ERROR')));
                $transok = false;
            }


        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
                "Please add atleast one product in invoice $invocieno"));
            $transok = false;
        }


        if ($transok) {
            $this->db->trans_complete();
        } else {
            $this->db->trans_rollback();
        }
    }
    public function editdraftaction()
    {
        $customer_id = $this->input->post('customer_id');
        $invocieno_n = $this->input->post('invocieno');
        $invocieno = $this->input->post('iid');
        $invoicedate = $this->input->post('invoicedate');
        $invocieduedate = $this->input->post('invocieduedate');
        $convertflg = $this->input->post('convertflg');
        $notes = $this->input->post('notes', true);
        $tax = $this->input->post('tax_handle');
        $total_tax = 0;
        $total_discount = 0;
        $discountFormat = $this->input->post('discountFormat');
        $pterms = $this->input->post('pterms');
        $propos = $this->input->post('propos');
        $currency = $this->input->post('mcurrency');
        $ship_taxtype = $this->input->post('ship_taxtype');
        $employee = $this->input->post('employee');
        $oldemployee = $this->input->post('oldemployee');
        $approvalflg = $this->input->post('approvalflg');
        $subtotal = rev_amountExchange_s($this->input->post('subtotal'), $currency, $this->aauth->get_user()->loc);
        $shipping = rev_amountExchange_s($this->input->post('shipping'), $currency, $this->aauth->get_user()->loc);
        $shipping_tax = rev_amountExchange_s($this->input->post('ship_tax'), $currency, $this->aauth->get_user()->loc);
        if ($ship_taxtype == 'incl') $shipping = $shipping - $shipping_tax;
        $refer = $this->input->post('refer', true);
        $total = rev_amountExchange_s($this->input->post('total'), $currency, $this->aauth->get_user()->loc);

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
        if (empty($this->input->post('pid'))) {
            echo json_encode(array('status' => 'Error', 'message' =>
                $this->lang->line('Please add atleast one product')));
            exit;
        }
        //erp2024 find amout limit 17-07-2024 ///////////////////////////////////////
        $oldtotal = rev_amountExchange_s($this->input->post('oldtotal'), $currency, $this->aauth->get_user()->loc);        
        // $history =[];    
        //     if($oldtotal<$total){
        //         $history['requested_date'] = date("Y-m-d");
        //         $history['requested_amount'] = $total;
        //         $history['function_type'] = 'Quote';
        //         $history['function_id'] = $invocieno;
        //         $approvalflg = 0;
        //         $history['requested_by'] = $employee;
        //         $this->db->delete('authorization_history', array('function_id' => $invocieno, 'function_type'=>'Quote')); 
        //         $this->db->insert('authorization_history',$history);            
        //     }
        //////////////////////////////////////////////////////////////////////////////////////////////////////////

        $this->db->trans_start();
        $flag = false;
        $transok = true;


        //Product Data
        $pid = $this->input->post('pid');
        $productlist = array();

        $prodindex = 0;

        
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
        // $product_des = $this->input->post('product_description', true); refer
        $product_hsn = $this->input->post('hsn');
        $product_unit = $this->input->post('unit');
        
        $discount_type = $this->input->post('discount_type');
        $lowest_price = $this->input->post('lowest_price');
        $maximum_discount_rate = $this->input->post('maxdiscountrate');        
        $product_amt = $this->input->post('product_amt');
        $product_tax =0;
        if(!empty($pid)){
            $this->db->delete('cberp_quotes_items', array('tid' => $invocieno));
            foreach ($pid as $key => $value) {
                if($this->configurations["config_tax"]!="0"){ 
                    $product_tax = numberClean($product_tax[$key]);
                }
                $total_discount += numberClean(@$ptotal_disc[$key]);
                $total_tax += numberClean($ptotal_tax[$key]);
                if($discount_type[$key]=="Amttype"){
                    $discountamount = numberClean($product_amt[$key]);
                }
                else{
                    $discountamount = numberClean($product_discount[$key]);
                }
                $data1 = array( 
                    'tid' => $invocieno,
                    'pid' => $product_id[$key],
                    'product' => $product_name1[$key],
                    'product_code' => $code[$key],
                    'quantity' => numberClean($product_qty[$key]),
                    'remaining_quantity' => numberClean($product_qty[$key]),
                    'ordered_quantity' => numberClean($product_qty[$key]),
                    'transfered_quantity' => 0,
                    'delivered_quantity' => 0,
                    'price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
                    'tax' => $product_tax,
                    'discount' => $discountamount,
                    'subtotal' => rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc),
                    'total_tax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                    'total_discount' => rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc),
                    // 'product_des' => $product_des[$key],
                    'unit' => $product_unit[$key],
                    'discount_type' => $discount_type[$key],
                    'lowest_price' => $lowest_price[$key],
                    'maximum_discount_rate' => $maximum_discount_rate[$key]
                );

                $flag = true;
                $productlist[$prodindex] = $data1;
                $i += numberClean($product_qty[$key]);;
                $prodindex++;
            }
        }
        $bill_date = datefordatabase($invoicedate);
        $bill_due_date = datefordatabase($invocieduedate);

        $total_discount = rev_amountExchange_s(amountFormat_general($total_discount), $currency, $this->aauth->get_user()->loc);
        $total_tax = rev_amountExchange_s(amountFormat_general($total_tax), $currency, $this->aauth->get_user()->loc);

        
      
       $data = array('invoicedate' => $bill_date, 'due_date' => $bill_due_date, 'subtotal' => $subtotal, 'shipping' => $shipping, 'ship_tax' => $shipping_tax, 'ship_tax_type' => $ship_taxtype, 'discount' => $total_discount, 'tax' => $total_tax, 'total' => $total, 'notes' => $notes, 'customer_id' => $customer_id, 'items' => $i, 'tax_status' => $tax, 'discount_status' => $discount_status, 'format_discount' => $discountFormat, 'refer' => $refer, 'term' => $pterms, 'customer_message' => $propos, 'multi' => $currency, 'approvalflg' => $approvalflg, 'employee_id'=>$employee);
       $data['status'] ="draft";
       $this->db->set($data);
        $this->db->where('id', $invocieno);
        //////////////////////////////////////////////////////////////
        if ($flag) {
            if ($this->db->update('cberp_quotes', $data)) {
               
                $this->db->insert_batch('cberp_quotes_items', $productlist);
                echo json_encode(array('status' => 'Success', 'message' =>
                    $this->lang->line('Quote has  been updated') . " <a href='view?id=$invocieno' class='btn btn-info btn-sm'><span class='icon-file-text2' aria-hidden='true'></span> View </a> "));
            } else {
                echo json_encode(array('status' => 'Error', 'message' =>
                    $this->lang->line('ERROR')));
                $transok = false;
            }


        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
                "Please add atleast one product in invoice $invocieno"));
            $transok = false;
        }


        if ($transok) {
            $this->db->trans_complete();
        } else {
            $this->db->trans_rollback();
        }
    }


    public function update_status()
    {
        $tid = $this->input->post('tid');
        $status = $this->input->post('status');
        $this->db->set('status', $status);
        $this->db->where('id', $tid);
        $this->db->update('cberp_quotes');
        echo json_encode(array('status' => 'Success', 'message' =>
            $this->lang->line('Quote Status updated') . '', 'pstatus' => $status));
    }
    

    public function convert()
    {
        $tid = $this->input->post('tid');


        if ($this->quote->convert($tid)) {

            echo json_encode(array('status' => 'Success', 'message' =>
                $this->lang->line('Quote to invoice conversion')));
        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
                $this->lang->line('ERROR')));
        }
    }

    public function convert_po()
    {
        $tid = $this->input->post('tid');
        $person = $this->input->post('customer_id');


        if ($this->quote->convert_po($tid, $person)) {

            echo json_encode(array('status' => 'Success', 'message' =>
                $this->lang->line('Quote to invoice conversion')));
        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
                $this->lang->line('ERROR')));
        }
    }

    public function file_handling()
    {
        if ($this->input->get('op')) {
            $name = $this->input->get('name');
            $invoice = $this->input->get('invoice');
            if ($this->quote->meta_delete($invoice, 2, $name)) {
                echo json_encode(array('status' => 'Success'));
            }
        } else {
            $id = $this->input->get('id');
            $this->load->library("Uploadhandler_generic", array(
                'accept_file_types' => '/\.(gif|jpe?g|png|docx|docs|txt|pdf|xls)$/i', 'upload_dir' => FCPATH . 'userfiles/attach/', 'upload_url' => base_url() . 'userfiles/attach/'
            ));
            $files = (string)$this->uploadhandler_generic->filenaam();
            if ($files != '') {
                $fid = rand(100, 9999);
                $this->quote->meta_insert($id, 2, $files);
            }
        }


    }



    public function convert_to_salesorder()
    {

        $quote_number = $this->input->post('id'); 
        $results = $this->quote->quote_product_by_id($quote_number); 
        $response=[];
        $prevresult = $this->quote->already_converted_to_salesorder($quote_number);
        if(!empty($prevresult['salesorder_number']))
        {
            $response= [
                "status" => "ok",
                "message" => "successfully converted",
                "id" => $prevresult['salesorder_number'],
                "flg" =>"1"  //set flag is 1 for prevent duplicate check
            ];
        }
        else{
            unset($results['quote_date']);
            unset($results['lead_number']);
            unset($results['created_by']);
            unset($results['created_date']);
            unset($results['updated_by']);
            unset($results['updated_date']);
            unset($results['prepared_by']);
            unset($results['prepared_date']);
            unset($results['prepared_flag']);
            unset($results['sent_by']);
            unset($results['sent_date']);
            unset($results['approved_level']);
            // unset($results['customer_reference_number']);
            // unset($results['customer_contact_person']);
            // unset($results['customer_contact_number']);
            // unset($results['customer_contact_email']);

            $results['quote_number'] = $quote_number;    
            $results['created_by'] = $this->session->userdata('id'); 
            $results['created_date'] = date('Y-m-d H:i:s');
            $results['salesorder_date'] = date('Y-m-d H:i:s');
            $results['status'] = 'pending';
            // $results['salesorder_date'] = NULL;
            // history_table_log('cberp_quotes_log','quote_id',$results['id'],'Converted to Sales Order');
            $salesorder_number = $this->salesorder->lastsalesorder();        
            insertion_to_tracking_table('salesorder_number',$salesorder_number,'quote_number', $quote_number);             
            $results['salesorder_number'] = $salesorder_number;          
            $module_number =  module_number_name('Sales');  
            detailed_log_history($module_number,$salesorder_number,'Created', '');
            $this->db->insert('cberp_sales_orders', $results); 
            $this->session->set_userdata('latestsalesorder', $salesorder_number);
            $this->quote->insert_to_sales_order_items($quote_number,$salesorder_number);
            $response= [
                "status" => "ok",
                "message" => "successfully converted",
                "id" => $salesorder_number,
                "flg" =>"1"  //set flag is 1 for prevent duplicate check
            ];
            
        }
        
        echo json_encode($response);
        
    }
   
    public function salesorders()
    {

            // $this->session->unset_userdata('repeatsubmit');
            $data['permissions'] = load_permissions('Sales','Sales','Sales Orders','View Page');
            $this->load->model('customers_model', 'customers');
            $data['customergrouplist'] = $this->customers->group_list();
            $tid = intval($this->input->get('id'));
            $data['id'] = $tid;
            
            $this->session->set_userdata('orderid', $tid);
            $data['terms'] = $this->quote->billingterms();
            $data['invoice'] = $this->quote->salesorder_details($tid);

            if($data['invoice']['converted_status']=='3')
            {
                $data['warehouse_details'] = $this->quote->assigned_warehouse($data['invoice']['delnote_number']);
            }
            $data['products'] = $this->quote->salesorder_products_main_list($tid);
            $data['prdstatus'] = $this->salesorder->get_prdstatus_salesorderid($tid);
            $data['currency'] = $this->quote->currencies();
            $head['title'] = "Salesorder #" . $data['invoice']['salesorder_number'];
            $head['usernm'] = $this->aauth->get_user()->username;
            $data['warehouse'] = $this->quote->warehouses();
            $this->load->model('plugins_model', 'plugins');
            $data['exchange'] = $this->plugins->universal_api(5);
            $this->load->library("Common");
            $data['taxlist'] = $this->common->taxlist_edit($data['invoice']['tax_status']);
            $data['trackingdata'] = tracking_details('quote_id',$data['invoice']['quote_id']);   
            // echo "<pre>";
            // print_r($data['trackingdata']);
            // die();   
            $data['configurations'] = $this->configurations;
            $data['log'] = $this->quote->getsalehistory($tid);
            //erp2024 06-01-2025 detailed history log starts
            $page = "Salesorder";
            $data['detailed_log']= $this->salesorder->get_detailed_log($tid,$page);
            $products = $data['detailed_log'];
            $groupedBySequence = []; // Initialize an empty array for grouping

            foreach ($products as $product) {
                $sequence = $product['seqence_number'];
                $groupedBySequence[$sequence][] = $product; // Group by sequence number
            }
            
            $data['groupedSalesorders'] = $groupedBySequence;
            //erp2024 06-01-2025 detailed history log starts
            $this->load->view('fixed/header', $head);
            $this->load->view('sales/edit', $data);
            $this->load->view('fixed/footer');
    }
    public function quote_to_salesorders()
    {
        $this->load->model('customers_model', 'customers');
        $data['customergrouplist'] = $this->customers->group_list();
        $tid = intval($this->input->get('id'));
        $data['id'] = $tid;
        $data['quote_id'] = $this->quote->get_quote_number_from_salesorder($tid);
        $quote_id = $data['quote_id'];
        $data['trackingdata'] = tracking_details('quote_id',$data['quote_id']);

        $this->session->set_userdata('orderid', $tid);
        $data['terms'] = $this->quote->billingterms();
        $data['invoice'] = $this->quote->quote_details_byid($quote_id);
        $data['products'] = $this->quote->salesorder_products($tid);
        
        $data['customer'] = $this->quote->get_customer_by_quoteid($quote_id);
        $data['salesorder_num'] = $this->quote->salesorder_number();
        $data['currency'] = $this->quote->currencies();
        // $head['title'] = "Quote To Sales Order #" . $data['invoice']['tid'];
        $head['title'] = "Sales Order Landing";
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['warehouse'] = $this->quote->warehouses();
        $this->load->model('plugins_model', 'plugins');
        $data['exchange'] = $this->plugins->universal_api(5);
        $this->load->library("Common");
        $data['taxlist'] = $this->common->taxlist_edit($data['invoice']['tax_status']);                
        $data['configurations'] = $this->configurations;
        $this->load->view('fixed/header', $head);
        $this->load->view('sales/quotetosalesorder', $data);
        $this->load->view('fixed/footer');
            
    }



    public function saleorderaction_for_draft_convertion()
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
        $invocieno_n = $this->input->post('invocieno');
        $invocieno = $this->input->post('iid');
        $quote_id = $this->input->post('quote_id');
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

        
        $ordered_quantity = $this->input->post('ordered_quantity');
        $transfered_quantity = $this->input->post('transfered_quantity');
        $delivered_quantity = $this->input->post('delivered_quantity');
        $remaining_quantity = $this->input->post('remaining_quantity');

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
        $this->db->where('tid', $invocieno);
        $oldstatus = $this->db->get();

      
        $statusList = $oldstatus->result_array();        
        $this->db->delete('cberp_sales_orders_items', array('tid' => $invocieno));
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
        $maximum_discount_rate = $this->input->post('maximum_discount_rate');
        $lowest_price = $this->input->post('lowest_price');
        


        // print_r($product_discount); die();
        foreach ($pid as $key => $value) {
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
                'tid' => $invocieno,
                'pid' => $product_id[$key],
                'product' => $product_name1[$key],
                'product_code' => $product_hsn[$key],
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
                'lowest_price'    => $lowest_price[$key],
                'maximum_discount_rate'    => $maximum_discount_rate[$key],
                'transfered_quantity' => (numberClean($product_qty[$key]) + numberClean($transfered_quantity[$key])),
                'delivered_quantity'  => numberClean($delivered_quantity[$key]) + numberClean($product_qty[$key]),
                'remaining_quantity'  => numberClean($remaining_quantity[$key]) - numberClean($product_qty[$key]),
                
            );
            // $data = array(
            //     'tid' => $invocieno,
            //     'pid' => $product_id[$key],
            //     'product' => $product_name1[$key],
            //     'product_code' => $product_hsn[$key],
            //     'quantity' => numberClean($product_qty[$key]),
            //     'price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
            //     'tax' => numberClean($product_tax[$key]),
            //     'discount' => $discountamount,
            //     'subtotal' => rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc),
            //     'total_tax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
            //     'total_discount' => rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc),
            //     'product_des' => $product_des[$key],
            //     'unit' => $product_unit[$key],
            //     'status' => $status,                
            //     'discount_type' => $discount_type[$key],
            // );

            // erp2024 quote items update 06-08-2024
            $quoteitems = $this->quote->quote_details_for_multiplesalesorders($quote_id,$product_id[$key]);
            $remainigqty = $quoteitems['remaining_quantity'];
            $deliveredqty = $quoteitems['delivered_quantity'];
            $transferedqty = $quoteitems['transfered_quantity'];
            $orderedqty = $quoteitems['ordered_quantity'];
            $actualqty = $quoteitems['qty'];
            $delveredqty = numberClean($quoteitems['deliveredqty']) + numberClean($product_qty[$key]);
            $transferqty = (numberClean($product_qty[$key]) + numberClean($quoteitems['transfered_quantity']));  
            $remainigqty_new = numberClean($quoteitems['remaining_quantity']) - numberClean($product_qty[$key]);
            $remainigqty_new = ($remainigqty_new >0) ? $remainigqty_new : 0;
            $prdstatus=0;        
            if($orderedqty <= $transferqty){
                $prdstatus = 1;
            }
            else{
                $prdstatus = 0;  
            }
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
        // echo "<pre>";
        // print_r($productlist);
        // die();
        $bill_date = datefordatabase($invoicedate);
        $bill_due_date = datefordatabase($invocieduedate);

        $total_discount = rev_amountExchange_s(amountFormat_general($total_discount), $currency, $this->aauth->get_user()->loc);
        $total_tax = rev_amountExchange_s(amountFormat_general($total_tax), $currency, $this->aauth->get_user()->loc);

       
        $data1 = array('invoicedate' => $bill_date, 'due_date' => $bill_due_date, 'subtotal' => $subtotal, 'shipping' => $shipping, 'ship_tax' => $shipping_tax, 'ship_tax_type' => $ship_taxtype, 'discount' => $total_discount, 'tax' => $total_tax, 'total' => $total, 'notes' => $notes, 'customer_id' => $customer_id, 'items' => $i, 'tax_status' => $tax, 'discount_status' => $discount_status, 'format_discount' => $discountFormat, 'refer' => $refer, 'term' => $pterms, 'customer_message' => $propos, 'multi' => $currency, 'customer_order_date'=>$customer_order_date, 'customer_purchase_order'=>$customer_purchase_order,'completed_status'=>$completed_status);
        $this->db->set($data1);
        $this->db->where('id', $invocieno);
        
        if ($flag) {
            
            if ($this->db->update('cberp_sales_orders', $data1)) {
               
                $this->db->insert_batch('cberp_sales_orders_items', $productlist);
                //update quote status
                $salestid = $invocieno+1000;
                $seqdetails = $this->quote->seqnumber_byquote($quote_id);
                if($seqdetails['seq_number']>0 && !empty($seqdetails['salesorder_number']))
                {
                    $this->quote->update_quote_status_for_subitems($quote_id, $salestid, $invocieno);
                }
                else{
                    $this->quote->update_quote_status($quote_id, $salestid, $invocieno);
                }
                // 
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
                "Please add atleast one product in invoice $invocieno"));
            $transok = false;
        }


        // if ($transok) {
        //     $this->db->trans_complete();
        // } else {
        //     $this->db->trans_rollback();
        // }
    }
    

   

    public function deliverynoteaction()
    {
       
        $delevery_note_type = $this->input->post('delevery_note_type');
        // if($delevery_note_type = "New")
        // {
        //     $this->deliverynote_save_and_new_action();
        // }
        $current_value = $this->session->userdata('repeatsubmit');
        $transaction_number = get_latest_trans_number();
        $deleverynotetid = $this->input->post('invocieno');        
        // $deleverynotetid = $this->input->post('invocieno');        
        $data1['tid'] = intval($this->input->post('delevery_note_id'))+1000;
        // $data1['tid'] = $this->input->post('invocieno');
        $store_id = $this->input->post('store_id');
        //erp2024 note 26-09-2024 
        $data1['note'] = $this->input->post('note');
        $data1['store_id'] = $this->input->post('store_id');
        //erp@2024 new field 02-12-2024
        $income_account_number = $this->input->post('income_account_number', true);

        $data1['delnote_number'] = $this->input->post('invocieno_demo');
        $data1['salesorder_number'] = $this->input->post('salesorder_number');
        $data1['total_amount'] = rev_amountExchange_s($this->input->post('total'), $currency, $this->aauth->get_user()->loc);
        $totalamountcust = rev_amountExchange_s($this->input->post('total'), $currency, $this->aauth->get_user()->loc);
        $data1['salesorder_id'] = $this->input->post('iid');
        $data1['subtotal'] = numberClean($this->input->post('subtotal'));
        $data1['shipping'] = $this->input->post('shipping');
        $data1['ship_tax'] = $this->input->post('ship_tax');
        $data1['completed_status'] = $this->input->post('completed_status');
        $data1['customer_id'] = $customer_id = $this->input->post('customer_id');
        // $data1['invoice_number'] = $this->input->post('invoice_number');
        $data1['created_date'] = date("Y-m-d");
        $data1['created_time'] = date("H:i:s");
        $data1['transaction_number'] = $transaction_number;
        $data1['deliveryduedate'] = $this->input->post('deliveryduedate');
        if($data1['completed_status']==0){
            $data1['status'] = 'Draft';
        }
        else if($data1['completed_status']==1){
            $data1['status'] = 'Assigned';
            $data1['completed_status']='0';
            if($this->input->post('pick_item_recieved_status')=='1')
            {
                $data1['status'] = 'Completed';
                $data1['completed_status']='1';
            }
            
        }
        else{            
            $data1['status'] = 'Invoiced';
        }

        // $data1['status'] = 'Printed'; 


        $checkres = $this->quote->check_deliverynote_creation_once_completed($data1['salesorder_id']);
        if (!empty($checkres)) {
            $salesorder_deltid = $this->input->post('delnote_tid');
        } else {
            $salesorder_deltid = $deleverynotetid;
        }
        

        
        $existingdeliverynoteid =  $this->quote->deliverynoteid_by_salesorder_number($data1['salesorder_number'],$data1['tid']);
        
        // $this->db->delete('cberp_delivery_notes',['salesorder_number'=>$data1['salesorder_number']]);
        // $this->db->delete('cberp_delivery_note_items',['delevery_note_id'=>$existingdeliverynoteid]);

        // $last_insert_id = $this->session->userdata("latest_delnote_id");
        $last_insert_id =$this->input->post('delevery_note_id');
        $this->db->update('cberp_delivery_notes', $data1,['salesorder_number'=>$data1['salesorder_number'],'tid'=>$data1['tid']]);
        history_table_log('delivery_note_log','deliverynote_id',$last_insert_id,'Update');
        // die($this->db->last_query());
        $this->db->delete('cberp_delivery_note_items',['delevery_note_id'=>$last_insert_id]);

        if($_FILES['upfile'])
        {
            upload_files($_FILES['upfile'], 'Deliverynote',$last_insert_id);
        }
        
        // $this->db->delete('cberp_delivery_note_items',['delevery_note_id'=>$existingdeliverynoteid]);
        // $last_insert_id =  $existingdeliverynoteid;
        
        // die($this->db->last_query());
        
        
        //tracking 
        // $this->db->where('sales_id', $this->input->post('iid'));
        // $this->db->update('cberp_transaction_tracking',['deliverynote_id'=>$last_insert_id]);

        $this->session->set_userdata("deliverymaster", $last_insert_id);

        $i = 0;

        $flag = false;
        $transok = true;


        //Product Data
        $pid = $this->input->post('pid');
        $productlist = array();

        $prodindex = 0;
        $product_id = $this->input->post('pid');
        $product_name1 = $this->input->post('product_name', true);
        $product_qty = $this->input->post('product_qty');
        $old_product_qty = $this->input->post('old_product_qty');
        $product_price = $this->input->post('product_price');
        $product_tax = $this->input->post('product_tax');
        $product_discount = $this->input->post('product_discount');
        $product_subtotal = $this->input->post('product_subtotal');
        $ptotal_tax = $this->input->post('taxa');
        $ptotal_disc = $this->input->post('disca');
        $product_des = $this->input->post('product_description', true);
        $product_hsn = $this->input->post('hsn');
        $product_unit = $this->input->post('unit');
        $discount_type = $this->input->post('discount_type');
        $order_discount = $this->input->post('order_discount');
        $total_records  = count($product_id);
        $product_wise_order_discount = ($order_discount>0) ?round(($order_discount/$total_records),2):0;
        $tid = $this->session->userdata('salesorderid');        
        $product_cost = $this->input->post('product_cost', true);
        $total_discount =0;
        $total_tax  =0;        
        $wholestatus = 1;
        $grand_product_cost = 0;
        $grandprice = 0;
        // erp2024 19-12-2024 load default accounts
        $default_cost_of_goods_account = default_chart_of_account('cost_of_goods_solid');
        $default_inventory_account = default_chart_of_account('inventory');
        // $cost_data_list = [];
        $producttransdata1 = [];
        foreach ($pid as $key => $value) {
            $status ="";
            $total_discount += numberClean(@$ptotal_disc[$key]);
            $total_tax += numberClean($ptotal_tax[$key]);
            foreach ($statusList as $item) {            
                if($item['pid']==$product_id[$key]){
                    $status = $item['status'];
                }            
            }

            // $upstatus = array('status' => "delivered");                     
            // $this->db->where('tid', $tid);
            // $this->db->where_in('pid', $pid);
            // $this->db->update('cberp_sales_orders_items', $upstatus);
            if(($data1['completed_status']==1) && (numberClean($product_qty[$key])<1)){
                continue;
            }
            $productprice = rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc);
            $productty = numberClean($product_qty[$key]);

            $actulprice = numberClean($productprice)*numberClean($productty);   
            $grandprice +=  numberClean($actulprice);

            $productwise_total = rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc)-numberClean($product_wise_order_discount);
            $data = array(
                'salesorder_id' => numberClean($data1['salesorder_id']),
                'delevery_note_id' => $this->input->post('delevery_note_id'),
                'delnote_number' => $this->input->post('invocieno_demo'),
                'product' => $product_name1[$key],
                'product_id' => numberClean($product_id[$key]),
                'product' => $product_name1[$key],
                'product_code' => $product_hsn[$key],
                'product_qty' => $productty,
                'salesorder_product_qty' => numberClean($old_product_qty[$key]),
                'product_price' => $productprice,
                'product_tax' => numberClean($product_tax[$key]),
                // 'product_discount' => numberClean($product_discount[$key]),
                'subtotal' => rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc),
                'total_tax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                'total_discount' => rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc),
                'unit' => $product_unit[$key],
                'discount_type' => $discount_type[$key],
                'product_cost' => $product_cost[$key]
            );
            if(numberClean($product_qty[$key])<1){
                $data['product_discount'] =  0;
            }
            else{
                $data['product_discount'] =  numberClean($product_discount[$key]);
            }
             


           

            
            // $producttransdata =  [
            //     'acid' => $income_account_number[$key],
            //     'type' => 'Asset',
            //     'cat' => 'Deliverynote',
            //     'credit' => $productwise_total,
            //     // 'credit' => $actulprice,
            //     'employee_id' => $this->session->userdata('id'),
            //     'date' => date('Y-m-d'),
            //     'transaction_number'=>$transaction_number,
            //     // 'invoice_number'=>$invoice_number
            // ];
            // $this->db->set('lastbal', 'lastbal - ' . $productwise_total, FALSE);
            // $this->db->where('acn', $income_account_number[$key]);
            // $this->db->update('cberp_accounts'); 
            // $this->db->insert('cberp_transactions', $producttransdata);

            $producttransdata1[$income_account_number[$key]][] =  [
                'acid' => $income_account_number[$key],
                'credit' => numberClean($actulprice)
            ];
      


            // cost of goods transaction
            $total_product_cost = $product_cost[$key]*numberClean($product_qty[$key]);
            $grand_product_cost += numberClean($total_product_cost);
           


            $flag = true;
            $productlist[$prodindex] = $data;
            $i += numberClean($product_qty[$key]);
            $prodindex++;

            $this->db->insert('cberp_delivery_note_items', $data);



            //erp2024 invetory adjusted 25-08-2024
            if($data1['completed_status']==1)
            {
                $pid = numberClean($product_id[$key]);
                $qty = numberClean($product_qty[$key]);
                $this->db->select('qty');
                $this->db->from('cberp_products');
                $this->db->where('pid', $pid);
                $prdQry = $this->db->get();        
                $prdresult = $prdQry->row_array();
                $this->update_warehouse_products($pid, $store_id, $qty);
                if ($prdresult) {
                    $onhand = intval($prdresult['qty']);
                    $updateQty = $onhand - $qty;          
                    $upqty = array('quantity' => $updateQty);
                    $this->db->where('pid', $pid);
                    $this->db->update('cberp_products', $upqty);
                }   
                
                 //erp2024 data insert to average cost 25-02-2025
                insert_data_to_average_cost_table($product_id[$key], $product_cost[$key],numberClean($product_qty[$key]), get_costing_transation_type("Sales"));
            }  
            //erp2024 invetory adjusted 25-08-2024

            // erp2024 quote items update 20-08-2024
             
            if($data1['completed_status']==1)
            {
                $saleorderid = $this->input->post('iid');
                $salesorderitems = $this->quote->salesorder_details_for_multipledeliverynotes($saleorderid,$product_id[$key]);
   
                $actualqty = $salesorderitems['qty'];
                $remainigqty = ($salesorderitems['del_remaining_qty']>0) ? $salesorderitems['del_remaining_qty'] : $actualqty;
                $deliveredqty = $salesorderitems['del_delivered_qty'];
                $transferedqty = $salesorderitems['del_transfered_qty'];
                $orderedqty = $salesorderitems['ordered_quantity'];
                $delveredqty = numberClean($salesorderitems['del_delivered_qty']) + numberClean($product_qty[$key]);
                $transferqty = (numberClean($product_qty[$key]) + numberClean($salesorderitems['del_transfered_qty'])); 
                $enteredqty = numberClean($product_qty[$key]);
                
                $sumofqty = $enteredqty+$transferqty;
                if($transferqty >= $remainigqty){
                // if($sumofqty >= $remainigqty){
                    $prdstatus = 1;
                }
                else{
                    $prdstatus = 0;  
                    $wholestatus = $wholestatus+1;
                }
                
                $delnote_qty = (numberClean($remainigqty) - numberClean($product_qty[$key])>0) ? numberClean($remainigqty) - numberClean($product_qty[$key]) :0;
                $salesdata = array(
                    'del_remaining_qty' => $delnote_qty,
                    'del_delivered_qty' => numberClean($product_qty[$key]),
                    'del_transfered_qty' =>  $transferqty,
                    'prdstatus' => $prdstatus
                );

                $this->db->update('cberp_sales_orders_items',$salesdata,['tid'=>$saleorderid, 'pid'=>$product_id[$key]]); 

                // if($current_value==1)
                // {
                //     $this->db->update('cberp_sales_orders_items',$salesdata,['tid'=>$saleorderid, 'pid'=>$product_id[$key]]);   
                //     // echo $this->db->last_query()."\n<br>";
                    
                // }  

                 
            }

            

        }
        if($producttransdata1)
        {
            foreach ($producttransdata1 as $acid => $transactions) {
                $totalCredit = 0;
        
                foreach ($transactions as $transaction) {
                    $totalCredit += $transaction['credit'];
                }
        
                // Store the summed data for each `acid`
                $groupedData[] = [
                    'acid' => $acid,
                    'type' => 'Asset',
                    'cat' => 'Deliverynote',
                    'credit' => $totalCredit,
                    'employee_id' => $this->session->userdata('id'),
                    'date' => date('Y-m-d'),
                    'transaction_number'=>$transaction_number
                ];
                $this->db->set('lastbal', 'lastbal - ' . $totalCredit, FALSE);
                $this->db->where('acn', $acid);
                $this->db->update('cberp_accounts'); 

            }
        }

          // erp2024 transactions starts 25-10-2024
            if (($groupedData)) {
             $this->db->insert_batch('cberp_transactions', $groupedData);
            } 
            $invoice_receivable_account_details = default_chart_of_account('accounts_receivable');
            $latest_total = $total;
            $receivable_data = [
                'acid' => $invoice_receivable_account_details,
                'type' => 'Asset',
                'cat' => 'Deliverynote',
                'debit' => $grandprice,
                // 'debit' => $total,
                'employee_id' => $this->session->userdata('id'),
                'date' => date('Y-m-d'),
                'transaction_number'=>$transaction_number,
            ];
            $this->db->insert('cberp_transactions',$receivable_data);
            $this->db->set('lastbal', 'lastbal + ' .$total, FALSE);
            $this->db->where('acn', $invoice_receivable_account_details);
            $this->db->update('cberp_accounts'); 


            // $invoice_receivable_account_details = get_account_details_for_invoicing("Current Asset",'Accounts Receivable');
            // $latest_total = $total;
            // $receivable_data = [
            //     'acid' => $invoice_receivable_account_details['acn'],
            //     'type' => 'Asset',
            //     'cat' => 'Deliverynote',
            //     'debit' => $totalamountcust,
            //     'employee_id' => $this->session->userdata('id'),
            //     'date' => date('Y-m-d'),
            //     'transaction_number'=>$transaction_number,
            // ];
            // $this->db->insert('cberp_transactions',$receivable_data);
            // $this->db->set('lastbal', 'lastbal + ' .$totalamountcust, FALSE);
            // $this->db->where('acn', $invoice_receivable_account_details['acn']);
            // $this->db->update('cberp_accounts'); 
            // erp2024 transactions ends 25-10-2024 
           

            //erp2024 total_discount transaction 11-11-2024 starts
            // if($total_discount>0)
            // {
            //     $discount_account_details = get_account_details_for_invoicing("Expense",'Sales Discount');
            //     $discount_data = [
            //         'acid' => $discount_account_details['acn'],
            //         'type' => 'Asset',
            //         'cat' => 'Deliverynote',
            //         'debit' => $total_discount,
            //         'employee_id' => $this->session->userdata('id'),
            //         'date' => date('Y-m-d'),
            //         'transaction_number'=>$transaction_number,
            //         // 'invoice_number'=>$invoice_number
            //     ];
            //     $this->db->insert('cberp_transactions',$discount_data);
            //     $this->db->set('lastbal', 'lastbal + ' .$total_discount, FALSE);
            //     $this->db->where('acn', $discount_account_details['acn']);
            //     $this->db->update('cberp_accounts'); 
            // }
            // if($order_discount)
            // {
            //     $order_discount_account_details = get_account_details_for_invoicing("Expense",'Order Discount');
            //     $discount_data1 = [
            //         'acid' => $order_discount_account_details['acn'],
            //         // 'account' => $order_discount_account_details['holder'],
            //         'type' => 'Asset',
            //         'cat' => 'Deliverynote',
            //         'debit' => $order_discount,
            //         'employee_id' => $this->session->userdata('id'),
            //         'date' => date('Y-m-d'),
            //         'transaction_number'=>$transaction_number,
            //         // 'invoice_number'=>$invoice_number
            //     ];
            //     $this->db->insert('cberp_transactions',$discount_data1);
            //     $this->db->set('lastbal', 'lastbal + ' .$order_discount, FALSE);
            //     $this->db->where('acn', $order_discount_account_details['acn']);
            //     $this->db->update('cberp_accounts'); 
            // }

        if($data1['completed_status']==1)
        {
            
            $cost_of_goods_data =  [
                'acid' => $default_cost_of_goods_account,
                'type' => 'Expense',
                'cat' => 'Deliverynote',
                'debit' => $total_product_cost,
                'employee_id' => $this->session->userdata('id'),
                'date' => date('Y-m-d'),
                'transaction_number'=>$transaction_number,
            ];
            $this->db->set('lastbal', 'lastbal + ' . $total_product_cost, FALSE);
            $this->db->where('acn', $default_cost_of_goods_account);
            $this->db->update('cberp_accounts'); 
            $this->db->insert('cberp_transactions', $cost_of_goods_data);

            // Inventory transaction
            $inventory_data =  [
                'acid' => $default_inventory_account,
                'type' => 'Asset',
                'cat' => 'Deliverynote',
                'credit' => $total_product_cost,
                'employee_id' => $this->session->userdata('id'),
                'date' => date('Y-m-d'),
                'transaction_number'=>$transaction_number,
            ];
            $this->db->set('lastbal', 'lastbal - ' . $total_product_cost, FALSE);
            $this->db->where('acn', $default_inventory_account);
            $this->db->update('cberp_accounts'); 
            $this->db->insert('cberp_transactions', $inventory_data);
        }
        if($wholestatus==1)
        {
            $this->db->update('cberp_sales_orders',['converted_status'=>'1'],['id'=>$saleorderid]);   
            //erp2024 06-01-2025 detailed history log starts
            detailed_log_history('Salesorder',$saleorderid,'Converted to Deliverynote', $_POST['changedFields']);
            //erp2024 06-01-2025 detailed history log ends 
        }
        else{
            $this->db->update('cberp_sales_orders',['converted_status'=>'2'],['id'=>$saleorderid]);   
        }
        if($data1['completed_status']==1){
            //#erp2024 update customer available credit limit
            $this->load->model('transactions_model', 'transactions');
            $custdata = $this->transactions->check_customer_account_details($data1['customer_id']);
            $custcredit_limit = $custdata['credit_limit'];
            $cust_avalable_credit_limit = (!empty($custdata['avalable_credit_limit'])) ? $custdata['avalable_credit_limit']: 0;

            $subamount = $cust_avalable_credit_limit - $totalamountcust;
            $this->db->set('avalable_credit_limit', $subamount, FALSE);
            $this->db->where('id', $data1['customer_id']);
            $this->db->update('cberp_customers');
        }


        // if($data1['completed_status']==1)
        // {
            if($current_value==1)
            {
                // $this->quote->update_salesorder_status($data1['salesorder_id'], $salesorder_deltid,$last_insert_id,$data1['completed_status']);
            }
            if ($current_value !== NULL) {
                $new_value = $current_value + 1;
            } else {
                $new_value = 1;
            }           
            $this->session->set_userdata('repeatsubmit', $new_value);
            // $this->quote->update_salesorder_status($data1['salesorder_id'], $deleverynotetid,$last_insert_id,$data1['completed_status']);
        // }
        

        detailed_log_history('Deliverynote',$last_insert_id,$data1['status'], $_POST['changedFields']);
        // print_r($productlist); die();
        if ($productlist) {
            if($data1['salesorder_number'])
            {
                $number = $data1['salesorder_number'];
                $type = 'fromsales';
            }
            else{
                $number =  $data1['tid'];
                $type = 'direct';
            }
            echo json_encode(array('status' => 'Success', 'message' => 'Successfully Created', 'data' => $number, 'type' => $type));   

        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
                "Please add atleast one product in invoice $invocieno"));
            $transok = false;
        }
    }

     public function deliverynote_save_and_new_action()
    {
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);  

        //commented all accounts transactions except - inventory and cost of goods(07-02-2025)
        $masterdata = [];
        $order_discount=0;
        $tax =0;
        $transaction_number = get_latest_trans_number();
        $discountchecked = $this->input->post('discountchecked');
    
        if (isset($discountchecked)) {
            $discountchecked = $discountchecked;
        } else {
            $discountchecked = 0;
        }
        $customer_id = $this->input->post('customer_id');
        $invocieno_n = $this->input->post('invocieno_demo');
        $total_tax = 0;
        $total_discount = 0;
        $proposal = $this->input->post('proposal');
    
        $currency = $this->input->post('currency');
        $note = $this->input->post('note');
        $store_id = $this->input->post('s_warehouses');
        $ship_taxtype = $this->input->post('ship_taxtype');
        $subtotal = numberClean($this->input->post('subtotal'));
        $shipping = numberClean($this->input->post('shipping'));
        $shipping_tax = numberClean($this->input->post('ship_tax'));
        if ($ship_taxtype == 'incl') $shipping = $shipping - $shipping_tax;
        $refer = $this->input->post('refer', true);
        $total = numberClean($this->input->post('total'));
        $order_discount = (numberClean($this->input->post('order_discount'))) ? numberClean($this->input->post('order_discount')) : 0.00;

        
        // 24-10-2024 erp2024 newlyadded fields
        $customer_po_reference = $this->input->post('customer_po_reference', true);
        $customer_contact_person = $this->input->post('customer_contact_person', true);
        $customer_contact_number = $this->input->post('customer_contact_number', true);
        $customer_contact_email = $this->input->post('customer_contact_email', true);
        $delivery_note_number = $this->input->post('delivery_note_number', true);
        $created_date = $this->input->post('created_date', true);
        $created_date = ($created_date) ? $created_date : date('Y-m-d');
        // 24-10-2024 erp2024 newlyadded fields
       
        
        $shop_type = $this->input->post('shoptype');
        
        //    echo $customer_id; die();
        if ($customer_id == 0) {
            echo json_encode(array('status' => 'Error', 'message' =>
                $this->lang->line('Please add a new client')));
            exit;
        }
    
        $masterdata = [
            'tid' => $invocieno_n,
            'delnote_seq_number' => 1,
            'delnote_number' => $invocieno_n,
            'created_date' => $created_date,
            'created_time' => date('H:i:s'),
            'shipping' => $shipping,
            'tax' => $tax,
            'note' => $note,
            'status' => 'Assigned',
            'customer_id' => $customer_id,
            'employee_id' => $this->session->userdata('id'),
            'store_id' => $store_id,
            'refer' => $refer,
            'shop_type' => $shop_type,
            'customer_contact_person' => $customer_contact_person,
            'customer_contact_number' => $customer_contact_number,
            'customer_contact_email' => $customer_contact_email,
            'delivery_note_number' => $delivery_note_number,
            'order_discount' => $order_discount,
            'transaction_number'=>$transaction_number,
            
        ];

        if($shop_type=='Retail Shop'){
            $masterdata['status'] = "Completed";
            $masterdata['pick_ticket_status'] = '1';
            $masterdata['pick_item_recieved_status'] = '1';
            $masterdata['completed_status'] = '1';
        }
       $existresult = $this->deliverynote->deliverynote_already_exist_or_not($invocieno_n);
       if($existresult > 0)
       {
            $this->db->update('cberp_delivery_notes',$masterdata,['delevery_note_id'=>$existresult]);
            $insert_id = $existresult;

       }
       else{
            $this->db->insert('cberp_delivery_notes',$masterdata);
            $insert_id = $this->db->insert_id();
             // file upload section starts 22-01-2025
             if($_FILES['upfile'])
             {
                 upload_files($_FILES['upfile'], 'Deliverynote',$insert_id);
             }
             // file upload section ends 22-01-2025
            //erp2024 06-01-2025 detailed history log starts
            detailed_log_history('Deliverynote',$insert_id,'Created', $_POST['changedFields']);
            //erp2024 06-01-2025 detailed history log ends 
            //insert to tracking table
            $this->db->insert('cberp_transaction_tracking',['deliverynote_id'=>$insert_id,'deliverynote_number'=>$invocieno_n]);
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
        $total_product_qty = array_sum($product_qty);
        // $product_wise_discount = order_discount_percentage_for_single_product($order_discount,$total_product_qty);
        $total_records  = count($product_id);
        $product_wise_order_discount = ($order_discount>0) ?round(($order_discount/$total_records),2):0;
        //erp@2024 new field 06-12-2024
        $income_account_number = $this->input->post('income_account_number', true);
        $expense_account_number = $this->input->post('expense_account_number', true);
        $product_cost = $this->input->post('product_cost', true);
        $product_tax =0;
        $grandtotal=0;
        $grandprice=0;

        // erp2024 19-12-2024 load default accounts
        $default_cost_of_goods_account = default_chart_of_account('cost_of_goods_solid');
        $default_inventory_account = default_chart_of_account('inventory');
       $i=0;
       $cost_of_goods_data = [];
        //    $cost_data_list = [];
       $grand_product_cost = 0;
       $grand_inventory = 0;
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
                    'delevery_note_id' => $insert_id,
                    'delnote_number' => $invocieno_n,
                    'product_id' => $product_id[$key],
                    'product' => $product_name1[$key],
                    'product_qty' => numberClean($product_qty[$key]),
                    'product_code' => $code[$key],
                    'product_price' => numberClean($product_price[$key]),
                    'product_tax' => $product_tax,
                    'product_discount' => $discountamount,
                    'subtotal' => numberClean($product_subtotal[$key]),
                    'total_tax' => numberClean($ptotal_tax[$key]),
                    'total_discount' => numberClean($ptotal_disc[$key]),
                    'unit' => $product_unit[$key],
                    'discount_type' => $discount_type[$key],
                    'product_cost' => $product_cost[$key]
                    
    
                );
                

                $actulprice = numberClean($product_price[$key])*numberClean($product_qty[$key]);
                $productprice = numberClean($product_subtotal[$key])-numberClean($product_wise_order_discount);
                $grandprice +=  numberClean($actulprice);
               
                if($shop_type=='Retail Shop'){
                    
             

                    $pid = numberClean($product_id[$key]);
                    $qty = numberClean($product_qty[$key]);
                    $this->db->select('qty');
                    $this->db->from('cberp_products');
                    $this->db->where('pid', $pid);
                    $prdQry = $this->db->get();        
                    $prdresult = $prdQry->row_array();

                    $this->update_warehouse_products($pid, $store_id, $qty);
                    if ($prdresult) {
                        $onhand = intval($prdresult['qty']);
                        $updateQty = $onhand - $qty;          
                        $upqty = array('quantity' => $updateQty);
                        $this->db->where('pid', $pid);
                        $this->db->update('cberp_products', $upqty);
                    }
                    //erp2024 data insert to average cost 25-02-2025
                    insert_data_to_average_cost_table($product_id[$key], $product_cost[$key],numberClean($product_qty[$key]), get_costing_transation_type("Sales"));

                    //commented on 07-02-2025
                    // $producttransdata =  [
                    //     'acid' => $income_account_number[$key],
                    //     'type' => 'Asset',
                    //     'cat' => 'Deliverynote',
                    //     // 'credit' => $productprice,
                    //     'credit' => $actulprice,
                    //     'employee_id' => $this->session->userdata('id'),
                    //     'date' => date('Y-m-d'),
                    //     'transaction_number'=>$transaction_number,
                    // ];
                    // $this->db->set('lastbal', 'lastbal - ' . $actulprice, FALSE);
                    // $this->db->set('lastbal', 'lastbal - ' . $productprice, FALSE);

                    // $this->db->where('acn', $income_account_number[$key]);
                    // $this->db->update('cberp_accounts'); 
                    // $this->db->insert('cberp_transactions', $producttransdata);

                    // cost of goods transaction
                    $productQuantity = numberClean($product_qty[$key]);
                    $total_product_cost = $product_cost[$key]*intval($productQuantity);
                    $grand_product_cost += numberClean($total_product_cost);
                                       

                }
                $flag = true;
                $productlist[$prodindex] = $data;
            }   
            $i++;
            $prodindex++;
            $amt = numberClean($product_qty[$key]);
            $itc += $amt;
            $grandtotal += numberClean($product_subtotal[$key]); 
        }



        if ($prodindex > 0) {
            $grandtotal = $grandtotal - $order_discount;
            $order_discount_percentage = order_discount_percentage($order_discount,$grandprice);
            $this->db->delete('cberp_delivery_note_items', ['delevery_note_id'=>$insert_id]);
            $this->db->insert_batch('cberp_delivery_note_items', $productlist);
            // $this->db->insert_batch('cberp_average_cost', $cost_data_list);
            $this->db->set(array('discount' => numberClean(amountFormat_general($total_discount), $currency, $this->aauth->get_user()->loc), 'tax' => numberClean(amountFormat_general($total_tax), $currency, $this->aauth->get_user()->loc),  'subtotal'=>$grandtotal,'total_amount'=>$grandtotal,'order_discount_percentage'=>$order_discount_percentage));
            $this->db->where('delevery_note_id', $insert_id);
            $this->db->update('cberp_delivery_notes');
            if($shop_type=='Retail Shop'){
                
                //commented all accounts transactions except - inventory and cost of goods(07-02-2025)
                // cost of goods transactions transaction 07-02-2025
                $cost_of_goods_data =  [
                    'acid' => $default_cost_of_goods_account,
                    'type' => 'Expense',
                    'cat' => 'Deliverynote',
                    'debit' => $grand_product_cost,
                    'employee_id' => $this->session->userdata('id'),
                    'date' => date('Y-m-d'),
                    'transaction_number'=>$transaction_number,
                ];
                $this->db->set('lastbal', 'lastbal + ' . $grand_product_cost, FALSE);
                $this->db->where('acn', $default_cost_of_goods_account);
                $this->db->update('cberp_accounts'); 
                $this->db->insert('cberp_transactions', $cost_of_goods_data);
                // Inventory transaction 07-02-2025
                $inventory_data =  [
                    'acid' => $default_inventory_account,
                    'type' => 'Asset',
                    'cat' => 'Deliverynote',
                    'credit' => $grand_product_cost,
                    'employee_id' => $this->session->userdata('id'),
                    'date' => date('Y-m-d'),
                    'transaction_number'=>$transaction_number,
                ];
                $this->db->set('lastbal', 'lastbal - ' . $grand_product_cost, FALSE);
                $this->db->where('acn', $default_inventory_account);
                $this->db->update('cberp_accounts'); 
                $this->db->insert('cberp_transactions', $inventory_data);


                // erp2024 transactions starts 06-12-2024
               //$grandprice grand total price of all products without discount
                // $invoice_receivable_account_details = default_chart_of_account('accounts_receivable');
                // $latest_total = $total;
                // $receivable_data = [
                //     'acid' => $invoice_receivable_account_details,
                //     'type' => 'Asset',
                //     'cat' => 'Deliverynote',
                //     'debit' => $grandprice,
                //     // 'debit' => $total,
                //     'employee_id' => $this->session->userdata('id'),
                //     'date' => date('Y-m-d'),
                //     'transaction_number'=>$transaction_number,
                // ];
                // $this->db->insert('cberp_transactions',$receivable_data);
                // $this->db->set('lastbal', 'lastbal + ' .$total, FALSE);
                // $this->db->where('acn', $invoice_receivable_account_details);
                // $this->db->update('cberp_accounts'); 
                // erp2024 transactions ends 06-12-2024

                //erp2024 total_discount transaction 06-12-2024 starts
                // if($total_discount>0)
                // {
                //     $discount_account_details = default_chart_of_account('sales_discount');
                //     $discount_data = [
                //         'acid' => $discount_account_details,
                //         'type' => 'Asset',
                //         'cat' => 'Deliverynote',
                //         'debit' => $total_discount,
                //         'employee_id' => $this->session->userdata('id'),
                //         'date' => date('Y-m-d'),
                //         'transaction_number'=>$transaction_number,
                //         // 'invoice_number'=>$invoice_number
                //     ];
                //     $this->db->insert('cberp_transactions',$discount_data);
                //     $this->db->set('lastbal', 'lastbal + ' .$total_discount, FALSE);
                //     $this->db->where('acn', $discount_account_details);
                //     $this->db->update('cberp_accounts'); 

                    
                //     $total_discount_credit = [
                //         'acid' => $invoice_receivable_account_details,
                //         'type' => 'Asset',
                //         'cat' => 'Deliverynote',
                //         'credit' => $total_discount,
                //         'employee_id' => $this->session->userdata('id'),
                //         'date' => date('Y-m-d'),
                //         'transaction_number'=>$transaction_number,
                //         // 'invoice_number'=>$invoice_number
                //     ];
                //     $this->db->insert('cberp_transactions',$total_discount_credit);
                //     $this->db->set('lastbal', 'lastbal - ' .$total_discount, FALSE);
                //     $this->db->where('acn', $invoice_receivable_account_details);
                //     $this->db->update('cberp_accounts'); 
                // }
                // if($order_discount)
                // {
                //     $order_discount_account_number = default_chart_of_account('order_discount');
                //     $discount_data1 = [
                //         'acid' => $order_discount_account_number,
                //         // 'account' => $order_discount_account_details['holder'],
                //         'type' => 'Asset',
                //         'cat' => 'Deliverynote',
                //         'debit' => $order_discount,
                //         'employee_id' => $this->session->userdata('id'),
                //         'date' => date('Y-m-d'),
                //         'transaction_number'=>$transaction_number,
                //         // 'invoice_number'=>$invoice_number
                //     ];

                //     $this->db->insert('cberp_transactions',$discount_data1);
                //     $this->db->set('lastbal', 'lastbal + ' .$order_discount, FALSE);
                //     $this->db->where('acn', $order_discount_account_number);
                //     $this->db->update('cberp_accounts'); 

                //     $order_discount_data_credit = [
                //         'acid' => $invoice_receivable_account_details,
                //         'type' => 'Asset',
                //         'cat' => 'Deliverynote',
                //         'credit' => $order_discount,
                //         'employee_id' => $this->session->userdata('id'),
                //         'date' => date('Y-m-d'),
                //         'transaction_number'=>$transaction_number,
                //         // 'invoice_number'=>$invoice_number
                //     ];
                //     $this->db->insert('cberp_transactions',$order_discount_data_credit);
                //     $this->db->set('lastbal', 'lastbal - ' .$order_discount, FALSE);
                //     $this->db->where('acn', $invoice_receivable_account_details);
                //     $this->db->update('cberp_accounts');
                // }


            }
            
        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
                "Please choose product from product list. Go to Item manager section if you have not added the products."));
            $transok = false;
        } 
        //sales order items ends
    
    
        // $this->db->trans_start();
        $flag = false;
        $transok = true;
        
        echo json_encode(array('status' => 'Success','id'=>$insert_id));
            $transok = false;
        if ($transok) {
            $this->db->trans_complete();
        } else {
            $this->db->trans_rollback();
        }
    }

  
    public function deliverynoteeditaction()
    {
       
 
        //session value
        $current_value = $this->session->userdata('repeatsubmit');
        $deleverynotetid = $this->input->post('invocieno');
        $data1['total_amount'] = rev_amountExchange_s($this->input->post('total'), $currency, $this->aauth->get_user()->loc);
        $data1['subtotal'] = rev_amountExchange_s($this->input->post('total'), $currency, $this->aauth->get_user()->loc);;
        $data1['salesorder_id'] = numberClean($this->input->post('salesorder_id'));
        $current_status = $this->input->post('status');        
        $store_id = $this->input->post('store_id');
        $data1['store_id'] = $this->input->post('store_id');
        $data1['completed_status'] = $this->input->post('completed_status');
        $salesorder_number = $this->input->post('refer1');
        if($data1['completed_status']==1){
            $data1['status'] = 'Assigned';
        }
        else{
            $data1['status'] = 'Draft';
        }
        // echo "<pre>"; print_r($deleverynotetid); die();
        $this->db->delete('cberp_delivery_note_items',['delevery_note_id'=>$deleverynotetid]);
        

        // $this->db->update('cberp_delivery_notes', $data1,['salesorder_number'=>$data1['salesorder_number'],'tid'=>$data1['tid']]);
        // $this->db->delete('cberp_delivery_note_items',['delevery_note_id'=>$existingdeliverynoteid]);
        // $last_insert_id =  $existingdeliverynoteid;
        

        // $this->session->set_userdata("deliverymaster", $last_insert_id);

        $i = 0;

        $flag = false;
        $transok = true;


        //Product Data
        $pid = $this->input->post('pid');
        $productlist = array();

        $prodindex = 0;
        $product_id = $this->input->post('pid');
        $product_name1 = $this->input->post('product_name', true);
        $product_qty = $this->input->post('product_qty');
        $old_product_qty = $this->input->post('old_product_qty');
        $product_price = $this->input->post('product_price');
        $product_tax = $this->input->post('product_tax');
        $product_discount = $this->input->post('product_discount');
        $product_subtotal = $this->input->post('product_subtotal');
        $ptotal_tax = $this->input->post('taxa');
        $ptotal_disc = $this->input->post('disca');
        $product_des = $this->input->post('product_description', true);
        $product_hsn = $this->input->post('hsn');
        $product_unit = $this->input->post('unit');
        $discount_type = $this->input->post('discount_type');
        $tid = $this->session->userdata('salesorderid');
        $total_discount =0;
        $total_tax  =0;
        foreach ($pid as $key => $value) {
            $status ="";
            $total_discount += numberClean(@$ptotal_disc[$key]);
            $total_tax += numberClean($ptotal_tax[$key]);
            foreach ($statusList as $item) {            
                if($item['pid']==$product_id[$key]){
                    $status = $item['status'];
                }            
            }

            // $upstatus = array('status' => "delivered");                    
            // $this->db->where('tid', $tid);
            // $this->db->where_in('pid', $pid);
            // $this->db->update('cberp_sales_orders_items', $upstatus);
            if(($data1['completed_status']==1) && (numberClean($product_qty[$key])<1)){
                continue;
            }
            $data = array(
                'salesorder_id' => numberClean($data1['salesorder_id']),
                'delevery_note_id' => $deleverynotetid,
                'product' => $product_name1[$key],
                'product_id' => numberClean($product_id[$key]),
                'product' => $product_name1[$key],
                'product_code' => $product_hsn[$key],
                'product_qty' => numberClean($product_qty[$key]),
                'salesorder_product_qty' => numberClean($old_product_qty[$key]),
                'product_price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
                'product_tax' => numberClean($product_tax[$key]),
                'product_discount' => numberClean($product_discount[$key]),
                'subtotal' => rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc),
                'total_tax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                'total_discount' => rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc),
                'unit' => $product_unit[$key],
                'discount_type' => $discount_type[$key]
            );

            $flag = true;
            $productlist[$prodindex] = $data;
            $i += numberClean($product_qty[$key]);
            $prodindex++;

            $this->db->insert('cberp_delivery_note_items', $data);

             //erp2024 invetory adjusted 25-08-2024
             if($data1['completed_status']==1)
             {
                 $pid = numberClean($product_id[$key]);
                 $qty = numberClean($product_qty[$key]);
                 $this->db->select('qty');
                 $this->db->from('cberp_products');
                 $this->db->where('pid', $pid);
                 $prdQry = $this->db->get();        
                 $prdresult = $prdQry->row_array();
                 $this->update_warehouse_products($pid, $store_id, $qty);
                 if ($prdresult) {
                     $onhand = intval($prdresult['qty']);
                     $updateQty = $onhand - $qty;          
                     $upqty = array('quantity' => $updateQty);
                     $this->db->where('pid', $pid);
                     $this->db->update('cberp_products', $upqty);
                 }
             }  

             // erp2024 quote items update 20-08-2024
             
            if($data1['completed_status']==1)
            {
                $saleorderid = $data1['salesorder_id'];
                $salesorderitems = $this->quote->salesorder_details_for_multipledeliverynotes($saleorderid,$product_id[$key]);
                $remainigqty = $salesorderitems['del_remaining_qty'];
                $deliveredqty = $salesorderitems['del_delivered_qty'];
                $transferedqty = $salesorderitems['del_transfered_qty'];
                $orderedqty = $salesorderitems['ordered_quantity'];
                $actualqty = $salesorderitems['qty'];
                $delveredqty = numberClean($salesorderitems['del_delivered_qty']) + numberClean($product_qty[$key]);
                $transferqty = (numberClean($product_qty[$key]) + numberClean($salesorderitems['del_transfered_qty'])); 

                if($actualqty <= $transferqty){
                    $prdstatus = 1;
                }
                else{
                    $prdstatus = 0;  
                }
                $delnote_qty = (numberClean($actualqty) - numberClean($product_qty[$key])>0) ? numberClean($actualqty) - numberClean($product_qty[$key]) :0;
                $salesdata = array(
                    'del_remaining_qty' => $delnote_qty,
                    'del_delivered_qty' => numberClean($product_qty[$key]),
                    'del_transfered_qty' =>  numberClean($product_qty[$key]),
                    'quantity' =>  $delnote_qty,
                    'prdstatus' => $prdstatus
                );

                
                if($current_value==1)
                {
                    $this->db->update('cberp_sales_orders_items',$salesdata,['tid'=>$saleorderid, 'pid'=>$product_id[$key]]);   
                }  
            }

        }

        $data1['discount'] = $total_discount;
        $data1['tax'] = $total_tax;

 
        $this->db->update('cberp_delivery_notes', $data1,['delevery_note_id'=>$deleverynotetid]);
        // if($data1['completed_status']==1)
        // {
            // if($current_value==1)
            // {
            //     $this->quote->update_salesorder_status($data1['salesorder_id'], $salesorder_deltid,$last_insert_id,$data1['completed_status']);
            // }
            if ($current_value !== NULL) {
                $new_value = $current_value + 1;
            } else {
                $new_value = 1;
            }           
            $this->session->set_userdata('repeatsubmit', $new_value);
            // $this->quote->update_salesorder_status($data1['salesorder_id'], $deleverynotetid,$last_insert_id,$data1['completed_status']);
        // }
        
        
        // print_r($productlist); die();
        if ($productlist) {
            echo json_encode(array('status' => 'Success', 'message' => 'Successfully Created', 'data' => $salesorder_number));   

        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
                "Please add atleast one product in invoice $invocieno"));
            $transok = false;
        }
    }

    //warehosu inventory updation #erp2024 25-08-2024
    public function update_warehouse_products($product_code, $store_id, $qty,$action="")
    {
        $this->db->select('stock_quantity');
        $this->db->from('cberp_product_to_store');
        $this->db->where('product_code', $product_code);
        $this->db->where('store_id', $store_id);
        $warehouseQry = $this->db->get(); 
        $warehouseresult = $warehouseQry->row_array();
        $onhandwh = intval($warehouseresult['stock_quantity']);
        if($action)
        {
            $onhandwhQty = $onhandwh + $qty; 
        }
        else{
            $onhandwhQty = $onhandwh - $qty; 
        }

        

        $upqty1 = array('stock_quantity' => $onhandwhQty);
        $this->db->where('product_code', $product_code);
        $this->db->where('store_id', $store_id);
        $this->db->update('cberp_product_to_store', $upqty1);
    }




    public function about()
    {
        $head['title'] = "About us";
        $this->load->view('fixed/header', $head);
        $this->load->view('quotes/about',);
        $this->load->view('fixed/footer');
    }

    //erp2024 18-07-2024
    public function quote_approval()
    {
        
        $this->load->model('customers_model', 'customers');
        $data['customergrouplist'] = $this->customers->group_list();
        $tid = intval($this->input->get('id'));
        $data['id'] = $tid;
        $data['terms'] = $this->quote->billingterms();
        $data['invoice'] = $this->quote->quote_details($tid);
        $data['products'] = $this->quote->quote_products($tid);
        $data['currency'] = $this->quote->currencies();
        $head['title'] = "Edit Quote #" . $data['invoice']['tid'];
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['warehouse'] = $this->quote->warehouses();
        $this->load->model('plugins_model', 'plugins');
        $data['exchange'] = $this->plugins->universal_api(5);
        $this->load->library("Common");
        $data['trackingdata'] = tracking_details('quote_id',$tid);
        $data['authrizationdata'] = $this->authorization_approval->authorization_details_byid($tid,'Quote');
        $data['taxlist'] = $this->common->taxlist_edit($data['invoice']['tax_status']);
        $data['configurations'] = $this->configurations;
        $this->load->view('fixed/header', $head);
        $this->load->view('quotes/quote_approval', $data);
        $this->load->view('fixed/footer');
    }

    public function approvalaction()
    {
        
        $customer_id = $this->input->post('customer_id');
        $invocieno_n = $this->input->post('invocieno');
        $invocieno = $this->input->post('iid');
        $invoicedate = $this->input->post('invoicedate');
        $invocieduedate = $this->input->post('invocieduedate');
        $notes = $this->input->post('notes', true);
        $tax = $this->input->post('tax_handle');
        $total_tax = 0;
        $total_discount = 0;
        $discountFormat = $this->input->post('discountFormat');
        $pterms = $this->input->post('pterms');
        $propos = $this->input->post('propos');
        $currency = $this->input->post('mcurrency');
        $ship_taxtype = $this->input->post('ship_taxtype');
        $subtotal = rev_amountExchange_s($this->input->post('subtotal'), $currency, $this->aauth->get_user()->loc);
        $shipping = rev_amountExchange_s($this->input->post('shipping'), $currency, $this->aauth->get_user()->loc);
        $shipping_tax = rev_amountExchange_s($this->input->post('ship_tax'), $currency, $this->aauth->get_user()->loc);
        if ($ship_taxtype == 'incl') $shipping = $shipping - $shipping_tax;
        $refer = $this->input->post('refer', true);
        $total = rev_amountExchange_s($this->input->post('total'), $currency, $this->aauth->get_user()->loc);

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

        
        $product_id = $this->input->post('pid');
        $product_name1 = $this->input->post('product_name', true);
        $product_qty = $this->input->post('product_qty');
        $product_price = $this->input->post('product_price');
        $product_tax = $this->input->post('product_tax');
        $product_discount = $this->input->post('product_discount');
        $product_subtotal = $this->input->post('product_subtotal');
        $ptotal_tax = $this->input->post('taxa');
        $ptotal_disc = $this->input->post('disca');
        $product_des = $this->input->post('product_description', true);
        $product_hsn = $this->input->post('hsn');
        $product_unit = $this->input->post('unit');
        
        //erp2024 new function for auth approval
        $oldtotal = rev_amountExchange_s($this->input->post('oldtotal'), $currency, $this->aauth->get_user()->loc);
        $authdata = [
            'authorized_amount' => rev_amountExchange_s($this->input->post('authorized_amount')),
            'status' => $this->input->post('statusType'),
            'comments' => $this->input->post('comments'),
            'authorized_date' => date("Y-m-d H:i:s"),
            'authorized_by' => $this->session->userdata('id'),
            'authorized_type' => 'Reported Person',
        ];

        $this->db->where('function_id',$this->input->post('iid'));
        $this->db->where('function_type','Quote');
        $this->db->update('authorization_history', $authdata);
        if($this->input->post('statusType')=="Approve"){
            $approvflg = '1';
        }
        else if($this->input->post('statusType')=="Hold"){
            $approvflg = '2';
        }
        else{
            $approvflg = '3';
        }
        $this->db->where('id',$this->input->post('iid'));
      
        $this->db->update('cberp_quotes', ['approvalflg'=> $approvflg]);
        $targeturl = base_url("authorization_approval");
        if($oldtotal!=$total)
        {
            $this->db->trans_start();
            if(!empty($pid)){
                $this->db->delete('cberp_quotes_items', array('tid' => $invocieno));
                foreach ($pid as $key => $value) {

                    $total_discount += numberClean(@$ptotal_disc[$key]);
                    $total_tax += numberClean($ptotal_tax[$key]);

                    $data = array(
                        'tid' => $invocieno,
                        'pid' => $product_id[$key],
                        'product' => $product_name1[$key],
                        'product_code' => $product_hsn[$key],
                        'quantity' => numberClean($product_qty[$key]),
                        'price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
                        'tax' => numberClean($product_tax[$key]),
                        'discount' => numberClean($product_discount[$key]),
                        'subtotal' => rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc),
                        'total_tax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                        'total_discount' => rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc),
                        'product_des' => $product_des[$key],
                        'unit' => $product_unit[$key]
                    );

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

            $data = array('invoicedate' => $bill_date, 'due_date' => $bill_due_date, 'subtotal' => $subtotal, 'shipping' => $shipping, 'ship_tax' => $shipping_tax, 'ship_tax_type' => $ship_taxtype, 'discount' => $total_discount, 'tax' => $total_tax, 'total' => $total, 'notes' => $notes, 'customer_id' => $customer_id, 'items' => $i, 'tax_status' => $tax, 'discount_status' => $discount_status, 'format_discount' => $discountFormat, 'refer' => $refer, 'term' => $pterms, 'customer_message' => $propos, 'multi' => $currency);
            $this->db->set($data);
            $this->db->where('id', $invocieno);

            if ($flag) {

                if ($this->db->update('cberp_quotes', $data)) {
                    $this->db->insert_batch('cberp_quotes_items', $productlist);
                    // echo json_encode(array('status' => 'Success', 'message' =>
                    //     $this->lang->line('Quote has  been updated') . " <a href='view?id=$invocieno' class='btn btn-info btn-sm'><span class='icon-file-text2' aria-hidden='true'></span> View </a> "));
                } else {
                    echo json_encode(array('status' => 'Error', 'message' =>
                        $this->lang->line('ERROR')));
                    $transok = false;
                }


            }
            

            if ($transok) {
                $this->db->trans_complete();
            } else {
                $this->db->trans_rollback();
            }
            
        }//if ends
        echo json_encode(array('status' => 'Success', 'message' =>$this->lang->line('Authorization request has been approved') . " <a href='".$targeturl."' class='btn btn-info btn-sm'><span class='icon-file-text2' aria-hidden='true'></span> View </a> "));

    }

    public function product_history(){
        $response = $this->quote->product_history($this->input->post('product_id'), $this->input->post('customer_id'));
        echo json_encode(array('status' => 'Success', 'data'=>$response));
    }

    #erp2024 08-08-2024
    public function salesorder_new()
    {
       
        $token = intval($this->input->get('token'));
        if($token==1)
        {
            $quote_id = intval($this->input->get('id'));
         
            $data['salesorder_num'] = $this->quote->salesorder_number();
            
            $head['title'] = "Sales Order Landing";
            // $head['title'] = "Sales Order Landing #" . $data['salesorder_num']+1000;
            $head['usernm'] = $this->aauth->get_user()->username;
            $data['quoteid'] = $quote_id;
            $data['terms'] = $this->quote->billingterms();
            $data['customer'] = $this->quote->get_customer_by_quoteid($quote_id);
            $data['currency'] = $this->quote->currencies();
            // $head['usernm'] = $this->aauth->get_user()->username;
            $this->load->model('plugins_model', 'plugins');
            $data['exchange'] = $this->plugins->universal_api(5);
            $this->load->library("Common");
                            
            $data['configurations'] = $this->configurations;
            $masterdata = $this->quote->get_quote_details($quote_id);

            // echo "<pre>";  print_r($masterdata); die();
            $sequentialdata = $this->quote->get_sales_seqnumber_tid($quote_id);
            $data['newsalesordernumber'] = $sequentialdata['newsalesordernumber'];
            $data['salesseqnumber'] = $sequentialdata['salesseqnumber'];
            $data['invoice'] = $this->quote->quote_details_byquoteid($quote_id);

          

            $data['taxlist'] = $this->common->taxlist_edit($data['invoice']['tax_status']);
            $data['convertedflg'] = $this->quote->check_quote_converted_stage($quote_id);
            $productdata = [];
            $salesorders = [];
            if(!empty($masterdata)){
                foreach ($masterdata as $row) {
                    $pid = $row['pid'];
                    $quote_id = $row['quote_id'];
                    $productdata[$pid] = array(
                        'lead_id' => $row['lead_id'],
                        'leadqty' => $row['leadqty'],
                        'remaining_quantity' => $row['remaining_quantity'],
                        'leaddate' => $row['leaddate'],
                        'productid' => $pid,
                        'quote_id' => $quote_id,
                        'product' => $row['product'],
                        'code'     => $row['code'],
                        'quoteqty' => $row['quoteqty'],
                        'quoterate' => $row['quoterate'],
                        'currentrate' => $row['currentrate'],
                        'quotedate' => $row['quotedate'],
                        'product_price'        => $row['product_price'],
                        'product_lowest_price' => $row['product_lowest_price'],
                        'product_max_discount' => $row['product_max_discount'],
                        'salestid'             => $row['salestid'],
                        'salesseqnumber'       => $row['salesseqnumber']
                    );                
                    
                }
            }
            $saleorderdata = $this->quote->get_salesorder_against_quote($quote_id, $pid);
            if(!empty($saleorderdata)){
                foreach ($saleorderdata as $sdata) {
                    // if($row['pid'] == $sdata['salesprdid'])
                    // {
                        $prd_id = $sdata['salesprdid'];
                        $salesorders[$prd_id][$quote_id][] = array(
                            'salesprdid' => $sdata['salesprdid'],
                            'salesorderqty' => $sdata['salesorderqty'],
                            'salesorderid' => $sdata['salesorderid'],
                            'salesorderdate' => $sdata['salesorderdate'],
                            'salesordernumber' => $sdata['salesordernumber'],
                            'subtotal'         => $sdata['subtotal'],
                            'salestotaldiscount'  => $sdata['salestotaldiscount'],
                            'salesdiscount'       => $sdata['salesdiscount'],
                            'completedstatus'       => $sdata['completedstatus'],
                            'convertedstatus'       => $sdata['convertedstatus'],
                            'salesdiscounttype'   => ($sdata['salesdiscounttype']=="Amttype")?'Amt':'%'
                        );
                    // }
                }
            }

           
            $data['trackingdata'] = tracking_details('quote_id',intval($this->input->get('id')));
            // print_r($productdata); die(); 
            $data['salesorders'] = $salesorders;
            $data['productdata'] = $productdata;
            $this->load->view('fixed/header', $head);
            $this->load->view('sales/new-salesorder-from-partial-quote', $data);
            $this->load->view('fixed/footer');
        }
        else if($token==2){ 
            //Quote to sales order convertion
            $this->load->model('customers_model', 'customers');
            $data['customergrouplist'] = $this->customers->group_list();
            $tid = intval($this->input->get('id'));
            $data['id'] = $tid;
            $data['quote_id'] = $this->quote->get_quote_number_from_salesorder($tid);
            $quote_id = $data['quote_id'];
            $data['trackingdata'] = tracking_details('quote_id',$quote_id);

            $this->session->set_userdata('orderid', $tid);
            $data['terms'] = $this->quote->billingterms();
            $data['invoice'] = $this->quote->quote_details_byid($tid);
            $data['products'] = $this->quote->salesorder_products($tid);
            // echo "<pre>"; print_r($data['products']); die();
            $data['customer'] = $this->quote->get_customer_by_quoteid($quote_id);
            //  echo "<pre>";  print_r($data['customer']); die();
            $data['salesorder_num'] = $this->quote->salesorder_number();
            $data['currency'] = $this->quote->currencies();
            // $head['title'] = "Quote To Sales Order #" . $data['invoice']['tid'];
            $head['title'] = "Sales Order Landing";
            $head['usernm'] = $this->aauth->get_user()->username;
            $data['warehouse'] = $this->quote->warehouses();
            $this->load->model('plugins_model', 'plugins');
            $data['exchange'] = $this->plugins->universal_api(5);
            $this->load->library("Common");
            $data['taxlist'] = $this->common->taxlist_edit($data['invoice']['tax_status']);                
            $data['configurations'] = $this->configurations;
            $this->load->view('fixed/header', $head);
            $this->load->view('sales/quotetosalesorder', $data);
            $this->load->view('fixed/footer');
        }

        else if($token==3)
        {
            //create new sales order
            
        }

        else {
            $this->load->model('customers_model', 'customers');
            $data['customergrouplist'] = $this->customers->group_list();
            $tid = intval($this->input->get('id'));
            $data['id'] = $tid;
            $data['quote_id'] = $this->quote->get_quote_number_from_salesorder($tid);
            $quote_id = $data['quote_id'];
            
            $data['trackingdata'] = tracking_details('quote_id',$quote_id);

            $this->session->set_userdata('orderid', $tid);
            $data['terms'] = $this->quote->billingterms();
            $data['invoice'] = $this->quote->quote_details_by_saleid_quoteid($quote_id,$tid);
            $data['products'] = $this->quote->salesorder_products($tid);
            // echo "<pre>"; print_r($data['products']); die();
            $data['customer'] = $this->quote->get_customer_by_quoteid($quote_id);
            $data['salesorder_num'] = $this->quote->salesorder_number();
            $data['currency'] = $this->quote->currencies();
            // $head['title'] = "Quote To Sales Order #" . $data['invoice']['tid'];
            $head['title'] = "Sales Order Landing";
            $head['usernm'] = $this->aauth->get_user()->username;
            $data['warehouse'] = $this->quote->warehouses();
            $this->load->model('plugins_model', 'plugins');
            $data['exchange'] = $this->plugins->universal_api(5);
            $this->load->library("Common");
            $data['taxlist'] = $this->common->taxlist_edit($data['invoice']['tax_status']);                
            $data['configurations'] = $this->configurations;
            $this->load->view('fixed/header', $head);
            $this->load->view('sales/quotetosalesorder-draft', $data);
            $this->load->view('fixed/footer');
        }
       
        
    }

    public function generate_new_salesorder(){

        $prefix = $this->prifix72['salesorder_prefix'];
        $quote_id = $_POST['quote_id'];
        $quoteitems =$this->quote->get_quote_items_for_new_salesorder($quote_id);
        $i = 0;
        
        $table = '<table class="table table-bordered dataTable">';
        $table .= '<thead>';
        $table .= '<tr>';
        $table .= '<th width="7%">Item No.</th>';
        $table .= '<th width="20%">Item Name</th>';
        $table .= '<th width="1%">Lead Qty</th>';
        $table .= '<th width="1%">Quote Qty</th>';
        $table .= '<th  width="1%">Received SO Qty</th>';
        $table .= '<th width="1%">Remaining SO</th>';
        $table .= '<th width="10%" class="text-center">New SO Qty</th>';
        $table .= '<th width="12%" class="text-center">Discount</th>';
        $table .= '<th width="10%" class="text-right">Quote Price</th>';
        $table .= '<th width="7%" class="text-right">Unit Price</th>';
        $table .= '<th class="text-right">Total</th>';
        $table .= '</tr>';
        $table .= '</thead>';
        $table .= '<tbody>';
        foreach ($quoteitems as $item) {
            $unitcost = ($item['price']>0) ? round($item['subtotal'] / intval($item['orderedqty']), 2) : 0;
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
            $table .= '<td>' . $item['product_code'] . '</td>';
            $table .= '<td>' . htmlspecialchars($item['product']) . '<input type="hidden" class="form-control" name="product_name[]" id="product_name-' . $i . '"  value="' . $item['product'] . '"><input type="hidden" name="hsn[]" id="unit-' . $i . '" value="' . $item['product_code'] . '"></td>';
            $table .= '<td class="text-center">' . htmlspecialchars(intval($item['leadqty'])) . '</td>';
            $table .= '<td class="text-center">' . htmlspecialchars(intval($item['qty'])) . '<input type="hidden" class="form-control req" name="trasferedqty[]" id="trasferedqty-' . $i . '" value="' .intval($item['trasferedqty']) . '"><input type="hidden" class="form-control req" name="orderedqty[]" id="orderedqty-' . $i . '" value="' .intval($item['qty']) . '"></td>';
            $table .= '<td class="text-center">' . htmlspecialchars(intval($item['trasferedqty'])) . '<input type="hidden" class="form-control req" name="trasferedqty[]" id="trasferedqty-' . $i . '" value="' .intval($item['trasferedqty']) . '"></td>';
            $table .= '<td class="text-center">' . htmlspecialchars(intval($item['remainingqty'])) . '<input type="hidden" class="form-control req" name="remainingqty[]" id="remainingqty-' . $i . '" value="' .intval($item['remainingqty']) . '"></td>';
            // $table .= '<td><input type="text" class="form-control" name="so_qty[]" value="' . htmlspecialchars($item['so_qty']) . '"></td>';
            $table .= '<td><input type="number" class="form-control req amnt" name="product_qty[]" oninput="isPositiveNumber(event, this)" id="amount-'.$i.'" onkeypress="return isNumber(event)" onkeyup="checkqty('.$i.'), rowTotal('.$i.'), billUpyog()" autocomplete="off" value="0"></td>';
            $table .= '<td class="text-center discountpotion d-none">
                    <div class="input-group text-center">
                        <select name="discount_type[]" id="discounttype-' . $i . '" class="form-control" onchange="discounttypeChange(' . $i . ')">
                                <option value="Perctype" '.$percsel.'>%</option>
                                <option value="Amttype" '.$amtsel.'>Amt</option>
                        </select>&nbsp;
                        <input type="number" min="0" class="form-control discount '.$perccls.'" name="product_discount[]" onkeypress="return isNumber(event)" id="discount-' . $i . '"  autocomplete="off" onkeyup="discounttypeChange(' . $i . ')" value="' .$disperc. '">
                        <input type="number" min="0" class="form-control discount '.$amtcls.'" name="product_amt[]" onkeypress="return isNumber(event)" id="discountamt-' . $i . '" autocomplete="off" onkeyup="discounttypeChange(' . $i . ')" value="' .$disamt. '">
                    </div>                                    
                    <strong id="discount-amtlabel-' . $i . '" class="discount-amtlabel discount-amtlabel-' . $i . '"></strong>
                    <div><strong id="discount-error-' . $i . '"></strong></div>                                    
                    </td>';

            $table .= '<td class="text-center discountpotionnotedit">
                <div class="text-center"> ';
                $table .= '<strong id="discount_type_label-' . $i . '" >' .$distype . '</strong> / '; 
                
                if($percsel!=""){
                                                    
                    $table .= '<strong id="discount_typeval_label-' . $i . '" >' .$disperc . '</strong>';
                }
                else{
                    $table .= '<strong id="discount_typeval_label-' . $i . '" >' .$disamt . '</strong>';
                }
            $table .= '</div>                                    
                <strong id="discount-amtlabel-' . $i . '" class="discount-amtlabel discount-amtlabel-' . $i . '"></strong>
                <div><strong id="discount-error-' . $i . '"></strong><input type="hidden" name="disca[]" id="disca-' . $i . '" value="0"></div>                                    
            </td>';
            $table .= '<td class="text-right"><strong id="pricelabel' . $i . '" class="pricelabel">'.($item['price']).'</strong><input type="hidden" class="form-control req prc " name="product_price[]" id="price-' . $i . '"  onkeypress="return isNumber(event)" onkeyup="rowTotal(' . $i . '), billUpyog()" autocomplete="off" value="' .$item['price'] . '" ><input type="hidden" name="unit[]" id="unit-' . $i . '" value="' . $item['unit'] . '"><input type="hidden" class="ttInput" name="product_subtotal[]" id="total-' . $i . '" value="0">';
            $table .= '<td class="text-right"><strong><span >'.$unitcost.'</span></strong>';
            $table .= '<td class="text-right"><strong><span class="ttlText" id="result-' . $i . '">0</span></strong>';
            $table .= '<input type="hidden" name="taxa[]" id="taxa-' . $i . '" value="0">
                <input type="hidden" class="pdIn" name="pid[]" id="pid-' . $i . '" value="' . $item['pid'] . '">
                <input type="hidden"  name="min_price[]" id="min_price-' . $i . '" value="' . $item['min_price'] . '">
                <input type="hidden"  name="maximum_discount_rate[]" id="maximum_discount_rate-' . $i . '" value="' . $item['maximum_discount_rate'] . '">
                <input type="hidden" class="form-control" name="lowest_price[]" id="lowestprice-' . $i . '" onkeypress="return isNumber(event)" autocomplete="off" value="' . $item['lowest_price'] . '">
                <input type="hidden" class="form-control" name="maxdiscountrate[]" id="maxdiscountrate-' . $i . '" onkeypress="return isNumber(event)" autocomplete="off" value="' . $item['maximum_discount_rate'] . '"><input type="hidden" name="hsn[]" id="unit-' . $i . '" value="' . $item['code'] . '"></td>';
            $table .= '</tr>';
            $i++;
            
            
            
        }
    
        $table .= '</tbody>';
        $table .= '</table>';
        echo $table;
    }
    

    public function salesorder_sub_action_old()
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
       
        $salesorderid = $this->input->post('salesorderid');
        $salesordertid = $this->input->post('invocieno');
        $salesorder_id = ($salesordertid-1000);
        $quote_id = $this->input->post('quote_id');
        $completed_status =  $this->input->post('completed_status');

        //insert to tracking quote salesorder_number
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
        $subtotal = rev_amountExchange_s($this->input->post('subtotal'), $currency, $this->aauth->get_user()->loc);
        $shipping = rev_amountExchange_s($this->input->post('shipping'), $currency, $this->aauth->get_user()->loc);
        $shipping_tax = rev_amountExchange_s($this->input->post('ship_tax'), $currency, $this->aauth->get_user()->loc);
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
            $discount_status = 0;
        } else {
            $discount_status = 1;
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
        $data1 = array('invoicedate' => $invoicedate, 'due_date' => $invocieduedate, 'subtotal' => $subtotal, 'shipping' => $shipping, 'ship_tax' => $shipping_tax, 'ship_tax_type' => $ship_taxtype,'total' => $total, 'notes' => $notes, 'customer_id' => $customer_id, 'tax_status' => $tax, 'discount_status' => $discount_status, 'format_discount' => $discountFormat, 'refer' => $refer, 'term' => $pterms, 'customer_message' => $propos, 'multi' => $currency, 'customer_order_date'=>$customer_order_date, 'customer_purchase_order'=>$customer_purchase_order,'salesorder_number'=>$salesorder_number, 'seq_number'=>$seq_number,'quote_id'=>$quote_id,'tid'=>$salesordertid, 'completed_status' => $completed_status,'customer_reference_number' => $this->input->post('customer_reference_number'),'customer_contact_person' => $this->input->post('customer_contact_person'),'customer_contact_number' => $this->input->post('customer_contact_number'),'customer_contact_email' => $this->input->post('customer_contact_email'));

        $this->db->insert('cberp_sales_orders', $data1);
        // die($this->db->last_query()); salesorder_number
        $insert_id = $this->db->insert_id();
        
        if($insert_id > 0)
        {
            //Product Data
            $pid = $this->input->post('pid');
            $productlist = array();
            $prodindex = 0;
            $product_id = $this->input->post('pid');
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
            
            $ordered_quantity = $this->input->post('orderedqty');
            $transfered_quantity = $this->input->post('trasferedqty');
            $delivered_quantity = $this->input->post('deliveredqty');
            $remaining_quantity = $this->input->post('remainingqty');
            $maxdiscountrate = $this->input->post('maxdiscountrate');
            $lowest_price = $this->input->post('lowest_price');
            
            // print_r($product_discount); die();
            foreach ($pid as $key => $value) {
                if(numberClean($product_qty[$key]) > 0)
                {
                    $status ="";
                    $total_discount += numberClean(@$ptotal_disc[$key]);
                    $total_tax += numberClean($ptotal_tax[$key]);
                    $discount_type_val="";      
                    
                    
                    $quoteitems = $this->quote->quote_details_for_multiplesalesorders($quote_id,$product_id[$key]);
                    $remainigqty_new = numberClean($quoteitems['remaining_quantity']) - numberClean($product_qty[$key]);
                    $remainigqty_new = ($remainigqty_new >0) ? $remainigqty_new : 0;
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
                        'tid' => $insert_id,
                        'pid' => $product_id[$key],
                        'product' => $product_name1[$key],
                        'product_code' => $product_hsn[$key],
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
                        'discount_type' => $discount_type_val,
                        'ordered_quantity'    => $ordered_quantity[$key],
                        'transfered_quantity' => (numberClean($product_qty[$key]) + numberClean($transfered_quantity[$key])),
                        'delivered_quantity'  => (numberClean($product_qty[$key]) + numberClean($transfered_quantity[$key])),
                        // 'delivered_quantity'  => numberClean($delivered_quantity[$key]) + numberClean($product_qty[$key]),
                        'remaining_quantity'  => $remainigqty_new,
                        'salesorder_number'=>$salesorder_number,
                        'maxdiscountrate' => $maxdiscountrate[$key],
                        'lowest_price' => $lowest_price[$key]                        

                    );
                    // erp2024 quote items update 06-08-2024
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
                    
                    
                    $quotedata = array(
                        'remaining_quantity' => $remainigqty_new,
                        'delivered_quantity' => $delveredqty,
                        'transfered_quantity' =>  $transferqty,
                        'prdstatus' => $prdstatus
                    );
                    $this->quote->quote_items($quote_id,$product_id[$key],$quotedata);
                    
                    $flag = true;
                    $productlist[$prodindex] = $data;
                    $i += numberClean($product_qty[$key]);;
                    $prodindex++;
                }
            }

            
            if(!empty($productlist))
            {
                $this->db->insert_batch('cberp_sales_orders_items', $productlist);
                //update quote status
                // $this->quote->update_quote_status($quote_id, $salesordertid, $salesorderid);
               
                $total_discount = rev_amountExchange_s(amountFormat_general($total_discount), $currency, $this->aauth->get_user()->loc);
                $total_tax = rev_amountExchange_s(amountFormat_general($total_tax), $currency, $this->aauth->get_user()->loc);
                $this->db->update('cberp_sales_orders', ['items' => $i,'discount' => $total_discount,'tax' => $total_tax], ['id'=> $quote_id]);     
                $this->quote->update_quote_status_for_subitems($quote_id, $salesordertid, $salesorderid);           
                echo json_encode(array('status' => 'Success'));
            }
        }
        else{
            echo json_encode(array('status' => 'Error', 'message' => 'Creation Failed'));
            exit;
        }


        if ($transok) {
            $this->db->trans_complete();
        } else {
            $this->db->trans_rollback();
        }
    }
    public function salesorder_sub_action()
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
        $salesorderid = $this->input->post('salesorderid');
        $salesordertid = $this->input->post('invocieno');
        $salesorder_id = ($salesordertid-1000);
        $quote_id = $this->input->post('quote_id');
        $completed_status =  $this->input->post('completed_status');

        
        

        //insert to tracking quote salesorder_number
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
        $subtotal = rev_amountExchange_s($this->input->post('subtotal'), $currency, $this->aauth->get_user()->loc);
        $shipping = rev_amountExchange_s($this->input->post('shipping'), $currency, $this->aauth->get_user()->loc);
        $shipping_tax = rev_amountExchange_s($this->input->post('ship_tax'), $currency, $this->aauth->get_user()->loc);
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
            $discount_status = 0;
        } else {
            $discount_status = 1;
        }

        if ($customer_id == 0) {
            echo json_encode(array('status' => 'Error', 'message' =>
                $this->lang->line('Please add a new client')));
            exit;
        }


        $data1 = array('invoicedate' => $invoicedate, 'due_date' => $invocieduedate, 'subtotal' => $subtotal, 'shipping' => $shipping, 'ship_tax' => $shipping_tax, 'ship_tax_type' => $ship_taxtype,'total' => $total, 'notes' => $notes, 'customer_id' => $customer_id, 'tax_status' => $tax, 'discount_status' => $discount_status, 'format_discount' => $discountFormat, 'refer' => $refer, 'term' => $pterms, 'customer_message' => $propos, 'multi' => $currency, 'customer_order_date'=>$customer_order_date, 'customer_purchase_order'=>$customer_purchase_order,'salesorder_number'=>$salesorder_number, 'seq_number'=>$seq_number,'quote_id'=>$quote_id,'tid'=>$salesordertid, 'completed_status' => $completed_status,'customer_reference_number' => $this->input->post('customer_reference_number'),'customer_contact_person' => $this->input->post('customer_contact_person'),'customer_contact_number' => $this->input->post('customer_contact_number'),'customer_contact_email' => $this->input->post('customer_contact_email'),'created_by'=>$this->session->userdata('id'),'created_date'=>date('Y-m-d'));

        $checkdraftres = $this->quote->salesorder_draft($salesorder_number);
        if(!empty($checkdraftres['id']))
        {
            $this->db->where('id', $checkdraftres['id']);
            $this->db->update('cberp_sales_orders', $data1);
            $insert_id = $checkdraftres['id'];
            $this->db->delete('cberp_sales_orders_items', array('tid' => $insert_id));
        }
        else{
            $this->db->insert('cberp_sales_orders', $data1);
            $insert_id = $this->db->insert_id();
        }
        detailed_log_history('Salesorder',$insert_id,'Created', $changedFields);
        
        // die($this->db->last_query());
        

        if($insert_id > 0)
        {
            //Product Data
            $pid = $this->input->post('pid');
            $productlist = array();
            $prodindex = 0;
            $product_id = $this->input->post('pid');
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
            
            $ordered_quantity = $this->input->post('orderedqty');
            $transfered_quantity = $this->input->post('trasferedqty');
            $delivered_quantity = $this->input->post('deliveredqty');
            $remaining_quantity = $this->input->post('remainingqty');

            
            $maxdiscountrate = $this->input->post('maxdiscountrate');
            $lowest_price = $this->input->post('lowest_price');
            
            // print_r($product_discount); die();
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
                    // erp2024 quote items update 06-08-2024


                    $quoteitems = $this->quote->quote_details_for_multiplesalesorders($quote_id,$product_id[$key]);
                    $remainigqty = $quoteitems['remaining_quantity'];
                    $deliveredqty = $quoteitems['delivered_quantity'];
                    $transferedqty = $quoteitems['transfered_quantity'];
                    $orderedqty = $quoteitems['ordered_quantity'];
                    // $actualqty = $quoteitems['qty'];
                    $delveredqty = numberClean($quoteitems['deliveredqty']) + numberClean($product_qty[$key]);
                    $transferqty = (numberClean($product_qty[$key]) + numberClean($quoteitems['transfered_quantity'])); 
                    $remainigqty_new = numberClean($quoteitems['remaining_quantity']) - numberClean($product_qty[$key]);
                    $remainigqty_new = ($remainigqty_new >0) ? $remainigqty_new : 0;
                    
                   

                    $data = array(
                        'tid' => $insert_id,
                        'pid' => $product_id[$key],
                        'product' => $product_name1[$key],
                        'product_code' => $product_hsn[$key],
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
                        'discount_type' => $discount_type_val,
                        'ordered_quantity'    => $ordered_quantity[$key],
                        // 'transfered_quantity' => (numberClean($product_qty[$key]) + numberClean($transfered_quantity[$key])),
                        // 'delivered_quantity'  => (numberClean($product_qty[$key]) + numberClean($transfered_quantity[$key])),
                        // // 'delivered_quantity'  => numberClean($delivered_quantity[$key]) + numberClean($product_qty[$key]),
                        // 'remaining_quantity'  => numberClean($remaining_quantity[$key]) - numberClean($product_qty[$key]),

                        // 'remaining_quantity'  => $remainigqty_new,
                        'salesorder_number'=>$salesorder_number,
                        'maximum_discount_rate' => $maxdiscountrate[$key],
                        'lowest_price'    => $lowest_price[$key]
                    );
                    
                    $prdstatus=0;
                    if($orderedqty <= $transferqty){
                        $prdstatus = 1;
                    }
                    else{
                        $prdstatus = 0;  
                    }
                    $quotedata = array(
                        'remaining_quantity' => $remainigqty_new,
                        'delivered_quantity' => $delveredqty,
                        'transfered_quantity' =>  $transferqty,
                        'prdstatus' => $prdstatus
                    );
                    $this->quote->quote_items($quote_id,$product_id[$key],$quotedata);
                   
                    $flag = true;
                    $productlist[$prodindex] = $data;
                    $i += numberClean($product_qty[$key]);;
                    $prodindex++;
                }
            }
            if(!empty($productlist))
            {
                $this->db->insert_batch('cberp_sales_orders_items', $productlist);
                // echo "<pre>"; print_r($this->db->last_query()); die();
                //update quote status
                // $this->quote->update_quote_status($quote_id, $salesordertid, $salesorderid);                
                
                $total_discount = rev_amountExchange_s(amountFormat_general($total_discount), $currency, $this->aauth->get_user()->loc);
                $total_tax = rev_amountExchange_s(amountFormat_general($total_tax), $currency, $this->aauth->get_user()->loc);
                $this->db->update('cberp_sales_orders', ['items' => $i,'discount' => $total_discount,'tax' => $total_tax], ['id'=> $insert_id]);         

                $this->quote->update_quote_status_for_subitems($quote_id, $salesordertid, $insert_id);     

                echo json_encode(array('status' => 'Success'));
            }
        }
        else{
            echo json_encode(array('status' => 'Error', 'message' => 'Creation Failed'));
            exit;
        }


        if ($transok) {
            $this->db->trans_complete();
        } else {
            $this->db->trans_rollback();
        }
    }

    public function deliverynote_reassigned()
    {

        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
        $salesorder_number = $this->input->post('salesorder_id');
        $delivery_note_number = $this->input->post('delevery_note_id');
        $grand_product_cost = 0;
        $total_product_cost = 0;
     
        if($this->input->post('completedstatus')!='Draft')
        {
            $product_cost = $this->input->post('product_cost', true);
            $product_code = $this->input->post('hsn');
            $product_qty = $this->input->post('product_qty');
            $old_product_qty = $this->input->post('old_product_qty'); //if salesorderid > 0 then salesorder qty else product_qty
            $default_cost_of_goods_account = default_chart_of_account('cost_of_goods_solid');
            $default_inventory_account = default_chart_of_account('inventory');
            $transaction_number = $this->input->post('delivery_transaction_number');
            if($product_qty)
            {
                foreach ($product_qty as $key => $value) {
                    $total_product_cost = $product_cost[$key]*numberClean($product_qty[$key]);
                    $grand_product_cost += $total_product_cost;
                    $del_delivered_qty = $product_qty[$key];

                    if($salesorder_number)
                    {
                        $this->db->set('deliverynote_delivered_quantity', 'deliverynote_delivered_quantity - ' . $del_delivered_qty, FALSE);
                        $this->db->set('deliverynote_transfered_quantity', 'deliverynote_transfered_quantity - ' . $del_delivered_qty, FALSE);
                        $this->db->set('deliverynote_remaining_quantity', 'deliverynote_remaining_quantity + ' . $del_delivered_qty, FALSE);
                        $this->db->set('product_status', '0');
                        $this->db->where('salesorder_number', $salesorder_number);
                        $this->db->where('product_code', $product_code[$key]);                   
                        $this->db->update('cberp_sales_orders_items'); 
                    }

                    $qty = numberClean($product_qty[$key]);
                    $this->db->select('onhand_quantity');
                    $this->db->from('cberp_products');
                    $this->db->where('product_code', $product_code[$key]);
                    $prdQry = $this->db->get();        
                    $prdresult = $prdQry->row_array();
                    $this->update_warehouse_products($product_code[$key], $store_id, $qty,"cancel");
                    if ($prdresult) {
                        $onhand = intval($prdresult['onhand_quantity']);
                        $updateQty = $onhand + $qty;          
                        $upqty = array('onhand_quantity' => $updateQty);
                        $this->db->where('product_code', $product_code[$key]);
                        $this->db->update('cberp_products', $upqty);
                    }

                }


            }
               

            if($grand_product_cost > 0)
            {
                $cost_of_goods_data =  [
                    'acid' => $default_cost_of_goods_account,
                    'type' => 'Expense',
                    'cat' => 'Deliverynote',
                    'credit' => $total_product_cost,
                    'employee_id' => $this->session->userdata('id'),
                    'date' => date('Y-m-d'),
                    'transaction_number'=>$transaction_number,
                ];
                $this->db->set('lastbal', 'lastbal - ' . $total_product_cost, FALSE);
                $this->db->where('acn', $default_cost_of_goods_account);
                $this->db->update('cberp_accounts'); 
                $this->db->insert('cberp_transactions', $cost_of_goods_data);
    
                // Inventory transaction
                $inventory_data =  [
                    'acid' => $default_inventory_account,
                    'type' => 'Asset',
                    'cat' => 'Deliverynote',
                    'debit' => $total_product_cost,
                    'employee_id' => $this->session->userdata('id'),
                    'date' => date('Y-m-d'),
                    'transaction_number'=>$transaction_number,
                ];
                $this->db->set('lastbal', 'lastbal + ' . $total_product_cost, FALSE);
                $this->db->where('acn', $default_inventory_account);
                $this->db->update('cberp_accounts'); 
                $this->db->insert('cberp_transactions', $inventory_data);
            }
        }


        $this->db->update('cberp_sales_orders', ['converted_status' => '2'], ['salesorder_number'=> $salesorder_number]);
        $this->db->update('cberp_delivery_notes', ['status' => 'Canceled'], ['delivery_note_number'=> $delivery_note_number]);
        //erp2024 06-01-2025 detailed history log starts
        detailed_log_history('Deliverynote',$delivery_note_number,'Canceled', $_POST['changedFields']);
        //erp2024 06-01-2025 detailed history log ends 
        echo json_encode(array('status' => 'success'));
    }
    public function lead_reassigned()
    {
        $leadid = $this->input->post('leadid');
        $assigned_val = $this->input->post('assigned_val');
        $this->db->update('cberp_customer_leads', ['assigned_to' => '','enquiry_status' => 'Completed', 'accepted_dt'=>NULL], ['lead_id'=> $leadid]); 
        // $this->db->update('cberp_customer_leads', ['assigned_to' => '','enquiry_status' => 'Open', 'accepted_dt'=>NULL], ['lead_id'=> $leadid]); 

        //data added to log                  
        master_table_log('customer_general_enquiry_log',$leadid,'Lead Reverted');
        //erp2024 06-01-2025 detailed history log starts
        $changedFields = json_encode([
            [
                'fieldlabel' => 'Assigned To',
                'field_name' => 'assigned_to',
                'oldValue' => $assigned_val,
                'newValue' => ''
            ]
        ]);
        detailed_log_history('Lead',$leadid,'Lead Reverted', $changedFields);
        //erp2024 06-01-2025 detailed history log ends 
        echo json_encode(array('status' => 'success'));
    }
    public function lead_accept()
    {
        
        $leadid = $this->input->post('leadid');
        // $this->db->update('cberp_customer_leads', ['enquiry_status' => 'Accepted','accepted_dt'=>date('Y-m-d H:i:s')], ['lead_id'=> $leadid]);
        $this->db->update('cberp_customer_leads', ['enquiry_status' => 'Completed','accepted_dt'=>date('Y-m-d H:i:s')], ['lead_id'=> $leadid]);
        //data added to log                  
        master_table_log('customer_general_enquiry_log',$leadid,'Lead Accepted');
         // erp2025 09-01-2025 starts
         detailed_log_history('Lead',$leadid,'Accepted', $_POST['changedFields']);	
             // erp2025 09-01-2025 ends

        echo json_encode(array('status' => 'success'));
    }
    public function alreadyconverted_or_not()
    {
        $leadid = $this->input->post('leadid');
        $result = $this->quote->alreadyconverted_or_not($leadid);
        echo json_encode(array('status' => 'success', 'data'=> $result));
    }

    public function quote_reassigned()
    {
        $quote_id = $this->input->post('quote_id'); 
        //erp2024 06-01-2025 detailed history log starts
        detailed_log_history('Quote',$quote_id,'Quotation Reverted', $changedFields);
        //erp2024 06-01-2025 detailed history log ends 
        $this->db->update('cberp_quotes', ['employee_id' => NULL,'term'=>NULL,'approvalflg'=>'0','status'=>'Reverted'], ['id'=> $quote_id]);
        echo json_encode(array('status' => 'success'));
    }

    public function quote_reverted_by_dmin()
    {
        $quote_id = $this->input->post('quote_id'); 
        //erp2024 06-01-2025 detailed history log starts
        detailed_log_history('Quote',$quote_id,'Quotation Reverted', $changedFields);
        //erp2024 06-01-2025 detailed history log ends 
        $this->db->update('cberp_quotes', ['employee_id' => NULL,'term'=>NULL,'approvalflg'=>'0','approved_by'=>NULL,'approved_date'=>NULL,'status'=>'pending'], ['id'=> $quote_id]);
        //07-04-2025 old one   
        // $this->db->update('cberp_quotes', ['employee_id' => NULL,'term'=>NULL,'approvalflg'=>'0','status'=>'Reverted'], ['id'=> $quote_id]);
        echo json_encode(array('status' => 'success'));
    }
    public function quote_accept()
    {
        $quote_id = $this->input->post('quote_id');
        //erp2024 06-01-2025 detailed history log starts
        detailed_log_history('Quote',$quote_id,'Quotation Accepted', $changedFields);
        //erp2024 06-01-2025 detailed history log ends 
        $this->db->update('cberp_quotes', ['sent_by' => $this->session->userdata('id'),'sent_dt'=>date('Y-m-d H:i:s'),'status'=>'Sent'], ['id'=> $quote_id]);
        echo json_encode(array('status' => 'success'));
    }

    
    public function get_quote_count_filter()
    {
        $filter_status = $this->input->post('filter_status');
        // $filter_employee = $this->input->post('filter_employee');

        $filter_expiry_date_from = !empty($this->input->post('filter_expiry_date_from')) ? date('Y-m-d',strtotime($this->input->post('filter_expiry_date_from'))) : ""; 

        $filter_expiry_date_to = !empty($this->input->post('filter_expiry_date_to')) ? date('Y-m-d',strtotime($this->input->post('filter_expiry_date_to'))) : "";

        $filter_price_from = !empty($this->input->post('filter_price_from')) ? $this->input->post('filter_price_from') : 0;
        $filter_price_to = !empty($this->input->post('filter_price_to')) ? $this->input->post('filter_price_to'): 0;

        $filter_customer = !empty($this->input->post('filter_customer')) ?$this->input->post('filter_customer') : "";
        // $filter_customertype = !empty($this->input->post('filter_customertype')) ?$this->input->post('filter_customertype') : "";
 
        $results = $this->quote->get_quote_count_filter('invoicedate','total',$filter_status,$filter_expiry_date_from,$filter_expiry_date_to,$filter_price_from,$filter_price_to,$filter_customer);        
        foreach ($results as $key => $value) {
            if (empty($value)) {
                $results[$key] = 0;
            }
        }
        
        echo json_encode(array('status' => 'success','data'=>$results));
    }
    public function deletesubItem(){
        $image_id = $this->input->post('selectedProducts');
        $name = $this->input->post('image');
        $this->db->where('id', $image_id);
        $this->db->delete('cberp_sent_received_files');
        unlink(FCPATH . 'uploads/' . $name);
        echo json_encode(array('status' => '1', 'message' =>"Success"));            
    }
    public function deleteFiles(){
        $image_id = $this->input->post('selectedProducts');
        $name = $this->input->post('image');
        $this->db->where('id', $image_id);
        $this->db->delete('cberp_sent_received_files');
        unlink(FCPATH . 'uploads/' . $name);
        // detailed_log_history('Salesorder',$saleorderid,'Converted to Deliverynote', $_POST['changedFields']);
        echo json_encode(array('status' => '1', 'message' =>"Success"));            
    }
    public function approvel_level_action(){
       $approval_data = array(
            'module_number' => $this->input->post('module_number'),
            'function_number' => $this->input->post('function_number'),
            'approval_step' => $this->input->post('approval_step'),
            'approval_comments' => $this->input->post('approval_comments'),
            'approved_by' => $this->session->userdata('id'),
            'approved_date' => date('Y-m-d H:i:s'),
        );
        $this->db->insert('cberp_approval',$approval_data);
        $changedFields = json_encode([
            [
                'fieldlabel' => 'Approved Level '.$this->input->post('approval_step'),
                'field_name' => 'Approved',
                'oldValue' => '',
                'newValue' => $this->input->post('approval_step')
            ]
        ]);
        $users_list = linked_user_module_approvals_by_module_number($this->sales_module_group_number);
        $target_url = $this->input->post('target_url');   
        $message = "Approved : (".$this->input->post('function_number').") Level ".$this->input->post('approval_step');            
        $message_caption = "Approved : (".$this->input->post('function_number').") Level ".$this->input->post('approval_step');
        send_message_to_users($users_list,$target_url,$message_caption,$message,'');
        detailed_log_history($this->input->post('module_number'),$this->input->post('function_number'),'Level Approved', $changedFields);
        echo json_encode(array('status' => '1', 'message' =>"Success"));            
    }
    public function approval_cancellation(){
        $module_number = $this->input->post('module_number');
        $function_number = $this->input->post('function_number');
        $approval_step = $this->input->post('approval_step');
        $cancelreason = $this->input->post('cancelreason');
        $this->db->delete('cberp_approval',['module_number'=>$module_number,'function_number'=>$function_number,'approval_step'=>$approval_step]);
        $changedFields = json_encode([
            [
                'fieldlabel' => 'Cancelled Level '.$this->input->post('approval_step'),
                'field_name' => 'Cancelled',
                'oldValue' => '',
                'newValue' => $this->input->post('cancelreason')
            ]
        ]);
        detailed_log_history($this->input->post('module_number'),$this->input->post('function_number'),'Level Cancelled', $changedFields);
        echo json_encode(array('status' => '1', 'message' =>"Success"));            
    }
   
}
