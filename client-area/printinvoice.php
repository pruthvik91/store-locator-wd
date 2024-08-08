<?php
ini_set('max_execution_time', '300'); // 5 minutes
ini_set('memory_limit', '512M');

if(isset($_GET["btnPDFSubmit"]) && $_GET["btnPDFSubmit"]=="PDFSubmit") {
    require "../config.php";
    $url = ADMIN_URL . "printinvoice.php?1=1";
    foreach($_GET as $key => $val) {
        if(!empty($val) && is_array($val)) {
            foreach($val as $v) {
                $url .= "&$key"."[]=$v";
            }
        } else {
            if($key == "btnPDFSubmit" && $val == "PDFSubmit") {
                $url .= "&btnPDFSubmit=PDFDownloadSubmit";
            } else {
                $url .= "&$key=$val";
            }
        }
    }

    $pdfName = 'wed&nik-'.date("d-m-Y H-i-s").'.pdf';
    $pdfPath = "C:/Users/Lenovo/Downloads/$pdfName"; // Define the correct path
    $url = escapeshellarg($url); // Escape special characters in the URL
    $pdfPath = escapeshellarg($pdfPath); // Escape special characters in the PDF path
    
    $command = "node \"C:/wamp64/www/store-locator-wd/client-area/generate_pdf.js\" $url $pdfPath";
    $command .= ' 2>&1'; // Redirect stderr to stdout

    exec($command, $output, $return_var);
    print_r($return_var);
    if ($return_var !== 0) {
        print_r("Command failed with return code: $return_var\nOutput: " . implode("\n", $output));
    }
    
    // exec($command, $output, $return_var);
   
    // if($return_var === 0) {
    //     // File generated successfully, send it to the browser
    //     header('Content-Type: application/pdf');
    //     header('Content-Disposition: attachment; filename="' . $pdfName . '"');
    //     readfile($pdfPath);
    //     exit;
    // } else {
    //     // Handle error
    //     echo "There was an error generating the PDF.";
    //     exit;
    // }
}

 ?>
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
    <link rel="shortcut icon" href="../assets/images/favicon.png" type="image/x-icon">

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
if((isset($_GET["invoiceid"]))&&($_GET["invoiceid"]!="")){
    
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
    }
}
}else{
    header('Location: invoicedetail.php?error=notfound');
}

?>
<!-- Invoice 1 start -->
<div class="invoice-1 invoice-content" id="invoice_content" style="padding:20px;">
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
                                        <p class="color-white mb-1">Invoice Number <span>#45613</span></p>
                                        <p class="color-white mb-0">Invoice Date <span>21 Sep 2021</span></p>
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
                                                99095 68777 <br/>
                                                95748 40135 <br/>
                                                Patel Mill Road,Keshod <br/>
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
                                        <th class="pl0 text-start">Item Description</th>
                                        <th class="text-center">Price</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-center">Discount</th>
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
                                        <td class="text-center"><?= number_format($rate,2); ?></td>
                                        <td class="text-center"><?= number_format($quantity,0); ?></td>
                                        <td class="text-center"><?= number_format($discount,2); ?></td>
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
                                            <li><h5>Discount Total : ₹<strong><?= number_format($discount_total,1) ?></strong></h5></li>
                                            <li><h5>Grand Total : ₹<strong><?= number_format($grand_total,2) ?></strong></h5></li>
                                            
                                            <li><strong>Branch Name:</strong> xyz</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="invoice-contact clearfix">
                            <div class="row g-0">
                                <div class="col-lg-9 col-md-11 col-sm-12">
                                    <div class="contact-info">
                                        <a href="tel:+55-4XX-634-7071"><i class="fa fa-phone"></i> 99095 68777</a>
                                        <a href="tel:+55-4XX-634-7071"><i class="fa fa-phone"></i> 95748 40135</a>
                                        <a href="tel:info@themevessel.com"><i class="fa fa-instagram"></i> Wed&Nik</a>                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="invoice-btn-section clearfix d-print-none">
                        <a href="javascript:window.print()" class="btn btn-lg btn-print">
                            <i class="fa fa-print"></i> Print Invoice
                        </a>
                        <a id="invoice_download_btn" class="btn btn-lg btn-download btn-theme">
                            <i class="fa fa-download"></i> Download Invoice
                        </a>
                        <?php 
                        $url = ADMIN_URL."printinvoice.php?invoiceid=".$_GET['invoiceid']."";
                        foreach($_POST as $key => $val)
                        {
                            if(!empty($val) && is_array($val))
                            {
                                foreach($val as $v)
                                {
                                    $url .= "&$key"."[]=".htmlspecialchars($v);
                                }
                                
                            }
                        }
                        $url .= "&btnPDFSubmit=PDFSubmit";
                        	$url .= "&uid=".url_crypt($_SESSION["userid"], 'e');
                            $url .= "&guid=".url_crypt(url_crypt($_SESSION["userid"], 'e'), 'e');
                        
					 ?>
                      <a id="invoice_download_btn" class="btn btn-lg btn-download btn-theme" href="<?php echo $url; ?>&btnPDFSubmit=PDFSubmit">
    <i class="fa fa-download"></i> Download Invoice
</a>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Invoice 1 end -->

<script>

 document.getElementById('invoice_download_btn').addEventListener('click', function () {
    var element = document.getElementById('invoice_content');
    var opt = {
        margin: 0,
        filename: 'invoice.pdf',
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2 },
        jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
    };

    html2pdf().from(element).set(opt).outputPdf('blob').then(function(pdfBlob) {
        var url = URL.createObjectURL(pdfBlob);
        var a = document.createElement('a');
        a.href = url;
        a.download = 'invoice.pdf';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);

        // Hide the download button after the PDF is downloaded
        document.getElementById('invoice_download_btn').style.display = 'none';
    });
});


</script>

</body>
</html>
