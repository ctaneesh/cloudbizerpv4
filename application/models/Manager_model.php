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

class Manager_model extends CI_Model
{

    var $column_order = array('status', 'name', 'duedate', 'tdate', null);
    var $column_search = array('name', 'duedate', 'tdate');
    var $notecolumn_order = array(null, 'title', 'cdate', null);
    var $notecolumn_search = array('id', 'title', 'cdate');
    var $tcolumn_order = array('status', 'name', 'duedate', 'start', null, null);
    var $tcolumn_search = array('name', 'edate', 'status');
    var $order = array('id' => 'asc');

    var $pcolumn_order = array('cberp_projects.status', 'cberp_projects.name', 'cberp_projects.edate', 'cberp_projects.worth', null);
    var $pcolumn_search = array('cberp_projects.name', 'cberp_projects.edate', 'cberp_projects.status');

    private function _task_datatables_query($cday = '')
    {

        $this->db->from('cberp_todolist');
        if ($cday) {
            $this->db->where('DATE(duedate)=', $cday);
        }
        $this->db->where('eid', $this->aauth->get_user()->id);

        
        $i = 0;

        foreach ($this->column_search as $item) // loop column
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

                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
        $search = $this->input->post('order');
        if ($search) {
            $this->db->order_by($this->column_order[$search['0']['column']], $search['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function task_datatables($cday = '')
    {


        $this->_task_datatables_query($cday);

        if ($this->input->post('length') != -1)
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
        $this->db->where('eid', $this->aauth->get_user()->id);
        
        $query = $this->db->get();
        // die($this->db->last_query());
        return $query->result();
    }

    function task_count_filtered($cday = '')
    {
        $this->_task_datatables_query($cday);
        $this->db->where('eid', $this->aauth->get_user()->id);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function task_count_all($cday = '')
    {
        $this->_task_datatables_query($cday);
        $this->db->where('eid', $this->aauth->get_user()->id);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function addtask($name, $status, $priority, $stdate, $tdate, $employee, $content)
    {

        $data = array('tdate' => date('Y-m-d H:i:s'), 'name' => $name, 'status' => $status, 'start' => $stdate, 'duedate' => $tdate, 'description' => $content, 'eid' => $employee, 'related' => 0, 'priority' => $priority, 'rid' => 0);
        return $this->db->insert('cberp_todolist', $data);
    }

    public function edittask($id, $name, $status, $priority, $stdate, $tdate, $employee, $content)
    {

        $data = array('tdate' => date('Y-m-d H:i:s'), 'name' => $name, 'status' => $status, 'start' => $stdate, 'duedate' => $tdate, 'description' => $content, 'eid' => $employee, 'related' => 0, 'priority' => $priority, 'rid' => 0);
        $this->db->set($data);
        $this->db->where('id', $id);
        $this->db->where('eid', $this->aauth->get_user()->id);
        return $this->db->update('cberp_todolist');
        //return $this->db->insert('cberp_todolist', $data);
    }

    public function editproject($id, $name, $status, $priority, $progress, $customer, $sdate, $edate, $tag, $phase, $content, $budget, $customerview, $customer_comment, $link_to_cal, $color, $ptype, $employee)
    {
        $title = '[Project Edited] ';
        $this->add_activity($title, $id);
        $data = array('name' => $name, 'status' => $status, 'priority' => $priority, 'progress' => $progress, 'cid' => $customer, 'sdate' => $sdate, 'edate' => $edate, 'tag' => $tag, 'phase' => $phase, 'note' => $content, 'worth' => $budget, 'ptype' => $ptype);
        $this->db->set($data);
        $this->db->where('id', $id);
        $out = $this->db->update('cberp_projects');

        $this->db->delete('cberp_events', array('rel' => 1, 'rid' => $id));
        if ($link_to_cal > 0) {
            if ($link_to_cal == 1) {
                $sdate = $edate;
            }
            $data = array(
                'title' => '[Project] ' . $name,
                'start' => $sdate,
                'end' => $edate,
                'description' => $priority . ' priority. Start date: ' . $sdate . ' End Date: ' . $edate, 'color' => $color,
                'rel' => 1,
                'rid' => $id
            );
            $this->db->insert('cberp_events', $data);
        }
        if ($employee) {
            $this->db->delete('cberp_project_meta', array('pid' => $id, 'meta_key' => 19));
            foreach ($employee as $key => $value) {

                $data = array('pid' => $id, 'meta_key' => 19, 'meta_data' => $value);
                $this->db->insert('cberp_project_meta', $data);
            }
        }

        $data1 = array('meta_data' => $customerview, 'value' => $customer_comment);
        $this->db->set($data1);
        $this->db->where('pid', $id);
        $this->db->where('meta_key', 2);

        return $this->db->update('cberp_project_meta');
    }


    public function settask($id, $stat)
    {

        $data = array('status' => $stat);
        $this->db->set($data);
        $this->db->where('id', $id);
        $this->db->where('eid', $this->aauth->get_user()->id);
        return $this->db->update('cberp_todolist');
    }

    public function deletetask($id)
    {

        return $this->db->delete('cberp_todolist', array('id' => $id));
    }

    public function viewtask($id)
    {

        $this->db->select('cberp_todolist.*,cberp_employees.name AS emp, assi.name AS assign');
        $this->db->from('cberp_todolist');
        $this->db->where('cberp_todolist.id', $id);
        $this->db->join('cberp_employees', 'cberp_employees.id = cberp_todolist.eid', 'left');
        $this->db->join('cberp_employees AS assi', 'assi.id = cberp_todolist.aid', 'left');
        $query = $this->db->get();
        return $query->row_array();
    }

    public function pending_tasks_user($id)
    {
        $this->db->select('*');
        $this->db->from('cberp_todolist');
        // $this->db->where('status', 'Due');
        $this->db->where('eid', $id);
        $this->db->where('read_flag', '0');
        // $this->db->order_by('DATE(duedate)', 'ASC');
        $this->db->order_by('id', 'DESC');
        // $this->db->order_by('duedate', 'DESC');

        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }


    //projects

    private function _project_datatables_query($cday = '')
    {
        $this->db->select("cberp_projects.*,cberp_customers.name AS customer");
        $this->db->from('cberp_projects');
        $this->db->join('cberp_customers', 'cberp_projects.cid = cberp_customers.customer_id', 'left');
        $this->db->join('cberp_project_meta', 'cberp_project_meta.pid = cberp_projects.id', 'left');
        $this->db->where('cberp_project_meta.meta_key', 19);
        $this->db->where('cberp_project_meta.meta_data', $this->aauth->get_user()->id);
        if ($cday) {
            $this->db->where('DATE(cberp_projects.edate)=', $cday);
        }


        $i = 0;

        foreach ($this->pcolumn_search as $item) // loop column
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

                if (count($this->pcolumn_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
        $search = $this->input->post('order');
        if ($search) {
            $this->db->order_by($this->pcolumn_order[$search['0']['column']], $search['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function project_datatables($cday = '')
    {


        $this->_project_datatables_query($cday);

        if ($this->input->post('length') != -1)
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
        $query = $this->db->get();
        return $query->result();
    }

    function project_count_filtered($cday = '')
    {
        $this->_project_datatables_query($cday);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function project_count_all($cday = '')
    {
        $this->_project_datatables_query($cday);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function project_stats($project)
    {

        $query = $this->db->query("SELECT
				COUNT(IF( cberp_projects.status = 'Waiting', cberp_projects.id, NULL)) AS Waiting,
				COUNT(IF( cberp_projects.status = 'Progress', cberp_projects.id, NULL)) AS Progress,
				COUNT(IF( cberp_projects.status = 'Finished', cberp_projects.id, NULL)) AS Finished			
				FROM cberp_projects LEFT JOIN cberp_project_meta ON cberp_project_meta.pid=cberp_projects.id WHERE cberp_project_meta.meta_key=19 AND cberp_project_meta.meta_data=" . $this->aauth->get_user()->id . "");

        echo json_encode($query->result_array());

    }

    public function task_stats($id)
    {
        $query = $this->db->query("SELECT
				COUNT(IF( status = 'Due', id, NULL)) AS Due,
				COUNT(IF( status = 'Progress', id, NULL)) AS Progress,
				COUNT(IF( status = 'Done', id, NULL)) AS Done
				FROM cberp_todolist WHERE related=1 AND rid=$id AND aid=" . $this->aauth->get_user()->id . "");

        echo json_encode($query->result_array());

    }


    public function explore($id)
    {
        //project
        $this->db->select('cberp_projects.*,cberp_customers.name AS customer,cberp_customers.email');
        $this->db->from('cberp_projects');
        $this->db->where('cberp_projects.id', $id);
        $this->db->join('cberp_customers', 'cberp_projects.cid = cberp_customers.customer_id', 'left');
        $query = $this->db->get();
        $project = $query->row_array();
        //employee
        $this->db->select('cberp_employees.name');
        $this->db->from('cberp_project_meta');
        $this->db->where('cberp_project_meta.pid', $id);
        $this->db->where('cberp_project_meta.meta_key', 6);
        $this->db->join('cberp_employees', 'cberp_project_meta.meta_data = cberp_employees.id', 'left');
        $query = $this->db->get();
        $employee = $query->result_array();
        //invoices
        $this->db->select('cberp_invoices.*');
        $this->db->from('cberp_project_meta');
        $this->db->where('cberp_project_meta.pid', $id);
        $this->db->where('cberp_project_meta.meta_key', 11);
        $this->db->join('cberp_invoices', 'cberp_project_meta.meta_data = cberp_invoices.tid', 'left');
        $query = $this->db->get();
        $invoices = $query->result_array();
                   //clock
        $this->db->select('*');
        $this->db->from('cberp_project_meta');
        $this->db->where('pid', $id);
        $this->db->where('meta_key', 29);
        $this->db->where('meta_data', $this->aauth->get_user()->id);
        $query = $this->db->get();
        $clock = $query->row_array();

         return array('project' => $project, 'employee' => $employee, 'invoices' => $invoices,'clock'=>$clock);

    }

    private function _ptask_datatables_query($cday = '')
    {

        $this->db->from('cberp_todolist');
        $this->db->where('related', 1);
        if ($cday) {

            $this->db->where('rid=', $cday);
        }


        $i = 0;

        foreach ($this->tcolumn_search as $item) // loop column
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

                if (count($this->tcolumn_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
        $search = $this->input->post('order');
        if ($search) {
            $this->db->order_by($this->tcolumn_order[$search['0']['column']], $search['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function ptask_datatables($cday = '')
    {


        $this->_ptask_datatables_query($cday);

        if ($this->input->post('length') != -1)
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
        $this->db->where('related', 1);
        $this->db->where('rid=', $cday);
        $query = $this->db->get();
        return $query->result();
    }

    function ptask_count_filtered($cday = '')
    {
        $this->_ptask_datatables_query($cday);
        $this->db->where('related', 1);
        $this->db->where('rid=', $cday);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function ptask_count_all($cday = '')
    {
        $this->_ptask_datatables_query($cday);
        $this->db->where('related', 1);
        $this->db->where('rid=', $cday);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function task_thread($id)
    {

        $this->db->select('cberp_todolist.*, cberp_employees.name AS emp');
        $this->db->from('cberp_todolist');
        $this->db->where('cberp_todolist.related', 1);
        $this->db->where('cberp_todolist.rid', $id);
        $this->db->join('cberp_employees', 'cberp_todolist.eid = cberp_employees.id', 'left');
        $this->db->order_by('cberp_todolist.id', 'desc');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function milestones_list($id)
    {

        $query = $this->db->query('SELECT cberp_milestones.*,cberp_todolist.name as task FROM cberp_milestones LEFT JOIN cberp_project_meta ON cberp_project_meta.meta_data=cberp_milestones.id AND cberp_project_meta.meta_key=8 LEFT JOIN cberp_todolist ON cberp_project_meta.value=cberp_todolist.id WHERE cberp_milestones.pid=' . $id . ' ORDER BY cberp_milestones.id DESC;');
        return $query->result_array();


    }

    public function activities($id)
    {

        $this->db->select('cberp_project_meta.value');
        $this->db->from('cberp_project_meta');
        $this->db->where('pid', $id);
        $this->db->where('meta_key', 12);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function p_files($id)
    {

        $this->db->select('*');
        $this->db->from('cberp_project_meta');
        $this->db->where('pid', $id);
        $this->db->where('meta_key', 9);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function comments_thread($id)
    {

        $this->db->select('cberp_project_meta.value, cberp_project_meta.key3,cberp_employees.name AS employee, cberp_customers.name AS customer');
        $this->db->from('cberp_project_meta');
        $this->db->where('cberp_project_meta.pid', $id);
        $this->db->where('cberp_project_meta.meta_key', 13);
        $this->db->join('cberp_employees', 'cberp_project_meta.meta_data = cberp_employees.id', 'left');
        $this->db->join('cberp_customers', 'cberp_project_meta.key3 = cberp_customers.customer_id', 'left');
        $this->db->order_by('cberp_project_meta.id', 'desc');
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

    public function milestones($id)
    {

        $this->db->select('*');
        $this->db->from('cberp_milestones');
        $this->db->where('pid', $id);
        $this->db->order_by('id', 'desc');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function add_milestone($name, $stdate, $tdate, $content, $color, $prid)
    {

        $data = array('pid' => $prid, 'name' => $name, 'sdate' => $stdate, 'edate' => $tdate, 'color' => $color, 'exp' => $content);
        if ($prid) {

            $title = '[Milestone] ' . $name;
            $this->add_activity($title, $prid);

            return $this->db->insert('cberp_milestones', $data);

        } else {
            return 0;
        }
    }

    public function add_activity($name, $prid)
    {

        $data = array('pid' => $prid, 'meta_key' => 12, 'value' => $name . ' @' . date('Y-m-d H:i:s'));
        if ($prid) {
            return $this->db->insert('cberp_project_meta', $data);
        } else {
            return 0;
        }
    }

    public function paddtask($name, $status, $priority, $stdate, $tdate, $employee, $assign, $content, $prid, $milestone)
    {

        $data = array('tdate' => date('Y-m-d H:i:s'), 'name' => $name, 'status' => $status, 'start' => $stdate, 'duedate' => $tdate, 'description' => $content, 'eid' => $employee, 'aid' => $assign, 'related' => 1, 'priority' => $priority, 'rid' => $prid);
        if ($prid) {

            $this->db->insert('cberp_todolist', $data);
            $last = $this->db->insert_id();

            if ($milestone) {
                $this->meta_insert($prid, 8, $milestone, $last);
            }

            $out = $this->communication($prid, $name);

            return 1;
        } else {
            return 0;
        }
    }

    public function meta_insert($prid, $meta_key, $meta_data, $value)
    {

        $data = array('pid' => $prid, 'meta_key' => $meta_key, 'meta_data' => $meta_data, 'value' => $value);
        if ($prid) {
            return $this->db->insert('cberp_project_meta', $data);
        } else {
            return 0;
        }
    }

    private function communication($id, $sub)
    {

        $this->db->select('cberp_projects.name as pname,cberp_projects.ptype,cberp_customers.name as cust,cberp_customers.email');
        $this->db->from('cberp_projects');
        $this->db->where('cberp_projects.id', $id);
        $this->db->join('cberp_customers', "cberp_customers.customer_id = cberp_projects.cid", 'left');
        $query = $this->db->get();
        $result = $query->row_array();

        if ($result['ptype'] == '1') {
            $this->db->select('cberp_users.email,cberp_users.username');
            $this->db->from('cberp_project_meta');
            $this->db->where('cberp_project_meta.pid', $id);
            $this->db->where('cberp_project_meta.meta_key', 19);
            $this->db->join('cberp_users', "cberp_project_meta.meta_data = cberp_users.id", 'left');
            $query = $this->db->get();
            $result_c = $query->result_array();
            $message = '<h3>Dear Project Participant,</h3>
                        <p>This is an update mail regarding your project ' . $result['pname'] . '</p> <p>A new task has been added ' . $sub . '</p><p>With Reagrds,<br>Project Communication Manager';
            foreach ($result_c as $row) {
                $this->send_email($row['email'], $row['username'], '[Task Added]' . $sub, $message);
            }


        } else if ($result['ptype'] == '2') {

            $this->db->select('cberp_users.email,cberp_users.username');
            $this->db->from('cberp_project_meta');
            $this->db->where('cberp_project_meta.pid', $id);
            $this->db->where('cberp_project_meta.meta_key', 19);
            $this->db->join('cberp_users', "cberp_project_meta.meta_data = cberp_users.id", 'left');
            $query = $this->db->get();
            $result_c = $query->result_array();
            $message = '<h3>Dear Project Participant,</h3>
                        <p>This is an update mail regarding your project ' . $result['pname'] . '</p> <p>A new task has been added <strong>' . $sub . '</strong></p><p>With Regards,<br>Project Communication Manager</p>';
            foreach ($result_c as $row) {
                $this->send_email($row['email'], $row['username'], '[Task Added] ' . $sub, $message);
            }

            $message = '<h3>Dear Customer,</h3>
                        <p>This is an update mail regarding your project ' . $result['pname'] . '</p> <p>A new task has been added <strong>' . $sub . '</strong></p><p>With Warm Regards,<br>Project Communication Manager</p>';

            $this->send_email($result['email'], $result['cust'], '[Task Added] ' . $sub, $message);

        }

    }

    public function deletefile($pid, $mid)
    {

        $this->db->select('value');
        $this->db->from('cberp_project_meta');
        $this->db->where('pid', $pid);
        $this->db->where('meta_key', 9);
        $this->db->where('meta_data', $mid);
        $query = $this->db->get();
        $result = $query->row_array();
        unlink(FCPATH . 'userfiles/project/' . $result['value']);
        $this->db->delete('cberp_project_meta', array('pid' => $pid, 'meta_key' => 9, 'meta_data' => $mid));
    }




}
