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

class Employeelist_model extends CI_Model
{
    
    var $table = 'cberp_employees';
    var $column_order = array('cberp_employees.id','cberp_employees.name','cberp_users.roleid','cberp_employees.phone','cberp_users.email',null,null,'cberp_store.title','empreporting.name'); //set column field database for datatable orderable
    var $column_search = array('cberp_employees.name','cberp_employees.phone','cberp_users.email','cberp_store.store_name','empreporting.name'); //set column field database for datatable searchable
    var $order = array('cberp_employees.id' => 'desc'); // default order



    private function _get_datatables_query($id = '')
    {
       
      
        
        $filter_passport_date_from = !empty($this->input->post('filter_passport_date_from')) ? date('Y-m-d',strtotime($this->input->post('filter_passport_date_from'))) : "";

        $filter_passport_date_to = !empty($this->input->post('filter_passport_date_to')) ? date('Y-m-d',strtotime($this->input->post('filter_passport_date_to'))) : ""; 

        $filter_residence_permit_from = !empty($this->input->post('filter_residence_permit_from')) ? date('Y-m-d',strtotime($this->input->post('filter_residence_permit_from'))) : "";

        $filter_residence_permit_to = !empty($this->input->post('filter_residence_permit_to')) ? date('Y-m-d',strtotime($this->input->post('filter_residence_permit_to'))) : ""; 
        
    
        $filter_reportingto = !empty($this->input->post('filter_employee')) ?$this->input->post('filter_employee') : "";
        $filter_emp_work_location = !empty($this->input->post('filter_warehouse')) ?$this->input->post('filter_warehouse') : "";
        


        $this->db->select('cberp_employees.*,cberp_users.banned,cberp_users.email,cberp_users.roleid,cberp_users.loc,empreporting.name as reportingto,cberp_store.store_name as warehouse');
        $this->db->from('cberp_employees');

        $this->db->join('cberp_users', 'cberp_employees.id = cberp_users.id', 'left');
        $this->db->join('cberp_store', 'cberp_store.store_id = cberp_employees.emp_work_location', 'left');
        $this->db->join('cberp_employees as empreporting', 'empreporting.id = cberp_employees.reportingto', 'left');
       
        if(!empty($filter_residence_permit_from) && !empty($filter_residence_permit_to)){
            $this->db->where("cberp_employees.expiry_date BETWEEN '$filter_residence_permit_from' AND '$filter_residence_permit_to'");
        }

        if(!empty($filter_passport_date_from) && !empty($filter_passport_date_to)){
            $this->db->where("cberp_employees.passport_expiry BETWEEN '$filter_passport_date_from' AND '$filter_passport_date_to'");
        }

        if($filter_reportingto){
            $this->db->where_in("cberp_employees.reportingto", $filter_reportingto);
        }

        if(!empty($filter_emp_work_location)){
            $this->db->where_in("cberp_employees.emp_work_location",$filter_emp_work_location);
        }
        
        $i = 0;

        foreach ($this->column_search as $item) // loop column
        {
            $search = $this->input->post('search');
            $value = $search['value'];
            if ($value) // if datatable send POST for search
            {

                if ($i === 0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $value);
                } else {
                    $this->db->or_like($item, $value);
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
        else{
            $order = array('cberp_employees.id' => 'DESC');
        }
    }

    function get_datatables($id = '')
    {
        $this->_get_datatables_query($id);
        // if ($this->aauth->get_user()->loc) {
        //    // $this->db->where('loc', $this->aauth->get_user()->loc);
        // }
        if ($this->input->post('length') != -1)
        {
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
        }
        
        $query = $this->db->get();
        // die($this->db->last_query());
        return $query->result();
    }

    function count_filtered($id = '')
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        if ($id != '') {
            $this->db->where('cberp_employees.id', $id);
        }
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->where('cberp_employees.loc', $this->aauth->get_user()->loc);
        // }
        return $query->num_rows($id = '');
    }

    public function count_all($id = '')
    {
        $this->_get_datatables_query();
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->where('cberp_customers.loc', $this->aauth->get_user()->loc);
        // }
        if ($id != '') {
            $this->db->where('cberp_employees.id', $id);
        }
        $query = $this->db->get();
        return $query->num_rows($id = '');
    }


}
