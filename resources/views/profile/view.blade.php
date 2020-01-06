@extends('layouts.app')

@section('title', isset($user) ? $user->name . ' | Strona' : 'Nieznaleziono | Strona')

@section('content')
@if (isset($user))
    <div class='w-full md:w-5/6 xl:w-1/2 bg-white mx-auto mt-4 rounded shadow-lg f-sec'>
        
        @if ($errors->any())
            @foreach ($errors->all() as $message)
            <span class="w-full bg-red-100 border-red-700 text-red-600 p-2 border w-full block mt-2 mb-4 text-sm" role="alert">
                {{ $message }}
            </span>
            @endforeach
        @endif
        {{-- @error('twitch')
            <span class="w-full bg-red-100 border-red-700 text-red-600 p-2 border w-full block mb-4 text-sm" role="alert">
                {{ $message }}
            </span>
        @enderror --}}
        <div class='w-full flex justify-center md:flex-row flex-col'>
            <div class='flex flex-col items-center m-2'>
                <img class='w-32 rounded-full mb-2 mx-2 shadow-lg' src="{{ $user->avatar }}" alt="Profile avatar">
                <p class=' text-center px-4 py-1'>{{ $user->name }}</p>
                {{-- bg-gray-800 text-white rounded-full shadow-md --}}
                @if ($user->name_tw != null)
                    <a href="" class='bg-purple-700 text-white rounded-full shadow-lg text-center px-4 py-1 hover:bg-purple-800'>
                        <span class=''>{{ $user->name_tw }}</span>
                        <img class='w-4 inline' src="{{URL('/images/tw.png')}}" alt="Twitch account link">
                    </a>
                @else
                    @if ($is_his_profile)
                        <a href='/login/twitch' class='bg-purple-700 text-white rounded-full shadow-lg text-center px-4 py-1 select-none'>
                            <span class=''>Połącz konto</span>
                            <img class='w-4 inline' src="{{URL('/images/tw.png')}}" alt="Twitch account link">
                        </a>
                    @else
                        <a onclick="return false;" class='bg-purple-700 text-white rounded-full shadow-lg text-center px-4 py-1 cursor-not-allowed select-none'>
                            <span class=''>Nie połączono</span>
                            <img class='w-4 inline' src="{{URL('/images/tw.png')}}" alt="Twitch account link">
                        </a>
                    @endif
                @endif
            </div>
            <div class='flex flex-col justify-between m-2'>
                {{-- <p class=''>{{ $created_at }}</p>
                <p class=''>{{ $last_activity }}</p> --}}

                <p class='mb-2 md:m-0 bg-gray-700 text-white py-2 pr-4 rounded-full shadow-md'><span class='bg-gray-800 text-white py-2 px-4 rounded-full'>{{ $post_num }}</span> komentarzy.</p>
                <p class='mb-2 md:m-0 bg-gray-700 text-white py-2 pr-4 rounded-full shadow-md'><span class='bg-gray-800 text-white py-2 px-4 rounded-full'>{{ $com_num }}</span> postów.</p>
                <p class='mb-2 md:m-0 bg-gray-700 text-white py-2 pr-4 rounded-full shadow-md'><span class='bg-gray-800 text-white py-2 px-4 rounded-full'>{{ $post_likes }}</span> lajków za posty.</p>
                <p class='mb-2 md:m-0 bg-gray-700 text-white py-2 pr-4 rounded-full shadow-md'><span class='bg-gray-800 text-white py-2 px-4 rounded-full'>{{ $com_likes }}</span> lajków za komentarze.</p>
            </div>
        </div>
        {{-- data stworzenia, last_activity --}}
        <div class='flex justify-around mt-2'>
            <div class='text-center border rounded px-2 py-1 shadow-lg'>
                <p class=''>Dołączył:</p>
                <span class='font-bold'>{{ $created_at }}</span>
            </div>
            <div class='text-center border rounded px-2 py-1 shadow-lg'>
                <p>Ostatnio widziany: </p>
                <span class='font-bold'>{{ $last_activity }}</span>
            </div>
        </div>

        <div class='flex justify-around mt-4'>
            <a class='bg-red-600 text-white w-full text-center py-2 text-xl hover:bg-red-400 cursor-not-allowed'>
                Posty
            </a>
            <a class='bg-red-600 text-white w-full text-center py-2 text-xl hover:bg-red-400 cursor-not-allowed'>
                Komentarze
            </a>
        </div>

        <div class='w-full p-4 text-gray-500 select-none'>
            <p class='text-center text-xl flex items-center justify-center'>
                <span>Trwają roboty...</span>
                <i class="material-icons ml-2">build</i>
            </p>
        </div>
    </div>
@else
    <div class='w-full h-full absolute flex items-center justify-center inset-0 f-sec p-2'>
        <span class='text-4xl mr-2'>Takie konto nie istnieje...</span>
        <i class="material-icons text-5xl">accessible_forward</i>
    </div>
@endif
@endsection