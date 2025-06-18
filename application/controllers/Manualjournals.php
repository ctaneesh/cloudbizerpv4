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

class Manualjournals extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('manualjournal_model', 'manualjournal');
        $this->load->model('accounts_model', 'accounts');
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

        $data['permissions'] = load_permissions('Accounts','Manual Journals','Manage Manual Journals','List');
        $head['title'] = "Manual Journals";
        $head['usernm'] = $this->aauth->get_user()->username;        
        $data['accountheaders'] = $this->manualjournal->load_banking_headers();
        $data['details'] = $this->manualjournal->get_datatables();
        $this->load->view('fixed/header', $head);
        $this->load->view('manualjournals/list', $data);
        $this->load->view('fixed/footer');
    }

    public function addeditaction(){

         $transcat_id = $this->input->post('transcat_id', true);
         $transcat_name = $this->input->post('transcat_name', true);
         $transtype_id = $this->input->post('transtype_id', true);
         $catid = $this->input->post('category_id', true); 
         $status = $this->input->post('status', true); 
         $data = [
             'transcat_id' => $transcat_id,
             'transcat_name' => $transcat_name,
             'transtype_id' => $transtype_id,
             'status' => $status
         ];
         if($catid > 0)
         {
             $this->db->update('cberp_bank_transcategory', $data,['id'=>$catid]);
             echo json_encode(array('status' => 'Success', 'message' =>"Category Created Successfully"));
            //  if ($this->db->insert('cberp_bank_transcategory', $data)) {
            //     echo json_encode(array('status' => 'Success', 'message' =>"Category Updated Successfully"));
            // } else {
            //     echo json_encode(array('status' => 'Error', 'message' =>"Category Number already used"));
            // }
             
         }
         else{
             if ($this->db->insert('cberp_bank_transcategory', $data)) {
                 echo json_encode(array('status' => 'Success', 'message' =>"Category Created Successfully"));
             } else {
                 echo json_encode(array('status' => 'Error', 'message' =>"Category Number already used"));
             }
         }
        
        //  echo json_encode(array('status' => 'Success', 'message' =>$this->lang->line('Delivery Return') . "&nbsp;".$link."&nbsp;".$returns));
    }
    public function load_category_by_id(){
        $accountdetails = $this->manualjournal->load_category_by_id($this->input->post('category_id'));       
        echo json_encode(array('status' => 'Success', 'data' => $accountdetails));        
    }


    public function create()
    {
        $data['permissions'] = load_permissions('Accounts','Manual Journals','New Manual Journals');
        $tid = $this->input->get('id');
        $this->load->library("Common");
        $data['taxlist'] = $this->common->taxlist($this->config->item('tax'));
        $this->load->model('plugins_model', 'plugins');
        $data['exchange'] = $this->plugins->universal_api(5);
        $head['title'] = "Manual Journal";
        $head['usernm'] = $this->aauth->get_user()->username;   
        $head['journal_number'] = get_latest_journal_number();   
        $head['basislist'] = $this->manualjournal->load_basis_list();
        $this->load->view('fixed/header', $head);
        $this->load->view('manualjournals/create', $data);
        $this->load->view('fixed/footer');
    }

    public function coa_accounts()
    {
        // Load data from the database
        $accountheaders = $this->accounts->load_coa_account_headers();
        $data['accounttypes'] = $this->accounts->load_coa_account_types();
        $data['accountlist'] = $this->accounts->load_account_list();
       
        $account_id = $this->input->post('account_id');
        // Process account types to group by header ID
        $child = [];
        foreach ($data['accounttypes'] as $row) {
            $child[$row['coa_header_id']][] = $row;
        }
    
        // Process account list to group by header ID
        $accountchild = [];
        foreach ($data['accountlist'] as $single) {
            $accountchild[$single['coa_header_id']][] = $single;
        }
        $data['accountlists'] = $accountchild;
        //  echo "<pre>"; print_r($data['accountlists']);die();
        // Generate dropdown menu
        $select = '<option value="">Select an account</option>';
    
        foreach ($accountheaders as $parentItem) {
            $coaHeaderId = $parentItem['coa_header_id'];
            $coaHeader = $parentItem['coa_header'];
            $i=0;
            if (isset($child[$coaHeaderId])) {
                $select .= '<optgroup label="' . htmlspecialchars($coaHeader) . '">';
    
                foreach ($accountchild[$coaHeaderId] as $childItem) {
                    $childId = $childItem['coa_type_id'];
    
                    // Ensure 'acn' is set for the corresponding header ID
                    $typename = "";
                    if (isset($accountchild[$coaHeaderId][0]['acn'])) {
                        $typename .= $accountchild[$coaHeaderId][$i]['acn']." - ";
                    }
                    $typename .= $childItem['holder'];
                    $sel = ($account_id==$accountchild[$coaHeaderId][$i]['acn']) ? "selected" : "";
                    $select .= '<option value="' . htmlspecialchars($accountchild[$coaHeaderId][$i]['acn']) . '" data-id="' . htmlspecialchars($typename) . '" '.$sel.'>' . htmlspecialchars($typename) . '</option>';
                    $i++;
                }
    
                $select .= '</optgroup>';
            }
        }
    
        echo $select;
    }


    public function action()
    {
        $transaction_number = get_latest_trans_number();
        $journal_number     = $this->input->post('journal_number');
        $masterdata = [
            'transaction_number' => $transaction_number,
            'journal_number'     => $journal_number,
            'journal_date'       => $this->input->post('journal_date'),
            'journal_amount'       => $this->input->post('journal_amount'),
            'journal_note'       => $this->input->post('journal_note'),
            'journal_reference'       => $this->input->post('journal_reference'),
            'journal_basis'      => $this->input->post('journal_basis'),
            'created_by'         => $this->session->userdata('id'),
            'created_dt'         => date('Y-m-d H:i:s')
        ];
        

        $this->db->insert('cberp_manual_journals',$masterdata);


        $account_id        = $this->input->post('account');
        $debit             = $this->input->post('debit');
        $credit            = $this->input->post('credit');
        $description       = $this->input->post('note');

        $i = 0;
        $prodindex = 0;        
        $productlist = array();
        $journallist = array();
        if($account_id)
        {
            
            foreach ($account_id as $key => $value) {
                $itemdata = array(
                    'journal_number' => $journal_number,
                    'account_id' => $account_id[$key],
                    'debit' => $debit[$key],
                    'credit' => $credit[$key],
                    'description' => $description[$key]
                );
                $productlist[$prodindex] = $itemdata;

                //transation data
                $transactiondata = [
                    'acid' => $account_id[$key],
                    'type' => 'Manual Journal',
                    'cat' => 'Journal Entry',
                    'debit' => $debit[$key],
                    'credit' => $credit[$key],
                    'eid' => $this->session->userdata('id'),
                    'date' => date('Y-m-d'),
                    'transaction_number'=>$transaction_number
                ];
                
                $journallist[$prodindex] = $transactiondata;

                $lastbalUpdate = ($credit[$key]) ? 'lastbal - ' . $credit[$key] : 'lastbal + ' . $debit[$key];        
                $this->db->set('lastbal', $lastbalUpdate, FALSE);
                $this->db->where('acn', $account_id[$key]);
                $this->db->update('cberp_accounts'); 

                $i++;
                $prodindex++;
            }

        }
      
        if ($prodindex > 0) {
            $this->db->insert_batch('cberp_manual_journal_items', $productlist);             
            $this->db->insert_batch('cberp_transactions', $journallist);             
        
        }
        echo json_encode(array('status' => 'Success'));

    }

    public function view()
    {
 
      $transaction_number = $this->input->get('id'); 
      $head['title'] = "Manual Journal #".$transaction_number;
      $data['journal_master'] = $this->manualjournal->journal_master_by_number($transaction_number);
      $data['journal_items'] = $this->manualjournal->journal_items_by_number($data['journal_master']['journal_number']);
      
     //echo "<pre>"; print_r($data['journal_master']); die();
      $this->load->view('fixed/header', $head);
      $this->load->view('manualjournals/journal_view', $data);
      $this->load->view('fixed/footer');
    }

    public function edit()
    {
        $journal_number = $this->input->get('id');
        $this->load->library("Common");
        $data['taxlist'] = $this->common->taxlist($this->config->item('tax'));
        $this->load->model('plugins_model', 'plugins');
        $data['exchange'] = $this->plugins->universal_api(5);
        $head['title'] = "Manual Journal #".$journal_number;
        $head['usernm'] = $this->aauth->get_user()->username;   
        $head['journal_number'] = $journal_number;   
        $head['basislist'] = $this->manualjournal->load_basis_list();
        $data['journal_master'] = $this->manualjournal->journal_master_by_journal($journal_number);
       
        $data['journal_items'] = $this->manualjournal->journal_items_by_number($data['journal_master']['journal_number']);
        //  echo "<pre>"; print_r($data['journal_items']); die();
        $this->load->view('fixed/header', $head);
        $this->load->view('manualjournals/edit', $data);
        $this->load->view('fixed/footer');
    }
    public function editaction()
    {
        $transaction_number = $this->input->post('transaction_number');
        $journal_number     = $this->input->post('journal_number');
        $masterdata = [
            'journal_date'       => $this->input->post('journal_date'),
            'journal_amount'       => $this->input->post('journal_amount'),
            'journal_note'       => $this->input->post('journal_note'),
            'journal_reference'       => $this->input->post('journal_reference'),
            'journal_basis'      => $this->input->post('journal_basis'),
            'updated_by'         => $this->session->userdata('id'),
            'updated_dt'         => date('Y-m-d H:i:s')
        ];
        

        $this->db->update('cberp_manual_journals',$masterdata,['journal_number'=>$journal_number]);


        $account_id        = $this->input->post('account');
        $debit             = $this->input->post('debit');
        $credit            = $this->input->post('credit');
        $description       = $this->input->post('note');

        //previous datas
        $account_id_old        = $this->input->post('account_id_old');
        $debit_old             = $this->input->post('debit_old');
        $credit_old            = $this->input->post('credit_old');

        $i = 0;
        $prodindex = 0;        
        $productlist = array();
        $journallist = array();
        $this->db->delete('cberp_manual_journal_items',['journal_number'=>$journal_number]);
        $this->db->delete('cberp_transactions',['transaction_number'=>$transaction_number]);
        if($account_id_old)
        {
  
            foreach ($account_id_old as $key1 => $row) {
                 //update previous
                 $lastbalUpdate_old = ($credit_old[$key1]>0) ? 'lastbal + ' . $credit_old[$key1] : 'lastbal - ' . $debit_old[$key1];        
                 $this->db->set('lastbal', $lastbalUpdate_old, FALSE);
                 $this->db->where('acn', $account_id_old[$key1]);
                 $this->db->update('cberp_accounts'); 
                 echo "\n<br>". $this->db->last_query();
            }
        }
        if($account_id)
        {
            
            foreach ($account_id as $key => $value) {
                $itemdata = array(
                    'journal_number' => $journal_number,
                    'account_id' => $account_id[$key],
                    'debit' => $debit[$key],
                    'credit' => $credit[$key],
                    'description' => $description[$key]
                );
                $productlist[$prodindex] = $itemdata;

                //transation data
                $transactiondata = [
                    'acid' => $account_id[$key],
                    'type' => 'Manual Journal',
                    'cat' => 'Journal Entry',
                    'debit' => $debit[$key],
                    'credit' => $credit[$key],
                    'eid' => $this->session->userdata('id'),
                    'date' => date('Y-m-d'),
                    'transaction_number'=>$transaction_number
                ];
                
                $journallist[$prodindex] = $transactiondata;

               

                $lastbalUpdate = ($credit[$key]>0) ? 'lastbal - ' . $credit[$key] : 'lastbal + ' . $debit[$key];        
                $this->db->set('lastbal', $lastbalUpdate, FALSE);
                $this->db->where('acn', $account_id[$key]);
                $this->db->update('cberp_accounts'); 
                $i++;
                $prodindex++;
            }

        }
      
        if ($prodindex > 0) {
            $this->db->insert_batch('cberp_manual_journal_items', $productlist);             
            $this->db->insert_batch('cberp_transactions', $journallist);             
        
        }
        echo json_encode(array('status' => 'Success'));

    }
    
    
    
}
