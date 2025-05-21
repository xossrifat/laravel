<!-- Sidebar with balance and help box -->
<aside class="sidebar">
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
        <a href="{{ route('user.settings.account') }}" class="menu-btn {{ request()->routeIs('user.settings.account') ? 'active' : '' }}">Account Settings</a>
        <a href="{{ route('user.settings.notifications') }}" class="menu-btn {{ request()->routeIs('user.settings.notifications') ? 'active' : '' }}">Notification Settings</a>
        <a href="{{ route('user.settings.appearance') }}" class="menu-btn {{ request()->routeIs('user.settings.appearance') ? 'active' : '' }}">Appearance</a>
        <a href="{{ route('user.settings.privacy') }}" class="menu-btn {{ request()->routeIs('user.settings.privacy') ? 'active' : '' }}">Privacy</a>
        <a href="{{ route('referrals.index') }}" class="menu-btn {{ request()->routeIs('referrals.index') ? 'active' : '' }}">Referrals</a>
    </div>
    
    <form method="POST" action="{{ route('logout') }}" class="mt-4">
        @csrf
        <button type="submit" class="menu-btn logout">Logout</button>
    </form>
</aside>

<!-- Mobile Verification Card -->
<div class="card mb-4">
    <div class="card-header">
        <h6 class="mb-0">
            <i class="fas fa-mobile-alt me-2"></i> Mobile Verification
            @if(auth()->user()->mobile_verified_at)
                <span class="badge bg-success ms-2">Verified</span>
            @else
                <span class="badge bg-warning ms-2">Not Verified</span>
            @endif
        </h6>
    </div>
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-6">
                <p class="mb-0">
                    <strong>Mobile Number:</strong> 
                    @if(auth()->user()->mobile)
                        {{ auth()->user()->mobile }}
                    @else
                        <span class="text-muted">Not set</span>
                    @endif
                </p>
                <p class="small text-muted mt-2 mb-0">
                    <i class="fas fa-info-circle me-1"></i> 
                    Verified mobile number is required for withdrawals and other security-sensitive operations.
                </p>
            </div>
            <div class="col-md-6 text-md-end mt-3 mt-md-0">
                @if(auth()->user()->mobile && auth()->user()->mobile_verified_at)
                    <a href="{{ route('verification.mobile.form') }}" class="btn btn-secondary">
                        <i class="fas fa-edit me-1"></i> Update Number
                    </a>
                @else
                    <a href="{{ route('verification.mobile.form') }}" class="btn btn-primary">
                        <i class="fas fa-check-circle me-1"></i> 
                        {{ auth()->user()->mobile ? 'Verify Now' : 'Add & Verify' }}
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
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
    background-color: #f7c700;
    color: #000;
}

.deposit {
    background-color: #f8b500;
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
    background-color: #f7c700;
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
    padding: 10px;
    font-size: 14px;
    text-align: left;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
    color: #333;
    display: block;
}

.menu-btn:hover {
    background-color: #f7f7f7;
}

.menu-btn.active {
    background-color: #f0f0f0;
    border-left: 3px solid #f7c700;
}

.menu-btn.logout {
    color: red;
    font-weight: bold;
    border-color: #ffdddd;
    margin-top: 10px;
    text-align: center;
}
</style> 