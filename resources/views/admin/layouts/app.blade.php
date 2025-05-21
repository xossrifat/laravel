<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Fix conflicts between Bootstrap and Tailwind */
        .card {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 1.5rem;
        }
        .table {
            width: 100%;
            margin-bottom: 1rem;
        }
        .form-control, .form-check-input, .btn {
            appearance: auto;
        }
        .card-header {
            border-bottom: 1px solid #e5e7eb;
            padding: 1rem;
        }
        .card-body {
            padding: 1.5rem;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="bg-gray-800 text-white w-64 py-6 flex flex-col">
            <div class="px-6 mb-8">
                <h2 class="text-2xl font-bold">Admin Panel</h2>
            </div>
            <nav class="flex-1">
                <div class="px-4 space-y-2">
                    <!-- Dashboard -->
                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">
                        <i class="fas fa-home mr-2"></i> Dashboard
                    </a>

                    <!-- User Management -->
                    <a href="{{ route('admin.users.index') }}" class="block px-4 py-2 rounded-lg {{ request()->routeIs('admin.users.*') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">
                        <i class="fas fa-users mr-2"></i> User Management
                    </a>

                    <!-- Spin Configuration -->
                    <a href="{{ route('admin.spin-config.index') }}" class="block px-4 py-2 rounded-lg {{ request()->routeIs('admin.spin-config.*') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">
                        <i class="fas fa-dharmachakra mr-2"></i> Spin Configuration
                    </a>

                    <!-- Watch & Earn -->
                    <a href="{{ route('admin.watch-earn.index') }}" class="block px-4 py-2 rounded-lg {{ request()->routeIs('admin.watch-earn.*') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">
                        <i class="fas fa-play-circle mr-2"></i> Watch & Earn
                    </a>

                    <!-- Shortlinks -->
                    <a href="{{ route('admin.shortlinks.index') }}" class="block px-4 py-2 rounded-lg {{ request()->routeIs('admin.shortlinks.*') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">
                        <i class="fas fa-link mr-2"></i> Shortlinks
                    </a>

                    <!-- Global Ads removed -->

                    <!-- AdMob Integration -->
                    <a href="{{ route('admin.adsterra.index') }}" class="block px-4 py-2 rounded-lg {{ request()->routeIs('admin.adsterra.*') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">
                        <i class="fas fa-ad mr-2"></i> Adsterra Monitor
                    </a>

                    <!-- Transactions -->
                    <a href="{{ route('admin.transactions.index') }}" class="block px-4 py-2 rounded-lg {{ request()->routeIs('admin.transactions.*') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">
                        <i class="fas fa-exchange-alt mr-2"></i> Transactions
                    </a>

                    <!-- Analytics -->
                    <a href="{{ route('admin.analytics.index') }}" class="block px-4 py-2 rounded-lg {{ request()->routeIs('admin.analytics.*') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">
                        <i class="fas fa-chart-line mr-2"></i> Analytics
                    </a>

                    <!-- Settings -->
                    <a href="{{ route('admin.settings.index') }}" class="block px-4 py-2 rounded-lg {{ request()->routeIs('admin.settings.*') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">
                        <i class="fas fa-cog mr-2"></i> Settings
                    </a>

                    <!-- About Settings -->
                    <a href="{{ route('admin.about-settings.index') }}" class="block px-4 py-2 rounded-lg {{ request()->routeIs('admin.about-settings.*') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">
                        <i class="fas fa-info-circle mr-2"></i> About Page Settings
                    </a>

                    <!-- Feature Flags -->
                    <a href="{{ route('admin.features.index') }}" class="block px-4 py-2 rounded-lg {{ request()->routeIs('admin.features.*') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">
                        <i class="fas fa-flag mr-2"></i> ফিচার ফ্ল্যাগ
                    </a>

                    <!-- Email System -->
                    <div class="mt-2 mb-2">
                        <div class="px-4 py-2 text-gray-400 text-xs uppercase tracking-wider">Email System</div>
                        <a href="{{ route('admin.email_templates.index') }}" class="block px-4 py-2 rounded-lg {{ request()->routeIs('admin.email_templates.*') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">
                            <i class="fas fa-envelope-open-text mr-2"></i> Email Templates
                        </a>
                        <a href="{{ route('admin.email_settings.index') }}" class="block px-4 py-2 rounded-lg {{ request()->routeIs('admin.email_settings.*') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">
                            <i class="fas fa-mail-bulk mr-2"></i> SMTP Settings
                        </a>
                    </div>
                    
                    <!-- Integrations -->
                    <div class="mt-2 mb-2">
                        <div class="px-4 py-2 text-gray-400 text-xs uppercase tracking-wider">Integrations</div>
                        <a href="{{ route('admin.telegram.config') }}" class="block px-4 py-2 rounded-lg {{ request()->routeIs('admin.telegram.*') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">
                            <i class="fab fa-telegram mr-2"></i> Telegram Bot
                        </a>
                    </div>
                </div>
            </nav>
            <div class="px-6 py-4">
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="w-full bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1">
            <!-- Top Navigation -->
            <nav class="bg-white shadow-lg">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <h1 class="text-2xl font-semibold text-gray-900">@yield('header', 'Dashboard')</h1>
                        </div>
                        <div class="flex items-center">
                            <span class="text-gray-500">Welcome, {{ Auth::guard('admin')->user()->name }}</span>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 