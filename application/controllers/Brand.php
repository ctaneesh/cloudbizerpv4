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

class Brand extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('categories_model', 'products_cat');
        $this->load->model('brand_model', 'brand');
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
        $data['permissions'] = load_permissions('Stock','Products','Brands','List');
        $data['cat'] = $this->brand->brand_list();
        $head['title'] = "Brands";
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->view('fixed/header', $head);
        $this->load->view('products/brands', $data);
        $this->load->view('fixed/footer');
    }


    public function add()
    {
        
        $head['title'] = "Add Product Category";
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->view('fixed/header', $head);
        $this->load->view('products/brand_add', $data);
        $this->load->view('fixed/footer');
    }
    public function addbrand_action()
    {
        $brand_name = $this->input->post('brand_name');
        $status = $this->input->post('status');
        $this->db->insert('cberp_brands',['brand_name'=>$brand_name, 'status'=>$status]);
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


    //view for edit
    public function edit()
    {
        $brandid = $this->input->get('id');
        $data['brand'] = $this->brand->brand_list_by_id($brandid);
        $head['title'] = "Edit Brand";
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->view('fixed/header', $head);
        $this->load->view('products/brand-edit', $data);
        $this->load->view('fixed/footer');

    }

    public function editwarehouse()
    {
        if ($this->input->post()) {
            $cid = $this->input->post('catid');
            $cat_name = $this->input->post('product_cat_name', true);
            $cat_desc = $this->input->post('product_cat_desc', true);
            $lid = $this->input->post('lid');

            if ($this->aauth->get_user()->loc) {
                if ($lid == 0 or $this->aauth->get_user()->loc == $lid) {

                } else {
                    exit();
                }
            }


            if ($cat_name) {

                $this->products_cat->editwarehouse($cid, $cat_name, $cat_desc, $lid);
            }
        } else {
            $catid = $this->input->get('id');
            $this->db->select('*');
            $this->db->from('cberp_store');
            $this->db->where('id', $catid);
            $query = $this->db->get();
            $data['warehouse'] = $query->row_array();
            $this->load->model('locations_model');
            $data['locations'] = $this->locations_model->locations_list2();
            $head['title'] = "Edit Product Warehouse";
            $head['usernm'] = $this->aauth->get_user()->username;
            $this->load->view('fixed/header', $head);
            $this->load->view('products/product-warehouse-edit', $data);
            $this->load->view('fixed/footer');
        }

    }



}
