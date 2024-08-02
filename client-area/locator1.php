<?php
require_once('../config.php');
require_once('uservalidation.php');
require_once('header.php'); 
?>
<div class="row">					
	<div class="col-lg-12 grid-margin stretch-card">
		<div class="card">
			<div class="card-body">
        <label for="type">Category</label>
        <!-- onchange="filterMarkers(this.value);"  -->
        <select id="type"  class="filter filter-category">
          <option value="all">All Category</option>
          <option value="company">company</option>
          <option value="shop">shop</option>          
        </select>
        <label for="tag">tags</label>
        <select id="tag" class="filter filter-tag">
          <option value="all">All Tags</option>
          <option value="tag1">tag 1</option>
          <option value="tag2">tag 2</option>          
          <option value="tag3">tag 3</option>          
        </select>
        <!-- <div>
          <div class="form-check form-check-inline">
						<label class="form-check-label">							
              <input type="checkbox" class="form-check-input" name="tag1" value="tag1" id="tag1" onchange="filterByCheck(this.value);"> 
							tag 1
						<i class="input-frame"></i></label>
					</div>
          <div class="form-check form-check-inline">
						<label class="form-check-label">							
              <input type="checkbox" class="form-check-input" name="tag2" value="tag2" id="tag2" onchange="filterByCheck(this.value);"> 
							tag 2
						<i class="input-frame"></i></label>
					</div>
          <div class="form-check form-check-inline">
						<label class="form-check-label">							
              <input type="checkbox" class="form-check-input" name="tag3" value="tag3" id="tag3" onchange="filterByCheck(this.value);"> 
							tag 3
						<i class="input-frame"></i></label>
					</div>
				</div> -->
      </div>
    </div>
  </div>
</div>
<div class="row">					
	<div class="col-lg-5 grid-margin stretch-card">
		<div class="card">
			<div class="card-body">
            <div class="media d-block d-sm-flex mb-3">
	            <img src="https://dummyimage.com/100x100/000/fff.jpg&text=image" class="wd-50p wd-sm-100 mb-3 mb-sm-0 mr-3" alt="..."  onClick="clickOpenInfowindow(1);">
	          <div class="media-body">
		          <h5 class="mt-0" id='11'><a href="#" onClick="clickOpenInfowindow(1);">Ifox solutions</a></h5>
		          <p>Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin. Cras purus odio, vestibulum in vulputate at.</p>
	          </div>
          </div>
          <div class="media d-block d-sm-flex mb-3">
	          <img src="https://dummyimage.com/100x100/000/fff.jpg&text=image" class="wd-50p wd-sm-100 mb-3 mb-sm-0 mr-3" alt="..."  onClick="clickOpenInfowindow(11);">
	          <div class="media-body">
		          <h5 class="mt-0" onClick="clickOpenInfowindow(11);">Shree Ram Emporium</h5>
		          <p>Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin. Cras purus odio, vestibulum in vulputate at.</p>
	          </div>
          </div>
          <div class="media d-block d-sm-flex mb-3">
	          <img src="https://dummyimage.com/100x100/000/fff.jpg&text=image" class="wd-50p wd-sm-100 mb-3 mb-sm-0 mr-3" alt="..."  onClick="clickOpenInfowindow(12);">
	          <div class="media-body">
		          <h5 class="mt-0" onClick="clickOpenInfowindow(12);">Alpha one</h5>
		          <p>Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin. Cras purus odio, vestibulum in vulputate at.</p>
	          </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-7 grid-margin stretch-card">
		  <div class="card">
  			<div class="card-body">
          <style>       
            #map {
              height: 400px;
              width: 100%; 
            }
          </style>                
          <div id="map"></div>
          </div></div>
        </div>
    </div>
    </div>
<script>
 function initMap() {

        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 5,
          center: {lat: -28.024, lng: 140.887}
        });

        // Create an array of alphabetical characters used to label the markers.
        var labels = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        // Add some markers to the map.
        // Note: The code uses the JavaScript Array.prototype.map() method to
        // create an array of markers based on a given "locations" array.
        // The map() method here has nothing to do with the Google Maps API.
        var markers = locations.map(function(location, i) {
          return new google.maps.Marker({
            position: location,
            label: labels[i % labels.length]
          });
        });

        // Add a marker clusterer to manage the markers.
        var markerCluster = new MarkerClusterer(map, markers,
            {imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});
      }
      var locations = [
        {lat: -31.563910, lng: 147.154312},
        {lat: -33.718234, lng: 150.363181},
        {lat: -33.727111, lng: 150.371124},
        {lat: -33.848588, lng: 151.209834},
        {lat: -33.851702, lng: 151.216968},
        {lat: -34.671264, lng: 150.863657},
        {lat: -35.304724, lng: 148.662905},
        {lat: -36.817685, lng: 175.699196},
        {lat: -36.828611, lng: 175.790222},
        {lat: -37.750000, lng: 145.116667},
        {lat: -37.759859, lng: 145.128708},
        {lat: -37.765015, lng: 145.133858},
        {lat: -37.770104, lng: 145.143299},
        {lat: -37.773700, lng: 145.145187},
        {lat: -37.774785, lng: 145.137978},
        {lat: -37.819616, lng: 144.968119},
        {lat: -38.330766, lng: 144.695692},
        {lat: -39.927193, lng: 175.053218},
        {lat: -41.330162, lng: 174.865694},
        {lat: -42.734358, lng: 147.439506},
        {lat: -42.734358, lng: 147.501315},
        {lat: -42.735258, lng: 147.438000},
        {lat: -43.999792, lng: 170.463352}
      ]


</script>

<script src="https://unpkg.com/@google/markerclustererplus@4.0.1/dist/markerclustererplus.min.js">
</script>

<script async defer
  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBs7ogV4LvhIdo8sZmw0eWEBiGacTMkkbk&callback=initMap">
</script> 


<?php require_once('footer.php'); ?>