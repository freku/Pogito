@extends('layouts.app')

@section('title', 'View page')

@section('content')
<script src={{ asset('js/ajax.js') }}></script>

{{-- <div class='w-full h-full fixed flex justify-center inset-0 z-10 ' style='background: rgba(0,0,0,.5);'>
    <div class='bg-white h-12 w-1/4'>
        <p>Tag</p>
    </div>
</div> --}}
<div class="container mx-auto justify-center flex mt-2">
    <div class='bg-white rounded shadow-lg p-2 lg:w-1/2 md:w-4/6 w-full'>
        <div class="h_iframe">
            <img class="ratio" src="{{URL('/images/img.jpg')}}"/>
            <iframe src="{{ $clip_url }}" frameborder="0" allowfullscreen="true" autoplay='false'></iframe>
        </div>
        
        <div class="flex pt-2">
            <div class='w-1/6'>
                <a href="" class="flex items-center flex-col text-xs hover:text-black">
                    {{-- <img src="{{URL('/images/avk.jpeg')}}" class="w-8 rounded-full" alt="avatar"> --}}
                    <img src="{{URL($avatar)}}" class="w-8 rounded-full" alt="avatar">
                    <span>{{ $autor }}</span>
                    <!-- Skrocic nick jesli jest za dlugo -->
                </a>
            </div>

            <div class='w-5/6'>
                <!-- tagi -->
                <div>
                    <div class='flex'>
                        @forelse ($tags as $tag)
                            <a href='' class='bg-red-600 px-1 text-white text-xs mr-1 rounded'>{{ $tag->tag->name }}</a>
                        @empty
                            <span class="px-1 text-gray-600 text-xs">Brak tagów</span>
                        @endforelse
                        {{-- <a href='' class='bg-orange-600 px-1 text-white text-xs mr-1 rounded'>#Śmieszne</a>
                        <a href='' class='bg-red-600 px-1 text-white text-xs mr-1 rounded'>#NSFW</a>
                        <a href='' class='bg-green-600 px-1 text-white text-xs mr-1 rounded'>#Fail</a> --}}
                    </div>
                </div>
                <!-- title -->
                {{-- <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum mollis interdum ornare. Phasellus non.</p> --}}
                <p>{{ $tytul }}</p>
                <!-- time ago -->
                <div class="flex items-center justify-end text-xs hover:text-black">
                    <i class="material-icons md-14 mr-1">access_time</i>
                    {{-- <span>4 godz. temu</span> --}}
                    <span>{{ $time }}</span>
                </div>
            </div>
        </div>

        <div class="flex justify-center p-1">
            <div class='flex'>
                <div id='ass-like' class="flex items-center text-md p-2 border rounded shadow hover:border-blue-500 bg-orange-100 select-none cursor-pointer {{$is_liked ? 'post-liked' : ''}}">
                    <i class="material-icons md-18 mr-1">thumb_up</i>
                    <span class="text-green-500 font-bold">{{ $likes }}</span>
                </div>
                {{-- <a href="" class="flex items-center ml-1">
                    <i class="material-icons md-18 mr-1 hover:text-orange-500">thumb_down</i>
                </a> --}}
            </div>
        </div>
        
        <div class="mt-3" id='com-box-all'>
            <div class='mb-3'>
                {{-- <p>Komentarze: </p> --}}
                <div class='flex' id='com-box'>
                    @auth
                        <div class='flex items-center mr-2'>
                            <img src="{{URL(Auth::user()->avatar)}}" class="w-10 shadom-md rounded-full" alt="avatar">
                        </div>
                        <textarea id='comment-input' name="commentBox" placeholder="Napisz coś.." class='border rounded-l-lg w-full p-1'></textarea>
                        <button  class='add-comment-btn uppercase text-xs bg-blue-500 text-white rounded-r px-4 py-1 shadow hover:bg-blue-400'>Dodaj komentarz</button>
                        
                        <input type="hidden" name="user_id" value='{{ Auth::user()->id }}'>
                        <input type="hidden" name="user_name" value='{{ Auth::user()->name }}'>
                    @endauth

                    @if ($is_rank == true)
                        <input type="hidden" name="is_rank" value='1'>
                    @endif
                    
                    <input type="hidden" name="post_id" value='{{ $post_id }}'>
                    <input type="hidden" name="author_id" value='{{ $autor_id }}'>
                    <input type="hidden" name="page_num" value='1'>

                    @guest
                        <div class='flex items-center mr-2'>
                            <img src="{{URL('/images/avatars/basic.jpg')}}" class="w-10 shadom-md rounded-full" alt="avatar">
                        </div>
                        <textarea id='comment-input' name="commentBox" placeholder="Musisz być zalogowany, aby komentować" class='border rounded-l-lg w-full p-1'></textarea>
                        <button  class='add-comment-btn uppercase text-xs bg-blue-500 text-white rounded-r px-4 py-1 shadow hover:bg-blue-400'>Dodaj komentarz</button>
                        {{-- <input type="hidden" name="post_id" value='{{ $post_id }}'> --}}
                        {{-- <input type="hidden" name="user_id" value='{{ Auth::user()->id }}'> --}}
                        {{-- <input type="hidden" name="user_name" value='{{ Auth::user()->name }}'> --}}
                        {{-- <input type="hidden" name="author_id" value='{{ $autor_id }}'> --}}
                    @endguest
                    
                </div>
            </div>
            <div id='comments'>
                {{-- <div class="mb-4 border rounded-lg p-1 bg-blue-100">
                    <div class='flex'>
                        <div class='px-2 flex flex-col items-center'>
                            <img src="{{URL('/images/avk.jpeg')}}" class="w-8 rounded-full" alt="avatar">
                            <span class='text-green-700'>+2247</span>
                            <a href="">
                                <i class="material-icons md-18 p-1 hover:text-white hover:bg-blue-500 border border-blue-500 rounded-full">thumb_up</i>
                            </a>
                        </div>
                        <div class='w-full'>
                            <a href="" class="flex items-center text-xs text-gray-600">
                                <span class="font-bold">freku0</span>
                                <i class="material-icons md-18 ml-1 hover:text-blue-500">person_pin</i>
                            </a>
                            <p class='text-sm'>
                                Lorem ipsum dolor sit amet, 
                            </p>
                            <i class="reply material-icons md-18 mr-1 hover:text-gray-700 cursor-pointer">reply</i>
                            <i class="material-icons md-18 mr-1 hover:text-gray-700 cursor-pointer">more_horiz</i>
                        </div>
                    </div>

                    <div class='flex ml-12'>
                        <div class='px-2 flex flex-col items-center'>
                            <img src="{{URL('/images/avk.jpeg')}}" class="w-8 rounded-full" alt="avatar">
                            <span class='text-green-700'>+2247</span>
                            <a href="">
                                <i class="material-icons md-18 p-1 hover:text-white hover:bg-blue-500 border border-blue-500 rounded-full">thumb_up</i>
                            </a>
                        </div>
                        <div>
                            <a href="" class="flex items-center text-xs text-gray-600">
                                <span class="font-bold">freku0</span>
                                <i class="material-icons md-18 ml-1 hover:text-blue-500">person_pin</i>
                            </a>
                            <p class='text-sm'>
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed elit dolor, fermentum vitae posuere in,
                                ultrices a ipsum. Aenean gravida egestas orci, sit ametsollicitudin nulla porttitor finibus.
                            </p>
                            <i class="reply material-icons md-18 mr-1 hover:text-gray-700 cursor-pointer">reply</i>
                            <i class="material-icons md-18 mr-1 hover:text-gray-700 cursor-pointer">more_horiz</i>
                            {{-- <div class='flex' id='com-box'>
                                <textarea id='comment-input' name="commentBox" placeholder="Napisz coś.." class='border rounded-l-lg w-full p-1'></textarea>
                                <button id='add-comment-btn' class='uppercase text-xs bg-blue-500 text-white rounded-r px-4 py-1 shadow hover:bg-blue-400'>Dodaj komentarz</button>
                            </div> --}}
                        {{-- </div>
                    </div>
                </div> --}}
            </div>
            <div id='load-gif' class="w-full flex justify-center">
                <img src="{{URL('/images/load.gif')}}" alt="Loading icon" class="w-10">
            </div>
            {{-- <button id='get-coms'>get more coms</button> --}}
        </div>
    </div>
</div>
@endsection