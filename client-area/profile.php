<?php
  $title = "Update Profile";
  require ('../config.php');
  require ('./header.php');
    $aid = $_SESSION['userid'];
    $query = "SELECT * FROM ".DB_MAIN.".`user_detail` WHERE `user_id` = :aid";
    $result = $db->prepare($query);
    $result->execute(array(':aid'=>$aid));
    $fetch = $result->fetchAll(PDO::FETCH_OBJ);
    $count = count($fetch);
    if ($count > 0) {
        foreach ($fetch as $value) {
        $user_email = $value->user_email;
        $email = $value->email;
        $number = $value->user_first_name;
        $register_id = $value->user_last_name;
        $logo1 = $value->employee_pic;
        $password = $value->password;
        
        }
    }
    

  try {
    if (isset($_POST['submit'])) {
   
      
      $email = $_POST['email'];
      $number = trim($_POST['number']);
      $password = trim($_POST['password']);
      if(!isset($password)||$password==''){
        
        $hash_password = $_SESSION['auth_password'];  
      }
      else{
          $hash_password = hash('sha256', $password);
      }
        
      
      if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {

          $logo = $_FILES['image']['name'];
          $logo_tmp = $_FILES['image']['tmp_name'];
          $type = pathinfo($logo, PATHINFO_EXTENSION);
          if (in_array($type, ['jpg', 'jpeg', 'png', 'gif'])) {
              if(isset($_SESSION['login_type']) && $_SESSION['login_type'] != 'employee'){
                $img = $aid . "_" . "." . $type;
              }else{
                $img = $emp_id . "_" . "." . $type;
              }
             
              $target_path = $file_desti . $img;
      
              if (move_uploaded_file($logo_tmp, $target_path)) {
                  $add = 'image_added';
              } else {
                  echo '<script>alert("upload_failed")</script>';
              }
          }
      } 
      
      $update_img = '';
      if($_POST['hidden_img']!="")
      { 
        $update_img = $_POST['hidden_img'];
       }
      else if($img!=""){
         $update_img = $img ;
       }

      if (isset($aid) && $aid != '' && isset($emp_id) && $emp_id == 0) {

            $query = "SELECT * FROM  `registration` WHERE `register_id`=:aid AND `email`=:email";
            $result1 = $conn->prepare($query);
            $result1->execute(array(':email'=>$email,':aid'=>$aid));
            if($result1){
                $update = "UPDATE `registration` SET `phone`=:phone,`logo`=:logo,`password`=:password,`modifydate`=now() WHERE `register_id`=:aid";
                $result = $conn->prepare($update);
                $result->execute(array(':phone'=>$number,':logo'=>$update_img,':password'=>$hash_password,':aid'=>$aid,));
                if($result){
                  if(isset($hash_password) && $hash_password!='' ){
                    setcookie("admin_password", $hash_password, time() + (86400 * 30));   
                  }
                }
                $url=BASE_URL.'dashboard.php?action=profile-update';
                header("Location: $url");
            }
    
          exit();
        } 
      else {
    $query = "SELECT * FROM `employee` WHERE `employee_id`=:emp_id AND `email`=:email AND `user_id`=:aid";
    $result1 = $conn->prepare($query);
    $result1->execute(array(':email' => $email, ':aid' => $aid, ':emp_id' => $emp_id));
    if ($result1) {
        $update = "UPDATE `employee` SET `employee_pic`=:employee_pic, `phone`=:phone, `password`=:password, `modifydate`=now() WHERE `user_id`=:aid AND `employee_id`=:emp_id";
        $result = $conn->prepare($update);
        $result->execute(array(':employee_pic' => $update_img, ':phone' => $number, ':aid' => $aid, ':password' => $hash_password, ':emp_id' => $emp_id));
        if ($result) {
            if (isset($hash_password) && $hash_password != '') {
                setcookie("employee_password", $hash_password, time() + (86400 * 30));
            }
        }
        $url = BASE_URL . 'dashboard.php?action=profile-update';
        header("Location: $url");
    }
    exit();
}

    }
  } catch (PDOException $e) {
    echo $e->getMessage();
  }



?>
<?php
  include ('header.php');
?>
    <div class="page-wrapper">
      <div class="page-content">
        <nav class="page-breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard  </a></li>
            <li class="breadcrumb-item active" aria-current="page">Update settings</li>
          </ol>
        </nav>
        <div class="row">
          <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h6 class="card-title">Update settings</h6>
  
                <form class="forms-sample" method="post" id="form_add_emp" enctype="multipart/form-data">
                  <div class="form-group">
                    <input type="phone" id="" name="number1" class="form-control" value="<?php echo $number1; ?>" disabled>
                    <p id="phonecheck"></p>
                  </div>

                  <div class="form-group">
                  <input type="hidden" id="imagename" value=''/>
                  <input type="hidden" id="eid" value='<?php echo  $id; ?>'/>
                    <label for="name">Name</label>
                    <input type="text" class="form-control" value="<?php echo $name; ?>" disabled>
                    <p id="namecheck"></p>
                  </div>
                  <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" class="form-control" name="email" value="<?php echo $email; ?>" disabled>
                    <p id="emailcheck"></p>
                  </div>

                  <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" autocomplete="new-password" name="password" class="form-control" value="" id="password" placeholder="Enter New Password">
                  </div>
                  
                  <div class="form-group">
                    <label for="phone">Contact Number</label>
                    <input type="number" name="number" class="form-control" id="phone" placeholder="number" value="<?php echo $number; ?>" required>
                    <p id="phonecheck"></p>
                  </div>
                  
                  <div class="form-group">
                          <label for="adminPic">Profile Pic</label>
                          <input type="hidden" name="hidden_img" value="<?php echo $logo1; ?>">
                          <input type="file" id="employeePic" placeholder="employeePic" value="<?php echo $file_desti.$logo1; ?>" name="image" class="form-control"  style="margin-bottom: 5px">
                          <span id="file_error" style="color: red;"></span>
                            <?php if(isset($logo1) && $logo1!=''){?>
                            <img src="<?php echo $file_desti.$logo1; ?>" id="blah" alt="Profile" class="img-thumbnail rounded" style="max-width: 100%; max-height: 100px;">
                          <?php }else { ?>
                            <img src="images/user.jpg" id="blah" alt="Profile" class="img-thumbnail rounded" style="max-width: 100%; max-height: 100px;">
                          <?php } ?>
                          <input type="hidden" name="profilePic" class="form-control" id="profilePic" value="">
                   
                          <p id="deductcheck"></p>
                    </div>
                  <button type="submit" name="submit" class="btn btn-primary mr-2" id="subbtn">Submit</button>
                  <a href="dashboard.php" class="btn btn-default">back</a>
                </form>
              </div>
            </div>
          </div>
        </div> <!-- row -->

      </div>
    </div>
    <div class="modal fade" id="profilePicModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
			  	<div class="modal-dialog modal-lg" role="document">
			    	<div class="modal-content">
			      		<div class="modal-header">
			        		<h5 class="modal-title">Crop Image Before Upload</h5>
			        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			          			<span aria-hidden="true">×</span>
			        		</button>
			      		</div>
			      		<div class="modal-body">
			        		<div class="img-container">
			            	 
			                		<div class=" imgdiv"  >
			                    		<img src="" id="sample_image" />
			                		</div>
			        		</div>
			      		</div>
			      		<div class="modal-footer">
			      			<button type="button" id="crop" class="btn btn-primary">Crop</button>
			        		<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
			      		</div>
			    	</div>
			  	</div>
			</div>

    <script>      
      
    

//----------------------------------------------------------------//

$(function() {
        'use strict';

        $('#datetimepickerExample').datetimepicker({
          format: 'LT'
        });
      });

	    $(document).ready(function() {
	      $("#form_add_emp").validate({
          rules:{
            password:{
              maxlength:25
            }, 
            employeePic: {
              extension: "png|jpeg|jpg",
              filesize: 5000000
            }
          },
          messages: {
            employeePic: "File must be in proper formate, less than 5MB" 
          }
        });
        $.validator.addMethod('extension', function(value, element, param) {
          var okextension = ["png","jpeg","jpg","JPG"];
          var extension = value.split('.').pop();
              if (this.optional(element) ||okextension.indexOf(extension) !== -1) {
                return true;
              }
              else 
              {
                return false;
              }
        });
        $.validator.addMethod('filesize', function(value, element, param) {

            return this.optional(element) || (element.files[0].size <= param) 
        });

    var $modal = $('#profilePicModal');

	var image = document.getElementById('sample_image');

	var cropper;

	$('#employeePic').change(function(event){
		var files = event.target.files;

		var done = function(url){
			image.src = url;
			$modal.modal('show');
		};

		if(files && files.length > 0)
		{
			reader = new FileReader();
			reader.onload = function(event)
			{
				done(reader.result);
			};
			reader.readAsDataURL(files[0]);
		}
    var  imgName = files[0].name ;

    $("#imagename").val(imgName);
	});

	$modal.on('shown.bs.modal', function() {
		cropper = new Cropper(image, {
		viewMode: 1,
    dragMode: 'move',
    aspectRatio: 1
		});
	}).on('hidden.bs.modal', function(){
		cropper.destroy();
   		cropper = null;
	});

	$('#crop').click(function(){
		canvas = cropper.getCroppedCanvas({
			width:400,
			height:400
		});

		canvas.toBlob(function(blob){
			url = URL.createObjectURL(blob);
			var reader = new FileReader();
			reader.readAsDataURL(blob);
			reader.onloadend = function(){
				var base64data = reader.result;

      
        var id = $("#eid").val();
        var imgname =  $("#imagename").val();
        ext = imgname.split('.').pop();
        
				$.ajax({
          url:'pic_upload.php',
					method:'POST',
					data:{image:base64data,
          id :id,
          ext :ext
          },
					success:function(data)
					{
						$modal.modal('hide');
						$('#profilePic').attr('value', data);
					}
				});
			};
		});
	});
	
	});


  $(document).ready(function() {
    $("#employeePic").change(function() {
        var input = this;
        var image = $("#blah")[0];
        var file = input.files[0];
        var fileName = file.name;
        var allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        
        var fileExtension = fileName.split('.').pop().toLowerCase();
        if (file) {
            if (allowedExtensions.indexOf(fileExtension) === -1) {
                  Swal.fire({
                  icon: "error",
                  title: "Oops...",
                  text: "Invalid file type. Please choose a valid image file.!",
                  });
            } else {            
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        image.src = e.target.result;
                    };
                    reader.readAsDataURL(input.files[0]);
                } else {
                    image.src = ""; 
                }
            }
        }
    });
});



    </script>
<?php
  include ('footer.php');
?>    