var companyRemeningPayment = [];
var inventory_enable = 1;
var quantity_decimal_rounding = 100;
var price_decimal_rounding = 100;
var gst_decimal_rounding = 100;
var taxable_decimal_rounding = 100;
var gst_rate_decimal_rounding  = 100;
var price_decimal_rounding_by = 100;
var productAllowed = 1;
var pre=($("#invoice_id").val());
var tapelement="first";
var count=0;
var call=0;
var rowdata = '<tr class="product-item-row">';
rowdata += '	<td>';
rowdata += '		<label class="product-item-row-number">1</label>';
rowdata += '	</td>';
rowdata += '	<td>';
rowdata += '		<label class="product-item-row-number product-item-row-number-tab">1</label><div class="inputlabel_wrapper"><input class="product-combobox"  placeholder="Enter Product name"  maxlength="500"  ><label>Product Name</label></div>';
rowdata += '		<input class="hidden-item-product-id" 			name="hidden-item-product-id[]" 		type="hidden" >';
rowdata += '		<input class="hidden-item-detail-product-id" 	name="hidden-item-detail-product-id[]" 		type="hidden" >';
rowdata += '		<input class="hidden-item-product-name" 		name="hidden-item-product-name[]" 		type="hidden" >';
rowdata += '		<input class="hidden-item-product-uom" 		name="hidden-item-product-uom[]" 		type="hidden" >';
rowdata += '	</td>';
rowdata += '	<td>';
rowdata += '		<div class="inputlabel_wrapper"><input type="text" name="hsccode[]" class="hsccode"  placeholder="HSN/SAC" class="span2" style="width:60px;" maxlength="20"><label>HSN/SAC</label></div>';
rowdata += '	</td>';
rowdata += '	<td>';
rowdata += '		<div class="inputlabel_wrapper"><input value="" type="text" name="quantity[]" class="quantity decimal_numeric_only"  placeholder="Qty." class="span2" style="width:50px;" maxlength="10" data-msg="Not enough stock!" ><label>Qty</label></div>';
rowdata += '		<label class="product-quantity-available"></label>';
rowdata += '	</td>';
rowdata += '	<td><center>';
rowdata += '		<div class="inputlabel_wrapper"><input type="text" name="rate[]" class="rate decimal_numeric_only"    placeholder="Price" style="width:100px;"  class="span2" maxlength="10"  ><label>Price</label></div>';
rowdata += '		<div class="currency-fields" >';
rowdata += '		<div class="currency-factor-symbol">Rs</div>';
rowdata += '		<div class="inputlabel_wrapper"><input type="text" class="rate_usd decimal_numeric_only"    placeholder="Price" class="span2" maxlength="10"  ><label>Price</label></div>';
rowdata += '		</div>';
rowdata += '		<label class="customer_rate_label" ></label>';
rowdata += '		<span class="last-price-tip" ><i class="fa fa-info"></i></span>';
rowdata += '	</td></center>';
rowdata += '	<td class="product_row_igst_col">';
rowdata += '		<div class="inputlabel_wrapper"><select name="igst[]" class="igst">';
rowdata += '													<option value="">--</option>';
rowdata += '			'+gst_rates_options_igst+'';
rowdata += '												</select><label>IGST</label></div>';
rowdata += '		<input type="text" name="igst_rate[]" class="igst_rate text_rate decimal_numeric_only"    readonly="readonly" class="span2" style="width:50px;" value="0" >';
rowdata += '	</td>';
rowdata += '	<td class="discount_field">';
rowdata += '		<div class="inputlabel_wrapper"><input type="text" name="disc[]" class="disc decimal_numeric_only"   class="span2" style="width:50px;"  maxlength="10"  ><label>Disc.</label></div>';
rowdata += '		<div class="currency-fields" >';
rowdata += '		<div class="currency-factor-symbol">Rs</div>';
rowdata += '		<div class="inputlabel_wrapper"><input type="text" class="discount_usd decimal_numeric_only"    placeholder="Discount" class="span2" maxlength="10"  ><label>Price</label></div>';
rowdata += '		</div>';
rowdata += '		<input type="hidden" name="taxable_line_value[]" class="taxable_line_value"   class="span2"   >';
rowdata += '		<input type="hidden" name="gd_taxable_line_value[]" class="gd_taxable_line_value">';
rowdata += '		<input type="hidden" name="gd_discount_line_value[]" class="gd_discount_line_value">';
rowdata += '	</td>';

rowdata += '	<td><center>';
rowdata += '		<div class="inputlabel_wrapper">	<input type="text" name="line_total[]" class="line_total decimal_numeric_only"    placeholder="Total"  class="span2" style="width:80px;"  ><label>Total</label></div>';
rowdata += '		<div class="currency-fields" >';
rowdata += '		<div class="currency-factor-symbol">Rs</div>';
rowdata += '			<div class="inputlabel_wrapper"><input type="text" class="line_total_usd"    placeholder="Total"  class="span2" ><label>Total</label></div>';
rowdata += '		</div>';
rowdata += '	</center></td>';
rowdata += '	<td>';
rowdata += '		<div class="addmore-section">';
rowdata += '			<button type="button"  value=""  class="btn btn-primary btnadd-product-line btnadd-row-item btnaddmoreoption" ><i class="fa fa-plus" ></i></button>';
rowdata += '			<button type="button"  value=""  class="btn btn-danger btnremove-product-line btnremove-row-item btnaddmoreoption"  style="display:none;"><i class="fa fa-minus" ></i></button>';
rowdata += '		</div>';
rowdata += '	</td>';
rowdata += '</tr>';
 
function allowOnlyNumber(evt,enterkey=''){
	try {
		var charCode = (evt.which) ? evt.which : event.keyCode;
		if (((charCode != 46 || $(this).val().indexOf('.') != -1) && (charCode < 48 || charCode > 57)&&charCode!=8) && (charCode != enterkey)) {
			errorMessage('','Only Numeric Input Is Allowed');
			return false;
		} 
		else{
			return true;
		}
	}
	catch(err){
	}
    
}

var substringMatcher = function(strs) {
    return function findMatches(q, cb) {
        var matches;
        matches = [];
        substrRegex = new RegExp(q, 'i');
        $.each(strs, function(i, str) {
            if (substrRegex.test(str)) {
                matches.push(str);
            }
        });
        cb(matches);
    };
};


function InvoiceTypeSet(country,party_type = null){
	if ( $("#hidden-invoice-country").is( "select" ) ) {
		if(country == "India")			
		{		
			if(Org_GSTIN_No == ""){
				$("#hidden-invoice-country").html('<option value="TaxInvoice">Regular</option>');	
			}
			else if(party_type == 'SEZ'){
				$("#hidden-invoice-country").html('<option value="SEZInvoice">Sez Invoice (With IGST)</option><option value="SEZInvoiceWithoutIGST">Sez Invoice (Without IGST)</option>');								
			}else{
				$("#hidden-invoice-country").html('<option value="TaxInvoice">Regular</option><option value="BillofSupply">Bill of Supply</option>');								
			}
			
			$(".currency-fields").hide();
			$("body").removeClass("multicurrency-doc");			
			$('#currency').val('INR - Indian Rupee');
			$('#currency').trigger("change");
			$('#invoice_prefix').val($('#invoice_prefix').attr('data-pre'));
			$('#invoice_postfix').val($('#invoice_postfix').attr('data-post'));
			$(".export-eway-fields").hide();
		}		
		else	
		{		
			$("#hidden-invoice-country").html('<option value="ExportInvoice">Export Invoice (With IGST)</option><option value="ExportInvoiceWithoutIGST">Export Invoice (Without IGST)</option>');						
			$(".currency-fields").show();
			$("body").addClass("multicurrency-doc");
			$('#currency').trigger("change");
			$('#invoice_prefix').val($('#invoice_prefix').attr('data-export-pre'));
			$('#invoice_postfix').val($('#invoice_postfix').attr('data-export-post'));
			$(".export-eway-fields").show();
		}
		var defaulttype = $("#hidden-invoice-country").attr("data-value");
		var setting_defaulttype = $("#hidden-invoice-country").attr("data-default-value");
		if(defaulttype != "")
		{
			$('#hidden-invoice-country option[value="'+defaulttype+'"]').attr("selected","selected");
		}
		else if(setting_defaulttype != "")
		{
			$('#hidden-invoice-country option[value="'+setting_defaulttype+'"]').attr("selected","selected");
		}
		$("#hidden-invoice-country").trigger("change");	
	}else{
		if(country == "India")			
		{		
			$(".currency-fields").hide();
			$("body").removeClass("multicurrency-doc");
			$('#currency').val('INR - Indian Rupee');
			$('#currency').trigger("change");
			$(".export-eway-fields").hide();
		}		
		else	
		{
			$(".currency-fields").show();
			$("body").addClass("multicurrency-doc");
			$('#currency').trigger("change");
			$(".export-eway-fields").show();
		}
	}
}

function getInvoiceNumber(){
	var data = {
        action: 'GetInvoiceNumber',
		invoice: 'invoice',
    }
	$.ajax({  
		type: "POST",  
		url: "ajaxcall.php",  
		ContentType : 'application/json',
		dataType: 'json',
		data: data,
		success: function(data){
			if(data.status=='OK')
			{
				$('[name="invoice_id"]').val(data.lastid);
			}
		},
		error: function(data){
		},
		complete: function(data){
		}
	});
	
}
function getCustomerOutstanding(){
	var data = {
        action: 'GetCustomerTotalOutstanding',
        id: $("#customers").val()
    }
	$.ajax({  
		type: "POST",  
		url: "ajaxcall.php",  
		ContentType : 'application/json',
		dataType: 'json',
		data: data,
		success: function(data){
			if(data.status=='OK')
			{
				$(".customer_outstanding_value").html(data.outstanding).removeClass("btn-link");;
			}
		},
		error: function(data){
		},
		complete: function(data){
		}
	});
}

var companyid = $("#customers").val(); 
var dispatchName = $("#dispatchName").val(); 
var shippingName = $("#shippingName").val(); 
var customerretail = 0;
function SetIGTS(){
	

	$(window).trigger("resize");
}


function checkValidPayment(){
	var paymentType = $("[name=payment]:checked").val() != undefined ? $("[name=payment]:checked").val() : $("[name=paymentid]:checked").val();
	if(paymentType != 'CREDIT' && paymentType != undefined)
	{
		var pr = $("#payment_received").val();
		var gt = $("#grand_total").val();
		if(pr == "")
		{
			errorMessage('','Enter Valid Payment Received Value');
			$("#payment_received").focus();
			return false;
		}
		if(pr != "" & isNaN(pr))
		{
			errorMessage('','Enter Valid Payment Received Value');
			return false;
		}
		else
		{
			if(parseInt(pr,10)>parseInt(gt,10))
			{
				errorMessage('','Payment Received Must Be Less Then OR Equal To Total Amount');
				return false;
			}	
		}
	}
	return true;
}
function SetPaymentModeDueDate(){
	if(companyid != $("#customers").val()){
		$('.disabledAutoEntry').hide();
	}else{
		$('.disabledAutoEntry').show();
	}
	var paymentType = $("[name=payment]:checked").val() != undefined ? $("[name=payment]:checked").val() : $("[name=paymentid]:checked").val();
	if(paymentType != 'CREDIT' && paymentType != undefined)
	{
		$("#datepicker_lr").attr("data-old-due",$("#datepicker_lr").val());
		$("#datepicker_lr").val("");
		$("#datepicker_lr").prop('disabled',true);
		if($("[name=payment]:checked").val() != undefined){
			$("#payment_received_row").slideDown();
			$("#payment_note").slideDown();
		}
		$('#adjustpayoff-0').click();
		$('[name="adjustpayoff"]').change();
		$('#autoentryoff-1').click();
		if(companyRemeningPayment.length == 0){
			$('.disabledAutoEntry').hide();
		}
		$('.adjustPayment').css('display','none');
	}
	else
	{
		$("#datepicker_lr").prop('disabled',false);
		if($("#datepicker_lr").attr("data-old-due") !="" && typeof $("#datepicker_lr").attr("data-old-due") !== typeof undefined && $("#datepicker_lr").attr("data-old-due") !== false){
			var ddd = $("#datepicker_lr").attr("data-old-due");
			$("#datepicker_lr").val(ddd);
		}
		$("#payment_received_row").slideUp();
		$("#payment_note").slideUp();
		if(companyid != $("#customers").val()){
			$('.disabledAutoEntry').hide();
			if(companyRemeningPayment.length != 0){
				$('[name="adjustpayoff"]').change();
				$('.adjustPayment').show();
			}
		}else{
			if(companyRemeningPayment.length == 0){
				$('.disabledAutoEntry').hide();
			}else{
				$('.disabledAutoEntry').slideDown();
			}
		}
		
		if($('.disabledAutoEntry:visible').length != 0)
		{
			$('.adjustPayment').hide();
		}
	}
}

$(document).ready(function(){
	UpdateCalculations();
	if(!($("[name=invoice_id][data-value!='']").length>0)){
		getInvoiceNumber();
	}
	refreshSn();

});

function refreshSn()
{
    var time = 600000;
    setTimeout(
        function ()
        {
			var data = {
				action: 'refreshSn'
			}
			$.ajax({
				url: 'ajaxcall.php',
				type: "POST",
				cache: false,
				global: false,
				ContentType : 'application/json',
				dataType: 'json',
				data: data,
				complete: function () {refreshSn();}
			});
		},
		time
	);
}

/*on change invoice_id data get	*/
$(document).on("change", "#invoice_id", function(){
	
	
	if(isNaN($(this).val())){
		errorMessage('','Only Numeric Value.');
		return false;
	}
	var data = {
        action: 'ValidateInvoiceID',
        id: parseInt($(this).val()),
        invoicetype: $("#hidden-invoice-country").val(),
		diffrent_invoice_series_for_export:$("#hidden-diffrent_invoice_series_for_export").val()

    }
	$.ajax({  
		type: "POST",  
		url: "ajaxcall.php",  
		ContentType : 'application/json',
		dataType: 'json',
		data: data,
		success: function(data){
			if(data.status=='USED')
			{	
				errorMessage('','Invoice No Is Already Exist!!');
				$("#invoice_id").val("");
				$("#invoice_id").focus();
			}
		},
		error: function(data){
		},
		complete: function(data){
		}
	});
});
/*on change invoice_id data get	*/

function parseDate(s) {
  var months = {jan:0,feb:1,mar:2,apr:3,may:4,jun:5,
                jul:6,aug:7,sep:8,oct:9,nov:10,dec:11};
  var p = s.split('-');
  return new Date(p[2], months[p[1].toLowerCase()], p[0]);
}

  $(document).ready(function(){

	var dateFormat = "dd-M-yy";
	function getDate( element ) {
      var date;
      try {
        date = $.datepicker.parseDate( dateFormat, element.value );
      } catch( error ) {
        date = null;
      }
       return date;
    }
	$( "#datepicker_bill" ).datepicker(
	{
		changeMonth: true,
		changeYear: true,
		dateFormat :'dd-M-yy'
	}).on( "change", function() {
		if($("#datepicker_lr").attr("data-value") == "")
		{
			var ddd = $("#datepicker_lr").attr("data-default-due");
			var newDate  = getDate(this);
			$("#datepicker_lr").datepicker(  "option","minDate", getDate(this));
			newDate.setDate(newDate.getDate() + parseInt(ddd,10));
			if(newDate != 'Invalid Date' && newDate != ""){
				$("#datepicker_lr").datepicker( "setDate", newDate);
			}
		}else{
			$("#datepicker_lr").datepicker(  "option","minDate", getDate(this));
		}
    });
	
	var datepicker_lr = $( "#datepicker_lr" ).datepicker(
	{
		changeMonth: true,
		changeYear: true,
		dateFormat :'dd-M-yy',
		minDate: "dateToday",
		
	});
	
	if($("#datepicker_lr").val()=="" && $("#datepicker_lr").attr("data-default-due") !=""){
		var ddd = $("#datepicker_lr").attr("data-default-due");
		$("#datepicker_lr").datepicker("setDate", "+"+ddd);
	}
	$(document).ready(function() {
		$("#product_invoice_form").submit(function(event) {
			if ($("#address").val().trim() == "") {
				event.preventDefault(); // Prevent the form from submitting
				
				Swal.fire({
					icon: 'warning',
					title: 'Please Add Address.',
					didClose: (e) => {
						$("#address").focus(); // Focus back on the textarea after alert is closed
					}
				});
			}
			if (!$('input[name="payment_type"]:checked').val()) {
				$('#paymentError').show(); // Show error message
				event.preventDefault(); // Prevent form submission
			} else {
				$('#paymentError').hide(); // Hide error message
			}
		});
		
	});
	
	document.addEventListener('DOMContentLoaded', function() {
		const form = document.getElementById('product_invoice_form');

		form.addEventListener('keypress', function(event) {
			if (event.key === 'Enter') {
				event.preventDefault(); // Prevent form submission
			}
		});
	});
	UpdateTrigger();
	$( "#product_invoice_form" ).validate({
		rules: {
			field: {required: true},
			ignore: ':hidden, [readonly=readonly],[readonly],[readonly=true]',
		},
		messages: {
			name: "Please enter Name",
			cmp_contactno: "Please enter Phone",
			address: "Please enter Address",
		},

	});
	
});	
function UpdateCalculations(){
	reverse_Charge = false;
	$(".reverse_charge_label_text").html("Total Tax ");
	var total_taxable_value = 0;
	var grand_total_value = 0;
	var total_tax_value = 0;
	var row_total_qty = 0;
	var row_total_mrp = 0;
	var row_total_price = 0;
	var row_total_disc = 0;
	var row_total_cgst_rate = 0;
	var row_total_cgst_sgst_rate = 0;
	var row_total_sgst_rate = 0;
	var row_total_igst_rate = 0;
	var row_total_cess_rate = 0;
	var row_total_total=0;
	var rowcounter = 0;
	var rowlength = $("#document-item-list-table .product-item-row").length;
	var gd_total_taxable_value = 0;
	var gd_discount_in_rupee = 0;
	var gd_discount_in_percentage = 0;
	var gd_total_discount = 0;
	$("#document-item-list-table .product-item-row").each(function(){
		rowcounter = rowcounter + 1;
		var quantity = $(this).find(".quantity").val();
		var is_transport = $(this).find(".hidden-item-product-is_transport").val();
		var mrp = $(this).find(".mrp").val();
		var rate = $(this).find(".rate").val();
		var disc = $(this).find(".disc").val();
		var taxable_line_value = $(this).find(".taxable_line_value").val();
		var cgst = "";
		var sgst = "";
		var igst = "";
		var cess = $(this).find(".cess").val();
		var cessrate = "";
		var cess_amount = $(this).find(".cess_amount").val();
		var cgstrate = "";
		var sgstrate = "";
		var igstrate = "";
		if($(this).find(".cgst:not([readonly])").length > 0 ) { cgst = $(this).find(".cgst:not([readonly])").val(); }
		if($(this).find(".sgst:not([readonly])").length > 0 ) { sgst = $(this).find(".sgst:not([readonly])").val(); }
		if($(this).find(".igst:not([readonly])").length > 0 ) { igst = $(this).find(".igst:not([readonly])").val(); }
		/* if(igst != "" && (igst.charAt(igst.length - 1) != ".") ){
			igst  = igst.toString().substring(0, igst.toString().indexOf('.') + 1 + gst_rate_decimal_rounding_by);
			$(this).find(".igst:not([readonly])").val(igst);
			igst = parseFloat(igst);
		} */
		var row_total = 0;
		var gd_row_total = 0;
		var gd_discount_line_value = 0;
		if( quantity != "" && rate != "" )
		{
			if((quantity.charAt(quantity.length - 1) != "." && quantity.toString().indexOf('.') > -1  ))
			{
				quantity  = quantity.toString().substring(0, quantity.toString().indexOf('.') + 1 );
				$(this).find(".quantity").val(quantity);
				quantity = parseFloat(quantity);
			}
			else if(quantity.toString().indexOf('.') > -1  )
			{
				quantity  = quantity.toString().substring(0, quantity.toString().indexOf('.') + 1 );
				quantity = parseFloat(quantity);
				$(this).find(".quantity").val(quantity);
			}
			else{
				quantity = parseFloat(quantity);
			}
			if((rate.charAt(rate.length - 1) != "." && price_decimal_rounding_by > 0 && rate.toString().indexOf('.') > -1  ) )
			{				
				rate  = rate.toString().substring(0, rate.toString().indexOf('.') + 1 );
				/* rate = parseFloat(rate);
				rate = rate.toFixed(price_decimal_rounding_by);				 */
				$(this).find(".rate").val(rate);
				rate = parseFloat(rate);
				
			}
			else if( price_decimal_rounding_by == 0 && rate.toString().indexOf('.') > -1  )
			{
				rate  = rate.toString().substring(0, rate.toString().indexOf('.') + 1 );
				/* rate = parseFloat(rate);
				rate = rate.toFixed(); */
				$(this).find(".rate").val(rate);
			}
			else{
				rate = parseFloat(rate);
			}
			if(disc !="")
			{
				disc = parseFloat(disc);
			}
			else
			{
				disc = 0;
			}
			if(!(is_transport == 1 || is_transport == "1")){
				row_total_qty += quantity;
			}

			if(mrp != "" && !isNaN(mrp)){
				if((mrp.charAt(mrp.length - 1) != "." && price_decimal_rounding_by > 0 && mrp.toString().indexOf('.') > -1  ) )
				{				
					mrp  = mrp.toString().substring(0, mrp.toString().indexOf('.') + 1 );
				}
				$(this).find(".mrp").val(mrp);
				mrp = parseFloat(mrp);
			}else{
				mrp = 0;
			}
			
			row_total_mrp += mrp;
			row_total_price += rate;
			row_total =  quantity*rate;
			
			var DiskRs = 0;
			var discount_in = 'rupee';
			var discount_per_item = 1;
			if(disc>0 && discount_in=='rupee' && discount_per_item == 1)
			{
				DiskRs = disc;
			}
			row_total_disc += DiskRs;
			row_total = row_total-DiskRs;
			row_total = Math.round(row_total * taxable_decimal_rounding) / taxable_decimal_rounding;
			
			
			$(this).find(".taxable_line_value").val(row_total);
			
			total_taxable_value += row_total;
			$(this).find(".gd_taxable_line_value").val(gd_row_total);
			$(this).find(".gd_discount_line_value").val(gd_discount_line_value);
			gd_total_taxable_value += row_total;
			$(this).find(".gd_taxable_line_value").val(row_total);
			if(cgst != "" ){
				cgst = parseFloat(cgst);
				if(cgst>0){
					cgstrate = (row_total*cgst)/100;
					cgstrate = Math.round(cgstrate * gst_decimal_rounding) / gst_decimal_rounding;
				}
			}
			if(sgst != "" ){
				sgst = parseFloat(sgst);
				if(sgst>0){
					sgstrate = (row_total*sgst)/100;
					sgstrate = Math.round(sgstrate * gst_decimal_rounding) / gst_decimal_rounding;
				}
			}
			if(igst != "" ){
				igst = parseFloat(igst);
				if(igst>0){
					igstrate = (row_total*igst)/100;
					igstrate = Math.round(igstrate * gst_decimal_rounding) / gst_decimal_rounding;
				}
			}
			if(cess != "" ){
				cess = parseFloat(cess);
				if(cess>0){
					cessrate = (row_total*cess)/100;
					cessrate = Math.round(cessrate * gst_decimal_rounding) / gst_decimal_rounding;
				}
			}
			
			if(cgstrate !=""){
				$(this).find(".cgst_rate").val(cgstrate);
				$(this).find(".cgst_sgst_rate").val(cgstrate+sgstrate);
				if(!reverse_Charge){
					row_total = row_total+cgstrate;
				}
				total_tax_value+=cgstrate;
				row_total_cgst_rate += cgstrate;				
			}
			else{
				$(this).find(".cgst_rate").val("0");
				$(this).find(".cgst_sgst_rate").val("0");
			}
			if(sgstrate !=""){
				$(this).find(".sgst_rate").val(sgstrate);
				if(!reverse_Charge){
					row_total = row_total+sgstrate;
				}
				total_tax_value+=sgstrate;
				row_total_sgst_rate += sgstrate;
			}
			else{
				$(this).find(".sgst_rate").val("0");
			}
			if(igstrate !=""){
				$(this).find(".igst_rate").val(igstrate);
				if(!reverse_Charge){
					row_total = row_total+igstrate;
				}
				total_tax_value+=igstrate;
				row_total_igst_rate += igstrate;
			}
			else{
				$(this).find(".igst_rate").val("0");
			}
			if(cessrate !=""){
				$(this).find(".cessrate").val(cessrate);
				if(!reverse_Charge){
					row_total = row_total+cessrate;
				}
				total_tax_value+=cessrate;
				row_total_cess_rate += cessrate;
			}
			else{
				$(this).find(".cessrate").val("0");
			}
			if(cess_amount != "" ){
				cess_amount = parseFloat(cess_amount);
				if(cess_amount>0){
					cess_amount = Math.round(cess_amount * gst_decimal_rounding) / gst_decimal_rounding;
					
					$(this).find(".cess_amount").val(cess_amount);
					if(!reverse_Charge){
						row_total = row_total+cess_amount;
					}
					total_tax_value+=cess_amount;
					row_total_cess_rate += cess_amount;
				
				}
			}
			row_total = Math.round(row_total * taxable_decimal_rounding) / taxable_decimal_rounding;

			grand_total_value += row_total;
			row_total_total += row_total;
			$(this).find(".line_total").val(row_total);
			if(rowcounter == rowlength ) {
				addNewRowItem($(".btnadd-row-item:last"));
			}
		}
	});
	
	row_total_qty = Math.round(row_total_qty * quantity_decimal_rounding) / quantity_decimal_rounding;
	row_total_mrp = Math.round(row_total_mrp * price_decimal_rounding) / price_decimal_rounding;
	row_total_price = Math.round(row_total_price * price_decimal_rounding) / price_decimal_rounding;
	row_total_disc = Math.round(row_total_disc * 100) / 100;
	row_total_cgst_rate = Math.round(row_total_cgst_rate * gst_decimal_rounding) / gst_decimal_rounding;	
	row_total_sgst_rate = Math.round(row_total_sgst_rate * gst_decimal_rounding) / gst_decimal_rounding;
	row_total_igst_rate = Math.round(row_total_igst_rate * gst_decimal_rounding) / gst_decimal_rounding;
	row_total_cess_rate = Math.round(row_total_cess_rate * gst_decimal_rounding) / gst_decimal_rounding;
	row_total_total=Math.round(row_total_total * 100) / 100;
	total_taxable_value = Math.round(total_taxable_value * taxable_decimal_rounding) / taxable_decimal_rounding;
	gd_total_taxable_value = Math.round(gd_total_taxable_value * taxable_decimal_rounding) / taxable_decimal_rounding;
	grand_total_value = Math.round(grand_total_value * 100) / 100;
	total_tax_value = Math.round(total_tax_value * gst_decimal_rounding) / gst_decimal_rounding;
	var cus_pay_grandtotal = grand_total_value;
	cus_pay_grandtotal = Math.round(cus_pay_grandtotal * 100) / 100;
	$("#cus_pay_grandtotal").val(cus_pay_grandtotal);	
	$("#cus_pay_grandtotal_lable").html(cus_pay_grandtotal);

	var rupeesymbol = "₹";
	
	$("#row_total_qty_lable").html(row_total_qty);
	$("#row_total_qty").val(row_total_qty);
	$("#row_total_mrp_lable").html(number_format_indian(row_total_mrp));
	$("#row_total_mrp").val(row_total_mrp);
	$("#row_total_price_lable").html(number_format_indian(row_total_price));
	$("#row_total_price").val(row_total_price);
	$("#row_total_disc_lable").html(number_format_indian(row_total_disc));
	$("#row_total_disc").val(row_total_disc);
	$("#row_total_cgst_rate_lable").html(number_format_indian(row_total_cgst_rate+row_total_sgst_rate));
	$("#row_total_cgst_rate").val(row_total_cgst_rate);
	//$("#row_total_sgst_rate_lable").html(row_total_sgst_rate);
	$("#row_total_sgst_rate").val(row_total_sgst_rate);
	$("#row_total_igst_rate_lable").html(number_format_indian(row_total_igst_rate));
	$("#row_total_igst_rate").val(row_total_igst_rate);
	$("#row_total_cess_rate_lable").html(number_format_indian(row_total_cess_rate));
	$("#row_total_cess_rate").val(row_total_cess_rate);
	$("#row_total_total_lable").html(number_format_indian(row_total_total));
	$("#row_total_total").val(row_total_total);
	
	$("#total_taxable_lable").html(number_format_indian(total_taxable_value));
	$("#total_taxable").val(total_taxable_value);
	$("#gd_total_taxable").val(gd_total_taxable_value);
	$("#total_tax_lable").html(number_format_indian(total_tax_value));
	$("#total_tax").val(total_tax_value);
	$("#grand_total_lable").html(number_format_indian(grand_total_value));
	$("#grand_total").val(grand_total_value);
	$("#grandtotalwords").text(number2text(cus_pay_grandtotal));
	$("#hidden_grandtotalwords").val(number2text(grand_total_value));
	$("#hidden_cust_pay_grandtotalwords").val(number2text(cus_pay_grandtotal));
	$("#payment_received").val(grand_total_value);
	
	


	if($("#currency").val()=="INR - Indian Rupee")
	{
		
		$("#currency_grandtotal_lable").html("");
		$("#currency_grandtotal").val("");
		
		$("#currency_cus_pay_grandtotal_lable").html("");
		$("#currency_cus_pay_grandtotal").val("");
		
		$("#currency_total_in_words").html("");
		$("#hidden_currency_total_in_words").val("");
		$("#hidden_currency_cus_pay_total_in_words").val("");
		
	}
	else
	{
		var currency_rate = $("#currency_rate").val();
		if(currency_rate == "")
		{
			currency_rate = 1;
		}
		else{
			currency_rate = parseFloat(currency_rate);
		
		}
		$("#grand_total_lable").html(rupeesymbol+" "+number_format_indian(grand_total_value));
		$("#cus_pay_grandtotal_lable").html(rupeesymbol+" "+cus_pay_grandtotal);
		
	}
	$('.inputlabel_wrapper input').each(function(){
		var inputlabel_wrapper = $(this).closest('.inputlabel_wrapper');
		if( !jQuery(this).val() ) {
			inputlabel_wrapper.removeClass('active_inputlabel');
		}else{
			inputlabel_wrapper.addClass('active_inputlabel');
		}
	});
	$('.inputlabel_wrapper textarea').each(function(){
		var inputlabel_wrapper = $(this).closest('.inputlabel_wrapper');
		if( !jQuery(this).val() ) {
			inputlabel_wrapper.removeClass('active_inputlabel');
		}else{
			inputlabel_wrapper.addClass('active_inputlabel');
		}
	});
	AmountAdjustment();
	grand_total_lable_change();
	if(grand_total_value > 50000){
		$('.smart_suggestion').show();
	}else{
		$('.smart_suggestion').hide();
	}
	
}
function UpdateTrigger(){
	/*
	if($( ".invoice-product-items-table tbody" ).hasClass("ui-sortable"))
	{
		$( ".invoice-product-items-table tbody" ).sortable( "refresh" );	
	}
	*/
	$("#document-item-list-table .quantity, #document-item-list-table .rate, #document-item-list-table .mrp, #document-item-list-table .disc, #document-item-list-table .cgst, #document-item-list-table .sgst, #document-item-list-table .igst, #document-item-list-table .cess, #document-item-list-table .cess_amount,#total_discount_value,[name=total_discount_in_rupee],[name=total_discount_type_minus],#other_tax_value,[name=other_tax_in_rupee],[name=other_tax_type_minus],#currency_rate,#general_disc_value,[name=general_disc_in_rupee]").off('keyup change blur').on('keyup change blur',function(){
		UpdateCalculations();
	});			
	$("#document-item-list-table .line_total").off('blur').on('blur',function(){	
	var ParentRowOfCurTotal = $(this).parents(".product-item-row");	
	var quantity = $(ParentRowOfCurTotal).find(".quantity").val();	
	var line_total = $(ParentRowOfCurTotal).find(".line_total").val();	
	var rate = $(ParentRowOfCurTotal).find(".rate").val();	
	var disc = $(ParentRowOfCurTotal).find(".disc").val();	
	console.log(disc);
	var taxable_line_value = $(ParentRowOfCurTotal).find(".taxable_line_value").val();	
	var cgst = "";	
	var sgst = "";	
	var igst = "";	
	var cess = "";	
	var cess_amount = "";	
	var igstrate = "";	
	var gd_discount_in_percentage = 0;
	var general_disc_value = 0;
	if($(ParentRowOfCurTotal).find(".cgst:not([readonly])").length > 0 ) { 
		cgst = $(ParentRowOfCurTotal).find(".cgst:not([readonly])").val(); 
	}		
	if($(ParentRowOfCurTotal).find(".sgst:not([readonly])").length > 0 ) { 
		sgst = $(ParentRowOfCurTotal).find(".sgst:not([readonly])").val();
	}		
	if($(ParentRowOfCurTotal).find(".igst:not([readonly])").length > 0 ) {
		igst = $(ParentRowOfCurTotal).find(".igst:not([readonly])").val();
	}
	if(igst != "" && (igst.charAt(igst.length - 1) != ".") ){
		igst = parseFloat(igst);
		igst = Math.round(igst * gst_rate_decimal_rounding) / gst_rate_decimal_rounding;
		$(ParentRowOfCurTotal).find(".igst:not([readonly])").val(igst);
	}
	var totaltoqty = $('#total_to_qty').val();
	if( quantity != "" && line_total != "")		{
		quantity = parseFloat(quantity);		
		if(quantity == 0)		
		{		
			quantity = 1;	
			$(ParentRowOfCurTotal).find(".quantity").val("1");	
		}		
		line_total = parseFloat(line_total);
		var totalTax = 0;	
		if(cgst != "" ){	
			cgst = parseFloat(cgst);	
			if(cgst>0){		
				totalTax += cgst;		
			}		
		}		
		if(sgst != "" ){
			sgst = parseFloat(sgst);
			if(sgst>0){			
				totalTax += sgst;		
			}		
		}		
		if(igst != "" ){	
			igst = parseFloat(igst);	
			if(igst>0){		
				totalTax += igst;	
			}		
		}
		if(cess != "" ){	
			cess = parseFloat(cess);	
			if(cess>0){		
				totalTax += cess;	
			}		
		}
		
		if(cess_amount != "" ){	
			cess_amount = parseFloat(cess_amount);	
			if(cess_amount>0){		
				line_total -= cess_amount;
			}		
		}
		

		var taxablevalue = 0;	
		if(totalTax > 0)
		{		
			taxablevalue = (line_total*100)/(100+totalTax);	
		}		
		else	
		{		
			taxablevalue = line_total;	
		}				
		if(disc !="")		
		{			
			disc = parseFloat(disc);	
		}		
		else	
		{	
			disc = 0;	
		}		
		var DiskRs = 0;		

		if(disc>0 && discount_in=='percentage')		
		{		
			taxablevalue = (taxablevalue*100)/(100-disc);	
		}					

		if(disc>0 && discount_in=='rupee' && discount_per_item == 0)	
		{		
			taxablevalue = taxablevalue+disc;		
		}		
		if(disc>0 && discount_in=='rupee' && discount_per_item == 1)	
		{		
			taxablevalue = (taxablevalue+(disc*quantity));		
		}		
		taxablevalue += DiskRs;		
		if(totaltoqty == 1){
			rate = (rate != '')?rate:1;
			quantity = taxablevalue / rate;	
			$(ParentRowOfCurTotal).find(".quantity").val(Math.round(quantity * quantity_decimal_rounding) / quantity_decimal_rounding);	
			$(ParentRowOfCurTotal).find(".rate").val(Math.round(rate * price_decimal_rounding) / price_decimal_rounding);	
		}else{
			rate = taxablevalue / quantity;		
			$(ParentRowOfCurTotal).find(".rate").val(Math.round(rate * price_decimal_rounding) / price_decimal_rounding);	
		}

	}		
	UpdateCalculations();	
	
});
		
	$(".btnadd-row-item").off("click").on("click", function(){
			addNewRowItem($(this));
	});
	$( ".btnremove-row-item").off("click").on("click", function(){
		RemoveRowItem($(this));
	});
	SetIGTS();
	var prevbarcode = "";
	$( ".product-combobox" ).autocomplete({
		/*source: availableProducts,*/
		source: function (request, response) {
			var results = $.grep(availableProducts, function (item) {return (item.label.toLowerCase().includes(request.term.toLowerCase())||item.hsn.toLowerCase().includes(request.term.toLowerCase())||item.barcode_no.toLowerCase().includes(request.term.toLowerCase()));});response(results.slice(0, 500));
		},
		change: function(event, ui) {
			if (ui.item == null || ui.item == undefined) {
				var $targetparnt = $( event.target ).parents(".product-item-row");
				if(!productAllowed)
				{
					$( event.target ).val("");
					Swal.fire({
					  icon: 'warning',
					  title: 'Error in Adding Product',
					  text: "You do not have Access to add new Product."
					});		
					
				}
				$( event.target ).attr("data-oldval", "");
				$($targetparnt).find(".quantity").attr('readonly',false);
				$($targetparnt).find(".hsccode").attr('readonly',false);
				$($targetparnt).find(".hidden-item-product-name").val($( event.target ).val());
				
				$($targetparnt).find(".hidden-item-product-uom").val("");
				$($targetparnt).find(".uom").attr('disabled',false);
				$($targetparnt).find(".uom").val("");
				$($targetparnt).find(".hidden-item-product-id").val("");
				$($targetparnt).find(".hidden-item-product-is_transport").val("");
				$($targetparnt).find(".hidden-item-product-cgst").val("");
				$($targetparnt).find(".hidden-item-product-sgst").val("");
				$($targetparnt).find(".hidden-item-product-igst").val("");
				
				
				$($targetparnt).find(".product-quantity-available").html("");
				$($targetparnt).find(".quantity").removeAttr("qtymax");
				
				$($targetparnt).find(".hidden-item-product-att1-name").val("");
				$($targetparnt).find(".hidden-item-product-att1-val").val("");
				$($targetparnt).find(".hidden-item-product-att2-name").val("");
				$($targetparnt).find(".hidden-item-product-att2-val").val("");
				$($targetparnt).find(".hidden-item-product-att3-name").val("");
				$($targetparnt).find(".hidden-item-product-att3-val").val("");
				$($targetparnt).find(".hidden-item-product-att4-name").val("");
				$($targetparnt).find(".hidden-item-product-att4-val").val("");

				$($targetparnt).find(".hidden-item-product-stockType").val("");
				$($targetparnt).find( ".hidden-item-product-batch-id" ).val("");			
				$($targetparnt).find( ".hidden-item-product-batch-name" ).val("");			
				$($targetparnt).find( ".hidden-item-manufacture-date" ).val("");			
				$($targetparnt).find( ".hidden-item-expiry-date" ).val("");
				$($targetparnt).find( ".batch_name" ).val("");			
				$($targetparnt).find( ".model_no" ).val("");			
				$($targetparnt).find( ".size" ).val("");			
				
				$($targetparnt).find(".batch_name").attr('readonly',true);
				$($targetparnt).find(".model_no").attr('readonly',true);
				$($targetparnt).find(".size").attr('readonly',true);
				$($targetparnt).find(".manufacture_date").attr('readonly',false);
				$($targetparnt).find(".expiry_date").attr('readonly',false);
				$($targetparnt).find('.manufacture_date').val("").prop('disabled',true);
				$($targetparnt).find('.expiry_date').val("").prop('disabled',true);
				$($targetparnt).find('.hidden-batch-mfg').val("").prop('disabled',false);
				$($targetparnt).find('.hidden-batch-expiry').val("").prop('disabled',false);
				
			}
			else{
				if (ui.item) {
					$( event.target ).attr("data-oldval", ui.item.label );
					var $targetparnt = $( event.target ).parents(".product-item-row");
					$( event.target ).val( ui.item.label );
					console.log(ui.item.value);
					$($targetparnt).find( ".hidden-item-product-id" ).val( ui.item.value);
					$($targetparnt).find( ".hidden-item-product-name" ).val( ui.item.name);
					$($targetparnt).find( ".hidden-item-product-uom" ).val( ui.item.uom);
					$($targetparnt).find( ".uom" ).val( ui.item.uom);
					$($targetparnt).find(".uom").attr('disabled',true);
					$($targetparnt).find(".quantity").attr('readonly',false);
					if(ui.item.is_transport == 1 || ui.item.is_transport == '1')
					{
						$($targetparnt).find(".quantity").val("1");
						$($targetparnt).find(".quantity").attr('readonly',true);
					}
					else if(ui.item.is_service_product == 1 || ui.item.is_service_product == '1')
					{
						
					}
					else
					{
						if(inventory_enable){
							if(ui.item.non_salable_product != 1 || ui.item.non_salable_product != '1'){
								$($targetparnt).find(".product-quantity-available").html(ui.item.items_available);
							}
							if((ui.item.non_salable_product != 1 || ui.item.non_salable_product != '1')){
								$($targetparnt).find(".quantity").attr("qtymax",ui.item.items_available);
							}
						}
					}
					$($targetparnt).find( ".hidden-item-product-igst" ).val( ui.item.igst).change();
					
				
					
					$($targetparnt).find(".hsccode").val(ui.item.hsn);
					var mrp = ui.item.mrp;
					if(mrp != '' && mrp != null){
						mrp  = mrp.toString().substring(0, mrp.toString().indexOf('.') + 1 );
						mrp = parseFloat(mrp).toFixed(price_decimal_rounding_by);
					}
					$($targetparnt).find(".rate").val(ui.item.sellprice);
					$($targetparnt).find(".customer_rate_label").html('');
					if($($targetparnt).find(".quantity").val() == "")
						{
							$($targetparnt).find(".quantity").val("1");
						}						
					UpdateCalculations();					

				}
			}
		},
		select: function( event, ui ) {
			console.log(11111);
			var $targetparnt = $( event.target ).parents(".product-item-row");
			$(event.target).attr("data-oldval", ui.item.label);
			$(event.target).val(ui.item.label);
			console.log(ui.item.value);
			$($targetparnt).find( ".hidden-item-product-id" ).val( ui.item.value);
			$($targetparnt).find( ".hidden-item-product-name" ).val( ui.item.name);
			$($targetparnt).find(".quantity").attr('readonly',false);
			if(ui.item.is_transport == 1 || ui.item.is_transport == '1')
			{
				$($targetparnt).find(".quantity").val("1");
				$($targetparnt).find(".quantity").attr('readonly',true);
			}
			else if(ui.item.is_service_product == 1 || ui.item.is_service_product == '1')
			{
				
			}
			else
			{
				if(inventory_enable){
					if(ui.item.non_salable_product != 1 || ui.item.non_salable_product != '1'){
						$($targetparnt).find(".product-quantity-available").html(ui.item.items_available);
					}
				}
			}
			
			
			
			$($targetparnt).find( ".hidden-item-product-igst" ).val( ui.item.igst).change();
			
			
			
			
			$($targetparnt).find(".hsccode").val(ui.item.hsn);
			var mrp = ui.item.mrp;
			if(mrp != '' && mrp != null){
				mrp  = mrp.toString().substring(0, mrp.toString().indexOf('.') + 1 );
				mrp = parseFloat(mrp).toFixed(price_decimal_rounding_by);
			}
			$($targetparnt).find(".mrp").val(mrp);
			$($targetparnt).find(".rate").val(ui.item.sellprice);
			if($($targetparnt).find(".quantity").val() == "")	
			{		
				$($targetparnt).find(".quantity").val("1");	
			}
			UpdateCalculations();
			return false;
		}, minLength:0
	}).focus(function(){            
            $(this).autocomplete("search");
    
}).scannerDetection({
	timeBeforeScanTest: 500,
	avgTimeByChar: 100,
	onComplete: function () {
		var target = $(this);
		target.autocomplete("search", "");
		var requestBarcode = target.val();			
		var matchingProducts = availableProducts.filter(function(product) {
			return product.barcode_no === requestBarcode;
		});			
		if (matchingProducts.length == 1) {
			var productName = matchingProducts[0].label;
			var checkprev = $('.product-item-row').filter(function(product,val) {
				return $(this).find('.hidden-item-product-name').val() === productName;
			});
			if(matchingProducts[0].stockType == 2)
			{
				target.autocomplete("widget").find(`li:contains("${productName}")`).addClass("ui-state-active").trigger("click");
				setTimeout(function() {target.closest("tr").find(".has-serialno").find("input.tt-input").focus();}, 50);
			}else{
				if(checkprev.length >= 1) {
					addcount = parseFloat(checkprev.eq(checkprev.length - 1).find('.quantity').val()) + 1;
					checkprev.eq(checkprev.length - 1).find('.quantity').val(addcount);
					target.val("");
				}else{
					target.autocomplete("widget").find(`li:contains("${productName}")`).addClass("ui-state-active").trigger("click");
					$(".product-combobox:last").focus();
				}
			}
		}else if(matchingProducts.length > 1){}else{ 
				Swal.fire({
				icon: 'warning',
				title: 'Scanned Product Not Found.',
				didClose: function (e) {
					$(this).find(".product-combobox").focus();
					target.val("");
				}
			});
		}
		
		$(this).autocomplete("close");
		 
}
});
	var rowcounter = 1;
	var rowlength = $("#document-item-list-table .product-item-row").length;
	$('.show-batch-column').hide();
	$("#document-item-list-table .product-item-row").each(function(){
		$(this).find(".product-item-row-number").html(rowcounter);
		$(this).find(".serialno").attr('id','serialno-'+rowcounter);
		
		if(rowlength == rowcounter)
		{
			$(this).find(".btnadd-row-item").show();
			$(this).find(".btnremove-row-item").hide();
		}
		else
		{
			$(this).find(".btnremove-row-item").show();
		}

		rowcounter += 1;

		$(this).find(".has-serialno").hide();
		$(this).find(".serialno").show();
		$(this).find(".bootstrap-tagsinput").hide();
		var stockType = $(this).find(".hidden-item-product-stockType").val();
		if(stockType == 1){
			showHideBatchColumn();
		}else if(stockType == 2 && inventory_enable){
			$(this).find(".has-serialno").show();
			$(this).find(".serialno").hide();
			$(this).find(".bootstrap-tagsinput").show();
			$(this).find(".bootstrap-tagsinput input").attr('maxlength','30');
		}
		
	});
	
}

function addNewRowItem(ele){
	$(ele).parents('tr.product-item-row').after(rowdata);
	UpdateTrigger();
}
function validateCurrentRowsForSubmit(){
	var isValid = true;
	if($("#document-item-list-table .product-item-row").length>1)
	{
		var wrongHSN = [];
		var RowCounter = 1;
		var totalRowsData = $("#document-item-list-table .product-item-row").length;
		$("#document-item-list-table .product-item-row").each(function(){
			var quantity = $(this).find(".quantity").val();
			var hsccode = $(this).find(".hsccode").val();
			var rate = $(this).find(".rate").val();
			var mrp = $(this).find(".mrp").val();
			var product_note = $(this).find(".product_note").val();
			var product = $(this).find(".hidden-item-product-name").val();
			var stockType = $(this).find(".hidden-item-product-stockType").val();
			var serialno = $(this).find(".serialno").val();
			var batch_name = $(this).find(".batch_name").val();
			var batchname = $(this).find(".hidden-item-product-batch-name").val();
			batchname = (batch_name == batchname)?batchname:'';
			if(totalRowsData == RowCounter &&  product == "" &&  rate == "" &&  quantity == "")
			{
			
			}
			else
			{
				if( product == "")
				{
					Swal.fire({
						icon: 'warning',
						title: 'Please Select Product',
						didClose: (e) => {
							$(this).find(".product-combobox").focus();
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
							$(this).find(".mrp").focus();
						}
					});
					isValid = false;
					return false;
				}
				if( hsccode == "")
				{
					Swal.fire({
						icon: 'warning',
						title:'Please Enter HSN/SAC Code',
						didClose: (e) => {
							$(this).find(".hsccode").focus();
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
							$(this).find(".quantity").focus();
						}
					});
					isValid = false;
					return false;
				}
				if(  rate == "" )
				{
					Swal.fire({
						icon: 'warning',
						title:'Please Enter Price',
						didClose: (e) => {
							$(this).find(".rate").focus();
						}
					});
					isValid = false;
					return false;
				}
			}
			
			RowCounter++;
		});
		if (wrongHSN.length > 0) {
			var errorMessage = '';
			wrongHSN.forEach(function (item, index) {
				var parts = item.split("---");
				errorMessage += parts[0] + ' in SR. ' + parts[1];
				if (index < wrongHSN.length - 1) {
					errorMessage += ', <br>';
				}
			});
			errorMessage += ".";
			Swal.fire({
				icon: 'warning',
				title: "Invalid HSN/SAC Code:",
				html: errorMessage,
				didClose: (e) => {
					var firstInvalidHSNRow = wrongHSN[0].split("---")[1];
					$("#document-item-list .product-item-row").eq(firstInvalidHSNRow - 1).find(".hsccode").focus();
				}
			});
		
			isValid = false;
			return false;
		}
	}
	else
	{
		$("#document-item-list-table .product-item-row").each(function(){
			var quantity = $(this).find(".quantity").val();
			var hsccode = $(this).find(".hsccode").val();
			var mrp = $(this).find(".mrp").val();
			var rate = $(this).find(".rate").val();
			var product_note = $(this).find(".product_note").val();
			var product = $(this).find(".hidden-item-product-name").val();
			var stockType = $(this).find(".hidden-item-product-stockType").val();
			var serialno = $(this).find(".serialno").val();
			var batch_name = $(this).find(".batch_name").val();
			var batchname = $(this).find(".hidden-item-product-batch-name").val();
			batchname = (batch_name == batchname)?batchname:'';
			if( product == "")
			{
				Swal.fire({
					icon: 'warning',
					title: 'Please Select Product',
					didClose: (e) => {
						$(this).find(".product-combobox").focus();
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
						$(this).find(".mrp").focus();
					}
				});
				isValid = false;
				return false;
			}
			if( hsccode == "" && enable_einvoice_options == 1)
			{
				Swal.fire({
					icon: 'warning',
					title:'Please Enter HSN/SAC Code',
					didClose: (e) => {
						$(this).find(".hsccode").focus();
					}
				});
				isValid = false;
				return false;
			}
		
			if(hsccode != "" && jQuery.inArray(hsccode, hsnCodes) === -1) 
			{
				Swal.fire({
					icon: 'warning',
					title: 'Invalid HSN-SAC code : '+hsccode,
					didClose: (e) => {
						$(this).find(".hsccode").focus();
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
						$(this).find(".quantity").focus();
					}
				});
				isValid = false;
				return false;
			}
			if(  rate == "" )
			{
				Swal.fire({
					icon: 'warning',
					title:'Please Enter Price',
					didClose: (e) => {
						$(this).find(".rate").focus();
					}
				});
				isValid = false;
				return false;
			}
		

			if(  product_note != "" && product_note.length > 1200 )
			{
				Swal.fire({
					icon: 'warning',
					title:'Product description must be less than 1200 character.',
					didClose: (e) => {
						$(this).find(".product_note").focus();
					}
				});
				isValid = false;
				return false;
			}
		});
	}
	var grand_total = parseFloat($("#grand_total").val());
	if(grand_total > 9999999999){
		Swal.fire({
			icon: 'warning',
			title:'Grandtotal must be less than 9999999999!.',
		});
		isValid = false;
		return false;
	}
	
	return isValid;
}
$(document).on("change", "#reverse_Charge", function(){
	SetIGTS();
	UpdateCalculations();
});
$(document).on("change", "[name=payment]", function(){
	SetPaymentModeDueDate();
});

function RemoveRowItem(row){
	$(row).parents(".product-item-row").remove();
	UpdateTrigger();
	UpdateCalculations();
}

$('#SameShippingAdd').change(function() {
    if($(this).is(":checked")) {
        $("#shipping_detail_drawer").removeClass("open_drwawer");
    }
	else
	{
		$("#shipping_detail_drawer").addClass("open_drwawer");
	}
	$(window).trigger("resize");
	getDispatchDistance();
});

$(".sez-box").hide();

/*
  $( function() {
    $( ".invoice-product-items-table tbody" ).sortable({
		placeholder: "ui-state-highlight",
		handle: ".product-item-row-number",
		update:function(event, ui){
			UpdateTrigger();
		}
	});
	$( ".invoice-product-items-table tbody" ).disableSelection();
  } );
*/

/* Validate single quantity field */
function qty_validate_item(item){
	var isValid = true;
	
	if (typeof item.attr('qtymax') !== 'undefined' && item.attr('qtymax') !== false) {
		if(parseInt(item.val()) != 0  && parseInt(item.val()) > parseInt(item.attr('qtymax'))){
			item.next().remove(".qerror");
			item.after("<span class='qerror'>Not enough stock!</span>");
			isValid = false;		
		}else{
			item.next().remove(".qerror");		
			isValid = true;
		}
	}	
	
	return isValid;
}

/* Validate all quantity fields on submit */
function qty_validate_items(){
	var isValid = true;
	
	$("input[name='quantity[]']").each(function() {
		qty_validate_item($(this));
		if(qty_validate_item($(this)) == false){
			isValid = false;			
		}
	});
	return isValid;
}

/* Quantity validation on quantity change */
$(document).on('keyup change', "input[name='quantity[]']",function () {
	qty_validate_item($(this));	
});

/* Switchers */
$('input[type=radio][name=disc-type]').change(function() {
    if (this.value == '1') {
		discount_in = 'rupee';		
	}
    else if (this.value == '0') {
		discount_in = 'percentage';
    }
	$('#discount_in').val(discount_in);
	UpdateCalculations();
});
$('input[type=radio][name=roundoff]').change(function() {
    if (this.value == '1') {
		enable_round_off = '1';		
	}
    else if (this.value == '0') {
		enable_round_off = '0';		
    }
	UpdateCalculations();
});
var pre_grand_total = $('#grand_total').val();

$( ".curSessionInList" ).hide();
$( ".payment_totals" ).hide();

$( "#customers" ).trigger("change");

$(document).on('change','.in_select',function () { 
	var data_amount_total = 0;	
	var data_amount = $(this).attr("data_amount");
	var grand_total = pre_grand_total;
	var totaladj = $('.table-total-adjusted').text();
	 
		if($(this).prop('checked') == true){

			if(parseFloat($('.table-total-adjusted').text()) >= parseFloat(grand_total)){
				$(this).prop('checked', false);
				return false;
			}	
			var pandingAmt, amountPaid;
			if(parseFloat(data_amount) < (parseFloat(grand_total)+parseFloat(totaladj)) && (parseFloat(grand_total) - parseFloat($('.table-total-adjusted').text())) > parseFloat(data_amount)){
				pandingAmt = '0.00';
				amountPaid = (Math.round((data_amount) * 100) / 100).toFixed(2);
			}else{
				pandingAmt = Math.abs(parseFloat(data_amount)-parseFloat(grand_total)+parseFloat(totaladj));
				amountPaid = (Math.round((parseFloat(grand_total)-parseFloat(totaladj)) * 100) / 100).toFixed(2);
			}
				

			$(this).closest('tr').find('.amount_paid').val('');		
			$(this).closest('tr').find('.pending_amount').text(pandingAmt);
			$(this).closest('tr').find('.amount_paid').val(amountPaid);
			var value_data = $(this).attr('value');		
			var value_data_array = value_data.split('-');
			value_data = value_data_array[0] + '-' + value_data_array[1] + '-' + value_data_array[2] + '-' + amountPaid;
			$(this).val(value_data);
		}else{
			$(this).closest('tr').find('.amount_paid').val('');		
			$(this).closest('tr').find('.pending_amount').text(data_amount);
			
			var value_data = $(this).attr('value');		
			var value_data_array = value_data.split('-');
			value_data = value_data_array[0] + '-' + value_data_array[1] + '-' + value_data_array[2] + '-0';
			$(this).val(value_data);
		}
		$(".in_select:checked").each(function(){		
			var amount_paid = $(this).closest('tr').find('.amount_paid').val();		
			if(amount_paid > 0){
				data_amount_total = parseFloat(data_amount_total) + parseFloat(amount_paid);
			}
		});
		
		UpdateTableTotal();
	
	
});



$('[name="payment"]').change(function(){
	if($(this).val() == 'CREDIT'){
		$('[name="adjustpayoff"]').prop('disabled', false);
		if(parseFloat($('.table-total-remaining').text()) != 0){
			$('.adjustPayment').css('display','block');
		}else{
			$('.adjustPayment').css('display','none');
		}
	}else{
		$('[name="adjustpayoff"]').prop({'disabled':true,'checked':true});
		$('.adjustPayment').css('display','none');
	}
});


function AmountAdjustment(){
	var remttl = ($('.table-total-remainingtotal').val())?$('.table-total-remainingtotal').val():0;
	var grandttl = ($('.table-total-grandtotal').val())?$('.table-total-grandtotal').val():0;
	if(companyid != $("#customers").val()){
		pre_grand_total = $('#grand_total').val();
	}else{
		pre_grand_total = parseFloat($('#grand_total').val()) + parseFloat(remttl) - parseFloat(grandttl);
	}
	var amountadjust = 0;
	var isTotalExceeded = false;
	$('.in_select:checked').prop('checked', false).trigger("change");
	$(".in_select").each(function(){
		if (!isTotalExceeded) {
			if(amountadjust > parseFloat(pre_grand_total)){
				$(this).prop('checked', false);
				isTotalExceeded = true;
			}else{
				$(this).trigger('click');
				amountadjust += parseFloat($(this).closest('tr').find('.totalamount').text());
			}
		}
		
	});
	UpdateTableTotal();
}

function UpdateTableTotal(){
	var total_remaining = 0;
	var total_adjusted = 0;
	var total_pending_amount = 0;
	var amount_paid;
	$(".in_select").each(function(){
		var pending_amount = parseFloat($(this).closest('tr').find('.pending_amount').text());
		total_pending_amount += pending_amount;
		var data_amount = parseFloat($(this).attr("data_amount"));
		total_remaining += data_amount;
		if($(this).is(":checked")){
			amount_paid = $(this).closest('tr').find('.amount_paid').val();
			total_adjusted += parseFloat(amount_paid);
		}else{
			$(this).closest('tr').find('.pending_amount').text(data_amount);

		}
	});
	$(".table-total-pending").html((Math.round((total_pending_amount) * 100) / 100).toFixed(2));
	$(".table-total-remaining").html((Math.round((total_remaining) * 100) / 100).toFixed(2));
	$(".table-total-adjusted").html((Math.round((total_adjusted) * 100) / 100).toFixed(2));
}


function UpdateQtyMax(){
	$('.product-combobox').each(function(n,v){
    	var productname = $(this).val();
    	if(productname != ''){
			var avlProd = availableProducts.find(function(val) {
				return val.name === productname;
			});
			var $targetparnt = $(this).parents(".product-item-row");
			if(inventory_enable){
				if(avlProd.non_salable_product != 1 || avlProd.non_salable_product != '1'){
					$($targetparnt).find(".product-quantity-available").html(avlProd.items_available);
				}
				if((avlProd.non_salable_product != 1 || avlProd.non_salable_product != '1')){
					$($targetparnt).find(".quantity").attr("qtymax",avlProd.items_available);
				}
			}
    	}
		
    });
	
}





var pre_grand_total_for_amount = $('#grand_total_lable').text();
function grand_total_lable_change(){	
	var grand_total_for_amount = $('#grand_total_lable').text(); 	
	if(pre_grand_total_for_amount != grand_total_for_amount){
		AmountAdjustment();
		pre_grand_total_for_amount = grand_total_for_amount;
	}
	
}
