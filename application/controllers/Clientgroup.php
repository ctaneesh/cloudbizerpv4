<?php
/**
 * Cloud Biz Erp  Accounting,  Invoicing  and CRM Software
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

class Clientgroup extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('clientgroup_model', 'clientgroup');
        $this->load->model('customers_model', 'customers');
        $this->load->library("Aauth");
        if (!$this->aauth->is_loggedin()) {
            redirect('/user/', 'refresh');
        }
        // if (!$this->aauth->premission(3)) {

        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');

        // }
        $this->li_a = 'crm';
    }

    //groups
    public function index()
    {       
        $data['group'] = $this->customers->group_list();
        $head['usernm'] = $this->aauth->get_user()->username;
        $head['title'] = 'Client Groups';
        $data['permissions'] = load_permissions('CRM','Customers','Customer Groups');
        $this->load->view('fixed/header', $head);
        $this->load->view('groups/groups', $data);
        $this->load->view('fixed/footer');
    }

    //view
    public function groupview()
    {
        $head['usernm'] = $this->aauth->get_user()->username;
        $id = $this->input->get('id');
        $data['group'] = $this->clientgroup->details($id);
        $head['title'] = 'Group View';
        $this->load->view('fixed/header', $head);
        $this->load->view('groups/groupview', $data);
        $this->load->view('fixed/footer');
    }

    //datatable
    public function grouplist()

    {

        $id = $this->input->get('id');
        $list = $this->customers->get_datatables($id);
        $data = array();
        $no = $this->input->post('start');
        foreach ($list as $customers) {
            $no++;

            $row = array();
            $row[] = '<div class="text-center">'.$no.'</div>';
            $row[] = '<div class="text-center"><img class="small-img"  src="' . base_url() . 'userfiles/customers/thumbnail/' . $customers->picture . '" ></div>';
            $row[] = '<a href="' . base_url() . 'customers/view?id=' . $customers->id . '">' . $customers->name . '</a>';
            $row[] = $customers->address . ',' . $customers->city . ',' . $customers->country;
            $row[] = $customers->email;
            $row[] = $customers->phone;
            $row[] = ' <a href="' . base_url() . 'customers/create?id=' . $customers->id . '" class="btn btn-success btn-sm" title="Edit"><span class="icon-pencil"></span></a> <a href="#" data-object-id="' . $customers->id . '" class="btn btn-danger btn-sm delete-object" title="Delete"><span class="fa fa-trash"></span></a>';
            // $row[] = '<a href="' . base_url() . 'customers/view?id=' . $customers->id . '" class="btn btn-info btn-sm" title="View"><span class="fa fa-eye"></span></a> <a href="' . base_url() . 'customers/create?id=' . $customers->id . '" class="btn btn-success btn-sm" title="Edit"><span class="icon-pencil"></span></a> <a href="#" data-object-id="' . $customers->id . '" class="btn btn-danger btn-sm delete-object" title="Delete"><span class="fa fa-trash"></span></a>';
            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->customers->count_all($id),
            "recordsFiltered" => $this->customers->count_filtered($id),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function create()
    {
        $head['usernm'] = $this->aauth->get_user()->username;
        $head['title'] = 'Create Group';
        $this->load->view('fixed/header', $head);
        $this->load->view('groups/add');
        $this->load->view('fixed/footer');
    }

    public function add()
    {
        $group_name = $this->input->post('group_name', true);
        $group_desc = $this->input->post('group_desc', true);

        if ($group_name) {
            $this->clientgroup->add($group_name, $group_desc);
        }
    }

    public function editgroup()
    {
        $gid = $this->input->get('id');
        $this->db->select('*');
        $this->db->from('cberp_cust_group');
        $this->db->where('id', $gid);
        $query = $this->db->get();
        $data['group'] = $query->row_array();
        $head['usernm'] = $this->aauth->get_user()->username;
        $head['title'] = 'Edit Group';
        $this->load->view('fixed/header', $head);
        $this->load->view('groups/groupedit', $data);
        $this->load->view('fixed/footer');

    }

    public function editgroupupdate()
    {
        $gid = $this->input->post('gid', true);
        $group_name = $this->input->post('group_name', true);
        $group_desc = $this->input->post('group_desc', true);
        if ($gid) {
            $this->clientgroup->editgroupupdate($gid, $group_name, $group_desc);
        }
    }

    public function delete_i()
    {
       // if ($this->aauth->premission(11)) {
            $id = $this->input->post('deleteid');
            if ($id != 1) {
                $this->db->delete('cberp_cust_group', array('id' => $id));
                $this->db->set(array('gid' => 1));
                $this->db->where('gid', $id);
                $this->db->update('cberp_customers');
                echo json_encode(array('status' => 'Success', 'message' => $this->lang->line('DELETED')));
            } else if ($id == 1) {
                echo json_encode(array('status' => 'Error', 'message' => 'You can not delete the default group!'));
            } else {
                echo json_encode(array('status' => 'Error', 'message' => $this->lang->line('ERROR')));
            }
        // } else {
        //     echo json_encode(array('status' => 'Error', 'message' =>
        //         $this->lang->line('ERROR')));
        // }
    }

    function sendGroup()
    {
        $id = $this->input->post('gid');
        $subject = $this->input->post('subject', true);
        $message = $this->input->post('text');
        $attachmenttrue = false;
        $attachment = '';
        $recipients = $this->clientgroup->recipients($id);
        $this->load->model('communication_model');
        $this->communication_model->group_email($recipients, $subject, $message, $attachmenttrue, $attachment);
    }

    public function discount_update()
    {
        $gid = $this->input->post('gid', true);
        $disc_rate = (float)$this->input->post('disc_rate', true);

        if ($gid) {
            $this->clientgroup->editgroupdiscountupdate($gid, $disc_rate);
        }
    }
}
