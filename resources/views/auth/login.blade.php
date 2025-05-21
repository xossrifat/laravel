@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="max-w-md mx-auto">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden dark:bg-gray-800">
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 py-4 px-6">
                <h1 class="text-xl font-bold text-white">{{ __('Login') }}</h1>
            </div>

            <div class="p-6">
                <!-- Messages -->
                @if(session('message'))
                <div class="mb-4 px-4 py-3 bg-blue-100 border-l-4 border-blue-500 text-blue-700 rounded-lg">
                    {{ session('message') }}
                </div>
                @endif

                @if(session('status'))
                <div class="mb-4 px-4 py-3 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-lg">
                    {{ session('status') }}
                </div>
                @endif

                @if($errors->any())
                <div class="mb-4 px-4 py-3 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-lg">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                        <li class="text-sm">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Regular Web Login Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ __('Email Address') }}
                        </label>
                        <input id="email" type="email" 
                            class="w-full px-4 py-3 rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('email') border-red-500 @enderror" 
                            name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('Password') }}
                            </label>
                            @if (Route::has('password.request'))
                                <a class="text-sm text-indigo-600 hover:text-indigo-800 dark:text-indigo-400" href="{{ route('password.request') }}">
                                    {{ __('Forgot Password?') }}
                                </a>
                            @endif
                        </div>
                        <input id="password" type="password" 
                            class="w-full px-4 py-3 rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('password') border-red-500 @enderror" 
                            name="password" required autocomplete="current-password">
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center space-x-2 py-2">
                        <input 
                            type="hidden" 
                            name="remember" 
                            id="remember_hidden" 
                            value="{{ old('remember') ? '1' : '0' }}">
                        
                        <button 
                            type="button" 
                            id="remember_toggle" 
                            class="w-6 h-6 border-2 border-gray-300 rounded focus:outline-none flex justify-center items-center bg-white"
                            onclick="toggleRemember()">
                            <svg id="remember_check" class="{{ old('remember') ? '' : 'hidden' }}" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                        </button>
                        
                        <label class="text-sm text-gray-600 dark:text-gray-400 cursor-pointer" onclick="toggleRemember()">
                            {{ __('Remember Me') }}
                        </label>
                    </div>

                    <div>
                        <button type="submit" class="w-full bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-bold py-3 px-4 rounded-lg hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 transform hover:scale-105">
                            {{ __('Login') }}
                        </button>
                    </div>

                    @if (Route::has('register'))
                    <div class="mt-4 text-center">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ __('Don\'t have an account?') }} 
                            <a href="{{ route('register') }}" class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">
                                {{ __('Register') }}
                            </a>
                        </p>
                    </div>
                    @endif
                </form>

                <!-- Telegram Login Section
                <div id="telegram-login-section">
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <h5 class="text-center text-gray-600 dark:text-gray-400 mb-4">{{ __('Or login with') }}</h5>
                        <div class="flex justify-center">
                            @include('auth.telegram-login')
                        </div>
                    </div>
                </div>    -->
            </div>
        </div>
    </div>
</div>
@endsection

@section('head')
<!-- Include CSRF token meta tag for Telegram auth script -->
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Check if we're in Telegram environment and hide regular login if needed
        const isTelegramWebApp = window.Telegram && 
                               window.Telegram.WebApp && 
                               window.Telegram.WebApp.initData && 
                               window.Telegram.WebApp.initData.length > 0;
        
        // If in Telegram app, hide the regular login form and show only Telegram login
        if (isTelegramWebApp) {
            const regularLoginForm = document.querySelector('form[action="{{ route('login') }}"]');
            if (regularLoginForm) {
                regularLoginForm.style.display = 'none';
            }
            
            // Update header text
            const cardHeader = document.querySelector('.card-header');
            if (cardHeader) {
                cardHeader.textContent = 'Telegram Login';
            }
        }
    });
    
    function toggleRemember() {
        const hiddenField = document.getElementById('remember_hidden');
        const checkIcon = document.getElementById('remember_check');
        const isChecked = hiddenField.value === '1';
        
        if (isChecked) {
            hiddenField.value = '0';
            checkIcon.classList.add('hidden');
        } else {
            hiddenField.value = '1';
            checkIcon.classList.remove('hidden');
        }
    }
</script>
@endpush
