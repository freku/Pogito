$(document).ready(function() {
    $("div").on('click', '.ActionButton', function(e) {
        e.stopImmediatePropagation();

        if (!$(this).parent().hasClass('action-done')) {
            
            var id = parseInt($(this).attr('r-id'));
            var com_id = parseInt($(this).attr('com-id'));
            var action = parseInt($(this).attr('action'));
            
            $(this).parent().toggleClass('action-done', true);
            reportAction(id, com_id, action);
        }
    });

    function reportAction(id_r, com_id, action) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: '/ajax/report/action',
            type: 'GET',
            data: {
                id: id_r,
                com_id: com_id,
                action: action
            },
            dataType: 'JSON',
            success: function (data) {
                
            },
            error: function (data) {

            },
            complete: function(data, status) {
                
            }
        });
    };
});