<!DOCTYPE html>
<html lang="en" class="{{ auth()->check() && auth()->user()->theme == 'dark' ? 'dark' : 'light' }}">
<head>
    <meta charset="UTF-8">
    @php
    $appName = \App\Models\Setting::where('key', 'app_name')->first()?->value ?? ' RewardBazar';
    $favicon = \App\Models\Setting::where('key', 'favicon')->first()?->value ?? 'favicon.ico';
    $userTheme = auth()->check() ? auth()->user()->theme : 'light';
    @endphp
    <title>{{ $appName }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-preference" content="{{ $userTheme }}">
    <meta name="theme-color" content="#4f46e5">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="/{{ $favicon }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    
    <!-- Vite Assets (with proper HTTPS URLs) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @yield('head')
    
    <style>
        /* Theme settings */
        :root {
            color-scheme: light dark;
        }
        
        /* Light theme styles */
        html.light body {
            background-color: #f2f3f7;
            color: #333333;
        }
        
        /* Card styles for light theme */
        html.light .dark\:bg-gray-800 {
            background-color: rgb(223, 223, 223);
            border-color: #e5e7eb !important;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05) !important;
        }

        html.light .settings-container {
            background-color: rgb(255, 255, 255);
            border-color: #e5e7eb !important;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05) !important;
        }
        
        html.light .settings-wrapper {
            background-color: rgb(255, 255, 255);
            border-color: #e5e7eb !important;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05) !important;
        }



        html.light .settings-container .settings-title {
            color: #000;
        }

        html.light .settings-container .settings-icon {
            color: #000;
        }

        html.light .settings-container .settings-label {
            color: #000;
        }

        html.light .settings-container .chevron-icon {
            color: #000;
        }
         
        html.light .settings-container .notification-title {
            color: #000;
        }

        html.light .settings-container .section-title {
            color: #000;
        }

        html.light .settings-container .app-name {
            color: #000;
        }

        html.light .settings-container .info-title {
            color: #000;
        }


        html.light .bg-gray-900 {
            --tw-bg-opacity: 1;
            background-color: rgb(255, 255, 255);
        }

        html.light .bg-gray-800 {
            background-color: rgb(223, 223, 223);
        }


        html.light .dark\:text-gray-300 {
            color: #000;
        }


        

        html.light .settings-container .back-button {
            color: #000;
        }


        html.light .settings-item:first-child .settings-link {
            border-left: 3px solid #000;
            background-color: #fff;
        }

        html.light .settings-link:hover, html.light .settings-link:active {
            background-color:rgb(214, 214, 214);
        }

            
        html.light .flex.items-center.space-x-3 svg {
            color: rgb(0, 0, 0) !important;
        }



        html.light .bg-gray-700 {
            background-color: #f3f4f6 !important;
        }

        html.light .bg-white2 {
            background-color:rgb(37, 37, 37) !important;
        }
        
        html.light .text-white {
            color: #333333 !important;
        }

        html.light .text-white2 {
            color: #ffffff !important;
        }

        html.light .text-indigo-100 {
            color:rgb(51, 51, 51) !important;
        }
        
        html.light .text-gray-400 {
            color: #6b7280 !important;
        }
        
        html.light .text-gray-300 {
            color: #6b7280 !important;
        }
        
        /* Light theme header */
        html.light header.bg-gradient-to-r {
            background: #5d4fff !important;
        }
        
        /* Light theme welcome gradient */
        html.light .welcome-banner {
            background: linear-gradient(to right, #ff6b8b, #ff8e53) !important;
            color: white !important;
        }
        
        html.light .welcome-banner * {
            color: white !important;
        }
        
        /* Dark mode styles */
        html.dark body {
            background-color: #1a1a1a;
            color: #e5e5e5;
        }
        
        html.dark .bg-white {
            background-color: #2a2a2a !important;
        }
        
        html.dark .theme-toggle .bg-white {
            background-color: #2a2a2a !important;
        }
        
        html.dark .bg-gray-100 {
            background-color: #1a1a1a !important;
        }
        
        html.dark .bg-gray-50 {
            background-color: #333333 !important;
        }
        
        html.dark .text-gray-800 {
            color: #e5e5e5 !important;
        }
        
        html.dark .text-gray-700 {
            color: #d1d1d1 !important;
        }
        
        html.dark .text-gray-500 {
            color: #a0a0a0 !important;
        }
        
        html.dark .border-gray-200 {
            border-color: #3a3a3a !important;
        }
        
        html.dark .shadow-md {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3), 0 2px 4px -1px rgba(0, 0, 0, 0.2) !important;
        }
        
        /* Dark mode for navigation and mobile navigation */
        html.dark nav.fixed.top-0,
        html.dark .fixed.bottom-0.left-0.w-full.bg-white.shadow-lg {
            background-color: #2a2a2a !important;
            border-color: #3a3a3a !important;
        }
        
        /* Light mode for mobile navigation */
        html.light .fixed.bottom-0.left-0.w-full.bg-white.shadow-lg {
            background-color: #ffffff !important;
            border-color: #e5e7eb !important;
        }
        
        /* Dark mode for form inputs - with more specific selectors */
        html.dark input[type="text"],
        html.dark input[type="email"],
        html.dark input[type="password"],
        html.dark input[type="number"],
        html.dark input[type="tel"],
        html.dark input[type="url"],
        html.dark textarea {
            background-color: #333333 !important;
            border-color: #444444 !important;
            color: #e5e5e5 !important;
        }
        
        /* Light mode for form inputs */
        html.light input,
        html.light textarea,
        html.light select {
            background-color: #ffffff !important;
            border-color:rgb(20, 20, 20) !important; /* gray-300 */
            color:rgb(5, 5, 5) !important; /* gray-800 */
        }
        
        /* Light mode action buttons */
        html.light .bg-blue-600 {
            background-color: #5d4fff !important;
        }
        
        html.light .bg-green-600 {
            background-color: #10b981 !important;
        }
        
        html.light .hover\:bg-blue-700:hover {
            background-color: #4f46e5 !important;
        }
        
        html.light .hover\:bg-green-700:hover {
            background-color: #059669 !important;
        }
        
        /* Ensure input field styles apply correctly by default */
        input, textarea, select {
            background-color: #ffffff;
            border-color: #d1d5db;
            color: #1f2937;
        }
        
        /* Dark mode for buttons that aren't explicitly colored */
        html.dark button:not(.bg-indigo-600):not(.bg-red-600):not(.bg-green-600):not(.bg-blue-600):not(.bg-yellow-600):not(.bg-pink-600) {

        }
        
        /* Dark mode for alerts and notifications */
        html.dark .bg-green-100 {
            background-color: rgba(0, 100, 0, 0.2) !important;
        }
        
        html.dark .bg-red-100 {
            background-color: rgba(100, 0, 0, 0.2) !important;
        }
        
        html.dark .bg-yellow-100 {
            background-color: rgba(100, 100, 0, 0.2) !important;
        }
        
        html.dark .bg-blue-100 {
            background-color: rgba(0, 0, 100, 0.2) !important;
        }
        
        /* Dark mode for borders */
        html.dark .border {
            border-color: #3a3a3a !important;
        }
        
        /* Special styling for toggle switches in dark mode */
        html.dark .peer-checked\:bg-indigo-600 {
            background-color: #6366f1 !important;
        }
        
        html.dark .dark\:bg-gray-700 {
            background-color: #333333 !important;
        }
        
        /* Focus, hover, and active states for dark mode */
        html.dark a:hover, 
        html.dark a:focus {
            color: #93c5fd !important; /* light blue in dark mode */
        }
        
        /* Support button animation */
        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(79, 70, 229, 0.7);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(79, 70, 229, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(79, 70, 229, 0);
            }
        }
        
        .support-btn-pulse {
            animation: pulse 2s infinite;
        }

        /* Mobile styles */
        @media (max-width: 768px) {
            .mobile-container {
                padding-left: 0.75rem;
                padding-right: 0.75rem;
                width: 100%;
            }
            
            .mobile-nav {
                height: 4rem;
                padding-bottom: 4rem;
            }
        }

        /* Form input specific styles */
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="number"],
        input[type="tel"],
        input[type="url"],
        textarea {
            background-color: #ffffff !important;
            color: #1f2937 !important;
            border-color: #d1d5db !important;
        }

        /* Toggle switch styles - fixes for both dark and light mode */
        .peer:checked ~ .peer-checked\:bg-indigo-600 {
            background-color: #4f46e5 !important;
        }
        
        .peer:checked ~ .peer-checked\:after\:translate-x-full:after {
            transform: translateX(100%) !important;
        }
        
        /* Ensure toggle track is visible */
        .peer-checked\:after\:translate-x-full {
            background-color: #d1d5db !important; /* light gray for track */
        }
        
        /* Dark mode tweaks for toggles */
        html.dark .peer-checked\:after\:translate-x-full {
            background-color: #4b5563 !important; /* darker gray for track in dark mode */
        }
        
        /* Mobile app-like styles */
        .mobile-status-bar {
            height: 24px;
            background-color: rgba(79, 70, 229, 0.95);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 10px;
            color: white;
            font-size: 12px;
            font-weight: 500;
        }
        
        .mobile-bottom-nav {
            background-color: white;
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
            box-shadow: 0 -5px 10px rgba(0, 0, 0, 0.05);
            padding: 10px 0 5px 0;
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            z-index: 50;
        }
        
        html.dark .mobile-bottom-nav {
            background-color: #2a2a2a;
            box-shadow: 0 -5px 10px rgba(0, 0, 0, 0.2);
        }
        
        .nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            font-size: 12px;
            font-weight: 500;
            color: #6B7280;
            transition: all 0.2s ease;
        }
        
        .nav-item.active {
            color: #4F46E5;
            transform: translateY(-5px);
        }
        
        html.dark .nav-item {
            color: #9CA3AF;
        }
        
        html.dark .nav-item.active {
            color: #6366F1;
        }
        
        .nav-icon {
            font-size: 20px;
            margin-bottom: 3px;
        }
        
        /* Hide elements while Alpine.js initializes to prevent flicker */
        [x-cloak] {
            display: none !important;
        }
        
        /* Card styles for light mode */
        html.light .rounded-lg {
            border-radius: 1rem !important;
        }
        
        html.light .shadow-md {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.07) !important;
        }
    </style>
    
    <!-- Global ads removed -->

   <script src='//solseewuthi.net/sdk.js' data-zone='9341414' data-sdk='show_9341414'></script>
</head>
<body class="bg-gray-100 text-gray-800">

    <!-- Theme script to apply selected theme -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const htmlElement = document.documentElement;
            const userTheme = document.querySelector('meta[name="theme-preference"]').content || 'light';
            const themeToggleBtn = document.getElementById('theme-toggle');
            const sunIcon = document.getElementById('sun-icon');
            const moonIcon = document.getElementById('moon-icon');
            
            // Function to update theme toggle icon
            function updateThemeIcon(theme) {
                if (theme === 'dark' || (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    sunIcon.classList.add('hidden');
                    moonIcon.classList.remove('hidden');
                } else {
                    sunIcon.classList.remove('hidden');
                    moonIcon.classList.add('hidden');
                }
            }
            
            // Function to apply theme based on preference
            function applyTheme(theme) {
                // First, clear any existing theme classes
                htmlElement.classList.remove('dark', 'light');
                
                if (theme === 'dark') {
                    htmlElement.classList.add('dark');
                    updateThemeIcon('dark');
                } else if (theme === 'light') {
                    htmlElement.classList.add('light');
                    updateThemeIcon('light');
                } else if (theme === 'system') {
                    // Check system preference
                    if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                        htmlElement.classList.add('dark');
                    } else {
                        htmlElement.classList.add('light');
                    }
                    
                    updateThemeIcon(theme);
                    
                    // Listen for system theme changes
                    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
                        // Remove both classes first
                        htmlElement.classList.remove('dark', 'light');
                        
                        if (e.matches) {
                            htmlElement.classList.add('dark');
                        } else {
                            htmlElement.classList.add('light');
                        }
                        
                        updateThemeIcon(theme);
                    });
                } else {
                    // Default to light theme if no valid theme is found
                    htmlElement.classList.add('light');
                    updateThemeIcon('light');
                }
            }
            
            // Apply theme initially
            applyTheme(userTheme);
            
            // Theme toggle functionality (quick toggle between light/dark)
            if (themeToggleBtn) {
                themeToggleBtn.addEventListener('click', function() {
                    const currentTheme = htmlElement.classList.contains('dark') ? 'dark' : 'light';
                    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                    
                    // Apply the new theme visually immediately
                    htmlElement.classList.remove('dark', 'light');
                    htmlElement.classList.add(newTheme);
                    updateThemeIcon(newTheme);
                    
                    // If user is logged in, save preference via fetch API
                    @auth
                    fetch('{{ route("user.theme.update") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ theme: newTheme })
                    });
                    @endauth
                });
            }
        });
    </script>


<!-- Collapsible Header -->
<header class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white2 px-4 py-3 flex items-center justify-between fixed top-0 left-0 right-0 z-40 shadow-lg transition-all duration-300" x-data="{ isScrolled: false }" @scroll.window="isScrolled = window.scrollY > 50" :class="{'py-2': isScrolled}">
    <div class="flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 animate-pulse" viewBox="0 0 20 20" fill="currentColor">
            <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
        </svg>
        <h1 class="font-extrabold text-lg tracking-tight" :class="{'text-base': isScrolled}">{{ $appName }}</h1>
    </div>
                <!-- Desktop Navigation Menu - Only visible on medium screens and up -->
                                <!-- Desktop Navigation Menu -->
                                <div class="hidden sm:hidden md:block lg:block xl:block flex items-center space-x-6">
                    <a href="{{ route('dashboard') }}" class="text-white hover:text-indigo-100 {{ request()->routeIs('dashboard') ? 'font-medium underline' : '' }}">
                        Home
                    </a>
                    <a href="{{ route('spin') }}" class="text-white hover:text-indigo-100 {{ request()->routeIs('spin') ? 'font-medium underline' : '' }}">
                        Spin
                    </a>
                    <a href="{{ route('ads.show') }}" class="text-white hover:text-indigo-100 {{ request()->routeIs('ads.*') ? 'font-medium underline' : '' }}">
                        Videos
                    </a>
                    <a href="{{ route('shortlinks.index') }}" class="text-white hover:text-indigo-100 {{ request()->routeIs('shortlinks.*') ? 'font-medium underline' : '' }}">
                        Shortlinks
                    </a>
                    <a href="{{ route('withdraw.form') }}" class="text-white hover:text-indigo-100 {{ request()->routeIs('withdraw.*') ? 'font-medium underline' : '' }}">
                        Withdraw
                    </a>
                    <a href="{{ route('referrals.index') }}" class="text-white hover:text-indigo-100 {{ request()->routeIs('referrals.index') ? 'font-medium underline' : '' }}">
                        Referrals
                    </a>
                    <a href="{{ route('chat.index') }}" class="text-white hover:text-indigo-100 {{ request()->routeIs('chat.*') || request()->routeIs('support.*') ? 'font-medium underline' : '' }}">
                        Live Chat
                    </a>
                    @auth
                        <a href="{{ route('user.settings') }}" class="text-white hover:text-indigo-100 {{ request()->routeIs('user.settings') ? 'font-medium underline' : '' }}">
                            Profile
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-white hover:text-indigo-100">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="px-3 py-1 bg-white text-indigo-600 rounded-lg hover:bg-indigo-100">
                            Register
                        </a>
                    @endauth
                </div>
    <div class="flex items-center space-x-3">
        <button id="theme-toggle" class="p-2 rounded-full bg-white bg-opacity-20 hover:bg-opacity-30 transition relative group" aria-label="Toggle dark mode">
            <svg id="sun-icon" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <svg id="moon-icon" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
            </svg>
        </button>
        <a href="{{ route('notifications.index') }}" class="relative p-2 rounded-full bg-white bg-opacity-20 hover:bg-opacity-30 transition group" aria-label="View notifications">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            @auth
            @php
                $unreadCount = auth()->user()->notifications()->where('is_read', false)->count();
            @endphp
            @if($unreadCount > 0)
                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center animate-ping">
                    {{ $unreadCount }}
                </span>
            @endif
            @endauth
        </a>
    </div>
</header>

    <!-- Spacer to prevent content from being hidden under fixed navbar -->
    <div class="hidden sm:hidden md:block lg:block xl:block h-16 md:h-20"></div>
    @include('layouts.banner')

    <main class="pt-8">
    @yield('content')
</main>


    @stack('scripts')
    
    <!-- Mobile Time Script -->
    <script>
        // Update the time in the status bar
        function updateTime() {
            const now = new Date();
            const timeElement = document.getElementById('current-time');
            if (timeElement) {
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                timeElement.textContent = hours + ':' + minutes;
            }
        }
        
        // Update time now and every minute
        updateTime();
        setInterval(updateTime, 60000);
    </script>

    @auth


    <style>
    .referral-floating-btn {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 99;
    }

    .referral-floating-btn a {
        display: flex;
        align-items: center;
        background: linear-gradient(to right, #9333ea, #6366f1);
        color: white;
        padding: 12px 20px;
        border-radius: 50px;
        font-weight: bold;
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .referral-floating-btn a:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0,0,0,0.2);
    }

    .referral-floating-btn i {
        margin-right: 8px;
        font-size: 1.2em;
    }

    @media (max-width: 640px) {
        .referral-floating-btn a span {
            display: none;
        }
        
        .referral-floating-btn a {
            width: 50px;
            height: 50px;
            justify-content: center;
            padding: 0;
        }
        
        .referral-floating-btn i {
            margin: 0;
            font-size: 1.5em;
        }
    }
    </style>
    @endauth
</body>
</html>
