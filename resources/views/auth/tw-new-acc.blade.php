@extends('layouts.app')

@section('title', 'Ustaw nick dla konta | Strona')

@section('content')
    <div class='m-full sm:max-w-sm mx-2 sm:mx-0 bg-white mt-4 sm:mx-auto rounded shadow-lg f-sec'>
        <div class='w-full p-4 text-center bg-purple-600 text-white '>
            DANE NOWEGO KONTA
        </div>
        <div class='p-4 pb-0'>
            <form action="{{ route('tw-new-acc-check') }}" method="post">
                @csrf
                @error('name')
                    <span class="w-full bg-red-100 border-red-700 text-red-600 p-2 border w-full block mb-4 text-sm" role="alert">
                        {{ $message }}
                    </span>
                @enderror
                @error('email')
                    <span class="w-full bg-red-100 border-red-700 text-red-600 p-2 border w-full block mb-4 text-sm" role="alert">
                        {{ $message }}
                    </span>
                @enderror

                <p class='text-xs text-orange-500 pb-2'>
                    Logowanie się na to konto będzie tylko możlie za pomocą tego konta twitch chyba że ustawisz hasło w ustawieniach.
                </p>
                <div class='flex items-center mb-4 border rounded bg-gray-800'>
                    <i class="material-icons px-2 h-full text-white">fingerprint</i>
                    <input id="name" type="text" class="border w-full p-2 appearance-none focus:bg-gray-100 focus:outline-none focus:border-blue-500" placeholder="Twój nick" name="name" value="{{ old('name') }}" required autocomplete="false" autofocus>
                </div>
                
                @if (session('tw_data')['email'] == null)
                    <p class='text-sm text-orange-600 pb-2'>Jeśli widzisz to pole oznacza to, że email z konta twitcha jest już zajęty. Wprowadź inny email.</p>
                    <div class='flex items-center mb-4 border rounded bg-gray-800'>
                        <i class="material-icons px-2 h-full text-white">email</i>
                        <input id="email" type="text" class="border w-full p-2 appearance-none focus:bg-gray-100 focus:outline-none focus:border-blue-500" placeholder="Twój email" name="email" value="{{ old('email') }}" required autocomplete="false" autofocus>
                    </div>
                @endif
    
                <button type="submit" class='w-full border p-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 mb-2'>
                    Stwórz konto!
                </button>
            </form>
        </div>
    </div>
@endsection