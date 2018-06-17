import $ from 'jquery'
import './vendors/leaflet/leaflet.js'
import './vendors/leaflet/leaflet.markercluster.js'
import './vendors/leaflet/Leaflet.Photo.js'

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
  var map = L.map('map', { scrollWheelZoom:false }).setView([46.506755997519654, 7.699578983615311], 5);

  L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
    maxZoom: 18,
    attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' +
    '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
    id: 'mapbox.streets'
  }).addTo(map);

  const photoLayer = L.photo.cluster().on('click', function (evt) {
    const photo = evt.layer.photo,
      template = '<img src="{url}"/></a><p>{caption}</p>';

    evt.layer.bindPopup(L.Util.template(template, evt.layer.photo), {
      className: 'leaflet-popup-photo',
      minWidth: 400
    }).openPopup();
  });

  const datas = window.datas
  let photos = [];
  const data = JSON.parse(datas);
  for (let i = 0; i < data.length; i++) {
    const photo = data[i];
    photos.push({
      id: photo.id,
      timestamp: parseInt(photo.timestamp),
      lat: parseFloat(photo.lat),
      lng: parseFloat(photo.lng),
      url: photo.url,
      caption: photo.caption,
      iconUrl: photo.iconUrl
    });
  }
  photoLayer.add(photos).addTo(map);
  // map.fitBounds(photoLayer.getBounds());
}

$(function () {
  resizeContentMapHeight();
  initMap();
})