<?php  
require_once('../config.php');
require_once('header-none.php');

if((!empty($_POST)) && isset($_POST["btnsubmit"])){
  /*Database Connection*/  
  global $db;	  
	if(isset($_POST["email"]) && $_POST["email"]!="" 
		&& isset($_POST["password"]) && $_POST["password"]!=""
	)
	{
    
    $email = trim($_POST["email"]);		
    $fname = trim($_POST["fname"]);		
    $lname = trim($_POST["lname"]);		    
		$password = trim($_POST["password"]);
		$isactive = 1;		
		/*Check User Login Email Is Same Or Not Code*/
		
	
    $sql ="SELECT count(user_email) FROM ".DB_MAIN.".user_detail where user_email = :user_email";
    $data = array(':user_email'=>$email);
    
    $rowcount  = CP_Count($sql,$data);
	  $ValidUserId = true;	
		if($rowcount>0){
			$ValidUserId = false;
		}    		
		$dt1 = date("Y-m-d H:i:s");
		if($ValidUserId){
      /*Login Details & Contact Details Data Add In Table userdetail.*/
      $sql = "INSERT INTO ".DB_MAIN.".user_detail (`user_email`, `user_password`, `user_first_name`, `user_last_name`, `is_active`, `create_date`) VALUES (:user_email, :user_password, :user_first_name, :user_last_name, :is_active,:create_date)";           
      $data = array(':user_email'=>$email,
                    ':user_password'=>$password,
                    ':user_first_name'=>$fname,
                    ':user_last_name'=>$lname,
                    ':is_active'=>$isactive,
                    ':create_date'=>$dt1);
      $userid = CP_Insert($sql,$data);		

			$_SESSION['userid'] = $userid;

			header("Location: index.php?newaccount=success");		
		}
		else{
			$errorAuth = "userexist";
		}	
	}
}

?>

				<div class="row w-100 mx-0 auth-page">
					<div class="col-md-8 col-xl-6 mx-auto">
						<div class="card">
							<div class="row">
                <div class="col-md-4 pr-md-0">
                  <div class="">
                      
                  </div>
                </div>
                <div class="col-md-8 pl-md-0">
                  <div class="auth-form-wrapper px-4 py-5">
                    <a href="#" class="noble-ui-logo d-block mb-2">Noble<span>UI</span></a>
                    <h5 class="text-muted font-weight-normal mb-4">Create a free account.</h5>
                    <form class="forms-sample" action="" method="post" id="signupForm">
                      <div class="form-group">
                        <label for="fname">First Name</label>
                        <input type="text" name="fname" class="form-control" id="fname" autocomplete="Username" placeholder="First Name" required>
                      </div>
                      <div class="form-group">
                        <label for="lname">Last Name</label>
                        <input type="text" name="lname" class="form-control" id="lname" autocomplete="Username" placeholder="Last Name" required>
                      </div>
                      <div class="form-group">
                        <label for="exampleInputEmail1">Email address</label>
                        <input type="email" name="email" class="form-control" id="exampleInputEmail1" placeholder="Email" required>
                      </div>
                      <div class="form-group">
                        <label for="exampleInputPassword1">Password</label>
                        <input type="password" name="password" class="form-control" id="exampleInputPassword1" autocomplete="current-password" placeholder="Password" required>
                      </div>
                      <div class="form-check form-check-flat form-check-primary">
                        <label class="form-check-label">
                          <input type="checkbox" class="form-check-input">
                          Remember me
                        </label>
                      </div>
                      <div class="mt-3">
                        <button type="submit" name="btnsubmit" class="btn btn-primary mr-2 mb-2 mb-md-0">Sing up</button>
                        
                        <button type="button" class="btn btn-outline-primary btn-icon-text mb-2 mb-md-0">
                          <i class="btn-icon-prepend" data-feather="twitter"></i>
                          Sign up with twitter
                        </button>
                      </div>
                      <a href="index.php" class="d-block mt-3 text-muted">Already a user? Sign in</a>
                    </form>
                  </div>
                </div>
              </div>
						</div>
					</div>
				</div>
<?php require_once('footer-none.php'); ?>