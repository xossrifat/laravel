@extends('admin.layouts.app')

@section('title', 'Analytics Dashboard')
@section('header', 'Analytics Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Summary Stats -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <dt class="text-sm font-medium text-gray-500 truncate">Total Users</dt>
                <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ number_format($totalUsers) }}</dd>
                <p class="mt-2 text-sm text-gray-500">{{ number_format($newUsersThisMonth) }} new this month</p>
            </div>
        </div>
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <dt class="text-sm font-medium text-gray-500 truncate">Total Spins</dt>
                <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ number_format($totalSpins) }}</dd>
                <p class="mt-2 text-sm text-gray-500">{{ number_format($spinsThisMonth) }} this month</p>
            </div>
        </div>
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <dt class="text-sm font-medium text-gray-500 truncate">Total Videos Watched</dt>
                <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ number_format($totalVideos) }}</dd>
                <p class="mt-2 text-sm text-gray-500">{{ number_format($videosThisMonth) }} this month</p>
            </div>
        </div>
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <dt class="text-sm font-medium text-gray-500 truncate">Total Money Paid</dt>
                <dd class="mt-1 text-3xl font-semibold text-gray-900">৳{{ number_format($totalPaid) }}</dd>
                <p class="mt-2 text-sm text-gray-500">৳{{ number_format($pendingAmount) }} pending</p>
            </div>
        </div>
    </div>

    <!-- User Activity -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">User Activity</h3>
            <p class="mt-1 text-sm text-gray-500">Daily user logins for the past 30 days.</p>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <div class="h-64 relative">
                <!-- This would typically be a chart -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">Date</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Active Users</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($dailyUserActivity as $activity)
                            <tr>
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm text-gray-900">{{ \Carbon\Carbon::parse($activity->date)->format('M d, Y') }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ number_format($activity->count) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="px-3 py-4 text-sm text-gray-500 text-center">No data available</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Spin Activity -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Spin Activity</h3>
            <p class="mt-1 text-sm text-gray-500">Daily spins for the past 30 days.</p>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <div class="h-64 relative">
                <!-- This would typically be a chart -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">Date</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Spins</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($dailySpinActivity as $activity)
                            <tr>
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm text-gray-900">{{ \Carbon\Carbon::parse($activity->date)->format('M d, Y') }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ number_format($activity->count) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="px-3 py-4 text-sm text-gray-500 text-center">No data available</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
        <a href="{{ route('admin.analytics.active-users') }}" class="bg-white overflow-hidden shadow rounded-lg hover:bg-gray-50">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-users text-blue-600 text-2xl"></i>
                    </div>
                    <div class="ml-5">
                        <h3 class="text-lg font-medium text-gray-900">Active Users</h3>
                        <p class="text-sm text-gray-500">Analyze user engagement</p>
                    </div>
                </div>
            </div>
        </a>
        <a href="{{ route('admin.analytics.spins') }}" class="bg-white overflow-hidden shadow rounded-lg hover:bg-gray-50">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-dharmachakra text-indigo-600 text-2xl"></i>
                    </div>
                    <div class="ml-5">
                        <h3 class="text-lg font-medium text-gray-900">Spin Statistics</h3>
                        <p class="text-sm text-gray-500">Analyze spin activity and rewards</p>
                    </div>
                </div>
            </div>
        </a>
        <a href="{{ route('admin.analytics.earnings') }}" class="bg-white overflow-hidden shadow rounded-lg hover:bg-gray-50">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-money-bill-wave text-green-600 text-2xl"></i>
                    </div>
                    <div class="ml-5">
                        <h3 class="text-lg font-medium text-gray-900">Earnings</h3>
                        <p class="text-sm text-gray-500">Track earning and withdrawal activity</p>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection 