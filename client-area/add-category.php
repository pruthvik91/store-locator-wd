<?php 
require_once('../config.php');
require_once('uservalidation.php');
$URLBack = $_SERVER['QUERY_STRING'];
global $db;
$userid = $_SESSION["userid"];	
$category_name = "";
$category_detail = "";
$marker_type = "";
$marker_icon = "";
$marker_color = "";
$marker_size = "";
$marker_image = "";
$featured = 0;
$is_active = 1;

if((!empty($_POST)) && isset($_POST["btnsubmit"])){
	if(isset($_POST["category_name"]))
	  {
	  	$category_name = trim($_POST["category_name"]);		
	  	$category_detail = trim($_POST["category_detail"]);		
	  	$marker_type = trim($_POST["marker_type"]);		
		$marker_icon = trim($_POST["marker_icon"]);
		$marker_color = trim($_POST["marker_color"]);
	  	$marker_size = trim($_POST["marker_size"]);
	  	$marker_image = $_POST["marker_image"];
		$featured = isset($_POST["featured"]) && ($_POST["featured"]=='1')?1:0;
		$is_active = isset($_POST["is_active"]) && ($_POST["is_active"]=='1')?1:0;	  			
		$dt1 = date("Y-m-d H:i:s");		

		if((isset($_GET["id"])) && ($_GET["id"]!="")){
			$id = $_GET["id"];
			
			$sql="SELECT * FROM ".DB_BASE.".store_category where store_category_id = :store_category_id  and user_detail_id = :id ";
			$data=array(':store_category_id'=>$id,':id'=>$userid);			
			$rowcount = CP_count($sql,$data);
			$sqlupdate = 'UPDATE '.DB_BASE.'.store_category set  category_name=:category_name  , category_detail=:category_detail , marker_type=:marker_type , marker_icon=:marker_icon , marker_color=:marker_color , marker_size=:marker_size , marker_image=:marker_image , featured=:featured , is_active=:is_active , modify_date=:modify_date where store_category_id=:store_category_id AND user_detail_id=:user_id';				
			$data = array(':category_name'=>$category_name,
				':category_detail'=>$category_detail,
				':marker_type'=>$marker_type,
				':marker_icon'=>$marker_icon,
				':marker_color'=>$marker_color,
				':marker_size'=>$marker_size,
				':marker_image'=>$marker_image,
				':featured'=>$featured,
				':is_active'=>$is_active,
				':modify_date'=>$dt1,
				':store_category_id'=>$id,
				':user_id'=>$userid );
			CP_update($sqlupdate,$data);
			header("Location: list-category.php?mode=updated");		
		}else{
			$sql = "INSERT INTO ".DB_BASE.".store_category (`user_detail_id`, `category_name`, `category_detail`, 	`marker_type`, `marker_icon`, `marker_color`, `marker_size`, `marker_image`, `featured`, `is_active`, 	`create_date`) VALUES (:user_detail_id, :category_name, :category_detail, :marker_type, :marker_icon, 	:marker_color, :marker_size, :marker_image, :featured, :is_active, :create_date)";      	   
			$data = array( ':user_detail_id'=>$userid,
							':category_name'=>$category_name,
							':category_detail'=>$category_detail,
							':marker_type'=>$marker_type,
							':marker_icon'=>$marker_icon,
							':marker_color'=>$marker_color,
							':marker_size'=>$marker_size,
							':marker_image'=>$marker_image,
							':featured'=>$featured,
							':is_active'=>$is_active,
							':create_date'=>$dt1);
			$store_caregory_id = CP_Insert($sql,$data);	
			header("Location: list-category.php?mode=inserted");
		}		
	}
}
if((isset($_GET["id"])) && ($_GET["id"]!="")){
	$id = $_GET["id"];	
	$sql="SELECT * FROM ".DB_BASE.".store_category where store_category_id = :store_category_id  and user_detail_id = :userid ";
	$data=array(':store_category_id'=>$id,':userid'=>$userid);			
	$rows = CP_select($sql,$data);	
	$rowcount=count($rows);
	if($rowcount>0){
		foreach($rows as $row){
			$category_name = $row->category_name;
			$category_detail = $row->category_detail;
			$marker_type = $row->marker_type;
			$marker_icon = $row->marker_icon;
			$marker_size = $row->marker_size;
			$marker_color = $row->marker_color;
			$marker_image = $row->marker_image;
			$featured = $row->featured;
			$featured = isset($featured) && ($featured=='1')?1:0;
			$is_active = $row->is_active;
			$is_active = isset($is_active) && ($is_active=='1')?1:0;
		}
	}

}
require_once('header.php');
?>
<nav class="page-breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="#">Home</a></li>
						<li class="breadcrumb-item active" aria-current="page">Add Category</li>
					</ol>
				</nav>
				<form class="cmxform" id="form" method="post" action="">
					<fieldset>
				<div class="row">
					<div class="col-md-12 grid-margin stretch-card">
						<div class="card">
							<div class="card-body">
								<h4 class="card-title">Add Categery</h4>								
								
										<div class="form-group">
											<label for="name">Category Name</label>
											<input id="name" class="form-control" name="category_name" value="<?php echo $category_name; ?>" type="text" required>
										</div>
										<div class="form-group">
											<label for="category_detail">Category Detail</label>
											<textarea class="form-control" name="category_detail"   id="category_detail" rows="3" ><?php echo $category_detail; ?></textarea>
										</div>																					
										<div class="form-check form-check-flat form-check-primary mt-0">
											<label class="form-check-label">
											<input type="checkbox" name="featured" class="form-check-input" value="1" <?php if ($featured=='1'){echo 'checked';} ?> >
											Featured
											<i class="input-frame"></i></label>
										</div>
										<div class="form-check form-check-flat form-check-primary mt-0">
											<label class="form-check-label">
											<input type="checkbox" name="is_active" class="form-check-input" value="1" <?php if ($is_active=='1'){echo 'checked';} ?> >
											Visibility
											<i class="input-frame"></i></label>
										</div>										
							</div>
						</div>
					</div>					
                </div>	
				<?php include('marker-fields.php'); ?>
				<div class="row">
					<div class="col-md-12 grid-margin stretch-card">
						<div class="card">
							<div class="card-body">
							<input class="btn btn-primary" type="submit" value="Submit" name="btnsubmit">
							<a type="button" class="btn" href="list-category.php?<?php echo $URLBack; ?>">Back</a>
							</div>
						</div>
					</div>					
                </div>	
				</fieldset>
								</form>
            </div>	

<?php require_once('footer.php'); ?>