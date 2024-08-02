<div class="row">
	<div class="col-md-12 grid-margin stretch-card">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title">Marker</h4>
				<div class="form-group">
					<label for="marker_type">Marker Type</label>
					<select class="form-control" id="marker_type" name="marker_type" >
						<option value="default" <?php if($marker_type=="default"){echo "selected";} ?>>Default</option>	
						<option value="icon" <?php if($marker_type=="icon"){echo "selected";} ?> >Icon</option>
						<option value="image"  <?php if($marker_type=="image"){echo "selected";} ?>  >Image</option>
					</select>
				</div>
				<div class="form-group icon marker">
					<label for="marker_icon">Marker Icon</label>
					<input id="marker_icon" class="form-control" type="text" name="marker_icon"  value="<?php echo $marker_icon; ?>" >
				</div>                                        
                <div class="form-group icon marker">
   					<label>Icon Color:</label>
				    <div id="cp1" class="input-group mb-4" title="Using input value">
					    <input type="text" class="form-control input-lg"  name="marker_color"  value="<?php echo $marker_color; ?>" />
					    <span class="input-group-append">
						    <span class="input-group-text colorpicker-input-addon"><i></i>
                            </span>
					    </span>
				    </div>
                </div>                                  
				<div class="form-group icon marker">
					<label for="marker_size">Maker Size</label>
					<input type="text" class="form-control" id="marker_size" placeholder="" name="marker_size"  value="<?php echo $marker_size; ?>" >
				</div>
				<div class="form-group image marker">
					<label>Upload Image</label>
					<input type="file" class="file-upload-default" data-name="marker_image" name="marker_image_box"> 
					<div class="input-group col-xs-12">
						<input type="text" class="form-control file-upload-info" disabled="" placeholder="Upload Image">						
						<span class="input-group-append">
							<button class="file-upload-browse btn btn-primary" type="button">Upload</button>
						</span>
					</div>					
					<input type="hidden" class="form-control" name="marker_image" value='<?php echo $marker_image; ?>'>
					<ul class="marker_image_box uploaded_img_box">
					<?php 
					$marker_image = json_decode($marker_image, true);					
					 if(!empty($marker_image) && isset($marker_image)){ ?>
					<li class="uploaded_img_span"   data-type="image"  data-remove-x="<?php echo $marker_image[0]['name']; ?>"><img src="<?php echo SITE_URL."client-area/upload/small/".$marker_image[0]['name']; ?>"><span data-remove="<?php echo $marker_image[0]['name']; ?>" remove-from="marker_image" class="remove_img"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></span></li>
					<?php	} ?>
					</ul>
				</div>					
			</div>
		</div>
	</div>
</div>
<script>
$('.marker').hide();
var marker_type = $('#marker_type').val();
$('.'+marker_type).show();
$('#marker_type').change(function () {
    $('.marker').hide();    
    $('.' + this.value).show();
});
</script>