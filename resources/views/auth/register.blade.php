@extends('layouts.app')

@section('content')
<div class='m-full sm:max-w-sm mx-2 sm:mx-0 bg-white mt-4 sm:mx-auto rounded shadow-lg'>
    <div class='bg-blue-600 flex justify-center items-center text-white p-4 block rounded-t'>
        <span>REJESTRACJA</span>
    </div>
    <div class='p-4'>
        <form action="{{ route('register') }}" method="post">
            @csrf

            @error('name')
                <span class="text-red-500 py-1 text-sm" role="alert">{{ $message }}</span>
            @enderror
            <div class='flex items-center mb-4 border rounded bg-gray-800'>
                <i class="material-icons px-2 h-full text-white">person</i>
                <input id="name" type="text" class="@error('name') is-invalid @enderror border w-full p-2 appearance-none focus:bg-gray-100 focus:outline-none focus:border-blue-500" placeholder="Login" name="name" value="{{ old('name') }}" required autocomplete="false" autofocus>
            </div>
            
            @error('email')
                <span class="text-red-500 py-1 text-sm" role="alert">{{ $message }}</span>
            @enderror
            <div class='flex items-center mb-4 border rounded bg-gray-800'>
                <i class="material-icons px-2 h-full text-white">mail</i>
                <input id="email" type="email" class="@error('email') is-invalid @enderror border w-full p-2 appearance-none focus:bg-gray-100 focus:outline-none focus:border-blue-500" placeholder="Email" name="email" value="{{ old('email') }}" required autocomplete="false" autofocus>
            </div>
           
            @error('password')
                <span class="text-red-500 py-1 text-sm" role="alert">{{ $message }}</span>
            @enderror
            <div class='flex items-center mb-4 border rounded bg-gray-800'>
                <i class="material-icons px-2 text-white">lock</i>
                <input id="password" type="password" class="@error('password') is-invalid @enderror border w-full border p-2 appearance-none focus:bg-gray-100 focus:outline-none focus:border-blue-500" placeholder="Hasło" name="password" required autocomplete="false">
            </div>

            <div class='flex items-center mb-4 border rounded bg-gray-800'>
                <i class="material-icons px-2 text-white">lock</i>
                <input id="password-confirm" type="password" class="@error('password') is-invalid @enderror border w-full border p-2 appearance-none focus:bg-gray-100 focus:outline-none focus:border-blue-500" placeholder="Powtórz hasło" name="password_confirmation" required autocomplete="false">
            </div>                
            
            <button type="submit" class='w-full border p-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900'>
                Załóż konto
            </button>
        </form>
    </div>
</div>
{{-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> --}}
@endsection
