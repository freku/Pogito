<!doctype html>
{{-- <html lang="{{ str_replace('_', '-', app()->getLocale()) }}"> --}}
<html lang='pl'>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- <title>{{ config('app.name', 'Laravel') }}</title> --}}
    <title>@yield('title')</title>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="{{ asset('js/tags.js') }}"></script>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Supermercado+One&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    {{-- <link rel="dns-prefetch" href="//fonts.gstatic.com"> --}}
    {{-- <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet"> --}}

    <!-- Styles -->
    <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class='bg-gray-100'>
    <nav class="border-b flex justify-center p-2 bg-white gradient-s relative">
        <div class='w-1/3'>
        </div>
        
        <div class='w-1/3 flex justify-center'>
            <a href="{{ url('/') }}" class="text-2xl f-main font-bold text-gray-800">pogito</a>
        </div>

        <div class='w-1/3 flex items-center justify-end text-gray-700 f-sec'>
            
            @guest
                <a href="/login" class="flex items-center justify-center mr-2 text-xs bg-indigo-700 text-white px-1 md:px-4 py-1 hover:shadow hover:text-black hover:bg-white">
                    <i class="material-icons md:pr-2 ">accessible_forward</i>
                    <span class="hidden sm:inline">ZALOGUJ SIĘ</span>
                </a>
            @else
                <a href="/post/create" class="flex items-center justify-center mr-2 text-xs text-indigo-700 px-1 md:px-4 py-1 hover:text-black">
                    <i class="material-icons md:pr-2 ">add</i>
                    <span class="hidden sm:inline">DODAJ POST</span>
                </a>
                <div class='flex flex-col items-center cursor-pointer dd-menu-img'>
                    @if (Auth::user()->isTwitch)
                        <img src="{{ Auth::user()->avatar }}" class='w-8 h-8 rounded-full' alt="">
                    @else
                        <img src="{{ url(Auth::user()->avatar) }}" class='w-8 h-8 rounded-full' alt="">
                    @endif
                    <i class="material-icons absolute" style='top:33px;'>arrow_drop_down</i>

                    <div class="flex flex-col bg-white absolute text-sm shadow rounded-lg text-center dd-menu hidden right-0" style='top:110%;'>
                        <a href="{{ URL('/x/' . Auth::user()->name) }}" class='hover:bg-gray-100 px-8 py-2 flex items-center'>
                            <i class="material-icons">person</i>
                            <div>
                                <span>{{ Auth::user()->name }}</span>
                                @if (Auth::user()->name_tw != null)
                                    <span class='block text-xs text-purple-700'>{{ Auth::user()->name_tw }}</span>
                                @endif
                            </div>
                        </a>
                        <a href="{{ URL('/x/' . Auth::user()->name) . '/settings' }}" class='hover:bg-gray-100 px-8 py-2 flex items-center'>
                            <i class="material-icons">settings_applications</i>
                            <span>Ustawienia</span>
                        </a>
                        {{-- <a href="" class='hover:bg-gray-100 px-8 py-2'>Profil</a> --}}
                        <a href="" class='hover:bg-gray-100 px-8 py-2 flex items-center' onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="material-icons">directions_run</i>
                            <span>Wyloguj się</span>
                        </a>
                    </div>
                </div>
            @endguest
        </div>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </nav>

    @yield('content')

    {{-- <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div> --}}
</body>
</html>
