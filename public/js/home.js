$(document).ready(function() {
    var askForMore = true;
    $('#load-gif').hide();
    getPosts();

    // $('#get-coms').on('click', function() {
    //     $('input[name="page_num"]').attr('value', parseInt($('input[name="page_num"]').attr('value')) + 1);
    //     getPosts();
    // });

    $(window).scroll(function() {
        var perc = $(window).scrollTop() / ($(document).height() - $(window).height()) * 100;

        if (perc >= 65 && askForMore) {
            $('input[name="page_num"]').attr('value', parseInt($('input[name="page_num"]').attr('value')) + 1);
            getPosts();
            askForMore = false;
        }
    });

    $('div').on('click', '.likable', function(e) {
        e.stopImmediatePropagation();

        var isAuthed = $('input[name="user_id"]').attr('value');
        if (isAuthed === undefined) {
            console.log('zaloguj sie!');
            return;
        }

        if (!$(this).hasClass('liked-home-post-puss')) {
            var id = parseInt($(this).parent().parent().attr('post-id'));
            $(this).find('div span').text(parseInt($(this).find('div span').text()) + 1);
            $(this).toggleClass('liked-home-post-puss');
            giveLike(id, 2, false);
        }
    });

    function getPosts() {
        $('#load-gif').show();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: '/ajax/post',
            type: 'GET',
            data: {
                page_num: $('input[name="page_num"]').attr('value'),
                sort_type: $('input[name="sort_type"]').attr('value')
            },
            dataType: 'JSON',
            success: function (data) {
                Object.entries(data.posts).forEach(([k, v])=>{
                    var p = v.post;
                    var html = getPost(p.thumbnail_url, p.streamer_name, v.tags, v.author, p.created_at, p.title, p.likes, p.comments, p.post_url, p.time_passed, p.id, p.is_liked);
    
                    $('#clips').append(html);

                    if (data.num >= 3) {
                        askForMore = true;
                    }
                });
            },
            error: function (jqXHR, textStatus, errorThrown) {
                // var errors = $.parseJSON(data.responseText);
            },
            complete: function(data, status) {
                $('#load-gif').hide();
            }
        });
    }

    function getPost(thumbnail_url, streamer_name, tags, author, created_at, title, likes, comments, post_url, time_passed, post_id, is_liked) {
        var html = '';
        var colors = ['red', 'orange', 'yellow', 'green', 'teal', 'blue', 'purple', 'pink'];
        var likeClass = is_liked ? 'liked-home-post-puss' : '';
        // var time_place = '';
        html += `
            <div class='lg:w-4/6 w-full bg-white mx-2 mt-1 mb-2 p-2 rounded-lg shadow-lg gradient-s' post-id=${post_id}>
                <div class="flex justify-center relative">
                    <div class='rounded bg-gray-500' style="height: 272px;"></div>
                    <a href="${post_url}" target="_blank" class='w-full'>
                        <img src="${thumbnail_url}" class="w-full rounded" style='object-fit: contain;' alt="bg">
                    </a>
                    <div class="text-xs px-1 rounded absolute text-white font-bold" style="top:5px;right:5px;background:rgba(0,0,0,.3);">
                        <span class='uppercase'>${streamer_name}</span>
                    </div>

                    <div class='absolute flex p-2' style="bottom:0;left:0;">`;
        Object.entries(tags).forEach(([k, v])=>{
            var ran_color = colors[Math.floor((Math.random() * colors.length) + 1) - 1];

            html += `<a href='' class='bg-${ran_color}-600 px-1 rounded-full text-white text-xs mr-1'>${v.name}</a>`;
        });
        html +=     `</div>
                </div>
                
                <div class='flex justify-between text-gray-500 text-xs py-1'>
                    <a href="" class="hover:text-black">${author}</a>
                    <span>${time_passed}</span>
                </div>
                
                <div class="f-sec font-bold text-center overflow-hidden">
                    <a href="${post_url}" target="_blank">
                        <p>${title}</p>
                    </a>
                </div>
                
                <div class='flex justify-between text-gray-500 mt-2'>
                    <div class='flex cursor-pointer likable select-none ${likeClass}'>
                        <div class="flex items-center text-xs">
                            <i class="material-icons md-14 mr-1">thumb_up</i>
                            <span class="text-green-500 font-bold">${likes}</span>
                        </div>
                    </div>

                    <a href="" class="flex items-center text-xs hover:text-black">
                        <i class="material-icons md-14 mr-1">comment</i>
                        <span>${comments}</span>
                    </a>
                </div>
            </div>
        `;

        return html;
    }

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
                // ;)
            },
            error: function (data) {
                if (data.responseJSON == 'no login') {
                    console.log('no login be!');
                }
            },
            complete: function(data, status) {
                // no
            }
        });
    };
});

function redirectSort(opt, opt2)
{
    if (opt == 1) {
        document.location.href = "http://localhost:8000/";
    } else if (opt == 2) {
        document.location.href = "http://localhost:8000/sort-new";
    } else if (opt == 3 && opt2 == 'login') {
        document.location.href = "http://localhost:8000/login";
    }
}