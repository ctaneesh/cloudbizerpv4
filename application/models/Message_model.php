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

class Message_model extends CI_Model
{


    public function employee_details($id)
    {

        $this->db->select('cberp_employees.*');
        $this->db->from('cberp_employees');
        $this->db->where('cberp_pms.id', $id);
        $this->db->join('cberp_pms', 'cberp_employees.id = cberp_pms.sender_id', 'left');
        $query = $this->db->get();
        return $query->row_array();
    }
    public function unread_message_count($receiver_id)
    {
        $this->db->select('COUNT(id) as unread_count');
        $this->db->from('cberp_pms');
        $this->db->where('date_read', NULL);
        $this->db->where('receiver_id', $receiver_id);
        $query = $this->db->get();  
        // die($this->db->last_query());
        $result = $query->row_array();    
        return isset($result['unread_count']) ? $result['unread_count'] : 0;
    }
    public function unreadmsglist($limit = 5, $offset = 0, $receiver_id = NULL, $sender_id = NULL)
    {
        $this->db->select('cberp_pms.*,cberp_pms.id as msgid,cberp_pms.id AS pid,cberp_employees.*');
        $this->db->from('cberp_pms');
        if (is_numeric($receiver_id)) {
            $query = $this->db->where('cberp_pms.receiver_id', $receiver_id);
            $query = $this->db->where('cberp_pms.pm_deleted_receiver', 0);
        }
        if (is_numeric($sender_id)) {
            $query = $this->db->where('cberp_pms.sender_id', $sender_id);
            $query = $this->db->where('cberp_pms.pm_deleted_sender', 0);
        }
        $this->db->where('date_read', NULL);
        $this->db->order_by('cberp_pms.id', 'DESC');
        $this->db->join('cberp_employees', 'cberp_employees.id = cberp_pms.sender_id', 'left');
        $this->db->limit($limit, $offset);
        //	$query = $this->db->get( $this->config_vars['pms'], $limit, $offset);
        $query = $this->db->get();
        // die($this->db->last_query());
        $result = $query->result();

        if ($this->config_vars['pm_encryption']) {
            $this->CI->load->library('encrypt');

            foreach ($result as $k => $r) {
                $result[$k]->title = $this->CI->encrypt->decode($r->title);
                $result[$k]->message = $this->CI->encrypt->decode($r->message);
            }
        }

        return $result;
    }


}
