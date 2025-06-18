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


class Printer_model extends CI_Model
{
    var $table = 'cberp_config';

    public function __construct()
    {
        parent::__construct();
    }

    public function printers_list()
    {
        $this->db->select('*');
        $this->db->from('cberp_config');
        $this->db->where('type', 1);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function printer_details($id)
    {
        $this->db->select('*');
        $this->db->from('cberp_config');
        $this->db->where('id', $id);
        $this->db->where('type', 1);
        $query = $this->db->get();
        return $query->row_array();
    }


    public function create($p_name, $p_type, $p_connect, $lid, $mode)
    {
        $data = array(
            'type' => 1,
            'val1' => $p_name,
            'val2' => $p_type,
            'val3' => $p_connect,
            'val4' => $lid,
            'other' => $mode
        );
        if ($this->db->insert('cberp_config', $data)) {
            echo json_encode(array('status' => 'Success', 'message' =>
                $this->lang->line('ADDED')));
        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
                $this->lang->line('ERROR')));
        }
    }

    public function edit($id, $p_name, $p_type, $p_connect, $lid, $mode)
    {
        $data = array(
            'type' => 1,
            'val1' => $p_name,
            'val2' => $p_type,
            'val3' => $p_connect,
            'val4' => $lid,
            'other' => $mode
        );


        $this->db->set($data);
        $this->db->where('id', $id);

        if ($this->db->update('cberp_config')) {
            echo json_encode(array('status' => 'Success', 'message' =>
                $this->lang->line('UPDATED')));
        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
                $this->lang->line('ERROR')));
        }

    }

    public function print_settings_list()
    {
        $this->db->select('*');
        $this->db->from('cberp_print_settings');
        $this->db->where('printing_type','Pre-Print');
        $query = $this->db->get();
        return $query->row_array();
    }


}
