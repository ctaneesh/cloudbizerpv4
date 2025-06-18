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

class Dashboardcrm_model extends CI_Model
{

	public function get_customers()
	{
		// 1. Get 10 recent customers
		$this->db->select('customer_id,name, status, balance');
		$this->db->from('cberp_customers');
		$this->db->order_by('customer_id', 'DESC');
		$this->db->limit(10);
		$customers = $this->db->get()->result();

		// 2. Total customers
		$total = $this->db->count_all('cberp_customers');

		// 3. Today's customers
		$this->db->where('DATE(registration_date)', date('Y-m-d'));
		$today = $this->db->count_all_results('cberp_customers');

		// 4. This month's customers
		$this->db->where('MONTH(registration_date)', date('m'));
		$this->db->where('YEAR(registration_date)', date('Y'));
		$month = $this->db->count_all_results('cberp_customers');

		// 5. This year's customers
		$this->db->where('YEAR(registration_date)', date('Y'));
		$year = $this->db->count_all_results('cberp_customers');

		// 6. Active customers
		$this->db->where('status', 'Enable');
		$active = $this->db->count_all_results('cberp_customers');

		// 7. Customers count per month (last 12 months)
		$monthlyData = [];
		for ($i = 11; $i >= 0; $i--) {
			$monthStart = date('Y-m-01', strtotime("-$i months"));
			$monthEnd = date('Y-m-t', strtotime("-$i months"));

			$this->db->where('registration_date >=', $monthStart);
			$this->db->where('registration_date <=', $monthEnd);
			$monthlyCount = $this->db->count_all_results('cberp_customers');

			$monthlyData[] = $monthlyCount;
		}		

		return [
			'customer_list' => $customers,
			'total' => $total,
			'today' => $today,
			'month' => $month,
			'year' => $year,
			'active' => $active,
			'monthly_data' => $monthlyData
		];
	}



	public function group_list_count()
	{
		// $whr = "";
		// if ($this->aauth->get_user()->loc) {
		// 	$whr = "WHERE (cberp_customers.loc=" . $this->aauth->get_user()->loc . " ) ";
		// 	if (BDATA) {
		// 		$whr = "WHERE (cberp_customers.loc=" . $this->aauth->get_user()->loc . " OR cberp_customers.loc=0 ) ";
		// 	}
		// } elseif (!BDATA) {
		// 	$whr = "WHERE  cberp_customers.loc=0  ";
		// }

		// $query = $this->db->query("
		// 	SELECT COUNT(*) AS total_groups
		// 	FROM cberp_cust_group AS c
		// 	LEFT JOIN (
		// 		SELECT gid
		// 		FROM cberp_customers
		// 		$whr
		// 		GROUP BY gid
		// 	) AS p ON p.gid = c.id
		// 	WHERE p.gid IS NOT NULL
		// ");

		// return $query->row()->total_groups;

		$query = $this->db->query("SELECT COUNT(*) AS total_groups FROM cberp_cust_group");
		$total = $query->row()->total_groups;

		return [
			'group_total' => $total
		];
	}

	public function get_enquiries()
	{
		// Get latest 10 enquiries
		$this->db->select('lead_id, lead_number, created_date, due_date, status, enquiry_status');
		$this->db->from('cberp_customer_leads');
		$this->db->where("lead_number IS NOT NULL");
		$this->db->order_by('lead_id', 'DESC');
		$this->db->limit(10);
		$query = $this->db->get();
		$enquiries = $query->result();

		// Total count
		$this->db->from('cberp_customer_leads');
		$this->db->where("lead_number IS NOT NULL");
		$total = $this->db->count_all_results();

		// Today's leads
		$today = date('Y-m-d');
		$this->db->from('cberp_customer_leads');
		$this->db->where("DATE(created_date)", $today);
		$today_count = $this->db->count_all_results();

		// This week's leads (from Monday)
		$this->db->from('cberp_customer_leads');
		$this->db->where("YEARWEEK(created_date, 1) = YEARWEEK(CURDATE(), 1)");
		$week_count = $this->db->count_all_results();

		// This month's leads
		$this->db->from('cberp_customer_leads');
		$this->db->where("MONTH(created_date)", date('m'));
		$this->db->where("YEAR(created_date)", date('Y'));
		$month_count = $this->db->count_all_results();

		// This quarter's leads
		$quarter_months = [
			1 => [1, 2, 3],
			2 => [4, 5, 6],
			3 => [7, 8, 9],
			4 => [10, 11, 12]
		];
		$current_month = (int)date('n');
		$current_quarter = ceil($current_month / 3);
		$months = $quarter_months[$current_quarter];
		$this->db->from('cberp_customer_leads');
		$this->db->where_in("MONTH(created_date)", $months);
		$this->db->where("YEAR(created_date)", date('Y'));
		$quarter_count = $this->db->count_all_results();

		// This year's leads
		$this->db->from('cberp_customer_leads');
		$this->db->where("YEAR(created_date)", date('Y'));
		$year_count = $this->db->count_all_results();

		return [
			'enquiries' => $enquiries,
			'total' => $total,
			'today' => $today_count,
			'week' => $week_count,
			'month' => $month_count,
			'quarter' => $quarter_count,
			'year' => $year_count
		];
	}


	public function get_enquiry_count($ranges)
	{
        $today = date('Y-m-d');
        $startMonth    = $ranges['month'];
        $startWeek     = $ranges['week'];
        $startQuarter  = $ranges['quarter'];
        $startYear     = $ranges['year'];
        $query = $this->db->query("
            SELECT 
                -- Total enquiry counts
                SUM(CASE WHEN created_date BETWEEN '$startYear' AND '$today' THEN 1 ELSE 0 END) AS yearly_count,
                SUM(CASE WHEN created_date BETWEEN '$startQuarter' AND '$today' THEN 1 ELSE 0 END) AS quarterly_count,
                SUM(CASE WHEN created_date BETWEEN '$startMonth' AND '$today' THEN 1 ELSE 0 END) AS monthly_count,
                SUM(CASE WHEN created_date BETWEEN '$startWeek' AND '$today' THEN 1 ELSE 0 END) AS weekly_count,
                SUM(CASE WHEN DATE(created_date) = '$today' THEN 1 ELSE 0 END) AS daily_count,

                -- 'Completed' status
                SUM(CASE WHEN enquiry_status = 'Completed' AND created_date BETWEEN '$startYear' AND '$today' THEN 1 ELSE 0 END) AS yearly_assigned_count,
                SUM(CASE WHEN enquiry_status = 'Completed' AND created_date BETWEEN '$startQuarter' AND '$today' THEN 1 ELSE 0 END) AS quarterly_assigned_count,
                SUM(CASE WHEN enquiry_status = 'Completed' AND created_date BETWEEN '$startMonth' AND '$today' THEN 1 ELSE 0 END) AS monthly_assigned_count,
                SUM(CASE WHEN enquiry_status = 'Completed' AND created_date BETWEEN '$startWeek' AND '$today' THEN 1 ELSE 0 END) AS weekly_assigned_count,
                SUM(CASE WHEN enquiry_status = 'Completed' AND DATE(created_date) = '$today' THEN 1 ELSE 0 END) AS daily_assigned_count,

                -- 'Open' status
                SUM(CASE WHEN enquiry_status = 'Open' AND created_date BETWEEN '$startYear' AND '$today' THEN 1 ELSE 0 END) AS yearly_open_count,
                SUM(CASE WHEN enquiry_status = 'Open' AND created_date BETWEEN '$startQuarter' AND '$today' THEN 1 ELSE 0 END) AS quarterly_open_count,
                SUM(CASE WHEN enquiry_status = 'Open' AND created_date BETWEEN '$startMonth' AND '$today' THEN 1 ELSE 0 END) AS monthly_open_count,
                SUM(CASE WHEN enquiry_status = 'Open' AND created_date BETWEEN '$startWeek' AND '$today' THEN 1 ELSE 0 END) AS weekly_open_count,
                SUM(CASE WHEN enquiry_status = 'Open' AND DATE(created_date) = '$today' THEN 1 ELSE 0 END) AS daily_open_count,

                -- 'Closed' status
                SUM(CASE WHEN enquiry_status = 'Closed' AND created_date BETWEEN '$startYear' AND '$today' THEN 1 ELSE 0 END) AS yearly_closed_count,
                SUM(CASE WHEN enquiry_status = 'Closed' AND created_date BETWEEN '$startQuarter' AND '$today' THEN 1 ELSE 0 END) AS quarterly_closed_count,
                SUM(CASE WHEN enquiry_status = 'Closed' AND created_date BETWEEN '$startMonth' AND '$today' THEN 1 ELSE 0 END) AS monthly_closed_count,
                SUM(CASE WHEN enquiry_status = 'Closed' AND created_date BETWEEN '$startWeek' AND '$today' THEN 1 ELSE 0 END) AS weekly_closed_count,
                SUM(CASE WHEN enquiry_status = 'Closed' AND DATE(created_date) = '$today' THEN 1 ELSE 0 END) AS daily_closed_count,

                -- 'Draft' status
                SUM(CASE WHEN enquiry_status = 'Draft' AND created_date BETWEEN '$startYear' AND '$today' THEN 1 ELSE 0 END) AS yearly_draft_count,
                SUM(CASE WHEN enquiry_status = 'Draft' AND created_date BETWEEN '$startQuarter' AND '$today' THEN 1 ELSE 0 END) AS quarterly_draft_count,
                SUM(CASE WHEN enquiry_status = 'Draft' AND created_date BETWEEN '$startMonth' AND '$today' THEN 1 ELSE 0 END) AS monthly_draft_count,
                SUM(CASE WHEN enquiry_status = 'Draft' AND created_date BETWEEN '$startWeek' AND '$today' THEN 1 ELSE 0 END) AS weekly_draft_count,
                SUM(CASE WHEN enquiry_status = 'Draft' AND DATE(created_date) = '$today' THEN 1 ELSE 0 END) AS daily_draft_count,

                -- Total amounts
                SUM(CASE WHEN created_date BETWEEN '$startYear' AND '$today' THEN total ELSE 0 END) AS yearly_total,
                SUM(CASE WHEN created_date BETWEEN '$startQuarter' AND '$today' THEN total ELSE 0 END) AS quarterly_total,
                SUM(CASE WHEN created_date BETWEEN '$startMonth' AND '$today' THEN total ELSE 0 END) AS monthly_total,
                SUM(CASE WHEN created_date BETWEEN '$startWeek' AND '$today' THEN total ELSE 0 END) AS weekly_total,
                SUM(CASE WHEN DATE(created_date) = '$today' THEN total ELSE 0 END) AS daily_total

            FROM cberp_customer_leads
            WHERE lead_number IS NOT NULL
        ");

        return $query->row();
    }

	public function get_support_ticket_count()
    {

        // $this->db->from('cberp_tickets');
        // if ($filt == 'unsolved') {
        //     $this->db->where('status!=', 'Solved');
        // }
        // $i = 0;
        // foreach ($this->doccolumn_search as $item) // loop column
        // {
        //     $search = $this->input->post('search');
        //     $value = $search['value'];
        //     if ($value) {

        //         if ($i === 0) {
        //             $this->db->group_start();
        //             $this->db->like($item, $value);
        //         } else {
        //             $this->db->or_like($item, $value);
        //         }

        //         if (count($this->doccolumn_search) - 1 == $i) //last loop
        //             $this->db->group_end(); //close bracket
        //     }
        //     $i++;
        // }
        // $search = $this->input->post('order');
        // if ($search) {
        //     $this->db->order_by($this->doccolumn_order[$search['0']['column']], $search['0']['dir']);
        // } else if (isset($this->order)) {
        //     $order = $this->order;
        //     $this->db->order_by(key($order), $order[key($order)]);
        // }
		// $query = $this->db->get();		
        // $res = $query->num_rows();
		// print_r($res);
		// exit();

		$query = $this->db->query("
            SELECT
                COUNT(IF(status = 'Waiting', id, NULL)) AS Waiting,
                COUNT(IF(status = 'Processing', id, NULL)) AS Processing,
                COUNT(IF(status = 'Solved', id, NULL)) AS Solved
            FROM cberp_tickets
        ");
        
        $res = $query->row_array();

        $total = (int)$res['Waiting'] + (int)$res['Processing'] + (int)$res['Solved'];
		$values = [(int)$res['Waiting'], (int)$res['Processing'], (int)$res['Solved']];
		$labels = ['Waiting', 'Processing', 'Solved'];
		$percentages = array_map(function ($val) use ($total) {
			return $total > 0 ? round(($val / $total) * 100, 2) : 0;
		}, $values);

		return [
			'labels' => $labels,
			'values' => $values,
			'total' => $total,
			'percentages' => $percentages
		];


    }

	public function get_suppliers(){

		$this->db->select('supplier_id,name, email, phone');
		$this->db->from('cberp_suppliers');
		$this->db->order_by('supplier_id', 'DESC');
		$this->db->limit(10);
		$suppliers = $this->db->get()->result();
		
		$total = $this->db->count_all('cberp_suppliers');
		
		$monthlyData = [];
		for ($i = 11; $i >= 0; $i--) {
			$monthStart = date('Y-m-01', strtotime("-$i months"));
			$monthEnd = date('Y-m-t', strtotime("-$i months"));

			$this->db->where('created_date >=', $monthStart);
			$this->db->where('created_date <=', $monthEnd);
			$monthlyCount = $this->db->count_all_results('cberp_suppliers');

			$monthlyData[] = $monthlyCount;
		}		

		return [
			'supplier_list' => $suppliers,
			'total' => $total,
			'monthly_data' => $monthlyData
		];

	}




	


}
