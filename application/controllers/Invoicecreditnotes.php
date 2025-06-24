<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\Printer;

class Invoicecreditnotes extends CI_Controller
{
    private $configurations;
    public function __construct()
    {       
        parent::__construct();        
        $this->load->model('Stockreturn_model', 'stockreturn');    
        $this->load->model('invoice_creditnotes_model', 'invocies_creditnote');
        $this->load->model('invoices_model', 'invocies');
         $this->load->model('plugins_model', 'plugins');
         $this->load->model('customer_enquiry_model', 'customer_enquiry');
         $this->load->model('costingcalculation_model', 'costingcalculation');
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
        
    }



    //invoices list
    public function index()
    {
        $data['permissions'] = load_permissions('Accounts','Invoices','Invoice Credit Notes','List');
        $head['title'] = "Manage Credit Notes";
        $head['usernm'] = $this->aauth->get_user()->username;
        // $condition = "WHERE invoice_id IS NOT NULL";
        // $data['counts'] = $this->invocies_creditnote->get_dynamic_count('cberp_stock_returns','invoicedate','total',$condition);
        $data['ranges'] = getCommonDateRanges();
        $data['counts'] = $this->invocies_creditnote->get_filter_count($data['ranges']);
        // print_r($data['counts']); die();
        $this->load->view('fixed/header', $head);
        $this->load->view('invoices/invoicecreditnotes_list',$data);
        $this->load->view('fixed/footer');
    }


    public function ajax_list()
    {
        $list = $this->invocies_creditnote->get_datatables($this->limited);
        $data = array();
        $no = $this->input->post('start');
        $prefixes = get_prefix_72();
        $prefix = $prefixes['invoicereturn_prefix'];
        foreach ($list as $invoices) {
            $no++;
            $row = array();
            $row[] = $no;
            $disablecls="";
            $bank_transaction_ref_number = $this->invocies_creditnote->bank_transaction_ref_number($invoices->invoice_retutn_number);
            $referencenumber = $bank_transaction_ref_number['trans_ref_number'];
            $payment_status="";
            if($invoices->payment_status=='Due')
            {
                $payment_status = ($referencenumber) ?'<a href="' . base_url("invoicecreditnotes/payment_return_to_customer_edit?id=" . $invoices->invoice_retutn_number . "&csd=" . $invoices->customer_id. "&ref=" . $referencenumber) . '" class="btn btn-crud btn-sm btn-secondary"><span class="fa fa-money"></span> Make Payment</a>' : '<a href="' . base_url("invoices/payment_return_to_customer?id=" . $invoices->invoice_retutn_number . "&csd=" . $invoices->customer_id) . '" class="btn btn-crud btn-sm btn-secondary"><span class="fa fa-money"></span> Make Payment</a>';
            }
            

            $row[] = '<a href="' . base_url("invoicecreditnotes/create?iid=$invoices->invoice_retutn_number") . '">' . $invoices->invoice_retutn_number . '</a>';
            // $row[] = '<a href="' . base_url("invoicecreditnotes/view?id=$invoices->id") . '">' . $prefix.$invoices->tid . '</a>';
            $row[] = '<a href="' . base_url("invoices/create?id=$invoices->invoice_number") . '">' . $invoices->invoice_number . '</a>';
            $row[] = $invoices->name;
            $row[] = $invoices->employee; 
            $row[] = ($invoices->created_date)? dateformat_time($invoices->created_date):"";
            $approveddate = "";
            // $approveddate = ($invoices->approved_dt)? dateformat_time($invoices->approved_dt):"";
            $row[] = $invoices->employee."<br>".$approveddate;
            // $row[] = ($invoices->approved_dt)? date('Y-m-d H:i:s', strtotime($invoices->approved_dt)):"";
            $row[] = number_format($invoices->total,2); 
            $row[] = '<span class="st-' . strtolower($invoices->payment_status) . '">' . $this->lang->line(ucwords($invoices->payment_status)) . '</span>';
            
            // invoices/payment_return_to_customer?id=3&csd=5
            // if($invoices->status=='post dated cheque')
            // {
            //     $status = '<span class="st-rejected">' . $this->lang->line(($invoices->status)) . '</span>';
            // }
            // else{
            //     $status = '<span class="st-' . $invoices->status . '">' . $this->lang->line(ucwords($invoices->status)) . '</span>';
            // }
            // $row[] = $status;
            // if($invoices->creditnote_status=='Pending')
            // {
            //     $creditnotestatus = '<span class="st-'.strtolower($invoices->creditnote_status).'">' . $this->lang->line(($invoices->creditnote_status)) . '</span>';
            //     $approveBtn = '<a href="' . base_url("invoicecreditnotes/approve_invoice_creditnote?id=$invoices->id") . '" class="btn btn-secondary btn-sm"  title="Approve"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i> Approve Now</a>';
            // }
            // else{
            //     $creditnotestatus = '<span class="st-accepted">' . $this->lang->line(ucwords($invoices->creditnote_status)) . '</span>';
            //     $approveBtn="";
            // }
            // $row[] = $creditnotestatus;
            $printbtn = '<a href="' . base_url("invoices/invoicereturn_print?delivery=" . $invoices->invoice_retutn_number . "&cust=" . $invoices->customer_id) . '" class="btn btn-crud btn-sm btn-secondary" target="_blank"><span class="fa fa-print"></span> Print</a>';

            $row[] = $payment_status." ".$printbtn;
            // $row[] = '<a href="' . base_url("invoicecreditnotes/create?iid=$invoices->id") . '" class="btn btn-crud btn-sm btn-secondary"><i class="fa fa-pencil"></i> Edit</a> '.$payment_status." ".$printbtn;

            // $row[] = '<a href="' . base_url("invoicecreditnotes/edit?id=$invoices->id") . '" class="btn btn-sm btn-secondary"><i class="fa fa-pencil"></i> Edit</a> '.$payment_status;
            $data[] = $row;
        }
        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->invocies_creditnote->count_all($this->limited),
            "recordsFiltered" => $this->invocies_creditnote->count_filtered($this->limited),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }
    

    // erp2024 04-11-2024 starts 
    public function approve_invoice_creditnote()
    {
        $tid = intval($this->input->get('id'));
        $data['id'] = $tid;
        $data['currency'] = $this->quote->currencies();
        $head['title'] = "Invoice Credit Note";
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['notemaster'] = $this->invocies_creditnote->invoice_by_id($tid);       
        $data['products'] = $this->invocies_creditnote->invoice_products_for_return($tid);
        // echo "<pre>"; print_r($data['products']); die();
        $this->load->model('Stockreturn_model', 'stockreturn');    
        $data['creditnotetid'] = $this->stockreturn->lastpurchase();
        $data['configurations'] = $this->configurations;       
        $this->load->view('fixed/header', $head);
        $this->load->view('invoices/invoicecreditnote_approve', $data);
        $this->load->view('fixed/footer');
    }
    public function invoice_creditnote_return_action1()
    {
          // echo "<pre>"; print_r(base_url("stockreturn/view?id=$invocie")); die();
          $currency = $this->input->post('mcurrency');
          $customer_id = $this->input->post('customer_id');
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
          $invocietid = $this->input->post('invocieno');
          $invoicedate = $this->input->post('invoicedate');
          $invocieduedate = $this->input->post('invoiceduedate');
          $invoice_id = $this->input->post('invoice_id');
          $creditnote_id = $this->input->post('creditnote_id');
          $total_tax = 0;
          $total_discount = 0;
          $discountFormat = $this->input->post('discountFormat');
          $invoice_return_number = $this->input->post('invoice_return_number');
          $pterms = $this->input->post('pterms');
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
          //Invoice Data
          $bill_date = datefordatabase($invoicedate);
          $bill_due_date = datefordatabase($invocieduedate);
          $grandsubtotal = 0;
          if (!$currency) $currency = 0; 
          $data = array('tid' => $invocieno, 'invoicedate' => $bill_date, 'invoiceduedate' => $bill_due_date, 'subtotal' => $subtotal, 'shipping' => $shipping, 'ship_tax' => $shipping_tax, 'ship_tax_type' => $ship_taxtype, 'total' => $total, 'notes' => $notes, 'csd' => $customer_id, 'eid' => $this->aauth->get_user()->id, 'taxstatus' => $tax, 'discstatus' => $discstatus, 'format_discount' => $discountFormat, 'refer' => $refer, 'term' => $pterms, 'loc' => $this->aauth->get_user()->loc, 'i_class' => 0, 'multi' => $currency, 'invoice_id' => $invoice_id,'prepared_dt'=>date('Y-m-d H:i:s'), 'prepared_flg'=>'1', 'prepared_by'=>$this->session->userdata('id'),'return_status'=>'Pending','payment_status'=>'Due');

          if($creditnote_id>0){
              $this->db->update('cberp_stock_returns', $data,['id'=>$creditnote_id]);
              $invocieno = $creditnote_id;            
              $this->db->delete('cberp_stock_returns_items', array('tid' => $creditnote_id));
          }
          else{
              $this->db->insert('cberp_stock_returns', $data);
              $invocieno = $this->db->insert_id();
          }
          
          if(!empty($invoice_id))
          {
            $this->db->update('cberp_invoices',['creditnote_status'=>'Pending'],['id'=>$invoice_id]);
              $tracking_data = [
                  'invoice_id' => $invoice_id,
                  'stock_return_id' => $invocieno,
                  'stock_return_number' => $invocietid,
              ];
              $this->db->insert('cberp_transaction_tracking', $tracking_data);
              
            insertion_to_tracking_table('stock_return_id',$invocieno,'stock_return_number',$invoice_return_number,'invoice_id',$invoice_id);
          }
          if ($invocieno) {
              
              $pid = $this->input->post('product_id');
              $productlist = array();
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
              $code = $this->input->post('prdcode');
              $damaged_qty = $this->input->post('damaged_qty');              
              
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
                          'subtotal' => rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc),
                          'totaltax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                          'totaldiscount' => rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc),
                          'code' => $code[$key],
                          'unit' => $product_unit[$key]
                      );
                      $productlist[$prodindex] = $data;
                      $i++;
                      $prodindex++;
                      $this->invocies->product_qty_update_to_invoice_items_table($invoice_id, $product_id[$key], numberClean($product_qty[$key]), numberClean($damaged_qty[$key]));
                  }
                  $amt = numberClean($product_qty[$key]);
                  if ($product_id[$key] > 0) {
                  //     if ($this->input->post('update_stock') == 'yes') {
  
                  //         if ($person_type) {
                  //             $this->db->set('qty', "qty+$amt", FALSE);
                  //         } else {
                  //             $this->db->set('qty', "qty-$amt", FALSE);
                  //         }
  
                  //         $this->db->where('pid', $product_id[$key]);
                  //         $this->db->update('cberp_products');
                  //     }
                      $itc += $amt;
                  }
              }
  
              if ($prodindex > 0) {
                  $this->db->insert_batch('cberp_stock_returns_items', $productlist);
                  $this->db->set(array('discount' => rev_amountExchange_s(amountFormat_general($total_discount), $currency, $this->aauth->get_user()->loc), 'tax' => rev_amountExchange_s(amountFormat_general($total_tax), $currency, $this->aauth->get_user()->loc), 'items' => $itc));
                  $this->db->where('id', $invocieno);
                  $this->db->update('cberp_stock_returns');
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
              echo json_encode(['status' => 'Success', 'message' => 'Successfully completed']);
              
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

    
    public function view()
    {
        $data['permissions'] = load_permissions('Accounts','Invoices','Invoice Credit Notes','View Page');
        $this->load->model('accounts_model');
        $data['acclist'] = $this->accounts_model->accountslist((integer)$this->aauth->get_user()->loc);
        $tid = $this->input->get('id');
        $data['invoice'] = $this->invocies_creditnote->invoice_return_details($tid);        
        $data['products'] = $this->invocies_creditnote->invoice_return_products($tid);
        // $data['merged_deliverynote'] = ($data['invoice']['invoice_type']=='Deliverynote') ? $this->invocies->delnote_by_invoice_number($data['invoice']['invoice_number']):"";
        
        // echo "<pre>"; print_r($data['products']); die();
        // $data['delnotedetails'] = $this->invocies->delnote_details($data['invoice']['delevery_note_id']);
        // $data['attach'] = $this->invocies->attach($tid);
        // $data['c_custom_fields'] = $this->custom->view_fields_data($data['invoice']['cid'], 1);
        // $data['paymentmethod_details'] = $this->invocies->payment_method_details($tid);
       
        
        $data['trackingdata'] = tracking_details('stock_return_id',$tid);
        $data['prefix'] = get_prefix_72();
        $head['usernm'] = $this->aauth->get_user()->username;
        $head['title'] = "Credit Notes" . $data['invoice']['tid'];
        $this->load->view('fixed/header', $head);
        $data['journals_records'] = $this->invocies_creditnote->get_journals_for_invoice_return($tid);        
        $data['payment_records'] = $this->invocies_creditnote->invoice_payments_received($tid);
        // erp2024 20-11-2024 ends
        //erp2024 06-01-2025 detailed history log starts
        $page = "Invoicereturn";
        $data['detailed_log']= $this->invocies_creditnote->get_detailed_log($tid,$page);
        $products = $data['detailed_log'];
        $groupedBySequence = []; // Initialize an empty array for grouping
  
        foreach ($products as $product) {
            $sequence = $product['seqence_number'];
            $groupedBySequence[$sequence][] = $product; // Group by sequence number
        }
          
        $data['groupedInvoicereturns'] = $groupedBySequence;
        $this->load->view('invoices/invoice_return_view', $data);
        $this->load->view('fixed/footer');
    }

    public function edit()
    {
        $tid = intval($this->input->get('id'));
        $data['id'] = $tid;
        $data['currency'] = $this->quote->currencies();
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['notemaster'] = $this->invocies_creditnote->invoice_return_details($tid); 
        $data['products'] = $this->invocies_creditnote->invoice_return_products($tid);
        $data['bank_transaction_ref_number'] = $this->invocies_creditnote->bank_transaction_ref_number($tid);
        $this->load->model('Stockreturn_model', 'stockreturn');    
        $data['configurations'] = $this->configurations;
        // $data['prefix'] = $this->configurations['invoiceprefix'];   
        // $data['configurations'] = $this->configurations;
        // $this->load->model('deliveryreturn_model', 'deliveryreturn');
        // $data['deliverynote_status'] = $this->deliveryreturn->deliverynote_status($tid);
        // $data['invoiceid'] = ($data['deliverynote_status']=='Invoiced') ? $this->deliveryreturn->invoice_details_by_delnoteid($tid):"";
        // $data['trackingdata'] = tracking_details('deliverynote_id',$tid);
        //  echo "<pre>"; print_r($data['products']); die();
        // $data['invoice_details'] = ($data['deliverynote_status']=="Invoiced") ? $this->deliveryreturn->invoice_details($tid) : "";
        $data['prefix'] = get_prefix(); 
        $head['title'] = "Invoice Return " . $data['prefix']['stockreturn_prefix'].$tid+1000;
        $this->load->view('fixed/header', $head);
        $this->load->view('invoices/invoicecreditnote_edit', $data);
        $this->load->view('fixed/footer');
    }


    public function payment_return_to_customer_edit()
    {
    //         ini_set('display_errors', 1);
    // ini_set('display_startup_errors', 1);
    // error_reporting(E_ALL);

        $this->load->model('accounts_model');
        $data['acclist'] = $this->accounts_model->accountslist((integer)$this->aauth->get_user()->loc);
        $tid = $this->input->get('id');
        $customerid = $this->input->get('csd');
        $bank_transaction_refernce = $this->input->get('ref');

        $data['transaction_details'] = $this->invocies_creditnote->get_invoice_return_details_bank_trans_number($bank_transaction_refernce);

        // echo "<pre>"; print_r($data['transaction_details']); die();

        $data['invoice'] = $this->invocies->invoice_credit_note_master_details_by_id($tid);
        
        $data['trans_numbers'] = $this->invocies_creditnote->get_bank_transaction_for_invoice_return($data['invoice']['tid']);

     
       
        $head['usernm'] = $this->aauth->get_user()->username;
        $head['title'] = "Payment Return -  " . $data['invoice']['tid'];
        $this->load->view('fixed/header', $head);
        $data['employee'] = $this->invocies->employee($data['invoice']['eid']);
        $data['custom_fields'] = $this->custom->view_fields_data($tid, 2);        
        $data['accountheaders'] = $this->accounts_model->load_coa_account_headers();
        $data['accounttypes'] = $this->accounts_model->load_coa_account_types();
        $data['accountlist'] = $this->accounts_model->load_account_list();
       
        $accountchild=[];
        foreach($data['accountlist'] as $single){
            $accountchild[$single['coa_header_id']][] = $single;
        } 
        $this->load->model('transactions_model', 'transactions');
        $data['transaction_ai'] = $this->transactions->get_transaction_ai_details($data['trans_numbers']['transcation_number']);
        //    die($this->db->last_query());
        //  echo "<pre>"; print_r($data['transaction_ai']); die();

        $data['accountlists'] = $accountchild;
        $data['bankaccounts'] = bank_account_list();
        $data['default_bankaccount'] =  $data['trans_numbers']['trans_account_id'];
        $data['default_receivableaccount'] = $data['trans_numbers']['trans_chart_of_account_id'];
        $data['prefix'] = get_prefix_72();
        if ($data['invoice']['id']) {
            $data['invoice']['id'] = $tid;            
            // $data['trackingdata'] = tracking_details('invoice_id',$tid);
            $this->load->view('invoices/payment_return_to_customer_edit', $data);
        }
        $this->load->view('fixed/footer');
    }

    public function delete_invoice_return_action()
    {
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
        // if (!$this->aauth->premission(1)) {
        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        // }
        
        $invoiceid = $this->input->post('invoiceid');
        $invoice_returnid = $this->input->post('invoice_returnid');
        // $cancel_reason = $this->input->post('cancel_reason');

        $tid = intval($this->input->get('id'));
        $data['id'] = $tid;
        $data['currency'] = $this->quote->currencies();
        $notemaster = $this->invocies_creditnote->invoice_return_details($invoice_returnid); 
        $products = $this->invocies_creditnote->invoice_return_products($invoice_returnid);
        $bank_transaction_ref_number = $this->invocies_creditnote->bank_transaction_ref_number($invoice_returnid);
        $transaction_number = $notemaster['transaction_number'];
        $default_cost_of_goods_account = default_chart_of_account('cost_of_goods_solid');
        $default_inventory_account = default_chart_of_account('inventory');
        $grand_total = 0;
        $total_discount = 0;
        $order_discount = $notemaster['order_discount'];
        if($products)
        {
            foreach($products as $row)
            {
                $grand_total += $row['subtotal'];
                $total_discount += $row['totaldiscount'];
                 // cost of goods transaction
                 $total_product_cost = $row['product_cost']*numberClean($row['qty']);
                 $cost_of_goods_data =  [
                     'acid' => $default_cost_of_goods_account,
                     'type' => 'Expense',
                     'cat' => 'Invoice Return',
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
                    'cat' => 'Invoice Return',
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
                $this->invocies_creditnote->product_qty_update_to_invoice_items_table_return($invoiceid, $row['pid'], $row['qty'], $row['damaged_qty'],$row['subtotal']);

            }
        }
        


        $invoice_receivable_account_details =  default_chart_of_account('accounts_receivable');
        // // $invoice_sale_revenue_account_details = get_account_details("Sales/Revenue");
        $latest_total = $grand_total;
        $latest_total = $grand_total-$notemaster['order_discount'];
        $receivable_data = [
            'acid' => $invoice_receivable_account_details,
            // 'account' => $invoice_receivable_account_details['holder'],
            'type' => 'Asset',
            'cat' => 'Invoice Return',
            'debit' => $latest_total,
            'eid' => $this->session->userdata('id'),
            'date' => date('Y-m-d'),
            'transaction_number'=>$transaction_number
        ];
        $this->db->insert('cberp_transactions',$receivable_data);
        $this->db->set('lastbal', 'lastbal + ' .$latest_total, FALSE);
        $this->db->where('acn', $invoice_receivable_account_details);
        $this->db->update('cberp_accounts'); 
        
        
        $invoice_return_account = default_chart_of_account('sales_returns');
        $invoice_return_data = [
        'acid' => $invoice_return_account,
        // 'account' => $invoice_receivable_account_details['holder'],
        'type' => 'Asset',
        'cat' => 'Invoice Return',
        'credit' => $latest_total,
        'eid' => $this->session->userdata('id'),
        'date' => date('Y-m-d'),
        'transaction_number'=>$transaction_number
        ];
        $this->db->insert('cberp_transactions',$invoice_return_data);
        $this->db->set('lastbal', 'lastbal - ' .$total, FALSE);
        $this->db->where('acn', $invoice_return_account);
        $this->db->update('cberp_accounts'); 

        // //erp2024 totaldiscount transaction 11-11-2024 starts
        if($total_discount>0)
        {
            $discount_account_details = default_chart_of_account('sales_discount');
            $discount_data = [
                'acid' => $discount_account_details,
                // 'account' => $discount_account_details['holder'],
                'type' => 'Asset',
                'cat' => 'Invoice Return',
                'debit' => $total_discount,
                'eid' => $this->session->userdata('id'),
                'date' => date('Y-m-d'),
                'transaction_number'=>$transaction_number
            ];
            $this->db->insert('cberp_transactions',$discount_data);
            $this->db->set('lastbal', 'lastbal + ' .$total_discount, FALSE);
            $this->db->where('acn', $discount_account_details);
            $this->db->update('cberp_accounts'); 
        }
        if($order_discount>0)
        {
            $order_discount_account_details = default_chart_of_account('order_discount');
            $discount_data1 = [
                'acid' => $order_discount_account_details,
                'type' => 'Asset',
                'cat' => 'Invoice Return',
                'debit' => $order_discount,
                'eid' => $this->session->userdata('id'),
                'date' => date('Y-m-d'),
                'transaction_number'=>$transaction_number
            ];
            $this->db->insert('cberp_transactions',$discount_data1);
            $this->db->set('lastbal', 'lastbal + ' .$order_discount, FALSE);
            $this->db->where('acn', $order_discount_account_details);
            $this->db->update('cberp_accounts'); 
        }



        echo json_encode(array('status' => 'Success'));

        die();
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

    // erp2024 04-11-2024 starts 
    public function create()
    {
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);  
        $tid = ($this->input->get('id'));
        $data['id'] = $tid;
        $data['currency'] = $this->quote->currencies();
        $head['title'] = "Credit Note";
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['permissions'] = load_permissions('Accounts','Invoices','Invoice Credit Notes','View Page');
        $data['configurations'] = $this->configurations;
        // $this->load->model('deliveryreturn_model', 'deliveryreturn');
        // $data['deliverynote_status'] = $this->deliveryreturn->deliverynote_status($tid);
        // $data['invoiceid'] = ($data['deliverynote_status']=='Invoiced') ? $this->deliveryreturn->invoice_details_by_delnoteid($tid):"";

        $invoice_retutn_number = ($this->input->get('iid'));
        $data['action_type']="";
        $data['receipt_numbers']  = "";
        if($invoice_retutn_number)
        {
            $data['action_type']="Edit";
            $data['invoice_retutn_number'] =$invoice_retutn_number;
            $this->load->model('invoice_creditnotes_model', 'invocies_creditnote');
            $data['notemaster'] = $this->invocies_creditnote->invoice_return_details($invoice_retutn_number);             
            $data['receipt_numbers']  = $this->invocies_creditnote->payment_receipt_number($invoice_retutn_number);
            // print_r($data['receipt_numbers']); die();
            if($data['notemaster']['created_by'])
            {
                $data['created_employee'] = employee_details_by_id($data['notemaster']['created_by']);          
            }
            $data['products'] = $this->invocies_creditnote->invoice_return_products($invoice_retutn_number);
            // echo "<pre>"; print_r($data['products']); die();
            $data['bank_transaction_ref_number'] = $this->invocies_creditnote->bank_transaction_ref_number($invoice_retutn_number);
            $data['trackingdata'] = tracking_details('invoice_retutn_number',$invoice_retutn_number);
            $data['journals_records'] = $this->invocies_creditnote->get_journals_for_invoice_return($invoice_retutn_number);        
            $data['payment_records'] = $this->invocies_creditnote->invoice_payments_received($invoice_retutn_number);
        }
        else{
            
            $data['invoice_retutn_number'] = $this->stockreturn->lastinvoicereturn();
            $data['notemaster'] = $this->invocies->invoice_by_id($tid);       
            $data['products'] = $this->invocies->invoice_products_for_return($tid);
            $data['trackingdata'] = tracking_details('invoice_number',$tid);
    
        }   
        
        //  echo "<pre>"; print_r($data['products']); die();
        // $data['invoice_details'] = ($data['deliverynote_status']=="Invoiced") ? $this->deliveryreturn->invoice_details($tid) : "";
        $data['prefix'] = get_prefix_72();

        $page = "Invoicereturn";
        $data['detailed_log']= get_detailed_logs($invoice_retutn_number,$page);
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
    
    
    public function action()
    {
        //   ini_set('display_errors', 1);
        //   ini_set('display_startup_errors', 1);
        //   error_reporting(E_ALL);
          $transaction_number = get_latest_trans_number();
          $currency = $this->input->post('mcurrency');
          $customer_id = $this->input->post('customer_id');
          $data3=[];
          $person_type = $this->input->post('person_type');
          $action_type =  $this->input->post('action_type');
          $invoice_return_number = ($action_type) ? $this->input->post('invoice_return_number') : $this->stockreturn->lastinvoicereturn();
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
          $invoice_id = $this->input->post('invoice_number');
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
        //   $this->db->trans_start();
          //products
          $transok = true;
          //Invoice Data discount
          $bill_date = datefordatabase($invoicedate);
          $bill_due_date = datefordatabase($invocieduedate);
          $grandsubtotal = 0;
          if (!$currency) $currency = 0; 
          $stockReturndata = [
            'invoice_retutn_number'         => $invoice_return_number,
            'invoice_number'                => $invoice_number,
            'transaction_number'            => $transaction_number,
            'return_date'                   => date('Y-m-d H:i:s'),
            'customer_id'                   => $customer_id,
            'subtotal'                      => $subtotal,
            'shipping'                      => $shipping,
            'shipping_tax'                  => $shipping_tax,
            'shipping_tax_type'             => $ship_taxtype,
            'discount'                      => 0.00,
            'order_discount'                => $order_discount,
            'order_discount_percentage'     => $order_discount_percentage,
            'shipping_percentage'           => $shipping_percentage,
            'total'                         => $total,
            'notes'                         => $notes,
            'status'                        => 'pending',
            'discount_status'               => $discstatus,
            'format_discount'               => $discountFormat,
            'reference'                     => $refer,
            'payment_term'                  => $pterms,
            'loc'                           => $this->aauth->get_user()->loc,
            'i_class'                       => 0,
            'multi'                         =>  $currency,
            'return_status'                 => 'Pending',
            'store_id'                      => $store_id,
            'created_by'                    => $this->session->userdata('id'),
            'created_date'                  => date('Y-m-d H:i:s'),
        ];

          $invoice_transcations = "";
         
        //   if($iid>0){
        //       $this->db->update('cberp_stock_returns', $data,['id'=>$iid]);
        //       $invocieno = $iid;            
        //       $this->db->delete('cberp_stock_returns_items', array('tid' => $invocieno));
        //   }
        //   else{
              $this->db->insert('cberp_stock_returns', $stockReturndata);   
        //   }
          
          //insert data to tracking table
          if(!empty($invoice_number))
          {
              insertion_to_tracking_table('invoice_retutn_number', $invoice_return_number,'invoice_number',$invoice_number);
          }
          
            
          if ($invoice_return_number) {
              
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

              foreach ($code as $key => $value) {
                  if(intval($product_qty[$key]) > 0 && !empty($product_name1[$key]))
                  {
                      $total_discount += numberClean(@$ptotal_disc[$key]);
                      $total_tax += numberClean($ptotal_tax[$key]);
                      $grandsubtotal =  $grandsubtotal + rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc);
                      $data = array(
                          'invoice_retutn_number' => $invoice_return_number,
                          'product_code' => $code[$key],
                          'quantity' => numberClean($product_qty[$key]),
                          'price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
                          'product_cost' => $product_cost[$key],
                          'tax' => numberClean($product_tax[$key]),
                          'discount' => numberClean($product_discount[$key]),
                          'discount_type' => $discount_type[$key],
                          'subtotal' => rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc),
                          'total_tax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                          'total_discount' => rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc),
                          'account_number' => $account_number[$key],
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
                      $this->invocies->product_qty_update_to_invoice_items_table($invoice_number, $code[$key], numberClean($product_qty[$key]), numberClean($damaged_qty[$key]),numberClean($product_subtotal[$key]));
                      //product quantity update
                      $prdQuantity = numberClean($product_qty[$key]);
                      $this->db->set('onhand_quantity', "onhand_quantity+$prdQuantity", FALSE);
                      $this->db->where('product_code', $code[$key]);
                      $this->db->update('cberp_products');
                      //erp2024 check transfer warehoues 13-06-2024
                        $this->db->select('store_id,stock_quantity');
                        $this->db->from('cberp_product_to_store');
                        $this->db->where('product_code', $code[$key]);
                        $this->db->where('store_id', $store_id);
                        $checkquery = $this->db->get();
                        $check_result = $checkquery->row_array();                    
                        $chekedID = (!empty($check_result))?$check_result['store_id']:"0";
                        $transferqty = numberClean($product_qty[$key]);
                        if($chekedID>0){
                            $existingQty = $check_result['stock_quantity'];
                            $current_stock = ($existingQty>0)? $existingQty+$transferqty :$transferqty;
                            $data3['stock_quantity'] = $current_stock;
                            $data3['updated_by'] = $this->session->userdata('id');
                            $data3['updated_date'] = date('Y-m-d H:i:s');
                            $this->db->where('store_id', $chekedID);
                             $this->db->where('product_code', $code[$key]);
                            $this->db->update('cberp_product_to_store', $data3);
                        }
                        // insert_data_to_average_cost_table($product_id[$key], $product_cost[$key],numberClean($product_qty[$key]), get_costing_transation_type("Invoice Return"));
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
                
                  $this->db->update('cberp_invoices',['creditnote_status'=>'Approved','invoice_retutn_number'=>$invoice_return_number],['invoice_number'=>$invoice_number]);
                                          


                  history_table_log('cberp_invoice_return_log','invoice_return_id',$invoice_return_number,'Create');
                    // erp2025 09-01-2025 starts

                    detailed_log_history('Invoice',$invoice_number,'Product Returned', $_POST['changedFields']);	
                    detailed_log_history('Invoicereturn',$invoice_return_number,'Created', $_POST['changedFields']);
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
                  $this->db->update('cberp_stock_returns',$stock_return_data,['invoice_return_number'=> $invoice_return_number]);
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
  
  
            //   $targeturl = base_url("stockreturn/view?id=$invoice_return_number");
            //   $createurl = base_url("stockreturn/$new_u");
              // echo json_encode(array(
              //     'status' => 'Success',
              //     'message' => $this->lang->line('ADDED') . " <a href='".$targeturl."' class='btn btn-secondary btn-sm'><span class='fa fa-eye' aria-hidden='true'></span> View</a> <a href='" . $createurl . "' class='btn btn-secondary btn-sm'><span class='fa fa-plus-circle' aria-hidden='true'></span> Create</a>"
              // ));
              echo json_encode(['status' => 'Success', 'message' => 'Successfully completed','data'=>$payment_type,'returnid'=>$invoice_return_number]);
              
          } else {
              echo json_encode(array('status' => 'Error', 'message' => $this->lang->line('ERROR')));
              $transok = false;
          }
  
  
        //   if ($transok) {
        //       $this->db->trans_complete();
        //   } else {
        //       $this->db->trans_rollback();
        //   }
    }

    
    public function invoice_creditnote_return_edit_action()
    {
          $transaction_number = $this->input->post('transaction_number');
          $currency = $this->input->post('mcurrency');
          $customer_id = $this->input->post('customer_id');
          $data3=[];
          $stock_return_data=[];
          $order_discount=0;
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
          $invoice_retutn_number = $this->input->post('invoice_return_number');
          $invoice_number = $this->input->post('invoice_number');
          $invocieno = $this->input->post('invocieno');
          $invoice_returnid = $this->input->post('invoice_returnid');
          $invocietid = $invocieno;
          $invoicedate = $this->input->post('invoicedate');
          $invocieduedate = $this->input->post('invoiceduedate');
          $invoice_id = $this->input->post('invoice_id');
          $notes = $this->input->post('notes');
          $total_tax = 0;
          $total_discount = 0;
          $total_old_discount=0;
          $grandsubtotal_old =0;
          $discountFormat = $this->input->post('discountFormat');
          $pterms = $this->input->post('pterms');
          $payment_type = $this->input->post('payment_type');
          $store_id = $this->input->post('store_id');
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
          $total_old = rev_amountExchange_s($this->input->post('total_old'), $currency, $this->aauth->get_user()->loc);
          if ($customer_id == 0) {
              echo json_encode(array('status' => 'Error', 'message' =>
                  "Please add a new person or search from a previous added!"));
              exit;
          }
          $this->db->trans_start();
          //products
          $transok = true;
          //Invoice Data
          $bill_date = datefordatabase($invoicedate);
          $bill_due_date = datefordatabase($invocieduedate);
          $grandsubtotal = 0;
          if (!$currency) $currency = 0; 
          $stockReturndata = [
            'customer_id'                   => $customer_id,
            'subtotal'                      => $subtotal,
            'shipping'                      => $shipping,
            'shipping_tax'                  => $shipping_tax,
            'shipping_tax_type'             => $ship_taxtype,
            'discount'                      => 0.00,
            'order_discount'                => $order_discount,
            'order_discount_percentage'     => $order_discount_percentage,
            'shipping_percentage'           => $shipping_percentage,
            'total'                         => $total,
            'notes'                         => $notes,
            'status'                        => 'pending',
            'discount_status'               => $discstatus,
            'format_discount'               => $discountFormat,
            'reference'                     => $refer,
            'payment_term'                  => $pterms,
            'loc'                           => $this->aauth->get_user()->loc,
            'i_class'                       => 0,
            'multi'                         =>  $currency,
            'return_status'                 => 'Pending',
            'store_id'                      => $store_id,
            'updated_by'                    => $this->session->userdata('id'),
            'updated_date'                  => date('Y-m-d H:i:s'),
        ];

         
          $invoice_transcations = "";
          $this->db->update('cberp_stock_returns', $data,['invoice_return_number'=>$invoice_return_number]);
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
        // erp2024 19-12-2024 load default accounts
        $default_cost_of_goods_account = default_chart_of_account('cost_of_goods_solid');
        $default_inventory_account = default_chart_of_account('inventory');
          if ($invoice_returnid) {
              
              $pid = $this->input->post('product_id');
              $productlist = array();
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
              $account_number = $this->input->post('account_number');              
              $damaged_qty = $this->input->post('damaged_qty');     
             
              $damaged_qty_old = $this->input->post('damaged_qty_old');     
              $product_qty_old = $this->input->post('return_qty_old');
              $product_subtotal_old = $this->input->post('product_subtotal_old');
              $old_discount = $this->input->post('old_discount');
              $discount_type = $this->input->post('discount_type');
              $product_cost = $this->input->post('product_cost');
              $order_discount_old = $this->input->post('order_discount_old');
              $order_discount = $this->input->post('order_discount');
              $shipping_amount = $this->input->post('shipping');
              $shipping_amount_old = $this->input->post('shipping_old');

              $this->db->delete('cberp_stock_returns_items', ['invoice_return_number'=>$invoice_retutn_number]);
              $this->db->delete('cberp_transactions', ['transaction_number'=>$transaction_number]);
              foreach ($product_qty as $key => $value) {
                  if(intval($product_qty[$key]) > 0 && !empty($product_name1[$key]))
                  {
                      $total_discount += numberClean(@$ptotal_disc[$key]);
                      $total_old_discount += numberClean(@$old_discount[$key]);
                      $total_tax += numberClean($ptotal_tax[$key]);
                      $grandsubtotal =  $grandsubtotal + rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc);
                      $grandsubtotal_old =  $grandsubtotal_old + rev_amountExchange_s($product_subtotal_old[$key], $currency, $this->aauth->get_user()->loc);
                     $data = array(
                          'invoice_retutn_number' => $invoice_retutn_number,
                          'product_code' => $code[$key],
                          'quantity' => numberClean($product_qty[$key]),
                          'price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
                          'product_cost' => $product_cost[$key],
                          'tax' => numberClean($product_tax[$key]),
                          'discount' => numberClean($product_discount[$key]),
                          'discount_type' => $discount_type[$key],
                          'subtotal' => rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc),
                          'total_tax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                          'total_discount' => rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc),
                          'account_number' => $account_number[$key],
                      );

                      $productlist[$prodindex] = $data;
                      $i++;
                      $prodindex++;
                       
                      $this->invocies_creditnote->product_qty_update_to_invoice_items_table_edit($invoice_number, $code[$key], numberClean($product_qty[$key]), numberClean($damaged_qty[$key]),numberClean($product_subtotal[$key]),numberClean($product_qty_old[$key]), numberClean($damaged_qty_old[$key]),numberClean($product_subtotal_old[$key]));

                      //product quantity update
                      $prdQuantity = numberClean($product_qty[$key])-numberClean($product_qty_old[$key]); 
                      $this->db->set('onhand_quantity', "onhand_quantity+$prdQuantity", FALSE);
                      $this->db->where('product_code', $code[$key]);
                      $this->db->update('cberp_products');
                      //erp2024 check transfer warehoues 13-06-2024
                        $this->db->select('store_id,stock_quantity');
                        $this->db->from('cberp_product_to_store');
                        $this->db->where('product_code', $code[$key]);
                        $this->db->where('store_id', $store_id);
                        $checkquery = $this->db->get();
                        $check_result = $checkquery->row_array();                    
                        $chekedID = (!empty($check_result))?$check_result['store_id']:"0";
                        $transferqty = numberClean($product_qty[$key]);
                        if($chekedID>0){
                            $existingQty = $check_result['stock_quantity'];
                            $current_stock = ($existingQty>0)? $existingQty+$transferqty :$transferqty;
                            $data3['stock_quantity'] = $current_stock;
                            $data3['updated_by'] = $this->session->userdata('id');
                            $data3['updated_date'] = date('Y-m-d H:i:s');
                            $this->db->where('store_id', $chekedID);
                             $this->db->where('product_code', $code[$key]);
                            $this->db->update('cberp_product_to_store', $data3);
                        }

                       


                      
                      // // cost of goods transaction
                      //   $total_product_cost_old = $product_cost[$key]*numberClean($product_qty_old[$key]);
                      $total_product_cost = $product_cost[$key]*numberClean($product_qty[$key]);
                     //   $total_product_cost = $total_product_cost_new-$total_product_cost_old;
                      $cost_of_goods_data =  [
                          'acid' => $default_cost_of_goods_account,
                          'type' => 'Expense',
                          'cat' => 'Invoice Return',
                          'credit' => abs($total_product_cost),
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
                          'debit' => abs($total_product_cost),
                          'eid' => $this->session->userdata('id'),
                          'date' => date('Y-m-d'),
                          'transaction_number'=>$transaction_number,
                      ];
                      $this->db->set('lastbal', 'lastbal + ' . $total_product_cost, FALSE);
                      $this->db->where('acn', $default_inventory_account);
                      $this->db->update('cberp_accounts'); 
                      $this->db->insert('cberp_transactions', $inventory_data);
                      //erp2024 totaldiscount transaction 11-11-2024 ends


                      

                    //   if($payment_type=='Customer Credit')
                    //   {
                                            
                        // insert_return_transaction('debit', 'Invoice Return', rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc), $account_number[$key], $transaction_number);

                        // $creditamount = rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc) - rev_amountExchange_s($product_subtotal_old[$key], $currency, $this->aauth->get_user()->loc);

                        // update_account_balance($account_number[$key], $creditamount, 'add');
             
                    //   }
                  }
                  $amt = numberClean($product_qty[$key]);
              }
              if ($prodindex > 0) {
                  $this->db->insert_batch('cberp_stock_returns_items', $productlist);
                  $stock_return_data = ['discount' => rev_amountExchange_s(amountFormat_general($total_discount), $currency, $this->aauth->get_user()->loc), 'tax' => rev_amountExchange_s(amountFormat_general($total_tax), $currency, $this->aauth->get_user()->loc)];
                  
                  $this->db->update('cberp_invoices',['creditnote_status'=>'Approved','invoice_retutn_number'=>$invoice_retutn_number],['invoice_number'=>$invoice_number]);
                  $total1 =  $total - $total_old;
                //   history_table_log('cberp_invoice_return_log','invoice_retutn_number',$invoice_retutn_number,'Update');
                  //erp2024 06-01-2025 detailed history log starts
                  detailed_log_history('Invoicereturn',$invoice_retutn_number,'Updated', $_POST['changedFields']);
                  detailed_log_history('Invoice',$invoice_number,'Updated', $_POST['changedFields']);
                  //erp2024 06-01-2025 detailed history log ends 
                  //FOR PAID INVOICE
                  $default_bank_account = default_bank_account();
                  $invoice_receivable_account_details =  $default_bank_account['code'];
                  if($payment_type=='Customer Credit')
                  {          
                    $creditlimits = get_customer_credit_limit($customer_id);
                    update_customer_credit($customer_id, $total1,$creditlimits['avalable_credit_limit']);
                    $stock_return_data['payment_status'] = 'Paid';
                    $stock_return_data['status'] = 'accepted';
                    $stock_return_data['return_status'] = 'Sent';
                    $invoice_receivable_account_details =  default_chart_of_account('accounts_receivable');
                  }
                  $this->db->update('cberp_stock_returns',$stock_return_data,['invoice_retutn_number' => $invoice_retutn_number]);

                  $receivable_data = [
                    'acid' => $invoice_receivable_account_details,
                    // 'account' => $invoice_receivable_account_details['holder'],
                    'type' => 'Asset',
                    'cat' => 'Invoice Return',
                    'credit' => abs($total1),
                    'eid' => $this->session->userdata('id'),
                    'date' => date('Y-m-d'),
                    'transaction_number'=>$transaction_number
                 ];
                 $this->db->insert('cberp_transactions',$receivable_data);
                 $this->db->set('lastbal', 'lastbal - ' .$total1, FALSE);
                 $this->db->where('acn', $invoice_receivable_account_details);
                 $this->db->update('cberp_accounts'); 


                    // $default_receivableaccount = default_receivable_account();
                    $grandsubtotal = $grandsubtotal - $grandsubtotal_old;
                    
                    $invoice_return_account = default_chart_of_account('sales_returns');
                    $invoice_return_data = [
                        'acid' => $invoice_return_account,
                        // 'account' => $invoice_receivable_account_details['holder'],
                        'type' => 'Asset',
                        'cat' => 'Invoice Return',
                        'debit' => abs($total1),
                        'eid' => $this->session->userdata('id'),
                        'date' => date('Y-m-d'),
                        'transaction_number'=>$transaction_number
                    ];
                    $this->db->insert('cberp_transactions',$invoice_return_data);
                    $this->db->set('lastbal', 'lastbal + ' .$total1, FALSE);
                    $this->db->where('acn', $invoice_return_account);
                    $this->db->update('cberp_accounts');                    
                 
                    //erp2024 totaldiscount transaction 11-11-2024 starts
                    if($total_discount>0 || $total_old_discount>0) 
                    {
                        $total_discount = $total_old_discount-$total_discount;
                        $discount_account_details = default_chart_of_account('sales_discount');
                        $discount_data = [
                            'acid' => $discount_account_details,
                            'type' => 'Asset',
                            'cat' => 'Invoice Return',
                            'debit' => abs($total_discount),
                            'eid' => $this->session->userdata('id'),
                            'date' => date('Y-m-d'),
                            'transaction_number'=>$transaction_number
                        ];
                        $this->db->insert('cberp_transactions',$discount_data);
                        $this->db->set('lastbal', 'lastbal - ' .$total_discount, FALSE);
                        $this->db->where('acn', $discount_account_details);
                        $this->db->update('cberp_accounts'); 
                    }
                    // if($order_discount)
                    // {
                    //     $order_discount_account_details = default_chart_of_account('order_discount');
                    //     $discount_data1 = [
                    //         'acid' => $order_discount_account_details,
                    //         // 'account' => $order_discount_account_details['holder'],
                    //         'type' => 'Asset',
                    //         'cat' => 'Invoice',
                    //         'debit' => abs($order_discount),
                    //         'eid' => $this->session->userdata('id'),
                    //         'date' => date('Y-m-d'),
                    //         'transaction_number'=>$transaction_number
                    //     ];
                    //     $this->db->insert('cberp_transactions',$discount_data1);
                    //     $this->db->set('lastbal', 'lastbal - ' .$order_discount, FALSE);
                    //     $this->db->where('acn', $order_discount_account_details);
                    //     $this->db->update('cberp_accounts'); 
                    // }

                    if($order_discount>0 || $order_discount_old>0)
                    {
                        $order_discount_substarcted = $order_discount-$order_discount_old;
                        $order_discount_account_details = default_chart_of_account('order_discount');
                        $discount_data1 = [
                            'acid' => $order_discount_account_details,
                            'type' => 'Asset',
                            'cat' => 'Invoice Return',
                            'credit' => abs($order_discount_substarcted),
                            'eid' => $this->session->userdata('id'),
                            'date' => date('Y-m-d'),
                            'transaction_number'=>$transaction_number
                        ];
                        $this->db->insert('cberp_transactions',$discount_data1);
                        $this->db->set('lastbal', 'lastbal - ' .$order_discount_substarcted, FALSE);
                        $this->db->where('acn', $order_discount_account_details);
                        $this->db->update('cberp_accounts'); 
                    }
                    if($shipping_amount>0 || $shipping_amount_old>0)
                    {
                        $shipping_amount_substracted = $shipping_amount-$shipping_amount_old;
                        $shipping_account_details = default_chart_of_account('shipping');
                        $shipping_data1 = [
                            'acid' => $shipping_account_details,
                            'type' => 'Asset',
                            'cat' => 'Invoice Return',
                            'debit' => abs($shipping_amount_substracted),
                            'eid' => $this->session->userdata('id'),
                            'date' => date('Y-m-d'),
                            'transaction_number'=>$transaction_number
                        ];
                        $this->db->insert('cberp_transactions',$shipping_data1);
                        $this->db->set('lastbal', 'lastbal + ' .$shipping_amount_substracted, FALSE);
                        $this->db->where('acn', $shipping_account_details);
                        $this->db->update('cberp_accounts'); 
                    }
                
                //erp2024 totaldiscount transaction 11-11-2024 ends
                  
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
              echo json_encode(['status' => 'Success', 'message' => 'Successfully completed','data'=>$payment_type,'returnid'=>$invoice_retutn_number]);
              
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
}

