@extends('layouts.app')

@section('content')

<div class='m-full sm:max-w-sm mx-2 sm:mx-0 bg-white mt-4 sm:mx-auto rounded shadow-lg'>
    <a href='{{ route('twitch-login') }}' class='bg-yellow-600 flex justify-center items-center text-white p-4 block rounded-t'>
        <span>Podaj email</span>
    </a>
    <div class='p-4 pb-2'>
        <form action="{{ route('password.email') }}" method="post">
            @csrf

            @if (session('status'))
                <span class="w-full bg-green-100 border-green-700 text-green-600 p-2 border w-full block mb-4 text-sm" role="alert">
                    {{ session('status') }}
                </span>
            @endif
            
            @error('email')
                <span class="w-full bg-red-100 border-red-700 text-red-600 p-2 border w-full block mb-4 text-sm" role="alert">
                    {{ $message }}
                </span>
            @enderror

            <div class='flex items-center mb-4 border rounded bg-gray-800'>
                <i class="material-icons px-2 h-full text-white">person</i>
                <input id="email" type="text" class="border w-full p-2 appearance-none focus:bg-gray-100 focus:outline-none focus:border-blue-500" placeholder="Email" name="email" value="{{ old('email') }}" required autocomplete="false" autofocus>
            </div>
            
            <button type="submit" class='w-full border p-2 mt-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900'>
                Zresetuj hasło
            </button>
        </form>
    </div>
</div>
@endsection
