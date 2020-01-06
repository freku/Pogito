@extends('layouts.app')

@section('title', 'Ustawienia konta | Strona')

@section('content')
    <div class='w-full md:w-5/6 xl:w-1/2 bg-white mx-auto mt-4 rounded shadow-lg f-sec'>
        <div class='w-full border-b p-4'>
            <span class='text-2xl'>Ustawienia</span>
        </div>
        
        {{-- @if ($is_pw_set == null) --}}
        @if (true)
            <div class='w-full border-b p-4'>
                <p class='text-red-500'>Ustaw hasło</p>
                <span class='text-xs mb-1 block text-red-700'>Ustawienie hasła pozwoli na normalne logowanie się za pomocą loginu i hasła.</span>
                <div class='w-full md:w-1/2'>
                    <form action="{{ route('profile.settings.post', $user) }}" method="post">
                        @csrf
                        
                        <div class='flex items-center mb-4 border rounded bg-gray-800'>
                            <i class="material-icons px-2 h-full text-white">lock_open</i>
                            <input id="password" type="text" class="border border-red-400 w-full p-2 appearance-none focus:bg-gray-100 focus:outline-none focus:border-blue-500" placeholder="Hasło" name="password" value="{{ old('password') }}" required autocomplete="false" autofocus>
                        </div>
                        
                        <div class='flex items-center mb-4 border rounded bg-gray-800'>
                            <i class="material-icons px-2 h-full text-white">lock_open</i>
                            <input id="password_confirmation" type="text" class="border border-red-400 w-full p-2 appearance-none focus:bg-gray-100 focus:outline-none focus:border-blue-500" placeholder="Powtórz hasło" name="password_confirmation" value="{{ old('password_confirmation') }}" required autocomplete="false" autofocus>
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
        </div>

        <div class='w-full border-b p-4 shadow'>
            <span class=''>Nick</span>
            <p class='text-sm text-gray-600 mb-1'>;w procesie prac;</p>
        </div>

        <div class='w-full border-b p-4 shadow'>
            <span class=''>Hasło</span>
            <p class='text-sm text-gray-600 mb-1'>Zmień hasło za pomocą emaila.</p>

            <a href="/password/reset" class='text-blue-400 underline hover:text-blue-300'>
                Reset hasła    
            </a>
        </div>

        <div class='w-full border-b p-4 shadow'>
            <span class=''>Twitch</span>
            <p class='text-sm text-gray-600 mb-1'>Odłacz aktualne konto twitch od tego konta.</p>
            
            {{-- @if ($is_pw_set == null) --}}
            @if (true)
                <p class='text-sm text-red-500 mb-1'>Uwaga: Odłaczenie spowoduje całkowite usunięcie konta, ponieważ nie ma ustawionego jeszcze hasła!</p>
            @endif

            <form method="POST">
                <input type="hidden" name="type" value="">
                
                <button type="submit" class='w-60 block border p-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900'>
                    Odłacz
                </button>
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
                        - zmiana nicku? ( moze tylko wielosc liter ) ;todo infut;
                        - pokazywanie ostaniej aktywnosci, postow, kometarzy statystyk ( polityka prywatnosci ) ;todo infut;
            --}}
    </div>
@endsection