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

class Templates_model extends CI_Model
{


    /*Read the data from DB */
    public function get_template($start, $end)
    {
        $where = "id BETWEEN $start AND $end";
        $this->db->from('univarsal_api');
        $this->db->where($where);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function template_info($id)
    {
        $this->db->from('univarsal_api');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }


    public function edit($id, $subect, $body)
    {
        $data = array(
            'key1' => $subect,
            'other' => $body
        );

        $this->db->set($data);
        $this->db->where('id', $id);

        if ($this->db->update('univarsal_api')) {
            echo json_encode(array('status' => 'Success', 'message' =>
                $this->lang->line('UPDATED')));
        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
                $this->lang->line('ERROR')));
        }
    }


}
