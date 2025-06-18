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

class Manufacturers extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('categories_model', 'products_cat');
        $this->load->model('manufacturer_model', 'manufacturer');
        $this->load->library("Aauth");
        if (!$this->aauth->is_loggedin()) {
            redirect('/user/', 'refresh');
        }
        // if (!$this->aauth->premission(2)) {
        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        // }
        $this->li_a = 'stock';
    }

    public function index()
    {
        // $data['cat'] = $this->manufacturer->brand_list();
        $data['permissions'] = load_permissions('Stock','Products','Manufacturers','List');
        $head['title'] = "Manufacturers";
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->view('fixed/header', $head);
        $this->load->view('products/manufacturers', $data);
        $this->load->view('fixed/footer');
    }


    public function add()
    {
        $mfgid = $this->input->get('id');
        $data['details']=[];
        if($mfgid)
        {
            $data['details'] = $this->manufacturer->details_by_id($mfgid);
        }
        $data['permissions'] = load_permissions('Stock','Products','Manufacturers','Add');
        $head['title'] = "Manufacturer";
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->view('fixed/header', $head);
        $this->load->view('products/manufacturer-add', $data);
        $this->load->view('fixed/footer');
    }
    public function action()
    {
        $data = [
            'manufacturer_name' => $this->input->post('manufacturer_name'),
            'mfg_code' => $this->input->post('mfg_code'),
            'mfg_email' => $this->input->post('mfg_email'),
            'mfg_email2' => $this->input->post('mfg_email2'),
            'mfg_phone1' => $this->input->post('mfg_phone1'),
            'mfg_phone2' => $this->input->post('mfg_phone2'),
            'mfg_country' => $this->input->post('mfg_country'),
            'mfg_region' => $this->input->post('mfg_region'),
            'mfg_city' => $this->input->post('mfg_city'),
            'mfg_postbox' => $this->input->post('mfg_postbox'),
            'mfg_address' => $this->input->post('mfg_address')
        ];
        if($this->input->post('manufacturer_id'))
        {
            $this->db->update('cberp_manufacturer_ai',$data,['manufacturer_id'=>$this->input->post('manufacturer_id')]); 
        }
        else{
            $this->db->insert('cberp_manufacturer_ai',$data);
        }
        
        echo json_encode(array('status' => 'Success'));
    }
    
    public function editbrand_action()
    {
        $brand_name = $this->input->post('brand_name');
        $status = $this->input->post('status');
        $id = $this->input->post('brand_id');
        $this->db->update('cberp_brands',['brand_name'=>$brand_name, 'status'=>$status],['id'=>$id]);
        echo json_encode(array('status' => 'Success'));
    }

    public function ajax_list()
    {

        $list = $this->manufacturer->get_datatables();
        $data = array();
        $no = $this->input->post('start');
        foreach ($list as $prd) {
            $no++;
            $row = array();
            $row[] = $no;
            $cid = $prd->manufacturer_id;
            $row[] = $prd->manufacturer_name; 
            $row[] = $prd->mfg_code; 
            $row[] = $prd->mfg_email; 
            $row[] = $prd->mfg_phone1; 
            $row[] = $prd->mfg_phone2; 
            $row[] = '<a href="' . base_url() . 'manufacturers/add?id=' . $cid . '"  title="Edit" class="btn btn-sm btn-secondary"><i class="fa fa-pencil"></i> </a>';
            $data[] = $row;
        }
        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->manufacturer->count_all(),
            "recordsFiltered" => $this->manufacturer->count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
    }


}
