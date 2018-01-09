
 <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true"></script>

    <style>
      #map-canvas {
        height: 100%;
        margin: 0;
        padding: 0;
      }

    </style>
   
    <script>
function initialize() {
  var myLatlng = new google.maps.LatLng(-25.363882,131.044922);
  var mapOptions = {
    zoom: 4,
    center: myLatlng
  }
  var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

  var marker = new google.maps.Marker({
      position: myLatlng,
      map: map,
      title: 'Hello World!'
  });
}

google.maps.event.addDomListener(window, 'load', initialize);

    </script>
    <div id="map-canvas"></div>
 

