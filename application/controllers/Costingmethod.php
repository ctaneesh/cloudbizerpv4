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

class Costingmethod extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('costingmethod_model', 'costingmethod');
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

        $head['title'] = "Costing Method";
        $head['usernm'] = $this->aauth->get_user()->username;        
        $data['costing'] = $this->costingmethod->get_datatables();
        $this->load->view('fixed/header', $head);
        $this->load->view('costingmethod/index', $data);
        $this->load->view('fixed/footer');
    }


    public function create()
    {
        $head['title'] = "Create Costing Method";
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->view('fixed/header', $head);
        $this->load->view('costingmethod/create');
        $this->load->view('fixed/footer');
    }
    public function action()
    {
        if ($this->input->post()) {
            $cberp_costing_method = $this->input->post('cberp_costing_method', true);
            $created_by   = $this->session->userdata('id');
            if($this->input->post('method_id', true))
            {
                $this->db->update('cberp_costing_method',['picked_item'=>'0']);

                $this->db->update('cberp_costing_method',['costing_method'=>$cberp_costing_method, 'created_by'=> $created_by, 'created_dt'=>date('Y-m-d H:i:s'),'picked_item'=>'1'],['costing_method'=>$cberp_costing_method]);
            }
            else{
                $this->db->insert('cberp_costing_method',['costing_method'=>$cberp_costing_method, 'created_by'=> $created_by, 'created_dt'=>date('Y-m-d H:i:s')]);
            }
            
        }
        echo json_encode(array('status' => 'success'));
    }

    public function edit()
    {
        $head['title'] = "Edit Product Pricing";
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['costing'] = $this->costingmethod->view($this->input->get('id'));
        // print_r($data['costing']); die();
        $this->load->view('fixed/header', $head);
        $this->load->view('costingmethod/edit', $data);
        $this->load->view('fixed/footer');
    }


    public function delete_i()
    {
        $id = $this->input->post('deleteid');
        if ($id) {

            $this->db->delete('cberp_units', array('id' => $id));


            echo json_encode(array('status' => 'Success', 'message' => $this->lang->line('DELETED')));
        } else {
            echo json_encode(array('status' => 'Error', 'message' => $this->lang->line('ERROR')));
        }
    }

    //variations
    public function variations()
    {

        $head['title'] = "Variations Units";
        $data['units'] = $this->productpricing->variations_list();
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->view('fixed/header', $head);
        $this->load->view('units/variations', $data);
        $this->load->view('fixed/footer');
    }

    public function create_va()
    {
        if ($this->input->post()) {
            $name = $this->input->post('name', true);


            $this->productpricing->create_va($name, 1);
        } else {


            $head['title'] = "Add variation";
            $head['usernm'] = $this->aauth->get_user()->username;
            $this->load->view('fixed/header', $head);
            $this->load->view('units/create_va');
            $this->load->view('fixed/footer');
        }
    }

    public function edit_va()
    {
        if ($this->input->post()) {
            $id = $this->input->post('id');
            $name = $this->input->post('name', true);

            $this->productpricing->edit_va($id, $name);
        } else {


            $head['title'] = "Edit variation";
            $head['usernm'] = $this->aauth->get_user()->username;
            $data = $this->productpricing->view($this->input->get('id'));
            $this->load->view('fixed/header', $head);
            $this->load->view('units/edit_va', $data);
            $this->load->view('fixed/footer');
        }
    }


    public function delete_va_i()
    {
        $id = $this->input->post('deleteid');
        if ($id) {

            $this->db->delete('cberp_units', array('id' => $id));


            echo json_encode(array('status' => 'Success', 'message' => $this->lang->line('DELETED')));
        } else {
            echo json_encode(array('status' => 'Error', 'message' => $this->lang->line('ERROR')));
        }
    }

    //varriables
    public function variables()
    {

        $head['title'] = "Variations variables";
        $data['units'] = $this->productpricing->variables_list();
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->view('fixed/header', $head);
        $this->load->view('units/variables', $data);
        $this->load->view('fixed/footer');
    }

    public function create_vb()
    {
        if ($this->input->post()) {
            $name = $this->input->post('name', true);
            $var_id = $this->input->post('pvars');


            $this->productpricing->create_vb($name, $var_id);
        } else {


            $head['title'] = "Add variation variable";
            $head['usernm'] = $this->aauth->get_user()->username;
            $data['variations'] = $this->productpricing->variations_list();
            $this->load->view('fixed/header', $head);
            $this->load->view('units/create_vb', $data);
            $this->load->view('fixed/footer');
        }
    }

    public function edit_vb()
    {
        if ($this->input->post()) {
            $id = $this->input->post('id');
            $name = $this->input->post('name', true);
            $var_id = $this->input->post('var_id');

            $this->productpricing->edit_vb($id, $name, $var_id);
        } else {


            $head['title'] = "Edit variation variable";
            $head['usernm'] = $this->aauth->get_user()->username;
            $data = $this->productpricing->view($this->input->get('id'));
            $this->load->view('fixed/header', $head);
            $this->load->view('units/edit_va', $data);
            $this->load->view('fixed/footer');
        }
    }


    public function delete_vb_i()
    {
        $id = $this->input->post('deleteid');
        if ($id) {

            $this->db->delete('cberp_units', array('id' => $id));


            echo json_encode(array('status' => 'Success', 'message' => $this->lang->line('DELETED')));
        } else {
            echo json_encode(array('status' => 'Error', 'message' => $this->lang->line('ERROR')));
        }
    }


}
