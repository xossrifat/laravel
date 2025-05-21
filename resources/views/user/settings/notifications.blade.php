@extends('layouts.app')

@section('title', 'Notification Settings')

@section('content')
<style>
    .wheel-card {
        background: linear-gradient(to bottom right, #b8e1e9, #a1cad0);
        border-radius: 15px;
        padding: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.15);
        margin: 10px;
    }
    
    .wheel-card .card-header {
        background-color: transparent;
        color: #1a3a5c;
        font-weight: bold;
        border-bottom: none;
        text-align: center;
        font-size: 20px;
        padding: 10px;
    }
    
    .section {
        background: linear-gradient(to bottom right, #ffffff, #f8f9fa);
        margin-bottom: 20px;
        padding: 20px;
        border-radius: 10px;
        border: 1px solid #e3e3e3;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }
    
    html.dark .section {
        background: linear-gradient(to bottom right, #2d3748, #1a202c);
    }
    
    .section:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    .section-header {
        display: flex;
        justify-content: space-between;
        font-weight: bold;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eaeaea;
    }
    
    .notification-toggle {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 24px;
    }
    
    .notification-toggle input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 34px;
    }
    
    .slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }
    
    input:checked + .slider {
        background-color: #5d4fff;
    }
    
    input:checked + .slider:before {
        transform: translateX(26px);
    }
    
    .notification-description {
        font-size: 14px;
        color: #666;
        margin: 10px 0;
    }
    
    .alert-success {
        background-color: #d4edda;
        color: #155724;
        padding: 12px 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        border-left: 4px solid #28a745;
    }
    
    @media (min-width: 768px) {
        .wheel-card {
            padding: 20px;
            margin: 0 auto;
        }
    }
</style>

<div class="mobile-header">
    @include('user.settings.settings')
</div>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card wheel-card">
                <div class="card-header">Notification Settings</div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('user.settings.notifications.update') }}" method="POST">
                        @csrf
                        
                        <div class="section">
                            <div class="section-header">
                                <span>Email Notifications</span>
                            </div>
                            
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>Marketing Emails</strong>
                                        <p class="notification-description">Receive emails about new features, promotions, and updates.</p>
                                    </div>
                                    <label class="notification-toggle">
                                        <input type="checkbox" name="email_marketing" {{ auth()->user()->notify_marketing ? 'checked' : '' }}>
                                        <span class="slider"></span>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>Account Notifications</strong>
                                        <p class="notification-description">Receive emails about important account updates and security alerts.</p>
                                    </div>
                                    <label class="notification-toggle">
                                        <input type="checkbox" name="email_account" {{ auth()->user()->notify_account ? 'checked' : '' }}>
                                        <span class="slider"></span>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>Withdrawal Notifications</strong>
                                        <p class="notification-description">Receive emails when withdrawals are processed or completed.</p>
                                    </div>
                                    <label class="notification-toggle">
                                        <input type="checkbox" name="email_withdrawals" {{ auth()->user()->notify_withdrawals ? 'checked' : '' }}>
                                        <span class="slider"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="section">
                            <div class="section-header">
                                <span>Push Notifications</span>
                            </div>
                            
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>Rewards & Bonuses</strong>
                                        <p class="notification-description">Receive push notifications when you earn rewards or bonuses.</p>
                                    </div>
                                    <label class="notification-toggle">
                                        <input type="checkbox" name="push_rewards" {{ auth()->user()->notify_rewards ? 'checked' : '' }}>
                                        <span class="slider"></span>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>Daily Spin Reminders</strong>
                                        <p class="notification-description">Receive reminders when your spin is available.</p>
                                    </div>
                                    <label class="notification-toggle">
                                        <input type="checkbox" name="push_spin" {{ auth()->user()->notify_spin ? 'checked' : '' }}>
                                        <span class="slider"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                    
                    @include('layouts.banner')
                </div>
            </div>
        </div>
    </div>
    @include('layouts.social-ads')
    @include('layouts.banner')
</div>
@endsection