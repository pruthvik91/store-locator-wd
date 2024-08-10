<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Wed & Nik</title>	
	<link rel="stylesheet" href="../assets/vendors/core/core.css">		
	<link rel="stylesheet" href="../assets/vendors/select2/select2.min.css">
	<link rel="stylesheet" href="../assets/vendors/jquery-tags-input/jquery.tagsinput.min.css">
	<link rel="stylesheet" href="../assets/vendors/dropzone/dropzone.min.css"> 
	<link rel="stylesheet" href="../assets/vendors/bootstrap-colorpicker/bootstrap-colorpicker.min.css">
	<link rel="stylesheet" href="../assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css">
	<link rel="stylesheet" href="../assets/vendors/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="../assets/fonts/feather-font/css/iconfont.css">	
	<link rel="stylesheet" href="../assets/css/my-bootstrap.min.css">  
	<link rel="stylesheet" href="../assets/css/jquery-ui.css">
	<link rel="stylesheet" href="../assets/css/demo_5/style.css">
	<link rel="stylesheet" href="../assets/css/style.css">
	<link rel="stylesheet" href="../assets/css/custom.css">  
	<link rel="stylesheet" href="../assets/css/font-awesome.css">
    <link rel="shortcut icon" href="./favicon_io/android-chrome-192x192.png" />  
	<script src="../assets/vendors/core/core.js"></script>
	
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
	<script src="../assets/js/jquery-1.12.4.min.js"></script>
	<script src="../assets/js/jquery-ui.js"></script>
	<script src="../assets/js/jquery-migrate-1.4.1.min.js"></script>
	<script src="../assets/js/general.js"></script>
	<script src="../assets/js/JsBarcode.js"></script>

</head>
<body>
<script>
        var gst_rates_options_igst = "<?php echo gst_rates_options('','igst'); ?>";
        var gst_rates_options_cgst = "<?php echo gst_rates_options(); ?>";
    </script>
		
		<div class="horizontal-menu">
			<nav class="navbar top-navbar">
				<div class="container">
					<div class="navbar-content">
						<a href="home.php" class="navbar-brand">
						Wed & <span style="margin-left:4px;">Nik</span>
						</a>						
						<ul class="navbar-nav">
							
							
							<!-- <li class="nav-item dropdown nav-notifications">
								<a class="nav-link dropdown-toggle" href="#" id="notificationDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									<i data-feather="bell"></i>
									<div class="indicator">
										<div class="circle"></div>
									</div>
								</a>
								<div class="dropdown-menu" aria-labelledby="notificationDropdown">
									<div class="dropdown-header d-flex align-items-center justify-content-between">
										<p class="mb-0 font-weight-medium">6 New Notifications</p>
										<a href="javascript:;" class="text-muted">Clear all</a>
									</div>
									<div class="dropdown-body">
										<a href="javascript:;" class="dropdown-item">
											<div class="icon">
												<i data-feather="user-plus"></i>
											</div>
											<div class="content">
												<p>New customer registered</p>
												<p class="sub-text text-muted">2 sec ago</p>
											</div>
										</a>
										<a href="javascript:;" class="dropdown-item">
											<div class="icon">
												<i data-feather="gift"></i>
											</div>
											<div class="content">
												<p>New Order Recieved</p>
												<p class="sub-text text-muted">30 min ago</p>
											</div>
										</a>
										<a href="javascript:;" class="dropdown-item">
											<div class="icon">
												<i data-feather="alert-circle"></i>
											</div>
											<div class="content">
												<p>Server Limit Reached!</p>
												<p class="sub-text text-muted">1 hrs ago</p>
											</div>
										</a>
										<a href="javascript:;" class="dropdown-item">
											<div class="icon">
												<i data-feather="layers"></i>
											</div>
											<div class="content">
												<p>Apps are ready for update</p>
												<p class="sub-text text-muted">5 hrs ago</p>
											</div>
										</a>
										<a href="javascript:;" class="dropdown-item">
											<div class="icon">
												<i data-feather="download"></i>
											</div>
											<div class="content">
												<p>Download completed</p>
												<p class="sub-text text-muted">6 hrs ago</p>
											</div>
										</a>
									</div>
									<div class="dropdown-footer d-flex align-items-center justify-content-center">
										<a href="javascript:;">View all</a>
									</div>
								</div>
							</li> -->
							<li class="nav-item dropdown nav-profile">
								<a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i data-feather="user"></i>
								</a>
								<div class="dropdown-menu" aria-labelledby="profileDropdown">
									<div class="dropdown-header d-flex flex-column align-items-center">
										<div class="figure mb-3">
											<img src="./favicon_io/android-chrome-512x512.png" alt="">
										</div>
										<div class="info text-center">
											<p class="name font-weight-bold mb-0"><?php echo $_SESSION['user_name']; ?></p>
											<p class="email text-muted mb-3"><?php echo $_SESSION['user_email']; ?></p>
										</div>
									</div>
									<div class="dropdown-body">
										<ul class="profile-nav p-0 pt-3">
											<li class="nav-item">
												<a href="store-Setting.php" class="nav-link">
													<i data-feather="user"></i>
													<span>Store Settings</span>
												</a>
											</li>			
											<li class="nav-item">
												<a href="logout.php" class="nav-link">
													<i data-feather="log-out"></i>
													<span>Log Out</span>
												</a>
											</li>
										</ul>
									</div>
								</div>
							</li>
						</ul>
						<button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="horizontal-menu-toggle">
							<i data-feather="menu"></i>					
						</button>
					</div>
				</div>
			</nav>
			<nav class="bottom-navbar">
				<div class="container">
					<ul class="nav page-navigation">
						<li class="nav-item">
							<a class="nav-link" href="home.php">
								<i class="link-icon" data-feather="home"></i>
								<span class="menu-title">Dashboard</span>
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="list-products.php">
								<i class="link-icon" data-feather="box"></i>
								<span class="menu-title">Products</span>
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="invoicedetail.php">
								<i class="link-icon" data-feather="file-text"></i>
								<span class="menu-title">Sale Invoice</span>
							</a>
						</li>
						<!-- <li class="nav-item">
    <a class="nav-link" href="manage-stock.php">
        <i class="link-icon" data-feather="box"></i>
        <span class="menu-title">Manage Stock</span>
    </a>
</li> -->

					</ul>
				</div>
			</nav>
		</div>		
	
		<div class="page-wrapper">

			<div class="page-content">

				