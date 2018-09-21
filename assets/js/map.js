import $ from 'jquery'
import './vendors/leaflet/leaflet.js'
import './vendors/leaflet/leaflet.markercluster.js'
import './vendors/leaflet/Leaflet.Photo.js'

var map;

const resizeContentMapHeight = () => {
  const $contentMap = $('.content-map');
  const $header = $('#header');
  const $footer = $('#footer');

  const wh = $(window).height();
  const heightHeader = $header.height();
  const heightFooter= $footer.height();

  $contentMap.height(wh - heightFooter - heightHeader);
}

const initMap = () => {
  map = L.map('map', { scrollWheelZoom:false }).setView([30, 8], 2);

  L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
    maxZoom: 18,
    attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' +
    '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
    id: 'mapbox.streets'
  }).addTo(map);

  const photoLayer = L.photo.cluster().on('click', function (evt) {
    const photo = evt.layer.photo,
      template = '<a href="{artworkUrl}"><img src="{url}"/><p>{caption}</p></a>';

    evt.layer.bindPopup(L.Util.template(template, photo), {
      className: 'leaflet-popup-photo',
      minWidth: 400
    }).openPopup();
  });

  const datas = window.datas;
  let photos = [];
  const data = JSON.parse(datas);
  for (let i = 0; i < data.length; i++) {
    const photo = data[i];
    photos.push({
      id: photo.id,
      timestamp: parseInt(photo.timestamp),
      lat: parseFloat(photo.lat),
      lng: parseFloat(photo.lng),
      url: photo.imgUrl,
      caption: photo.caption,
      iconUrl: photo.iconUrl,
      artworkUrl: photo.artworkUrl
    });
  }
  photoLayer.add(photos).addTo(map);
  L.control.scale().addTo(map);
  // map.fitBounds(photoLayer.getBounds());
}

$(function () {
  resizeContentMapHeight();
  initMap();

    function maPosition(position) {
        var infopos = "<button id='btn-showaround' data-lat='"+position.coords.latitude+"' data-lng='"+position.coords.longitude+"' type='button' class='btn btn-primary'>" + translations.aroundme + "Voir autour de moi</button>";
        $('#showaround').html(infopos).show();

        $('#btn-showaround').on('click', function () {
            // leaf version 0.7.2 so no flyTo function
            map.setView([$(this).attr('data-lat'), $(this).attr('data-lng')], 14);
        });
    }

    if(navigator.geolocation)
        navigator.geolocation.getCurrentPosition(maPosition);
})
