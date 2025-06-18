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

defined('BASEPATH') OR exit('No direct script access allowed');

class Barcode_model extends CI_Model
{


    public function locations_list()
    {
        $query = $this->db->query("SELECT * FROM cberp_locations ORDER BY id DESC");
        return $query->result_array();
    }


    public function view($id)
    {

        $this->db->from('locations');
        $this->db->where('id', $id);

        $query = $this->db->get();
        $result = $query->row_array();
        return $result;


    }

    public function create($name, $address, $city, $region, $country, $postbox, $phone, $email, $tax_id, $image)
    {
        $data = array(
            'cname' => $name,
            'address' => $address,
            'city' => $city,
            'region' => $region,
            'country' => $country,
            'postbox' => $postbox,
            'phone' => $phone,
            'email' => $email,
            'tax_id' => $tax_id,
            'logo' => $image
        );

        if ($this->db->insert('locations', $data)) {
            echo json_encode(array('status' => 'Success', 'message' =>
                $this->lang->line('ADDED')));
        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
                $this->lang->line('ERROR')));
        }

    }

    public function edit($id, $name, $address, $city, $region, $country, $postbox, $phone, $email, $tax_id, $image)
    {
        $data = array(
            'cname' => $name,
            'address' => $address,
            'city' => $city,
            'region' => $region,
            'country' => $country,
            'postbox' => $postbox,
            'phone' => $phone,
            'email' => $email,
            'tax_id' => $tax_id,
            'logo' => $image
        );

        $this->db->set($data);
        $this->db->where('id', $id);

        if ($this->db->update('locations')) {
            echo json_encode(array('status' => 'Success', 'message' =>
                $this->lang->line('UPDATED')));
        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
                $this->lang->line('ERROR')));
        }

    }

    public function addwarehouse($cat_name, $cat_desc)
    {
        $data = array(
            'title' => $cat_name,
            'extra' => $cat_desc
        );

        if ($this->db->insert('cberp_store', $data)) {
            echo json_encode(array('status' => 'Success', 'message' =>
                $this->lang->line('ADDED')));
        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
                $this->lang->line('ERROR')));
        }

    }


    public function editwarehouse($catid, $product_cat_name, $product_cat_desc)
    {
        $data = array(
            'title' => $product_cat_name,
            'extra' => $product_cat_desc
        );


        $this->db->set($data);
        $this->db->where('id', $catid);

        if ($this->db->update('cberp_store')) {
            echo json_encode(array('status' => 'Success', 'message' =>
                $this->lang->line('UPDATED')));
        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
                $this->lang->line('ERROR')));
        }

    }


}
