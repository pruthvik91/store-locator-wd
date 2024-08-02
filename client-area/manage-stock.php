<?php
$title = "Manage Stock";
include('header.php');
global $db;
/* Get Current User Id By Session*/
$userid = $_SESSION["userid"];
if (true) {
	$enable_inventory_options = "";
	$deleted = false;
	$checkDelete = false;
	$emptyDelete = false;
	$searchQuery = "";
	$searchname = "";
	$searchcatename = "";
	$searchstockmin = "";
	$searchstockmax = "";
	$searchinstock = "";
	if ((!empty($_POST))) {
		if (!empty($_POST["btnsubmit"]) && $_POST["btnsubmit"] == "submit" && isset($_POST['inventory2'])) {

			$i = count($_POST['inventory2']);

			for ($j = 0; $j < $i; $j++) {

				$product_id = $_POST['hidden_product_id'][$j];
				$remarks = $_POST['remarks'][$j];
				if (isset($_POST['inventory2'][$j]) && $_POST['inventory2'][$j] != "") {
					$inventory = $_POST['inventory2'][$j];
				} else {
					$inventory = "0.00";
				}
				$sqlupdate = 'UPDATE ' . DB_BASE . '.product set items_available=items_available+:items_available where product_id=:product_id and user_id=:user_id';
				$preparedStatement = $db->prepare($sqlupdate);
				$preparedStatement->execute(
					array(
						':user_id' => $userid,
						':items_available' => $inventory,
						':product_id' => $product_id
					)
				);
				add_transaction($db, $_SESSION["userid"], $_SESSION['staffaccount_id'], $product_id, $product_id, "product_stock", TRANSACTION_UPDATE);

				// add stock summary table
				if ($inventory > 0 || $inventory < 0) {
					add_summary($userid, $product_id, $inventory, $remarks);
				}
			}
			header("Location: manage-stock.php?mode=update");
			exit;
		}

		// BY Name to Search Product Code
		if ((isset($_POST["btnSearch"]) && $_POST["btnSearch"] == "Search")) {
			$searchname = $_POST["productname"];
			$searchcatename = $_POST["category_name"];
			$searchinstock = $_POST["instock"];
			$searchstockmin = $_POST["searchstockmin"];
			$searchstockmax = $_POST["searchstockmax"];

			if (!empty($searchname)) {
				$searchQuery = $searchQuery . " and  ( p.name LIKE '%" . str_replace("'", "''", $searchname) . "%'  or  p.product_attr_1_val LIKE '%" . str_replace("'", "''", $searchname) . "%'  or  p.product_attr_2_val LIKE '%" . str_replace("'", "''", $searchname) . "%'  or  p.product_attr_3_val LIKE '%" . str_replace("'", "''", $searchname) . "%'  or  p.product_attr_4_val LIKE '%" . str_replace("'", "''", $searchname) . "%' ) ";
			}
			if (!empty($searchcatename)) {
				$searchQuery = $searchQuery . " and  vtb.category_name LIKE '%" . str_replace("'", "''", $searchcatename) . "%'";
			}
			if (isset($searchstockmin) && $searchstockmin != "") {
				$searchQuery = $searchQuery . " and  p.items_available >= " . $searchstockmin;
			}
			if (isset($searchstockmax) && $searchstockmax != "") {
				$searchQuery = $searchQuery . " and  p.items_available <= " . $searchstockmax;
			}
			if (!empty($searchinstock) && $searchinstock == "-1") {
				$searchQuery = $searchQuery . " and p.items_available < 0";
			}
			if (!empty($searchinstock) && $searchinstock == "0.00") {
				$searchQuery = $searchQuery . " and p.items_available = " . $searchinstock;
			}
			if (!empty($searchinstock) && $searchinstock == "1") {
				$searchQuery = $searchQuery . " and p.items_available >= " . $searchinstock;
			}
			if (!empty($searchinstock) && $searchinstock == "10") {
				$searchQuery = $searchQuery . " and p.items_available BETWEEN 1 AND 10 ";
			}
			if (!empty($searchinstock) && $searchinstock == "50") {
				$searchQuery = $searchQuery . " and p.items_available BETWEEN 11 AND 50 ";
			}
			if (!empty($searchinstock) && $searchinstock == "above50") {
				$searchQuery = $searchQuery . " and p.items_available > 50 ";
			}
		}
	} else {
		if (!empty($_GET["searchname"])) {
			$searchname = $_GET["searchname"];
			$searchQuery = $searchQuery . " and  ( p.name LIKE '%" . str_replace("'", "''", $searchname) . "%'  or  p.product_attr_1_val LIKE '%" . str_replace("'", "''", $searchname) . "%'  or  p.product_attr_2_val LIKE '%" . str_replace("'", "''", $searchname) . "%'  or  p.product_attr_3_val LIKE '%" . str_replace("'", "''", $searchname) . "%'  or  p.product_attr_4_val LIKE '%" . str_replace("'", "''", $searchname) . "%' ) ";
		}
		if (isset($_GET["searchcatename"]) && $_GET["searchcatename"] != "") {
			$searchcatename = $_GET["searchcatename"];
			$searchQuery = $searchQuery . " and  vtb.category_name LIKE '%" . str_replace("'", "''", $_GET["searchcatename"]) . "%'";
		}
		if (isset($_GET["searchstockmin"]) && $_GET["searchstockmin"] != "") {
			$searchstockmin = $_GET["searchstockmin"];
			$searchQuery = $searchQuery . " and  p.items_available >= " . $_GET["searchstockmin"];
		}
		if (isset($_GET["searchstockmax"]) && $_GET["searchstockmax"] != "") {
			$searchstockmax = $_GET["searchstockmax"];
			$searchQuery = $searchQuery . " and  p.items_available >= " . $_GET["searchstockmax"];
		}
		if (!empty($_GET["searchinstock"]) && $_GET["searchinstock"] == "-1") {
			$searchinstock = $_GET["searchinstock"];
			$searchQuery = $searchQuery . " and p.items_available < 0";
		}
		if (!empty($_GET["searchinstock"]) && $_GET["searchinstock"] == "0.00") {
			$searchinstock = $_GET["searchinstock"];
			$searchQuery = $searchQuery . " and p.items_available = " . $searchinstock;
		}
		if (!empty($_GET["searchinstock"]) && $_GET["searchinstock"] == "1") {
			$searchinstock = $_GET["searchinstock"];
			$searchQuery = $searchQuery . " and p.items_available >= " . $searchinstock;
		}
		if (!empty($_GET["searchinstock"]) && $_GET["searchinstock"] == "10") {
			$searchinstock = $_GET["searchinstock"];
			$searchQuery = $searchQuery . " and p.items_available BETWEEN 1 AND 10 ";
		}
		if (!empty($_GET["searchinstock"]) && $_GET["searchinstock"] == "50") {
			$searchinstock = $_GET["searchinstock"];
			$searchQuery = $searchQuery . " and p.items_available BETWEEN 11 AND 50 ";
		}
		if (!empty($_GET["searchinstock"]) && $_GET["searchinstock"] == "above50") {
			$searchinstock = $_GET["searchinstock"];
			$searchQuery = $searchQuery . " and p.items_available > 50 ";
		}
	} //

	//search User base current user query
	$searchUser = " and p.user_id = '$userid'";

	include('paginate.php'); //include of paginat page
	if (isset($_POST["btnSearch"]) == "Search") {
		$cur_page = 1;
	} else {
		$cur_page = isset($_GET["page"]) ? $_GET["page"] : 1;
	}
	$per_page = 10;         // number of results to show per page

	if ($cur_page == 1) {
		$page_start = 0;
	} else {
		$page_start = (($cur_page - 1) * $per_page);
	}


	$result1 = $db->prepare("SELECT p.product_id FROM " . DB_BASE . ".product p LEFT JOIN (SELECT pg.product_id,pg.user_id,c.category_name from " . DB_BASE . ".product_group_detail pg LEFT JOIN " . DB_BASE . ".product_category c ON pg.product_category_id = c.product_category_id and c.is_active = 1 and c.is_deleted = 0 and c.user_id = '$userid' WHERE pg.user_id = '$userid' GROUP BY pg.product_id) vtb on vtb.product_id = p.product_id and vtb.user_id = '$userid' where 1=1 and p.non_salable_product !=1 and p.is_service_product=0 and p.is_active=1 and p.is_transport!=1  " . $searchQuery . " " . $searchUser . " AND p.is_deleted != 1 order by p.name");
	$result1->execute();
	$rows1 = $result1->fetchAll(PDO::FETCH_OBJ);
	$rowcount1 = count($rows1);

	//include of paginat page
	$total_results = $rowcount1;
	$total_pages = intval($total_results / $per_page); //total pages we going to have

	if ($total_pages == 0) {
		$total_pages = 1;
	} elseif (($total_pages * $per_page) < $total_results) {
		$total_pages++;
	}

	$result = $db->prepare("SELECT p.product_id,p.product_note,p.stockType,vtb.category_name,p.product_attr_1_val,p.product_attr_2_val,p.product_attr_3_val,p.product_attr_4_val,p.name,p.items_available,IF(p.stockType = 1,pb.purchase_price,p.purchaseprice) as purchaseprice,IF(p.stockType = 1,pb.price,p.sellprice) as sellprice,p.hsn,p.uom,IF((p.purchaseprice = 0.00), (0 * p.items_available), (p.purchaseprice * p.items_available)) as stockvalue,IF((p.sellprice = 0.00), (0 * p.items_available), (p.sellprice * p.items_available)) as slstockvalue,p.igst,p.cgst,p.sgst,p.cess,p.cess_amount  FROM " . DB_BASE . ".product p LEFT JOIN ".DB_BASE.".product_batch pb ON pb.product_id = p.product_id AND pb.user_id = p.user_id LEFT JOIN (SELECT pg.product_id,pg.user_id, GROUP_CONCAT( c.category_name ) as category_name from " . DB_BASE . ".product_group_detail pg LEFT JOIN " . DB_BASE . ".product_category c ON pg.product_category_id = c.product_category_id and c.is_active = 1 and c.is_deleted = 0 and c.user_id = '$userid' WHERE pg.user_id = '$userid' GROUP BY pg.product_id) vtb on vtb.product_id = p.product_id and vtb.user_id = '$userid' where 1=1 and p.non_salable_product !=1 and p.is_service_product=0 and p.is_active=1  and p.is_transport!=1  " . $searchQuery . " " . $searchUser . " AND p.is_deleted != 1 GROUP BY p.product_id order by p.name limit " . $page_start . "," . $per_page);

	$result->execute();
	$rows = $result->fetchAll(PDO::FETCH_OBJ);
	$rowcount = count($rows);



?>
	<!--  Product Inventory Option Table  -->
	<?php
	/*Change satatus In Attribe*/
	if (isset($_POST['mode']) && ($_POST['mode'] == 'changestatus')) {

		$isactive = $_POST['isactive'];

		$settingsresult = $db->prepare("select general_options from " . DB_MAIN . ".settings where user_id = :userid");
		$settingsresult->execute(array(':userid' => $_SESSION["userid"]));
		$settingsrows = $settingsresult->fetchAll(PDO::FETCH_OBJ);
		$settingsrowcount = count($settingsrows);
		if ($settingsrowcount > 0) {
			foreach ($settingsrows as $settingsrs) {
				$general_options = $settingsrs->general_options;
			}
		}
		$general_options = json_decode($general_options, true);
		$general_options['enable_inventory_options'] = $isactive;
		$general_options = json_encode($general_options);

		$sqlupdate = 'UPDATE ' . DB_MAIN . '.settings set
				general_options=:general_options,
				modifydate=DATE_ADD(NOW(), INTERVAL ' . DB_TIMEDIFF . ' MINUTE) where user_id = :userid';
		$preparedStatement = $db->prepare($sqlupdate);
		$preparedStatement->execute(array(
			':general_options' => $general_options,
			':userid' => $_SESSION["userid"]
		));

		add_transaction($db, $_SESSION["userid"], $_SESSION['staffaccount_id'], $_SESSION["userid"], "", "invoice_option_inventory_option", TRANSACTION_UPDATE);
		$_SESSION['inventory_options'] =  ($isactive == 1 ? true : false);
		$settings = $_SESSION['settings'];
		$settings = json_decode($settings, true);
		$settings['enable_inventory_options'] = $isactive;
		$settings = json_encode($settings);
		$_SESSION['settings'] = $settings;
	}


	$rowsinventory = get_settings();
	$rowcountinventory = count($rowsinventory);
	?>

	<script>
		var availableProducts = [];
	</script>
	<?php

	$sqlpro = "select p.name,c.category_name,p.product_attr_1_val,p.product_attr_2_val,p.product_attr_3_val,p.product_attr_4_val from " . DB_BASE . ".product p LEFT JOIN " . DB_BASE . ".product_category c ON p.product_category_id = c.product_category_id and c.is_active = 1 and c.is_deleted != 1  where p.user_id = '" . $userid . "' and p.non_salable_product !=1 AND p.is_deleted != 1 order by p.name";
	$resultpro = $db->prepare("$sqlpro");
	$resultpro->execute();
	$rowspro = $resultpro->fetchAll(PDO::FETCH_OBJ);
	$rowcountpro = count($rowspro);
	if (isset($rowcountpro) && $rowcountpro != "") {
		if ($rowcountpro > 0) {
	?>
			<script>
				availableProducts = [
					<?php
					$procounter = 0;
					foreach ($rowspro as $product) {
						if ($procounter == 0) {
						} else {
							echo ',';
						}

						$lbl_string  = "";
						if ($product->product_attr_1_val != "") {
							$lbl_string .= $product->product_attr_1_val . " / ";
						}
						if ($product->product_attr_2_val != "") {
							$lbl_string .= $product->product_attr_2_val . " / ";
						}
						if ($product->product_attr_3_val != "") {
							$lbl_string .= $product->product_attr_3_val . " / ";
						}
						if ($product->product_attr_4_val != "") {
							$lbl_string .= $product->product_attr_4_val . " / ";
						}
						$lbl_string  = rtrim($lbl_string, " ");
						$lbl_string  = rtrim($lbl_string, "/");
						if ($lbl_string != "") {
							$newlbl_string  = $product->name . " ( " . $lbl_string . ")";
						} else {
							$newlbl_string  = $product->name;
						}


						echo '{
				value: "' . addslashes($newlbl_string) . '",
				label: "' . addslashes($newlbl_string) . '"
			}';
						$procounter++;
					}
					?>
				];
			</script>


	<?php
		}
	}

	?>
	<form name="tabledata" method="post" style="display: inline-block;width: 240px;">
		<label style="font-weight: bold;float: left;line-height: 25px;margin-right: 10px;">Inventory Option</label>
		<?php
		if ($rowcountinventory > 0) {
			foreach ($rowsinventory as $rsinventory) {
				$enable_inventory_options = $rsinventory->enable_inventory_options;
				if ($rsinventory->enable_inventory_options == '1') {
		?>
					<input type="checkbox" class="checkbox" id="inventoryoption" name="inventoryoption" checked="checked" onchange="this.form.submit()">
					<label for="inventoryoption"><span></span></label>
					<input type="hidden" name="isactive" value="0" />
				<?php
				} else {
				?>
					<input type="checkbox" class="checkbox" id="inventoryoption" name="inventoryoption" onchange="this.form.submit()">
					<label for="inventoryoption"><span></span></label>
					<input type="hidden" name="isactive" value="1" />
		<?php
				}
			}
		}
		?>
		<input type="hidden" name="mode" value="changestatus" />
		<input type="submit" name="stockbtn" value="stockbtn" style="visibility:hidden;position:absolute;height:0px;width:0px;" />
	</form><!-- /Form -->

	<?php if (isset($enable_inventory_options) && $enable_inventory_options == "1") { ?>


		<script>
			var autoProductList = [];
			var autoCategoryList = [];
		</script>
		<?php
		$sqlautoCust = "SELECT p.product_id, p.name FROM " . DB_BASE . ".product p where p.is_transport = '0' and p.user_id = '$userid' AND p.is_deleted != 1 AND p.non_salable_product !=1 AND p.is_active = 1 order by p.is_active desc , p.name ASC";
		$resultautoCust = $db->prepare("$sqlautoCust");
		$resultautoCust->execute();
		$rowsautoCust = $resultautoCust->fetchAll(PDO::FETCH_OBJ);
		$rowcountautoCust = count($rowsautoCust);
		if ($rowcountautoCust > 0) {
		?>
			<script>
				autoProductList = [
					<?php
					$autoCustomercounter = 0;
					foreach ($rowsautoCust as $rs1) {
						if ($autoCustomercounter == 0) {
						} else {
							echo ',';
						}
						echo '{
					label: "' . addslashes($rs1->name) . '"
				}';
						$autoCustomercounter++;
					}
					?>
				];
			</script>
		<?php
		}

		$sqlautoCat = "SELECT product_category_id, category_name FROM " . DB_BASE . ".product_category where user_id = '$userid' and is_deleted != 1 order by category_name";
		$resultautoCat = $db->prepare("$sqlautoCat");
		$resultautoCat->execute();
		$rowsautoCat = $resultautoCat->fetchAll(PDO::FETCH_OBJ);
		$rowcountautoCat = count($rowsautoCat);
		if ($rowcountautoCat > 0) {
		?>
			<script>
				autoCategoryList = [
					<?php
					$autoCatcounter = 0;
					foreach ($rowsautoCat as $rs1) {
						if ($autoCatcounter == 0) {
						} else {
							echo ',';
						}
						echo '{
					label: "' . addslashes($rs1->category_name) . '"
				}';
						$autoCatcounter++;
					}
					?>
				];
			</script>
		<?php
		}
		?>
		<form method="post">
			<div class="widget" id="ContentPlace_trmsg">
				<div class="widget-header container-header">
					<h3>Manage Stock</h3>
					<div class="search_summary_buttons_wrap">
						<a class="btn btn-search pull-right"><i class="fa fa-search" style="color:#555555;"></i>Search</a>
					</div>
				</div>
				<div class="widget-content widget-content-search widget-content-toggle closed" style="display:none;width:100%;">
					<hr class="seperator-search-box" />

					<div class="control-group-search-container">
						<div class="control-group-search" style="float:left; margin-left:10px;">
							<label class="control-label" for="firstname">Search By Product Name :</label>
							<input type="text" class="span3" name="productname" id="autoProductField" value="<?php echo $searchname; ?>" placeholder="Enter your search product name" />
						</div>
						<div class="control-group-search" style="float:left; margin-left:10px;">
							<label class="control-label" for="firstname">Search By Product Group :</label>
							<input type="text" class="span3" name="category_name" id="autoCategoryField" value="<?php echo $searchcatename; ?>" placeholder="Enter your search product group" />
						</div>
						<div class="control-group-search" style="float:left; margin-left:10px;">
							<label class="control-label" for="firstname">Stock Available (Min) :</label>
							<input type="text" class="span3" name="searchstockmin" value="<?php echo $searchstockmin; ?>" placeholder="Enter stock avaiable more than value" />
						</div>
						<div class="control-group-search" style="float:left; margin-left:10px;">
							<label class="control-label" for="firstname">Stock Available (Max) :</label>
							<input type="text" class="span3" name="searchstockmax" value="<?php echo $searchstockmax; ?>" placeholder="Enter stock avaiable less than value" />
						</div>
						<div class="control-group-search" style="float:left; margin-left:10px;">
							<label class="control-label" for="firstname">Search By Stock :</label>
							<select class="span3" name="instock" id="instock">
								<option value="">--- Select Stock ---</option>
								<option value="-1" <?php if ($searchinstock == "-1") {
															echo "selected";
														} ?>>Less than 0</option>
								<option value="0.00" <?php if ($searchinstock == "0.00") {
															echo "selected";
														} ?>>Equal to 0</option>
								<option value="1" <?php if ($searchinstock == "1") {
														echo "selected";
													} ?>>Greater than 0</option>
								<option value="10" <?php if ($searchinstock == "10") {
														echo "selected";
													} ?>>Between 1 to 10</option>
								<option value="50" <?php if ($searchinstock == "50") {
														echo "selected";
													} ?>>Between 11 to 50</option>
								<option value="above50" <?php if ($searchinstock == "above50") {
															echo "selected";
														} ?>>More Than 50</option>
							</select>
						</div>
						<div class="list-detail-search-action">
							<div class=" control-group-search control-group-search-action search-btn" style="margin-left:10px; margin-top:20px;">
								<button type="submit" class="btn btn-primary mng-stk-srch-btn" value="Search" name="btnSearch"><i class="fa fa-search"></i>Search</button>

							</div>
						</div>
					</div>

				</div>
			</div> <!-- /widget-content -->
		</form>
		<script>
			$(document).ready(function() {
				$("#autoProductField").autocomplete({
					source: autoProductList,
					change: function(event, ui) {
						if (ui.item == null || ui.item == undefined) {} else {
							if (ui.item) {
								$("#autoProductField").val(ui.item.label);
							}
						}
					},
					select: function(event, ui) {
						$("#autoProductField").val(ui.item.label);
						return false;
					},
					minLength: 0
				}).focus(function() {
					$(this).autocomplete("search");
				});

				$("#autoCategoryField").autocomplete({
					source: autoCategoryList,
					change: function(event, ui) {
						if (ui.item == null || ui.item == undefined) {} else {
							if (ui.item) {
								$("#autoCategoryField").val(ui.item.label);
							}
						}
					},
					select: function(event, ui) {
						$("#autoCategoryField").val(ui.item.label);
						return false;
					},
					minLength: 0
				}).focus(function() {
					$(this).autocomplete("search");
				});
			});
		</script>

		<?php include('ad1-1.php'); ?>
		<div class="row">
			<div class="span12">
				<form name="tabledata" method="post">
					<?php
					$successmsg = '';
					$errormsg = '';
					if ((isset($_GET["mode"])) && ($_GET["mode"] == "update")) {
						$successmsg = 'Stock Details Updated successfully.';
					}
					if ((isset($_GET["mode"])) && ($_GET["mode"] == "insert")) {
						$successmsg = 'Stock Details Inserted successfully.';
					}

					if ($deleted) {
						$successmsg = 'Stock Details Deleted successfully.';
					}

					if ($emptyDelete) {
						$errormsg = ' You do not have any selected any Product to delete.';
					}
					if ($checkDelete) {
						$errormsg = ' You can not delete this Product. its details are used in Invoice.';
					}


					if ($successmsg != '') { ?>
						<script>
							Toast.fire({
								icon: 'success',
								title: '<?php echo $successmsg; ?>'
							});
						</script>
					<?php }
					if ($errormsg != '') { ?>
						<script>
							Swal.fire({
								icon: 'error',
								title: '<?php echo ucwords($errormsg); ?>'
							});
						</script>
					<?php }
					?>
					<div class="widget widget-table action-table">

						<div class="widget-content">
							<table class="table wrap_table manage-stock-table " data-footable data-toggle-column="last">
								<thead>
									<tr>
										<th>Name</th>
										<th data-breakpoints="sm">Product Group</th>
										<th data-breakpoints="sm" class="text-right">Purchase Price</th>
										<th data-breakpoints="sm" class="text-right">Sell Price</th>
										<th data-breakpoints="md">HSN Code</th>
										<th data-breakpoints="md">UOM</th>
										<th>Current Stock</th>
										<?php
										if ($_SESSION['inventory_options']) {
										?>
											<th data-breakpoints="sm" style="">Change In Stock</th>
											<th data-breakpoints="sm" style="">New Stock</th>
											<th data-breakpoints="md" style="">Remarks</th>
										<?php
										}
										?>
									</tr>
								</thead>
								<tbody>

									<?php
									if ($rowcount > 0) {
										$Row_count = 0;
										foreach ($rows as $rs) {
											$Row_count ++;
									?>
											<tr>
												<td> <?php

														$lbl_string  = "";
														if ($rs->product_attr_1_val != "") {
															$lbl_string .= $rs->product_attr_1_val . " / ";
														}
														if ($rs->product_attr_2_val != "") {
															$lbl_string .= $rs->product_attr_2_val . " / ";
														}
														if ($rs->product_attr_3_val != "") {
															$lbl_string .= $rs->product_attr_3_val . " / ";
														}
														if ($rs->product_attr_4_val != "") {
															$lbl_string .= $rs->product_attr_4_val . " / ";
														}
														$lbl_string  = rtrim($lbl_string, " ");
														$lbl_string  = rtrim($lbl_string, "/");
														if ($lbl_string != "") {
															$newlbl_string  = $rs->name . " ( " . $lbl_string . ")";
														} else {
															$newlbl_string  = $rs->name;
														}
														echo $newlbl_string;
														if (isset($product_description) && $product_description == 1 && isset($rs->product_note) && $rs->product_note != "") {
															echo '<p style="margin: 0;font-style: italic;color: #969696;">' . $rs->product_note . '</p>';
														} ?></td>
												<td><?php echo $rs->category_name; ?></td>
												<td class="text-right"> <?php echo number_format_indian($rs->purchaseprice, $price_decimal_value); ?> </td>
												<td class="text-right"> <?php echo number_format_indian($rs->sellprice, $price_decimal_value); ?> </td>
												<td> <?php echo $rs->hsn; ?> </td>
												<td> <?php echo $rs->uom; ?> </td>
												<td <?php echo ($rs->stockType != 1)?'id="qunatity_'.$Row_count.'"':''; ?> > <?php echo number_format_indian($rs->items_available, $quantity_decimal_value); ?> </td>
												<?php
												if ($_SESSION['inventory_options']) {
													if ($rs->stockType == 0) {
												?>

													<td>
														<input style="width:auto;" type="text" name="changein[]" id="changein_<?php echo $Row_count; ?>" value="" maxlength="10" data-row-no = "<?php echo $Row_count; ?>" oninput="calculateDifferenceWithChangeInStock(this)" onkeypress="return allowOnlyNumber(event);"/>
													</td>
													<td>
														<input style="width:auto;" type="text" name="inventory[]" id="inventory_<?php echo $Row_count; ?>" value="" maxlength="10" data-row-no = "<?php echo $Row_count; ?>" oninput="calculateDifferenceWithNewStock(this)" onkeypress="return allowOnlyNumber(event);" />
														<input style="width:auto;" type="hidden" name="inventory2[]" id="inventory2_<?php echo $Row_count; ?>" value="" />
														<input type="hidden" name="hidden_product_id[]" id="product_id" value="<?php echo $rs->product_id; ?>" />
													</td>
													<td>
														<input type="text" style="width:auto;" name="remarks[]" id="remarks"></label>
													</td>
												<?php }else{ ?>
													<td></td>
													<td>
														<input type="hidden" name="changein[]">
														<input type="hidden" name="inventory[]">
														<input type="hidden" name="inventory2[]">
														<input type="hidden" name="remarks[]">
														<input type="hidden" name="hidden_product_id[]" id="product_id" value="<?php echo $rs->product_id; ?>" />
													</td>
													<td>
														<a data-productid="<?php echo $rs->product_id; ?>" data-row-no = "<?php echo $Row_count; ?>" data-stocktype="<?php echo $rs->stockType; ?>" data-igst="<?php echo $rs->igst; ?>" data-cgst="<?php echo $rs->cgst; ?>" data-sgst="<?php echo $rs->sgst; ?>" data-cess="<?php echo $rs->cess; ?>" data-cess_amount="<?php echo $rs->cess_amount; ?>" data-item_availble ="<?php echo $rs->items_available; ?>" class="btn btn-primary managestock">Manage Stock</a>
													</td>
												<?php } ?>
												<?php
												}
												?>
											</tr>
									<?php
										}
									}
									?>
								</tbody>
							</table>
						</div>
					</div>
					<?php

					if ($rowcount > 0) {
					?>
						<div class="form-actions">
							<button class="btn btn-primary" name="btnsubmit" type="submit" value="submit"><i class="fa fa-save"></i>Save</button>
							<a href="list-product.php" class="btn btn_white"><i class="fa fa-chevron-left"></i>Back</a>
							<?php if ($rowcount > 0) { ?><a href="import-product-stock.php" class="btn  btn_white  pull-right"><i class="fa fa-download"></i>Bulk Edit Stock</a><?php } ?>
						</div>
					<?php
					}
					if ($total_pages > 1) {
						$url_string = "searchname=" . $searchname .
							"&searchcatename=" . $searchcatename .
							"&searchstockmin=" . $searchstockmin .
							"&searchstockmax=" . $searchstockmax .
							"&searchinstock=" . $searchinstock;
						echo paginate('manage-stock.php?' . $url_string, $cur_page, $total_pages);
					}
					?>


				</form>
			</div>
		</div>

		<!-- Modal -->
		<div id="ManageStockModal" class="popup_right_slider modal fade" role="dialog" style="display: none;">
			<div class="modal-dialog">

				<!-- Modal content-->
				<form class="form-horizontal" method="post" id="ManageStockForm">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="btn btn-default btn_close" data-dismiss="modal"><i class="fa fa-close"></i></button>
						<h4 class="modal-title">Manage Stock</h4>
					</div>
					<div class="modal-body">
						<div class="row">
		            		<div class="span12">
			                    <div class="widget widget-table action-table">
			                    	<div class="manage-header">
				                        <h4 class="productName has_modal_lbl"></h4>
				                        <div class="btn btn-primary right_modal_btn has_modal_lbl_btn addProductBatch"><i class="fa fa-plus" aria-hidden="true"></i>Add Batch</div>
				                    </div>
			                        <div class="widget-content getListDataTbl">
			                           
			                        </div>
			                    </div>
			                    <div class="batch-section" style="display: none;">
			                        <div class="widget-content ">
			                        	<h4 class="has_modal_lbl">Batch Stock</h4>
			                        	<hr>
			                        	<table class="table  table-bordered">
											<thead>
												<tr>
													<?php if($enable_batch_no == 1){ ?><th><?php echo $label_batch_no; ?></th><?php } ?>
													<?php if($enable_batch_model == 1){ ?><th><?php echo $label_batch_model; ?></th><?php } ?>
													<?php if($enable_batch_size == 1){ ?><th><?php echo $label_batch_size; ?></th><?php } ?>
													<?php if($enable_batch_mfg == 1){ ?><th><?php echo $label_batch_mfg; ?></th><?php } ?>
													<?php if($enable_batch_expiry == 1){ ?><th><?php echo $label_batch_expiry;?></th><?php } ?>
													<th>Quantity</th>
													<?php if($enable_product_mrp == 1){ ?><th><?php echo $label_product_mrp; ?></th><?php } ?>
													<th>Sale Price</th>
													<th>Sale Price (Incl. Tax)</th>
													<th>Purchase Price</th>
													<th>Purchase Price (Incl. Tax)</th>
													<th></th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<?php if($enable_batch_no == 1){ ?><td><div class="inputlabel_wrapper"><input type="text" name="batch_name[]" placeholder="<?php echo $label_batch_no; ?>" autocomplete="off"  maxlength="20"><label for="batch_name[]"><?php echo $label_batch_no; ?></label></div></td><?php }
													 if($enable_batch_model == 1){ ?><td><div class="inputlabel_wrapper"><input type="text" name="model_no[]" autocomplete="off" placeholder="<?php echo $label_batch_model; ?>"  maxlength="20"><label for="model_no[]"><?php echo $label_batch_model; ?></label></div></td><?php } ?>
													<?php if($enable_batch_size == 1){ ?><td><div class="inputlabel_wrapper"><input type="text" name="size[]" autocomplete="off" placeholder="<?php echo $label_batch_size; ?>"  maxlength="20"><label for="size[]"><?php echo $label_batch_size; ?></label></div></td><?php } ?>
													<?php if($enable_batch_mfg == 1){ ?><td><div class="inputlabel_wrapper"><input type="text" class="monthyear_datepicker"  autocomplete="off" placeholder="<?php echo $label_batch_mfg; ?>" name="manufacture_date[]"><label for="manufacture_date[]"><?php echo $label_batch_mfg; ?></label></div></td><?php } ?>
													<?php if($enable_batch_expiry == 1){ ?><td><div class="inputlabel_wrapper"><input type="text" class="monthyear_datepicker"  autocomplete="off" placeholder="<?php echo $label_batch_expiry; ?>" name="expiry_date[]"><label for="expiry_date[]"><?php echo $label_batch_expiry; ?></label></div></td><?php } ?>
													<td>
														<div class="inputlabel_wrapper">
															<input type="text" name="quantity[]" placeholder="Qty">
															<input type="hidden" name="product_batch_id[]">
															<?php if($enable_batch_no != 1){?><input type="hidden" name="batch_name[]"> <?php } ?>
															<?php if($enable_batch_model != 1){?><input type="hidden" name="model_no[]"> <?php } ?>
															<?php if($enable_batch_size != 1){?><input type="hidden" name="size[]"> <?php } ?>
															<?php if($enable_batch_mfg != 1){?><input type="hidden" name="manufacture_date[]"> <?php } ?>
															<?php if($enable_batch_expiry != 1){?><input type="hidden" name="expiry_date[]"> <?php } ?>
															<?php if($enable_product_mrp != 1){?><input type="hidden" name="mrp[]"> <?php } ?>
															<label for="quantity[]">Qty.</label>
														</div>
													</td>
													<?php if($enable_product_mrp == 1){ ?><td><div class="inputlabel_wrapper"><input type="text" name="mrp[]" autocomplete="off" placeholder="<?php echo $label_product_mrp; ?>"><label for="mrp[]"><?php echo $label_product_mrp; ?></label></div></td><?php } ?>
													<td>
														<div class="inputlabel_wrapper">
															<input type="text" name="price[]" autocomplete="off" placeholder="Sale Price" class="sellprice">
															<label for="price[]">Sale Price</label>
														</div>
													</td>
													<td>
														<div class="inputlabel_wrapper">
															<input type="text" name="sellpriceincltax[]" autocomplete="off"  placeholder="Sale Price (Incl. Tax)" class="sellpriceincltax">
															<label for="sellpriceincltax[]">Sale Price (Incl. Tax)</label>
														</div>
													</td>
													<td>
														<div class="inputlabel_wrapper">
															<input type="text" name="purchase_price[]" autocomplete="off"  placeholder="Purchase Price">
															<label for="purchase_price[]">Purchase Price</label>
														</div>
													</td>
													<td>
														<div class="inputlabel_wrapper">
															<input type="text" name="purchasepriceincltax[]" autocomplete="off"  placeholder="Purchase Price (Incl. Tax)">
															<label for="purchasepriceincltax[]">Purchase Price (Incl. Tax)</label>
														</div>
													</td>
													<td>
														<div class="addmore-section">
															<a class="btn btn-primary addBatch btnaddmoreoption"><i class="fa fa-plus"></i></a>
															<a class="btn btn-danger removeBatch btnaddmoreoption"><i class="fa fa-minus"></i></a>
														</div>
													</td>
													
												</tr>
											</tbody>
										</table>
			                        </div>
			                        
			                    </div>
				            </div>
			        	</div>
		        	</div>
		   		</div>
					<div class="modal-footer">
						<input type="hidden" id="openmodel">
						<input type="hidden" name="hidden_product_id">
						<input type="hidden" name="hidden_stocktype">
						<input type="hidden" id="igst">
						<input type="hidden" id="cgst">
						<input type="hidden" id="sgst">
						<input type="hidden" id="cess">
						<input type="hidden" id="cess_amount">
						<button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-close"></i>Close</button>
						<button class="btn btn-primary" name="btnsubmit" type="submit"><i class="fa fa-save"></i>Save</button>
					</div>
				</div>
				</form>
			</div>
		</div>

		<script>
			var price_decimal_rounding = '<?php echo $price_decimal_value; ?>';
			var enable_product_mrp = <?php echo $enable_product_mrp; ?>;
			var enable_batch_no = <?php echo $enable_batch_no; ?>;
			var enable_batch_model = <?php echo $enable_batch_model; ?>;
			var enable_batch_size = <?php echo $enable_batch_size; ?>;
			var enable_batch_mfg = <?php echo $enable_batch_mfg; ?>;
			var enable_batch_expiry = <?php echo $enable_batch_expiry; ?>;
			var enable_serialno_strict = <?php echo $enable_serialno_strict; ?>;
			
			var require_product_mrp = <?php echo $require_product_mrp; ?>;
			var require_batch_no = <?php echo $require_batch_no; ?>;
			var require_batch_model = <?php echo $require_batch_model; ?>;
			var require_batch_size = <?php echo $require_batch_size; ?>;
			var require_batch_mfg = <?php echo $require_batch_mfg; ?>;
			var require_batch_expiry = <?php echo $require_batch_expiry; ?>;
			
			var label_product_mrp = '<?php echo $label_product_mrp; ?>';
			var label_batch_no = '<?php echo $label_batch_no; ?>';
			var label_batch_model = '<?php echo $label_batch_model; ?>';
			var label_batch_size = '<?php echo $label_batch_size; ?>';
			var label_batch_mfg = '<?php echo $label_batch_mfg; ?>';
			var label_batch_expiry = '<?php echo $label_batch_expiry; ?>';


			$("#productname").autocomplete({
				source: availableProducts,
				minLength: 0
			})

			var removeCommas = function (value) {
				return value.replace(/,/g, '');
			};

			function calculateDifferenceWithNewStock(input) {
				var row = input.closest('tr');
				var row_no = $(input).data('row-no');
				var quantity_decimal = <?php echo $quantity_decimal_value; ?>;
				if (row) {
					var quantityCell = $('#qunatity_' + row_no);
					var inventory = input.value;
					if(inventory != "" && (inventory.charAt(inventory.length - 1) != ".")  && inventory.includes(".")){
						inventory  = inventory.toString().substring(0, inventory.toString().indexOf('.') + 1 + quantity_decimal);
						$(input).val(inventory);			
					}
					
					if (quantityCell) {
						
						inventory = parseFloat(inventory);
						var currentStock = parseFloat(removeCommas(quantityCell.text()));
						if(inventory != null || inventory != "Nan" ){
							if (isNaN(inventory)) {
								var difference = null;
							} else {
								var difference = 0;
								if (inventory > currentStock) {
									difference = inventory - currentStock;
								} else {
									difference = inventory - currentStock;
								}
							}
						}

						if(difference != null){
							var roundedDifference = parseFloat(difference.toFixed(quantity_decimal));
						}else{
							var roundedDifference = null;
						}
						
						var changeinInput = $('#changein_' + row_no);
						var inventory = $('#inventory2_' + row_no);
						if (changeinInput) {
							
                            changeinInput.val(roundedDifference);
                            inventory.val(difference);
						}

					}
				}
			}
			function calculateDifferenceWithChangeInStock(input) {
				
				var row = input.closest('tr');
                var row_no = $(input).data('row-no');
				var quantity_decimal = <?php echo $quantity_decimal_value; ?>;
				if (row) {
					var quantityCell = $('#qunatity_' + row_no);
					if (quantityCell) {
						
						var inventory = input.value;
						if(inventory != "" && (inventory.charAt(inventory.length - 1) != ".")  && inventory.includes(".")){
							inventory  = inventory.toString().substring(0, inventory.toString().indexOf('.') + 1 + quantity_decimal);
							$(input).val(inventory);			
						}
						inventory = parseFloat(inventory);


						
						var currentStock = parseFloat(removeCommas(quantityCell.text()));
						if (isNaN(inventory) || inventory === 0) {
							var difference = null;
						} else {
							var difference = 0;
								difference = inventory + currentStock;
								
						}
						var changeinInput = $('#inventory_' + row_no);
						var inventory = $('#inventory2_' + row_no);
						if (changeinInput) {
                            changeinInput.val(difference);
							if(difference != null){	
									difference = difference - currentStock;
							}
                            inventory.val(difference);
						}

					}
				}
			}

			
			$(document).on('click','.managestock',function(){
				var productid = $(this).data('productid');
				var stocktype = $(this).data('stocktype');
				var rowNo = $(this).data('row-no');
				var igst = $(this).data('igst');
				var cgst = $(this).data('cgst');
				var sgst = $(this).data('sgst');
				var cess = $(this).data('cess');
				var cess_amount = $(this).data('cess_amount');
				var item_availble = $(this).data('item_availble');
				if(stocktype == 1){
					$('#ManageStockModal .batch-section').hide();
					$('#ManageStockModal .addProductBatch').show();	
					var data = {action:'getProductStockBatch',productid:productid,rowNo:rowNo};
					getProductBatch(data);	
					if($(window).width() > 991){
						$('#ManageStockModal').css('width', '55%');
					}
				}else{
					$('#ManageStockModal .addProductBatch').hide();	
					$('#ManageStockModal .batch-section').hide();	
					var data = {action:'getProductStockSerial',productid:productid,rowNo:rowNo,item_availble:item_availble};
					getProductSerial(data);		
					if($(window).width() > 991){
						$('#ManageStockModal').css('width', '700px');
					}
				}
				$('#ManageStockModal .productName').text($(this).closest('tr').find('td:first').text());
				$('#ManageStockModal [name="hidden_product_id"]').val(productid);
				$('#ManageStockModal [name="hidden_stocktype"]').val(stocktype);
				$('#ManageStockModal #igst').val(igst);
				$('#ManageStockModal #cgst').val(cgst);
				$('#ManageStockModal #sgst').val(sgst);
				$('#ManageStockModal #cess').val(cess);
				$('#ManageStockModal #cess_amount').val(cess_amount);
				
			});

			function getProductBatch(data){
				$.ajax({
					type: "POST",
					url: "ajaxcall.php",
					ContentType: 'application/json',
					dataType: 'json',
					data: data,
					success: function(data) {
						if (data.status == "OK") {
							$('#ManageStockModal .getListDataTbl').html(data.data);
							$('#ManageStockModal').modal('show');
							 $('#ManageStockModal #reportTableData').footable();
						}
					},
					error: function(data) {}
				});
			}
			
			$('#ManageStockModal').on('shown.bs.modal', function () {
				$("#ManageStockModal .bootstrap-tagsinput input").scannerDetection({
					timeBeforeScanTest: 500,
					avgTimeByChar: 100,
					onComplete: function(barcode) {
						var oldval=$("#ManageStockModal #tagInputs").val();
						if(oldval != ""){
						var newval=barcode;
						$('#ManageStockModal #tagInputs').tagsinput('removeAll');
						if(oldval != ""){
							oldval =oldval.split(",");
							oldval.push(newval);
						}
						$.each(oldval, function(index, tag) {
							$("#ManageStockModal #tagInputs").tagsinput('add', tag);
						});
						$("#ManageStockModal .bootstrap-tagsinput input").val("");	
					}
					else{
						$("#ManageStockModal #tagInputs").tagsinput('add', barcode);
						$("#ManageStockModal .bootstrap-tagsinput input").val("");
					}
				}
				});
			});
			$(document).on('keypress','#ManageStockModal .bootstrap-tagsinput input', function(e){
				if (e.keyCode == 13){
					e.keyCode = 188;
					e.preventDefault();
				};
			});

			function getProductSerial(data){
				$('#ManageStockModal #openmodel').val(0);
				$.ajax({
					type: "POST",
					url: "ajaxcall.php",
					ContentType: 'application/json',
					dataType: 'json',
					data: data,
					success: function(data) {
						if (data.status == "OK") {
							$('#ManageStockModal .getListDataTbl').html(data.data);
							$('#ManageStockModal #openmodel').val(1);
							$('#ManageStockModal #tagInputs').tagsinput({
								confirmKeys: [13, 44],
								itemText: function(item) {
									return item.replace(/\b\w/g, l => l.toUpperCase());
								},
								maxTags: 25,
							});
							$('#ManageStockModal .bootstrap-tagsinput input').attr('maxlength','30');
							$('#ManageStockModal').modal('show');
						}
					},
					error: function(data) {}
				});
			}

			$(document).on('change','#ManageStockModal [name="serialno"]',function(event){
				var openmodel = $('#ManageStockModal #openmodel').val();
				if(openmodel == 0){
					if($(this).val().split(',').length != parseInt($('#ManageStockModal .ttlquantity').text())){
						$('#ManageStockModal [name="inventory"]').val($(this).val().split(',').length).trigger("input");
						$('#ManageStockModal .serialnocnt').text(($(this).val().length > 0)?$(this).val().split(',').length:0);
					}else{
						$('#ManageStockModal [name="inventory"]').val('');
					}
				}else{
					$('#ManageStockModal #openmodel').val(0);
					$('#ManageStockModal [name="inventory"]').val('');
				}
			});

			function validateCurrentRowsForSubmit(){
				var isValid = true;
				var RowCounter = 1;
				var totalRowsData = $("#ManageStockModal .batch-section table tbody tr").length;
				$("#ManageStockModal .batch-section table tbody tr").each(function(){

					var quantity = $(this).find("[name='quantity[]']").val();
					var batch_name = $(this).find(".batch_name").val();
					var batchname = $(this).find("[name='batch_name[]']").val();
					var model_no = $(this).find("[name='model_no[]']").val();
					var size = $(this).find("[name='size[]']").val();
					var mfgdate = $(this).find("[name='manufacture_date[]']").val();
					var expirydate = $(this).find("[name='expiry_date[]']").val();
					var mrp = $(this).find("[name='mrp[]']").val();

					batchname = (batchname != '' && batchname != undefined)?batchname.trim():'';
					model_no = (model_no != '' && model_no != undefined)?model_no.trim():'';
					size = (size != '' && size != undefined)?size.trim():'';
					
					if(totalRowsData == RowCounter &&  batchname == "" &&  quantity == "")
					{
					
					}
					else
					{
						if(enable_batch_no == 1 && require_batch_no == 1 && batchname == "" )
						{
							Swal.fire({
								icon: 'warning',
								title:'Please Enter '+label_batch_no,
								didClose: (e) => {
									$(this).find("[name='batch_name[]']").focus();
								}
							});
							isValid = false;
							return false;
						}
						if(enable_batch_model == 1 && require_batch_model == 1 && model_no == "")
						{
							Swal.fire({
								icon: 'warning',
								title:'Please Enter '+label_batch_model,
								didClose: (e) => {
									$(this).find("[name='model_no[]']").focus();
								}
							});
							isValid = false;
							return false;
						}
						if(enable_batch_size == 1 && require_batch_size == 1 && size == "" )
						{
							Swal.fire({
								icon: 'warning',
								title:'Please Enter '+label_batch_size,
								didClose: (e) => {
									$(this).find("[name='size[]']").focus();
								}
							});
							isValid = false;
							return false;
						}
						if(mfgdate == "" && enable_batch_mfg == 1 && require_batch_mfg == 1)
						{
							Swal.fire({
								icon: 'warning',
								title:'Please Enter '+label_batch_mfg,
								didClose: (e) => {
									$(this).find("[name='manufacture_date[]']").focus();
								}
							});
							isValid = false;
							return false;
						}
						if(expirydate == "" && enable_batch_expiry == 1 && require_batch_expiry == 1)
						{
							Swal.fire({
								icon: 'warning',
								title:'Please Enter '+label_batch_expiry,
								didClose: (e) => {
									$(this).find("[name='expiry_date[]']").focus();
								}
							});
							isValid = false;
							return false;
						}
					
						if( quantity == "")
						{
							Swal.fire({
								icon: 'warning',
								title:'Please Enter Quantity',
								didClose: (e) => {
									$(this).find("[name='quantity[]']").focus();
								}
							});
							isValid = false;
							return false;
						}

						if( mrp == "" && enable_product_mrp == 1 && require_product_mrp == 1)
						{
							Swal.fire({
								icon: 'warning',
								title: 'Please Enter '+label_product_mrp,
								didClose: (e) => {
									$(this).find("[name='mrp[]']").focus();
								}
							});
							isValid = false;
							return false;
						}
					}
					
					RowCounter++;
				});
			
				return isValid;
			}


			$(document).on('submit','#ManageStockModal form',function(event){
				event.preventDefault();
				var is_valid = false;

				var stocktype = $('#ManageStockModal [name="hidden_stocktype"]').val();	
				if(stocktype == 1){
					if($('#ManageStockModal .batch-section').is(":visible")){
						is_valid = validateCurrentRowsForSubmit();
					}else{
						is_valid = true;
					}	
				}else{
						var inventory = parseInt($('#ManageStockModal [name="inventory"]').val());	
						var ttlserialno = ($('#ManageStockModal [name="serialno"]').val().trim() !== '')?$('#ManageStockModal [name="serialno"]').val().split(',').length:0;
						if(enable_serialno_strict == 1){
							if(inventory === ttlserialno){
								is_valid = true;
							}else{
								errorMessage('','New Stock and Total Serial No. not matched.');
							}
						}else{
							if(inventory >= ttlserialno || (inventory == 0 && ttlserialno == 0)){
								is_valid = true;
							}else{
								errorMessage('','Serial No. should be less than or equal to New Stock.');
							}
						}
					
				}
				if(is_valid){
					var data = $(this).serialize() + '&action=SubmitProductStockModel&serialno_strictmode='+enable_serialno_strict;
					$.ajax({
						type: "POST",
						url: "ajaxcall.php",
						ContentType: 'application/json',
						dataType: 'json',
						data: data,
						success: function(data) {
							if (data.status == "OK") {
								window.location.href = data.redirect;
							}else{
								errorMessage('','New Stock and Total Serial No. not matched.');
							}
						},
						error: function(data) {}
					});
				}

			});	


var addmorebatch = `<tr>
					<?php if($enable_batch_no == 1){ ?><td><div class="inputlabel_wrapper"><input type="text" name="batch_name[]" maxlength="20" placeholder="<?php echo $label_batch_no; ?>" autocomplete="off"><label for="batch_name[]"><?php echo $label_batch_no; ?></label></div></td><?php }
					 if($enable_batch_model == 1){ ?><td><div class="inputlabel_wrapper"><input type="text" name="model_no[]"  maxlength="20" autocomplete="off" placeholder="<?php echo $label_batch_model; ?>"><label for="model_no[]"><?php echo $label_batch_model; ?></label></div></td><?php } ?>
					<?php if($enable_batch_size == 1){ ?><td><div class="inputlabel_wrapper"><input type="text" name="size[]"  maxlength="20" autocomplete="off" placeholder="<?php echo $label_batch_size; ?>"><label for="size[]"><?php echo $label_batch_size; ?></label></div></td><?php } ?>
					<?php if($enable_batch_mfg == 1){ ?><td><div class="inputlabel_wrapper"><input type="text" class="monthyear_datepicker"  autocomplete="off" placeholder="<?php echo $label_batch_mfg; ?>" name="manufacture_date[]"><label for="manufacture_date[]"><?php echo $label_batch_mfg; ?></label></div></td><?php } ?>
					<?php if($enable_batch_expiry == 1){ ?><td><div class="inputlabel_wrapper"><input type="text" class="monthyear_datepicker"  autocomplete="off" placeholder="<?php echo $label_batch_expiry; ?>" name="expiry_date[]"><label for="expiry_date[]"><?php echo $label_batch_expiry; ?></label></div></td><?php } ?>
					<td>
						<div class="inputlabel_wrapper">
							<input type="text" name="quantity[]" placeholder="Qty">
							<input type="hidden" name="product_batch_id[]">
							<?php if($enable_batch_no != 1){?><input type="hidden" name="batch_name[]"> <?php } ?>
							<?php if($enable_batch_model != 1){?><input type="hidden" name="model_no[]"> <?php } ?>
							<?php if($enable_batch_size != 1){?><input type="hidden" name="size[]"> <?php } ?>
							<?php if($enable_batch_mfg != 1){?><input type="hidden" name="manufacture_date[]"> <?php } ?>
							<?php if($enable_batch_expiry != 1){?><input type="hidden" name="expiry_date[]"> <?php } ?>
							<?php if($enable_product_mrp != 1){?><input type="hidden" name="mrp[]"> <?php } ?>
							<label for="quantity[]">Qty.</label>
						</div>
					</td>
					<?php if($enable_product_mrp == 1){ ?><td><div class="inputlabel_wrapper"><input type="text" name="mrp[]" autocomplete="off" placeholder="<?php echo $label_product_mrp; ?>"><label for="mrp[]"><?php echo $label_product_mrp; ?></label></div></td><?php } ?>
					<td>
						<div class="inputlabel_wrapper">
							<input type="text" name="price[]" autocomplete="off" placeholder="Sale Price" class="sellprice">
							<label for="price[]">Sale Price</label>
						</div>
					</td>
					<td>
						<div class="inputlabel_wrapper">
							<input type="text" name="sellpriceincltax[]" autocomplete="off"  placeholder="Sale Price (Incl. Tax)" class="sellpriceincltax">
							<label for="sellpriceincltax[]">Sale Price (Incl. Tax)</label>
						</div>
					</td>
					<td>
						<div class="inputlabel_wrapper">
							<input type="text" name="purchase_price[]" autocomplete="off"  placeholder="Purchase Price">
							<label for="purchase_price[]">Purchase Price</label>
						</div>
					</td>
					<td>
						<div class="inputlabel_wrapper">
							<input type="text" name="purchasepriceincltax[]" autocomplete="off"  placeholder="Purchase Price (Incl. Tax)">
							<label for="purchasepriceincltax[]">Purchase Price (Incl. Tax)</label>
						</div>
					</td>
					<td>
						<div class="addmore-section">
							<a class="btn btn-primary addBatch btnaddmoreoption"><i class="fa fa-plus"></i></a>
							<a class="btn btn-danger removeBatch btnaddmoreoption"><i class="fa fa-minus"></i></a>
						</div>
					</td>
					
				</tr>`;

		$(document).on('click','#ManageStockModal .addProductBatch',function(event){
			$('#ManageStockModal .batch-section').show();
			addMoreButtonFn();
			monthYearDatepicker();
			dayWiseDatepicker();
		});


		function addMoreButtonFn(){
			var stockcls = '.batchOpt';
			var stockaddbtn = '.addBatch';
			var stockremovebtn = '.removeBatch';
			var row = $('#ManageStockModal .batch-section table tbody tr');
			var rowcounter = 1;
			var rowlength = row.length;
			row.each(function(){
				if(rowlength == rowcounter)
				{
					$(this).find(stockaddbtn).show();
					$(this).find(stockremovebtn).hide();
				}
				else
				{
					$(this).find(stockaddbtn).hide();
					$(this).find(stockremovebtn).show();
				}
				rowcounter += 1;
			});
		}	

		$(document).on('click','#ManageStockModal .addBatch',function(){
			$('#ManageStockModal .batch-section table tbody tr:last').after(addmorebatch);
			addMoreButtonFn();	
			monthYearDatepicker();
			dayWiseDatepicker();				
		});	 

		$(document).on('click','#ManageStockModal .removeBatch',function(){
			var removeclass = $(this).closest('tr');
			addMoreButtonFn();
			monthYearDatepicker();
			dayWiseDatepicker();
			$(this).closest('tr').remove();
			 
		});

		$(document).on('keyup change blur',"#ManageStockModal [name='sellpriceincltax[]']", function() {
			var cgst = $("#ManageStockModal #cgst").val();
			var sgst = $("#ManageStockModal #sgst").val();
			var igst = $("#ManageStockModal #igst").val();
			var cess = $("#ManageStockModal #cess").val();
			var cess_amount = $("#ManageStockModal #cess_amount").val();
		
			$('#ManageStockModal table tr').each(function(i,val){
				var totaltax = 0;
				var sell = $(this).find("[name='price[]']").val();
				var sell_inc = $(this).find("[name='sellpriceincltax[]']").val();
				if (sell_inc != "") {
					sell_inc = parseFloat(sell_inc);
					if (cess_amount != "") {
						cess_amount = parseFloat(cess_amount);
						sell_inc = sell_inc - cess_amount;
					}
					if (cgst != "" && sgst != "") {
						cgst = parseFloat(cgst);
						sgst = parseFloat(sgst);
						totaltax = totaltax + cgst + sgst;
					} else {
						if (igst != "") {
							igst = parseFloat(igst);
							totaltax = totaltax + igst;
						}
					}
					if (cess != "") {
						cess = parseFloat(cess);
						totaltax = totaltax + cess;
					}
					sell = (sell_inc * 100) / (100 + totaltax);
					sell = Math.round(sell * price_decimal_rounding) / price_decimal_rounding;
					$(this).find("[name='price[]']").val(sell);
				}
	   		});

			

		});


		$(document).on('keyup change blur',"#ManageStockModal [name='price[]']", function() {
			var cgst = $("#ManageStockModal #cgst").val();
			var sgst = $("#ManageStockModal #sgst").val();
			var igst = $("#ManageStockModal #igst").val();
			var cess = $("#ManageStockModal #cess").val();
			var cess_amount = $("#ManageStockModal #cess_amount").val();
			$('#ManageStockModal table tr').each(function(i,val){
				var totaltax = 0;
				var igstrate = 0;
				var cessrate = 0;
				var sell = $(this).find("[name='price[]']").val();
				var sell_inc = $(this).find("[name='sellpriceincltax[]']").val();
				if (sell != "") {
					sell = parseFloat(sell);
					sell_inc = parseFloat(sell_inc);

					if (cgst != "" && sgst != "") {
						cgst = parseFloat(cgst);
						sgst = parseFloat(sgst);
						igstrate = (sell * (cgst + sgst)) / 100;
						igstrate = Math.round(igstrate * 100) / 100;
					} else {
						if (igst != "") {
							igstrate = (sell * igst) / 100;
							igstrate = Math.round(igstrate * 100) / 100;
						}
					}


					if (cess != "") {
						cessrate = (sell * cess) / 100;
						cessrate = Math.round(cessrate * 100) / 100;
					}
					sell_inc = sell + igstrate + cessrate;
					if (cess_amount != "") {
						cess_amount = parseFloat(cess_amount);
						sell_inc = sell_inc + cess_amount;
					}
					sell_inc = Math.round(sell_inc * price_decimal_rounding) / price_decimal_rounding;
					$(this).find("[name='sellpriceincltax[]']").val(sell_inc);
				}
			});

		});

		$(document).on('keyup change blur',"#ManageStockModal [name='purchasepriceincltax[]']", function() {
			var totaltax = 0;
			var cgst = $("#ManageStockModal #cgst").val();
			var sgst = $("#ManageStockModal #sgst").val();
			var igst = $("#ManageStockModal #igst").val();
			var cess = $("#ManageStockModal #cess").val();
			var cess_amount = $("#ManageStockModal #cess_amount").val();

			$('#ManageStockModal table tr').each(function(i,val){
				var totaltax = 0;
				var totaltax = 0;
				var purc = $(this).find("[name='purchase_price[]']").val();
				var purc_inc = $(this).find("[name='purchasepriceincltax[]']").val();
				
				if (purc_inc != "") {
					purc_inc = parseFloat(purc_inc);
					if (cess_amount != "") {
						cess_amount = parseFloat(cess_amount);
						purc_inc = purc_inc - cess_amount;
					}
					if (cgst != "" && sgst != "") {
						cgst = parseFloat(cgst);
						sgst = parseFloat(sgst);
						totaltax = totaltax + cgst + sgst;
					} else {
						if (igst != "") {
							igst = parseFloat(igst);
							totaltax = totaltax + igst;
						}
					}
					if (cess != "") {
						cess = parseFloat(cess);
						totaltax = totaltax + cess;
					}
					purc = (purc_inc * 100) / (100 + totaltax);
					purc = Math.round(purc * price_decimal_rounding) / price_decimal_rounding;
					$(this).find("[name='purchase_price[]']").val(purc);
				}
			});
		});

		$(document).on('keyup change blur',"#ManageStockModal [name='purchase_price[]']", function() {
			var cgst = $("#ManageStockModal #cgst").val();
			var sgst = $("#ManageStockModal #sgst").val();
			var igst = $("#ManageStockModal #igst").val();
			var cess = $("#ManageStockModal #cess").val();
			var cess_amount = $("#ManageStockModal #cess_amount").val();
			$('#ManageStockModal table tr').each(function(i,val){
				var totaltax = 0;
				var igstrate = 0;
				var cessrate = 0;
				var purc = $(this).find("[name='purchase_price[]']").val();
				var purc_inc = $(this).find("[name='purchasepriceincltax[]']").val();
				if (purc != "") {
					purc = parseFloat(purc);
					if (cgst != "" && sgst != "") {
						cgst = parseFloat(cgst);
						sgst = parseFloat(sgst);
						igstrate = (purc * (cgst + sgst)) / 100;
						igstrate = Math.round(igstrate * 100) / 100;
					} else {
						if (igst != "") {
							igstrate = (purc * igst) / 100;
							igstrate = Math.round(igstrate * 100) / 100;
						}
					}
					if (cess != "") {
						cessrate = (purc * cess) / 100;
						cessrate = Math.round(cessrate * 100) / 100;
					}
					purc_inc = purc + igstrate + cessrate;
					if (cess_amount != "") {
						cess_amount = parseFloat(cess_amount);
						purc_inc = purc_inc + cess_amount;
					}
					purc_inc = Math.round(purc_inc * price_decimal_rounding) / price_decimal_rounding;
					$(this).find("[name='purchasepriceincltax[]']").val(purc_inc);
				}
			});
		});

		$(document).on('change',"#ManageStockModal .batch-section table tbody tr:last [name='price[]'] , #ManageStockModal .batch-section table tbody tr:last [name='quantity[]'], #ManageStockModal .batch-section table tbody tr:last [name='sellpriceincltax[]']",function(){
			if($(this).closest('tr').find("[name='quantity[]']").val() != '' && $(this).closest('tr').find("[name='price[]']").val() != ''){
				var sellprice = $(this).closest('tr').find("[name='price[]']").val();
				var sellpriceincltax = $(this).closest('tr').find("[name='sellpriceincltax[]']").val();
				$('#ManageStockModal .batch-section table tbody').append(addmorebatch);	
				addMoreButtonFn();	
				monthYearDatepicker();
				dayWiseDatepicker();
				$("#ManageStockModal .batch-section table tbody tr:last [name='price[]']").val(sellprice);	
				$("#ManageStockModal .batch-section table tbody tr:last [name='sellpriceincltax[]']").val(sellpriceincltax);	
			}
		});
		

		$('#ManageStockModal .batch-section .inputlabel_wrapper input').off('keyup change blur').on('keyup change blur click',function(){
			$('#ManageStockModal .batch-section .inputlabel_wrapper input[type="text"]').each(function(){
				var inputlabel_wrapper = $(this).closest('.inputlabel_wrapper');
				if($(this).val() || $(this).is(':focus')) {
					inputlabel_wrapper.addClass('active_inputlabel');
				}else{
					inputlabel_wrapper.removeClass('active_inputlabel');
				}
			});
		});
		
		</script>
	<?php
	}
	?>
<?php
} else {
	include('access-denied.php');
}
?>
<?php
include('footer.php');
?>