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

class Enquiry extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('enquiry_model', 'enquiry');
        $this->load->model('customer_enquiry_model', 'customer_enquiry');
        // require_once('../application/libraries/Aauth.php');
        if (!is_login()) {
            redirect(base_url() . 'user/profile', 'refresh');
        }
        set_default_timezone_set();
        
        
    }


    //invoices list
    public function index()
    {
        $head['title'] = "Customer Enquiry";
        $this->load->view('includes/header', $head);
        $this->load->view('enquiry/enquiry');
        $this->load->view('includes/footer');
    }

    public function create()
    {
    //    ini_set('display_errors', 1);
    //    ini_set('display_startup_errors', 1);
    //    error_reporting(E_ALL);
  
        $data =[]; 
        $data['customer_sequence_number'] = "";
        $lead_id = intval($this->input->get('id'));
        $prifix72 = get_prefix_72();
        $data['prefix'] = $prifix72['lead_prefix'];  
        $validity = default_validity();
        $data['lead_validity'] = date('Y-m-d', strtotime(date('Y-m-d') . " +" . (int)$validity['lead_validity'] . " days"));
        $data['lead_id'] ="";
        if($lead_id)
        {
            $data['enquirymain'] = $this->customer_enquiry->enquiry_details($lead_id);            
            $data['images'] = $this->customer_enquiry->enquiry_details_table($lead_id);
            // print_r($data['images']); die();
            $enqID = $data['enquirymain']['lead_id'];
            $data['products'] = $this->customer_enquiry->enquiry_products($enqID);
            $data['customer_lead_number'] = $data['enquirymain']['customer_lead_number'];
            $data['lead_id'] = $lead_id;
        }
        else{
            $data['customer_lead_number'] = $this->customer_enquiry->customer_sequence_number($this->session->userdata('user_details')[0]->cid);
        }
        
        
        $general_enquiry_id = $this->enquiry->lead_number();
        $data['general_enquiry_number'] = $prifix72['lead_prefix'].$general_enquiry_id+1000;
        $head['title'] = "Customer Enquiry";
        $this->load->view('includes/header', $head);
        $this->load->view('enquiry/newenquiry',$data);
        $this->load->view('includes/footer');
    }
    public function action()
    {    
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL); 


        $prifix72 = get_prefix_72();
        $prefix = $prifix72['lead_prefix']; 
        $totalPrdts = count($this->input->post()['product_name']);
        $products = $this->input->post(); 
        // $this->db->trans_start();
        $transok = true;
            $customergeneral =[];
            if(!empty($products['product_name'][0]))
            {

                // $general_enquiry_id = $this->enquiry->lead_number();
                // $enquiryMain['lead_id'] = $general_enquiry_id;

                $customerdetails = $this->enquiry->loadcustomer_byid($_SESSION['user_details'][0]->cid);
                // $customergeneral['lead_number']= $this->input->post('lead_number');
                $customergeneral['customer_type']='existing';
                $customergeneral['customer_id']= $_SESSION['user_details'][0]->cid; 
                $customergeneral['date_received']=date('Y-m-d');
                $customergeneral['customer_lead_status']='Created';
                $customergeneral['created_date']=date('Y-m-d');
                $customergeneral['source_of_enquiry']= 'Direct';
                $customergeneral['customer_lead_number']=  $this->input->post('customer_lead_number');
                $customergeneral['due_date'] = $this->input->post('due_date'); 
                $customergeneral['customer_reference_number'] = $this->input->post('customer_reference_number'); 
                $customergeneral['customer_contact_person'] = $this->input->post('customer_contact_person'); 
                $customergeneral['customer_contact_number'] = $this->input->post('customer_contact_number'); 
                $customergeneral['user_type'] = 'Customer'; 
                $customergeneral['created_by'] = $this->session->userdata('user_details')[0]->cid; 
                $customergeneral['created_date'] = date('Y-m-d'); 
                $customergeneral['email_contents']=$this->input->post('enquiry_message');
               
                $totalamount = 0;
                if($this->db->insert('cberp_customer_leads', $customergeneral))
                {
                    
                    $last_insert_id = $this->db->insert_id();
                    for ($i = 0; $i < $totalPrdts; $i++) {
                        $productlist =[];     
                        $general_productlist =[]; 
                        if(!empty($products['product_name'][$i])){                            
                            $productlist['lead_id'] = $last_insert_id;
                            $productlist['product_qty'] = $products['product_qty'][$i];
                            $productlist['product_code'] = $products['product_code'][$i];
                            // find product details and insetr into lead_items
                            $subtotal = $this->enquiry->product_data($products['product_code'][$i],$products['product_qty'][$i],$last_insert_id);
                            $totalamount = $totalamount+$subtotal;
                            // if (!empty($productlist)) {
                            //     $this->db->insert('customer_enquiry_items', $productlist);
                            // }
                        }
                    }


                     // erp2024 10-07-2024 add items ends
                    //  define('FCPATH', dirname(__FILE__).DIRECTORY_SEPARATOR);

                    //FCPATH1 - set in index.php
                     $config['upload_path'] = FCPATH1 . 'uploads/';
                     $config['allowed_types'] = 'pdf|jpg|jpeg|png|csv|xls|xlsx';
                     $config['encrypt_name'] = TRUE;
                     $this->load->library('upload', $config);
                     if (isset($_FILES['upfile'])) {
                         $files = $_FILES['upfile'];
                         if(!empty($files))
                         {
                             $uploaded_data['lead_id'] = $last_insert_id;
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

                
                    $this->db->where('lead_id', $last_insert_id);
                    $this->db->update('cberp_customer_leads', ['total'=>$totalamount]);

                    //send meaasge to the employees                          
                  
                    // detailed_log_history('Lead',$last_insert_id,'Created By Customer', '');
                    // $this->db->insert('cberp_transaction_tracking',['lead_id'=>$last_insert_id,'lead_number'=>$this->input->post('lead_number')]);
                    if(empty($productlist)) {
                        echo json_encode(array('status' => 'Error', 'message' =>
                            "Please choose product."));
                        $transok = false;
                    }
                    else{
                        $target = base_url()."enquiry/";
                        echo json_encode(array('status' => 'Success', 'message' =>
                        $this->lang->line('Enquiry has  been created') . " <a href='$target' class='btn btn-secondary btn-sm'><span class='fa fa-eye' aria-hidden='true'></span> View </a> &nbsp; &nbsp;<a href='create' class='btn btn-secondary btn-sm'><span class='fa fa-plus-circle' aria-hidden='true'></span> " . $this->lang->line('Create Enquiry') . "  </a>"));
                    }
                }
                else{
                    echo json_encode(array('status' => 'Error', 'message' =>
                            "Please choose product."));
                        $transok = false;
                }
                if ($transok) {
                    $this->db->trans_complete();
                } else {
                    $this->db->trans_rollback();
                }
            }
            else{
                echo json_encode(array('status' => 'Error', 'message' =>
                        "Please choose product."));
                    $transok = false;
            }
            if ($transok) {
                $this->db->trans_complete();
            } else {
                $this->db->trans_rollback();
            }
    }

    public function draft_action()
    {
       
        //    ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL); 
        // $totalPrdts = ($this->input->post()['counter']) ? count($this->input->post()['counter']) : 0;
        $totalPrdts = $this->input->post()['counter'];
        $lead_id = $this->input->post('lead_id');
        $lead_id = $this->input->post('lead_id');
        $id = $this->input->post('prid');
        $products = $this->input->post();
        $productlist =[];     
        $general_productlist =[];

        // $query = $this->db->select_max('lead_id', 'last_id')->get('customer_enquiry');
        // $result = $query->row();
        // $lead_id = $result->last_id;
        // if(!empty($result) && $lead_id >0){
        //     $lead_id = $result->last_id;
        // }else{
        //     $lead_id = 1000;
        // }
        // $this->db->trans_start(); product_name
            $transok = true;
            $customerdetails = $this->enquiry->loadcustomer_byid($_SESSION['user_details'][0]->cid);
            $customergeneral['customer_type']='existing';
            $customergeneral['customer_id']= $_SESSION['user_details'][0]->cid; 
            $customergeneral['customer_lead_status']='Draft';
            $customergeneral['created_date']=date('Y-m-d');
            $customergeneral['source_of_enquiry']= 'Direct';
            $customergeneral['customer_lead_number']=  $this->input->post('customer_lead_number');
            $customergeneral['due_date'] = $this->input->post('due_date'); 
            $customergeneral['customer_reference_number'] = $this->input->post('customer_reference_number'); 
            $customergeneral['customer_contact_person'] = $this->input->post('customer_contact_person'); 
            $customergeneral['customer_contact_number'] = $this->input->post('customer_contact_number'); 
            $customergeneral['customer_contact_email'] = $this->input->post('customer_contact_email'); 
            $customergeneral['email_contents'] = $this->input->post('enquiry_message');  
            $customergeneral['user_type'] = 'Customer'; 
            $customergeneral['created_by'] = $this->session->userdata('user_details')[0]->cid; 
            $customergeneral['created_date'] = date('Y-m-d'); 
            if($lead_id > 0)
            {
                $this->db->where('lead_id', $lead_id);
                $this->db->update('cberp_customer_leads', $customergeneral);
            }
            else{
                $this->db->insert('cberp_customer_leads', $customergeneral);
                $lead_id = $this->db->insert_id();
            }
          
            $totalamount=0;
                $this->db->where('lead_id', $lead_id);
                $this->db->delete('cberp_customer_lead_items');
                for ($i = 0; $i <= $totalPrdts; $i++) {
                   
                    if(!empty($products['product_name'][$i])){  
                        $productlist['lead_id'] = $lead_id;
                        $productlist['product_qty'] = $products['product_qty'][$i];
                        $productlist['product_id'] = $products['pid'][$i];
                        // find product details

                        $subtotal = $this->enquiry->product_data($products['product_code'][$i],$products['product_qty'][$i],$lead_id);
                        $totalamount = $totalamount+$subtotal;
                            
                    }
                    
                }


                $this->db->where('lead_id', $lead_id);
                $this->db->update('cberp_customer_leads', ['total'=>$totalamount]);
                    //FCPATH1 - set in index.php
                    $config['upload_path'] = FCPATH1 . 'uploads/';
                    $config['allowed_types'] = 'pdf|jpg|jpeg|png|csv|xls|xlsx';
                    $config['encrypt_name'] = TRUE;
                    $this->load->library('upload', $config);
                    if (isset($_FILES['upfile'])) {
                        $files = $_FILES['upfile'];
                        if(!empty($files))
                        {
                            $uploaded_data['lead_id'] = $lead_id;
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
                                    // die($this->db->last_query());
                                } else {
                                    // Handle upload errors
                                    $error = array('error' => $this->upload->display_errors());
                                    // print_r($error); // You can handle errors as needed
                                }
                            }
                        }

                    }

                echo json_encode(array('status' => 'Success', 'data' =>$lead_id));
                
    }


    public function send_action()
    {
       
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL); 
        $totalPrdts = count($this->input->post()['product_name']);
        $lead_id = $this->input->post('lead_id');
        $lead_id = $this->input->post('lead_id');
        $id = $this->input->post('prid');
        $products = $this->input->post();
        $productlist =[];     
        $general_productlist =[];

        $lead_number = $this->enquiry->lead_number();
        $prifix72 = get_prefix_72();
            $transok = true;
         
            $customerdetails = $this->enquiry->loadcustomer_byid($_SESSION['user_details'][0]->cid);
            // $customergeneral['lead_number']= $this->input->post('lead_number');
            $customergeneral['customer_type']='existing';
            $customergeneral['customer_id']= $_SESSION['user_details'][0]->cid; 
            $customergeneral['enquiry_status']='Completed';
            $customergeneral['customer_lead_status']='Sent';
            $customergeneral['created_date']=date('Y-m-d');
            $customergeneral['source_of_enquiry']= 'Direct';
            $customergeneral['due_date'] = $this->input->post('due_date'); 
            $customergeneral['customer_reference_number'] = $this->input->post('customer_reference_number'); 
            $customergeneral['customer_contact_person'] = $this->input->post('customer_contact_person'); 
            $customergeneral['customer_contact_number'] = $this->input->post('customer_contact_number'); 
            $customergeneral['customer_contact_email'] = $this->input->post('customer_contact_email'); 
            $customergeneral['email_contents'] = $this->input->post('enquiry_message');  
            $customergeneral['user_type'] = 'Customer'; 
            $customergeneral['created_by'] = $this->session->userdata('user_details')[0]->cid; 
            $customergeneral['created_date'] = date('Y-m-d');             
            $customergeneral['lead_number'] = $prifix72['lead_prefix'].$lead_number;             
            $customergeneral['customer_lead_number']=  $this->input->post('customer_lead_number');
            if($lead_id > 0)
            {
                $this->db->where('lead_id', $lead_id);
                $this->db->update('cberp_customer_leads', $customergeneral);
            }
            else{
                $this->db->insert('cberp_customer_leads', $customergeneral);
                $lead_id = $this->db->insert_id();
            }
          
            $module_number = get_module_details_by_name('CRM');
            $users_list =  linked_user_module_approvals_by_module_number($module_number);
            $main_url = config_item('main_base_url');
            $target_url = $main_url."invoices/customer_leads?id=".$lead_id;        
            $enqnumber = $prifix72['lead_prefix'].$lead_number;
            $message = "Request For Quote(".$enqnumber.") Sent";            
            $message_caption = "Request For Quote(".$enqnumber.")";
            detailed_log_history('Lead',$lead_id,'Lead Created By Customer', '');
            $this->db->insert('cberp_transaction_tracking',['lead_id'=>$lead_id,'lead_number'=>$enqnumber]);
            send_message_to_users($users_list,$target_url,$message_caption,$message,$this->input->post('due_date'));

            $totalamount=0;
            if($totalPrdts>0){
                $this->db->where('lead_id', $lead_id);
                $this->db->delete('cberp_customer_lead_items');
                
                for ($i = 0; $i < $totalPrdts; $i++) {
                    if(!empty($products['product_name'][$i])){                            
                        $productlist['lead_id'] = $lead_id;
                        $productlist['product_qty'] = $products['product_qty'][$i];
                        // find product details
                        // $productdata = $this->enquiry->product_data($products['pid'][$i]);
                        $subtotal = $this->enquiry->product_data($products['product_code'][$i],$products['product_qty'][$i],$lead_id);
                        $totalamount = $totalamount+$subtotal;
                        // if (!empty($productlist)) {
                        //     $this->db->insert('customer_enquiry_items', $productlist);
                        // }
                    }
                }
                $this->db->where('lead_id', $lead_id);
                $this->db->update('cberp_customer_leads', ['total'=>$totalamount]);
                    //FCPATH1 - set in index.php
                    $config['upload_path'] = FCPATH1 . 'uploads/';
                    $config['allowed_types'] = 'pdf|jpg|jpeg|png|csv|xls|xlsx';
                    $config['encrypt_name'] = TRUE;
                    $this->load->library('upload', $config);
                    if (isset($_FILES['upfile'])) {
                        $files = $_FILES['upfile'];
                        if(!empty($files))
                        {
                            $uploaded_data['lead_id'] = $lead_id;
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
                                    // die($this->db->last_query());
                                } else {
                                    // Handle upload errors
                                    $error = array('error' => $this->upload->display_errors());
                                    // print_r($error); // You can handle errors as needed
                                }
                            }
                        }

                    }

                if(empty($productlist)) {
                    echo json_encode(array('status' => 'Error', 'message' =>
                        "Please choose product."));
                    $transok = false;
                }
                else{
                    
 
                    $target = base_url()."enquiry/";
                    echo json_encode(array('status' => 'Success', 'message' =>
                    $this->lang->line('Enquiry has  been updated') . " <a href='$target' class='btn btn-secondary btn-sm'><span class='fa fa-eye' aria-hidden='true'></span> View </a> &nbsp; &nbsp;<a href='create' class='btn btn-secondary btn-sm'><span class='fa fa-plus-circle' aria-hidden='true'></span> " . $this->lang->line('Create Enquiry') . "  </a>"));
                }
                
                // if ($transok) {
                //     $this->db->trans_complete();
                // } else {
                //     $this->db->trans_rollback();
                // }
            }
            else{
                echo json_encode(array('status' => 'Error', 'message' =>
                        "Please choose product."));
                    $transok = false;
            }
            // if ($transok) {
            //     $this->db->trans_complete();
            // } else {
            //     $this->db->trans_rollback();
            // }
    }

    public function ajax_list()
    {
        $list = $this->enquiry->get_datatables();
      
        $data = array();

        $no = $this->input->post('start');
        $prifix72 = get_prefix_72();
        $prefix = $prifix72['lead_prefix']; 

        foreach ($list as $enquiry) {
            $sequnce_number ="";
            $enquiry->lead_id = $enquiry->lead_id;
            if($enquiry->customer_lead_status=="Created" || $enquiry->customer_lead_status=="Open" || $enquiry->customer_lead_status=="Draft"){
                $mainurl = '<a href="' . base_url("enquiry/create?id=$enquiry->lead_id"). '">'.$enquiry->lead_number.'</a>';
                if($enquiry->customer_lead_number)
                {
                    $sequnce_number = '<a href="' . base_url("enquiry/create?id=$enquiry->lead_id"). '">'.$prefix.$enquiry->customer_lead_number.'</a>';
                }
                
            }
            else{
                if($enquiry->customer_lead_number)
                {
                    $sequnce_number = '<a href="' . base_url("enquiry/view?id=$enquiry->lead_id"). '">'.$prefix.$enquiry->customer_lead_number.'</a>';
                }
                $mainurl = '<a href="' . base_url("enquiry/view?id=$enquiry->lead_id") . '" >'.$enquiry->lead_number.'</a>';
            }
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $sequnce_number;
            $row[] = $mainurl;
            // $row[] = $enquiry->lead_id;
            // $row[] = $enquiry->enquiry_note;
            $row[] = $enquiry->email_contents;
            $row[] = (!empty($enquiry->created_date))?dateformat($enquiry->created_date):"";
            $row[] = (!empty($enquiry->created_date))?dateformat($enquiry->created_date):"";
            $status = ucfirst(strtolower($enquiry->customer_lead_status));
            $row[] = '<span class="st-' . strtolower($status) . '">' . ucwords($enquiry->customer_lead_status) . '</span>';
            if($enquiry->customer_lead_status=="Created" || $enquiry->customer_lead_status=="Open" || $enquiry->customer_lead_status=="Draft"){
                $row[] = '<a href="' . base_url("enquiry/create?id=$enquiry->lead_id"). '" class="btn btn-secondary btn-sm"><i class="icon-pencil"></i> </a>';
            }
            else{
                $row[] = '<a href="' . base_url("enquiry/view?id=$enquiry->lead_id") . '" class="btn btn-secondary btn-sm"><i class="icon-eye"></i></a>';
            }

            $data[] = $row;
        }
        // print_r($data); 
        // die();
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->enquiry->count_all(),
            "recordsFiltered" => $this->enquiry->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);

    }

    public function view()
    {
        
        $tid = $this->input->get('id');
        $data['id'] = $tid;
        $data['enqurymain'] = $this->customer_enquiry->enquiry_details($tid);
        // echo "<pre>"; print_r($data['enqurymain']); die();
        // if ($data['enqurymain']['customer_id'] == $this->session->userdata('user_details')[0]->cid) {
            $enqID = $data['enqurymain']['lead_id'];
            $head['title'] = "Enquiry $enqID";
            $data['products'] = $this->customer_enquiry->enquiry_products($tid);
            $this->load->view('includes/header', $head);
            $this->load->view('enquiry/view', $data);
            $this->load->view('includes/footer');
        // }

    }

    public function approve()
    {
        $tid = $this->input->get('id');
        $data['id'] = $tid;
        $head['title'] = "Quote $tid";
        $data['invoice'] = $this->enquiry->quote_details($tid);
        if ($data['invoice']['csd'] == $this->session->userdata('user_details')[0]->cid) {
            $this->enquiry->update_status($tid);
            $data['products'] = $this->enquiry->quote_products($tid);


            $data['employee'] = $this->enquiry->employee($data['invoice']['eid']);
            $m=array('message'=>'Approved!');
            $this->session->set_flashdata('item',$m);
            $this->session->keep_flashdata('item',$m);
            redirect(base_url('quote/view?id=' . $tid));


        }

    }
    public function edit()
    {
       
        $tid = intval($this->input->get('id'));
        $data['enquirymain'] = $this->enquiry->enquiry_details($tid);
        $enqID = $data['enquirymain']['lead_id'];
        $data['products'] = $this->enquiry->enquiry_products($enqID);
        $head['title'] = "Edit Quote #" . $enqID;
        $this->load->view('includes/header', $head);
        $this->load->view('enquiry/edit', $data);
        $this->load->view('includes/footer');
    }
    public function editaction()
    {
       
        
        $totalPrdts = count($this->input->post()['product_name']);
        $lead_id = $this->input->post('lead_id');
        $id = $this->input->post('prid');
        $products = $this->input->post();
        $productlist =[];     
        $general_productlist =[];
        // $this->db->trans_start();
        $transok = true;
           
            $customergeneral['email_contents'] = $this->input->post('enquiry_message');
            $customergeneral['due_date'] = $this->input->post('due_date'); 
            $customergeneral['customer_reference_number'] = $this->input->post('customer_reference_number'); 
            $customergeneral['customer_contact_person'] = $this->input->post('customer_contact_person'); 
            $customergeneral['customer_contact_number'] = $this->input->post('customer_contact_number'); 
            $customergeneral['customer_contact_email'] = $this->input->post('customer_contact_email'); 
            $customergeneral['customer_lead_status']='Created';

            $this->db->where('lead_id', $lead_id);
            $this->db->update('cberp_customer_leads', $customergeneral);
            $totalamount=0;
            if($totalPrdts>0){
             
                $this->db->where('lead_id', $lead_id);
                $this->db->delete('cberp_customer_lead_items');
                // die($this->db->last_query());

                for ($i = 0; $i <= $totalPrdts; $i++) {
                    if(!empty($products['product_name'][$i])){                            
                        $productlist['lead_id'] = $lead_id;
                        $productlist['product_qty'] = $products['product_qty'][$i];
                        $productlist['product_id'] = $products['pid'][$i];

                        // find product details
                        // $productdata = $this->enquiry->product_data($products['pid'][$i]);
                        $subtotal = $this->enquiry->product_data($products['product_code'][$i],$products['product_qty'][$i],$lead_id);
                        $totalamount = $totalamount+$subtotal;
                    }
                }
                $this->db->where('lead_id', $lead_id);
                $this->db->update('cberp_customer_leads', ['total'=>$totalamount]);
                    //FCPATH1 - set in index.php
                    $config['upload_path'] = FCPATH1 . 'uploads/';
                    $config['allowed_types'] = 'pdf|jpg|jpeg|png|csv|xls|xlsx';
                    $config['encrypt_name'] = TRUE;
                    $this->load->library('upload', $config);
                    if (isset($_FILES['upfile'])) {
                        $files = $_FILES['upfile'];
                        if(!empty($files))
                        {
                            $uploaded_data['lead_id'] = $lead_id;
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
                                    // die($this->db->last_query());
                                } else {
                                    // Handle upload errors
                                    $error = array('error' => $this->upload->display_errors());
                                    // print_r($error); // You can handle errors as needed
                                }
                            }
                        }

                    }

                if(empty($productlist)) {
                    echo json_encode(array('status' => 'Error', 'message' =>
                        "Please choose product."));
                    $transok = false;
                }
                else{
                    $target = base_url()."enquiry/";
                    echo json_encode(array('status' => 'Success', 'message' =>
                    $this->lang->line('Enquiry has  been updated') . " <a href='$target' class='btn btn-secondary btn-sm'><span class='fa fa-eye' aria-hidden='true'></span> View </a> &nbsp; &nbsp;<a href='create' class='btn btn-secondary btn-sm'><span class='fa fa-plus-circle' aria-hidden='true'></span> " . $this->lang->line('Create Enquiry') . "  </a>"));
                }
                
                // if ($transok) {
                //     $this->db->trans_complete();
                // } else {
                //     $this->db->trans_rollback();
                // }
            }
            else{
                echo json_encode(array('status' => 'Error', 'message' =>
                        "Please choose product."));
                    $transok = false;
            }
            // if ($transok) {
            //     $this->db->trans_complete();
            // } else {
            //     $this->db->trans_rollback();
            // }
    }

    public function search()
	{
		$result = array();
		$out = array();
		$row_num = $this->input->post('row_num', true);
		$name = $this->input->post('name_startsWith', true);
		$wid = $this->input->post('wid', true);
		$query = $this->db->query("SELECT cberp_product_description.product_name,cberp_products.product_code FROM cberp_products  
        JOIN cberp_product_description on cberp_product_description.product_code = cberp_products.product_code
        WHERE  (UPPER(cberp_product_description.product_name) LIKE '%" . strtoupper($name) . "%') OR (UPPER(cberp_products.product_code) LIKE '" . strtoupper($name) . "%') LIMIT 6");
        // print_r($query); die();
		$result = $query->result_array();

        foreach ($result as $row) {
            $name = array($row['product_name'], $row['product_code'],$row['product_code']);
            array_push($out, $name);
        }
        echo json_encode($out);

	}
     public function deletesubItem(){
        $lead_attachment_id = $this->input->post('selectedProducts');
        $name = $this->input->post('image');
        $this->db->where('lead_attachment_id', $lead_attachment_id);
        $this->db->delete('cberp_customer_lead_attachments');
        $main_url = config_item('main_base_url');
        unlink($main_url . 'uploads/' . $name);
        echo json_encode(array('status' => '1', 'message' => "Success"));
    }
   
}
