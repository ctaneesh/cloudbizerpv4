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

class Roles_model extends CI_Model
{
    var $table = 'cberp_roles';
    var $column_order = array(null,'role_name','status', null);
    var $column_search = array('role_name','status');
    var $order = array('id' => 'desc');

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

        $this->db->select('cberp_roles.*');
        $this->db->from('cberp_roles');
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
    public function load_roles_by_id($role_id)
    {
        $this->db->select('*');
        $this->db->from('cberp_roles');
        $this->db->where('role_id', $role_id);   
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }
  


    public function copy_from_user_menu_links_to_log($user_id)
    {
       
        // Select data from the first table
        $this->db->select('user_id, menu_link_id,created_by,created_date_time,updated_by,updated_date_time');
        $this->db->from('cberp_user_menu_links');
        $this->db->where('user_id', $user_id);
        $query = $this->db->get();
    
        if ($query->num_rows() > 0) {
            $data = $query->result_array();
            $seqence_number = $this->latest_sequence_number('cberp_user_menu_links_log');
            foreach ($data as $key => &$row) {
                $row['sequence_number'] = $seqence_number;
                $row['performed_by'] = $this->session->userdata('id');
                $row['performed_dt'] = date('Y-m-d H:i:s');
            }    
            $this->db->insert_batch('cberp_user_menu_links_log', $data);    
        }
            
    }
    public function copy_from_user_module_approval_to_log($user_id)
    {
       
        // Select data from the first table
        $this->db->select('user_id, module_id,first_level_approval,second_level_approval,third_level_approval,created_by,created_date_time,updated_by,updated_date_time');
        $this->db->from('cberp_menu_user_module_approval');
        $this->db->where('user_id', $user_id);
        $query = $this->db->get();
    
        if ($query->num_rows() > 0) {
            $data = $query->result_array();
            $seqence_number = $this->latest_sequence_number('cberp_menu_user_module_approval_log');
            foreach ($data as $key => &$row) {
                $row['sequence_number'] = $seqence_number;
                $row['performed_by'] = $this->session->userdata('id');
                $row['performed_date_time'] = date('Y-m-d H:i:s');
            }    
            $this->db->insert_batch('cberp_menu_user_module_approval_log', $data);    
        }
            
    }
    

    public function latest_sequence_number($table)
    {
        $this->db->select('sequence_number');
        $this->db->from($table);
        $this->db->order_by('sequence_number', 'DESC');
        $this->db->limit(1);
    
        $query = $this->db->get();
    
        if ($query->num_rows() > 0) {
            return $query->row()->sequence_number + 1; // Increment the latest sequence number
        } else {
            return 1000; // Default starting value
        }
    }

    public function latest_approval_link_id()
    {
        $this->db->select('approval_link_id');
        $this->db->from('cberp_menu_user_module_approval');
        $this->db->order_by('approval_link_id', 'DESC');
        $this->db->limit(1);
    
        $query = $this->db->get();
        // die($this->db->last_query());
        if ($query->num_rows() > 0) {
            $seqence_number = $query->row()->approval_link_id;
        } else {
            $seqence_number = '100';
        }
        return $seqence_number;
    }

    function get_latest_role_approval_link_id()
    {
        $ci =& get_instance();
    
        $ci->db->select('approval_link_id');
        $ci->db->from('cberp_menu_role_module_approval'); 
        $ci->db->order_by('approval_link_id', 'DESC');
        $ci->db->limit(1);

        $query = $ci->db->get();

        if ($query->num_rows() > 0) {
            $seqence_number = $query->row()->approval_link_id;
        } else {
            $seqence_number = '100';
        }

        return $seqence_number;
    }

    public function fetch_from_log_to_user_menu_links($user_id)
    {
        // Fetch the latest data from 'cberp_user_menu_links_log'
        $this->db->select('user_id, menu_link_id, created_date_time, updated_by, updated_date_time');
        $this->db->from('cberp_user_menu_links_log');
        $this->db->where('sequence_number', '(SELECT MAX(sequence_number) FROM cberp_user_menu_links_log)', FALSE);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            // Delete existing data for the user
            $this->db->delete('cberp_user_menu_links', ['user_id' => $user_id]);
            
            // Insert new data from the log
            $data = $query->result_array();
            $this->db->insert_batch('cberp_user_menu_links', $data);
        }

        // Fetch the latest data from 'cberp_menu_user_module_approval_log'
        $this->db->select('approval_link_id, user_id, module_id, first_level_approval, second_level_approval, third_level_approval, created_by, created_date_time, updated_by, updated_date_time');
        $this->db->from('cberp_menu_user_module_approval_log');
        $this->db->where('sequence_number', '(SELECT MAX(sequence_number) FROM cberp_menu_user_module_approval_log)', FALSE);
        $query1 = $this->db->get();
        if ($query1->num_rows() > 0) {
            // Delete existing data for the user
            $this->db->delete('cberp_menu_user_module_approval', ['user_id' => $user_id]);
            
            // Insert new data from the log
            $data1 = $query1->result_array();
            $this->db->insert_batch('cberp_menu_user_module_approval', $data1);
        }
    }
    
    public function check_entries_in_menu_log($user_id)
    {
        // Select data from the first table
        $this->db->select('user_id');
        $this->db->from('cberp_user_menu_links_log');
        $this->db->where('user_id', $user_id);
    
        $query = $this->db->get();    
        if ($query->num_rows() > 0) {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function linked_menus_approvel_by_user_id($user_id){
        $query = $this->db->query("SELECT first_level_approval,second_level_approval,third_level_approval FROM cberp_user_menu_links WHERE user_id = '$user_id'");
        $result = $query->row_array();
        return $result;
     }
    
     public function linked_menus_approvel_by_role_id($role_id){
        $query = $this->db->query("SELECT first_level_approval,second_level_approval,third_level_approval,module_id FROM cberp_menu_role_module_approval WHERE role_id = '$role_id'");
          $result = $query->result_array();
        return $result;
     }

     public function check_prvious_data_in_menu_log($user_id)
     {
         // Select data from the first table
         $this->db->select('user_id, menu_link_id');
         $this->db->from('cberp_user_menu_links_log');
         $this->db->where('user_id', $user_id);
         $query = $this->db->get();
         if ($query->num_rows() > 0) {
             return true; 
         } else {
             return false;
         }
     }
     
     public function get_menu_data($id)
     {
    

        $this->db->select('main_menu as main,submenu1 as submenu,submenu2,menu_detail');
        $this->db->from('cberp_menu_details');
        $this->db->where('menu_id', $id);   
     
        $this->db->group_by('submenu2'); 
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;  

   
   }
    
    


}
