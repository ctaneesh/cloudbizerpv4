<?php
/**
 * Cloud Biz Erp -  Accounting,  Invoicing  and CRM Software
 * Copyright (c) Cloud Biz Erp. All Rights Reserved
 * ***********************************************************************
 *
 *  Email: support@cloudbizerp.com
 *  Website: https://www.cloudbizerp.com

 * ***********************************************************************
 */

defined('BASEPATH') or exit('No direct script access allowed');

class Purchasereturns extends CI_Controller
{
    private $prifix51;
    private $stock_module_group_number;
    private $my_approval_levels;
    private $all_approval_level;
    private $module_number;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Stockreturn_model', 'stockreturn');
        $this->load->model('purchase_model', 'purchase');        
        $this->load->model('Purchase_reciept_return_model', 'purchasereturn');        
        $this->load->model('costingcalculation_model', 'costingcalculation');
        $this->load->library("Aauth");
        if (!$this->aauth->is_loggedin()) {
            redirect('/user/', 'refresh');
        }
        $this->li_a = 'stock';        
        $this->prifix51 =  get_prefix();
        $this->stock_module_group_number =  get_module_details_by_name('Stock');
        $this->module_number =  module_number_name('Stock Return');
    }

    //create invoice
    public function create()
    {
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
        $data['prefix'] =  $this->prifix51['purchasereturn_prefix'];
        $data['permissions'] = load_permissions('Stock','Purchase returns','New Purchase Return');
        // if (!$this->aauth->premission(2)) {
        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        // }
        // $data['token'] = $this->input->get('token', true);
        $purchase_reciept_number = $this->input->get('id');        
        $receipt_return_number = $this->input->get('pid');
        $data['default_warehouse'] = $this->costingcalculation->default_warehouse();
        $this->load->library("Common");
        $this->load->model('plugins_model', 'plugins');
        $data['exchange'] = $this->plugins->universal_api(5);
        $data['currency'] = $this->purchasereturn->currencies();
        $data['taxlist'] = $this->common->taxlist($this->config->item('tax'));
        $this->load->model('customers_model', 'customers');
        $data['customergrouplist'] = $this->customers->group_list();
        $data['lastinvoice'] = $this->purchasereturn->last_return();       
        $data['terms'] = $this->purchasereturn->billingterms();
        $head['title'] = "Purchase return";
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['warehouse'] = $this->purchasereturn->warehouses();
        $data['taxdetails'] = $this->common->taxdetail();
        $this->load->view('fixed/header', $head);
        if(!empty($receipt_return_number))
        {            
            if($this->module_number)
            {
                $data['approved_levels'] = function_approved_levels($this->module_number,$receipt_return_number);
                $data['approval_level_users'] =  linked_user_module_approvals_by_module_number($this->stock_module_group_number);   
                $data['my_approval_permissions'] =  linked_user_module_approvals_by_module_number($this->stock_module_group_number,$this->session->userdata('id'));
                $data['module_number'] = $this->module_number;
            }
            $data['trackingdata'] = tracking_details('purchase_reciept_return_number',$receipt_return_number);
            $data['invoice'] = $this->purchasereturn->purchase_details($receipt_return_number);  
            $data['products'] = $this->purchasereturn->purchase_products($receipt_return_number);     
            $data['assignedperson'] = $this->purchasereturn->assigedemployee($data['invoice']['assigned_to']);
            $data['journals_records'] = $this->purchasereturn->purchase_return_journal_records($receipt_return_number);
            $data['payment_records'] = $this->purchasereturn->purchase_return_payments_received($receipt_return_number);     
            //   echo "<pre>"; print_r($data['products']); die();            
            if($data['invoice']['created_by'])
            {
                $data['created_employee'] = employee_details_by_id($data['invoice']['created_by']);
            }            
              
            $this->load->model('employee_model', 'employee');
            $data['employee'] = $this->employee->list_employee();
            $data['log'] = $this->purchasereturn->gethistory($receipt_return_number);
            $data['stockreturnid'] = $receipt_return_number;
            //erp2024 06-01-2025 detailed history log starts
            $page = $this->module_number;
            $data['detailed_log']= get_detailed_logs($receipt_return_number,$page);
            $products = $data['detailed_log'];
            $groupedBySequence = []; // Initialize an empty array for grouping

            foreach ($products as $product) {
                $sequence = $product['seqence_number'];
                $groupedBySequence[$sequence][] = $product; // Group by sequence number
            }
            $data['return_exists'] = $this->purchasereturn->get_purchase_order_by_field('receipt_return_number',$receipt_return_number);
            $data['groupedDatas'] = $groupedBySequence;
            //  echo "<pre>"; print_r($data['groupedDatas']); die();         
            $data['images'] = get_uploaded_images('Purchasereturn',$receipt_return_number);
            $this->load->view('stockreturn/purchase_return_create', $data);
        }

        else
        {      
          
            $data['invoice'] = [];
            $data['products'] = [];
            $data['return_exists'] = 0;
            if($purchase_reciept_number)
            {
                
                $data['trackingdata'] = tracking_details('purchase_reciept_number',$purchase_reciept_number);
                $data['invoice'] = $this->purchasereturn->purchase_receipt_details($purchase_reciept_number); 
                $data['products'] = $this->purchasereturn->purchase_receipt_products($purchase_reciept_number); 
                // echo "<pre>"; print_r($data['products']); die();         
                // $data['return_exists'] = $this->purchasereturn->get_purchase_order_by_field('purchase_reciept_id',$purchase_reciept_number);
            }
           
                  
            // $this->load->view('stockreturn/newinvoice', $data);
            $this->load->view('stockreturn/purchase_return_create', $data);
        }
       
        $this->load->view('fixed/footer');
    }


    public function create_client()
    {
        // if (!$this->aauth->premission(2)) {
        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        // }
        $this->load->library("Common");
        $this->load->model('plugins_model', 'plugins');
        $data['exchange'] = $this->plugins->universal_api(5);
        $data['currency'] = $this->purchasereturn->currencies();
        $data['taxlist'] = $this->common->taxlist($this->config->item('tax'));
        $this->load->model('customers_model', 'customers');
        $data['customergrouplist'] = $this->customers->group_list();
        $data['lastinvoice'] = $this->purchasereturn->lastpurchase();
        $data['terms'] = $this->purchasereturn->billingterms();
        $head['title'] = "New Stock return";
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['warehouse'] = $this->purchasereturn->warehouses();
        $data['taxdetails'] = $this->common->taxdetail();
        $this->load->view('fixed/header', $head);
        $this->load->view('stockreturn/c_newinvoice', $data);
        $this->load->view('fixed/footer');
    }

    public function create_note()
    {
        $data['permissions'] = load_permissions('Sales','Sales','Sales Returns','Create Page');
        // if (!$this->aauth->premission(1)) {
        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        // }
        $this->load->library("Common");
        $data['taxlist'] = $this->common->taxlist($this->config->item('tax'));
        $this->load->model('customers_model', 'customers');
        $this->load->model('plugins_model', 'plugins');
        $data['exchange'] = $this->plugins->universal_api(5);
        $data['currency'] = $this->purchasereturn->currencies();
        $data['customergrouplist'] = $this->customers->group_list();
        $data['lastinvoice'] = $this->purchasereturn->lastpurchase();
        $data['terms'] = $this->purchasereturn->billingterms();
        $head['title'] = "New Credit Note";
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['warehouse'] = $this->purchasereturn->warehouses();
        $data['taxdetails'] = $this->common->taxdetail();
        $this->load->view('fixed/header', $head);
        $this->load->view('stockreturn/note_newinvoice', $data);
        $this->load->view('fixed/footer');
    }

    //edit invoice
    public function edit()
    {
        // if (!$this->aauth->premission(2)) {
        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        // }
        $tid = intval($this->input->get('id'));
        $data['id'] = $tid;
        $this->load->model('customers_model', 'customers');
        $data['customergrouplist'] = $this->customers->group_list();
        $data['terms'] = $this->purchasereturn->billingterms();
        $data['invoice'] = $this->purchasereturn->purchase_details($tid);
        $data['products'] = $this->purchasereturn->purchase_products($tid);
        $head['title'] = "Stock return Order " . $data['invoice']['iid'];
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['warehouse'] = $this->purchasereturn->warehouses();
        $this->load->library("Common");
        $data['taxlist'] = $this->common->taxlist_edit($data['invoice']['taxstatus']);
        $this->load->view('fixed/header', $head);
        $this->load->view('stockreturn/edit', $data);
        $this->load->view('fixed/footer');
    }

    public function edit_c()
    {
        // if (!$this->aauth->premission(2)) {
        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        // }
        $tid = intval($this->input->get('id'));
        $data['id'] = $tid;
        $this->load->model('customers_model', 'customers');
        $data['customergrouplist'] = $this->customers->group_list();
        $data['terms'] = $this->purchasereturn->billingterms();
        $data['invoice'] = $this->purchasereturn->purchase_details($tid);
        $data['products'] = $this->purchasereturn->purchase_products($tid);;
        $head['title'] = "Stock return Order " . $data['invoice']['iid'];
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['warehouse'] = $this->purchasereturn->warehouses();
        $this->load->library("Common");
        $data['taxlist'] = $this->common->taxlist_edit($data['invoice']['taxstatus']);
        $this->load->view('fixed/header', $head);
        $this->load->view('stockreturn/c_edit', $data);
        $this->load->view('fixed/footer');

    }

    public function edit_note()
    {
        // if (!$this->aauth->premission(1)) {
        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        // }
        $tid = intval($this->input->get('id'));
        $data['id'] = $tid;
        $this->load->model('customers_model', 'customers');
        $data['customergrouplist'] = $this->customers->group_list();
        $data['terms'] = $this->purchasereturn->billingterms();
        $data['invoice'] = $this->purchasereturn->purchase_details($tid);
        $data['products'] = $this->purchasereturn->purchase_products($tid);;
        $head['title'] = "Credit Note " . $data['invoice']['iid'];
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['warehouse'] = $this->purchasereturn->warehouses();
        $this->load->library("Common");
        $data['taxlist'] = $this->common->taxlist_edit($data['invoice']['taxstatus']);
        $this->load->view('fixed/header', $head);
        $this->load->view('stockreturn/note_edit', $data);
        $this->load->view('fixed/footer');

    }

    //invoices list
    public function index()
    {
        $data['permissions'] = load_permissions('Stock','Purchase returns','Manage Purchase Returns');
        $head['title'] = "Manage Purchase Returns";        
        $data['counts'] = $this->purchasereturn->get_filter_count();
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['ranges'] = getCommonDateRanges();    
        // print_r($data['ranges']); die();
        $this->load->view('fixed/header', $head);        
        
        $this->load->view('stockreturn/purchase_return_list', $data);
        $this->load->view('fixed/footer');
    }

    public function customer()
    {
        // if (!$this->aauth->premission(2)) {
        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        // }
        $head['title'] = "Manage Stockreturn Orders";
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->model('invoices_model');
        $condition = " WHERE i_class = 2";
        $data['counts'] = $this->invoices_model->get_dynamic_count('cberp_purchase_reciept_returns','invoicedate','total',$condition);
        // print_r($data['counts']); die();
        $this->load->view('fixed/header', $head);
        $this->load->view('stockreturn/invoices_client',$data);
        $this->load->view('fixed/footer');
    }

    public function creditnotes()
    {   
        $data['permissions'] = load_permissions('Sales','Sales','Sales Returns'); 
        // if (!$this->aauth->premission(1)) {
        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        // }
        $head['title'] = "Manage Credit Notes";
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->model('invoices_model');
        $condition = " WHERE i_class = 2";
        $data['counts'] = $this->invoices_model->get_dynamic_count('cberp_purchase_reciept_returns','invoicedate','total',$condition);
        // print_r($data['counts']); die();
        $this->load->view('fixed/header', $head);
        $this->load->view('stockreturn/creditnotes_client', $data);
        $this->load->view('fixed/footer');
    }


    //action
    public function action()
    {
            ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        $currency = $this->input->post('mcurrency');
        $customer_id = $this->input->post('customer_id');
        $person_type = $this->input->post('person_type');
        $new_u = 'create';
        if ($person_type) {
            $new_u = 'create_client';
        }
        if ($person_type == 2) {
            $new_u = 'create_note';
        }
        $invocieno = $this->input->post('invocieno');
        $invocietid = $this->input->post('invocieno');
        $iid = $this->input->post('iid');
        $invoicedate = $this->input->post('invoicedate');
        $receipt_return_number = $this->input->post('stock_return_number');
        $store_id = $this->input->post('s_warehouses');
        $purchase_reciept_number = $this->input->post('purchase_reciept_number');
        $notes = $this->input->post('notes', true);
        $tax = $this->input->post('tax_handle');
        $ship_taxtype = $this->input->post('ship_taxtype');
        $total_tax = 0;
        $total_discount = 0;
        $discountFormat = $this->input->post('discountFormat');
        $pterms = $this->input->post('pterms');
        $purchase_order = $this->input->post('purchase_order');
        $i = 0;
        if ($discountFormat == '0') {
            $discstatus = 0;
        } else {
            $discstatus = 1;
        }
     
        $subtotal = rev_amountExchange_s($this->input->post('subtotal'), $currency, $this->aauth->get_user()->loc);
        $shipping = rev_amountExchange_s($this->input->post('shipping'), $currency, $this->aauth->get_user()->loc);
        $shipping_tax = rev_amountExchange_s($this->input->post('ship_tax'), $currency, $this->aauth->get_user()->loc);
        if ($ship_taxtype == 'incl') $shipping = $shipping - $shipping_tax;
        $refer = $this->input->post('refer', true);
        $total = rev_amountExchange_s($this->input->post('total'), $currency, $this->aauth->get_user()->loc);
        if ($customer_id == 0) {
            echo json_encode(array('status' => 'Error', 'message' =>
                "Please add a new person or search from a previous added!"));
            exit;
        }
        $this->db->trans_start();
        //products
        $transok = true;
        //Invoice Data transa total
        $bill_date = datefordatabase($invoicedate);
        // $bill_due_date = datefordatabase($invocieduedate);
        $grandsubtotal = 0;
        if (!$currency) $currency = 0; 
        // $data = array('tid' => $invocieno, 'invoicedate' => $bill_date, 'invoiceduedate' => $bill_due_date, 'subtotal' => numberClean($subtotal), 'shipping' => numberClean($shipping), 'ship_tax' => $shipping_tax, 'ship_tax_type' => $ship_taxtype, 'total' => numberClean($total), 'notes' => $notes, 'csd' => $customer_id, 'eid' => $this->aauth->get_user()->id, 'taxstatus' => $tax, 'discstatus' => $discstatus, 'format_discount' => $discountFormat, 'refer' => $refer, 'term' => $pterms, 'loc' => $this->aauth->get_user()->loc, 'i_class' => $person_type, 'multi' => $currency, 'purchase_reciept_id' => $purchase_reciept_id, 'purchase_id' => $purchase_id,'prepared_dt'=>date('Y-m-d H:i:s'), 'prepared_flg'=>'1', 'prepared_by'=>$this->session->userdata('id'),'return_status'=>'Pending','purchase_reciept_number'=>$purchase_reciept_number,'purchase_order' => $purchase_order,'created_dt'=>date('Y-m-d H:i:s'),'created_by'=>$this->session->userdata('id'));
        $supplier_id = $this->input->post('customer_id');
        $receipt_return_number = ($receipt_return_number) ? $receipt_return_number: $this->purchasereturn->last_return();  
        $data = array('receipt_return_number' => $receipt_return_number, 'return_date' => $bill_date, 'subtotal' => numberClean($subtotal), 'shipping_charge' => numberClean($shipping), 'shipping_tax' => $shipping_tax, 'shipping_tax_type' => $ship_taxtype, 'total' => numberClean($total), 'notes' => $notes, 'tax' => $tax, 'internal_reference' => $refer, 'prepared_date'=>date('Y-m-d H:i:s'), 'prepared_flag'=>'1', 'prepared_by'=>$this->session->userdata('id'),'return_status'=>'Pending','purchase_reciept_number'=>$purchase_reciept_number,'created_date'=>date('Y-m-d H:i:s'),'created_by'=>$this->session->userdata('id'),'store_id'=>$store_id,'supplier_id'=>$supplier_id,'purchase_order' => $purchase_order);

        $changedFields = $_POST['changedFields'];
       
        if($iid>0){
            $this->db->update('cberp_purchase_reciept_returns', $data,['receipt_return_number'=>$receipt_return_number]);
            $invocieno = $iid;            
            $this->db->delete('cberp_purchase_reciept_returns_items', array('receipt_return_number' => $receipt_return_number));
            detailed_log_history($this->module_number,$receipt_return_number,'Updated', $changedFields);
        }
        else{
             $data['receipt_return_number'] = $this->purchasereturn->last_return();  
            //  print_r($this->purchasereturn->last_return());
            $this->db->insert('cberp_purchase_reciept_returns', $data);
            //  die($this->db->last_query());
            $invocieno = $this->db->insert_id();
            detailed_log_history('Purchasereceipt',$purchase_reciept_number,'Purchase Receipt converted to Purchase Return', $changedFields);
            detailed_log_history($this->module_number,$receipt_return_number,'Created', $changedFields);
        }
        //erp2024 06-01-2025 detailed history log starts
       
        
        //erp2024 06-01-2025 detailed history log ends 
        // file upload section starts 22-01-2025
           if($_FILES['upfile'])
           {
               upload_files($_FILES['upfile'], 'Purchasereturn',$receipt_return_number);
           }
        // file upload section ends 22-01-2025
        if(!empty($purchase_reciept_number))
        {
            insertion_to_tracking_table('purchase_reciept_return_id', $invocieno, 'purchase_reciept_return_number', $receipt_return_number,'purchase_reciept_number',$purchase_reciept_number);
        }
        if ($invocieno) {
            
            $pid = $this->input->post('pid');
            $productlist = array();
            $prodindex = 0;
            $itc = 0;
            $product_id = $this->input->post('pid');
            $product_name1 = $this->input->post('product_name', true);
            $product_code = $this->input->post('product_code', true);
            $product_qty = $this->input->post('product_qty');
            $product_price = $this->input->post('product_price');
            $product_tax = $this->input->post('product_tax');
            $product_discount = $this->input->post('product_discount');
            $product_subtotal = $this->input->post('product_subtotal');
            $ptotal_tax = $this->input->post('taxa');
            $ptotal_disc = $this->input->post('disca');
            $product_unit = $this->input->post('unit');
            $product_hsn = $this->input->post('hsn');
            $code = $this->input->post('code');
            $damaged_quantity = $this->input->post('damage');
            $grandprice=0;
            foreach ($product_name1 as $key => $value) {
                if(intval($product_qty[$key]) > 0 && !empty($product_name1[$key]))
                {
                    $total_discount += numberClean(@$ptotal_disc[$key]);
                    $total_tax += numberClean($ptotal_tax[$key]);
                    $grandsubtotal =  $grandsubtotal + rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc);
                    $actulprice = rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc)*numberClean($product_qty[$key]);
                    $grandprice += numberClean($actulprice);
                    $data = array(
                        'receipt_return_number' => $receipt_return_number,
                        'product_code' => $product_hsn[$key],
                        'quantity' => numberClean($product_qty[$key]),
                        'damaged_quantity' => numberClean($damaged_quantity[$key]),
                        'price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
                        'line_total' => numberClean($product_subtotal[$key]),
                        'account_number' => numberClean($product_subtotal[$key]),
                    );
                    $productlist[$prodindex] = $data;
                    $i++;
                    $prodindex++;
                }
                
            }

            if ($prodindex > 0) {
                $this->db->insert_batch('cberp_purchase_reciept_returns_items', $productlist);
                // $this->db->set(array('discount' => rev_amountExchange_s(amountFormat_general($total_discount), $currency, $this->aauth->get_user()->loc), 'tax' => rev_amountExchange_s(amountFormat_general($total_tax), $currency, $this->aauth->get_user()->loc), 'items' => $itc));
                // $this->db->where('id', $invocieno);
                // $this->db->update('cberp_purchase_reciept_returns');
                
            } else {
                echo json_encode(array('status' => 'Error', 'message' =>"Please choose product from product list. Go to Item manager section if you have not added the products."));
                $transok = false;
            }
          
            echo json_encode(['status' => 'Success', 'message' => 'Write-off operation successfully completed']);
            
        } else {
            echo json_encode(array('status' => 'Error', 'message' => $this->lang->line('ERROR')));
            $transok = false;
        }


        if ($transok) {
            $this->db->trans_complete();
        } else {
            $this->db->trans_rollback();
        }
    }

    public function ajax_list()
    {
        $no = $this->input->post('start');
        $type = $this->input->get('t');
        $list = $this->purchasereturn->get_datatables($type);
        $data = array();
        $prefix =  $this->prifix51['purchasereturn_prefix'];
        foreach ($list as $invoices) {
            $no++;
            $approvstatus = "";
            $actionbtn = "";
            $validtoken = hash_hmac('ripemd160', 'p' . $invoice['iid'], $this->config->item('encryption_key'));
            switch (true) {
                // purchasereturns/create?pid=1&token=0ebdf108dc24b5d08ca2329c0a7048458e4b85c7
                case ($invoices->return_status == "Pending" && $invoices->approval_flag == "0"):
                    $actionbtn = '<a href="' . base_url("purchasereturns/create?pid=$invoices->receipt_return_number&token=$validtoken") . '" title="Approve Now" class="btn btn-sm btn-secondary">Approve Now</a>';
                    $approvstatus = '';
                    break;

                case ($invoices->approval_flag == "1" && $invoices->return_status == "Assigned" && $invoices->assign_to == $this->session->userdata('id')):
                    $approvstatus = '<span class="st-received">' . $this->lang->line('Approved') . '</span>';
                    $actionbtn = '<a href="' . base_url("purchasereturns/create?pid=$invoices->receipt_return_number&token=$validtoken") . '" title="Accept & Send" class="btn btn-sm btn-secondary">Accept Send</a>';
                    break;
               

                case ($invoices->approval_flag == "1" && $invoices->return_status != "Reverted" && $invoices->return_status != "Received"):
                    $approvstatus = '<span class="st-received">' . $this->lang->line('Approved') . '</span>';
                    $actionbtn = '<a href="' . base_url("purchasereturns/create?pid=$invoices->receipt_return_number&token=$validtoken") . '" title="Approve Now" class="btn btn-sm btn-secondary"><i class="fa fa-edit"></i></a>';
                    break;
                case ($invoices->return_status == "Received"):
                    $approvstatus = '<span class="st-received">' . $this->lang->line('Approved') . '</span>';
                    $actionbtn = '<a href="' . base_url("purchasereturns/create?pid=$invoices->receipt_return_number&token=$validtoken") . '" title="Approve Now" class="btn btn-sm btn-secondary"><i class="fa fa-edit"></i></a>&nbsp;<a href="' . base_url("purchasereturns/create?pid=$invoices->id&token=$validtoken") . '" title="Stock Return" class="btn btn-sm btn-secondary">Stock Return</a>';
                    break;

                case ($invoices->return_status == "Reverted"):
                    $approvstatus = '<span class="st-received">' . $this->lang->line('Approved') . '</span>';
                    $actionbtn = '<a href="' . base_url("purchasereturns/create?pid=$invoices->receipt_return_number&token=$validtoken") . '" title="Approve Now" class="btn btn-sm btn-secondary"><i class="fa fa-edit"></i></a>&nbsp;<a href="' . base_url("Invoices/costing?pid=$invoices->id") . '" title="Approve Now" class="btn btn-sm btn-secondary">Ready To Send</a>';
                    break;
            
                case ($invoices->return_status == "Draft"):
                    $approvstatus = '';
                    $actionbtn = '<a href="' . base_url("purchasereturns/create?pid=$invoices->receipt_return_number&token=$validtoken") . '" title="Approve Now" class="btn btn-sm btn-secondary"><i class="fa fa-edit"></i></a>';
                    break;
               
            
                default:
                    // Handle any other cases here if needed
                    break;
            }
            $row = array();
            $row[] = $no;
            $row[] = '<a href="' . base_url("purchasereturns/create?pid=$invoices->receipt_return_number&token=$validtoken") . '">&nbsp; ' .$invoices->receipt_return_number . '</a>';
            $row[] = $invoices->purchase_reciept_number;
            // $row[] = '<a href="' . base_url("stockreturn/view?id=$invoices->id") . '">&nbsp; ' . $invoices->tid . '</a>';
            $row[] = $invoices->name;
            $row[] = dateformat($invoices->return_date);
            $row[] = $invoices->total;
            $row[] = (strtolower($invoices->return_status)=='pending') ? '<span class="st-'.strtolower($invoices->return_status).'">Created</span>' :'<span class="st-'.strtolower($invoices->return_status).'">'.$invoices->return_status.'</span>';
            $row[] = $approvstatus;
            $row[] = '<span class="st-'.strtolower($invoices->payment_status).'">'.$invoices->payment_status.'</span>';
            //erp2024 hide on 23-03-2025
            // $row[] = $invoices->assigned_employee;
            $row[] = $actionbtn;
            // $row[] = '<a href="#" data-object-id="' . $invoices->id . '" class="btn btn-secondary btn-sm delete-object"><span class="fa fa-trash"></span></a>';
            // $row[] = '<a href="' . base_url("stockreturn/view?id=$invoices->id") . '" class="btn btn-success btn-sm" title="View"><i class="fa fa-eye"></i></a> <a href="' . base_url("billing/printinvoice?id=$invoices->id") . '&token=1" class="btn btn-info btn-sm"  title="Download"><span class="fa fa-download"></span></a>&nbsp;<a href="#" data-object-id="' . $invoices->id . '" class="btn btn-danger btn-sm delete-object"><span class="fa fa-trash"></span></a>';
            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->purchasereturn->count_all(),
            "recordsFiltered" => $this->purchasereturn->count_filtered(),
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
        $data['invoice'] = $this->purchasereturn->purchase_details($tid);
        $data['products'] = $this->purchasereturn->purchase_products($tid);
        $data['activity'] = $this->purchasereturn->purchase_transactions($tid);
        $data['attach'] = $this->purchasereturn->attach($tid);
        $data['employee'] = $this->purchasereturn->employee($data['invoice']['eid']);
        $head['usernm'] = $this->aauth->get_user()->username;
        $head['title'] = "Stock return Order " . $data['invoice']['iid'];
        if (($data['invoice']['i_class'] != 2) or ($data['invoice']['i_class'] == 2)) {
            $this->load->view('fixed/header', $head);
            if ($data['invoice']['tid']) $this->load->view('stockreturn/view', $data);
            $this->load->view('fixed/footer');
        }

    }


    public function printinvoice()
    {
        $tid = $this->input->get('id');
        $ty = $this->input->get('ty');
        $data['id'] = $tid;
        $data['title'] = "Stock Return $tid";
        $data['invoice'] = $this->purchasereturn->purchase_details($tid);
        $data['products'] = $this->purchasereturn->purchase_products($tid);
        $data['employee'] = $this->purchasereturn->employee($data['invoice']['eid']);
        if (($data['invoice']['i_class'] != 2) or ($data['invoice']['i_class'] == 2)) {
            if ($ty < 2) {
                $data['general'] = array('title' => $this->lang->line('Stock Return'), 'person' => $this->lang->line('Supplier'), 'prefix' => prefix(4), 't_type' => 0);
            } else {
                $data['general'] = array('title' => $this->lang->line('Credit Note'), 'person' => $this->lang->line('Customer'), 'prefix' => prefix(4), 't_type' => 0);
            }
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
            $file_name = preg_replace('/[^A-Za-z0-9]+/', '-', 'Credit_note__' . $data['invoice']['name'] . '_' . $data['invoice']['tid']);
            if ($this->input->get('d')) {
                $pdf->Output($file_name . '.pdf', 'D');
            } else {
                $pdf->Output($file_name . '.pdf', 'I');
            }
        }
    }

    public function delete_i()
    {
        $id = $this->input->post('deleteid');
        if ($this->purchasereturn->purchase_delete($id, $this->limited)) {
            echo json_encode(array('status' => 'Success', 'message' =>
                $this->lang->line('DELETED')));
        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
                $this->lang->line('ERROR')));
        }
    }

    public function editaction()
    {
        $customer_id = $this->input->post('customer_id');
        $person_type = $this->input->post('person_type');
        if ($person_type) {
            // if (!$this->aauth->premission(2)) {
            //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
            // }
        }
        if ($person_type == 2) {
            // if (!$this->aauth->premission(1)) {
            //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
            // }
        }
        $invocieno = $this->input->post('iid');
        $invoicedate = $this->input->post('invoicedate');
        $invocieduedate = $this->input->post('invocieduedate');
        $notes = $this->input->post('notes', true);
        $tax = $this->input->post('tax_handle');
        $ship_taxtype = $this->input->post('ship_taxtype');
        $total_tax = 0;
        $total_discount = 0;
        $discountFormat = $this->input->post('discountFormat');
        $pterms = $this->input->post('pterms');
        $i = 0;
        if ($discountFormat == '0') {
            $discstatus = 0;
        } else {
            $discstatus = 1;
        }
        if ($customer_id == 0) {
            echo json_encode(array('status' => 'Error', 'message' =>
                "Please add a new supplier or search from a previous added!"));
            exit;
        }
        $currency = $this->input->post('mcurrency');
        $subtotal = rev_amountExchange_s($this->input->post('subtotal'), $currency, $this->aauth->get_user()->loc);
        $shipping = rev_amountExchange_s($this->input->post('shipping'), $currency, $this->aauth->get_user()->loc);
        $shipping_tax = rev_amountExchange_s($this->input->post('ship_tax'), $currency, $this->aauth->get_user()->loc);
        if ($ship_taxtype == 'incl') $shipping = $shipping - $shipping_tax;
        $refer = $this->input->post('refer', true);
        $total = rev_amountExchange_s($this->input->post('total'), $currency, $this->aauth->get_user()->loc);
        $this->db->trans_start();
        $flag = false;
        $transok = true;
        //Product Data
        $pid = $this->input->post('pid');
        $productlist = array();
        $prodindex = 0;
        $this->db->delete('cberp_purchase_reciept_returns_items', array('tid' => $invocieno));
        $product_id = $this->input->post('pid');
        $product_name1 = $this->input->post('product_name', true);
        $product_qty = $this->input->post('product_qty');
        $old_product_qty = $this->input->post('old_product_qty');
        if ($old_product_qty == '') $old_product_qty = 0;
        $product_price = $this->input->post('product_price');
        $product_tax = $this->input->post('product_tax');
        $product_discount = $this->input->post('product_discount');
        $product_subtotal = $this->input->post('product_subtotal');
        $ptotal_tax = $this->input->post('taxa');
        $ptotal_disc = $this->input->post('disca');
        $product_des = $this->input->post('product_description', true);
        $product_unit = $this->input->post('unit');
        $product_hsn = $this->input->post('hsn');
        $code = $this->input->post('code');
        foreach ($pid as $key => $value) {
            if(intval($product_qty[$key]) > 0 && !empty($product_name1[$key]))
            {
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
                    'purchase_reciept_id' => $this->input->post('purchase_reciept_id'),
                    'code' => $code[$key]
                );
                $productlist[$prodindex] = $data;
                $i++;
                $prodindex++;
            }
            // if ($this->input->post('update_stock') == 'yes') {
            //     $amt = numberClean(@$product_qty[$key]) - numberClean(@$old_product_qty[$key]);
            //     $this->db->set('qty', "qty-$amt", FALSE);
            //     $this->db->where('pid', $product_id[$key]);
            //     $this->db->update('cberp_products');
            // }
            $flag = true;
        }
        $bill_date = datefordatabase($invoicedate);
        $bill_due_date = datefordatabase($invocieduedate);
        $data = array('invoicedate' => $bill_date, 'invoiceduedate' => $bill_due_date, 'subtotal' => numberClean($subtotal), 'shipping' => $shipping, 'ship_tax' => $shipping_tax, 'ship_tax_type' => $ship_taxtype, 'discount' => numberClean($total_discount), 'tax' => $total_tax, 'total' => numberClean($total), 'notes' => $notes, 'csd' => $customer_id, 'items' => $i, 'taxstatus' => $tax, 'discstatus' => $discstatus, 'format_discount' => $discountFormat, 'refer' => $refer, 'term' => $pterms, 'i_class' => $person_type,   'updated_dt'  => date('Y-m-d H:i:s'),'updated_by'  => $this->session->userdata('id'),'assign_to'   => $this->input->post('employee', true),'approved_by' => $this->session->userdata('id'),'approved_dt' => date('Y-m-d H:i:s'),'approvalflg' => '1','return_status' => 'Assigned');

        $this->db->set($data);
        $this->db->where('id', $invocieno);
        if ($flag) {
            if ($this->db->update('cberp_purchase_reciept_returns', $data)) {
                

                history_table_log('cberp_purchase_return_logs','purchase_return_id',$invocieno,'Assigned to Employee');
                $inventory_account_details = get_account_details("Purchase Return");
                $payable_account_details = get_account_details("Payable");
                
                if(!empty($inventory_account_details))
                {
                  
                    $inventorydata = [
                        'acid' => $inventory_account_details['id'],
                        'account' => $inventory_account_details['holder'],
                        'type' => 'Credit',
                        'cat' => 'Purchase Return',
                        'credit' => numberClean($subtotal),
                        'eid' => $this->session->userdata('id'),
                        'date' => date('Y-m-d')
                    ];
                    $this->db->insert('cberp_transactions',$inventorydata);
                }
                if(!empty($inventory_account_details))
                {
                    $accounts_payable_data = [
                        'acid' => $payable_account_details['id'],
                        'account' => $payable_account_details['holder'],
                        'type' => 'Debit',
                        'cat' => 'Purchase Return',
                        'debit' => numberClean($subtotal),
                        'eid' => $this->session->userdata('id'),
                        'date' => date('Y-m-d')
                    ];
                    $this->db->insert('cberp_transactions',$accounts_payable_data);

                }


                $this->db->insert_batch('cberp_purchase_reciept_returns_items', $productlist);
                echo json_encode(array('status' => 'Success', 'message' =>
                    "Updated! <a href='view?id=$invocieno' class='btn btn-info btn-sm'><span class='fa fa-eye' aria-hidden='true'></span></a> "));
            } else {
                echo json_encode(array('status' => 'Error', 'message' =>
                    "There is a missing field!"));
                $transok = false;
            }
        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
                "Please add atleast one product in order!"));
            $transok = false;
        }

        // if ($this->input->post('update_stock') == 'yes') {
        //     if ($this->input->post('restock')) {
        //         foreach ($this->input->post('restock') as $key => $value) {
        //             $myArray = explode('-', $value);
        //             $prid = $myArray[0];
        //             $dqty = numberClean($myArray[1]);
        //             if ($prid > 0) {
        //                 $this->db->set('qty', "qty-$dqty", FALSE);
        //                 $this->db->where('pid', $prid);
        //                 $this->db->update('cberp_products');
        //             }
        //         }
        //     }
        // }
        $log = [
            'stock_return_id' => $invocieno,
            'purchase_id' => $this->input->post('purchase_id', true),
            'performed_by' => $this->session->userdata('id'),
            'performed_dt' => date("Y-m-d H:i:s"),
            'action_performed' => 'Stock Receipt approved and assign to an employee',
        ];
        $this->db->insert('supplier_stock_return_log', $log);
        //erp2024 06-01-2025 detailed history log starts
        detailed_log_history($this->module_number,$invocieno,'Assign and Updated', $_POST['changedFields']);
        //erp2024 06-01-2025 detailed history log ends 
        // file upload section starts 22-01-2025
            if($_FILES['upfile'])
            {
                upload_files($_FILES['upfile'], 'Purchasereturn',$invocieno);
            }
        // file upload section ends 22-01-2025
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
        $this->db->select('i_class');
        $this->db->from('cberp_purchase_reciept_returns');
        $this->db->where('id', $tid);
        $query = $this->db->get();
        $stock = $query->row_array();
        if (($stock['i_class'] != 2) or ($stock['i_class'] == 2)) {
            $this->db->set('status', $status);
            $this->db->where('id', $tid);
            $this->db->update('cberp_purchase_reciept_returns');
            echo json_encode(array('status' => 'Success', 'message' =>
                'Status updated successfully!', 'pstatus' => $status));
        }
    }

    public function file_handling()
    {
        if ($this->input->get('op')) {
            $name = $this->input->get('name');
            $invoice = $this->input->get('invoice');
            if ($this->purchasereturn->meta_delete($invoice, 5, $name)) {
                echo json_encode(array('status' => 'Success'));
            }
        } else {
            $id = $this->input->get('id');
            $this->load->library("Uploadhandler_generic", array(
                'accept_file_types' => '/\.(gif|jpe?g|png|docx|docs|txt|pdf|xls)$/i', 'upload_dir' => FCPATH . 'userfiles/attach/', 'upload_url' => base_url() . 'userfiles/attach/'
            ));
            $files = (string)$this->uploadhandler_generic->filenaam();
            if ($files != '') {
                $this->purchasereturn->meta_insert($id, 5, $files);
            }
        }
    }

    public function cancelorder()
    {
        $tid = intval($this->input->post('tid'));
        $this->db->select('i_class');
        $this->db->from('cberp_purchase_reciept_returns');
        $this->db->where('id', $tid);
        $query = $this->db->get();
        $stock = $query->row_array();
        if (($stock['i_class'] != 2) or ($stock['i_class'] == 2)) {
            $this->db->set('pamnt', "0.00", FALSE);
            $this->db->set('status', 'canceled');
            $this->db->where('id', $tid);
            $this->db->update('cberp_purchase_reciept_returns');
            //reverse
            $this->db->select('credit,acid');
            $this->db->from('cberp_transactions');
            $this->db->where('tid', $tid);
            $this->db->where('ext', 6);
            $query = $this->db->get();
            $revresult = $query->result_array();
            foreach ($revresult as $trans) {
                $amt = $trans['credit'];
                $this->db->set('lastbal', "lastbal-$amt", FALSE);
                $this->db->where('id', $trans['acid']);
                $this->db->update('cberp_accounts');
            }
            $this->db->select('pid,qty');
            $this->db->from('cberp_purchase_reciept_returns_items');
            $this->db->where('tid', $tid);
            $query = $this->db->get();
            $prevresult = $query->result_array();
            foreach ($prevresult as $prd) {
                $amt = $prd['qty'];
                $this->db->set('qty', "qty+$amt", FALSE);
                $this->db->where('pid', $prd['pid']);
                $this->db->update('cberp_products');
            }
            $this->db->delete('cberp_transactions', array('tid' => $tid, 'ext' => 6));
            echo json_encode(array('status' => 'Success', 'message' =>
                $this->lang->line('Return canceled')));

        }
    }


    public function pay()
    {
        $this->load->library("Custom");
        $tid = intval($this->input->post('tid'));
        $this->db->select('i_class');
        $this->db->from('cberp_purchase_reciept_returns');
        $this->db->where('id', $tid);
        $query = $this->db->get();
        $stock = $query->row_array();
        if (($stock['i_class'] != 2) or ($stock['i_class'] == 2)) {

            $amount = rev_amountExchange_s($this->input->post('amount', true), 0, $this->aauth->get_user()->loc);
            $paydate = $this->input->post('paydate');
            $note = $this->input->post('shortnote', true);
            $pmethod = $this->input->post('pmethod');
            $acid = $this->input->post('account');
            $cid = $this->input->post('cid');
            $cname = $this->input->post('cname', true);
            $paydate = datefordatabase($paydate);


            if ($stock['i_class'] == 2 or $stock['i_class'] == 1) {
                $this->db->select('holder');
                $this->db->from('cberp_accounts');
                $this->db->where('id', $acid);
                $query = $this->db->get();
                $account = $query->row_array();

                $data = array(
                    'acid' => $acid,
                    'account' => $account['holder'],
                    'type' => 'Expense',
                    'cat' => 'Credit Note',
                    'debit' => $amount,
                    'payer' => $cname,
                    'payerid' => $cid,
                    'method' => $pmethod,
                    'date' => $paydate,
                    'eid' => $this->aauth->get_user()->id,
                    'tid' => $tid,
                    'note' => $note,
                    'ext' => 6
                );
                $this->db->insert('cberp_transactions', $data);
                $this->db->insert_id();
                $this->db->select('total,csd,pamnt');
                $this->db->from('cberp_purchase_reciept_returns');
                $this->db->where('id', $tid);
                $query = $this->db->get();
                $invresult = $query->row();
                $totalrm = $invresult->total - $invresult->pamnt;
                if ($totalrm > $amount) {
                    $this->db->set('pmethod', $pmethod);
                    $this->db->set('pamnt', "pamnt+$amount", FALSE);
                    $this->db->set('status', 'partial');
                    $this->db->where('id', $tid);
                    $this->db->update('cberp_purchase_reciept_returns');
                    //account update
                    $this->db->set('lastbal', "lastbal-$amount", FALSE);
                    $this->db->where('id', $acid);
                    $this->db->update('cberp_accounts');
                    $paid_amount = $invresult->pamnt + $amount;
                    $status = 'Partial';
                    $totalrm = $totalrm - $amount;
                } else {
                    $this->db->set('pmethod', $pmethod);
                    $this->db->set('pamnt', "pamnt+$amount", FALSE);
                    $this->db->set('status', 'accepted');
                    $this->db->where('id', $tid);
                    $this->db->update('cberp_purchase_reciept_returns');
                    //acount update
                    $this->db->set('lastbal', "lastbal-$amount", FALSE);
                    $this->db->where('id', $acid);
                    $this->db->update('cberp_accounts');
                    $totalrm = 0;
                    $status = 'Accepted';
                    $paid_amount = $amount;
                }
                $dual = $this->custom->api_config(65);
                if ($dual['key1']) {

                    $this->db->select('holder');
                    $this->db->from('cberp_accounts');
                    $this->db->where('id', $dual['url']);
                    $query = $this->db->get();
                    $account = $query->row_array();

                    $data['debit'] = 0;
                    $data['credit'] = $amount;
                    $data['type'] = 'Income';
                    $data['acid'] = $dual['url'];
                    $data['account'] = $account['holder'];
                    $data['note'] = 'Credit ' . $data['note'];

                    $this->db->insert('cberp_transactions', $data);

                    //account update
                    $this->db->set('lastbal', "lastbal+$amount", FALSE);
                    $this->db->where('id', $dual['url']);
                    $this->db->update('cberp_accounts');
                }
            } else {


                $this->db->select('holder');
                $this->db->from('cberp_accounts');
                $this->db->where('id', $acid);
                $query = $this->db->get();
                $account = $query->row_array();

                $data = array(
                    'acid' => $acid,
                    'account' => $account['holder'],
                    'type' => 'Income',
                    'cat' => 'Purchase',
                    'credit' => $amount,
                    'payer' => $cname,
                    'payerid' => $cid,
                    'method' => $pmethod,
                    'date' => $paydate,
                    'eid' => $this->aauth->get_user()->id,
                    'tid' => $tid,
                    'note' => $note,
                    'ext' => 6
                );
                $this->db->insert('cberp_transactions', $data);
                $this->db->insert_id();
                $this->db->select('total,csd,pamnt');
                $this->db->from('cberp_purchase_reciept_returns');
                $this->db->where('id', $tid);
                $query = $this->db->get();
                $invresult = $query->row();
                $totalrm = $invresult->total - $invresult->pamnt;
                if ($totalrm > $amount) {
                    $this->db->set('pmethod', $pmethod);
                    $this->db->set('pamnt', "pamnt+$amount", FALSE);
                    $this->db->set('status', 'partial');
                    $this->db->where('id', $tid);
                    $this->db->update('cberp_purchase_reciept_returns');
                    //account update
                    $this->db->set('lastbal', "lastbal-$amount", FALSE);
                    $this->db->where('id', $acid);
                    $this->db->update('cberp_accounts');
                    $paid_amount = $invresult->pamnt + $amount;
                    $status = 'Partial';
                    $totalrm = $totalrm - $amount;
                } else {
                    $this->db->set('pmethod', $pmethod);
                    $this->db->set('pamnt', "pamnt+$amount", FALSE);
                    $this->db->set('status', 'accepted');
                    $this->db->where('id', $tid);
                    $this->db->update('cberp_purchase_reciept_returns');
                    //acount update
                    $this->db->set('lastbal', "lastbal-$amount", FALSE);
                    $this->db->where('id', $acid);
                    $this->db->update('cberp_accounts');
                    $totalrm = 0;
                    $status = 'Accepted';
                    $paid_amount = $amount;
                }
            }


            $activitym = "<tr><td>" . substr($paydate, 0, 10) . "</td><td>$pmethod</td><td>$amount</td><td>$note</td></tr>";
            echo json_encode(array('status' => 'Success', 'message' =>
                $this->lang->line('Transaction has been added'), 'pstatus' => $this->lang->line($status), 'activity' => $activitym, 'amt' => $totalrm, 'ttlpaid' => $paid_amount));

        }
    }


    //erp2024 supervisor directly send stock return
    public function stock_return_send_by_admin_action()
    {
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
        $transaction_number = get_latest_trans_number();
        $customer_id = $this->input->post('customer_id');
        $person_type = $this->input->post('person_type');
        if ($person_type) {
            // if (!$this->aauth->premission(2)) {
            //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
            // }
        }
        if ($person_type == 2) {
            // if (!$this->aauth->premission(1)) {
            //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
            // }
        }
        $invocieno = $this->input->post('iid');
        $receipt_return_number = $this->input->post('stock_return_number');
        $invoicedate = $this->input->post('invoicedate');
        $invocieduedate = $this->input->post('invocieduedate');
        $notes = $this->input->post('notes', true);
        $tax = $this->input->post('tax_handle');
        $ship_taxtype = $this->input->post('ship_taxtype');
        $total_tax = 0;
        $total_discount = 0;
        $discountFormat = $this->input->post('discountFormat');
        $pterms = $this->input->post('pterms');
        $i = 0;
        if ($discountFormat == '0') {
            $discstatus = 0;
        } else {
            $discstatus = 1;
        }
        if ($customer_id == 0) {
            echo json_encode(array('status' => 'Error', 'message' =>
                "Please add a new supplier or search from a previous added!"));
            exit;
        }
        $store_id = $this->input->post('s_warehouses');
        $currency = $this->input->post('mcurrency');
        $subtotal = rev_amountExchange_s($this->input->post('subtotal'), $currency, $this->aauth->get_user()->loc);
        $shipping = rev_amountExchange_s($this->input->post('shipping'), $currency, $this->aauth->get_user()->loc);
        $shipping_tax = rev_amountExchange_s($this->input->post('ship_tax'), $currency, $this->aauth->get_user()->loc);
        if ($ship_taxtype == 'incl') $shipping = $shipping - $shipping_tax;
        $refer = $this->input->post('refer', true);
        $total = rev_amountExchange_s($this->input->post('total'), $currency, $this->aauth->get_user()->loc);
        $this->db->trans_start();
        $flag = false;
        $transok = true;
        //Product Data
        $pid = $this->input->post('pid');
        $productlist = array();
        $prodindex = 0;
        $this->db->delete('cberp_purchase_reciept_returns_items', array('receipt_return_number' => $receipt_return_number));
        $product_id = $this->input->post('pid');
        $product_name1 = $this->input->post('product_name', true);
        $product_qty = $this->input->post('product_qty');
        $old_product_qty = $this->input->post('old_product_qty');
        if ($old_product_qty == '') $old_product_qty = 0;
        $product_price = $this->input->post('product_price');
        $product_tax = $this->input->post('product_tax');
        $product_discount = $this->input->post('product_discount');
        $product_subtotal = $this->input->post('product_subtotal');
        $ptotal_tax = $this->input->post('taxa');
        $ptotal_disc = $this->input->post('disca');
        $product_unit = $this->input->post('unit');
        $product_hsn = $this->input->post('hsn');
        $code = $this->input->post('code');
        $damaged_qty = $this->input->post('damage');
        $purchase_reciept_number = $this->input->post('purchase_reciept_number');
        $store_id = $this->input->post('s_warehouses');
        $supplier_id = $this->input->post('customer_id');
        if(empty($receipt_return_number))
        {
            $bill_date = datefordatabase($invoicedate);

            // $data1 = array('prepared_by' => $this->session->userdata('id'),'prepared_date'=>date('Y-m-d H:i:s'),'prepared_flag'=>'1','purchase_reciept_number'=>$purchase_reciept_number);
            //return_date
            $data1 = array('receipt_return_number' => $receipt_return_number, 'subtotal' => numberClean($subtotal), 'shipping_charge' => numberClean($shipping), 'shipping_tax' => $shipping_tax, 'shipping_tax_type' => $ship_taxtype, 'total' => numberClean($total), 'notes' => $notes, 'tax' => $tax, 'internal_reference' => $refer, 'prepared_date'=>date('Y-m-d H:i:s'), 'prepared_flag'=>'1', 'prepared_by'=>$this->session->userdata('id'),'return_status'=>'Pending','purchase_reciept_number'=>$purchase_reciept_number,'created_date'=>date('Y-m-d H:i:s'),'created_by'=>$this->session->userdata('id'),'store_id'=>$store_id,'supplier_id' => $supplier_id);
            
            $this->db->insert('cberp_purchase_reciept_returns', $data1);
            $invocieno = $this->db->insert_id();
            insertion_to_tracking_table('purchase_reciept_return_id', $invocieno, 'purchase_reciept_return_number', $this->input->post('stock_return_number', true),'purchase_reciept_number',$purchase_reciept_number);
        }
        else{
              $data2 = array('receipt_return_number' => $receipt_return_number, 'return_date' => date('Y-m-d H:i:s'), 'subtotal' => numberClean($subtotal), 'shipping_charge' => numberClean($shipping), 'shipping_tax' => $shipping_tax, 'shipping_tax_type' => $ship_taxtype, 'total' => numberClean($total), 'notes' => $notes, 'tax' => $tax, 'internal_reference' => $refer, 'prepared_date'=>date('Y-m-d H:i:s'), 'prepared_flag'=>'1', 'prepared_by'=>$this->session->userdata('id'),'return_status'=>'Pending','purchase_reciept_number'=>$purchase_reciept_number, 'updated_date'  => date('Y-m-d H:i:s'),'updated_by'  => $this->session->userdata('id'),'assigned_to'   => $this->session->userdata('id'),'approved_by' => $this->session->userdata('id'),'approved_date' => date('Y-m-d H:i:s'),'sent_by' => $this->session->userdata('id'),'sent_date' => date('Y-m-d H:i:s'),'approval_flag' => '1','return_status' => 'Sent','transaction_number'=>$transaction_number);
            // $this->db->set($data);
            $this->db->where('receipt_return_number', $receipt_return_number);
            $this->db->update('cberp_purchase_reciept_returns', $data2);
        }
 
       
        foreach ($pid as $key => $value) {
            if(intval($product_qty[$key]) > 0 && !empty($product_name1[$key]))
            {
                // $total_discount += numberClean(@$ptotal_disc[$key]);
                // $total_tax += numberClean($ptotal_tax[$key]);
                // $data = array(
                //     'tid' => $invocieno,
                //     'pid' => $product_id[$key],
                //     'product' => $product_name1[$key],
                //     'code' => $product_hsn[$key],
                //     'qty' => numberClean($product_qty[$key]),
                //     'price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
                //     'tax' => numberClean($product_tax[$key]),
                //     'discount' => numberClean($product_discount[$key]),
                //     'subtotal' => rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc),
                //     'totaltax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                //     'totaldiscount' => rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc),
                //     'product_des' => $product_des[$key],
                //     'unit' => $product_unit[$key],
                //     'code' => $code[$key],
                //     'damaged_qty' => $damaged_qty[$key],
                //     'purchase_reciept_id' => $this->input->post('purchase_reciept_id')
                // );
                $data = array(
                    'receipt_return_number' => $receipt_return_number,
                    'product_code' => $product_hsn[$key],
                    'quantity' => numberClean($product_qty[$key]),
                    'damaged_quantity' => numberClean($damaged_qty[$key]),
                    'price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
                    'line_total' => numberClean($product_subtotal[$key]),
                    'account_number' => numberClean($product_subtotal[$key]),
                );
                $productlist[$prodindex] = $data;
                $i++;
                $prodindex++;

                $prdqty = intval($product_qty[$key]) - intval($damaged_qty[$key]);
                $this->db->set('onhand_quantity', 'onhand_quantity-'.(int)$prdqty, FALSE);
                $this->db->where('product_code', $product_hsn[$key]);
                $this->db->update('cberp_products');
                


                $this->db->set('stock_quantity', 'stock_quantity-'.(int)$prdqty, FALSE);
                $this->db->where('store_id', $store_id);
                $this->db->where('product_code', $product_hsn[$key]);
                $this->db->update('cberp_product_to_store');
                
                //erp2024 data insert to average cost 25-02-2025
                $product_cost=0;
                insert_data_to_average_cost_table($product_hsn[$key], $product_cost,numberClean($product_qty[$key]), get_costing_transation_type("Purchase Return"));
            }


            // if ($this->input->post('update_stock') == 'yes') {
            //     $amt = numberClean(@$product_qty[$key]) - numberClean(@$old_product_qty[$key]);
            //     $this->db->set('qty', "qty-$amt", FALSE);
            //     $this->db->where('pid', $product_id[$key]);
            //     $this->db->update('cberp_products');
            // }
            $flag = true;
        }
        $bill_date = datefordatabase($invoicedate);
        $bill_due_date = datefordatabase($invocieduedate);

        // $data = array('invoicedate' => $bill_date, 'invoiceduedate' => $bill_due_date, 'subtotal' => numberClean($subtotal), 'shipping' => $shipping, 'ship_tax' => $shipping_tax, 'ship_tax_type' => $ship_taxtype, 'discount' => $total_discount, 'tax' => $total_tax, 'total' => numberClean($total), 'notes' => $notes, 'csd' => $customer_id, 'items' => $i, 'taxstatus' => $tax, 'discstatus' => $discstatus, 'format_discount' => $discountFormat, 'refer' => $refer, 'term' => $pterms, 'i_class' => $person_type,   'updated_dt'  => date('Y-m-d H:i:s'),'updated_by'  => $this->session->userdata('id'),'updated_dt'  => date('Y-m-d H:i:s'),'updated_by'  => $this->session->userdata('id'),'assign_to'   => $this->session->userdata('id'),'approved_by' => $this->session->userdata('id'),'approved_dt' => date('Y-m-d H:i:s'),'sent_by' => $this->session->userdata('id'),'sent_dt' => date('Y-m-d H:i:s'),'approvalflg' => '1','return_status' => 'Sent','transaction_number'=>$transaction_number);

        

        if($invocieno)
        {
            
        }
       
        if ($flag) {
            // if ($this->db->update('cberp_purchase_reciept_returns', $data)) {
                // history_table_log('cberp_purchase_return_logs','purchase_return_id',$invocieno,'Send by Admin');
                $inventory_account_details = default_chart_of_account("inventory");
                // $payable_account_details = default_bank_account();
                $payable_account_details = default_chart_of_account("accounts_payable");
                if(!empty($inventory_account_details))
                {
                  
                    $inventorydata = [
                        'acid' => $inventory_account_details,
                        'type' => 'Purchase Return',
                        'cat' => 'Purchase Return',
                        'credit' => numberClean($subtotal),
                        'eid' => $this->session->userdata('id'),
                        'date' => date('Y-m-d'),                        
                        'transaction_number'=>$transaction_number
                    ];
                    $this->db->insert('cberp_transactions',$inventorydata);
                    $this->db->set('lastbal', 'lastbal - ' . numberClean($subtotal), FALSE);
                    $this->db->where('acn', $inventory_account_details);
                    $this->db->update('cberp_accounts'); 
                }
                if(!empty($inventory_account_details))
                {
                    $accounts_payable_data = [
                        'acid' => $payable_account_details,
                        // 'acid' => $payable_account_details['code'],
                        'type' => 'Purchase Return',
                        'cat' => 'Purchase Return',
                        'debit' => numberClean($subtotal),
                        'eid' => $this->session->userdata('id'),
                        'date' => date('Y-m-d'),
                        'transaction_number'=>$transaction_number
                    ];
                    $this->db->insert('cberp_transactions',$accounts_payable_data);
                    $this->db->set('lastbal', 'lastbal + ' . numberClean($subtotal), FALSE);
                    $this->db->where('acn', $payable_account_details);
                    $this->db->update('cberp_accounts'); 
                }


                $this->db->insert_batch('cberp_purchase_reciept_returns_items', $productlist);
                echo json_encode(array('status' => 'Success', 'message' =>
                    "Updated! <a href='view?id=$invocieno' class='btn btn-info btn-sm'><span class='fa fa-eye' aria-hidden='true'></span></a> "));
            // } 
            // else {
            //     echo json_encode(array('status' => 'Error', 'message' =>
            //         "There is a missing field!"));
            //     $transok = false;
            // }
        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
                "Please add atleast one product in order!"));
            $transok = false;
        }

        // $log = [
        //     'stock_return_id' => $invocieno,
        //     'purchase_id' => $this->input->post('purchase_id', true),
        //     'performed_by' => $this->session->userdata('id'),
        //     'performed_dt' => date("Y-m-d H:i:s"),
        //     'action_performed' => 'Stock Receipt Directly sent by authorized person',
        // ];
        // $this->db->insert('supplier_stock_return_log', $log);
        //erp2024 06-01-2025 detailed history log starts
        detailed_log_history($this->module_number,$receipt_return_number,'Stock Receipt Directly sent by authorized person', $changedFields);
        //erp2024 06-01-2025 detailed history log ends 
        // file upload section starts 22-01-2025
            if($_FILES['upfile'])
            {
                upload_files($_FILES['upfile'], 'Purchasereturn',$invocieno);
            }
        // file upload section ends 22-01-2025

        // if ($this->input->post('update_stock') == 'yes') {
        //     if ($this->input->post('restock')) {
        //         foreach ($this->input->post('restock') as $key => $value) {
        //             $myArray = explode('-', $value);
        //             $prid = $myArray[0];
        //             $dqty = numberClean($myArray[1]);
        //             if ($prid > 0) {
        //                 $this->db->set('qty', "qty-$dqty", FALSE);
        //                 $this->db->where('pid', $prid);
        //                 $this->db->update('cberp_products');
        //             }
        //         }
        //     }
        // }
        if ($transok) {
            $this->db->trans_complete();
        } else {
            $this->db->trans_rollback();
        }
    }

    public function revert_return_by_admin_action()
    {
        
        $receipt_id = $this->input->post('receipt_id');
        $purchase_id = $this->input->post('purchase_id');
        $this->db->update('cberp_purchase_reciept_returns', ['assign_to' => NULL,'approved_dt' => (NULL),'approvalflg'=>'1','return_status'=>'Reverted'], ['id'=> $receipt_id]);
        $log = [
            'stock_return_id' => $receipt_id,
            'purchase_id' => $purchase_id,
            'performed_by' => $this->session->userdata('id'),
            'performed_dt' => date("Y-m-d H:i:s"),
            'action_performed' => 'Stock Receipt reverted by authorized person',
        ];
        $this->db->insert('supplier_stock_return_log', $log);
        history_table_log('cberp_purchase_return_logs','purchase_return_id',$invocieno,'Reverted By Admin');
        //erp2024 06-01-2025 detailed history log starts
        detailed_log_history($this->module_number,$receipt_id,'Reverted By Admin', $changedFields);
        //erp2024 06-01-2025 detailed history log ends 
        echo json_encode(array('status' => 'success'));
    }

    public function revert_stock_return_by_employee_action()
    {
        $receipt_id = $this->input->post('receipt_id');
        $purchase_id = $this->input->post('purchase_id');
        $this->db->update('cberp_purchase_reciept_returns', ['assign_to' => NULL,'approvalflg'=>'0','return_status'=>'Pending'], ['id'=> $receipt_id]);
        $log = [
            'stock_return_id' => $receipt_id,
            'purchase_id' => $purchase_id,
            'performed_by' => $this->session->userdata('id'),
            'performed_dt' => date("Y-m-d H:i:s"),
            'action_performed' => 'Stock Receipt reverted by employee',
        ];
        $this->db->insert('supplier_stock_return_log', $log);
        history_table_log('cberp_purchase_return_logs','purchase_return_id',$invocieno,'Reverted By Employee');
        echo json_encode(array('status' => 'success'));
    }

    public function stock_return_accept_by_employee()
    {
        $customer_id = $this->input->post('customer_id');
        $person_type = $this->input->post('person_type');
        if ($person_type) {
            // if (!$this->aauth->premission(2)) {
            //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
            // }
        }
        if ($person_type == 2) {
            // if (!$this->aauth->premission(1)) {
            //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
            // }
        }
        $invocieno = $this->input->post('iid');
        $invoicedate = $this->input->post('invoicedate');
        $invocieduedate = $this->input->post('invocieduedate');
        $notes = $this->input->post('notes', true);
        $tax = $this->input->post('tax_handle');
        $ship_taxtype = $this->input->post('ship_taxtype');
        $total_tax = 0;
        $total_discount = 0;
        $discountFormat = $this->input->post('discountFormat');
        $pterms = $this->input->post('pterms');
        $i = 0;
        if ($discountFormat == '0') {
            $discstatus = 0;
        } else {
            $discstatus = 1;
        }
        if ($customer_id == 0) {
            echo json_encode(array('status' => 'Error', 'message' =>
                "Please add a new supplier or search from a previous added!"));
            exit;
        }
        $currency = $this->input->post('mcurrency');
        $subtotal = rev_amountExchange_s($this->input->post('subtotal'), $currency, $this->aauth->get_user()->loc);
        $shipping = rev_amountExchange_s($this->input->post('shipping'), $currency, $this->aauth->get_user()->loc);
        $shipping_tax = rev_amountExchange_s($this->input->post('ship_tax'), $currency, $this->aauth->get_user()->loc);
        if ($ship_taxtype == 'incl') $shipping = $shipping - $shipping_tax;
        $refer = $this->input->post('refer', true);
        $total = rev_amountExchange_s($this->input->post('total'), $currency, $this->aauth->get_user()->loc);
        $this->db->trans_start();
        $flag = false;
        $transok = true;
        //Product Data
        $pid = $this->input->post('pid');
        $productlist = array();
        $prodindex = 0;
        $this->db->delete('cberp_purchase_reciept_returns_items', array('tid' => $invocieno));
        $product_id = $this->input->post('pid');
        $product_name1 = $this->input->post('product_name', true);
        $product_qty = $this->input->post('product_qty');
        $old_product_qty = $this->input->post('old_product_qty');
        if ($old_product_qty == '') $old_product_qty = 0;
        $product_price = $this->input->post('product_price');
        $product_tax = $this->input->post('product_tax');
        $product_discount = $this->input->post('product_discount');
        $product_subtotal = $this->input->post('product_subtotal');
        $ptotal_tax = $this->input->post('taxa');
        $ptotal_disc = $this->input->post('disca');
        $product_des = $this->input->post('product_description', true);
        $product_unit = $this->input->post('unit');
        $product_hsn = $this->input->post('hsn');
        $code = $this->input->post('code');
        $damaged_qty = $this->input->post('damage');
        foreach ($pid as $key => $value) {
            if(intval($product_qty[$key]) > 0 && !empty($product_name1[$key]))
            {
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
                    'code' => $code[$key],
                    'damaged_qty' => $damaged_qty[$key],
                    'purchase_reciept_id' => $this->input->post('purchase_reciept_id')
                );
                $productlist[$prodindex] = $data;
                $i++;
                $prodindex++;

                
                $prdqty = intval($product_qty[$key]) - intval($damaged_qty[$key]);
                $this->db->set('qty', 'qty-'.(int)$prdqty, FALSE);
                $this->db->where('pid', $product_id[$key]);
                $this->db->update('cberp_products');

                $this->db->set('stock_quantity', 'stock_quantity-'.(int)$prdqty, FALSE);
                $this->db->where('store_id', $store_id);
                $this->db->where('product_id', $product_id[$key]);
                $this->db->update('cberp_product_to_store');

            }
            // if ($this->input->post('update_stock') == 'yes') {
            //     $amt = numberClean(@$product_qty[$key]) - numberClean(@$old_product_qty[$key]);
            //     $this->db->set('qty', "qty-$amt", FALSE);
            //     $this->db->where('pid', $product_id[$key]);
            //     $this->db->update('cberp_products');
            // }
            $flag = true;
        }
        if($_FILES['upfile'])
        {
            upload_files($_FILES['upfile'], 'Purchasereturn',$invocieno);
        }
        $transaction_number = get_latest_trans_number();
        $bill_date = datefordatabase($invoicedate);
        $bill_due_date = datefordatabase($invocieduedate);
        $data = array('invoicedate' => $bill_date, 'invoiceduedate' => $bill_due_date, 'subtotal' => numberClean($subtotal), 'shipping' => $shipping, 'ship_tax' => $shipping_tax, 'ship_tax_type' => $ship_taxtype, 'discount' => $total_discount, 'tax' => $total_tax, 'total' => numberClean($total), 'notes' => $notes, 'csd' => $customer_id, 'items' => $i, 'taxstatus' => $tax, 'discstatus' => $discstatus, 'format_discount' => $discountFormat, 'refer' => $refer, 'term' => $pterms, 'i_class' => $person_type,   'sent_by' => $this->session->userdata('id'),'sent_dt' => date('Y-m-d H:i:s'),'return_status' => 'Sent','transaction_number'=>$transaction_number);

        $this->db->set($data);
        $this->db->where('id', $invocieno);
        if ($flag) {
            if ($this->db->update('cberp_purchase_reciept_returns', $data)) {
                //erp2024 06-01-2025 detailed history log starts
                detailed_log_history($this->module_number,$invocieno,'Accepeted by Employee', $_POST['changedFields']);
                //erp2024 06-01-2025 detailed history log ends 
                history_table_log('cberp_purchase_return_logs','purchase_return_id',$invocieno,'Accept & Send By Employee');
                $this->db->insert_batch('cberp_purchase_reciept_returns_items', $productlist);

                $inventory_account_details = default_chart_of_account("inventory");
                $payable_account_details = default_chart_of_account("accounts_payable");
                
                if(!empty($inventory_account_details))
                {
                  
                    $inventorydata = [
                        'acid' => $inventory_account_details,
                        'type' => 'Purchase Return',
                        'cat' => 'Purchase Return',
                        'credit' => $subtotal,
                        'eid' => $this->session->userdata('id'),
                        'date' => date('Y-m-d'),                        
                        'transaction_number'=>$transaction_number
                    ];
                    $this->db->insert('cberp_transactions',$inventorydata);
                    // die($this->db->last_query());
                    $this->db->set('lastbal', 'lastbal - ' . $subtotal, FALSE);
                    $this->db->where('acn', $inventory_account_details);
                    $this->db->update('cberp_accounts'); 
                   
                }
                if(!empty($inventory_account_details))
                {
                    $accounts_payable_data = [
                        'acid' => $payable_account_details,
                        'type' => 'Purchase Return',
                        'cat' => 'Purchase Return',
                        'debit' => $subtotal,
                        'eid' => $this->session->userdata('id'),
                        'date' => date('Y-m-d'),
                        'transaction_number'=>$transaction_number
                    ];
                    $this->db->insert('cberp_transactions',$accounts_payable_data);
                    
                    $this->db->set('lastbal', 'lastbal + ' . $subtotal, FALSE);
                    $this->db->where('acn', $payable_account_details);
                    $this->db->update('cberp_accounts'); 
                }
                echo json_encode(array('status' => 'Success', 'message' =>"Updated! <a href='view?id=$invocieno' class='btn btn-info btn-sm'><span class='fa fa-eye' aria-hidden='true'></span></a> "));
            } else {
                echo json_encode(array('status' => 'Error', 'message' =>
                    "There is a missing field!"));
                $transok = false;
            }
        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
                "Please add atleast one product in order!"));
            $transok = false;
        }

        $log = [
            'stock_return_id' => $invocieno,
            'purchase_id' => $this->input->post('purchase_id', true),
            'performed_by' => $this->session->userdata('id'),
            'performed_dt' => date("Y-m-d H:i:s"),
            'action_performed' => 'Stock Receipt accepted by employee',
        ];
        $this->db->insert('supplier_stock_return_log', $log);

        if ($transok) {
            $this->db->trans_complete();
        } else {
            $this->db->trans_rollback();
        }
    }

    //erp2024 05-10-2024
    public function draftaction()
    {
        
        // echo "<pre>"; print_r(base_url("stockreturn/view?id=$invocie")); die();
        $currency = $this->input->post('mcurrency');
        $customer_id = $this->input->post('customer_id');
        $person_type = $this->input->post('person_type');
        $new_u = 'create';
        $invocieno = $this->input->post('invocieno');
        $invoicedate = $this->input->post('invoicedate');
        $invocieduedate = $this->input->post('invocieduedate');
        $purchase_reciept_id = $this->input->post('purchase_reciept_id');
        $purchase_reciept_number = $this->input->post('purchase_reciept_number');
        $purchase_id = $this->input->post('purchase_id');
        $notes = $this->input->post('notes', true);
        $tax = $this->input->post('tax_handle');
        $ship_taxtype = $this->input->post('ship_taxtype');
        $total_tax = 0;
        $total_discount = 0;
        $discountFormat = $this->input->post('discountFormat');
        $pterms = $this->input->post('pterms');
        $purchase_order = $this->input->post('purchase_order');
        $store_id = $this->input->post('s_warehouses');
        $customer_id = $this->input->post('customer_id');
        $receipt_return_number = $this->input->post('stock_return_number');
        $i = 0;
        if ($discountFormat == '0') {
            $discstatus = 0;
        } else {
            $discstatus = 1;
        }
        $subtotal = rev_amountExchange_s($this->input->post('subtotal'), $currency, $this->aauth->get_user()->loc);
        $shipping = rev_amountExchange_s($this->input->post('shipping'), $currency, $this->aauth->get_user()->loc);
        $shipping_tax = rev_amountExchange_s($this->input->post('ship_tax'), $currency, $this->aauth->get_user()->loc);
        if ($ship_taxtype == 'incl') $shipping = $shipping - $shipping_tax;
        $refer = $this->input->post('refer', true);
        $total = rev_amountExchange_s($this->input->post('total'), $currency, $this->aauth->get_user()->loc);
        if ($customer_id == 0) {
            echo json_encode(array('status' => 'Error', 'message' =>
                "Please add a new person or search from a previous added!"));
            exit;
        }
        $this->db->trans_start();
        $transok = true;
        $bill_date = datefordatabase($invoicedate);
        $bill_due_date = datefordatabase($invocieduedate);
        $grandsubtotal = 0;
        if (!$currency) $currency = 0;

        // $data = array('tid' => $invocieno, 'invoicedate' => $bill_date, 'invoiceduedate' => $bill_due_date, 'subtotal' => $subtotal, 'shipping' => $shipping, 'ship_tax' => $shipping_tax, 'ship_tax_type' => $ship_taxtype, 'total' => numberClean($total), 'notes' => $notes, 'csd' => $customer_id, 'eid' => $this->aauth->get_user()->id, 'taxstatus' => $tax, 'discstatus' => $discstatus, 'format_discount' => $discountFormat, 'refer' => $refer, 'term' => $pterms, 'loc' => $this->aauth->get_user()->loc, 'i_class' => $person_type, 'multi' => $currency, 'purchase_reciept_id' => $purchase_reciept_id, 'purchase_id' => $purchase_id,'return_status'=>'Draft','purchase_reciept_number'=>$purchase_reciept_number,'purchase_order' => $purchase_order,'created_by'=>$this->session->userdata('id'),'created_dt'=>date('Y-m-d H:i:s'));
        
        $receipt_return_number = ($receipt_return_number) ? $receipt_return_number: $this->purchasereturn->last_return(); 
        $data = array('receipt_return_number' => $receipt_return_number, 'return_date' => $bill_date, 'subtotal' => numberClean($subtotal), 'shipping_charge' => numberClean($shipping), 'shipping_tax' => $shipping_tax, 'shipping_tax_type' => $ship_taxtype, 'total' => numberClean($total), 'notes' => $notes, 'tax' => $tax, 'internal_reference' => $refer,'prepared_by'=>$this->session->userdata('id'),'return_status'=>'Draft','purchase_reciept_number'=>$purchase_reciept_number,'supplier_id' => $customer_id,'store_id'=>$store_id);
       
        if ($this->db->insert('cberp_purchase_reciept_returns', $data)) {
            $invocieno = $this->db->insert_id();            
            insertion_to_tracking_table('purchase_reciept_return_id', $invocieno, 'purchase_reciept_return_number', $this->input->post('stock_return_number', true),'purchase_reciept_number',$purchase_reciept_number);
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
            $product_hsn = $this->input->post('hsn');
            $code = $this->input->post('code');

            foreach ($pid as $key => $value) {
                if(!empty($product_name1[$key]))
                {
                    $total_discount += numberClean(@$ptotal_disc[$key]);
                    $total_tax += numberClean($ptotal_tax[$key]);
                    $grandsubtotal =  $grandsubtotal + rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc);
                    // $data = array(
                    //     'tid' => $invocieno,
                    //     'pid' => $product_id[$key],
                    //     'product' => $product_name1[$key],
                    //     'code' => $product_hsn[$key],
                    //     'qty' => numberClean($product_qty[$key]),
                    //     'price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
                    //     'tax' => numberClean($product_tax[$key]),
                    //     'discount' => numberClean($product_discount[$key]),
                    //     'subtotal' => rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc),
                    //     'totaltax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                    //     'totaldiscount' => rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc),
                    //     'product_des' => $product_des[$key],
                    //     'code' => $code[$key],
                    //     'unit' => $product_unit[$key],
                    //     'purchase_reciept_id' => $purchase_reciept_id
                    // );
                    $data = array(
                        'receipt_return_number' => $receipt_return_number,
                        'product_code' => $product_hsn[$key],
                        'quantity' => numberClean($product_qty[$key]),
                        'damaged_quantity' => numberClean($damaged_quantity[$key]),
                        'price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
                        'line_total' => numberClean($product_subtotal[$key]),
                    );
                    $productlist[$prodindex] = $data;
                    $i++;
                    $prodindex++;
                    $amt = numberClean($product_qty[$key]);
                }
                
            }
            if ($prodindex > 0) {
                $this->db->insert_batch('cberp_purchase_reciept_returns_items', $productlist);               
                $this->db->update('cberp_purchase_reciept_returns');
            }
                $log = [
                    'stock_return_id' => $invocieno,
                    'purchase_id' => $this->input->post('purchase_id', true),
                    'performed_by' => $this->session->userdata('id'),
                    'performed_dt' => date("Y-m-d H:i:s"),
                    'action_performed' => 'Stock Receipt move to draft',
                ];
                $this->db->insert('supplier_stock_return_log', $log);
                //erp2024 06-01-2025 detailed history log starts
                detailed_log_history($this->module_number,$receipt_return_number,'Data Saved as Draft', $changedFields);
                //erp2024 06-01-2025 detailed history log ends 
                // file upload section starts 22-01-2025
                  if($_FILES['upfile'])
                  {
                      upload_files($_FILES['upfile'], 'Purchasereturn',$receipt_return_number);
                  }
               // file upload section ends 22-01-2025

                $validtoken = hash_hmac('ripemd160', 'p' . $receipt_return_number, $this->config->item('encryption_key'));
                $response = array(
                    'success' => true,
                    'message' => 'Saved successfully',
                    'data'=>$receipt_return_number,
                    'token'=>$validtoken
                );
                echo json_encode($response);
            // echo json_encode(['status' => 'Success', 'message' => 'Write-off operation successfully completed']);
            
        } else {
            echo json_encode(array('status' => 'Error', 'message' => $this->lang->line('ERROR')));
            $transok = false;
        }


        if ($transok) {
            $this->db->trans_complete();
        } else {
            $this->db->trans_rollback();
        }
    }

    public function draftaction_sub()
    {
        
        $currency = $this->input->post('mcurrency');
        $customer_id = $this->input->post('customer_id');
        $person_type = $this->input->post('person_type');
        $new_u = 'create';
        $invocieno = $this->input->post('iid');
        $invoicedate = $this->input->post('invoicedate');
        $invocieduedate = $this->input->post('invocieduedate');
        $purchase_reciept_id = $this->input->post('purchase_reciept_id');
        $purchase_id = $this->input->post('purchase_id');
        $notes = $this->input->post('notes', true);
        $tax = $this->input->post('tax_handle');
        $ship_taxtype = $this->input->post('ship_taxtype');        
        $store_id = $this->input->post('s_warehouses');
        $total_tax = 0;
        $total_discount = 0;
        $discountFormat = $this->input->post('discountFormat');
        $i = 0;
        if ($discountFormat == '0') {
            $discstatus = 0;
        } else {
            $discstatus = 1;
        }
        $subtotal = rev_amountExchange_s($this->input->post('subtotal'), $currency, $this->aauth->get_user()->loc);
        $shipping = rev_amountExchange_s($this->input->post('shipping'), $currency, $this->aauth->get_user()->loc);
        $shipping_tax = rev_amountExchange_s($this->input->post('ship_tax'), $currency, $this->aauth->get_user()->loc);
        if ($ship_taxtype == 'incl') $shipping = $shipping - $shipping_tax;
        $refer = $this->input->post('refer', true);
        $total = rev_amountExchange_s($this->input->post('total'), $currency, $this->aauth->get_user()->loc);
        if ($customer_id == 0) {
            echo json_encode(array('status' => 'Error', 'message' =>
                "Please add a new person or search from a previous added!"));
            exit;
        }
        $this->db->trans_start();
        $transok = true;
        $bill_date = datefordatabase($invoicedate);
        $bill_due_date = datefordatabase($invocieduedate);
        $grandsubtotal = 0;
        if (!$currency) $currency = 0;


        $receipt_return_number = $this->input->post('stock_return_number');
        $purchase_reciept_number = $this->input->post('purchase_reciept_number');
        // $data = array('receipt_return_number' => $receipt_return_number, 'invoiceduedate' => $bill_due_date, 'subtotal' => $subtotal, 'shipping' => $shipping, 'ship_tax' => $shipping_tax, 'ship_tax_type' => $ship_taxtype, 'total' => numberClean($total), 'notes' => $notes, 'csd' => $customer_id, 'eid' => $this->aauth->get_user()->id, 'taxstatus' => $tax, 'discstatus' => $discstatus, 'format_discount' => $discountFormat, 'refer' => $refer, 'term' => $pterms, 'loc' => $this->aauth->get_user()->loc, 'i_class' => $person_type, 'multi' => $currency, 'purchase_reciept_id' => $purchase_reciept_id, 'purchase_id' => $purchase_id,'return_status'=>'Draft','purchase_order' => $purchase_order);
  
        $data = array('receipt_return_number' => $receipt_return_number, 'return_date' => $bill_date, 'subtotal' => numberClean($subtotal), 'shipping_charge' => numberClean($shipping), 'shipping_tax' => $shipping_tax, 'shipping_tax_type' => $ship_taxtype, 'total' => numberClean($total), 'notes' => $notes, 'tax' => $tax, 'internal_reference' => $refer,'prepared_by'=>$this->session->userdata('id'),'return_status'=>'Draft','purchase_reciept_number'=>$purchase_reciept_number,'store_id'=>$store_id);
        
        if ($this->db->update('cberp_purchase_reciept_returns', $data,['receipt_return_number'=>$receipt_return_number])) {
            $this->db->delete('cberp_purchase_reciept_returns_items', array('receipt_return_number' => $receipt_return_number));
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

            $product_unit = $this->input->post('unit');
            $product_hsn = $this->input->post('hsn');
            $code = $this->input->post('code');
            $damaged_quantity = $this->input->post('damage');

            foreach ($pid as $key => $value) {
                if(intval($product_qty[$key]) > 0 && !empty($product_name1[$key]))
                {
                    // $total_discount += numberClean(@$ptotal_disc[$key]);
                    // $total_tax += numberClean($ptotal_tax[$key]);
                    // $grandsubtotal =  $grandsubtotal + rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc);
                    // $data = array(
                    //     'tid' => $invocieno,
                    //     'pid' => $product_id[$key],
                    //     'product' => $product_name1[$key],
                    //     'code' => $product_hsn[$key],
                    //     'qty' => numberClean($product_qty[$key]),
                    //     'price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
                    //     'tax' => numberClean($product_tax[$key]),
                    //     'discount' => numberClean($product_discount[$key]),
                    //     'subtotal' => rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc),
                    //     'totaltax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                    //     'totaldiscount' => rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc),
                    //     'product_des' => $product_des[$key],
                    //     'code' => $code[$key],
                    //     'unit' => $product_unit[$key],
                    //     'purchase_reciept_id' => $purchase_reciept_id
                    // );
                    // $productlist[$prodindex] = $data;
                    // $i++;
                    // $prodindex++;
                    // $amt = numberClean($product_qty[$key]);

                    $total_discount += numberClean(@$ptotal_disc[$key]);
                    $total_tax += numberClean($ptotal_tax[$key]);
                    $grandsubtotal =  $grandsubtotal + rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc);
                    $actulprice = rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc)*numberClean($product_qty[$key]);
                    $grandprice += numberClean($actulprice);
                    $data = array(
                        'receipt_return_number' => $receipt_return_number,
                        'product_code' => $product_hsn[$key],
                        'quantity' => numberClean($product_qty[$key]),
                        'damaged_quantity' => numberClean($damaged_quantity[$key]),
                        'price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
                        'line_total' => numberClean($product_subtotal[$key]),
                        // 'account_number' => numberClean($product_subtotal[$key]),
                    );
                    $productlist[$prodindex] = $data;
                    $i++;
                    $prodindex++;
                }
                
            }
            if ($prodindex > 0) {
                $this->db->insert_batch('cberp_purchase_reciept_returns_items', $productlist);               
               
            }
                // $log = [
                //     'stock_return_id' => $invocieno,
                //     'purchase_id' => $this->input->post('purchase_id', true),
                //     'performed_by' => $this->session->userdata('id'),
                //     'performed_dt' => date("Y-m-d H:i:s"),
                //     'action_performed' => 'Stock Receipt move to draft',
                // ];
                // $this->db->insert('supplier_stock_return_log', $log);
                //erp2024 06-01-2025 detailed history log starts
                detailed_log_history($this->module_number,$receipt_return_number,'Data Saved as Draft', $_POST['changedFields']);
                //erp2024 06-01-2025 detailed history log ends 
                // file upload section starts 22-01-2025
                    if($_FILES['upfile'])
                    {
                        upload_files($_FILES['upfile'], 'Purchasereturn',$invocieno);
                    }
                // file upload section ends 22-01-2025

                $validtoken = hash_hmac('ripemd160', 'p' . $invocieno, $this->config->item('encryption_key'));
                $response = array(
                    'success' => true,
                    'message' => 'Saved successfully',
                    'data'=>$receipt_return_number,
                    'token'=>$validtoken
                );
                echo json_encode($response);
            // echo json_encode(['status' => 'Success', 'message' => 'Write-off operation successfully completed']);
            
        } else {
            echo json_encode(array('status' => 'Error', 'message' => $this->lang->line('ERROR')));
            $transok = false;
        }


        if ($transok) {
            $this->db->trans_complete();
        } else {
            $this->db->trans_rollback();
        }
    }

    public function purchase_return_payment()
    {
        
        $this->load->model('accounts_model');
        $data['acclist'] = $this->accounts_model->accountslist((integer)$this->aauth->get_user()->loc);
        $tid = $this->input->get('id');
        $customerid = $this->input->get('csd');
        $data['invoice'] = $this->purchasereturn->purchase_return_data($tid);
        // echo "<pre>"; print_r($data['invoice']); die();

        $this->load->model('accounts_model');
        $data['accountheaders'] = $this->accounts_model->load_coa_account_headers();
        $data['accounttypes'] = $this->accounts_model->load_coa_account_types();
        $data['accountlist'] = $this->accounts_model->load_account_list();       
        $accountchild=[];
        foreach($data['accountlist'] as $single){
            $accountchild[$single['coa_header_id']][] = $single;
        } 
        $data['accountlists'] = $accountchild;
        $data['bankaccounts'] = bank_account_list();
        $data['default_bankaccount'] = default_bank_account();
        $data['default_receivableaccount'] = default_chart_of_account('accounts_payable');
        $data['module_number'] = $this->module_number;

        // echo "<pre>"; print_r($data['invoice']); die();
        // $data['attach'] = $this->invocies->attach($tid);
        $head['usernm'] = $this->aauth->get_user()->username;
        $head['title'] = "Purchase order Payment";
        $this->load->view('fixed/header', $head);
        // if ($data['invoice']['id']) {
        //     $data['invoice']['id'] = $tid;            
            // $data['trackingdata'] = tracking_details('invoice_id',$tid);
            $this->load->view('stockreturn/purchase_return_payment', $data);
        // }
        $this->load->view('fixed/footer');
    }

 


}
