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

class Authorization_approval extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('authorizationapproval_model', 'authorization_approval');
        $this->load->library("Aauth");
        $this->load->library('session');
        if (!$this->aauth->is_loggedin()) {
            redirect('/user/', 'refresh');
        }
        // if (!$this->aauth->premission(1)) {
        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        // }
        $this->li_a = 'sales';
        $this->session->unset_userdata('orderid');   
    }

    
    public function index()
    {
        $data['permissions'] = load_permissions('Sales','Authorization Requests','Manage Authorization Requests');
        $head['title'] = "Request Approval";
        $data['eid'] = intval($this->input->get('eid'));
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->model('invoices_model');
        $condition = " JOIN `cberp_employees` ON `cberp_employees`.`id` = `authorization_history`.`requested_by` ";
        $condition .= " WHERE `cberp_employees`.`id` = ".$this->session->userdata('id');
        $condition .= " OR `cberp_employees`.`reportingto` = ".$this->session->userdata('id');
        $data['counts'] = $this->invoices_model->get_dynamic_count('authorization_history','requested_date','requested_amount',$condition);
        $this->load->view('fixed/header', $head);
        $this->load->view('quotes/authorization_list', $data);
        $this->load->view('fixed/footer');
    }

  

    public function ajax_list()
    {
       
        $eid = 0;
        $list = $this->authorization_approval->get_datatables($eid);
        $data = array();
        $no = $this->input->post('start');
        foreach ($list as $invoices) {
            $fl = 0;
            if(!empty($invoices->authorized_by) && $invoices->status=='Approve'){
                $fl = 1;
                $status = '<span class="st-active">' . $this->lang->line(ucwords("Approved")) . '</span>';
            }
            else if(!empty($invoices->authorized_by) && $invoices->status=='Hold'){
                $fl = 0;
                $status = '<span class="st-pending">' . $this->lang->line(ucwords("Hold")) . '</span>';
            }
            else if(!empty($invoices->authorized_by) && $invoices->status=='Reject'){
                $fl = 0;
                $status = '<span class="st-Closed">' . $this->lang->line(ucwords("Rejected")) . '</span>';
            }
            else{
                $status = '<span class="st-due">' . ucwords("Waiting for approval") . '</span>';
            }
            $no++;
            $row = array();

            

            if($invoices->function_type=="Quote"){                
                $row[] = ' QT #' . ($invoices->function_id+1000);  
                $targeturl = '<a href="' . base_url("quote/quote_approval?id=$invoices->function_id") . '" class="btn btn-sm btn-secondary"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i> Approve Now</a>';              
            }
            else if($invoices->function_type=="Purchase Order"){ 
                $row[] = ' PO #' . ($invoices->function_id+1000); 
                $targeturl = '<a href="' . base_url("purchase/purchase_approval?id=$invoices->function_id") . '" class="btn btn-sm btn-secondary"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i> Approve Now</a>';
            }
            else{
                $prefix = "OTHER";
                $row[] = ' SR #' . ($invoices->function_id+1000);
                $row[] = '<a href="' . base_url("quote/quote_approval?id=$invoices->function_id") . '">&nbsp; ' . $invoices->function_id . '</a>';
                
            }
            if($invoices->requested_by == $invoices->authorized_by){
                $accepetdby = $invoices->Requester;
            }
            else{
                $accepetdby = $invoices->Accepter;
            }
            $row[] = $invoices->function_type;
            $row[] = $invoices->Requester;
            $row[] = $invoices->requested_amount;
            $row[] = $accepetdby;
            $row[] = $invoices->authorized_amount;
            $row[] = dateformat($invoices->requested_date);
            $row[] = $status;
            if(($this->session->userdata('id') == $invoices->requestedid))
            {
                // $row[] ="";
            }
            else{
                if($fl!=1)
                {
                    // $row[] = $targeturl;
                    // $row[] = '';
                }
                else{
                    // $row[] = '';
                    // $row[] = '<button class="btn btn-sm btn-secondary" disabled><i class="fa fa-thumbs-o-up" aria-hidden="true"></i> Already Approved</button>';
                }
            }
            
            //  $row[] = '<a href="' . base_url("quote/view?id=$invoices->id") . '" class="btn btn-secondary btn-sm" target="_blank" title="View"><i class="fa fa-eye"></i></a> <a href="' . base_url("billing/printquote?id=$invoices->id") . '&token=1" class="btn btn-secondary btn-sm"  title="Print" target="_blank"><span class="fa fa-print"></span></a> <a href="#" data-object-id="' . $invoices->id . '" class="btn btn-secondary btn-sm delete-object" title="Delete"><span class="fa fa-trash"></span></a>';
            // $row[] = $targeturl;
            $row[] ='';
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->authorization_approval->count_all($eid),
            "recordsFiltered" => $this->authorization_approval->count_filtered($eid),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);

    }

    public function view()
    {
        $this->load->model('accounts_model');
        $data['acclist'] = $this->accounts_model->accountslist();
        $tid = intval($this->input->get('id'));
        $data['id'] = $tid;
        $data['invoice'] = $this->authorization_approval->quote_details($tid);
        $data['products'] = $this->authorization_approval->quote_products($tid);
        $data['approvedby'] = $this->authorization_approval->approved_person($tid,"Quote");
        $data['trackingdata'] = $this->authorization_approval->tracking_details('quote_id',$tid);
        $data['attach'] = $this->authorization_approval->attach($tid);
        $data['employee'] = $this->authorization_approval->employee($data['invoice']['eid']);
        $head['title'] = "Quote #" . $data['invoice']['tid'];
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->view('fixed/header', $head);
        if ($data['invoice']) $this->load->view('quotes/view', $data);
        $this->load->view('fixed/footer');
    }


}
