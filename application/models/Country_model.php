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

class Country_model extends CI_Model
{
    var $table = 'cberp_country';
    var $column_order = array(null, 'cberp_country.name', 'cberp_country.code', null); //set column field database for datatable orderable
    var $column_search = array('cberp_country.name', 'cberp_country.product_code'); //set column field database for datatable searchable
    var $order = array('cberp_country.id' => 'desc'); // default order

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    public function country_list()
    {
        $this->db->select('*');
        $this->db->from('cberp_country');
        $this->db->where('status','1');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function warehouse_list()
    {
        $this->db->select('*');
        $this->db->from('cberp_store');
        $this->db->order_by('store_name', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
}