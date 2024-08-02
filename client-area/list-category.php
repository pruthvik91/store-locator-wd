<?php
require_once('../config.php');
require_once('uservalidation.php');
require_once('header.php');

$userid = $_SESSION['userid']; 
$searchQuery = "";
$category_name = "";

//delete start
if (isset($_GET["did"]) && !empty($_GET["did"])) {
	$did = $_GET["did"];
	if (is_numeric($did) && $did > 0) {
		$sqldelete = 'DELETE from ' . DB_BASE . '.store_category where user_detail_id=:userid and  store_category_id=:store_category_id';
		$datadelete = array(':store_category_id' => $did, ':userid' => $userid);
		CP_Delete($sqldelete, $datadelete);
		header("Location: list-category.php?mode=deleted");
	}
}
//delete end

//search start
if (isset($_POST["btnSearch"]) == "Search") {
	$category_name = $_POST["category_name"];
	if (!empty($category_name)) {
		$searchQuery = $searchQuery . " AND category_name LIKE '%" . $category_name . "%' ";
	}
} else {
	if (!empty($_GET["category_name"])) {
		$category_name = $_GET["category_name"];
		$searchQuery = $searchQuery . " AND category_name LIKE '%" . $category_name . "%' ";
	}
} //
//search end

$sql = "SELECT * FROM " . DB_BASE . ".store_category where 1=1 " . $searchQuery . " AND user_detail_id = :user_id";
$data = array(':user_id' => $userid);
$categories =  CP_Select($sql, $data);
$categories_count = count($categories);

// Pagination start
include('paginate.php');
if (isset($_POST["btnSearch"]) == "Search") {
    $cur_page = 1;
} else {
    $cur_page = isset($_GET["page"]) ? $_GET["page"] : 1;
}
$per_page = PERPAGE;

if ($cur_page == 1) {
    $page_start = 0;
} else {
    $page_start = (($cur_page - 1) * $per_page);
}
$total_results = $categories_count1; // Use $categories_count1 here
$total_pages = intval($total_results / $per_page); // Total pages we're going to have
if ($total_pages == 0) {
    $total_pages = 1;
} elseif (($total_pages * $per_page) < $total_results) {
    $total_pages++;
}
$pagination = "";
$url_string = "";
$url_string .= "category_name=" . $category_name;

if ($total_pages > 1) {
    $pagination = paginate('list-category.php?' . $url_string, $cur_page, $total_pages);
    $url_string .= "&page=" . $cur_page;
}

$sql1 = "SELECT * FROM " . DB_BASE . ".store_category where 1=1 " . $searchQuery . "   and user_detail_id = :user_id limit " . $page_start . "," . $per_page;
$data1 = array(':user_id' => $userid);
$categories1 =  CP_Select($sql1, $data1);
$categories_count1 = count($categories1);

// Pagination end

?>
<nav class="page-breadcrumb">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="#">Dashboard</a></li>
		<li class="breadcrumb-item active" aria-current="page">Add Category</li>
	</ol>
</nav>

<?php if (isset($_GET['mode']) && $_GET['mode'] == 'inserted') { ?>
	<div class="alert alert-icon-info alert-dismissible fade show" role="alert">
		<i data-feather="check-square"></i>
		Inserted Successfully!
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">×</span>
		</button>
	</div>
<?php } ?>
<?php if (isset($_GET['mode']) && $_GET['mode'] == 'updated') { ?>
	<div class="alert alert-icon-info alert-dismissible fade show" role="alert">
		<i data-feather="check-square"></i>
		Updated Successfully!
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">×</span>
		</button>
	</div>
<?php } ?>
<?php if (isset($_GET['mode']) && $_GET['mode'] == 'deleted') { ?>
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
				<h6 class="card-title">Search Category</h6>
				<form method="post">
					<div class="row">
						<div class="col-sm-4">
							<div class="form-group">
								<label class="control-label">Name</label>
								<input type="text" class="form-control" placeholder="Enter Name" name="category_name" value="<?php echo $category_name;  ?>">
							</div>
						</div><!-- Col -->
					</div><!-- Row -->

					<button type="submit" name="btnSearch" value="search" class="btn btn-primary 	btn-icon-text submit">
						<i class="btn-icon-prepend" data-feather="search"></i>
						Search
					</button>
					<a class="btn btn-primary 	btn-icon-text submit" href="list-category.php">
						<i class="btn-icon-prepend" data-feather="list"></i>
						Show all
					</a>
				</form>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-lg-12 grid-margin stretch-card">
		<div class="card">
			<div class="card-body">
				<h6 class="card-title">Category List
					<a href="add-category.php?<?php echo $url_string; ?>" class="btn btn-primary btn-icon-text float-right">
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
								<th>Marker</th>
								<th class="text-center">Visibility</th>
								<th class="table_action_th text-center">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
							if ($cur_page == 1) {
								$counter = 0;
							} else {
								$counter = ($cur_page - 1) * $per_page;
							}
							if ($categories_count1 > 0) {
								foreach ($categories1 as $category) {
									$counter++;
									$store_category_id = $category->store_category_id;
									$category_name = $category->category_name;
									$category_detail = $category->category_detail;
									$marker_type = $category->marker_type;
									$marker_icon = $category->marker_icon;
									$marker_size = $category->marker_size;
									$marker_image = $category->marker_image;
									$is_active = $category->is_active;
							?>
									<tr>
										<th><?php echo $counter; ?></th>
										<td><?php echo $category_name; ?></td>
										<td><?php echo $marker_image; ?></td>
										<td class="text-center">
											<button type="button" class="btn is_active" data-value="<?php echo $is_active; ?>" data-id="<?php echo $store_category_id; ?>"><?php echo ($is_active == 1) ? "Visible" : "Invisible"; ?></button>
										</td>
										<td class="text-center">
											<a href="add-category.php?id=<?php echo $store_category_id . "&" . $url_string; ?>" class="btn btn-primary btn-icon-text">
												<i class="btn-icon-prepend" data-feather="edit"></i>
												Edit
											</a>

											<div class="dropdown mb-2 table_dropdown_option">
												<button class="btn p-0" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
													<i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
												</button>
												<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton2">
													<a class="dropdown-item d-flex align-items-center" onclick="return confirm('are you sure to delete');" href="?did=<?php echo $store_category_id; ?>"><i data-feather="trash" class="icon-sm mr-2"></i> <span class="">Delete</span></a>
												</div>
											</div>
										</td>
									</tr>
							<?php }
							} ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo $pagination; ?>
</div>


<script>
	$(document).ready(function() {
		$('.is_active').click(function() {
			var btn = $(this);
			btn.attr("data-text", btn.html()).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
			btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
			var data = {
				action: 'ChangeStatus',
				module: 'store_category',
				id: btn.attr('data-id'),
				value: btn.attr('data-value'),
				hash: btn.attr('data-hash')
			}
			$.ajax({
				url: "ajaxcall.php",
				ContentType: 'application/json',
				method: "POST",
				dataType: "json",
				data: data,
				success: function(data) {

					if (data.status == 'OK') {
						btn.html((data.data == 1) ? "Visible" : "Invisible");
						btn.attr("data-value", data.data);
					} else {
						btn.html(btn.attr("data-text"));
					}
				},
				error: function(errorThrown) {

					console.log(errorThrown);
				},
				complete: function(data) {

				}
			});


		});
	});
</script>
<?php require_once('footer.php'); ?>