function number_format_indian(x) {
    (x.length > 0)?x.trim():x;
   if(x.length > 0 && x.charAt(0) == "-"){
    x = x.substring(1);
    return "-"+ (x.toString().split('.')[0].length > 3 ? x.toString().substring(0,x.toString().split('.')[0].length-3).replace(/\B(?=(\d{2})+(?!\d))/g, ",") + "," + x.toString().substring(x.toString().split('.')[0].length-3): x.toString());
  }else{
    return x.toString().split('.')[0].length > 3 ? x.toString().substring(0,x.toString().split('.')[0].length-3).replace(/\B(?=(\d{2})+(?!\d))/g, ",") + "," + x.toString().substring(x.toString().split('.')[0].length-3): x.toString();
   }
  }
  function frac(f) {
    return (f % Math.floor(f));
}
  function number2text(value) {
    var fraction = Math.round(frac(value) * 100);
    var f_text = "";
    if (fraction > 0) {
        f_text = "AND " + convert_number(fraction) + " PAISA";
    }
    return convert_number(value) + " RUPEES " + f_text + " ONLY";
}
function convert_number(number) {
    if ((number < 0) || (number > 9999999999)) {
        return "NUMBER OUT OF RANGE!";
    }
    var Mn = Math.floor(number / 1000000000); /* Thousand Crore */
    number -= Mn * 1000000000;
    var Gn = Math.floor(number / 10000000); /* Crore */
    number -= Gn * 10000000;
    var kn = Math.floor(number / 100000); /* lakhs */
    number -= kn * 100000;
    var Hn = Math.floor(number / 1000); /* thousand */
    number -= Hn * 1000;
    var Dn = Math.floor(number / 100); /* Tens (deca) */
    number = number % 100; /* Ones */
    var tn = Math.floor(number / 10);
    var one = Math.floor(number % 10);
    var res = "";
    if (Mn > 0) {
        res += (convert_number(Mn) + " HUNDRED");
    }
    if (Gn > 0) {
        res += (((res == "") ? "" : " ") + convert_number(Gn) + " CRORE");
    }
    if (kn > 0) {
        res += (((res == "") ? "" : " ") + convert_number(kn) + " LAKH");
    }
    if (Hn > 0) {
        res += (((res == "") ? "" : " ") + convert_number(Hn) + " THOUSAND");
    }
    if (Dn) {
        res += (((res == "") ? "" : " ") + convert_number(Dn) + " HUNDRED");
    }
    var ones = Array("", "ONE", "TWO", "THREE", "FOUR", "FIVE", "SIX", "SEVEN", "EIGHT", "NINE", "TEN", "ELEVEN", "TWELVE", "THIRTEEN", "FOURTEEN", "FIFTEEN", "SIXTEEN", "SEVENTEEN", "EIGHTEEN", "NINETEEN");
    var tens = Array("", "", "TWENTY", "THIRTY", "FORTY", "FIFTY", "SIXTY", "SEVENTY", "EIGHTY", "NINETY");
    if (tn > 0 || one > 0) {
        if (!(res == "")) {
            res += " AND ";
        }
        if (tn < 2) {
            res += ones[tn * 10 + one];
        } else {
            res += tens[tn];
            if (one > 0) {
                res += ("-" + ones[one]);
            }
        }
    }
    if (res == "") {
        res = "ZERO";
    }
    return res;
}
function errorMessage(text,title = "Error",html='',icon='warning') {
	Swal.fire({
		icon: icon,
		title: ''+title+'',
		text: text,
		html: html,
		allowEnterKey: false,
		allowEscapeKey: false
	});
}
$(document).on('change', 'input[name="delall[]"]', function() {
    var checkedCount = $('input[name="delall[]"]:checked').length;
    console.log(checkedCount);
    if (checkedCount >= 1) {
      $('body').addClass("bulk-action");
    } else {
      $('body').removeClass("bulk-action");
    }
  });
  
    document.addEventListener('DOMContentLoaded', function() {
        // Check local storage for selected checkboxes
        const checkedCheckboxes = JSON.parse(localStorage.getItem('checkedCheckboxes') || '[]');
        if (checkedCheckboxes.length > 0) {
            document.body.classList.add('bulk-action');
        }
    });


 /* Barcode Detetction Library start */
!function(e){e.fn.scannerDetection=function(n){if("string"==typeof n)return this.each(function(){this.scannerDetectionTest(n)}),this;if(n===!1)return this.each(function(){this.scannerDetectionOff()}),this;var t={onComplete:!1,onError:!1,onReceive:!1,timeBeforeScanTest:100,avgTimeByChar:30,minLength:6,endChar:[9,13],startChar:[],ignoreIfFocusOn:!1,scanButtonKeyCode:0,scanButtonLongPressThreshold:3,onScanButtonLongPressed:!1,stopPropagation:!1,preventDefault:!1};return"function"==typeof n&&(n={onComplete:n}),n="object"!=typeof n?e.extend({},t):e.extend({},t,n),this.each(function(){var t=this,o=e(t),r=0,i=0,s="",c=!1,a=!1,f=0,u=function(){r=0,s="",f=0};t.scannerDetectionOff=function(){o.unbind("keydown.scannerDetection"),o.unbind("keypress.scannerDetection")},t.isFocusOnIgnoredElement=function(){if(!n.ignoreIfFocusOn)return!1;if("string"==typeof n.ignoreIfFocusOn)return e(":focus").is(n.ignoreIfFocusOn);if("object"==typeof n.ignoreIfFocusOn&&n.ignoreIfFocusOn.length)for(var t=e(":focus"),o=0;o<n.ignoreIfFocusOn.length;o++)if(t.is(n.ignoreIfFocusOn[o]))return!0;return!1},t.scannerDetectionTest=function(e){return e&&(r=i=0,s=e),f||(f=1),s.length>=n.minLength&&i-r<s.length*n.avgTimeByChar?(n.onScanButtonLongPressed&&f>n.scanButtonLongPressThreshold?n.onScanButtonLongPressed.call(t,s,f):n.onComplete&&n.onComplete.call(t,s,f),o.trigger("scannerDetectionComplete",{string:s}),u(),!0):(n.onError&&n.onError.call(t,s),o.trigger("scannerDetectionError",{string:s}),u(),!1)},o.data("scannerDetection",{options:n}).unbind(".scannerDetection").bind("keydown.scannerDetection",function(e){if(n.scanButtonKeyCode&&e.which==n.scanButtonKeyCode)f++,e.preventDefault(),e.stopImmediatePropagation();else if(r&&-1!==n.endChar.indexOf(e.which)||!r&&-1!==n.startChar.indexOf(e.which)){var t=jQuery.Event("keypress",e);t.type="keypress.scannerDetection",o.triggerHandler(t),e.preventDefault(),e.stopImmediatePropagation()}}).bind("keypress.scannerDetection",function(e){this.isFocusOnIgnoredElement()||(n.stopPropagation&&e.stopImmediatePropagation(),n.preventDefault&&e.preventDefault(),r&&-1!==n.endChar.indexOf(e.which)?(e.preventDefault(),e.stopImmediatePropagation(),c=!0):r||-1===n.startChar.indexOf(e.which)?(s+=String.fromCharCode(e.which),c=!1):(e.preventDefault(),e.stopImmediatePropagation(),c=!1),r||(r=Date.now()),i=Date.now(),a&&clearTimeout(a),c?(t.scannerDetectionTest(),a=!1):a=setTimeout(t.scannerDetectionTest,n.timeBeforeScanTest),n.onReceive&&n.onReceive.call(t,e),o.trigger("scannerDetectionReceive",{evt:e}))})}),this}}(jQuery);
/* Barcode Detetction Library end */
