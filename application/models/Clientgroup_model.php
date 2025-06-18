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

class Clientgroup_model extends CI_Model
{


    public function details($id)
    {

        $this->db->select('*');
        $this->db->from('cberp_cust_group');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function recipients($id)
    {

        $this->db->select('id,name,email');
        $this->db->from('cberp_customers');
        $this->db->where('gid', $id);
        $query = $this->db->get();
        return $query->result_array();
    }


    public function add($group_name, $group_desc)
    {
        $data = array(
            'title' => $group_name,
            'summary' => $group_desc
        );

        if ($this->db->insert('cberp_cust_group', $data)) {
            $this->aauth->applog("[Group Created] $group_name ID " . $this->db->insert_id(), $this->aauth->get_user()->username);
            echo json_encode(array('status' => 'Success', 'message' =>
                $this->lang->line('ADDED')));
        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
                $this->lang->line('ERROR')));
        }

    }


    public function editgroupupdate($gid, $group_name, $group_desc)
    {
        $data = array(
            'title' => $group_name,
            'summary' => $group_desc
        );


        $this->db->set($data);
        $this->db->where('id', $gid);

        if ($this->db->update('cberp_cust_group')) {
            $this->aauth->applog("[Group updated] $group_name ID " . $gid, $this->aauth->get_user()->username);
            echo json_encode(array('status' => 'Success', 'message' =>
                $this->lang->line('UPDATED')));
        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
                $this->lang->line('ERROR')));
        }

    }

    public function editgroupdiscountupdate($gid, $disc_rate)
    {
        $data = array(
            'disc_rate' => $disc_rate
        );
        $this->db->set($data);
        $this->db->where('id', $gid);

        if ($this->db->update('cberp_cust_group')) {

            $data = array(
                'discount_c' => $disc_rate
            );
            $this->db->set($data);
            $this->db->where('gid', $gid);
            $this->db->update('cberp_customers');

            $this->aauth->applog("[Group discount updated] %" . $disc_rate . " GID-" . $gid, $this->aauth->get_user()->username);
            echo json_encode(array('status' => 'Success', 'message' =>
                $this->lang->line('UPDATED')));
        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
                $this->lang->line('ERROR')));
        }

    }
}
