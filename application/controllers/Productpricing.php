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

class Productpricing extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('productpricing_model', 'productpricing');
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

        $head['title'] = "Product Pricing";
        $head['usernm'] = $this->aauth->get_user()->username;        
        $data['units'] = $this->productpricing->get_datatables();
        $this->load->view('fixed/header', $head);
        $this->load->view('productpricing/index', $data);
        $this->load->view('fixed/footer');
    }


    public function create()
    {
        if ($this->input->post()) {
            $price_perc = $this->input->post('price_perc', true);
            $selling_price_perc = $this->input->post('selling_price_perc', true);
            $whole_price_perc = $this->input->post('whole_price_perc', true);
            $web_price_perc = $this->input->post('web_price_perc', true);
            $this->productpricing->create($price_perc, $selling_price_perc, $whole_price_perc, $web_price_perc);
            
        } else {


            $head['title'] = "Add Product Pricing";
            $head['usernm'] = $this->aauth->get_user()->username;
            $this->load->view('fixed/header', $head);
            $this->load->view('productpricing/create');
            $this->load->view('fixed/footer');
        }
    }

    public function edit()
    {
        if ($this->input->post()) {
            $id = $this->input->post('id');
            $price_perc = $this->input->post('price_perc', true);
            $selling_price_perc = $this->input->post('selling_price_perc', true);
            $whole_price_perc = $this->input->post('whole_price_perc', true);
            $web_price_perc = $this->input->post('web_price_perc', true);
            $this->productpricing->edit($id, $price_perc, $selling_price_perc, $whole_price_perc, $web_price_perc);
        } else {


            $head['title'] = "Edit Product Pricing";
            $head['usernm'] = $this->aauth->get_user()->username;
            $data = $this->productpricing->view($this->input->get('id'));
            $this->load->view('fixed/header', $head);
            $this->load->view('productpricing/edit', $data);
            $this->load->view('fixed/footer');
        }
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
