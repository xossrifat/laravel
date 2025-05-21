@extends('admin.layouts.app')

@section('title', 'Video Analytics')
@section('header', 'Video Analytics')

@section('content')
<div class="space-y-6">
    <!-- Daily Video Watches -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Daily Video Watches</h3>
            <p class="mt-1 text-sm text-gray-500">Video watches per day over the last 30 days.</p>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <div class="h-80">
                <!-- This would typically be a chart -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">Date</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Videos Watched</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($daily as $data)
                            <tr>
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm text-gray-900">{{ \Carbon\Carbon::parse($data->date)->format('M d, Y') }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ number_format($data->count) }}</td>
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

    <!-- Monthly Video Watches -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Monthly Video Watches</h3>
            <p class="mt-1 text-sm text-gray-500">Videos watched and coins earned by month over the last year.</p>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <div class="h-80">
                <!-- This would typically be a chart -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">Month</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Videos Watched</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Coins Earned</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Avg. Reward</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($monthly as $data)
                            <tr>
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm text-gray-900">{{ \Carbon\Carbon::createFromDate($data->year, $data->month, 1)->format('F Y') }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ number_format($data->count) }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ number_format($data->total_coins) }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ number_format($data->count > 0 ? $data->total_coins / $data->count : 0, 1) }}</td>
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

    <!-- Top Video Watchers -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Top Video Watchers</h3>
            <p class="mt-1 text-sm text-gray-500">Users who have watched the most videos (last 30 days).</p>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <div class="h-80">
                <!-- This would typically be a chart -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">User</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Videos Watched</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Percentage of Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @php 
                                $totalVideos = $userDistribution->sum('count'); 
                            @endphp
                            @forelse($userDistribution as $user)
                            <tr>
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900">
                                    <a href="{{ route('admin.users.videos', $user->user_id) }}" class="text-indigo-600 hover:text-indigo-900">
                                        {{ $user->user->name ?? 'Unknown' }}
                                    </a>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ number_format($user->count) }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ number_format(($user->count / max(1, $totalVideos)) * 100, 1) }}%</td>
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
        <a href="{{ route('admin.analytics.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Analytics Dashboard
        </a>
    </div>
</div>
@endsection 