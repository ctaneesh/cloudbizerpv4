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

class Enquiry_model extends CI_Model
{
    var $table = 'cberp_customer_leads';
    var $column_order = array(null, 'lead_number','customer_name','customer_type','customer_phone','total','customer_reference_number','created_by','created_date','assigned_to','due_date','enquiry_status','due_date', null);
    var $column_search = array('lead_number','customer_name','customer_type','customer_phone','total','customer_reference_number','createdby.name','created_date','cberp_employees.name','due_date','enquiry_status','due_date');
    var $order = array('lead_id' => 'desc');
    public function __construct()
    {
        parent::__construct();
    }


    public function enquiry_details($id)
    {

        $this->db->select('cberp_customer_leads.*,cberp_customers.name,cberp_customers.phone,cberp_customers.address,cberp_customers.city,cberp_customers.region,cberp_customers.country,cberp_customers.postbox,cberp_customers.email,cberp_customers.gid,cberp_customers.company,cberp_customers.tax_id');
        $this->db->from($this->table);
        $this->db->where('cberp_customer_leads.id', $id);        
        $this->db->join('cberp_customers', 'cberp_customers.customer_id = cberp_customer_leads.customer_id', 'left');
        $query = $this->db->get();
        return $query->row_array();

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
    
    public function product_details($pid)
    {
        $this->db->select('*');
        $this->db->from('cberp_products');
        $this->db->where('pid', $pid);    
        $query = $this->db->get();
        // die($this->db->last_query());
        $result = $query->row();
        return $result;
    }

    public function enquiry_products($id)
    {

        $this->db->select('cberp_customer_lead_items.*,cberp_products.product_name,cberp_products.product_code');
        $this->db->from('cberp_customer_lead_items');
        $this->db->where('lead_id', $id);        
        $this->db->join('cberp_products', 'cberp_products.pid = cberp_customer_lead_items.product_id', 'left');
        $query = $this->db->get();
        return $query->result_array();

    }

    private function _get_datatables_query($dategap="")
    {

        $filter_status = !empty($this->input->post('filter_status')) ?$this->input->post('filter_status') : "";
        $filter_employee = !empty($this->input->post('filter_employee')) ?$this->input->post('filter_employee') : "";

        $filter_expiry_date_from = !empty($this->input->post('filter_expiry_date_from')) ? date('Y-m-d',strtotime($this->input->post('filter_expiry_date_from'))) : ""; 

        $filter_expiry_date_to = !empty($this->input->post('filter_expiry_date_to')) ? date('Y-m-d',strtotime($this->input->post('filter_expiry_date_to'))) : "";
       
        $filter_price_from = !empty($this->input->post('filter_price_from')) ? $this->input->post('filter_price_from') : 0;
        $filter_price_to = !empty($this->input->post('filter_price_to')) ? $this->input->post('filter_price_to'): 0;

        $filter_customer = !empty($this->input->post('filter_customer')) ?$this->input->post('filter_customer') : "";
        $filter_customertype = !empty($this->input->post('filter_customertype')) ?$this->input->post('filter_customertype') : "";

        $this->db->select('cberp_customer_leads.*,cberp_employees.name AS assigned_person,createdby.name as createdbyname,cberp_customers.name as customer_name');
        $this->db->join('cberp_employees', 'cberp_employees.id = cberp_customer_leads.assigned_to', 'left');      
        $this->db->join('cberp_customers', 'cberp_customers.customer_id = cberp_customer_leads.customer_id');
        $this->db->join('cberp_employees as createdby', 'createdby.id = cberp_customer_leads.created_by', 'left');
        $this->db->where("cberp_customer_leads.lead_number IS NOT NULL");
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
        
        if ($this->input->post('start_date') && $this->input->post('end_date'))
        {
            $start_date = datefordatabase($this->input->post('start_date'));
            $end_date = datefordatabase($this->input->post('end_date'));
            $this->db->where("DATE(cberp_customer_leads.created_date) BETWEEN '$start_date' AND '$end_date'");
        }
        if(!empty($filter_status)){
            $this->db->where_in("cberp_customer_leads.enquiry_status",$filter_status);
        }

      
        if(!empty($filter_employee)){
            $this->db->where_in("cberp_customer_leads.assigned_to",$filter_employee);
        }

        if(!empty($filter_expiry_date_from) && !empty($filter_expiry_date_to)){
            $this->db->where("cberp_customer_leads.due_date BETWEEN '$filter_expiry_date_from' AND '$filter_expiry_date_to'");
        }
      
        if(!empty($filter_customer)){
            $this->db->where_in("cberp_customer_leads.customer_id",$filter_customer);
        }
        if(!empty($filter_customertype)){
            $this->db->where("cberp_customer_leads.customer_type",$filter_customertype);
        }

        if($filter_price_to > 0){
            $this->db->where("cberp_customer_leads.total BETWEEN $filter_price_from AND $filter_price_to");
        }


        if (isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
        // else{
        //     $order = array('lead_id' => 'desc');
        // }
    }

    function get_datatables($dategap="")
    {
       

        $this->_get_datatables_query($dategap);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
               
        $query = $this->db->get(); 
        return $query->result();
    }

    function count_filtered($dategap="")
    {
        $this->_get_datatables_query($dategap);
		// $this->db->where('cberp_customer_leads.customer_id', $this->session->userdata('user_details')[0]->cid);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all($dategap="")
    {
        // $this->db->from($this->table);
        $this->_get_datatables_query($dategap);
		// $this->db->where('cberp_customer_leads.customer_id', $this->session->userdata('user_details')[0]->cid);
        $query = $this->db->get();
        // die($this->db->last_query());
        return $query->num_rows();
        // return $this->db->count_all_results();
    }

     public function update_status($id)
    {
        $this->db->set('status', 'customer_approved');
                $this->db->where('id', $id);
               return $this->db->update('cberp_quotes');
    }

    public function get_enquiry_count_filter($filter_status='',$filter_employee='',$filter_expiry_date_from='',$filter_expiry_date_to='',$filter_price_from='',$filter_price_to='',$filter_customer='',$filter_customertype=''){
        $this->db->select("
        SUM(CASE WHEN created_date BETWEEN CURDATE() - INTERVAL 1 YEAR AND CURDATE() THEN 1 ELSE 0 END) AS yearly_count,
        SUM(CASE WHEN created_date BETWEEN CURDATE() - INTERVAL 1 QUARTER AND CURDATE() THEN 1 ELSE 0 END) AS quarterly_count,
        SUM(CASE WHEN created_date BETWEEN CURDATE() - INTERVAL 1 MONTH AND CURDATE() THEN 1 ELSE 0 END) AS monthly_count,
        SUM(CASE WHEN created_date BETWEEN CURDATE() - INTERVAL 1 WEEK AND CURDATE() THEN 1 ELSE 0 END) AS weekly_count,
        SUM(CASE WHEN DATE(created_date) = CURDATE() THEN 1 ELSE 0 END) AS daily_count,
        SUM(CASE WHEN enquiry_status = 'Assigned' AND created_date BETWEEN CURDATE() - INTERVAL 1 YEAR AND CURDATE() THEN 1 ELSE 0 END) AS yearly_assigned_count,
        SUM(CASE WHEN enquiry_status = 'Assigned' AND created_date BETWEEN CURDATE() - INTERVAL 1 QUARTER AND CURDATE() THEN 1 ELSE 0 END) AS quarterly_assigned_count,
        SUM(CASE WHEN enquiry_status = 'Assigned' AND created_date BETWEEN CURDATE() - INTERVAL 1 MONTH AND CURDATE() THEN 1 ELSE 0 END) AS monthly_assigned_count,
        SUM(CASE WHEN enquiry_status = 'Assigned' AND created_date BETWEEN CURDATE() - INTERVAL 1 WEEK AND CURDATE() THEN 1 ELSE 0 END) AS weekly_assigned_count,
        SUM(CASE WHEN enquiry_status = 'Assigned' AND DATE(created_date) = CURDATE() THEN 1 ELSE 0 END) AS daily_assigned_count,
        SUM(CASE WHEN enquiry_status = 'Open' AND created_date BETWEEN CURDATE() - INTERVAL 1 YEAR AND CURDATE() THEN 1 ELSE 0 END) AS yearly_open_count,
        SUM(CASE WHEN enquiry_status = 'Open' AND created_date BETWEEN CURDATE() - INTERVAL 1 QUARTER AND CURDATE() THEN 1 ELSE 0 END) AS quarterly_open_count,
        SUM(CASE WHEN enquiry_status = 'Open' AND created_date BETWEEN CURDATE() - INTERVAL 1 MONTH AND CURDATE() THEN 1 ELSE 0 END) AS monthly_open_count,
        SUM(CASE WHEN enquiry_status = 'Open' AND created_date BETWEEN CURDATE() - INTERVAL 1 WEEK AND CURDATE() THEN 1 ELSE 0 END) AS weekly_open_count,
        SUM(CASE WHEN enquiry_status = 'Open' AND DATE(created_date) = CURDATE() THEN 1 ELSE 0 END) AS daily_open_count,
        SUM(CASE WHEN enquiry_status = 'Closed' AND created_date BETWEEN CURDATE() - INTERVAL 1 YEAR AND CURDATE() THEN 1 ELSE 0 END) AS yearly_closed_count,
        SUM(CASE WHEN enquiry_status = 'Closed' AND created_date BETWEEN CURDATE() - INTERVAL 1 QUARTER AND CURDATE() THEN 1 ELSE 0 END) AS quarterly_closed_count,
        SUM(CASE WHEN enquiry_status = 'Closed' AND created_date BETWEEN CURDATE() - INTERVAL 1 MONTH AND CURDATE() THEN 1 ELSE 0 END) AS monthly_closed_count,
        SUM(CASE WHEN enquiry_status = 'Closed' AND created_date BETWEEN CURDATE() - INTERVAL 1 WEEK AND CURDATE() THEN 1 ELSE 0 END) AS weekly_closed_count,
        SUM(CASE WHEN enquiry_status = 'Closed' AND DATE(created_date) = CURDATE() THEN 1 ELSE 0 END) AS daily_closed_count,
        SUM(CASE WHEN created_date BETWEEN CURDATE() - INTERVAL 1 YEAR AND CURDATE() THEN total ELSE 0 END) AS yearly_total,
        SUM(CASE WHEN created_date BETWEEN CURDATE() - INTERVAL 1 QUARTER AND CURDATE() THEN total ELSE 0 END) AS quarterly_total,
        SUM(CASE WHEN created_date BETWEEN CURDATE() - INTERVAL 1 MONTH AND CURDATE() THEN total ELSE 0 END) AS monthly_total,
        SUM(CASE WHEN created_date BETWEEN CURDATE() - INTERVAL 1 WEEK AND CURDATE() THEN total ELSE 0 END) AS weekly_total,
        SUM(CASE WHEN DATE(created_date) = CURDATE() THEN total ELSE 0 END) AS daily_total
    ");
        $this->db->from('cberp_customer_leads');

        // Apply the filter conditions
        if (!empty($filter_status)) {
            $this->db->where_in('cberp_customer_leads.enquiry_status', $filter_status);
        }

        if (!empty($filter_employee)) {
            $this->db->where_in('cberp_customer_leads.assigned_to', $filter_employee);
        }

        if (!empty($filter_expiry_date_from) && !empty($filter_expiry_date_to)) {
            $this->db->where("cberp_customer_leads.due_date BETWEEN '$filter_expiry_date_from' AND '$filter_expiry_date_to'");
        }

        if(!empty($filter_customer)){
            $this->db->where_in("cberp_customer_leads.customer_id",$filter_customer);
        }
        if(!empty($filter_customertype)){
            $this->db->where("cberp_customer_leads.customer_type",$filter_customertype);
        }

        if($filter_price_to > 0){
            $this->db->where("cberp_customer_leads.total BETWEEN $filter_price_from AND $filter_price_to");
        }

        
        $query = $this->db->get();
        // die($this->db->last_query());
        // Return the result
        return $query->row_array();
    }
    

}
