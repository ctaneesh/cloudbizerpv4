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

class Manufacturer_model extends CI_Model
{

    var $table = 'cberp_manufacturer_ai';
    var $column_order = array(null, 'manufacturer_name','manufacturer_name', 'mfg_code','mfg_email','mfg_phone1','mfg_phone2', null); 
    //set column field database for datatable orderable
    var $column_search = array('manufacturer_name','manufacturer_name', 'mfg_code','mfg_email','mfg_phone1','mfg_phone2'); //set column field database for datatable searchable
    var $order = array('cberp_manufacturer_ai.manufacturer_id' => 'desc'); 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    
    private function _get_datatables_query()
    {
        $this->db->select('manufacturer_id,manufacturer_name,mfg_code,mfg_email,mfg_phone1,mfg_phone2');
        $this->db->from($this->table);
        $i = 0;
        foreach ($this->column_search as $item) // loop column
        {
            if ($this->input->post('search')['value']) // if datatable send POST for search
            {

                if ($i === 0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $this->input->post('search')['value']);
                } else {
                    $this->db->or_like($item, $this->input->post('search')['value']);
                }

                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables()
    {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        // die($this->db->last_query());
        return $query->result();
    }

    function count_filtered()
    {
       
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function brand_list()
    {        
        $query = $this->db->query("SELECT manufacturer_id,manufacturer_name,mfg_code,mfg_email,mfg_phone1,mfg_phone2
        FROM cberp_manufacturer_ai
        ORDER BY manufacturer_id DESC");
        return $query->result_array();
    }

    public function details_by_id($id)
    {        
        $query = $this->db->query("SELECT *  FROM cberp_manufacturer_ai WHERE manufacturer_id = '$id'");
        return $query->row_array();
    }

    public function brand_list_by_id($id)
    {
        $this->db->select('*');
        $this->db->from('cberp_brands');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }




}
