<?php
global $image_mimes;
global $default_sizes;
$default_sizes = array(
	'small'=> array(
		'width'  => 200,
		'height' => 200,
		'crop'   => true,
	),
	'medium'=> array(
		'width'  => 800,
		'height' => 800,
		'crop'   => false,
	),
	'large'=> array(
		'width'  => 1500,
		'height' => 1500,
		'crop'   => false,
	)		
);

$image_mimes = array(
		'jpg|jpeg|jpe'=> 'image/jpeg',
		'gif'         => 'image/gif',
		'png'         => 'image/png',
		'bmp'         => 'image/bmp',
		'tiff|tif'    => 'image/tiff',
		'ico'         => 'image/x-icon'
	);
	
function is_cp_error( $thing ) {
	return ( $thing instanceof CP_Error );
}

class CP_Error {
	public $errors = array();
	public $error_data = array();

	public function __construct( $code = '', $message = '', $data = '' ) {
		if ( empty( $code ) ) {
			return;
		}

		$this->errors[ $code ][] = $message;

		if ( ! empty( $data ) ) {
			$this->error_data[ $code ] = $data;
		}
	}
	public function get_error_codes() {
		if ( ! $this->has_errors() ) {
			return array();
		}

		return array_keys( $this->errors );
	}
	public function get_error_code() {
		$codes = $this->get_error_codes();

		if ( empty( $codes ) ) {
			return '';
		}

		return $codes[0];
	}
	public function get_error_messages( $code = '' ) {
		// Return all messages if no code specified.
		if ( empty( $code ) ) {
			$all_messages = array();
			foreach ( (array) $this->errors as $code => $messages ) {
				$all_messages = array_merge( $all_messages, $messages );
			}

			return $all_messages;
		}

		if ( isset( $this->errors[ $code ] ) ) {
			return $this->errors[ $code ];
		} else {
			return array();
		}
	}
	public function get_error_message( $code = '' ) {
		if ( empty( $code ) ) {
			$code = $this->get_error_code();
		}
		$messages = $this->get_error_messages( $code );
		if ( empty( $messages ) ) {
			return '';
		}
		return $messages[0];
	}
	public function get_error_data( $code = '' ) {
		if ( empty( $code ) ) {
			$code = $this->get_error_code();
		}

		if ( isset( $this->error_data[ $code ] ) ) {
			return $this->error_data[ $code ];
		}
	}
	public function has_errors() {
		if ( ! empty( $this->errors ) ) {
			return true;
		}
		return false;
	}

	public function add( $code, $message, $data = '' ) {
		$this->errors[ $code ][] = $message;
		if ( ! empty( $data ) ) {
			$this->error_data[ $code ] = $data;
		}
	}
	public function add_data( $data, $code = '' ) {
		if ( empty( $code ) ) {
			$code = $this->get_error_code();
		}

		$this->error_data[ $code ] = $data;
	}
	public function remove( $code ) {
		unset( $this->errors[ $code ] );
		unset( $this->error_data[ $code ] );
	}
}
class CP_Return {
	public $status = array();
	public $message = array();
	public $data = array();
	public $url = array();
}
function gst_rates_options($value = '',$rate_type = 'cgst'){
	$gst_rates_options = array(
				0,
0.05,
0.125,
0.25,
0.5,
0.75,
1.5,
2.5,
3,
3.75,
6,
9,
14
			);
	$gst_rates_options_html = "";
	foreach($gst_rates_options as $gst_rates_option){
		
		if($rate_type == 'cgst'){
			$selected_text = ($gst_rates_option == $value)?'selected':'';
			$gst_rates_options_html .= "<option value='".$gst_rates_option."' $selected_text>".$gst_rates_option." + ".$gst_rates_option."</option>";
		}else{
			$selected_text = (($gst_rates_option*2) == $value)?'selected':'';
			$gst_rates_options_html .= "<option value='".($gst_rates_option*2)."' $selected_text>".($gst_rates_option*2)."</option>";
		}
	}
	return $gst_rates_options_html;
}
function trim_all_values($values){
	$clean_values = array();
	foreach ($values as $key => $item) {
		if(is_string($item))
		{
			$clean_values[$key] = trim($item);
		}
		else
		{
			$clean_values[$key] = $item;
		}
	}
	return $clean_values;
}
function dateFromString($str, $return_error = false){
	if($str != ""){
		$str = trim($str);
		$str = str_replace("`","",$str);
		if(preg_match("/[a-z]/i", $str)){
			if (strpos($str, '/') !== false) { $str = str_replace("/","-",$str);}
			else if (strpos($str, '_') !== false)  { $str = str_replace("_","-",$str);}
			else if (strpos($str, '.') !== false)  { $str = str_replace(".","-",$str);}
			else if (strpos($str, ',') !== false)  { $str = str_replace(",","-",$str);}
			return  date("Y-m-d",  strtotime($str));
		}
		else
		{
			if (strpos($str, '/') !== false) { $str = str_replace("/","-",$str);}
			else if (strpos($str, '_') !== false)  { $str = str_replace("_","-",$str);}
			else if (strpos($str, '.') !== false)  { $str = str_replace(".","-",$str);}
			else if (strpos($str, ',') !== false)  { $str = str_replace(",","-",$str);}
			
			$dash_count = substr_count($str,"-");
			if (strpos($str, '-') !== false && $dash_count == 2) list($day, $month, $year) = explode('-', $str);
			else return $return_error?false:"";	
			return  date("Y-m-d",  mktime(0, 0, 0, $month, $day, $year));
		}
	}
	else
	{
		return "";
	}
}
require_once('image.php');
require_once('mail.php');
require_once('db_class.php');
?>