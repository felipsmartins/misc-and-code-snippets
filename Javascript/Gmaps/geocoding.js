/**
 * O ID do elemento no DOM que conterá o mapa a ser carregado
 * @type String
 */
var MAP_CONTAINER_ELEMENTID = 'map-canvas';

/**
 *  Zoom do mapa. Máximo é  18
 *  @type Int
 */
var MAP_ZOOM = 16;
var geocoder;
var map;

function initialize() {
  geocoder = new google.maps.Geocoder();
  var latlng = new google.maps.LatLng(-34.397, 150.644); /*Por padrão -34.397, 150.644: São Paulo, Brasil*/
  var mapOptions = {
    zoom: MAP_ZOOM,
    center: latlng
  }
  map = new google.maps.Map(document.getElementById(MAP_CONTAINER_ELEMENTID), mapOptions);
}


/**
 * Obtém o as coordenadas lat-long baseado no endereço, ou seja, o geolocalização (inverso de Reverse Geolocation)
 */
function setGeocodeByAddress(address) {
  geocoder.geocode( { 'address': address}, function(results, status) {
    if (status == google.maps.GeocoderStatus.OK) {
      map.setCenter(results[0].geometry.location);
      var marker = new google.maps.Marker({
          map: map,
          position: results[0].geometry.location
      });
    } else {
      console.log('Não foi possível fazer a geolocalização pelo motivo: ' + status);
    }
  });
}
