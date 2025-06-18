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

class Menus_model extends CI_Model
{
    var $table = 'cberp_menu_details';
    var $column_order = array(null,'main_menu','submenu1','submenu2','menu_detail','function','status', null);
    var $column_search = array('menu_number','submenu1','submenu2','menu_detail','function','status');
    var $order = array('menu_id' => 'desc');

    public function __construct()
    {
        parent::__construct();
    }


    public function load_coa_account_headers()
    {
        $this->db->from('cberp_coa_headers');
        $this->db->where('status', 'Active');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }
  
    private function _get_datatables_query()
    {

        $this->db->select('cberp_menu_details.*');
        $this->db->from('cberp_menu_details');
        // $this->db->group_by('cberp_menu_details.main_menu');
        // $this->db->order_by('cberp_menu_details.menu_id', 'DESC');
        $i = 0;

        foreach ($this->column_search as $item) // loop column
        {
            if ($_POST['search']['value']) // if datatable send POST for search
            {

                if ($i === 0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
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

    function get_datatables($dategap="")
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
		// $this->db->where('cberp_coa_types.customer_id', $this->session->userdata('user_details')[0]->cid);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function load_menus_by_parameter_with_value($field,$fieldvalue,$fetchedfield)
    {
        $this->db->select("$fetchedfield");
        $this->db->from('cberp_menu_details');
        $this->db->where($field, $fieldvalue);   
        $this->db->where('status', 'Active');   
        $this->db->group_by($fetchedfield);   
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    public function load_menu_by_menuid($menu_id)
    {
        $this->db->select('*');
        $this->db->from('cberp_menu_details');
        $this->db->where('menu_id', $menu_id);   
        $query = $this->db->get();
        $result = $query->row_array();
        return $result;
    }
    public function delete_action($menu_id)
    {
        
        $this->db->delete('cberp_menu_details',['menu_id'=>$menu_id]);
        $this->db->delete('cberp_user_menu_links',['menu_link_id'=>$menu_id]);
        
    }

}
