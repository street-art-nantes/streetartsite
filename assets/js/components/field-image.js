import $ from "jquery"

$(document).ready(function () {

  $('body').on('change', '.field-image input[type=file]', function (e) {

    let $container = $(e.currentTarget).closest('.field-image').find('.field-image-link-wrapper')

    console.log($(e.currentTarget).closest('.field-image'));
    console.log($container[0]);

    const file = e.currentTarget.files[0];
    const reader = new FileReader();

    reader.onloadend = function () {
      const $img = $('<img />');
      $img.addClass('field-image-link-img')
      $img.attr('src', reader.result)
      $container.html($img)
    }

    if (file) {
      reader.readAsDataURL(file);
    } else {
      $container.empty()
    }
  })

})
