@extends('admin.layouts.app')

@section('title', 'Popular Rewards Analytics')
@section('header', 'Popular Rewards Analytics')

@section('content')
<div class="space-y-6">
    <!-- Reward Distribution -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Reward Distribution</h3>
            <p class="mt-1 text-sm text-gray-500">Frequency of different rewards being won.</p>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <div class="h-80">
                <!-- This would typically be a chart -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">Reward</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Times Won</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Percentage</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Coins Awarded</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @php 
                                $totalSpins = $rewardDistribution->sum('count'); 
                            @endphp
                            @forelse($rewardDistribution as $reward)
                            <tr>
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900">{{ $reward->reward->name ?? 'Unknown' }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ number_format($reward->count) }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ number_format(($reward->count / max(1, $totalSpins)) * 100, 1) }}%</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ number_format($reward->reward->coins_value ?? 0 * $reward->count) }}</td>
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
    </div>

    <!-- Daily Reward Trends -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Daily Reward Trends</h3>
            <p class="mt-1 text-sm text-gray-500">Which rewards are being won each day (last 30 days).</p>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <div class="h-80">
                <!-- This would typically be a chart -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">Date</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Most Popular Reward</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Occurrences</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Total Rewards Won</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($dailyRewards as $date => $rewards)
                            @php
                                // Find the most popular reward for this day
                                $mostPopular = $rewards->sortByDesc('count')->first();
                                $totalForDay = $rewards->sum('count');
                            @endphp
                            <tr>
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm text-gray-900">{{ \Carbon\Carbon::parse($date)->format('M d, Y') }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $mostPopular->reward->name ?? 'Unknown' }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ number_format($mostPopular->count) }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ number_format($totalForDay) }}</td>
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
    </div>

    <!-- Configuration Link -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-indigo-100 rounded-md p-3">
                    <i class="fas fa-cog text-indigo-500 text-xl"></i>
                </div>
                <div class="ml-5">
                    <h4 class="text-lg font-medium text-gray-900">Configure Spin Rewards</h4>
                    <p class="text-gray-500">Adjust the probabilities and values of rewards</p>
                </div>
                <div class="ml-auto">
                    <a href="{{ route('admin.spin-config.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-dharmachakra mr-2"></i>
                        Spin Configuration
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Back to Dashboard -->
    <div class="mt-6">
        <a href="{{ route('admin.analytics.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Analytics Dashboard
        </a>
    </div>
</div>
@endsection 