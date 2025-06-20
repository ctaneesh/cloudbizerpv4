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

class Invoices extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('invoices_model', 'invocies');
         $this->load->model('plugins_model', 'plugins');
         $this->load->model('customer_enquiry_model', 'customer_enquiry');
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
        
    }

    //create invoice
    public function create()
    {

         $data['emp'] = $this->plugins->universal_api(69);
        if ($data['emp']['key1']) {
            $this->load->model('employee_model', 'employee');
            $data['employee'] = $this->employee->list_employee();
        }

        $this->load->library("Common");
        $data['custom_fields_c'] = $this->custom->add_fields(1);

        $this->load->model('customers_model', 'customers');
        $this->load->model('plugins_model', 'plugins');
        $data['exchange'] = $this->plugins->universal_api(5);
        $data['customergrouplist'] = $this->customers->group_list();
        $data['lastinvoice'] = $this->invocies->lastinvoice();
        $data['warehouse'] = $this->invocies->warehouses();
        $data['terms'] = $this->invocies->billingterms();
        $data['currency'] = $this->invocies->currencies();
        $this->load->library("Common");
        $data['taxlist'] = $this->common->taxlist($this->config->item('tax'));
        $head['title'] = "New Invoice";
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['taxdetails'] = $this->common->taxdetail();
        $data['custom_fields'] = $this->custom->add_fields(2);
        $this->load->view('fixed/header', $head);
        $this->load->view('invoices/newinvoice', $data);
        $this->load->view('fixed/footer');
    }

    //create customer enquiry
    public function newcustomerenquiry()
    {

         $data['emp'] = $this->plugins->universal_api(69);
        if ($data['emp']['key1']) {
            $this->load->model('employee_model', 'employee');
            $data['employee'] = $this->employee->list_employee();
        }

        $this->load->library("Common");
        $data['custom_fields_c'] = $this->custom->add_fields(1);

        $this->load->model('customers_model', 'customers');
        $this->load->model('plugins_model', 'plugins');
        $data['exchange'] = $this->plugins->universal_api(5);
        $data['customergrouplist'] = $this->customers->group_list();
        $data['lastenquirynumber'] = $this->invocies->lastenquiry();
        $this->load->library("Common");
        $data['taxlist'] = $this->common->taxlist($this->config->item('tax'));
        $head['title'] = "New Enquiry";
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['taxdetails'] = $this->common->taxdetail();
        $data['custom_fields'] = $this->custom->add_fields(2);
        $this->load->view('fixed/header', $head);
        $this->load->view('invoices/newcustomerenquiry', $data);
        $this->load->view('fixed/footer');
    }

    public function customerenquiryaction(){
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $enquiry_data = array(
                'lead_number' => $this->input->post('lead_number'),
                'customer_type' => $this->input->post('customerType'),
                'customer_name' => $this->input->post('customer_name'),
                'customer_phone' => $this->input->post('customer_phone'),
                'customer_email' => $this->input->post('customer_email'),
                'customer_address' => $this->input->post('customer_address'),
                'date_received' => $this->input->post('date_received'),
                'due_date' => $this->input->post('due_date'),
                'source_of_enquiry' => $this->input->post('source_of_enquiry'),
                'assigned_to' => $this->input->post('assignedto'),
                'comments' => $this->input->post('comments'),
                'email_contents' => $this->input->post('email_contents'),
                'enquiry_status' => $this->input->post('enquiry_status'),
                'created_by' => $this->session->userdata('id'),
                'created_date' => date('Y-m-d'),
            );
            if(!empty($enquiry_data)){
                $this->db->insert('cberp_customer_leads', $enquiry_data);
                $enquiryid = $this->db->insert_id();
                $config['upload_path'] = FCPATH . 'uploads/';
                $config['allowed_types'] = 'pdf|jpg|jpeg|png|csv|xls';
                $config['encrypt_name'] = TRUE;
                $this->load->library('upload', $config);
                if (isset($_FILES['upfile'])) {
                    $files = $_FILES['upfile'];
                    if(!empty($files))
                    {
                        $uploaded_data['lead_id'] = $enquiryid;
                        $uploaded_data['lead_number'] = $this->input->post('lead_number');
                        foreach ($files['name'] as $key => $filename) {
                            $_FILES['userfile']['name'] = $files['name'][$key];
                            $_FILES['userfile']['type'] = $files['type'][$key];
                            $_FILES['userfile']['tmp_name'] = $files['tmp_name'][$key];
                            $_FILES['userfile']['error'] = $files['error'][$key];
                            $_FILES['userfile']['size'] = $files['size'][$key];

                            if ($this->upload->do_upload('userfile')) {
                                $uploaded_info = $this->upload->data();
                                $uploaded_data['file_name'] = $uploaded_info['file_name'];
                                $this->db->insert('cberp_customer_lead_attachments', $uploaded_data);
                            } else {
                                // Handle upload errors
                                $error = array('error' => $this->upload->display_errors());
                                // print_r($error); // You can handle errors as needed
                            }
                        }
                    }
                }
                
                $this->leads();

            }
            
        }
    }
    public function customerenquiryeditaction(){
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $enquiry_data = array(
                'lead_number' => $this->input->post('lead_number'),
                'customer_type' => $this->input->post('customerType'),
                'customer_name' => $this->input->post('customer_name'),
                'customer_phone' => $this->input->post('customer_phone'),
                'customer_email' => $this->input->post('customer_email'),
                'customer_address' => $this->input->post('customer_address'),
                'date_received' => $this->input->post('date_received'),
                'due_date' => $this->input->post('due_date'),
                'source_of_enquiry' => $this->input->post('source_of_enquiry'),
                'assigned_to' => $this->input->post('assignedto'),
                'comments' => $this->input->post('comments'),
                'email_contents' => $this->input->post('email_contents'),
                'enquiry_status' => $this->input->post('enquiry_status'),
                'created_by' => $this->session->userdata('id'),
                'created_date' => date('Y-m-d'),
            );
            if(!empty($enquiry_data)){
                $lead_id = $this->input->post('lead_id');
                $this->db->where('lead_id', $lead_id);
                $this->db->update('cberp_customer_leads', $enquiry_data);

                $config['upload_path'] = FCPATH . 'uploads/';
                $config['allowed_types'] = 'pdf|jpg|jpeg|png|csv|xls';
                $config['encrypt_name'] = TRUE;
                $this->load->library('upload', $config);
                $files = $_FILES['upfile']; // Get uploaded files array
                // echo "<pre>"; print_r($files); die();
                if(!empty($files))
                {
                    $uploaded_data['lead_id'] = $lead_id;
                    $uploaded_data['lead_number'] = $this->input->post('lead_number');
                    foreach ($files['name'] as $key => $filename) {
                        $_FILES['userfile']['name'] = $files['name'][$key];
                        $_FILES['userfile']['type'] = $files['type'][$key];
                        $_FILES['userfile']['tmp_name'] = $files['tmp_name'][$key];
                        $_FILES['userfile']['error'] = $files['error'][$key];
                        $_FILES['userfile']['size'] = $files['size'][$key];

                        if ($this->upload->do_upload('userfile')) {
                            $uploaded_info = $this->upload->data();
                            $uploaded_data['file_name'] = $uploaded_info['file_name'];
                            $this->db->insert('cberp_customer_lead_attachments', $uploaded_data);
                        } else {
                            // Handle upload errors
                            $error = array('error' => $this->upload->display_errors());
                            // print_r($error); // You can handle errors as needed
                        }
                    }
                }
                
                $this->leads();

            }
            
        }
    }
    public function deletesubItem(){
        $lead_attachment_id = $this->input->post('selectedProducts');
        $this->db->where('lead_attachment_id', $lead_attachment_id);
        $this->db->delete('cberp_customer_lead_attachments');
        echo json_encode(array('status' => '1', 'message' =>
                        "Success"));
    }
    public function leads()
    {
        
        $head['title'] = "Enquiries";
        $this->load->view('fixed/header', $head);
        $this->load->view('enquiry/enquiry');
        $this->load->view('fixed/footer');
    }
    public function enquiryedit()
    {
        
        $head['title'] = "Enquiries";
        $tid = intval($this->input->get('id'));
        $data['enquirymain'] = $this->customer_enquiry->enquiry_details($tid);
        
        $enqID = $data['enquirymain']['lead_number'];
        $data['images'] = $this->customer_enquiry->enquiry_details_table($tid);
        $head['title'] = "Edit Enquiry #" . $enqID;
        $this->load->view('fixed/header', $head);
        $this->load->view('invoices/customer_leads', $data);
        $this->load->view('fixed/footer');
    }
    public function ajax_enquirylist()
    {
        $list = $this->customer_enquiry->get_datatables($this->limited);
        $data = array();
        $no = $this->input->post('start');
        foreach ($list as $invoices) {
            $no++;
            $row = array();
            $row[] = $no;

            $row[] = '<a href="' . base_url("invoices/view?id=$invoices->lead_id") . '">&nbsp; ' . $invoices->lead_number . '</a>';
            $row[] = $invoices->customer_name;
            $row[] = dateformat($invoices->invoicedate);
            $row[] = $invoices->customer_phone;
            $row[] = '<span class="st-' . $invoices->enquiry_status . '">' . $this->lang->line(ucwords($invoices->enquiry_status)) . '</span>';
            $row[] = '<a href="' . base_url("invoices/view?id=$invoices->lead_id") . '" class="btn btn-success btn-sm" title="View"><i class="fa fa-eye"></i></a>&nbsp;<a href="' . base_url("invoices/printinvoice?id=$invoices->lead_id") . '&d=1" class="btn btn-info btn-sm"  title="Download"><span class="fa fa-download"></span></a> <a href="#" data-object-id="' . $invoices->lead_id . '" class="btn btn-danger btn-sm delete-object"><span class="fa fa-trash"></span></a>';
            $data[] = $row;
        }
        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->invocies->count_all($this->limited),
            "recordsFiltered" => $this->invocies->count_filtered($this->limited),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }
    public function converttoinvoice()
    {
        
      
        $data['emp'] = $this->plugins->universal_api(69);
        if ($data['emp']['key1']) {
            $this->load->model('employee_model', 'employee');
            $data['employee'] = $this->employee->list_employee();
        }

        $this->load->library("Common");
        $data['custom_fields_c'] = $this->custom->add_fields(1);

        $this->load->model('customers_model', 'customers');
        $this->load->model('plugins_model', 'plugins');
        $data['exchange'] = $this->plugins->universal_api(5);
        $data['customergrouplist'] = $this->customers->group_list();
        $data['lastinvoice'] = $this->invocies->lastinvoice();
        $data['warehouse'] = $this->invocies->warehouses();
        $data['terms'] = $this->invocies->billingterms();
        $data['currency'] = $this->invocies->currencies();
        $data['taxlist'] = $this->common->taxlist($this->config->item('tax'));
        $head['title'] = "New Invoice";
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['taxdetails'] = $this->common->taxdetail();
        $data['custom_fields'] = $this->custom->add_fields(2);
        $tid =  $this->session->userdata('orderid');
        $customerDetails = $this->customers->customerByTid($tid);
        $data['custname'] = $customerDetails['name'];
        $data['phone'] = $customerDetails['phone'];
        $data['email'] = $customerDetails['email'];
        $data['address'] = $customerDetails['address'];
        $data['city'] = $customerDetails['city'];
        $data['region'] = $customerDetails['region'];
        $data['country'] = $customerDetails['country'];
        $data['customerid'] = $customerDetails['id'];
        // echo "<pre>"; print_r($data); die();
        
        $this->load->view('fixed/header', $head);
        $this->load->view('invoices/convertinvoice', $data);
        $this->load->view('fixed/footer');
    }

    //edit invoice
    public function edit()
    {
        // if (!$this->aauth->premission(13)) {
        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        // }
        $tid = intval($this->input->get('id'));
        $data['id'] = $tid;
        $data['title'] = "Edit Invoice $tid";
        $this->load->model('customers_model', 'customers');
        $data['customergrouplist'] = $this->customers->group_list();
        $data['terms'] = $this->invocies->billingterms();
        $data['currency'] = $this->invocies->currencies();
        $data['invoice'] = $this->invocies->invoice_details($tid, $this->limited);
        if ($data['invoice']['id']) $data['products'] = $this->invocies->items_with_product($tid);
        $head['title'] = "Edit Invoice #$tid";
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['warehouse'] = $this->invocies->warehouses();
        $this->load->model('plugins_model', 'plugins');
        $data['exchange'] = $this->plugins->universal_api(5);
        $this->load->library("Common");
        $data['taxlist'] = $this->common->taxlist_edit($data['invoice']['taxstatus']);

         $this->load->library("Common");
          $data['custom_fields_c'] = $this->custom->add_fields(1);
        $data['custom_fields'] = $this->custom->add_fields(2);
        $data['custom_fields'] = $this->custom->view_edit_fields($tid, 2);




        $this->load->view('fixed/header', $head);
        if ($data['invoice']['id']) $this->load->view('invoices/edit', $data);
        $this->load->view('fixed/footer');

    }

    //invoices list
    public function index()
    {
        $head['title'] = "Manage Invoices";
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->view('fixed/header', $head);
        $this->load->view('invoices/invoices');
        $this->load->view('fixed/footer');
    }

    //action
    public function action()
    {
        $currency = $this->input->post('mcurrency');
        $customer_id = $this->input->post('customer_id');
        $invocieno = $this->input->post('invocieno');
        $invoicedate = $this->input->post('invoicedate');
        $invocieduedate = $this->input->post('invocieduedate');
        $notes = $this->input->post('notes', true);
        $tax = $this->input->post('tax_handle');
        $ship_taxtype = $this->input->post('ship_taxtype');
        $disc_val = numberClean($this->input->post('disc_val'));
        $subtotal = rev_amountExchange_s($this->input->post('subtotal'), $currency, $this->aauth->get_user()->loc);
        $shipping = rev_amountExchange_s($this->input->post('shipping'), $currency, $this->aauth->get_user()->loc);
        $shipping_tax = rev_amountExchange_s($this->input->post('ship_tax'), $currency, $this->aauth->get_user()->loc);
        if ($ship_taxtype == 'incl') $shipping = $shipping - $shipping_tax;
        $refer = $this->input->post('refer', true);
        $total = rev_amountExchange_s($this->input->post('total'), $currency, $this->aauth->get_user()->loc);
        $project = $this->input->post('prjid');
        $total_tax = 0;
        $total_discount = rev_amountExchange_s($this->input->post('after_disc'), $currency, $this->aauth->get_user()->loc);
        $discountFormat = $this->input->post('discountFormat');
        $pterms = $this->input->post('pterms', true);
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

        $this->load->model('plugins_model', 'plugins');
        $empl_e = $this->plugins->universal_api(69);
        if ($empl_e['key1']) {
            $emp = $this->input->post('employee');
        } else {
            $emp = $this->aauth->get_user()->id;
        }

        $transok = true;
        $st_c = 0;
        $this->load->library("Common");
        $this->db->trans_start();
        //Invoice Data
        $bill_date = datefordatabase($invoicedate);
        $bill_due_date = datefordatabase($invocieduedate);

        $this->db->select('tid');
        $this->db->from('cberp_invoices');
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $this->db->where('tid', $invocieno);
        $this->db->where('i_class', 0);
        $query = $this->db->get();
        if(@$query->row()->tid){
            $this->db->select('tid');
            $this->db->from('cberp_invoices');
            $this->db->order_by('id', 'DESC');
            $this->db->limit(1);
            $this->db->where('i_class', 0);
            $query = $this->db->get();
            $invocieno=$query->row()->tid+1;
        }

        $data = array('tid' => $invocieno, 'invoicedate' => $bill_date, 'invoiceduedate' => $bill_due_date, 'subtotal' => $subtotal, 'shipping' => $shipping, 'ship_tax' => $shipping_tax, 'ship_tax_type' => $ship_taxtype, 'discount_rate' => $disc_val, 'total' => $total, 'notes' => $notes, 'csd' => $customer_id, 'eid' => $emp, 'taxstatus' => $tax, 'discstatus' => $discstatus, 'format_discount' => $discountFormat, 'refer' => $refer, 'term' => $pterms, 'multi' => $currency, 'loc' => $this->aauth->get_user()->loc);
        $invocieno2 = $invocieno;
		//$data['status']='due';
        if ($this->db->insert('cberp_invoices', $data)) {
            $invocieno = $this->db->insert_id();
            //products
            $pid = $this->input->post('pid');
            $productlist = array();
            $prodindex = 0;
            $itc = 0;
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
            $product_unit = $this->input->post('unit');
            $product_hsn = $this->input->post('hsn', true);
            $product_alert = $this->input->post('alert');
            $product_serial = $this->input->post('serial');
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
                    'unit' => $product_unit[$key],
                    'serial' => $product_serial[$key]
                );

                $productlist[$prodindex] = $data;
                $i++;
                $prodindex++;
                $amt = numberClean($product_qty[$key]);
                if ($product_id[$key] > 0) {
                    $this->db->set('qty', "qty-$amt", FALSE);
                    $this->db->where('pid', $product_id[$key]);
                    $this->db->update('cberp_products');
                    if ((numberClean($product_alert[$key]) - $amt) < 0 and $st_c == 0 and $this->common->zero_stock()) {
                        echo json_encode(array('status' => 'Error', 'message' => 'Product - <strong>' . $product_name1[$key] . "</strong> - Low quantity. Available stock is  " . $product_alert[$key]));
                        $transok = false;
                        $st_c = 1;
                    }
                }
                $itc += $amt;
            }
            if (count($product_serial) > 0) {
                $this->db->set('status', 1);
                $this->db->where_in('serial', $product_serial);
                $this->db->update('cberp_product_serials');
            }
            if ($prodindex > 0) {
                $this->db->insert_batch('cberp_invoice_items', $productlist);
                $this->db->set(array('discount' => rev_amountExchange_s(amountFormat_general($total_discount), $currency, $this->aauth->get_user()->loc), 'tax' => rev_amountExchange_s(amountFormat_general($total_tax), $currency, $this->aauth->get_user()->loc), 'items' => $itc));
                $this->db->where('id', $invocieno);
                $this->db->update('cberp_invoices');
            } else {
                echo json_encode(array('status' => 'Error', 'message' =>
                    "Please choose product from product list. Go to Item manager section if you have not added the products."));
                $transok = false;
            }
            $tnote = '#' . $invocieno . '-' ;
          $d_trans = $this->plugins->universal_api(69);
        if ($d_trans['key2']) {
            $t_data = array(
            'type' => 'Income',
            'cat' => 'Sales',
            'payerid' => $customer_id,
            'method' => 'Auto',
            'date' => $bill_date,
            'eid' =>$emp,
            'tid' => $invocieno,
            'loc' =>$this->aauth->get_user()->loc
        );

            $dual = $this->custom->api_config(65);
            $this->db->select('holder');
            $this->db->from('cberp_accounts');
            $this->db->where('id', $dual['key2']);
            $query = $this->db->get();
            $account_d = $query->row_array();
            $t_data['credit'] = 0;
           $t_data['debit'] = $total;
           $t_data['type'] = 'Expense';
            $t_data['acid'] = $dual['key2'];
            $t_data['account'] = $account_d['holder'];
            $t_data['note'] = 'Debit ' . $tnote;

            $this->db->insert('cberp_transactions', $t_data);
            //account update
            $this->db->set('lastbal', "lastbal-$total", FALSE);
            $this->db->where('id', $dual['key2']);
            $this->db->update('cberp_accounts');

        }
            if ($transok) {
                $validtoken = hash_hmac('ripemd160', $invocieno, $this->config->item('encryption_key'));
                $link = base_url('billing/view?id=' . $invocieno . '&token=' . $validtoken);
                echo json_encode(array('status' => 'Success', 'message' =>
                    $this->lang->line('Invoice Success') . " <a href='view?id=$invocieno' class='btn btn-primary btn-sm'><span class='fa fa-eye' aria-hidden='true'></span> " . $this->lang->line('View') . "  </a> &nbsp; &nbsp;<a href='printinvoice?id=$invocieno' class='btn btn-blue btn-sm' target='_blank'><span class='fa fa-print' aria-hidden='true'></span> " . $this->lang->line('Print') . "  </a> &nbsp; &nbsp; <a href='$link' class='btn btn-purple btn-sm'><span class='fa fa-globe' aria-hidden='true'></span> " . $this->lang->line('Public View') . " </a> &nbsp; &nbsp; <a href='create' class='btn btn-warning btn-sm'><span class='fa fa-plus-circle' aria-hidden='true'></span></a>"));
            }
        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
                "Invalid Entry!"));
            $transok = false;
        }
        if ($transok) {
            // if ($this->aauth->premission(4) and $project > 0) {
            //     $data = array('pid' => $project, 'meta_key' => 11, 'meta_data' => $invocieno, 'value' => '0');
            //     $this->db->insert('cberp_project_meta', $data);
            // }
            $this->db->trans_complete();
        } else {
            $this->db->trans_rollback();
        }
        if ($transok) {
            $this->db->from('univarsal_api');
            $this->db->where('univarsal_api.id', 56);
            $query = $this->db->get();
            $auto = $query->row_array();
            if ($auto['key1'] == 1) {
                $this->db->select('name,email');
                $this->db->from('cberp_customers');
                $this->db->where('id', $customer_id);
                $query = $this->db->get();
                $customer = $query->row_array();
                $this->load->model('communication_model');
                $invoice_mail = $this->send_invoice_auto($invocieno, $invocieno2, $bill_date, $total, $currency);
                $attachmenttrue = false;
                $attachment = '';
                $this->communication_model->send_corn_email($customer['email'], $customer['name'], $invoice_mail['subject'], $invoice_mail['message'], $attachmenttrue, $attachment);
            }
            if ($auto['key2'] == 1) {
                $this->db->select('name,phone');
                $this->db->from('cberp_customers');
                $this->db->where('id', $customer_id);
                $query = $this->db->get();
                $customer = $query->row_array();
                $this->load->model('plugins_model', 'plugins');

                $invoice_sms = $this->send_sms_auto($invocieno, $invocieno2, $bill_date, $total, $currency);
                $mobile = $customer['phone'];
                $text_message = $invoice_sms['message'];
                $this->load->model('sms_model', 'sms');
                $this->sms->send_sms($mobile, $text_message, false);
            }

            //profit calculation
            $t_profit = 0;
            $this->db->select('cberp_invoice_items.pid, cberp_invoice_items.price, cberp_invoice_items.qty, cberp_products.product_cost');
            $this->db->from('cberp_invoice_items');
            $this->db->join('cberp_products', 'cberp_products.pid = cberp_invoice_items.pid', 'left');
            $this->db->where('cberp_invoice_items.tid', $invocieno);
            $query = $this->db->get();
            $pids = $query->result_array();
            foreach ($pids as $profit) {
                $t_cost = $profit['product_cost'] * $profit['qty'];
                $s_cost = $profit['price'] * $profit['qty'];
                $t_profit += $s_cost - $t_cost;
            }
            $data = array('type' => 9, 'rid' => $invocieno, 'col1' => $t_profit, 'd_date' => $bill_date);

            $this->db->insert('cberp_metadata', $data);

            $this->custom->save_fields_data($invocieno, 2);

        }

    }
    public function actionconvertinvoice()
    {
        
        $currency = $this->input->post('mcurrency');
        $customer_id = $this->input->post('customer_id');
        $invocieno = $this->input->post('invocieno');
        $invoicedate = $this->input->post('invoicedate');
        $invocieduedate = $this->input->post('invocieduedate');
        $notes = $this->input->post('notes', true);
        $tax = $this->input->post('tax_handle');
        $ship_taxtype = $this->input->post('ship_taxtype');
        $disc_val = numberClean($this->input->post('disc_val'));
        $subtotal = rev_amountExchange_s($this->input->post('subtotal'), $currency, $this->aauth->get_user()->loc);
        $shipping = rev_amountExchange_s($this->input->post('shipping'), $currency, $this->aauth->get_user()->loc);
        $shipping_tax = rev_amountExchange_s($this->input->post('ship_tax'), $currency, $this->aauth->get_user()->loc);
        if ($ship_taxtype == 'incl') $shipping = $shipping - $shipping_tax;
        $refer = $this->input->post('refer', true);
        $total = rev_amountExchange_s($this->input->post('total'), $currency, $this->aauth->get_user()->loc);
        $project = $this->input->post('prjid');
        $total_tax = 0;
        $total_discount = rev_amountExchange_s($this->input->post('after_disc'), $currency, $this->aauth->get_user()->loc);
        $discountFormat = $this->input->post('discountFormat');
        $pterms = $this->input->post('pterms', true);
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

        $this->load->model('plugins_model', 'plugins');
        $empl_e = $this->plugins->universal_api(69);
        if ($empl_e['key1']) {
            $emp = $this->input->post('employee');
        } else {
            $emp = $this->aauth->get_user()->id;
        }

        $transok = true;
        $st_c = 0;
        $this->load->library("Common");
        $this->db->trans_start();
        //Invoice Data
        $bill_date = datefordatabase($invoicedate);
        $bill_due_date = datefordatabase($invocieduedate);

        $this->db->select('tid');
        $this->db->from('cberp_invoices');
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $this->db->where('tid', $invocieno);
        $this->db->where('i_class', 0);
        $query = $this->db->get();
        if(@$query->row()->tid){
            $this->db->select('tid');
            $this->db->from('cberp_invoices');
            $this->db->order_by('id', 'DESC');
            $this->db->limit(1);
            $this->db->where('i_class', 0);
            $query = $this->db->get();
            $invocieno=$query->row()->tid+1;
        }

        $data = array('tid' => $invocieno, 'invoicedate' => $bill_date, 'invoiceduedate' => $bill_due_date, 'subtotal' => $subtotal, 'shipping' => $shipping, 'ship_tax' => $shipping_tax, 'ship_tax_type' => $ship_taxtype, 'discount_rate' => $disc_val, 'total' => $total, 'notes' => $notes, 'csd' => $customer_id, 'eid' => $emp, 'taxstatus' => $tax, 'discstatus' => $discstatus, 'format_discount' => $discountFormat, 'refer' => $refer, 'term' => $pterms, 'multi' => $currency, 'loc' => $this->aauth->get_user()->loc);
        $invocieno2 = $invocieno;
		//$data['status']='due';
        if ($this->db->insert('cberp_invoices', $data)) {
            $invocieno = $this->db->insert_id();
            //products
            $pid = $this->input->post('pid');
            $productlist = array();
            $productids = array();
            $prodindex = 0;
            $itc = 0;
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
            $product_unit = $this->input->post('unit');
            $product_hsn = $this->input->post('hsn', true);
            $product_alert = $this->input->post('alert');
            $product_serial = $this->input->post('serial');
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
                    'unit' => $product_unit[$key],
                    'serial' => $product_serial[$key]
                );
                $productids[$prodindex] =  $product_id[$key];
                $productlist[$prodindex] = $data;
                $i++;
                $prodindex++;
                $amt = numberClean($product_qty[$key]);
                if ($product_id[$key] > 0) {
                    $this->db->set('qty', "qty-$amt", FALSE);
                    $this->db->where('pid', $product_id[$key]);
                    $this->db->update('cberp_products');
                    if ((numberClean($product_alert[$key]) - $amt) < 0 and $st_c == 0 and $this->common->zero_stock()) {
                        echo json_encode(array('status' => 'Error', 'message' => 'Product - <strong>' . $product_name1[$key] . "</strong> - Low quantity. Available stock is  " . $product_alert[$key]));
                        $transok = false;
                        $st_c = 1;
                    }
                }
                $itc += $amt;
            }
            if (count($product_serial) > 0) {
                $this->db->set('status', 1);
                $this->db->where_in('serial', $product_serial);
                $this->db->update('cberp_product_serials');
            }
            if ($prodindex > 0) {
                $tid = $this->session->userdata('orderid');
              
                if(!empty($tid)){
                    $this->db->set('status', 'invoiced');
                    $this->db->where_in('pid', $productids);
                    $this->db->where('tid', $tid);
                    // $query = $this->db->get_compiled_update('cberp_sales_orders_items');
                    // echo "Last Query: " . $query;
                    // die();
                    $this->db->update('cberp_sales_orders_items');                   
                    
                }
                
                $this->db->insert_batch('cberp_invoice_items', $productlist);
                $this->db->set(array('discount' => rev_amountExchange_s(amountFormat_general($total_discount), $currency, $this->aauth->get_user()->loc), 'tax' => rev_amountExchange_s(amountFormat_general($total_tax), $currency, $this->aauth->get_user()->loc), 'items' => $itc));
                $this->db->where('id', $invocieno);
                $this->db->update('cberp_invoices');
            } else {
                echo json_encode(array('status' => 'Error', 'message' =>
                    "Please choose product from product list. Go to Item manager section if you have not added the products."));
                $transok = false;
            }
            $tnote = '#' . $invocieno . '-' ;
          $d_trans = $this->plugins->universal_api(69);
        if ($d_trans['key2']) {
            $t_data = array(
            'type' => 'Income',
            'cat' => 'Sales',
            'payerid' => $customer_id,
            'method' => 'Auto',
            'date' => $bill_date,
            'eid' =>$emp,
            'tid' => $invocieno,
            'loc' =>$this->aauth->get_user()->loc
        );

            $dual = $this->custom->api_config(65);
            $this->db->select('holder');
            $this->db->from('cberp_accounts');
            $this->db->where('id', $dual['key2']);
            $query = $this->db->get();
            $account_d = $query->row_array();
            $t_data['credit'] = 0;
           $t_data['debit'] = $total;
           $t_data['type'] = 'Expense';
            $t_data['acid'] = $dual['key2'];
            $t_data['account'] = $account_d['holder'];
            $t_data['note'] = 'Debit ' . $tnote;

            $this->db->insert('cberp_transactions', $t_data);
            //account update
            $this->db->set('lastbal', "lastbal-$total", FALSE);
            $this->db->where('id', $dual['key2']);
            $this->db->update('cberp_accounts');

        }
            if ($transok) {
                $validtoken = hash_hmac('ripemd160', $invocieno, $this->config->item('encryption_key'));
                $link = base_url('billing/view?id=' . $invocieno . '&token=' . $validtoken);
                echo json_encode(array('status' => 'Success', 'message' =>
                    $this->lang->line('Invoice Success') . " <a href='view?id=$invocieno' class='btn btn-primary btn-sm'><span class='fa fa-eye' aria-hidden='true'></span> " . $this->lang->line('View') . "  </a> &nbsp; &nbsp;<a href='printinvoice?id=$invocieno' class='btn btn-blue btn-sm' target='_blank'><span class='fa fa-print' aria-hidden='true'></span> " . $this->lang->line('Print') . "  </a> &nbsp; &nbsp; <a href='$link' class='btn btn-purple btn-sm'><span class='fa fa-globe' aria-hidden='true'></span> " . $this->lang->line('Public View') . " </a> &nbsp; &nbsp; <a href='create' class='btn btn-warning btn-sm'><span class='fa fa-plus-circle' aria-hidden='true'></span></a>"));
            }
        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
                "Invalid Entry!"));
            $transok = false;
        }
        if ($transok) {
            // if ($this->aauth->premission(4) and $project > 0) {
            //     $data = array('pid' => $project, 'meta_key' => 11, 'meta_data' => $invocieno, 'value' => '0');
            //     $this->db->insert('cberp_project_meta', $data);
            // }
            $this->db->trans_complete();
        } else {
            $this->db->trans_rollback();
        }
        if ($transok) {
            $this->db->from('univarsal_api');
            $this->db->where('univarsal_api.id', 56);
            $query = $this->db->get();
            $auto = $query->row_array();
            if ($auto['key1'] == 1) {
                $this->db->select('name,email');
                $this->db->from('cberp_customers');
                $this->db->where('id', $customer_id);
                $query = $this->db->get();
                $customer = $query->row_array();
                $this->load->model('communication_model');
                $invoice_mail = $this->send_invoice_auto($invocieno, $invocieno2, $bill_date, $total, $currency);
                $attachmenttrue = false;
                $attachment = '';
                $this->communication_model->send_corn_email($customer['email'], $customer['name'], $invoice_mail['subject'], $invoice_mail['message'], $attachmenttrue, $attachment);
            }
            if ($auto['key2'] == 1) {
                $this->db->select('name,phone');
                $this->db->from('cberp_customers');
                $this->db->where('id', $customer_id);
                $query = $this->db->get();
                $customer = $query->row_array();
                $this->load->model('plugins_model', 'plugins');

                $invoice_sms = $this->send_sms_auto($invocieno, $invocieno2, $bill_date, $total, $currency);
                $mobile = $customer['phone'];
                $text_message = $invoice_sms['message'];
                $this->load->model('sms_model', 'sms');
                $this->sms->send_sms($mobile, $text_message, false);
            }

            //profit calculation
            $t_profit = 0;
            $this->db->select('cberp_invoice_items.pid, cberp_invoice_items.price, cberp_invoice_items.qty, cberp_products.product_cost');
            $this->db->from('cberp_invoice_items');
            $this->db->join('cberp_products', 'cberp_products.pid = cberp_invoice_items.pid', 'left');
            $this->db->where('cberp_invoice_items.tid', $invocieno);
            $query = $this->db->get();
            $pids = $query->result_array();
            foreach ($pids as $profit) {
                $t_cost = $profit['product_cost'] * $profit['qty'];
                $s_cost = $profit['price'] * $profit['qty'];
                $t_profit += $s_cost - $t_cost;
            }
            $data = array('type' => 9, 'rid' => $invocieno, 'col1' => $t_profit, 'd_date' => $bill_date);

            $this->db->insert('cberp_metadata', $data);

            $this->custom->save_fields_data($invocieno, 2);

        }

    }


    public function ajax_list()
    {
        $list = $this->invocies->get_datatables($this->limited);
        $data = array();
        $no = $this->input->post('start');
        foreach ($list as $invoices) {
            $no++;
            $row = array();
            $row[] = $no;

            $row[] = '<a href="' . base_url("invoices/view?id=$invoices->id") . '">&nbsp; ' . $invoices->tid . '</a>';
            $row[] = $invoices->name;
            $row[] = dateformat($invoices->invoicedate);
            $row[] = amountExchange($invoices->total, 0, $this->aauth->get_user()->loc);
            $row[] = '<span class="st-' . $invoices->status . '">' . $this->lang->line(ucwords($invoices->status)) . '</span>';
            $row[] = '<a href="' . base_url("invoices/view?id=$invoices->id") . '" class="btn btn-success btn-sm" title="View"><i class="fa fa-eye"></i></a>&nbsp;<a href="' . base_url("invoices/printinvoice?id=$invoices->id") . '&d=1" class="btn btn-info btn-sm"  title="Download"><span class="fa fa-download"></span></a> <a href="#" data-object-id="' . $invoices->id . '" class="btn btn-danger btn-sm delete-object"><span class="fa fa-trash"></span></a>';
            $data[] = $row;
        }
        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->invocies->count_all($this->limited),
            "recordsFiltered" => $this->invocies->count_filtered($this->limited),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }
    

    public function view()
    {
        $this->load->model('accounts_model');
        $data['acclist'] = $this->accounts_model->accountslist((integer)$this->aauth->get_user()->loc);
        $tid = $this->input->get('id');
        $data['invoice'] = $this->invocies->invoice_details($tid, $this->limited);
        $data['attach'] = $this->invocies->attach($tid);
        $data['c_custom_fields'] = $this->custom->view_fields_data($data['invoice']['cid'], 1);
        $head['usernm'] = $this->aauth->get_user()->username;
        $head['title'] = "Invoice " . $data['invoice']['tid'];
        $this->load->view('fixed/header', $head);
        $data['products'] = $this->invocies->invoice_products($tid);
        if ($data['invoice']['id']) $data['activity'] = $this->invocies->invoice_transactions($tid);
        $data['employee'] = $this->invocies->employee($data['invoice']['eid']);
        $data['custom_fields'] = $this->custom->view_fields_data($tid, 2);
        if ($data['invoice']['id']) {
            $data['invoice']['id'] = $tid;
            $this->load->view('invoices/view', $data);
        }
        $this->load->view('fixed/footer');
    }

    public function printinvoice()
    {

        $tid = $this->input->get('id');
        $data['id'] = $tid;
        $data['invoice'] = $this->invocies->invoice_details($tid, $this->limited);
        if ($data['invoice']['id']) $data['products'] = $this->invocies->invoice_products($tid);
        if ($data['invoice']['id']) $data['employee'] = $this->invocies->employee($data['invoice']['eid']);
        if ($data['invoice']['i_class'] == 1) {
            $pref = prefix(7);
        } else {
            $pref = $this->config->item('prefix');
        }
        if (CUSTOM) $data['c_custom_fields'] = $this->custom->view_fields_data($data['invoice']['cid'], 1, 1);
        $data['general'] = array('title' => $this->lang->line('Invoice'), 'person' => $this->lang->line('Customer'), 'prefix' => $pref, 't_type' => 0);
        ini_set('memory_limit', '64M');
        if ($data['invoice']['taxstatus'] == 'cgst' || $data['invoice']['taxstatus'] == 'igst') {
            $html = $this->load->view('print_files/invoice-a4-gst_v' . INVV, $data, true);
        } else {
            $html = $this->load->view('print_files/invoice-a4_v' . INVV, $data, true);
        }
        //PDF Rendering
        $this->load->library('pdf');
        if (INVV == 1) {
            $header = $this->load->view('print_files/invoice-header_v' . INVV, $data, true);
            $pdf = $this->pdf->load_split(array('margin_top' => 40));
            $pdf->SetHTMLHeader($header);
        }
        if (INVV == 2) {
            $pdf = $this->pdf->load_split(array('margin_top' => 5));
        }
        $pdf->SetHTMLFooter('<div style="text-align: right;font-family: serif; font-size: 8pt; color: #5C5C5C; font-style: italic;margin-top:-6pt;">{PAGENO}/{nbpg} #' . $data['invoice']['tid'] . '</div>');
        $pdf->WriteHTML($html);
        $file_name = preg_replace('/[^A-Za-z0-9]+/', '-', 'Invoice__' . $data['invoice']['name'] . '_' . $data['invoice']['tid']);
        if ($this->input->get('d')) {
            $pdf->Output($file_name . '.pdf', 'D');
        } else {
            $pdf->Output($file_name . '.pdf', 'I');
        }
    }

    public function delete_i()
    {
        if ($this->aauth->premission(11)) {
            $id = $this->input->post('deleteid');

            if ($this->invocies->invoice_delete($id, $this->limited)) {
                echo json_encode(array('status' => 'Success', 'message' =>
                    $this->lang->line('DELETED')));
            } else {
                echo json_encode(array('status' => 'Error', 'message' =>
                    $this->lang->line('ERROR')));
            }
        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
                $this->lang->line('ERROR')));
        }

    }

    public function editaction()
    {
        if (!$this->aauth->premission(13)) {
            exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        }
        $customer_id = $this->input->post('customer_id');
        $invocieno = $this->input->post('invocieno');
        $iid = $this->input->post('iid');
        $invoicedate = $this->input->post('invoicedate');
        $invocieduedate = $this->input->post('invocieduedate');
        $notes = $this->input->post('notes', true);
        $tax = $this->input->post('tax_handle');
        $ship_taxtype = $this->input->post('ship_taxtype');
        $total_tax = 0;
        $discountFormat = $this->input->post('discountFormat');
        $pterms = $this->input->post('pterms');
        $currency = $this->input->post('mcurrency');
        $subtotal = rev_amountExchange_s($this->input->post('subtotal'), $currency, $this->aauth->get_user()->loc);
        $shipping = rev_amountExchange_s($this->input->post('shipping'), $currency, $this->aauth->get_user()->loc);
        $shipping_tax = rev_amountExchange_s($this->input->post('ship_tax'), $currency, $this->aauth->get_user()->loc);
        if ($ship_taxtype == 'incl') $shipping = $shipping - $shipping_tax;
        $refer = $this->input->post('refer', true);
        $total = rev_amountExchange_s($this->input->post('total'), $currency, $this->aauth->get_user()->loc);
        $disc_val = numberClean($this->input->post('disc_val'));
        $total_discount = rev_amountExchange_s($this->input->post('after_disc'), $currency, $this->aauth->get_user()->loc);
        $i = 0;
        if ($this->limited) {
            $employee = $this->invocies->invoice_details($iid, $this->limited);
            if ($this->aauth->get_user()->id != $employee['eid']) exit();
        }
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
        $this->db->trans_start();
        $transok = true;
          $st_c = 0;
           $this->load->library("Common");

        $bill_date = datefordatabase($invoicedate);
        $bill_due_date = datefordatabase($invocieduedate);
        $data = array('invoicedate' => $bill_date, 'invoiceduedate' => $bill_due_date, 'subtotal' => $subtotal, 'shipping' => $shipping, 'ship_tax' => $shipping_tax, 'ship_tax_type' => $ship_taxtype, 'discount_rate' => $disc_val, 'discount' => $total_discount, 'tax' => $total_tax, 'total' => $total, 'notes' => $notes, 'csd' => $customer_id, 'items' => 0, 'taxstatus' => $tax, 'discstatus' => $discstatus, 'format_discount' => $discountFormat, 'refer' => $refer, 'term' => $pterms, 'multi' => $currency);
        $this->db->set($data);
        $this->db->where('id', $iid);


        if ($this->db->update('cberp_invoices', $data)) {
            //Product Data
            $pid = $this->input->post('pid');
            $productlist = array();
            $prodindex = 0;
            $itc = 0;
            $this->db->delete('cberp_invoice_items', array('tid' => $iid));
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
            $product_unit = $this->input->post('unit');
            $product_hsn = $this->input->post('hsn');
            $product_serial = $this->input->post('serial');
            $product_alert = $this->input->post('alert');

            foreach ($pid as $key => $value) {

                $total_discount += numberClean(@$ptotal_disc[$key]);
                $total_tax += numberClean($ptotal_tax[$key]);

                $data = array(
                    'tid' => $iid,
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
                    'unit' => $product_unit[$key],
                    'serial' => $product_serial[$key]
                );
                $productlist[$prodindex] = $data;
                $i++;
                $prodindex++;

                $amt = numberClean(@$product_qty[$key]) - numberClean(@$old_product_qty[$key]);
                if ($product_id[$key] > 0 and $amt) {
                    $this->db->set('qty', "qty-$amt", FALSE);
                    $this->db->where('pid', $product_id[$key]);
                    $this->db->update('cberp_products');

                                        if (isset($product_alert[$key]) AND (numberClean($product_alert[$key]) - $amt) < 0 and $st_c == 0 and $this->common->zero_stock()) {
                        echo json_encode(array('status' => 'Error', 'message' => 'Product - <strong>' . $product_name1[$key] . "</strong> - Low quantity. Available stock is  " . $product_alert[$key]));
                        $transok = false;
                        $st_c = 1;
                    }
                }
                $itc += $amt;
            }

            if ($prodindex > 0) {
                $this->db->insert_batch('cberp_invoice_items', $productlist);
                if (count($product_serial) > 0) {
                    $this->db->set('status', 1);
                    $this->db->where_in('serial', $product_serial);
                    $this->db->update('cberp_product_serials');
                }
                $this->db->set(array('discount' => rev_amountExchange_s(amountFormat_general($total_discount), $currency, $this->aauth->get_user()->loc), 'tax' => rev_amountExchange_s(amountFormat_general($total_tax), $currency, $this->aauth->get_user()->loc), 'items' => $itc));
                $this->db->where('id', $iid);
                $this->db->update('cberp_invoices');
         if($transok)    echo json_encode(array('status' => 'Success', 'message' => $this->lang->line('Invoice has  been updated') . " <a href='view?id=$iid' class='btn btn-info btn-sm'><span class='fa fa-eye' aria-hidden='true'></span> " . $this->lang->line('View') . " </a> "));
            } else {
                echo json_encode(array('status' => 'Error', 'message' =>
                    $this->lang->line('ERROR')));
                $transok = false;
            }

            if ($this->input->post('restock')) {
                foreach ($this->input->post('restock') as $key => $value) {
                    $myArray = explode('-', $value);
                    $prid = $myArray[0];
                    $dqty = numberClean($myArray[1]);
                    if ($prid > 0) {
                        $this->db->set('qty', "qty+$dqty", FALSE);
                        $this->db->where('pid', $prid);
                        $this->db->update('cberp_products');
                    }
                }
            }
        } else {
                if($transok)   echo json_encode(array('status' => 'Error', 'message' =>
                "Please add at least one product in invoice"));
            $transok = false;

        }


        if ($transok) {
            $this->custom->edit_save_fields_data($iid, 2);
            $this->db->trans_complete();
        } else {
            $this->db->trans_rollback();
        }

        //profit calculation
        $t_profit = 0;
        $this->db->select('cberp_invoice_items.pid, cberp_invoice_items.price, cberp_invoice_items.qty, cberp_products.product_cost');
        $this->db->from('cberp_invoice_items');
        $this->db->join('cberp_products', 'cberp_products.pid = cberp_invoice_items.pid', 'left');
        $this->db->where('cberp_invoice_items.tid', $iid);
        $query = $this->db->get();
        $pids = $query->result_array();
        foreach ($pids as $profit) {
            $t_cost = $profit['product_cost'] * $profit['qty'];
            $s_cost = $profit['price'] * $profit['qty'];

            $t_profit += $s_cost - $t_cost;
        }
       $this->db->trans_start();
        $this->db->set('col1', $t_profit);
        $this->db->where('type', 9);
        $this->db->where('rid', $iid);
        $this->db->update('cberp_metadata');
        $this->db->trans_complete();
    }

    public function update_status()
    {
        $tid = $this->input->post('tid');
        $status = $this->input->post('status');
        $this->db->set('status', $status);
        $this->db->where('id', $tid);
        $this->db->update('cberp_invoices');

        echo json_encode(array('status' => 'Success', 'message' =>
            $this->lang->line('UPDATED'), 'pstatus' => $status));
    }


    public function addcustomer()
    {
        $name = $this->input->post('name', true);
        $company = $this->input->post('company', true);
        $phone = $this->input->post('phone', true);
        $email = $this->input->post('email', true);
        $address = $this->input->post('address', true);
        $city = $this->input->post('city', true);
        $region = $this->input->post('region', true);
        $country = $this->input->post('country', true);
        $postbox = $this->input->post('postbox', true);
        $tax_id = $this->input->post('tax_id', true);
        $customergroup = $this->input->post('customergroup');
        $shipping_name = $this->input->post('shipping_name', true);
        $shipping_phone = $this->input->post('shipping_phone', true);
        $shipping_email = $this->input->post('shipping_email', true);
        $shipping_address_1 = $this->input->post('shipping_address_1', true);
        $shipping_city = $this->input->post('shipping_city', true);
        $shipping_region = $this->input->post('shipping_region', true);
        $shipping_country = $this->input->post('shipping_country', true);
        $shipping_postbox = $this->input->post('shipping_postbox', true);

        $this->load->model('customers_model', 'customers');
        $this->customers->add($name, $company, $phone, $email, $address, $city, $region, $country, $postbox, $customergroup, $tax_id, $shipping_name, $shipping_phone, $shipping_email, $shipping_address_1, $shipping_city, $shipping_region, $shipping_country, $shipping_postbox);

    }

    public function file_handling()
    {
        if ($this->input->get('op')) {
            $name = $this->input->get('name');
            $invoice = $this->input->get('invoice');
            if ($this->invocies->meta_delete($invoice, 1, $name)) {
                echo json_encode(array('status' => 'Success'));
            }
        } else {
            $id = $this->input->get('id');
            $this->load->library("Uploadhandler_generic", array(
                'accept_file_types' => '/\.(gif|jpe?g|png|docx|docs|txt|pdf|xls)$/i', 'upload_dir' => FCPATH . 'userfiles/attach/', 'upload_url' => base_url() . 'userfiles/attach/'
            ));
            $files = (string)$this->uploadhandler_generic->filenaam();
            if ($files != '') {

                $this->invocies->meta_insert($id, 1, $files);
            }
        }


    }

    public function delivery()
    {

        $tid = $this->input->get('id');

        $data['id'] = $tid;
        $data['title'] = "Invoice $tid";
        $data['invoice'] = $this->invocies->invoice_details($tid, $this->limited);
        if ($data['invoice']['id']) $data['products'] = $this->invocies->invoice_products($tid);
        if ($data['invoice']['id']) $data['employee'] = $this->invocies->employee($data['invoice']['eid']);

        ini_set('memory_limit', '64M');

        $html = $this->load->view('invoices/del_note', $data, true);

        //PDF Rendering
        $this->load->library('pdf');

        $pdf = $this->pdf->load();

        $pdf->SetHTMLFooter('<div style="text-align: right;font-family: serif; font-size: 8pt; color: #5C5C5C; font-style: italic;margin-top:-6pt;">{PAGENO}/{nbpg} #' . $tid . '</div>');

        $pdf->WriteHTML($html);

        if ($this->input->get('d')) {

            $pdf->Output('DO_#' . $data['invoice']['tid'] . '.pdf', 'D');
        } else {
            $pdf->Output('DO_#' . $data['invoice']['tid'] . '.pdf', 'I');
        }


    }

    public function proforma()
    {

        $tid = $this->input->get('id');

        $data['id'] = $tid;
        $data['title'] = "Invoice $tid";
        $data['invoice'] = $this->invocies->invoice_details($tid, $this->limited);
        if ($data['invoice']['id']) $data['products'] = $this->invocies->invoice_products($tid);
        if ($data['invoice']['id']) $data['employee'] = $this->invocies->employee($data['invoice']['eid']);
        ini_set('memory_limit', '64M');
        $html = $this->load->view('invoices/proforma', $data, true);
        //PDF Rendering
        $this->load->library('pdf');
        $pdf = $this->pdf->load();
        $pdf->SetHTMLFooter('<div style="text-align: right;font-family: serif; font-size: 8pt; color: #5C5C5C; font-style: italic;margin-top:-6pt;">{PAGENO}/{nbpg} #' . $tid . '</div>');
        $pdf->WriteHTML($html);
        if ($this->input->get('d')) {
            $pdf->Output('Proforma_#' . $data['invoice']['tid'] . '.pdf', 'D');
        } else {
            $pdf->Output('Proforma_#' . $data['invoice']['tid'] . '.pdf', 'I');
        }


    }


    public function send_invoice_auto($invocieno, $invocieno2, $idate, $total, $multi)
    {
        $this->load->library('parser');
        $this->load->model('templates_model', 'templates');
        $template = $this->templates->template_info(6);

        $data = array(
            'Company' => $this->config->item('ctitle'),
            'BillNumber' => $invocieno2
        );
        $subject = $this->parser->parse_string($template['key1'], $data, TRUE);
        $validtoken = hash_hmac('ripemd160', $invocieno, $this->config->item('encryption_key'));
        $link = base_url('billing/view?id=' . $invocieno . '&token=' . $validtoken);


        $data = array(
            'Company' => $this->config->item('ctitle'),
            'BillNumber' => $invocieno2,
            'URL' => "<a href='$link'>$link</a>",
            'CompanyDetails' => '<h6><strong>' . $this->config->item('ctitle') . ',</strong></h6>
<address>' . $this->config->item('address') . '<br>' . $this->config->item('address2') . '</address>
             ' . $this->lang->line('Phone') . ' : ' . $this->config->item('phone') . '<br>  ' . $this->lang->line('Email') . ' : ' . $this->config->item('email'),
            'DueDate' => dateformat($idate),
            'Amount' => amountExchange($total, $multi)
        );
        $message = $this->parser->parse_string($template['other'], $data, TRUE);
        return array('subject' => $subject, 'message' => $message);
    }

    public function send_sms_auto($invocieno, $invocieno2, $idate, $total, $multi)
    {
        $this->load->library('parser');
        $this->load->model('templates_model', 'templates');
        $template = $this->templates->template_info(30);
        $validtoken = hash_hmac('ripemd160', $invocieno, $this->config->item('encryption_key'));
        $link = base_url('billing/view?id=' . $invocieno . '&token=' . $validtoken);
        $this->load->model('plugins_model', 'plugins');
        $sms_service = $this->plugins->universal_api(1);
        if ($sms_service['active']) {
            $this->load->library("Shortenurl");
            $this->shortenurl->setkey($sms_service['key1']);
            $link = $this->shortenurl->shorten($link);
        }
        $data = array(
            'BillNumber' => $invocieno2,
            'URL' => $link,
            'DueDate' => dateformat($idate),
            'Amount' => amountExchange($total, $multi)
        );
        $message = $this->parser->parse_string($template['other'], $data, TRUE);
        return array('message' => $message);
    }

    public function view_payslip()
    {
        $id = $this->input->get('id');
        $inv = $this->input->get('inv');
        $data['invoice'] = $this->invocies->invoice_details($inv, $this->limited);
        if (!$data['invoice']['id']) exit('Limited Permissions!');

        $this->load->model('transactions_model', 'transactions');
        $head['title'] = "View Transaction";
        $head['usernm'] = $this->aauth->get_user()->username;

        $data['trans'] = $this->transactions->view($id);

        if ($data['trans']['payerid'] > 0) {
            $data['cdata'] = $this->transactions->cview($data['trans']['payerid'], $data['trans']['ext']);
        } else {
            $data['cdata'] = array('address' => 'Not Registered', 'city' => '', 'phone' => '', 'email' => '');
        }
        ini_set('memory_limit', '64M');

        $html = $this->load->view('transactions/view-print-customer', $data, true);

        //PDF Rendering
        $this->load->library('pdf');

        $pdf = $this->pdf->load_en();

        $pdf->SetHTMLFooter('<table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #5C5C5C; font-style: italic;"><tr><td width="33%"></td><td width="33%" align="center" style="font-weight: bold; font-style: italic;">{PAGENO}/{nbpg}</td><td width="33%" style="text-align: right; ">#' . $id . '</td></tr></table>');

        $pdf->WriteHTML($html);

        if ($this->input->get('d')) {

            $pdf->Output('Trans_#' . $id . '.pdf', 'D');
        } else {
            $pdf->Output('Trans_#' . $id . '.pdf', 'I');
        }


    }

    public function updateInventory(){
        $salesPro = $this->input->post('selectedProducts');
        $tid = $this->session->userdata("orderid");
        $this->db->select('pid,qty');
        $this->db->from('cberp_sales_orders_items');
        $this->db->where_in('pid', $salesPro);
        $this->db->where('tid', $tid);
        $this->db->where('status','delivered');
        $this->db->where('inventory_adjusted','0');        
        $query = $this->db->get();        
        $result = $query->result_array();
        if(!empty($result)){
            foreach($result as $item){
                $pid = $item['pid'];
                $qty = $item['qty'];
                $this->db->select('qty');
                $this->db->from('cberp_products');
                $this->db->where_in('pid', $pid);
                $prdQry = $this->db->get();        
                $prdresult = $prdQry->row_array();
                $onhand = $prdresult['qty'];
                $updateQty = intval($onhand) - intval($qty);

                $upqty = array('qty' => $updateQty);
                $this->db->where_in('pid', $pid);
                $this->db->update('cberp_products', $upqty);

                $this->db->set('inventory_adjusted', '1');
                $this->db->where_in('pid', $pid);
                $this->db->where('tid', $tid);
                $this->db->update('cberp_sales_orders_items');
            }
            echo json_encode(array('status' => '1', 'message' =>
                $this->lang->line('Inventory status updated')));
        }else{
            echo json_encode(array('status' => '1', 'message' =>
                $this->lang->line('Inventory status failed')));
        }
        
    }
    

}
