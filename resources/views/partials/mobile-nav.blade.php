<!-- Bottom Mobile Navigation -->
<div class="fixed bottom-0 left-0 w-full bg-white shadow-lg md:hidden z-50">
    <div class="flex justify-around py-3">
        <a href="{{ route('dashboard') }}" class="text-center text-xs {{ request()->routeIs('dashboard') || request()->routeIs('home') ? 'text-indigo-600' : 'text-gray-700' }}">
            <div class="text-xl mb-1">🏠</div>
            <div>Home</div>
        </a>
        <a href="{{ route('spin') }}" class="text-center text-xs {{ request()->routeIs('spin') || request()->routeIs('spin.*') ? 'text-indigo-600' : 'text-gray-700' }}">
            <div class="text-xl mb-1">🎯</div>
            <div>Spin</div>
        </a>
        <a href="{{ route('withdraw.proof') }}" class="text-center text-xs {{ request()->routeIs('withdraw.proof') ? 'text-indigo-600' : 'text-gray-700' }}">
            <div class="text-xl mb-1">✅</div>
            <div>Proofs</div>
        </a>
        <a href="{{ route('shortlinks.index') }}" class="text-center text-xs {{ request()->routeIs('shortlinks.*') ? 'text-indigo-600' : 'text-gray-700' }}">
            <div class="text-xl mb-1">🔗</div>
            <div>Links</div>
        </a>
        <a href="{{ route('ads.show') }}" class="text-center text-xs {{ request()->routeIs('ads.*') ? 'text-indigo-600' : 'text-gray-700' }}">
            <div class="text-xl mb-1">📺</div>
            <div>Videos</div>
        </a>
        <a href="{{ route('withdraw.form') }}" class="text-center text-xs {{ request()->routeIs('withdraw.form') ? 'text-indigo-600' : 'text-gray-700' }}">
            <div class="text-xl mb-1">💰</div>
            <div>Withdraw</div>
        </a>
        @auth
            <a href="{{ route('user.settings') }}" class="text-center text-xs {{ request()->routeIs('user.settings') ? 'text-indigo-600' : 'text-gray-700' }}">
                <div class="text-xl mb-1">👤</div>
                <div>Profile</div>
            </a>
        @else
            <a href="{{ route('login') }}" class="text-center text-xs {{ request()->routeIs('login') ? 'text-indigo-600' : 'text-gray-700' }}">
                <div class="text-xl mb-1">🔑</div>
                <div>Login</div>
            </a>
        @endauth
    </div>
</div>

<!-- Add padding to prevent content from being hidden behind mobile navigation -->
<div class="h-20 md:hidden"></div> 