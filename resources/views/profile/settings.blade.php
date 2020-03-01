@extends('layouts.app')

@section('title', 'Ustawienia konta | Strona')

@section('content')
    <div class='w-full md:w-5/6 xl:w-1/2 bg-white mx-auto mt-4 rounded shadow-lg f-sec'>
        @error('success')
            <span class="w-full bg-green-100 border-green-700 text-green-600 p-2 border w-full block mb-4 text-sm" role="alert">
                {{ $message }}
            </span>
        @enderror
        <div class='w-full border-b p-4'>
            <p class='text-2xl'>
                <span>Ustawienia > </span>
                <span class='border-b-2 border-blue-500 text-blue-500'>[{{ Auth::user()->name }}]</span>
                <span class=''>{{ Auth::user()->name_tw ? ' > ' : '' }}</span>
                <span class='border-b-2 border-purple-500 text-purple-600'>{{ Auth::user()->name_tw ?? '' }}</span>
            </p>
        </div>
        {{-- @if ($errors->any())
            @foreach (array_unique($errors->all()) as $error)
                <div class="bg-red-100 border-red-700 text-red-600 p-2 border m-3 text-sm" role="alert">
                    {{ $error }}
                </div>
            @endforeach
        @endif --}}
        @if ($is_pw_set == null)
        {{-- @if (true) --}}
            <div class='w-full border-b p-4'>
                <p class='text-red-500'>Ustaw hasło</p>
                <span class='text-xs mb-1 block text-red-700'>Ustawienie hasła pozwoli na normalne logowanie się za pomocą loginu i hasła.</span>
                <div class='w-full md:w-1/2'>
                    @error('password')
                        <span class="w-full bg-red-100 border-red-700 text-red-600 p-2 border w-full block mb-4 text-sm" role="alert">
                            {{ $message }}
                        </span>
                    @enderror

                    <form action="{{ route('profile.settings.post', $user) }}" method="post">
                        @csrf
                        <input type="hidden" name="type" value="1">
                        <div class='flex items-center mb-4 border rounded bg-gray-800'>
                            <i class="material-icons px-2 h-full text-white">lock_open</i>
                            <input id="password" type="password" class="border border-red-400 w-full p-2 appearance-none focus:bg-gray-100 focus:outline-none focus:border-blue-500" placeholder="Hasło" name="password" value="{{ old('password') }}" required autocomplete="false" autofocus>
                        </div>
                        
                        <div class='flex items-center mb-4 border rounded bg-gray-800'>
                            <i class="material-icons px-2 h-full text-white">lock_open</i>
                            <input id="password_confirmation" type="password" class="border border-red-400 w-full p-2 appearance-none focus:bg-gray-100 focus:outline-none focus:border-blue-500" placeholder="Powtórz hasło" name="password_confirmation" value="{{ old('password_confirmation') }}" required autocomplete="false" autofocus>
                        </div>

                        <button type="submit" class='w-1/3 md:w-1/2 mx-auto block border p-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900'>
                            Ustaw hasło
                        </button>
                    </form>
                </div>
            </div>
        @endif
        
        <div class='w-full border-b p-4 shadow'>
            <span class=''>Avatar</span>
            <p class='text-sm text-gray-600 mb-1'>Zmień avatar profilowy.</p>
            @error('avatar')
                <span class="w-full bg-red-100 border-red-700 text-red-600 p-2 border w-full block mb-4 text-sm" role="alert">
                    {{ $message }}
                </span>
            @enderror
            
            <img src="{{ Auth::user()->avatar }}" alt="Avatar" class='border w-32 h-32 rounded-full shadow-md inline'>
            <form method="POST" class='inline sm:m-2 m-0' enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="type" value="3">
                <input type="file" name="avatar" id="avatar" accept=".jpeg,.png,.jpg" class='my-2'>
                <div class='flex items-center'>
                    <input type="checkbox" name="avk_from_twitch" id="avk_from_twitch" class='block'> 
                    <span class='ml-1'>Ustaw avatar z twitcha</span>
                </div>
                <button type="submit" class="inline sm:block sm:my-2 w-60 block p-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900">
                    <span>Ustaw avatar</span>
                </button>
            </form>
        </div>

        {{-- <div class='w-full border-b p-4 shadow'>
            <span class=''>Nick</span>
            <p class='text-sm text-gray-600 mb-1'>;w procesie prac;</p>
        </div> --}}

        <div class='w-full border-b p-4 shadow'>
            <span class=''>Hasło</span>
            <p class='text-sm text-gray-600 mb-1'>Zmień hasło za pomocą emaila.</p>

            <a href="/password/reset" class='text-blue-400 underline hover:text-blue-300'>
                Reset/Zmiana hasła    
            </a>
        </div>

        <div class='w-full border-b p-4 shadow'>
            <span class=''>Twitch</span>
            <p class='text-sm text-gray-600 mb-1'>Odłacz aktualne konto twitch od tego konta.</p>
            
            @error('wrong_nick')
                <div class="bg-red-100 border-red-700 text-red-600 p-2 border m-3 ml-0 text-sm" role="alert">
                    {{ $message }}
                </div>
            @enderror
            @error('no_tw_connected')
                <div class="bg-red-100 border-red-700 text-red-600 p-2 border m-3 ml-0 text-sm" role="alert">
                    {{ $message }}
                </div>
            @enderror

            @if ($is_pw_set == null)
            {{-- @if (true) --}}
                <p class='text-sm text-red-500 mb-1'>Uwaga: Odłaczenie spowoduje całkowite usunięcie konta, ponieważ nie ma ustawionego jeszcze hasła!</p>
            @endif
            @if (!$is_twitch_connected)
                <p class='text-sm text-blue-500 mb-1'>Aktualnie konto twitch nie jest połączone.</p>
            @endif

            <form method="POST">
                @csrf
                <input type="hidden" name="type" value="2">

                @if ($is_twitch_connected)
                {{-- @if (true) --}}
                    <input id="acc_name" type="text" class="inline border border-green-400 p-2 appearance-none focus:bg-gray-100 focus:outline-none focus:border-blue-500" placeholder="Podaj swój nick" name="acc_name" value="{{ old('acc_name') }}" required autocomplete="false">
                    <button type="submit" class="inline w-60 block p-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900">
                        <span>Odłacz</span>
                    </button>
                @endif
            </form>
        </div>
        {{-- 
            Ustawienia: 
                        - zmiana emaila,
                            - loguje sie przez twitcha,
                            - mail z twitcha jest juz uzywany przez inne normalne konto:
                                1) podanie emaila przy tworzeniu konta <-- WINNER
                                
                                2) Nieustawianie emaila, mozliwa ustawienia w settings
                        - zmiana hasla,
                        - zmiana avartara,
                        - odlaczenie konta twitch? Niemozliwe jesli uzytkownik loguje sie tylko przez twitcha (nie ma hasla na koncie) Trzeba dodac info o tym
                            - usuwa jednak konto w tym przypadku
                            - usuwa dane, zmienia nazwe na '.' przez co bedziemy wiedziesz ze user jest usuniety
                        - zmiana nicku? ( moze tylko wielosc liter ) ;todo infut;
                        - pokazywanie ostaniej aktywnosci, postow, kometarzy statystyk ( polityka prywatnosci ) ;todo infut;
                        
            --}}
    </div>
@endsection