import $ from "jquery"

jQuery(document).ready(function() {

    $(function() {
        $('#search-artist').on('keyup', function() {
            var pattern = $(this).val();
            console.log(pattern);
            $('.searchable-container a[class*="items"]').attr('style','display:none !important');
            $('.searchable-container a[class*="items"]').filter(function() {
                return $(this).find('h2').text().match(new RegExp(pattern, 'i'));
            }).show();
        });
    });
});
