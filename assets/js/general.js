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