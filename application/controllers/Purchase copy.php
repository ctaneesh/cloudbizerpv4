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

class Purchase extends CI_Controller
{
    private $stock_module_group_number;
    private $my_approval_levels;
    private $all_approval_level;
    private $module_number;
    public function __construct()
    {
        parent::__construct();        
        $this->load->library('session');
        $this->load->model('purchase_model', 'purchase');
        $this->load->model('employee_model', 'employee');
        $this->load->model('plugins_model', 'plugins');
        $this->load->model('customers_model', 'customers');
        $this->load->model('authorizationapproval_model', 'authorizationapproval');
        $this->load->library("Aauth");
        if (!$this->aauth->is_loggedin()) {
            redirect('/user/', 'refresh');
        }
        $this->li_a = 'stock';
        $this->stock_module_group_number =  get_module_details_by_name('Stock');
        $this->module_number =  module_number_name('Purchase Orders');

    }
    public function selectedProducts(){  
        $this->session->unset_userdata('selectedValues');      
        if (!empty($this->input->post('selectedValues'))) {
            $this->session->set_userdata('selectedValues', $this->input->post('selectedValues'));
        }

    }

    //create invoice
    public function create()
    {
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
        $data['your_approval_level'] =  linked_user_module_approvals_by_module_number($module_number,$this->session->userdata('id'));
        $data['approval_level_users'] =  linked_user_module_approvals_by_module_number($module_number);
        // print_r($data['your_approval_level']); die(); salary 
       

        $data['lastinvoice'] = $this->purchase->lastpurchase();
        $data['permissions'] = load_permissions('Stock','Purchase Order','New Order');
        $tid = $this->input->get('id');//purchase_number
        // $data['trackingdata'] = [];
        // $data['invoice'] = [];
        // $data['products'] = [];
        // $data['assignedperson'] = []; 
        if($tid)
        {
            if($this->module_number)
            {
                // $data['approved_levels'] = function_approved_levels($this->module_number,$tid);
                // $data['approval_level_users'] =  linked_user_module_approvals_by_module_number($this->stock_module_group_number);   
                // $data['my_approval_permissions'] =  linked_user_module_approvals_by_module_number($this->stock_module_group_number,$this->session->userdata('id'));
                // $data['module_number'] = $this->module_number;
            }
            $data['trackingdata'] = tracking_details('purchase_order_number',$tid);
            $data['invoice'] = $this->purchase->purchase_details($tid);
            $data['products'] = $this->purchase->purchase_products($tid);
            $data['assignedperson'] = $this->purchase->employee($data['invoice']['assigned_to']);                
            $invoice_number = (!empty($data['invoice']['tid'])) ? $data['invoice']['tid'] : $data['lastinvoice']+1001;
        }   
        $data['poid'] = $tid;        
        //   echo "<pre>"; print_r($data['invoice']);  die();
       
        if($data['invoice']['created_by'])
        {
            $data['created_employee'] = employee_details_by_id($data['invoice']['created_by']);
            $data['colorcode'] = get_color_code($data['invoice']['duedate']);
        }
        
        // echo "<pre>"; 
        foreach ($data['products'] as $key => $value) {
        
           $data['products'][$key]['last_purchase_price'] = $this->purchase->last_purchase_price($value['product_code']);
        }
        // print_r($data['products']); die();
        $this->load->library("Common");
        $data['taxlist'] = $this->common->taxlist($this->config->item('tax'));
        $data['exchange'] = $this->plugins->universal_api(5);
        $data['currencies'] = $this->purchase->currencies();
        $data['customergrouplist'] = $this->customers->group_list();
      
        $data['terms'] = $this->purchase->billingterms();
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['warehouse'] = $this->purchase->warehouses();
        $data['taxdetails'] = $this->common->taxdetail();
        $data['approvedby'] = $this->authorizationapproval->approved_person($tid,"Purchase Order");
        $data['employee'] = $this->employee->list_employee();
       
        $data['prefix'] = get_prefix();  
        $head['title'] = "Purchase Order ".$data['invoice']['purchase_number'];    
        $data['selectedValues'] = $this->session->userdata('selectedValues');
        $data['log'] = $this->purchase->gethistory($data['invoice']['id']);
         // erp2025 09-01-2025 start
         $page = $this->module_number;
         $data['detailed_log']= get_detailed_logs($data['invoice']['purchase_number'],$page);
         $loadhistory = $data['detailed_log'];
         $groupedBySequence = []; 
         foreach ($loadhistory as $product) {
             $sequence = $product['seqence_number'];
             $groupedBySequence[$sequence][] = $product; 
         }
         $data['groupedDatas'] = $groupedBySequence;
         $data['validity'] = default_validity();
        // echo "<pre>"; print_r($data['detailed_log']); die();
         // erp2025 09-01-2025 end
         $data['images'] = get_uploaded_images('Purchaseorder',$tid);
        $this->load->view('fixed/header', $head);
        $this->load->view('purchase/newinvoice', $data);
        $this->load->view('fixed/footer');
    }
    public function create_indirect()
    {
        $this->load->library("Common");
        $data['taxlist'] = $this->common->taxlist($this->config->item('tax'));
        $this->load->model('plugins_model', 'plugins');
        $data['exchange'] = $this->plugins->universal_api(5);
        $data['currencies'] = $this->purchase->currencies();
        $this->load->model('customers_model', 'customers');
        $data['customergrouplist'] = $this->customers->group_list();
        $data['lastinvoice'] = $this->purchase->lastpurchase();
        $data['terms'] = $this->purchase->billingterms();
        $head['title'] = "New Purchase";
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['warehouse'] = $this->purchase->warehouses();
        $data['taxdetails'] = $this->common->taxdetail();
        // Load the view after setting session data
        $data['selectedValues'] = $this->session->userdata('selectedValues');
        $this->load->view('fixed/header', $head);
        $this->load->view('purchase/purchase_order_indirect', $data);
        $this->load->view('fixed/footer');
    }


    //edit invoice
    public function edit()
    {

        $tid = $this->input->get('id');
        $data['id'] = $tid;
        $data['title'] = "Purchase Order $tid";
        $this->load->model('customers_model', 'customers');             
        $data['currencies'] = $this->purchase->currencies();
        $data['customergrouplist'] = $this->customers->group_list();
        $data['terms'] = $this->purchase->billingterms();   
        $data['invoice'] = $this->purchase->purchase_details($tid);
        $data['products'] = $this->purchase->purchase_products($tid);
        $head['title'] = "Edit Invoice #$tid";
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['warehouse'] = $this->purchase->warehouses();
        $data['currency'] = $this->purchase->currencies();
        $this->load->model('plugins_model', 'plugins');
        $data['exchange'] = $this->plugins->universal_api(5);
        $this->load->library("Common");
        $data['taxlist'] = $this->common->taxlist_edit($data['invoice']['tax_status']);
        $this->load->view('fixed/header', $head);
        $this->load->view('purchase/edit', $data);
        $this->load->view('fixed/footer');
    }

    //invoices list
    public function index()
    {
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
        $data['permissions'] = load_permissions('Stock','Purchase Order','Manage Orders','List');
        $head['title'] = "Manage Purchase Orders";
        $head['usernm'] = $this->aauth->get_user()->username;
        // $this->load->model('invoices_model');       
        // $condition = "";
        // $data['counts'] = $this->invoices_model->get_dynamic_count('cberp_purchase_orders','invoicedate','total',$condition);
        $data['ranges'] = getCommonDateRanges();
        $data['counts'] = $this->purchase->get_filter_count($data['ranges']);
        $this->load->view('fixed/header', $head);
        $this->load->view('purchase/invoices', $data);
        $this->load->view('fixed/footer');
    }

    //action
    public function action()
    {
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
       

        $currency = $this->input->post('mcurrency');
        $customer_id = $this->input->post('customer_id');
        $purchase_type = $this->input->post('doc_type');
        $currency_id = $this->input->post('currency_id');
        $invocieno = $this->input->post('invocieno');
        $purchase_order_date = $this->input->post('invoicedate');
        $duedate = $this->input->post('invocieduedate');
        $store_id = $this->input->post('store_id');
        $notes = $this->input->post('notes', true);
        $tax = $this->input->post('tax_handle');
        $subtotal = rev_amountExchange_s($this->input->post('subtotal'), $currency, $this->aauth->get_user()->loc);
        $shipping = rev_amountExchange_s($this->input->post('shipping'), $currency, $this->aauth->get_user()->loc);
        $shipping_tax = rev_amountExchange_s($this->input->post('ship_tax'), $currency, $this->aauth->get_user()->loc);
        $ship_taxtype = $this->input->post('ship_taxtype');
        if ($ship_taxtype == 'incl') @$shipping = $shipping - $shipping_tax;
        $internal_reference = $this->input->post('refer', true);
        $order_total = rev_amountExchange_s($this->input->post('total'), $currency, $this->aauth->get_user()->loc);
        $total_tax = 0;
        $total_discount = 0;
        $discountFormat = $this->input->post('discountFormat');
        $payment_terms = $this->input->post('pterms');
        $purchase_number = $this->input->post('purchase_number');
        $prefix = get_prefix();  
        $purchase_number = ($purchase_number) ? $purchase_number : $prefix['po_prefix'].$this->purchase->lastpurchase();
        $i = 0;
        if ($discountFormat == '0') {
            $discstatus = 0;
        } else {
            $discstatus = 1;
        }

        
        if($this->input->post('completed_status')=='1')
        {
            if ($customer_id == 0) {
                echo json_encode(array('status' => 'Error', 'message' =>
                    "Please add a new supplier or search from a previous added!"));
                exit;
            }
            if (empty($purchase_type)) {
                echo json_encode(array('status' => 'Error', 'message' =>
                    "Please select a Purchase Type"));
                exit;
            }
            if (empty($currency_id)) {
                echo json_encode(array('status' => 'Error', 'message' =>
                    "Please select a currency"));
                exit;
            }
        }
        // $this->db->trans_start();
        //products
        $transok = true;
        //Invoice Data purchase_number
        $bill_date = datefordatabase($purchase_order_date);
        $bill_due_date = datefordatabase($duedate);
        $data = array('purchase_order_date' => $bill_date, 'duedate' => $bill_due_date, 'shipping_charge' => $shipping, 'shipping_tax' => $shipping_tax, 'shipping_tax_type' => $ship_taxtype, 'order_total' => $order_total, 'notes' => $notes, 'customer_id' => $customer_id, 'employee_id' => $this->aauth->get_user()->id, 'tax_status' => $tax, 'discstatus' => $discstatus, 'format_discount' => $discountFormat, 'internal_reference' => $internal_reference, 'payment_terms' => $payment_terms, 'loc' => $this->aauth->get_user()->loc, 'multi' => $currency, 'currency_id'=>$currency_id, 'purchase_type'=>$purchase_type, 'store_id'=>$store_id,'customer_reference' => $this->input->post('customer_reference_number'),'customer_contact_person' => $this->input->post('customer_contact_person'),'customer_contact_number' =>  $this->input->post('customer_contact_number'),'customer_contact_email' => $this->input->post('customer_contact_email'));
        $this->session->unset_userdata('selectedValues');

        //erp2024 find amout limit 19-07-2024 /////////////////////////////////////////
        $amount_limit = $this->authorizationapproval->amount_limit($this->session->userdata('id'));
        $history =[];
        if($this->input->post('completed_status')=='1'){
            $data["order_status"] = 'Pending';
        }
        else{
            $data["order_status"] = 'Draft';
        }

        if($this->input->post('customer_contact_email')=='Pending')
        {
            $data["order_status"] = 'Pending';
        }
       
        $data['prepared_by']  = $this->session->userdata('id');
        $data['prepared_date']  = date('Y-m-d H:i:s');
        $data['prepared_flag'] = '1';
        $data['purchase_number'] = $purchase_number;

        $message = "Approval Pending for the Purchase Order (".$data['purchase_number'].")";

        
        if($this->input->post('approval_level')=='1')
        {
            $message = "Purchase Order (".$data['purchase_number'].") Approved";
            // $data['order_status'] = "Pending";
            $data['order_status'] = "Pending";
            $data['approval_flag'] = "1";
            $data['approved_by'] = $this->session->userdata('id');
            $data['approved_date'] = date('Y-m-d H:i:s');
            
        }
        // echo "<pre>"; print_r($data);
        // die();
        // if($amount_limit>=$order_total){
        //     $data["approval_flag"] = 1;
        //     $history['authorized_by'] = $this->session->userdata('id');
        //     $history['authorized_date'] = date("Y-m-d");
        //     $history['authorized_amount'] = $order_total;
        //     $history['authorized_type'] = "Own";
        //     $history['status'] = "Approve";
        // }
        //////////////////////////////////////////////////////////////////////////////////
        $purchase_number_exists = $this->purchase->check_quote_existornot($purchase_number);
        $authid = $this->purchase->check_approval_existornot($purchase_number);

        
        //erp2024 message sent
        $module_number = get_module_details_by_name('Stock');
        $users_list =  linked_user_module_approvals_by_module_number($module_number);


        if($purchase_number_exists > 0)
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


            $data['updated_by']   = $this->session->userdata('id');
            $data['updated_date']   = date('Y-m-d H:i:s');
            $this->db->update('cberp_purchase_orders', $data,['purchase_number'=>$purchase_number_exists]);      
           
            $purchase_number = $purchase_number_exists;
            $target_url = base_url("purchase/create?id=".$purchase_number_exists);
            $message_caption = "Purchase Order(".$data['purchase_number'].")";
            
            
            //erp2024 insert to authorization history table////////////////////////////////
            $history['function_type'] = 'Purchase Order';
            $history['function_id'] = $purchase_number;
            $history['requested_by'] = $this->session->userdata('id');
            $history['requested_date'] = date("Y-m-d");
            $history['requested_amount'] = $order_total;
            // if($authid>0){
            //     $this->db->update('authorization_history',$history,['function_id'=>$purchase_number,'function_type'=>'Purchase Order']);
            // }
            // else{
            //     $this->db->insert('authorization_history',$history);
            // }
            
            // history_table_log('cberp_purchase_order_logs','purchase_order_id',$invocieno,'Update');
            // erp2025 09-01-2025 starts
            if($this->input->post('approval_level')=='1')
            {
                detailed_log_history('Purchaseorder',$purchase_number,'Purchase Order Approved', $_POST['changedFields']);
                $message = "Purchase Order(".$data['purchase_number'].") Approved";
                send_message_to_users($users_list,$target_url,$message_caption,$message,datefordatabase($duedate));
            }
            else{
                detailed_log_history('Purchaseorder',$purchase_number,'Created','');
                send_message_to_users($users_list,$target_url,$message_caption,$message,datefordatabase($duedate));
            }
                
            // erp2025 09-01-2025 ends   
            
            //////////////////////////////////////////////////////////////////////////////
            // file upload section starts 22-01-2025
                if($_FILES['upfile'])
                {
                    upload_files($_FILES['upfile'], 'Purchaseorder',$purchase_number);
                }
            // file upload section ends 22-01-2025
            
            $pid = $this->input->post('pid');
            $productlist = array();
            $prodindex = 0;
            $itc = 0;
            $flag = false;
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
            $account_code = $this->input->post('expense_account_number');

            $total_discount = 0;

            $deleted_items = $this->input->post('deleted_item');
            $deleted_items_array = explode(",", $deleted_items);
            if($deleted_items_array)
            {
                $this->db->where('purchase_number', $purchase_number);
                $this->db->where_in('product_code', $deleted_items_array);
                $this->db->delete('cberp_purchase_order_items'); 
            }

            foreach ($pid as $key => $value) {
                if(!empty($product_id[$key]) && !empty($product_name1[$key]) && $product_qty[$key]>0)
                {
                    $total_discount += numberClean($product_discount[$key]);
                    $total_tax += numberClean($ptotal_tax[$key]);
                    $data = array(                    
                        'purchase_number' => $purchase_number,
                        'product_code' => $product_hsn[$key],
                        'quantity' => numberClean($product_qty[$key]),
                        'price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
                        // 'tax' => numberClean($product_tax[$key]),
                        'discount' => numberClean($product_discount[$key]),
                        'subtotal' => rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc),
                        'totaltax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                        'totaldiscount' => numberClean($product_discount[$key]),
                        'account_code' => $account_code[$key],
                        'unit' => $product_unit[$key]
                    );

                    $code = trim($product_hsn[$key]);     
                    $isChanged = !empty($changedSet) && isset($changedSet[$code]);
                    $isInWhole = !empty($wholeSet) && isset($wholeSet[$code]);   

                    if($isChanged && in_array($code, $product_hsn)) {
                        $this->db->update('cberp_purchase_order_items', $data, ['purchase_number'=>$purchase_number, 'product_code'=>$code]);
                    }
                    elseif (!$isInWhole && in_array($code, $product_hsn)) 
                    {
                        $this->db->insert('cberp_purchase_order_items', $data);
                    }
                    $existornot = $this->purchase->check_product_existornot($purchase_number,$product_hsn[$key]);
                    if($existornot==1)
                    {
                        $this->db->update('cberp_purchase_order_items', $data, ['purchase_number'=>$purchase_number, 'product_code'=>$product_hsn[$key]]);
                    }
                    else{
                        $this->db->insert('cberp_purchase_order_items', $data);
                    }

                    if($this->input->post('completed_status')=='1'){
                        $this->db->select('stock_quantity');
                        $this->db->from('cberp_product_to_store');
                        $this->db->where('store_id', $store_id);
                        $this->db->where('product_code', $product_hsn[$key]);
                        $query = $this->db->get();
                        // die($this->db->last_query());
                        if ($query->num_rows() > 0) {
                            $row = $query->row();
                            $current_qty = $row->stock_quantity;
                            $new_qty=0;
                            if ($current_qty != 0) {
                                $new_qty = intval($current_qty) + intval($product_qty[$key]);
                            } else {
                                $new_qty = intval($product_qty[$key]);
                            }
                            $data2 = array(
                                'stock_quantity' => $new_qty
                            );
                            $this->updateqty($store_id,intval($product_hsn[$key]),$data2);
                        
                        
                        }
                    }

                    
                    $flag = true;
                    $productlist[$prodindex] = $data;
                    $i++;
                    $prodindex++;
                    $amt = numberClean($product_qty[$key]);

                    if ($product_id[$key] > 0) {
                        if ($this->input->post('update_stock') == 'yes') {

                            $this->db->set('onhand_quantity', "onhand_quantity+$amt", FALSE);
                            $this->db->where('product_code', $product_hsn[$key]);
                            $this->db->update('cberp_products');
                        }
                        $itc += $amt;
                    }
                }

            }
            // echo "Ends"; die();
            if ($prodindex > 0) {
                $this->db->set(array('discount' => ($total_discount), 'tax' => rev_amountExchange_s(amountFormat_general($total_tax), $currency, $this->aauth->get_user()->loc)));
                $this->db->where('purchase_number', $purchase_number);
                $this->db->update('cberp_purchase_orders');                

            } else {
                echo json_encode(array('status' => 'Error', 'message' =>
                    "Please choose product from product list. Go to Item manager section if you have not added the products."));
                $transok = false;
            }
            
            echo json_encode(array('status' => 'Success', 'data' => $purchase_number));
            
            
        }
        else
        {
            $data['created_by']   = $this->session->userdata('id');
            $data['created_date']   = date('Y-m-d H:i:s');
            if ($this->db->insert('cberp_purchase_orders', $data)) {
                
                $invocieno = $this->db->insert_id();    
                
                $this->db->insert('cberp_transaction_tracking',['purchase_order_id'=>$invocieno,'purchase_order_number'=>$data['purchase_number']]);
              
                $target_url = base_url("purchase/create?id=".$purchase_number);
                $message_caption = "Purchase Order(".$data['purchase_number'].")";
                if($this->input->post('approval_level')=='1')
                {
                    $message = "Purchase Order(".$data['purchase_number'].") Approved";
                    send_message_to_users($users_list,$target_url,$message_caption,$message,datefordatabase($duedate));
                }
                else{
                    send_message_to_users($users_list,$target_url,$message_caption,$message,datefordatabase($duedate));
                }
                
                
                //erp2024 insert to authorization history table////////////////////////////////
                $history['function_type'] = 'Purchase Order';
                $history['function_id'] = $purchase_number;
                $history['requested_by'] = $this->session->userdata('id');
                $history['requested_date'] = date("Y-m-d");
                $history['requested_amount'] = $order_total;
                $this->db->insert('authorization_history',$history);
                // history_table_log('cberp_purchase_order_logs','purchase_order_id',$invocieno,'Create');
                // erp2025 09-01-2025 starts	
                if($this->input->post('approval_level')=='1')
                {
                    detailed_log_history('Purchaseorder',$purchase_number,'Purchase Order Approved', $_POST['changedFields']);
                }
                else{
                    detailed_log_history('Purchaseorder',$purchase_number,'Created', '');                    
                   
                }
                // erp2025 09-01-2025 ends   
                //////////////////////////////////////////////////////////////////////////////
                    // file upload section starts 22-01-2025
                    if($_FILES['upfile'])
                    {
                        upload_files($_FILES['upfile'], 'Purchaseorder',$purchase_number);
                    }
                // file upload section ends 22-01-2025

                $pid = $this->input->post('pid');
                $productlist = array();
                $prodindex = 0;
                $itc = 0;
                $flag = false;
                $product_id = $this->input->post('pid');
                $product_name1 = $this->input->post('product_name', true);
                $product_qty = $this->input->post('product_qty');
                $product_price = $this->input->post('product_price');
                $product_tax = $this->input->post('product_tax');
                $product_discount = $this->input->post('product_discount');
                $product_subtotal = $this->input->post('product_subtotal');
                $ptotal_tax = $this->input->post('taxa');
                $ptotal_disc = $this->input->post('disca');
                // $product_des = $this->input->post('product_description', true);
                $product_unit = $this->input->post('unit');
                $product_hsn = $this->input->post('hsn');
                $account_code = $this->input->post('expense_account_number');

                $total_discount=0;
                foreach ($pid as $key => $value) {
                    if(!empty($product_id[$key]) && !empty($product_name1[$key]) && $product_qty[$key]>0)
                    {
                        $total_discount += $product_discount[$key];
                        $total_tax += numberClean($ptotal_tax[$key]);
        
                        $data = array(
                            'purchase_number' => $purchase_number,
                            'product_code' => $product_hsn[$key],
                            'quantity' => numberClean($product_qty[$key]),
                            'price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
                            'tax' => numberClean($product_tax[$key]),
                            'subtotal' => rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc),
                            'discount' => numberClean($product_discount[$key]),                            
                            'totaltax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                            'totaldiscount' => numberClean($product_discount[$key]),
                            'account_code' => $account_code[$key],
                            'unit' => $product_unit[$key],
                            
                        );
        
                        

                        if($this->input->post('completed_status')=='1'){
                            $this->db->select('stock_quantity');
                            $this->db->from('cberp_product_to_store');
                            $this->db->where('store_id', $store_id);
                            $this->db->where('product_code', $product_hsn[$key]);
                            $query = $this->db->get();
                            // die($this->db->last_query());
                            if ($query->num_rows() > 0) {
                                $row = $query->row();
                                $current_qty = $row->stock_quantity;
                                $new_qty=0;
                                if ($current_qty != 0) {
                                    $new_qty = intval($current_qty) + intval($product_qty[$key]);
                                } else {
                                    $new_qty = intval($product_qty[$key]);
                                }
                                $data2 = array(
                                    'stock_quantity' => $new_qty
                                );
                                $this->updateqty($store_id,intval($product_hsn[$key]),$data2);
                            
                            
                            }
                        }
        
                        
                        $flag = true;
                        $productlist[$prodindex] = $data;
                        $i++;
                        $prodindex++;
                        $amt = numberClean($product_qty[$key]);
        
                        if ($product_id[$key] > 0) {
                            if ($this->input->post('update_stock') == 'yes') {
        
                                $this->db->set('onhand_quantity', "onhand_quantity+$amt", FALSE);
                                $this->db->where('product_code', $product_hsn[$key]);
                                $this->db->update('cberp_products');
                            }
                            $itc += $amt;
                        }
                    }

                }
                //erp2024 send message to users
                // $target_url = base_url("purchase/create?id=$invocieno");
                // send_message_to_users($users_list,$target_url);
                if ($prodindex > 0) {
                    $this->db->insert_batch('cberp_purchase_order_items', $productlist);
                 
                    $this->db->set(array('discount' => $total_discount, 'tax' => rev_amountExchange_s(amountFormat_general($total_tax), $currency, $this->aauth->get_user()->loc)));
                    $this->db->where('purchase_number', $purchase_number);
                    $this->db->update('cberp_purchase_orders');

                } else {
                    echo json_encode(array('status' => 'Error', 'message' =>
                        "Please choose product from product list. Go to Item manager section if you have not added the products."));
                    $transok = false;
                }
                echo json_encode(array('status' => 'Success', 'data' => $purchase_number));

                // echo json_encode(array('status' => 'Success', 'message' => $this->lang->line('Purchase order success') . "<a href='view?id=$invocieno' class='btn btn-secondary btn-sm' title='View'><span class='fa fa-eye' aria-hidden='true'></span>" . $this->lang->line('View') . " </a>")); cberp_transaction_tracking
            }
        }

        
        if ($transok) {
            $this->db->trans_complete();
        } else {
            $this->db->trans_rollback();
        }



    }

    //erp2024 01-10-2024 starts
    public function draftaction()
    {
    //    ini_set('display_errors', 1);
    //    ini_set('display_startup_errors', 1);
    //    error_reporting(E_ALL);
       
        // echo "<pre>";
        // echo "Post Array : ";
        // print_r($producthsn);

        // echo "<br>\nWhole Array : ";
        // // print_r($wholeProducts);
        // print_r($wholeSet);

        //  echo "<br>\nChanged Array : ";
        // // print_r($changedSet);
        // print_r($changedSet);
        // die();
        $currency = $this->input->post('mcurrency');
        $customer_id = $this->input->post('customer_id');
        $purchase_type = $this->input->post('doc_type');
        $currency_id = $this->input->post('currency_id');
        $invocieno = $this->input->post('invocieno');
        $purchase_order_date = $this->input->post('invoicedate');
        $invocieduedate = $this->input->post('invocieduedate');
        $store_id = $this->input->post('store_id');
        $notes = $this->input->post('notes', true);
        $tax = $this->input->post('tax_handle');
        $subtotal = rev_amountExchange_s($this->input->post('subtotal'), $currency, $this->aauth->get_user()->loc);
        $shipping = rev_amountExchange_s($this->input->post('shipping'), $currency, $this->aauth->get_user()->loc);
        $shipping_tax = rev_amountExchange_s($this->input->post('ship_tax'), $currency, $this->aauth->get_user()->loc);
        $ship_taxtype = $this->input->post('ship_taxtype');
        if ($ship_taxtype == 'incl') @$shipping = $shipping - $shipping_tax;
        $internal_reference = $this->input->post('refer', true);
        $total = rev_amountExchange_s($this->input->post('total'), $currency, $this->aauth->get_user()->loc);
        $total_tax = 0;
        $total_discount = 0;
        $discountFormat = $this->input->post('discountFormat');
        $pterms = $this->input->post('pterms');
        $purchase_number = $this->input->post('purchase_number');
        $i = 0;
        if ($discountFormat == '0') {
            $discstatus = 0;
        } else {
            $discstatus = 1;
        }
        $this->db->trans_start();
        //products
        $transok = true;
        //Invoice Data
        $bill_date = datefordatabase($purchase_order_date);
        $bill_due_date = datefordatabase($invocieduedate);
        $data = array('purchase_order_date' => $bill_date, 'duedate' => $bill_due_date, 'shipping_charge' => $shipping, 'shipping_tax' => $shipping_tax, 'shipping_tax_type' => $ship_taxtype, 'order_total' => $total, 'notes' => $notes, 'customer_id' => $customer_id, 'employee_id' => $this->aauth->get_user()->id, 'tax_status' => $tax, 'discstatus' => $discstatus, 'format_discount' => $discountFormat, 'internal_reference' => $internal_reference, 'payment_terms' => $pterms, 'loc' => $this->aauth->get_user()->loc, 'multi' => $currency, 'currency_id'=>$currency_id, 'purchase_type'=>$purchase_type, 'store_id'=>$store_id,'customer_reference' => $this->input->post('customer_reference_number'),'customer_contact_person' => $this->input->post('customer_contact_person'),'customer_contact_number' =>  $this->input->post('customer_contact_number'),'customer_contact_email' => $this->input->post('customer_contact_email'),'assigned_to'=>$this->input->post('employee'));
        $this->session->unset_userdata('selectedValues');

        $data['purchase_number'] = $this->input->post('purchase_number');
        //erp2024 find amout limit 19-07-2024 /////////////////////////////////////////
        $amount_limit = $this->authorizationapproval->amount_limit($this->session->userdata('id'));
        $history =[];
        $data["order_status"] = 'Draft';       

        //////////////////////////////////////////////////////////////////////////////////
        $qtid = $this->purchase->check_quote_existornot($data['purchase_number']);        
        // $this->db->delete('cberp_purchase_order_items', array('tid' => $qtid)); 
        if($qtid > 0)
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

            $purchase_number = $qtid;
            $data['updated_by']   = $this->session->userdata('id');
            $data['updated_date']   = date('Y-m-d H:i:s');
            $this->db->update('cberp_purchase_orders', $data,['purchase_number'=>$purchase_number]);  


            $deleted_items = $this->input->post('deleted_item');
            $deleted_items_array = explode(",", $deleted_items);
            if($deleted_items_array)
            {
                $this->db->where('purchase_number', $purchase_number);
                $this->db->where_in('product_code', $deleted_items_array);
                $this->db->delete('cberp_purchase_order_items'); 
            }


            // history_table_log('cberp_purchase_order_logs','purchase_order_id',$qtid,'Update');    
            // erp2025 09-01-2025 starts
            detailed_log_history('Purchaseorder',$purchase_number,'Draft Update', $_POST['changedFields']);	
           // erp2025 09-01-2025 ends   
            // file upload section starts 22-01-2025
            if($_FILES['upfile'])
            {
                upload_files($_FILES['upfile'], 'Purchaseorder',$purchase_number);
            }
            // file upload section ends 22-01-2025
            $invocieno = $qtid;
            $pid = $this->input->post('pid');
            $productlist = array();
            $prodindex = 0;
            $itc = 0;
            $flag = false;
            $product_id = $this->input->post('pid');
            $product_name1 = $this->input->post('product_name');
            $product_qty = $this->input->post('product_qty');
            $product_price = $this->input->post('product_price');
            $product_tax = $this->input->post('product_tax');
            $product_discount = $this->input->post('product_discount');
            $product_subtotal = $this->input->post('product_subtotal');
            $ptotal_tax = $this->input->post('taxa');
            $ptotal_disc = $this->input->post('disca');
            // $product_des = $this->input->post('product_description', true);
            $product_unit = $this->input->post('unit');
            $product_hsn = $this->input->post('hsn');
            $account_code = $this->input->post('expense_account_number');

                foreach ($pid as $key => $value) 
                {
                    if(!empty($product_id[$key]) && !empty($product_name1[$key]) && $product_qty[$key]>0)
                    {
                        $total_discount += numberClean(@$ptotal_disc[$key]);
                        $total_tax += numberClean($ptotal_tax[$key]);
                        $data = array(
                            'product_code' => $product_hsn[$key],
                            'quantity' => numberClean($product_qty[$key]),
                            'price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
                            'discount' => numberClean($product_discount[$key]),
                            'subtotal' => rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc),
                            'totaltax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                            'totaldiscount' => numberClean($product_discount[$key]),
                            'account_code' => $account_code[$key],
                            'unit' => $product_unit[$key],
                            'purchase_number' => $purchase_number
                        );

                        $code = trim($product_hsn[$key]);
                        $isChanged = !empty($changedSet) && isset($changedSet[$code]);
                        $isInWhole = !empty($wholeSet) && isset($wholeSet[$code]);  
                    
                        if($isChanged && in_array($code, $product_hsn)) {
                            $this->db->update('cberp_purchase_order_items', $data, ['purchase_number'=>$purchase_number, 'product_code'=>$code]);
                        }
                        elseif (!$isInWhole && in_array($code, $product_hsn)) 
                        {
                            $this->db->insert('cberp_purchase_order_items', $data);
                        }
                        
                        $flag = true;
                        $productlist[$prodindex] = $data;
                        $i++;
                        $prodindex++;
                        $amt = numberClean($product_qty[$key]);

                        if ($product_id[$key] > 0) {
                            // if ($this->input->post('update_stock') == 'yes' AND $this->aauth->premission(14)) {

                            //     $this->db->set('qty', "qty+$amt", FALSE);
                            //     $this->db->where('pid', $product_id[$key]);
                            //     $this->db->update('cberp_products');
                            // }
                            $itc += $amt;
                        }

                    }
                }
            // echo "Ends"; die();
            if ($prodindex > 0) {
                $this->db->set(array('discount' => rev_amountExchange_s(amountFormat_general($total_discount), $currency, $this->aauth->get_user()->loc), 'tax' => rev_amountExchange_s(amountFormat_general($total_tax), $currency, $this->aauth->get_user()->loc)));
                $this->db->where('purchase_number', $purchase_number);
                $this->db->update('cberp_purchase_orders');

            } 
            

            // echo json_encode(array('status' => 'Success', 'message' => $this->lang->line('Purchase order success') . "<a href='view?id=$invocieno' class='btn btn-secondary btn-sm' title='View'><span class='fa fa-eye' aria-hidden='true'></span>" . $this->lang->line('View') . " </a>"));
        } 
        else {
            $data['created_by']   = $this->session->userdata('id');
            $data['created_date']   = date('Y-m-d H:i:s');
            $this->db->insert('cberp_purchase_orders', $data);
            $invocieno = $this->db->insert_id();   
            // history_table_log('cberp_purchase_order_logs','purchase_order_id',$purchase_number,'Draft');  
            // erp2025 09-01-2025 starts
            detailed_log_history('Purchaseorder',$purchase_number,'Save as Draft', $changedFields);	
           // erp2025 09-01-2025 ends   
           // file upload section starts 22-01-2025
           if($_FILES['upfile'])
           {
               upload_files($_FILES['upfile'], 'Purchaseorder',$purchase_number);
           }
        // file upload section ends 22-01-2025
            $pid = $this->input->post('pid');
            $productlist = array();
            $prodindex = 0;
            $itc = 0;
            $flag = false;
            $product_id = $this->input->post('pid');
            $product_name1 = $this->input->post('product_name');
            $product_qty = $this->input->post('product_qty');
            $product_price = $this->input->post('product_price');
            $product_tax = $this->input->post('product_tax');
            $product_discount = $this->input->post('product_discount');
            $product_subtotal = $this->input->post('product_subtotal');
            $ptotal_tax = $this->input->post('taxa');
            $ptotal_disc = $this->input->post('disca');
            // $product_des = $this->input->post('product_description', true);
            $product_unit = $this->input->post('unit');
            $product_hsn = $this->input->post('hsn');
            $account_code = $this->input->post('expense_account_number');

           
            foreach ($pid as $key => $value) {
                if(!empty($product_id[$key]) && !empty($product_name1[$key]) && $product_qty[$key]>0)
                {
                    $total_discount += numberClean(@$ptotal_disc[$key]);
                    $total_tax += numberClean($ptotal_tax[$key]);

                    

                    $data = array(
                        'product_code' => $product_hsn[$key],
                        'quantity' => numberClean($product_qty[$key]),
                        'price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
                        'tax' => numberClean($product_tax[$key]),
                        'discount' => numberClean($product_discount[$key]),
                        'subtotal' => rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc),
                        'totaltax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                        'totaldiscount' => numberClean($product_discount[$key]),
                        // 'totaldiscount' => rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc),
                        'account_code' => $account_code[$key],
                        'unit' => $product_unit[$key],
                        'purchase_number' => $purchase_number
                    );

                    
                    $flag = true;
                    $productlist[$prodindex] = $data;
                    $i++;
                    $prodindex++;
                    $amt = numberClean($product_qty[$key]);

                    if ($product_id[$key] > 0) {
                        // if ($this->input->post('update_stock') == 'yes' AND $this->aauth->premission(14)) {

                        //     $this->db->set('qty', "qty+$amt", FALSE);
                        //     $this->db->where('pid', $product_id[$key]);
                        //     $this->db->update('cberp_products');
                        // }
                        $itc += $amt;
                    }
                }

            }
            // echo "Ends"; die();
            if ($prodindex > 0) {
                $this->db->insert_batch('cberp_purchase_order_items', $productlist);
                $this->db->set(array('discount' => rev_amountExchange_s(amountFormat_general($total_discount), $currency, $this->aauth->get_user()->loc), 'tax' => rev_amountExchange_s(amountFormat_general($total_tax), $currency, $this->aauth->get_user()->loc)));
                $this->db->where('purchase_number', $purchase_number);
                $this->db->update('cberp_purchase_orders');

            } 
        }

        echo json_encode(array('status' => 'Success', 'data' => $purchase_number));
        if ($transok) {
            $this->db->trans_complete();
        } else {
            $this->db->trans_rollback();
        }



    }

    public function approval_action()
    {

        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
        $changedProducts = [];
        $wholeProducts = [];            
        if (!empty($this->input->post('changedProducts'))) {
            $changedProducts = json_decode($this->input->post('changedProducts'), true);
        }            
        if (!empty($this->input->post('wholeProducts'))) {
            $wholeProducts = json_decode($this->input->post('wholeProducts'), true);
        }            
        $changedSet = array_flip($changedProducts);
        $wholeSet = array_flip($wholeProducts);
        // echo "<pre>";
        // // echo "Post Array : ";
        // // print_r($producthsn);

        // echo "<br>\nWhole Array : ";
        // // print_r($wholeProducts);
        // print_r($wholeSet);

        //  echo "<br>\nChanged Array : ";
        // // print_r($changedSet);
        // print_r($changedSet);
        // die();
        
        $currency = $this->input->post('mcurrency');
        $customer_id = $this->input->post('customer_id');
        $purchase_type = $this->input->post('doc_type');
        $currency_id = $this->input->post('currency_id');
        $invocieno = $this->input->post('invocieno');
        $invoicedate = $this->input->post('invoicedate');
        $invocieduedate = $this->input->post('invocieduedate');
        $store_id = $this->input->post('store_id');
        $notes = $this->input->post('notes', true);
        $tax = $this->input->post('tax_handle');
        $subtotal = rev_amountExchange_s($this->input->post('total'), $currency, $this->aauth->get_user()->loc);
        $shipping = rev_amountExchange_s($this->input->post('shipping'), $currency, $this->aauth->get_user()->loc);
        $shipping_tax = rev_amountExchange_s($this->input->post('ship_tax'), $currency, $this->aauth->get_user()->loc);
        $ship_taxtype = $this->input->post('ship_taxtype');
        if ($ship_taxtype == 'incl') @$shipping = $shipping - $shipping_tax;
        $internal_reference = $this->input->post('refer', true);
        $total = rev_amountExchange_s($this->input->post('total'), $currency, $this->aauth->get_user()->loc);
        $total_tax = 0;
        $total_discount = 0;
        $discountFormat = $this->input->post('discountFormat');
        $pterms = $this->input->post('pterms');
        $purchase_number = $this->input->post('purchase_number');

       
        // $this->db->delete('cberp_purchase_order_items', $deleted_items_array); 

        $i = 0;
        if ($discountFormat == '0') {
            $discstatus = 0;
        } else {
            $discstatus = 1;
        }
        if($this->input->post('completed_status')=='1')
        {
            if ($customer_id == 0) {
                echo json_encode(array('status' => 'Error', 'message' =>
                    "Please add a new supplier or search from a previous added!"));
                exit;
            }
            if (empty($purchase_type)) {
                echo json_encode(array('status' => 'Error', 'message' =>
                    "Please select a Purchase Type"));
                exit;
            }
            if (empty($currency_id)) {
                echo json_encode(array('status' => 'Error', 'message' =>
                    "Please select a currency"));
                exit;
            }
        }

       
        $this->db->trans_start();
        //products
        $transok = true;
        //Invoice Data
        $bill_date = datefordatabase($invoicedate);
        $bill_due_date = datefordatabase($invocieduedate);
        $data = array('purchase_order_date' => $bill_date, 'duedate' => $bill_due_date, 'shipping_charge' => $shipping, 'shipping_tax' => $shipping_tax, 'shipping_tax_type' => $ship_taxtype, 'order_total' => $total, 'notes' => $notes, 'customer_id' => $customer_id, 'employee_id' => $this->aauth->get_user()->id, 'tax_status' => $tax, 'discstatus' => $discstatus, 'format_discount' => $discountFormat, 'internal_reference' => $internal_reference, 'payment_terms' => $pterms, 'loc' => $this->aauth->get_user()->loc, 'multi' => $currency, 'currency_id'=>$currency_id, 'purchase_type'=>$purchase_type, 'store_id'=>$store_id,'customer_reference' => $this->input->post('customer_reference_number'),'customer_contact_person' => $this->input->post('customer_contact_person'),'customer_contact_number' =>  $this->input->post('customer_contact_number'),'customer_contact_email' => $this->input->post('customer_contact_email'),'approved_by'=>$this->session->userdata('id'),'approved_date'=>date('Y-m-d H:i:s'),'assigned_to'=>$this->input->post('employee'));
        $this->session->unset_userdata('selectedValues');
       
        //erp2024 find amout limit 19-07-2024 /////////////////////////////////////////
        $data['purchase_number'] = $this->input->post('purchase_number');
        $amount_limit = $this->authorizationapproval->amount_limit($this->session->userdata('id'));
        $history =[];
        // $data['order_status'] = "Assigned";
        // $data["approval_flag"] = '1';     
        $data['updated_by']   = $this->session->userdata('id');
        $data['updated_date']   = date('Y-m-d H:i:s');
        //////////////////////////////////////////////////////////////////////////////////
        $purchase_number_exsits = $this->purchase->check_quote_existornot($purchase_number);
        // $purchase_number = $purchase_number_exsits;
        
        // if($this->input->post('approval_level')=='1')
        // {
        //     $data['order_status'] = "Approved";
        //     $module_number = get_module_details_by_name('Stock');
        //     $users_list =  linked_user_module_approvals_by_module_number($module_number);
        //     $target_url = base_url("purchase/create?id=".$purchase_number);
        //     $message_caption = "Purchase Order(".$data['purchase_number'].")";
        //     $message = "Purchase Order(".$data['purchase_number'].") Approved";
        //     send_message_to_users($users_list,$target_url,$message_caption,$message,$invocieduedate);
        // }

        // $authid = $this->purchase->check_approval_existornot($invocieno);
        $this->db->update('cberp_purchase_orders', $data,['purchase_number'=>$purchase_number]); 
        // $this->db->delete('cberp_purchase_order_items', $deleted_items_array);        

       
        $deleted_items = $this->input->post('deleted_item');
        $deleted_items_array = explode(",", $deleted_items);
        if($deleted_items_array)
        {
            $this->db->where('purchase_number', $purchase_number);
            $this->db->where_in('product_code', $deleted_items_array);
            $this->db->delete('cberp_purchase_order_items'); 
        }
        // $this->db->delete('cberp_purchase_order_items', array('purchase_number' => $purchase_number));        
        // history_table_log('cberp_purchase_order_logs','purchase_order_id',$purchase_number,'Assigned To');  
        // erp2025 09-01-2025 starts
          detailed_log_history('Purchaseorder',$purchase_number,'Updated', $_POST['changedFields']);	
        // erp2025 09-01-2025 ends   
        // die($this->db->last_query());
        
        //erp2024 insert to authorization history table////////////////////////////////
            // file upload section starts 22-01-2025
            if($_FILES['upfile'])
            {
                upload_files($_FILES['upfile'], 'Purchaseorder',$purchase_number);
            }
            // file upload section ends 22-01-2025

        $authdata = [
        'authorized_amount' => $subtotal,
        'status' => 'Approve',
        'authorized_date' => date("Y-m-d H:i:s"),
        'authorized_by' => $this->session->userdata('id'),
        'authorized_type' => 'Reported Person',
        ];
    
        $this->db->update('authorization_history',$authdata,['function_id'=>$purchase_number,'function_type'=>'Purchase Order']);
        //////////////////////////////////////////////////////////////////////////////

        $pid = $this->input->post('pid');
        $productlist = array();
        $prodindex = 0;
        $itc = 0;
        $flag = false;
        $product_id = $this->input->post('pid');
        $product_name1 = $this->input->post('product_name', true);
        $product_qty = $this->input->post('product_qty');
        $product_price = $this->input->post('product_price');
        $product_tax = $this->input->post('product_tax');
        $product_discount = $this->input->post('product_discount');
        $product_subtotal = $this->input->post('product_subtotal');
        $ptotal_tax = $this->input->post('taxa');
        $ptotal_disc = $this->input->post('disca');
        // $product_des = $this->input->post('product_description', true);
        $product_unit = $this->input->post('unit');
        $product_hsn = $this->input->post('hsn');
        $account_code = $this->input->post('expense_account_number');

        $k=1;
        $l=1;
        foreach ($pid as $key => $value) {
            if(!empty($product_id[$key]) && !empty($product_name1[$key]) && $product_qty[$key]>0)
            {
                $total_discount += numberClean(@$ptotal_disc[$key]);
                $total_tax += numberClean($ptotal_tax[$key]);

                

                $data = array(
                    'product_code' => $product_hsn[$key],
                    'quantity' => numberClean($product_qty[$key]),
                    'price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
                    // 'tax' => numberClean($product_tax[$key]),
                    'discount' => numberClean($product_discount[$key]),
                    'subtotal' => rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc),
                    'totaltax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                    'totaldiscount' => rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc),
                    'account_code' => $account_code[$key],
                    'unit' => $product_unit[$key],
                    'purchase_number' => $purchase_number,
                );
                $code = trim($product_hsn[$key]);
                $isChanged = isset($changedSet[$code]);
                $isInWhole = isset($wholeSet[$code]);  
             
                if($isChanged && in_array($code, $product_hsn)) {
                    $this->db->update('cberp_purchase_order_items', $data, ['purchase_number'=>$purchase_number, 'product_code'=>$code]);
                    // echo $this->db->last_query()."\n";
                }
                elseif (!$isInWhole && in_array($code, $product_hsn)) 
                {
                    $this->db->insert('cberp_purchase_order_items', $data);
                    // echo $this->db->last_query()."\n";
                }

                // $existornot = $this->purchase->check_product_existornot($invocieno,$product_id[$key]);
                // if($existornot==1)
                // {
                //     $this->db->update('cberp_purchase_order_items', $data, ['purchase_number'=>$purchase_number, 'product_code'=>$product_hsn[$key]]);
                // }
                // else{
                //     $this->db->insert('cberp_purchase_order_items', $data);
                // }

                //commented on 18-04-2025
                // $this->db->insert('cberp_purchase_order_items', $data);
                // if($this->input->post('completed_status')=='1'){
                //     $this->db->select('stock_quantity');
                //     $this->db->from('cberp_product_to_store');
                //     $this->db->where('store_id', $store_id);
                //     $this->db->where('product_id', $product_id[$key]);
                //     $query = $this->db->get();
                //     // die($this->db->last_query());
                //     if ($query->num_rows() > 0) {
                //         $row = $query->row();
                //         $current_qty = $row->stock_quantity;
                //         $new_qty=0;
                //         if ($current_qty != 0) {
                //             $new_qty = intval($current_qty) + intval($product_qty[$key]);
                //         } else {
                //             $new_qty = intval($product_qty[$key]);
                //         }
                //         $data2 = array(
                //             'stock_quantity' => $new_qty
                //         );
                //         $this->updateqty($store_id,intval($product_id[$key]),$data2);
                    
                    
                //     }
                // }
            }

            
            $flag = true;
            $productlist[$prodindex] = $data;
            $i++;
            $prodindex++;
            $amt = numberClean($product_qty[$key]);

            if ($product_id[$key] > 0) {
                if ($this->input->post('update_stock') == 'yes') {

                    $this->db->set('qty', "qty+$amt", FALSE);
                    $this->db->where('pid', $product_id[$key]);
                    $this->db->update('cberp_products');
                }
                $itc += $amt;
            }

        }

        // die(12);
        // echo "Ends"; die();
        if ($prodindex > 0) {
            
            $this->db->set(array('discount' => rev_amountExchange_s(amountFormat_general($total_discount), $currency, $this->aauth->get_user()->loc), 'tax' => rev_amountExchange_s(amountFormat_general($total_tax), $currency, $this->aauth->get_user()->loc)));
            $this->db->where('purchase_number', $purchase_number);
            $this->db->update('cberp_purchase_orders');

        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
                "Somthing went wrong."));
            $transok = false;
        }

        echo json_encode(array('status' => 'Success', 'data' => $purchase_number));

        if ($transok) {
            $this->db->trans_complete();
        } else {
            $this->db->trans_rollback();
        }



    }
    //erp2024 01-10-2024 end
    public function updateqty($store_id,$product_code,$data2){
        $this->db->where('store_id', $store_id);                    
        $this->db->where('product_code', $product_code);
        $this->db->update('cberp_product_to_store', $data2);
        // die($this->db->last_query());
    }
    public function ajax_list()
    {

        $list = $this->purchase->get_datatables();
        
        $data = array();

        $no = $this->input->post('start');
        // echo ""; print_r($list); die();
        foreach ($list as $invoices) {
            // if($invoices->approval_flag=='1'){
            //     $approvstatus = '<span class="st-Closed">' . $this->lang->line('Approved') . '</span>';
            // }
            // else if($invoices->approval_flag=='2'){
            //     $approvstatus = '<span class="st-accepted">' . $this->lang->line('Hold') . '</span>';
            // }
            // else if($invoices->approval_flag=='3'){
            //     $approvstatus = '<span class="st-Closed">' . $this->lang->line(ucwords('Reject')) . '</span>';
            // }
            // else{
            //     $approvstatus = '<span class="st-pending">' . $this->lang->line('Waiting for approval') . '</span>';
            // }
            $approvstatus = "";
            $actionbtn = "";
            
            switch (true) {
                case ($invoices->order_status == "Dummy"):
                    $actionbtn ="";
                    break;
                case ($invoices->order_status == "Pending" && $invoices->approval_flag == "0"):
                    $actionbtn = '<a href="' . base_url("purchase/create?id=$invoices->purchase_number") . '" title="Approve Now" class="btn btn-sm btn-secondary">Approve Now</a>';
                    $approvstatus = '<span class="st-pending">' . $this->lang->line('Waiting for approval') . '</span>';
                    break;
             // && $invoice['approved_by'] != $this->session->userdata('id')
                case ($invoices->approval_flag == "1" && $invoices->order_status == "Assigned" && $invoices->assign_to == $this->session->userdata('id')):
                    $approvstatus = '<span class="st-approved">' . $this->lang->line('Approved') . '</span>';
                    // $approvstatus = '<span class="st-Closed">' . $this->lang->line('Assigned') . '</span>';
                    $actionbtn = '<a href="' . base_url("purchase/create?id=$invoices->purchase_number") . '" title="Accept & Send" class="btn btn-sm btn-secondary">Accept Send</a>';
                    break;
               

                case ($invoices->approval_flag == "1" && $invoices->order_status != "Reverted" && $invoices->order_status != "Sent"):
                    $approvstatus = '<span class="st-approved">' . $this->lang->line('Approved') . '</span>';
                    // $approvstatus = '<span class="st-Closed">' . $this->lang->line('Assigned') . '</span>';
                    $actionbtn = '<a href="' . base_url("purchase/create?id=$invoices->purchase_number") . '" title="Approve Now" class="btn btn-sm btn-secondary"><i class="fa fa-edit"></i></a>&nbsp;<a href="' . base_url("purchase/create?id=$invoices->purchase_number") . '" title="Approve Now" class="btn btn-sm btn-secondary">Send Purchase Order</a>';
                    break;
               

                case ($invoices->order_status == "Reverted"):
                    $approvstatus = '<span class="st-approved">' . $this->lang->line('Approved') . '</span>';
                    // $approvstatus = '<span class="st-Closed">' . $this->lang->line('Assigned') . '</span>';
                    $actionbtn = '<a href="' . base_url("purchase/create?id=$invoices->purchase_number") . '" title="Approve Now" class="btn btn-sm btn-secondary"><i class="fa fa-edit"></i></a>&nbsp;<a href="' . base_url("purchase/create?id=$invoices->purchase_number") . '" title="Approve Now" class="btn btn-sm btn-secondary">Ready To Send</a>';
                    break;
                case ($invoices->order_status == "Sent"):
                    $validtoken = hash_hmac('ripemd160', 'p' . $invoices->id, $this->config->item('encryption_key'));
                    $approvstatus = '<span class="st-approved">' . $this->lang->line('Approved') . '</span>';
                    $actionbtn = '<a href="' . base_url("purchase/create?id=$invoices->purchase_number") . '" title="Approve Now" class="btn btn-sm btn-secondary"><i class="fa fa-edit"></i></a>&nbsp;<a href="' . base_url("Invoices/costing?pid=$invoices->purchase_number&token=$validtoken") . '" title="Purchase Receipt" class="btn btn-sm btn-secondary">Purchase Receipt</a>';
                    break;

                
               
                default:
                    // Handle any other cases here if needed
                    break;
            }
            $order_status = ($invoices->order_status=='Pending') ? "Created" : $invoices->order_status;
            $order_status = '<span class="st-'.strtolower($invoices->order_status).'">'.$order_status.'</span>';
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = '<a href="' . base_url("purchase/create?id=$invoices->purchase_number") . '" title="View">'.$invoices->purchase_number.'</a>';            
            $row[] = $invoices->name;

            $colorcode = get_color_code($invoices->duedate);
            $dudate = (!empty($invoices->duedate))?dateformat($invoices->duedate):"";
            $row[] = '<b style="color:'.$colorcode.'">'.$dudate.'</b>';

            // $row[] = dateformat($invoices->invoicedate);
            $row[] = number_format($invoices->order_total,2);
            // $row[] = '<span class="st-' . $invoices->status . '">' . $this->lang->line(ucwords($invoices->status)) . '</span>';
            
            // $row[] = $invoices->assigned_person;
            $assigned_date = (!empty($invoices->approved_date) && $invoices->approved_date!='0000-00-00 00:00:00') ? date('d-m-Y H:i:s', strtotime($invoices->approved_date)) : "";
            $row[] = $assigned_date;
            $row[] = $approvstatus;
            $row[] = $order_status;
            $receipt_status = "";
            if($invoices->receipt_status=="1") {
                 $receipt_status =  '<span class="st-received">' . $this->lang->line(ucwords("Fully Received")) . '</span>'; 
                 $actionbtn ="";
            } 
            else if($invoices->receipt_status=="2"){ $receipt_status =  '<span class="st-partial">' . $this->lang->line(ucwords("Partial")) . '</span>'; }
            $row[] = $receipt_status;
            
            $validtoken = hash_hmac('ripemd160', 'p' . $invoices->id, $this->config->item('encryption_key'));
            $link = base_url('billing/printorder?id=' . $invoices->id . '&token=' . $validtoken);
            
            // $row[] = '<a href="' . base_url("purchase/view?id=$invoices->id") . '" class="btn btn-sm btn-success"><i class="fa fa-eye"></i></a> <a href="#" data-object-id="' . $invoices->id . '" class="btn btn-danger btn-sm delete-object"><span class="fa fa-trash"></span></a>';
            
            // $row[] = '<a href="' . $link .'" class="btn btn-sm btn-secondary"  title="Print" target="_blank"><span class="fa fa-print"></span></a> <a href="#" data-object-id="' . $invoices->id . '" class="btn btn-secondary btn-sm delete-object" title="Delete"><span class="fa fa-trash"></span></a>';

            // $row[] = '<a href="' . $link .'" class="btn btn-sm btn-secondary"  title="Print" target="_blank"><span class="fa fa-print"></span></a>';
            $row[] = $actionbtn;
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->purchase->count_all(),
            "recordsFiltered" => $this->purchase->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);

    }

    public function view()
    {
        $this->load->model('accounts_model');
        $data['acclist'] = $this->accounts_model->accountslist((integer)$this->aauth->get_user()->loc);
        $tid = intval($this->input->get('id'));
        $data['id'] = $tid;
        $data['invoice'] = $invoices =  $this->purchase->purchase_details($tid);
        $head['title'] = "Purchase Order #".$invoices['tid'];    
        $data['approvedby'] = $this->authorizationapproval->approved_person($tid,"Purchase Order");
        $data['currency'] = $this->purchase->currency_d($invoices['currency_id']);
        $data['products'] = $this->purchase->purchase_products($tid);
        $data['activity'] = $this->purchase->purchase_transactions($tid);
        $data['attach'] = $this->purchase->attach($tid);
        $data['employee'] = $this->purchase->employee($data['invoice']['employee_id']);
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->view('fixed/header', $head);
        if ($data['invoice']['tid']) $this->load->view('purchase/view', $data);
        $this->load->view('fixed/footer');

    }


    public function printinvoice()
    {

        $tid = $this->input->get('id');

        $data['id'] = $tid;
        $data['title'] = "Purchase $tid";
        $data['invoice'] = $this->purchase->purchase_details($tid);
        $data['products'] = $this->purchase->purchase_products($tid);
        $data['employee'] = $this->purchase->employee($data['invoice']['employee_id']);
        $data['invoice']['multi'] = 0;

        $data['general'] = array('title' => $this->lang->line('Purchase Order'), 'person' => $this->lang->line('Supplier'), 'prefix' => prefix(2), 't_type' => 0);


        ini_set('memory_limit', '64M');

        if ($data['invoice']['tax_status'] == 'cgst' || $data['invoice']['tax_status'] == 'igst') {
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

        if ($this->input->get('d')) {

            $pdf->Output('Purchase_#' . $data['invoice']['tid'] . '.pdf', 'D');
        } else {
            $pdf->Output('Purchase_#' . $data['invoice']['tid'] . '.pdf', 'I');
        }


    }

    public function delete_i()
    {
        $id = $this->input->post('deletemployee_id');

        if ($this->purchase->purchase_delete($id)) {
            echo json_encode(array('status' => 'Success', 'message' =>
                "Purchase Order #$id has been deleted successfully!"));

        } else {

            echo json_encode(array('status' => 'Error', 'message' =>
                "There is an error! Purchase has not deleted."));
        }

    }

    public function editaction()
    {
        $currency = $this->input->post('mcurrency');
        $customer_id = $this->input->post('customer_id');
        $purchase_type = $this->input->post('doc_type');
        $currency_id = $this->input->post('currency_id');
        $invocieno = $this->input->post('iid');
        $purchase_order_date = $this->input->post('invoicedate');
        $invocieduedate = $this->input->post('invocieduedate');
        $notes = $this->input->post('notes', true);
        $tax = $this->input->post('tax_handle');
        $internal_reference = $this->input->post('refer', true);
        $total = rev_amountExchange_s($this->input->post('total'), $currency, $this->aauth->get_user()->loc);
        $total_tax = 0;
        $total_discount = 0;
        $discountFormat = $this->input->post('discountFormat');
        $pterms = $this->input->post('pterms');
        $ship_taxtype = $this->input->post('ship_taxtype');
        $subtotal = rev_amountExchange_s($this->input->post('subtotal'), $currency, $this->aauth->get_user()->loc);
        $shipping = rev_amountExchange_s($this->input->post('shipping'), $currency, $this->aauth->get_user()->loc);
        $shipping_tax = rev_amountExchange_s($this->input->post('ship_tax'), $currency, $this->aauth->get_user()->loc);
        if ($ship_taxtype == 'incl') $shipping = $shipping - $shipping_tax;

        $itc = 0;
        if ($discountFormat == '0') {
            $discstatus = 0;
        } else {
            $discstatus = 1;
        }

        if ($customer_id == 0) {
            echo json_encode(array('status' => 'Error', 'message' =>
                "Please add a new supplier or search from a previous added!"));
            exit();
        }
        if (empty($purchase_type)) {
            echo json_encode(array('status' => 'Error', 'message' =>
                "Please select a Purchase Type"));
            exit;
        }
        if (empty($currency_id)) {
            echo json_encode(array('status' => 'Error', 'message' =>
                "Please select a currency"));
            exit;
        }
         //erp2024 find amout limit 17-07-2024 ///////////////////////////////////////
         $oldtotal = rev_amountExchange_s($this->input->post('oldtotal'), $currency, $this->aauth->get_user()->loc);        
         $history =[];        
         $approval_flag = 0;
         if($oldtotal != $total)
         {
             $amount_limit = $this->authorizationapproval->amount_limit($this->session->userdata('id'));     
             $history['requested_date'] = date("Y-m-d");
             $history['requested_amount'] = $total;
             $history['function_type'] = 'Purchase Order';
             $history['function_id'] = $invocieno;
             if($amount_limit>=$total){
                 $approval_flag = 1;
                 $history['authorized_by'] = $this->session->userdata('id');
                 $history['authorized_date'] = date("Y-m-d");
                 $history['authorized_amount'] = $total;
                 $history['authorized_type'] = "Own";
                 $history['status'] = "Approve";
             }
             else{
                 $history['requested_by'] = $this->session->userdata('id');
             }
             $this->db->delete('authorization_history', array('function_id' => $invocieno, 'function_type'=>'Purchase Order')); 
             $this->db->insert('authorization_history',$history);
             
         }
         //////////////////////////////////////////////////////////////////////////////////////////////////////////
        $this->db->trans_start();
        $flag = false;
        $transok = true;


        //Product Data
        $pid = $this->input->post('pid');
        $productlist = array();

        $prodindex = 0;

        $this->db->delete('cberp_purchase_order_items', array('tid' => $invocieno));
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
        $account_code = $this->input->post('expense_account_number');

        foreach ($pid as $key => $value) {
            $total_discount += numberClean(@$ptotal_disc[$key]);
            $total_tax += numberClean($ptotal_tax[$key]);
            $data1 = array(
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
                'account_code' => $account_code[$key],
                'unit' => $product_unit[$key]
            );


            $productlist[$prodindex] = $data1;

            $prodindex++;
            $amt = numberClean($product_qty[$key]);
            $itc += $amt;

            if ($this->input->post('update_stock') == 'yes') {
                $amt = numberClean(@$product_qty[$key]) - numberClean(@$old_product_qty[$key]);
                $this->db->set('qty', "qty+$amt", FALSE);
                $this->db->where('pid', $product_id[$key]);
                $this->db->update('cberp_products');
            }
            $flag = true;
        }

       
        $bill_date = datefordatabase($invoicedate);
        $bill_due_date = datefordatabase($invocieduedate);
        $total_discount = rev_amountExchange_s(amountFormat_general($total_discount), $currency, $this->aauth->get_user()->loc);
        $total_tax = rev_amountExchange_s(amountFormat_general($total_tax), $currency, $this->aauth->get_user()->loc);
        
        
        $data = array('purchase_order_date' => $bill_date, 'duedate' => $bill_due_date, 'shipping_charge' => $shipping, 'shipping_tax' => $shipping_tax, 'shipping_tax_type' => $ship_taxtype, 'discount' => $total_discount, 'tax' => $total_tax, 'order_total' => $total, 'notes' => $notes, 'customer_id' => $customer_id, 'tax_status' => $tax, 'discstatus' => $discstatus, 'format_discount' => $discountFormat, 'internal_reference' => $internal_reference, 'payment_terms' => $pterms, 'multi' => $currency, 'purchase_type'=>$purchase_type, 'currency_id'=>$currency_id, 'approval_flag'=> $approval_flag);
        $this->db->set($data);
        $this->db->where('id', $invocieno);

        if ($flag) {

            if ($this->db->update('cberp_purchase_orders', $data)) {
                $this->db->insert_batch('cberp_purchase_order_items', $productlist);
                echo json_encode(array('status' => 'Success', 'message' =>
                    "Purchase order has  been updated successfully! <a href='view?id=$invocieno' class='btn btn-info btn-sm'><span class='fa fa-eye' aria-hidden='true'></span> View </a> "));
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

        if ($this->input->post('update_stock') == 'yes') {
            if ($this->input->post('restock')) {
                foreach ($this->input->post('restock') as $key => $value) {
                    $myArray = explode('-', $value);
                    $prid = $myArray[0];
                    $dqty = numberClean($myArray[1]);
                    if ($prid > 0) {

                        $this->db->set('qty', "qty-$dqty", FALSE);
                        $this->db->where('pid', $prid);
                        $this->db->update('cberp_products');
                    }
                }

            }
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
        $this->db->update('cberp_purchase_orders');

        echo json_encode(array('status' => 'Success', 'message' =>
            'Purchase Order Status updated successfully!', 'pstatus' => $status));
    }

    public function file_handling()
    {
        if ($this->input->get('op')) {
            $name = $this->input->get('name');
            $invoice = $this->input->get('invoice');
            if ($this->purchase->meta_delete($invoice, 4, $name)) {
                echo json_encode(array('status' => 'Success'));
            }
        } else {
            $id = $this->input->get('id');
            $this->load->library("Uploadhandler_generic", array(
                'accept_file_types' => '/\.(gif|jpe?g|png|docx|docs|txt|pdf|xls)$/i', 'upload_dir' => FCPATH . 'userfiles/attach/', 'upload_url' => base_url() . 'userfiles/attach/'
            ));
            $files = (string)$this->uploadhandler_generic->filenaam();
            if ($files != '') {

                $this->purchase->meta_insert($id, 4, $files);
            }
        }
    }
    public function selected_session_products(){
        $data = $this->session->userdata('selectedValues');
        echo json_encode($data);
    }
    public function selected_session_shipped_products(){
        $data = $this->session->userdata('selectedValues');
        $tid = $this->session->userdata('orderid');
        $activeData = [];
        foreach($data as $key => $pid){
            $this->db->select('id');
            $this->db->from('cberp_sales_orders_items');
            $this->db->where('pid', $pid);
            $this->db->where('tid', $tid);
            $this->db->where('status', 'delivered');
            $query = $this->db->get();
            $result = $query->row_array();
            if(!empty($result)){
                $activeData[] = $pid;
            }
        }
        echo json_encode($activeData);
    }

    // erp2024 19-07-2024
    public function purchase_approval()
    {

        $tid = $this->input->get('id');
        $data['id'] = $tid;
        $data['title'] = "Purchase Order $tid";
        $this->load->model('customers_model', 'customers');             
        $data['currencies'] = $this->purchase->currencies();
        $data['customergrouplist'] = $this->customers->group_list();
        $data['terms'] = $this->purchase->billingterms();   
        $data['invoice'] = $this->purchase->purchase_details($tid);
        $data['products'] = $this->purchase->purchase_products($tid);
        $head['title'] = "Edit Invoice #$tid";
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['warehouse'] = $this->purchase->warehouses();
        $data['currency'] = $this->purchase->currencies();
        $this->load->model('plugins_model', 'plugins');
        $data['exchange'] = $this->plugins->universal_api(5);
        $this->load->library("Common");
        $data['taxlist'] = $this->common->taxlist_edit($data['invoice']['tax_status']);        
        $data['authrizationdata'] = $this->authorizationapproval->authorization_details_byid($tid,'Purchase Order');
        $this->load->view('fixed/header', $head);
        $this->load->view('purchase/purchase_approval', $data);
        $this->load->view('fixed/footer');
    }

    
    //erp2024 02-10-2024
    public function approvalaction()
    {
        $currency = $this->input->post('mcurrency');
        $customer_id = $this->input->post('customer_id');
        $purchase_type = $this->input->post('doc_type');
        $currency_id = $this->input->post('currency_id');
        $invocieno = $this->input->post('po_id');
        // $invocieno = $this->input->post('iid');
        $purchase_order_date = $this->input->post('invoicedate');
        $invocieduedate = $this->input->post('invocieduedate');
        $notes = $this->input->post('notes', true);
        $tax = $this->input->post('tax_handle');
        $internal_reference = $this->input->post('refer', true);
        $total = rev_amountExchange_s($this->input->post('total'), $currency, $this->aauth->get_user()->loc);
        $total_tax = 0;
        $total_discount = 0;
        $discountFormat = $this->input->post('discountFormat');
        $pterms = $this->input->post('pterms');
        $ship_taxtype = $this->input->post('ship_taxtype');
        $subtotal = rev_amountExchange_s($this->input->post('subtotal'), $currency, $this->aauth->get_user()->loc);
        $shipping = rev_amountExchange_s($this->input->post('shipping'), $currency, $this->aauth->get_user()->loc);
        $shipping_tax = rev_amountExchange_s($this->input->post('ship_tax'), $currency, $this->aauth->get_user()->loc);
        if ($ship_taxtype == 'incl') $shipping = $shipping - $shipping_tax;

        $itc = 0;
        if ($discountFormat == '0') {
            $discstatus = 0;
        } else {
            $discstatus = 1;
        }

        if ($customer_id == 0) {
            echo json_encode(array('status' => 'Error', 'message' =>
                "Please add a new supplier or search from a previous added!"));
            exit();
        }
        if (empty($purchase_type)) {
            echo json_encode(array('status' => 'Error', 'message' =>
                "Please select a Payment Type"));
            exit;
        }
        if (empty($currency_id)) {
            echo json_encode(array('status' => 'Error', 'message' =>
                "Please select a currency"));
            exit;
        }
         //erp2024 find amout limit 17-07-2024 ///////////////////////////////////////
         $oldtotal = rev_amountExchange_s($this->input->post('oldtotal'), $currency, $this->aauth->get_user()->loc);        
         $authdata =[];     
         $authdata = [
            'authorized_amount' => $this->input->post('authorized_amount'),
            'status' => $this->input->post('statusType'),
            'comments' => $this->input->post('comments'),
            'authorized_amount' => $this->input->post('authorized_amount'),
            'authorized_date' => date("Y-m-d H:i:s"),
            'authorized_by' => $this->session->userdata('id'),
            'authorized_type' => 'Reported Person',
        ];

        $this->db->where('function_id',$this->input->post('iid'));
        $this->db->where('function_type','Purchase Order');
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
        $this->db->update('cberp_purchase_orders', ['approval_flag'=> $approvflg]);
        $targeturl = base_url("authorization_approval");
        
        if($oldtotal!=$total)
        {
         //////////////////////////////////////////////////////////////////////////////////////////////////////////
            $this->db->trans_start();
            $flag = false;
            $transok = true;


            //Product Data
            $pid = $this->input->post('pid');
            $productlist = array();

            $prodindex = 0;

            $this->db->delete('cberp_purchase_order_items', array('tid' => $invocieno));
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

            foreach ($pid as $key => $value) {
                $total_discount += numberClean(@$ptotal_disc[$key]);
                $total_tax += numberClean($ptotal_tax[$key]);
                $data1 = array(
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


                $productlist[$prodindex] = $data1;

                $prodindex++;
                $amt = numberClean($product_qty[$key]);
                $itc += $amt;

                if ($this->input->post('update_stock') == 'yes') {
                    $amt = numberClean(@$product_qty[$key]) - numberClean(@$old_product_qty[$key]);
                    $this->db->set('qty', "qty+$amt", FALSE);
                    $this->db->where('pid', $product_id[$key]);
                    $this->db->update('cberp_products');
                }
                $flag = true;
            }

            $bill_date = datefordatabase($invoicedate);
            $bill_due_date = datefordatabase($invocieduedate);
            $total_discount = rev_amountExchange_s(amountFormat_general($total_discount), $currency, $this->aauth->get_user()->loc);
            $total_tax = rev_amountExchange_s(amountFormat_general($total_tax), $currency, $this->aauth->get_user()->loc);
            
            
            $data = array('purchase_order_date' => $bill_date, 'duedate' => $bill_due_date,  'shipping_charge' => $shipping, 'shipping_tax' => $shipping_tax, 'shipping_tax_type' => $ship_taxtype, 'discount' => $total_discount, 'tax' => $total_tax, 'order_total' => $total, 'notes' => $notes, 'customer_id' => $customer_id, 'tax_status' => $tax, 'discstatus' => $discstatus, 'format_discount' => $discountFormat, 'internal_reference' => $internal_reference, 'payment_terms' => $pterms, 'multi' => $currency, 'purchase_type'=>$purchase_type, 'currency_id'=>$currency_id, 'approval_flag'=> $approval_flag);
            $this->db->set($data);
            $this->db->where('id', $invocieno);

            if ($flag) {

                if ($this->db->update('cberp_purchase_orders', $data)) {
                    $this->db->insert_batch('cberp_purchase_order_items', $productlist);
                }

            } else {
                $transok = false;
            }

            if ($transok) {
                $this->db->trans_complete();
            } else {
                $this->db->trans_rollback();
            }
        }
        echo json_encode(array('status' => 'Success', 'message' =>$this->lang->line('Authorization request has been approved') . " <a href='".$targeturl."' class='btn btn-info btn-sm'><span class='icon-file-text2' aria-hidden='true'></span> View </a> "));
    }
    public function send_po_action()
    {
    //    ini_set('display_errors', 1);
    //    ini_set('display_startup_errors', 1);
    //    error_reporting(E_ALL);
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
        $purchase_number = $this->input->post('purchase_number');
        $currency = $this->input->post('mcurrency');
        $customer_id = $this->input->post('customer_id');
        $purchase_type = $this->input->post('doc_type');
        $currency_id = $this->input->post('currency_id');
        $invocieno = $this->input->post('po_id');
        $purchase_order_date = $this->input->post('invoicedate');
        $invocieduedate = $this->input->post('invocieduedate');
        $notes = $this->input->post('notes', true);
        $tax = $this->input->post('tax_handle');
        $internal_reference = $this->input->post('refer', true);
        $total = rev_amountExchange_s($this->input->post('total'), $currency, $this->aauth->get_user()->loc);
        $total_tax = 0;
        $total_discount = 0;
        $discountFormat = $this->input->post('discountFormat');
        $pterms = $this->input->post('pterms');
        $ship_taxtype = $this->input->post('ship_taxtype');
        $subtotal = rev_amountExchange_s($this->input->post('subtotal'), $currency, $this->aauth->get_user()->loc);
        $shipping = rev_amountExchange_s($this->input->post('shipping'), $currency, $this->aauth->get_user()->loc);
        $shipping_tax = rev_amountExchange_s($this->input->post('ship_tax'), $currency, $this->aauth->get_user()->loc);

        $store_id = $this->input->post('store_id');
        $bill_date = datefordatabase($purchase_order_date);
        $bill_due_date = datefordatabase($invocieduedate);
    
        if ($ship_taxtype == 'incl') $shipping = $shipping - $shipping_tax;

        $itc = 0;
        if ($discountFormat == '0') {
            $discstatus = 0;
        } else {
            $discstatus = 1;
        }

        if ($customer_id == 0) {
            echo json_encode(array('status' => 'Error', 'message' =>
                "Please add a new supplier or search from a previous added!"));
            exit();
        }
        if (empty($purchase_type)) {
            echo json_encode(array('status' => 'Error', 'message' =>
                "Please select a Payment Type"));
            exit;
        }
        if (empty($currency_id)) {
            echo json_encode(array('status' => 'Error', 'message' =>
                "Please select a currency"));
            exit;
        }
        

         //erp2024 find amout limit 17-07-2024 ///////////////////////////////////////
         $oldtotal = rev_amountExchange_s($this->input->post('oldtotal'), $currency, $this->aauth->get_user()->loc);        
         $authdata =[];     
         $authdata = [
            'authorized_amount' => $this->input->post('authorized_amount'),
            'status' => 'Approve',
            'comments' => $this->input->post('comments'),
            'authorized_amount' => $this->input->post('authorized_amount'),
            'authorized_date' => date("Y-m-d H:i:s"),
            'authorized_by' => $this->session->userdata('id'),
            'authorized_type' => 'Reported Person',
            'function_id' => $purchase_number,
        ];
        if($this->input->post('statusType')=="Approve"){
            $approvflg = '1';
        }
        else if($this->input->post('statusType')=="Hold"){
            $approvflg = '2';
        }
        else{
            $approvflg = '3';
        }
        if($this->input->post('iid'))
        {
            $this->db->where('function_id',$purchase_number);
            $this->db->where('function_type','Purchase Order');
            $this->db->update('authorization_history', $authdata);
            $this->db->where('purchase_number',$purchase_number);
            $this->db->update('cberp_purchase_orders', ['approval_flag'=> $approvflg,'prepared_flag'=>'1']);
        }
        else{            
            $this->db->insert('authorization_history', $authdata);
            $data_new = array('purchase_number' => $purchase_number, 'purchase_order_date' => $bill_date, 'duedate' => $bill_due_date, 'shipping_charge' => $shipping, 'shipping_tax' => $shipping_tax, 'shipping_tax_type' => $ship_taxtype, 'order_total' => $total, 'notes' => $notes, 'customer_id' => $customer_id, 'employee_id' => $this->aauth->get_user()->id, 'tax_status' => $tax, 'discstatus' => $discstatus, 'format_discount' => $discountFormat, 'internal_reference' => $internal_reference, 'payment_terms' => $pterms, 'loc' => $this->aauth->get_user()->loc, 'multi' => $currency, 'currency_id'=>$currency_id, 'purchase_type'=>$purchase_type, 'store_id'=>$store_id,'customer_reference' => $this->input->post('customer_reference_number'),'customer_contact_person' => $this->input->post('customer_contact_person'),'customer_contact_number' =>  $this->input->post('customer_contact_number'),'customer_contact_email' => $this->input->post('customer_contact_email'));

            $data_new["order_status"] = 'Sent';
            $data_new["sent_by"] = 'Sent';
            $data_new["sent_date"] = date('Y-m-d H:i:s');
            $data_new['prepared_by']  = $this->session->userdata('id');
            $data_new['prepared_date']  = date('Y-m-d H:i:s');
            $data_new['prepared_flag'] = '1';
            $data_new['assigned_to'] = $this->session->userdata('id');
            $data_new["approval_flag"] = 1;
            $data_new['created_by']   = $this->session->userdata('id');
            $data_new['created_date']   = date('Y-m-d H:i:s');
            $this->db->insert('cberp_purchase_orders', $data_new);

            $invocieno = $this->db->insert_id();  
            $authdata['function_type'] = 'Purchase Order';
            $authdata['function_id'] = $purchase_number;
            $authdata['requested_by'] = $this->session->userdata('id');
            $authdata['requested_date'] = date("Y-m-d");
            $authdata['requested_amount'] = $total;
            $this->db->insert('authorization_history',$history);  
        }   
   
        $module_number = get_module_details_by_name('Stock');
        $users_list =  linked_user_module_approvals_by_module_number($module_number);
        $target_url = base_url("purchase/create?id=".$purchase_number);        
        $message_caption = "Purchase Order(".$purchase_number.")";
        $message = "Purchase Order(".$purchase_number.") Sent";
        send_message_to_users($users_list,$target_url,$message_caption,$message,date('Y-m-d'));
        
        $targeturl = base_url("authorization_approval");
        // erp2025 09-01-2025 starts
        $sequence_number = detailed_log_history('Purchaseorder',$purchase_number,'Purchase order Send', $_POST['changedFields']);	
        // erp2025 09-01-2025 ends  
        // file upload section starts 22-01-2025
            if($_FILES['upfile'])
            {
                upload_files($_FILES['upfile'], 'Purchaseorder',$invocieno);
            }
        // file upload section ends 22-01-2025
        if($oldtotal!=$total)
        {
         //////////////////////////////////////////////////////////////////////////////////////////////////////////
            $this->db->trans_start();
            $flag = false;
            $transok = true;


            //Product Data
            $pid = $this->input->post('pid');
            $productlist = array();
            $product_id = $this->input->post('pid');
            $prodindex = 0;
            // delete_product_log('cberp_purchase_order_items','Purchaseorder',$purchase_number,$product_id,$sequence_number);
            // $this->db->delete('cberp_purchase_order_items', array('tid' => $invocieno));   
            
            $deleted_items = $this->input->post('deleted_item');
            $deleted_items_array = explode(",", $deleted_items);
            if($deleted_items_array)
            {
                $this->db->where('purchase_number', $purchase_number);
                $this->db->where_in('product_code', $deleted_items_array);
                $this->db->delete('cberp_purchase_order_items'); 
            }       
       
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
            $account_code = $this->input->post('expense_account_number');
            foreach ($pid as $key => $value) {
                $total_discount += numberClean(@$ptotal_disc[$key]);
                $total_tax += numberClean($ptotal_tax[$key]);
                $data1 = array(                   
                    'product_code' => $product_hsn[$key],
                    'quantity' => numberClean($product_qty[$key]),
                    'price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
                    // 'tax' => numberClean($product_tax[$key]),
                    'discount' => numberClean($product_discount[$key]),
                    'subtotal' => rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc),
                    'totaltax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                    'totaldiscount' => rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc),
                    'account_code' => $account_code[$key],
                    'unit' => $product_unit[$key],
                    'purchase_number' => $purchase_number,
                );

                $code = trim($product_hsn[$key]);
                $isChanged = !empty($changedSet) && isset($changedSet[$code]);
                $isInWhole = !empty($wholeSet) && isset($wholeSet[$code]);  
                
                if($isChanged && in_array($code, $product_hsn)) {
                    $this->db->update('cberp_purchase_order_items', $data1, ['purchase_number'=>$purchase_number, 'product_code'=>$code]);
                }
                elseif (!$isInWhole && in_array($code, $product_hsn)) 
                {
                    $this->db->insert('cberp_purchase_order_items', $data1);
                }
                $productlist[$prodindex] = $data1;

                $prodindex++;
                $amt = numberClean($product_qty[$key]);
                $itc += $amt;

                if ($this->input->post('update_stock') == 'yes') {
                    $amt = numberClean(@$product_qty[$key]) - numberClean(@$old_product_qty[$key]);
                    $this->db->set('qty', "qty+$amt", FALSE);
                    $this->db->where('pid', $product_id[$key]);
                    $this->db->update('cberp_products');
                }
                $flag = true;
            }

            $bill_date = datefordatabase($invoicedate);
            $bill_due_date = datefordatabase($invocieduedate);
            $total_discount = rev_amountExchange_s(amountFormat_general($total_discount), $currency, $this->aauth->get_user()->loc);
            $total_tax = rev_amountExchange_s(amountFormat_general($total_tax), $currency, $this->aauth->get_user()->loc);
            
           
            $data = array('purchase_order_date' => $bill_date, 'duedate' => $bill_due_date, 'shipping_charge' => $shipping, 'shipping_tax' => $shipping_tax, 'shipping_tax_type' => $ship_taxtype, 'discount' => $total_discount, 'tax' => $total_tax, 'order_total' => $total, 'notes' => $notes, 'customer_id' => $customer_id, 'tax_status' => $tax, 'discstatus' => $discstatus, 'format_discount' => $discountFormat, 'internal_reference' => $internal_reference, 'payment_terms' => $pterms, 'multi' => $currency, 'purchase_type'=>$purchase_type, 'currency_id'=>$currency_id, 'approval_flag'=> 1,'sent_by' => $this->session->userdata('id'),'sent_date'=>date('Y-m-d H:i:s'),'order_status'=>'Sent','approved_by'=> $this->session->userdata('id'),'approved_date'=>date('Y-m-d H:i:s'),'assigned_to'=> $this->session->userdata('id'));
            $this->db->set($data);
            $this->db->where('purchase_number', $purchase_number);
            if ($flag) {

                if ($this->db->update('cberp_purchase_orders', $data)) {
                   
                }

            } else {
                $transok = false;
            }

            if ($transok) {
                $this->db->trans_complete();
            } else {
                $this->db->trans_rollback();
            }
        }

        history_table_log('cberp_purchase_order_logs','purchase_order_id',$invocieno,'Send Directly');
        header('Content-Type: application/json');
        echo json_encode(array('status' => 'Success', 'message' =>$this->lang->line('Authorization request has been approved'),'data'=>$purchase_number));
    }

    public function revertorder_action()
    {
        $po_id = $this->input->post('po_id');
        // erp2025 09-01-2025 starts
        detailed_log_history('Purchaseorder',$po_id,'Purchase order Reverted', $_POST['changedFields']);	
        // erp2025 09-01-2025 ends   
        $this->db->update('cberp_purchase_orders', ['assigned_to' => NULL,'approval_flag'=>'0','order_status'=>'Pending'], ['id'=> $po_id]);
        echo json_encode(array('status' => 'success'));
    }

 

    public function revertorder_by_admin_action()
    {
        $po_id = $this->input->post('po_id');
        history_table_log('cberp_purchase_order_logs','purchase_order_id',$po_id,'Reverted');
        // erp2025 09-01-2025 starts
         detailed_log_history('Purchaseorder',$po_id,'Purchase order Reverted', $_POST['changedFields']);	
        // erp2025 09-01-2025 ends   
        $this->db->update('cberp_purchase_orders', ['assigned_to' => NULL,'approved_date' => NULL,'approval_flag'=>'1','order_status'=>'Reverted'], ['id'=> $po_id]);
        echo json_encode(array('status' => 'success'));
    }

    public function po_accept()
    {
        $po_id = $this->input->post('po_id');
        history_table_log('cberp_purchase_order_logs','purchase_order_id',$po_id,'Accept & Send');
        // erp2025 09-01-2025 starts
         detailed_log_history('Purchaseorder',$po_id,'Purchase order Accepted & Send', $_POST['changedFields']);	
        // erp2025 09-01-2025 ends   
        $this->db->update('cberp_purchase_orders', ['sent_by' => $this->session->userdata('id'),'sent_date'=>date('Y-m-d H:i:s'),'order_status'=>'Sent'], ['id'=> $po_id]);
        echo json_encode(array('status' => 'success'));
    }
    // customer payment #erp2024 01-10-2024
    public function purchase_order_payment()
    {
        $this->load->model('accounts_model');
        $data['acclist'] = $this->accounts_model->accountslist((integer)$this->aauth->get_user()->loc);
        $tid = $this->input->get('id');
        $customerid = $this->input->get('customer_id');
        $data['invoice'] = $this->purchase->purchase_details($tid);
        // $data['attach'] = $this->invocies->attach($tid);
        $head['usernm'] = $this->aauth->get_user()->username;
        $head['title'] = "Purchase order Payment";
        // $data['dew_invoices'] = $this->invocies->dew_invoices_by_customerid($customerid);
        // if ($data['invoice']['id']) $data['activity'] = $this->invocies->invoice_transactions($tid);
        // $data['employee'] = $this->invocies->employee($data['invoice']['employee_id']);
        // $data['custom_fields'] = $this->custom->view_fields_data($tid, 2);
        $this->load->view('fixed/header', $head);
        // if ($data['invoice']['id']) {
        //     $data['invoice']['id'] = $tid;            
            // $data['trackingdata'] = tracking_details('invoice_id',$tid);
            $this->load->view('purchase/purchase_order_payment', $data);
        // }
        $this->load->view('fixed/footer');
    }

    // customer payment #erp2024 01-10-2024
    public function purchase_receipt_payment()
    {
        $this->load->model('accounts_model');
        $data['acclist'] = $this->accounts_model->accountslist((integer)$this->aauth->get_user()->loc);
        $purchase_reciept_number = $this->input->get('id');
        $customerid = $this->input->get('customer_id');
        $data['invoice'] = $this->purchase->purchase_receipt_data($purchase_reciept_number);

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
        $data['default_receivableaccount'] = default_chart_of_account('purchase_account');


        // $data['attach'] = $this->invocies->attach($tid);
        $head['usernm'] = $this->aauth->get_user()->username;
        $head['title'] = "Purchase order Payment";
        $this->load->view('fixed/header', $head);
        // if ($data['invoice']['id']) {
        //     $data['invoice']['id'] = $tid;            
            // $data['trackingdata'] = tracking_details('invoice_id',$tid);
            $this->load->view('purchase/purchase_receipt_payment', $data);
        // }
        $this->load->view('fixed/footer');
    }

    public function purchase_receipt_payment_edit()
    {
        $this->load->model('accounts_model');
        $data['acclist'] = $this->accounts_model->accountslist((integer)$this->aauth->get_user()->loc);

        $purchase_reciept_number = $this->input->get('id');
        $reference_number = $this->input->get('ref');
        $data['invoice'] = $this->purchase->purchase_receipt_data($purchase_reciept_number);
        
       
        $data['transaction_data'] = $this->purchase->purchase_receipt_data_by_id($reference_number);
       
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
        $data['default_receivableaccount'] = default_chart_of_account('purchase_account');

        $this->load->model('transactions_model', 'transactions');
        $data['transaction_ai'] = $this->transactions->get_transaction_ai_details($data['transaction_data']['transaction_number']);
        // echo "<pre>"; print_r($data['transaction_ai']); die();
 
        // $data['attach'] = $this->invocies->attach($tid);transaction_data
        $head['usernm'] = $this->aauth->get_user()->username;
        $head['title'] = "Purchase order Payment";
        $this->load->view('fixed/header', $head);
        // if ($data['invoice']['id']) {
        //     $data['invoice']['id'] = $tid;            
            // $data['trackingdata'] = tracking_details('invoice_id',$tid);
            $this->load->view('purchase/purchase_receipt_payment_edit', $data);
        // }
        $this->load->view('fixed/footer');
    }
    
    public function cancel_purchasereceipt_action()
    {
        // if (!$this->aauth->premission(1)) {
        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        // }
        $receipt_id = $this->input->post('receipt_id');

        $details = $this->purchase->check_purchase_receipt_ispaid($receipt_id);
        if($details['payment_status']=='Paid' || $details['payment_status']=='Partial')
        {
            $this->purchase->reset_purchase_payment_accounts($details['payment_transaction_number']);
        }       

        $transaction_data = $this->purchase->transaction_number_by_id($receipt_id); 
        $this->load->model('invoices_model', 'invocies');
        $this->purchase->reset_credit_accounts($transaction_data['transaction_number']);
        $this->purchase->reset_debit_accounts($transaction_data['transaction_number']);        
        $this->purchase->reset_transaction_amounts($transaction_data['transaction_number']);
        $updatedata = [
            'reciept_status' => 'Draft',
            'payment_status' => 'Due',
            'approved_by' => (NULL),
            'approved_date' => (NULL),
            'approval_flag' => '0',
            // 'received_by' => NULL,
            // 'received_dt' => NULL,
            'payment_date' => NULL,
            'payment_recieved_amount' => 0.00,
        ];
        
        $this->db->where('id', $receipt_id);
        $this->db->update('cberp_purchase_receipts', $updatedata);
        history_table_log('purchase_receipt_log','reciept_id',$receipt_id,'Cancel Receipt');
        //erp2024 06-01-2025 detailed history log starts
         detailed_log_history('Purchasereceipt',$receipt_id,'Receipt Canceled', $changedFields);
        //erp2024 06-01-2025 detailed history log ends 
        echo json_encode(array('status' => 'Success'));
    }

    public function cancel_purchase_order_approval_action()
    {
        $po_id = $this->input->post('po_id');
        $purchase_number = $this->input->post('purchase_number');
        // erp2025 09-01-2025 starts
        detailed_log_history('Purchaseorder',$po_id,'Purchase Order Approve Cancel', $_POST['changedFields']);	
        // erp2025 09-01-2025 ends   
        $this->db->update('cberp_purchase_orders', ['assigned_to' => NULL,'approved_by'=>NULL,'approval_flag'=>'0','order_status'=>'Pending','approved_date'=>NULL], ['purchase_number'=> $purchase_number]);
        $message = "Purchase Order(".$purchase_number.") Approve Cancel";
        $module_number = get_module_details_by_name('Stock');
        $users_list =  linked_user_module_approvals_by_module_number($module_number);
        $target_url = base_url("purchase/create?id=".$purchase_number);
        $message_caption = "Purchase Order(".$purchase_number.")";
        send_message_to_users($users_list,$target_url,$message_caption,$message,date('Y-m-d'));
        echo json_encode(array('status' => 'success'));
    }
    
}
