import "bootstrap/dist/js/bootstrap.bundle.min"
import $ from 'jquery'

import "./components/field-image"

$(function () {
  $('[data-toggle="tooltip"]').tooltip()
});

// exit popup
function addEvent(obj, evt, fn) {
  if (obj.addEventListener) {
    obj.addEventListener(evt, fn, false);
  }
  else if (obj.attachEvent) {
    obj.attachEvent("on" + evt, fn);
  }
}

addEvent(document, 'mouseout', function(evt) {
  if (!evt.toElement && !evt.relatedTarget && !localStorage.getItem('exitstreetartwork')) {
    $('#follow-us').modal();
    localStorage.setItem('exitstreetartwork', 'true');
  }
});
