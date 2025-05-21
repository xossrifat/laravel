@extends('admin.layouts.app')

@section('title', 'Settings')
@section('header', 'Application Settings')

@section('content')
<div class="space-y-6">
    <!-- Site Identity -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Site Identity</h3>
            <p class="mt-1 text-sm text-gray-500">Update your site name and favicon.</p>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <div class="grid grid-cols-1 gap-6 mb-6">
                <!-- General Site Settings -->
                <form action="{{ route('admin.settings.general-settings.update') }}" method="POST" class="border-b pb-6">
                    @csrf
                    <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6 sm:col-span-4">
                            <label for="site_title" class="block text-sm font-medium text-gray-700">Site Title</label>
                            <input type="text" name="site_title" id="site_title" value="{{ $settings['site_title'] }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            <p class="mt-2 text-xs text-gray-500">Used for site title tag and SEO</p>
                        </div>
                        <div class="col-span-6 sm:col-span-4">
                            <label for="site_description" class="block text-sm font-medium text-gray-700">Site Description</label>
                            <textarea name="site_description" id="site_description" rows="3" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ $settings['site_description'] }}</textarea>
                            <p class="mt-2 text-xs text-gray-500">Used for meta description and SEO</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Update Site Settings
                        </button>
                    </div>
                </form>

                <!-- App Name Form -->
                <form action="{{ route('admin.settings.app-name.update') }}" method="POST" class="border-b pb-6">
                    @csrf
                    <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6 sm:col-span-4">
                            <label for="app_name" class="block text-sm font-medium text-gray-700">App Name</label>
                            <input type="text" name="app_name" id="app_name" value="{{ $settings['app_name'] }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Update App Name
                        </button>
                    </div>
                </form>

                <!-- Favicon Form -->
                <form action="{{ route('admin.settings.favicon.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6 sm:col-span-4">
                            <label for="favicon" class="block text-sm font-medium text-gray-700">Favicon</label>
                            <div class="mt-2 flex items-center">
                                <span class="mr-4">
                                    <img src="/{{ $settings['favicon'] ?? 'favicon.ico' }}" alt="Current Favicon" class="w-8 h-8">
                                </span>
                                <input type="file" name="favicon" id="favicon" class="focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>
                            <p class="mt-2 text-xs text-gray-500">Upload a .ico, .png, or .jpg file (max 2MB)</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Update Favicon
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Coin Rate -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Coin Rate Configuration</h3>
            <p class="mt-1 text-sm text-gray-500">Define how many coins convert to real money.</p>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <form action="{{ route('admin.settings.coin-rate.update') }}" method="POST">
                @csrf
                <div class="grid grid-cols-6 gap-6">
                    <div class="col-span-6 sm:col-span-3">
                        <label for="coin_rate" class="block text-sm font-medium text-gray-700">Coins per 1 BDT</label>
                        <input type="number" name="coin_rate" id="coin_rate" value="{{ $settings['coin_rate'] }}" min="1" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        <p class="mt-2 text-sm text-gray-500">Example: 1000 coins = 1 BDT</p>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Update Coin Rate
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Redemption Conditions -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Redemption Conditions</h3>
            <p class="mt-1 text-sm text-gray-500">Set minimum withdraw amounts and other conditions.</p>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <form action="{{ route('admin.settings.redeem-conditions.update') }}" method="POST">
                @csrf
                <div class="grid grid-cols-6 gap-6">
                    <div class="col-span-6 sm:col-span-3">
                        <label for="min_withdraw_amount" class="block text-sm font-medium text-gray-700">Minimum Withdrawal Amount (BDT)</label>
                        <input type="number" name="min_withdraw_amount" id="min_withdraw_amount" value="{{ $settings['min_withdraw_amount'] }}" min="1" step="0.01" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        <p class="mt-2 text-sm text-gray-500">Minimum amount users can withdraw at once</p>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Update Redemption Conditions
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- System Limits -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">System Limits</h3>
            <p class="mt-1 text-sm text-gray-500">Configure daily limits for spins and video watches.</p>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <form action="{{ route('admin.settings.limits.update') }}" method="POST">
                @csrf
                <div class="grid grid-cols-6 gap-6">
                    <div class="col-span-6 sm:col-span-3">
                        <label for="daily_spin_limit" class="block text-sm font-medium text-gray-700">Daily Spin Limit</label>
                        <input type="number" name="daily_spin_limit" id="daily_spin_limit" value="{{ $settings['daily_spin_limit'] ?? 5 }}" min="1" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        <p class="mt-2 text-sm text-gray-500">Maximum spins a user can perform per day</p>
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <label for="daily_video_limit" class="block text-sm font-medium text-gray-700">Daily Video Watch Limit</label>
                        <input type="number" name="daily_video_limit" id="daily_video_limit" value="{{ $settings['daily_video_limit'] ?? 10 }}" min="1" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        <p class="mt-2 text-sm text-gray-500">Maximum videos a user can watch per day</p>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Update System Limits
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Referral System -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Referral System</h3>
            <p class="mt-1 text-sm text-gray-500">Configure referral rewards and settings.</p>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <form action="{{ route('admin.settings.referral.update') }}" method="POST">
                @csrf
                <div class="grid grid-cols-6 gap-6">
                    <div class="col-span-6 sm:col-span-3">
                        <label for="referral_reward" class="block text-sm font-medium text-gray-700">Referral Reward (Coins)</label>
                        <input type="number" name="referral_reward" id="referral_reward" value="{{ $settings['referral_reward'] ?? 100 }}" min="1" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        <p class="mt-2 text-sm text-gray-500">Coins awarded to users when someone signs up using their referral link</p>
                    </div>
                    
                    <div class="col-span-6 sm:col-span-3">
                        <label for="referral_percentage" class="block text-sm font-medium text-gray-700">Referral Percentage (%)</label>
                        <input type="number" name="referral_percentage" id="referral_percentage" value="{{ $settings['referral_percentage'] ?? 5 }}" min="0" max="100" step="0.01" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        <p class="mt-2 text-sm text-gray-500">Percentage of referred user's earnings to be awarded to the referrer</p>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Update Referral Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Maintenance Mode -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Maintenance Mode</h3>
            <p class="mt-1 text-sm text-gray-500">Enable or disable maintenance mode for the entire application.</p>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <form action="{{ route('admin.settings.maintenance.toggle') }}" method="POST">
                @csrf
                <div class="flex items-center">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="maintenance_mode" id="maintenance_mode" value="1" class="sr-only peer" {{ $settings['maintenance_mode'] == 'true' ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        <span class="ml-3 text-sm font-medium text-gray-700">Enable Maintenance Mode</span>
                    </label>
                </div>
                <p class="mt-2 text-sm text-gray-500">When enabled, users will see a maintenance message and cannot access the app.</p>
                <div class="mt-4">
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Update Maintenance Mode
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Notifications -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Send Notification</h3>
            <p class="mt-1 text-sm text-gray-500">Send a notification to users of the application.</p>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <form action="{{ route('admin.settings.notifications.send') }}" method="POST">
                @csrf
                <div class="grid grid-cols-6 gap-6">
                    <div class="col-span-6">
                        <label for="title" class="block text-sm font-medium text-gray-700">Notification Title</label>
                        <input type="text" name="title" id="title" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>
                    <div class="col-span-6">
                        <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
                        <textarea name="message" id="message" rows="3" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <label for="send_to" class="block text-sm font-medium text-gray-700">Send To</label>
                        <select id="send_to" name="send_to" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="all">All Users</option>
                            <option value="active">Active Users (logged in last 30 days)</option>
                            <option value="inactive">Inactive Users (not logged in last 30 days)</option>
                        </select>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Send Notification
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Generate Referral Codes -->
    <div class="mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Generate Referral Codes for Existing Users</h5>
            </div>
            <div class="card-body">
                <p>This will generate referral codes for any users who don't have one yet. Use this if you have users that were created before the referral system was implemented.</p>
                <a href="{{ route('admin.update-referral-codes') }}" class="btn btn-primary">
                    <i class="fas fa-sync-alt me-2"></i> Generate Missing Referral Codes
                </a>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Modern toggle styles are included inline, no additional styles needed */
</style>
@endpush
@endsection 