<?php
  $title = "Update Profile";
  require ('../config.php');
  require ('./header.php');
    $aid = $_SESSION['userid'];
    $number1 = "";
    $number2 = "";
    $display_hsn = "";
    $instagram = "";
    $storeaddress = "";
    $query = "SELECT * FROM ".DB_BASE.".`store_settings`";
    $result = $db->prepare($query);
    $result->execute();
    $fetch = $result->fetchAll(PDO::FETCH_OBJ);
    $count = count($fetch);
    if ($count > 0) {
        foreach ($fetch as $value) {
        
        }
    }

    if (isset($_POST['submit'])) {
   
      

    $number1 = trim($_POST['number1']);
    $number2 = trim($_POST['number2']);
    $instagram = trim($_POST['instagram']);
    $storeaddress = trim($_POST['address']);
    $display_hsn = trim($_POST['display_hsn']);
    $general_options = array(
        'number1'=>$number1
        ,'number2'=>$number2
        ,'instagram'=>$instagram
        ,'storeaddress'=>$storeaddress
        ,'display_hsn'=>$display_hsn
    );
    $general_options = json_encode($general_options);
    $query = "INSERT into  ".DB_BASE.".`store_settings`(`settings`,`createdate`) VALUES ('$general_options',NOW());";
    $result = $db->prepare($query);
    $result->execute();
  }
  $getsettings = "SELECT * FROM ".DB_BASE.".store_settings order by store_id desc limit 1";
  $qrysettings =  CP_Select($getsettings,[]);
  $settings_count = count($qrysettings);
  $st = json_decode($qrysettings[0]->settings,true);
  $number1 = isset($st['number1']) && !empty($st['number1']) ? $st['number1'] :'Add number In store Setting';
  $number2 = isset($st['number2']) && !empty($st['number2']) ? $st['number2'] :'Add number In store Setting';
  $instagram = isset($st['instagram']) && !empty($st['instagram']) ? $st['instagram'] :'Add instagram store Setting';
  $storeaddress = isset($st['storeaddress']) && !empty($st['storeaddress']) ? $st['storeaddress']:'Add store address store Setting';
  $display_hsn = isset($st['display_hsn']) && !empty($st['display_hsn']) ? $st['display_hsn']:'';
  


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
                <label for="email">Store Address</label>
                    <input type="text" id="" name="address" class="form-control" value="<?php echo $storeaddress; ?>">
                    <p id="phonecheck"></p>
                  </div> <div class="form-group">
                <label for="email">Phone 1</label>
                    <input type="number" id="" name="number1" class="form-control" value="<?php echo $number1; ?>">
                    <p id="phonecheck"></p>
                  </div>
                <div class="form-group">
                <label for="email">Phone 2</label>
                    <input type="number" id="" name="number2" class="form-control" value="<?php echo $number2; ?>">
                    <p id="phonecheck"></p>
                </div>
                 <div class="form-group">
                <label for="email">Instagram User id</label>
                    <input type="phone" id="" name="instagram" class="form-control" value="<?php echo $instagram; ?>">
                    <p id="phonecheck"></p>
                </div>
                <div class="form-group">
                <div class="form-check">
                  <label class="form-check-label"><input type="checkbox" class="display_hsn" name="display_hsn" <?= ($display_hsn == 'on')?'checked':''?>>Display HSN?<i class="input-frame"></i><i class="input-frame"></i></label>
              </div>
              </div>
                  <button type="submit" name="submit" class="btn btn-primary mr-2" id="subbtn">Submit</button>
                  <a href="list-products.php" class="btn btn-default">back</a>
                </form>
              </div>
            </div>
          </div>
        </div> <!-- row -->

      </div>
    </div>
<?php
  include ('footer.php');
?>    