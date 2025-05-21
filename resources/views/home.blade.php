@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-10 p-6 bg-white rounded-xl shadow-md">
    <h2 class="text-3xl font-bold mb-6 text-center">Welcome, {{ $user->name }}</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Coins -->
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4">
            <p class="font-semibold text-lg">Your Coins</p>
            <p class="text-3xl font-bold">{{ $user->coins }}</p>
        </div>

        <!-- Spins Left -->
        <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4">
            <p class="font-semibold text-lg">Spins Left Today</p>
            <p class="text-3xl font-bold">{{ $spinsLeft }}</p>
        </div>

        <!-- Ads Left -->
        <div class="bg-pink-100 border-l-4 border-pink-500 text-pink-700 p-4">
            <p class="font-semibold text-lg">Ads Left Today</p>
            <p class="text-3xl font-bold">{{ $adsLeft }}</p>
        </div>

        <!-- Spin Button -->
        <div class="col-span-2 text-center mt-6">
            <a href="{{ route('spin') }}" class="inline-block bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-full text-xl">
                🎯 Spin Now
            </a>
        </div>

        <!-- Watch Ads -->
        <div class="col-span-2 text-center mt-6">
            <a href="{{ route('ads.show') }}" class="inline-block bg-pink-500 hover:bg-pink-600 text-white px-6 py-3 rounded-full text-xl">
                📺 Watch Ads for Coins
            </a>
        </div>

        <!-- Withdraw Request -->
        <div class="col-span-2 text-center mt-6">
            <a href="{{ route('withdraw.form') }}" class="inline-block bg-purple-500 hover:bg-purple-600 text-white px-6 py-3 rounded-full text-xl">
                💰 Withdraw Coins
            </a>
        </div>

        <!-- Withdraw History -->
        <div class="col-span-2 text-center mt-6">
            <a href="{{ route('withdraw.history') }}" class="inline-block bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-full text-xl">
                📜 View Withdraw History
            </a>
        </div>

        <!-- Profile Settings -->
        <div class="col-span-2 text-center mt-6">
            <a href="{{ route('profile.settings') }}" class="inline-block bg-indigo-500 hover:bg-indigo-600 text-white px-6 py-3 rounded-full text-xl">
                ⚙️ Profile Settings
            </a>
        </div>
    </div>
</div>

<!-- Bottom Mobile Navigation -->
<div class="fixed bottom-0 left-0 w-full bg-white shadow-md md:hidden">
    <div class="flex justify-around py-2">
        <a href="{{ route('dashboard') }}" class="text-center text-sm">
            <div>🏠</div>
            <div>Home</div>
        </a>
        <a href="{{ route('spin') }}" class="text-center text-sm">
            <div>🎯</div>
            <div>Spin</div>
        </a>
        <a href="{{ route('ads.show') }}" class="text-center text-sm">
            <div>📺</div>
            <div>Ads</div>
        </a>
        <a href="{{ route('withdraw.form') }}" class="text-center text-sm">
            <div>💰</div>
            <div>Withdraw</div>
        </a>
        <a href="{{ route('profile.settings') }}" class="text-center text-sm">
            <div>⚙️</div>
            <div>Settings</div>
        </a>
    </div>
</div>
@endsection
