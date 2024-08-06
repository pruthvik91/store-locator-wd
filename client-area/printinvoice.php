<?php
require 'wkhtmltopdf/autoload.php';
use mikehaertl\wkhtmlto\Pdf;
if(isset($_GET["btnPDFSubmit"]) && $_GET["btnPDFSubmit"]=="PDFDownloadSubmit")
{
	require_once "../config.php";
	global $db;
	include('configure_report_setting.php');
	foreach($_GET as $key => $val)
	{
		if($key == "btnPDFSubmit" && $val == "PDFDownloadSubmit")
		{
			$_POST["btnSubmit"]='Submit';
		}
		else
		{
			$_POST[$key]=$val;
		}
	}
	$isprint = false;
	
}
else if(isset($_GET["btnPDFSubmit"]) && $_GET["btnPDFSubmit"]=="PDFSubmit")
{
	require_once "../config.php";
	global $db;
	include('configure_report_setting.php');
	$url = ADMIN_URL."printinvoice.php?1=1";
	foreach($_GET as $key => $val)
	{
		if(!empty($val) && is_array($val))
		{
			foreach($val as $v)
			{
				$url .= "&$key"."[]=$v";
			}
			
		}
		else{
			if($key == "btnPDFSubmit" && $val == "PDFSubmit")
			{
				$url .= "&$key=PDFDownloadSubmit";
			}
			else
			{
				$url .= "&$key=$val";
			}
			
		}
	}
	
	$parameter = array(
		'ignoreWarnings' => true,
		'commandOptions' => array(
			'useExec' => true,   
			'procEnv' => array(
				'LANG' => 'en_US.utf-8',
			),
			'procOptions' => array(
				'bypass_shell' => true,
				'suppress_errors' => true,
			),
		),
		'margin-bottom' => '10',
		'margin-left' => '0',
		'margin-right' => '0',
		'margin-top' => '10',
		'print-media-type',
		'disable-smart-shrinking',
	);
    $parameter['page-size'] = 'A4';
    $parameter['binary'] = WKHTMLTOPDF;
	$pdf = new Pdf($parameter);
	$pdf -> addPage($url);
	ob_clean();
	$pdfname = 'invoice- '.date("d-m-Y H-i-s").'.pdf';
	$pdf->send($pdfname);
	exit;
	
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
    }
}
}else{
    header('Location: invoicedetail.php?error=notfound');
}

?>
<!-- Invoice 1 start -->
<div class="invoice-1 invoice-content" id="invoice_content">
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
                                        <td class="text-center"><?= $rate; ?></td>
                                        <td class="text-center"><?= $quantity; ?></td>
                                        <td class="text-end"><?= $taxable_line_value; ?></td>
                                    </tr>
                                   <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="invoice-bottom">
                            <div class="row">
                                <div class="col-lg-6 col-md-8 col-sm-7">
                                    <div class="mb-30 dear-client">
                                        <h3 class="inv-title-1">Terms & Conditions</h3>
                                        <p><?= $term_detail ?></p>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-4 col-sm-5">
                                    <div class="mb-30 payment-method">
                                        <!-- <h3 class="inv-title-1">Payment Method</h3>
                                        <ul class="payment-method-list-1 text-14">
                                            <li><strong>Account No:</strong> 00 123 647 840</li>
                                            <li><strong>Account Name:</strong> Jhon Doe</li>
                                            <li><strong>Branch Name:</strong> xyz</li>
                                        </ul> -->
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
                        <a  class=" btn_white pdf-download-btn btn report-desktop-button pull-right" href="<?php echo $url.'&wtn-download-file=true';?>"><i class="fa fa-download"></i>Download PDF</a>
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
        html2pdf(element, {
            margin: 0,
            filename: 'invoice.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2 },
            jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
        });
    });
</script>

</body>
</html>
