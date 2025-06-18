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

class Dashboardsales_model extends CI_Model
{

	public function sales_list_count()
	{		
		$this->db->select('salesorder_number,salesorder_date,total');
		$this->db->from('cberp_sales_orders');
		$this->db->order_by('created_date', 'DESC');
		$this->db->limit(10);
		$sales = $this->db->get()->result();
		
		$total = $this->db->count_all('cberp_sales_orders');		

		return [
			'sales_list' => $sales,
			 'total' => $total,
		];
	}

	public function quotes_list_count()
	{		
		$this->db->select('quote_number,quote_date,total');
		$this->db->from('cberp_quotes');
		$this->db->order_by('created_date', 'DESC');
		$this->db->limit(10);
		$invoices = $this->db->get()->result();
		
		$total = $this->db->count_all('cberp_quotes');		

		return [
			'quote_list' => $invoices,
			 'total' => $total,
		];
	}

	public function purchase_request_list_count() {
		// Get the latest 10 purchase requests with product info
		$this->db->select('product_request.id, cberp_products.product_name, product_request.requested_qty, product_request.requested_status');
		$this->db->from('product_request');
		//$this->db->join('cberp_warehouse AS warehouse_from_warehouse', 'warehouse_from_warehouse.id = product_request.warehouse_from_id');
		$this->db->join('cberp_products', 'cberp_products.pid = product_request.product_id', 'left');
		//$this->db->join('cberp_employees', 'cberp_employees.id = product_request.requested_by');
		$this->db->order_by('product_request.id', 'DESC');
		$this->db->limit(10);
		$purch_rqst = $this->db->get()->result();

		// Get total count of purchase requests
		$total = $this->db->from('product_request')->count_all_results();

		return [
			'purch_rqst_list' => $purch_rqst,
			'total' => $total
		];
    }

	public function delivery_notes_list_count(){
		$this->db->select('delevery_note_id,delivery_note_number,deliveryduedate,total_amount,status');
		$this->db->from('delivery_note_m');
		$this->db->order_by('delevery_note_id', 'DESC');
		$this->db->limit(10);
		$deliverynotes = $this->db->get()->result();
		
		$total = $this->db->count_all('delivery_note_m');		

		return [
			'delivery_notes_list' => $deliverynotes,
			 'total' => $total,
		];
	}


	
	public function get_sales_overview_count($ranges)
    {
        $today = date('Y-m-d');
        $startMonth    = $ranges['month'];
        $startWeek     = $ranges['week'];
        $startQuarter  = $ranges['quarter'];
        $startYear     = $ranges['year'];

        $query = $this->db->query("
            SELECT 
                -- Total counts
                SUM(CASE WHEN invoicedate BETWEEN '$startYear' AND '$today' THEN 1 ELSE 0 END) AS yearly_count,
                SUM(CASE WHEN invoicedate BETWEEN '$startQuarter' AND '$today' THEN 1 ELSE 0 END) AS quarterly_count,
                SUM(CASE WHEN invoicedate BETWEEN '$startMonth' AND '$today' THEN 1 ELSE 0 END) AS monthly_count,
                SUM(CASE WHEN invoicedate BETWEEN '$startWeek' AND '$today' THEN 1 ELSE 0 END) AS weekly_count,
                SUM(CASE WHEN DATE(invoicedate) = '$today' THEN 1 ELSE 0 END) AS daily_count,

                -- 'pending' status
                SUM(CASE WHEN (status = 'pending' || status = 'invoiced') AND invoicedate BETWEEN '$startYear' AND '$today' THEN 1 ELSE 0 END) AS yearly_created_count,
                SUM(CASE WHEN (status = 'pending' || status = 'invoiced') AND invoicedate BETWEEN '$startQuarter' AND '$today' THEN 1 ELSE 0 END) AS quarterly_created_count,
                SUM(CASE WHEN (status = 'pending' || status = 'invoiced') AND invoicedate BETWEEN '$startMonth' AND '$today' THEN 1 ELSE 0 END) AS monthly_created_count,
                SUM(CASE WHEN (status = 'pending' || status = 'invoiced') AND invoicedate BETWEEN '$startWeek' AND '$today' THEN 1 ELSE 0 END) AS weekly_created_count,
                SUM(CASE WHEN (status = 'pending' || status = 'invoiced') AND DATE(invoicedate) = '$today' THEN 1 ELSE 0 END) AS daily_created_count,

                -- 'draft' status
                SUM(CASE WHEN status = 'draft' AND invoicedate BETWEEN '$startYear' AND '$today' THEN 1 ELSE 0 END) AS yearly_draft_count,
                SUM(CASE WHEN status = 'draft' AND invoicedate BETWEEN '$startQuarter' AND '$today' THEN 1 ELSE 0 END) AS quarterly_draft_count,
                SUM(CASE WHEN status = 'draft' AND invoicedate BETWEEN '$startMonth' AND '$today' THEN 1 ELSE 0 END) AS monthly_draft_count,
                SUM(CASE WHEN status = 'draft' AND invoicedate BETWEEN '$startWeek' AND '$today' THEN 1 ELSE 0 END) AS weekly_draft_count,
                SUM(CASE WHEN status = 'draft' AND DATE(invoicedate) = '$today' THEN 1 ELSE 0 END) AS daily_draft_count,

                -- Totals
                SUM(CASE WHEN invoicedate BETWEEN '$startYear' AND '$today' THEN total ELSE 0 END) AS yearly_total,
                SUM(CASE WHEN invoicedate BETWEEN '$startQuarter' AND '$today' THEN total ELSE 0 END) AS quarterly_total,
                SUM(CASE WHEN invoicedate BETWEEN '$startMonth' AND '$today' THEN total ELSE 0 END) AS monthly_total,
                SUM(CASE WHEN invoicedate BETWEEN '$startWeek' AND '$today' THEN total ELSE 0 END) AS weekly_total,
                SUM(CASE WHEN DATE(invoicedate) = '$today' THEN total ELSE 0 END) AS daily_total

            FROM cberp_sales_orders
            WHERE salesorder_number IS NOT NULL
        ");

        return $query->row();
    }
	
	public function get_quote_overview_count($ranges)
    {
        $today = date('Y-m-d');
        $startMonth    = $ranges['month'];
        $startWeek     = $ranges['week'];
        $startQuarter  = $ranges['quarter'];
        $startYear     = $ranges['year'];

        $query = $this->db->query("
            SELECT 
                -- Total counts
                SUM(CASE WHEN invoicedate BETWEEN '$startYear' AND '$today' THEN 1 ELSE 0 END) AS yearly_count,
                SUM(CASE WHEN invoicedate BETWEEN '$startQuarter' AND '$today' THEN 1 ELSE 0 END) AS quarterly_count,
                SUM(CASE WHEN invoicedate BETWEEN '$startMonth' AND '$today' THEN 1 ELSE 0 END) AS monthly_count,
                SUM(CASE WHEN invoicedate BETWEEN '$startWeek' AND '$today' THEN 1 ELSE 0 END) AS weekly_count,
                SUM(CASE WHEN DATE(invoicedate) = '$today' THEN 1 ELSE 0 END) AS daily_count,

                -- 'Assigned' status
                SUM(CASE WHEN status = 'Assigned' AND invoicedate BETWEEN '$startYear' AND '$today' THEN 1 ELSE 0 END) AS yearly_assigned_count,
                SUM(CASE WHEN status = 'Assigned' AND invoicedate BETWEEN '$startQuarter' AND '$today' THEN 1 ELSE 0 END) AS quarterly_assigned_count,
                SUM(CASE WHEN status = 'Assigned' AND invoicedate BETWEEN '$startMonth' AND '$today' THEN 1 ELSE 0 END) AS monthly_assigned_count,
                SUM(CASE WHEN status = 'Assigned' AND invoicedate BETWEEN '$startWeek' AND '$today' THEN 1 ELSE 0 END) AS weekly_assigned_count,
                SUM(CASE WHEN status = 'Assigned' AND DATE(invoicedate) = '$today' THEN 1 ELSE 0 END) AS daily_assigned_count,

                -- 'pending' status
                SUM(CASE WHEN status = 'pending' AND invoicedate BETWEEN '$startYear' AND '$today' THEN 1 ELSE 0 END) AS yearly_created_count,
                SUM(CASE WHEN status = 'pending' AND invoicedate BETWEEN '$startQuarter' AND '$today' THEN 1 ELSE 0 END) AS quarterly_created_count,
                SUM(CASE WHEN status = 'pending' AND invoicedate BETWEEN '$startMonth' AND '$today' THEN 1 ELSE 0 END) AS monthly_created_count,
                SUM(CASE WHEN status = 'pending' AND invoicedate BETWEEN '$startWeek' AND '$today' THEN 1 ELSE 0 END) AS weekly_created_count,
                SUM(CASE WHEN status = 'pending' AND DATE(invoicedate) = '$today' THEN 1 ELSE 0 END) AS daily_created_count,

                -- 'Sent' status
                SUM(CASE WHEN status = 'Sent' AND invoicedate BETWEEN '$startYear' AND '$today' THEN 1 ELSE 0 END) AS yearly_sent_count,
                SUM(CASE WHEN status = 'Sent' AND invoicedate BETWEEN '$startQuarter' AND '$today' THEN 1 ELSE 0 END) AS quarterly_sent_count,
                SUM(CASE WHEN status = 'Sent' AND invoicedate BETWEEN '$startMonth' AND '$today' THEN 1 ELSE 0 END) AS monthly_sent_count,
                SUM(CASE WHEN status = 'Sent' AND invoicedate BETWEEN '$startWeek' AND '$today' THEN 1 ELSE 0 END) AS weekly_sent_count,
                SUM(CASE WHEN status = 'Sent' AND DATE(invoicedate) = '$today' THEN 1 ELSE 0 END) AS daily_sent_count,

                -- 'draft' status
                SUM(CASE WHEN status = 'draft' AND invoicedate BETWEEN '$startYear' AND '$today' THEN 1 ELSE 0 END) AS yearly_draft_count,
                SUM(CASE WHEN status = 'draft' AND invoicedate BETWEEN '$startQuarter' AND '$today' THEN 1 ELSE 0 END) AS quarterly_draft_count,
                SUM(CASE WHEN status = 'draft' AND invoicedate BETWEEN '$startMonth' AND '$today' THEN 1 ELSE 0 END) AS monthly_draft_count,
                SUM(CASE WHEN status = 'draft' AND invoicedate BETWEEN '$startWeek' AND '$today' THEN 1 ELSE 0 END) AS weekly_draft_count,
                SUM(CASE WHEN status = 'draft' AND DATE(invoicedate) = '$today' THEN 1 ELSE 0 END) AS daily_draft_count,

                -- Totals
                SUM(CASE WHEN invoicedate BETWEEN '$startYear' AND '$today' THEN total ELSE 0 END) AS yearly_total,
                SUM(CASE WHEN invoicedate BETWEEN '$startQuarter' AND '$today' THEN total ELSE 0 END) AS quarterly_total,
                SUM(CASE WHEN invoicedate BETWEEN '$startMonth' AND '$today' THEN total ELSE 0 END) AS monthly_total,
                SUM(CASE WHEN invoicedate BETWEEN '$startWeek' AND '$today' THEN total ELSE 0 END) AS weekly_total,
                SUM(CASE WHEN DATE(invoicedate) = '$today' THEN total ELSE 0 END) AS daily_total
            FROM cberp_quotes
        ");

        return $query->row();
    }

	public function get_delivry_note_graph_overview_count($ranges){

		$today = date('Y-m-d');

        $startYear     = $ranges['year'];
        $startQuarter  = $ranges['quarter'];
        $startMonth    = $ranges['month'];
        $startWeek     = $ranges['week'];

        $query = $this->db->query("
            SELECT 
                -- Total counts
                SUM(CASE WHEN created_date BETWEEN '$startYear' AND '$today' THEN 1 ELSE 0 END) AS yearly_count,
                SUM(CASE WHEN created_date BETWEEN '$startQuarter' AND '$today' THEN 1 ELSE 0 END) AS quarterly_count,
                SUM(CASE WHEN created_date BETWEEN '$startMonth' AND '$today' THEN 1 ELSE 0 END) AS monthly_count,
                SUM(CASE WHEN created_date BETWEEN '$startWeek' AND '$today' THEN 1 ELSE 0 END) AS weekly_count,
                SUM(CASE WHEN DATE(created_date) = '$today' THEN 1 ELSE 0 END) AS daily_count,

                -- Assigned statuses: Completed, Invoiced, Canceled
                SUM(CASE WHEN status IN ('Completed', 'Invoiced', 'Canceled') AND created_date BETWEEN '$startYear' AND '$today' THEN 1 ELSE 0 END) AS yearly_assigned_count,
                SUM(CASE WHEN status IN ('Completed', 'Invoiced', 'Canceled') AND created_date BETWEEN '$startQuarter' AND '$today' THEN 1 ELSE 0 END) AS quarterly_assigned_count,
                SUM(CASE WHEN status IN ('Completed', 'Invoiced', 'Canceled') AND created_date BETWEEN '$startMonth' AND '$today' THEN 1 ELSE 0 END) AS monthly_assigned_count,
                SUM(CASE WHEN status IN ('Completed', 'Invoiced', 'Canceled') AND created_date BETWEEN '$startWeek' AND '$today' THEN 1 ELSE 0 END) AS weekly_assigned_count,
                SUM(CASE WHEN status IN ('Completed', 'Invoiced', 'Canceled') AND DATE(created_date) = '$today' THEN 1 ELSE 0 END) AS daily_assigned_count,

                -- Created status
                SUM(CASE WHEN status = 'Created' AND created_date BETWEEN '$startYear' AND '$today' THEN 1 ELSE 0 END) AS yearly_created_count,
                SUM(CASE WHEN status = 'Created' AND created_date BETWEEN '$startQuarter' AND '$today' THEN 1 ELSE 0 END) AS quarterly_created_count,
                SUM(CASE WHEN status = 'Created' AND created_date BETWEEN '$startMonth' AND '$today' THEN 1 ELSE 0 END) AS monthly_created_count,
                SUM(CASE WHEN status = 'Created' AND created_date BETWEEN '$startWeek' AND '$today' THEN 1 ELSE 0 END) AS weekly_created_count,
                SUM(CASE WHEN status = 'Created' AND DATE(created_date) = '$today' THEN 1 ELSE 0 END) AS daily_created_count,

                -- Draft status
                SUM(CASE WHEN status = 'Draft' AND created_date BETWEEN '$startYear' AND '$today' THEN 1 ELSE 0 END) AS yearly_draft_count,
                SUM(CASE WHEN status = 'Draft' AND created_date BETWEEN '$startQuarter' AND '$today' THEN 1 ELSE 0 END) AS quarterly_draft_count,
                SUM(CASE WHEN status = 'Draft' AND created_date BETWEEN '$startMonth' AND '$today' THEN 1 ELSE 0 END) AS monthly_draft_count,
                SUM(CASE WHEN status = 'Draft' AND created_date BETWEEN '$startWeek' AND '$today' THEN 1 ELSE 0 END) AS weekly_draft_count,
                SUM(CASE WHEN status = 'Draft' AND DATE(created_date) = '$today' THEN 1 ELSE 0 END) AS daily_draft_count,

                -- In Progress status
                SUM(CASE WHEN status = 'In Progress' AND created_date BETWEEN '$startYear' AND '$today' THEN 1 ELSE 0 END) AS yearly_progress_count,
                SUM(CASE WHEN status = 'In Progress' AND created_date BETWEEN '$startQuarter' AND '$today' THEN 1 ELSE 0 END) AS quarterly_progress_count,
                SUM(CASE WHEN status = 'In Progress' AND created_date BETWEEN '$startMonth' AND '$today' THEN 1 ELSE 0 END) AS monthly_progress_count,
                SUM(CASE WHEN status = 'In Progress' AND created_date BETWEEN '$startWeek' AND '$today' THEN 1 ELSE 0 END) AS weekly_progress_count,
                SUM(CASE WHEN status = 'In Progress' AND DATE(created_date) = '$today' THEN 1 ELSE 0 END) AS daily_progress_count,

                -- Total amounts
                SUM(CASE WHEN created_date BETWEEN '$startYear' AND '$today' THEN total_amount ELSE 0 END) AS yearly_total,
                SUM(CASE WHEN created_date BETWEEN '$startQuarter' AND '$today' THEN total_amount ELSE 0 END) AS quarterly_total,
                SUM(CASE WHEN created_date BETWEEN '$startMonth' AND '$today' THEN total_amount ELSE 0 END) AS monthly_total,
                SUM(CASE WHEN created_date BETWEEN '$startWeek' AND '$today' THEN total_amount ELSE 0 END) AS weekly_total,
                SUM(CASE WHEN DATE(created_date) = '$today' THEN total_amount ELSE 0 END) AS daily_total

            FROM delivery_note_m
        ");

        return $query->row();

	}

	// public function get_purchase_rqst_overview_count($ranges){

	// }
	
	public function subscriptions_list_count(){

		$this->db->from('cberp_invoices');
		$this->db->where('cberp_invoices.i_class >', 1);
		$total = $this->db->count_all_results(); // Correct method to apply WHERE before counting

		return [
			'total' => $total,
		];
	}


}
