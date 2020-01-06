@extends('layouts.app')

@section('title', 'Resetowanie hasła | Strona')

@section('content')

<div class='m-full sm:max-w-sm mx-2 sm:mx-0 bg-white mt-4 sm:mx-auto rounded shadow-lg'>
    <a href='{{ route('twitch-login') }}' class='bg-purple-600 flex justify-center items-center text-white p-4 block rounded-t'>
        <span>Zaloguj się przez twitch</span>
        <img src="{{URL('/images/tw.png')}}" alt="logo twitcha" class='w-8 ml-4'>
    </a>
    <div class='p-4 pb-0'>
        <form action="{{ route('password.update') }}" method="post">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            @error('password')
                <span class="w-full bg-red-100 border-red-700 text-red-600 p-2 border w-full block mb-4 text-sm" role="alert">
                    {{ $message }}
                </span>
            @enderror
            @error('email')
                <span class="w-full bg-red-100 border-red-700 text-red-600 p-2 border w-full block mb-4 text-sm" role="alert">
                    {{ $message }}
                </span>
            @enderror

            <div class='flex items-center mb-4 border rounded bg-gray-800'>
                <i class="material-icons px-2 h-full text-white">person</i>
                <input id="email" type="text" class="border w-full p-2 appearance-none focus:bg-gray-100 focus:outline-none focus:border-blue-500" placeholder="Email" name="email" value="{{ $email ?? old('email') }}" required autocomplete="false" autofocus>
            </div>
            
            <div class='flex items-center mb-4 border rounded bg-gray-800'>
                <i class="material-icons px-2 text-white">lock</i>
                <input id="password" type="password" class="border w-full border p-2 appearance-none focus:bg-gray-100 focus:outline-none focus:border-blue-500" placeholder="Hasło" name="password" required autocomplete="current-password">
            </div>

            <div class='flex items-center mb-4 border rounded bg-gray-800'>
                <i class="material-icons px-2 text-white">lock</i>
                <input id="password-confirm" type="password" class="border w-full border p-2 appearance-none focus:bg-gray-100 focus:outline-none focus:border-blue-500" placeholder="Powtorz hasło" name="password_confirmation" required autocomplete="current-password">
            </div>
            
            <button type="submit" class='w-full border p-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 mb-2'>
                Zresetuj hasło
            </button>
        </form>
    </div>
</div>
@endsection
