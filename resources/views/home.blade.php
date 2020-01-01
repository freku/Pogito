@extends('layouts.app')

@section('title', 'Home page')

@section('content')
<div class="container mx-auto mt-4 flex">
        <!-- clips wrapper -->
    <div class="lg:w-2/3 sm:w-3/4 w-full flex justify-end flex-wrap"> 
        @foreach ([1,2,3,4,5,5,5,5] as $item)
        <!-- clip box -->
        {{-- <div class='lg:w-1/2 sm:w-3/4 w-full bg-white mx-2 mt-1 mb-2 p-2 rounded-lg shadow-lg gradient-s'> --}}
        {{-- <div class='lg:w-1/2 sm:w-3/4 w-full bg-white mx-2 mt-1 mb-2 p-2 rounded-lg shadow-lg gradient-s'> --}}
        <div class='lg:w-4/6 w-full bg-white mx-2 mt-1 mb-2 p-2 rounded-lg shadow-lg gradient-s'>
            <div class="flex justify-center relative">
                {{-- <img src="{{URL('/images/bg.png')}}" class="w-auto rounded" alt="bg"> --}}
                {{-- <a href=""> --}}
                    <img src="{{URL('/images/img.jpg')}}" class="w-full rounded" style='object-fit: contain;' alt="bg">
                {{-- </a> --}}
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
                {{-- <p>{{ Str::random(32) }}</p> --}}
                
            </div>
            <div class='flex justify-between text-gray-500 mt-2'>
                <div class='flex'>
                    {{-- <a href="" class="flex items-center text-xs text-green-500"> --}}
                    <a href="" class="flex items-center text-xs">
                        <i class="material-icons md-14 mr-1 hover:text-blue-500">thumb_up</i>
                        <span class="text-green-500 font-bold">351</span>
                        {{-- <span class="text-red-500 font-bold">351</span> --}}
                    </a>
                    {{-- <a href="" class="flex items-center text-xs ml-1">
                        <i class="material-icons md-14 mr-1 hover:text-orange-500">thumb_down</i>
                    </a> --}}
                </div>
                <a href="" class="flex items-center text-xs hover:text-black">
                    <i class="material-icons md-14 mr-1">comment</i>
                    <span>48</span>
                </a>

            </div>
        </div>
        @endforeach

    </div>
    
    <!-- SIDE BAR -->
    {{-- <div class="lg:w-1/4 w-2/5 sm:block hidden"> --}}
    <div class="lg:w-1/4 w-2/5 sm:block hidden">
        <div class='bg-white rounded shadow-lg p-2 m-2 my-1'>
            <a href="" class="flex items-center text-xs justify-center text-lg text-red-500 bg-yellow-300 mb-2">
                <i class="material-icons mr-1">trending_up</i>
                <span>HOT</span>
            </a>
            @foreach ([1,1,1,1,1] as $item)
                
            <div class='flex bg-gray-100 rounded mb-2'>
                <a href='' class='w-1/4 p-2 flex items-center border-r'>
                    {{-- <img src="{{URL('/images/bg.png')}}" class="w-full rounded" alt="miniaturka"> --}}
                    <img src="{{URL('/images/img.jpg')}}" class="w-auto rounded" alt="bg">
                </a>
                <div class='w-3/4'>
                    <div class='flex justify-between border-b'>
                        <a href="" class="flex items-center text-xs text-green-500 pl-2 font-bold">
                            <i class="material-icons md-14 mr-1">thumb_up</i>
                            <span>351</span>
                        </a>
                        <p class='uppercase text-sm text-right px-2'>h2p_gucio</p>
                    </div>
                    <a href="">
                        <p class='text-xs p-2'>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
                        </p>
                    </a>
                </div>
            </div>
            @endforeach

        </div>
    </div>
</div>
@endsection
