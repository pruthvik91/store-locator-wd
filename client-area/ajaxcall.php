<?php
require_once('../config.php');
$userid = $_SESSION["userid"];	
$retArray = array();
if(isset($_POST['action']) && $_POST['action']!="")
{
	switch($_POST['action'])
	{		
        case 'ChangeStatus':		
            $id =  $_POST["id"];
            $value =  $_POST["value"];
            $is_active = (intval($value)==1)?0:1;
            $module = $_POST["module"];
            $retArray["status"] = "OK";
            $dt1 = date("Y-m-d H:i:s");
            $sqlupdate = 'UPDATE '.DB_BASE.'.'.$module.' set  is_active=:is_active , modify_date=:modify_date where '.$module.'_id = :id AND user_detail_id=:user_id';
			$dataupdate = array(':is_active'=>$is_active,
				':modify_date'=>$dt1,
				':id'=>$id,
				':user_id'=>$userid );
            CP_update($sqlupdate,$dataupdate);
            $retArray["data"] = $is_active;
        break;

        case 'imageUpload':		
            $retArray["status"] = "OK";
			$retArray["current_data"] = $_POST["current_data"];
			$retArray["multiple"] = $_POST["multiple"];			
			$field_name = $_POST["field_name"] ;
			global $image_mimes, $default_sizes;
            $settings['sizes'] = $default_sizes;
            $settings['mimes'] = $image_mimes;
			$settings['upload_to'] = UPLOAD_PATH;
			$retArray["single_img_name"]  = "";
			$images_json = array();
			$filenames = array();
            if(strpos($field_name, '[]')){
				$field_name = str_replace("[]","",$field_name);
				for($int = 0; $int < count($_FILES[$field_name]['name']);$int++){
					$file_to_upload = array();
					$file_to_upload['name'] = $_FILES[$field_name]['name'][$int];
					$file_to_upload['type'] = $_FILES[$field_name]['type'][$int];
					$file_to_upload['tmp_name'] = $_FILES[$field_name]['tmp_name'][$int];
					$file_to_upload['error'] = $_FILES[$field_name]['error'][$int];
					$file_to_upload['size'] = $_FILES[$field_name]['size'][$int];
					$uploaded = "";
					$uploaded = handle_upload($file_to_upload,$settings);
					$filenames['name'] = $uploaded;
					$filenames['type'] = 'image';
					array_push($images_json ,$filenames);
				}
			}else{
				$uploaded = "";
				$uploaded = handle_upload($_FILES[$field_name],$settings);
				$filenames['name'] = $uploaded;
				$filenames['type'] = 'image';
				$retArray["single_img_name"] = $uploaded;
				array_push($images_json ,$filenames);
			}            
		   //$retArray["file"] = implode(";",$images_json );
		   $images_json = json_encode($images_json);
		   $retArray["file"] = $images_json;		   
		   
           $retArray["field_name"] = $field_name;
        break;
     
        default:
        break;
		case 'GetInvoiceNumber':
					global $db;
					$invoice = $_POST["invoice"];
					$sql="select max(invoice_id) as lastid from ".DB_BASE.".invoice_detail  WHERE is_deleted != 1  "; 
					$lastid = 0;
					$result = $db->prepare("$sql");
					$result->execute(); 
					$rows = $result->fetchAll(PDO::FETCH_OBJ);
					$rowcount = count($rows);	
					if(isset($rowcount) && $rowcount!= "" && $rowcount>0){
						foreach($rows as $max){	
							if(isset($max->lastid))
							{
								$lastid = intval($max->lastid);
								$lastid++;
							}
							else
							{
								$lastid++;
							}
						}
					}
					else
					{
						$lastid++;
					}
					$retArray["status"] = "OK";
					$retArray["lastid"] = $lastid;
			break;
			case 'ValidateInvoiceID':
					if(isset($_POST['id'])) {	
						global $db;
						$id = $_POST["id"];
						$invoicetype = $_POST["invoicetype"];
						$sql="SELECT invoice_id FROM ".DB_BASE.".invoice_detail where invoice_id = '$id' AND is_deleted != 1  ";
						$result = $db->prepare("$sql");
						$result->execute(); 
						$rows = $result->fetchAll(PDO::FETCH_OBJ);
						$rowcount = count($rows);	
						if($rowcount>0)
						{
							$retArray["status"] = "USED";				
						}
						else {
							$retArray["status"] = "OK";		
							
						}
					}
				break;
    }
	
}

ob_clean();
//print_r($retArray);
echo json_encode($retArray);
exit;
?>