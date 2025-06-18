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

class Customer_enquiry_model extends CI_Model
{
    var $table = 'cberp_customer_leads';
    var $column_order = array(null, 'lead_id','lead_number','customer_type','customer_name','customer_phone','customer_email','enquiry_status', null);
    var $column_search = array('lead_id','lead_number','customer_type','customer_name','customer_phone','customer_email','enquiry_status');
    var $order = array('lead_id' => 'desc');

    public function __construct()
    {
        parent::__construct();
    }


    public function enquiry_details($id)
    {

        $this->db->select('cberp_customer_leads.*,cberp_employees.name as employee');
        $this->db->from($this->table); 
        $this->db->join('cberp_employees', 'cberp_employees.id = cberp_customer_leads.created_by','left');
        $this->db->where('cberp_customer_leads.lead_id', $id);   
        $this->db->where('cberp_customer_leads.customer_id', $this->session->userdata('user_details')[0]->cid);
        $query = $this->db->get();
        // die($this->db->last_query());
        return $query->row_array();

    }
    
    public function enquiry_products($id)
    {

        $this->db->select('cberp_customer_lead_items.*,cberp_product_description.product_name,cberp_products.product_code');
        $this->db->from('cberp_customer_lead_items');
        $this->db->where('cberp_customer_lead_items.lead_id', $id);        
        $this->db->join('cberp_products', 'cberp_products.product_code = cberp_customer_lead_items.product_code', 'left');        
        $this->db->join('cberp_product_description', 'cberp_product_description.product_code = cberp_customer_lead_items.product_code', 'left');
        $query = $this->db->get();
        // die($this->db->last_query());
        return $query->result_array();

    }
    public function enquiry_details_table($id)
    {

        $this->db->select('cberp_customer_lead_attachments.*');
        $this->db->from('cberp_customer_lead_attachments');
        $this->db->where('cberp_customer_lead_attachments.lead_id', $id);  
        $query = $this->db->get();
        return $query->result_array();

    }

    public function find_tid()
    {
        $query = $this->db->select_max('tid', 'last_id')->get('cberp_quotes');
        $result = $query->row();
        $tid = $result->last_id;
        if(!empty($result) && $tid >0){
            $tid = $result->last_id;
        }else{
            $tid = 1000;
        }
        $tid = $tid+1;
        return $tid;
    }
    public function product_price($pid,$prdQty)
    {
        $this->db->select('product_price');
        $this->db->from('cberp_products');
        $this->db->where('pid', $pid);    
        $query = $this->db->get();
        $result = $query->row();
        
        if(!empty($result)){
            $total = ($result->product_price*$prdQty);
        }else{
            $total=0;
        }
        return $total;
    }
    





    private function _get_datatables_query()
    {

        $this->db->select('cberp_customer_leads.*');
        $this->db->from($this->table);
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
		// $this->db->where('cberp_customer_leads.customer_id', $this->session->userdata('user_details')[0]->cid);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from($this->table);
		// $this->db->where('cberp_customer_leads.customer_id', $this->session->userdata('user_details')[0]->cid);
        return $this->db->count_all_results();
    }

     public function update_status($id)
    {
        $this->db->set('status', 'customer_approved');
                $this->db->where('id', $id);
               return $this->db->update('cberp_quotes');
    }

    
    public function lead_products($id)
    {
        $this->db->select('cberp_customer_lead_items.*, cberp_products.onhand_quantity AS totalQty, cberp_products.alert_quantity, cberp_products.product_code,cberp_products.product_name,cberp_product_ai.min_price as lowestprice,cberp_product_ai.max_disrate');
        $this->db->from('cberp_customer_lead_items');
        $this->db->join('cberp_products', 'cberp_products.pid = cberp_customer_lead_items.pid', 'left');
        $this->db->join('cberp_product_ai', 'cberp_products.pid = cberp_product_ai.product_id');
        $this->db->where('cberp_customer_lead_items.tid', $id);
        $query = $this->db->get();
        // die($this->db->last_query());
        return $query->result_array();

    }

    public function gethistory($pid)
    {
        $this->db->select('customer_general_enquiry_log.*,cberp_employees.name');
        $this->db->from('customer_general_enquiry_log');  
        $this->db->join('cberp_employees','customer_general_enquiry_log.updated_by=cberp_employees.id');
        $this->db->where('customer_general_enquiry_log.master_id',$pid);
        $query = $this->db->get();
        return $query->result_array();
    }

      //erp2024 06-01-2025 detailed history log starts

      public function get_detailed_log($id,$page)
      {
          $this->db->select('cberp_master_log.*,cberp_employees.name,cberp_employees.picture');
          $this->db->from('cberp_master_log');  
          $this->db->join('cberp_employees','cberp_master_log.changed_by=cberp_employees.id');
          $this->db->where('cberp_master_log.item_no',$id);
          $this->db->where('cberp_master_log.log_from',$page);
          $this->db->order_by('cberp_master_log.seqence_number', 'ASC');
          $query = $this->db->get();
          return $query->result_array();
      }
      public function customer_sequence_number($customer_id)
      {
            $this->db->select('IFNULL(MAX(customer_lead_number) + 1, 1) AS next_lead_number');
            $this->db->from('cberp_customer_leads');
            $this->db->where('customer_id', $customer_id);
            $query = $this->db->get();
            $result = $query->row();
            
            $next_lead_number = $result->next_lead_number;
        
          return $next_lead_number;
      }
      //erp2024 06-01-2025 detailed history log ends

      

}   
