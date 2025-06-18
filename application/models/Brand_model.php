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

class Brand_model extends CI_Model
{


    public function brand_list()
    {
        
        $query = $this->db->query("SELECT id,brand_name as title,status
        FROM cberp_brands
        ORDER BY id DESC");
        // die($this->db->last_query());
        return $query->result_array();
    }

    public function brand_list_by_id($id)
    {
        $this->db->select('*');
        $this->db->from('cberp_brands');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }


}
