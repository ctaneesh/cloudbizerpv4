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

class Dashboardaccounts_model extends CI_Model
{

	public function accounts_list_count()
	{		
		$this->db->select('id,holder,adate,account_type');
		$this->db->from('cberp_accounts');
		$this->db->order_by('id', 'DESC');
		$this->db->limit(10);
		$accounts = $this->db->get()->result();
		
		$total = $this->db->count_all('cberp_accounts');		

		return [
			'accounts_list' => $accounts,
			 'total' => $total,
		];
	}

	public function invoices_list_count()
	{		
		$this->db->select('id,invoice_number,invoicedate,total,tid');
		$this->db->from('cberp_invoices');
		$this->db->order_by('id', 'DESC');
		$this->db->limit(10);
		$invoices = $this->db->get()->result();
		
		$total = $this->db->count_all('cberp_invoices');		

		return [
			'invoices_list' => $invoices,
			 'total' => $total,
		];
	}

	public function transactions_list_count(){
		$this->db->select('id,trans_ref_number,trans_date,trans_amount,trans_number');
		$this->db->from('cberp_bank_transactions');
		$this->db->order_by('id', 'DESC');
		$this->db->limit(10);
		$transactions = $this->db->get()->result();
		
		$total = $this->db->count_all('cberp_bank_transactions');
		
		$monthlyData = [];
		for ($i = 11; $i >= 0; $i--) {
			$monthStart = date('Y-m-01', strtotime("-$i months"));
			$monthEnd = date('Y-m-t', strtotime("-$i months"));

			$this->db->where('trans_date >=', $monthStart);
			$this->db->where('trans_date <=', $monthEnd);
			$monthlyCount = $this->db->count_all_results('cberp_bank_transactions');

			$monthlyData[] = $monthlyCount;
		}		


		return [
			'transactions_list' => $transactions,
			'total' => $total,
			'monthly_data' => $monthlyData
		];
	}

	public function get_manualjournl_count(){

		$total = $this->db->count_all('cberp_products');		

		return [
			 'total' => $total,
		];
	}

	public function get_invoice_overview_count($ranges)
    {
        $today = date('Y-m-d');
        $startMonth    = $ranges['month'];
        $startWeek     = $ranges['week'];
        $startQuarter  = $ranges['quarter'];
        $startYear     = $ranges['year'];

        $query = $this->db->query("
            SELECT 
                -- Total invoice counts
                SUM(CASE WHEN invoicedate BETWEEN '$startYear' AND '$today' THEN 1 ELSE 0 END) AS yearly_count,
                SUM(CASE WHEN invoicedate BETWEEN '$startQuarter' AND '$today' THEN 1 ELSE 0 END) AS quarterly_count,
                SUM(CASE WHEN invoicedate BETWEEN '$startMonth' AND '$today' THEN 1 ELSE 0 END) AS monthly_count,
                SUM(CASE WHEN invoicedate BETWEEN '$startWeek' AND '$today' THEN 1 ELSE 0 END) AS weekly_count,
                SUM(CASE WHEN DATE(invoicedate) = '$today' THEN 1 ELSE 0 END) AS daily_count,

                -- 'due' status invoice counts
                SUM(CASE WHEN (status = 'due' || status = 'partial' || status = 'paid') AND invoicedate BETWEEN '$startYear' AND '$today' THEN 1 ELSE 0 END) AS yearly_created_count,
                SUM(CASE WHEN (status = 'due' || status = 'partial' || status = 'paid') AND invoicedate BETWEEN '$startQuarter' AND '$today' THEN 1 ELSE 0 END) AS quarterly_created_count,
                SUM(CASE WHEN (status = 'due' || status = 'partial' || status = 'paid') AND invoicedate BETWEEN '$startMonth' AND '$today' THEN 1 ELSE 0 END) AS monthly_created_count,
                SUM(CASE WHEN (status = 'due' || status = 'partial' || status = 'paid') AND invoicedate BETWEEN '$startWeek' AND '$today' THEN 1 ELSE 0 END) AS weekly_created_count,
                SUM(CASE WHEN (status = 'due' || status = 'partial' || status = 'paid') AND DATE(invoicedate) = '$today' THEN 1 ELSE 0 END) AS daily_created_count,

                -- 'draft' status invoice counts
                SUM(CASE WHEN status = 'draft' AND invoicedate BETWEEN '$startYear' AND '$today' THEN 1 ELSE 0 END) AS yearly_draft_count,
                SUM(CASE WHEN status = 'draft' AND invoicedate BETWEEN '$startQuarter' AND '$today' THEN 1 ELSE 0 END) AS quarterly_draft_count,
                SUM(CASE WHEN status = 'draft' AND invoicedate BETWEEN '$startMonth' AND '$today' THEN 1 ELSE 0 END) AS monthly_draft_count,
                SUM(CASE WHEN status = 'draft' AND invoicedate BETWEEN '$startWeek' AND '$today' THEN 1 ELSE 0 END) AS weekly_draft_count,
                SUM(CASE WHEN status = 'draft' AND DATE(invoicedate) = '$today' THEN 1 ELSE 0 END) AS daily_draft_count,

                -- Totals (sum of 'total' column)
                SUM(CASE WHEN invoicedate BETWEEN '$startYear' AND '$today' THEN total ELSE 0 END) AS yearly_total,
                SUM(CASE WHEN invoicedate BETWEEN '$startQuarter' AND '$today' THEN total ELSE 0 END) AS quarterly_total,
                SUM(CASE WHEN invoicedate BETWEEN '$startMonth' AND '$today' THEN total ELSE 0 END) AS monthly_total,
                SUM(CASE WHEN invoicedate BETWEEN '$startWeek' AND '$today' THEN total ELSE 0 END) AS weekly_total,
                SUM(CASE WHEN DATE(invoicedate) = '$today' THEN total ELSE 0 END) AS daily_total
            FROM cberp_invoices
        ");

        return $query->row();
    }
	
	public function get_credit_overview_count($ranges)
    {
        $today = date('Y-m-d');
        $startMonth   = $ranges['month'];
        $startWeek    = $ranges['week'];
        $startQuarter = $ranges['quarter'];
        $startYear    = $ranges['year'];

        $query = $this->db->query("
            SELECT 
                -- Total invoice counts
                SUM(CASE WHEN invoicedate BETWEEN '$startYear' AND '$today' THEN 1 ELSE 0 END) AS yearly_count,
                SUM(CASE WHEN invoicedate BETWEEN '$startQuarter' AND '$today' THEN 1 ELSE 0 END) AS quarterly_count,
                SUM(CASE WHEN invoicedate BETWEEN '$startMonth' AND '$today' THEN 1 ELSE 0 END) AS monthly_count,
                SUM(CASE WHEN invoicedate BETWEEN '$startWeek' AND '$today' THEN 1 ELSE 0 END) AS weekly_count,
                SUM(CASE WHEN DATE(invoicedate) = '$today' THEN 1 ELSE 0 END) AS daily_count,

                -- 'due' status
                SUM(CASE WHEN payment_status = 'due' AND invoicedate BETWEEN '$startYear' AND '$today' THEN 1 ELSE 0 END) AS yearly_created_count,
                SUM(CASE WHEN payment_status = 'due' AND invoicedate BETWEEN '$startQuarter' AND '$today' THEN 1 ELSE 0 END) AS quarterly_created_count,
                SUM(CASE WHEN payment_status = 'due' AND invoicedate BETWEEN '$startMonth' AND '$today' THEN 1 ELSE 0 END) AS monthly_created_count,
                SUM(CASE WHEN payment_status = 'due' AND invoicedate BETWEEN '$startWeek' AND '$today' THEN 1 ELSE 0 END) AS weekly_created_count,
                SUM(CASE WHEN payment_status = 'due' AND DATE(invoicedate) = '$today' THEN 1 ELSE 0 END) AS daily_created_count,

                -- 'paid' status
                SUM(CASE WHEN payment_status = 'Paid' AND invoicedate BETWEEN '$startYear' AND '$today' THEN 1 ELSE 0 END) AS yearly_paid_count,
                SUM(CASE WHEN payment_status = 'Paid' AND invoicedate BETWEEN '$startQuarter' AND '$today' THEN 1 ELSE 0 END) AS quarterly_paid_count,
                SUM(CASE WHEN payment_status = 'Paid' AND invoicedate BETWEEN '$startMonth' AND '$today' THEN 1 ELSE 0 END) AS monthly_paid_count,
                SUM(CASE WHEN payment_status = 'Paid' AND invoicedate BETWEEN '$startWeek' AND '$today' THEN 1 ELSE 0 END) AS weekly_paid_count,
                SUM(CASE WHEN payment_status = 'Paid' AND DATE(invoicedate) = '$today' THEN 1 ELSE 0 END) AS daily_paid_count,

                -- 'partial' status
                SUM(CASE WHEN payment_status = 'Partial' AND invoicedate BETWEEN '$startYear' AND '$today' THEN 1 ELSE 0 END) AS yearly_partial_count,
                SUM(CASE WHEN payment_status = 'Partial' AND invoicedate BETWEEN '$startQuarter' AND '$today' THEN 1 ELSE 0 END) AS quarterly_partial_count,
                SUM(CASE WHEN payment_status = 'Partial' AND invoicedate BETWEEN '$startMonth' AND '$today' THEN 1 ELSE 0 END) AS monthly_partial_count,
                SUM(CASE WHEN payment_status = 'Partial' AND invoicedate BETWEEN '$startWeek' AND '$today' THEN 1 ELSE 0 END) AS weekly_partial_count,
                SUM(CASE WHEN payment_status = 'Partial' AND DATE(invoicedate) = '$today' THEN 1 ELSE 0 END) AS daily_partial_count,

                -- Totals
                SUM(CASE WHEN invoicedate BETWEEN '$startYear' AND '$today' THEN total ELSE 0 END) AS yearly_total,
                SUM(CASE WHEN invoicedate BETWEEN '$startQuarter' AND '$today' THEN total ELSE 0 END) AS quarterly_total,
                SUM(CASE WHEN invoicedate BETWEEN '$startMonth' AND '$today' THEN total ELSE 0 END) AS monthly_total,
                SUM(CASE WHEN invoicedate BETWEEN '$startWeek' AND '$today' THEN total ELSE 0 END) AS weekly_total,
                SUM(CASE WHEN DATE(invoicedate) = '$today' THEN total ELSE 0 END) AS daily_total

            FROM cberp_stock_returns
            WHERE invoice_id IS NOT NULL
        ");

        return $query->row();
    }

	public function bank_accounts_list_count(){

		$total = $this->db->count_all('cberp_bank_ac');		

		return [
			 'total' => $total,
		];
	}

	public function bank_category_list_count(){

		$total = $this->db->count_all('cberp_bank_transcategory');		

		return [
			 'total' => $total,
		];
	}

	public function recociliations_list_count(){

		//$total = $this->db->count_all('cberp_reconciliations');
		
		$this->db->from('cberp_reconciliations');
		$this->db->join('cberp_bank_ac', 'cberp_bank_ac.code = cberp_reconciliations.account_id');

		$total = $this->db->count_all_results();


		return [
			 'total' => $total,
		];

	}

	


	


}
