<?php
require 'wkhtmltopdf/autoload.php';
use mikehaertl\wkhtmlto\Pdf;
$searchname = "";
$barcode_no = "";
$barcode_count = 1;
$product_ids="";
$products="";
$label_no = "";
$line_1 = "";
$line_2 = "";
$header = "";
$url_string = "";
$searchQuery = "";
$searchUser = '';
$field = '';
$barwidth = '';
$line_11 = '';
$line_22 = '';
$header1 = '';
$product_detail=[];
$marginbottom = false;
$isprint = true;
$showReport = true;
if(isset($_GET["btnPDFSubmit"]) && $_GET["btnPDFSubmit"]=="PDFDownloadSubmit")
{
	require_once "../config.php";
	global $db;
    include('configure_report_setting.php');
    $db_main = DB_MAIN;
	$db_base = DB_BASE;
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
	$url = ADMIN_URL."generate_barcode.php?1=1";
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
	$parameter = [
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
		'margin-bottom' => '0',
		'margin-left' => '0',
		'margin-right' => '0',
		'margin-top' => '0',
		'disable-smart-shrinking'
	];
    define("GOGSTBILL_MODE","local");
	if(GOGSTBILL_MODE == "local" || GOGSTBILL_MODE == "offline")
	{
		$parameter['binary'] = WKHTMLTOPDF;
	}
	$pdf = new Pdf($parameter);
	$pdf -> addPage($url);
	ob_clean();
	$pdfname = 'Wed & Nik Barcode - '.date("d-m-Y H-i-s").'.pdf';
	$pdf->send($pdfname);
	exit;
	
} 
else {
	
	$title = "Generate Barcode";
    require_once "../config.php";
	include('header.php');
	$db_main = DB_MAIN;
	$db_base = DB_BASE;
}
if($isprint){
	global $db;
	if(true)
	{
		if(isset($_GET["btnPDFSubmit"]) && $_GET["btnPDFSubmit"]=="PDFSubmit")
		{
			
			$_POST = array();
			$_GET = array();
		}
	}
	else
	{
		include('access-denied.php');
		$showReport = false;
	}
}
if($showReport)
{
		$barcode_no =[];
		$product_ids = isset($_REQUEST['pid']) ? $_REQUEST['pid'] : '';
		$products = rtrim(implode(',',explode('-',$product_ids)),',');
		$products = str_replace("'", '', $products);
		$result1 = $db->prepare("SELECT product_name,sell_price,barcode_no FROM " . $db_base . ".store_product where  product_id IN ($products)");
		$result1->execute(); 
		$rows1 = $result1->fetchAll(PDO::FETCH_ASSOC);
		foreach($rows1 as $rw){
			$tmp_array = $rw['product_name'];
			if($rw['barcode_no'] == 0 && empty($rw['barcode_no'])){
				$barcode_no[] = $tmp_array;
			}
		}
	$lineheight = '3px';
	$lineheight_header = "14px";
	$barheight = 15;
	$size = "";
	$printer_name = '';
	$barwidth = 1;
	if((!empty($_POST) && $_POST['label_no']!='') && ((isset($_POST["btnSubmit"]) && $_POST["btnSubmit"] == "Submit"))){
		foreach($rows1 as $rs1){
		if($rs1['barcode_no'] != null && !empty($rs1['barcode_no'])){
		$product_name=$rs1['product_name'];
		$sellprice=$rs1['sell_price'];
		$label_no = isset($_POST['label_no']) ? $_POST['label_no'] : '';
		$header = $header1= isset($_POST['Header']) ? $_POST['Header'] : '';
		$line_1 = $line_11= isset($_POST['line_1']) ? $_POST['line_1'] : '';
		$line_2 = $line_22= isset($_POST['line_2']) ? $_POST['line_2'] : '';
		$printer_name = isset($_POST['printer_name']) ? $_POST['printer_name'] : '';
		$size = isset($_POST['size']) ? $_POST['size'] : '';
        $orgname = "Wed & Nik";
		switch($header){
			case '{{Company Name}}':
				
				$header1 = $orgname;
			break;
			case '{{Product Name}}':
				$header1 = $product_name;
				
			break;
			case '{{Price}}':
				$header1 = "Price: ".floor($sellprice);
			break;
		}
	
		switch($line_1){
			case '{{Company Name}}':
				$line_11 = $orgname;
			break;
			case '{{Product Name}}':
				$line_11 = $product_name;
			break;
			case '{{Price}}':
				$line_11 = "Price: ".floor($sellprice);
			break;
		}
	
		switch($line_2){
			case '{{Company Name}}':
				$line_22 = $orgname;
			break;
			case '{{Product Name}}':
				$line_22 = $product_name;
			break;
			case '{{Price}}':
				$line_22 = "Price: ".floor($sellprice);
			break;
				
		}
		$product_detail[] = array('barcode_no'=>$rs1['barcode_no'],'header'=>$header1,'line1'=>$line_11,'line2'=>$line_22);
	}
  	}
	
		$labelSettings = [
			'65_label' => [
				'label_rows' => '13',
				'label_col' => '5',
				'bar_col_height' => '84px',
				'table_width' => '760px',
				'lineheight_header' => '1.5',
				'lineheight' => '1.5',
				'bar_fontsize' => 10,
				'line_fontsize' => 9,
				'barheight' => 13,
				'barwidth' =>0.7,
				'padding' => '17px 0px 13px 16px',
				'dw_padding' => '17px 0px 13px 0px',
				'barcode_count' => 65,
			],
			'48_label' => [
				'label_rows' => '12',
				'label_col' => '4',
				'table_width' => '756px',
				'lineheight_header' => '13px',
				'lineheight' => 1.5,
				'bar_fontsize' => 11,
				'line_fontsize' => 10,
				'barheight' => 18,
				'bar_col_height' => '91px',
				'barwidth' =>0.7,
				'padding' => '14px 0px 13px 19px',
				'dw_padding' => '14px 0px 10px 0px',
				'barcode_count' => 48,
			],
	
			'40_label' => [
				'column_gap' => '5px',
				'row_gap' => '5px',
				'label_rows' => '8',
				'label_col' => '5',
				'bar_col_height' => '136px',
				'lineheight_header' => '16px',
				'table_width' => '754px',
				'lineheight' => '2',
				'bar_fontsize' => 12,
				'line_fontsize' => 11,
				'barheight' => 22,
				'barwidth' =>0.8,
				'padding' => '14px 0px 10px 19px',
				'dw_padding' => '14px 0px 10px 0px',
				'barcode_count' => 40,
			],
			'24_label' => [
				'label_rows' => '8',
				'label_col' => '3',
				'column_gap' => '10px',
				'row_gap' => '0px',
				'lineheight_header' => '19px',
				'bar_col_height' => '131px',
				'table_width' => '754px',
				'lineheight' => '1.5',
				'dw_lineheight' => '16px',
				'bar_fontsize' => 15,
				'line_fontsize' => 13,
				'barheight' => 30,
				'barwidth' =>1,
				'padding' => '38px 0px 13px 19px',
				'dw_padding' => '38px 0px 13px 0px',
				'barcode_count' => 24,
			],
			'21_label' => [
				'label_rows' => '7',
				'label_col' => '3',
				'column_gap' => '9px',
				'row_gap' => '0px',
				'lineheight_header' => '19px',
				'bar_col_height' => '148px',
				'table_width' => '750px',
				'lineheight' => '1.5',
				'bar_fontsize' => 15,
				'line_fontsize' => 13,
				'barheight' => 30,
				'barwidth' =>1,
				'padding' => '40px 0px 13px 19px',
				'dw_padding' => '40px 0px 13px 0px',
				'barcode_count' => 21,
			],
			'12_label' => [
				'label_rows' => '6',
				'label_col' => '2',
				'bar_col_height' => '172px',
				'table_width' => '765px',
				'lineheight_header' => '30px',
				'lineheight' => '2',
				'bar_fontsize' => 25,
				'line_fontsize' => 20,
				'barheight' => 35,
				'barwidth' => 1.7,
				'padding' => '36px 0px 21px 17px',
				'dw_padding' => '33px 0px 21px 0px',
				'barcode_count' => 12,
			],
			'2_label_50' => [
				'label_rows' => '1',
				'label_col' => '2',
				'lineheight_header' => 1,
				'lineheight' => '1',
				'bar_fontsize' => 12,
				'table_width' => '378px',
				'line_fontsize' => 11,
				'bar_col_height' => '94px',
				'barheight' => 25,
				'barwidth' => 1.5,
				'padding' => '10px 15px 0px 28px',
				'dw_padding' => '10px 15px 0px 28px',
				'barcode_count' => 2,
			],
			'1_label_50' => [
				'label_rows' => '1',
				'label_col' => '1',
				'lineheight_header' => 'normal',
				'lineheight' => 'normal',
				'table_width' => '378px',
				'bar_col_height' => '189px',
				'bar_fontsize' => 24,
				'line_fontsize' => 22,
				'barheight' => 30,
				'barwidth' => 2,
				'padding' => '10px 15px 0px 28px',
				'dw_padding' => '10px 15px 0px 28px',
				'barcode_count' => 1,
			],
			'1_label_25' => [
				'label_rows' => '1',
				'label_col' => '1',
				'lineheight_header' => 1,
				'lineheight' => '1',
				'bar_fontsize' => 12,
				'table_width' => '189px',
				'line_fontsize' => 11,
				'bar_col_height' => '94px',
				'barheight' => 25,
				'barwidth' => 1.5,
				'padding' => '10px 15px 0px 28px',
				'dw_padding' => '10px 15px 0px 28px',
				'barcode_count' => 2,
			],
			'2_label_25' => [
				'label_rows' => '2',
				'label_col' => '2',
				'lineheight_header' => 'normal',
				'table_width' => '286px',
				'bar_col_height' => '94px',
				'lineheight' => '1',
				'bar_fontsize' => 12,
				'line_fontsize' => 10,
				'barheight' => 20,
				'barwidth' => 1,
				'padding' => '10px 15px 0px 28px',
				'dw_padding' => '10px 15px 0px 28px',
				'barcode_count' => 2,
			],
		];
		$settings = $labelSettings[$size] ?? [];
		extract($settings);
		if(!empty($product_detail) ){
	?>
	<style>
				
			.barcode-table{
				border-spacing: 30px;
			}
			.barcode-table tr td{
				<?php if ((isset($_GET["btnPDFSubmit"]) && $_GET["btnPDFSubmit"]=="PDFDownloadSubmit")) { ?>
				border : 1px;
				<?php }else{ ?>
				border : 1px solid;
				<?php } ?>
			}
			.barcode-container {
				overflow-x: auto;
				<?php if ((isset($_GET["btnPDFSubmit"]) && $_GET["btnPDFSubmit"]=="PDFDownloadSubmit")) { ?>
				padding: <?php echo $dw_padding; ?>;
				<?php }else{?>
				padding: <?php echo $padding; ?>;
			<?php } ?>
			}
			.barcode-container .barcode-table {
				margin: auto;
				width: <?php echo $table_width; ?>;
				max-width : <?php echo $table_width; ?>;
			}
			.bar-col .inner-box > div , .bar-col .inner-box > svg {
				float: left;
				width: 100%;
				overflow: hidden;
			}
			.barcode-table {
				overflow: hidden;
				-webkit-user-select: none;
				-ms-user-select: none;
				user-select: none;
			}
			.bar-col .header {
				line-height: <?php echo $lineheight_header; ?>;
				font-size: <?php echo $bar_fontsize . "px"; ?>;
				color: #000;
				font-weight: 400;
			}
			.bar-col .line_1 {
				font-size: <?php echo $line_fontsize . "px"; ?>;
				color: #000;
				<?php if ((isset($_GET["btnPDFSubmit"]) && $_GET["btnPDFSubmit"]=="PDFDownloadSubmit")) { ?>
					font-weight: 600;
				<?php }else{ ?>
				font-weight: 900;
					<?php } ?>
				line-height: <?php echo $lineheight; ?>;
			}
			.bar-col .line_2 {
				font-size: <?php echo $bar_fontsize . "px"; ?>;
				color: #000;
				
			}
			.bar-col{
				text-align: center;
				height: <?php echo $bar_col_height; ?>;
			}
			.bar-row{
				vertical-align: middle;
			}
			.inner-box{
				vertical-align: middle;
				height:<?php echo $bar_col_height;?>;
				overflow: hidden;
			}
			<?php if ((isset($_GET["btnPDFSubmit"]) && $_GET["btnPDFSubmit"]=="PDFDownloadSubmit")) { ?>
			.bulk-action{
			background-color: #fff !important;
			}
			<?php } 
		?>
			@media print {
					@page {
						margin: 0;
					}
                    .sidebar-folded{
                        height: initial;
                        width: initial;
                    }
                    .page-content
                    {
                        margin:0 !important;
                        padding:0 !important;
                    }
					.footer ,.horizontal-menu,.subnavbar,
					.navbar,
					.widget-header,
					.widget .widget-content-search,
					.floating-dropup-contact,
					.footer,
					.drawer-hamburger,
					.container #myTable {
						display: none !important;
						margin: 0;
						padding: 0;
						margin: 0;
						padding: 0;
					}
					.subnavbar,
					.navbar,
					.form-actions,
					.footer,
					.drawer-toggle.drawer-hamburger,
					#myTable,
					.expiring_soon {
						display: none;
						margin: 0;
						padding: 0;
					}
                    .widget-content{
                        display: none !important;
						margin: 0 !important;
						padding: 0 !important;
                    }
					.widget #myTable {
						display: none !important;
						margin: 0 !important;
						padding: 0 !important;
					}
					.main, .main-wrapper,
					.container,.page-wrapper,
					.span12 {
						width: 750px;
						margin: 0;
						padding: 0;
                        min-height: 0px !important;
					}
					body {
						min-width: 0;
						min-height: 0;
						margin: 0;
						padding: 0;
					}
					.main .widget-content,
					.span12 .page-content {
						border: none !important;
						margin: 0;
                        min-height: 0px !important;
						padding: 0;
					}
					body {
						background: #fff;
						margin: 0;
						padding: 0;
					}
					.floating-highlight-contact {
						display: none;
						margin: 0;
						padding: 0;
					}
					.adsbygoogle {
						display: none;
						margin: 0;
						padding: 0;
					}
					body.bulk-action .form-actions.form-actions-floating {
						display: none;
					}
					.widget,.page-content {
						background-color: none;
						box-shadow: none;
						margin: 0;
						padding: 0;
					}
					.span12 {
						margin: 0;
						padding: 0;
					}
					.main {
						padding-bottom: 0;
						margin: 0;
						padding: 0;
					}
					form {
						margin: 0;
						padding: 0;
					}
					.footer-inner{
						margin: 0;
						padding: 0;
					}
					.barcode-table tr td{
						border: 1px;
					}
					.barcode-container { 
						overflow-x: unset; 
					}
					.note{
						display: none;
					}
                    .bulk-action{
                        width:initial;
                        height:initial;
                    }
                    
				}
			</style>
		
	<?php
	}
	}
	$url = ADMIN_URL . "generate_barcode.php?pid=" . $products . "&label_no=" . $label_no . "&btnPDFSubmit=PDFSubmit";
	if((!(isset($_GET["btnPDFSubmit"]) && $_GET["btnPDFSubmit"]=="PDFDownloadSubmit")))
	{
	?>
	
				
		<div class="widget" id="ContentPlace_trmsg">
		<div class="widget-header container-header toggle-content-widget">
			<div class="widget-header  container-header ">
				<h3>Generate Barcode</h3>
			</div>
		<i class="fa drop-down-toggle fa-plus-square" style="float:right;" aria-hidden="true"></i>
		</div>
			<div class="widget-content widget-content-toggle">
				<hr class="seperator-search-box"/>
				<form method="post">
					<div class="control-group-search-container">
						<div class="control-group-search" style="float:left; margin-left:10px;">
							<label class="control-label" for="firstname">No of Label:</label>
							<input type="number" class="span3" name="label_no" id="label_no" value="<?php echo htmlspecialchars($label_no); ?>" />
							<input type="hidden" name="barcode_no" id="barcode_no" value="<?php echo htmlspecialchars($products); ?>" />
						</div>
						<div class="control-group-search " style="float:left; margin-left:10px;">
							<label class="control-label" for="firstname">Header:</label>
							<input type="text" class="span3 autoHeaderField" name="Header" id="Header" value="<?php echo $header; ?>"  data-value="<?php echo $header1; ?>" autocomplete="off"/>
						</div>
						<div class="control-group-search" style="float:left; margin-left:10px;">
							<label class="control-label" for="firstname">Line 1:</label>
							<input type="text" class="span3 autoHeaderField" name="line_1" id="line_1" value="<?php echo $line_1; ?>" data-value="<?php echo $line_11; ?>" autocomplete="off" />
						</div>
						
						<div class="control-group-search" style="float:left; margin-left:10px;">
							<label class="control-label" for="firstname">Line 2:</label>
							<input type="text" class="span3 autoHeaderField" name="line_2" id="line_2" value="<?php echo $line_2; ?>" data-value="<?php echo $line_22; ?>" autocomplete="off"/>
						</div>
						<div class="control-group-search" style="float:left; margin-left:10px;">
							<label class="control-label" for="firstname">Printer:</label>
							<select class="span3" name="printer_name" id="printer">
								<option value="regular" <?php echo ($printer_name == "regular") ? 'selected' : '' ?>>Regular</option>
								<option value="label" <?php echo ($printer_name == "label") ? 'selected' : '' ?>>Label</option>
							</select>
						</div>
						<div class="control-group-search" style="float:left; margin-left:10px;" id="sizeContainer">
							<label class="control-label" for="size">Size:</label>
							<select class="span3" name="size" id="size">

							</select>
						</div>
						<div class="list-detail-search-action">
							<div class="control-group-search control-group-search-action search-btn" style="margin-left:20px; margin-top:20px;">
								<button type="submit" class="btn btn-primary" value="Submit" name="btnSubmit"><i class="fa fa-barcode"></i>Generate</button>
								<?php if ($label_no != '') { ?>
									
								<?php } ?>
							</div>
						</div>
					</div>
				</form>
			
			</div>
			<p class="note">Note: Only Products Having Barcode Number Are Displayed</p>
			
			<?php if(!empty($barcode_no)) {
				$temp_barcode = implode(', ',$barcode_no);
				?>
			<p class="note" style="color: #ff0000;">Barcode No. Not Found For : <?php echo $temp_barcode ?></p>
			<?php } ?>
		</div> <!-- /widget-content -->
	<?php
	} else { ?>
		<!DOCTYPE html>
		<html xmlns="http://www.w3.org/1999/xhtml">

		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<link href="css/my-bootstrap.min.css<?php echo URLVERSION . VERSION; ?>" rel="stylesheet">
			<link href="css/OpenSans.css<?php echo URLVERSION.VERSION; ?>" rel="stylesheet">
			<link href="css/style.css<?php echo URLVERSION . VERSION; ?>" rel="stylesheet">
			<script src="js/jquery-1.12.4.min.js<?php echo URLVERSION . VERSION; ?>"></script>
			<script src="js/jquery-migrate-1.4.1.min.js<?php echo URLVERSION . VERSION; ?>"></script>
			<script src="js/JsBarcode.js<?php echo URLVERSION . VERSION; ?>"></script>
			<script src="js/ui/jquery-ui.js<?php echo URLVERSION . VERSION; ?>"></script>
		</head>
		<body>
			<div class="container">
			<?php }
		if ($label_no != '') {
			?>
							<form id="list-data-table" name="tabledata" method="post">
								<div class=" widget widget-table action-table">
									<div class="barcode-temp"></div>
								</div>
							</div>
							</form>
						<?php if(!((isset($_GET["btnPDFSubmit"]) && $_GET["btnPDFSubmit"]=="PDFDownloadSubmit"))){?>
				<div class="form-actions form-actions-floating " >
					<button type="Button" class="btn" name="print" onclick="window.print()"><i class="fa fa-print"></i>Print</button>
									<?php 
									foreach($_POST as $key => $val)
									{
										if(!empty($val) && is_array($val))
										{
											foreach($val as $v)
											{
												$url .= "&$key"."[]=$v";
											}
										}
										else{
											if($key == "btnSubmit" && $val == "Submit")
											{
												$url .= "&btnPDFSubmit=PDFSubmit";
											}
											else
											{
												$url .= "&$key=$val";
											}
										}
									} 
									$url .= "&label_no=".$label_no;
                                    $url .= "&uid=".url_crypt($_SESSION["userid"], 'e');
									$url .= "&guid=".url_crypt(url_crypt($_SESSION["userid"], 'e'), 'e');
				 					
									?>
									<a class="btn  pull-right " data-filename="gogst_barcode.pdf" href="<?php echo $url.'&wtn-download-file=true'; ?>" style="width:auto;"><i class="fa fa-download"></i>Download</a>
				</div>
			
			<?php }
			} else { ?>
			<div class="widget widget-table action-table" id="myTable">
				<div class="actions_container" id="barcode-container">
					<img src="images/barcode.svg" class="no-record-img" alt="no aimage" width="200px" draggable="false" />
					<h3 class="no-data">Generate Barcode</h3>
				</div>
				</div>
			<?php
		}

		?>
		
		<script>
			var barcode_count = 0;
			<?php $count=0; 
			if(count($product_detail) > 0){ ?>
				$('body').addClass("bulk-action");
				var totalcount = <?php echo count($product_detail) ;?>;
				 <?php 
				foreach($product_detail as $bno){ $count++; ?>
							var count = <?php echo $count; ?>;
							var selected_count = <?php echo $barcode_count; ?>;
							var barcodeValue = '<?php echo $bno['barcode_no'] ?>';
							var header = "<?php echo $bno['header'] ?>";
							var line_1 ="<?php echo $bno['line1'] ?>";
							var line_2 = "<?php echo $bno['line2'] ?>";
							var newBarcode = function() {
								$(".barcode<?php echo $count; ?>").JsBarcode(
									barcodeValue, {
										"format": "CODE128",
										"background": "#ffffff",
										"fontSize": <?php echo $bar_fontsize; ?>,
										"height": <?php echo $barheight; ?>,
										"width": <?php echo $barwidth; ?>,
										"margin": 5,
										"font": "Open Sans",
										"textAlign": "center",
									}
								)
							};
					$('.barcode<?php echo $count; ?>').remove();
					if(count != 1){
						if(barcode_count == selected_count){
							barcode_count= 0;
							$('.barcode-temp').append("<div class='barcode-main'><div class='inner-box'><div class='header header<?php echo $count; ?>'></div><svg class='barcode barcode<?php echo $count; ?>'></svg><div class='line_1 line_1<?php echo $count; ?>' id='line_1'></div><div class='line_2 line_2<?php echo $count; ?>' id='line_2'></div></div></div>");
						}else{
							$('.barcode-temp .barcode-main:last').after("<div class='barcode-main'><div class='inner-box'><div class='header header<?php echo $count; ?>'></div><svg class='barcode barcode<?php echo $count; ?>'></svg><div class='line_1 line_1<?php echo $count; ?>' id='line_1'></div><div class='line_2 line_2<?php echo $count; ?>' id='line_2'></div></div></div>");
						}
							barcode_count++;
					}else{
						barcode_count++;
						$('.barcode-temp').append("<div class='barcode-main'><div class='inner-box'><div class='header header<?php echo $count; ?>'></div><svg class='barcode barcode<?php echo $count; ?>'></svg><div class='line_1 line_1<?php echo $count; ?>' id='line_1'></div><div class='line_2 line_2<?php echo $count; ?>' id='line_2'></div></div></div>");
					}
						var label_no = <?php echo $label_no; ?>;
						for (var i = 2; i <= label_no; i++) {
					if(barcode_count==selected_count)
					{barcode_count=0;
						$('.barcode-temp').append("<div class='barcode-main'><div class='inner-box'><div class='header header<?php echo $count; ?>'></div><svg class='barcode barcode<?php echo $count; ?>'></svg><div class='line_1 line_1<?php echo $count; ?>' id='line_1'></div><div class='line_2 line_2<?php echo $count; ?>' id='line_2'></div></div></div>");
					}	
					
					else{
						$('.barcode-temp .barcode-main:last').after("<div class='barcode-main'><div class='inner-box'><div class='header header<?php echo $count; ?>'></div><svg class='barcode barcode<?php echo $count; ?>'></svg><div class='line_1 line_1<?php echo $count; ?>' id='line_1'></div><div class='line_2 line_2<?php echo $count; ?>' id='line_2'></div></div></div>");
					}
					barcode_count++;
					}
					newBarcode();		
						$('.header<?php echo $count; ?>').html(header);
						$('.line_1<?php echo $count; ?>').html(line_1);
						$('.line_2<?php echo $count; ?>').html(line_2);
						<?php  }  ?>
							
					var ttllabel = <?php echo isset($label_no)?$label_no:0; ?>;
					ttllabel = totalcount*ttllabel; 
					var ttlcol = <?php echo isset($label_col)?$label_col:0; ?>;
					var pagerow = <?php echo isset($label_rows)?$label_rows:0; ?>;
					var appendtr = '';
					var ttlrow =ttlc = r = c = ttr = 0;
					var widthAttribute = document.querySelector(".barcode").getAttribute("width");
					var heightAttribute = document.querySelector(".barcode").getAttribute("height");
					$('.barcode-temp .barcode-main').each(function(){
						if(c == 0){
							ttr++;
							appendtr += '<tr class="bar-row">';
							ttlrow++;
						}
						c++;
						if(c <= ttlcol){
							appendtr += "<td class='bar-col'>"+$(this).html()+"</td>";	
							if(c == ttlcol ){
								c=0;
							}
						}
						if(c == 0){
							appendtr += '</tr>';
						}
						if(ttr%pagerow == 0 && c==0){
							$('.action-table').append('<div class="barcode-container"><table class="barcode-table "></table></div>');
							if(ttr == 0){
								$('.barcode-table').append(appendtr);
							}else{
								$('.barcode-table:last').append(appendtr);
							}
							appendtr = '';
							ttr = 0;
						}
						ttlc++;
						if(ttlc == ttllabel){
							$('.action-table').append('<div class="barcode-container"><table class="barcode-table "></table></div>');
							$('.barcode-table:last').append(appendtr);
							if($('.barcode-table:last tr').length == 0)
							{
								$('.barcode-container:last').remove();
							}
						}
					});
					var htmltd = '';
					var htmltr = '';
					var remainingcol = ttlrow*ttlcol-ttllabel;
					var remainingrow = pagerow-ttlrow;
					var csswidthAttribute = $("td.bar-col").width();
					var cssheightAttribute = $("td.bar-col").height();
					for(var i=1;i <=remainingcol; i++)
					{
						htmltd += "<td class='bar-col'><div class='inner-box'><div class='header '></div><svg class='barcode' ></svg><div class='line_1' ></div><div class='line_2'></div></div></div></td>";
					}
					$('.barcode-table:last .bar-col:last').after(htmltd);
					if(remainingcol!=0){
						$('.bar-col').css('width', csswidthAttribute);
						$('.barcode').css('height', heightAttribute);
					}
					var headerheight = parseInt($(".bar-col .header").css('height').replace('px',''));
					var barcodeheight = $(".bar-col .barcode").outerHeight();
					var line_1height = parseInt($(".bar-col .line_1").css('line-height').replace('px',''));
					var line_2height = $(".bar-col .line_2").outerHeight();
					var calcheight= headerheight+barcodeheight+line_1height+line_2height;
					if(calcheight >= cssheightAttribute){
						cssheightAttribute=cssheightAttribute-3;
						var cssRule = '.inner-box { height: ' + cssheightAttribute + 'px !important; }';
						$('style#custom-css').remove();
						$('<style id="custom-css">' + cssRule + '</style>').appendTo('head');
					}
					else{
						var cssRule = '.inner-box { height: ' + calcheight + 'px !important; }';						
						$('style#custom-css').remove();
						$('<style id="custom-css">' + cssRule + '</style>').appendTo('head');
					}
					
					$('.barcode-temp').remove();
					<?php } ?>
		</script>
		<?php 	 if((!(isset($_GET["btnPDFSubmit"]) && $_GET["btnPDFSubmit"]=="PDFDownloadSubmit"))){?>
			<script>
					var autoHederList = [];
					autoHederList = [
						{
							label: "{{Company Name}}",
						},
						{
							label: "{{Product Name}}",
						},
						{
							label: "{{Price}}",
						}
				];
					$(".autoHeaderField").autocomplete({
						source: autoHederList,
						change: function(event, ui) {
							if (ui.item == null || ui.item == undefined) {} else {
								if (ui.item) {
									$(this).val(ui.item.label);
								}
							}
						},
						select: function(event, ui) {
							$(this).val(ui.item.label);
							return false;
						},
						minLength: 0
					}).focus(function() {
						$(this).autocomplete("search");
					});
					</script>
			<script>
				document.addEventListener('DOMContentLoaded', () => {
				$('#printer').change(function() {
					var selectedPrinter = $(this).val();
					var selectedSizeOption = $('#size option[value="' + selectedPrinter + '"]');
					$('#size option').prop('selected', false);
					if (selectedSizeOption.length > 0) {
						selectedSizeOption.prop('selected', true);
					}
					var options = [];
					if (selectedPrinter === 'label') {
						options = [{
								value: '2_label_50',
								label: '2 Labels (50 * 25mm)'
							},
							{
								value: '1_label_50',
								label: '1 Label (100 * 50mm)'
							},
							{
								value: '1_label_25',
								label: '1 Label (50 * 25mm)'
							},
							{
								value: '2_label_25',
								label: '2 Labels (38 * 25mm)'
							}
						];
					} else {
						options = [{
								value: '65_label',
								label: '65 Labels (38 * 21mm)'
							},
							{
								value: '48_label',
								label: '48 Labels (48 * 24mm)'
							},
							{
								value: '40_label',
								label: '40 Labels (39 * 35mm)'
							},
							{
								value: '24_label',
								label: '24 Labels (64 * 34mm)'
							},
							{
								value: '21_label',
								label: '21 Labels (63 * 38mm)'
							},
							{
								value: '12_label',
								label: '12 Labels (100 * 44mm)'
							}
							
						];
					}
					var size = '<?php echo $size; ?>';
					$('#size').empty();
					options.forEach(function(option) {
						var selected = '';
						if (size == option.value) {
							selected = "selected";
						}
						$('#size').append('<option value="' + option.value + '"' + selected + ' >' + option.label + '</option>');
					});
				});
				$('#printer').change();
			});
			</script>
			<?php } ?>
		
		<?php
		if (isset($_GET["btnPDFSubmit"]) && $_GET["btnPDFSubmit"] == "PDFDownloadSubmit") { ?>
			</div>
		</body>

		</html>
	<?php }
	else
	{
		include('footer.php');
	}
}	?>
	
	

