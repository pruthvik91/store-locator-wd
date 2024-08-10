<?php
require_once('../config.php');
require_once('uservalidation.php');
$URLBack = $_SERVER['QUERY_STRING'];
global $db;
global $default_sizes;
global $image_mimes;
$userid = $_SESSION["userid"];
$product_name = "";
$pur_price = "";
$sell_price = "";
$gst = "";
$item_available = "";
$barcode_no = "";
$hsn_sac = "";
if ((!empty($_POST)) && isset($_POST["btnsubmit"])) {
    if (isset($_POST["product_name"])) {

        $product_name = trim($_POST["product_name"]);
        $hsn_sac = trim($_POST["hsn_sac"]);
        $sell_price = trim($_POST["sell_price"]);
        $pur_price = trim($_POST["pur_price"]);
        $barcode_no = trim($_POST["barcode_no"]);

        $plus_minus = trim($_POST["plus_minus"]);
        $operator = ($plus_minus == "plus") ? "+" : "-";
        if($_GET['id']!="")
        {
        $new_item_available =   $_POST["new_item_available"]?trim($_POST["new_item_available"]): 0;
        }else{
            $item_available = trim($_POST["item_available"]);
        }
        $gst = trim($_POST["gst"]);
        $dt1 = date("Y-m-d H:i:s");
        if ((isset($_GET["id"])) && ($_GET["id"] != "")) {
            $id = $_GET["id"];

            $sql = "SELECT * FROM " . DB_BASE . ".store_product where product_id = :product_id";
            $data = array(':product_id' => $id);
            $rowcount = CP_count($sql, $data);

            $sqlupdate = 'UPDATE ' . DB_BASE . '.store_product SET  
                product_name=:product_name,
                sell_price=:sell_price,
                pur_price=:pur_price,
                hsn_sac=:hsn_sac, 
                barcode_no=:barcode_no, 
                gst=:gst, 
                item_available=(item_available ' . $operator . $new_item_available . '),
                modifydate=:modifydate WHERE product_id=:product_id';
            $data = array(
                ':product_id' => $id,
                ':product_name' => $product_name,
                ':sell_price' => $sell_price,
                ':pur_price' => $pur_price,
                ':hsn_sac' => $hsn_sac,
                ':barcode_no' => $barcode_no,
                ':gst' => $gst,
                ':modifydate' => $dt1
            );

           $update= CP_update($sqlupdate, $data);
            if ($update) {
                $id = $_GET["id"];
                $pn = (!isset($_GET["page"])&&$_GET["page"]=="")? '' :'&page='.$pn.'';
                header("Location: list-products.php?id=$id&product_name=&store_category_id=&mode=updated");
            }
        } else {
            $sql = "INSERT INTO " . DB_BASE . ".store_product (product_name,sell_price,pur_price,hsn_sac,barcode_no,gst,item_available,createdate) VALUES (:product_name,:sell_price,:pur_price,:hsn_sac,:barcode_no,:gst,:item_available,:createdate);";
            $data = array(
                ':product_name' => $product_name,
                ':sell_price' => $sell_price,
                ':pur_price' => $pur_price,
                ':hsn_sac' => $hsn_sac,
                ':barcode_no' => $barcode_no,
                ':gst' => $gst,
                ':item_available' => $item_available,
                ':createdate' => $dt1
            );
            $store_id = CP_Insert($sql, $data);
            if ($store_id) {
                header("Location: list-products.php?mode=inserted");
            }
        }
    }
}
$isedit = false;
if ((isset($_GET["id"])) && ($_GET["id"] != "")) {
    $id = $_GET["id"];
    $isedit = 1;
    $sql = "SELECT * FROM " . DB_BASE . ".store_product where product_id = :product_id ";
    $data = array(':product_id' => $id);
    $rows = CP_select($sql, $data);
    $rowcount = count($rows);
    if ($rowcount > 0) {
        foreach ($rows as $row) {
            $product_name = $row->product_name;
            $sell_price = $row->sell_price;
            $pur_price = $row->pur_price;
            $gst = $row->gst;
            $item_available = $row->item_available;
            $hsn_sac = $row->hsn_sac;
            $barcode_no = $row->barcode_no;
        }
    }
}
require_once('header.php');

?>

<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page"><?php if ((isset($_GET["id"])) && ($_GET["id"] != "")) {
                                                                    echo "Edit";
                                                                } else {
                                                                    echo "Add";
                                                                } ?> Product</li>
    </ol>
</nav>

<form class="cmxform" id="form" enctype="multipart/form-data" method="post" action="">
    <fieldset>
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title"><?php if ((isset($_GET["id"])) && ($_GET["id"] != "")) {
                                                    echo "Edit";
                                                } else {
                                                    echo "Add";
                                                } ?> Product</h4>

                        <div class="form-group">
                            <label for="name">Product Name</label>
                            <input id="name" class="form-control" name="product_name" value="<?php echo $product_name; ?>" type="text" required>
                        </div>
                        <div class="form-group">
                            <label for="name">Hsn No.</label>
                            <input id="hsn_sac" class="form-control" name="hsn_sac" value="<?php echo $hsn_sac; ?>" type="text" required>
                        </div>
                        <div class="form-group">
                            <label for="name">Purchase Price</label>
                            <input id="name" class="form-control" name="pur_price" value="<?php echo $pur_price; ?>" type="number" required>
                        </div>
                        <div class="form-group">
                            <label for="name">Sale Price</label>
                            <input id="name" class="form-control" name="sell_price" value="<?php echo $sell_price; ?>" type="number" required>
                        </div>
                        <div class="form-group">
                            <label for="name">Barcode No</label>
                            <input id="name" class="form-control" name="barcode_no" value="<?php echo $barcode_no; ?>" type="number" required>
                        </div>
                        <div class="form-group">
                            <label for="store_description">Tax (in percentage)</label>
                            <input type="number" class="form-control" name="gst" id="gst" value="<?php echo $gst; ?>">
                        </div>
                        <div class="form-group">
                            <label for="item_available"> <?php echo ($isedit) ? "Current" : ""; ?> Stock</label>
                            <div>
                                <input type="number" class="form-control" name="item_available" id="item_available" value="<?php echo $item_available; ?>" <?php echo ($isedit) ? "Disabled" : ""; ?> />
                            </div>
                        </div>
                       <?php if($isedit) {?>
                        <div class="form-group">
                            <div class="row">
                            
                                <div class="col-4 float-left" >
                                <label for="item_available" class="mt-1 text-align-left" style="width:auto">Add New Stock :</label>
                                    <div class="cd-pricing-switcher float-none">
                                    
											<div class="switcher_label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
											<p class="fieldset">
												<input type="radio" name="plus_minus" value="plus" id="disc-rs" checked="">
												<label for="disc-rs">+</label>
												<input type="radio" name="plus_minus" value="minus" id="disc-p">
												<label for="disc-p">-</label>
												<span class="cd-switch"></span>
											</p>
										</div>
                                    </div>
                                        <div class="col-6"><input type="number" placeholder="Add Stock" class="form-control" name="new_item_available" id="new_item_available" value="" /></div>

                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <input class="btn btn-primary" type="submit" value="Submit" name="btnsubmit">
                        <a type="button" class="btn" href="list-products.php?<?php echo $URLBack; ?>">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </fieldset>
</form>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const imageInput = document.getElementById("image-input");
        const uploadButton = document.getElementById("upload-button");
        const imagePreview = document.getElementById("image-preview");

        imageInput.addEventListener("change", function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = "block";
                };
                reader.readAsDataURL(file);
            }
        });
    });
</script>
<?php require_once('footer.php'); ?>