<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
    $appName = \App\Models\Setting::where('key', 'app_name')->first()?->value ?? 'Admin Panel';
    $favicon = \App\Models\Setting::where('key', 'favicon')->first()?->value ?? 'favicon.ico';
    @endphp
    <title>@yield('title', 'Admin Dashboard') - {{ $appName }}</title>
    <link rel="icon" href="/{{ $favicon }}" type="image/x-icon">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        
        .sidebar {
            min-height: 100vh;
            background-color: #212529;
            padding-top: 1rem;
        }
        
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.6);
            margin: 0.2rem 0;
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
        }
        
        .sidebar .nav-link:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar .nav-link.active {
            color: #fff;
            background-color: #0d6efd;
        }
        
        .sidebar-heading {
            font-size: 0.8rem;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.4);
            padding: 0.75rem 1rem;
            font-weight: bold;
        }
        
        main {
            padding: 1.5rem;
        }
        
        .navbar-brand {
            padding: 0.75rem 1rem;
            font-size: 1.1rem;
            font-weight: 600;
            color: white;
            background-color: #171a1d;
            width: 100%;
            display: block;
            text-align: center;
            margin-bottom: 1rem;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <a href="{{ route('admin.dashboard') }}" class="navbar-brand">Admin Panel</a>
                
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-home fa-fw me-2"></i> Dashboard
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                            <i class="fas fa-users fa-fw me-2"></i> User Management
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.spin-config.*') ? 'active' : '' }}" href="{{ route('admin.spin-config.index') }}">
                            <i class="fas fa-dharmachakra fa-fw me-2"></i> Spin Configuration
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.watch-earn.*') ? 'active' : '' }}" href="{{ route('admin.watch-earn.index') }}">
                            <i class="fas fa-play-circle fa-fw me-2"></i> Watch & Earn
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.shortlinks.*') ? 'active' : '' }}" href="{{ route('admin.shortlinks.index') }}">
                            <i class="fas fa-link fa-fw me-2"></i> Shortlinks
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.adsterra.*') ? 'active' : '' }}" href="{{ route('admin.adsterra.index') }}">
                            <i class="fas fa-ad fa-fw me-2"></i> Adsterra Monitor
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.transactions.*') ? 'active' : '' }}" href="{{ route('admin.transactions.index') }}">
                            <i class="fas fa-exchange-alt fa-fw me-2"></i> Transactions
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.analytics.*') ? 'active' : '' }}" href="{{ route('admin.analytics.index') }}">
                            <i class="fas fa-chart-line fa-fw me-2"></i> Analytics
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" href="{{ route('admin.settings.index') }}">
                            <i class="fas fa-cog fa-fw me-2"></i> Settings
                        </a>
                    </li>
                    
                    <li class="sidebar-heading mt-3">Email System</li>
                    
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.email_templates.*') ? 'active' : '' }}" href="{{ route('admin.email_templates.index') }}">
                            <i class="fas fa-envelope-open-text fa-fw me-2"></i> Email Templates
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.email_settings.*') ? 'active' : '' }}" href="{{ route('admin.email_settings.index') }}">
                            <i class="fas fa-mail-bulk fa-fw me-2"></i> SMTP Settings
                        </a>
                    </li>
                    
                    <li class="sidebar-heading mt-3">Integrations</li>
                    
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.telegram.dashboard') ? 'active' : '' }}" href="{{ route('admin.telegram.dashboard') }}">
                            <i class="fab fa-telegram fa-fw me-2"></i> Telegram Dashboard
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.telegram.users') ? 'active' : '' }}" href="{{ route('admin.telegram.users') }}">
                            <i class="fas fa-users fa-fw me-2"></i> Telegram Users
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.telegram.messages') ? 'active' : '' }}" href="{{ route('admin.telegram.messages') }}">
                            <i class="fas fa-bullhorn fa-fw me-2"></i> Telegram Messaging
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.telegram.config') ? 'active' : '' }}" href="{{ route('admin.telegram.config') }}">
                            <i class="fas fa-cogs fa-fw me-2"></i> Telegram Config
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.support.*') ? 'active' : '' }}" href="{{ route('admin.support.index') }}">
                            <i class="fas fa-headset fa-fw me-2"></i> Support
                        </a>
                    </li>
                    
                    <!-- Add Live Chat to the admin navigation sidebar -->
                    <div class="sb-sidenav-menu-heading">Customer Support</div>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.chat.*') ? 'active' : '' }}" href="{{ route('admin.chat.index') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-comments"></i></div>
                            Live Chat
                        </a>
                    </li>
                    
                    <li class="nav-item mt-4">
                        <form method="POST" action="{{ route('admin.logout') }}" class="px-3">
                            @csrf
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-sign-out-alt fa-fw me-2"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
            
            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">@yield('header', 'Dashboard')</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <span class="text-muted">Welcome, {{ Auth::guard('admin')->user()->name ?? 'Admin' }}</span>
                    </div>
                </div>
                
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @yield('content')
            </main>
        </div>
    </div>
    
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Include any scripts from child views -->
    @stack('scripts')
</body>
</html> 