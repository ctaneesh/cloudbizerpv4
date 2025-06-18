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

class Bankingcategory extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('bankingcategory_model', 'bankingcategory');
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

        $data['permissions'] = load_permissions('Accounts','Banking','Banking Category','List');
        $head['title'] = "Banking Category";
        $head['usernm'] = $this->aauth->get_user()->username;        
        $data['accountheaders'] = $this->bankingcategory->load_banking_headers();
        $data['details'] = $this->bankingcategory->get_datatables();
        $this->load->view('fixed/header', $head);
        $this->load->view('banking/bankingcategorylist', $data);
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
        $accountdetails = $this->bankingcategory->load_category_by_id($this->input->post('category_id'));       
        echo json_encode(array('status' => 'Success', 'data' => $accountdetails));        
    }


}
