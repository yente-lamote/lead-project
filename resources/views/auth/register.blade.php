@extends('layouts.app')

@section('content')
<div class="xl:w-2/6 md:w-1/2 sm:w-3/4 w-11/12 m-4 bg-card shadow mx-auto p-8 rounded-lg">
    <div class="lg:w-4/6 mx-auto">
        <img class="w-full" src="{{ URL::to('/assets/images/logo.png') }}" alt="lead-project logo">
        <h1 class="text-center text-xl my-4">Lead-project</h1>
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="login-input-container">
                <label for="name">
                    {{ __('Name') }}
                </label>
                <input class="@error('name') error @enderror" value="{{ old('name') }}" name="name" id="name" type="text" placeholder="Name" autofocus autocomplete="name" required>
                @error('name')
                <span class="error" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="login-input-container">
                <label for="email">
                    {{ __('E-Mail Address') }}
                </label>
                <input class="@error('email') error @enderror" value="{{ old('email') }}" name="email" id="email" type="email" placeholder="Email" autocomplete="email" required>
                @error('email')
                <span class="error" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="login-input-container">
                <label for="password">
                    {{ __('Password') }}
                </label>
                <input class="@error('password') error @enderror" name="password" id="password" type="password" placeholder="Password" autocomplete="new-password" required>
                @error('password')
                <span class="error" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="login-input-container">
                <label for="password-confirm">
                    {{ __('Confirm Password') }}
                </label>
                <input class="@error('password') error @enderror" name="password_confirmation" id="password-confirm" type="password" placeholder="Password" autocomplete="new-password" required>
                @error('password')
                <span class="error" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="flex flex-col">
                <button type="submit" class="py-2 px-10 rounded-lg bg-blue-600 text-white mt-4 mx-auto">
                    {{ __('Register') }}
                </button>
                @if (Route::has('register'))
                <span class="text-gray-500 mt-3 mx-auto">Already have an account? <a class="hover:text-blue-400 text-blue-600" href="{{ route('login') }}">{{ __('Login') }}</a></span>

                @endif
            </div>
        </form>
    </div>
</div>

@endsection