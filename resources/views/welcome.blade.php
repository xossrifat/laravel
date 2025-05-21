<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', ' RewardBazar') }}</title>
    <meta name="description" content="Earn coins by spinning wheels and watching videos — redeem for real cash!">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fonts & Icons -->
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />

    <!-- Tailwind Inline (Optional Fallback) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Telegram Auth -->
    <script src="https://telegram.org/js/telegram-web-app.js"></script>
    <script src="{{ asset('js/telegram-auth.js') }}"></script>

    <style>
        body {
            font-family: 'Figtree', sans-serif;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-white via-blue-50 to-blue-100 min-h-screen flex items-center justify-center p-6">
    <div class="bg-white shadow-xl rounded-3xl w-full max-w-lg p-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-extrabold text-blue-700">🎉  RewardBazar</h1>
            <p class="mt-2 text-gray-600 text-sm">Reward Bazaar – বাংলাদেশের সেরা Spin & Earn অ্যাপ!  
            এখন ঘরে বসেই আয় করা যাবে একদম সহজভাবে।</p>
        </div>

        <!-- Telegram status -->
        <div id="telegram-auth-loading" class="hidden text-center bg-blue-100 text-blue-700 p-3 rounded mb-4">
            <i class="fas fa-spinner fa-spin mr-2"></i> Logging in via Telegram...
        </div>
        <div id="telegram-auth-error" class="hidden bg-red-100 text-red-700 p-3 rounded mb-4 text-sm"></div>
        <div id="telegram-auth-success" class="hidden bg-green-100 text-green-700 p-3 rounded mb-4 text-sm"></div>

        <!-- Telegram auto-login -->
        <div id="telegram-welcome" class="hidden text-center">
            <div class="text-5xl text-blue-500 mb-3">
                <i class="fab fa-telegram"></i>
            </div>
            <h2 class="text-xl font-bold text-gray-800">Welcome, Telegram User!</h2>
            <p class="text-gray-500 text-sm">Redirecting you...</p>
            <div class="mt-3 animate-pulse">
                <div class="h-1 w-24 bg-blue-500 rounded-full mx-auto"></div>
            </div>
        </div>

        <!-- Auth buttons -->
        <div id="regular-login-buttons" class="space-y-3">
            <a href="https://t.me/rewardbazar_bot/app" class="block text-center bg-gradient-to-r from-blue-500 to-indigo-500 hover:from-blue-600 hover:to-indigo-600 text-white font-semibold py-2.5 rounded-lg shadow transition duration-300">
            সাইন ইন করুন
            </a>
            <a href="{{ route('register') }}" class="block text-center bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2.5 rounded-lg transition duration-300">
            একাউন্ট তৈরি করুন
            </a>
            <a href="{{ route('withdraw.proof') }}" class="block text-center bg-gradient-to-r from-yellow-500 to-amber-500 hover:from-yellow-600 hover:to-amber-600 text-white font-semibold py-2.5 rounded-lg shadow transition duration-300 mt-4">
            <i class="fas fa-check-circle mr-1"></i> উইথড্র প্রুফ দেখুন
            </a>
        </div>

        <!-- Proof Banner -->
        <div class="mt-6 py-3 px-4 bg-amber-50 border border-amber-200 rounded-lg">
            <div class="flex items-center">
                <div class="text-amber-600 text-xl mr-3">
                    <i class="fas fa-trophy"></i>
                </div>
                <div class="text-sm">
                    <p class="font-medium text-amber-800">আমাদের সত্যিকারের পেমেন্ট প্রুফ দেখুন</p>
                    <p class="text-amber-700 text-xs">বাস্তব টাকা পেমেন্টের যাচাইয়ের জন্য উইথড্র প্রুফ দেখুন!</p>
                </div>
            </div>
        </div>

        <!-- Feature Cards -->
        <div class="grid grid-cols-2 gap-4 mt-8 text-center">
            <div class="p-4 rounded-xl shadow-md bg-blue-50 hover:bg-blue-100 transition">
                <div class="text-3xl text-blue-600 mb-1">
                    <i class="fas fa-dharmachakra"></i>
                </div>
                <p class="text-sm font-medium text-gray-700">🎯 স্পিন করো</p>
            </div>
            <div class="p-4 rounded-xl shadow-md bg-green-50 hover:bg-green-100 transition">
                <div class="text-3xl text-green-600 mb-1">
                    <i class="fas fa-video"></i>
                </div>
                <p class="text-sm font-medium text-gray-700">ভিডিও দেখো</p>
            </div>
            <div class="p-4 rounded-xl shadow-md bg-yellow-50 hover:bg-yellow-100 transition">
                <div class="text-3xl text-yellow-600 mb-1">
                    <i class="fas fa-coins"></i>
                </div>
                <p class="text-sm font-medium text-gray-700">💰 রিডিম করো টাকায়!</p>
            </div>
            <div class="p-4 rounded-xl shadow-md bg-pink-50 hover:bg-pink-100 transition">
                <div class="text-3xl text-pink-600 mb-1">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <p class="text-sm font-medium text-gray-700">🚀 আসল পেমেন্ট, আসল পুরস্কার!</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center text-xs text-black mt-6">
        তোমার ফোনেই এখন ইনকামের নতুন বাজার – Reward Bazaar!
        </div>
    </div>
</body>
</html>
