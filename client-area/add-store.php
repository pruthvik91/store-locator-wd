<?php 
require_once('../config.php');
require_once('uservalidation.php');
$URLBack = $_SERVER['QUERY_STRING'];
global $db;
global $default_sizes;
global $image_mimes;
$userid = $_SESSION["userid"];	

$store_category_id = "";
$store_name = "";
$store_description = "";
$tags = "";
$store_featured_image = "";
$store_images = "";
$store_video = "";
$store_opening_hours = "";
$open_always = "";
$store_phone = "";
$store_email = "";
$store_website = "";
$store_address_line1 = "";
$store_address_line2 = "";
$city = "";
$state = "";
$country = "";
$zip = "";
$latitude = "";
$longitude = "";
$marker_type = "";
$marker_icon = "";
$marker_color = "";
$marker_image = "";
$marker_size = "";
$featured = 0;
$is_active = 1;

//categories
$sql_c = "SELECT * FROM ".DB_BASE.".store_category where 1=1 AND user_detail_id = :user_id";
$data_c = array(':user_id' => $userid);
$categories =  CP_Select($sql_c,$data_c);
$categories_count = count($categories);

if((!empty($_POST)) && isset($_POST["btnsubmit"])){ 
	//print_r($_POST);print_r($_FILES);exit;
	if(isset($_POST["store_name"]))
	  {
		$store_category_id = trim($_POST["store_category_id"]);
		$store_name = trim($_POST["store_name"]);
		$store_description = trim($_POST["store_description"]);
		$tags = trim($_POST["tags"]);
		// $store_featured_image = trim($_POST["store_featured_image"]);
		$store_images = trim($_POST["store_images"]);
		$store_video = trim($_POST["store_video"]);
		$store_opening_hours = trim($_POST["store_opening_hours"]);
		//$open_always = trim($_POST["open_always"]);
		$store_phone = trim($_POST["store_phone"]);
		$store_email = trim($_POST["store_email"]);
		$store_website = trim($_POST["store_website"]);
		$store_address_line1 = trim($_POST["store_address_line1"]);
		$store_address_line2 = trim($_POST["store_address_line2"]);
		$city = trim($_POST["city"]);
		$state = trim($_POST["state"]);
		$country = trim($_POST["country"]);
		$zip = trim($_POST["zip"]);
		$latitude = trim($_POST["latitude"]);
		$longitude = trim($_POST["longitude"]);
		$marker_type = trim($_POST["marker_type"]);
		$marker_icon = trim($_POST["marker_icon"]);
		$marker_color = trim($_POST["marker_color"]);
		$marker_image = trim($_POST["marker_image"]);
		$marker_size = trim($_POST["marker_size"]);
		$featured = isset($_POST["featured"]) && ($_POST["featured"]=='1')?1:0;
		$is_active = isset($_POST["is_active"]) && ($_POST["is_active"]=='1')?1:0;		
		$dt1 = date("Y-m-d H:i:s");				
		if((isset($_GET["id"])) && ($_GET["id"]!="")){
			$id = $_GET["id"];
			
			$sql="SELECT * FROM ".DB_BASE.".store where store_id = :store_id  and user_detail_id = :id ";
			$data=array(':store_id'=>$id,':id'=>$userid);			
			$rowcount = CP_count($sql,$data);
			$sqlupdate = 'UPDATE '.DB_BASE.'.store set  
								store_category_id=:store_category_id,
								store_name=:store_name,
								store_description=:store_description, 
								tags=:tags, 
								store_featured_image=:store_featured_image, 
								store_images=:store_images, 
								store_video=:store_video, 
								store_opening_hours=:store_opening_hours, 								
								store_phone=:store_phone, 
								store_email=:store_email, 
								store_website=:store_website, 
								store_address_line1=:store_address_line1, 
								store_address_line2=:store_address_line2, 
								city=:city, 
								state=:state, 
								country=:country, 
								zip=:zip, 
								latitude=:latitude, 
								longitude=:longitude, 
								marker_type=:marker_type, 
								marker_icon=:marker_icon, 
								marker_color=:marker_color, 
								marker_image=:marker_image, 
								marker_size=:marker_size, 
								featured=:featured,
								is_active=:is_active,
								modify_date=:modify_date
							where store_id=:store_id AND user_detail_id=:user_id';				
			$data = array(':store_id'=>$id,
					':store_category_id'=>$store_category_id, 
					':store_name'=>$store_name, 
					':store_description'=>$store_description, 
					':tags'=>$tags, 
					':store_featured_image'=>$store_featured_image, 
					':store_images'=>$store_images, 
					':store_video'=>$store_video, 
					':store_opening_hours'=>$store_opening_hours, 					
					':store_phone'=>$store_phone, 
					':store_email'=>$store_email, 
					':store_website'=>$store_website, 
					':store_address_line1'=>$store_address_line1, 
					':store_address_line2'=>$store_address_line2, 
					':city'=>$city, 
					':state'=>$state, 
					':country'=>$country, 
					':zip'=>$zip, 
					':latitude'=>$latitude, 
					':longitude'=>$longitude, 
					':marker_type'=>$marker_type, 
					':marker_icon'=>$marker_icon, 
					':marker_color'=>$marker_color, 
					':marker_image'=>$marker_image, 
					':marker_size'=>$marker_size, 
					':featured'=>$featured,
					':is_active'=>$is_active,
					':modify_date'=>$dt1,					
					':user_id'=>$userid );
			CP_update($sqlupdate,$data);
			header("Location: list-store.php?mode=updated");		
		}else{
			$sql = "INSERT INTO ".DB_BASE.".store ( `user_detail_id`, `store_category_id`, `store_name`, `store_description`,`tags`, `store_featured_image`, `store_images`, `store_video`, `store_opening_hours`, `store_phone`, `store_email`, `store_website`, `store_address_line1`, `store_address_line2`, `city`, `state`, `country`, `zip`, `latitude`, `longitude`, `marker_type`, `marker_icon`, `marker_color`, `marker_image`, `marker_size`, `featured`, `is_active`, `create_date`) VALUES (:user_detail_id,:store_category_id,:store_name,:store_description,:tags,:store_featured_image,:store_images,:store_video,:store_opening_hours,:store_phone,:store_email,:store_website,:store_address_line1,:store_address_line2,:city,:state,:country,:zip,:latitude,:longitude,:marker_type,:marker_icon,:marker_color,:marker_image,:marker_size,:featured,:is_active,:create_date)";      	   
			$data = array( ':user_detail_id'=>$userid,							
							':store_category_id'=>$store_category_id,
							':store_name'=>$store_name,
							':store_description'=>$store_description,
							':tags'=>$tags,
							':store_featured_image'=>$store_featured_image,
							':store_images'=>$store_images,
							':store_video'=>$store_video,
							':store_opening_hours'=>$store_opening_hours,
							':store_phone'=>$store_phone,
							':store_email'=>$store_email,
							':store_website'=>$store_website,
							':store_address_line1'=>$store_address_line1,
							':store_address_line2'=>$store_address_line2,
							':city'=>$city,
							':state'=>$state,
							':country'=>$country,
							':zip'=>$zip,
							':latitude'=>$latitude,
							':longitude'=>$longitude,
							':marker_type'=>$marker_type,
							':marker_icon'=>$marker_icon,
							':marker_color'=>$marker_color,
							':marker_image'=>$marker_image,
							':marker_size'=>$marker_size,
							':featured'=>$featured,
							':is_active'=>$is_active,
							':create_date'=>$dt1);
			$store_id = CP_Insert($sql,$data);	
			//header("Location: list-store.php?mode=inserted");
		}		
	}
}
if((isset($_GET["id"])) && ($_GET["id"]!="")){
	$id = $_GET["id"];	
	$sql="SELECT * FROM ".DB_BASE.".store where store_id = :store_id  and user_detail_id = :userid ";
	$data=array(':store_id'=>$id,':userid'=>$userid);			
	$rows = CP_select($sql,$data);	
	$rowcount=count($rows);
	if($rowcount>0){
		foreach($rows as $row){
			$store_category_id = $row->store_category_id; 
			$store_name = $row->store_name; 
			$store_description = $row->store_description; 
			$tags = $row->tags; 
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
						<li class="breadcrumb-item active" aria-current="page">Add Store</li>
					</ol>
				</nav>
				<form class="cmxform" id="form" method="post" action="" >
					<fieldset>
				<div class="row">
					<div class="col-md-12 grid-margin stretch-card">
						<div class="card">
							<div class="card-body">
								<h4 class="card-title">Add Store</h4>
								
										<div class="form-group">
											<label for="name">Store Name</label>
											<input id="name" class="form-control" name="store_name" value="<?php echo $store_name; ?>" type="text" required>
										</div>
										<div class="form-group">
											<label for="store_description">Store Description</label>
											<textarea class="form-control" name="store_description"   id="store_description" rows="3" ><?php echo $store_description; ?></textarea>
										</div>

										<div class="form-group">
											<label>Category</label>
											<select class="js-example-basic-single w-100"  name="store_category_id">
												<option value="" selected="">Select Category</option>
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
										<div class="form-group">											
											<label for="tags">Tags</label>											
											<div>
												<input type="text" name="tags" id="tags" value="<?php echo $tags; ?>"/>
											</div>
										</div>
										<div class="form-group">
											<label for="store_opening_hours">Opening Hours</label>
											<textarea class="form-control" name="store_opening_hours"   id="store_opening_hours" rows="3" ><?php echo $store_opening_hours; ?></textarea>
										</div>

										<div class="row">
											<div class="col-sm-4">
										<div class="form-group">
											<label for="store_phone">Phone</label>
											<input id="store_phone" class="form-control" name="store_phone" value="<?php echo $store_phone; ?>" type="text"  >
										</div>
										</div>
										<div class="col-sm-4">
										<div class="form-group">
											<label for="email">Email</label>
											<input id="email" class="form-control"  name="store_email" value="<?php echo $store_email; ?>" type="text"  >
									</div>
									</div>
									<div class="col-sm-4">
									<div class="form-group">
										<label for="store_website">Website</label>
								<input id="store_website" class="form-control" name="store_website" value="<?php echo $store_website; ?>" type="text"  >
								</div>
								</div>
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
				<div class="row">
					<div class="col-md-12 grid-margin stretch-card">
						<div class="card">
							<div class="card-body">
								<h4 class="card-title">Location</h4>
								<div class="row">
									<div class="col-sm-6">
										<div class="form-group">
											<label for="store_address_line1">Address Line 1 <span class="required_star">*</spa></label>
											<input id="store_address_line1" class="form-control" name="store_address_line1" value="<?php echo $store_address_line1; ?>" type="text"   required>
										</div>
									</div>
									<div class="col-sm-6">
										<div class="form-group">
											<label for="store_address_line2">Address Line 2</label>
											<input id="store_address_line2" class="form-control" name="store_address_line2" value="<?php echo $store_address_line2; ?>" type="text"  >
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-3">
										<div class="form-group">
											<label for="city">City <span class="required_star">*</spa></label>
											<input id="city" class="form-control" name="city" value="<?php echo $city; ?>" type="text"   required>
										</div>
									</div>										
									<div class="col-sm-3">
										<div class="form-group">
											<label for="state">State <span class="required_star">*</spa></label>
											<input id="state" class="form-control" name="state" value="<?php echo $state; ?>" type="text"   required>
										</div>
									</div>										
									<div class="col-sm-3">
										<div class="form-group">
											<label for="country">Country  <span class="required_star">*</spa></label>
											<input id="country" class="form-control" name="country" value="<?php echo $country; ?>" type="text"   required>
										</div>
									</div>										
									<div class="col-sm-3">
										<div class="form-group">
											<label for="zip">Zip <span class="required_star">*</spa></label>
											<input id="zip" class="form-control" name="zip" value="<?php echo $zip; ?>" type="text"   required>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-6">
										<div class="form-group">
											<label for="latitude">Latitude</label>
											<input id="latitude" class="form-control" name="latitude" value="<?php echo $latitude; ?>" type="text"  >
										</div>
									</div>										
									<div class="col-sm-6">
										<div class="form-group">
											<label for="longitude">Longitude</label>
											<input id="longitude" class="form-control" name="longitude" value="<?php echo $longitude; ?>" type="text"  >
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12 grid-margin stretch-card">
						<div class="card" id="accordion">
							<div class="card-body">
								<h4 class="card-title">Media
									<a href="#add_video_collapse" data-toggle="collapse"  role="button" aria-expanded="false" aria-controls="add_video_collapse" class="ml-2 btn btn-primary btn-icon-text float-right" class="ml-3 btn btn-primary btn-icon-text float-right">	
										<i class="btn-icon-prepend" data-feather="video"></i>
										Add Video
									</a>									
									<a href="#add_images_collapse" data-toggle="collapse"  role="button" aria-expanded="false" aria-controls="add_images_collapse" class="btn btn-primary btn-icon-text float-right">	
										<i  class="btn-icon-prepend" data-feather="image"></i>
										Add Images
									</a>
								</h4>
								<div class="collapse" id="add_images_collapse"  data-parent="#accordion">
									<div class="form-group">
										<label>Store Images</label>
										<input type="file" class="file-upload-default" name="store_images_box[]" data-name="store_images" multiple> 
										<div class="input-group col-xs-12">
											<input type="text" class="form-control file-upload-info" disabled="" placeholder="Upload Image">												
											<span class="input-group-append">
												<button class="file-upload-browse btn btn-primary" type="button">Upload</button>
											</span>
										</div>	
										<input type="hidden" class="form-control" name="store_images"  value='<?php echo $store_images; ?>'>
									</div>
								</div>
								<div class="collapse" id="add_video_collapse"  data-parent="#accordion">
									<div class="form-group">
										<label for="store_video">Video (Youtube Link)</label>
										<div class="input-group col-xs-12">
											<input  id="store_video" class="form-control" name="store_video" value="<?php echo $store_video; ?>" type="text"   placeholder="Enter Youtube Video URL">	
											<span class="input-group-append">
												<button class="file-upload-browse btn btn-primary add-video-youtube" type="button">Add</button>
											</span>
										</div>
										<!-- <input id="store_video" class="form-control" name="store_video" value="<?php echo $store_video; ?>" type="text"  > -->
									</div>
								</div>
								<ul class="store_images_box uploaded_img_box" id="sortable">										
									<?php //echo  $store_images;
										  $store_images = json_decode($store_images, true);
										  
										  
										if(!empty($store_images) && isset($store_images)){
										foreach($store_images as $store_image ){ 										
										if(!empty($store_image) && $store_image['type']=='image'){?>
											<li class="uploaded_img_span"  data-type="image"  data-name="<?php echo $store_image['name']; ?>"><img src="<?php echo SITE_URL."client-area/upload/small/".$store_image['name']; ?>"><span data-remove="<?php echo $store_image['name']; ?>" remove-from="store_images" class="remove_img"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></span></li>
											<?php
											}else if(!empty($store_image) && $store_image['type']=='video-youtube'){ ?>
												<li class="uploaded_img_span youtubevideo" data-type="video-youtube" data-name="<?php echo $store_image['name']; ?>"><img src="//img.youtube.com/vi/<?php echo $store_image['name']; ?>/0.jpg"><span data-remove="<?php echo $store_image['name']; ?>" remove-from="store_images" class="remove_img"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg><svg class="youtube_icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-youtube"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"></path><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"></polygon></svg></span></li>
											<?php }
										}													
									} ?>											
								</ul>
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
							<a type="button" class="btn" href="list-store.php?<?php echo $URLBack; ?>">Back</a>
							</div>
						</div>
					</div>
				</div>
					</fieldset>

					<div class="progress">
    					<div class="progress-bar"></div>
					</div>

				<div id="uploadStatus"></div>
				</form>
			</div>

<script>
$( function() {
	$( "#sortable" ).sortable();
	$( "#sortable" ).disableSelection();
});
$('#sortable').sortable({
	update: function(event, ui){		
		var getChild = $(this).children('.uploaded_img_span');
		var imgdata = "[";
		var cnt = 1;
		getChild.each(function(i,v){			
			var data_name = $(this).attr('data-name');
			var data_type = $(this).attr('data-type');
			
			if(data_name != undefined && cnt == 1){
				imgdata = imgdata + '{"name":"'+data_name+'","type":"'+data_type+'"}';
				cnt = cnt+1;
			}else if(data_name != undefined){
				imgdata = imgdata +","+ '{"name":"'+data_name+'","type":"'+data_type+'"}';
			}
		});
		imgdata = imgdata + ']';
		$("input[name='store_images']").val(imgdata);				
   }
});
</script>  
<script>
//add video
$(document).ready(function(){
	$('.add-video-youtube').click(function(){
		var newval = '',
		$this = $('input[name=store_video]');
		var video_id = '';
		if (newval = $this.val().match(/(\?|&)v=([^&#]+)/)) {
			video_id = newval.pop();
			videoRemovePrevious();		
			addVideo(video_id);			
		} else if (newval = $this.val().match(/(\.be\/)+([^\/]+)/)) {
			video_id = newval.pop();
			videoRemovePrevious();		
			addVideo(video_id);
		} else if (newval = $this.val().match(/(\embed\/)+([^\/]+)/)) {
			video_id = newval.pop();
			videoRemovePrevious();		
			addVideo(video_id);
		}else if($this.val().length == 11){			
			var img = new Image();	
			img.src = "http://img.youtube.com/vi/" + $this.val() + "/mqdefault.jpg";
			img.onload = function () {
				if (this.width != 120) {
					video_id = $this.val();
					videoRemovePrevious();
					addVideo(video_id);
				}				
			}			
		}
	});
});
//append video
function addVideo(video_id){	
	var video_thumbnail = '<img src="//img.youtube.com/vi/'+video_id+'/0.jpg">';   			
	var img_ele = '<li class="uploaded_img_span youtubevideo" data-type="video-youtube" data-name="'+video_id+'"><span data-remove="'+video_id+'" remove-from="store_images_box" class="remove_img"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg><svg class="youtube_icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-youtube"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"></path><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"></polygon></svg></span></li>';
	$('.store_images_box').append(img_ele);
	$('.youtubevideo').append(video_thumbnail);
	$("input[name=store_video]").val(video_id);
	var current_data = $("input[name=store_images]").val();
	if (!current_data.trim()) {
	}else{					
		var current_data = JSON.parse(current_data);					
		var filename = '[{"name":"'+video_id+'","type":"video-youtube"}]';
		var filename = JSON.parse(filename);					
		var aaa = 	$.merge(current_data , filename );
		var filename = JSON.stringify(aaa);										
	}			
	$("input[name=store_images]").val(filename);
}
//remove previous videos on new add
function videoRemovePrevious(){
	var img_data = $("input[name='store_images']").val();
	var img_data = JSON.parse(img_data);
	jQuery.each(img_data, function(i, val) {
			if(val.type == 'video-youtube'){
   				delete img_data[i];
			}
	});
	img_data = img_data.filter(function(n){ return n }); 
	var img_data = JSON.stringify(img_data);
	$("input[name='store_images']").val(img_data);
	$('.store_images_box .youtubevideo').remove();
}
//remove image	
function deleteImage(){
	$('.remove_img').click(function(){			
		var data_remove = $(this).attr('data-remove'); 
		var remove_from = $(this).attr('remove-from');
		var img_data = $("input[name='"+remove_from+"']").val();			
		var img_data = JSON.parse(img_data);		
		jQuery.each(img_data, function(i, val) {
			if(val.name == data_remove){
   				delete img_data[i];
				if(val.type=="video-youtube"){$("#store_video").val("");}
			}
		});		
		img_data = img_data.filter(function(n){ return n }); 			
		var img_data = JSON.stringify(img_data);
		$("input[name='"+remove_from+"']").val(img_data);		
		$(this).closest(".uploaded_img_span").remove();
	});
}

$(document).ready(function(){
	deleteImage();
});
</script>
<script>
//form validation
$(function() {    
    $("#form").validate({
      rules: {                
        store_email: {
			required:false,
          	email: true
        }
      },
      messages: {              
        store_email: "Please enter a valid email address",
      },
      errorPlacement: function(label, element) {
        label.addClass('mt-2 text-danger');
        label.insertAfter(element);
      },
      highlight: function(element, errorClass) {
        $(element).parent().addClass('has-danger')
        $(element).addClass('form-control-danger')
      }
    });
  });
</script>
<script>
//upload images start
$(document).ready(function(){

	$('input[type="file"]').change(function(e){	
		var attr = $(this).attr('multiple');		
		var frm  = $(this).parents("form");
		var frmData = new FormData(frm[0]);
		var field_name = $(this).attr('name');		
		frmData.append('action', 'imageUpload');
		frmData.append('field_name', field_name);
		if (typeof attr !== typeof undefined && attr !== false) {
			frmData.append("multiple", "yes");			
		}else{
			frmData.append("multiple", "no");
		}
		var  current_data = $("input[name='"+field_name.replace('_box','').replace('[]','')+"']").val();		
		frmData.append('current_data', current_data);		
		e.preventDefault();		
        $.ajax({
            xhr: function() {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function(evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = ((evt.loaded / evt.total) * 100);
                        $(".progress-bar").width(percentComplete + '%');
                        $(".progress-bar").html(percentComplete+'%');
                    }
                }, false);
                return xhr;
            },
            url:"ajaxcall.php",		    	
	    	type:"POST",
			data:frmData,
			contentType: false,
            cache: false,
            processData:false,
			responseType : 'json',
            beforeSend: function(){
                $(".progress-bar").width('0%');                
            },
            error:function(){
                $('#uploadStatus').html('<p style="color:#EA4335;">File upload failed, please try again.</p>');
            },
            success: function(resp){
				var obj = JSON.parse(resp);				
				var filename = obj.file;				
				var filename_arr = JSON.parse(filename);
				var field_name = obj.field_name;				
				var single_img_name = obj.single_img_name;
				var current_data = obj.current_data;												
				if(obj.multiple == 'yes'){
					if (!current_data.trim()) {
						
					}else{
						var current_data = JSON.parse(current_data);					
						var filename = JSON.parse(filename);
						var aaa = 	$.merge(current_data , filename );
						var filename = JSON.stringify(aaa);										
						
					}
					$("input[name='"+field_name.replace('_box','')+"']").val(filename);
					$.each(filename_arr, function( index, value ) {
						console.log(value);
						
						var img_ele = '<li class="uploaded_img_span" data-type="image" data-name="'+value.name+'"><img src="'+'<?php echo SITE_URL."client-area/upload/small/"; ?>'+value.name+'"><span data-remove="'+value.name+'" remove-from="'+field_name.replace('_box','')+'" class="remove_img"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></span></li>';
						$("."+field_name).append(img_ele);
						deleteImage();
					});
				}else{
					$("input[name='"+field_name.replace('_box','')+"']").val(filename);
					var img_ele = '<li class="uploaded_img_span"  data-type="image" data-name="'+single_img_name+'"><img src="'+'<?php echo SITE_URL."client-area/upload/small/"; ?>'+single_img_name+'"><span data-remove="'+single_img_name+'" remove-from="'+field_name.replace('_box','')+'" class="remove_img"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></span></li>';
					$("."+field_name).empty();
					$("."+field_name).append(img_ele);
					deleteImage();					
				}
            }
        });	 
	 });	  
});
</script>

<?php require_once('footer.php'); ?>