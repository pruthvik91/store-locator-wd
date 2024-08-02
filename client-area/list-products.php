<?php
    require_once('../config.php');
    require_once('uservalidation.php');
    require_once('header.php'); 
  $product_id = "";
    $product_name = "";
    $hsn_sac = "";
    $pur_price = "";
    $sell_price = "";
    $item_available = "";
    $gst = "";
    $userid = $_SESSION['userid'];
    $searchQuery ="";
    $product_name = "";
//delete start
if(isset($_GET["did"]) && !empty($_GET["did"])){
	$did = $_GET["did"];	
	if(is_numeric($did) && $did > 0 ){
		$sqldelete = 'DELETE from '.DB_BASE.'.store_product where product_id=:product_id';		
		$datadelete = array(':product_id' => $did);		
		CP_Delete($sqldelete,$datadelete);
		header("Location: list-products.php?mode=deleted");
	}
}
//delete end

//search start
if(isset($_GET["btnSearch1"])=="Search"){	
	$product_name = $_GET["product_name"];
	if(!empty($product_name))
	{
		$searchQuery = $searchQuery." AND product_name LIKE '%".$product_name."%' ";
	}
	
}else{		
	if(!empty($_GET["product_name"]) || !empty($_GET["store_category_id"])){
		$product_name = $_GET["product_name"];
		if(!empty($product_name))
		{
			$searchQuery = $searchQuery." AND product_name LIKE '%".$product_name."%' ";
		}
	}	
}
if(isset($_GET["showall"])=="Showall"){
    header("Location: list-products.php");
}
//search end

$sql = "SELECT * FROM ".DB_BASE.".store_product where 1=1 ".$searchQuery."";
$product =  CP_Select($sql,[]);
$product_count = count($product);

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

$total_results = $product_count;
$total_pages = intval($total_results / $per_page); //total pages we going to have

if($total_pages==0){
	$total_pages = 1;
}elseif(($total_pages*$per_page)<$total_results){
	$total_pages++;
}

$pagination = "";
$url_string = "";
$url_string .= "product_name=".$product_name;
// $url_string .= "&search=".$;
if ($total_pages > 1) {
	$pagination = paginate('list-products.php?'.$url_string, $cur_page, $total_pages);
	$url_string .= "&page=".$cur_page;
}

$sql1 = "SELECT * FROM " . DB_BASE . ".store_product 
         WHERE 1=:user" . $searchQuery . " 
         ORDER BY product_id DESC 
         LIMIT " . $page_start . "," . $per_page;
$data1 = array(':user' => 1);
$product1 =  CP_Select($sql1,$data1);
$product_count1 = count($product1);

//pagination end
?>

<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">List Product</li>
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
<div class="alert alert-icon-info alert-dismissible fade-in show" role="alert">
    <i data-feather="check-square"></i>
    Updated Successfully!
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
<?php } ?>
<?php if(isset($_GET['mode']) && $_GET['mode'] == 'deleted'){?>
<div class="alert alert-icon-danger alert-dismissible fade-in show" role="alert">
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
                <h6 class="card-title">Product Search</h6>
                <form method="get">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label">Product Name</label>
                                <input type="text" class="form-control" placeholder="Enter Product Name"
                                    name="product_name" value="<?php echo $product_name; ?>">
                            </div>
                        </div><!-- Col -->
                    </div><!-- Row -->

                    <button type="submit" name="btnSearch1" value="search" class="btn btn-primary 	btn-icon-text submit">
                        <i class="btn-icon-prepend" data-feather="search"></i>
                        Search
                    </button>
                    <button class="btn btn-primary 	btn-icon-text submit" name="showall">
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
                <h6 class="card-title">Product List
                    <a href="add-products.php?<?php echo $url_string; ?>"
                        class="btn btn-primary btn-icon-text float-right">
                        <i class="btn-icon-prepend" data-feather="plus"></i>
                        Add New
                    </a>
                </h6>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Product Name</th>
                                <th>Hsn</th>
                                <th>Purchase Price</th>
                                <th>Sell Price</th>
                                <th>Stock available</th>
                                <th>Gst</th>
                                <th class="table_action_th text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
											if($cur_page==1){
												$counter = 0;
											}else{
												$counter = ($cur_page-1)*$per_page;
											}
											if($product_count1 > 0){
												foreach($product1 as $row){
													$counter++;
													$product_id = $row->product_id; 
													$product_name = $row->product_name; 
													$hsn_sac = $row->hsn_sac; 
                                                    $pur_price = $row->pur_price; 
													$sell_price = $row->sell_price; 
													$item_available = $row->item_available;    
													$gst = $row->gst;
											?>
                            <tr>
                                <th><?php echo $counter; ?></th>
                                <td><?php echo $product_name; ?></td>
                                <td><?php echo $hsn_sac; ?></td>
                                <td><?php echo number_format($pur_price,2); ?></td>
                                <td><?php echo number_format($sell_price,2); ?></td>
                                <td><?php echo $item_available; ?></td>
                                <td><?php echo $gst."%"; ?></td>

                                <td class="text-center">
                                    <a href="add-products.php?id=<?php echo $product_id."&".$url_string; ?>"
                                        class="btn btn-primary btn-icon-text">
                                        <i class="btn-icon-prepend" data-feather="edit"></i>
                                        Edit
                                    </a>


                                    <div class="dropdown mb-2 table_dropdown_option">
                                        <button class="btn p-0" type="button" id="dropdownMenuButton2"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right"
                                            aria-labelledby="dropdownMenuButton2">
                                            <a class="dropdown-item d-flex align-items-center"
                                                href="#" onclick="confirmDelete(<?php echo $product_id; ?>)"><i data-feather="trash"
                                                    class="icon-sm mr-2"></i> <span class="">Delete</span></a>
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
function confirmDelete(productId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "list-products.php?did=" + productId;
        }
    })
}
</script>
<?php require_once('footer.php'); ?>
