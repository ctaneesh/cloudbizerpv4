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

class Reconciliations extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('reconciliations_model', 'reconciliations');
        $this->load->library("Aauth");
        if (!$this->aauth->is_loggedin()) {
            redirect('/user/', 'refresh');
        }
        // if ($this->aauth->get_user()->roleid < 4) {

        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');

        // }


    }

    public function index()
    {
      //  $data['permissions'] = load_permissions('Accounts','Banking','Reconciliations');
        //  ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
        $head['title'] = "Reconciliations";
        $head['usernm'] = $this->aauth->get_user()->username;        
        $this->load->view('fixed/header', $head);
        $data['total_closing_balance'] = $this->reconciliations->get_total_closing_balance();
        $this->load->view('reconciliations/reconciliationslist',$data);
        $this->load->view('fixed/footer');
    }
    public function ajax_list()
    {
        // $dategap = !empty($this->input->post('dategap'))?$this->input->post('dategap'):"";
        $list = $this->reconciliations->get_datatables($this->limited);
        $data = array();

        $no = $this->input->post('start');

        foreach ($list as $enquiry) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $enquiry->reconciliations_id;
            $row[] = date('d-m-Y H:i:s', strtotime($enquiry->created_dt));
            $row[] = $enquiry->name;            
            $row[] = date('d-m-Y', strtotime($enquiry->date_from));
            $row[] = date('d-m-Y', strtotime($enquiry->date_to));
            $row[] = $enquiry->opening_balance;
            $row[] = $enquiry->closing_balance;
            $actionbtn = '<a href="' . base_url("reconciliations/edit?id=$enquiry->reconciliations_id") . '" title="Edit" class="btn btn-sm btn-secondary"><span class="fa fa-pencil" aria-hidden="true"></span></a> <button class="btn btn-sm btn-secondary" type="button" title="delete" onclick="delete_action(\''.$enquiry->reconciliations_id.'\')"><span class="fa fa-trash" aria-hidden="true"></span></button>';
            $row[] = $actionbtn;

            
            $data[] = $row;
        }
       
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->reconciliations->count_all($this->limited),
            "recordsFiltered" => $this->reconciliations->count_filtered($this->limited),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);

    }

    public function create()
    {
        $head['title'] = "Reconciliations Create";
        $head['usernm'] = $this->aauth->get_user()->username;   
        
        $data['bankaccounts'] = bank_account_list_with_balance();
        $data['default_bankaccount'] = default_bank_account();

        $datefrom = ($this->input->get('datefrom')) ? $this->input->get('datefrom') : date('Y-m-01');         
        $dateto = ($this->input->get('dateto')) ? $this->input->get('dateto') : date('Y-m-d');   
        $account_code = ($this->input->get('bank_account')) ? $this->input->get('bank_account') : $data['default_bankaccount']['code'];    
        $this->load->view('fixed/header', $head);

        $data['datefrom'] = $datefrom;
        $data['dateto'] = $dateto;
        $data['account_code'] = $account_code;

           
        $data['banktransactions']  = $this->reconciliations->transaction_list($datefrom,$dateto,$account_code);  
        //  echo "<pre>"; print_r($data['banktransactions']); die();
        // $data['banktransactions']  = $this->reconciliations->transaction_list(date('Y-m-01'),date('Y-m-d'),'900');
        $this->load->view('reconciliations/create',$data);
        $this->load->view('fixed/footer');
    }
    public function action(){

        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
         $start_date = $this->input->post('start_date', true);
         $end_date = $this->input->post('end_date', true);
         $closing_balance = $this->input->post('closing_balance', true);
         $bank_account = $this->input->post('bank_account', true); 
         $opening_balance = $this->input->post('opening_balance', true); 
         $note = $this->input->post('note', true); 
         $id = $this->input->post('id', true); 
         $reconciliations_id = get_latest_reconciliation_number();
         $data = [
             'date_from' => $start_date,
             'date_to' => $end_date,
             'account_id' => $bank_account,
             'opening_balance' => $opening_balance,
             'closing_balance' => $closing_balance,
             'created_by' => $this->session->userdata('id'),
             'created_dt' => date('Y-m-d H:i:s'),
             'reconciliations_id' => $reconciliations_id,
             'note' => $note
         ];

         $trans_ref_number = $this->input->post('trans_ref_number', true); 
         $transtype = $this->input->post('transtype', true); 
         $trans_amount = $this->input->post('trans_amount', true); 
         $checkedflg = $this->input->post('checkedflg', true); 
         $itemdata = [];
         $index = 0;
         foreach ($trans_ref_number as $key => $value) {
            if(!empty($checkedflg[$key]) == 1)
            { 
                if($transtype[$key]=='credit')
                {
                    $withdrawal=$trans_amount[$key];
                    $deposit=0;
                }
                else{
                    $deposit=$trans_amount[$key];
                    $withdrawal=0;
                }
                $data1 = array(
                    'trans_ref_number' => $trans_ref_number[$key],
                    'withdrawal' => $withdrawal,
                    'deposit' => $deposit,
                    'reconciliations_id' => $reconciliations_id,
                );
                $itemdata[$index] = $data1;
                $index++;
            }
         }
         if(!empty($itemdata)){
            $this->db->insert_batch('cberp_reconciliations_items', $itemdata);
         }
            if($id > 0)
            {
                $this->db->update('cberp_reconciliations', $data,['id'=>$id]);
                echo json_encode(array('status' => 'Success', 'message' =>"Category Created Successfully"));
               
            }
            else{
                if ($this->db->insert('cberp_reconciliations', $data)) {
                    echo json_encode(array('status' => 'Success'));
                } else {
                    echo json_encode(array('status' => 'Error'));
                }
            }
     
    }

    
    public function edit()
    {
        $head['title'] = "Reconciliations Edit";
        $head['usernm'] = $this->aauth->get_user()->username;   
        $reconciliations_id = $this->input->get('id');
        $details  = $this->reconciliations->details_by_id($reconciliations_id);
           
           
        $data['bankaccounts'] = bank_account_list_with_balance();
        // $data['default_bankaccount'] = default_bank_account();

        // $datefrom = ($this->input->get('datefrom')) ? $this->input->get('datefrom') : date('Y-m-01');         
        // $dateto = ($this->input->get('dateto')) ? $this->input->get('dateto') : date('Y-m-d');   
        // $account_code = ($this->input->get('bank_account')) ? $this->input->get('bank_account') : $data['default_bankaccount']['code'];    
        $this->load->view('fixed/header', $head);

        $data['datefrom'] = $details['date_from']; 
        $data['dateto'] = $details['date_to']; 
        $data['account_code'] = $details['account_id']; 
        $data['note'] = $details['note']; 
        $data['reconciliations_id'] = $details['reconciliations_id']; 

        $data['opening_balance'] = $details['opening_balance'];    
        $data['closing_balance'] =$details['closing_balance'];    
        $data['banktransactions']  = $this->reconciliations->transaction_list_edit($details['date_from'], $details['date_to'], $details['account_id'],$details['reconciliations_id']);  
        //  echo "<pre>"; print_r($data['banktransactions']); 
        $this->load->view('reconciliations/edit',$data);
        $this->load->view('fixed/footer');
    }

    public function editaction(){

        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
         $start_date = $this->input->post('start_date', true);
         $end_date = $this->input->post('end_date', true);
         $closing_balance = $this->input->post('closing_balance', true);
         $bank_account = $this->input->post('bank_account', true); 
         $opening_balance = $this->input->post('opening_balance', true); 
         $reconciliations_id = $this->input->post('reconciliations_id', true); 
         $note = $this->input->post('note', true); 
         $id = $this->input->post('id', true); 
         $data = [
             'date_from' => $start_date,
             'date_to' => $end_date,
             'account_id' => $bank_account,
             'opening_balance' => $opening_balance,
             'closing_balance' => $closing_balance,
             'created_by' => $this->session->userdata('id'),
             'created_dt' => date('Y-m-d H:i:s'),
             'reconciliations_id' => $reconciliations_id,
             'note' => $note
         ];

         $trans_ref_number = $this->input->post('trans_ref_number', true); 
         $transtype = $this->input->post('transtype', true); 
         $trans_amount = $this->input->post('trans_amount', true); 
         $checkedflg = $this->input->post('checkedflg', true); 
         $itemdata = [];
         $index = 0;
         foreach ($trans_ref_number as $key => $value) {
            if(!empty($checkedflg[$key]) == 1)
            { 
                if($transtype[$key]=='credit')
                {
                    $withdrawal=$trans_amount[$key];
                    $deposit=0;
                }
                else{
                    $deposit=$trans_amount[$key];
                    $withdrawal=0;
                }
                $data1 = array(
                    'trans_ref_number' => $trans_ref_number[$key],
                    'withdrawal' => $withdrawal,
                    'deposit' => $deposit,
                    'reconciliations_id' => $reconciliations_id,
                );
                $itemdata[$index] = $data1;
                $index++;
            }
         }
         if(!empty($itemdata)){
            $this->db->delete('cberp_reconciliations_items',['reconciliations_id'=>$reconciliations_id]);
            $this->db->insert_batch('cberp_reconciliations_items', $itemdata);
         }
            if($reconciliations_id > 0)
            {
                $this->db->update('cberp_reconciliations', $data,['reconciliations_id'=>$reconciliations_id]);
                echo json_encode(array('status' => 'Success', 'message' =>"Category Created Successfully"));
               
            }
            else{
                if ($this->db->insert('cberp_reconciliations', $data)) {
                    echo json_encode(array('status' => 'Success'));
                } else {
                    echo json_encode(array('status' => 'Error'));
                }
            }
     
    }

    public function deleteaction(){
        $reconciliations_id = $this->input->post('id', true);
        $this->db->delete('cberp_reconciliations_items',['reconciliations_id'=>$reconciliations_id]);
        $this->db->delete('cberp_reconciliations',['reconciliations_id'=>$reconciliations_id]);
        echo json_encode(array('status' => 'Success'));        
    }
    public function load_category_by_id(){
        $accountdetails = $this->reconciliations->load_category_by_id($this->input->post('category_id'));       
        echo json_encode(array('status' => 'Success', 'data' => $accountdetails));        
    }


}
