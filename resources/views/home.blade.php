@extends('layouts.app')

@section('title', 'Strona')

@section('content')
<script src={{ asset('js/home.js') }}></script>

{{-- <div class='w-full h-12 mt-10 fixed flex justify-center inset-0 z-10'>
    <div class='w-1/2 p-2 bg-red-200'>
        <p class='bg-white'>Nice paragraph!</p>
        <p class='bg-white'>Nice paragraph!</p>
        <p class='bg-white'>Nice paragraph!</p>
        <p class='bg-white'>Nice paragraph!</p>
    </div>
</div> --}}
<div class="container mx-auto mt-4 flex">
    <input type="hidden" name="page_num" value='1'>
    <input type="hidden" name="sort_type" value='{{ $sort_type ?? 1 }}'> {{-- 1 - popular, 2 - nowości --}}
    @auth
        <input type="hidden" name="user_id" value='{{ Auth::user()->id }}'>
    @endauth
    <!-- clips wrapper -->
    <div class="lg:w-2/3 sm:w-3/4 w-full">
        <div id='clips' class='flex justify-end flex-wrap w-full'>
            <div class='lg:w-4/6 w-full bg-white mx-2 mt-1 mb-2 p-2 rounded-lg shadow-lg gradient-s'>
                <span>Sortuj według: </span>
                <select id="sort_opt" class='border rounded' onchange="redirectSort(this.value);">
                    <option value="1" {{$sort_type == 1 ? 'selected' : ''}}>Popularności</option>
                    <option value="2" {{$sort_type == 2 ? 'selected' : ''}}>Nowości</option>
                </select>
            </div>
        </div>
        <div id='load-gif' class="w-full justify-end flex">
            <div class='lg:w-4/6 w-full mx-2 mt-1 mb-2 p-2 flex justify-center'>
                <img src="{{URL('/images/load.gif')}}" alt="Loading icon" style='height: 40px;'>
            </div>
        </div>
        
        <!-- clip box -->
        {{-- <div class='lg:w-4/6 w-full bg-white mx-2 mt-1 mb-2 p-2 rounded-lg shadow-lg gradient-s'>
            <div class="flex justify-center relative">
                <div class='rounded bg-gray-500' style="height: 272px;"></div>
                <img src="{{URL('/images/img.jpg')}}" class="w-full rounded" style='object-fit: contain;' alt="bg">
                <div class="text-xs px-1 rounded absolute text-white font-bold" style="top:5px;right:5px;background:rgba(0,0,0,.3);">
                    <span class='uppercase'>h2p_gucio</span>
                </div>

                <div class='absolute flex p-2' style="bottom:0;left:0;">
                    <a href='' class='bg-orange-600 px-1 rounded-full text-white text-xs mr-1'>Śmieszne</a>
                    <a href='' class='bg-red-600 px-1 rounded-full text-white text-xs mr-1'>NSFW</a>
                    <a href='' class='bg-green-600 px-1 rounded-full text-white text-xs mr-1'>Fail</a>
                </div>
            </div>
            <div class='flex justify-between text-gray-500 text-xs py-1'>
                <a href="" class="hover:text-black">freku0</a>
                <span>4 godz. temu</span>
            </div>
            <div class="f-sec font-bold text-center overflow-hidden">
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum mollis interdum ornare. Phasellus non.</p>
                
            </div>
            <div class='flex justify-between text-gray-500 mt-2'>
                <div class='flex'>
                    <a href="" class="flex items-center text-xs">
                        <i class="material-icons md-14 mr-1 hover:text-blue-500">thumb_up</i>
                        <span class="text-green-500 font-bold">351</span>
                    </a>
                </div>
                <a href="" class="flex items-center text-xs hover:text-black">
                    <i class="material-icons md-14 mr-1">comment</i>
                    <span>48</span>
                </a>

            </div>
        </div> --}}
    </div>
    
    <!-- SIDE BAR -->
    {{-- <div class="lg:w-1/4 w-2/5 sm:block hidden"> --}}
    <div class="lg:w-1/4 w-2/5 sm:block hidden">
        <div class='bg-white rounded shadow-lg p-2 m-2 my-1'>
            <a href="" class="flex items-center text-xs justify-center text-lg text-red-500 bg-yellow-300 mb-2">
                <i class="material-icons mr-1">trending_up</i>
                <span>TOP TYGODNIA</span>
            </a>
            @foreach ($posts as $p)
                
            <div class='flex bg-gray-100 rounded mb-2'>
                <a href='{{ URL("/post/$p->id-" . Str::slug($p->title)) }}' class='w-1/4 p-2 flex items-center border-r' target="_blank">
                    {{-- <img src="{{URL('/images/bg.png')}}" class="w-full rounded" alt="miniaturka"> --}}
                    {{-- <img src="{{URL('/images/img.jpg')}}" class="w-auto rounded" alt="bg"> --}}
                    <img src="{{ $p->thumbnail_url }}" class="w-auto rounded" alt="bg">
                </a>
                <div class='w-3/4'>
                    <div class='flex justify-between border-b'>
                        <a href="" class="flex items-center text-xs text-green-500 pl-2 font-bold">
                            <i class="material-icons md-14 mr-1">thumb_up</i>
                            <span>{{ $p->likes }}</span>
                        </a>
                        <p class='uppercase text-sm text-right px-2 overflow-hidden'>{{ $p->streamer_name }}</p>
                    </div>
                    <a href="{{ URL("/post/$p->id-" . Str::slug($p->title)) }}" target="_blank">
                        <p class='text-xs p-2'>
                            {{ $p->title }}
                        </p>
                    </a>
                </div>
            </div>
            @endforeach
            {{-- <button id='get-coms'>get more posts</button> --}}
        </div>

        <div class='bg-white rounded shadow-lg p-2 m-2 my-2 text-sm'>
            <p class='flex items-center'>
                <i class="material-icons mr-2">mail_outline</i>
                <span>Kontakt: strona@gmail.com</span>
            </p>
        </div>
    </div>
</div>
@endsection
