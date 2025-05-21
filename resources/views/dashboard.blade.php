@extends('layouts.app')

@section('content')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<style>
    [x-cloak] { display: none !important; }
</style>

<!-- Maintenance Alert -->
@if($maintenanceMode)
<div class="mx-4 mt-4 p-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 rounded-xl animate-bounce">
    <div class="flex items-center">
        <div class="text-2xl mr-3">🛠️</div>
        <div>
            <p class="font-bold text-sm">Under Maintenance</p>
            <p class="text-xs">Some features may be unavailable.</p>
        </div>
    </div>
</div>
@endif

<!-- Main Container -->
<div class="mx-4 mt-4">
    <!-- Welcome Card with Animated Background -->
    <div class="relative overflow-hidden bg-gradient-to-br from-indigo-700 to-purple-700 rounded-2xl shadow-xl p-6 mb-5 backdrop-blur-lg animate-gradient-bg">
        <div class="absolute inset-0 bg-white opacity-10 backdrop-blur-xl"></div>
        <div class="absolute -bottom-20 -right-20 w-48 h-48 bg-purple-400 rounded-full opacity-30 animate-pulse"></div>
        <div class="relative flex flex-col sm:flex-row items-start sm:items-center justify-between">
            <div>
                <h2 class="text-2xl font-extrabold text-white2 mb-2 animate-slide-in">Welcome, {{ $user->name }}! 🚀</h2>
                <p class="text-indigo-100 text-sm">Spin, play, and win big today!</p>
            </div>
            <button class="mt-3 sm:mt-0 bg-white bg-opacity-30 hover:bg-opacity-40 text-white px-4 py-2 rounded-full transition text-sm font-semibold" @click="open = true" aria-label="View profile">
                My Profile
            </button>
        </div>
        <div class="grid grid-cols-3 gap-3 mt-4">
            <div class="bg-white bg-opacity-20 p-3 rounded-lg text-center transform hover:scale-105 transition">
                <p class="text-white font-bold text-lg">{{ number_format($user->coins) }}</p>
                <p class="text-indigo-100 text-xs">Coins</p>
            </div>
            <div class="bg-white bg-opacity-20 p-3 rounded-lg text-center transform hover:scale-105 transition">
                <p class="text-white font-bold text-lg">৳{{ number_format($moneyEquivalent, 2) }}</p>
                <p class="text-indigo-100 text-xs">Worth</p>
            </div>
            <div class="bg-white bg-opacity-20 p-3 rounded-lg text-center transform hover:scale-105 transition">
                <p class="text-white font-bold text-lg">{{ $spinsLeft + $adsLeft }}</p>
                <p class="text-indigo-100 text-xs">Actions Left</p>
            </div>
        </div>
    </div>

    <!-- Quick Actions with Hover Effects -->
    <div class="grid grid-cols-2 gap-4 mb-5">
        <a href="{{ route('spin') }}" class="group relative bg-gradient-to-br from-purple-600 to-pink-600 text-white2 rounded-2xl p-5 shadow-lg hover:shadow-2xl transition-all transform hover:-translate-y-1">
            <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-20 transition-opacity rounded-2xl"></div>
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-3 text-pink-200" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                </svg>
                <div>
                    <h3 class="text-lg font-bold">Spin & Win</h3>
                    <p class="text-pink-100 text-xs">Earn up to 100 Coins!</p>
                </div>
            </div>
        </a>
        <a href="{{ route('ads.show') }}" class="group relative bg-gradient-to-br from-indigo-600 to-cyan-500 text-white2 rounded-2xl p-5 shadow-lg hover:shadow-2xl transition-all transform hover:-translate-y-1">
            <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-20 transition-opacity rounded-2xl"></div>
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-3 text-cyan-200" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z" />
                </svg>
                <div>
                    <h3 class="text-lg font-bold">Watch & Earn</h3>
                    <p class="text-cyan-100 text-xs">Coins for watching videos!</p>
                </div>
            </div>
        </a>
        <a href="{{ route('referrals.index') }}" class="group relative bg-gradient-to-br from-purple-600 to-pink-600 text-white2 rounded-2xl p-5 shadow-lg hover:shadow-2xl transition-all transform hover:-translate-y-1">
            <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-20 transition-opacity rounded-2xl"></div>
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-3 text-pink-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.586a1 1 0 01.707 1.707l-6.586 6.586a1 1 0 01-1.414 0l-6.586-6.586A1 1 0 015.414 10H10V6a2 2 0 114 0v4z" />
                </svg>
                <div>
                    <h3 class="text-lg font-bold">Refer & Earn</h3>
                    <p class="text-pink-100 text-xs">Invite friends & get rewards!</p>
                </div>
            </div>
        </a>
        <a href="{{ route('shortlinks.index') }}" class="group relative bg-gradient-to-br from-purple-600 to-pink-600 text-white2 rounded-2xl p-5 shadow-lg hover:shadow-2xl transition-all transform hover:-translate-y-1">
            <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-20 transition-opacity rounded-2xl"></div>
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-3 text-pink-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                <div>
                    <h3 class="text-lg font-bold">Short Links</h3>
                    <p class="text-pink-100 text-xs">Earn by visiting links</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Game Cards Section -->
    <div class="mb-5">
        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-3">Play & Earn</h3>
        <div class="grid grid-cols-2 gap-4">
            <a href="{{ route('coming-soon') }}" class="group relative bg-gradient-to-br from-yellow-500 to-orange-500 text-white2 rounded-2xl p-5 shadow-lg hover:shadow-2xl transition-all transform hover:-translate-y-1">
                <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-20 transition-opacity rounded-2xl"></div>
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-3 text-orange-200" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                    </svg>
                    <div>
                        <h4 class="text-base font-bold">Color Crowd</h4>
                        <p class="text-orange-100 text-xs">Play to earn 100 Coins!</p>
                    </div>
                </div>
            </a>
            <a href="{{ route('coming-soon') }}" class="group relative bg-gradient-to-br from-green-500 to-teal-500 text-white2 rounded-2xl p-5 shadow-lg hover:shadow-2xl transition-all transform hover:-translate-y-1">
                <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-20 transition-opacity rounded-2xl"></div>
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-3 text-teal-200" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10 2a5 5 0 00-5 5v2a2 2 0 00-2 2v5a2 2 0 002 2h10a2 2 0 002-2v-5a2 2 0 00-2-2H5V7a3 3 0 016 0v2h2V7a5 5 0 00-5-5z" />
                    </svg>
                    <div>
                        <h4 class="text-base font-bold">100 Balls</h4>
                        <p class="text-teal-100 text-xs">Play to earn 100 Coins!</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Stats Cards with Progress Bars -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-5">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-4 group relative overflow-hidden transform hover:scale-105 transition">
            <div class="absolute inset-0 bg-indigo-500 opacity-0 group-hover:opacity-10 transition-opacity rounded-2xl"></div>
            <p class="text-xs text-gray-500 dark:text-gray-400">Total Coins</p>
            <p class="text-xl font-bold text-gray-800 dark:text-gray-200">{{ number_format($user->coins) }}</p>
            <p class="text-gray-600 dark:text-gray-400 text-xs">৳{{ number_format($moneyEquivalent, 2) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-4 group relative overflow-hidden transform hover:scale-105 transition">
            <div class="absolute inset-0 bg-purple-500 opacity-0 group-hover:opacity-10 transition-opacity rounded-2xl"></div>
            <p class="text-xs text-gray-500 dark:text-gray-400">Spins Left</p>
            <p class="text-xl font-bold text-gray-800 dark:text-gray-200">{{ $spinsLeft }}</p>
            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mt-2">
                @php
                    $spinsPercentage = $maxSpins > 0 ? max(0, min(100, (($maxSpins - $spinsLeft) / $maxSpins) * 100)) : 0;
                @endphp
                <div class="bg-purple-500 h-2 rounded-full" style="width: {{ $spinsPercentage }}%"></div>
            </div>
            <p class="text-xs text-gray-500 mt-1">{{ $maxSpins - $spinsLeft }}/{{ $maxSpins }} used</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-4 group relative overflow-hidden transform hover:scale-105 transition">
            <div class="absolute inset-0 bg-cyan-500 opacity-0 group-hover:opacity-10 transition-opacity rounded-2xl"></div>
            <p class="text-xs text-gray-500 dark:text-gray-400">Videos Left</p>
            <p class="text-xl font-bold text-gray-800 dark:text-gray-200">{{ $adsLeft }}</p>
            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mt-2">
                @php
                    $adsPercentage = $maxAds > 0 ? max(0, min(100, (($maxAds - $adsLeft) / $maxAds) * 100)) : 0;
                @endphp
                <div class="bg-cyan-500 h-2 rounded-full" style="width: {{ $adsPercentage }}%"></div>
            </div>
            <p class="text-xs text-gray-500 mt-1">{{ $maxAds - $adsLeft }}/{{ $maxAds }} used</p>
        </div>
    </div>

    <!-- Recent Activity with Swipeable Tabs -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-5 mb-5" x-data="{tab: 'spins'}">
        <h3 class="text-lg font-bold mb-4 text-gray-800 dark:text-gray-200">Recent Activity</h3>
        <div class="flex space-x-3 mb-4 text-sm overflow-x-auto">
            <button @click="tab = 'spins'" :class="tab == 'spins' ? 'text-purple-600 border-b-2 border-purple-600' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200'" class="pb-2 font-semibold whitespace-nowrap">Spins</button>
            <button @click="tab = 'withdrawals'" :class="tab == 'withdrawals' ? 'text-purple-600 border-b-2 border-purple-600' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200'" class="pb-2 font-semibold whitespace-nowrap">Withdrawals</button>
        </div>

        <!-- Spins Content -->
        <div x-cloak x-show="tab === 'spins'" class="space-y-3">
            @if(count($recentSpins) > 0)
                @foreach($recentSpins as $spin)
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                        <div class="flex items-center">
                            <div class="bg-purple-100 dark:bg-purple-900 p-2 rounded-full mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-500 dark:text-purple-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-800 dark:text-gray-200">{{ number_format($spin->coins_won) }} Coins</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $spin->sector }}</p>
                            </div>
                        </div>
                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ $spin->created_at->diffForHumans() }}</span>
                    </div>
                @endforeach
            @else
                <div class="text-center py-5">
                    <p class="text-gray-500 dark:text-gray-400 text-sm">No spins yet!</p>
                    <a href="{{ route('spin') }}" class="text-purple-600 hover:text-purple-800 dark:text-purple-400 dark:hover:text-purple-300 text-sm font-semibold">Start Spinning Now</a>
                </div>
            @endif
        </div>

        <!-- Withdrawals Content -->
        <div x-cloak x-show="tab === 'withdrawals'" class="space-y-3">
            @if(count($recentWithdraws) > 0)
                @foreach($recentWithdraws as $withdraw)
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                        <div class="flex items-center">
                            <div class="mr-3">
                                @if($withdraw->status === 'completed')
                                    <div class="bg-green-100 dark:bg-green-900 p-2 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 dark:text-green-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                @elseif($withdraw->status === 'pending')
                                    <div class="bg-yellow-100 dark:bg-yellow-900 p-2 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500 dark:text-yellow-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                @else
                                    <div class="bg-red-100 dark:bg-red-900 p-2 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500 dark:text-red-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <p class="text-sm text-gray-800 dark:text-gray-200">৳{{ number_format($withdraw->amount) }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $withdraw->payment_method }}</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs 
                            @if($withdraw->status === 'completed') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-200 
                            @elseif($withdraw->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-200 
                            @else bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-200 @endif">
                        {{ ucfirst($withdraw->status) }}
                    </span>
                    </div>
                @endforeach
            @else
                <div class="text-center py-5">
                    <p class="text-gray-500 dark:text-gray-400 text-sm">No withdrawals yet!</p>
                    <a href="{{ route('withdraw.form') }}" class="text-purple-600 hover:text-purple-800 dark:text-purple-400 dark:hover:text-purple-300 text-sm font-semibold">Withdraw Now</a>
                </div>
            @endif
        </div>
    </div>

    <!-- Withdraw Card -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-5 mb-5">
        <h3 class="text-lg font-bold mb-4 text-gray-800 dark:text-gray-200">Withdraw Earnings</h3>
        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-4">
            <div class="flex justify-between items-center mb-3">
                <p class="text-gray-500 dark:text-gray-400 text-sm">Available Balance:</p>
                <p class="text-lg font-bold text-gray-800 dark:text-gray-200">৳{{ number_format($moneyEquivalent, 2) }}</p>
            </div>
            <div class="text-xs text-gray-500 dark:text-gray-400">
                <p>Minimum: ৳{{ $minWithdraw }}</p>
                <p>Rate: {{ number_format($coinRate) }} coins = ৳1</p>
            </div>
        </div>
        <div class="flex flex-col gap-3">
            <a href="{{ route('withdraw.form') }}" class="block w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-lg py-3 text-center text-sm font-semibold transition transform hover:scale-105" @click="navigator.vibrate(50)">Withdraw Now</a>
            <a href="{{ route('withdraw.history') }}" class="block w-full bg-gradient-to-r from-gray-600 to-blue-600 hover:from-gray-700 hover:to-blue-700 text-white rounded-lg py-3 text-center text-sm font-semibold transition transform hover:scale-105" @click="navigator.vibrate(50)">Withdraw History</a>
            <a href="{{ route('withdraw.proof') }}" class="block w-full bg-gradient-to-r from-yellow-600 to-amber-600 hover:from-yellow-700 hover:to-amber-700 text-white rounded-lg py-3 text-center text-sm font-semibold transition transform hover:scale-105" @click="navigator.vibrate(50)">Withdrawal Proof</a>
        </div>
    </div> 

    <!-- Top Offers Section -->
    <div class="mb-5">
        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-3">Top Offers</h3>
        <div class="space-y-4">
            <a href="{{ route('coming-soon') }}" class="group flex items-center justify-between bg-gradient-to-r from-cyan-500 to-teal-500 text-white rounded-2xl p-5 shadow-lg hover:shadow-2xl transition-all transform hover:-translate-y-1">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-3 text-teal-200" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 01-1.731 0A1 1 0 007 7a3 3 0 116 0 1 1 0 01-1.732 0A1 1 0 0010 7z" clip-rule="evenodd" />
                    </svg>
                    <div>
                        <h4 class="text-base font-bold">Toluna</h4>
                        <p class="text-teal-100 text-xs">Complete surveys & earn!</p>
                    </div>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
            </a>
            <a href="{{ route('coming-soon') }}" class="group flex items-center justify-between bg-gradient-to-r from-indigo-500 to-blue-500 text-white rounded-2xl p-5 shadow-lg hover:shadow-2xl transition-all transform hover:-translate-y-1">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-3 text-blue-200" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z" clip-rule="evenodd" />
                    </svg>
                    <div>
                        <h4 class="text-base font-bold">Pagazani</h4>
                        <p class="text-blue-100 text-xs">Play games & earn!</p>
                    </div>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
            </a>
        </div>
    </div>
    @include('layouts.banner')
</div>

<!-- Bottom Navigation -->
    <!--<nav class="fixed bottom-0 left-0 right-0 bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700 flex justify-around items-center py-3 z-50 shadow-lg">
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center text-indigo-600 p-2" @click="navigator.vibrate(50)">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
            </svg>
            <span class="text-xs font-medium">Home</span>
        </a>
        <a href="{{ route('spin') }}" class="flex flex-col items-center text-gray-500 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 p-2" @click="navigator.vibrate(50)">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
            </svg>
            <span class="text-xs font-medium">Spin</span>
        </a>
        <div class="relative -mt-8">
            <a href="{{ route('withdraw.form') }}" class="flex items-center justify-center bg-gradient-to-r from-indigo-600 to-purple-600 text-white w-14 h-14 rounded-full shadow-xl hover:shadow-2xl transition transform hover:scale-110" @click="navigator.vibrate(100)">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M4 4 Berge 2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" />
                    <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd" />
                </svg>
            </a>
        </div>
        <a href="{{ route('ads.show') }}" class="flex flex-col items-center text-gray-500 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 p-2" @click="navigator.vibrate(50)">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z" />
            </svg>
            <span class="text-xs font-medium">Videos</span>
        </a>
        <a href="{{ url('/settings') }}" class="flex flex-col items-center text-gray-500 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 p-2" @click="navigator.vibrate(50)">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836 1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
            </svg>
            <span class="text-xs font-medium">Settings</span>
        </a>
    </nav>            -->

<!-- Support Button -->
<div class="fixed bottom-24 right-4 z-40">
    <a href="{{ route('chat.index') }}" class="group flex items-center justify-center bg-gradient-to-r from-indigo-600 to-purple-600 text-white w-16 h-16 rounded-full shadow-xl hover:shadow-2xl transition transform hover:scale-110 relative overflow-hidden" @click="navigator.vibrate(50)">
        <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-20 transition-opacity"></div>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 group-hover:animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
        </svg>
        <span class="absolute -bottom-10 group-hover:bottom-1 text-xs font-medium transition-all duration-300 opacity-0 group-hover:opacity-100">Live Chat</span>
    </a>
</div>


<!-- Profile Modal -->
<div x-data="{ open: false }" x-show="open" @keydown.escape="open = false" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50">
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 max-w-sm w-full">
        <h3 class="text-lg font-bold mb-3 text-gray-800 dark:text-gray-200">My Profile</h3>
        <p class="text-sm text-gray-600 dark:text-gray-300">Name: {{ $user->name }}</p>
        <p class="text-sm text-gray-600 dark:text-gray-300">Email: {{ $user->email }}</p>
        <p class="text-sm text-gray-600 dark:text-gray-300">Member Since: {{ $user->created_at->format('M Y') }}</p>
        <div class="mt-4 flex justify-end">
            <button @click="open = false" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition text-sm font-semibold">Close</button>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
    // Dark Mode Toggle
    const toggleButton = document.getElementById('theme-toggle');
    const sunIcon = document.getElementById('sun-icon');
    const moonIcon = document.getElementById('moon-icon');
    toggleButton.addEventListener('click', () => {
        document.documentElement.classList.toggle('dark');
        sunIcon.classList.toggle('hidden');
        moonIcon.classList.toggle('hidden');
        navigator.vibrate?.(50);
    });

    // Pull-to-Refresh
    let startY, isPulling = false;
    document.addEventListener('touchstart', e => {
        startY = e.touches[0].clientY;
        isPulling = window.scrollY === 0;
    });
    document.addEventListener('touchmove', e => {
        if (!isPulling) return;
        const y = e.touches[0].clientY;
        if (y - startY > 120) {
            navigator.vibrate?.(100);
            window.location.reload();
        }
    });

    // Swipe Tabs
    let touchStartX = 0;
    document.querySelector('.flex.space-x-3').addEventListener('touchstart', e => {
        touchStartX = e.touches[0].clientX;
    });
    document.querySelector('.flex.space-x-3').addEventListener('touchend', e => {
        const touchEndX = e.changedTouches[0].clientX;
        if (touchStartX - touchEndX > 50) {
            document.querySelector('[x-data]').__x.$data.tab = 'withdrawals';
        } else if (touchEndX - touchStartX > 50) {
            document.querySelector('[x-data]').__x.$data.tab = 'spins';
        }
    });

    // Prevent Pinch Zoom
    document.addEventListener('gesturestart', e => e.preventDefault());
</script>

<!-- Styles -->
<style>
    .animate-gradient-bg {
        background: linear-gradient(45deg, #4f46e5, #7c3aed, #db2777, #f97316);
        background-size: 400%;
        animation: gradientShift 15s ease infinite;
    }
    @keyframes gradientShift {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    .animate-slide-in {
        animation: slideIn 0.6s ease-out;
    }
    .animate-bounce {
        animation: bounce 0.5s ease;
    }
    @keyframes slideIn {
        from { transform: translateY(-15px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
        40% { transform: translateY(-10px); }
        60% { transform: translateY(-5px); }
    }
    .dark {
        background-color: #1f2937;
        color: #e5e7eb;
    }
    .dark .bg-white {
        background-color: #374151;
    }
    .dark .text-gray-800 {
        color: #e5e7eb;
    }
    .dark .text-gray-600 {
        color: #d1d5db;
    }
    .dark .bg-gray-50 {
        background-color: #4b5563;
    }
    [x-transition] {
        transition: opacity 0.4s ease, transform 0.4s ease;
    }
    [x-show="tab == 'spins'"]:not(.x-show) {
        opacity: 0;
        transform: translateX(-15px);
    }
    [x-show="tab == 'withdrawals'"]:not(.x-show) {
        opacity: 0;
        transform: translateX(15px);
    }
    body {
        -webkit-user-select: none;
        user-select: none;
        -webkit-tap-highlight-color: transparent;
        background-color: #f3f4f6;
    }
    a, button {
        touch-action: manipulation;
    }
    [x-cloak] { display: none !important; }
</style>

@endsection

@include('partials.mobile-nav')