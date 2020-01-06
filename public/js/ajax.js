$(document).ready(function() {
    var askForMore = true;
    $('#load-gif').hide();

    $("#com-box-all").on('click', '.add-comment-btn', function() {
        var isAuthed = $('input[name="user_id"]').attr('value');
        if (isAuthed === undefined) {
            // wyswietl ze musi sie zalogowac
            console.log('zaloguj sie! add comm');
            return;
        }

        var com_id = $(this).attr('com-box-id') || null;
        var user_id = $('input[name="user_id"]').attr('value');
        var author_id = $('input[name="author_id"]').attr('value');
        var msg = com_id == null ? 
            $('#comment-input').val() :
            $("textarea[com-box-id='"+ com_id +"']").val();
        // var is_sub = $(this).attr('is_sub') || null;
        
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
                message: msg,
                post_id: $('input[name="post_id"]').attr('value'),
                sub_of: com_id
            },
            dataType: 'JSON',
            success: function (data) {
                var html = getComment(data.avatar, data.name, data.user_id == author_id, data.message, data.com_id, data.sub_of != undefined, data.likes)

                if (data.sub_of) {
                    $("div[com-id='"+ data.sub_of +"']").after(html);
                    $("button[com-box-id='"+ data.com_id +"'");
                } else {
                    $('#comments').prepend(html);
                    // $('#comments').append(html);
                }
                $('#comment-input').val(' ');
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
            },
            complete: function (data, status) {
                btn.removeClass('inactive');
                if (btn.attr('is-sub')) {
                    btn.parent().remove();
                }
            }
        });
    });

    $("#comments").on('click', '.reply', function(e) {
        var isAuthed = $('input[name="user_id"]').attr('value');
        if (isAuthed === undefined) {
            // wyswietl ze musi sie zalogowac
            console.log('zaloguj sie! comm reply');
            return;
        }

        var com_id = $(this).parent().parent().parent().attr('com-id');
        var is_sub = $(this).parent().parent().parent().attr('is-sub');

        if( $("textarea[com-box-id='"+com_id+"'").length === 0 ) {
            var html = `
                <div class='flex'>
                    <textarea is-sub='${is_sub}' com-box-id='${com_id}' name="commentBox" placeholder="Napisz coś.." class='border rounded-l-lg w-full p-1'></textarea>
                    <button is-sub='${is_sub}' com-box-id='${com_id}' class='add-comment-btn uppercase text-xs bg-blue-500 text-white rounded-r px-4 py-1 shadow hover:bg-blue-400'>Dodaj komentarz</button>
                </div>
            `;
    
            $("div[com-id='"+ com_id +"']").children('.left-com-box').append(html);
        }
    });

    // $('#get-coms').on('click', function() {
    //     $('input[name="page_num"]').attr('value', parseInt($('input[name="page_num"]').attr('value')) + 1);
    //     getMoreComments();
    // });

    $("div").on('click', '.LikeAssButton', function(e) {
        e.stopImmediatePropagation();

        var isAuthed = $('input[name="user_id"]').attr('value');
        if (isAuthed === undefined) {
            // wyswietl ze musi sie zalogowac
            console.log('zaloguj sie!');
            return;
        }

        var id = parseInt($(this).parent().parent().attr('com-id'));
        var likes = parseInt($(this).parent().find("span").text().slice(1));
        
        if ($(this).hasClass('com-liked')) {
            likes -= 1;
        } else {
            likes += 1;
        }

        $(this).toggleClass('com-liked');
        $(this).toggleClass('com-not-liked');

        $(this).parent().find('span').text('+' + likes);

        if (!$(this).hasClass('processing')) {
            giveLike(id, 1, this);
            $(this).toggleClass('processing', true);
        }

        console.log(`Dajesz lajka komentarzowi: ${id}`);
    });

    $("div").on('click', 'p.reportClick', function(e) {
        e.stopImmediatePropagation();
        var id = parseInt($(this).attr('com-id'));

        var isAuthed = $('input[name="user_id"]').attr('value');
        if (isAuthed === undefined) {
            // wyswietl ze musi sie zalogowac
            console.log('zaloguj sie! // report');
            return;
        }
        if (!$(this).hasClass('com-reported')) {
            $(this).toggleClass('com-reported', true);
            
            report(id);
        }
    });

    $("#ass-like").on('click', function(e) {
        var isAuthed = $('input[name="user_id"]').attr('value');
        if (isAuthed === undefined) {
            // wyswietl ze musi sie zalogowac
            console.log('zaloguj sie!');
            return;
        }

        if (!$(this).hasClass('post-liked')) {
            var id = parseInt($('input[name="post_id"]').attr('value'));
            
            $(this).toggleClass('post-liked', true);
            $(this).find('span').text(parseInt($(this).find('span').text()) + 1);
            
            giveLike(id, 2, false);
        }
    });

    $(window).scroll(function() {
        if ($(window).scrollTop() / ($(document).height() - $(window).height()) * 100 >= 65 && askForMore) {
            $('input[name="page_num"]').attr('value', parseInt($('input[name="page_num"]').attr('value')) + 1);
            getMoreComments();
            askForMore = false;
        }
    });

    getMoreComments();

    function getMoreComments() {
        $('#load-gif').show();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: '/ajax/comment',
            type: 'GET',
            data: {
                post_id: $('input[name="post_id"]').attr('value'),
                page_num: $('input[name="page_num"]').attr('value')
            },
            dataType: 'JSON',
            success: function (data) {
                // var userName = $('input[name="user_name"]').attr('value');
                var author_id = $('input[name="author_id"]').attr('value');
    
                Object.entries(data.comments).forEach(([k, v])=>{
                    var html = getComment(v.author_avatar, v.author_name, v.author_id == author_id, v.message, v.id, false, v.likes, v.is_liked, v.isRemoved, v.tw_author_name);
    
                    $('#comments').append(html);
                });

                Object.entries(data.sub_comments).forEach(([k, v])=>{
                    Object.entries(v).forEach(([k, v])=>{
                        var html = getComment(v.author_avatar, v.author_name, v.author_id == author_id, v.message, v.com_id, true, v.likes, v.is_liked, v.isRemoved, v.tw_author_name) // przedostatnio argument com id
                        var sub_of = v.sub_of;
    
                        $("div[com-id='"+ sub_of +"']").after(html);
                    })
                });

                if (data.num >= 5) {
                    askForMore = true;
                }
            },
            error: function (data) {
                var errors = $.parseJSON(data.responseText);
            },
            complete: function(data, status) {
                $('#load-gif').hide();
            }
        });
    };

    function giveLike(id_, type, a_tag) { // type == 1 - com, 2 - post
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var d;
        if (type == 1) {
            d = { com_id: id_ };
        } else if (type == 2) {
            d = { post_id: id_ };
        }

        $.ajax({
            url: '/ajax/like',
            type: 'GET',
            data: d,
            dataType: 'JSON',
            success: function (data) {
                // nic
            },
            error: function (data) {
                // nie jestes zalogwany 
            },
            complete: function(data, status) {
                if (type == 1) {
                    $(a_tag).toggleClass('processing', false);
                } 
            }
        });
    };

    function report(id_) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: '/ajax/report',
            type: 'GET',
            data: {
                id: id_
            },
            dataType: 'JSON',
            success: function (data) {
                // 
            },
            error: function (data) {
                //
            },
            complete: function(data, status) {
                // $(a_tag).toggleClass('processing', false);
            }
        });
    }

    function addError(errorMsg, ms = 2500) {
        var id = Math.round(Math.random());
        $("#com-box").after("<div id='"+ id +"' class='bg-red-100 border-red-700 text-red-600 p-2 border m-3 text-sm' role='alert'>" + errorMsg + "</div>");

        setTimeout(function() {
            $('#' + id).remove();
        }, ms);
    };

    function getComment(avatar, name, is_author, message, com_id, is_sub, likes=0, is_liked, is_removed, tw_name) {
        var html = '';
        var is_rank = $('input[name="is_rank"]').attr('value') !== undefined;
        var deleted = '<span class="text-gray-500 italic">[Komentarz został usunięty]';
        var isAuthed = $('input[name="user_id"]').attr('value') !== undefined;

        if (is_sub) {
            html += `<div class='flex ml-12' com-id='`+ com_id +`' is-sub='true'>`;
        } else {
            html += `<div class='flex' com-id='`+ com_id +`' is-sub='false'>`;
        }

        var likeClass = is_liked ? 'com-liked' : 'com-not-liked';
        
        html += `
            <div class='px-2 flex flex-col items-center'>
                <img src="${avatar}" class="w-8 rounded-full" alt="avatar">
                <span class='text-green-700'>+${likes}</span>
                <div class='tooltip LikeAssButton select-none cursor-pointer ${likeClass}'>
                    <i class="material-icons md-18 p-1 hover:text-white hover:bg-blue-500 border border-blue-500 rounded-full">thumb_up</i>
                    ${isAuthed ? '' : '<span class="tooltiptext text-xs lowercase">Musisz być zalogowany!</span>'}
                </div>
            </div>
            <div class='left-com-box w-full'>
                <a href="/x/${name}" class="flex items-center text-xs text-gray-600">
                    <span class="font-bold">${name} ${tw_name ? '(<span class="text-purple-800">' + tw_name + '</span>)' : ''}</span>`;
        if (is_author) {
            html += '<i class="material-icons md-18 ml-1 hover:text-blue-500">person_pin</i>';
        }
        // <p class='cursor-pointer px-2 hover:bg-gray-200' com-id="` + com_id + `">Report</p>
        var html2 = `</a>
                    <p class='text-sm'>
                        ${is_removed ? deleted : message}
                    </p>
                    <div class='tooltip'>
                        <i type='sub-com' class="reply material-icons md-18 mr-1 cursor-pointer">reply</i>
                        ${isAuthed ? '' : '<span class="tooltiptext text-xs lowercase">Musisz być zalogowany!</span>'}
                    </div>
                    <div class="more-menu inline relative">
                        <i class="material-icons md-18 mr-1 hover:text-gray-700 cursor-pointer">more_horiz</i>
                        <div class='bg-white absolute rounded shadow border hidden select-none'>
                            <p class='reportClick cursor-pointer px-2 hover:bg-gray-200 flex items-center border-b' com-id="` + com_id + `">
                                <i class="material-icons md-18 pr-2">report</i>
                                Report
                            </p>
                            `;
        if (is_rank) {
            html2 += `
                <p class='cursor-pointer px-2 hover:bg-gray-200 flex items-center border-b' com-id="` + com_id + `">
                    <i class="material-icons md-18 pr-2">gavel</i>
                    Ban
                </p>
                <p class='cursor-pointer px-2 hover:bg-gray-200 flex items-center border-b' com-id="` + com_id + `">
                    <i class="material-icons md-18 pr-2">delete</i>
                    Usuń
                </p>
            `;
        }
                            
        html2 += `</div>
                    </div>
                </div>
        `;

        html += html2;

        if (!is_sub) {
            html = '<div class="mb-4 border rounded-lg p-1 bg-blue-100">' + html;
            html += '</div>';
        }

        return html;
    };
});