@extends('layouts.app')

@section('content')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<style>
    [x-cloak] { display: none !important; }
    .highlight-glow {
        animation: glow 2s infinite alternate;
    }
    @keyframes glow {
        from { box-shadow: 0 0 10px -5px rgba(79, 70, 229, 0.8); }
        to { box-shadow: 0 0 20px 5px rgba(79, 70, 229, 0.8); }
    }
    .floating-animation {
        animation: floating 3s ease-in-out infinite;
    }
    @keyframes floating {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
        100% { transform: translateY(0px); }
    }
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
    <!-- Hero Section -->
    <div class="relative overflow-hidden bg-gradient-to-br from-indigo-700 to-purple-700 rounded-2xl shadow-xl p-6 mb-5 backdrop-blur-lg">
        <div class="absolute inset-0 bg-white opacity-10 backdrop-blur-xl"></div>
        <div class="absolute -bottom-20 -right-20 w-48 h-48 bg-purple-400 rounded-full opacity-30 animate-pulse"></div>
        <div class="relative">
            <h2 class="text-2xl font-extrabold text-white mb-3 animate-slide-in">Spin, Earn & Win! 🚀</h2>
            <p class="text-indigo-100 text-sm mb-4">Join thousands of users earning rewards daily through our platform.</p>
            
            <div class="flex gap-3 mt-4">
                <a href="{{ route('login') }}" class="px-5 py-2 bg-white text-indigo-600 font-semibold rounded-lg shadow hover:bg-indigo-50 transition transform hover:scale-105">
                    Login
                </a>
                <a href="{{ route('register') }}" class="px-5 py-2 bg-gradient-to-r from-pink-500 to-rose-500 text-white font-semibold rounded-lg shadow hover:from-pink-600 hover:to-rose-600 transition transform hover:scale-105 highlight-glow">
                    Register Now
                </a>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-3">Why Join Us?</h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-5 hover:shadow-lg transition">
            <div class="flex items-center mb-3">
                <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900 rounded-full flex items-center justify-center text-indigo-600 dark:text-indigo-300 mr-3 floating-animation">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Daily Rewards</h4>
            </div>
            <p class="text-gray-600 dark:text-gray-400 text-sm">Spin the wheel daily to earn coins that can be converted to real cash.</p>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-5 hover:shadow-lg transition">
            <div class="flex items-center mb-3">
                <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900 rounded-full flex items-center justify-center text-purple-600 dark:text-purple-300 mr-3 floating-animation">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                </div>
                <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Watch & Earn</h4>
            </div>
            <p class="text-gray-600 dark:text-gray-400 text-sm">Earn by simply watching ads and videos in your spare time.</p>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-5 hover:shadow-lg transition">
            <div class="flex items-center mb-3">
                <div class="w-10 h-10 bg-pink-100 dark:bg-pink-900 rounded-full flex items-center justify-center text-pink-600 dark:text-pink-300 mr-3 floating-animation">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                    </svg>
                </div>
                <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Shortlinks</h4>
            </div>
            <p class="text-gray-600 dark:text-gray-400 text-sm">Complete shortlinks to earn additional coins every day.</p>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-5 hover:shadow-lg transition">
            <div class="flex items-center mb-3">
                <div class="w-10 h-10 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center text-green-600 dark:text-green-300 mr-3 floating-animation">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Easy Withdrawals</h4>
            </div>
            <p class="text-gray-600 dark:text-gray-400 text-sm">Withdraw your earnings directly to your mobile banking account.</p>
        </div>
    </div>

    <!-- How It Works -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-6 mb-5">
        <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-4">How It Works</h3>
        <div class="space-y-4">
            <div class="flex">
                <div class="flex-shrink-0 w-8 h-8 bg-indigo-500 rounded-full flex items-center justify-center text-white font-bold mr-3">1</div>
                <div>
                    <h4 class="font-semibold text-gray-800 dark:text-gray-200">Sign Up</h4>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">Create your free account in less than a minute.</p>
                </div>
            </div>
            
            <div class="flex">
                <div class="flex-shrink-0 w-8 h-8 bg-indigo-500 rounded-full flex items-center justify-center text-white font-bold mr-3">2</div>
                <div>
                    <h4 class="font-semibold text-gray-800 dark:text-gray-200">Earn Coins</h4>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">Complete simple tasks to earn coins daily.</p>
                </div>
            </div>
            
            <div class="flex">
                <div class="flex-shrink-0 w-8 h-8 bg-indigo-500 rounded-full flex items-center justify-center text-white font-bold mr-3">3</div>
                <div>
                    <h4 class="font-semibold text-gray-800 dark:text-gray-200">Cash Out</h4>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">Withdraw your earnings to your preferred payment method.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl shadow-xl p-6 text-white mb-5">
        <h3 class="text-xl font-bold mb-3">Ready to Start Earning?</h3>
        <p class="text-indigo-100 mb-4">Join thousands of users who are already earning rewards every day.</p>
        <div class="flex gap-3">
            <a href="{{ route('register') }}" class="px-6 py-3 bg-white text-indigo-600 font-bold rounded-lg shadow hover:bg-indigo-50 transition transform hover:scale-105">
                Register Now
            </a>
            <a href="{{ route('login') }}" class="px-6 py-3 bg-transparent border border-white text-white font-bold rounded-lg hover:bg-white hover:bg-opacity-10 transition">
                Login
            </a>
        </div>
    </div>
    
    <!-- Testimonials -->
    <div class="mb-5">
        <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-3">What Our Users Say</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-5 hover:shadow-lg transition">
                <div class="flex items-center mb-3">
                    <div class="w-10 h-10 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center text-gray-600 dark:text-gray-300 mr-3">
                        <span class="text-sm font-bold">JD</span>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800 dark:text-gray-200">John Doe</h4>
                        <div class="flex text-yellow-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        </div>
                    </div>
                </div>
                <p class="text-gray-600 dark:text-gray-400 text-sm">"I've already withdrawn ৳500 in my first month. This app is amazing!"</p>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-5 hover:shadow-lg transition">
                <div class="flex items-center mb-3">
                    <div class="w-10 h-10 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center text-gray-600 dark:text-gray-300 mr-3">
                        <span class="text-sm font-bold">SJ</span>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800 dark:text-gray-200">Sarah Johnson</h4>
                        <div class="flex text-yellow-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        </div>
                    </div>
                </div>
                <p class="text-gray-600 dark:text-gray-400 text-sm">"So easy to use and the payouts are fast. The spin feature is my favorite!"</p>
            </div>
        </div>
    </div>
</div>

<!-- Login/Register Floating Button -->
<div class="fixed bottom-24 right-4 z-40">
    <a href="{{ route('register') }}" class="group flex items-center justify-center bg-gradient-to-r from-indigo-600 to-purple-600 text-white w-16 h-16 rounded-full shadow-xl hover:shadow-2xl transition transform hover:scale-110 highlight-glow">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
        </svg>
    </a>
</div>

@include('partials.mobile-nav')
@endsection 