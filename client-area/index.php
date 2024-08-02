<?php 
require_once('../config.php');
require_once('header-none.php'); 


if(isset($_SESSION['userid']) && !empty($_SESSION['userid'])){
	$redirect="2fa.php";
	header("location: $redirect"); 
	
}
if(isset($_COOKIE['member_login']) && isset($_COOKIE['member_password'])){ 
	$_REQUEST['login'] = "Login";
	$_POST['username'] = $_COOKIE['member_login'];
	$_POST['password'] = $_COOKIE['member_password'];
}

if(isset($_REQUEST['login'])){
	global $db;
	$eroare=0;
	if(isset($_POST['email'])){
		$u_name = trim($_POST['email']);
		$pass = trim($_POST['password']);
		if(isset($_POST['rememberme']) && $_POST['rememberme']=='on'){
			$rememberme = 1;
		
	
				$sql = "SELECT *,user_email as adminemail  FROM ".DB_MAIN.".user_detail WHERE user_email = :u ";	
				$params = array(":u" => $u_name);
				
				$rows = CP_Select($sql,$params);		
				$rowcount = count($rows);	
				if($rowcount>0){
					foreach($rows as $rs){
						$dbpass = $rs->user_password;			
						$fname = $rs->user_first_name;
						$lname = $rs->user_last_name;
						$email = $rs->user_email;
						$userdetail_id =  $rs->user_detail_id;
						$isactive =  $rs->is_active;			
					}
					if($isactive !="" && $isactive == 0 ) {
						header("location: index.php?status=inactive");
						exit;
					}
					if($dbpass == $pass )
					{
						if($rememberme == 1)
						{
							setcookie ("member_login",$u_name,time()+ (86400 * 30));
							setcookie ("member_password",$dbpass,time()+ (86400 * 30));
						}
						
						$sqlupdate = 'UPDATE '.DB_MAIN.'.user_detail set  lastLogin=DATE(Y-m-d H:i:s) where user_detail_id=:user_detail_id';
						$preparedStatement = $db->prepare($sqlupdate);
						$preparedStatement->execute(array( ':user_detail_id' => $userdetail_id));
				
						$_SESSION['userid_check'] = $userdetail_id;
						$_SESSION['user_name'] = $fname." ".$lname; /*
						$_SESSION['user_email'] = $email;  */
						
						$redirect="2fa.php";
						header("location: $redirect"); 
					}
					else{
						$errorAuth = "Invalid password";
					}
				}
				else{
					$errorAuth = "Invalid User";
				}

	    }else{
			$rememberme = 0;

			$sql = "SELECT *,user_email as adminemail  FROM ".DB_MAIN.".user_detail WHERE user_email = :u ";	
				$params = array(":u" => $u_name);
				
				$rows = CP_Select($sql,$params);		
				$rowcount = count($rows);	
				if($rowcount>0){
					foreach($rows as $rs){
						$dbpass = $rs->user_password;			
						$fname = $rs->user_first_name;
						$lname = $rs->user_last_name;
						$email = $rs->user_email;
						$userdetail_id =  $rs->user_detail_id;
						$isactive =  $rs->is_active;			
					}
					if($isactive !="" && $isactive == 0 ) {
						header("location: index.php?status=inactive");
						exit;
					}
					if($dbpass == $pass )
					{
						
						$sqlupdate = 'UPDATE '.DB_MAIN.'.user_detail set  lastLogin=DATE(Y-m-d H:i:s) where user_detail_id=:user_detail_id';
						$preparedStatement = $db->prepare($sqlupdate);
						$preparedStatement->execute(array( ':user_detail_id' => $userdetail_id));
				
						$_SESSION['userid_check'] = $userdetail_id;
						$_SESSION['user_name'] = $fname." ".$lname; /*
						$_SESSION['user_email'] = $email;  */
						
						$redirect="2fa.php";
						header("location: $redirect"); 
					}
					else{
						$errorAuth = "Invalid password";
					}
				}
				else{
					$errorAuth = "Invalid User";
				}
		}	
	}
}

?>
<div class="row w-100 mx-0 auth-page">
    <div class="col-md-8 col-xl-6 mx-auto">
        <div class="card">
            <div class="row">
                <div class="col-md-4 pr-md-0">
                    <div class="auth-left-wrapper">

                    </div>
                </div>
                <div class="col-md-8 pl-md-0">
                    <div class="auth-form-wrapper px-4 py-5">
                        <a href="#" class="noble-ui-logo d-block mb-2">Wed &<span> Nik</span></a>
                        <h5 class="text-muted font-weight-normal mb-4">Welcome back! Log in to your account.</h5>
                        <?php if(isset($errorAuth) && $errorAuth != ""){ ?>
                        <div class="text-danger mb-4"><?php echo $errorAuth; ?></div>
                        <?php } ?>
                        <form class="forms-sample" action="" method="post" id="loginForm">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Email address</label>
                                <input type="email" name="email" class="form-control" id="exampleInputEmail1"
                                    placeholder="Email"
                                    value="<?php if(isset($_POST['email'])){ echo $_POST['email']; } ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Password</label>
                                <input type="password" name="password" class="form-control" id="exampleInputPassword1"
                                    autocomplete="current-password" placeholder="Password" required>
                            </div>
                            <!-- <div class="form-check form-check-flat form-check-primary">
                                <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" name="rememberme">
                                    Remember me
                                </label>
                            </div> -->
                            <div class="mt-3">
                                <button type="submit" name="login"
                                    class="btn btn-primary mr-2 mb-2 mb-md-0">Login</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once('footer-none.php'); ?>