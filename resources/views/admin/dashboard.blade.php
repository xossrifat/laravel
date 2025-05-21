@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('header', 'Dashboard Overview')

@section('content')
@if(isset($error_message))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
        <p>{{ $error_message }}</p>
        <p class="text-sm">The dashboard may show placeholder values. Please check server logs for details.</p>
    </div>
@endif

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <!-- Total Users -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                    <i class="fas fa-users text-white text-2xl"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Users</dt>
                        <dd class="text-3xl font-semibold text-gray-900">{{ $stats['total_users'] }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Users Today -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                    <i class="fas fa-user-check text-white text-2xl"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Active Users Today</dt>
                        <dd class="text-3xl font-semibold text-gray-900">{{ $stats['active_users_today'] }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Spins Today -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                    <i class="fas fa-dharmachakra text-white text-2xl"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Spins Today</dt>
                        <dd class="text-3xl font-semibold text-gray-900">{{ $stats['total_spins_today'] }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Withdrawals -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-red-500 rounded-md p-3">
                    <i class="fas fa-money-bill-wave text-white text-2xl"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Pending Withdrawals</dt>
                        <dd class="text-3xl font-semibold text-gray-900">{{ $stats['pending_withdrawals'] }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Adsterra Banner Ad -->
<div class="bg-white shadow rounded-lg p-4 mb-8">
    <div class="flex justify-center" id="adsterra-container">
        <!-- Adsterra Ad loading message -->
        <p class="text-sm text-gray-500">Loading advertisement...</p>
    </div>
</div>

<script type="text/javascript">
    // Wait for document to be fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Create script element for Adsterra options
        var optionsScript = document.createElement('script');
        optionsScript.type = 'text/javascript';
        optionsScript.text = `
            atOptions = {
                'key' : 'd007ce8d8904632b8c54dbc4807c29a0',
                'format' : 'iframe',
                'height' : 60,
                'width' : 468,
                'params' : {}
            };
        `;
        document.getElementById('adsterra-container').appendChild(optionsScript);
        
        // Create script element for Adsterra invoke
        var invokeScript = document.createElement('script');
        invokeScript.type = 'text/javascript';
        invokeScript.src = 'https://www.highperformanceformat.com/d007ce8d8904632b8c54dbc4807c29a0/invoke.js';
        document.getElementById('adsterra-container').appendChild(invokeScript);
        
        // Clear loading message when scripts are added
        document.getElementById('adsterra-container').querySelector('p').remove();
    });
</script>

<!-- Recent Activity and Charts -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Recent Withdrawals -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Recent Withdrawal Requests
            </h3>
        </div>
        <div class="p-4">
            <div class="flow-root">
                <ul role="list" class="-mb-8">
                    @forelse($recent_withdrawals as $withdrawal)
                    <li>
                        <div class="relative pb-8">
                            <div class="relative flex space-x-3">
                                <div>
                                    <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                        <i class="fas fa-money-bill-wave text-white"></i>
                                    </span>
                                </div>
                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                    <div>
                                        <p class="text-sm text-gray-500">
                                            Withdrawal request by <span class="font-medium text-gray-900">{{ $withdrawal->user->name }}</span>
                                            <span class="font-medium text-indigo-600">৳{{ $withdrawal->amount }}</span>
                                        </p>
                                    </div>
                                    <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                        <time>{{ $withdrawal->created_at->diffForHumans() }}</time>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    @empty
                    <li class="py-4 text-center text-gray-500">
                        No recent withdrawal requests
                    </li>
                    @endforelse
                </ul>
            </div>
            <div class="mt-6">
                <a href="{{ route('admin.transactions.requests') }}" class="text-indigo-600 hover:text-indigo-900">View all requests →</a>
            </div>
        </div>
    </div>

    <!-- Recent Users -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Recent Users
            </h3>
        </div>
        <div class="p-4">
            <div class="flow-root">
                <ul role="list" class="divide-y divide-gray-200">
                    @forelse($recent_users as $user)
                    <li class="py-4">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <span class="h-8 w-8 rounded-full bg-gray-400 flex items-center justify-center">
                                    <i class="fas fa-user text-white"></i>
                                </span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">
                                    {{ $user->name }}
                                </p>
                                <p class="text-sm text-gray-500 truncate">
                                    {{ $user->email }}
                                </p>
                            </div>
                            <div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->is_banned ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $user->is_banned ? 'Banned' : 'Active' }}
                                </span>
                            </div>
                        </div>
                    </li>
                    @empty
                    <li class="py-4 text-center text-gray-500">
                        No users found
                    </li>
                    @endforelse
                </ul>
            </div>
            <div class="mt-6">
                <a href="{{ route('admin.users.index') }}" class="text-indigo-600 hover:text-indigo-900">View all users →</a>
            </div>
        </div>
    </div>
</div>
@endsection