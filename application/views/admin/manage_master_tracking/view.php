<?php $mapapikey = $this->Scheme_Model->get_website_data("mapapikey") ;?>
<div class="row">
    <div class="col-xs-12" style="margin-bottom:5px;">
    	<?php /*<a href="add">
            <button type="submit" class="btn btn-info">
                Add
            </button>
        </a> */ ?>
   	</div>
	<div class="col-xs-12">
       <div id="map"></div>
    </div>
</div>
<script async
    src="https://maps.googleapis.com/maps/api/js?key=<?php echo $mapapikey ?>&loading=async&callback=initMap">
</script>
<script>
setTimeout(function(){
  initMap();
}, 60000);
// Initialize and add the map
function initMap() {
	// The location of Uluru
	var locations = []

	locations.push(['DRD Office', 28.5183163, 77.279475,1]);
  <?php
	$i = 1;
	foreach ($result as $newrow)
	{
    $row = $db_master->query("select * from tbl_tracking where date='$mydate' and latitude!='0.0' and user_altercode='$newrow' limit 1")->row();
		?>
		locations.push(['<?php echo $row->user_altercode; ?> - <?php echo $row->getdate; ?> - <?php echo $row->gettime; ?>',<?php echo $row->latitude; ?>,<?php echo $row->longitude; ?>,<?php echo $i++; ?>]);
		<?php
	} ?>

    var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 12,
      center: new google.maps.LatLng(28.5183163, 77.279475),
      mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    var infowindow = new google.maps.InfoWindow();
    var marker, i;
    for (i = 0; i < locations.length; i++) {
      if(i!=0)
      {  
        const icon = {
                url: "https://drdistributor.com/img_v51/marker_b.png", // url
                scaledSize: new google.maps.Size(30, 30), // scaled size
                origin: new google.maps.Point(0,0), // origin
                anchor: new google.maps.Point(0, 0) // anchor
          };
          marker = new google.maps.Marker({
          position: new google.maps.LatLng(locations[i][1], locations[i][2]),
          map: map,
          icon:icon
        });
      }
      if(i==0)
      {
        const icon2 = {
              url: "https://drdistributor.com/img_v51/marker_a.png", // url
              scaledSize: new google.maps.Size(30, 30), // scaled size
              origin: new google.maps.Point(0,0), // origin
              anchor: new google.maps.Point(0, 0) // anchor
        };
        marker = new google.maps.Marker({
          position: new google.maps.LatLng(locations[i][1], locations[i][2]),
          map: map,
          icon:icon2
        });
      }

      google.maps.event.addListener(marker, 'click', (function(marker, i) {
        return function() {
          infowindow.setContent(locations[i][0]);
          infowindow.open(map, marker);
        }
      })(marker, i));
    }
setTimeout(function(){
  initMap();
  }, 60000);
}
</script>
<!--Load the API from the specified URL
* The async attribute allows the browser to render the page while the API loads
* The key parameter will contain your own API key (which is not needed for this tutorial)
* The callback parameter executes the initMap() function
-->
<style>
    /* Set the size of the div element that contains the map */
  #map {
    height: 500px;  /* The height is 400 pixels */
    width: 100%;  /* The width is the width of the web page */
    }
</style>