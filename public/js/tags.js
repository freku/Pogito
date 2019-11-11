$(document).ready(function() {

    $(".tag").on('click', function() {
        var tags = $('#tags');
        tags.val(tags.val() + $(this).text() + ' ');
    });
});