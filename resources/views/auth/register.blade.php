@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="max-w-md mx-auto">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden dark:bg-gray-800">
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 py-4 px-6">
                <h1 class="text-xl font-bold text-white">{{ __('Register') }}</h1>
            </div>

            <div class="p-6">
                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <!-- Name -->
                    <div class="space-y-2">
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ __('Name') }}
                        </label>
                        <input id="name" type="text" 
                            class="w-full px-4 py-3 rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('name') border-red-500 @enderror" 
                            name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Address -->
                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ __('Email') }}
                        </label>
                        <input id="email" type="email" 
                            class="w-full px-4 py-3 rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('email') border-red-500 @enderror" 
                            name="email" value="{{ old('email') }}" required autocomplete="email">
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Mobile Number -->
                    <div class="space-y-2">
                        <label for="mobile_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ __('Mobile Number') }}
                        </label>
                        <input id="mobile_number" type="text" 
                            class="w-full px-4 py-3 rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('mobile_number') border-red-500 @enderror" 
                            name="mobile_number" value="{{ old('mobile_number') }}" required autocomplete="tel"
                            placeholder="+880 1XXXXXXXXX">
                        @error('mobile_number')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 dark:text-gray-400">Required for withdrawals</p>
                    </div>

                    <!-- OTP Preference -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ __('Preferred OTP Method') }}
                        </label>
                        <div class="flex space-x-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="preferred_otp_channel" value="sms" class="text-indigo-600 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600"
                                    {{ old('preferred_otp_channel') == 'sms' ? 'checked' : 'checked' }}>
                                <span class="ml-2 text-gray-700 dark:text-gray-300">SMS</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="preferred_otp_channel" value="whatsapp" class="text-indigo-600 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600"
                                    {{ old('preferred_otp_channel') == 'whatsapp' ? 'checked' : '' }}>
                                <span class="ml-2 text-gray-700 dark:text-gray-300">WhatsApp</span>
                            </label>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">How you'd like to receive verification codes</p>
                    </div>

                    <!-- Password -->
                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ __('Password') }}
                        </label>
                        <input id="password" type="password" 
                            class="w-full px-4 py-3 rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('password') border-red-500 @enderror" 
                            name="password" required autocomplete="new-password">
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="space-y-2">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ __('Confirm Password') }}
                        </label>
                        <input id="password_confirmation" type="password" 
                            class="w-full px-4 py-3 rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white" 
                            name="password_confirmation" required autocomplete="new-password">
                    </div>

                    <!-- Referral Code -->
                    <div class="space-y-2">
                        <label for="referral_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ __('Referral Code') }} <span class="text-gray-500 text-xs">(Optional)</span>
                        </label>
                        <input id="referral_code" type="text" 
                            class="w-full px-4 py-3 rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('referral_code') border-red-500 @enderror" 
                            name="referral_code" value="{{ old('referral_code', $referralCode ?? '') }}" autocomplete="off">
                        @error('referral_code')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-sm text-gray-500 dark:text-gray-400">Enter a referral code if you were invited by someone</p>
                    </div>

                    <div class="flex items-center justify-between mt-6">
                        <a href="{{ route('login') }}" class="text-sm text-indigo-600 hover:text-indigo-800 dark:text-indigo-400">
                            {{ __('Already registered?') }}
                        </a>

                        <button type="submit" class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-bold py-2 px-6 rounded-lg hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 transform hover:scale-105">
                            {{ __('Register') }}
                        </button>
                    </div>
                </form>
                
                <!-- Telegram Registration Option 
                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <h2 class="text-center text-gray-600 dark:text-gray-400 mb-4">{{ __('Or register with') }}</h2>
                    <div class="flex justify-center">
                        @include('auth.telegram-login')
                    </div>
                </div> -->
            </div>
        </div>

        <!-- Telegram-only view -->
        <div id="telegram-only-view" class="hidden mt-4">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden dark:bg-gray-800 p-6">
                <h2 class="text-xl font-semibold text-center text-blue-600 mb-6 dark:text-blue-400">Register with Telegram</h2>
                <div class="bg-blue-50 p-4 rounded-lg mb-6 dark:bg-blue-900">
                    <p class="text-blue-700 text-center dark:text-blue-300">
                        <i class="fas fa-info-circle mr-1"></i>
                        You'll be automatically registered and logged in with your Telegram account.
                    </p>
                </div>
                
                <div class="flex justify-center">
                    @include('auth.telegram-login')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@push('scripts')
<script src="https://telegram.org/js/telegram-web-app.js"></script>
<script>
    // Check if we're in Telegram environment and adjust UI accordingly
    document.addEventListener('DOMContentLoaded', function() {
        const isTelegramWebApp = window.Telegram && 
                                window.Telegram.WebApp && 
                                window.Telegram.WebApp.initData && 
                                window.Telegram.WebApp.initData.length > 0;
                                
        if (isTelegramWebApp) {
            // Show Telegram-only view and hide regular registration
            document.querySelector('form').parentElement.parentElement.classList.add('hidden');
            
            const telegramView = document.getElementById('telegram-only-view');
            telegramView.classList.remove('hidden');
            telegramView.classList.add('block');
        }
    });
</script>
@endpush
