<div class="card card-block">
    <div id="notify" class="alert alert-success" style="display:none;">
        <a href="#" class="close" data-dismiss="alert">&times;</a>

        <div class="message"></div>
    </div>
    <div class="card-body">
        <h5 class="title"> <?php echo $this->lang->line('Default Accounts') ?> </h5>
        <hr>
        <div class="card card-block formborder">
            <form method="post" id="data_form" class="form-horizontal">
                <?php
                    function renderAccountDropdown($name, $accountheaders, $accountlists, $accountparent, $defaultaccounts, $last_log) {
                        $previous_selected = "";
                        $reciecveflg = 1;
                        $printedParents = [];
                        $shownChildren = [];
            
                        echo '<select name="' . htmlspecialchars($name) . '" id="' . htmlspecialchars($name) . '" class="form-control">';
                        echo '<option value="">Select Type</option>';
            
                        foreach ($accountheaders as $parentItem) {
                            $coaHeaderId = $parentItem['coa_header_id'];
                            $coaHeader = $parentItem['coa_header'];
            
                            if (isset($accountlists[$coaHeaderId])) {
                                echo '<optgroup label="' . htmlspecialchars($coaHeader) . '">';
            
                                // Show parent and its children
                                if (!empty($accountparent[$coaHeaderId])) {
                                    foreach ($accountparent[$coaHeaderId] as $parentId => $childAccounts) {
                                        foreach ($accountlists[$coaHeaderId] as $acc) {
                                            if ($acc['id'] == $parentId && !in_array($parentId, $printedParents)) {
                                                echo '<option disabled style="font-weight:bold;">' . htmlspecialchars($acc['acn'])." - ".htmlspecialchars($acc['holder']) . '</option>';
                                                $printedParents[] = $parentId;
                                                break;
                                            }
                                        }
            
                                        foreach ($childAccounts as $child) {
                                            $childacn = $child['acn'];
                                            $childholder = $child['holder'];
                                            $childid = $child['id'];
                                            $childSelected = ($defaultaccounts[$name] == $childacn) ? 'selected' : '';
                                            if ($last_log[$name] == $childacn && $reciecveflg == 1) {
                                                $previous_selected = $childacn . " - " . htmlspecialchars($childholder);
                                                $reciecveflg = 0;
                                            }
            
                                            echo '<option value="' . htmlspecialchars($childacn) . '" data-id="' . htmlspecialchars($childholder) . '" ' . $childSelected . '>&nbsp;&nbsp;&nbsp;&nbsp;↳ ' . htmlspecialchars($childacn)." - ".htmlspecialchars($childholder) . '</option>';
            
                                            $shownChildren[] = $childid;
                                        }
                                    }
                                }
            
                                // Show standalone accounts (not child of any)
                                foreach ($accountlists[$coaHeaderId] as $row) {
                                    $aid = $row['id'];
            
                                    if (in_array($aid, $shownChildren) || in_array($aid, $printedParents)) {
                                        continue;
                                    }
            
                                    $holder = $row['holder'];
                                    $acn = $row['acn'];
                                    $selted = "";
            
                                    if ($last_log[$name] == $acn && $reciecveflg == 1) {
                                        $previous_selected = $acn . " - " . htmlspecialchars($holder);
                                        $reciecveflg = 0;
                                    }
            
                                    if ($defaultaccounts[$name] == $acn) {
                                        $selted = 'selected';
                                    }
            
                                    echo '<option value="' . htmlspecialchars($acn) . '" data-id="' . htmlspecialchars($holder) . '" ' . $selted . '>' . htmlspecialchars($acn)." - ".htmlspecialchars($holder) . '</option>';
                                }
            
                                echo '</optgroup>';
                            }
                        }
            
                        echo '</select>';
                        echo '<label class="col-form-label"><i> Previous : ' . $previous_selected . '</i></label>';
                    }
                ?>
                <div class="form-group row">                
                    <div class="col-12"><h4>Default Chart of Accounts</h4><hr></div>
                    <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="address"><?php echo $this->lang->line('Accounts Receivable') ?><span class="compulsoryfld">*</span></label>
                            
                            <?php                            
                            renderAccountDropdown('accounts_receivable', $accountheaders, $accountlists, $accountparent, $defaultaccounts, $last_log);   
                            ?>
                    </div>

                    <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="address"><?php echo $this->lang->line('Accounts Payable') ?><span class="compulsoryfld">*</span></label>
                        <?php
                            renderAccountDropdown('accounts_payable', $accountheaders, $accountlists, $accountparent, $defaultaccounts, $last_log);   
                            // $previous_accounts_payable ="";
                            // $accountspayableflg=1;
                            // echo '<select name="accounts_payable" id="accounts_payable" class="form-control">';
                            // echo '<option value="">Select Type</option>';
                            // foreach ($accountheaders as $parentItem) {
                            //     $coaHeaderId = $parentItem['coa_header_id'];
                            //     $coaHeader = $parentItem['coa_header'];
                            //     if (isset($accountlists[$coaHeaderId])) {
                            //         echo '<optgroup label="' . htmlspecialchars($coaHeader) . '">';                                          
                            //     foreach ($accountlists[$coaHeaderId] as $row) {
                            //         $aid = $row['id'];
                            //         $holder = $row['holder'];
                            //         $balance = $row['lastbal'];
                            //         $type = $row['account_type'];
                            //         $acn = $row['acn'];
                            //         $selted = "";
                            //         if ($last_log['accounts_payable'] == $acn & $accountspayableflg==1) {
                            //             $previous_accounts_payable = $acn." - ".htmlspecialchars($holder);
                            //             $accountspayableflg=0;
                            //         }
                            //         if ($defaultaccounts['accounts_payable'] == $acn) {
                            //             $selted = 'selected';
                            //         }
                            //         echo '<option value="' . htmlspecialchars($acn) . '" data-id="' . htmlspecialchars($holder) . '" ' . $selted . '>' . $acn." - ".htmlspecialchars($holder) . '</option>';
                                    
                            //     }
                            // }
                            // }
                            // echo '</select>';
                            // echo '<label class="col-form-label"><i>'.$this->lang->line('Previous').' : '.$previous_accounts_payable.'</i></label>';
                            ?>
                            
                    </div>

                    <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="address"><?php echo $this->lang->line('Sales') ?><span class="compulsoryfld">*</span></label>
                            
                        <?php
                            renderAccountDropdown('sales', $accountheaders, $accountlists, $accountparent, $defaultaccounts, $last_log);
                            // $previous_sales ="";
                            // $salesflg=1;
                            // echo '<select name="sales" id="sales" class="form-control">';
                            // echo '<option value="">Select Type</option>';
                            // foreach ($accountheaders as $parentItem) {
                            //     $coaHeaderId = $parentItem['coa_header_id'];
                            //     $coaHeader = $parentItem['coa_header'];
                            //     if (isset($accountlists[$coaHeaderId])) {
                            //         echo '<optgroup label="' . htmlspecialchars($coaHeader) . '">';                                          
                            //     foreach ($accountlists[$coaHeaderId] as $row) {
                            //         $aid = $row['id'];
                            //         $holder = $row['holder'];
                            //         $balance = $row['lastbal'];
                            //         $type = $row['account_type'];
                            //         $acn = $row['acn'];
                            //         $selted = "";
                            //         if ($last_log['sales'] == $acn & $salesflg==1) {
                            //             $previous_sales = $acn." - ".htmlspecialchars($holder);
                            //             $salesflg=0;
                            //         }
                            //         if ($defaultaccounts['sales'] == $acn) {
                            //             $selted = 'selected';
                            //         }
                            //         echo '<option value="' . htmlspecialchars($acn) . '" data-id="' . htmlspecialchars($holder) . '" ' . $selted . '>' . $acn." - ".htmlspecialchars($holder) . '</option>';
                                    
                            //     }
                            // }
                            // }
                            // echo '</select>';
                            // echo '<label class="col-form-label"><i>'.$this->lang->line('Previous').' : '.$previous_sales.'</i></label>';
                            ?>
                    </div>
                    
                    <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="address"><?php echo $this->lang->line('General Expenses') ?><span class="compulsoryfld">*</span></label>
                            
                            <?php
                             renderAccountDropdown('general_expenses', $accountheaders, $accountlists, $accountparent, $defaultaccounts, $last_log);
                            ?>
                    </div>

                    <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="address"><?php echo $this->lang->line('Sales Discount') ?><span class="compulsoryfld">*</span></label>
                            
                            <?php
                            renderAccountDropdown('sales_discount', $accountheaders, $accountlists, $accountparent, $defaultaccounts, $last_log);
                            ?>
                    </div>

                    <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="address"><?php echo $this->lang->line('Order Discount') ?><span class="compulsoryfld">*</span></label>
                            
                            <?php
                             renderAccountDropdown('order_discount', $accountheaders, $accountlists, $accountparent, $defaultaccounts, $last_log);
                            ?>
                    </div>
                   

                    <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="address"><?php echo $this->lang->line('Shipping') ?><span class="compulsoryfld">*</span></label>
                            
                            <?php
                            renderAccountDropdown('shipping', $accountheaders, $accountlists, $accountparent, $defaultaccounts, $last_log);
                            // $previous_previous_shipping ="";
                            // $shippingflg=1;
                            // echo '<select name="shipping" id="shipping" class="form-control">';
                            // echo '<option value="">Select Type</option>';
                            // foreach ($accountheaders as $parentItem) {
                            //     $coaHeaderId = $parentItem['coa_header_id'];
                            //     $coaHeader = $parentItem['coa_header'];
                            //     if (isset($accountlists[$coaHeaderId])) {
                            //         echo '<optgroup label="' . htmlspecialchars($coaHeader) . '">';                                          
                            //     foreach ($accountlists[$coaHeaderId] as $row) {
                            //         $aid = $row['id'];
                            //         $holder = $row['holder'];
                            //         $balance = $row['lastbal'];
                            //         $type = $row['account_type'];
                            //         $acn = $row['acn'];
                            //         $selted = "";
                            //         if ($last_log['shipping'] == $acn & $shippingflg==1) {
                            //             $previous_previous_shipping = $acn." - ".htmlspecialchars($holder);
                            //             $shippingflg=0;
                            //         }
                            //         if ($defaultaccounts['shipping'] == $acn) {
                            //             $selted = 'selected';
                            //         }
                            //         echo '<option value="' . htmlspecialchars($acn) . '" data-id="' . htmlspecialchars($holder) . '" ' . $selted . '>' . $acn." - ".htmlspecialchars($holder) . '</option>';
                                    
                            //     }
                            // }
                            // }
                            // echo '</select>';
                            // echo '<label class="col-form-label"><i>'.$this->lang->line('Previous').' : '.$previous_previous_shipping.'</i></label>';
                            ?>
                    </div>

                    <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="address"><?php echo $this->lang->line('Purchase Account') ?><span class="compulsoryfld">*</span></label>
                            
                            <?php
                            renderAccountDropdown('purchase_account', $accountheaders, $accountlists, $accountparent, $defaultaccounts, $last_log);
                            // $previous_purchase_account ="";
                            // $purchase_accountflg=1;
                            // echo '<select name="purchase_account" id="purchase_account" class="form-control">';
                            // echo '<option value="">Select Type</option>';
                            // foreach ($accountheaders as $parentItem) {
                            //     $coaHeaderId = $parentItem['coa_header_id'];
                            //     $coaHeader = $parentItem['coa_header'];
                            //     if (isset($accountlists[$coaHeaderId])) {
                            //         echo '<optgroup label="' . htmlspecialchars($coaHeader) . '">';                                          
                            //     foreach ($accountlists[$coaHeaderId] as $row) {
                            //         $aid = $row['id'];
                            //         $holder = $row['holder'];
                            //         $balance = $row['lastbal'];
                            //         $type = $row['account_type'];
                            //         $acn = $row['acn'];
                            //         $selted = "";
                            //         if ($last_log['purchase_account'] == $acn & $purchase_accountflg==1) {
                            //             $previous_purchase_account = $acn." - ".htmlspecialchars($holder);
                            //             $purchase_accountflg=0;
                            //         }
                            //         if ($defaultaccounts['purchase_account'] == $acn) {
                            //             $selted = 'selected';
                            //         }
                            //         echo '<option value="' . htmlspecialchars($acn) . '" data-id="' . htmlspecialchars($holder) . '" ' . $selted . '>' . $acn." - ".htmlspecialchars($holder) . '</option>';
                                    
                            //     }
                            // }
                            // }
                            // echo '</select>';
                            // echo '<label class="col-form-label"><i>'.$this->lang->line('Previous').' : '.$previous_purchase_account.'</i></label>';
                            ?>
                    </div>

                    <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="address"><?php echo $this->lang->line('Purchase Discount') ?><span class="compulsoryfld">*</span></label>
                            
                            <?php
                             renderAccountDropdown('purchase_discount', $accountheaders, $accountlists, $accountparent, $defaultaccounts, $last_log);
                            // $previous_purchase_discount ="";
                            // $purchase_discountflg=1;
                            // echo '<select name="purchase_discount" id="purchase_discount" class="form-control">';
                            // echo '<option value="">Select Type</option>';
                            // foreach ($accountheaders as $parentItem) {
                            //     $coaHeaderId = $parentItem['coa_header_id'];
                            //     $coaHeader = $parentItem['coa_header'];
                            //     if (isset($accountlists[$coaHeaderId])) {
                            //         echo '<optgroup label="' . htmlspecialchars($coaHeader) . '">';                                          
                            //     foreach ($accountlists[$coaHeaderId] as $row) {
                            //         $aid = $row['id'];
                            //         $holder = $row['holder'];
                            //         $balance = $row['lastbal'];
                            //         $type = $row['account_type'];
                            //         $acn = $row['acn'];
                            //         $selted = "";
                            //         if ($last_log['purchase_discount'] == $acn & $purchase_discountflg==1) {
                            //             $previous_purchase_discount = $acn." - ".htmlspecialchars($holder);
                            //             $purchase_discountflg=0;
                            //         }
                            //         if ($defaultaccounts['purchase_discount'] == $acn) {
                            //             $selted = 'selected';
                            //         }
                            //         echo '<option value="' . htmlspecialchars($acn) . '" data-id="' . htmlspecialchars($holder) . '" ' . $selted . '>' . $acn." - ".htmlspecialchars($holder) . '</option>';
                                    
                            //     }
                            // }
                            // }
                            // echo '</select>';
                            // echo '<label class="col-form-label"><i>'.$this->lang->line('Previous').' : '.$previous_purchase_discount.'</i></label>';
                            ?>
                    </div>

                    <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="address"><?php echo $this->lang->line('Owners Contribution') ?><span class="compulsoryfld">*</span></label>
                            
                            <?php
                             renderAccountDropdown('owners_contribution', $accountheaders, $accountlists, $accountparent, $defaultaccounts, $last_log);
                            // $previous_owners_contribution ="";
                            // $owners_contributionflg=1;
                            // echo '<select name="owners_contribution" id="owners_contribution" class="form-control">';
                            // echo '<option value="">Select Type</option>';
                            // foreach ($accountheaders as $parentItem) {
                            //     $coaHeaderId = $parentItem['coa_header_id'];
                            //     $coaHeader = $parentItem['coa_header'];
                            //     if (isset($accountlists[$coaHeaderId])) {
                            //         echo '<optgroup label="' . htmlspecialchars($coaHeader) . '">';                                          
                            //     foreach ($accountlists[$coaHeaderId] as $row) {
                            //         $aid = $row['id'];
                            //         $holder = $row['holder'];
                            //         $balance = $row['lastbal'];
                            //         $type = $row['account_type'];
                            //         $acn = $row['acn'];
                            //         $selted = "";
                            //         if ($last_log['owners_contribution'] == $acn & $owners_contributionflg==1) {
                            //             $previous_owners_contribution = $acn." - ".htmlspecialchars($holder);
                            //             $owners_contributionflg=0;
                            //         }
                            //         if ($defaultaccounts['owners_contribution'] == $acn) {
                            //             $selted = 'selected';
                            //         }
                            //         echo '<option value="' . htmlspecialchars($acn) . '" data-id="' . htmlspecialchars($holder) . '" ' . $selted . '>' . $acn." - ".htmlspecialchars($holder) . '</option>';
                                    
                            //     }
                            // }
                            // }
                            // echo '</select>';
                            // echo '<label class="col-form-label"><i>'.$this->lang->line('Previous').' : '.$previous_owners_contribution.'</i></label>';
                            ?>
                    </div>

                    <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="address"><?php echo $this->lang->line('Inventory') ?><span class="compulsoryfld">*</span></label>
                            
                            <?php
                             renderAccountDropdown('inventory', $accountheaders, $accountlists, $accountparent, $defaultaccounts, $last_log);
                            // $previous_inventory ="";
                            // $inventoryflg=1;
                            // echo '<select name="inventory" id="inventory" class="form-control">';
                            // echo '<option value="">Select Type</option>';
                            // foreach ($accountheaders as $parentItem) {
                            //     $coaHeaderId = $parentItem['coa_header_id'];
                            //     $coaHeader = $parentItem['coa_header'];
                            //     if (isset($accountlists[$coaHeaderId])) {
                            //         echo '<optgroup label="' . htmlspecialchars($coaHeader) . '">';                                          
                            //     foreach ($accountlists[$coaHeaderId] as $row) {
                            //         $aid = $row['id'];
                            //         $holder = $row['holder'];
                            //         $balance = $row['lastbal'];
                            //         $type = $row['account_type'];
                            //         $acn = $row['acn'];
                            //         $selted = "";
                            //         if ($last_log['inventory'] == $acn & $inventoryflg==1) {
                            //             $previous_inventory = $acn." - ".htmlspecialchars($holder);
                            //             $inventoryflg=0;
                            //         }
                            //         if ($defaultaccounts['inventory'] == $acn) {
                            //             $selted = 'selected';
                            //         }
                            //         echo '<option value="' . htmlspecialchars($acn) . '" data-id="' . htmlspecialchars($holder) . '" ' . $selted . '>' . $acn." - ".htmlspecialchars($holder) . '</option>';
                                    
                            //     }
                            // }
                            // }
                            // echo '</select>';
                            // echo '<label class="col-form-label"><i>'.$this->lang->line('Previous').' : '.$previous_inventory.'</i></label>';
                            ?>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="address"><?php echo $this->lang->line('Cost of Goods Sold') ?><span class="compulsoryfld">*</span></label>
                            
                            <?php
                             renderAccountDropdown('cost_of_goods_solid', $accountheaders, $accountlists, $accountparent, $defaultaccounts, $last_log);
                            // $previous_cost_of_goods_solid ="";
                            // $cost_of_goods_solidflg=1;
                            // echo '<select name="cost_of_goods_solid" id="cost_of_goods_solid" class="form-control">';
                            // echo '<option value="">Select Type</option>';
                            // foreach ($accountheaders as $parentItem) {
                            //     $coaHeaderId = $parentItem['coa_header_id'];
                            //     $coaHeader = $parentItem['coa_header'];
                            //     if (isset($accountlists[$coaHeaderId])) {
                            //         echo '<optgroup label="' . htmlspecialchars($coaHeader) . '">';                                          
                            //     foreach ($accountlists[$coaHeaderId] as $row) {
                            //         $aid = $row['id'];
                            //         $holder = $row['holder'];
                            //         $balance = $row['lastbal'];
                            //         $type = $row['account_type'];
                            //         $acn = $row['acn'];
                            //         $selted = "";
                            //         if ($last_log['cost_of_goods_solid'] == $acn & $cost_of_goods_solidflg==1) {
                            //             $previous_cost_of_goods_solid = $acn." - ".htmlspecialchars($holder);
                            //             $cost_of_goods_solidflg=0;
                            //         }
                            //         if ($defaultaccounts['cost_of_goods_solid'] == $acn) {
                            //             $selted = 'selected';
                            //         }
                            //         echo '<option value="' . htmlspecialchars($acn) . '" data-id="' . htmlspecialchars($holder) . '" ' . $selted . '>' . $acn." - ".htmlspecialchars($holder) . '</option>';
                                    
                            //     }
                            // }
                            // }
                            // echo '</select>';
                            // echo '<label class="col-form-label"><i>'.$this->lang->line('Previous').' : '.$previous_cost_of_goods_solid.'</i></label>';
                            ?>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="address"><?php echo $this->lang->line('Sales Returns') ?><span class="compulsoryfld">*</span></label>
                            
                            <?php
                             renderAccountDropdown('sales_returns', $accountheaders, $accountlists, $accountparent, $defaultaccounts, $last_log);
                            // $previous_sales_returns ="";
                            // $sales_returnsflg=1;
                            // echo '<select name="sales_returns" id="sales_returns" class="form-control">';
                            // echo '<option value="">Select Type</option>';
                            // foreach ($accountheaders as $parentItem) {
                            //     $coaHeaderId = $parentItem['coa_header_id'];
                            //     $coaHeader = $parentItem['coa_header'];
                            //     if (isset($accountlists[$coaHeaderId])) {
                            //         echo '<optgroup label="' . htmlspecialchars($coaHeader) . '">';                                          
                            //     foreach ($accountlists[$coaHeaderId] as $row) {
                            //         $aid = $row['id'];
                            //         $holder = $row['holder'];
                            //         $balance = $row['lastbal'];
                            //         $type = $row['account_type'];
                            //         $acn = $row['acn'];
                            //         $selted = "";
                            //         if ($last_log['sales_returns'] == $acn & $sales_returnsflg==1) {
                            //             $previous_sales_returns = $acn." - ".htmlspecialchars($holder);
                            //             $sales_returnsflg=0;
                            //         }
                            //         if ($defaultaccounts['sales_returns'] == $acn) {
                            //             $selted = 'selected';
                            //         }
                            //         echo '<option value="' . htmlspecialchars($acn) . '" data-id="' . htmlspecialchars($holder) . '" ' . $selted . '>' . $acn." - ".htmlspecialchars($holder) . '</option>';
                                    
                            //     }
                            // }
                            // }
                            // echo '</select>';
                            // echo '<label class="col-form-label"><i>'.$this->lang->line('Previous').' : '.$previous_sales_returns.'</i></label>';
                            ?>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="address"><?php echo $this->lang->line('Product Income') ?><span class="compulsoryfld">*</span></label>
                            
                            <?php
                            renderAccountDropdown('product_income', $accountheaders, $accountlists, $accountparent, $defaultaccounts, $last_log);
                            // $previous_product_income ="";
                            // $product_incomeflg=1;
                            // echo '<select name="product_income" id="product_income" class="form-control">';
                            // echo '<option value="">Select Type</option>';
                            // foreach ($accountheaders as $parentItem) {
                            //     $coaHeaderId = $parentItem['coa_header_id'];
                            //     $coaHeader = $parentItem['coa_header'];
                            //     if (isset($accountlists[$coaHeaderId])) {
                            //         echo '<optgroup label="' . htmlspecialchars($coaHeader) . '">';                                          
                            //     foreach ($accountlists[$coaHeaderId] as $row) {
                            //         $aid = $row['id'];
                            //         $holder = $row['holder'];
                            //         $balance = $row['lastbal'];
                            //         $type = $row['account_type'];
                            //         $acn = $row['acn'];
                            //         $selted = "";
                            //         if ($last_log['product_income'] == $acn & $product_incomeflg==1) {
                            //             $previous_product_income = $acn." - ".htmlspecialchars($holder);
                            //             $product_incomeflg=0;
                            //         }
                            //         if ($defaultaccounts['product_income'] == $acn) {
                            //             $selted = 'selected';
                            //         }
                            //         echo '<option value="' . htmlspecialchars($acn) . '" data-id="' . htmlspecialchars($holder) . '" ' . $selted . '>' . $acn." - ".htmlspecialchars($holder) . '</option>';
                                    
                            //     }
                            // }
                            // }
                            // echo '</select>';
                            // echo '<label class="col-form-label"><i>'.$this->lang->line('Previous').' : '.$previous_product_income.'</i></label>';
                            ?>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="address"><?php echo $this->lang->line('Product Expense') ?><span class="compulsoryfld">*</span></label>
                            
                            <?php
                            renderAccountDropdown('product_expense', $accountheaders, $accountlists, $accountparent, $defaultaccounts, $last_log);
                            // $previous_product_expense ="";
                            // $product_expenseflg=1;
                            // echo '<select name="product_expense" id="product_expense" class="form-control">';
                            // echo '<option value="">Select Type</option>';
                            // foreach ($accountheaders as $parentItem) {
                            //     $coaHeaderId = $parentItem['coa_header_id'];
                            //     $coaHeader = $parentItem['coa_header'];
                            //     if (isset($accountlists[$coaHeaderId])) {
                            //         echo '<optgroup label="' . htmlspecialchars($coaHeader) . '">';                                          
                            //     foreach ($accountlists[$coaHeaderId] as $row) {
                            //         $aid = $row['id'];
                            //         $holder = $row['holder'];
                            //         $balance = $row['lastbal'];
                            //         $type = $row['account_type'];
                            //         $acn = $row['acn'];
                            //         $selted = "";
                            //         if ($last_log['product_expense'] == $acn & $product_expenseflg==1) {
                            //             $previous_product_expense = $acn." - ".htmlspecialchars($holder);
                            //             $product_expenseflg=0;
                            //         }
                            //         if ($defaultaccounts['product_expense'] == $acn) {
                            //             $selted = 'selected';
                            //         }
                            //         echo '<option value="' . htmlspecialchars($acn) . '" data-id="' . htmlspecialchars($holder) . '" ' . $selted . '>' . $acn." - ".htmlspecialchars($holder) . '</option>';
                                    
                            //     }
                            // }
                            // }
                            // echo '</select>';
                            // echo '<label class="col-form-label"><i>'.$this->lang->line('Previous').' : '.$previous_product_expense.'</i></label>';
                            ?>
                    </div>

                    <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="address"><?php echo $this->lang->line('Costing Account') ?><span class="compulsoryfld">*</span></label>
                            
                            <?php
                            renderAccountDropdown('costing_account', $accountheaders, $accountlists, $accountparent, $defaultaccounts, $last_log);
                            // $previous_costing_account ="";
                            // $costing_accountflg=1;
                            // echo '<select name="costing_account" id="costing_account" class="form-control">';
                            // echo '<option value="">Select Type</option>';
                            // foreach ($accountheaders as $parentItem) {
                            //     $coaHeaderId = $parentItem['coa_header_id'];
                            //     $coaHeader = $parentItem['coa_header'];
                            //     if (isset($accountlists[$coaHeaderId])) {
                            //         echo '<optgroup label="' . htmlspecialchars($coaHeader) . '">';                                          
                            //     foreach ($accountlists[$coaHeaderId] as $row) {
                            //         $aid = $row['id'];
                            //         $holder = $row['holder'];
                            //         $balance = $row['lastbal'];
                            //         $type = $row['account_type'];
                            //         $acn = $row['acn'];
                            //         $selted = "";
                            //         if ($last_log['costing_account'] == $acn & $costing_accountflg==1) {
                            //             $previous_costing_account = $acn." - ".htmlspecialchars($holder);
                            //             $costing_accountflg=0;
                            //         }
                            //         if ($defaultaccounts['costing_account'] == $acn) {
                            //             $selted = 'selected';
                            //         }
                            //         echo '<option value="' . htmlspecialchars($acn) . '" data-id="' . htmlspecialchars($holder) . '" ' . $selted . '>' . $acn." - ".htmlspecialchars($holder) . '</option>';
                                    
                            //     }
                            // }
                            // }
                            // echo '</select>';
                            // echo '<label class="col-form-label"><i>'.$this->lang->line('Previous').' : '.$previous_costing_account.'</i></label>';
                            ?>
                    </div>

                    <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="address"><?php echo $this->lang->line('Damage Account') ?><span class="compulsoryfld">*</span></label>
                            
                            <?php
                            renderAccountDropdown('damage_account', $accountheaders, $accountlists, $accountparent, $defaultaccounts, $last_log);
                            // $previous_damage_account ="";
                            // $costing_accountflg=1;
                            // echo '<select name="damage_account" id="damage_account" class="form-control">';
                            // echo '<option value="">Select Type</option>';
                            // foreach ($accountheaders as $parentItem) {
                            //     $coaHeaderId = $parentItem['coa_header_id'];
                            //     $coaHeader = $parentItem['coa_header'];
                            //     if (isset($accountlists[$coaHeaderId])) {
                            //         echo '<optgroup label="' . htmlspecialchars($coaHeader) . '">';                                          
                            //     foreach ($accountlists[$coaHeaderId] as $row) {
                            //         $aid = $row['id'];
                            //         $holder = $row['holder'];
                            //         $balance = $row['lastbal'];
                            //         $type = $row['account_type'];
                            //         $acn = $row['acn'];
                            //         $selted = "";
                            //         if ($last_log['damage_account'] == $acn & $costing_accountflg==1) {
                            //             $previous_damage_account = $acn." - ".htmlspecialchars($holder);
                            //             $costing_accountflg=0;
                            //         }
                            //         if ($defaultaccounts['damage_account'] == $acn) {
                            //             $selted = 'selected';
                            //         }
                            //         echo '<option value="' . htmlspecialchars($acn) . '" data-id="' . htmlspecialchars($holder) . '" ' . $selted . '>' . $acn." - ".htmlspecialchars($holder) . '</option>';
                                    
                            //     }
                            // }
                            // }
                            // echo '</select>';
                            // echo '<label class="col-form-label"><i>'.$this->lang->line('Previous').' : '.$previous_damage_account.'</i></label>';
                            ?>
                    </div>
                   


                    <div class="col-12 mt-2 text-right">
                        <input type="submit" id="account-type-btn" class="btn btn-crud btn-primary btn-lg margin-bottom"
                            value="<?php echo $this->lang->line('Modify') ?>" data-loading-text="Adding...">
                        
                            <!-- <input type="hidden" value="productpricing/edit" id="action-url"> -->
                        <!-- <input type="hidden" value="<?php echo $id ?>" name="id"> -->
                    </div>
                </div>

            </form>
        </div>

    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        // $("#accounts_receivable").trigger('change');
        $("#accounts_receivable").select2({
            placeholder: "Select Accounts Receivable", 
            allowClear: true,
            width: '100%'
        }); 
        $("#accounts_payable").select2({
            placeholder: "Select Accounts Payable", 
            allowClear: true,
            width: '100%'
        }); 
        $("#sales").select2({
            placeholder: "Select Sales Account", 
            allowClear: true,
            width: '100%'
        }); 
        $("#general_expenses").select2({
            placeholder: "Select General Expenses Account", 
            allowClear: true,
            width: '100%'
        }); 
        $("#sales_discount").select2({
            placeholder: "Select Sales Discount Account", 
            allowClear: true,
            width: '100%'
        }); 
        $("#order_discount").select2({
            placeholder: "Select Order Discount Account", 
            allowClear: true,
            width: '100%'
        }); 
        $("#shipping").select2({
            placeholder: "Select Shipping Account", 
            allowClear: true,
            width: '100%'
        }); 
        $("#purchase_discount").select2({
            placeholder: "Select Purchase Discount Account", 
            allowClear: true,
            width: '100%'
        }); 
        $("#purchase_account").select2({
            placeholder: "Select Purchase Account", 
            allowClear: true,
            width: '100%'
        }); 
        $("#owners_contribution").select2({
            placeholder: "Select Owners Contibution Account", 
            allowClear: true,
            width: '100%'
        }); 
        $("#inventory").select2({
            placeholder: "Select Inventory Account", 
            allowClear: true,
            width: '100%'
        }); 
        $("#cost_of_goods_solid").select2({
            placeholder: "Select Cost of Goods Sold Account", 
            allowClear: true,
            width: '100%'
        }); 
        $("#sales_returns").select2({
            placeholder: "Select Sales Returns Account", 
            allowClear: true,
            width: '100%'
        }); 
        $("#product_income").select2({
            placeholder: "Select Product Income Account", 
            allowClear: true,
            width: '100%'
        }); 
        $("#product_expense").select2({
            placeholder: "Select Product Expense Account", 
            allowClear: true,
            width: '100%'
        }); 
        $("#costing_account").select2({
            placeholder: "Select Costing Account", 
            allowClear: true,
            width: '100%'
        }); 
        $("#damage_account").select2({
            placeholder: "Select Damage Account", 
            allowClear: true,
            width: '100%'
        }); 

        $("#data_form").validate({
            ignore: [],
            rules: {               
                accounts_receivable: { required: true },
                accounts_payable: { required: true },
                sales: { required: true },
                general_expenses: { required: true },
                sales_discount: { required: true },
                order_discount: { required: true },
                shipping: { required: true },
                purchase_account: { required: true },
                purchase_discount: { required: true },
                owners_contribution: { required: true },
                inventory: { required: true },
                cost_of_goods_solid: { required: true },
                sales_returns: { required: true },
                damage_account: { required: true },
            },
            messages: {
                accounts_receivable: "Select Account Receivable Account",
                accounts_payable: "Select Account Payable Account",
                sales: "Select Sales Account",
                general_expenses: "Select General Expense Account",
                sales_discount: "Select Sales Discount Account",
                order_discount: "Select Order Discount Account",
                shipping: "Select Shipping Account",
                purchase_account: "Select Purchase Account",
                purchase_discount: "Select Purchase Discount Account",
                owners_contribution: "Select Owners Contribution Account",
                inventory: "Select Inventory Account",
                cost_of_goods_solid: "Select Cost of Goods Sold Account",
                sales_returns: "Select Sales Returns Account",
                damage_account: "Select Damage Account",
            },
            

            errorElement: "em",
            errorPlacement: function(error, element) {
                error.addClass("help-block");
                if (element.prop("type") === "checkbox") {
                    error.insertAfter(element.parent("label"));
                } else {
                    error.insertAfter(element);
                }
            },
            highlight: function(element, errorClass, validClass) {
                $(element).parents(".col-sm-5").addClass("has-error").removeClass("has-success");
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).parents(".col-sm-5").addClass("has-success").removeClass("has-error");
            },
            invalidHandler: function(event, validator) {
                // Focus on the first invalid element
                if (validator.errorList.length) {
                    $(validator.errorList[0].element).focus();
                }
            }
        });
        //datatables
        $('#catgtable').DataTable({responsive: true});

    });

    $('#account-type-btn').on('click', function(e) {
        e.preventDefault(); // Prevent the default form submission
        $('#account-type-btn').prop('disabled', true);
        
        // Validate the form
        if ($("#data_form").valid()) {                
            var form = $('#data_form')[0];
            var formData = new FormData(form); 
            Swal.fire({
                title: "Are you sure?",
                text: "Do you want to modify default accounts?",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, proceed!',
                cancelButtonText: "No - Cancel",
                reverseButtons: true,  
                focusCancel: true,      
                allowOutsideClick: false,  // Disable outside click
            }).then((result) => {
                if (result.isConfirmed) {
                    
                    $.ajax({
                        url: baseurl + 'defaultaccounts/addeditaction', // Replace with your server endpoint
                        type: 'POST',
                        data: formData,
                        contentType: false, 
                        processData: false,
                        success: function(response) {
                            if (typeof response === "string") {
                                response = JSON.parse(response);
                            }
                            location.reload();
                            
                        },
                        error: function(xhr, status, error) {
                            Swal.fire('Error', 'An error occurred while generating the lead', 'error');
                            console.log(error); // Log any errors
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    // Enable the button again if user cancels
                    $('#account-type-btn').prop('disabled', false);
                }
            });
        } else {
            // If form validation fails, re-enable the button
            $('#account-type-btn').prop('disabled', false);
        }
    });
</script>