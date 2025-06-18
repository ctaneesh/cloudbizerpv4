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

class Tools_model extends CI_Model
{

    var $column_order = array(null,'cberp_todolist.created_date_time', 'cberp_todolist.name', 'duedate', 'tdate', null, null);
    var $column_search = array('cberp_todolist.created_date_time','cberp_todolist.name', 'cberp_todolist.duedate', 'cberp_todolist.tdate','cberp_todolist.description','cberp_employees.name');
    var $notecolumn_order = array(null, 'title', 'cdate', null);
    var $notecolumn_search = array('id', 'title', 'cdate');
    var $order = array('cberp_todolist.created_date_time' => 'DESC');
    private function _task_datatables_query($cday = '')
    {

        // $this->db->from('cberp_todolist');
       

        $this->db->select('cberp_todolist.*,cberp_employees.name AS emp, assi.name AS assign');
        $this->db->from('cberp_todolist');
        if ($cday) {
            $this->db->where('DATE(duedate)=', $cday);
        }
        $this->db->join('cberp_employees', 'cberp_employees.id = cberp_todolist.eid', 'left');
        $this->db->join('cberp_employees AS assi', 'assi.id = cberp_todolist.aid', 'left');


        $i = 0;

        foreach ($this->column_search as $item) // loop column
        {
            $search = $this->input->post('search');

            if ($search) {
                $value = $search['value'];

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
        // print_r($this->input->post('order'));
        if ($search) // here order processing
        {
            if($this->column_order[$search['0']['column']])
            {
                $this->db->order_by($this->column_order[$search['0']['column']], $search['0']['dir']);
            }
            else
            {
                $order = $this->order;
                $this->db->order_by(key($order), $order[key($order)]);
            }
            
            
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
        // $order = $this->order;
        // $this->db->order_by(key($order), $order[key($order)]);
    }

    function task_datatables($cday = '')
    {


        $this->_task_datatables_query($cday);

        if ($this->input->post('length') != -1)
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
        $query = $this->db->get();
        // die();
        // die($this->db->last_query());
        return $query->result();
    }

    function task_count_filtered($cday = '')
    {
        $this->_task_datatables_query($cday);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function task_count_all($cday = '')
    {
        $this->_task_datatables_query($cday);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function addtask($name, $status, $priority, $stdate, $tdate, $employee, $assign, $content, $created_date_time)
    {

        $data = array('tdate' => date('Y-m-d H:i:s'), 'name' => $name, 'status' => $status, 'start' => $stdate, 'duedate' => $tdate, 'description' => $content, 'eid' => $employee, 'aid' => $assign, 'related' => 0, 'priority' => $priority, 'rid' => 0,'created_date_time'=>$created_date_time);
        $this->db->insert('cberp_todolist', $data);
        return $this->db->insert('cberp_todolist', $data);
    }

    public function edittask($id, $name, $status, $priority, $stdate, $tdate, $employee, $content)
    {

        $data = array('tdate' => date('Y-m-d H:i:s'), 'name' => $name, 'status' => $status, 'start' => $stdate, 'duedate' => $tdate, 'description' => $content, 'eid' => $employee, 'related' => 0, 'priority' => $priority, 'rid' => 0);
        $this->db->set($data);
        $this->db->where('id', $id);
        return $this->db->update('cberp_todolist');
        //return $this->db->insert('cberp_todolist', $data);
    }

    public function settask($id, $stat)
    {

        $data = array('status' => $stat);
        $this->db->set($data);
        $this->db->where('id', $id);
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


    public function task_stats()
    {

        $query = $this->db->query("SELECT
				COUNT(IF( status = 'Due', id, NULL)) AS Due,
				COUNT(IF( status = 'Progress', id, NULL)) AS Progress,
				COUNT(IF( status = 'Done', id, NULL)) AS Done
				FROM cberp_todolist ");

        echo json_encode($query->result_array());

    }

    //goals

    public function goals($id)
    {

        $this->db->select('*');
        $this->db->from('cberp_goals');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function setgoals($income, $expense, $sales, $netincome)
    {


        $data = array('income' => $income, 'expense' => $expense, 'sales' => $sales, 'netincome' => $netincome);
        $this->db->set($data);
        $this->db->where('id', 1);
        return $this->db->update('cberp_goals');
    }

    //notes

    private function _notes_datatables_query()
    {

        $this->db->from('cberp_notes');
        $this->db->where('ntype', 0);
        $i = 0;

        foreach ($this->notecolumn_search as $item) // loop column
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
            $this->db->order_by($this->notecolumn_order[$search['0']['column']], $search['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function notes_datatables()
    {
        $this->_notes_datatables_query();
        if ($this->input->post('length') != -1)
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
        $query = $this->db->get();
        return $query->result();
    }

    function notes_count_filtered()
    {
        $this->_notes_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function notes_count_all()
    {
        $this->_notes_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }


    function addnote($title, $content)
    {
        $data = array('title' => $title, 'content' => $content, 'cdate' => date('Y-m-d'), 'last_edit' => date('Y-m-d H:i:s'), 'cid' => $this->aauth->get_user()->id, 'fid' => $this->aauth->get_user()->id, 'ntype' => 0);
        return $this->db->insert('cberp_notes', $data);

    }

    public function note_v($id)
    {
        $this->db->select('*');
        $this->db->from('cberp_notes');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    function deletenote($id)
    {
        return $this->db->delete('cberp_notes', array('id' => $id));

    }


    //documents list

    var $doccolumn_order = array(null, 'title', 'cdate', null);
    var $doccolumn_search = array('title', 'cdate');

    public function documentlist()
    {
        $this->db->select('*');
        $this->db->from('cberp_documents');
        $query = $this->db->get();
        return $query->result_array();
    }

    function adddocument($title, $filename)
    {
        $data = array('title' => $title, 'filename' => $filename, 'cdate' => date('Y-m-d'));
        return $this->db->insert('cberp_documents', $data);

    }

    function deletedocument($id)
    {
        $this->db->select('filename');
        $this->db->from('cberp_documents');
        $this->db->where('id', $id);
        $query = $this->db->get();
        $result = $query->row_array();
        if ($this->db->delete('cberp_documents', array('id' => $id))) {

            unlink(FCPATH . 'userfiles/documents/' . $result['filename']);
            return true;
        } else {
            return false;
        }

    }


    function document_datatables()
    {
        $this->document_datatables_query();
        if ($this->input->post('length') != -1)
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
        $query = $this->db->get();
        return $query->result();
    }

    private function document_datatables_query()
    {

        $this->db->from('cberp_documents');

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
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function document_count_filtered()
    {
        $this->document_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function document_count_all()
    {
        $this->document_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function pending_tasks()
    {
        $this->db->select('*');
        $this->db->from('cberp_todolist');
        $this->db->where('status', 'Due');
        $this->db->order_by('DATE(duedate)', 'ASC');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    public function pending_tasks_user($id)
    {
        $this->db->select('*');
        $this->db->from('cberp_todolist');
        $this->db->where('status', 'Due');
        $this->db->where('eid', $id);
        $this->db->order_by('DATE(duedate)', 'ASC');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }


    public function editnote($id, $title, $content)
    {
        $data = array(
            'title' => $title,
            'content' => $content

        );

        $data = array('title' => $title, 'content' => $content, 'last_edit' => date('Y-m-d H:i:s'), 'fid' => $this->aauth->get_user()->id);


        $this->db->set($data);
        $this->db->where('id', $id);

        if ($this->db->update('cberp_notes')) {
            return true;
        } else {
            return false;
        }

    }


}
