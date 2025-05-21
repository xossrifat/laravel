@extends('admin.layouts.app')

@section('title', 'Adsterra User Statistics')
@section('header', 'Adsterra User Statistics')

@section('content')
<div class="space-y-6">
    <!-- Top Viewers -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Top Video Viewers</h3>
            <p class="mt-1 text-sm text-gray-500">Users who have watched the most ad videos.</p>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">User</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Views</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Coins Earned</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Avg. Reward</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($topViewers as $viewer)
                        <tr>
                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900">
                                <a href="{{ route('admin.users.coins', $viewer->user->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                    {{ $viewer->user->name }}
                                </a>
                                <span class="block text-xs text-gray-500">{{ $viewer->user->email }}</span>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ number_format($viewer->total_views) }}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ number_format($viewer->total_coins) }}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                {{ number_format($viewer->total_views > 0 ? $viewer->total_coins / $viewer->total_views : 0, 1) }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-3 py-4 text-sm text-gray-500 text-center">No data available</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Viewing Times -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Viewing Time Distribution</h3>
            <p class="mt-1 text-sm text-gray-500">When users are most active watching videos.</p>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <div class="h-64">
                <!-- This would typically be a chart. For simplicity, displaying a table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">Hour</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Views</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Percentage</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @php
                                $totalViews = $viewTimeDistribution->sum('views');
                            @endphp
                            @forelse($viewTimeDistribution as $time)
                            <tr>
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm text-gray-900">
                                    {{ sprintf('%02d:00', $time->hour) }} - {{ sprintf('%02d:00', ($time->hour + 1) % 24) }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    {{ number_format($time->views) }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    {{ number_format(($time->views / max(1, $totalViews)) * 100, 1) }}%
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-3 py-4 text-sm text-gray-500 text-center">No data available</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Back to Dashboard -->
    <div class="mt-6">
        <a href="{{ route('admin.adsterra.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Adsterra Dashboard
        </a>
    </div>
</div>
@endsection 