<?php

require 'excel/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

$title = "Upload Product Data";
require_once('../config.php');
include('./header.php');
global $db;
/* Get Current User Id By Session*/

//back url parameter
$URLBack = $_SERVER['QUERY_STRING'];

ini_set("display_errors", 1);

$file_error = false;    
if((!empty($_POST)) && isset($_POST["btnsubmit"])){
    $temp_table = 'products_temp';
    
    $file_name = "";
    $allowed_image_extension = array("xls", "xlsx");
    if(isset($_FILES["file"]["name"]) && $_FILES["file"]["name"] != ""){
        $file_extension = pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
        if (!in_array($file_extension, $allowed_image_extension)) {
            echo  "Upload valid Excel. Only xls and xlsx are allowed.";
            exit;
        }    
        else if (($_FILES["file"]["size"] > 10485760)) {
            echo "Document size exceeds 10MB";
            exit;
        } 
        if(!$file_error){
            
            $extArray = explode(".", $_FILES["file"]["name"]);
            $extItem = end($extArray);
            $ext = strtolower($extItem); 
            $all_ext = array("xls", "xlsx");
    
            if(in_array($ext, $all_ext)){
                $file_name = "sheet_product_.$ext";
                $file_tmp = $_FILES["file"]["tmp_name"];
                move_uploaded_file($file_tmp, 'upload/'.$file_name);
            }
            else {
                echo "Please Select Valid File to Import Data.";
                exit;
            }
        }
    }
    else {
        echo "Please Select Valid File to Import Data.";
        exit;
    }
    
    $result_sheet_path = "./upload/".$file_name;
    
    $spreadsheet = IOFactory::load($result_sheet_path);
    $data = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
    $header_label = 0;
    $checkValid = false;
    $checkheaderarr = ['product name', 'sell price', 'barcode no', 'purchase price', 'hsn/sac code', 'stock available', 'gst %'];
    $headerarrLower = array_map('strtolower', array_map('trim', $checkheaderarr));
    $excelHeadersLower = array_map('strtolower', array_map('trim', $data[1] ));
    $setData = [];
    $headerarr = array_map(function($header) {
        return preg_replace('/[^a-zA-Z0-9\s]/', '', $header);
    }, $checkheaderarr);
    $headerarr = array_map('trim', $headerarr);
    $headerarr = str_replace(" ", "_", $headerarr);
    foreach($excelHeadersLower as $key => $val){
        $index = array_search($val, $headerarrLower);
        $setData['xls'.$headerarr[$index]] = $key;
    }
    extract($setData);
    $missingHeaders1 = array_diff($headerarrLower, $excelHeadersLower);
    $missingHeaders2 = array_diff($excelHeadersLower, $headerarrLower);
    $header_label = 0;
    $checkValid = false;
    if (empty($missingHeaders1) && empty($missingHeaders2)) {
        $checkValid = true;
    } else {
        $checkValid = false;
        $missingHeaders = array_merge($missingHeaders1, $missingHeaders2);
        echo "Missing headers: " . implode(", ", array_map('ucfirst', $missingHeaders));
        exit; 
    }
    $price_decimal_value = 100;
    $IsInsertError = 0;
    $ERROR = "";
    $summaryInvalid = array();
    $summaryExist = array();
    $summarySuccess = array();
    if($checkValid){
    
        $rowcount = count($data);//no. of rows
        $previousName = "";
        $values = "";
        $importArr = [];
        $product_group = [];    
        /* cells */
        for($i = 2; $i <= $rowcount; $i++){    
            $rowNoError = true;
            $product_name = trim($data[$i][$xlsproduct_name] ?? '');
            $sellprice = trim($data[$i][$xlssell_price] ?? '');
            $purchaseprice = trim($data[$i][$xlspurchase_price] ?? '');
            $hsn = trim($data[$i][$xlshsnsac_code] ?? '');
            if($hsn == "")
            {
                $hsn = substr($product_name, 0,4)."-".$i;
            }
            $hsn = str_replace(",", "", $hsn);
            
            $items_available = trim($data[$i][$xlsstock_available] ?? '');
            $igst = trim($data[$i][$xlsgst] ?? '');
            $barcode_no = $data[$i][$xlsbarcode_no];
            $barcode_no = !empty($barcode_no) ? str_replace(",", "", $barcode_no) : '';
            $product_name = str_replace("\n", "" , $product_name);
            $igst = preg_replace("/[^0-9.]/", "", $igst);
            $sellprice = preg_replace("/[^0-9.]/", "", $sellprice);
            $purchaseprice = preg_replace("/[^0-9.]/", "", $purchaseprice);
            $items_available = preg_replace("/[^0-9.]/", "", $items_available);

            // Check for existing product by barcode_no
            $stmt = $db->prepare("SELECT COUNT(*) FROM ".DB_BASE.".`store_product` WHERE hsn_sac = :hsn");
            $stmt->execute([':hsn' => $hsn]);
            $productExists = $stmt->fetchColumn();
            
            if($productExists){
                $IsInsertError++;
                $ERROR .= "Error in Row - '$i'. Product with Barcode No '$hsn' already exists.<br>";
                $rowNoError = false;
                $summaryExist[$i] = "Product with this Code No '$hsn' already exists.";
            }

            if($product_name == ''){
                $IsInsertError++;
                $ERROR .= "Error in Row - '$i'. Not Valid Product Name.<br>";
                $rowNoError = false;
            }
            if(isset($sellprice) && $sellprice != ""){
                if(!is_numeric($sellprice)){
                    $IsInsertError++;
                    $ERROR .= "Error in Row - '$i'. Not Valid Sellprice.<br>";
                    $rowNoError = false;
                    $summaryInvalid[$i] = 'Not Valid Sellprice.';
                } else {
                    $sellprice = round($sellprice, $price_decimal_value);
                }
            } else {
                $sellprice = 0;
            }
            if(isset($purchaseprice) && $purchaseprice != ""){
                if(!is_numeric($purchaseprice)){
                    $IsInsertError++;
                    $ERROR .= "Error in Row - '$i'. Not Valid Purchaseprice.<br>";
                    $summaryInvalid[$i] = 'Not Valid Purchaseprice.';
                    $rowNoError = false;
                } else {
                    $purchaseprice = round($purchaseprice, $price_decimal_value);
                }
            } else {
                $purchaseprice = 0;
            }
            if(isset($hsn) && $hsn != ""){
                if(!is_numeric($hsn)){
                    $IsInsertError++;
                    $ERROR .= "Error in Row - '$i'. Not Valid hsn.<br>";
                    $summaryInvalid[$i] = 'Not Valid hsn.';
                    $rowNoError = false;
                }
            } else {
                $hsn = "";
            }
            if(isset($items_available) && $items_available != ""){
                if(!is_numeric($items_available)){
                    $IsInsertError++;
                    $ERROR .= "Error in Row - '$i'. Not Valid Items Available.<br>";
                    $summaryInvalid[$i] = 'Not Valid Items Available.';
                    $rowNoError = false;
                }
            } else {
                $items_available = 0;
            }
            if(isset($igst) && $igst != ""){
                if(!is_numeric($igst)){
                    $IsInsertError++;
                    $ERROR .= "Error in Row - '$i'. Not Valid igst.<br>";
                    $summaryInvalid[$i] = 'Not Valid igst.';
                    $rowNoError = false;
                }
            } else {
                $igst = 0;
            }

            if ($rowNoError) {
                $importArr[':product_name'.$i] = $product_name; 
                $importArr[':sellprice'.$i] = $sellprice; 
                $importArr[':purchaseprice'.$i] = $purchaseprice; 
                $importArr[':hsn'.$i] = $hsn; 
                $importArr[':items_available'.$i] = $items_available; 
                $importArr[':igst'.$i] = $igst; 
                $importArr[':barcode_no'.$i] = $barcode_no; 
                $values .= "(:product_name$i, :sellprice$i, :purchaseprice$i, :hsn$i, :items_available$i, :igst$i, :barcode_no$i, DATE_ADD(NOW(), INTERVAL ".DB_TIMEDIFF." MINUTE)),";
            }
        }
        if($values != ""){
            if(substr($values, -1) == ','){
                $values = rtrim($values, ",");
            }
            $cmpnsql = "INSERT INTO ".DB_BASE.".`store_product` 
            (product_name, sell_price, pur_price, hsn_sac, item_available, gst, barcode_no, createdate) VALUES ".$values;
            
            $resultcompany = $db->prepare("$cmpnsql");
            $resultcompany->execute($importArr); 
            
            $values = "";
            $importArr = [];
        }
    }
}
?>

<div class="row">
    <div class="span12">
    
        <div class="widget">
            <div class="widget-header "> 
                <h3>Import Product</h3>        
                <a class="btn btn-primary pull-right" href="product-sample-new.xlsx?wtn-download-file=true"><i class="fa fa-edit"></i>Download Sample Data</a>    
            </div>
            <hr>
            <div class="widget-content">
                <form class="form-horizontal" method="post" id="edit-profile" enctype="multipart/form-data">
                    <fieldset>
                        
                        <div class="control-group">                                            
                            <label for="email" class="control-label">Upload Excel(.xls,.xlsx) File</label>
                            <div class="controls">
                                <input type="file"  id="txtkeyvalue" name="file" class="span4">
                            </div> <!-- /controls -->                
                        </div> <!-- /control-group -->
                        
                        <div class="form-actions">
                            <button class="btn btn-primary" name="btnsubmit" type="submit"><i class="fa fa-save"></i>Save</button> 
                            <a href="list-products.php?<?php echo $URLBack; ?>" class="btn"><i class="fa fa-chevron-left"></i>Back</a>
                        </div> <!-- /form-actions -->
                    </fieldset>
                </form>
            </div>
         </div>
    </div>
</div>

<?php
    include('footer.php');
?>
