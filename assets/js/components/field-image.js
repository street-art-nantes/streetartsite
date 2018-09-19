import $ from "jquery"
import exif from 'exif-js'

$(document).ready(function () {

  const rational64uToDecimal = (n) => {
    const degrees = parseFloat(n[0]);
    const minutes = parseFloat(n[1]);
    const seconds = parseFloat(n[2]);

    return degrees + minutes/60 + seconds/3600
  }

  $('body').on('change', '.field-image input[type=file]', function (e) {
    let $containerImg = $(e.currentTarget).closest('.field-image').find('.field-image-link-wrapper')
    let $containerBtn = $(e.currentTarget).closest('.field-image').find('.field-image-actions')

    $containerImg.empty()
    $containerBtn.find('.field-image-btn-gps').remove();

    const file = e.currentTarget.files[0];
    const reader = new FileReader();

    reader.onloadend = function () {
      const $img = $('<img />');
      $img.addClass('field-image-link-img')
      $img.attr('src', reader.result)
      $containerImg.html($img)
    }

    if (!file) {
      return;
    }

    reader.readAsDataURL(file);

    // Parse lat long exif metadata from image
    // and create a button with data-lat and data-long into it
    exif.getData(file, function() {
      const latRational64u = exif.getTag(this, "GPSLatitude");
      const longRational64u = exif.getTag(this, "GPSLongitude");

      switch(parseInt(exif.getTag(this, "Orientation"))) {
          case 2:
              $containerImg.addClass('flip'); break;
          case 3:
              $containerImg.addClass('rotate-180'); break;
          case 4:
              $containerImg.addClass('flip-and-rotate-180'); break;
          case 5:
              $containerImg.addClass('flip-and-rotate-270'); break;
          case 6:
              $containerImg.addClass('rotate-90'); break;
          case 7:
              $containerImg.addClass('flip-and-rotate-90'); break;
          case 8:
              $containerImg.addClass('rotate-270'); break;
      }

      if (latRational64u && longRational64u) {
        const $btnLocation = $('<button type="button" class="btn btn-sm btn-dark" data-placement="top" title="Use GPS location"><i class="fa fa-globe"></i></button>');
        var latRef = (exif.getTag(this, "GPSLatitudeRef") == 'S') ? '-' : '';
        var longRef = (exif.getTag(this, "GPSLongitudeRef") == 'W') ? '-' : '';

        $btnLocation.addClass('btn btn-sm field-image-btn-gps');
        $btnLocation.attr('data-lat', latRef + rational64uToDecimal(latRational64u));
        $btnLocation.attr('data-long', longRef + rational64uToDecimal(longRational64u));

        $containerBtn.prepend($btnLocation);
        $btnLocation.tooltip();
      }
    });

  })

})
