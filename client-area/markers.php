<?php
require_once('../config.php');
//require_once('uservalidation.php');
//require_once('header.php'); 

$userid = $_SESSION['userid'];
$dom = new DOMDocument("1.0");
//$node = $dom->createElement("markers");
//$parnode = $dom->appendChild($node);

function parseToXML($htmlStr)
{
$xmlStr=str_replace('<','&lt;',$htmlStr);
$xmlStr=str_replace('>','&gt;',$xmlStr);
$xmlStr=str_replace('"','&quot;',$xmlStr);
$xmlStr=str_replace("'",'&#39;',$xmlStr);
$xmlStr=str_replace("&",'&amp;',$xmlStr);
return $xmlStr;
}



$sql = "SELECT * FROM ".DB_BASE.".store where 1=1 AND user_detail_id = :user_id";
$data = array(':user_id' => $userid);
$store =  CP_Select($sql,$data);
$store_count = count($store);
ob_clean();
ob_start();
echo "<?xml version='1.0' ?>";
echo '<markers>';
$ind=0;
$counter = 0;
// Iterate through the rows, adding XML nodes for each
if($store_count > 0){
  foreach($store as $row){
    $counter++;
    $store_id = $row->store_id; 
    $store_category_id = $row->store_category_id; 
    $store_name = $row->store_name; 
    $store_description = $row->store_description; 
    $store_featured_image = $row->store_featured_image; 
    $store_images = $row->store_images; 
    $store_video = $row->store_video; 
    $store_opening_hours = $row->store_opening_hours; 
    $open_always = $row->open_always; 
    $store_phone = $row->store_phone; 
    $store_email = $row->store_email; 
    $store_website = $row->store_website; 
    $store_address_line1 = $row->store_address_line1; 
    $store_address_line2 = $row->store_address_line2; 
    $city = $row->city; 
    $state = $row->state; 
    $country = $row->country; 
    $zip = $row->zip; 
    $latitude = $row->latitude; 
    $longitude = $row->longitude; 
    $marker_type = $row->marker_type; 
    $marker_icon = $row->marker_icon; 
    $marker_color = $row->marker_color; 
    $marker_image = $row->marker_image; 
    $marker_size = $row->marker_size; 
    $is_active = $row->is_active;
    $featured = $row->featured;
    $featured = isset($featured) && ($featured=='1')?1:0;
    $category_name = "";
    if(isset($store_category_id) && !empty($store_category_id)){
      $sql_c = "SELECT category_name FROM ".DB_BASE.".store_category where 1=1 AND user_detail_id = :user_id AND store_category_id = :store_category_id";
      $data_c = array(':user_id' => $userid,':store_category_id'=>$store_category_id);
      $categories =  CP_Select($sql_c,$data_c);
      $categories_count = count($categories);						
      $category_name = $categories['0']->category_name;           
    }
      echo '<marker ';
      echo 'id="' . $store_id. '" ';
      echo 'name="' . parseToXML($store_name) . '" ';
      echo 'address="' . parseToXML($city) . '" ';
      echo 'lat="' . $latitude . '" ';
      echo 'lng="' . $longitude . '" ';
      echo ' type="company" ';
      echo '/>';
      $ind = $ind + 1;
  }
}
echo '</markers>';
$content = ob_get_clean();

file_put_contents('markers1.xml', $content);


//require_once('footer.php');
?>