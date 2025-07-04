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
		if ($wid > 0) {
			$qw = "(cberp_products.warehouse='$wid') AND ";
		}
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
		if ($billing_settings['key1'] == 1) {
			$e .= ',cberp_product_serials.serial';
			$join .= 'LEFT JOIN cberp_product_serials ON cberp_product_serials.product_id=cberp_products.pid';
			$qw .= '(cberp_product_serials.status=0) AND ';
		}

		if ($name) {

			if ($billing_settings['key1'] == 2) {
				$e .= ',cberp_product_serials.serial';
				$query = $this->db->query("SELECT cberp_products.pid,cberp_products.product_name,cberp_products.product_price,cberp_products.product_code,cberp_products.taxrate,cberp_products.disrate,cberp_products.product_des,cberp_products.onhand_quantity,cberp_products.unit $e  FROM cberp_product_serials LEFT JOIN cberp_products  ON cberp_products.pid=cberp_product_serials.product_id $join WHERE " . $qw . "(UPPER(cberp_product_serials.serial) LIKE '" . strtoupper($name) . "%')  LIMIT 6");
			} else {
				$query = $this->db->query("SELECT cberp_products.pid,cberp_products.product_name,cberp_products.product_price,cberp_products.product_code,cberp_products.taxrate,cberp_products.disrate,cberp_products.product_des,cberp_products.onhand_quantity,cberp_products.unit $e  FROM cberp_products $join WHERE " . $qw . "(UPPER(cberp_products.product_name) LIKE '%" . strtoupper($name) . "%') OR (UPPER(cberp_products.product_code) LIKE '" . strtoupper($name) . "%') LIMIT 6");
			}

			$result = $query->result_array();
			foreach ($result as $row) {
				$name = array($row['product_name'], amountExchange_s($row['product_price'], 0, $this->aauth->get_user()->loc), $row['pid'], amountFormat_general($row['taxrate']), amountFormat_general($row['disrate']), $row['product_des'], $row['unit'], $row['product_code'], amountFormat_general($row['qty']), $row_num, @$row['serial']);
				array_push($out, $name);
			}
			echo json_encode($out);
		}

	}

	public function searchByProductID()
	{
		$this->load->model('plugins_model', 'plugins');
		$billing_settings = $this->plugins->universal_api(67);
		$result = array();
		$out = array();
		$pid = $this->input->post('pid', true);

		
		$query = $this->db->query("SELECT cberp_products.pid,cberp_products.product_name,cberp_products.product_price,cberp_products.product_code,cberp_products.taxrate,cberp_products.disrate,cberp_products.product_des,cberp_products.onhand_quantity,cberp_products.unit   FROM cberp_products WHERE cberp_products.pid = '$pid'");
		$result = $query->result_array();
		foreach ($result as $row) {
				$name = array($row['product_name'], amountExchange_s($row['product_price'], 0, $this->aauth->get_user()->loc), $row['pid'], amountFormat_general($row['taxrate']), amountFormat_general($row['disrate']), $row['product_des'], $row['unit'], $row['product_code'], amountFormat_general($row['qty']), $row_num, @$row['serial']);
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
		if ($wid > 0) {
			$qw = "(cberp_products.warehouse='$wid' ) AND ";
		}
		$join = '';
		// if ($this->aauth->get_user()->loc) {
		// 	$join = 'LEFT JOIN cberp_store ON cberp_store.id=cberp_products.warehouse';
		// 	if (BDATA) $qw .= '(cberp_store.loc=' . $this->aauth->get_user()->loc . ' OR cberp_store.loc=0) AND '; else $qw .= '(cberp_store.loc=' . $this->aauth->get_user()->loc . ' ) AND ';
		// } elseif (!BDATA) {
		// 	$join = 'LEFT JOIN cberp_store ON cberp_store.id=cberp_products.warehouse';
		// 	$qw .= '(cberp_store.loc=0) AND ';
		// }
		if ($name) {
			$query = $this->db->query("SELECT cberp_products.pid,cberp_products.product_name,cberp_products.product_code,cberp_products.product_cost,cberp_products.taxrate,cberp_products.disrate,cberp_products.product_des,cberp_products.unit FROM cberp_products $join WHERE " . $qw . "UPPER(cberp_products.product_name) LIKE '%" . strtoupper($name) . "%' OR UPPER(cberp_products.product_code) LIKE '" . strtoupper($name) . "%' LIMIT 6");

			$result = $query->result_array();
			foreach ($result as $row) {
				$name = array($row['product_name'], amountExchange_s($row['product_cost'], 0, $this->aauth->get_user()->loc), $row['pid'], amountFormat_general($row['taxrate']), amountFormat_general($row['disrate']), $row['product_des'], $row['unit'], $row['product_code'], $row_num);
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
		// if ($this->aauth->get_user()->loc) {
		// 	$whr = ' (loc=' . $this->aauth->get_user()->loc . ' OR loc=0) AND ';
		// 	if (!BDATA) $whr = ' (loc=' . $this->aauth->get_user()->loc . ' ) AND ';
		// } elseif (!BDATA) {
		// 	$whr = ' (loc=0) AND ';
		// }
		if ($name) {
			$query = $this->db->query("SELECT id,name,address,city,phone,email,discount_c FROM cberp_customers WHERE $whr (UPPER(name)  LIKE '%" . strtoupper($name) . "%' OR UPPER(phone)  LIKE '" . strtoupper($name) . "%') LIMIT 6");
			$result = $query->result_array();
			echo '<ol>';
			$i = 1;
			foreach ($result as $row) {

				echo "<li onClick=\"selectCustomer('" . $row['id'] . "','" . $row['name'] . " ','" . $row['address'] . "','" . $row['city'] . "','" . $row['phone'] . "','" . $row['email'] . "','" . amountFormat_general($row['discount_c']) . "')\"><span>$i</span><p>" . $row['name'] . " &nbsp; &nbsp  " . $row['phone'] . "</p></li>";
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

				echo "<li onClick=\"selectCustomer('" . $row['id'] . "','" . $row['name'] . " ','" . $row['address'] . "','" . $row['city'] . "','" . $row['phone'] . "','" . $row['email'] . "')\"><span>$i</span><p>" . $row['name'] . " &nbsp; &nbsp  " . $row['phone'] . "</p></li>";
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
			$query = $this->db->query("SELECT id,name,phone,discount_c FROM cberp_customers WHERE $whr (UPPER(name)  LIKE '%" . strtoupper($name) . "%' OR UPPER(phone)  LIKE '" . strtoupper($name) . "%') LIMIT 6");
			$result = $query->result_array();
			echo '<ol>';
			$i = 1;
			foreach ($result as $row) {
				echo "<li onClick=\"PselectCustomer('" . $row['id'] . "','" . $row['name'] . " ','" . amountFormat_general($row['discount_c']) . "')\"><span>$i</span><p>" . $row['name'] . " &nbsp; &nbsp  " . $row['phone'] . "</p></li>";
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
			$query = $this->db->query("SELECT id,name,address,city,phone,email FROM cberp_suppliers WHERE $whr (UPPER(name)  LIKE '%" . strtoupper($name) . "%' OR UPPER(phone)  LIKE '" . strtoupper($name) . "%') LIMIT 6");
			$result = $query->result_array();
			echo '<ol>';
			$i = 1;
			foreach ($result as $row) {
				echo "<li onClick=\"selectSupplier('" . $row['id'] . "','" . $row['name'] . " ','" . $row['address'] . "','" . $row['city'] . "','" . $row['phone'] . "','" . $row['email'] . "')\"><span>$i</span><p>" . $row['name'] . " &nbsp; &nbsp  " . $row['phone'] . "</p></li>";
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
			$query = "SELECT cberp_products.* $e FROM cberp_products $join WHERE " . $qw . "(UPPER(cberp_products.product_name) LIKE '%" . strtoupper($name) . "%' $bar OR cberp_products.product_code LIKE '" . strtoupper($name) . "%') AND (cberp_products.onhand_quantity>0) LIMIT 16";

		}


		$query = $this->db->query($query);
		$result = $query->result_array();
		$i = 0;
		echo '<div class="row match-height">';
		foreach ($result as $row) {

			$out .= '<div class="col-3 border mb-1 "><div class="rounded">
                                 <a   id="posp' . $i . '"  class="select_pos_item btn btn-outline-light-blue round"   data-name="' . $row['product_name'] . '"  data-price="' . amountExchange_s($row['product_price'], 0, $this->aauth->get_user()->loc) . '"  data-tax="' . amountFormat_general($row['taxrate']) . '"  data-discount="' . amountFormat_general($row['disrate']) . '"   data-pcode="' . $row['product_code'] . '"   data-pid="' . $row['pid'] . '"  data-stock="' . amountFormat_general($row['qty']) . '" data-unit="' . $row['unit'] . '" data-serial="' . @$row['serial'] . '">
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

		$qw = '';

		if ($wid > 0) {
			$qw .= "(cberp_products.warehouse='$wid') AND ";
		}
		if ($billing_settings['key2']) $qw .= "(cberp_products.expiry IS NULL OR DATE (cberp_products.expiry)<" . date('Y-m-d') . ") AND ";
		if ($cid > 0) {
			$qw .= "(cberp_products.pcat='$cid') AND ";
		}
		$join = '';

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
			$join .= 'LEFT JOIN cberp_product_serials ON cberp_product_serials.product_id=cberp_products.pid ';
			$qw .= '(cberp_product_serials.status=0) AND  ';
		}

		$bar = '';
		$p_class = 'v2_select_pos_item';
		if ($enable_bar == 'true' and is_numeric($name) and strlen($name) >= 8) {
			$flag_p = true;
			$bar = " (cberp_products.barcode = '" . (substr($name, 0, -1)) . "' OR cberp_products.barcode LIKE '" . $name . "%')";

			$query = "SELECT cberp_products.*  FROM cberp_products $join WHERE " . $qw . "$bar AND (cberp_products.onhand_quantity>0) ORDER BY cberp_products.product_name LIMIT 6";
			$p_class = 'v2_select_pos_item_bar';

		} elseif ($enable_bar == 'false' or !$enable_bar) {
			$flag_p = true;
			if ($billing_settings['key1'] == 2) {

				$query = "SELECT cberp_products.*,cberp_product_serials.serial FROM cberp_product_serials  LEFT JOIN cberp_products  ON cberp_products.pid=cberp_product_serials.product_id $join WHERE " . $qw . "cberp_product_serials.serial LIKE '" . strtoupper($name) . "%'  AND (cberp_products.onhand_quantity>0) LIMIT 18";

			} else {

				$query = "SELECT cberp_products.* $e FROM cberp_products $join WHERE " . $qw . "(UPPER(cberp_products.product_name) LIKE '%" . strtoupper($name) . "%' $bar OR cberp_products.product_code LIKE '" . strtoupper($name) . "%') AND (cberp_products.onhand_quantity>0) ORDER BY cberp_products.product_name LIMIT 18";
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
                                 <a  id="posp' . $i . '"  class="' . $p_class . ' round"   data-name="' . $row['product_name'] . '"  data-price="' . amountExchange_s($row['product_price'], 0, $this->aauth->get_user()->loc) . '"  data-tax="' . amountFormat_general($row['taxrate']) . '"  data-discount="' . amountFormat_general($row['disrate']) . '" data-pcode="' . $row['product_code'] . '"   data-pid="' . $row['pid'] . '"  data-stock="' . amountFormat_general($row['qty']) . '" data-unit="' . $row['unit'] . '" data-serial="' . @$row['serial'] . '" data-bar="' . $bar . '">
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
			$query = "SELECT cberp_products.* $e FROM cberp_products $join WHERE " . $qw . "(UPPER(cberp_products.product_name) LIKE '%" . strtoupper($name) . "%' $bar OR cberp_products.product_code LIKE '" . strtoupper($name) . "%') AND (cberp_products.onhand_quantity>0) ORDER BY cberp_products.product_name LIMIT 18";
		}

		$query = $this->db->query($query);
		$result = $query->result_array();
		$i = 0;
		echo '<div class="row match-height">';
		foreach ($result as $row) {

			$out .= '    <div class="col-2 border mb-1"  ><div class=" rounded" >
                                 <a  id="posp' . $i . '"  class="v2_select_pos_item round"   data-name="' . $row['product_name'] . '"  data-price="' . amountExchange_s($row['product_price'], 0, $this->aauth->get_user()->loc) . '"  data-tax="' . amountFormat_general($row['taxrate']) . '"  data-discount="' . amountFormat_general($row['disrate']) . '" data-pcode="' . $row['product_code'] . '"   data-pid="' . $row['pid'] . '"  data-stock="' . amountFormat_general($row['qty']) . '" data-unit="' . $row['unit'] . '" data-serial="' . @$row['serial'] . '">
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

}
