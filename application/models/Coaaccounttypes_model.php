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

class Coaaccounttypes_model extends CI_Model
{
    var $table = 'cberp_coa_types';
    var $column_order = array(null,'coa_type_id','typename','coa_header_id','status', null);
    var $column_search = array('coa_type_id','typename','coa_header_id','status');
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
    public function load_coa_account_types()
    {
        $this->db->select('id,typename,coa_header_id');
        $this->db->from('cberp_coa_types');
        $this->db->where('status', 'Active');
        $this->db->order_by('typename', 'ASC'); 
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }
    public function create($price_perc, $selling_price_perc, $whole_price_perc, $web_price_perc)
    {
        $data = array(
            'price_perc' => $price_perc,
            'selling_price_perc' => $selling_price_perc,
            'whole_price_perc' => $whole_price_perc,
            'web_price_perc' => $web_price_perc,
        );
        if ($this->db->insert('cberp_coa_types', $data)) {
            echo json_encode(array('status' => 'Success', 'message' =>  $this->lang->line('ADDED') . ' <a href="' . base_url('productpricing') . '" class="btn btn-blue btn-sm"><span class="fa fa-eye" aria-hidden="true"></span> </a>' ));
        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
                $this->lang->line('ERROR')));
        }

    }
    public function edit($id, $price_perc, $selling_price_perc, $whole_price_perc, $web_price_perc)
    {
        $data = array(
            'price_perc' => $price_perc,
            'selling_price_perc' => $selling_price_perc,
            'whole_price_perc' => $whole_price_perc,
            'web_price_perc' => $web_price_perc,
        );

        $this->db->set($data);
        $this->db->where('id', $id);

        if ($this->db->update('cberp_coa_types')) {
            echo json_encode(array('status' => 'Success', 'message' =>  $this->lang->line('UPDATED') . ' <a href="' . base_url('productpricing') . '" class="btn btn-blue btn-sm"><span class="fa fa-eye" aria-hidden="true"></span> </a>' ));
        } else {
            echo json_encode(array('status' => 'Error', 'message' => $this->lang->line('ERROR')));
        }

        

    }

    private function _get_datatables_query()
    {

        $this->db->select('cberp_coa_types.*,cberp_coa_headers.coa_header');
        $this->db->from('cberp_coa_types');
        $this->db->join('cberp_coa_headers', 'cberp_coa_headers.coa_header_id = cberp_coa_types.coa_header_id');
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


}
