@extends('layouts.app')

@section('title', 'Logowanie | Strona')

@section('content')

<div class='m-full sm:max-w-sm mx-2 sm:mx-0 bg-white mt-4 sm:mx-auto rounded shadow-lg f-sec'>
    <a href='{{ route('twitch-login') }}' class='bg-purple-600 flex justify-center items-center text-white p-4 block rounded-t'>
        <span>Zaloguj się przez twitch</span>
        <img src="{{URL('/images/tw.png')}}" alt="logo twitcha" class='w-8 ml-4'>
    </a>
    <div class='p-4 pb-0'>
        <form action="{{ route('login') }}" method="post">
            @csrf
            @error('password')
                <span class="w-full bg-red-100 border-red-700 text-red-600 p-2 border w-full block mb-4 text-sm" role="alert">
                    {{ $message }}
                </span>
            @enderror
            @error('twitch')
                <span class="w-full bg-red-100 border-red-700 text-red-600 p-2 border w-full block mb-4 text-sm" role="alert">
                    {{ $message }}
                </span>
            @enderror
            @error('name')
                <span class="w-full bg-red-100 border-red-700 text-red-600 p-2 border w-full block mb-4 text-sm" role="alert">
                    {{ $message }}
                </span>
            @enderror

            <div class='flex items-center mb-4 border rounded bg-gray-800'>
                <i class="material-icons px-2 h-full text-white">person</i>
                <input id="name" type="text" class="border w-full p-2 appearance-none focus:bg-gray-100 focus:outline-none focus:border-blue-500" placeholder="Login" name="name" value="{{ old('name') }}" required autocomplete="false" autofocus>
            </div>
            
            <div class='flex items-center mb-4 border rounded bg-gray-800'>
                <i class="material-icons px-2 text-white">lock</i>
                <input id="password" type="password" class="border w-full border p-2 appearance-none focus:bg-gray-100 focus:outline-none focus:border-blue-500" placeholder="Hasło" name="password" required autocomplete="current-password">
            </div>
            
            <div class='flex justify-between text-sm mb-2 mt-4'>
                <div>
                    <input class="leading-tight" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <span>Zapamietaj mnie</span>
                </div>
                <div>
                    @if (Route::has('password.request'))
                        <a class="text-gray-500 underline hover:text-blue-800" href="{{ route('password.request') }}">
                            {{ __('Zapomniałeś hasło?') }}
                        </a>
                    @endif
                </div>
            </div>

            <button type="submit" class='w-full border p-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900'>
                Zaloguj się
            </button>
        </form>
        <div class='flex justify-center items-center pt-4 pb-1 text-sm text-red-500'>
            <a href="{{ route('register') }}" class='cursor-pointer flex items-center'>
                <i class="material-icons pr-1 md-18 text-orange-500">whatshot</i>
                <span class='underline'>Załóż nowe konto!</span>
            </a>
        </div>
    </div>
</div>
@endsection
