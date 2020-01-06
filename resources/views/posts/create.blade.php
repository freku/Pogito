@extends('layouts.app')

@section('title', 'Dodawanie postu | Strona')

@section('content')
    <div class="container mx-auto mt-4 flex justify-center f-sec ">
        <!-- clips wrapper -->
        <form action='/post' method='POST' class="lg:w-2/3 sm:w-3/4 w-full shadow-lg rounded-lg bg-white">
            @csrf
            <div class='bg-blue-500 text-white p-4 text-center text-lg rounded-t-lg'>
                Dodaj epicki klip!
            </div>
            @if ($errors->any())
                {{-- @foreach ($errors->all() as $error) --}}
                @foreach (array_unique($errors->all()) as $error)
                    <div class="bg-red-100 border-red-700 text-red-600 p-2 border m-3 text-sm" role="alert">
                        {{ $error }}
                    </div>
                @endforeach
            @endif
            <div class='p-3 pb-1'>
                <label for='tytul' class='pb-1 block text-gray-600'>{ Tytuł }</label>
                <input type="text" id='tytul' name='tytul' class='border w-full p-2 appearance-none focus:bg-gray-100 focus:outline-none focus:border-blue-500' value="{{ old('tytul') }}" placeholder="Podaj tytuł klipa">
            </div>
            <div class='p-3 pb-1'>
                {{-- tytul, link, tagi --}}
                <label for='link' class='pb-1 block text-gray-600'>{ Link }</label>
                <input type="text" id='link' name='link' class='border w-full p-2 appearance-none focus:bg-gray-100 focus:outline-none focus:border-blue-500' value="{{ old('link') }}" placeholder="Podaj link to klipa">
                <p class='text-sm text-gray-800 pt-1 overflow-hidden'>Przykład: 
                    <span class='text-orange-600'>https://clips.twitch.tv/AwkwardHelplessSalamanderSwiftRage</span>
                </p>
            </div>

            <div class='p-3'>
                <label for='tags' class='pb-1 block text-gray-600'>
                    { Tagi }
                    <span class='text-sm text-orange-600'>Przyklad: #Smieszne #Fail</span>
                </label>
                <input type="text" id='tags' name='tags' class='border w-full p-2 appearance-none focus:bg-gray-100 focus:outline-none focus:border-blue-500' value="{{ old('tags') != null ? implode(' ', old('tags')) : old('tags') }}" placeholder="Podaj tagi..">
                <ul class='flex flex-wrap p-2 pl-0'>
                    <li class='tag cursor-pointer mt-1 bg-red-400 p-2 mr-2 text-white hover:bg-red-500'>#Smieszne</li>
                    <li class='tag cursor-pointer mt-1 bg-red-400 p-2 mr-2 text-white hover:bg-red-500'>#Fail</li>
                    <li class='tag cursor-pointer mt-1 bg-red-400 p-2 mr-2 text-white hover:bg-red-500'>#NSFW</li>
                    <li class='tag cursor-pointer mt-1 bg-red-400 p-2 mr-2 text-white hover:bg-red-500'>#LOL</li>
                </ul>
            </div>

            <div class='text-center pt-1 pb-2'>
                <button type="submit" class='border p-2 px-4 shadow-md bg-gray-800 text-white rounded-lg hover:bg-gray-900'>
                    Dodaj
                </button>
            </div>
        </form>
    </div>
@endsection