<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\Printer;

class Invoices extends CI_Controller
{
    private $configurations;
    private $prifix72;
    private $prifix51;
    public function __construct()
    {       
        parent::__construct();
        $this->load->model('invoices_model', 'invocies');
         $this->load->model('plugins_model', 'plugins');
         $this->load->model('customer_enquiry_model', 'customer_enquiry');
         $this->load->model('costingcalculation_model', 'costingcalculation');
         $this->load->model('quote_model', 'quote'); 
         $this->load->model('purchase_model', 'purchase');
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
        $this->prifix72 =  get_prefix_72();
        $this->prifix51 =  get_prefix();
        // $prifix72 =  get_prefix_72();
    }

    //costing calculation

    public function costing()
    {
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);

        //purchaseitemsdata
        $id = $this->input->get('pid', true);
        $token = $this->input->get('token', true);  
        $data['token'] = $token;
        $this->load->model('plugins_model', 'plugins');
        $this->load->library("Common");            
        
        
        $this->load->model('employee_model', 'employee');
        if(isset($id) || !empty($id)) 
        {
            // ini_set('display_errors', 1);
            // ini_set('display_startup_errors', 1);
            // error_reporting(E_ALL);
            $data['trackingdata'] = tracking_details('purchase_order_number',$id);
            $data['purchasemasterdata'] = $this->costingcalculation->purchase_details($id);
            $data['purchaseitemsdata'] = $this->costingcalculation->purchase_item_details($id);
            $data['purchaseorderdata'] =  $data['purchasemasterdata'];
            $data['default_warehouse'] = $this->costingcalculation->default_warehouse(); 
            $data['purchasemasterdata']['salepoint_id'] = $data['default_warehouse']['store_id'];
            $data['purchasemasterdata']['salepoint_name'] = $data['default_warehouse']['store_name'];
            $data['purchase_number'] = $id;
            $data['purchaseid'] = $id;           
            $srvData = $this->costingcalculation->lastsrvNumber($id);
            $data['srvNumber'] = $srvData['srv'];
            $data['srvFlg'] = $srvData['srvflg'];           
            $data['purchaseexpensesdata'] = [];            
            $data['purchaserecipts'] = $this->costingcalculation->get_purchase_receipt_by_srvNumber($data['srvNumber']);    
            $data['custom_fields_c'] = $this->custom->add_fields(1);
            $data['exchange'] = $this->plugins->universal_api(5);
            $data['taxlist'] = $this->common->taxlist($this->config->item('tax'));
            $head['title'] = "Purchase Receipt";
            $head['usernm'] = $this->aauth->get_user()->username;
            $data['custom_fields'] = $this->custom->add_fields(2);
            $data['log'] = $this->invocies->receipthistory($id);
            $data['employee'] = $this->employee->list_employee();
            $data['reciept_number'] = '';
        }
        else{
            // ini_set('display_errors', 1);
            // ini_set('display_startup_errors', 1);
            // error_reporting(E_ALL);
          
            $purchase_reciept_number = $this->input->get('id', true);
            $data['trackingdata'] = tracking_details('purchase_reciept_number',$purchase_reciept_number);  
            $data['purchaseorderdata'] = $this->costingcalculation->purchase_order_by_srv($purchase_reciept_number);
            $data['purchasemasterdata'] = $this->costingcalculation->cberp_costing_master_details($purchase_reciept_number);  
            // echo "<pre>"; print_r($data['purchasemasterdata']); die();          
            $data['reciept_number'] = $purchase_reciept_number;
            $data['purchaseitemsdata'] = $this->costingcalculation->costing_item_details($purchase_reciept_number);
            $data['purchaseexpensesdata'] = $this->costingcalculation->cberp_costing_expenses_details($purchase_reciept_number);
            $data['assignedperson'] = $this->costingcalculation->employee($data['purchasemasterdata']['assign_to']);
            $data['payment_records'] = $this->costingcalculation->purchase_receipt_payments_received($purchase_reciept_number); 
            $data['journals_records'] = $this->costingcalculation->purchase_receipt_journal_records($purchase_reciept_number);  
            $data['purchaserecipts'] = $this->costingcalculation->get_purchase_receipt_by_srvNumber($data['purchasemasterdata']['purchase_reciept_number']);
            $data['srvNumber'] = $purchase_reciept_number;           
            $data['custom_fields_c'] = $this->custom->add_fields(1);
            $data['exchange'] = $this->plugins->universal_api(5);
            $data['taxlist'] = $this->common->taxlist($this->config->item('tax'));
            $head['title'] = "Purchase Reciept";
            $head['usernm'] = $this->aauth->get_user()->username;
            $data['custom_fields'] = $this->custom->add_fields(2); 
            $data['costid'] = $purchase_reciept_number;
            $data['employee'] = $this->employee->list_employee();
            // $data['log'] = $this->invocies->receipthistory($id);
            
           
            //erp2024 06-01-2025 detailed history log starts 
            $page = "Purchasereceipt";
            $data['detailed_log']= get_detailed_logs($purchase_reciept_number,$page);
            // $data['detailed_log']= $this->invocies->get_detailed_log($purchase_reciept_number,$page);
            $products = $data['detailed_log'];
            $groupedBySequence = []; // Initialize an empty array for grouping

            foreach ($products as $product) {
                $sequence = $product['seqence_number'];
                $groupedBySequence[$sequence][] = $product; // Group by sequence number
            }
            
            $data['groupedDatas'] = $groupedBySequence;
            if($data['purchasemasterdata']['created_by'])
            {
                $data['created_employee'] = employee_details_by_id($data['purchasemasterdata']['created_by']);
            }
        }
       
      
        $this->load->view('fixed/header', $head);
        $this->load->view('invoices/costingapprove', $data);
        $this->load->view('fixed/footer');
        // if(isset($id) || !empty($id)) 
        // {
        //     $data['trackingdata'] = $this->costingcalculation->tracking_details('purchase_order_id',$id);
        //     $data['purchasemasterdata'] = $this->costingcalculation->purchase_details($id);
        //     $data['purchaseitemsdata'] = $this->costingcalculation->purchase_item_details($id);
        //     $data['default_warehouse'] = $this->costingcalculation->default_warehouse();
        //     $data['purchaseid'] = $id;
        //     $srvData = $this->costingcalculation->lastsrvNumber($id);
        //     $data['srvNumber'] = $srvData['srv'];
        //     $data['srvFlg'] = $srvData['srvflg'];           
    
            
        //     $data['custom_fields_c'] = $this->custom->add_fields(1);
        //     $data['exchange'] = $this->plugins->universal_api(5);
        //     $data['taxlist'] = $this->common->taxlist($this->config->item('tax'));
        //     $head['title'] = "Purchase Receipt";
        //     $head['usernm'] = $this->aauth->get_user()->username;
        //     $data['custom_fields'] = $this->custom->add_fields(2);
        //     $data['log'] = $this->invocies->receipthistory($id);
        //     $this->load->view('fixed/header', $head);
        //     $this->load->view('invoices/costing', $data);
        //     $this->load->view('fixed/footer');
        // }
        // else{

        //     $id = $this->input->get('id', true);
        //     $data['trackingdata'] = $this->costingcalculation->tracking_details('purchase_reciept_id',$id);
        //     $data['purchaseorderdata'] = $this->costingcalculation->purchase_order_by_srv($id);
        //     $data['purchasemasterdata'] = $this->costingcalculation->cberp_costing_master_details($id);
        //     $data['purchaseitemsdata'] = $this->costingcalculation->costing_item_details($id);
        //     $data['purchaseexpensesdata'] = $this->costingcalculation->cberp_costing_expenses_details($id);           
        //     $data['assignedperson'] = $this->costingcalculation->employee($data['purchasemasterdata']['assign_to']);

        //     $data['payment_records'] = $this->costingcalculation->purchase_receipt_payments_received($id);
        //     $data['journals_records'] = $this->costingcalculation->purchase_receipt_journal_records($id);
            
        //     $data['custom_fields_c'] = $this->custom->add_fields(1);
        //     $data['exchange'] = $this->plugins->universal_api(5);
        //     $data['taxlist'] = $this->common->taxlist($this->config->item('tax'));
        //     $head['title'] = "Purchase Reciept";
        //     $head['usernm'] = $this->aauth->get_user()->username;
        //     $data['custom_fields'] = $this->custom->add_fields(2);
        //     $data['costid'] = $id;
        //     $this->load->model('employee_model', 'employee');
        //     $data['employee'] = $this->employee->list_employee();
        //     $data['log'] = $this->invocies->receipthistory($id);
        //     //erp2024 06-01-2025 detailed history log starts
        //     $page = "Purchasereceipt";
        //     $data['detailed_log']= $this->invocies->get_detailed_log($id,$page);
        //     $products = $data['detailed_log'];
        //     $groupedBySequence = []; // Initialize an empty array for grouping

        //     foreach ($products as $product) {
        //         $sequence = $product['seqence_number'];
        //         $groupedBySequence[$sequence][] = $product; // Group by sequence number
        //     }
            
        //     $data['groupedReceipts'] = $groupedBySequence;
        //     $this->load->view('fixed/header', $head);
        //     $this->load->view('invoices/costingapprove', $data);
        //     $this->load->view('fixed/footer');
        // }
    }

    //stock recipt insert action cberp_transaction_tracking
    public function dataoperation(){
        //  ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
        $purchase_id = $this->input->post('purchase_id', true);
        $purchase_number = $this->input->post('purchase_number', true);
        $costfactor = $this->input->post('cost_factor', true);
        $receipt_id = $this->input->post('receipt_id', true);
        $purchase_reciept_number = $this->input->post('srv', true);
        $master_data = [
            "salepoint_name" => $this->input->post('salepoint_name', true),
            "salepoint_id" => $this->input->post('salepoint_id', true),
            "purchase_number" => $purchase_number,
            "supplier_id" => $this->input->post('supplier_id', true),
            "party_name" => $this->input->post('party_name', true),
            "damageclaim_account_id" => $this->input->post('damageclaim_ac', true),
            "damageclaim_ac_name" => $this->input->post('damageclaim_ac_name', true),
            "bill_number" => $this->input->post('bill_number', true),
            "bill_date" => datefordatabase($this->input->post('bill_date', true)),
            "currency_id" => $this->input->post('currency_id', true),
            "currency_rate" => $this->input->post('currency_rate', true),
            "bill_description" => $this->input->post('bill_description', true),
            "purchase_type" => $this->input->post('doctype', true),
            "purchase_reciept_number" => $this->input->post('srv', true),
            "purchase_receipt_date" => date("Y-m-d"),
            "purchase_amount" => numberClean($this->input->post('purchase_amount', true)),
            "cost_factor" => $costfactor,
            "payment_date" => datefordatabase($this->input->post('payment_date', true)),
            "bill_amount" => numberClean($this->input->post('bill_amount', true)),
            "created_date" => date("Y-m-d H:i:s"),
            "created_by" => $this->session->userdata('id'),
            "prepared_by" => $this->session->userdata('id'),            
            "prepared_date" => date("Y-m-d H:i:s"),
            "prepared_flag" => '1',
            "reciept_status" => 'Pending',
            "note" => $this->input->post('note', true),
        ];

        $employee = $this->input->post('employee', true);
        if($employee)
        {
            $master_data['assign_to'] = $employee;
            $master_data['approved_by'] = $this->session->userdata('id');
            $master_data['approved_dt'] = date("Y-m-d H:i:s");
            $master_data['approval_flag'] = '1';
            $master_data['reciept_status'] = 'Assigned';
        }
        $stockreciptid = "";
        $changedFields = $_POST['changedFields'];
        if(!empty($master_data) && !empty($this->input->post('salepoint_name', true)) && !empty( $this->input->post('bill_number', true))){
            $query = $this->db->select('id,purchase_reciept_number')
            ->from('cberp_purchase_receipts')
            ->where('purchase_number', $purchase_number)
            ->where('purchase_reciept_number', $purchase_reciept_number)
            ->get();
            if ($query->num_rows() > 0) {
                $existing_row = $query->row_array();
                $this->db->where('purchase_reciept_number', $existing_row['purchase_reciept_number']);
                $this->db->update('cberp_purchase_receipts', $master_data);
                // die($this->db->last_query());
                
                $stockreciptid = $existing_row['id'];
                detailed_log_history('Purchasereceipt',$purchase_reciept_number,'Purchase Receipt Updated',$changedFields);
            } 
            else 
            {
                $srvData = $this->costingcalculation->lastsrvNumber($id);
                $master_data['purchase_reciept_number'] = $srvData['srv']; 
                $purchase_reciept_number = $master_data['purchase_reciept_number'];             
                $this->db->insert('cberp_purchase_receipts', $master_data);
                $stockreciptid = $this->db->insert_id();
                 //erp2024 06-01-2025 detailed history log starts                
                detailed_log_history('Purchaseorder',$purchase_number,'Purchase Receipt Created', $changedFields);
                detailed_log_history('Purchasereceipt',$purchase_reciept_number,'Purchase Receipt Created','');
                //erp2024 06-01-2025 detailed history log ends 
            }
            $this->db->where('purchase_reciept_number', $purchase_reciept_number);
            $this->db->delete('cberp_purchase_receipt_items');
            $this->db->delete('cberp_purchase_receipt_expenses',['purchase_reciept_number'=>$purchase_reciept_number]);
            //erp2024 insert to authorization history table////////////////////////////////
            $history['function_type'] = 'Purchase Receipt';
            $history['function_id'] = $purchase_reciept_number;
            $history['requested_by'] = $this->session->userdata('id');
            $history['requested_date'] = date("Y-m-d");
            $history['requested_amount'] = numberClean($this->input->post('bill_amount'));
            $this->db->insert('authorization_history',$history);
            //////////////////////////////////////////////////////////////////////////////
            
        }
        $product_names          =  $this->input->post('product_name', true);
        $product_code           =  $this->input->post('product_code', true);
        $product_unit           =  $this->input->post('product_unit', true);
        $product_qty            =  $this->input->post('product_qty', true);
        $product_qty_recieved   =  $this->input->post('product_qty_recieved', true);
        $product_foc            =  $this->input->post('product_foc', true);
        $damage                 =  $this->input->post('damage', true);
        $price                  =  $this->input->post('price', true);
        $saleprice              =  $this->input->post('saleprice', true);
        $amount                 =  $this->input->post('amount', true);
        $discountperc           =  $this->input->post('discountperc', true);
        $discountamount         =  $this->input->post('discountamount', true);
        $netamount              =  $this->input->post('netamount', true);
        $qaramount              =  $this->input->post('qaramount', true);
        $qaramount              =  $this->input->post('qaramount', true);
        $description            =  $this->input->post('description', true);
        $product_id             =   $this->input->post('product_id', true);
        $account_code           =   $this->input->post('account_code', true);
        $prodindex=0;
        $productlist =[];
        foreach ($product_names as $key => $value) {
            if(!empty($product_names[$key]) && !empty($stockreciptid))
            { 
                
                $data1 = array(
                    'purchase_reciept_number' => $purchase_reciept_number,
                    'product_code'             => $product_code[$key],
                    'ordered_quantity'         => $product_qty[$key],
                    'product_quantity_recieved' => $product_qty_recieved[$key],
                    'product_foc'            => numberClean($product_foc[$key]),
                    'damaged_quantity'        => $damage[$key],
                    'price'                  => numberClean($price[$key]),
                    'saleprice'              => numberClean($saleprice[$key]),
                    'amount'                 => numberClean(($amount[$key])),
                    'discountperc'           => $discountperc[$key],
                    'discountamount'         => $discountamount[$key],
                    'netamount'              => numberClean($netamount[$key]),
                    'description'            => $description[$key],
                    'qaramount'              => numberClean($qaramount[$key]),
                    'account_code'           => $account_code[$key],
                    'created_date'           => date("Y-m-d H:i:s"),
                );
                // if($amount[$key]>0 && $product_qty_recieved[$key]>0)
                // {
                //     $itemcost = ($amount[$key]/$product_qty_recieved[$key])*$costfactor;
                //     $productcost = [
                //         'item_cost' => $itemcost,
                //         'updated_by' => $this->session->userdata('id'),
                //         'updated_dt' => date("Y-m-d H:i:s")

                //     ];
                //     $this->db->where('product_id', $product_id[$key]);
                //     $this->db->update('cberp_product_ai', $productcost);
                // }


                $this->invocies->purchase_order_items_update($purchase_number,$product_code[$key],$product_qty_recieved[$key]);
                $productlist[$prodindex] = $data1;
                $prodindex++;
            }
        }
       
        //costing section 
        $expense_name =  $this->input->post('expense_name', true);
        $expense_id   =  $this->input->post('expense_id', true);
        $payable_acc  =  $this->input->post('payable_acc', true);
        $payable_acc_no =  $this->input->post('payable_acc_no', true);
        $bill_number_cost =  $this->input->post('bill_number_cost', true);
        $bill_date_cost =  $this->input->post('bill_date_cost', true);
        $costing_amount =  $this->input->post('costing_amount', true);
        $currency_cost =  $this->input->post('currency_cost', true);
        $currency_rate_cost =  $this->input->post('currency_rate_cost', true);
        $costing_amount_qar =  $this->input->post('costing_amount_qar', true);
        $costing_amount_net =  $this->input->post('costing_amount_net', true);
        $remarks =  $this->input->post('remarks', true);
        $costindex=0;
        $costlist =[];
        foreach ($expense_name as $key => $row) {
            if(!empty($expense_name[$key]) && !empty($stockreciptid))
            {
             
                $data2 = array(
                    'purchase_reciept_number'=> $purchase_reciept_number,
                    'expense_name'          => $expense_name[$key],
                    'expense_id'            => $expense_id[$key],
                    'payable_account'        => $payable_acc[$key],
                    'payable_account_number'=> $payable_acc_no[$key],
                    'bill_number_cost'      => $bill_number_cost[$key],
                    'bill_date_cost'        => $bill_date_cost[$key],
                    'costing_amount'        => numberClean($costing_amount[$key]),
                    'currency_cost'         => ($currency_cost[$key]),
                    'currency_rate_cost'    => numberClean($currency_rate_cost[$key]),
                    'costing_amount_net'    => numberClean($costing_amount_net[$key]),
                    'costing_amount_qar'    => numberClean($costing_amount_qar[$key]),
                    'remarks'               => $remarks[$key],
                    'cost_per_item'         => numberClean($this->input->post('cost_per_item', true)),
                    'created_date'          => date("Y-m-d H:i:s"),

                );
                $costlist[$costindex] = $data2;
                $costindex++;
            }
        }
        // $log = [
        //     'reciept_id' => $stockreciptid,
        //     'purchase_id' => $this->input->post('purchase_id', true),
        //     'ip_address' => getUserIpAddress(),
        //     'performed_by' => $this->session->userdata('id'),
        //     'performed_dt' => date("Y-m-d H:i:s"),
        //     'action_performed' => 'Prepared Purchase Receipt',
        // ];
        // $this->db->insert('purchase_receipt_log', $log);
        
        if(!empty($productlist)){
            $this->db->insert_batch('cberp_purchase_receipt_items', $productlist);
            //update purchase order status erp2024 18-04-2025
            $this->invocies->update_purchase_order_status($purchase_number);
           
        }
        if(!empty($costlist)){
            $this->db->insert_batch('cberp_purchase_receipt_expenses', $costlist); 
        }
        
        // $this->db->insert('cberp_transaction_tracking',['purchase_order_id'=>$this->input->post('purchase_id', true),'purchase_order_number'=>$this->input->post('purchase_tid', true),'purchase_reciept_id'=>$stockreciptid,'purchase_reciept_number'=>$this->input->post('srv', true)]);
        insertion_to_tracking_table('purchase_reciept_id', $stockreciptid, 'purchase_reciept_number', $purchase_reciept_number,'purchase_order_number',$purchase_number);
        $response = array(
            'success' => true,
            'message' => 'Saved successfully'
        );
        echo json_encode($response);
        die();
    }

    public function costingedit()
    {
        $id = $this->input->get('id', true);
        $this->load->model('plugins_model', 'plugins');
        $this->load->library("Common");
        $data['purchaseorderdata'] = $this->costingcalculation->purchase_order_by_srv($id);
        $data['purchasemasterdata'] = $this->costingcalculation->cberp_costing_master_details($id);
        $data['purchaseitemsdata'] = $this->costingcalculation->costing_item_details($id);
        $data['purchaseexpensesdata'] = $this->costingcalculation->cberp_costing_expenses_details($id);
        $this->load->library("Common");
        $data['custom_fields_c'] = $this->custom->add_fields(1);
        $data['exchange'] = $this->plugins->universal_api(5);
        $data['costid'] = $id;
        $data['taxlist'] = $this->common->taxlist($this->config->item('tax'));
        $head['title'] = "Costing Calculation Edit";
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['custom_fields'] = $this->custom->add_fields(2);
        $this->load->view('fixed/header', $head);
        $this->load->view('invoices/costingedit', $data);
        $this->load->view('fixed/footer');
    }
    public function dataoperationeditfrominsert(){
        $purchase_id = $this->input->post('purchase_id', true);
        $stockreciptid = $this->costingcalculation->costing_idby_purchase_id($purchase_id);      
        $costfactor = $this->input->post('cost_factor', true);
        //delete
        $master_data = [
            "salepoint_name" => $this->input->post('salepoint_name', true),
            "salepoint_id" => $this->input->post('salepoint_id', true),
            "supplier_name" => $this->input->post('supplier_name', true),
            "supplier_id" => $this->input->post('supplier_id', true),
            "party_name" => $this->input->post('party_name', true),
            "damageclaim_ac" => $this->input->post('damageclaim_ac', true),
            "damageclaim_ac_name" => $this->input->post('damageclaim_ac_name', true),
            "bill_number" => $this->input->post('bill_number', true),
            "bill_date" => $this->input->post('bill_date', true),
            "currency_id" => $this->input->post('currency_id', true),
            "currency_rate" => $this->input->post('currency_rate', true),
            "bill_description" => $this->input->post('bill_description', true),
            "doctype" => $this->input->post('doctype', true),
            "srv" => $this->input->post('srv', true),
            "srvdate" => $this->input->post('srvdate', true),
            "purchase_amount" => numberClean($this->input->post('purchase_amount', true)),
            "cost_factor" => $costfactor,
            "payment_date" => $this->input->post('payment_date', true),
            "bill_amount" => numberClean($this->input->post('bill_amount', true)),
            "updated_dt" => date("Y-m-d H:i:s"),
            "updated_by" => $this->session->userdata('id')
        ];
        $employee = $this->input->post('employee', true);
        if($employee)
        {
            $master_data['assign_to'] = $employee;
            $master_data['approved_by'] = $this->session->userdata('id');
            $master_data['approved_dt'] = date("Y-m-d H:i:s");
            $master_data['approvalflg'] = '1';
            $master_data['reciept_status'] = 'Assigned';
        }
        if(!empty($master_data) && !empty($this->input->post('salepoint_name', true)) && !empty( $this->input->post('bill_number', true))){
            $this->db->where('id', $stockreciptid);
            $this->db->update('cberp_purchase_receipts', $master_data);
        }
        
        $product_names          =  $this->input->post('product_name', true);
        $product_code           =  $this->input->post('product_code', true);
        $product_unit           =  $this->input->post('product_unit', true);
        $product_qty            =  $this->input->post('product_qty', true);
        $product_qty_recieved   =  $this->input->post('product_qty_recieved', true);
        $product_foc            =  $this->input->post('product_foc', true);
        $damage                 =  $this->input->post('damage', true);
        $price                  =  $this->input->post('price', true);
        $saleprice              =  $this->input->post('saleprice', true);
        $amount                 =  $this->input->post('amount', true); //amountFormat_general
        $discountperc           =  $this->input->post('discountperc', true);
        $discountamount         =  $this->input->post('discountamount', true);
        $netamount              =  $this->input->post('netamount', true);
        $qaramount              =  $this->input->post('qaramount', true);
        $qaramount              =  $this->input->post('qaramount', true);
        $description            =  $this->input->post('description', true);
        $product_id             =  $this->input->post('product_id', true);
        $account_code           =  $this->input->post('account_code', true);
        
        $prodindex=0;
        $productlist =[];
        // echo "<pre>"; print_r($product_qty_recieved); die(); amount
        foreach ($product_names as $key => $value) {
            if(!empty($product_names[$key]) && !empty($stockreciptid))
            {
                $data1 = array(
                    'stockreciptid'          => $stockreciptid,
                    'product_name'           => $product_names[$key],
                    'product_id'             => $product_id[$key],
                    'product_code'           => $product_code[$key],
                    'product_unit'           => $product_unit[$key],
                    'product_qty'            => $product_qty[$key],
                    'product_qty_recieved'   => $product_qty_recieved[$key],
                    'product_foc'            => $product_foc[$key],
                    'damage'                 => $damage[$key],
                    'price'                  => numberClean($price[$key]),
                    'saleprice'              => numberClean($saleprice[$key]),
                    'amount'                 => numberClean($amount[$key]),
                    'discountperc'           => $discountperc[$key],
                    'discountamount'         => numberClean($discountamount[$key]),
                    'netamount'              => numberClean($netamount[$key]),
                    'description'            => $description[$key],
                    'qaramount'              => numberClean($qaramount[$key]),
                    'account_code'              => $account_code[$key],
                    'created_date'           => date("Y-m-d"),
                    'created_dt'             => date("Y-m-d H:i:s")
                );
                if($amount[$key]>0 && $product_qty_recieved[$key]>0)
                {
                    $itemcost = ($amount[$key]/$product_qty_recieved[$key])*$costfactor;
                    $productcost = [
                        'item_cost' => $itemcost,
                        'updated_by' => $this->session->userdata('id'),
                        'updated_dt' => date("Y-m-d H:i:s")

                    ];
                    $this->db->where('product_id', $product_id[$key]);
                    $this->db->update('cberp_product_ai', $productcost);
                }
                $productlist[$prodindex] = $data1;
                $prodindex++;
            }
            
        }
       
        //costing section 
        $expense_name =  $this->input->post('expense_name', true);
        $expense_id   =  $this->input->post('expense_id', true);
        $payable_acc  =  $this->input->post('payable_acc', true);
        $payable_acc_no =  $this->input->post('payable_acc_no', true);
        $bill_number_cost =  $this->input->post('bill_number_cost', true);
        $bill_date_cost =  $this->input->post('bill_date_cost', true);
        $costing_amount =  $this->input->post('costing_amount', true);
        $currency_cost =  $this->input->post('currency_cost', true);
        $currency_rate_cost =  $this->input->post('currency_rate_cost', true);
        $costing_amount_qar =  $this->input->post('costing_amount_qar', true);
        $costing_amount_net =  $this->input->post('costing_amount_net', true);
        $remarks =  $this->input->post('remarks', true);
        $costindex=0;
        $costlist =[];
        foreach ($expense_name as $key => $row) {
            if(!empty($expense_name[$key]) && !empty($stockreciptid))
            {
                $data2 = array(
                    'stockreciptid'         => $stockreciptid,
                    'expense_name'          => $expense_name[$key],
                    'expense_id'            => $expense_id[$key],
                    'payable_acc'           => $payable_acc[$key],
                    'payable_acc_no'        => $payable_acc_no[$key],
                    'bill_number_cost'      => $bill_number_cost[$key],
                    'bill_date_cost'        => $bill_date_cost[$key],
                    'costing_amount'        => numberClean($costing_amount[$key]),
                    'currency_cost'         => $currency_cost[$key],
                    'currency_rate_cost'    => $currency_rate_cost[$key],
                    'costing_amount_net'    => numberClean($costing_amount_net[$key]),
                    'costing_amount_qar'    => numberClean($costing_amount_qar[$key]),
                    'remarks'               => $remarks[$key],
                    'created_date'          => date("Y-m-d"),
                    'created_dt'          => date("Y-m-d H:i:s"),                    
                    'cost_per_item'         => $this->input->post('cost_per_item', true),
    
                );
                $costlist[$costindex] = $data2;
                $costindex++;
            }
        }
        if(!empty($productlist)){
            $this->db->where('stockreciptid', $stockreciptid);
            $this->db->delete('cberp_purchase_receipt_items');
            $this->db->insert_batch('cberp_purchase_receipt_items', $productlist);
           
        }
        if(!empty($costlist)){
            $this->db->where('stockreciptid', $stockreciptid);
            $this->db->delete('cberp_purchase_receipt_expenses');
            $this->db->insert_batch('cberp_purchase_receipt_expenses', $costlist);
        }
        $response = array(
            'success' => true,
            'message' => 'Saved successfully'
        );
        echo json_encode($response);
        die();
    }

    public function dataoperationedit(){
        $costfactor = $this->input->post('cost_factor', true);
        $stockreciptid = $purchase_id = $this->input->post('costmaserid');
        $master_data = [
            "salepoint_name" => $this->input->post('salepoint_name'),
            "salepoint_id" => $this->input->post('salepoint_id', true),
            "supplier_name" => $this->input->post('supplier_name', true),
            "supplier_id" => $this->input->post('supplier_id', true),
            "party_name" => $this->input->post('party_name', true),
            "damageclaim_ac" => $this->input->post('damageclaim_ac', true),
            "damageclaim_ac_name" => $this->input->post('damageclaim_ac_name', true),
            "bill_number" => $this->input->post('bill_number', true),
            // "bill_date" => $this->input->post('bill_date', true),
            "currency_id" => $this->input->post('currency_id', true),
            "currency_rate" => $this->input->post('currency_rate', true),
            "bill_description" => $this->input->post('bill_description', true),
            "doctype" => $this->input->post('doctype', true),
            "srv" => $this->input->post('srv', true),
            // "srvdate" => $this->input->post('srvdate', true),
            "purchase_amount" => numberClean($this->input->post('purchase_amount', true)),
            "bill_amount" => numberClean($this->input->post('bill_amount', true)),
            "cost_factor" => $costfactor,
            // "payment_date" => $this->input->post('payment_date', true),
            "updated_dt" => date("Y-m-d H:i:s"),
            "updated_by" => $this->session->userdata('id'),
            "assign_to"   => $this->input->post('employee', true),
            "approved_by" => $this->session->userdata('id'),
            "approved_dt" => date("Y-m-d H:i:s"),
            "approvalflg" => "1",
            "reciept_status" => "Assigned",
            "note" => $this->input->post('note', true),
        ];

  
        if(!empty($master_data) && !empty($this->input->post('salepoint_name')) && !empty( $this->input->post('bill_number', true))){
            $this->db->where('id', $stockreciptid);
            $this->db->update('cberp_purchase_receipts', $master_data);
            // /////////////////////////////////////////////////////////////////
            $authdata =[];     
            $authdata = [
                'authorized_amount' => numberClean($this->input->post('bill_amount')),
                'status' => "Approve",
                'authorized_date' => date("Y-m-d H:i:s"),
                'authorized_by' => $this->session->userdata('id'),
                'authorized_type' => 'Reported Person',
            ];

            $this->db->where('function_id',$stockreciptid);
            $this->db->where('function_type','Purchase Receipt');
            $this->db->update('authorization_history', $authdata);
            // /////////////////////////////////////////////////////////////////
        }
        
        $product_names          =  $this->input->post('product_name', true);
        $product_code           =  $this->input->post('product_code', true);
        $product_unit           =  $this->input->post('product_unit', true);
        $product_qty            =  $this->input->post('product_qty', true);
        $product_qty_recieved   =  $this->input->post('product_qty_recieved', true);
        $product_foc            =  $this->input->post('product_foc', true);
        $damage                 =  $this->input->post('damage', true);
        $price                  =  $this->input->post('price', true);
        $saleprice              =  $this->input->post('saleprice', true);
        $amount                 =  $this->input->post('amount', true);
        $discountperc           =  $this->input->post('discountperc', true);
        $discountamount         =  $this->input->post('discountamount', true);
        $netamount              =  $this->input->post('netamount', true);
        $qaramount              =  $this->input->post('qaramount', true);
        $qaramount              =  $this->input->post('qaramount', true);
        $description            =  $this->input->post('description', true);
        $product_id             =  $this->input->post('product_id', true);
        $account_code           =  $this->input->post('account_code', true);
        
        $prodindex=0;
        $productlist =[];
        // echo "<pre>"; print_r($product_qty_recieved); die();
        foreach ($product_names as $key => $value) {
            if(!empty($product_names[$key]) && !empty($stockreciptid))
            {
                $data1 = array(
                    'stockreciptid'          => $stockreciptid,
                    'product_name'           => $product_names[$key],
                    'product_id'             => $product_id[$key],
                    'product_code'           => $product_code[$key],
                    'product_unit'           => $product_unit[$key],
                    'product_qty'            => $product_qty[$key],
                    'product_qty_recieved'   => $product_qty_recieved[$key],
                    'product_foc'            => $product_foc[$key],
                    'damage'                 => $damage[$key],
                    'price'                  => numberClean($price[$key]),
                    'saleprice'              => numberClean($saleprice[$key]),
                    'amount'                 => numberClean($amount[$key]),
                    'discountperc'           => $discountperc[$key],
                    'discountamount'         => numberClean($discountamount[$key]),
                    'netamount'              => numberClean($netamount[$key]),
                    'description'            => $description[$key],
                    'qaramount'              => numberClean($qaramount[$key]),
                    'account_code'           => $account_code[$key],
                    'created_date'           => date("Y-m-d"),
                    'created_dt'             => date("Y-m-d H:i:s")
                );
                // if($amount[$key]>0 && $product_qty_recieved[$key]>0)
                // {
                //     $itemcost = ($amount[$key]/$product_qty_recieved[$key])*$costfactor;
                //     $productcost = [
                //         'item_cost' => $itemcost,
                //         'updated_by' => $this->session->userdata('id'),
                //         'updated_dt' => date("Y-m-d H:i:s")

                //     ];
                //     $this->db->where('product_id', $product_id[$key]);
                //     $this->db->update('cberp_product_ai', $productcost);
                // }
                $productlist[$prodindex] = $data1;
                $prodindex++;
            }
            
        }
       
        //costing section 
        $expense_name =  $this->input->post('expense_name', true);
        $expense_id   =  $this->input->post('expense_id', true);
        $payable_acc  =  $this->input->post('payable_acc', true);
        $payable_acc_no =  $this->input->post('payable_acc_no', true);
        $bill_number_cost =  $this->input->post('bill_number_cost', true);
        $bill_date_cost =  $this->input->post('bill_date_cost', true);
        $costing_amount =  $this->input->post('costing_amount', true);
        $currency_cost =  $this->input->post('currency_cost', true);
        $currency_rate_cost =  $this->input->post('currency_rate_cost', true);
        $costing_amount_qar =  $this->input->post('costing_amount_qar', true);
        $costing_amount_net =  $this->input->post('costing_amount_net', true);
        $remarks =  $this->input->post('remarks', true);
        $costindex=0;
        $costlist =[];
        foreach ($expense_name as $key => $row) {
            if(!empty($expense_name[$key]) && !empty($stockreciptid))
            {
                $data2 = array(
                    'stockreciptid'         => $stockreciptid,
                    'expense_name'          => $expense_name[$key],
                    'expense_id'            => $expense_id[$key],
                    'payable_acc'           => $payable_acc[$key],
                    'payable_acc_no'        => $payable_acc_no[$key],
                    'bill_number_cost'      => $bill_number_cost[$key],
                    'bill_date_cost'        => $bill_date_cost[$key],
                    'costing_amount'        => numberClean($costing_amount[$key]),
                    'currency_cost'         => $currency_cost[$key],
                    'currency_rate_cost'    => $currency_rate_cost[$key],
                    'costing_amount_net'    => numberClean($costing_amount_net[$key]),
                    'costing_amount_qar'    => numberClean($costing_amount_qar[$key]),
                    'remarks'               => $remarks[$key],
                    'created_date'          => date("Y-m-d"),
                    'created_dt'          => date("Y-m-d H:i:s"),
                    'cost_per_item'         => $this->input->post('cost_per_item', true)
    
                );
                $costlist[$costindex] = $data2;
                $costindex++;
            }
        }
        $log = [
            'reciept_id' => $stockreciptid,
            'purchase_id' => $this->input->post('purchase_id', true),
            'ip_address' => getUserIpAddress(),
            'performed_by' => $this->session->userdata('id'),
            'performed_dt' => date("Y-m-d H:i:s"),
            'action_performed' => 'Purchase Receipt Assigned to an employee',
        ];
        $this->db->insert('purchase_receipt_log', $log);
        //erp2024 06-01-2025 detailed history log starts
        detailed_log_history('Purchasereceipt',$stockreciptid,'Assigned to an employee', $_POST['changedFields']);
        //erp2024 06-01-2025 detailed history log ends 
        if(!empty($productlist)){
            $this->db->where('stockreciptid', $stockreciptid);
            $this->db->delete('cberp_purchase_receipt_items');
            $this->db->insert_batch('cberp_purchase_receipt_items', $productlist);
           
        }
        if(!empty($costlist)){
            $this->db->where('stockreciptid', $stockreciptid);
            $this->db->delete('cberp_purchase_receipt_expenses');
            $this->db->insert_batch('cberp_purchase_receipt_expenses', $costlist);
        }
        $response = array(
            'success' => true,
            'message' => 'Updated successfully'
        );
        echo json_encode($response);
        die();
    }

    public function stockreciepts(){
        
        $head['title'] = "Purchase Reciepts";    
        // $condition = "WHERE receipt_type = 'Genuine'";
        // $data['counts'] = $this->invocies->get_dynamic_count('cberp_purchase_receipts','created_dt','purchase_amount',$condition);
        $data['ranges'] = getCommonDateRanges();
        $data['counts'] = $this->costingcalculation->get_filter_count($data['ranges']);     
        $data['permissions'] = load_permissions('Stock','Purchase Order','Purchase Reciepts');
        $this->load->view('fixed/header', $head);
        $this->load->view('invoices/stockreciepts', $data);
        $this->load->view('fixed/footer');
    }
    //create invoice
    public function create()
    {
    
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
        
        $data['validity'] = default_validity();
        $data['permissions'] = load_permissions('Accounts','Invoices','Manage Invoices1','View Page');
        $invoice_id = $this->input->get('id');
        $deliverynote_id = $this->input->get('dnid');
        $deliverynote_ids = $this->input->get('dnids');
        $salesorder_id = $this->input->get('salesid');
        $data['paymentmethod_details'] =[];
        $default_print =  get_prefix_73();
        $data['default_print'] = $default_print['default_invoice_print'];
        if($invoice_id)
        {
            $data['trackingdata'] = tracking_details('invoice_id',$invoice_id);
            $data['paymentmethod_details'] = $this->invocies->payment_method_details($invoice_id);
        }    
        else if($deliverynote_id)
        {
            $data['trackingdata'] = tracking_details('deliverynote_id',$deliverynote_id);
        }
        else if($salesorder_id){
            $data['trackingdata'] = tracking_details('sales_id',$salesorder_id);
        }    
        else{
            $data['trackingdata'] = [];
        }
       
        $data['master']=[];
        $data['products']=[];
        $data['created_employee'] = [];
        $data['emp'] = $this->plugins->universal_api(69);
        if ($data['emp']['key1']) {
            $this->load->model('employee_model', 'employee');
            $data['employee'] = $this->employee->list_employee();
        }
        $data['invoiced_id'] = "";
        $data['assigned_customer']  = [];
        if(!empty($invoice_id)){
            $data['invoiced_id'] = $invoice_id;
            $data['master'] = $this->invocies->invoice_details($invoice_id);
            
            $data['invoice'] = $data['master'];
            $data['colorcode'] = get_color_code($data['invoice']['invoiceduedate']);
            $data['products'] = $this->invocies->invoice_products($invoice_id);
            $data['assigned_customer']  = get_customer_details_by_id($data['master']['csd']);
        
            $data['customer'] = $this->invocies->customerByInvoiceid($invoice_id);            
            $data['images'] = get_uploaded_images('Invoice',$invoice_id);  
            $data['returned_status'] = $this->invocies->check_delivered_and_return_qty_equal($invoice_id);
            $data['payment_records'] = $this->invocies->invoice_payments_received($invoice_id);
            $data['journals_records'] = ($data['invoice']['invoice_type']=='Deliverynote') ? $this->invocies->get_deliverynote_invoice_transaction_details($data['invoice']['invoice_number']):$this->invocies->get_invoice_transaction_details($invoice_id);
            $data['merged_deliverynote'] = ($data['invoice']['invoice_type']=='Deliverynote') ? $this->invocies->delnote_by_invoice_number($data['invoice']['invoice_number']):"";
              //erp2024 06-01-2025 detailed history log starts
            $page = "invoice";
            $data['detailed_log']= get_detailed_logs($invoice_id,$page);
            $products = $data['detailed_log'];
            $groupedBySequence = []; 
            foreach ($products as $product) {
                $sequence = $product['seqence_number'];
                $groupedBySequence[$sequence][] = $product; 
            }
            $data['groupedDatas'] = $groupedBySequence;
            if($data['master']['created_by'])
            {
                $data['created_employee'] = employee_details_by_id($data['master']['created_by']);
            }
           
            
            //erp2024 06-01-2025 detailed history log ends
        }
        
        else{            
            $data['lastinvoice'] = $this->invocies->lastinvoice();
        }
        // echo "<pre>"; print_r($data['lastinvoice']); die(); 
        $this->load->library("Common");
        $data['custom_fields_c'] = $this->custom->add_fields(1);

        $this->load->model('customers_model', 'customers');
        $this->load->model('plugins_model', 'plugins');
        $data['exchange'] = $this->plugins->universal_api(5);
        $data['customergrouplist'] = $this->customers->group_list();
        $data['warehouse'] = $this->invocies->warehouses();
        $data['terms'] = $this->invocies->billingterms();
        $data['currency'] = $this->invocies->currencies();
        $data['taxlist'] = $this->common->taxlist($this->config->item('tax'));
        $head['title'] = "Invoice";
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['taxdetails'] = $this->common->taxdetail();
        $data['custom_fields'] = $this->custom->add_fields(2); 
        $data['prefix'] = $this->configurations['invoiceprefix'];   
        $data['configurations'] = $this->configurations;
        $this->load->view('fixed/header', $head);
        $this->load->model('deliverynote_model', 'deliverynote');
        $data['create_type'] = "";
        $data['deliverynote_number']="";
        $data['convert_type']="";
        if($deliverynote_id)
        {
            $data['master'] = $this->deliverynote->deliverynoteby_number($deliverynote_id);
            $data['convert_type'] = "Single";
            $data['products'] = $this->deliverynote->deliverynote_products($deliverynote_id);
            $data['deliverynote_number'] = $data['master']['delivery_note_number'];
            //  echo "<pre>"; print_r($data['master']); die();
            $data['customer'] = $this->invocies->customerByDeliverynoteid($deliverynote_id);
            $this->load->view('invoices/newinvoice_deliverynote', $data);
            
            
        }
        else if($deliverynote_ids)
        {
            $data['convert_type'] = "Multiple";
            $selected_delivery_notes =  $this->session->userdata('selecteddelnoteids');
            // $data['master'] = $this->deliverynote->deliverynoteby_number($deliverynote_id);
            // $data['products'] = $this->deliverynote->deliverynote_products($deliverynote_id);
            $data['master'] = $this->deliverynote->deliverynotedetails_byid_for_multiple($selected_delivery_notes);
            $data['products'] = $data['master'];
            $data['store_id'] = $data['master'][0]['store_id'];
            $data['orderamount'] = $this->deliverynote->order_amount_total_by_delivery_note_ids($selected_delivery_notes);
            $data['customer'] = $this->invocies->customerByDeliverynoteid($selected_delivery_notes[0]);
            // echo "<pre>"; print_r($data['products']); die();
            $this->load->view('invoices/newinvoice_deliverynote', $data);
            
        }
        else{
            $data['create_type'] = "direct";
            $data['convert_type'] = "";
            $this->load->view('invoices/newinvoice_deliverynote', $data);
            // $this->load->view('invoices/newinvoice', $data);
        }
        $this->load->view('fixed/footer');
    }
    //convert invoice
    public function convert_salesorder_to_invoice()
    {

        $data['permissions'] = load_permissions('Accounts','Invoices','New Invoice1');
        $salesorder_id = $this->input->get('id');
        $data['salesorder_id'] = $salesorder_id;
        $data['master']=[];
        $data['products']=[];
        
        $data['emp'] = $this->plugins->universal_api(69);
        if ($data['emp']['key1']) {
            $this->load->model('employee_model', 'employee');
            $data['employee'] = $this->employee->list_employee();
        }

        $this->load->model('SalesOrder_model', 'salesorder');
        $data['master'] = $this->salesorder->salesorder_details($salesorder_id);
        $data['products'] = $this->invocies->salesorder_products($salesorder_id);
        $data['lastinvoice'] = $this->invocies->lastinvoice();
        $data['customer'] = $this->invocies->customerBySalesorderid($salesorder_id);
        // echo "<pre>"; print_r($data['lastinvoice']); die(); 
        $this->load->library("Common");
        $data['custom_fields_c'] = $this->custom->add_fields(1);

        $this->load->model('customers_model', 'customers');
        $this->load->model('plugins_model', 'plugins');
        $data['exchange'] = $this->plugins->universal_api(5);
        $data['customergrouplist'] = $this->customers->group_list();
        $data['warehouse'] = $this->invocies->warehouses();
        $data['terms'] = $this->invocies->billingterms();
        $data['currency'] = $this->invocies->currencies();
        $data['taxlist'] = $this->common->taxlist($this->config->item('tax'));
        $head['title'] = "New Invoice";
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['taxdetails'] = $this->common->taxdetail();
        $data['custom_fields'] = $this->custom->add_fields(2); 
        $data['prefix'] = $this->configurations['invoiceprefix'];   
        $data['configurations'] = $this->configurations;
        $this->load->view('fixed/header', $head);
        $this->load->view('invoices/convert_salesorder_to_invoice', $data);
        $this->load->view('fixed/footer');
    }



    public function customerenquiryaction()
    {

            
        
        if ($this->input->server('REQUEST_METHOD') === 'POST') {

            $alreadyxists = 1;
            $this->db->where('lead_number', $this->input->post('lead_number'));
            $query = $this->db->get('cberp_customer_leads');

            if ($query->num_rows() > 0) {
                $alreadyxists = 0;
            }
            if($alreadyxists==1)
            {   
                       
                $customer_id = $this->input->post('customer_id');
                if($this->input->post('customerType')=='new'){
                    $customer_data = array(
                        'name' => $this->input->post('customer_name'),
                        'phone' => $this->input->post('customer_phone'),
                        'email' => $this->input->post('customer_email'),
                        'address' => $this->input->post('customer_address')
                    );
                    $customer_id = $this->invocies->create_customer($customer_data);
                }
                $assigned_to = ($this->input->post('assignedto'))?$this->input->post('assignedto'):'';
                $lead_number = $this->prifix72['lead_prefix'].$this->invocies->lastenquiry();
                $enquiry_data = array(
                    'lead_number' => $lead_number,
                    'customer_type' => $this->input->post('customerType'),
                    'customer_id' => $customer_id,
                    'date_received' => $this->input->post('date_received'),
                    'due_date' => $this->input->post('due_date'),
                    'source_of_enquiry' => $this->input->post('source_of_enquiry'),
                    'assigned_to' => $assigned_to,
                    'note' => $this->input->post('note'),
                    'email_contents' => $this->input->post('email_contents'),
                    'enquiry_status' => 'Completed',
                    // 'enquiry_status' => $this->input->post('enquiry_status'),
                    'created_by' => $this->session->userdata('id'),
                    'created_date' => date('Y-m-d H:i:s'),
                    'customer_reference_number' => $this->input->post('customer_reference_number'),
                    'customer_contact_person' => $this->input->post('customer_contact_person'),
                    'customer_contact_number' => $this->input->post('customer_contact_number'),
                    'customer_contact_email' => $this->input->post('customer_contact_email'),
                      
                );
           
                
                if(!empty($enquiry_data)){
                    $this->db->insert('cberp_customer_leads', $enquiry_data);
                    $enquiryid = $this->db->insert_id();      
                    $module_number = get_module_details_by_name('CRM');
                    if ($assigned_to) {
                        $users_list = [];
                        $users_list[0]['user_id'] = $assigned_to;
                    } else {
                        $users_list = linked_user_module_approvals_by_module_number($module_number);
                    }        
                    $target_url = base_url("invoices/customer_leads?id=$enquiryid");        
                    $message = "Please Proccess the Lead: (".$lead_number.")";            
                    $message_caption = "Please Proccess the Lead (".$lead_number.")";
                    send_message_to_users($users_list,$target_url,$message_caption,$message,$this->input->post('due_date'));
                    
                    //data added to log                  
                    // erp2025 09-01-2025 starts
                    detailed_log_history('Lead',$enquiryid,'Created', $_POST['changedFields']);	
                      // erp2025 09-01-2025 starts
                    insertion_to_tracking_table('lead_id',$enquiryid,'lead_number',$lead_number);
                    // erp2024 10-07-2024 add items starts
                    $pid = $this->input->post('pid');
                    $invocieno = $enquiryid;
                    $productlist = array();
                    $customerdata_details= [];
                    $prodindex = 0;
                    $itc = 0;
                    $i=0;
                    $grandtotal = 0;
                    $flag = false;
                    $product_id = $this->input->post('pid');
                    $product_name1 = $this->input->post('product_name', true);
                    $code = $this->input->post('code', true);
                    $product_qty = $this->input->post('product_qty');
                    $product_price = $this->input->post('product_price');
                    $product_tax = $this->input->post('product_tax');
                    $product_discount = $this->input->post('product_discount');
                    $product_amt = $this->input->post('product_amt');
                    $product_subtotal = $this->input->post('product_subtotal');
                    $ptotal_tax = $this->input->post('taxa');
                    $ptotal_disc = $this->input->post('disca');
                    // $product_des = $this->input->post('product_description', true);
                    $product_hsn = $this->input->post('hsn');
                    $discount_type = $this->input->post('discount_type');
                    $product_unit = $this->input->post('unit');
                    $min_price = $this->input->post('lowest_price');
                    $max_disrate = $this->input->post('maxdiscountrate');
                    foreach ($pid as $key => $value) {
                        if($product_name1[$key])
                        {
                            $prdsubtotal = rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc);
                            $grandtotal = $grandtotal + $prdsubtotal;
                            $total_discount += numberClean(@$ptotal_disc[$key]);
                            $total_tax += numberClean($ptotal_tax[$key]);
                            if($discount_type[$key]=="Amttype"){
                                $discountamount = numberClean($product_amt[$key]);
                            }
                            else{
                                $discountamount = numberClean($product_discount[$key]);
                            }
                            $data = array(
                                'lead_id' => $invocieno,
                                'product_code' => $product_hsn[$key],
                                'quantity' => numberClean($product_qty[$key]),
                                'price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
                                'tax' => numberClean($product_tax[$key]),
                                'discount' => $discountamount,
                                'subtotal' => $prdsubtotal,
                                'total_tax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                                'total_discount' => rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc),
                                // 'product_des' => $product_des[$key],
                                'discount_type' => $discount_type[$key],
                                'lowest_price' => $min_price[$key],
                                'maximum_discount_rate' => $max_disrate[$key],
                            );

                            // $customerdata_details['lead_id'] = ($enquiryid+1000);
                            // $customerdata_details['product_id'] = (int)$product_id[$key];
                            // $customerdata_details['product_qty'] = (int)numberClean($product_qty[$key]);
                            // $this->db->insert('customer_enquiry_items', $customerdata_details);
                            $flag = true;
                            $productlist[$prodindex] = $data;
                            $i++;
                            $prodindex++;
                            $amt = numberClean($product_qty[$key]);
                            $itc += $amt;
                        }
                    }
                    if ($prodindex > 0) {
                        $this->db->insert_batch('cberp_customer_lead_items', $productlist);
                        $this->db->where('lead_id', $enquiryid);
                        $this->db->update('cberp_customer_leads', ['total' => $grandtotal]);
                    } else {
                        echo json_encode(array('status' => 'Error', 'message' =>
                            "Please choose product from product list. Go to Item manager section if you have not added the products."));
                        $transok = false;
                    }
                    
                    // erp2024 10-07-2024 add items ends

                    $config['upload_path'] = FCPATH . 'uploads/';
                    $config['allowed_types'] = 'pdf|jpg|jpeg|png|csv|xls|xlsx';
                    $config['encrypt_name'] = TRUE;
                    $this->load->library('upload', $config);
                    if (isset($_FILES['upfile'])) {
                        $files = $_FILES['upfile'];
                        if(!empty($files))
                        {
                            $uploaded_data['lead_id'] = $enquiryid;
                            foreach ($files['name'] as $key => $filename) {
                                $_FILES['userfile']['name'] = $files['name'][$key];
                                $_FILES['userfile']['type'] = $files['type'][$key];
                                $_FILES['userfile']['tmp_name'] = $files['tmp_name'][$key];
                                $_FILES['userfile']['error'] = $files['error'][$key];
                                $_FILES['userfile']['size'] = $files['size'][$key];
                                $uploaded_data['actual_name'] = $files['name'][$key];
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
                    
                }
                header('Content-Type: application/json');
                                // $this->invocies->get_enquiry_count();  
                $response = array(
                    'success' => true,
                    'message' => 'Updated successfully'
                );
                echo json_encode($response);             

            }
            
            
        }
        // $this->leads();
    }
    public function customerenquiryeditaction(){
        
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $enquiry_status = $this->input->post('enquiry_status');
            $email_contents = $this->input->post('email_contents');
            if ($email_contents == '<p><br></p>') {
                $email_contents = '';
            }
          
            $enquiry_data = array(
                'customer_type' => $this->input->post('customerType'),
                'date_received' => $this->input->post('date_received')." ".date('H:i:s'),
                'due_date' => $this->input->post('due_date'),
                'source_of_enquiry' => $this->input->post('source_of_enquiry'),
                'assigned_to' => ($this->input->post('enquiry_status')=='Assigned')?$this->input->post('assignedto'):'',
                'comments' => ($this->input->post('enquiry_status')=='Closed')?$this->input->post('comments'):'',
                'note' => $this->input->post('note'),
                'email_contents' => $email_contents,
                'enquiry_status' => $enquiry_status,
                'updated_by' => $this->session->userdata('id'),
                'updated_date' => date('Y-m-d H:i:s'),
                'pickup_flag' => '0',
                'pickup_date' => NULL,
                'picked_by' => '',
                'enquiry_status' => 'Completed',
                'customer_reference_number' => $this->input->post('customer_reference_number'),
                'customer_contact_person' => $this->input->post('customer_contact_person'),
                'customer_contact_number' => $this->input->post('customer_contact_number'),
                'customer_contact_email' => $this->input->post('customer_contact_email')
            );
            if(!empty($enquiry_data)){
                $lead_id = $this->input->post('lead_id');
                $this->db->where('lead_id', $lead_id);
                $this->db->update('cberp_customer_leads', $enquiry_data);
                // die($this->db->last_query());
                //data added to log    
                // $tablevalue = ($this->input->post('employeeassign')==1)? "Lead assigned to an employee": "Updated";             
                // master_table_log('customer_general_enquiry_log',$lead_id,$tablevalue);

                //parameters - pagename,item_no,actionname
                $sequence_number = detailed_log_history('Lead',$lead_id,'Updated', $_POST['changedFields']);

                $config['upload_path'] = FCPATH . 'uploads/';
                $config['allowed_types'] = 'pdf|jpg|jpeg|png|csv|xls|xlsx';
                $config['encrypt_name'] = TRUE;
                $this->load->library('upload', $config);
                $files = $_FILES['upfile']; // Get uploaded files array
                
                // ==================================================//
                //Product Data
                $pid = $this->input->post('pid');
                $productlist = array();
                $customerdata_details =[];
                $prodindex = 0;     
                $grandtotal = 0;           
                $product_id = $this->input->post('pid');
                $product_name1 = $this->input->post('product_name', true);
                $code = $this->input->post('code', true);
                $product_qty = $this->input->post('product_qty');
                $product_price = $this->input->post('product_price');
                $product_tax = $this->input->post('product_tax');
                $product_discount = $this->input->post('product_discount');
                $product_amt = $this->input->post('product_amt');
                $product_subtotal = $this->input->post('product_subtotal');
                $ptotal_tax = $this->input->post('taxa');
                $ptotal_disc = $this->input->post('disca');
                $product_hsn = $this->input->post('hsn');
                $product_unit = $this->input->post('unit');
                $discount_type = $this->input->post('discount_type');
                $min_price = $this->input->post('lowest_price');
                $max_disrate = $this->input->post('maxdiscountrate');

                // delete_product_log('cberp_customer_lead_items','Lead',$lead_id,$product_id,$sequence_number);
                
                $this->db->delete('cberp_customer_lead_items', array('lead_id' => $lead_id));
                if(!empty($pid))
                {
                    foreach ($pid as $key => $value) {
                        $prdsubtotal = rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc);
                        $grandtotal = $grandtotal + $prdsubtotal;
                        $total_discount += numberClean(@$ptotal_disc[$key]);
                        $total_tax += numberClean($ptotal_tax[$key]);

                        if($discount_type[$key]=="Amttype"){
                            $discountamount = numberClean($product_amt[$key]);
                        }
                        else{
                            $discountamount = numberClean($product_discount[$key]);
                        }

                        $data = array(
                            'lead_id' => $lead_id,
                            'product_code' => $product_hsn[$key],
                            'quantity' => numberClean($product_qty[$key]),
                            'price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
                            'tax' => numberClean($product_tax[$key]),
                            'discount' => $discountamount,
                            'subtotal' => rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc),
                            'total_tax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                            'total_discount' => rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc),
                            //'product_des' => $product_des[$key],
                            //'unit' => $product_unit[$key]
			                'discount_type' => $discount_type[$key],
                            'lowest_price' => $min_price[$key],
                            'maximum_discount_rate' => $max_disrate[$key],
                            
                        );

                        $flag = true;
                        $productlist[$prodindex] = $data;
                        $i += numberClean($product_qty[$key]);;
                        $prodindex++;
                        // $customerdata_details['lead_id'] = $customer_enqid;
                        // $customerdata_details['product_id'] = (int)$product_id[$key];
                        // $customerdata_details['product_qty'] = (int)numberClean($product_qty[$key]);
                        // $this->db->insert('customer_enquiry_items', $customerdata_details);
                    }
                    if(!empty($productlist)){
                        $this->db->insert_batch('cberp_customer_lead_items', $productlist);
                    }
                    
                    // $this->db->where('lead_id', $lead_id);
                    // $this->db->update('cberp_customer_leads', ['total' => $grandtotal]);
                    
                }
                $this->db->where('lead_id', $lead_id);
                $this->db->update('cberp_customer_leads', ['total' => $grandtotal]);
                // ==================================================//

                if(!empty($files))
                {
                    $uploaded_data['lead_id'] = $lead_id;
                    foreach ($files['name'] as $key => $filename) {
                        $_FILES['userfile']['name'] = $files['name'][$key];
                        $uploaded_data['actual_name'] = $files['name'][$key];
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
                
                // $this->leads();

            }
            
        }
    }

    // erp2024 03-01-2025
    public function customerenquiry_draft_action(){
       
        if ($this->input->server('REQUEST_METHOD') === 'POST') {

            $alreadyxists = 1;
            $this->db->where('lead_number', $this->input->post('lead_number'));
            $query = $this->db->get('cberp_customer_leads');
            if ($query->num_rows() > 0) {
                $alreadyxists = 0;
            }
            if($alreadyxists==1)
            {   
                
                $customer_id = $this->input->post('customer_id');
                if($this->input->post('customerType')=='new'){
                    $customer_data = array(
                        'name' => $this->input->post('customer_name'),
                        'phone' => $this->input->post('customer_phone'),
                        'email' => $this->input->post('customer_email'),
                        'address' => $this->input->post('customer_address')
                    );
                    $customer_id = $this->invocies->create_customer($customer_data);
                }
                $enquiry_data = array(
                    'lead_number' => $this->input->post('lead_number'),
                    'customer_type' => $this->input->post('customerType'),
                    'customer_id' => $customer_id,
                    'date_received' => $this->input->post('date_received'),
                    'due_date' => $this->input->post('due_date'),
                    'source_of_enquiry' => $this->input->post('source_of_enquiry'),
                    'assigned_to' => ($this->input->post('enquiry_status')=='Assigned')?$this->input->post('assignedto'):'',
                    'note' => $this->input->post('note'),
                    'email_contents' => $this->input->post('email_contents'),
                    'enquiry_status' => 'Draft',
                    'created_by' => $this->session->userdata('id'),
                    'created_date' => date('Y-m-d'),
                    'customer_reference_number' => $this->input->post('customer_reference_number'),
                    'customer_contact_person' => $this->input->post('customer_contact_person'),
                    'customer_contact_number' => $this->input->post('customer_contact_number'),
                    'customer_contact_email' => $this->input->post('customer_contact_email')
                      
                );
                
                if(!empty($enquiry_data)){
                    $this->db->insert('cberp_customer_leads', $enquiry_data);
                    $enquiryid = $this->db->insert_id();   
                    
                   
                    //data added to log                  
                    //erp2024 06-01-2025 detailed history log starts
                    detailed_log_history('Lead',$enquiryid,'Data saved as draft', $changedFields);
                    //erp2024 06-01-2025 detailed history log ends 

                    // erp2024 10-07-2024 add items starts
                    $pid = $this->input->post('pid');
                    $invocieno = $enquiryid;
                    $productlist = array();
                    $customerdata_details= [];
                    $prodindex = 0;
                    $itc = 0;
                    $i=0;
                    $grandtotal = 0;
                    $flag = false;
                    $product_id = $this->input->post('pid');
                    $product_name1 = $this->input->post('product_name', true);
                    $code = $this->input->post('code', true);
                    $product_qty = $this->input->post('product_qty');
                    $product_price = $this->input->post('product_price');
                    $product_tax = $this->input->post('product_tax');
                    $product_discount = $this->input->post('product_discount');
                    $product_amt = $this->input->post('product_amt');
                    $product_subtotal = $this->input->post('product_subtotal');
                    $ptotal_tax = $this->input->post('taxa');
                    $ptotal_disc = $this->input->post('disca');
                    // $product_des = $this->input->post('product_description', true);
                    $product_hsn = $this->input->post('hsn');
                    $discount_type = $this->input->post('discount_type');
                    $product_unit = $this->input->post('unit');
                    $min_price = $this->input->post('lowest_price');
                    $max_disrate = $this->input->post('maxdiscountrate');
                    foreach ($pid as $key => $value) {
                        $prdsubtotal = rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc);
                        $grandtotal = $grandtotal + $prdsubtotal;
                        $total_discount += numberClean(@$ptotal_disc[$key]);
                        $total_tax += numberClean($ptotal_tax[$key]);
                        if($discount_type[$key]=="Amttype"){
                            $discountamount = numberClean($product_amt[$key]);
                        }
                        else{
                            $discountamount = numberClean($product_discount[$key]);
                        }
                        $data = array(
                            'lead_id' => $invocieno,
                            'product_code' => $product_hsn[$key],
                            'quantity' => numberClean($product_qty[$key]),
                            'price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
                            'tax' => numberClean($product_tax[$key]),
                            'discount' => $discountamount,
                            'subtotal' => $prdsubtotal,
                            'total_tax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                            'total_discount' => rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc),
                            // 'product_des' => $product_des[$key],
                            'discount_type' => $discount_type[$key],
                            'lowest_price' => $min_price[$key],
                            'maximum_discount_rate' => $max_disrate[$key],
                        );

                        // $customerdata_details['lead_id'] = ($enquiryid+1000);
                        // $customerdata_details['product_id'] = (int)$product_id[$key];
                        // $customerdata_details['product_qty'] = (int)numberClean($product_qty[$key]);
                        // $this->db->insert('customer_enquiry_items', $customerdata_details);
                        $flag = true;
                        $productlist[$prodindex] = $data;
                        $i++;
                        $prodindex++;
                        $amt = numberClean($product_qty[$key]);
                        $itc += $amt;
                    }
                    if ($prodindex > 0) {
                        $this->db->insert_batch('cberp_customer_lead_items', $productlist);
                        $this->db->where('lead_id', $enquiryid);
                        $this->db->update('cberp_customer_leads', ['total' => $grandtotal]);
                    } else {
                        echo json_encode(array('status' => 'Error', 'message' =>
                            "Please choose product from product list. Go to Item manager section if you have not added the products."));
                        $transok = false;
                    }
                   
                    // erp2024 10-07-2024 add items ends

                    $config['upload_path'] = FCPATH . 'uploads/';
                    $config['allowed_types'] = 'pdf|jpg|jpeg|png|csv|xls|xlsx';
                    $config['encrypt_name'] = TRUE;
                    $this->load->library('upload', $config);
                    if (isset($_FILES['upfile'])) {
                        $files = $_FILES['upfile'];
                        if(!empty($files))
                        {
                            $uploaded_data['lead_id'] = $enquiryid;
                            // $uploaded_data['lead_number'] = $this->input->post('lead_number');
                            foreach ($files['name'] as $key => $filename) {
                                $_FILES['userfile']['name'] = $files['name'][$key];
                                $_FILES['userfile']['type'] = $files['type'][$key];
                                $_FILES['userfile']['tmp_name'] = $files['tmp_name'][$key];
                                $_FILES['userfile']['error'] = $files['error'][$key];
                                $_FILES['userfile']['size'] = $files['size'][$key];
                                $uploaded_data['actual_name'] = $files['name'][$key];
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
                    header('Content-Type: application/json');
                    echo json_encode(array('status' => 'Success', 'data' =>$enquiryid));
                }
                // $this->invocies->get_enquiry_count();               

            }
            // $this->leads();
            
        }
    }

    public function customerenquiry_draft_edit_action(){
        
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $enquiry_status = $this->input->post('enquiry_status');
            $email_contents = $this->input->post('email_contents');
            if ($email_contents == '<p><br></p>') {
                $email_contents = '';
            }
          
            $enquiry_data = array(
                'lead_number' => $this->input->post('lead_number'),
                'customer_type' => $this->input->post('customerType'),
                'date_received' => $this->input->post('date_received'),
                'due_date' => $this->input->post('due_date'),
                'source_of_enquiry' => $this->input->post('source_of_enquiry'),
                // 'assigned_to' => ($this->input->post('enquiry_status')=='Assigned')?$this->input->post('assignedto'):'',
                'comments' => ($this->input->post('enquiry_status')=='Closed')?$this->input->post('comments'):'',
                'note' => $this->input->post('note'),
                'email_contents' => $email_contents,
                'enquiry_status' => 'Draft',
                'updated_by' => $this->session->userdata('id'),
                'updated_date' => date('Y-m-d'),
                'pickup_flag' => '0',
                'pickup_date' => NULL,
                'picked_by' => '',
                'customer_reference_number' => $this->input->post('customer_reference_number'),
                'customer_contact_person' => $this->input->post('customer_contact_person'),
                'customer_contact_number' => $this->input->post('customer_contact_number'),
                'customer_contact_email' => $this->input->post('customer_contact_email')
            );
            if(!empty($enquiry_data)){
                $lead_id = $this->input->post('lead_id');
                $this->db->where('lead_id', $lead_id);
                $this->db->update('cberp_customer_leads', $enquiry_data);

                //data added to log    
               
                // master_table_log('customer_general_enquiry_log',$lead_id,'Lead saved as draft');

                $config['upload_path'] = FCPATH . 'uploads/';
                $config['allowed_types'] = 'pdf|jpg|jpeg|png|csv|xls|xlsx';
                $config['encrypt_name'] = TRUE;
                $this->load->library('upload', $config);
                $files = $_FILES['upfile']; // Get uploaded files array
                
                // ==================================================//
                //Product Data
                $pid = $this->input->post('pid');
                $productlist = array();
                $customerdata_details =[];
                $prodindex = 0;     
                $grandtotal = 0;           
                $product_id = $this->input->post('pid');
                $product_name1 = $this->input->post('product_name', true);
                $code = $this->input->post('code', true);
                $product_qty = $this->input->post('product_qty');
                $product_price = $this->input->post('product_price');
                $product_tax = $this->input->post('product_tax');
                $product_discount = $this->input->post('product_discount');
                $product_amt = $this->input->post('product_amt');
                $product_subtotal = $this->input->post('product_subtotal');
                $ptotal_tax = $this->input->post('taxa');
                $ptotal_disc = $this->input->post('disca');
                $product_des = $this->input->post('product_description', true);
                $product_hsn = $this->input->post('hsn');
                $product_unit = $this->input->post('unit');
                $discount_type = $this->input->post('discount_type');
                $min_price = $this->input->post('lowest_price');
                $max_disrate = $this->input->post('maxdiscountrate');
                $this->db->delete('cberp_customer_lead_items', array('tid' => $lead_id));
                if(!empty($pid))
                {
                    // $this->db->delete('cberp_customer_lead_items', array('tid' => $lead_id));
                    $customer_enqid = $this->invocies->customer_leadid_by_id($lead_id);
                    if(!empty($customer_enqid)){
                        $this->db->delete('customer_enquiry_items', array('lead_id' => $customer_enqid));
                    }
                    foreach ($pid as $key => $value) {
                        $prdsubtotal = rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc);
                        $grandtotal = $grandtotal + $prdsubtotal;
                        $total_discount += numberClean(@$ptotal_disc[$key]);
                        $total_tax += numberClean($ptotal_tax[$key]);

                        if($discount_type[$key]=="Amttype"){
                            $discountamount = numberClean($product_amt[$key]);
                        }
                        else{
                            $discountamount = numberClean($product_discount[$key]);
                        }

                        $data = array(
                            'lead_id' => $lead_id,
                            'product_code' => $product_hsn[$key],
                            'quantity' => numberClean($product_qty[$key]),
                            'price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
                            'tax' => numberClean($product_tax[$key]),
                            'discount' => $discountamount,
                            'subtotal' => rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc),
                            'total_tax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                            'total_discount' => rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc),
                            //'product_des' => $product_des[$key],
                            //'unit' => $product_unit[$key]
			                'discount_type' => $discount_type[$key],
                            'lowest_price' => $min_price[$key],
                            'maximum_discount_rate' => $max_disrate[$key],
                            
                        );

                        $flag = true;
                        $productlist[$prodindex] = $data;
                        $i += numberClean($product_qty[$key]);;
                        $prodindex++;
                        // $customerdata_details['lead_id'] = $customer_enqid;
                        // $customerdata_details['product_id'] = (int)$product_id[$key];
                        // $customerdata_details['product_qty'] = (int)numberClean($product_qty[$key]);
                        // $this->db->insert('customer_enquiry_items', $customerdata_details);
                    }
                    if(!empty($productlist)){
                        $this->db->insert_batch('cberp_customer_lead_items', $productlist);
                    }
                    
                    // $this->db->where('lead_id', $lead_id);
                    // $this->db->update('cberp_customer_leads', ['total' => $grandtotal]);
                    
                }
                $this->db->where('lead_id', $lead_id);
                $this->db->update('cberp_customer_leads', ['total' => $grandtotal]);
                // ==================================================//

                if(!empty($files))
                {
                    $uploaded_data['lead_id'] = $lead_id;
                    foreach ($files['name'] as $key => $filename) {
                        $_FILES['userfile']['name'] = $files['name'][$key];
                        $uploaded_data['actual_name'] = $files['name'][$key];
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
                detailed_log_history('Lead',$lead_id,'Data saved as draft', $_POST['changedFields']);
                header('Content-Type: application/json');
                echo json_encode(array('status' => 'Success', 'data' =>$enquiryid));

            }
            
        }
    }


    public function deletesubItem(){
        $lead_attachment_id = $this->input->post('selectedProducts');
        $name = $this->input->post('image');
        $this->db->where('lead_attachment_id', $lead_attachment_id);
        $this->db->delete('cberp_customer_lead_attachments');
        unlink(FCPATH . 'uploads/' . $name);
        echo json_encode(array('status' => '1', 'message' => "Success"));
    }
    
    public function leads()
    {          
        $head['title'] = "Leads";
        $data['employees']  = employee_list();
        $data['customers']  = customer_list();
        $this->load->view('fixed/header', $head);        
        $data['permissions'] = load_permissions('CRM','Leads','Manage Lead','List');
        // echo "<pre>"; print_r($data['permissions']); die();
        $data['ranges'] = getCommonDateRanges();
        $data['enquirycounts'] =  $this->invocies->get_enquiry_count($data['ranges']);
        $this->load->view('enquiry/leads',$data);
        $this->load->view('fixed/footer');
    }
   


    //create customer enquiry
    public function newcustomerenquiry()
    {
        $data['prefix'] = $this->prifix72['lead_prefix'];  
        $this->load->model('employee_model', 'employee');
        $data['employee'] = $this->employee->list_employee();
        $this->load->library("Common");
        $data['custom_fields_c'] = $this->custom->add_fields(1);
        $this->load->model('customers_model', 'customers');
        $this->load->model('plugins_model', 'plugins');
        $data['exchange'] = $this->plugins->universal_api(5);
        $data['customergrouplist'] = $this->customers->group_list();
        $data['lastenquirynumber'] = $this->invocies->lastenquiry();
        $data['taxlist'] = $this->common->taxlist($this->config->item('tax'));
        $head['title'] = "New Enquiry";
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['taxdetails'] = $this->common->taxdetail();
        $data['custom_fields'] = $this->custom->add_fields(2);
        $data['configurations'] = $this->configurations;
        $this->load->view('fixed/header', $head);
        $this->load->view('invoices/newcustomerenquiry', $data);
        $this->load->view('fixed/footer');
    }
    public function customer_leads()
    {      
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);  lastenquirynumber

        
        $module_number = get_module_details_by_name('CRM');
        // $data['your_approval_level'] =  linked_user_module_approvals_by_module_number($module_number,$this->session->userdata('id'));
        $data['approval_level_users'] =  module_level_users($module_number);
        // echo "<pre>"; print_r($data['approval_level_users']); 
        // die();
        $data['validity'] = default_validity();       
        $this->load->library("Common");
        $data['custom_fields_c'] = $this->custom->add_fields(1);
        $this->load->model('customers_model', 'customers');
        $this->load->model('plugins_model', 'plugins');
        $this->load->model('employee_model', 'employee');
        $head['title'] = "Enquiries";
        $tid = intval($this->input->get('id'));
        $data['configurations'] = $this->configurations;
        $data['assigned_customer'] =[];
        if($tid)
        {
            $data['permissions'] = load_permissions('CRM','Leads','Manage Lead','View Page');
            
            $data['enquirymain'] = $this->customer_enquiry->enquiry_details($tid);
            $pickedby =  $data['enquirymain']['picked_by'];
            $data['assigned_customer']  = get_customer_details_by_id($data['enquirymain']['customer_id']);   
            $data['colorcode'] = get_color_code($data['enquirymain']['due_date']); 
            if(!empty($pickedby)){
                $data['pickedperson'] = $this->employee->employee_details($pickedby);                       
             
            }
            else{
                $pickupdata = [
                    'pickup_flag' => "1",
                    'pickup_date'  => date('Y-m-d H:i:s'),
                    'picked_by'  => $this->session->userdata('id')
                ];
                $this->db->where('lead_id', $tid);
                $this->db->update('cberp_customer_leads', $pickupdata);
            } 
          
            $data['employee'] = $this->employee->list_employee();            
            $enqID = $data['enquirymain']['lead_number']; 
            $employee_name = "";
            if(!empty($data['enquirymain']['assigned_to']) && $data['enquirymain']['assigned_to'] > 0)
            {
                $empname = $this->employee->reporting_emp_byid($data['enquirymain']['assigned_to']);
                $employee_name = $empname['name'];
            }
            
            $data['employee_name1'] = $employee_name;
            $data['images'] = $this->customer_enquiry->enquiry_details_table($tid);
            $data['products'] = $this->customer_enquiry->lead_products($tid);
            $data['taxdetails'] = $this->common->taxdetail();
            $data['customergrouplist'] = $this->customers->group_list();
            $data['lastenquirynumber'] = $this->invocies->lastenquiry();
            $data['taxlist'] = $this->common->taxlist($this->config->item('tax'));
            if($data['enquirymain']['enquiry_status']=="Closed"){
                $head['title'] = "Lead #" . $enqID;
            }
            else{
                $head['title'] = "Edit Lead #" . $enqID;
            }
            // erp2025 09-01-2025 start
            $page = "Lead";
            $data['detailed_log']= get_detailed_logs($tid,$page);
            $products = $data['detailed_log'];
            
            $groupedBySequence = []; 
            foreach ($products as $product) {
                $sequence = $product['seqence_number'];
                $groupedBySequence[$sequence][] = $product; 
            }
            $data['groupedDatas'] = $groupedBySequence;            
            $data['trackingdata'] = tracking_details('lead_id',$tid);
        }
        else{
            $data['permissions'] = load_permissions('CRM','Leads','New Lead');
            $data['enquirymain'] = $this->customer_enquiry->enquiry_details(0);
            
            $data['prefix'] = $this->prifix72['lead_prefix'];  
            $this->load->model('employee_model', 'employee');
            $data['employee'] = $this->employee->list_employee();
            $this->load->library("Common");
            $data['custom_fields_c'] = $this->custom->add_fields(1);
            $this->load->model('customers_model', 'customers');
            $this->load->model('plugins_model', 'plugins');
            $data['exchange'] = $this->plugins->universal_api(5);
            $data['customergrouplist'] = $this->customers->group_list();
            $data['lastenquirynumber'] = $this->invocies->lastenquiry();
            $data['taxlist'] = $this->common->taxlist($this->config->item('tax'));
            $head['title'] = "New Enquiry";
            $head['usernm'] = $this->aauth->get_user()->username;
            $data['taxdetails'] = $this->common->taxdetail();
            $data['custom_fields'] = $this->custom->add_fields(2);
            $data['products'] = [];
        }
        // echo "<pre>"; print_r($data['assigned_customer']); die();
        // erp2025 09-01-2025 end
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
            $row[] = '<a href="' . base_url("invoices/printinvoice?id=$invoices->lead_id") . '&d=1" class="btn btn-info btn-sm"  title="Download"><span class="fa fa-download"></span></a> <a href="#" data-object-id="' . $invoices->lead_id . '" class="btn btn-danger btn-sm delete-object"><span class="fa fa-trash"></span></a>';
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
        $this->load->model('employee_model', 'employee');
        $data['employee'] = $this->employee->list_employee();
        $tid = intval($this->input->get('id'));
        $data['id'] = $tid;
        $data['title'] = "Edit Invoice $tid";
        $this->load->model('customers_model', 'customers');
        $data['customergrouplist'] = $this->customers->group_list();
        $data['terms'] = $this->invocies->billingterms();
        $data['currency'] = $this->invocies->currencies();
        $data['invoice'] = $this->invocies->invoice_details($tid, $this->limited);
        //   $data['products'] = $this->invocies->items_with_product($tid);
        $data['products'] = $this->invocies->invoice_products($tid);
        // echo "<pre>"; print_r($data['products']); die();
        $head['title'] = "Edit Invoice #$tid";
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['warehouse'] = $this->invocies->warehouses();
        $this->load->model('plugins_model', 'plugins');
        $data['exchange'] = $this->plugins->universal_api(5);
        $this->load->library("Common");
        $data['taxlist'] = $this->common->taxlist_edit($data['invoice']['taxstatus']);
        $data['trackingdata'] = tracking_details('invoice_id',$tid);
        $this->load->library("Common");
        $data['custom_fields_c'] = $this->custom->add_fields(1);
        $data['custom_fields'] = $this->custom->add_fields(2);
        $data['custom_fields'] = $this->custom->view_edit_fields($tid, 2);
        $data['configurations'] = $this->configurations;
        $data['images'] = get_uploaded_images('Invoice',$tid);
        $this->load->view('fixed/header', $head);
        if ($data['invoice']['id']) $this->load->view('invoices/edit', $data);
        $this->load->view('fixed/footer');

    }

    //invoices list
    public function index()
    {

    //    $dates =  getCommonDateRanges(); echo "<pre>"; print_r($dates); die();
        $data['permissions'] = load_permissions('Accounts','Invoices','Manage Invoices1','List');
        $head['title'] = "Manage Invoices";
        $head['usernm'] = $this->aauth->get_user()->username;
        // $condition = "WHERE i_class = 0";
        // $condition = "";
        // $data['counts'] = $this->invocies->get_dynamic_count('cberp_invoices','invoicedate','total',$condition);
        $data['ranges'] = getCommonDateRanges();
        $data['counts'] = $this->invocies->get_filter_count($data['ranges']);
        
        $this->load->view('fixed/header', $head);
        $this->load->view('invoices/invoices',$data);
        $this->load->view('fixed/footer');
    }

    //action
    public function action()
    {

        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
        //loc
        $convert_type = $this->input->post('convert_type');
        $delevery_note_id = $this->input->post('delevery_note_id');
        $invoice_type =  "Deliverynote";
        if (empty($delevery_note_id[0])) {
            $delevery_note_id = [];
            $invoice_type = "POS";
        }

        
        $transaction_number1 = $this->input->post('transaction_number');

        if (empty($transaction_number1[0])) {
        // if (!isset($transaction_number1) || empty($transaction_number1)) {
             
            $transaction_number = get_latest_trans_number();
            
        }
        else{
            $transaction_number = $transaction_number1[0];
        }
        
        // $transaction_number = ($transaction_number1) ? $transaction_number1 : get_latest_trans_number();
        
  
        //delivery noteid is present  or not
        
        $delevery_note_id = ((($delevery_note_id) && count($delevery_note_id) > 0)) ? $delevery_note_id : "";
        // $delevery_note_id = ((($delevery_note_id) && count($delevery_note_id) > 0) && ($convert_type=='Single')) ? $delevery_note_id : "";
        $currency = $this->input->post('mcurrency');
        $customer_id = $this->input->post('customer_id');
        $invocieno = $this->input->post('invocieno');
        $invocietid = $this->input->post('invocieno');
        $invoicedate = $this->input->post('invoicedate');
        $invocieduedate = $this->input->post('invocieduedate');
        $invoice_number = $this->input->post('invoice_number');
        $store_id = $this->input->post('s_warehouses', true);
        $notes = $this->input->post('notes', true);
        $tax = $this->input->post('tax_handle');
        $ship_taxtype = $this->input->post('ship_taxtype');
        $disc_val = numberClean($this->input->post('disc_val'));
        $order_discount = numberClean($this->input->post('order_discount'));
        $subtotal = rev_amountExchange_s($this->input->post('subtotal'), $currency, $this->aauth->get_user()->loc);
        $shipping = rev_amountExchange_s($this->input->post('shipping'), $currency, $this->aauth->get_user()->loc);
        $shipping_tax = 0;
        // $shipping_tax = rev_amountExchange_s($this->input->post('ship_tax'), $currency, $this->aauth->get_user()->loc);
        // if ($ship_taxtype == 'incl') $shipping = $shipping - $shipping_tax;
        $refer = $this->input->post('refer', true);
        $total = rev_amountExchange_s($this->input->post('total'), $currency, $this->aauth->get_user()->loc);
        $project = $this->input->post('prjid');
        $total_tax = 0;
        $total_discount = rev_amountExchange_s($this->input->post('after_disc'), $currency, $this->aauth->get_user()->loc);
        $discountFormat = $this->input->post('discountFormat');
        $pterms = $this->input->post('pterms', true);
        $payment_type = $this->input->post('payment_type', true);
        
        // $total = $total - $order_discount; changedFields

        $data3 = [];
        $productids = [];
        $producttransdata1 = [];
        $data_from_delevierynote = [];
        $groupedData =[];
        $receivable_data =[];
        $total_delivery_note_sale_amount =0;
        $i = 0;
        $grandtotal=0;
        $grandprice=0;
        if ($discountFormat == '0') {
            $discstatus = 0;
        } else {
            $discstatus = 1;
        }
        if ($customer_id == 0) {
            echo json_encode(array('status' => 'Error', 'message' => $this->lang->line('Please add a new client')));
            exit;
        }

        $this->load->model('plugins_model', 'plugins');
        $empl_e = $this->plugins->universal_api(69);
        if ($empl_e['key1']) {
            $emp = $this->input->post('employee');
        } else {
            $emp = $this->aauth->get_user()->id;
        }

        $totaldiscountamt = 0;
        $transok = true;
        $st_c = 0;
        $this->load->library("Common");
        $this->db->trans_start();
        //Invoice Data get_transnumber
        $bill_date = datefordatabase($invoicedate);
        $bill_due_date = datefordatabase($invocieduedate);

        // $this->db->select('invoice_number');
        // $this->db->from('cberp_invoices');
        // $this->db->order_by('invoice_date', 'DESC');
        // $this->db->limit(1);
        // $this->db->where('invoice_number', $invocieno);
        // $query = $this->db->get();
        // if(@$query->row()->invoice_number){
        //     $this->db->select('invoice_number');
        //     $this->db->from('cberp_invoices');
        //     $this->db->order_by('invoice_date', 'DESC');
        //     $this->db->limit(1);
        //     $query = $this->db->get();
        //     $last_invoice_number = $query->row()->invoice_number;
        //     $parts = explode('/', $last_invoice_number);
        //     $last_number = (int)end($parts); 
        //     $next_number = $last_number + 1;
        //     $invocieno=$next_number;
        // }

        //erp2024 new item 11-11-2024
        $status = ($this->input->post('status', true)=='Draft') ? 'due' : $this->input->post('status', true);
        if($payment_type=='Customer Credit')
        {
            $status ='paid';
        }
        $data = array('invoice_number'=>$invoice_number, 'invoice_date' => $bill_date, 'due_date' => $bill_due_date, 'subtotal' => $subtotal, 'shipping' => $shipping, 'shipping_tax' => $shipping_tax, 'shipping_tax_type' => $ship_taxtype, 'discount_rate' => $disc_val, 'total' => $total, 'notes' => $notes, 'customer_id' => $customer_id, 'employee_id' => $emp, 'tax_status' => $tax, 'discount_status' => $discstatus, 'format_discount' => $discountFormat, 'reference' => $refer, 'payment_terms' => $pterms, 'multi' => $currency, 'loc' => $this->aauth->get_user()->loc,'order_discount'=>$order_discount,'store_id'=>$store_id,'transaction_number'=>$transaction_number,'status'=>$status,'payment_type'=>$payment_type,'invoice_type'=>$invoice_type);
        // if ($convert_type) {
        //     $data['delevery_note_id'] = is_array($delevery_note_id) ? implode(',', $delevery_note_id) : $delevery_note_id;
        // }
      
        $invocieno2 = $invocieno;
		//$data['status']='due'; 
        //  ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);  
        $existresult = $this->invocies->invoice_already_exist_or_not($invoice_number); 
    
        if($existresult > 0)
        {
                $this->db->update('cberp_invoices',$data,['invoice_number'=>$invoice_number]);
                $invocie_id = $existresult;
                 // file upload section starts 22-01-2025
                 if($_FILES['upfile'])
                 {
                     upload_files($_FILES['upfile'],'Invoice',$invoice_number);
                 }
                 // file upload section ends 22-01-2025
                // history_table_log('cberp_invoice_log','invoice_id',$invocie_id,'Update');
                $this->db->delete('cberp_invoice_items',['invoice_number'=>$invoice_number]);
                //erp2024 06-01-2025 detailed history log starts
                $changedFields = $this->input->post('changedFields', true);  
                                  
                detailed_log_history('invoice',$invoice_number,'Updated', $changedFields);
        
                //erp2024 06-01-2025 detailed history log ends 
               
        }
        else{
                $data['created_by'] = $this->session->userdata('id');
                $data['created_date'] = date('Y-m-d');
                $this->db->insert('cberp_invoices',$data); 
                // file upload section starts 22-01-2025
                //   if($_FILES['upfile'])
                //   {
                //       upload_files($_FILES['upfile'], 'Invoice',$invoice_number);
                //   }
                // file upload section ends 22-01-2025
                // history_table_log('cberp_invoice_log','invoice_id',$invocie_id,'Created');
                //erp2024 06-01-2025 detailed history log starts
                $changedFields = $this->input->post('changedFields', true);  
                // if($changedFields)
                // {                    
                    detailed_log_history('invoice',$invoice_number,'Created','');
                // }
                //erp2024 06-01-2025 detailed history log ends 
                //insert to tracking table
                // $this->db->insert('cberp_transaction_tracking',['deliverynote_id'=>$insert_id,'deliverynote_number'=>$invocieno_n]);
        }
      
        if ($invoice_number) {
            //products order_discount
            $pid = $this->input->post('pid');
            $productlist = array();
            $wholeproducttransdata = array();
            $prodindex = 0;
            $itc = 0;
            $product_id = $this->input->post('pid');
            $product_name1 = $this->input->post('product_name', true);
            $product_qty = $this->input->post('product_qty');
            $product_price = $this->input->post('product_price');
            $product_tax = $this->input->post('product_tax');
            $product_discount = $this->input->post('product_discount');
            $product_amt = $this->input->post('product_amt');
            $product_subtotal = $this->input->post('product_subtotal');
            $ptotal_tax = $this->input->post('taxa');
            $ptotal_disc = $this->input->post('disca');
            $product_des = $this->input->post('product_description', true);
            $product_unit = $this->input->post('unit');
            $product_hsn = $this->input->post('hsn', true);
            $product_alert = $this->input->post('alert');
            $product_serial = $this->input->post('serial');
            $discount_type = $this->input->post('discount_type');
            $product_cost = $this->input->post('product_cost');

            // print_r($product_cost); die();
          
            //erp@2024 new field 11-11-2024
            $income_account_number = $this->input->post('income_account_number', true);

            $total_records  = count($product_id);
            $product_wise_order_discount = ($order_discount>0) ?round(($order_discount/$total_records),2):0;
            // erp2024 19-12-2024 load default accounts
            $default_cost_of_goods_account = default_chart_of_account('cost_of_goods_solid');
            $default_inventory_account = default_chart_of_account('inventory');
            $invoice_receivable_account_details =  default_chart_of_account('accounts_receivable');
            $grand_product_cost = 0;
            foreach ($product_hsn as $key => $value) {
            
                if ($product_hsn[$key]) {
                    $total_discount += numberClean(@$ptotal_disc[$key]);
                    $total_tax += numberClean($ptotal_tax[$key]);
                    if($discount_type[$key]=="Amttype"){
                        $discountamount = numberClean($product_amt[$key]);
                    }
                    else{
                        $discountamount = numberClean($product_discount[$key]);
                        
                    }
                    
                    $data = array(
                        'invoice_number' => $invoice_number,
                        'product_code' => $product_hsn[$key],
                        'quantity' => numberClean($product_qty[$key]),
                        'price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
                        // 'tax' => numberClean($product_tax[$key]),
                        'discount' => $discountamount,
                        'discount_type' => ($discount_type[$key]),
                        'subtotal' => rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc),
                        'total_tax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                        'total_discount' => rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc),
                        'account_number' => $income_account_number[$key],
                    );

                    
                    $data['delevery_note_id'] = (($delevery_note_id) && count($delevery_note_id) > 0) ? $delevery_note_id[$key] : "";
                    // $productcoaaccount = coa_account_against_productid($product_id[$key]);
                    $productamount = rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc);                
                    $grandtotal += numberClean($product_subtotal[$key]); 
                    $productprice = numberClean($product_subtotal[$key])-numberClean($product_wise_order_discount);
                    $actulprice = rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc)*numberClean($product_qty[$key]);
                    $actulprice = rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc)*numberClean($product_qty[$key]);
                    // $actulprice = $productamount;
                    $grandprice +=  $actulprice;
                    // $producttransdata =  [
                    //     'acid' => $income_account_number[$key],
                    //     'type' => 'Asset',
                    //     'cat' => 'Invoice',
                    //     'credit' => $actulprice,
                    //     // 'credit' => $productprice,
                    //     'eid' => $this->session->userdata('id'),
                    //     'date' => date('Y-m-d'),
                    //     'transaction_number'=>$transaction_number,
                    //     'invoice_number'=>$invoice_number
                    // ];

                    if ($convert_type) {
                        $transaction_number = $transaction_number1[$key];
                        $data_from_delevierynote[$transaction_number][$income_account_number[$key]][] =  [
                            'acid' => $income_account_number[$key],
                            'credit' => $actulprice
                        ];
                    }
                    else
                    {
                        $producttransdata1[$income_account_number[$key]][] =  [
                            'acid' => $income_account_number[$key],
                            'credit' => $actulprice
                        ];
                    }
                
                    // $this->db->set('lastbal', 'lastbal - ' . $actulprice, FALSE);
                    // $this->db->where('acn', $income_account_number[$key]);
                    // $this->db->update('cberp_accounts'); 

                    // cost of goods transaction
                    $total_product_cost = $product_cost[$key]*numberClean($product_qty[$key]);
                    $grand_product_cost += $total_product_cost;
                    //  $cost_of_goods_data =  [
                    //      'acid' => $default_cost_of_goods_account,
                    //      'type' => 'Expense',
                    //      'cat' => 'Invoice',
                    //      'debit' => $total_product_cost,
                    //      'eid' => $this->session->userdata('id'),
                    //      'date' => date('Y-m-d'),
                    //      'transaction_number'=>$transaction_number,
                    //      'invoice_number'=>$invoice_number
                    //  ];
                    //  $this->db->set('lastbal', 'lastbal + ' . $total_product_cost, FALSE);
                    //  $this->db->where('acn', $default_cost_of_goods_account);
                    //  $this->db->update('cberp_accounts'); 
                    //  $this->db->insert('cberp_transactions', $cost_of_goods_data);

                    // Inventory transaction
                    //  $inventory_data =  [
                    //      'acid' => $default_inventory_account,
                    //      'type' => 'Asset',
                    //      'cat' => 'Invoice',
                    //      'credit' => $total_product_cost,
                    //      'eid' => $this->session->userdata('id'),
                    //      'date' => date('Y-m-d'),
                    //      'transaction_number'=>$transaction_number,
                    //      'invoice_number'=>$invoice_number
                    //  ];
                    //  $this->db->set('lastbal', 'lastbal - ' . $total_product_cost, FALSE);
                    //  $this->db->where('acn', $default_inventory_account);
                    //  $this->db->update('cberp_accounts'); 
                    //  $this->db->insert('cberp_transactions', $inventory_data);

                    // $wholeproducttransdata[$prodindex] = $producttransdata;
                    
                    $productlist[$prodindex] = $data;
                    $i++;
                    $prodindex++;
                    //erp2024 newly added for inventory reduction starts
                    $amt = numberClean($product_qty[$key]);

                    $productids[$prodindex] =  $product_id[$key];
                    $this->db->set('quantity', "quantity-$amt", FALSE);
                    $this->db->where('product_code', $product_hsn[$key]);
                    $this->db->update('cberp_products');
                    
                    //erp2024 check transfer warehoues 13-06-2024
                    // $this->db->select('store_id,stock_quantity');
                    // $this->db->from('cberp_product_to_store');
                    // $this->db->where('product_code', $product_hsn[$key]);
                    // $this->db->where('store_id', $store_id);
                    // $checkquery = $this->db->get();
                    // $check_result = $checkquery->row_array();                    
                    // $chekedID = (!empty($check_result))?$check_result['store_id']:"0";
                    // $transferqty = $amt;
                    
                    // if($chekedID>0){
                    //     $existingQty = $check_result['stock_quantity'];
                    //     $current_stock = ($existingQty>0)? $existingQty-$transferqty :$transferqty;
                    //     $data3['stock_quantity'] = $current_stock;
                    //     $data3['updated_by'] = $this->session->userdata('id');
                    //     $data3['updated_date'] = date('Y-m-d H:i:s');
                    //     $this->db->where('store_id', $chekedID);
                    //     $this->db->update('cberp_product_to_store', $data3);
                    // }
                    // if(empty($delevery_note_id))
                    // {
                    //     //erp2024 data insert to average cost 25-02-2025
                    //     insert_data_to_average_cost_table($product_hsn[$key], $product_cost[$key],numberClean($product_qty[$key]), get_costing_transation_type("Invoice Sales"));
                    //     insertion_to_tracking_table('invoice_number', $invoice_number);
                    // }
                    // else{
                    //     insertion_to_tracking_table('invoice_number', $invoice_number,'deliverynote_id',$delevery_note_id[0]);
                    //     detailed_log_history('Deliverynote',$delevery_note_id[0],'Converted to Invoice','');
                    // }   
                    // //erp2024 check transfer warehoues 13-06-2024 

                    // if ((numberClean($product_qty[$key]) - $amt) < 0 and $st_c == 0 and $this->common->zero_stock()) {
                    //     echo json_encode(array('status' => 'Error', 'message' => 'Product - <strong>' . $product_name1[$key] . "</strong> - Low quantity. Available stock is  " . $product_alert[$key]));
                    //     $transok = false;
                    //     $st_c = 1;
                    // }
                    // if ((numberClean($product_alert[$key]) - $amt) < 0 and $st_c == 0 and $this->common->zero_stock()) {
                    //     echo json_encode(array('status' => 'Error', 'message' => 'Product - <strong>' . $product_name1[$key] . "</strong> - Low quantity. Available stock is  " . $product_alert[$key]));
                    //     $transok = false;
                    //     $st_c = 1;
                    // }
                }
                $itc += $amt;
                //erp2024 newly added for inventory reduction  ends

            }

         
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
            //             'cat' => 'Invoice',
            //             'credit' => $totalCredit,
            //             'eid' => $this->session->userdata('id'),
            //             'date' => date('Y-m-d'),
            //             'transaction_number'=>$transaction_number,
            //             'invoice_number'=>$invoice_number
            //         ];
            //         $this->db->set('lastbal', 'lastbal - ' . $totalCredit, FALSE);
            //         $this->db->where('acn', $acid);
            //         $this->db->update('cberp_accounts'); 

            //         $receivable_data[] = [
            //             'acid' => $invoice_receivable_account_details,
            //             // 'account' => $invoice_receivable_account_details['holder'],
            //             'type' => 'Asset',
            //             'cat' => 'Invoice',
            //             'debit' => $totalCredit,
            //             'eid' => $this->session->userdata('id'),
            //             'date' => date('Y-m-d'),
            //             'transaction_number'=>$transaction_number,
            //             'invoice_number'=>$invoice_number
            //         ];

            //         $this->db->set('lastbal', 'lastbal + ' .$totalCredit, FALSE);
            //         $this->db->where('acn', $invoice_receivable_account_details);
            //         $this->db->update('cberp_accounts'); 
            //     }
            // }

            //erp2024 commented on 06-03-2024, data already inserted from delivery note
            // if($data_from_delevierynote)
            // {
            //     foreach ($data_from_delevierynote as $transaction_number => $accounts) {
            //         foreach ($accounts as $acid => $records) {
            //             $totalCredit = 0;

            //             // Sum credit for the same acid within the transaction number
            //             foreach ($records as $record) {
            //                 $totalCredit += $record['credit'];
            //                 $total_delivery_note_sale_amount +=$totalCredit;
            //             }

            //             // Prepare data for insertion
                       
            //             $groupedData[] = [
            //                 'acid' => $acid,
            //                 'credit' => $totalCredit,
            //                 'transaction_number' => $transaction_number,
            //                 'date' => date('Y-m-d'),
            //                 'type' => 'Asset',
            //                 'cat' => 'Invoice',
            //                 'eid' => $this->session->userdata('id'),
            //                 'invoice_number'=>$invoice_number
            //             ];
            //             $this->db->set('lastbal', 'lastbal - ' . $totalCredit, FALSE);
            //             $this->db->where('acn', $acid);
            //             $this->db->update('cberp_accounts'); 
            //             $receivable_data[] = [
            //                 'acid' => $invoice_receivable_account_details,
            //                 'type' => 'Asset',
            //                 'cat' => 'Invoice',
            //                 'debit' => $totalCredit,
            //                 'eid' => $this->session->userdata('id'),
            //                 'date' => date('Y-m-d'),
            //                 'transaction_number'=>$transaction_number,
            //                 'invoice_number'=>$invoice_number
            //             ];
                   
            //             $this->db->set('lastbal', 'lastbal + ' .$totalCredit, FALSE);
            //             $this->db->where('acn', $invoice_receivable_account_details);
            //             $this->db->update('cberp_accounts'); 
            //         }
            //     }
            // }


            // echo "<pre>"; 
            // print_r($data_from_delevierynote); 
            // print_r($groupedData);
            // print_r($receivable_data);
            // die();
            // if (count($product_serial) > 0) {
            //     $this->db->set('status', 1);
            //     $this->db->where_in('serial', $product_serial);
            //     $this->db->update('cberp_product_serials');
            // }
            

            if ($prodindex > 0) {
                $this->db->insert_batch('cberp_invoice_items', $productlist); 
                $granddiscountamt = $total_discount + $order_discount;
                // $order_discount_percentage = order_discount_percentage($order_discount,$grandprice);
                // $shipping_percentage = order_discount_percentage($shipping,$grandprice);
                // $this->db->set(array('discount' => $total_discount, 'tax' => rev_amountExchange_s(amountFormat_general($total_tax), $currency, $this->aauth->get_user()->loc), 'order_discount_percentage'=>$order_discount_percentage,'shipping_percentage'=>$shipping_percentage));
                // $this->db->where('invoice_number', $invoice_number);
                // $this->db->update('cberp_invoices');
                // //check the invoice coming from salesorder
                // $salesorder_id = $this->input->post('salesorder_id', true);
                // if($salesorder_id)
                // {   
                //     $this->db->update('cberp_sales_orders',['converted_status'=>5], ['id'=>$salesorder_id]);
                // }
                
               // history_table_log('cberp_invoice_log','invoice_id',$invocietid,'Update');

                // $creditlimits = get_customer_credit_limit($customer_id);
                // reset_customer_credit($customer_id, $total,$creditlimits['avalable_credit_limit']);
                // // erp2024 transactions starts 25-10-2024
               
                // if(empty($delevery_note_id[0]))
                // { 
                //     // cost of goods transactions transaction 07-02-2025
                //     $cost_of_goods_data =  [
                //         'acid' => $default_cost_of_goods_account,
                //         'type' => 'Expense',
                //         'cat' => 'Invoice',
                //         'debit' => $grand_product_cost,
                //         'eid' => $this->session->userdata('id'),
                //         'date' => date('Y-m-d'),
                //         'transaction_number'=>$transaction_number,
                //         'invoice_number'=>$invoice_number
                //     ];
                //     $this->db->set('lastbal', 'lastbal + ' . $grand_product_cost, FALSE);
                //     $this->db->where('acn', $default_cost_of_goods_account);
                //     $this->db->update('cberp_accounts'); 
                //     $this->db->insert('cberp_transactions', $cost_of_goods_data);
                    
                //     // Inventory transaction 07-02-2025
                //     $inventory_data =  [
                //         'acid' => $default_inventory_account,
                //         'type' => 'Asset',
                //         'cat' => 'Invoice',
                //         'credit' => $grand_product_cost,
                //         'eid' => $this->session->userdata('id'),
                //         'date' => date('Y-m-d'),
                //         'transaction_number'=>$transaction_number,
                //         'invoice_number'=>$invoice_number
                //     ];
                //     $this->db->set('lastbal', 'lastbal - ' . $grand_product_cost, FALSE);
                //     $this->db->where('acn', $default_inventory_account);
                //     $this->db->update('cberp_accounts'); 
                //     $this->db->insert('cberp_transactions', $inventory_data);
                
                // }
                // else
                // {
                //     // $this->db->set('status', 'Invoiced');
                //     // $this->db->set('invoice_number', $invoice_number);
                //     // $this->db->where('delevery_note_id', $delevery_note_id);
                //     // $this->db->update('cberp_delivery_notes');

                //     // $this->db->set('status', 'Invoiced');
                //     // $this->db->where_in('product_id', $productids);
                //     // $this->db->where('delevery_note_id', $delevery_note_id);
                //     // $this->db->update('cberp_delivery_note_items'); 
                //     $this->db->set('status', 'Invoiced');
                //     $this->db->where_in('product_id', $productids);
                //     $this->db->where_in('delevery_note_id', $delevery_note_id);
                //     $this->db->update('cberp_delivery_note_items');

                //     $this->db->set('status', 'Invoiced');
                //     $this->db->set('invoice_number', $invoice_number);
                //     $this->db->where_in('delevery_note_id', $delevery_note_id);
                //     $this->db->update('cberp_delivery_notes');
                // }


                
                // if (($groupedData)) {
                //     $this->db->insert_batch('cberp_transactions', $groupedData);
                    
                // }
                // if (($receivable_data)) {
                //     $this->db->insert_batch('cberp_transactions', $receivable_data);
                //     // die( $this->db->last_query());
                // }

                // erp2024 transactions ends 25-10-2024 order_discount

                //erp2024 totaldiscount transaction 11-11-2024 starts
                // if($total_discount>0)
                // {
                //     $discount_account_details = default_chart_of_account('sales_discount');
                //     $discount_data = [
                //         'acid' => $discount_account_details,
                //         // 'account' => $discount_account_details['holder'],
                //         'type' => 'Asset',
                //         'cat' => 'Invoice',
                //         'debit' => $total_discount,
                //         'eid' => $this->session->userdata('id'),
                //         'date' => date('Y-m-d'),
                //         'transaction_number'=>$transaction_number,
                //         'invoice_number'=>$invoice_number
                //     ];
                //     $this->db->insert('cberp_transactions',$discount_data);
                //     $this->db->set('lastbal', 'lastbal + ' .$total_discount, FALSE);
                //     $this->db->where('acn', $discount_account_details);
                //     $this->db->update('cberp_accounts');                     
                    
                //     $total_discount_credit = [
                //         'acid' => $invoice_receivable_account_details,
                //         'type' => 'Asset',
                //         'cat' => 'Invoice',
                //         'credit' => $total_discount,
                //         'eid' => $this->session->userdata('id'),
                //         'date' => date('Y-m-d'),
                //         'transaction_number'=>$transaction_number,
                //         'invoice_number'=>$invoice_number
                //     ];
                //     $this->db->insert('cberp_transactions',$total_discount_credit);
                //     $this->db->set('lastbal', 'lastbal - ' .$total_discount, FALSE);
                //     $this->db->where('acn', $invoice_receivable_account_details);
                //     $this->db->update('cberp_accounts'); 
                // }
                // if($order_discount)
                // {
                //     $order_discount_account_details = default_chart_of_account('order_discount');
                //     $discount_data1 = [
                //         'acid' => $order_discount_account_details,
                //         'type' => 'Asset',
                //         'cat' => 'Invoice',
                //         'debit' => $order_discount,
                //         'eid' => $this->session->userdata('id'),
                //         'date' => date('Y-m-d'),
                //         'transaction_number'=>$transaction_number,
                //         'invoice_number'=>$invoice_number
                //     ];
                //     $this->db->insert('cberp_transactions',$discount_data1);
                //     $this->db->set('lastbal', 'lastbal + ' .$order_discount, FALSE);
                //     $this->db->where('acn', $order_discount_account_details);
                //     $this->db->update('cberp_accounts'); 

                //     $order_discount_data_credit = [
                //         'acid' => $invoice_receivable_account_details,
                //         'type' => 'Asset',
                //         'cat' => 'Invoice',
                //         'credit' => $order_discount,
                //         'eid' => $this->session->userdata('id'),
                //         'date' => date('Y-m-d'),
                //         'transaction_number'=>$transaction_number,
                //         'invoice_number'=>$invoice_number
                //     ];
                //     $this->db->insert('cberp_transactions',$order_discount_data_credit);
                //     $this->db->set('lastbal', 'lastbal - ' .$order_discount, FALSE);
                //     $this->db->where('acn', $invoice_receivable_account_details);
                //     $this->db->update('cberp_accounts');
                // }
                // if($shipping)
                // {
                //     $shipping_account_details = default_chart_of_account('shipping');
                //     $shipping_data2 = [
                //         'acid' => $shipping_account_details,
                //         'type' => 'Asset',
                //         'cat' => 'Invoice',
                //         'credit' => $shipping,
                //         'eid' => $this->session->userdata('id'),
                //         'date' => date('Y-m-d'),
                //         'transaction_number'=>$transaction_number,
                //         'invoice_number'=>$invoice_number
                //     ];
                //     $this->db->insert('cberp_transactions',$shipping_data2);
                //     $this->db->set('lastbal', 'lastbal - ' .$shipping, FALSE);
                //     $this->db->where('acn', $shipping_account_details);
                //     $this->db->update('cberp_accounts'); 

                //     $shipping_data_debit = [
                //         'acid' => $invoice_receivable_account_details,
                //         'type' => 'Asset',
                //         'cat' => 'Deliverynote',
                //         'debit' => $shipping,
                //         // 'debit' => $total,
                //         'eid' => $this->session->userdata('id'),
                //         'date' => date('Y-m-d'),
                //         'transaction_number'=>$transaction_number,
                //         'invoice_number'=>$invoice_number
                //     ];
                //     $this->db->insert('cberp_transactions',$shipping_data_debit);
                //     $this->db->set('lastbal', 'lastbal + ' .$shipping, FALSE);
                //     $this->db->where('acn', $invoice_receivable_account_details);
                //     $this->db->update('cberp_accounts'); 
                // }
                //erp2024 totaldiscount transaction 11-11-2024 ends

            } else {
                echo json_encode(array('status' => 'Error', 'message' =>
                    "Please choose product from product list. Go to Item manager section if you have not added the products."));
                $transok = false;
            }
            $tnote = '#' . $invoice_number . '-' ;
            $d_trans = $this->plugins->universal_api(69);
            // if ($d_trans['key2']) {
            //     $t_data = array(
            //         'type' => 'Income',
            //         'cat' => 'Sales',
            //         'payerid' => $customer_id,
            //         'method' => 'Auto',
            //         'date' => $bill_date,
            //         'eid' =>$emp,
            //         'tid' => $invocie_id,
            //         'loc' =>$this->aauth->get_user()->loc
            //     );

                

            // }
            if ($transok) {
                $validtoken = hash_hmac('ripemd160', $invoice_number, $this->config->item('encryption_key'));
                $link = base_url('billing/printinvoice?id=' . $invoice_number . '&token=' . $validtoken);
                echo json_encode(array('status' => 'Success','id'=>$invoice_number,'link'=>$link));
            }
        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
                "Invalid Entry!"));
            $transok = false;
        }
   
        if ($transok) {
            $this->db->trans_complete();
        } else {
            $this->db->trans_rollback();
        }
        if ($transok) {
            // $this->db->from('univarsal_api');
            // $this->db->where('univarsal_api.id', 56);
            // $query = $this->db->get();
            // $auto = $query->row_array();
            // if ($auto['key1'] == 1) {
            //     $this->db->select('name,email');
            //     $this->db->from('cberp_customers');
            //     $this->db->where('id', $customer_id);
            //     $query = $this->db->get();
            //     $customer = $query->row_array();
            //     $this->load->model('communication_model');
            //     $invoice_mail = $this->send_invoice_auto($invocie_id, $invocieno2, $bill_date, $total, $currency);
            //     $attachmenttrue = false;
            //     $attachment = '';
            //     $this->communication_model->send_corn_email($customer['email'], $customer['name'], $invoice_mail['subject'], $invoice_mail['message'], $attachmenttrue, $attachment);
            // }
            // if ($auto['key2'] == 1) {
            //     $this->db->select('name,phone');
            //     $this->db->from('cberp_customers');
            //     $this->db->where('id', $customer_id);
            //     $query = $this->db->get();
            //     $customer = $query->row_array();
            //     $this->load->model('plugins_model', 'plugins');

            //     $invoice_sms = $this->send_sms_auto($invocie_id, $invocieno2, $bill_date, $total, $currency);
            //     $mobile = $customer['phone'];
            //     $text_message = $invoice_sms['message'];
            //     $this->load->model('sms_model', 'sms');
            //     $this->sms->send_sms($mobile, $text_message, false);
            // }

            // profit calculation
            $t_profit = 0;
            // $this->db->select('cberp_invoice_items.pid, cberp_invoice_items.price, cberp_invoice_items.qty, cberp_products.product_cost');
            // $this->db->from('cberp_invoice_items');
            // $this->db->join('cberp_products', 'cberp_products.pid = cberp_invoice_items.pid', 'left');
            // $this->db->where('cberp_invoice_items.tid', $invocie_id);
            // $query = $this->db->get();
            // $pids = $query->result_array();
            // foreach ($pids as $profit) {
            //     $t_cost = $profit['product_cost'] * $profit['qty'];
            //     $s_cost = $profit['price'] * $profit['qty'];
            //     $t_profit += $s_cost - $t_cost;
            // }
            // $data = array('type' => 9, 'rid' => $invocie_id, 'col1' => $t_profit, 'd_date' => $bill_date);
            // $this->db->insert('cberp_metadata', $data);

            // $this->custom->save_fields_data($invocie_id, 2);

        }

    }

    //erp2024 23-10-2024 invoice actions starts   salesorder_id

    public function invoice_preview_action()
    {
        
       
        $currency = $this->input->post('mcurrency');
        $customer_id = $this->input->post('customer_id');
        $invocieno = $this->input->post('invocieno');
        $invocietid = $this->input->post('invocieno');
        $invoicedate = $this->input->post('invoicedate');
        $invocieduedate = $this->input->post('invocieduedate');
        $notes = $this->input->post('notes', true);
        $tax = $this->input->post('tax_handle');
        $ship_taxtype = $this->input->post('ship_taxtype');
        $disc_val = numberClean($this->input->post('disc_val'));
        $subtotal = rev_amountExchange_s($this->input->post('subtotal'), $currency, $this->aauth->get_user()->loc);
        $shipping = numberClean($this->input->post('shipping'));
        $shipping_tax = rev_amountExchange_s($this->input->post('ship_tax'), $currency, $this->aauth->get_user()->loc);
        if ($ship_taxtype == 'incl') $shipping = $shipping - $shipping_tax;
        $refer = $this->input->post('refer', true);
        $total = rev_amountExchange_s($this->input->post('total'), $currency, $this->aauth->get_user()->loc);
        $project = $this->input->post('prjid');
        $delevery_note_id = $this->input->post('delevery_note_id');
        $total_tax = 0;
        $product_total = 0;
        $total_discount = rev_amountExchange_s($this->input->post('after_disc'), $currency, $this->aauth->get_user()->loc);
        $discountFormat = $this->input->post('discountFormat');
        $pterms = $this->input->post('pterms', true);
        
        // erp2024 newly added
        $order_discount = $this->input->post('order_discount', true);
        $invoice_number = $this->input->post('invoice_number', true);
        $store_id = $this->input->post('s_warehouses', true);
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
       
        $data = array('tid' => $invocietid, 'invoicedate' => $bill_date, 'invoiceduedate' => $bill_due_date, 'subtotal' => $subtotal, 'shipping' => $shipping, 'ship_tax' => $shipping_tax, 'ship_tax_type' => $ship_taxtype, 'discount_rate' => $disc_val, 'total' => $total, 'notes' => $notes, 'csd' => $customer_id, 'eid' => $emp, 'taxstatus' => $tax, 'discstatus' => $discstatus, 'format_discount' => $discountFormat, 'refer' => $refer, 'term' => $pterms, 'multi' => $currency, 'loc' => $this->aauth->get_user()->loc,'order_discount'=>$order_discount,'invoice_number'=>$invoice_number,'store_id'=>$store_id,'status'=>'Draft');
        $invocieno2 = $invocieno;
		//$data['status']='due';
        $preview_or_cancel  = $this->input->post('stage');
        if($preview_or_cancel=='cancel')
        {
            $data['cancel_reason'] = $this->input->post('comment');
        }
        $existresult = $this->invocies->invoice_already_exist_or_not($invocietid);  
        // echo $existresult;
        // die();
        $data['delevery_note_id'] = (($delevery_note_id) && count($delevery_note_id) > 0) ? $delevery_note_id[0] : "";
        //want set multiple delivery note id
        $deliverinote_first_id = $data['delevery_note_id'];

        if($existresult > 0)
        {
            
                $this->db->update('cberp_invoices',$data,['id'=>$existresult]);
                $invocie_id = $existresult;
                history_table_log('cberp_invoice_log','invoice_id',$invocie_id,'Update');
                // file upload section starts 22-01-2025
                if($_FILES['upfile'])
                {
                    upload_files($_FILES['upfile'], 'Invoice',$invocie_id);
                }
                // file upload section ends 22-01-2025
                   // erp2025 09-01-2025 starts
                   detailed_log_history('Invoice',$invocie_id,'Save as Draft', '');	
                   // erp2025 09-01-2025 ends
        }
        else{
                $data['created_by'] = $this->session->userdata('id');
                $data['created_date'] = date('Y-m-d');
                $this->db->insert('cberp_invoices',$data);
                $invocie_id = $this->db->insert_id();
               
                // file upload section starts 22-01-2025
                if($_FILES['upfile'])
                {
                    upload_files($_FILES['upfile'], 'Invoice',$invocie_id);
                }
                    // file upload section ends 22-01-2025
                 history_table_log('cberp_invoice_log','invoice_id',$invocie_id,'Draft');
                // erp2025 09-01-2025 starts
                detailed_log_history('Invoice',$invocie_id,'Save as Draft', '');	
                // erp2025 09-01-2025 ends
                //insert to tracking table
                // $this->db->insert('cberp_transaction_tracking',['deliverynote_id'=>$insert_id,'deliverynote_number'=>$invocieno_n]);
        }
        if($deliverinote_first_id)
        {   
            $this->db->update('cberp_delivery_notes',['status'=>'Invoiced'], ['delevery_note_id'=>$deliverinote_first_id]);
            detailed_log_history('Deliverynote',$deliverinote_first_id,'Converted to Invoice','');
            insertion_to_tracking_table('invoice_id', $invocie_id, 'invoice_number', $invoice_number,'deliverynote_id',$deliverinote_first_id);
        }
    
        if ($invocie_id) {
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
            $product_amt = $this->input->post('product_amt');
            $product_subtotal = $this->input->post('product_subtotal');
            $ptotal_tax = $this->input->post('taxa');
            $ptotal_disc = $this->input->post('disca');
            $product_des = $this->input->post('product_description', true);
            $product_unit = $this->input->post('unit');
            $product_hsn = $this->input->post('hsn', true);
            $product_alert = $this->input->post('alert');
            $product_serial = $this->input->post('serial');
            $discount_type = $this->input->post('discount_type');
            $product_cost = $this->input->post('product_cost');
            //erp@2024 new field 11-11-2024
            $income_account_number = $this->input->post('income_account_number', true);
            foreach ($pid as $key => $value) {
                if($discount_type[$key]=="Amttype"){
                    $discountamount = numberClean($product_amt[$key]);
                }
                else{
                    $discountamount = numberClean($product_discount[$key]);
                }
                $total_discount += numberClean(@$ptotal_disc[$key]);
                $total_tax += numberClean($ptotal_tax[$key]);
                $product_total += numberClean($product_subtotal[$key]);
                $data = array(
                    'tid' => $invocie_id,
                    'pid' => $product_id[$key],
                    'product' => $product_name1[$key],
                    'code' => $product_hsn[$key],
                    'qty' => numberClean($product_qty[$key]),
                    'price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
                    'tax' => numberClean($product_tax[$key]),
                    'discount' => $discountamount,
                    'discount_type' => $discount_type[$key],
                    'subtotal' => rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc),
                    'totaltax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                    'totaldiscount' => rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc),
                    'product_des' => $product_des[$key],
                    'unit' => $product_unit[$key],
                    'serial' => $product_serial[$key],                    
                    'product_cost' => $product_cost[$key],                    
                    'account_number' => $income_account_number[$key]
                );

                $productlist[$prodindex] = $data;
                $i++;
                $prodindex++;

            }
            if (count($product_serial) > 0) {
                $this->db->set('status', 1);
                $this->db->where_in('serial', $product_serial);
                $this->db->update('cberp_product_serials');
            }
            if ($prodindex > 0) {
                $this->db->delete('cberp_invoice_items', ['tid'=>$invocie_id]);
                $this->db->insert_batch('cberp_invoice_items', $productlist);   
                // die($this->db->last_query());   
                $this->db->set(array('discount' => rev_amountExchange_s(amountFormat_general($total_discount), $currency, $this->aauth->get_user()->loc), 'tax' => rev_amountExchange_s(amountFormat_general($total_tax), $currency, $this->aauth->get_user()->loc), 'items' => $itc));
                $this->db->where('id', $invocie_id);
                $this->db->update('cberp_invoices');
                
                //check the invoice coming from salesorder
                $salesorder_id = $this->input->post('salesorder_id', true);
                
                
                if($salesorder_id)
                {   
                    $this->db->update('cberp_sales_orders',['converted_status'=>5], ['id'=>$salesorder_id]);
                    insertion_to_tracking_table_sales_to_invoice('invoice_id', $invocie_id, 'invoice_number', $invoice_number,'sales_id',$salesorder_id);
                }

            } else {
                echo json_encode(array('status' => 'Error', 'message' =>
                    "Please choose product from product list. Go to Item manager section if you have not added the products."));
                $transok = false;
            }
           
            if ($transok) {
                $validtoken = hash_hmac('ripemd160', $invocie_id, $this->config->item('encryption_key'));
                $link = base_url('billing/printinvoice_preview?id=' . $invocie_id . '&token=' . $validtoken);
                //need view 
                // $link = base_url('billing/view?id=' . $invocieno . '&token=' . $validtoken);
                echo json_encode(array('status' => 'Success','id'=>$invocie_id,'link'=>$link));
            }
        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
                "Invalid Entry!"));
            $transok = false;
        }
        if ($transok) {            
            $this->db->trans_complete();
        } else {
            $this->db->trans_rollback();
        }
       

    }
    //erp2024 23-10-2024 invoice actions ends
    public function actionconvertinvoice()
    {
        $store_id = $this->input->post('s_warehouses');
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

                    //erp2024 check transfer warehoues 13-06-2024
                    $this->db->select('id,stock_qty');
                    $this->db->from('cberp_product_to_store');
                    $this->db->where('product_id', $product_id[$key]);
                    $this->db->where('store_id', $store_id);
                    $checkquery = $this->db->get();
                    $check_result = $checkquery->row_array();                    
                    $chekedID = (!empty($check_result))?$check_result['id']:"0";
                    $transferqty = $amt;
                    
                    if($chekedID>0){
                        $existingQty = $check_result['stock_qty'];
                        $current_stock = ($existingQty>0)? $existingQty-$transferqty :$transferqty;
                        $data3['stock_qty'] = $current_stock;
                        $data3['updated_by'] = $this->session->userdata('id');
                        $data3['updated_dt'] = date('Y-m-d H:i:s');
                        $this->db->where('id', $chekedID);
                        $this->db->update('cberp_product_to_store', $data3);
                    }
                    //erp2024 check transfer warehoues 13-06-2024

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
                    $this->lang->line('Invoice Success') . " <a href='view?id=$invocieno' class='btn btn-primary btn-sm'><span class='fa fa-eye' aria-hidden='true'></span> " . $this->lang->line('View') . "  </a> &nbsp; &nbsp;<a href='printinvoice?id=$invocieno' class='btn btn-blue btn-sm' ><span class='fa fa-print' aria-hidden='true'></span> " . $this->lang->line('Print') . "  </a> &nbsp; &nbsp; <a href='$link' class='btn btn-purple btn-sm'><span class='fa fa-globe' aria-hidden='true'></span> " . $this->lang->line('Public View') . " </a> &nbsp; &nbsp; <a href='create' class='btn btn-warning btn-sm'><span class='fa fa-plus-circle' aria-hidden='true'></span></a>"));
            }
        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
                "Invalid Entry!"));
            $transok = false;
        }
        if ($transok) {
            // if ($this->aauth->premission(4) and $project > 0) {
                // $data = array('pid' => $project, 'meta_key' => 11, 'meta_data' => $invocieno, 'value' => '0');
                // $this->db->insert('cberp_project_meta', $data);
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

            //profit calculation printinvoice
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
      
        $default_print =  get_prefix_73();
        $default_print_type = $default_print['default_invoice_print'];                  
        $list = $this->invocies->get_datatables($this->limited);
        $data = array();
        $no = $this->input->post('start');
        foreach ($list as $invoices) {
            $no++;
            $row = array();
            $row[] = $no;
            $disablecls="";
            $checkedres="";
            $checkedres = $this->invocies->check_delivered_and_return_qty_equal($invoices->id);
            $invoicetid = (!empty($invoices->invoice_number)) ? $invoices->invoice_number :$invoices->tid;
            $targeturl = '<a href="' . base_url("invoices/create?id=$invoices->id") . '">' . $invoicetid . '</a>' ;
            // $targeturl = ($invoices->status!='Draft') ? '<a href="' . base_url("invoices/view?id=$invoices->id") . '">' . $invoicetid . '</a>' : '<a href="' . base_url("invoices/create?id=$invoices->id") . '">' . $invoicetid . '</a>' ;

            $row[] = $targeturl;
            $row[] = $invoices->invoice_type;
            $row[] = $invoices->payment_type;
            $row[] = $invoices->name;
            $colorcode = get_color_code($invoices->invoiceduedate);
            $dudate = (!empty($invoices->invoiceduedate))?dateformat($invoices->invoiceduedate):"";
            $row[] = '<b style="color:'.$colorcode.'">'.$dudate.'</b>';

            // $row[] = dateformat($invoices->invoiceduedate);
            $row[] = $invoices->total; 
            // $row[] = !empty($invoices->payment_recieved_date) ? dateformat($invoices->payment_recieved_date):""; 
            $paymentdate = !empty($invoices->payment_recieved_date) ? dateformat($invoices->payment_recieved_date):"";
            $paidamount = ($invoices->payment_recieved_amount>0) ? number_format($invoices->payment_recieved_amount,2) : "";
            $row[] = "<b>". ($paidamount)."</b><br>".$paymentdate;
           
            $pending_invoice = $this->invocies->payment_pending_invoice($invoices->csd);
            switch ($invoices->status) {
                case 'post dated cheque':
                    $status = '<span class="st-rejected">' . $this->lang->line($invoices->status) . '</span>';
                    break;
                case 'Deleted':
                    $status = '<span class="st-Closed">' . $this->lang->line($invoices->status) . '</span>';
                    break;
            
                case 'due':
                    $status = '<span class="st-created">' . $this->lang->line('Created') . '</span>';
                    if($checkedres==1)
                    {
                        $status = "<span class='st-Closed'>Fully Returned</span>";
                    }
                    break;
                case 'partial':
                    if ($pending_invoice == 1) {
                        $makepaymentbtn = '<a href="' . base_url("invoices/customer_payment?id=$invoices->id&csd=$invoices->csd") . '" class="btn btn-secondary btn-sm"><span class="fa fa-money"></span> Make Payment</a>';
                    } else {
                        $makepaymentbtn = '';
                    }
                    $status = ($invoices->status != 'Draft') ? '<span class="st-' . $invoices->status . '">' . $this->lang->line(ucwords($invoices->status)) . '</span>' : $invoices->status;
                    break;
            
                default:
                    $status = ($invoices->status != 'Draft') ? '<span class="st-' . $invoices->status . '">' . $this->lang->line(ucwords($invoices->status)) . '</span>' : '<span class="st-' . $invoices->status . '">' . $this->lang->line(ucwords($invoices->status)) . '</span>';
                    $makepaymentbtn = '';
                    break;
            }
            
            if ($invoices->payment_type == 'Customer Credit') {
                $status = '<span class="st-paid">Paid</span>';
                $makepaymentbtn = '';
            }
            
            $creditnoteBtn = '<a href="' . base_url("invoices/invoice_creditnote?id=$invoices->id") . '" class="btn btn-sm btn-secondary ' . $disablecls . '"><i class="fa fa-undo"></i> ' . $this->lang->line('Return Items') . '</a>';
            

            //invoice return without paid 05-02-2025

            switch ($invoices->invoice_type) {
                case 'POS':
                    if ($invoices->status == 'Deleted') {
                        $creditnoteBtn = '';
                    }
                    break;
            
                // case 'Deliverynote':
                //     $creditnoteBtn = '';
                //     break;
            }
            $row[] = $status;
            if($checkedres==1)
            {
                $creditnoteBtn = "";
                // $creditnoteBtn = "<span class='st-Closed'>Fully Returned</span>";
                $makepaymentbtn="";
            }
            $validtoken = hash_hmac('ripemd160', $invoices->id, $this->config->item('encryption_key'));                     
            $editbtn = ($invoices->payment_recieved_amount<=0) ? '<a href="' . base_url("invoices/edit?id=$invoices->id") . '" class="btn btn-secondary btn-sm "><span class="fa fa-pencil"></span></a>&nbsp;':'';

            $printbtn = ($default_print_type=='Dot Matrix Print') ? '<a href="' . base_url("billing/pre_print_invoice?id=" . $invoices->id . "&token=" . $validtoken) . '" class="btn btn-secondary btn-sm" title="Print Invoice" target="_blank"><span class="fa fa-print"></span></a>' : '<a href="' . base_url("billing/printinvoice?id=" . $invoices->id . "&token=" . $validtoken) . '" class="btn btn-secondary btn-sm" title="Print Invoice" target="_blank"><span class="fa fa-print"></span></a>';
            $actionbtn = ($invoices->status == 'Draft') ? '' : $printbtn . $makepaymentbtn . ' ' . $creditnoteBtn;
        

            // cloudbizerp/billing/printinvoice?id=15&token=04190ed3f7bc3d3f26160dcb1bfdcb21934dfe22

            // $actionbtn = ($invoices->status=='Draft') ? "" : '<a href="' . base_url("invoices/printinvoice?id=$invoices->id") . '&d=1" class="btn btn-secondary btn-sm"  title="Download"><span class="fa fa-download"></span></a> <a href="#" data-object-id="' . $invoices->id . '" class="btn btn-secondary btn-sm delete-object"><span class="fa fa-trash"></span></a>&nbsp;'.$makepaymentbtn." ".$creditnoteBtn; 
            $row[] = $actionbtn;
            
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
        $data['permissions'] = load_permissions('Accounts','Invoices','Manage Invoices1','View Page');
        $this->load->model('accounts_model');
        $data['acclist'] = $this->accounts_model->accountslist((integer)$this->aauth->get_user()->loc);
        $tid = $this->input->get('id');
        $data['invoice'] = $this->invocies->invoice_details($tid, $this->limited);
        $data['merged_deliverynote'] = ($data['invoice']['invoice_type']=='Deliverynote') ? $this->invocies->delnote_by_invoice_number($data['invoice']['invoice_number']):"";
        
        // echo "<pre>"; print_r($data['invoice']); die();
        $data['delnotedetails'] = $this->invocies->delnote_details($data['invoice']['delevery_note_id']);
        $data['trackingdata'] = tracking_details('invoice_id',$tid);
        $data['attach'] = $this->invocies->attach($tid);
        $data['c_custom_fields'] = $this->custom->view_fields_data($data['invoice']['cid'], 1);
        $data['paymentmethod_details'] = $this->invocies->payment_method_details($tid);
        
        

        $head['usernm'] = $this->aauth->get_user()->username;
        $head['title'] = "Invoice " . $data['invoice']['tid'];
        $this->load->view('fixed/header', $head);
        $data['products'] = $this->invocies->invoice_products($tid);
        if ($data['invoice']['id']) $data['activity'] = $this->invocies->invoice_transactions($tid);
        $data['employee'] = $this->invocies->employee($data['invoice']['eid']);
        // erp2024 20-11-2024 starts
        $data['payment_records'] = $this->invocies->invoice_payments_received($tid);
        $data['journals_records'] = ($data['invoice']['invoice_type']=='Deliverynote') ? $this->invocies->get_deliverynote_invoice_transaction_details($data['invoice']['invoice_number']):$this->invocies->get_invoice_transaction_details($tid);
        // echo "<pre>"; print_r($data['invoice']); die();
        // erp2024 20-11-2024 ends
        $data['log'] = $this->invocies->gethistory($tid);
        //erp2024 06-01-2025 detailed history log starts
        $page = "invoice";
        $data['detailed_log']= get_detailed_logs($tid,$page);
        $products = $data['detailed_log'];
        $groupedBySequence = []; 
        foreach ($products as $product) {
           $sequence = $product['seqence_number'];
           $groupedBySequence[$sequence][] = $product; 
        }
        $data['groupedDatas'] = $groupedBySequence;
        //erp2024 06-01-2025 detailed history log ends
        $data['custom_fields'] = $this->custom->view_fields_data($tid, 2);
        if ($data['invoice']['id']) {
            $data['invoice']['id'] = $tid;            
            // $data['trackingdata'] = tracking_details('invoice_id',$tid);
            $this->load->view('invoices/view', $data);
        }
        $this->load->view('fixed/footer');
    }

    public function printinvoice()
    {

        //products
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
        // if ($this->aauth->premission(11)) {
            $id = $this->input->post('deleteid');

            if ($this->invocies->invoice_delete($id, $this->limited)) {
                echo json_encode(array('status' => 'Success', 'message' =>
                    $this->lang->line('DELETED')));
            } else {
                echo json_encode(array('status' => 'Error', 'message' =>
                    $this->lang->line('ERROR')));
            }
        // } else {
        //     echo json_encode(array('status' => 'Error', 'message' =>
        //         $this->lang->line('ERROR')));
        // }

    }

    public function editaction()
    {
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
     
        // if (!$this->aauth->premission(13)) {
        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        // }
        $store_id = $this->input->post('s_warehouses');
        $customer_id = $this->input->post('customer_id');
        $invocieno = $this->input->post('invocieno');
        $iid = $this->input->post('iid');
        $invocie_id = $iid;
        $invoicedate = $this->input->post('invoicedate');
        $invocieduedate = $this->input->post('invocieduedate');
        $notes = $this->input->post('notes', true);
        $tax = $this->input->post('tax_handle');
        $ship_taxtype = $this->input->post('ship_taxtype');
        $total_tax = 0;
        $product_total=0;
        $grandprice = 0;
        $discountFormat = $this->input->post('discountFormat');
        $pterms = $this->input->post('pterms');
        $currency = $this->input->post('mcurrency');
        $subtotal = rev_amountExchange_s($this->input->post('subtotal'), $currency, $this->aauth->get_user()->loc);
        $shipping = rev_amountExchange_s($this->input->post('shipping'), $currency, $this->aauth->get_user()->loc);
        $shipping_tax = rev_amountExchange_s($this->input->post('ship_tax'), $currency, $this->aauth->get_user()->loc);
        // if ($ship_taxtype == 'incl') $shipping = $shipping - $shipping_tax;
        $refer = $this->input->post('refer', true);
        $total = rev_amountExchange_s($this->input->post('total'), $currency, $this->aauth->get_user()->loc);
        $disc_val = numberClean($this->input->post('disc_val'));
        $total_discount = rev_amountExchange_s($this->input->post('after_disc'), $currency, $this->aauth->get_user()->loc);

        // erp2024 newly added
        $order_discount = numberClean($this->input->post('order_discount'));
        // $total = $total - $order_discount;
        $invoice_number = $this->input->post('invoice_number', true);
        $store_id = $this->input->post('s_warehouses', true);
        $payment_type = $this->input->post('payment_type', true);
       
        $data3 =[];

        // Load the data you want to process
    
   

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
        // $data = array('invoicedate' => $bill_date, 'invoiceduedate' => $bill_due_date, 'subtotal' => $subtotal, 'shipping' => $shipping, 'ship_tax' => $shipping_tax, 'ship_tax_type' => $ship_taxtype, 'discount_rate' => $disc_val, 'discount' => $total_discount, 'tax' => $total_tax, 'total' => $total, 'notes' => $notes, 'csd' => $customer_id, 'items' => 0, 'taxstatus' => $tax, 'discstatus' => $discstatus, 'format_discount' => $discountFormat, 'refer' => $refer, 'term' => $pterms, 'multi' => $currency);
     
        //erp2024 20-12-2024        
        $product_cost = $this->input->post('product_cost');
        $default_cost_of_goods_account = default_chart_of_account('cost_of_goods_solid');
        $default_inventory_account = default_chart_of_account('inventory');


        $status1 = ($this->input->post('status', true)=='Draft') ? 'due' : $this->input->post('status', true);

        //check here for multiple pending
        $trans_number = $this->input->post('transaction_number', true);
        if($trans_number[0])
        {
            $transaction_number = $trans_number[0];
            $this->invocies->reset_debit_accounts($transaction_number);
            $this->invocies->reset_credit_accounts($transaction_number);
            $this->db->delete('cberp_transactions', array('transaction_number' => $transaction_number));
        }
        else{
            $transaction_number = get_latest_trans_number();
        }      
        $data = array('tid' => $invocieno, 'invoicedate' => $bill_date, 'invoiceduedate' => $bill_due_date, 'subtotal' => $subtotal, 'shipping' => $shipping, 'ship_tax' => $shipping_tax, 'ship_tax_type' => $ship_taxtype, 'discount_rate' => $disc_val, 'total' => $total, 'notes' => $notes, 'csd' => $customer_id, 'eid' => $emp, 'taxstatus' => $tax, 'discstatus' => $discstatus, 'format_discount' => $discountFormat, 'refer' => $refer, 'term' => $pterms, 'multi' => $currency, 'loc' => $this->aauth->get_user()->loc,'order_discount'=>$order_discount,'invoice_number'=>$invoice_number,'store_id'=>$store_id,'transaction_number'=>$transaction_number,'status'=>$status1,'payment_type'=>$payment_type);

        $this->db->set($data);
        $this->db->where('id', $iid);
       
        
        if ($this->db->update('cberp_invoices', $data)) {
            //Product Data discount_type
            history_table_log('cberp_invoice_log','invoice_id',$iid,'Update'); 
            // erp2025 09-01-2025 starts
            // file upload section starts 22-01-2025
            if($_FILES['upfile'])
            {
                upload_files($_FILES['upfile'], 'Invoice',$iid);
            }
                 // file upload section ends 22-01-2025
            $sequence_number = detailed_log_history('Invoice',$iid,'Updated', $_POST['changedFields']);	
            // erp2025 09-01-2025 ends
            $pid = $this->input->post('pid');
            $productlist = array();
            $wholeproducttransdata = array();
            $prodindex = 0;
            $itc = 0;
            delete_product_log('cberp_invoice_items','Invoice',$iid,$pid,$sequence_number);
            $this->db->delete('cberp_invoice_items', array('tid' => $iid));
            $product_id = $this->input->post('pid');
            $product_name1 = $this->input->post('product_name', true);
            $product_qty = $this->input->post('product_qty');
            $old_product_qty = $this->input->post('old_product_qty');
            $product_price = $this->input->post('product_price');
            $product_tax = $this->input->post('product_tax');
            $product_discount = $this->input->post('product_discount');
            $product_amt = $this->input->post('product_amt');
            $product_subtotal = $this->input->post('product_subtotal');
            $ptotal_tax = $this->input->post('taxa');
            $ptotal_disc = $this->input->post('disca');
            $product_des = $this->input->post('product_description', true);
            $product_unit = $this->input->post('unit');
            $product_hsn = $this->input->post('hsn');
            $product_serial = $this->input->post('serial');
            $product_alert = $this->input->post('alert');
            $discount_type = $this->input->post('discount_type');
            $income_account_number = $this->input->post('income_account_number', true);
            //erp2024 10-12-2024            
            $product_qty_old = $this->input->post('product_qty_old', true);

            $total_records  = count($product_id);
            $product_wise_order_discount = ($order_discount>0) ?round(($order_discount/$total_records),2):0;
            foreach ($pid as $key => $value) {
                if($discount_type[$key]=="Amttype"){
                    $discountamount = numberClean($product_amt[$key]);
                }
                else{
                    $discountamount = numberClean($product_discount[$key]);
                }
                $total_discount += numberClean(@$ptotal_disc[$key]);
                $total_tax += numberClean($ptotal_tax[$key]);
                $product_total += numberClean($product_subtotal[$key]);
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
                    'serial' => $product_serial[$key],
                    'discount_type' => $discount_type[$key],
                    'account_number' => $income_account_number[$key],
                    'product_cost' => $product_cost[$key],
                    
                );

                // transcation data prepare starts
                $productamount = rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc);
                $actulprice = rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc)*numberClean($product_qty[$key]);
                $grandprice = $actulprice;
                $productprice = numberClean($product_subtotal[$key])-numberClean($product_wise_order_discount);
                $producttransdata =  [
                    'acid' => $income_account_number[$key],
                    'type' => 'Asset',
                    'cat' => 'Invoice',
                    'credit' => $actulprice,
                    // 'credit' => $productprice,
                    'eid' => $this->session->userdata('id'),
                    'date' => date('Y-m-d'),
                    'transaction_number'=>$transaction_number,
                    'invoice_number'=>$invoice_number
                ];
                $this->db->set('lastbal', 'lastbal - ' . $actulprice, FALSE);
                $this->db->where('acn', $income_account_number[$key]);
                $this->db->update('cberp_accounts'); 

               // cost of goods transaction
               $total_product_cost = $product_cost[$key]*numberClean($product_qty[$key]);
               $cost_of_goods_data =  [
                   'acid' => $default_cost_of_goods_account,
                   'type' => 'Expense',
                   'cat' => 'Invoice',
                   'debit' => $total_product_cost,
                   'eid' => $this->session->userdata('id'),
                   'date' => date('Y-m-d'),
                   'transaction_number'=>$transaction_number,
                   'invoice_number'=>$invoice_number
               ];
               $this->db->set('lastbal', 'lastbal + ' . $total_product_cost, FALSE);
               $this->db->where('acn', $default_cost_of_goods_account);
               $this->db->update('cberp_accounts'); 
               $this->db->insert('cberp_transactions', $cost_of_goods_data);

               // Inventory transaction
               $inventory_data =  [
                   'acid' => $default_inventory_account,
                   'type' => 'Asset',
                   'cat' => 'Invoice',
                   'credit' => $total_product_cost,
                   'eid' => $this->session->userdata('id'),
                   'date' => date('Y-m-d'),
                   'transaction_number'=>$transaction_number,
                   'invoice_number'=>$invoice_number
               ];
               $this->db->set('lastbal', 'lastbal - ' . $total_product_cost, FALSE);
               $this->db->where('acn', $default_inventory_account);
               $this->db->update('cberp_accounts'); 
               $this->db->insert('cberp_transactions', $inventory_data);


                $wholeproducttransdata[$prodindex] = $producttransdata;
                // transcation data prepare ends
                $productlist[$prodindex] = $data;
                $i++;
                $prodindex++;

                $amt = numberClean(@$product_qty[$key]);
                //erp2024 removed 13-06-2024
                // $amt = numberClean(@$product_qty[$key]) - numberClean(@$old_product_qty[$key]);
                if ($product_id[$key] > 0 and $amt) {
                    $oldqty = $product_qty_old[$key];
                    $amt = $amt-$oldqty;
                    $this->db->set('qty', "qty-$amt", FALSE);
                    $this->db->where('pid', $product_id[$key]);
                    $this->db->update('cberp_products');
                    

                    //erp2024 check transfer warehoues 13-06-2024
                    
                    $this->db->select('id,stock_qty');
                    $this->db->from('cberp_product_to_store');
                    $this->db->where('product_id', $product_id[$key]);
                    $this->db->where('store_id', $store_id);
                    $checkquery = $this->db->get();

                   
                    $check_result = $checkquery->row_array();                    
                    $chekedID = (!empty($check_result))?$check_result['id']:"0";
                    $transferqty = $amt;
                    
                    if($chekedID>0){
                        $existingQty = $check_result['stock_qty'];
                        $current_stock = ($existingQty>0)? $existingQty-$transferqty :$transferqty;
                        $data3['stock_qty'] = $current_stock;
                        $data3['updated_by'] = $this->session->userdata('id');
                        $data3['updated_dt'] = date('Y-m-d H:i:s');
                        $this->db->where('id', $chekedID);
                        $this->db->update('cberp_product_to_store', $data3);
                    }
                    
                    //erp2024 check transfer warehoues 13-06-2024
                    

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
                $this->db->insert_batch('cberp_transactions', $wholeproducttransdata);
                if (count($product_serial) > 0) {
                    $this->db->set('status', 1);
                    $this->db->where_in('serial', $product_serial);
                    $this->db->update('cberp_product_serials');
                }
                $granddiscountamt = rev_amountExchange_s(amountFormat_general($total_discount), $currency, $this->aauth->get_user()->loc) + $order_discount;
                
                $order_discount_percentage = order_discount_percentage($order_discount,$grandprice);
                $shipping_percentage = order_discount_percentage($shipping,$grandprice);

                $this->db->set(array('discount' => rev_amountExchange_s(amountFormat_general($total_discount), $currency, $this->aauth->get_user()->loc), 'tax' => rev_amountExchange_s(amountFormat_general($total_tax), $currency, $this->aauth->get_user()->loc), 'items' => $itc,'order_discount_percentage'=>$order_discount_percentage,'shipping_percentage'=>$shipping_percentage));
                $this->db->where('id', $invocie_id);
                $this->db->update('cberp_invoices');

              
                 // erp2024 transactions starts 11-11-2024
                $invoice_receivable_account_details = default_chart_of_account('accounts_receivable');
                // $invoice_sale_revenue_account_details = get_account_details("Sales/Revenue");
                $latest_total = $total;
                $receivable_data = [
                    'acid' => $invoice_receivable_account_details,
                    // 'account' => $invoice_receivable_account_details['holder'],
                    'type' => 'Asset',
                    'cat' => 'Invoice',
                    'debit' => $latest_total,
                    'eid' => $this->session->userdata('id'),
                    'date' => date('Y-m-d'),
                    'transaction_number'=>$transaction_number,
                    'invoice_number'=>$invoice_number
                ];
                $this->db->insert('cberp_transactions',$receivable_data);

                $this->db->set('lastbal', 'lastbal + ' .$latest_total, FALSE);
                $this->db->where('acn', $invoice_receivable_account_details);
                $this->db->update('cberp_accounts'); 
                // erp2024 transactions ends 11-11-2024
                
                //erp2024 totaldiscount transaction 11-11-2024 starts
                if($total_discount>0)
                {
                    $discount_account_details = default_chart_of_account('sales_discount');
                    $discount_data1 = [
                        'acid' => $discount_account_details,
                        // 'account' => $discount_account_details['holder'],
                        'type' => 'Asset',
                        'cat' => 'Invoice',
                        'debit' => $total_discount,
                        'eid' => $this->session->userdata('id'),
                        'date' => date('Y-m-d'),
                        'transaction_number'=>$transaction_number,
                        'invoice_number'=>$invoice_number
                    ];
                    $this->db->insert('cberp_transactions',$discount_data1);
                    $this->db->set('lastbal', 'lastbal + ' .$total_discount, FALSE);
                    $this->db->where('acn', $discount_account_details);
                    $this->db->update('cberp_accounts');
                } 
                if($order_discount)
                {
                    $order_discount_account_details = default_chart_of_account('order_discount');
                    $discount_data = [
                        'acid' => $order_discount_account_details,
                        // 'account' => $order_discount_account_details['holder'],
                        'type' => 'Asset',
                        'cat' => 'Invoice',
                        'debit' => $order_discount,
                        'eid' => $this->session->userdata('id'),
                        'date' => date('Y-m-d'),
                        'transaction_number'=>$transaction_number,
                        'invoice_number'=>$invoice_number
                    ];
                    $this->db->insert('cberp_transactions',$discount_data);
                    $this->db->set('lastbal', 'lastbal + ' .$order_discount, FALSE);
                    $this->db->where('acn', $order_discount_account_details);
                    $this->db->update('cberp_accounts');
                } 
                if($shipping)
                {
                    $shipping_account_details = default_chart_of_account('shipping');
                    $shipping_data2 = [
                        'acid' => $shipping_account_details,
                        'type' => 'Asset',
                        'cat' => 'Invoice',
                        'credit' => $shipping,
                        'eid' => $this->session->userdata('id'),
                        'date' => date('Y-m-d'),
                        'transaction_number'=>$transaction_number,
                        'invoice_number'=>$invoice_number
                    ];
                    $this->db->insert('cberp_transactions',$shipping_data2);
                    $this->db->set('lastbal', 'lastbal - ' .$shipping, FALSE);
                    $this->db->where('acn', $shipping_account_details);
                    $this->db->update('cberp_accounts'); 
                }
                //erp2024 totaldiscount transaction 11-11-2024 ends

                
                if($transok)    echo json_encode(array('status' => 'Success', 'message' => $this->lang->line('Invoice has  been updated') . " <a href='view?id=$iid' class='btn btn-secondary btn-sm'><span class='fa fa-eye' aria-hidden='true'></span></a> "));
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
        $this->customers->pos_add($name, $company, $phone, $email, $address, $city, $region, $country, $postbox, $customergroup, $tax_id, $shipping_name, $shipping_phone, $shipping_email, $shipping_address_1, $shipping_city, $shipping_region, $shipping_country, $shipping_postbox);

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
            'CompanyDetails' => '<h6><strong>' . $this->config->item('ctitle') . ',</strong></h6><address>' . $this->config->item('address') . '<br>' . $this->config->item('address2') . '</address>
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

    public function convert_to_deals(){
        $head['title'] = "Convert To Deals";
        $this->load->view('fixed/header', $head);
        $this->load->view('invoices/convert_to_deals');
        $this->load->view('fixed/footer');
    }

    // customer payment #erp2024 09-09-2024
    public function customer_payment()
    {
        $this->load->model('accounts_model');
        $data['acclist'] = $this->accounts_model->accountslist((integer)$this->aauth->get_user()->loc);
        $tid = $this->input->get('id');
        $customerid = $this->input->get('csd');
        $data['invoice'] = $this->invocies->invoice_details($tid, $this->limited);
        $data['attach'] = $this->invocies->attach($tid);
        $head['usernm'] = $this->aauth->get_user()->username;
        $head['title'] = "Customer Payment for Invoice -  " . $data['invoice']['tid'];
        $this->load->view('fixed/header', $head);
        $data['dew_invoices'] = $this->invocies->dew_invoices_by_customerid($customerid);
        if ($data['invoice']['id']) $data['activity'] = $this->invocies->invoice_transactions($tid);
        $data['employee'] = $this->invocies->employee($data['invoice']['eid']);
        $data['custom_fields'] = $this->custom->view_fields_data($tid, 2);        
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
        $data['default_receivableaccount'] = default_chart_of_account('accounts_receivable');
        if ($data['invoice']['id']) {
            $data['invoice']['id'] = $tid;            
            // $data['trackingdata'] = tracking_details('invoice_id',$tid);
            $this->load->view('invoices/customer_payment', $data);
        }
        $this->load->view('fixed/footer');
    }

    //erp2024 03-10-2024
    public function draftaction()
    {        
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
        $purchase_id = $this->input->post('purchase_id', true);
        $purchase_number = $this->input->post('purchase_number', true);
        $purchase_reciept_number = $this->input->post('srv', true);
        $costfactor = $this->input->post('cost_factor', true);
        // $srvdate = $this->input->post('srvdate', true); //cberp_transaction_tracking
        // $srvdate = (!empty($this->input->post('srvdate', true)))?$this->input->post('srvdate', true):date('Y-m-d');
        $master_data = [            
            "purchase_reciept_number" => $this->input->post('srv', true),
            "salepoint_name" => $this->input->post('salepoint_name', true),
            "salepoint_id" => $this->input->post('salepoint_id', true),
            "purchase_number" => $purchase_number,
            "supplier_id" => $this->input->post('supplier_id', true),
            "party_name" => $this->input->post('party_name', true),
            "damageclaim_account_id" => $this->input->post('damageclaim_ac', true),
            "damageclaim_ac_name" => $this->input->post('damageclaim_ac_name', true),
            "bill_number" => $this->input->post('bill_number', true),
            "currency_id" => $this->input->post('currency_id', true),
            "currency_rate" => $this->input->post('currency_rate', true),
            "bill_description" => $this->input->post('bill_description', true),
            "purchase_type" => $this->input->post('doctype', true),
            // "purchase_receipt_date" => $srvdate,
            "purchase_amount" => numberClean($this->input->post('purchase_amount', true)),
            "cost_factor" => $costfactor,
            "bill_amount" => numberClean($this->input->post('bill_amount', true)),
            "reciept_status" =>'Draft',
            "note" => $this->input->post('note', true)
        ];
        if($this->input->post('salepoint_name', true)!="1")
        {
            $master_data["created_date"] = date("Y-m-d H:i:s");
        }
        $stockreciptid = "";
       
        $query = $this->db->select('purchase_reciept_number')
        ->from('cberp_purchase_receipts')
        ->where('purchase_reciept_number', $this->input->post('srv', true))
        ->get();

        if ($query->num_rows() > 0) {
            $existing_row = $query->row_array();
            $this->db->where('purchase_reciept_number', $existing_row['purchase_reciept_number']);
            $this->db->update('cberp_purchase_receipts', $master_data);
            $purchase_reciept_number = $existing_row['purchase_reciept_number'];
        } else {
            $srvData = $this->costingcalculation->lastsrvNumber($purchase_number);
            $master_data['purchase_reciept_number'] = $srvData['srv'];               
            $purchase_reciept_number = $master_data['purchase_reciept_number'];   
            $this->db->insert('cberp_purchase_receipts', $master_data);
            $stockreciptid = $this->db->insert_id();
            detailed_log_history('Purchaseorder',$purchase_number,'Purchase Receipt Created', $_POST['changedFields']);
            insertion_to_tracking_table('purchase_reciept_id',$stockreciptid,'purchase_reciept_number',$purchase_reciept_number, 'purchase_order_id', $purchase_id);
        }
        
        $product_names          =  $this->input->post('product_name', true);
        $product_code           =  $this->input->post('product_code', true);
        $product_unit           =  $this->input->post('product_unit', true);
        $product_qty            =  $this->input->post('product_qty', true);
        $product_qty_recieved   =  $this->input->post('product_qty_recieved', true);
        $product_foc            =  $this->input->post('product_foc', true);
        $damage                 =  $this->input->post('damage', true);
        $price                  =  $this->input->post('price', true);
        $saleprice              =  $this->input->post('saleprice', true);
        $amount                 =  $this->input->post('amount', true);
        $discountperc           =  $this->input->post('discountperc', true);
        $discountamount         =  $this->input->post('discountamount', true);
        $netamount              =  $this->input->post('netamount', true);
        $qaramount              =  $this->input->post('qaramount', true);
        $qaramount              =  $this->input->post('qaramount', true);
        $description            =  $this->input->post('description', true);
        $product_id             =   $this->input->post('product_id', true);
        $account_code           =   $this->input->post('account_code', true);
        $prodindex=0;
        $productlist =[];
        foreach ($product_names as $key => $value) {
            if(!empty($product_names[$key]) && ($product_qty_recieved[$key] > 0) && !empty($purchase_reciept_number))
            { 
                $data1 = array(
                    'purchase_reciept_number' => $purchase_reciept_number,
                    'product_code'            => $product_code[$key],
                    'ordered_quantity'        => $product_qty[$key],
                    'product_quantity_recieved'=> $product_qty_recieved[$key],
                    'product_foc'             => $product_foc[$key],
                    'damaged_quantity'        => $damage[$key],
                    'price'                   => numberClean($price[$key]),
                    'saleprice'               => numberClean($saleprice[$key]),
                    'amount'                  => numberClean($amount[$key]),
                    'discountperc'            => $discountperc[$key],
                    'discountamount'          => $discountamount[$key],
                    'netamount'               => numberClean($netamount[$key]),
                    // 'description'             => $description[$key],
                    'qaramount'               => numberClean($qaramount[$key]),
                    'account_code'            => $account_code[$key],
                    'created_date'              => date("Y-m-d H:i:s") //check
                );
                $existornot = $this->costingcalculation->check_product_existornot($purchase_reciept_number,$product_code[$key]);
                if($existornot==1)
                {
                    $this->db->update('cberp_purchase_receipt_items', $data1, ['purchase_reciept_number'=>$purchase_reciept_number, 'product_code'=>$product_code[$key]]);
                    
                }
                else{
                    $this->db->insert('cberp_purchase_receipt_items', $data1);
                }
                $productlist[$prodindex] = $data1;
                $prodindex++;
            }

        }
       
        //costing section 
        $expense_name =  $this->input->post('expense_name', true);
        $expense_id   =  $this->input->post('expense_id', true);
        $payable_acc  =  $this->input->post('payable_acc', true);
        $payable_acc_no =  $this->input->post('payable_acc_no', true);
        $bill_number_cost =  $this->input->post('bill_number_cost', true);
        $bill_date_cost =  $this->input->post('bill_date_cost', true);
        $costing_amount =  $this->input->post('costing_amount', true);
        $currency_cost =  $this->input->post('currency_cost', true);
        $currency_rate_cost =  $this->input->post('currency_rate_cost', true);
        $costing_amount_qar =  $this->input->post('costing_amount_qar', true);
        $costing_amount_net =  $this->input->post('costing_amount_net', true);
        $remarks =  $this->input->post('remarks', true);
        $costindex=0;
        $costlist =[];
        $this->db->delete('cberp_purchase_receipt_expenses', ['purchase_reciept_number'=>$purchase_reciept_number]);
        foreach ($expense_name as $key => $row) {
            if(!empty($expense_name[$key]) && !empty($purchase_reciept_number))
            {
                $data2 = array(
                    'purchase_reciept_number' => $purchase_reciept_number,
                    'expense_name'          => $expense_name[$key],
                    'expense_id'            => $expense_id[$key],
                    'payable_account'       => $payable_acc[$key],
                    'payable_account_number'=> $payable_acc_no[$key],
                    'bill_number_cost'      => $bill_number_cost[$key],
                    'bill_date_cost'        => $bill_date_cost[$key],
                    'costing_amount'        => numberClean($costing_amount[$key]),
                    'currency_cost'         => $currency_cost[$key],
                    'currency_rate_cost'    => $currency_rate_cost[$key],
                    'costing_amount_net'    => numberClean($costing_amount_net[$key]),
                    'costing_amount_qar'    => numberClean($costing_amount_qar[$key]),
                    'remarks'               => $remarks[$key],
                    'created_date'          => date("Y-m-d H:i:s"),
                    'cost_per_item'         => $this->input->post('cost_per_item', true)

                );
                $costlist[$costindex] = $data2;
                $costindex++;
                $existornot = $this->costingcalculation->check_expense_existornot($purchase_reciept_number,$expense_id[$key]);
                if($existornot==1)
                {
                    $this->db->update('cberp_purchase_receipt_expenses', $data2, ['purchase_reciept_number'=>$purchase_reciept_number, 'expense_id'=>$expense_id[$key]]);
                }
                else{
                    $this->db->insert('cberp_purchase_receipt_expenses', $data2);
                }
                
                // $this->db->insert('cberp_purchase_receipt_expenses', $data2);
            }
        }

        // $log = [
        //     'reciept_id' => $stockreciptid,
        //     'purchase_id' => $this->input->post('purchase_id', true),
        //     'ip_address' => getUserIpAddress(),
        //     'performed_by' => $this->session->userdata('id'),
        //     'performed_dt' => date("Y-m-d H:i:s"),
        //     'action_performed' => 'Purchase Receipt move to Draft',
        // ];
        // $this->db->insert('purchase_receipt_log', $log);
        //erp2024 06-01-2025 detailed history log starts
        detailed_log_history('Purchasereceipt',$purchase_reciept_number,'Data Saved As Draft','');
        //erp2024 06-01-2025 detailed history log ends 
        $response = array(
            'success' => true,
            'message' => 'Saved successfully',
            'data'=>$purchase_reciept_number
        );
        echo json_encode($response);
        die();
    }
    public function revertorder_by_admin_action()
    {
        $po_id = $this->input->post('po_id');
        $this->db->update('cberp_purchase_receipts', ['assign_to' => NULL,'approved_dt' => (NULL),'approvalflg'=>'1','reciept_status'=>'Reverted'], ['id'=> $po_id]);
        history_table_log('purchase_receipt_log','reciept_id',$po_id,'Reverted');
        //erp2024 06-01-2025 detailed history log starts
         detailed_log_history('Purchasereceipt',$po_id,'Reverted', $changedFields);
        //erp2024 06-01-2025 detailed history log ends 
         echo json_encode(array('status' => 'success'));
    }

    public function recieve_item_action()
    {        
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL); 
        //insertion_to_tracking_table
        $purchase_number = $this->input->post('purchase_number', true);
        $purchase_reciept_number = $this->input->post('srv', true);
        $costfactor = $this->input->post('cost_factor', true);
        $stockreciptid = $purchase_id = $this->input->post('costmaserid');
        $bill_amount = numberClean($this->input->post('bill_amount', true));
        $producttransdata = [];
        $grand_discount =0;        
        $grand_cost =0;      
        $changedFields = $_POST['changedFields'];  
        if($this->input->post('transaction_number', true))
        {
            $transaction_number = $this->input->post('transaction_number', true);
            $this->purchase->reset_debit_accounts($transaction_number);
            $this->purchase->reset_credit_accounts($transaction_number);
            $this->db->delete('cberp_transactions', array('transaction_number' => $transaction_number));
        }
        else{
            $transaction_number = get_latest_trans_number();
        }  
        $master_data = [
            "purchase_reciept_number" => $this->input->post('srv', true),
            "purchase_number" => $purchase_number,
            "salepoint_name" => $this->input->post('salepoint_name'),
            "salepoint_id" => $this->input->post('salepoint_id', true),
            "supplier_id" => $this->input->post('supplier_id', true),
            "party_name" => $this->input->post('party_name', true),
            "damageclaim_account_id" => $this->input->post('damageclaim_ac', true),
            "damageclaim_ac_name" => $this->input->post('damageclaim_ac_name', true),
            "bill_number" => $this->input->post('bill_number', true),
            // "bill_date" => $this->input->post('bill_date', true),
            "currency_id" => $this->input->post('currency_id', true),
            "currency_rate" => $this->input->post('currency_rate', true),
            "bill_description" => $this->input->post('bill_description', true),
            "purchase_type" => $this->input->post('doctype', true),
            // "srvdate" => $this->input->post('srvdate', true),
            "purchase_amount" => numberClean($this->input->post('purchase_amount', true)),
            "bill_amount" => $bill_amount,
            "cost_factor" => $costfactor,
            // "payment_date" => $this->input->post('payment_date', true),
            "updated_date" => date("Y-m-d H:i:s"),
            "updated_by" => $this->session->userdata('id'),
            "assigned_to"   => $this->session->userdata('id'),
            "approved_by" => $this->session->userdata('id'),
            "approved_date" => date("Y-m-d H:i:s"),
            "received_by" => $this->session->userdata('id'),
            "received_date" => date("Y-m-d H:i:s"),
            "approval_flag" => "1",
            "reciept_status" => "Received",
            "note" => $this->input->post('note', true),
            "transaction_number" => $transaction_number

        ];

        
        
        if(!empty($master_data) && !empty($this->input->post('salepoint_name')) && !empty( $this->input->post('bill_number', true))){
            $purchaserecipts_exists = $this->costingcalculation->get_purchase_receipt_by_srvNumber($this->input->post('srv', true));
            $authdata =[];     
            $authdata = [
                'authorized_amount' => numberClean($this->input->post('bill_amount')),
                'status' => "Approve",
                'authorized_date' => date("Y-m-d H:i:s"),
                'authorized_by' => $this->session->userdata('id'),
                'authorized_type' => 'Reported Person',
            ];

            if($purchaserecipts_exists)
            {
                $this->db->where('purchase_reciept_number', $purchase_reciept_number);
                $this->db->update('cberp_purchase_receipts', $master_data);
                // /////////////////////////////////////////////////////////////////           
                $this->db->where('function_id',$purchase_reciept_number);
                $this->db->where('function_type','Purchase Receipt');
                $this->db->update('authorization_history', $authdata);
                // /////////////////////////////////////////////////////////////////
            }
            else{
                $master_data["bill_date"] = date("Y-m-d");
                $master_data["purchase_receipt_date"] = date("Y-m-d");
                $master_data["created_date"] = date("Y-m-d H:i:s");
                $master_data["created_by"] = $this->session->userdata('id');
                $master_data["prepared_by"] = $this->session->userdata('id');            
                $master_data["prepared_date"] = date("Y-m-d H:i:s");
                $master_data["prepared_flag"] = '1';
                $srvData = $this->costingcalculation->lastsrvNumber($purchase_number);
                $master_data['purchase_reciept_number'] = $srvData['srv'];                     
                $purchase_reciept_number = $master_data['purchase_reciept_number'];   
                $this->db->insert('cberp_purchase_receipts', $master_data);
                $stockreciptid = $this->db->insert_id();
                // /////////////////////////////////////////////////////////////////  
                $authdata['function_id'] = $purchase_reciept_number;         
                $authdata['function_type'] = 'Purchase Receipt';        
                $this->db->insert('authorization_history', $authdata);
                insertion_to_tracking_table('purchase_reciept_id', $stockreciptid, 'purchase_reciept_number', $purchase_reciept_number,'purchase_order_id',$this->input->post('purchase_id', true));
                // /////////////////////////////////////////////////////////////////
            }   
            
        }
        
        $product_names          =  $this->input->post('product_name', true);
        $product_code           =  $this->input->post('product_code', true);
        $product_unit           =  $this->input->post('product_unit', true);
        $product_qty            =  $this->input->post('product_qty', true);
        $product_qty_recieved   =  $this->input->post('product_qty_recieved', true);
        $product_foc            =  $this->input->post('product_foc', true);
        $damage                 =  $this->input->post('damage', true);
        $price                  =  $this->input->post('price', true);
        $saleprice              =  $this->input->post('saleprice', true);
        $amount                 =  $this->input->post('amount', true);
        $discountperc           =  $this->input->post('discountperc', true);
        $discountamount         =  $this->input->post('discountamount', true);
        $netamount              =  $this->input->post('netamount', true);
        $qaramount              =  $this->input->post('qaramount', true);
        $qaramount              =  $this->input->post('qaramount', true);
        $description            =  $this->input->post('description', true);
        $product_id             =  $this->input->post('product_id', true);
        $store_id           = $this->input->post('salepoint_id');
        $account_code           = $this->input->post('account_code');
        $newcost                = $this->input->post('newcost');
        $prodindex=0;
        $productlist =[];
        $producttransdata1=[];
        $groupedData = [];
        $average_cost_data = [];
        $cost_data_list = [];

        //erp2024 04-03-2025
        $inventory_account_details = default_chart_of_account('inventory');
        // echo "<pre>"; print_r($product_qty_recieved); die(); 
       
        foreach ($product_names as $key => $value) {
           
            // if(!empty($product_names[$key]) && !empty($stockreciptid))
            // {
               
                $data1 = array(
                    'purchase_reciept_number' => $purchase_reciept_number,
                    'product_code'           => $product_code[$key],
                    'ordered_quantity'       => $product_qty[$key],
                    'product_quantity_recieved'=> $product_qty_recieved[$key],
                    'product_foc'            => numberClean($product_foc[$key]),
                    'damaged_quantity'       => $damage[$key],
                    'price'                  => numberClean($price[$key]),
                    'saleprice'              => numberClean($saleprice[$key]),
                    'amount'                 => numberClean($amount[$key]),
                    'discountperc'           => $discountperc[$key],
                    'discountamount'         => numberClean($discountamount[$key]),
                    'netamount'              => numberClean($netamount[$key]),
                    'description'            => $description[$key],
                    'qaramount'              => numberClean($qaramount[$key]),
                    'account_code'           => $account_code[$key],
                    'created_date'           => date("Y-m-d H:i:s"),
                );

                $average_cost_data = array(
                    'product_cost'           => $newcost[$key],
                    'transaction_date_time'  => date("Y-m-d H:i:s"),
                    'transaction_quantity'   => $product_qty[$key],
                    'transaction_type'       => get_costing_transation_type("Purchase"),
                    'added_by'               => $this->session->userdata('id')
                );
                               

                $prdqty = intval($product_qty_recieved[$key]) - intval($damage[$key]);
                //average cost 
                $this->invocies->calculate_average_cost($product_code[$key],numberClean($prdqty),numberClean($newcost[$key]));

                $this->db->set('onhand_quantity', 'onhand_quantity + ' . (int)$prdqty, FALSE);
                $this->db->where('product_code', $product_code[$key]);
                $this->db->update('cberp_products');

                $this->db->set('stock_quantity', 'stock_quantity + ' . (int)$prdqty, FALSE);
                $this->db->where('store_id', $store_id);
                $this->db->where('product_code', $product_code[$key]);
                $this->db->update('cberp_product_to_store');
                // $productamount = $netamount[$key];
                $productamount = round($product_qty_recieved[$key] * numberClean($price[$key]), 2);
                $grand_discount +=  $discountamount[$key];
                //preparing data for product account transaction
                $producttransdata =  [
                    'acid' => $inventory_account_details,
                    // 'acid' => $account_code[$key],
                    'type' => 'Expense',
                    'cat' => 'Purchase',
                    'debit' => $productamount,
                    'eid' => $this->session->userdata('id'),
                    'date' => date('Y-m-d'),
                    'transaction_number'=>$transaction_number,
                    // 'invoice_number'=>$invoice_number
                ];

                //preparing data for product account transaction ends
                // $this->db->set('lastbal', 'lastbal + ' . $productamount, FALSE);
                // $this->db->where('acn', $account_code[$key]);
                // $this->db->update('cberp_accounts'); 

                $producttransdata1[$inventory_account_details][] =  [
                    'acid' => $inventory_account_details,
                    // 'acid' => $account_code[$key],
                    'debit' => $productamount
                ];
                // $producttransdata1[$account_code[$key]][] =  [
                //     // 'acid' => $account_code[$key],
                //     'debit' => $productamount
                // ];
    
                $wholeproducttransdata[$prodindex] = $producttransdata;
                
                if($amount[$key]>0 && $product_qty_recieved[$key]>0)
                {
                    // $itemcost = ($amount[$key]/$product_qty_recieved[$key])*$costfactor;
                    // $productcost = [
                    //     'item_cost' => $newcost[$key],
                    //     'updated_by' => $this->session->userdata('id'),
                    //     'updated_dt' => date("Y-m-d H:i:s")
                    // ];
                    // $this->db->where('product_id', $product_id[$key]);
                    // $this->db->update('cberp_product_ai', $productcost);
                    // $this->db->update('cberp_products', ['product_cost'=>$newcost[$key]],['pid'=>$product_id[$key]]);
                }

                
                $this->invocies->purchase_order_items_update($purchase_number,$product_code[$key],$product_qty_recieved[$key]);
                $productlist[$prodindex] = $data1;
                $cost_data_list[$prodindex] = $average_cost_data;
                $prodindex++;
            // }
            
        }
    //    die("here");
        if($producttransdata1)
        {
            foreach ($producttransdata1 as $acid => $transactions) {
                $totalCredit = 0;
        
                foreach ($transactions as $transaction) {
                    $totalCredit += $transaction['debit'];
                }
        
                // Store the summed data for each `acid`
                $groupedData[] = [
                    'acid' => $acid,
                    'type' => 'Asset',
                    'cat' => 'Purchase',
                    'debit' => $totalCredit,
                    'eid' => $this->session->userdata('id'),
                    'date' => date('Y-m-d'),
                    'transaction_number'=>$transaction_number,
                    // 'invoice_number'=>$invoice_number
                ];
                $this->db->set('lastbal', 'lastbal + ' . $totalCredit, FALSE);
                $this->db->where('acn', $acid);
                $this->db->update('cberp_accounts'); 
            }
        }


        //costing section 
        $expense_name =  $this->input->post('expense_name', true);
        $expense_id   =  $this->input->post('expense_id', true);
        $payable_acc  =  $this->input->post('payable_acc', true);
        $payable_acc_no =  $this->input->post('payable_acc_no', true);
        $bill_number_cost =  $this->input->post('bill_number_cost', true);
        $bill_date_cost =  $this->input->post('bill_date_cost', true);
        $costing_amount =  $this->input->post('costing_amount', true);
        $currency_cost =  $this->input->post('currency_cost', true);
        $currency_rate_cost =  $this->input->post('currency_rate_cost', true);
        $costing_amount_qar =  $this->input->post('costing_amount_qar', true);
        $costing_amount_net =  $this->input->post('costing_amount_net', true);
        $remarks =  $this->input->post('remarks', true);
        $costindex=0;
        $costlist =[];
        $grand_cost_amount = 0;
        foreach ($expense_name as $key => $row) {
            if(!empty($expense_name[$key]) && !empty($stockreciptid))
            {
                $data2 = array(
                    'purchase_reciept_number'  => $purchase_reciept_number,
                    'expense_name'          => $expense_name[$key],
                    'expense_id'            => $expense_id[$key],
                    'payable_account'       => $payable_acc[$key],
                    'payable_account_number' => $payable_acc_no[$key],
                    'bill_number_cost'      => $bill_number_cost[$key],
                    'bill_date_cost'        => $bill_date_cost[$key],
                    'costing_amount'        => numberClean($costing_amount[$key]),
                    'currency_cost'         => $currency_cost[$key],
                    'currency_rate_cost'    => $currency_rate_cost[$key],
                    'costing_amount_net'    => numberClean($costing_amount_net[$key]),
                    'costing_amount_qar'    => numberClean($costing_amount_qar[$key]),
                    'remarks'               => $remarks[$key],
                    'created_date'          => date("Y-m-d H:i:s"),
                    'cost_per_item'         => $this->input->post('cost_per_item', true),
    
                );
                $grand_cost_amount += numberClean($costing_amount_net[$key]);
                $costlist[$costindex] = $data2;
                $costindex++;
            }
        }
        if(!empty($productlist)){
            $this->db->where('purchase_reciept_number', $purchase_reciept_number);
            $this->db->delete('cberp_purchase_receipt_items');
            $this->db->insert_batch('cberp_purchase_receipt_items', $productlist);
            $this->invocies->update_purchase_order_status($purchase_number);

            // $this->db->insert_batch('cberp_average_cost', $cost_data_list);

            // die($this->db->last_query());
            //transaction section    ////////////////////////////////////////
            $payable_account_details = default_chart_of_account('purchase_account');
            $accounts_payable_data = [
                'acid' => $payable_account_details,
                'type' => 'Liability',
                'cat' => 'Purchase',
                'credit' => numberClean($this->input->post('bill_amount', true)),
                'eid' => $this->session->userdata('id'),
                'date' => date('Y-m-d'),
                'transaction_number'=>$transaction_number
            ];
            $this->db->set('lastbal', 'lastbal - ' . $bill_amount, FALSE);
            // $this->db->where('id', $payable_account_details['id']);
            $this->db->where('acn', $payable_account_details);
            $this->db->update('cberp_accounts'); 
            $this->db->insert('cberp_transactions',$accounts_payable_data);
            // $this->db->insert_batch('cberp_transactions', $wholeproducttransdata);

           if($grand_discount)
           {
                $purchase_discount_account = default_chart_of_account('purchase_discount');
                $discount_payable_data = [
                    'acid' => $purchase_discount_account,
                    'type' => 'Income',
                    'cat' => 'Purchase',
                    'credit' => $grand_discount,
                    'eid' => $this->session->userdata('id'),
                    'date' => date('Y-m-d'),
                    'transaction_number'=>$transaction_number
                ];
                $this->db->set('lastbal', 'lastbal - ' . $grand_discount, FALSE);
                // $this->db->where('id', $purchase_discount_account);
                $this->db->where('acn', $purchase_discount_account);
                $this->db->update('cberp_accounts'); 
                $this->db->insert('cberp_transactions',$discount_payable_data);
           }
           if (($groupedData)) {
            $this->db->insert_batch('cberp_transactions', $groupedData);
        }
            //transaction section  ends  ////////////////////////////////////////
           
        }
        if(!empty($costlist)){
            $this->db->where('purchase_reciept_number', $purchase_reciept_number);
            $this->db->delete('cberp_purchase_receipt_expenses');
            $this->db->insert_batch('cberp_purchase_receipt_expenses', $costlist);
            $default_costing_account = default_chart_of_account('costing_account');
            if($grand_cost_amount > 0)
            {
                $costing_amount_data = [
                    'acid' => $default_costing_account,
                    'type' => 'Expense',
                    'cat' => 'Purchase',
                    'debit' => $grand_cost_amount,
                    'eid' => $this->session->userdata('id'),
                    'date' => date('Y-m-d'),
                    'transaction_number'=>$transaction_number
                ];
                $this->db->set('lastbal', 'lastbal + ' . $default_costing_account, FALSE);
                // $this->db->where('id', $purchase_discount_account);
                $this->db->where('acn', $default_costing_account);
                $this->db->update('cberp_accounts'); 
                $this->db->insert('cberp_transactions',$costing_amount_data);

                $costing_payable_data = [
                    'acid' => $payable_account_details,
                    'type' => 'Liability',
                    'cat' => 'Purchase',
                    'credit' => $grand_cost_amount,
                    'eid' => $this->session->userdata('id'),
                    'date' => date('Y-m-d'),
                    'transaction_number'=>$transaction_number
                ];
                $this->db->set('lastbal', 'lastbal - ' . $grand_cost_amount, FALSE);
                // $this->db->where('id', $payable_account_details['id']);
                $this->db->where('acn', $payable_account_details);
                $this->db->update('cberp_accounts'); 
                $this->db->insert('cberp_transactions',$costing_payable_data);
               

                $this->db->where('id', $stockreciptid);
                $this->db->update('cberp_purchase_receipts', ['costing_amount'=>$grand_cost_amount]);
            }
        }
        $log = [
            'reciept_id' => $stockreciptid,
            'purchase_id' => $this->input->post('purchase_id', true),
            'ip_address' => getUserIpAddress(),
            'performed_by' => $this->session->userdata('id'),
            'performed_dt' => date("Y-m-d H:i:s"),
            'action_performed' => 'Purchase Receipt Items Recieved',
        ];
        $this->db->insert('purchase_receipt_log', $log);
        //erp2024 06-01-2025 detailed history log starts
        detailed_log_history('Purchasereceipt',$purchase_reciept_number,'Items Recieved', $changedFields);
        //erp2024 06-01-2025 detailed history log ends  cberp_transaction_tracking
        
        $response = array(
            'success' => true,
            'message' => 'Updated successfully'
        );
        echo json_encode($response);
        die();
    }
    public function average_cost_from_purchase_order()
    {        
         ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL); 
        $prvious_data = $this->invocies->calculate_average_cost(3,10,100);
        // $this->invocies->calculate_average_cost('3','10','500');
        die();
    }
    // public function receipt_accept_by_employee()
    // {
    //     $po_id = $this->input->post('po_id');
    //     $this->db->update('cberp_purchase_receipts', ['reciept_status'=>'Received'], ['id'=> $po_id]);
    //     echo json_encode(array('status' => 'success'));
    // }

    public function receipt_accept_by_employee(){
        $costfactor = $this->input->post('cost_factor', true);
        $stockreciptid = $purchase_id = $this->input->post('costmaserid');
        $bill_amount = numberClean($this->input->post('bill_amount', true));
        $producttransdata = [];
        $grand_discount =0;        
        $transaction_number = get_latest_trans_number();
        $master_data = [
            "salepoint_name" => $this->input->post('salepoint_name'),
            "salepoint_id" => $this->input->post('salepoint_id', true),
            "supplier_name" => $this->input->post('supplier_name', true),
            "supplier_id" => $this->input->post('supplier_id', true),
            "party_name" => $this->input->post('party_name', true),
            "damageclaim_ac" => $this->input->post('damageclaim_ac', true),
            "damageclaim_ac_name" => $this->input->post('damageclaim_ac_name', true),
            "bill_number" => $this->input->post('bill_number', true),
            // "bill_date" => $this->input->post('bill_date', true),
            "currency_id" => $this->input->post('currency_id', true),
            "currency_rate" => $this->input->post('currency_rate', true),
            "bill_description" => $this->input->post('bill_description', true),
            "doctype" => $this->input->post('doctype', true),
            "srv" => $this->input->post('srv', true),
            // "srvdate" => $this->input->post('srvdate', true),
            "purchase_amount" => numberClean($this->input->post('purchase_amount', true)),
            "bill_amount" => $bill_amount,
            "cost_factor" => $costfactor,
            // "payment_date" => $this->input->post('payment_date', true),
            "updated_dt" => date("Y-m-d H:i:s"),
            "updated_by" => $this->session->userdata('id'),
            "assign_to"   => $this->session->userdata('id'),
            "approved_by" => $this->session->userdata('id'),
            "approved_dt" => date("Y-m-d H:i:s"),
            "received_by" => $this->session->userdata('id'),
            "received_dt" => date("Y-m-d H:i:s"),
            "approvalflg" => "1",
            "reciept_status" => "Received",
            "note" => $this->input->post('note', true),
            "transaction_number" => $transaction_number

        ];


        
        if(!empty($master_data) && !empty($this->input->post('salepoint_name')) && !empty( $this->input->post('bill_number', true))){
            $this->db->where('id', $stockreciptid);
            $this->db->update('cberp_purchase_receipts', $master_data);    

            // /////////////////////////////////////////////////////////////////
            $authdata =[];     
            $authdata = [
                'authorized_amount' => numberClean($this->input->post('bill_amount')),
                'status' => "Approve",
                'authorized_date' => date("Y-m-d H:i:s"),
                'authorized_by' => $this->session->userdata('id'),
                'authorized_type' => 'Reported Person',
            ];

            $this->db->where('function_id',$stockreciptid);
            $this->db->where('function_type','Purchase Receipt');
            $this->db->update('authorization_history', $authdata);
            // /////////////////////////////////////////////////////////////////
        }
        
        $product_names          =  $this->input->post('product_name', true);
        $product_code           =  $this->input->post('product_code', true);
        $product_unit           =  $this->input->post('product_unit', true);
        $product_qty            =  $this->input->post('product_qty', true);
        $product_qty_recieved   =  $this->input->post('product_qty_recieved', true);
        $product_foc            =  $this->input->post('product_foc', true);
        $damage                 =  $this->input->post('damage', true);
        $price                  =  $this->input->post('price', true);
        $saleprice              =  $this->input->post('saleprice', true);
        $amount                 =  $this->input->post('amount', true);
        $discountperc           =  $this->input->post('discountperc', true);
        $discountamount         =  $this->input->post('discountamount', true);
        $netamount              =  $this->input->post('netamount', true);
        $qaramount              =  $this->input->post('qaramount', true);
        $qaramount              =  $this->input->post('qaramount', true);
        $description            =  $this->input->post('description', true);
        $product_id             =  $this->input->post('product_id', true);
        $store_id           = $this->input->post('salepoint_id');
        $account_code           = $this->input->post('account_code');
        $prodindex=0;
        $productlist =[];
        // echo "<pre>"; print_r($product_qty_recieved); die();
        foreach ($product_names as $key => $value) {
            if(!empty($product_names[$key]) && !empty($stockreciptid))
            {
                $data1 = array(
                    'stockreciptid'          => $stockreciptid,
                    'product_name'           => $product_names[$key],
                    'product_id'             => $product_id[$key],
                    'product_code'           => $product_code[$key],
                    'product_unit'           => $product_unit[$key],
                    'product_qty'            => $product_qty[$key],
                    'product_qty_recieved'   => $product_qty_recieved[$key],
                    'product_foc'            => $product_foc[$key],
                    'damage'                 => $damage[$key],
                    'price'                  => numberClean($price[$key]),
                    'saleprice'              => numberClean($saleprice[$key]),
                    'amount'                 => numberClean($amount[$key]),
                    'discountperc'           => $discountperc[$key],
                    'discountamount'         => numberClean($discountamount[$key]),
                    'netamount'              => numberClean($netamount[$key]),
                    'description'            => $description[$key],
                    'qaramount'              => numberClean($qaramount[$key]),
                    'account_code'           => $account_code[$key],
                    'created_date'           => date("Y-m-d"),
                    'created_dt'             => date("Y-m-d H:i:s")
                );
                $prdqty = intval($product_qty_recieved[$key]) - intval($damage[$key]);
                $this->db->set('qty', 'qty + ' . (int)$prdqty, FALSE);
                $this->db->where('pid', $product_id[$key]);
                $this->db->update('cberp_products');

                $this->db->set('stock_qty', 'stock_qty + ' . (int)$prdqty, FALSE);
                $this->db->where('store_id', $store_id);
                $this->db->where('product_id', $product_id[$key]);
                $this->db->update('cberp_product_to_store');

                $productamount = round($product_qty_recieved[$key] * numberClean($price[$key]), 2);
                $grand_discount +=  $discountamount[$key];
                $producttransdata =  [
                    'acid' => $account_code[$key],
                    'type' => 'Expense',
                    'cat' => 'Purchase',
                    'debit' => $productamount,
                    'eid' => $this->session->userdata('id'),
                    'date' => date('Y-m-d'),
                    'transaction_number'=>$transaction_number,
                    'invoice_number'=>$invoice_number
                ];
                $this->db->set('lastbal', 'lastbal + ' . $productamount, FALSE);
                $this->db->where('acn', $account_code[$key]);
                $this->db->update('cberp_accounts'); 
    
                $wholeproducttransdata[$prodindex] = $producttransdata;

                $productlist[$prodindex] = $data1;
                $prodindex++;
            }
            
        }
       
        //costing section  bill_amount
        $expense_name =  $this->input->post('expense_name', true);
        $expense_id   =  $this->input->post('expense_id', true);
        $payable_acc  =  $this->input->post('payable_acc', true);
        $payable_acc_no =  $this->input->post('payable_acc_no', true);
        $bill_number_cost =  $this->input->post('bill_number_cost', true);
        $bill_date_cost =  $this->input->post('bill_date_cost', true);
        $costing_amount =  $this->input->post('costing_amount', true);
        $currency_cost =  $this->input->post('currency_cost', true);
        $currency_rate_cost =  $this->input->post('currency_rate_cost', true);
        $costing_amount_qar =  $this->input->post('costing_amount_qar', true);
        $costing_amount_net =  $this->input->post('costing_amount_net', true);
        $remarks =  $this->input->post('remarks', true);
        $costindex=0;
        $costlist =[];
        foreach ($expense_name as $key => $row) {
            if(!empty($expense_name[$key]) && !empty($stockreciptid))
            {
                $data2 = array(
                    'stockreciptid'         => $stockreciptid,
                    'expense_name'          => $expense_name[$key],
                    'expense_id'            => $expense_id[$key],
                    'payable_acc'           => $payable_acc[$key],
                    'payable_acc_no'        => $payable_acc_no[$key],
                    'bill_number_cost'      => $bill_number_cost[$key],
                    'bill_date_cost'        => $bill_date_cost[$key],
                    'costing_amount'        => numberClean($costing_amount[$key]),
                    'currency_cost'         => $currency_cost[$key],
                    'currency_rate_cost'    => $currency_rate_cost[$key],
                    'costing_amount_net'    => numberClean($costing_amount_net[$key]),
                    'costing_amount_qar'    => numberClean($costing_amount_qar[$key]),
                    'remarks'               => $remarks[$key],
                    'created_date'          => date("Y-m-d"),
                    'created_dt'          => date("Y-m-d H:i:s"),
                    'cost_per_item'         => $this->input->post('cost_per_item', true)
    
                );
                $costlist[$costindex] = $data2;
                $costindex++;
            }
        }
        if(!empty($productlist)){
            $this->db->where('stockreciptid', $stockreciptid);
            $this->db->delete('cberp_purchase_receipt_items');
            $this->db->insert_batch('cberp_purchase_receipt_items', $productlist);
           
            $payable_account_details = get_account_details_for_invoicing("Current Liability","Accounts Payable");            
            $accounts_payable_data = [
                'acid' => $payable_account_details['acn'],
                'type' => 'Liability',
                'cat' => 'Purchase',
                'credit' => numberClean($this->input->post('bill_amount', true)),
                'eid' => $this->session->userdata('id'),
                'date' => date('Y-m-d'),
                'transaction_number'=>$transaction_number
            ];
            $this->db->set('lastbal', 'lastbal - ' . $bill_amount, FALSE);
            $this->db->where('id', $payable_account_details['id']);
            $this->db->update('cberp_accounts'); 
            $this->db->insert('cberp_transactions',$accounts_payable_data);
            $this->db->insert_batch('cberp_transactions', $wholeproducttransdata);

            $purchase_discount_account = get_account_details_for_invoicing("Revenue","Purchase Discount");
            $discount_payable_data = [
                'acid' => $purchase_discount_account['acn'],
                'type' => 'Income',
                'cat' => 'Purchase',
                'credit' => $grand_discount,
                'eid' => $this->session->userdata('id'),
                'date' => date('Y-m-d'),
                'transaction_number'=>$transaction_number
            ];
            $this->db->set('lastbal', 'lastbal - ' . $grand_discount, FALSE);
            $this->db->where('id', $purchase_discount_account['id']);
            $this->db->update('cberp_accounts'); 
            $this->db->insert('cberp_transactions',$discount_payable_data);
           
        }
        if(!empty($costlist)){
            $this->db->where('stockreciptid', $stockreciptid);
            $this->db->delete('cberp_purchase_receipt_expenses');
            $this->db->insert_batch('cberp_purchase_receipt_expenses', $costlist);
        }
        $log = [
            'reciept_id' => $stockreciptid,
            'purchase_id' => $this->input->post('purchase_id', true),
            'ip_address' => getUserIpAddress(),
            'performed_by' => $this->session->userdata('id'),
            'performed_dt' => date("Y-m-d H:i:s"),
            'action_performed' => 'Purchase Receipt Accepeted by Employee',
        ];
        $this->db->insert('purchase_receipt_log', $log);
        //erp2024 06-01-2025 detailed history log starts
         detailed_log_history('Purchasereceipt',$stockreciptid,'Accepeted by Employee', $changedFields);
        //erp2024 06-01-2025 detailed history log ends 
        $response = array(
            'success' => true,
            'message' => 'Updated successfully'
        );
        echo json_encode($response);
        die();
    }

    public function revert_reciept_by_employee_action()
    {
        $po_id = $this->input->post('po_id');
        $this->db->update('cberp_purchase_receipts', ['assign_to' => NULL,'approvalflg'=>'0','reciept_status'=>'Pending'], ['id'=> $po_id]);
        echo json_encode(array('status' => 'success'));
    }

    public function get_enquiry_count_filter()
    {
        $filter_status = $this->input->post('filter_status');
        $filter_employee = $this->input->post('filter_employee');

        $filter_expiry_date_from = !empty($this->input->post('filter_expiry_date_from')) ? date('Y-m-d',strtotime($this->input->post('filter_expiry_date_from'))) : ""; 

        $filter_expiry_date_to = !empty($this->input->post('filter_expiry_date_to')) ? date('Y-m-d',strtotime($this->input->post('filter_expiry_date_to'))) : "";

        $filter_price_from = !empty($this->input->post('filter_price_from')) ? $this->input->post('filter_price_from') : 0;
        $filter_price_to = !empty($this->input->post('filter_price_to')) ? $this->input->post('filter_price_to'): 0;

        $filter_customer = !empty($this->input->post('filter_customer')) ?$this->input->post('filter_customer') : "";
        $filter_customertype = !empty($this->input->post('filter_customertype')) ?$this->input->post('filter_customertype') : "";

        $this->load->model('enquiry_model', 'enquiry_model');
        // $results = $this->enquiry_model->get_enquiry_count_filter($filter_status);        
        $results = $this->enquiry_model->get_enquiry_count_filter($filter_status,$filter_employee,$filter_expiry_date_from,$filter_expiry_date_to,$filter_price_from,$filter_price_to,$filter_customer,$filter_customertype);        
        foreach ($results as $key => $value) {
            if (empty($value)) {
                $results[$key] = 0;
            }
        }
        
        echo json_encode(array('status' => 'success','data'=>$results));
    }

    public function get_min_max_amount() {
        $table = $this->input->post('tablename');
        $field = $this->input->post('tablefield');
        $min_max = min_max_amount($table, $field);
        echo json_encode(array('status' => 'success','data'=>$min_max));
    }

    // erp2024 04-11-2024 starts 
    public function invoice_creditnote()
    {
        //    ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL); stockreturn_id
        $tid = intval($this->input->get('id'));
        $data['id'] = $tid;
        $data['currency'] = $this->quote->currencies();
        $head['title'] = "Credit Note";
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['permissions'] = load_permissions('Accounts','Invoices','Invoice Credit Notes','View Page');
        $this->load->model('Stockreturn_model', 'stockreturn');    
        $data['creditnotetid'] = $this->stockreturn->lastpurchase();
        $data['configurations'] = $this->configurations;
        // $this->load->model('deliveryreturn_model', 'deliveryreturn');
        // $data['deliverynote_status'] = $this->deliveryreturn->deliverynote_status($tid);
        // $data['invoiceid'] = ($data['deliverynote_status']=='Invoiced') ? $this->deliveryreturn->invoice_details_by_delnoteid($tid):"";

        $stockreturn_id = intval($this->input->get('iid'));
        if($stockreturn_id)
        {
            $data['stockreturn_id'] =$stockreturn_id;
            $this->load->model('invoice_creditnotes_model', 'invocies_creditnote');
            $data['notemaster'] = $this->invocies_creditnote->invoice_return_details($stockreturn_id); 
            
            if($data['notemaster']['created_by'])
            {
                $data['created_employee'] = employee_details_by_id($data['notemaster']['created_by']);          
            }
            $data['products'] = $this->invocies_creditnote->invoice_return_products($stockreturn_id);
            // echo "<pre>"; print_r($data['notemaster']); die();
            $data['bank_transaction_ref_number'] = $this->invocies_creditnote->bank_transaction_ref_number($stockreturn_id);
            $data['trackingdata'] = tracking_details('stock_return_id',$stockreturn_id);
            $data['journals_records'] = $this->invocies_creditnote->get_journals_for_invoice_return($stockreturn_id);        
            $data['payment_records'] = $this->invocies_creditnote->invoice_payments_received($stockreturn_id);
        }
        else{
            $data['notemaster'] = $this->invocies->invoice_by_id($tid);       
            $data['products'] = $this->invocies->invoice_products_for_return($tid);
            $data['trackingdata'] = tracking_details('invoice_id',$tid);
    
        }   
        
        //  echo "<pre>"; print_r($data['notemaster']); die();
        // $data['invoice_details'] = ($data['deliverynote_status']=="Invoiced") ? $this->deliveryreturn->invoice_details($tid) : "";
        $data['prefix'] = get_prefix_72();

        $page = "Invoicereturn";
        $data['detailed_log']= get_detailed_logs($stockreturn_id,$page);
        $products1 = $data['detailed_log'];
        $groupedBySequence = []; // Initialize an empty array for grouping
  
        foreach ($products1 as $product) {
            $sequence = $product['seqence_number'];
            $groupedBySequence[$sequence][] = $product; // Group by sequence number
        }
          
        $data['groupedDatas'] = $groupedBySequence;


        $this->load->view('fixed/header', $head);
        $this->load->view('invoices/invoicecreditnote', $data);
        $this->load->view('fixed/footer');
    }
    public function invoice_creditnote_return_action()
    {
        //   ini_set('display_errors', 1);
        //   ini_set('display_startup_errors', 1);
        //   error_reporting(E_ALL);
          $transaction_number = get_latest_trans_number();
          $currency = $this->input->post('mcurrency');
          $customer_id = $this->input->post('customer_id');
          $data3=[];
          $person_type = $this->input->post('person_type');
          $new_u = 'create';
          if ($person_type) {
              $new_u = 'create_client';
            //   if (!$this->aauth->premission(2)) {
            //       exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
            //   }
          }
          if ($person_type == 2) {
              $new_u = 'create_note';
            //   if (!$this->aauth->premission(1)) {
            //       exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
            //   }
          }
          $invocieno = $this->input->post('invocieno');
          $invocietid = $invocieno;
          $invoice_number = $this->input->post('invoice_number');
          $invoicedate = $this->input->post('invoicedate');
          $invocieduedate = $this->input->post('invoiceduedate');
          $invoice_id = $this->input->post('invoice_id');
          $notes = $this->input->post('notes');
          $total_tax = 0;
          $total_discount = 0;
          $order_discount =0;
          $discountFormat = $this->input->post('discountFormat');
          $pterms = $this->input->post('pterms');
          $payment_type = $this->input->post('payment_type');          
          $store_id = $this->input->post('store_id');
          $shipping_amount = $this->input->post('shipping_amount');
          $order_discount = $this->input->post('order_discount');
          $invoice_return_number = $this->input->post('invoice_return_number');
          $i = 0;
          if ($discountFormat == '0') {
              $discstatus = 0;
          } else {
              $discstatus = 1;
          }
          
          $order_discount_percentage = $this->input->post('order_discount_percentage');
          $shipping_percentage = $this->input->post('shipping_percentage');
          $order_discount = rev_amountExchange_s($this->input->post('order_discount'), $currency, $this->aauth->get_user()->loc);
          $subtotal = rev_amountExchange_s($this->input->post('subtotal'), $currency, $this->aauth->get_user()->loc);
          $shipping = rev_amountExchange_s($this->input->post('shipping_amount'), $currency, $this->aauth->get_user()->loc);
        //   $shipping_tax = rev_amountExchange_s($this->input->post('ship_tax'), $currency, $this->aauth->get_user()->loc);
        //   if ($ship_taxtype == 'incl') $shipping = $shipping - $shipping_tax;
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
          //Invoice Data discount
          $bill_date = datefordatabase($invoicedate);
          $bill_due_date = datefordatabase($invocieduedate);
          $grandsubtotal = 0;
          if (!$currency) $currency = 0; 
          $data = array('tid' => $invocieno, 'invoicedate' => $bill_date, 'invoiceduedate' => $bill_due_date, 'subtotal' => $subtotal, 'shipping' => $shipping, 'ship_tax' => $shipping_tax, 'ship_tax_type' => $ship_taxtype, 'total' => $total, 'notes' => $notes, 'csd' => $customer_id, 'eid' => $this->aauth->get_user()->id, 'taxstatus' => $tax, 'discstatus' => $discstatus, 'format_discount' => $discountFormat, 'refer' => $refer, 'term' => $pterms, 'loc' => $this->aauth->get_user()->loc, 'i_class' => 0, 'multi' => $currency, 'invoice_id' => $invoice_id,'prepared_dt'=>date('Y-m-d H:i:s'), 'prepared_flg'=>'1', 'prepared_by'=>$this->session->userdata('id'),'return_status'=>'Received','approved_by'=>$this->session->userdata('id'),'approved_dt'=>date('Y-m-d H:i:s'),'approvalflg'=>1,'sent_by'=>$this->session->userdata('id'),'sent_dt'=>date('Y-m-d H:i:s'),'created_by'=>$this->session->userdata('id'),'created_dt'=>date('Y-m-d H:i:s'),'transaction_number'=>$transaction_number,'payment_status'=>'Due','store_id'=>$store_id,'order_discount_percentage'=>$order_discount_percentage,'order_discount'=>$order_discount,'shipping_percentage'=>$shipping_percentage);
          $invoice_transcations = "";
         
          if($iid>0){
              $this->db->update('cberp_stock_returns', $data,['id'=>$iid]);
              $invocieno = $iid;            
              $this->db->delete('cberp_stock_returns_items', array('tid' => $invocieno));
          }
          else{
              $this->db->insert('cberp_stock_returns', $data);
              $invocieno = $this->db->insert_id();
          }
          
          //insert data to tracking table
          if(!empty($invoice_id))
          {
              insertion_to_tracking_table('stock_return_id', $invocieno, 'stock_return_number', $invoice_return_number,'invoice_id',$invoice_id);
          }
          if ($invocieno) {
              
              $pid = $this->input->post('product_id');
              $productlist = array();
              $stock_return_data = array();
              $prodindex = 0;
              $itc = 0;
              $product_id = $this->input->post('product_id');
              $product_name1 = $this->input->post('product_name', true);
              $product_code = $this->input->post('product_code', true);
              $product_qty = $this->input->post('return_qty');
              $product_price = $this->input->post('product_price');
              $product_tax = $this->input->post('product_tax');
              $product_discount = $this->input->post('product_discount');
              $product_subtotal = $this->input->post('product_subtotal');
              $ptotal_tax = $this->input->post('taxa');
              $ptotal_disc = $this->input->post('disca');
              $product_unit = $this->input->post('unit');
              $code = $this->input->post('product_code');
              $damaged_qty = $this->input->post('damaged_qty');              
              $account_number = $this->input->post('account_number');              
              $damaged_qty = $this->input->post('damaged_qty');   
              $discount_type = $this->input->post('discount_type');
              $product_cost = $this->input->post('product_cost');

              //erp2024 20-12-2024  default accounts             
              $default_cost_of_goods_account = default_chart_of_account('cost_of_goods_solid');
              $default_inventory_account = default_chart_of_account('inventory');

              foreach ($pid as $key => $value) {
                  if(intval($product_qty[$key]) > 0 && !empty($product_name1[$key]))
                  {
                      $total_discount += numberClean(@$ptotal_disc[$key]);
                      $total_tax += numberClean($ptotal_tax[$key]);
                      $grandsubtotal =  $grandsubtotal + rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc);
                      $data = array(
                          'tid' => $invocieno,
                          'pid' => $product_id[$key],
                          'product' => $product_name1[$key],
                          'code' => $code[$key],
                          'qty' => numberClean($product_qty[$key]),
                          'price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
                          'tax' => numberClean($product_tax[$key]),
                          'discount' => numberClean($product_discount[$key]),
                          'discount_type' => $discount_type[$key],
                          'subtotal' => rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc),
                          'totaltax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                          'totaldiscount' => rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc),
                          'code' => $code[$key],
                          'unit' => $product_unit[$key],
                          'damaged_qty' => $damaged_qty[$key],
                          'account_number' => $account_number[$key],
                          'product_cost' => $product_cost[$key]
                      );
                        // // cost of goods transaction
                        // $total_product_cost = rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc);
                        $total_product_cost = numberClean($product_cost[$key])*numberClean($product_qty[$key]);
                        $cost_of_goods_data =  [
                            'acid' => $default_cost_of_goods_account,
                            'type' => 'Expense',
                            'cat' => 'Invoice Return',
                            'credit' => $total_product_cost,
                            'eid' => $this->session->userdata('id'),
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
                            'cat' => 'Invoice Return',
                            'debit' => $total_product_cost,
                            'eid' => $this->session->userdata('id'),
                            'date' => date('Y-m-d'),
                            'transaction_number'=>$transaction_number,
                        ];
                        $this->db->set('lastbal', 'lastbal + ' . $total_product_cost, FALSE);
                        $this->db->where('acn', $default_inventory_account);
                        $this->db->update('cberp_accounts'); 
                        $this->db->insert('cberp_transactions', $inventory_data);


                        //erp2024 totaldiscount transaction 11-11-2024 ends
                      $productlist[$prodindex] = $data;
                      $i++;
                      $prodindex++;
                      $this->invocies->product_qty_update_to_invoice_items_table($invoice_id, $product_id[$key], numberClean($product_qty[$key]), numberClean($damaged_qty[$key]),numberClean($product_subtotal[$key]));


                      //product quantity update
                      $prdQuantity = numberClean($product_qty[$key]);
                      $this->db->set('qty', "qty+$prdQuantity", FALSE);
                      $this->db->where('pid', $product_id[$key]);
                      $this->db->update('cberp_products');

                      //erp2024 check transfer warehoues 13-06-2024
                        $this->db->select('id,stock_qty');
                        $this->db->from('cberp_product_to_store');
                        $this->db->where('product_id', $product_id[$key]);
                        $this->db->where('store_id', $store_id);
                        $checkquery = $this->db->get();
                        $check_result = $checkquery->row_array();                    
                        $chekedID = (!empty($check_result))?$check_result['id']:"0";
                        $transferqty = numberClean($product_qty[$key]);
                        if($chekedID>0){
                            $existingQty = $check_result['stock_qty'];
                            $current_stock = ($existingQty>0)? $existingQty+$transferqty :$transferqty;
                            $data3['stock_qty'] = $current_stock;
                            $data3['updated_by'] = $this->session->userdata('id');
                            $data3['updated_dt'] = date('Y-m-d H:i:s');
                            $this->db->where('id', $chekedID);
                            $this->db->update('cberp_product_to_store', $data3);
                        }
                        insert_data_to_average_cost_table($product_id[$key], $product_cost[$key],numberClean($product_qty[$key]), get_costing_transation_type("Invoice Return"));
                    //   if($payment_type=='Customer Credit') 
                    //   {
                     
                        // insert_return_transaction('debit', 'Invoice Return', rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc), $account_number[$key], $transaction_number);

                        // update_account_balance($account_number[$key], rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc), 'add');
             
                    //   }
                  }
                  $amt = numberClean($product_qty[$key]);
                  if ($product_id[$key] > 0) {
                      $itc += $amt;
                  }
              }
              
              if ($prodindex > 0) {
                  $this->db->insert_batch('cberp_stock_returns_items', $productlist);
                  $stock_return_data = ['discount' => rev_amountExchange_s(amountFormat_general($total_discount), $currency, $this->aauth->get_user()->loc), 'tax' => rev_amountExchange_s(amountFormat_general($total_tax), $currency, $this->aauth->get_user()->loc), 'items' => $itc];
                
                  $this->db->update('cberp_invoices',['creditnote_status'=>'Approved','creditnote_id'=>$invocieno],['id'=>$invoice_id]);

                  history_table_log('cberp_invoice_return_log','invoice_return_id',$invocieno,'Create');
                    // erp2025 09-01-2025 starts

                    detailed_log_history('Invoice',$invoice_id,'Product Returned', $_POST['changedFields']);	
                    detailed_log_history('Invoicereturn',$invocieno,'Created', $_POST['changedFields']);
                    // erp2025 09-01-2025 ends

                  //FOR PAID INVOICE
                  $default_bank_account = default_bank_account();
                //   $invoice_receivable_account_details =  $default_bank_account['code'];
                  if($payment_type=='Customer Credit')
                  {           
                    $creditlimits = get_customer_credit_limit($customer_id);
                    update_customer_credit($customer_id, $total,$creditlimits['avalable_credit_limit']);                    
                    $stock_return_data['payment_status'] = 'Paid';
                    $stock_return_data['status'] = 'accepted';
                    $stock_return_data['return_status'] = 'Sent';                    
                  }

                  $invoice_receivable_account_details =  default_chart_of_account('accounts_receivable');
                  $this->db->update('cberp_stock_returns',$stock_return_data,['id'=> $invocieno]);
                    $receivable_data = [
                        'acid' => $invoice_receivable_account_details,
                        // 'account' => $invoice_receivable_account_details['holder'],
                        'type' => 'Asset',
                        'cat' => 'Invoice Return',
                        'credit' => numberClean($total),
                        'eid' => $this->session->userdata('id'),
                        'date' => date('Y-m-d'),
                        'transaction_number'=>$transaction_number
                    ];
                    $this->db->insert('cberp_transactions',$receivable_data);

                    $this->db->set('lastbal', 'lastbal - ' .$total, FALSE);
                    $this->db->where('acn', $invoice_receivable_account_details);
                    $this->db->update('cberp_accounts'); 



                  $invoice_return_account = default_chart_of_account('sales_returns');
                  $invoice_return_data = [
                    'acid' => $invoice_return_account,
                    // 'account' => $invoice_receivable_account_details['holder'],
                    'type' => 'Asset',
                    'cat' => 'Invoice Return',
                    'debit' => numberClean($total),
                    'eid' => $this->session->userdata('id'),
                    'date' => date('Y-m-d'),
                    'transaction_number'=>$transaction_number
                  ];
                  $this->db->insert('cberp_transactions',$invoice_return_data);
                  $this->db->set('lastbal', 'lastbal + ' .$total, FALSE);
                  $this->db->where('acn', $invoice_return_account);
                  $this->db->update('cberp_accounts'); 

                

                //erp2024 totaldiscount transaction 11-11-2024 starts
                if($total_discount>0)
                {
                    $discount_account_details = default_chart_of_account('sales_discount');
                    $discount_data = [
                        'acid' => $discount_account_details,
                        'type' => 'Asset',
                        'cat' => 'Invoice Return',
                        'credit' => $total_discount,
                        'eid' => $this->session->userdata('id'),
                        'date' => date('Y-m-d'),
                        'transaction_number'=>$transaction_number
                    ];
                    $this->db->insert('cberp_transactions',$discount_data);
                    $this->db->set('lastbal', 'lastbal - ' .$total_discount, FALSE);
                    $this->db->where('acn', $discount_account_details);
                    $this->db->update('cberp_accounts'); 
                }
                if($order_discount)
                {
                    $order_discount_account_details = default_chart_of_account('order_discount');
                    $discount_data1 = [
                        'acid' => $order_discount_account_details,
                        'type' => 'Asset',
                        'cat' => 'Invoice Return',
                        'credit' => $order_discount,
                        'eid' => $this->session->userdata('id'),
                        'date' => date('Y-m-d'),
                        'transaction_number'=>$transaction_number
                    ];
                    $this->db->insert('cberp_transactions',$discount_data1);
                    $this->db->set('lastbal', 'lastbal - ' .$order_discount, FALSE);
                    $this->db->where('acn', $order_discount_account_details);
                    $this->db->update('cberp_accounts'); 
                }

                // if($shipping_amount)
                // {
                //     $shipping_account_details = default_chart_of_account('shipping');
                //     $shipping_data1 = [
                //         'acid' => $shipping_account_details,
                //         'type' => 'Asset',
                //         'cat' => 'Invoice Return',
                //         'debit' => $shipping_amount,
                //         'eid' => $this->session->userdata('id'),
                //         'date' => date('Y-m-d'),
                //         'transaction_number'=>$transaction_number
                //     ];
                //     $this->db->insert('cberp_transactions',$shipping_data1);
                //     $this->db->set('lastbal', 'lastbal + ' .$shipping_amount, FALSE);
                //     $this->db->where('acn', $shipping_account_details);
                //     $this->db->update('cberp_accounts'); 
                // }

              
                  
                //   $log = [
                //       'stock_return_id' => $invocieno,
                //       'purchase_id' => $this->input->post('purchase_id', true),
                //       'performed_by' => $this->session->userdata('id'),
                //       'performed_dt' => date("Y-m-d H:i:s"),
                //       'action_performed' => 'Stock Receipt Prepared',
                //   ];
                //   $this->db->insert('supplier_stock_return_log', $log);
              } else {
                  echo json_encode(array('status' => 'Error', 'message' =>"Please choose product from product list. Go to Item manager section if you have not added the products."));
                  $transok = false;
              }
             
              // erp2024 update customer credit limit 11-09-2024
              // $this->load->model('transactions_model', 'transactions');
              // $custdata = $this->transactions->check_customer_account_details($customer_id);
              // $custcredit_limit = $custdata['credit_limit'];
              // $cust_avalable_credit_limit = (!empty($custdata['avalable_credit_limit'])) ? $custdata['avalable_credit_limit']: 0;
  
              // $subamount = $cust_avalable_credit_limit + $grandsubtotal;
              // $this->db->set('avalable_credit_limit', $subamount, FALSE);
              // $this->db->where('id', $customer_id);
              // $this->db->update('cberp_customers');
  
              //update delivery return set if once converted to delivery note
            //   $delivery_return_number = $this->input->post('delivery_return_number');
            //   $this->db->update('cberp_delivery_returns',['convert_to_credit_note_flag'=>'1'],['delivery_return_number'=>$delivery_return_number]);
              // erp2024 update customer credit limit 11-09-2024 ends
  
  
            //   $targeturl = base_url("stockreturn/view?id=$invocieno");
            //   $createurl = base_url("stockreturn/$new_u");
              // echo json_encode(array(
              //     'status' => 'Success',
              //     'message' => $this->lang->line('ADDED') . " <a href='".$targeturl."' class='btn btn-secondary btn-sm'><span class='fa fa-eye' aria-hidden='true'></span> View</a> <a href='" . $createurl . "' class='btn btn-secondary btn-sm'><span class='fa fa-plus-circle' aria-hidden='true'></span> Create</a>"
              // ));
              echo json_encode(['status' => 'Success', 'message' => 'Successfully completed','data'=>$payment_type,'returnid'=>$invocieno]);
              
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

    public function load_product_accounts()
    {
        $header = $this->input->post('actheader', true);
        $accountnumber = $this->input->post('accountnumber', true);
        $accountlist = coa_account_list_by_header($header);
        $options ="";
        if($accountlist)
        {
            foreach ($accountlist as $key => $value) {
                $acn = $value['acn'];
                $holder = $value['holder'];
                $sel = ($accountnumber==$acn) ? 'selected' : '' ;
                $options .= '<option value="'.$acn.'" '.$sel.'>'.$acn." - ".$holder.'</option>';
            }
        }
     
        echo json_encode(['status' => 'Success', 'data' => $options]);
    }



    public function cancelinvoiceaction()
    {
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
        // if (!$this->aauth->premission(1)) {
        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        // }
        $invoiceid = $this->input->post('invoiceid');
        $cancel_reason = $this->input->post('cancel_reason');
        $invoicedetails = $this->invocies->check_invoice_ispaid($invoiceid);
        $invoice_data = [];
        if(($invoicedetails) && $invoicedetails['status']=='paid' || $invoicedetails['status']=='partial')
        {
            $this->invocies->reset_invoice_payment_accounts($invoicedetails['payment_transaction_number']);
            $invoice_data =[
                'pamnt'=>0.00,
                'payment_recieved_amount'=>0.00,
                'payment_recieved_date' => NULL,
            ];
        }
        $transaction_data = $this->invocies->transaction_number_invoiceid($invoiceid); 
        $this->invocies->reset_credit_accounts($transaction_data['transaction_number']);
        $this->invocies->reset_debit_accounts($transaction_data['transaction_number']);        
        $this->invocies->cancel_transactions($transaction_data['transaction_number']);        
        // $this->invocies->reset_transaction_amounts($transaction_data['transaction_number']);  
        $invoice_data['status'] = 'Draft';
        $invoice_data['cancel_reason'] = $cancel_reason;
        $this->db->where('id', $invoiceid);
        $this->db->update('cberp_invoices',$invoice_data);
        history_table_log('cberp_invoice_log','invoice_id',$invoiceid,'Cancelled');
        // erp2025 09-01-2025 starts

        detailed_log_history('Invoice',$invoiceid,'Cancelled', $_POST['changedFields']);	
        // erp2025 09-01-2025 ends
        echo json_encode(array('status' => 'Success'));
    }


    public function deleteinvoiceaction()
    {
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
        // if (!$this->aauth->premission(1)) {
        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        // }
        $invoiceid = $this->input->post('invoiceid');
        $invoice_number = $this->input->post('invoice_number');
        $invoicedetails = $this->invocies->check_invoice_ispaid($invoiceid);
        $invoice_data = [];
        if(($invoicedetails) && $invoicedetails['status']=='paid' || $invoicedetails['status']=='partial')
        {
            echo json_encode(array('status' => 'Success'));
        }
        
        $invoice_data['status'] = 'Deleted';
        $invoice_data['invoice_number'] = $invoice_number."-DELETED";
        $this->db->where('id', $invoiceid);
        $this->db->update('cberp_invoices',$invoice_data);
        history_table_log('cberp_invoice_log','invoice_id',$invoiceid,'Deleted');
        // erp2025 09-01-2025 starts

        detailed_log_history('Invoice',$invoiceid,'Deleted', '');	
        // erp2025 09-01-2025 ends
        echo json_encode(array('status' => 'Success'));
    }

    // customer payment #erp2024 09-09-2024
    public function payment_return_to_customer()
    {
        $this->load->model('accounts_model');
        $data['acclist'] = $this->accounts_model->accountslist((integer)$this->aauth->get_user()->loc);
        $tid = $this->input->get('id');
        $customerid = $this->input->get('csd');
        $data['invoice'] = $this->invocies->invoice_credit_note_master_details_by_id($tid);
        $head['usernm'] = $this->aauth->get_user()->username;
        $head['title'] = "Payment Return -  " . $data['invoice']['tid'];
        $this->load->view('fixed/header', $head);
        $data['employee'] = $this->invocies->employee($data['invoice']['eid']);
        $data['custom_fields'] = $this->custom->view_fields_data($tid, 2);        
        $data['accountheaders'] = $this->accounts_model->load_coa_account_headers();
        $data['accounttypes'] = $this->accounts_model->load_coa_account_types();
        $data['accountlist'] = $this->accounts_model->load_account_list();
        $data['prefix'] = get_prefix_72();
        //echo "<pre>"; print_r($data['invoice']); die();
        $accountchild=[];
        foreach($data['accountlist'] as $single){
            $accountchild[$single['coa_header_id']][] = $single;
        } 
        $data['accountlists'] = $accountchild;
        $data['bankaccounts'] = bank_account_list();
        $data['default_bankaccount'] = default_bank_account();
        $data['default_receivableaccount'] = default_chart_of_account('sales_returns');
        if ($data['invoice']['id']) {
            $data['invoice']['id'] = $tid;            
            // $data['trackingdata'] = tracking_details('invoice_id',$tid);
            $this->load->view('invoices/payment_return_to_customer', $data);
        }
        $this->load->view('fixed/footer');
    }

    public function invoicereturn_print()
    {

        //ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL); 
        $data['prefix'] = get_prefix_72();
        $stockreturn_id = $this->input->get('delivery', true);
        $customer_id = $this->input->get('cust', true);
        $data["delevery_note_id"] = $stockreturn_id;
        $client = "";
        $data['CustDetails'] = get_customer_details($customer_id);
        if(!empty($data['CustDetails'])){ 
            $client = '' . $data['CustDetails'][0]['name'] . '<br>' . $data['CustDetails'][0]['address'] . ','. $data['CustDetails'][0]['city'] .' <br>' . $data['CustDetails'][0]['phone'] . '<br>' .$data['CustDetails'][0]['email'] ;
        
            $data["custId"]=$data['CustDetails'][0]['id'];
        }
        $data["client"]=$client;
       
        $loc = location($this->aauth->get_user()->loc);
        $data['companyNanme']=$loc['cname'];
        $company = '' . $loc['address'] . '<br>' . $loc['city'] . ', ' . $loc['region'] . '<br>' . $loc['country'] . ' -  ' . $loc['postbox'] . '<br>' . $this->lang->line('Phone') . ': ' . $loc['phone'] . '<br> ' . $this->lang->line('Email') . ': ' . $loc['email'];
        $data['lang']['company'] = $company;

        // ==================================================================
        $this->db->select('cberp_stock_returns.*, cberp_stock_returns_items.*,cberp_products.product_code,cberp_products.product_name,cberp_products.unit AS productunit,cberp_invoices.invoice_number');
        $this->db->from('cberp_stock_returns');
        $this->db->join('cberp_stock_returns_items', 'cberp_stock_returns.id = cberp_stock_returns_items.tid');
        $this->db->join('cberp_invoices', 'cberp_invoices.id = cberp_stock_returns.invoice_id');
        
        $this->db->join('cberp_products', 'cberp_products.pid = cberp_stock_returns_items.pid');
        $this->db->where('cberp_stock_returns.id', $stockreturn_id);
        $query = $this->db->get();
        $data['products'] = $query->result_array();
        $html = $this->load->view('invoices/invoicereturnreprintpdf-' . LTR, $data, true);         
        ini_set('memory_limit', '64M');
        $this->load->library('pdf');
        $pdf = $this->pdf->load();
        $pdf->WriteHTML($html);       
        $pdf->Output('reprint-note' . $pay_acc . '.pdf', 'I');       
            
    }
    

    

    public function return_view()
    {
     
        $this->load->model('accounts_model');
        $data['acclist'] = $this->accounts_model->accountslist((integer)$this->aauth->get_user()->loc);
        $tid = $this->input->get('id');
        $data['invoice'] = $this->invocies->invoice_details($tid, $this->limited);
        $data['merged_deliverynote'] = ($data['invoice']['invoice_type']=='Deliverynote') ? $this->invocies->delnote_by_invoice_number($data['invoice']['invoice_number']):"";
        
        // echo "<pre>"; print_r($data['invoice']); die();
        $data['delnotedetails'] = $this->invocies->delnote_details($data['invoice']['delevery_note_id']);
        $data['trackingdata'] = tracking_details('deliverynote_id',$data['invoice']['delevery_note_id']);
        $data['attach'] = $this->invocies->attach($tid);
        $data['c_custom_fields'] = $this->custom->view_fields_data($data['invoice']['cid'], 1);
        $data['paymentmethod_details'] = $this->invocies->payment_method_details($tid);
        
        

        $head['usernm'] = $this->aauth->get_user()->username;
        $head['title'] = "Invoice " . $data['invoice']['tid'];
        $this->load->view('fixed/header', $head);
        $data['products'] = $this->invocies->invoice_products($tid);
        if ($data['invoice']['id']) $data['activity'] = $this->invocies->invoice_transactions($tid);
        $data['employee'] = $this->invocies->employee($data['invoice']['eid']);
        // erp2024 20-11-2024 starts
        $data['payment_records'] = $this->invocies->invoice_payments_received($tid);
        $data['journals_records'] = ($data['invoice']['invoice_type']=='Deliverynote') ? $this->invocies->get_deliverynote_invoice_transaction_details($data['invoice']['invoice_number']):$this->invocies->get_invoice_transaction_details($tid);
        // echo "<pre>"; print_r($data['journals_records']); die();
        // erp2024 20-11-2024 ends
        $data['custom_fields'] = $this->custom->view_fields_data($tid, 2);
        if ($data['invoice']['id']) {
            $data['invoice']['id'] = $tid;            
            // $data['trackingdata'] = tracking_details('invoice_id',$tid);
            $this->load->view('invoices/view', $data);
        }
        $this->load->view('fixed/footer');
    }

    public function customerenquiry_complete_action(){
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $enquiry_status = $this->input->post('enquiry_status');
            $email_contents = $this->input->post('email_contents');
            if ($email_contents == '<p><br></p>') {
                $email_contents = '';
            }
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
                'assigned_to' => '',
                'accepted_dt' => NULL,
                'comments' => ($this->input->post('enquiry_status')=='Closed')?$this->input->post('comments'):'',
                'note' => $this->input->post('note'),
                'email_contents' => $email_contents,
                'enquiry_status' => "Completed",
                'updated_by' => $this->session->userdata('id'),
                'updated_date' => date('Y-m-d'),
                'pickup_flag' => '0',
                'pickup_date' => NULL,
                'picked_by' => '',
                'customer_reference_number' => $this->input->post('customer_reference_number'),
                'customer_contact_person' => $this->input->post('customer_contact_person'),
                'customer_contact_number' => $this->input->post('customer_contact_number'),
                'customer_contact_email' => $this->input->post('customer_contact_email')
            );
            if(!empty($enquiry_data)){
                $lead_id = $this->input->post('lead_id');
                $this->db->where('lead_id', $lead_id);
                $this->db->update('cberp_customer_leads', $enquiry_data);
    
                //data added to log                  
                master_table_log('customer_general_enquiry_log',$lead_id,'Lead process completed, not converted to a quote');
                //erp2024 06-01-2025 detailed history log starts
                detailed_log_history('Lead',$lead_id,'Lead process completed, not converted to a quote', $_POST['changedFields']);
                //erp2024 06-01-2025 detailed history log ends 
 
 
                $config['upload_path'] = FCPATH . 'uploads/';
                $config['allowed_types'] = 'pdf|jpg|jpeg|png|csv|xls|xlsx';
                $config['encrypt_name'] = TRUE;
                $this->load->library('upload', $config);
                $files = $_FILES['upfile']; // Get uploaded files array
                
                // ==================================================//
                //Product Data
                $pid = $this->input->post('pid');
                $productlist = array();
                $customerdata_details =[];
                $prodindex = 0;     
                $grandtotal = 0;           
                $product_id = $this->input->post('pid');
                $product_name1 = $this->input->post('product_name', true);
                $code = $this->input->post('code', true);
                $product_qty = $this->input->post('product_qty');
                $product_price = $this->input->post('product_price');
                $product_tax = $this->input->post('product_tax');
                $product_discount = $this->input->post('product_discount');
                $product_amt = $this->input->post('product_amt');
                $product_subtotal = $this->input->post('product_subtotal');
                $ptotal_tax = $this->input->post('taxa');
                $ptotal_disc = $this->input->post('disca');
                $product_des = $this->input->post('product_description', true);
                $product_hsn = $this->input->post('hsn');
                $product_unit = $this->input->post('unit');
                $discount_type = $this->input->post('discount_type');
                $min_price = $this->input->post('lowest_price');
                $max_disrate = $this->input->post('maxdiscountrate');
                $this->db->delete('cberp_customer_lead_items', array('tid' => $lead_id));
                if(!empty($pid))
                {
                    // $this->db->delete('cberp_customer_lead_items', array('tid' => $lead_id));
                  
                    foreach ($pid as $key => $value) {
                        $prdsubtotal = rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc);
                        $grandtotal = $grandtotal + $prdsubtotal;
                        $total_discount += numberClean(@$ptotal_disc[$key]);
                        $total_tax += numberClean($ptotal_tax[$key]);
    
                        if($discount_type[$key]=="Amttype"){
                            $discountamount = numberClean($product_amt[$key]);
                        }
                        else{
                            $discountamount = numberClean($product_discount[$key]);
                        }
    
                        $data = array(
                            'tid' => $lead_id,
                            'pid' => $product_id[$key],
                            'product' => $product_name1[$key],
                            'code' => $code[$key],
                            'code' => $product_hsn[$key],
                            'qty' => numberClean($product_qty[$key]),
                            'price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
                            'tax' => numberClean($product_tax[$key]),
                            'discount' => $discountamount,
                            'subtotal' => rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc),
                            'totaltax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                            'totaldiscount' => rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc),
                            //'product_des' => $product_des[$key],
                            //'unit' => $product_unit[$key]
                            'discount_type' => $discount_type[$key],
                            'lowest_price' => $min_price[$key],
                            'max_disrate' => $max_disrate[$key],
                            
                        );
    
                        $flag = true;
                        $productlist[$prodindex] = $data;
                        $i += numberClean($product_qty[$key]);;
                        $prodindex++;
                        $customerdata_details['lead_id'] = $customer_enqid;
                        $customerdata_details['product_id'] = (int)$product_id[$key];
                        $customerdata_details['product_qty'] = (int)numberClean($product_qty[$key]);
                        $this->db->insert('customer_enquiry_items', $customerdata_details);
                    }
                    if(!empty($productlist)){
                        $this->db->insert_batch('cberp_customer_lead_items', $productlist);
                    }
                    
                    // $this->db->where('lead_id', $lead_id);
                    // $this->db->update('cberp_customer_leads', ['total' => $grandtotal]);
                    
                }
                $this->db->where('lead_id', $lead_id);
                $this->db->update('cberp_customer_leads', ['total' => $grandtotal]);
                // ==================================================//
    
                if(!empty($files))
                {
                    $uploaded_data['lead_id'] = $lead_id;
                    $uploaded_data['lead_number'] = $this->input->post('lead_number');
                    foreach ($files['name'] as $key => $filename) {
                        $_FILES['userfile']['name'] = $files['name'][$key];
                        $uploaded_data['actual_name'] = $files['name'][$key];
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
    public function customerenquiry_to_quote_action()
    {

         
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $enquiry_status = $this->input->post('enquiry_status');
            $email_contents = $this->input->post('email_contents');
            if ($email_contents == '<p><br></p>') {
                $email_contents = '';
            }
            $customer_id = $this->input->post('customer_id');
            if($this->input->post('customerType')=='new'){
                $customer_data = array(
                    'name' => $this->input->post('customer_name'),
                    'phone' => $this->input->post('customer_phone'),
                    'email' => $this->input->post('customer_email'),
                    'address' => $this->input->post('customer_address')
                );
                $customer_id = $this->invocies->create_customer($customer_data);
            }
            $enquiry_data = array(
                'lead_number' => $this->input->post('lead_number'),
                'customer_type' => $this->input->post('customerType'),
                'customer_id' => $customer_id,
                'date_received' => $this->input->post('date_received'),
                'due_date' => $this->input->post('due_date'),
                'source_of_enquiry' => $this->input->post('source_of_enquiry'),
                'assigned_to' => ($this->input->post('enquiry_status')=='Assigned')?$this->input->post('assignedto'):$this->session->userdata('id'),                
                'note' => $this->input->post('note'),
                'email_contents' => $email_contents,
                'enquiry_status' => 'Closed',
                'updated_by' => $this->session->userdata('id'),
                'updated_date' => date('Y-m-d'),
                'pickup_flag' => '0',
                'pickup_date' => NULL,
                'picked_by' => '',
                'customer_reference_number' => $this->input->post('customer_reference_number'),
                'customer_contact_person' => $this->input->post('customer_contact_person'),
                'customer_contact_number' => $this->input->post('customer_contact_number'),
                'customer_contact_email' => $this->input->post('customer_contact_email')
            );
            if(!empty($enquiry_data)){
                
                $currency = $this->config->item('currency');              
                // rev_amountExchange_s($this->input->post('total'), $currency, $this->aauth->get_user()->loc),

                // erp2024 insert data to quote master $this->db->insert('cberp_transaction_tracking'

                $quote_number = $this->prifix51['quote_prefix'].$this->quote->lastquote();
                $enquiry_data['comments'] = "Lead converted to quote. Quote : #".($quote_number);
                $lead_id = $this->input->post('lead_id');
                if($lead_id)
                {                    
                    $this->db->where('lead_id', $lead_id);
                    $this->db->update('cberp_customer_leads', $enquiry_data);
                }
                else{
                    $enquiry_data['created_by'] = $this->session->userdata('id');
                    $enquiry_data['created_date'] = date('Y-m-d');
                    $this->db->insert('cberp_customer_leads', $enquiry_data);
                    $lead_id = $this->db->insert_id();   
                }
                $quote_prefix = $this->prifix51['quote_prefix'];
                $validity = default_validity();
                $quote_invoiceduedate = date('Y-m-d', strtotime(date('Y-m-d') . " +" . (int)$validity['quote_validity'] . " days"));
                $quote_master_data = array(
                'quote_number' => $quote_number,
                'quote_date' => date('Y-m-d'),
                'due_date' => $quote_invoiceduedate,
                'subtotal' => rev_amountExchange_s($this->input->post('total'), $currency, $this->aauth->get_user()->loc),
                'discount' => $total_discount,
                'total' => rev_amountExchange_s($this->input->post('total'), $currency, $this->aauth->get_user()->loc),
                'customer_id' =>  $this->input->post('customer_id'),
                'discount_status' => 0,
                'loc' => $this->aauth->get_user()->loc,
                'customer_reference_number' => $this->input->post('customer_reference_number'),
                'customer_contact_person' => $this->input->post('customer_contact_person'),
                'customer_contact_number' => $this->input->post('customer_contact_number'),
                'customer_contact_email' => $this->input->post('customer_contact_email'),
                'created_by' => $this->session->userdata('id'),
                'created_date' => date('Y-m-d H:i:s'),
                'prepared_by' => $this->session->userdata('id'),
                'prepared_date' => date('Y-m-d H:i:s'),
                'prepared_flag' => '1',
                'lead_number' => $this->input->post('lead_number'),
                'tax_status'=>'no',
                'status'=>'pending',
               );               
               $this->db->insert('cberp_quotes', $quote_master_data);
             
               $quote_id = $this->db->insert_id();   
               
               //erp2024 insert to authorization history table /////////////////////////////////////
              
               $history['function_type'] = 'Quote';
               $history['function_id'] = $quote_number;
               $history['requested_by'] = $this->session->userdata('id');
               $history['requested_date'] = date("Y-m-d");
               $history['requested_amount'] = rev_amountExchange_s($this->input->post('total'), $currency, $this->aauth->get_user()->loc);               
               $this->db->insert('authorization_history',$history);
              
               $this->db->insert('cberp_transaction_tracking',['lead_id'=>$lead_id,'lead_number'=>$this->input->post('lead_number'),'quote_number'=>$quote_number]);
                // insertion_to_tracking_table_sales_to_invoice('quote_id',$quote_id,'quote_number',$quote_number, 'lead_id', $lead_id);

               ////////////////////////////////////////////////////////////////////////////////////////
              
                //data added to log                  
                // erp2025 09-01-2025 starts
                detailed_log_history('Quote',$quote_number,'Created', $_POST['changedFields']);	
                detailed_log_history('Lead',$lead_id,'Converted to Quote', $_POST['changedFields']);	
                // erp2025 09-01-2025 starts

                $config['upload_path'] = FCPATH . 'uploads/';
                $config['allowed_types'] = 'pdf|jpg|jpeg|png|csv|xls|xlsx';
                $config['encrypt_name'] = TRUE;
                $this->load->library('upload', $config);
                $files = $_FILES['upfile']; // Get uploaded files array
                
                // ==================================================//
                //Product Data
                $pid = $this->input->post('pid');
                $productlist = array();
                $customerdata_details =[];
                $prodindex = 0;     
                $grandtotal = 0;           
                $product_id = $this->input->post('pid');
                $product_name1 = $this->input->post('product_name', true);
                $code = $this->input->post('code', true);
                $product_qty = $this->input->post('product_qty');
                $product_price = $this->input->post('product_price');
                $product_tax = $this->input->post('product_tax');
                $product_discount = $this->input->post('product_discount');
                $product_amt = $this->input->post('product_amt');
                $product_subtotal = $this->input->post('product_subtotal');
                $ptotal_tax = $this->input->post('taxa');
                $ptotal_disc = $this->input->post('disca');
                $product_des = $this->input->post('product_description', true);
                $product_hsn = $this->input->post('hsn');
                $product_unit = $this->input->post('unit');
                $discount_type = $this->input->post('discount_type');
                $min_price = $this->input->post('lowest_price');
                $max_disrate = $this->input->post('maxdiscountrate');
                if($lead_id){ $this->db->delete('cberp_customer_lead_items', array('lead_id' => $lead_id)); }
                
                $quoteitems_list = [];
                $grand_discountamt = 0;
                $totalsitems = 0;
                if(!empty($product_name1))
                {
                    $totalsitems = count($product_name1);
                    
                    foreach ($product_name1 as $key => $value) {
                       if($product_name1[$key])
                        {
                            // $prdsubtotal = rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc);
                            // $grandtotal = $grandtotal + $prdsubtotal;
                            // $total_discount += numberClean(@$ptotal_disc[$key]);
                            // $total_tax += numberClean($ptotal_tax[$key]);

                            // if($discount_type[$key]=="Amttype"){
                            //     $discountamount = numberClean($product_amt[$key]);
                            // }
                            // else{
                            //     $discountamount = numberClean($product_discount[$key]);
                            // }
                            // $grand_discountamt += rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc);
                            // $data = array(
                            //     'lead_id' => $lead_id,
                            //     'product_code' => $code[$key],
                            //     'quantity' => numberClean($product_qty[$key]),
                            //     'price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
                            //     'tax' => numberClean($product_tax[$key]),
                            //     'discount' => $discountamount,
                            //     'subtotal' => numberClean($prdsubtotal),
                            //     'total_tax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                            //     'total_discount' => rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc),
                            //     'unit' => $product_unit[$key],
                            //     'discount_type' => $discount_type[$key],
                            //     'lowest_price' => $min_price[$key],
                            //     'maximum_discount_rate' => $max_disrate[$key],
                                
                            // );

                            $prdsubtotal = rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc);
                            $grandtotal = $grandtotal + numberClean($product_subtotal[$key]);
                            $total_discount += numberClean(@$ptotal_disc[$key]);
                            $total_tax += numberClean($ptotal_tax[$key]);
                            if($discount_type[$key]=="Amttype"){
                                $discountamount = numberClean($product_amt[$key]);
                            }
                            else{
                                $discountamount = numberClean($product_discount[$key]);
                            }
                            $data = array(
                                'lead_id' => $lead_id,
                                'product_code' => $product_hsn[$key],
                                'quantity' => numberClean($product_qty[$key]),
                                'price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
                                'tax' => numberClean($product_tax[$key]),
                                'discount' => $discountamount,
                                'subtotal' => numberClean($product_subtotal[$key]),
                                'total_tax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                                'total_discount' => rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc),
                                'discount_type' => $discount_type[$key],
                                'lowest_price' => $min_price[$key],
                                'maximum_discount_rate' => $max_disrate[$key],
                            );
                            $quote_items = array(
                                'quote_number' => $quote_number,
                                'product_code' => $code[$key],
                                'quantity' => numberClean($product_qty[$key]),
                                'price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
                                'tax' => numberClean($product_tax[$key]),
                                'discount' => $discountamount,
                                'total_amount' => numberClean($product_subtotal[$key]),
                                'total_tax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                                'total_discount' => rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc),
                                'discount_type' => $discount_type[$key],
                            );

                            $flag = true;
                            $productlist[$prodindex] = $data;
                            $quoteitems_list[$prodindex] = $quote_items;
                            $i += numberClean($product_qty[$key]);
                            $prodindex++;
                        }
                    }

                    if(!empty($productlist)){
                        $this->db->insert_batch('cberp_customer_lead_items', $productlist);
                        $this->db->insert_batch('cberp_quotes_items', $quoteitems_list);        
                        $this->db->where('lead_id', $lead_id);
                        $this->db->update('cberp_customer_leads', ['total' => $grandtotal]);
                        
                        $this->db->where('quote_number', $quote_number);
                        $this->db->update('cberp_quotes', ['total' => $grandtotal,'discount' => $grand_discountamt]);         
                    }
                    
                    // $this->db->where('lead_id', $lead_id);
                    // $this->db->update('cberp_customer_leads', ['total' => $grandtotal]);
                    
                    
                }

                // ==================================================//

                if(!empty($files))
                {
                    $uploaded_data['lead_id'] = $lead_id;
                    $uploaded_data['lead_number'] = $this->input->post('lead_number');
                    foreach ($files['name'] as $key => $filename) {
                        $_FILES['userfile']['name'] = $files['name'][$key];
                        $uploaded_data['actual_name'] = $files['name'][$key];
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
                        }
                    }
                }
                header('Content-Type: application/json');
                echo json_encode(array('status' => 'Success','data' => $quote_number));

            }
            
        }
    }
   
}

