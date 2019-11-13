$(document).ready(function() {
    $("#add-comment-btn").click(function() {
        if ($(this).hasClass('inactive')) {
            return;
        }

        $(this).addClass('inactive');
        var btn = $(this);

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: '/ajax/comment',
            type: 'POST',
            data: {
                message: $("#comment-input").val(),
                post_id: $('input[name="post_id"]').attr('value')
            },
            dataType: 'JSON',
            success: function (data) {
                var html = getComment(data.avatar, data.name, data.is_author, data.message, data.com_id, false)

                $('#comments').prepend(html);
            },
            // inactive
            error: function (data) {
                var errors = $.parseJSON(data.responseText);
                if (errors['errors'] && errors['errors'].message) {
                    addError(errors['errors'].message, 2500);
                }
                if (data.status == 401) {
                    addError('Musisz sie zalogowac aby to zrobic! Mozliwe jest zalogowanie sie przez Twitch.tv', 2000);
                }
                console.log(data);
            },
            complete: function (data, status) {
                btn.removeClass('inactive');
                // console.log(data, status);
            }
        });
    });

    $(".reply").on('click', function() {
        var com_id = $(this).parent().parent().attr('com-id');
        var is_sub = $(this).parent().parent().attr('is-sub');
        console.log('yes');
        var html = `
            <div class='flex' com-box-id='${com_id}' is-sub='${is_sub}'>
                <textarea id='comment-input' name="commentBox" placeholder="Napisz coś.." class='border rounded-l-lg w-full p-1'></textarea>
                <button id='add-comment-btn' class='uppercase text-xs bg-blue-500 text-white rounded-r px-4 py-1 shadow hover:bg-blue-400'>Dodaj komentarz</button>
            </div>
        `;

        $("div[com-id='"+ com_id +"']").children('.left-com-box').append(html);
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: '/ajax/comment',
        type: 'GET',
        data: {
            message: $("#comment-input").val()
        },
        dataType: 'JSON',
        success: function (data) {
            
        },
        error: function (data) {
            var errors = $.parseJSON(data.responseText);
        },
    }); 

    function addError(errorMsg, ms = 2500) {
        var id = Math.round(Math.random());
        $("#com-box").after("<div id='"+ id +"' class='bg-red-100 border-red-700 text-red-600 p-2 border m-3 text-sm' role='alert'>" + errorMsg + "</div>");

        setTimeout(function() {
            $('#' + id).remove();
        }, ms);
    };

    function getComment(avatar, name, is_author, message, com_id, is_sub) {
        var html = '';

        if (is_sub) {
            html += `<div class='flex ml-12' com-id='`+ com_id +`' is-sub='true'>`;
        } else {
            html += `<div class='flex' com-id='`+ com_id +`' is-sub='false'>`;
        }
        
        html += `
            <div class='px-2 flex flex-col items-center'>
                <img src="/${avatar}" class="w-8 rounded-full" alt="avatar">
                <span class='text-green-700'>+0</span>
                <a href="">
                    <i class="material-icons md-18 p-1 hover:text-white hover:bg-blue-500 border border-blue-500 rounded-full">thumb_up</i>
                </a>
            </div>
            <div class='left-com-box'>
                <a href="" class="flex items-center text-xs text-gray-600">
                    <span class="font-bold">${name}</span>`;
        if (is_author) {
            html += '<i class="material-icons md-18 ml-1 hover:text-blue-500">person_pin</i>';
        }
        
        var html2 = `</a>
                    <p class='text-sm'>
                        ${message}
                    </p>
                    <i class="reply material-icons md-18 mr-1 hover:text-gray-700 cursor-pointer">reply</i>
                    <i class="material-icons md-18 mr-1 hover:text-gray-700 cursor-pointer">more_horiz</i>
                </div>
        `;

        html += html2;

        if (!is_sub) {
            html = '<div class="mb-4 border rounded-lg p-1 bg-blue-100">' + html;
            html += '</div>';
        }

        return html;
    }
});