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

class Authorizationapproval_model extends CI_Model
{
    var $table = 'authorization_history';
    var $column_order = array(null, 'requester.id', 'requester.name','accepter.name', 'requester.reportingto', 'requester.amount_limit', 'authorization_history.requested_amount', null);
    var $column_search = array('requester.name','accepter.name', 'requester.reportingto', 'requester.amount_limit','authorization_history.requested_amount',);
    var $order = array('authorization_history.id' => 'desc');

    public function __construct()
    {
        parent::__construct();
    }

    public function lastquote()
    {
        $this->db->select('tid');
        $this->db->from($this->table);
        $this->db->order_by('tid', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row()->tid;
        } else {
            return 1000;
        }
    }

    public function warehouses()
    {
        $this->db->select('*');
        $this->db->from('cberp_store');       
        $query = $this->db->get();
        return $query->result_array();

    }


    private function _get_datatables_query($eid)
    {


        $this->db->select('requester.name AS Requester,requester.id AS requestedid, accepter.name AS Accepter, requester.reportingto, requester.amount_limit,requester.id, authorization_history.*');
        $this->db->from($this->table);

        // if ($eid) $this->db->where('cberp_quotes.eid', $eid);
        //         if ($this->aauth->get_user()->loc) {
        //     $this->db->where('cberp_quotes.loc', $this->aauth->get_user()->loc);
        // }
        // elseif(!BDATA) { $this->db->where('cberp_quotes.loc', 0); }
       
        if ($this->input->post('start_date') && $this->input->post('end_date'))
        {
            $start_date = datefordatabase($this->input->post('start_date'));
            $end_date = datefordatabase($this->input->post('end_date'));
            $this->db->where("DATE(authorization_history.requested_date) BETWEEN '$start_date' AND '$end_date'");
        }

        
        $this->db->join('cberp_employees AS requester', 'requester.id = authorization_history.requested_by');
        $this->db->join('cberp_employees AS accepter', 'accepter.id = requester.reportingto');
        // $this->db->where("requester.reportingto", $this->session->userdata('id'));
        $this->db->group_start();
        $this->db->where('requester.reportingto', $this->session->userdata('id'));
        $this->db->or_where('requester.id', $this->session->userdata('id'));
        $this->db->group_end();
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

    function get_datatables($eid)
    {
        $this->_get_datatables_query($eid);
        if ($_POST['length'] != -1){
            $this->db->limit($_POST['length'], $_POST['start']);
        }           
        $query = $this->db->get();
        // print_r($this->db->last_query()); die();
        return $query->result();
    }

    function count_filtered($eid)
    {
        $this->_get_datatables_query($eid);
    // if ($this->aauth->get_user()->loc) {
    //         $this->db->where('cberp_quotes.loc', $this->aauth->get_user()->loc);
    //     }  elseif(!BDATA) { $this->db->where('cberp_quotes.loc', 0); }

        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all($eid)
    {
        $this->db->select('authorization_history.id');
        // $this->db->join('cberp_employees', 'cberp_employees.id=authorization_history.requested_by');
        // $this->db->where("cberp_employees.reportingto", $this->session->userdata('id'));
        $this->db->from($this->table);

        //  if ($this->aauth->get_user()->loc) {
        //     $this->db->where('cberp_quotes.loc', $this->aauth->get_user()->loc);
        // }  elseif(!BDATA) { $this->db->where('cberp_quotes.loc', 0); }

        if ($eid) $this->db->where('authorization_history.id', $eid);
        return $this->db->count_all_results();
    }


    public function amount_limit($id)
    {
        $this->db->select('cberp_employees.amount_limit');
        $this->db->from('cberp_employees');
        $this->db->where('cberp_employees.id', $id);
        $query = $this->db->get();
        $result = $query->row_array();
        
        // print_r($result); die();
        if(!empty($result) && $result['amount_limit']>0){
            return $result['amount_limit'];
        }
        else{
            return 0;
        }
    }

    public function approved_person($id,$type){
        $sql = "
            SELECT `cberp_employees`.`name`
            FROM `cberp_employees`
            WHERE `cberp_employees`.`id` IN (
                SELECT `cberp_employees`.`reportingto`
                FROM `cberp_employees`
                JOIN `authorization_history`
                ON `cberp_employees`.`id` = `authorization_history`.`requested_by`
                WHERE `authorization_history`.`function_id` = '$id'
                AND `authorization_history`.`function_type` = '$type'
            )";

        $query = $this->db->query($sql);

        // Fetch the result
        
        return $query->row_array();
    }
 

    public function authorization_details_byid($id,$type)
    {
        $this->db->select('cberp_employees.name, cberp_employees.reportingto, cberp_employees.amount_limit,authorization_history.*');
        $this->db->from('authorization_history');
        $this->db->join('cberp_employees', 'cberp_employees.id = authorization_history.requested_by');
        $this->db->where('authorization_history.function_id', $id);
        $this->db->where('authorization_history.function_type', $type);
        $query = $this->db->get();
        // print_r($this->db->last_query()); die();
        return $query->row_array();

    }
    
}
