<!-- Mobile toggle menu button - only visible on small screens -->
<!-- <div class="mobile-menu-toggle" onclick="toggleMobileMenu()">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
    </svg> 
    Menu
</div> -->

<!-- Sidebar overlay - only visible when sidebar is open -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleMobileMenu()"></div>

<!-- Sidebar with balance and help box -->
<aside class="sidebar" id="settingsSidebar">
    <!-- Mobile close button - only visible when menu is open on mobile -->
    <div class="mobile-close" onclick="toggleMobileMenu()">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
        </svg>
    </div>

    <div class="balance-box">
        <div class="icon"></div>
        <div class="amount">{{ number_format(auth()->user()->coins / 1000, 2) }} TK</div>
        <div class="coins">{{ number_format(auth()->user()->coins) }} (coins)</div>
        <div class="balance-label">Available Balance</div>
        <div class="buttons">
            <a href="{{ route('withdraw.form') }}" class="btn withdraw">Withdraw</a>
            <a href="#" class="btn deposit">Deposit</a>
        </div>
    </div>
    
    <div class="help-box">
        <p class="help-title">Need Help?</p>
        <p class="help-text">Have questions or concerns regarding your account?</p>
        <a href="{{ route('support.create') }}" class="chat-btn">CHAT WITH US</a>
    </div>

    <div class="menu-list">
        <a href="{{ route('user.settings.account') }}" class="menu-btn {{ request()->routeIs('user.settings.account') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="mr-2" viewBox="0 0 16 16">
                <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
            </svg> Account
        </a>
        <a href="{{ route('user.settings.notifications') }}" class="menu-btn {{ request()->routeIs('user.settings.notifications') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="mr-2" viewBox="0 0 16 16">
                <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zm.995-14.901a1 1 0 1 0-1.99 0A5.002 5.002 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901z"/>
            </svg> Notifications
        </a>
        <a href="{{ route('user.settings.appearance') }}" class="menu-btn {{ request()->routeIs('user.settings.appearance') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="mr-2" viewBox="0 0 16 16">
                <path d="M8 5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zm4 3a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zM5.5 7a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm.5 6a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/>
                <path d="M16 8c0 3.15-1.866 2.585-3.567 2.07C11.42 9.763 10.465 9.473 10 10c-.603.683-.475 1.819-.351 2.92C9.826 14.495 9.996 16 8 16a8 8 0 1 1 8-8zm-8 7c.611 0 .654-.171.655-.176.078-.146.124-.464.07-1.119-.014-.168-.037-.37-.061-.591-.052-.464-.112-1.005-.118-1.462-.01-.707.083-1.61.704-2.314.369-.417.845-.578 1.272-.618.404-.038.812.026 1.16.104.343.077.702.186 1.025.284l.028.008c.346.105.658.199.953.266.653.148.904.083.991.024C14.717 9.38 15 9.161 15 8a7 7 0 1 0-7 7z"/>
            </svg> Appearance
        </a>
        <a href="{{ route('support.create') }}" class="menu-btn {{ request()->routeIs('support.create') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="mr-2" viewBox="0 0 16 16">
                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                <path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
            </svg> Help and Support
        </a>
        <a href="{{ route('user.settings.about') }}" class="menu-btn {{ request()->routeIs('user.settings.about') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="mr-2" viewBox="0 0 16 16">
                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
            </svg> About
        </a>
        <a href="{{ route('referrals.index') }}" class="menu-btn {{ request()->routeIs('referrals.index') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="mr-2" viewBox="0 0 16 16">
                <path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-5 6s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H1zM11 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5zm.5 2.5a.5.5 0 0 0 0 1h4a.5.5 0 0 0 0-1h-4zm2 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1h-2zm0 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1h-2z"/>
            </svg> Refer & Earn
        </a>
    </div>
    
    <form method="POST" action="{{ route('logout') }}" class="mt-4">
        @csrf
        <button type="submit" class="menu-btn2 logout">
          Logout
        </button>
    </form>
</aside>



























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
        <a href="javascript:void(0)" role="button" onclick="toggleMobileMenu()" class="text-center text-xs text-gray-700">
            <div class="text-xl mb-1">👤</div>
            <div>Menu</div>
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
<style>
/* Add overlay styling */
.sidebar-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0,0,0,0.6);
    z-index: 999;
}

.mobile-menu-toggle {
    display: none;
}

.mobile-close {
    display: none;
    text-align: right;
    font-size: 24px;
    margin-bottom: 15px;
    cursor: pointer;
}
html.dark .sidebar {
  background-color:rgb(27, 27, 27) !important;
}
html.dark .balance-box {
  background-color:rgb(27, 27, 27) !important;
}
html.dark .menu-btn.active {
  background-color:rgb(27, 27, 27) !important;
}
html.dark .menu-btn {
  background-color:rgb(27, 27, 27) !important;
  color:white !important;
}



.sidebar {
    width: 260px;
    background-color: #fff;
    padding: 20px;
    border-right: 1px solid #ddd;
}

.balance-box {
    text-align: center;
    padding: 15px;
    border: 1px solid #eee;
    border-radius: 10px;
    background: #fafafa;
}

.icon {
    width: 50px;
    height: 40px;
    background: #ccc;
    margin: 0 auto 10px;
    border-radius: 6px;
}

.amount {
    font-size: 24px;
    font-weight: bold;
}

.coins {
    font-size: 12px;
    color: #666;
}

.balance-label {
    font-size: 13px;
    margin-top: 4px;
    color: #aaa;
}

.buttons {
    margin-top: 10px;
    display: flex;
    justify-content: center;
    gap: 10px;
}

.btn {
    padding: 8px 16px;
    font-size: 12px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    display: inline-block;
    text-decoration: none;
    text-align: center;
    flex: 1;
}

.withdraw {
    background-color: #5d4fff;
    color: #000;
}

.deposit {
    background-color: #5d4fff;
    color: #fff;
}

.help-box {
    margin-top: 40px;
    text-align: center;
}

.help-title {
    font-size: 18px;
    font-weight: bold;
}

.help-text {
    font-size: 13px;
    margin: 10px 0;
    color: #777;
}

.chat-btn {
    background-color: #5d4fff;
    color: #000;
    padding: 10px 20px;
    border: none;
    font-weight: bold;
    border-radius: 5px;
    cursor: pointer;
    display: inline-block;
    text-decoration: none;
    width: 100%;
    box-sizing: border-box;
}

.menu-list {
    margin: 30px 0;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.menu-btn {
    background-color: #fff;
    border: 1px solid #eee;
    padding: 12px 15px;
    font-size: 14px;
    text-align: left;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
    color: #333;
    display: block;
}

.menu-btn2 {
    background-color: red;
  border: 1px solid #f00;
  padding: 2px 25px;
  font-size: 17px;
  text-align: center;
  border-radius: 2px;
  cursor: pointer;
  transition: all 0.2s ease;
  text-decoration: none;
  color: #fff;
  display: block;
}




.menu-btn i {
    width: 20px;
    display: inline-block;
    text-align: center;
    margin-right: 8px;
}

.menu-btn:hover {
    background-color: #f7f7f7;
}

.menu-btn.active {
    background-color: #f0f0f0;
    border-left: 3px solid #5d4fff;
    font-weight: bold;
}

.menu-btn.logout {
    color: red;
    font-weight: bold;
    border-color: #ffdddd;
    margin-top: 10px;
    text-align: center;
}

.mr-2 {
    margin-right: 8px;
}

/* Mobile responsive styles */
@media (max-width: 768px) {
    .mobile-menu-toggle {
        display: flex;
    }
    
    .sidebar {
        position: fixed;
        left: -300px;
        top: 0;
        height: 100vh;
        z-index: 9999; /* Higher z-index to appear above everything */
        transition: left 0.3s ease;
        overflow-y: auto;
        width: 80%;
        max-width: 290px;
        box-shadow: 0 0 15px rgba(0,0,0,0.2);
        background-color: #fff;
        padding-bottom: 100px; /* Extra padding to account for bottom navigation */
    }
    
    .sidebar.active {
        left: 0;
    }
    
    .mobile-close {
        display: block;
    }
    
    .container {
        flex-direction: column;
    }
    
    .content {
        width: 100%;
        padding: 15px;
        margin-bottom: 70px; /* Add space for bottom navigation */
    }
}
</style>

<script>
function toggleMobileMenu() {
    console.log('Toggle menu called');
    const sidebar = document.getElementById('settingsSidebar');
    const overlay = document.getElementById('sidebarOverlay');
    
    if (sidebar) {
        sidebar.classList.toggle('active');
        
        // Make sure sidebar is visible when active
        if (sidebar.classList.contains('active')) {
            sidebar.style.display = 'block';
            if (overlay) overlay.style.display = 'block';
            document.body.style.overflow = 'hidden'; // Prevent scrolling behind sidebar
        } else {
            if (overlay) overlay.style.display = 'none';
            setTimeout(() => {
                if (!sidebar.classList.contains('active')) {
                    sidebar.style.display = '';
                }
            }, 300); // Wait for transition to complete
            document.body.style.overflow = '';
        }
    } else {
        console.error('Sidebar element not found');
    }
}

// Execute after DOM is fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Close menu when clicking outside on mobile
    document.addEventListener('click', function(event) {
        const sidebar = document.getElementById('settingsSidebar');
        const toggleButton = document.querySelector('.mobile-menu-toggle');
        const toggleButtonBottom = document.querySelector('.text-center.text-xs[onclick="toggleMobileMenu()"]');
        const overlay = document.getElementById('sidebarOverlay');
        
        if (sidebar && sidebar.classList.contains('active') && 
            !sidebar.contains(event.target) && 
            !toggleButton?.contains(event.target) &&
            (!toggleButtonBottom || !toggleButtonBottom.contains(event.target)) &&
            event.target !== overlay) {
            toggleMobileMenu();
        }
    });
});
</script> 