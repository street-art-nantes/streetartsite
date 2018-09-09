import $ from "jquery"

var $collectionHolder;

jQuery(document).ready(function() {
  // Get the ul that holds the collection of tags
  $collectionHolder = $('#artwork_documents');
  var $addTagLink = $('.add-document-link');

  // count the current form inputs we have (e.g. 2), use that as the new
  // index when inserting a new item (e.g. 2)
  $collectionHolder.data('index', $collectionHolder.find(':input').length);

  $addTagLink.on('click', function(e) {
    // prevent the link from creating a "#" on the URL
    e.preventDefault();

    // add a new tag form (see next code block)
    addTagForm($collectionHolder);
  });

  $collectionHolder.find('.item-document').each(function() {
    addTagFormDeleteLink($(this));
  });

  $('body').on('click', '.field-image-btn-gps', function(e) {
    const lat = $(e.currentTarget).data('lat')
    const long = $(e.currentTarget).data('long')

    if (lat && long) {
      $('#artwork_poi_latitude').val(lat);
      $('#artwork_poi_longitude').val(long);
      getAddressFromCoordinates();
    }
  })
});

function getAddressFromCoordinates() {
    var lat = $('#artwork_poi_latitude').val();
    var long = $('#artwork_poi_longitude').val();
    const key = 'AIzaSyAW-nTdgEOCmpA9z7mblXxh8jh4bm0yV-A';
    $.ajax({
        url : 'https://maps.googleapis.com/maps/api/geocode/json',
        type : 'GET',
        dataType : 'json',
        data : 'latlng=' + lat + ',' + long + '&key=' + key,
        success : function(data){
            var result = data.results.shift();
            $.each(result['address_components'], function( key, value ) {
                if (value.types.includes('administrative_area_level_1')) {
                    $('#artwork_poi_city').val(value.long_name);
                } else if (value.types.includes('country')) {
                    $('#artwork_poi_country').val(value.long_name);
                }
            });

            $('#artwork_poi_address').val(result['formatted_address']);
        }
    });
}

function addTagForm($collectionHolder) {
  // Get the data-prototype explained earlier
  var prototype = $collectionHolder.data('prototype');

  // get the new index
  var index = $collectionHolder.data('index');

  var newForm = prototype;
  // You need this only if you didn't set 'label' => false in your tags field in TaskType
  // Replace '__name__label__' in the prototype's HTML to
  // instead be a number based on how many items we have
  // newForm = newForm.replace(/__name__label__/g, index);

  // Replace '__name__' in the prototype's HTML to
  // instead be a number based on how many items we have
  newForm = newForm.replace(/__name__/g, index);

  // increase the index with one for the next item
  $collectionHolder.data('index', index + 1);

  var $newForm = $(newForm);

  $collectionHolder.append($newForm);

  addTagFormDeleteLink($newForm);
}

function addTagFormDeleteLink($tagFormLi) {
  var $removeFormA = $('<button class="btn btn-danger btn-sm">Supprimer</button>'); // Todo change this by an icon
  $tagFormLi.find('label .field-image-actions').append($removeFormA);

  $removeFormA.on('click', function(e) {
    // prevent the link from creating a "#" on the URL
    e.preventDefault();

    // remove the li for the tag form
    $tagFormLi.remove();
  });
}