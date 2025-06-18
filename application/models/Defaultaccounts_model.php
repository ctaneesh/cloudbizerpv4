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

class Defaultaccounts_model extends CI_Model
{
    var $table = 'cberp_coa_types';
    var $column_order = array(null,'coa_type_id','typename','coa_header_id','status', null);
    var $column_search = array('coa_type_id','typename','coa_header_id','status');
    var $order = array('id' => 'desc');

    public function __construct()
    {
        parent::__construct();
    }


    public function load_default_accounts()
    {
        $this->db->from('cberp_default_double_entry_accounts');
        $this->db->where('id', 1);
        $query = $this->db->get();
        $result = $query->row_array();
        return $result;
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
    public function insert_to_log()
    {
        $userid = $this->session->userdata('id');
        $dt = date('Y-m-d H:i:s');
        $sql = "INSERT INTO cberp_default_double_entry_accounts_log (accounts_receivable,accounts_payable,sales,general_expenses,sales_discount,order_discount,shipping,purchase_account,purchase_discount,owners_contribution,inventory,cost_of_goods_solid,sales_returns,product_income,product_expense,costing_account,damage_account,modified_by,modified_dt)
            SELECT accounts_receivable,accounts_payable,sales,general_expenses,sales_discount,order_discount,shipping,purchase_account,purchase_discount,owners_contribution,inventory,cost_of_goods_solid,sales_returns,product_income,product_expense,costing_account,damage_account,'$userid','$dt'
            FROM cberp_default_double_entry_accounts";
        $this->db->query($sql);

    }
    public function last_record()
    {
        $this->db->select('*');
        $this->db->from('cberp_default_double_entry_accounts_log');
        $this->db->order_by('log_id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->row_array();
    }


}
