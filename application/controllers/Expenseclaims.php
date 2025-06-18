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

class Expenseclaims extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('expenseclaim_model', 'expenseclaim');
        $this->load->model('transactions_model', 'transactions');
        $this->load->library("Aauth");
        if (!$this->aauth->is_loggedin()) {
            redirect('/user/', 'refresh');
        }
        if ($this->aauth->get_user()->roleid < 4) {

            exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');

        }

    }

    public function index()
    {
        $data['permissions'] = load_permissions('HRM','Employees','Expense Claims');
        $head['title'] = "Expense Claim";
        $head['usernm'] = $this->aauth->get_user()->username;        
        $data['accountheaders'] = $this->expenseclaim->load_banking_headers();
        $data['details'] = $this->expenseclaim->get_datatables();
        $this->load->view('fixed/header', $head);
        $this->load->view('expenseclaims/list', $data);
        $this->load->view('fixed/footer');
    }

    public function create()
    {
        $data['permissions'] = load_permissions('HRM','Employees','Expense Claims','','Add New');
        $tid = $this->input->get('id');
        $this->load->library("Common");
        $data['taxlist'] = $this->common->taxlist($this->config->item('tax'));
        $this->load->model('plugins_model', 'plugins');
        $data['exchange'] = $this->plugins->universal_api(5);
        $head['title'] = "Expense Claim ";
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['taxdetails'] = $this->common->taxdetail();
        $data['employee'] = employee_list();
        $data['prefix'] = get_prefix();      
        $data['claim_number'] = get_latest_expense_claim_number();      
        $data['category'] = income_or__expense_category_by_type('Expense');      
        $data['selectedValues'] = $this->session->userdata('selectedValues');
        $this->load->view('fixed/header', $head);
        $this->load->view('expenseclaims/create', $data);
        $this->load->view('fixed/footer');
    }

    public function edit()
    {
        $data['permissions'] = load_permissions('HRM','Employees','Expense Claims');
        $claim_number = $this->input->get('id');
        $this->load->library("Common");
        $data['taxlist'] = $this->common->taxlist($this->config->item('tax'));
        $this->load->model('plugins_model', 'plugins');
        $data['exchange'] = $this->plugins->universal_api(5);
        $head['title'] = "Expense Claim ";
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['taxdetails'] = $this->common->taxdetail();
        $data['employee'] = employee_list();
        $data['prefix'] = get_prefix();           
        $data['category'] = income_or__expense_category_by_type('Expense');   
        $data['mastredata'] = $this->expenseclaim->expense_clam_details_by_number($claim_number); 
        $data['products'] = $this->expenseclaim->get_claim_items_details_by_number($claim_number); 
        // echo "<pre>"; print_r($data['mastredata']); die();
        $data['selectedValues'] = $this->session->userdata('selectedValues');
        $this->load->view('fixed/header', $head);
        $this->load->view('expenseclaims/edit', $data);
        $this->load->view('fixed/footer');
    }

    public function load_claim_appover()
    {
        
       
        $options = array(
            "5" => 'Business Owner',
            "4" => 'Business Manager',
            "3" => 'Sales Manager',
            "2" => 'Sales Person',
            "6" => 'Sales Man',
            "1" => 'Inventory Manager',
            "-1" => 'Project Manager'
        );
       
        $expense_claim_approver = $this->input->post('employee_claim_approver', true); 
        $employee_id = $this->input->post('employee_id', true); 
        
        $expense_approver = $this->expenseclaim->load_approvers();
     
        $option='<option value="">Select Approver</option>';
        if ($expense_approver) {
            foreach ($expense_approver as $row) {
                if (isset($row['roleid']) && isset($options[$row['roleid']])) {
                    $sel="";
                    if ($row['id'] == $expense_claim_approver) {
                      $sel ="selected";
                      $option .= "<option value='" . $row['id'] . "' $sel>" . $row['name'] . " (" . $options[$row['roleid']] . ")</option>";                 
                    }
                    if($employee_id==$row['id'])
                    {
                        continue;
                    }
                    // $option .= "<option value='" . $row['id'] . "' $sel>" . $row['name'] . " (" . $options[$row['roleid']] . ")</option>";                 
                }
            }
        }
        echo $option;
    }

      //action
      public function action()
      {
          
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
          $transaction_number = get_latest_trans_number();
          $supplier_id = $this->input->post('customer_id');
          $claim_number = $this->input->post('claim_number');
          $claim_due_date = datefordatabase($this->input->post('claim_due_date'));
          $employee_id = $this->input->post('employee_id');
          $approver_id = $this->input->post('approver_id');
          $claim_category_id = $this->input->post('claim_category_id');
          $note = $this->input->post('note');
          $discount_type = $this->input->post('discount_type');
          $claim_discount = $this->input->post('claim_discount');
          $total = rev_amountExchange_s($this->input->post('total'), $currency, $this->aauth->get_user()->loc);
          $claim_discount_amount = $this->input->post('claim_discount_amount');
          $masterdata = array(
            'claim_number'          => $claim_number,
            'supplier_id'           => $supplier_id,
            'approver_id'           => $approver_id,
            'employee_id'           => $employee_id,
            'claim_date'            => date('Y-m-d'),
            'claim_due_date'        => $claim_due_date,
            'claim_dt'              => date('Y-m-d H:i:s'),
            'created_by'            => $this->session->userdata('id'),
            'created_dt'            => date('Y-m-d H:i:s'),
            'note'                  => $note,
            'discount_type'         => $discount_type,
            'claim_discount'        => $claim_discount,
            'claim_total'           => $total,
            'claim_discount_amount' => $claim_discount_amount,
            'transaction_number'    => $transaction_number
          );

          $this->db->insert('cberp_expense_claims',$masterdata);
          $claimid = $this->db->insert_id();
          //for detils table
          
          $product_code = $this->input->post('hsn');
          $product_qty = $this->input->post('product_qty');
          $product_price = $this->input->post('product_price');
          $product_subtotal = $this->input->post('product_subtotal');
          $claim_account_code = $this->input->post('expense_account_number');
          $prodindex = 0;
          $grand_total = 0;
          $productlist = array();
          $wholeexpenseclaim_data = array();
          $i =0;
          if($product_subtotal)
          {
            foreach ($product_code as $key => $value) {
                $price = rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc);
                $product_total = rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc);
                $grand_total += $product_total;
                $itemdata = array(
                    'claim_number' => $claim_number,
                    'product_code' => $product_code[$key],
                    'quantity' => numberClean($product_qty[$key]),
                    'price' => $price,
                    'total' =>  $product_total,
                    'claim_account_code' => $claim_account_code[$key]
                );           
                $expenseclaim_data =  [
                    'acid' => $claim_account_code[$key],
                    'type' => 'Expense',
                    'cat' => 'Expense Claim',
                    'debit' => $product_total,
                    'eid' => $this->session->userdata('id'),
                    'date' => date('Y-m-d'),
                    'transaction_number'=>$transaction_number
                ];
                //preparing data for product account transaction ends
                $this->db->set('lastbal', 'lastbal + ' . $product_total, FALSE);
                $this->db->where('acn', $claim_account_code[$key]);
                $this->db->update('cberp_accounts'); 
    
                $wholeexpenseclaim_data[$prodindex] = $expenseclaim_data;     
                $productlist[$prodindex] = $itemdata;
                $i++;
                $prodindex++;
            }
          }
          if ($prodindex > 0) {
              $this->db->insert_batch('cberp_expense_claim_items', $productlist);              
              $this->db->insert_batch('cberp_transactions', $wholeexpenseclaim_data);
              $this->db->set(array('claim_subtotal' => $grand_total));
              $this->db->where('id', $claimid);
              $this->db->update('cberp_expense_claims');
              //transaction section    ////////////////////////////////////////
                $payable_account_details = get_account_details_for_invoicing("Current Liability","Accounts Payable");            
                $accounts_payable_data = [
                    'acid' => $payable_account_details['acn'],
                    'type' => 'Liability',
                    'cat' => 'Expense Claim',
                    'credit' => $total,
                    'eid' => $this->session->userdata('id'),
                    'date' => date('Y-m-d'),
                    'transaction_number'=>$transaction_number
                ];
                $this->db->set('lastbal', 'lastbal - ' . $total, FALSE);
                $this->db->where('id', $payable_account_details['id']);
                $this->db->update('cberp_accounts'); 
                $this->db->insert('cberp_transactions',$accounts_payable_data);

            if($claim_discount)
            {
                $purchase_discount_account = get_account_details_for_invoicing("Revenue","Purchase Discount");
                $discount_payable_data = [
                    'acid' => $purchase_discount_account['acn'],
                    'type' => 'Income',
                    'cat' => 'Expense Claim',
                    'credit' => $claim_discount_amount,
                    'eid' => $this->session->userdata('id'),
                    'date' => date('Y-m-d'),
                    'transaction_number'=>$transaction_number
                ];
                $this->db->set('lastbal', 'lastbal - ' . $claim_discount_amount, FALSE);
                $this->db->where('id', $purchase_discount_account['id']);
                $this->db->update('cberp_accounts'); 
                $this->db->insert('cberp_transactions',$discount_payable_data);
            }
            //transaction section  ends  ////////////////////////////////////////

          } else {
              echo json_encode(array('status' => 'Error', 'message' =>
                  "Please choose product from product list. Go to Item manager section if you have not added the products."));
              $transok = false;
          }
          echo json_encode(array('status' => 'Success', 'data' => $claim_number));
        
  
      }
      public function editaction()
      {
          
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL); 
          $transaction_number =  $this->input->post('transaction_number');
        //   $acid1 = $this->expenseclaim->get_transaction_details_by_number($transaction_number,'Liability');
        //     print_r($acid1);
        //   die();
          $supplier_id = $this->input->post('customer_id');
          $claim_number = $this->input->post('claim_number');
          $claim_due_date = datefordatabase($this->input->post('claim_due_date'));
          $employee_id = $this->input->post('employee_id');
          $approver_id = $this->input->post('approver_id');
          $claim_category_id = $this->input->post('claim_category_id');
          $note = $this->input->post('note');
          $discount_type = $this->input->post('discount_type');
          $claim_discount = $this->input->post('claim_discount');
          $total = rev_amountExchange_s($this->input->post('total'), $currency, $this->aauth->get_user()->loc);
          $total_old = rev_amountExchange_s($this->input->post('total_old'), $currency, $this->aauth->get_user()->loc);
          $claim_discount_amount = $this->input->post('claim_discount_amount');
          $claim_discount_amount_old = $this->input->post('claim_discount_amount_old');
          $masterdata = array(
            'claim_number'          => $claim_number,
            'supplier_id'           => $supplier_id,
            'approver_id'           => $approver_id,
            'employee_id'           => $employee_id,
            'claim_date'            => date('Y-m-d'),
            'claim_due_date'        => $claim_due_date,
            'claim_dt'              => date('Y-m-d H:i:s'),
            'created_by'            => $this->session->userdata('id'),
            'created_dt'            => date('Y-m-d H:i:s'),
            'note'                  => $note,
            'discount_type'         => $discount_type,
            'claim_discount'        => $claim_discount,
            'claim_total'           => $total,
            'claim_discount_amount' => $claim_discount_amount,
            'transaction_number'    => $transaction_number,
            'approval_status'       => 'Not Approved',
            'refused_by'            => NULL,
            'refused_reason'        => NULL,
            'refused_dt'            => NULL,

          );

          $claimid = $this->input->post('iid');
          $this->db->where('id', $claimid);
          $this->db->update('cberp_expense_claims',$masterdata);          
          
          //for detils table
          
          $product_code = $this->input->post('hsn');
          $product_qty = $this->input->post('product_qty');
          $product_price = $this->input->post('product_price');
          $product_subtotal = $this->input->post('product_subtotal');
          $product_subtotal_old = $this->input->post('product_subtotal_old');
          $claim_account_code = $this->input->post('expense_account_number');
          $prodindex = 0;
          $grand_total = 0;
          $productlist = array();
          $wholeexpenseclaim_data = array();
          $i =0;
          if($product_subtotal)
          {
            $this->db->delete('cberp_expense_claim_items',['claim_number'=>$claim_number]);
            foreach ($product_code as $key => $value) {
                $price = rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc);
                $product_total = rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc);
                $product_total_old = rev_amountExchange_s($product_subtotal_old[$key], $currency, $this->aauth->get_user()->loc);
                $grand_total += $product_total;
                $itemdata = array(
                    'claim_number' => $claim_number,
                    'product_code' => $product_code[$key],
                    'quantity' => numberClean($product_qty[$key]),
                    'price' => $price,
                    'total' =>  $product_total,
                    'claim_account_code' => $claim_account_code[$key]
                );           
                $expenseclaim_data =  [
                    'acid' => $claim_account_code[$key],
                    'type' => 'Expense',
                    'cat' => 'Expense Claim',
                    'debit' => $product_total,
                    'eid' => $this->session->userdata('id'),
                    'date' => date('Y-m-d'),
                    'transaction_number'=>$transaction_number
                ];
                //preparing data for product account transaction ends
                $this->db->set('lastbal', 'lastbal - ' . $product_total_old, FALSE);
                $this->db->where('acn', $claim_account_code[$key]);
                $this->db->update('cberp_accounts'); 

                $this->db->set('lastbal', 'lastbal + ' . $product_total, FALSE);
                $this->db->where('acn', $claim_account_code[$key]);
                $this->db->update('cberp_accounts'); 
    
                $wholeexpenseclaim_data[$prodindex] = $expenseclaim_data;     
                $productlist[$prodindex] = $itemdata;
                $i++;
                $prodindex++;
            }
          }
          if ($prodindex > 0) {
            
            $acid1 = $this->expenseclaim->get_transaction_details_by_number($transaction_number,'Liability');
                
            $this->db->set('lastbal', 'lastbal + ' . $total_old, FALSE);
            $this->db->where('acn', $acid1);
            $this->db->update('cberp_accounts'); 
            

            $bankaccountid = $this->expenseclaim->get_transaction_details_by_number($transaction_number,'Income');
            $this->db->set('lastbal', 'lastbal + ' . $claim_discount_amount_old, FALSE);
            $this->db->where('acn', $bankaccountid);
            $this->db->update('cberp_accounts'); 


              $this->db->delete('cberp_transactions',['transaction_number' => $transaction_number]);
              $this->db->insert_batch('cberp_expense_claim_items', $productlist);              
              $this->db->insert_batch('cberp_transactions', $wholeexpenseclaim_data);
              $this->db->set(array('claim_subtotal' => $grand_total));
              $this->db->where('id', $claimid);
              $this->db->update('cberp_expense_claims');
              //transaction section    ////////////////////////////////////////
              
              
                $payable_account_details = get_account_details_for_invoicing("Current Liability","Accounts Payable");            
                $accounts_payable_data = [
                    'acid' => $payable_account_details['acn'],
                    'type' => 'Liability',
                    'cat' => 'Expense Claim',
                    'credit' => $total,
                    'eid' => $this->session->userdata('id'),
                    'date' => date('Y-m-d'),
                    'transaction_number'=>$transaction_number
                ];
                $this->db->insert('cberp_transactions',$accounts_payable_data);
                $this->db->set('lastbal', 'lastbal - ' . $total, FALSE);
                $this->db->where('id', $payable_account_details['id']);
                $this->db->update('cberp_accounts'); 

                

            if($claim_discount)
            {
                $purchase_discount_account = get_account_details_for_invoicing("Revenue","Purchase Discount");
                $discount_payable_data = [
                    'acid' => $purchase_discount_account['acn'],
                    'type' => 'Income',
                    'cat' => 'Expense Claim',
                    'credit' => $claim_discount_amount,
                    'eid' => $this->session->userdata('id'),
                    'date' => date('Y-m-d'),
                    'transaction_number'=>$transaction_number
                ];
                $this->db->set('lastbal', 'lastbal - ' . $claim_discount_amount, FALSE);
                $this->db->where('id', $purchase_discount_account['id']);
                $this->db->update('cberp_accounts'); 
                $this->db->insert('cberp_transactions',$discount_payable_data);
            }
            //transaction section  ends  ////////////////////////////////////////

          } else {
              echo json_encode(array('status' => 'Error', 'message' =>
                  "Please choose product from product list. Go to Item manager section if you have not added the products."));
              $transok = false;
          }
          echo json_encode(array('status' => 'Success', 'data' => $claim_number));
        
  
      }

      public function view()
      {
   
        $data['permissions'] = load_permissions('HRM','Employees','Expense Claims');
        $claim_number = $this->input->get('id'); 
        $head['title'] = "Expense Claim #".$claim_number;
        $data['expense_details'] = $this->expenseclaim->expense_clam_details_by_number($claim_number);
        $data['expense_items'] = $this->expenseclaim->load_expnseclaim_items_by_number($claim_number);
        $data['payment_transactions'] = $this->expenseclaim->load_expnseclaim_payment_transactions($claim_number);
        $data['journels'] = $this->expenseclaim->load_expnseclaim_journel_by_number($data['expense_details']['transaction_number']);
        $this->load->view('fixed/header', $head);
        $this->load->view('expenseclaims/expense_clam_view', $data);
        $this->load->view('fixed/footer');
      }
      public function request_for_approval()
      {
        $claim_number = $this->input->post('claim_number'); 
        $this->db->update('cberp_expense_claims',['approval_status'=>'Waiting For Approval','requested_by'=>$this->session->userdata('id'),'requested_date'=>date('Y-m-d H:i:s')],['claim_number'=>$claim_number]);
        echo json_encode([
            'status' => 'success',
            'message' => 'Approval request sent successfully.'
        ]);
      }
      public function approving_the_request()
      {
        $claim_number = $this->input->post('claim_number'); 
        $this->db->update('cberp_expense_claims',['approval_status'=>'Approved','approverd_by'=>$this->session->userdata('id'),'approved_dt'=>date('Y-m-d H:i:s')],['claim_number'=>$claim_number]);
        echo json_encode([
            'status' => 'success',
            'message' => 'Approval request sent successfully.'
        ]);
      }
      public function refusing_the_request()
      {
        $claim_number = $this->input->post('claim_number'); 
        $refuse_reason = $this->input->post('refuse_reason'); 
        $this->db->update('cberp_expense_claims',['refused_by'=>$this->session->userdata('id'),'refused_dt'=>date('Y-m-d H:i:s'),'refused_reason'=>$refuse_reason],['claim_number'=>$claim_number]);
        echo json_encode([
            'status' => 'success',
            'message' => 'Approval request sent successfully.'
        ]);
      }

    public function expense_claim_payment()
    {
        $this->load->model('accounts_model');
        $data['acclist'] = $this->accounts_model->accountslist((integer)$this->aauth->get_user()->loc);

        $claim_number = $this->input->get('id');
        $supplier_id = $this->input->get('csd');
        $data['expense_details'] = $this->expenseclaim->expense_clam_details_by_number($claim_number);
       

        $data['accountheaders'] = $this->accounts_model->load_coa_account_headers();
        $data['accounttypes'] = $this->accounts_model->load_coa_account_types();
        $data['accountlist'] = $this->accounts_model->load_account_list();
        $data['payment_transactions'] = $this->expenseclaim->load_expnseclaim_payment_transactions($claim_number);       
        $accountchild=[];
        foreach($data['accountlist'] as $single){
            $accountchild[$single['coa_header_id']][] = $single;
        } 
        $data['accountlists'] = $accountchild;
        $data['bankaccounts'] = bank_account_list();
        $data['default_bankaccount'] = default_bank_account();
        $data['default_receivableaccount'] = default_payable_account();


        // echo "<pre>"; print_r($data['invoice']); die();
        // $data['attach'] = $this->invocies->attach($tid);
        $head['usernm'] = $this->aauth->get_user()->username;
        $head['title'] = "Expense Claim Payment";
        $this->load->view('fixed/header', $head);
            $this->load->view('expenseclaims/expense_claim_payment', $data);
        // }
        $this->load->view('fixed/footer');
    }

    public function expense_claim_payment_edit()
    {
        $this->load->model('accounts_model');
        $data['acclist'] = $this->accounts_model->accountslist((integer)$this->aauth->get_user()->loc);

        $trans_ref_number = $this->input->get('id');
        $supplier_id = $this->input->get('csd');
        $data['trans_numbers'] = $this->expenseclaim->load_trasnsation_numbers_by_reference_number($trans_ref_number);
        $claim_number = $data['trans_numbers']['trans_type_number'];
        $data['expense_details'] = $this->expenseclaim->expense_clam_details_by_number($claim_number);
        $data['payment_transactions'] = $this->expenseclaim->load_expnseclaim_payment_transactions($claim_number);
        $data['accountheaders'] = $this->accounts_model->load_coa_account_headers();
        $data['accounttypes'] = $this->accounts_model->load_coa_account_types();
        $data['accountlist'] = $this->accounts_model->load_account_list();       
        $accountchild=[];
        foreach($data['accountlist'] as $single){
            $accountchild[$single['coa_header_id']][] = $single;
        } 
        $data['accountlists'] = $accountchild;
        $data['bankaccounts'] = bank_account_list();
        $data['default_bankaccount'] =  $data['trans_numbers']['trans_account_id'];
        $data['default_receivableaccount'] = $data['trans_numbers']['trans_chart_of_account_id'];
        


        $data['transaction_ai'] = $this->transactions->get_transaction_ai_details($data['trans_numbers']['transaction_number']);
        // echo "<pre>"; print_r($data['payment_transactions']); die();
        // echo "<pre>"; print_r($data['invoice']); die();
        // $data['attach'] = $this->invocies->attach($tid);
        $head['usernm'] = $this->aauth->get_user()->username;
        $head['title'] = "Expense Claim Payment";
        $this->load->view('fixed/header', $head);
            $this->load->view('expenseclaims/expense_claim_payment_edit', $data);
        // }
        $this->load->view('fixed/footer');
    }

}
