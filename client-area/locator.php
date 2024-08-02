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
var customLabel = {
        restaurant: {
          label: 'R',          
        },
        bar: {
          label: 'B'          
        },
        company: {          
          icon: 'http://localhost/store-locator/client-area/upload/markers/app.png'
        },
        shop: {          
          icon: 'http://localhost/store-locator/client-area/upload/markers/world.png'
        }
      };      
var gmarkers = [];
var markers = [];
var markerCluster;
var filterResults = [];
var filterSelect = jQuery('.filter');
// add markers
function initMap() {
  var map = new google.maps.Map(document.getElementById('map'), {
    center: new google.maps.LatLng(23.021645, 72.637993),
    zoom: 8
  });
  var infoWindow = new google.maps.InfoWindow;
  // Change this depending on the name of your PHP or XML file
  downloadUrl('markers.xml', function(data) {
    var xml = data.responseXML;
    var markers = xml.documentElement.getElementsByTagName('marker');
	console.log(markers);
    Array.prototype.forEach.call(markers, function(markerElem) {
      var id = markerElem.getAttribute('id');
      var tag = markerElem.getAttribute('tag');
      var filterTag = markerElem.getAttribute('tag');
      var name = markerElem.getAttribute('name');
      var address = markerElem.getAttribute('address');
      var type = markerElem.getAttribute('type');
      var filterCategory = markerElem.getAttribute('type');
      filterResults.push(filterCategory, filterTag);
      var point = new google.maps.LatLng(
          parseFloat(markerElem.getAttribute('lat')),
          parseFloat(markerElem.getAttribute('lng'))
          );

      var infowincontent = document.createElement('div');
      //infowincontent.setAttribute('class', 'note');
      var strong = document.createElement('strong');
      strong.textContent = name
      infowincontent.appendChild(strong);
      infowincontent.appendChild(document.createElement('br'));
      
      var strong = document.createElement('strong');
      strong.textContent = type
      infowincontent.appendChild(strong);
      infowincontent.appendChild(document.createElement('br'));

      var text = document.createElement('text');
      text.textContent = address
      infowincontent.appendChild(text);
      
      var icon = customLabel[type] || {};
      var marker = new google.maps.Marker({
        map: map,
        position: point,  
        label: icon.label,
        icon: icon.icon,
        category : type,
        id : id,
        tag : tag
      });
	
      gmarkers.push(marker);
      
      marker.addListener('click', function() {                
        infoWindow.setContent(infowincontent);
        infoWindow.open(map, marker);
      });
      marker.addListener('mouseover', function() {                
        infoWindow.setContent(infowincontent);
        infoWindow.open(map, marker);
      });      
      marker.addListener('mouseout', function() {
        infoWindow.close();
      });
   });
	markerCluster = new MarkerClusterer(map, gmarkers,
            {imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'}); 
	
  });
  
}
        
function downloadUrl(url, callback) {
  var request = window.ActiveXObject ?
      new ActiveXObject('Microsoft.XMLHTTP') :
      new XMLHttpRequest;
  request.onreadystatechange = function() {
    if (request.readyState == 4) {
      request.onreadystatechange = doNothing;
      callback(request, request.status);
    }
  };
  request.open('GET', url, true);
  request.send(null);
}
//list click open info
function clickOpenInfowindow(id){    
  $.each(gmarkers, function(index, value) {    
    if(this.id == id){
      google.maps.event.trigger(this, 'click');
    }    
  });
}
//filter
//filterMarkers = function (category) {  
//  console.log('sdf');
//  for (i = 0; i < gmarkers.length; i++) {
//    marker = gmarkers[i];    
//      if (marker.category == category || category.length === 0) {
//        marker.setVisible(true);
//      }        
//      else {
//        marker.setVisible(false);
//      }
//  }
//}
//filterMarkersTag = function (tag) {  
//  for (i = 0; i < gmarkers.length; i++) {
//    marker = gmarkers[i];    
//      if (marker.tag == tag || tag.length === 0) {
//        marker.setVisible(true);
//      }        
//      else {
//        marker.setVisible(false);
//      }
//  }
//}
function clusterManager(array) {
    markerCluster.clearMarkers();
    if (!array.length) {
        jQuery('.alert').addClass('is-visible');
    } else {
        jQuery('.alert').removeClass('is-visible');
        for (i=0; i < array.length; i++) {
            markerCluster.addMarker(array[i]);
        }
    }
}
function newFilter(filterType1 = 'all', filterType2 = 'all') {
    var criteria = [
        { Field: "category", Values: [filterType1] },
        { Field: "tag", Values: [filterType2] },        
        // { Field: ["animal", "name", "drink"], Values: [filterTyped] }
      ];
    
    var filtered = gmarkers.flexFilter(criteria);    
	clusterManager(filtered);
    //console.log(filtered);
}
Array.prototype.flexFilter = function(info) {
    var matchesFilter, matches = [], count;
    matchesFilter = function(item) {
      count = 0;
      //console.log(item);
      for (var n = 0; n < info.length; n++) {
        //console.log(info[n]["Values"]);
        //console.log(item.values);
        //console.log(info[n]["Values"].indexOf(item.Field));
        //console.log(item.Values);
        //if (info[n]["Values"].indexOf(item[info[n]["Field"]]) > -1) {
        //console.log(info[n]["Values"].indexOf(item.Values));
        if (info[n]["Values"]==item.Values ) {
            count++;
            //console.log('1');
        }
        else if (info[n]["Values"] == "all") {
            count++;
            //console.log('2');
        }
      }
      //console.log(count+"="+info.length);
      return count == info.length;
    }
    
    for (var i = 0; i < info.length; i++) {
      //console.log(info[i]);
      if (matchesFilter(info[i])) {
        matches.push(info[i]);
        //console.log(info.length);
      }
    }  
    console.log(matches);
    return matches;
 }
 jQuery(document).ready(function() {
  jQuery('.filter-category').on('change', function(){       
    var filter2 = jQuery('.filter-tag').val();      
    newFilter(jQuery(this).val(), filter2);
  });
  
  jQuery('.filter-tag').on('change', function(){
    var filter1 = jQuery('.filter-category').val();    
    newFilter(filter1, jQuery(this).val());
  });

  var uniqueValue = [];
    jQuery.each(filterResults, function(i, el){
        if(jQuery.inArray(el, uniqueValue) === -1) {
            uniqueValue.push(el);
        } 
    });
    /* var substringMatcher = function(strs) {
        return function findMatches(q, cb) {
        var matches, substringRegex;
        matches = [];
    
        substrRegex = new RegExp(q, 'i');
    
        jQuery.each(strs, function(i, str) {
            if (substrRegex.test(str)) {
            matches.push(str);
            }
        });
        cb(matches);
        };    
    };     */
  });
function doNothing() {}
/* jQuery(window).on('load', function(){
    initMap();
}); */
</script>

<!-- <script src="https://unpkg.com/@google/markerclustererplus@4.0.1/dist/markerclustererplus.min.js"></script> -->
<script src="http://localhost/store-locator/client-area/markerclusterer/markerclusterer.js"></script>

<script async defer
  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBs7ogV4LvhIdo8sZmw0eWEBiGacTMkkbk&callback=initMap">
</script> 


<?php require_once('footer.php'); ?>