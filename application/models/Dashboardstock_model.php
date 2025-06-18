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

class Dashboardstock_model extends CI_Model
{

	public function group_products_list_count()
	{	
		
		$this->db->select('cberp_products.product_code,cberp_product_description.product_name,cberp_products.product_price,cberp_products.status');
		$this->db->from('cberp_products');		
        $this->db->join('cberp_product_description', 'cberp_product_description.product_code = cberp_products.product_code');
		$this->db->order_by('created_date', 'DESC');
		$this->db->limit(10);
		$products = $this->db->get()->result();		
		$total = $this->db->count_all('cberp_products');	

		return [
			'product_list' => $products,
			//'product_list' => '',
			 'total' => $total,
		];
	}

	public function group_product_stock_status(){
		$whr = '';
        $query = $this->db->query("SELECT
        COUNT(IF( cberp_products.onhand_quantity > 0, cberp_products.onhand_quantity, NULL)) AS Instock,
        COUNT(IF( cberp_products.onhand_quantity <= 0, cberp_products.onhand_quantity, NULL)) AS Outofstock,
        COUNT(cberp_products.onhand_quantity) AS total
        FROM cberp_products $whr");

		$res = $query->row_array();

        $total = (int)$res['Instock'] + (int)$res['Outofstock'];
		$values = [(int)$res['Instock'], (int)$res['Outofstock']];
		$labels = ['Instock', 'Outofstock'];
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

	public function get_purchase_order_list_count(){

		$this->db->select('cberp_purchase_orders.purchase_number as id,cberp_purchase_orders.order_status,cberp_purchase_orders.purchase_order_date,cberp_purchase_orders.purchase_number');
		$this->db->from('cberp_purchase_orders');
		$this->db->order_by('cberp_purchase_orders.created_date', 'DESC');
		$this->db->limit(10);
		$purchase_orders = $this->db->get()->result();
		
		$total = $this->db->count_all('cberp_purchase_orders');

		$monthlyData = [];
		for ($i = 11; $i >= 0; $i--) {
			$monthStart = date('Y-m-01', strtotime("-$i months"));
			$monthEnd = date('Y-m-t', strtotime("-$i months"));

			$this->db->where('created_date >=', $monthStart);
			$this->db->where('created_date <=', $monthEnd);
			$monthlyCount = $this->db->count_all_results('cberp_purchase_orders');

			$monthlyData[] = $monthlyCount;
		}		
		

		return [
			'purchase_orders_list' => $purchase_orders,
			 'total' => $total,
			'monthly_data' => $monthlyData
		];
		
	}

	public function get_purchase_return_list_count()
	{
		$this->db->select('id, purchase_reciept_number, return_status, receipt_return_number, return_date');
		$this->db->from('cberp_purchase_reciept_returns');
		$this->db->order_by('id', 'DESC');
		$this->db->limit(10);
		$return_list = $this->db->get()->result();

		$total = $this->db->count_all('cberp_purchase_reciept_returns');

		$monthlyData = [];
		for ($i = 11; $i >= 0; $i--) {
			$monthStart = date('Y-m-01', strtotime("-$i months"));
			$monthEnd = date('Y-m-t', strtotime("-$i months"));
			$monthLabel = date('M Y', strtotime("-$i months"));

			$this->db->where('created_date >=', $monthStart);
			$this->db->where('created_date <=', $monthEnd);
			$count = $this->db->count_all_results('cberp_purchase_reciept_returns');

			$monthlyData[] = [
				'label' => $monthLabel,
				'count' => $count
			];
		}

		return [
			'return_list' => $return_list,
			'total' => $total,
			'monthly_data' => $monthlyData
		];
	}

	public function get_stocks_count(){
		//$this->db->select('stock_transfer_wh_to_wh.id');
		//$this->db->from('stock_transfer_wh_to_wh');
		//$this->db->order_by('pid', 'DESC');
		//$this->db->limit(10);
		//$products = $this->db->get()->result();

		$this->db->from('stock_transfer_wh_to_wh');
        $count = $this->db->count_all_results();

		
		//$total = $this->db->count_all('cberp_products');
		

		return [
			//'product_list' => $products,
			//'product_list' => '',
			 'total' => $count,
		];
	}

	public function get_product_catgry_count(){
		$this->db->from('cberp_product_category');
		// $this->db->where('c_type', 0);
		$total = $this->db->count_all_results();
		return ['total' => $total];
	}

	public function get_product_brand_count(){
		$this->db->from('cberp_brands');
		$total = $this->db->count_all_results();
		return ['total' => $total];
		
	}

	public function get_product_manufctr_count(){
		$this->db->from('cberp_manufacturer_ai');
		$total = $this->db->count_all_results();
		return ['total' => $total];
	}

	public function get_purchs_recipts_count(){
        $this->db->from('cberp_purchase_receipts');
        $this->db->where('status', '1');
		$total = $this->db->count_all_results();
		return ['total' => $total];
	}



	


	


}
