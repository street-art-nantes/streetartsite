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

    const datas = window.datas;
    let photos = [];
    const data = JSON.parse(datas);
    const photo = data[0];
    photos.push({
        id: photo.id,
        timestamp: parseInt(photo.timestamp),
        lat: parseFloat(photo.lat),
        lng: parseFloat(photo.lng),
        url: photo.url,
        caption: photo.caption,
        iconUrl: photo.iconUrl
    });

  var map = L.map('map', { scrollWheelZoom:false }).setView([photo.lat, photo.lng], 14);

  L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
    maxZoom: 18,
    attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' +
    '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
    id: 'mapbox.streets'
  }).addTo(map);

    L.photo.cluster().add(photos).addTo(map);
}

$(function () {
  // resizeContentMapHeight();
  initMap();
})