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

class Employee_model extends CI_Model
{
    var $quoteorder = array('cberp_quotes.created_date' => 'desc');
    var $quotecolumn_order = array(null,'cberp_quotes.quote_number', 'cberp_customers.name', 'cberp_quotes.quote_date', 'cberp_quotes.total',null, 'cberp_quotes.status');
    var $quotecolumn_search = array('cberp_quotes.quote_number', 'cberp_customers.name', 'cberp_quotes.quote_date', 'cberp_quotes.total','cberp_employees.name','cberp_quotes.status');
    public function list_employee()
    {
        $this->db->select('cberp_employees.*,cberp_users.banned,cberp_users.roleid,cberp_users.loc');
        $this->db->from('cberp_employees');

        $this->db->join('cberp_users', 'cberp_employees.id = cberp_users.id', 'left');
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->group_start();
        //     $this->db->where('cberp_users.loc', $this->aauth->get_user()->loc);
        //     if (BDATA) $this->db->or_where('loc', 0);
        //     $this->db->group_end();
        // }
        $this->db->order_by('cberp_employees.name', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function list_employee_details()
    {
        $this->db->select('cberp_employees.*,cberp_users.banned,cberp_users.email,cberp_users.roleid,cberp_users.loc,empreporting.name as reportingto,cberp_store.store_name as warehouse');
        $this->db->from('cberp_employees');

        $this->db->join('cberp_users', 'cberp_employees.id = cberp_users.id', 'left');
        $this->db->join('cberp_store', 'cberp_store.store_id = cberp_employees.emp_work_location', 'left');
        $this->db->join('cberp_employees as empreporting', 'empreporting.id = cberp_employees.reportingto', 'left');
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->group_start();
        //     $this->db->where('cberp_users.loc', $this->aauth->get_user()->loc);
        //     if (BDATA) $this->db->or_where('loc', 0);
        //     $this->db->group_end();
        // }
        $this->db->order_by('cberp_employees.name', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function list_project_employee($id)
    {
        $this->db->select('cberp_employees.*');
        $this->db->from('cberp_project_meta');
        $this->db->where('cberp_project_meta.pid', $id);
        $this->db->where('cberp_project_meta.meta_key', 19);
        $this->db->join('cberp_employees', 'cberp_employees.id = cberp_project_meta.meta_data', 'left');
        $this->db->join('cberp_users', 'cberp_employees.id = cberp_users.id', 'left');
        $this->db->order_by('cberp_users.roleid', 'DESC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function employee_details($id)
    {
        $this->db->select('cberp_employees.*,cberp_users.email,cberp_users.loc,cberp_users.roleid,cberp_country.name as countryname,cberp_country.code as countrycode');
        $this->db->from('cberp_employees');
        $this->db->where('cberp_employees.id', $id);
        $this->db->join('cberp_users', 'cberp_employees.id = cberp_users.id', 'left');
        $this->db->join('cberp_country', 'cberp_country.id = cberp_employees.country', 'left');
        $query = $this->db->get();
        // die($this->db->last_query());
        return $query->row_array();
    }
        
    public function employee_lists($id="")
    {
        $this->db->select('cberp_employees.id,cberp_employees.name,cberp_users.email,cberp_users.roleid');
        $this->db->from('cberp_employees');
        $this->db->join('cberp_users', 'cberp_employees.id = cberp_users.id', 'left');
        if(!empty($id)){
            $this->db->where_not_in('cberp_employees.id', $id);
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    public function salary_history($id)
    {
        $this->db->select('*');
        $this->db->from('cberp_hrm');
        $this->db->where('typ', 1);
        $this->db->where('rid', $id);
        $query = $this->db->get();
        return $query->result_array();
    }


    public function update_employee($id, $name, $phone, $phonealt, $address, $city, $region, $country, $postbox, $location, $salary = 0, $department = -1, $commission = 0, $roleid = false,  $nationality="", $residence_permit="", $expiry_date="", $passport_number="", $passport_expiry="", $passport_status="", $join_date="", $emp_work_location="", $reportingto,$amount_limit,$expense_claim_approver)
    {
        $this->db->select('salary');
        $this->db->from('cberp_employees');
        $this->db->where('id', $id);
        $query = $this->db->get();
        $sal = $query->row_array();
        $this->db->select('roleid');
        $this->db->from('cberp_users');
        $this->db->where('id', $id);
        $query = $this->db->get();
        $role = $query->row_array();


        $data = array(
            'name' => $name,
            'phone' => $phone,
            'phonealt' => $phonealt,
            'address' => $address,
            'city' => $city,
            'region' => $region,
            'country' => $country,
            'postbox' => $postbox,
            'salary' => $salary,
            'c_rate' => $commission,
            //erp2024 newly added fields 03-06-2024
            'expense_claim_approver' => $expense_claim_approver,
            'nationality' => $nationality,
            'residence_permit' => $residence_permit,
            'expiry_date' => $expiry_date,
            'passport_number' => $passport_number,
            'passport_expiry' => $passport_expiry,
            'passport_status' => $passport_status,
            'join_date' => $join_date,
            'phonealt' => $phonealt,
            'emp_work_location' => $emp_work_location,
            "reportingto" => $reportingto, 
            "amount_limit" => $amount_limit,
            "updated_dt" => date("Y-m-d H:i:s"),
            "updated_by" => $this->session->userdata('id')
            //erp2024 newly added fields 03-06-2024
        );
        if ($department > -1) {
            $data = array(
                'name' => $name,
                'phone' => $phone,
                'phonealt' => $phonealt,
                'address' => $address,
                'city' => $city,
                'region' => $region,
                'country' => $country,
                'postbox' => $postbox,
                'salary' => $salary,
                'dept' => $department,
                'c_rate' => $commission,
                //erp2024 newly added fields 03-06-2024
                'expense_claim_approver' => $expense_claim_approver,
                'nationality' => $nationality,
                'residence_permit' => $residence_permit,
                'expiry_date' => $expiry_date,
                'passport_number' => $passport_number,
                'passport_expiry' => $passport_expiry,
                'passport_status' => $passport_status,
                'join_date' => $join_date,
                'phonealt' => $phonealt,
                'emp_work_location' => $emp_work_location,
                "reportingto" => $reportingto, 
                "amount_limit" => $amount_limit,
                "updated_dt" => date("Y-m-d H:i:s"),
                "updated_by" => $this->session->userdata('id')
                //erp2024 newly added fields 03-06-2024
            );
        }
        
        // echo "<pre>"; print_r($data);  die();
        $this->db->set($data);
        $this->db->where('id', $id);


        if ($this->db->update('cberp_employees')) {

            // if ($roleid && $role['roleid'] != 5) {
                $this->db->set('loc', $location);
                $this->db->set('roleid', $roleid);
                $this->db->where('id', $id);
                $this->db->update('cberp_users');
            // }
            if (($salary != $sal['salary']) AND ($salary > 0.00)) {
                $data1 = array(
                    'typ' => 1,
                    'rid' => $id,
                    'val1' => $salary,
                    'val2' => $sal['salary'],
                    'val3' => date('Y-m-d H:i:s')
                );

                $this->db->insert('cberp_hrm', $data1);
            }
            echo json_encode(array('status' => 'Success', 'message' => $this->lang->line('UPDATED') .'&nbsp;<a href="' . base_url('employee/view?id=' .  $id) . '" class="btn btn-info btn-sm"><span class="icon-eye"></span>' . $this->lang->line('View') . '</a>'));
        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
                $this->lang->line('ERROR')));
        }

    }

    public function update_password($id, $cpassword, $newpassword, $renewpassword)
    {


    }

    public function editpicture($id, $pic)
    {
        $this->db->select('picture');
        $this->db->from('cberp_employees');
        $this->db->where('id', $id);

        $query = $this->db->get();
        $result = $query->row_array();


        $data = array(
            'picture' => $pic
        );


        $this->db->set($data);
        $this->db->where('id', $id);
        if ($this->db->update('cberp_employees')) {
            $this->db->set($data);
            $this->db->where('id', $id);
            $this->db->update('cberp_users');
            unlink(FCPATH . 'userfiles/employee/' . $result['picture']);
            unlink(FCPATH . 'userfiles/employee/thumbnail/' . $result['picture']);
        }


    }


    public function editsign($id, $pic)
    {
        $this->db->select('sign');
        $this->db->from('cberp_employees');
        $this->db->where('id', $id);

        $query = $this->db->get();
        $result = $query->row_array();


        $data = array(
            'sign' => $pic
        );


        $this->db->set($data);
        $this->db->where('id', $id);
        if ($this->db->update('cberp_employees')) {

            unlink(FCPATH . 'userfiles/employee_sign/' . $result['sign']);
            unlink(FCPATH . 'userfiles/employee_sign/thumbnail/' . $result['sign']);
        }


    }


    var $table = 'cberp_invoices';
    var $column_order = array(null, 'cberp_invoices.invoice_number', 'cberp_invoices.invoice_date', 'cberp_invoices.total', 'cberp_invoices.status');
    var $column_search = array('cberp_invoices.invoice_number', 'cberp_invoices.invoice_date', 'cberp_invoices.total', 'cberp_invoices.status');
    var $order = array('cberp_invoices.created_date' => 'asc');


    private function _invoice_datatables_query($id)
    {
        $this->db->select('cberp_invoices.*,cberp_customers.name');
        $this->db->from('cberp_invoices');
        $this->db->where('cberp_invoices.created_by', $id);
        $this->db->join('cberp_customers', 'cberp_invoices.customer_id=cberp_customers.customer_id', 'left');

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
        $search = $this->input->post('order');
        if ($search) // here order processing
        {
            $this->db->order_by($this->column_order[$search['0']['column']], $search['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function invoice_datatables($id)
    {
        $this->_invoice_datatables_query($id);
        if ($this->input->post('length') != -1)
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
        $query = $this->db->get();
        // die($this->db->last_query());
        return $query->result();
    }

    function invoicecount_filtered($id)
    {
        $this->_invoice_datatables_query($id);
        $query = $this->db->get();
        if ($id != '') {
            $this->db->where('cberp_invoices.created_by', $id);
        }
        return $query->num_rows($id);
    }

    public function invoicecount_all($id)
    {
        $this->_invoice_datatables_query($id);
        $query = $this->db->get();
        if ($id != '') {
            $this->db->where('cberp_invoices.created_by', $id);
        }
        return $query->num_rows($id = '');
    }

    //quote details
    private function _quote_datatables_query($eid)
    {

        // die($eid);
        $this->db->select('cberp_quotes.quote_number as id,cberp_quotes.quote_number as tid,cberp_quotes.quote_date as invoicedate,cberp_quotes.due_date invoiceduedate,cberp_quotes.total,cberp_quotes.status,cberp_customers.name,cberp_quotes.prepared_flag,cberp_employees.name as employeename');
        $this->db->from('cberp_quotes');
        $this->db->join('cberp_customers', 'cberp_quotes.customer_id=cberp_customers.customer_id', 'left');
        $this->db->join('cberp_employees', 'cberp_employees.id = cberp_quotes.created_by', 'left');
        $this->db->where('cberp_quotes.created_by',$eid);
        $i = 0;

        foreach ($this->quotecolumn_search as $item) // loop column
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

                if (count($this->quotecolumn_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->quotecolumn_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        }
         else if (isset($this->$quoteorder)) {
            $quoteorder = $this->$quoteorder;
            $this->db->order_by(key($quoteorder), $quoteorder[key($quoteorder)]);
        }
        else{
            $quoteorder = array('cberp_quotes.id' => 'desc');
        }
    }

    function quote_datatables($eid)
    {
        $this->_quote_datatables_query($eid);
        if ($_POST['length'] != -1){
            $this->db->limit($_POST['length'], $_POST['start']);
        }           

        // if ($this->aauth->get_user()->loc) {
        //     $this->db->where('cberp_quotes.loc', $this->aauth->get_user()->loc);
        // }  elseif(!BDATA) { $this->db->where('cberp_quotes.loc', 0); }
        $query = $this->db->get();
        // die($this->db->last_query());
        return $query->result();
    }

    function quote_count_filtered($eid)
    {
        $this->_quote_datatables_query($eid);    
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function quote_count_all($eid)
    {
        $this->db->select('cberp_quotes.quote_number');
        $this->db->from('cberp_quotes');
        return $this->db->count_all_results();
    }
    //quote ends

    //transaction


    var $tcolumn_order = array(null, 'account', 'type', 'cat', 'amount', 'stat');
    var $tcolumn_search = array('id', 'account');
    var $torder = array('id' => 'asc');
    var $eid = '';

    private function _get_datatables_query()
    {

        $this->db->from('cberp_transactions');

        $this->db->where('eid', $this->eid);


        $i = 0;

        foreach ($this->tcolumn_search as $item) // loop column
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

                if (count($this->tcolumn_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->tcolumn_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->torder)) {
            $order = $this->torder;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables($eid)
    {
        $this->eid = $eid;
        $this->_get_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered()
    {
        $this->db->from('cberp_transactions');
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from('cberp_transactions');
        $this->db->where('eid', $this->eid);
        return $this->db->count_all_results();
    }


    public function add_employee($id, $username, $name, $roleid, $phone, $address, $city, $region, $country, $postbox, $location, $salary = 0, $commission = 0, $department = 0, $nationality="", $residence_permit="", $expiry_date="", $passport_number="", $passport_expiry="", $passport_status="", $join_date="",$phonealt="",$emp_work_location="", $reportingto, $amount_limit,$expense_claim_approver)
    {
        $data = array(
            'id' => $id,
            'username' => $username,
            'name' => $name,
            'address' => $address,
            'city' => $city,
            'region' => $region,
            'country' => $country,
            'postbox' => $postbox,
            'phone' => $phone,
            'dept' => $department,
            'salary' => $salary,
            'c_rate' => $commission,
            //erp2024 newly added fields 03-06-2024
            'nationality' => $nationality,
            'expense_claim_approver' => $expense_claim_approver,
            'residence_permit' => $residence_permit,
            'expiry_date' => $expiry_date,
            'passport_number' => $passport_number,
            'passport_expiry' => $passport_expiry,
            'passport_status' => $passport_status,
            'join_date' => $join_date,
            'phonealt' => $phonealt,
            "emp_work_location" => $emp_work_location,
             "reportingto" => $reportingto, 
             "amount_limit" => $amount_limit,
             "created_dt" => date("Y-m-d H:i:s"),
             "created_by" => $this->session->userdata('id')
            //erp2024 newly added fields 03-06-2024
        );


        if ($this->db->insert('cberp_employees', $data)) {
            $data1 = array(
                'roleid' => $roleid,
                'loc' => $location
            );
            $lastinsert_id = $this->db->insert_id();
            log_table_data('cberp_employees','cberp_employees_log', 'id' ,'employee_id','Create',$lastinsert_id);
            $this->db->set($data1);
            $this->db->where('id', $id);

            $this->db->update('cberp_users');
            echo json_encode(array('status' => 'Success', 'message' => $this->lang->line('ADDED') .'&nbsp;<a href="' . base_url('employee/view?id=' .  $lastinsert_id) . '" class="btn btn-info btn-sm"><span class="icon-eye"></span>' . $this->lang->line('View') . '</a>'));

        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
                $this->lang->line('ERROR')));
        }

    }

    public function employee_validate($email)
    {
        $this->db->select('*');
        $this->db->from('cberp_users');
        $this->db->where('email', $email);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function money_details($eid)
    {
        $this->db->select('SUM(debit) AS debit,SUM(credit) AS credit');
        $this->db->from('cberp_transactions');
        $this->db->where('eid', $eid);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function sales_details($eid)
    {
        $this->db->select('SUM(pamnt) AS total');
        $this->db->from('cberp_invoices');
        $this->db->where('eid', $eid);
        $query = $this->db->get();
        // die($this->db->last_query());
        return $query->row_array();
    }

    public function employee_permissions()
    {
        $this->db->select('*');
        $this->db->from('cberp_premissions');
        $this->db->order_by('id', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    //documents list

    var $doccolumn_order = array(null, 'val1', 'val2', null);
    var $doccolumn_search = array('val1', 'val2');


    function addholidays($loc, $hday, $hdayto, $note)
    {
        $data = array('typ' => 2, 'rid' => $loc, 'val1' => $hday, 'val2' => $hdayto, 'val3' => $note);
        return $this->db->insert('cberp_hrm', $data);

    }

    function deleteholidays($id)
    {

        if ($this->db->delete('cberp_hrm', array('id' => $id, 'typ' => 2))) {


            return true;
        } else {
            return false;
        }

    }


    function holidays_datatables()
    {
        $this->holidays_datatables_query();
        if ($this->input->post('length') != -1)
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
        $query = $this->db->get();
        return $query->result();
    }

    private function holidays_datatables_query()
    {

        $this->db->from('cberp_hrm');
        $this->db->where('typ', 2);
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->where('rid', $this->aauth->get_user()->loc);
        // }
        $i = 0;

        foreach ($this->doccolumn_search as $item) // loop column
        {
            $search = $this->input->post('search');
            $value = $search['value'];
            if ($value) {

                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $value);
                } else {
                    $this->db->or_like($item, $value);
                }

                if (count($this->doccolumn_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
        $search = $this->input->post('order');
        if ($search) {
            $this->db->order_by($this->doccolumn_order[$search['0']['column']], $search['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->doccolumn_order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function holidays_count_filtered()
    {
        $this->holidays_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function holidays_count_all()
    {
        $this->holidays_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function hday_view($id, $loc)
    {
        $this->db->select('*');
        $this->db->from('cberp_hrm');
        $this->db->where('id', $id);
        $this->db->where('typ', 2);
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->where('rid', $loc);
        // }

        $query = $this->db->get();
        return $query->row_array();
    }

    public function edithday($id, $loc, $from, $todate, $note)
    {

        $data = array('typ' => 2, 'val1' => $from, 'val2' => $todate, 'val3' => $note);


        $this->db->set($data);
        $this->db->where('id', $id);
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->where('rid', $loc);
        // }


        $this->db->update('cberp_hrm');
        return true;

    }

    public function department_list($id, $rid = 0)
    {
        $this->db->select('*');
        $this->db->from('cberp_hrm');
        $this->db->where('typ', 3);
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->where('rid', $id);
        // }
        $query = $this->db->get();
        return $query->result_array();
    }

    public function department_elist($id)
    {
        $this->db->select('*');
        $this->db->from('cberp_employees');
        $this->db->where('dept', $id);
        $query = $this->db->get();
        return $query->result_array();
    }


    public function department_view($id, $loc)
    {
        $this->db->select('*');
        $this->db->from('cberp_hrm');
        $this->db->where('id', $id);
        $this->db->where('typ', 3);
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->where('rid', $loc);
        // }


        $query = $this->db->get();
        return $query->row_array();
    }

    function adddepartment($loc, $name)
    {
        $data = array('typ' => 3, 'rid' => $loc, 'val1' => $name);
        return $this->db->insert('cberp_hrm', $data);

    }

    function deletedepartment($id)
    {

        if ($this->db->delete('cberp_hrm', array('id' => $id, 'typ' => 3))) {


            return true;
        } else {
            return false;
        }

    }

    public function editdepartment($id, $loc, $name)
    {

        $data = array(
            'val1' => $name
        );


        $this->db->set($data);
        $this->db->where('id', $id);
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->where('rid', $loc);
        // }


        $this->db->update('cberp_hrm');
        return true;

    }

    //payroll

    private function _pay_get_datatables_query($eid)
    {

        $this->db->from('cberp_transactions');
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->where('loc', $this->aauth->get_user()->loc);
        // }
        $this->db->where('ext', 4);
        if ($eid) {
            $this->db->where('payerid', $eid);
        }


        $i = 0;

        foreach ($this->tcolumn_search as $item) // loop column
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

                if (count($this->tcolumn_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->tcolumn_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->torder)) {
            $order = $this->torder;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function pay_get_datatables($eid)
    {

        $this->_pay_get_datatables_query($eid);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function pay_count_filtered($eid)
    {
        $this->db->from('cberp_transactions');
        $this->db->where('ext', 4);
        if ($eid) {
            $this->db->where('payerid', $eid);
        }
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function pay_count_all($eid)
    {
        $this->db->from('cberp_transactions');
        $this->db->where('ext', 4);
        if ($eid) {
            $this->db->where('payerid', $eid);
        }
        return $this->db->count_all_results();
    }


    function addattendance($emp, $adate, $tfrom, $tto, $note)
    {

        foreach ($emp as $row) {

            $this->db->where('emp', $row);
            $this->db->where('DATE(adate)', $adate);
            $num = $this->db->count_all_results('cberp_attendance');

            if (!$num) {
                $data = array('emp' => $row, 'created' => date('Y-m-d H:i:s'), 'adate' => $adate, 'tfrom' => $tfrom, 'tto' => $tto, 'note' => $note);
                $this->db->insert('cberp_attendance', $data);
            }

        }

        return true;

    }

    function deleteattendance($id)
    {

        if ($this->db->delete('cberp_attendance', array('id' => $id))) {
            return true;
        } else {
            return false;
        }

    }

    var $acolumn_order = array(null, 'cberp_attendance.emp', 'cberp_attendance.adate', null, null);
    var $acolumn_search = array('cberp_employees.name', 'cberp_attendance.adate');

    function attendance_datatables($cid)
    {
        $this->attendance_datatables_query($cid);
        if ($this->input->post('length') != -1)
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
        $query = $this->db->get();
        return $query->result();
    }

    private function attendance_datatables_query($cid = 0)
    {
        $this->db->select('cberp_attendance.*,cberp_employees.name');
        $this->db->from('cberp_attendance');
        $this->db->join('cberp_employees', 'cberp_employees.id=cberp_attendance.emp', 'left');
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->join('cberp_users', 'cberp_users.id=cberp_attendance.emp', 'left');
        //     $this->db->where('cberp_users.loc', $this->aauth->get_user()->loc);

        // }
        if ($cid) $this->db->where('cberp_attendance.emp', $cid);
        $i = 0;

        foreach ($this->acolumn_search as $item) // loop column
        {
            $search = $this->input->post('search');
            $value = $search['value'];
            if ($value) {

                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $value);
                } else {
                    $this->db->or_like($item, $value);
                }

                if (count($this->acolumn_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
        $search = $this->input->post('order');
        if ($search) {
            $this->db->order_by($this->acolumn_order[$search['0']['column']], $search['0']['dir']);
        } else if (isset($this->acolumn_order)) {
            $order = $this->acolumn_order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function attendance_count_filtered($cid)
    {
        $this->attendance_datatables_query($cid);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function attendance_count_all($cid)
    {
        $this->attendance_datatables_query($cid);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function getAttendance($emp, $start, $end)
    {

        $sql = "SELECT  CONCAT(tfrom, ' - ', tto) AS title,DATE(adate) as start ,DATE(adate) as end FROM cberp_attendance WHERE (emp='$emp') AND (DATE(adate) BETWEEN ? AND ? ) ORDER BY DATE(adate) ASC";
        return $this->db->query($sql, array($start, $end))->result();

    }

    public function getHolidays($loc, $start, $end)
    {

        $sql = "SELECT  CONCAT(DATE(val1), ' - ', DATE(val2),' - ',val3) AS title,DATE(val1) as start ,DATE(val2) as end FROM cberp_hrm WHERE  (typ='2') AND  (rid='$loc') AND (DATE(val1) BETWEEN ? AND ? ) ORDER BY DATE(val1) ASC";
        return $this->db->query($sql, array($start, $end))->result();

    }


    public function salary_view($eid)
    {
        $this->db->from('cberp_transactions');
        $this->db->where('ext', 4);
        $this->db->where('payerid', $eid);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function autoattend($opt)
    {
        $this->db->set('key1', $opt);
        $this->db->where('id', 62);

        $this->db->update('univarsal_api');
        return true;
    }

    //erp2024 17-07-2024  
    public function reporting_emp_byid($id) 
    {
        $this->db->select('cberp_employees.name');
        $this->db->from('cberp_employees');
        $this->db->where('cberp_employees.id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }
    //erp2024 17-07-2024  

    public function gethistory($id)
    {
        $this->db->select('cberp_employees_log.*,cberp_employees.name');
        $this->db->from('cberp_employees_log');  
        $this->db->join('cberp_employees','cberp_employees_log.performed_by=cberp_employees.id');
        $this->db->where('cberp_employees_log.employee_id',$id);
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
     public function load_modules_by_roleid($role_id)
     {
        $this->db->select('cberp_menus.module_number');
        $this->db->from('cberp_role_menu_links');
        $this->db->where('cberp_role_menu_links.role_id',$role_id);
        $query = $this->db->get();
        return $query->result_array();
     }
     public function load_all_menus()
     {
        $this->db->select('cberp_menus.menu_number, cberp_menus.menu_label, cberp_menus.function_name, cberp_menus.parent_menu_id');
        $this->db->from('cberp_menus');        
        $this->db->where('cberp_menus.status','Active');
        $query = $this->db->get();
        return $query->result_array();
     }
     public function load_all_menus_with_modules()
     {
        $this->db->select('cberp_menus.menu_number, cberp_menus.menu_label, cberp_module_groups.module_number');
        $this->db->from('cberp_menus');
        $this->db->join('cberp_module_groups', 'cberp_module_groups.module_number = cberp_menus.module_number', 'left');
        $this->db->where('cberp_menus.status','Active');
        $this->db->where('cberp_menus.module_number IS NOT NULL');
        // $this->db->where('cberp_menus.function_name IS NULL');
        $this->db->where('cberp_module_groups.status','Active');
        $query = $this->db->get();
        return $query->result_array();
     }
}
