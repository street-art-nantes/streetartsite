import $ from "jquery"

var $collectionHolder;

jQuery(document).ready(function() {
  // Get the ul that holds the collection of tags
  $collectionHolder = $('div.documents');
  var $addTagLink = $('.add_document_link');

  // count the current form inputs we have (e.g. 2), use that as the new
  // index when inserting a new item (e.g. 2)
  $collectionHolder.data('index', $collectionHolder.find(':input').length);

  $addTagLink.on('click', function(e) {
    // prevent the link from creating a "#" on the URL
    e.preventDefault();

    // add a new tag form (see next code block)
    addTagForm($collectionHolder);
  });

  $collectionHolder.find('div.form-collection-item').each(function() {
    addTagFormDeleteLink($(this));
  });
});

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

  // Display the form in the page in an li, before the "Add a tag" link li
  var $newFormLi = $('<div class="form-collection-item"></div>').append(newForm);
  $collectionHolder.append($newFormLi);

  addTagFormDeleteLink($newFormLi);
}

function addTagFormDeleteLink($tagFormLi) {
  var $removeFormA = $('<a href="#">Supprimer</a>'); // Todo change this by an icon
  $tagFormLi.append($removeFormA);

  $removeFormA.on('click', function(e) {
    // prevent the link from creating a "#" on the URL
    e.preventDefault();

    // remove the li for the tag form
    $tagFormLi.remove();
  });
}