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
    }

    //create invoice
    public function create()
    {

        $this->session->unset_userdata('draftquote_id');   

        $this->load->model('plugins_model', 'plugins');
        $data['emp'] = $this->plugins->universal_api(69);
        if ($data['emp']['key1']) {
            $this->load->model('employee_model', 'employee');
            $data['employee'] = $this->employee->list_employee();
        }
        $this->load->library("Common");
        $data['taxlist'] = $this->common->taxlist($this->config->item('tax'));
        $this->load->model('customers_model', 'customers');
        $this->load->model('plugins_model', 'plugins');
        $data['exchange'] = $this->plugins->universal_api(5);
        $data['currency'] = $this->quote->currencies();
        $data['customergrouplist'] = $this->customers->group_list();
        $data['lastinvoice'] = $this->quote->lastquote();
        $data['terms'] = $this->quote->billingterms();
        $head['title'] = "New Quote";
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['warehouse'] = $this->quote->warehouses();
        $data['taxdetails'] = $this->common->taxdetail();        
        $data['configurations'] = $this->configurations;
        $this->load->view('fixed/header', $head);
        $this->load->view('quotes/newquote', $data);
        $this->load->view('fixed/footer');
    }
    public function create_draft()
    {
        $data['permissions'] = load_permissions('Sales','Quotes','Manage Quotes','View Page');
        // $this->session->unset_userdata('draftquote_id');   

        $this->load->model('plugins_model', 'plugins');
        $data['emp'] = $this->plugins->universal_api(69);
        if ($data['emp']['key1']) {
            $this->load->model('employee_model', 'employee');
            $data['employee'] = $this->employee->list_employee();
        }
        $quoteid = $this->input->get('id');
        $data['invoice'] = $this->quote->quote_details($quoteid);
        $data['products'] = $this->quote->quote_products($quoteid);
        $data['quote_id'] = $quoteid;
        $data['approvedby'] = $this->quote->approved_person($tid,"Quote");
        $data['assignedperson'] = $this->quote->employee($data['invoice']['eid']);
        $this->load->library("Common");
        $data['taxlist'] = $this->common->taxlist($this->config->item('tax'));
        $this->load->model('customers_model', 'customers');
        $this->load->model('plugins_model', 'plugins');
        $data['exchange'] = $this->plugins->universal_api(5);
        $data['currency'] = $this->quote->currencies();
        $data['customergrouplist'] = $this->customers->group_list();
        
        $data['trackingdata'] = tracking_details('quote_id',$quoteid);
        $data['terms'] = $this->quote->billingterms();
        $head['title'] = "Quote #".$data['invoice']['tid'];
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['warehouse'] = $this->quote->warehouses();
        $data['taxdetails'] = $this->common->taxdetail();        
        $data['configurations'] = $this->configurations;
        $data['log'] = $this->quote->gethistory($quoteid);
        $data['images'] = get_uploaded_images('Quote',$quoteid);
        $this->load->view('fixed/header', $head);
        $this->load->view('quotes/create_quote', $data);
        $this->load->view('fixed/footer');
    }
    public function convert_to_quote()
    {
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
        $data['taxlist'] = $this->common->taxlist_edit($data['invoice']['taxstatus']);        
        $data['configurations'] = $this->configurations;
        $this->load->view('fixed/header', $head);
        $this->load->view('quotes/edit', $data);
        $this->load->view('fixed/footer');
    }

    //invoices list
    public function index()
    {
        $data['permissions'] = load_permissions('Sales','Quotes','Manage Quotes');
        $head['title'] = "Manage Quote";
        $data['eid'] = intval($this->input->get('eid'));
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->model('invoices_model');       
        $condition = "";
        $data['counts'] = $this->invoices_model->get_dynamic_count('cberp_quotes','invoicedate','total',$condition);
        $data['employees']  = employee_list();
        $data['customers']  = customer_list();
        $this->load->view('fixed/header', $head);
        $this->load->view('quotes/quotes', $data);
        $this->load->view('fixed/footer');
    }
  
    //action leadid  s_warehouses eid
    public function action()
    {
        $currency = $this->input->post('mcurrency');
        $customer_id = $this->input->post('customer_id');
        $invocieno = $this->input->post('invocieno');
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
        $refer = $this->input->post('refer');
        $total = rev_amountExchange_s($this->input->post('total'), $currency, $this->aauth->get_user()->loc);
        
        $proposal = $this->input->post('propos');
        $total_tax = 0;
        $total_discount = 0;
        // $discountFormat = $this->input->post('discountFormat');
        $discountFormat = 0;
        $pterms = $this->input->post('pterms');
        $quote_id="";
        // $this->load->model('plugins_model', 'plugins');
        // $empl_e = $this->plugins->universal_api(69);
        // if ($empl_e['key1']) {


        $emp = 0;
        // $emp = $this->input->post('employee');


        // $emp = (!empty($this->input->post('employee'))) ? $this->input->post('employee'): $this->aauth->get_user()->id;
        // } else {
        //     $emp = $this->aauth->get_user()->id;
        // }

        $i = 0;
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

        $data = array('tid' => $invocieno, 'invoicedate' => $bill_date, 'invoiceduedate' => $bill_due_date, 'subtotal' => $subtotal, 'shipping' => $shipping, 'ship_tax' => $shipping_tax, 'ship_tax_type' => $ship_taxtype, 'discount' => $total_discount, 'tax' => $total_tax, 'total' => $total, 'notes' => $notes, 'csd' => $customer_id, 'eid' => $emp, 'taxstatus' => $tax, 'discstatus' => $discstatus, 'format_discount' => $discountFormat, 'refer' => $refer, 'term' => $pterms, 'proposal' => $proposal, 'multi' => $currency, 'loc' => $this->aauth->get_user()->loc,'customer_reference_number' => $this->input->post('customer_reference_number'),'customer_contact_person' => $this->input->post('customer_contact_person'),'customer_contact_number' => $this->input->post('customer_contact_number'),'customer_contact_email' => $this->input->post('customer_contact_email'));

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
        $data['status'] = "pending";
        $data["approvalflg"] = 0;       
        // if(empty($this->input->post('leadid'))){
        //     $data['status'] = "pending";
        //     $data["approvalflg"] = 0;
        // }
        $data['lead_id'] = (!empty($this->input->post('leadid'))) ? $this->input->post('leadid') : "";
        if($data['lead_id']){
          
            master_table_log('customer_general_enquiry_log',$data['lead_id'],'Lead Converted');
            //erp2024 06-01-2025 detailed history log starts
            detailed_log_history('Lead',$data['lead_id'],'Lead Converted to Quote', $changedFields);
            //erp2024 06-01-2025 detailed history log ends 
           
        }
        $qtid = $this->quote->check_quote_existornot($invocieno);
        $authid = $this->quote->check_approval_existornot($invocieno);
      
        if($qtid > 0)
        {
                $data['updated_by']   = $this->session->userdata('id');
                $data['updated_dt']   = date('Y-m-d H:i:s');
                $quote_id = $this->input->post('quote_id');
                $data['prepared_by']  = $this->session->userdata('id');
                $data['prepared_dt']  = date('Y-m-d H:i:s');
                $data['prepared_flg'] = '1';
                // log_table_data('cberp_quotes','cberp_quotes_log', 'id' ,'quote_id','Update',$quote_id);
                // master_table_log('cberp_quotes_log',$quote_id,'Update');
                history_table_log('cberp_quotes_log','quote_id',$quote_id,'Created');
                //erp2024 06-01-2025 detailed history log starts
                detailed_log_history('Quote',$quote_id,'Quote Created', $changedFields);
                //erp2024 06-01-2025 detailed history log ends 
                $this->db->update('cberp_quotes', $data,['tid'=>$invocieno]);
                // file upload section starts 22-01-2025
                if($_FILES['upfile'])
                {
                    upload_files($_FILES['upfile'], 'Quote',$quote_id);
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
                if(!empty($this->input->post('leadid')))
                {
                                  
                    $comments = "Lead converted to quote. Quote : #".($invocieno);
                    $this->db->where('lead_id', $this->input->post('leadid'));
                    $this->db->update('cberp_customer_leads',['enquiry_status'=>'Closed','comments'=>$comments]);
                    
                    $this->db->where('lead_id', $this->input->post('leadid'));
                    $this->db->update('cberp_transaction_tracking',['quote_id'=>$quote_id,'quote_number'=>$tid]);
                }
                else{
                    $this->db->insert('cberp_transaction_tracking',['quote_id'=>$quote_id,'quote_number'=>$tid]);
    
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
                $max_disrate = $this->input->post('maxdiscountrate');
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
                            'code' => $code[$key],
                            'qty' => numberClean($product_qty[$key]),
                            'remaining_qty' => numberClean($product_qty[$key]),
                            'ordered_qty' => numberClean($product_qty[$key]),
                            'transfered_qty' => 0,
                            'delivered_qty' => 0,
                            'price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
                            'tax' => $product_tax,
                            'discount' => $discountamount,
                            'subtotal' => rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc),
                            'totaltax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                            'totaldiscount' => rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc),
                            // 'product_des' => $product_des[$key],
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
                    // $this->db->insert_batch('cberp_quotes_items', $productlist);
                    $this->db->set(array('discount' => rev_amountExchange_s(amountFormat_general($total_discount), $currency, $this->aauth->get_user()->loc), 'tax' => rev_amountExchange_s(amountFormat_general($total_tax), $currency, $this->aauth->get_user()->loc), 'items' => $itc));
                    $this->db->where('id', $quote_id);
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
            $data['created_dt']   = date('Y-m-d H:i:s');
            $data['prepared_by']  = $this->session->userdata('id');
            $data['prepared_dt']  = date('Y-m-d H:i:s');
            $data['prepared_flg'] = '1';
            if ($this->db->insert('cberp_quotes', $data)) {
                $pid = $this->input->post('pid');
                $invocieno = $this->db->insert_id();
                // log_table_data('cberp_quotes','cberp_quotes_log', 'id' ,'quote_id','Create',$invocieno);
                history_table_log('cberp_quotes_log','quote_id',$invocieno,'Create');
                //erp2024 06-01-2025 detailed history log starts
                detailed_log_history('Quote',$invocieno,'Created', $changedFields);
                //erp2024 06-01-2025 detailed history log ends 
                $quote_id = $invocieno;

                    // file upload section starts 22-01-2025
                    if($_FILES['upfile'])
                    {
                        upload_files($_FILES['upfile'], 'Quote',$invocieno);
                    }
                    // file upload section ends 22-01-2025
    
                //erp2024 insert to authorization history table /////////////////////////////////////
                $history['function_type'] = 'Quote';
                $history['function_id'] = $invocieno;
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
                if(!empty($this->input->post('leadid')))
                {
                    $comments = "Lead converted to quote. Quote : #".($invocieno);
                    $this->db->where('lead_id', $this->input->post('leadid'));
                    $this->db->update('cberp_customer_leads',['enquiry_status'=>'Closed','comments'=>$comments]);
                    
                    $this->db->where('lead_id', $this->input->post('leadid'));
                    $this->db->update('cberp_transaction_tracking',['quote_id'=>$quote_id,'quote_number'=>$tid]);
                }
                else{
                    $this->db->insert('cberp_transaction_tracking',['quote_id'=>$quote_id,'quote_number'=>$tid]);
    
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
                $max_disrate = $this->input->post('maxdiscountrate');
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
                            'tid' => $invocieno,
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
                            'subtotal' => rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc),
                            'totaltax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                            'totaldiscount' => rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc),
                            // 'product_des' => $product_des[$key],
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
                }
                if ($prodindex > 0) {
                    $this->db->insert_batch('cberp_quotes_items', $productlist);
                    // log_table_items_data('cberp_quotes_items','cberp_quotes_items_log', 'id' ,'quote_item_id','Create','tid',$invocieno);
                    $this->db->set(array('discount' => rev_amountExchange_s(amountFormat_general($total_discount), $currency, $this->aauth->get_user()->loc), 'tax' => rev_amountExchange_s(amountFormat_general($total_tax), $currency, $this->aauth->get_user()->loc), 'items' => $itc));
                    $this->db->where('id', $invocieno);
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
        $refer = $this->input->post('refer');
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
            $discstatus = 0;
        } else {
            $discstatus = 1;
        }

        $this->db->trans_start();
        //products product_description
        $transok = true;
        //Invoice Data employee
        $bill_date = datefordatabase($invoicedate);
        $bill_due_date = datefordatabase($invocieduedate);
        $data = array('tid' => $invocieno, 'invoicedate' => $bill_date, 'invoiceduedate' => $bill_due_date, 'subtotal' => $subtotal, 'shipping' => $shipping, 'ship_tax' => $shipping_tax, 'ship_tax_type' => $ship_taxtype, 'discount' => $total_discount, 'tax' => $total_tax, 'total' => $total, 'notes' => $notes, 'csd' => $customer_id, 'eid' => $emp, 'taxstatus' => $tax, 'discstatus' => $discstatus, 'format_discount' => $discountFormat, 'refer' => $refer, 'term' => $pterms, 'proposal' => $proposal, 'multi' => $currency, 'loc' => $this->aauth->get_user()->loc,'status'=>'draft','customer_reference_number' => $this->input->post('customer_reference_number'),'customer_contact_person' => $this->input->post('customer_contact_person'),'customer_contact_number' => $this->input->post('customer_contact_number'),'customer_contact_email' => $this->input->post('customer_contact_email'));

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

        $data['lead_id'] = (!empty($this->input->post('leadid'))) ? $this->input->post('leadid') : "";
        $qtid = $this->quote->check_quote_existornot($tid);
        if($qtid > 0)
        {
                $data['updated_by']   = $this->session->userdata('id');
                $data['updated_dt']   = date('Y-m-d H:i:s');
                $this->db->update('cberp_quotes', $data,['id'=>$qtid]);
                // file upload section starts 22-01-2025
                if($_FILES['upfile'])
                {
                    upload_files($_FILES['upfile'], 'Quote',$qtid);
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
                $max_disrate = $this->input->post('maxdiscountrate');
                $product_amt = $this->input->post('product_amt');
                $product_tax =0;
                $this->db->delete('cberp_quotes_items', array('tid' => $invocieno));
                
                    
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
                            'tid' => $invocieno,
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
                            'subtotal' => rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc),
                            'totaltax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                            'totaldiscount' => rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc),
                            'unit' => $product_unit[$key],
                            'discount_type' => $discount_type[$key],
                            'lowest_price' => $lowest_price[$key],
                            'max_disrate' => $max_disrate[$key],
                        );
                        
                        $flag = true;
                        $productlist[$prodindex] = $data;
                        $existornot = $this->quote->check_product_existornot($invocieno,$product_id[$key]);
                        if($existornot==1)
                        {
                            $this->db->update('cberp_quotes_items', $data, ['tid'=>$invocieno, 'pid'=>$product_id[$key]]);
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
            $data['created_by']   = $this->session->userdata('id');
            $data['created_dt']   = date('Y-m-d H:i:s');
            if ($this->db->insert('cberp_quotes', $data)) {
                $pid = $this->input->post('pid');
                $invocieno = $this->db->insert_id();
                  // file upload section starts 22-01-2025
                  if($_FILES['upfile'])
                  {
                      upload_files($_FILES['upfile'], 'Quote',$invocieno);
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
                $max_disrate = $this->input->post('maxdiscountrate');
                $product_amt = $this->input->post('product_amt');
                $product_tax =0;

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
                            'tid' => $invocieno,
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
                            'subtotal' => rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc),
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
                }
                if ($prodindex > 0) {
                    $this->db->insert_batch('cberp_quotes_items', $productlist);
                    $this->db->set(array('discount' => rev_amountExchange_s(amountFormat_general($total_discount), $currency, $this->aauth->get_user()->loc), 'tax' => rev_amountExchange_s(amountFormat_general($total_tax), $currency, $this->aauth->get_user()->loc), 'items' => $itc));
                    $this->db->where('id', $invocieno);
                    $this->db->update('cberp_quotes');
                } 
                header('Content-Type: application/json'); // Ensure correct content type

                
                
                $response = array(
                    'status' => 'Success',
                    'quote' => $invocieno
                );
                
                // Output JSON and exit
                echo json_encode($response);

               
            } 
        }        
        history_table_log('cberp_quotes_log','quote_id',$invocieno,'Data Saved As Draft');
        //erp2024 06-01-2025 detailed history log starts
        detailed_log_history('Quote',$invocieno,'Data Saved As Draft', $changedFields);
        //erp2024 06-01-2025 detailed history log ends 
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
        $refer = $this->input->post('refer');
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
            $discstatus = 0;
        } else {
            $discstatus = 1;
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
        $data = array('tid' => $invocieno, 'invoicedate' => $bill_date, 'invoiceduedate' => $bill_due_date, 'subtotal' => $subtotal, 'shipping' => $shipping, 'ship_tax' => $shipping_tax, 'ship_tax_type' => $ship_taxtype, 'discount' => $total_discount, 'tax' => $total_tax, 'total' => $total, 'notes' => $notes, 'csd' => $customer_id, 'eid' => $emp, 'taxstatus' => $tax, 'discstatus' => $discstatus, 'format_discount' => $discountFormat, 'refer' => $refer, 'term' => $pterms, 'proposal' => $proposal, 'multi' => $currency, 'loc' => $this->aauth->get_user()->loc,'approved_by'=>$this->session->userdata('id'),'approved_dt'=>date('Y-m-d H:i:s'),'customer_reference_number' => $this->input->post('customer_reference_number'),'customer_contact_person' => $this->input->post('customer_contact_person'),'customer_contact_number' => $this->input->post('customer_contact_number'),'customer_contact_email' => $this->input->post('customer_contact_email'));

        //erp2024 find amout limit 17-07-2024 ///////////////////////////////////////
        $amount_limit = $this->quote->amount_limit($this->session->userdata('id'));
        
        //////////////////////////////////////////////////////////////
        $data['status'] = "Assigned";
        $data["approvalflg"] = '1';     
        $data['updated_by']   = $this->session->userdata('id');
        $data['updated_dt']   = date('Y-m-d H:i:s');
        $quote_id = $this->input->post('quote_id');
        $this->db->update('cberp_quotes', $data,['tid'=>$invocieno]);
        $pid = $this->input->post('pid');
        // file upload section starts 22-01-2025
        if($_FILES['upfile'])
        {
            upload_files($_FILES['upfile'], 'Quote',$quote_id);
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
        $max_disrate = $this->input->post('maxdiscountrate');
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
                    'code' => $code[$key],
                    'qty' => numberClean($product_qty[$key]),
                    'remaining_qty' => numberClean($product_qty[$key]),
                    'ordered_qty' => numberClean($product_qty[$key]),
                    'transfered_qty' => 0,
                    'delivered_qty' => 0,
                    'price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
                    'tax' => $product_tax,
                    'discount' => $discountamount,
                    'subtotal' => rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc),
                    'totaltax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                    'totaldiscount' => rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc),
                    // 'product_des' => $product_des[$key],
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
            $existornot = $this->quote->check_product_existornot($quote_id,$product_id[$key]);
            if($existornot==1)
            {
                $this->db->update('cberp_quotes_items', $data, ['tid'=>$quote_id, 'pid'=>$product_id[$key]]);
            }
            else{
                $this->db->insert('cberp_quotes_items', $data);
            }
        }
         //erp2024 06-01-2025 detailed history log starts
        detailed_log_history('Quote',$quote_id,'Quotaion Approved', $_POST['changedFields']);
        //erp2024 06-01-2025 detailed history log ends 
        if ($prodindex > 0) {
            history_table_log('cberp_quotes_log','quote_id',$quote_id,'Quotaion Approved');
           
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

        if ($ship_taxtype == 'incl') $shipping = $shipping - $shipping_tax;
        $refer = $this->input->post('refer');
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
            $discstatus = 0;
        } else {
            $discstatus = 1;
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
        $data = array('tid' => $invocieno, 'invoicedate' => $bill_date, 'invoiceduedate' => $bill_due_date, 'subtotal' => $subtotal, 'shipping' => $shipping, 'ship_tax' => $shipping_tax, 'ship_tax_type' => $ship_taxtype, 'discount' => $total_discount, 'tax' => $total_tax, 'total' => $total, 'notes' => $notes, 'csd' => $customer_id, 'eid' => $this->session->userdata('id'), 'taxstatus' => $tax, 'discstatus' => $discstatus, 'format_discount' => $discountFormat, 'refer' => $refer, 'term' => $pterms, 'proposal' => $proposal, 'multi' => $currency, 'loc' => $this->aauth->get_user()->loc,'approved_by'=>$this->session->userdata('id'),'approved_dt'=>date('Y-m-d H:i:s'),'customer_reference_number' => $this->input->post('customer_reference_number'),'customer_contact_person' => $this->input->post('customer_contact_person'),'customer_contact_number' => $this->input->post('customer_contact_number'),'customer_contact_email' => $this->input->post('customer_contact_email'));

        //erp2024 find amout limit 17-07-2024 ///////////////////////////////////////
        $amount_limit = $this->quote->amount_limit($this->session->userdata('id'));
        
        //////////////////////////////////////////////////////////////
        $data['status'] = "Sent";
        $data["approvalflg"] = '1';    
        $data['updated_by']   = $this->session->userdata('id');
        $data['updated_dt']   = date('Y-m-d H:i:s');
        $data['sent_by']   = $this->session->userdata('id');
        $data['sent_dt']   = date('Y-m-d H:i:s');
        $quote_id = $this->input->post('quote_id');
        history_table_log('cberp_quotes_log','quote_id',$quote_id,'Quote Sent');  
        $this->db->update('cberp_quotes', $data,['tid'=>$invocieno]);
        $pid = $this->input->post('pid');
        // file upload section starts 22-01-2025
        if($_FILES['upfile'])
        {
                upload_files($_FILES['upfile'], 'Quote',$quote_id);
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
        $max_disrate = $this->input->post('maxdiscountrate');
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
                    'code' => $code[$key],
                    'code' => $code[$key],
                    'qty' => numberClean($product_qty[$key]),
                    'remaining_qty' => numberClean($product_qty[$key]),
                    'ordered_qty' => numberClean($product_qty[$key]),
                    'transfered_qty' => 0,
                    'delivered_qty' => 0,
                    'price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
                    'tax' => $product_tax,
                    'discount' => $discountamount,
                    'subtotal' => rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc),
                    'totaltax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                    'totaldiscount' => rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc),
                    // 'product_des' => $product_des[$key],
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
            history_table_log('cberp_quotes_log','quote_id',$quote_id,'Quotation Sent');
            //erp2024 06-01-2025 detailed history log starts
            detailed_log_history('Quote',$quote_id,'Quotation Sent', $changedFields);
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
    // erp2024 15-10-2024 quote direct send ends
    public function ajax_list()
    {
        $eid = 0;
        // if ($this->aauth->premission(9)) {
        //     $eid = $this->input->post('eid');
        // } 
        $list = $this->quote->get_datatables($eid);
        // print_r($list); die();
        $data = array();
        $no = $this->input->post('start');
        foreach ($list as $invoices) {
            $salebtn = '';
            if($invoices->convertflg == '1'){
                $salesorderstatus = '<span class="st-Closed">' . $this->lang->line(ucwords("Received")) . '</span>';
                $salebtn = '<a href="' . base_url("SalesOrders/salesorder_new?id=$invoices->id") . '&token=1" class="btn btn-secondary btn-sm"  title="sales order">Sales Order(s)</span></a>';
            }
            else if($invoices->convertflg == '2'){
                $salesorderstatus = '<span class="st-partial">' . $this->lang->line(ucwords("Partially Created")) . '</span>';
                $salebtn = '<a href="' . base_url("SalesOrders/salesorder_new?id=$invoices->id") . '&token=1" class="btn btn-secondary btn-sm"  title="sales order">Sales Order(s)</span></a>';
            }
            else{
                $salebtn = '';
                $salesorderstatus ='';
            }
            $convert_to_quote_btn="";
            switch ($invoices->status) {
                case 'pending':
                    $status = '<span class="st-pending">' . $this->lang->line(ucwords($invoices->status)) . '</span>';  
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
                    if($invoices->convertflg==0)
                    {
                        $convert_to_quote_btn = '<button type="button" class="btn btn-secondary btn-sm"  title="Convert To Sales Order" onclick="convertToSalesOrderDirect('.$invoices->id.')">Convert To Sales Order</button>';
                    }
                    
                break;
                case 'Assigned':
                    $status = '<span class="st-assigned">' . $this->lang->line(ucwords($invoices->status)) . '</span>';
                break;
                case 'draft':   
                    $status = '<span class="">' . $this->lang->line(ucwords($invoices->status)) . '</span>';
                break;
                default:
                    $status = '<span class="st-accepted">' . $this->lang->line(ucwords($invoices->status)) . '</span>';
                    break;
            }

            
            if($invoices->approvalflg=='1'){
                $approvstatus = '<span class="st-accepted">' . $this->lang->line('Approved') . '</span>';
            }
            else if($invoices->approvalflg=='2'){
                $approvstatus = '<span class="st-pending">' . $this->lang->line('Hold') . '</span>';
            }
            else if($invoices->approvalflg=='3'){
                $approvstatus = '<span class="st-Closed">' . $this->lang->line(ucwords('Reject')) . '</span>';
            }
            else if($invoices->prepared_flg=='0' && $invoices->approvalflg=='0'){
                $approvstatus = '';
            }
            else{
                $approvstatus = '<span class="st-Closed">' . $this->lang->line('Waiting for approval') . '</span>';
            }
           
            $targeturl = '<a href="' . base_url("quote/create?id=$invoices->id") . '">&nbsp; ' . $invoices->tid . '</a>';
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $targeturl;
            $approveddt = ($invoices->approved_dt)?(date('d-m-Y H:i:s',strtotime($invoices->approved_dt))):"";
            $row[] = $invoices->name;
            $row[] = $invoices->customerid;
            $row[] = (!empty($invoices->invoicedate)) ? dateformat($invoices->invoicedate) :"";
            $row[] = (!empty($invoices->invoiceduedate)) ? dateformat($invoices->invoiceduedate) : "";
            $row[] = $invoices->total;
            $row[] = $invoices->refer;
            $row[] = $invoices->customer_reference_number;
            $row[] = $invoices->employeename;
            $row[] = $approveddt;
            $row[] = $approvstatus;
            $row[] = $status;
            $row[] = $salesorderstatus;
            $btns = '<a href="' . base_url("billing/printquote?id=$invoices->id") . '&token=1" class="btn btn-secondary btn-sm"  title="Print" target="_blank"><span class="fa fa-print"></span></a> '.$salebtn;
            $row[] = $btns." ". $convert_to_quote_btn;
            // $row[] = '<a href="' . base_url("quote/view?id=$invoices->id") . '" class="btn btn-secondary btn-sm" target="_blank" title="View"><i class="fa fa-eye"></i></a> <a href="' . base_url("billing/printquote?id=$invoices->id") . '&token=1" class="btn btn-secondary btn-sm"  title="Print" target="_blank"><span class="fa fa-print"></span></a> <a href="#" data-object-id="' . $invoices->id . '" class="btn btn-secondary btn-sm delete-object" title="Delete"><span class="fa fa-trash"></span></a>';
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->quote->count_all($eid),
            "recordsFiltered" => $this->quote->count_filtered($eid),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);

    }

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
        $data['employee'] = $this->quote->employee($data['invoice']['eid']);
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
        $data['employee'] = $this->quote->employee($data['invoice']['eid']);
        $data['general'] = array('title' => $this->lang->line('Quote'), 'person' => $this->lang->line('Customer'), 'prefix' => prefix(1), 't_type' => 0);


        ini_set('memory_limit', '64M');
        if ($data['invoice']['taxstatus'] == 'cgst' || $data['invoice']['taxstatus'] == 'igst') {
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
            $discstatus = 0;
        } else {
            $discstatus = 1;
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
        $max_disrate = $this->input->post('maxdiscountrate');        
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
                    'code' => $code[$key],
                    'qty' => numberClean($product_qty[$key]),
                    'remaining_qty' => numberClean($product_qty[$key]),
                    'ordered_qty' => numberClean($product_qty[$key]),
                    'transfered_qty' => 0,
                    'delivered_qty' => 0,
                    'price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
                    'tax' => $product_tax,
                    'discount' => $discountamount,
                    'subtotal' => rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc),
                    'totaltax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                    'totaldiscount' => rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc),
                    // 'product_des' => $product_des[$key],
                    'unit' => $product_unit[$key],
                    'discount_type' => $discount_type[$key],
                    'lowest_price' => $lowest_price[$key],
                    'max_disrate' => $max_disrate[$key]
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

        
      
       $data = array('invoicedate' => $bill_date, 'invoiceduedate' => $bill_due_date, 'subtotal' => $subtotal, 'shipping' => $shipping, 'ship_tax' => $shipping_tax, 'ship_tax_type' => $ship_taxtype, 'discount' => $total_discount, 'tax' => $total_tax, 'total' => $total, 'notes' => $notes, 'csd' => $customer_id, 'items' => $i, 'taxstatus' => $tax, 'discstatus' => $discstatus, 'format_discount' => $discountFormat, 'refer' => $refer, 'term' => $pterms, 'proposal' => $propos, 'multi' => $currency, 'approvalflg' => $approvalflg, 'eid'=>$employee);
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
            $discstatus = 0;
        } else {
            $discstatus = 1;
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
        $max_disrate = $this->input->post('maxdiscountrate');        
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
                    'code' => $code[$key],
                    'qty' => numberClean($product_qty[$key]),
                    'remaining_qty' => numberClean($product_qty[$key]),
                    'ordered_qty' => numberClean($product_qty[$key]),
                    'transfered_qty' => 0,
                    'delivered_qty' => 0,
                    'price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
                    'tax' => $product_tax,
                    'discount' => $discountamount,
                    'subtotal' => rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc),
                    'totaltax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                    'totaldiscount' => rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc),
                    // 'product_des' => $product_des[$key],
                    'unit' => $product_unit[$key],
                    'discount_type' => $discount_type[$key],
                    'lowest_price' => $lowest_price[$key],
                    'max_disrate' => $max_disrate[$key]
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

        
      
       $data = array('invoicedate' => $bill_date, 'invoiceduedate' => $bill_due_date, 'subtotal' => $subtotal, 'shipping' => $shipping, 'ship_tax' => $shipping_tax, 'ship_tax_type' => $ship_taxtype, 'discount' => $total_discount, 'tax' => $total_tax, 'total' => $total, 'notes' => $notes, 'csd' => $customer_id, 'items' => $i, 'taxstatus' => $tax, 'discstatus' => $discstatus, 'format_discount' => $discountFormat, 'refer' => $refer, 'term' => $pterms, 'proposal' => $propos, 'multi' => $currency, 'approvalflg' => $approvalflg, 'eid'=>$employee);
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

        $tid = $this->input->post('id'); 
        $results = $this->quote->quote_product_by_id($tid);  
       
        $salesordernumber = $this->quote->salesorder_number();
        $response=[];
                $prevresult = $this->quote->already_converted_to_salesorder($results['id']);
                if(!empty($prevresult['id']) && !empty($prevresult['tid']))
                {
                    $response= [
                        "status" => "ok",
                        "message" => "successfully converted",
                        "id" => $prevresult['id'],
                        "flg" =>"1"  //set flag is 1 for prevent duplicate check
                    ];
                }
                else{
                    unset($results['convertflg']);
                    unset($results['id']);
                    unset($results['tid']);
                    unset($results['approvalflg']);             
                    unset($results['lead_id']);
                    unset($results['sales_tid']);
                    unset($results['created_by']);
                    unset($results['created_dt']);
                    unset($results['updated_by']);
                    unset($results['updated_dt']);
                    unset($results['prepared_by']);
                    unset($results['prepared_dt']);
                    unset($results['prepared_flg']);
                    unset($results['approved_by']);
                    unset($results['approved_dt']);
                    unset($results['sent_by']);
                    unset($results['sent_dt']);
                    // unset($results['customer_reference_number']);
                    // unset($results['customer_contact_person']);
                    // unset($results['customer_contact_number']);
                    // unset($results['customer_contact_email']);

                    $results['quote_id'] = $tid;    
                    // history_table_log('cberp_quotes_log','quote_id',$results['id'],'Converted to Sales Order');                     
                    $results['tid'] = $salesordernumber+1000;
                    $this->db->insert('cberp_sales_orders', $results);
                    $last_insert_id = $this->db->insert_id();                
                    $this->session->set_userdata('latestsalesorder', $last_insert_id);
                    $this->quote->insert_to_sales_order_items($tid,$last_insert_id);
                    
                    // die();
                    $response= [
                        "status" => "ok",
                        "message" => "successfully converted",
                        "id" => $last_insert_id,
                        "flg" =>"1"  //set flag is 1 for prevent duplicate check
                    ];
                }
                
            // }
                
        // }
        // else{
        //     $response= [
        //         "status" => "ok",
        //         "message" => "Please approved the quote first",
        //     ];
            
        // }
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
            $data['taxlist'] = $this->common->taxlist_edit($data['invoice']['taxstatus']);
            $data['trackingdata'] = tracking_details('quote_id',$data['invoice']['quote_id']);   
            // echo "<pre>";
            // print_r($data['trackingdata']);
            // die();   
            $data['configurations'] = $this->configurations;
            $data['log'] = $this->quote->getsalehistory($tid);
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
        $data['taxlist'] = $this->common->taxlist_edit($data['invoice']['taxstatus']);                
        $data['configurations'] = $this->configurations;
        $this->load->view('fixed/header', $head);
        $this->load->view('sales/quotetosalesorder', $data);
        $this->load->view('fixed/footer');
            
    }

    public function saleorderaction()
    {
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);  delete
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

        
        $ordered_qty = $this->input->post('ordered_qty');
        $transfered_qty = $this->input->post('transfered_qty');
        $delivered_qty = $this->input->post('delivered_qty');
        $remaining_qty = $this->input->post('remaining_qty');

        $i = 0;
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
            $data = array(
                'tid' => $salesorderids,
                // 'tid' => $invocieno,
                'pid' => $product_id[$key],
                'product' => $product_name1[$key],
                'code' => $code[$key],
                'qty' => numberClean($product_qty[$key]),
                'price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
                'tax' => numberClean($product_tax[$key]),
                'discount' => $discountamount,
                'subtotal' => $subtotal,
                'totaltax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                'totaldiscount' => $totaldiscount,
                'product_des' => $product_des[$key],
                'unit' => $product_unit[$key],
                'status' => $status,                
                'discount_type'  => $discount_type_val,
                'ordered_qty'    => $ordered_qty[$key],
                'transfered_qty' => (numberClean($product_qty[$key]) + numberClean($transfered_qty[$key])),
                'delivered_qty'  => numberClean($delivered_qty[$key]) + numberClean($product_qty[$key]),
                'remaining_qty'  => numberClean($remaining_qty[$key]) - numberClean($product_qty[$key]),
                
            );
            // erp2024 quote items update 06-08-2024
            $quoteitems = $this->quote->quote_details_for_multiplesalesorders($quote_id,$product_id[$key]);
            $remainigqty = $quoteitems['remaining_qty'];
            $deliveredqty = $quoteitems['delivered_qty'];
            $transferedqty = $quoteitems['transfered_qty'];
            $orderedqty = $quoteitems['ordered_qty'];
            $actualqty = $quoteitems['qty'];
            $delveredqty = numberClean($quoteitems['deliveredqty']) + numberClean($product_qty[$key]);
            $transferqty = (numberClean($product_qty[$key]) + numberClean($quoteitems['transfered_qty']));          
            if($orderedqty <= $transferqty){
                $prdstatus = 1;
            }
            else{
                $prdstatus = 0;  
            }
            $quotedata = array(
                'remaining_qty' => numberClean($quoteitems['remaining_qty']) - numberClean($product_qty[$key]),
                'delivered_qty' => $delveredqty,
                'transfered_qty' =>  $transferqty,
                // 'qty' =>  (numberClean($actualqty) - numberClean($product_qty[$key])),
                // 'subtotal' => $quoteitems['subtotal'] - rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc),
                // 'totaltax' => $quoteitems['totaltax'] - rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                // 'totaldiscount' => $quoteitems['totaldiscount'] - rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc),
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

       
        $data1 = array('invoicedate' => $bill_date, 'invoiceduedate' => $bill_due_date, 'subtotal' => $subtotal, 'shipping' => $shipping, 'ship_tax' => $shipping_tax, 'ship_tax_type' => $ship_taxtype, 'discount' => $total_discount, 'tax' => $total_tax, 'total' => $total, 'notes' => $notes, 'csd' => $customer_id, 'items' => $i, 'taxstatus' => $tax, 'discstatus' => $discstatus, 'format_discount' => $discountFormat, 'refer' => $refer, 'term' => $pterms, 'proposal' => $propos, 'multi' => $currency, 'customer_order_date'=>$customer_order_date, 'customer_purchase_order'=>$customer_purchase_order,'completed_status'=>$completed_status,'customer_reference_number' => $this->input->post('customer_reference_number'),'customer_contact_person' => $this->input->post('customer_contact_person'),'customer_contact_number' => $this->input->post('customer_contact_number'),'customer_contact_email' => $this->input->post('customer_contact_email'));
        $this->db->set($data1);
        $this->db->where('id', $salesorderids);
        // $this->db->where('id', $invocieno);
        
        if ($flag) {
            
            if ($this->db->update('cberp_sales_orders', $data1)) {
                history_table_log('cberp_sales_orders_log','sales_order_id',$invocieno,'Created');               
                history_table_log('cberp_quotes_log','quote_id',$quote_id,'Converted to sales order');
                 //erp2024 06-01-2025 detailed history log starts
                detailed_log_history('Salesorder',$invocieno,'Created', $_POST['changedFields']);
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

        
        $ordered_qty = $this->input->post('ordered_qty');
        $transfered_qty = $this->input->post('transfered_qty');
        $delivered_qty = $this->input->post('delivered_qty');
        $remaining_qty = $this->input->post('remaining_qty');

        $i = 0;
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
            $data = array(
                'tid' => $invocieno,
                'pid' => $product_id[$key],
                'product' => $product_name1[$key],
                'code' => $product_hsn[$key],
                'qty' => numberClean($product_qty[$key]),
                'price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
                'tax' => numberClean($product_tax[$key]),
                'discount' => $discountamount,
                'subtotal' => $subtotal,
                'totaltax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                'totaldiscount' => $totaldiscount,
                'product_des' => $product_des[$key],
                'unit' => $product_unit[$key],
                'status' => $status,                
                'discount_type'  => $discount_type_val,
                'ordered_qty'    => $ordered_qty[$key],
                'transfered_qty' => (numberClean($product_qty[$key]) + numberClean($transfered_qty[$key])),
                'delivered_qty'  => numberClean($delivered_qty[$key]) + numberClean($product_qty[$key]),
                'remaining_qty'  => numberClean($remaining_qty[$key]) - numberClean($product_qty[$key]),
                
            );
            // $data = array(
            //     'tid' => $invocieno,
            //     'pid' => $product_id[$key],
            //     'product' => $product_name1[$key],
            //     'code' => $product_hsn[$key],
            //     'qty' => numberClean($product_qty[$key]),
            //     'price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
            //     'tax' => numberClean($product_tax[$key]),
            //     'discount' => $discountamount,
            //     'subtotal' => rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc),
            //     'totaltax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
            //     'totaldiscount' => rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc),
            //     'product_des' => $product_des[$key],
            //     'unit' => $product_unit[$key],
            //     'status' => $status,                
            //     'discount_type' => $discount_type[$key],
            // );

            // erp2024 quote items update 06-08-2024
            $quoteitems = $this->quote->quote_details_for_multiplesalesorders($quote_id,$product_id[$key]);
            $remainigqty = $quoteitems['remaining_qty'];
            $deliveredqty = $quoteitems['delivered_qty'];
            $transferedqty = $quoteitems['transfered_qty'];
            $orderedqty = $quoteitems['ordered_qty'];
            $actualqty = $quoteitems['qty'];
            $delveredqty = numberClean($quoteitems['deliveredqty']) + numberClean($product_qty[$key]);
            $transferqty = (numberClean($product_qty[$key]) + numberClean($quoteitems['transfered_qty']));          
            if($orderedqty <= $transferqty){
                $prdstatus = 1;
            }
            else{
                $prdstatus = 0;  
            }
            $quotedata = array(
                'remaining_qty' => numberClean($quoteitems['remaining_qty']) - numberClean($product_qty[$key]),
                'delivered_qty' => $delveredqty,
                'transfered_qty' =>  $transferqty,
                // 'qty' =>  (numberClean($actualqty) - numberClean($product_qty[$key])),
                // 'subtotal' => $quoteitems['subtotal'] - rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc),
                // 'totaltax' => $quoteitems['totaltax'] - rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                // 'totaldiscount' => $quoteitems['totaldiscount'] - rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc),
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

       
        $data1 = array('invoicedate' => $bill_date, 'invoiceduedate' => $bill_due_date, 'subtotal' => $subtotal, 'shipping' => $shipping, 'ship_tax' => $shipping_tax, 'ship_tax_type' => $ship_taxtype, 'discount' => $total_discount, 'tax' => $total_tax, 'total' => $total, 'notes' => $notes, 'csd' => $customer_id, 'items' => $i, 'taxstatus' => $tax, 'discstatus' => $discstatus, 'format_discount' => $discountFormat, 'refer' => $refer, 'term' => $pterms, 'proposal' => $propos, 'multi' => $currency, 'customer_order_date'=>$customer_order_date, 'customer_purchase_order'=>$customer_purchase_order,'completed_status'=>$completed_status);
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
        $invocieno_n = $this->input->post('invocieno');
        $invocieno = $this->input->post('iid');
        $quote_id = $this->input->post('quote_id');
        $completed_status =  $this->input->post('completed_status');
        $salesorderids = $invocieno_n - 1000;
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

        
        $ordered_qty = $this->input->post('ordered_qty');
        $transfered_qty = $this->input->post('transfered_qty');
        $delivered_qty = $this->input->post('delivered_qty');
        $remaining_qty = $this->input->post('remaining_qty');

        $i = 0;
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
        // $this->db->delete('cberp_sales_orders_items', array('tid' => $invocieno));
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
                $totaldiscount = ($ptotal_disc[$key]) ? rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc):0;
                $subtotal = ($product_subtotal[$key]) ? rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc):0;
            // }
            // else{
                // $discountamount=0;
                // $discount_type_val = "";
                // $totaldiscount = 0;
                // $prdprice = rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc);
                // $subtotal = ($prdprice * numberClean($product_qty[$key]));
            // }
            $data = array(
                'tid' => $salesorderids,
                'pid' => $product_id[$key],
                'product' => $product_name1[$key],
                'code' => $product_hsn[$key],
                'qty' => numberClean($product_qty[$key]),
                'price' => ($product_price[$key]) ? rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc): 0,
                'tax' => 0,
                // 'tax' => ($product_tax[$key]) ? numberClean($product_tax[$key]):0,
                'discount' => $discountamount,
                'subtotal' => $subtotal,
                'totaltax' => ($ptotal_tax[$key]) ? rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc): 0,
                'totaldiscount' => $totaldiscount,
                // 'product_des' => $product_des[$key],
                'unit' => $product_unit[$key],
                'status' => $status,                
                'discount_type'  => $discount_type_val,
               
            );
            if ($quote_id) {
                $data['ordered_qty'] = ($ordered_qty[$key]) ? ($ordered_qty[$key]) : 0;
                $data['transfered_qty'] = ($transfered_qty[$key]) ? ($transfered_qty[$key]) : 0;
                $data['delivered_qty'] = ($delivered_qty[$key]) ? ($delivered_qty[$key]) : 0;
                $data['remaining_qty'] = ($remaining_qty[$key]) ? ($remaining_qty[$key]) : 0;
            }

            // echo "<pre>"; print_r($data);
     
            $this->db->where('tid', $salesorderids);
            $this->db->where('pid', $product_id[$key]);
            $this->db->update('cberp_sales_orders_items', $data);
            // die($this->db->last_query());
            // echo $this->db->last_query()."<br>";

            // erp2024 quote items update 06-08-2024
            $quoteitems = $this->quote->quote_details_for_multiplesalesorders($quote_id,$product_id[$key]);
            if($quoteitems)
            {
                $remainigqty = $quoteitems['remaining_qty'];
                $deliveredqty = $quoteitems['delivered_qty'];
                $transferedqty = $quoteitems['transfered_qty'];
                $orderedqty = $quoteitems['ordered_qty'];
                $actualqty = $quoteitems['qty'];
                $delveredqty = numberClean($quoteitems['deliveredqty']) + numberClean($product_qty[$key]);
                $transferqty = (numberClean($product_qty[$key]) + numberClean($quoteitems['transfered_qty']));          
                if($orderedqty <= $transferqty){
                    $prdstatus = 1;
                }
                else{
                    $prdstatus = 0;  
                }
            }

       
            
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

        $total_discount = ($total_discount) ? rev_amountExchange_s(amountFormat_general($total_discount), $currency, $this->aauth->get_user()->loc) : 0;
        $total_tax = ($total_tax) ? rev_amountExchange_s(amountFormat_general($total_tax), $currency, $this->aauth->get_user()->loc) : 0;

       
        $data1 = array('invoicedate' => $bill_date, 'invoiceduedate' => $bill_due_date, 'subtotal' => $subtotal, 'shipping' => $shipping, 'ship_tax' => $shipping_tax, 'ship_tax_type' => $ship_taxtype, 'discount' => $total_discount, 'tax' => $total_tax, 'total' => $total, 'notes' => $notes, 'csd' => $customer_id, 'items' => $i, 'taxstatus' => $tax, 'discstatus' => $discstatus, 'format_discount' => $discountFormat, 'refer' => $refer, 'term' => $pterms, 'proposal' => $propos, 'multi' => $currency, 'customer_order_date'=>$customer_order_date, 'customer_purchase_order'=>$customer_purchase_order,'completed_status'=>$completed_status,'salesorder_number'=>$invocieno_n,'customer_reference_number' => $this->input->post('customer_reference_number'),'customer_contact_person' => $this->input->post('customer_contact_person'),'customer_contact_number' => $this->input->post('customer_contact_number'),'customer_contact_email' => $this->input->post('customer_contact_email'));
        $this->db->set($data1);
        $this->db->where('id', $salesorderids);
        $this->db->update('cberp_sales_orders', $data1);   
        
        // file upload section starts 22-01-2025
        // if($_FILES['upfile'])
        // {
        //     upload_files($_FILES['upfile'], 'Salesorder',$salesorderids);
        // }
            // file upload section ends 22-01-2025   
        // history_table_log('cberp_sales_orders_log','sales_order_id',$salesorderids,'Data Saved As Draft'); 
	    //erp2024 06-01-2025 detailed history log starts
        // detailed_log_history('Salesorder',$salesorderids,'Data Saved As Draft', $_POST['changedFields']);
        //erp2024 06-01-2025 detailed history log ends 
        if ($flag) {
            
            echo json_encode(array(
                'status' => 'Success'
            ));

        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
                "Please add atleast one product in invoice $invocieno"));
            $transok = false;
        }

    }
   

    public function deliverynote()
    {
      
        $data['permissions'] = load_permissions('Sales','Sales','Delivery Notes','View Page');
        $this->load->model('customers_model', 'customers');
        $data['customergrouplist'] = $this->customers->group_list();
        $customersalesman = $this->customers->customer_salesman($data['customergrouplist'][0]['id']);
        $data['salesman'] = $data['customersalesman']['data'];
        $tid = intval($this->input->get('id'));
        $data['id'] = $tid;
        // $data['trackingdata'] = tracking_details('sales_id',$tid);
        $this->session->set_userdata('salesorderid', $tid);
        $data['terms'] = $this->quote->billingterms();

        // $data['invoice'] = $this->quote->salesorder_details_draft($tid);
        // $data['products'] = $this->quote->salesorder_products_deliverynotes($tid);

        $this->load->model('deliverynote_model', 'deliverynote');
        $data['invoice'] = $this->deliverynote->deliverynoteby_number($tid);
        // echo "<pre>"; print_r($data['invoice']); die();
        $data['products'] = $this->deliverynote->deliverynote_products($tid);
        // echo "<pre>"; print_r($data['products']); die();
        $salesorder_id = $data['invoice']['salesorder_id'];

        $data['creditlimtcompare'] = $this->quote->compare_delivery_product_price_with_avail_credit_limit($salesorder_id);
        $data['return_status'] = $this->deliverynote->check_delivered_and_return_qty_equal($tid);
        

        $data['warehouse_title'] = (!empty($data['invoice']['warehouseid'])) ? $this->quote->warehouse_by_id($data['invoice']['warehouseid']) : warehouse_list();
        // echo "<pre>"; print_r($data['warehouse_title']); die();
       
        // $data['deliverynoteid'] = $this->quote->deliverynote_number($tid);

        $data['currency'] = $this->quote->currencies();
        $head['title'] = "Delivery Note #" . $data['invoice']['delnotenumber'];
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['warehouse'] = $this->quote->warehouses();
        $this->load->model('plugins_model', 'plugins');
        $data['exchange'] = $this->plugins->universal_api(5);
        $data['configurations'] = $this->configurations;
        $this->load->library("Common");
        $data['taxlist'] = $this->common->taxlist_edit($data['invoice']['taxstatus']);        
        $data['trackingdata'] = tracking_details('deliverynote_id',$tid);
        $data['prefix'] = $this->configurations['invoiceprefix'];
        $this->load->model('invoices_model', 'invocies');
        $data['lastinvoice'] = $this->invocies->lastinvoice();
        $data['log'] = $this->quote->getnotehistory($tid); 
        $data['images'] = get_uploaded_images('Deliverynote',$tid);
        $this->load->view('fixed/header', $head);
        $this->load->view('sales/delivery-note', $data);
        $this->load->view('fixed/footer');
    }
   
    public function deliverynoteaction()
    {
       
       
        //session value
        $current_value = $this->session->userdata('repeatsubmit');
        $transaction_number = get_latest_trans_number();
        $deleverynotetid = $this->input->post('invocieno');        
        // $deleverynotetid = $this->input->post('invocieno');        
        $data1['tid'] = intval($this->input->post('delevery_note_id'))+1000;
        // $data1['tid'] = $this->input->post('invocieno');
        $store_id = $this->input->post('store_id');
        //erp2024 note 26-09-2024 
        $data1['note'] = $this->input->post('note');

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
        if($data1['completed_status']==0){
            $data1['status'] = 'Draft';
        }
        else if($data1['completed_status']==1){
            $data1['status'] = 'Printed';
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

        // erp2024 19-12-2024 load default accounts
        $default_cost_of_goods_account = default_chart_of_account('cost_of_goods_solid');
        $default_inventory_account = default_chart_of_account('inventory');

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
            $actulprice = $productprice*$productty;            
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
                'totaltax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                'totaldiscount' => rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc),
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

            $producttransdata =  [
                'acid' => $income_account_number[$key],
                'type' => 'Asset',
                'cat' => 'Deliverynote',
                'credit' => $productwise_total,
                // 'credit' => $actulprice,
                'eid' => $this->session->userdata('id'),
                'date' => date('Y-m-d'),
                'transaction_number'=>$transaction_number,
                // 'invoice_number'=>$invoice_number
            ];
            $this->db->set('lastbal', 'lastbal - ' . $productwise_total, FALSE);
            $this->db->where('acn', $income_account_number[$key]);
            $this->db->update('cberp_accounts'); 
            $this->db->insert('cberp_transactions', $producttransdata);


            // cost of goods transaction
            $total_product_cost = $product_cost[$key]*numberClean($product_qty[$key]);
            $cost_of_goods_data =  [
                'acid' => $default_cost_of_goods_account,
                'type' => 'Expense',
                'cat' => 'Deliverynote',
                'debit' => $total_product_cost,
                'eid' => $this->session->userdata('id'),
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
                'eid' => $this->session->userdata('id'),
                'date' => date('Y-m-d'),
                'transaction_number'=>$transaction_number,
            ];
            $this->db->set('lastbal', 'lastbal - ' . $total_product_cost, FALSE);
            $this->db->where('acn', $default_inventory_account);
            $this->db->update('cberp_accounts'); 
            $this->db->insert('cberp_transactions', $inventory_data);


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
                    $upqty = array('qty' => $updateQty);
                    $this->db->where('pid', $pid);
                    $this->db->update('cberp_products', $upqty);
                }

               
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
                $orderedqty = $salesorderitems['ordered_qty'];
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
      
          // erp2024 transactions starts 25-10-2024
              
            $invoice_receivable_account_details = get_account_details_for_invoicing("Current Asset",'Accounts Receivable');
            $latest_total = $total;
            $receivable_data = [
                'acid' => $invoice_receivable_account_details['acn'],
                'type' => 'Asset',
                'cat' => 'Deliverynote',
                'debit' => $totalamountcust,
                'eid' => $this->session->userdata('id'),
                'date' => date('Y-m-d'),
                'transaction_number'=>$transaction_number,
            ];
            $this->db->insert('cberp_transactions',$receivable_data);
            $this->db->set('lastbal', 'lastbal + ' .$totalamountcust, FALSE);
            $this->db->where('acn', $invoice_receivable_account_details['acn']);
            $this->db->update('cberp_accounts'); 
            // erp2024 transactions ends 25-10-2024 

            //erp2024 totaldiscount transaction 11-11-2024 starts
            if($total_discount>0)
            {
                $discount_account_details = get_account_details_for_invoicing("Expense",'Sales Discount');
                $discount_data = [
                    'acid' => $discount_account_details['acn'],
                    'type' => 'Asset',
                    'cat' => 'Deliverynote',
                    'debit' => $total_discount,
                    'eid' => $this->session->userdata('id'),
                    'date' => date('Y-m-d'),
                    'transaction_number'=>$transaction_number,
                    // 'invoice_number'=>$invoice_number
                ];
                $this->db->insert('cberp_transactions',$discount_data);
                $this->db->set('lastbal', 'lastbal + ' .$total_discount, FALSE);
                $this->db->where('acn', $discount_account_details['acn']);
                $this->db->update('cberp_accounts'); 
            }
            if($order_discount)
            {
                $order_discount_account_details = get_account_details_for_invoicing("Expense",'Order Discount');
                $discount_data1 = [
                    'acid' => $order_discount_account_details['acn'],
                    // 'account' => $order_discount_account_details['holder'],
                    'type' => 'Asset',
                    'cat' => 'Deliverynote',
                    'debit' => $order_discount,
                    'eid' => $this->session->userdata('id'),
                    'date' => date('Y-m-d'),
                    'transaction_number'=>$transaction_number,
                    // 'invoice_number'=>$invoice_number
                ];
                $this->db->insert('cberp_transactions',$discount_data1);
                $this->db->set('lastbal', 'lastbal + ' .$order_discount, FALSE);
                $this->db->where('acn', $order_discount_account_details['acn']);
                $this->db->update('cberp_accounts'); 
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

    public function delivery_print_action()
    {
       
       
        //session value
        $current_value = $this->session->userdata('repeatsubmit');
      
        $deleverynotetid = $this->input->post('invocieno');         
        $data1['tid'] = intval($this->input->post('delevery_note_id'))+1000;
        $store_id = $this->input->post('store_id');
        //erp2024 note 26-09-2024
        $data1['note'] = $this->input->post('note');


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
        $data1['created_date'] = date("Y-m-d");
        $data1['created_time'] = date("H:i:s");
        $data1['pick_ticket_status'] = '1';
        
        $checkres = $this->quote->check_deliverynote_creation_once_completed($data1['salesorder_id']);
        if (!empty($checkres)) {
            $salesorder_deltid = $this->input->post('delnote_tid');
        } else {
            $salesorder_deltid = $deleverynotetid;
        }
        $existingdeliverynoteid =  $this->quote->deliverynoteid_by_salesorder_number($data1['salesorder_number'],$data1['tid']);
        $last_insert_id =$this->input->post('delevery_note_id');
        $this->db->update('cberp_delivery_notes', $data1,['delevery_note_id'=>$last_insert_id]);
        // die($this->db->last_query());
        $this->db->delete('cberp_delivery_note_items',['delevery_note_id'=>$last_insert_id]);

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
        $tid = $this->session->userdata('salesorderid');
        //erp2024 19-12-2024        
        $product_cost = $this->input->post('product_cost', true);

        $total_discount =0;
        $total_tax  =0;        
        $wholestatus = 1;
        foreach ($pid as $key => $value) {
            $status ="";
            $total_discount += numberClean(@$ptotal_disc[$key]);
            $total_tax += numberClean($ptotal_tax[$key]);
            foreach ($statusList as $item) {            
                if($item['pid']==$product_id[$key]){
                    $status = $item['status'];
                }            
            }

            $data = array(
                'salesorder_id' => numberClean($data1['salesorder_id']),
                'delevery_note_id' => $this->input->post('delevery_note_id'),
                'delnote_number' => $this->input->post('invocieno_demo'),
                'product' => $product_name1[$key],
                'product_id' => numberClean($product_id[$key]),
                'product' => $product_name1[$key],
                'product_code' => $product_hsn[$key],
                'product_qty' => numberClean($product_qty[$key]),
                'salesorder_product_qty' => numberClean($old_product_qty[$key]),
                'product_price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
                'product_tax' => numberClean($product_tax[$key]),
                'subtotal' => rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc),
                'totaltax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                'totaldiscount' => rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc),
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
            $flag = true;
            $productlist[$prodindex] = $data;
            $i += numberClean($product_qty[$key]);
            $prodindex++;

            $this->db->insert('cberp_delivery_note_items', $data);

        }
        
        if ($productlist) {
            history_table_log('delivery_note_log','deliverynote_id',$last_insert_id,'Print Picking List');
            //erp2024 06-01-2025 detailed history log starts
            detailed_log_history('Deliverynote',$last_insert_id,'Print Picking List', $_POST['changedFields']);
            //erp2024 06-01-2025 detailed history log ends 
	       // file upload section starts 22-01-2025
                if($_FILES['upfile'])
                {
                    upload_files($_FILES['upfile'], 'Deliverynote',$last_insert_id);
                }
                // file upload section ends 22-01-2025
            echo json_encode(array('status' => 'Success', 'message' => 'Successfully Printed', 'data' => $data1['salesorder_number']));   

        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
                "Please add atleast one product in invoice $invocieno"));
            $transok = false;
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
                'totaltax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                'totaldiscount' => rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc),
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
                     $upqty = array('qty' => $updateQty);
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
                $orderedqty = $salesorderitems['ordered_qty'];
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
                    'qty' =>  $delnote_qty,
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
    public function update_warehouse_products($pid, $store_id, $qty)
    {
        $this->db->select('stock_qty');
        $this->db->from('cberp_product_to_store');
        $this->db->where('product_id', $pid);
        $this->db->where('store_id', $store_id);
        $warehouseQry = $this->db->get(); 
        $warehouseresult = $warehouseQry->row_array();
        $onhandwh = intval($warehouseresult['stock_qty']);
        $onhandwhQty = $onhandwh - $qty; 

        $upqty1 = array('stock_qty' => $onhandwhQty);
        $this->db->where('product_id', $pid);
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
        $data['taxlist'] = $this->common->taxlist_edit($data['invoice']['taxstatus']);
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
            $discstatus = 0;
        } else {
            $discstatus = 1;
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
                        'code' => $product_hsn[$key],
                        'qty' => numberClean($product_qty[$key]),
                        'price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
                        'tax' => numberClean($product_tax[$key]),
                        'discount' => numberClean($product_discount[$key]),
                        'subtotal' => rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc),
                        'totaltax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                        'totaldiscount' => rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc),
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

            $data = array('invoicedate' => $bill_date, 'invoiceduedate' => $bill_due_date, 'subtotal' => $subtotal, 'shipping' => $shipping, 'ship_tax' => $shipping_tax, 'ship_tax_type' => $ship_taxtype, 'discount' => $total_discount, 'tax' => $total_tax, 'total' => $total, 'notes' => $notes, 'csd' => $customer_id, 'items' => $i, 'taxstatus' => $tax, 'discstatus' => $discstatus, 'format_discount' => $discountFormat, 'refer' => $refer, 'term' => $pterms, 'proposal' => $propos, 'multi' => $currency);
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

           
            $sequentialdata = $this->quote->get_sales_seqnumber_tid($quote_id);
            $data['newsalesordernumber'] = $sequentialdata['newsalesordernumber'];
            $data['salesseqnumber'] = $sequentialdata['salesseqnumber'];
            $data['invoice'] = $this->quote->quote_details_byquoteid($quote_id);

            // echo "<pre>"; print_r($data['invoice']); die();
            $data['taxlist'] = $this->common->taxlist_edit($data['invoice']['taxstatus']);
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
                        'remaining_qty' => $row['remaining_qty'],
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

            // echo "<pre>";  print_r($salesorders); die();
            $data['trackingdata'] = tracking_details('quote_id',intval($this->input->get('id')));
            // print_r($data['trackingdata']); die();
            $data['salesorders'] = $salesorders;
            $data['productdata'] = $productdata;
            $this->load->view('fixed/header', $head);
            $this->load->view('sales/new-salesorder-from-partial-quote', $data);
            $this->load->view('fixed/footer');
        }
        else if($token==2){ 
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
            $data['taxlist'] = $this->common->taxlist_edit($data['invoice']['taxstatus']);                
            $data['configurations'] = $this->configurations;
            $this->load->view('fixed/header', $head);
            $this->load->view('sales/quotetosalesorder', $data);
            $this->load->view('fixed/footer');
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
            // echo "<pre>"; print_r($data['invoice']); die();
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
            $data['taxlist'] = $this->common->taxlist_edit($data['invoice']['taxstatus']);                
            $data['configurations'] = $this->configurations;
            $this->load->view('fixed/header', $head);
            $this->load->view('sales/quotetosalesorder-draft', $data);
            $this->load->view('fixed/footer');
        }
       
        
    }

    public function generate_new_salesorder(){

        
        $quote_id = $_POST['quote_id'];
        $quoteitems =$this->quote->get_quote_items_for_new_salesorder($quote_id);
        $i = 0;
        
        $table = '<table class="table table-bordered dataTable">';
        $table .= '<thead>';
        $table .= '<tr>';
        $table .= '<th width="20%">Item Name</th>';
        $table .= '<th width="7%">Item No.</th>';
        $table .= '<th width="1%">Lead Qty</th>';
        $table .= '<th width="1%">Quote Qty</th>';
        $table .= '<th  width="1%">Received SO Qty</th>';
        $table .= '<th width="1%">Remaining SO</th>';
        $table .= '<th width="10%" class="text-center">New SO Qty</th>';
        $table .= '<th width="14%" class="text-center">Discount</th>';
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
            $table .= '<td>' . htmlspecialchars($item['product']) . '<input type="hidden" class="form-control" name="product_name[]" id="product_name-' . $i . '"  value="' . $item['product'] . '"><input type="hidden" name="hsn[]" id="unit-' . $i . '" value="' . $item['product_code'] . '"></td>';
            $table .= '<td>' . $item['product_code'] . '</td>';
            $table .= '<td class="text-center">' . htmlspecialchars(intval($item['leadqty'])) . '</td>';
            $table .= '<td class="text-center">' . htmlspecialchars(intval($item['qty'])) . '<input type="hidden" class="form-control req" name="trasferedqty[]" id="trasferedqty-' . $i . '" value="' .intval($item['trasferedqty']) . '"><input type="hidden" class="form-control req" name="orderedqty[]" id="orderedqty-' . $i . '" value="' .intval($item['qty']) . '"></td>';
            $table .= '<td class="text-center">' . htmlspecialchars(intval($item['trasferedqty'])) . '<input type="hidden" class="form-control req" name="trasferedqty[]" id="trasferedqty-' . $i . '" value="' .intval($item['trasferedqty']) . '"></td>';
            $table .= '<td class="text-center">' . htmlspecialchars(intval($item['remainingqty'])) . '<input type="hidden" class="form-control req" name="remainingqty[]" id="remainingqty-' . $i . '" value="' .intval($item['remainingqty']) . '"></td>';
            // $table .= '<td><input type="text" class="form-control" name="so_qty[]" value="' . htmlspecialchars($item['so_qty']) . '"></td>';
            $table .= '<td><input type="number" class="form-control req amnt" name="product_qty[]" id="amount-'.$i.'" onkeypress="return isNumber(event)" onkeyup="checkqty('.$i.'), rowTotal('.$i.'), billUpyog()" autocomplete="off" value="0"></td>';
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
                <input type="hidden" class="form-control" name="lowest_price[]" id="lowestprice-' . $i . '" onkeypress="return isNumber(event)" autocomplete="off" value="' . $item['lowest_price'] . '">
                <input type="hidden" class="form-control" name="maxdiscountrate[]" id="maxdiscountrate-' . $i . '" onkeypress="return isNumber(event)" autocomplete="off" value="' . $item['max_disrate'] . '"><input type="hidden" name="hsn[]" id="unit-' . $i . '" value="' . $item['code'] . '"></td>';
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
        $data1 = array('invoicedate' => $invoicedate, 'invoiceduedate' => $invocieduedate, 'subtotal' => $subtotal, 'shipping' => $shipping, 'ship_tax' => $shipping_tax, 'ship_tax_type' => $ship_taxtype,'total' => $total, 'notes' => $notes, 'csd' => $customer_id, 'taxstatus' => $tax, 'discstatus' => $discstatus, 'format_discount' => $discountFormat, 'refer' => $refer, 'term' => $pterms, 'proposal' => $propos, 'multi' => $currency, 'customer_order_date'=>$customer_order_date, 'customer_purchase_order'=>$customer_purchase_order,'salesorder_number'=>$salesorder_number, 'seq_number'=>$seq_number,'quote_id'=>$quote_id,'tid'=>$salesordertid, 'completed_status' => $completed_status,'customer_reference_number' => $this->input->post('customer_reference_number'),'customer_contact_person' => $this->input->post('customer_contact_person'),'customer_contact_number' => $this->input->post('customer_contact_number'),'customer_contact_email' => $this->input->post('customer_contact_email'));

        $this->db->insert('cberp_sales_orders', $data1);
        // die($this->db->last_query());
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
            
            $ordered_qty = $this->input->post('orderedqty');
            $transfered_qty = $this->input->post('trasferedqty');
            $delivered_qty = $this->input->post('deliveredqty');
            $remaining_qty = $this->input->post('remainingqty');
            
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
                    $data = array(
                        'tid' => $insert_id,
                        'pid' => $product_id[$key],
                        'product' => $product_name1[$key],
                        'code' => $product_hsn[$key],
                        'qty' => numberClean($product_qty[$key]),
                        'price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
                        'tax' => numberClean($product_tax[$key]),
                        'discount' => $discountamount,
                        'subtotal' => $subtotal,
                        'totaltax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                        'totaldiscount' => $totaldiscount,
                        'product_des' => $product_des[$key],
                        'unit' => $product_unit[$key],
                        'status' => $status,                
                        'discount_type' => $discount_type_val,
                        'ordered_qty'    => $ordered_qty[$key],
                        'transfered_qty' => (numberClean($product_qty[$key]) + numberClean($transfered_qty[$key])),
                        'delivered_qty'  => (numberClean($product_qty[$key]) + numberClean($transfered_qty[$key])),
                        // 'delivered_qty'  => numberClean($delivered_qty[$key]) + numberClean($product_qty[$key]),
                        'remaining_qty'  => numberClean($remaining_qty[$key]) - numberClean($product_qty[$key]),
                        'salesorder_number'=>$salesorder_number
                    );
                    // erp2024 quote items update 06-08-2024
                    $quoteitems = $this->quote->quote_details_for_multiplesalesorders($quote_id,$product_id[$key]);
                    $remainigqty = $quoteitems['remaining_qty'];
                    $deliveredqty = $quoteitems['delivered_qty'];
                    $transferedqty = $quoteitems['transfered_qty'];
                    $orderedqty = $quoteitems['ordered_qty'];
                    $actualqty = $quoteitems['qty'];
                    $delveredqty = numberClean($quoteitems['deliveredqty']) + numberClean($product_qty[$key]);
                    $transferqty = (numberClean($product_qty[$key]) + numberClean($quoteitems['transfered_qty'])); 

                    if($orderedqty <= $transferqty){
                        $prdstatus = 1;
                    }
                    else{
                        $prdstatus = 0;  
                    }
                    $quotedata = array(
                        'remaining_qty' => numberClean($quoteitems['remaining_qty']) - numberClean($product_qty[$key]),
                        'delivered_qty' => $delveredqty,
                        'transfered_qty' =>  $transferqty,
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
                $this->quote->update_quote_status_for_subitems($quote_id, $salesordertid, $salesorderid);
                $total_discount = rev_amountExchange_s(amountFormat_general($total_discount), $currency, $this->aauth->get_user()->loc);
                $total_tax = rev_amountExchange_s(amountFormat_general($total_tax), $currency, $this->aauth->get_user()->loc);
                $this->db->update('cberp_sales_orders', ['items' => $i,'discount' => $total_discount,'tax' => $total_tax], ['id'=> $quote_id]);                
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
            $discstatus = 0;
        } else {
            $discstatus = 1;
        }

        if ($customer_id == 0) {
            echo json_encode(array('status' => 'Error', 'message' =>
                $this->lang->line('Please add a new client')));
            exit;
        }


        $data1 = array('invoicedate' => $invoicedate, 'invoiceduedate' => $invocieduedate, 'subtotal' => $subtotal, 'shipping' => $shipping, 'ship_tax' => $shipping_tax, 'ship_tax_type' => $ship_taxtype,'total' => $total, 'notes' => $notes, 'csd' => $customer_id, 'taxstatus' => $tax, 'discstatus' => $discstatus, 'format_discount' => $discountFormat, 'refer' => $refer, 'term' => $pterms, 'proposal' => $propos, 'multi' => $currency, 'customer_order_date'=>$customer_order_date, 'customer_purchase_order'=>$customer_purchase_order,'salesorder_number'=>$salesorder_number, 'seq_number'=>$seq_number,'quote_id'=>$quote_id,'tid'=>$salesordertid, 'completed_status' => $completed_status,'customer_reference_number' => $this->input->post('customer_reference_number'),'customer_contact_person' => $this->input->post('customer_contact_person'),'customer_contact_number' => $this->input->post('customer_contact_number'),'customer_contact_email' => $this->input->post('customer_contact_email'));


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
            
            $ordered_qty = $this->input->post('orderedqty');
            $transfered_qty = $this->input->post('trasferedqty');
            $delivered_qty = $this->input->post('deliveredqty');
            $remaining_qty = $this->input->post('remainingqty');
            
            // print_r($product_discount); die();
            foreach ($pid as $key => $value) {
                // if(numberClean($product_qty[$key]) > 0)
                // {
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
                    $data = array(
                        'tid' => $insert_id,
                        'pid' => $product_id[$key],
                        'product' => $product_name1[$key],
                        'code' => $product_hsn[$key],
                        'qty' => numberClean($product_qty[$key]),
                        'price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
                        'tax' => numberClean($product_tax[$key]),
                        'discount' => $discountamount,
                        'subtotal' => $subtotal,
                        'totaltax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                        'totaldiscount' => $totaldiscount,
                        'product_des' => $product_des[$key],
                        'unit' => $product_unit[$key],
                        'status' => $status,                
                        'discount_type' => $discount_type_val,
                        'ordered_qty'    => $ordered_qty[$key],
                        // 'transfered_qty' => (numberClean($product_qty[$key]) + numberClean($transfered_qty[$key])),
                        // 'delivered_qty'  => (numberClean($product_qty[$key]) + numberClean($transfered_qty[$key])),
                        // // 'delivered_qty'  => numberClean($delivered_qty[$key]) + numberClean($product_qty[$key]),
                        // 'remaining_qty'  => numberClean($remaining_qty[$key]) - numberClean($product_qty[$key]),
                        'salesorder_number'=>$salesorder_number
                    );
                    // erp2024 quote items update 06-08-2024
                    // $quoteitems = $this->quote->quote_details_for_multiplesalesorders($quote_id,$product_id[$key]);
                    // $remainigqty = $quoteitems['remaining_qty'];
                    // $deliveredqty = $quoteitems['delivered_qty'];
                    // $transferedqty = $quoteitems['transfered_qty'];
                    // $orderedqty = $quoteitems['ordered_qty'];
                    // $actualqty = $quoteitems['qty'];
                    // $delveredqty = numberClean($quoteitems['deliveredqty']) + numberClean($product_qty[$key]);
                    // $transferqty = (numberClean($product_qty[$key]) + numberClean($quoteitems['transfered_qty'])); 

                    // if($orderedqty <= $transferqty){
                    //     $prdstatus = 1;
                    // }
                    // else{
                    //     $prdstatus = 0;  
                    // }
                    // $quotedata = array(
                    //     'remaining_qty' => numberClean($quoteitems['remaining_qty']) - numberClean($product_qty[$key]),
                    //     'delivered_qty' => $delveredqty,
                    //     'transfered_qty' =>  $transferqty,
                    //     'prdstatus' => $prdstatus
                    // );
                    // $this->quote->quote_items($quote_id,$product_id[$key],$quotedata);
                    
                    $flag = true;
                    $productlist[$prodindex] = $data;
                    $i += numberClean($product_qty[$key]);;
                    $prodindex++;
                }
            // }
            if(!empty($productlist))
            {
                $this->db->insert_batch('cberp_sales_orders_items', $productlist);
                //update quote status
                // $this->quote->update_quote_status($quote_id, $salesordertid, $salesorderid);
                $total_discount = rev_amountExchange_s(amountFormat_general($total_discount), $currency, $this->aauth->get_user()->loc);
                $total_tax = rev_amountExchange_s(amountFormat_general($total_tax), $currency, $this->aauth->get_user()->loc);
                $this->db->update('cberp_sales_orders', ['items' => $i,'discount' => $total_discount,'tax' => $total_tax], ['id'=> $insert_id]);                
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
        $salesorder_id = $this->input->post('salesorder_id');
        $delevery_note_id = $this->input->post('delevery_note_id');
        $this->db->update('cberp_sales_orders', ['converted_status' => '0'], ['id'=> $salesorder_id]);
        $this->db->update('cberp_delivery_notes', ['status' => 'Canceled'], ['delevery_note_id'=> $delevery_note_id]);
        //erp2024 06-01-2025 detailed history log starts
        detailed_log_history('Deliverynote',$delevery_note_id,'Reverted', $_POST['changedFields']);
        //erp2024 06-01-2025 detailed history log ends 
        echo json_encode(array('status' => 'success'));
    }
    public function lead_reassigned()
    {
        $leadid = $this->input->post('leadid');
        $assigned_val = $this->input->post('assigned_val');
        $this->db->update('cberp_customer_leads', ['assigned_to' => '','enquiry_status' => 'Open', 'accepted_dt'=>NULL], ['lead_id'=> $leadid]); 

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
        $this->db->update('cberp_customer_leads', ['enquiry_status' => 'Accepted','accepted_dt'=>date('Y-m-d H:i:s')], ['lead_id'=> $leadid]);
      
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
        history_table_log('cberp_quotes_log','quote_id',$quote_id,'Quotation Reverted');
        //erp2024 06-01-2025 detailed history log starts
        detailed_log_history('Quote',$quote_id,'Quotation Reverted', $changedFields);
        //erp2024 06-01-2025 detailed history log ends 
        $this->db->update('cberp_quotes', ['eid' => NULL,'term'=>NULL,'approvalflg'=>'0','status'=>'Reverted'], ['id'=> $quote_id]);
        echo json_encode(array('status' => 'success'));
    }

    public function quote_reverted_by_dmin()
    {
        $quote_id = $this->input->post('quote_id'); 
        history_table_log('cberp_quotes_log','quote_id',$quote_id,'Quotation Reverted');
        //erp2024 06-01-2025 detailed history log starts
        detailed_log_history('Quote',$quote_id,'Quotation Reverted', $changedFields);
        //erp2024 06-01-2025 detailed history log ends 
        $this->db->update('cberp_quotes', ['eid' => NULL,'term'=>NULL,'approvalflg'=>'0','status'=>'Reverted'], ['id'=> $quote_id]);
        echo json_encode(array('status' => 'success'));
    }
    public function quote_accept()
    {
        $quote_id = $this->input->post('quote_id');
        history_table_log('cberp_quotes_log','quote_id',$quote_id,'Quotation Accepted');
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

}
