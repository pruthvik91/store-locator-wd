<?php 
require_once('../config.php');
require_once('uservalidation.php');
require_once('header.php'); 

$userid = $_SESSION['userid'];
$searchQuery ="";
$store_name = "";
$store_category_id = "";

$sql_c = "SELECT store_category_id,category_name FROM ".DB_BASE.".store_category where 1=1 AND user_detail_id = :user_id";
$data_c = array(':user_id' => $userid);
$categories =  CP_Select($sql_c,$data_c);
$categories_count = count($categories);						

//delete start
if(isset($_GET["did"]) && !empty($_GET["did"])){
	$did = $_GET["did"];	
	if(is_numeric($did) && $did > 0 ){
		$sqldelete = 'DELETE from '.DB_BASE.'.store where user_detail_id=:userid and  store_id=:store_id';		
		$datadelete = array(':store_id' => $did,':userid' => $userid);		
		CP_Delete($sqldelete,$datadelete);
		header("Location: list-store.php?mode=deleted");
	}
}
//delete end

//search start
if(isset($_POST["btnSearch"])=="Search"){	
	
	$store_name = $_POST["store_name"];
	$store_category_id = $_POST["store_category_id"];
	if(!empty($store_name
	))
	{
		$searchQuery = $searchQuery." AND store_name LIKE '%".$store_name."%' ";
	}
	if(!empty($store_category_id))
	{
		$searchQuery = $searchQuery." AND store_category_id = '".$store_category_id."' ";
	}
}else{	
		
	if(!empty($_GET["store_name"]) || !empty($_GET["store_category_id"])){
		echo "<pre>";
		print_r($_GET);
		$store_name = $_GET["store_name"];
		$store_category_id = $_GET["store_category_id"];
		if(!empty($store_name))
		{
			$searchQuery = $searchQuery." AND store_name LIKE '%".$store_name."%' ";
		}
		if(!empty($store_category_id))
		{
			$searchQuery = $searchQuery." AND store_category_id = '".$store_category_id."' ";
		}
	}	
}
//search end

$sql = "SELECT * FROM ".DB_BASE.".store WHERE 1=1 ".$searchQuery." AND user_detail_id = :user_id";
$data = array(':user_id' => $userid);
$store =  CP_Select($sql,$data);
$store_count = count($store);

//pagination start
include('paginate.php');
if(isset($_POST["btnSearch"])=="Search"){
	$cur_page=1; 
}else{
	$cur_page = isset($_GET["page"])?$_GET["page"]:1;	
}
$per_page = PERPAGE;

if($cur_page==1){
	$page_start = 0;
}else{
	$page_start = (($cur_page-1)*$per_page);
}

$total_results = $store_count;
$total_pages = intval($total_results / $per_page); //total pages we going to have

if($total_pages==0){
	$total_pages = 1;
}elseif(($total_pages*$per_page)<$total_results){
	$total_pages++;
}

$pagination = "";
$url_string = "";
$url_string .= "store_name=".$store_name;
$url_string .= "&store_category_id=".$store_category_id;
if ($total_pages > 1) {
	$pagination = paginate('list-store.php?'.$url_string, $cur_page, $total_pages);
	$url_string .= "&page=".$cur_page;
}

$sql1 = "SELECT * FROM ".DB_BASE.".store where 1=1 ".$searchQuery."   and user_detail_id = :user_id limit ".$page_start.",".$per_page;
$data1 = array(':user_id' => $userid);
$store1 =  CP_Select($sql1,$data1);
$store_count1 = count($store1);

//pagination end
?>
<nav class="page-breadcrumb">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="#">Dashboard</a></li>
		<li class="breadcrumb-item active" aria-current="page">Add Store</li>
	</ol>
</nav>
				
<?php if(isset($_GET['mode']) && $_GET['mode'] == 'inserted'){?>
<div class="alert alert-icon-info alert-dismissible fade show" role="alert">
	<i data-feather="check-square"></i>
	Inserted Successfully!
	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		<span aria-hidden="true">×</span>
	</button>
</div>
<?php } ?>
<?php if(isset($_GET['mode']) && $_GET['mode'] == 'updated'){?>
<div class="alert alert-icon-info alert-dismissible fade show" role="alert">
	<i data-feather="check-square"></i>
	Updated Successfully!
	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		<span aria-hidden="true">×</span>
	</button>
</div>
<?php } ?>
<?php if(isset($_GET['mode']) && $_GET['mode'] == 'deleted'){?>
<div class="alert alert-icon-danger alert-dismissible fade show" role="alert">
	<i data-feather="alert-triangle"></i>
	Deleted Successfully!
	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		<span aria-hidden="true">×</span>
	</button>
</div>
<?php } ?>
				<div class="row">
					<div class="col-md-12 stretch-card grid-margin">
						<div class="card">
							<div class="card-body">
								<h6 class="card-title">Search Store</h6>
								<form method="post">
									<div class="row">										
										<div class="col-sm-4">
											<div class="form-group">
												<label class="control-label">Name</label>
												<input type="text" class="form-control" placeholder="Enter Name" name="store_name" value="<?php echo $store_name; ?>">
											</div>
										</div><!-- Col -->
										<div class="col-sm-4">
										<div class="form-group">
											<label>Category</label>
											<select class="js-example-basic-single w-100"  name="store_category_id">
												<option selected="" value="">Select Category</option>
												<?php
												if($categories_count > 0){
													foreach($categories as $category){				
														$store_category_id_c = $category->store_category_id;
														$category_name = $category->category_name;
												?>
												<option value="<?php echo $store_category_id_c; ?>" <?php if($store_category_id_c == $store_category_id){echo "selected";}?>><?php echo $category_name; ?></option>			
												<?php
													}
												}
												?>
											</select>
											
										</div>
										</div><!-- Col -->
									</div><!-- Row -->										
								
									<button type="submit" name="btnSearch" value="search"  class="btn btn-primary 	btn-icon-text submit">
										<i class="btn-icon-prepend" data-feather="search"></i>
										Search
									</button>
									<button class="btn btn-primary 	btn-icon-text submit">
										<i class="btn-icon-prepend" data-feather="list"></i>
										Show all
									</button>
								</form>
							</div>
						</div>
					</div>
				</div>
				
				<div class="row">					
					<div class="col-lg-12 grid-margin stretch-card">
						<div class="card">
							<div class="card-body">
								<h6 class="card-title">Store List
									<a href="add-store.php?<?php echo $url_string; ?>" class="btn btn-primary btn-icon-text float-right">									
										<i class="btn-icon-prepend" data-feather="plus"></i>
										Add New
									</a>
								</h6>
								
								<div class="table-responsive">
									<table class="table table-hover">
										<thead>
											<tr>
												<th>#</th>
												<th>Name</th>												
												<th>Address</th>
												<th class="text-center">Visibility</th>
												<th class="table_action_th text-center">Action</th>
												<!--<th style=""></th> -->
											</tr>
										</thead>
										<tbody>
											<?php
											if($cur_page==1){
												$counter = 0;
											}else{
												$counter = ($cur_page-1)*$per_page;
											}
											if($store_count1 > 0){
												foreach($store1 as $row){
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
													$address = '';
													if(isset($store_address_line1) && !empty($store_address_line1)){$address .= $store_address_line1.", ";}
													if(isset($store_address_line2) && !empty($store_address_line2)){$address .= $store_address_line2.", ";}
													if(isset($city) && !empty($city)){$address .= $city.", ";}
													if(isset($state) && !empty($state)){$address .= $state.", ";}
													if(isset($country) && !empty($country)){$address .= $country." ";}
													if(isset($zip) && !empty($zip)){$address .= $zip;}
													//$category_name = "";
													//if(isset($store_category_id) && !empty($store_category_id)){
														//$sql_c = "SELECT category_name FROM ".DB_BASE.".store_category where 1=1 AND user_detail_id = :user_id AND store_category_id = :store_category_id";
														///$data_c = array(':user_id' => $userid,':store_category_id'=>$store_category_id);
														//$categories =  CP_Select($sql_c,$data_c);
														//$categories_count = count($categories);						
														//$category_name = $categories['0']->category_name;
													//}
											?>
											<tr>
												<th><?php echo $counter; ?></th>
												<td><?php echo $store_name; ?></td>					
												<td><?php echo $address; ?></td>
												<td class="text-center"><button type="button" class="btn is_active"  data-value = "<?php echo $is_active; ?>" data-id="<?php echo $store_id;?>"><?php echo ($is_active == 1)?"Visible":"Invisible"; ?></button></td>
												<td class="text-center">
													<a href="add-store.php?id=<?php echo $store_id."&".$url_string; ?>" class="btn btn-primary btn-icon-text">									
														<i class="btn-icon-prepend" data-feather="edit"></i>
														Edit
													</a>

													<div class="dropdown mb-2 table_dropdown_option">
                       									<button class="btn p-0" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                       									  <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                       									</button>
                       									<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton2">                       									  
                       									  <a class="dropdown-item d-flex align-items-center" href="?did=<?php echo $store_id; ?>"><i data-feather="trash" class="icon-sm mr-2"></i> <span class="">Delete</span></a>
                       									</div>
                      								</div>
												</td>
											</tr>
											<?php }} ?>											
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>				
				<?php echo $pagination ;?>
            </div>
		
			<script>    
$(document).ready(function(){
	 $('.is_active').click(function(){		 
	var btn =  $(this);
	//btn.attr("data-text",btn.html()).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
	//btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
	  var data = {
			action: 'ChangeStatus',
			module: 'store',
			id:btn.attr('data-id'),

			value:btn.attr('data-value'),
			hash: btn.attr('data-hash')
	  }	  	  
	   $.ajax({
	    url:"ajaxcall.php",
		ContentType : 'application/json',
	    method:"POST",
		dataType:"json",
	    data:data,	    
	    success:function(data)
	    {		

			if(data.status == 'OK'){
				btn.html((data.data == 1)?"Visible":"Invisible");
				btn.attr("data-value",data.data);
			}else{
				btn.html(btn.attr("data-text"));
			}
	    },
	    error:function(errorThrown)
	    {
			
			console.log(errorThrown);
	    },
	   complete:function(data)
	    {
			
	    }
	   });

	 
	 });	 
	});
</script>
<?php require_once('footer.php'); ?>