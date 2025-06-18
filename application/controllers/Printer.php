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

class Printer extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('printer_model', 'printer');
        $this->load->library("Aauth");
        if (!$this->aauth->is_loggedin()) {
            redirect('/user/', 'refresh');
        }
        if ($this->aauth->get_user()->roleid < 5) {

            exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');

        }
    }

    public function index()
    {
        $data['printers'] = $this->printer->printers_list();
        $head['title'] = "Printers";
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->view('fixed/header', $head);
        $this->load->view('printers/index', $data);
        $this->load->view('fixed/footer');
    }

    public function add()
    {
        $this->load->model('locations_model');
        if ($this->input->post()) {
            $p_name = $this->input->post('p_name', true);
            $p_type = $this->input->post('p_type', true);
            $p_connect = $this->input->post('p_connect');
            $p_mode = $this->input->post('pmode');
            $lid = $this->input->post('lid');

            $this->printer->create($p_name, $p_type, $p_connect, $lid, $p_mode);
        } else {

            $data['printers'] = $this->printer->printers_list();
            $data['locations'] = $this->locations_model->locations_list();
            $head['title'] = "Printers";
            $head['usernm'] = $this->aauth->get_user()->username;
            $this->load->view('fixed/header', $head);
            $this->load->view('printers/add', $data);
            $this->load->view('fixed/footer');
        }

    }

    public function view()
    {
        $id = $this->input->get('id');
        $data['printer'] = $this->printer->printer_details($id);
        $head['title'] = "View Printer";
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->view('fixed/header', $head);
        $this->load->view('printers/view', $data);
        $this->load->view('fixed/footer');
    }

    public function edit()
    {
        $id = $this->input->get('id');
        $data['printer'] = $this->printer->printer_details($id);
        $this->load->model('locations_model');
        if ($this->input->post()) {
            $p_name = $this->input->post('p_name', true);
            $p_type = $this->input->post('p_type');
            $p_connect = $this->input->post('p_connect');
            $lid = $this->input->post('lid');
            $id = $this->input->post('p_id');
            $p_mode = $this->input->post('pmode');

            $this->printer->edit($id, $p_name, $p_type, $p_connect, $lid, $p_mode);
        } else {

            $data['printers'] = $this->printer->printers_list();
            $data['locations'] = $this->locations_model->locations_list();
            $head['title'] = "Printers";
            $head['usernm'] = $this->aauth->get_user()->username;
            $this->load->view('fixed/header', $head);
            $this->load->view('printers/edit', $data);
            $this->load->view('fixed/footer');
        }

    }


    public function delete_i()
    {
        $id = $this->input->post('deleteid');
        if ($id) {
            $this->db->delete('cberp_config', array('id' => $id, 'type' => 1));
            echo json_encode(array('status' => 'Success', 'message' => 'Printer Removed'));
        } else {
            echo json_encode(array('status' => 'Error', 'message' => $this->lang->line('ERROR')));
        }
    }

    public function pre_print_settings()
    {
        if ($this->input->post()) {
            $print_setting_number = $this->input->post('print_setting_number', true);
           $data = [
            'measurement_unit' => $this->input->post('measurement_unit'),
            'header_height' => $this->input->post('header_height'),
            'footer_height' => $this->input->post('footer_height'),
            'margin_left' => $this->input->post('margin_left'),
            'page_height' => $this->input->post('page_height'),
            'page_width' => $this->input->post('page_width'),
            'items_per_page' => $this->input->post('items_per_page'),
            'row_height' => $this->input->post('row_height'),
            'bill_details' => $this->input->post('bill_details'),
            'bill_details_height' => $this->input->post('bill_details_height'),
            'display_item_labels' => $this->input->post('display_item_labels'),
           ];

            $this->db->update('cberp_print_settings',$data,['print_setting_number'=>$print_setting_number]);
            $response = array(
                'success' => true,
                'message' => 'Saved successfully'
            );
            echo json_encode($response);
        } 
        else {
            $data['printer'] = $this->printer->print_settings_list();
            $head['title'] = "Pre Print Settings";
            $head['usernm'] = $this->aauth->get_user()->username;
            $this->load->view('fixed/header', $head);
            $this->load->view('printers/pre-print-settings', $data);
            $this->load->view('fixed/footer');
        }
    }
    public function default_invoice_print()
    {
        if ($this->input->post()) {
            $data = [
                'name' => $this->input->post('printer_type')
            ];

            $this->db->update('univarsal_api',$data,['id'=>73]);
            $response = array(
                'success' => true,
                'message' => 'Saved successfully'
            );
            echo json_encode($response);
        } 
        else {
            // $data['printer'] = $this->printer->print_settings_list();
            $default_print =  get_prefix_73();
            $data['default_print'] = $default_print['default_invoice_print'];
            // print_r($data['default_print']);
            // die();
            $head['title'] = "Default Invoice Print";
            $head['usernm'] = $this->aauth->get_user()->username;
            $this->load->view('fixed/header', $head);
            $this->load->view('printers/default_invoice_print', $data);
            $this->load->view('fixed/footer');
        }
    }

}
