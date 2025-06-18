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
require_once APPPATH . 'third_party/vendor/autoload.php';
class Bankingtransactions extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('bankingtransactions_model', 'bankingtransactions');
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
        $data['permissions'] = load_permissions('Accounts','Banking','Transactions');
        $head['title'] = "Banking Transactions";
        $head['usernm'] = $this->aauth->get_user()->username;      
        $this->load->view('fixed/header', $head);
        $data['trans_type'] = ($this->input->get('type')) ? $this->input->get('type') : "All";
        $data['bank_summary'] =$this->bankingtransactions->bank_transaction_summary($data['bank_accounts']['code']);
        $this->load->view('banking/bankingtransactionslist',$data);
        $this->load->view('fixed/footer');
    }
    public function ajax_list()
    {
        // $dategap = !empty($this->input->post('dategap'))?$this->input->post('dategap'):"";
        $list = $this->bankingtransactions->get_datatables($this->limited);
        $data = array();

        $no = $this->input->post('start');

        foreach ($list as $enquiry) {
            $no++;
            $row = array();
            $customer_or_supplier = ($enquiry->trans_type=='Income') ? $enquiry->customer: $enquiry->supplier;
            $row[] = $no;
            $row[] = '<a  href="' .base_url("transactions/banking_transaction?ref=$enquiry->trans_ref_number"). '" class="breaklink">&nbsp; ' .$enquiry->trans_ref_number. '</a>';
            $row[] = $enquiry->trans_number;
            $row[] = date('d-m-Y H:i:s', strtotime($enquiry->trans_date));
            $row[] = $enquiry->trans_type;            
            $row[] = $enquiry->transcat_name;
            $row[] = $enquiry->name;
            $row[] = $customer_or_supplier;
            $row[] = $enquiry->trans_amount;
           
            
            $data[] = $row;
        }
       
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->bankingtransactions->count_all($this->limited),
            "recordsFiltered" => $this->bankingtransactions->count_filtered($this->limited),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);

    }

    public function create()
    {
        $type = $this->input->get('type');
        $data['type'] = $type;
        $data['code'] = $this->input->get('code');
        if($type=='income')
        {
            $head['title'] = "New Income";
            $head['usernm'] = $this->aauth->get_user()->username;             
            $data['customers'] = customer_list();
        }
        else if($type=='expense'){
            $head['title'] = "New Expense";            
            $data['suppliers'] = supplier_list();
        }
        $data['bankaccounts'] = bank_account_list();
        $data['default_bank_account'] = default_bank_account();
        $data['default_sales_account'] = default_sales_account('Income');
        $data['transaction_number'] = get_transnumber();
        $data['category'] = $this->bankingtransactions->load_category_by_type(ucfirst($type));
        $data['accounttypes'] = $this->accounts->load_coa_account_types();
        $accountlist = $this->accounts->load_account_list_by_type($type);
        
        
        $accountlists = [];
        foreach($accountlist as $row){
            $accountlists[$row['typename']][] = $row;
        }  
        $data['permissions'] = load_permissions('Accounts','Banking','Transactions');
        $data['accountslist'] = $accountlists;
        $head['usernm'] = $this->aauth->get_user()->username;      
        $this->load->view('fixed/header', $head);
        $this->load->view('banking/createitem',$data);
        $this->load->view('fixed/footer');
        
    }
   
    public function banktransaction_action()
    {
        $trans_number = $this->input->post('trans_number');
        $trans_date = date('Y-m-d', strtotime($this->input->post('trans_date')));
        $trans_type = $this->input->post('trans_type');
        $trans_description = $this->input->post('trans_description');
        $trans_category_id = $this->input->post('trans_category_id');
        $trans_customer_id = $this->input->post('trans_customer_id');
        $trans_supplier_id = $this->input->post('trans_supplier_id');
        $trans_payment_method = $this->input->post('trans_payment_method');
        $trans_reference = $this->input->post('trans_reference');
        
        $trans_account_id = $this->input->post('trans_account_id');
        $trans_chart_of_account_id = $this->input->post('trans_chart_of_account_id');
        $trans_amount = $this->input->post('trans_amount');

        $payerid = ($trans_type=='Income') ? $trans_customer_id: $trans_supplier_id;
        $time = date("H:i:s");
        // $account_id = $this->input->post('account_id');

            $data = array(
                'trans_ref_number' => get_banktrans_reference_number(),
                'trans_number' => $this->input->post('trans_number'),
                'trans_date' => date('Y-m-d', strtotime($this->input->post('trans_date')))." ".$time,
                'trans_account_id' => $this->input->post('trans_account_id'),
                'trans_type' => $this->input->post('trans_type'),
                'trans_payment_method' => $this->input->post('trans_payment_method'),
                'trans_amount' => $this->input->post('trans_amount'),
                'trans_description' => $this->input->post('trans_description'),
                'trans_chart_of_account_id'=> $this->input->post('trans_chart_of_account_id'),
                'trans_category_id'=> $this->input->post('trans_category_id'),
                'trans_customer_id'=> $this->input->post('trans_customer_id'),
                'trans_supplier_id'=> $this->input->post('trans_supplier_id'),
                'trans_reference'=> $this->input->post('trans_reference'),
                'transfered_by'=> $this->session->userdata('id')
            );
       
                if ($this->db->insert('cberp_bank_transactions', $data)) {
                    //update chart of account
                    if($trans_type=="Income")
                    {
                        $this->db->set('lastbal', 'lastbal + ' . $trans_amount, FALSE);
                        $this->db->where('acn', $trans_account_id);
                        $this->db->update('cberp_accounts');   
    
                        $this->db->set('lastbal', 'lastbal - ' . $trans_amount, FALSE);
                        $this->db->where('acn', $trans_chart_of_account_id);
                        $this->db->update('cberp_accounts'); 
                    }
                    else{
                        $this->db->set('lastbal', 'lastbal - ' . $trans_amount, FALSE);
                        $this->db->where('acn', $trans_account_id);
                        $this->db->update('cberp_accounts');   
    
                        $this->db->set('lastbal', 'lastbal + ' . $trans_amount, FALSE);
                        $this->db->where('acn', $trans_chart_of_account_id);
                        $this->db->update('cberp_accounts'); 
                    }
                        
                    
                    //insert transactions debit & credit

                    
                    
                    // Define the array with the conditional amount field included
                    $transaction_number = get_latest_trans_number();
                    $banktranslink_data = [                            
                        'trans_type' => 'Deposit',
                        'transaction_number'=> $transaction_number,
                        'bank_transaction_number'=>$this->input->post('trans_number'),
                        'created_dt' => date('Y-m-d H:i:s'),
                        'created_by'=> $this->session->userdata('id')
                       
                    ];
                    $this->db->insert('cberp_payment_transaction_link', $banktranslink_data);
                    
                    $creditdata = [
                        'acid' => $this->input->post('trans_account_id'),
                        'type' => $trans_type,
                        'cat' => 'New ' . $trans_type,
                        'eid' => $this->session->userdata('id'),
                        'date' => date('Y-m-d'),
                        'payerid' => $payerid,
                        'transaction_number' => $transaction_number
                    ];
                    if ($trans_type == "Income") {
                        $creditdata['debit'] =  $this->input->post('trans_amount');
                    } else {
                        $creditdata['credit'] =  $this->input->post('trans_amount');
                    }
                    $this->db->insert('cberp_transactions',$creditdata);
                    
                    $debitdata = [
                        'acid' => $this->input->post('trans_chart_of_account_id'),
                        'type' => $trans_type,
                        'cat' => 'New '.$trans_type,                        
                        'eid' => $this->session->userdata('id'),
                        'date' => date('Y-m-d'),
                        'payerid' => $payerid,
                        'transaction_number' => $transaction_number
                    ];
                    if ($trans_type == "Income") {
                        $debitdata['credit'] =  $this->input->post('trans_amount');
                    } else {
                        $debitdata['debit'] =  $this->input->post('trans_amount');
                    }
                    $this->db->insert('cberp_transactions',$debitdata);
                    echo json_encode(array('status' => 'Success', 'message' =>"Transaction Created Successfully"));
                } else {
                    echo json_encode(array('status' => 'Error', 'message' =>"Account Number already used"));
                }
            // }
        }
    
        public function edit()
        {
            $type = $this->input->get('type');
            $refernce = $this->input->get('ref');
            $data['type'] = $type;
            if($type=='income')
            {
                $head['title'] = "New Income";
                $head['usernm'] = $this->aauth->get_user()->username;             
                $data['customers'] = customer_list();
            }
            else if($type=='expense'){
                $head['title'] = "New Expense";            
                $data['suppliers'] = supplier_list();
            }
            $data['bankaccounts'] = bank_account_list();
            $data['default_bank_account'] = default_bank_account();
            $data['default_sales_account'] = default_sales_account('Income');
            $data['transaction_number'] = get_transnumber();
            $data['category'] = $this->bankingtransactions->load_category_by_type(ucfirst($type));
            $data['accounttypes'] = $this->accounts->load_coa_account_types();
            $accountlist = $this->accounts->load_account_list();
            $accountlists = [];
            foreach($accountlist as $row){
                $accountlists[$row['typename']][] = $row;
            }  
            $data['accountslist'] = $accountlists;
            $head['usernm'] = $this->aauth->get_user()->username;  
            
            $data['transaction_details'] = $this->bankingtransactions->bank_transaction_details_by_reference($refernce); 
            $this->load->view('fixed/header', $head);
            $this->load->view('banking/edititem',$data);
            $this->load->view('fixed/footer');
            
        }

        public function banktransaction_update_action()
        {
            $trans_number = $this->input->post('trans_number');
            $trans_date = date('Y-m-d', strtotime($this->input->post('trans_date')));
            $trans_type = $this->input->post('trans_type');
            $trans_description = $this->input->post('trans_description');
            $trans_category_id = $this->input->post('trans_category_id');
            $trans_customer_id = $this->input->post('trans_customer_id');
            $trans_supplier_id = $this->input->post('trans_supplier_id');
            $trans_payment_method = $this->input->post('trans_payment_method');
            $trans_reference = $this->input->post('trans_reference');
            
            $trans_account_id = $this->input->post('trans_account_id');
            $trans_chart_of_account_id = $this->input->post('trans_chart_of_account_id');
            $trans_chart_of_account_id_old = $this->input->post('trans_chart_of_account_id_old');
            $trans_account_id_old = $this->input->post('trans_account_id_old');
            $trans_amount = $this->input->post('trans_amount');
            $trans_amount_old = $this->input->post('trans_amount_old');
            $trans_ref_number = $this->input->post('trans_ref_number');

            $general_trans_number = $this->bankingtransactions->get_trans_number_by_bank_trans_number($trans_number);
            $payerid = ($trans_type=='Income') ? $trans_customer_id: $trans_supplier_id;
                $time = date("H:i:s");
                $data = array(
                    'trans_number' => $this->input->post('trans_number'),
                    'trans_date' => date('Y-m-d', strtotime($this->input->post('trans_date')))." ".$time,
                    'trans_account_id' => $this->input->post('trans_account_id'),
                    'trans_type' => $this->input->post('trans_type'),
                    'trans_payment_method' => $this->input->post('trans_payment_method'),
                    'trans_amount' => $this->input->post('trans_amount'),
                    'trans_description' => $this->input->post('trans_description'),
                    'trans_chart_of_account_id'=> $this->input->post('trans_chart_of_account_id'),
                    'trans_category_id'=> $this->input->post('trans_category_id'),
                    'trans_customer_id'=> $this->input->post('trans_customer_id'),
                    'trans_supplier_id'=> $this->input->post('trans_supplier_id'),
                    'trans_reference'=> $trans_reference,
                    'transfered_by'=> $this->session->userdata('id')
                );
        

                    if ($this->db->update('cberp_bank_transactions', $data,['trans_ref_number'=>$trans_ref_number])) {
                        //update chart of account
                        if($trans_type=="Income")
                        {
                            $this->db->set('lastbal', 'lastbal - ' . $trans_amount_old, FALSE);
                            $this->db->where('acn', $trans_account_id_old);
                            $this->db->update('cberp_accounts');

                            $this->db->set('lastbal', 'lastbal + ' . $trans_amount, FALSE);
                            $this->db->where('acn', $trans_account_id);
                            $this->db->update('cberp_accounts');   
                           
        
                            $this->db->set('lastbal', 'lastbal + ' . $trans_amount_old, FALSE);
                            $this->db->where('acn', $trans_chart_of_account_id_old);
                            $this->db->update('cberp_accounts');

                            $this->db->set('lastbal', 'lastbal - ' . $trans_amount, FALSE);
                            $this->db->where('acn', $trans_chart_of_account_id);
                            $this->db->update('cberp_accounts'); 
                        }
                        else{
                            $this->db->set('lastbal', 'lastbal + ' . $trans_amount_old, FALSE);
                            $this->db->where('acn', $trans_account_id_old);
                            $this->db->update('cberp_accounts');

                            $this->db->set('lastbal', 'lastbal - ' . $trans_amount, FALSE);
                            $this->db->where('acn', $trans_account_id);
                            $this->db->update('cberp_accounts');   
        
                            $this->db->set('lastbal', 'lastbal - ' . $trans_amount_old, FALSE);
                            $this->db->where('acn', $trans_chart_of_account_id_old);
                            $this->db->update('cberp_accounts');

                            $this->db->set('lastbal', 'lastbal + ' . $trans_amount, FALSE);
                            $this->db->where('acn', $trans_chart_of_account_id);
                            $this->db->update('cberp_accounts'); 
                        }
                            
                        
                        //insert transactions debit & credit

                        
                        
                        // Define the array with the conditional amount field included
                        // $transaction_number = get_latest_trans_number();
                        $banktranslink_data = [                            
                            'trans_type' => 'Deposit',
                            'bank_transaction_number'=>$this->input->post('trans_number'),
                            'created_dt' => date('Y-m-d H:i:s'),
                            'created_by'=> $this->session->userdata('id')
                        
                        ];
                        $this->db->update('cberp_payment_transaction_link', $banktranslink_data,['bank_transaction_number'=>$this->input->post('trans_number')]);
                        
                        $creditdata = [
                            'acid' => $this->input->post('trans_account_id'),
                            'type' => $trans_type,
                            'cat' => 'New ' . $trans_type,
                            'eid' => $this->session->userdata('id'),
                            'date' => date('Y-m-d'),
                            'payerid' => $payerid
                        ];
                        if ($trans_type == "Income") {
                            $creditdata['debit'] =  $this->input->post('trans_amount');
                        } else {
                            $creditdata['credit'] =  $this->input->post('trans_amount');
                        }
                        $this->db->update('cberp_transactions',$creditdata,['transaction_number'=>$general_trans_number,'acid'=>$this->input->post('trans_account_id')]);
                        // die($this->db->last_query());
                        $debitdata = [
                            'acid' => $this->input->post('trans_chart_of_account_id'),
                            'type' => $trans_type,
                            'cat' => 'New '.$trans_type,                        
                            'eid' => $this->session->userdata('id'),
                            'date' => date('Y-m-d'),
                            'payerid' => $payerid
                        ];
                        if ($trans_type == "Income") {
                            $debitdata['credit'] =  $this->input->post('trans_amount');
                        } else {
                            $debitdata['debit'] =  $this->input->post('trans_amount');
                        }
                        $this->db->update('cberp_transactions',$debitdata,['transaction_number'=>$general_trans_number,'acid'=>$this->input->post('trans_chart_of_account_id')]);
                        echo json_encode(array('status' => 'Success', 'message' =>"Transaction Created Successfully"));
                    } else {
                        echo json_encode(array('status' => 'Error', 'message' =>"Account Number already used"));
                    }
                // }
        }
        function bank_account_view()
        {
            $id = $this->input->get('id');        
            $data['bank_accounts'] = bank_account_list_by_id($id);
            $data['transactions'] =$this->bankingtransactions->bank_transaction_list($data['bank_accounts']['code']);
            $data['bank_summary'] =$this->bankingtransactions->bank_transaction_summary_by_code($data['bank_accounts']['code']);
            // echo "<pre>"; print_r($data['bank_summary']); die();
            $this->load->view('fixed/header');
            $this->load->view('banking/bank_account_view', $data);
            $this->load->view('fixed/footer');
        }


        public function bank_transaction_pdf()
        {
            // ini_set('display_errors', 1);
            // ini_set('display_startup_errors', 1);
            // error_reporting(E_ALL);
            ini_set('memory_limit', '64M');
            
            $loc = location($this->aauth->get_user()->loc);
            $type = $this->input->get('type');   
               
            $configurations = $this->session->userdata('configurations');
            $data['config_currency'] = $configurations['config_currency'];
            $data['companyNanme'] = $loc['cname'];
            $company = '' . $loc['address'] . '<br>' . $loc['city'] . ', ' . $loc['region'] . '<br>' . $loc['country'] . ' -  ' . $loc['postbox'] . '<br>' . $this->lang->line('Phone') . ': ' . $loc['phone'] . '<br> ' . $this->lang->line('Email') . ': ' . $loc['email'];
            $data['lang']['company'] = $company;
            $data['display_fields'] = ['Sl No.', 'Relation', 'Transaction Number', 'Date', 'Type', 'Category', 'Account', 'Customer', 'Supplier', 'Amount'];
            $data['output_data'] = $this->bankingtransactions->export_data($type);
            $data['caption'] = 'Bank Transactions';
        
            // Generate HTML view
            $html = $this->load->view('print_files/common-a4_v1', $data, true);
        
            // Load mPDF library
            $this->load->library('pdf');
        
            // Initialize mPDF with custom dimensions for A4 landscape
            $pdf = $this->pdf->load([
                'mode' => 'utf-8',
                'format' => [297, 210], // Custom size for A4 landscape (297mm x 210mm)
                'margin_top' => 10,
                'margin_bottom' => 10,
                'margin_left' => 10,
                'margin_right' => 10
            ]);
        
            // Write HTML to the PDF
            $pdf->WriteHTML($html);
        
            // Output the PDF
            $filename = 'ExportedData_' . date('YmdHis') . '.pdf';
            $pdf->Output($filename, 'I'); // 'I' sends the PDF inline to the browser
        }

        public function bank_transaction_csv()
        {
            // Get location and configuration details
            $loc = location($this->aauth->get_user()->loc);
            $type = $this->input->get('type');   

            // Define the display fields for CSV columns
            $data['display_fields'] = ['Sl No.', 'Relation', 'Transaction Number', 'Date', 'Type', 'Category', 'Account', 'Customer', 'Supplier', 'Amount'];

            // Fetch the data for export
            $data['output_data'] = $this->bankingtransactions->export_data($type);

            // Set the CSV file name
            $filename = 'Bank_Transactions_' . date('YmdHis') . '.csv';

            // Open PHP output stream to write the CSV
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Pragma: no-cache');
            header('Expires: 0');
            
            $output = fopen('php://output', 'w');

            // Write the header row (column names)
            fputcsv($output, $data['display_fields']);

            // Write the data rows
            $serial_number = 1; // Serial number for rows
            foreach ($data['output_data'] as $row) {
                // Add serial number as the first column in each row
                array_unshift($row, $serial_number++);
                // Write the current row to CSV
                fputcsv($output, $row);
            }

            // Close the file pointer
            fclose($output);
        }

        function reconciliations_index()
        {
            $id = $this->input->get('id');        
            $data['bank_accounts'] = bank_account_list_by_id($id);
            $data['transactions'] =$this->bankingtransactions->bank_transaction_list($data['bank_accounts']['code']);
            $data['bank_summary'] =$this->bankingtransactions->bank_transaction_summary_by_code($data['bank_accounts']['code']);
            // echo "<pre>"; print_r($data['bank_summary']); die();
            $this->load->view('fixed/header');
            $this->load->view('banking/reconciliations_index_view', $data);
            $this->load->view('fixed/footer');
        }

}
