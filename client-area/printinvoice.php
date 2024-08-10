<!DOCTYPE html>
<html lang="en">
<head>
    <title>Print Invoice</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">

    <!-- External CSS libraries -->
    <link type="text/css" rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link type="text/css" rel="stylesheet" href="../assets/css/bootstrap.css">
    <link type="text/css" rel="stylesheet" href="../assets/css/font-awesome.css">

    <!-- Favicon icon -->
    <link rel="shortcut icon" href="./favicon_io/android-chrome-192x192.png" type="image/x-icon">

    <!-- Google fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900">

    <!-- Custom Stylesheet -->
    <link type="text/css" rel="stylesheet" href="../assets/css/invoice.css">
    
    <!-- html2pdf.js library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>
</head>
<body>
<?php
 require_once('../config.php');
 $settings= "";
if((isset($_GET["invoiceid"]))&&($_GET["invoiceid"]!="")){
$getsettings = "SELECT * FROM ".DB_BASE.".store_settings order by store_id desc limit 1";
$qrysettings =  CP_Select($getsettings,[]);
$settings_count = count($qrysettings);
$st = json_decode($qrysettings[0]->settings,true);
$number1 = isset($st['number1']) && !empty($st['number1']) ? $st['number1'] :'Add number In store Setting';
$number1 = preg_replace('/(\d{5})(\d{5})/', '$1 $2', $number1);
$number2 = isset($st['number2']) && !empty($st['number2']) ? $st['number2'] :'Add number In store Setting';
$number2 = preg_replace('/(\d{5})(\d{5})/', '$1 $2', $number2);
$instagram = isset($st['instagram']) && !empty($st['instagram']) ? $st['instagram'] :'Add instagram store Setting';
$storeaddress = isset($st['storeaddress']) && !empty($st['storeaddress']) ? $st['storeaddress']:'Add store address store Setting';
$display_hsn = isset($st['display_hsn']) && !empty($st['display_hsn']) ? $st['display_hsn']:'';
$invoice_detail_id = $_GET["invoiceid"];
$sql = "SELECT * FROM ".DB_BASE.".invoice_detail where 1=1 AND invoice_detail_id = $invoice_detail_id";
$invoice =  CP_Select($sql,[]);
$invoice_count = count($invoice);
if($invoice_count > 0)
{
    foreach($invoice as $inv){
        $customername = $inv->companyname;
        $cmp_contactno = $inv->cmp_contactno;
        $address = $inv->cmp_address1;
        $term_detail = $inv->term_detail; 
        $discount_total = $inv->disc_total; 
        $grand_total = $inv->grandtotal; 
        $payment_type = $inv->payment_type; 
        $total_tax = $inv->tax_total; 
        $bill_date = date('d-M-y',strtotime($inv->bill_date)); 
    }
}
}else{
    header('Location: invoicedetail.php?error=notfound');
}
?>
<!-- Invoice 1 start -->
<div class="invoice-1 invoice-content" id="invoice_content" style="padding-top:10px;">
    <div class="container">
        <div class="row">
            <div class="col-lg-12" style="width:80%;">
                <div class="invoice-inner clearfix">
                    <div class="invoice-info clearfix" id="invoice_wrapper">
                        <div class="invoice-headar">
                            <div class="row g-0">
                                <div class="col-sm-6">
                                    <div class="invoice-logo">
                                        <!-- logo started -->
                                        <div class="logo">
                                            <img src="../assets/images/w&d_logo.png" alt="logo">
                                        </div>
                                        <!-- logo ended -->
                                    </div>
                                </div>
                                <div class="col-sm-6 invoice-id">
                                    <div class="info">
                                        <h1 class="color-white inv-header-1">Invoice</h1>
                                        <p class="color-white mb-1">Invoice Number <span><?= $invoice_detail_id ?></span></p>
                                        <p class="color-white mb-0">Invoice Date <span><?= $bill_date ?></span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div style="height:50px;"></div>
                        <div class="invoice-top">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="invoice-number-1 mb-30">
                                        <h4 class="inv-title-1">Invoice To</h4>
                                        <h2 class="name mb-10"><?php echo $customername; ?></h2>
                                        <p class="invo-addr-1">
                                        <?= $cmp_contactno ; ?> <br/>
                                        <?= $address; ?> <br/>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="invoice-number mb-30">
                                        <div class="invoice-number-inner">
                                            <h4 class="inv-title-1">Invoice From</h4>
                                            <h2 class="name mb-10">Wed & Nik</h2>
                                            <p class="invo-addr-1">
                                                <?=  $number1 ?> <br/>
                                                <?=  $number2 ?> <br/>
                                                <?=  $storeaddress ?> <br/>
                                            
                                            
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="invoice-center">
                            <div class="table-responsive">
                                <table class="table mb-0 table-striped invoice-table">
                                    <thead class="bg-active">
                                    <tr class="tr">
                                        <th>No.</th>
                                        <th class="pl0 text-start">Name</th>
                                        <?php  if($display_hsn != "0" && !empty($display_hsn))
                                        { ?>
                                        <th class="pl0 text-start">Code</th>
                                        <?php }?>
                                        <th class="text-center">Price</th>
                                        <th class="text-center">Quantity</th>
                                        <?php  if($discount_total != "0" && !empty($discount_total))
                                        { ?>
                                        <th class="text-center">Discount</th>
                                        <?php }?>
                                        <th class="text-end">Amount</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $result = $db->prepare("SELECT iid.* FROM " . DB_BASE . ".invoice_item_detail iid WHERE iid.invoice_detail_id=" . $invoice_detail_id . " AND iid.is_deleted != 1 GROUP BY iid.invoice_detail_item_id;");
                                        $result->execute();
                                        $item = $result->fetchAll(PDO::FETCH_OBJ);
                                        $rowcount = count($item);
                                        $counter = 0;
                                        foreach ($item as $itm){
                                            $counter++;
                                            $product_name = $itm->producttitle; 
                                            $HSC_SAC = $itm->HSC_SAC; 
                                            $rate = $itm->rate; 
                                            $quantity = $itm->quantity; 
                                            $discount = $itm->disc; 
                                            $gst = $itm->igst; 
                                            $gst_rate = $itm->igst_rate; 
                                            $taxable_line_value = $itm->taxable_line_value;                                             
                                        ?>
                                    <tr class="tr">
                                        <td>
                                            <div class="item-desc-1">
                                                <span><?= $counter; ?></span>
                                            </div>
                                        </td>
                                        <td class="pl0"><?= $product_name; ?></td>
                                        <?php  if($display_hsn != "0" && !empty($display_hsn))
                                        { ?>
                                        <td class="pl0"><?= $HSC_SAC; ?></td>
                                        <?php } ?>
                                        <td class="text-center"><?= number_format($rate,2); ?></td>
                                        <td class="text-center"><?= number_format($quantity,0); ?></td>
                                        <?php if($discount_total != "0" && !empty($discount_total))
                                        { ?>
                                        <td class="text-center"><?= number_format($discount,2); ?></td>
                                        <?php }?>
                                        <td class="text-end"><?= number_format($taxable_line_value,2); ?></td>
                                    </tr>
                                   <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div style="height:50px;"></div>
                        <div class="invoice-bottom">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-7" style="width:50%">
                                    <div class="mb-30 dear-client">
                                        <h3 class="inv-title-1">Terms & Conditions</h3>
                                        <p><?= str_replace('.','. <br>',$term_detail) ?></p>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-5" style="width:50%">
                                    <div class="mb-30 payment-method" style="float: right;">
                                        <h2 class="inv-title-1 text-center" style="font-size:18px;">Total</h2>
                                        <ul class="payment-method-list-1 text-14">
                                            <li><h5>Payment Method : <strong><?= $payment_type ?></strong></h5></li>
                                            <?php if($discount_total != "0" && !empty($discount_total)){ ?>
                                            <li><h5>Total Discount : ₹<strong><?= number_format($discount_total,1) ?></strong></h5></li>
                                            <?php } ?>
                                            <?php if($total_tax != "0" && !empty($total_tax)){ ?>
                                            <li><h5>Total Tax : ₹<strong><?= number_format($total_tax,1) ?></strong></h5></li>
                                            <?php } ?>
                                            <li><h5>Grand Total : ₹<strong><?= number_format($grand_total,2) ?></strong></h5></li>
                                        </ul>
                                    </div>
                                </div>  
                            </div>
                        </div>
                        <div class="invoice-contact clearfix">
                            <div class="row g-0">
                                <div class="col-lg-9 col-md-11 col-sm-12">
                                    <div class="contact-info">
                                        <a href="tel:+91<?= $number1 ?>"><i class="fa fa-phone"></i> <?= $number1 ?></a>
                                        <a href="tel:+91<?= $number1 ?>"><i class="fa fa-phone"></i> <?= $number2 ?></a>
                                        <a href="https://www.instagram.com/ <?= $instagram ?>/" target="_blank"><i class="fa fa-instagram"></i> <?= $instagram; ?></a>                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="invoice-btn-section clearfix d-print-none no-print">
                        <a href="javascript:window.print()" class="btn btn-lg btn-print no-print">
                            <i class="fa fa-print"></i> Print Invoice
                        </a>
                        <a id="invoice_download_btn" class="btn btn-lg btn-download btn-theme no-print" onclick="downloadPDF()">
                            <i class="fa fa-download"></i> Download Invoice
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Invoice 1 end -->
<style>
    @media print {
        .no-print {
            display: none;
        }
    }
</style>

<script>
    function downloadPDF() {
        document.querySelector('.invoice-btn-section').style.display = 'none';
        var element = document.getElementById('invoice_content');
        var exten = "<?php echo $customername; ?>";
        var exten2 = "<?php echo date('d-m-y_H_i_s'); ?>";
        var opt = {
            filename:     exten+'_invoice'+exten2+'.pdf',
            image:        { type: 'jpeg', quality: 100 },
            html2canvas:  { scale: 4 },
            jsPDF:        { unit: 'in', format: 'a4', orientation: 'portrait' }
        };
        html2pdf().from(element).set(opt).save();
    }
    setTimeout(function() {
        document.querySelector('.invoice-btn-section').style.display = 'block';
        },3000)
</script>
</body>
</html>
