(function( $, document, window, google) {
    var $canvas = $('#map-canvas');
    if(!$canvas.length) {
        return;
    }
    var addr = $canvas.data('map'),
    geocoder = new google.maps.Geocoder();
    
    google.maps.visualRefresh = true;
    
    function initialize() {
        
        geocoder.geocode( { 'address': addr}, function(results, status) {
            //detectBrowser();
          if (status == google.maps.GeocoderStatus.OK) {
              var jsonStyle = 
[
  {
    "stylers": [
      { "lightness": -33 },
      { "saturation": -100 },
      { "gamma": 0.22 }
    ]
  },{
    "featureType": "road",
    "stylers": [
      { "lightness": -81 }
    ]
  },{
    "featureType": "transit.station",
    "elementType": "labels.text.fill",
    "stylers": [
      { "lightness": 89 }
    ]
  },{
    "featureType": "transit.station",
    "elementType": "labels.icon",
    "stylers": [
      { "gamma": 9.99 }
    ]
  },{
  },{
    "elementType": "labels.text.fill",
    "stylers": [
      { "lightness": 48 },
      { "gamma": 1 }
    ]
  },{
    "featureType": "poi",
    "stylers": [
      { "visibility": "off" }
    ]
  }
];
// 48.8655920 	2.3504374

        //var loc = new google.maps.LatLng(48.8656167, 2.3504482),
        var loc = results[0].geometry.location,
        mapOptions = {
          //center: new google.maps.LatLng(48.8674905, 2.3466233),
          center: new google.maps.LatLng(48.8674905, 2.3466233),
          center: results[0].geometry.location,
          zoom: 15,
          mapTypeId: google.maps.MapTypeId.ROADMAP,
          styles : jsonStyle
        },
        bounds = new google.maps.LatLngBounds(),

        map = new google.maps.Map(document.getElementById("map-canvas"),
            mapOptions),
        marker = new google.maps.Marker({
            map: map, 
            position: loc
            });
            /*
        bounds.extend(loc);
        map.fitBounds(bounds);
            */
        google.maps.event.addListenerOnce(map, 'idle', function() {
                google.maps.event.trigger(map, 'resize');
            });
            
          } else {
            alert("Geocode "+ addr + " was not successful for the following reason: " + status);
          }
        });
        
            
      }
      
      google.maps.event.addDomListener(window, 'load', initialize);
      /*
      function detectBrowser() {
        var useragent = navigator.userAgent;
        var mapdiv = document.getElementById("map-canvas");

        if (useragent.indexOf('iPhone') != -1 || useragent.indexOf('Android') != -1 ) {
          mapdiv.style.width = '100%';
          mapdiv.style.height = '100%';
        } else {
          mapdiv.style.width = '600px';
          mapdiv.style.height = '800px';
        }
      }
      */

})(jQuery, document, window, google)