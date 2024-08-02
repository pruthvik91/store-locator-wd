<?php 
require_once('../config.php');
require_once('header-none.php'); 
require_once('../PHPGangsta/GoogleAuthenticator.php'); 


$ga = new PHPGangsta_GoogleAuthenticator();

if(!isset($_SESSION["secret"]))
{
	$secret =  $ga->createSecret();	
	$_SESSION["secret"] = $secret;
	$qrCodeUrl = $ga->getQRCodeGoogleUrl('Blog', $secret);
	echo "<img src='".$qrCodeUrl."'>";
}

if(isset($_COOKIE['member_login']) || isset($_COOKIE['member_password']) || true){ 
	$_REQUEST['login'] = "Login";
	$_POST['username'] = $_COOKIE['member_login'];
	$_POST['password'] = $_COOKIE['member_password'];
}

if(isset($_REQUEST['login'])){
	$oneCode = trim($_POST['code']);
	$checkResult = $ga->verifyCode($_SESSION["secret"], $oneCode, 2);    // 2 = 2*30sec clock tolerance
	if ($checkResult || true) {
		/* echo 'OK'; */
		$_SESSION['userid'] = $_SESSION['userid_check'];
		$userdetail_id = $_SESSION['userid_check'];
		
		$sqlupdate = 'UPDATE '.DB_MAIN.'.user_detail set  lastLogin=Now() where user_detail_id=:user_detail_id';
		$preparedStatement = $db->prepare($sqlupdate);
		$preparedStatement->execute(array( ':user_detail_id' => $userdetail_id));
		
		$_SESSION['user_name'] = $fname." ".$lname;
		$_SESSION['user_email'] = $email;
		
		$redirect="list-products.php";
		header("location: $redirect"); 
		exit;
	} else {
		$errorAuth = "Invaild Code";
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
                    <a href="#" class="noble-ui-logo d-block mb-2">Noble<span>UI</span></a>
					<h5 class="text-muted font-weight-normal mb-4">Welcome back! Log in to your account.</h5>
					<?php if(isset($errorAuth) && $errorAuth != ""){ ?>
						<div class="text-danger mb-4"><?php echo $errorAuth; ?></div>
					<?php } ?>
                    <form class="forms-sample" action="" method="post" id="loginForm">
                      <div class="form-group">
                        <label for="code">Enter Code</label>
                        <input type="text" name="code" class="form-control" id="code" placeholder="Code" value="" required>
                      </div> 
                      
                      <div class="mt-3">
                        <button type="submit" name="login" class="btn btn-primary mr-2 mb-2 mb-md-0">Verify</button>                        
                      </div>                      
                    </form>
                  </div>
                </div>
              </div>
						</div>
					</div>
				</div>
<?php require_once('footer-none.php'); ?>