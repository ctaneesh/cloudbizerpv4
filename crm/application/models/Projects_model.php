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

class Projects_model extends CI_Model
{

    var $column_order = array('cberp_projects.status', 'cberp_projects.name', 'cberp_projects.edate', 'cberp_projects.worth', null);
    var $column_search = array('cberp_projects.name', 'cberp_projects.edate', 'cberp_projects.status');
    var $tcolumn_order = array('status', 'name', 'duedate', 'start', null, null);
    var $tcolumn_search = array('name', 'edate', 'status');
    var $order = array('id' => 'desc');


    public function explore($id)
    {
        $this->db->select('cberp_projects.id AS prj');
        $this->db->from('cberp_projects');
        $this->db->where('cberp_projects.id', $id);
        $this->db->where('cberp_projects.cid=', $this->session->userdata('user_details')[0]->cid);
        $this->db->where('cberp_project_meta.meta_key', 2);
        $this->db->where('cberp_project_meta.meta_data=', 'true');
        $this->db->join('cberp_customers', 'cberp_projects.cid = cberp_customers.customer_id', 'left');
        $this->db->join('cberp_project_meta', 'cberp_project_meta.pid = cberp_projects.id', 'left');

        $query = $this->db->get();
        $result = $query->row_array();

        if ($result['prj'] == $id) {
//project
            $this->db->select('cberp_projects.*,cberp_customers.name AS customer,cberp_customers.email');
            $this->db->from('cberp_projects');
            $this->db->where('cberp_projects.id', $id);
            $this->db->where('cberp_projects.cid=', $this->session->userdata('user_details')[0]->cid);
            $this->db->where('cberp_project_meta.meta_key=', 2);
            $this->db->where('cberp_project_meta.meta_data=', 'true');
            $this->db->join('cberp_customers', 'cberp_projects.cid = cberp_customers.customer_id', 'left');
            $this->db->join('cberp_project_meta', 'cberp_project_meta.pid = cberp_projects.id', 'left');
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
            $this->db->join('cberp_invoices', 'cberp_project_meta.meta_data = cberp_invoices.id', 'left');
            $query = $this->db->get();
            $invoices = $query->result_array();

            return array('project' => $project, 'employee' => $employee, 'invoices' => $invoices);
        }

    }

    public function details($id)
    {
//project
        $this->db->select('cberp_projects.id AS prj');
        $this->db->from('cberp_projects');
        $this->db->where('cberp_projects.id', $id);
        $this->db->where('cberp_projects.cid=', $this->session->userdata('user_details')[0]->cid);
        $this->db->where('cberp_project_meta.meta_key', 2);
        $this->db->where('cberp_project_meta.meta_data=', 'true');
        $this->db->join('cberp_customers', 'cberp_projects.cid = cberp_customers.customer_id', 'left');
        $this->db->join('cberp_project_meta', 'cberp_project_meta.pid = cberp_projects.id', 'left');

        $query = $this->db->get();
        $result = $query->row_array();


        if ($result['prj'] == $id) {
            $this->db->select('cberp_projects.*,cberp_projects.id AS prj, cberp_customers.name AS customer,cberp_project_meta.*');

            $this->db->from('cberp_projects');
            $this->db->where('cberp_projects.id', $id);
            $this->db->where('cberp_projects.cid=', $this->session->userdata('user_details')[0]->cid);
            $this->db->where('cberp_project_meta.meta_key', 2);
            $this->db->join('cberp_customers', 'cberp_projects.cid = cberp_customers.customer_id', 'left');
            $this->db->join('cberp_project_meta', 'cberp_project_meta.pid = cberp_projects.id', 'left');

            $query = $this->db->get();
            return $query->row_array();
        }
    }

    private function _project_datatables_query($cday = '')
    {
        $this->db->select('cberp_projects.*,cberp_customers.name AS customer');
        $this->db->from('cberp_projects');
        $this->db->where('cberp_projects.cid=', $this->session->userdata('user_details')[0]->cid);
        $this->db->where('cberp_project_meta.meta_key=', 2);
        $this->db->where('cberp_project_meta.meta_data=', 'true');
        $this->db->join('cberp_customers', 'cberp_projects.cid = cberp_customers.customer_id', 'left');
        $this->db->join('cberp_project_meta', 'cberp_project_meta.pid = cberp_projects.id', 'left');
        if ($cday) {
            $this->db->where('DATE(cberp_projects.edate)=', $cday);
        }


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
				COUNT(IF( status = 'Waiting', id, NULL)) AS Waiting,
				COUNT(IF( status = 'Progress', id, NULL)) AS Progress,
				COUNT(IF( status = 'Finished', id, NULL)) AS Finished			
				FROM cberp_projects WHERE cid='" . $this->session->userdata('user_details')[0]->cid . "'");

        echo json_encode($query->result_array());

    }

    //project tasks

    private function _task_datatables_query($cday = '')
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

    function task_datatables($cday = '')
    {


        $this->_task_datatables_query($cday);

        if ($this->input->post('length') != -1)
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
        $this->db->where('related', 1);
        $this->db->where('rid=', $cday);
        $query = $this->db->get();
        return $query->result();
    }

    function task_count_filtered($cday = '')
    {
        $this->_task_datatables_query($cday);
        $this->db->where('related', 1);
        $this->db->where('rid=', $cday);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function task_count_all($cday = '')
    {
        $this->_task_datatables_query($cday);
        $this->db->where('related', 1);
        $this->db->where('rid=', $cday);
        $query = $this->db->get();
        return $query->num_rows();
    }

    //thread task


    public function task_thread($id)
    {

        $this->db->select('cberp_projects.id AS prj');
        $this->db->from('cberp_projects');
        $this->db->where('cberp_projects.id', $id);
        $this->db->where('cberp_projects.cid=', $this->session->userdata('user_details')[0]->cid);
        $this->db->where('cberp_project_meta.meta_key', 2);
        $this->db->where('cberp_project_meta.meta_data=', 'true');
        $this->db->join('cberp_customers', 'cberp_projects.cid = cberp_customers.customer_id', 'left');
        $this->db->join('cberp_project_meta', 'cberp_project_meta.pid = cberp_projects.id', 'left');

        $query = $this->db->get();
        $result = $query->row_array();

        if ($result['prj'] == $id) {
            $this->db->select('cberp_todolist.*, cberp_employees.name AS emp');
            $this->db->from('cberp_todolist');
            $this->db->where('cberp_todolist.related', 1);
            $this->db->where('cberp_todolist.rid', $id);
            $this->db->join('cberp_employees', 'cberp_todolist.eid = cberp_employees.id', 'left');
            $this->db->order_by('cberp_todolist.id', 'desc');
            $query = $this->db->get();
            return $query->result_array();
        }
    }


    public function milestones($id)
    {

        $this->db->select('cberp_projects.id AS prj');
        $this->db->from('cberp_projects');
        $this->db->where('cberp_projects.id', $id);
        $this->db->where('cberp_projects.cid=', $this->session->userdata('user_details')[0]->cid);
        $this->db->where('cberp_project_meta.meta_key', 2);
        $this->db->where('cberp_project_meta.meta_data=', 'true');
        $this->db->join('cberp_customers', 'cberp_projects.cid = cberp_customers.customer_id', 'left');
        $this->db->join('cberp_project_meta', 'cberp_project_meta.pid = cberp_projects.id', 'left');

        $query = $this->db->get();
        $result = $query->row_array();

        if ($result['prj'] == $id) {
            $this->db->select('*');

            $this->db->from('cberp_milestones');
            $this->db->where('pid', $id);
            $this->db->order_by('id', 'desc');
            $query = $this->db->get();
            return $query->result_array();
        }
    }

    public function milestones_list($id)
    {

        $this->db->select('cberp_projects.id AS prj');
        $this->db->from('cberp_projects');
        $this->db->where('cberp_projects.id', $id);
        $this->db->where('cberp_projects.cid=', $this->session->userdata('user_details')[0]->cid);
        $this->db->where('cberp_project_meta.meta_key', 2);
        $this->db->where('cberp_project_meta.meta_data=', 'true');
        $this->db->join('cberp_customers', 'cberp_projects.cid = cberp_customers.customer_id', 'left');
        $this->db->join('cberp_project_meta', 'cberp_project_meta.pid = cberp_projects.id', 'left');

        $query = $this->db->get();
        $result = $query->row_array();


        if ($result['prj'] == $id) {
            $query = $this->db->query('SELECT cberp_milestones.*,cberp_todolist.name as task FROM cberp_milestones LEFT JOIN cberp_project_meta ON cberp_project_meta.meta_data=cberp_milestones.id AND cberp_project_meta.meta_key=8 LEFT JOIN cberp_todolist ON cberp_project_meta.value=cberp_todolist.id WHERE cberp_milestones.pid=' . $id . ' ORDER BY cberp_milestones.id DESC;');
            return $query->result_array();
        }


    }


    public function p_files($id)
    {

        $this->db->select('cberp_projects.id AS prj');
        $this->db->from('cberp_projects');
        $this->db->where('cberp_projects.id', $id);
        $this->db->where('cberp_projects.cid=', $this->session->userdata('user_details')[0]->cid);
        $this->db->where('cberp_project_meta.meta_key', 2);
        $this->db->where('cberp_project_meta.meta_data=', 'true');
        $this->db->join('cberp_customers', 'cberp_projects.cid = cberp_customers.customer_id', 'left');
        $this->db->join('cberp_project_meta', 'cberp_project_meta.pid = cberp_projects.id', 'left');

        $query = $this->db->get();
        $result = $query->row_array();


        if ($result['prj'] == $id) {
            $this->db->select('*');
            $this->db->from('cberp_project_meta');
            $this->db->where('pid', $id);
            $this->db->where('meta_key', 9);
            $query = $this->db->get();
            return $query->result_array();
        }
    }


    //comments

    public function comments_thread($id)
    {

        $this->db->select('cberp_projects.id AS prj');
        $this->db->from('cberp_projects');
        $this->db->where('cberp_projects.id', $id);
        $this->db->where('cberp_projects.cid=', $this->session->userdata('user_details')[0]->cid);
        $this->db->where('cberp_project_meta.meta_key', 2);
        $this->db->where('cberp_project_meta.value=', 'true');
        $this->db->join('cberp_customers', 'cberp_projects.cid = cberp_customers.customer_id', 'left');
        $this->db->join('cberp_project_meta', 'cberp_project_meta.pid = cberp_projects.id', 'left');

        $query = $this->db->get();
        $result = $query->row_array();


        if ($result['prj'] == $id) {
            $this->db->select('cberp_project_meta.value, cberp_project_meta.key3,cberp_employees.name AS employee, cberp_customers.name AS customer');

            $this->db->from('cberp_project_meta');
            $this->db->where('cberp_project_meta.pid', $id);
            $this->db->where('cberp_project_meta.meta_key', 13);
            $this->db->join('cberp_employees', 'cberp_project_meta.meta_data = cberp_employees.id', 'left');
            $this->db->join('cberp_customers', 'cberp_project_meta.key3 = cberp_customers.customer_id', 'left');
            $this->db->order_by('cberp_project_meta.id', 'desc');
            $query = $this->db->get();
            return $query->result_array();
        } else {
            return false;
        }
    }

    public function add_comment($comment, $prid, $cust)
    {

        $this->db->select('cberp_projects.*,cberp_projects.id AS prj, cberp_customers.name AS customer,cberp_project_meta.*');
        $this->db->from('cberp_projects');
        $this->db->where('cberp_projects.id', $prid);
        $this->db->where('cberp_projects.cid=', $cust);
        $this->db->where('cberp_project_meta.meta_key', 2);
        $this->db->where('cberp_project_meta.value=', 'true');
        $this->db->join('cberp_customers', 'cberp_projects.cid = cberp_customers.customer_id', 'left');
        $this->db->join('cberp_project_meta', 'cberp_project_meta.pid = cberp_projects.id', 'left');

        $query = $this->db->get();
        $result = $query->row_array();

        if ($result['prj'] == $prid) {

            $data = array('pid' => $prid, 'meta_key' => 13, 'key3' => $cust, 'value' => $comment . '<br><small>@' . date('Y-m-d H:i:s') . '</small>');
            if ($prid) {
                return $this->db->insert('cberp_project_meta', $data);
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }


}
