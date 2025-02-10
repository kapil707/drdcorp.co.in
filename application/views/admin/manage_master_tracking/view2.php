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
    
  <script async
    src="https://maps.googleapis.com/maps/api/js?key=<?php echo $mapapikey ?>&loading=async&callback=initMap">
</script>

  <div id="map" style="height: 600px;"></div>
  <script>
    function initMap() {
        const map = new google.maps.Map(document.getElementById("map"), {
            zoom: 7,
            center: { lat: 29.625996, lng: 74.287491 }, // San Francisco
        });

        const directionsService = new google.maps.DirectionsService();
        const directionsRenderer = new google.maps.DirectionsRenderer({ map: map });

        var origin = {lat: <?php echo $f_lat ?>, lng: <?php echo $f_lng ?>};  // Origin coordinates
        var destination = {lat: <?php echo $l_lat ?>, lng: <?php echo $l_lng ?>};  // Destination coordinates

        var waypoints = [
            <?php foreach($result as $row) { ?>
            {location: {lat: <?php echo $row->latitude ?>, lng: <?php echo $row->longitude ?>}, stopover: true},  // Waypoint 1
            <?php } ?>
        ];

        const request = {
            origin: origin,
            destination: destination,
            waypoints: waypoints,
            optimizeWaypoints: true,
            travelMode: 'DRIVING'
        };

        // var origin = {lat: 41.85, lng: -87.65};  // Origin coordinates
        // var destination = {lat: 42.36, lng: -71.06};  // Destination coordinates
        // var waypoints = [
        //     {location: {lat: 41.85, lng: -87.65}, stopover: true},  // Waypoint 1
        //     {location: {lat: 41.90, lng: -87.70}, stopover: true},  // Waypoint 2
        //     {location: {lat: 42.10, lng: -87.95}, stopover: true}   // Waypoint 3
        // ];

        // const request = {
        //     origin: origin,
        //     destination: destination,
        //     waypoints: waypoints,
        //     optimizeWaypoints: true,
        //     travelMode: 'DRIVING'
        // };

        // const request = {
        //     origin: "hanumangarh",
        //     destination: "jaipur",
        //     travelMode: google.maps.TravelMode.DRIVING,
        // };

        directionsService.route(request, function (result, status) {
            if (status == google.maps.DirectionsStatus.OK) {
                directionsRenderer.setDirections(result);
            } else {
                window.alert("Directions request failed due to " + status);
            }
        });
    }
</script>
   
<script>
    window.onload = function () {
        initMap();
    };
</script>

</div>
</div>