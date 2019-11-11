<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Laravel</title>
        <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Supermercado+One&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Poppins&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <style>
            .f-main {
                font-family: 'Supermercado One', cursive;
            }
            .f-sec {
                font-family: 'Poppins', sans-serif;
            }
            /* Rules for sizing the icon. */
            .material-icons.md-14 { font-size: 14px; }
            .material-icons.md-18 { font-size: 18px; }
            .material-icons.md-24 { font-size: 24px; }
            .material-icons.md-36 { font-size: 36px; }
            .material-icons.md-48 { font-size: 48px; }

            /* Rules for using icons as black on a light background. */
            .material-icons.md-dark { color: rgba(0, 0, 0, 0.54); }
            .material-icons.md-dark.md-inactive { color: rgba(0, 0, 0, 0.26); }

            /* Rules for using icons as white on a dark background. */
            .material-icons.md-light { color: rgba(255, 255, 255, 1); }
            .material-icons.md-light.md-inactive { color: rgba(255, 255, 255, 0.3); }
            .gradient-s {
                background: linear-gradient(45deg, rgba(255,255,255,1) 0%, rgba(246,246,246,1) 47%, rgba(237,237,237,1) 100%), white;
                /* background: linear-gradient(45deg, rgba(13,111,217,1) 0%, rgba(250,153,153,1) 100%); */
            }
        </style>
    </head> 
    <body class='bg-gray-100'>
        <!-- NAV BAR -->
        <nav class="border-b flex justify-center p-2 bg-white gradient-s">
            <div class='w-1/3'>
            </div>
            
            <div class='w-1/3 flex justify-center'>
                <a href="" class="text-lg md:text-2xl f-main font-bold text-gray-800">\ k0x /</a>
            </div>

            <div class='w-1/3 flex items-center justify-end text-gray-700 f-sec'>
                
                <a href="/login" class="flex items-center justify-center mr-2 text-xs bg-indigo-700 text-white px-1 md:px-4 py-1 hover:shadow hover:text-black hover:bg-white">
                    <i class="material-icons md:pr-2 ">accessible_forward</i>
                    <span class="hidden sm:inline">ZALOGUJ SIĘ</span>
                </a>
            </div>
        </nav>
        
        <div class="container mx-auto mt-4 flex">
            <!-- clips wrapper -->
            <div class="lg:w-2/3 sm:w-3/4 w-full flex justify-end flex-wrap"> 
                @foreach ([1,2,3,4,5,5,5,5] as $item)
                <!-- clip box -->
                <div class='lg:w-1/2 sm:w-3/4 w-full bg-white mx-2 mt-1 mb-2 p-2 rounded-lg shadow-lg gradient-s'>
                    <div class="flex justify-center relative">
                        {{-- <img src="{{URL('/images/bg.png')}}" class="w-auto rounded" alt="bg"> --}}
                        <a href="">
                            <img src="{{URL('/images/img.jpg')}}" class="w-auto rounded" alt="bg">
                        </a>
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
                    <div class="text-sm f-sec font-bold text-center overflow-hidden">
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

        {{-- <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    k0x app
                </div>

                <div class="links">
                    <a href="https://laravel.com/docs">Docs</a>
                    <a href="https://laracasts.com">Laracasts</a>
                    <a href="https://laravel-news.com">News</a>
                    <a href="https://blog.laravel.com">Blog</a>
                    <a href="https://nova.laravel.com">Nova</a>
                    <a href="https://forge.laravel.com">Forge</a>
                    <a href="https://vapor.laravel.com">Vapor</a>
                    <a href="https://github.com/laravel/laravel">GitHub</a>
                </div>
            </div>
        </div> --}}
    </body>
</html>
