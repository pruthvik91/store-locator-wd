<?php
ini_set("log_errors", 1);
ini_set("display_errors", 1);
ini_set("error_log", "error_logs/php-error_" . date('Y-m-d') . ".log");
ini_set('memory_limit', '-1');

error_reporting(E_ALL);
session_start();
ob_start();

function GetCurrentUserDB(){
	$user_base_db = "";
	if(empty($_SESSION['userid'])){
		$user_base_db = "_0_5k";
	}
	else
	{
		$uid = intval($_SESSION['userid']);
		if($uid<=4999)
		{
			$user_base_db = "_0_5k";
		}
		else if($uid>4999 && $uid<=9999)
		{
			$user_base_db = "5_10k";
		}
		else
		{
			$user_base_db = "_0_5k";
		}
	}
	return $user_base_db;
}
function all_base_db_name()
{
	return implode(",",array('store_db_0_5k','store_db_5_10k'));
}
function get_timezone_offset($remote_tz, $origin_tz = null) {
    if($origin_tz === null) {
        if(!is_string($origin_tz = date_default_timezone_get())) {
            return false; 
        }
    }
    $origin_dtz = new DateTimeZone($origin_tz);
    $remote_dtz = new DateTimeZone($remote_tz);
    $origin_dt = new DateTime("now", $origin_dtz);
    $remote_dt = new DateTime("now", $remote_dtz);
    $offset = $remote_dtz->getOffset($remote_dt) - $origin_dtz->getOffset($origin_dt);
    return ($offset/60);
}
define("ADMIN_URL","http://localhost/wed&nik/client-area/");
define("SITE_URL","http://localhost/wed&nik/");
define("SITE_PATH", $_SERVER['DOCUMENT_ROOT']."/wed&nik/");
define('DB_PRE', 'store_db');
define("WKHTMLTOPDF",'C:\\wamp64\\www\\wed&nik\\include\\wkhtmltopdf\\bin\\wkhtmltopdf.exe');
define('DB_MAIN', DB_PRE.'_main');
define('DB_TEMP',DB_PRE.'_temp');
define('DB_BASE', DB_PRE.GetCurrentUserDB());
define('DB_ALL_BASE', all_base_db_name());
define('ENCRYPTION_KEY', '3223223323232323');
define('DBUSER', 'root');
define('DBHOST', 'localhost');
define('DBPASS', 'root');
define('DBDSN', 'mysql:host='.DBHOST.';dbname='.DB_MAIN.';charset=utf8mb4;');

require_once(SITE_PATH."include/function.php");
define('DB_TIMEDIFF', get_timezone_offset('Asia/Kolkata'));
define('GLOBAL_HOST','smtp.gmail.com');
define('GLOBAL_SMTPAUTH',true);
define('GLOBAL_USERNAME','storelocator@gmail.com');
define('GLOBAL_PASSWORD','storelocator@gmail.com');
define('GLOBAL_SMTPSECURE','tls');
define('GLOBAL_PORT',587);
define('GLOBAL_SETFROM','storelocator@gmail.com');
define('GLOBAL_SETFROMNAME','Store Locator');
define("UPLOAD_PATH",$_SERVER['DOCUMENT_ROOT']."/wed&nik/client-area/upload/");

define('GLOBAL_ADDREPLYTO','storelocator@gmail.com');
define('GLOBAL_ADDREPLYTONAME','Store Locator');

define('URLVERSION','?ver=9.2252.2252&mode=9.22552921'); 
define('VERSION','9.2252.225512334aabzdfasa33azzxqaaas32');

define('PERPAGE', '30');

global $db;
$db = new PDO(DBDSN,DBUSER,DBPASS);
?>