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


class Restapi_model extends CI_Model
{
    var $table = 'cberp_accounts';

    public function __construct()
    {
        parent::__construct();
    }

    public function keylist()
    {
        $this->db->select('*');
        $this->db->from('cberp_restkeys');
        $query = $this->db->get();
        return $query->result_array();
    }


    public function addnew()
    {

        $random = substr(md5(mt_rand()), 0, 24);
        $data = array(
            'user_id' => 0,
            'key' => $random,
            'level' => 0,
            'date_created' => date('Y-m-d')


        );

        if ($this->db->insert('cberp_restkeys', $data)) {
            return true;
        } else {
            return false;

        }

    }


}
