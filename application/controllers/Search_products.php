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

defined('BASEPATH') or exit('No direct script access allowed');

class Search_products extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library("Aauth");
		$this->load->model('search_model');
		if (!$this->aauth->is_loggedin()) {
			redirect('/user/', 'refresh');
		}
		// if (!$this->aauth->premission(1)) {
		// 	exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
		// }
	}

//search product in invoice
	public function search()
	{
		$this->load->model('plugins_model', 'plugins');
		$billing_settings = $this->plugins->universal_api(67);
		$result = array();
		$out = array();
		$row_num = $this->input->post('row_num', true);
		$name = $this->input->post('name_startsWith', true);
		$wid = $this->input->post('wid', true);
		$qw = '';
		// if ($wid > 0) {
		// 	$qw = "(cberp_products.warehouse='$wid') AND ";
		// }
		if ($billing_settings['key2']) $qw .= "(cberp_products.expiry IS NULL OR DATE (cberp_products.expiry)<" . date('Y-m-d') . ") AND ";
		$join = '';

		// if ($this->aauth->get_user()->loc) {
		// 	$join = 'LEFT JOIN cberp_store ON cberp_store.id=cberp_products.warehouse';
		// 	$join2 = 'LEFT JOIN cberp_store ON cberp_store.id=cberp_products.warehouse';
		// 	if (BDATA) $qw .= '(cberp_store.loc=' . $this->aauth->get_user()->loc . ' OR cberp_store.loc=0) AND '; else $qw .= '(cberp_store.loc=' . $this->aauth->get_user()->loc . ' ) AND ';
		// } elseif (!BDATA) {
		// 	$join = 'LEFT JOIN cberp_store ON cberp_store.id=cberp_products.warehouse';
		// 	$qw .= '(cberp_store.loc=0) AND ';
		// } 
		$e = '';
		$productai = ' INNER JOIN cberp_product_description ON cberp_product_description.product_code = cberp_products.product_code';
		$productai .= ' INNER JOIN cberp_product_pricing ON cberp_product_pricing.product_code = cberp_products.product_code';
		
		// if ($billing_settings['key1'] == 1) {
		// 	$e .= ',cberp_product_serials.serial';
		// 	$join .= 'LEFT JOIN cberp_product_serials ON cberp_product_serials.product_id=cberp_products.product_code';
		// 	$qw .= '(cberp_product_serials.status=0) AND ';
		// }

		if ($name) {

			// if ($billing_settings['key1'] == 2) {
			// 	// $e .= ',cberp_product_serials.serial';
			// 	$query = $this->db->query("SELECT cberp_products.product_code,cberp_product_description.product_name,cberp_products.product_price,cberp_products.product_code,cberp_products.tax_rate,cberp_products.discount_rate,cberp_product_description.product_description,cberp_products.onhand_quantity,cberp_products.unit, cberp_products.product_cost as product_cost,  cberp_product_pricing.minimum_price as lowest_price, cberp_products.maximum_discount_rate as maxdiscountrate,cberp_products.income_account_number,cberp_products.expense_account_number  FROM cberp_product_serials  $productai WHERE " . $qw . "(UPPER(cberp_product_serials.serial) LIKE '" . strtoupper($name) . "%')  LIMIT 6");
			// } else {
				$query = $this->db->query("SELECT cberp_products.product_code,cberp_product_description.product_name,cberp_products.product_price,cberp_products.tax_rate,cberp_products.discount_rate,cberp_product_description.product_description,cberp_products.onhand_quantity,cberp_products.unit $e,cberp_products.product_cost as product_cost, cberp_product_pricing.minimum_price as lowest_price, cberp_products.maximum_discount_rate as maxdiscountrate,cberp_products.income_account_number,cberp_products.expense_account_number  FROM cberp_products $productai WHERE " . $qw . "(UPPER(cberp_product_description.product_name) LIKE '%" . strtoupper($name) . "%') OR (UPPER(cberp_products.product_code) LIKE '%" . strtoupper($name) . "%') LIMIT 6");
			// }

			

			$result = $query->result_array();
			// die($this->db->last_query());
			$default_sales_account = default_chart_of_account('sales');
			foreach ($result as $row) {
				
				$row['income_account_number'] = ($row['income_account_number'])?$row['income_account_number']:$default_sales_account;
				// $row['income_account_number'] = ($default_sales_account)?$default_sales_account:$row['income_account_number'];
				$name = array($row['product_name'], $row['product_price'], $row['product_code'], amountFormat_general($row['tax_rate']), amountFormat_general($row['discount_rate']), $row['product_description'], $row['unit'], $row['product_code'], amountFormat_general($row['onhand_quantity']), $row_num, @$row['serial'], $row['lowest_price'],$row['maxdiscountrate'],$row['income_account_number'],$row['expense_account_number'],$row['product_cost']);
				array_push($out, $name);
			}
			echo json_encode($out);
		}

	}

	//erp2024 new function starts 05-06-2024
	public function productsearch()
	{
		$result = array();
		$out = array();
		$row_num = $this->input->post('row_num', true);
		$name = $this->input->post('name_startsWith', true);
		$wid = $this->input->post('wid', true);
		$qw = '';
		$query = $this->db->query("SELECT cberp_product_description.product_name,cberp_products.product_code,cberp_products.onhand_quantity FROM cberp_products  WHERE " . $qw . "(UPPER(cberp_product_description.product_name) LIKE '%" . strtoupper($name) . "%') OR (UPPER(cberp_products.product_code) LIKE '%" . strtoupper($name) . "%') LIMIT 6");
		$result = $query->result_array();
		foreach ($result as $row) {
			$name = array($row['product_name'], $row['pid'], $row['product_code'], $row['onhand_quantity']);
			array_push($out, $name);
		}
		echo json_encode($out);
	}
	public function productsinwarehouse()
	{
		$result = array();
		$out = array();
		$row_num = $this->input->post('row_num', true);
		$name = $this->input->post('name_startsWith', true);
		$wid = !empty($this->input->post('wid', true)) ? $this->input->post('wid', true) :0;
		$qw = ' AND cberp_product_to_store.store_id ='. $wid;
		// $query = $this->db->query("SELECT DISTINCT cberp_products.pid,cberp_product_description.product_name,cberp_products.product_code,cberp_products.onhand_quantity,cberp_products.unit, cberp_product_to_store.stock_qty FROM cberp_products INNER JOIN cberp_product_to_store ON cberp_product_to_store.product_id = cberp_products.pid  WHERE   (UPPER(cberp_product_description.product_name) LIKE '%" . strtoupper($name) . "%') OR (UPPER(cberp_products.product_code) LIKE '" . strtoupper($name) . "%') " . $qw . " LIMIT 10");

		$query = $this->db->query("SELECT DISTINCT 
                cberp_product_to_store.store_id,
                cberp_product_description.product_name,
                cberp_products.product_code,
                cberp_products.onhand_quantity,
                cberp_products.unit,
                cberp_product_to_store.stock_qty
				FROM cberp_product_to_store
				INNER JOIN cberp_products
						ON cberp_product_to_store.product_code = cberp_product_description.product_name
				WHERE cberp_product_to_store.store_id = $wid
					AND (
						UPPER(cberp_product_description.product_name) LIKE '%".strtoupper($name)."%'
						OR UPPER(cberp_products.product_code) LIKE '%".strtoupper($name)."%'
					)
				LIMIT 10 ");

		// echo $this->db->last_query(); die();
		$result = $query->result_array();
		foreach ($result as $row) {
			$name = array($row['product_name'],  $row['product_code'], $row['unit'], $row['stock_quantity']);
			array_push($out, $name);
		}
		echo json_encode($out);
	}
	//erp2024 new function  05-06-2024 ends
	public function searchByProductID()
	{
		$this->load->model('plugins_model', 'plugins');
		$billing_settings = $this->plugins->universal_api(67);
		$result = array();
		$out = array();
		$pid = $this->input->post('pid', true);

		
		$query = $this->db->query("SELECT cberp_product_description.product_name,cberp_products.product_price,cberp_products.product_code,cberp_products.tax_rate,cberp_products.discount_rate,cberp_product_description.product_description,cberp_products.onhand_quantity,cberp_products.unit   FROM cberp_products WHERE cberp_products.pid = '$pid'");
		$result = $query->result_array();
		foreach ($result as $row) {
				$name = array($row['product_name'], amountExchange_s($row['product_price'], 0, $this->aauth->get_user()->loc), $row['product_code'], amountFormat_general($row['tax_rate']), amountFormat_general($row['discount_rate']), $row['product_description'], $row['unit'], $row['product_code'], amountFormat_general($row['onhand_quantity']), $row_num, @$row['serial']);
				array_push($out, $name);
		}
		echo json_encode($out);
		

	}

	public function expense_search()
	{
		$this->load->model('plugins_model', 'plugins');
		$billing_settings = $this->plugins->universal_api(67);
		$result = array();
		$out = array();
		$name = $this->input->post('name_startsWith', true);
		$query = $this->db->query("SELECT id,expence_name FROM expenses WHERE expence_name  LIKE '%" . strtoupper($name) . "%'");
		$last_query = $this->db->last_query();
		$result = $query->result_array();
		foreach ($result as $row) {
			$name = array($row['id'],  $row['expence_name']);
			array_push($out, $name);
		}
		echo json_encode($out);
		

	}

	public function payableaccount_search()
	{
		$this->load->model('plugins_model', 'plugins');
		$billing_settings = $this->plugins->universal_api(67);
		$result = array();
		$out = array();
		$name = $this->input->post('name_startsWith', true);
		$query = $this->db->query("SELECT id,holder,acn FROM cberp_accounts WHERE (UPPER(acn)  LIKE '%".strtoupper($name)."%' OR  UPPER(holder)  LIKE '%".strtoupper($name)."%') LIMIT 6");
		
		$result = $query->result_array();
		foreach ($result as $row) {
			$name = array($row['id'], $row['holder'], $row['acn']);
			array_push($out, $name);
		}
		echo json_encode($out);
		

	}

	public function puchase_search()
	{
		$result = array();
		$out = array();
		$row_num = $this->input->post('row_num', true);
		$name = $this->input->post('name_startsWith', true);
		$wid = $this->input->post('wid', true);
		$qw = '';
		// if ($wid > 0) {
		// 	$qw = "(cberp_products.warehouse='$wid' ) AND ";
		// }
		$join = ' INNER JOIN cberp_product_description ON cberp_product_description.product_code = cberp_products.product_code';

		if ($name) {
			
			$query = $this->db->query("SELECT cberp_products.product_code,cberp_product_description.product_name,cberp_products.product_code,cberp_products.product_cost,cberp_products.tax_rate,cberp_products.discount_rate,cberp_product_description.product_description,cberp_products.unit,cberp_products.income_account_number,cberp_products.expense_account_number,latest_purchase.price AS last_purchase_price FROM cberp_products $join
			LEFT JOIN (
				SELECT cberp_purchase_order_items.product_code, cberp_purchase_order_items.price 
				FROM cberp_purchase_order_items
				JOIN cberp_purchase_orders ON cberp_purchase_order_items.purchase_number = cberp_purchase_orders.purchase_number
				WHERE (cberp_purchase_order_items.product_code, cberp_purchase_orders.created_date) IN (
					SELECT cberp_purchase_order_items.product_code, MAX(cberp_purchase_orders.created_date) 
					FROM cberp_purchase_order_items
					JOIN cberp_purchase_orders ON cberp_purchase_order_items.purchase_number = cberp_purchase_orders.purchase_number
					GROUP BY cberp_purchase_order_items.product_code
				)
			) latest_purchase ON latest_purchase.product_code = cberp_products.product_code
				WHERE " . $qw . "UPPER(cberp_product_description.product_name) LIKE '%" . strtoupper($name) . "%' 
				OR UPPER(cberp_products.product_code) LIKE '%" . strtoupper($name) . "%' LIMIT 6");

				
			
			$result = $query->result_array();
			foreach ($result as $row) {
				$name = array($row['product_name'], amountExchange_s($row['product_cost'], 0, $this->aauth->get_user()->loc), $row['product_code'], amountFormat_general($row['tax_rate']), amountFormat_general($row['discount_rate']), $row['product_description'], $row['unit'], $row['product_code'], $row_num,$row['income_account_number'],$row['expense_account_number'],$row['last_purchase_price']);
				array_push($out, $name);
			}

			echo json_encode($out);
		}

	}

	public function csearch()
	{
		$result = array();
		$out = array();
		$name = $this->input->get('keyword', true);
		$whr = '';
		if ($name) {
			$query = $this->db->query("SELECT customer_id as id,name,address,city,phone,email,discount,avalable_credit_limit,credit_period,credit_limit FROM cberp_customers WHERE $whr (UPPER(name)  LIKE '%" . strtoupper($name) . "%' OR UPPER(phone)  LIKE '" . strtoupper($name) . "%') LIMIT 6");
			$result = $query->result_array();
			echo '<ol>';
			$i = 1;
			foreach ($result as $row) {
				$name = $this->sanitizeString($row['name']);
				$address = $this->sanitizeString($row['address']);
				$city = $this->sanitizeString($row['city']);
				$email = $this->sanitizeString($row['email']);
				$avalable_credit_limit = number_format($row['avalable_credit_limit'],2);
				$credit_period = $row['credit_period'];
				$credit_limit = $row['credit_limit'];
				echo "<li onClick=\"selectCustomer('" . $row['id'] . "','".$name."','".$city."','" . trim($row['phone']) . "','" . $email . "','" . amountFormat_general($row['discount']) . "','" . trim($row['credit_period']) . "','" . trim(number_format($row['credit_limit'],2)) . "','" . trim(number_format($row['avalable_credit_limit'],2)) . "')\"><span>$i</span><p>" . $name . " &nbsp; &nbsp  " . $row['phone'] . "</p></li>";
				$i++;
			}
			echo '</ol>';
		}

	}
	public function customersearch()
	{
		$result = array();
		$out = array();
		$name = $this->input->get('keyword', true);
		$whr = '';
		// if ($this->aauth->get_user()->loc) {
		// 	$whr = ' (loc=' . $this->aauth->get_user()->loc . ' OR loc=0) AND ';
		// 	if (!BDATA) $whr = ' (loc=' . $this->aauth->get_user()->loc . ' ) AND ';
		// } elseif (!BDATA) {
		// 	$whr = ' (loc=0) AND ';
		// }
		if ($name) {
			$query = $this->db->query("SELECT customer_id as id,name,address,city,phone,email,discount FROM cberp_customers WHERE $whr (UPPER(name)  LIKE '%" . strtoupper($name) . "%' OR UPPER(phone)  LIKE '" . strtoupper($name) . "%') LIMIT 6");
			$result = $query->result_array();
			echo '<ol>';
			$i = 1;
			foreach ($result as $row) {
				$name = $this->sanitizeString($row['name']);
				$address = $this->sanitizeString($row['address']);
				$city = $this->sanitizeString($row['city']);
				$email = $this->sanitizeString($row['email']);
				echo "<li onClick=\"selectedCustomer('" . trim($row['id']) . "','" . $name . "','" . $city . "','" . trim($row['phone']) . "','" . $email . "')\"><p>" . $name . " &nbsp; &nbsp  " . $row['phone'] . "</p></li>";
				$i++;
			}
			echo '</ol>';
		}

	}

	public function party_search()
	{
		$result = array();
		$out = array();
		$tbl = 'cberp_customers';
		$name = $this->input->get('keyword', true);

		$ty = $this->input->get('ty', true);
		if ($ty) $tbl = 'cberp_suppliers';
		$whr = '';


		// if ($this->aauth->get_user()->loc) {
		// 	$whr = ' (loc=' . $this->aauth->get_user()->loc . ' OR loc=0) AND ';
		// 	if (!BDATA) $whr = ' (loc=' . $this->aauth->get_user()->loc . ' ) AND ';
		// } elseif (!BDATA) {
		// 	$whr = ' (loc=0) AND ';
		// }


		if ($name) {
			$query = $this->db->query("SELECT id,name,address,city,phone,email FROM $tbl  WHERE $whr (UPPER(name)  LIKE '%" . strtoupper($name) . "%' OR UPPER(phone)  LIKE '" . strtoupper($name) . "%') LIMIT 6");
			$result = $query->result_array();
			echo '<ol>';
			$i = 1;
			foreach ($result as $row) {
				$name = $this->sanitizeString($row['name']);
				$address = $this->sanitizeString($row['address']);
				$city = $this->sanitizeString($row['city']);
				$email = $this->sanitizeString($row['email']);
				echo "<li onClick=\"selectCustomer('" . $row['id'] . "','" . $name . "','" . $city . "','" . $row['phone'] . "','" . $email . "')\"><span>$i</span><p>" . $name . " &nbsp; &nbsp  " . $row['phone'] . "</p></li>";
				$i++;
			}
			echo '</ol>';
		}

	}

	public function pos_c_search()
	{
		$result = array();
		$out = array();
		$name = $this->input->get('keyword', true);
		$whr = '';
		// if ($this->aauth->get_user()->loc) {
		// 	$whr = ' (loc=' . $this->aauth->get_user()->loc . ' OR loc=0) AND ';
		// 	if (!BDATA) $whr = ' (loc=' . $this->aauth->get_user()->loc . ' ) AND ';
		// } elseif (!BDATA) {
		// 	$whr = ' (loc=0) AND ';
		// }

		if ($name) {
			$query = $this->db->query("SELECT customer_id,name,phone,discount FROM cberp_customers WHERE $whr (UPPER(name)  LIKE '%" . strtoupper($name) . "%' OR UPPER(phone)  LIKE '" . strtoupper($name) . "%') LIMIT 6");
			$result = $query->result_array();
			echo '<ol>';
			$i = 1;
			foreach ($result as $row) {
				
				$name = $this->sanitizeString($row['name']);
				echo "<li onClick=\"PselectCustomer('" . $row['customer_id'] . "','" . $name . "','" . amountFormat_general($row['discount']) . "')\"><span>$i</span><p>" . $name . " &nbsp; &nbsp  " . $row['phone'] . "</p></li>";
				$i++;
			}
			echo '</ol>';
		}

	}


	public function supplier()
	{
		$result = array();
		$out = array();
		$name = $this->input->get('keyword', true);

		$whr = '';
		// if ($this->aauth->get_user()->loc) {
		// 	$whr = ' (loc=' . $this->aauth->get_user()->loc . ' OR loc=0) AND ';
		// 	if (!BDATA) $whr = ' (loc=' . $this->aauth->get_user()->loc . ' ) AND ';
		// } elseif (!BDATA) {
		// 	$whr = ' (loc=0) AND ';
		// }
		if ($name) {
			$query = $this->db->query("SELECT supplier_id as id,name,address,city,phone,email FROM cberp_suppliers WHERE $whr (UPPER(name)  LIKE '%" . strtoupper($name) . "%' OR UPPER(phone)  LIKE '" . strtoupper($name) . "%') LIMIT 6");
			$result = $query->result_array();
			echo '<ol>';
			$i = 1;
			foreach ($result as $row) {
				$name = $this->sanitizeString($row['name']);
				$address = $this->sanitizeString($row['address']);
				$city = $this->sanitizeString($row['city']);
				$email = $this->sanitizeString($row['email']);
				echo "<li onClick=\"selectSupplier('" . $row['id'] . "','" . $name . "','" . $city . "','" . $row['phone'] . "','" . $email . "')\"><span>$i</span><p>" . $name . " &nbsp; &nbsp  " . $row['phone'] . "</p></li>";
				$i++;
			}
			echo '</ol>';
		}

	}

	public function pos_search()
	{
		$out = '';
		$this->load->model('plugins_model', 'plugins');
		$billing_settings = $this->plugins->universal_api(67);
		$name = (string)$this->input->post('name', true);
		$cid = $this->input->post('cid', true);
		$wid = $this->input->post('wid', true);
		$qw = '';
		if ($wid > 0) {
			$qw .= "(cberp_products.warehouse='$wid') AND ";
		}
		if ($billing_settings['key2']) $qw .= "(cberp_products.expiry IS NULL OR DATE (cberp_products.expiry)<" . date('Y-m-d') . ") AND ";
		if ($cid > 0) {
			$qw .= "(cberp_products.pcat='$cid') AND ";
		}
		$join = '';
		if ($this->aauth->get_user()->loc) {
			$join = 'LEFT JOIN cberp_store ON cberp_store.id=cberp_products.warehouse';
			if (BDATA) $qw .= '(cberp_store.loc=' . $this->aauth->get_user()->loc . ' OR cberp_store.loc=0) AND '; else $qw .= '(cberp_store.loc=' . $this->aauth->get_user()->loc . ' ) AND ';
		} elseif (!BDATA) {
			$join = 'LEFT JOIN cberp_store ON cberp_store.id=cberp_products.warehouse';
			$qw .= '(cberp_store.loc=0) AND ';
		}

		$e = '';
		if ($billing_settings['key1'] == 1) {
			$e .= ',cberp_product_serials.serial';
			$join .= 'LEFT JOIN cberp_product_serials ON cberp_product_serials.product_id=cberp_products.pid ';
			$qw .= '(cberp_product_serials.status=0) AND  ';
		}


		$bar = '';
		if (is_numeric($name)) {
			$b = array('-', '-', '-');
			$c = array(3, 4, 11);
			$barcode = $name;
			for ($i = count($c) - 1; $i >= 0; $i--) {
				$barcode = substr_replace($barcode, $b[$i], $c[$i], 0);
			}

			$bar = " OR (cberp_products.barcode LIKE '" . (substr($barcode, 0, -1)) . "%' OR cberp_products.barcode LIKE '" . $name . "%')";
		}
		if ($billing_settings['key1'] == 2) {

			$query = "SELECT cberp_products.*,cberp_product_serials.serial FROM cberp_product_serials  LEFT JOIN cberp_products  ON cberp_products.pid=cberp_product_serials.product_id $join WHERE " . $qw . "cberp_product_serials.serial LIKE '" . strtoupper($name) . "%'  AND (cberp_products.onhand_quantity>0) LIMIT 16";


		} else {
			$query = "SELECT cberp_products.* $e FROM cberp_products $join WHERE " . $qw . "(UPPER(cberp_product_description.product_name) LIKE '%" . strtoupper($name) . "%' $bar OR cberp_products.product_code LIKE '" . strtoupper($name) . "%') AND (cberp_products.onhand_quantity>0) LIMIT 16";

		}


		$query = $this->db->query($query);
		$result = $query->result_array();
		$i = 0;
		echo '<div class="row match-height">';
		foreach ($result as $row) {

			$out .= '<div class="col-3 border mb-1 "><div class="rounded">
                                 <a   id="posp' . $i . '"  class="select_pos_item btn btn-outline-light-blue round breaklink"   data-name="' . $row['product_name'] . '"  data-price="' . amountExchange_s($row['product_price'], 0, $this->aauth->get_user()->loc) . '"  data-tax="' . amountFormat_general($row['tax_rate']) . '"  data-discount="' . amountFormat_general($row['discount_rate']) . '"   data-pcode="' . $row['product_code'] . '"   data-pid="' . $row['pid'] . '"  data-stock="' . amountFormat_general($row['onhand_quantity']) . '" data-unit="' . $row['unit'] . '" data-serial="' . @$row['serial'] . '">
                                        <img class="round"
                                             src="' . base_url('userfiles/product/' . $row['image']) . '"  style="max-height: 100%;max-width: 100%">
                                        <div class="text-xs-center text">
                                       
                                            <small style="white-space: pre-wrap;">' . $row['product_name'] . '</small>

                                            
                                        </div></a>
                                  
                                </div></div>';

			$i++;
			//   if ($i % 4 == 0) $out .= '</div><div class="row">';
		}

		echo $out;

	}

	public function v2_pos_search()
	{

		$out = '';
		$this->load->model('plugins_model', 'plugins');
		$billing_settings = $this->plugins->universal_api(67);
		$name = (string)$this->input->post('name', true);
		$cid = (int)$this->input->post('cid', true);
		$wid = (int)$this->input->post('wid', true);
		$enable_bar = (string)$this->input->post('bar', true);
		$flag_p = false;
		$join = '';
		$qw = '';

		if ($wid > 0) {
			$qw .= "(cberp_product_to_store.store_id='$wid') AND ";
			$join .= 'JOIN cberp_product_to_store ON cberp_product_to_store.product_code=cberp_products.product_code ';
		}
		if ($billing_settings['key2']) $qw .= "(cberp_products.expiry IS NULL OR DATE (cberp_products.expiry)<" . date('Y-m-d') . ") AND ";

		if ($cid > 0) {
			//$qw .= "(cberp_products.pcat = '$cid' OR cberp_product_to_category.category_id = '$cid') AND ";
			$qw .= "(cberp_product_to_category.category_id = '$cid') AND ";
			$join .= 'LEFT JOIN cberp_product_to_category ON cberp_product_to_category.product_code = cberp_products.product_code ';
		}
		
		

		// if ($this->aauth->get_user()->loc) {
		// 	$join = 'LEFT JOIN cberp_store ON cberp_store.id=cberp_products.warehouse';
		// 	if (BDATA) $qw .= '(cberp_store.loc=' . $this->aauth->get_user()->loc . ' OR cberp_store.loc=0) AND '; else $qw .= '(cberp_store.loc=' . $this->aauth->get_user()->loc . ' ) AND ';
		// } elseif (!BDATA) {
		// 	$join = 'LEFT JOIN cberp_store ON cberp_store.id=cberp_products.warehouse';
		// 	$qw .= '(cberp_store.loc=0) AND ';
		// }

		$e = '';
		if ($billing_settings['key1'] == 1) {
			$e .= ',cberp_product_serials.serial';
			$join .= 'LEFT JOIN cberp_product_serials ON cberp_product_serials.product_id=cberp_products.product_code ';
			$qw .= '(cberp_product_serials.status=0) AND  ';
		}

		$join .= 'LEFT JOIN cberp_product_description ON cberp_product_description.product_code = cberp_products.product_code ';

		$bar = '';
		$p_class = 'v2_select_pos_item';
		if ($enable_bar == 'true' and is_numeric($name) and strlen($name) >= 8) {
			$flag_p = true;
			$bar = " (cberp_products.barcode = '" . (substr($name, 0, -1)) . "' OR cberp_products.barcode LIKE '" . $name . "%')";

			$query = "SELECT cberp_products.* , cberp_product_description.product_name FROM cberp_products $join WHERE " . $qw . "$bar AND (cberp_products.onhand_quantity>0) ORDER BY cberp_product_description.product_name LIMIT 6";
			$p_class = 'v2_select_pos_item_bar';

		} elseif ($enable_bar == 'false' or !$enable_bar) {
			$flag_p = true;
			if ($billing_settings['key1'] == 2) {

				$query = "SELECT cberp_products.*,cberp_product_description.product_name,cberp_product_serials.serial FROM cberp_product_serials  LEFT JOIN cberp_products  ON cberp_products.product_code=cberp_product_serials.product_id $join WHERE " . $qw . "cberp_product_serials.serial LIKE '" . strtoupper($name) . "%'  AND (cberp_products.onhand_quantity>0) LIMIT 18";

			} else {

				if(!empty($name))
				{
					$query = "SELECT cberp_products.*, cberp_product_description.product_name $e FROM cberp_products $join WHERE " . $qw . "(UPPER(cberp_product_description.product_name) LIKE '%" . strtoupper($name) . "%' $bar OR cberp_products.product_code LIKE '" . strtoupper($name) . "%') AND (cberp_products.onhand_quantity>0) ORDER BY cberp_product_description.product_name LIMIT 18";
				}
				else{
					$query = "SELECT cberp_products.*, cberp_product_description.product_name $e FROM cberp_products $join  WHERE  " . $qw . " (cberp_products.onhand_quantity>0) ORDER BY cberp_product_description.product_name LIMIT 18";
				}
				
			}


		}
		

		 
		if ($flag_p) {	
			$query = $this->db->query($query);
			
			$result = $query->result_array();
			$i = 0;
			$out = '<div class="row match-height">';
			foreach ($result as $row) {
				if ($bar) $bar = $row['barcode'];
				$out .= '    <div class="col-2 border mb-1"  ><div class=" rounded" >
                                 <a  id="posp' . $i . '"  class="' . $p_class . ' round breaklink"   data-name="' . $row['product_name'] . '"  data-price="' . amountExchange_s($row['product_price'], 0, $this->aauth->get_user()->loc) . '"  data-tax="' . amountFormat_general($row['tax_rate']) . '"  data-discount="' . amountFormat_general($row['discount_rate']) . '" data-pcode="' . $row['product_code'] . '"   data-pid="' . $row['product_code'] . '"  data-stock="' . amountFormat_general($row['onhand_quantity']) . '" data-unit="' . $row['unit'] . '" data-serial="' . @$row['serial'] . '" data-bar="' . $bar . '">
                                        <img class="round"
                                             src="' . base_url('userfiles/product/' . $row['image']) . '"  style="max-height: 100%;max-width: 100%">
                                        <div class="text-center" style="margin-top: 4px;">
                                       
                                            <small style="white-space: pre-wrap;">' . $row['product_name'] . '</small>

                                            
                                        </div></a>
                                  
                                </div></div>';

				$i++;

			}


			$out .= '</div>';

			echo $out;
		}


	}

	public function group_pos_search()
	{

		$out = '';
		$this->load->model('plugins_model', 'plugins');
		$billing_settings = $this->plugins->universal_api(67);
		$name = $this->input->post('name', true);
		$cid = $this->input->post('cid', true);
		$wid = $this->input->post('wid', true);


		$qw = '';

		if ($wid > 0) {
			$qw .= "(cberp_product_groups.warehouse='$wid') AND ";
		}

		$join = '';

		// if ($this->aauth->get_user()->loc) {
		// 	$qw .= "(cberp_product_groups.loc='" . $this->aauth->get_user()->loc . "') AND ";
		// 	$join = 'LEFT JOIN cberp_store ON cberp_store.id=cberp_products.warehouse';
		// 	if (BDATA) $qw .= '(cberp_store.loc=' . $this->aauth->get_user()->loc . ' OR cberp_store.loc=0) AND '; else $qw .= '(cberp_store.loc=' . $this->aauth->get_user()->loc . ' ) AND ';
		// } elseif (!BDATA) {
		// 	$join = 'LEFT JOIN cberp_store ON cberp_store.id=cberp_products.warehouse';
		// 	$qw .= '(cberp_store.loc=0) AND ';
		// }

		$e = '';
		if ($billing_settings['key1'] == 1) {
			$e .= ',cberp_product_serials.serial';
			$join .= 'LEFT JOIN cberp_product_serials ON cberp_product_serials.product_id=cberp_products.pid ';
			$qw .= '(cberp_product_serials.status=0) AND  ';
		}

		$bar = '';

		if (is_numeric($name)) {
			$b = array('-', '-', '-');
			$c = array(3, 4, 11);
			$barcode = $name;
			for ($i = count($c) - 1; $i >= 0; $i--) {
				$barcode = substr_replace($barcode, $b[$i], $c[$i], 0);
			}
			//    echo(substr($barcode, 0, -1));
			$bar = " OR (cberp_products.barcode LIKE '" . (substr($barcode, 0, -1)) . "%' OR cberp_products.barcode LIKE '" . $name . "%')";
			//  $query = "SELECT cberp_products.* FROM cberp_products $join WHERE " . $qw . " $bar AND (cberp_products.onhand_quantity>0) LIMIT 16";
		}
		if ($billing_settings['key1'] == 2) {

			$query = "SELECT cberp_products.*,cberp_product_serials.serial FROM cberp_product_serials  LEFT JOIN cberp_products  ON cberp_products.pid=cberp_product_serials.product_id $join WHERE " . $qw . "cberp_product_serials.serial LIKE '" . strtoupper($name) . "%'  AND (cberp_products.onhand_quantity>0) LIMIT 18";

		} else {
			$query = "SELECT cberp_products.* $e FROM cberp_products $join WHERE " . $qw . "(UPPER(cberp_product_description.product_name) LIKE '%" . strtoupper($name) . "%' $bar OR cberp_products.product_code LIKE '" . strtoupper($name) . "%') AND (cberp_products.onhand_quantity>0) ORDER BY cberp_product_description.product_name LIMIT 18";
		}

		$query = $this->db->query($query);
		$result = $query->result_array();
		$i = 0;
		echo '<div class="row match-height">';
		foreach ($result as $row) {

			$out .= '    <div class="col-2 border mb-1"  ><div class=" rounded" >
                                 <a  id="posp' . $i . '"  class="v2_select_pos_item round breaklink"   data-name="' . $row['product_name'] . '"  data-price="' . amountExchange_s($row['product_price'], 0, $this->aauth->get_user()->loc) . '"  data-tax="' . amountFormat_general($row['tax_rate']) . '"  data-discount="' . amountFormat_general($row['discount_rate']) . '" data-pcode="' . $row['product_code'] . '"   data-pid="' . $row['pid'] . '"  data-stock="' . amountFormat_general($row['onhand_quantity']) . '" data-unit="' . $row['unit'] . '" data-serial="' . @$row['serial'] . '">
                                        <img class="round"
                                             src="' . base_url('userfiles/product/' . $row['image']) . '"  style="max-height: 100%;max-width: 100%">
                                        <div class="text-center" style="margin-top: 4px;">
                                       
                                            <small style="white-space: pre-wrap;">' . $row['product_name'] . '</small>

                                            
                                        </div></a>
                                  
                                </div></div>';

			$i++;

		}

		echo $out;

	}

	public function sanitizeString($string) {
		// Remove backslashes
		$string = stripslashes($string);
		// Remove whitespace from the beginning and end of the string
		$string = trim($string);
		// Replace commas with hyphens
		$string = str_replace(',', '-', $string);
		// Remove HTML tags
		$string = strip_tags($string);
		// Convert special characters to HTML entities
		$string = htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
		// Escape single quotes for JavaScript
		$string = str_replace("'", "\'", $string);
		return $string;
	}
	public function searchinproduct()
	{
		$this->load->model('plugins_model', 'plugins');
		$billing_settings = $this->plugins->universal_api(67);
		$result = array();
		$out = array();
		$row_num = $this->input->post('row_num', true);
		$name = $this->input->post('name_startsWith', true);
		$qw = '';
		if ($billing_settings['key2']) $qw .= "(cberp_products.expiry IS NULL OR DATE (cberp_products.expiry)<" . date('Y-m-d') . ") AND ";
		$join = '';

		
		$e = '';
		$productai = ' INNER JOIN cberp_product_description ON cberp_product_description.product_code = cberp_products.product_code';
		$productai .= ' INNER JOIN cberp_product_pricing ON cberp_product_pricing.product_code = cberp_products.product_code';
		if ($billing_settings['key1'] == 1) {
			$e .= ',cberp_product_serials.serial';
			$join .= 'LEFT JOIN cberp_product_serials ON cberp_product_serials.product_id=cberp_products.pid';
			$qw .= '(cberp_product_serials.status=0) AND ';
		}

		if ($name) {

			
				$query = $this->db->query("SELECT cberp_products.pid,cberp_product_description.product_name,cberp_products.product_price,cberp_products.product_code,cberp_products.tax_rate,cberp_products.discount_rate,cberp_product_description.product_description,cberp_products.onhand_quantity,cberp_products.unit $e, cberp_product_pricing.minimum_price as lowest_price, cberp_products.maximum_discount_rate as maxdiscountrate  FROM cberp_products $join $productai WHERE " . $qw . " (UPPER(cberp_products.product_code) LIKE '" . strtoupper($name) . "%') LIMIT 6");
			
			// die($this->db->last_query());
			$result = $query->result_array();
			foreach ($result as $row) {
				$name = array($row['product_name'], amountExchange_s($row['product_price'], 0, $this->aauth->get_user()->loc), $row['pid'], amountFormat_general($row['tax_rate']), amountFormat_general($row['discount_rate']), $row['product_description'], $row['unit'], $row['product_code'], amountFormat_general($row['onhand_quantity']), $row_num, @$row['serial'], $row['lowest_price'],$row['maxdiscountrate']);
				array_push($out, $name);
			}
			echo json_encode($out);
		}

	}
}
