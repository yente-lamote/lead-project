@extends('layouts.app')

@section('content')
<div class="xl:w-2/6 md:w-1/2 sm:w-3/4 w-11/12 m-4 bg-card shadow mx-auto p-8 rounded-lg">
    <div class="lg:w-4/6 mx-auto">
        <img class="w-full" src="{{ URL::to('/assets/images/logo.png') }}" alt="lead-project logo">
        <h1 class="text-center text-xl my-4">Lead-project</h1>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="login-input-container">
                <label for="email">
                    {{ __('E-Mail Address') }}
                </label>
                <input class="@error('email') error @enderror" value="{{ old('name') }}" name="email" id="email" type="email" placeholder="Email" autofocus autocomplete="email" required>
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
                <input class="@error('password') error @enderror" name="password" id="password" type="password" placeholder="Password" autocomplete="current-password" required>
                @error('password')
                <span class="error" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="flex items-center justify-between ">
                <div>
                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label for="remember">
                        {{ __('Remember Me') }}
                    </label>
                </div>
                @if (Route::has('password.request'))
                <a class="text-gray-500 text-sm" href="{{ route('password.request') }}">
                    {{ __('Forgot Your Password?') }}
                </a>
                @endif
            </div>
            <div class="flex flex-col">
                <button type="submit" class="py-2 px-10 rounded-lg bg-blue-600 text-white mt-4 mx-auto">
                    {{ __('Login') }}
                </button>
                @if (Route::has('register'))
                <span class="text-gray-500 mt-3 mx-auto">Don't have an account? <a class="hover:text-blue-400 text-blue-600" href="{{ route('register') }}">{{ __('Register') }}</a></span>
                   
                @endif
            </div>
        </form>
    </div>
</div>
<p class="xl:w-2/6 md:w-1/2 sm:w-3/4 w-11/12 text-center text-red-500 mx-auto">
Login accounts in README
</p>
@endsection