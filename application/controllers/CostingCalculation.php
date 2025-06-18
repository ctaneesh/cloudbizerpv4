<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\Printer;

class CostingCalculation extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('costingcalculation_model', 'costingcalculation');
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

    
    //create customer enquiry
    public function index()
    {
        $id = $this->input->get('pid', true);
        $this->load->model('plugins_model', 'plugins');
        $this->load->library("Common");
        // error_reporting(-1);
		// ini_set('display_errors', 1);
        $data['purchasemasterdata'] = $this->costingcalculation->purchase_details($id);
        $data['purchaseitemsdata'] = $this->costingcalculation->purchase_item_details($id);
        $this->load->library("Common");
        $data['custom_fields_c'] = $this->custom->add_fields(1);
        $data['exchange'] = $this->plugins->universal_api(5);
        $data['taxlist'] = $this->common->taxlist($this->config->item('tax'));
        $head['title'] = "Purchase Reciepts";
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['custom_fields'] = $this->custom->add_fields(2);
        $this->load->view('fixed/header', $head);
        $this->load->view('costing/costingcalculation', $data);
        $this->load->view('fixed/footer');
    }

    public function dataoperation(){
        $purchase_id = $this->input->post('purchase_id', true);
        $master_data = [
            "salepoint_name" => $this->input->post('salepoint_name', true),
            "salepoint_id" => $this->input->post('salepoint_id', true),
            "supplier_name" => $this->input->post('supplier_name', true),
            "purchase_id" => $purchase_id,
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
            "purchase_reciept_number" => $this->input->post('srv', true),
            "purchase_receipt_date" => $this->input->post('srvdate', true),
            "purchase_amount" => $this->input->post('purchase_amount', true),
            "cost_factor" => $this->input->post('cost_factor', true),
            "payment_date" => $this->input->post('payment_date', true),
            "created_date" => date("Y-m-d"),
            "created_dt" => date("Y-m-d H:i:s")
        ];
        $stockreciptid = "";
        if(!empty($master_data)){
            $query = $this->db->select('id')
            ->from('cberp_purchase_receipts')
            ->where('purchase_id', $purchase_id)
            ->where('bill_number', $this->input->post('bill_number', true))
            ->get();

            if ($query->num_rows() > 0) {
                $existing_row = $query->row_array();
                $this->db->where('id', $existing_row['id']);
                $this->db->update('cberp_purchase_receipts', $master_data);
                $stockreciptid = $existing_row['id'];
            } else {
                $this->db->insert('cberp_purchase_receipts', $master_data);
                $stockreciptid = $this->db->insert_id();
            }
            
        }
        echo "<pre>"; print_r($_POST);
    }
    // public function customerenquiryaction(){
    //     if ($this->input->server('REQUEST_METHOD') === 'POST') {
    //         $enquiry_data = array(
    //             'lead_number' => $this->input->post('lead_number'),
    //             'customer_type' => $this->input->post('customerType'),
    //             'customer_name' => $this->input->post('customer_name'),
    //             'customer_phone' => $this->input->post('customer_phone'),
    //             'customer_email' => $this->input->post('customer_email'),
    //             'customer_address' => $this->input->post('customer_address'),
    //             'date_received' => $this->input->post('date_received'),
    //             'due_date' => $this->input->post('due_date'),
    //             'source_of_enquiry' => $this->input->post('source_of_enquiry'),
    //             'assigned_to' => $this->input->post('assignedto'),
    //             // 'comments' => $this->input->post('comments'),
    //             'email_contents' => $this->input->post('email_contents'),
    //             'enquiry_status' => $this->input->post('enquiry_status'),
    //             'created_by' => $this->session->userdata('id'),
    //             'created_date' => date('Y-m-d'),
    //         );
    //         if(!empty($enquiry_data)){
    //             $this->db->insert('cberp_customer_leads', $enquiry_data);
    //             $enquiryid = $this->db->insert_id();
    //             $config['upload_path'] = FCPATH . 'uploads/';
    //             $config['allowed_types'] = 'pdf|jpg|jpeg|png|csv|xls|xlsx';
    //             $config['encrypt_name'] = TRUE;
    //             $this->load->library('upload', $config);
    //             if (isset($_FILES['upfile'])) {
    //                 $files = $_FILES['upfile'];
    //                 if(!empty($files))
    //                 {
    //                     $uploaded_data['lead_id'] = $enquiryid;
    //                     $uploaded_data['lead_number'] = $this->input->post('lead_number');
    //                     foreach ($files['name'] as $key => $filename) {
    //                         $_FILES['userfile']['name'] = $files['name'][$key];
    //                         $_FILES['userfile']['type'] = $files['type'][$key];
    //                         $_FILES['userfile']['tmp_name'] = $files['tmp_name'][$key];
    //                         $_FILES['userfile']['error'] = $files['error'][$key];
    //                         $_FILES['userfile']['size'] = $files['size'][$key];
    //                         $uploaded_data['actual_name'] = $files['name'][$key];
    //                         if ($this->upload->do_upload('userfile')) {
    //                             $uploaded_info = $this->upload->data();
    //                             $uploaded_data['file_name'] = $uploaded_info['file_name'];
    //                             $this->db->insert('cberp_customer_lead_attachments', $uploaded_data);
    //                         } else {
    //                             // Handle upload errors
    //                             $error = array('error' => $this->upload->display_errors());
    //                             // print_r($error); // You can handle errors as needed
    //                         }
    //                     }
    //                 }
    //             }
                
    //             $this->leads();

    //         }
            
    //     }
    // }
    // public function customerenquiryeditaction(){
    //     if ($this->input->server('REQUEST_METHOD') === 'POST') {
    //         $enquiry_data = array(
    //             'lead_number' => $this->input->post('lead_number'),
    //             'customer_type' => $this->input->post('customerType'),
    //             'customer_name' => $this->input->post('customer_name'),
    //             'customer_phone' => $this->input->post('customer_phone'),
    //             'customer_email' => $this->input->post('customer_email'),
    //             'customer_address' => $this->input->post('customer_address'),
    //             'date_received' => $this->input->post('date_received'),
    //             'due_date' => $this->input->post('due_date'),
    //             'source_of_enquiry' => $this->input->post('source_of_enquiry'),
    //             'assigned_to' => $this->input->post('assignedto'),
    //             // 'comments' => $this->input->post('comments'),
    //             'email_contents' => $this->input->post('email_contents'),
    //             'enquiry_status' => $this->input->post('enquiry_status'),
    //             'created_by' => $this->session->userdata('id'),
    //             'created_date' => date('Y-m-d'),
    //         );
    //         if(!empty($enquiry_data)){
    //             $lead_id = $this->input->post('lead_id');
    //             $this->db->where('lead_id', $lead_id);
    //             $this->db->update('cberp_customer_leads', $enquiry_data);

    //             $config['upload_path'] = FCPATH . 'uploads/';
    //             $config['allowed_types'] = 'pdf|jpg|jpeg|png|csv|xls|xlsx';
    //             $config['encrypt_name'] = TRUE;
    //             $this->load->library('upload', $config);
    //             $files = $_FILES['upfile']; // Get uploaded files array
    //             // echo "<pre>"; print_r($files); die();
    //             if(!empty($files))
    //             {
    //                 $uploaded_data['lead_id'] = $lead_id;
    //                 $uploaded_data['lead_number'] = $this->input->post('lead_number');
    //                 foreach ($files['name'] as $key => $filename) {
    //                     $_FILES['userfile']['name'] = $files['name'][$key];
    //                     $uploaded_data['actual_name'] = $files['name'][$key];
    //                     $_FILES['userfile']['type'] = $files['type'][$key];
    //                     $_FILES['userfile']['tmp_name'] = $files['tmp_name'][$key];
    //                     $_FILES['userfile']['error'] = $files['error'][$key];
    //                     $_FILES['userfile']['size'] = $files['size'][$key];

    //                     if ($this->upload->do_upload('userfile')) {
    //                         $uploaded_info = $this->upload->data();
    //                         $uploaded_data['file_name'] = $uploaded_info['file_name'];
    //                         $this->db->insert('cberp_customer_lead_attachments', $uploaded_data);
    //                     } else {
    //                         // Handle upload errors
    //                         $error = array('error' => $this->upload->display_errors());
    //                         // print_r($error); // You can handle errors as needed
    //                     }
    //                 }
    //             }
                
    //             $this->leads();

    //         }
            
    //     }
    // }



   


    public function ajax_list()
    {
        $list = $this->costingcalculation->get_datatables($this->limited);
        $data = array();
        $no = $this->input->post('start');
        
        foreach ($list as $invoices) {
            $no++;
            $approvstatus = "";
            $actionbtn = "";
            $validtoken = hash_hmac('ripemd160', 'p' . $invoice['iid'], $this->config->item('encryption_key'));
            $target_url = '<a href="' . base_url("Invoices/costing?id=$invoices->purchase_reciept_number&token=$validtoken") . '"  title="View" >&nbsp; ' . $invoices->purchase_reciept_number . '</a>';
            $payment_status = '<span class="st-'.strtolower($invoices->payment_status).'">'.$invoices->payment_status.'</span>';
            switch (true) {
               
                case ($invoices->note == "Dummy Purchase Reciept"):
                    $actionbtn = '';
                    $approvstatus = '<span class="st-approved">' . $this->lang->line('Approved') . '</span>';
                    $target_url=$invoices->purchase_reciept_number;
                    $payment_status='';
                    break;
                case ($invoices->reciept_status == "Pending" && $invoices->approvalflg == "0"):
                    $actionbtn = '<a href="' . base_url("Invoices/costing?id=$invoices->purchase_reciept_number&token=$validtoken") . '" title="Approve Now" class="btn btn-sm btn-secondary">Approve Now</a>';
                    $approvstatus = '<span class="st-pending">' . $this->lang->line('Waiting for approval') . '</span>';
                    break;

                case ($invoices->approvalflg == "1" && $invoices->reciept_status == "Assigned" && $invoices->assign_to == $this->session->userdata('id')):
                    $approvstatus = '<span class="st-approved">' . $this->lang->line('Approved') . '</span>';
                    $actionbtn = '<a href="' . base_url("Invoices/costing?id=$invoices->purchase_reciept_number&token=$validtoken") . '" title="Accept & Send" class="btn btn-sm btn-secondary">Accept Send</a>';
                    break;
               

                case ($invoices->approvalflg == "1" && $invoices->reciept_status != "Reverted" && $invoices->reciept_status != "Received"):
                    $approvstatus = '<span class="st-approved">' . $this->lang->line('Approved') . '</span>';
                    $actionbtn = '<a href="' . base_url("Invoices/costing?id=$invoices->purchase_reciept_number&token=$validtoken") . '" title="Approve Now" class="btn btn-sm btn-secondary"><i class="fa fa-edit"></i></a>';
                    break;
                case ($invoices->reciept_status == "Received"):
                    $approvstatus = '<span class="st-approved">' . $this->lang->line('Approved') . '</span>';
                    $actionbtn = '<a href="' . base_url("Invoices/costing?id=$invoices->purchase_reciept_number&token=$validtoken") . '" title="Approve Now" class="btn btn-sm btn-secondary"><i class="fa fa-edit"></i></a>&nbsp;<a href="' . base_url("purchasereturns/create?id=$invoices->purchase_reciept_number&token=$validtoken") . '" title="Purchase Return" class="btn btn-sm btn-secondary">Purchase Return</a>';
                    if($invoices->payment_status!='Paid')
                        {
                            $actionbtn .= '&nbsp;<a href="' . base_url("purchase/purchase_receipt_payment?id=$invoices->purchase_reciept_number&csd=$invoices->supplier_id") . '" title="Make Payment" class="btn btn-sm btn-secondary">'.$this->lang->line('Make Payment').'</a>';
                        }
                    break;
                    
                case ($invoices->reciept_status == "Reverted"):
                    $approvstatus = '<span class="st-approved">' . $this->lang->line('Approved') . '</span>';
                    $actionbtn = '<a href="' . base_url("Invoices/costing?id=$invoices->purchase_reciept_number&token=$validtoken") . '" title="Approve Now" class="btn btn-sm btn-secondary"><i class="fa fa-edit"></i></a>&nbsp;<a href="' . base_url("Invoices/costing?pid=$invoices->purchase_reciept_number") . '" title="Approve Now" class="btn btn-sm btn-secondary">Ready To Send</a>';
                    break;
            
                case ($invoices->reciept_status == "Draft"):
                    $approvstatus = '';
                    $actionbtn = '<a href="' . base_url("Invoices/costing?id=$invoices->purchase_reciept_number&token=$validtoken") . '" title="Approve Now" class="btn btn-sm btn-secondary"><i class="fa fa-edit"></i></a>';
                    break;
               
            
                default:
                    // Handle any other cases here if needed
                    break;
            }
            $row = array();
            $row[] = $no;
            $row[] = $target_url;
            // $row[] = '<a href="' . base_url("Invoices/costing?id=$invoices->purchase_reciept_number&token=$validtoken") . '"  title="View" >&nbsp; ' . $invoices->bill_number . '</a>';
            // $row[] = '<a href="' . base_url("invoices/view?id=$invoices->purchase_reciept_number") . '">&nbsp; ' . $invoices->bill_number . '</a>';
            $row[] = $invoices->salepoint_name;
            $row[] = $invoices->supplier_name;
            $row[] = number_format($invoices->purchase_amount,2);
            // $row[] = $invoices->damageclaim_ac;
            // $row[] = $invoices->cost_factor;
            $row[] = dateformat($invoices->created_date);
            $reciept_status = ($invoices->reciept_status=="Pending") ? "Created" : $invoices->reciept_status;
            $row[] = '<span class="st-'.strtolower($invoices->reciept_status).'">'.$reciept_status.'</span>';
            $row[] = $approvstatus;
            $row[] = $payment_status;
            $row[] =$actionbtn;
            $data[] = $row;
        }
        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->costingcalculation->count_all($this->limited),
            "recordsFiltered" => $this->costingcalculation->count_filtered($this->limited),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }
    

  

    // public function printinvoice()
    // {

    //     $tid = $this->input->get('id');
    //     $data['id'] = $tid;
    //     $data['invoice'] = $this->invocies->invoice_details($tid, $this->limited);
    //     if ($data['invoice']['id']) $data['products'] = $this->invocies->invoice_products($tid);
    //     if ($data['invoice']['id']) $data['employee'] = $this->invocies->employee($data['invoice']['eid']);
    //     if ($data['invoice']['i_class'] == 1) {
    //         $pref = prefix(7);
    //     } else {
    //         $pref = $this->config->item('prefix');
    //     }
    //     if (CUSTOM) $data['c_custom_fields'] = $this->custom->view_fields_data($data['invoice']['cid'], 1, 1);
    //     $data['general'] = array('title' => $this->lang->line('Invoice'), 'person' => $this->lang->line('Customer'), 'prefix' => $pref, 't_type' => 0);
    //     ini_set('memory_limit', '64M');
    //     if ($data['invoice']['taxstatus'] == 'cgst' || $data['invoice']['taxstatus'] == 'igst') {
    //         $html = $this->load->view('print_files/invoice-a4-gst_v' . INVV, $data, true);
    //     } else {
    //         $html = $this->load->view('print_files/invoice-a4_v' . INVV, $data, true);
    //     }
    //     //PDF Rendering
    //     $this->load->library('pdf');
    //     if (INVV == 1) {
    //         $header = $this->load->view('print_files/invoice-header_v' . INVV, $data, true);
    //         $pdf = $this->pdf->load_split(array('margin_top' => 40));
    //         $pdf->SetHTMLHeader($header);
    //     }
    //     if (INVV == 2) {
    //         $pdf = $this->pdf->load_split(array('margin_top' => 5));
    //     }
    //     $pdf->SetHTMLFooter('<div style="text-align: right;font-family: serif; font-size: 8pt; color: #5C5C5C; font-style: italic;margin-top:-6pt;">{PAGENO}/{nbpg} #' . $data['invoice']['tid'] . '</div>');
    //     $pdf->WriteHTML($html);
    //     $file_name = preg_replace('/[^A-Za-z0-9]+/', '-', 'Invoice__' . $data['invoice']['name'] . '_' . $data['invoice']['tid']);
    //     if ($this->input->get('d')) {
    //         $pdf->Output($file_name . '.pdf', 'D');
    //     } else {
    //         $pdf->Output($file_name . '.pdf', 'I');
    //     }
    // }

    //search warehouse
    public function warehousesearch()
	{
		$result = array();
		$out = array();
		$name = $this->input->post('keyword', true);
		$whr = '';		
		if ($name) {
			$query = $this->db->query("SELECT id,title FROM cberp_store WHERE $whr (UPPER(title)  LIKE '%" . strtoupper($name) . "%' ) LIMIT 6");
			$result = $query->result_array();
			echo '<ol>';
			$i = 1;
			foreach ($result as $row) {

				echo "<li onClick=\"selectedWarehouse('".$row['id']."','".$row['title']."')\"><p>".$row['title']."</p></li>";
				$i++;
			}
			echo '</ol>';
		}

	}

   //search supplier
   public function suppliersearch()
   {
       $result = array();
       $out = array();
       $name = $this->input->post('keyword', true);
       $whr = '';		
       if ($name) {
        $query = $this->db->query("SELECT id,name,phone,email FROM cberp_suppliers WHERE $whr (UPPER(name)  LIKE '%".strtoupper($name)."%' OR phone  LIKE '%".strtoupper($name)."%' OR UPPER(email)  LIKE '%".strtoupper($name)."%' ) LIMIT 6");
        $result = $query->result_array();
        
        echo '<ol>';
        $i = 1;
        foreach ($result as $row) {

            echo "<li onClick=\"selectedSupplier('".$row['id']."','".$row['name']."','".$row['phone']."')\"><p>".$row['name']." - ".$row['name']."</p></li>";
            $i++;
        }
        echo '</ol>';
    }

   }

   //search supplier
   public function currencysearch()
   {
    // ini_set('display_errors', 1);
    // ini_set('display_startup_errors', 1);
    // error_reporting(E_ALL);
       $result = array();
        $query = $this->db->query("SELECT `id`,`code`,`symbol`,`rate` FROM `cberp_currencies` order by `code` ASC ");
        $result = $query->result_array();
        $options="<option value=''>Select Currency</option>";
        foreach ($result as $row) {
            $options .= "<option value='".$row['id']."' data-rate='".$row['rate']."'>".$row['code']."</option>";
        }
        echo $options;
   }

   //search warehouse
   public function expensesearch()
   {
       $result = array();
       $out = array();
       $name = $this->input->post('keyword', true);
       $field_number = $this->input->post('field_number', true);
       $whr = ' status = "1" AND ';		
       if ($name) {
           $query = $this->db->query("SELECT id,expence_name FROM expenses WHERE $whr (UPPER(expence_name)  LIKE '%" . strtoupper($name) . "%' ) LIMIT 6");
           $result = $query->result_array();
           echo '<ol>';
           $i = 1;
           foreach ($result as $row) {

               echo "<li onClick=\"selectedExpense('".$row['id']."','".$row['expence_name']."','".$field_number."')\"><p>".$row['expence_name']."</p></li>";
               $i++;
           }
           echo '</ol>';
       }

   }
   //account search
   public function accountsearch()
   {
       $result = array();
       $out = array();
       $name = $this->input->post('keyword', true);
       $whr = ' account_type="Damage Claim" AND ';		
       if ($name) {
           $query = $this->db->query("SELECT id,holder,acn FROM cberp_accounts WHERE $whr (UPPER(acn)  LIKE '%".strtoupper($name)."%' OR  UPPER(holder)  LIKE '%".strtoupper($name)."%') LIMIT 6");
           $result = $query->result_array();
           echo '<ol>';
           $i = 1;
           foreach ($result as $row) {
               echo "<li onClick=\"selectedAccount('".$row['id']."','".$row['holder']."','".$row['acn']."')\"><p>".$row['holder']." - ".$row['acn']."</p></li>";
               $i++;
           }
           echo '</ol>';
       }

   }
}
