@extends('admin.layouts.app')

@section('title', 'Adsterra Monitor')
@section('header', 'Adsterra Monitor')

@section('content')
<div class="space-y-6">
    <!-- Today's Stats -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <dt class="text-sm font-medium text-gray-500 truncate">Video Views Today</dt>
                <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ number_format($videoWatchesToday) }}</dd>
            </div>
        </div>
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <dt class="text-sm font-medium text-gray-500 truncate">Unique Viewers Today</dt>
                <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ number_format($uniqueViewersToday) }}</dd>
            </div>
        </div>
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <dt class="text-sm font-medium text-gray-500 truncate">Coins Awarded Today</dt>
                <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ number_format($coinsAwardedToday) }}</dd>
            </div>
        </div>
    </div>

    <!-- Weekly Stats -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Weekly Performance</h3>
            <p class="mt-1 text-sm text-gray-500">Video watch statistics for the past week.</p>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <div class="relative">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">Date</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Total Views</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Unique Viewers</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Coins Awarded</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($weeklyStats as $stat)
                        <tr>
                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm text-gray-500">{{ \Carbon\Carbon::parse($stat->date)->format('M d, Y') }}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ number_format($stat->total_views) }}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ number_format($stat->unique_viewers) }}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ number_format($stat->coins_awarded) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-3 py-4 text-sm text-gray-500 text-center">No data available for this week</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Current Configuration -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Current Adsterra Configuration</h3>
            <p class="mt-1 text-sm text-gray-500">Your active Adsterra integration settings.</p>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Publisher ID</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $settings['adsterra_publisher_id'] ?? 'Not configured' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Banner Ad Zone ID</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $settings['adsterra_banner_id'] ?? 'Not configured' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Interstitial Ad Zone ID</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $settings['adsterra_interstitial_id'] ?? 'Not configured' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Social Bar Ad Zone ID</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $settings['adsterra_social_id'] ?? 'Not configured' }}</dd>
                </div>
            </dl>
            <div class="mt-6">
                <a href="{{ route('admin.watch-earn.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-cog mr-2"></i>
                    Update Configuration
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
        <a href="{{ route('admin.adsterra.reports') }}" class="bg-white overflow-hidden shadow rounded-lg hover:bg-gray-50">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-chart-bar text-indigo-600 text-2xl"></i>
                    </div>
                    <div class="ml-5">
                        <h3 class="text-lg font-medium text-gray-900">Detailed Reports</h3>
                        <p class="text-sm text-gray-500">View monthly and yearly statistics</p>
                    </div>
                </div>
            </div>
        </a>
        <a href="{{ route('admin.adsterra.earnings') }}" class="bg-white overflow-hidden shadow rounded-lg hover:bg-gray-50">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-dollar-sign text-green-600 text-2xl"></i>
                    </div>
                    <div class="ml-5">
                        <h3 class="text-lg font-medium text-gray-900">Earnings</h3>
                        <p class="text-sm text-gray-500">Track your Adsterra revenue</p>
                    </div>
                </div>
            </div>
        </a>
        <a href="{{ route('admin.adsterra.user-stats') }}" class="bg-white overflow-hidden shadow rounded-lg hover:bg-gray-50">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-users text-blue-600 text-2xl"></i>
                    </div>
                    <div class="ml-5">
                        <h3 class="text-lg font-medium text-gray-900">User Stats</h3>
                        <p class="text-sm text-gray-500">Analyze user engagement</p>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection 