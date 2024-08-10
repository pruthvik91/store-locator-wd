<?php
require_once('../config.php');
require_once('uservalidation.php');
require_once('header.php');


$title = (isset($_GET["invoice_id"]) && $_GET["invoice_id"] != "") ? ' Update ' : ' Create ';
$title .= " Sale Invoice";
$IsAccessError = false;
$IsEditInvoice = false;
$IsInvoiceIdError = false;
$userid = "";
$invoice_id = "";
$printflag = false;
$invoice_prefix = "";
$invoice_postfix = "";
$export_prefix = "";
$export_postfix = "";
$other_tax_1_after_Taxable = "";
$total_to_qty = 0;
$payment_type = "";
$term_detail ="";
$payment_type_option = "";
$PlaceofSupply = isset($_POST['PlaceofSupply']) ? $_POST['PlaceofSupply'] : '';
$lut_no = isset($_POST['lut_no']) ? $_POST['lut_no'] : '';
$total_in_words = "";
$transport_id = "";
$transportdetils = "";
$transportname = "";
$vehicleno = "";
$transportno = "";
$transportdate = NUll;

$transportdate = "";

$transportID = "";
$invoice_id = "";
$user_id = $userid;
$org_state = "";
$org_gstin = "";
$cmp_state = "";
$cmp_gstin = "";
$cmp_contactperson = "";
$cmp_contactno = "";
$cmp_address1 = "";
$cmp_address2 = "";

$cmp_address1 = "";
$cmp_address2 = '';

$cmp_landmark = "";
$cmp_pincode = "";
$cmp_country = "";
$cmp_city = "";
$company_id = "";
$challanno = "";
$orderno = "";
$lrno = "";
$eway = "";
$default_due_date = "";
$enable_challan = 1;
$enable_challan_date = 1;
$challan_no_lable = "Challan No.";
$challan_date_lable = "Challan Date";
$ewaybill_no_lable = "E-Way No.";
$extra_text_field = 1;
$extra_date_field = 1;
$extra_text_field_3 = 0;
$extra_text_field_4 = 0;
$extra_date_field_3 = 0;
$enable_product_note = 0;
$enable_discount = 1;
$enable_round_off = 0;
$total_discount_label_op = 'Discount';
$other_tax_label_op = 'TCS';
$general_disc_label_op = 'General Discount';
$allow_oversell = 0;
$discount_in_op = 'percentage';
$discount_per_item_op = 0;
$quantity_decimal_value = 2;
$price_decimal_value = 2;
$gst_decimal_value = 2;
$gst_rate_decimal_value = 2;
$taxable_decimal_value = 2;
$default_customer = "";
$price_from_last_invoice = 0;
$show_export_fields_for_all = 0;
$onEditDisable = "";
$invoice_id = "";
$ms =  "";
$challanno =  "";
$orderno =  "";
$lrno =  "";
$eway =  "";
$eway_status = "";
$einvoice_status = "";

$org_gstin = "";
$org_state = "";
$cmp_state = "";
$cmp_gstin = "";
$cmp_contactperson = "";
$cmp_contactno = "";
$cmp_address1 = "";
$cmp_address2 = "";
$cmp_landmark = "";
$cmp_pincode = "";
$cmp_country = "";
$cmp_city = "";

$SameShippingAdd = "checked";
$shipping_distance_eway = "";
$shipping_address_id = "";
$shippingName = "";
$shippingAddress = "";
$shippingPhone = "";
$shippingLandmark = "";
$shippingCity = "";
$shippingPincode = "";
$shippingGSTIN = "";
$shippingState = "";
$shippingCountry = "";

$dispatchAdd = 0;
$additionalbusinessdetail_id = "";
$dispatchOrgname = "";
$dispatchName = "";
$dispatchAddress1 = "";
$dispatchAddress2 = "";
$dispatchPincode = "";
$dispatchCity = "";
$dispatchState = "";



$hidden_transport_id =  "";
$document_note = "";
$PlaceofSupply = "";
$bill_date =  "";
$challan_date =  "";
$orderno_date =  "";
$due_date =  "";
$reverse_Charge =  "";
$discount_in =  "";
$discount_per_item =  "";

$extra_field3 = '';
$extra_field4 = '';
$extra_date_field3 = '';

$bank = "empty";
$payment =  "";
$payment_note =  "";
$remark_sez = "";
$banktc_inv = "";
$row_total_qty =  "";
$row_total_mrp =  "";
$row_total_price =  "";
$row_total_disc =  "";

$row_total_cgst_rate =  "";
$row_total_sgst_rate =  "";
$row_total_igst_rate =  "";
$row_total_cess_rate =  "";
$row_total_total =  "";
$total_taxable =  "";
$gd_total_taxable =  "";
$total_tax =  "";
$reverse_tax_total =  "";
$grand_total =  "";
$remaining_payment =  "";
$round_off_value = "";
$transport_id = "";
$transportname = "";
$vehicleno = "";
$transportID = "";
$total_in_words = "";
$invoiceType = "";
$default_invoice_type = "";
$setting_default_invoice_type = "";
$shippingBillNo = "";
$shippingBillDate = "";
$shippingPortCode = "";
$precarriage_by = "";
$place_of_precarriage = "";
$vesselflight_no = "";
$port_of_loading = "";
$port_of_discharge = "";
$final_destiantion = "";
$country_of_origin = "";
$country_of_final = "";
$weight_kg = "";
$packeges = "";
$hidden_grand_total = "";


$total_discount_in_rupee = 1;
$total_discount_type_minus = 1;
$total_discount_label = "";
$total_discount_value = "";
$total_discount_amount = "";



$other_tax_in_rupee = 0;
$other_tax_type_minus = 0;
$other_tax_label = "";
$other_tax_value = "";
$other_tax_amount = "";

$general_disc_label = "";
$general_disc_in_rupee = 1;
$general_disc_value = "";
$general_disc_amount = "";
$companyname = "";
$extra_select_field_value = "";

$einvoice = "";
$einvoice_status = "";
$actions_json = "";

$cus_pay_grandtotal = "";
$cus_pay_total_in_words = "";
$subsidy_amount = "";
$convert_from = "";
$convert_from_id = "";
$currency = "";
$currency_rate = "";
$currency_grandtotal = "";
$currency_total_in_words = "";
$currency_cus_pay_grandtotal = "";
$currency_cus_pay_total_in_words = "";
$invoice_detail_id = "";

if (isset($discount_in_op)) {
    $discount_in = $discount_in_op;
}
$default_product_note = "";
if ((!empty($_POST)) && isset($_POST)) {
    $_POST = trim_all_values($_POST);
    $invoice_id = $_POST["invoice_id"];
    $cmp_contactno = $_POST['cmp_contactno'];
    if ($_POST['customerretail'] == 0) {
        $cmp_address1 = $_POST['address'];
        $cmp_address2 = "";
    } else {
        $cmp_address1 = $_POST['address'];
        $cmp_address2 = '';
    }
    $cmp_landmark = $_POST['cmp_landmark'];
    $cmp_pincode = $_POST['cmp_pincode'];
    $cmp_country = $_POST['cmp_country'];
    $cmp_city = $_POST['cmp_city'];
    $companyname = isset($_POST['companyname']) ? $_POST['companyname'] : '';
    $invoice_prefix = isset($_POST['invoice_prefix']) ? $_POST['invoice_prefix'] : '';
    $invoice_postfix = isset($_POST['invoice_postfix']) ? $_POST['invoice_postfix'] : '';
    $document_note = $_POST['document_note'];
    $bill_date = dateFromString($_POST['bill_date'], true);
    $discount_in = $_POST['discount_in'];
    $discount_per_item = $_POST['discount_per_item'];
    $payment_type = $_POST['payment_type'];
    $qty_total = $_POST['row_total_qty'];
    $price_total = $_POST['row_total_price'];
    $disc_total = $_POST['row_total_disc'];
    $igst_total = $_POST['row_total_igst_rate'];
    $total_total = $_POST['row_total_total'];
    $taxable_total = $_POST['total_taxable'];
    $tax_total = $_POST['total_tax'];
    $total_discount_in_rupee = isset($_POST['total_discount_in_rupee']) ? $_POST['total_discount_in_rupee'] : 1;
    $total_discount_type_minus = isset($_POST['total_discount_type_minus']) ? $_POST['total_discount_type_minus'] : 1;
    $total_discount_label = isset($_POST['total_discount_label']) ? $_POST['total_discount_label'] : 'Discount';
    $total_discount_value = isset($_POST['total_discount_value']) ? $_POST['total_discount_value'] : '';
    $total_discount_amount = isset($_POST['total_discount_amount']) ? $_POST['total_discount_amount'] : '';
    $grandtotal = $_POST['grand_total'];
    $term_detail = $_POST['term_detail'];
    $createdate = date("Y-m-d");
    $IsSave = false;

    $IsEditInvoice = false;
    if ((isset($_GET["invoice_id"])) && ($_GET["invoice_id"] != "")) {
        $IsEditInvoice = true;
        $invoice_detail_id = $_GET["invoice_id"];
    }

    if ($IsEditInvoice) {
        $stmt = $db->prepare("update " . DB_BASE . ".invoice_detail set 
            invoice_id=:invoice_id,
            companyname=:companyname,
            invoice_prefix=:invoice_prefix,
            invoice_postfix=:invoice_postfix,
            cmp_state=:cmp_state,
            cmp_contactno=:cmp_contactno,
            cmp_address1=:cmp_address1,
            cmp_address2=:cmp_address2,
            cmp_landmark=:cmp_landmark,
            cmp_pincode=:cmp_pincode,
            cmp_country=:cmp_country,
            cmp_city=:cmp_city,
            bill_date=:bill_date,
            qty_total=:qty_total,
            price_total=:price_total,
            disc_total=:disc_total,
            igst_total=:igst_total,
            total_total=:total_total,
            taxable_total=:taxable_total,
            tax_total=:tax_total,
            grandtotal=:grandtotal,
            payment_type=:payment_type,
            total_discount_in_rupee=:total_discount_in_rupee,
            total_discount_type_minus=:total_discount_type_minus,
            total_discount_value=:total_discount_value,
            total_discount_amount=:total_discount_amount,
            modifydate=DATE_ADD(NOW(), INTERVAL " . DB_TIMEDIFF . " MINUTE),
            total_in_words=:total_in_words,
            document_note=:document_note, 
            term_detail=:term_detail 
            where invoice_detail_id=:invoice_detail_id");

        $stmt->execute(array(
            ':invoice_id' => $invoice_id,
            ':companyname' => $companyname,
            ':invoice_prefix' => $invoice_prefix,
            ':invoice_postfix' => $invoice_postfix,
            ':cmp_state' => $cmp_state,
            ':cmp_contactno' => $cmp_contactno,
            ':cmp_address1' => $cmp_address1,
            ':cmp_address2' => $cmp_address2,
            ':cmp_landmark' => $cmp_landmark,
            ':cmp_pincode' => $cmp_pincode,
            ':cmp_country' => $cmp_country,
            ':cmp_city' => $cmp_city,
            ':bill_date' => $bill_date,
            ':qty_total' => $qty_total,
            ':price_total' => $price_total,
            ':disc_total' => $disc_total,
            ':igst_total' => $igst_total,
            ':total_total' => $total_total,
            ':taxable_total' => $taxable_total,
            ':tax_total' => $tax_total,
            ':grandtotal' => $grandtotal,
            ':payment_type' => $payment_type,
            ':total_discount_in_rupee' => $total_discount_in_rupee,
            ':total_discount_type_minus' => $total_discount_type_minus,
            ':total_discount_value' => $total_discount_value,
            ':total_discount_amount' => $total_discount_amount,
            ':total_in_words' => $total_in_words,
            ':document_note' => $document_note,
            ':term_detail' => $term_detail,
            ':invoice_detail_id' => $invoice_detail_id
        ));
        $selectitem = $db->prepare("select quantity,product_id FROM " . DB_BASE . ".invoice_item_detail WHERE invoice_detail_id=" . $invoice_detail_id);
        $selectitem->execute();
        $itmrows = $selectitem->fetchAll(PDO::FETCH_OBJ);
        $invitmrowcount = count($itmrows);
       
        $result = $db->prepare("delete  FROM " . DB_BASE . ".invoice_item_detail WHERE invoice_detail_id=" . $invoice_detail_id);
        $result->execute();
      
           
 
    } else {
        $enable_inventory_val = 0;
        if (isset($_POST['enable_inventory']) && $_POST['enable_inventory'] == 1) {
            $enable_inventory_val = 1;
        }

        $stmt = $db->prepare("INSERT INTO " . DB_BASE . ".invoice_detail(
    invoice_id,
    invoice_prefix,
    invoice_postfix,
    companyname,
    cmp_state,
    cmp_contactperson,
    cmp_contactno,
    cmp_address1,
    cmp_address2,
    cmp_landmark,
    cmp_pincode,
    cmp_country,
    cmp_city,
    bill_date,
    payment_type,
    qty_total,
    price_total,
    disc_total,
    igst_total,
    total_total,
    taxable_total,
    tax_total,
    grandtotal,
    total_discount_in_rupee,
    total_discount_type_minus,
    total_discount_value,
    total_discount_amount,
    createdate,
    total_in_words,
    discount_in,
    discount_per_item,
    document_note,
    term_detail
    
)
VALUES(
    :invoice_id,
    :invoice_prefix,
    :invoice_postfix,
    :companyname,
    :cmp_state,
    :cmp_contactperson,
    :cmp_contactno,
    :cmp_address1,
    :cmp_address2,
    :cmp_landmark,
    :cmp_pincode,
    :cmp_country,
    :cmp_city,
    :bill_date,
    :payment_type,
    :qty_total,
    :price_total,
    :disc_total,
    :igst_total,
    :total_total,
    :taxable_total,
    :tax_total,
    :grandtotal,
    :total_discount_in_rupee,
    :total_discount_type_minus,
    :total_discount_value,
    :total_discount_amount,
    DATE_ADD(
        NOW(), INTERVAL " . DB_TIMEDIFF . " MINUTE),
    :total_in_words,
    :discount_in,
    :discount_per_item,
    :document_note,
    :term_detail    
       )");
        $stmt->execute(array(
            ':invoice_id' => $invoice_id,
            ':invoice_prefix' => $invoice_prefix,
            ':invoice_postfix' => $invoice_postfix,
            ':companyname' => $companyname,
            ':cmp_state' => $cmp_state,
            ':cmp_contactperson' => $cmp_contactperson,
            ':cmp_contactno' => $cmp_contactno,
            ':cmp_address1' => $cmp_address1,
            ':cmp_address2' => $cmp_address2,
            ':cmp_landmark' => $cmp_landmark,
            ':cmp_pincode' => $cmp_pincode,
            ':cmp_country' => $cmp_country,
            ':cmp_city' => $cmp_city,
            ':bill_date' => $bill_date,
            ':payment_type' => $payment_type,
            ':qty_total' => $qty_total,
            ':price_total' => $price_total,
            ':disc_total' => $disc_total,
            ':igst_total' => $igst_total,
            ':total_total' => $total_total,
            ':taxable_total' => $taxable_total,
            ':tax_total' => $tax_total,
            ':grandtotal' => $grandtotal,
            ':total_discount_in_rupee' => $total_discount_in_rupee,
            ':total_discount_type_minus' => $total_discount_type_minus,
            ':total_discount_value' => $total_discount_value,
            ':total_discount_amount' => $total_discount_amount,
            ':total_in_words' => $total_in_words,
            ':discount_in' => $discount_in,
            ':discount_per_item' => $discount_per_item,
            ':document_note' => $document_note,
            ':term_detail' => $term_detail
        ));
        $affected_rows = $stmt->rowCount();
        $invoice_detail_id = $db->lastInsertId();
    }
 
    
    $i = count($_POST['quantity']);
    for ($j = 0; $j < $i; $j++) {
        $user_id = $userid;
        $product_id = $_POST['hidden-item-product-id'][$j];
        $producttitle = $_POST['hidden-item-product-name'][$j];
        $HSC_SAC = $_POST['hsccode'][$j];
        $is_service_product = (!empty($HSC_SAC) && strpos($HSC_SAC, "99") === 0) ? 1 : 0;
        $quantity = $_POST['quantity'][$j];
        $rate = $_POST['rate'][$j];
        $disc = $_POST['disc'][$j];
        $taxable_line_value = $_POST['taxable_line_value'][$j];

        $igst_rate = $_POST['igst_rate'][$j];
        $igst = isset($_POST['igst'][$j]) ? $_POST['igst'][$j] : '';
        $item_total = $_POST['line_total'][$j];
        if ($product_id != "" && $quantity != "" && $rate != "") {
            $stmt = $db->prepare("INSERT INTO " . DB_BASE . ".invoice_item_detail (
    invoice_detail_id,
    product_id,
    producttitle,
    HSC_SAC,
    quantity,
    rate,
    disc,
    igst_rate,
    igst,
    item_total,
    taxable_line_value
    ) VALUES (
    :invoice_detail_id,
    :product_id,
    :producttitle,
    :HSC_SAC,
    :quantity,
    :rate,
    :disc,
    :igst_rate,
    :igst,
    :item_total,
    :taxable_line_value
    )");
            $stmt->execute(array(
                ':invoice_detail_id' => $invoice_detail_id,
                ':product_id' => $product_id,
                ':producttitle' => $producttitle,
                ':HSC_SAC' => $HSC_SAC,
                ':quantity' => $quantity,
                ':rate' => $rate,
                ':disc' => $disc,
                ':igst_rate' => $igst_rate,
                ':igst' => $igst,
                ':item_total' => $item_total,
                ':taxable_line_value' => $taxable_line_value
            ));

            $invoiceitemdetailid = $db->lastInsertId(); 
            if($invitmrowcount > 0)
            {
            
            foreach($itmrows as $item)
            {
                $product_id = $item->product_id;
                $oldquantity = $item->quantity;
                $updateproduct = $db->prepare("update " . DB_BASE . ".store_product set item_available = (item_available+$oldquantity) WHERE product_id=" . $product_id);
                $updateproduct->execute();
                $result = $db->prepare("update " . DB_BASE . ".store_product set item_available = (item_available-$quantity) WHERE product_id=" . $product_id);
                $result->execute();
            }
        }
           
       
        }
 
    }
    header("Location:invoicedetail.php");
    exit; 
}
?>



<div class="row">
    <div class="span12">
        <?php
        if ($IsInvoiceIdError) {
            echo '<div class="alert alert-danger"><button data-dismiss="alert" class="close" type="button"><i class="fa fa-window-close" aria-hidden="true"></i></button><strong>Exists!</strong> Invoice No. Already Used, Please use Different Invoice no.</div>';
        }
        ?>
        <div class="widget-header open-header"> <i class="fa fa-edit"></i>
            <h3><?php echo $title; ?></h3>
        </div>
        <div class="widget-create-document">
            <?php
            if (isset($_GET["invoice_id"]) && $_GET["invoice_id"] != "") {
                $invoice_id = $_GET["invoice_id"];
                $result = $db->prepare("SELECT * FROM " . DB_BASE . ".invoice_detail WHERE invoice_detail_id = " . $invoice_id . " AND is_deleted != 1;");
                $result->execute();
                $rows = $result->fetchAll(PDO::FETCH_OBJ);
                $rowcount = count($rows);
                foreach ($rows as $row) {
                    $companyname = $row->companyname;
                    $cmp_contactno = $row->cmp_contactno;
                    $cmp_address1 = $row->cmp_address1;
                    $cmp_address2 = $row->cmp_address2;
                    $bill_date = $row->bill_date;
                    $term_detail = $row->term_detail;
                    $payment_type = $row->payment_type;
                    $document_note = $row->document_note;
                }
            }
            ?>
            <div class="">
                <form class="form-horizontal" method="post" action="" id="product_invoice_form">
                    <div class="row">
                        <div class="span5  span-md-5 span-xs-12 span-sm-12"><!-- CompanyDetails-->
                            <div class="widget">
                                <div class="widget-header">
                                    <h3><span class="desktop_only_label">Customer Information</span><span class="mobile_only_label">Customer Info</span></h3>
                                </div>
                                <hr>
                                <div class="widget-content customer-information-invoice equal-height-divs"><!-- /CompanyDetailsContent-->

                                    <div class="control-group">
                                        <label for="firstname" class="control-label ">Customer Name<span class="star_red">*</span></label>
                                        <div class="controls cmp" id="companybilling">
                                            <input type="text" name="companyname" required value="<?php echo $companyname; ?>">
                                        </div> <!--/ms-->

                                    </div>
                                    <div class="control-group">
                                        <label for="address" class="control-label">Address<span class="star_red">*</span></label>
                                        <div class="controls cmp">
                                            <textarea name="address" id="address"><?php echo trim($cmp_address1); ?></textarea>
                                        </div> <!--/address-->
                                    </div>
                                    <input type="hidden" name="cmp_address1" id="cmp_address1" data-value="<?php echo $cmp_address1; ?>" value="<?php echo $cmp_address1; ?>">
                                    <input type="hidden" name="cmp_address2" id="cmp_address2" data-value="<?php echo $cmp_address2; ?>" value="<?php echo $cmp_address2; ?>">
                                    <input type="hidden" name="cmp_landmark" id="cmp_landmark" data-value="<?php echo $cmp_landmark; ?>" value="<?php echo $cmp_landmark; ?>">
                                    <input type="hidden" name="cmp_pincode" id="cmp_pincode" data-value="<?php echo $cmp_pincode; ?>" value="<?php echo $cmp_pincode; ?>">
                                    <input type="hidden" name="cmp_country" id="cmp_country" data-value="<?php echo $cmp_country; ?>" value="<?php echo $cmp_country; ?>">
                                    <input type="hidden" name="cmp_city" id="cmp_city" data-value="<?php echo $cmp_city; ?>" value="<?php echo $cmp_city; ?>">
                                    <input type="hidden" name="customerretail" id="customerretail" value="0">
                                    <div class="control-group ">
                                        <label for="firstname" class="control-label ">Phone No<span class="star_red">*</span></label>
                                        <div class="controls cmp">
                                            <input type="text" class="control-labe2" name="cmp_contactno" id="cmp_contactno" data-value="<?php echo $cmp_contactno; ?>" value="<?php echo $cmp_contactno; ?>" placeholder="Phone No" required />
                                            <input type="hidden" name="hphone" id="hphone" />
                                        </div> <!-- /cst no -->

                                    </div> <!-- control group -->
                                </div>
                            </div><!-- /CompanyDetailsContent-->
                        </div><!-- /CompanyDetailsContent-->


                        <div class="span7  span-md-7 span-xs-12 span-sm-12 widget"><!-- BillingDetails -->
                            <div class="widget-header">
                                <h3>Invoice Detail</h3>
                            </div>
                            <hr>
                            <div class="widget-content invoice-detail-invoice  equal-height-divs"><!-- CompanyDetailsContent-->
                                <div class="minibox control-group">
                                    <label class="control-label billing-margin-date">Invoice No.<span class="star_red">*</span></label>
                                    <div class="controls cmp invoice_pre_post">
                                        <?php if (true) { ?>
                                            <input placeholder="Inv Pre." data-pre="<?php echo $invoice_prefix; ?>" data-export-pre="<?php echo $export_prefix; ?>" name="invoice_prefix" id="invoice_prefix" type="text" value="<?php echo $invoice_prefix; ?>" class="text-width input_prepost" style="width: 30%;float: left;">
                                            <input placeholder="Inv No." name="invoice_id" id="invoice_id" type="text" data-value="<?php echo ((isset($_GET["invoice_id"])) && ($_GET["invoice_id"] != "")) ? $invoice_id : ''; ?>" value="<?php echo $invoice_id; ?>" class="text-width decimal_numeric_only" style="width: 40%;float: left;" required>
                                            <input placeholder="Inv Post." data-post="<?php echo $invoice_postfix; ?>" data-export-post="<?php echo $export_postfix; ?>" name="invoice_postfix" id="invoice_postfix" type="text" value="<?php echo $invoice_postfix; ?>" class="text-width input_prepost" style="width: 30%;float: left;">
                                        <?php } ?>
                                        <label id="invoice_id-error" class="error" for="invoice_id" style="width:100%;display:none;"></label>
                                    </div>

                                </div><!-- bill no-->

                                <div class="minibox control-group">
                                    <label class="control-label billing-margin-date">Date<span class="star_red">*</span></label>
                                    <div class="controls cmp">
                                        <input placeholder="dd/mm/yy" name="bill_date" type="text" class="text-width" id="datepicker_bill" value="<?php

                                                                                                                                                    echo date('d-M-Y', strtotime(date("Y-m-d H:i:s") . " " . DB_TIMEDIFF . " minutes"));
                                                                                                                                                    ?>" required autocomplete="off">
                                    </div>
                                </div><!-- date-->
                                <div style="clear:both;"></div>
                                <hr>
                            </div><!-- CompanyDetailsContent-->
                        </div><!-- BillingDetails -->
                    </div><!-- /CompanyDetails-->
                    <?php
                    if ((isset($_GET["mode"])) && ($_GET["mode"] == "update")) {
                        echo '<div class="alert "><button data-dismiss="alert" class="close" type="button">×</button><strong>Updated!</strong> Detail Updated successfully.</div>';
                    }
                    if ((isset($_GET["mode"])) && ($_GET["mode"] == "insert")) {
                        echo '<div class="alert alert-success"><button data-dismiss="alert" class="close" type="button">×</button><strong>Inserted!</strong> Detail Inserted successfully.</div>';
                    }
                    $deleted = '';
                    if ($deleted) {
                        echo '<div class="alert alert-danger"><button data-dismiss="alert" class="close" type="button">×</button><strong>Deleted!</strong> Detail Deleted successfully.</div>';
                    }
                    ?><!--class="widget widget-table action-table"-->



                    <div class="row">
                        <div class="span12 bill_main_table" style="margin-top:10px;">
                            <div class="widget">
                                <div class="widget-header">
                                    <h3>Product Items</h3>
                                </div>
                                <div class="widget-content">
                                    <div class="div-invoice-product-items-table">
                                        <table class="invoice-product-items-table table  table-bordered" id="document-item-list-table">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        <center>SR.</center>
                                                    </th>
                                                    <th>
                                                        <center>Product / Other Charges</center>
                                                    </th>
                                                    <th>
                                                        <center>Product Code</center>
                                                    </th>
                                                    <th>
                                                        <center>Qty. / Stock</center>
                                                    </th>
                                                    <th>
                                                        <center>Price (Rs)</center>
                                                    </th>
                                                    <th>
                                                        <center>Gst</center>
                                                    </th>
                                                    <th class="discount_field">
                                                        <center>Discount</center>
                                                    </th>
                                                    <th>
                                                        <center>Total</center>
                                                    </th>
                                                    <th>
                                                        <center>Action</center>
                                                    </th>
                                                    <th>
                                                        <center></center>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                <?php
                                                $rowcount = 0;
                                                if ((isset($_GET["invoice_id"])) && ($_GET["invoice_id"] != "")) {
                                                    $invoice_detail_id = $_GET["invoice_id"];
                                                    global $db;
                                                    $result = $db->prepare("SELECT iid.* FROM " . DB_BASE . ".invoice_item_detail iid WHERE iid.invoice_detail_id=" . $invoice_detail_id . " AND iid.is_deleted != 1 GROUP BY iid.invoice_detail_item_id;");
                                                    $result->execute();
                                                    $rows = $result->fetchAll(PDO::FETCH_OBJ);
                                                    $rowcount = count($rows);
                                                }

                                                if ($rowcount > 0) {
                                                    foreach ($rows as $rs) {

                                                        $invoice_detail_item_id = isset($rs->invoice_detail_item_id) ? $rs->invoice_detail_item_id : '';
                                                        $product_id = $rs->product_id;
                                                        $producttitle = htmlspecialchars($rs->producttitle  ?? '');
                                                        $uom = htmlspecialchars($rs->uom ?? '');
                                                        $product_note = htmlspecialchars($rs->product_note ?? '');
                                                        $HSC_SAC = htmlspecialchars($rs->HSC_SAC ?? '');
                                                        $quantity = floatval($rs->quantity);
                                                        $rate = floatval($rs->rate);
                                                        $disc = floatval($rs->disc);
                                                        $taxable_line_value = floatval($rs->taxable_line_value);
                                                        $igst_rate = floatval($rs->igst_rate);
                                                        $igst = floatval($rs->igst);
                                                        $item_total = floatval($rs->item_total);


                                                ?>
                                                        <tr class="product-item-row">
                                                            <td>
                                                                <label class="product-item-row-number">1</label>
                                                            </td>
                                                            <td>
                                                                <label class="product-item-row-number product-item-row-number-tab">1</label>
                                                                <div class="inputlabel_wrapper">
                                                                    <input value="<?php echo $producttitle; ?>" data-oldval="<?php echo $producttitle; ?>" name="product_name" class="product-combobox" placeholder="Enter Product name" maxlength="500">
                                                                    <label for="product_name">Product Name</label>
                                                                </div>
                                                                <input value="<?php echo $product_id; ?>" class="hidden-item-product-id" name="hidden-item-product-id[]" type="hidden">
                                                                <input value="<?php echo $producttitle; ?>" class="hidden-item-product-name" name="hidden-item-product-name[]" type="hidden">
                                                                <input value="<?php echo $igst; ?>" class="hidden-item-product-igst" name="hidden-item-product-igst[]" type="hidden">
                                                                <input value="<?php echo $quantity; ?>" type="hidden" name="hidden_quantity[]">
                                                            </td>
                                                            <td>
                                                                <div class="inputlabel_wrapper">
                                                                    <input value="<?php echo $HSC_SAC; ?>" type="text" name="hsccode[]" class="hsccode" placeholder="Product Code" style="width:60px;" maxlength="20">
                                                                    <label for="hsccode[]">Product Code</label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="inputlabel_wrapper">
                                                                    <input value="<?php echo $quantity; ?>" type="text" name="quantity[]" class="quantity decimal_numeric_only" placeholder="Qty." style="width:50px;" maxlength="10" data-msg="Not enough stock!">
                                                                    <label>Qty</label>
                                                                </div>
                                                                <label class="product-quantity-available"></label>
                                                            </td>

                                                            <td>
                                                                <center>
                                                                    <div class="inputlabel_wrapper">
                                                                        <input value="<?php echo $rate; ?>" type="text" name="rate[]" class="rate decimal_numeric_only" placeholder="Price" style="width:100px;" maxlength="10">
                                                                        <label>Price</label>
                                                                    </div>
                                                                    <div class="currency-fields">
                                                                        <div class="currency-factor-symbol">Rs</div>
                                                                        <input value="" type="text" class="rate_usd decimal_numeric_only" placeholder="Price" maxlength="10">
                                                                    </div>
                                                                    <label class="customer_rate_label"></label>
                                                                    <span class='last-price-tip'><i class="fa fa-info"></i></span>

                                                                </center>
                                                            </td>
                                                            <td class="product_row_igst_col">
                                                                <div class="inputlabel_wrapper">
                                                                    <select name="igst[]" class="igst">
                                                                        <option value="">--</option>
                                                                        <?php echo gst_rates_options($igst, 'igst'); ?>
                                                                    </select>
                                                                    <label>IGST</label>
                                                                </div>
                                                                <input value="<?php echo $igst_rate; ?>" type="text" name="igst_rate[]" class="igst_rate text_rate decimal_numeric_only" readonly="readonly" style="width:50px;" value="0">
                                                            </td>
                                                            <td class="discount_field">
                                                                <div class="inputlabel_wrapper">
                                                                    <input value="<?php echo $disc; ?>" type="text" name="disc[]" class="disc decimal_numeric_only" style="width:50px;" maxlength="10">
                                                                    <label>Disc.</label>
                                                                </div>
                                                                <div class="currency-fields">
                                                                    <div class="currency-factor-symbol">Rs</div>
                                                                    <input value="" type="text" class="discount_usd decimal_numeric_only" placeholder="Discount" maxlength="10">
                                                                </div>
                                                                <input value="<?php echo $taxable_line_value; ?>" type="hidden" name="taxable_line_value[]" class="taxable_line_value">

                                                            </td>
                                                            <td>
                                                                <center>
                                                                    <div class="inputlabel_wrapper">
                                                                        <input value="<?php echo $item_total; ?>" type="text" name="line_total[]" class="line_total decimal_numeric_only" placeholder="Total" style="width:80px;">
                                                                        <label>Total</label>
                                                                    </div>
                                                                    <div class="currency-fields">
                                                                        <div class="currency-factor-symbol">Rs</div>
                                                                        <input value="" type="text" class="line_total_usd" placeholder="Total in Currency" maxlength="10">
                                                                    </div>
                                                                </center>
                                                            </td>
                                                            <td>
                                                                <div class="addmore-section">
                                                                    <button type="button" value="" class="btn btn-primary btnadd-product-line btnadd-row-item btnaddmoreoption"><i class="fa fa-plus"></i></button>
                                                                    <button type="button" value="" class="btn btn-danger btnremove-product-line btnremove-row-item btnaddmoreoption" style="display:none;"><i class="fa fa-minus"></i></button>
                                                                </div>
                                                            </td>

                                                        </tr>

                                                    <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <tr class="product-item-row">
                                                        <td>
                                                            <label class="product-item-row-number">1</label>
                                                        </td>
                                                        <td>
                                                            <label class="product-item-row-number product-item-row-number-tab">1</label>
                                                            <div class="inputlabel_wrapper">
                                                                <input class="product-combobox" placeholder="Enter Product name" maxlength="500" name="product_name">
                                                                <label for="product_name">Product Name</label>
                                                            </div>
                                                            <input class="hidden-item-product-id" name="hidden-item-product-id[]" type="hidden">
                                                            <input class="hidden-item-product-name" name="hidden-item-product-name[]" type="hidden">
                                                            <input class="hidden-item-product-igst" name="hidden-item-product-igst[]" type="hidden">
                                                            <input value="" type="hidden" name="hidden_quantity[]">


                                                        </td>

                                                        <td>
                                                            <div class="inputlabel_wrapper">
                                                                <input type="text" onkeyup="this.setAttribute('value', this.value);" name="hsccode[]" class="hsccode" value="" placeholder="HSN/SAC" style="width:60px;" maxlength="20">
                                                                <label for="hsccode[]">HSN/SAC</label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="inputlabel_wrapper">
                                                                <input type="text" value="" onkeyup="this.setAttribute('value', this.value);" name="quantity[]" class="quantity decimal_numeric_only" placeholder="Qty." style="width:50px;" maxlength="10" data-msg="Not enough stock!">
                                                                <label for="quantity[]">Qty</label>
                                                            </div>
                                                            <label class="product-quantity-available"></label>
                                                        </td>
                                                        <td>
                                                            <center>
                                                                <div class="inputlabel_wrapper">
                                                                    <input type="text" value="" onkeyup="this.setAttribute('value', this.value);" name="rate[]" class="rate decimal_numeric_only" placeholder="Price" style="width:100px;" maxlength="10">
                                                                    <label for="rate[]">Price</label>
                                                                </div>
                                                                <div class="currency-fields">
                                                                    <div class="currency-factor-symbol">Rs</div>
                                                                    <div class="inputlabel_wrapper">
                                                                        <input value="" type="text" class="rate_usd decimal_numeric_only" placeholder="Price" maxlength="10">
                                                                        <label>Price</label>
                                                                    </div>
                                                                </div>
                                                                <label class="customer_rate_label"></label>
                                                                <span class='last-price-tip'><i class="fa fa-info"></i></span>
                                                            </center>
                                                        </td>
                                                        <td class="product_row_igst_col">
                                                            <div class="inputlabel_wrapper">
                                                                <select name="igst[]" class="igst">
                                                                    <option value="">--</option>
                                                                    <?php echo gst_rates_options('', 'igst'); ?>
                                                                </select>
                                                                <label>IGST</label>
                                                            </div>
                                                            <input value="" type="text" name="igst_rate[]" class="igst_rate text_rate decimal_numeric_only" readonly="readonly" style="width:50px;" value="0">
                                                        </td>
                                                        <td class="discount_field">
                                                            <div class="inputlabel_wrapper">
                                                                <input type="text" onkeyup="this.setAttribute('value', this.value);" name="disc[]" class="disc decimal_numeric_only" style="width:50px;" value="0" maxlength="10">
                                                                <label for="disc[]">Disc</label>
                                                            </div>
                                                            <div class="currency-fields">
                                                                <div class="currency-factor-symbol">Rs</div>
                                                                <div class="inputlabel_wrapper">
                                                                    <input value="" type="text" class="discount_usd decimal_numeric_only" placeholder="Discount" maxlength="10">
                                                                    <label>Disc</label>
                                                                </div>
                                                            </div>
                                                            <input type="hidden" name="taxable_line_value[]" class="taxable_line_value">
                                                        </td>
                                                        <td>
                                                            <center>
                                                                <div class="inputlabel_wrapper">
                                                                    <input type="text" name="line_total[]" class="line_total decimal_numeric_only" placeholder="Total" style="width:80px;">
                                                                    <label>Total</label>
                                                                </div>
                                                                <div class="currency-fields">
                                                                    <div class="currency-factor-symbol">Rs</div>
                                                                    <div class="inputlabel_wrapper">
                                                                        <input value="" type="text" class="line_total_usd" placeholder="Total in Currency" maxlength="10">
                                                                        <label>Total in Currency</label>
                                                                    </div>
                                                                </div>
                                                            </center>
                                                        </td>
                                                        <td>
                                                            <div class="addmore-section">
                                                                <button type="button" value="" class="btn btn-primary btnadd-product-line btnadd-row-item btnaddmoreoption"><i class="fa fa-plus"></i></button>
                                                                <button type="button" value="" class="btn btn-danger btnremove-product-line btnremove-row-item btnaddmoreoption" style="display:none;"><i class="fa fa-minus"></i></button>
                                                            </div>
                                                        </td>

                                                    </tr>
                                                <?php } ?>
                                            </tbody>

                                            <tfoot>
                                                <tr class="all-row-totals">
                                                    <td colspan="2" style="text-align:right;">&nbsp; Total Inv. Val </td>
                                                    <td></td>
                                                    <td>
                                                        <input type="hidden" name="row_total_qty" id="row_total_qty" value="<?php echo $row_total_qty; ?>" />
                                                        <label type="text" name="row_total_qty_lable" id="row_total_qty_lable" class="row_total_qty_lable"></label>
                                                    </td>
                                                    <td>
                                                        <input type="hidden" name="row_total_price" id="row_total_price" value="<?php echo $row_total_price; ?>" />
                                                        <label type="text" name="row_total_price_lable" id="row_total_price_lable" class="row_total_price_lable"></label>
                                                    </td>
                                                    <td class="discount_field">
                                                        <input type="hidden" name="row_total_disc" id="row_total_disc" value="<?php echo $row_total_disc; ?>" />
                                                        <label type="text" name="row_total_disc_lable" id="row_total_disc_lable" class="row_total_disc_lable"></label>
                                                    </td>
                                                    <td class="product_row_igst_col">
                                                        <input type="hidden" name="row_total_igst_rate" id="row_total_igst_rate" value="<?php echo $row_total_igst_rate; ?>" />
                                                        <label type="text" name="row_total_igst_rate_lable" id="row_total_igst_rate_lable" class="row_total_igst_rate_lable"></label>
                                                    </td>
                                                    <td>
                                                        <input type="hidden" name="row_total_total" id="row_total_total" value="<?php echo $row_total_total; ?>" />
                                                        <label type="text" name="row_total_total_lable" id="row_total_total_lable" class="row_total_total_lable"></label>
                                                    </td>
                                                    <td></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>

                                    <div class="row">
                                        <div class="span6 span-md-7  span-sm-7 span-xs-13 row-span-left">
                                            <br>
                                            <h5 class="text-center">Terms &amp; Condition / Additional Note</h5>
                                            <div class="termConditionSection sortabletc ui-sortable">
                                                <div class="tc_section tc_reapiter_banktc" data-section="1">

                                                    <div class="control-group tc_counter" style="margin-right: 25px;">
                                                        <label class="control-label">Title</label>
                                                        <div class="controls">
                                                            <input type="text" value="Terms and Conditions" name="banktc[term_title][]" class="span4" maxlength="50" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="control-group tc_counter-1" style="margin-right: 25px;">
                                                        <label for="Address" class="control-label">Detail</label>
                                                        <div class="controls">
                                                            <textarea name="term_detail" class="span4" placeholder="Terms & Conditions"><?= (isset($term_detail) && !empty($term_detail))?$term_detail:"કપડાં માં વોશિંગ ગેરંટી આવતી નથી.
શેમ્પૂ વોશ કરવું ફરજિયાત છે.
ખરીદી સમયે બરાબર ચકાસણી કરી લઇ જવું."; ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span6 span-md-6  span-sm-6 span-xs-12 row-span-left" style="
    padding-right: 20px;
">
                                            <table class="table document-total-section">
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            Total Taxable
                                                        </td>
                                                        <td>

                                                            <div class="total-controls">
                                                                <input type="hidden" name="total_taxable" id="total_taxable" value="<?php echo $total_taxable; ?>" />
                                                                <center><label type="text" name="total_taxable_lable" id="total_taxable_lable" class="total_taxable_lable"></label></center>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label class="reverse_charge_label_text">Total Tax</label>
                                                        </td>
                                                        <td>
                                                            <div class="total-controls">
                                                                <input type="hidden" name="total_tax" id="total_tax" value="<?php echo $total_tax; ?>" />
                                                                <center><label type="text" name="total_tax_lable" id="total_tax_lable" class="total_tax_lable"></label></center>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr class="grand-total-section">
                                                        <td>
                                                            Grand Total
                                                        </td>
                                                        <td>
                                                            <div class="total-controls">
                                                                <input type="hidden" name="grand_total" id="grand_total" value="<?php echo $grand_total; ?>" />
                                                                <center><label type="text" name="grand_total_lable" id="grand_total_lable" class="grand_total_lable"></label></center>
                                                                <div class="currency-fields">
                                                                    <center><label type="text" name="currency_grandtotal_lable" id="currency_grandtotal_lable" class="currency_grandtotal_lable"></label></center>
                                                                </div>
                                                                <input type="hidden" name="currency_grandtotal" id="currency_grandtotal" value="<?php echo $currency_grandtotal; ?>" />
                                                            </div>
                                                        </td>
                                                    </tr>
                                                   
                                                    <tr>
                                                        <td colspan="2" class="tdttlinwords">
                                                            <label class="inner-widget-content-label">Total in words</label>
                                                            <label for="firstname" id="grandtotalwords"><?php echo $total_in_words; ?></label>
                                                            <input type="hidden" name="hidden_round_off_value" id="hidden_round_off_value" value="<?php echo $round_off_value; ?>" />
                                                            <input type="hidden" name="hidden_grandtotalwords" id="hidden_grandtotalwords" value="<?php echo $total_in_words; ?>" />
                                                            <input type="hidden" name="hidden_cust_pay_grandtotalwords" id="hidden_cust_pay_grandtotalwords" value="<?php echo $cus_pay_total_in_words; ?>" />
                                                            <input type="hidden" name="hidden_currency_total_in_words" id="hidden_currency_total_in_words" value="<?php echo $currency_total_in_words; ?>" />
                                                            <input type="hidden" name="hidden_currency_cus_pay_total_in_words" id="hidden_currency_cus_pay_total_in_words" value="<?php echo $currency_cus_pay_total_in_words; ?>" />
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <hr style="margin-top:0;">
                                            <div class="control-group">
                                                <input type="hidden" name="discount_in" id="discount_in" value="<?php echo $discount_in; ?>" />
                                                <input type="hidden" name="discount_per_item" id="discount_per_item" value="<?php echo $discount_per_item; ?>" />
                                                <label for="payment" class="control-label">Payment Type<span class="star_red">*</span></label>
                                                <div class="controls">


                                                        <div class="payment-type-section">
                                                            <input type="radio" id="cash" value="cash" class="payment" name="payment_type" required <?php echo ($payment_type == "cash")?'checked':''; ?>>
                                                            <label for="cash" class="btn btn-payment-type btn-payment-type-cash">CASH</label>
                                                            <input type="radio" id="Online" value="online" class="payment" name="payment_type" required <?php echo ($payment_type == "online")?'checked':''; ?>>
                                                            <label for="Online" class="btn btn-payment-type btn-payment-type-cash">ONLINE</label>

                                                        </div>
                                                        
                                                    <label id="payment-error" class="error" for="payment" style="width:100%;display:none;"></label>
                                                </div> <!-- /controls -->
                                            </div> <!-- /control-group -->
                                            <hr>
                                            <div class="control-group">
                                                <label for="document_note" class="control-label">Document Note / Remarks<span class="note" style="display:block;">Not Visible on Print</span></label>
                                                <div class="controls">
                                                    <textarea id="document_note" name="document_note" class="Doc_Note" maxlength="450"><?php echo $document_note; ?></textarea>
                                                </div> <!-- /controls -->
                                            </div>
                                        </div>
                                    </div>

                                    <hr>
                                    <div class="row footer-section">
                                        <div class="span6 span-xs-12  span-md-6 span-sm-6">
                                        </div>
                                        <div class="span6 span-xs-12  span-md-6 span-sm-6" style="
    padding-right: 20px;
">
                                            <div class="action-btn-container">
                                                <input type="hidden" name="total_to_qty" id="total_to_qty" value="<?php echo $total_to_qty; ?>" />
                                                <input type="hidden" name="hidden_grand_total" id="hidden_grand_total" value="<?php echo $hidden_grand_total; ?>" />
                                                <input type="hidden" name="convert_from" id="convert_from" value="<?php echo $convert_from; ?>" />
                                                <input type="hidden" name="convert_from_id" id="convert_from_id" value="<?php echo $convert_from_id; ?>" />
                                                <input type="hidden" name="submittype" id="submittype" value="" />
                                                <input type="hidden" name="action" id="action" value="" />
                                                <input type="hidden" name="draft_id" id="draft_id" value="<?php if (isset($_GET['draft_id']) && !empty($_GET['draft_id'])) {
                                                                                                                echo $_GET['draft_id'];
                                                                                                            } ?>" />
                                                <input type="hidden" name="hidden_submit_form_data" id="hidden_submit_form_data" value="yes" />
                                                <button type="submit" id="save" name="save" class="btn btn-primary pull-right"><i class="fa fa-save"></i>Save<span class="shortcut_key_ctrl">s</span></button>
                                                <a href="invoicedetail.php" class="btn  pull-right pull-mobile-left bill_back_btn"><i class="fa fa-chevron-left"></i>Back</a>
                                                <a class="btn  pull-left pull-mobile-left discard_btn" style="<?php if (isset($_GET['draft_id']) && !empty($_GET['draft_id'])) {
                                                                                                                    echo "display:block;";
                                                                                                                } ?>"><i class="btn-icon-only fa fa-trash-o"></i>Discard</a>
                                            </div><!--/save&&saveandpdf -->
                                        </div>
                                    </div>
                                </div><!---widget content terminated -->
                            </div>
                        </div><!--span12  terminated -->
                    </div>
            </div>
            </form><!--form terminated-->
        </div><!--row terminated -->
    </div><!---widget content terminated -->
</div><!--span12 terminated -->
</div><!--row terminated -->

<?php

$PageType = 'Customer';
?>

<?php
$sqlpro = "SELECT * FROM " . DB_BASE . ".store_product WHERE 1";

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


                    echo '{
				
				value: "' . $product->product_id . '",
				label: "' . addslashes($product->product_name ?? '') . '",
				sellprice: "' . $product->sell_price . '",
				purchaseprice: "' . $product->pur_price . '",
				items_available: "' . str_replace(',', '', $product->item_available) . '",
				gst: "' . $product->gst . '",
				name: "' . addslashes($product->product_name ?? '') . '",
				hsn: "' . addslashes($product->hsn_sac ?? '') . '",
				barcode_no: "' . addslashes($product->barcode_no ?? '') . '",
			}';
                    $procounter++;
                }
                ?>
            ];
        </script>


    <?php
    }
}
if (isset($_GET['draft_id']) && $_GET['draft_id'] != '') {
    ?>
    <script>
        $(document).ready(function() {
            UpdateQtyMax();
        });
    </script>

<?php
}
?>
<script src="../assets/js/general.js"></script>
<script src="../assets/js/bill.js"></script>
<script src="../assets/js/bootstrap-select.min.js"></script>
<link rel="stylesheet" href="../assets/css/bootstrap-select.min.css" />

<?php require_once('footer.php'); ?>